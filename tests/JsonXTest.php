<?php

use jsonx\JsonX;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;
use PHPUnit\Framework\TestCase;

class JsonXTest extends TestCase {
    /**
     * @test
     */
    public function testToJsonString00() {
        $j = new JsonX(['hello'=>'world']);
        $this->assertEquals('{"hello":"world"}', $j->toJSONString());
        $this->assertEquals('world', $j->get('hello'));
    }
    /**
     * @test
     */
    public function testToJsonString01() {
        $j = new JsonX(['number'=>100]);
        $this->assertEquals('{"number":100}', $j->toJSONString());
        $this->assertSame(100, $j->get('number'));
    }
    /**
     * @test
     */
    public function testToJsonString02() {
        $j = new JsonX(['number'=>20.2235]);
        $this->assertEquals('{"number":20.2235}', $j->toJSONString());
        $this->assertSame(20.2235, $j->get('number'));
    }
    /**
     * @test
     */
    public function testToJsonString03() {
        $j = new JsonX(['number'=>NAN]);
        $this->assertEquals('{"number":"NAN"}', $j->toJSONString());
        $this->assertTrue(is_nan($j->get('number')));
    }
    /**
     * @test
     */
    public function testToJsonString04() {
        $j = new JsonX(['number'=>INF]);
        $this->assertEquals('{"number":"INF"}', $j->toJSONString());
        $this->assertSame(INF, $j->get('number'));
    }
    /**
     * @test
     */
    public function testToJsonString05() {
        $j = new JsonX(['bool-true'=>true,'bool-false'=>false]);
        $this->assertEquals('{"bool-true":true, "bool-false":false}', $j->toJSONString());
        $this->assertSame(true, $j->get('bool-true'));
        $this->assertSame(false, $j->get('bool-false'));
    }
    /**
     * @test
     */
    public function testToJsonString06() {
        $j = new JsonX(['null'=>null]);
        $this->assertEquals('{"null":null}', $j->toJSONString());
        $this->assertNull($j->get('null'));
    }
    /**
     * @test
     */
    public function testToJsonString07() {
        $j = new JsonX(['array'=>['one',1]]);
        $this->assertEquals('{"array":["one", 1]}', $j->toJSONString());
        $this->assertEquals(['one', 1],$j->get('array'));
    }
    /**
     * @test
     */
    public function testToJsonString08() {
        $jx = new JsonX(['hello'=>'world']);
        $arr = ['one',1,null,1.8,true,false,NAN,INF,$jx,['two','good']];
        $j = new JsonX([
            'array'=>$arr
            ]);
        $this->assertEquals('{"array":["one", 1, null, 1.8, true, false, "NAN", "INF", {"hello":"world"}, ["two", "good"]]}', $j->toJSONString());
        $this->assertEquals($arr,$j->get('array'));
    }
    /**
     * @test
     */
    public function testDecode00() {
        $jsonStr = '{"Hello":"world"}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $this->assertEquals("world",$decoded->get('Hello'));
    }
    /**
     * @test
     */
    public function testDecode02() {
        $jsonStr = '{"Hello":"world","one":1,"two":2.4,"null":null}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $this->assertEquals("world",$decoded->get('Hello'));
        $this->assertEquals(1,$decoded->get('one'));
        $this->assertEquals(2.4,$decoded->get('two'));
        $this->assertNull($decoded->get('null'));
    }
    public function testAddMultiple00() {
        $j = new JsonX();
        $j->addMultiple([
            'user-id'=>5,
            'an-array'=>[1,2,3],
            'float'=>1.6,
            'bool'=>true
        ]);
        $this->assertEquals('{"user-id":5, "an-array":[1, 2, 3], "float":1.6, "bool":true}',$j.'');
    }
    /**
     * @test
     */
    public function testAdd00() {
        $j = new JsonX();
        $this->assertTrue($j->add('a-string', 'This is a string.'));
        $this->assertTrue($j->add('string-as-bool-1', 'NO',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-2', -1,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-3', 0,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-4', 1,['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-5', 't',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-6', 'Yes',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-not-as-bool', 'Yes'));
        $this->assertTrue($j->add('null-value', null));
        $this->assertTrue($j->add('infinity', INF));
        $this->assertTrue($j->add('not-a-number', INF));
    }
    /**
     * @test
     */
    public function testAdd01() {
        $j = new JsonX();
        $this->assertTrue($j->add('string-as-bool-1', 'NO',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-2', 'No',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-3', 'false',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-4', 'on',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-5', 't',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-as-bool-6', 'Yes',['string-as-boolean' => true]));
        $this->assertTrue($j->add('string-not-as-bool', 'Yes'));
        $this->assertEquals('{"string-as-bool-1":false, '
                .'"string-as-bool-2":false, '
                .'"string-as-bool-3":false, '
                .'"string-as-bool-4":true, '
                .'"string-as-bool-5":true, '
                .'"string-as-bool-6":true, '
                .'"string-not-as-bool":"Yes"}',$j.'');
        $this->assertTrue($j->add('string-as-bool-6', 'False',['string-as-boolean' => true]));
        $this->assertEquals('{"string-as-bool-1":false, '
                .'"string-as-bool-2":false, '
                .'"string-as-bool-3":false, '
                .'"string-as-bool-4":true, '
                .'"string-as-bool-5":true, '
                .'"string-as-bool-6":false, '
                .'"string-not-as-bool":"Yes"}',$j.'');
    }
    /**
     * @test
     */
    public function testAdd03() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            [4,1.7,true,null],
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            [[new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4')]]];
        $j->add('big-array', $arr);
        $this->assertEquals('{'
                .'"big-array":[{"hello":"world"}, '
                .'{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                .'[4, 1.7, true, null], '
                .'{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'{"test":true}, '
                .'[[{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                .'{"test":true}, '
                .'{"property-00":"p0", "property-01":"p1", "property-02":"p2"}]]]'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd04() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            [4,1.7,true,null],
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            [[new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4')]]];
        $j->add('big-array', $arr,['array-as-object' => true]);
        $this->assertEquals('{'
                .'"big-array":{"hello":"world", '
                .'"0":{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                .'"1":{"0":4, "1":1.7, "2":true, "3":null}, '
                .'"2":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'"3":{"test":true}, '
                .'"4":{"0":{"0":{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                .'"1":{"test":true}, '
                .'"2":{"property-00":"p0", "property-01":"p1", "property-02":"p2"}}}}'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd05() {
        $j = new JsonX();
        $this->assertFalse($j->add('  ',null));
        $this->assertTrue($j->add('null-value',null));
        $this->assertEquals('{"null-value":null}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd06() {
        $j = new JsonX();
        $this->assertFalse($j->add('boolean','null',['string-as-boolean' => true]));
    }
    /**
     * @test
     */
    public function testAdd07() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = [
            'hello' => 'world',
            'null' => null,
            'boolean' => true,
            'number' => 665,
            'str-as-bool' => 'f',
            'object-0' => new Obj0('Nice', 'To', 99, INF, NAN),
            'array-0' => [4,1.7,true,null,'t','f'],
            'object-1' => new Obj1('1', 'Hello', 'No', true, false),
            'jsonx-obj' => $subJ,
            'array-1' => [[new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4')]]];
        $j->add('big-array', $arr,['array-as-object' => true]);
        $this->assertEquals('{'
                .'"big-array":{"hello":"world", '
                .'"null":null, '
                .'"boolean":true, '
                .'"number":665, '
                .'"str-as-bool":false, '
                .'"object-0":{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                .'"array-0":{"0":4, "1":1.7, "2":true, "3":null, "4":true, "5":false}, '
                .'"object-1":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'"jsonx-obj":{"test":true}, '
                .'"array-1":{"0":{"0":{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                .'"1":{"test":true}, '
                .'"2":{"property-00":"p0", "property-01":"p1", "property-02":"p2"}}}}'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAdd08() {
        $j = new JsonX();
        $subJ = new JsonX();
        $subJ->add('test', true);
        $arr = [
            'hello' => 'world',
            new Obj0('Nice', 'To', 99, INF, NAN),
            [4,1.7,true,null,'t','f'],
            new Obj1('1', 'Hello', 'No', true, false),
            $subJ,
            [[new Obj0('p0', 'p1', 'p2', 'p3', 'p4'),$subJ,new Obj1('p0', 'p1', 'p2', 'p3', 'p4')]]];
        $j->add('big-array', $arr);
        $this->assertEquals('{'
                .'"big-array":[{"hello":"world"}, '
                .'{"prop-0":"Nice", "prop-1":"To", "prop-2":99, "prop-3":"NAN"}, '
                .'[4, 1.7, true, null, true, false], '
                .'{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'{"test":true}, '
                .'[[{"prop-0":"p0", "prop-1":"p1", "prop-2":"p2", "prop-3":"p4"}, '
                .'{"test":true}, '
                .'{"property-00":"p0", "property-01":"p1", "property-02":"p2"}]]]'
                .'}',$j->toJSONString());
    }
    /**
     * @test
     */
    public function testAddArray00() {
        $j = new JsonX();
        $arr = [];
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":[]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray01() {
        $j = new JsonX();
        $arr = [1,"Hello",true,NAN,null,99.8,INF];
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":[1, "Hello", true, "NAN", null, 99.8, "INF"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray02() {
        $j = new JsonX();
        $arr = [1,1.5,"Hello",true,NAN,null,INF];
        $j->addArray('arr', $arr,false);
        $this->assertEquals('{"arr":[1, 1.5, "Hello", true, "NAN", null, "INF"]}',$j.'');
    }
    /**
     * @test
     */
    public function testAddArray03() {
        $j = new JsonX();
        $arr = ["number" => 1,"Hello" => "world!","boolean" => true,NAN,null];
        $j->addArray('arr', $arr);
        $this->assertEquals('{"arr":[{"number":1}, {"Hello":"world!"}, {"boolean":true}, "NAN", null]}',$j.'');
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
    public function testAddNumber00() {
        $j = new JsonX();
        $j->addNumber('   number', 33);
        $this->assertEquals('{"number":33}',$j.'');
    }
    /**
     * @test
     */
    public function testAddObj00() {
        $j = new JsonX();
        $obj = new Obj0('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"prop-0":"Hello", "prop-1":0, "prop-2":true, "prop-3":"he"}}',$j.'');
    }

    /**
     * @test
     */
    public function testAddObj01() {
        $j = new JsonX();
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{"object":{"property-00":"Hello", "property-01":0, "property-02":true}}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest00() {
        $j = new JsonX();
        $this->assertFalse($j->addString('', 'Hello World!'));
        $this->assertFalse($j->addString('  ', 'Hello World!'));
        $this->assertFalse($j->addString("\n", 'Hello World!'));
        $this->assertEquals('{}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest01() {
        $j = new JsonX();
        $this->assertTrue($j->addString('hello', 'Hello World!'));
        $this->assertEquals('{"hello":"Hello World!"}',$j.'');
    }
    /**
     * @test
     */
    public function testAddStringTest02() {
        $j = new JsonX();
        $this->assertFalse($j->addString('invalid-boolean', 'falseX',true));
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
                .'\\An inline comment is good.';
        $result = JsonX::escapeJSONSpecialChars($str);
        $this->assertEquals('\\\\tI\'m good. But \"YOU\" are \"Better\".\\\\r\\\\n'
                .'\\\\An inline comment is good.',$result);
    }
    /**
     * @test
     */
    public function testFormat00() {
        $j = new JsonX([],true);
        $this->assertEquals("{\n}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat01() {
        $j = new JsonX([],true);
        $j->addBoolean('hello');
        $this->assertEquals("{\n"
                .'    "hello":true'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat02() {
        $j = new JsonX([],true);
        $j->addNumber('hello',66);
        $this->assertEquals("{\n"
                .'    "hello":66'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat03() {
        $j = new JsonX([],true);
        $j->addString('hello','world');
        $j->addString('hello2','another string');
        $this->assertEquals("{\n"
                .'    "hello":"world", '."\n"
                .'    "hello2":"another string"'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat04() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[]);
        $this->assertEquals("{\n"
                .'    "hello-arr":['."\n"
                .'    ]'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat05() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[1,2,3,4]);
        $this->assertEquals("{\n"
                .'    "hello-arr":['."\n"
                .'        1, '."\n"
                .'        2, '."\n"
                .'        3, '."\n"
                .'        4'."\n"
                .'    ]'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat06() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[[],["hello world"]]);
        $this->assertEquals("{\n"
                .'    "hello-arr":['."\n"
                .'        ['."\n"
                .'        ], '."\n"
                .'        ['."\n"
                .'            "hello world"'."\n"
                .'        ]'."\n"
                .'    ]'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat07() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[[],["hello world",["another sub","with two elements"]]]);
        $this->assertEquals("{\n"
                .'    "hello-arr":['."\n"
                .'        ['."\n"
                .'        ], '."\n"
                .'        ['."\n"
                .'            "hello world", '."\n"
                .'            ['."\n"
                .'                "another sub", '."\n"
                .'                "with two elements"'."\n"
                .'            ]'."\n"
                .'        ]'."\n"
                .'    ]'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat08() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[], true);
        $this->assertEquals("{\n"
                .'    "hello-arr":{'."\n"
                .'    }'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat09() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[1, 2, 3, "hello mr ali"], true);
        $this->assertEquals("{\n"
                .'    "hello-arr":{'."\n"
                .'        "0":1, '."\n"
                .'        "1":2, '."\n"
                .'        "2":3, '."\n"
                .'        "3":"hello mr ali"'."\n"
                .'    }'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat10() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',["is-good" => "You are good", 2, 3, "hello mr ali",[],["a sub with element","hello" => 'world']], true);
        $this->assertEquals("{\n"
                .'    "hello-arr":{'."\n"
                .'        "is-good":"You are good", '."\n"
                .'        "0":2, '."\n"
                .'        "1":3, '."\n"
                .'        "2":"hello mr ali", '."\n"
                .'        "3":{'."\n"
                .'        }, '."\n"
                .'        "4":{'."\n"
                .'            "0":"a sub with element", '."\n"
                .'            "hello":"world"'."\n"
                .'        }'."\n"
                .'    }'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat11() {
        $j = new JsonX([],true);
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addObject('object', $obj);
        $this->assertEquals('{'."\n"
                .'    "object":{'."\n"
                .'        "property-00":"Hello", '."\n"
                .'        "property-01":0, '."\n"
                .'        "property-02":true'."\n"
                .'    }'."\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat12() {
        $j = new JsonX([],true);
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addArray('array', [$obj]);
        $this->assertEquals('{'."\n"
                .'    "array":['."\n"
                .'        {'."\n"
                .'            "property-00":"Hello", '."\n"
                .'            "property-01":0, '."\n"
                .'            "property-02":true'."\n"
                .'        }'."\n"
                .'    ]'."\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat13() {
        $j = new JsonX([],true);
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addArray('array', [$obj], true);
        $this->assertEquals('{'."\n"
                .'    "array":{'."\n"
                .'        "0":{'."\n"
                .'            "property-00":"Hello", '."\n"
                .'            "property-01":0, '."\n"
                .'            "property-02":true'."\n"
                .'        }'."\n"
                .'    }'."\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat14() {
        $j = new JsonX([],true);
        $obj = new Obj1('Hello', 0, true, null, 'he');
        $j->addArray('array', ["my-obj" => $obj,"empty-arr" => []], true);
        $this->assertEquals('{'."\n"
                .'    "array":{'."\n"
                .'        "my-obj":{'."\n"
                .'            "property-00":"Hello", '."\n"
                .'            "property-01":0, '."\n"
                .'            "property-02":true'."\n"
                .'        }, '."\n"
                .'        "empty-arr":{'."\n"
                .'        }'."\n"
                .'    }'."\n"
                .'}',$j.'');
    }
    /**
     * @test
     */
    public function testFormat15() {
        $j = new JsonX([
            "hello" => "world",
            'object' => new Obj0('8', 7, '6', '5', 4),
            'null' => null,
            'nan' => NAN,
            'inf' => INF,
            'bool' => true,
            'number' => 667,
            'jsonx' => new JsonX(['sub-json-x' => new JsonX()])
        ], true);
        $this->assertEquals(''
                .'{'."\n"
                .'    "hello":"world", '."\n"
                .'    "object":{'."\n"
                .'        "prop-0":"8", '."\n"
                .'        "prop-1":7, '."\n"
                .'        "prop-2":"6", '."\n"
                .'        "prop-3":4'."\n"
                .'    }, '."\n"
                .'    "null":null, '."\n"
                .'    "nan":"NAN", '."\n"
                .'    "inf":"INF", '."\n"
                .'    "bool":true, '."\n"
                .'    "number":667, '."\n"
                .'    "jsonx":{'."\n"
                .'        "sub-json-x":{'."\n"
                .'        }'."\n"
                .'    }'."\n"
                .'}'
                .'',$j.'');
    }
    /**
     * @test
     */
    public function testFormat16() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',[new JsonX(),new JsonX(['hello' => "world"])]);
        $this->assertEquals("{\n"
                .'    "hello-arr":['."\n"
                .'        {'."\n"
                .'        }, '."\n"
                .'        {'."\n"
                .'            "hello":"world"'."\n"
                .'        }'."\n"
                .'    ]'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testFormat17() {
        $j = new JsonX([],true);
        $j->addArray('hello-arr',["my-j" => new JsonX(),new JsonX(['hello' => "world"])],true);
        $this->assertEquals("{\n"
                .'    "hello-arr":{'."\n"
                .'        "my-j":{'."\n"
                .'        }, '."\n"
                .'        "0":{'."\n"
                .'            "hello":"world"'."\n"
                .'        }'."\n"
                .'    }'."\n"
                ."}",$j.'');
    }
    /**
     * @test
     */
    public function testGetKeyValue00() {
        $j = new JsonX();
        $this->assertNull($j->get('not-exist'));
        $j->add('hello', 'world');
        $obj = new Obj0('8', 7, '6', '5', 4);
        $j->add('object', $obj);
        $j->add('null', null);
        $j->add('nan', NAN);
        $j->add('inf', INF);
        $j->add('bool', true);
        $j->add('number', 667);
        $this->assertEquals('world',$j->get('  hello  '));
        $this->assertEquals($obj,$j->get('  object  '));
        $this->assertNull($j->get('null'));
        $this->assertTrue($j->get('bool'));
        $this->assertEquals(667,$j->get('number'));
    }
    /**
     * @test
     */
    public function testPropCase00() {
        $j = new JsonX();
        $j->setPropsStyle('camel');
        $j->add('hello', 'world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id', 1);
        $this->assertTrue($j->hasKey('userId'));
        $j->add('user_email', 1);
        $this->assertTrue($j->hasKey('userEmail'));
        $j->add('user-Display-Name', 1);
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
        $j = new JsonX();
        $j->setPropsStyle('snake');
        $j->add('hello', 'world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id', 1);
        $this->assertTrue($j->hasKey('user_id'));
        $j->add('user_email', 1);
        $this->assertTrue($j->hasKey('user_email'));
        $j->add('userDisplayName', 1);
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
    public function testPropCase02() {
        $j = new JsonX();
        $j->setPropsStyle('kebab');
        $j->add('hello', 'world');
        $this->assertTrue($j->hasKey('hello'));
        $j->add('user-id', 1);
        $this->assertTrue($j->hasKey('user-id'));
        $j->add('user_email', 1);
        $this->assertTrue($j->hasKey('user-email'));
        $j->add('userDisplayName', 1);
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
}
