<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblVerbo extends Model {

	public static $table = "TblVerbo";
	public static $pk    = "id_verbo";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_verbo;
	public $descrip_verbo;
	public $tipo_saber_verbo;
	public $disponible;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;

}