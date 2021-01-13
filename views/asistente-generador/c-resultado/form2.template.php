<div class="container-fluid">
	<button data-toggle="popover" data-html="true" data-trigger="hover" data-container="body" role="button" data-placement="left" data-html="true" data-content="<?= AYUDA_FASES['f1'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 1. Especificación/construcción de la competencia</h4>
	<hr class="mb-25">
	<div class="table-responsive mb-10">
		<table class="table table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>Asignatura</th>
					<th>Unidad</th>
					<th>Contenido(s)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?= $this->asignatura->nombre_asignatura ?></td>
					<td>Unidad <?= $this->unidad->num_unidad ?>. <?= $this->unidad->nombre_unidad ?></td>
					<td>
						<?php foreach ($this->contenidos as $index => $contenido) { ?>
							<ul>
								<li><?= $contenido->detalle_secuencia_contenido ?></li>
							</ul>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<input type="hidden" value="<?= $this->competencia_resultado->id_competencia ?>" name="id_competencia" id="id_competencia">
			<h5 style="margin-bottom:5px;">1. Verbo <span class="badge badge-info" data-toggle="tooltip" data-placement="top" title="<?= AYUDA_FASES['f1_verbo'] ?>">?</span></h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe un verbo" id="autocomplete_verbo" class="form-control">
				<button id="btn_abrir_lista_completa_verbos" data-item="verbo" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<div id="no_result_verbo"></div>
			<br>
			<div id="contenedor_lista_verbos_seleccionados">
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">2. Contenido <span class="badge badge-info" data-toggle="tooltip" data-placement="top" title="<?= AYUDA_FASES['f1_contenido'] ?>">?</span></h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el contenido" id="autocomplete_contenido" class="form-control">
				<button id="btn_abrir_lista_completa_contenidos" data-item="contenido" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<div id="no_result_contenido"></div>
			<br>
			<div id="contenedor_lista_contenidos_seleccionados">
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">3. Contexto <span class="badge badge-info" data-toggle="tooltip" data-placement="top" title="<?= AYUDA_FASES['f1_contexto'] ?>">?</span></h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el contexto" id="autocomplete_contexto" class="form-control">
				<button id="btn_abrir_lista_completa_contextos" data-item="contexto" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<div id="no_result_contexto"></div>
			<br>
			<div id="contenedor_lista_contextos_seleccionados"></div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">4. Criterio <span class="badge badge-info" data-toggle="tooltip" data-placement="top" title="<?= AYUDA_FASES['f1_criterio'] ?>">?</span></h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el criterio" id="autocomplete_criterio" class="form-control">
				<button id="btn_abrir_lista_completa_criterios" data-item="criterio" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<div id="no_result_criterio"></div>
			<br>
			<div id="contenedor_lista_criterios_seleccionados"></div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			Vista previa de la competencia construida, puede editarla en el texto de color negro
		</div>
		<div class="card-body">
			<div id="contenedor_spans">
				<span id="contenedor_verbo" class="text-info"></span> 
				<span id="contenedor_contenido" class="text-success"></span> 
				<span id="contenedor_contexto" class="text-danger"></span> 
				<span id="contenedor_criterio" class="text-primary"></span>
			</div>
			<br>
			<div id="contenedor_input">
				<input type="text" class="form-control" name="competencia_editable" id="competencia_editable" placeholder="Competencia editable">
			</div>
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php if($this->competencia_resultado->etapa_actual==0){ ?>
				<div class="dropdown">
				  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <i class="fa fa-save"></i> Guardar como
				  </button>
				  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
				    <button type="button" onclick="guardarComo('asignatura')" class="dropdown-item">Competencia de asignatura</button>
				    <button type="button" onclick="guardarComo('unidad')" class="dropdown-item">Competencia de unidad</button>
				  </div>
				</div>
			<?php } ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" type='button' id="btn_continuar_f2">
				Siguiente  <i class="fa fa-arrow-right"></i> 
			</button>
		</div>
	</div>
</div>

<script type="text/javascript">

	var no_results = {
		verbo: $('#no_result_verbo'),
		contenido: $('#no_result_contenido'),
		criterio: $('#no_result_criterio'),
		contexto: $('#no_result_contexto')
	};

	// maximos por elemento
	var MAX_VERBOS = 1;
	var MAX_CONTENIDOS = Infinity;
	var MAX_CONTEXTOS = Infinity;
	var MAX_CRITERIOS = Infinity;

	// contenedor de seleccionados
	var contVerbos = [];
	var contContenidos = [];
	var contContextos = [];
	var contCriterios = [];

	var idCompetencia = <?= $this->competencia_resultado->id_competencia ?>;
	
	function intentarGuardar(competenciaEditable, tipo, dialog){
		dialog.btn.disabled();
		dialog.modal.modal('hide');
		utils.modal.remote({
			url: '<?= ruta("competencia2.formulario") ?>',
			data: {
				id_competencia       : idCompetencia,
				tipo                 : tipo,
				competencia_editable : competenciaEditable,
				cambiar_tipo         : 1,
			},
			errorMessage: 'Ha ocurrido un error al abrir el formulario',
			modal_options: {
				titulo: 'Actualizar tipo de competencia',
				size: 'lg'
			}

		});
	}

	function guardarComo(tipo){
		var competenciaEditable = $('#competencia_editable').val();
		if(competenciaEditable){
			utils.modal.confirm({
				titulo    : 'Confirmación',
				contenido : 'Se intentará guardar : <strong>'+competenciaEditable+'</strong> como una competencia de tipo '+tipo+'<br /> oprime aceptar para continuar. <br /> <div class="alert alert-warning"> Esta acción puede no funcionar si ya existe una competencia de '+tipo+' para la asignatura y/o unidad seleccionada</div>',
				type      : 'primary',
				size      : 'md',
				success   : function(dialog){
					intentarGuardar(competenciaEditable, tipo, dialog);
				}
			});
		}else{
			utils.alert.warning('Aún no se redacta la competencia');
		}
	}


	// configuraciones de bootstrap
	$().alert();

	$(document).on('close.bs.alert','.alert', function(){
		var id = $(this).data('id');
		var tipo = $(this).data('tipo');
		var texto = $(this).data('texto');
		
		if(tipo == 'verbo'){
			for(var i=0;i<contVerbos.length;i++){	
				if(contVerbos[i].value==texto && contVerbos[i].id_verbo == id){
					contVerbos.splice(i,1);
					break;
				}
			}
		}else if(tipo == 'contenido'){
			for(var i=0;i<contContenidos.length;i++){	
				if(contContenidos[i].value==texto && contContenidos[i].id_contenido == id){
					contContenidos.splice(i,1);
					break;
				}
			}
		}else if(tipo == 'contexto'){
			for(var i=0;i<contContextos.length;i++){	
				if(contContextos[i].value==texto && contContextos[i].id_contexto == id){
					contContextos.splice(i,1);
					break;
				}
			}
		}else{
			for(var i=0;i<contCriterios.length;i++){	
				if(contCriterios[i].value==texto && contCriterios[i].id_criterio == id){
					contCriterios.splice(i,1);
					break;
				}
			}
		}

		generarVistaPrevia();
	});


	// funciones helpers
	function btnCerrarAlert(tipo, id){
		return "<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
	}

	function alertDiv(tipo, id, texto){
		var btn = btnCerrarAlert(tipo, id);
		return "<div data-id='"+id+"' data-texto='"+texto+"' data-tipo='"+tipo+"' class='alert alert-primary alert-dismissible' role='alert'>"+texto+"<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
	}

	function existeEnArray(tipo, item){

		var tmpArray;
		var prop = 'id_'+tipo;
		if(tipo == 'verbo'){
			tmpArray = contVerbos;
		}else if(tipo == 'contenido'){
			tmpArray = contContenidos;
		}else if(tipo == 'contexto'){
			tmpArray = contContextos;
		}else{
			tmpArray = contCriterios;
		}

		for(var i=0;i<tmpArray.length; i++){
			if(tmpArray[i]['value'] == item['value']){// && tmpArray[i][prop]==item[prop]){

				// en el caso que sea un elemento de listado pero el que ya este
				// listado sea uno sin id, entonces se actualiza
				if(tmpArray[i][prop] == 0){
					tmpArray[i][prop] = item[prop];
				}

				return true;
			}
		}
		return false;
	}

	function obtenerElementosCompetencia(){
		if(idCompetencia != 0){
			$.ajax({
			    url: '<?= ruta("asistente.fase2_elementos") ?>',
			    method: 'get',
			    data: {id_competencia: idCompetencia},
			    success: function(response){
					if(response.estado){

						var verbos = response.extra.verbos;
						var contenidos = response.extra.contenidos;
						var contextos = response.extra.contextos;
						var criterios = response.extra.criterios;

						verbos.forEach(function (item){
							agregarVerbo({id_verbo: item.id_verbo, value: item.descrip_verbo, label: item.descrip_verbo});
						});

						contenidos.forEach(function (item, index){
							agregarContenido({id_contenido: item.id_contenido, value: item.descrip_contenido, label: item.descrip_contenido});
						});

						contextos.forEach(function (item, index){
							agregarContexto({id_contexto: item.id_contexto, value: item.descrip_contexto, label: item.descrip_contexto});

						});

						criterios.forEach(function (item, index){
							agregarCriterio({id_criterio: item.id_criterio, value: item.descrip_criterio, label: item.descrip_criterio});
						});

						if(response.competencia){
							$('#competencia_editable').val(response.competencia.competencia_editable);
						}
					}else{
						utils.alert.warning(response.mensaje);
					}
			    },
			    error: function(xhr){
			        utils.alert.danger('Error al obtener los elementos de la competencia');
			    }
			});

		}
	};


	function agregarVerbo(item){
		console.log(item);
		if(existeEnArray('verbo',item)) return;
		if( contVerbos.length >= MAX_VERBOS ) contVerbos.pop();
		
		contVerbos.push(item);
		var divContenedor = $('#contenedor_lista_verbos_seleccionados');
		divContenedor.html('');
		
		$.each(contVerbos, function(index, item2){
			var html = alertDiv('verbo',item2.id_verbo, item2.value);
			divContenedor.append(html);
		});
		$('#autocomplete_verbo').val(item.value);
		generarVistaPrevia();
	}

	function agregarContenido(item){

		if(existeEnArray('contenido',item))return;
		if( contContenidos.length >= MAX_CONTENIDOS ) contContenidos.pop();
		
		contContenidos.push(item);
		var divContenedor = $('#contenedor_lista_contenidos_seleccionados');
		divContenedor.html('');
		$.each(contContenidos, function(index, item){
			divContenedor.append(alertDiv('contenido',item.id_contenido, item.value));
		});
		$('#autocomplete_contenido').val(item.value);
		generarVistaPrevia();
	}

	function agregarContexto(item){
		if(existeEnArray('contexto',item))return;
		if( contContextos.length >= MAX_CONTEXTOS ) contContextos.pop();
		
		contContextos.push(item);
		var divContenedor = $('#contenedor_lista_contextos_seleccionados');
		divContenedor.html('');
		$.each(contContextos, function(index, item){
			divContenedor.append(alertDiv('contexto',item.id_contexto, item.value));
		});
		$('#autocomplete_contexto').val(item.value);
		generarVistaPrevia();
	}

	function agregarCriterio(item){
		if(existeEnArray('criterio',item))return;
		if( contCriterios.length >= MAX_CRITERIOS )contCriterios.pop();
		
		contCriterios.push(item);
		var divContenedor = $('#contenedor_lista_criterios_seleccionados');
		divContenedor.html('');
		$.each(contCriterios, function(index, item){
			divContenedor.append(alertDiv('criterio',item.id_criterio, item.value));
		});
		$('#autocomplete_criterio').val(item.value);
		generarVistaPrevia();
	}

	function agregarElementoNoEncontrado(name){
		if(name == 'verbo'){
			utils.modal.remote({
				modal_options: {
					titulo: 'Agregar verbo',
					type: 'primary',
				},
				url: '<?= ruta("verbo.nuevo") ?>',
				data: { nombre_verbo: $('#autocomplete_verbo').val() },
				error: function(){
					utils.alert.error('Ha ocurrido un error al abrir el formulario');
				}
			});
		}else if(name == 'contenido'){
			var valor = $('#autocomplete_contenido').val();
			if(valor){
				utils.busy.show('Guardando nuevo contenido, espere por favor');
				api.post({
					url: '<?= ruta("contenido.crear") ?>',
					data: { nombre_contenido: valor },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							ElementoNuevoAgregado('contenido', response.data);
						}else{
							utils.alert.warning(response.mensaje);
						}
						utils.busy.hide();
					},
					error: function(error){
						console.log(error);
						utils.busy.hide();
						utils.alert.error('Ha ocurrido un error al intentar crear el contenido');
					}
				})

			}
		}else if(name == 'contexto'){
			var valor = $('#autocomplete_contexto').val();
			if(valor){
				utils.busy.show('Guardando nuevo contexto, espere por favor');
				api.post({
					url: '<?= ruta("contexto.crear") ?>',
					data: { contexto: valor },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							ElementoNuevoAgregado('contexto', response.data);
						}else{
							utils.alert.warning(response.mensaje);
						}
						utils.busy.hide();
					},
					error: function(error){
						console.log(error);
						utils.busy.hide();
						utils.alert.error('Ha ocurrido un error al intentar crear el contexto');
					}
				})
			}
		}else if(name == 'criterio'){
			var valor = $('#autocomplete_criterio').val();
			if(valor){
				utils.busy.show('Guardando nuevo criterio, espere por favor');
				api.post({
					url: '<?= ruta("criterio.crear") ?>',
					data: { criterio: valor },
					success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							ElementoNuevoAgregado('criterio', response.data);
						}else{
							utils.alert.warning(response.mensaje);
						}
						utils.busy.hide();
					},
					error: function(error){
						console.log(error);
						utils.busy.hide();
						utils.alert.error('Ha ocurrido un error al intentar crear el criterio');
					}
				});
			}
		}
	}

	function ElementoNuevoAgregado(name, objeto) {
		var valor = null;
		if(name == 'verbo'){
			no_results.verbo.html('');
			agregarVerbo({label: objeto.descrip_verbo, value: objeto.descrip_verbo, id_verbo: objeto.id_verbo});
			valor = objeto.descrip_verbo;
		}else if(name == 'contenido'){
			no_results.contenido.html('');
			agregarContenido({label: objeto.descrip_contenido, value: objeto.descrip_contenido, id_contenido: objeto.id_contenido});
			valor =objeto.descrip_contenido;
		}else if(name == 'contexto'){
			no_results.contexto.html('');
			agregarContexto({label: objeto.descrip_contexto, value: objeto.descrip_contexto, id_contexto: objeto.id_contexto});
			valor = objeto.descrip_contexto;
		}else if(name == 'criterio'){
			no_results.criterio.html('');
			agregarCriterio({label: objeto.descrip_criterio, value: objeto.descrip_criterio, id_criterio: objeto.id_criterio});
			valor = objeto.descrip_criterio;
		}

		if(valor){ $('#autocomplete_'+name).val(valor); }
	}

	function generarHtmlNoResultados(name, value) {
		var html = 'El texto '+(value ? ' "<strong>'+value+'</strong>"' : ".")+' no se encuentra en el catálogo de '+name+'s. ';
		html+=' <strong>Puede utilizar otro, ¿O desea añadirlo?</strong> <button type="button" onclick="agregarElementoNoEncontrado(\''+name+'\')" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Añadir</button>';
		// html+=' <strong>¿Deseas agregar al sistema este '+name+' ?</strong> <button type="button" onclick="agregarElementoNoEncontrado(\''+name+'\')" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Agregar</button>';
		return html;
	}

	$('#autocomplete_verbo').autocomplete({
		source: function (request, response){
			$.get('verbos', {
				descrip_verbo: request.term
			}, function (data){
				var verbos = [];
				$.each(data, function(index, item){
					verbos.push({label: item.descrip_verbo, id_verbo: item.id_verbo });
				});
				if(verbos.length == 0){
					no_results.verbo.html(errorAlert( generarHtmlNoResultados('verbo', request.term)));
				}else{
					no_results.verbo.html('');
				}

				response(verbos);
			});
		},
		select: function(event, object){
			event.preventDefault();
			agregarVerbo(object.item);
			$('#autocomplete_contenido').focus();
		},
		minLength: 0
	});

	$('#autocomplete_contenido').autocomplete({
		source: function(request, response){
			$.get('contenidos', {
				descrip_contenido: request.term
			}, function(data){
				var contenidos = [];
				$.each(data, function(index, item){
					contenidos.push({label: item.descrip_contenido, id_contenido: item.id_contenido});
				});
				if(contenidos.length == 0){
					no_results.contenido.html(errorAlert(generarHtmlNoResultados('contenido',request.term )));
				}else{
					no_results.contenido.html('');
				}
				response(contenidos);
			});
		},
		select: function (event, object){
			event.preventDefault();
			agregarContenido(object.item);
			$('#autocomplete_contexto').focus();
		},
		minLength: 0
	});

	$('#autocomplete_contexto').autocomplete({
		source: function (request, response){
			$.get('contextos', {
				descrip_contexto: request.term
			}, function(data){
				var contextos = [];
				$.each(data, function(index, item){
					contextos.push({label: item.descrip_contexto, id_contexto: item.id_contexto});
				});
				if(contextos.length == 0){
					no_results.contexto.html(errorAlert(generarHtmlNoResultados('contexto', request.term)));
				}else{
					no_results.contexto.html('');
				}
				response(contextos);
			});
		},
		select: function(event, object){
			event.preventDefault();
			agregarContexto(object.item);
			$('#autocomplete_criterio').focus();
		},
		minLength: 0
	});
	
	$('#autocomplete_criterio').autocomplete({
		source: function (request, response){
			$.get('criterios', {
				descrip_criterio: request.term
			}, function(data){
				var criterios = [];
				$.each(data, function(index, item){
					criterios.push({label: item.descrip_criterio, id_criterio: item.id_criterio});
				});
				if(criterios.length == 0){
					no_results.criterio.html(errorAlert(generarHtmlNoResultados('criterio', request.term)));
				}else{
					no_results.criterio.html('');
				}
				response(criterios);
			});
		},
		select: function(event, object){
			event.preventDefault();
			agregarCriterio(object.item);
		},
		minLength: 0
	});

	var dispararAutocomplete = function (elemento){
		$('#autocomplete_'+elemento).autocomplete("search", $('#autocomplete_'+elemento).val());
	};

	function generarVistaPrevia(){

		var inputCompetencia = $('#competencia_editable');

		var strVerbo = "";
		var i = 0;
		$.each(contVerbos, function(index, item){
			if(i>0){
				strVerbo += " y ";
			}
			strVerbo += item.value;
			i++;
		});

		// inputCompetencia.hide();
		$('#contenedor_verbo').html(strVerbo);

		var strContenido =  "";
		var i = 0;
		$.each(contContenidos, function(index, item){
			if(i>0){
				strContenido += " y ";
			}
			strContenido += item.value;
			i++;
		});
		$('#contenedor_contenido').html(strContenido);

		var strContexto = "";
		var i = 0;
		$.each(contContextos, function(index, item){
			if(i>0){
				strContexto += " y ";
			}
			strContexto += item.value;
			i++;
		});
		$('#contenedor_contexto').html(strContexto);


		var strCriterio = "";
		var i = 0;
		$.each(contCriterios, function(index, item){
			
			if(i>0){
				strCriterio += " y ";
			}
			strCriterio += item.value;
			i++;
		});
		$('#contenedor_criterio').html(strCriterio);

		if(strVerbo != "" || strContenido != "" || strContexto != "" || strCriterio != ""){
			// inputCompetencia.show();
			inputCompetencia.val(strVerbo+" "+strContenido+" "+strContexto+" "+strCriterio);
		} 

	}

	var agregarElemento = function(item){
		var val = $('#autocomplete_'+item).val().trim();
		if(val == "")return;
		utils.alert.warning('Debes seleccionar un '+item+' del listado obtenido');
		dispararAutocomplete(item);
	}

	var keyEnter = function (e){
		if(e.which == 13){
			var id = $(this).prop('id');
			var opt = id.split("_").pop();
			agregarElemento(opt);
		}
	};

    $(document).ready(function(){

    	generarVistaPrevia();

    	obtenerElementosCompetencia();

    	$('.btn_abrir_lista_completa_item').off('click').on('click', function(){
    		var name = $(this).data('item');
    		$('#autocomplete_'+name).val('');
			dispararAutocomplete(name);
		});

		$('#btn_continuar_f2').off('click').on('click', function(){
			var strCompetenciaEditable = $('#competencia_editable').val();
			var btn = $(this);
			if(contVerbos.length == 0 || contContenidos.length == 0 || contContextos.length == 0 || contCriterios.length == 0 || strCompetenciaEditable.trim() == ""){
				utils.alert.warning('Algunos campos tienen valores no válidos');
				
			}else{

				// enviamos los datos al servidor
				var idCompetencia = $('#id_competencia').val();
				var verbosSel = [];
				contVerbos.forEach(function (item){
					verbosSel.push({id_verbo: item.id_verbo, value: item.value});
				});

				var contenidosSel = [];
				contContenidos.forEach(function (item){
					contenidosSel.push({id_contenido: item.id_contenido, value: item.value});
				});

				var contextosSel = [];
				contContextos.forEach(function (item){
					contextosSel.push({id_contexto: item.id_contexto, value: item.value});
				});

				var criteriosSel = [];
				contCriterios.forEach(function (item){
					criteriosSel.push({id_criterio: item.id_criterio, value: item.value});
				});

				var data = {
					id_competencia: idCompetencia,
					verbos: verbosSel,
					contenidos: contenidosSel,
					contextos: contextosSel,
					criterios: criteriosSel,
					competencia_editable: strCompetenciaEditable
				};

				loading(btn,"Guardando...");

				$.ajax({
					url: "<?= ruta('asistente.guardar_fase2') ?>",
					method: 'post',
					data: data,
					success: function(response){
						notLoading(btn, '<i class="fa fa-save"></i> Guardar competencia');
						
						if(response.estado){
							utils.alert.success(response.mensaje);

							if(ultimoPasoGuardado < 2){
								ultimoPasoGuardado = 2;
							}

							fase3(true);

							// $('#smartwizard').smartWizard("next");
						}else{
							utils.alert.warning(response.mensaje);
						}

					}, error: function(xhr){
						console.log(xhr);
						utils.alert.danger('Ha ocurridoun error al solicitar el guardado');
						// utils.modal.confirm({
						// 	titulo: 'Error',
						// 	contenido: 'Ha ocurridoun error al solicitar el guardado',
						// 	type: 'danger',
						// 	size: 'sm'
						// });
					}
				});

			}
		});

		// agregamos eventos on key press a autocompletes
		$('#autocomplete_verbo').keypress(keyEnter);
		$('#autocomplete_contenido').keypress(keyEnter);
		$('#autocomplete_contexto').keypress(keyEnter);
		$('#autocomplete_criterio').keypress(keyEnter);

    });
</script>


