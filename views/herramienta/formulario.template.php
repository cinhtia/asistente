<?php
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_herramienta') ?>
	<?php
	$form->inputHidden([
		'id' => 'id_herramienta',
		'value' => $this->herramienta->id_herramienta,
	]);
	?>
	
	<?php 
	$form->bs4FormInput([
		'id'=>'descripcion_herramienta',
		'label'=>'Descripción',
		'placeholder'=>'Descripción',
		'required' => true,
		'value' => $this->nuevo ? '' : $this->herramienta->descripcion_herramienta,
		],true);
	 ?>

	 <?php 
	 $form->bs4FormTextarea([
	 	'id'=>'explicacion_herramienta',
	 	'label'=>'Explicación',
	 	'placeholder'=>'Explicación',
	 	'required' => true,
	 	'value' => $this->nuevo ? '' : $this->herramienta->explicacion_herramienta,
	 	],true);
	  ?>
	<div class="form-group">
		<label for="estrategias_didacticas">Estrategias didácticas <small class="text-info">escribe cada estrategia separada por coma</small></label>
		<ul id="estrategias_didacticas">
			<?php
			$tagsed = explode(",", $this->herramienta->estrategias_didacticas);
			foreach ($tagsed as $item) {?>
				<li><?= $item ?></li>
			<?php } ?>
		</ul>
		<!-- <input name="estrategias_didacticas" class="form-control" id="estrategias_didacticas" value="<?= $this->herramienta->estrategias_didacticas ?>" /> -->
	</div>
	
	<div class="form-group">
		<label for="palabras_asociadas">Palabras asociadas <small class="text-info">Escribe cada palabra clave separada por coma</small></label>
		<ul id="palabras_asociadas">
			<?php
			$tagsed = explode(",", $this->herramienta->palabras_asociadas);
			foreach ($tagsed as $item) {?>
				<li><?= $item ?></li>
			<?php } ?>
		</ul>
		<!-- <input placeholder="Escribe las palabras clave" name="palabras_asociadas" class="form-control" id="palabras_asociadas" value="<?= $this->herramienta->palabras_asociadas ?>" /> -->
	</div>


	<?php $form->end() ?>
</div>

<script type="text/javascript">
	var nuevo = <?= $this->nuevo ? 'true' : 'false' ?>;

    $(document).ready(function(){
    	var form = $('#frm_herramienta');

    	$('#estrategias_didacticas').tagit({
    		allowSpaces: true,
    	});
    	$('#palabras_asociadas').tagit({
    		allowSpaces: true,
    	});

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			descripcion_herramienta: 'La descripción es requerida',
    			explicacion_herramienta: 'La explicación es requerida',
    		}
    	});

    	form.on('submit', function (e){
            e.preventDefault();
    		if(form.valid()){
    			var data = {
    				id_herramienta : $('#id_herramienta').val(),
    				descripcion_herramienta : $('#descripcion_herramienta').val(),
    				explicacion_herramienta : $('#explicacion_herramienta').val(),
    				estrategias_didacticas : $('#estrategias_didacticas').tagit('assignedTags').join(","),
    				palabras_asociadas : $('#palabras_asociadas').tagit('assignedTags').join(","),
    			};

    			api.post({
    				url: '<?= ruta("herramienta.guardar") ?>',
    				data: data,
    				cb: function (response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
    						cargarListado(1);
    						if(nuevo){ form[0].reset(); }
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				}
    			})
    		}
    	});

		utils.modal.btn.onClick(function (){
			form.submit();
		});
    });
</script>