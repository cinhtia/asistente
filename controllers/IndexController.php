<?php 

class IndexController extends BaseController {

	public function LoginAction(Request $req, Response $res, Data $data){
		
		if($data->isPost()){
			if($data->isParamsOk()){
				$usuario = $data->fromBody('username');
				$contrasenia = $data->fromBody('contrasenia');

				$usuario = TblUsuario::findOne(['where'=>array('username'=>$usuario)]);
				$data->setErrorMessage('Usuario o contraseña incorrectos');
				if($usuario->id_usuario != 0){
					if(SecHash::verificarHashContrasena($contrasenia, $usuario->contrasena)){
						$permisos = [];
						if($usuario->id_permiso){
							$modT = TblModulo::$table;
							$permT = TblPermisoModulo::$table;
							$sql = "select m.nombre, pm.leer, pm.escribir, pm.eliminar from $modT m inner join $permT pm on m.id_modulo=pm.id_modulo where pm.id_permiso = ?;";
							$db = new DBHelper();
							$tmp = $db->read($sql, [$usuario->id_permiso], false, PDO::FETCH_COLUMN);
							$permisos = [];
							foreach ($tmp as $perm) {
								if($perm['leer']){
									$permisos[] = $perm['nombre'].'_leer';
								}

								if($perm['escribir']){
									$permisos[] = $perm['nombre'].'_escribir';
								}

								if($perm['eliminar']){
									$permisos[] = $perm['nombre'].'_eliminar';
								}
							}
						}

						$sesion = new Sesion();
						$sesion->iniciar($usuario, $permisos);
						$this->redirect('root');
					}
				}
			}else{
				$data->setErrorMessage('Parametros incompletos');
			}
		}
		
		$this->render('login', $data);
	}	

	public function LogoutAction(Request $req, Response $res, Data $data){
		session_destroy();

		$this->redirect('login', $data);
	}

	public function IndexAction(Request $req, Response $res, Data $data){
		$this->render('panel', $data);
	}

	public function InstitucionAction(Request $req, Response $res, Data $data){
		$institucion = Helpers::getJSONFromFile('datos_institucion.json');
		$data->addToBody('institucion', $institucion);
		$this->render('institucion', $data);
	}

	public function AyudaAction(Request $req, Response $res, Data $data){
		$this->render('ayuda', $data);
	}

	public function AutoresAction(Request $req, Response $res, Data $data){
		$this->render('autores', $data);
	}
}