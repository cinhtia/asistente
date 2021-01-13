<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblCompo_actitud_valor extends Model {

	public static $table = "TblCompo_actitud_valor";
	public static $pk    = "id_compo_valor";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_compo_valor;
	public $descrip_actitud_valor;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;
	public $tipo;

	public static function tipoFormal($val){
		return $val == 'a' ? 'Actitud' : ($val == 'v' ? 'Valor' : 'Actitud | Valor');
	}

}