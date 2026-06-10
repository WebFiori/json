<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonIgnore;
use WebFiori\Json\JsonProperty;

class Product {
    #[JsonProperty('product_sku')]
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

    #[JsonProperty('on_sale')]
    public function getAvailable(): bool { return $this->available; }

    public function getDiscount(): ?float { return null; }

    #[JsonIgnore]
    public function getSecretMargin(): float { return 0.42; }
}

// Style 'none' — getter names stay PascalCase (backward compatible)
$json = new Json([], 'none');
$product = new Product('Keyboard', 49.99, true);
$json->addObject('product', $product);
echo $json."\n";
// {"product":{"Name":"Keyboard","Price":49.99,"on_sale":true,"Discount":null,"product_sku":"ABC-001"}}

// Style 'snake' — getter names normalized, #[JsonProperty] names preserved
$json = new Json([], 'snake');
$product = new Product('Keyboard', 49.99, true);
$json->addObject('product', $product);
echo $json."\n";
// {"product":{"name":"Keyboard","price":49.99,"on_sale":true,"discount":null,"product_sku":"ABC-001"}}
