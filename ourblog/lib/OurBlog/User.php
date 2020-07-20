<?php

class OurBlog_User
{
    const PASSWORD_SALT = 'This-salt-string-should-be-long-enough-bla-bla-bla...';

    protected static function prepareRegData(array $data)
    {
        $requiredKeys = array('email', 'username', 'password', 'confirmPassword');
        foreach ($requiredKeys as $key) {
            if (!isset($data[$key])) {
                throw new InvalidArgumentException("missing required key $key");
            }
            $data[$key] = trim($data[$key]);
            if (!$data[$key]) {
                throw new InvalidArgumentException("$key required");
            }
        }
        
        // email
        $len = strlen($data['email']);
        if ($len < 5) {
            throw new InvalidArgumentException('email too short, minlength is 5');
        }
        if ($len > 200) {
            throw new InvalidArgumentException('email too long, maxlength is 200');
        }
        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$data['email']) {
            throw new InvalidArgumentException('invalid email');
        }
        // username
        if (mb_strlen($data['username'], 'UTF-8') > 30) {
            throw new InvalidArgumentException('username too long, maxlength is 30');
        }
        // password
        $len = strlen($data['password']);
        if ($len < 6 || $len > 50) {
            throw new InvalidArgumentException('invalid password, length limit 6 ~ 50');
        }
        // confirmPassword
        if ($data['confirmPassword'] != $data['password']) {
            throw new InvalidArgumentException('confirmPassword should equal to password');
        }
        // is email already registered?
        if (OurBlog_Db::getInstance()->fetchOne('SELECT id FROM user WHERE email = ?', array($data['email']))) {
            throw new InvalidArgumentException('email already registered');
        }
        
        return $data;
    }
    
    public static function hashPassword($password)
    {
        return md5(self::PASSWORD_SALT . $password);
    }

    public static function reg(array $data, OurBlog_Util $util)
    {
        $data = self::prepareRegData($data);
        
        $db = OurBlog_Db::getInstance();
        $db->beginTransaction();
        try {
            // user
            $db->insert('user', array(
                'email'    => $data['email'],
                'username' => $data['username'],
                'password' => self::hashPassword($data['password'])
            ));
            // user.reg_token
            $uid      = $db->lastInsertId();
            $regToken = $util->generateRegToken($uid);
            $db->update('user', array(
                'reg_token' => $regToken
            ), 'id = ' . $uid);
            // mail_queue
            $db->insert('mail_queue', array(
                'to'      => $data['email'],
                'subject' => 'OurBlog: Please activate your account',
                'body'    => "Hello {$data['username']}, Welcome to OurBlog.

Please activate your account by click the link below:

    http://localhost/ourblog/activate.php?id=$uid&token=$regToken

Thanks."
            ));
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    public static function activate(array $data)
    {
        // id
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('missing required key id');
        }
        $id = OurBlog_Post::DBAIPK($data['id']);
        if (!$id) {
            throw new InvalidArgumentException('invalid id');
        }
        // token
        if (!isset($data['token'])) {
            throw new InvalidArgumentException('missing required key token');
        }
        $len = strlen($data['token']);
        if ($len != 32) {
            throw new InvalidArgumentException('invalid token');
        }
        
        $db = OurBlog_Db::getInstance();
        if (!$db->fetchOne('SELECT id FROM user WHERE id = ? AND reg_token = ?', array($id, $data['token']))) {
            throw new InvalidArgumentException('token not exists, have you activated before?');
        }
        
        $updateDate = date('Y-m-d H:i:s');
        $db->exec("UPDATE user SET reg_token = NULL, update_date = '$updateDate' WHERE id = $id");
    }
    
    public static function auth(array $data)
    {
        // email
        if (!isset($data['email'])) {
            throw new InvalidArgumentException('missing required key email');
        }
        $len = strlen($data['email']);
        if ($len < 5 || $len > 200) {
            throw new InvalidArgumentException('invalid email, length limit 5 ~ 200');
        }
        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$data['email']) {
            throw new InvalidArgumentException('invalid email');
        }
        // password
        if (!isset($data['password'])) {
            throw new InvalidArgumentException('missing required key password');
        }
        $len = strlen($data['password']);
        if ($len < 6 || $len > 50) {
            throw new InvalidArgumentException('invalid password, length limit 6 ~ 50');
        }
        
        $row = OurBlog_Db::getInstance()->fetchRow(
            'SELECT id, username, reg_token FROM user WHERE email = ? AND password = ?',
            array($data['email'], self::hashPassword($data['password']))
        );
        if (!$row) {
            return false;
        }
        if ($row['reg_token']) {
            throw new InvalidArgumentException('please activate your account first!');
        }
        unset($row['reg_token']);
        return $row;
    }
}
