<?php
use PHPUnit\Framework\TestCase;
use jsonx\JsonX;

class JsonXTest extends TestCase{
    /**
     * @test
     */
    public function testAddStringTest00(){
        $j = new JsonX();
        $this->assertFalse($j->addString('', 'Hello World!'));
        $this->assertFalse($j->addString('  ', 'Hello World!'));
        $this->assertFalse($j->addString("\n", 'Hello World!'));
        $this->assertEquals('{}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest01(){
        $j = new JsonX();
        $this->assertTrue($j->addString('hello', 'Hello World!'));
        $this->assertEquals('{"hello":"Hello World!"}',$j.'');
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars00() {
        $str = 'I\'m "Good".';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('I\'m \"Good\".',$result);
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars01() {
        $str = 'Path: "C:/Windows/Media/onestop.midi"\n';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('Path: \"C:\/Windows\/Media\/onestop.midi\"\\\\n',$result);
    }
}
