<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-striped table-hover table-bordered">
	<colgroup>
		<col width="40%">
		<col width="30%">
		<col width="15%">
		<col width="15%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Nombre</th>
			<th>Facultad</th>
			<th>Asignaturas</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($this->result['rows'] as $key => $pe) {?>
			<tr>
				<td>
					<?= $pe->nombre_pe ?>
				</td>
				<td>
					<?= $pe->facultad ? (isset(FACULTADES[$pe->facultad]) ? FACULTADES[$pe->facultad] : '-') : '-' ?>
				</td>
				<td class="text-center"> 
					<?= $pe->totalAsignaturas ?>
					<!-- <button type="button" onclick="abrir_asignaturas(<?= $pe->id_pe ?>)" class="btn btn-link btn_ver_asignaturas" data-id="<?= $pe->id_pe ?>">
					</button> -->
				</td>
				<td>
				    <button type="button" onclick="abrir_asignaturas(<?= $pe->id_pe ?>)" class="btn btn-sm btn-info btn_ver_asignaturas" data-id="<?= $pe->id_pe ?>" data-toggle="tooltip" data-placement="top" title="Ver asignaturas">
				    	<i class="fa fa-list"></i>
				    </button>
					<div class="dropdown" style="display: inline-block;">
					  <button 
					  	class="btn btn-sm btn-secondary dropdown-toggle" 
					  	type="button" 
					  	id="dropdownMenuButton" 
					  	data-toggle="dropdown" 
					  	aria-haspopup="true" 
					  	aria-expanded="false"
					  	data-toggle="tooltip"
					  	data-placement="top"
					 	title="Opciones <?= ' ' ?>"> 
					 	
					    <i class="fa fa-cog"></i>
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					    <button type="button" onclick="formulario(<?= $pe->id_pe ?>)" class="dropdown-item btn_editar_pe" data-id="<?= $pe->id_pe ?>"><i class="fa fa-edit"></i> Editar</button>
					    <button type="button" onclick="eliminar(<?= $pe->id_pe ?>)" class="dropdown-item btn_eliminar_pe" href="#" data-id="<?= $pe->id_pe ?>"><i class="fa fa-trash"></i> Eliminar</button>
					  </div>
					</div>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion") ?>