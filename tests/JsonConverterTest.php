<?php

namespace jsonx\tests;

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
}
