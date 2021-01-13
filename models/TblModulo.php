<?php

/**
 * 
 */
class TblModulo extends Model {
	
	public static $table = "TblModulo";
	public static $pk    = 'id_modulo';
	public static $className = __CLASS__;
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";

	var $id_modulo;         
	var $nombre;     
	var $descripcion;     
	var $fecha_creacion;     
	var $fecha_actualizacion;
}