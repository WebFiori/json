<?php

namespace webfiori\tests\json;

use webfiori\json\Json;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;
use PHPUnit\Framework\TestCase;
use webfiori\json\Property;
use webfiori\json\JsonConverter;

/**
 * Description of ProbertyTest
 *
 * @author Ibrahim
 */
class JsonConverterTest extends TestCase {
    /**
     * @test
     */
    public function testToJson00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('"hello":"world"', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson01() {
        $prop = new Property('hello', 1);
        $this->assertEquals('"hello":1', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson02() {
        $prop = new Property('hello', 1.7);
        $this->assertEquals('"hello":1.7', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson03() {
        $prop = new Property('hello', true);
        $this->assertEquals('"hello":true', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson04() {
        $prop = new Property('hello', false);
        $this->assertEquals('"hello":false', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson05() {
        $prop = new Property('hello', null);
        $this->assertEquals('"hello":null', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJson06() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true
        ]));
        $this->assertEquals('"hello":{"one":1,"bool":true}', JsonConverter::propertyToJsonString($prop));
        $this->assertEquals('"hello":{'."\r\n"
                .'    "one":1,'."\r\n"
                .'    "bool":true'
                ."\r\n"
                .'}', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testToJson07() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'obj' => new Json([
                'good' => true,
                'null' => null
            ])
        ]));
        $this->assertEquals('"hello":{"one":1,"bool":true,"obj":{"good":true,"null":null}}', JsonConverter::propertyToJsonString($prop));
        $this->assertEquals('"hello":{'."\r\n"
                .'    "one":1,'."\r\n"
                .'    "bool":true,'."\r\n"
                .'    "obj":{'."\r\n"
                .'        "good":true,'."\r\n"
                .'        "null":null'."\r\n"
                .'    }'."\r\n"
                .'}', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testToJson08() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => []
        ]));
        $this->assertEquals('"hello":{"one":1,"bool":true,"array":[]}', JsonConverter::propertyToJsonString($prop));
        $this->assertEquals('"hello":{'."\r\n"
                .'    "one":1,'."\r\n"
                .'    "bool":true,'."\r\n"
                .'    "array":['."\r\n"
                .'    ]'."\r\n"
                .'}', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testToJson09() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => [
                "string of text"
            ]
        ]));
        $this->assertEquals('"hello":{"one":1,"bool":true,"array":["string of text"]}', JsonConverter::propertyToJsonString($prop));
        $this->assertEquals('"hello":{'."\r\n"
                .'    "one":1,'."\r\n"
                .'    "bool":true,'."\r\n"
                .'    "array":['."\r\n"
                .'        "string of text"'."\r\n"
                .'    ]'."\r\n"
                .'}', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testToJson10() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => [
                "string of text",
                new Json([
                    'ok' => false
                ])
            ]
        ]));
        $this->assertEquals('"hello":{"one":1,"bool":true,"array":["string of text",{"ok":false}]}', JsonConverter::propertyToJsonString($prop));
        $this->assertEquals('"hello":{'."\r\n"
                .'    "one":1,'."\r\n"
                .'    "bool":true,'."\r\n"
                .'    "array":['."\r\n"
                .'        "string of text",'."\r\n"
                .'        {'."\r\n"
                .'            "ok":false'."\r\n"
                .'        }'."\r\n"
                .'    ]'."\r\n"
                .'}', JsonConverter::propertyToJsonString($prop, true));
    }
    /**
     * @test
     */
    public function testToJson11() {
        $obj = new Obj1(1, 2, 3, 4, 5);
        $json = JsonConverter::objectToJson($obj);
        $json->setIsFormatted(false);
        $this->assertEquals('{"property-00":1,"property-01":2,"property-02":3}', $json.'');
    }
    /**
     * @test
     */
    public function testToJson12() {
        $prop = new Property('ABCD', 'world');
        $this->assertEquals('"ABCD":"world"', JsonConverter::propertyToJsonString($prop));
        $prop->setStyle('snake');
        $this->assertEquals('"ABCD":"world"', JsonConverter::propertyToJsonString($prop));
        $prop->setStyle('snake', 'lower');
        $this->assertEquals('"abcd":"world"', JsonConverter::propertyToJsonString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('<json:string name="hello">'."\r\n"
                . '    world'."\r\n"
                . '</json:string>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonXX01() {
        $prop = new Property('hello', 1);
        $this->assertEquals('<json:number name="hello">'."\r\n"
                . '    1'."\r\n"
                . '</json:number>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX02() {
        $prop = new Property('hello', 1.7);
        $this->assertEquals('<json:number name="hello">'."\r\n"
                . '    1.7'."\r\n"
                . '</json:number>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX03() {
        $prop = new Property('hello', true);
        $this->assertEquals('<json:boolean name="hello">'."\r\n"
                . '    true'."\r\n"
                . '</json:boolean>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX04() {
        $prop = new Property('hello', false);
        $this->assertEquals('<json:boolean name="hello">'."\r\n"
                . '    false'."\r\n"
                . '</json:boolean>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX05() {
        $prop = new Property('hello', null);
        $this->assertEquals('<json:null name="hello">'."\r\n"
                . '    null'."\r\n"
                . '</json:null>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX06() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true
        ]));
        $this->assertEquals('<json:object name="hello">'."\r\n"
                . '    <json:number name="one">'."\r\n"
                . '        1'."\r\n"
                . '    </json:number>'."\r\n"
                . '    <json:boolean name="bool">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '</json:object>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX07() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'obj' => new Json([
                'good' => true,
                'null' => null
            ])
        ]));
        $this->assertEquals('<json:object name="hello">'."\r\n"
                . '    <json:number name="one">'."\r\n"
                . '        1'."\r\n"
                . '    </json:number>'."\r\n"
                . '    <json:boolean name="bool">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '    <json:object name="obj">'."\r\n"
                . '        <json:boolean name="good">'."\r\n"
                . '            true'."\r\n"
                . '        </json:boolean>'."\r\n"
                . '        <json:null name="null">'."\r\n"
                . '            null'."\r\n"
                . '        </json:null>'."\r\n"
                . '    </json:object>'."\r\n"
                . '</json:object>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX08() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => []
        ]));
        $this->assertEquals('<json:object name="hello">'."\r\n"
                . '    <json:number name="one">'."\r\n"
                . '        1'."\r\n"
                . '    </json:number>'."\r\n"
                . '    <json:boolean name="bool">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '    <json:array name="array">'."\r\n"
                //. '        '."\r\n"
                . '    </json:array>'."\r\n"
                . '</json:object>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX09() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => [
                "string of text"
            ]
        ]));
        $this->assertEquals('<json:object name="hello">'."\r\n"
                . '    <json:number name="one">'."\r\n"
                . '        1'."\r\n"
                . '    </json:number>'."\r\n"
                . '    <json:boolean name="bool">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '    <json:array name="array">'."\r\n"
                . '        <json:string>'."\r\n"
                . '            string of text'."\r\n"
                . '        </json:string>'."\r\n"
                . '    </json:array>'."\r\n"
                . '</json:object>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testToJsonX10() {
        $prop = new Property('hello', new Json([
            'one' => 1,
            'bool' => true,
            'array' => [
                "string of text",
                new Json([
                    'ok' => false
                ])
            ]
        ]));
        $this->assertEquals('<json:object name="hello">'."\r\n"
                . '    <json:number name="one">'."\r\n"
                . '        1'."\r\n"
                . '    </json:number>'."\r\n"
                . '    <json:boolean name="bool">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '    <json:array name="array">'."\r\n"
                . '        <json:string>'."\r\n"
                . '            string of text'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:object>'."\r\n"
                . '            <json:boolean name="ok">'."\r\n"
                . '                false'."\r\n"
                . '            </json:boolean>'."\r\n"
                . '        </json:object>'."\r\n"
                . '    </json:array>'."\r\n"
                . '</json:object>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }

}
