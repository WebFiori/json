<?php

namespace WebFiori\Tests\Json;

use WebFiori\Json\Json;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;
use PHPUnit\Framework\TestCase;
use WebFiori\Json\Property;
use WebFiori\Json\JsonConverter;

class JsonConverterTest extends TestCase {
    /**
     * @test
     */
    public function testObjectToJson00() {
        $obj = new Obj0('Hello', 'World', 8, 9, 'Good');
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json instanceof Json);
        $this->assertEquals('{"Property00":"Hello","Property01":"World","Property02":8,"Property04":"Good"}',$json.'');
    }
    /**
     * @test
     */
    public function testObjectToJson01() {
        $obj = new Obj1('Hello', 'World', 8, 9, 'Good');
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json instanceof Json);
        $this->assertEquals('{"property-00":"Hello","property-01":"World","property-02":8}',$json.'');
    }
    /**
     * @test
     */
    public function testObjectToJson02() {
        $obj = new Json(['hello' => 'world']);
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json instanceof Json);
        $this->assertEquals('{"hello":"world"}',$json.'');
    }
    /**
     * @test
     */
    public function testPropertyToJsonString00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('"hello":"world"', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testPropertyToJsonString01() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('"hello":"world"', JsonConverter::propertyToJsonString($prop, false));
    }
    /**
     * @test
     */
    public function testPropertyToJsonString02() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('"hello":"world"', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testPropertyToJsonXString00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('<json:string name="hello">'."\r\n"
                . '    world'."\r\n"
                . '</json:string>', JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testPropertyToJsonXString01() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('<json:string>'."\r\n"
                . '    world'."\r\n"
                . '</json:string>', JsonConverter::propertyToJsonXString($prop, false));
    }
}
