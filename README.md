# WebFiori Json

A PHP library for creating and parsing JSON and JSONx strings. Supports all PHP scalar types, arrays, and objects with flexible property naming styles.

<p align="center">
  <a href="https://github.com/WebFiori/json/actions/workflows/php84.yaml">
    <img src="https://github.com/WebFiori/json/actions/workflows/php84.yaml/badge.svg?branch=main">
  </a>
  <a href="https://codecov.io/gh/WebFiori/json">
    <img src="https://codecov.io/gh/WebFiori/json/branch/main/graph/badge.svg" />
  </a>
  <a href="https://sonarcloud.io/dashboard?id=WebFiori_json">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=WebFiori_json&metric=alert_status" />
  </a>
  <a href="https://github.com/WebFiori/json/releases">
      <img src="https://img.shields.io/github/release/WebFiori/json.svg?label=latest" />
  </a>
  <a href="https://packagist.org/packages/webfiori/jsonx">
      <img src="https://img.shields.io/packagist/dt/webfiori/jsonx?color=light-green">
  </a>
  <img src="https://img.shields.io/badge/php-%3E%3D8.1-blue" alt="PHP 8.1+">
</p>

## Table of Contents

- [Key Features](#key-features)
- [Supported PHP Versions](#supported-php-versions)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
  - [Working With Arrays](#working-with-arrays)
  - [Working With Objects](#working-with-objects)
  - [Property Naming Styles](#property-naming-styles)
  - [Decoding JSON](#decoding-json)
  - [Typed Deserialization](#typed-deserialization)
  - [Saving to File](#saving-to-file)
  - [JSONx](#jsonx)
- [Error Handling](#error-handling)
- [API Reference](#api-reference)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)
- [Changelog](#changelog)

## Key Features

- Create well-formatted JSON strings from any PHP value (scalars, arrays, objects)
- Decode JSON strings and files into `Json` objects
- Typed deserialization via `Json::decodeAs()` with nested object hydration
- Flexible property naming styles: `camelCase`, `kebab-case`, `snake_case`, or `none`
- Letter case control: `same`, `upper`, `lower`
- Custom object serialization via the `JsonI` interface
- Auto-mapping of plain objects via public getter methods and public properties
- Auto-detection of associative arrays as JSON objects
- Attribute-based control: `#[JsonProperty]`, `#[JsonIgnore]`, `#[JsonType]`
- Application-wide defaults via `Json::setDefaults()`
- [JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx) output (XML representation of JSON)
- Save JSON output directly to a file

## Supported PHP Versions

|                                                                                        Build Status                                                                                         |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php81.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php81.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php82.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php82.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php83.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php83.yaml/badge.svg?branch=main"></a> |
| <a target="_blank" href="https://github.com/WebFiori/json/actions/workflows/php84.yaml"><img src="https://github.com/WebFiori/json/actions/workflows/php84.yaml/badge.svg?branch=main"></a> |

## Installation

```bash
composer require webfiori/jsonx
```

## Quick Start

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

You can also build the object incrementally:

```php
$json = new Json();
$json->addString('name', 'Ibrahim');
$json->addNumber('age', 30);
$json->addBoolean('married', false);
$json->addNull('notes');
```

## Usage

### Working With Arrays

```php
$json = new Json();

// Indexed array
$json->addArray('tags', ['php', 'json', 'api']);

// Associative arrays are automatically encoded as JSON objects
$json->addArray('address', ['city' => 'Riyadh', 'country' => 'SA']);

echo $json;
```

Output:

```json
{"tags":["php","json","api"],"address":{"city":"Riyadh","country":"SA"}}
```

### Working With Objects

#### Using JsonI Interface

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
$json->addObject('user', new User('ibrahim', 'ibrahim@example.com'));
echo $json;
```

Output:

```json
{"user":{"username":"ibrahim","email":"ibrahim@example.com"}}
```

#### Auto-Mapping Objects

Objects that don't implement `JsonI` are mapped automatically using:

1. **Public getter methods** — any zero-parameter method prefixed with `get` is called. The property name is derived by stripping `get` (e.g. `getName()` → `Name` with style `none`, or `name` with style `camel`).
2. **Public properties** — extracted via reflection and added as-is.

Use `#[JsonIgnore]` to exclude specific getters or properties, and `#[JsonProperty]` to override the output name:

```php
use WebFiori\Json\Json;
use WebFiori\Json\JsonIgnore;
use WebFiori\Json\JsonProperty;

class Product {
    #[JsonProperty('product_sku')]
    public string $sku = 'ABC-001';

    #[JsonIgnore]
    public string $internalCode = 'X-99';

    private string $name;
    private float $price;

    public function __construct(string $name, float $price) {
        $this->name  = $name;
        $this->price = $price;
    }

    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }

    #[JsonProperty('on_sale')]
    public function getAvailable(): bool { return true; }

    #[JsonIgnore]
    public function getSecretMargin(): float { return 0.42; }
}

$json = new Json([], 'snake');
$json->addObject('product', new Product('Keyboard', 49.99));
echo $json;
```

Output:

```json
{"product":{"name":"Keyboard","price":49.99,"on_sale":true,"product_sku":"ABC-001"}}
```

### Property Naming Styles

Four naming styles are supported: `none` (default), `camel`, `snake`, `kebab`.  
Three letter cases are supported: `same` (default), `upper`, `lower`.

```php
$data = ['first-name' => 'Ibrahim', 'last-name' => 'Al-Shikh'];

echo new Json($data, 'none')  . "\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}
echo new Json($data, 'camel') . "\n"; // {"firstName":"Ibrahim","lastName":"Al-Shikh"}
echo new Json($data, 'snake') . "\n"; // {"first_name":"Ibrahim","last_name":"Al-Shikh"}
echo new Json($data, 'kebab') . "\n"; // {"first-name":"Ibrahim","last-name":"Al-Shikh"}
```

Set application-wide defaults:

```php
Json::setDefaults(style: 'camel', case: 'lower', formatted: false);
```

### Decoding JSON

Decode a JSON string:

```php
$json = Json::decode('{"name":"Ibrahim","age":30}');
echo $json->get('name'); // Ibrahim
```

Read from a file:

```php
$json = Json::fromJsonFile('/path/to/file.json');
```

### Typed Deserialization

Deserialize JSON directly into typed objects:

```php
class User {
    public function __construct(
        private string $username,
        private string $email
    ) {}
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
}

$user = Json::decodeAs('{"username":"ibrahim","email":"a@b.com"}', User::class);
echo $user->getUsername(); // ibrahim
```

Nested objects are resolved automatically via constructor type hints. Use `#[JsonType]` for arrays of objects:

```php
use WebFiori\Json\JsonType;

class Order {
    public function __construct(
        private User $customer,
        #[JsonType(LineItem::class, isArray: true)]
        private array $items
    ) {}
}

$order = Json::decodeAs($jsonString, Order::class);
$order->getCustomer();  // User instance
$order->getItems();     // LineItem[] array
```

Runtime type mapping without attributes:

```php
$json = Json::decode($jsonString);
$json->setTypeMap([
    'customer' => User::class,
    'items'    => [LineItem::class],
]);
$json->get('customer'); // User instance
$json->get('items');    // LineItem[] array
```

### Saving to File

```php
$json = new Json(['name' => 'Ibrahim', 'age' => 30]);
$json->toJsonFile('data', '/path/to/directory', true);
// Creates /path/to/directory/data.json
```

### JSONx

[JSONx](https://www.ibm.com/docs/en/datapower-gateways/10.0.1?topic=20-jsonx) is an IBM standard that represents JSON as XML:

```php
$json = new Json(['name' => 'Ibrahim', 'age' => 30, 'isEmployed' => true]);
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
</json:object>
```

## Error Handling

All errors throw `\WebFiori\Json\JsonException`:

```php
try {
    $json = Json::decode('{invalid json}');
} catch (\WebFiori\Json\JsonException $e) {
    echo $e->getMessage();
}
```

## API Reference

### Classes

| Class | Description |
|-------|-------------|
| `Json` | Main class for building, reading, and deserializing JSON data |
| `JsonI` | Interface for custom object serialization |
| `JsonConverter` | Handles serialization to JSON and JSONx strings |
| `JsonDeserializer` | Handles typed deserialization of JSON into objects |
| `Property` | Represents a single JSON property |
| `CaseConverter` | Converts property names between naming styles |
| `JsonTypes` | Constants for JSON data types |
| `JsonException` | Exception thrown on JSON errors |

### Attributes

| Attribute | Target | Description |
|-----------|--------|-------------|
| `#[JsonIgnore]` | Method, Property | Exclude from serialization |
| `#[JsonProperty(name)]` | Method, Property | Override output name |
| `#[JsonType(class, isArray)]` | Parameter, Property | Specify type for deserialization |

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
| `setTypeMap(array $map): void` | Set type map for typed deserialization via get() |
| `toJSONString(): string` | Get JSON string |
| `toJSONxString(): string` | Get JSONx string |
| `toJsonFile(string $fileName, string $path, bool $override = false): void` | Save to file |
| `Json::decode(string $jsonStr): Json` | Decode a JSON string |
| `Json::decodeAs(string $jsonStr, string $className): object` | Decode and hydrate a typed object |
| `Json::fromJsonFile(string $path): Json` | Load from a JSON file |
| `Json::setDefaults(?string $style, ?string $case, ?bool $formatted): void` | Set application-wide defaults |
| `Json::resetDefaults(): void` | Reset to library defaults |

## Testing

```bash
# Install dependencies
composer install

# Run tests
composer test
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request on [GitHub](https://github.com/WebFiori/json).

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Support

If you encounter any issues, please [open an issue](https://github.com/WebFiori/json/issues) on GitHub.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes.
