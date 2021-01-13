<?php $form = new Form(); ?>
<?php $form->begin('frm_competencia'); ?>
<?php $form->inputHidden(['id'=>'id_competencia2','value'=>$this->competencia->id_competencia]) ?>

<div class="row">

	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
		<?php 
		$form->bs4FormSelect([
			'id' => 'id_asignatura2',
			'label' => 'Asignatura',
			'options' => Helpers::options($this->asignaturas, 'id_asignatura', 'nombre_asignatura', true),
			'value' => $this->nuevo ? '' : $this->competencia->id_asignatura,
			'required' => true
		], true); 
		?>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
		<?php $form->bs4FormSelect([
				'id' => 'tipo_competencia2',
				'label' => 'Tipo de competencia',
				'options' => ['asignatura'=>'Asignatura','unidad'=>'Unidad'],
				'value' => $this->nuevo ? '' : $this->competencia->tipo_competencia,
				'required' => true
			], true); 
		?>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" id="col_unidad">
		<?php
		$opts=[]; 
		if(!$this->nuevo && $this->competencia->tipo_competencia == 'unidad'){
			$totalUnidades = 0;
			for ($i=0; $i < count($this->asignaturas); $i++) { 
				if($this->asignaturas[$i]->id_asignatura == $this->competencia->id_asignatura){
					$totalUnidades = $this->asignaturas[$i]->num_unidades;
					break;
				}
			}

			for ($i=0; $i < $totalUnidades; $i++) { 
				$opts[] = "Unidad ".($i+1);
			}
		}
		?>
		<?php $form->bs4FormSelect([
				'id' => 'num_unidad2',
				'label' => 'Unidad',
				'options' => $opts,
				'value' => $this->nuevo ? '' : $this->competencia->num_unidad,
				'required' => $this->competencia->tipo_competencia == 'unidad'
			], true);
		?>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" id="col_competencia">
		<?php $form->bs4FormTextarea([
			'id' => 'competencia_editable2',
			'label' => 'Competencia',
			'required' => true,
			'placeholder' => 'Escriba aquí la competencia de la Unidad',
			'value' => $this->nuevo ? '' : $this->competencia->competencia_editable,
		],true); ?>
	</div>
</div>
<?php $form->end(); ?>

<script type="text/javascript">
	var redirigirMisComps = <?= $this->redirigirMisCompetencias ? 'true' : 'false' ?>;
	var colUnidad = $('#col_unidad');
	var colCompetencia = $('#col_competencia');
	var form = $('#frm_competencia');
	var asignaturas = <?= json_encode($this->asignaturas) ?>;
	var contUnidades = $('#ch_unidades');

	function tipoAsignaturaSeleccionada(){
		var idApe = $('#id_asignatura2').val();
		for (var i = 0; i < asignaturas.length; i++) {
			if(asignaturas[i].id_asignatura == idApe){
				return asignaturas[i].tipo_asignatura;
			}
		}
		return '';
	}

	function enviarDatos(data){
		api.post({
			url: '<?= ruta("competencia2.guardar") ?>',
			data: data,
			cb: function (response){
				if(response.estado){
					utils.alert.success(response.mensaje);
					if(data.id_competencia == 0){
						form[0].reset();
						$('#id_competencia2').val(0);
					}
					if(redirigirMisComps){
						setTimeout(function(){
							window.location.replace("<?= ruta('competencia') ?>");
						}, 3000);
					}else{
						cargarListado();
					}
				}else{
					utils.alert.error(response.mensaje);
				}
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al intentar guardar los datos');
			}
		});
	}

	function crearUnidades(num_unidades){
		contUnidades.html('');
		for(var i=1;i<=num_unidades;i++){
			contUnidades.append('<label><input type="checkbox" class="ch_unidad" value="'+i+'" /> Unidad '+i+'</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		}
	}

    $(document).ready(function(){
    	
    	if($('#tipo_competencia2').val() != 'unidad'){
    		colUnidad.hide();
    		colCompetencia.removeClass('col-md-8 col-lg-8');
    		colCompetencia.addClass('col-md-12 col-lg-12');
	    	
	    	if($('#id_competencia2').val() != 0){
	    		$('#contenedor_cgs').show();
	    		if(tipoAsignaturaSeleccionada() == 'obligatoria'){
	    			contUnidades.hide();
	    		}else{
	    			contUnidades.show();
	    		}
	    		llenarCgs();
	    	}
    	}

		
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				tipo_competencia2: 'Este campo es requerido',
				id_asignatura2: 'Este campo es requerido',
				num_unidad2: 'Este campo es requerido',
				competencia_editable2: 'Este campo es requerido',
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){

				var data = {
					id_competencia 			: $('#id_competencia2').val(),
					tipo_competencia 		: $('#tipo_competencia2').val(),
					id_asignatura 			: $('#id_asignatura2').val(),
					num_unidad 				: $('#num_unidad2').val(),
					competencia_editable2 	: $('#competencia_editable2').val(),
				};
				enviarDatos(data);
			}
		});

		utils.modal.btn.onClick(function (){
			form.submit();
		});

		$('#tipo_competencia2').on('change', function (e){
			var esUnidad = $(this).val() == 'unidad';
			$('#label_for_num_unidad').html(esUnidad ? 'Unidad <span class="text-danger">*</span>' : 'Unidad');
			$('#num_unidad2').prop('required', esUnidad)
			if(esUnidad){
				colUnidad.show();
    			colCompetencia.removeClass('col-md-12 col-lg-12');
				colCompetencia.addClass('col-md-8 col-lg-8');
				contUnidades.hide();
			}else{
				colUnidad.hide();
				colCompetencia.removeClass('col-md-8 col-lg-8');
    			colCompetencia.addClass('col-md-12 col-lg-12');
    			var ta = tipoAsignaturaSeleccionada();
    			if(ta && ta != 'obligatoria')
    				contUnidades.show();
    			else
    				contUnidades.hide();
			}
		});

		$('#id_asignatura2').on('change', function (e){
			var id = $(this).val();
			if(id){
				let ta = tipoAsignaturaSeleccionada();
				if(ta == 'obligatoria'){
					contUnidades.hide();
				}else{
					contUnidades.show();
				}
				var num_unidades = 0;
				asignaturas.forEach(function (item, index){
					if(item.id_asignatura == id){
						num_unidades = item.num_unidades;
						return;
					}
				});

				if(num_unidades>0){
					var opts = [{id: '', label: 'Seleccione'}];
					for (var i = 0; i < num_unidades; i++) {
						opts.push({id: (i+1), label: 'Unidad '+(i+1)});
					}
					$('#num_unidad2').fill(opts);
					crearChUnidades(num_unidades);
					return;
				}
			}
			$('#num_unidad2').clear([{id: '',label:'Seleccione'}]);
		});

    });
</script>