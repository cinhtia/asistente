<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="container" id="contenedor_mensajes_alerta"></div>
			<div id="smartwizard" style='margin: 10px;'>
				<!-- Nombres de secciones -->
				<ul>
					<li><a href="#step-1">Fase 0 <br><small>Información general</small></a></li>
					<li><a href="#step-2">Fase 1 <br><small>Construcción de la competencia</small></a></li>
					<li><a href="#step-3">Fase 2 <br><small>Desglose componentes</small></a></li>
					<li><a href="#step-4">Fase 3 <br><small>Competencias genéricas</small></a></li>
					<li><a href="#step-5">Fases 4-6 <br><small>Especificación/Construcción ADAs</small></a></li>
					<li><a href="#step-6">Fase 7 <br><small>Exportación de ADAs</small></a></li>
				</ul>

				<!-- contenido de cada elemento -->
				<div>
					<div id="step-1" style='padding:10px;'><div id="contenedor_fase_1"></div></div>
					<div id="step-2" style='padding:10px;'><div id="contenedor_fase_2"></div></div>
					<div id="step-3" style='padding:10px;'><div id="contenedor_fase_3"></div></div>
					<div id="step-4" style='padding:10px;'><div id="contenedor_fase_4"></div></div>
					<div id="step-5" style='padding:10px;'><div id="contenedor_fase_5"></div></div>
					<div id="step-6" style='padding:10px;'><div id="contenedor_fase_6"></div></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var competenciaResultado = <?= json_encode($this->competencia_resultado); ?>;
	var unidad = 0; //< ?= $this->competencia_resultado->num_unidad; ? >;
	var ultimoPasoGuardado = <?= $this->competencia_resultado->etapa_actual ?>;
	var nuevo = <?= $this->competencia_resultado->id_competencia == 0 ? 'true' : 'false' ?>;

	var fase1Cargada = false;
	var fase2Cargada = false;
	var fase3Cargada = false;
	var fase4Cargada = false;
	var fase5Cargada = false;
	var fase6Cargada = false;
	var fase7Cargada = false;
	var fase8Cargada = false;
	var fase9Cargada = false;

	function dataCompetencia(){
		return {
			id_competencia_padre 		: competenciaResultado.id_competencia_padre || 0,
			id_competencia_resultado 	: competenciaResultado.id_competencia,
		};
	}

	
	function fase1(){
		if(!fase1Cargada){
			$.ajax({
			    url: '<?= ruta("asistente.fresultado_form1") ?>',
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
		// !fase2Cargada && 
		if(ultimoPasoGuardado>=1){
			$.ajax({
			    url: '<?= ruta("asistente.fresultado_form2") ?>',
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
		// !fase3Cargada && 
		if(ultimoPasoGuardado>=2){
			$.ajax({
			    url: '<?= ruta("asistente.fresultado_form3") ?>',
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

	function fase4(sigTab){
		// !fase4Cargada && 
		if(ultimoPasoGuardado>=3){
			api.get({
				url: '<?= ruta("asistente.fresultado_form4") ?>',
				data: dataCompetencia(),
				success: function(response){
					$('#contenedor_fase_4').html(response);
					if(sigTab){ $('#smartwizard').smartWizard('next'); }
				},
				error: function(){
					$('#contenedor_fase_4').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 4'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 4');
				}
			});
		}
	}

	function fase5(sigTab){
		// !fase5Cargada && 
		if(ultimoPasoGuardado>=4){
			api.get({
				url: '<?= ruta("asistente.fresultado_form5") ?>',
				data: dataCompetencia(),
				success: function(response){
					$('#contenedor_fase_5').html(response);
					if(sigTab){ $('#smartwizard').smartWizard('next'); }
				},
				error: function(){
					$('#contenedor_fase_5').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 5'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 5');
				}
			});
		}
	}

	function fase6(sigTab){
		// !fase6Cargada && 
		if(ultimoPasoGuardado>=5){
			api.get({
				url: '<?= ruta("asistente.fresultado_form6") ?>',
				data: dataCompetencia(),
				success: function(response){
					$('#contenedor_fase_6').html(response);
					if(sigTab){ $('#smartwizard').smartWizard('next'); }
				},
				error: function(){
					$('#contenedor_fase_6').html(errorAlert('Ha ocurrido un error al cargar el formulario de la fase 6'));
			        utils.alert.error('Ha ocurrido un error al cargar el formulario de la fase 6');
				}
			});
		}
	}

	var tabAnterior = function(){
		$('#smartwizard').smartWizard("prev");
	};

	$(function () {
	  $('[data-toggle="popover"]').popover()
	});

	$(document).ready(function(){
		fase1(); // datos generales
		fase2(); // creacion de la competencia
		fase3(); // desglose de componentes
		fase4(); // competencias genericas
		fase5(); // construccion adas
		fase6(); // evidencias y retroalimentacion
		// fase7(); // evidencias y retroalimentacion


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
            selected: !nuevo && ultimoPasoGuardado == 0 ? 1 : Math.min(ultimoPasoGuardado, 5),
            theme: 'arrows',
            keyNavigation: false,
            transitionEffect:'fade',
            enableFinishButton: true,
            useURLhash: false,
            // disabledSteps: [],
            labelNext:'Siguiente', // label for Next button
			labelPrevious:'Anterior', // label for Previous button
			labelFinish:'Finalizar',
			// theme: 'dots',
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