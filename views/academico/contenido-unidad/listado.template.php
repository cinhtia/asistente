<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion",count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<!-- <colgroup>
		<col width="100%">
	</colgroup> -->
	<thead class="thead-dark">
		<tr>
			<th>No</th>
			<th>Secuencia</th>
			<th>Desagregados</th>
			<th>Horas presenciales</th>
			<th>Horas no presenciales</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $index => $item){ ?>
		<tr>
			<td><?= $this->ua->num_unidad.'.'.($index+1) ?></td>
			<td><?= $item->detalle_secuencia_contenido ?></td>
			<td><?= $item->total_desagregados ?></td>
			<td><?= $item->duracion_hp ?></td>
			<td><?= $item->duracion_hnp ?></td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <button type="button" class="dropdown-item" onClick="abrirFormulario(<?= $item->id_contenido_unidad_asignatura ?>)" href="#"><i class="fa fa-edit"></i> Editar</button>
				    <button type="button" class="dropdown-item" onClick="eliminar(<?= $item->id_contenido_unidad_asignatura ?>)" href="#"><i class="fa fa-trash-o"></i> Eliminar</button>

				  </div>
				</div>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion") ?>