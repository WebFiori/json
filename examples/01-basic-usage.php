<?php
require_once __DIR__ . '/../vendor/autoload.php';

use WebFiori\Json\Json;

// Quick init via constructor
$json = new Json([
    'name'    => 'Ibrahim',
    'age'     => 30,
    'married' => false,
    'score'   => 9.5,
    'notes'   => null,
]);

echo $json . "\n";
// {"name":"Ibrahim","age":30,"married":false,"score":9.5,"notes":null}
