<?php 

$form = new Form();
$objPEs = TblPlanEstudio::findAll(['order' => 'nombre_pe asc']);
$optionsPEs = [];
foreach ($objPEs as $item) {
	$optionsPEs[$item->id_pe] = $item->nombre_pe;
}

 ?>
<div class="container">
	<?php $form->begin('frm_cd'); ?>
	<?php $form->inputHidden(['id'=>'id_competencia_disciplinar','value'=>$this->cd->id_competencia_disciplinar]) ?>

	<?php 
	$form->bs4FormInput([
		'id'=>'descripcion',
		'label'=>'Descripción',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->cd->descripcion,
		],true);
	 ?>

	<?php
	$form->bs4FormSelect([
		'id'=>'plan_estudio_id',
		'label'=>'Plan de estudios',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->cd->plan_estudio_id,
		'options' => $optionsPEs
		],true);
	 ?>

	<?php $form->end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		var form = $('#frm_cd');

		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				descripcion: 'La descripción es obligatoria',
				plan_estudio_id: 'El plan de estudios es obligatorio'
			}
		});

		$('#frm_cd').on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				$.ajax({
				    url: '<?= ruta("cd.guardar") ?>',
				    method: 'post',
				    data: form.serialize(),
				    success: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							if($('#id_competencia_disciplinar').val() == 0){
								form[0].reset();
							}
							cargarListado();
						}else{
							utils.alert.error(response.mensaje);
						}
				    },
				    error: function(xhr){
				        utils.alert.error('Ha ocurrido un error al intentar guardar la competencia');
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