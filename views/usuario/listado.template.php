<?php 
$total = $data->fromBody('total');
$usuarios = $data->fromBody('lista');
$count = $data->fromBody('count');
$page = $data->fromBody('page');
$usuario = Sesion::obtener();
?>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion", count($usuarios)) ?>

<table class="table table-hover table-bordered table-striped">
	<thead class="thead-dark">
		<tr>
			<th>No.</th>
			<th>Nombre</th>
			<th>Tipo</th>
			<th>Username</th>
		<!--	<th>Creado</th> -->
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($usuarios as $key => $user) { 
			?>
			<tr>
				<td><?= ($key+1) ?></td>
				<td><?= $user->nombre ?></td>
				<td><?= $user->tipo_usuario ?></td>
				<td><?= $user->username ?></td>
				<!-- <td><?= Helpers::fechaFormalCorta(false, $user->fecha_creacion) ?></td> -->
				<td>
					<?php
						if($user->id_usuario==$usuario->id_usuario || $usuario->tipo_usuario=='admin'){ ?>
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
						    <a class="dropdown-item btn_editar_verbo" href="#" data-id="<?= $user->id_usuario ?>"><i class="fa fa-edit"></i> Editar</a>
						  </div>
						</div>
					<?php	} ?>
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
			cargarMiHistorial($(this).data('page'));
		});

		$('.btn_editar_verbo').off('click').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
			GET($('#modal_modal_body'),"<?= ruta('usuario.editar') ?>","?id_usuario="+id, function(response){
				$('#modal').modal('show');
				$('#modal_modal_title').html('Editar datos');
				$('#modal_modal_body').html(response);

			}, function(){
				alert('Ocurrió un error al solicitar la edición');
			});
		});
	});
</script>