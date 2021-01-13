<?php 
$form = new Form();
$form->begin('frm_estrategia_ea'); 
$nuevo = $this->ea->id_estrategia_ea == 0;
?>
<div class="container">
	<?php $form->inputHidden([
		'id' => 'id_estrategia_ea',
		'value' => $this->ea->id_estrategia_ea
	]); ?>

	<?php 
	$form->bs4FormTextarea([
		'id'=>'descripcion_ea',
		'label'=>'Descripción de la estrategia enseñanza-aprendizaje',
		'placeholder' => 'Descripción de la estrategia enseñanza-aprendizaje',
		'required' => true,
		'value' => $nuevo ? '' : $this->ea->descripcion_ea,
		],true);
	?>

	<?php 
	$form->bs4FormTextarea([
		'id'=>'explicacion_ea',
		'label'=>'Explicación',
		'placeholder'=>'Explicación',
		'required' => true,
		'value' => $nuevo ? '' : $this->ea->explicacion_ea,
		],true);
	?>
</div>

<?php $form->end(); ?>

<script type="text/javascript">
    $(document).ready(function(){
		var form = $('#frm_estrategia_ea');
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				descripcion_ea: 'La descripción es requerida',
				explicacion_ea: 'La explicación es requerida'
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				api.post({
					url: '<?= ruta("ea.guardar") ?>',
					data: form.serialize(),
					cb: function (resp){
						if(resp.estado){
							utils.alert.success(resp.mensaje);
							if($('#id_estrategia_ea').val() == 0){
								form[0].reset();
							}
							cargarListado(1);
						}else{
							utils.alert.error(resp.mensaje);
						}
					},
					errorMessage: 'Ha ocurrido un error al intentar guardar los datos'
				});
			}
		});

		utils.modal.btn.onClick(function (){
			form.submit();
		});

    });
</script>
