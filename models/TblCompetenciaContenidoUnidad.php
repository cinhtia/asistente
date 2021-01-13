<?php
/**
 * Relacion entre TblCompetencia y sus contenidos de unidad a evaluar
 */

class TblCompetenciaContenidoUnidad extends Model{
	public static $table = "competencia_contenido_unidad";
	public static $pk    = ["id_competencia","id_contenido_unidad"];
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public static $relations = [ 
		'id_contenido_unidad' => [
			'type'=>1, 
			'model'=>'TblContenidoUnidadAsignatura',
			'foreignField'=>'id_contenido_unidad_asignatura'
		],
		'id_competencia' => [
			'type' => 1,
			'model' => 'TblCompetencia',
			'foreignField' => 'id_competencia',
		]
	];

	var $id_competencia;
	var $id_contenido_unidad;
	var $fecha_creacion;
	var $fecha_actualizacion;
}