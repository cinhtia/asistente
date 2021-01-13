<div class="container">
	<h4>Competencias genéricas</h4>
	<div class="card">
		<div class="card-body">
			<button type="button" class="btn btn-primary" onclick="formulario()" data-toggle="tooltip" data-placement="top" title="Nueva competencia genérica">
				<i class="fa fa-plus"></i>
			</button>
			<button type="button" onclick="cargarListado(1)" class="btn btn-secondary" id="btn_recargar_cg" data-toggle="tooltip" data-placement="top" title="Recargar listado">
				<i class="fa fa-refresh"></i>
			</button>
		</div>
	</div>
	<div id="contenedor_listado"></div>
</div>

<script type='text/javascript'>

	function cargarListado(page){
		api.get({
			url: '<?= ruta("cg.listado") ?>',
			data: { page : page || 1},
			success: function(resp){
				$('#contenedor_listado').html(resp);
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al obtener el listado');
			}
		});
	}

	var formulario = function(idCG){
		utils.modal.remote({
			method: 'get',
			url: idCG ? '<?= ruta("cg.editar") ?>' : '<?= ruta("cg.nuevo") ?>',
			data: { id_cg: idCG },
			modal_options: {
				titulo: idCG ? 'Editar competencia genérica' : 'Nueva competencia genérica'
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error');
			}
		});
	}

	var eliminar = function(idCG){
		utils.modal.confirm({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar esta competencia genérica?',
			btn: 'Si, eliminar',
			size: 'md',
			type: 'danger',
			success: function(dialog){
				dialog.body.html('Eliminando');
				dialog.btn.prop('disabled', true);
				$.ajax({
				    url: '<?= ruta("cg.eliminar") ?>',
				    method: 'post',
				    data: {id_cg: idCG},
				    success: function(response){
						if(response.estado){
							dialog.body.html(successAlert('La competencia genérica fue eliminada correctamente'));
							utils.alert.success('La competencia genérica fue eliminada correctamente');
							cargarSeccionCG(1);
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
				    },
				    error: function(xhr){
				    	utils.alert.error('Error desconocido al intentar eliminar la competencia genérica');
				        dialog.body.html(errorAlert('Error desconocido al intentar eliminar la competencia genérica'));
				    }
				});
			}
		});
	}
	

	$(document).ready(function (){
		cargarListado(1);
	});

</script>