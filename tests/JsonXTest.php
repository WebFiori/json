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
    }
    /**
     * @test
     */
    public function testToJsonString09() {
        $arr = [NAN,INF];
        $j = new JsonX();
        $j->addArray('arr', $arr, true);
        $this->assertEquals('{"arr":{"0":"NAN", "1":"INF"}}', $j->toJSONString());
        $j->setIsFormatted(true);
        $this->assertEquals('{'."\n"
                . '    "arr":{'."\n"
                . '        "0":"NAN", '."\n"
                . '        "1":"INF"'."\n"
                . '    }'."\n"
                . '}', $j->toJSONString());
        $j->setIsFormatted(false);
        $this->assertEquals('{"arr":{"0":"NAN", "1":"INF"}}', $j->toJSONString());
    }
    /**
     * @test
     */
    public function testToJsonString10() {
        $j = new JsonX();
        $subJ = new JsonX([
            'number-one' => 1,
            'arr' => [],
            'obj' => new JsonX()
        ]);
        $j->add('jsonx', $subJ);
        $j->add('o', new Obj1('1', 2, 3, 4, '5'));
        $this->assertEquals('{"jsonx":{"number-one":1, "arr":[], "obj":{}}, '
                . '"o":{"property-00":"1", "property-01":2, "property-02":3}}', $j.'');
        $j->setPropsStyle('snake');
        $this->assertEquals('{"jsonx":{"number_one":1, "arr":[], "obj":{}}, '
                . '"o":{"property_00":"1", "property_01":2, "property_02":3}}', $j.'');
        $j->setIsFormatted(true);
        $this->assertEquals('{'."\n"
                . '    "jsonx":{'."\n"
                . '        "number_one":1, '."\n"
                . '        "arr":['."\n"
                . '        ], '."\n"
                . '        "obj":{'."\n"
                . '        }'."\n"
                . '    }, '."\n"
                . '    "o":{'."\n"
                . '        "property_00":"1", '."\n"
                . '        "property_01":2, '."\n"
                . '        "property_02":3'."\n"
                . '    }'."\n"
                . '}', $j.'');
        $subX = $j->get('jsonx');
        $this->assertEquals('{'."\n"
                . '    "number_one":1, '."\n"
                . '    "arr":['."\n"
                . '    ], '."\n"
                . '    "obj":{'."\n"
                . '    }'."\n"
                . '}', $subX->toJSONString());
        
        $j->get('jsonx')->add('general', new Obj0('1', '3', 99, 100, "ok"));
        $this->assertEquals('{'."\n"
                . '    "jsonx":{'."\n"
                . '        "number_one":1, '."\n"
                . '        "arr":['."\n"
                . '        ], '."\n"
                . '        "obj":{'."\n"
                . '        }, '."\n"
                . '        "general":{'."\n"
                . '            "property00":"1", '."\n"
                . '            "property01":"3", '."\n"
                . '            "property02":99, '."\n"
                . '            "property04":"ok"'."\n"
                . '        }'."\n"
                . '    }, '."\n"
                . '    "o":{'."\n"
                . '        "property_00":"1", '."\n"
                . '        "property_01":2, '."\n"
                . '        "property_02":3'."\n"
                . '    }'."\n"
                . '}', $j.'');
        $j->setIsFormatted(false);
        $this->assertEquals('{"jsonx":{"number_one":1, "arr":[], "obj":{}, "general":{"property00":"1", "property01":"3", "property02":99, "property04":"ok"}}, "o":{"property_00":"1", "property_01":2, "property_02":3}}', $j.'');
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
                new Obj0('1', 2, 3, 4, 5), 
                new JsonX(['good'=>true])
            ], 
            new JsonX(['bad'=>false])
        ];
        $json = new JsonX();
        $json->addArray('array', $arr);
        $this->assertEquals('{"array":[['
                . '"sub-arr", 1, 2, {"hello":"world"}, {"Property00":"1", "Property01":2, "Property02":3, "Property04":5}, '
                . '{"good":true}'
                . '], {"bad":false}]}', $json.'');
        $json->remove('array');
        $json->addArray('x-array', $arr, true);
        $this->assertEquals('{"x-array":{"0":{"0":"sub-arr", "1":1, "2":2, "hello":"world", '
                . '"3":{"Property00":"1", "Property01":2, "Property02":3, "Property04":5}, "4":{"good":true}}, "1":{"bad":false}}}', $json.'');
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
    public function testDecode01() {
        $jsonStr = '{"Hello":"world","true":true,"false":false}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $this->assertEquals("world",$decoded->get('Hello'));
        $this->assertTrue($decoded->get('true'));
        $this->assertFalse($decoded->get('false'));
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
    /**
     * @test
     */
    public function testDecode03() {
        $jsonStr = '{"array":["world","one",1,"two",2.4,"null",null,true,false]}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $arr = $decoded->get('array');
        $this->assertTrue(gettype($arr) == 'array');
        $this->assertEquals('world', $arr[0]);
        $this->assertEquals('one', $arr[1]);
        $this->assertEquals(1, $arr[2]);
        $this->assertEquals('two', $arr[3]);
        $this->assertEquals(2.4, $arr[4]);
        $this->assertEquals('null', $arr[5]);
        $this->assertNull( $arr[6]);
        $this->assertTrue($arr[7]);
        $this->assertFalse($arr[8]);
    }
    /**
     * @test
     */
    public function testDecode04() {
        $jsonStr = '{"object":{"true":true,"false":false,"null":null,"str":"A string", "number":33, "array":["Hello"]}}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $jObj = $decoded->get('object');
        $this->assertTrue($jObj instanceof JsonX);
        $this->assertTrue($jObj->get('true'));
        $this->assertFalse($jObj->get('false'));
        $this->assertNull($jObj->get('null'));
        $this->assertEquals('A string',$jObj->get('str'));
        $this->assertEquals(33,$jObj->get('number'));
        $arr = $jObj->get('array');
        $this->assertTrue(gettype($arr) == 'array');
        $this->assertEquals("Hello", $arr[0]);
    }
    /**
     * @test
     */
    public function testDecode06() {
        $jsonStr = '{"prop-1":1,"prop-2":"hello","prop-3":true}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $this->assertEquals(1, $decoded->get('prop-1'));
        $this->assertEquals('hello', $decoded->get('prop-2'));
        $this->assertTrue($decoded->get('prop-3'));
    }
    /**
     * @test
     */
    public function testDecode07() {
        $jsonStr = '{prop-1:1}';
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue(gettype($decoded) == 'array');
        $this->assertEquals(4, $decoded['error-code']);
        $this->assertEquals('Syntax error', $decoded['error-message']);
    }
    /**
     * @test
     */
    public function testDecod08() {
        $jsonxObj =JsonX::decode('{"hello":"world", "sub-obj":{}, "an-array":[]}');
        $this->assertEquals('{"hello":"world", "sub-obj":{}, "an-array":[]}', $jsonxObj.'');
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
        $decoded = JsonX::decode($jsonStr);
        $this->assertTrue($decoded instanceof JsonX);
        $this->assertEquals('{'
                . '"obj":{"array":["world", {"hell":"no"}, ["one", 1]]},'
                . ' "outer-arr":[{"hello":"world", "deep-arr":["deep"]}]}', $decoded.'');
        $jObj = $decoded->get('obj');
        $this->assertTrue($jObj instanceof JsonX);
        $objArr = $jObj->get('array');
        $this->assertTrue(gettype($objArr) == 'array');
        $this->assertEquals("world", $objArr[0]);
        $this->assertTrue($objArr[1] instanceof JsonX);
        $this->assertEquals("no", $objArr[1]->get('hell'));
        $this->assertTrue(gettype($objArr[2]) == 'array');
        $this->assertEquals("one",$objArr[2][0]);
        $this->assertEquals(1,$objArr[2][1]);
        
        $outerArr = $decoded->get('outer-arr');
        $this->assertTrue(gettype($outerArr) == 'array');
        $this->assertTrue($outerArr[0] instanceof JsonX);
        $this->assertEquals("world",$outerArr[0]->get('hello'));
        $this->assertTrue(gettype($outerArr[0]->get('deep-arr')) == 'array');
        
        
    }
    /**
     * @test
     */
    public function testFromFile00() {
        $this->assertNull(JsonX::fromFile(ROOT.DIRECTORY_SEPARATOR.'not-exist.json'));
        $arr = JsonX::fromFile(ROOT.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'Obj0.php');
        $this->assertTrue(gettype($arr) == 'array');
        $jsonx = JsonX::fromFile(ROOT.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'composer.json');
        $this->assertTrue($jsonx instanceof JsonX);
        $packagesArr = $jsonx->get('packages');
        $this->assertEquals(5, count($packagesArr));
        $package5 = $packagesArr[4];
        $this->assertTrue($package5 instanceof JsonX);
        $this->assertEquals('webfiori/rest-easy', $package5->get('name'));
        $this->assertEquals([
                "Web APIs",
                "api",
                "json",
                "library",
                "php"
            ], $package5->get('keywords'));
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
                .'{"Property00":"Nice", "Property01":"To", "Property02":99, "Property04":"NAN"}, '
                .'[4, 1.7, true, null], '
                .'{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'{"test":true}, '
                .'[[{"Property00":"p0", "Property01":"p1", "Property02":"p2", "Property04":"p4"}, '
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
                .'"0":{"Property00":"Nice", "Property01":"To", "Property02":99, "Property04":"NAN"}, '
                .'"1":{"0":4, "1":1.7, "2":true, "3":null}, '
                .'"2":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'"3":{"test":true}, '
                .'"4":{"0":{"0":{"Property00":"p0", "Property01":"p1", "Property02":"p2", "Property04":"p4"}, '
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
                .'"object-0":{"Property00":"Nice", "Property01":"To", "Property02":99, "Property04":"NAN"}, '
                .'"array-0":{"0":4, "1":1.7, "2":true, "3":null, "4":true, "5":false}, '
                .'"object-1":{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'"jsonx-obj":{"test":true}, '
                .'"array-1":{"0":{"0":{"Property00":"p0", "Property01":"p1", "Property02":"p2", "Property04":"p4"}, '
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
                .'{"Property00":"Nice", "Property01":"To", "Property02":99, "Property04":"NAN"}, '
                .'[4, 1.7, true, null, true, false], '
                .'{"property-00":"1", "property-01":"Hello", "property-02":"No"}, '
                .'{"test":true}, '
                .'[[{"Property00":"p0", "Property01":"p1", "Property02":"p2", "Property04":"p4"}, '
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
        $this->assertEquals('{"object":{"Property00":"Hello", "Property01":0, "Property02":true, "Property04":"he"}}',$j.'');
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
                .'        "Property00":"8", '."\n"
                .'        "Property01":7, '."\n"
                .'        "Property02":"6", '."\n"
                .'        "Property04":4'."\n"
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
    public function testHasKeyValue00() {
        $j = new JsonX();
        $j->setPropsStyle('snake');
        
        $j->add('hello-hero', 'world');
        $obj = new Obj0('8', 7, '6', '5', 4);
        $j->add('object_1', $obj);
        $j->add('nullVal', null);
        
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
        $j = new JsonX();
        $j->setPropsStyle('none');
        
        $j->add('hello-hero', 'world');
        $obj = new Obj0('8', 7, '6', '5', 4);
        $j->add('object_1', $obj);
        $j->add('nullVal', null);
        
        $this->assertFalse($j->hasKey('helloHero'));
        $this->assertTrue($j->hasKey('hello-hero'));
        $this->assertFalse($j->hasKey('hello_hero'));
        
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
    /**
     * @test
     */
    public function testStyle() {
        define('JSONX_PROP_STYLE', 'snake');
        $json = new JsonX();
        $this->assertEquals('snake', $json->getPropStyle());
    }
}
