<?php
$form = new Form();
$form->begin('frm_rubrica');
$form->inputHidden([
	'id' => 'id_rubrica',
	'value' => $this->rubrica->id_rubrica
]);

$form->inputHidden([
	'id' => 'desde_asistente',
	'value' => 1
]);
?>

<?php  $form->bs4FormInput([
	'id'=>'descripcion_rubrica',
	'label'=>'Descripción',
	'required' => true,
	'value' => $this->nuevo ? '' : $this->rubrica->descripcion_rubrica,
	],true); ?>

<?php  $form->bs4FormTextarea([
	'id'=>'explicacion_rubrica',
	'label'=>'Explicación',
	'placeholder'=>'Explicación',
	'required' => false,
	'value' => $this->nuevo ? '' : $this->rubrica->explicacion_rubrica,
	'rows' => '3'
	],true); ?>
<?php $form->end(); ?>

<script type="text/javascript">
    $(document).ready(function(){
		
		var form = $('#frm_rubrica');

		form.validate();

		form.on('submit', function(e){
			e.preventDefault();
			if(form.valid()){
				utils.modal2.btn.disabled();
				api.post({
					url: '<?= ruta("rubrica.guardar") ?>',
					data: form.serialize(),
					cb: function (response){
						$('#btn_guardar_rubrica').enabled();
						if(response.estado){
							utils.alert.success(response.mensaje);
							rubricaCreada(true, response.extra);
							utils.modal2.hide();
						}else{
							utils.alert.error(response.mensaje);
						}
						utils.modal2.btn.enabled();
					},
					error: function (){
						utils.modal2.btn.enabled();
						$('#btn_guardar_rubrica').enabled();
						utils.alert.error('Ha ocurrido un error al intentar guardar los datos.');
					}
				});
			}
		});

		utils.modal2.btn.onClick(function(){
			form.submit();
		});
    });
</script>