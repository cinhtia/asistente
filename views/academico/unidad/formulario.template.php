<?php
$nuevo= $this->nuevo; 
$optsAsignatura = Helpers::options($this->asignaturas, 'id_asignatura','nombre_asignatura',true);
$competencia_unidad = $this->competencia_unidad;
?>
<div class="container">
	<?php if($data->isError()){ ?>
	<div class="alert alert-danger">
		<i class="fa fa-alert"></i> <?= $data->getMsj(); ?>
	</div>
	<?php } ?>
	
	<?php 
	$form = new Form(); 
	$form->begin('frm_unidad_asignatura');
	$form->inputHidden(['id'=>'id_unidad_asignatura','value'=>$this->nuevo ? 0 : $this->ua->id_unidad_asignatura]);
	?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php 
			$form->bs4FormSelect([
				'id' => 'id_asignatura',
				'options' => $optsAsignatura,
				'value' => $this->nuevo ? ($this->asignatura ? $this->asignatura->id_asignatura : '' ) : $this->ua->id_asignatura,
				'required' => true,
				// 'enabled' => $this->asignatura == null,
				'label' => 'Asignatura asociada',
				], true);
			?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php 
			$opciones = [];
			// if(!$this->nuevo){
			// 	$nUnidad = $this->ua->num_unidad;
			// 	foreach ($this->asignaturas as $asign) {
			// 		if($asign->id_asignatura == $this->ua->id_asignatura){
			// 			for ($i=1; $i <= $asign->num_unidades; $i++) { 
			// 				$opciones[$i] = "Unidad $i";
			// 			}
			// 		}
			// 	}

			// }
			$form->bs4FormSelect([
				'id' => 'num_unidad',
				'options' => $opciones,
				'value' => $this->nuevo ? '' : $this->ua->num_unidad,
				'required' => true,
				'label' => 'Número de unidad',
				], true);
			?>
		</div>
	</div>
	
	<div id="contenedor_campos_form">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<?php 
				$form->bs4FormInput([
					'id'=>'nombre_unidad',
					'label'=>'Nombre de la unidad',
					'required' => true,
					'value' => $nuevo ? '' : $this->ua->nombre_unidad,
					],true);
				 ?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<?php 
				$form->bs4FormInput([
					'id'=>'duracion_unidad_hp',
					'type' => 'number',
					'label'=>'Horas presenciales',
					'required' => true,
					'min' => 0,
					'value' => $nuevo ? '' : $this->ua->duracion_unidad_hp,
					],true);
				 ?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<?php 
				$form->bs4FormInput([
					'type' => 'number',
					'id'=>'duracion_unidad_hnp',
					'label'=>'Horas no presenciales',
					'required' => true,
					'min' => 0,
					'value' => $nuevo ? '' : $this->ua->duracion_unidad_hnp,
					],true);
				 ?>
			</div>
		</div>
	</div>

	<?php 
	$form->bs4FormTextArea([
		'id' => 'competencia_unidad',
		'label' => 'Competencia de la unidad',
		'required' => true,
		'value' => $competencia_unidad ? $competencia_unidad->competencia_editable : '',
	], true);
	?>

	<?php $form->end(); ?>
</div>


<script type="text/javascript">
	var esNuevaAsignatura = <?= $this->nuevo ? 'true' : 'false' ?>;
	var asignatura = <?= $this->asignatura ? json_encode($this->asignatura) : "null" ?>;

	var asignaturaTmp = <?= json_encode($this->ua); ?>;
	var numUnidadesAsign = <?= json_encode($this->asignaturas) ?>;

	function generarUnidades(id, numUnidad){
		api.get({
			url: '<?= ruta("unidad.unidades_existentes") ?>',
			data: { id_asignatura: id },
			success: function(response){
				if(response.estado){
					var obj = numUnidadesAsign.find(function(item){ return item.id_asignatura == id });
					if(obj){
						var listadoNumsUnidades = [{id: '',label: 'Selecciona'}];
						for (var j = 1; j <= obj.num_unidades; j++) {
							var existeUnidad = response.data.find(function(item){ return item.num_unidad == j; });
							if(!existeUnidad || (numUnidad && j == numUnidad)){
								listadoNumsUnidades.push({
									id: j,
									label: 'Unidad '+j,
								});
							}
						};

						$('#num_unidad').fill(listadoNumsUnidades);
					}
					if(numUnidad){
						$('#num_unidad').val(numUnidad);
					}
				}else{
					utils.alert.warning(response.mensaje);
				}
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al obtener las unidades faltantes por crear')
			}
		});
	}

    $(document).ready(function(){

    	utils.modal.setTitle('<?= $nuevo ? "Nueva unidad de asignatura" : "Editar unidad ".$this->ua->nombre_unidad ?>');

		$('#id_asignatura').on('change', function (){
			var id = $(this).val();
			if(id != ""){
				$('#contenedor_campos_form').show();
				generarUnidades(id);
			}else{
				numUnidadesAsign = [];
				$('#contenedor_campos_form').hide();
			}
		});


		var form = $('#frm_unidad_asignatura');
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				id_asignatura: 'Debes seleccionar una asignatura',
				num_unidad: 'Debes seleccionar el número de unidad',
				nombre_unidad: 'Debes ingresar el nombre de la unidad',
				duracion_unidad_hp: 'Debes ingresar las horas presenciales',
				duracion_unidad_hnp: 'Debes ingresar las horas no presenciales',
				competencia_unidad: 'Debes ingresar la competencia de la unidad',
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				$('#modal_btn_primary').prop('disabled', true);
				$.ajax({
				    url: '<?= ruta("unidad.guardar") ?>',
				    method: 'post',
				    data: form.serialize(),
				    success: function(response){
						if(response.estado){
							cargarListado(1);
							utils.alert.success(response.mensaje);
							<?php if($nuevo){ ?>
								form[0].reset();
							<?php } ?>
						}else{
							utils.alert.error(response.mensaje);
						}
						$('#modal_btn_primary').prop('disabled', false);
				    },
				    error: function(xhr){
				    	utils.alert.error('Ha ocurrido un error al intentar guardar los datos');
				        $('#modal_btn_primary').prop('disabled', false);
				    }
				});
			}
		});

		$('#modal_btn_primary').off('click').on('click', function (e){
		    e.preventDefault();
		    form.submit();
		});

		if(asignatura){
			generarUnidades(asignatura.id_asignatura);
		}else if(!esNuevaAsignatura && asignaturaTmp){
			generarUnidades(asignaturaTmp.id_asignatura, asignaturaTmp.num_unidad);
		}

    });
</script>

