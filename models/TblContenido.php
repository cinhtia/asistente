<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblContenido extends Model {

	public static $table = "TblContenido";
	public static $pk    = "id_contenido";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_contenido;
	public $descrip_contenido;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;

}