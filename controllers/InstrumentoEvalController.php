<?php 

/**
 * Controlador para modelo de instrumentos de evaluacion
 */
class InstrumentoEvalController extends BaseController{
	
	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('instrumento-eval/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		
		$this->page = $req->get('page',1);
		$this->limit = $req->get('count',10);

		$this->result = TblInstrumentoEval::findAndCountAll([
			'limit' => $this->limit,
			'offset' => $this->limit * ($this->page-1),
			'select' => ['id_instrumento_eval','descripcion_instrum_eval','explicacion_instrum_eval'],
			'order' => 'descripcion_instrum_eval asc'
		]);
	
		$this->render('instrumento-eval/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_instrumento_eval', 0);
		$this->instrumento = new TblInstrumentoEval(['id_instrumento_eval'=>0]);
		if($id != 0){
			$this->instrumento = TblInstrumentoEval::findById($id);
		}

		$this->nuevo = $this->instrumento->id_instrumento_eval == 0;
		$this->render('instrumento-eval/formulario', $data);
	}

	public function GuardarAction(Request $req, Response $res, Data $data){
		$id_instrumento_eval = $req->post('id_instrumento_eval', 0);
		$descripcion_instrum_eval = $req->post('descripcion_instrum_eval');
		$explicacion_instrum_eval = $req->post('explicacion_instrum_eval');

		if($id_instrumento_eval != 0){
			$instrumento = TblInstrumentoEval::findById($id_instrumento_eval);
			if($instrumento){
				$instrumento->descripcion_instrum_eval = $descripcion_instrum_eval;
				$instrumento->explicacion_instrum_eval = $explicacion_instrum_eval;
				if($instrumento->update()){
					$data->setSuccessMessage('El instrumento de evaluación ha sido actualizado.');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar el instrumento. '.$instrumento->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el instrumento a actualizar');
			}
		}else{
			$instrumento = new TblInstrumentoEval();
			$instrumento->descripcion_instrum_eval = $descripcion_instrum_eval;
			$instrumento->explicacion_instrum_eval = $explicacion_instrum_eval;
			$instrumento->id_usuario = $this->user->id;
			if($instrumento->save()){
				$data->setSuccessMessage('El instrumento de evaluación ha sido creado');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al crear el instrumento. '.$instrumento->getError());
			}
		}
	
		$res->json($data->forJSON());
	}

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_instrumento_eval', 0);

		$instrumento = TblInstrumentoEval::findById($id);
		if($instrumento){

			$uso = TblAda::findOne([
				'where' => [
					'id_instrumento_eval' => $id,
				]
			]);

			if($uso){
				$data->setErrorMessage('No se puede eliminar el instrumento de evaluación porque se encuentra en uso');
			}else{
				if($instrumento->delete()){
					$data->setSuccessMessage('El instrumento ha sido eliminado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al eliminar el instrumento de evaluación');
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el instrumento solicitado');
		}
	
		$res->json($data->forJSON());
	}
	
}