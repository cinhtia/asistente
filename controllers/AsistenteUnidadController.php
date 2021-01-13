<?php

class AsistenteUnidadController extends BaseController{

	public function IndexFormUnidadAction(Request $req, Response $res, Data $data){
		$idCompetencia = $req->get('id_competencia');
		$this->competencia_padre = TblCompetencia::findById($idCompetencia);
		$this->asignatura_pe = TblAsignaturaPe::findById($this->competencia_padre->id_asignatura_pe);
		$this->asignatura = TblAsignatura::findById($this->asignatura_pe->id_asignatura);
		$this->unidad = $req->get('unidad');

		$criteria = [
			'where' => [
				'num_unidad' => $this->unidad,
				'id_competencia_padre' => $idCompetencia,
			]
		];

		$competenciaUnidad = TblCompetencia::findOne($criteria);
		if(!$competenciaUnidad){
			$this->competencia_unidad = new TblCompetencia();
			$this->competencia_unidad->id_competencia = 0;
			$this->competencia_unidad->etapa_actual = 0;
		}else{
			$this->competencia_unidad = $competenciaUnidad;
		}

		$this->render('asistente-generador/c-unidad/index', $data);
	}

	public function Form1Action(Request $req, Response $res, Data $data){
		$this->unidad = $req->get('unidad');
		$this->competencia_padre = TblCompetencia::findById($req->get('id_competencia_padre'));	

		$idCompetenciaUnidad = $req->get('id_competencia_unidad');
		$this->competencia_unidad = new TblCompetencia();
		if($idCompetenciaUnidad != 0){
			$this->competencia_unidad = TblCompetencia::findById($idCompetenciaUnidad);
		}else{
			$this->competencia_unidad->id_competencia_padre = $this->competencia_padre->id_competencia;
			$this->competencia_unidad->num_unidad = $this->unidad;
			$this->competencia_unidad->id_competencia = 0;
		}

		$this->render('asistente-generador/c-unidad/form1', $data);
	}

	public function GuardarFase1Action(Request $req, Response $res, Data $data){

		$idCompetencia = $req->post('id_competencia_unidad');
		if($idCompetencia == 0){
			$idPadre = $req->post('id_competencia_padre');
			$competenciaPadre = TblCompetencia::findById($idPadre);
			$competenciaUnidad = new TblCompetencia();

			$competencia = new TblCompetencia();
			$competencia->descripcion = $req->post('descripcion');
			$competencia->id_competencia_padre = $idPadre;
			$competencia->num_unidad = $req->post('num_unidad');
			$competencia->id_usuario = $this->user->id;
			$competencia->id_asignatura_pe = $competenciaPadre->id_asignatura_pe;
			$competencia->etapa_actual = 1;
			$competencia->tipo_competencia = 'unidad';
			if($competencia->save()){
				$competenciaUnidad = $competencia;
				$idCompetencia=$competencia->id_competencia;
				$data->setSuccessMessage('La competencia ha sido guardada correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al guardar. '.$competencia->getError());
			}
		}else{
			$competencia = TblCompetencia::findById($idCompetencia);
			if($competencia && $competencia->tipo_competencia == 'unidad'){
				$competencia->descripcion = $req->post('descripcion');
				if($competencia->update()){
					$competenciaUnidad = $competencia;
					$data->setSuccessMessage('Competencia actualizada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al actualizar la competencia. '.$competencia->getError());
				}
			}else{
				$data->setErrorMessage('La competencia de tipo unidad no ha sido encontrada');
			}
		}
		$res->json($data->forJSON($competenciaUnidad));
	}

	public function Form2Action(Request $req, Response $res, Data $data){
		$this->unidad = $req->get('unidad');
		$this->competencia_padre = TblCompetencia::findById($req->get('id_competencia_padre'));
		$this->competencia_unidad = TblCompetencia::findById($req->get('id_competencia_unidad'));
	
		$this->render('asistente-generador/c-unidad/form2', $data);
	}

	public function Form3Action(Request $req, Response $res, Data $data){
		$this->unidad = $req->get('unidad');
		$this->competencia_padre = TblCompetencia::findById($req->get('id_competencia_padre'));
		$this->competencia_unidad = TblCompetencia::findById($req->get('id_competencia_unidad'));
	
		$this->render('asistente-generador/c-unidad/form3', $data);
	}
	
	
}