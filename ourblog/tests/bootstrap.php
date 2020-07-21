<?php

// autoload
include __DIR__ . '/../autoload.php';

// OurBlog_Db
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=3307');
putenv('DB_DATABASE=our_blog_test');
putenv('DB_USER=root');
putenv('DB_PASSWORD=123456');

include 'PHPUnitNoNamespace.php';

abstract class OurBlog_DatabaseTestCase extends PHPUnit_DbUnit_Mysql_TestCase
{
    protected static $mysqlDbname = 'our_blog_test';
}
