<div class="container-fluid">
	<button data-toggle="popover" data-trigger="hover" data-container="body" role="button" data-placement="left" data-html="true" data-content="<?= AYUDA_FASES['f8'] ?>" type="button" class="ayuda-popover btn btn-circle btn-info btn-circle-sm pull-right">
		<i class="fa fa-question"></i>
	</button>
	<h4 class="mt-25">Fase 7. Exportación de ADAs</h4>
	<hr class="mb-25">

	<div class="card mb-10">
		<div class="card-header">Exportar formatos ADA</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
					<div class="form-group mb-20">
						<label for="ada_seleccionada_id"><strong>Seleccione la actividad de aprendizaje que desea exportar</strong></label>
						<select class="custom-select" onchange="validarSeleccion()" name="ada_seleccionada_id" id="ada_seleccionada_id">
							<option value="">Seleccione una ADA</option>
						</select>
					</div>

				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
					<button style="margin-top: 25px;" type="button" onclick="obtenerADAs()" class="btn btn-block btn-secondary" data-toggle="tooltip" data-placement="top" title="Actualizar lista de ADAs">
						<i class="fa fa-refresh"></i> Actualizar
					</button>
				</div>
			</div>
			<label><strong>Seleccione los resultados de aprendizaje que están asociados con esta ADA</strong></label>
			<div id="contenedor_items_competencias" class="mb-20">
				<?php foreach ($this->todas_competencias as $index => $competencia): ?>
					<label for="ch_sel_comp_<?= $index ?>" class="cont-ch-normal mt-10">
					  <input class="ch-item-competencia-extra" type="checkbox" id="ch_sel_comp_<?= $index ?>" name="ada_competencia_extra[]" value="<?= $competencia->id_competencia; ?>" <?= $competencia->id_competencia == $this->competencia->id_competencia ? "checked disabled" : "" ?>>
					  <span class="checkmark"></span> &nbsp; <?= $competencia->competencia_editable ?>
					</label>
				<?php endforeach ?>
			</div>

			<button id="btn_generar_formato_ada" onclick="guardarAsociacionAdaCompetencias()" type="button" disabled class="btn btn-primary"><i class="fa fa-check"></i> Generar ADA</button>
			<br>
			<br>

			<h3 style="margin-bottom: 5px; margin-top: 30px;">Texto de la ADA para el estudiante <button id="btn_copiar_contenido_estudiante" type="button" onclick="copiarContenido('estudiante')" data-toggle="tooltip" data-placement="right" title="Seleccionar y copiar texto de la ADA" class="btn btn-sm btn-secondary d-none"><i class="fa fa-clipboard"></i></button></h3>
			<textarea name="textarea_formato_ada_estudiante" id="textarea_formato_ada_estudiante"></textarea>

			<br>
			<h3 style="margin-bottom: 5px;">Información resumida para el profesor <button id="btn_copiar_contenido_profesor" type="button" onclick="copiarContenido('profesor')" data-toggle="tooltip" data-placement="right" title="Seleccionar y copiar texto de la ADA" class="btn btn-sm btn-secondary d-none"><i class="fa fa-clipboard"></i></button></h3>
			<textarea name="textarea_formato_ada_profesor" id="textarea_formato_ada_profesor"></textarea>
		</div>
	</div>	

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
			<button class='btn btn-secondary' type='button' onClick='tabAnterior()'>
				<i class="fa fa-arrow-left"></i> Anterior
			</button>&nbsp;
			<button onclick="confirmarFinalizarCompetencia()" class="btn btn-primary" type='button' id="btn_guardar_competencia_fase6">
				Finalizar  <i class="fa fa-arrow-right"></i> 
			</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	var f6 = {
		editor_cargado: false,
		competencia: <?= $this->competencia ? json_encode($this->competencia) : '{}' ?>,
		adas: [],
	};

	var objs = {
		select_ada                      : $('#ada_seleccionada_id'),
		textarea_formato_ada_estudiante : $('#textarea_formato_ada_estudiante'),
		textarea_formato_ada_profesor   : $('#textarea_formato_ada_profesor'),
		btn_generar_formato_ada         : $('#btn_generar_formato_ada'),
		btn_copiar_contenido_estudiante : $('#btn_copiar_contenido_estudiante'),
		btn_copiar_contenido_profesor   : $('#btn_copiar_contenido_profesor'),
		btn_finalizar                   : $('#btn_guardar_competencia_fase6'),
		contenedor_items_competencias   : $('#contenedor_items_competencias'),
	};

	function guardarAsociacionAdaCompetencias() {
		var id_ada = objs.select_ada.val();
		if(id_ada){
			var competencias_extras_ids = [];
			$("input[name='ada_competencia_extra[]']").each(function(index){
				var _this = $(this);
				if(_this.is(':checked')){
					competencias_extras_ids.push(_this.val());
				}
			});

			if(competencias_extras_ids.length > 0){

				utils.busy.show('Espere un momento por favor...');
				
				var datos = {
					id_ada: id_ada,
					competencias_extras_ids: competencias_extras_ids
				};

				api.post({
					url: '<?= ruta("asistente.fresultado_f6_guardar_ada_competencias") ?>',
					data: { id_ada: id_ada, competencias_extras_ids: competencias_extras_ids },
					success: function(response){
						if(response.estado){
							generarFormatoAda();
						}else{
							utils.busy.hide();
							utils.alert.warning(response.mensaje || 'Ha ocurrido un error');
						}
					},
					error: function(){
						utils.busy.hide();
						utils.alert.error('Ha ocurrido un error al guardar las asociaciones de la ADA con los resultados seleccionados');
					}
				})

			}else{
				utils.alert.warning('Debe seleccionar al menos un resultado de aprendizaje');
			}
		}
	};

	function confirmarSalida() {
		utils.modal.confirm({
			titulo: 'Confirmar salida',
			contenido: '¿Deseas salir de la edición?',
			static: true,
			size: 'md',
			type: 'primary',
			success: function(dialog){
				dialog.modal.modal('hide');
				utils.goToUrl('<?= ruta("competenciafin", ['finalizado'=> 1]) ?>');
			}
		});
	}

	function finalizarCompetencia(){
		utils.busy.show('Espere un momento...');
		api.post({
			url: '<?= ruta("asistente.fresultado_guardarf6") ?>',
			data: { id_competencia: f6.competencia.id_competencia },
			success: function(response){
				utils.busy.hide();
				if(response.estado){
					utils.alert.success(response.mensaje);
					setTimeout(function() {
						confirmarSalida();
					}, 350);
				}else{
					utils.alert.warning(response.mensaje || 'Ha ocurrido un error al intentar realizar la acción');
				}
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al intentar realizar la acción');
				utils.busy.hide();
			}
		})
	}

	function confirmarFinalizarCompetencia() {
		utils.modal.confirm({
			titulo: 'Confirmación',
			size: 'md',
			type: 'primary',
			contenido: '¿Confirma que desea finalizar el proceso? El estado de esta evaluación cambiará a \'Finalizado\'',
			// contenido: '¿Estas seguro de finalizar la creación de esta competencia de resultado? Pasará a un estatus de \'Finalizado\'',
			success: function(dialog){
				// $("html, body").animate({ scrollTop: 0 }, "slow");
				dialog.modal.modal('hide');
				finalizarCompetencia();
			}
		});
	};

	function copiarContenido(formato) {
		if(formato == 'estudiante'){
			tinymce.get('textarea_formato_ada_estudiante').execCommand('selectAll', true,'id_text');
			tinymce.get('textarea_formato_ada_estudiante').execCommand('copy', true, 'id_text');
		}else{
			tinymce.get('textarea_formato_ada_profesor').execCommand('selectAll', true,'id_text');
			tinymce.get('textarea_formato_ada_profesor').execCommand('copy', true, 'id_text');
		}		
		utils.alert.info('Formato copiado al portapapeles');

		// var content = null;
		// if(formato == 'estudiante'){
		// 	tinymce.get('textarea_formato_ada_estudiante').execCommand('selectAll', true,'id_text');
		// 	tinymce.get('textarea_formato_ada_estudiante').execCommand('copy', true, 'id_text');
		// 	utils.alert.info('Formato copiado al portapapeles');
		// 	return;
		// 	content = tinymce.get('textarea_formato_ada_estudiante').getContent();
		// }else{
		// 	content = tinymce.get('textarea_formato_ada_profesor').getContent();
		// }
		// if(content){
		// 	const el = document.createElement('textarea');
		//   	el.value = content;
		//   	el.setAttribute('readonly', '');
		//   	el.style.position = 'absolute';
		//   	el.style.left = '-9999px';
		//   	document.body.appendChild(el);
		//   	el.select();
		//   	document.execCommand('copy');
		//   	document.body.removeChild(el);
		//   	utils.alert.success('Formato copiado al portapapeles');
		// }
	}

	function inicializarEditor(){
		f6.editor_cargado = true;
		tinymce.remove();

		tinymce.init({
			selector: '#textarea_formato_ada_estudiante',
		    language: 'es_MX',
		    height: 300,
		    plugins: [
		      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
		      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
		      'save table contextmenu directionality emoticons template paste textcolor'
		    ],
		    // content_css: 'css/content.css',
		    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons',
		    setup: function(editor){
		    	objs.btn_copiar_contenido_estudiante.removeClass('d-none');
		    }
		});

		tinymce.init({
			selector: '#textarea_formato_ada_profesor',
		    language: 'es_MX',
		    height: 300,
		    plugins: [
		      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
		      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
		      'save table contextmenu directionality emoticons template paste textcolor'
		    ],
		    // content_css: 'css/content.css',
		    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons',
		    setup: function(editor){
		    	objs.btn_copiar_contenido_profesor.removeClass('d-none');
		    }
		});

	}

	function cargarFormato(id_ada, formato, callback) {
		return new Promise(function(resolve, reject){
			api.get({
				url: '<?= ruta("ada.formato_exportar") ?>',
				data: { id_ada: id_ada, formato: formato },
				success: function(htmlResponse){
					resolve(htmlResponse);
				},
				error: function(error){
					reject({message: 'Ha ocurrido un error al intentar generar el formato del '+formato, custom: true});
				}
			});
		});
	};


	function generarFormatoAda() {

		if(!f6.editor_cargado){
			inicializarEditor();
		}

		// objs.textarea_formato_ada.val('');
		var id_ada = objs.select_ada.val();

		cargarFormato(id_ada, 'estudiante').then(function(htmlFormatoEstudiante){
			return cargarFormato(id_ada, 'profesor').then(function(htmlFormatoProfesor){
				utils.busy.hide();
				utils.alert.success('Formato generado correctamente');
				tinymce.get('textarea_formato_ada_estudiante').setContent(htmlFormatoEstudiante);
				tinymce.get('textarea_formato_ada_profesor').setContent(htmlFormatoProfesor);
			});
		}).catch(function(error){
			var msj = 'Ha ocurrido un error al generar el formato';
			if(error && error.message){
				msj = error.message;
			}
			utils.busy.hide();
			utils.alert.error(msj);
		});
	}

	function validarSeleccion() {
		var ada_id = objs.select_ada.val();
		objs.btn_generar_formato_ada.prop('disabled', ada_id ? false : true);
		if(ada_id){
			
			utils.busy.show('Obteniendo resultados de aprendizaje asociados a esta ADA');

			api.get({
				url: '<?= ruta("asistente.fresultado_f6_ada_competencias") ?>',
				data: { id_ada: ada_id },
				success: function(response){
					utils.busy.hide();
					if(response.estado){
						$("input[name='ada_competencia_extra[]']").each(function(index){
							var _this = $(this);
							var id_comp = parseInt(_this.val() || 0);
							var comp = (response.data || []).find(function(item){ return item.id_competencia == id_comp; });
							_this.prop('checked', comp ? true : false);
							_this.prop('disabled', false);

							if(id_comp == f6.competencia.id_competencia){
								if(comp){
									_this.prop('checked', true);
									_this.prop('disabled',true);
								}
							}
						});
					}else{
						utils.alert.warning(response.mensaje || 'Ha ocurrido un error al obtener los resultados asociados a la ADA seleccionada');
					}
				},
				error: function(){
					utils.busy.hide();
					utils.alert.warning('Ha ocurrido un error al obtener los resultados asociados a la ADA seleccionada');
				}
			});
		}
	}

	function llenarAdas() {
		var items = [{id: '', label: 'Seleccione una ADA'}];
		f6.adas.forEach(function (item, index){
			items.push({
				id: item.id_ada,
				label: item.nombre_ada,
			});
		});

		objs.select_ada.fill(items);
		// if(items.length == 2){
		// 	objs.select_ada.val(items[1].id);
		// 	validarSeleccion();
		// 	generarFormatoAda();
		// }
	}

	function obtenerADAs() {
		utils.busy.show('Obteniendo resultados asociados a esta ADA');
		f6.adas = [];
		api.get({
			url: '<?= ruta("ada.listado") ?>',
			data: { id_competencia: f6.competencia.id_competencia, },
			success: function(response){
				utils.busy.hide();
				if(response.estado){
					f6.adas = response.data || [];
					llenarAdas();
				}else{
					utils.alert.warning(response.mensaje);
				}
			},
			error: function(){
				utils.busy.hide();
				utils.alert.error('Ha ocurrido un error al obtener las ADAs');
			}
		});
	}

    $(document).ready(function(){
		obtenerADAs();
		inicializarEditor();
    });
</script>