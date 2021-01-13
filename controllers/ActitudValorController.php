<?php 

class ActitudValorController extends BaseController{


	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('actitudes-valores/index', $data);
	}
	
	public function ListadoAction(Request $req, Response $res, Data $data){
		$page = $req->getifExists('page',1);
		$count = $req->getifExists('count', 10);
	
		$limit = $count;
		$offset = $count * ($page - 1);
		$whereAssoc = null;

		if($req->existsGet('descrip_av')){
			$term = $req->get('descrip_av');
			if($term != ""){
				$whereAssoc = [
					'descrip_actitud_valor' => [ 'like',$term ]
				];
			}
		}

		if($req->existsGet('tipo')){
			$term = $req->get('tipo');
			if($term != ""){

				if($whereAssoc == null){
					$whereAssoc = [];
				}

				$whereAssoc['tipo'] = $term;
			}
		}

		$result = TblCompo_actitud_valor::findAndCountAll([
			'where' => $whereAssoc != null ? $whereAssoc : [],
			'order' => 'descrip_actitud_valor asc',
			'limit' => $limit,
			'offset' => $offset
		]);


		$data->addToBody('total', $result['count']);
		$data->addToBody('avs', $result['rows']);
		$data->addToBody('count', $count);
		$data->addToBody('page', $page);
		
		$this->render('actitudes-valores/listado_av', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		$av = new TblCompo_actitud_valor();
		$this->desde_asistente = $req->get('desde_asistente', false);
		if($data->isPost()){
			if($data->isParamsOk()){
				$nombre_av = $data->fromBody('descrip_actitud_valor');
				$tipo = $data->fromBody('tipo');

				$user = Sesion::obtener();

				$av->descrip_actitud_valor = $nombre_av;
				$av->tipo = $tipo;
				$av->id_usuario = $user->id;

				if($nombre_av == "" || $tipo == ""){
					$res->json([
						'estado' => false,
						'mensaje' => 'La petición no es válida',
					]);
				}else{
					$existentes = TblCompo_actitud_valor::count([
						'where'=>[ 'descrip_actitud_valor' => [ 'like', $nombre_av, false] ]
					]);
					if($existentes == 0){
						if($av->save()){
							$res->json([
								'estado' => true,
								'mensaje' => $tipo == 'a' ? 'La actitud fue creada' : ( $tipo == 'v' ? 'El valor fue creado' : 'El registro de tipo actitud/valor fue creado'),
								'data' => $av,
							]);
						}else{
							$res->json([
								'estado' => false,
								'mensaje' => 'Ha ocurrido un error al crear el registro',
							]);
						}
					}else{
						$res->json([
							'estado' => false,
							'mensaje' => 'El nombre del componente actitud-valor ya existe',
						]);
					}
				}
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'Los parámetros estan incompletos',
				]);
			}
		}

		$data->addToBody('av',$av);
	
		$this->render('actitudes-valores/formulario_av', $data);
	}
	


	public function EditarAction(Request $req, Response $res, Data $data){
		$av = new TblCompo_actitud_valor();
		$this->desde_asistente = $req->get('desde_asistente', false);
		if($data->isParamsOk()){
			$id = $data->fromBody('id_compo_valor');

			$av = TblCompo_actitud_valor::findById($id);
			
			if($av){
				if($req->isMethodPost()){
					
					$nombre_av = $data->fromBody('descrip_actitud_valor');
					$tipo = $data->fromBody('tipo');
					$existentes = 0;
					if($nombre_av != $av->descrip_actitud_valor){
						$existentes = TblCompo_actitud_valor::count([
							'where'=>['descrip_actitud_valor'=>['like',$nombre_av, false]],
						]);
					}

					$av->descrip_actitud_valor = $nombre_av;
					$av->tipo = $tipo;

					if($existentes == 0){
						if($av->update()){
							$res->json([
								'estado' => true,
								'mensaje' => 'el elemento actitud-valor ha sido actualizado',
								'data' => $av,
							]);
						}else{
							$res->json([
								'estado' => false,
								'mensaje' => 'Ha ocurrido un error al actualizar el registro',
							]);
						}
					}else{
						$res->json([
							'estado' => false,
							'mensaje' => 'El nombre del componente actitud-valor ya existe en otro registro',
						]);
					}
				}
			}else{
				$data->setErrorMessage("No se ha encontrado el elemento solicitado");
			}
		}else{
			$data->setErrorMessage("Los datos  están incompletos");
		}

		
		$data->addToBody('av', $av);
		$data->addToBody('existente', true);
		$this->render('actitudes-valores/formulario_av', $data);
	}
	
	public function DeleteAction(Request $req, Response $res, Data $data){
		$idAV = $req->postIfExists('id_av',0);

		$av = TblCompo_actitud_valor::findById($idAV);

		if($av){
			if($av->delete()){
				$data->setSuccessMessage('Registro eliminado correctamente');
			}else{
				$data->setErrorMessage('No se ha podido eliminar la actitud/valor ya que actualmente se encuentra en uso');
			}
		}else{
			$data->setErrorMessage('Registro no encontrado');
		}
	
		$res->json($data->forJSON());
	}
	
	public function CrearAction(Request $req, Response $res, Data $data){
		$descrip_actitud_valor = $req->post('descrip_actitud_valor');

	}

}