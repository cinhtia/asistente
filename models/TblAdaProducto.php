<?php 

class TblAdaProducto extends Model{
	public static $table = "TblAdaProducto";
	public static $pk    = ["id_ada","id_producto"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_producto' => ['type' => 1, 'model' => 'TblProducto', 'foreignField' => 'id_producto']
	];

	var $id_ada;
	var $id_producto;
}