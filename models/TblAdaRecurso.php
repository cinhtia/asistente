<?php 

class TblAdaRecurso extends Model{
	public static $table = "TblAdaRecurso";
	public static $pk    = ["id_ada","id_recurso"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_recurso' => [
			'type' => 1, 'model' => 'TblRecurso', 'foreignField' => 'id_recurso',
		]
	];

	var $id_ada;
	var $id_recurso;
}