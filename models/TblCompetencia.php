<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */


class TblCompetencia extends Model {

	public static $table = "TblCompetencia";
	public static $pk    = "id_competencia";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public static $relations = [ 
		'id_asignatura' => 	[ 'type'=>1, 'model'=>'TblAsignatura','foreignField'=>'id_asignatura'],
	];
	
	public $id_competencia;
	public $id_usuario;
	public $id_asignatura;
	public $competencia_editable;
	public $descripcion;
	public $etapa_actual;
	public $fecha_creacion;
	public $fecha_actualizacion;
	public $tipo_competencia;
	public $id_competencia_padre;
	public $num_unidad;
	public $num_resultado;
	public $finalizado;


}