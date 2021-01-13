<?php 
$asignatura = $data->fromBody('asignatura');
$pageBack = $data->fromBody('pageBack', 1);
?>
<div class="container">
	
	<fieldset>
		<h4>Unidades de <?= $asignatura ? ' la asignatura '.$asignatura->nombre_asignatura : 'asignaturas' ?></h4>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<?php if($asignatura){ ?>
							<a href="<?= ruta('asignatura', ['page' => $pageBack ]) ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Regresar</a>
						<?php } ?>
						<button class="btn btn-primary" id="btn_nueva_unidad" data-toggle="tooltip" data-placement="bottom" title="Nueva unidad">
							<i class="fa fa-plus"></i>
						</button>
						<button class="btn btn-secondary" onClick="cargarListado(1)" id="btn_recargar_listado" data-toggle="tooltip" data-placement="bottom" title="Recargar listado">
							<i class="fa fa-refresh"></i>
						</button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 <?= $asignatura ? 'd-none' : '' ?>">
						<form id="frm_filtro_busqueda" class="form form-inline">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">Asignatura</div>
									</div>
							    	<input type="text" name="asignatura_nombre" value="<?= $this->term ?>" id="asignatura_nombre" class="form-control" placeholder="Nombre de una asignatura"/>
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				
			</div>
		</div>
		<div id="contenedor_listado" class="table-responsive"></div>
	</fieldset>
</div>


<script type="text/javascript">

	var asignaturaIndex = <?= $asignatura ? json_encode($asignatura) : 'null' ?>;
	var pageAsignatura = <?= $pageBack; ?>;

	function cargarListado(page){
		var term = $('#asignatura_nombre').val() || undefined;
		api.get({
			url: '<?= ruta("unidad.listado") ?>',
			data: { page: page || 1, term: term, id_asignatura: asignaturaIndex ? asignaturaIndex.id_asignatura : undefined, page_asignatura: pageAsignatura },
			contenedor: '#contenedor_listado',
			error: function(){ $('#contenedor_listado').errorAlert('Ha ocurrido un error al obtener el listado'); utils.alert.error('Ha ocurrido un error al obtener el listado') },
		});
	};

	$(document).off('click','.btn-editar-item').on('click','.btn-editar-item', function (e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    utils.modal.remote({
	    	url: '<?= ruta("unidad.formulario") ?>',
	    	data: { id_unidad_asignatura : id },
	    	modal_options: {
	    		titulo: 'Editar unidad-asignatura',
	    	},
	    	error: function(){
	    		utils.alert.error('Ha ocurrido un error al intentar abrir el formulario');
	    	}
	    });
	});

	$(document).off('click','.btn-eliminar-item').on('click','.btn-eliminar-item', function (e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    utils.modal.confirm({
	    	type: 'danger',
	    	size: 'md',
	    	titulo : 'Confirmación eliminación',
	    	contenido: '¿Estas seguro de eliminar esta unidad-asignatura?',
	    	success: function (dialog){
	    		dialog.btn.prop('disabled', true);

	    		$.ajax({
	    		    url: '<?= ruta("unidad.eliminar") ?>',
	    		    method: 'post',
	    		    data: {id_unidad_asignatura: id},
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
	    		    	dialog.body.errorAlert('Ha ocurrido un error al intentar eliminar la unidad-asignatura');
	    		        utils.alert.error('Ha ocurrido un error al intentar eliminar la unidad-asignatura');
	    		    }
	    		});


	    	}
	    });
	});

    $(document).ready(function(){
    	cargarListado();

		$('#btn_nueva_unidad').off('click').on('click', function (e){
		    e.preventDefault();
		    utils.modal.remote({
		    	url: '<?= ruta("unidad.formulario") ?>',
		    	data: { id_asignatura: asignaturaIndex ? asignaturaIndex.id_asignatura : undefined },
		    	modal_options: {
		    		titulo: 'Nueva unidad-asignatura',
		    	},
		    	error: function(){
		    		utils.alert.error('Ha ocurrido un error al intentar abrir el formulario');
		    	}
		    });
		});

		$('#frm_filtro_busqueda').on('submit', function(e){
			e.preventDefault();
			cargarListado();
		})

    });
</script>