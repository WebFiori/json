<?php
namespace WebFiori\Tests;

class ObjWithPublicProps {
    public $name;
    public $age;
    public $active;
    private $secret = 'hidden';

    public function __construct($name, $age, $active) {
        $this->name = $name;
        $this->age = $age;
        $this->active = $active;
    }
}
