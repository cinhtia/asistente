<div class="table-responsive">
	<!-- <?php if($this->total_ponderado<100){ ?>
		<div class="alert alert-warning text-center"><strong>Aún no se alcanza el 100% de ponderación. Falta <?= (100-$this->total_ponderado).'%' ?></strong></div>
	<?php }else{ ?>
		<div class="alert alert-success"><strong>100% de ponderación</strong></div>
	<?php } ?> -->
	<table class="table table-hover">
		<colgroup>
			<col width="10%">
			<col width="20%">
			<col width="15%">
			<col width="40%">
			<!-- <col width="10%"> -->
			<col width="15%">
		</colgroup>
		<thead class="thead-dark">
			<tr>
				<th>No</th>
				<th>Nombre</th>
				<th>Herramienta</th>
				<th>Instrucciones</th>
				<!-- <th>Ponderación</th> -->
				<th>Opciones</th>
			</tr>
		</thead>
		<tbody>
			<?php $ponderacion_total = 0; 
			foreach ($this->adas as $index => $item) { 
				$ponderacion_total+=$item->ponderacion;
				?>
				<tr>
					<td><?= $index+1 ?></td>
					<td><?= $item->nombre_ada ?></td>
					<td><?= $item->descripcion_herramienta ?></td>
					<td><?= substr($item->instruccion_ada, 0, 100).(strlen($item->instruccion_ada) > 100 ? '...' : '') ?></td>
					<!-- <td class="text-right"><strong><?= $item->ponderacion."%" ?></strong></td> -->
					<td>
						<div class="dropdown">
						  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						    <button type="button" class="dropdown-item" onclick="formularioAda(<?= $item->id_ada ?>)" ><i class="fa fa-edit"></i> Editar</button>
						    <button type="button" class="dropdown-item" onclick="eliminarAda(<?= $item->id_ada ?>)" ><i class="fa fa-trash-o"></i> Eliminar</button>
						  </div>
						</div>
					</td>
				</tr>	
			<?php } ?>
			<?php if(count($this->adas)==0){ ?>
				<tr>
					<td colspan="6" class="text-center">
						<div class="alert alert-info"><h4>Aún no ha creado ninguna actividad de aprendizaje</h4></div>
					</td>
				</tr>
			<?php }else{ ?>
				<!-- <tr>
					<td colspan="4" class="text-right"><h4>Total ponderado</h4></td>
					<td class="text-right text-success"><h4><?= $ponderacion_total."%" ?></h4></td>
					<td></td>
				</tr> -->
			<?php } ?>
		</tbody>
	</table>
</div>