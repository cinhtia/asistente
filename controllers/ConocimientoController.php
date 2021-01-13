<?php

class ConocimientoController extends BaseController {
	
	public function IndexAction(Request $req, Response $res, Data $data){
		
		
		$this->render('conocimiento/index', $data);
	}

	public function ListadoAction(Request $req, Response $res, Data $data){
			
		$page = $req->getIfExists('page', 1);
		$count = 10;
		
		$limit = 10;
		$offset = $limit * ($page - 1);
		$whereAssoc = null;
		if($req->existsGet('descrip_conocimiento')){
			$term = $req->get('descrip_conocimiento');
			if($term != ""){
				$whereAssoc = [
					'descrip_conocimiento' => ['like', $term]
				];
			}
		}

		$result = TblCompo_conocimiento::findAndCountAll([
				'limit' =>$limit,
				'offset' => $offset,
				'where' => $whereAssoc,
				'order' => 'descrip_conocimiento asc',
			]);
		
		
		$data->addToBody('total', $result['count']);
		$data->addToBody('lista', $result['rows']);
		$data->addToBody('count', $limit);
		$data->addToBody('page', $page);
	
		$this->render('conocimiento/listado', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		
		$conocimiento = new TblCompo_conocimiento();
		if($data->isPost()){
			if($data->isParamsOk() ){
				$descrip_conocimiento = $data->fromBody('descrip_conocimiento');

				$conocimiento->descrip_conocimiento = $descrip_conocimiento;
				$conocimiento->id_usuario = $this->user->id;
				if($conocimiento->save()){
					$conocimiento = new TblCompo_conocimiento();
					$data->setSuccessMessage('Conocimiento creado correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al crear el conocimiento. '.$conocimiento->obtenerError());
				}

			}
		}
		$data->addToBody('conocimiento', $conocimiento);
		$this->render('conocimiento/formulario', $data);
	}

	public function EditarAction(Request $req, Response $res, Data $data){
		$conocimiento = new TblCompo_conocimiento();
		
		if($data->isParamsOk()){
			$id = $data->fromBody('id_compo_conocimiento');
			$conocimiento = TblCompo_conocimiento::findById($id);
			if($conocimiento){
				if($data->isPost()){
					$conocimiento->descrip_conocimiento = $data->fromBody('descrip_conocimiento');
					if($conocimiento->update()){
						$data->setSuccessMessage('Conocimiento actualizado correctamente');
					}else{
						$data->setErrorMessage('Ha ocurrido un error al actualizar el conocimiento. '.$conocimiento->obtenerError());
					}
				}
			}else{
				$data->setErrorMessage('Conocimiento no encontrado');
			}
		}

		$data->addToBody('conocimiento', $conocimiento);
		$data->addToBody('nuevo', false);
		$this->render('conocimiento/formulario', $data);
	}
	
	public function EliminarAction(Request $req, Response $res, Data $data){
		
		if($data->isParamsOk()){
			$id = $data->fromBody('id_compo_conocimiento');
			$conocimiento = TblCompo_conocimiento::findById($id);
			if($conocimiento->id_compo_conocimiento != 0){
				if($conocimiento->delete()){
					$data->setSuccessMessage('Conocimiento eliminado correctamente');
				}else{
					$data->setErrorMessage('No se ha podido eliminar el conocimiento ya que actualmente se encuentra en uso');
				}
			}else{
				$data->setErrorMessage('Conocimiento no encontrado');
			}
		}

		$res->json($data->forJSON());
	}

	public function CrearAction(Request $req, Response $res, Data $data){
		$descrip_conocimiento = $req->post('descrip_conocimiento');
		if($descrip_conocimiento){
			$query = "select * from ".TblCompo_conocimiento::$table." where descrip_conocimiento like ?";
			$db = new DBHelper();
			$encontrado = $db->read($query, [$descrip_conocimiento], true);
			if($encontrado){
				$res->json([
					'estado' => false,
					'mensaje' => 'Ya existe un conocimiento con el mismo nombre',
				]);
			}else{
				$con = new TblCompo_conocimiento([
					'descrip_conocimiento' => $descrip_conocimiento
				]);

				if($con->save()){
					$res->json([
						'estado' => true,
						'mensaje' => 'Conocimiento creado correctamente',
						'data' => $con,
					]);
				}else{
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al crear el conocimiento',
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