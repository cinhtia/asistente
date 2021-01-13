<?php 

$competencia = $data->fromBody('competencia');
$conocimientos = $data->fromBody('conocimientos', []);
$habilidades = $data->fromBody('habilidades', []);
$actitudesValores = $data->fromBody('actitudesValores', []);
?>
<h4>Fase 3. Desglose de componentes</h4>
<div style="margin: 10px 30px 5px 10px;" class="text-center">
	<p>Competencia creada</p>
	<h5 id="contenedor_competencia_generada" class="text-primary">
		<strong><?= $competencia->competencia_editable; ?></strong>
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
	    		<input type="text" placeholder="Cree un conocimiento aquí" id="input_conocimiento" class="form-control">
	    		<span class="input-group-append">
	    			<button id="btn_add_conocimiento" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agregar">
	    				<i class="fa fa-check"></i>
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
				<input type="text" placeholder="Cree una habilidad aquí" id="input_habilidad" class="form-control">
				<span class="input-group-append">
					<button id="btn_add_habilidad" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agregar">
						<i class="fa fa-check"></i>
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
				<input type="text" placeholder="Cree una actitud o valor aquí" id="input_av" class="form-control">
				<span class="input-group-append">
					<button id="btn_add_av" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Agregar">
						<i class="fa fa-check"></i>
					</button>
				</span>
			</div>
		</div>
	</div>
</div>

<br><br>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="pull-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" id="btn_guardar_competencia_fase3" type='button'>
				Siguiente <i class="fa fa-arrow-right"></i>
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

	var idCompetencia = <?= $competencia->id_competencia ?>;
	var conocimientos = [];
	var habilidades = [];
	var actitudesValores = [];

	function redraw(opcion){
		if(opcion == 'conocimientos'){
			conocimientoObj.table.html('');
			conocimientos.forEach(function (item, index){
				var html = '<tr><td class="align-middle"><button data-index="'+index+'" class="btn btn-sm btn-danger btn_remover_conocimiento">&times;</button></td><td class="align-middle">'+item.label+'</td></tr>';
				conocimientoObj.table.prepend(html);
			});
			$('#total_conocimientos').html(conocimientos.length);
		}else if(opcion == 'habilidades'){
			habilidadObj.table.html('');
			habilidades.forEach(function (item, index){
				var html = '<tr><td class="align-middle"><button data-index="'+index+'" class="btn btn-sm btn-danger btn_remover_habilidad">&times;</button></td><td class="align-middle">'+item.label+'</td></tr>';
				habilidadObj.table.prepend(html);
			});
			$('#total_habilidades').html(habilidades.length);
		}else if(opcion == 'avs'){
			avObj.table.html('');
			actitudesValores.forEach(function (item, index){
				var html = '<tr><td class="align-middle"><button data-index="'+index+'" class="btn btn-sm btn-danger btn_remover_av">&times;</button></td><td class="align-middle">'+item.label+'</td></tr>';
				avObj.table.prepend(html);
			});
			$('#total_avs').html(actitudesValores.length);
		}
	}

	function agregarF3(opcion,item){
		var encontrado = false;
		console.log("llega "+opcion);
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
						var tmp = {};
						if(opcion == 'conocimientos'){
							tmp = {id: item.id_compo_conocimiento, label: item.descrip_conocimiento};
						}else if(opcion == 'habilidades'){
							tmp = {id: item.id_compo_habilidad, label: item.descrip_habilidad};
						}else{
							tmp = {id: item.id_compo_valor || item.id_compo_actitud_valor || 0, label: item.descrip_actitud_valor};
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
			utils.alert.warning('No se ha seleccionado una competencia.');
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

	// ------------------------------------------------------------------------
	// ------------------------------------------------------------------------

    $(document).ready(function(){

    	obtenerRecomendacionesFase3();

    	$('#btn_guardar_competencia_fase3').off('click').on('click', function (e){
    	    e.preventDefault();
    	   	var correcto = true;
    	    if(conocimientos.length == 0){
    	    	utils.alert.warning('Debe incluir al menos un conocimiento');
    	    	correcto = false;
    	    }

    	    if(habilidades.length == 0){
    	    	utils.alert.warning('Debe incluir al menos una habilidad');
    	    	correcto = false;
    	    }

    	    if(actitudesValores.length == 0){
    	    	utils.alert.warning('Debe incluir al menos una actitud o valor');
    	    	correcto = false;
    	    }

    	    if(!correcto){
    	    	return;
    	    }

    	    var datosEnviar = {
    	    	conocimientos : conocimientos,
    	    	habilidades: habilidades,
    	    	avs: actitudesValores,
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

    	    			fase4();
    	    			$('#smartwizard').smartWizard("next");
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
    	    var texto = conocimientoObj.input.val();
    	    if(texto.trim() != ""){
    	    	if(agregarF3('conocimientos',{id: 0, label: texto})){
    	    		conocimientoObj.input.val("");
    	    	}else{
    	    		utils.alert.error('El conocimiento ya existe en el listado, solo selecciónelo.');
    	    	}
    	    }
    	});

    	conocimientoObj.input.on('keydown', function (e){
    		if(e.which == 13){
    			var _this = $(this);
    			var texto = _this.val();
    			if(texto.trim() != ""){
    				if(agregarF3('conocimientos',{id: 0, label: texto})){
    					_this.val("");
    				}else{
    					utils.alert.error('El conocimiento ya existe en el listado, solo selecciónelo.');
    				}
    			}
    		}
    	});


    	// para habilidades
    	// -------------------------------------------------------------------
    	habilidadObj.btnAdd.off('click').on('click', function (){
    	    var texto = habilidadObj.input.val();
    	    if(texto.trim() != ""){
    	    	if(agregarF3('habilidades',{id: 0, label: texto})){
    	    		habilidadObj.input.val("");
    	    	}else{
    	    		utils.alert.error('Esta habilidad ya existe en el listado, solo selecciónela.');
    	    	}
    	    }
    	});

    	habilidadObj.input.on('keydown', function (e){
    		if(e.which == 13){
    			var _this = $(this);
    			var texto = _this.val();
    			if(texto.trim() != ""){
    				if(agregarF3('habilidades',{id: 0, label: texto})){
    					_this.val("");
    				}else{
    					utils.alert.error('La habilidad ya existe en el listado, solo selecciónela.');
    				}
    			}
    		}
    	});

    	// para actitudes y valores
    	// -------------------------------------------------------------------
    	avObj.btnAdd.off('click').on('click', function (){
    	    var texto = avObj.input.val();
    	    if(texto.trim() != ""){
    	    	if(agregarF3('avs',{id: 0, label: texto})){
    	    		avObj.input.val("");
    	    	}else{
    	    		utils.alert.error('La actitud o valor ya existe en el listado, solo selecciónela.');
    	    	}
    	    }
    	});

    	avObj.input.on('keydown', function (e){
    		if(e.which == 13){
    			var _this = $(this);
    			var texto = _this.val();
    			if(texto.trim() != ""){
    				if(agregarF3('avs',{id: 0, label: texto})){
    					_this.val("");
    				}else{
    					utils.alert.error('La actitud o valor ya existe en el listado, solo selecciónela.');
    				}
    				console.log(actitudesValores);
    			}
    		}
    	});
    	

    });
</script>




