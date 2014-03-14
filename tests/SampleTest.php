<?php
class SampleTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    public function testExample()
    {   
        $this->assertEquals(1, 1);
        $this->assertTrue(true);
        $this->assertFalse(false);
    }

    protected function tearDown()
    {

    }
}
?>