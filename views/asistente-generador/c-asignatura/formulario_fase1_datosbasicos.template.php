<?php 
$nuevo = $data->fromBody('nuevo', true);
$pe = $data->fromBody('pe', new TblPlanEstudio());
$competencia = $data->fromBody('competencia', new TblCompetencia());

$form = new BaseForm();
$form->bs4FormSelect('id_pe','Plan de estudio','id_pe',[], $pe->id_pe, true);
$form->bs4FormSelect('id_asignatura_pe','Asignatura','id_asignatura_pe', [], $competencia->id_asignatura_pe, true);
$form->bs4FormTextArea('descripcion','Descripción', 'descripcion', $competencia->descripcion, false);
?>
<div>
	<h4>Fase 1. <?= $nuevo ? 'Creación de la competencia de asignatura' : 'Editar competencia de asignatura'; ?></h4>
	
	<?php if($data->isError()){ ?>
	<div class="alert alert-danger">
		<?= $data->getMsj(); ?>
	</div>
	<?php } ?>

	<?php 

	$form->beginForm('frm_fase1_competencia'); 
	$form->inputHidden('id_competencia','id_competencia',$competencia->id_competencia);

	?>
	<br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h4><strong>Autor:</strong> <?= $nuevo ? $user->nombre : $competencia->Usuario->nombre; ?></h4>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php if(!$nuevo){?>
			<h4><strong>Última actualización: </strong> <?= Helpers::fechaFormalCorta(false, $competencia->fecha_actualizacion); ?></h4>
			<?php } ?>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->render('id_pe'); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->render('id_asignatura_pe'); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->render('descripcion'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="pull-right">
				<button class="btn btn-primary" type='submit'>
					<?= $nuevo ? 'Crear la competencia' : 'Siguiente' ?>  <i class="fa fa-arrow-right"></i> 
				</button>
			</div>
		</div>
	</div>
	<?php $form->endForm(); ?>

</div>

<script type="text/javascript">
	var formFase1 = $('#frm_fase1_competencia');
	var esNuevo = <?= $nuevo ? "true" : "false"; ?>;
	var idPE = <?= $nuevo ? "0" : $competencia->AsignaturaPe->id_pe; ?>;
	var idAsignaturaPe = <?= $nuevo ? "0" : $competencia->AsignaturaPe->id_asignatura_pe; ?>;

	var selectPEs = $('#id_pe');
	var selectAsigPe = $('#id_asignatura_pe');

	function cargarAsignaturas(idPe){
		selectAsigPe.find('option').remove().end().append('<option value="">Selecciona</option>').val('');
		$.ajax({
		    url: '<?= ruta("asignatura.listado_pe") ?>',
		    method: 'get',
		    data: {id_pe: idPe},
		    success: function(response){
				if(response.estado){
					response.extra.forEach(function (item, index){
						var option = $('<option  />').val(item.id_asignatura_pe).text(item.nombre_asignatura);
						if(item.id_asignatura_pe == idAsignaturaPe){
							option.prop('selected', true);
						}
						selectAsigPe.append(option);
					});
				}else{
					errorFase(response.mensaje);
				}
		    },
		    error: function(xhr){
		        console.log(xhr);
		        errorFase("Ha ocurrido un error al solicitar los planes de estudio disponibles.");
		    }
		});
	}

	function cargarPes(){
		$.ajax({
		    url: '<?= ruta("pe.listado_usuario") ?>',
		    method: 'get',
		    success: function(response){
				if(response.estado){
					response.extra.forEach(function (item, index){
						var option = $('<option />').val(item.id_pe).text(item.nombre_pe);
						if(item.id_pe == idPE){
							option.prop('selected', true);
							cargarAsignaturas(idPE);
						}
						selectPEs.append(option);
					});
				}else{
					errorFase(response.mensaje);
				}
		    },
		    error: function(xhr){
		    	console.log(xhr);
		        errorFase("Ha ocurrido un error al solicitar los planes de estudio disponibles.");
		    }
		});
	}

	function guardarFase1(){
		if(formFase1.valid()){
			$.ajax({
			    url: '<?= ruta("asistente.guardar_fase1") ?>',
			    method: 'post',
			    data: formFase1.serialize(),
			    success: function(response){
			    	console.log(response);
					if(response.estado){
						console.log(response.extra.id_competencia);
			    		var idCompetencia = response.extra.id_competencia;
						<?php if($nuevo){ ?>
							window.location.replace("<?= ruta('asistente') ?>?id_competencia="+idCompetencia);
						<?php }else{ ?>
							$('#smartwizard').smartWizard("next");
							errorFase(null);

							// cambios la etapa actual (variable global)
							if(ultimoPasoGuardado < 1){
								ultimoPasoGuardado = 1;
							}
							utils.alert.success('Datos guardados correctamente');
							fase2();
						<?php } ?>
					}else{
						utils.alert.error(response.mensaje);
						// errorFase(response.mensaje);
					}
			    },
			    error: function(xhr){
			    	utils.alert.error("Ha ocurrido un error desconocido");
			        // errorFase("Ha ocurrido un error desconocido");
			    }
			});
		}else{
			utils.alert.warning("Algunos campos no son válidos");
			// errorFase("Algunos campos no son válidos");
		}
	}

    $(document).ready(function(){
    	formFase1.validate({
    		messages: {
    			id_pe: 'Debe seleccionar un plan de estudios',
    			id_asignatura_pe: 'Debe seleccionar una asignatura. Si no aparece, primero seleccione un plan de estudio.'
    		}
    	});

    	cargarPes();

    	if(esNuevo){
    		$('#btn_guardar_cambios_fase').hide();
    	}

    	selectPEs.on('change', function (e){
    		var idPe = this.value;
    		cargarAsignaturas(idPe);
    	});


    	formFase1.on('submit', function (e){
    		e.preventDefault();
    		guardarFase1();
    	});

    });
</script>
