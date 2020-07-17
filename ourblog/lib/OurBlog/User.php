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

    public static function reg(array $data)
    {
        $data = self::prepareRegData($data);
        
        OurBlog_Db::getInstance()->insert('user', array(
            'email'    => $data['email'],
            'username' => $data['username'],
            'password' => self::hashPassword($data['password'])
        ));
    }
}
