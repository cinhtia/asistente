<?php 
$av = $data->fromBody('av');
$existente = $data->fromBody('existente',false);?>

<div id="div_error_js" class="alert alert-danger" style="display:none;">
	<strong>Error</strong> Ocurrió un error al intentar guardar los datos
</div>


<form method="post" id="form_av">
	
	<?php if($data->isPost()){ ?>
		<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
			<?= $data->getMsj() ?>
		</div>


		<?php if(!$data->isError()){ ?>
			<script type="text/javascript">
				listarAVs();
			</script>
		<?php } ?>
		
	<?php }  ?>

	<input type="hidden" name="id_compo_valor" value="<?= $av->id_compo_valor ?>">

	<!-- descrip_actitud_valor -->
	<div class="form-group">
	    <label>Nombre <span class="text-danger">*</span></label> 
	    <input type="text" name="descrip_actitud_valor" value="<?= $av->descrip_actitud_valor ?>" id="descrip_actitud_valor" required="true" class="form-control" placeholder="Nombre"/>
	</div>

	<!-- tipo -->
	<div class="form-group">
	    <label>Tipo <span class="text-danger">*</span></label> 
	    <select name="tipo" id="tipo" required="true" class="form-control">
			<option value="">Selecciona una opción</option>
			<option value="a" <?= $av->tipo == "a" ? "selected" : "" ?> >Actitud</option>
			<option value="v" <?= $av->tipo == "v" ? "selected" : "" ?>>Valor</option>
			<option value="av" <?= $av->tipo == "av" ? "selected" : "" ?>>Actitud y valor</option>
	    </select>
	</div>

</form>


<script>

	var desde_asistente = <?= $this->desde_asistente ? 'true' : 'false' ?>;
	var existente = <?= $existente ? 'true' : 'false' ?>;
	
	function crearAV(){
		var nombreAV = $('#descrip_actitud_valor').val();

		if(nombreAV == ""){
			$('#descrip_actitud_valor').focus();
			return;
		}

		var tipoAV = $('#tipo_av').val();
		if(tipoAV == ""){
			$('#tipo_av').focus();
			return;
		}

		var btn = $('#modal_btn_confirm');
		loading(btn,'Guardando');

		$.ajax({
			url: '<?= $existente ? ruta("actitud_valor.editar") : ruta("actitud_valor.nuevo") ?>',
			method: 'post',
			data: $('#form_av').serialize(),
			success: function (response){
				notLoading(btn, 'Guardar');
				if(response.estado){
					if(desde_asistente){
						utils.modal.hide();
						ComponenteCreado(response.data, 'av');
					}else{
						if(!existente){
							$('#form_av')[0].reset();
						}
						listarAVs();
					}
					utils.alert.success(response.mensaje || 'Actitud-valor creado');
				}else{
					utils.alert.warning(response.mensaje || 'Ha ocurrido un error al crear la actitud-valor');
				}
			},
			error: function(){
				$('#div_error_js').show(1500, function (){$(this).hide()});
			}
		});
	}


	$(document).ready(function (){

		$('#form_av').on('submit', function (e){
			e.preventDefault();
			crearAV();
		});

		$('#modal_btn_primary').off('click').on('click', function(){
			crearAV();
		});
	});
</script>