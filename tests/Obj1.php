<?php
namespace jsonx\tests;

use jsonx\JsonI;
use jsonx\JsonX;
/**
 * Description of Obj1
 *
 * @author Ibrahim
 */
class Obj1 extends Obj0 implements JsonI {
    //put your code here
    public function toJSON() {
        $j = new JsonX();
        $j->add('property-00', $this->getProperty00());
        $j->add('property-01', $this->getProperty01());
        $j->add('property-02', $this->getProperty02());

        return $j;
    }
}
