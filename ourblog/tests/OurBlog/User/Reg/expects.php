<?php

return array(
    'user' => array(
        array(
            'id'        => 1,
            'email'     => 'bob@ourats.com',
            'reg_token' => NULL,
            'username'  => 'Bob',
            'password'  => OurBlog_User::hashPassword('123456'),
        ),
        array(
            'id'        => 2,
            'email'     => 'joe@ourats.com',
            'reg_token' => 'abcd1234abcd1234abcd1234abcd1234',
            'username'  => 'Joe',
            'password'  => OurBlog_User::hashPassword('654321'),
        )
    ),
    'mail_queue' => array(
        array(
            'id'      => 1,
            'to'      => 'joe@ourats.com',
            'subject' => 'OurBlog: Please activate your account',
            'body'    => 'Hello Joe, Welcome to OurBlog.

Please activate your account by click the link below:

    http://localhost/ourblog/activate.php?id=2&token=abcd1234abcd1234abcd1234abcd1234

Thanks.'
        )
    )
);
