<?php 
$criterio = $data->fromBody('criterio');
$existente = $data->fromBody('existente', false);
?>

<div id="div_error_js" class="alert alert-danger" style="display:none;">
	<strong>Errro</strong> Ocurrió un error al intentar guardar los datos
</div>

<div class="container">
	<form method="post" id="form_criterio">
		
		<?php if($data->isPost()){ ?>
			<div class="alert alert-<?= $data->isError() ? 'danger' : 'success' ?>">
				<?= $data->getMsj() ?>
			</div>

			<?php if(!$data->isError()){ ?>
			<script type="text/javascript">
				listarCriterios();
			</script>
			<?php } ?>

		<?php }  ?>

		<input type="hidden" name="id_criterio" value="<?= $criterio->id_criterio ?>">

		<!-- Campo nombre criterio -->
		<div class="form-group">
		    <label>Criterio <span class="text-danger">*</span></label> 
		    <input type="text" value="<?= $criterio->descrip_criterio ?>" name="nombre_criterio" required="true" class="form-control" placeholder="Criterio"/>
		</div>
	</form>
</div>


<script>
	
	function crearCriterio(){
		var nombreCriterio = $('#nombre_criterio').val();

		if(nombreCriterio == ""){
			$('#nombre_criterio').focus();
			return;
		}
		var btn = $('#modal_btn_confirm');
		loading(btn,'Guardando');

		$.ajax({
			url: '<?= $existente ? "editar-criterio" : "nuevo-criterio" ?>',
			method: 'post',
			data: $('#form_criterio').serialize(),
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

		$('#form_criterio').on('submit', function (e){
			e.preventDefault();
			crearCriterio();
		});

		$('#modal_btn_primary').off('click').on('click', function(){
			crearCriterio();
		});
	});
</script>