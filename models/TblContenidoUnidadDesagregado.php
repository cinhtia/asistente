<?php

class TblContenidoUnidadDesagregado extends Model{
	public static $table = "Tbl_contenido_unidad_desagregado";
	public static $pk = ['id_contenido_unidad_asignatura','id_desagregado_contenido'];
	public static $className = __CLASS__;

	public static $relations = [
		'id_desagregado_contenido' => [
			'type' => 1,
			'model' => 'TblDesagregadoContenido',
			'foreignField' => 'id_desagregado_contenido',
		]
	];
	
	var $id_contenido_unidad_asignatura;
	var $id_desagregado_contenido;
}