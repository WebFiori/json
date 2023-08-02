<?php
/**
 * This file is licensed under MIT License.
 *
 * Copyright (c) 2022 Ibrahim BinAlshikh
 *
 * For more information on the license, please visit:
 * https://github.com/WebFiori/.github/blob/main/LICENSE
 *
 */
namespace webfiori\json;

/**
 * A class which is used to convert string case from one to another (e.g. camel to snake).
 *
 * @author Ibrahim
 * 
 * @version 1.0
 */
class CaseConverter {
    /**
     * An array of supported property styles.
     * 
     * This array holds the following values:
     * <ul>
     * <li>camel</li>
     * <li>kebab</li>
     * <li>snake</li>
     * <li>none</li>
     * </ul>
     * 
     */
    const PROP_NAME_STYLES = [
        'camel',
        'kebab',
        'snake',
        'none'
    ];
        /**
     * An array of supported property styles.
     * 
     * This array holds the following values:
     * <ul>
     * <li>camel</li>
     * <li>kebab</li>
     * <li>snake</li>
     * <li>none</li>
     * </ul>
     * 
     */
    const LETTER_CASE = [
        'same',
        'upper',
        'lower',
    ];
    /**
     * Converts a string to specific case.
     * 
     * @param string $value The string that will be converted.
     * 
     * @param string $style The name of the style that the given string will be 
     * converted to. It can be one of 3 values:
     * <ul>
     * <li>snake</li>
     * <li>kebab</li>
     * <li>camel</li>
     * </ul>
     * If the given value is none of the given 3, the string wouldn't be changed.
     * 
     * @param string $letterCase This is used to set the case of property name.
     * it can have one of following values:
     * <ul>
     * <li>same: Leave letter case as provided.</li>
     * <li>lower: Convert all letters to lower case.</li>
     * <li>upper: Convert all letter to upper case.</li>
     * </ul>
     * If the given value is none of the given 3, the string wouldn't be changed.
     * 
     * 
     * @return string The same string converted to selected style.
     * 
     */
    public static function convert(string $value, string $style, string $letterCase = 'same') : string {
        if ($style == 'snake') {
            return self::toSnackCase($value, $letterCase);
        } else if ($style == 'kebab') {
            return self::toKebabCase($value, $letterCase);
        } else if ($style == 'camel') {
            return self::toCamelCase($value, $letterCase);
        } else {
            return self::convertCase(trim($value), $letterCase);
        }
    }
    /**
     * Converts a string to camel case.
     * 
     * @param string $value A string such as 'my-val'.
     * 
     * @param string $letterCase This is used to set the case of property name.
     * it can have one of following values:
     * <ul>
     * <li>same: Leave letter case as provided.</li>
     * <li>lower: Convert all letters to lower case.</li>
     * <li>upper: Convert all letter to upper case.</li>
     * </ul>
     * If the given value is none of the given 3, the string wouldn't be changed.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'my-val', the method will return the string 'myVal'.
     * 
     */
    public static function toCamelCase(string $value, string $letterCase = 'same') : string {
        $retVal = '';
        $changeNextCharCase = false;
        $valueTrim = trim($value);

        for ($x = 0 ; $x < strlen($valueTrim) ; $x++) {
            $char = $valueTrim[$x];

            if (($char == '-' || $char == '_') && $x != 0) {
                $changeNextCharCase = true;
                continue;
            }

            if ($changeNextCharCase) {
                $retVal .= strtoupper($char);
                $changeNextCharCase = false;
            } else {
                $retVal .= $value[$x];
            }
        }

        return self::convertCase($retVal, $letterCase);
    }
    /**
     * Converts a string to kebab case.
     * 
     * @param string $value A string such as 'myVal'.
     * 
     * @param string $letterCase This is used to set the case of property name.
     * it can have one of following values:
     * <ul>
     * <li>same: Leave letter case as provided.</li>
     * <li>lower: Convert all letters to lower case.</li>
     * <li>upper: Convert all letter to upper case.</li>
     * </ul>
     * If the given value is none of the given 3, the string wouldn't be changed.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'myVal', the method will return the string 'my-val'.
     * 
     */
    public static function toKebabCase(string $value, string $letterCase = 'same') : string {
        return self::_toSnakeOrKebab($value, $letterCase, '_', '-');
    }
    /**
     * Converts a string to snake case.
     * 
     * @param string $value A string such as 'my-val'.
     * 
     * @param string $letterCase This is used to set the case of property name.
     * it can have one of following values:
     * <ul>
     * <li>same: Leave letter case as provided.</li>
     * <li>lower: Convert all letters to lower case.</li>
     * <li>upper: Convert all letter to upper case.</li>
     * </ul>
     * If the given value is none of the given 3, the string wouldn't be changed.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'my-val', the method will return the string 'my_val'.
     * 
     */
    public static function toSnackCase(string $value, string $letterCase = 'same') : string {
        return self::_toSnakeOrKebab($value, $letterCase, '-', '_');
    }
    /**
     * Checks if an english letter is in upper case or not.
     * 
     * @param string $char The character that will be checked.
     * 
     * @return bool
     */
    public static function isUpper(string $char) : bool {
        return $char >= 'A' && $char <= 'Z';
    }
    private static function _toSnakeOrKebab(string $value, string $letterCase, string $from, string $to) : string {
        $attr1 = str_replace($from, $to, trim($value));
        $retVal = '';
        $isNumFound = false;
        $snakeOrKebabFound = false;
        $len = strlen($attr1);
        
        for ($x = 0 ; $x < $len ; $x++) {
            $char = $attr1[$x];
            $nextChar = $x < $len - 1 ? $attr1[$x + 1] : $attr1[$x];
            
            if ($char == $to) {
                $snakeOrKebabFound = true;
                $retVal .= $char;
            } else if ($char >= '0' && $char <= '9') {
                $retVal .= self::addNumber($x, $isNumFound, $to, $char, $snakeOrKebabFound);
            } else {
                $retVal .= self::addChar($x, $isNumFound, $to, $char, $snakeOrKebabFound, $nextChar);
            }
        }
        return self::convertCase($retVal, $letterCase);
    }
    private static function convertCase($retVal, $letterCase) {
        if ($letterCase == 'upper') {
            return strtoupper($retVal);
        } else if ($letterCase == 'lower') {
            return strtolower($retVal);
        }
        return $retVal;
    }
    private static function addChar($x, &$isNumFound, $to, $char, &$snakeOrKebabFound, $nextChar) : string {
        $isUpper = self::isUpper($char);
        if ($nextChar !== null) {
            $isNextUpper = self::isUpper($nextChar);
        } else {
            $isNextUpper = false;
        }
        $retVal = '';

        if (($isUpper || $isNumFound) && $x != 0 && !$snakeOrKebabFound && !$isNextUpper) {
            $retVal .= $to.strtolower($char);
        } else if ($isUpper && $x == 0 && !$isNextUpper) {
            $retVal .= strtolower($char);
        } else if ($isUpper && $x != 0 && !$isNextUpper && $snakeOrKebabFound) {
            $retVal .= strtolower($char);
        } else {
            $retVal .= $char;
        }
        $snakeOrKebabFound = false;
        $isNumFound = false;

        return $retVal;
    }

    private static function addNumber($x, &$isNumFound, $to, $char, &$snakeOrKebabFound) : string {
        $retVal = '';

        if ($x == 0) {
            $isNumFound = true;
            $retVal .= $char;
        } else if ($isNumFound || $snakeOrKebabFound) {
            $retVal .= $char;
            $snakeOrKebabFound = false;
        } else {
            $retVal .= $to.$char;
        }
        $isNumFound = true;

        return $retVal;
    }
}
