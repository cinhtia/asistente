<?php
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_recurso') ?>
	<?php $form->inputHidden(['id'=>'id_recurso','value'=>$this->recurso->id_recurso]) ?>
	<?php $form->bs4FormInput([
		'id' => 'nombre',
		'label' => 'Nombre',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->recurso->nombre
	], true); ?>

	<?php $form->bs4FormTextarea([
		'id' => 'descripcion',
		'label' => 'Descripción',
		// 'required' => true,
		'value' => $this->nuevo ? '' : $this->recurso->descripcion
	], true); ?>
	<?php $form->end() ?>
</div>

<script type="text/javascript">
	var nuevo = <?= $this->nuevo ? 'true' : 'false' ?>;

    $(document).ready(function(){
    	var form = $('#frm_recurso');

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
    			api.post({
    				url: '<?= ruta("recurso.guardar") ?>',
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