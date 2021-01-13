<?php

class TblDesagregadoContenido extends Model{
	public static $table = 'Tbl_desagregado_contenido';
	public static $pk = 'id_desagregado_contenido';
	public static $createdAt = 'fecha_creacion';
	public static $updatedAt = 'fecha_actualizacion';
	public static $className = __CLASS__;

	var $id_desagregado_contenido;
	var $descripcion;
	var $id_usuario;
	var $fecha_creacion;
	var $fecha_actualizacion;
}