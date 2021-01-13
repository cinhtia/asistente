<?php

class TblPermisoModulo extends Model{
	public static $table = "TblPermisoModulo";
	public static $pk = ['id_permiso','id_modulo'];
	public static $className = __CLASS__;

	var $id_permiso;
	var $id_modulo;
	var $leer;
	var $escribir;
	var $eliminar;
}