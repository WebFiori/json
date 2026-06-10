<?php
namespace WebFiori\Json;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
class JsonProperty {
    public function __construct(public readonly string $name) {
    }
}
