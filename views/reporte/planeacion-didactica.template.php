<div class="container">
	<h4>Formato de la Planeación Didáctica</h4>
	<div class="card mb-20">
		<div class="card-body">
			<form id="frm_busqueda_asignatura">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="form-group">
						    <label>Seleccione una asignatura</label> 
						    <div class="input-group">
							    <select id="asigntura_reporte_id" name="asigntura_reporte_id" required="true" class="custom-select">
									<option value="">Espere...</option>
							    </select>
							    <div class="input-group-append">
							    	<button type="button" onclick="obtenerFormatoInicial()" class="btn btn-primary">
							    		<i class="fa fa-check"></i> Precargar
							    	</button>
							    </div>
						    </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<button onclick="obtenerAsignaturas()" type="button" class="btn mt-25 btn-secondary">
							<i class="fa fa-refresh"></i> Actualizar
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="contenedor_wizard">
		<div id="smartwizard" style='margin: 10px;'>
			<ul>
				<li><a href="#step-1">Formato editable</a></li>
				<li><a href="#step-2">Formato PDF</a></li>
			</ul>

			<div>
				<div id="step-1" style='padding:10px;'>
					<h3 style="margin-bottom: 5px; margin-top: 30px;">1. Planeación didáctica (Formato editable) <button onclick="copiarContenidoEditablePD()" id="btn_copiar_contenido_formato_editable_pe" type="button" data-toggle="tooltip" data-placement="right" title="Copiar contenido" class="btn btn-sm btn-outline-secondary d-none"><i class="fa fa-clipboard"></i></button></h3>
					<textarea name="textarea_formato_pd" id="textarea_formato_pd"></textarea>
					<div class="mt-25 mb-20 text-right">
						<button onclick="generarFormatoPDF()" type="button" class="btn btn-primary mt-10">Siguiente <i class="fa fa-arrow-right"></i></button>
					</div>
				</div>
				<div id="step-2" style='padding:10px;'>
					<h3 id="titulo_seccion_formato_pe_pdf" class="d-none" style="margin-bottom: 5px; margin-top: 30px;">2. Planeación didáctica (Formato PDF)</h3>
					<div class="mt-10">
						<h3 id="titulo_asignatura"></h3>
						<div class="text-center" id="view_loading_info"></div>
						<object style="width: 100%; height: 450px;" data="" type="application/pdf" id="object_formato_generado"></object>
					</div>
					
					<div class="row mt-25 mb-20">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<button type="button" onclick="anterior()" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Anterior</button>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-right">
							<button type="button" onclick="finalizarEdicion()" class="btn btn-primary">Finalizar <i class="fa fa-arrow-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>




</div>

<script type="text/javascript">

	var primeraCarga = true;
	
	var objs = {
		asigntura_reporte_id: $('#asigntura_reporte_id'),
		titulo_asignatura: $('#titulo_asignatura'),
		view_loading_info: $('#view_loading_info'),
		object_formato_generado: $('#object_formato_generado'),
		textarea_formato_pd: $('#textarea_formato_pd'),
	};

	function obtenerFormatoInicial() {
		var id = objs.asigntura_reporte_id.val();
		if(id){
			loading(objs.view_loading_info, 'Cargando formato');
			utils.busy.show('Obteniendo el formato');
			iniciarEdicion(function(){
				api.get({
					url: '<?= ruta("reporte.generar_pd") ?>',
					data: { id_asignatura: id, preview: 1 },
					success: function(response){
						notLoading(objs.view_loading_info, '');
						utils.busy.hide();
						tinymce.get('textarea_formato_pd').setContent(response);
					},
					error: function(){
						utils.busy.hide();
						notLoading(objs.view_loading_info, '');
					}
				});
			});
		}else{
			finalizarEdicion();
			utils.alert.warning('Debes seleccionar una asignatura');
		}
	}

	function generarFormatoOriginal() {
		var id = objs.asigntura_reporte_id.val();
		if(id){
			loading(objs.view_loading_info, 'Cargando formato');
			utils.busy.show('Generando el formato');
			api.get({
				url: '<?= ruta("reporte.generar_pd") ?>',
				data: { id_asignatura: id, },
				success: function(response){
					notLoading(objs.view_loading_info, '');
					utils.busy.hide();
					if(response.estado){
						objs.object_formato_generado.prop('data', response.data.url);
					}else{
						utils.alert.warning(response.mensaje || 'Ha ocurrido un error al generar el formato');
					}
				},
				error: function(){
					utils.busy.hide();
					notLoading(objs.view_loading_info, '');
				}
			});
		}else{
			reiniciarVista();
			utils.alert.warning('Debes seleccionar una asignatura');
		}
	};

	function obtenerAsignaturas() {
		api.get({
			url: '<?= ruta("shared.asignatura") ?>',
			success: function(response){
				if(response.estado){
					var items = [ { id: '', label: 'Seleccione una asignatura' } ];
					(response.data || []).forEach(function (item, index){
						items.push({id: item.id_asignatura, label: item.nombre_asignatura});
					});

					objs.asigntura_reporte_id.fill(items);
					if((response.data || []).length == 1){
						objs.asigntura_reporte_id.val(response.data[0].id_asignatura);
					}
				}else{
					utils.alert.warning(response.mensaje || 'Ha ocurrido un error al obtener las asignaturas');
				}
			}, 
			error: function(){
				utils.alert.error('Ha ocurrido un error al obtener las asignaturas');
			}
		})
	}

	function copiarContenidoEditablePD(formato) {
		tinymce.get('textarea_formato_pd').execCommand('selectAll', true,'id_text');
		tinymce.get('textarea_formato_pd').execCommand('copy', true, 'id_text');
		utils.alert.info('Formato copiado al portapapeles');
	}

	function generarFormatoPDF() {
		var contenido = tinymce.get('textarea_formato_pd').getContent();
		var id_asignatura = objs.asigntura_reporte_id.val();
		if(contenido && id_asignatura){
			objs.object_formato_generado.prop('data', '');
			$('#smartwizard').smartWizard('next');
			utils.busy.show('Generando formato en PDF');
			api.post({
				url: '<?= ruta("reporte.generar_pd_desde_html") ?>',
				data: {
					contenido: contenido,
					id_asignatura: id_asignatura,
				},
				success: function(response){
					utils.busy.hide();
					if(response.estado){
						objs.object_formato_generado.prop('data', response.data.url);
					}else{
						utils.alert.warning(response.mensaje || 'Ha ocurrido un error al generar el formato');
					}
				},
				error: function(){
					utils.alert.error('Ha ocurrido un error al intentar generar el formato PDF');
				}
			})
		}else{
			utils.alert.info('Sin contenido');
		}

	}

	function iniciarEdicion(cargaFinalizadaCB) {
		$('#contenedor_wizard').show();

		objs.object_formato_generado.prop('data', '');

	    $('#smartwizard').smartWizard({
            selected: 0,
            // theme: 'arrows',
            keyNavigation: false,
            transitionEffect:'fade',
            enableFinishButton: true,
            useURLhash: false,
            disabledSteps: [2],
            labelNext:'Siguiente', // label for Next button
			labelPrevious:'Anterior', // label for Previous button
			labelFinish:'Finalizar',
			theme: 'dots',
            toolbarSettings: {
            	toolbarPosition: 'none',
            	showNextButton: false,
            	showPreviousButton: false,
            },
            lang: {
            	next: 'Siguiente',
            	previous: 'Anterior'
            }
	    });

	    // Set selected theme on page refresh
	    $("#theme_selector").change();

		tinymce.remove();

		tinymce.init({
			selector: '#textarea_formato_pd',
		    language: 'es_MX',
		    height: 500,
		    content_css: '<?= css("estilo_formato_pd.css") ?>',
		    plugins: [
		      'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
		      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
		      'save table contextmenu directionality emoticons template paste textcolor'
		    ],
		    // content_css: 'css/content.css',
		    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons',
		    setup: function(editor){
		    	$('#btn_copiar_contenido_formato_editable_pe').removeClass('d-none');
				
				editor.on('init', function(e){
					cargaFinalizadaCB();
				});

				// setTimeout(function() {
		    		
				// }, 1000);

		    },
		});

		if(primeraCarga){
			primeraCarga = false;
		}else{
			anterior();
		}
	}

	function finalizarEdicion() {
		$('#contenedor_wizard').hide();
	}

	function anterior() {
		$('#smartwizard').smartWizard("prev");
	}


    $(document).ready(function(){
		obtenerAsignaturas();

		finalizarEdicion();
    });
</script>