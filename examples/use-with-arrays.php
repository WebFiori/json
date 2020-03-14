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

$arr = [1,'1','1.4',5.8,'hello'];
$arr2 = [
    'name' => 'Ibrahim',100,
    'age' => '25','sex' => 'M',true,false
];

//add the array as is
$j->add('basic-array', $arr);

//adding the array as object
$j->addArray('basic-array-as-object', $arr,true);

//adding the array as is
$j->add('complicated-array', $arr2);

//adding the array as object
$j->addArray('complicated-array-as-object', $arr2,true);
echo $j;
