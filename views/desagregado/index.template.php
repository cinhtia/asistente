<div class="container">
	<fieldset>
		<legend>Desagregados de contenidos</legend>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<button class="btn btn-primary" onclick="formulario()" data-toggle="tooltip" data-placement="bottom" title="Nuevo desagregado">
							<i class="fa fa-plus"></i>
						</button>
						<button class="btn btn-secondary" onclick="cargarListado(1)" data-toggle="tooltip" data-placement="bottom" title="Actualizar listado">
							<i class="fa fa-refresh"></i>
						</button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<form id="frm_filter_desagregado">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<div class="input-group-text">Buscar</div>
									</div>
									<input type="text" class="form-control" id="filter_descripcion" name="filter_descripcion" placeholder="Buscar por nombre">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="contenedor_listado"></div>
	</fieldset>
</div>

<script type="text/javascript">
	
	function cargarListado(page){
		var data = { page: page || 1 };
		if($('#filter_descripcion').val()){
			data.term = $('#filter_descripcion').val();
		}

		api.get({
			url: '<?= ruta("desagregado.listado") ?>',
			data: data,
			cb: function (response){
				$('#contenedor_listado').html(response);
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al cargar el listado');
			} 
		});
	}

	function formulario(idItem){
		let data = { id_desagregado: idItem };
		utils.modal.remote({
			url: '<?= ruta("desagregado.formulario") ?>',
			data: data,
			modal_options: { 
				titulo: idItem ? 'Editar registro' : 'Nuevo registro',
				size: 'md',
			},
			errorMessage: 'Ha ocurrido un error al abrir el formulario'
		});
	}

	function eliminar(idItem){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar este desagregado?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("desagregado.eliminar") ?>',
					data: { id_desagregado: idItem },
					cb: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
							cargarListado(1);
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
					},
					error: function(xhrError){
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar el registro'));
						utils.alert.error('Ha ocurrido un error al intentar eliminar el registro');
					}
				});
			}
		});
	}

    $(document).ready(function(){
		cargarListado(1);

		$('#frm_filter_desagregado').on('submit', function(e){
			e.preventDefault();
			cargarListado();
		})
    });
</script>