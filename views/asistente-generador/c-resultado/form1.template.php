<div class="container-fluid">
	<button data-toggle="popover" data-trigger="hover" data-html="true" data-container="body" role="button" data-placement="left" data-content="<?= AYUDA_FASES['f0'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 0. Información general</h4>
	<hr class="mb-25">

	<form id="frm_fase1">		
		<input type="hidden" value="<?= $this->competencia_resultado->id_competencia_padre ?>" name="id_competencia_padre">
		<input type="hidden" value="<?= $this->competencia_resultado->id_competencia; ?>" name="id_competencia_resultado">
		<input type="hidden" value="<?= $this->competencia_resultado->num_resultado; ?>" name="num_resultado">
		
		<div id="alerta_asignatura_unidad_competencia_existente" class="alert alert-info">
			<strong>Información: </strong> Existen evaluaciones en proceso para la asignatura y unidad seleccionada.
		</div>
		<div id="alert_unidad_no_valida" class="alert alert-warning hidden">
			<strong>Alerta</strong> La unidad seleccionada no ha sido configurada.
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<!-- id asignatura pe -->
				<div class="form-group">
				    <label>Asignatura <span class="text-danger">*</span></label> 
				    <select onchange="obtenerUnidades()" name="id_asignatura" id="id_asignatura" required="true" class="form-control">
						<option value="">Seleccione una asignatura</option>
						<?php foreach ($this->asignaturas as $index => $asignatura) {?>
						<option <?= $asignatura['id_asignatura'] == $this->competencia_resultado->id_asignatura ? 'selected' : ''; ?> value="<?= $asignatura['id_asignatura'] ?>"><?= $asignatura['nombre_asignatura'] ?></option>
						<?php } ?>
				    </select>
				</div>
				<div id="contenedor_competencia_asignatura_sel" class="d-none form-group">
					<label for="str_competencia_asignatura_sel">Competencia de la asignatura <!-- <span class="text-danger">*</span> --></label>
					<textarea disabled placeholder="Ingresa la competencia de la asignatura" required="true" name="str_competencia_asignatura_sel" id="str_competencia_asignatura_sel" rows="3" class="form-control"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<!-- num unidad -->
				<div class="form-group">
				    <label>Unidad <span class="text-danger">*</span></label> 
				    <select name="num_unidad" onchange="validar_unidad()" id="num_unidad" required="true" class="form-control">
						<option value="">Seleccione una unidad</option>
				    </select>
				</div>
				<div id="contenedor_competencia_unidad_sel" class="d-none form-group">
					<label for="str_competencia_unidad_sel">Competencia de la unidad <!-- <span class="text-danger">*</span> --></label>
					<textarea disabled name="str_competencia_unidad_sel" id="str_competencia_unidad_sel" rows="3" class="form-control" required="true" placeholder="Ingresa la competencia de la unidad"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<label>Contenidos de la unidad a evaluar <button class="btn btn-sm btn-outline-primary" type="button" onclick="abrirModalUnidad()"><i class="fa fa-plus"></i></button> </label>
				<div id="grupo_contenidos_unidad_mensaje">Seleccione una unidad</div>
				<div id="grupo_contenidos_unidad_listado"></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="descripcion">Descripción (opcional)</label>
					<textarea placeholder="Descripción de esta nueva evaluación (opcional)" name="descripcion" class="form-control" id="descripcion" rows="2"><?= $this->competencia_resultado->descripcion; ?></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
				<button id="btn_siguiente_form1" class="btn btn-primary" type='submit'>
					Siguiente  <i class="fa fa-arrow-right"></i> 
				</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	var competenciaResultado = <?= json_encode($this->competencia_resultado) ?>;
	var contenidosUnidad = <?= json_encode($this->contenidos_unidad); ?>;
	var asignaturas = <?= json_encode($this->asignaturas); ?>;
	var unidades_asignatura = [];
	var selectUnidades = $('#num_unidad');

	$('#alert_unidad_no_valida').hide();
	$('#alerta_asignatura_unidad_competencia_existente').hide();

	function obtenerCompetenciaAsignatura(){
		var idAsignatura = $('#id_asignatura').val();
		unidades_asignatura = [];
		selectUnidades.val('');
		$('#grupo_contenidos_unidad_mensaje').html('Selecciona una unidad');
		$('#grupo_contenidos_unidad_listado').html('');
		$('#alert_unidad_no_valida').hide();

		if(idAsignatura){
			api.get({
				url: "<?= ruta('shared.competencia_asignatura') ?>",
				data: { id_asignatura: idAsignatura },
				success: function(response){
					$('#contenedor_competencia_asignatura_sel').removeClass('d-none');
					$('#str_competencia_asignatura_sel').val( response.estado ? response.data.competencia_editable || '' : '');
					if(!response.estado){
						utils.alert.error(response.mensaje);
					}
					obtenerCompetenciaUnidad();
				}, 
				error: function(){
					utils.alert.error('Ha ocurrido un error al obtener la competencia de la asignatura');
					$('#contenedor_competencia_asignatura_sel').removeClass('d-none');
					$('#str_competencia_asignatura_sel').val('');
					obtenerCompetenciaUnidad();
				}
			});
		}else{
			$('#contenedor_competencia_asignatura_sel').addClass('d-none');
			$('#str_competencia_asignatura_sel').val('');
			obtenerCompetenciaUnidad();
		}
	};

	function abrirModalUnidad() {
		var idAsignatura = $('#id_asignatura').val();
		var numUnidad = selectUnidades.val();
		var unidad = unidades_asignatura.find(function(item){ return item.id == numUnidad; });
		if(idAsignatura && unidad ){
			utils.modal.remote({
				url: '<?= ruta("cont_unidad.formulario") ?>',
				data: { id_unidad_asignatura: unidad.id_unidad_asignatura, desde_asistente: 1 },
				modal_options: {
					size: 'lg',
					type: 'primary',
					titulo: 'Agregar contenido a unidad '+numUnidad
				},
			});
		}else{
			utils.alert.info('Seleccione una asignatura y unidad por favor');
		}
	}

	function obtenerContenidosUnidad(utilizarArrayGlobal) {
	
		var seleccionados_previos = [];
		$('input[type="checkbox"].ch-sel-contenido:checked').each(function(){
			seleccionados_previos.push($(this).val());
		});

		$('#grupo_contenidos_unidad_listado').addClass('d-none');
		$('#grupo_contenidos_unidad_listado').html('');

		var mensaje = 'Debe seleccionar una asignatura y una unidad';

		var idAsignatura = $('#id_asignatura').val();
		var numUnidad = selectUnidades.val();
		var unidad = unidades_asignatura.find(function(item){ return item.id == numUnidad; });

		if(idAsignatura && numUnidad){
			if(unidad){
				api.get({
					url: '<?= ruta("shared.contenido_unidad") ?>',
					data: { id_asignatura: idAsignatura, num_unidad: numUnidad, id_unidad_asignatura: unidad.id_unidad_asignatura },
					success: function(response){
						
						

						var objs = [];
						response.data.forEach(function (contenido, index){

							var childrens = [
								{
									name: 'label',
									props: { for: 'check_contenido_'+index, class: 'cont-ch-normal' },
									childrens: [
										{
											name: 'input',
											props: {
												type: 'checkbox',
												class: 'ch-sel-contenido',
												id: 'check_contenido_'+index,
												name: 'contenidos_unidad[]',
												value: contenido.id_contenido_unidad_asignatura,
											},
										},
										{
											name: 'span',
											props: {class: 'checkmark'},
										},
										{
											content: numUnidad+'.'+(index+1)+' - '+contenido.detalle_secuencia_contenido || '-',
										}
									]
								},
							];

							if(seleccionados_previos.indexOf(contenido.id_contenido_unidad_asignatura) != -1){
								childrens[0].childrens[0].props.checked = true;
							}else if(utilizarArrayGlobal){
								var found = contenidosUnidad.find(function(item){ return item.id_contenido_unidad == contenido.id_contenido_unidad_asignatura; });
								if(found){
									childrens[0].childrens[0].props.checked = true;
								}
							}

							var htmlElementos = utils.tag('div', null, {class: 'form-group'}, childrens);
							objs.push(htmlElementos)
						});

						mensaje = objs.length > 0 ? '' : utils.tag('div', 'La unidad no tiene contenidos agregados', {class: 'alert alert-warning'}, 
							// [ { name: 'button', props: { type: 'button', class: 'btn btn-outline-warning btn-sm', onclick:"abrirModalUnidad()"}, content: '<i class="fa fa-plus"></i> Agregar' } ]
							);
						$('#grupo_contenidos_unidad_mensaje').html(mensaje)
						$('#grupo_contenidos_unidad_listado').html(objs.join(' '));
						if(objs.length > 0){
							$('#grupo_contenidos_unidad_listado').removeClass('d-none');
						}
					},
					error: function(){
						mensaje = utils.tag('div', 'Ha ocurrido un error al obtener los contenidos de esta unidad' ,{class: 'alert alert-danger'}, [
								{ name: 'button', content: '<i class="fa fa-refresh"></i> Reintentar', props: {onclick: 'obtenerContenidosUnidad()', class:'btn btn-sm btn-outline-secondary'} }
						]);
						$('#grupo_contenidos_unidad_mensaje').html(mensaje)
					}
				});
			}else{
				mensaje = utils.tag('div', 'La unidad no ha sido configurada', {class: 'alert alert-warning'});
				$('#grupo_contenidos_unidad_mensaje').html(mensaje)
			}
		}
	}

	function obtenerUnidades(unidadSel){
		obtenerCompetenciaAsignatura(); // obtenemos tambien la competencia de la asignatura
		var idA = $('#id_asignatura').val();
		var us = [{id: '',label:'Seleccione una unidad'}];
		api.get({
			url: '<?= ruta("shared.asignatura_unidad") ?>',
			data: { asignatura_id: idA },
			success: function(response){
				if(response.estado){
					unidades_asignatura = response.extra;
					us = us.concat(response.extra);
				}else{
					utils.alert.error(response.mensaje);
				}
				selectUnidades.fill(us);
				if(unidadSel){
					selectUnidades.val(unidadSel)
					obtenerContenidosUnidad(true);
				};
			},
			error: function(){
				selectUnidades.fill(us);
				utils.alert.error('Ha ocurrido un error al obtener las unidades de la asignatura');
			}
		});
	}

	function validar_unidad() {
		var num_unidad = selectUnidades.val();
		$('#alert_unidad_no_valida').hide();
		$('#btn_siguiente_form1').prop('disabled',false);
		$('#contenedor_competencia_unidad_sel').addClass('d-none');
		$('#str_competencia_unidad_sel').val('');
		$('#grupo_contenidos_unidad_listado').addClass('d-none');
		$('#grupo_contenidos_unidad_listado').html('');

		if(num_unidad){
			var unidad = unidades_asignatura.find(function(item){ return item.id == num_unidad; });
			if(unidad){
				if(!unidad.id_unidad_asignatura){
					$('#alert_unidad_no_valida').show();
					$('#btn_siguiente_form1').prop('disabled',true);
				}else{
					obtenerCompetenciaUnidad();
					validarExistenciaCompetenciaResultado();
					obtenerContenidosUnidad();
				}
			}
		}
	};

	function verCompetenciasResultadoSimilares() {
		var id_asignatura = $('#id_asignatura').val();
		var num_unidad = selectUnidades.val();
		if(id_asignatura && num_unidad){
			utils.modal.remote({
				url: '<?= ruta("asistente.fresultado_resultados_unidad") ?>',
				data: { id_asignatura: id_asignatura, num_unidad: num_unidad },
				modal_options: {
					size: 'lg',
					type: 'primary',
					titulo: 'Competencias existentes de la unidad seleccionada'
				}
			});
		}
	};

	function validarExistenciaCompetenciaResultado() {
		var id_asignatura = $('#id_asignatura').val();
		var unidad_asignatura = selectUnidades.val();
		$('#alerta_asignatura_unidad_competencia_existente').hide();

		if(id_asignatura && unidad_asignatura){
			api.get({
				url: '<?= ruta("shared.existe_competencia_resultado") ?>',
				data: { id_asignatura: id_asignatura, num_unidad: unidad_asignatura },
				success: function(response){
					if(response.estado){
						var conteo = response.extra;
						if(conteo > 0){
							$('#alerta_asignatura_unidad_competencia_existente').show();
							var msj = '<strong>Información</strong> Existen '+conteo+' evaluaciones en proceso para la asignatura y unidad seleccionada.';

							$('#alerta_asignatura_unidad_competencia_existente').html(
								utils.tag('div', msj, null, [
									{ name: 'button', content: '<i class="fa fa-eye"></i> Ver', props: { class: 'btn btn-sm btn-outline-info', type: 'button', onclick:'verCompetenciasResultadoSimilares()' } }
								])
							);
						}
					}else{
						utils.alert.error(response.mensaje);
					}
				},
				error: function(){
					utils.alert.error('No se ha encontrado la competencia de la unidad asociada');
				}
			})
		}
	}

	function obtenerCompetenciaUnidad(unidadOpt) {
		var id_asignatura = $('#id_asignatura').val();
		var unidad_asignatura = selectUnidades.val() || unidadOpt || undefined;
		// $('#descripcion').prop('disabled', true);
		if(id_asignatura && unidad_asignatura){
			api.get({
				url: '<?= ruta("shared.competencia_unidad") ?>',
				data: { id_asignatura: id_asignatura, num_unidad: unidad_asignatura },
				success: function(response){
					// if(response.estado){
					// 	$('#descripcion').val(response.extra);
					// 	$('#descripcion').prop('disabled', true);
					// }else{
					// 	utils.alert.error(response.mensaje);
					// }
					
					if(!response.estado){
						utils.alert.warning(response.mensaje || response.error || 'Ha ocurrido un error al obtener la competencia de la unidad');
					}

					$('#contenedor_competencia_unidad_sel').removeClass('d-none');
					$('#str_competencia_unidad_sel').val(response.estado ? response.extra : '');
				},
				error: function(){
					utils.alert.error('No se ha encontrado la competencia de unidad asociada');
					$('#contenedor_competencia_unidad_sel').removeClass('d-none');
					$('#str_competencia_unidad_sel').val('');
				}
			})
		}else{
			// $('#descripcion').val('');
			$('#contenedor_competencia_unidad_sel').addClass('d-none');
			$('#str_competencia_unidad_sel').val('');
		}
	}
	
    $(document).ready(function(){
    	if(competenciaResultado.num_unidad){
    		obtenerUnidades(competenciaResultado.num_unidad);
    		obtenerCompetenciaUnidad(competenciaResultado.num_unidad);
    	}
		
    	var form = $('#frm_fase1');
    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			id_pe: 'Debe indicar un plan de estudios',
    			id_asignatura_pe: 'Debe seleccionar una asignatura del plan de estudios',
    			num_unidad: 'Debe indicar un número de unidad',
    		}
    	});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				api.post({
					url: '<?= ruta("asistente.fresultado_guardarf1") ?>',
				    data: form.serialize(),
				    cb: function(response){
						if(response.estado){
							if(competenciaResultado.id_competencia == 0){
								window.location.replace("<?= ruta('asistente.fresultado')?>?id_competencia_resultado="+response.extra.id_competencia);
							}else{
								competenciaResultado = response.extra;
								ultimoPasoGuardado = competenciaResultado.etapa_actual;
								utils.alert.success(response.mensaje);
								fase2(true);
							}
						}else{
							utils.alert.danger(response.mensaje);
						}
				    },
				    errorMessage: 'Ha ocurrido un error al intentar guardar la fase 1'
				});
			}
		});
    });
</script>