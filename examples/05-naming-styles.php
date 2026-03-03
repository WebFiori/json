<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;

$data = ['first-name' => 'Ibrahim', 'last-name' => 'Al-Shikh'];

echo new Json($data, 'none')."\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}
echo new Json($data, 'camel')."\n"; // {"firstName":"Ibrahim","lastName":"Al-Shikh"}
echo new Json($data, 'snake')."\n"; // {"first_name":"Ibrahim","last_name":"Al-Shikh"}
echo new Json($data, 'kebab')."\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}

// Change style after construction + apply upper case
$json = new Json($data);
$json->setPropsStyle('snake', 'upper');
echo $json."\n"; // {"FIRST_NAME":"Ibrahim","LAST_NAME":"Al-Shikh"}
