<?php

class UsuarioController extends BaseController {
	
	
	public function IndexAction(Request $req, Response $res, $args){
		
		$this->render('usuario/index', $args);
	}


	public function ListadoAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page', 1);
		$count = 10;
	
		$limit = $count;
		$offset =  $count * ($page - 1);
		$whereAssoc = null;
		$orderBy = "fecha_creacion desc";
	
		if($req->existsGet('nombre_usuario_buscar')){
			$term = $req->get('nombre_usuario_buscar');
			if($term != ""){
				$whereAssoc = [
					'nombre' => [ 'like'=>true, 'value'=>$term ]
				];
			}
		}

		if($req->existsGet('tipo_usuario_buscar')){
			$term2= $req->get('tipo_usuario_buscar');
			if($term2 != ""){
				if($whereAssoc == null){
					$whereAssoc = [];
				}
				$whereAssoc['tipo_usuario'] = $term2;
			}
		}

		$result = TblUsuario::findAndCountAll([
			'where' => $whereAssoc != null ? $whereAssoc : [],
			'order' => 'nombre asc',
			'limit' => $limit,
			'offset' => $offset
			]);

		$total = $result['count'];
		$lista = $result['rows'];
		
		$data->addToBody('total', $total);
		$data->addToBody('lista',$lista);
		$data->addToBody('count', $count);
		$data->addToBody('page',$page);
		
		$this->render('usuario/listado', $data);
	}
	

	public function RegistroUsuarioAction(Request $req, Response $res, Data $data){
		$usuario = new TblUsuario();
		$user = $this->user;
		$desde_registro = false;
		$this->permisos = TblPermiso::findAll(['select'=>['id_permiso','nombre_permiso']]);

		$uLog = Sesion::obtener();
		if($uLog != null && $uLog->tipo_usuario != "estudiante"){
			$desde_registro = true;
		}
		
		$data->addToBody('desde_registro', $desde_registro);

		if($req->isMethodPost()){
			if($data->isParamsOk()){
				$usuario->nombre = $data->fromBody('nombre');
				$usuario->username = $data->fromBody('username');
				$usuario->tipo_usuario = $data->fromBody('tipo_usuario');
				$usuario->id_permiso = $req->post('id_permiso');
				

				if($usuario->tipo_usuario == 'admin' && $user->tipo_usuario != 'admin'){
					$data->setErrorMessage("No tienes permisos para crear un administrador");
					$this->render('usuario/formulario', $data);
				}

				$totalExistente = TblUsuario::count(['where'=>['username'=>$usuario->username]]);

				if($totalExistente > 0){
					$data->addToBody('usuario', $usuario);
					$data->setErrorMessage("Ya existe un usuario '$usuario->username'");
					$this->render('usuario/formulario', $data);
					return;
				}

				$usuario->contrasena = $data->fromBody('contrasena');
				$confirmar_contrasena = $data->fromBody('confirmar_contrasena');

				if(strlen(trim($usuario->contrasena)) < 8){
					$data->setErrorMessage("La contraseña debe tener mínimo 8 caracteres");
					$this->render('usuario/formulario', $data);
					return;
				}

				if($usuario->contrasena != $confirmar_contrasena){
					$data->setErrorMessage("Las contraseñas no coinciden");
					$this->render('usuario/formulario', $data);
					return;
				}

				$hashContrasena = SecHash::generarHashContrasenaSegura($usuario->contrasena);

				$usuario->contrasena = $hashContrasena;

				if($usuario->save()){

					
					if($uLog != null){
						$data->setSuccessMessage("Usuario registrado exitosamente");
						$usuario = new TblUsuario();
					}else{
						$sesion = new Sesion();
						$sesion->iniciar($usuario);
						$this->redirect('root');
					}
				}else{
					$data->setErrorMessage($usuario->getError());
					$usuario->contrasena = $confirmar_contrasena;
				}
			}
		}

		$data->addToBody('usuario', $usuario);
		$this->render('usuario/formulario', $data);

	}


	public function EditarAction(Request $req, Response $res, Data $data){
		$data->addToBody('desde_registro', true);
		$this->permisos = TblPermiso::findAll(['select'=>['id_permiso','nombre_permiso']]);

		$usuario = new TblUsuario();
		
		$id = $data->fromBody('id_usuario');

		$uLog = Sesion::obtener();
		$usuario = TblUsuario::findById($id);
		$backPass = $usuario->contrasena;
		if($usuario){
			if(!($uLog->id_usuario == $usuario->id_usuario || $uLog->tipo_usuario == "admin")){
				$data->setErrorMessage('Permiso denegado para esta acción');

			}else{
				if($req->isMethodPost()){

					$usuario->nombre = $data->fromBody('nombre');
					$usuario->username = $data->fromBody('username');
					$usuario->id_permiso = $req->post('id_permiso');
					
					if($uLog != null && $uLog->esAdmin){
						$usuario->tipo_usuario = $data->fromBody('tipo_usuario');
					}

					$duplicadoUser = TblUsuario::findOne([
						'where' => [
							'username' => $usuario->username,
							'id_usuario' => ['!=', $usuario->id_usuario]
						]
					]);

					if($duplicadoUser){
						$data->setErrorMessage('El usuario ingresado ya se encuentra en uso');
					}else{
						$pass = $req->existsPost('contrasena') ? $req->post('contrasena') : "" ;
						$confirmPass = $pass != "" ? $req->post('confirmar_contrasena') : "";
						
						if($pass != ""){
							if(strlen($pass)<8){
								$data->setErrorMessage("La contraseña debe tener mínimo 8 caracteres");
							}else{
								if($pass == $confirmPass){
									$usuario->contrasena = SecHash::generarHashContrasenaSegura($pass);	
								}else{
									$data->setErrorMessage("Las contraseñas no coinciden");
								}
							}
						}else{
							$usuario->contrasena = $backPass;
						}

						if(!$data->isError()){
							if($usuario->update()){
								$usuario->contrasena = "";
								$data->setSuccessMessage("Los datos del usuario han sido actualizados correctamente");
							}else{
								$usuario->contrasena = "";
								$data->setErrorMessage('Ha ocurrido un error al intentar actualizar el usuario');
							}
						}
					}

					
				}
			}
		}else{
			$data->setErrorMessage("No se ha encontrado al usuario solicitado");
		}

		$data->addToBody('usuario', $usuario);
		$data->addToBody('existente', true);
		$this->render('usuario/formulario_editar', $data);
	}


	public function AsignaturaAction(Request $req, Response $res, Data $data){
		$this->asignaturas = TblAsignatura::findAll([
			'select' => ['id_asignatura','nombre_asignatura']
		]);
		$this->render('usuario/asignatura/index', $data);
	}


	public function AsignaturaListadoAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);
		$this->count = $req->get('count', 10);

		$criteria = [
			'where' => [
				'id_usuario' => $this->user->id,
			],
			'include' => [
				[ 'localField' => 'id_asignatura','select' => ['nombre_asignatura'], 'as' => 'asignatura'],
			],
			'limit' => $this->count,
			'offset' => $this->count * ($this->page -1 ),
			'order' => 'asignatura.nombre_asignatura asc'
		];

		$this->result = TblUsuarioAsignatura::findAndCountAll($criteria);
		$this->render('usuario/asignatura/listado', $data);
	}	
	public function GuardarAsignaturaAction(Request $req, Response $res, Data $data){
		$id_asignatura = $req->post('id_asignatura');
		if($id_asignatura){
			$asignatura = TblAsignatura::findById($id_asignatura);
			if($asignatura){
				$comId = [ 'id_asignatura' => $id_asignatura, 'id_usuario' => $this->user->id ];
				$ua = TblUsuarioAsignatura::findById($comId);
				if($ua){
					$data->setErrorMessage('La asignatura ya ha sido agregada');
				}else{
					$usuarioAsignatura = new TblUsuarioAsignatura($comId);

					if($usuarioAsignatura->save()){
						$data->setSuccessMessage('La asignatura fue agregada correctamente');
					}else{
						$data->setErrorMessage('Ha ocurrido un error al intentar agregar la asignatura');
					}
				}
			}else{
				$data->setErrorMessage('Asignatura no encontrada');
			}
		}else{
			$data->setErrorMessage('Asignatura no válida');
		}
		$res->json($data->forJSON());
	}


	public function EliminarAsignaturaAction(Request $req, Response $res, Data $data){
		$id_asignatura = $req->post('id_asignatura');
		if($id_asignatura){
			$comId = [ 'id_asignatura' => $id_asignatura, 'id_usuario' => $this->user->id ];
			$ua = TblUsuarioAsignatura::findById($comId);
			if(!$ua){
				$data->setErrorMessage('Esta asignatura no está agregada a tu perfil de profesor');
			}else{
				if($ua->delete()){
					$data->setSuccessMessage('La asignatura fue eliminada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la asignatura');
				}
			}
		}else{
			$data->setErrorMessage('Asignatura no válida');
		}
		$res->json($data->forJSON());
	}
	
	public function ValidarUsernameAction(Request $req, Response $res, Data $data){
		$username = $req->get('username');
		$id = $req->get('id');

		if($username){
			$criteria = [
				'where' => [
					'username' => $username,
				]
			];
			if($id){ $criteria['where']['id_usuario'] = ['!=', $id]; }

			$userExistente = TblUsuario::findOne($criteria);

			if($userExistente){
				$res->json(['estado' => false, 'mensaje' => 'Ya existe un usuario con el mismo username']);
			}else{
				$res->json(['estado' => true]);
			}
		}else{
			$res->json(['estado' => true]);
		}
	}

}