# WebFiori Json

A helper library for creating JSON strings in PHP. It can be used to create well-formatted json strings from any variable type (strings, numbers, boolean arrays and even objects). More information about JSON notation can be found at https://www.json.org/.

<p align="center">
  <img src="https://github.com/WebFiori/json/workflows/Build%20PHP%207,8/badge.svg?branch=master">
  <a href="https://codecov.io/gh/WebFiori/json">
    <img src="https://codecov.io/gh/WebFiori/json/branch/master/graph/badge.svg" />
  </a>
  <a href="https://packagist.org/packages/webfiori/jsonx">
    <img src="https://img.shields.io/packagist/dt/webfiori/jsonx?color=light-green">
  </a>
</p>

## API Docs
This library is a part of <a href="https://github.com/WebFiori/framework">WebFiori Framework</a>. API docs of the library can be found at https://webfiori.com/docs/webfiori/json.

## Supported PHP Versions
The library supports all versions from PHP 5.6 up to PHP 8.0.

## Usage
The process of using the classes is very simple. What you have to do is the following steps:

  * Import the class `Json`.
  * Create an instance of the class.
  * Add JSON data as needed.
  * Output the object using `echo` command or any similar one.

For more information and advanced use cases, check [here](https://webfiori.com/learn/webfiori-json).

## Simple Example
The following code shows a very simple usage example.

```php
//load the class "Json"
require_once 'Json.php';
use webfiori\json\Json;

//initialize an object of the class Json
$j = new Json();

//add a number attribute
$j->addNumber('my-number', 34);

//add a boolean with 'false' as its value. 
$j->addBoolean('my-boolean', false);

//add a string
$j->addString('a-string', 'Hello, I\'m Json! I like "JSON". ');

header('content-type:application/json');
/*
send back the generated json string.
The output of the code will be like that:
{
    "my-number":34,
    "my-boolean":false,
    "my-number":"Hello, I'm Json! I like \"json\". ",
}
*/
echo $j;
```


