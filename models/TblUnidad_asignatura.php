<?php

class TblUnidad_asignatura extends Model{

	public static $table = "TblUnidad_asignatura";
	public static $pk    = "id_unidad_asignatura";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public static $relations = [ 
		'id_asignatura' 	=> 	[ 'type'=>1, 'model'=>'TblAsignatura','foreignField'=>'id_asignatura']
	];


	var $id_unidad_asignatura;
	var $id_asignatura;    
	var $num_unidad;          
	var $duracion_unidad_hp;  
	var $duracion_unidad_hnp; 
	var $nombre_unidad;       
	var $id_usuario;          
	var $fecha_creacion;      
	var $fecha_actualizacion; 

}