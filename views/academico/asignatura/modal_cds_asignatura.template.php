<?php 
$cds = Helpers::options($this->cds, 'id_competencia_disciplinar','descripcion', true);
$form = new Form();
?>
<div class="container">
	<?php $form->begin('frm_cd'); ?>
	<?php $form->inputHidden(['id'=>'id_asignatura','value'=>$this->id_asignatura], true); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			<?php $form->bs4FormSelect([
				'id' => 'id_competencia_disciplinar',
				'required' => true,
				'label' => 'Competencia disciplinar',
				'options' => $cds,
			], true); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<button class="btn btn-success mt-25 btn-block" type="submit">
				<i class="fa fa-save"></i> Agregar
			</button>
		</div>
	</div>
	<?php $form->end(); ?>
	<br><br>
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>Competencia disciplinar</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="tbody_cds"></tbody>
		</table>
	</div>

</div>


<script type="text/javascript">
	
	var cds_sel = <?= json_encode($this->cds_sel); ?>;
	var tbody = $('#tbody_cds');

	function htmlRow(index){
		var item = cds_sel[index];
		var htmlRow = 	'<tr>'+
				'<td>'+item.descripcion+'</td>'+
				'<td>'+
					'<button type="button" data-index="'+index+'" data-cd="'+item.id_competencia_disciplinar+'" data-ape="'+item.id_asignatura+'" class="btn btn-sm btn-danger btn-remover-cdape"><i class="fa fa-trash"></i></button>'+
				'</td>'+
			'</tr>';
		return htmlRow;
	}

	function actualizarListado(indexSpec){
		if(cds_sel.length == 0 || !indexSpec){ tbody.html(''); }
		if(indexSpec){
			tbody.append(htmlRow(indexSpec));
		}else{
			for (var i = 0; i < cds_sel.length; i++) {
				tbody.append(htmlRow(i));
			};
			if(cds_sel.length == 0){
				tbody.append('<tr><td colspan="2" class="text-center">Sin competencias disciplinarias agregadas</td></tr>')
			}
		}
	}

	$(document).off('click','.btn-remover-cdape').on('click','.btn-remover-cdape', function (e){
		e.preventDefault();
		var ape = $(this).data('ape');
		var cd = $(this).data('cd');
		var index = $(this).data('index');
		utils.modal.confirm({
			titulo: 'Eliminar',
			contenido: '¿Confirma que desea remover esta competencia disciplinar de la asignatura?',
			type: 'danger',
			size: 'md',
			success: function (dialog){
				dialog.btn.disabled();
				dialog.body.html('Eliminando...');

				api.post({
					url: '<?= ruta("asignatura.cd_remover_cd_ape") ?>',
					data: {id_asignatura: ape, id_competencia_disciplinar: cd},
					cb: function(response){
						if(response.estado){
							dialog.body.html(successAlert(response.mensaje));
							utils.alert.success(response.mensaje);
							cds_sel.splice(index, 1);
							actualizarListado();
						}
					},
					error: function(){
						dialog.btn.enabled();
						dialog.body.html(errorAlert('Ocurrió un error al intentar remover la competencia disciplinar'));
						utils.alert.error('Ocurrió un error al intentar remover la competencia disciplinar');
					}
				})

			}
		});
	});

    $(document).ready(function(){
    	$('#modal_btn_primary').hide();
		
		utils.modal.setTitle('Competencias disciplinares de la asignatura <?= $this->asignatura->nombre_asignatura ?>');

    	actualizarListado();
		var form = $('#frm_cd');
		form.validate({
			validClass: 'is-valid',
			errorClass: 'is-invalid',
			messages: {
				id_competencia_disciplinar: 'Debe seleccionar una competencia disciplinar',
			}
		});

		form.on('submit', function (e){
			e.preventDefault();
			if(form.valid()){
				api.post({
					url: '<?= ruta("asignatura.cd_agregar_cd_ape") ?>',
					data: form.serialize(),
					cb: function(response){
						if(response.estado){
							utils.alert.success('La competencia disciplinar ha sido agregada');
							cds_sel.push(response.extra);
							actualizarListado(cds_sel.length-1);
						}else{
							utils.alert.error(response.mensaje);
						}
					},
					error: function(){
						utils.alert.error('Ha ocurrido un error al intentar agregar la competencia disciplinar');
					}
				});
			}
		});
    });
</script>