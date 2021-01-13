<?php 
$nuevo = $this->permiso->id_permiso == 0;
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_permiso'); ?>
	<?php $form->inputHidden([
		'id' => 'id_permiso',
		'value' => $nuevo ? 0 : $this->permiso->id_permiso,
	]); ?>
	<?php $form->bs4FormInput([
		'label' => 'Nombre del permiso',
		'id' => 'nombre_permiso',
		'required' => true,
		'value' => $nuevo ? '' : $this->permiso->nombre_permiso
	], true); ?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<div class="form-group">
				<label for="sel_agregar">Módulo</label>
				<select class="form-control" name="modulo" id="modulo">
					<option value="">selecciona</option>
					<?php foreach ($this->modulos as $index => $modulo) { ?>
						<option value="<?= $modulo->id_modulo ?>"><?= $modulo->descripcion ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
			<div class="mt-25">
				<label for="leer" class="cont-ch-normal">
			  	  <input type="checkbox" class="ch-sel" name="leer" id="leer">
			  	  <span class="checkmark"></span> Leer
			  	</label>&nbsp;&nbsp;&nbsp;
		  		<label for="escribir" class="cont-ch-normal">
		  	  	  <input type="checkbox" class="ch-sel" name="escribir" id="escribir">
		  	  	  <span class="checkmark"></span> Escribir
		  	  	</label>&nbsp;&nbsp;&nbsp;
				<label for="eliminar" class="cont-ch-normal">
			  	  <input type="checkbox" class="ch-sel" name="eliminar" id="eliminar">
			  	  <span class="checkmark"></span> Eliminar
			  	</label>


				<!-- <label for="leer"> <input type="checkbox" id="leer"> Leer</label> -->
				<!-- &nbsp;&nbsp;<label for="escribir"> <input type="checkbox" id="escribir"> Escribir</label> -->
				<!-- &nbsp;&nbsp;<label for="eliminar"> <input type="checkbox" id="eliminar"> Eliminar</label> -->
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<button type="button" class="btn btn-sm btn-primary btn-block mt-25" onclick="agregarModulo()">
				<i class="fa fa-plus"></i> Agregar
			</button>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-hover table-condensed">
			<thead class="thead-dark">
				<tr>
					<th>Módulo</th>
					<th>Leer</th>
					<th>Escribir</th>
					<th>Eliminar</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody id="tbody">
				
			</tbody>
		</table>
	</div>

	<?php $form->end(); ?>
</div>

<script type="text/javascript">

	var modulos = <?= json_encode($this->modulosGuardados) ?>;
	var tbody = $('#tbody');

	function itemTable(index){
		var mod = modulos[index];
		var html = '<tr>'+
			'<td>'+mod.descripcion+'</td>'+
			'<td>'+((mod.leer==1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>')+'</td>'+
			'<td>'+((mod.escribir==1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>')+'</td>'+
			'<td>'+((mod.eliminar==1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>')+'</td>'+
			'<td>'+
				'<button type="button" onclick="removerModulo('+index+')" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placemente="top" title="Remover módulo">'+
					'<i class="fa fa-trash-o"></i>'+
				'</button>'+
			'</td>'+
		'</tr>';
		return html;
	}

	function actualizarListado(index){
		if(index){
			if(index == 0) tbody.html('');
			
			tbody.append(itemTable(index));
		}else{

			if(modulos.length > 0) tbody.html('');

			for (var i = 0; i < modulos.length; i++) {
				tbody.append(itemTable(i));
			}
			if(modulos.length == 0){
				tbody.html('<tr><td colspan="5" class="text-center"><strong>Sin módulos asignados<strong></td></tr>');
			}
		}
	}

	function agregarModulo(){
		var idMod = $('#modulo').val();
		var descrip = $('#modulo option:selected').text();
		if(idMod != ""){
			var leer = $('#leer').isChecked() ? 1 : 0;
			var escribir = $('#escribir').isChecked() ? 1 : 0;
			var eliminar = $('#eliminar').isChecked() ? 1 : 0;

			if(leer == 0 && escribir == 0 && eliminar == 0){
				utils.alert.error('Debes incluir al menos un tipo de acción (leer, escribir, eliminar)');
				return;
			}

			var indexEncontrado = -1;
			modulos.forEach(function (item, index){
				console.log("######## analizando : "+item.id_modulo+" == "+idMod);
				if(item.id_modulo == idMod){
					indexEncontrado = index;
				}
			});

			if(indexEncontrado == -1){
				modulos.push({
					id_modulo: idMod,
					descripcion: descrip,
					leer: leer,
					escribir: escribir, 
					eliminar : eliminar,
				});

				actualizarListado(modulos.length - 1);

				$('#modulo').val('');
				$('#leer').prop('checked', false);
				$('#escribir').prop('checked', false);
				$('#eliminar').prop('checked', false);
			}else{
				modulos[indexEncontrado].leer = leer;
				modulos[indexEncontrado].escribir = escribir;
				modulos[indexEncontrado].eliminar = eliminar;
				actualizarListado();
			}
		}
	}

	function removerModulo(index){
		if(modulos[index]){
			modulos.splice(index, 1);
			actualizarListado();
		}
	}

    $(document).ready(function(){
    	actualizarListado();
    	var form = $('#frm_permiso');

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			nombre_permiso: 'El nombre es requerido'
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){

    			if(modulos.length == 0){
    				utils.alert.error('Debes seleccionar al menos un módulo');
    				return;
    			}

    			var idsMods = [];
    			modulos.forEach(function (item, index){
    				idsMods.push({
    					id_modulo: item.id_modulo, 
    					leer: item.leer || 0, 
    					escribir: item.escribir || 0, 
    					eliminar: item.eliminar || 0
    				});
    			});

    			var datos = {
    				id_permiso: $('#id_permiso').val() || 0,
    				nombre_permiso: $('#nombre_permiso').val(),
    				modulos: idsMods
    			};

    			api.post({
    				url: '<?= ruta("permiso.guardar") ?>',
    				data: datos,
    				cb: function (response){
    					if(response.estado){
    						cargarListado(1);
    						utils.alert.success(response.mensaje);
    						if(datos.id_permiso == 0){
    							form[0].reset();
    							modulos = [];
    							actualizarListado();
    						}
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				},
    				errorMessage: 'Ha ocurrido un error al intentar guardar el permiso',
    			});

    		}
    	});

		utils.modal.btn.onClick(function (){
			form.submit();
		});
    });
</script>