<?php

/*
 * The MIT License
 *
 * Copyright 2019 Ibrahim, JsonX library.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace webfiori\json;

/**
 * A class that contains constants which represents supported JSON datatypes. 
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
