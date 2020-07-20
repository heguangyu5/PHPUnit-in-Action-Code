<?php

return array(
    'user' => array(
        array(
            'id'          => 2,
            'email'       => 'joe@ourats.com',
            'reg_token'   => 'abcd1234abcd1234abcd1234abcd1234',
            'username'    => 'Joe',
            'password'    => OurBlog_User::hashPassword('654321'),
            'create_date' => '2020-07-20 12:00:00',
            'update_date' => '2020-07-20 12:00:00'
        )
    )
);
