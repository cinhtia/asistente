<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="25%">
		<col width="60%">
		<col width="10%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Descripción</th>
			<th>Explicación</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $key => $item){ ?>
		
		<tr>
			<td><?= $item->descripcion_ea ?></td>
			<td><?= $item->explicacion_ea ?></td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <button type="button" onClick="abrirFormulario(<?= $item->id_estrategia_ea ?>)" class="dropdown-item" ><i class="fa fa-edit"></i> Editar</button>
				    <button type="button" onClick="eliminar(<?= $item->id_estrategia_ea ?>)" class="dropdown-item" ><i class="fa fa-trash-o"></i> Eliminar</button>
				  </div>
				</div>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion") ?>