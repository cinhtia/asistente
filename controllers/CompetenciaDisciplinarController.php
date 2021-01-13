<?php

class CompetenciaDisciplinarController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		
	
		$this->render('academico/cd/index', $data);
	}


	public function ListAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page',1);
		$count = $req->getIfExists('count',10);

		$criteria = [
			'limit' => $count,
			'offset'=> ($page-1) * $count,
			'order' => 'descripcion asc',
		];

		$this->result = TblCompetenciaDisciplinar::findAndCountAll($criteria);
		$this->page = $page;
		$this->count = $count;
	
		$this->render('academico/cd/listado', $data);
	}


	public function FormAction(Request $req, Response $res, Data $data){
		$this->cd = new TblCompetenciaDisciplinar();
		$this->cd->id_competencia_disciplinar = 0;
		$this->nuevo = true;
		$id = $req->getIfExists('id_competencia_disciplinar',0);
		if($id != 0){
			$this->nuevo = false;
			$this->cd = TblCompetenciaDisciplinar::findById($id);
		}
	
		$this->render('academico/cd/formulario', $data);
	}
	

	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_competencia_disciplinar',0);
		$descripcion = $req->postIfExists('descripcion',null);
		$plan_estudio_id = $req->postIfExists('plan_estudio_id',null);

		if($id != 0){
			$cd = TblCompetenciaDisciplinar::findById($id);
			if($cd){
				$cd->descripcion = $descripcion;
				$cd->plan_estudio_id = $plan_estudio_id;
				if($cd->update()){
					$data->setSuccessMessage('La competencia disciplinar ha sido actualizada');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$cd->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la competencia solicitada');
			}
		}else{
			$cd = new TblCompetenciaDisciplinar();
			$cd->descripcion = $descripcion;
			$cd->id_usuario = $this->user->id;
			$cd->plan_estudio_id = $plan_estudio_id;
			if($cd->save()){
				$data->setSuccessMessage('La competencia disciplinar ha sido guardada');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar la competencia. '.$cd->getError());
			}
		}

		$res->json($data->forJSON());
	}
	

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_competencia_disciplinar',0);
		if($id){
			$cd = TblCompetenciaDisciplinar::findById($id);
			if($cd){

				$uso = TblCompetenciaDisciplinarAsignatura::findAll([
					'where' => [
						'id_competencia_disciplinar' => $cd->id_competencia_disciplinar
					],
					'include' => [
						['localField' => 'id_asignatura', 'select' => ['nombre_asignatura']],
					]
				]);

				if(count($uso) > 0){
					$asignaturas = '<ul>';
					foreach ($uso as $index => $item) {
						$asignaturas .= '<li><strong>'.$item->nombre_asignatura.'</strong></li>';
					}
					$asignaturas .= '</ul>';

					$res->json([
						'estado' => false,
						'mensaje' => 'No es posible eliminar la competencia disciplinar porque se encuentra en uso por las asignaturas: '.($asignaturas),
					]);
					return;
				}

				if($cd->delete()){
					$data->setSuccessMessage('La competencia disciplinar ha sido eliminada');
				}else{
					$data->setErrorMessage('Ha ocurrido un error. '.$cd->getError());	
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la competencia solicitada');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la competencia solicitada');
		}
		$res->json($data->forJSON());
	}
	
	
	

}