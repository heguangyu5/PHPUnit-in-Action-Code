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
}
