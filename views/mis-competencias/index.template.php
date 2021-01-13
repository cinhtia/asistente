<?php $finalizado = isset($_GET['finalizado']) && $_GET['finalizado'] == 1; ?>
<div class="container">
	<fieldset>
		<h4><?= $finalizado ? 'Evaluaciones concluidas - ADAs exportadas' : 'Resultados de aprendizaje en proceso de evaluación' ?></h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<a href="<?= ruta('asistente.fresultado') ?>" class="btn btn-primary" data-toggle="tooltip" data-placemente="top" title="Nueva competencia">
							<i class="fa fa-plus"></i>
						</a>
						<button type="button" href="#" id="btn_recargar_listado" class="btn btn-secondary" data-toggle="tooltip" data-placemente="top" title="Recargar listado">
							<i class="fa fa-refresh"></i>
						</button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						
					</div>
				</div>
			</div>
		</div>
		<div id="contenedor_listado_datos"></div>
	</fieldset>
</div>

<script type="text/javascript">

	var finalizado = <?= $finalizado ? '1' : '0'; ?>;
	
	function cargarListado(page){
		api.get({
			url: '<?= ruta("competencia.listado") ?>',
			data: { page: page, finalizado: finalizado },
			contenedor: '#contenedor_listado_datos',
			error: function (){  utils.alert.error('Ha ocurrido un error al obtener las competencias'); }
		});
	};

	function eliminarCompetencia(competenciaId) {
		var contenido = '<div class="text-center"><strong>¿Está seguro de eliminar este resultado de aprendizaje?</strong></div>';
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: contenido,
			confirm: function(dialog){
				dialog.body.html('<div class="text-danger text-center">Eliminando, espere un momento por favor...</div>');

				api.post({
					url: '<?= ruta("competencia.eliminar") ?>',
					data: { id: competenciaId },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.modal.modal('hide');
							cargarListado(1);
						}else{
							utils.alert.warning(response.mensaje);
							dialog.body.html(warningAlert(response.mensaje));
						}
					},
					error: function(){
						utils.alert.error('Ha ocurrido un error al eliminar el resultado de aprendizaje');
						dialog.body.html(errorAlert('Ha ocurrido un error al eliminar el resultado de aprendizaje'));
					}
				})

			},
		})

	}

    $(document).ready(function(){
    	cargarListado(1);

    	$('#btn_recargar_listado').off('click').on('click', function (e){
    	    e.preventDefault();
			cargarListado(1);    	    
    	});
    });
</script>