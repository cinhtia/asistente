<?php 
$contexto = $data->fromBody('contexto');
$existente = $data->fromBody('existente', false);
?>

<div id="div_error_js" class="alert alert-danger" style="display:none;">
	<strong>Errro</strong> Ocurrió un error al intentar guardar los datos
</div>

<div class="container">
	<form method="post" id="form_contexto">
		
		<?php if($data->isPost()){ ?>
			<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
				<?= $data->getMsj() ?>
			</div>
			<?php if(!$data->isError()){ ?>
				<script type="text/javascript">
					listarContextos();
				</script>
			<?php } ?>
		<?php }  ?>

		<input type="hidden" value="<?= $contexto->id_contexto ?>" name="id_contexto">

		<!-- Campo nombre contexto -->
		<div class="form-group">
		    <label>Contexto <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $contexto->descrip_contexto ?>" name="nombre_contexto" required="true" class="form-control" placeholder="contexto"/>
		</div>
	</form>
</div>


<script>
	
	function crearContexto(){
		var nombreContexto = $('#nombre_contexto').val();

		if(nombreContexto == ""){
			$('#nombre_contexto').focus();
			return;
		}
		var btn = $('#modal_btn_confirm');
		loading(btn,'Guardando');

		$.ajax({
			url: '<?= ruta( $existente ?  "contexto.editar" : "contexto.nuevo" ) ?>',
			method: 'post',
			data: $('#form_contexto').serialize(),
			success: function (response){
				$('#modal_modal_body').html(response);
				notLoading(btn, 'Guardar');
			},
			error: function(){
				$('#div_error_js').show(1500, function (){$(this).hide()});
			}
		});
	}


	$(document).ready(function (){

		$('#form_contexto').on('submit', function (e){
			e.preventDefault();
			crearContexto();
		});

		$('#modal_btn_primary').off('click').on('click', function(){
			crearContexto();
		});
	});
</script>