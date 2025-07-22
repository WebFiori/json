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
namespace webfiori\json;

/**
 * A class that contains constants which represents supported JSON data types.
 *
 * @author Ibrahim
 * 
 * @version 1.0
 */
abstract class JsonTypes {
    /**
     * A constant that indicates that given datatype is of type array.
     * 
     * @since 1.0
     */
    const ARR = 'array';
    /**
     * A constant that indicates that given datatype is of type boolean.
     * 
     * @since 1.0
     */
    const BOOL = 'boolean';
    /**
     * A constant that indicates that given datatype is of type double (or float).
     * 
     * @since 1.0
     */
    const DOUBLE = 'double';
    /**
     * A constant that indicates that given datatype is of type integer.
     * 
     * @since 1.0
     */
    const INT = 'integer';
    /**
     * A constant that indicates that given datatype is null.
     * 
     * @since 1.0
     */
    const NUL = 'NULL';
    /**
     * A constant that indicates that given datatype is an object.
     * 
     * @since 1.0
     */
    const OBJ = 'object';
    /**
     * A constant that indicates that given datatype is of type string.
     * 
     * @since 1.0
     */
    const STRING = 'string';
}
