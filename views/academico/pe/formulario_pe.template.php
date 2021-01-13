<?php 
$pe = $data->fromBody('pe');
$nuevo = $data->fromBody('nuevo', true);
?>
<div class="container">
	<?php if($data->isPost()){?>
	<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
		<?= $data->getMsj() ?>
	</div>
		<?php if(!$data->isError()){?>
	<script type="text/javascript">
		cargarListado();
	</script>
		<?php } ?>
	<?php }

	$form = new Form();

	$form->begin('frm_pe');

	$form->inputHidden([ 'id' => 'id_pe', 'value' => $pe->id_pe ]);

	$form->bs4FormInput([
		'type' => 'text',
		'id' => 'nombre_pe',
		'name' => 'nombre_pe',
		'label' => 'Nombre del plan de estudios',
		'value' => $nuevo ? '' : $pe->nombre_pe,
		'required' => true,
	], true);

	$form->bs4FormSelect([
		'id' => 'facultad',
		'name' => 'facultad',
		'label' => 'Facultad',
		'value' => $nuevo ? '' : $pe->facultad,
		'options' => FACULTADES,
		'required' => true,
	], true);

	$form->end();

	// $form->bs4FormInput('nombre_pe','Nombre Plan Estudio','nombre_pe',$pe->nombre_pe,true);
	// $form->beginForm('frm_pe');
	// $form->inputHidden('id_pe','id_pe',$pe->id_pe);
	// $form->render('nombre_pe');

	// $form->endForm();
	?>
</div>

<script type="text/javascript">
	var form = $('#frm_pe');
	$(document).ready(function(){
		utils.modal.setTitle('<?= $nuevo ? "Nuevo Plan de Estudio" : "Editar Plan de Estudio  ".$pe->nombre_pe ?>');

		form.validate({
			messages: {
				nombre_pe: 'Nombre no válido',
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				utils.modal.beginLoading('Guardando...');
				$.ajax({
					url: '<?= ruta($nuevo ? "pe.nuevo" : "pe.editar") ?>',
					method: 'post',
					data: form.serialize(),
					success: function(response){
						// $('#modal_modal_body').html(response);
						utils.modal.contenido(response);
						utils.modal.endLoading('Guardar');
					}, 
					error: function(xhr){
						console.log(xhr);
						utils.modal.endLoading('Guardar');
					}
				});
			}
		});

		$('#modal_btn_primary').off('click').on('click', function (e){
			form.submit();
		});
	});
</script>