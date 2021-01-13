<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="20%">
		<col width="60%">
		<col width="10%">
		<col width="10%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Descripción</th>
			<th>Explicación</th>
			<th>Plantillas</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $index => $item){ ?>
		<tr>
			<td><?= $item->descripcion_rubrica ?></td>
			<td><?= $item->explicacion_rubrica ?></td>
			<td>
				<a href="<?= ruta("rubrica.formulario").'?id_rubrica='.$item->id_rubrica.'#plantillas' ?>" class="btn btn-link">
					<?= $item->total_plantillas ?>
				</a>
			</td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <a href="<?= ruta("rubrica.formulario").'?id_rubrica='.$item->id_rubrica?>" class="dropdown-item" ><i class="fa fa-edit"></i> Editar</a>
				    <button type="button" class="dropdown-item" onclick="eliminar(<?= $item->id_rubrica ?>)"><i class="fa fa-trash-o"></i> Eliminar</button>
				  </div>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion") ?>