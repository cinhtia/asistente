<?php
// estilos
$body = 'font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;';
$bg_primary = 'background-color: #002E5F; color: white;';
$bg_secondary = 'background-color: #99661C; color: white;';
$bg_softgray = 'background-color: #D9D9D9; color: black;';
$bg_primary_bg_strong = 'background-color: #002042;';
$color_primary = $cuady_primary = 'color: #002E5F;';

?>
<div>
	<html>
	<head>
		<meta charset="UTF-8">
		<title>Planeación didáctica</title>
		<style>
			body{ font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;  }
		</style>
	</head>
	<body>
		<div> <?= $this->html_header ?> </div>
		<h1 style="color: #632423; text-align: center;"><strong>PLANEACIÓN DIDÁCTICA</strong></h1>
		
		<div style="<?= $bg_primary ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 5px;"> DATOS GENERALES DE IDENTIFICACIÓN </div>

		<table style="width: 100%; margin-bottom: 15px;">
			<tbody>
				<?php
					$estilo_td_1 = $bg_secondary.'padding: 5px; color: white; margin-right: 2px;';
					$estilo_td_2 = $bg_softgray.'padding: 5px; margin-left: 2px;';
				?>
				<tr style="margin-bottom: 5px;">
					<td style="width: 25%; <?=$estilo_td_1?>">Nombre de la asignatura</td>
					<td style="width: 75%; <?=$estilo_td_2?>" colspan="5"><?= $this->asignatura->nombre_asignatura ?></td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="width: 25%;<?=$estilo_td_1?>">Tipo</td>
					<td style="width: 75%;<?=$estilo_td_2?>" colspan="5"><?= ucwords($this->asignatura->tipo_asignatura) ?></td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="width: 25%;<?=$estilo_td_1?>">Modalidad</td>
					<td style="width: 75%;<?=$estilo_td_2?>" colspan="5">
						<?= $this->asignatura->modalidad == 'presencial' ? 'Presencial' : ( $this->asignatura->modalidad == 'en_linea' ? 'En linea' : 'Mixta' ); ?>
					</td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="width: 25%;<?=$estilo_td_1?>">Ubicación</td>
					<td style="width: 75%;<?=$estilo_td_2?>" colspan="5"><?= Helpers::numeroSemestreATexto($this->asignatura->semestre_ubicacion) ?></td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="<?= $bg_secondary ?> width: 25%; padding: 5px; color: white; margin-right: 2px;">Duración total en horas</td>
					<td style="<?= $bg_softgray ?> width: 12%; padding: 5px; margin-left: 2px; margin-right: 2px;"><?= $this->asignatura->horas_duracion ?></td>
					<td style="<?= $bg_secondary ?> width: 18%; padding: 5px; color: white; margin-left: 2px; margin-right: 2px;">Horas presenciales</td>
					<td style="<?= $bg_softgray ?> width: 12%; padding: 5px; margin-left: 2px; margin-right: 2px;"><?= $this->asignatura->horas_presenciales ?></td>
					<td style="<?= $bg_secondary ?> width: 18%; padding: 5px; color: white; margin-left: 2px; margin-right: 2px;">Horas no presenciales</td>
					<td style="<?= $bg_softgray ?> width: 15%; padding: 5px; margin-left: 2px; margin-right: 2px;"><?= $this->asignatura->horas_nopresenciales ?></td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="<?= $bg_secondary ?> width: 25%; padding: 5px; color: white; margin-right: 2px;">Créditos</td>
					<td style="<?= $bg_softgray ?> width: 75%; padding: 5px; margin-left: 2px;" colspan="5"><?= $this->asignatura->creditos ?></td>
				</tr>
				<tr style="margin-bottom: 5px;">
					<td style="<?= $bg_secondary ?> width: 25%; padding: 5px; color: white; margin-right: 2px;">Requisitos académicos previos</td>
					<td style="<?= $bg_softgray ?> width: 75%; padding: 5px; margin-left: 2px;" colspan="5">Ninguno</td>
				</tr>
			</tbody>
		</table>

		<div style="<?= $bg_primary ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 10px;"> COMPETENCIA DE LA ASIGNATURA </div>
		<div style="<?= $bg_softgray ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 15px;"><?= $this->competencia_asignatura ? $this->competencia_asignatura->competencia_editable : '' ?></div>

		<div style="<?= $bg_primary ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 10px;"> CONTEXTUALIZACIÓN </div>
		<div style="<?= $bg_softgray ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 15px;"><?= $this->asignatura->contextualizacion; ?></div>
		
		<div style="<?= $bg_primary ?> text-align: center; padding: 10px; margin-top: 15px; margin-bottom: 10px;"> COMPETENCIAS DISCIPLINARES QUE SE MOVILIZAN EN LA ASIGNATURA </div>
		<div style="<?= $bg_primary ?> text-align: center; padding: 10px; margin-bottom: 10px; font-size: 90%;"> COMPETENCIAS DISCIPLINARES </div>

		<div style="<?= $bg_softgray ?> padding: 15px; margin-top: 15px; margin-bottom: 15px;">
			<?php foreach ($this->pes_cds as $pe_id => $pe): ?>
				<strong>Para <?= $pe->nombre_pe ?></strong>
				<ul>
					<?php foreach ($this->competencias_disciplinares as $comp_disciplinar): ?>
						<?php if($comp_disciplinar->plan_estudio_id == $pe_id): ?>
							<li><?= $comp_disciplinar->descripcion ?></li>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			<?php endforeach ?>

		</div>

		<div style="<?= $bg_primary ?> text-align: center; color: white; padding: 10px; margin-top: 15px; margin-bottom: 10px;"> UNIDADES Y COMPETENCIAS </div>
		<table style="width: 100%;">
			<thead>
				<tr style="<?= $bg_primary ?> color: white;">
					<th style="width: 20%; padding: 5px; color: white;" rowspan="2">Unidades</th>
					<th style="width: 60%; padding: 5px; color: white;" rowspan="2">Competencias</th>
					<th style="width: 20%; padding: 5px; color: white;" colspan="2">Duración</th>
				</tr>
				<tr style="<?= $bg_primary ?> color: white;">
					<th style="padding: 5px; color: white;">HP</th>
					<th style="padding: 5px; color: white;">HNP</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->unidades as $index => $unidad): ?>
					<tr>
						<td style="<?= $bg_secondary ?> width: 20%; padding: 5px;">
							<?= Helpers::numberToRomanRepresentation($unidad->num_unidad) ?>. <?= $unidad->nombre_unidad ?>
						</td>
						<td style="<?= $bg_softgray ?> padding: 5px; width: 60%;"><?= $unidad->Competencia ? $unidad->Competencia->competencia_editable : '-' ?> </td>
						<td style="<?= $bg_softgray ?> width: 10%; padding: 5px; text-align: center;"><?= $unidad->duracion_unidad_hp ?></td>
						<td style="<?= $bg_softgray ?> width: 10%; padding: 5px; text-align: center;"><?= $unidad->duracion_unidad_hnp ?></td>
					</tr>
				<?php endforeach ?>
				<tr>
					<td style="<?= $bg_secondary ?> width: 20%; text-align: right; padding: 5px;"><strong>Total</strong></td>
					<td style="<?= $bg_softgray ?> padding: 5px; width: 60%;"></td>
					<td style="<?= $bg_softgray ?> width: 10%; padding: 5px; text-align: center;"><strong><?= $this->total_hp ?></strong></td>
					<td style="<?= $bg_softgray ?> width: 10%; padding: 5px; text-align: center;"><strong><?= $this->total_hnp ?></strong></td>
				</tr>
			</tbody>
		</table>
		
		<?php 
		$p1 = 60;
		$pn = count($this->unidades) > 0 ? ( 40 / count($this->unidades) ) : 10 ;
		?>
		<div style="<?= $bg_primary ?> text-align: center; color: white; padding: 10px; margin-top: 15px; margin-bottom: 10px;"> DESARROLLO DE LAS COMPETENCIAS GENÉRICAS DE LA ASIGNATURA </div>
		<table style="width: 100%;">
			<thead>
				<tr style="<?= $bg_primary ?>">
					<th style="color: white; padding: 5px; text-align: center;">
						COMPETENCIA GENÉRICA
					</th>
					<?php foreach ($this->unidades as $inex => $unidad): ?>
						<th style="width: <?= $pn.'%' ?>; color: white; padding: 5px; text-align: center;">Unidad <?= Helpers::numberToRomanRepresentation($unidad->num_unidad) ?></th>
					<?php endforeach ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->cgs as $index => $cg): ?>
					<tr>
						<td style="color: white; <?= $bg_secondary ?> padding: 5px;"><?= $cg->descripcion_cg ?></td>
						<?php foreach ($this->unidades as $index2 => $unidad): ?>
							<td style="vertical-align: middle; <?= $bg_softgray ?> text-align: center; padding: 5px;"> <?= in_array($unidad->num_unidad, $cg->getUnidades()) ? 'X' : ''; ?> </td>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
			</tbody>

		</table>

		<?php foreach ($this->unidades as $index => $unidad): ?>
			<div style="page-break-before: always;">
				<div style="<?= $bg_primary.' '.$bg_strong ?> text-align: center; color: white; padding: 10px; margin-top: 30px; margin-bottom: 10px;"> SECUENCIA DIDÁCTICA UNIDAD <?= Helpers::numberToRomanRepresentation($unidad->num_unidad) ?> </div>
				<table style="width: 100%; margin-bottom: 10px;">
					<tbody>
						<tr>
							<td style="width: 15%; <?= $bg_primary ?> color: white; padding: 5px;">Unidad <?= Helpers::numberToRomanRepresentation($unidad->num_unidad) ?></td>
							<td style="width: 85%; padding: 5px; <?= $bg_softgray ?>"><?= $unidad->nombre_unidad ?></td>
						</tr>
						<tr>
							<td style="width: 15%; <?= $bg_primary ?> color: white; padding: 5px;">Competencia</td>
							<td style="width: 85%; padding: 5px; <?= $bg_softgray ?>"><?= $unidad->Competencia ? $unidad->Competencia->competencia_editable : '-' ?></td>
						</tr>
					</tbody>
				</table>

				<table style="width: 100%; margin-bottom: 10px;">
					<!-- <thead> -->
						<tr style="<?= $bg_secondary ?>">
							<th style="width: 15%; color: white; padding: 5px; text-align: center;" rowspan="3">Secuencia de contenido</th>
							<th style="width: 15%; color: white; padding: 5px; text-align: center;" rowspan="3">Resultados de aprendizaje</th>
							<th style="width: 15%; color: white; padding: 5px; text-align: center;" rowspan="3">Desagregación de contenidos</th>
							<th style="width: 15%; color: white; padding: 5px; text-align: center;" rowspan="3">Estrategias de enseñanza y aprendizaje</th>
							<th style="width: 40%; color: white; padding: 5px; text-align: center;" colspan="3">Actividades de aprendizaje</th>
						</tr>
						<tr style="<?= $bg_secondary ?>">
							<th style="width: 30%; color: white; padding: 5px; text-align: center;" rowspan="2">Descripción</th>
							<th style="width: 10%; color: white; padding: 5px; text-align: center;" colspan="2">Duración</th>
						</tr>
						<tr style="<?= $bg_secondary ?>">
							<th style="width: 5%; color: white; padding: 5px; text-align: center;">HP</th>
							<th style="width: 5%; color: white; padding: 5px; text-align: center;">HNP</th>
						</tr>
					<!-- </thead>
					<tbody> -->
						<?php
						$total_hp = 0;
						$total_hnp = 0;
						?>
						<?php foreach ($unidad->Contenidos as $index => $contenido): ?>
							<?php
								$total_hp += intval($contenido->duracion_hp);
								$total_hnp += intval($contenido->duracion_hnp);
							?>
							<tr>
								<td style="width: 15%; <?= $bg_softgray ?> padding: 5px;"><?= $index+1 ?> <?= $contenido->detalle_secuencia_contenido ?></td>
								<td style="width: 15%; <?= $bg_softgray ?> padding: 5px;">
									<?php foreach ($contenido->Resultados as $indexRes => $resultado): ?>
										<p><?= $resultado->competencia_editable ?></p>
									<?php endforeach ?>
								</td>
								<td style="width: 15%;<?= $bg_softgray ?> padding: 5px;">
									<ol>
									<?php foreach ($contenido->Desagregados as $indexDes => $desagregado): ?>
										<li><?= $desagregado->descripcion ?></li>
									<?php endforeach ?>
									</ol>
								</td>
								<?php if (false): ?>
									<?php if ($index == 0): ?>
										<td ro2wspan="<?= count($unidad->Contenidos) ?>" style="width: 15%; border-bottom: solid thin #99661C; <?= $bg_softgray ?> padding: 5px;">
											<ul>
												<?php foreach ($unidad->EstrategiasEa as $indexEa => $estrategia_ea): ?>
													<li><?= $estrategia_ea['descripcion_ea'] ?></li>
												<?php endforeach ?>
											</ul>
										</td>
									<?php else: ?>
										<td style="border-bottom: solid thin #99661C; border-top: solid thin #99661C; <?= $bg_softgray ?> padding: 5px;"></td>
									<?php endif ?>
								<?php endif ?>

								<!-- el bloque anterior hace que se corte la tabla (haciendo que se unan todas las filas) -->

								<td style="width: 15%; <?= $bg_softgray ?> padding: 5px;">
									<ul>
										<?php foreach ($contenido->EstrategiasEa as $indexEa => $estrategia_ea): ?>
											<li><?= $estrategia_ea['descripcion_ea'] ?></li>
										<?php endforeach ?>
									</ul>
								</td>
								

								<td style="width: 30%; <?= $bg_softgray ?> padding: 5px;">
									<?php foreach ($contenido->Adas as $indexAda => $ada): ?>
										<div>
											<p><strong>Actividad de aprendizaje <?= $indexAda+1 ?>.</strong> <?= $ada['instruccion_ada'] ?></p> <br>
											<p><strong>Recursos y materiales</strong><br><?= $ada['referencias_ada'] ?></p>
										</div>
									<?php endforeach ?>
								</td>
								<td style="width: 5%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><?= $contenido->duracion_hp ?></td>
								<td style="width: 5%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><?= $contenido->duracion_hnp ?></td>
							</tr>
						<?php endforeach ?>
						<tr>
							<td style="width: 15%;<?= $bg_softgray ?> padding: 5px;"></td>
							<td style="width: 15%;<?= $bg_softgray ?> padding: 5px;"></td>
							<td style="width: 15%;<?= $bg_softgray ?> padding: 5px;"></td>
							<td style="width: 15%;<?= $bg_softgray ?> padding: 5px;"></td>
							<td style="width: 30%;<?= $bg_softgray ?> padding: 5px; text-align: right;"><strong>Total</strong></td>
							<td style="width: 5%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><strong><?= $total_hp ?></strong></td>
							<td style="width: 5%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><strong><?= $total_hnp ?></strong></td>
						</tr>
					<!-- </tbody> -->
				</table>
			</div>
		<?php endforeach ?>

		<div style="page-break-before: always;"></div>
		<div style="<?= $bg_primary_bg_strong ?> text-align: center; color: white; padding: 10px; margin-top: 30px; margin-bottom: 10px;">EVALUACIÓN DEL DESEMPEÑO</div>
		
		<div style="<?= $bg_primary ?> text-align: center; color: white; padding: 10px;">EVALUACIÓN DE PROCESO</div>

		<table style="width: 100%;">
			<thead>
				<tr>
					<th style="width: 35%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Estrategias de evaluación</th>
					<th style="width: 40%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Criterios de evaluación</th>
					<th style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Ponderación</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->evaluacion_proceso as $indexEvalProc => $eval_proc): ?>
					<tr>
						<td style="width: 35%; padding: 5px; <?= $bg_softgray ?>">
							
						</td>
						<td style="width: 40%; padding: 5px; <?= $bg_softgray ?>">
							<ul>
							<?php foreach ($eval_proc['criterios'] as $indexCrit => $criterio): ?>
								<li><?= $criterio['criterio']; ?></li>
							<?php endforeach ?>
							</ul>
						</td>
						<td style="width: 25%; padding: 5px; <?= $bg_softgray ?> text-align: center;">
							0%
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>

		<div style="<?= $bg_primary ?> text-align: center; color: white; padding: 10px; margin-top: 30px;">EVALUACIÓN DE PRODUCTO</div>
		<table style="width: 100%; margin-bottom: 30px;">
			<thead>
				<tr>
					<th style="width: 35%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Estrategias de evaluación</th>
					<th style="width: 40%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Criterios de evaluación</th>
					<th style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center;">Ponderación</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->evaluacion_producto as $indexEvalProc => $eval_prod): ?>
					<tr>
						<td style="width: 35%; padding: 5px; <?= $bg_softgray ?>">

						</td>
						<td style="width: 40%; padding: 5px; <?= $bg_softgray ?>">
							<ul>
								<li></li>
							</ul>
						</td>
						<td style="width: 25%; padding: 5px; <?= $bg_softgray ?> text-align: center;">
							0%
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>

		<div style="width: 40%; margin: 20px auto;">
			<div style="<?= $bg_primary ?> text-align: center; color: white; padding: 10px;">EVALUACIÓN DEL DESEMPEÑO</div>
			<table style="width: 100%;">
				<tr>
					<td style="width: 70%; <?= $bg_secondary ?> padding: 5px;"><strong>Evaluación de proceso</strong></td>
					<td style="width: 30%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><?= $this->total_evaluacion_proceso ?>%</td>
				</tr>
				<tr>
					<td style="width: 70%; <?= $bg_secondary ?> padding: 5px;"><strong>Evaluación de producto</strong></td>
					<td style="width: 30%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><?= $this->total_evaluacion_producto ?>%</td>
				</tr>
				<tr>
					<td style="width: 70%; <?= $bg_secondary ?> padding: 5px; text-align: right;"><strong>Total</strong></td>
					<td style="width: 30%; <?= $bg_softgray ?> padding: 5px; text-align: center;"><?= $this->total_evaluacion_proceso+$this->total_evaluacion_producto ?>%</td>
				</tr>
			</table>
		</div>

		<div style="<?= $bg_primary ?> text-align: center; color: white; margin-top: 20px; padding: 10px;">DESCRIPCIÓN DE LOS NIVELES DE DOMINIO</div>
		<table style="width: 100%; margin-bottom: 10px;">
			<thead>
				<tr>
					<th style="width: 10%; <?= $bg_primary; ?> padding: 5px; text-align: center;">Puntaje</th>
					<th style="width: 15%; <?= $bg_primary; ?> padding: 5px; text-align: center;">Categoría</th>
					<th style="width: 75%; <?= $bg_primary; ?> padding: 5px; text-align: center;">Descripción</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 10%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">90 - 100</td>
					<td style="width: 15%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">Sobresaliente (SS)</td>
					<td style="width: 15%; padding: 5px; <?= $bg_softgray ?> "></td>
				</tr>
				<tr>
					<td style="width: 10%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">80 - 89</td>
					<td style="width: 15%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">Satisfactorio (SA)</td>
					<td style="width: 15%; padding: 5px; <?= $bg_softgray ?> "></td>
				</tr>
				<tr>
					<td style="width: 10%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">70 - 79</td>
					<td style="width: 15%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">Suficiente (S)</td>
					<td style="width: 15%; padding: 5px; <?= $bg_softgray ?> "></td>
				</tr>
				<tr>
					<td style="width: 10%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">0 - 69</td>
					<td style="width: 15%; padding: 5px; <?= $bg_secondary ?> text-align: center; ">No acreditado (NA)</td>
					<td style="width: 15%; padding: 5px; <?= $bg_softgray ?> "></td>
				</tr>
			</tbody>
		</table>

		<div style="<?= $bg_primary ?> text-align: center; color: white; margin-top: 20px; padding: 10px;">ACTIVIDADES QUE FOMENTAN LA FORMACIÓN INTEGRAL</div>
		<table style="width: 100%; margin-bottom: 10px;">
			<thead>
				<tr>
					<th style="width: 25%; <?= $bg_primary; ?> padding: 5px; text-align: center;">DIMENSIONES DE LA FI</th>
					<th style="width: 75%; <?= $bg_primary; ?> padding: 5px; text-align: center;">ACTIVIDADES</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center; ">Cognitiva</td>
					<td style="width: 75%; <?= $bg_softgray ?> padding: 5px; "></td>
				</tr>
				<tr>
					<td style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center; ">Social</td>
					<td style="width: 75%; <?= $bg_softgray ?> padding: 5px; "></td>
				</tr>
				<tr>
					<td style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center; ">Emocional</td>
					<td style="width: 75%; <?= $bg_softgray ?> padding: 5px; "></td>
				</tr>
				<tr>
					<td style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center; ">Valoral-actitudinal</td>
					<td style="width: 75%; <?= $bg_softgray ?> padding: 5px; "></td>
				</tr>
				<tr>
					<td style="width: 25%; <?= $bg_secondary ?> padding: 5px; text-align: center; ">Física</td>
					<td style="width: 75%; <?= $bg_softgray ?> padding: 5px; "></td>
				</tr>
			</tbody>
		</table>

		<div style="<?= $bg_primary ?> text-align: center; color: white; margin-top: 20px; padding: 10px;">REFERENCIAS</div>
		<div style="<?= $bg_softgray ?> padding: 5px; margin-bottom: 20px;">
			<ol><li></li></ol>
		</div>

		<table style="width: 100%; margin-bottom: 25px;">
			<tr>
				<th style="width: 50%; color: white; <?= $bg_secondary ?> padding: 10px;">PLANEACIÓN DIDÁCTICA ELABORADA POR:</th>
				<th style="width: 50%; color: white; <?= $bg_secondary ?> padding: 10px;">FECHA DE ELABORACIÓN</th>
			</tr>
			<tr>
				<td style="width: 50%; <?= $bg_softgray ?> padding: 10px;">
					<ul>
						<li></li>
					</ul>
				</td>
				<td style="width: 50%; <?= $bg_softgray ?> padding: 10px;">
					<?= ucfirst(Helpers::fechaFormalLocal(null, '%B de %G')); ?>
				</td>
			</tr>
		</table>

		<p><strong>Fecha de envío</strong>: </p>
		<p><strong>Fecha de aprobación o Vo. Bo.</strong>: </p>
		<p><strong>Periodo en el que se imparte</strong>: </p>
		<p><strong>Quien elaboró</strong>: </p>
	</body>
	</html>
</div>