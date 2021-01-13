<?php
/**
 * @author Reyes Yam <reyesyamm@gmail.com>
 * @copyright (c) 2018, Reyes Yam
 * @version 1.0
 * */

class TblContenidoCompetencia extends Model {

	public static $table = "TblContenidoCompetencia";
	public static $pk    = ["id_contenido","id_competencia"];
	public static $className = __CLASS__;

	public static $relations = [
		'id_competencia' => [
			'type' => 1, 'model' => 'TblCompetencia', 'foreignField' => 'id_competencia',
		],
		'id_contenido' => [
			'type' => 1, 'model' => 'TblContenido', 'foreignField' => 'id_contenido'
		]
	];

	public $id_contenido;
	public $id_competencia;

}