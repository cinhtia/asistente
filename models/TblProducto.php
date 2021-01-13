<?php

class TblProducto extends Model{
	public static $table = "TblProducto";
	public static $pk    = "id_producto";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_producto;        
	var $nombre;             
	var $descripcion;        
	var $id_usuario;         
	var $fecha_creacion;     
	var $fecha_actualizacion;
	
}