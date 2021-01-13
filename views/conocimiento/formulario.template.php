<?php 
$conocimiento = $data->fromBody('conocimiento', new TblCompo_conocimiento());
$nuevo = $data->fromBody('nuevo', true);
$form = new BaseForm();

$form->bs4FormInput('descrip_conocimiento','Descripción','descrip_conocimiento', $conocimiento->descrip_conocimiento,true);
?>

<?php if($data->isError()){?>
<div class="alert alert-danger"> <?= $data->getMsj(); ?> </div>
<?php }else if($data->isPost()){ ?>
	<div class="alert alert-success"><?= $data->getMsj() ?></div>
	<script type="text/javascript">
		cargarListado(1);
		utils.alert.success('<?= $data->getMsj(); ?>');
	</script>
<?php } ?>

<?php
$form->beginForm('frm_conocimiento');
$form->inputHidden('id_compo_conocimiento','id_compo_conocimiento', $conocimiento->id_compo_conocimiento);
$form->render('descrip_conocimiento');
$form->endForm();
 ?>

<script type="text/javascript">
    $(document).ready(function(){
    	var form = $('#frm_conocimiento');
    	form.validate({
    		validClass: "is-valid",
    		errorClass: "is-invalid",
    		messages: {
    			descrip_conocimiento: 'La descripción no es válida'
    		}
    	});

    	form.on('submit', function(e){
    		e.preventDefault();
    		if(form.valid()){
	    		$.ajax({
	    		    url: '<?= ruta($nuevo ? "conocimiento.nuevo" : "conocimiento.editar") ?>',
	    		    method: 'post',
	    		    data: form.serialize(),
	    		    success: function(response){
	    				utils.modal.contenido(response);
	    		    },
	    		    error: function(xhr){
	    		      	utils.alert.error('Ha ocurrido un error al actualizar el registro');
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