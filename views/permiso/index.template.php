<div class="container">
	<fieldset>
		<legend>Permisos</legend>
		<div class="card">
			<div class="card-body">
				<button class="btn btn-primary" onclick="abrirFormulario()" id="btn_nuevo_item" data-toggle="tooltip" data-placement="top" title="Nuevo permiso">
					<i class="fa fa-plus"></i>
				</button>
				<button class="btn btn-secondary" onClick="cargarListado(1)" data-toggle="tooltip" data-placement="top" title="Recargar listado">
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
			url: '<?= ruta("permiso.listado") ?>',
			contenedor: '#contenedor_listado',
			data: { page: page }
		});
	}

	function abrirFormulario(idPermiso){
		utils.modal.remote({
			url: '<?= ruta("permiso.formulario") ?>',
			data: { id_permiso: idPermiso || 0 },
			modal_options: {
				titulo: idPermiso ? 'Editar permiso' : 'Nuevo permiso',
				size: 'lg',
				backdrop: 'static',
			},
			errorMessage: 'Ha ocurrido un error'
		});
	}

	function eliminar(idPermiso){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar este permiso?',
			success: function (dialog){
				api.post({
					url: '<?= ruta("permiso.eliminar") ?>',
					data: { id_permiso: idPermiso },
					cb: function (response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
							cargarListado(1);
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
					},
					error: function (){
						utils.alert.error('Ha ocurrido un error al intentar eliminar el permiso');
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar el permiso'));
					}
				});
			},
		});
	}

    $(document).ready(function(){
		cargarListado(1);
    });
</script>