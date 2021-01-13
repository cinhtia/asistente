<?php

class TblInstrumentoEval extends Model{
	public static $table = "TblInstrumento_eval";
	public static $pk    = "id_instrumento_eval";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_instrumento_eval;     
	var $descripcion_instrum_eval;    
	var $explicacion_instrum_eval;
	var $id_usuario;              
	var $fecha_creacion;          
	var $fecha_actualizacion;     
}