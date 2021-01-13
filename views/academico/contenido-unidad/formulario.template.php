<?php $nuevo = $this->cua->id_contenido_unidad_asignatura == 0; ?>
<div class="container">
	<?php $form = new Form(); ?>

	<?php $form->begin('frm_cont_unidad'); ?>
	<?php $form->inputHidden(['id'=>'id_contenido_unidad_asignatura','value'=>$this->cua->id_contenido_unidad_asignatura]) ?>
	<?php $form->inputHidden(['id'=>'id_unidad_asignatura','value'=>$this->cua->id_unidad_asignatura]) ?>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php $form->bs4FormInput([
				'id' => 'duracion_hp',
				'label' => 'Horas presenciales',
				'required' => true,
				'type' => 'number',
				'value' => $nuevo ? '' : $this->cua->duracion_hp,
				'min' => 0,
			],true); ?>	
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php $form->bs4FormInput([
				'id' => 'duracion_hnp',
				'label' => 'Horas no presenciales',
				'required' => true,
				'type' => 'number',
				'value' => $nuevo ? '' : $this->cua->duracion_hnp,
				'min' => 0,
			],true); ?>	
		</div>
	</div>

	<?php $form->bs4FormTextarea([
		'id' => 'detalle_secuencia_contenido',
		'label' => 'Secuencia de contenido',
		'value' => $nuevo ? '' : $this->cua->detalle_secuencia_contenido,
		'required' => true,
		'placeholder' => 'Detalles de la secuencia de contenidos',
		'rows' => 4,
	],true); ?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<div class="form-group">
				<label for="desagregado_contenido">Desagregados</label>
				<div class="input-group">
					<select name="desagregado_contenido" id="desagregado_contenido" class="custom-select">
						<option value="">Seleccione un desagregado</option>
						<?php foreach ($this->desagregados as $item) { ?>
						<option value="<?= $item->id_desagregado_contenido ?>"><?= $item->descripcion; ?></option>
						<?php } ?>
					</select>

					<div class="input-group-append">
						<button type="button" class="btn btn-primary " onclick="agregarDesagregado()">
							<i class="fa fa-check"></i> Agregar
						</button>
					</div>
					
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
			<button onclick="abrirModalCrearDesagregado()" type="button" data-toggle="tooltip" data-placement="top" title="Crear nuevo desagregado" class="btn btn-primary" style="margin-top: 30px;"><i class="fa fa-plus"></i> Crear</button>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<colgroup>
				<col width="85%">
				<col width="15%">
			</colgroup>
			<thead class="thead-dark">
				<tr>
					<th>Descripcion</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody id="tbody"></tbody>
		</table>
	</div>

	<?php $form->end(); ?>
</div>

<script type="text/javascript">
	
	// --------------------------------------------------------
	var desde_asistente = <?= $this->desde_asistente == 1 ? 'true' : 'false'; ?>;
	var desagregados = <?= json_encode($this->desagregadosGuardados) ?>;
	var tbody = $('#tbody');

	function desagregadoCreado(desagregado) {
		console.log(desagregado);
		if(desagregado){
			$('#desagregado_contenido').append('<option value="'+desagregado.id_desagregado_contenido+'">'+desagregado.descripcion+'</option>')
			desagregados.push(desagregado);
			actualizarListado();
			utils.alert.info('Agregado al listado a guardar')
		}
	}

	function abrirModalCrearDesagregado() {
		utils.modal2.remote({
			url: '<?= ruta("desagregado.formulario") ?>',
			data: { desde_form_contenido: 1 },
			modal_options:{
				titulo: 'Crear desagregado de contenido',
				size: 'md',
				type: 'primary',
				backdrop: 'static',
			}
		});
	};

	function itemTable(index){
		var desag = desagregados[index];
		var html = '<tr>'+
			'<td>'+desag.descripcion+'</td>'+
			'<td>'+
				'<button type="button" onclick="removerDesagregado('+index+')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placemente="top" title="Remover desagregado">'+
					'<i class="fa fa-trash"></i>'+
				'</button>'+
			'</td>'+
		'</tr>';
		return html;
	}

	function actualizarListado(index){
		if(index){
			if(index == 0) tbody.html('');
			tbody.append(itemTable(index));
		}else{

			if(desagregados.length > 0) tbody.html('');

			for (var i = 0; i < desagregados.length; i++) {
				tbody.append(itemTable(i));
			}
			if(desagregados.length == 0){
				tbody.html('<tr><td colspan="2" class="text-center"><strong>Sin desagregados asignados<strong></td></tr>');
			}
		}
	}

	function agregarDesagregado(){
		var idDesagregado = $('#desagregado_contenido').val();
		var descrip = $('#desagregado_contenido option:selected').text();
		if(idDesagregado != ""){
			var indexEncontrado = -1;
			desagregados.forEach(function (item, index){
				if(item.id_desagregado_contenido == idDesagregado){
					indexEncontrado = index;
				}
			});

			if(indexEncontrado>=0){
				utils.alert.warning('El desagregado ya se encuentra en el listado');
				return;
			}

			desagregados.push({
				id_desagregado_contenido: idDesagregado,
				descripcion: descrip
			});

			actualizarListado(desagregados.length - 1);

			$('#desagregado_contenido').val('');
		}
	}

	function removerDesagregado(index){
		if(desagregados[index]){
			desagregados.splice(index, 1);
			actualizarListado();
		}
	}

	// --------------------------------------------------------


    $(document).ready(function(){

    	utils.modal.setTitle('<?= ($this->cua->id_contenido_unidad_asignatura ? "Editar secuencia de contenido" : "Nueva secuencia de contenido")." de la unidad ".$this->unidad->nombre_unidad." de la asignatura ".$this->unidad->nombre_asignatura ?>');

    	actualizarListado();

    	var form = $('#frm_cont_unidad');
    	form.validate({
    		validClass: 'is-valid',
    		errorCass: 'is-invalid',
    		messages: {
    			duracion_hp : 'Este campo es requerido',
    			duracion_hnp : 'Este campo es requerido',
    			detalle_secuencia_contenido : 'Este campo es requerido',
    			detalle_desagregado_contenido : 'Este campo es requerido',
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){

    			if(desagregados.length == 0){
    				utils.alert.error('Debe agregar al menos un desagregado de contenido');
    				return;
    			}

    			var data = {
    				id_contenido_unidad_asignatura: $('#id_contenido_unidad_asignatura').val(),
    				id_unidad_asignatura: $('#id_unidad_asignatura').val(),
    				duracion_hp: $('#duracion_hp').val(),
    				duracion_hnp: $('#duracion_hnp').val(),
    				detalle_secuencia_contenido: $('#detalle_secuencia_contenido').val(),
    				desagregados: [],
    			};

    			desagregados.forEach(function (item, index){
    				data.desagregados.push(item.id_desagregado_contenido);
    			});

    			api.post({
    				url: '<?= ruta("cont_unidad.guardar") ?>',
    				data: data,
    				cb: function (response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
    						if(desde_asistente){
    							obtenerContenidosUnidad();
    							utils.modal.hide();
    							return;
    						}
    						if($('#id_contenido_unidad_asignatura').val() == 0){
    							cargarListado(1);
    						}else{
    							cargarListado();
    						}
    						utils.modal.hide();
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				},
    				error: function (){
    					utils.alert.error('Ha ocurrido un error al intentar guardar');
    				}
    			});
    		}
    	});

		$('#modal_btn_primary').off('click').on('click', function (e){
		    e.preventDefault();
		    form.submit();
		});
    });
</script>
