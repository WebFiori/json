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

use InvalidArgumentException;
/**
 * An entity that represents an attribute in Json object.
 *
 * @author Ibrahim
 */
class Property {
    /**
     * 
     * @var boolean
     * 
     * @since 1.0
     */
    private $asObject;
    /**
     * 
     * @var string
     * 
     * @since 1.0
     */
    private $datatype;
    private $lettersCase;
    /**
     * 
     * @var string
     * 
     * @since 1.0
     */
    private $name;
    /**
     * 
     * @var string
     * 
     * @since 1.0
     */
    private $probsStyle;
    /**
     * 
     * @var mixed
     * 
     * @since 1.0
     */
    private $value;
    /**
     * Creates new instance of the class.
     * 
     * @param string $name The name of the property.
     * 
     * @param mixed $value The value of the property.
     * 
     * @param string $style The style at which the name of the property will
     * use to represent its name. Can be one of the following values:
     * <ul>
     * <li>snake</li>
     * <li>kebab</li>
     * <li>camel</li>
     * <li>none</li>
     * </ul>
     * The default value is 'none'.
     * 
     * @param string $case The case at which the name of the property will use. 
     * It can be one of the following values:
     * <ul>
     * <li>same</li>
     * <li>upper</li>
     * <li>lower</li>
     * </ul>
     * The default value is 'same'.
     * 
     * @throws InvalidArgumentException If the name of the property is invalid
     * 
     * @since 1.0
     */
    public function __construct(string $name, $value, ?string $style = 'none', string $case = 'same') {
        $this->name = '';
        $this->setStyle('none');

        if (!$this->setName($name)) {
            throw new InvalidArgumentException('Invalid property name: "'.$name.'"');
        }

        $this->setAsObject(false);

        if (in_array($style, CaseConverter::PROP_NAME_STYLES)) {
            $this->setStyle($style, $case);
        }


        $this->setValue($value);
    }
    /**
     * Returns the value of the property.
     * 
     * @return mixed|Json The value of the property.
     * 
     * @since 1.0
     */
    public function &getValue() {
        return $this->value;
    }
    /**
     * Returns the case at which the name of the property will be set to.
     * 
     * @return string The method will return one of the following values:
     * <ul>
     * <li>same</li>
     * <li>upper</li>
     * <li>lower</li>
     * </ul>
     * The default value is 'same'.
     */
    public function getCase() : string {
        return $this->lettersCase;
    }
    /**
     * Returns the name of XML tag that will be used when representing the
     * property in JSONx.
     * 
     * @return string The returned string will have the following syntax:
     * "json:&lt;type&gt;" where "&lt;type&gt;" is the datatype of the property.
     * 
     * @since 1.0
     */
    public function getJsonXTagName() : string {
        $type = $this->getType();
        $retVal = '';

        if ($type == JsonTypes::ARR) {
            if ($this->isAsObject()) {
                $retVal = 'json:object';
            } else {
                $retVal = 'json:array';
            }
        } else if ($type == JsonTypes::OBJ) {
            $retVal = 'json:object';
        } else if ($type == JsonTypes::BOOL) {
            $retVal = 'json:boolean';
        } else if ($type == JsonTypes::DOUBLE || $type == JsonTypes::INT) {
            if (is_nan($this->getValue()) || $this->getValue() == INF) {
                $retVal = 'json:string';
            } else {
                $retVal = 'json:number';
            }
        } else if ($type == JsonTypes::STRING) {
            $retVal = 'json:string';
        } else {
            $retVal = 'json:null';
        } 

        return $retVal;
    }
    /**
     * Returns the name of the property.
     * 
     * @return string The name of the property. Note that the returned value
     * will depend on the style at which the property name is set to use.
     * 
     * @since 1.0
     */
    public function getName() : string {
        return $this->name;
    }
    /**
     * Returns the style at which the names of the properties will use.
     * 
     * @return string The method will return one of the following values:
     * <ul>
     * <li>snake</li>
     * <li>kebab</li>
     * <li>camel</li>
     * <li>none</li>
     * </ul>
     * The default value is 'none'.
     * 
     * @since 1.0
     */
    public function getStyle() : string {
        return $this->probsStyle;
    }
    /**
     * Returns the datatype of property value.
     * 
     * @return string The method will return one of the following values:
     * <ul>
     * <li>integer</li>
     * <li>double</li>
     * <li>string</li>
     * <li>boolean</li>
     * <li>object</li>
     * <li>NULL</li>
     * </ul>
     * 
     * @since 1.0
     */
    public function getType() : string {
        return $this->datatype;
    }
    /**
     * Checks if the property will be represented as object or array.
     * 
     * This method is only used with arrays since in some cases the developer
     * would like to have associative arrays as objects.
     * 
     * @return bool If the property will be represented as object, true is
     * returned. False otherwise.
     * 
     * @since 1.0
     */
    public function isAsObject() : bool {
        return $this->asObject;
    }
    /**
     * Sets the value of the property which is used to tell if
     * the property will be represented as object or array.
     * 
     * This method is only used with arrays since in some cases the developer
     * would like to have associative arrays as objects.
     * 
     * @param bool $bool True to represent the array as object. False 
     * otherwise.
     * 
     * @since 1.0
     */
    public function setAsObject(bool $bool) {
        $this->asObject = $bool;
    }
    /**
     * Sets the name of the property.
     * 
     * @param string $name The name of the property.
     * 
     * @return bool If the name is set, the method will return true. False
     * otherwise.
     * 
     * @since 1.0
     */
    public function setName(string $name) : bool {
        $keyValidity = self::isValidKey($name, $this->getStyle(), $this->getCase());

        if ($keyValidity === false) {
            return false;
        }
        $this->name = $keyValidity;

        return true;
    }
    /**
     * Sets the style at which the names of the properties will use.
     * 
     * Another way to set the style that will be used by the instance is to 
     * define the global constant 'JSONX_PROP_STYLE' and set its value to 
     * the desired style. Note that the method will change already added properties 
     * to the new style. Also, it will override the style which is set using 
     * the constant 'JSON_PROP_STYLE'.
     * 
     * @param string $style The style that will be used. It can be one of the 
     * following values:
     * <ul>
     * <li>camel</li>
     * <li>kebab</li>
     * <li>snake</li>
     * <li>none</li>
     * </ul>
     * 
     * @since 1.0
     */
    public function setStyle(string $style, string $lettersCase = 'same') {
        $trimmed = strtolower(trim($style));
        $trimmedLetterCase = strtolower(trim($lettersCase));

        if (in_array($trimmed, CaseConverter::PROP_NAME_STYLES) && in_array($trimmedLetterCase, CaseConverter::LETTER_CASE)) {
            $this->probsStyle = $trimmed;
            $this->lettersCase = $trimmedLetterCase;
            $this->setName(CaseConverter::convert($this->getName(), $trimmed, $trimmedLetterCase));
        }
        $val = $this->getValue();

        if ($val instanceof Json) {
            $val->setPropsStyle($trimmed, $trimmedLetterCase);
        }
    }
    /**
     * Sets the value of the property.
     * 
     * @param mixed $val The value of the property. This can be a string or
     * array or number or an object or null.
     * 
     * @since 1.0
     */
    public function setValue($val) {
        $this->datatype = gettype($val);

        if ($this->getType() == 'object' && is_subclass_of($val, 'webfiori\\json\\JsonI')) {
            $this->value = $val->toJSON();
        } else {
            $this->value = $val;
        }
    }
    /**
     * Checks if the key is a valid key string.
     * 
     * The key is invalid if it's an empty string.
     * 
     * @param string $key The key that will be validated.
     * 
     * @return bool|string If the key is valid, it will be returned 
     * after trimmed. If not valid, false is returned.
     * 
     * @since 1.0
     */
    private static function isValidKey($key, $style = 'kebab', $case = 'same') {
        $trimmedKey = trim($key);

        if (strlen($trimmedKey) != 0) {
            return CaseConverter::convert($trimmedKey, $style, $case);
        }

        return false;
    }
}
