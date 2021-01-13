<?php 
$asignaturas = $data->fromBody('asignaturas', []);
$pe = $data->fromBody('pe');
$idPE = $pe->id_pe;
$asignaturaPE = $data->fromBody('asignatura_pe');

$lista = [];
foreach ($asignaturas as $key => $a) {
	$lista[] = ['label'=>$a->nombre_asignatura, 'value'=>$a->id_asignatura];
}

$form = new BaseForm();
$form->bs4FormSelect('id_asignatura','Seleccione una asignatura para añadir a '.$pe->nombre_pe.': ','id_asignatura',$lista, '',true);
 ?>
<div class="container">
	<?php 
	$form->beginForm('frm_asignatura_pe');
	$form->inputHidden('id_pe','id_pe',$idPE);
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			<?php $form->render('id_asignatura'); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<button type="submit" class="btn btn-primary" style="margin-top: 27px;"><i class="fa fa-save"></i> Agregar</button>
		</div>
	</div>
	<?php
	$form->endForm();
	?>
	
	<table class="table table-hover table-bordered table-striped">
		<colgroup>
			<col width="80%">
			<col width="20%">
		</colgroup>
		<thead class="thead-dark">
			<tr>
				<th>Asignatura</th>
				<th>Acción</th>
			</tr>
		</thead>
		<tbody id="tbody_asignaturas_pe"></tbody>
	</table>

</div>

<script type="text/javascript">
	
	var id_pe = <?= $pe->id_pe ?>;

	$('#modal_btn_primary').hide();

	var form_asignatura_pe = $('#frm_asignatura_pe');

	var obtener_asignaturas_pe = function(){
		api.get({
			url: '<?= ruta("pe.asignaturas") ?>',
			data: { id_pe: <?= $pe->id_pe ?>},
			success: function(response){
				if(response.estado){
					$('#tbody_asignaturas_pe').html('');
					if(response.extra.length > 0){
						response.extra.forEach(function (item, index){
							var tr = "<tr><td>"+item.nombre_asignatura+"</td>";
							tr += "<td><button data-toggle='tooltip' data-placement='left' title='Eliminar del plan de estudios' type='button' onclick='eliminar_asignatura_pe("+item.id_pe+","+item.id_asignatura_pe+")' class='btn btn-sm btn-outline-danger'><i class='fa fa-trash-o'></i></td> </tr>";
							$('#tbody_asignaturas_pe').append(tr);
						});
					}else{
						$('#tbody_asignaturas_pe').html('<tr><td colspan="2"><div class="alert alert-info text-center">No se encontraron asignaturas</div></td></tr>');
					}
				}else{
					utils.alert.error(response.mensaje);
				}
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al obtener las asignaturas');
			}
		});
	};

	var eliminar_asignatura_pe = function(id_pe, id_asignatura_pe){
		utils.modal.confirm({
			titulo: 'Confirmación',
			contenido: '¿Confirma que desea eliminar esta asignatura del plan de estudios?',
			size: 'md',
			type: 'danger',
			vertical_center: true,
			success: function(dialog){
				dialog.body.html('Eliminando...');
				dialog.btn.prop('disabled', true);
				api.post({
					url: '<?= ruta("pe.eliminar_asignatura") ?>',
					data: { id_pe: id_pe, id_asignatura_pe: id_asignatura_pe },
					success: function(response){
						dialog.btn.prop('disabled', false);
						if(response.estado){
							dialog.modal.modal('hide');
							utils.alert.success(response.mensaje);
							obtener_asignaturas_pe();
						}else{
							utils.alert.error(response.mensaje);
						}
					},
					error: function(){
						utils.alert.error('Ha ocurrido un error al eliminar la asignatura del plan de estudios');
					}
				})
			}
		});
	};

    $(document).ready(function(){
    	obtener_asignaturas_pe();

    	form_asignatura_pe.validate();

    	form_asignatura_pe.on('submit', function(e){
    		e.preventDefault();
    		if(form_asignatura_pe.valid()){
    			var id_asignatura = $('#id_asignatura').val();
    			api.post({
    				url: '<?= ruta("pe.agregar_asignatura") ?>',
    				data: { id_pe: id_pe, id_asignatura: id_asignatura },
    				success: function(response){
    					if(response.estado){
    						utils.alert.success(response.mensaje);
    						$('#id_asignatura').val('');
    						obtener_asignaturas_pe();
    						cargarListado(1);
    					}else{
    						utils.alert.error(response.mensaje);
    					}
    				},
    				error: function(){
    					utils.alert.error('Ha ocurrido un error al intentar agregar la asignatura al plan de estudios');
    				}
    			});
    		}
    	})

    });



</script>