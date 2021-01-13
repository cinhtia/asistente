<?php 
$user = Sesion::obtener();
?>
<div class="container" style="margin-top: 15px; margin-bottom: 25px;">
	<fieldset>
		<legend>Bienvenido(a) <?= $user->nombre ?> <small><?= $user->tipo_usuario ?></small></legend>
	</fieldset>
	<h4 class="mt-25 mb-0">Evaluaciones en proceso</h4>
	<hr class="m-0">
	<div>
	<!--	<a href="<?= ruta('usuario.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-group fa-3x"></i><br> Institución
		</a>
		<a href="<?= ruta('academico') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Planes de estudio
		</a>
		<a href="<?= ruta('academico') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Asignaturas
		</a>
-->
		<a href="<?= ruta('competencia') ?>" class="btn btn-primary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Evaluaciones en proceso
		</a>
<!--
		<a href="<?= ruta('usuario.asignatura') ?>" class="btn btn-success btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Mis materias asignadas
		</a>
	</div>
	<h4 class="mt-25 mb-0">Usuarios</h4>
	<hr class="m-0">
	<div>
		<a href="<?= ruta('usuario.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-group fa-3x"></i><br> Usuarios
		</a>
		<a href="<?= ruta('permiso') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Permisos
		</a>
		<a href="<?= ruta('modulo') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Módulos
		</a>

	
	</div>
	
	<h4 class="mt-25 mb-0">Competencias</h4>
	<hr class="m-0">
	<div>
		<a href="<?= ruta('competencia') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Resultados de aprendizaje en proceso
		</a>
		<a href="<?= ruta('academico') ?>?seccion=4" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Competencias genéricas
		</a>
	</div>

	<h4 class="mt-25 mb-0">Académico</h4>
	<hr class="m-0">
	<div>
		<a href="<?= ruta('academico') ?>?seccion=2" class="btn btn-secondary btn-custom">
			<i class="fa fa-book fa-3x"></i><br> Planes de estudio
		</a>
		<a href="<?= ruta('academico') ?>?seccion=3" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Asignaturas
		</a>
		<a href="<?= ruta('unidad') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Unidades
		</a>
	</div>

	<h4 class="mt-25 mb-0">Catálogos</h4>
	<hr class="m-0">
	<div>
		<a href="<?= ruta('verbo.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Verbos
		</a>

		<a href="<?= ruta('contenido.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Contenidos
		</a>

		<a href="<?= ruta('contexto.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Contextos
		</a>

		<a href="<?= ruta('criterio.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Criterios
		</a>

		<a href="<?= ruta('conocimiento') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Conocimientos
		</a>

		<a href="<?= ruta('habilidad') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Habilidades
		</a>

		<a href="<?= ruta('actitud_valor.index') ?>" class="btn btn-secondary btn-custom">
			<i class="fa fa-list fa-3x"></i><br> Actitudes y valores
		</a>
	</div>
-->
</div>

<script type="text/javascript">
    $(document).ready(function(){
		
    });
</script>