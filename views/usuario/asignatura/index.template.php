<div class="container">
	<fieldset>
		<legend>Asignaturas de <?= $user->nombre; ?></legend>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<button type="button" class="btn btn-secondary" onclick="cargarListado(1)" data-toggle="tooltip" data-placement="bottom" title="Actualizar listado">
							<i class="fa fa-refresh"></i>
						</button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<form id="frm_agregar_asignatura" class="form pull-right form-inline">
							<div class="input-group">
							  <div class="input-group-prepend">
							    <label class="input-group-text" for="inputGroupSelect01">Asignatura</label>
							  </div>
							  <select class="custom-select" id="id_asignatura" required>
							    <option value="">Seleccione</option>
							    <?php foreach ($this->asignaturas as $index => $asignatura) {?>
							    	<option value="<?= $asignatura->id_asignatura ?>"><?= $asignatura->nombre_asignatura ?></option>
							    <?php } ?>
							  </select>
							  <div class="input-group-append">
							      <button class="btn btn-primary" type="submit"><i class="fa fa-plus"></i> Agregar</button>
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
		api.get({
			url: '<?= ruta("usuario.asignatura.listado") ?>',
			data: { page: page || 1 },
			cb: function (response){
				$('#contenedor_listado').html(response);
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al cargar el listado');
			} 
		});
	}

	function formulario(idAsignatura){
		let data = { ida: idAsignatura };
		utils.modal.remote({
			url: '<?= ruta("usuario.asignatura.formulario") ?>',
			data: data,
			modal_options: { 
				titulo: idAsignatura ? 'Editar registro' : 'Nuevo registro',
				size: 'lg',
			},
			errorMessage: 'Ha ocurrido un error al abrir el formulario'
		});
	}

	function eliminar(idAsignatura){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar esta asignatura?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("usuario.asignatura.eliminar") ?>',
					data: { id_asignatura: idAsignatura },
					success: function(response){
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
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar la asignatura'));
						utils.alert.error('Ha ocurrido un error al intentar eliminar la asignatura');
					}
				});
			}
		})
	}

	var enviando_asignatura = false;
    $(document).ready(function(){
		cargarListado(1);

		$('#frm_agregar_asignatura').on('submit', function(e){
			e.preventDefault();
			var id = $('#id_asignatura').val();
			if(id && !enviando_asignatura){
				enviando_asignatura = true;
				api.post({
					url: '<?= ruta("usuario.asignatura.guardar") ?>',
					data: { id_asignatura: id },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							$('#id_asignatura').val('');
							cargarListado(1);
						}else{
							utils.alert.error(response.mensaje);
						}
						enviando_asignatura = false;
					},
					error: function(){
						enviando_asignatura = false;
						utils.alert.error('Ha ocurrido un error al intentar agregar la asignatura a tu perfil de profesor');
					}
				});
			}
		});
    });
</script>