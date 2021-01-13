<?php

use TblContenidoUnidadAsignatura as TblCUA;
use TblContenidoUnidadDesagregado as TblCUD;

class ContenidoUnidadAsignaturaController extends BaseController {


	public function IndexAction(Request $req, Response $res, Data $data){
		
		$id = $req->getIfExists('id',0);
		$this->old_page = $req->getIfExists('page', 1);
		$this->ua = TblUnidad_asignatura::findById($id);
		$asignatura = TblAsignatura::findById($this->ua->id_asignatura, ['select'=>['nombre_asignatura']]); 
		$this->ua->nombre_asignatura = $asignatura ? $asignatura->nombre_asignatura : 'Asignatura desconocida';
		
		if(!$this->ua){
			$data->setErrorMessage('No se ha encontrado la unidad solicitada');
		}

		$this->term = $req->getIfExists('term', null);
	
		$this->render('academico/contenido-unidad/index', $data);
	}


	public function ListAction(Request $req, Response $res, Data $data){
		
		$this->page = $req->getIfExists('page', 1);
		$this->count = $req->getIfExists('count', 10);
		$this->id_unidad_asignatura = $req->getIfExists('id_unidad_asignatura', 0);

		$limit = $this->count;
		$offset = $this->count * ($this->page-1);

		$criteria = [
			'where' => [ 'id_unidad_asignatura' => $this->id_unidad_asignatura],
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'id_contenido_unidad_asignatura asc',
		];

		$this->ua = TblUnidad_asignatura::findById($this->id_unidad_asignatura);
		$this->result = TblCUA::findAndCountAll($criteria);
		foreach ($this->result['rows'] as $index => $value) {
			$criteria = ['where'=>[
					'id_contenido_unidad_asignatura'=>$value->id_contenido_unidad_asignatura
				]
			];
			$this->result['rows'][$index]->total_desagregados = TblCUD::count($criteria);
		}

		$this->render('academico/contenido-unidad/listado', $data);
	}
	


	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->getIfExists('id_contenido_unidad_asignatura', 0);
		$this->id_unidad_asignatura = $req->getIfExists('id_unidad_asignatura', '');
		$this->unidad = TblUnidad_asignatura::findById($this->id_unidad_asignatura, [
			'include' => [
				['localField' => 'id_asignatura', 'select' => ['nombre_asignatura']],
			]
		]);
		$this->desde_asistente = $req->getIfExists('desde_asistente', 0);
		$this->cua = new TblCUA();
		$this->desagregados = TblDesagregadoContenido::findAll(['select'=>['id_desagregado_contenido','descripcion']]);
		$this->desagregadosGuardados = [];
		if($id != 0){
			$this->cua = TblCUA::findById($id);
			$sql = "select cd.id_desagregado_contenido,cd.descripcion from ".TblDesagregadoContenido::$table." cd inner join ".TblCUD::$table." cud on cd.id_desagregado_contenido = cud.id_desagregado_contenido where cud.id_contenido_unidad_asignatura = ?;";
			$db = new DBHelper();
			$this->desagregadosGuardados = $db->read($sql, [$id]);
		}else{
			$this->cua->id_unidad_asignatura = $this->id_unidad_asignatura;
			$this->cua->id_contenido_unidad_asignatura = 0;
		}

		$this->render('academico/contenido-unidad/formulario', $data);
	}
	

	public function GuardarAction(Request $req, Response $res, Data $data){
		
		$cua = new TblCUA();

		$id_contenido_unidad_asignatura = $req->post('id_contenido_unidad_asignatura');
		$id_unidad_asignatura = $req->post('id_unidad_asignatura');
		$duracion_hp = $req->post('duracion_hp');
		$duracion_hnp = $req->post('duracion_hnp');
		$detalle_secuencia_contenido = $req->post('detalle_secuencia_contenido');
		$desagregados = $req->postArray('desagregados');

		if($id_contenido_unidad_asignatura != 0 ){
			$cua = TblCUA::findById($id_contenido_unidad_asignatura);
			if($cua){
				$cua->duracion_hp = $duracion_hp;
				$cua->duracion_hnp = $duracion_hnp;
				$cua->detalle_secuencia_contenido = $detalle_secuencia_contenido;

				$db = new DBHelper();
				$db->beginTransaction();
				if($cua->update(null,$db)){
					$str = implode(',', array_fill(0, count($desagregados), '?'));
					$sql = "delete from ".TblCUD::$table." where id_contenido_unidad_asignatura = ? and id_desagregado_contenido not in ($str);";
					$args = array_merge([$id_contenido_unidad_asignatura], $desagregados);

					$deleted = $db->delete($sql, $args);
					foreach ($desagregados as $index => $desagregadoId) {
						$existe = TblCUD::count(['where'=>[
							'id_contenido_unidad_asignatura' => $cua->id_contenido_unidad_asignatura,
							'id_desagregado_contenido' => $desagregadoId
						]], $db);


						if($existe == 0){
							$sqlInsert = "insert into ".TblCUD::$table." (id_contenido_unidad_asignatura,id_desagregado_contenido) values (?,?);";
							if(!$db->insert($sqlInsert, [$cua->id_contenido_unidad_asignatura, $desagregadoId])){
								$data->setErrorMessage('Ha ocurrido un error al guardar los desagregados. '.$db->error_db);
								$db->rollBack();
								$res->json($data->forJSON());
								return;
							}
						}
					}
					$db->commit();
					$data->setSuccessMessage('Registro actualizado correctamente. ');
				}else{
					$db->rollBack();
					$data->setErrorMessage('Error al actualizar el registro. '.$cua->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el registro');
			}
		}else{
			$cua->id_contenido_unidad_asignatura = $id_contenido_unidad_asignatura;
			$cua->id_unidad_asignatura = $id_unidad_asignatura;
			$cua->duracion_hp = $duracion_hp;
			$cua->duracion_hnp = $duracion_hnp;
			$cua->detalle_secuencia_contenido = $detalle_secuencia_contenido;
			$cua->id_usuario = $this->user->id;
			$db = new DBHelper();
			$db->beginTransaction();
			if($cua->save($db)){

				foreach ($desagregados as $index => $desagregadoId) {
					$sqlInsert = "insert into ".TblCUD::$table." (id_contenido_unidad_asignatura,id_desagregado_contenido) values (?,?);";
					if(!$db->insert($sqlInsert, [$cua->id_contenido_unidad_asignatura, $desagregadoId])){
						$data->setErrorMessage('Ha ocurrido un error al guardar los desagregados. '.$db->error_db);
						$db->rollBack();
						$res->json($data->forJSON());
						return;
					}
				}

				$db->commit();
				$data->setSuccessMessage('Registro guardado correctamente');
			}else{
				$db->rollBack();
				$data->setErrorMessage('Error al guardar el registro. '.$cua->getError());
			}
		}

		$res->json($data->forJSON());
	}


	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_unidad_asignatura');

		$cua = TblCUA::findById($id);

		if($cua){

			$conteo_uso = TblCompetenciaContenidoUnidad::count([
				'where' => [
					'id_contenido_unidad' => $id,
				]
			]);

			if($conteo_uso > 0){
				$res->json([
					'estado' => false,
					'mensaje' => 'No se puede eliminar esta secuencia porque se encuentra en uso en '.$conteo_uso.' resultados de aprendizaje',
				]);
				return;
			}

			$db = new DBHelper();
			$db->beginTransaction();
			$sql = "delete from ".TblCUD::$table." where id_contenido_unidad_asignatura=?;";
			if($db->delete($sql, [$id])){
				if($cua->delete($db)){
					$db->commit();
					$data->setSuccessMessage('Registro eliminado correctamente');
				}else{
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar el registro. '.$cua->getError());
				}
			}else{
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al intentar eliminar el registro. '.$db->error_db);
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el registro solicitada');
		}
	
		$res->json($data->forJSON());
	}
	
}