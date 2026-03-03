<?php
require_once __DIR__ . '/../vendor/autoload.php';

use WebFiori\Json\Json;

$json = new Json();
$json->addArray('tags', ['php', 'json', 'api']);
$json->addArray('address', ['city' => 'Riyadh', 'country' => 'SA'], true); // true = as object

echo $json . "\n";
// {"tags":["php","json","api"],"address":{"city":"Riyadh","country":"SA"}}
