<?php

require_once __DIR__.'/../vendor/autoload.php';

use WebFiori\Json\Json;

// Set application-wide defaults
Json::setDefaults(style: 'camel', case: 'lower', formatted: false);

$json = new Json(['first-name' => 'Ibrahim', 'last-name' => 'Al-Shikh']);
echo $json."\n"; // {"firstname":"ibrahim","lastname":"al-shikh"}

// Constructor parameters override defaults
$json = new Json(['first-name' => 'Ibrahim'], 'snake');
echo $json."\n"; // {"first_name":"ibrahim"}

// Reset to library defaults (style: none, case: same, formatted: false)
Json::resetDefaults();

$json = new Json(['first-name' => 'Ibrahim']);
echo $json."\n"; // {"first-name":"Ibrahim"}
