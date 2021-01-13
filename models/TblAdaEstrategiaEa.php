<?php 

class TblAdaEstrategiaEa extends Model{
	public static $table = "TblAdaEstrategiaEa";
	public static $pk    = ["id_ada","id_estrategia_ea"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_estrategia_ea' => [
			'type' => 1, 'model' => 'TblEstrategiaEa', 'foreignField' => 'id_estrategia_ea'
		]
	];

	var $id_ada;
	var $id_estrategia_ea;
}