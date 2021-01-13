<?php
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_producto') ?>
	<?php $form->inputHidden(['id'=>'id_producto','value'=>$this->producto->id_producto]) ?>
	<?php $form->bs4FormInput([
		'id' => 'nombre',
		'label' => 'Nombre',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->producto->nombre
	], true); ?>

	<?php $form->bs4FormTextarea([
		'id' => 'descripcion',
		'label' => 'Descripción',
		// 'required' => true,
		'value' => $this->nuevo ? '' : $this->producto->descripcion
	], true); ?>

	<?php $form->end() ?>
</div>

<script type="text/javascript">
	var nuevo = <?= $this->nuevo ? 'true' : 'false' ?>;

    $(document).ready(function(){
    	var form = $('#frm_producto');

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			nombre: 'El nombre es requerido',
    			// descripcion: 'La descripción es requerida'
    		}
    	});

    	form.on('submit', function (e){
            e.preventDefault();
    		if(form.valid()){
    			api.post({
    				url: '<?= ruta("producto.guardar") ?>',
    				data: form.serialize(),
    				cb: function (response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
    						cargarListado(1);
    						if(nuevo){ form[0].reset(); }
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