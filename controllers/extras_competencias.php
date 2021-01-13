<?php

/**
 * para guardar los conocimientos de la compentencia
 * @param  Integer $idCompetencia id de la competencia en edicion
 * @param  Boolean $nuevo         identifica si hay que buscar entre los ya guardados para una actualizacion (eliminacion de los que ya no son necesarios y agregación de los nuevos)
 * @param  Array $datos         Registros de conocimientos
 * @param  DBHelper $dbt         Transacción existente si es necesaria
 * @return Boolean                indica si el proceso fue realizado correctamente
 */
function guardarConocimientosCompetencia($idCompetencia, $nuevo, $datos, $idUsuario, $db){
	$agregar = [];
	$crear = [];
	$eliminar = [];

	// proceso de creacion de los que no existen
	foreach ($datos as $index => $item) {
		if($item['id'] == 0){
			// primero intentamos buscarlos
			$tmp = TblCompo_conocimiento::findOne(['where'=>['descrip_conocimiento'=>$item['label']]], $db);
			if($tmp){
				$datos[$index]['id'] = $tmp->id_compo_conocimiento;
			}else{
				$objeto = new TblCompo_conocimiento(['id_compo_conocimiento'=>0,'descrip_conocimiento'=>$item['label'],'id_usuario'=>$idUsuario]);
				if($objeto->save($db)){
					$datos[$index]['id'] = $objeto->id_compo_conocimiento;
				}else{
					return "Error al guardar c nuevo '".$item['label']."'.".$db->error_db;
				}
			}
		}
	}



	$idsNuevos = [];
	foreach ($datos as $item) {
		$idsNuevos[] = $item['id'];
	}

	if(!$nuevo){
		$sql = "select * from ".TblConocimientoCompetencia::$table." where id_competencia = ?";
		$existentes = $db->read($sql, [$idCompetencia]);

		$idsExistentes = [];
		foreach ($existentes as $item) {
			$idsExistentes[] = $item['id_compo_conocimiento'];
		}

		// obteniendo aquellos que debemos agregar
		foreach ($idsNuevos as $idNuevo) {
			if(!in_array($idNuevo, $idsExistentes)){
				$agregar[] = $idNuevo;
			}
		}

		// obtenemos aquellos que ya existen pero debemos eliminar
		foreach ($idsExistentes as $idExistente) {
			if(!in_array($idExistente, $idsNuevos)){
				$eliminar[] = $idExistente;
			}
		}
	}else{
		$agregar = $idsNuevos;
	}

	// agregamos y eliminamos los correspondientes
	foreach ($eliminar as $idItem) {
		$sql = "delete from ".TblConocimientoCompetencia::$table." where id_competencia=? and id_compo_conocimiento = ?;";
		if(!$db->delete($sql, [$idCompetencia, $idItem])){
			return "c:error al eliminar $idItem. ".$db->error_db;
		}
	}

	foreach ($agregar as $idItem) {
		$sql = "insert into ".TblConocimientoCompetencia::$table." (id_competencia,id_compo_conocimiento) values (?,?);";
		if(!$db->insert($sql, [$idCompetencia, $idItem])){
			return "c:error al insertar nuevo $idItem. ".$db->error_db;
		}
	}

	return true;
}



function guardarHabilidadesCompetencia($idCompetencia, $nuevo, $datos, $idUsuario, $db){
	$agregar = [];
	$crear = [];
	$eliminar = [];

	// proceso de creacion de los que no existen
	foreach ($datos as $index => $item) {
		if($item['id'] == 0){
			// primero intentamos buscarlos
			$tmp = TblCompo_habilidad::findOne(['where'=>['descrip_habilidad'=>$item['label']]], $db);
			if($tmp){
				$datos[$index]['id'] = $tmp->id_compo_habilidad;
			}else{
				$objeto = new TblCompo_habilidad(['id_compo_habilidad'=>0,'descrip_habilidad'=>$item['label'],'id_usuario'=>$idUsuario]);
				if($objeto->save($db)){
					$datos[$index]['id'] = $objeto->id_compo_habilidad;
				}else{
					return "Error al guardar h nuevo '".$item['label']."'.".$db->error_db;
				}
			}
		}
	}

	$idsNuevos = [];
	foreach ($datos as $item) {
		$idsNuevos[] = $item['id'];
	}

	if(!$nuevo){
		$sql = "select * from ".TblHabilidadCompetencia::$table." where id_competencia = ?";
		$existentes = $db->read($sql, [$idCompetencia]);

		$idsExistentes = [];
		foreach ($existentes as $item) {
			$idsExistentes[] = $item['id_compo_habilidad'];
		}

		// obteniendo aquellos que debemos agregar
		foreach ($idsNuevos as $idNuevo) {
			if(!in_array($idNuevo, $idsExistentes)){
				$agregar[] = $idNuevo;
			}
		}

		// obtenemos aquellos que ya existen pero debemos eliminar
		foreach ($idsExistentes as $idExistente) {
			if(!in_array($idExistente, $idsNuevos)){
				$eliminar[] = $idExistente;
			}
		}
	}else{
		$agregar = $idsNuevos;
	}

	// agregamos y eliminamos los correspondientes
	foreach ($eliminar as $idItem) {
		$sql = "delete from ".TblHabilidadCompetencia::$table." where id_competencia=? and id_compo_habilidad = ?;";
		if(!$db->delete($sql, [$idCompetencia, $idItem])){
			return "h:error al eliminar $idItem. ".$db->error_db;
		}
	}

	foreach ($agregar as $idItem) {
		$sql = "insert into ".TblHabilidadCompetencia::$table." (id_competencia,id_compo_habilidad) values (?,?);";
		if(!$db->insert($sql, [$idCompetencia, $idItem])){
			return "h:error al insertar nuevo $idItem. ".$db->error_db;
		}
	}

	return true;
}


function guardarActitudesValoresCompetencia($idCompetencia, $nuevo, $datos, $idUsuario, $db){
	$agregar = [];
	$crear = [];
	$eliminar = [];

	// proceso de creacion de los que no existen
	foreach ($datos as $index => $item) {
		if($item['id'] == 0){
			// primero intentamos buscarlos
			$tmp = TblCompo_actitud_valor::findOne(['where'=>['descrip_actitud_valor'=>$item['label']]], $db);
			if($tmp){
				$datos[$index]['id'] = $tmp->id_compo_valor;
			}else{
				$objeto = new TblCompo_actitud_valor(['id_compo_valor'=>0,'descrip_actitud_valor'=>$item['label'],'id_usuario'=>$idUsuario]);
				if($objeto->save($db)){
					$datos[$index]['id'] = $objeto->id_compo_valor;
				}else{
					return "Error al guardar av nuevo '".$item['label']."'.".$db->error_db;
				}
			}
		}
	}

	$idsNuevos = [];
	foreach ($datos as $item) {
		$idsNuevos[] = $item['id'];
	}

	if(!$nuevo){
		$sql = "select * from ".TblActitudValorCompetencia::$table." where id_competencia = ?";
		$existentes = $db->read($sql, [$idCompetencia]);

		$idsExistentes = [];
		foreach ($existentes as $item) {
			$idsExistentes[] = $item['id_compo_actitud_valor'];
		}

		// obteniendo aquellos que debemos agregar
		foreach ($idsNuevos as $idNuevo) {
			if(!in_array($idNuevo, $idsExistentes)){
				$agregar[] = $idNuevo;
			}
		}

		// obtenemos aquellos que ya existen pero debemos eliminar
		foreach ($idsExistentes as $idExistente) {
			if(!in_array($idExistente, $idsNuevos)){
				$eliminar[] = $idExistente;
			}
		}
	}else{
		$agregar = $idsNuevos;
	}

	// agregamos y eliminamos los correspondientes
	foreach ($eliminar as $idItem) {
		$sql = "delete from ".TblActitudValorCompetencia::$table." where id_competencia=? and id_compo_actitud_valor = ?;";
		if(!$db->delete($sql, [$idCompetencia, $idItem])){
			return "av:error al eliminar $idItem. ".$db->error_db;
		}
	}

	foreach ($agregar as $idItem) {
		$sql = "insert into ".TblActitudValorCompetencia::$table." (id_competencia,id_compo_actitud_valor) values (?,?);";
		if(!$db->insert($sql, [$idCompetencia, $idItem])){
			return "av:error al insertar nuevo $idItem. ".$db->error_db;
		}
	}

	return true;
}

// -----------------------------------------------------------------
function limpiarTexto($str){
	$str = Helpers::textoNoAcentos($str);
	$str = Helpers::removerConectores($str);
	return $str;
}

function recomendacionesConocimientos(TblCompetencia $competencia, $max = 5){
	$str = limpiarTexto($competencia->competencia_editable);

	$contenidos = TblCompetenciaContenidoUnidad::findAll([
		'where' => [
			'id_competencia' => $competencia->id_competencia,
		],
		'include' => [
			['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido']]
		]
	]);

	$contenidos_unidad = [];
	foreach ($contenidos as $contenido) {
		$contenidos_unidad[] = limpiarTexto($contenido->detalle_secuencia_contenido);
	}
	
	$tokens = explode(" ", $str);
	$sqlArgs = [];

	// removemos espacios en blanco y preparamos cada token para la sintaxis lik
	foreach ($tokens as $item) {
		if(trim($item) != ""){
			$sqlArgs[] = "%".$item."%";
		}
	}

	foreach ($contenidos_unidad as $contenido_unidad) {
		$tokens_contenido = explode(" ",  $contenido_unidad);
		foreach ($tokens_contenido as $tokenCU) {
			if(!in_array("%".$tokenCU."%", $sqlArgs)){
				$sqlArgs[] = "%".$tokenCU."%";
			}
		}
	}

	$likeStatement = "";
	$db = new DBHelper();
	if(count($sqlArgs)>0){
		$likeStatement = " where  descrip_conocimiento like ? ";
		for ($i=1; $i < count($sqlArgs); $i++) { 
			$likeStatement .= " or descrip_conocimiento like ? ";
		}

		$sql = "select descrip_conocimiento, id_compo_conocimiento from ".
				TblCompo_conocimiento::$table.
				" $likeStatement order by descrip_conocimiento asc limit $max;";
		$listado = $db->read($sql, $sqlArgs);

		if(count($listado)>0){
			foreach ($listado as $key => $value) {
				$listado[$key]['descrip_conocimiento'] = $value['descrip_conocimiento']." (recomendación)";
				$listado[$key]['es_recomendacion'] = true;
			}
		}
	}
	if(count($listado) == 0){
		$sql = "select descrip_conocimiento, id_compo_conocimiento from ".TblCompo_conocimiento::$table." order by descrip_conocimiento asc limit $max;";
		$listado = $db->read($sql, $sqlArgs);
		foreach ($listado as $key => $value) {
			$listado[$key]['descrip_conocimiento'] = $value['descrip_conocimiento']." (recomendación)";
			$listado[$key]['es_recomendacion'] = true;
		}
	}

	// contenidos de la fase anterior
	$contenidos_f2 = TblContenidoCompetencia::findAll([
		'where' => [
			'id_competencia' => $competencia->id_competencia,
		],
		'include' => [
			['localField' => 'id_contenido', 'select' => ['descrip_contenido']],
		]
	]);

	foreach ($contenidos_f2 as $index => $contenidof2) {
		$existentes = TblCompo_conocimiento::findAll([
			'where' => [
				'descrip_conocimiento' => ['like', $contenidof2->descrip_contenido],
			],
			'limit' => 2
		]);

		if(count($existentes) > 0){
			// vemos si se deben agregar
			foreach ($existentes as $index2 => $existente) {
				// vemos si no esta agregado
				// id_compo_conocimiento
				$found = false;
				foreach ($listado as $index3 => $itemListado) {
					if($itemListado['id_compo_conocimiento'] == $existente->id_compo_conocimiento){
						$found = true;
						break;
					}
				}

				if(!$found){
					$listado[] = [
						'id_compo_conocimiento' => $existente->id_compo_conocimiento,
						'descrip_conocimiento' => $existente->descrip_conocimiento,
						'es_recomendacion' => true,
					];
				}
			}
		}else{
			$listado[] = [
				'descrip_conocimiento' => $contenidof2->descrip_contenido." (recomendación)",
				'es_recomendacion' => true,
				'id_compo_conocimiento' => 0,
			];
		}

	}

	return $listado;
}

function recomendacionesHabilidades(TblCompetencia $competencia, $max = 5){
	$str = limpiarTexto($competencia->competencia_editable);

	$tokens = explode(" ", $str);
	$sqlArgs = [];

	// removemos espacios en blanco y preparamos cada token para la sintaxis like
	foreach ($tokens as $item) {
		if(trim($item) != ""){
			$sqlArgs[] = "%".$item."%";
		}
	}
	
	$likeStatement = "";
	$db = new DBHelper();
	if(count($sqlArgs)>0){
		$likeStatement = " where  descrip_habilidad like ? ";
		for ($i=1; $i < count($sqlArgs); $i++) { 
			$likeStatement .= " or descrip_habilidad like ? ";
		}

		$sql = "select descrip_habilidad, id_compo_habilidad from ".
				TblCompo_habilidad::$table.
				" $likeStatement order by descrip_habilidad asc limit $max;";

		
		$listado = $db->read($sql, $sqlArgs);

		if(count($listado)>0){
			foreach ($listado as $key => $value) {
				$listado[$key]['descrip_habilidad'] = $value['descrip_habilidad']." (recomendación)";
				$listado[$key]['es_recomendacion'] = true;
			}
			return $listado;
		}
	}

	$sql = "select descrip_habilidad, id_compo_habilidad from ".TblCompo_habilidad::$table." order by descrip_habilidad asc limit $max;";
	$listado = $db->read($sql, $sqlArgs);
	foreach ($listado as $key => $value) {
		$listado[$key]['descrip_habilidad'] = $value['descrip_habilidad']." (recomendación)";
		$listado[$key]['es_recomendacion'] = true;
	}

	return $listado;
}

function recomendacionesAVs(TblCompetencia $competencia, $max = 5){

	$db = new DBHelper();
	$sql = "select c.* from ".TblCriterio::$table." c inner join ".TblCriterioCompetencia::$table." cc on c.id_criterio=cc.id_criterio where cc.id_competencia = ?;";
	$criterios = $db->read($sql,[$competencia->id_competencia]);
	$str = "";
	foreach ($criterios as $item) {
		$str.= " ".limpiarTexto($item['descrip_criterio']);
	}

	$tokens = explode(" ", $str);
	$sqlArgs = [];

	// removemos espacios en blanco y preparamos cada token para la sintaxis like
	foreach ($tokens as $item) {
		if(trim($item) != ""){
			$sqlArgs[] = "%".$item."%";
		}
	}
	
	$likeStatement = "";
	$db = new DBHelper();
	if(count($sqlArgs)>0){
		$likeStatement = " where  descrip_actitud_valor like ? ";
		for ($i=1; $i < count($sqlArgs); $i++) { 
			$likeStatement .= " or descrip_actitud_valor like ? ";
		}

		$sql = "select id_compo_valor, descrip_actitud_valor from ".
				TblCompo_actitud_valor::$table.
				" $likeStatement order by descrip_actitud_valor asc limit $max;";

		// print $sql;
		// print_r($sqlArgs);
		$listado = $db->read($sql, $sqlArgs);

		if(count($listado)>0){
			foreach ($listado as $key => $value) {
				$listado[$key]['descrip_actitud_valor'] = $value['descrip_actitud_valor']." (recomendación)";
				$listado[$key]['es_recomendacion'] = true;
			}
			return $listado;
		}
	}

	$sql = "select descrip_actitud_valor, id_compo_valor from ".TblCompo_actitud_valor::$table." order by descrip_actitud_valor asc limit $max;";
	$listado = $db->read($sql, $sqlArgs);
	foreach ($listado as $key => $value) {
		$listado[$key]['descrip_actitud_valor'] = $value['descrip_actitud_valor']." (recomendación)";
		$listado[$key]['es_recomendacion'] = true;
	}

	return $listado;
}
