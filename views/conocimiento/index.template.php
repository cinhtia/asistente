<div class="container">
	
	<h3>Conocimientos</h3>
	<hr>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<button class="btn btn-primary" id="btn_nuevo_item" data-toggle="tooltip" data-placemente="top" title="Nuevo conocimiento">
						<i class="fa fa-plus"></i>
					</button>
					<button class="btn btn-secondary" id="btn_recargar_listado" data-toggle="tooltip" data-placemente="top" title="Recargar listado">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<form class="form-inline pull-right" id="form_search">
					  <div class="form-group mb-2">
					    <label for="descrip_conocimiento_buscar" >Nombre &nbsp;</label>
					    <input type="text" class="form-control" name="descrip_conocimiento_buscar" id="descrip_conocimiento_buscar" placeholder="nombre">
					  </div>&nbsp;
					  <button type="submit" id="btn_buscar" class="btn btn-primary mb-2">
					  	<i class="fa fa-search"></i>
					  </button>
					</form>

				</div>
			</div>
		</div>
	</div>

	<div id="contenedor_listado"></div>

</div>

<script type="text/javascript">
	
	var cargarListado = function(page){
		var data = {
			page: page ? page : 1,
		};
		var descrip = $('#descrip_conocimiento_buscar').val();
		if(descrip.trim() != ""){
			data.descrip_conocimiento = descrip;
		}

		$.ajax({
		    url: '<?= ruta("conocimiento.listado") ?>',
		    method: 'get',
		    data: data,
		    success: function(response){
				$('#contenedor_listado').html(response);
		    },
		    error: function(xhr){
		        utils.alert.error('Ha ocurrido un error al obtener el listado');
		    }
		});
	};


	$(document).off('click','.btn-editar-item').on('click','.btn-editar-item', function (e){
		e.preventDefault();
		var id = $(this).data('id');

		utils.modal.remote({
			url: '<?= ruta("conocimiento.editar") ?>',
			data: {id_compo_conocimiento: id},
			modal_options: {
				titulo: 'Editar',
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			}
		});
	});

	$(document).off('click','.btn-eliminar-item').on('click','.btn-eliminar-item', function (e){
		e.preventDefault();
		var id = $(this).data('id');
		utils.modal.confirm({
			titulo: 'Confirmación',
			contenido: '¿Confirma que desea eliminar este conocimiento?',
			type: 'danger',
			size: 'md',
			success: function (dialog){
				dialog.btn.prop('disabled', true);
				dialog.body.html('Eliminando...');
				$.ajax({
				    url: '<?= ruta("conocimiento.eliminar") ?>',
				    method: 'post',
				    data: { id_compo_conocimiento: id },
				    success: function(response){
						if(response.estado){
							dialog.body.html(successAlert(response.mensaje));
							utils.alert.success(response.mensaje);
							cargarListado(1);
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


    $(document).ready(function(){
    	cargarListado(1);

    	$('#btn_nuevo_item').off('click').on('click', function (e){
    	    e.preventDefault();
    	    utils.modal.remote({
    	    	method: 'get',
    	    	url: '<?= ruta("conocimiento.nuevo") ?>',
    	    	modal_options: {
    	    		titulo: 'Nuevo conocimiento',
    	    	},
    	    	error: function(){
    	    		utils.alert.error('Ha ocurrido un error al abrir el formulario');
    	    	}
    	    });
    	});

    	$('#btn_recargar_listado').off('click').on('click', function (e){
    	    e.preventDefault();
    	    cargarListado(1);
    	});


    	$('#form_search').on('submit', function (e){
    		e.preventDefault();
    		cargarListado(1);
    	});

    });
</script>