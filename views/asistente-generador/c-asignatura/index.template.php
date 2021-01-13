<?php 
$competencia = $data->fromBody('competencia', new TblCompetencia());
$nuevo = $data->fromBody('nuevo', true);
 ?>
<div class="container-fluid">
	<div class="container" id="contenedor_mensajes_alerta"></div>
	<div id="smartwizard" style='margin: 10px;'>
		<!-- Nombres de secciones -->
		<ul>
			<li><a href="#step-1">Fase 1 <br><small>Datos básicos</small></a></li>
			<li><a href="#step-2">Fase 2 <br><small>Creación de competencia</small></a></li>
			<li><a href="#step-3">Fase 3 <br><small>Desglose componentes</small></a></li>
			<li><a href="#step-4">Fase 4 <br><small>Selección de CGs</small></a></li>
		</ul>

		<!-- contenido de cada elemento -->
		<div>
			<div id="step-1" style='padding:10px;'><div id="contenedor_fase_1"></div></div>
			<div id="step-2" style='padding:10px;'><div id="contenedor_fase_2"></div></div>
			<div id="step-3" style='padding:10px;'><div id="contenedor_fase_3"></div></div>
			<div id="step-4" style='padding:10px;'><div id="contenedor_fase_4"></div></div>	
		</div>

	</div>
	
</div>
<br><br>
<!-- funciones y variables genericas a utilizar -->
<script type="text/javascript" src="assets/js/asistente.js"></script>

<!-- funciones y variables necesarias para soportar la reutilizacion de esta plantilla para edición -->
<script type="text/javascript">
	var nuevo = <?= $nuevo ? 'true' : 'false' ?>;
	var vistaFase1Cargada = false;
	var vistaFase2Cargada = false;
	var vistaFase3Cargada = false;
	var vistaFase4Cargada = false;
	
	var contenedorFase1 = $('#contenedor_fase_1');
	var contenedorFase2 = $('#contenedor_fase_2');
	var contenedorFase3 = $('#contenedor_fase_3');
	var contenedorFase4 = $('#contenedor_fase_4');

	var pasosDeshabilitados = [];// nuevo ? [1,2,3,4] : [];
	var ultimoPasoGuardado = nuevo ? 0 : <?= $competencia->etapa_actual ? $competencia->etapa_actual : 0 ; ?>;
	var contenedorAlerta = $('#contenedor_mensajes_alerta');
	var idCompetencia = <?= $nuevo ? '0' : $competencia->id_competencia ?>;

	function errorFase(msj){
		if(msj != null){
			contenedorAlerta.html(errorAlert(msj));
		}else{
			contenedorAlerta.html('');
		}
	}

	function infoFase(msj){
		if(msj != null){
			contenedorAlerta.html(infoAlert(msj));
		}else{
			contenedorAlerta.html('');
		}
	}

	function fase1(){
		if(vistaFase1Cargada){
			return;
		}

		vistaFase1Cargada = true;
		cargarFase1(idCompetencia != 0 ? idCompetencia : null, function (error, response){
			if(!error){
				contenedorFase1.html(response);
			}else{
				contenedorFase1.html(errorAlert('Ocurrió un error y no se pudo cargar el formulario de la fase 1'));
			}
		});
	}

	function fase2(){
		if(vistaFase2Cargada || (ultimoPasoGuardado < 1 && nuevo)){
			return;
		}

		vistaFase2Cargada = true;
		cargarFase2(idCompetencia != 0 ? idCompetencia : null, function (error, response){
			if(!error){
				contenedorFase2.html(response);
			}else{
				contenedorFase2.html(errorAlert('Ocurrió un error y no se pudo cargar el formulario de la fase 2'));
			}
		});
	}

	function fase3(){
		// vistaFase3Cargada || 
		if(ultimoPasoGuardado < 2){
			return;
		}

		vistaFase3Cargada = true;
		cargarFase3(idCompetencia != 0 ? idCompetencia : null, function (error, response){
			if(!error){
				contenedorFase3.html(response);
			}else{
				contenedorFase3.html(errorAlert('Ocurrió un error y no se pudo cargar el formulario de la fase 3'));
			}
		});
	}

	function fase4(){
		if(vistaFase4Cargada || ultimoPasoGuardado < 3){
			return;
		}

		vistaFase4Cargada = true;
		cargarFase4(idCompetencia != 0 ? idCompetencia : null, function (error, response){
			if(!error){
				contenedorFase4.html(response);
			}else{
				contenedorFase4.html(errorAlert('Ocurrió un error y no se pudo cargar el formulario de la fase 4'));
			}
		});
	}

	var tabAnterior = function(){
		$('#smartwizard').smartWizard("prev");
	};

	$(document).ready(function(){
		fase1();
		fase2();
		fase3();
		fase4();

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

	       if(ultimoPasoGuardado >= stepNumber ){
	       		if(stepNumber == 2){
	       			fase2();
	       		}else if(stepNumber == 3){
	       			fase3();
	       		}
	       }
	    });

	    if(!nuevo && ultimoPasoGuardado == 0 && !vistaFase2Cargada){
	    	fase2();
	    }

	    // Smart Wizard
	    $('#smartwizard').smartWizard({
	            selected: !nuevo && ultimoPasoGuardado == 0 ? 1 : Math.min(ultimoPasoGuardado, 3),
	            theme: 'arrows',
	            keyNavigation: false,
	            transitionEffect:'fade',
	            enableFinishButton: true,
	            disabledSteps: pasosDeshabilitados,
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

    	$('.popover-dismiss').popover({
          trigger: 'focus'
        });

	});

</script>