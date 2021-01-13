<?php
class Validator{

    const EMAIL = 1;
    const STRING_SIMPLE = 2;
    const STRING_TRIM = 3;
    const NUMBER = 4;
    const NUMBER_POSITIVE = 5;
    const NUMBER_NEGATIVE = 6;
    const URL = 7;
    const IP = 8;
    const FILENAME = 9;
    const ARRAY_LIST = 10;
    const BOOLEAN_V = 11;
    const DEFAULTV = 0; // -> SIMPLE_STRING

    const VALID_STR = array(
        self::EMAIL=>'EMAIL',
        self::STRING_SIMPLE=>'STRING_SIMPLE',
        self::STRING_TRIM=>'STRING_TRIM',
        self::NUMBER=>'NUMBER',
        self::NUMBER_POSITIVE=>'NUMBER_POSITIVE',
        self::NUMBER_NEGATIVE=>'NUMBER_NEGATIVE',
        self::URL=>'URL',
        self::IP=>'IP',
        self::FILENAME=>'FILENAME',
        self::ARRAY_LIST=>'ARRAY',
        self::BOOLEAN_V=>'BOOLEAN'
    );

    public static $errores = "";

    public function __construct(){

    }

    public static function valid($array = array(), $args = array()){
        $retorno = false;
        if(count($array)>0 && count($array)==count($args)){
            foreach ($array as $key => $const_validator) {
                $value = $args[$key];
                switch($const_validator){
                    case self::EMAIL:
                        $retorno = self::validEmail($value);
                    break;

                    case self::STRING_SIMPLE:
                        $retorno = self::validString($value);
                    break;

                    case self::STRING_TRIM:
                        $retorno = self::validStringTrim($value);
                    break;

                    case self::BOOLEAN_V:
                        $retorno = self::validBoolean($value);
                    break;

                    case self::NUMBER:
                        $retorno = self::validNumber($value);
                    break;

                    case self::NUMBER_POSITIVE:
                        $retorno = self::validPositiveNumber($value);
                    break;

                    case self::NUMBER_NEGATIVE:
                        $retorno = self::validNegativeNumber($value);
                    break;

                    case self::URL:
                        $retorno = self::validURL($value);
                    break;

                    case self::IP:
                        $retorno = self::validIP($value);
                    break;

                    case self::FILENAME:
                        $retorno = self::validFileName($value);
                    break;

                    case self::ARRAY_LIST:
                        $retorno = self::validArray($value);
                    break;

                    default:
                        $retorno = self::validString($value);
                }

                if(!$retorno){
                    self::$errores = "error: '$key' debe ser de tipo |".self::VALID_STR[$const_validator]."| ¿'$value'?";
                    break;
                }
            }
        }
        return $retorno;
    }

    public static function validEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validString($string){
        return is_string($string) && strlen($string)>=0;
    }

    public static function validStringTrim($stringTmp){
        $retorno = true;
        if(self::validString($stringTmp)){
            $string = trim($stringTmp);
            $wos = preg_replace('/\s+/', '', $string);
            $retorno = $wos==$string;
            //print "'$string'=='$wos'";
        }

        return $retorno;
    }

    public static function validBoolean($strBoolean){
        return filter_var($strBoolean, FILTER_VALIDATE_BOOLEAN) || $strBoolean==0 || $strBoolean==1;
    }

    public static function validNumber($strNumber){
        return is_numeric($strNumber);
    }

    public static function validPositiveNumber($strNumber){
        return is_numeric($strNumber) && ($strNumber+0)>=0;
    }

    public static function validNegativeNumber($strNumber){
        return is_numeric($strNumber) && ($strNumber+0)<=0;
    }

    public static function validURL($strURL){
        return filter_var($strURL, FILTER_VALIDATE_URL);
    }

    public static function validIP($strIP){
        return true;
    }

    public static function validFileName($strFile){
        return self::validStringTrim($strFile) && strpos($strFile,"\\.") === false;
    }

    public static function validArray($array){
        return is_array($array);
    }

}
