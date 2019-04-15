# jsonx
A JSON helper classes for creating JSON strings in PHP.

<p align="center">
  <a href="https://travis-ci.org/usernane/jsonx"><img src="https://travis-ci.org/usernane/jsonx.svg?branch=master"></a>
  <a href="https://codecov.io/gh/usernane/jsonx">
    <img src="https://codecov.io/gh/usernane/jsonx/branch/master/graph/badge.svg" />
  </a>
  <a href="https://paypal.me/IbrahimBinAlshikh">
    <img src="https://img.shields.io/endpoint.svg?url=https%3A%2F%2Fprogrammingacademia.com%2Fwebfiori%2Fapis%2Fshields-get-dontate-badget">
  </a>
</p>
# Usage
<p>
The process of using the classes is very simple. What you have to do is the following steps:
</p>
* Import the class "JsonX".
* Create an instance of the class.
* Add JSON data as needed.
* Output the object using 'echo' command or any similar one.
#Simple Example
<p>
The following code shows a very simple usage example.
</p>
```php
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
```
