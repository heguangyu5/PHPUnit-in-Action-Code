<?php
/**
 * @group dataProvider
 */
class DataProviderTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider invalidUsernameProvider
     */
    public function testInvalidUsername($invalidUsername)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('invalid username, should 2 ~ 30 characters and only contains a-z, A-Z, 0-9 and space.');
        
        $data = array('username' => $invalidUsername);
        sayHello($data);
    }
    
    public function invalidUsernameProvider()
    {
        return array(
            array('a'),
            array(str_pad('a', 31, 'A')),
            array('Bob!')
        );
    }
}
