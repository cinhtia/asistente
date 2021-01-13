<?php
use TblCompetenciaGenericaAsignatura as TblCGA;
class AsistenteResultadoController extends BaseController{

	public function IndexFormResultadoAction(Request $req, Response $res, Data $data){
		$idCUnidad = $req->getIfExists('id_competencia_unidad', 0);		
		$this->competencia_unidad = $idCUnidad != 0 ? TblCompetencia::findById($idCUnidad) : (new TblCompetencia());
		$this->unidad = $this->competencia_unidad->num_unidad;
		$this->asignaturaPe = $idCUnidad == 0 ? (new TblAsignaturaPe()) : TblAsignaturaPe::findById($this->competencia_unidad->id_asignatura_pe);
		$this->asignatura = $idCUnidad == 0 ? ( new TblAsignatura() ) : TblAsignatura::findById($this->asignaturaPe->id_asignatura);
		$this->num_resultado = $this->unidad.'.'.'1';

		$criteria = [
			'where' => [
				'id_competencia_padre' => $this->competencia_unidad->id_competencia,
			],
			'select' => ['id_competencia','descripcion','num_unidad','num_resultado']
		];

		$this->competenciasResultado = [];// TblCompetencia::findAll($criteria);
		$conteo = 0;//count($this->competenciasResultado);

		if($conteo>0){
			$this->num_resultado = $this->unidad.'.'.($conteo+1);
		}

		$idCR = $req->getIfExists('id_competencia_resultado', 0);
		$this->competencia_resultado = new TblCompetencia();
		if($idCR != 0){
			$this->competencia_resultado = TblCompetencia::findById($req->get('id_competencia_resultado'));
		}else{
			$this->competencia_resultado->id_competencia = 0;
			$this->competencia_resultado->id_competencia_padre = $this->competencia_unidad->id_competencia;
			$this->competencia_resultado->num_unidad = $this->unidad;
			$this->competencia_resultado->tipo_competencia = 'resultado';
			$this->competencia_resultado->etapa_actual = 0;
			$this->competencia_resultado->num_resultado = $conteo+1;
		}
	
		$this->render('asistente-generador/c-resultado/index', $data);
	}
	
	public function Form1Action(Request $req, Response $res, Data $data){
		$idCP = $req->getIfExists('id_competencia_padre', 0);
		$idCR = $req->getIfExists('id_competencia_resultado', 0);
		$tmpAsigns = TblAsignatura::findAll(['select'=>['id_asignatura','nombre_asignatura','num_unidades','tipo_asignatura']]);
		$this->asignaturas = [];
		foreach ($tmpAsigns as $item) {
			$this->asignaturas[] = [
				'id_asignatura'=>$item->id_asignatura,
				'nombre_asignatura' => $item->nombre_asignatura,
				'num_unidades' => $item->num_unidades,
				'tipo_asignatura' => $item->tipo_asignatura
			];
		}
		$this->competencia_unidad = new TblCompetencia(['id_competencia'=>0]);
		$this->competencia_resultado = new TblCompetencia(['id_competencia'=>0]);
		if($idCP != 0) $this->competencia_unidad = TblCompetencia::findById($idCP);
		
		$this->competencia_resultado->id_competencia_padre = $this->competencia_unidad->id_competencia;
		$this->competencia_resultado->num_unidad = $this->competencia_unidad->num_unidad;

		$this->contenidos_unidad = [];
		if($idCR != 0){
			$this->competencia_resultado = TblCompetencia::findById($idCR);
			$this->contenidos_unidad = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $this->competencia_resultado->id_competencia,
				]
			]);
		}

		$this->render('asistente-generador/c-resultado/form1', $data);
	}

	public function CompetenciasResultadoMismaUnidadAction(Request $req, Response $res, Data $data){
		$id_asignatura = $req->getIfExists('id_asignatura', null);
		$num_unidad = $req->getIfExists('num_unidad', null);

		$asignatura = TblAsignatura::findById($id_asignatura);
		$unidad = TblUnidad_asignatura::findOne([
			'where' => [
				'id_asignatura' => $id_asignatura,
				'num_unidad' => $num_unidad,
			]
		]);

		$competencias_resultado = TblCompetencia::findAll([
			'where' => [
				'id_asignatura' => $id_asignatura,
				'tipo_competencia' => 'resultado',
				'num_unidad' => $num_unidad,
			]
		]);

		foreach ($competencias_resultado as $index => $competencia) {
			$competencia->Usuario = TblUsuario::findOne([
				'where' => [
					'id_usuario' => $competencia->id_usuario
				],
				'select' => ['id_usuario','nombre']
			]);

			$competencia->Contenidos = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $competencia->id_competencia,
				],
				'include' => [
					['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido'] ]
				]
			]);
		}


		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('unidad', $unidad);
		$data->addToBody('competencias_resultado', $competencias_resultado);

		$this->render('asistente-generador/c-resultado/modal_resultados_unidad', $data);

	}

	public function GuardarFase1Action(Request $req, Response $res, Data $data){

		$id_competencia_padre 		= $req->post('id_competencia_padre');
		$id_competencia_resultado 	= $req->post('id_competencia_resultado');
		$id_asignatura 				= $req->post('id_asignatura');
		$num_unidad 				= $req->post('num_unidad');
		$descripcion 				= $req->post('descripcion');
		$contenidos_unidad          = $req->postArray('contenidos_unidad');

		if(count($contenidos_unidad) == 0){
			$data->setErrorMessage('Debes seleccionar al menos un contenido de la unidad a evaluar');
			$res->json($data->forJSON());
		}

		$competencia = new TblCompetencia();
		if($id_competencia_resultado != 0){
			$competencia = TblCompetencia::findById($id_competencia_resultado);
			
			// if($competencia->id_asignatura_pe != $id_asignatura_pe){
			// PREGUNTAR SI ESTO VA A PASAR, O LO MAS SIMPLE ES BLOQUEAR LA EDICION DE ESTOS CAMPOS
			// }

			if($competencia){
				$competencia->descripcion = $descripcion;
				$db = new DBHelper();
				$db->beginTransaction();

				$contenidos = TblCompetenciaContenidoUnidad::findAll([
					'where' => [
						'id_competencia' => $competencia->id_competencia,
					]
				], $db);

				// agregamos los nuevos
				$error = false;
				foreach ($contenidos_unidad as $cUnidadId) {
					$encontrado = false;
					foreach ($contenidos as $contenido) {
						if($contenido->id_contenido_unidad == $cUnidadId){
							$encontrado = true;
							break;
						}
					}

					if(!$encontrado){
						$ccu = new TblCompetenciaContenidoUnidad([
							'id_competencia' => $competencia->id_competencia,
							'id_contenido_unidad' => $cUnidadId,
						]);

						if(!$ccu->save($db)){
							$error = false;
							break;
						}
					}
				}

				// ahoramos buscamos aquellos que ya no se seleccionaron
				if(!$error){
					foreach ($contenidos as $contenido) {
						if(!in_array($contenido->id_contenido_unidad, $contenidos_unidad)){
							if(!$contenido->delete($db)){
								$error = true;
								break;
							}
						}
					}
				}

				if($error){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al actualizar los datos básicos de la competencia');
				}else{
					if($competencia->update(null, $db)){
						$db->commit();
						$data->setSuccessMessage('La competencia ha sido guardada correctamente');
					}else{
						$data->setErrorMessage('Ha ocurrido un error al guardar. '.$competencia->getError());
					}
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la competencia solicitada');
			}
		}else{
			
			// no se puede asignar una competencia padre porque no es posible saber hasta
			// este momento si se trata de una competencia de unidad, en cuyo caso
			// la competencia padre seria una de asignatura
			// pero si es de resultado, la competencia padre seria de unidad
			// $competenciaPadre = TblCompetencia::findById($id_competencia_padre);
			// $competencia->id_competencia_padre = $id_competencia_padre;
			
			// lo mismo pasa con el numero de resultado
			// $competencia->num_resultado = $num_resultado;

			// ======> YA NO SE VALIDA QUE NO SE REPITA LA COMPETENCIA DE RESULTADO
				// comprobamos que no exista otra competencia con la misma asignatura_pe
				// $existente = TblCompetencia::findOne([
				// 	'where' => [
				// 		'id_asignatura' => $id_asignatura,
				// 		'num_unidad' => $num_unidad,
				// 		'tipo_competencia' => 'resultado',
				// 	],
				// 	'select' => ['id_asignatura','num_unidad','id_usuario'],
				// ]);

				// if($existente){
				// 	if($existente->id_usuario == $this->user->id){
				// 		$data->setErrorMessage('Ya existe una competencia de resultado para esta asignatura-unidad. Edítalo desde el menú "mis resultados"');
				// 	}else{
				// 		$usuarioCreador = TblUsuario::findById($existente->id_usuario, ['select'=>['nombre']]);
				// 		$nombreU = $usuarioCreador ? $usuarioCreador->nombre : 'Desconocido';
				// 		$data->setErrorMessage('Ya existe una competencia para la asignatura y unidad seleccionada creada por el usuario '.$nombreU);
				// 	}
				// 	$res->json($data->forJSON());
				// }


			$competencia->descripcion = $descripcion;
			$competencia->id_usuario = $this->user->id;
			$competencia->id_asignatura = $id_asignatura;
			$competencia->etapa_actual = 1;
			$competencia->num_unidad = $num_unidad;
			$competencia->tipo_competencia = 'resultado';
			$competencia->finalizado = false;

			$db = new DBHelper();
			$db->beginTransaction();
			if($competencia->save($db)){
				$error = false;
				$msj_error = "";
				foreach ($contenidos_unidad as $cUnidadId) {
					$ccu = new TblCompetenciaContenidoUnidad([
						'id_competencia' => $competencia->id_competencia,
						'id_contenido_unidad' => $cUnidadId,
					]);

					if(!$ccu->save($db)){
						$error = true;
						$msj_error = $ccu->getError();
						break;
					}
				}

				if($error){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al agregar los contenidos a la competencia. '.$msj_error);
				}else{
					$competenciaUnidad = $competencia;
					$idCompetencia=$competencia->id_competencia;
					$db->commit();
					$data->setSuccessMessage('La competencia ha sido guardada correctamente');
				}
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar. '.$competencia->getError());
			}
		}

		$res->json($data->forJSON($competencia));
	}

	public function Form2Action(Request $req, Response $res, Data $data){

		$id_competencia_padre = $req->get('id_competencia_padre');
		$id_competencia_resultado = $req->get('id_competencia_resultado');
		$this->competencia_resultado = TblCompetencia::findById($id_competencia_resultado);
		
		$this->asignatura = new TblAsignatura;
		$this->unidad = new TblUnidad_asignatura;
		$this->contenidos = [];
		if($this->competencia_resultado){
			$this->asignatura = TblAsignatura::findById($this->competencia_resultado->id_asignatura);
			$this->unidad = TblUnidad_asignatura::findOne([
				'where' => [
					'id_asignatura' => $this->competencia_resultado->id_asignatura,
					'num_unidad' => $this->competencia_resultado->num_unidad,
				]
			]);

			$this->contenidos = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $this->competencia_resultado->id_competencia,
				],
				'include' => [
					['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido']]
				]
			]);
		}
		
		$this->render('asistente-generador/c-resultado/form2', $data);
	}

	public function Form3Action(Request $req, Response $res, Data $data){
		$id_competencia_padre = $req->get('id_competencia_padre');
		$id_competencia_resultado = $req->get('id_competencia_resultado');
		$this->competencia_resultado = TblCompetencia::findById($id_competencia_resultado);

		$this->asignatura = new TblAsignatura;
		$this->unidad = new TblUnidad_asignatura;
		$this->contenidos = [];
		if($this->competencia_resultado){
			$this->asignatura = TblAsignatura::findById($this->competencia_resultado->id_asignatura);
			$this->unidad = TblUnidad_asignatura::findOne([
				'where' => [
					'id_asignatura' => $this->competencia_resultado->id_asignatura,
					'num_unidad' => $this->competencia_resultado->num_unidad,
				]
			]);

			$this->contenidos = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $this->competencia_resultado->id_competencia,
				],
				'include' => [
					['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido']]
				]
			]);
		}
		
		$this->render('asistente-generador/c-resultado/form3', $data);
	}


	public function Form4Action(Request $req, Response $res, Data $data){
		$id = $req->get('id_competencia_resultado', 0);
		$this->competencia_resultado = TblCompetencia::findById($id);
		if(!$this->competencia_resultado){
			$this->render('<strong>Competencia no encontrada</strong>', $data, false);
		}

		$this->asignatura = TblAsignatura::findById($this->competencia_resultado->id_asignatura);
		$this->cgs = [];
		$tmp = TblCGA::findAll([
			'where' => [
				'id_asignatura' => $this->asignatura->id_asignatura,
				'activo' => 1,
			],
			'include' => [
				['localField' => 'id_cg', 'select' => ['id_cg','descripcion_cg']]
			],
			'select' => [
				'unidades'
			]
		]);
		foreach ($tmp as $index => $tmpItem) {
			if($tmpItem->unidades){
				$num_unidades = explode(",", $tmpItem->unidades);
				if(in_array($this->competencia_resultado->num_unidad, $num_unidades)){
					$this->cgs[] = $tmpItem;
				}
			}
		}

		$this->asignatura = new TblAsignatura;
		$this->unidad = new TblUnidad_asignatura;
		$this->contenidos = [];
		if($this->competencia_resultado){
			$this->asignatura = TblAsignatura::findById($this->competencia_resultado->id_asignatura);
			$this->unidad = TblUnidad_asignatura::findOne([
				'where' => [
					'id_asignatura' => $this->competencia_resultado->id_asignatura,
					'num_unidad' => $this->competencia_resultado->num_unidad,
				]
			]);

			$this->contenidos = TblCompetenciaContenidoUnidad::findAll([
				'where' => [
					'id_competencia' => $this->competencia_resultado->id_competencia,
				],
				'include' => [
					['localField' => 'id_contenido_unidad', 'select' => ['detalle_secuencia_contenido']]
				]
			]);
		}

		// $this->cgs = TblCG::CgsAsignatura($this->asignatura->id_asignatura, $this->competencia_resultado->num_unidad);
		$this->cgsSels = TblMallaCurricular::obtenerDeCompetencia($this->competencia_resultado->id_competencia);
		$this->render('asistente-generador/c-resultado/form4', $data);
	}


	public function GuardarFase4Action(Request $req, Response $res, Data $data){
		
		$idCompetencia = $req->post('id_competencia', 0);
		$cgs = $req->postArray('cgs');

		$competencia = TblCompetencia::findById($idCompetencia);
		if($competencia){

			$db = new DBHelper();
			$db->beginTransaction();

			$sqlDelete = "delete from ".TblMallaCurricular::$table." where id_competencia=?;";
			$exc1 = $db->delete($sqlDelete, [$idCompetencia]);

			$error = false;
			foreach ($cgs as $index => $idCg) {
				$malla = new TblMallaCurricular();
				$malla->id_asignatura = $competencia->id_asignatura;
				$malla->unidades = $competencia->num_unidad."";
				$malla->id_usuario = $this->user->id;
				$malla->tipo_competencia = "resultado";
				$malla->id_cg = $idCg;
				$malla->id_competencia = $competencia->id_competencia;

				if(!$malla->save($db)){
					$error = true;
					break;
				}

			}

			if($error){
				$data->setErrorMessage('Ha ocurrido un error. '.$db->error_db);
				$db->rollBack();
			}else{
				if($competencia->etapa_actual < 4){
					$competencia->etapa_actual = 4;
					if(!$competencia->update(null,$db)){
						$error = true;
					}
				}
				if($error){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error. '.$db->error_db);
				}else{
					$db->commit();
					$data->setSuccessMessage('Las competencias genéricas han sido guardadas correctamente.');
				}
			}

		}else{
			$data->setErrorMessage('No se ha encontrado la competencia solicitada.');
		}

		$res->json($data->forJSON($competencia));
	}

	public function Form5Action(Request $req, Response $res, Data $data){
		$id = $req->get('id_competencia_resultado', 0);
		$this->competencia_resultado = TblCompetencia::findById($id);
		$this->conocimientos = TblConocimientoCompetencia::findAll([
			'where' => [
				'id_competencia' => $id,
			],
			'include' => [
				['localField' => 'id_compo_conocimiento', 'select' => ['descrip_conocimiento'] ]
			]
		]);

		$this->habilidades = TblHabilidadCompetencia::findAll([
			'where' => [
				'id_competencia' => $id,
			],
			'include' => [
				['localField' => 'id_compo_habilidad', 'select' => ['descrip_habilidad'] ],
			]
		]);

		$this->actitudes = TblActitudValorCompetencia::findAll([
			'where' => [
				'id_competencia' => $id,
			],
			'include' => [
				['localField' => 'id_compo_actitud_valor', 'select' => ['descrip_actitud_valor']]
			]
		]);

		$this->cgs = TblMallaCurricular::findAll([
			'where' => [
				'id_competencia' => $id,
			],
			'include' => [
				['localField' => 'id_cg', 'select' => ['descripcion_cg']]
			]
		]);

		$this->render('asistente-generador/c-resultado/form5', $data);
	}

	public function GuardarFase5Action(Request $req, Response $res, Data $data){
		$id_competencia = $req->post('id_competencia', 0);
		$competencia = TblCompetencia::findById($id_competencia);
		if(!$competencia){
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado la competencia solicitada',
			]);
		}


		$adas = TblAda::findAll([
			'where' => [
				'resultado_competencia_ada' => $id_competencia,
			]
		]);

		// al menos 1 ada
		if(count($adas) == 0){
			$res->json([
				'estado' => false,
				'mensaje' => 'Debes agregar al menos una ADA',
			]);
		}

		// 100% ponderado
		// $suma_ponderado = 0;
		// foreach ($adas as $ada) {
		// 	$suma_ponderado += floatval($ada->ponderacion);
		// }

		// if($suma_ponderado < 100){
		// 	$res->json([
		// 		'estado' => false,
		// 		'mensaje' => 'No se ha completado el 100% de ponderación con las ADAs creadas',
		// 	]);
		// }

		// debemos verificar que cada ADA tenga al menos una cg
		$adas_id = [];
		foreach ($adas as $ada) {
			$adas_id[] = $ada->id_ada;

			$conteo_cgs = TblAdaCg::count([
				'where' => [
					'ada_id' => $ada->id_ada,
				]
			]);

			if($conteo_cgs == 0){
				$res->json([
					'estado' => false,
					'mensaje' => 'La ADA '.$ada->nombre_ada.' no tiene asignadas CGs '.$ada->id_ada,
				]);
			}
		}

		// debemos validar que en total las cgs seleccionadas en la competencia 
		// se utilicen entre todas las ADAs
		$cgs = TblMallaCurricular::findAll([
			'where' => [
				'id_competencia' => $id_competencia,
			]
		]);

		$db = new DBHelper();
		$sql = "select distinct cg_id from ".TblAdaCg::$table." where ada_id in (".implode(",", $adas_id).")";
		$cgs_anexadas = $db->read($sql);
		$cgs_anexadas_ids = [];
		foreach ($cgs_anexadas as $cgItem) {
			$cgs_anexadas_ids[] = $cgItem['cg_id'];
		}

		$todos = true;
		foreach ($cgs as $index => $cg) {
			if(!in_array($cg->id_cg, $cgs_anexadas_ids)){
				$todos = false;
				break;
			}
		}

		if(!$todos){
			$res->json([
				'estado' => false,
				'mensaje' => 'No se han utilizado las '.count($cgs).' competencias genéricas del resultado en edición. (Utilizadas: '.count($cgs_anexadas_ids).')',
			]);
		}

		if($competencia->etapa_actual < 5){
			$datos = [
				'etapa_actual'=> 5,
			];
			if(!$competencia->update($datos)){
				$res->json([
					'estado' => false,
					'mensaje' => 'Ha ocurrido un error al',
				]);
			}
		}


		$res->json([
			'estado' => true,
			'mensaje' => 'ADAs actualizadas correctamente',
			'data' => $competencia,
		]);
	}

	public function Form6Action(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia_resultado');
		$this->competencia = TblCompetencia::findById($id_competencia);

		$this->todas_competencias = TblCompetencia::findAll([
			'where' => [
				'id_asignatura' => $this->competencia->id_asignatura,
				'num_unidad' => $this->competencia->num_unidad,
				'tipo_competencia' => 'resultado'
			]
		]);

		$this->render('asistente-generador/c-resultado/form6', $data);
	}

	public function GuardarFase6Action(Request $req, Response $res, Data $data){
		$id_competencia = $req->post('id_competencia');
		$competencia = TblCompetencia::findById($id_competencia);
		if($competencia){
			if($competencia->etapa_actual>=5){
				$data = [
					'etapa_actual' => $competencia->etapa_actual > 6 ? $competencia->etapa_actual : 6,
					'finalizado' => true,
				];
				if($competencia->update($data)){
					$res->json([
						'estado' => true,
						'mensaje' => 'La competencia ha sido finalizada correctamente',
						'data' => $competencia,
					]);
				}else{
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al actualizar la etapa de la competencia',
					]);
				}
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'La competencia aún no se puede finalizar',
				]);
			}
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado la competencia',
			]);
		}
	}

	public function GuardarAdaCompetenciasExtrasAction(Request $req, Response $res, Data $data){
		$id_ada = $req->post('id_ada');
		$competencias_ids = $req->postArray('competencias_extras_ids');

		$ada = TblAda::findById($id_ada);
		if(!$ada){
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado la ADA',
			]);
		}

		$competencia_ada = TblCompetencia::findById($ada->resultado_competencia_ada);

		if(!$competencia_ada){
			$res->json([
				'estado' => false,
				'mensaje' => 'La ADA no se encuentra en ningún resultado de aprendizaje',
			]);
		}

		if(count($competencias_ids) == 0){
			$res->json([
				'estado' => false,
				'mensaje' => 'Debe seleccionar al menos un resultado de la unidad',
			]);
		}

		$db = new DBHelper();
		$db->beginTransaction();
		$ids_comp_guardados = [];
		foreach ($competencias_ids as $index => $id_competencia) {
			$competencia = TblCompetencia::findOne([
				'where' => [
					'id_competencia' => $id_competencia,
					'num_unidad' => $competencia_ada->num_unidad,
					'tipo_competencia' => 'resultado',
				]
			], $db);

			if(!$competencia){
				$db->rollBack();
				$res->json([
					'estado' => false,
					'mensaje' => 'No se ha encontrado un resultado seleccionado',
				]);
			}

			// validamos si ya está agregada
			$ada_competencia_extra = TblAdaCompetenciaExtra::findOne([
				'where' => [
					'id_competencia' => $competencia->id_competencia,
					'id_ada' => $ada->id_ada,
				]
			], $db);

			if(!$ada_competencia_extra){
				$ada_competencia_extra = new TblAdaCompetenciaExtra([
					'id_competencia' => $competencia->id_competencia,
					'id_ada' => $ada->id_ada
				]);

				if(!$ada_competencia_extra->save($db)){
					$db->rollBack();
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al asociar un resultado con la ADA seleccionada',
						'data' => $ada_competencia_extra,
						'e' => $ada_competencia_extra->getError(),
					]);
				}
			}

			$ids_comp_guardados[] = $ada_competencia_extra->id_competencia;
		}

		// ahora debemos eliminar algunos
		
		$sql_delete = "delete from ".TblAdaCompetenciaExtra::$table." where id_ada = ".$ada->id_ada." and id_competencia not in (".implode(",", $ids_comp_guardados).")";
		$db->delete($sql_delete, []);

		$db->commit();

		$res->json([
			'estado' => true,
			'mensaje' => 'Los resultados se han asociados con la ADA seleccionada',
		]);
	}

	public function ListaAdaCompetenciasExtra(Request $req, Response $res, Data $data){
		$id_ada = $req->get('id_ada');
		$ada = TblAda::findById($id_ada);

		if($ada){
			$ada_competencias = TblAdaCompetenciaExtra::findAll([
				'where' => [
					'id_ada' => $ada->id_ada,
				]
			]);

			$res->json([
				'estado' => true,
				'data' => $ada_competencias,
			]);
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado la ADA seleccionada',
			]);
		}
	}

	public function ListaAdasAction(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia');
		$adas = TblAda::findAll([
			'where' => [
				'resultado_competencia_ada' => $id_competencia,
			],
			'select' => [
				'id_ada',
				'id_asignatura',
				'id_unidad',
				'num_unidad',
				'resultado_competencia_ada',
				'id_herramienta',
				'nombre_ada',
			]
		]);

		$res->json([
			'estado' => true,
			'mensaje' => 'Adas obtenidas',
			'data' => $adas,
		]);
	}

	public function FormatoExportacionAda(Request $req, Response $res, Data $data){
		$id_ada = $req->get('id_ada');
		$formato = $req->get('formato', 'estudiante');
		$this->ada = TblAda::findById($id_ada);
		if($this->ada){

			$this->productos = TblAdaProducto::findAll([
				'where' => [
					'id_ada' => $id_ada,
				],
				'include' => [
					['localField' => 'id_producto', 'select' => ['nombre'] ]
				],
				'order' => 'nombre asc',
			]);

			$this->recursos = TblAdaRecurso::findAll([
				'where' => [
					'id_ada' => $id_ada,
				],
				'include' => [
					['localField' => 'id_recurso', 'select' => ['nombre']],
				],
				'order' => 'nombre asc',
			]);

			
			$this->competencia = TblCompetencia::findById($this->ada->resultado_competencia_ada);

			$this->competencias_resultado = [];
			$competencias_extras = TblAdaCompetenciaExtra::findAll([
				'where' => [
					'id_ada' => $this->ada->id_ada,
				]
			]);

			foreach ($competencias_extras as $index => $compExtra) {
				$tmp1 = TblCompetencia::findOne([
					'where' => [
						'id_competencia' => $compExtra->id_competencia,
						'id_asignatura' => $this->competencia->id_asignatura,
						'tipo_competencia' => 'resultado',
						'num_unidad' => $this->competencia->num_unidad,
						'etapa_actual' => ['>=', 5],
					],
				]);
				if($tmp1){
					$this->competencias_resultado[] = $tmp1;
				}
			}


			if(!$this->competencia){
				$res->send('<h1>Competencia no encontrada</h1>');
			}

			$this->asignatura = TblAsignatura::findById($this->competencia->id_asignatura);
			if(!$this->asignatura){
				$res->send('<h1>Asignatura no encontrada</h1>');
			}

			$this->unidad = TblUnidad_asignatura::findOne([
				'where' => [
					'id_asignatura' => $this->competencia->id_asignatura,
					'num_unidad' => $this->competencia->num_unidad,
				]
			]);

			if(!$this->unidad){
				$res->json('<h1>Unidad no encontrada</h1>');
			}

			$this->estrategias_ea = new TblAdaEstrategiaEa;

			$this->instrumento_eval = TblInstrumentoEval::findById($this->ada->id_instrumento_eval);
			$this->rubrica = $this->ada->id_rubrica ? TblRubrica::findById($this->ada->id_rubrica) : (new TblRubrica);
			$this->plantilla_rubrica = $this->ada->id_plantilla_rubrica ? TblPlantillaRubrica::findById($this->ada->id_plantilla_rubrica) : ( new TblPlantillaRubrica );
			

			if($formato == 'profesor'){
				$this->herramienta = TblHerramienta::findById($this->ada->id_herramienta);
				$this->estrategias_ea = TblAdaEstrategiaEa::findAll([
					'where' => [
						'id_ada' => $id_ada,
					],
					'include' => [
						['localField' => 'id_estrategia_ea', 'select' => ['descripcion_ea']],
					],
					'order' => 'descripcion_ea asc',
				]);

				

				$this->contenidos_unidad = TblContenidoUnidadAsignatura::findAll([
					'where' => [
						'id_unidad_asignatura' => $this->unidad->id_unidad_asignatura,
					]
				]);

				// $all_competencias = TblCompetencia::findAll([
				// 	'where' => [
				// 		'id_asignatura' => $this->asignatura->id_asignatura,
				// 		'num_unidad' => $this->unidad->num_unidad,
				// 		'tipo_competencia' => 'resultado',
				// 	]
				// ]);
				$db = new DBHelper();
				foreach ($this->contenidos_unidad as $index => $contenido_unidad) {
					$sql = "select ada.id_ada, ada.nombre_ada, ada.agentes_eval, ada.momento_eval, ada.id_herramienta, h.descripcion_herramienta, ada.otra_herramienta  from ".TblAda::$table." ada left join ".TblHerramienta::$table." h on h.id_herramienta = ada.id_herramienta where ada.resultado_competencia_ada in (select id_competencia from ".TblCompetenciaContenidoUnidad::$table." where id_contenido_unidad = ? )";
					$adas_contenido = $db->read($sql, [$contenido_unidad->id_contenido_unidad_asignatura]);

					$id_adas = [];
					$agentes = [];
					$momentos = [];
					$herramientas = [];
					foreach ($adas_contenido as $ada_cont) {
						$id_adas[] = $ada_cont['id_ada'];
						$ag = $ada_cont['agentes_eval'];
						if($ag){
							$expAg = explode(",", $ag);
							$agCap = [];
							foreach ($expAg as $item) {
								$agCap[] = ucwords($item);
							}
							$agentes = array_merge($agentes, $agCap);
						}

						$mm_o = $ada_cont['momento_eval'];
						$mm_f = $mm_o;
						if($mm_o == 'diagnostico'){
							$mm_f = 'Diagnóstico';
						}else if($mm_o == 'formativa'){
							$mm_f = 'Formativa';
						}else if($mm_o == 'sumativa'){
							$mm_f = 'Sumativa';
						}

						$momentos = array_merge($momentos , [$mm_f]);
						$herramientas = array_merge($herramientas, [$ada_cont['id_herramienta'] ? $ada_cont['descripcion_herramienta'] : $ada_cont['otra_herramienta'] ]);
					}

					$estrategias = [];
					if(count($adas_contenido) > 0){
						$sql2 = "select ea.descripcion_ea, ea.id_estrategia_ea from ".TblAdaEstrategiaEa::$table." adaea join ".TblEstrategiaEa::$table." ea on adaea.id_estrategia_ea = ea.id_estrategia_ea where adaea.id_ada in (".implode(",", $id_adas).");";

						$estrategias = $db->read($sql2, [], false, false);
					}


					$contenido_unidad->Extra = [
						'adas' => $adas_contenido,
						'eas' => $estrategias,
						'agentes' => $agentes,
						'momentos' => $momentos,
						'herramientas' => $herramientas,
					];
				}

			}


			if($formato == 'estudiante' || $formato == 'profesor'){
				$this->render('formatos/exportar-ada-'.$formato, $data);
			}else{
				$this->render('formatos/recurso-no-encontrado', $data);
			}
		}else{
			$this->render('formatos/recurso-no-encontrado', $data);
		}
	}

	public function Form7Action(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia_resultado');
		$this->competencia = TblCompetencia::findById($id_competencia);
		
		$this->render('asistente-generador/c-resultado/form7', $data);
	}

	public function GuardarFase7Action(Request $req, Response $res, Data $data){
		$res->json([
			'estado' => false,
			'mensaje' => 'No implementado',
		]);
	}

	public function Form8Action(Request $req, Response $res, Data $data){

		$this->render('asistente-generador/c-resultado/form8');
	}


	public function AdasResAction(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia');
		$competencia = TblCompetencia::findById($id_competencia);
		$this->adas = TblAda::findAll([
			'where' => [
				'resultado_competencia_ada' => $id_competencia,
				'id_asignatura' => $competencia->id_asignatura,
			],
			'include' => [
				[ 'localField' => 'id_herramienta','select' => ['descripcion_herramienta'], 'required' => false ],
			],
			'select' => ['id_ada', 'ponderacion', 'nombre_ada','instruccion_ada'],
			'order' => 'TblAda.fecha_creacion desc'
		]);

		$this->total_ponderado = 0;
		foreach ($this->adas as $ada) {
			$this->total_ponderado += $ada->ponderacion;
		}
		$this->render('asistente-generador/c-resultado/adas', $data);
	}
	

	public function FormAdaAction(Request $req, Response $res, Data $data){
		$this->productos = TblProducto::findAll([
			'select' => [
				'id_producto',
				'nombre'
			]
		]);
		$this->eas = TblEstrategiaEa::findAll([
			'select' => [
				'id_estrategia_ea',
				'descripcion_ea',
			],
			'order' => 'descripcion_ea asc'
		]);

		$this->instrumentos = TblInstrumentoEval::findAll([
			'select' => [
				'id_instrumento_eval',
				'descripcion_instrum_eval'
			],
			'order' => 'descripcion_instrum_eval asc'
		]);

		$this->recursos = TblRecurso::findAll([
			'select' => [
				'id_recurso',
				'nombre'
			],
			'order' => 'nombre asc'
		]);

		$this->rubricas = TblRubrica::findAll([
			'select' => [
				'id_rubrica',
				'descripcion_rubrica'
			],
			'order' => 'descripcion_rubrica asc'
		]);

		$this->ada = new TblAda(['id_ada'=>0]);
		
		// estas solo se utilizan cuando es una edicion
		$this->herramientas = null;
		$this->plantillas_rubrica = null;
		$this->prods_sel = [];
		$this->recs_sel = [];
		$this->eas_sel = [];

		$id = $req->get('id_ada', 0);
		if($id != 0){
			$this->ada = TblAda::findById($id);
			
			// se obtienen los datos, si aplica
			if($this->ada->id_herramienta){
				$this->herramientas = TblHerramienta::findAll([
					'select' => ['id_herramienta', 'descripcion_herramienta','explicacion_herramienta'],
					'order' => 'descripcion_herramienta asc'
				]);
			}

			if($this->ada->id_rubrica && $this->ada->id_plantilla_rubrica){
				$this->plantillas_rubrica = RubricaController::ListadoPlantillasAda($this->ada->id_rubrica, true, $req->get('id_competencia', 0), true);
				// TblPlantillaRubrica::findAll([
				// 	'where' => [
				// 		'id_rubrica' => $this->ada->id_rubrica
				// 	],
				// 	'select' => ['id_plantilla_rubrica','nombre'],
				// 	'order' => 'nombre asc'
				// ]);
			}

			// productos
			$sql = "select p.id_producto, p.nombre from ".TblProducto::$table." p join ".TblAdaProducto::$table." ap on p.id_producto=ap.id_producto where ap.id_ada=?;";
			$this->prods_sel = DBHelper::singleton()->read($sql, [$this->ada->id_ada]);
			
			$sql = "select r.id_recurso, r.nombre from ".TblRecurso::$table." r join ".TblAdaRecurso::$table." ar on r.id_recurso=ar.id_recurso where ar.id_ada=?;";
			$this->recs_sel = DBHelper::singleton()->read($sql, [$this->ada->id_ada]);

			$sql = "select e.id_estrategia_ea, e.descripcion_ea from ".TblEstrategiaEa::$table." e join ".TblAdaEstrategiaEa::$table." ae on e.id_estrategia_ea=ae.id_estrategia_ea where ae.id_ada=?;";
			$this->eas_sel = DBHelper::singleton()->read($sql, [$this->ada->id_ada]);

		}
		$competencia = TblCompetencia::findById($req->get('id_competencia', 0));
		if(!$competencia){
			$this->render('<div class="alert alert-error">Competencia no especificada</div>', $data, false);
		}
		$this->ada->id_asignatura = $competencia->id_asignatura;
		$unidad = TblUnidad_asignatura::findOne([
			'where' => [
				'num_unidad'=>$competencia->num_unidad,
				'id_asignatura' => $competencia->id_asignatura,
			]
		]);

		if($unidad){
			$this->ada->id_unidad = $unidad->id_unidad_asignatura;
		}
		$this->ada->num_unidad =  $competencia->num_unidad;
		$this->ada->resultado_competencia_ada =  $competencia->id_competencia;

		$this->cgs = TblMallaCurricular::findAll([
			'where' => [
				'id_competencia' => $competencia->id_competencia,
			],
			'include' => [
				['localField' => 'id_cg', 'select' => ['descripcion_cg']]
			]
		]);

		$tmp1 = TblAdaCg::findAll([
			'where' => [
				'ada_id' => $this->ada->id_ada
			]
		]);

		$this->cgs_ada_ids = [];
		foreach ($tmp1 as $tmp11) {
			$this->cgs_ada_ids[] = $tmp11->cg_id;
		}

		$this->render('asistente-generador/c-resultado/formulario_ada', $data);
	}

	public function GuardarAdaAction(Request $req, Response $res, Data $data){
		
		$id_ada = $req->post('id_ada', 0);
		$ADA = new TblAda();
		// datos que no deben cambiar
		$ADA->id_asignatura             = $req->post('id_asignatura');
		$ADA->id_unidad                 = $req->post('id_unidad');
		$ADA->num_unidad                = $req->post('num_unidad');
		$ADA->resultado_competencia_ada = $req->post('resultado_competencia_ada');

		if($id_ada != 0){
			$ADA = TblAda::findById($id_ada);
			if(!$ADA){
				$data->setErrorMessage('No se ha encontrado la ADA solicitada');
				$res->json($data->forJSON()); // finaliza
			}
		}

		$ADA->ponderacion = $req->post('ponderacion');
		$db = new DBHelper();

		// hacemos una primera verificacion del total ponderado
		// $args = [$ADA->resultado_competencia_ada];
		// $sql = "select sum(ponderacion) from ".TblAda::$table." where resultado_competencia_ada=? ";
		// if($id_ada != 0){
		// 	$sql.= " and id_ada != ?";
		// 	array_push($args, $id_ada);
		// }

		// $sql.=";";
		// $total_ponderado = $db->readScalar($sql, $args);
		// if($total_ponderado+$ADA->ponderacion>100){
		// 	$data->setErrorMessage("No es posible guardar esta ADA. El total de ponderación estaría rebasando el 100% (Esta ADA: ".$ADA->ponderacion."%. Actualmente es $total_ponderado%). Verifica los datos por favor");
		// 	$res->json($data->forJSON()); // finaliza
		// }



		// datos variables
		$ADA->id_herramienta         = $req->post('id_herramienta');
		$ADA->nombre_ada             = $req->post('nombre_ada');
		$ADA->modalidad_ada          = $req->post('modalidad_ada');
		$ADA->instruccion_ada        = $req->post('instruccion_ada');
		$ADA->max_integrantes_equipo = $req->post('max_integrantes_equipo');
		$ADA->procedimiento_ada      = $req->post('procedimiento_ada');
		$ADA->duracion_horas         = $req->post('duracion_horas');
		$ADA->referencias_ada        = $req->post('referencias_ada');
		$ADA->fecha_fin_ada          = $req->post('fecha_fin_ada') ? DateTime::createFromFormat('d/m/Y H:i', $req->post('fecha_fin_ada'))->format('Y-m-d H:i:s') : null;
		$ADA->fecha_ini_ada          = $req->post('fecha_ini_ada');
		$ADA->id_instrumento_eval    = $req->post('id_instrumento_eval');
		$ADA->otra_herramienta       = $req->post('otra_herramienta');
		// $ADA->id_estrategia_ea       = $req->post('id_estrategia_ea');
		$ADA->id_rubrica             = $req->post('id_rubrica');
		$ADA->id_plantilla_rubrica   = $req->post('id_plantilla_rubrica');
		$ADA->agentes_eval           = $req->post('agentes_eval');
		$ADA->momento_eval           = $req->post('momento_eval');
		$ADA->otro_producto          = $req->post('otro_producto');
		$ADA->otro_recurso           = $req->post('otro_recurso');
		$ADA->otro_estrategia_ea     = $req->post('otro_estrategia_ea');


		$db->beginTransaction();
		$ok = false;
		if($id_ada != 0){
			if($ADA->update(null,$db)){
				$ok = true;
			}
		}else{
			$ADA->id_usuario =  $this->user->id;
			if($ADA->save($db)){
				$ok = true;
			}
		}

		if(!$ok){
			$data->setErrorMessage('Ha ocurrido al intentar guardar la ADA. '.$ADA->getError());
			$db->rollBack();
			$res->json($data->forJSON()); // finaliza
		}

		$productos_entregar      = $req->postArray('productos_entregar');
		$recursos_entregar       = $req->postArray('recursos_entregar');
		$estrategias_ea_entregar = $req->postArray('estrategias_ea_entregar');
		$cgs                     = $req->postArray('cgs');

		// validamos desde aqui que lleguen cgs
		if(!$cgs || count($cgs) == 0){
			$db->rollBack();
			$res->json([
				'estado' => false,
				'mensaje' => 'Debe seleccionar al menos una competencia genérica',
			]);
		}

		if($id_ada != 0){

			// -----------------------------------------------------
			// -----------------------------------------------------
			// debemos eliminar aquellos que se hayan removido
			// y tambien eliminar de los que llegan que no es necesario
			// volver a insertar

			$ada_prods = TblAdaProducto::findAll([
				'where' => [
					'id_ada' => $id_ada
				]
			], $db);

			// aquellos que debemos eliminar de ada_producto rel
			$eliminar_ada_prods = [];
			foreach ($ada_prods as $ap) {
				if(!in_array($ap->id_producto, $productos_entregar)){
					$eliminar_ada_prods[] = $ap->id_producto;
				}
			}

			if(count($eliminar_ada_prods)>0){
				$str = Helpers::repetir([
					'str' => '?',
					'separador' => ',',
					'total' => count($eliminar_ada_prods)
				]);
				$sql = "delete from ".TblAdaProducto::$table." where id_ada=? and id_producto in ($str)";
				// print "=======> str: ".$str;
				// print $sql;
				// print_r($eliminar_ada_prods);
				$resultado_eliminar = $db->delete($sql, array_merge([$id_ada], $eliminar_ada_prods));
				if(!$resultado_eliminar){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar los productos no requeridos. '.$db->error_db);
					$res->json($data->forJSON()); // finaliza
				}
			}

			// remover del listado productos_entregar aquellos
			// que ya estan guardados
			$prods_finales = [];
			foreach ($productos_entregar as $prodId) {
				$encontrado = false;
				for ($i=0; $i < count($ada_prods); $i++) { 
					if($ada_prods[$i]->id_producto == $prodId){
						$encontrado = true;
						break;
					}
				}
				if(!$encontrado){
					$prods_finales[] = $prodId;
				}
			}

			// asignamos para el posterior guardado
			$productos_entregar = $prods_finales;

			// -----------------------------------------------------
			// -----------------------------------------------------
			// Hacemos lo mismo para los ada_recursos
			$ada_recs = TblAdaRecurso::findAll([
				'where' => [
					'id_ada' => $id_ada
				]
			], $db);

			// aquellos que debemos eliminar de ada_producto rel
			$eliminar_ada_recs = [];
			foreach ($ada_recs as $ap) {
				if(!in_array($ap->id_recurso, $recursos_entregar)){
					$eliminar_ada_recs[] = $ap->id_recurso;
				}
			}
			if(count($eliminar_ada_recs)>0){
				$str = Helpers::repetir([
					'str' => '?',
					'separador' => ',',
					'total' => count($eliminar_ada_recs)
				]);
				$sql = "delete from ".TblAdaRecurso::$table." where id_ada=? and id_recurso in ($str)";
				$resultado_eliminar = $db->delete($sql, array_merge([$id_ada], $eliminar_ada_recs));
				if(!$resultado_eliminar){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar los recurso no requeridos. '.$db->error_db);
					$res->json($data->forJSON()); // finaliza
				}
			}

			// remover del listado recursos_entregar aquellos
			// que ya estan guardados
			$recs_finales = [];
			foreach ($recursos_entregar as $recId) {
				$encontrado = false;
				for ($i=0; $i < count($ada_recs); $i++) { 
					if($ada_recs[$i]->id_recurso == $recId){
						$encontrado = true;
						break;
					}
				}
				if(!$encontrado){
					$recs_finales[] = $recId;
				}
			}

			$recursos_entregar = $recs_finales;

			// -----------------------------------------------------
			// -----------------------------------------------------
			// Hacemos lo mismo para los ada_eas
			$ada_eas = TblAdaEstrategiaEa::findAll([
				'where' => [
					'id_ada' => $id_ada
				]
			], $db);

			// aquellos que debemos eliminar de ada_ea rel
			$eliminar_ada_eas = [];
			foreach ($ada_eas as $ap) {
				if(!in_array($ap->id_estrategia_ea, $estrategias_ea_entregar)){
					$eliminar_ada_eas[] = $ap->id_estrategia_ea;
				}
			}
			if(count($eliminar_ada_eas)>0){
				$str = Helpers::repetir([
					'str' => '?',
					'separador' => ',',
					'total' => count($eliminar_ada_eas)
				]);
				$sql = "delete from ".TblAdaEstrategiaEa::$table." where id_ada=? and id_estrategia_ea in ($str)";
				$resultado_eliminar = $db->delete($sql, array_merge([$id_ada], $eliminar_ada_eas));
				if(!$resultado_eliminar){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar las estrategias no requeridas. '.$db->error_db);
					$res->json($data->forJSON()); // finaliza
				}
			}

			// remover del listado recursos_entregar aquellos
			// que ya estan guardados
			$eas_finales = [];
			foreach ($estrategias_ea_entregar as $eaId) {
				$encontrado = false;
				for ($i=0; $i < count($ada_eas); $i++) { 
					if($ada_eas[$i]->id_estrategia_ea == $eaId){
						$encontrado = true;
						break;
					}
				}
				if(!$encontrado){
					$eas_finales[] = $eaId;
				}
			}
			$estrategias_ea_entregar = $eas_finales;



			
			// -----------------------------------------------------
			// -----------------------------------------------------
			// Hacemos lo mismo para los ada_eas
			$cgs_existentes = TblAdaCg::findAll([
				'where' => [
					'ada_id' => $id_ada
				],
			], $db);

			// aquellos que debemos eliminar de ada_ea rel
			$eliminar_ada_cgs = [];
			foreach ($cgs_existentes as $cg_existente) {
				if(!in_array($cg_existente->cg_id, $cgs)){
					$eliminar_ada_cgs[] = $cg_existente->cg_id;
				}
			}
			if(count($eliminar_ada_cgs)>0){
				$str = Helpers::repetir([
					'str' => '?',
					'separador' => ',',
					'total' => count($eliminar_ada_cgs)
				]);

				$sql = "delete from ".TblAdaCg::$table." where ada_id=? and cg_id in ($str)";
				$resultado_eliminar_ada_cg = $db->delete($sql, array_merge([$id_ada], $eliminar_ada_cgs));
				if(!$resultado_eliminar_ada_cg){
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar las competencias genéricas no requeridas. '.$db->error_db);
					$res->json($data->forJSON()); // finaliza
				}
			}

			// remover del listado cgs aquellos
			// que ya estan guardados
			if(count($cgs_existentes) > 0){
				$cgs_finales = [];
				foreach ($cgs as $cgId) {
					$encontrado = false;
					for ($i=0; $i < count($cgs_existentes); $i++) { 
						if($cgs_existentes[$i]->cg_id == $cgId){
							$encontrado = true;
							break;
						}
					}
					if(!$encontrado){
						$cgs_finales[] = $cgId;
					}
				}
				$cgs = $cgs_finales;
			}
		}

		if(count($productos_entregar)>0){
			$error = false;
			$error_msj = "";
			foreach ($productos_entregar as $prodId) {
				$adaProd = new TblAdaProducto([
					'id_ada' => $ADA->id_ada,
					'id_producto' => $prodId
				]);
				if(!$adaProd->save($db)){
					$error = true;
					$error_msj = $adaProd->getError();
					break;
				}

			}
			if($error){
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al agregar los productos a entregar. '.$error_msj);
				$res->json($data->forJSON()); // finaliza
			}
		}

		if(count($recursos_entregar)>0){
			$error = false;
			$error_msj = "";
			foreach ($recursos_entregar as $recId) {
				$adaRec = new TblAdaRecurso([
					'id_ada' => $ADA->id_ada,
					'id_recurso' => $recId
				]);
				if(!$adaRec->save($db)){
					$error = true;
					$error_msj = $adaRec->getError();
					break;
				}
			}

			if($error){
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al agregar los recursos a utilizar. '.$error_msj);
				$res->json($data->forJSON()); // finaliza
			}
		}

		if(count($estrategias_ea_entregar)>0){
			$error = false;
			$error_msj = "";
			foreach ($estrategias_ea_entregar as $eaId) {
				$adaEstrategiaEa = new TblAdaEstrategiaEa([
					'id_ada'           => $ADA->id_ada,
					'id_estrategia_ea' => $eaId
				]);
				if(!$adaEstrategiaEa->save($db)){
					$error = true;
					$error_msj = $adaEstrategiaEa->getError();
					break;
				}
			}

			if($error){
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al agregar las estrategias ea a utilizar. '.$error_msj);
				$res->json($data->forJSON()); // finaliza
			}
		}

		if(count($cgs)>0){
			$error = false;
			$error_msj = "";
			foreach($cgs as $cgId){
				// debemos validar que la cg este con la asignatura, la unidad y competencia
				// seleccionada
				
				$encontrado = TblMallaCurricular::findOne([
					'where' => [
						'id_cg' => $cgId,
						'tipo_competencia' => 'resultado',
						'id_competencia' => $ADA->resultado_competencia_ada,
					]
				], $db);

				if(!$encontrado){
					$db->rollBack();
					$res->json([
						'estado' => false,
						'mensaje' => 'Algunas competencias genéricas seleccionadas no pertenencen al resultado en edición',
					]);
				}

				$adaCg = new TblAdaCg([
					'ada_id' => $ADA->id_ada,
					'cg_id' => $cgId
				]);
				if(!$adaCg->save($db)){
					$error = true;
					$error_msj = $adaCg->getError();
					break;
				}
			}

			if($error){
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al agregar las competencias genéricas a utilizar. '.$error_msj);
				$res->json($data->forJSON()); // finaliza
			}
		}

		$db->commit();
		$data->setSuccessMessage('La ADA ha sido '.($id_ada == 0 ? 'creada':'actualizada').' correctamente');		
		$res->json($data->forJSON());
	}
	
	public function EliminarAdaAction(Request $req, Response $res, Data $data){
		$id_ada = $req->post('id_ada');

		$ada = TblAda::findById($id_ada);
		if($ada){
			if($ada->delete()){
				$data->setSuccessMessage('La ADA ha sido eliminada correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la ADA. '.$ada->getError());
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la ADA seleccionada');
		}
		$res->json($data->forJSON());
	}

	public function ValidarUsoHerramientaAdas(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia');
		$adas = TblAda::findAll([
			'where' => [
				'resultado_competencia_ada' => $id_competencia
			],
			'include' => [
				['localField' => 'id_herramienta', 'select' => ['descripcion_herramienta']],
			],
			// 'select' => ['id_ada', 'nombre_ada','id_herramienta']
		]);

		$ALERTAS = [];
		if(count($adas) > 0){
			$ALERTAS = array_merge($ALERTAS, $this->AlertasUsoHerramientas($adas));
			$ALERTAS = array_merge($ALERTAS, $this->AlertasUsoEstrategiasEA($adas));
			$ALERTAS = array_merge($ALERTAS, $this->AlertasUsoTipoActividad($adas));
			$ALERTAS = array_merge($ALERTAS, $this->AlertasUsoAgentesEvaluacion($adas));
		}

		$res->json([
			'estado' => true,
			'data' => $ALERTAS,
		]);

	}

	public function AlertasUsoHerramientas($adas){
		$total_adas = count($adas);

		$herramientas = TblHerramienta::findAll([
			'where' => [ ],
			'select' => ['id_herramienta','descripcion_herramienta']
		]);

		$total_herramientas = count($herramientas);

		$ALERTAS = [];

		if($total_adas > 1){
			$conteos = [];
			$rebasados = [];

			foreach ($herramientas as $index => $herr) {
				$conteos[$herr->id_herramienta] = 0;
			}

			foreach ($adas as $index => $ada) {
				if(isset($conteos[$ada->id_herramienta])){
					$conteos[$ada->id_herramienta]++;
				}
			}

			// obtenemos el maximo de veces deseable que se debe utilizar una herramienta en
			// la competencia
			
			$maximo_recomendable = max([$total_herramientas, $total_adas]) / min([$total_herramientas, $total_adas]);

			if($maximo_recomendable < 1){ $maximo_recomendable = 1; }

			$index = 0;
			foreach ($conteos as $id_herramienta => $conteo_uso_herramienta) {
				if($conteo_uso_herramienta > $maximo_recomendable){
					$ALERTAS[] = [
						'tipo' => 'warning',
						'mensaje' => 'La herramienta "'.$herramientas[$index]->descripcion_herramienta.'" se está utilizando demasiadas veces (En '.$conteo_uso_herramienta.' de '.$total_adas.' ADAs)',
					];
				}
				$index++;
			}
		}

		return $ALERTAS;
	}

	public function AlertasUsoEstrategiasEA($adas){
		$estrategias = TblEstrategiaEa::findAll();

		$conteos = [];
		foreach ($estrategias as $index => $ea) {
			$conteos[$ea->id_estrategia_ea] = 0;
		}

		foreach ($adas as $index => $ada) {
			$easAda = TblAdaEstrategiaEa::findAll([
				'where' => [ 'id_ada' => $ada->id_ada ]
			]);

			foreach ($easAda as $index2 => $eaAda) {
				if(isset($conteos[$eaAda->id_estrategia_ea])){
					$conteos[$eaAda->id_estrategia_ea]++;
				}
			}
		}

		$ALERTAS = [];
		$maximo_recomendable = max([count($estrategias), count($adas)])  / min([count($estrategias), count($adas)]);

		foreach ($conteos as $id_estrategia_ea => $conteo) {
			if($conteo > $maximo_recomendable){

				$desc = '';
				foreach ($estrategias as $ea) {
					if($ea->id_estrategia_ea == $id_estrategia_ea){
						$desc = $ea->descripcion_ea;
					}
				}

				$ALERTAS[] = [
					'tipo' => 'warning',
					'mensaje' => 'La estrategia de enseñanza-aprendizaje "'.$desc.'" se está utilizando demasiadas veces (En '.$conteo.' de '.count($adas).' ADAs)',
				];
			}
		}

		return $ALERTAS;
	}

	public function AlertasUsoTipoActividad($adas){
		$tipos = [
			'individual' => 0,
			'pares' => 0,
			'equipo' => 0,
			'grupal' => 0
		];

		$max_uso = max([4, count($adas)]) / min([4, count($adas)]);


		foreach ($adas as $index => $ada) {
			if($ada->modalidad_ada){
				$tipos[$ada->modalidad_ada]++;
			}
		}

		$ALERTAS = [];
		foreach ($tipos as $tipo => $conteo) {
			if($conteo > $max_uso){
				$ALERTAS[] = [
					'tipo' => 'warning',
					'mensaje' => 'El tipo de actividad "'.$tipo.'" se está utilizando demasiado (En '.$conteo.' de '.count($adas).' ADAs)',
				];
			}
		}
		return $ALERTAS;
	}

	public function AlertasUsoAgentesEvaluacion($adas){
		$agentes = [
			'profesor' => 0,
			'alumno' => 0,
			'pares' => 0
		];

		foreach ($adas as $ada) {
			if($ada->agentes_eval){
				$items = explode(",", $ada->agentes_eval);

				if(in_array('profesor', $items)){
					$agentes['profesor']++;
				}

				if(in_array('alumno', $items)){
					$agentes['alumno']++;
				}

				if(in_array('pares', $items)){
					$agentes['pares']++;
				}
			}
		}


		$max = max([3, count($adas)]) / min([3, count($adas)]);
		$ALERTAS = [];
		foreach ($agentes as $name => $conteo) {
			if($conteo > $max){
				$ALERTAS[] = [
					'tipo' => 'warning',
					'mensaje' => 'El momento o agente de evaluación "'.$name.'" se está utilizando demasiado (En '.$conteo.' de '.count($adas).' ADAs)',
				];
			}
		}
		return $ALERTAS;
	}	

	public function cmp($a, $b){
		if ($a['conteo'] == $b['conteo']) {
	        return 0;
	    }
	    return ($a['conteo'] < $b['conteo']) ? 1 : -1;
	}

	public function RecomendacionHerramientaAction(Request $req, Response $res, Data $data){
		$id_competencia = $req->get('id_competencia');
		$instruccion = Helpers::textoNoAcentos(Helpers::removerConectores($req->get('instruccion', '')));
		$procedimiento = Helpers::textoNoAcentos(Helpers::removerConectores($req->get('procedimiento', '')));
		$strModalidadTokens = Helpers::sinonimosModalidadAda($req->get('modalidad'));
		$recomendaciones = [];
		$matching_encontrado = true;
		if($instruccion && $procedimiento){
			$herramientas = TblHerramienta::findAll();
			foreach ($herramientas as $index => $herramienta) {
				$palabras = explode(",", $herramienta->palabras_asociadas);
				foreach ($palabras as $token) {
					$token = Helpers::textoNoAcentos($token);
					if($token != ''){
						if(strpos($strModalidadTokens, $token) !== false || 
							strpos($instruccion, $token) !== false || 
							strpos($procedimiento, $token) !== false) {
						    if(isset($recomendaciones[$herramienta->id_herramienta])){
						    	$recomendaciones[$herramienta->id_herramienta]->conteo++;
						    }else{
						    	$recomendaciones[$herramienta->id_herramienta] = $herramienta;
						    	$recomendaciones[$herramienta->id_herramienta]->conteo = 1;
						    }
						}
					}
				}
			}
			// si no se encontraron similares, procedemos a obtener
			// algunos
			if(count($recomendaciones) == 0){
				$matching_encontrado = false;
				$recomendaciones = array_slice($herramientas, 0, 5);
			}
		}else{
			$data->setErrorMessage('Debes ingresar la instrucción y el procedimiento para generar una recomendación');
		}

		$final = [];
		foreach ($recomendaciones as $index=>$item) {
			$final[] = [
				'id_herramienta'=>$item->id_herramienta, 
				'descripcion_herramienta'=>$item->descripcion_herramienta,
				'explicacion_herramienta'=>$item->explicacion_herramienta,
				'conteo'=>(isset($item->conteo) ? $item->conteo : 1)
			];
		}

		// ordenamos por el que tiene el conteo mayor
		usort($final, [$this, "cmp"]);

		$res->json($data->forJSON([
			'matching_encontrado'=>$matching_encontrado,
			'listado'=>$final
		]));
	}


	public function PlantillaRecomendadaAction(Request $req, Response $res, Data $data){
		$idInstrumento = $req->get('id_instrumento_eval', 0);
		if($idInstrumento != 0){

		}else{
			$data->setErrorMessage('Debes seleccionar un instrumento de evaluación.');
		}
	}


	public function AgregarPlabrasClaveAction(Request $req, Response $res, Data $data){
		$this->palabras = $req->getArray('palabras');
		$this->herramientas = TblHerramienta::findAll([
			'select' => [
				'id_herramienta',
				'descripcion_herramienta',
				'palabras_asociadas',
			]
		]);
	
		$this->render('asistente-generador/c-resultado/agregar_palabras_clave', $data);
	}
	
	

}