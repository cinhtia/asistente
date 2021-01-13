<?php

class TblRecurso extends Model{
	public static $table = "TblRecurso";
	public static $pk    = "id_recurso";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_recurso;         
	var $nombre;             
	var $descripcion;        
	var $id_usuario;         
	var $fecha_creacion;     
	var $fecha_actualizacion;

}