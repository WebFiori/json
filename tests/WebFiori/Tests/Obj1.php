<?php
namespace WebFiori\Tests;

use WebFiori\Json\JsonI;
use WebFiori\Json\Json;
/**
 * Description of Obj1
 *
 * @author Ibrahim
 */
class Obj1 implements JsonI {
    private $prop00;
    private $prop01;
    private $prop02;
    private $prop03;
    private $prop04;
    public function __construct($prop00='',$prop01='',$prop02='',$prop03='',$prop04='') {
        $this->prop00 = $prop00;
        $this->prop01 = $prop01;
        $this->prop02 = $prop02;
        $this->prop03 = $prop03;
        $this->prop04 = $prop04;
    }
    public function getProperty00() {
        return $this->prop00;
    }
    public function getProperty01() {
        return $this->prop01;
    }
    public function getProperty02() {
        return $this->prop02;
    }
    public function getProperty03() {
        return $this->prop03;
    }
    public function getProperty04() {
        return $this->prop04;
    }
    public function toJSON() : Json {
        $json = new Json();
        $json->addNumber('property-00', $this->getProperty00());
        $json->addNumber('property-01', $this->getProperty01());
        $json->add('property-02', $this->getProperty02());
        return $json;
    }
}
