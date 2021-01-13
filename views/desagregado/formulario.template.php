<?php
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_desagregado') ?>
	<?php $form->inputHidden([
		'id'=>'id_desagregado_contenido',
		'value' => $this->desagregado->id_desagregado_contenido,
	]) ?>
	<?php $form->bs4FormInput([
		'id' => 'descripcion',
		'label' => 'Descripción desagregado',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->desagregado->descripcion,
	], true); ?>
	<?php $form->end() ?>
</div>

<script type="text/javascript">
	var nuevo = <?= isset($this->nuevo) && $this->nuevo ? 'true' : 'false' ?>;

    $(document).ready(function(){
        utils.modal.setTitle('<?= $this->desagregado->id_desagregado_contenido ? 'Editar desagregado '.$this->desagregado->descripcion : 'Nuevo desagregado de contenido' ?>');
    	var form = $('#frm_desagregado');

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			descripcion: 'La descripción es obligatoria'
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){
    			api.post({
    				url: '<?= ruta("desagregado.guardar") ?>',
    				data: form.serialize(),
    				cb: function (response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
                            <?php if($this->desde_form_contenido){ ?>
                                desagregadoCreado(response.data || response.extra);
                                utils.modal2.hide();
                            <?php }else{ ?>
    						  cargarListado(1);
                            <?php } ?>
    						if(nuevo){ form[0].reset(); }
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				}
    			})
    		}
    	});

        <?php if($this->desde_form_contenido){ ?>
            utils.modal2.btn.onClick(function (){
                form.submit();
            });
        <?php }else{ ?>
    		utils.modal.btn.onClick(function (){
    			form.submit();
    		});
        <?php } ?>

    });
</script>