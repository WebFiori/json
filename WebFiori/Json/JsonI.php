<?php

/**
 * This file is licensed under MIT License.
 *
 * Copyright (c) 2019 Ibrahim BinAlshikh
 *
 * For more information on the license, please visit:
 * https://github.com/WebFiori/.github/blob/main/LICENSE
 *
 */
namespace WebFiori\Json;

/**
 * Interface for objects that provide a custom JSON representation.
 *
 * Implementing this interface allows a class to control exactly how it is
 * serialized when passed to {@see Json::addObject()} or
 * {@see JsonConverter::objectToJson()}. When either of those methods encounters
 * an object that implements this interface, they call {@see JsonI::toJSON()}
 * instead of falling back to getter-method or reflection-based mapping.
 *
 * @author Ibrahim
 * @see Json
 * @see JsonConverter
 */
interface JsonI {
    /**
     * Returns a {@see Json} instance that represents the object.
     *
     * Implement this method to define which properties are included in the
     * JSON output and how they are named. The returned instance will be used
     * directly by {@see Json::addObject()} and {@see JsonConverter::objectToJson()},
     * bypassing getter-method scanning and reflection-based property extraction.
     *
     * @return Json A Json instance representing this object.
     */
    public function toJSON() : Json;
}
