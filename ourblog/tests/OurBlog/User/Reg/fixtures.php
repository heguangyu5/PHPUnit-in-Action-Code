<?php

return array(
    'user' => array(
        array(
            'id'          => 1,
            'email'       => 'bob@ourats.com',
            'reg_token'   => NULL,
            'username'    => 'Bob',
            'password'    => OurBlog_User::hashPassword('123456'),
            'create_date' => '2020-07-14 18:00:00',
            'update_date' => '2020-07-14 18:06:00'
        )
    ),
    'mail_queue' => array()
);
