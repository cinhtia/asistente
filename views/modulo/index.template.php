<div class="container">
	<fieldset>
		<legend>Módulos</legend>
		<div class="card">
			<div class="card-body">
				<button class="btn btn-success" onClick="instalarModulos()" id="btn_nuevo_item" data-toggle="tooltip" data-placement="top" title="Instalar módulos">
					<i class="fa fa-download"></i>
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
	
	function instalarModulos(){
		utils.alert.info('En proceso');
		api.post({
			url: '<?= ruta("modulo.instalar") ?>',
			cb: function (response){
				if(response.estado){
					utils.alert.success(response.mensaje);
				}else{
					utils.alert.error(response.mensaje);
				}
			},
			errorMessage:'Ha ocurrido un error al intentar instalar los módulos',
		});
	}


	function cargarListado(page){
		api.get({
			url: '<?= ruta("modulo.listado") ?>',
			contenedor: '#contenedor_listado',
			data: { page: page }
		});
	}

	// function abrirFormulario(idModulo){
	// 	utils.modal.remote({
	// 		url: '< ?= ruta("modulo.formulario") ? >',
	// 		data: { id_modulo: idModulo || 0 },
	// 		modal_options: {
	// 			titulo: idModulo ? 'Editar modulo' : 'Nuevo modulo',
	// 			size: 'lg',
	// 		},
	// 		errorMessage: 'Ha ocurrido un error'
	// 	});
	// }

	// function eliminar(idModulo){
	// 	utils.modal.confirmDelete({
	// 		titulo: 'Confirmación eliminación',
	// 		contenido: '¿Esta seguro de eliminar este modulo?',
	// 		success: function (dialog){
	// 			api.post({
	// 				url: '<?= ruta("modulo.eliminar") ?>',
	// 				data: { id_modulo: idModulo },
	// 				cb: function (response){
	// 					if(response.estado){
	// 						utils.alert.success(response.mensaje);
	// 						dialog.body.html(successAlert(response.mensaje));
	// 						cargarListado(1);
	// 					}else{
	// 						utils.alert.error(response.mensaje);
	// 						dialog.body.html(errorAlert(response.mensaje));
	// 					}
	// 				},
	// 				error: function (){
	// 					utils.alert.error('Ha ocurrido un error al intentar eliminar el modulo');
	// 					dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar el modulo'));
	// 				}
	// 			});
	// 		},
	// 	});
	// }

    $(document).ready(function(){
		cargarListado(1);
    });
</script>