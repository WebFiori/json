<?php

require_once __DIR__ . '/../vendor/autoload.php';

use WebFiori\Json\Json;

// Basic usage: convert a Json object to a plain PHP array
$json = new Json([
    'name' => 'Ibrahim',
    'age' => 30,
    'active' => true,
]);
$array = $json->toArray();
print_r($array);
// Output:
// Array
// (
//     [name] => Ibrahim
//     [age] => 30
//     [active] => 1
// )

// Nested Json objects become nested arrays
$address = new Json([
    'city' => 'Riyadh',
    'country' => 'SA',
]);
$json->add('address', $address);
$array = $json->toArray();
print_r($array);
// Output:
// Array
// (
//     [name] => Ibrahim
//     [age] => 30
//     [active] => 1
//     [address] => Array
//         (
//             [city] => Riyadh
//             [country] => SA
//         )
// )

// Arrays within Json are preserved
$json2 = new Json([
    'framework' => 'WebFiori',
    'features' => ['routing', 'cli', 'database', 'mail'],
]);
$array2 = $json2->toArray();
print_r($array2);
// Output:
// Array
// (
//     [framework] => WebFiori
//     [features] => Array
//         (
//             [0] => routing
//             [1] => cli
//             [2] => database
//             [3] => mail
//         )
// )

// This is equivalent to json_decode($json . '', true) but without the
// overhead of encoding to string and parsing back.
$equivalent = json_decode($json2 . '', true);
var_dump($array2 === $equivalent); // bool(true)
