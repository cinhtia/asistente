<!-- <style>
	.modal-lg {
	    max-width: 95% !important;
	}
</style> -->
<?php $form = new Form(); ?>
<div class="container">
	<fieldset>
		<legend><?= $this->nuevo ? 'Nueva rúbrica' : 'Editar rúbrica' ?></legend>
		<?php $form->begin('frm_rubrica'); ?>
		<?php $form->inputHidden([
			'id' => 'id_rubrica',
			'value' => $this->rubrica->id_rubrica
		]); ?>

		<?php  $form->bs4FormInput([
			'id'=>'descripcion_rubrica',
			'label'=>'Descripción',
			'required' => true,
			'value' => $this->nuevo ? '' : $this->rubrica->descripcion_rubrica,
			],true); ?>
		
		<?php  $form->bs4FormTextarea([
			'id'=>'explicacion_rubrica',
			'label'=>'Explicación',
			'placeholder'=>'Explicación',
			'required' => false,
			'value' => $this->nuevo ? '' : $this->rubrica->explicacion_rubrica,
			'rows' => '3'
			],true); ?>
		<div class="text-right">
			<a href="<?= ruta("rubrica") ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Cancelar</a>
			<button type="submit" id="btn_guardar_rubrica" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
		</div>
		<?php $form->end(); ?>
	</fieldset>
	<br><br>
	<?php if(!$this->nuevo){ ?>
	<div id="plantillas">
		<fieldset>
			<legend>Plantillas</legend>
			<div class="card">
				<div class="card-body">
					<button type="button" onclick="formularioPlantilla()" data-toggle="tooltip" data-placemente="top" title="Nueva plantilla" class="btn btn-primary"><i class="fa fa-plus"></i></button>
					<button type="button" onclick="recargarListadoPlantillas()" data-toggle="tooltip" data-placemente="top" title="Recargar listado" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
				</div>
			</div>
			<div id="contenedor_listado_plantillas"></div>
		</fieldset>
	</div>
	<?php } ?>
</div>

<script type="text/javascript">
	var nuevo = <?= $this->nuevo ? 'true' : 'false'; ?>;
	function recargarListadoPlantillas(){
		api.get({
			url: '<?= ruta("plantillarubrica").'?id_rubrica='.$this->rubrica->id_rubrica ?>',
			cb: function (response){
				$('#contenedor_listado_plantillas').html(response);
			},
			errorMessage: 'Ha ocurrido un error al obtener las plantillas de la rúbrica'
		});
	}


	function formularioPlantilla(idPlantilla) {
		var data = { id_plantilla_rubrica: idPlantilla, id_rubrica: <?= $this->rubrica->id_rubrica ?> };
		utils.modal.remote({
			url: '<?= ruta("plantillarubrica.formulario") ?>',
			data: data,
			modal_options: { 
				titulo: idPlantilla ? 'Editar plantilla' : 'Nueva plantilla',
				size: 'lg',
				backdrop: 'static',
			},
			error: function() {
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			}
		});
	}

	function eliminarPlantilla(idPlantilla){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Está seguro de eliminar esta platilla?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("plantillarubrica.eliminar") ?>',
					data: { id_plantilla_rubrica: idPlantilla },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
							recargarListadoPlantillas();
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
		})
	}

    $(document).ready(function(){

		var form = $('#frm_rubrica');
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				descripcion_rubrica : 'La descripción es obligatoria',
				// explicacion_rubrica : '',
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				$('#btn_guardar_rubrica').disabled();

				api.post({
					url: '<?= ruta("rubrica.guardar") ?>',
					data: form.serialize(),
					cb: function (response){
						$('#btn_guardar_rubrica').enabled();
						if(response.estado){
							utils.alert.success(response.mensaje);
							if(nuevo){
								if(confirm('La rúbrica fue creada correctamente. ¿Desea agregar alguna plantilla?')){
									window.location.replace('<?= ruta("rubrica.formulario") ?>?id_rubrica='+response.extra.id_rubrica+'#plantillas');
								}else{
									form[0].reset();									
								}
							}
						}else{
							utils.alert.error(response.mensaje);
						}
					},
					error: function (){
						$('#btn_guardar_rubrica').enabled();
						utils.alert.error('Ha ocurrido un error al intentar guardar los datos.');
					}
				});
			}
		});

		if(!nuevo){
			recargarListadoPlantillas();
		}
    });
</script>