<?php
/**
 * Created by PhpStorm.
 * User: jensgheerardyn
 * Date: 27/12/13
 * Time: 15:37
 */
class StackTest extends PHPUnit_Framework_TestCase
{
    public function CheckValidUrl()
    {

    }

    public function testPushAndPop2()
    {
        $stack = array();
        $this->assertEquals(0, count($stack));
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}
?>