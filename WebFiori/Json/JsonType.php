<?php
namespace WebFiori\Json;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class JsonType {
    public function __construct(
        public readonly string $className,
        public readonly bool $isArray = false
    ) {
    }
}
