<?php 
$contextos = $data->fromBody('contextos');
$page = $data->fromBody('page');
$count = $data->fromBody('count'); 
$total = $data->fromBody('total');
$usuario = Sesion::obtener();

?>

<br>
<strong><?= "Listando ".count($contextos)." de ".$total." resultados"?></strong>

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
		<?php foreach ($contextos as $key => $contexto) { 
			?>
			<tr>
				<td><?=($key+1)?></td>
				<td><?= $contexto->descrip_contexto ?></td>
			<!--	<td><?= Helpers::fechaFormalCorta(false,$contexto->fecha_creacion) ?></td> -->
				<td>
					<?php
						if($contexto->id_usuario==$usuario->id_usuario || $usuario->esAdmin){ ?>
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
						  	title="Opciones para <?= $contexto->descrip_contexto ?>">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item btn_editar_contexto" href="#" data-id="<?= $contexto->id_contexto ?>"><i class="fa fa-edit"></i> Editar</a>
						    <a class="dropdown-item btn_eliminar_contexto" href="#" data-id="<?= $contexto->id_contexto ?>"><i class="fa fa-trash-o"></i> Eliminar</a>
						  </div>
						</div>
					<?php	} ?>
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
			listarContextos($(this).data('page'));
		});

		$('.btn_editar_contexto').off('click').on('click', function(e){
			e.preventDefault();
			var idcontexto = $(this).data('id');

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'editar-contexto','?id_contexto='+idcontexto, function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Editar contexto');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('.btn_eliminar_contexto').off('click').on('click', function (e){
		    e.preventDefault();
		    var idContexto = $(this).data('id');
		    utils.modal.confirm({
		    	titulo    : 'Confirmación eliminación',
		    	contenido : '¿Confirma que desea eliminar este contexto?',
		    	size      : 'md',
		    	type      : 'danger',
		    	success   :  function (dialog){
		    		dialog.btn.prop('disabled',true);
		    		dialog.body.html('Eliminando, espera un momento por favor...');

		    		api.post({
		    			url: '<?= ruta("contexto.eliminar") ?>',
		    			data: { id: idContexto },
		    			success: function (response){
		    				if(response.estado){
		    					dialog.body.html(successAlert(response.mensaje));
		    					utils.alert.success(response.mensaje);
		    					listarContextos(1);
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