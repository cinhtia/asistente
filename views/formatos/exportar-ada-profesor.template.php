<ul>
	<li>
		<strong>Estrategias de Enseñanza-Aprendizaje:</strong>
		<ul>
			<?php foreach ($this->estrategias_ea as $index => $estrategia_ea): ?>
				<li><?= $estrategia_ea->descripcion_ea ?></li>
			<?php endforeach ?>
			<li></li>
		</ul>
	</li>
	<li>
		<strong>Agente de evaluación: </strong>
		<ul>
			<?php $agentes = explode(",", $this->ada->agentes_eval); ?>
			<?php foreach ($agentes as $agente): ?>
				<li><?= ucwords($agente) ?></li>
			<?php endforeach ?>
		</ul>
	</li>
	<li>
		<strong>Momento de evaluación:</strong>
		<?php 
		if($this->ada->momento_eval == 'diagnostico'){
			print " Diagnóstico";
		}else if($this->ada->momento_eval == 'formativa'){
			print " Formativa";
		}else if($this->ada->momento_eval == 'sumativa'){
			print " Sumativa";
		}
		?>
	</li>
	<li><strong>Instrumento de evaluación: </strong><?= $this->instrumento_eval->descripcion_instrum_eval ?></li>
	<li>
		<strong>Herramienta sugerida: </strong> <?= $this->herramienta ? $this->herramienta->descripcion_herramienta : $this->ada->otra_herramienta ?>
	</li>
	<li>
		<strong>Duración estimada: </strong> <?= $this->ada->duracion_horas.' '.($this->ada->duracion_horas == 1 ? 'hora' : 'horas') ?>
	</li>
	<!-- <li>
		<strong>Ponderación de la actividad: </strong><?= $this->ada->ponderacion ?>%
	</li> -->
	<h4>Observaciones para la Unidad <?= $this->unidad->num_unidad.'. '.$this->unidad->nombre_unidad ?></h4>
	<ul>
		<?php foreach ($this->contenidos_unidad as $index => $contenido_unidad): ?>
			<li>
				Tema <?= $this->unidad->num_unidad ?>.<?= ($index+1) ?> <?= $contenido_unidad->detalle_secuencia_contenido ?>
				<ul>
					<li>
						Estrategias de Enseñanza-Aprendizaje: <?= count($contenido_unidad->Extra['eas']) ?> <?= count($contenido_unidad->Extra['eas']) > 0 ? '(' : '' ?><?= implode(", ", $contenido_unidad->Extra['eas']) ?><?= count($contenido_unidad->Extra['eas']) > 0 ? ')' : '' ?>
					</li>
					<li>
						Agentes: <?= count($contenido_unidad->Extra['agentes']) ?> <?= count($contenido_unidad->Extra['agentes']) > 0 ? '(' : '' ?><?= implode(", ", $contenido_unidad->Extra['agentes']) ?><?= count($contenido_unidad->Extra['agentes']) > 0 ? ')' : '' ?>
					</li>
					<li>
						Momentos: <?= count($contenido_unidad->Extra['momentos']) ?> <?= count($contenido_unidad->Extra['momentos']) >0 ? '(' : '' ?><?= implode(", ", $contenido_unidad->Extra['momentos']) ?><?= count($contenido_unidad->Extra['momentos']) >0 ? ')' : '' ?>
					</li>
					<li>
						Herramientas: <?= count($contenido_unidad->Extra['herramientas']) ?> <?= count($contenido_unidad->Extra['herramientas']) > 0 ? '(' : '' ?><?= implode(", ", $contenido_unidad->Extra['herramientas']) ?><?= count($contenido_unidad->Extra['herramientas']) > 0 ? ')' : '' ?>
					</li>
				</ul>
			</li>
		<?php endforeach ?>
	</ul>
</ul>