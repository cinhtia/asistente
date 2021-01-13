<div class="container">
	<fieldset>
		<legend>Herramientas Moodle</legend>
		<div class="card">
			<div class="card-body">
				<button class="btn btn-primary" onclick="formulario()" data-toggle="tooltip" data-placement="bottom" title="Nuevo herramienta">
					<i class="fa fa-plus"></i>
				</button>
				<button class="btn btn-secondary" onclick="cargarListado(1)" data-toggle="tooltip" data-placement="bottom" title="Actualizar listado">
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
			url: '<?= ruta("herramienta.listado") ?>',
			data: { page: page || 1 },
			cb: function (response){
				$('#contenedor_listado').html(response);
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al cargar el listado');
			} 
		});
	}

	function formulario(idItem){
		var data = { id_herramienta: idItem };
		utils.modal.remote({
			url: '<?= ruta("herramienta.formulario") ?>',
			data: data,
			modal_options: { 
				titulo: idItem ? 'Editar registro' : 'Nuevo registro',
				size: 'lg',
			},
			errorMessage: 'Ha ocurrido un error al abrir el formulario'
		});
	}

	function eliminar(idItem){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar este herramienta?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("herramienta.eliminar") ?>',
					data: { id_herramienta: idItem },
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
		})
	}

    $(document).ready(function(){
		cargarListado(1);
    });
</script>