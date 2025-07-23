<?php
namespace WebFiori\Tests\Json;

use WebFiori\Json\Json;
use WebFiori\Tests\Obj0;
use WebFiori\Tests\Obj1;
use PHPUnit\Framework\TestCase;
use WebFiori\Json\Property;

/**
 * Description of PropertyTest
 *
 * @author Ibrahim
 */
class PropertyTest extends TestCase {
    /**
     * @test
     */
    public function testConstructor00() {
        $this->expectException(\InvalidArgumentException::class);
        $prop = new Property('', 'Hello');
    }
    /**
     * @test
     */
    public function testConstructor01() {
        $this->expectException(\InvalidArgumentException::class);
        $prop = new Property('   ', 'Hello');
    }
    /**
     * @test
     */
    public function testConstructor02() {
        $prop = new Property('hello', 'Hello');
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals('Hello', $prop->getValue());
        $this->assertEquals('string', $prop->getType());
        $this->assertEquals('json:string', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor03() {
        $prop = new Property('hello', 33);
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals(33, $prop->getValue());
        $this->assertEquals('integer', $prop->getType());
        $this->assertEquals('json:number', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor04() {
        $prop = new Property('hello', 33.8);
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals(33.8, $prop->getValue());
        $this->assertEquals('double', $prop->getType());
        $this->assertEquals('json:number', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor05() {
        $prop = new Property('hello', true);
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals(true, $prop->getValue());
        $this->assertEquals('boolean', $prop->getType());
        $this->assertEquals('json:boolean', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor06() {
        $prop = new Property('hello', null);
        $this->assertEquals('hello', $prop->getName());
        $this->assertNull($prop->getValue());
        $this->assertEquals('NULL', $prop->getType());
        $this->assertEquals('json:null', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor07() {
        $prop = new Property('hello', []);
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals([], $prop->getValue());
        $this->assertEquals('array', $prop->getType());
        $this->assertEquals('json:array', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor08() {
        $prop = new Property('hello', new Json());
        $this->assertEquals('hello', $prop->getName());
        $this->assertTrue($prop->getValue() instanceof Json);
        $this->assertEquals('object', $prop->getType());
        $this->assertEquals('json:object', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor09() {
        $prop = new Property('hello', new Obj0('1', 2, 3, 4, 5));
        $this->assertEquals('hello', $prop->getName());
        $this->assertTrue($prop->getValue() instanceof Json);
        $this->assertEquals('object', $prop->getType());
        $this->assertEquals('json:object', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor10() {
        $prop = new Property('hello', new Obj1('1', 2, 3, 4, 5));
        $this->assertEquals('hello', $prop->getName());
        $this->assertTrue($prop->getValue() instanceof Json);
        $this->assertEquals('object', $prop->getType());
        $this->assertEquals('json:object', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor11() {
        $prop = new Property('hello', NAN);
        $this->assertEquals('hello', $prop->getName());
        $this->assertTrue(is_nan($prop->getValue()));
        $this->assertEquals('double', $prop->getType());
        $this->assertEquals('json:string', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testConstructor12() {
        $prop = new Property('hello', INF);
        $this->assertEquals('hello', $prop->getName());
        $this->assertEquals(INF, $prop->getValue());
        $this->assertEquals('double', $prop->getType());
        $this->assertEquals('json:string', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testSetAsObject00() {
        $prop = new Property('hello', []);
        $this->assertFalse($prop->isAsObject());
        $prop->setAsObject(true);
        $this->assertTrue($prop->isAsObject());
        $this->assertEquals('json:object', $prop->getJsonXTagName());
    }
    /**
     * @test
     */
    public function testSetName00() {
        $prop = new Property('hello', []);
        $this->assertEquals('hello', $prop->getName());
        $this->assertTrue($prop->setName('new-name'));
        $this->assertEquals('new-name', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetName01() {
        $prop = new Property('hello', []);
        $this->assertEquals('hello', $prop->getName());
        $this->assertFalse($prop->setName(''));
        $this->assertEquals('hello', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle00() {
        $prop = new Property('hello-world', []);
        $this->assertEquals('hello-world', $prop->getName());
        $prop->setStyle('camel');
        $this->assertEquals('helloWorld', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle01() {
        $prop = new Property('hello-world', []);
        $this->assertEquals('hello-world', $prop->getName());
        $prop->setStyle('snake');
        $this->assertEquals('hello_world', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle02() {
        $prop = new Property('hello_world', []);
        $this->assertEquals('hello_world', $prop->getName());
        $prop->setStyle('kebab');
        $this->assertEquals('hello-world', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle03() {
        $prop = new Property('helloWorld', []);
        $this->assertEquals('helloWorld', $prop->getName());
        $prop->setStyle('kebab');
        $this->assertEquals('hello-world', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle04() {
        $prop = new Property('helloWorld', []);
        $this->assertEquals('helloWorld', $prop->getName());
        $prop->setStyle('snake');
        $this->assertEquals('hello_world', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle05() {
        $prop = new Property('hello-world', []);
        $this->assertEquals('hello-world', $prop->getName());
        $prop->setStyle('camel', 'upper');
        $this->assertEquals('HELLOWORLD', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetStyle06() {
        $prop = new Property('hello-world', []);
        $this->assertEquals('hello-world', $prop->getName());
        $prop->setStyle('camel', 'lower');
        $this->assertEquals('helloworld', $prop->getName());
    }
    /**
     * @test
     */
    public function testSetValue00() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('world', $prop->getValue());
        $prop->setValue(33);
        $this->assertEquals(33, $prop->getValue());
        $this->assertEquals('integer', $prop->getType());
    }
    /**
     * @test
     */
    public function testSetValue01() {
        $prop = new Property('hello', 'world');
        $this->assertEquals('world', $prop->getValue());
        $prop->setValue(new Obj0('1', 2, 3, 4, 5));
        $this->assertTrue($prop->getValue() instanceof Json);
        $this->assertEquals('object', $prop->getType());
    }
}
