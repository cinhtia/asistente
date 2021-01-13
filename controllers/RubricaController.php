<?php 

/**
 * 
 */
class RubricaController extends BaseController{

	public function IndexAction(Request $req, Response $res, Data $data){
		
		$this->render('rubrica/index', $data);
	}


	public function ListAction(Request $req, Response $res, Data $data){
		$this->page = $req->get('page', 1);
		$this->count = $req->get('count', 10);

		$this->result = TblRubrica::findAndCountAll([
			'limit' => $this->count,
			'offset' => $this->count * ($this->page-1),
			'order' => 'descripcion_rubrica asc',
		]);

		if($this->result['count']>0){
			$tmp = implode(',', array_fill(0, count($this->result['rows']), '?'));
			$ids = [];
			foreach ($this->result['rows'] as $item) {
				$ids[] = $item->id_rubrica;
			}
			$sql = "select  id_rubrica, count(id_rubrica) as total from ".TblPlantillaRubrica::$table.($this->result['count']>0 ? " where id_rubrica in ($tmp) ": "")." group by id_rubrica;";
			$resultado = DBHelper::singleton()->read($sql, $ids);

			foreach ($this->result['rows'] as $index=>$row) {
				foreach ($resultado as $item) {
					if($item['id_rubrica'] == $row->id_rubrica){
						$this->result['rows'][$index]->total_plantillas = $item['total'];
						break;
					}
				}
				if(!isset($this->result['rows'][$index]->total_plantillas)){
					$this->result['rows'][$index]->total_plantillas = 0;
				}
			}
		}
	
		$this->render('rubrica/listado', $data);
	}


	public function FormAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_rubrica', 0);
		$this->rubrica = new TblRubrica(['id_rubrica'=>0]);
		if($id != 0){
			$this->rubrica = TblRubrica::findById($id);
		}

		$this->nuevo = $this->rubrica->id_rubrica == 0;
		$this->render('rubrica/formulario', $data);
	}

	public function FormAsistenteAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_rubrica', 0);
		$this->rubrica = new TblRubrica(['id_rubrica'=>0]);
		if($id != 0){
			$this->rubrica = TblRubrica::findById($id);
		}

		$this->nuevo = $this->rubrica->id_rubrica == 0;
		$this->render('rubrica/formulario-asistente', $data);	
	}
	
	public function GuardarAction(Request $req, Response $res, Data $data){
		$id_rubrica = $req->post('id_rubrica', 0);
		$descripcion_rubrica = $req->post('descripcion_rubrica');
		$explicacion_rubrica = $req->post('explicacion_rubrica');

		$desde_asistente = $req->post('desde_asistente', 0);

		$rubrica = new TblRubrica();
		if($id_rubrica != 0){
			$rubrica = TblRubrica::findById($id_rubrica);
			if($rubrica){
				$rubrica->descripcion_rubrica = $descripcion_rubrica;
				$rubrica->explicacion_rubrica = $explicacion_rubrica;
				if($rubrica->update()){
					$data->setSuccessMessage('La rúbrica ha sido actualizada correctamente');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar la rúbrica. '.$rubrica->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la rubrica solicitada');
			}
		}else{

			$db = new DBHelper();
			$db->beginTransaction();




			$rubrica->descripcion_rubrica = $descripcion_rubrica;
			$rubrica->explicacion_rubrica = $explicacion_rubrica;
			$rubrica->id_usuario = $this->user->id;
			if($rubrica->save($db)){
				if($desde_asistente == 1){
					$plantilla_base = $this->datosPlantillaBase($rubrica->id_rubrica, $rubrica->descripcion_rubrica);
					if($plantilla_base->save($db)){
						$db->commit();
						$data->setSuccessMessage('La rúbrica ha sido creada correctamente');
					}else{
						$db->rollBack();
						$data->setErrorMessage('Ha ocurrido un error al intentar guardar la rúbrica..');
					}
				}else{
					$db->commit();
					$data->setSuccessMessage('La rúbrica ha sido creada correctamente');
				}
			}else{
				$db->rollBack();
				$data->setErrorMessage('Ha ocurrido un error al intentar guardar la rúbrica. '.$rubrica->getError());
			}
		}
		$res->json($data->forJSON($rubrica));
	}


	public function EliminarAction(Request $req, Response $res, Data $data){
		$id = $req->post('id_rubrica', 0);
		$rubrica = TblRubrica::findById($id);
		if($rubrica){

			$adas_en_uso = TblAda::findAll([
				'where' => [
					'id_rubrica'=>$id,
				],
				'include' => [
					['localField' => 'id_asignatura', 'select' => ['nombre_asignatura']],
				],
				'select'=>['nombre_ada']
			]);

			if(count($adas_en_uso) > 0){
				$msj = 'La rúbrica no se puede eliminar porque es utilizada en '.count($adas_en_uso).' ADAs';
				$msj .= '<ul>';
				foreach ($adas_en_uso as $ada) {
					$msj .= '<li>'.$ada->nombre_ada.' ('.$ada->nombre_asignatura.')</li>';
				}
				$msj .= '</ul>';
				$res->json([
					'estado' => false,
					'mensaje' => $msj,
				]);
				return;
			}

			$conteo_plantillas = TblPlantillaRubrica::count([
				'where' => [
					'id_rubrica' => $id
				]
			]);

			if($conteo_plantillas > 0){
				$res->json([
					'estado' => false,
					'mensaje' => 'La rúbrica no se puede eliminar porque contiene plantillas. Elimine primero las plantillas',
				]);
				return;
			}

			if($rubrica->delete()){
				$data->setSuccessMessage('La rúbrica ha sido eliminar correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la rúbrica. '.$rubrica->getError());
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la rúbrica a eliminar. ');
		}
		$res->json($data->forJSON());
	}


	public function IndexPlantillaAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_rubrica', 0);
		$this->plantillas = [];
		if($id != 0){
			$this->rubrica = TblRubrica::findById($id);
			$this->plantillas = TblPlantillaRubrica::findAll([
				'where' => [
					'id_rubrica' => $id,
				],
				'select' => ['id_rubrica','id_plantilla_rubrica','nombre','fecha_creacion'],
				'order' => 'fecha_creacion desc'
			]);
		}
		$this->render('rubrica/index_plantillas', $data);
	}


	public function FormPlantillaAction(Request $req, Response $res, Data $data){
		$id_plantilla = $req->get('id_plantilla_rubrica', 0);
		$this->plantilla = new TblPlantillaRubrica();

		$this->extras_competencia = [];

		$this->es_copia = $req->get('es_copia', 0);

		$this->id_competencia = null;

		if($this->es_copia){
			// debemos obtener todos los registros de la competencia que llega
			$this->id_competencia = $req->get('id_competencia');
		}

		$this->registro_nuevo = true;
		if($id_plantilla != 0){
			$this->registro_nuevo = false;
			$this->plantilla = TblPlantillaRubrica::findById($id_plantilla);
			if($this->plantilla){
				if($this->es_copia){
					$this->id_competencia = $req->get('id_competencia');
					if($this->id_competencia){
						$this->plantilla->id_competencia = $this->id_competencia;
						$tmp = $this->plantilla->completarContenido();

						$this->plantilla->contenido = json_encode($tmp['contenido']);
						$this->plantilla->referencias_incluidas = json_encode($tmp['referencias_incluidas']);
						$this->plantilla->conteo_modificados = $tmp['conteo_modificados'];

					}
				}

				// En el caso de que se quiera editar una plantilla que ya es clon y que el usuario que lo intenta sea el autor
				if($this->plantilla->es_copia == 1 && $this->plantilla->id_usuario == $this->user->id){
					$this->plantilla->es_copia = 0;
				}else{
					$this->plantilla->es_copia = $this->es_copia;
				}
			}else{
				$this->plantilla = new TblPlantillaRubrica;
			}
		}else{
			$id_rubrica = $req->get('id_rubrica');
			$this->plantilla->id_rubrica = $id_rubrica;
			$this->plantilla->id_plantilla_rubrica = 0;
		}
		$this->render('rubrica/formulario_plantilla', $data);
	}
	
	
	public function GuardarPlantillaAction(Request $req, Response $res, Data $data){
		
		$id_plantilla_rubrica = $req->post('id_plantilla_rubrica', 0);
		$id_rubrica = $req->post('id_rubrica');
		$contenido = $req->post('contenido');
		$es_copia = $req->post('es_copia', 0);
		$nombre = $req->post('nombre');
		$id_competencia = $req->post('id_competencia', null);
		$referencias_incluidas = $req->post('referencias_incluidas', null);

		$sufx = $es_copia == 1 ? (' edicion '.date('YmdHis')) : '' ;
		if($id_competencia){
			$competencia = TblCompetencia::findById($id_competencia);
			if(!$competencia){
				$res->json([
					'estado' => false,
					'mensaje' => 'No se ha encontrado la competencia indicada',
				]);
			}

			$sufx = " - Competencia ".$competencia->competencia_editable; 
		}

		$plantilla = new TblPlantillaRubrica();
		if($id_plantilla_rubrica != 0){
			$plantilla            = TblPlantillaRubrica::findById($id_plantilla_rubrica);
			if($plantilla){
				$datos = [
					'nombre' => $plantilla->es_copia ? $nombre : ($nombre.$sufx),
					'contenido' => $contenido,
					'id_competencia' => $id_competencia,
					'referencias_incluidas' => $referencias_incluidas,
				];
				// $plantilla->es_copia  = $es_copia; // 
				if($plantilla->update($datos)){
					$data->setSuccessMessage('La plantilla ha sido actualizada. ');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar actualizar la plantilla. '.$plantilla->getError());
				}
			}else{
				$data->setErrorMessage('No se ha encontrado la plantilla');
			}
		}else{
			$plantilla->id_rubrica = $id_rubrica;
			$plantilla->contenido = $contenido;
			$plantilla->nombre = $nombre.$sufx;
			$plantilla->es_copia  = $es_copia;
			$plantilla->id_usuario = $this->user->id;
			$plantilla->id_competencia = $id_competencia;
			$plantilla->referencias_incluidas = $referencias_incluidas;

			if($plantilla->save()){
				$data->setSuccessMessage('La plantilla ha sido creada correctamente');
			}else{
				$data->setErrorMessage('Ha ocurrido un error al intentar guardar la plantilla. '.$plantilla->getError());
			}
		}

		$res->json($data->forJSON($plantilla));
	}


	public function EliminarPlantillaAction(Request $req, Response $res, Data $data){
		$id_plantilla = $req->post('id_plantilla_rubrica', 0);
		$plantilla = TblPlantillaRubrica::findById($id_plantilla);
		if($plantilla){

			// buscamo las adas que utilicen esta plantilla 
			$adas = TblAda::findAll([
				'where' => [
					'id_plantilla_rubrica' => $plantilla->id_plantilla_rubrica,
				],
				'include' => [
					['localField' =>  'id_asignatura', 'select' => ['nombre_asignatura']],
				],
				'select' => ['id_ada','nombre_ada']
			]);

			if(count($adas) > 0){
				$msj = "Esta plantilla se encuentra en uso por ".count($adas)." ADA(s):";
				$msj .= "<ul>";
				foreach ($adas as $ada) {
					$msj .= "<li>".$ada->nombre_ada." (".$ada->nombre_asignatura.")</li>";
				}
				$msj .= "</ul>";
				$data->setErrorMessage($msj);
			}else{
				if($plantilla->delete()){
					$data->setSuccessMessage('La plantilla ha sido eliminada');
				}else{
					$data->setErrorMessage('Ha ocurrido un error al intentar eliminar la plantilla. '.$plantilla->getError());
				}
			}
		}else{
			$data->setErrorMessage('No se ha encontrado la plantilla a eliminar');
		}

		$res->json($data->forJSON());
	}


	public function ListadoCompletoAction(Request $req, Response $res, Data $data){
		$id = $req->get('id_rubrica');
		$desde_asistente = $req->get('desde_asistente', 0);
		$id_competencia = $req->get('id_competencia');
		$args = [$id];
		$sql = "select t1.id_plantilla_rubrica, t1.nombre from ".TblPlantillaRubrica::$table." t1 where t1.id_rubrica=? ";
		if($desde_asistente){
			$sql .= " and (t1.es_copia = false or t1.es_copia is null or (t1.es_copia = true and t1.id_competencia = ? ) ) ";
			$args[] = $id_competencia;
		}

		$sql .= " order by t1.nombre asc";

		// print $sql;

		$result = RubricaController::ListadoPlantillasAda($id, $desde_asistente, $id_competencia);// DBHelper::singleton()->read($sql, $args);
		
		$data->setSuccessMessage('Plantillas obtenidas');
		$res->json($data->forJSON($result));
	}

	public static function ListadoPlantillasAda($id_rubrica, $desde_asistente, $id_competencia, $as_object = false ){
		$id = $id_rubrica;
		$args = [$id];
		$sql = "select t1.id_plantilla_rubrica, t1.nombre from ".TblPlantillaRubrica::$table." t1 where t1.id_rubrica=? ";
		if($desde_asistente){
			$sql .= " and (t1.es_copia = false or t1.es_copia is null or (t1.es_copia = true and t1.id_competencia = ? ) ) ";
			$args[] = $id_competencia;
		}

		$sql .= " order by t1.nombre asc";
		// print $sql;

		$resultado = DBHelper::singleton()->read($sql, $args);

		if($as_object && count($resultado)>0){
			return TblPlantillaRubrica::parseList2ArrayObj($resultado);
		}

		return $resultado;
	}

	public function datosPlantillaBase($id_rubrica, $nombre_plantilla){

		$matriz_base = [
			'num_valoraciones' => 0,
			'num_puntajes' => 3,
			'matriz' => [
				[ [ 'value' =>  ''], [ 'value' =>  ''], [ 'value' => ''], [ 'value' => ''] ],
			]
		];

		return new TblPlantillaRubrica([
			'id_rubrica' => $id_rubrica,
			'nombre' => 'Plantilla base '.$nombre_plantilla,
			'contenido' => json_encode($matriz_base),
			'id_usuario' => $this->user->id_usuario,
			'es_copia' => false,
		]);
	}	
}