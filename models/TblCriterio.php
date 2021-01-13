<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCriterio extends Model {

	public static $table = "TblCriterio";
	public static $pk    = "id_criterio";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_criterio;
	public $descrip_criterio;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;

}