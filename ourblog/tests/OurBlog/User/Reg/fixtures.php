<?php

return array(
    'user' => array(
        array(
            'id'          => 1,
            'email'       => 'bob@ourats.com',
            'username'    => 'Bob',
            'password'    => OurBlog_User::hashPassword('123456'),
            'create_date' => '2020-07-14 18:00:00'
        )
    )
);
