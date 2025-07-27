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
 * A class that contains constants which represents supported JSON data types.
 *
 * @author Ibrahim
 */
abstract class JsonTypes {
    /**
     * A constant that indicates that given datatype is of type array.
     */
    const ARR = 'array';
    /**
     * A constant that indicates that given datatype is of type boolean.
     */
    const BOOL = 'boolean';
    /**
     * A constant that indicates that given datatype is of type double (or float).
     */
    const DOUBLE = 'double';
    /**
     * A constant that indicates that given datatype is of type integer.
     */
    const INT = 'integer';
    /**
     * A constant that indicates that given datatype is null.
     */
    const NUL = 'NULL';
    /**
     * A constant that indicates that given datatype is an object.
     */
    const OBJ = 'object';
    /**
     * A constant that indicates that given datatype is of type string.
     */
    const STRING = 'string';
}
