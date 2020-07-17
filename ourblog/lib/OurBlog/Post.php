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
            'content'    => false
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
        
        return $data;
    }
    
    public function add(array $data)
    {
        $data = $this->preparePostData($data);
        
        OurBlog_Db::getInstance()->insert('post', array(
            'category_id' => $data['categoryId'],
            'title'       => $data['title'],
            'content'     => $data['content'],
            'user_id'     => $this->uid
        ));
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

        OurBlog_Db::getInstance()->update('post', array(
            'category_id' => $data['categoryId'],
            'title'       => $data['title'],
            'content'     => $data['content'],
            'update_date' => date('Y-m-d H:i:s')
        ), 'id = ' . $data['id']);
    }

    public function delete(array $data)
    {
        $data = $this->preparePostId($data);
        
        OurBlog_Db::getInstance()->exec("DELETE FROM post WHERE id = {$data['id']}");
    }
}
