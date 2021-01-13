<?php 
$lista = $data->fromBody('lista');
$page = $data->fromBody('page',1);
$total = $data->fromBody('total',0);
$limit = $data->fromBody('limit',10);
 ?>
<?= Helpers::paginacionHTML($page, $total, $limit, "btn-paginacion", count($lista)) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="85%">
		<col width="15%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Descripción</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($lista as $key => $listaItem){ ?>
		
		<tr>
			<td><?= $listaItem->descrip_conocimiento ?></td>
			<td>
				<div class="dropdown">
				  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-cog"></i>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item btn-editar-item" data-id="<?= $listaItem->id_compo_conocimiento; ?>" href="#"><i class="fa fa-edit"></i> Editar</a>
				    <a class="dropdown-item btn-eliminar-item" data-id="<?= $listaItem->id_compo_conocimiento; ?>" href="#"><i class="fa fa-trash"></i> Eliminar</a>
				  </div>
				</div>
			</td>
		</tr>

		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($page, $total, $limit, "btn-paginacion") ?>	