<?php
// require_once 'Database.php';

// class DBHelper{

//     public $DB = null;
//     public $error_db = "";
//     public $has_error = false;

//     public function __construct(){
//         $this->DB = Database::getInstance()->getDb();
//     }

//     public function __destruct(){
//         if($this->DB->inTransaction()){
//             $this->DB->rollBack();
//         }
//     }

//     private function resetError(){
//         $this->error_db = "";
//         $this->has_error = false;

//     }

//     public function beginTransaction(){
//         if($this->DB==null && !$this->DB->inTransaction())
//             $this->DB = Database::getInstance()->getDb();

//         $this->DB->beginTransaction();
//         return $this->DB;
//     }

//     public function commit(){
//         if($this->DB->inTransaction())
//             $this->DB->commit();
//     }

//     public function rollBack(){
//         if($this->DB->inTransaction())
//             $this->DB->rollBack();
//     }

//     public function getId(){
//         return $this->DB->lastInsertId();
//     }

//     public function insert($query, $args){
//         $this->resetError();
//         try{
//             $sentence = $this->DB->prepare($query);
//             $res = $sentence->execute($args);
//             return $res;
//         }catch(Exception $ex){
//             $this->error_db = $ex->getMessage();
//             $this->has_error = true;
//             return false;
//         }
//     }

//     public function readScalar($query,$args = array()){
//         $this->resetError();
//         $res = $this->read($query, $args, true, false);
//         if($res){
//             $this->error_db = "";
//             return $res;
//         }else{
//             $this->error_db = "Error desconocido al leer en scalar db";
//             return null;
//         }
//     }

//     public function read($query, $args = array(), $limit_1=false,$type_assoc=true){
//         $this->resetError();
//         try{
//             $sentence = $this->DB->prepare($query);
//             $sentence->execute($args);
//             return ($limit_1)?$sentence->fetch($type_assoc?PDO::FETCH_ASSOC:PDO::FETCH_COLUMN):$sentence->fetchAll($type_assoc?PDO::FETCH_ASSOC:PDO::FETCH_COLUMN);
//         }catch(Exception $ex){
//             $this->error_db = $ex->getMessage();
//             $this->has_error = true;
//             return array();
//         }
//     }

//     public function update($query, $args){
//         $this->resetError();
//         try{
//             $sentence = $this->DB->prepare($query);
//             $res = $sentence->execute($args);
//             if($sentence->rowCount()==0){
//                 $this->error_db = "Registro no encontrado";
//             }
//             return ($res && $sentence->rowCount()>0);
//         }catch(Exception $ex){
//             $this->error_db = $ex->getMessage();
//             $this->has_error = true;
//             return false;
//         }
//     }

//     public function delete($query, $args){
//         return $this->update($query, $args);
//     }

//     public static function sqlInsert($tabla, $assocArray = array()){
//         $sql = "";
//         if(!empty($assocArray)){
//             $keys = self::keyStringArray($assocArray);
//             $qFilter = self::repeatCharOnString(count($assocArray));
//             $sql = "insert into $tabla ($keys) values ($qFilter);";
//         }

//         return $sql;
//     }

//     public static function sqlCount($tabla, $assocArray = null, $sep = "and"){
//         $sql = "select count(*) as total from $tabla ";
//         if($assocArray != null){
//             $sql .= (" where ".self::keyEqCharToString($assocArray,array(),"?",$sep));
//         }
//         return $sql;
//     }

//     public static function sqlUpdate($tabla, $assocArray = array(), $whereStatement = "", $ignore = array(), $sep = "," ){
//         $sql = "";
//         if(!empty($assocArray)){
//             $keyEqChar = self::keyEqCharToString($assocArray, $ignore, "?", $sep);
//             $sql = "update $tabla set $keyEqChar ".($whereStatement!=""?"where $whereStatement":"").";";
//         }
//         return $sql;
//     }

//     public static function sqlDelete($tabla, $whereAssocArray, $ignore = array(), $sep = "and" ){
//         $sql = "delete from $tabla where ";
//         $keyString = self::keyEqCharToString($whereAssocArray, $ignore, "?", $sep);
//         $sql .= $keyString.";";
//         return $sql;
//     }

//     public static function sqlSelect($tabla, $columns = null, $whereAssocArray = null, $orderBy = null, $limit = -1, $offset = -1){
//         $columnsToSelect = "*";
//         if($columns!=null && count($columns)>0){
//             $columnsToSelect = implode(",", $columns);
//         }
//         $whereStatement = "";
//         if($whereAssocArray != null && count($whereStatement)>0){
//             $whereStatement = " where ".self::keyEqCharToString($whereAssocArray, array(), '?', 'and');
//         }
//         $sql = "select $columnsToSelect from $tabla $whereStatement ";
//         if($orderBy != null && $orderBy != ""){
//             $sql .= " order by $orderBy ";
//         }
//         if($limit != -1 && $offset != -1){
//             $sql .= " limit $limit offset $offset ";
//         }
//         $sql.=";";

//         return $sql;
//     }

//     public static function keyStringArray($assocArray, $separator = ','){
//         return implode($separator, array_keys($assocArray));
//     }

//     public static function repeatCharOnString($count, $charV='?', $separator=','){
//         return implode($separator, array_fill(0, $count, " ".$charV." "));
//     }

//     public static function valueStringArray($assocArray, $separator = ','){
//         return implode($separator, array_values($assocArray));
//     }

//     public static function arrayValues($assocArray = array(), $ignore=array()){
//         $retArray = array();
//         if(!empty($ignore)){            
//             foreach ($assocArray as $key => $value) {
//                 if(!in_array($key, $ignore)){

//                     if(is_array($value)){

//                         $val = $value['value'];

//                         if(isset($value['like']) && $value['like']){
//                             $val = "%".$val."%";
//                         }
//                         array_push($retArray, $val);
//                     }else{
//                         array_push($retArray, $value);
//                     }
//                 }
//             }
//         }else{
//             foreach ($assocArray as $key => $value) {
//                 if(is_array($value)){
//                     $val = $value['value'];
//                     if(isset($value['like']) && $value['like']){
//                         $val = "%".$val."%";
//                     }
//                     array_push($retArray, $val);
//                 }else{
//                     array_push($retArray, $value);
//                 }
//             }
//             // $retArray = array_values($assocArray);
//         }
//         return $retArray;
//     }

//     public static function keyEqCharToString($assocArray, $ignore = array(), $charV = '?', $separator=','){
//         $buffer = array();
        
//         foreach ($assocArray as $key => $value) {
//             $eqChar = "=";
//             if(!in_array($key, $ignore)){
//                 if(is_array($value)){
//                     if(isset($value['like']) && $value['like']){
//                         $eqChar = "like";
//                     }
//                 }
                
//                 array_push($buffer, " $key $eqChar ? ");
//             }
//         }
//         return implode($separator, $buffer);
//     }
// }
