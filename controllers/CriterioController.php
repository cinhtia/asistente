<?php

class CriterioController extends BaseController {

	public function ObtenerTodosAction(Request $req, Response $res, Data $data){
		$columnas = ['id_criterio','descrip_criterio'];
		$whereAssoc = null;
		if($req->existsGet('descrip_criterio')){
			$term = $req->get('descrip_criterio');
			if($term != ""){
				$whereAssoc = [
					'descrip_criterio' => [ 'like'=>$term]
				];
			}
		}

		$criterios = TblCriterio::findAll(['where'=>$whereAssoc,'limit'=>15]);
		$strJSON = $res->json($criterios);
	}

	public function NuevoCriterioAction(Request $req, Response $res, Data $data){
		$criterio = new TblCriterio();
		if($req->isMethodPost()){
			if($data->isParamsOk()){
				$nombre_criterio = $data->fromBody('nombre_criterio');

				if(strlen($nombre_criterio) > 0){
					$s = Sesion::obtener();
					$criterio->descrip_criterio = $nombre_criterio;
					$criterio->id_usuario = $s->id_usuario;


					$existentes = TblCriterio::count(['where'=>['descrip_criterio'=>$nombre_criterio]]);
					if($existentes == 0){
						if($criterio->save()){
							$data->setSuccessMessage("criterio guardado correctamente");
							$criterio = new TblCriterio();
						}else{
							$data->setErrorMessage($criterio->obtenerError());
						}
					}else{
						$data->setErrorMessage("Ya existe un criterio con el nombre ingresado.");
					}
				}else{
					$data->setErrorMessage("Valor no válido");
				}
			}else{
				$data->setErrorMessage("Valores faltantes");
			}
		}

		$data->addToBody('criterio', $criterio);
		$this->render('criterios/formulario_criterio', $data);
	}

	public function IndexAction(Request $req, Response $res, Data $data){
		// a
		// b
		$this->render('criterios/index', $data);
	}
	

	public function ListadoAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page', 1);
		$count = $req->getIfExists('count', 10);
	
		$limit = $count;
		$offset = $count * ($page - 1);
		$columnas = null;
		$dbTransaction = null;
		$whereAssoc = null;
		$orderBy = "fecha_creacion desc"; // "----- asc";
	
		if($req->existsGet('descrip_criterio')){
			$term = $req->get('descrip_criterio');
			if($term != ""){
				$whereAssoc = [
					'descrip_criterio' => [ 'like', $term ]
				];
			}
		}

		$result = TblCriterio::findAndCountAll([
			'limit' => $limit,
			'offset' => $offset,
			'where' => $whereAssoc,
			'order' => 'descrip_criterio asc',
			]);
	
		
		$data->addToBody('total', $result['count']);
		$data->addToBody('criterios', $result['rows']);
		$data->addToBody('count', $count);
		$data->addToBody('page', $page);
	
		$this->render('criterios/listado_criterios', $data);
	}
		

	public function EditarCriterioAction(Request $req, Response $res, Data $data){
		
		$criterio = new TblCriterio();

		if($data->isParamsOk()){
			$id_criterio = $data->fromBody('id_criterio');
			$criterio = TblCriterio::findById($id_criterio);

			if($criterio->id_criterio != 0){
				if($req->isMethodPost()){
					$descrip_criterio = $data->fromBody('nombre_criterio');
					$existentes = 0;
					if($criterio->descrip_criterio != $descrip_criterio){
						$existentes = TblCriterio::count(['where'=>['descrip_criterio'=>$descrip_criterio]]);
					}

					if($existentes == 0){
						$criterio->descrip_criterio = $descrip_criterio;
						if($criterio->update()){
							$data->setSuccessMessage("Criterio actualizado correctamente");
						}else{
							$data->setErrorMessage($criterio->obtenerError());
						}
					}else{
						$data->setErrorMessage("El nombre del criterio ya existe para otro registro");
					}
				}
			}else{
				$data->setErrorMessage('Criterio no encontrado');
			}
		}else{
			$data->setErrorMessage("Algunos valores no fueron recibidos");
		}

		$data->addToBody('criterio', $criterio);
		$data->addToBody('existente', true);
	
		$this->render('criterios/formulario_criterio', $data);
	}

	public function CrearCriterioAction(Request $req, Response $res, Data $data){
		$criterio = $req->post('criterio');
		if($criterio){
			$tbl = new TblCriterio([
				'descrip_criterio' => $criterio,
				'id_usuario' => $this->user->id,
			]);

			$db = new DBHelper();
			$conteo = $db->readScalar('select count(*) from '.TblCriterio::$table.' where descrip_criterio like ?', [$tbl->descrip_criterio]);
			if($conteo > 0){
				$res->json([
					'estado' => false,
					'mensaje' => 'Ya existe un criterio con el mismo nombre',
				]);
			}

			if($tbl->save()){
				$res->json([
					'estado' => true,
					'mensaje' => 'Criterio creado correctamente',
					'data' => $tbl,
				]);
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'Ha ocurrido un error al guardar el criterio',
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
		$id = $req->post('id');
		$criterio = TblCriterio::findById($id);
		if($criterio){
			$conteo_uso = TblCriterioCompetencia::count([
				'where' => [
					'id_criterio' => $id,
				]
			]);

			if($conteo_uso > 0){
				$data->setErrorMessage('El criterio no se puede eliminar porque se encuentra en uso en resultados de aprendizaje');
			}else{
				if($criterio->delete()){
					$data->setSuccessMessage('Criterio eliminado correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error. '.$criterio->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el criterio seleccionado');
		}
		$res->json($data->forJSON());
	}
}