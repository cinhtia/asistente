<?php

class PermisoController extends BaseController{


	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('permiso/index', $data);
	}


	public function ListAction(Request $req, Response $res, Data $data){
		
		$this->page = $req->getIfExists('page', 1);
		$this->count = $this->limit = $req->getIfExists('count', 10);

		$this->result = TblPermiso::findAndCountAll([
				'limit' => ($this->count),
				'offset' => ( $this->count * ($this->page -1) ),
				'order' => 'fecha_creacion desc',
			]);
		
	
		$this->render('permiso/listado', $data);
	}


	public function FormAction(Request $req, Response $res, Data $data){
		
		$id = $req->getIfExists('id_permiso',0);

		$permiso = new TblPermiso();
		$this->modulosGuardados = [];
		if($id != 0){
			$permiso = TblPermiso::findById($id);
			$sql = "select m.descripcion, pm.id_modulo, pm.leer, pm.escribir, pm.eliminar from ".TblPermisoModulo::$table." pm inner join ".TblModulo::$table." m on pm.id_modulo = m.id_modulo where pm.id_permiso = ?;" ;
			$db = new DBHelper();
			$this->modulosGuardados = $db->read($sql, [$id]);
		}

		if(!$permiso){
			$data->setErrorMessage('No se ha encontrado el registro solicitado');
		}

		$this->modulos = TblModulo::findAll(['select' => ['id_modulo','descripcion']]);

		$this->nuevo = $id == 0;
		$this->permiso = $permiso;
		$this->render('permiso/formulario', $data);
	}

	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_permiso',0);
		$nombre_permiso = $req->postIfExists('nombre_permiso',null);
		$modulos = $req->postArray('modulos', []);
		if(count($modulos)>0){
			if($id != 0){
				$permiso = TblPermiso::findById($id);
				if($permiso){
					$permiso->nombre_permiso = $nombre_permiso;
					$db = new DBHelper();
					$db->beginTransaction();
					if($permiso->update(null,$db)){

						$sql = "delete from ".TblPermisoModulo::$table." where id_permiso = ?;";
						$exec = $db->delete($sql, [$id]);
						if($exec){
							
							$error = "";
							foreach ($modulos as $index => $modulo) {
								$tmpPM = new TblPermisoModulo();
								$tmpPM->id_modulo = $modulo['id_modulo'];
								$tmpPM->id_permiso = $id;
								$tmpPM->leer = $modulo['leer'];
								$tmpPM->escribir = $modulo['escribir'];
								$tmpPM->eliminar = $modulo['eliminar'];
								if(!$tmpPM->save($db)){
									$error = $tmpPM->getError();
									break;
								}
							}

							if($error != ""){
								$db->rollBack();
								$data->setErrorMessage('Ha ocurrido un error al intentar guardar el permiso. '.$error);
							}else{
								$db->commit();
								$data->setSuccessMessage('El permiso ha sido guardado');
							}

						}else{
							$db->rollBack();
							$data->setErrorMessage('Ha ocurrido al intentar actualizar el permiso.');
						}
					}else{
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$permiso->getError());
					}
				}else{
					$data->setErrorMessage('No se ha encontrado el permiso solicitado');
				}
			}else{
				$permiso = new TblPermiso();
				$permiso->nombre_permiso = $nombre_permiso;

				$db = new DBHelper();
				$db->beginTransaction();

				if($permiso->save($db)){
					$id = $permiso->id_permiso;
					$error = "";
					foreach ($modulos as $index => $modulo) {
						$tmpPM = new TblPermisoModulo();
						$tmpPM->id_modulo = $modulo['id_modulo'];
						$tmpPM->id_permiso = $id;
						$tmpPM->leer = $modulo['leer'];
						$tmpPM->escribir = $modulo['escribir'];
						$tmpPM->eliminar = $modulo['eliminar'];
						if(!$tmpPM->save($db)){
							$error = $tmpPM->getError();
							break;
						}
					}

					if($error != ""){
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error al intentar guardar el permiso. '.$error);
					}else{
						$db->commit();
						$data->setSuccessMessage('El permiso ha sido guardado');
					}

				}else{
					$data->setErrorMessage('Ha ocurrido un error al guardar el permiso. '.$permiso->getError());
				}
			}
		}else{
			$data->setErrorMessage('Debes seleccionar al menos un módulo');
		}

		$res->json($data->forJSON());
	}
	

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_permiso',0);
		if($id){
			$permiso = TblPermiso::findById($id);
			if($permiso){
				if($permiso->delete()){
					$data->setSuccessMessage('Permiso eliminado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error. '.$permiso->getError());	
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el permiso');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el permiso');
		}
		$res->json($data->forJSON());
	}
	

}