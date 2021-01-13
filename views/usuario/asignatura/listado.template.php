<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="90%">
		<col width="10%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Asignatura</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $index => $item){ ?>
		<tr>
			<td><?= $item->nombre_asignatura ?></td>
			<td>
				<button type="button" data-toggle="tooltip" data-placement="left" title="Eliminar asignatura del perfil" class="btn btn-danger btn-sm" onclick="eliminar(<?= $item->id_asignatura ?>)"><i class="fa fa-trash-o"></i></button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion") ?>