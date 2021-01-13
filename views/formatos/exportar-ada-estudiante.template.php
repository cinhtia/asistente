<div>
	<h1 style="text-align: center;"><?= $this->asignatura->nombre_asignatura.' - Unidad '.$this->unidad->num_unidad.'. '.$this->unidad->nombre_unidad ?></h1>
	<h3>ADA 1. <?= $this->ada->nombre_ada ?></h3>
	
	<h4>Resultados de aprendizaje</h4>
	<ol>
		<?php foreach ($this->competencias_resultado as $competencia) {?>
			<li><?= $competencia->competencia_editable ?></li>
		<?php } ?>
	</ol>
	
	<h4>Instrucción</h4>
	<p><?= $this->ada->instruccion_ada ?></p>
	
	<h4>Procedimiento (pasos a seguir)</h4>
	<p><?= $this->ada->procedimiento_ada ?></p>
	
	<h4>Productos a entregar</h4>
	<ul>
		<?php foreach ($this->productos as $index => $producto): ?>
			<li><?= $producto->nombre ?></li>
		<?php endforeach ?>
	</ul>
	
	<h4>Recursos y materiales de apoyo</h4>
	<ul>
		<?php foreach ($this->recursos as $index => $recurso): ?>
			<li><?= $recurso->nombre ?></li>
		<?php endforeach ?>
	</ul>

	<h4>Rúbrica</h4>
	<div>
		<?php if ($this->plantilla_rubrica): ?>
			<?= $this->plantilla_rubrica->generarTablaHtml() ?>
		<?php else: ?>
			<p style="text-align: center;">No se ha seleccionado una rúbrica</p>
		<?php endif ?>
	</div>
	
	<h4>Fecha y hora de entrega</h4>
	<p>Entregar antes del <?= Helpers::fechaFormalLocal($this->ada->fecha_fin_ada, "%A %e de %B, %l:%M %p") ?></p>
</div>