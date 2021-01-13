<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblContextoCompetencia extends Model {

	public static $table = "TblContextoCompetencia";
	public static $pk    = ["id_contexto","id_competencia"];
	public static $className = __CLASS__;

	public $id_contexto;
	public $id_competencia;
	
}