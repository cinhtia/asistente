<?php 

$asignatura = $data->fromBody('asignatura');
$numUnidades = $asignatura->num_unidades;
$cgs = $data->fromBody('cgs');
$cgsDisponibles = $data->fromBody('cg_disponibles');
$cgsLista = [];
foreach ($cgsDisponibles as $key => $value) {
	$cgsLista[] = ['value'=>$value->id_cg,'label'=>$value->descripcion_cg];
}

$form = new BaseForm();

$form->bs4FormSelect('id_cg','Seleccione las competencias genéricas y las unidades asociadas a esta asignatura.','id_cg',$cgsLista,'',true);

 ?>

<div class="container">
	<?php  
		$form->beginForm('frm_cg_asignatura'); 
		$form->inputHidden('id_asignatura','id_asignatura',$asignatura->id_asignatura);
	?>
	<?php $form->render('id_cg'); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<?php for ($i=1; $i <= $numUnidades; $i++) { ?>
				<label for="check_<?= $i ?>" class="cont-ch-normal">
				  <input type="checkbox" class="ch-sel" id="check_<?= $i ?>" name="unidades[]" value="<?= $i ?>">
				  <span class="checkmark"></span> Unidad <?= $i ?>
				</label>&nbsp;&nbsp;
			<?php } ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
			<button id='btn_guardar_cg' class="btn btn-primary" type="submit">
				<i class="fa fa-save"></i> Guardar
			</button>
		</div>
	</div>

	<?php $form->endForm(); ?>
	<br>

	<table class="table table-bordered table striped table-hover">
		<colgroup>
			<col width="10%">
			<col width="65%">
			<col width="15%">
			<col width="10%">
		</colgroup>
		<thead class="thead-dark">
			<tr>
				<th>No.</th>
				<th>Descripción</th>
				<th>Unidades</th>
				<th>Opción</th>
			</tr>
		</thead>
		<tbody id='tbody_cgs_asignatura'>
			<?php foreach($cgs as $key => $cgsItem){ ?>
						
			<tr class="row_cg_item" id='row_<?= $cgsItem->id_cg.'_'.$cgsItem->id_asignatura ?>'>
				<td><?= ($key+1) ?></td>
				<td><?= $cgsItem->descripcion_cg ?></td>
				<td><?= $cgsItem->unidades ? $cgsItem->unidades : '-' ?></td>
				<td>
					<a href="#" data-idcg='<?= $cgsItem->id_cg ?>' data-idpe='<?= $cgsItem->id_pe ?>' data-idasignatura='<?= $cgsItem->id_asignatura ?>' class="btn btn-sm btn-danger btn-remover-cg" data-toggle="tooltip" data-placemente="top" title="Remover de la asignatura">
						<i class="fa fa-trash-o"></i>	
					</a>
				</td>
			</tr>
	
			<?php } 
			if(count($cgs)==0){?>
			<tr>
				<td colspan="4">
					<div class="alert alert-info">Esta asignatura no tiene competencias genéricas asignadas</div>
				</td>
			</tr>
			<?php }
			?>
		</tbody>
	</table>

</div>

<script type="text/javascript">
	var tr_no_res = '<tr><td colspan="4"><div class="alert alert-info">Esta asignatura no tiene competencias genéricas asignadas</div></td></tr>';
	var maxIndex = <?= count($cgs); ?>;

	$(document).off('click','.btn-remover-cg').on('click','.btn-remover-cg', function (e){
	    e.preventDefault();
	    var idCG = $(this).data('idcg');
	    var idAsignatura = $(this).data('idasignatura');

	    utils.modal.confirm({
	    	titulo: 'Cofirmación remover',
	    	contenido: '¿Está seguro de remover esta competencia genérica de esta asignatura?',
	    	btn: 'Si, remover',
	    	type: 'danger',
	    	size: 'md',
	    	vertical_center: true,
	    	success: function(dialog){
	    		dialog.modal.modal('hide');
	    		procederEliminar(idCG, idAsignatura);
	    	}
	    });

	});

	function procederEliminar(idCG, idAsignatura){
		var id = 'row_'+idCG+'_'+idAsignatura;
		$.ajax({
		    url: '<?= ruta("asignatura.eliminar_cg_asignatura") ?>',
		    method: 'post',
		    data: {id_cg: idCG, id_asignatura: idAsignatura},
		    success: function(response){
				if(response.estado){
					$('#'+id).remove();
					if($('#tbody_cgs_asignatura tr.row_cg_item').length == 0){
						$('#tbody_cgs_asignatura').html(tr_no_res);
					}
					utils.modal.success(response.mensaje);
				}else{
					utils.modal.error(response.mensaje);
				}
		    },
		    error: function(xhr){
		        utils.modal.error('Ocurrió un error desconocido');
		    }
		});	
	}
	
	function agregarATabla(limpiar){
		var str = $('#id_cg option:selected').text();
		var idCG = $('#id_cg').val();

		var unidades = [];
		$('input[name="unidades[]"').each(function (index){
			if($(this).is(':checked')){
				unidades.push($(this).val());
			}
		});

		var idAsignatura = $('#id_asignatura').val();
		id = 'row_'+idCG+'_'+idAsignatura;

		var aLink = '<a href="#" data-idcg="'+idCG+'" data-idasignatura="'+idAsignatura+'" class="btn btn-sm btn-danger btn-remover-cg"><i class="fa fa-trash-o"></i></a>';

		var html = "<tr class='row_cg_item' id='"+id+"'>";
		html += "<td>"+(++maxIndex)+"</td><td>"+str+"</td><td>"+unidades.join(",")+"</td><td>"+aLink+"</td></tr>";
		if($('#tbody_cgs_asignatura tr.row_cg_item').length == 0){
			$('#tbody_cgs_asignatura').html(html);
		}else{
			$('#tbody_cgs_asignatura').append(html);
		}

		if(limpiar){
			$('#id_cg').val('');
			$('.ch-sel').each(function (item){
				$(this).prop('checked',false);
			});
		}
	}


    $(document).ready(function(){
    	$('#modal_btn_primary').hide();

    	utils.modal.setTitle('<?= "Competencias genéricas de la asignatura ".$asignatura->nombre_asignatura ?>');

    	var form = $('#frm_cg_asignatura');

    	form.validate({
    		validClass: 'is-valid',
    		errorClass: 'is-invalid',
    		messages: {
    			id_cg: 'Debe seleccionar una competencia genérica'
    		}
    	});

    	form.on('submit', function (e){
    		e.preventDefault();
    		if(form.valid()){

    			var totalUnidades = 0;
    			$('input[name="unidades[]"]').each(function (index){
    				if($(this).is(':checked')){
    					totalUnidades++;
    				}
    			});

    			if(totalUnidades==0){
    				utils.alert.error('Debe seleccionar al menos una unidad');
    				return;
    			}

    			$('#btn_guardar_cg').prop('disabled', true);
    			$.ajax({
    			    url: '<?= ruta("asignatura.agregar_cg_asignatura") ?>',
    			    method: 'post',
    			    data: form.serialize(),
    			    success: function(response){
    					if(response.estado){
    						// utils.modal.success(response.mensaje, 4000);
    						utils.alert.success(response.mensaje);
    						agregarATabla(true);
    					}else{
    						utils.alert.error(response.mensaje);
    						// utils.modal.error(response.mensaje,4000);
    						// alert(response.mensaje);
    					}
    					$('#btn_guardar_cg').prop('disabled', false);
    			    },
    			    error: function(xhr){
    			    	utils.alert.error('Ha ocurrido un error al agregar la competencia genérica');
    			        // utils.modal.error('Ocurrió un error desconocido');
    			        $('#btn_guardar_cg').prop('disabled', false);
    			    }
    			});
    		}
    	});
    });
</script>