<?php
namespace WebFiori\Json;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Handles typed deserialization of JSON data into PHP objects.
 */
class JsonDeserializer {
    /**
     * Deserializes JSON data into an instance of the given class.
     *
     * @param Json $json The JSON data to deserialize.
     * @param string $className The fully qualified class name to hydrate.
     *
     * @return object An instance of the given class populated with JSON data.
     *
     * @throws JsonException If the class does not exist or cannot be instantiated.
     */
    public static function deserialize(Json $json, string $className): object {
        if (!class_exists($className)) {
            throw new JsonException("Class '$className' does not exist.");
        }

        $refClass = new ReflectionClass($className);

        // Priority 1: static fromJSON(Json $json) factory
        if ($refClass->hasMethod('fromJSON')) {
            $method = $refClass->getMethod('fromJSON');

            if ($method->isStatic() && $method->isPublic() && $method->getNumberOfRequiredParameters() <= 1) {
                return $method->invoke(null, $json);
            }
        }

        // Priority 2: Constructor-based hydration
        $constructor = $refClass->getConstructor();

        if ($constructor === null) {
            $instance = $refClass->newInstance();
            self::populateRemaining($refClass, $instance, $json, self::getPropertyNames($json));

            return $instance;
        }

        $params = $constructor->getParameters();
        $args = [];
        $usedKeys = [];

        foreach ($params as $param) {
            $paramName = $param->getName();
            $value = self::getJsonValue($json, $paramName);

            if ($value !== null || $json->hasKey($paramName)) {
                $usedKeys[] = $paramName;
                $args[] = self::resolveValue($value, $param);
            } else if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else if ($param->allowsNull()) {
                $args[] = null;
            } else {
                throw new JsonException("Required parameter '\$$paramName' for class '$className' not found in JSON data.");
            }
        }

        $instance = $refClass->newInstanceArgs($args);

        // Priority 4: Remaining keys via setters/public properties
        $remainingKeys = array_diff(self::getPropertyNames($json), $usedKeys);
        self::populateRemaining($refClass, $instance, $json, $remainingKeys);

        return $instance;
    }

    /**
     * Gets a value from Json by key name.
     */
    private static function getJsonValue(Json $json, string $key): mixed {
        return $json->get($key);
    }

    /**
     * Gets all property names from a Json instance.
     */
    private static function getPropertyNames(Json $json): array {
        return $json->getPropsNames();
    }

    /**
     * Hydrates a value based on a #[JsonType] attribute instance.
     */
    private static function hydrateByJsonType(mixed $value, JsonType $jsonType): mixed {
        if ($jsonType->isArray) {
            if (!is_array($value)) {
                return [];
            }

            $result = [];

            foreach ($value as $item) {
                if ($item instanceof Json) {
                    $result[] = self::deserialize($item, $jsonType->className);
                } else if (is_array($item)) {
                    $result[] = self::deserialize(new Json($item), $jsonType->className);
                } else {
                    $result[] = $item;
                }
            }

            return $result;
        }

        if ($value instanceof Json) {
            return self::deserialize($value, $jsonType->className);
        }

        if (is_array($value)) {
            return self::deserialize(new Json($value), $jsonType->className);
        }

        return $value;
    }

    /**
     * Populates remaining JSON keys via setter methods or public properties.
     */
    private static function populateRemaining(ReflectionClass $refClass, object $instance, Json $json, array $keys): void {
        foreach ($keys as $key) {
            $value = self::getJsonValue($json, $key);
            $setterName = 'set'.ucfirst($key);

            if ($refClass->hasMethod($setterName)) {
                $setter = $refClass->getMethod($setterName);

                if ($setter->isPublic() && $setter->getNumberOfRequiredParameters() <= 1) {
                    $params = $setter->getParameters();

                    if (!empty($params)) {
                        $value = self::resolveSetterValue($value, $params[0]);
                    }
                    $setter->invoke($instance, $value);

                    continue;
                }
            }

            if ($refClass->hasProperty($key)) {
                $prop = $refClass->getProperty($key);

                if ($prop->isPublic()) {
                    $resolvedValue = self::resolvePropertyValue($value, $prop);
                    $prop->setValue($instance, $resolvedValue);
                }
            }
        }
    }

    /**
     * Resolves value for a public property.
     */
    private static function resolvePropertyValue(mixed $value, \ReflectionProperty $prop): mixed {
        $attrs = $prop->getAttributes(JsonType::class);

        if (!empty($attrs)) {
            return self::hydrateByJsonType($value, $attrs[0]->newInstance());
        }

        $type = $prop->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            if ($value instanceof Json) {
                return self::deserialize($value, $type->getName());
            }
        }

        return $value;
    }

    /**
     * Resolves value for a setter parameter.
     */
    private static function resolveSetterValue(mixed $value, ReflectionParameter $param): mixed {
        $attrs = $param->getAttributes(JsonType::class);

        if (!empty($attrs)) {
            return self::hydrateByJsonType($value, $attrs[0]->newInstance());
        }

        $type = $param->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            if ($value instanceof Json) {
                return self::deserialize($value, $type->getName());
            }
        }

        return $value;
    }

    /**
     * Resolves a JSON value to its proper PHP type based on parameter reflection.
     */
    private static function resolveValue(mixed $value, ReflectionParameter $param): mixed {
        // Check #[JsonType] attribute on parameter
        $attrs = $param->getAttributes(JsonType::class);

        if (!empty($attrs)) {
            $jsonType = $attrs[0]->newInstance();

            return self::hydrateByJsonType($value, $jsonType);
        }

        // Check constructor parameter type hint
        $type = $param->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            $typeName = $type->getName();

            if ($value instanceof Json) {
                return self::deserialize($value, $typeName);
            }

            if (is_array($value)) {
                return self::deserialize(new Json($value), $typeName);
            }
        }

        // Also check #[JsonType] on the corresponding class property if exists
        $declaringClass = $param->getDeclaringFunction()->getDeclaringClass();

        if ($declaringClass !== null && $declaringClass->hasProperty($param->getName())) {
            $prop = $declaringClass->getProperty($param->getName());
            $propAttrs = $prop->getAttributes(JsonType::class);

            if (!empty($propAttrs)) {
                $jsonType = $propAttrs[0]->newInstance();

                return self::hydrateByJsonType($value, $jsonType);
            }
        }

        // Scalar or untyped — return as-is
        if ($value instanceof Json) {
            return $value;
        }

        return $value;
    }
}
