<?php 
$verbos = $data->fromBody('verbos');
$page = $data->fromBody('page');
$count = $data->fromBody('count'); 
$total = $data->fromBody('total');
$usuario = Sesion::obtener();

?>

<br>
<strong><?= "Listando ".count($verbos)." de ".$total." resultados"?></strong>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>

<table class="table table-hover">
	<thead class="thead-dark">
		<tr>
			<th>No.</th>
			<th>Nombre</th>
			<th>Tipo saber</th>
			<th class="text-center">Seleccionable</th>
		<!--	<th>Creado</th> -->
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($verbos as $key => $verbo) { 
			?>
			<tr>
				<td><?=($key+1)?></td>
				<td><?= $verbo->descrip_verbo ?></td>
				<td><?= $verbo->tipo_saber_verbo ?></td>
				<td class="text-center"><i class="fa fa-<?= $verbo->disponible ? 'check' : 'times' ?> text-<?= $verbo->disponible ? 'success' : 'danger' ?>"></i></td>
			<!--	<td><?= Helpers::fechaFormalCorta(false,$verbo->fecha_creacion) ?></td> -->
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
					  	title="Opciones para <?= $verbo->descrip_verbo ?>">
					    <i class="fa fa-cog"></i>
					  </button>
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					    <a class="dropdown-item btn_editar_verbo" href="#" data-id="<?= $verbo->id_verbo ?>"><i class="fa fa-edit"></i> Editar</a>
					    <a class="dropdown-item btn_eliminar_verbo" href="#" data-id="<?= $verbo->id_verbo ?>"><i class="fa fa-trash-o"></i> Eliminar</a>
					  </div>
					</div>
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
			listarVerbos($(this).data('page'));
		});

		$('.btn_editar_verbo').off('click').on('click', function(e){
			e.preventDefault();
			var idVerbo = $(this).data('id');

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'editar-verbo','?id_verbo='+idVerbo, function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Editar verbo');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});

		});

		$('.btn_eliminar_verbo').off('click').on('click', function (e){
		    e.preventDefault();
		    var idVerbo = $(this).data('id');

		    utils.modal.confirm({
		    	titulo    : 'Confirmación eliminación',
		    	contenido : '¿Confirma que desea eliminar este verbo?',
		    	size      : 'md',
		    	type      : 'danger',
		    	success   :  function (dialog){
		    		dialog.btn.prop('disabled',true);
		    		dialog.body.html('Eliminando, espera un momento por favor...');

		    		api.post({
		    			url: '<?= ruta("verbo.eliminar") ?>',
		    			data: { id: idVerbo },
		    			success: function (response){
		    				if(response.estado){
		    					dialog.body.html(successAlert(response.mensaje));
		    					utils.alert.success(response.mensaje);
		    					listarVerbos(1);
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