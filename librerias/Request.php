<?php
require_once 'Response.php';
require_once 'Validator.php';

class Request{

	public $api_key_client;
	public $api_key_server;
	public $content = null;

	public static $instance = null;

	public static function instance(){
		return self::singleton();
	}

	public static function singleton(){
		if(self::$instance == null){
			self::$instance = new Request();
		}
		return self::$instance;
	}

	public function _construct(){
		$headers = getallheaders();
		$this->api_key_client = isset($headers['apikey'])?$headers['apikey']:"";
		$this->api_key_server = isset($headers['serverapikey'])?$headers['serverapikey']:"";
	}

	public function getApiKey($client = true){
		return $client?$this->api_key_client:$this->api_key_server;
	}

	public function isMethodGet(){
		return $_SERVER['REQUEST_METHOD']=='GET';
	}

	public function isMethodPost(){
		return $_SERVER['REQUEST_METHOD']=='POST';
	}

	public function isMethod($method){
		return strtolower($_SERVER['REQUEST_METHOD'])==strtolower($method);
	}

	public function existsGet($key){
		return isset($_GET[$key]);
	}

	public function get($key, $defaultValue = null){
		$ret = filter_input(INPUT_GET, $key,FILTER_SANITIZE_SPECIAL_CHARS);
		if($ret != null) return $ret;
		return $defaultValue;
	}

	public function getArray($key){
		return isset($_GET[$key]) ? $_GET[$key] : [];
	}

	public function getIfExists($key, $default){
		if($this->existsGet($key)){
			return $this->get($key);
		}else{
			return $default;
		}
	}

	public function existsPost($key){
		return isset($_POST[$key]);
	}

	public function postIfExists($key, $default){
		if($this->existsPost($key)){
			return $this->post($key);
		}else{
			return $default;
		}
	}

	public function postArray($key){
		return isset($_POST[$key]) ? $_POST[$key] : [];
	}

	public function post($key, $defaultValue = null){
		$ret = filter_input(INPUT_POST, $key,FILTER_SANITIZE_SPECIAL_CHARS);
		if($ret != null) return $ret;
		return $defaultValue;
	}

	public function findVarBy($method, $key){
		if($method=='get'){
			return $this->get($key);
		}else {
			return $this->post($key);
		}
	}

	public function existsByMethod($method,$key){
		if($method=='get'){
			return $this->existsGet($key);
		}else{
			return $this->existsPost($key);
		}
	}


	public function getContent(){
		return file_get_contents("php://input");
	}

	public function getContentArray(){
		if($this->content == null)
			$this->content = json_decode($this->getContent(),true);
		return $this->content;
	}

	public function existsInContent($key, $acceptEmpty = false, $isArray = false){
		$content = $this->getContentArray();
		if($acceptEmpty){
			return array_key_exists($key, $content) && ($isArray ? is_array($content[$key]) : true);
		}else{
			return isset($content[$key]) && ($isArray ? is_array($content[$key]) : true);
		}
	}

	public function getOnContent($key){
		if($this->content == null){
			throw new Error('Debes inicializar content: llama a ::getContentArray() primero');
		}else{
			return $this->content[$key];
		}
	}

	public static function existsWithVal($key, $array){
		return isset($array[$key]);
	}

	public static function exists($key, $array){
		return array_key_exists($key, $array);
	}

	public static function CREATE_GET($handler, $parameters = null, $validatorArray = null){
		self::create('get', $parameters!=null?$parameters:array(), $handler, false, $validatorArray);
	}

	public static function CREATE_POST($handler, $parameters = null, $validatorArray = null, $esJSON = true){
		self::create('post',$parameters!=null?$parameters:array(),$handler,$esJSON,$validatorArray);
	}

	public static function create($method='get', $parameters = array(), $function, $json=true, $validator_array = null){
		$request = new Request();
		$response = new Response();

		if($request->isMethod($method)){
			$allFound = true;
			$body = array();
			if(count($parameters)>0 && $json){
				//$body = $request->getContentArray();
				$body2 = json_decode(file_get_contents("php://input"),true);
				
				foreach ($parameters as $i => $key) {
					if(Request::existsWithVal($key,$body2)){
						$body[$key]=$body2[$key];
					}else{
						//print "falta ".$key;
						$allFound = false;
						break;
					}
				}
			}else if(count($parameters)>0){
				$lowerMethod = strtolower($method);
				foreach ($parameters as $i => $key) {
					if($request->existsByMethod($lowerMethod, $key)){
						$body[$key]=$request->findVarBy($lowerMethod,$key);
					}else{
						$allFound = false;
						break;
					}
				}
			}

			if($allFound && count($body)>=0){

				if($validator_array!=null){
					if(Validator::valid($validator_array,$body)){
						$function($request,$response,$body);
					}else{
						$response->json_error_invalida_parameters(Validator::$errores);
					}
				}else{
					$function($request,$response,$body);
				}
			}else{
				$response->json_error_parameters();
			}
		}else{
			$response->json_error_request();
		}
	}

	// version 3
	public static function dispatchAction($controller, $actionName, $data, $method, $resultFunction){
		$request = new Request();
		$response = new Response(false); //false -> no es json
		$allowedMethods = ['get','post'];

		if(isset($data['parametros'])){
			$multi = false;
			foreach ($allowedMethods as $met) {
				if(isset($data['parametros'][$met])){
					$multi = true;
					break;
				}
			}

			if($multi){
				if(isset($data['parametros'][$method])){
					$parameters = $data['parametros'][$method];
				}else{
					$parameters = array();
				}
			}else{
				$parameters = $data['parametros'];
			}
		}else{
			$parameters = array();
		}


		$finalArgs = array();

		$finalArgs['request_data'] = $data;
		$finalArgs['request_estatus'] = false;

		$body = array();
                $finalArgs['body'] = [];
		if($request->isMethod($method)){
			
			$allFound = true;

			if($parameters != null && count($parameters)>0){
				
				foreach ($parameters as $i => $key) {
					if($request->existsByMethod($method, $key)){
						$body[$key]=$request->findVarBy($method,$key);
					}else{
						// array_push($data_not_found, $key);
						$allFound = false;
						break;
					}
				}
			}

			$finalArgs['request_estatus'] = $allFound && count($body)>=0;
			$finalArgs['body'] = $body;
			// print_r($body);
		}
                
                $finalData = new Data($finalArgs);

		$resultFunction($request, $response, $finalData, $controller, $actionName);
	}

	// version 2
	public static function new_request_service(Controller $controller, $data, $resultFunction){
		$request = new Request();
		$esJSON = isset($data['json']) && $data['json'];
		$response = new Response($esJSON); //false -> no es json
		$parameters = $data['params'];
		$finalArgs = array();
		
		$finalArgs['request_data'] = $data;
		$finalArgs['request_estatus'] = false;

		$body = array();
                $finalArgs['body'] = [];

		if($request->isMethod($data['metodo'])){
			
			$allFound = true;

			if($parameters != null && count($parameters)>0){
				
				foreach ($parameters as $i => $key) {
					if($request->existsByMethod($data['metodo'], $key)){
						$body[$key]=$request->findVarBy($data['metodo'],$key);
					}else{
						// array_push($data_not_found, $key);
						$allFound = false;
						break;
					}
				}
			}

			$finalArgs['request_estatus'] = $allFound && count($body)>=0;
			$finalArgs['body'] = $body;
			$finalArgs['error'] = true;
			$finalArgs['mensaje'] = "";
			// print_r($body);
		}
                
                $finalData = new Data($finalArgs);

		$resultFunction($request,$response,$finalData, $controller, $data['controller']);
	}

}
