<?php 
$id_asignatura = Request::singleton()->getIfExists('id_asignatura', null);
$page_asignatura = Request::singleton()->getIfExists('page_asignatura', 1);
 ?>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	
	<thead class="thead-dark">
		<tr>
			<th>Nombre</th>
			<th>No unidad</th>
			<th>Duración (Horas)</th>
			<th>No. Contenidos</th>
			<?php if(!$id_asignatura){ ?>
			<th>Asignatura</th>
			<?php } ?>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $key => $item){ ?>
		
		<tr>
			<td><a href="<?= ruta('cont_unidad', ['id' => $item->id_unidad_asignatura, 'page' => $this->page, 'id_asignatura' => $id_asignatura, 'page_asignatura' => $page_asignatura, 'term' => $this->term ]) ?>"><?= $item->nombre_unidad ?></a></td>
			<td><?= $item->num_unidad; ?></td>
			<td><?= intval($item->duracion_unidad_hp)+intval($item->duracion_unidad_hnp) ?></td>
			<td><?= $item->numero_contenidos ?></td>
			<?php if(!$id_asignatura){ ?>
			<td><?= $item->nombre_asignatura ?></td>
			<?php } ?>
			<td>
				<a class="btn btn-sm btn-secondary" href="<?= ruta('cont_unidad', ['id' => $item->id_unidad_asignatura, 'page' => $this->page, 'id_asignatura' => $id_asignatura, 'page_asignatura' => $page_asignatura, 'term' => $this->term ]) ?>" data-toggle="tooltip" data-placement="top" title="Ver contenidos de la unidad"><i class="fa fa-eye"></i></a>
				<div class="dropdown" style="display: inline-block;">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item  btn-editar-item" data-id="<?= $item->id_unidad_asignatura ?>" href="#"><i class="fa fa-edit"></i> Editar</a>
				    <a class="dropdown-item  btn-eliminar-item" data-id="<?= $item->id_unidad_asignatura ?>" href="#"><i class="fa fa-trash"></i> Eliminar</a>
				  </div>
				</div>

			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->count, "btn-paginacion") ?>