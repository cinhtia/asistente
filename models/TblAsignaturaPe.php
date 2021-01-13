<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblAsignaturaPe extends Model {

	public static $table = "TblAsignatura_pe";
	public static $pk    = "id_asignatura_pe";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;
	
	public $id_asignatura_pe;
	public $id_asignatura;
	public $id_pe;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;
	
}