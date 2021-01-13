<?php $page = $data->fromBody('page', 1); ?>
<div class="container">
	<h4>Asignaturas</h4>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<button onclick="formularioAsignatura()" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Nueva asignatura" ><i class="fa fa-plus"></i></button>
					<button onclick="cargarListado()" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Recargar listado de asignaturas">
						<i class="fa fa-refresh"></i>
					</a>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<form id="frm_search" class="form form-inline pull-right">
                        <div class="form-group">
                            <input type="text" name="busqueda_asignatura" id="busqueda_asignatura" required="false" class="form-control" placeholder="Buscar asignatura"/>
                        </div>
                        &nbsp;
                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </form>
				</div>
			</div>
		</div>
	</div>
    <div id="contenedor_listado"></div>
</div>

<script type="text/javascript">
	
	function formularioAsignatura(idAsignatura){
        utils.modal.remote({
		    url: idAsignatura ? '<?= ruta("asignatura.editar") ?>' : '<?= ruta("asignatura.nuevo") ?>',
            data: { id_asignatura: idAsignatura },
            modal_options: {
                titulo: idAsignatura ? 'Editar asignatura' : 'Nueva asignatura',
            },
            error: function(){
                utils.alert.error('Ha ocurrido un error al abrir el formulario');
            }
        });
	}

    function cargarListado(page){
        var term = $('#busqueda_asignatura').val();
        api.get({
            url: '<?= ruta("asignatura.listado") ?>',
            data: {page: page || 1, term: term},
            success: function(resp){
                $('#contenedor_listado').html(resp);
            },
            error: function(){
                utils.alert.error('Ha ocurrido un error al obtener los datos');
            }
        })
    }

    function listaCGS(idAsignatura){
        utils.modal.remote({
            method: 'get',
            url: '<?= ruta("asignatura.cg_asignatura") ?>',
            data: {id_asignatura: idAsignatura},
            modal_options: {
                titulo: 'Competencias genéricas de la asignatura',
                btn: 'Guardar'
            },
            error: function(){
                alert('Error al cargar la sección solicitada');
            }
        });
    }

    function eliminarAsignatura(id){
        utils.modal.confirm({
            titulo: 'Confirmación',
            contenido: '¿Confirma que desea eliminar esta asignatura? Se eliminarán automáticamente sus unidades, competencias genéricas y competencias disciplinares',
            btn_primary: 'Eliminar',
            size: 'md',
            type: 'danger',
            success: function(dialog){
                dialog.body.html('Eliminando, espera por favor');
                dialog.btn.prop('disabled', true);

                $.ajax({
                    url: '<?= ruta("asignatura.eliminar")  ?>',
                    method: 'post',
                    data: {id_asignatura: id},
                    success: function(response){
                        if(response.estado){
                            dialog.body.html(successAlert('La asignatura ha sido eliminada'));
                            cargarListado(1);
                        }else{
                            dialog.body.html(errorAlert(response.mensaje));
                        }
                    },
                    error: function(xhr){
                        dialog.body.html('Error desconocido al intentar eliminar la asignatura');
                    }
                });

            }
        });
    }

    function listaCDS(id){
        utils.modal.remote({
            url: '<?= ruta("asignatura.cd_asignatura") ?>',
            data: { id_asignatura: id },
            modal_options: {
                titulo: 'Competencias disciplinares de la asignatura',
            },
            error: function(){
                utils.alert.error('Ha ocurrido un error al mostrar las competencias disciplinares');
            }
        });
    }

    function abrirModalPEs(idAsignatura){
        
    }

    $(document).ready(function(){

    	$('.btn_pes_asignatura').off('click').on('click', function (e){
    	    e.preventDefault();
    	    var idAsignatura = $(this).data('id');
    	    // cargarSubseccionPlanesEstudioAsignatura(idAsignatura, < ?= $page ? >);
    	});

    	$('.btn_editar_asignatura').off('click').on('click', function (e){
    	    e.preventDefault();
    		var id = $(this).data('id');
    		$.ajax({
    			url: '<?= ruta("asignatura.editar") ?>',
    			method: 'get',
    			data: {id_asignatura: id},
    			success:function (response){
    				utils.modal.show({
    					titulo: 'Editar asignatura',
    					contenido: response,
    					btn_primary: 'Actualizar'
    				});
    			},
    			error: function(){
    				alert('Error desconocido');
    			}
    		});
    	});

        $('#frm_search').on('submit', function(event){
            event.preventDefault();
            cargarListado(1);
        })

        cargarListado(<?= $page ?>);
    });
</script>