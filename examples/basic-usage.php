<?php
//show any errors
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);


//load the class JsonX
require_once '../src/JsonX.php';
use jsonx\JsonX;

//initialize an object of the class JsonX
$j = new JsonX();

//add a number attribute
$j->addNumber('my-number', 34);

//add a boolean with 'false' as its value. 
$j->addBoolean('my-boolean', false);

//add a string
$j->addString('a-string', 'Hello, I\'m JsonX! I like "JSON". ');

header('content-type:application/json');
/*
send back the generated json string.
The output of the code will be like that:
{
    "my-number":34,
    "my-boolean":false,
    "my-number":"Hello, I'm JsonX! I like \"json\". ",
}
*/
echo $j;

