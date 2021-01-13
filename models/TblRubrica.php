<?php 

class TblRubrica extends Model{
	public static $table = "TblRubrica";
	public static $pk    = "id_rubrica";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_rubrica;         
	var $descripcion_rubrica;
	var $explicacion_rubrica;
	var $id_usuario;         
	var $fecha_creacion;     
	var $fecha_actualizacion;
}