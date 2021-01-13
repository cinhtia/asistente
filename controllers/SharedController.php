<?php
/**
 */
class SharedController extends BaseController{
	
	public function AsignaturaUnidadListaAction(Request $req, Response $res, Data $data){
		$asignatura_id = $req->get('asignatura_id', null);
		if($asignatura_id){

			$asignatura = TblAsignatura::findById($asignatura_id);
			$competencia_unidad = TblCompetencia::findOne([
				'where' => [
					'tipo_competencia' => 'unidad',
					'id_asignatura' => $asignatura_id,
				]
			]);

			$str_competencia_unidad = $competencia_unidad ? $competencia_unidad->competencia_editable : '';

			$unidades = TblUnidad_asignatura::findAll([
				'where' => [
					'id_asignatura' => $asignatura_id,
				]
			]);

			$num_unidades = intval($asignatura->num_unidades);
			$unidades_finales = [];
			for ($i=0; $i < $num_unidades; $i++){ 
				$unidades_finales[$i] = [
					'label' => 'Unidad '.($i+1).' (pendiente)',
					'id' => ($i+1)
				];
				foreach ($unidades as $unid) {
					if(intval($unid->num_unidad) == ($i+1)){
						$unidades_finales[$i]['label'] = "Unidad ".($i+1)." - ".$unid->nombre_unidad;
						$unidades_finales[$i]['id_unidad_asignatura'] = $unid->id_unidad_asignatura;
						break;
					}
				}
			}

			$data->setSuccessMessage('Unidades obtenidas'.$num_unidades);
			$res->json($data->forJSON($unidades_finales));
		}else{
			$data->setErrorMessage('La petición no es válida');
			$res->json($data->forJSON());
		}
	}

	public function CompetenciaUnidadAction(Request $req, Response $res, Data $data){
		$competencia = TblCompetencia::findOne([
			'where' => [
				'id_asignatura' => $req->get('id_asignatura', null),
				'tipo_competencia' => 'unidad',
				'num_unidad' => $req->get('num_unidad', null),
			]
		]);

		if($competencia){
			$data->setSuccessMessage('Competencia obtenida');
			$res->json($data->forJSON($competencia->competencia_editable));
		}else{
			$data->setErrorMessage('No se ha encontrado la competencia de la unidad');
			$res->json($data->forJSON());
		}
	}

	public function ExisteCompetenciaResultadoAction(Request $req, Response $res, Data $data){
		$conteo = TblCompetencia::count([
			'where' => [
				'id_asignatura' => $req->get('id_asignatura', null),
				'tipo_competencia' => 'resultado',
				'num_unidad' => $req->get('num_unidad', null),
			]
		]);

		$data->setSuccessMessage('Validado');
		$res->json($data->forJSON($conteo));
	}

	public function CompetenciaAsignaturaAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_asignatura');
		$competencia_asignatura = TblCompetencia::findOne([
			'where' => [
				'id_asignatura' => $id,
				'tipo_competencia' => 'asignatura',
			]
		]);

		if($competencia_asignatura){
			$res->json([
				'estado' => true,
				'data' => $competencia_asignatura
			]);
		}else{
			$res->json(['estado'=> false, 'mensaje' => 'No se ha encontrado la competencia de la asignatura']);
		}
	}

	public function ContenidosUnidadAction(Request $req, Response $res, Data $data){
		$id_unidad_asignatura = $req->get('id_unidad_asignatura');
		$id_asignatura = $req->get('id_asignatura');
		$num_unidad = $req->get('num_unidad');

		$unidad = TblUnidad_asignatura::findOne([
			'where' => [
				'id_unidad_asignatura' => $id_unidad_asignatura,
				'id_asignatura' => $id_asignatura,
				'num_unidad' => $num_unidad,
			]
		]);

		if($unidad){
			$contenidos = TblContenidoUnidadAsignatura::findAll([
				'where' => [
					'id_unidad_asignatura' => $unidad->id_unidad_asignatura,
				]
			]);
			$res->json([
				'estado' => true,
				'data' => $contenidos,
			]);
		}else{
			$res->json([
				'estado' => false,
				'mensaje' => 'No se ha encontrado la unidad solicitada',
			]);
		}
	}

	public function AsignaturasAction(Request $req, Response $res, Data $data){
		$asignaturas = TblAsignatura::findAll([
			'select' => ['id_asignatura','nombre_asignatura'],
			'order' => 'nombre_asignatura asc'
		]);
		$res->json([
			'estado' => true,
			'mensaje' => 'Asignaturas obtenidas',
			'data' => $asignaturas,
		]);
	}

	public function ConocimientosAction(Request $req, Response $res, Data $data){
		$term = $req->get('term');
		$notids = $req->getArray('notids');
		$where = "";
		$args = [];
		if($term){
			$where = " where descrip_conocimiento like ? ";
			$args[] = "%$term%";
		}

		if($notids){
			$arr_notids = $notids;// explode("-", $notids);
			if(count($arr_notids) > 0){
				$args = array_merge($args, $arr_notids);
				$qArgs = Helpers::seralizarArray2ParametrosMysql($arr_notids);
				$where .= $term ? " and " : " where ";
				$where .= " id_compo_conocimiento not in ($qArgs) ";
			}
		}

		$query = "select id_compo_conocimiento, descrip_conocimiento from ".TblCompo_conocimiento::$table." ".$where." order by descrip_conocimiento asc limit 10";
		$db = new DBHelper();
		$resultado = $db->read($query, $args);

		$res->json([
			'estado' => true,
			'mensaje' => 'Conocimientos obtenidos',
			'data' => $resultado,
			'notids' => $notids,
		]);
	}

	public function HabilidadesAction(Request $req, Response $res, Data $data){
		$term = $req->get('term');
		$notids = $req->getArray('notids');
		$where = "";
		$args = [];
		if($term){
			$where = " where descrip_habilidad like ? ";
			$args[] = "%$term%";
		}

		if($notids){
			$arr_notids = $notids;//explode("-", $notids);
			if(count($arr_notids) > 0){
				$args = array_merge($args, $arr_notids);
				$qArgs = Helpers::seralizarArray2ParametrosMysql($arr_notids);
				$where .= $term ? " and " : " where ";
				$where .= " id_compo_habilidad not in ($qArgs) ";
			}
		}

		$query = "select id_compo_habilidad, descrip_habilidad from ".TblCompo_habilidad::$table." ".$where." order by descrip_habilidad asc limit 10";
		$db = new DBHelper();
		$resultado = $db->read($query, $args);

		$res->json([
			'estado' => true,
			'mensaje' => 'Habilidades obtenidas',
			'data' => $resultado,
		]);
	}

	public function ActitudesValoresAction(Request $req, Response $res, Data $data){
		$term = $req->get('term');
		$notids = $req->getArray('notids');
		$where = "";
		$args = [];
		if($term){
			$where = " where descrip_actitud_valor like ? ";
			$args[] = "%$term%";
		}

		if($notids){
			$arr_notids = $notids; // explode("-", $notids);
			if(count($arr_notids) > 0){
				$args = array_merge($args, $arr_notids);
				$qArgs = Helpers::seralizarArray2ParametrosMysql($arr_notids);
				$where .= $term ? " and " : " where ";
				$where .= " id_compo_valor not in ($qArgs) ";
			}
		}

		$query = "select id_compo_valor, descrip_actitud_valor from ".TblCompo_actitud_valor::$table." ".$where." order by descrip_actitud_valor asc limit 10";
		$db = new DBHelper();
		$resultado = $db->read($query, $args);

		$res->json([
			'estado' => true,
			'mensaje' => 'Actitudes y valores obtenidos',
			'data' => $resultado,
		]);
	}
}