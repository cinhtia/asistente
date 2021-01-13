<div class="container">
	
	<fieldset>
		<h4>Estrategias de enseñanza-aprendizaje</h4>
		<div class="card">
			<div class="card-body">
				<button onClick="abrirFormulario()" type="button" class="btn btn-primary" tooltip="Nueva estrategia ea"><i class="fa fa-plus"></i></button>
				<button class="btn btn-secondary" onClick="cargarListado(1)" data-toggle="tooltip" data-placement="bottom" title="Recargar listado">
					<i class="fa fa-refresh"></i>
				</button>
			</div>
		</div>
		<div id="contenedor_listado" class="table-responsive"></div>
	</fieldset>
</div>


<script type="text/javascript">

	function abrirFormulario(idEa){
		utils.modal.remote({
			url: '<?= ruta("ea.formulario") ?>',
			data: { id_ea : idEa || 0},
			error: function(){
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			},
			modal_options: {
				titulo: idEa ? 'Editar estrategia de enseñanza-aprendizaje' : 'Nueva estrategia de enseñanza-aprendizaje',
				backdrop: 'static',
			}
		});
	}

	function eliminar(id){
		utils.modal.confirm({
			type: 'danger',
			size: 'md',
			titulo : 'Confirmación eliminación',
			contenido: '¿Estas seguro de eliminar esta estrategia de enseñanza-aprendizaje?',
			success: function (dialog){
				dialog.btn.prop('disabled', true);

				$.ajax({
				    url: '<?= ruta("ea.eliminar") ?>',
				    method: 'post',
				    data: {id_estrategia_ea: id},
				    success: function(response){
				    	dialog.body.html('Eliminando..');
						if(response.estado){
							dialog.body.successAlert(response.mensaje);
							utils.alert.success(response.mensaje);
							cargarListado(1);
						}else{
							dialog.body.errorAlert(response.mensaje);
							utils.alert.error(response.mensaje);
						}
				    },
				    error: function(xhr){
				    	dialog.body.errorAlert('Ha ocurrido un error al intentar eliminar la estrategia de enseñanza-aprendizaje');
				        utils.alert.error('Ha ocurrido un error al intentar eliminar la estrategia de enseñanza-aprendizaje');
				    }
				});
			}
		});
	}
	
	function cargarListado(page){
		api.get({
			url: '<?= ruta("ea.listado") ?>',
			data: { page: page || 1 },
			contenedor: '#contenedor_listado',
			error: function(){ $('#contenedor_listado').errorAlert('Ha ocurrido un error al obtener el listado'); utils.alert.error('Ha ocurrido un error al obtener el listado') },
		});
	};

    $(document).ready(function(){
    	cargarListado();

    });
</script>