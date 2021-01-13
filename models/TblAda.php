<?php

/**
 * Modelo de actividad de aprendizaje
 */
class TblAda extends Model{
	public static $table = "TblAda";
	public static $pk    = "id_ada";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;	

	public static $relations = [
		'id_herramienta' => [ 'type'=>1, 'model'=>'TblHerramienta','foreignField'=>'id_herramienta'],
		'id_asignatura' => ['type' =>1, 'model' => 'TblAsignatura', 'foreignField' => 'id_asignatura'],
	];

	var $id_ada;
	var $id_asignatura;
	var $id_unidad;
	var $num_unidad;
	var $resultado_competencia_ada;
	var $id_herramienta;
	var $nombre_ada;
	var $instruccion_ada;
	var $modalidad_ada;
	var $max_integrantes_equipo;
	var $procedimiento_ada;
	var $duracion_horas;
	var $referencias_ada;
	var $fecha_ini_ada;
	var $fecha_fin_ada;
	var $id_instrumento_eval;
	var $momento_eval;
	var $agentes_eval;
	// var $id_estrategia_ea;
	var $ponderacion;
	
	var $otra_herramienta;
	var $id_rubrica;
	var $id_plantilla_rubrica;
	var $otro_producto;
	var $otro_recurso;
	var $otro_estrategia_ea;

	var $id_usuario;
	var $fecha_creacion;
	var $fecha_actualizacion;

}