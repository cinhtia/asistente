<div class="table-responsive">
	<table class="table table-hover">
		<colgroup>
			<!-- <col width="20%"> -->
			<col width="85%">
			<col width="15">
		</colgroup>
		<thead class="thead-dark">
			<tr>
				<!-- <th>No</th> -->
				<th>Nombre</th>
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->plantillas as $index => $item) { ?>
			<tr>
				<!-- <td><?= ($index+1) ?></td> -->
				<td><?= $item->nombre ?></td>
				<td>
					<div class="dropdown">
					  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    <i class="fa fa-cog"></i>
					  </button>
					  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
					    <button type="button" onclick="formularioPlantilla(<?= $item->id_plantilla_rubrica ?>)" class="dropdown-item" href="#"><i class="fa fa-edit"></i> Editar</button>
					    <button type="button" onclick="eliminarPlantilla(<?= $item->id_plantilla_rubrica ?>)" class="dropdown-item" href="#"><i class="fa fa-trash-o"></i> Eliminar</button>
					  </div>
					</div>
				</td>
			</tr>
			<?php } ?>
			<?php if(count($this->plantillas) == 0){ ?>
			<tr>
				<td colspan="3">
					<div class="alert alert-info text-center"><strong>Sin plantillas</strong></div>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>