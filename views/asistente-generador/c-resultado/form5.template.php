<div class="container-fluid">
	<button data-toggle="popover" data-trigger="hover" data-container="body" role="button" data-placement="left" data-html="true" data-content="<?= AYUDA_FASES['f4_7'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 4-6. Especificación/Construcción de ADAs</h4>
	<hr class="mb-25">
	
	<div class="table-responsive" style="padding: 2px;">
		<table class="table table-hover table-bordered">
			<colgroup>
				<col width="20%">
				<col width="80%">
			</colgroup>
			<thead>
				<tr class="bg-info">
					<th colspan="2" style="color: white;"> Al crear sus ADAs, considere la competencia, los componentes y las competencias genéricas obtenidas en los pasos anteriores </th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th class="text-right">Competencia</th>
					<td><?= $this->competencia_resultado->competencia_editable ?></td>
				</tr>
				<tr>
					<th class="text-right">Componentes de la competencia</th>
					<td>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<strong>Conocimientos</strong>
								<ul>
									<?php foreach ($this->conocimientos as $index => $conocimiento) { ?>
										<li><?= $conocimiento->descrip_conocimiento ?></li>
									<?php } ?>
								</ul>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<strong>Habilidades</strong>
								<ul>
									<?php foreach ($this->habilidades as $index => $habilidad) { ?>
										<li><?= $habilidad->descrip_habilidad ?></li>
									<?php } ?>
								</ul>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<strong>Actitudes y valores</strong>
								<ul>
									<?php foreach ($this->actitudes as $index => $actitud) { ?>
										<li><?= $actitud->descrip_actitud_valor ?></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th  class="text-right">Competencias genéricas</th>
					<td>
						<?php foreach ($this->cgs as $index => $cg) { ?>
							<div style="margin-top: 5px;">
								<i class="fa fa-check"></i> <?= $cg->descripcion_cg ?>	
							</div>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="card">
		<div class="card-body">
			<button class="btn btn-primary" type="button" onclick="formularioAda()"><i class="fa fa-plus"></i> Nueva ADA</button>
			<button class="btn btn-secondary" type="button" onclick="recargarListadoAda(1)" title="Recargar listado"><i class="fa fa-refresh"></i></button>
		</div>
	</div>
	<div id="contenedor_alertas_uso_adas"></div>

	<div id="contenedor_form_ada"></div>
	<div id="contenedor_adas" style="padding: 15px 0px;"></div>
	<br><br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button class="btn btn-primary" type='button' id="btn_guardar_competencia_fase5">
				Siguiente  <i class="fa fa-arrow-right"></i> 
			</button>
		</div>
	</div>
</div>


<script type="text/javascript">
	var contenedorAdas = $('#contenedor_adas');
	var competencia = <?= json_encode($this->competencia_resultado) ?>;
	var contenedoFormAda = $('#contenedor_form_ada');
	var contenedorAlertasUsoAdas = $('#contenedor_alertas_uso_adas');
	var enEdicion = false;

	function obtenerAlertasUsoAdas() {
		api.get({
			url: '<?= ruta("ada.validar_uso") ?>',
			data: { id_competencia: competencia.id_competencia },
			success: function(response){
				if(response.estado){
					var alertas = response.data || [];
					contenedorAlertasUsoAdas.html('');
					alertas.forEach(function (alerta, index){
						var html = '<div class="mt-10 alert alert-'+(alerta.tipo || 'info')+'"><strong><i class="fa fa-exclamation-triangle"></i> '+alerta.mensaje+'</strong></div>';
						contenedorAlertasUsoAdas.append(html);
					});
				}else{
					utils.alert.warning(response.mensaje || 'Ha ocurrido un error');
				}
			},
			error: function(){
				utils.alert.warning('Ha ocurrido un error al validar el uso de las ADAs');
			}
		})
	}

	function validarYGuardar() {
		if(enEdicion){
			utils.alert.info('Debes guardar o cancelar la ada en edición');
			return;
		}

		utils.busy.show('Guardando datos');

		api.post({
			url: '<?= ruta("asistente.fresultado_guardarf5") ?>',
			data: { id_competencia: competencia.id_competencia  },
			success: function(response){
				utils.busy.hide();
				if(response.estado){
					utils.alert.success(response.mensaje || 'Avances guardados');
					if(response.data.etapa_actual > ultimoPasoGuardado){
						ultimoPasoGuardado = response.data.etapa_actual;
					}
					fase6(true);
				}else{
					utils.alert.warning(response.mensaje || 'Ha ocurrido un error en el servidor');
				}
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error desconocido');
				utils.busy.hide();
			}
		});
	}

	function confirmarFinalizarEdicion(nuevaCarga, idAda){
		utils.modal.confirm({
			titulo: 'Confirmación finalización',
			contenido: 'Existe una ADA en edición. ¿Está seguro de finalizar la edición de esta ADA? Los cambios no guardados se perderán',
			size: 'md',
			type: 'info',
			success: function (dialog){
				dialog.modal.modal('hide');
				if(nuevaCarga){
					enEdicion = false;
					formularioAda(idAda);
				}else{
					esconderFormularioAda(); // es de form5.template.php
				}
			}
		});
	}

	function esconderFormularioAda() {
		contenedoFormAda.html('');
		enEdicion = false;
	}

	function formularioAda(idAda){
		if(enEdicion){
			confirmarFinalizarEdicion(true, idAda);
		}else{
			api.get({
				url: '<?= ruta("asistente.fresultado_formada") ?>',
				data: { id_competencia: idCompetencia, id_ada: idAda || 0 },
				cb: function (response){
					contenedoFormAda.html(response);
					enEdicion = true;
				},
				error: function (){
					contenedoFormAda.html(errorAlert('Ha ocurrido un error al intentar cargar el formulario de la ada'));
					utils.alert.error('Ha ocurrido un error al intentar abrir el formulario');
				}
			});
		}
	}

	function eliminarAda(idAda){
		utils.modal.confirmDelete({
			titulo: 'Confirmación eliminación',
			contenido: '¿Está seguro de eliminar esta ADA?',
			confirm: function(dialog){
				dialog.body.html('Eliminando, espera un momento...');
				api.post({
					url: '<?= ruta("asistente.fresultado_eliminar_ada") ?>',
					data: { id_ada: idAda },
					cb: function(response){
						if(response.estado){
							utils.alert.success(response.mensaje);
							dialog.body.html(successAlert(response.mensaje));
							recargarListadoAda(1);
						}else{
							utils.alert.error(response.mensaje);
							dialog.body.html(errorAlert(response.mensaje));
						}
					},
					error: function(xhrError){
						dialog.body.html(errorAlert('Ha ocurrido un error al intentar eliminar la ADA'));
						utils.alert.error('Ha ocurrido un error al intentar eliminar la ADA');
					}
				});
			}
		})
	}

	function recargarListadoAda(page) {
		obtenerAlertasUsoAdas();
		api.get({
			url: '<?= ruta("asistente.fresultado_ladas") ?>',
			data: { id_competencia: competencia.id_competencia },
			cb: function (response){
				contenedorAdas.html(response);
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al intentar cargar los datos');
				contenedorAdas.html(errorAlert('Ha ocurrido un error al intentar cargar los datos'));
			}
		});
	}

    $(document).ready(function(){
    	contenedoFormAda.html('');
		recargarListadoAda(1);

		$('#btn_guardar_competencia_fase5').off('click').on('click', function(e){
			validarYGuardar();
		});
    });
</script>