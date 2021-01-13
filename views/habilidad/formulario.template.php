<?php 
$habilidad = $data->fromBody('habilidad', new TblCompo_habilidad());
$nuevo = $data->fromBody('nuevo', true);
$form = new BaseForm();

$form->bs4FormInput('descrip_habilidad','Descripción','descrip_habilidad', $habilidad->descrip_habilidad,true);
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
$form->beginForm('frm_habilidad');
$form->inputHidden('id_compo_habilidad','id_compo_habilidad', $habilidad->id_compo_habilidad);
$form->render('descrip_habilidad');
$form->endForm();
 ?>

<script type="text/javascript">
    $(document).ready(function(){
    	var form = $('#frm_habilidad');
    	form.validate({
    		validClass: "is-valid",
    		errorClass: "is-invalid",
    		messages: {
    			descrip_habilidad: 'La descripción no es válida'
    		}
    	});

    	form.on('submit', function(e){
    		e.preventDefault();
    		if(form.valid()){
	    		$.ajax({
	    		    url: '<?= ruta($nuevo ? "habilidad.nuevo" : "habilidad.editar") ?>',
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