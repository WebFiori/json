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

use Exception;
use InvalidArgumentException;
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
    private $formatted;
    private $propsArr;


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
    public function __construct(array $initialData = [], $isFormatted = false) {
        $this->propsArr = [];

        $this->setIsFormatted($isFormatted === true || (defined('WF_VERBOSE') && WF_VERBOSE === true));
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
        $keyTrimmed = CaseConverter::convert($key, $this->getPropStyle());
        $retVal = null;

        foreach ($this->getProperties() as $val) {
            if ($val->getName() == $keyTrimmed) {
                $retVal = $val->getValue();
                break;
            }
        }

        return $retVal;
    }
    /**
     * Returns a property object given its key.
     * 
     * @param string $key The key of the property.
     * 
     * @return Property|null if a property which has the given key exist, it
     * will be returned. Other than that, null is returned.
     */
    public function &getProperty(string $key) {
        $keyTrimmed = CaseConverter::convert($key, $this->getPropStyle());
        $retVal = null;

        foreach ($this->getProperties() as $val) {
            if ($val->getName() == $keyTrimmed) {
                $retVal = $val;
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
     * Adds a new value to JSON.
     * 
     * This method can be used to add an integer, a double, 
     * a string, an array or an object. If null is given, the method will 
     * set the value at the given key to null. If the given value or key is 
     * invalid, the method will not add the value and will return false.
     * This method also can be used to update the value of an existing property.
     * 
     * @param string $key The value of the key.
     * 
     * @param mixed $value The value of the key.
     * 
     * @param array $arrayAsObj This parameter is used only if the given value
     * is an array. If set to true, the array will be added as an object. 
     * Default is false.
     * 
     * @return boolean The method will return true if the value is set. 
     * If the given value or key is invalid, the method will return false.
     * 
     * @since 1.1
     */
    public function add(string $key, $value, $arrayAsObj = false) {
        if (!$this->updateExisting($key, $value)) {
            return $this->addString($key, $value) ||
            $this->addArray($key, $value, $arrayAsObj) ||
            $this->addBoolean($key, $value) ||
            $this->addNumber($key, $value) || 
            $this->addObject($key, $value) ||
            $this->addNull($key);
        }

        return true;
    }
    /**
     * Adds an array to the JSON.
     * 
     * This method also can be used to update the value of an existing property.
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
    public function addArray(string $key, $value, $asObject = false) {
        if (!$this->updateExisting($key, $value)) {
            $prop = $this->createProb($key, $value);
            $propType = $prop->getType();

            if ($propType == JsonTypes::ARR) {
                $prop->setAsObject($asObject);
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        } else {
            $this->getProperty($key)->setAsObject($asObject);

            return true;
        }
    }
    /**
     * Adds a boolean value (true or false) to the JSON data.
     * 
     * This method also can be used to update the value of an existing property.
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
    public function addBoolean($key, $val = true) {
        if (!$this->updateExisting($key, $val)) {
            $prop = $this->createProb($key, $val);

            if ($prop->getType() == 'boolean') {
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        }

        return true;
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
    public function addMultiple(array $arr) {
        foreach ($arr as $key => $value) {
            $this->add($key, $value);
        }
    }
    /**
     * Adds a 'null' value to JSON.
     * 
     * This method also can be used to update the value of an existing property.
     * 
     * @param string $key The name of value key.
     * 
     * @return boolean The method will return true if the value is set. 
     * If the given value or key is invalid, the method will return false.
     */
    public function addNull(string $key) {
        $nul = null;

        if (!$this->updateExisting($key, $nul)) {
            $prop = $this->createProb($key, $nul);
            $propType = $prop->getType();

            if ($propType == JsonTypes::NUL) {
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        }

        return true;
    }
    /**
     * Adds a number to the JSON data.
     * 
     * Note that if the given number is the constant <b>INF</b> or the constant 
     * <b>NAN</b>, The method will add them as a string. The 'INF' will be added
     * as the string "Infinity" and the 'NAN' will be added as the string "Nan".
     * This method also can be used to update the value of an existing property.
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
    public function addNumber(string $key, $value) {
        if (!$this->updateExisting($key, $value)) {
            $prop = $this->createProb($key, $value);
            $propType = $prop->getType();

            if ($propType == JsonTypes::INT || $propType == JsonTypes::DOUBLE) {
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        }

        return true;
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
     * This method also can be used to update the value of an existing property.
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
    public function addObject(string $key, &$val) {
        if (!$this->updateExisting($key, $val)) {
            $prop = $this->createProb($key, $val);
            $propType = $prop->getType();

            if ($propType == JsonTypes::OBJ) {
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        }

        return true;
    }
    /**
     * Adds a new key to the JSON data with its value as string.
     * 
     * This method also can be used to update the value of an existing property.
     * 
     * @param string $key The name of the key. Must be non empty string.
     * 
     * @param string $val The value of the string.
     * 
     * @return boolean The method will return true in case the string is added. 
     * If the given value is not a string or the given key is invalid, the 
     * method will return false.
     * 
     * @since 1.0
     */
    public function addString(string $key, $val) {
        if (!$this->updateExisting($key, $val)) {
            $prop = $this->createProb($key, $val);

            if ($prop->getType() == JsonTypes::STRING) {
                $this->propsArr[] = $prop;

                return true;
            }

            return false;
        }

        return true;
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

        throw  new JsonException(json_last_error_msg(), json_last_error());
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
    public static function fromJsonFile($pathToJsonFile) {
        if (file_exists($pathToJsonFile)) {
            $fileContent = file_get_contents($pathToJsonFile);

            if ($fileContent !== false) {
                return self::decode($fileContent);
            }
        }
    }
    /**
     * Returns an array that holds all added attributes.
     * 
     * @return array An array that holds objects of type 'Property'.
     */
    public function getProperties() {
        return $this->propsArr;
    }
    /**
     * Returns an array that contains the names of all added properties.
     * 
     * Note that the names may differ if properties style is changed after
     * adding them.
     * 
     * @return array An array that contains the names of all added properties.
     * 
     * @since 1.2.5
     */
    public function getPropsNames() {
        $retVal = [];

        foreach ($this->getProperties() as $propObj) {
            $retVal[] = $propObj->getName();
        }

        return $retVal;
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
        $keyTrimmed = CaseConverter::convert($key, $this->getPropStyle());

        return in_array($keyTrimmed, $this->getPropsNames());
    }
    /**
     * Checks if the final JSON output will be formatted or not.
     * 
     * This can be used to make the generated output readable by adding 
     * indentation and new lines.
     * 
     * @return boolean True if will be formatted. False otherwise.
     */
    public function isFormatted() {
        return $this->formatted;
    }

    /**
     * Removes a property from the instance.
     * 
     * @param string $keyName The name of the property.
     * 
     * @return Property|null The method will return the property as object if
     * removed. Other than that, the method will return null.
     * 
     * @since 1.2.5
     */
    public function remove($keyName) {
        $keyValidated = CaseConverter::convert($keyName, $this->getPropStyle());

        if ($this->hasKey($keyValidated)) {
            $oldPropsArr = $this->getProperties();
            $this->propsArr = [];
            $retVal = null;

            foreach ($oldPropsArr as $prop) {
                if ($prop->getName() != $keyValidated) {
                    $this->propsArr[] = $prop;
                } else {
                    $retVal = $prop;
                }
            }

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
        $this->formatted = $bool === true;

        foreach ($this->getProperties() as $prop) {
            if ($prop->getValue() instanceof Json) {
                $prop->getValue()->setIsFormatted($this->isFormatted());
            } else if ($prop->getType() == JsonTypes::ARR) {
                $this->_setIsFormattedArray($prop->getValue());
            }
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

        if (in_array($trimmed, CaseConverter::PROP_NAME_STYLES)) {
            $this->attrNameStyle = $trimmed;

            foreach ($this->getProperties() as $prop) {
                $prop->setStyle($style);
            }
        }
    }
    /**
     * Attempt to write the generated JSON to a .json file.
     * 
     * @param string $fileName The name of the file at which JSON output will be
     * sent to. If the file does not exist, the method will attempt to create it.
     * 
     * @param string $path The folder in file system that the file will be created
     * at. If does not exist, the method will attempt to create it.
     * 
     * @param bool $override If a file exist in the specified location with same
     * name and this parameter is set to true, the method will override existing
     * file by deleting it and creating new one.
     * 
     * @throws Exception
     */
    public function toJsonFile(string $fileName, string $path, bool $override = false) {
        $nameTrim = trim($fileName);

        if (strlen($nameTrim) == 0) {
            throw new JsonException('Invalid file name: '.$fileName, -1);
        }
        $pathTrimmed = trim(str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $path)));

        if (strlen($pathTrimmed) == 0) {
            throw new JsonException('Invalid file path: '.$path, -1);
        }

        if (!is_dir($pathTrimmed) && !mkdir($pathTrimmed, 0777 , true)) {
            throw new JsonException("Unable to create directory '$pathTrimmed'", -1);
        }

        $fixedName = explode('.', $fileName)[0];
        $fullPath = $path.DIRECTORY_SEPARATOR.$fixedName.'.json';

        $isExist = file_exists($fullPath);

        if ($isExist && !$override) {
            throw new JsonException("File already exist: '$fullPath'", -1);
        } else {
            if ($isExist && $override) {
                unlink($fullPath);
            }
        }
        $resource = fopen($fullPath, 'wb');

        if (!is_resource($resource)) {
            throw new JsonException("Unable to open file for writing: '$fullPath'", -1);
        }
        fwrite($resource, $this->toJSONString());
        fclose($resource);
    }
    /**
     * Creates and returns a well formatted JSON string that will be created using 
     * provided data.
     * 
     * @return string A well formatted JSON string that will be created using 
     * provided data.
     */
    public function toJSONString() {
        return JsonConverter::toJsonString($this, $this->isFormatted());
    }
    /**
     * Creates and returns a well formatted XML string that will be created using 
     * provided data.
     * 
     * @return string A well formatted JSONx string that will be created using 
     * provided data.
     */
    public function toJSONxString() {
        return JsonConverter::toJsonXString($this);
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
     * 
     * @param array $data
     * @since 1.2.2
     */
    private function _initData($data) {
        foreach ($data as $key => $value) {
            $this->add($key, $value);
        }
    }
    private static function _isIndexedArr($arr) {
        $isIndexed = true;

        foreach ($arr as $index => $val) {
            $isIndexed = $isIndexed && gettype($index) == 'integer';
        }

        return $isIndexed;
    }
    private function _setIsFormattedArray(&$arr) {
        foreach ($arr as $arrVal) {
            if ($arrVal instanceof Json) {
                $arrVal->setIsFormatted($this->isFormatted());
            } else {
                if (gettype($arrVal) == 'array') {
                    $this->_setIsFormattedArray($arrVal);
                }
            }
        }
    }
    /**
     * 
     * @param type $name
     * 
     * @param type $value
     * 
     * @return Property|null
     */
    private function createProb($name, $value) {
        try {
            return new Property($name, $value, $this->getPropStyle());
        } catch (InvalidArgumentException $ex) {
            throw new InvalidArgumentException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
    private function updateExisting($key, &$val) {
        $tempProp = $this->getProperty($key);

        if ($tempProp !== null) {
            $tempProp->setValue($val);

            return true;
        }

        return false;
    }
}
