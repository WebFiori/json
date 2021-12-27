<?php

use webfiori\json\Json;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase {
    /**
     * @test
     */
    public function testToJsonString00() {
        $j = new Json(['hello'=>'world']);
        $this->assertEquals('{"hello":"world"}',$j->toJSONString());
        $this->assertEquals('world',$j->get('hello'));
        return $j;
    }
    /**
     * @depends testToJsonString00
     * @param Json $json
     */
    public function testToJsonXString00(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:string name="hello">'."\r\n"
                . '        world'."\r\n"
                . '    </json:string>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString01() {
        $j = new Json(['number'=>100]);
        $this->assertEquals('{"number":100}',$j->toJSONString());
        $this->assertSame(100,$j->get('number'));
        return $j;
    }
    /**
     * @depends testToJsonString01
     * @param Json $json
     */
    public function testToJsonXString01(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:number name="number">'."\r\n"
                . '        100'."\r\n"
                . '    </json:number>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString02() {
        $j = new Json(['number'=>20.2235]);
        $this->assertEquals('{"number":20.2235}',$j->toJSONString());
        $this->assertSame(20.2235,$j->get('number'));
        return $j;
    }
    /**
     * @depends testToJsonString02
     * @param Json $json
     */
    public function testToJsonXString02(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:number name="number">'."\r\n"
                . '        20.2235'."\r\n"
                . '    </json:number>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString03() {
        $j = new Json(['number'=>NAN]);
        $this->assertEquals('{"number":"NaN"}',$j->toJSONString());
        $this->assertTrue(is_nan($j->get('number')));
        return $j;
    }
    /**
     * @depends testToJsonString03
     * @param Json $json
     */
    public function testToJsonXString03(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:string name="number">'."\r\n"
                . '        NaN'."\r\n"
                . '    </json:string>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString04() {
        $j = new Json(['number'=>INF]);
        $this->assertEquals('{"number":"Infinity"}',$j->toJSONString());
        $this->assertSame(INF,$j->get('number'));
        return $j;
    }
    /**
     * @depends testToJsonString04
     * @param Json $json
     */
    public function testToJsonXString04(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:string name="number">'."\r\n"
                . '        Infinity'."\r\n"
                . '    </json:string>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString05() {
        $j = new Json(['bool-true'=>true,'bool-false'=>false]);
        $this->assertEquals('{"bool-true":true,"bool-false":false}',$j->toJSONString());
        $this->assertSame(true,$j->get('bool-true'));
        $this->assertSame(false,$j->get('bool-false'));
        return $j;
    }
    /**
     * @depends testToJsonString05
     * @param Json $json
     */
    public function testToJsonXString05(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:boolean name="bool-true">'."\r\n"
                . '        true'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '    <json:boolean name="bool-false">'."\r\n"
                . '        false'."\r\n"
                . '    </json:boolean>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString06() {
        $j = new Json(['null'=>null]);
        $this->assertEquals('{"null":null}',$j->toJSONString());
        $this->assertNull($j->get('null'));
        return $j;
    }
    /**
     * @depends testToJsonString06
     * @param Json $json
     */
    public function testToJsonXString06(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:null name="null">'."\r\n"
                . '        null'."\r\n"
                . '    </json:null>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString07() {
        $j = new Json(['array'=>['one',1]]);
        $this->assertEquals('{"array":["one",1]}',$j->toJSONString());
        $this->assertEquals(['one',1],$j->get('array'));
        return $j;
    }
    /**
     * @depends testToJsonString07
     * @param Json $json
     */
    public function testToJsonXString07(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:array name="array">'."\r\n"
                . '        <json:string>'."\r\n"
                . '            one'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:number>'."\r\n"
                . '            1'."\r\n"
                . '        </json:number>'."\r\n"
                . '    </json:array>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString08() {
        $jx = new Json(['hello'=>'world']);
        $arr = ['one',1,null,1.8,true,false,NAN,INF,$jx,['two','good']];
        $j = new Json([
            'array'=>$arr
            ]);
        $this->assertEquals('{"array":["one",1,null,1.8,true,false,"NaN","Infinity",{"hello":"world"},["two","good"]]}',$j->toJSONString());
    
        return $j;
    }
    /**
     * @depends testToJsonString08
     * @param Json $json
     */
    public function testToJsonXString08(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:array name="array">'."\r\n"
                . '        <json:string>'."\r\n"
                . '            one'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:number>'."\r\n"
                . '            1'."\r\n"
                . '        </json:number>'."\r\n"
                . '        <json:null>'."\r\n"
                . '            null'."\r\n"
                . '        </json:null>'."\r\n"
                . '        <json:number>'."\r\n"
                . '            1.8'."\r\n"
                . '        </json:number>'."\r\n"
                . '        <json:boolean>'."\r\n"
                . '            true'."\r\n"
                . '        </json:boolean>'."\r\n"
                . '        <json:boolean>'."\r\n"
                . '            false'."\r\n"
                . '        </json:boolean>'."\r\n"
                . '        <json:string>'."\r\n"
                . '            NaN'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:string>'."\r\n"
                . '            Infinity'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:object>'."\r\n"
                . '            <json:string name="hello">'."\r\n"
                . '                world'."\r\n"
                . '            </json:string>'."\r\n"
                . '        </json:object>'."\r\n"
                . '        <json:array>'."\r\n"
                . '            <json:string>'."\r\n"
                . '                two'."\r\n"
                . '            </json:string>'."\r\n"
                . '            <json:string>'."\r\n"
                . '                good'."\r\n"
                . '            </json:string>'."\r\n"
                . '        </json:array>'."\r\n"
                . '    </json:array>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString09() {
        $arr = [NAN,INF];
        $j = new Json();
        $j->addArray('arr',$arr,true);
        $this->assertEquals('{"arr":{"0":"NaN","1":"Infinity"}}',$j->toJSONString());
        $j->setIsFormatted(true);
        $this->assertEquals('{'."\r\n"
                . '    "arr":{'."\r\n"
                . '        "0":"NaN",'."\r\n"
                . '        "1":"Infinity"'."\r\n"
                . '    }'."\r\n"
                . '}',$j->toJSONString());
        $j->setIsFormatted(false);
        $this->assertEquals('{"arr":{"0":"NaN","1":"Infinity"}}',$j->toJSONString());
        return $j;
    }
    /**
     * @depends testToJsonString09
     * @param Json $json
     */
    public function testToJsonXString09(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:object name="arr">'."\r\n"
                . '        <json:string name="0">'."\r\n"
                . '            NaN'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:string name="1">'."\r\n"
                . '            Infinity'."\r\n"
                . '        </json:string>'."\r\n"
                . '    </json:object>'."\r\n"
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString10() {
        $j = new Json();
        $subJ = new Json([
            'number-one' => 1,
            'arr' => [],
            'obj' => new Json()
        ]);
        $this->assertEquals([
            'number-one',
            'arr',
            'obj'
        ],$subJ->getPropsNames());
        $j->add('jsonx',$subJ);
        $j->add('o',new Obj1('1',2,3,4,'5'));
        $this->assertEquals('{"jsonx":{"number-one":1,"arr":[],"obj":{}},'
                . '"o":{"property-00":"1","property-01":2,"property-02":3}}',$j.'');
        $j->setPropsStyle('snake');
        $this->assertEquals('{"jsonx":{"number_one":1,"arr":[],"obj":{}},'
                . '"o":{"property_00":"1","property_01":2,"property_02":3}}',$j.'');
        $j->setIsFormatted(true);
        $this->assertEquals('{'."\r\n"
                . '    "jsonx":{'."\r\n"
                . '        "number_one":1,'."\r\n"
                . '        "arr":['."\r\n"
                . '        ],'."\r\n"
                . '        "obj":{'."\r\n"
                . '        }'."\r\n"
                . '    },'."\r\n"
                . '    "o":{'."\r\n"
                . '        "property_00":"1",'."\r\n"
                . '        "property_01":2,'."\r\n"
                . '        "property_02":3'."\r\n"
                . '    }'."\r\n"
                . '}',$j.'');
        $subX = $j->get('jsonx');
        $this->assertEquals('{'."\r\n"
                . '    "number_one":1,'."\r\n"
                . '    "arr":['."\r\n"
                . '    ],'."\r\n"
                . '    "obj":{'."\r\n"
                . '    }'."\r\n"
                . '}',$subX->toJSONString());
        
        $j->get('jsonx')->add('general',new Obj0('1','3',99,100,"ok"));
        $this->assertEquals('{'."\r\n"
                . '    "jsonx":{'."\r\n"
                . '        "number_one":1,'."\r\n"
                . '        "arr":['."\r\n"
                . '        ],'."\r\n"
                . '        "obj":{'."\r\n"
                . '        },'."\r\n"
                . '        "general":{'."\r\n"
                . '            "property00":"1",'."\r\n"
                . '            "property01":"3",'."\r\n"
                . '            "property02":99,'."\r\n"
                . '            "property04":"ok"'."\r\n"
                . '        }'."\r\n"
                . '    },'."\r\n"
                . '    "o":{'."\r\n"
                . '        "property_00":"1",'."\r\n"
                . '        "property_01":2,'."\r\n"
                . '        "property_02":3'."\r\n"
                . '    }'."\r\n"
                . '}',$j.'');
        $j->setIsFormatted(false);
        $this->assertEquals('{"jsonx":{"number_one":1,"arr":[],"obj":{},"general":{"property00":"1","property01":"3","property02":99,"property04":"ok"}},"o":{"property_00":"1","property_01":2,"property_02":3}}',$j.'');
        return $j;
    }
    /**
     * @depends testToJsonString10
     * @param Json $json
     */
    public function testToJsonXString10(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:object name="jsonx">'."\r\n"
                . '        <json:number name="number_one">'."\r\n"
                . '            1'."\r\n"
                . '        </json:number>'."\r\n"
                . '        <json:array name="arr">'."\r\n"
                . '        </json:array>'."\r\n"
                . '        <json:object name="obj">'."\r\n"
                . '        </json:object>'."\r\n"
                . '        <json:object name="general">'."\r\n"
                . '            <json:string name="property00">'."\r\n"
                . '                1'."\r\n"
                . '            </json:string>'."\r\n"
                . '            <json:string name="property01">'."\r\n"
                . '                3'."\r\n"
                . '            </json:string>'."\r\n"
                . '            <json:number name="property02">'."\r\n"
                . '                99'."\r\n"
                . '            </json:number>'."\r\n"
                . '            <json:string name="property04">'."\r\n"
                . '                ok'."\r\n"
                . '            </json:string>'."\r\n"
                . '        </json:object>'."\r\n"
                . '    </json:object>'."\r\n"
                . '    <json:object name="o">'."\r\n"
                . '        <json:string name="property_00">'."\r\n"
                . '            1'."\r\n"
                . '        </json:string>'."\r\n"
                . '        <json:number name="property_01">'."\r\n"
                . '            2'."\r\n"
                . '        </json:number>'."\r\n"
                . '        <json:number name="property_02">'."\r\n"
                . '            3'."\r\n"
                . '        </json:number>'."\r\n"
                . '    </json:object>'."\r\n"
                
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testToJsonString11() {
        $arr = [
            [
                "sub-arr",
                1,
                2,
                "hello"=>"world",
                new Obj0('1',2,3,4,5),
                new Json(['good'=>true])
            ],
            new Json(['bad'=>false])
        ];
        $json = new Json();
        $json->addArray('array',$arr);
        $this->assertEquals('{"array":[['
                . '"sub-arr",1,2,"world",{"Property00":"1","Property01":2,"Property02":3,"Property04":5},'
                . '{"good":true}'
                . '],{"bad":false}]}',$json.'');
        $json->remove('array');
        $json->addArray('x-array', $arr, true);
        $this->assertEquals('{"x-array":{"0":{"0":"sub-arr","1":1,"2":2,"hello":"world",'
                . '"3":{"Property00":"1","Property01":2,"Property02":3,"Property04":5},"4":{"good":true}},"1":{"bad":false}}}',$json.'');
        return $json;
    }
    /**
     * @depends testToJsonString11
     * @param Json $json
     */
    public function testToJsonXString11(Json $json) {
        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>'."\r\n"
                . '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'."\r\n"
                . '    <json:object name="x-array">'."\r\n"
                . '        <json:object name="0">'."\r\n"
                . '            <json:string name="0">'."\r\n"
                . '                sub-arr'."\r\n"
                . '            </json:string>'."\r\n"
                . '            <json:number name="1">'."\r\n"
                . '                1'."\r\n"
                . '            </json:number>'."\r\n"
                . '            <json:number name="2">'."\r\n"
                . '                2'."\r\n"
                . '            </json:number>'."\r\n"
                . '            <json:string name="hello">'."\r\n"
                . '                world'."\r\n"
                . '            </json:string>'."\r\n"
                . '            <json:object name="3">'."\r\n"
                . '                <json:string name="Property00">'."\r\n"
                . '                    1'."\r\n"
                . '                </json:string>'."\r\n"
                . '                <json:number name="Property01">'."\r\n"
                . '                    2'."\r\n"
                . '                </json:number>'."\r\n"
                . '                <json:number name="Property02">'."\r\n"
                . '                    3'."\r\n"
                . '                </json:number>'."\r\n"
                . '                <json:number name="Property04">'."\r\n"
                . '                    5'."\r\n"
                . '                </json:number>'."\r\n"
                . '            </json:object>'."\r\n"
                . '            <json:object name="4">'."\r\n"
                . '                <json:boolean name="good">'."\r\n"
                . '                    true'."\r\n"
                . '                </json:boolean>'."\r\n"
                . '            </json:object>'."\r\n"
                . '        </json:object>'."\r\n"
                . '        <json:object name="1">'."\r\n"
                . '            <json:boolean name="bad">'."\r\n"
                . '                false'."\r\n"
                . '            </json:boolean>'."\r\n"
                . '        </json:object>'."\r\n"
                . '    </json:object>'."\r\n"
                
                . '</json:object>', $json->toJSONxString());
    }
    /**
     * @test
     */
    public function testDecode00() {
        $jsonStr = '{"Hello":"world"}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $this->assertEquals("world",$decoded->get('Hello'));
    }
    /**
     * @test
     */
    public function testDecode01() {
        $jsonStr = '{"Hello":"world","true":true,"false":false}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $this->assertEquals("world",$decoded->get('Hello'));
        $this->assertTrue($decoded->get('true'));
        $this->assertFalse($decoded->get('false'));
    }
    /**
     * @test
     */
    public function testDecode02() {
        $jsonStr = '{"Hello":"world","one":1,"two":2.4,"null":null}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $this->assertEquals("world",$decoded->get('Hello'));
        $this->assertEquals(1,$decoded->get('one'));
        $this->assertEquals(2.4,$decoded->get('two'));
        $this->assertNull($decoded->get('null'));
    }
    /**
     * @test
     */
    public function testDecode03() {
        $jsonStr = '{"array":["world","one",1,"two",2.4,"null",null,true,false]}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $arr = $decoded->get('array');
        $this->assertTrue(gettype($arr) == 'array');
        $this->assertEquals('world',$arr[0]);
        $this->assertEquals('one',$arr[1]);
        $this->assertEquals(1,$arr[2]);
        $this->assertEquals('two',$arr[3]);
        $this->assertEquals(2.4,$arr[4]);
        $this->assertEquals('null',$arr[5]);
        $this->assertNull( $arr[6]);
        $this->assertTrue($arr[7]);
        $this->assertFalse($arr[8]);
    }
    /**
     * @test
     */
    public function testDecode04() {
        $jsonStr = '{"object":{"true":true,"false":false,"null":null,"str":"A string","number":33,"array":["Hello"]}}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $jObj = $decoded->get('object');
        $this->assertTrue($jObj instanceof Json);
        $this->assertTrue($jObj->get('true'));
        $this->assertFalse($jObj->get('false'));
        $this->assertNull($jObj->get('null'));
        $this->assertEquals('A string',$jObj->get('str'));
        $this->assertEquals(33,$jObj->get('number'));
        $arr = $jObj->get('array');
        $this->assertTrue(gettype($arr) == 'array');
        $this->assertEquals("Hello",$arr[0]);
    }
    /**
     * @test
     */
    public function testDecode06() {
        $jsonStr = '{"prop-1":1,"prop-2":"hello","prop-3":true}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $this->assertEquals(1,$decoded->get('prop-1'));
        $this->assertEquals('hello',$decoded->get('prop-2'));
        $this->assertTrue($decoded->get('prop-3'));
    }
    /**
     * @test
     */
    public function testDecode07() {
        $jsonStr = '{prop-1:1}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue(gettype($decoded) == 'array');
        $this->assertEquals(4,$decoded['error-code']);
        $this->assertEquals('Syntax error',$decoded['error-message']);
    }
    /**
     * @test
     */
    public function testDecod08() {
        $jsonxObj =Json::decode('{"hello":"world","sub-obj":{},"an-array":[]}');
        $this->assertEquals('{"hello":"world","sub-obj":{},"an-array":[]}',$jsonxObj.'');
    }
    /**
     * @test
     */
    public function testDecode05() {
        $jsonStr = '{'
                . '"obj":{'
                . '    "array":['
                . '        "world",'
                . '        {"hell":"no"},'
                . '        ["one",1]]},'
                . '"outer-arr":[{"hello":"world","deep-arr":["deep"]}]}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        
        $jObj = $decoded->get('obj');
        $this->assertTrue($jObj instanceof Json);
        $objArr = $jObj->get('array');
        $this->assertTrue(gettype($objArr) == 'array');
        $this->assertEquals("world",$objArr[0]);
        $this->assertTrue($objArr[1] instanceof Json);
        $this->assertEquals("no",$objArr[1]->get('hell'));
        $this->assertTrue(gettype($objArr[2]) == 'array');
        $this->assertEquals("one",$objArr[2][0]);
        $this->assertEquals(1,$objArr[2][1]);
        
        $outerArr = $decoded->get('outer-arr');
        $this->assertTrue(gettype($outerArr) == 'array');
        $this->assertTrue($outerArr[0] instanceof Json);
        $this->assertEquals("world",$outerArr[0]->get('hello'));
        $this->assertTrue(gettype($outerArr[0]->get('deep-arr')) == 'array');
        
        
    }
    public function testDecode08() {
        $jsonStr = '{"arr":['
                . '["hello",{"one":1},[{"sub-arr":["one",{"hello":"world"}]}]'
                . ']'
                . ']}';
        $decoded = Json::decode($jsonStr);
        $this->assertTrue($decoded instanceof Json);
        $this->assertEquals($jsonStr,$decoded->toJSONString());
        $arr = $decoded->get('arr');
        $this->assertTrue(gettype($arr) == 'array');
        $this->assertEquals(1,count($arr));
        $this->assertEquals('hello',$arr[0][0]);
        $subObj = $arr[0][1];
        $this->assertTrue($subObj instanceof Json);
        $this->assertTrue($subObj->hasKey('one'));
        $subArr = $arr[0][2];
        $this->assertTrue(gettype($subArr) == 'array');
    }
    /**
     * @test
     */
    public function testFromFile00() {
        $this->assertNull(Json::fromFile(ROOT.DIRECTORY_SEPARATOR.'not-exist.json'));
        $arr = Json::fromFile(ROOT.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'Obj0.php');
        $this->assertTrue(gettype($arr) == 'array');
        $jsonx = Json::fromFile(ROOT.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'composer.json');
        $this->assertTrue($jsonx instanceof Json);
        $packagesArr = $jsonx->get('packages');
        $this->assertEquals(5,count($packagesArr));
        $package5 = $packagesArr[4];
        $this->assertTrue($package5 instanceof Json);
        $this->assertEquals('webfiori/rest-easy',$package5->get('name'));
        $this->assertEquals([
                "Web APIs",
                "api",
                "json",
                "library",
                "php"
            ],$package5->get('keywords'));
    }
    public function testAddMultiple00() {
        $j = new Json();
        $j->addMultiple([
            'user-id'=>5,
            'an-array'=>[1,2,3],
            'float'=>1.6,
            'bool'=>true
        ]);
        $this->assertEquals('{"user-id":5,"an-array":[1,2,3],"float":1.6,"bool":true}',$j.'');
    }
    /**
     * @test
     */
    public function testAdd00() {
        $j = new Json();
        $this->assertTrue($j->add('a-string','This is a string.'));
        $this->assertTrue($j->add('string-as-bool-1','NO',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-2',-1,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-3',0,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-4',1,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-5','t',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-6','Yes',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-not-as-bool','Yes'));
        $this->assertTrue($j->add('null-value',null));
        $this->assertTrue($j->add('infinity',INF));
        $this->assertTrue($j->add('not-a-number',INF));
        $this->assertFalse($j->addObject('ok', null));
    }

    /**
     * @test
     */
    public function testAdd03() {
        $j = new Json();
        $subJ = new Json();
        $subJ->add('test',true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice','To',99,INF,NAN),
            [4,1.7,true,null],
            new Obj1('1','Hello','No',true,false),
            $subJ,
            [[new Obj0('p0','p1','p2','p3','p4'),$subJ,new Obj1('p0','p1','p2','p3','p4')]]];
        $j->add('big-array',$arr);
        $this->assertEquals('{'
                .'"big-array":["world",'
                .'{"Property00":"Nice","Property01":"To","Property02":99,"Property04":"NaN"},'
                .'[4,1.7,true,null],'
                .'{"property-00":"1","property-01":"Hello","property-02":"No"},'
                .'{"test":true},'
                .'[[{"Property00":"p0","Property01":"p1","Property02":"p2","Property04":"p4"},'
                .'{"test":true},'
                .'{"property-00":"p0","property-01":"p1","property-02":"p2"}]]]'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd04() {
        $j = new Json();
        $subJ = new Json();
        $subJ->add('test',true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice','To',99,INF,NAN),
            [4,1.7,true,null],
            new Obj1('1','Hello','No',true,false),
            $subJ,
            [[new Obj0('p0','p1','p2','p3','p4'),$subJ,new Obj1('p0','p1','p2','p3','p4')]]];
        $j->add('big-array',$arr,true);
        $this->assertEquals('{'
                .'"big-array":{"hello":"world",'
                .'"0":{"Property00":"Nice","Property01":"To","Property02":99,"Property04":"NaN"},'
                .'"1":{"0":4,"1":1.7,"2":true,"3":null},'
                .'"2":{"property-00":"1","property-01":"Hello","property-02":"No"},'
                .'"3":{"test":true},'
                .'"4":{"0":{"0":{"Property00":"p0","Property01":"p1","Property02":"p2","Property04":"p4"},'
                .'"1":{"test":true},'
                .'"2":{"property-00":"p0","property-01":"p1","property-02":"p2"}}}}'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd05() {
        $j = new Json();
        $this->assertFalse($j->add('  ',null));
        $this->assertTrue($j->add('null-value',null));
        $this->assertEquals('{"null-value":null}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd07() {
        $j = new Json();
        $subJ = new Json();
        $subJ->add('test',true);
        $arr = [
            'hello' => 'world',
            'null' => null,
            'boolean' => true,
            'number' => 665,
            'str-as-bool' => 'f',
            'object-0' => new Obj0('Nice','To',99,INF,NAN),
            'array-0' => [4,1.7,true,null,'t','f'],
            'object-1' => new Obj1('1','Hello','No',true,false),
            'jsonx-obj' => $subJ,
            'array-1' => [[new Obj0('p0','p1','p2','p3','p4'),$subJ,new Obj1('p0','p1','p2','p3','p4')]]];
        $j->add('big-array',$arr,true);
        $this->assertEquals('{'
                .'"big-array":{"hello":"world",'
                .'"null":null,'
                .'"boolean":true,'
                .'"number":665,'
                .'"str-as-bool":"f",'
                .'"object-0":{"Property00":"Nice","Property01":"To","Property02":99,"Property04":"NaN"},'
                .'"array-0":{"0":4,"1":1.7,"2":true,"3":null,"4":"t","5":"f"},'
                .'"object-1":{"property-00":"1","property-01":"Hello","property-02":"No"},'
                .'"jsonx-obj":{"test":true},'
                .'"array-1":{"0":{"0":{"Property00":"p0","Property01":"p1","Property02":"p2","Property04":"p4"},'
                .'"1":{"test":true},'
                .'"2":{"property-00":"p0","property-01":"p1","property-02":"p2"}}}}'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd08() {
        $j = new Json();
        $subJ = new Json();
        $subJ->add('test',true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice','To',99,INF,NAN),
            [4,1.7,true,null,'t',false],
            new Obj1('1','Hello','No',true,false),
            $subJ,
            [[new Obj0('p0','p1','p2','p3','p4'),$subJ,new Obj1('p0','p1','p2','p3','p4')]]];
        $j->add('big-array',$arr);
        $this->assertEquals('{'
                .'"big-array":["world",'
                .'{"Property00":"Nice","Property01":"To","Property02":99,"Property04":"NaN"},'
                .'[4,1.7,true,null,"t",false],'
                .'{"property-00":"1","property-01":"Hello","property-02":"No"},'
                .'{"test":true},'
                .'[[{"Property00":"p0","Property01":"p1","Property02":"p2","Property04":"p4"},'
                .'{"test":true},'
                .'{"property-00":"p0","property-01":"p1","property-02":"p2"}]]]'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAddArray00() {
        $j = new Json();
        $arr = [];
        $j->addArray('arr',$arr);
        $this->assertEquals('{"arr":[]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray01() {
        $j = new Json();
        $arr = [1,"Hello",true,NAN,null,99.8,INF];
        $j->addArray('arr',$arr);
        $this->assertEquals('{"arr":[1,"Hello",true,"NaN",null,99.8,"Infinity"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray02() {
        $j = new Json();
        $arr = [1,1.5,"Hello",true,NAN,null,INF];
        $j->addArray('arr',$arr,false);
        $this->assertEquals('{"arr":[1,1.5,"Hello",true,"NaN",null,"Infinity"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray03() {
        $j = new Json();
        $arr = ["number" => 1,"Hello" => "world!","boolean" => true,NAN,null];
        $j->addArray('arr',$arr, true);
        $this->assertEquals('{"arr":{"number":1,"Hello":"world!","boolean":true,"0":"NaN","1":null}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray04() {
        $j = new Json();
        $arr = ["number" => 1,"Hello-1" => "world!","boolean-super" => true,NAN,null];
        $j->setPropsStyle('snake');
        $j->addArray('arr',$arr);
        $this->assertEquals('{"arr":[1,"world!",true,"NaN",null]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray05() {
        $j = new Json();
        $arr = ["number" => 1,"Hello-1" => "world!","boolean-super" => true,NAN,null];
        $j->setPropsStyle('snake');
        $j->add('arr',$arr,true);
        $this->assertEquals('{"arr":{"number":1,"hello_1":"world!","boolean_super":true,"0":"NaN","1":null}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray06() {
        $arr = [
            [
                new Json([
                    'null' => null
                ]),
                [
                    'hello'
                ],
                new Obj1(1, 2, 3, 4, 5)
            ]
        ];
        $j = new Json([
            'array' => $arr
        ], false);
        $j->setIsFormatted(true);
        $this->assertEquals('{'."\r\n"
                . '    "array":['."\r\n"
                . '        ['."\r\n"
                . '            {'."\r\n"
                . '                "null":null'."\r\n"
                . '            },'."\r\n"
                . '            ['."\r\n"
                . '                "hello"'."\r\n"
                . '            ],'."\r\n"
                . '            {'."\r\n"
                . '                "property-00":1,'."\r\n"
                . '                "property-01":2,'."\r\n"
                . '                "property-02":3'."\r\n"
                . '            }'."\r\n"
                . '        ]'."\r\n"
                . '    ]'."\r\n"
                . '}', $j.'');
    }
    /**
     * @test
     */
    public function testAddBoolean00() {
        $j = new Json();
        $j->addBoolean('bool ',true);
        $this->assertEquals('{"bool":true}',$j.'');
    }
    /**
     * @test
     */
    public function testAddNumber00() {
        $j = new Json();
        $j->addNumber('   number',33);
        $this->assertEquals('{"number":33}',$j.'');
    }
    /**
     * @test
     */
    public function testAddObj00() {
        $j = new Json();
        $obj = new Obj0('Hello',0,true,null,'he');
        $j->addObject('object',$obj);
        $this->assertEquals('{"object":{"Property00":"Hello","Property01":0,"Property02":true,"Property04":"he"}}',$j.'');
    }

    /**
     * @test
     */
    public function testAddObj01() {
        $j = new Json();
        $obj = new Obj1('Hello',0,true,null,'he');
        $j->addObject('object',$obj);
        $this->assertEquals('{"object":{"property-00":"Hello","property-01":0,"property-02":true}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest00() {
        $j = new Json();
        $this->assertFalse($j->addString('','Hello World!'));
        $this->assertFalse($j->addString('  ','Hello World!'));
        $this->assertFalse($j->addString("\r\n",'Hello World!'));
        $this->assertEquals('{}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest01() {
        $j = new Json();
        $this->assertTrue($j->addString('hello','Hello World!'));
        $this->assertEquals('{"hello":"Hello World!"}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest02() {
        $j = new Json();
        $this->assertFalse($j->addBoolean('invalid-boolean','falseX'));
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars00() {
        $str = 'I\'m "Good".';
        $result = Json::escapeJSONSpecialChars($str);
        $this->assertEquals('I\'m \"Good\".',$result);
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars01() {
        $str = 'Path: "C:/Windows/Media/onestop.midi"\n';
        $result = Json::escapeJSONSpecialChars($str);
        $this->assertEquals('Path: \"C:\/Windows\/Media\/onestop.midi\"\\\\n',$result);
    }
    /**
     * @test
     */
    public function testEscJSonSpecialChars02() {
        $str = '\tI\'m good. But "YOU" are "Better".\r\n'
                .'\\An inline comment is good.';
        $result = Json::escapeJSONSpecialChars($str);
        $this->assertEquals('\\\\tI\'m good. But \"YOU\" are \"Better\".\\\\r\\\\n'
                .'\\\\An inline comment is good.',$result);
    }
    /**
     * @test
     */
    public function testFormat00() {
        $j = new Json([],true);
        $this->assertEquals("{\r\n}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat01() {
        $j = new Json([],true);
        $j->addBoolean('hello');
        $this->assertEquals("{\r\n"
                .'    "hello":true'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat02() {
        $j = new Json([],true);
        $j->addNumber('hello',66);
        $this->assertEquals("{\r\n"
                .'    "hello":66'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat03() {
        $j = new Json([],true);
        $j->addString('hello','world');
        $j->addString('hello2','another string');
        $this->assertEquals("{\r\n"
                .'    "hello":"world",'."\r\n"
                .'    "hello2":"another string"'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat04() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[]);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":['."\r\n"
                .'    ]'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat05() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[1,2,3,4]);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":['."\r\n"
                .'        1,'."\r\n"
                .'        2,'."\r\n"
                .'        3,'."\r\n"
                .'        4'."\r\n"
                .'    ]'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat06() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[[],["hello world"]]);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":['."\r\n"
                .'        ['."\r\n"
                .'        ],'."\r\n"
                .'        ['."\r\n"
                .'            "hello world"'."\r\n"
                .'        ]'."\r\n"
                .'    ]'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat07() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[[],["hello world",["another sub","with two elements"]]]);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":['."\r\n"
                .'        ['."\r\n"
                .'        ],'."\r\n"
                .'        ['."\r\n"
                .'            "hello world",'."\r\n"
                .'            ['."\r\n"
                .'                "another sub",'."\r\n"
                .'                "with two elements"'."\r\n"
                .'            ]'."\r\n"
                .'        ]'."\r\n"
                .'    ]'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat08() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[],true);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":{'."\r\n"
                .'    }'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat09() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[1,2,3,"hello mr ali"],true);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":{'."\r\n"
                .'        "0":1,'."\r\n"
                .'        "1":2,'."\r\n"
                .'        "2":3,'."\r\n"
                .'        "3":"hello mr ali"'."\r\n"
                .'    }'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat10() {
        $j = new Json([],true);
        $j->addArray('hello-arr',["is-good" => "You are good",2,3,"hello mr ali",[],["a sub with element","hello" => 'world']],true);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":{'."\r\n"
                .'        "is-good":"You are good",'."\r\n"
                .'        "0":2,'."\r\n"
                .'        "1":3,'."\r\n"
                .'        "2":"hello mr ali",'."\r\n"
                .'        "3":{'."\r\n"
                .'        },'."\r\n"
                .'        "4":{'."\r\n"
                .'            "0":"a sub with element",'."\r\n"
                .'            "hello":"world"'."\r\n"
                .'        }'."\r\n"
                .'    }'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat11() {
        $j = new Json([],true);
        $obj = new Obj1('Hello',0,true,null,'he');
        $j->addObject('object',$obj);
        $this->assertEquals('{'."\r\n"
                .'    "object":{'."\r\n"
                .'        "property-00":"Hello",'."\r\n"
                .'        "property-01":0,'."\r\n"
                .'        "property-02":true'."\r\n"
                .'    }'."\r\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat12() {
        $j = new Json([],true);
        $obj = new Obj1('Hello',0,true,null,'he');
        $j->addArray('array',[$obj]);
        $this->assertEquals('{'."\r\n"
                .'    "array":['."\r\n"
                .'        {'."\r\n"
                .'            "property-00":"Hello",'."\r\n"
                .'            "property-01":0,'."\r\n"
                .'            "property-02":true'."\r\n"
                .'        }'."\r\n"
                .'    ]'."\r\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat13() {
        $j = new Json([],true);
        $obj = new Obj1('Hello',0,true,null,'he');
        $j->addArray('array',[$obj],true);
        $this->assertEquals('{'."\r\n"
                .'    "array":{'."\r\n"
                .'        "0":{'."\r\n"
                .'            "property-00":"Hello",'."\r\n"
                .'            "property-01":0,'."\r\n"
                .'            "property-02":true'."\r\n"
                .'        }'."\r\n"
                .'    }'."\r\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat14() {
        $j = new Json([],true);
        $obj = new Obj1('Hello',0,true,null,'he');
        $j->addArray('array',["my-obj" => $obj,"empty-arr" => []],true);
        $this->assertEquals('{'."\r\n"
                .'    "array":{'."\r\n"
                .'        "my-obj":{'."\r\n"
                .'            "property-00":"Hello",'."\r\n"
                .'            "property-01":0,'."\r\n"
                .'            "property-02":true'."\r\n"
                .'        },'."\r\n"
                .'        "empty-arr":{'."\r\n"
                .'        }'."\r\n"
                .'    }'."\r\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat15() {
        $j = new Json([
            "hello" => "world",
            'object' => new Obj0('8',7,'6','5',4),
            'null' => null,
            'nan' => NAN,
            'inf' => INF,
            'bool' => true,
            'number' => 667,
            'jsonx' => new Json(['sub-json-x' => new Json()])
        ],true);
        $this->assertEquals(''
                .'{'."\r\n"
                .'    "hello":"world",'."\r\n"
                .'    "object":{'."\r\n"
                .'        "Property00":"8",'."\r\n"
                .'        "Property01":7,'."\r\n"
                .'        "Property02":"6",'."\r\n"
                .'        "Property04":4'."\r\n"
                .'    },'."\r\n"
                .'    "null":null,'."\r\n"
                .'    "nan":"NaN",'."\r\n"
                .'    "inf":"Infinity",'."\r\n"
                .'    "bool":true,'."\r\n"
                .'    "number":667,'."\r\n"
                .'    "jsonx":{'."\r\n"
                .'        "sub-json-x":{'."\r\n"
                .'        }'."\r\n"
                .'    }'."\r\n"
                .'}'
                .'',$j.'');
    }
    /**
     * @test
     */
    public function testFormat16() {
        $j = new Json([],true);
        $j->addArray('hello-arr',[new Json(),new Json(['hello' => "world"])]);
        $this->assertEquals("{\r\n"
                .'    "hello-arr":['."\r\n"
                .'        {'."\r\n"
                .'        },'."\r\n"
                .'        {'."\r\n"
                .'            "hello":"world"'."\r\n"
                .'        }'."\r\n"
                .'    ]'."\r\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat17() {
        $j = new Json([],true);
        $j->addArray('Hello_arr',["my-j" => new Json(),new Json(['Hello_x' => "world"])],true);
        $this->assertEquals("{\r\n"
                .'    "Hello_arr":{'."\r\n"
                .'        "my-j":{'."\r\n"
                .'        },'."\r\n"
                .'        "0":{'."\r\n"
                .'            "Hello_x":"world"'."\r\n"
                .'        }'."\r\n"
                .'    }'."\r\n"
                ."}",$j.'');
        $this->assertEquals(['Hello_arr'],$j->getPropsNames());
        $j->setPropsStyle('kebab');
        $this->assertEquals(['hello-arr'],$j->getPropsNames());
    }
    /**
     * @test
     */
    public function testGetKeyValue00() {
        $j = new Json();
        $this->assertNull($j->get('not-exist'));
        $j->add('hello','world');
        $obj = new Obj0('8',7,'6','5',4);
        $j->add('object',$obj);
        $j->add('null',null);
        $j->add('nan',NAN);
        $j->add('inf',INF);
        $j->add('bool',true);
        $j->add('number',667);
        $this->assertEquals('world',$j->get('  hello  '));
        $this->assertEquals($obj,$j->get('  object  '));
        $this->assertNull($j->get('null'));
        $this->assertTrue($j->get('bool'));
        $this->assertEquals(667,$j->get('number'));
    }
    /**
     * @test
     */
    public function testHasKeyValue00() {
        $j = new Json();
        $j->setPropsStyle('snake');
        
        $j->add('hello-hero','world');
        $obj = new Obj0('8',7,'6','5',4);
        $j->add('object_1',$obj);
        $j->add('nullVal',null);
        
        $this->assertTrue($j->hasKey('helloHero'));
        $this->assertTrue($j->hasKey('hello-hero'));
        $this->assertTrue($j->hasKey('hello_hero'));
        
        $j->setPropsStyle('kebab');
        $this->assertTrue($j->hasKey(' null-val '));
        $this->assertTrue($j->hasKey(' nullVal'));
        $this->assertTrue($j->hasKey(' null_val        '));
    }
    /**
     * @test
     */
    public function testHasKeyValue01() {
        $j = new Json();
        $j->setPropsStyle('none');
        
        $j->add('hello-hero','world');
        $obj = new Obj0('8',7,'6','5',4);
        $j->add('object_1',$obj);
        $j->add('nullVal',null);
        
        $this->assertFalse($j->hasKey('helloHero'));
        $this->assertTrue($j->hasKey('hello-hero'));
        $this->assertFalse($j->hasKey('hello_hero'));
        
    }
    /**
     * @test
     */
    public function testPropCase00() {
        $j = new Json();
        $j->setPropsStyle('camel');
        $j->add('hello','world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id',1);
        $this->assertTrue($j->hasKey('userId'));
        $j->add('user_email',1);
        $this->assertTrue($j->hasKey('userEmail'));
        $j->add('user-Display-Name',1);
        $this->assertTrue($j->hasKey('userDisplayName'));
        
        $j->setPropsStyle('snake');
        $this->assertTrue($j->hasKey('user_display_name'));
        $this->assertTrue($j->hasKey('user-display-name'));
        $this->assertTrue($j->hasKey('user_email'));
        $this->assertTrue($j->hasKey('user_id'));
        
        $j->setPropsStyle('kebab');
        $this->assertTrue($j->hasKey('user-display-name'));
        $this->assertTrue($j->hasKey('user-email'));
        $this->assertTrue($j->hasKey('user-id'));
    }
    /**
     * @test
     */
    public function testPropCase01() {
        $j = new Json();
        $j->setPropsStyle('snake');
        $j->add('hello','world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id',1);
        $this->assertTrue($j->hasKey('user_id'));
        $j->add('user_email',1);
        $this->assertTrue($j->hasKey('user_email'));
        $j->add('userDisplayName',1);
        $this->assertTrue($j->hasKey('user_display_name'));
        
        $j->setPropsStyle('camel');
        $this->assertTrue($j->hasKey('userDisplayName'));
        $this->assertTrue($j->hasKey('userEmail'));
        $this->assertTrue($j->hasKey('userId'));
        
        $j->setPropsStyle('kebab');
        $this->assertTrue($j->hasKey('user-display-name'));
        $this->assertTrue($j->hasKey('user-email'));
        $this->assertTrue($j->hasKey('user-id'));
    }
    /**
     * @test
     */
    public function testRemove00() {
        $j = new Json([
            'p1' => 1,
            'p2' => 'hello',
            'p3' => null
        ]);
        $this->assertTrue($j->hasKey('p2'));
        $prop = $j->remove('p2');
        $this->assertFalse($j->hasKey('p2'));
        $this->assertEquals('hello', $prop->getValue());
    }
    /**
     * @test
     */
    public function testPropCase02() {
        $j = new Json();
        $j->setPropsStyle('kebab');
        $j->add('hello','world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id',1);
        $this->assertTrue($j->hasKey('user-id'));
        $j->add('user_email',1);
        $this->assertTrue($j->hasKey('user-email'));
        $j->add('userDisplayName',1);
        $this->assertTrue($j->hasKey('user-display-name'));
        
        $j->setPropsStyle('camel');
        $this->assertTrue($j->hasKey('userDisplayName'));
        $this->assertTrue($j->hasKey('userEmail'));
        $this->assertTrue($j->hasKey('userId'));
        
        $j->setPropsStyle('snake');
        $this->assertTrue($j->hasKey('user_display_name'));
        $this->assertTrue($j->hasKey('user_email'));
        $this->assertTrue($j->hasKey('user_id'));
    }
    /**
     * @test
     */
    public function testStyle() {
        define('JSON_PROP_STYLE','snake');
        $json = new Json();
        $this->assertEquals('snake',$json->getPropStyle());
    }
}
