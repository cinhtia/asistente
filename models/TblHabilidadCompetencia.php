<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblHabilidadCompetencia extends Model {

	public static $table = "TblHabilidadCompetencia";
	public static $pk    = ["id_competencia","id_compo_habilidad"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_compo_habilidad' => ['type' => 1, 'model' => 'TblCompo_habilidad', 'foreignField' => 'id_compo_habilidad' ]
	];
	
	public $id_competencia;
	public $id_compo_habilidad;

}