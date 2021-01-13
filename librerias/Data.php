<?php

/*
 * Author: Reyes Yam
 * Copyright © 2018
 * Contacto: reyesyamm@gmail.com
 **/

class Data{
    
    var $requestContent = null;
    var $requestEstatus = false;
    var $contenido = null;
    var $metodo;
    var $msj = "";
    var $error = false;
    
    public function __construct($contenido = []) {
        $this->metodo = $contenido['request_data']['metodo'];
        $this->requestContent = $contenido['request_data'];
        $this->requestEstatus = $contenido['request_estatus'];
        $this->contenido = $contenido['body'];
        $this->msj = "";
        if(!$this->requestEstatus){
            $this->setErrorMessage('Parámetros incompletos');
        }
    }
    
    public function isGet(){
        return $this->metodo == "get";
    }
    
    public function isPost(){
        return $this->metodo == "post";
    }
    
    public function fromBody($key, $defaultValue = ""){
        return isset($this->contenido[$key]) ? $this->contenido[$key] : $defaultValue;
    }
    
    public function isParamsOk(){
        return $this->requestEstatus;
    }
    
    public function addToBody($key, $data){
        $this->contenido[$key] = $data;
    }
    
    public function existsMessage(){
        return $this->msj != null && $this->msj != "";
    }
    
    public function getMsj(){
        return $this->existsMessage() ? $this->msj : "";
    }
    
    public function setMsj($msj){
        $this->msj = $msj;
    }
    
    public function isError(){
        return $this->error;
    }
    
    public function setErrorMessage($msj){
        $this->setMsj($msj);
        $this->error = true;
    }
    
    
    public function setSuccessMessage($msj){
        $this->setMsj($msj);
        $this->error = false;
    }

    public function forKey($key, $default){
        return isset($this->requestContent[$key]) ? $this->requestContent[$key] : $default;
    }
    
    public function forJSON($extra = null){
        return [
            'estado' => !$this->error,
            'mensaje' => $this->msj,
            'error' => $this->error,
            'extra' => $extra
        ];
    }    
    
    
}
