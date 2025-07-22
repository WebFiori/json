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
 * An interface for the objects that can be added to an instance of JsonX.
 * @author Ibrahim 
 * @version 1.0
 * @see JsonX
 */
interface JsonI {
    /**
     * Returns an object of type JsonX.
     * This method can be implemented by any class that will be added  
     * to any JsonX instance. It is used to customize the generated 
     * JSON string.
     * 
     * @return Json An instance of JsonX.
     * 
     * @since 1.0
     */
    public function toJSON() : Json;
}
