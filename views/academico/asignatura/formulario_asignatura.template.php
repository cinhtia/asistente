<?php
$asignatura = $data->fromBody('asignatura');
$asignatura_pe = $data->fromBody('asignatura_pe', []);
$pes_ids = [];
foreach ($asignatura_pe as $i => $asignatura_pe) {
	$pes_ids[] = $asignatura_pe->id_pe;
}

$pes = TblPlanEstudio::findAll([
	'order'=>'nombre_pe asc'
]);
$nuevo = $data->fromBody('nuevo', true);
$tiposAsig = [
	['value'=>'obligatoria', 'label'=>'Obligatoria'],
	['value'=>'optativa', 'label'=>'Optativa'],
	['value'=>'libre', 'label'=>'Libre'],
];

$modalidades = [
	['value'=>'presencial', 'label'=>'Presencial'],
	['value'=>'en_linea', 'label'=>'En linea'],
	['value'=>'mixta', 'label'=>'Mixta'],
];

$form = new BaseForm();
$form->bs4FormInput('nombre_asignatura','Nombre','nombre_asignatura',$asignatura->nombre_asignatura, true);
// $form->bs4FormSelect('tipo_asignatura', 'Tipo','tipo_asignatura', $tiposAsig, $asignatura->tipo_asignatura, true);
$form->bs4FormSelect('modalidad', 'Modalidad','modalidad', $modalidades, $asignatura->modalidad, true);
$form->bs4FormInput('num_unidades', 'Número de unidades', 'num_unidades', $nuevo ? '' : $asignatura->num_unidades, true,'number');
$form->bs4FormInput('semestre_ubicacion', 'Semestre', 'semestre_ubicacion', $nuevo ? '' : $asignatura->semestre_ubicacion, $nuevo ? true : $asignatura->tipo_asignatura != 'libre','number'); //puede quedar vacío
$form->bs4FormInput('horas_duracion', 'Duración (Horas)', 'horas_duracion', $nuevo ? '' : $asignatura->horas_duracion, true,'number');
$form->bs4FormInput('horas_presenciales', 'Horas presenciales', 'horas_presenciales', $nuevo ? '' : $asignatura->horas_presenciales, true,'number');
$form->bs4FormInput('horas_nopresenciales', 'Horas no presenciales', 'horas_nopresenciales', $nuevo ? '' : $asignatura->horas_nopresenciales, true,'number');
$form->bs4FormInput('creditos', 'Créditos', 'creditos', $nuevo ? '' : $asignatura->creditos, true,'number');
$form->bs4FormTextArea('competencia_asignada','Competencia de la asignatura','competencia_asignada',$asignatura->competencia_asignada,true);
$form->bs4FormTextArea('contextualizacion','Contextualización','contextualizacion',$asignatura->contextualizacion,true);
?>

<div class="container">

	<?php if($data->isPost()){?>
	<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
		<?= $data->getMsj() ?>
	</div>
	<?php if(!$data->isError()){?>
	<script type="text/javascript">
		cargarListado(1);
		utils.alert.success("<?= $data->getMsj() ?>");
	</script>
	<?php }else{?>
		<script type="text/javascript">
		utils.alert.error("<?= $data->getMsj() ?>");
	</script>
	<?php }
	} ?>
	
	<?php 
	$form->beginForm('frm_asignatura');
	$form->inputHidden('id_asignatura','id_asignatura',$asignatura->id_asignatura);
	 ?>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php $form->render('nombre_asignatura') ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="form-group">
			    <label>Tipo<span class="text-danger">*</span></label>
			    <select name="tipo_asignatura" id="tipo_asignatura" required="true" class="form-control" onChange="validarRequeridoSemestre()">
					<option value="">Selecciona</option>
					<?php foreach ($tiposAsig as $ta) { ?>
						<option <?= $asignatura->tipo_asignatura == $ta['value'] ? 'selected' : '' ?> value="<?= $ta['value'] ?>"><?= $ta['label'] ?></option>
					<?php } ?>
			    </select>
			</div>
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php $form->render('modalidad') ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('num_unidades') ?></div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('semestre_ubicacion') ?></div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('horas_duracion') ?></div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('horas_presenciales') ?></div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('horas_nopresenciales') ?></div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><?php $form->render('creditos') ?></div>
	</div>
	
	<?php $form->render('contextualizacion') ?>
	
	<hr>
	<?php $form->render('competencia_asignada') ?>

	<hr class="line-dashed">
	<h4>Planes de estudio</h4>
	<?php foreach ($pes as $i => $pe) { ?>
		<div style="margin-bottom: 5px; display: inline-block; margin-right: 5px; margin-left: 5px;">
			<label for="check_<?= $i ?>" class="cont-ch-normal">
			  <input type="checkbox" <?= in_array($pe->id_pe, $pes_ids) ? 'checked' : '' ?> class="ch-sel" id="check_<?= $i ?>" name="pes[]" value="<?= $pe->id_pe ?>">
			  <span class="checkmark"></span> <?= $pe->nombre_pe ?>
			</label>
		</div>
	<?php } ?>

	<?php $form->endForm(); ?>
</div>

<script type="text/javascript">


	var form =$('#frm_asignatura');
	
	function validarRequeridoSemestre(){
		var opcional = $('#tipo_asignatura').val() == 'libre';
		$('#semestre_ubicacion').prop('required', !opcional);
		if(opcional){
			$('#span_required_semestre_ubicacion').hide();
		}else{
			$('#span_required_semestre_ubicacion').show();
		}
	}

    $(document).ready(function(){
    	utils.modal.setTitle('<?= $asignatura->nombre_asignatura ? 'Editar asignatura '.$asignatura->nombre_asignatura: 'Nueva asignatura'; ?>');
    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			nombre_asignatura: 'El nombre de la asignatura no es válido',
    			tipo_asignatura: 'Debe seleccionar el tipo de asignatura',
    			modalidad: 'Debe seleccionar la modalidad',
    			num_unidades: 'Debe indicar un número de unidads',
    			semestre_ubicacion: 'Debe indicar el semestre',
    			horas_duracion: 'Duración no válida',
    			horas_presenciales: 'Horas presenciales no válidas',
    			horas_nopresenciales: 'Horas no presenciales no válidas',
    			creditos: 'Créditos no válidos',
    			competencia_asignada: 'Competencia asignada no válida',
    			contextualizacion: 'Contextualización no válida',
    			// competencia_corregida: '',
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){
    			$.ajax({
    			    url: '<?= ruta( $nuevo ? "asignatura.nuevo" : "asignatura.editar") ?>',
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
