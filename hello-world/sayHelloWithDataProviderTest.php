<?php
/**
 * @group dataProvider
 */
class sayHelloWithDataProviderTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider validUsernameProvider
     */
    public function testEmptyInput($data, $ret)
    {
        $this->assertEquals($ret, sayHello($data));
    }
    
    public function validUsernameProvider()
    {
        return array(
            array(array(), 'Hello World'),
            array(array('username' => ''), 'Hello World'),
            array(array('username' => 'Bob'), 'Hello Bob')
        );
    }
}
