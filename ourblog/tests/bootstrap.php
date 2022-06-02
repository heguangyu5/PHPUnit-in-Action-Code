<?php

// autoload
if (defined('__BPC__')) {
    spl_autoload_register(function ($class) {
        $path = str_replace('_', '/', $class) . '.php';
        include_once_silent($path);
    }, true, true);
} else {
    include __DIR__ . '/../autoload.php';
}

// OurBlog_Db
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=3307');
putenv('DB_DATABASE=our_blog_test');
putenv('DB_USER=root');
putenv('DB_PASSWORD=123456');

include 'phpunit-ext/loader.php';

abstract class OurBlog_DatabaseTestCase extends PHPUnit_DbUnit_Mysql_TestCase
{
    protected $mysqlDbname = 'our_blog_test';
}
