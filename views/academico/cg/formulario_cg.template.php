<?php
$cg = $data->fromBody('cg');
$nuevo = $data->fromBody('nuevo', true);

$form = new BaseForm();
$form->bs4FormInput('descripcion_cg','Descripción','descripcion_cg', $cg->descripcion_cg, true);


?>
<div class="container">
	<?php if($data->isError()){?>
	<div class="alert alert-danger">
		<?= $data->getMsj(); ?>
	</div>
	<?php }else if($data->isPost()){ ?>
		<div class="alert alert-success"><?= $data->getMsj() ?></div>
		<script type="text/javascript">
			cargarListado(1);
		</script>
	<?php } ?>

	<?php 
	$form->beginForm('frm_cg');
	$form->inputHidden('id_cg','id_cg',$cg->id_cg);
	$form->render('descripcion_cg');
	$form->endForm();
	?>

</div>


<script type="text/javascript">

    $(document).ready(function(){

    	var form = $('#frm_cg');

    	form.validate({
            validClass: 'is-valid',
            errorClass: 'is-invalid',
    		messages: {
    			descripcion_cg: 'La descripción de la competencia genérica no es válida',
    		}
    	});

    	form.on('submit', function(e){
    		e.preventDefault();
    		if(form.valid()){
    			$.ajax({
    			    url: '<?= ruta($nuevo ? "cg.nuevo" : "cg.editar") ?>',
    			    method: 'post',
    			    data: form.serialize(),
    			    success: function(response){
    					utils.modal.contenido(response);
    			    },
    			    error: function(xhr){
    			        utils.modal.error("Ocurrió un error desconocido");
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
