<?php
$cgs = $data->fromBody('cgs', []);

$page = $data->fromBody('page',1);
$count = $data->fromBody('count',1); 
$total = $data->fromBody('total',1);
?>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion", count($cgs)) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="70%">
		<col width="10%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Descripción</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($cgs as $key => $cgsItem){ ?>
		
		<tr>
			<td><?= $cgsItem->descripcion_cg ?></td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <button type="button" class="dropdown-item" onclick="formulario(<?= $cgsItem->id_cg ?>)"><i class="fa fa-edit"></i> Editar</button>
				    <button type="button" class="dropdown-item" onclick="eliminar(<?= $cgsItem->id_cg ?>)"><i class="fa fa-trash"></i> Eliminar</button>
				  </div>
				</div>	
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>