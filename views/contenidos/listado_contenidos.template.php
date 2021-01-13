<?php 
$contenidos = $data->fromBody('contenidos');
$page = $data->fromBody('page');
$count = $data->fromBody('count'); 
$total = $data->fromBody('total');
$usuario = Sesion::obtener();

?>

<br>
<strong><?= "Listando ".count($contenidos)." de ".$total." resultados"?></strong>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>

<table class="table table-hover">
	<colgroup>
		<col width="10%" />
		<col width="75%" />
		<col width="15%" />
	</colgroup>
	<thead class="thead-dark">
		
		<tr>
			<th>No.</th>
			<th>Nombre</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($contenidos as $key => $contenido) { 
			?>
			<tr>
				<td><?=($key+1)?></td>
				<td><?= $contenido->descrip_contenido ?></td>
				<td>
					<?php
						if($contenido->id_usuario==$usuario->id_usuario || $this->user->esAdmin){ ?>
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
						  	title="Opciones para <?= $contenido->descrip_contenido ?>">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item btn_editar_contenido" href="#" data-id="<?= $contenido->id_contenido ?>"><i class="fa fa-edit"></i> Editar</a>
						    <a class="dropdown-item btn_eliminar_contenido" href="#" data-id="<?= $contenido->id_contenido ?>"><i class="fa fa-trash-o"></i> Eliminar</a>
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
			listarContenidos($(this).data('page'));
		});

		$('.btn_editar_contenido').off('click').on('click', function(e){
			e.preventDefault();
			var idcontenido = $(this).data('id');

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'editar-contenido','?id_contenido='+idcontenido, function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Editar contenido');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('.btn_eliminar_contenido').off('click').on('click', function (e){
		    e.preventDefault();
		    var id = $(this).data('id');

		    utils.modal.confirm({
		    	titulo    : 'Confirmación eliminación',
		    	contenido : '¿Confirma que desea eliminar este contenido?',
		    	size      : 'md',
		    	type      : 'danger',
		    	success   :  function (dialog){
		    		dialog.btn.prop('disabled',true);
		    		dialog.body.html('Eliminando, espere un momento por favor...');

		    		api.post({
		    			url: '<?= ruta("contenido.eliminar") ?>',
		    			data: { id: id },
		    			success: function (response){
		    				if(response.estado){
		    					dialog.body.html(successAlert(response.mensaje));
		    					utils.alert.success(response.mensaje);
		    					listarContenidos(1);
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