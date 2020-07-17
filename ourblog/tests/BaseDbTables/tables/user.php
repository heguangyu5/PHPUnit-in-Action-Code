<?php

return array(
    array(
        'id'          => 1,
        'email'       => 'bob@ourats.com',
        'username'    => 'Bob',
        'password'    => OurBlog_User::hashPassword('123456'),
        'create_date' => '2020-07-14 18:00:00'
    ),
    array(
        'id'          => 2,
        'email'       => 'joe@ourats.com',
        'username'    => 'Joe',
        'password'    => OurBlog_User::hashPassword('654321'),
        'create_date' => '2020-07-15 18:00:00'
    )
);
