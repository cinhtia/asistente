<?php
$asignaturas = $data->fromBody('asignaturas', []);
$planesEstudio = $data->fromBody('pe',[]);
$pePorAsignatura = $data->fromBody('pe_asignatura', []);

$page = $data->fromBody('page',1);
$count = $data->fromBody('count',1); 
$total = $data->fromBody('total',1);
?>
<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion", count($asignaturas)) ?>

<table class="table table-bordered table-striped table-hover">
	<colgroup>
		<col width="30%">
		<col width="15%">
		<col width="10%">
		<col width="25%">
		<col width="20%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Nombre</th>
			<th>Tipo</th>
			<th>Modalidad</th>
			<th>Planes de estudio</th>
			<th>Opciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($asignaturas as $key => $asignatura) { ?>
			<tr>
				<td><?= $asignatura->nombre_asignatura ?></td>
				<td><?= $asignatura->tipo_asignatura ?></td>
				<td><?= $asignatura->modalidad == 'en_linea' ? 'En linea' : ( $asignatura->modalidad == 'presencial' ? 'Presencial' : 'Mixta' ) ?></td>
				<td>
					<?php
					foreach ($pePorAsignatura[$key] as $key2 => $peA) {
						echo "- ".$peA['nombre_pe']."<br>";
					}
					?>
					<!-- <div class="text-center2">
						<a href="#" data-id='<?= $asignatura->id_asignatura ?>' class="btn btn-sm btn-primary btn_pes_asignatura" data-toggle='tooltip' data-placement='top' title='Ver planes de estudio asociados'>
							<i class='fa fa-arrow-right'></i>
						</a>
					</div> -->
				</td>
				
				<td>
				    <button type="button" data-toggle="tooltip" data-placement="top" title="Ver competencias genéricas" class="btn btn-sm btn-secondary" onclick="listaCGS(<?= $asignatura->id_asignatura ?>)" href="#">CG</button>
				    <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Competencias disciplinares" onclick="listaCDS(<?= $asignatura->id_asignatura ?>)" href="#">CD</button>
				    <a href="<?= ruta('unidad', ['id_asignatura' => $asignatura->id_asignatura, 'pageBack' => $page]) ?>" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Unidades">U</a>
					<div class="dropdown" style="display: inline-block;">
					  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    <i class="fa fa-cog"></i>
					  </button>
					  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
					    <button type="button" class="dropdown-item" onclick="formularioAsignatura(<?= $asignatura->id_asignatura ?>)"><i class="fa fa-edit"></i> Editar </button>
					    <button type="button" class="dropdown-item" onclick="eliminarAsignatura(<?= $asignatura->id_asignatura ?>)"><i class="fa fa-trash"></i> Eliminar </button>
					    <!-- <button type="button" class="dropdown-item" onclick="abrirModalPEs(< ?= $asignatura->id_asignatura ? >)"><i class="fa fa-list"></i> Planes de estudio </button> -->
					    <!-- <div class="dropdown-divider" ></div> -->
					  </div>
					</div>	
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion") ?>