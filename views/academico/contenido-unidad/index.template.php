<?php 
$page_asignatura = Request::singleton()->getIfExists('page_asignatura', null);
$id_asignatura = Request::singleton()->getIfExists('id_asignatura', null);

?>
<div class="container">
	
	<fieldset>
		<h4>Contenidos de la unidad</h4>
		<div class="card">
			<div class="card-body">
				<a href="<?= ruta('unidad',['page' => $this->old_page, 'term' => $this->term, 'pageBack' => $page_asignatura, 'id_asignatura' => $id_asignatura]); ?>" class="btn btn-secondary" >
					<i class="fa fa-arrow-left"></i> Regresar
				</a>
				<button onClick="abrirFormulario()" type="button" class="btn btn-primary" tooltip="Nuevo contenido"><i class="fa fa-plus"></i></button>
				<button class="btn btn-secondary" onClick="cargarListado(1)" id="btn_recargar_listado" data-toggle="tooltip" data-placement="bottom" title="Recargar listado">
					<i class="fa fa-refresh"></i>
				</button>
			</div>
		</div>
		<br>
		<?php if($data->isError()){?>
		<div class="alert alert-danger">
			<i class="fa fa-alert"></i> <?= $data->getMsj() ?>
		</div>
		<?php } ?>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 table-responsive">
				<table class="table table-sm table-bordered">
					
					<tbody>
						<tr>
							<th>Unidad</th>
							<td><?= $this->ua->num_unidad ?></td>
						</tr>
						<tr>
							<th>Nombre</th>
							<td><?= $this->ua->nombre_unidad ?></td>
						</tr>
						<tr>
							<th>Asignatura</th>
							<td><?= $this->ua->nombre_asignatura ?></td>
						</tr>
						
						<tr> 
			<!--				<th>Creado</th>
							<td><?= Helpers::fechaFormalCorta(false, $this->ua->fecha_creacion); ?></td>
						</tr>
							
						<tr>
							<th>Actualizado</th>
							<td><?= Helpers::fechaFormalCorta(false, $this->ua->fecha_actualizacion); ?></td> -->
						</tr> 
					</tbody>
				</table>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div id="contenedor_listado" class="table-responsive"></div>
			</div>
		</div>

	</fieldset>
</div>


<script type="text/javascript">

	function abrirFormulario(idContenido){
		utils.modal.remote({
			url: '<?= ruta("cont_unidad.formulario") ?>',
			data: { id_contenido_unidad_asignatura : idContenido || 0, id_unidad_asignatura : <?= $this->ua->id_unidad_asignatura; ?> },
			error: function(){
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			},
			modal_options: {
				titulo: idContenido ? 'Editar contenido de unidad' : 'Nuevo contenido de unidad',
				backdrop: 'static',
			}
		});
	}

	function eliminar(id){
		utils.modal.confirm({
			type: 'danger',
			size: 'md',
			titulo : 'Confirmación eliminación',
			contenido: '¿Confirma que desea eliminar esta unidad-asignatura?',
			success: function (dialog){
				dialog.btn.prop('disabled', true);

				$.ajax({
				    url: '<?= ruta("cont_unidad.eliminar") ?>',
				    method: 'post',
				    data: {id_unidad_asignatura: id},
				    success: function(response){
				    	dialog.body.html('Eliminando..');
						if(response.estado){
							dialog.body.successAlert(response.mensaje);
							utils.alert.success(response.mensaje);
							cargarListado(1);
						}else{
							dialog.body.errorAlert(response.mensaje);
							utils.alert.error(response.mensaje);
						}
				    },
				    error: function(xhr){
				    	dialog.body.errorAlert('Ha ocurrido un error al intentar eliminar la unidad-asignatura');
				        utils.alert.error('Ha ocurrido un error al intentar eliminar la unidad-asignatura');
				    }
				});
			}
		});
	}
	
	function cargarListado(page){
		api.get({
			url: '<?= ruta("cont_unidad.listado") ?>',
			data: { page: page || 1, id_unidad_asignatura: <?= $this->ua->id_unidad_asignatura; ?> },
			contenedor: '#contenedor_listado',
			error: function(){ $('#contenedor_listado').errorAlert('Ha ocurrido un error al obtener el listado'); utils.alert.error('Ha ocurrido un error al obtener el listado') },
		});
	};

	// $(document).off('click','.btn-editar-item').on('click','.btn-editar-item', function (e){
	//     e.preventDefault();
	//     var id = $(this).data('id');
	//     utils.modal.remote({
	//     	url: '<?= ruta("unidad.formulario") ?>',
	//     	data: { id_unidad_asignatura : id },
	//     	modal_options: {
	//     		titulo: 'Editar unidad-asignatura',
	//     	},
	//     	error: function(){
	//     		utils.alert.error('Ha ocurrido un error al intentar abrir el formulario');
	//     	}
	//     });
	// });

	$(document).off('click','.btn-eliminar-item').on('click','.btn-eliminar-item', function (e){
	    e.preventDefault();
	    var id = $(this).data('id');
	    
	});

    $(document).ready(function(){
    	cargarListado();

    });
</script>