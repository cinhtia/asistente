<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

require_once 'BaseModel.php';

class TblMallaCurricular extends Model {

	public static $table = "TblMallaCurricular";
	public static $pk    = 'id_malla_curricular';
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public static $relations = [
		'id_cg' => ['type' => 1, 'model' => 'TblCG', 'foreignField' => 'id_cg' ],
	];
	
	public $id_malla_curricular;
	public $id_asignatura;
	public $id_cg;
	public $tipo_competencia = "asignatura";
	public $unidades;
	public $id_usuario;         
	public $id_competencia;     
	public $fecha_creacion;     
	public $fecha_actualizacion;

	
	public static function obtenerCompetenciasGenericas($idAsignatura){
		$sql = "select cg.descripcion_cg, mc.* from ".TblCG::$table." cg inner join ".self::$table." mc on mc.id_cg = cg.id_cg where mc.id_asignatura=?;";
		$lista = DBHelper::singleton()->read($sql, [$idAsignatura]);
		return TblMallaCurricular::parseList2ArrayObj($lista);
	}

	public static function obtenerDeCompetencia($idCompetencia){
		$sql = "select cg.id_cg from ".TblCG::$table." cg inner join ".self::$table." mc on mc.id_cg = cg.id_cg where mc.id_competencia=?;";
		$rawRes = DBHelper::singleton()->read($sql, [$idCompetencia], false, false);
		return $rawRes;
	}

}