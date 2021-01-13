<div class="container-fluid">

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 table-responsive">
			<br><br>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th>Asignatura</th>
						<td><?= $this->asignatura->nombre_asignatura; ?></td>
					</tr>
					<tr>
						<th>Unidad</th>
						<td><?= $this->unidad; ?></td>
					</tr>
					<tr>
						<th>Competencia de asignatura</th>
						<td><?= $this->competencia_padre->competencia_editable; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			<div class="container" id="contenedor_mensajes_alerta"></div>
			<div id="smartwizard" style='margin: 10px;'>
				<!-- Nombres de secciones -->
				<ul>
					<li><a href="#step-1">Fase 1 <br><small>Datos básicos</small></a></li>
					<li><a href="#step-2">Fase 2 <br><small>Creación de competencia</small></a></li>
					<li><a href="#step-3">Fase 3 <br><small>Desglose componentes</small></a></li>
				</ul>

				<!-- contenido de cada elemento -->
				<div>
					<div id="step-1" style='padding:10px;'><div id="contenedor_fase_1"></div></div>
					<div id="step-2" style='padding:10px;'><div id="contenedor_fase_2"></div></div>
					<div id="step-3" style='padding:10px;'><div id="contenedor_fase_3"></div></div>
				</div>

			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	var competenciaAsignatura = <?= json_encode($this->competencia_padre); ?>;
	var competenciaUnidad = <?= json_encode($this->competencia_unidad); ?>;
	var unidad = <?= $this->unidad; ?>;
	var ultimoPasoGuardado = <?= $this->competencia_unidad->etapa_actual ?>;
	var nuevo = <?= $this->competencia_unidad->id_competencia == 0 ? 'true' : 'false' ?>;

	var fase1Cargada = false;
	var fase2Cargada = false;
	var fase3Cargada = false;

	function dataCompetencia(){
		return {
			id_competencia_padre: competenciaAsignatura.id_competencia,
			id_competencia_unidad : competenciaUnidad.id_competencia,
			unidad: unidad
		};
	}

	
	function fase1(){
		if(!fase1Cargada){
			$.ajax({
			    url: '<?= ruta("asistente.funidad_form1") ?>',
			    method: 'get',
			    data: dataCompetencia(),
			    success: function(response){
			    	fase1Cargada = true;
					$('#contenedor_fase_1').html(response);
			    },
			    error: function(xhr){
			        $('#contenedor_fase_1').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 1'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 1');
			    }
			});
		}
	}

	function fase2(sigTab){
		if(!fase2Cargada && ultimoPasoGuardado>=1){
			$.ajax({
			    url: '<?= ruta("asistente.funidad_form2") ?>',
			    method: 'get',
			    data: dataCompetencia(),
			    success: function(response){
			    	fase2Cargada = true;
					$('#contenedor_fase_2').html(response);
					if(sigTab){ // para cambiar al tab 2
						$('#smartwizard').smartWizard("next");
					}
			    },
			    error: function(xhr){
			        $('#contenedor_fase_2').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 2'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 2');
			    }
			});
		}else if(fase2Cargada){
			$('#smartwizard').smartWizard("next");
		}
	}

	function fase3(sigTab){
		if(!fase3Cargada && ultimoPasoGuardado>=2){
			$.ajax({
			    url: '<?= ruta("asistente.funidad_form3") ?>',
			    method: 'get',
			    data: dataCompetencia(),
			    success: function(response){
					$('#contenedor_fase_3').html(response);
					if(sigTab){ // para cambiar al tab 3
						$('#smartwizard').smartWizard("next");
					}
			    },
			    error: function(xhr){
			        $('#contenedor_fase_3').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 3'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 3');
			    }
			});
		}else if(fase3Cargada){
			$('#smartwizard').smartWizard("next");
		}
	}

	var tabAnterior = function(){
		$('#smartwizard').smartWizard("prev");
	};

	$(document).ready(function(){
		fase1();
		fase2();
		fase3();

		// ---------------------------------------------------------
		// Step show event
	    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
	       // alert("You are on step "+stepNumber+" now");
	       if(stepPosition === 'first'){
	           $("#prev-btn").addClass('disabled');
	       }else if(stepPosition === 'final'){
	           $("#next-btn").addClass('disabled');
	       }else{
	           $("#prev-btn").removeClass('disabled');
	           $("#next-btn").removeClass('disabled');
	       }
	    });

	    // Smart Wizard
	    $('#smartwizard').smartWizard({
	            selected: !nuevo && ultimoPasoGuardado == 0 ? 1 : Math.min(ultimoPasoGuardado, 2),
	            theme: 'arrows',
	            keyNavigation: false,
	            transitionEffect:'fade',
	            enableFinishButton: true,
	            // disabledSteps: [],
	            labelNext:'Siguiente', // label for Next button
    			labelPrevious:'Anterior', // label for Previous button
    			labelFinish:'Finalizar',
	            toolbarSettings: {
	            	toolbarPosition: 'none',
	            	showNextButton: false,
	            	showPreviousButton: false,
	            },
	            lang: {
	            	next: 'Siguiente',
	            	previous: 'Anterior'
	            }
	    });

	    // Set selected theme on page refresh
	    $("#theme_selector").change();

	});
</script>