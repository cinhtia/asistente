<?php
$asignaturaPE = $data->fromBody('asignatura_pe');
$nuevo = $data->fromBody('nuevo', true);
$asignatura = $data->fromBody('asignatura');
$planesEstudio = $data->fromBody('planes_estudio', []);
$pageIndexAsignaturas = $data->fromBody('page_index_asignaturas', 1);

$pesSel = [];
foreach ($planesEstudio as $key => $pe) {
	$pesSel[] = ['value'=>$pe->id_pe, 'label'=>$pe->nombre_pe];
}

$form = new BaseForm();
$form->bs4FormInput('nombre_asignatura','Asignatura','nombre_asignatura',$asignatura->nombre_asignatura, false,'text','','',false);
$form->bs4FormSelect('id_pe', 'Plan de estudio','id_pe', $pesSel, $nuevo ? '' : $asignaturaPE->id_pe, true);
?>

<div class="container">

	<?php if($data->isError()){?>
	<div class="alert alert-danger">
		<?= $data->getMsj(); ?>
	</div>
	<?php }else if($data->isPost()){ ?>
		<div class="alert alert-success"><?= $data->getMsj() ?></div>
		<script type="text/javascript">
			cargarSubseccionPlanesEstudioAsignatura(<?= $asignatura->id_asignatura ?>, <?= $pageIndexAsignaturas ?>);
		</script>
	<?php } ?>

	<?php 
	$form->beginForm('frm_asignatura_pe');
	$form->inputHidden('id_asignatura_pe','id_asignatura_pe',$asignaturaPE->id_asignatura_pe);
	$form->inputHidden('id_asignatura','id_asignatura',$asignatura->id_asignatura);
	?>

	<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?php $form->render('nombre_asignatura') ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?php $form->render('id_pe') ?>
        </div>
	</div>
	<?php $form->endForm(); ?>
</div>

<script type="text/javascript">
	
	var form =$('#frm_asignatura_pe');

    $(document).ready(function(){
    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			id_pe: 'Debe seleccionar un plan de estudios',
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){
    			$.ajax({
    			    url: '<?= ruta( $nuevo ? "asignatura.nuevo_pe" : "asignatura.editar_pe") ?>',
    			    method: 'post',
    			    data: form.serialize(),
    			    success: function(response){
    			    	utils.modal.contenido(response);
    			    },
    			    error: function(xhr){
    			        utils.modal.error('Ocurrió un error desconocido',4000);
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
