<?php
/*
 * The MIT License
 *
 * Copyright 2019 Ibrahim, WebFiori Json library.
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
 * @version 1.2.5
 */
class Json {
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
     * The style of attribute name.
     * 
     * @var string 
     * 
     * @since 1.2.4
     */
    private $attrNameStyle;
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
     * An array that contains JSON data.
     * 
     * This array will store the keys as indices and every value will be at 
     * each index.
     * 
     * @var array 
     * 
     * @since 1.0
     */
    private $originals = [];
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
     * will be ignored if the constant 'WF_VERBOSE' is defined and is set to true.
     * 
     * @since 1.2.2
     */
    public function __construct(array $initialData = [],$isFormatted = false) {
        $this->currentTab = 0;

        if ($isFormatted === true || (defined('WF_VERBOSE') && WF_VERBOSE === true)) {
            $this->tabSize = 4;
            $this->NL = "\n";
        } else {
            $this->tabSize = 0;
            $this->NL = '';
        }
        $this->setPropsStyle('none');

        if (defined('JSON_PROP_STYLE')) {
            $this->setPropsStyle(JSON_PROP_STYLE);
        }

        $this->_initData($initialData);
    }
    /**
     * Returns the value at the given key.
     * 
     * @param string $key The value of the key. Note that the style of the key 
     * does not matter.
     * 
     * @return Json|mixed|null The return type will depends on the value which 
     * was set by any method which can be used to add props. It can be a number, 
     * a boolean, string, an object or null if does not exist.
     * 
     * @since 1.2
     */
    public function &get($key) {
        $keyTrimmed = self::_isValidKey($key, $this->getPropStyle());
        $retVal = null;

        foreach ($this->originals as $key => $val) {
            if (self::_isValidKey($key, $this->getPropStyle()) == $keyTrimmed) {
                $retVal = $val['val'];
                break;
            }
        }

        return $retVal;
    }
    /**
     * Returns the data on the object as a JSON string.
     * 
     * @return string
     */
    public function __toString() {
        return $this->toJSONString();
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
            $keyValidated = Json::_isValidKey($key, $this->getPropStyle());

            if ($keyValidated !== false) {
                $this->_addToOriginals($keyValidated, null, 'null');

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
        $keyValidated = Json::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($value) == JsonTypes::ARR) {
            $this->_addToOriginals($keyValidated, $value, 'array', ['array-as-object' => $asObject === true]);

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
        $keyValidated = Json::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == JsonTypes::BOOL) {
            $this->_addToOriginals($keyValidated, $val, 'boolean');

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
     * @throws InvalidArgumentException If the given parameter is not an array.
     * 
     * @since 1.2.3
     */
    public function addMultiple($arr) {
        $paramType = gettype($arr);

        if ($paramType != 'array') {
            throw new \InvalidArgumentException('Was expecting an array. '.$paramType.' is given.');
        }

        foreach ($arr as $key => $value) {
            $this->add($key, $value);
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
        $retVal = false;

        if ($keyValidated !== false && ($val_type == JsonTypes::INT || $val_type == JsonTypes::DOUBLE)) {
            if (is_nan($value)) {
                $retVal = $this->addString($keyValidated, 'NAN');
            } else if ($value == INF) {
                $retVal = $this->addString($keyValidated, 'INF');
            } else {
                $retVal = true;
            }

            if ($retVal) {
                $this->_addToOriginals($keyValidated, $value, 'number');
            }
        }

        return $retVal;
    }
    /**
     * Adds an object to the JSON string.
     * 
     * The object that will be added can implement the interface JsonI to make 
     * the generated JSON string customizable. Also, the object can be of 
     * type Json. If the given value is an object that does not implement the 
     * interface JsonI or it is not of type Json, 
     * The method will try to extract object information based on its "getXxxxx()" public 
     * methods. Assuming that the object has 2 public methods with names 
     * <code>getFirstProp()</code> and <code>getSecondProp()</code>. 
     * In that case, the generated JSON will be on the formate 
     * <b>{"FirstProp":"prop-1","SecondProp":""}</b>.
     * 
     * @param string $key The key value.
     * 
     * @param JsonI|Json|object $val The object that will be added.
     * 
     * @return boolean The method will return true if the object is added. 
     * If the key value is invalid string, the method will return false.
     * 
     * @since 1.0
     */
    public function addObject($key, $val) {
        $keyValidated = self::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == JsonTypes::OBJ) {
            if (is_subclass_of($val, 'webfiori\json\JsonI')) {
                $this->_addToOriginals($keyValidated, $val, 'jsoni');
                return true;
            } else if ($val instanceof Json) {
                $this->_addToOriginals($keyValidated, $val, 'jsonx');
                return true;
            } else if (gettype($val) == 'object') {
                $this->_addToOriginals($keyValidated, $val, 'object');
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
        $keyValidated = Json::_isValidKey($key, $this->getPropStyle());

        if ($keyValidated !== false && gettype($val) == JsonTypes::STRING) {
            if ($toBool) {
                $boolVal = $this->_stringAsBoolean($val);

                if ($boolVal === true || $boolVal === false) {
                    return $this->addBoolean($keyValidated, $boolVal);
                }
            } else {
                $this->_addToOriginals($keyValidated, $val, 'string');
                return true;
            }
        }

        return false;
    }
    /**
     * Converts a JSON-like string to JSON object.
     * 
     * Note that this method uses the function 'json_decode()' to parse the 
     * given JSON string. This means same rules which applies to 'json_decode()'
     * applies here.
     * 
     * @param string $jsonStr A string which looks like JSON object.
     * 
     * @return array|Json If the given string represents A valid JSON, it 
     * will be converted to Json object and returned. Other than that, the 
     * method will return an array that contains information about parsing error. 
     * The array will have two indices, 'error-code' and 'error-message'.
     * 
     * @since 1.2.5
     */
    public static function decode($jsonStr) {
        $decodedStd = json_decode($jsonStr);

        if (gettype($decodedStd) == 'object') {
            $jsonXObj = new Json();
            $objProps = get_object_vars($decodedStd);

            foreach ($objProps as $key => $val) {
                self::_fixParsed($jsonXObj, $key, $val);
            }

            return $jsonXObj;
        }

        return [
            'error-code' => json_last_error(),
            'error-message' => json_last_error_msg()
        ];
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
            $count = count(Json::SPECIAL_CHARS);

            for ($i = 0 ; $i < $count ; $i++) {
                if ($i == 0) {
                    $escapedJson = str_replace(Json::SPECIAL_CHARS[$i], Json::SPECIAL_CHARS_ESC[$i], $string);
                } else {
                    $escapedJson = str_replace(Json::SPECIAL_CHARS[$i], Json::SPECIAL_CHARS_ESC[$i], $escapedJson);
                }
            }
        }

        return $escapedJson;
    }
    /**
     * Reads JSON data from a file and convert it to an object of type 'Json'.
     * 
     * @param string $pathToJsonFile The full path to a file that contains 
     * JSON data.
     * 
     * @return Json|null|array If the method was able to read the whole data 
     * and convert it to <code>Json</code> instance, the method will return 
     * an object of type <code>Json</code>. If the method was unable to convert 
     * file data to an object of type <code>Json</code>, it will return an 
     * array that contains error information. The array will have two indices, 
     * 'error-code' and 'error-message' Other than that, it will return null.
     * 
     * @since 1.2.5
     */
    public static function fromFile($pathToJsonFile) {
        $fileContent = file_get_contents($pathToJsonFile);

        if ($fileContent !== false) {
            return self::decode($fileContent);
        }
    }
    /**
     * Returns an array that contains the names of all added properties.
     * 
     * Note that the names will be returned same as when added without changing 
     * the style.
     * 
     * @return array An array that contains the names of all added properties.
     * 
     * @since 1.2.5
     */
    public function getPropsNames() {
        return array_keys($this->originals);
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
     * @since 1.2.4
     */
    public function getPropStyle() {
        return $this->attrNameStyle;
    }
    /**
     * Checks if Json instance has the given key or not.
     * 
     * Note that if properties style is set to 'none', the value of the key 
     * must be exactly the same as when the property was added or the method 
     * will consider the key as does not exist. For other styles, the method will 
     * convert the key to selected style and check for its existence. 
     * 
     * @param string $key The name of the key. 
     * 
     * @return boolean The method will return true if the 
     * key exists. false if not.
     * 
     * @since 1.2
     */
    public function hasKey($key) {
        $keyTrimmed = self::_isValidKey($key, $this->getPropStyle());
        $keys = array_keys($this->originals);

        foreach ($keys as $propKey) {
            if (self::_isValidKey($propKey, $this->getPropStyle()) == $keyTrimmed) {
                return true;
            }
        }

        return false;
    }
    /**
     * Removes a property from the instance.
     * 
     * @param string $keyName The name of the property.
     * 
     * @return mixed|null The method will return the value of the property if 
     * removed. Other than that, the method will return null.
     * 
     * @since 1.2.5
     */
    public function remove($keyName) {
        $keyValidated = self::_isValidKey($keyName, $this->getPropStyle());

        if ($this->hasKey($keyValidated)) {
            $retVal = $this->get($keyValidated);
            unset($this->originals[$keyValidated]);

            return $retVal;
        }
    }
    /**
     * Makes the JSON output appears readable or not.
     * 
     * If the output is formatted, the generated output will look like 
     * a tree. If not formatted, the output string will be generated as one line. 
     * 
     * 
     * @param boolean $bool True to make the output formatted and false to make 
     * it not.
     * 
     * @since 1.2.5
     */
    public function setIsFormatted($bool) {
        $formatted = $bool === true;

        if ($formatted) {
            $this->tabSize = 4;
            $this->NL = "\n";
            $this->currentTab = 0;
        } else {
            $this->tabSize = 0;
            $this->NL = '';
        }
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
     * @since 1.2.4
     */
    public function setPropsStyle($style) {
        $trimmed = strtolower(trim($style));

        if (in_array($trimmed, self::PROP_NAME_STYLES)) {
            $this->attrNameStyle = $trimmed;
        }
    }
    /**
     * Creates and returns a well formatted JSON string that will be created using 
     * provided data.
     * 
     * @return string A well formatted JSON string that will be created using 
     * provided data.
     */
    public function toJSONString() {
        return $this->_toJson(false);
    }
    /**
     * @since 1.2.2
     */
    private function _addTab() {
        if ($this->_isFormatted()) {
            $this->currentTab++;
        }
    }
    /**
     * 
     * @param type $key
     * @param type $value
     * @param type $type
     * @param type $options
     */
    private function _addToOriginals($key, $value, $type, $options = []) {
        $this->originals[$key] = [
            'type' => $type,
            'val' => $value,
            'options' => $options
        ];
    }
    private function _appendBool(&$jsonStr, $val,$propsTab, $keyPropStyle) {
        if ($val === true) {
            $jsonStr .= $propsTab.'"'.$keyPropStyle.'":true';
        } else {
            $jsonStr .= $propsTab.'"'.$keyPropStyle.'":false';
        }
    }
    private function _appendJsonI(&$jsonStr, $val, $propsTab, $keyPropStyle) {
        $jsonXObj = $val->toJSON();

        if ($this->tabSize != 0) {
            $jsonXObj->tabSize = $this->tabSize;
            $jsonXObj->currentTab = $this->currentTab;
        }
        $jsonXObj->NL = $this->NL;
        $jsonXObj->setPropsStyle($this->getPropStyle());
        $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.$jsonXObj->_toJson();
    }
    private function _appendJsonX(&$jsonStr, $val, $propsTab, $keyPropStyle) {
        if ($this->tabSize != 0) {
            $val->tabSize = $this->tabSize;
        }
        $val->currentTab = $this->currentTab;
        $val->NL = $this->NL;
        $val->setPropsStyle($this->getPropStyle());
        $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.$val->_toJson();
    }
    private function _appendNum(&$jsonStr, $val, $propsTab, $keyPropStyle) {
        if (is_nan($val)) {
            $jsonStr .= $propsTab.'"'.$keyPropStyle.'":"NAN"';
        } else if ($val == INF) {
            $jsonStr .= $propsTab.'"'.$keyPropStyle.'":"INF"';
        } else {
            $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.$val;
        }
    }
    private function _appendObj(&$jsonStr, $val, $propsTab, $keyPropStyle) {
        $jsonXObj = $this->_objectToJson($val);

        if ($this->tabSize != 0) {
            $jsonXObj->tabSize = $this->tabSize;
            $jsonXObj->currentTab = $this->currentTab;
        }
        $jsonXObj->NL = $this->NL;
        $jsonXObj->setPropsStyle($this->getPropStyle());
        $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.$jsonXObj->_toJson();
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
    private function _arrayToJSONString(array $value,$asObject = false) {
        $keys = array_keys($value);
        $keysCount = count($keys);

        if ($asObject === true) {
            $arr = '{'.$this->NL;
        } else {
            $arr = '['.$this->NL;
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
            $keyStyled = self::toStyle($keys[$x], $this->getPropStyle());
            $keyType = gettype($keys[$x]);
            $valueType = gettype($valueAtKey);

            if ($valueAtKey instanceof JsonI) {
                $jsonXObj = $valueAtKey->toJSON();
                $jsonXObj->tabSize = $this->tabSize;
                $jsonXObj->currentTab = $this->currentTab;
                $jsonXObj->NL = $this->NL;

                if ($asObject === true) {
                    $arr .= $this->_getTab().'"'.$keyStyled.'":'.trim($jsonXObj->_toJson()).$comma;
                } else {
                    $arr .= $this->_getTab().trim($jsonXObj->_toJson()).$comma;
                }
            } else if ($valueAtKey instanceof Json) {
                    $valueAtKey->setPropsStyle($this->getPropStyle());
                    $valueAtKey->tabSize = $this->tabSize;
                    $valueAtKey->currentTab = $this->currentTab;
                    $valueAtKey->NL = $this->NL;

                    if ($asObject === true) {
                        $arr .= $this->_getTab().'"'.$keys[$x].'":'.trim($valueAtKey->_toJson()).$comma;
                    } else {
                        $arr .= $this->_getTab().trim($valueAtKey->_toJson()).$comma;
                    }
            } else if ($keyType == 'integer') {
                if ($valueType == JsonTypes::INT || $valueType == JsonTypes::DOUBLE) {
                    if ($asObject === true) {
                        if (is_nan($valueAtKey)) {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":"NAN"'.$comma;
                        } else if ($valueAtKey == INF) {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":"INF"'.$comma;
                        } else {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":'.$valueAtKey.$comma;
                        }
                    } else if (is_nan($valueAtKey)) {
                                    $arr .= $this->_getTab().'"NAN"'.$comma;
                    } else if ($valueAtKey == INF) {
                        $arr .= $this->_getTab().'"INF"'.$comma;
                    } else {
                        $arr .= $this->_getTab().$valueAtKey.$comma;
                    }
                } else if ($valueType == JsonTypes::STRING) {
                    if ($asObject === true) {
                        $asBool = $this->_stringAsBoolean($valueAtKey);

                        if ($asBool === true || $asBool === false) {
                            $toAdd = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                            $arr .= $this->_getTab().'"'.$keyStyled.'":'.$toAdd;
                        } else {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":"'.Json::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                        }
                    } else {
                        $asBool = $this->_stringAsBoolean($valueAtKey);

                        if ($asBool === true || $asBool === false) {
                            $toAdd = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                            $arr .= $toAdd;
                        } else {
                            $arr .= $this->_getTab().'"'.Json::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                        }
                    }
                } else if ($valueType == JsonTypes::BOOL) {
                    if ($asObject) {
                        if ($valueAtKey) {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":true'.$comma;
                        } else {
                            $arr .= $this->_getTab().'"'.$keyStyled.'":false'.$comma;
                        }
                    } else if ($valueAtKey) {
                        $arr .= $this->_getTab().self::$BoolTypes[0].$comma;
                    } else {
                        $arr .= $this->_getTab().self::$BoolTypes[1].$comma;
                    }
                } else if ($valueType == JsonTypes::ARR) {
                    if ($asObject) {
                        $arr .= $this->_getTab().'"'.$keyStyled.'":'.$this->_arrayToJSONString($valueAtKey,$asObject).$comma;
                    } else {
                        $arr .= $this->_getTab().$this->_arrayToJSONString($valueAtKey,$asObject).$comma;
                    }
                } else if ($valueType == JsonTypes::NUL) {
                    if ($asObject) {
                        $arr .= $this->_getTab().'"'.$keyStyled.'":'.'null'.$comma;
                    } else {
                        $arr .= $this->_getTab().'null'.$comma;
                    }
                } else if ($valueType == JsonTypes::OBJ) {
                    if ($asObject) {
                        $json = $this->_objectToJson($valueAtKey);
                        $arr .= $this->_getTab().'"'.$keyStyled.'":'.trim($json->_toJson()).$comma;
                    } else {
                        $json = $this->_objectToJson($valueAtKey);
                        $arr .= $this->_getTab().trim($json).$comma;
                    }
                }
            } else if ($asObject) {
                $arr .= $this->_getTab().'"'.$keyStyled.'":';
                $type = gettype($valueAtKey);

                if ($type == JsonTypes::STRING) {
                    $asBool = $this->_stringAsBoolean($valueAtKey);

                    if ($asBool === true || $asBool === false) {
                        $result = $asBool === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                        $arr .= $result;
                    } else {
                        $arr .= '"'.self::escapeJSONSpecialChars($valueAtKey).'"'.$comma;
                    }
                } else if ($type == JsonTypes::INT || $type == JsonTypes::DOUBLE) {
                    $arr .= $valueAtKey.$comma;
                } else if ($type == JsonTypes::BOOL) {
                    $arr .= $valueAtKey === true ? self::$BoolTypes[0].$comma : self::$BoolTypes[1].$comma;
                } else if ($type == JsonTypes::NUL) {
                    $arr .= 'null'.$comma;
                } else if ($type == JsonTypes::ARR) {
                    $result = $this->_arrayToJSONString($valueAtKey, $asObject, true);
                    $arr .= $result.$comma;
                } else if ($type == JsonTypes::OBJ) {
                    $json = $this->_objectToJson($valueAtKey);
                    $arr .= trim($json->_toJson()).$comma;
                } else {
                    $arr .= 'null'.$comma;
                }
            } else {
                $j = new Json();
                $j->setPropsStyle($this->getPropStyle());
                $j->currentTab = $this->currentTab;
                $j->tabSize = $this->tabSize;
                $j->add($keyStyled, $valueAtKey);
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

        return $arr;
    }
    private static function _checkArr($subVal, &$parentArr) {
        $isIndexed = self::_isIndexedArr($subVal);

        if ($isIndexed) {
            $subArr = [];
            // A sub array. Can have sub arrays. 
            // Sub arrays can have objects.
            for ($x = 0 ; $x < count($subVal) ; $x++) {
                $subArrVal = $subVal[$x];

                if (gettype($subArrVal) == 'array') {
                    self::_checkArr($subArrVal, $subArr);
                } else if (gettype($subArrVal) == 'object') {
                    // Object inside array.
                    $subObj = new Json();
                    $props = get_object_vars($subArrVal);
                    foreach ($props as $propName => $propVal) {
                        self::_fixParsed($subObj, $propName, $propVal);
                    }
                    $subArr[] = $subObj;
                } else {
                    //Normal value inside array.
                    $subArr[] = $subArrVal;
                }
            }
            $parentArr[] = $subArr;
        }
    }
    /**
     * 
     * @param Json $jsonx
     * @param type $xVal
     */
    private static function _fixParsed($jsonx, $xKey, $xVal) {
        if (gettype($xVal) == 'array') {
            // An array inside object.
            $arr = [];
            self::_checkArr($xVal, $arr);
            $jsonx->add($xKey, $arr[0]);
        } else if (gettype($xVal) == 'object') {
            //An object
            $xJson = new Json();
            $xProps = get_object_vars($xVal);
            foreach ($xProps as $prop => $val) {
                self::_fixParsed($xJson, $prop, $val);
            }
            $jsonx->add($xKey, $xJson);
        } else {
            //A simple value. Just add it
            $jsonx->add($xKey, $xVal);
        }

        return $jsonx;
    }
    /**
     * Convert the name of the prop name to the correct case.
     * 
     * @param string $attr The name of the attribute.
     * 
     * @param string $style The name of the style that the given string will be 
     * converted to. It can be one of 3 values:
     * <ul>
     * <li>snake</li>
     * <li>kebab</li>
     * <li>camel</li>
     * </ul>
     * 
     * @return string The same string converted to selected style.
     * 
     * @since 1.2.5
     */
    public static function toStyle($attr, $style) {
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
        foreach ($data as $key => $value) {
            $this->add($key, $value);
        }
    }
    private function _isFormatted() {
        return $this->NL == "\n" && $this->tabSize != 0;
    }
    private static function _isIndexedArr($arr) {
        $isIndexed = true;

        foreach ($arr as $index => $val) {
            $isIndexed = $isIndexed && gettype($index) == 'integer';
        }

        return $isIndexed;
    }
    private static function _isUpper($char) {
        return $char >= 'A' && $char <= 'Z';
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
            return self::toStyle($trimmedKey, $style);
        }

        return false;
    }
    /**
     * 
     * @param type $valueAtKey
     * 
     * @return Json
     */
    private function _objectToJson($valueAtKey) {
        $methods = get_class_methods($valueAtKey);
        $count = count($methods);
        $json = new Json();
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
                    $json->add(substr($methods[$y], 3), $propVal);
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
    private static function _toCamelCase($attr) {
        $retVal = '';
        $changeNextCharCase = false;

        for ($x = 0 ; $x < strlen($attr) ; $x++) {
            $char = $attr[$x];

            if (($char == '-' || $char == '_') && $x != 0) {
                $changeNextCharCase = true;
                continue;
            }

            if ($changeNextCharCase) {
                $retVal .= strtoupper($char);
                $changeNextCharCase = false;
            } else {
                $retVal .= $attr[$x];
            }
        }
        return $retVal;
    }
    private function _toJson($parentCall = true) {
        if (!$parentCall) {
            $this->currentTab = 0;
        }
        $jsonStr = '{';
        $this->_addTab();
        $propsTab = $this->_getTab();
        $comma = $this->NL;
        $dataType = null;

        foreach ($this->originals as $key => $val) {
            $dataType = $val['type'];
            $keyPropStyle = self::_isValidKey($key, $this->getPropStyle());
            $jsonStr .= $comma;

            if ($dataType == 'string') {
                $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.'"'.Json::escapeJSONSpecialChars($val['val']).'"';
            } else if ($dataType == 'number') {
                $this->_appendNum($jsonStr, $val['val'], $propsTab, $keyPropStyle);
            } else if ($dataType == 'boolean') {
                $this->_appendBool($jsonStr, $val['val'], $propsTab, $keyPropStyle);
            } else if ($dataType == 'jsonx') {
                $this->_appendJsonX($jsonStr, $val['val'], $propsTab, $keyPropStyle);
            } else if ($dataType == 'jsoni') {
                $this->_appendJsonI($jsonStr, $val['val'], $propsTab, $keyPropStyle);
            } else if ($dataType == 'object') {
                $this->_appendObj($jsonStr, $val['val'], $propsTab, $keyPropStyle);
            } else if ($dataType == 'array') {
                $jsonStr .= $propsTab.'"'.$keyPropStyle.'":'.$this->_arrayToJSONString($val['val'],$val['options']['array-as-object']);
            } else if ($dataType == 'null') {
                $jsonStr .= $propsTab.'"'.$keyPropStyle.'":null';
            }
            $comma = ', '.$this->NL;
        }

        if ($this->currentTab > 1) {
            $this->currentTab--;
            $jsonStr .= $this->NL.$this->_getTab().'}';
        } else {
            $jsonStr .= $this->NL.'}';
        }

        return $jsonStr;
    }
    private static function _toKebabCase($attr) {
        $attr1 = str_replace('_', '-', $attr);
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
    private static function _toSnackCase($attr) {
        $attr1 = str_replace('-', '_', $attr);
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
}
