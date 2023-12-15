# WebFiori Json

A helper class library for creating JSON or JSONx strings in PHP. It can be used to create well-formatted json strings from any variable type (strings, numbers, boolean arrays and even objects).

<p align="center">
  <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php83.yml">
    <img src="https://github.com/WebFiori/json/actions/workflows/php83.yml/badge.svg?branch=master">
  </a>
  <a href="https://codecov.io/gh/WebFiori/json">
    <img src="https://codecov.io/gh/WebFiori/json/branch/master/graph/badge.svg" />
  </a>
  <a href="https://sonarcloud.io/dashboard?id=WebFiori_json">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=WebFiori_json&metric=alert_status" />
  </a>
  <a href="https://packagist.org/packages/webfiori/jsonx">
    <img src="https://img.shields.io/packagist/dt/webfiori/jsonx?color=light-green">
  </a>
</p>

# Content
* [What is JSON?](what-is-json)
* [Features](#features)
* [Supported PHP Versions](#supported-php-versions)
* [Installation](#installation)
* [Code Samples](#code-samples)
* [Basic Usage](#basic-usage)
  * [Example](#example)
* [Converting Properties Case](#converting-properties-case)
* [Reading From Files](#reading-from-files)
* [Working With Objects](#working-with-objects)
* [Decoding JSON String](#decoding-json-string)
* [Storing Output](#storing-output)
* [Working With Arrays](#working-with-arrays)
* [JSONx](#jsonx]

## What is JSON?

According to [json.org](https://www.json.org/json-en.html), JSON is a data exchange format which is based partially on JavaScript. It is easy for humans to read and for machines to understand. JSON data is represented as pairs of keys and values.

## Features
* Support fo creating well formatted JSON.
* Support for creating [JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx).
* Ability to decode JSON strings and convert them to `Json` objects.
* Ability to read JSON files and map JSON values to PHP data types.
* Ability to manipulate JSON properties as needed.

## Supported PHP Versions
|                                                                                        Build Status                                                                                         |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php70.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php70.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php71.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php71.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php72.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php72.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php73.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php73.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php74.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php74.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php80.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php80.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php81.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php81.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php82.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php82.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php83.yml"><img src="https://github.com/WebFiori/json/actions/workflows/php83.yml/badge.svg?branch=master"></a> |


## Installation
If you are using composer to manage your dependencies, then it is possible to install the library by including the entry `"webfiori/jsonx":"*"` in the `require` section of your `composer.json` file to install the latest release. 

Another way to include the library is by going to [releases](https://github.com/WebFiori/json/releases) and download the latest release and extract compressed file content and add them to your include directory.

## Basic Usage
The process of using the classes is very simple. What you have to do is the following steps:

  * Import (or include) the class [`Json`](https://github.com/WebFiori/json/blob/master/webfiori/json/Json.php).
  * Create an instance of the class.
  * Add data as needed.
  * Output the object using `echo` command or any similar one.

For more information and advanced use cases, check [here](https://webfiori.com/learn/webfiori-json).

### Example
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

Following example shows how data can be added directly using the constructure.

``` php
$jsonObj = new Json([
    'first-name' => 'Ibrahim',
    'last-name' => 'BinAlshikh',
    'age' => 26,
    'is-married' => true,
    'mobile-number' => null
]);
```

The JSON output of this code will be the following:

``` json
{
    "first-name":"Ibrahim",
    "last-name":"BinAlshikh",
    "age":26,
    "is-married":true,
    "mobile-number":null
}
```

