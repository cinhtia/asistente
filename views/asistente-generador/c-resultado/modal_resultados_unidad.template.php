<?php 
$asignatura = $data->fromBody('asignatura', new TblAsignatura);
$unidad = $data->fromBody('unidad', new TblUnidad_asignatura);
$competencias_resultado = $data->fromBody('competencias_resultado', [new TblCompetencia]);
?>
<div class="container">
	<table class="table table-bordered table-hover">
		<colgroup>
			<col width="20%">
			<col width="80%">
		</colgroup>
		<tbody>
			<tr>
				<th>Asignatura</th>
				<td><?= $asignatura->nombre_asignatura ?></td>
			</tr>
			<tr>
				<th>Unidad</th>
				<td><?= $unidad->num_unidad ?></td>
			</tr>
			<tr>
				<th>Nombre Unidad</th>
				<td><?= $unidad->nombre_unidad ?></td>
			</tr>
		</tbody>
	</table>

	<div class="mt-25">
		<table class="table table-hover table-striped">
			<colgroup>
				<col width="30%">
				<!-- <col width="25%"> -->
				<col width="10%">
				<col width="35%">
				<col width="15%">
				<col width="10%">
			</colgroup>
			<thead class="thead-dark">
				<tr>
					<th>Competencia generada</th>
					<!-- <th>Descripción</th> -->
					<th>Etapa</th>
					<th>Contenidos a evaluar</th>
					<th>Autor</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($competencias_resultado as $competencia) { ?>
				<tr>
					<td><?= $competencia->competencia_editable ? $competencia->competencia_editable : '-' ?></td>
					<!-- <td><?= $competencia->descripcion ?></td> -->
					<td><?= $competencia->etapa_actual ?></td>
					<td>
						<?php foreach ($competencia->Contenidos as $contenido) { ?>
							<ul>
								<li> <?= $contenido->detalle_secuencia_contenido ?> </li>
							</ul>
						<?php } ?>
					</td>
					<td>
						<?= $competencia->Usuario->nombre; ?>
						<!-- <i class="fa <?= $competencia->finalizado ? 'fa-check text-success' : 'fa-times text-danger' ?>"></i> -->
					</td>
					<td>
						<a class="btn btn-sm btn-secondary" href="<?= ruta('asistente.fresultado', ['id_competencia_resultado' => $competencia->id_competencia]) ?>" data-toggle="tooltip" data-placement="left" title="Editar competencia"><i class="fa fa-edit"></i></a>
					</td>
				</tr>
				<?php } ?>
				<?php if(count($competencias_resultado) == 0){ ?>
					<tr>
						<td colspan="4" class="text-center">
							No se han encontrado competencias de resultado para la unidad
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		$('#modal_btn_primary').hide();
    });
</script>