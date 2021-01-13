<?php

/**
 * ReporteController
 */
class ReporteController extends BaseController{
	
	public function PlaneacionDidacticaIndexAction(Request $req, Response $res, Data $data){
		$this->render('reporte/planeacion-didactica', $data);
	}

	public function FormatoPlaneacionDidacticaAction(Request $req, Response $res, Data $data){
		try{
			$preview = $req->get('preview');
			$id_asignatura = $req->get('id_asignatura');
			$this->asignatura = TblAsignatura::findById($id_asignatura);
			if($this->asignatura){
				$html = null;
				$nombre_pes = $this->asignatura->getPE2Formato();

				// obtenemos la competencia de la asignatura
				$this->competencia_asignatura = TblCompetencia::findOne([
					'where' => [
						'id_asignatura' => $this->asignatura->id_asignatura,
						'tipo_competencia' => 'asignatura',
					],
					'select' => ['id_asignatura','competencia_editable'],
				]);

				$this->competencias_disciplinares = TblCompetenciaDisciplinarAsignatura::findAll([
					'where' => [
						'id_asignatura' => $this->asignatura->id_asignatura,
					],
					'include' => [
						['localField' => 'id_competencia_disciplinar', 'select' => ['descripcion', 'plan_estudio_id']],
					],
					'order' => 'TblCompetenciaDisciplinar.descripcion asc',
				]);

				$this->pes_cds = [];
				foreach ($this->competencias_disciplinares as $index => $comp_disciplinar) {
					if( $comp_disciplinar->plan_estudio_id ){
						if( !isset( $this->pes_cds[$comp_disciplinar->plan_estudio_id] ) ){
							$pe = TblPlanEstudio::findById($comp_disciplinar->plan_estudio_id, [ 'select' => [ 'id_pe', 'nombre_pe' ] ]);
							if($pe){ $this->pes_cds[$comp_disciplinar->plan_estudio_id] = $pe; }
						}
					}
				}

				$tmpu = TblUnidad_asignatura::findAll([
					'where' => [
						'id_asignatura' => $this->asignatura->id_asignatura,
					],
					'select' => [ 'id_unidad_asignatura', 'nombre_unidad', 'num_unidad', 'duracion_unidad_hp', 'duracion_unidad_hnp' ],
					'order' => 'num_unidad asc',
				]);

				$this->unidades = [];
				$this->total_hp = 0;
				$this->total_hnp = 0;

				$this->evaluacion_proceso = [];
				$criterios_plantillas = [];
				$this->evaluacion_producto = [
					[],
				];

				$this->total_evaluacion_proceso = 0;
				$this->total_evaluacion_producto = 0;

				for ($i=1; $i <= $this->asignatura->num_unidades; $i++) { 
					$unidad = null;
					foreach ($tmpu as $uu) {
						if($uu->num_unidad == $i){
							$unidad = $uu;
							break;
						}
					}

					if(!$unidad){
						$unidad = new TblUnidad_asignatura([
							'nombre_unidad' => 'Unidad no creada',
							'num_unidad' => $i,
							'duracion_unidad_hp' => 0,
							'duracion_unidad_hnp' => 0,
						]);
					}

					$unidad->Competencia = TblCompetencia::findOne([
						'where' => [
							'id_asignatura' => $this->asignatura->id_asignatura,
							'tipo_competencia' => 'unidad',
							'num_unidad' => $unidad->num_unidad,
						],
						'select' => ['id_asignatura', 'competencia_editable'],
					]);

					$unidad->EstrategiasEa = [];

					$contenidos = [];

					if($unidad->id_unidad_asignatura){
						// obtenemos los contenidos de la unidad
						$contenidos = TblContenidoUnidadAsignatura::findAll([
							'where' => [
								'id_unidad_asignatura' => $unidad->id_unidad_asignatura,
							],
							'order' => 'id_contenido_unidad_asignatura asc',
						]);

						// por cada contenido debemos obtener los resultados de aprendizaje (competencias)
						// y sus desagregados.
						foreach ($contenidos as $indexc => $contenido) {
							// print "--------->>".$contenido->id_contenido_unidad_asignatura."<<---------";
							$contenido->Resultados = TblCompetenciaContenidoUnidad::findAll([
								'where' => [
									'id_contenido_unidad' => $contenido->id_contenido_unidad_asignatura,
								],
								'include' => [
									['localField' => 'id_competencia', 'select' => ['id_competencia','competencia_editable'] ]
								]
							]);

							$contenido->EstrategiasEa = [];

							// print_r($contenido->Resultados);

							$ids_resultados = [];
							foreach ($contenido->Resultados as $index => $resultado) {
								$ids_resultados[] = $resultado->id_competencia;
							}

							$contenido->Desagregados = TblContenidoUnidadDesagregado::findAll([
								'where' => [
									'id_contenido_unidad_asignatura' => $contenido->id_contenido_unidad_asignatura,
								],
								'include' => [
									['localField' => 'id_desagregado_contenido', 'select' => ['descripcion']]
								],
								'order' => 'id_desagregado_contenido asc',
							]);

							if(count($ids_resultados) > 0){


								$string_ids_resultados = implode(",", $ids_resultados);
								$query = "select ea.id_estrategia_ea, ea.descripcion_ea from ".TblEstrategiaEa::$table." ea join ".TblAdaEstrategiaEa::$table." adaea on ea.id_estrategia_ea = adaea.id_estrategia_ea where adaea.id_ada in (select id_ada from ".TblAda::$table." where resultado_competencia_ada in (".$string_ids_resultados.") ) order by ea.descripcion_ea asc";
								$eas = DBHelper::singleton()->read($query, []);
								$contenido->EstrategiasEa = $eas;

								foreach ($eas as $ea) {
									$encontrado = false;
									foreach ($unidad->EstrategiasEa as $uea) {
										if( $uea['id_estrategia_ea'] == $ea['id_estrategia_ea'] ){
											$encontrado = true;
											break;
										}
									}

									if(!$encontrado){
										array_push($unidad->EstrategiasEa, $ea);
									}
								}

								$query_adas = "select ada.id_ada, ada.nombre_ada, ada.instruccion_ada, ada.referencias_ada, ada.id_plantilla_rubrica from ".TblAda::$table." ada where ada.resultado_competencia_ada in (".$string_ids_resultados.") order by ada.id_ada asc";
								$contenido->Adas = DBHelper::singleton()->read($query_adas, []);

								$criterios_plantillas = $this->helperAgregarDatosPlantillas($contenido->Adas, $criterios_plantillas);

							}else{
								$contenido->EstrategiasEa = [];
								$contenido->Adas = [];
							}
						}

					}

					$unidad->Contenidos = $contenidos;

					$this->total_hp += intval($unidad->duracion_unidad_hp);
					$this->total_hnp += intval($unidad->duracion_unidad_hnp);

					array_push($this->unidades, $unidad);
				}

				$this->evaluacion_proceso[] = [
					'criterios' => $criterios_plantillas,
				];

				$this->cgs = TblCompetenciaGenericaAsignatura::findAll([
					'where' => [
						'id_asignatura' => $this->asignatura->id_asignatura,
					],
					'include' => [
						['localField'=> 'id_cg', 'select' => ['descripcion_cg']],
					],
					'order' => 'descripcion_cg asc',
				]);

				$datos_institucion = Helpers::getJSONFromFile('datos_institucion.json');
				$nombre_universidad = '-';
				foreach ($datos_institucion as $dato){
					if($dato['identificador'] == 'nombre_institucion'){
						$nombre_universidad = $dato['valor'];
						break;
					}
				}

				$html_header = '<div style="text-align: center; color: #002E5F;" class="header-content cuady-primary">';
				$html_header .= $nombre_universidad.'<br>';
				if(count($nombre_pes) > 0){ $html_header .= implode("<br>", $nombre_pes).'<br>'; }
				$html_header .= $this->asignatura->nombre_asignatura;
				$html_header .= '</div>';
				$this->html_header = $html_header;


				if(!$preview){
					ob_start();
				}

				$this->render('reporte/html-pd', $data, true, $preview ? true : false); // es html, pero no finalizar el proceso no es preview 

				if(!$preview){
					$html = ob_get_contents();
					ob_end_clean();
				}else{
					exit();
				}

				if($html != null){

					$mpdf = new \Mpdf\Mpdf([
						'format' => 'Letter-L'
					]);

					// $mpdf->use_kwt = false;
					// $mpdf->autoPageBreak = false;
					$mpdf->useSubstitutions = false;
					$mpdf->simpleTables = true;
					$mpdf->setAutoTopMargin = 'stretch'; // Set pdf top margin to stretch to avoid content overlapping
					$mpdf->setAutoBottomMargin = 'stretch'; 

					$html_footer = '<div style="text-align: right; color: #002E5F;" class="footer-content cuady-primary">';
					$html_footer .= '{PAGENO}';
					$html_footer .= '</div>';

					
					// $mpdf->SetHTMLHeader($html_header);
					$mpdf->SetHTMLFooter($html_footer);
					$mpdf->WriteHTML($html);

					$nombre_asignatura_limpio = preg_replace('/\s+/', '_', strtolower( Helpers::textoNoAcentos($this->asignatura->nombre_asignatura) ));
					$rel_name = 'public/formato_'.$nombre_asignatura_limpio.'.pdf';
					$nombre_filename = DIRECTORY.$rel_name;

					$mpdf->Output($nombre_filename, \Mpdf\Output\Destination::FILE);

					$res->json([
						'estado' => true,
						'mensaje' => 'Formato generado',
						'data' => [
							'url' => BASE_URL_WEB.$rel_name
						],
					]);

				}else{
					$res->json([
						'estado' => false,
						'mensaje' => 'Ha ocurrido un error al generar el formato',
					]);
				}
			}else{
				$res->json([
					'estado' => false,
					'mensaje' => 'No se ha encontrado la asignatura seleccionada',
				]);
			}
		}catch(Exception $ex){
			$res->json([
				'estado' => false,
				'mensaje' => 'Ha ocurrido un error al generar el formato PDF',
				'dev' => $ex->getMessage(),
			]);
		}
	}

	public function FormatoPlaneacionDidacticaDesdeHtmlAction(Request $req, Response $res, Data $data){
		try{
			$id_asignatura = $req->post('id_asignatura');
			$this->contenido = $req->post('contenido');

			$this->asignatura = TblAsignatura::findById($id_asignatura);

			if(!$this->asignatura){
				$res->json([
					'estado' => false,
					'mensaje' => 'No se ha encontrado la asignatura seleccionada',
				]);
				return;
			}

			$nombre_pes = $this->asignatura->getPE2Formato();

			ob_start();

			$this->render('reporte/html-pdf-container', $data, true, false); // es html, pero no finalizar el proceso no es preview 

			$html = ob_get_contents();
			ob_end_clean();

			$mpdf = new \Mpdf\Mpdf([
				'format' => 'Letter-L'
			]);

			// $mpdf->use_kwt = false;
			// $mpdf->autoPageBreak = false;
			$mpdf->useSubstitutions = false;
			$mpdf->simpleTables = true;
			$mpdf->setAutoTopMargin = 'stretch'; // Set pdf top margin to stretch to avoid content overlapping
			$mpdf->setAutoBottomMargin = 'stretch'; 

			// $datos_institucion = Helpers::getJSONFromFile('datos_institucion.json');
			// $nombre_universidad = '-';
			// foreach ($datos_institucion as $dato){
			// 	if($dato['identificador'] == 'nombre_institucion'){
			// 		$nombre_universidad = $dato['valor'];
			// 		break;
			// 	}
			// }

			// $html_header = '<div style="text-align: center; color: #002E5F;" class="header-content cuady-primary">';
			// $html_header .= $nombre_universidad.'<br>';
			// if(count($nombre_pes) > 0){ $html_header .= implode("<br>", $nombre_pes).'<br>'; }
			// $html_header .= $this->asignatura->nombre_asignatura;
			// $html_header .= '</div>';

			$html_footer = '<div style="text-align: right; color: #002E5F;" class="footer-content cuady-primary">';
			$html_footer .= '{PAGENO}';
			$html_footer .= '</div>';

			
			// $mpdf->SetHTMLHeader($html_header);
			$mpdf->SetHTMLFooter($html_footer);
			$mpdf->WriteHTML($html);

			$nombre_asignatura_limpio = preg_replace('/\s+/', '_', strtolower( Helpers::textoNoAcentos($this->asignatura->nombre_asignatura) ));
			$rel_name = 'public/formato_'.$nombre_asignatura_limpio.'.pdf';
			$nombre_filename = DIRECTORY.$rel_name;

			$mpdf->Output($nombre_filename, \Mpdf\Output\Destination::FILE);

			$res->json([
				'estado' => true,
				'mensaje' => 'Formato generado',
				'data' => [
					'url' => BASE_URL_WEB.$rel_name
				],
			]);
		}catch(Exception $ex){
			$res->json([
				'estado' => false,
				'mensaje' => 'Ha ocurrido un error en la generación del formato',
				'dev' => $ex->getMessage(),
			]);
		}
	}
	
	private function helperAgregarDatosPlantillas($adas, $agregados){
		$pids = [];
		foreach ($adas as $index => $ada) {
			if($ada['id_plantilla_rubrica']){
				if(!in_array($ada['id_plantilla_rubrica'], $pids)){

					$encontrado = false;
					foreach ($agregados as $index => $agregado) {
						if($agregado['id'] == $ada['id_plantilla_rubrica']){
							$encontrado = true;
							break;
						}
					}

					if(!$encontrado){
						$pids[] = $ada['id_plantilla_rubrica'];
					}
				}
			}
		}

		try{
			if(count($pids) > 0){
				$query = "select * from ".TblPlantillaRubrica::$table." where id_plantilla_rubrica in (".implode(",", $pids).")";
				$resultado = DBHelper::singleton()->read($query, []);
				if(count($resultado) > 0){
					foreach ($resultado as $index => $resultadoItem) {
						$objPlantilla = new TblPlantillaRubrica($resultadoItem);
						$obj_generado = $objPlantilla->completarContenido();
						$contenido = $obj_generado['contenido'];

						foreach ($contenido['matriz'] as $index => $row) {
							if($index > 0){
								$col1 = $row[0];
								if(!isset($col1['es_cg']) || ( isset($col1['es_cg']) && !$col1['es_cg'] == 1 )  ){
									$existente = false;
									foreach ($agregados as $indexAg => $agregado) {
										if($agregado['criterio'] == $col1['value']){
											$existente = true;
											break;
										}
									}
									if(!$existente){
										$agregados[] = [
											'id' => $objPlantilla->id_plantilla_rubrica,
											'criterio' => $col1['value'],
										];
									}
								}
							}
						}

					}
				}
			}
		}catch(Exception $ex2){
			
		}

		return $agregados;
	}
}