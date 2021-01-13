<style>
	.modal-lg{
		max-width: 90% !important;
	}
</style>
<?php
$crear_copia = !$this->plantilla->es_copia || ( $this->id_competencia != null && $this->id_competencia != $this->plantilla->id_competencia );
?>
<div class="row">
	<div class="col-lg-10">
		<div class="alert alert-info d-none" id="alert_info_plantilla_rubrica"></div>
		<form id="frm_plantilla">
			<input type="hidden" name="id_plantilla_rubrica" id="id_plantilla_rubrica" value="<?= $crear_copia ? $this->plantilla->id_plantilla_rubrica : '0' ?>">
			<input type="hidden" name="id_rubrica" id="id_rubrica" value="<?= $this->plantilla->id_rubrica ?>">
			<input type="hidden" name="es_copia" id="es_copia" value="<?= $this->plantilla->es_copia ?>">
			<div class="form-group">
			    <label>Nombre <span class="text-danger">*</span></label> 
			    <input type="text" name="nombre" id="nombre" required="true" value="<?= $this->plantilla->nombre ?>" class="form-control" placeholder="Nombre"/>
			</div>
			<div style="height: 400px; max-height: 500px; overflow-y: scroll; margin: 10px;">
				<div id="contenedor_preview_grid" class="table-responsive"></div>
			</div>
		</form>
	</div>
	<div class="col-lg-2">
		<br><br><br><br>
		<button type="button" onclick="agregarColumna()" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Columna</button>
	</div>
</div>
<div class="row">
	<div class="col-lg-10">
		<button type="button" onclick="agregarFila()" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Fila</button>
	</div>
	<div class="col-lg-2"></div>
</div>

<?php
// print $this->plantilla->contenido;
// print "<br>";
// print "<br> ===> <br>";
// print_r (json_decode(((trim($this->plantilla->contenido))), true));
// print "<br>";
// print "<br>";
$strContenido = $this->plantilla->contenido;

if($strContenido){
	// print "==> intento 1";
	$this->plantilla->contenido = json_decode(trim($strContenido), true);
}

if($strContenido && !$this->plantilla->contenido){
	// print "==> intento 2";
	$this->plantilla->contenido = json_decode(html_entity_decode(trim($strContenido)), true);
}

if($strContenido && !$this->plantilla->contenido){
	// print "==> intento 3";
	$strContenido = stripslashes( html_entity_decode(trim($strContenido)) );
	$this->plantilla->contenido = json_decode(trim($strContenido), true);
}
// print "<br> aaa <br>";
// print $strContenido;
// print "<br> bbb <br>";
// print json_encode($this->plantilla);
?>


<script type="text/javascript">
	var esCopia = <?= $this->es_copia ? 'true' : 'false'; ?>;
	
	var idCompetenciaPlantilla = <?= $this->id_competencia ? $this->id_competencia : 'null'; ?>;
	// var extrasCompetencia = < ?= json_encode($this->extras_competencia) ? >;
	var esNuevo = <?= $this->registro_nuevo ? 'true' : 'false' ?>;
	var refIncluidas = JSON.parse('<?= $this->plantilla && $this->plantilla->referencias_incluidas ? htmlspecialchars_decode($this->plantilla->referencias_incluidas) : '{}' ?>');
	var plantillaOriginal = <?= json_encode($this->plantilla) ?>;


	var data = {
		num_valoraciones: 2,
		num_puntajes: 2,
		matriz: [
			[ { value: '' },  { value: ''  }, { value: '' } ],
			[ { value: '' } , { value: ''  }, { value: '' } ],
			[ { value: '' } , { value: ''  }, { value: '' } ],
		]
	};

	function agregarColumna() {
		for (var i = 0; i < data.num_valoraciones+1; i++)
			data.matriz[i].push({value: ''});
		data.num_puntajes++;
		drawTable();
	}

	function agregarFila() {
		var nRow = [{value: ''}];
		for (var i = 0; i < data.num_puntajes; i++)
			nRow.push({value: ''});
		data.matriz.push(nRow);
		data.num_valoraciones++;
		drawTable();
	}

	function actualizarValor(i,j){
		data.matriz[i][j].value = $('#cell_'+i+'_'+j).val();
	}

	function removerColumna(i){
		utils.modal.confirmDelete({
			titulo:'Confirmación',
			contenido: '¿Confirma que desea eliminar esta columna?',
			confirm: function(dialog){
				dialog.modal.modal('hide');
				data.num_puntajes--;
				for (var k = 0; k < data.matriz.length; k++)
					data.matriz[k].splice(i,1);
				drawTable();
			}
		});
	}

	function removerFila(i) {
		utils.modal.confirmDelete({
			titulo: 'Confirmación',
			contenido: '¿Confirma que desea eliminar esta fila?',
			confirm: function(dialog){
				dialog.modal.modal('hide');
				data.num_valoraciones--;
				data.matriz.splice(i,1);
				drawTable();
			}
		});
	}

	function drawCell(cell, i, j){
		var ret = '<td>';
		if(j>1 && i==0){ ret+='<button type="button" onclick="removerColumna('+(j-1)+')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>'; }
		if(i>1 && j==0){ ret+='<button type="button" onclick="removerFila('+(i-1)+')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>'; }
		if(i>0 && j>0){
			i--;
			j--;
			if(i!=0 || j!=0){
				var placeholder = 'Descripción';
				if(i==0 && j>0){
					placeholder = 'Puntaje '+j;
				}else if(i>0 && j==0){
					placeholder = 'Valoración '+i;
				}
				ret+='<textarea rows="2" required="true" onchange="actualizarValor('+(i)+','+(j)+')" placeholder="'+placeholder+'" class="form-control" name="cell_'+i+'_'+j+'" id="cell_'+i+'_'+j+'">'+(cell ? cell.value : '')+'</textarea>';			
			}else{
				ret += '<div class="text-center">Criterios / Valoración</div>';
			}
		}

		return ret+'</td>';
	}
	

	function drawTable() {
		var length = data.num_puntajes+1;
		var tmp = '<colgroup>';
		for (var i = 0; i < length; i++) {
			tmp += '<col width="200px">';
		}
		tmp +='</colgroup>';

		var html = '<table id="table_prev_data">';
		// console.log(data);
		for (var i = 0; i < data.num_valoraciones+2; i++) {
			var row = '<tr>';
			for (var j = 0; j < data.num_puntajes+2; j++) {
				if(i>0 && j>0){
					row+=drawCell( data.matriz[i-1] ? (data.matriz[i-1][j-1] || {}) : {} , i, j);
				}else{
					row+=drawCell(null, i, j);
				}
			}
			row+='</tr>';
			html+=row;
		}
		html += '</table>';
		$('#contenedor_preview_grid').html(html);
	}

	function prellenar() {
		if(plantillaOriginal.conteo_modificados && plantillaOriginal.conteo_modificados > 0){
			// Se han añadido 10 filas a la rúbrica, considerando los datos de las fases previas de la evaluación.
			var msj = plantillaOriginal.conteo_modificados == 1 ? 'Se han añadido 1 fila a la rúbrica, considerando los datos de las fases previas de la evaluación' : ('Se han añadido '+plantillaOriginal.conteo_modificados+' filas a la rúbrica, considerando los datos de las fases previas de la evaluación');
			// msj += ' en base a las fases previas de la competencia en edición';
			$('#alert_info_plantilla_rubrica').html('<strong>'+msj+'</strong>')
			$('#alert_info_plantilla_rubrica').addClass('d-block');
		}
		// var nuevos_agregados = 0;
		// // var data = {
		// // 	num_valoraciones: 2,
		// // 	num_puntajes: 2,
		// // 	matriz: [
		// // 		[ { value: '' },  { value: ''  }, { value: '' } ],
		// // 		[ { value: '' } , { value: ''  }, { value: '' } ],
		// // 		[ { value: '' } , { value: ''  }, { value: '' } ],
		// // 	]
		// // };
		
		// if(!refIncluidas.conocimiento){ refIncluidas.conocimiento = []; }
		// if(!refIncluidas.habilidad){ refIncluidas.habilidad = []; }
		// if(!refIncluidas.actitud){ refIncluidas.actitud = []; }
		// if(!refIncluidas.cg){ refIncluidas.cg = []; }

		// var conocimiento = extrasCompetencia.conocimiento || [];
		// var incCon = refIncluidas.conocimiento || [];

		// var habilidad = extrasCompetencia.habilidad || [];
		// var incHab = refIncluidas.habilidad || [];

		// var actitud = extrasCompetencia.actitud || [];
		// var incActitud = refIncluidas.actitud || [];

		// var cg = extrasCompetencia.cg || [];
		// var incCG = refIncluidas.cg || [];
		
		// conocimiento.forEach(function (con, index){			
		// 	if(incCon.indexOf(con.id_compo_conocimiento) == -1){
		// 		console.log("=========> agregando un conocimiento");
		// 		nuevos_agregados++;
		// 		refIncluidas.conocimiento.push(con.id_compo_conocimiento);
				
		// 		var newRow = [];
		// 		newRow.push({ value:  con.descrip_conocimiento || '' });
		// 		for (var i = 1; i <= data.num_puntajes; i++) { newRow.push({value: '' }); }

		// 		data.num_valoraciones++;
		// 		data.matriz.push(newRow);
		// 	}
		// });

		// habilidad.forEach(function (hab, index){
		// 	if(incHab.indexOf(hab.id_compo_habilidad) == -1){
		// 		console.log("=========> agregando una habilidad");
		// 		nuevos_agregados++;
		// 		refIncluidas.habilidad.push(hab.id_compo_habilidad);
				
		// 		var newRow = [];
		// 		newRow.push({ value:  hab.descrip_habilidad || '' });
		// 		for (var i = 1; i <= data.num_puntajes; i++) { newRow.push({value: '' }); }

		// 		data.num_valoraciones++;
		// 		data.matriz.push(newRow);
		// 	}
		// });

		// actitud.forEach(function (act, index){
		// 	if(incActitud.indexOf(act.id_compo_actitud_valor) == -1){
		// 		console.log("=========> agregando una actitud");
		// 		nuevos_agregados++;
		// 		refIncluidas.actitud.push(act.id_compo_actitud_valor);
				
		// 		var newRow = [];
		// 		newRow.push({ value:  act.descrip_actitud_valor || '' });
		// 		for (var i = 1; i <= data.num_puntajes; i++) { newRow.push({value: '' }); }

		// 		data.num_valoraciones++;
		// 		data.matriz.push(newRow);
		// 	}
		// });

		// cg.forEach(function (cgItem, index){
		// 	if(incCG.indexOf(cgItem.id_cg) == -1){
		// 		console.log("=========> agregando una cg");
		// 		nuevos_agregados++;
		// 		refIncluidas.cg.push(cgItem.id_cg);
				
		// 		var newRow = [];
		// 		newRow.push({ value:  cgItem.descripcion_cg || '' });
		// 		for (var i = 1; i <= data.num_puntajes; i++) { newRow.push({value: '' }); }

		// 		data.num_valoraciones++;
		// 		data.matriz.push(newRow);
		// 	}
		// });
		

		// drawTable();

		// if(nuevos_agregados > 0){
		// 	var msj = nuevos_agregados == 1 ? 'Se agregó una fila automaticamente a la rúbrica' : 'Se agregaron '+nuevos_agregados+' filas automáticamente a la rúbrica';
		// 	msj += ' en base a las fases previas de la competencia en edición';
		// 	$('#alert_info_plantilla_rubrica').html('<strong>'+msj+'</strong>')
		// 	$('#alert_info_plantilla_rubrica').addClass('d-block');
		// }

		// console.log("======> nuevos agregados: "+nuevos_agregados);
	}

    $(document).ready(function(){
    	var tmp = plantillaOriginal ? plantillaOriginal.contenido : null;  //'< ?= $this->plantilla->contenido ? $this->plantilla->contenido : "{}" ?>'; //'< ?= htmls pecialc hars_d ecode( $ this->plantilla->contenido) ? >';
    	if(tmp){
    		data = tmp; //JSON.parse(tmp);
    	};

		drawTable();

		var form = $('#frm_plantilla');
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				nombre: 'El nombre es requerido'
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				var datos = {
					id_rubrica            : $('#id_rubrica').val(),
					id_plantilla_rubrica  : $('#id_plantilla_rubrica').val(),
					es_copia 			  : $('#es_copia').val(),
					nombre                : $('#nombre').val(),
					contenido             : JSON.stringify(data),
					id_competencia        : idCompetenciaPlantilla,
					referencias_incluidas : refIncluidas ? JSON.stringify(refIncluidas) : null,
				};

				console.log(datos);
				// return;

				api.post({
					url: '<?= ruta("plantillarubrica.guardar") ?>',
					data: datos,
					cb: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							utils.modal.hide();
							// FUNCIONAMIENTO ESPECIAL QUE SE BASA EN
							// EL USO EN EL ASISTENTE----------
							if(esCopia){
								plantillaCreadaComoCopia(response.extra);
							}else{
								recargarListadoPlantillas();
							}
						}else{
							utils.alert.error(response.mensaje);
						}
					},
					error: function(){
						utils.alert.error('Ha ocurrido un error al intentar guardar la plantilla');
					}
				});
			}
		})

		utils.modal.btn.onClick(function (){
			form.submit();
		});

		prellenar();
    });
</script>