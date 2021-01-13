<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblPlanEstudio extends Model {

	public static $table = "TblPlanEstudio";
	public static $pk    = "id_pe";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	public $id_pe;
	public $nombre_pe;
	public $facultad;
	public $id_usuario;
	public $fecha_creacion;
	public $fecha_actualizacion;

	public function obtenerAsignaturas($likeObj = true){
		$sql = "select ape.id_asignatura_pe, ape.id_pe, a.num_unidades, a.id_asignatura, a.nombre_asignatura, a.tipo_asignatura, a.modalidad from ".TblAsignatura::$table." a inner join ".TblAsignaturaPe::$table." ape on a.id_asignatura = ape.id_asignatura where ape.id_pe = ?;";
		$db = new DBHelper();

		$lista = $db->read($sql, [$this->id_pe]);
		if(!$likeObj)
			return $lista;
		$objetos = TblAsignaturaPe::parseList2ArrayObj($lista);
		return $objetos;
	}


}