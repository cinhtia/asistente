<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="5%;">
		<col width="30%;">
		<col width="20%;">
		<col width="25%;">
		<col width="10%;">
		<col width="10%;">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>No</th>
			<!-- <th>Tipo</th> -->
			<th>Asignatura</th>
			<th>Unidad</th>
			<th>Secuencias de contenido</th>
			<th>Etapa</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $index => $item){ ?>
		<tr>
			<td><?= $index+1 ?></td>
			<!-- <td><?= ucfirst($item->tipo_competencia); ?></td> -->
			<td><?= $item->nombre_asignatura ?></td>
			<td><?= 'Unidad '.$item->num_unidad ?>. <?= $item->nombre_unidad ?></td>
			<td>
				<ol>
					<?php foreach ($item->secuencias_contenido as $index2 => $contenido): ?>
						<li><?= $contenido ?></li>
					<?php endforeach ?>
				</ol>
			</td>
			<td><?= $item->etapa_actual; ?></td>
			<td>
				<a class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placemente="top" title="Editar" href="<?= ruta('asistente.fresultado').'?id_competencia_resultado='.$item->id_competencia; ?>"><i class="fa fa-edit"></i></a>
				<button data-toggle="tooltip" data-placement="top" title="Eliminar" type="button" onclick="eliminarCompetencia(<?= $item->id_competencia ?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>

				<!-- <div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item" href="< ?= ruta('asistente.fresultado').'?id_competencia_resultado='.$item->id_competencia; ? >"><i class="fa fa-edit"></i> Editar</a>
				  </div>
				</div> -->
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit,"btn-paginacion") ?>