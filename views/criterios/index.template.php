<div class="container">
	
	<h4>Criterios registrados</h4>

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<a href="#" id="btn_nuevo_criterio" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Nuevo criterio">
						<i class="fa fa-plus"></i>
					</a>
					<button type="button" onclick="listarCriterios(1)" data-toggle="tooltip" data-placemente="top" title="Actualizar listado" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
					<!-- <a href="#" id="generar_reporte" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Exportar a excel">
						<i class="fa fa-file-excel-o"></i>
					</a> -->
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<form class="form-inline pull-right" id="form_search">
					  <div class="form-group mb-2">
					    <label for="nombre_criterio_buscar" >Nombre &nbsp;</label>
					    <input type="text" class="form-control" name="nombre_criterio_buscar" id="nombre_criterio_buscar" placeholder="nombre">
					  </div>
					  &nbsp;
					  <button type="button" id="btn_buscar" class="btn btn-primary mb-2">
					  	<i class="fa fa-search"></i>
					  </button>
					</form>

				</div>
			</div>
		</div>
	</div>
	
	<div id="contenedor_criterios"></div>
</div>
<script type="text/javascript">

	
	function listarCriterios(pagina){

		var nombre = $('#nombre_criterio_buscar').val();
		var criterioDisp = $('#criterio_disponible_buscar').val();

		var params = "?page="+(pagina != null ? pagina : 1);

		if(nombre.trim() != ""){
			params += "&descrip_criterio="+nombre;
		}

		if(criterioDisp != ""){
			params += "&disponible="+criterioDisp;
		}

		var url = "<?= ruta('criterio.listado') ?>";
		var objeto = $('#contenedor_criterios');
		GET(objeto, url, params, function (respuesta){
			objeto.html(respuesta);
		}, function(){
			objeto.html(errorAlert('Ocurrió un error al obtener la lista de criterios'));
		});
	}

	$(document).ready(function(){
		listarCriterios(1);


		$('#btn_nuevo_criterio').off('click').on('click', function(e){
			e.preventDefault();

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'nuevo-criterio','', function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Nuevo criterio');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('#btn_buscar').off('click').on('click', function(){
			listarCriterios(1);
		});

		$('#form_search').on('submit', function(e){
			e.preventDefault();
			listarCriterios(1);
		});

	});

</script>