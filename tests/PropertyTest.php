<?php
namespace jsonx\tests;

use webfiori\json\Json;
use jsonx\tests\Obj0;
use jsonx\tests\Obj1;
use PHPUnit\Framework\TestCase;
use webfiori\json\Property;

/**
 * Description of ProbertyTest
 *
 * @author Ibrahim
 */
class PropertyTest extends TestCase {
    /**
     * @test
     */
    public function testConstructor00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals('world', $prop->getValue());
        $this->assertEquals('string', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor01() {
        $prop = new Property('a-number', 1);
        $this->assertEquals('a-number', $prop->getName());
        $this->assertEquals(1, $prop->getValue());
        $this->assertEquals('integer', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor02() {
        $prop = new Property('a-double', 1.3, 'snake');
        $this->assertEquals('a_double', $prop->getName());
        $this->assertEquals(1.3, $prop->getValue());
        $this->assertEquals('double', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor03() {
        $obj = new Obj0(1, 2, 3, 4, 5);
        $prop = new Property('an_obj', $obj, 'camel');
        $this->assertEquals('anObj', $prop->getName());
        $this->assertSame($obj, $prop->getValue());
        $this->assertEquals('object', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor04() {
        $prop = new Property('aBool', true, 'kebab');
        $this->assertEquals('a-bool', $prop->getName());
        $this->assertTrue($prop->getValue());
        $this->assertEquals('boolean', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor05() {
        $prop = new Property('aBool', false, 'kebab');
        $this->assertEquals('a-bool', $prop->getName());
        $this->assertFalse($prop->getValue());
        $this->assertEquals('boolean', $prop->getType());
    }
    /**
     * @test
     */
    public function testConstructor06() {
        $prop = new Property('null_val', null, 'kebab');
        $this->assertEquals('null-val', $prop->getName());
        $this->assertNull($prop->getValue());
        $this->assertEquals('NULL', $prop->getType());
    }
}
