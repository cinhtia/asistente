<?php
$seccion = $data->fromBody('seccion');
$page = $data->fromBody('page', 1);
?>
<div class="container">
	<fieldset>
		<legend>Configuraciones Académicas</legend>
		
		<h4 class="mt-25 mb-0">Institución</h4>
		<hr class="m-0">
		<div>
			<a href="<?= ruta('pe') ?>" class="btn btn-secondary btn-custom">
				<i class="fa fa-list fa-3x"></i><br> Planes de estudio
			</a>
			<a href="<?= ruta('asignatura') ?>" class="btn btn-secondary btn-custom">
				<i class="fa fa-list fa-3x"></i><br> Asignaturas
			</a>
		</div>
	
		<h4 class="mt-25 mb-0">Competencias</h4>
		<hr class="m-0">
		<div>
			<a href="<?= ruta('cg') ?>" class="btn btn-secondary btn-custom">
				<i class="fa fa-list fa-3x"></i><br> Competencias genéricas
			</a>
			<a href="<?= ruta('cg') ?>" class="btn btn-secondary btn-custom">
				<i class="fa fa-list fa-3x"></i><br> Competencias disciplinares
			</a>

			<a href="<?= ruta('competencia2') ?>" class="btn btn-secondary btn-custom">
				<i class="fa fa-list fa-3x"></i><br> Competencias de asignatura y unidad
			</a>
		</div>
	</fieldset>
</div>

<!-- <div class="container-fluid">
	<div class="row" style="margin-top:40px;">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
			<div class="list-group">
			  <a href="#" class="list-group-item list-group-item-action" id="btn_planes_estudio">
			  	Planes de estudio
			  </a>
			  <a href="#" class="list-group-item list-group-item-action" id="btn_asignaturas">
			  	Asignaturas
			  </a>
			  <a href="#" class="list-group-item list-group-item-action disabled" id="btn_cg">
			  	Competencias genéricas
			  </a>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">
			<div id="contenedor_principal">
				<h2>Selecciona un elemento del menú izquierdo</h2>
			</div>
		</div>
	</div>
	
</div> -->

<script type="text/javascript">

	// var contenedorPrincipal = $('#contenedor_principal');
	// var vistaSeleccionada = -1;
	
	// var btnPEs = $('#btn_planes_estudio');
	// var btnAsignaturas = $('#btn_asignaturas');
	// var btnCG = $('#btn_cg');

	// function seleccionarBoton(obj){
	// 	btnPEs.removeClass('active');
	// 	btnAsignaturas.removeClass('active');
	// 	btnCG.removeClass('active');

	// 	obj.addClass('active');
	// }

	// function cargarSeccionPEs(page,search){
	// 	seleccionarBoton(btnPEs);
	// 	window.history.pushState('page1', 'Académico', '<?= ruta("academico") ?>?seccion=2');
		
	// 	if(!page){
	// 		page = 1;
	// 	}

	// 	search = (search != null) ? ("&"+search) : "";


	// 	$.ajax({
	// 		url: '<?= ruta("pe") ?>?page='+page+search,
	// 		success: function(response){
	// 			contenedorPrincipal.html(response);
	// 		},
	// 		error: function(){
	// 			contenedorPrincipal.html(errorAlert('Error al cargar la lista de planes de estudio'));
	// 		}
	// 	});
	// }

	// function cargarSeccionAsignaturas(page, search){
	// 	if(!page){
	// 		page = 1;
	// 	}

	// 	search = (search != null) ? ("&"+search) : "";

	// 	seleccionarBoton(btnAsignaturas);
	// 	window.history.pushState('page1', 'Asignatura', '<?= ruta("academico") ?>?seccion=3&page='+page);

	// 	$.ajax({
	// 		url: '<?= ruta("asignatura") ?>?page='+page+search,
	// 		success: function(response){
	// 			contenedorPrincipal.html(response);
	// 		},
	// 		error: function(){
	// 			contenedorPrincipal.html(errorAlert('Error al cargar la lista de asignaturas'));
	// 		}
	// 	});
	// }

	// function cargarSubseccionPlanesEstudioAsignatura(idAsignatura, pageAsignatura){
	// 	$.ajax({
	// 	    url: '<?= ruta("asignatura.pes") ?>',
	// 	    method: 'get',
	// 	    data: {id_asignatura: idAsignatura, page_asignatura: pageAsignatura},
	// 	    success: function(response){
	// 			contenedorPrincipal.html(response);
	// 	    },
	// 	    error: function(xhr){
	// 	        alert('Error al cargar la sección solicitada');
	// 	    }
	// 	});
	// }

	// function cararSubseccionAsignaturasPlanEstudio(idPE, pagePE){
	// 	$.ajax({
	// 	    url: '<?= ruta("pe.asignaturas") ?>',
	// 	    method: 'get',
	// 	    data: {id_pe: idPE, page_pe: pagePE},
	// 	    success: function(response){
	// 			contenedorPrincipal.html(response);
	// 	    },
	// 	    error: function(xhr){
	// 	        alert('Error al cargar la sección solicitada');
	// 	    }
	// 	});
	// }

	// function cargarSeccionCG(page, search){		
	// 	if(!page){
	// 		page = 1;
	// 	}
	// 	search = (search != null) ? ("&"+search) : "";

	// 	seleccionarBoton(btnCG);
	// 	window.history.pushState('page1', 'Competencias', '<?= ruta("academico") ?>?seccion=4&page='+page);

	// 	$.ajax({
	// 		url: '<?= ruta("cg") ?>?page='+page+search,
	// 		success: function(response){
	// 			contenedorPrincipal.html(response);
				
				
	// 		},
	// 		error: function(){
	// 			contenedorPrincipal.html(errorAlert('Error al cargar la lista de competencias genéricas'));
	// 		}
	// 	});
	// }

	// $(document).ready(function(){
		
	// 	btnPEs.off('click').on('click', function (e){
	// 		e.preventDefault();
	// 		cargarSeccionPEs();
	// 	});

	// 	btnAsignaturas.off('click').on('click', function (e){
	// 		e.preventDefault();
	// 		cargarSeccionAsignaturas();
	// 	});

	// 	btnCG.off('click').on('click', function (e){
	// 		e.preventDefault();
	// 		cargarSeccionCG();
	// 	});

	// 	<?php if($seccion == 2){ ?>
	// 		cargarSeccionPEs(<?= $page ?>);
	// 	<?php }else if($seccion == 3){ ?>
	// 		cargarSeccionAsignaturas(<?= $page ?>);
	// 	<?php }else if($seccion == 4){?>
	// 		cargarSeccionCG(<?= $page ?>);
	// 	<?php } ?>

		
	// });


	// $(document).off('click','.btn-paginacion-institucion').on('click','.btn-paginacion-institucion', function (e){
	// 	e.preventDefault();
	// 	cargarSeccionInstituciones($(this).data('page'));
	// });

</script>