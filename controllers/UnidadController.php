<?php 
class UnidadController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		$this->term = $req->getIfExists('term', null);
		$id_asignatura = $req->getIfExists('id_asignatura', null);
		$pageBack = $req->getIfExists('pageBack', 1);
		if($id_asignatura){
			$asignatura = TblAsignatura::findById($id_asignatura);
			$data->addToBody('asignatura', $asignatura);
			$data->addToBody('pageBack', $pageBack);
		}
		$this->render('academico/unidad/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->getIfExists('page', 1);
		$this->count = $req->getIfExists('count', 10);
		$id_asignatura = $req->getIfExists('id_asignatura', null);

		$criteria = [
			'where' => [],
			'limit' => ($this->count),
			'offset' => ( $this->count * ($this->page -1) ),
			'select' => ['id_unidad_asignatura','num_unidad','nombre_unidad','id_asignatura', 'duracion_unidad_hp','duracion_unidad_hnp'],
			'include' => [
				['localField'=>'id_asignatura', 'select' => ['nombre_asignatura']],
			],
			'order' => 'num_unidad asc',
		];

		if($id_asignatura){
			$criteria['where']['id_asignatura'] = $id_asignatura;
		}

		$term = $req->get('term', null);
		if($term && $term != ''){
			$criteria['where']['TblAsignatura.nombre_asignatura'] = ['like', $term];
		}

		$this->result = TblUnidad_asignatura::findAndCountAll($criteria);

		foreach ($this->result['rows'] as $key => $item) {
			$item->numero_contenidos = TblContenidoUnidadAsignatura::count([
				'where' => [
					'id_unidad_asignatura' => $item->id_unidad_asignatura
				]
			]);
		}

		$this->term = $term;
		$this->render('academico/unidad/listado', $data);
	}

	public function UnidadesDisponiblesAction(Request $req, Response $res, Data $data){
		$id_asignatura = $req->get('id_asignatura');
		$unidades = TblUnidad_asignatura::findAll([
			'where' => [
				'id_asignatura' => $id_asignatura
			]
		]);

		$res->json([
			'estado' => true,
			'data' => $unidades,
		]);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->getIfExists('id_unidad_asignatura',0);
		$id_asignatura = $req->getIfExists('id_asignatura', null);

		$this->asignatura = null;
		if($id_asignatura){
			$this->asignatura = TblAsignatura::findById($id_asignatura);
		}

		$ua = new TblUnidad_asignatura();
		if($id != 0){
			$ua = TblUnidad_asignatura::findById($id);
		}

		if(!$ua){
			$data->setErrorMessage('No se ha encontrado el registro solicitado');
		}

		$usuario = TblUsuario::findById($this->user->id);
		$this->nuevo = $id == 0;
		$this->ua = $ua;
		$this->asignaturas = TblAsignatura::findAll(['select'=>[
			'id_asignatura',
			'nombre_asignatura',
			'num_unidades'
		]]);
		$this->competencia_unidad = $this->nuevo ? null : TblCompetencia::findOne([
			'where' => [
				'tipo_competencia' => 'unidad',
				'num_unidad' => $this->ua->num_unidad,
				'id_asignatura' => $this->ua->id_asignatura,
			]
		]);
		$this->render('academico/unidad/formulario', $data);
	}

	public function SaveAction(Request $req, Response $res, Data $data){
		$dataUA = $data->contenido;
		if($data->isParamsOk()){

			$competencia_unidad = $req->post('competencia_unidad'); // se necesita para crear la competencia de la unidad desde el form de la unidad
			if(!$competencia_unidad){
				$data->setErrorMessage('Debes indicar la competencia de la unidad');
				$res->json($data->forJSON()); // finaliza el script
			}

			// ============================ ACTUALIZACION =================================
			if( intval($dataUA['id_unidad_asignatura']) != 0 ){
				$ua = TblUnidad_asignatura::findById($dataUA['id_unidad_asignatura']);
				if($ua){

					// validamos que si el numero de unidad cambió, no exista
					$nuevo_num_unidad = intval($dataUA['num_unidad']);
					$old_num_unidad = intval($ua->num_unidad);

					if($nuevo_num_unidad != $old_num_unidad){
						$unidadExistente = TblUnidad_asignatura::findOne([
							'where' => [
								'id_asignatura' => $ua->id_asignatura,
								'num_unidad' => $nuevo_num_unidad,
							]
						]);

						if($unidadExistente){
							$data->setErrorMessage('Ya existe un registro de la unidad '.$nuevo_num_unidad.' para la asignatura seleccionada');
							$res->json($data->forJSON());
						}
					}

					// intentamos obtener la competencia de unidad si existe
					$competencia = TblCompetencia::findOne([
						'where' => [
							'tipo_competencia' => 'unidad',
							'num_unidad' => $ua->num_unidad,
							'id_asignatura' => $ua->id_asignatura,
						]
					]);

					$ua->set($dataUA);
					$db = new DBHelper();
					$db->beginTransaction();
					if($ua->update(null, $db)){
						if($competencia){
							$dt = [ 'competencia_editable' => $competencia_unidad ];
							if($competencia->update($dt, $db)){
								$db->commit();
								$data->setSuccessMessage('Unidad de la asignatura actualizada correctamente');
							}else{
								$db->rollBack();
								$data->setErrorMessage('Ha ocurrido un error al actualizar la competencia de la unidad');
							}
						}else{
							// intentamos buscar la competencia padre
							$cpadre = TblCompetencia::findOne([
								'where' => [
									'tipo_competencia' => 'asignatura',
									'id_asignatura' => $ua->id_asignatura
								]
							], $db);

							$competencia = new TblCompetencia([
								'competencia_editable' => $competencia_unidad,
								'tipo_competencia' => 'unidad',
								'num_unidad' => $ua->num_unidad,
								'id_asignatura' => $ua->id_asignatura,
								'id_usuario' => $this->user->id,
								'finalizado' => true,
								'id_competencia_padre' => $cpadre ? $cpadre->id_competencia : null,
							]);

							if($competencia->save($db)){
								$db->commit();
								$data->setSuccessMessage('Unidad asignatura actualizada correctamente');
							}else{
								$data->setErrorMessage('Ha ocurrido un error al crear la competencia de la unidad');
							}
						}
					}else{
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$ua->getError());
					}
				}else{
					$data->setErrorMessage('No se ha encontrado la unidad solicitada');
				}
			}else{
				// =============================== CREACION =========================================
				$ua = new TblUnidad_asignatura($dataUA);

				// debemos validar que no exista una unidad ya dada de alta
				$unidadExistente = TblUnidad_asignatura::findOne([
					'where' => [
						'id_asignatura' => $ua->id_asignatura,
						'num_unidad' => $ua->num_unidad,
					]
				]);

				if($unidadExistente){
					$data->setErrorMessage('Ya existe un regisro de la unidad '.$ua->num_unidad.' para la asignatura seleccionada');
					$res->json($data->forJSON());
				}


				$ua->id_usuario = $this->user->id;
				$db = new DBHelper();
				$db->beginTransaction();
				if($ua->save($db)){
					// intentamos buscar la competencia padre
					$cpadre = TblCompetencia::findOne([
						'where' => [
							'tipo_competencia' => 'asignatura',
							'id_asignatura' => $ua->id_asignatura
						]
					], $db);

					$competencia = new TblCompetencia([
						'competencia_editable' => $competencia_unidad,
						'tipo_competencia' => 'unidad',
						'num_unidad' => $ua->num_unidad,
						'id_asignatura' => $ua->id_asignatura,
						'id_usuario' => $this->user->id,
						'finalizado' => true,
						'id_competencia_padre' => $cpadre ? $cpadre->id_competencia : null,
					]);

					if($competencia->save($db)){
						$db->commit();
						$data->setSuccessMessage('Unidad asignatura guardada correctamente');
					}else{
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error al crear la competencia de la unidad');
					}
				}else{
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar guardar. '.$ua->getError());
				}
			}
		}
	
		$res->json($data->forJSON($dataUA));
	}

	public function DeleteAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_unidad_asignatura',0);
		$ua = TblUnidad_asignatura::findById($id);
		if($ua){

			

			// $conteo_contenidos = TblContenidoUnidadAsignatura::count([
			// 	'where' => [
			// 		'id_unidad_asignatura' => $id
			// 	]
			// ]);

			// if($conteo_contenidos > 0){
				

			// 	$res->json([
			// 		'estado' => false,
			// 		'mensaje' => 'No se puede eliminar la unidad porque tiene contenidos agregados',
			// 	]);
			// }

			$db = new DBHelper();
			$db->beginTransaction();

			$criteria_delete_comp = [
				'id_asignatura' => $ua->id_asignatura,
				'num_unidad' => $ua->num_unidad,
				'tipo_competencia' => 'unidad',
			];

			if(TblCompetencia::deleteItem($criteria_delete_comp, $db)){
				if($ua->delete($db)){
					$db->commit();
					$data->setSuccessMessage('Unidad asignatura eliminada correctamente');
				}else{
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al eliminar la unidad de la asignatura, la unidad o alguno de sus contenido se encuentra en uso en resultados de aprendizaje');
				}
			}else{
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al eliminar la unidad de la asignatura');
			}

		}else{
			$data->setErrorMessage('Registro no encontrado');
		}

		$res->json($data->forJSON());
	}
}