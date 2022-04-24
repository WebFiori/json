<?php
namespace webfiori\json;

use InvalidArgumentException;
/**
 * An entity that represents an attribute in Json object.
 *
 * @author Ibrahim
 */
class Property {
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
     * @throws InvalidArgumentException If the name of the property is invalid
     * 
     * @since 1.0
     */
    public function __construct($name, $value, $style = null) {
        $this->setStyle('none');
        $this->setAsObject(false);

        if ($style !== null) {
            $this->setStyle($style);
        }

        if (!$this->setName($name)) {
            throw new InvalidArgumentException('Invalid property name: "'.$name.'"');
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
     * Returns the name of XML tag that will be used when representing the
     * property in JSONx.
     * 
     * @return string The returned string will have the following syntax:
     * "json:&lt;type&gt;" where "&lt;type&gt;" is the datatype of the property.
     * 
     * @since 1.0
     */
    public function getJsonXTagName() {
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
    public function getName() {
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
    public function getStyle() {
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
    public function getType() {
        return $this->datatype;
    }
    /**
     * Checks if the property will be represented as object or array.
     * 
     * This method is only used with arrays since in some cases the developer
     * would like to have associative arrays as objects.
     * 
     * @return boolean If the property will be represented as object, true is
     * returned. False otherwise.
     * 
     * @since 1.0
     */
    public function isAsObject() {
        return $this->asObject;
    }
    /**
     * Sets the value of the property which is used to tell if
     * the property will be represented as object or array.
     * 
     * This method is only used with arrays since in some cases the developer
     * would like to have associative arrays as objects.
     * 
     * @param boolean $bool True to represent the array as object. False 
     * otherwise.
     * 
     * @since 1.0
     */
    public function setAsObject($bool) {
        $this->asObject = $bool === true;
    }
    /**
     * Sets the name of the property.
     * 
     * @param string $name The name of the property.
     * 
     * @return boolean If the name is set, the method will return true. False
     * otherwise.
     * 
     * @since 1.0
     */
    public function setName($name) {
        $keyValidity = self::_isValidKey($name, $this->getStyle());

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
     * the constant 'JSONX_PROP_STYLE'.
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
    public function setStyle($style) {
        $trimmed = strtolower(trim($style));

        if (in_array($trimmed, self::PROP_NAME_STYLES)) {
            $this->probsStyle = $trimmed;
            $this->setName(CaseConverter::convert($this->getName(), $trimmed));
        }
        $val = $this->getValue();

        if ($val instanceof Json) {
            $val->setPropsStyle($trimmed);
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
        if (is_subclass_of($val, 'webfiori\\json\\JsonI')) {
            $this->value = $val->toJSON();
        } else {
            $this->value = $val;
        }
        $this->datatype = gettype($val);
    }
    /**
     * Checks if the key is a valid key string.
     * 
     * The key is invalid if its an empty string.
     * 
     * @param string $key The key that will be validated.
     * 
     * @return boolean|string If the key is valid, it will be returned 
     * after trimmed. If not valid, false is returned.
     * 
     * @since 1.0
     */
    private static function _isValidKey($key, $style = 'kebab') {
        $trimmedKey = trim($key);

        if (strlen($trimmedKey) != 0) {
            return CaseConverter::convert($trimmedKey, $style);
        }

        return false;
    }
}
