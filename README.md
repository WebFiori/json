# WebFiori Json

A PHP library for creating and parsing JSON and JSONx strings. Supports all PHP scalar types, arrays, and objects with flexible property naming styles.

<p align="center">
  <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php84.yaml">
    <img src="https://github.com/WebFiori/json/actions/workflows/php84.yaml/badge.svg?branch=main">
  </a>
  <a href="https://codecov.io/gh/WebFiori/json">
    <img src="https://codecov.io/gh/WebFiori/json/branch/main/graph/badge.svg" />
  </a>
  <a href="https://sonarcloud.io/dashboard?id=WebFiori_json">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=WebFiori_json&metric=alert_status" />
  </a>
  <a href="https://packagist.org/packages/webfiori/jsonx">
    <img src="https://img.shields.io/packagist/dt/webfiori/jsonx?color=light-green">
  </a>
</p>

## Table of Contents
* [Features](#features)
* [Supported PHP Versions](#supported-php-versions)
* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Working With Arrays](#working-with-arrays)
* [Working With Objects](#working-with-objects)
  * [Using JsonI Interface](#using-jsoni-interface)
  * [Auto-Mapping Objects](#auto-mapping-objects)
* [Property Naming Styles](#property-naming-styles)
* [Decoding JSON](#decoding-json)
* [Saving to File](#saving-to-file)
* [JSONx](#jsonx)
* [Error Handling](#error-handling)
* [API Reference](#api-reference)

## Features
* Create well-formatted JSON strings from any PHP value (scalars, arrays, objects)
* Decode JSON strings and files into `Json` objects
* Flexible property naming styles: `camelCase`, `kebab-case`, `snake_case`, or `none`
* Letter case control: `same`, `upper`, `lower`
* Custom object serialization via the `JsonI` interface
* Auto-mapping of plain objects via public getter methods and public properties
* [JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx) output (XML representation of JSON)
* Save JSON output directly to a file

## Supported PHP Versions
|                                                                                        Build Status                                                                                         |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php81.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php81.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php82.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php82.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php83.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php83.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php84.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php84.yaml/badge.svg?branch=main"></a> |

## Installation

```json
{
    "require": {
        "webfiori/jsonx": "*"
    }
}
```

Or for a specific version:

```json
{
    "require": {
        "webfiori/jsonx": "^3.0"
    }
}
```

Then run:

```bash
composer install
```

## Basic Usage

```php
use WebFiori\Json\Json;

$json = new Json([
    'name'    => 'Ibrahim',
    'age'     => 30,
    'married' => false,
    'score'   => 9.5,
    'notes'   => null,
]);

echo $json;
```

Output:

```json
{"name":"Ibrahim","age":30,"married":false,"score":9.5,"notes":null}
```

You can also build the object incrementally using the `add*()` methods:

```php
$json = new Json();
$json->addString('name', 'Ibrahim');
$json->addNumber('age', 30);
$json->addBoolean('married', false);
$json->addNull('notes');

echo $json;
```

## Working With Arrays

```php
$json = new Json();

// Indexed array
$json->addArray('tags', ['php', 'json', 'api']);

// Associative array represented as a JSON object
$json->addArray('address', ['city' => 'Riyadh', 'country' => 'SA'], true);

echo $json;
```

Output:

```json
{"tags":["php","json","api"],"address":{"city":"Riyadh","country":"SA"}}
```

## Working With Objects

### Using JsonI Interface

Implement `JsonI` to fully control how an object is serialized:

```php
use WebFiori\Json\Json;
use WebFiori\Json\JsonI;

class User implements JsonI {
    public function __construct(
        private string $username,
        private string $email
    ) {}

    public function toJSON(): Json {
        return new Json(['username' => $this->username, 'email' => $this->email]);
    }
}

$json = new Json();
$user = new User('ibrahim', 'ibrahim@example.com');
$json->addObject('user', $user);

echo $json;
```

Output:

```json
{"user":{"username":"ibrahim","email":"ibrahim@example.com"}}
```

### Auto-Mapping Objects

For objects that don't implement `JsonI`, the library maps them automatically using two sources:

1. **Public getter methods** — any method prefixed with `get` is called and its return value is added. The property name is the method name with `get` stripped (e.g. `getName()` → `Name`). Methods returning `null` or `false` are skipped.
2. **Public properties** — extracted via reflection and added as-is, including those with a `null` value.

```php
class Product {
    public string $sku = 'ABC-001';       // added via reflection
    private string $name;
    private float $price;

    public function __construct(string $name, float $price) {
        $this->name  = $name;
        $this->price = $price;
    }

    public function getName(): string { return $this->name; }   // → "Name"
    public function getPrice(): float { return $this->price; }  // → "Price"
}

$json = new Json();
$product = new Product('Keyboard', 49.99);
$json->addObject('product', $product);

echo $json;
```

Output:

```json
{"product":{"Name":"Keyboard","Price":49.99,"sku":"ABC-001"}}
```

## Property Naming Styles

Four naming styles are supported: `none` (default), `camel`, `snake`, `kebab`.  
Three letter cases are supported: `same` (default), `upper`, `lower`.

Set them in the constructor or change them later with `setPropsStyle()`:

```php
$data = ['first-name' => 'Ibrahim', 'last-name' => 'Al-Shikh'];

echo new Json($data, 'none')  . "\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}
echo new Json($data, 'camel') . "\n"; // {"firstName":"Ibrahim","lastName":"Al-Shikh"}
echo new Json($data, 'snake') . "\n"; // {"first_name":"Ibrahim","last_name":"Al-Shikh"}
echo new Json($data, 'kebab') . "\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}

// Change style after construction
$json = new Json($data);
$json->setPropsStyle('snake', 'upper');
echo $json . "\n"; // {"FIRST_NAME":"Ibrahim","LAST_NAME":"Al-Shikh"}
```

## Decoding JSON

Decode a JSON string directly:

```php
$json = Json::decode('{"name":"Ibrahim","age":30}');

echo $json->get('name'); // Ibrahim
echo $json->get('age');  // 30
```

Read from a file:

```php
try {
    $json = Json::fromJsonFile('/path/to/file.json');
    echo $json->get('someKey');
} catch (\WebFiori\Json\JsonException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## Saving to File

```php
$json = new Json(['name' => 'Ibrahim', 'age' => 30]);

try {
    $json->toJsonFile('data', '/path/to/directory', true);
    // Creates /path/to/directory/data.json
} catch (\WebFiori\Json\JsonException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## JSONx

[JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx) is an IBM standard that represents JSON as XML:

```php
$json = new Json([
    'name'       => 'Ibrahim',
    'age'        => 30,
    'isEmployed' => true,
]);

echo $json->toJSONxString();
```

Output:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd"
             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
             xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">
    <json:string name="name">
        Ibrahim
    </json:string>
    <json:number name="age">
        30
    </json:number>
    <json:boolean name="isEmployed">
        true
    </json:boolean>
</json:object>
```

## Error Handling

All errors throw `\WebFiori\Json\JsonException`:

```php
try {
    $json = Json::decode('{invalid json}');
} catch (\WebFiori\Json\JsonException $e) {
    echo 'Error code: '    . $e->getCode()    . "\n";
    echo 'Error message: ' . $e->getMessage() . "\n";
}
```

## API Reference

### Classes

| Class | Description |
|-------|-------------|
| `Json` | Main class for building and reading JSON data |
| `JsonI` | Interface for custom object serialization |
| `JsonConverter` | Handles serialization to JSON and JSONx strings |
| `Property` | Represents a single JSON property |
| `CaseConverter` | Converts property names between naming styles |
| `JsonTypes` | Constants for JSON data types |
| `JsonException` | Exception thrown on JSON errors |

### Key Methods — `Json`

| Method | Description |
|--------|-------------|
| `add(string $key, mixed $value, bool $arrayAsObj = false): bool` | Add any value |
| `addString(string $key, string $val): bool` | Add a string |
| `addNumber(string $key, int\|float $value): bool` | Add a number |
| `addBoolean(string $key, bool $val = true): bool` | Add a boolean |
| `addNull(string $key): bool` | Add a null value |
| `addArray(string $key, array $value, bool $asObject = false): bool` | Add an array |
| `addObject(string $key, object &$val): bool` | Add an object |
| `get(string $key): mixed` | Get a property value |
| `hasKey(string $key): bool` | Check if a key exists |
| `remove(string $key): ?Property` | Remove a property |
| `setPropsStyle(string $style, string $lettersCase = 'same'): void` | Change naming style |
| `setIsFormatted(bool $bool): void` | Toggle formatted output |
| `toJSONString(): string` | Get JSON string |
| `toJSONxString(): string` | Get JSONx string |
| `toJsonFile(string $fileName, string $path, bool $override = false): void` | Save to file |
| `Json::decode(string $jsonStr): Json` | Decode a JSON string |
| `Json::fromJsonFile(string $path): Json` | Load from a JSON file |
