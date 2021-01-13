<?php

class SecHash{

	function __construct(){

	}

	public static function generarHashContrasenaSegura($contrasena){
		return password_hash($contrasena,PASSWORD_DEFAULT); 
	}

	public static function verificarHashContrasena($contrasena,$hash){
		return password_verify($contrasena,$hash);
	}


	public static function generarContrasenaAleatoria($longitud){
		$diccionario = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $contrasena = array(); 
	    $longitudDict = strlen($diccionario) - 1; 

	    for ($i = 0; $i < $longitud; $i++) {
	        $n = rand(0, $longitudDict);
	        $contrasena[] = $diccionario[$n];
	    }
	    
	    return implode($contrasena);
	}


}

?>