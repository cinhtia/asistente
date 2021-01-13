<div class="container-fluid">
	<button data-toggle="popover" data-trigger="hover" data-container="body" role="button" data-placement="left" data-html="true" data-content="<?= AYUDA_FASES['f3'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 3. Selección de competencia genérica</h4>
	<hr class="mb-25">
	<div class="table-responsive mb-10">
		<table class="table table-bordered">
			<colgroup>
				<col width="25%">
				<col width="20%">
				<col width="20%">
				<col width="35%">
			</colgroup>
			<thead class="thead-dark">
				<tr>
					<th>Asignatura</th>
					<th>Unidad</th>
					<th>Contenido(s)</th>
					<th>Competencia</th>
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
					<td>
						<?= $this->competencia_resultado->competencia_editable ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<p>Seleccione las competencias genéricas vinculadas con esta competencia:</p>
	<table class="table table-hover table-stripped table-bordered">
		<thead >
			<tr class="thead-dark">
				<th class="text-center" style="vertical-align: middle;">No.</th>
				<th class="text-center" style="vertical-align: middle;">Seleccionar</th>
				<th class="text-center" style="vertical-align: middle;">Competencias genéricas</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->cgs as $index => $item) {?>
				<tr id="cg_row_<?= $index ?>" class="<?= in_array($item->id_cg,$this->cgsSels) ? 'table-success' : ''; ?>">
					<td><?= $index+1 ?></td>
					<td class="text-center">
						<label for="ch_comp_gen_<?= $index ?>" class="cont-ch">
						  <input type="checkbox" class="ch-sel"  data-index="<?= $index ?>" id="ch_comp_gen_<?= $index ?>" name="comp_gen[]" value="<?= $item->id_cg; ?>" <?= in_array($item->id_cg, $this->cgsSels) ? "checked" : "" ?>>
						  <span class="checkmark"></span>
						</label>
					</td>
					<td><?= $item->descripcion_cg ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if(count($this->cgs)==0){ ?>
	<div class="alert alert-warning text-center"><strong>No se encontraron competencias genéricas configuradas en la asignatura</strong></div>
	<?php } ?>

	<br><br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" type='button' onclick="enviarDatosF4()">
				Siguiente  <i class="fa fa-arrow-right"></i> 
			</button>
		</div>
	</div>
</div>

<script type="text/javascript">

	var competencia = <?= json_encode($this->competencia_resultado); ?>;
	
	// $( function() {
	//   	$( "input[type='checkbox'].ch-sel" ).checkboxradio({
	//   		icon: false
	//   	});
	// });

	function obtenerSeleccionados(){
		var list = [];
		var error = false;
		$("input[name='comp_gen[]']").each(function(index){
			var _this = $(this);
			if(_this.is(':checked')){
				list.push(_this.val());
			}
		});

		if(list.length == 0){
			utils.alert.error('Debes seleccionar al menos una competencia genérica');
			return [];
		}

		return list;
	}

	function enviarDatosF4(){
		var lista = obtenerSeleccionados();
		if(lista.length > 0){
			api.post({
				url: '<?= ruta("asistente.fresultado_guardarf4") ?>',
				data: { id_competencia: competencia.id_competencia, cgs: lista},
				cb: function (response){
					if(response.estado){
						utils.alert.success(response.mensaje);
						if(response.extra.etapa_actual > ultimoPasoGuardado){
							ultimoPasoGuardado = response.extra.etapa_actual;
						}
						fase5(true);
					}else{
						utils.alert.error(response.mensaje);
					}
				},
				error: function (){
					utils.alert.error('Ha ocurrido un error al intentar guardar la fase 4');
				}
			});
		}
	}

    $(document).ready(function(){
  		$('.ch-sel').on('change', function (e){
	  		var index = $(this).data('index');
	  		if($(this).is(':checked')){
	  			$('#cg_row_'+index).addClass('table-success');
	  		}else{
	  			$('#cg_row_'+index).removeClass('table-success');
	  		}
  		});

    });
</script>