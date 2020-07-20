<?php

class OurBlog_Post
{
    protected $uid;
    
    public function __construct($uid)
    {
        $this->uid = $uid;
    }
    
    // DBAIPK short for DB Auto Increment Primary Key
    public static function DBAIPK($var)
    {
        return filter_var($var, FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
    }
    
    protected function preparePostData(array $data)
    {
        $requiredKeys = array(
            // key => required
            'categoryId' => true,
            'title'      => true,
            'content'    => false,
            'tags'       => false
        );
        foreach ($requiredKeys as $key => $required) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("missing required key $key");
            }
            $data[$key] = trim($data[$key]);
            if ($required && $data[$key] == '') {
                throw new InvalidArgumentException("$key required");
            }
        }
        // categoryId
        $data['categoryId'] = self::DBAIPK($data['categoryId']);
        if (!$data['categoryId']) {
            throw new InvalidArgumentException('invalid categoryId');
        }
        // title
        $len = mb_strlen($data['title'], 'UTF-8');
        if ($len > 500) {
            throw new InvalidArgumentException('title too long, maxlength is 500');
        }
        // content
        $len = strlen($data['content']);
        if ($len > 64000) {
            throw new InvalidArgumentException('content too long, maxlength is 64000 bytes');
        }
        // tags
        if ($data['tags']) {
            $len = mb_strlen($data['tags']);
            if ($len > 400) {
                throw new InvalidArgumentException('tags too long');
            }
            $tags = explode(',', $data['tags']);
            if (count($tags) > 10) {
                throw new InvalidArgumentException('too many tags');
            }
            $tagIdMap = array();
            foreach ($tags as $idx => $tag) {
                $tag = trim($tag);
                $len = mb_strlen($tag, 'UTF-8');
                if ($len > 30) {
                    throw new InvalidArgumentException('tag too long');
                }
                if ($len == 0) {
                    continue;
                }
                $tagIdMap[$tag] = 0;
            }
            unset($data['tags']);
            if ($tagIdMap) {
                // filter out exist tags
                $tagRows = OurBlog_Db::getInstance()->fetchAll(
                    'SELECT id, name FROM tag WHERE name IN (?' . str_repeat(', ?', count($tagIdMap) - 1) . ')',
                    array_keys($tagIdMap)
                );
                foreach ($tagRows as $row) {
                    $tagIdMap[$row['name']] = $row['id'];
                }
                $data['tagIdMap'] = $tagIdMap;
                // filter out new tags
                $newTags = array();
                foreach ($tagIdMap as $tag => $tagId) {
                    if (!$tagId) {
                        $newTags[] = $tag;
                    }
                }
                $data['newTags'] = $newTags;
            }
        }
        
        return $data;
    }
    
    public function add(array $data)
    {
        $data = $this->preparePostData($data);
        
        $db = OurBlog_Db::getInstance();
        $db->beginTransaction();
        try {
            // post
            $db->insert('post', array(
                'category_id' => $data['categoryId'],
                'title'       => $data['title'],
                'content'     => $data['content'],
                'user_id'     => $this->uid
            ));
            // tags
            if (isset($data['tagIdMap'])) {
                $postId = $db->lastInsertId();
                // tag
                if ($data['newTags']) {
                    $stmt = $db->prepare('INSERT INTO tag(name) VALUES (?)');
                    foreach ($data['newTags'] as $tag) {
                        $stmt->execute(array($tag));
                        $data['tagIdMap'][$tag] = $db->lastInsertId();
                    }
                }
                // post_tag
                $stmt = $db->prepare('INSERT INTO post_tag(post_id, tag_id) VALUES (?, ?)');
                foreach ($data['tagIdMap'] as $tagId) {
                    $stmt->execute(array($postId, $tagId));
                }
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    protected function preparePostId(array $data)
    {
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('missing required key id');
        }
        $data['id'] = self::DBAIPK($data['id']);
        if (!$data['id']) {
            throw new InvalidArgumentException('invalid id');
        }
        if (!OurBlog_Db::getInstance()->fetchOne("SELECT id FROM post WHERE id = {$data['id']} AND user_id = {$this->uid}")) {
            throw new InvalidArgumentException('id not exists or not your post');
        }

        return $data;
    }

    public function edit(array $data)
    {
        $data = self::preparePostData($data);
        $data = self::preparePostId($data);
        
        $db = OurBlog_Db::getInstance();
        if (isset($data['tagIdMap'])) {
            // get post tags from db
            $postTagIds = $db->fetchCol('SELECT tag_id FROM post_tag WHERE post_id = ' . $data['id']);
            // diff
            $tagIds = array();
            foreach ($data['tagIdMap'] as $tagId) {
                if ($tagId) {
                    $tagIds[] = $tagId;
                }
            }
            $tagIdsToBeAdded   = array_diff($tagIds, $postTagIds);
            $tagIdsToBeDeleted = array_diff($postTagIds, $tagIds);
        }
        
        $db->beginTransaction();
        try {
            // post
            $db->update('post', array(
                'category_id' => $data['categoryId'],
                'title'       => $data['title'],
                'content'     => $data['content'],
                'update_date' => date('Y-m-d H:i:s')
            ), 'id = ' . $data['id']);
            // tags
            if (isset($data['tagIdMap'])) {
                // newTags
                if ($data['newTags']) {
                    $stmtTag     = $db->prepare('INSERT INTO tag(name) VALUES (?)');
                    $stmtPostTag = $db->prepare('INSERT INTO post_tag(post_id, tag_id) VALUES (?, ?)');
                    foreach ($data['newTags'] as $tag) {
                        $stmtTag->execute(array($tag));
                        $stmtPostTag->execute(array($data['id'], $db->lastInsertId()));
                    }
                }
                // toBeAdded
                if ($tagIdsToBeAdded) {
                    if (!$data['newTags']) {
                        $stmtPostTag = $db->prepare('INSERT INTO post_tag(post_id, tag_id) VALUES (?, ?)');
                    }
                    foreach ($tagIdsToBeAdded as $tagId) {
                        $stmtPostTag->execute(array($data['id'], $tagId));
                    }
                }
                // toBeDeleted
                if ($tagIdsToBeDeleted) {
                    $db->exec('DELETE FROM post_tag WHERE post_id = ' . $data['id'] . ' AND tag_id IN (' . implode(',', $tagIdsToBeDeleted) . ')');
                }
            } else {
                $db->exec('DELETE FROM post_tag WHERE post_id = ' . $data['id']);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function delete(array $data)
    {
        $data = $this->preparePostId($data);
        
        $db = OurBlog_Db::getInstance();
        $db->beginTransaction();
        try {
            $db->exec("DELETE FROM post WHERE id = {$data['id']}");
            $db->exec("DELETE FROM post_tag WHERE post_id = {$data['id']}");
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
