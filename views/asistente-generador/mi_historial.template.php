<?php
$total = $data->fromBody('total',0);
$competencias = $data->fromBody('lista', []);
$count = $data->fromBody('count',1);
$page = $data->fromBody('page',1);
$usuario = Sesion::obtener();
?>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion", count($competencias)) ?>

<table class="table table-hover">
	<thead>
		<tr>
			<th>No.</th>
			<th class='text-center'>Última etapa</th>
			<th>Descripción</th>
			<th>Iniciado</th>
			<th>Actualizado</th>
			<!-- <th>Estatus</th> -->
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($competencias as $key => $competencia) { 
			?>
			<tr>
				<td><?= ($key+1) ?></td>
				<td class='text-center'><?= ($competencia->etapa_actual) ?></td>
				<td><?= $competencia->descripcion; ?></td>
				<td><?= Helpers::fechaFormal(false, $competencia->fecha_creacion) ?></td>
				<td><?= Helpers::fechaFormal(false, $competencia->fecha_actualizacion) ?></td>
				<!-- <td><span class="text-info">En proceso</span></td> -->
				<td>
					<div class="dropdown" >
					  <button 
					  	class="btn btn-sm btn-secondary dropdown-toggle" 
					  	type="button" 
					  	id="dropdownMenuButton" 
					  	data-toggle="dropdown" 
					  	aria-haspopup="true" 
					  	aria-expanded="false"
					  	data-toggle="tooltip"
					  	data-placement="top"
					  	title="Opciones para <?= $user->nombre ?>">
					    <i class="fa fa-cog"></i>
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					    <a class="dropdown-item btn_editar_verbo" href="<?= ruta('asistente').'?id_competencia='.$competencia->id_competencia ?>" data-id=""><i class="fa fa-edit"></i> Editar</a>
					  </div>
					</div>
				</td>
			</tr>

		<?php }  ?>
	</tbody>
</table>



<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('.btn-paginacion').off('click').on('click', function(e){
			e.preventDefault();
			listarUsuarios($(this).data('page'));
		});

		// $('.btn_editar_verbo').off('click').on('click', function(e){
		// 	e.preventDefault();
		// 	var id = $(this).data('id');
		// 	GET($('#modal_modal_body'),"<?= ruta('usuario.editar') ?>","?id_usuario="+id, function(response){
		// 		$('#modal').modal('show');
		// 		$('#modal_modal_title').html('Editar datos');
		// 		$('#modal_modal_body').html(response);

		// 	}, function(){
		// 		alert('Ocurrió un error al solicitar la edición');
		// 	});
		// });


	})

</script>