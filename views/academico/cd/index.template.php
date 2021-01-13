<div class="container">
	<h4>Competencias disciplinares</h4>
	<div class="card">
		<div class="card-body">
			<button id="btn_nuevo" data-toggle="tooltip" data-placemente="top" title="Nueva competencia disciplinar" class="btn btn-primary"><i class="fa fa-plus"></i></button>
			<button class="btn btn-secondary" data-toggle="tooltip" data-placemente="top" title="Recargar listado" id="btn_recargar_listado" onClick="cargarListado()"><i class="fa fa-refresh"></i></button>
		</div>
	</div>

	<div id="contenedor_listado"></div>
</div>


<script type="text/javascript">
	
	function abrirForm(idCD){
		utils.modal.remote({
			url: '<?= ruta("cd.formulario") ?>',
			data: { id_competencia_disciplinar: idCD || 0 },
			error: function(){
				utils.alert.error('Ha ocurrido un error al intentar abrir el formulario');
			},
			modal_options: {
				titulo: idCD ? 'Editar competencia disciplinar' : 'Nueva competencia disciplinar',

			}
		});
	}


	var lastPage = 1;
	function cargarListado(page){
		if(!page){
			page = lastPage;
		}

		api.get({
			url: '<?= ruta("cd.listado") ?>',
			data: {page: page},
			cb: function (resp){
				lastPage = page;
				$('#contenedor_listado').html(resp);
			},
			error: function(xhr){
				utils.alert.error('Ha ocurrido un error al obtener la lista de competencias disciplinares');
			}
		});

	}

	$(document).off('click','.btn-editar-item').on('click', '.btn-editar-item', function (e){
	    e.preventDefault();
	    abrirForm($(this).data('id'));
	});


	$(document).off('click','.btn_eliminar_item').on('click', '.btn_eliminar_item', function (e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    utils.modal.confirm({
	    	titulo: 'Confirmación',
	    	contenido: '¿Esta seguro de eliminar esta competencia disciplinar?',
	    	type: 'danger',
	    	size: 'md',
	    	success: function(dialog){
	    		dialog.btn.prop('disabled', true);
	    		dialog.body.html('Eliminando...');

	    		api.post({
	    			url: '<?= ruta("cd.eliminar") ?>',
	    			data: { id_competencia_disciplinar : id },
	    			cb: function(response){
	    				if(response.estado){
	    					utils.alert.success(response.mensaje);
	    					dialog.body.html(successAlert(response.mensaje));
	    					cargarListado(1);
	    				}else{
	    					dialog.btn.prop('disabled', false);
	    					dialog.body.html(errorAlert(response.mensaje));
	    					utils.alert.error(response.mensaje);
	    				}
	    			},
	    			error: function(){
	    				dialog.btn.prop('disabled', false);
	    				dialog.body.html(errorAlert('Ha ocurrido un error al eliminar el registro seleccionado'));
	    				utils.alert.error('Ha ocurrido un error al eliminar el registro seleccionado');
	    			}
	    		})
	    	}
	    })
	});

    $(document).ready(function(){
		cargarListado(1);		

		$('#btn_nuevo').off('click').on('click', function (e){
		    e.preventDefault();
		    abrirForm();
		});

    });
</script>