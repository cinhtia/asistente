<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblVerboCompetencia extends Model {

	public static $table = "TblVerboCompetencia";
	public static $pk    = ["id_verbo","id_competencia"];
	public static $className = __CLASS__;

	public $id_verbo;
	public $id_competencia;

}