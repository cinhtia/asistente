<?php

class TblUsuarioAsignatura extends Model{
	public static $table = "TblUsuarioAsignatura";
	public static $pk    = ['id_usuario','id_asignatura'];
	public static $className = __CLASS__;

	public static $relations = [
		'id_usuario' => [ 'type'=>1, 'model'=>'TblUsuario','foreignField'=>'id_usuario'],
		'id_asignatura' => [ 'type'=>1, 'model'=>'TblAsignatura','foreignField'=>'id_asignatura'],
	];

	var $id_usuario;
	var $id_asignatura;
}