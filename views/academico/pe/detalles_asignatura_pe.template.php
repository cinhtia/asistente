<?php 
$asignatura = $data->fromBody('asignatura');
?>
<div class="container">
	<h4><?= $asignatura->nombre_asignatura ?></h4><br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<dl>
				<dt>Tipo de asignatura</dt>
				<dd><?= $asignatura->obtenerTipoAsignatura() ?></dd>
			</dl>
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<dl>
				<dt>Modalidad</dt>
				<dd><?= $asignatura->obtenerModalidad(); ?></dd>
			</dl>
		</div>
	</div>
	<br>

	<!-- ------------------------------------------------- -->
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Número de unidades</dt>
				<dd><?= $asignatura->num_unidades ?></dd>
			</dl>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Semestre ubicación</dt>
				<dd><?= $asignatura->semestre_ubicacion ?></dd>
			</dl>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Créditos</dt>
				<dd><?= $asignatura->creditos ?></dd>
			</dl>
		</div>
	</div>
	<br>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Duración (Horas)</dt>
				<dd><?= $asignatura->horas_duracion ?></dd>
			</dl>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Horas presenciales</dt>
				<dd><?= $asignatura->horas_presenciales ?></dd>
			</dl>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<dl>
				<dt>Horas no presenciales</dt>
				<dd><?= $asignatura->horas_nopresenciales ?></dd>
			</dl>
		</div>
	</div>
	<br>


	<dl>
		<dt>Competencia asignada: </dt>
		<dd><?= $asignatura->competencia_asignada ?></dd>
	</dl>
	<br>

	<dl>
		<dt>Competencia corregida</dt>
		<dd><?= $asignatura->competencia_corregida ?></dd>
	</dl>
	<br>

	<dl>
		<dt>Contextualización</dt>
		<dd><?= $asignatura->contextualizacion ?></dd>
	</dl>
	<br>
	
	<!-- ------------------------------------------------- -->

</div>

<script type="text/javascript">
    $(document).ready(function(){
    	$('#modal_btn_primary').hide();
    });
</script>