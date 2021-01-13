<?php 

/**
 * Controlador herramientas moodle
 */
class HerramientaController extends BaseController{
	
	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('herramienta/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);	
		$this->limit = $req->get('count', 10);	

		$this->result = TblHerramienta::findAndCountAll([
			'limit' => $this->limit,
			'offset' => $this->limit * ($this->page - 1),
			'order' => 'descripcion_herramienta asc',
		]);
	
		$this->render('herramienta/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		
		$id = $req->get('id_herramienta', 0);
		$this->herramienta = new TblHerramienta([
			'id_herramienta'=> 0,
			'estrategias_didacticas' => '',
			'palabras_asociadas' => '',
		]);
		if($id != 0){
			$this->herramienta = TblHerramienta::findById($id);
		}

		$this->nuevo = $this->herramienta->id_herramienta == 0;
		$this->render('herramienta/formulario', $data);
	}
	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_herramienta', 0);
		$descripcion = $req->post('descripcion_herramienta');
		$explicacion = $req->post('explicacion_herramienta');
		$estrategias_didacticas = $req->post('estrategias_didacticas');
		$palabras_asociadas = $req->post('palabras_asociadas');
		if($id != 0){
			$herramienta = TblHerramienta::findById($id);
			if($herramienta){
				$herramienta->descripcion_herramienta = $descripcion;
				$herramienta->explicacion_herramienta = $explicacion;
				$herramienta->estrategias_didacticas = $estrategias_didacticas;
				$herramienta->palabras_asociadas = $palabras_asociadas;
				if($herramienta->update()){
					$data->setSuccessMessage('La herramienta ha sido actualizada');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar la herramienta. '.$herramienta->getError() );
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la herramienta solicitada.');
			}
		}else{
			$herramienta = new TblHerramienta([
				'descripcion_herramienta' => $descripcion,
				'explicacion_herramienta' => $explicacion,
				'id_usuario' => $this->user->id,
				'estrategias_didacticas' => $estrategias_didacticas,
				'palabras_asociadas' => $palabras_asociadas,
			]);

			if($herramienta->save()){
				$data->setSuccessMessage('La herramienta ha sido guardada');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar los datos. '.$herramienta->getError());
			}
		}
		$res->json($data->forJSON());
	}

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_herramienta', 0);
		$herramienta = TblHerramienta::findById($id);
		if($herramienta){
			$uso = TblAda::findOne([
				'where' => [
					'id_herramienta' => $id
				]
			]);

			if($uso){
				$data->setErrorMessage('No se puede eliminar la herramienta porque se encuentra en uso');
			}else{
				if($herramienta->delete()){
					$data->setSuccessMessage('La herramienta ha sido eliminada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la herramienta. '.$herramienta->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la herramienta solicitada');
		}
		$res->json($data->forJSON());
	}
	
	public function JSONListAction(Request $req, Response $res, Data $data){
		$sql = "select id_herramienta, descripcion_herramienta from ".TblHerramienta::$table;
		$herramientas = DBHelper::singleton()->read($sql);
		$data->setSuccessMessage('Herramientas obtenidas');
		$res->json($data->forJSON($herramientas));
	}
}