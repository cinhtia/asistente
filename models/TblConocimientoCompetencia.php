<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblConocimientoCompetencia extends Model {

	public static $table = "TblConocimientoCompetencia";
	public static $pk    = ["id_competencia","id_compo_conocimiento"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_compo_conocimiento' => ['type' => 1, 'model' => 'TblCompo_conocimiento', 'foreignField' => 'id_compo_conocimiento' ],
	];

	public $id_competencia;
	public $id_compo_conocimiento;
	
}