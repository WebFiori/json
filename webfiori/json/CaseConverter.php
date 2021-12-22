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
    public static function toCamelCase($value) {
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
    public static function toKebabCase($value) {
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
    public static function toSnackCase($value) {
        return self::_toSnakeOrKebab($value, '-', '_');
    }
    private static function _toSnakeOrKebab($value, $from, $to) {
        $attr1 = str_replace($from, $to, trim($value));
        $retVal = '';

        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            $char = $attr1[$x];

            if (self::_isUpper($char) && $x != 0) {
                $retVal .= $to.strtolower($char);
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
