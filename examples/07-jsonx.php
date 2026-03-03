<?php
require_once __DIR__ . '/../vendor/autoload.php';

use WebFiori\Json\Json;

$json = new Json([
    'name'       => 'Ibrahim',
    'age'        => 30,
    'isEmployed' => true,
]);

echo $json->toJSONxString() . "\n";
