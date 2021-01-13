<?php

use TblDesagregadoContenido as TblDesagregado;

class DesagregadoController extends BaseController{


	public function IndexAction(Request $req, Response $res, Data $data){
		

		$this->render('desagregado/index', $data);
	}


	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);
		$this->limit = $req->get('limit', 10);
		$term = $req->get('term');

		$criteria = [
			'offset' => ($this->page-1)*$this->limit,
			'limit' => $this->limit,
			'order'=>'descripcion asc'
		];

		if($term){
			$criteria['where'] = [
				'descripcion' => ['like', $term]
			];
		}


		$this->result = TblDesagregado::findAndCountAll($criteria);
	
		$this->render('desagregado/listado', $data);
	}
	

	public function FormAction(Request $req, Response $res, Data $data){
		$this->desde_form_contenido = $req->get('desde_form_contenido', 0);
		$this->desagregado = new TblDesagregado(['id_desagregado_contenido'=>0]);
		$id = $req->get('id_desagregado',0);
		if($id != 0){
			$this->desagregado = TblDesagregado::findById($id);
		}
		$this->nuevo = $this->desagregado->id_desagregado_contenido == 0;
		$this->render('desagregado/formulario', $data);
	}
	


	public function GuardarAction(Request $req, Response $res, Data $data){
		$id_desagregado_contenido = $req->postIfExists('id_desagregado_contenido', 0);
		$descripcion = $req->post('descripcion');
		$desagregado = new TblDesagregado();
		if($id_desagregado_contenido != 0){
			$desagregado = TblDesagregado::findById($id_desagregado_contenido);
			if(!$desagregado){
				$data->setErrorMessage('El desagregado no ha sido encontrado.');
			}else{
				$desagregado->descripcion = $descripcion;
				if($desagregado->update()){
					$data->setSuccessMessage('El desagregado ha sido actualizado correctamente.');
				}else{
					$data->setErrorMessage('Ha ocurrido un error. '.$desagregado->getError());
				}
			}
		}else{
			$desagregado->descripcion = $descripcion;
			$desagregado->id_usuario = $this->user->id;
			if($desagregado->save()){
				$data->setSuccessMessage('El desagregado ha sido creado correctamente.');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al crear el desagregado. '.$desagregado->getError());
			}
		}

		$res->json($data->forJSON($desagregado));
	}
	

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_desagregado', 0);

		$desagregado = TblDesagregado::findById($id);
		if($desagregado){
			if($desagregado->delete()){
				$data->setSuccessMessage('El desagregado seleccionado ha sido eliminado correctamente.');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar eliminar. '.$desagregado->getError());
			}
		}else{
			$data->setErrorMessage('El registro no ha sido encontrado.');
		}
	
		$res->json($data->forJSON());
	}
	


}