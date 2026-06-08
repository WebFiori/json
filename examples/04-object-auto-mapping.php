<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonIgnore;

class Product {
    public string $sku = 'ABC-001';

    #[JsonIgnore]
    public string $internalCode = 'X-99';  // excluded

    private string $name;
    private float $price;
    private bool $available;

    public function __construct(string $name, float $price, bool $available) {
        $this->name      = $name;
        $this->price     = $price;
        $this->available = $available;
    }

    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getAvailable(): bool { return $this->available; }  // false is included
    public function getDiscount(): ?float { return null; }             // null is included

    #[JsonIgnore]
    public function getSecretMargin(): float { return 0.42; }          // excluded
}

$json = new Json();
$product = new Product('Keyboard', 49.99, false);
$json->addObject('product', $product);

echo $json."\n";
// {"product":{"Name":"Keyboard","Price":49.99,"Available":false,"Discount":null,"sku":"ABC-001"}}
// ^ false and null are serialized; #[JsonIgnore] properties/getters are excluded
