<?php

class TblContenidoUnidadEstrategiaEa extends Model{

	public static $table = "Tbl_contenido_unidad_estrategia_ea";
	public static $pk = ["id_contenido_unidad_asignatura", "id_estrategia_ea"];
	public static $className = __CLASS__;

	var $id_contenido_unidad_asignatura;
	var $id_estrategia_ea;

}