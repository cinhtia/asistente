<?php 

class TblCompetenciaDisciplinarAsignatura extends Model{
	public static $table = "TblCompetenciaDisciplinarAsignatura";
	public static $pk    = ["id_competencia_disciplinar","id_asignatura"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_competencia_disciplinar' => [
			'type' => 1, 'model' => 'TblCompetenciaDisciplinar', 'foreignField' => 'id_competencia_disciplinar'
		],
		'id_asignatura' => [
			'type' => 1, 'model' => 'TblAsignatura', 'foreignField' => 'id_asignatura',
		]
	];

	var $id_competencia_disciplinar;
	var $id_asignatura;
}