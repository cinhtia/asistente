<div class="container">
	<fieldset>
		<legend>Competencias de asignatura y unidad</legend>
		<div class="card">
			<div class="card-body">
				<button type="button" onclick="formulario()" class="btn btn-primary" id="btn_nueva_competencia" data-toggle="tooltip" data-placement="bottom" title="Nueva competencia">
					<i class="fa fa-plus"></i>
				</button>

				<button type="button" onclick="cargarListado(1)" class="btn btn-secondary" id="btn_recargar_listado" data-toggle="tooltip" data-placement="bottom" title="Recargar listado">
					<i class="fa fa-refresh"></i>
				</button>
			</div>
		</div>
		<div id="contenedor_listado"></div>
	</fieldset>
</div>

<script type="text/javascript">
	var lastPage = 1;
	function formulario(idC){
		utils.modal.remote({
			url: '<?= ruta("competencia2.formulario") ?>',
			data: { id_competencia: idC || 0 },
			modal_options: {
				titulo: idC ? 'Editar competencia ':'Nueva competencia',
				backdrop: 'static'
			},
			errorMessage: 'Ha ocurrido un error al abrir el formulario'
		});
	}

	function cargarListado(page){
		api.get({
			url: '<?= ruta("competencia2.listado") ?>',
			data: { page: page || lastPage },
			errorMessage: 'Ha ocurrido un error al intentar obtener los datos',
			cb: function(resp){
				$('#contenedor_listado').html(resp);
				lastPage = page;
			}
		});
	}

	function eliminar(id_competencia){
		utils.modal.confirm({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar esta competencia? La eliminación de este registro puede no ser posible si actualmente está en uso.',
			size: 'md',
			type: 'danger',
			success: function(dialog){
				dialog.body.html('Eliminando..');
				dialog.btn.disabled();

				api.post({
					url: '<?= ruta("competencia2.eliminar") ?>',
					data: {id_competencia: id_competencia},
					success: function (response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
							dialog.btn.enabled();
						}
					},
					error: function(){
						utils.alert.error('Ha ocurrido un error al intentar eliminar el registro.');
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar el registro.'));
						dialog.btn.enabled();
					}
				});

			},
		});
	}

    $(document).ready(function(){
		cargarListado(1);
    });
</script>