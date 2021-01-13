<?php

class TblPlantillaRubrica extends Model{
	public static $table = "TblPlantillaRubrica";
	public static $pk    = "id_plantilla_rubrica";
	public static $createdAt = "fecha_creacion";
	public static $updatedAt = "fecha_actualizacion";
	public static $className = __CLASS__;

	var $id_plantilla_rubrica;
	var $id_rubrica;          
	var $nombre;              
	var $contenido;           
	var $id_usuario;
	var $es_copia;
	var $id_competencia;
	var $referencias_incluidas;
	var $fecha_creacion;      
	var $fecha_actualizacion;

	public function generarRecomendacion($texto, $num_puntaje, $total_puntajes){

		switch ($num_puntaje) {
			case 1:
				return 'Dominio alto';
			case 2:
				return $total_puntajes == 2 ? 'Dominio nulo' : ( $total_puntajes == 3 ? 'Dominio bajo' : 'Dominio medio' );
			case 3:
				return $total_puntajes == 3 ? 'Dominio nulo' : ( $total_puntajes == 4 ? 'Dominio medio' : 'Dominio bajo' );
			case 4:
				return $total_puntajes == 4 ? 'Dominio nulo' : ( $total_puntajes == 5 ? 'Dominio medio' : 'Dominio bajo' );
		}

		return "";
	}

	public function generarRow($texto, $num_puntajes, $es_cg = false){
		$arr = [
			[ 'value' => $texto, 'es_cg' => $es_cg ],
		];

		for ($i=1; $i <= $num_puntajes; $i++) { 
			array_push($arr, [ 'value' => $this->generarRecomendacion($texto, $i, $num_puntajes) ] );
		}

		return $arr;
	}

	public function completarContenido(){
		$contenido_json = $this->contenido ? Helpers::jsonString2Array($this->contenido) : [
			'num_valoraciones' => 0,
			'num_puntajes' => 3,
			'matriz' => []
		];

		$conteo_modificados = 0;
		$referencias_incluidas = [
			'conocimiento' => [],
			'habilidad' => [],
			'actitud' => [],
			'cg' => [],
		];

		if($this->id_competencia){
			$referencias_incluidas = $this->referencias_incluidas ? Helpers::jsonString2Array($this->referencias_incluidas) : [
				'conocimiento' => [],
				'habilidad' => [],
				'actitud' => [],
				'cg' => [],
			];

			if(!isset($referencias_incluidas['conocimiento'])){
				$referencias_incluidas['conocimiento'] = [];
			}

			if(!isset($referencias_incluidas['habilidad'])){
				$referencias_incluidas['habilidad'] = [];
			}

			if(!isset($referencias_incluidas['actitud'])){
				$referencias_incluidas['actitud'] = [];
			}

			if(!isset($referencias_incluidas['cg'])){
				$referencias_incluidas['cg'] = [];
			}

			// aqui debemos obtener los conocimientos, las habilidades, actitudes y valores y cgs de la competencia ligada
			$conocimientos = TblConocimientoCompetencia::findAll([
				'where' => [
					'id_competencia' => $this->id_competencia,
				],
				'include' => [
					['localField' => 'id_compo_conocimiento', 'select' => ['descrip_conocimiento']],
				],
				'order' => 'descrip_conocimiento asc',
			]);

			$habilidades = TblHabilidadCompetencia::findAll([
				'where' => [
					'id_competencia' => $this->id_competencia,
				],
				'include' => [
					['localField' => 'id_compo_habilidad', 'select' => ['descrip_habilidad']],
				],
				'order' => 'descrip_habilidad asc',
			]);

			$actitudes = TblActitudValorCompetencia::findAll([
				'where' => [
					'id_competencia' => $this->id_competencia,
				],
				'include' => [
					['localField' => 'id_compo_actitud_valor', 'select' => ['descrip_actitud_valor']],
				],
				'order' => 'descrip_actitud_valor asc',
			]);

			$cgs = TblMallaCurricular::findAll([
				'where' => [
					'id_competencia' => $this->id_competencia,
				],
				'include' => [
					['localField' => 'id_cg', 'select' => ['descripcion_cg']],
				],
				'order' => 'descripcion_cg asc',
			]);

			//----------------------- agregando conocimientos
			foreach ($conocimientos as $index => $conocimiento_obj) {
				$encontrado = false;
				foreach ($referencias_incluidas['conocimiento'] as $index => $conocimiento_id) {
					if($conocimiento_id == $conocimiento_obj->id_compo_conocimiento){
						$encontrado = true;
						break;
					}
				}

				if(!$encontrado){
					$conteo_modificados++;
					array_push($referencias_incluidas['conocimiento'], $conocimiento_obj->id_compo_conocimiento);
					$contenido_json['num_valoraciones'] = $contenido_json['num_valoraciones'] + 1;
					array_push( $contenido_json['matriz'], $this->generarRow($conocimiento_obj->descrip_conocimiento, $contenido_json['num_puntajes']));
				}
			}
			
			//----------------------- agregando habilidades
			foreach ($habilidades as $index => $habilidad_obj) {
				$encontrado = false;
				foreach ($referencias_incluidas['habilidad'] as $index => $habilidad_id) {
					if($habilidad_id == $habilidad_obj->id_compo_habilidad){
						$encontrado = true;
						break;
					}
				}

				if(!$encontrado){
					$conteo_modificados++;
					array_push($referencias_incluidas['habilidad'], $habilidad_obj->id_compo_habilidad);
					$contenido_json['num_valoraciones'] = $contenido_json['num_valoraciones'] + 1;
					array_push($contenido_json['matriz'], $this->generarRow($habilidad_obj->descrip_habilidad, $contenido_json['num_puntajes']));
				}
			}

			//----------------------- agregando actitudes
			foreach ($actitudes as $index => $actitud_obj) {
				$encontrado = false;
				foreach ($referencias_incluidas['actitud'] as $index => $actitud_id) {
					if($actitud_id == $actitud_obj->id_compo_actitud_valor){
						$encontrado = true;
						break;
					}
				}

				if(!$encontrado){
					$conteo_modificados++;
					array_push($referencias_incluidas['actitud'], $actitud_obj->id_compo_actitud_valor);
					$contenido_json['num_valoraciones'] = $contenido_json['num_valoraciones'] + 1;
					array_push($contenido_json['matriz'], $this->generarRow($actitud_obj->descrip_actitud_valor, $contenido_json['num_puntajes']));
				}
			}

			//----------------------- agregando cgs
			foreach ($cgs as $index => $cg_obj) {
				$encontrado = false;
				foreach ($referencias_incluidas['cg'] as $index => $cg_id) {
					if($cg_id == $cg_obj->id_cg){
						$encontrado = true;
						break;
					}
				}

				if(!$encontrado){
					$conteo_modificados++;
					array_push($referencias_incluidas['cg'], $cg_obj->id_cg);
					$contenido_json['num_valoraciones'] = $contenido_json['num_valoraciones'] + 1;
					array_push($contenido_json['matriz'], $this->generarRow($cg_obj->descripcion_cg, $contenido_json['num_puntajes'], true));
				}
			}
		}

		return [
			'contenido' => $contenido_json,
			'referencias_incluidas' => $referencias_incluidas,
			'conteo_modificados' => $conteo_modificados,
		];
	}

	public function drawCell($cell, $i, $j, $pc_width){
		$ret = '<td style="padding: 10px; border: solid thin #000;">';
		$html_cell = '';
		$cell_bg_color = '#ffffff';
		if($i == 0 || $j == 0){
			$cell_bg_color = '#d6d6d6';
		}

		if($i == 0 && $j == 0){
			$html_cell = '<span>Criterios / Valoración</span>';
		}else{
			$html_cell = $i == 0 && $j > 0 ? '<span><strong>'.$cell['value'].'</strong></span>' : '<span>'.$cell['value'].'</span>';
		}

		$ret = '<td style="width: '.$pc_width.'%; text-align: center; padding: 10px; border: solid thin #000; background-color: '.$cell_bg_color.' ">';
		$ret .= $html_cell;
		$ret .= '</td>';
		return $ret;

		// if($j>1 && $i==0){ $ret .=''; }
		// if($i>1 && $j==0){ $ret .=''; }
		// if($i>0 && $j>0){
		// 	$i--;
		// 	$j--;
		// 	if($i!=0 || $j!=0){
		// 		$ret .= '<span>'.$cell['value'].'</span>';
		// 	}else{
		// 		$ret .= '<span class="text-center">Criterios / Valoración</span>';
		// 	}
		// }

		// return $ret.'</td>';
	}

	public function generarTablaHtml(){
		if($this->contenido){
			$tmp = $this->completarContenido();
			$contenido_json = $tmp['contenido'];

			$num_valoraciones = $contenido_json['num_valoraciones'];
			$num_puntajes = $contenido_json['num_puntajes'];
			$matriz = $contenido_json['matriz'];

			$fcolwidth = 35;
			$fcolwidth2 = 65 / $num_puntajes;

			$html = '<table style="width: 95%; margin: 10px; border: solid thin #000;">';

			for ($i = 0; $i < $num_valoraciones+1; $i++) {
				$row = '<tr style="border: solid thin #000;">';
				for ($j = 0; $j < $num_puntajes+1; $j++) {
					$row .= $this->drawCell($matriz[$i][$j], $i, $j, ($i == 0 ? $fcolwidth : $fcolwidth2));
					// if($i>0 && $j>0){
					// }else{
					// 	$row .= $this->drawCell(null, $i, $j);
					// }
				}
				$row .= '</tr>';
				$html .= $row;
			}

			$html .= '</table>';
			return $html;
		}else{
			return '<p></p>';
		}
	}

}