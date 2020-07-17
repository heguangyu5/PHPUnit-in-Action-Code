<?php

class sayHelloTest extends PHPUnit\Framework\TestCase
{
    public function testEmptyInput()
    {
        $data = array();
        $this->assertEquals('Hello World', sayHello($data));
    }
    
    public function testEmptyUsername()
    {
        $data = array('username' => '');
        $this->assertEquals('Hello World', sayHello($data));
    }
    
    public function testSayHello()
    {
        $data = array('username' => 'Bob'); 
        $this->assertEquals('Hello Bob', sayHello($data));
    }
}
