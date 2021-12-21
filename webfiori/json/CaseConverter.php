<?php
namespace webfiori\json;

/**
 * A class which is used to convert string case from one to another (e.g. camle to snake).
 *
 * @author Ibrahim
 * 
 * @version 1.0
 */
class CaseConverter {
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
     * If the given value is non of the given 3, the string woun't be changed.
     * 
     * @return string The same string converted to selected style.
     * 
     * @since 1.0
     */
    public static function convert($value, $style) {
        if ($style == 'snake') {
            return self::toSnackCase($value);
        } else if ($style == 'kebab') {
            return self::toKebabCase($value);
        } else if ($style == 'camel') {
            return self::toCamelCase($value);
        } else {
            return $value;
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
    public static function toCamelCase($value) {
        $retVal = '';
        $changeNextCharCase = false;

        for ($x = 0 ; $x < strlen($value) ; $x++) {
            $char = $value[$x];

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
    public static function toKebabCase($value) {
        $attr1 = str_replace('_', '-', $value);
        $retVal = '';

        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            $char = $attr1[$x];

            if (self::_isUpper($char) && $x != 0) {
                $retVal .= '-'.strtolower($char);
            }  else if (self::_isUpper($char) && $x == 0) {
                $retVal .= strtolower($char);
            } else {
                $retVal .= $char;
            }
        }

        return $retVal;
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
    public static function toSnackCase($value) {
        $attr1 = str_replace('-', '_', $value);
        $retVal = '';

        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            $char = $attr1[$x];

            if (self::_isUpper($char) && $x != 0) {
                $retVal .= '_'.strtolower($char);
            } else if (self::_isUpper($char) && $x == 0) {
                $retVal .= strtolower($char);
            } else {
                $retVal .= $char;
            }
        }

        return $retVal;
    }
    private static function _isUpper($char) {
        return $char >= 'A' && $char <= 'Z';
    }
}
