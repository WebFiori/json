<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;
use WebFiori\Json\JsonException;

// Decode a JSON string
$json = Json::decode('{"name":"Ibrahim","age":30}');
echo $json->get('name')."\n"; // Ibrahim
echo $json->get('age')."\n"; // 30

// Read from file
try {
    $json = Json::fromJsonFile(__DIR__.'/sample.json');
    echo $json->get('product')."\n"; // Keyboard
    echo $json->get('price')."\n"; // 49.99
    echo ($json->get('inStock') ? 'true' : 'false')."\n"; // true
} catch (JsonException $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
