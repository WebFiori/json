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
     * @since 1.0
     */
    const PROP_NAME_STYLES = [
        'camel',
        'kebab',
        'snake',
        'none'
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
     * @return string The same string converted to selected style.
     * 
     * @since 1.0
     */
    public static function convert(string $value, string $style) : string {
        if ($style == 'snake') {
            return self::toSnackCase($value);
        } else if ($style == 'kebab') {
                return self::toKebabCase($value);
        } else if ($style == 'camel') {
            return self::toCamelCase($value);
        } else {
            return trim($value);
        }
    }
    /**
     * Converts a string to camel case.
     * 
     * @param string $value A string such as 'my-val'.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'my-val', the method will return the string 'myVal'.
     * 
     * @since 1.0
     */
    public static function toCamelCase(string $value) : string {
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

        return $retVal;
    }
    /**
     * Converts a string to kebab case.
     * 
     * @param string $value A string such as 'myVal'.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'myVal', the method will return the string 'my-val'.
     * 
     * @since 1.0
     */
    public static function toKebabCase(string $value) : string {
        return self::_toSnakeOrKebab($value, '_', '-');
    }
    /**
     * Converts a string to snake case.
     * 
     * @param string $value A string such as 'my-val'.
     * 
     * @return string The method will return the string after conversion. For
     * example, if the string is 'my-val', the method will return the string 'my_val'.
     * 
     * @since 1.0
     */
    public static function toSnackCase(string $value) : string {
        return self::_toSnakeOrKebab($value, '-', '_');
    }
    private static function _isUpper(string $char) : bool {
        return $char >= 'A' && $char <= 'Z';
    }
    private static function _toSnakeOrKebab(string $value, string $from, string $to) : string {
        $attr1 = str_replace($from, $to, trim($value));
        $retVal = '';
        $isNumFound = false;
        $snakeOrKebabFound = false;

        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            $char = $attr1[$x];

            if ($char == $to) {
                $snakeOrKebabFound = true;
                $retVal .= $char;
            } else if ($char >= '0' && $char <= '9') {
                $retVal .= self::addNumber($x, $isNumFound, $to, $char, $snakeOrKebabFound);
            } else {
                $retVal .= self::addChar($x, $isNumFound, $to, $char, $snakeOrKebabFound);
            }
        }

        return $retVal;
    }
    private static function addChar($x, &$isNumFound, $to, $char, &$snakeOrKebabFound) : string {
        $isUpper = self::_isUpper($char);
        $retVal = '';

        if (($isUpper || $isNumFound) && $x != 0 && !$snakeOrKebabFound) {
            $retVal .= $to.strtolower($char);
        } else if ($isUpper && $x == 0) {
            $retVal .= strtolower($char);
        } else if ($isUpper  && $x != 0 && $snakeOrKebabFound) {
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
