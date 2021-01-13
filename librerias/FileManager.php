<?php
require_once 'Environment.php';
require_once 'Validator.php';

class FileManager{
    const UPLOAD_DIR = UPLOADS_DIR;//"uploads/";
    public static $error = "";
    public static $last_file;

    public static function upload($key, $filename = null){
        $file = isset($_FILES[$key]['name'])?$_FILES[$key]['name']:null;
        $retorno = false;
        if($file!=null){
            $filename = ($filename==null)?trim($_FILES[$key]['name']):trim($filename);
            if(Validator::validFileName($filename)){
                $filePath = self::UPLOAD_DIR.$filename;
                // print $filePath;
                try {
                    $retorno = move_uploaded_file($_FILES[$key]['tmp_name'], $filePath);
                    self::$last_file = $filename;
                    self::$error = "sin error";
                } catch (Exception $e) {
                    $retorno  = false;
                    self::$error = $e->getMessage();
                }

            }else{
                self::$error = "nombre de archivo no valido ".$filename;
            }
        }else{
            self::$error = "file es null";
        }
        return $retorno;
    }

    public static function exists($filename){
        return Validator::validFileName($filename) && file_exists(self::UPLOAD_DIR.$filename);
    }

    public static function delete($filename){
        try{
            if(self::exists($filename)){
                Validator::validFileName($filename) && unlink(self::UPLOAD_DIR.$filename);
            }            
        }catch(Exception $ex){
            self::$error = $ex->getMessage();
        }

        return self::exists($filename);
    }

    public static function getURL($filename){
        return DEV_URL_ROOT.self::UPLOAD_DIR.$filename;
    }

}
