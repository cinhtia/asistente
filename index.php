<?php

session_start();

require_once 'constantes_configuracion.php';

// requerimos las librerias
require_once 'librerias/config.php';
require_once 'Sesion.php';

// requerimos el Enrutador
require_once 'Router.php';

// Requrerimos los modelos
require_once 'models/config.php';

// requerimos los controladores
require_once 'controllers/config.php';

// funciones extras
function ruta($name, $params = []){ 
	$data =  Router::obtenerData($name);
	$ruta = BASE_URL_WEB.$data['ruta'];

	if($params != null && count($params) > 0){
		$querys = [];
		foreach ($params as $key => $value) {
			if($value != null){
				$querys[] = "$key=".urlencode($value);
			}
		}
		$ruta.="?".(implode("&", $querys));
	}

	return $ruta;
}

function img($name){
	return (BASE_URL_WEB."assets/img/".$name);
}

function css($filename){
	return (BASE_URL_WEB."assets/css/".$filename);
}

// creamos el proceso de enrutar
$router = new Router();

$REQUEST_URI = $_SERVER['REQUEST_URI'];
$nombre = $router->encontrarRuta($REQUEST_URI);

$sesion = new Sesion();
// $router->iniciarAccion($nombre);

if($sesion->existe()){
	if($nombre == 'login'){
		header('Location: '.BASE_URL_WEB.'index');
	}else{
		$router->iniciarAccion($nombre);
	}
}else{

	if(in_array($nombre, Router::RUTAS_LIBRES)){
		$router->iniciarAccion($nombre);
	}else{
		header('Location: '.BASE_URL_WEB.'login?redirect='.$nombre);			
	}
}