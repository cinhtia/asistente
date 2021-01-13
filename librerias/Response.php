<?php

require_once 'Helpers.php';

class Response{



	public function __construct($esJSON = true){
		if($esJSON){
			self::defineGlobalHeader();	
		}
	}

	public function successResp($extra = ''){
		$this->json_r(true,'','',$extra);
	}

	public function errorResp($errorMsj='', $errorMsj2=''){
		$this->json_r(false,$errorMsj,$errorMsj2);
	}

	public function json_r($estado=false,$error='',$mensaje='',$extra=''){
		$this->json(self::CreateJSONResponse($estado,$error,$mensaje,$extra));
	}

	public function json($data, $redefineHeader = true){
		if($redefineHeader){
			self::JSONResponse($data);
		}else{
			print json_encode($data);
		}
		die();
	}

	public function html($data){
		self::HTMLResponse($data);
	}

	public function array_response($estado = false , $error = '', $mensaje = '', $extra = ''){
		return self::CreateJSONResponse($estado,$error,$mensaje,$extra);
	}

	public static function defineGlobalHeader(){
		header('Content-Type: application/json; charset=utf-8');
	}


	public static function JSONResponse($response){
		header('Content-Type: application/json; charset=utf-8');
		print json_encode($response);
	}

	public static function HTMLResponse($response){
		header('Content-Type: text/html; charset=utf-8');
		print $response;
	}

	public static function CreateJSONResponse($estado = false, $error = '', $log = '', $extra = ''){
		return array('estado'=>$estado,'error'=>$error, 'log'=>$log, 'extra'=>$extra);
	}


	public function json_error_request(){
		self::JSONResponse(Helpers::$error_request);
	}

	public function json_error_parameters(){
		self::JSONResponse(Helpers::$error_parameters);
	}

	public function json_error_invalida_parameters($msj = null){
		$err = Helpers::$error_invalid_parameters;
		if($msj!=null){
			$err['error'].=". $msj";
			$err['mensaje'].=". $msj";
		}
		self::JSONResponse($err);
	}
}
