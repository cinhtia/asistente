<?php

class ContenidoController extends BaseController {

	public function ObtenerTodosAction(Request $req, Response $res, Data $data){
		$criteria = [
			'select' => ['id_contenido','descrip_contenido'],
			'where' => [],
			'order' => 'descrip_contenido asc',
			'limit' => 15,
		];

		$term = $req->getIfExists('descrip_contenido', null);
		if($term != null){
			$criteria['where']['descrip_contenido'] = [ 'like' => $term ];
		}
		
		$contenidos = TblContenido::findAll($criteria);
		$lista = [];
		foreach ($contenidos as $key => $value) {
			$lista[] = ['id_contenido'=>$value->id_contenido,'descrip_contenido'=>$value->descrip_contenido];
		}
		$strJSON = $res->json($lista);
	}

	public function NuevoContenidoAction(Request $req, Response $res, Data $data){
		$contenido = new TblContenido();

		if($req->isMethodPost()){
			if($data->isParamsOk()){
				$nombre_contenido = $data->fromBody('nombre_contenido');

				if(strlen($nombre_contenido) > 0){

					$existente = TblContenido::count(['where'=>['descrip_contenido'=>$nombre_contenido]]);

					if($existente == 0){
						$s = Sesion::obtener();
						$contenido->descrip_contenido = $nombre_contenido;
						$contenido->id_usuario = $s->id_usuario;

						if($contenido->save()){
							$data->setSuccessMessage("Contenido guardado correctamente");
							$contenido = new TblContenido();
						}else{
							$data->setErrorMessage($contenido->obtenerError());
						}
					}else{
						$data->setErrorMessage("Ese contenido ya existe y puede ser utilizado.");
					}
					
				}else{
					$data->setErrorMessage("Valor no válido");
				}
			}else{
				$data->setErrorMessage("Valores faltantes");
			}
		}

		$data->addToBody('contenido', $contenido);

		$this->render('contenidos/formulario_contenido', $data);
	}

	public function CrearContenidoAction(Request $req, Response $res, Data $data){
		$nombre_contenido = $req->post('nombre_contenido');
		$tblContenido = new TblContenido([
			'descrip_contenido' => $nombre_contenido,
			'id_usuario' => $this->user->id,
		]);

		$db = new DBHelper();
		$conteo = $db->readScalar('select count(*) from '.TblContenido::$table.' where descrip_contenido like ?', [$tblContenido->descrip_contenido]);
		if($conteo > 0){
			$res->json([
				'estado' => false,
				'mensaje' => 'Ya existe un contenido con el mismo nombre',
			]);
		}

		if($tblContenido->save()){
			$res->json([
				'estado' => true,
				'mensaje' => 'Contenido creado correctamente',
				'data' => $tblContenido,
			]);
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'Ha ocurrido un error al crear el contenido',
			]);
		}
	}


	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('contenidos/index', $data);
	}
	

	public function ListadoAction(Request $req, Response $res, Data $data){
		
		$page = $req->getIfExists('page',1);
		$count = $req->getIfExists('count', 10);
	
		$limit = $count;
		$offset = $count * ($page - 1);

		$criteria = [
			'select' => ['id_contenido','descrip_contenido'],
			'where' => [],
			'order' => 'descrip_contenido asc',
			'limit' => $limit,
			'offset' => $offset,
		];

		$term = $req->getIfExists('descrip_contenido', null);
		if($term != null){
			$criteria['where']['descrip_contenido'] = [ 'like' => $term ];
		}

		$result = TblContenido::findAndCountAll($criteria);
		
		$data->addToBody('total', $result['count']);
		$data->addToBody('contenidos', $result['rows']);
		$data->addToBody('count', $count);
		$data->addToBody('page', $page);
	
		$this->render('contenidos/listado_contenidos', $data);
	}
			

	public function EditarContenidoAction(Request $req, Response $res, Data $data){
		
		$id = $data->fromBody('id_contenido');

		if($data->isParamsOk()){
			$contenido = TblContenido::findOne(['where'=>['id_contenido'=>$id]]);
			if($data->isPost()){
				$descrip_contenido = $data->fromBody('nombre_contenido');
				$existentes = 0;

				if($descrip_contenido != $contenido->descrip_contenido){
					$existentes = TblContenido::count(['where'=>['descrip_contenido'=>$descrip_contenido]]);
				}
				
				if($existentes==0){

					$contenido->descrip_contenido = $descrip_contenido;

					if($contenido->update()){
						$data->setSuccessMessage("El contenido ha sido actualizado correctamente");
					}else{
						$data->setErrorMessage($contenido->obtenerError());
					}

				}else{
					$data->setErrorMessage("Ya existe un registro con el nombre ingresado");
				}
			}
		}else{
			$data->setErrorMessage('Parámetros incompletos');
		}

	
		$data->addToBody('contenido', $contenido);
		$data->addToBody('existente', true);
	
		$this->render('contenidos/formulario_contenido', $data);
	}


	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id');
		if($id){
			$contenido = TblContenido::findById($id);
			if($contenido){
				if($contenido->delete()){
					$data->setSuccessMessage('Contenido eliminado correctamente');
				}else{
					$data->setErrorMessage('No se pudo eliminar el contenido seleccionado ya que actualmente se encuentra en uso.');
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el contenido especificado');
			}
		}else{
			$data->setErrorMessage('Debes especificar un contenido');
		}
		$res->json($data->forJSON());
	}
	
	

}