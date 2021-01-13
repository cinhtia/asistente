<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

require_once 'BaseModel.php';

class TblActitudValorCompetencia extends Model {

	public static $table = "TblActitudValorCompetencia";
	public static $pk    = ['id_competencia','id_compo_actitud_valor'];
	public static $className = __CLASS__;

	public static $relations = [
		'id_compo_actitud_valor' => [
			'type' => 1,
			'model' => 'TblCompo_actitud_valor',
			'foreignField' => 'id_compo_valor',
		]
	];

	public $id_competencia;
	public $id_compo_actitud_valor;
	
}