<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCompo_habilidad extends Model {

	public static $table = "TblCompo_habilidad";
	public static $pk    = "id_compo_habilidad";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_compo_habilidad;
	public $descrip_habilidad;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;

}