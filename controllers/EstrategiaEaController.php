<?php

class EstrategiaEaController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		
	
		$this->render('academico/ea/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		
		$this->page = $req->getIfExists('page', 1);
		$this->limit = $req->getIfExists('count', 10);
		$offset = ($this->page-1)*$this->limit;

		$this->result = TblEstrategiaEa::findAndCountAll([
				'limit' => $this->limit,
				'offset' => $offset,
				'order' => 'descripcion_ea asc'
			]);

		$this->render('academico/ea/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$this->ea = new TblEstrategiaEa();
		$id = $req->getIfExists('id_ea', 0);

		if($id != 0){
			$this->ea = TblEstrategiaEa::findById($id);
		}else{
			$this->ea->id_estrategia_ea = 0;
		}

		$this->render('academico/ea/formulario', $data);
	}

	public function GuardarAction(Request $req, Response $res, Data $data){
		
		$id_estrategia_ea = $req->post('id_estrategia_ea');
		$descripcion_ea = $req->post('descripcion_ea');
		$explicacion_ea = $req->post('explicacion_ea');
		
		if($id_estrategia_ea != 0){
			$ea = TblEstrategiaEa::findById($id_estrategia_ea);
			if($ea){
				$ea->descripcion_ea = $descripcion_ea;
				$ea->explicacion_ea = $explicacion_ea;
				if($ea->update()){
					$data->setSuccessMessage('La estrategia ha sido actualizada');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al actualizar. '.$ea->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la estrategia de aprendizaje');
			}
		}else{
			$ea = new TblEstrategiaEa();
			$ea->descripcion_ea = $descripcion_ea;
			$ea->explicacion_ea = $explicacion_ea;
			$ea->id_usuario = $this->user->id;
			if($ea->save()){
				$data->setSuccessMessage('La estrategia ha sido guardada');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar la estrategia. '.$ea->getError());
			}
		}
	
		$res->json($data->forJSON());
	}
	
	public function EliminarAction(Request $req, Response $res, Data $data){
		$id_estrategia_ea = $req->post('id_estrategia_ea');
		$ea = TblEstrategiaEa::findById($id_estrategia_ea);

		if($ea){

			$uso = TblAdaEstrategiaEa::findOne([
				'where' => [
					'id_estrategia_ea' => $id_estrategia_ea,
				]
			]);

			if($uso){
				$data->setErrorMessage('No se puede eliminar la estrategia de enseñanza-aprendizaje porque se encuentra en uso');
			}else{
				if($ea->delete()){
					$data->setSuccessMessage('La estrategia de aprendizaje ha sido eliminada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al eliminar el registro. '.$ea->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la estrategia de aprendizaje');
		}
	
		$res->json($data->forJSON());
	}
	
}