<?php

class DBHelper{
    public $DB        = null;
    public $error_db  = "";
    public $has_error = false;
    public static $instance  = null;

    public function __construct(){
        $this->DB = Database::getInstance()->getDb();
    }

    public function __destruct(){
        if($this->DB->inTransaction()){
            $this->DB->rollBack();
        }
    }

    public static function singleton(){
        if(self::$instance == null) self::$instance = new DBHelper();
        return self::$instance;
    }

    private function resetError(){
        $this->error_db = "";
        $this->has_error = false;

    }

    public function beginTransaction(){
        if($this->DB==null && !$this->DB->inTransaction())
            $this->DB = Database::getInstance()->getDb();

        $this->DB->beginTransaction();
        return $this->DB;
    }

    public function commit(){
        if($this->DB->inTransaction())
            $this->DB->commit();
    }

    public function rollBack(){
        if($this->DB->inTransaction())
            $this->DB->rollBack();
    }

    public function getId(){
        return $this->DB->lastInsertId();
    }

    public function insert($query, $args){
        $this->resetError();
        try{
            $sentence = $this->DB->prepare($query);
            $res = $sentence->execute($args);
            return $res;
        }catch(Exception $ex){
            $this->error_db = $ex->getMessage();
            $this->has_error = true;
            return false;
        }
    }

    public function readScalar($query,$args = array()){
        $this->resetError();
        $res = $this->read($query, $args, true, false);
        if($res || $res == 0){
            $this->error_db = "";
            return $res;
        }else{
            $this->error_db = "Error desconocido al leer en scalar db";
            return null;
        }
    }

    public function read($query, $args = array(), $limit_1=false,$type_assoc=true){
        $this->resetError();
        // try{
            $sentence = $this->DB->prepare($query);
            $sentence->execute($args);
            return ($limit_1)?$sentence->fetch($type_assoc?PDO::FETCH_ASSOC:PDO::FETCH_COLUMN):$sentence->fetchAll($type_assoc?PDO::FETCH_ASSOC:PDO::FETCH_COLUMN);
        // }catch(Exception $ex){
        //     $this->error_db = $ex->getMessage();
        //     $this->has_error = true;
        //     return array();
        // }
    }

    public function update($query, $args){
        $this->resetError();
        try{
            // print $query;
            $sentence = $this->DB->prepare($query);
            // print_r($args);
            $res = $sentence->execute($args);
            if($sentence->rowCount()==0){
                $this->error_db = "Registro no encontrado";
            }
            return ($res || $sentence->rowCount()>=0);
        }catch(Exception $ex){
            $this->error_db = $ex->getMessage();
            $this->has_error = true;
            return false;
        }
    }

    public function delete($query, $args){
        return $this->update($query, $args);
    }

    public static function sqlInsert($tabla, $assocArray = array()){
        $sql = "";
        if(!empty($assocArray)){
            $keys = self::keyStringArray($assocArray);
            $qFilter = self::repeatCharOnString(count($assocArray));
            $sql = "insert into $tabla ($keys) values ($qFilter);";
        }

        return $sql;
    }

    public static function sqlUpdate($tabla, $assocArray = array(), $whereStatement = "", $ignore = array(), $sep = "," ){
        $sql = "";
        if(!empty($assocArray)){
            $keyEqChar = self::keyEqCharToString($assocArray, $ignore, "?", $sep);
            $sql = "update $tabla set $keyEqChar ".($whereStatement!=""?"where $whereStatement":"").";";
        }
        return $sql;
    }

    public static function sqlDelete($tabla, $whereAssocArray, $ignore = array(), $sep = "and" ){
        $sql = "delete from $tabla where ";
        $keyString = self::keyEqCharToString($whereAssocArray, $ignore, "?", $sep);
        $sql .= $keyString.";";
        return $sql;
    }

    public static function selectInclude($relaciones, $include){
        $columnas = [];
        foreach ($include as $index => $itemInclude) {
            if(isset($relaciones[$itemInclude['localField']])){
                $rel = $relaciones[$itemInclude['localField']];
                if($rel['type'] == 1){
                    if(isset($itemInclude['select'])){
                        $asTable = isset($itemInclude['as']) ? $itemInclude['as'] : $rel['model'];
                        foreach($itemInclude['select'] as $columna){
                            if(is_array($columna) && count($columna)==2){
                                $columnas[] = $asTable.".".$columna[0]." as ".$columna[1];//.(!isset($itemInclude['as']) ? " as ".$rel['model'].'_'.$columna : "");
                            }else{
                                $columnas[] = $asTable.".".$columna." ";//.(!isset($itemInclude['as']) ? " as ".$rel['model'].'_'.$columna : "");
                            }
                        }
                    }else{
                        $columnas[] = $asTable.'*';
                    }
                }else{
                    // Por el momento esta opcion no esta soportadada
                }
            }
        }

        return (count($columnas)>0 ? ", " : "" ).implode(",", $columnas);
    }

    public static function innerJoinFor($tabla, $relaciones, $include = []){
        $inners = "";
        foreach ($include as $index => $itemInclude) {
            if(isset($relaciones[$itemInclude['localField']])){
                $required = isset($itemInclude['required']) ? $itemInclude['required'] : true;
                $tipoJoin = $required ? "INNER" : "LEFT";
                $rel = $relaciones[$itemInclude['localField']];
                if($rel['type'] == 1){
                    if(class_exists($rel['model'])){
                        $tableName = $rel['model']::$table;
                        $asName = isset($itemInclude['as']) ? $itemInclude['as'] : $rel['model'];
                        $inners .= " $tipoJoin JOIN ".$tableName." ".$asName." ON $tabla.".$itemInclude['localField']."=".$asName.".".$rel['foreignField']." ";
                    }else{
                        throw new Exception("No existe la clase ".$rel['model'], 1);
                    }
                }
            }
        }
        return $inners;
    }

    private static function skeletonWhere($tabla, $whereAssocArray, $sep, $relations, $include){
        $innerJoin = ($relations != null && $include != null) 
            ? self::innerJoinFor($tabla, $relations, $include)
            : "";

        $whereStatement = ($whereAssocArray != null && count($whereAssocArray)>0)
            ? " where ".self::keyEqCharToString($whereAssocArray, array(), '?', 'and', $tabla)
            : "";

        return " from $tabla $tabla $innerJoin $whereStatement ";
    }

    public static function sqlCount($tabla, $assocArray = null, $sep = "and", $relations = null, $include = null){
        $sql = "select count(*) as total ".self::skeletonWhere($tabla, $assocArray, $sep, $relations, $include);
        return $sql;
    }

    public static function sqlSelect($tabla, $columns = null, $whereAssocArray = null, $orderBy = null, $limit = -1, $offset = -1, $relations = null, $include = null){
        $columnsToSelect = "$tabla.*";
        if($columns!=null && count($columns)>0){
            $columnsToSelect = "$tabla.".$columns[0];
            if(count($columns)>1){
                for ($i=1; $i < count($columns); $i++) { 
                    $columnsToSelect.=" , $tabla.".$columns[$i];
                }
            }
        }

        if($relations != null && $include != null){
            $selectInclude = self::selectInclude($relations, $include);
            $columnsToSelect.=" $selectInclude ";
        }


        $sql = "select $columnsToSelect ".self::skeletonWhere($tabla, $whereAssocArray, 'and', $relations, $include);
        if($orderBy != null && $orderBy != ""){ $sql .= " order by $orderBy "; }
        if($limit != -1){ $sql .= " limit $limit "; }
        if($offset != -1){ $sql.= " offset $offset "; }
        return $sql;
    }

    // public static function sqlCount($tabla, $assocArray = null, $sep = "and", $relations = null, $include = null){
    //     $sql = "select count(*) as total from $tabla ";
    //     if($relations != null && $include != null && count($relations)>0 && count($include)>0){
    //         $inner = DBHelper::innerJoinFor($tabla, $relations, $include);
    //         if($inner){ $sql.=" $inner "; }
    //     }

    //     if($assocArray != null){
    //         $sql .= (" where ".self::keyEqCharToString($assocArray,array(),"?",$sep));
    //     }
    //     return $sql;
    // }

    // public static function sqlSelect($tabla, $columns = null, $whereAssocArray = null, $orderBy = null, $limit = -1, $offset = -1, $relations = null, $include = null){
    //     $columnsToSelect = "$tabla.*";
    //     if($columns!=null && count($columns)>0){
    //         $columnsToSelect = "$tabla.".$columns[0];
    //         if(count($columns)>1){
    //             for ($i=1; $i < count($columns); $i++) { 
    //                 $columnsToSelect.=" , $tabla.".$columns[$i];
    //             }
    //         }
    //     }

    //     $innerJoin = "";
    //     if($relations != null && $include != null){
    //         $selectInclude = self::selectInclude($relations, $include);
    //         $columnsToSelect.=" $selectInclude ";
    //         $innerJoin = self::innerJoinFor($tabla, $relations, $include);
    //     }

    //     $whereStatement = "";
    //     if($whereAssocArray != null && count($whereStatement)>0){
    //         $whereStatement = " where ".self::keyEqCharToString($whereAssocArray, array(), '?', 'and', $tabla);
    //     }

    //     $sql = "select $columnsToSelect from $tabla $tabla $innerJoin $whereStatement ";
    //     if($orderBy != null && $orderBy != ""){ $sql .= " order by $orderBy "; }

    //     if($limit != -1){ $sql .= " limit $limit "; }
    //     if($offset != -1){ $sql.= " offset $offset "; }
    //     $sql.=";";

    //     return $sql;
    // }

    public static function keyStringArray($assocArray, $separator = ','){
        return implode($separator, array_keys($assocArray));
    }

    public static function repeatCharOnString($count, $charV='?', $separator=','){
        return implode($separator, array_fill(0, $count, " ".$charV." "));
    }

    public static function valueStringArray($assocArray, $separator = ','){
        return implode($separator, array_values($assocArray));
    }

    public static function arrayValues($assocArray = array(), $ignore=array()){
        $retArray = array();
        if(!empty($ignore)){
            foreach ($assocArray as $key => $value) {
                if($key == 'and' || $key == 'or'){
                    if(is_array($value)){
                        foreach ($value as $subValue) {
                            foreach ($subValue as $index1 => $subSubValue) {
                                if(is_array($subSubValue)){
                                    if($subSubValue[0] == 'like'){
                                        array_push($retArray, "%".$subSubValue[1]."%");
                                    }else{
                                        array_push($retArray, $subSubValue[1]);
                                    }
                                }else{
                                    array_push($retArray, $subSubValue);
                                }
                            }   
                        }
                        continue;
                    }
                }
                
                if(!in_array($key, $ignore)){

                    if(is_array($value)){
                        $val = $value['value'];
                        if(isset($value['like']) && $value['like']){
                            $val = "%".$val."%";
                        }else if(isset($value[0]) && isset($value[1])){
                            if($value[0] == 'like'){
                                if(isset($value[2])){
                                    $val = $value[2] ? "%".$value[1]."%" : $value[1];
                                }else{
                                    $val = "%".$value[1]."%";
                                }
                            }else{
                                $val = $value[1];
                            }
                        }
                        array_push($retArray, $val);
                    }else{
                        array_push($retArray, $value);
                    }
                }
                
            }
        }else{
            // print_r($assocArray);
            foreach ($assocArray as $key => $value) {
                if($key == 'and' || $key == 'or'){
                    if(is_array($value)){
                        foreach ($value as $subValue) {
                            if(isset($subValue[0]) && $subValue[0] instanceof ModelFunction){
                                if(is_array($subValue[1])){
                                    array_push($retArray, $subValue[1][1]);
                                }else{
                                    array_push($retArray, $subValue[1]);
                                }
                            }else{
                                foreach ($subValue as $index1 => $subSubValue) {
                                    if(is_array($subSubValue)){
                                        if($subSubValue[0] == 'like'){
                                            array_push($retArray, "%".$subSubValue[1]."%");
                                        }else{
                                            array_push($retArray, $subSubValue[1]);
                                        }
                                    }else{
                                        array_push($retArray, $subSubValue);
                                    }
                                }   
                            }
                        }
                        continue;
                    }
                }

                
                if(is_array($value)){
                    if(isset($value[0]) && isset($value[1])){
                        $val = $value[0] == "like" ? "%".$value[1]."%" : $value[1];
                    }else if(isset($value['like'])){
                        $val = "%".$value['like']."%";
                    }else{
                        $val = $value['value'];
                        if(isset($value['like']) && $value['like']){
                            $val = "%".$val."%";
                        }
                    }
                    array_push($retArray, $val);
                }else{
                    array_push($retArray, $value);
                }
            }
            // $retArray = array_values($assocArray);
        }
        return $retArray;
    }

    public static function nestedKeyEqCharToString($assocArray, $separator, $tabla){
        if(isset($assocArray[0]) && $assocArray[0] instanceof ModelFunction){
            return "(".$assocArray[0]->value." ".(is_array($assocArray[1]) ? $assocArray[1][0] : " = "  )." ? )";
        }else{
            $buffer = array();
            $pref = $tabla != '' ? $tabla.'.' : '';
            
            foreach ($assocArray as $key => $value) {
                $eqChar = "=";
                if(is_array($value)){
                    if(isset($value['like']) && $value['like']){
                        $eqChar = "like";
                    }else{
                        if(isset($value[0])){
                            $eqChar = $value[0]; // puede ser like, =, >, >=, <, <= , <>
                        }
                    } 
                }
                if(count(explode(".", $key)) == 2 ) { // por si llega como tabla.valor
                    array_push($buffer, " $key $eqChar ? ");
                }else{
                    array_push($buffer, " $pref$key $eqChar ? ");
                }
            }
            return count($buffer)>0 ? "( ".implode($separator, $buffer)." )" : "";
        }
    }

    public static function keyEqCharToString($assocArray, $ignore = array(), $charV = '?', $separator=',', $tabla = ''){
        $buffer = array();
        $pref = $tabla != '' ? $tabla.'.' : '';
        
        foreach ($assocArray as $key => $value) {
            $eqChar = "=";
            if(!in_array($key, $ignore)){
                if(is_array($value)){
                    if($key == "and" || $key == "or"){
                        $strNestedBool = "";
                        $tmpAr = [];
                        foreach ($value as $index1 => $subValue) {
                            array_push($tmpAr, self::nestedKeyEqCharToString($subValue, "and", $tabla));
                        }
                        $strNestedBool = implode(" $key ", $tmpAr);
                        array_push($buffer, " ( $strNestedBool ) ");
                        continue;
                    }else{
                        if(isset($value['like']) && $value['like']){
                            $eqChar = "like";
                        }else{
                            if(isset($value[0])){
                                $eqChar = $value[0]; // puede ser like, =, >, >=, <, <= , <>
                            }
                        }    
                    }
                }
                if(count(explode(".", $key)) == 2 ) { // por si llega como tabla.valor
                    array_push($buffer, " $key $eqChar ? ");
                }else{
                    array_push($buffer, " $pref$key $eqChar ? ");
                }
            }
        }
        return implode($separator, $buffer);
    }
}