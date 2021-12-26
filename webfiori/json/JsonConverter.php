<?php
namespace webfiori\json;

/**
 * A class to convert Json instance to it's JSON string representation.
 *
 * @author Ibrahim
 * 
 * @version 1.0
 */
class JsonConverter {
    private static $CRLF = "\r\n";
    private static $CurrentTab = 0;
    private static $Tab = '';
    private static $TabSize = 0;
    private static $XmlClosingPool = [];
    /**
     * Converts an instance of Json to JSONx string.
     * 
     * @param Json $json The object that holds the attributes.
     * 
     * @return string Returns XML string that represents Json schema.
     * 
     * @since 1.0
     */
    public static function jsonToJsonXString(Json $json) {
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
     * Convert an object to Json object.
     * 
     * Note that the properties which will be in the generated Json
     * object will depend on the public 'get' methods of the object.
     * The name of the properties will depend on the name of the method. For
     * example, if the name of one of the methods is 'getFullName', then
     * property name will be 'FullName'.
     * 
     * @param object $obj The object that will be converted.
     * 
     * @return Json
     */
    public static function objectToJson($obj) {
        if (is_subclass_of($obj, 'webfiori\\json\\JsonI')) {
            return $obj->toJSON();
        } else {
            if ($obj instanceof Json) {
                return $obj;
            }
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

        $retVal .= self::checkVal($probVal, $probType, $prop->getStyle(), $prop->isAsObject());

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
        //$retVal .= self::$Tab;
        $retVal .= self::checkType($prop->getType(), $prop->getValue(), $prop);
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
            if ($x + 1 != $propsCount) {
                $jsonString .= self::propertyToJsonString($propsArr[$x]).','.self::$CRLF;
            } else {
                $jsonString .= self::propertyToJsonString($propsArr[$x]).self::$CRLF;
            }
        }
        self::updateTab(false);
        $jsonString .= self::$Tab.'}';

        return $jsonString;
    }

    /**
     * 
     * @param array $array
     * 
     * @param type $asObj
     * 
     * @param type $propsStyle
     * 
     * @return string
     * 
     * @since 1.0
     */
    private static function arrayToJsonString(array $array, $asObj, $propsStyle = 'snake') {
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
                $retVal .= $valToPreAppend.self::$Tab.self::checkVal($val, $valType, $propsStyle, $asObj);

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
            $retVal .= self::checkType(gettype($arrayEl), $arrayEl, $propObj, true);
        }

        return $retVal;
    }
    private static function checkType($datatype, $value, Property $prop = null, $isArrayValue = false) {
        $retVal = self::$Tab;

        if ($datatype == JsonTypes::STRING) {
            if ($isArrayValue) {
                $propX = new Property('x', $value);
                $retVal = self::propertyToJsonXString($propX, false);
            } else {
                $retVal .= htmlentities($value).self::$CRLF;
            }
        } else if ($datatype == JsonTypes::BOOL) {
            if ($isArrayValue) {
                $propX = new Property('x', $value);
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
                $propX = new Property('x', $value);
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal .= 'null'.self::$CRLF;
            }
        } else if ($datatype == JsonTypes::INT || $datatype == JsonTypes::DOUBLE) {
            if ($isArrayValue) {
                $propX = new Property('x', $value);
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal .= trim(self::getNumberVal($value),'"').self::$CRLF;
            }
        } else if ($datatype == JsonTypes::OBJ) {
            if ($isArrayValue) {
                $propX = new Property('x', $value);
                $propX->setStyle($prop->getStyle());
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal .= substr(self::objToJsonX($prop, $value), self::$CurrentTab * self::$TabSize);
            }
        } else if ($datatype == JsonTypes::ARR) {
            if ($isArrayValue) {
                $propX = new Property('x', $value);
                $propX->setStyle($prop->getStyle());
                $retVal .= substr(self::propertyToJsonXString($propX, false), self::$CurrentTab * self::$TabSize);
            } else {
                $retVal = self::arrayToJsonX($prop, $value);
            }
        }

        return $retVal;
    }
    private static function checkVal($val, $valType, $propsStyle, $asObj) {
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
            $retVal .= self::objToJson($val, $propsStyle);
        } else if ($valType == JsonTypes::ARR) {
            $retVal .= self::arrayToJsonString($val, $asObj, $propsStyle);
        }

        return $retVal;
    }
    /**
     * 
     * @param type $val
     * 
     * @return string
     * 
     * @since 1.0
     */
    private static function getNumberVal($val) {
        $retVal = $val;

        if (is_nan($retVal)) {
            $retVal = '"NaN"';
        } else if ($val == INF) {
            $retVal = '"Infinity"';
        }

        return $retVal;
    }
    /**
     * 
     * @param type $probVal
     * 
     * @param type $style
     * 
     * @return string
     * 
     * @since 1.0
     */
    private static function objToJson($probVal, $style) {
        if (!($probVal instanceof Json)) {
            if (!is_subclass_of($probVal, 'webfiori\\json\\JsonI')) {
                $probVal = self::objectToJson($probVal);
            } else {
                $probVal = $probVal->toJSON();
            }
        }
        $probVal->setPropsStyle($style);

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
    private static function objToJsonX(Property $prop, $val = null) {
        $asJson = self::objectToJson($val);

        if (count($asJson->getProperties()) == 0) {
            return self::$CRLF;
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
    private static function updateTab($increase = true) {
        if ($increase === true) {
            self::$CurrentTab++;
        } else {
            self::$CurrentTab--;
        }
        self::$Tab = str_repeat(' ', self::$CurrentTab * self::$TabSize);
    }
}
