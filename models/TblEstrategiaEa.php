<?php 

class TblEstrategiaEa extends Model{

	public static $table = "TblEstrategia_ea";
	public static $pk = "id_estrategia_ea";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_estrategia_ea;
	var $descripcion_ea;
	var $explicacion_ea;
	var $id_usuario;
	var $fecha_creacion;
	var $fecha_actualizacion;
	
}