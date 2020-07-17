<?php

function sayHello(array $data)
{
    if (isset($data['username']) && $data['username']) {
        return 'Hello ' . $data['username'];
    }
    
    return 'Hello World';
}
