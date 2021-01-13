<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblUsuario extends Model {

	public static $table = "TblUsuario";
	public static $pk    = "id_usuario";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_usuario = 0;         
	var $nombre = "";             
	var $username = "";           
	var $contrasena = "";         
	var $tipo_usuario = "";       
	var $fecha_creacion = "";     
	var $fecha_actualizacion = "";
	var $id_permiso = 0;
}