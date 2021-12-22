<?php
namespace webfiori\json;

/**
 * Description of JsonConverter
 *
 * @author Ibrahim
 */
class JsonConverter {
    private static $CurrentTab = 0;
    private static $TabSize = 0;
    private static $CRLF = "\r\n";
    private static $Tab = '';

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
    private static function updateTab($increase = true) {
        if ($increase === true) {
            self::$CurrentTab++;
        } else {
            self::$CurrentTab--;
        }
        self::$Tab = str_repeat(' ', self::$CurrentTab * self::$TabSize);
    }

    private static function _isIndexedArr($arr) {
        $isIndexed = true;

        foreach ($arr as $index => $val) {
            $isIndexed = $isIndexed && gettype($index) == 'integer';
        }

        return $isIndexed;
    }
    public static function arrayToJsonString(array $array, $asObj, $propsStyle = 'snake') {
        
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
                $retVal .= $valToPreAppend.self::$Tab;
                if ($val instanceof Json) {
                    $retVal .= self::toJsonString($val);
                } else if (is_subclass_of($val, 'webfiori\\json\\JsonI')) {
                    $retVal .= self::toJsonString($val->toJSON());
                } else if ($valType == JsonTypes::STRING) {
                    $retVal .= '"'.Json::escapeJSONSpecialChars($val).'"';
                } else if ($valType == JsonTypes::NUL) {
                    $retVal .= 'null';
                } else if ($valType == JsonTypes::OBJ) {
                    $retVal .= self::objToJson($val, $propsStyle);
                } else if ($valType == JsonTypes::ARR) {
                    $retVal .= self::arrayToJsonString($val, $asObj, $propsStyle);
                }  else if ($valType == JsonTypes::INT || $valType == JsonTypes::DOUBLE) {
                    $retVal .= self::getNumberVal($val);
                } else if ($valType == JsonTypes::BOOL) {
                    if ($val === true) {
                        $retVal .= 'true';
                    } else {
                        $retVal .= 'false';
                    }
                } else {
                    $retVal .= $val;
                }
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
    public static function propertyToJsonString(Property $prop, $formatted = false) {
        if (self::$CurrentTab == 0) {
            self::setIsFormatted($formatted);
        }
        $retVal = self::$Tab.'"'.$prop->getName().'":';
        $probType = $prop->getType();
        $probVal = $prop->getValue();
        
        if ($probType == JsonTypes::STRING) {
            $retVal .= '"'.Json::escapeJSONSpecialChars($probVal).'"';
        } else if ($probType == JsonTypes::INT || $probType == JsonTypes::DOUBLE) {
            $retVal .= self::getNumberVal($probVal);
        } else if ($probType == JsonTypes::NUL) {
            $retVal .= 'null';
        } else if ($probType == JsonTypes::BOOL) {
            if ($probVal === true) {
                $retVal .= 'true';
            } else {
                $retVal .= 'false';
            }
        } else if ($probType == JsonTypes::OBJ) {
            $retVal .= self::objToJson($probVal, $prop->getStyle());
        } else if ($probType == JsonTypes::ARR) {
            $retVal .= self::arrayToJsonString($probVal, $prop->isAsObject(), $prop->getStyle());
        }
        return $retVal;
    }
    private static function objToJson($probVal, $style) {
        if (!($probVal instanceof Json)) {
            if (!is_subclass_of($probVal, 'webfiori\\json\\JsonI')) {
                $probVal = Json::objectToJson($probVal);
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
    private static function getNumberVal($val) {
        $retVal = $val;
        if (is_nan($retVal)) {
            $retVal = '"NaN"';
        } else if ($val == INF) {
            $retVal = '"Infinity"';
        }
        return $retVal;
    }
    private static function _getTab() {
        $tabStr = str_repeat(' ', self::$CurrentTab * self::$TabSize);
        return $tabStr;
    }

    private static function setIsFormatted($bool) {
        if ($bool === true) {
            self::$TabSize = 4;
            self::$CRLF = "\r\n";
        } else {
            self::$TabSize = 0;
            self::$CRLF = "";
        }
    }
}
