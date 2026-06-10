<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;

$json = new Json();
$json->addArray('tags', ['php', 'json', 'api']);

// Associative arrays are now auto-detected as JSON objects (no need for $asObject = true)
$json->addArray('address', ['city' => 'Riyadh', 'country' => 'SA']);

echo $json."\n";
// {"tags":["php","json","api"],"address":{"city":"Riyadh","country":"SA"}}
