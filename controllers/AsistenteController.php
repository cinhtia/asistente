<?php 

require_once 'extras_competencias.php';

class AsistenteController extends BaseController{

	/**
	 * si existe un error en el guardado de la fase2, este string tendrá
	 * el error que ocurrió
	 * @var string
	 */
	private $error_fase = "";

	public function IndexAction(Request $req, Response $res, Data $data){

		if($req->existsGet('id_competencia')){
			$competencia = TblCompetencia::findById($req->get('id_competencia'));
			$data->addToBody('competencia', $competencia);
			$data->addToBody('nuevo', false);
		}
		
		$this->render('asistente-generador/c-asignatura/index', $data);
	}

	public function FormularioFase1Action(Request $req, Response $res, Data $data){
		
		if($req->existsGet('id_competencia')){
			$idCompetencia = $req->get('id_competencia');
			$competencia = TblCompetencia::findById($idCompetencia);
			$usuario = TblUsuario::findById($competencia->id_usuario);
			$competencia->Usuario = $usuario;
			$competencia->AsignaturaPe = TblAsignaturaPe::findById($competencia->id_asignatura_pe);

			$data->addToBody('competencia', $competencia);
			$data->addToBody('nuevo', false);
		}

		$this->render('asistente-generador/c-asignatura/formulario_fase1_datosbasicos', $data);
	}


	public function GuardarFase1Action(Request $req, Response $res, Data $data){
		$extra = [];
		if($data->isParamsOk()){
			$idPE = $data->fromBody('id_pe');
			$idAsignaturaPE = $data->fromBody('id_asignatura_pe');
			$descripcion = $data->fromBody('descripcion');
			$idCompetencia = $req->postIfExists('id_competencia', 0);

			$competencia = new TblCompetencia();
			$nuevo = true;
			if($idCompetencia>0){
				$competencia = TblCompetencia::findById($idCompetencia);
				$nuevo = false;
			}else{
				$competencia->id_usuario = $this->user->id;
			}
			
			$competencia->id_asignatura_pe=$idAsignaturaPE;
			$competencia->descripcion = $descripcion;

			$competencia->tipo_competencia = "asignatura";
			if($nuevo){
				if($competencia->save()){
					$extra['id_competencia'] = $competencia->id_competencia;
					$data->setSuccessMessage('Competencia guardada correctamente');
				}else{
					$data->setErrorMessage($competencia->obtenerError());
				}
			}else{
				if($competencia->update()){
					$extra['id_competencia'] = $competencia->id_competencia;
					$data->setSuccessMessage('Competencia actualizada correctamente');
				}else{
					$data->setErrorMessage($competencia->obtenerError());
				}
			}
		}

		$res->json($data->forJSON($extra));
	}

	public function FormularioFase2Action(Request $req, Response $res, Data $data){
		$idCompetencia = $data->fromBody('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);

		$data->addToBody('competencia', $competencia);
		$this->render('asistente-generador/c-asignatura/formulario_fase2_competencia', $data);
	}

	public function ElementosCompetenciaAction(Request $req, Response $res, Data $data){
		$id = $data->fromBody('id_competencia');
		$db = new DBHelper();
		$sqlVerbos = "select v.id_verbo, v.descrip_verbo from ".TblVerbo::$table." v inner join ".TblVerboCompetencia::$table." vc on v.id_verbo = vc.id_verbo where vc.id_competencia = ?;";
		$verbos = $db->read($sqlVerbos, [$id]);

		$sqlContenidos = "select c.id_contenido, c.descrip_contenido from ".TblContenido::$table." c inner join ".TblContenidoCompetencia::$table." cc on c.id_contenido = cc.id_contenido where cc.id_competencia = ?;";
		$contenidos = $db->read($sqlContenidos, [$id]);

		$sqlContextos = "select c.id_contexto, c.descrip_contexto from ".TblContexto::$table." c inner join ".TblContextoCompetencia::$table." cc on c.id_contexto = cc.id_contexto where cc.id_competencia = ?;";
		$contextos = $db->read($sqlContextos, [$id]);

		$sqlCriterios = "select c.id_criterio, c.descrip_criterio from ".TblCriterio::$table." c inner join ".TblCriterioCompetencia::$table." cc on c.id_criterio = cc.id_criterio where cc.id_competencia = ?;";
		$criterios = $db->read($sqlCriterios, [$id]);

		$resultado = [
			'verbos' => $verbos,
			'contenidos' => $contenidos,
			'contextos' => $contextos,
			'criterios' => $criterios,
			'competencia' => TblCompetencia::findById($id),
		];

		$data->setSuccessMessage('Elementos obtenidos correctamente');
		$res->json($data->forJSON($resultado));
	}

	public function GuardarFase2Action(Request $req, Response $res, Data $data){
		$idCompetencia = $data->fromBody('id_competencia');
		$competenciaEditable = $data->fromBody('competencia_editable');
		$competencia = TblCompetencia::findById($idCompetencia);
		if(!$competencia || $competencia->id_competencia == 0){
			$data->setErrorMessage('No se ha encontrado la competencia solicitada');
			$res->json($data->forJSON());
		}
		
		// array de objetos TblVerboCompetencia
		$verbosGuardados = TblVerboCompetencia::findAll(['where'=>['id_competencia'=>$idCompetencia]]);
		// array de objetos {id_verbo,label, value }
		$verbosRecibidos = $req->postArray('verbos');

		// array de objetos TblContenidoCompetencia
		$contenidosGuardados = TblContenidoCompetencia::findAll(['where'=>['id_competencia'=>$idCompetencia]]);
		// array de objetos {id_contenido,label, value }
		$contenidosRecibidos = $req->postArray('contenidos');

		// array de objetos TblContextoCompetencia
		$contextosGuardados = TblContextoCompetencia::findAll(['where'=>['id_competencia'=>$idCompetencia]]);
		// array de objetos {id_contexto,label, value }
		$contextosRecibidos = $req->postArray('contextos');

		// array de objetos TblCriterioCompetencia
		$criteriosGuardados = TblCriterioCompetencia::findAll(['where'=>['id_competencia'=>$idCompetencia]]);
		// array de objetos {id_criterio,label, value }
		$criteriosRecibidos = $req->postArray('criterios');

		// iniciamos una transaccion para todo el proceso
		$db = new DBHelper();
		$db->beginTransaction();

		$data->error = false;
		$verbosOk = $this->verbosCompetencia($idCompetencia, $verbosRecibidos, $verbosGuardados, $db);
		if(!$verbosOk){
			$data->setErrorMessage($this->error_fase);
			$db->rollBack();
		}else{
			$contenidosOk = $this->contenidosCompetencia($idCompetencia, $contenidosRecibidos, $contenidosGuardados, $db);
			if(!$contenidosOk){
				$data->setErrorMessage($this->error_fase);
				$db->rollBack();
			}else{
				$contextosOk = $this->contextosCompetencia($idCompetencia, $contextosRecibidos, $contextosGuardados, $db);
				if(!$contextosOk){
					$data->setErrorMessage($this->error_fase);
					$db->rollBack();
				}else{
					$criteriosOk = $this->criteriosCompetencia($idCompetencia, $criteriosRecibidos, $criteriosGuardados, $db);
					if(!$criteriosOk){
						$data->setErrorMessage($this->error_fase);
						$db->rollBack();
					}
				}
			}
		}

		if(!$data->isError()){
			$competencia->competencia_editable = $competenciaEditable;
			
			if($competencia->etapa_actual < 2){
				$competencia->etapa_actual = 2;
			}

			if($competencia->update(null,$db)){
				$db->commit();
				$data->setSuccessMessage('La competencia fue actualizada correctamente');
			}else{
				$db->rollBack();
				$data->setErrorMessage($competencia->getError());
			}

		}


		$res->json($data->forJSON());
	}


	public function FormularioFase3Action(Request $req, Response $res, Data $data){
		$idCompetencia = $data->fromBody('id_competencia');

		$competencia = TblCompetencia::findById($idCompetencia);

		$data->addToBody('competencia', $competencia);
		$this->render('asistente-generador/c-asignatura/formulario_fase3_desglose', $data);
	}


	public function GuardarFase3Action(Request $req, Response $res, Data $data){

		$idCompetencia = $data->fromBody('id_competencia');
		$conocimientos = $req->postArray('conocimientos');
		$habilidades = $req->postArray('habilidades');
		$avs = $req->postArray('avs');

		// primero analizamos la competencia, para saber que si existe
		$competencia = TblCompetencia::findById($idCompetencia);
		if($competencia){

			$db = new DBHelper();
			$db->beginTransaction();

			$ok1 = guardarConocimientosCompetencia($idCompetencia, $competencia->etapa_actual < 3, $conocimientos, $this->user->id, $db);
			$ok2 = guardarHabilidadesCompetencia($idCompetencia, $competencia->etapa_actual < 3, $habilidades, $this->user->id, $db);
			$ok3 = guardarActitudesValoresCompetencia($idCompetencia, $competencia->etapa_actual < 3, $avs, $this->user->id, $db);
			$tmp = "";//"$ok1 - $ok2 - $ok3 ";
			$ok = $ok1 == true && $ok2 == true && $ok3 == true; // no son necesariamento booleanos, por eso se hace esa comparación
			if($ok){
				if($competencia->etapa_actual<3){
					$competencia->etapa_actual = 3;
					if(!$competencia->update(null, $db)){
						$ok = false;
					}
				}

				if($ok){
					$db->commit();
					$data->setSuccessMessage("Etapa guardada correctamente. $tmp");
				}else{
					$db->rollBack();
					$data->setErrorMessage("Ha ocurrido un error al intentar guardar esta etapa 1. $tmp");
				}

			}else{
				$db->rollBack();
				$data->setErrorMessage("Ha ocurrido un error al intentar guardar esta etapa 2 $tmp");
			}
		}else{
			$data->setErrorMessage("No se ha encontrado la competencia a actualizar. $tmp");
		}
		
		// $data->setErrorMessage('En proceso');
		$res->json($data->forJSON());
	}
	
	
	

	public function FormularioFase4Action(Request $req, Response $res, Data $data){
		
		$idCompetencia = $data->fromBody('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);



		$asignaturaPe = TblAsignaturaPe::findById($competencia->id_asignatura_pe);
		$asignatura = TblAsignatura::findById($asignaturaPe->id_asignatura,  ['select'=>['nombre_asignatura','tipo_asignatura','modalidad','num_unidades']]);
		$cgs = [];
		$db = new DBHelper();
		if($asignatura->tipo_asignatura == 'obligatoria'){
			$cgsIds = TblMallaCurricular::findAll([
					'where' => [
						'id_asignatura_pe' => $asignaturaPe->id_asignatura_pe,
					],
					'select' => ['id_cg','unidades']
				]);

			$values = [];//array_values($cgsIds);

			foreach ($cgsIds as $item) {
				$values[] = $item->id_cg;
			}

			$cgs = $db->read("select * from ".TblCG::$table." where id_cg in (".implode(",", $values).");");

		}else{

			$cgsIds = TblMallaCurricular::findAll([
					'where' => ['id_competencia' => $idCompetencia],
					'select' => ['id_cg', 'unidades']
				]);

			$data->addToBody('cgsIds', $cgsIds);

			$cgs = $db->read("select * from ".TblCG::$table);
		}


		$data->addToBody('competencia', $competencia);
		$data->addToBody('asignaturaPe', $asignaturaPe);
		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('cgs', $cgs);

		$this->render('asistente-generador/c-asignatura/formulario_fase4_cgs', $data);
	}


	public function GuardarFase4Action(Request $req, Response $res, Data $data){
		
		$idCgs = $req->postArray('ids_cgs', []);
		$idCompetencia = $req->post('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);

		if($competencia){
			if($competencia->tipo_competencia == 'asignatura'){
				$asignaturaPe = TblAsignaturaPe::findById($competencia->id_asignatura_pe);
				$asignatura = TblAsignatura::findById($asignaturaPe->id_asignatura);
				
				if($asignatura->tipo_asignatura != 'obligatoria'){
					if(count($idCgs)>0){
						$dbt = new DBHelper();
						$dbt->beginTransaction();
						$error = false;
						foreach ($idCgs as $itemCg) {
							if(count($itemCg['unidades'])==0){
								$error = true;
								$data->setErrorMessage('Todas las competencias genéricas seleccionadas deben incluir al menos una unidad');
								break;
							}

							$malla = TblMallaCurricular::findById(['id_asignatura_pe'=>$competencia->id_asignatura_pe,'id_cg'=>$itemCg['cg']]);
							if($malla){
								$malla->unidades = implode(",", $itemCg["unidades"]);
								if(!$malla->update($dbt)){
									$error = true;
									$data->setErrorMessage('Ha ocurrido un error al actualizar un registro. '.$malla->getError());
								}
							}else{
								$malla = new TblMallaCurricular();
								$malla->id_usuario = $this->user->id;
								$malla->id_competencia = $idCompetencia;
								$malla->id_asignatura_pe = $competencia->id_asignatura_pe;
								$malla->id_cg = $itemCg['cg'];
								$malla->unidades = implode(",", $itemCg["unidades"]);


								if(!$malla->save($dbt)){
									$error = true;
									$data->setErrorMessage('Ha ocurrido un error. '.$malla->getError());
								}
							}
						}

						if(!$error){
							$competencia->etapa_actual = max($competencia->etapa_actual, 4);
							if($competencia->update($dbt)){
								$dbt->commit();
								$data->setSuccessMessage('Competencia actualizada correctamente');
							}else{
								$dbt->rollBack();
								$data->setErrorMessage('Ha ocurrido un error. '.$competencia->getError());
							}
						}else{
							$dbt->rollBack();
						}
					}else{
						$data->setErrorMessage('Debes incluir al menos una competencia genérica');
					}
				}else{
					$competencia->etapa_actual = max($competencia->etapa_actual, 4);
					if($competencia->update()){
						$data->setSuccessMessage('Competencia actualizada correctamente');
					}else{
						$data->setErrorMessage('Ha ocurrido un error. '.$competencia->getError());
					}
				}
			}else{
				$data->setErrorMessage('Esta competencia no es de asignatura');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la competencia solicitada');
		}

		$res->json($data->forJSON());
	}
	
	

	/**
	 * Actualiza la lista de verbos que corresponden a una competencia
	 * @param  Int $idCompetencia        Id de la competencia en bd
	 * @param  array $nuevos     Nuevos verbos ['id_verbo','label','value']
	 * @param  array $existentes Objetos TblVerboCompetencia asociados a la competencia
	 * @param  Object $dbt        Transaccion existente
	 * @return Boolean             en caso que todo fue realizado correctamente
	 */
	private function verbosCompetencia($idCompetencia, $nuevos, $existentes, $dbt){

		$agregar = [];
		$eliminar = [];
		// buscamos los que agregaremos
		foreach ($nuevos as $key => $nuevoVC) {
			$encontrado = false;
			foreach ($existentes as $key => $vc) {
				if($vc->id_verbo == $nuevoVC['id_verbo']){
					$encontrado = true;
					break;
				}
			}
			if(!$encontrado){
				$agregar[] = new TblVerboCompetencia(['id_verbo'=>$nuevoVC['id_verbo'],'id_competencia'=>$idCompetencia]);
			}
		}

		// buscamos los que eliminaremos
		foreach ($existentes as $key => $vc) {
			$encontrado = false;
			foreach ($nuevos as $key => $nuevoVC) {
				if($nuevoVC['id_verbo'] == $vc->id_verbo){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$eliminar[] = $vc;
			}
		}


		foreach ($agregar as $key => $vc) {
			if(!$vc->save($dbt)){
				$this->error_fase = $vc->obtenerError();
				return false;
			}
		}

		foreach ($eliminar as $key => $vc) {
			if(!$vc->delete($dbt)){
				$this->error_fase = $vc->obtenerError();
				return false;
			}
		}

		return true;
	}

	private function contenidosCompetencia($idCompetencia, $nuevos, $existentes, $dbt){

		$agregar = [];
		$eliminar = [];

		// buscamos los que agregaremos
		foreach ($nuevos as $key => $nuevo) {
			
			$encontrado = false;
			foreach ($existentes as $key => $object) {
				if($object->id_contenido == $nuevo['id_contenido']){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$agregar[] = new TblContenidoCompetencia(['id_contenido'=>$nuevo['id_contenido'],'id_competencia'=>$idCompetencia]);
			}
		}

		// buscamos los que eliminaremos
		foreach ($existentes as $key => $object) {
			$encontrado = false;
			foreach ($nuevos as $key => $nuevo) {
				if($nuevo['id_contenido'] == $object->id_contenido){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$eliminar[] = $object;
			}
		}

		foreach ($agregar as $key => $object) {
			if(!$object->save($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		foreach ($eliminar as $key => $object) {
			if(!$object->delete($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		return true;
	}

	private function contextosCompetencia($idCompetencia, $nuevos, $existentes, $dbt){

		$agregar = [];
		$eliminar = [];

		// buscamos los que agregaremos
		foreach ($nuevos as $key => $nuevo) {
			$encontrado = false;
			foreach ($existentes as $key => $object) {
				if($object->id_contexto == $nuevo['id_contexto']){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$agregar[] = new TblContextoCompetencia(['id_contexto'=>$nuevo['id_contexto'],'id_competencia'=>$idCompetencia]);
			}
		}

		// buscamos los que eliminaremos
		foreach ($existentes as $key => $object) {
			$encontrado = false;
			foreach ($nuevos as $key => $nuevo) {
				if($nuevo['id_contexto'] == $object->id_contexto){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$eliminar[] = $object;
			}
		}

		foreach ($agregar as $key => $object) {
			if(!$object->save($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		foreach ($eliminar as $key => $object) {
			if(!$object->delete($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		return true;
	}

	private function criteriosCompetencia($idCompetencia, $nuevos, $existentes, $dbt){

		$agregar = [];
		$eliminar = [];

		// buscamos los que agregaremos
		foreach ($nuevos as $key => $nuevo) {
			$encontrado = false;
			foreach ($existentes as $key => $object) {
				if($object->id_criterio == $nuevo['id_criterio']){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$agregar[] = new TblCriterioCompetencia(['id_criterio'=>$nuevo['id_criterio'],'id_competencia'=>$idCompetencia]);
			}
		}

		// buscamos los que eliminaremos
		foreach ($existentes as $key => $object) {
			$encontrado = false;
			foreach ($nuevos as $key => $nuevo) {
				if($nuevo['id_criterio'] == $object->id_criterio){
					$encontrado = true;
					break;
				}
			}

			if(!$encontrado){
				$eliminar[] = $object;
			}
		}

		foreach ($agregar as $key => $object) {
			if(!$object->save($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		foreach ($eliminar as $key => $object) {
			if(!$object->delete($dbt)){
				$this->error_fase = $object->obtenerError();
				return false;
			}
		}

		return true;
	}



	public function ConocimientosCompetenciaAction(Request $req, Response $res, Data $data){
		
		$idCompetencia = $data->fromBody('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);
		$extra = [];
		if($competencia->etapa_actual < 3){
			// debemos obtener las recomendaciones
			// $sql = "select descrip_conocimiento, id_compo_conocimiento from ".TblCompo_conocimiento::$table." limit 5;";
			// $extra = $db->read($sql);
			// 
			$extra = recomendacionesConocimientos($competencia);
			$data->setSuccessMessage('Recomendaciones de conocimientos obtenidas');
		}else{
			// simplemente debeomos leer del registro
			$db = new DBHelper();
			$sql = "select c.descrip_conocimiento, c.id_compo_conocimiento from ".TblCompo_conocimiento::$table." c inner join ".TblConocimientoCompetencia::$table." cc on cc.id_compo_conocimiento=c.id_compo_conocimiento where cc.id_competencia=?;";
			$extra = $db->read($sql, [$idCompetencia]);
			$data->setSuccessMessage('Conocimientos obtenidos');
		}
		
		$res->json($data->forJSON($extra));
	}
	


	public function HabilidadesCompetenciaAction(Request $req, Response $res, Data $data){
		$idCompetencia = $data->fromBody('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);
		$extra = [];
		if($competencia->etapa_actual < 3){
			// debemos obtener las recomendaciones
			// $sql = "select descrip_habilidad, id_compo_habilidad from TblCompo_habilidad limit 5;";
			// $extra = $db->read($sql);
			$extra = recomendacionesHabilidades($competencia);
			$data->setSuccessMessage('Ok');
		}else{
			// simplemente debeomos leer del registro
			$db = new DBHelper();
			$sql = "select c.descrip_habilidad, c.id_compo_habilidad from ".TblCompo_habilidad::$table." c inner join ".TblHabilidadCompetencia::$table." cc on cc.id_compo_habilidad=c.id_compo_habilidad where cc.id_competencia=?;";
			$extra = $db->read($sql, [$idCompetencia]);
			$data->setSuccessMessage('Habilidades guardadas obtenidas');
		}
		// $data->setErrorMessage('En creacion');
		$res->json($data->forJSON($extra));
	}


	public function AVsCompetenciaAction(Request $req, Response $res, Data $data){
		
		$idCompetencia = $data->fromBody('id_competencia');
		$competencia = TblCompetencia::findById($idCompetencia);

		$extra = [];
		if($competencia->etapa_actual < 3){
			// debemos obtener las recomendaciones
			// $sql = "select descrip_actitud_valor, id_compo_valor from TblCompo_actitud_valor limit 5;";
			// $extra = $db->read($sql);
			$extra = recomendacionesAVs($competencia);
			$data->setSuccessMessage('Ok');
		}else{
			// simplemente debeomos leer del registro
			$db = new DBHelper();
			$sql = "select c.descrip_actitud_valor, cc.id_compo_actitud_valor from ".TblCompo_actitud_valor::$table." c inner join ".TblActitudValorCompetencia::$table." cc on cc.id_compo_actitud_valor=c.id_compo_valor where cc.id_competencia=?;";
			$extra = $db->read($sql, [$idCompetencia]);
			$data->setSuccessMessage('Actitudes y valores obtenidas guardadas obtenidas. '.$db->error_db);
		}
		// $data->setErrorMessage('En creacion');
		$res->json($data->forJSON($extra));
	}


}