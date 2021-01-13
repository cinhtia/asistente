<?php 


class VerboController extends BaseController {

	// listado con renderizado html
	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('verbos/index_verbos', $data);
	}

	public function ListadoAction(Request $req, Response $res, Data $data){
		$page = $req->existsGet('page') ? $req->get('page') : 1;
		$count = 10;
		$limit = $count;
		$offset = $count * ($page - 1);
		$whereAssoc = null;

		$set = false;

		if($req->existsGet('descrip_verbo')){
			$term = $req->get('descrip_verbo');
			if($term != ""){
				$set = true;
				$whereAssoc = [
					'descrip_verbo' => [ 'like'=>$term ]
				];
			}
		}

		if($req->existsGet('disponible')){
			$disp = $req->get('disponible');
			if($disp != ""){
				if($set){
					$whereAssoc = [];
				}
				
				$whereAssoc['disponible'] = $disp;

			}
		}

		$result = TblVerbo::findAndCountAll([
				'where'=> $whereAssoc != null ? $whereAssoc : [],
				'order' => 'descrip_verbo asc',
				'limit' => $limit, 
				'offset' => $offset,
			]);

		$data->addToBody('total',$result['count']);
		$data->addToBody('verbos', $result['rows']);
		$data->addToBody('count', $count);
		$data->addToBody('page', $page);
		
		$this->render('verbos/listado_verbos', $data);
	}



	// para obtencion sin paginacion en formato json
	public function ObtenerTodosAction(Request $req, Response $res, Data $data){
		$criteria = [
			'select' => ['id_verbo','descrip_verbo'],
			'where' => [],
			'order' => 'descrip_verbo asc',
			'limit' => 15,
			'offset' => 0,
		];

		if($req->existsGet('descrip_verbo')){
			$term = $req->get('descrip_verbo');
			if($term != ""){
				$criteria['where']['descrip_verbo']= ['like'=>$term];
			}
		}

		$verbos = TblVerbo::findAll($criteria);

		$lista = [];
		foreach ($verbos as $key => $item) {
			$lista[] = ['id_verbo'=>$item->id_verbo, 'descrip_verbo'=>$item->descrip_verbo,'tipo_saber_verbo'=>$item->tipo_saber_verbo];
		}
		$res->json($lista, true);
	}

	public function NuevoVerboAction(Request $req, Response $res, Data $data){
		$verbo = new TblVerbo();

		$prellenado = $req->get('nombre_verbo');

		if($req->isMethodPost()){
			$data->addToBody('bandera_fase1', $req->postIfExists('bandera_fase1', false));
			if($data->isParamsOk()){
				$usuario = Sesion::obtener();
				$nombreNuevoVerbo = $data->fromBody('nombre_verbo');
				$tipoSaber = $data->fromBody('tipo_saber_verbo');
				$disponible = $req->postIfExists('disponible',0);

				$db = new DBHelper();
				$sql_conteo = "select count(*) from ".TblVerbo::$table.' where descrip_verbo like ? ';
				$conteo_existente = $db->readScalar($sql_conteo, [$nombreNuevoVerbo]);
				if($conteo_existente > 0){
					$res->json([
						'estado' => false,
						'mensaje' => 'Ya existe un verbo con el mismo nombre',
					]);
					exit();
				}

				$verbo->descrip_verbo = $nombreNuevoVerbo;
				$verbo->tipo_saber_verbo = $tipoSaber;
				$verbo->disponible = $disponible;
				$verbo->id_usuario = $usuario->id;

				$verbo_creado = [];
				if($verbo->save()){
					$verbo_creado = $verbo;
					$data->setSuccessMessage('El verbo ha sido guardado');
					$verbo = new TblVerbo();
				}else{
					$data->setErrorMessage($verbo->getError());
				}
			}else{
				$data->setErrorMessage("Error al recibir los parametros solicitados");
			}
			$res->json($data->forJSON($verbo_creado));
		}else if($prellenado){
			$verbo->descrip_verbo = ucwords($prellenado);
			$data->addToBody('bandera_fase1', true);
		}

		$data->addToBody('verbo', $verbo);
		$this->render('verbos/formulario_verbo',$data);
	}

	public function EditarVerboAction(Request $req, Response $res, Data $data){
		$verbo = new TblVerbo();

		if($data->isParamsOk()){

			$id_verbo = $data->fromBody('id_verbo');
			$verbo = TblVerbo::findById($id_verbo);

			if($verbo){
				if($req->isMethodPost()){
					$usuario = Sesion::obtener();
					$nombreVerbo = $data->fromBody('nombre_verbo');
					$tipoSaber = $data->fromBody('tipo_saber_verbo');
					$disponible = $req->existsPost('disponible') ? 1 : 0;// $args['body']['disponible'];

					$db = new DBHelper();
					$sql_conteo = "select count(*) from ".TblVerbo::$table.' where id_verbo != ? and descrip_verbo like ? ';
					$conteo_existente = $db->readScalar($sql_conteo, [$id_verbo, $nombreVerbo]);
					if($conteo_existente > 0){
						$res->json([
							'estado' => false,
							'mensaje' => 'Ya existe un verbo con el mismo nombre',
						]);
						exit();
					}

					$verbo->descrip_verbo = $nombreVerbo;
					$verbo->tipo_saber_verbo = $tipoSaber;
					$verbo->disponible = $disponible;
					
					if($usuario->id_usuario == $verbo->id_usuario || $usuario->tipo == 'admin'){
						if($verbo->update()){
							$data->setSuccessMessage("El verbo ha sido actualizado");
						}else{
							$data->setErrorMessage($verbo->getError());
						}
					}else{
						$data->setErrorMessage('No tiene permiso para modificar el verbo. Puedes crear una copia marcando la opción de abajo');
					}
					$res->json($data->forJSON($verbo));
				}
			}else{
				$data->setErrorMessage('Verbo no encontrado');
			}
		}else{
			$data->setErrorMessage("No llegaron los datos solicitados");
		}

		
		$data->addToBody('verbo', $verbo);
		$data->addToBody('existente', true);
		$this->render('verbos/formulario_verbo',$data);
	}


	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id');
		if($id){
			$verbo = TblVerbo::findById($id);
			if($verbo){
				if($verbo->delete()){
					$data->setSuccessMessage('Verbo eliminado correctamente');
				}else{
					$data->setErrorMessage('No se ha podido eliminar el verbo ya que actualmente se encuentra en uso.');
				}
			}else{
				$data->setSuccessMessage('No se ha encontrado el verbo seleccionado');
			}
		}else{
			$data->setErrorMessage('Debe seleccionar un verbo');
		}
		$res->json($data->forJSON());
	}
	

}