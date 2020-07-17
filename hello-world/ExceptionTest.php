<?php
/**
 * @group exception
 */
class ExceptionTest extends PHPUnit\Framework\TestCase
{
    public function testTooShortUsername()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid username, should 2 ~ 30 characters and only contains a-z, A-Z, 0-9 and space.');
        
        $data = array('username' => 'a');
        sayHello($data);
    }
    
    public function testTooLongUsername()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid username, should 2 ~ 30 characters and only contains a-z, A-Z, 0-9 and space.');
        
        $data = array('username' => str_pad('a', 31, 'A'));
        sayHello($data);
    }
    
    public function testUsernameContainsNotAllowedChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid username, should 2 ~ 30 characters and only contains a-z, A-Z, 0-9 and space.');
        
        $data = array('username' => 'Bob!');
        sayHello($data);
    }
}
