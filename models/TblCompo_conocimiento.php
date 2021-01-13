<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCompo_conocimiento extends Model {

	public static $table = "TblCompo_conocimiento";
	public static $pk    = "id_compo_conocimiento";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_compo_conocimiento;
	public $descrip_conocimiento;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;
	
}