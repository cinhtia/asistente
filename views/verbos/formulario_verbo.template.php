<?php 
$bandera_fase1 = $data->fromBody('bandera_fase1', false);
$verbo = $data->fromBody('verbo');
$existente = $data->fromBody('existente',false);
?>
<div class="container">
	
	<div id="div_error_js" class="alert alert-danger" style="display:none;">
		<strong>Errro</strong> Ocurrió un error al intentar guardar los datos
	</div>

	<?php if($data->isPost()){ ?>
		<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
			<?= $data->getMsj() ?>
		</div>
	<?php }  ?>

	<form id="form_verbo" class="form" method="post">
		<input type="hidden" name="id_verbo" value="<?= $verbo->id_verbo ?>">
		<?php if($bandera_fase1){ ?>
			<div class="alert alert-info text-center">
				Llena los campos faltantes para dar de alta el nuevo verbo
			</div>
			<input type="hidden" name="bandera_fase1" value="1">
		<?php } ?>

		<!-- Nombre verbo -->
		<div class="form-group">
		    <label>Nombre del verbo <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $verbo->descrip_verbo ?>" id="nombre_verbo" name="nombre_verbo" required="true" class="form-control" placeholder="Nombre del verbo"/>
		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<!-- Tipo saber -->
				<div class="form-group">
				    <label>Tipo saber <span class="text-danger">*</span></label> 
				    <select id="tipo_saber_verbo" name="tipo_saber_verbo" required="true" class="form-control">
						<option value="">Selecciona</option>
						<option value="habilidad" <?= $verbo->tipo_saber_verbo == 'habilidad' ? 'selected' : '' ?> >Habilidad</option>
						<option value="conocimiento" <?= $verbo->tipo_saber_verbo == 'conocimiento' ? 'selected' : '' ?> >Conocimiento</option>
						<option value="actitud" <?= $verbo->tipo_saber_verbo == 'actitud' ? 'selected' : '' ?> >Actitud</option>
						<option value="valor" <?= $verbo->tipo_saber_verbo == 'valor' ? 'selected' : '' ?> >Valor</option>
				    </select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<label for="disp_para_sel" class="cont-ch-normal mt-25">
			  	  <input type="checkbox" <?= $bandera_fase1 ? 'disabled' : '' ?> class="ch-sel" name="disponible" <?= $verbo->disponible || $bandera_fase1 ? "checked" : "" ?> value="1" id="disp_para_sel">
			  	  <span class="checkmark"></span> Seleccionable al crear competencia
			  	</label>
			</div>
		</div>


	</form>
</div>

<script>
	var bandera_fase1 = <?= $bandera_fase1 ? 'true' : 'false'; ?>;
	function guardarVerbo(){
		var nombreVerbo = $('#nombre_verbo').val();
		var tipoSaber =  $('#tipo_saber_verbo').val();

		if(nombreVerbo == ""){
			$('#nombre_verbo').focus();
			console.log("nombreVerbo es vacio.'"+nombreVerbo+"'");
			return;
		}

		if(tipoSaber == ""){
			$('#tipo_saber_verbo').focus();
			console.log("tipo_saber_verbo es vacio");
			return;
		}


		var btn = $('#modal_btn_confirm');
		loading(btn,'Guardando');
		if(bandera_fase1){ $('#disp_para_sel').prop('disabled',false); }
		$.ajax({
			url: '<?= $existente ? "editar":"nuevo" ?>-verbo',
			method: 'post',
			data: $('#form_verbo').serialize(),
			success: function (response){
				if(response.estado){
					utils.alert.success(response.mensaje);
					<?php if(!$existente){ ?>
						$('#form_verbo')[0].reset();
					<?php } ?>
					if(bandera_fase1){
						ElementoNuevoAgregado('verbo', response.extra);
						utils.modal.hide();
					}else{
						notLoading(btn, 'Guardar');
						listarVerbos();
					}
				}else{
					utils.alert.error(response.mensaje);
				}
				if(bandera_fase1){ $('#disp_para_sel').prop('disabled',true); }
				// $('#modal_modal_body').html(response);
			},
			error: function(){
				$('#div_error_js').show(1500, function (){$(this).hide()});
				if(bandera_fase1){ $('#disp_para_sel').prop('disabled',true); }
			}
		});
	}

	$(document).ready(function (){

		$('#form_verbo').validate({
			messages: {
				nombre_verbo: 'Nombre no válido',
				tipo_saber_verbo: 'Debes seleccionar un tipo de saber'
			}
		});

		$('#form_verbo').on('submit', function (e){
			e.preventDefault();
			if($(this).valid()){
				guardarVerbo();
			}
			
		});


		$('#modal_btn_primary').off('click').on('click', function (){
			$('#form_verbo').submit();


			// guardarVerbo();
		});
	});
</script>