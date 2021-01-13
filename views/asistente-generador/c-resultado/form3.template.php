<div class="container-fluid">
	<button data-toggle="popover" data-trigger="hover" data-container="body" role="button" data-placement="left" data-html="true" data-content="<?= AYUDA_FASES['f2'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 2. Generación de los componentes</h4>
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
	
	<div style="margin: 10px 30px 5px 10px;" class="text-center">
		<p>Competencia creada</p>
		<h5 id="contenedor_competencia_generada" class="text-primary">
			<strong><?= $this->competencia_resultado->competencia_editable; ?></strong>
		</h5>
	</div>
	<div class="hidden-xs hidden-sm">
		<div style="width: 5px; background-color: #777777; margin: 10px auto 0px auto; height: 30px;"></div>
		<div style="width: 70%; background-color: #777777; margin-left: 15%; height: 3px;"></div>
		<div style="width: 70%; background-color: #777777; margin-top: 2px; margin-bottom:2px; margin-left: 15%; height: 1px;"></div>
		<div style=" width: 70%; margin-left: 15%;  transform: translate(0px, -8px);">
			<div style="width: 5px; height: 40px; background-color: #777777; display:inline-block;"></div>
			<div class="text-center" style="position: absolute; width: 100%; transform: translate(0px, -40px);" ><div style="margin: 0px auto; width: 5px; height: 40px; background-color: #777777; display:inline-block;"></div></div>
			<div style="position: absolute; right: 0px; width: 5px; height: 40px; background-color: #777777; display:inline-block;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div class="text-center"><strong><span id="total_conocimientos">0</span> Conocimientos</strong></div>
		    <table class="table table-hover">
		    	<tbody id="f3_contenedor_conocimientos">
		    	</tbody>
		    </table>
		    <div class="form-group">
		    	<div class="input-group">
		    		<input type="text" placeholder="Crear/buscar un conocimiento aqui" id="input_conocimiento" class="form-control">
		    		<span class="input-group-append">
		    			<button id="btn_add_conocimiento" class="btn btn-primary" data2-toggle="tooltip" data2-placement="top" title2="Agregar">
		    				<i class="fa fa-angle-down"></i>
		    			</button>
		    		</span>
		    	</div>
		    </div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div class="text-center"><strong><span id="total_habilidades">0</span> Habilidades</strong></div>
			<table class="table table-hover">
				<tbody id="f3_contenedor_habilidades"></tbody>
			</table>
			<div class="form-group">
				<div class="input-group">
					<input type="text" placeholder="Crear/buscar una habilidad aqui" id="input_habilidad" class="form-control">
					<span class="input-group-append">
						<button id="btn_add_habilidad" class="btn btn-primary" data2-toggle="tooltip" data2-placement="top" title2="Agregar">
							<i class="fa fa-angle-down"></i>
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div class="text-center"><strong><span id="total_avs">0</span> Actitudes y valores</strong></div>
			<table class="table table-hover">
				<tbody id="f3_contenedor_av"></tbody>
			</table>
			<div class="form-group">
				<div class="input-group">
					<input type="text" placeholder="Crear/buscar una actitud o valor aqui" id="input_av" class="form-control">
					<span class="input-group-append">
						<button id="btn_add_av" class="btn btn-primary" data2-toggle="tooltip" data2-placement="top" title2="Agregar">
							<i class="fa fa-angle-down"></i>
						</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<br><br>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" type='button' id="btn_guardar_competencia_fase3">
				Siguiente  <i class="fa fa-arrow-right"></i> 
			</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	var conocimientoObj = {
		table: $('#f3_contenedor_conocimientos'),
		btnAdd 	: $('#btn_add_conocimiento'),
		input 	: $('#input_conocimiento')
	};

	var habilidadObj = {
		table: $('#f3_contenedor_habilidades'),
		btnAdd 	: $('#btn_add_habilidad'),
		input 	: $('#input_habilidad')
	};

	var avObj = {
		table: $('#f3_contenedor_av'),
		btnAdd 	: $('#btn_add_av'),
		input 	: $('#input_av')
	};

	var idCompetencia = <?= $this->competencia_resultado->id_competencia ?>;
	var conocimientos = [];
	var habilidades = [];
	var actitudesValores = [];

	function ComponenteCreado(tipo, objeto) {
		
	};

	$('#input_conocimiento').autocomplete({
		source: function (request, response){
			api.get({
				url: '<?= ruta("shared.conocimiento") ?>',
				data: {
					term: request.term,
					notids: conocimientos.map(function(item){ return item.id; }),
				},
				success: function(apiResp){
					var listado = [];
					if(apiResp.estado){
						listado = apiResp.data.map(function(item){
							return {
								id: item.id_compo_conocimiento,
								label: item.descrip_conocimiento
							};
						});
					}else{
						// utils.alert.warning(apiResp.mensaje);
						console.log(apiResp.mensaje);
					}
					response(listado);
				},
				error: function(){
					// utils.warning('Ha ocurrido un error al intentar obtener los conocimientos similares');
					console.log('Ha ocurrido un error al intentar obtener los conocimientos similares');
					response([]);
				}
			});
		},
		select: function(event, object){
			event.preventDefault();
			$('#input_conocimiento').val('');
			var conocimiento = object.item;
			agregarF3('conocimientos', conocimiento);
		},
		minLength: 0
	});

	$('#input_habilidad').autocomplete({
		source: function (request, response){
			api.get({
				url: '<?= ruta("shared.habilidad") ?>',
				data: {
					term: request.term,
					notids: habilidades.map(function(item){ return item.id; }),
				},
				success: function(apiResp){
					var listado = [];
					if(apiResp.estado){
						listado = apiResp.data.map(function(item){
							return {
								id: item.id_compo_habilidad,
								label: item.descrip_habilidad
							};
						});
					}else{
						// utils.alert.warning(apiResp.mensaje);
						console.log(apiResp.mensaje);
					}
					response(listado);
				},
				error: function(){
					console.log('Ha ocurrido un error al intentar obtener las habilidades similares');
					response([]);
				}
			});
		},
		select: function(event, object){
			event.preventDefault();
			$('#input_habilidad').val('');
			var hab = object.item;
			agregarF3('habilidades', hab);
		},
		minLength: 0
	});

	$('#input_av').autocomplete({
		source: function (request, response){
			api.get({
				url: '<?= ruta("shared.actitud") ?>',
				data: {
					term: request.term,
					notids: actitudesValores.map(function(item){ return item.id; }),
				},
				success: function(apiResp){
					var listado = [];
					if(apiResp.estado){
						listado = apiResp.data.map(function(item){
							return {
								id: item.id_compo_valor,
								label: item.descrip_actitud_valor,
							};
						});
					}else{
						// utils.alert.warning(apiResp.mensaje);
						console.log(apiResp.mensaje);
					}
					response(listado);
				},
				error: function(){
					console.log('Ha ocurrido un error al intentar obtener las actitudes o valores similares');
					response([]);
				}
			});
		},
		select: function(event, object){
			event.preventDefault();
			$('#input_av').val('');
			var avs = object.item;
			agregarF3('avs', avs);
		},
		minLength: 0
	});

	function onCheckboxItemChange(name, index) {
		if(name == 'conocimiento'){
			conocimientos[index].checked = $('#ch_sel_conocimiento_'+index).is(':checked');
			// console.log(conocimientos[index]);
		}else if(name == 'habilidad'){
			habilidades[index].checked = $('#ch_sel_habilidad_'+index).is(':checked');
			// console.log(habilidades[index]);
		}else if(name == 'av'){
			actitudesValores[index].checked = $('#ch_sel_av_'+index).is(':checked');
			// console.log(actitudesValores[index]);
		}
	}

	function checkboxItem(name, index, item) {
		var html = '<label style="display: block;" for="ch_sel_'+name+'_'+index+'" class="cont-ch-normal">'+
		  '<input type="checkbox" id="ch_sel_'+name+'_'+index+'" onchange="onCheckboxItemChange(\''+name+'\', '+index+')" '+(item.checked ? 'checked' : '')+' name="'+name+'[]" value="1">'+
		  '<span class="checkmark"></span>&nbsp;'+item.label
		'</label>';
		return html;
	}

	function redraw(opcion){
		if(opcion == 'conocimientos'){
			conocimientoObj.table.html('');
			conocimientos.forEach(function (item, index){
				var html = '<tr><td class="align-middle">'+checkboxItem('conocimiento', index, item)+'</td></tr>';
				conocimientoObj.table.prepend(html);
			});
			$('#total_conocimientos').html(conocimientos.length);
		}else if(opcion == 'habilidades'){
			habilidadObj.table.html('');
			habilidades.forEach(function (item, index){
				// var html = '<tr><td class="align-middle"><button data-index="'+index+'" class="btn btn-sm btn-danger btn_remover_habilidad"><i class="fa fa-trash"></i></button></td><td class="align-middle">'+item.label+'</td></tr>';
				var html = '<tr><td class="align-middle">'+checkboxItem('habilidad', index, item)+'</td></tr>';
				habilidadObj.table.prepend(html);
			});
			$('#total_habilidades').html(habilidades.length);
		}else if(opcion == 'avs'){
			avObj.table.html('');
			actitudesValores.forEach(function (item, index){
				// var html = '<tr><td class="align-middle"><button data-index="'+index+'" class="btn btn-sm btn-danger btn_remover_av"><i class="fa fa-trash"></i></button></td><td class="align-middle">'+item.label+'</td></tr>';
				var html = '<tr><td class="align-middle">'+checkboxItem('av', index, item)+'</td></tr>';
				avObj.table.prepend(html);
			});
			$('#total_avs').html(actitudesValores.length);
		}
	}

	function agregarF3(opcion,item){
		if(item.checked == null){ item.checked = true; } // si no se ha especificado, se marca por default
		var encontrado = false;
		if(opcion == 'conocimientos'){
			for (var i = 0; i < conocimientos.length; i++) {
				if( (conocimientos[i].id != 0 && conocimientos[i].id == item.id) || conocimientos[i].label.trim() == item.label.trim()){
					encontrado = true;
					break;
				}
			};
		}else if(opcion == 'habilidades'){
			for (var i = 0; i < habilidades.length; i++) {
				if( (habilidades[i].id != 0 && habilidades[i].id == item.id) || habilidades[i].label.trim() == item.label.trim()){
					encontrado = true;
					break;
				}
			};
		}else if(opcion == 'avs'){
			for (var i = 0; i < actitudesValores.length; i++) {
				if( (actitudesValores[i].id != 0 && actitudesValores[i].id == item.id) || actitudesValores[i].label.trim() == item.label.trim()){
					encontrado = true;
					break;
				}
			};
		}

		if(!encontrado){
			if(opcion == 'conocimientos'){
				conocimientos.push(item);
			}else if(opcion == 'habilidades'){
				habilidades.push(item);
			}else if(opcion == 'avs'){
				actitudesValores.push(item);
			}

			redraw(opcion);
			return true;
		}
		return false;
	}
	
	function obtenerF3(opcion){
		var url = opcion == 'conocimientos' ? '<?= ruta("asistente.fase3_conocimientos") ?>' : (opcion == 'habilidades' ? '<?= ruta("asistente.fase3_habilidades") ?>' : '<?= ruta("asistente.fase3_avs") ?>');
		$.ajax({
		    url: url,
		    method: 'get',
		    data: {id_competencia: idCompetencia},
		    success: function(response){
				if(response.estado){
					response.extra.forEach(function(item){
						var checked = item.es_recomendacion ? false : true;
						var tmp = {};
						if(opcion == 'conocimientos'){
							tmp = {id: item.id_compo_conocimiento || 0, label: item.descrip_conocimiento, checked: checked};
						}else if(opcion == 'habilidades'){
							tmp = {id: item.id_compo_habilidad || 0, label: item.descrip_habilidad, checked: checked };
						}else{
							tmp = {id: item.id_compo_valor || item.id_compo_actitud_valor || 0, label: item.descrip_actitud_valor, checked: checked};
						}
						agregarF3(opcion, tmp);
					});
				}else{
					utils.alert.error(response.mensaje);
				}
		    },
		    error: function(xhr){
		        utils.alert.error('Ha ocurrido un error al obtener '+( opcion == 'conocimientos' ? 'los conocimientos' : (opcion == 'habilidades' ? 'las habilidades' : 'las actitudes y valores') )+' de la competencia');
		    }
		});
	}


	function obtenerRecomendacionesFase3(){
		if(idCompetencia != 0){
			obtenerF3('conocimientos');
			obtenerF3('habilidades');
			obtenerF3('avs');
		}else{
			utils.alert.warning('No se ha seleccionado una competencia');
		}
	}


	// ------------------------------------------------------------------------
	// ------------------------------------------------------------------------

	$(document).off('click','.btn_remover_conocimiento').on('click','.btn_remover_conocimiento', function (e){
	    e.preventDefault();
	    var index = $(this).data('index');
	    conocimientos.splice(index, 1);
	    redraw('conocimientos');
	});

	$(document).off('click','.btn_remover_habilidad').on('click','.btn_remover_habilidad', function (e){
	    e.preventDefault();
	    var index = $(this).data('index');
	    habilidades.splice(index, 1);
	    redraw('habilidades');
	});

	$(document).off('click','.btn_remover_av').on('click','.btn_remover_av', function (e){
	    e.preventDefault();
	    var index = $(this).data('index');
	    actitudesValores.splice(index, 1);
	    redraw('avs');
	});

    $(document).ready(function(){
		obtenerRecomendacionesFase3();

		$('#btn_guardar_competencia_fase3').off('click').on('click', function (e){
		    e.preventDefault();
		   	var correcto = true;
			
			var conocimientosF = conocimientos.filter(function(item){return item.checked; });
			var habilidadesF = habilidades.filter(function(item){return item.checked; });
			var actitudesValoresF = actitudesValores.filter(function(item){return item.checked; });

		    if(conocimientosF.length == 0){
		    	utils.alert.warning('Debe incluir al menos un conocimiento');
		    	correcto = false;
		    }

		    if(habilidadesF.length == 0){
		    	utils.alert.warning('Debe incluir al menos una habilidad');
		    	correcto = false;
		    }

		    if(actitudesValoresF.length == 0){
		    	utils.alert.warning('Debe incluir al menos una actitud o valor');
		    	correcto = false;
		    }

		    if(!correcto){
		    	return;
		    }

		    var datosEnviar = {
		    	conocimientos : conocimientosF,
		    	habilidades: habilidadesF,
		    	avs: actitudesValoresF,
		    	id_competencia: idCompetencia
		    };

		    $.ajax({
		        url: '<?= ruta("asistente.guardar_fase3") ?>',
		        method: 'post',
		        data: datosEnviar,
		        success: function(response){
		    		if(response.estado){
		    			utils.alert.success(response.mensaje);
		    			if(ultimoPasoGuardado < 3){
		    				ultimoPasoGuardado = 3;
		    			}
		    			fase4(true);

		    		}else{
		    			utils.alert.danger(response.mensaje);
		    		}
		        },
		        error: function(xhr){
		            utils.alert.error('Ha ocurrido un error al guardar la fase 3');
		        }
		    });
		});


		// para conocimientos
		// -------------------------------------------------------------------
		conocimientoObj.btnAdd.off('click').on('click', function (){
		    conocimientoObj.input.val("");
			$('#input_conocimiento').val('');
			$('#input_conocimiento').focus();
			$('#input_conocimiento').autocomplete("search", '');
		    // var texto = conocimientoObj.input.val();
		    // if(texto.trim() != ""){
		    // 	if(agregarF3('conocimientos',{id: 0, label: texto})){
		    // 		conocimientoObj.input.val("");
		    // 	}else{
		    // 		utils.alert.error('El conocimiento ya existe en el listado');
		    // 	}
		    // }
		});

		conocimientoObj.input.on('keydown', function (e){
			if(e.which == 13){
				var _this = $(this);
				var texto = _this.val();
				if(texto.trim() != ""){
					if(agregarF3('conocimientos',{id: 0, label: texto})){
						_this.val("");
					}else{
						utils.alert.error('El conocimiento ya existe en el listado');
					}
				}
			}
		});


		// para habilidades
		// -------------------------------------------------------------------
		habilidadObj.btnAdd.off('click').on('click', function (){
		    habilidadObj.input.val("");
			$('#input_habilidad').val('');
			$('#input_habilidad').focus();
			$('#input_habilidad').autocomplete("search", '');
		    // var texto = habilidadObj.input.val();
		    // if(texto.trim() != ""){
		    // 	if(agregarF3('habilidades',{id: 0, label: texto})){
		    // 		habilidadObj.input.val("");
		    // 	}else{
		    // 		utils.alert.error('La habilidad ya existe en el listado');
		    // 	}
		    // }
		});

		habilidadObj.input.on('keydown', function (e){
			if(e.which == 13){
				var _this = $(this);
				var texto = _this.val();
				if(texto.trim() != ""){
					if(agregarF3('habilidades',{id: 0, label: texto})){
						_this.val("");
					}else{
						utils.alert.error('La habilidad ya existe en el listado');
					}
				}
			}
		});

		// para actitudes y valores
		// -------------------------------------------------------------------
		avObj.btnAdd.off('click').on('click', function (){
	        avObj.input.val("");
	    	$('#input_av').val('');
	    	$('#input_av').focus();
	    	$('#input_av').autocomplete("search", '');
		    // var texto = avObj.input.val();
		    // if(texto.trim() != ""){
		    // 	if(agregarF3('avs',{id: 0, label: texto})){
		    // 		avObj.input.val("");
		    // 	}else{
		    // 		utils.alert.error('La actitud o valor ya existe en el listado');
		    // 	}
		    // }
		});

		avObj.input.on('keydown', function (e){
			if(e.which == 13){
				var _this = $(this);
				var texto = _this.val();
				if(texto.trim() != ""){
					if(agregarF3('avs',{id: 0, label: texto})){
						_this.val("");
					}else{
						utils.alert.error('La actitud o valor ya existe en el listado');
					}
					console.log(actitudesValores);
				}
			}
		});
    });
</script>