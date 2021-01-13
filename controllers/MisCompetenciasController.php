<?php 

class MisCompetenciasController extends BaseController {


	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('mis-competencias/index', $data);
	}


	public function ListadoAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page', 1);
		$count = $req->getIfExists('count', 10);
		$limit = $count;
		$offset = $count * ($page - 1); 
		$finalizado = $req->getIfExists('finalizado', 0);

		$asignaturas = TblUsuarioAsignatura::findAll(['select' => ['id_asignatura']]);
		$or = [];
		foreach ($asignaturas as $key => $value) {
			$or[] = ['id_asignatura' => $value->id_asignatura ];
		}

		$or[] = ['id_usuario' => $this->user->id];
		

		$criteria = [
			'select' => ['id_competencia','id_asignatura','etapa_actual','tipo_competencia','num_unidad'],
			'where' => [
				'tipo_competencia' => 'resultado',
				'finalizado' => $finalizado == 1,
				'or' => $or,
			],
			'include' => [
				[ 'localField' => 'id_asignatura','select' => ['nombre_asignatura'] ],
			],
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'TblCompetencia.fecha_actualizacion desc'
		];

		$this->result = TblCompetencia::findAndCountAll($criteria);
		foreach ($this->result['rows'] as $index => $row) {
			$unidad = TblUnidad_asignatura::findOne([
				'where' => [
					'id_asignatura' => $row->id_asignatura,
					'num_unidad' => $row->num_unidad
				]
			]);

			$row->nombre_unidad = $unidad ? $unidad->nombre_unidad : '-';
			$row->secuencias_contenido = [];
			$tempc = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $row->id_competencia,
				],
				'include' => [
					['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido']]
				],
				'order' => 'detalle_secuencia_contenido asc',
			]);

			foreach ($tempc as $index2 => $contt) {
				$row->secuencias_contenido[] = $contt->detalle_secuencia_contenido;
			}
		}

		$this->page = $page;
		$this->limit = $limit;
		$this->count = $count;

		$this->render('mis-competencias/listado', $data);
	}


	public function DatosCompetenciaAction(Request $req, Response $res, Data $data){
		
		$idCompetencia = $req->getIfExists('id',0);
		$select = ['id_competencia','id_competencia_padre','descripcion','etapa_actual','tipo_competencia','id_asignatura_pe','competencia_editable','num_unidad'];
		$competencia = TblCompetencia::findById($idCompetencia, ['select'=>$select]);

		if($competencia->tipo_competencia == 'asignatura'){

			$db = new DBHelper();

			$asignatura = $db->read('select a.id_asignatura, a.nombre_asignatura, ape.num_unidades from tblasignatura a inner join tblasignatura_pe ape on a.id_asignatura = ape.id_asignatura where ape.id_asignatura_pe = ?;', [$competencia->id_asignatura_pe], true);
			$competencia->Asignatura = $asignatura ? $asignatura : [];
			
			$competenciasUnidad = TblCompetencia::findAll([
					'where' => [
						'id_competencia_padre' => $idCompetencia,
						'tipo_competencia' => 'unidad'
					],
					'select' => $select
				]);

			foreach ($competenciasUnidad as $index => $compUnidad) {
				$competenciasUnidad[$index]->CompetenciasResultado = TblCompetencia::findAll([
						'where' => [
							'id_competencia_padre' => $compUnidad->id_competencia,
							'tipo_competencia' => 'resultado'
						],
						'select' => $select,
					]);
			}

			$competencia->CompetenciasUnidad = $competenciasUnidad;
		}
		
		// $this->competencia = $competencia;
		$res->json($data->forJSON($competencia));
	}

	public function PerfilAction(Request $req, Response $res, Data $data){
		$this->idCompetencia = $req->getIfExists('id',0);
		$this->frm = $req->getIfExists('frm',0) == 0 ? false : true;

		$this->render('mis-competencias/perfil', $data);
	}

	public function GuardarAction(Request $req, Response $res, $args){
		$resp = ['estado'=>false,'mensaje'=>''];
		if($args['request_estatus']){
			
			$id         	 = $req->existsPost('id') ? $req->post('id') : 0;
			$verbos     	 = $args['body']['verbos'];	
			$contenidos 	 = $args['body']['contenidos'];
			$contextos       = $args['body']['contextos'];
			$criterios       = $args['body']['criterios'];

			// array de elementos que ya existen el la bd
			$stackVerbos     = [];
			$stackContenidos = [];
			$stackContextos  = [];
			$stackCriterios  = [];

			$usuario 		 = Sesion::obtener();
			$idUsuario 		 = $usuario->id_usuario;

			$db = new DBHelper();
			$db->beginTransaction(); // iniciamos una transaccion
			//-----------------------------------------------------
			
			// primero verificamos los verbos, y si alguno no existe, entonces lo creamos
			$respVerbos = $this->verificarYGuardarVerbos($verbos, $idUsuario, $db);
			if($respVerbos['ok']){
				$stackVerbos = $respVerbos['lista'];
			}else{
				$db->rollBack();
				$resp['mensaje'] = $respVerbos['mensaje'];
				$res->json($resp);
			}


			// 2do. verificamos los contenidos
			$respContenidos = $this->verificarYGuardarContenidos($contenidos, $idUsuario, $db);
			if($respContenidos['ok']){
				$stackContenidos = $respContenidos['lista'];
			}else{
				$db->rollBack();
				$resp['mensaje'] = $respContenidos['mensaje'];
				$res->json($resp);
			}

			// 3ro. Verificamos los contextos
			$respContextos = $this->verificarYGuardarContextos($contextos, $idUsuario, $db);
			if($respContextos['ok']){
				$stackContextos = $respContextos['lista'];
			}else{
				$db->rollBack();
				$resp['mensaje'] = $respContextos['mensaje'];
				$res->json($resp);
			}


			// 4to. Verificamos los criterios
			$respCriterios = $this->verificarYGuardarCriterios($criterios, $idUsuario, $db);
			if($respCriterios['ok']){
				$stackCriterios = $respCriterios['lista'];
			}else{
				$db->rollBack();
				$resp['mensaje'] = $respCriterios['mensaje'];
				$res->json($resp);
			}

		}else{
			$resp['mensaje']='Algunos campos no estan completos';
		}

		$res->json($resp);
	}

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id');
		$competencia = TblCompetencia::findOne([
			'where' => [
				'id_competencia' => $id,
				'tipo_competencia' => 'resultado',
			]
		]);

		if($competencia){
			$db = new DBHelper();
			$db->beginTransaction();
			try{

				if($competencia->delete($db)){
					$db->commit();
					$res->json([
						'estado' => true,
						'mensaje' => 'Resultado de aprendizaje eliminado correctamente',
						'data' => $competencia,
						'error' => $competencia->getError(),
					]);
				}else{
					$db->rollBack();
					$res->json([
						'estado' => false,
						'mensaje' => 'No se pudo eliminar el resultado de aprendizaje',
						'dev' => $competencia->getError(),
					]);
				}
			}catch(Exception $ex){
				$db->rollBack();
				$res->json([
					'estado' => false,
					'mensaje' => 'Ha ocurrido un error al intentar eliminar el resultado de aprendizaje',
					'dev' => $ex->getMessage(),
				]);
			}
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado el resultado de aprendizaje solicitado',
			]);
		}
	}

	// -----------------------------------------------------------------------------
	// METODOS PRIVADOS PARA PROCESAR LA CREACION DE LOS VERBOS

	private function verificarYGuardarVerbos($verbos, $idUsuario, $db){
		// la estructura de cada 
		$stack = [];
		$ok = true;
		$msj = "";

		if(count($verbos)>0){

			foreach ($verbos as $index => $verbo) {
				$objVerbo = null;
				$tmpVerbo = null;
				if($verbo['id_verbo'] != 0){
					$tmpVerbo == TblVerbo::findById($verbo['id_verbo'], $db);
				}else{
					$tmpVerbo = TblVerbo::findOne(['where'=>['descrip_verbo'=>$verbo['value']]], $db);
				}

				// significa que fue encontrado
				if($tmpVerbo){
					$objVerbo = $tmpVerbo;
				}else{
					// no fue encontrado, entonces, lo creamos
					$nuevoVerbo = new TblVerbo(['id_verbo'=>0, 'descrip_verbo'=>$verbo['value'], 'tipo_saber_verbo'=>"conocimiento", 'disponible'=>true]);
					$nuevoVerbo->id_usuario = $idUsuario;
					if($nuevoVerbo->save($db)){
						$objVerbo = $nuevoVerbo;
					}else{
						$ok = false;
						$msj = $nuevoVerbo->getError();
						break;
					}
				}

				if($objVerbo == null || !$objVerbo->disponible){
					$ok = false;
					$msj = "El verbo que estas utilizando no se encuentra disponible para utilizar en la creación de una competencia";
					break;
				}

				array_push($stack, $objVerbo);

			}

			if($ok){
				$msj = "Todos los verbos fueron bien analizados, sin nigun rechazo";
			}

		}else{
			$ok = false;
			$msj = "No se han encontrado verbos que incluir en la competencia";
		}

		return ['ok'=>$ok,'mensaje'=>$msj,'lista'=>$stack];
	}
	

	private function verificarYGuardarContenidos($contenidos, $idUsuario, $db){
		$stack = [];
		$ok = true;
		$msj = "";

		if(count($contenidos)>0){
			foreach ($contenidos as $index => $contenido) {
				$objContenido = null;
				$tmpContenido = null;
				if($contenido['id_contenido'] != 0){
					$tmpContenido == TblContenido::findById($contenido['id_contenido'], $db);
				}else{
					$tmpContenido = TblContenido::findOne(['where'=> ['descrip_contenido'=>$contenido['value']]] , $db);
				}

				// significa que fue encontrado
				if($tmpContenido){
					$objContenido = $tmpContenido;
				}else{
					// no fue encontrado, entonces, lo creamos
					$nuevoContenido = new TblContenido(['id_contenido'=>0, 'descrip_contenido'=>$contenido['value']]);
					$nuevoContenido->id_usuario = $idUsuario;
					if($nuevoContenido->save($db)){
						$objContenido = $nuevoContenido;
					}else{
						$ok = false;
						$msj = $nuevoContenido->getError();
						break;
					}
				}

				array_push($stack, $objContenido);
			}

			if($ok){
				$msj = "Todos los contenidos fueron bien analizados, no hubo rechazos.";
			}
		}else{
			$ok = false;
			$msj = "No se han encontrado contenidos para incluir en la competencia.";
		}

		return ['ok'=>$ok,'mensaje'=>$msj,'lista'=>$stack];
	}
	
	private function verificarYGuardarContextos($contextos, $idUsuario, $db){
		$stack = [];
		$ok = true;
		$msj = "";

		if(count($contextos)>0){
			foreach ($contextos as $index => $contexto) {
				$objContexto = null;
				$tmpContexto = null;
				if($contexto['id_contexto'] != 0){
					$tmpContexto == TblContexto::findById($contexto['id_contexto'], $db);
				}else{
					$tmpContexto = TblContexto::findOne(['where'=>$contexto['value']], $db);
				}

				// significa que fue encontrado
				if($tmpContexto){
					$objContexto = $tmpContexto;
				}else{
					// no fue encontrado, entonces, lo creamos
					$nuevoContexto = new TblContexto(['id_contexto'=>0, 'descrip_contexto'=>$contexto['value']]);
					$nuevoContexto->id_usuario = $idUsuario;
					if($nuevoContexto->save($db)){
						$objContexto = $nuevoContexto;
					}else{
						$ok = false;
						$msj = $nuevoContexto->getError();
						break;
					}
				}

				array_push($stack, $objContexto);
			}

			if($ok){
				$msj = "Todos los contextos fueron bien analizados, sin nigun rechazo.";
			}
		}else{
			$ok = false;
			$msj = "No se han encontrado contextos que incluir en la competencia.";
		}

		return ['ok'=>$ok,'mensaje'=>$msj,'lista'=>$stack];
	}

	private function verificarYGuardarCriterios($criterios, $idUsuario, $db){
		
	}


}