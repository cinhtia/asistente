<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCG extends Model {

	public static $table = "TblCG";
	public static $pk    = "id_cg";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;
	
	public $id_cg;
	public $descripcion_cg;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;
	
	public static function CgsAsignatura($idA, $numUnidad){
		$sql = "select cg.*, t1.unidades from ".TblCG::$table." cg inner join ".TblMallaCurricular::$table." t1 on t1.id_cg=cg.id_cg where t1.id_asignatura=? and t1.tipo_competencia='asignatura';";
		$tmp = DBHelper::singleton()->read($sql, [$idA]);
		$ret = [];
		foreach ($tmp as $index => $item) {
			if(in_array($numUnidad, explode(",", $item['unidades']))){
				$ret[] = $item;
			}
		}
		return TblCG::parseList2ArrayObj($ret);
	}

}