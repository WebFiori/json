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
namespace WebFiori\Json;

/**
 * A class to convert objects and Json instances to their JSON string representation.
 *
 * The conversion of plain objects follows this order of precedence:
 * <ol>
 * <li>If the object implements {@see JsonI}, its {@see JsonI::toJSON()} method is called.</li>
 * <li>If the object is already an instance of {@see Json}, it is returned as-is.</li>
 * <li>Otherwise, public getter methods (prefixed with 'get') are called and their return
 * values are mapped to properties. The property name is derived by stripping the 'get'
 * prefix (e.g. <code>getFullName()</code> becomes <code>FullName</code>). Methods that
 * return false or null are skipped.</li>
 * <li>Finally, all public properties are extracted via reflection and added to the JSON
 * output. Unlike getter-based mapping, public properties with a null value are included.</li>
 * </ol>
 *
 * @author Ibrahim
 *
 */
class JsonConverter {
    private static $CRLF = "\r\n";
    private static $CurrentTab = 0;
    private static $Tab = '';
    private static $TabSize = 0;
    private static $XmlClosingPool = [];
    /**
     * Converts a plain PHP object to a {@see Json} instance.
     *
     * The conversion follows this order:
     * <ol>
     * <li>If the object implements {@see JsonI}, its {@see JsonI::toJSON()} method is
     * called and the result is returned directly.</li>
     * <li>If the object is already an instance of {@see Json}, it is returned as-is.</li>
     * <li>All public methods whose names start with 'get' are called. The portion of the
     * method name after 'get' becomes the property name (e.g. <code>getFullName()</code>
     * produces the key <code>FullName</code>). Methods returning false or null are
     * skipped.</li>
     * <li>All public properties are extracted via {@see \ReflectionClass} and added to
     * the result. Properties with a null value are included; private and protected
     * properties are ignored.</li>
     * </ol>
     *
     * @param object $obj The object to convert.
     *
     * @return Json A Json instance populated with the object's data.
     */
    public static function objectToJson($obj) {
        if (is_subclass_of($obj, 'Webfiori\\Json\\JsonI')) {
            return $obj->toJSON();
        } else if ($obj instanceof Json) {
            return $obj;
        }

        $methods = get_class_methods($obj);
        $count = count($methods);
        $json = new Json();

        set_error_handler(null);

        for ($y = 0 ; $y < $count; $y++) {
            $funcNm = substr($methods[$y], 0, 3);

            if (strtolower($funcNm) == 'get') {
                $propVal = call_user_func([$obj, $methods[$y]]);

                if ($propVal !== false && $propVal !== null) {
                    $json->add(substr($methods[$y], 3), $propVal);
                }
            }
        }

        restore_error_handler();

        $reflection = new \ReflectionClass($obj);
        $publicProps = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        
        foreach ($publicProps as $prop) {
            $name = $prop->getName();
            $value = $prop->getValue($obj);
            $json->add($name, $value);
        }

        return $json;
    }
    /**
     * Converts a JSON property to its JSON string representation.
     * 
     * @param Property $prop The property that will be converted.
     * 
     * @param boolean $formatted If set to true, the generated output will have
     * indentations and new lines which makes it readable.
     * 
     * @return string JSON representation of the property as string.
     * 
     * @since 1.0
     */
    public static function propertyToJsonString(Property $prop, $formatted = false) {
        if (self::$CurrentTab == 0) {
            self::setIsFormatted($formatted);
        }
        $retVal = self::$Tab.'"'.$prop->getName().'":';
        $probType = $prop->getType();
        $probVal = $prop->getValue();

        $retVal .= self::checkJsonType($probVal, $probType, $prop->getStyle(), $prop->getCase(), $prop->isAsObject());

        return $retVal;
    }
    public static function propertyToJsonXString(Property $prop, $withName = true) {
        if (self::$CurrentTab == 0) {
            self::setIsFormatted(true);
        }

        if ($withName) {
            $retVal = self::$Tab.'<'.$prop->getJsonXTagName().' name="'.$prop->getName().'">'.self::$CRLF;
        } else {
            $retVal = self::$Tab.'<'.$prop->getJsonXTagName().'>'.self::$CRLF;
        }

        self::push($prop->getJsonXTagName());
        $retVal .= self::checkJsonXType($prop->getType(), $prop->getValue(), $prop);
        $retVal .= self::pop().self::$CRLF;

        return $retVal;
    }
    /**
     * Convert Json instance to it's JSON string representation.
     * 
     * @param Json $jsonObj The object that will be converted.
     * 
     * @param boolean $formatted If set to true, the generated output will have
     * indentation and new lines which makes it readable. Note that the
     * size of generated string will increase if set to true.
     * 
     * @return string A well formatted JSON string.
     * 
     * @since 1.0
     */
    public static function toJsonString(Json $jsonObj, $formatted = false) {
        if (self::$CurrentTab == 0) {
            self::setIsFormatted($formatted);
        }
        $jsonString = '{'.self::$CRLF;
        $propsArr = $jsonObj->getProperties();
        $propsCount = count($propsArr);
        self::updateTab(true);

        for ($x = 0 ; $x < $propsCount ; $x++) {
            $prop = $propsArr[$x];
            $prop->setStyle($jsonObj->getPropStyle(), $jsonObj->getCase());

            if ($x + 1 != $propsCount) {
                $jsonString .= self::propertyToJsonString($prop).','.self::$CRLF;
            } else {
                $jsonString .= self::propertyToJsonString($prop).self::$CRLF;
            }
        }
        self::updateTab(false);
        $jsonString .= self::$Tab.'}';

        return $jsonString;
    }
    /**
     * Converts an instance of Json to JSONx string.
     * 
     * @param Json $json The object that holds the attributes.
     * 
     * @return string Returns XML string that represents Json schema.
     * 
     * @since 1.0
     */
    public static function toJsonXString(Json $json) {
        if (self::$CurrentTab == 0) {
            self::setIsFormatted(true);
        }
        $retVal = '<?xml version="1.0" encoding="UTF-8"?>'.self::$CRLF;
        $retVal .= '<json:object xsi:schemaLocation="http://www.datapower.com/schemas/json jsonx.xsd" '
                    .'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                    .'xmlns:json="http://www.ibm.com/xmlns/prod/2009/jsonx">'.self::$CRLF;
        self::push('json:object');

        foreach ($json->getProperties() as $prop) {
            $retVal .= self::propertyToJsonXString($prop);
        }
        $retVal .= self::pop();

        return $retVal;
    }

    /**
     * 
     * @param array $array
     * 
     * @param bool $asObj
     * 
     * @param string $propsStyle
     * 
     * @return string
     * 
     * @since 1.0
     */
    private static function arrayToJsonString(array $array, bool $asObj, string $propsStyle = 'snake', $lettersCase = 'same') {
        $retVal = '';

        if ($asObj === true) {
            $jsonObj = new Json();
            $jsonObj->setPropsStyle($propsStyle);

            foreach ($array as $key => $val) {
                $jsonObj->add($key, $val, $asObj);
            }
            $retVal = self::toJsonString($jsonObj);
        } else {
            self::updateTab(true);
            $retVal = '['.self::$CRLF;
            $valToPreAppend = "";

            foreach ($array as $val) {
                $valType = gettype($val);
                $retVal .= $valToPreAppend.self::$Tab.self::checkJsonType($val, $valType, $propsStyle, $lettersCase, $asObj);

                $valToPreAppend = ",".self::$CRLF;
            }
            self::updateTab(false);

            if (count($array) == 0) {
                $retVal .= self::$Tab.']';
            } else {
                $retVal .= self::$CRLF.self::$Tab.']';
            }
        }

        return $retVal;
    }
    private static function arrayToJsonX(Property $propObj, $value) {
        $retVal = '';

        if (count($value) == 0) {
            return '';
        }

        foreach ($value as $arrayEl) {
            $retVal .= self::checkJsonXType(gettype($arrayEl), $arrayEl, $propObj, true);
        }

        return $retVal;
    }
    private static function checkJsonType($val, $valType, $propsStyle, $lettersCase, $asObj) {
        $retVal = '';

        if ($valType == JsonTypes::STRING) {
            $retVal .= '"'.Json::escapeJSONSpecialChars($val).'"';
        } else if ($valType == JsonTypes::INT || $valType == JsonTypes::DOUBLE) {
            $retVal .= self::getNumberVal($val);
        } else if ($valType == JsonTypes::NUL) {
            $retVal .= 'null';
        } else if ($valType == JsonTypes::BOOL) {
            if ($val === true) {
                $retVal .= 'true';
            } else {
                $retVal .= 'false';
            }
        } else if ($valType == JsonTypes::OBJ) {
            $retVal .= self::objToJson($val, $propsStyle, $lettersCase);
        } else if ($valType == JsonTypes::ARR) {
            $retVal .= self::arrayToJsonString($val, $asObj, $propsStyle, $lettersCase);
        }

        return $retVal;
    }
    private static function checkJsonXType($datatype, $value, ?Property $prop, $isArrayValue = false) {
        $retVal = self::$Tab;
        $propX = new Property('x', $value);
        $propX->setStyle($prop->getStyle());

        if ($datatype == JsonTypes::STRING) {
            if ($isArrayValue) {
                $retVal = self::propertyToJsonXString($propX, false);
            } else {
                $retVal .= htmlentities($value).self::$CRLF;
            }
        } else if ($datatype == JsonTypes::BOOL) {
            if ($isArrayValue) {
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                if ($value === true) {
                    $retVal .= 'true'.self::$CRLF;
                } else {
                    $retVal .= 'false'.self::$CRLF;
                }
            }
        } else if ($datatype == JsonTypes::NUL) {
            if ($isArrayValue) {
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal .= 'null'.self::$CRLF;
            }
        } else if ($datatype == JsonTypes::INT || $datatype == JsonTypes::DOUBLE) {
            if ($isArrayValue) {
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal .= trim(self::getNumberVal($value),'"').self::$CRLF;
            }
        } else if ($datatype == JsonTypes::OBJ) {
            if ($isArrayValue) {
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal = self::objToJsonX($prop, $value);
            }
        } else if ($datatype == JsonTypes::ARR) {
            if ($isArrayValue) {
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else if ($prop->isAsObject()) {
                $jsonObj = new Json();
                $jsonObj->setPropsStyle($prop->getStyle());

                foreach ($value as $key => $val) {
                    $jsonObj->add($key, $val, true);
                }
                $retVal = self::objToJsonX($prop, $jsonObj);
            } else {
                $retVal = self::arrayToJsonX($prop, $value);
            }
        }

        return $retVal;
    }
    /**
     * 
     * @param mixed $val
     * 
     * @return string
     * 
     * @since 1.0
     */
    private static function getNumberVal($val) {
        $retVal = $val;

        if (is_nan($retVal)) {
            $retVal = '"NaN"';
        } else {
            if ($val == INF) {
                $retVal = '"Infinity"';
            }
        }

        return $retVal;
    }
    /**
     * Converts an object value to its JSON string representation.
     *
     * If the value is not already a {@see Json} instance and does not implement
     * {@see JsonI}, it is first passed through {@see self::objectToJson()} which
     * maps public getter methods and public properties via reflection.
     *
     * @param object $probVal The object to convert.
     * @param string $style   The property naming style to apply.
     * @param string $lettersCase The letter case to apply to property names.
     *
     * @return string JSON object string representation.
     *
     * @since 1.0
     */
    private static function objToJson($probVal, string $style, string $lettersCase) {
        if (!($probVal instanceof Json)) {
            if (!is_subclass_of($probVal, 'Webfiori\\Json\\JsonI')) {
                $probVal = self::objectToJson($probVal);
            } else {
                $probVal = $probVal->toJSON();
            }
        }
        $probVal->setPropsStyle($style, $lettersCase);

        $retVal = "{".self::$CRLF;
        self::updateTab(true);
        $subProbs = $probVal->getProperties();
        $subProbsCount = count($subProbs);

        for ($x = 0 ; $x < $subProbsCount ; $x++) {
            if ($x + 1 != $subProbsCount) {
                $retVal .= self::propertyToJsonString($subProbs[$x]).','.self::$CRLF;
            } else {
                $retVal .= self::propertyToJsonString($subProbs[$x]).self::$CRLF;
            }
        }
        self::updateTab(false);
        $retVal .= self::$Tab.'}';

        return $retVal;
    }
    private static function objToJsonX(Property $prop, mixed $val) {
        $asJson = self::objectToJson($val);

        if (count($asJson->getProperties()) == 0) {
            return '';
        }
        $asJson->setPropsStyle($prop->getStyle());
        $retVal = '';

        foreach ($asJson->getProperties() as $subProp) {
            $retVal .= self::propertyToJsonXString($subProp);
        }

        return $retVal;
    }
    private static function pop() {
        self::updateTab(false);

        return array_pop(self::$XmlClosingPool);
    }
    private static function push($tagName) {
        self::$XmlClosingPool[] = self::$Tab.'</'.$tagName.'>';
        self::updateTab();
    }
    /**
     * 
     * @param boolean $bool
     * 
     * @since 1.0
     */
    private static function setIsFormatted($bool) {
        if ($bool === true) {
            self::$TabSize = 4;
            self::$CRLF = "\r\n";
        } else {
            self::$TabSize = 0;
            self::$CRLF = "";
        }
    }
    /**
     * 
     * @param boolean $increase
     * 
     * @since 1.0
     */
    private static function updateTab(bool $increase = true) {
        if ($increase === true) {
            self::$CurrentTab++;
        } else {
            self::$CurrentTab--;
        }
        self::$Tab = str_repeat(' ', self::$CurrentTab * self::$TabSize);
    }
}
