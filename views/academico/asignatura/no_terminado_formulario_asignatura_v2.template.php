<?php
$tiposAsig = [
	'obligatoria' => 'Obligatoria',
	'optativa'    => 'Optativa',
	'libre'       => 'Libre',
];

$modalidades = [
	'presencial' => 'Presencial',
	'en_linea'   => 'En linea',
	'mixta'      => 'Mixta',
];

$form = new Form();
?>

<div class="container">
	<h4><?= $this->nuevo ? 'Nueva asignatura' : 'Editar asignatura' ?></h4>
	<div class="card">
		<div class="card-body">
			<a href="<?= ruta('asignatura', ['page' => $this->page ]) ?>" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> Regresar</a>
		</div>
	</div>
	
	<?php  $form = new Form(); ?>

	<?php $form->begin('frm_asignatura'); ?>

	<?php
		$form->bs4FormInput([
			'type' => 'text',
			'id' => 'nombre_asignatura',
			'label' => 'Nombre',
			'value' => $this->asignatura->nombre_asignatura,
			'required' => true,
		], true);
	?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php $form->bs4FormSelect([
				'id' => 'tipo_asignatura',
				'label' => 'Tipo de asignatura',
				'value' => $this->asignatura->tipo_asignatura,
				'required' => true,
				'option' => $tiposAsig,
			], true); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php $form->bs4FormSelect([
				'id' => 'modalidad',
				'label' => 'Modalidad',
				'value' => $this->asignatura->modalidad,
				'required' => true,
				'option' => $modalidades,
			], true); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'num_unidades',
				'label' => 'Número de unidades',
				'value' => $this->asignatura->num_unidades,
				'required' => true,
			], true) ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'semestre_ubicacion',
				'label' => 'Semestre',
				'value' => $this->asignatura->semestre_ubicacion,
				'required' => !$this->nuevo || $this->asignatura->tipo_asignatura != 'libre',
			], true) ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'horas_duracion',
				'label' => 'Duración (Horas)',
				'value' => $this->asignatura->horas_duracion,
				'required' => true,
			], true) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'horas_presenciales',
				'label' => 'Horas presenciales',
				'value' => $this->asignatura->horas_presenciales,
				'required' => true,
			], true) ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'horas_nopresenciales',
				'label' => 'Horas no presenciales',
				'value' => $this->asignatura->horas_nopresenciales,
				'required' => true,
			], true) ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php $form->bs4FormInput([
				'type' => 'number',
				'id' => 'creditos',
				'label' => 'Créditos',
				'value' => $this->asignatura->creditos,
				'required' => true,
			], true) ?>
		</div>
	</div>

	<?php
		$form->bs4FormTextArea([
			'rows' => 3,
			'id' => 'contextualizacion',
			'label' => 'Contextualización',
			'placeholder' => 'Contextualización',
			'value' => $this->asignatura->contextualizacion,
			'required' => true,
		], true);
	?>

	<?php
		$form->bs4FormTextArea([
			'rows' => 3,
			'id' => 'competencia_asignada',
			'label' => 'Competencia de la asignatura',
			'value' => $this->asignatura->competencia_asignada,
			'required' => true,
		], true);
	?>

	<?php $form->end(); ?>

	<h4>Unidades de la asignatura</h4>
	<hr>


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