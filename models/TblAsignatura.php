<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblAsignatura extends Model {

	public static $table = "TblAsignatura";
	public static $pk    = "id_asignatura";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;
	
	public $id_asignatura;
	public $nombre_asignatura;
	public $tipo_asignatura;
	public $modalidad;
	public $num_unidades;
	public $semestre_ubicacion;
	public $horas_duracion;
	public $horas_presenciales;
	public $horas_nopresenciales;
	public $creditos;
	public $id_usuario;
	public $contextualizacion;
	public $fecha_creacion;
	public $fecha_actualizacion;

	public function obtenerModalidad(){
		if($this->modalidad == 'presencial'){
			return 'Presencial';
		}else if($this->modalidad == 'en_linea'){
			return 'En linea';
		}else if($this->modalidad == 'mixta'){
			return 'Mixta';
		}
		return $this->modalidad;
	}

	public function obtenerTipoAsignatura(){
		return ucfirst($this->tipo_asignatura);
	}

	public function getPE2Formato(){
		$query = "select pe.id_pe, pe.nombre_pe from ".TblPlanEstudio::$table." pe join ".TblAsignaturaPe::$table." ape on pe.id_pe = ape.id_pe where ape.id_asignatura = ? order by pe.nombre_pe asc";
		$resultado = DBHelper::singleton()->read($query, [$this->id_asignatura]);
		$solo_nombres = [];
		foreach ($resultado as $index => $row) {
			$solo_nombres[] = $row['nombre_pe'];
		}
		return $solo_nombres;
	}

}