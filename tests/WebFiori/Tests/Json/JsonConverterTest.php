<?php

namespace WebFiori\Tests\Json;

use WebFiori\Json\Json;
use WebFiori\Tests\Obj0;
use WebFiori\Tests\Obj1;
use WebFiori\Tests\ObjWithPublicProps;
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
                . '</json:string>'."\r\n", JsonConverter::propertyToJsonXString($prop));
    }
    /**
     * @test
     */
    public function testPropertyToJsonXString01() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('<json:string>'."\r\n"
                . '    world'."\r\n"
                . '</json:string>'."\r\n", JsonConverter::propertyToJsonXString($prop, false));
    }

    /**
     * @test
     */
    public function testObjectToJsonPublicPropsBasic() {
        $obj = new \WebFiori\Tests\ObjWithPublicProps('Ibrahim', 30, true);
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json instanceof Json);
        $this->assertTrue($json->hasKey('name'));
        $this->assertTrue($json->hasKey('age'));
        $this->assertTrue($json->hasKey('active'));
    }
    /**
     * @test
     */
    public function testObjectToJsonPublicPropsValues() {
        $obj = new \WebFiori\Tests\ObjWithPublicProps('Ibrahim', 30, true);
        $json = JsonConverter::objectToJson($obj);
        $this->assertEquals('Ibrahim', $json->get('name'));
        $this->assertEquals(30, $json->get('age'));
        $this->assertEquals(true, $json->get('active'));
    }
    /**
     * @test
     */
    public function testObjectToJsonPrivatePropsNotIncluded() {
        $obj = new \WebFiori\Tests\ObjWithPublicProps('Ibrahim', 30, true);
        $json = JsonConverter::objectToJson($obj);
        $this->assertFalse($json->hasKey('secret'));
    }
    /**
     * @test
     */
    public function testObjectToJsonPublicPropsNullValue() {
        $obj = new \WebFiori\Tests\ObjWithPublicProps('Ibrahim', 30, true);
        $obj->name = null;
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json->hasKey('name'));
        $this->assertNull($json->get('name'));
    }
    /**
     * @test
     * @see https://github.com/WebFiori/json/issues/57
     */
    public function testGetterReturningNullIsIncluded() {
        $obj = new \WebFiori\Tests\ObjWithNullFalseGetters('Ibrahim', null, true);
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json->hasKey('MiddleName'));
        $this->assertNull($json->get('MiddleName'));
    }
    /**
     * @test
     * @see https://github.com/WebFiori/json/issues/57
     */
    public function testGetterReturningFalseIsIncluded() {
        $obj = new \WebFiori\Tests\ObjWithNullFalseGetters('Ibrahim', 'Ali', false);
        $json = JsonConverter::objectToJson($obj);
        $this->assertTrue($json->hasKey('Active'));
        $this->assertFalse($json->get('Active'));
    }
    /**
     * @test
     * @see https://github.com/WebFiori/json/issues/57
     */
    public function testJsonIgnoreOnGetter() {
        $obj = new \WebFiori\Tests\ObjWithNullFalseGetters('Ibrahim', null, true);
        $json = JsonConverter::objectToJson($obj);
        $this->assertFalse($json->hasKey('Secret'));
    }
    /**
     * @test
     * @see https://github.com/WebFiori/json/issues/57
     */
    public function testJsonIgnoreOnPublicProperty() {
        $obj = new \WebFiori\Tests\ObjWithIgnoredProps();
        $json = JsonConverter::objectToJson($obj);
        $this->assertFalse($json->hasKey('internalId'));
        $this->assertTrue($json->hasKey('name'));
        $this->assertEquals('Ibrahim', $json->get('name'));
        $this->assertTrue($json->hasKey('email'));
        $this->assertNull($json->get('email'));
    }
}
