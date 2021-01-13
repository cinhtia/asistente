<?php
class PlanEstudioController extends BaseController{


	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('academico/pe/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->getIfExists('page',1);
		$this->count = $req->getIfExists('count',10);
		
		$limit = $this->count;
		$offset = $this->count * ($this->page - 1);
		$whereAssoc = null;;

		$this->result = TblPlanEstudio::findAndCountAll([
			'where' => $whereAssoc,
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'nombre_pe asc',
		]);
		
		foreach ($this->result['rows'] as $key => $pe) {
			$this->result['rows'][$key]->totalAsignaturas = TblAsignaturaPe::count(['where'=>['id_pe'=>$pe->id_pe]]);
			if(!$this->result['rows'][$key]->totalAsignaturas){
				$this->result['rows'][$key]->totalAsignaturas = 0;
			}
		}	
		$this->render('academico/pe/listado', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		$pe = new TblPlanEstudio();

		if($data->isPost()){
			if($data->isParamsOk()){
				$nombre = $data->fromBody('nombre_pe');
				$facultad = $req->post('facultad');
				$idUsuario = $this->user->id;

				$pe->nombre_pe = $nombre;
				$pe->facultad = $facultad;
				$pe->id_usuario = $idUsuario;

				if($pe->save()){
					$data->setSuccessMessage('Plan de estudio creado correctamente');
					$pe = new TblPlanEstudio();
				}else{
					$data->setErrorMessage($pe->obtenerError());
				}
			}else{
				$data->setErrorMessage('Los datos no estan completos');
			}
		}

		$data->addToBody('pe',$pe);
		$this->render('academico/pe/formulario_pe', $data);
	}

	public function EditarAction(Request $req, Response $res, Data $data){
		$pe = new TblPlanEstudio();

		if($data->isParamsOk()){
			$id = $data->fromBody('id_pe');
			$pe = TblPlanEstudio::findById($id);
			if($pe){
				if($data->isPost()){
					$nombre = $data->fromBody('nombre_pe');
					$facultad = $req->post('facultad');
					$pe->nombre_pe = $nombre;
					$pe->facultad = $facultad;
					if($this->user->esAdmin || $this->user->id == $pe->id_usuario){
						if($pe->update()){
							$data->setSuccessMessage('Plan de estudio actualizado correctamente');
						}else{
							$data->setErrorMessage($pe->obtenerError());
						}
					}else{
						$data->setErrorMessage('Permisos denegados para esta acción');
					}
				}
			}else{
				$data->setErrorMessage('Plan de estudio no encontrado');
			}
		}else{
			$data->setErrorMessage('Datos incompletos');
		}
		
		$data->addToBody('pe',$pe);
		$data->addToBody('nuevo',false);
		$this->render('academico/pe/formulario_pe', $data);
	}
	
	public function EliminarAction(Request $req, Response $res, Data $data){
		if($data->isParamsOk()){
			$id = $data->fromBody('id_pe');
			$pe = TblPlanEstudio::findById($id);
			if($pe){

				$count_cds = TblCompetenciaDisciplinar::count([
					'where' => [
						'plan_estudio_id' => $id,
					]
				]);

				if($count_cds > 0){
					$res->json([
						'estado' => false,
						'mensaje' => 'No se puede eliminar el plan de estudios porque tiene asociadas '.$count_cds.' competencias disciplinares',
					]);
					return;
				}


				if($pe->delete()){
					$res->json([
						'estado' => true,
						'mensaje' => 'Plan de estudio eliminado',
					]);
				}else{
					$res->json([
						'estado' => false,
						'mensaje' => 'No se puede eliminar el plan de estudios porque se encuentra en uso',
						'dev' => $pe->getError(),
					]);
				}
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'Plan de estudio no encontrado',
				]);
			}
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'Datos incompletos','Datos incompletos',
			]);
		}	
	}

	public function FromPEAsignaturasAction(Request $req, Response $res, Data $data){
		$asignaturas = TblAsignatura::findAll();
		$pe = TblPlanEstudio::findById($req->get('id_pe'));


		$data->addToBody('pe', $pe);
		$data->addToBody('asignaturas', $asignaturas);
		$this->render('academico/pe/modal-asignar-asignatura', $data);
	}
	
	public function AsignaturasAction(Request $req, Response $res, Data $data){
		$asignaturas = [];
		$pe = new TblPlanEstudio(); 
		if($data->isParamsOk()){
			$id = $req->get('id_pe');
			$pe = TblPlanEstudio::findById($id);
			if($pe){
				$asignaturas = $pe->obtenerAsignaturas();
				$data->setSuccessMessage('Asignaturas obtenidas');
			}else{
				$data->setErrorMessage('Plan de estudio no encontrado');
			}
		}

		$res->json($data->forJSON($asignaturas));
	}

	public function EliminarAsignaturaAction(Request $req, Response $res, Data $data){
		$id_pe = $req->post('id_pe');
		$id_asignatura_pe = $req->post('id_asignatura_pe');
		$item = TblAsignaturaPe::findOne([
			'where'=>[
				'id_pe' => $id_pe,
				'id_asignatura_pe' => $id_asignatura_pe,
			]
		]);

		if($item){
			if($item->delete()){
				$data->setSuccessMessage('Asignatura eliminada del plan de estudios');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la asignatura');
			}
		}else{
			$data->setErrorMessage('Asignatura no encontrada en el plan de estudios');
		}
		$res->json($data->forJSON());
	}
	
	public function AgregarAsignaturaAction(Request $req, Response $res, Data $data){
		if($data->isParamsOk()){
			$id_pe = $req->post('id_pe');
			$id_asignatura = $req->post('id_asignatura');
			$existe = TblAsignaturaPe::count([
				'where' => [
					'id_pe' => $id_pe,
					'id_asignatura' => $id_asignatura
				]
			]) > 0;

			if(!$existe){
				$ape = new TblAsignaturaPe();
				$ape->id_asignatura = $id_asignatura;
				$ape->id_pe = $id_pe;
				$ape->id_usuario = $this->user->id_usuario;

				if($ape->save()){
					$data->setSuccessMessage('Asignatura agregada al plan de estudios');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al agregar la asignatura');
				}
			}else{
				$data->setErrorMessage('La asignatura ya está agregada al plan de estudios');
			}
		}
		$res->json($data->forJSON());
	}
	
	public function DetallesAsignaturaPeAction(Request $req, Response $res, Data $data){
		
		if($data->isParamsOk()){
			$idAsignatura = $data->fromBody('id_asignatura');
			$asignatura = TblAsignatura::findById($idAsignatura);

			if($asignatura){
				$data->addToBody('asignatura', $asignatura);
			}else{
				$data->setErrorMessage('No se ha encontrado la asignatura');
			}
		}
	

		$this->render('academico/pe/detalles_asignatura_pe', $data);
	}	
	
	public function PlanesEstudioUsuarioAction(Request $req, Response $res, Data $data){
		$usuario = TblUsuario::findById($this->user->id);
		$pes = [];
		if($usuario){
			$pes = TblPlanEstudio::findAll(['where'=>['id_institucion'=>$usuario->id_institucion]]);
			$data->setSuccessMessage('Planes de estudio obtenidos');
		}else{
			$data->setErrorMessage('Primero debes configurar tu institución');
		}
		$res->json($data->forJSON($pes));
	}

}









