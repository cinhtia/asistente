<?php
class TblAdaCompetenciaExtra extends Model{
	public static $table = "ada_competencia_extra";
	public static $pk    = "id";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id;
	var $id_ada;
	var $id_competencia;
	var $fecha_creacion;
	var $fecha_actualizacion;
}