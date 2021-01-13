<div class="container">
	<fieldset>
		<legend>Rúbricas</legend>
		<div class="card">
			<div class="card-body">
				<a href="<?= ruta("rubrica.formulario") ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Nueva rubrica">
					<i class="fa fa-plus"></i>
				</a>
				<button type="button" class="btn btn-secondary" onclick="cargarListado(1)" data-toggle="tooltip" data-placement="bottom" title="Actualizar listado">
					<i class="fa fa-refresh"></i>
				</button>
			</div>
		</div>
		<div id="contenedor_listado"></div>
	</fieldset>
</div>

<script type="text/javascript">
	
	function cargarListado(page){
		api.get({
			url: '<?= ruta("rubrica.listado") ?>',
			data: { page: page || 1 },
			cb: function (response){
				$('#contenedor_listado').html(response);
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al cargar el listado');
			} 
		});
	}

	function eliminar(idItem){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Está seguro de eliminar esta rubrica?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("rubrica.eliminar") ?>',
					data: { id_rubrica: idItem },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
							cargarListado(1);
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
					},
					error: function(xhrError){
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar el registro'));
						utils.alert.error('Ha ocurrido un error al intentar eliminar el registro');
					}
				});
			}
		});
	}

    $(document).ready(function(){
		cargarListado(1);
    });
</script>