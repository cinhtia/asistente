<?php
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_instrumento_eval') ?>
	<?php $form->inputHidden(['id'=>'id_instrumento_eval','value'=>$this->instrumento->id_instrumento_eval]) ?>
	<?php $form->bs4FormTextarea([
		'id' => 'descripcion_instrum_eval',
		'label' => 'Descripción',
		'required' => true,
		'placeholder' => 'Descripción o nombre del instrumento',
		'value' => $this->nuevo ? '' : $this->instrumento->descripcion_instrum_eval,		
	],true); ?>

	<?php $form->bs4FormTextarea([
		'id' => 'explicacion_instrum_eval',
		'label' => 'Explicación',
		'required' => true,
		'placeholder' => 'Explicación',
		'value' => $this->nuevo ? '' : $this->instrumento->explicacion_instrum_eval,		
	],true); ?>

	<?php $form->end() ?>
</div>

<script type="text/javascript">
	var nuevo = <?= $this->nuevo ? 'true' : 'false' ?>;

    $(document).ready(function(){
    	var form = $('#frm_instrumento_eval');

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			descripcion_instrum_eval: 'La descripción es requerida',
    			explicacion_instrum_eval: 'La explicación es requerida',
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){
    			api.post({
    				url: '<?= ruta("instrumeval.guardar") ?>',
    				data: form.serialize(),
    				cb: function (response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
    						cargarListado(1);
    						if(nuevo){ form[0].reset(); $('#id_instrumento_eval').val(0); }
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				}
    			})
    		}
    	});

		utils.modal.btn.onClick(function (){
			form.submit();
		});
    });
</script>