<?php

class TblPermiso extends Model {
	public static $table = "TblPermiso";
	public static $pk    = 'id_permiso';
	public static $className = __CLASS__;
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";

	var $id_permiso;         
	var $nombre_permiso;     
	var $fecha_creacion;     
	var $fecha_actualizacion;
}