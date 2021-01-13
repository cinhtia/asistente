<?php
$page = $data->fromBody('page_asignatura', 1);
$peAsignaturas = $data->fromBody('pes_asignatura',[]);
$asignatura = $data->fromBody('asignatura');
?>
<div class="container">
	<h4>Planes de estudio que incluyen la asignatura <strong><?= $asignatura->nombre_asignatura ?></strong></h4>
	<nav aria-label="breadcrumb">
	  	<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#" id="btn_regresar_asignaturas">Asignaturas</a></li>
			<li class="breadcrumb-item active" aria-current="page">Planes de estudio</li>
		</ol>
	</nav>
	<div class="card">
		<div class="card-body">
			<a href="#" class="btn btn-secondary" id="btn_regresar_asignaturas2">
				<i class="fa fa-arrow-left"></i> Regresar
			</a>

			<a href="#" class="btn btn-primary" id="btn_agregar_pe_asignatura" data-idasignatura="<?= $asignatura->id_asignatura ?>" data-toggle="tooltip" data-placement="top" title="Asignar nuevo plan estudio">
				<i class="fa fa-plus"></i>
			</a>

			<a href="#" class="btn btn-secondary" id="btn_recargar_listado_pe_asignatura" data-toggle="tooltip" data-placement="top" title="Recargar listado"><i class="fa fa-refresh"></i></a>
		</div>
	</div>
	<br>
	<table class="table table-hover table-bordered table-striped">
		<colgroup>
			<col width="80%">
			<col width="20%">
		</colgroup>
		<thead class="thead-dark" style='width: 100%;'>
			<tr>
				<th>Plan de estudio</th>
				<th>Opciones</th>
			</tr>
		</thead>
	
		<tbody>
			<?php foreach ($peAsignaturas as $key => $peAsignatura) { ?>
				<tr id="asignatura_pe_<?= $peAsignatura->id ?>">
					<td><?= $peAsignatura->PlanEstudio->nombre_pe ?></td>
					<td>
						<div class="dropdown">
						  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="fa fa-cog"></i>
						  </button>
						  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						    <!-- <a class="dropdown-item btn_editar_pe_asignatura" data-id="<?= $peAsignatura->id_asignatura_pe ?>" href="#"><i class="fa fa-edit"></i> Editar</a> -->
						    <a class="dropdown-item btn_eliminar_pe_asignatura" data-id="<?= $peAsignatura->id_asignatura_pe ?>" href="#"><i class="fa fa-trash"></i> Eliminar</a>
						  </div>
						</div>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if(count($peAsignaturas)==0){ ?>
	<div class="alert alert-info">No se encontraron registros</div>
	<?php } ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        

    	$('#btn_regresar_asignaturas').off('click').on('click', function (e){
    	    e.preventDefault();
    		cargarSeccionAsignaturas(<?= $page ?>, '');
    	});

    	$('#btn_regresar_asignaturas2').off('click').on('click', function (e){
    	    e.preventDefault();
    		cargarSeccionAsignaturas(<?= $page ?>, '');
    	});

    	$('#btn_agregar_pe_asignatura').off('click').on('click', function (e){
    	    e.preventDefault();
    	    var idAsignatura = $(this).data('idasignatura');
    	    utils.modal.remote({
    	    	method: 'get',
    	    	url: '<?= ruta("asignatura.nuevo_pe") ?>',
    	    	data: { id_asignatura: idAsignatura },
    	    	modal_options: {
    	    		titulo: 'Nuevo plan de estudio - asignatura',
    	    		btn_primary: 'Guardar'
    	    	},
    	    	error: function(){
    	    		alert('Error desconocido 1');
    	    	}
    	    });
    	
    	});

    	$('#btn_recargar_listado_pe_asignatura').off('click').on('click', function (e){
    	    e.preventDefault();

    	    cargarSubseccionPlanesEstudioAsignatura(<?= $asignatura->id_asignatura ?>, <?= $page ?>);
    	
    	});


    	$('.btn_eliminar_pe_asignatura').off('click').on('click', function (e){
    	    e.preventDefault();
    		var id = $(this).data('id');

    		utils.modal.confirm({
    			titulo: 'Confirmación',
    			contenido: '¿Confirma que desea eliminar la asignatura actual de este plan de estudios?',
    			btn: 'Si, remover',
                type: 'danger',
                size: 'md',
    			success: function(dialog){
    				dialog.body.html('Eliminando...');
    				dialog.btn.prop('disabled', true);

    				$.ajax({
    				    url: '<?= ruta("asignatura.eliminar_pe") ?>',
    				    method: 'post',
    				    data: {id_asignatura:id},
    				    success: function(response){
    						if(response.estado){
    							dialog.body.html(successAlert('El plan de estudio fue removido de esta asignatura'));
    							$('#asignatura_pe_'+id).remove();
    						}else{
    							dialog.body.html(errorAlert(response.mensaje));
    						}
    				    },
    				    error: function(xhr){
    				        dialog.body.html(errorAlert('Error desconocido'));
    				    }
    				});

    			}
    		});
    	});




    });
</script>