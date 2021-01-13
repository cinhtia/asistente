<?php

class CompetenciaAsignaturaUnidadController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		
	
		$this->render('competencia-asignatura-unidad/index', $data);
	}

	public function ListAction(Request $req, Response $res, Data $data){

		$this->page = $req->getIfExists('page', 1);
		$this->count = $req->getIfExists('count', 10);			
		$criteria  = [
			'limit' => $this->count,
			'offset' => ($this->page-1)*$this->page,
			'where' => [
				'tipo_competencia' => ['<>','resultado']
			]
		];

		$this->result = TblCompetencia::findAndCountAll($criteria);
		$asigs = [];
		foreach ($this->result['rows'] as $index => $item) {
			if(isset($asigs[$item->id_asignatura])){
				$asignatura = $asigs[$item->id_asignatura];
			}else{
				$asignatura = TblAsignatura::findById($item->id_asignatura, ['select'=>['nombre_asignatura']]);
				$asigs[$item->id_asignatura] = $asignatura;
			}
			
			$this->result['rows'][$index]->nombre_asignatura = $asignatura->nombre_asignatura;
			$user = TblUsuario::findById($item->id_usuario, ['select'=>['nombre']]);
			$this->result['rows'][$index]->nombre_autor = $user->nombre;
		}
		$this->render('competencia-asignatura-unidad/listado', $data);
	}

	public function FormAction(Request $req, Response $res, Data $data){
		$this->competencia = new TblCompetencia();
		$this->redirigirMisCompetencias = false; // solo se utiliza si se quiere actualizar la competencia desde el asistente

		$id = $req->getIfExists('id_competencia', 0);
		if($id != 0){
			$this->competencia = TblCompetencia::findById($id);
			if($req->existsGet('cambiar_tipo')){
				$this->redirigirMisCompetencias = true;
				$this->competencia->competencia_editable = $req->getIfExists('competencia_editable', $this->competencia->competencia_editable);
				$this->competencia->tipo_competencia = $req->getIfExists('tipo', $this->competencia->tipo_competencia);
			}

			if($this->competencia->tipo_competencia == 'asignatura'){
				$this->asignatura = TblAsignatura::findById($this->competencia->id_asignatura, ['select'=>['num_unidades']]);
			}

		}

		// si se abre el modal para cambiar una comp de tipo resultado
		// a una de tipo asignatura/unidad, entonces debemos verificar 
		// si llega una competencia_editable

		$this->asignaturas = TblAsignatura::findAll(['select'=>['id_asignatura','nombre_asignatura','num_unidades','tipo_asignatura']]);
		$this->nuevo = $id == 0;
		$this->render('competencia-asignatura-unidad/formulario', $data);
	}

	public function GuardarAction(Request $req, Response $res, Data $data){
		$idCompetencia = $req->post('id_competencia');
		$tipoCompetencia = $req->post('tipo_competencia');
		$idAsignatura = $req->post('id_asignatura');
		$numUnidad = $req->post('num_unidad');
		$competenciaEditable = $req->post('competencia_editable2');

		$idCompetenciaPadre = null;

		$esAsignatura = false;
		// primero se realiza un proceso de valdiacion
		if($tipoCompetencia == 'asignatura'){
			$esAsignatura = true;
			// verificamos que no haya otra competencia para esta asignatura
			$criteriaAsignatura = [
				'where' => [
					'tipo_competencia' => 'asignatura',
					'id_asignatura' => $idAsignatura,
				]
			];

			if($idCompetencia != 0){
				$criteriaAsignatura['where']['id_competencia'] = ['<>' ,$idCompetencia];
			}
			$existente = TblCompetencia::count($criteriaAsignatura);
			if($existente>0){
				$data->setErrorMessage('Ya existe una competencia con la asignatura seleccionada');
				$res->json($data->forJSON());
			}
		}else{
			// verificamos que no haya otra competencia para esta asignatura
			$criteriaUnidad = [
				'where' => [
					'tipo_competencia' => 'unidad',
					'id_asignatura' => $idAsignatura,
					'num_unidad' => $numUnidad,
				]
			];

			if($idCompetencia != 0){
				$criteriaUnidad['where']['id_competencia'] = ['<>' ,$idCompetencia];
			}
			$existente = TblCompetencia::count($criteriaUnidad);
			if($existente>0){
				$data->setErrorMessage('Ya existe una competencia con la unidad de la asignatura seleccionada');
				$res->json($data->forJSON());
			}

			// obtenemos el id de la competencia padre de acuerdo con la asignatura
			$criteriaCAsignatura = [
				'where' => [
					'id_asignatura' => $idAsignatura
				],
				'select' => ['id_competencia']
			];

			$competenciaPadre = TblCompetencia::findOne($criteriaCAsignatura);
			if($competenciaPadre){
				$idCompetenciaPadre = $competenciaPadre->id_competencia;
			}else{
				$data->setErrorMessage('Primero debes crear la competencia de la asignatura seleccionada');
				$res->json($data->forJSON());
			}

		}
		$asignatura = TblAsignatura::findById($idAsignatura);
		if($idCompetencia != 0){
			$comp = TblCompetencia::findById($idCompetencia);
			if($comp){
				$db = new DBHelper();
				$db->beginTransaction();

				$comp->tipo_competencia     = $tipoCompetencia;
				$comp->id_asignatura        = $idAsignatura;
				$comp->num_unidad           = $tipoCompetencia == 'unidad' ? $numUnidad : null;
				$comp->competencia_editable = $competenciaEditable;
				$comp->etapa_actual         = 4;
				if($comp->update(null,$db)){
					$db->commit();
					$data->setSuccessMessage('La competencia ha sido actualizada correctamente');
				}else{
					$db->rollBack();
					$data->setErrorMessage('Ha ocurrido un error al actualizar la competencia. '.$comp->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la competencia solicitada');
			}
		}else{
			$comp = new TblCompetencia();
			$comp->tipo_competencia = $tipoCompetencia;
			$comp->id_asignatura = $idAsignatura;
			$comp->num_unidad = $tipoCompetencia == 'unidad' ? $numUnidad : null;
			$comp->competencia_editable = $competenciaEditable;
			$comp->id_usuario = $this->user->id;
			$comp->etapa_actual = 4;
			$comp->id_competencia_padre = $idCompetenciaPadre;

			$db = new DBHelper();
			$db->beginTransaction();

			if($comp->save($db)){
				$data->setSuccessMessage('Competencia guardada correctamente');
				$db->commit();
			}else{
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al guardar la competencia. '.$comp->getError());
			}
		}
		$res->json($data->forJSON());
	}
}