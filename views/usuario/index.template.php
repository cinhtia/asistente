<div class="container">
	
	<h4>Usuarios registrados</h4>

	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<a href="#" id="btn_nuevo_usuario" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Nuevo usuario">
						<i class="fa fa-user-plus"></i>
					</a>
					<button type="button" id="generar_reporte" onclick="listarUsuarios(1)" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Actualizar listado">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						
					<form class="form-inline pull-right" id="form_search">
					  <div class="form-group mb-2">
					    <label for="nombre_verbo_buscar" >Nombre &nbsp;</label>
					    <input type="text" class="form-control" name="nombre_usuario_buscar" id="nombre_usuario_buscar" placeholder="Nombre">
					  </div>
					  <div class="form-group mx-sm-3 mb-2">
					    <label for="tipo_usuario">Tipo&nbsp;</label>
					    <select name="tipo_usuario_buscar" id="tipo_usuario_buscar" class="form-control">
					    	<option value="">Todos</option>
					    	<option value="estudiante">Estudiante</option>
					    	<option value="profesor">Profesor</option>
					    	<option value="admin">Administrador</option>
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
	
	<div id="contenedor_usuarios">
		
	</div>

</div>

<script type="text/javascript">

	function cargarListado(page){
		listarUsuarios(page);
	}
	
	function listarUsuarios(pagina){

		var nombre = $('#nombre_usuario_buscar').val();
		var tipo = $('#tipo_usuario_buscar').val();

		var params = "?page="+(pagina != null ? pagina : 1);

		if(nombre.trim() != ""){
			params += "&nombre_usuario_buscar="+nombre;
		}

		if(tipo != ""){
			params += "&tipo_usuario_buscar="+tipo;
		}



		var url = "<?= ruta('usuario.listado') ?>";
		var objeto = $('#contenedor_usuarios');
		GET(objeto, url, params, function (respuesta){
			objeto.html(respuesta);
		}, function(){
			objeto.html(errorAlert('Ocurrió un error al obtener la lista de verbos'));
		})

	}

	$(document).ready(function(){
		listarUsuarios(1);


		$('#btn_nuevo_usuario').off('click').on('click', function(e){
			e.preventDefault();

			var obj = $('#modal_modal_body');
			$('#modal').modal('show');

			GET(obj,'<?= ruta("usuario.nuevo_interno") ?>','', function(resp){
				$('#modal_modal_body').html(resp);
				$('#modal_modal_title').html('Registrar usuario');
			}, function(){
				$('#modal_modal_body').html('Error al cargar la página');
			});
		});

		$('#btn_buscar').off('click').on('click', function(){
			listarUsuarios(1);
		});

		$('#form_search').on('submit', function(e){
			e.preventDefault();
			listarUsuarios(1);
		});

	});

</script>