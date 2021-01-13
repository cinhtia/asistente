<?php
class TblCompetenciaGenericaAsignatura extends Model{
	public static $table = "TblCompetenciaGenericaAsignatura";
	public static $pk    = "id_competencia_generica_asignatura";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public static $relations = [
		'id_asignatura' => ['type' => 1 , 'model' => 'TblAsignatura', 'foreignField'=>'id_asignatura' ],
		'id_cg' => ['type' => 1 , 'model' => 'TblCG', 'foreignField'=>'id_cg' ],
		'id_usuario' => ['type' => 1 , 'model' => 'TblUsuario', 'foreignField'=>'id_usuario' ],
	];
	
	var $id_competencia_generica_asignatura;
	var $id_asignatura;
	var $id_cg;
	var $activo;
	var $unidades;
	var $id_usuario;
	var $fecha_creacion;
	var $fecha_actualizacion;

	public function getUnidades(){
		if($this->unidades){
			return explode(",", $this->unidades);
		}
		return [];
	}

}