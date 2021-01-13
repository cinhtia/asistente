<div class="container">
	<h4>Planes de estudio</h4>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<a href="#" id="btn_nuevo_pe" class="btn btn-primary" data-toggle="tooltip" data-plaecement="top" title="Nuevo plan de estudio">
						<i class="fa fa-plus"></i>
					</a>
					<a href="#" id="btn_recargar_pes" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Recargar listado de planes de estudio" >
						<i class="fa fa-refresh"></i>
					</a>
				</div>
			</div>			
		</div>
	</div>
	<div id="contenedor_listado"></div>
</div>

<script type="text/javascript">
	
	function cargarCompetenciasGenericas(page, modalAbierto){
		$.ajax({
		    url: '<?= ruta("pe.cgs") ?>',
		    method: 'get',
		    data: {},
		    success: function(response){
				
		    },
		    error: function(xhr){
		        
		    }
		});
	}

	function formulario(idPe){
		utils.modal.remote({
			url: idPe ? '<?= ruta("pe.editar") ?>' : '<?= ruta("pe.nuevo") ?>',
			data: {id_pe : idPe || undefined },
			modal_options: {
				titulo: idPe ? 'Editar Plan de Estudio' : 'Nuevo Plan de Estudio'
			},
			error: function(){
				utils.alert.error('Ocurrió un error al abrir el formulario de nuevo plan de estudio');
			}
		});
	}

	function cargarListado(page){
		api.get({
			url: '<?= ruta("pe.listado") ?>',
			data: { page: page || 1},
			success: function(response){
				$('#contenedor_listado').html(response);
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al cargar el listado')
			}
		});
	}

	function eliminar(id_pe){
		utils.modal.confirm({
			titulo: 'Confirmación',
			contenido: '¿Esta seguro de eliminar este plan de estudio?',
			btn_primary: 'Eliminar',
			type: 'danger',
			size: 'md',
			auto_dismiss: false,
			success: function(dialog){
				dialog.body.html('Eliminando. Espera por favor...');
				dialog.btn.prop('disabled',true);

				$.ajax({
					url: '<?= ruta("pe.eliminar") ?>',
					method: 'post',
					data: {id_pe: id_pe},
					success: function(response){
						if(response.estado){
							cargarListado();
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert('Plan de estudio eliminado'));
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
					}, error: function(){
						utils.alert.error('Ocurrió un error al intentar eliminar la institución');
						dialog.body.html(errorAlert('Ocurrió un error al intentar eliminar la institución'));
					}
				});
			}
		});
	}

	function abrir_asignaturas(id_pe) {
		utils.modal.remote({
			url: '<?= ruta("pe.form_pe_asignaturas") ?>',
			modal_options: { titulo : 'Asignaturas de Plan de estudios' },
			method: 'get',
			data: { id_pe : id_pe },
			error: function(){
				utils.alert.error('Ha ocurrido un error al abrir el listado de asignaturas');
			}
		});
	}

	// $(document).off('click','.btn_ver_asignaturas').on('click','.btn_ver_asignaturas', function(e){
	// 	e.preventDefault();
	// 	var idpe = $(this).data('id');
	// 	// cararSubseccionAsignaturasPlanEstudio(idpe, < ?= $page ? >);
	// 	utils.modal.remote({
	// 		url: '< ?= ruta("pe.form_pe_asignaturas") ? >',
	// 		data: {id_pe: idpe},
	// 		modal_options: {
	// 			titulo: 'Asignaturas de plan de estudio'
	// 		},
	// 		error: function(){
	// 			alert("Error desconocido");
	// 		}
	// 	});
	// });

	$(document).ready(function(){
		$('#btn_nuevo_pe').off('click').on('click', function(e){
			e.preventDefault();
			formulario();
		});

		$('.btn_editar_pe').off('click').on('click', function(e){
			e.preventDefault();
			formulario($(this).data('id'));
		});

		$('.btn_ver_cgs').off('click').on('click', function (e){
		    e.preventDefault();
		    var idPE = $(this).data('id');
		    utils.modal.remote({
		    	url: '<?= ruta("pe.cgs") ?>',
		    	data: {id_pe: idPE},
		    	modal_options: {
		    		titulo : 'Competencias genéricas',
		    	},
		    	error: function(){
		    		utils.alert.error('Error desconocido')
		    	}
		    });
		});


		$('#btn_recargar_pes').off('click').on('click', function (e){
			e.preventDefault();
			cargarListado();
		});

		$('#btn_buscar_pe').off('click').on('click', function (e){
			e.preventDefault();
			cargarListado();
		});

		
		cargarListado(1);
	});
</script>