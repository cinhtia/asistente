<?php
$competencia = $data->fromBody('competencia');
$cgs = $data->fromBody('cgs');
$asignaturaPe = $data->fromBody('asignaturaPe');
$asignatura = $data->fromBody('asignatura');
$numUnidades = $asignaturaPe->num_unidades;
$cgsIds = $data->fromBody('cgsIds', []);
$ids = [];
foreach ($cgsIds as $itemCg) {
	$ids[$itemCg->id_cg] = $itemCg->unidades;
}
?>
<h4>Fase 3. Selección de competencias genéricas relacionadas</h4>
<p>Seleccione las competencias genéricas vinculadas </p>
<div id="alerta_fase3">
</div>

<?php if($asignatura->tipo_asignatura == 'obligatoria'){ ?>
<div class="alert alert-<?= count($cgs) == 0 ? 'danger' :'info' ?>">
	<strong><?= count($cgs) == 0 ? 'La asignatura no tiene definido las competencias genéricas' : 'Las siguientes competencias genéricas han sido pre-definidas para la asignatura' ?> </strong>
</div>
<?php }else{ ?>
<div class="alert alert-info">
	<strong>Seleccione un máximo de 6 competencias genéricas para esta asignatura</strong>
</div>
<?php } ?>
<table class="table table-hover table-stripped table-bordered">
	<thead >
		<tr class="thead-dark">
			<th rowspan="2" class="text-center" style="vertical-align: middle;">No.</th>
			<th rowspan="2" colspan="2" class="text-center" style="vertical-align: middle;">Competencias genéricas</th>
			<th colspan="5" class="text-center">Unidades</th>
		</tr>
		<tr class="text-center thead-dark">
			<?php for ($i=1; $i < $numUnidades; $i++) {  ?>
				<th><?= $i; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cgs as $index => $itemCg) {
			$seleccionado = isset($ids[$itemCg['id_cg']]) ? true : false;
			$unidades = $seleccionado ? explode(",", $ids[$itemCg['id_cg']]) : [];
			?>
			<tr id="cg_row_<?= $index ?>" class="<?= $seleccionado ? 'table-success' : ''; ?>">
				<td><?= ($index+1) ?></td>
				<td>
					<?php if($asignatura->tipo_asignatura == 'obligatoria'){ ?>
						<strong>Definida</strong>
					<?php }else{ ?>
						<label for="ch_comp_gen_<?= $index ?>"><i class="fa fa-check"></i> Seleccionar</label>
						<input type="checkbox" data-index="<?= $index ?>" class="ch-sel" id="ch_comp_gen_<?= $index ?>" name="comp_gen[]" value="<?= $itemCg['id_cg']; ?>" <?= $seleccionado ? "checked" : "" ?>>
					<?php } ?>
				</td>
				<td class="text-justify"><?= $itemCg['descripcion_cg'] ?></td>

				<?php for ($i=1; $i < $numUnidades; $i++) {  ?>
				<td>
					<?php if($asignatura->tipo_asignatura == 'obligatoria'){ ?>
						<i class="fa fa-check"></i>
					<?php }else{ ?>
						<input type="checkbox" value="<?= $i ?>" name="unidad_<?= $index ?>[]" <?= in_array($i, $unidades) ? "checked" : "" ?>>
					<?php }?>
				</td>
				<?php } ?>
			</tr>
		<?php } ?>
	</tbody>
</table>


<br><br>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="pull-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" id="btn_guardar_competencia_fase4" type='button'>
				Siguiente <i class="fa fa-arrow-right"></i>
			</button>
		</div>
	</div>
</div>
<script>
	var tipoAsignatura = "<?= $asignatura->tipo_asignatura; ?>";
	var ultimoError = "";
	function totalSeleccionados(){
		var total = 0;
		$("input[name='comp_gen[]']").each(function(index){
			total += ($(this).is(':checked') ? 1 : 0);
		});
		return total;
	}

  	$( function() {
    	$( "input[type='checkbox'].ch-sel" ).checkboxradio({
    		icon: false
    	});
  	});

  	$(document).ready(function (){
  		$('.ch-sel').on('change', function (e){

	  		if(totalSeleccionados()>6){
	  			e.preventDefault();
	  			$(this).prop('checked', false).change();
	  			utils.alert.error('Solamente puede seleccionar hasta 6 competencias genéricas');
	  			return;
	  		}

	  		var index = $(this).data('index');
	  		if($(this).is(':checked')){
	  			$('#cg_row_'+index).addClass('table-success');
	  		}else{
	  			$('#cg_row_'+index).removeClass('table-success');
	  		}

  		});

  		$('#btn_guardar_competencia_fase4').off('click').on('click', function (e){
  		    e.preventDefault();
  		    if(tipoAsignatura == 'obligatoria'){
  		    	$.ajax({
    			    url: '<?= ruta("asistente.guardar_fase4") ?>',
    			    method: 'post',
    			    data: { id_competencia: <?= $competencia->id_competencia ?> },
    			    success: function(response){
    					if(response.estado){
    						// utils.alert.success(response.mensaje);
    						window.location.replace("<?= ruta('competencia.perfil').'?id='.$competencia->id_competencia.'&frm=1'; ?>");
    					}else{
    						utils.alert.error('Ha ocurrido un error. '+response.mensaje);
    					}
    			    },
    			    error: function(xhr){
    			        utils.alert.error('Ha ocurrido un error al intentar guardar la última fase de la creación de ésta competencia');
    			    }
    			});
  		    }else{
  		    	var totalSel = totalSeleccionados();
  		    	if(totalSel>1 && totalSel<=6){

  		    		var seleccionados = [];
  		    		var error = false;
  		    		var indexError = 0;
  		    		$("input[name='comp_gen[]']").each(function(index){
  		    			var _this = $(this);
  		    			if(_this.is(':checked')){
  		    				var tmp = {
  		    					cg: _this.val(),
  		    					unidades : [],
  		    				};

  		    				$("input[name='unidad_"+index+"[]']").each(function (index){
  		    					if($(this).is(':checked')){
  		    						tmp.unidades.push($(this).val());
  		    					}
  		    				});

  		    				if(!error && tmp.unidades.length == 0){
  		    					error = true;
  		    					indexError = index;
  		    				}

  		    				seleccionados.push(tmp);
  		    			}
  		    		});

  		    		console.log(seleccionados);
  		    		if(error){
  		    			utils.alert.error('Debe seleccionar al menos una unidad para la competencia genérica '+(indexError+1)+' seleccionada' );
  		    			return;
  		    		}else{

  		    			$.ajax({
  		    			    url: '<?= ruta("asistente.guardar_fase4") ?>',
  		    			    method: 'post',
  		    			    data: { id_competencia: <?= $competencia->id_competencia ?>, ids_cgs: seleccionados },
  		    			    success: function(response){
  		    					if(response.estado){
  		    						utils.alert.success(response.mensaje);
  		    						window.location.replace("<?= ruta('competencia.perfil').'?id='.$competencia->id_competencia.'&frm=1'; ?>");
  		    					}else{
  		    						utils.alert.error('Ha ocurrido un error. '+response.mensaje);
  		    					}
  		    			    },
  		    			    error: function(xhr){
  		    			        utils.alert.error('Ha ocurrido un error al intentar guardar la última fase de la creación de ésta competencia');
  		    			    }
  		    			});

  		    			utils.alert.info(seleccionados.length+' competencias genéricas');

  		    		}

  		    	}else{
  		    		utils.alert.error('Debe seleccionar al menos una competencia genérica');
  		    	}
  		    }
  		    
  		});
  });

</script>