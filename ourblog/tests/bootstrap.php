<?php

// autoload
include __DIR__ . '/../autoload.php';

// OurBlog_Db
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=3306');
putenv('DB_DATABASE=our_blog_test');
putenv('DB_USER=ourbloguser');
putenv('DB_PASSWORD=ourblogpasswd');

include 'PHPUnitNoNamespace.php';

abstract class OurBlog_DatabaseTestCase extends PHPUnit_DbUnit_Mysql_TestCase
{
    protected static $mysqlPort     = 3306;
    protected static $mysqlDbname   = 'our_blog_test';
    protected static $mysqlUsername = 'rootpw';
}
