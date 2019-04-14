<?php
use PHPUnit\Framework\TestCase;
use jsonx\JsonX;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;

class JsonXTest extends TestCase{
    /**
     * @test
     */
    public function testAddNumber00() {
        $j = new JsonX();
        $j->addNumber('   number', 33);
        $this->assertEquals('{"number":33}',$j.'');
    }
    /**
     * @test
     */
    public function testAddBoolean00() {
        $j = new JsonX();
        $j->addBoolean('bool ', true);
        $this->assertEquals('{"bool":true}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray00() {
        $j = new JsonX();
        $arr = array();
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray01() {
        $j = new JsonX();
        $arr = array(1,"Hello",true,NAN,null,99.8,INF);
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{"0":1, "1":"Hello", "2":true, "3":"NAN", "4":null, "5":99.8, "6":"INF"}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray02() {
        $j = new JsonX();
        $arr = array(1,1.5,"Hello",true,NAN,null,INF);
        $j->addArray('arr', $arr,false);
        $this->assertEquals('{"arr":[1, 1.5, "Hello", true, "NAN", null, "INF"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray03() {
        $j = new JsonX();
        $arr = array("number"=>1,"Hello"=>"world!","boolean"=>true,NAN,null);
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":{"number":1, "Hello":"world!", "boolean":true, "0":"NAN", "1":null}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddObj00() {
        $j = new JsonX();
        $obj = new Obj0('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"prop-0":"Hello","prop-1":0,"prop-2":true,"prop-3":"he"}}',$j.'');
    }
    
    /**
     * @test
     */
    public function testAddObj01() {
        $j = new JsonX();
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"property-00":"Hello","property-01":0,"property-02":true}}',$j.'');
    }
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
    /**
     * @test
     */
    public function testEscJSonSpecialChars02() {
        $str = '\tI\'m good. But "YOU" are "Better".\r\n'
                . '\\An inline comment is good.';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('\\\\tI\'m good. But \"YOU\" are \"Better\".\\\\r\\\\n'
                . '\\\\An inline comment is good.',$result);
    }
}
