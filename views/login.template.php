<div class="container">
	<div class="row" style="margin-top:90px;">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

			<h4 class="text-center">Ingresar</h4>

			<?php 
			if($data->isPost()){
				?>
				<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
					<?= $data->getMsj() ?>
				</div>
				<?php
			}
			?>
			
			<form method="post">
				<!-- Username -->
				<div class="form-group">
				    <label>Usuario <span class="text-danger">*</span></label> 
				    <input type="text" name="username" required="true" class="form-control" placeholder="Usuario"/>
				</div>
	
				<!-- Contrasena -->
				<div class="form-group">
				    <label>Contraseña <span class="text-danger">*</span></label> 
				    <input type="password" name="contrasenia" required="true" class="form-control" placeholder="Contraseña"/>
				</div>

				<br>
	
				<button class="btn btn-primary" type="submit">
					<i class="fa fa-send"></i> Iniciar sesión
				</button>

				<a href="usuario/registrar" class="btn btn-secondary pull-right">
					<i class="fa fa-user-plus"></i> Registrarme
				</a>

			</form>
			


		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			
		</div>
	</div>


</div>