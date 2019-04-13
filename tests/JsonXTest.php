<?php
use PHPUnit\Framework\TestCase;
use jsonx\JsonX;

class JsonXTest extends TestCase{
    public function test1(){
        $j = new JsonX();
        $isAdded = $j->addString('hello', 'Hello World!');
        $this->assertEquals($isAdded,TRUE);
    }
}
