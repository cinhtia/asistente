<?php 
$error = $data->isError();
$existeVarError = $error; //isset($data['error']);
$existente = $data->fromBody('existente', false);
$contenido = $data->fromBody('contenido');
?>

<div id="div_error_js" class="alert alert-danger" style="display:none;">
	<strong>Error</strong> Ocurrió un error al intentar guardar el contenido
</div>

<div class="container">
	<form method="post" id="form_contenido">
		
		<?php if($data->forKey('metodo','get') == 'post'){ ?>
			<div class="alert alert-<?= $error ? 'danger' : 'success' ?>">
				<?= $data->getMsj(); ?>
			</div>


			<?php if(!$error){ ?>
				<script type="text/javascript">
					listarContenidos();
				</script>
			<?php } ?>
			
		<?php }  ?>

		<input type="hidden" name="id_contenido" value="<?= $contenido->id_contenido ?>">

		<!-- Campo nombre contenido -->
		<div class="form-group">
		    <label>Contenido <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $contenido->descrip_contenido ?>" name="nombre_contenido" required="true" class="form-control" placeholder="Contenido"/>
		</div>
	</form>
</div>


<script>
	
	function crearContenido(){
		var nombreContenido = $('#nombre_contenido').val();

		if(nombreContenido == ""){
			$('#nombre_contenido').focus();
			return;
		}
		var btn = $('#modal_btn_confirm');
		loading(btn,'Guardando');

		$.ajax({
			url: '<?= $existente ? "editar-contenido" : "nuevo-contenido" ?>',
			method: 'post',
			data: $('#form_contenido').serialize(),
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

		$('#form_contenido').on('submit', function (e){
			e.preventDefault();
			crearContenido();
		});

		$('#modal_btn_primary').off('click').on('click', function(){
			crearContenido();
		});
	});
</script>