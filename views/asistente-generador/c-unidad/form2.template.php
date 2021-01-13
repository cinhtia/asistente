<div class="container">
	<h4>Fase 2. Creación de la competencia</h4>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<input type="hidden" value="<?= $this->competencia_unidad->id_competencia ?>" name="id_competencia" id="id_competencia">
			<h5 style="margin-bottom:5px;">1. Verbo</h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe un verbo" id="autocomplete_verbo" class="form-control">
				<button id="btn_abrir_lista_completa_verbos" data-item="verbo" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<br>
			<div id="contenedor_lista_verbos_seleccionados">
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">2. Contenido</h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el contenido" id="autocomplete_contenido" class="form-control">
				<button id="btn_abrir_lista_completa_contenidos" data-item="contenido" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<br>
			<div id="contenedor_lista_contenidos_seleccionados">
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">3. Contexto</h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el contexto" id="autocomplete_contexto" class="form-control">
				<button id="btn_abrir_lista_completa_contextos" data-item="contexto" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<br>
			<div id="contenedor_lista_contextos_seleccionados">
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<h5 style="margin-bottom:5px;">4. Criterio</h5>
			<div class="input-group">
				<input type="text" placeholder="Escribe el criterio" id="autocomplete_criterio" class="form-control">
				<button id="btn_abrir_lista_completa_criterios" data-item="criterio" class="btn btn-primary btn-izq-input btn-der-input2 btn_abrir_lista_completa_item">
					<i class="fa fa-angle-down" aria-hidden="true"></i>
				</button>
			</div>
			<br>
			<div id="contenedor_lista_criterios_seleccionados">
				
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			Vista previa
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
				<input type="text" class="form-control" name="competencia_editable" id="competencia_editable" placeholder="Competencia generada">
			</div>
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
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

	var idCompetencia = <?= $this->competencia_unidad->id_competencia ?>;

	console.log(idCompetencia);

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
		return "<div data-id='"+id+"' data-texto='"+texto+"' data-tipo='"+tipo+"' class='alert alert-secondary alert-dismissible' role='alert'>"+texto+"<button class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
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

	function agregarVerbo(item){

		if(existeEnArray('verbo',item)){
			return;
		}

		if( contVerbos.length >= MAX_VERBOS ){
			contVerbos.pop();
		}
		
		contVerbos.push(item);
		var divContenedor = $('#contenedor_lista_verbos_seleccionados');
		divContenedor.html('');
		
		$.each(contVerbos, function(index, item2){
			var html = alertDiv('verbo',item2.id_verbo, item2.value);
			divContenedor.append(html);
		});
		$('#autocomplete_verbo').val('');
		generarVistaPrevia();
	}

	function agregarContenido(item){

		if(existeEnArray('contenido',item)){
			console.log("######## existe contenido:  debug info");
			return;
		}

		if( contContenidos.length >= MAX_CONTENIDOS ){
			contContenidos.pop();
		}
		
		contContenidos.push(item);
		var divContenedor = $('#contenedor_lista_contenidos_seleccionados');
		divContenedor.html('');
		$.each(contContenidos, function(index, item){
			divContenedor.append(alertDiv('contenido',item.id_contenido, item.value));
		});
		$('#autocomplete_contenido').val('');
		generarVistaPrevia();
	}

	function agregarContexto(item){
		if(existeEnArray('contexto',item)){
			return;
		}

		if( contContextos.length >= MAX_CONTEXTOS ){
			contContextos.pop();
		}
		
		contContextos.push(item);
		var divContenedor = $('#contenedor_lista_contextos_seleccionados');
		divContenedor.html('');
		$.each(contContextos, function(index, item){
			divContenedor.append(alertDiv('contexto',item.id_contexto, item.value));
		});
		$('#autocomplete_contexto').val('');
		generarVistaPrevia();
	}

	function agregarCriterio(item){
		if(existeEnArray('criterio',item)){
			return;
		}

		if( contCriterios.length >= MAX_CRITERIOS ){
			contCriterios.pop();
		}
		
		contCriterios.push(item);
		var divContenedor = $('#contenedor_lista_criterios_seleccionados');
		divContenedor.html('');
		$.each(contCriterios, function(index, item){
			divContenedor.append(alertDiv('criterio',item.id_criterio, item.value));
		});
		$('#autocomplete_criterio').val('');
		generarVistaPrevia();
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
		var current = $('#autocomplete_'+elemento).val();
		if(current == null){
			current = "";
		}
		$('#autocomplete_'+elemento).autocomplete("search",current);
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

		inputCompetencia.hide();
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
			inputCompetencia.show();
			inputCompetencia.val(strVerbo+" "+strContenido+" "+strContexto+" "+strCriterio);
		} 

	}

    $(document).ready(function(){

    	generarVistaPrevia();

    	obtenerElementosCompetencia();

    	$('.btn_abrir_lista_completa_item').off('click').on('click', function(){
			dispararAutocomplete($(this).data('item'));
		});

		// $('#btn_continuar_f2').off('click').on('click', function (e){
		// 	e.preventDefault();
		// 	$('#smartwizard').smartWizard("next");
		// });


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

    });
</script>


