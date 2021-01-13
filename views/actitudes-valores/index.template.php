<div class="container">
	
	<h4>Actitudes y valores registrados</h4>

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<a href="#" id="btn_nuevo_av" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Nueva actitud o valor">
						<i class="fa fa-plus"></i>
					</a>
					<button type="button" onclick="listarAVs(1)" data-toggle="tooltip" data-placemente="top" title="Actualizar listado" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
					<!-- <a href="#" id="generar_reporte" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Exportar a excel">
						<i class="fa fa-file-excel-o"></i>
					</a> -->
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						
					<form class="form-inline pull-right" id="form_search">
					  <div class="form-group mb-2">
					    <label for="nombre_av_buscar" >Nombre &nbsp;</label>
					    <input type="text" class="form-control" name="nombre_av_buscar" id="nombre_av_buscar" placeholder="nombre">
					  </div>
					  <div class="form-group mx-sm-3 mb-2">
					    <label for="av_tipo_buscar">Tipo&nbsp;</label>
					    <select name="av_tipo_buscar" id="av_tipo_buscar" class="form-control">
					    	<option value="">Todos</option>
					    	<option value="a">Actitud</option>
					    	<option value="v">Valor</option>
					    	<option value="av">Actitud y valor</option>
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
	
	<div id="contenedor_avs" class="table-responsive">
		
	</div>
</div>
<script type="text/javascript">

	
	function listarAVs(pagina){

		var nombre = $('#nombre_av_buscar').val();
		var avDisp = $('#av_tipo_buscar').val();

		var params = "?page="+(pagina != null ? pagina : 1);

		if(nombre.trim() != ""){
			params += "&descrip_av="+nombre;
		}

		if(avDisp != ""){
			params += "&tipo="+avDisp;
		}



		var url = "<?= ruta('actitud_valor.listado') ?>";
		var objeto = $('#contenedor_avs');
		GET(objeto, url, params, function (respuesta){
			objeto.html(respuesta);
		}, function(){
			objeto.html(errorAlert('Ocurrió un error al obtener la lista de actitudes y valores'));
		})

	}

	$(document).ready(function(){
		listarAVs(1);


		$('#btn_nuevo_av').off('click').on('click', function(e){
			e.preventDefault();

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'<?= ruta("actitud_valor.nuevo") ?>','', function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Nueva actitud o valor');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('#btn_buscar').off('click').on('click', function(){
			listarAVs(1);
		});

		$('#form_search').on('submit', function(e){
			e.preventDefault();
			listarAVs(1);
		});

	});

</script>