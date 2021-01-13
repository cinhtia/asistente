<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCriterioCompetencia extends Model {

	public static $table = "TblCriterioCompetencia";
	public static $pk    = ["id_criterio","id_competencia"];
	public static $className = __CLASS__;

	public $id_criterio;
	public $id_competencia;

}