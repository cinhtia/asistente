<div class="container">
	<h4>Fase 1. Competencia de unidad</h4>
	<br>
	<form id="frm_fase1">
		<input type="hidden" value="<?= $this->competencia_unidad->id_competencia_padre ?>" name="id_competencia_padre">
		<input type="hidden" value="<?= $this->competencia_unidad->num_unidad; ?>" name="num_unidad">
		<input type="hidden" value="<?= $this->competencia_unidad->id_competencia; ?>" name="id_competencia_unidad">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="descripcion">Descripción (opcional)</label>
					<textarea placeholder="Incluye aquí una descripción de la competencia de ésta unidad" name="descripcion" class="form-control" id="descripcion" rows="4"><?= $this->competencia_unidad->descripcion; ?></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="form-group">
					<label for="dunidad">Unidad</label>
					<div id="dunidad" class="form-control"><?= $this->unidad; ?></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
				<button class="btn btn-primary" type='submit'>
					Siguiente  <i class="fa fa-arrow-right"></i> 
				</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		$('#frm_fase1').on('submit', function (e){
			e.preventDefault();

			$.ajax({
			    url: '<?= ruta("asistente.funidad_guardarf1") ?>',
			    method: 'post',
			    data: $('#frm_fase1').serialize(),
			    success: function(response){
					if(response.estado){
						// var idComp = competenciaUnidad.id_competencia;
						competenciaUnidad = response.extra;
						ultimoPasoGuardado = competenciaUnidad.etapa_actual;
						utils.alert.success(response.mensaje);
						fase2(true);
						// $('#smartwizard').smartWizard("next");
					}else{
						utils.alert.danger(response.mensaje);
					}
			    },
			    error: function(xhr){
			        utils.alert.error('Ha ocurrido un error al intentar guardar la fase 1');
			    }
			});

			
		});
    });
</script>