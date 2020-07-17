<?php

return array(
    'user' => array(
        array(
            'id'       => 1,
            'email'    => 'bob@ourats.com',
            'username' => 'Bob',
            'password' => OurBlog_User::hashPassword('123456'),
        ),
        array(
            'id'       => 2,
            'email'    => 'joe@ourats.com',
            'username' => 'Joe',
            'password' => OurBlog_User::hashPassword('654321'),
        )
    )
);
