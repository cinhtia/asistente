<?php
$avs = $data->fromBody('avs',[]);
$page = $data->fromBody('page',1);
$count = $data->fromBody('count',1); 
$total = $data->fromBody('total');
$usuario = Sesion::obtener();
?>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion", count($avs)) ?>

<table class="table table-hover">
	<colgroup>
		<col width="10%">
		<col width="50%">
		<col width="15%">
		<col width="15%">
		<col width="10%">
	</colgroup>

	<thead class="thead-dark">
		<tr>
			<th>No.</th>
			<th>Nombre</th>
			<th>Tipo</th>
		<!--	<th>Creado</th> -->
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($avs as $key => $av) {
			?>
			<tr>
				<td><?= ($key+1) ?></td>
				<td><?= $av->descrip_actitud_valor ?></td>
				<td><?= TblCompo_actitud_valor::tipoFormal($av->tipo) ?></td>
			<!--	<td><?= Helpers::fechaFormalCorta(false, $av->fecha_creacion) ?></td> -->
				<td>
					<?php
						if($av->id_usuario==$usuario->id || $usuario->esAdmin){ ?>
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
						  	title="Opciones para <?= $av->descrip_actitud_valor ?>">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item btn_editar_av" href="#" data-id="<?= $av->id_compo_valor ?>"><i class="fa fa-edit"></i> Editar</a>
						    <a class="dropdown-item btn_eliminar_av" href="#" data-id="<?= $av->id_compo_valor ?>"><i class="fa fa-trash-o"></i> Eliminar</a>
						  </div>
						</div>
					<?php	} ?>


				</td>
			</tr>
			<?php
		} ?>
	</tbody>
</table>

<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>



<script>
	$(document).ready(function(){
		$('.btn-paginacion').off('click').on('click', function(e){
			e.preventDefault();
			listarAVs($(this).data('page'));
		});

		$('.btn_editar_av').off('click').on('click', function(e){
			e.preventDefault();
			var idAV = $(this).data('id');

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'<?= ruta("actitud_valor.editar") ?>','?id_compo_valor='+idAV, function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Editar actitud-valor');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});


		$('.btn_eliminar_av').off('click').on('click', function (e){
		    e.preventDefault();
		    var id = $(this).data('id');
		    utils.modal.confirm({
		    	titulo: 'Confirmación',
		    	contenido: '¿Esta seguro de eliminar este registro?',
		    	type: 'danger',
		    	size: 'md',
		    	success: function (dialog){
		    		dialog.btn.prop('disabled', true);
		    		dialog.body.html('Eliminando...');
		    		$.ajax({
		    		    url: '<?= ruta("actitud_valor.eliminar") ?>',
		    		    method: 'post',
		    		    data: { id_av: id },
		    		    success: function(response){
		    				if(response.estado){
		    					dialog.body.html(successAlert(response.mensaje));
		    					utils.alert.success(response.mensaje);
		    					listarAVs(1);
		    				}else{
		    					dialog.body.html(errorAlert(response.mensaje));
		    					utils.alert.error(response.mensaje);
		    				}
		    		    },
		    		    error: function(xhr){
		    		        utils.alert.error('Ha ocurrido un error al eliminar el elemento seleccionado');
		    		    }
		    		});
		    	}
		    });
		});


	});
</script>