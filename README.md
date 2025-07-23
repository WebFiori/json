# WebFiori Json

A helper class library for creating JSON or JSONx strings in PHP. It can be used to create well-formatted JSON strings from any variable type (strings, numbers, booleans, arrays, and even objects).

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

## Table of Contents
* [What is JSON?](#what-is-json)
* [Features](#features)
* [Supported PHP Versions](#supported-php-versions)
* [Installation](#installation)
* [Basic Usage](#basic-usage)
  * [Example](#example)
  * [Using the Constructor](#using-the-constructor)
* [Converting Properties Case](#converting-properties-case)
  * [Available Styles](#available-styles)
  * [Letter Case Options](#letter-case-options)
* [Reading From Files](#reading-from-files)
* [Working With Objects](#working-with-objects)
  * [Using JsonI Interface](#using-jsoni-interface)
  * [Auto-Mapping Objects](#auto-mapping-objects)
* [Decoding JSON String](#decoding-json-string)
* [Storing Output](#storing-output)
* [Working With Arrays](#working-with-arrays)
  * [Arrays as Objects](#arrays-as-objects)
* [JSONx](#jsonx)
* [Error Handling](#error-handling)
* [API Reference](#api-reference)

## What is JSON?

According to [json.org](https://www.json.org/json-en.html), JSON is a data exchange format which is based partially on JavaScript. It is easy for humans to read and for machines to understand. JSON data is represented as pairs of keys and values.

## Features
* Support for creating well-formatted JSON with proper indentation and escaping
* Support for creating [JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx) (XML representation of JSON)
* Ability to decode JSON strings and convert them to `Json` objects
* Ability to read JSON files and map JSON values to PHP data types
* Ability to manipulate JSON properties as needed
* Support for different property naming styles (camelCase, kebab-case, snake_case)
* Support for different letter cases (same, upper, lower)
* Customizable object serialization through the `JsonI` interface

## Supported PHP Versions
|                                                                                        Build Status                                                                                         |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php80.yml"><img src="https://github.com/WebFiori/json/workflows/php80.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php81.yml"><img src="https://github.com/WebFiori/json/workflows/php81.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php82.yml"><img src="https://github.com/WebFiori/json/workflows/php82.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php83.yml"><img src="https://github.com/WebFiori/json/workflows/php83.yml/badge.svg?branch=master"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php84.yml"><img src="https://github.com/WebFiori/json/workflows/php84.yml/badge.svg?branch=master"></a> |

## Installation
If you are using composer to manage your dependencies, then it is possible to install the library by including the entry `"webfiori/jsonx":"*"` in the `require` section of your `composer.json` file to install the latest release:

```json
{
    "require": {
        "webfiori/jsonx": "*"
    }
}
```

Alternatively, you can install a specific version:

```json
{
    "require": {
        "webfiori/jsonx": "^1.0"
    }
}
```

Another way to include the library is by going to [releases](https://github.com/WebFiori/json/releases) and downloading the latest release, then extracting the compressed file content and adding it to your include directory.

## Basic Usage
The process of using the classes is very simple. What you have to do is the following steps:

  * Import (or include) the class `Json` from the namespace `WebFiori\Json`
  * Create an instance of the class
  * Add data as needed using the various `add` methods
  * Output the object using `echo` command or any similar one

### Example
The following code shows a very simple usage example:

```php
//load the class "Json"
require_once 'vendor/autoload.php'; // If using Composer
// OR require_once 'path/to/WebFiori/Json/Json.php'; // If manually installed

use WebFiori\Json\Json;

//initialize an object of the class Json
$j = new Json();

//add a number attribute
$j->addNumber('my-number', 34);

//add a boolean with 'false' as its value. 
$j->addBoolean('my-boolean', false);

//add a string
$j->addString('a-string', 'Hello, I\'m Json! I like "JSON". ');

header('content-type:application/json');

// Output the JSON string
echo $j;
```

The output of the code will be:

```json
{
    "my-number":34,
    "my-boolean":false,
    "a-string":"Hello, I'm Json! I like \"JSON\". "
}
```

### Using the Constructor

You can also add data directly using the constructor by passing an associative array:

```php
use WebFiori\Json\Json;

$jsonObj = new Json([
    'first-name' => 'Ibrahim',
    'last-name' => 'BinAlshikh',
    'age' => 26,
    'is-married' => true,
    'mobile-number' => null
]);

echo $jsonObj;
```

The JSON output of this code will be:

```json
{
    "first-name":"Ibrahim",
    "last-name":"BinAlshikh",
    "age":26,
    "is-married":true,
    "mobile-number":null
}
```

## Converting Properties Case

The library supports different property naming styles and letter cases. You can set these when creating a Json object or change them later.

### Available Styles

The following property naming styles are supported:

* `none`: Keep the property names as they are provided
* `camel`: Convert property names to camelCase
* `kebab`: Convert property names to kebab-case
* `snake`: Convert property names to snake_case

### Letter Case Options

The following letter case options are available:

* `same`: Keep the letter case as provided
* `upper`: Convert all letters to uppercase
* `lower`: Convert all letters to lowercase

Example:

```php
use WebFiori\Json\Json;

// Set style and case in constructor
$json = new Json([], 'camel', 'lower');

// Add properties
$json->add('first-name', 'Ibrahim');
$json->add('last-name', 'BinAlshikh');

echo $json;
```

Output:

```json
{
    "firstname":"Ibrahim",
    "lastname":"BinAlshikh"
}
```

You can also change the style after creating the object:

```php
$json->setPropsStyle('snake', 'upper');
echo $json;
```

Output:

```json
{
    "FIRST_NAME":"Ibrahim",
    "LAST_NAME":"BinAlshikh"
}
```

## Reading From Files

The library provides a static method to read JSON data from files:

```php
use WebFiori\Json\Json;

try {
    $jsonObj = Json::fromJsonFile('/path/to/file.json');
    
    // Access properties
    $value = $jsonObj->get('propertyName');
    
    echo $value;
} catch (\WebFiori\Json\JsonException $ex) {
    echo 'Error: ' . $ex->getMessage();
}
```

## Working With Objects

### Using JsonI Interface

For custom object serialization, you can implement the `JsonI` interface:

```php
use WebFiori\Json\Json;
use WebFiori\Json\JsonI;

class Person implements JsonI {
    private $firstName;
    private $lastName;
    private $age;
    
    public function __construct($firstName, $lastName, $age) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
    }
    
    public function toJSON(): Json {
        $json = new Json();
        $json->addString('first-name', $this->firstName);
        $json->addString('last-name', $this->lastName);
        $json->addNumber('age', $this->age);
        
        return $json;
    }
}

$json = new Json();
$person = new Person('Ibrahim', 'BinAlshikh', 30);
$json->addObject('person', $person);

echo $json;
```

Output:

```json
{
    "person":{
        "first-name":"Ibrahim",
        "last-name":"BinAlshikh",
        "age":30
    }
}
```

### Auto-Mapping Objects

If an object doesn't implement the `JsonI` interface, the library will try to map its public getter methods:

```php
class User {
    private $username;
    private $email;
    
    public function __construct($username, $email) {
        $this->username = $username;
        $this->email = $email;
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    public function getEmail() {
        return $this->email;
    }
}

$json = new Json();
$user = new User('ibrahimBin', 'ibrahim@example.com');
$json->addObject('user', $user);

echo $json;
```

Output:

```json
{
    "user":{
        "Username":"ibrahimBin",
        "Email":"ibrahim@example.com"
    }
}
```

## Decoding JSON String

You can decode a JSON string into a `Json` object:

```php
use WebFiori\Json\Json;

$jsonString = '{"name":"Ibrahim","age":30,"city":"Riyadh"}';

try {
    $jsonObj = Json::decode($jsonString);
    
    // Access properties
    echo $jsonObj->get('name'); // Outputs: Ibrahim
    echo $jsonObj->get('age');  // Outputs: 30
    echo $jsonObj->get('city'); // Outputs: Riyadh
} catch (\WebFiori\Json\JsonException $ex) {
    echo 'Error: ' . $ex->getMessage();
}
```

## Storing Output

You can save the JSON output to a file:

```php
use WebFiori\Json\Json;

$json = new Json([
    'name' => 'Ibrahim',
    'age' => 30,
    'city' => 'Riyadh'
]);

try {
    $json->toJsonFile('data', '/path/to/directory', true);
    // This will create /path/to/directory/data.json
    
    echo 'File saved successfully!';
} catch (\WebFiori\Json\JsonException $ex) {
    echo 'Error: ' . $ex->getMessage();
}
```

## Working With Arrays

You can add arrays to your JSON object:

```php
use WebFiori\Json\Json;

$json = new Json();

// Simple array
$json->addArray('numbers', [1, 2, 3, 4, 5]);

// Array of objects
$json->addArray('users', [
    ['name' => 'Ibrahim', 'age' => 30],
    ['name' => 'Jane', 'age' => 25],
    ['name' => 'Bob', 'age' => 40]
]);

echo $json;
```

Output:

```json
{
    "numbers":[1,2,3,4,5],
    "users":[
        {"name":"Ibrahim","age":30},
        {"name":"Jane","age":25},
        {"name":"Bob","age":40}
    ]
}
```

### Arrays as Objects

You can also represent arrays as objects:

```php
use WebFiori\Json\Json;

$json = new Json();

$json->addArray('data', [
    'name' => 'Ibrahim',
    'age' => 30,
    'skills' => ['PHP', 'JavaScript', 'Python']
], true); // true means represent as object

echo $json;
```

Output:

```json
{
    "data":{
        "name":"Ibrahim",
        "age":30,
        "skills":["PHP","JavaScript","Python"]
    }
}
```

## JSONx

JSONx is an IBM standard format that represents JSON as XML. The library supports converting JSON to JSONx:

```php
use WebFiori\Json\Json;

$json = new Json([
    'name' => 'Ibrahim',
    'age' => 30,
    'isEmployed' => true,
    'address' => [
        'city' => 'Riyadh',
        'country' => 'Saudi Arabia'
    ]
]);

// Output as JSONx
echo $json->toJSONxString();
```

Output:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" 
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
             xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">
    <json:string name="name">Ibrahim</json:string>
    <json:number name="age">30</json:number>
    <json:boolean name="isEmployed">true</json:boolean>
    <json:object name="address">
        <json:string name="city">Riyadh</json:string>
        <json:string name="country">Saudi Arabia</json:string>
    </json:object>
</json:object>
```

## Error Handling

The library uses the `JsonException` class for error handling:

```php
use WebFiori\Json\Json;

try {
    // Attempt to decode invalid JSON
    $jsonObj = Json::decode('{invalid json}');
} catch (\WebFiori\Json\JsonException $ex) {
    echo 'Error code: ' . $ex->getCode() . "\n";
    echo 'Error message: ' . $ex->getMessage();
}
```

## API Reference

### Main Classes

- **Json**: The main class for creating and manipulating JSON data
- **JsonConverter**: Handles conversion between JSON and other formats
- **Property**: Represents a property in a JSON object
- **CaseConverter**: Utility for converting between different naming styles
- **JsonI**: Interface for objects that can be converted to JSON
- **JsonException**: Exception class for JSON-related errors
- **JsonTypes**: Constants for JSON data types

### Key Methods

#### Json Class
- `__construct(array $initialData = [], ?string $propsStyle = '', ?string $lettersCase = '', bool $isFormatted = false)`
- `add(string $key, $value, $arrayAsObj = false): bool`
- `addString(string $key, $val): bool`
- `addNumber(string $key, $value): bool`
- `addBoolean($key, $val = true): bool`
- `addNull(string $key): bool`
- `addArray(string $key, $value, $asObject = false): bool`
- `addObject(string $key, &$val): bool`
- `get($key): mixed`
- `hasKey($key): bool`
- `remove($keyName): ?Property`
- `setPropsStyle(string $style, string $lettersCase = 'same'): void`
- `setIsFormatted($bool): void`
- `toJSONString(): string`
- `toJSONxString(): string`
- `toJsonFile(string $fileName, string $path, bool $override = false): void`

#### Static Methods
- `Json::decode($jsonStr): Json`
- `Json::fromJsonFile($pathToJsonFile): Json`

For more information and advanced use cases, check [the official documentation](https://webfiori.com/learn/webfiori-json).
