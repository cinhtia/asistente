<?php 
$user = Sesion::obtener();
$usuario = $data->fromBody('usuario');
$instituciones = $data->fromBody('instituciones', []);
$error = $data->isError();
$msj = $data->getMsj()
?>
<div class="container">
	<?php if($error || $data->isPost()){ ?>
		<div class="alert alert-<?= $error ? 'danger' : 'success' ?>">
			<?= $msj ?>
		</div>
	<?php } ?>


	<form method="post" id="form_registro_usuario">
	
		<input type="hidden" value="<?= $usuario->id_usuario ?>" name="id_usuario">

		<!-- Nombre completo -->
		<div class="form-group">
		    <label>Nombre completo <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $usuario->nombre ?>" name="nombre" id="nombre" data-min required class="form-control" placeholder="Nombre completo"/>
		</div>

		<!-- Username -->
		<div class="form-group">
		    <label>Usuario de inicio de sesión <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $usuario->username ?>" name="username" id="username" required class="form-control" placeholder="Usuario de inicio de sesión"/>
		</div>
		<!-- Tipo de usuario -->
		<div class="form-group">
		    <label>Tipo de usuario <span class="text-danger">*</span></label> 
		    <select name="tipo_usuario" <?= !$user->esAdmin ? "disabled" : "" ?> id="tipo_usuario" required="true" class="form-control">
				<option value="estudiante" <?= $usuario->tipo_usuario == 'estudiante' ? 'selected' : '' ?>>Estudiante</option>
				<option value="profesor" <?= $usuario->tipo_usuario == 'profesor' ? 'selected' : '' ?>>Profesor</option>
				<?php if($user && $user->tipo_usuario=='admin'){ ?>
				<option value="admin" <?= $usuario->tipo_usuario == 'admin' ? 'selected' : '' ?> >Administrador</option>
				<?php }  ?>
		    </select>
		</div>

		<div class="form-group">
			<label>Permiso <span class="text-danger">*</span></label>
			<select name="id_permiso" id="id_permiso" class="form-control" required="true">
				<option value="">seleccione</option>
				<?php foreach ($this->permisos as $permiso) { ?>
					<option <?= $usuario->id_permiso == $permiso->id_permiso ? 'selected' : ''; ?> value="<?= $permiso->id_permiso ?>"><?= $permiso->nombre_permiso; ?></option>
				<?php } ?>
			</select>
		</div>

		<div style="background-color:#f8f9fa; padding: 10px; border-radius:5px; border: solid thin #e5e7eb;">
			<div class="form-group">
				<label for="ch_cambiar_pass">Cambiar contraseña</label>
				<input type="checkbox" id="ch_cambiar_pass" value="false">
			</div>

			<!-- Contraseña -->
			<div class="form-group">
			    <label>Contraseña <span class="text-danger invisible" id="span_req_contrasena">*</span></label> 
			    <input type="password" disabled name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña"/>
			</div>

			<!-- Repetir contraseña -->
			<div class="form-group">
			    <label>Confirmar contraseña <span class="text-danger invisible" id="span_req_confirmar_contrasena">*</span></label> 
			    <input type="password" disabled name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" placeholder="Confirmar contraseña"/>
			</div>
		</div>

		

	</form>
</div>

<script type="text/javascript">
	var idEdicion = <?= $usuario && $usuario->id_usuario ? $usuario->id_usuario : 0;  ?>;
	console.log(idEdicion);

	var validarUsername = function(){
		var username = $('#username').val();
		if(!username){ return; }

		var data = {username: username};
		if(idEdicion){
			data.id = idEdicion;
		}

		api.get({
			url: '<?= ruta("usuario.validaruser") ?>',
			data: data,
			success: function(response){
				if(!response.estado){
					utils.alert.error('El usuario '+username+' no se encuentra disponible');
				}
			},
			error: function(){
				utils.alert.error('Error al validar el usuario ingresado');
			}
		});
	};

	

	var rules = {
				contrasena: {
					required: false,
					minlength: 8
				},
			    confirmar_contrasena: {
			    	required: false,
			      	equalTo: "#contrasena"
			    }
			};

	var messages = {
				nombre: 'Por favor, ingrese un nombre válido',
				username: 'Por favor, ingrese un username válido',
				tipo_usuario: 'Debe seleccionar un tipo de usuario',
				contrasena: {
					required: 'ingrese una contraseña válida',
					minlength: 'Su contraseña debe tener mínimo 8 caracteres'
				},
				confirmar_contrasena: 'Ingrese la misma contraseña aquí'
			}

	function validarYEnviar(form){
		if(form.valid()){
			var btn = $('#modal_btn_primary');
			loading(btn,'Guardando');
			
			$.ajax({
				url: '<?= ruta("usuario.editar") ?>',
				method: 'post',
				data: form.serialize(),
				success: function(response){
					notLoading(btn,'Guardar');
					$('#modal_modal_body').html(response);
					listarUsuarios(1);
				},
				error: function(){
					notLoading(btn,'Guardar');
					alert('Error desconocido');
				}
			});
		}
	}

	function initValidForm(){
		return $('#form_registro_usuario').validate({
			rules: rules,
			messages: messages
		});
	}

	var validate = initValidForm();
	
	$(document).ready(function(){

		// initValidForm();

		$('#form_registro_usuario').on('submit', function(e){
			e.preventDefault();
			validarYEnviar($(this));
		});

		$('#modal_btn_primary').off('click').on('click', function(){
			$('#form_registro_usuario').submit();
		});

		$('#ch_cambiar_pass').change(function(){
			var bool = $(this).is(':checked') ? true : false;
			$('#contrasena').prop('disabled',!bool);
			$('#contrasena').prop('required',bool);

			$('#confirmar_contrasena').prop('disabled',!bool);
			$('#confirmar_contrasena').prop('required',bool);

			rules.contrasena.required = bool;
			rules.confirmar_contrasena.required = bool;

			validate.destroy();

			validate = initValidForm();

			$('#span_req_contrasena').removeClass('visible');
			$('#span_req_confirmar_contrasena').removeClass('visible');
			$('#span_req_contrasena').removeClass('invisible');
			$('#span_req_confirmar_contrasena').removeClass('invisible');

			if(bool){
				$('#span_req_contrasena').addClass('visible');
				$('#span_req_confirmar_contrasena').addClass('visible');
			}else{
				$('#span_req_contrasena').addClass('invisible');
				$('#span_req_confirmar_contrasena').addClass('invisible');
			}

		});

		$('#username').focusout(validarUsername);
	});

</script>