<?php
namespace jsonx\tests;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Obj0
 *
 * @author Ibrahim
 */
class Obj0 {
    private $property00;
    private $property01;
    private $property02;
    private $property03;
    private $property04;
    public function __construct($prop00,$prop01,$prop02,$prop03,$prop04) {
        $this->property00 = $prop00;
        $this->property01 = $prop01;
        $this->property02 = $prop02;
        $this->property03 = $prop03;
        $this->property04 = $prop04;
    }
    public function getProperty00() {
        return $this->property00;
    }
    public function getProperty01() {
        return $this->property01;
    }
    public function getProperty02() {
        return $this->property02;
    }
    public function getProperty04() {
        return $this->property04;
    }
}
