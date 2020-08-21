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
namespace jsonx;

/**
 * A class that can be used to create well formatted JSON strings. 
 * 
 * The class follows the specifications found at https://www.json.org/index.html.
 * This class is useful in collecting server variables and sends them back as 
 * JSON object to the clients that supports JSON.
 * The process of creating JSON strings using this class is as follows:
 * <ul>
 * <li>Create new instance of the class.</li>
 * <li>Add the data that will be decoded to JSON format using 
 * the proper method.</li>
 * <li>To see the generated JSON, simply use the command 'echo' 
 * against the created instance.</li>
 * </ul>
 * 
 * @author Ibrahim
 * 
 * @since 1.2.4
 */
class JsonX {
    /**
     * An array of supported property styles.
     * 
     * This array holds the following values:
     * <ul>
     * <li>camel</li>
     * <li>kebab</li>
     * <li>snake</li>
     * </ul>
     * 
     * @since 1.2.4
     */
    const PROP_NAME_STYLES = [
        'camel',
        'kebab',
        'snake',
        'none'
    ];
    /**
     * An array that contains JSON special characters.
     * 
     * The array contains the following characters:
     * <ul>
     * <li>\</li>
     * <li>/</li>
     * <li>"</li>
     * <li>\t</li>
     * <li>\r</li>
     * <li>\n</li>
     * <li>\f</li>
     * </ul>
     * 
     * @var array JSON special characters.
     * 
     * @since 1.0
     */
    const SPECIAL_CHARS = [
        //order of characters maters
        //first we must escape / and \
        '\\',"/",'"',"\t","\r","\n","\f"
    ];
    /**
     * An array that contains escaped JSON special characters.
     * 
     * @var array escaped JSON special characters.
     * 
     * @since 1.0
     */
    const SPECIAL_CHARS_ESC = [
        "\\\\","\/",'\"',"\\t","\\r","\\n","\\f"
    ];
    /**
     * An array of supported JOSN data types. 
     * 
     * The array has the following strings:
     * <ul>
     * <li>integer</li>
     * <li>string</li>
     * <li>double</li>
     * <li>boolean</li>
     * <li>array</li>
     * <li>NULL</li>
     * <li>object</li>
     * </ul>
     * 
     * @var array An array of supported JOSN data types.
     * 
     * @since 1.0
     */
    const TYPES = [
        'integer',
        'string',
        'double',
        'boolean',
        'array',
        'NULL',
        'object'
    ];
    /**
     * An array that contains JSON data.
     * 
     * This array will store the keys as indices and every value will be at 
     * each index.
     * 
     * @var array 
     * 
     * @since 1.0
     */
    private $attributes = [];
    /**
     * @var array array of boolean types.
     */
    private static $BoolTypes = [
        'true',
        'false'
    ];
    /**
     * The number of tabs that have been pressed.
     * 
     * @var int
     * 
     * @since 1.2.2 
     */
    private $currentTab;
    /**
     * New line character.
     */
    private $NL = "\n";
    /**
     * A number that represents the number of spaces in a tab.
     * 
     * @var int
     * 
     * @since 1.2.2 
     */
    private $tabSize;
    /**
     *
     * @var string 
     * @since 1.2.2
     */
    private $tabStr;
    /**
     * The style of attribute name.
     * 
     * @var string 
     * 
     * @since 1.2.4
     */
    private $attrNameStyle;
    /**
     * Creates new instance of the class.
     * 
     * @param array|string $initialData Initial data which is used to initialize 
     * the object. It can be a string which looks like JSON or it can be an 
     * associative array. If it is an associative array, then the keys will be 
     * acting as properties and the value of each key will be the value of 
     * the property.
     * 
     * @param boolean $isFormatted If this attribute is set to true, the generated 
     * JSON will be indented and have new lines (readable). Note that the parameter 
     * will be ignored if the constant 'VERBOSE' is defined and is set to true.
     * 
     * @since 1.2.2
     */
    public function __construct($initialData = [],$isFormatted = false) {
        $this->currentTab = 0;

        if ($isFormatted === true || (defined('VERBOSE') && VERBOSE === true)) {
            $this->tabSize = 4;
            $this->NL = "\n";
        } else {
            $this->tabSize = 0;
            $this->NL = '';
        }
        $this->setPropsStyle('kebab');
        
        if (defined('JSONX_PROP_STYLE')) {
            $this->setPropsStyle(JSONX_PROP_STYLE);
        }
        
        $this->_initData($initialData);
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
     * </ul>
     * 
     * @since 1.2.4
     */
    public function setPropsStyle($style) {
        $trimmed = strtolower(trim($style));
        
        if (in_array($trimmed, self::PROP_NAME_STYLES)) {
            $this->attrNameStyle = $trimmed;
            foreach ($this->attributes as $key => $value) {
                unset($this->attributes[$key]);
                $this->attributes[self::_getAttrName($key, $trimmed)] = $value;
            }
        }
    }
    /**
     * 
     * @param type $jsonStr
     * @return boolean|JsonX
     */
    public static function decode($jsonStr) {
        $decoded = json_decode($jsonStr, true);
        if (gettype($decoded) == 'array') {
            $jsonXObj = new JsonX($decoded);
            return $jsonXObj;
        }
        return false;
    }
    /**
     * Returns the data on the object as a JSON string.
     * 
     * @return string
     */
    public function __toString() {
        $retVal = $this->_getTab().'{'.$this->NL;
        $this->_addTab();
        $count = count($this->attributes);
        $index = 0;

        foreach ($this->attributes as $key => $val) {
            if ($index + 1 == $count) {
                $retVal .= $this->_getTab().'"'.$key.'":'.trim($val).$this->NL;
            } else {
                $retVal .= $this->_getTab().'"'.$key.'":'.trim($val).', '.$this->NL;
            }
            $index++;
        }
        $this->_reduceTab();

        return $retVal.$this->_getTab().'}';
    }
    /**
     * Adds a new value to the JSON string.
     * 
     * This method can be used to add an integer, a double, 
     * a string, an array or an object. If null is given, the method will 
     * set the value at the given key to null. If the given value or key is 
     * invalid, the method will not add the value and will return false.
     * 
     * @param string $key The value of the key.
     * 
     * @param mixed $value The value of the key.
     * 
     * @param array $options An associative array of options. Currently, the 
     * array has the following options: 
     * <ul>
     * <li><b>string-as-boolean</b>: A boolean value. If set to true and 
     * the given string is one of the following values, it will be added as 
     * a boolean:
     * <ul>
     * <li>true</li>
     * <li>false</li>
     * <li>t</li>
     * <li>f</li>
     * <li>Yes</li>
     * <li>No</li>
     * <li>On</li>
     * <li>Off</li>
     * <li>Y</li>
     * <li>N</li>
     * <li>Ok</li>
     * </ul> Default is false.</li>
     * <li><b>array-as-object</b>: A boolean value. If set to true, 
     * the array will be added as an object. Default is false.</li>
     * </ul>
     * 
     * @return boolean The method will return true if the value is set. 
     * If the given value or key is invalid, the method will return false.
     * 
     * @since 1.1
     */
    public function add($key, $value, $options = [
        'string-as-boolean' => false,
        'array-as-object' => false
    ]) {
        if ($value !== null) {
            if (isset($options['string-as-boolean'])) {
                $strAsbool = $options['string-as-boolean'] === true ? true : false;
            } else {
                $strAsbool = false;
            }

            if (isset($options['array-as-object'])) {
                $arrAsObj = $options['array-as-object'] === true ? true : false;
            } else {
                $arrAsObj = false;
            }

            return $this->addString($key, $value,$strAsbool) ||
                    $this->addArray($key, $value, $arrAsObj) ||
            $this->addBoolean($key, $value) ||
            $this->addNumber($key, $value) || 
            $this->addObject($key, $value);
        } else {
            $keyValidated = JsonX::_isValidKey($key, $this->getPropStyle());

            if ($keyValidated !== false) {
                $this->attributes[$keyValidated] = 'null';

                return true;
            }
        }

        return false;
    }
    /**
     * Adds an array to the JSON.
     * 
     * @param string $key The name of the key.
     * 
     * @param array $value The array that will be added.
     * 
     * @param boolean $asObject If this parameter is set to true, 
     * the array will be added as an object in JSON string. Note that if the 
     * array is associative, each index will be added as an object. Default is false.
     * 
     * @return boolean The method will return false if the given key is invalid 
     * or the given value is not an array.
     */
    public function addArray($key, $value,$asObject = false) {
        $keyValidated = JsonX::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($value) == self::TYPES[4]) {
            $this->attributes[$keyValidated] = $this->_arrayToJSONString($value,$asObject);

            return true;
        }

        return false;
    }
    /**
     * Adds a boolean value (true or false) to the JSON data.
     * 
     * @param string $key The name of the key.
     * 
     * @param boolean $val true or false. If not specified, 
     * The default will be true.
     * 
     * @return boolean The method will return true in case the value is set. 
     * If the given value is not a boolean or the key value is invalid string, 
     * the method will return false.
     * 
     * @since 1.0
     */
    public function addBoolean($key,$val = true) {
        $keyValidated = JsonX::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == self::TYPES[3]) {
            if ($val) {
                $this->attributes[$keyValidated] = self::$BoolTypes[0];
            } else {
                $this->attributes[$keyValidated] = self::$BoolTypes[1];
            }

            return true;
        }

        return false;
    }
    /**
     * Adds multiple values to the object.
     * 
     * @param array $arr An associative array. The keys will act as object keys 
     * in JSON and the values of the keys will be the values in JSON.
     * 
     * @since 1.2.3
     */
    public function addMultiple($arr) {
        if (gettype($arr) == 'array') {
            foreach ($arr as $key => $value) {
                $this->add($key, $value);
            }
        }
    }
    /**
     * Adds a number to the JSON data.
     * 
     * Note that if the given number is the constant <b>INF</b> or the constant 
     * <b>NAN</b>, The method will add them as a string.
     * 
     * @param string $key The name of the key.
     * 
     * @param int|double $value The value of the key.
     * 
     * @return boolean The method will return true in case the number is 
     * added. If the given value is not a number or the key value is invalid 
     * string, the method 
     * will return false. 
     * 
     * @since 1.0
     */
    public function addNumber($key,$value) {
        $val_type = gettype($value);
        $keyValidated = self::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && ($val_type == self::TYPES[0] || $val_type == self::TYPES[2])) {
            if (is_nan($value)) {
                return $this->addString($keyValidated, 'NAN');
            } else if ($value == INF) {
                return $this->addString($keyValidated, 'INF');
            }
            $this->attributes[$keyValidated] = $value;

            return true;
        }

        return false;
    }
    /**
     * Adds an object to the JSON string.
     * 
     * The object that will be added can implement the interface JsonI to make 
     * the generated JSON string customizable. Also, the object can be of 
     * type JsonX. If the given value is an object that does not implement the 
     * interface JsonI or it is not of type JsonX, 
     * The method will try to extract object information based on its "getXxxxx()" public 
     * methods. In that case, the generated JSON will be on the formate 
     * <b>{"prop-0":"prop-1","prop-n":""}</b>.
     * 
     * @param string $key The key value.
     * 
     * @param JsonI|JsonX|object $val The object that will be added.
     * 
     * @return boolean The method will return true if the object is added. 
     * If the key value is invalid string, the method will return false.
     * 
     * @since 1.0
     */
    public function addObject($key, $val) {
        $keyValidated = self::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == self::TYPES[6]) {
            if (is_subclass_of($val, 'jsonx\JsonI')) {
                $jsonXObj = $val->toJSON();
                $jsonXObj->currentTab = $this->currentTab + 1;
                $jsonXObj->tabSize = $this->tabSize;
                $jsonXObj->NL = $this->NL;
                $this->attributes[$keyValidated] = ''.$jsonXObj;

                return true;
            } else if ($val instanceof JsonX) {
                    $val->setPropsStyle($this->getPropStyle());
                    $val->currentTab = $this->currentTab + 1;
                    $val->tabSize = $this->tabSize;
                    $val->NL = $this->NL;
                    $this->attributes[$keyValidated] = $val;
                } else {
                    $json = $this->_objectToJson($val);
                    $this->add($keyValidated, $json);

                    return true;
                }
            }

        return false;
    }
    /**
     * Adds a new key to the JSON data with its value as string.
     * 
     * @param string $key The name of the key. Must be non empty string.
     * 
     * @param string $val The value of the string. Note that if the given string 
     * is one of the following and the parameter <b>$toBool</b> is set to true, 
     * it will be converted into boolean (case insensitive).
     * <ul>
     * <li>yes => <b>true</b></li>
     * <li>no => <b>false</b></li>
     * <li>y => <b>true</b></li>
     * <li>n => <b>false</b></li>
     * <li>t => <b>true</b></li>
     * <li>f => <b>false</b></li>
     * <li>true => <b>true</b></li>
     * <li>false => <b>false</b></li>
     * <li>on => <b>true</b></li>
     * <li>off => <b>false</b></li>
     * <li>ok => <b>true</b></li>
     * </ul>
     * 
     * @param boolean $toBool If set to true and the string represents a boolean 
     * value, then the string will be added as a boolean. Default is false.
     * 
     * @return boolean The method will return true in case the string is added. 
     * If the given value is not a string or the given key is invalid or the 
     * parameter <b>$toBool</b> is set to true and given string is not a boolean, the 
     * method will return false.
     * 
     * @since 1.0
     */
    public function addString($key, $val,$toBool = false) {
        $keyValidated = JsonX::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == self::TYPES[1]) {
            if ($toBool) {
                $boolVal = $this->_stringAsBoolean($val);

                if ($boolVal === true || $boolVal === false) {
                    return $this->addBoolean($keyValidated, $boolVal);
                }
            } else {
                $this->attributes[$keyValidated] = '"'.JsonX::escapeJSONSpecialChars($val).'"';

                return true;
            }
        }

        return false;
    }
    /**
     * Escape JSON special characters from string.
     * If the given string is null,the method will return empty string.
     * 
     * @param string $string A value of one of JSON object properties. 
     * 
     * @return string An escaped version of the string.
     * 
     * @since 1.0
     */
    public static function escapeJSONSpecialChars($string) {
        $escapedJson = '';
        $string = ''.$string;

        if ($string) {
            $count = count(JsonX::SPECIAL_CHARS);

            for ($i = 0 ; $i < $count ; $i++) {
                if ($i == 0) {
                    $escapedJson = str_replace(JsonX::SPECIAL_CHARS[$i], JsonX::SPECIAL_CHARS_ESC[$i], $string);
                } else {
                    $escapedJson = str_replace(JsonX::SPECIAL_CHARS[$i], JsonX::SPECIAL_CHARS_ESC[$i], $escapedJson);
                }
            }
        }

        return $escapedJson;
    }
    /**
     * Returns a string that represents the value at the given key.
     * 
     * @param string $key The value of the key.
     * 
     * @return string|null The method will return a string that 
     * represents the value. If the key does not exists,  the method will 
     * return null.
     * 
     * @since 1.2
     */
    public function get($key) {
        $keyTrimmed = trim($key);

        if ($this->hasKey($keyTrimmed)) {
            return $this->attributes[$keyTrimmed];
        }

        return null;
    }
    /**
     * Checks if JsonX instance has the given key or not.
     * 
     * @param string $key The name of the key.
     * 
     * @return boolean The method will return true if the 
     * key exists. false if not.
     * 
     * @since 1.2
     */
    public function hasKey($key) {
        $keyTrimmed = trim($key);

        if (strlen($keyTrimmed) != 0 && isset($this->attributes[$keyTrimmed])) {
            return true;
        }

        return false;
    }
    /**
     * Creates and returns a well formatted JSON string that will be created using 
     * provided data.
     * 
     * @return string A well formatted JSON string that will be created using 
     * provided data.
     */
    public function toJSONString() {
        return $this.'';
    }
    /**
     * @since 1.2.2
     */
    private function _addTab() {
        $this->currentTab++;
    }
    /**
     * A helper method used to parse arrays.
     * 
     * @param array $value
     * 
     * @return string A JSON string that represents the array.
     * 
     * @since 1.0
     */
    private function _arrayToJSONString($value,$asObject = false,$isSubArray = false) {
        $keys = array_keys($value);
        $keysCount = count($keys);

        if ($asObject === true) {
            $arr = '{'.$this->NL;
        } else {
            $arr = '['.$this->NL;
        }

        if (!$isSubArray) {
            $this->_addTab();
        }

        if ($keysCount > 0) {
            $this->_addTab();
        }

        for ($x = 0 ; $x < $keysCount ; $x++) {
            if ($x + 1 == $keysCount) {
                $comma = ''.$this->NL;
            } else {
                $comma = ', '.$this->NL;
            }
            $valueAtKey = $value[$keys[$x]];
            $keyType = gettype($keys[$x]);
            $valueType = gettype($valueAtKey);

            if ($valueAtKey instanceof JsonI) {
                $jsonXObj = $valueAtKey->toJSON();
                $jsonXObj->tabSize = $this->tabSize;
                $jsonXObj->currentTab = $this->currentTab;
                $jsonXObj->NL = $this->NL;

                if ($asObject === true) {
                    $arr .= $this->_getTab().'"'.$keys[$x].'":'.trim($jsonXObj).$comma;
                } else {
                    $arr .= $this->_getTab().trim($jsonXObj).$comma;
                }
            } else if ($valueAtKey instanceof JsonX) {
                    $valueAtKey->setPropsStyle($this->getPropStyle());
                    $valueAtKey->tabSize = $this->tabSize;
                    $valueAtKey->currentTab = $this->currentTab;
                    $valueAtKey->NL = $this->NL;

                    if ($asObject === true) {
                        $arr .= $this->_getTab().'"'.$keys[$x].'":'.trim($valueAtKey).$comma;
                    } else {
                        $arr .= $this->_getTab().trim($valueAtKey).$comma;
                    }
            } else if ($keyType == self::TYPES[0]) {
                if ($valueType == self::TYPES[0] || $valueType == self::TYPES[2]) {
                    if ($asObject === true) {
                        if (is_nan($valueAtKey)) {
                            $arr .= $this->_getTab().'"'.$keys[$x].'":"NAN"'.$comma;
                        } else {
                            if ($valueAtKey == INF) {
                                $arr .= $this->_getTab().'"'.$keys[$x].'":"INF"'.$comma;
                            } else {
                                $arr .= $this->_getTab().'"'.$keys[$x].'":'.$valueAtKey.$comma;
                            }
                        }
                    } else if (is_nan($valueAtKey)) {
                                    $arr .= $this->_getTab().'"NAN"'.$comma;
                    } else if ($valueAtKey == INF) {
                        $arr .= $this->_getTab().'"INF"'.$comma;
                    } else {
                        $arr .= $this->_getTab().$valueAtKey.$comma;
                    }
                } else if ($valueType == self::TYPES[1]) {
                    if ($asObject === true) {
                        $asBool = $this->_stringAsBoolean($valueAtKey);

                        if ($asBool === true || $asBool === false) {
                            $toAdd = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                            $arr .= $this->_getTab().'"'.$keys[$x].'":'.$toAdd;
                        } else {
                            $arr .= $this->_getTab().'"'.$keys[$x].'":"'.JsonX::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                        }
                    } else {
                        $asBool = $this->_stringAsBoolean($valueAtKey);

                        if ($asBool === true || $asBool === false) {
                            $toAdd = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                            $arr .= $toAdd;
                        } else {
                            $arr .= $this->_getTab().'"'.JsonX::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                        }
                    }
                } else if ($valueType == self::TYPES[3]) {
                    if ($asObject) {
                        if ($valueAtKey) {
                            $arr .= $this->_getTab().'"'.$keys[$x].'":true'.$comma;
                        } else {
                            $arr .= $this->_getTab().'"'.$keys[$x].'":false'.$comma;
                        }
                    } else {
                        if ($valueAtKey) {
                            $arr .= $this->_getTab().self::$BoolTypes[0].$comma;
                        } else {
                            $arr .= $this->_getTab().self::$BoolTypes[1].$comma;
                        }
                    }
                } else if ($valueType == self::TYPES[4]) {
                    if ($asObject) {
                        $arr .= $this->_getTab().'"'.$keys[$x].'":'.$this->_arrayToJSONString($valueAtKey,$asObject,true).$comma;
                    } else {
                        $arr .= $this->_getTab().$this->_arrayToJSONString($valueAtKey,$asObject, true).$comma;
                    }
                } else if ($valueType == self::TYPES[5]) {
                    if ($asObject) {
                        $arr .= $this->_getTab().'"'.$keys[$x].'":'.'null'.$comma;
                    } else {
                        $arr .= $this->_getTab().'null'.$comma;
                    }
                } else if ($valueType == self::TYPES[6]) {
                    if ($asObject) {
                        if ($valueAtKey instanceof JsonX) {
                            $valueAtKey->setPropsStyle($this->getPropStyle());
                            $valueAtKey->currentTab = $this->currentTab;
                            $valueAtKey->tabSize = $this->tabSize;
                            $valueAtKey->NL = $this->NL;
                            $arr .= $this->_getTab().'"'.$keys[$x].'":'.trim($valueAtKey).$comma;
                        } else {
                            $json = $this->_objectToJson($valueAtKey);
                            $arr .= $this->_getTab().'"'.$keys[$x].'":'.trim($json).$comma;
                        }
                    } else if ($valueAtKey instanceof JsonX) {
                        $valueAtKey->setPropsStyle($this->getPropStyle());
                        $valueAtKey->tabSize = $this->tabSize;
                        $valueAtKey->currentTab = $this->currentTab;
                        $valueAtKey->NL = $this->NL;
                        $arr .= $this->_getTab().$valueAtKey.$comma;
                    } else {
                        $json = $this->_objectToJson($valueAtKey);
                        $arr .= $this->_getTab().trim($json).$comma;
                    }
                }
            } else if ($asObject) {
                $arr .= $this->_getTab().'"'.$keys[$x].'":';
                $type = gettype($valueAtKey);

                if ($type == self::TYPES[1]) {
                    $asBool = $this->_stringAsBoolean($valueAtKey);

                    if ($asBool === true || $asBool === false) {
                        $result = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                        $arr .= $result;
                    } else {
                        $arr .= '"'.self::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                    }
                } else if ($type == self::TYPES[0] || $type == self::TYPES[2]) {
                    $arr .= $valueAtKey.$comma;
                } else if ($type == self::TYPES[3]) {
                    $arr .= $valueAtKey === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                } else if ($type == self::TYPES[5]) {
                    $arr .= 'null'.$comma;
                } else if ($type == self::TYPES[4]) {
                    $result = $this->_arrayToJSONString($valueAtKey, $asObject, true);
                    $arr .= $result.$comma;
                } else if ($type == self::TYPES[6]) {
                    if ($valueAtKey instanceof JsonX) {
                        $valueAtKey->setPropsStyle($this->getPropStyle());
                        $valueAtKey->currentTab = $this->currentTab;
                        $valueAtKey->tabSize = $this->tabSize;
                        $valueAtKey->NL = $this->NL;
                        $arr .= trim($valueAtKey).$comma;
                    } else {
                        $json = $this->_objectToJson($valueAtKey);
                        $arr .= trim($json).$comma;
                    }
                } else {
                    $arr .= 'null'.$comma;
                }
            } else {
                $j = new JsonX();
                $j->setPropsStyle($this->getPropStyle());
                $j->currentTab = $this->currentTab;
                $j->tabSize = $this->tabSize;
                $j->add($keys[$x], $valueAtKey);
                $arr .= $j.$comma;
            }
        }

        if ($keysCount > 0) {
            $this->_reduceTab();
        }

        if ($asObject === true) {
            $arr .= $this->_getTab().'}';
        } else {
            $arr .= $this->_getTab().']';
        }

        if (!$isSubArray) {
            $this->_reduceTab();
        }

        return $arr;
    }
    /**
     * 
     * 
     * @return string
     * 
     * @since 1.2.2
     */
    private function _getTab() {
        $tabLen = $this->tabSize * $this->currentTab;

        if (strlen($this->tabStr) != $tabLen) {
            $this->tabStr = '';

            for ($x = 0 ; $x < $tabLen ; $x++) {
                $this->tabStr .= ' ';
            }
        }

        return $this->tabStr;
    }
    /**
     * 
     * @param array $data
     * @since 1.2.2
     */
    private function _initData($data) {
        if (gettype($data) == self::TYPES[4]) {
            foreach ($data as $key => $value) {
                $this->add($key, $value);
            }
        }
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
    private static function _isValidKey($key, $style='kebab') {
        $trimmedKey = trim($key);

        if (strlen($trimmedKey) != 0) {
            return self::_getAttrName($trimmedKey, $style);
        }

        return false;
    }
    /**
     * 
     * @param type $valueAtKey
     * 
     * @return \jsonx\JsonX
     */
    private function _objectToJson($valueAtKey) {
        $methods = get_class_methods($valueAtKey);
        $count = count($methods);
        $json = new JsonX();
        $json->setPropsStyle($this->getPropStyle());
        $json->currentTab = $this->currentTab;
        $json->tabSize = $this->tabSize;
        $json->NL = $this->NL;
        $propNum = 0;
        set_error_handler(function()
        {
        });

        for ($y = 0 ; $y < $count; $y++) {
            $funcNm = substr($methods[$y], 0, 3);

            if (strtolower($funcNm) == 'get') {
                $propVal = call_user_func([$valueAtKey, $methods[$y]]);

                if ($propVal !== false && $propVal !== null) {
                    $json->add('prop-'.$propNum, $propVal);
                    $propNum++;
                }
            }
        }

        return $json;
    }
    private function _reduceTab() {
        if ($this->currentTab > 0) {
            $this->currentTab--;
        }
    }
    private function _stringAsBoolean($str) {
        $lower = strtolower($str);
        $boolTypes = [
            't' => true,
            'f' => false,
            'yes' => true,
            'no' => false,
            'true' => true,
            'false' => false,
            'on' => true,
            'off' => false,
            'y' => true,
            'n' => false,
            'ok' => true
        ];

        if (isset($boolTypes[$lower])) {
            return $boolTypes[$lower];
        }

        return 'INV';
    }
    /**
     * Returns the style at which the names of the properties will use.
     * 
     * @return string The method will return one of the following values:
     * <ul>
     * <li>snake</li>
     * <li>kebab</li>
     * <li>camel</li>
     * </ul>
     * 
     * @since 1.2.4
     */
    public function getPropStyle() {
        return $this->attrNameStyle;
    }
    /**
     * Convert the name of the prop name to the correct case.
     * 
     * @param type $attr
     * 
     * @return type
     */
    private static function _getAttrName($attr, $style) {
        if ($style == 'snake') {
            return self::_toSnackCase($attr);
        } else if ($style == 'kebab') {
            return self::_toKebabCase($attr);
        } else if ($style == 'camel') {
            return self::_toCamelCase($attr);
        } else {
            return $attr;
        }
    }
    private static function _toCamelCase($attr) {
        $retVal = '';
        $changeNextCharCase = false;
        for ($x = 0 ; $x < strlen($attr) ; $x++) {
            if (($attr[$x] == '-' || $attr[$x] == '_') && $x != 0) {
                $changeNextCharCase = true;
                continue;
            }
            if ($changeNextCharCase) {
                $retVal .= strtoupper($attr[$x]);
                $changeNextCharCase = false;
            } else {
                $retVal .= $attr[$x];
            }
        }
        return $retVal;
    }
    private static function _toKebabCase($attr) {
        $attr1 = str_replace('_', '-', $attr);
        $retVal = '';
        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            if ($attr1[$x] >= 'A' && $attr1[$x] <= 'Z' && $x != 0) {
                $retVal .= '-'.strtolower($attr1[$x]);
            } else {
                $retVal .= $attr1[$x];
            }
        }
        return $retVal;
    }
    private static function _toSnackCase($attr) {
        $attr1 = str_replace('-', '_', $attr);
        $retVal = '';
        for ($x = 0 ; $x < strlen($attr1) ; $x++) {
            if ($attr1[$x] >= 'A' && $attr1[$x] <= 'Z' && $x != 0) {
                $retVal .= '_'.strtolower($attr1[$x]);
            } else {
                $retVal .= $attr1[$x];
            }
        }
        return $retVal;
    }
}
