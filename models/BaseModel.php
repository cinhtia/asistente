<?php 

/**
 * autor: Reyes Yam
 * email: reyesyamm@gmail.com
 */

class BaseModel{

	private $error = "";

	public function __construct(){

	}

	public function obtenerError(){
		return $this->error;
	}

	/**
	 * Guarda el registro en la tabla definida
	 * @param  [type] $dbTransaction [description]
	 * @return [type]                [description]
	 */
	public function guardarDB( $db, $tabla, $assocArray){
		$sqlInsert = DBHelper::sqlInsert($tabla,$assocArray);
		// print $sqlInsert;
		$sqlArgs = DBHelper::arrayValues($assocArray);
		$ret = $db->insert($sqlInsert, $sqlArgs);
		$this->error = $db->error_db;
		return $ret;
	}


	public function actualizarDB($db, $tabla, $assocArray, $whereArgs){
		$where = DBHelper::keyEqCharToString($whereArgs);
		$sqlUpdate = DBHelper::sqlUpdate($tabla, $assocArray, $where);
		$sqlArgs = DBHelper::arrayValues($assocArray);
		foreach ($whereArgs as $key => $value) {
			array_push($sqlArgs, $value);
		}
		$ret = $db->update($sqlUpdate, $sqlArgs);
		$this->error = $db->error_db;
		return $ret;
	}

	public function eliminarDB($db, $tabla, $args){
		$sqlDelete = DBHelper::sqlDelete($tabla, $args);
		$sqlArgs = DBHelper::arrayValues($args);
		$ret = $db->delete($sqlDelete, $sqlArgs);
		$this->error = $db->error_db;
		return $ret;
	}

	public static function contarTodosDB($db, $tabla, $whereAssoc = null){
		$sqlCount = DBHelper::sqlCount($tabla, $whereAssoc);
		// print $sqlCount;
		$sqlArgs = $whereAssoc != null ? DBHelper::arrayValues($whereAssoc) : array();
		// print_r($sqlArgs);
		$ret = $db->readScalar($sqlCount, $sqlArgs);
		return $ret;
	}

	public static function obtenerTodosDB($db, $tabla, $limit = -1, $offset = -1, $columnas = null, $whereAssoc = null, $orderBy = null){
		$sqlSelect = DBHelper::sqlSelect($tabla, $columnas, $whereAssoc, $orderBy, $limit, $offset);
		$sqlArgs = $whereAssoc != null ? DBHelper::arrayValues($whereAssoc) : array();
		$ret = $db->read($sqlSelect, $sqlArgs);
		//$this->error = $db->error_db;
		return $ret;
	}

	public static function obtenerPorIdDB($db, $tabla, $args, $columnas = null){
		$sqlSelect = DBHelper::sqlSelect($tabla, $columnas, $args, null, 1);
		$sqlArgs = DBHelper::arrayValues($args);
		$ret = $db->read($sqlSelect, $sqlArgs, true);
		//$this->error = $db->error_db;
		return $ret;
	}

	public static function obtenerUnoDB($db, $tabla, $columnas = null, $assocArray){
		$sqlSelect = DBHelper::sqlSelect($tabla, $columnas, $assocArray, null, 1);
		// print $sqlSelect;
		$sqlArgs = DBHelper::arrayValues($assocArray);
		// print_r($sqlArgs);
		$ret = $db->read($sqlSelect, $sqlArgs, true);
		//$this->error = $db->error_db;
		return $ret;
	}
}