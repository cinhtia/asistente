<?php

class HabilidadController extends BaseController{
	public function IndexAction(Request $req, Response $res, Data $data){
		
		
		$this->render('habilidad/index', $data);
	}

	public function ListadoAction(Request $req, Response $res, Data $data){
			
		$page = $req->getIfExists('page', 1);
		$count = $req->getIfExists('count', 10);
		
		$limit = 10;
		$offset = $limit * ($page - 1);
		$whereAssoc = null;

		if($req->existsGet('descrip_habilidad')){
			$term = $req->get('descrip_habilidad');
			if($term != ""){
				$whereAssoc = [
					'descrip_habilidad' => ['like', $term]
				];
			}
		}

		$result = TblCompo_habilidad::findAndCountAll([
			'limit' => $limit,
			'offset' => $offset,
			'where' => $whereAssoc,
			'order' => 'descrip_habilidad asc',
		]);
		
		$data->addToBody('total', $result['count']);
		$data->addToBody('lista', $result['rows']);
		$data->addToBody('count', $limit);
		$data->addToBody('page', $page);
	
		$this->render('habilidad/listado', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		
		$habilidad = new TblCompo_habilidad();
		if($data->isPost()){
			if($data->isParamsOk() ){
				$descrip_habilidad = $data->fromBody('descrip_habilidad');

				$habilidad->descrip_habilidad = $descrip_habilidad;
				$habilidad->id_usuario = $this->user->id;
				if($habilidad->save()){
					$habilidad = new TblCompo_habilidad();
					$data->setSuccessMessage('Habilidad creado correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al crear la habilidad. '.$habilidad->obtenerError());
				}

			}
		}
		$data->addToBody('habilidad', $habilidad);
		$this->render('habilidad/formulario', $data);
	}

	public function EditarAction(Request $req, Response $res, Data $data){
		$habilidad = new TblCompo_habilidad();
		
		if($data->isParamsOk()){
			$id = $data->fromBody('id_compo_habilidad');
			$habilidad = TblCompo_habilidad::findById($id);
			if($habilidad){
				if($data->isPost()){
					$habilidad->descrip_habilidad = $data->fromBody('descrip_habilidad');
					if($habilidad->update()){
						$data->setSuccessMessage('Habilidad actualizada correctamente');
					}else{
						$data->setErrorMessage('Ha ocurrido un error al actualizar la habilidad. '.$habilidad->obtenerError());
					}
				}
			}else{
				$data->setErrorMessage('Habilidad no encontrado');
			}
		}

		$data->addToBody('habilidad', $habilidad);
		$data->addToBody('nuevo', false);
		$this->render('habilidad/formulario', $data);
	}
	
	public function EliminarAction(Request $req, Response $res, Data $data){
		
		if($data->isParamsOk()){
			$id = $data->fromBody('id_compo_habilidad');
			$habilidad = TblCompo_habilidad::findById($id);
			if($habilidad){
				if($habilidad->delete()){
					$data->setSuccessMessage('Habilidad eliminada correctamente');
				}else{
					$data->setErrorMessage('No se ha podido eliminar la habilidad ya que actualmente se encuentra en uso');
				}
			}else{
				$data->setErrorMessage('Habilidad no encontrada');
			}
		}

		$res->json($data->forJSON());
	}

	public function CrearAction(Request $req, Response $res, Data $data){
		$descrip_habilidad = $req->post('descrip_habilidad');
		if($descrip_habilidad){
			$encontrado = TblCompo_habilidad::findOne([
				'where' => [
					'descrip_habilidad' => ['like', $descrip_habilidad, false]
				]
			]);

			if($encontrado){
				$res->json([
					'estado' => false,
					'mensaje' => 'Ya existe una habilidad con el mismo nombre',
				]);
			}else{
				$chab = new TblCompo_habilidad([
					'descrip_habilidad' => $descrip_habilidad,
				]);

				if($chab->save()){
					$res->json([
						'estado' => true,
						'mensaje' => 'Habilidad creada',
						'data' => $chab,
					]);
				}else{
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al crear la habilidad',
					]);
				}
			}
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'La petición no es válida',
			]);
		}
	}
}