<?php

class TblAdaCg extends Model{
	public static $table = "ada_cg";
	public static $pk    = ["ada_id","cg_id"];
	public static $createdAt = "fecha_creacion";
	public static $className = __CLASS__;

	public static $relations = [
		'ada_id' => ['type' => 1, 'model' => 'TblAda', 'foreignField' => 'id_ada'],
		'cg_id' => ['type' => 1, 'model' => 'TblCG', 'foreignField' => 'id_cg']
	];

	var $ada_id;
	var $cg_id;
	var $fecha_creacion;

}