<?php

class TblContenidoUnidadAsignatura extends Model{

	public static $table = "TblContenido_unidad_asignatura";
	public static $pk = "id_contenido_unidad_asignatura";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;


	var $id_contenido_unidad_asignatura;
	var $id_unidad_asignatura;          
	var $detalle_secuencia_contenido;
	var $duracion_hp;                   
	var $duracion_hnp;                  
	var $id_usuario;                    
	var $fecha_creacion;                
	var $fecha_actualizacion;           


}