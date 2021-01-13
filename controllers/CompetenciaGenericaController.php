<?php

class CompetenciaGenericaController extends BaseController{

	
	public function IndexAction(Request $req, Response $res, Data $data){
	
		$this->render('academico/cg/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){
		$page = $req->getIfExists('page',1);
		$count = 10;
		
		$limit = $count;
		$offset = $count * ($page - 1);
		
		$result = TblCG::findAndCountAll([
			'limit' => $limit,
			'offset' => $offset,
			'order' => 'descripcion_cg asc',
		]);
		
		$data->addToBody('total',$result['count']);
		$data->addToBody('cgs',$result['rows']);
		$data->addToBody('count',$count);
		$data->addToBody('page',$page);
	
		$this->render('academico/cg/listado', $data);
	}
	
	public function NuevoAction(Request $req, Response $res, Data $data){
		$cg = new TblCG();
		
		if($data->isPost()){
			if($data->isParamsOk()){
				$descripcion_cg = $data->fromBody('descripcion_cg');
				$existentes = TblCG::count(['where'=>['descripcion_cg'=>$descripcion_cg]]);
				if($existentes == 0){
					$cg->descripcion_cg = $descripcion_cg;
					$cg->id_usuario = $this->user->id;

					$db = new DBHelper();
					$db->beginTransaction();

					if($cg->save($db)){
						$db->commit();
						$data->setSuccessMessage('La competencia genérica fue creada correctamente');
						$cg = new TblCG();
					}else{
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error: '.$cg->getError());
					}
				}else{
					$data->setErrorMessage('Ya existe una competencia genérica con la misma descripción');
				}
			}else{
				$data->setErrorMessage('Parámetros incompletos');
			}
		}

		$data->addToBody('cg', $cg);
		$this->render('academico/cg/formulario_cg', $data);
	}
	
	public function EditarAction(Request $req, Response $res, Data $data){
		
		$cg = new TblCG();
		
		if($data->isParamsOk()){
			$id = $data->fromBody('id_cg');
			$cg = TblCG::findById($id);
			if($cg){
				if($data->isPost()){
					$descripcion_cg = $data->fromBody('descripcion_cg');
					$existentes = 0;
					if($descripcion_cg != $cg->descripcion_cg){
						$existentes = TblCG::count(['where'=>['descripcion_cg'=>$descripcion_cg]]);
					}

					if($existentes == 0){
						$cg->descripcion_cg = $descripcion_cg;
						if($cg->update()){
							$data->setSuccessMessage('Competencia genérica agregada correctamente');
						}else{
							$data->setErrorMessage($cg->getError());
						}
					}else{
						$data->setErrorMessage('Ya existe una competencia genérica con la descripción indicada');
					}
				}
			}else{
				$data->setErrorMessage('Competencia genérica no encontrada');
			}
		}else{
			$data->setErrorMessage('Parámetros incompletos 1');
		}

		$data->addToBody('cg', $cg);
		$data->addToBody('nuevo', false);
		$this->render('academico/cg/formulario_cg', $data);
	}
	
	public function EliminarAction(Request $req, Response $res, Data $data){
		
		$respuesta = [
			'estado'=>false,
			'mensaje'=>''
		];

		if($data->isParamsOk()){
			$idCG = $data->fromBody('id_cg');
			$cg = TblCG::findById($idCG);
			if($cg){

				$uso = TblMallaCurricular::findOne([
					'where' => [
						'id_cg' => $idCG,
					]
				]);

				if($uso){
					$data->setErrorMessage('No se puede eliminar la CG porque se encuentra en uso');
				}else{
					if($cg->delete()){
						$data->setSuccessMessage('Competencia genérica eliminada');
					}else{
						$data->setErrorMessage($cg->getError());
					}
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la competencia geneérica');
			}
		}

		$res->json($data->forJSON());
	}
			
	
}