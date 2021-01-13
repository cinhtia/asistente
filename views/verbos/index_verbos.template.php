<div class="container">
	
	<h4>Verbos registrados</h4>

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<a href="#" id="btn_nuevo_verbo" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Nuevo verbo">
						<i class="fa fa-plus"></i>
					</a>
					<button onclick="listarVerbos(1)" data-toggle="tooltip" data-placemente="top" title="Recargar listado" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
					<!-- <a href="#" id="generar_reporte" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Exportar a excel">
						<i class="fa fa-file-excel-o"></i>
					</a> -->
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						
					<form class="form-inline pull-right" id="form_search">
					  <div class="form-group mb-2">
					    <label for="nombre_verbo_buscar" >Verbo &nbsp;</label>
					    <input type="text" class="form-control" name="nombre_verbo_buscar" id="nombre_verbo_buscar" placeholder="Escriba el verbo">
					  </div>
					  <div class="form-group mx-sm-3 mb-2">
					    <label for="verbo_disponible_buscar">Seleccionable&nbsp;</label>
					    <select name="verbo_disponible_buscar" id="verbo_disponible_buscar" class="form-control">
					    	<option value="">Todos</option>
					    	<option value="1">Sí</option>
					    	<option value="0">No</option>
					    </select>
					  </div>
					  <button type="button" id="btn_buscar" class="btn btn-primary mb-2">
					  	<i class="fa fa-search"></i>
					  </button>
					</form>

				</div>
			</div>
		</div>
	</div>
	
	<div id="contenedor_verbos">
		
	</div>
</div>
<script type="text/javascript">

	
	function listarVerbos(pagina){

		var nombre = $('#nombre_verbo_buscar').val();
		var verboDisp = $('#verbo_disponible_buscar').val();

		var params = "?page="+(pagina != null ? pagina : 1);

		if(nombre.trim() != ""){
			params += "&descrip_verbo="+nombre;
		}

		if(verboDisp != ""){
			params += "&disponible="+verboDisp;
		}



		var url = "<?= ruta('verbo.listado') ?>";
		var objeto = $('#contenedor_verbos');
		GET(objeto, url, params, function (respuesta){
			objeto.html(respuesta);
		}, function(){
			objeto.html(errorAlert('Ocurrió un error al obtener la lista de verbos'));
		})

	}

	$(document).ready(function(){
		listarVerbos(1);


		$('#btn_nuevo_verbo').off('click').on('click', function(e){
			e.preventDefault();

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'nuevo-verbo','', function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Nuevo verbo');
			}, function(errMessage){
				$('#modal_modal_body').html('Error al cargar la página<br>'+errMessage);
			});
		});

		$('#btn_buscar').off('click').on('click', function(){
			listarVerbos(1);
		});

		$('#form_search').on('submit', function(e){
			e.preventDefault();
			listarVerbos(1);
		});

	});

</script>