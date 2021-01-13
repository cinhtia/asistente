<?php
use TblCompetenciaDisciplinarAsignatura as TblCDA;
use TblCompetenciaGenericaAsignatura as TblCGA;
class AsignaturaController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		$data->addToBody('page', $req->getIfExists('page', 1));
	
		$this->render('academico/asignatura/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page',1);
		$term = $req->getIfExists('term', null);
		$count = 10;
		
		$limit = $count;
		$offset = $count * ($page - 1);
		$dbTransaction = new DBHelper();

		$criteria = [
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'nombre_asignatura asc'
		];

		if($term){
			$criteria['where'] = [
				'nombre_asignatura' => ['like', $term],
			];
		}
		
		$result = TblAsignatura::findAndCountAll($criteria);
		$peAsignatura = [];

		foreach ($result['rows'] as $key => $asignatura) {
			$sql = "select pe.nombre_pe from ".TblAsignaturaPe::$table." ape inner join ".TblPlanEstudio::$table." pe on  pe.id_pe=ape.id_pe where ape.id_asignatura=?;";
			$peAsignatura[$key] = $dbTransaction->read($sql,[$asignatura->id_asignatura]);
		}
		
		$data->addToBody('total',$result['count']);
		$data->addToBody('asignaturas',$result['rows']);
		$data->addToBody('pe_asignatura', $peAsignatura);
		$data->addToBody('count',$count);
		$data->addToBody('page',$page);
		$this->render('academico/asignatura/listado', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		
		$asignatura = new TblAsignatura();
		//$asignatura->competencia_asignada = '';

		if($data->isPost()){
			if($data->isParamsOk()){

				$asignatura->nombre_asignatura = $data->fromBody('nombre_asignatura');
				$asignatura->tipo_asignatura = $data->fromBody('tipo_asignatura');
				$asignatura->modalidad = $data->fromBody('modalidad');
				$asignatura->num_unidades = $data->fromBody('num_unidades');
				$asignatura->semestre_ubicacion = $data->fromBody('semestre_ubicacion', null);
				$asignatura->horas_duracion = $data->fromBody('horas_duracion');
				$asignatura->horas_presenciales = $data->fromBody('horas_presenciales');
				$asignatura->horas_nopresenciales = $data->fromBody('horas_nopresenciales');
				$asignatura->creditos = $data->fromBody('creditos');
				$asignatura->contextualizacion = $data->fromBody('contextualizacion');
				$asignatura->id_usuario = $this->user->id;

				$competencia_asignada = $data->fromBody('competencia_asignada', null);

				$existentes = TblAsignatura::count(['where'=> ['nombre_asignatura'=>$asignatura->nombre_asignatura]]);

				if($existentes == 0){
					$db = new DBHelper();
					$db->beginTransaction();
					if($asignatura->save($db)){

						// creamos la competencia de asignatura asociada
						$competencia = new TblCompetencia();
						$competencia->id_usuario = $this->user->id_usuario;
						$competencia->id_asignatura = $asignatura->id_asignatura;
						$competencia->competencia_editable = $competencia_asignada;
						$competencia->tipo_competencia = "asignatura";

						if($competencia->save($db)){
							// debemos guardar los pes
							
							$ids_pes = $req->postArray('pes', []);
							$error = false;
							foreach($ids_pes as $id_pe) {
								$pe = TblPlanEstudio::findById($id_pe, null, $db);
								if(!$pe){
									$db->rollBack();
									$res->json([
										'estado' => false,
										'mensaje' => 'No se ha encontrado un plan de estudio seleccionado',
									]);
								}

								$ape = new TblAsignaturaPe([
									'id_asignatura' => $asignatura->id_asignatura,
									'id_pe' => $id_pe,
									'id_usuario' => $this->user->id,
								]);

								if(!$ape->save($db)){
									$db->rollBack();
									$res->json([
										'estado' => false,
										'mensaje' => 'Ha ocurrido un error al intentar guardar el plan de estudios',
									]);
								}
							}

							$db->commit();
							$data->setSuccessMessage('Asignatura creada correctamente');
							$asignatura = new TblAsignatura();
							$asignatura->competencia_asignada = '';
						}else{
							$asignatura->competencia_asignada = $competencia_asignada;
							$db->rollBack();
							$data->setErrorMessage('No se ha podido guardar la asignatura');
						}
					}else{
						$asignatura->competencia_asignada = $competencia_asignada;
						$db->rollBack();
						$data->setErrorMessage($asignatura->getError());
					}
				}else{
					$asignatura->competencia_asignada = $competencia_asignada;
					$data->setErrorMessage('Ya existe una asignatura con el mismo nombre');
				}
			}else{
				$asignatura->competencia_asignada = '';
				$data->setErrorMessage('Los parámetros no están completos');
			}
		}else{
			$asignatura->competencia_asignada = '';
		}

		$data->addToBody('asignatura', $asignatura);
		$this->render('academico/asignatura/formulario_asignatura', $data);
	}

	public function EditarAction(Request $req, Response $res, Data $data){
		$asignatura = new TblAsignatura();
		$asignatura_pe = [];

		if($data->isParamsOk()){
			$id = $data->fromBody('id_asignatura');

			$asignatura = TblAsignatura::findById($id);
			$competencia_asignatura = TblCompetencia::findOne([
				'where' => [
					'id_asignatura' => $asignatura->id_asignatura,
					'tipo_competencia' => 'asignatura',
				]
			]);

			if($asignatura){
				if($data->isPost()){

					$oldNombre = $asignatura->nombre_asignatura;

					$asignatura->nombre_asignatura = $data->fromBody('nombre_asignatura');
					$asignatura->tipo_asignatura = $data->fromBody('tipo_asignatura');
					$asignatura->modalidad = $data->fromBody('modalidad');
					$asignatura->num_unidades = $data->fromBody('num_unidades');
					$asignatura->semestre_ubicacion = $data->fromBody('semestre_ubicacion');
					$asignatura->horas_duracion = $data->fromBody('horas_duracion');
					$asignatura->horas_presenciales = $data->fromBody('horas_presenciales');
					$asignatura->horas_nopresenciales = $data->fromBody('horas_nopresenciales');
					$asignatura->creditos = $data->fromBody('creditos');
					// $asignatura->competencia_asignada = $data->fromBody('competencia_asignada');
					$asignatura->contextualizacion = $data->fromBody('contextualizacion');

					$existentes = 0;
					if($oldNombre != $asignatura->nombre_asignatura){
						$existentes = TblAsignatura::count(['where'=>['nombre_asignatura'=>$asignatura->nombre_asignatura]]);
					}

					if($existentes == 0){
						$db = new DBHelper();
						$db->beginTransaction();
						if($asignatura->update(null,$db)){

							$id_pes = $req->postArray('pes', []);

							$ids_agregados = [];
							foreach ($id_pes as $index => $id_pe) {
								$pe = TblPlanEstudio::findById($id_pe, null, $db);
								if(!$pe){
									$db->rollBack();
									$res->json([
										'estado' => false,
										'mensaje' => 'No se ha encontrado un plan de estudios seleccionado',
									]);
								}

								$existe = TblAsignaturaPe::findOne([
									'where' => [
										'id_asignatura' => $asignatura->id_asignatura,
										'id_pe' => $pe->id_pe,
									]
								], $db);

								if(!$existe){
									$nape = new TblAsignaturaPe([
										'id_asignatura' => $asignatura->id_asignatura,
										'id_pe' => $pe->id_pe,
										'id_usuario' => $this->user->id,
									]);

									if(!$nape->save($db)){
										$db->rollBack();
										$res->json([
											'estado' => false,
											'mensaje' => 'Ha ocurrido un error al guardar el plan de estudios',
										]);
									}

									$ids_agregados[] = $nape->id_asignatura_pe;
								}else{
									$ids_agregados[] = $existe->id_asignatura_pe;
								}
							}

							try{
								$sql_eliminar = 'delete from '.TblAsignaturaPe::$table.' where id_asignatura = ? '.( count($ids_agregados) > 0 ? ( ' and id_asignatura_pe not in ('.implode(",", $ids_agregados).')' ) : '');
								$resultado1 = $db->delete($sql_eliminar, [$asignatura->id_asignatura]);
							}catch(Exception $ex){
								$db->rollBack();
								$res->json([
									'estado' => false,
									'mensaje' => 'Ha ocurrido un error al eliminar los planes de estudio desmarcados',
								]);
							}

							$nueva_competencia = $data->fromBody('competencia_asignada');
							
							if($competencia_asignatura){
								if($competencia_asignatura->competencia_editable == $nueva_competencia){
									$db->commit();
									$data->setSuccessMessage('La asignatura ha sido actualizada correctamente');
								}else{
									$competencia_asignatura->competencia_editable = $nueva_competencia;
									if($competencia_asignatura->update(null,$db)){
										$db->commit();
										$data->setSuccessMessage('La asignatura ha sido actualizada correctamente');
									}else{
										$db->rollBack();
										$data->setErrorMessage('Ha ocurrido un error al actualizar la asignatura');
									}
								}
							}else{
								$competencia = new TblCompetencia();
								$competencia->id_usuario = $this->user->id_usuario;
								$competencia->id_asignatura = $asignatura->id_asignatura;
								$competencia->competencia_editable = $nueva_competencia;
								$competencia->tipo_competencia = "asignatura";
								if($competencia->save($db)){
									$competencia_asignatura = $competencia;
									$db->commit();
									$data->setSuccessMessage('La asignatura ha sido actualizada correctamente');
								}else{
									$db->rollBack();
									$data->setErrorMessage('Ha ocurrido un error al actualizar la asignatura');
								}
							}
						}else{
							$data->setErrorMessage($asignatura->getError());
						}
					}else{
						$data->setErrorMessage('Ya existe otra asignatura con el nombre indicado');
					}
				}

				$asignatura_pe = TblAsignaturaPe::findAll([
					'where' => [
						'id_asignatura' => $id,
					]
				]);

			}else{
				$data->setErrorMessage('No se ha encontrado la asignatura solicitada');
			}
			$asignatura->competencia_asignada = $competencia_asignatura ? $competencia_asignatura->competencia_editable : '';
			
		}else{
			$data->setErrorMessage('Parámetros incompletos');
		}


		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('asignatura_pe', $asignatura_pe);
		$data->addToBody('nuevo', false);
		$this->render('academico/asignatura/formulario_asignatura', $data);
	}
	

	public function EliminarAction(Request $req, Response $res, Data $data){
		
		$respuesta = [
			'estado' => false,
			'mensaje' => ''
		];

		if($data->isParamsOk()){
			$id = $data->fromBody('id_asignatura');
			$asignatura = TblAsignatura::findById($id);

			if($asignatura){

				// $count_unidad = TblUnidad_asignatura::count([
				// 	'where' => [
				// 		'id_asignatura' => $asignatura->id_asignatura,
				// 	]
				// ]);
				
				$count_competencia = TblCompetencia::count([
					'where' => [
						'id_asignatura' => $asignatura->id_asignatura,
						'tipo_competencia' => 'resultado',
					]
				]);

				// $count_cgs = TblCompetenciaGenericaAsignatura::count([
				// 	'where' => [
				// 		'id_asignatura' => $asignatura->id_asignatura,
				// 	]
				// ]);

				// $count_cds = TblCompetenciaDisciplinarAsignatura::count([
				// 	'where' => [
				// 		'id_asignatura' => $asignatura->id_asignatura,
				// 	]
				// ]);

				// $count_profe = TblUsuarioAsignatura::count([
				// 	'where' => [
				// 		'id_asignatura' => $asignatura->id_asignatura,
				// 	]
				// ]);

				if(
					// $count_unidad > 0 || 
					$count_competencia > 0
					// || 
					// $count_cgs > 0 ||
					// $count_cds > 0 
					// || 
					// $count_profe > 0
				){
					$msj = 'No se puede eliminar la asignatura porque se encuentra asociada con: ';
					$msj .= '<ul>';
					// if($count_unidad > 0){
					// 	$msj .= '<li> '.$count_unidad.' unidades</li>';
					// }

					if($count_competencia > 0){
						$msj .= '<li> '.$count_competencia.' resultados de aprendizaje</li>';
					}

					// if($count_cgs > 0){
					// 	$msj .= '<li> '.$count_cgs.' competencias genéricas</li>';
					// }

					// if($count_cds > 0){
					// 	$msj .= '<li> '.$count_cds.' competencias disciplinares</li>';
					// }

					// if($count_profe > 0){
					// 	$msj .= '<li><strong>'.$count_profe.' profesores tienen agregado esta asignatura</strong></li>';
					// }

					$msj .= '</ul>';

					$res->json([
						'estado' => false,
						'mensaje' => $msj,
					]);
					return;
				}

				$db = new DBHelper();
				$db->beginTransaction();


				
				$criteria_eliminacion_comp = [
					'id_asignatura' => $asignatura->id_asignatura,
					'or' => [
						['tipo_competencia' => 'asignatura'],
						['tipo_competencia' => 'unidad'],
					]
				];
				if(!TblCompetencia::deleteItem($criteria_eliminacion_comp, $db)){
					$db->rollBack();
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al eliminar la asignatura.1 ',
					]);
					return;
				}

				// automatico con el delete cascade
				// $criteria_eliminacion_pe = [ 'id_asignatura' => $asignatura->id_asignatura ];
				// if( !TblAsignaturaPe::deleteItem($criteria_eliminacion_pe, $db) ){
				// 	$db->rollBack();
				// 	$res->json([
				// 		'estado' => false,
				// 		'mensaje' => 'Ha ocurrido un error al eliminar la asignatura. Verifique que la asignatura no tengo agregado cgs, cds, pes y/o resultados. 2',
				// 	]);
				// 	return;
				// }
				
				if(!$asignatura->delete($db)){
					$db->rollBack();
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al eliminar la asignatura. Verifique que la asignatura no se encuentre en uso en resultados de aprendizaje.',
					]);
					return;
				}
				
				$db->commit();
				$respuesta['estado'] = true;
				$respuesta['mensaje'] = "La asignatura ha sido eliminada";
			}else{
				$respuesta['mensaje'] = "Asignatura no encontrada";
			}
		}else{
			$respuesta['mensaje'] = "Parámetros incompletos";
		}

		$res->json($respuesta);
	}
	

	public function PlanesEstudioAction(Request $req, Response $res, Data $data){
		
		$asignatura = new TblAsignatura();
		$pesAsignatura = [];

		if($data->isParamsOk()){
			$asignatura = TblAsignatura::findById($data->fromBody('id_asignatura'));
			$pesAsignatura = TblAsignaturaPe::findAll(['where'=>['id_asignatura'=>$asignatura->id_asignatura]]);
			foreach ($pesAsignatura as $key => $pe) {
				$pesAsignatura[$key]->PlanEstudio = TblPlanEstudio::findById($pe->id_pe);
			}
		}

		$data->addToBody('page_asignatura', $req->getIfExists('page_asignatura', 1));
		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('pes_asignatura', $pesAsignatura);
		$this->render('academico/asignatura/index_pes_asignatura', $data);
	}


	public function NuevoPEAsignaturaAction(Request $req, Response $res, Data $data){
		
		$asignaturaPE = new TblAsignaturaPe();
		$asignatura = new TblAsignatura();
		$planesEstudio = [];
		if($data->isParamsOk()){
			$id = $data->fromBody('id_asignatura');
			$asignatura = TblAsignatura::findById($id);
			$planesEstudio = TblPlanEstudio::findAll();
			
			if($data->isPost()){
				$asignaturaPE->id_asignatura = $id;
				$asignaturaPE->id_pe = $data->fromBody('id_pe');
				$asignaturaPE->id_usuario = $this->user->id;

				$existentes = TblAsignaturaPe::count(['where'=>['id_asignatura'=>$id,'id_pe'=>$asignaturaPE->id_pe]]);

				if($existentes == 0){
					if($asignaturaPE->save()){
						$data->setSuccessMessage('Se ha asociado la asignatura con el plan de estudio exitosamente');
						$asignaturaPE = new TblAsignaturaPe();
					}else{
						$data->setErrorMessage($asignaturaPE->getError());
					}
				}else{
					$data->setErrorMessage('Ya existe un registro para la asignatura y el plan de estudio seleccionado');
				}

			}

		}else{
			$data->setErrorMessage('Parámetros incompletos');
		}
	
		$data->addToBody('asignatura_pe', $asignaturaPE);
		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('planes_estudio', $planesEstudio);
		$data->addToBody('nuevo', true);

		$this->render('academico/asignatura/formulario_pe_asignatura', $data);
	}
	
	public function AsignaturasPeAction(Request $req, Response $res, Data $data){
		$idPE = $data->fromBody('id_pe');
		$pe = TblPlanEstudio::findById($idPE);

		$asignatura = $pe->obtenerAsignaturas(false);//TblAsignaturaPe::obtenerTodos(-1,-1,null,null, ['id_pe'=>$idPE]);
		$data->setSuccessMessage('Asignaturas obtenidas');
		$res->json($data->forJSON($asignatura));
	}

	// --------------- competencias genericas ---------------------------
	public function CGsAsignaturasAction(Request $req, Response $res, Data $data){
		$asignatura = new TblAsignatura();
		$cgs = [];
		$cgDisponibles = [];
		$idAsignatura = $data->fromBody('id_asignatura');
		$asignatura = TblAsignatura::findById($idAsignatura);

		if($asignatura){
			$cgs = TblCGA::findAll([
				'where' => [
					'id_asignatura' => $idAsignatura,
				],
				'include' => [
					['localField'=>'id_cg','select'=>['descripcion_cg']]
				]
			]);

			$cgDisponibles = TblCG::findAll();
		}else{
			$data->setErrorMessage('No se ha encontrado la asignatura');
		}
		

		$data->addToBody('asignatura', $asignatura);
		$data->addToBody('cgs', $cgs);
		$data->addToBody('cg_disponibles', $cgDisponibles);
		$this->render('academico/asignatura/modal_cgs_asignatura', $data);
	}

	public function AgregarCgsAsignaturaPeAction(Request $req, Response $res, Data $data){
		if($data->isParamsOk()){
			$idAsignatura = $data->fromBody('id_asignatura');
			$idCG = $data->fromBody('id_cg');
			$unidades = $req->postArray('unidades', []);

			$asignatura = TblAsignatura::findById($idAsignatura);
			$args = [];
			if($asignatura){
				if(count($unidades)>0){
					$args = ['id_asignatura' => $idAsignatura];
					$totalAsociaciones = TblCGA::count(['where'=>$args]);

					if($totalAsociaciones < 10){
						$args['id_cg'] = $idCG;
						$args['activo'] = true;

						$existe2 = TblCGA::count(['where'=>$args]);

						if($existe2 == 0){
							$malla = new TblCGA($args);
							$malla->unidades = implode(",", $unidades);
							$malla->id_usuario = $this->user->id;
							if($malla->save()){
								$data->setSuccessMessage('La competencia genérica fue asignada correctamente');
							}else{
								$data->setErrorMessage($malla->obtenerError());
							}
						}else{
							$data->setErrorMessage('La competencia genérica ya ha sido asignada a la asignatura');
						}
					}else{
						$data->setErrorMessage('La asignatura no puede tener más de 10 competencias genéricas');
					}
				}else{
					$data->setErrorMessage('Debes seleccionar al menos una unidad de la asignatura');
				}
			}else{
				$data->setErrorMessage('La asignatura y el plan de estudios no estan relacionados');
			}
		}

		$res->json($data->forJSON($args));
	}

	public function EliminarCgsAsignaturaPeAction(Request $req, Response $res, Data $data){
		if($data->isParamsOk() ){
			$idAsignatura = $data->fromBody('id_asignatura');
			$idCG = $data->fromBody('id_cg');

			$args = ['id_asignatura'=>$idAsignatura,'id_cg'=>$idCG, 'activo'=>true];

			$malla = TblCGA::findOne(['where'=>$args]);
			if($malla){
				if($malla->delete()){
					$data->setSuccessMessage('Se ha removido la competencia genérica de la asignatura');
				}else{
					$data->setErrorMessage($malla->obtenerError());
				}
			}else{
				$data->setErrorMessage('La competencia genérica no está relacionada con la asignatura');
			}
		}
		$res->json($data->forJSON($args));
	}

	// -------------- competencias disciplinares --------------------------

	public function CDsAsignaturasAction(Request $req, Response $res, Data $data){
		$this->id_asignatura = $req->getIfExists('id_asignatura', 0);
		$this->asignatura = TblAsignatura::findById($this->id_asignatura);
		$this->cds = TblCompetenciaDisciplinar::findAll(['select'=>['id_competencia_disciplinar','descripcion']]);

		$sql = "select cdap.*, cd.descripcion from ".TblCDA::$table." cdap inner join ".TblCompetenciaDisciplinar::$table." cd on cdap.id_competencia_disciplinar = cd.id_competencia_disciplinar where cdap.id_asignatura = ?;";
		$db = new DBHelper();
		$this->cds_sel = $db->read($sql, [$this->id_asignatura]);

		$this->render('academico/asignatura/modal_cds_asignatura', $data);
	}

	public function GuardarCDAPEAction(Request $req, Response $res, Data $data){
		$idA = $req->postIfExists('id_asignatura', 0);
		$idCD = $req->postIfExists('id_competencia_disciplinar', 0);
		$asignatura = TblAsignatura::count(['where'=>['id_asignatura'=>$idA]]);
		$cd = TblCompetenciaDisciplinar::findById($idCD);
		$cdape = new TblCDA();
		if($asignatura > 0 && $cd){
			$existe = TblCDA::count(['where'=>['id_asignatura'=>$idA,'id_competencia_disciplinar'=>$idCD]]);
			if($existe == 0){
				$cda = new TblCDA();
				$cda->id_asignatura = $idA;
				$cda->id_competencia_disciplinar = $idCD;
				if($cda->save()){
					$cda->descripcion = $cd->descripcion;
					$data->setSuccessMessage('Competencia disciplinar agregada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al guardar. '.$cda->getError());
				}
			}else{
				$data->setErrorMessage('La competencia disciplinar ya está agregada');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la asignatura o la competencia disciplinar');
		}
		$res->json($data->forJSON($cda));
	}


	public function EliminarCDAPEAction(Request $req, Response $res, Data $data){
		
		$idApe = $req->postIfExists('id_asignatura', 0);
		$idCD = $req->postIfExists('id_competencia_disciplinar', 0);
		$cdape = TblCDA::findById(['id_asignatura'=>$idApe,'id_competencia_disciplinar'=>$idCD]);

		if($cdape){
			if($cdape->delete()){
				$data->setSuccessMessage('La competencia disciplinar ha sido removida de la asignatura');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar remover el registro. '.$cdape->getError());
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el registro solicitado');
		}

		$res->json($data->forJSON());
	}
	
}