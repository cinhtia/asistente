<?php
/**
 * Clase base para el ORM
 */
class Model implements JsonSerializable{

	public static $error_model = "";

	public function __construct($arguments = null){
		if(!isset(static::$table) && static::$table != null){
			throw new Error("Table name for ".get_class()." model not defined!!");
		}

		if(!self::pkExists() && static::$table != null){
			throw new Error("Primary key for ".get_class()." model not defined!!");
		}

		if($arguments != null){
			foreach ($arguments as $key => $value) {
				$this->$key = $value;
			}
		}

	}

	public static function pkExists($data = null){
		if(isset(static::$pk)){
			$pkTmp = static::$pk;
			if(is_array($pkTmp) && count($pkTmp) == 0){
				return false;
			}

			if($data != null){
				if(is_array($pkTmp)){
					foreach ($pkTmp as $key ) {
						if(!isset($data[$key])){
							return false;
						}
					}
				}else{
					return isset($data[$pkTmp]);
				}
			}

			return true;
		}
		return false;
	}

	public function set($arguments){
		if($arguments != null){
			foreach ($arguments as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	public function getError(){
		return self::$error_model;
	}


	public function save($dbt = null){
		$data = get_object_vars($this);
		unset($data['error']);
		if(count($data) == 0){
			return false;
		}
		$id =self::saveItem($data, $dbt, false);
		if($id){
			$idK  = static::$pk;
			if(!is_array($idK)){
				$this->$idK = $id;
			}
			return true;
		}

		return false;
	}

	public function update($data = null, $dbt = null, $require_id_update = false){
		$idK  = static::$pk;
		$dt = get_object_vars($this);
		if($data != null){
			if(!is_array($idK)){
				$data[$idK] = $dt[$idK];
			}else{
				foreach ($idK as $idKItem) {
					$data[$idKItem] = $dt[$idKItem];
				}
			}
			$this->set($data);
		}else{
			$data = $dt;
		}

		if(isset($data['error'])){
			unset($data['error']);
		}
		if(count($data) == 0){
			return false;
		}

		return self::updateItem($data, null, $dbt, $require_id_update);
	}

	public function delete($dbt = null){
		$idK  = static::$pk;
		if(is_array($idK)){
			$argsIds = [];
			foreach ($idK as $itemId) {
				if(isset($this->$itemId)){
					$argsIds[$itemId] = $this->$itemId;
				}else{
					return false;
				}
			}
			return self::deleteById($argsIds, $dbt);
		}else{
			if(isset($this->$idK)){
				$idThis = $this->$idK;
				return self::deleteById($idThis, $dbt);
			}else{
				return false;
			}
		}
	}

	public static function getIds($keys, $data){
		$arr = [];
		foreach ($keys as $key) {
			if(isset($data[$key])){
				$arr[$key] = $data[$key];
			}
		}

		return $arr;
	}

	public static function saveItem($data, $dbt = null, $returnObject = true){
		$currentDate = Helper::currentDate(true);
		if(isset(static::$createdAt)){
			$data[static::$createdAt] = $currentDate;
		}

		if(isset(static::$updatedAt)){
			$data[static::$updatedAt] = $currentDate;
		}
		$pkVal = null;
		if(!is_array(static::$pk)){
			if(isset($data[static::$pk]) && ($data[static::$pk] == 0 || $data[static::$pk] == null)){
				$data[static::$pk] = 'DEFAULT';
			}else{
				$pkVal = $data[static::$pk];
			}
		}

		$sqlInsert = DBHelper::sqlInsert(static::$table, $data);
		// print $sqlInsert;
		$sqlArgs = DBHelper::arrayValues($data);
		// print_r($sqlArgs);
		$db = $dbt != null ? $dbt : new DBHelper();
		$res = $db->insert($sqlInsert, $sqlArgs);
		if($res){
			if($pkVal != null){
				$lastId = $pkVal;
			}else{
				$lastId = is_array(static::$pk) ? self::getIds(static::$pk, $data) : $db->getId();
			}

			if($returnObject){
				return self::parse2Obj($data);
				if(!is_array(static::$pk)){
					$data[static::$pk] = $lastId;
				}
			}else{
				return $lastId;
			}
		}else{
			self::$error_model = $db->error_db;
			return false;
		}
	}

	public static function updateItem($data, $whereArgs = null, $dbt = null, $require_id_update = false){
		if($whereArgs == null && !self::pkExists($data)){
			throw new Error("No where statement found and Primary key not defined on data to update");
		}

		if($whereArgs == null){
			$whereArgs = [];
			$pkTmp = static::$pk;
			if(is_array($pkTmp)){
				foreach ($pkTmp as $key) {
					$whereArgs[$key] = $data[$key];
				}
			}else{
				$whereArgs[$pkTmp] = $data[$pkTmp];
			}
		}

		if(isset(static::$createdAt) ){
			if(isset($data[static::$createdAt])){
				unset($data[static::$createdAt]);
			}
		}

		if(isset(static::$updatedAt)){
			$data[static::$updatedAt] = Helper::currentDate(true);
		}

		if(!$require_id_update){
			$tmp = static::$pk;
			if(is_array($tmp)){
				foreach ($tmp as $key) {
					unset($data[$key]);
				}
			}else{
				unset($data[$tmp]);
			}
		}
		
		$where = DBHelper::keyEqCharToString($whereArgs,[],'?','and');
		$sqlUpdate = DBHelper::sqlUpdate(static::$table, $data, $where);
		$sqlArgs = DBHelper::arrayValues($data);
		// print $sqlUpdate;
		foreach ($whereArgs as $key => $value) {
			array_push($sqlArgs, $value);
		}
		// print_r($sqlArgs);

		$db = $dbt != null ? $dbt : new DBHelper();
		$ret = $db->update($sqlUpdate, $sqlArgs);
		self::$error_model = $db->error_db;
		return $ret;
	}

	public static function deleteItem($args, $dbt = null){
		$sqlDelete = DBHelper::sqlDelete(static::$table, $args);
		// print($sqlDelete);
		$sqlArgs = DBHelper::arrayValues($args);
		// print_r($sqlArgs);
		$db = $dbt != null ? $dbt : new DBHelper();
		$ret = $db->delete($sqlDelete, $sqlArgs);
		self::$error_model = $db->error_db;
		return $ret;
	}

	public static function deleteById($id, $dbt = null){
		if(is_array($id)){
			$args = $id;
		}else{
			$args = [static::$pk => $id];	
		}
		
		return self::deleteItem($args, $dbt);
	}

	public static function findById($id, $criteria = null, $dbt = null){
		if($criteria != null){
			$columnas =  isset($criteria['select']) ? $criteria['select'] : null;
		}else{
			$columnas = null;
		}

		if(is_array($id)){
			$args = $id;
		}else{
			$args = [static::$pk => $id];	
		}
		$relations = isset(static::$relations) ? static::$relations : null;
		$include = isset($criteria['include']) ? $criteria['include'] : null;
		$sqlSelect = DBHelper::sqlSelect(static::$table, $columnas, $args, null, 1, -1, $relations, $include);
		// print $sqlSelect;
		$sqlArgs = [];
		
		if(is_array($id)){
			foreach ($id as $key => $value) {
				array_push($sqlArgs, $value);
			}
		}else{
			$sqlArgs[] = $id;
		}

		$db = $dbt != null ? $dbt : new DBHelper();
		$ret = $db->read($sqlSelect, $sqlArgs, true);
		return $ret ? self::parse2Obj($ret) : null;
	}

	public static function findOne($criteria, $dbt = null){
		$columnas = isset($criteria['select']) ? $criteria['select'] : null;
		$assocArray = isset($criteria['where']) ? $criteria['where'] : null;
		$sqlSelect = DBHelper::sqlSelect(static::$table, $columnas, $assocArray, null, 1);
		$sqlArgs = DBHelper::arrayValues($assocArray);
		$db = $dbt != null ? $dbt : new DBHelper();
		$ret = $db->read($sqlSelect, $sqlArgs, true);

		return $ret ? self::parse2Obj($ret) : false;
	}

	private static function getMany($include, $relations, $ret){
		// TODO: el objetivo seria obtener muchos registros relacionados pero se separado
		// para que en caosd e incluir limit y offset de resultados incorrectos
		if(count($include)>0 && count($relations)>0 && count($ret)>0){
			
		}

		return $ret;
	}

	public static function findAll($criteria = null, $dbt = null){
		$columnas   = isset($criteria['select']) ? $criteria['select'] : null;
		$whereAssoc = isset($criteria['where']) ? $criteria['where'] : null;
		$orderBy    = isset($criteria['order']) ? $criteria['order'] : null;
		$limit      = isset($criteria['limit']) ? $criteria['limit'] : -1;
		$offset     = isset($criteria['offset']) ? $criteria['offset'] : -1;
		$relations  = isset(static::$relations) ? static::$relations : null;
		$include    = isset($criteria['include']) ? $criteria['include'] : null;

		if($limit == -1){ $limit = isset($criteria['count']) ? $criteria['count']: -1; }
		if($offset == -1){ $offset = isset($criteria['page']) ? $criteria['page'] : -1; }

		$sqlSelect = DBHelper::sqlSelect(static::$table, $columnas, $whereAssoc, $orderBy, $limit, $offset, $relations, $include);
		// print $sqlSelect;
		$sqlArgs = $whereAssoc != null ? DBHelper::arrayValues($whereAssoc) : array();
		$db = $dbt != null ? $dbt : new DBHelper();
		$ret = $db->read($sqlSelect, $sqlArgs);
		$rows = self::parseList2ArrayObj($ret);
		return $rows;
	}

	public static function count($criteria = null, $dbt = null){
		$whereAssoc = isset($criteria['where']) ? $criteria['where'] : null;
		$include = isset($criteria['include']) ? $criteria['include'] : null;
		$relations = isset(static::$relations) ? static::$relations : null;
		$sqlCount = DBHelper::sqlCount(static::$table, $whereAssoc, "and", $relations, $include);
		$sqlArgs = $whereAssoc != null ? DBHelper::arrayValues($whereAssoc) : array();
		$db = $dbt != null ? $dbt : new DBHelper();
		return $db->readScalar($sqlCount, $sqlArgs);
	}

	public static function findAndCountAll($criteria = null, $dbt = null){
		$count = self::count($criteria, $dbt);
		if($count == 0){ return ['count' => 0, 'rows' => []]; }
		$rows = self::findAll($criteria, $dbt);
		return ['count' => $count, 'rows' => $rows ];
	}

	public static function parseList2ArrayObj($arrayData){
		$list = [];
		foreach ($arrayData as $key => $value) {
			$list[] = self::parse2Obj($value);
		}
		return $list;
	}

	public static function parse2Obj($data){
		$className = static::$className;
		$tmp = new $className();
		foreach ($data as $key => $value) {
			$tmp->$key = $value;
		}
		return $tmp;
	}
	
	public function jsonSerialize(){
		$var = get_object_vars($this);
		// TODO: alguna transformacio para solo devolver las variables con valor
		return $var;
	}
}