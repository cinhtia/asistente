<?php 

class RecursoController extends BaseController{
	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('recurso/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);
		$this->limit = $req->get('count', 10);

		$this->result = TblRecurso::findAndCountAll([
			'limit' => $this->limit,
			'offset' => ($this->page-1)*$this->limit,
			'order' => 'nombre asc',
		]);
	
		$this->render('recurso/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_recurso', 0);
		$this->recurso = new TblRecurso(['id_recurso'=>0]);

		if($id != 0){
			$this->recurso = TblRecurso::findById($id);
		}
	
		$this->nuevo = $this->recurso->id_recurso == 0;
		$this->render('recurso/formulario', $data);
	}
	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_recurso', 0);
		$nombre = $req->post('nombre');
		$descripcion = $req->post('descripcion');

		$recurso = new TblRecurso();
		if($id != 0){
			$recurso = TblRecurso::findById($id);
			if($recurso){
				$recurso->nombre = $nombre;
				$recurso->descripcion = $descripcion;
				if($recurso->update()){
					$data->setSuccessMessage('El recurso ha sido actualizado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$recurso->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el recurso');
			}
		}else{
			$recurso->nombre = $nombre;
			$recurso->descripcion = $descripcion;
			$recurso->id_usuario = $this->user->id;
			if($recurso->save()){
				$data->setSuccessMessage('El recurso ha sido guardado correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar guardar el recurso. '.$recurso->getError());
			}
		}


		$res->json($data->forJSON());
	}

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_recurso', 0);

		$recurso = TblRecurso::findById($id);
		if($recurso){
			$uso = TblAdaRecurso::findOne([
				'where' => [
					'id_recurso' => $id,
				]
			]);

			if($uso){
				$data->setErrorMessage('No se puede eliminar el recurso porque se encuentra en uso');
			}else{
				if($recurso->delete()){
					$data->setSuccessMessage('El recurso ha sido eliminado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar el recurso. '.$recurso->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el recurso solicitado');
		}

		$res->json($data->forJSON());
	}
}