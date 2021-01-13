<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, 'btn-paginacion', count($this->result['rows'])); ?>
<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-dark">
			<tr>
				<th>Tipo</th>
				<th>Asignatura</th>
				<th>Unidad</th>
				<th>Autor</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->result['rows'] as $competencia) { ?>
				<tr>
					<td><?= ucfirst($competencia->tipo_competencia) ?></td>
					<td><?= $competencia->nombre_asignatura ?></td>
					<td><?= $competencia->tipo_competencia == 'asignatura' ? '-' : $competencia->num_unidad ?></td>
					<td><?= $competencia->nombre_autor ?></td>
					<!-- <td><?= Helpers::fechaFormalCorta(false, $competencia->fecha_actualizacion) ?></td> -->
					<td>
						<div class="dropdown">
						  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						    <button class="dropdown-item" onclick="formulario(<?= $competencia->id_competencia; ?>)"><i class="fa fa-edit"></i> Editar</button>
						    <button type="button" class="dropdown-item" onclick="eliminar(<?= $competencia->id_competencia ?>)" ><i class="fa fa-trash-o"></i> Eliminar</button>
						  </div>
						</div>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div><?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, 'btn-paginacion'); ?>