# Examples

| File | What it shows |
|------|---------------|
| [01-basic-usage.php](01-basic-usage.php) | Constructor init, scalar types, `echo` output |
| [02-arrays.php](02-arrays.php) | Indexed arrays, auto-detection of associative arrays as objects |
| [03-object-with-jsoni.php](03-object-with-jsoni.php) | Custom serialization via the `JsonI` interface |
| [04-object-auto-mapping.php](04-object-auto-mapping.php) | Auto-mapping via getters/properties, `#[JsonIgnore]`, `#[JsonProperty]` |
| [05-naming-styles.php](05-naming-styles.php) | `camel` / `snake` / `kebab` / `none` styles + letter case |
| [06-decode-and-read.php](06-decode-and-read.php) | Decoding JSON strings and reading from files |
| [07-jsonx.php](07-jsonx.php) | Converting JSON to JSONx (XML) format |
| [08-json-property-attribute.php](08-json-property-attribute.php) | `#[JsonProperty]` for explicit name override immune to style conversion |
| [09-typed-deserialization.php](09-typed-deserialization.php) | `Json::decodeAs()`, nested hydration, `#[JsonType]`, `setTypeMap()` |
| [10-set-defaults.php](10-set-defaults.php) | `Json::setDefaults()` and `Json::resetDefaults()` |

Run any example from the project root:

```bash
php examples/01-basic-usage.php
```
