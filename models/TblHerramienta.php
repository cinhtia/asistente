<?php 

class TblHerramienta extends Model{
	public static $table = "TblHerramienta";
	public static $pk    = "id_herramienta";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_herramienta;
	var $descripcion_herramienta;
	var $explicacion_herramienta;
	var $estrategias_didacticas;
	var $palabras_asociadas;
	var $id_usuario;
	var $fecha_creacion;
	var $fecha_actualizacion;
}