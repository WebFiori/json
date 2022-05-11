<?php
namespace jsonx\tests;

use webfiori\json\JsonI;
use webfiori\json\Json;
/**
 * Description of Obj1
 *
 * @author Ibrahim
 */
class Obj1 extends Obj0 implements JsonI {
    //put your code here
    public function toJSON() : Json {
        $j = new Json();
        $j->add('property-00', $this->getProperty00());
        $j->add('property-01', $this->getProperty01());
        $j->add('property-02', $this->getProperty02());

        return $j;
    }
}
