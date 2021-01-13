<?php 
$criterios = $data->fromBody('criterios');
$page = $data->fromBody('page',1);
$count = $data->fromBody('count'); 
$total = $data->fromBody('total');
$usuario = Sesion::obtener();

?>

<br>
<strong><?= "Listando ".count($criterios)." de ".$total." resultados"?></strong>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>

<table class="table table-hover">
	<colgroup>
		<col width="10%" />
		<col width="60%" />
		<col width="15%" />
		<col width="15%" />
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>No.</th>
			<th>Nombre</th>
		<!--	<th>Creado</th> -->
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($criterios as $key => $criterio) { 
			?>
			<tr>
				<td><?=($key+1)?></td>
				<td><?= $criterio->descrip_criterio ?></td>
			<!--	<td><?= Helpers::fechaFormalCorta(false,$criterio->fecha_creacion) ?></td> -->
				<td>
					<?php
						// if($criterio->id_usuario==$usuario->id_usuario || $usuario->tipoUsuario=='admin') { 
					?>
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
						  	title="Opciones para <?= $criterio->descrip_criterio ?>">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item btn_editar_criterio" href="#" data-id="<?= $criterio->id_criterio ?>"><i class="fa fa-edit"></i> Editar</a>
						    <a class="dropdown-item btn_eliminar_criterio" href="#" data-id="<?= $criterio->id_criterio ?>"><i class="fa fa-trash-o"></i> Eliminar</a>
						  </div>
						</div>
					<?php
				//		} 
						?>
				</td>
			</tr>

		<?php }  ?>
	</tbody>
</table>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>


<script>
	$(document).ready(function(){
		$('.btn-paginacion').off('click').on('click', function(e){
			e.preventDefault();
			listarCriterios($(this).data('page'));
		});

		$('.btn_editar_criterio').off('click').on('click', function(e){
			e.preventDefault();
			var idcriterio = $(this).data('id');

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'editar-criterio','?id_criterio='+idcriterio, function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Editar criterio');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('.btn_eliminar_criterio').off('click').on('click', function (e){
		    e.preventDefault();
		    var id = $(this).data('id');
		    utils.modal.confirm({
		    	titulo    : 'Confirmación eliminación',
		    	contenido : '¿Confirma que desea eliminar este criterio?',
		    	size      : 'md',
		    	type      : 'danger',
		    	success   :  function (dialog){
		    		dialog.btn.prop('disabled',true);
		    		dialog.body.html('Eliminando, espere un momento por favor...');

		    		api.post({
		    			url: '<?= ruta("criterio.eliminar") ?>',
		    			data: { id: id },
		    			success: function (response){
		    				if(response.estado){
		    					dialog.body.html(successAlert(response.mensaje));
		    					utils.alert.success(response.mensaje);
		    					listarCriterios(1);
		    				}else{
		    					dialog.body.html(errorAlert(response.mensaje));
		    					utils.alert.error(response.mensaje);
		    				}
		    			},
		    			error: function (){
		    				utils.alert.error('Ha ocurrido un error desconocido');
		    				dialog.body.html(errorAlert('Ha ocurrido un error desconocido'));
		    			}
		    		})
		    	}
		    });

		});
	});
</script>