<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="40%">
		<col width="40%">
		<col width="20%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Nombre</th>
			<th>Descripción</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $index => $item){ ?>
		<tr>
			<td><?= $item->nombre ?></td>
			<td><?= $item->descripcion ?></td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <button type="button" class="dropdown-item" onclick="formulario(<?= $item->id_recurso ?>)"><i class="fa fa-edit"></i> Editar</button>
				    <button type="button" class="dropdown-item" onclick="eliminar(<?= $item->id_recurso ?>)"><i class="fa fa-trash-o"></i> Eliminar</button>
				  </div>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion") ?>