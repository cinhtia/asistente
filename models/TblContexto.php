<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblContexto extends Model {

	public static $table = "TblContexto";
	public static $pk    = "id_contexto";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;
	
	public $id_contexto;
	public $descrip_contexto;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;
	
}