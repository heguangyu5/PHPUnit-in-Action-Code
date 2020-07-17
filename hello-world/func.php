<?php

function sayHello(array $data)
{
    if (isset($data['username']) && $data['username']) {
        if (!preg_match('/^[a-zA-z0-9 ]{2,30}$/', $data['username'])) {
            throw new InvalidArgumentException('invalid username, should 2 ~ 30 characters and only contains a-z, A-Z, 0-9 and space.');
        }
        return 'Hello ' . $data['username'];
    }
    
    return 'Hello World';
}
