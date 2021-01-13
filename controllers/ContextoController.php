<?php

class ContextoController extends BaseController {

	public function ObtenerTodosAction(Request $req, Response $res, Data $data){
		$whereAssoc = [];
		if($req->existsGet('descrip_contexto')){
			$term = $req->get('descrip_contexto');
			if($term != ""){
				$whereAssoc = [
					'descrip_contexto' => [ 'like'=>$term]
				];
			}
		}

		$contextos = TblContexto::findAll(['where'=>$whereAssoc, 'limit' => 15 ,'select' => ['id_contexto','descrip_contexto']]);
		$lista = [];
		foreach ($contextos as $key => $value) {
			$lista[] = ['id_contexto'=>$value->id_contexto, 'descrip_contexto'=>$value->descrip_contexto];
		}

		$strJSON = $res->json($lista);
	}

	public function NuevoContextoAction(Request $req, Response $res, Data $data){
		$contexto = new TblContexto();

		if($req->isMethodPost()){
			if($data->isParamsOk()){
				$nombre_contexto = $data->fromBody('nombre_contexto');

				if(strlen($nombre_contexto) > 0){
					$s = Sesion::obtener();
					$contexto->descrip_contexto = $nombre_contexto;
					$contexto->id_usuario = $s->id_usuario;


					$existentes = TblContexto::count(['where'=>['descrip_contexto'=>$nombre_contexto]]);
					if($existentes == 0){
						if($contexto->save()){
							$data->setSuccessMessage("contexto guardado correctamente");
							$contexto = new TblContexto();
						}else{
							$data->setErrorMessage($contexto->obtenerError());
						}
					}else{
						$data->setErrorMessage("El contexto ingresado ya existe para otro registro");
					}
				}else{
					$data->setErrorMessage("Valor no válido");
				}
			}else{
				$data->setErrorMessage("Valores faltantes");
			}
		}

		$data->addToBody('contexto', $contexto);
		$this->render('contextos/formulario_contexto', $data);
	}


	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('contextos/index', $data);
	}


	public function ListadoAction(Request $req, Response $res, Data $data){
		
		$page = $req->getIfExists('page',1);
		$count = $req->getIfExists('count',10);
	
		$limit = $count;
		$offset = $count * ($page - 1);
		$whereAssoc = null;
		if($req->existsGet('descrip_contexto')){
			$term = $req->get('descrip_contexto');
			if($term != ""){
				$whereAssoc = [
					'descrip_contexto' => [ 'like' => $term ]
				];
			}
		}
		$result = TblContexto::findAndCountAll([
				'where' => $whereAssoc,
				'order' => 'descrip_contexto asc',
				'limit' => $limit,
				'offset' => $offset,
			]);

		$data->addToBody('total', $result['count']);
		$data->addToBody('contextos', $result['rows']);
		$data->addToBody('count', $count);
		$data->addToBody('page', $page);
		
		$this->render('contextos/listado_contextos', $data);
	}
	
	
	public function EditarContextoAction(Request $req, Response $res, Data $data){

		$contexto = new TblContexto();
		if($data->isParamsOk()){
			$id_contexto = $data->fromBody('id_contexto');
			$contexto = TblContexto::findById($id_contexto);
			if($contexto){
				if($data->isPost()){
					$descrip_contexto = $req->postIfExists('nombre_contexto', '');
					$contexto->descrip_contexto = $descrip_contexto;
					if($contexto->update()){
						$data->setSuccessMessage('Contexto actualizado correctamente');						
					}else{
						$data->setErrorMessage('Ha ocurrido un error. '.$contexto->getError());
					}
				}
			}else{
				$data->setErrorMessage("No se ha encontrado el contexto solicitado");
			}
		}else{
			$data->setErrorMessage("No se recibieron todos los datos necesario");
		}

		$data->addToBody('contexto', $contexto);	
		$data->addToBody('existente', true);	
		$this->render('contextos/formulario_contexto', $data);
	}

	public function CrearContextoAction(Request $req, Response $res, Data $data){
		$contexto = $req->post('contexto');
		if($contexto){
			$tbl = new TblContexto([
				'descrip_contexto' => $contexto,
				'id_usuario' => $this->user->id,
			]);

			$db = new DBHelper();
			$conteo = $db->readScalar('select count(*) from '.TblContexto::$table.' where descrip_contexto like ?', [$tbl->descrip_contexto]);
			if($conteo > 0){
				$res->json([
					'estado' => false,
					'mensaje' => 'Ya existe un contexto con el mismo nombre',
				]);
			}


			if($tbl->save()){
				$res->json([
					'estado' => true,
					'mensaje' => 'El contexto ha sido creado correctamente',
					'data' => $tbl,
				]);
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'Ha ocurrido un error al intentar guardar el nuevo contexto',
				]);
			}
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'La petición no es válida',
			]);
		}
	}


	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id', 0);
		$contexto = TblContexto::findById($id);
		if($contexto){
			if($contexto->delete()){
				$data->setSuccessMessage('Contexto eliminado correctamente');
			}else{
				$data->setErrorMessage('No se ha podido eliminar el contexto ya que actualmente se encuentra en uso.');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el contexto seleccionado. ');
		}
		$res->json($data->forJSON());
	}	

}