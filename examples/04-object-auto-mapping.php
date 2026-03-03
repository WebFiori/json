<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;

class Product {
    public string $sku = 'ABC-001';
    private string $name;
    private float $price;

    public function __construct(string $name, float $price) {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName(): string {
        return $this->name;
    }
    public function getPrice(): float {
        return $this->price;
    }
}

$json = new Json();
$product = new Product('Keyboard', 49.99);
$json->addObject('product', $product);

echo $json."\n";
// {"product":{"Name":"Keyboard","Price":49.99,"sku":"ABC-001"}}
// ^ getters produce "Name"/"Price", public property adds "sku"
