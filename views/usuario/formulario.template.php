<?php 
$user = Sesion::obtener();
$usuario = $data->fromBody('usuario');
$desdeRegistroInterno = $data->fromBody('desde_registro', false);

$instituciones = $data->fromBody('instituciones',[]);

?>
<div class="container">
	<?php if(!$desdeRegistroInterno){ ?>
		<div  style="margin-top: 90px;"></div>
		<h4 class="text-center">Registrarse en el sistema</h4>
	<div class="row mt-25">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

	<?php } ?>
			
			<?php if($data->isPost()){ ?>
				<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
					<?= $data->getMsj() ?>
				</div>
			<?php } ?>


			<form method="post" id="form_registro_usuario">
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
				    <select name="tipo_usuario" id="tipo_usuario" required="true" class="form-control">
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
							<option value="<?= $permiso->id_permiso ?>"><?= $permiso->nombre_permiso; ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<!-- Contraseña -->
						<div class="form-group">
						    <label>Contraseña <span class="text-danger">*</span></label> 
						    <input type="password" value="<?= $usuario->contrasena ?>" name="contrasena" id="contrasena" required="true" class="form-control" placeholder="Contraseña"/>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<!-- Repetir contraseña -->
						<div class="form-group">
						    <label>Confirmar contraseña <span class="text-danger">*</span></label> 
						    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required="true" class="form-control" placeholder="Confirmar contraseña"/>
						</div>
					</div>
				</div>

				<?php if(!$desdeRegistroInterno){ ?>
					<button type="submit" class="btn btn-success">
						<i class="fa fa-check"></i> Registrar usuario
					</button>
				<?php } ?>

			</form>

	<?php if(!$desdeRegistroInterno){ ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			
		</div>
	</div>

	<?php } ?>
</div>

<?php if($desdeRegistroInterno){ ?>


<script type="text/javascript">

	function validarYEnviar(form){
		if(form.valid()){
			var btn = $('#modal_btn_primary');
			loading(btn,'Guardando');
			
			$.ajax({
				url: '<?= ruta("usuario.nuevo_interno") ?>',
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
	
	$(document).ready(function(){

		$('#form_registro_usuario').on('submit', function(e){
			e.preventDefault();
			validarYEnviar($(this));

		});

		$('#modal_btn_primary').off('click').on('click', function(){
			$('#form_registro_usuario').submit();
		});
	});

</script>

<?php }?>

<script type="text/javascript">
	var validarUsername = function(){
		var username = $('#username').val();
		if(!username){ return; }

		api.get({
			url: "<?= ruta('usuario.validaruser') ?>",
			data: { username: username },
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

	$('#form_registro_usuario').validate({
		rules: {
			contrasena: {
				required: true,
				minlength: 8
			},
		    confirmar_contrasena: {
		    	required: true,
		      	equalTo: "#contrasena"
		    }
		},
		messages: {
			nombre: 'Por favor ingresa un nombre válido',
			username: 'Por favor ingresa un username válido',
			tipo_usuario: 'Debes seleccionar un tipo de usuario',
			contrasena: {
				required: 'Ingresa una contraseña válida',
				minlength: 'Tu contraseña debe tener mínimo 8 caracteres'
			},
			confirmar_contrasena: 'Ingresa la misma contraseña aquí'

		}
	});

	$(document).ready(function(){
		$('#username').focusout(validarUsername);
	})

</script>