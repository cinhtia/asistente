<?php

class TblCompetenciaDisciplinar extends Model{

	public static $table = "TblCompetenciaDisciplinar";
	public static $pk    = "id_competencia_disciplinar";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_competencia_disciplinar;
	var $descripcion;
	var $id_usuario;
	var $plan_estudio_id;
	var $fecha_creacion;
	var $fecha_actualizacion;

}