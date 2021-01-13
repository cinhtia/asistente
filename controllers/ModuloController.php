<?php

/**
 * 
 */
class ModuloController extends BaseController
{

	public static $modulos = [
			['usuario',			'Usuarios'],
			['modulo',			'Módulos'],
			['permiso',			'Permiso'],
			['institucion',		'Instituciones'],
			['pe',				'Planes de estudio'],
			['asignatura',		'Asignaturas'],
			['cg',				'Competencias genéricas'],
			['unidad',			'Unidades'],
			['contenido_unidad','Contenidos de unidad'],
			['cd',				'Competencias disciplinares'],
			['ea',				'Estrategias de enseñanza-aprendizaje'],
			['verbo', 			'Verbos'],
			['contenido', 		'Contenidos'],
			['contexto', 		'Contextos'],
			['criterio', 		'Criterios'],
			['conocimiento', 	'Conocimientos'],
			['habilidad', 		'Habilidades'],
			['av', 				'Actitudes y valores'],
			['mcompetencia', 	'Mis competencias'],
			['asistente', 		'Asistente'],
			['asignatura_pe', 	'Asignaturas de planes de estudio'],
	];

	public function InstalarAction(Request $req, Response $res, Data $data){
		$instalados = 0;
		foreach (self::$modulos as $index => $modulo) {
			$existe = TblModulo::count(['where'=>['nombre'=> $modulo[0]]]);
			if($existe == 0){
				$mod = new TblModulo(['nombre' => $modulo[0], 'descripcion'=>$modulo[1]]);
				if($mod->save()){
					$instalados++;
				}else{
					break;
				}
			}
		}

		$data->setSuccessMessage('Se han instalado '.$instalados.' nuevos módulos');

		$res->json($data->forJSON());
		
	}
	
	
	public function IndexAction(Request $req, Response $res, Data $data){
		
	
		$this->render('modulo/index', $data);
	}
	
	public function ListAction(Request $req, Response $res, Data $data){
		
		$this->page = $req->getIfExists('page', 1);
		$this->count = $this->limit = $req->getIfExists('count', 10);

		$this->result = TblModulo::findAndCountAll([
				'limit' => ($this->count),
				'offset' => ( $this->count * ($this->page -1) ),
				'order' => 'fecha_creacion desc',
			]);
		
	
		$this->render('modulo/listado', $data);
	}


	public function FormAction(Request $req, Response $res, Data $data){
		
		$id = $req->getIfExists('id_modulo',0);

		$modulo = new TblModulo();
		if($id != 0){
			$modulo = TblModulo::findById($id);
		}

		if(!$modulo){
			$data->setErrorMessage('No se ha encontrado el registro solicitado');
		}

		$this->nuevo = $id == 0;
		$this->modulo = $modulo;
		$this->render('modulo/formulario', $data);
	}

	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_modulo',0);
		$nombre_modulo = $req->postIfExists('nombre_modulo',null);

		if($id != 0){
			$modulo = TblModulo::findById($id);
			if($modulo){
				$modulo->nombre_modulo = $nombre_modulo;
				if($modulo->update()){
					$data->setSuccessMessage('El módulo ha sido actualizado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar. '.$modulo->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el módulo solicitado');
			}
		}else{
			$modulo = new TblModulo();
			$modulo->nombre_modulo = $nombre_modulo;
			if($modulo->save()){
				$data->setSuccessMessage('El módulo ha sido guardado');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar el módulo. '.$modulo->getError());
			}
		}

		$res->json($data->forJSON());
	}
	

	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->postIfExists('id_modulo',0);
		if($id){
			$modulo = TblModulo::findById($id);
			if($modulo){
				if($modulo->delete()){
					$data->setSuccessMessage('El módulo ha sido eliminado');
				}else{
					$data->setErrorMessage('Ha ocurrido un error. '.$modulo->getError());	
				}
			}else{
				$data->setErrorMessage('No se ha encontrado el módulo');
			}
		}else{
			$data->setErrorMessage('No se ha encontrado el módulo');
		}
		$res->json($data->forJSON());
	}

}