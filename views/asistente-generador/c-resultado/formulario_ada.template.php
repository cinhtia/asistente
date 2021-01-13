<style>
	.form-check-label{
		font-size: 110%;
	}
</style>
<form id="frm_ada">
	<div class="card border-primary mt-20 mb-20">
		<div class="card-header bg-primary text-white" id="titulo_form_ada"><?= $this->ada->id_ada ? 'Editar ADA' : 'Nueva ADA'; ?></div>
		<div class="card-body">
			<div id="alerta_info_completar_datos_previos">
				<div class="alert alert-<?= $this->ada->id_ada ? 'success' : 'info' ?> ">
					<strong>Ingresa la modalidad, el nombre, las instrucciones y procedimientos. Seguidamente oprime el botón para obtener las sugerencias automáticas de las herramientas para esta ADA</strong>
				</div>
			</div>
			<input type="hidden" value="<?= $this->ada->id_ada ?>" name="id_ada" id="id_ada">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="form-group">
					    <label>Nombre de la actividad <span class="text-danger">*</span></label> 
					    <input onchange="validarSiMostrarBtnSugerencia()" type="text" value="<?= $this->ada->nombre_ada ?>" name="nombre_ada" id="nombre_ada" required="true" class="form-control" placeholder="Nombre de la actividad"/>
					</div>	
				</div>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="form-group">
					    <label>Modalidad de la actividad <span class="text-danger">*</span></label> 
					    <select onchange="validarSiMostrarBtnSugerencia()" name="modalidad_ada" id="modalidad_ada" required="true" class="form-control">
							<option value="">Selecciona una modalidad</option>
							<option value="individual" <?= 'individual' == $this->ada->modalidad_ada ? 'selected' : ''; ?>>Individual</option>
							<option value="pares" <?= 'pares' == $this->ada->modalidad_ada ? 'selected' : ''; ?>>Pares</option>
							<option value="equipo" <?= 'equipo' == $this->ada->modalidad_ada ? 'selected' : ''; ?>>Equipo</option>
							<option value="grupal" <?= 'grupal' == $this->ada->modalidad_ada ? 'selected' : ''; ?>>Grupal</option>
					    </select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
					<div class="text-right">
						<button class="btn btn-block mt-25 btn-secondary" onclick="abrirFormSugerenciaPalabras()" id="btn_sugerir_palabras_clave" type="button" data-toggle="tooltip" data-placemente="bottom" title="Puedes mejorar la recomendación de la herramienta seleccionada, indicando las algunas palabras clave">
							<i class="fa fa-check"></i> Sugerir <br>palabras <br>clave
						</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="form-group">
						<label>Instrucciones <span class="text-danger">*</span></label>
						<textarea onchange="validarSiMostrarBtnSugerencia()" name="instruccion_ada" class="form-control" id="instruccion_ada" required="true" placeholder="Describe las instrucciones aquí" rows="3"><?= $this->ada->instruccion_ada ?></textarea>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
					<div class="form-group">
						<label>Procedimiento de la actividad <span class="text-danger">*</span></label>
						<textarea onchange="validarSiMostrarBtnSugerencia()" name="procedimiento_ada" class="form-control" id="procedimiento_ada" required="true" placeholder="Describe el procedimiento aquí" rows="3"><?= $this->ada->procedimiento_ada ?></textarea>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
					<button type="button" <?= $this->ada->id_ada ? '' : 'd123isabled' ?> id="btn_obtener_recomendaciones" onclick="obtenerRecomendacion()" class="btn btn-primary mt-25 btn-block"><i class="fa fa-check"></i> Sugerir <br>herramienta</button>
				</div>
			</div>
			<div id="contenedor_f2">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3" id="col_herramienta">
						<div id="container_tooltip"></div>
						<div class="form-group">
						    <label>Herramienta</label> 
						    <div class="input-group">
							    <select onchange="debeMostrarOtraHerramienta()" name="id_herramienta" id="id_herramienta" class="form-control custom-select">
							    	<?php if($this->herramientas != null){ ?>
							    		<?php foreach ($this->herramientas as $herr) { ?>
										<option title="<?= $herr->explicacion_herramienta ?>" value="<?= $herr->id_herramienta ?>" <?= $this->ada->id_herramienta == $herr->id_herramienta ? 'selected' : '' ?>><?= $herr->descripcion_herramienta ?></option>
							    		<?php } ?>
							    		<option value="">Otra</option>
							    	<?php } ?>
							    </select>
						    	<div class="input-group-append">
						    		<button type="button" class="btn btn-primary" onclick="seleccionarHerramienta()" id="btn_seleccionar_herramienta_manualmente" data-toggle="tooltip" data-placemente="top" title="Seleccionar de listado completo">
						    			<i class="fa fa-search"></i>
						    		</button>
						    	</div>
						    </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 d-none" id="col_otra_herramienta">
						<div class="form-group" id="div_otra_herramienta">
							<label>Especifíque otra herramienta</label>
						    <input type="text" <?= $this->ada->id_herramienta != null ? 'disabled' : ''; ?> value="<?= $this->ada->otra_herramienta ?>" name="otra_herramienta" id="otra_herramienta" class="form-control" placeholder="Especificar herramienta"/>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group">
						    <label>Tiempo estimado (Horas) <span class="text-danger">*</span></label> 
						    <input type="number" value="<?= $this->ada->duracion_horas ?>" min="1" step="1" id="duracion_horas" name="duracion_horas" required="true" class="form-control" placeholder="Tiempo estimado"/>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					    <div class="form-group">
					        <label>Fecha y hora entrega <span class="text-danger">*</span></label> 
					        <div class="input-group">
					        	<input readonly autocomplete="off" type="text" value="<?= $this->ada->fecha_fin_ada ? Helpers::formatoFecha($this->ada->fecha_fin_ada, 'd/m/Y H:i') : '' ?>" name="fecha_fin_ada" id="fecha_fin_ada" required="true" class="form-control" placeholder="Fecha y hora entrega"/>
					        	<div class="input-group-append">
					        		<div class="input-group-btn">
					        			<button class="btn btn-secondary" type="button" onclick="abrirCalendario()">
					        				<i class="fa fa-calendar-o"></i>
					        			</button>
					        		</div>
					        	</div>
					        </div>
					    </div>
					</div>
					<!-- <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group">
						    <label>Ponderación <span class="text-danger">*</span></label> 
						    <div class="input-group">
						    	<input type="number" value="<?= $this->ada->ponderacion ?>" min="1" max="100" step="1" name="ponderacion" id="ponderacion" required="true" class="form-control" placeholder="Ponderación"/>
						    	<div class="input-group-append"><div class="input-group-text">%</div></div>
						    </div>
						</div>
					</div> -->
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="form-group">
						    <label>Instrumento de evaluación <span class="text-danger">*</span></label> 
					        <select name="id_instrumento_eval" id="id_instrumento_eval" required="true" class="form-control">
					    		<option value="">Selecciona un instrumento</option>
					    		<?php foreach ($this->instrumentos as $index => $item) { ?>
					    		<option value="<?= $item->id_instrumento_eval ?>" <?= $this->ada->id_instrumento_eval == $item->id_instrumento_eval ? 'selected' : '' ?>><?= $item->descripcion_instrum_eval ?></option>
					    		<?php } ?>
					    		<option value="">Otra</option>
					        </select>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<!-- Dependiendo del tipo de instrumento -->
						<div class="form-group" id="contenedor_rubricas">
						    <label>Rúbrica <span class="text-danger">*</span></label> 
						    <div class="input-group">
							    <select name="id_rubrica" id="id_rubrica" required="true" class="custom-select">
									<option value="">Selecciona una rúbrica</option>
									<?php foreach ($this->rubricas as $index => $item) { ?>
									<option value="<?= $item->id_rubrica ?>" <?= $this->ada->id_rubrica == $item->id_rubrica ? 'selected' : ''; ?>><?= $item->descripcion_rubrica ?></option>
									<?php } ?>
							    </select>
							    <div class="input-group-append">
							    	<button class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Crear rúbrica" onclick="abrirFormRubrica()" id="btn_form_rubrica" type="button"><i class="fa fa-plus"></i></button>
							    </div>
						    </div>
						</div>
					</div>
				    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				    	<div class="form-group" id="contenedor_plantillas_rubrica">
				    		<label for="nombre_plantilla">Plantilla <span class="text-danger">*</span></label>
				    		<div class="input-group">
					    		<select required onchange="plantillaRubricaSeleccionada()" name="plantilla_rubrica_id" id="plantilla_rubrica_id" class="form-control custom-select">
					    			<option value="">Selecciona una plantilla</option>
					    			<?php if($this->plantillas_rubrica != null){ ?>
									<?php foreach ($this->plantillas_rubrica as $pr) { ?>
									<option value="<?= $pr->id_plantilla_rubrica ?>" <?= $this->ada->id_plantilla_rubrica == $pr->id_plantilla_rubrica ? 'selected' : '' ?> ><?= $pr->nombre ?></option>
									<?php } ?>
					    			<?php } ?>
					    		</select>
				    			<div class="input-group-append">
				    				<button class="btn btn-sm btn-success" onclick="verPlantillaRubrica()" id="btn_ver_plantilla_rubrica" type="button"><i class="fa fa-edit"></i></button>
				    			</div>
				    		</div>
				    	</div>
				    </div>
				</div>

				<!-- <div class="row">
				</div> -->

				<div class="row">
				    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				      <div class="form-group">
				      	<p>Agentes de evaluación <span class="text-danger">*</span></p>
				      	<?php $opts = explode(",", $this->ada->agentes_eval.""); ?>

				      	<div class="">
				      	   	<label class="form-check-label cont-ch-normal d-block" style="margin-top: 10px;" for="ch_profesor">
				      	   		<input type="checkbox" value="profesor" <?= in_array('profesor', $opts) ? 'checked' : ''; ?> 
				      	   			name="agente_eval[]" class="form-check-input agentes ch-sel" id="ch_profesor">
				      	   		<span class="checkmark"></span> Profesor
				      	   	</label>
				      	   	<label class="form-check-label cont-ch-normal d-block" style="margin-top: 10px;" for="ch_alumno">
				      	   		<input type="checkbox" value="alumno" <?= in_array('alumno', $opts) ? 'checked' : ''; ?> 
				      	   			name="agente_eval[]" class="form-check-input ch-sel agentes" id="ch_alumno"> 
				      	   		<span class="checkmark"></span> Alumno
				      	   	</label>
				      	   	<label class="form-check-label cont-ch-normal d-block" style="margin-top: 10px;" for="ch_pares">
				      	   		<input type="checkbox" value="pares"  <?= in_array('pares', $opts) ? 'checked' : ''; ?> 
				      	   			name="agente_eval[]" class="form-check-input ch-sel agentes" id="ch_pares"> 
				      	   		<span class="checkmark"></span> Pares
				      	   	</label>
				      	</div>
				      </div>
				    </div>
				    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				        <div class="form-group">
				        	<p>Momento de evaluación <span class="text-danger">*</span></p>
				        	<div class="">
					      	   	<label class="form-check-label cont-rb-normal d-block" style="margin-top: 10px;" for="rb_diagnostico">
					      	   		<input type="radio" value="diagnostico" <?= $this->ada->momento_eval == 'diagnostico' ? 'checked' : '' ?> 
					      	   			name="momento_eval" class="form-check-input" id="rb_diagnostico">
					      	   		<span class="checkmark-rb"></span> Diagnóstico
					      	   	</label>
					      	   	<label class="form-check-label cont-rb-normal d-block" style="margin-top: 10px;" for="rb_formativa">
					      	   		<input type="radio" value="formativa"  <?= $this->ada->momento_eval == 'formativa' ? 'checked' : '' ?> 
					      	   			name="momento_eval" class="form-check-input" id="rb_formativa">
					      	   			<span class="checkmark-rb"></span> Formativa
					      	   	</label>
					      	   	<label class="form-check-label cont-rb-normal d-block" style="margin-top: 10px;" for="rb_sumativa">
					      	   		<input type="radio" value="sumativa"  <?= $this->ada->momento_eval == 'sumativa' ? 'checked' : '' ?> 
					      	   			name="momento_eval" class="form-check-input" id="rb_sumativa"> 
					      	   		<span class="checkmark-rb"></span> Sumativa
					      	   	</label>
				      	   </div>
				        </div>
				    </div>
				    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						
				    </div>
				</div>
				<br><br>
				<div class="row">
				    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				    	<div class="card">
				    		<div class="card-header">
				    			Productos a entregar
				    		</div>
				    		<div class="card-body">
				    			<div class="input-group mb-20">
				    				<select id="producto_sel" class="form-control custom-select">
				    					<option value="">Selecciona un producto</option>
				    					<?php foreach ($this->productos as $index => $item) {?>
				    					<option value="<?= $item->id_producto ?>"><?= $item->nombre ?></option>
				    					<?php } ?>
				    				</select>
				    				<div class="input-group-append">
				    					<button type="button" onclick="agregarItem('producto')" class="btn btn-block btn-primary">
				    						<i class="fa fa-check"></i> Agregar
				    					</button>
				    				</div>
				    			</div>
						    	<table class="table table-bordered">
						    		<colgroup><col width="80%"><col width="20%"></colgroup>
						    		<thead class="thead-dark"><tr><th colspan="2">Producto</th></tr></thead>
						    		<tbody id="tbody_productos"></tbody>
						    	</table>
						    	<!-- Otro producto -->
						    	<div class="form-group">
						    	    <label>Puede especificar otro producto</label>
						    	    <div class="input-group">
						    	    	<div class="input-group-prepend">
						    	    		<div class="input-group-text">Otro</div>
						    	    	</div>
						    	    	<input type="text" name="otro_producto" value="<?= $this->ada->otro_producto ?>" id="otro_producto" class="form-control" placeholder="Otro producto"/>
						    	    </div> 
						    	</div>
				    		</div>
				    	</div>
				    </div>

			        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			        	<div class="card">
			        		<div class="card-header">
			        			Recursos y materiales de apoyo
			        		</div>
			        		<div class="card-body">
		        	        	<div class="input-group mb-20">
		        	        		<select id="recurso_sel" class="form-control custom-select">
		        	        			<option value="">Selecciona un recurso</option>
		        	        			<?php foreach ($this->recursos as $index => $item) {?>
		        	        			<option value="<?= $item->id_recurso ?>"><?= $item->nombre ?></option>
		        	        			<?php } ?>
		        	        		</select>
		        	        		<div class="input-group-append">
		                				<button type="button" onclick="agregarItem('recurso')" class="btn btn-block btn-primary">
		                					<i class="fa fa-check"></i> Agregar
		                				</button>
		        	        		</div>
		        	        	</div>

			    		    	<table class="table table-bordered">
			    		    		<colgroup><col width="80%"><col width="20%"></colgroup>
			    		    		<thead class="thead-dark"><tr><th colspan="2">Recurso</th></tr></thead>
			    		    		<tbody id="tbody_recursos"></tbody>
			    		    	</table>

			    		    	<div class="form-group">
			    		    		<label>Puede especificar otro recurso o material</label>
			    		    	    <div class="input-group">
			    		    	    	<div class="input-group-prepend">
			    		    	    		<div class="input-group-text">Otro</div>
			    		    	    	</div>
			    		    	    	<input type="text" name="otro_recurso" value="<?= $this->ada->otro_recurso ?>" id="otro_recurso" class="form-control" placeholder="Otro recurso o material"/>
			    		    	    </div>
			    		    	</div>
			        		</div>
			        	</div>
			        </div>

					
			        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
			        	<div class="card">
			        		<div class="card-header">
			        			Estrategias EA
			        		</div>
			        		<div class="card-body">
		        	        	<div class="input-group mb-20">
		        	        		<select id="ea_sel" class="form-control custom-select">
		        	        			<option value="">Selecciona una estrategia</option>
		        	        			<?php foreach ($this->eas as $index => $item) {?>
		        	        			<option value="<?= $item->id_estrategia_ea ?>"><?= $item->descripcion_ea ?></option>
		        	        			<?php } ?>
		        	        		</select>
		        	        		<div class="input-group-append">
		                				<button type="button" onclick="agregarItem('ea')" class="btn btn-block btn-primary">
		                					<i class="fa fa-check"></i> Agregar
		                				</button>
		        	        		</div>
		        	        	</div>

			    		    	<table class="table table-bordered">
			    		    		<colgroup><col width="80%"><col width="20%"></colgroup>
			    		    		<thead class="thead-dark"><tr><th colspan="2">Estrategia EA</th></tr></thead>
			    		    		<tbody id="tbody_eas"></tbody>
			    		    	</table>

			    		    	<div class="form-group">
			    		    		<label>Puede especificar otra estrategia</label>
			    		    	    <div class="input-group">
			    		    	    	<div class="input-group-prepend">
			    		    	    		<div class="input-group-text">Otro</div>
			    		    	    	</div>
			    		    	    	<input type="text" name="otro_estrategia_ea" value="<?= $this->ada->otro_estrategia_ea ?>" id="otro_estrategia_ea" class="form-control" placeholder="Otra estrategia EA"/>
			    		    	    </div>
			    		    	</div>
			        		</div>
			        	</div>
			        </div>

				</div>
				<br>
				<label><strong>Verifique si en la ADA que acaba de crear ha incluido el desarrollo de las competencias genéricas indicadas previamente</strong></label>
				<div>
					<?php foreach ($this->cgs as $index => $cg): ?>
						<div class="" style="margin-top: 5px;">
							<label class="cont-ch-normal">
								<input <?= in_array($cg->id_cg, $this->cgs_ada_ids) ? 'checked' : '' ?> type="checkbox" name="cgs_evaluadas[]" class="cgs_evaluadas" id="cg_evaluada_<?=$index?>" value="<?= $cg->id_cg ?>">
								<span class="checkmark"></span> <?= $cg->descripcion_cg ?>
							</label>
						</div>
					<?php endforeach ?>
				</div>
				<br>
				<!-- referencias_ada -->
				<div class="form-group">
				    <label>Referencias </label> 
				    <textarea name="referencias_ada" id="referencias_ada" class="form-control" placeholder="Referencias"><?= $this->ada->referencias_ada ?></textarea>
				</div>

			</div>
		</div>
		<div class="card-footer text-right bg-transparent border-primary2">
			<button type="submit" <?= $this->ada->id_ada ? '' : 'disabled' ?> id="btn_guardar_ada" class="btn btn-success"><i class="fa fa-save"></i> Guardar ADA</button>
			<button type="button" onclick="confirmarFinalizarEdicion()" id="btn_cancelar_guardado_ada" class="btn btn-secondary"><i class="fa fa-times"></i> Cerrar formulario</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	var productos = <?= json_encode($this->productos); ?>;
	var productosSel = <?= json_encode($this->prods_sel); ?>;
	var tbodyProds = $('#tbody_productos');
	var instrumentosConPlantilla = <?= json_encode(PLANTILLAS); ?>;
	var primeraSugerencia = false;

	var recursos = <?= json_encode($this->recursos); ?>;
	var recursosSel = <?= json_encode($this->recs_sel); ?>;
	var tbodyRecs = $('#tbody_recursos');

	var tbodyEas = $('#tbody_eas');
	var eas = <?= json_encode($this->eas); ?>;
	var easSel = <?= json_encode($this->eas_sel); ?>;

	var ada = <?= json_encode($this->ada); ?>;
	var contenedor2 = $('#contenedor_f2');

	var herramientas = []; // solo se utiliza cuando se solicita explicitamente una herramienta

	function rubricaCreada(esNuevo, data) {
		$('#id_rubrica').append('<option value="'+data.id_rubrica+'">'+data.descripcion_rubrica+'</option>');
		$('#id_rubrica').val(data.id_rubrica);
		rubricaSeleccionada({});
	}

	function abrirFormRubrica() {
		utils.modal2.remote({
			url: '<?= ruta("rubrica.formulario_asistente") ?>',
			modal_options:{
				type: 'primary',
				size: 'lg',
				titulo: 'Nueva rúbrica',
			},
			error: function(){
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			},
			data: { desde_asistente: 1 },
		})
	}

	function plantillaRubricaSeleccionada() {
		var id = $('#plantilla_rubrica_id').val();
		if(id){
			verPlantillaRubrica(true);
		}
	}

	//-------------------- operaciones para manipular tablas ------------------
	// ---------------------------------------------------------------------------------
	var abrirCalendario = function(){
		$('#fecha_fin_ada').focus();
	};

	var debeMostrarOtraHerramienta = function(iniciando){
		var mostrar = $('#id_herramienta').val() ? false : true;
		$('#col_otra_herramienta').removeClass(mostrar ? 'd-none' : 'd-block');
		$('#col_otra_herramienta').addClass(mostrar ? 'd-show' : 'd-none');
		$('#otra_herramienta').prop('disabled', !mostrar);
		if(iniciando && mostrar){
			$('#id_herramienta').append('<option value="">Otra herramienta</option>');
			$('#id_herramienta').val('');

		}
	};
	
	var existeElemento = function(val, tipo){
		if(tipo == 'producto'){
			for (var i = 0; i < productosSel.length; i++) {
				if(productosSel[i].id_producto == val){
					return true;
				}
			}
		}else if(tipo == 'recurso'){
			for (var i = 0; i < recursosSel.length; i++) {
				if(recursosSel[i].id_recurso == val){
					return true;
				}
			}
		}else if(tipo =='ea'){
			for (var i = 0; i < easSel.length; i++) {
				if(easSel[i].id_estrategia_ea == val){
					return true;
				}
			}
		}
		return false;
	}

	var remover = function (index, tipo){
		if(tipo == 'producto'){
			productosSel.splice(index, 1);
		}else if(tipo =='recurso'){
			recursosSel.splice(index, 1);
		}else if(tipo == 'ea'){
			easSel.splice(index, 1);
		}
		actualizarView(tipo);
	}

	var generarHtmlItem = function (item, index, tipo) {
		var stipo = "\'"+tipo+"\'";
		return '<tr><td>'+(tipo == 'ea' ? item.descripcion_ea : item.nombre)+'</td><td><button class="btn btn-sm btn-danger" type="button" onclick="remover('+index+','+stipo+')"><i class="fa fa-trash-o"></i></button></td></tr>';
	}

	var actualizarView = function(tipo){
		if(tipo == 'producto'){
			if(productosSel.length > 0){
				tbodyProds.html('');
				productosSel.forEach(function (item, index){
					var htmlItem = generarHtmlItem(item, index, tipo) || '<tr></tr>';
					tbodyProds.append(htmlItem);
				});
			}else{
				tbodyProds.html('<tr><td colspan="2"><div class="alert alert-info text-center">Sin elementos seleccionados</div></td></tr>');
			}
		}else if(tipo == 'recurso'){
			if(recursosSel.length > 0){
				tbodyRecs.html('');
				recursosSel.forEach(function (item, index){
					var htmlItem = generarHtmlItem(item, index, tipo) || '<tr></tr>';
					tbodyRecs.append(htmlItem);
				});
			}else{
				tbodyRecs.html('<tr><td colspan="2"><div class="alert alert-info text-center">Sin elementos seleccionados</div></td></tr>');
			}
		}else{
			if(easSel.length > 0){
				tbodyEas.html('');
				easSel.forEach(function (item, index){
					var htmlItem = generarHtmlItem(item, index, tipo) || '<tr></tr>';
					tbodyEas.append(htmlItem);
				});
			}else{
				tbodyEas.html('<tr><td colspan="2"><div class="alert alert-info text-center">Sin elementos seleccionados</div></td></tr>');
			}
		}
	}

	function agregarItem(tipo, silencioso){
		var val = $('#'+tipo+'_sel').val();
		if(val){
			if(!existeElemento(val, tipo)){
				var ret = (tipo == 'producto' ? productos : ( tipo == 'ea' ? eas : recursos)).findOne(null, function (item){ return item['id_'+(tipo == 'ea' ? 'estrategia_ea' : tipo)] == val });
				if(ret){ (tipo == 'producto' ? productosSel : ( tipo == 'ea' ? easSel : recursosSel)).push(ret); }
				actualizarView(tipo);
				$('#'+tipo+'_sel').val('');
			}else{
				if(!silencioso)
					utils.alert.warning('Ya existe el '+tipo+' seleccionado');
			}
		}else{
			if(!silencioso)
				utils.alert.warning('Debe seleccionar un '+tipo);
		}
	}

	// ---------------------------------------------------------------------------------
	// ---------------------------------------------------------------------------------

	var abrirFormSugerenciaPalabras = function (){
		var instrucciones  = $('#instruccion_ada').val();
		var procedimientos = $('#procedimiento_ada').val();
		utils.modal.remote({
			url: '<?= ruta("asistente.fresultado_palabras_clave") ?>',
			data: { palabras: removerConectores([instrucciones,procedimientos])},
			modal_options: {
				titulo: 'Agregar elementos',
				size: 'md',
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al abrir el formulario');
			}
		});
	};

	var herramientaSeleccionada = function(e){
		var _this = $(this);
		var val = _this.val();
		if(val){
			$('#otra_herramienta').disabled();
			$('#otra_herramienta').val('');
		}else{
			$('#otra_herramienta').enabled();
		}
	};

	var instrumentoSeleccionado = function (e){
		var idCont = '';
		var idPlant = '';
		// aqui debemos poner todos los posibles elementos
		// que sean inputs para plantillas de los diferentes
		// instrumentos de evaluacion
		// $('#contenedor_rubricas').hide();
		// $('#contenedor_plantillas_rubrica').hide();
		
		var val = $(this).val();
		if(val){
			if(instrumentosConPlantilla[val]){
				if(instrumentosConPlantilla[val].nombre == 'rubrica'){
					idCont = '#contenedor_rubricas';
					idPlant = '#contenedor_plantillas_rubrica';
				}
			}
			$(idCont).show();
			$(idPlant).show();
		}
	}

	var rubricaSeleccionada = function (e){
		var val = $('#id_rubrica').val();
		var opts = [{id: '', label: 'Selecciona una plantilla'}];
		
		if(val){
			$('#plantilla_rubrica_id').enabled();
			$('#btn_recomendar_plantilla').enabled();
			
			api.get({
				url: '<?= ruta("plantillarubrica.listado_completo") ?>',
				data: { id_rubrica: val, desde_asistente: 1 },
				cb: function (response){
					if(response.estado){
						response.extra.forEach(function (item, index){
							opts.push({id: item.id_plantilla_rubrica, label: item.nombre});
						});
					}else{
						utils.alert.error('Ha ocurrido un error al intentar obtener las plantillas');
					}
					$('#plantilla_rubrica_id').fill(opts);
				},
				error: function (){
					$('#plantilla_rubrica_id').fill(opts);
				}
			});
		}else{
			$('#plantilla_rubrica_id').fill(opts);
			$('#plantilla_rubrica_id').disabled();
			$('#btn_recomendar_plantilla').disabled();
		}
	}

	var recomendarPlantillaRubrica = function (e){
		if($('#id_rubrica').val()){
			utils.alert.info('En proceso');
		}else{
			utils.alert.warning('Primero debe seleccionar una rúbrica');
		}
	}

	var herrSelTmp = function (idHerramienta){
		var options = [{id: '', label: 'Otra herramienta'}];
		herramientas.forEach(function (item, index){
			options.push({id: item.id_herramienta, label: item.descripcion_herramienta});
		});

		$('#id_herramienta').fill(options);
		$('#id_herramienta').val(idHerramienta || '');

		$('#modal_confirm').modal('hide');
		debeMostrarOtraHerramienta();
	}

	var seleccionarHerramienta = function (){
		var generarListado = function (list){
			herramientas = list;

			var html = '<div class="list-group" style="max-height: 400px; overflow-y: auto;">';
			for (var i = 0; i < list.length; i++) {
				html+='<button onclick="herrSelTmp('+list[i].id_herramienta+')" class="list-group-item list-group-item-action">'+list[i].descripcion_herramienta+'</button>'
			}
			html+='<button onclick="herrSelTmp()" class="list-group-item  list-group-item-dark list-group-item-action">Otra herramienta</button>';
			html+='</div>';	
			return html;
		};

		api.get({
			url: '<?= ruta("herramienta.json_listado") ?>',
			data: { page: 1, count: 100 },
			cb: function (response){
				if(response.estado){
					utils.modal.confirm({
						titulo: 'Seleccionar',
						contenido: generarListado(response.extra),
						size: 'md',
						type: 'primary',
					});
				}else{
					utils.alert.error(response.mensaje);
				}
			},
			error: function (){
				utils.alert.error('Ha ocurrido un error al intentar obtener el listado de herramientas');
			}
		});
	}

	var plantillaCreadaComoCopia = function (plantillaEditada){
		var encontrado = false;
		$('#plantilla_rubrica_id option').each(function (index){
			if($(this).val() == plantillaEditada.id_plantilla_rubrica){
				$(this).html(plantillaEditada.nombre);
				encontrado = true;
			}
		});

		if(!encontrado){
			$('#plantilla_rubrica_id').appendN([{
				id: plantillaEditada.id_plantilla_rubrica, 
				label: plantillaEditada.nombre
			}]);
		}

		$('#plantilla_rubrica_id').val(plantillaEditada.id_plantilla_rubrica);
	}

	var verPlantillaRubrica = function (automatico){
		var idPlantillaRubrica = $('#plantilla_rubrica_id').val();
		
		$('#edicion_plantilla').hide();
		if(idPlantillaRubrica){
			utils.modal.remote({
				url: '<?= ruta("plantillarubrica.formulario") ?>',
				data: { id_plantilla_rubrica: idPlantillaRubrica, es_copia: 1, automatico: automatico, id_competencia: idCompetencia },
				modal_options: {
					titulo: 'Editar plantilla',
					backdrop: 'static',
				},
				error: function (){
					utils.alert.error('Ha ocurrido un error al intentar abrir el formulario de la plantilla');
				}
			});
		}else{
			if($('#id_rubrica').val()){
				utils.alert.warning('Primero debe seleccionar una plantilla');
				$(this).focus();
			}else{
				utils.alert.warning('Selecciona un tipo de rúbrica, para obtener las plantillas disponibles');
				$('#id_rubrica').focus();
			}
		}
	}

	function obtenerRecomendacion() {
		var nombreAda = $('#nombre_ada').val();
		var instruccion = $('#instruccion_ada').val();
		var procedimiento = $('#procedimiento_ada').val();
		var modalidad = $('#modalidad_ada').val();

		if(!modalidad || !instruccion || !procedimiento){
			utils.alert.warning('Debe seleccionar la modalidad, introducir las instrucciones y los procedimientos de la actividad');
			if(!modalidad){
				$('#modalidad_ada').focus();
			}else if(!instruccion){
				$('#instruccion_ada').focus();
			}else if(!procedimiento){
				$('#procedimiento_ada').focus();
			}

			return;
		}

		api.get({
			url: '<?= ruta("asistente.fresultado_recomendacion") ?>',
			data: {
				instruccion: instruccion, 
				procedimiento: procedimiento, 
				competencia_id: idCompetencia,
				modalidad: modalidad,
			},
			cb: function(response){
				if(response.estado){
					var opciones = [{id: '',label:'Otra'}];
					var listado = response.extra.listado;
					if(response.extra.matching_encontrado){
						$('#btn_sugerir_palabras_clave').hide();
					}else{
						$('#btn_sugerir_palabras_clave').show();
					}

					listado.forEach(function (item, index){
						opciones.push({id: item.id_herramienta, label: item.descripcion_herramienta, tooltip: item.explicacion_herramienta});
					});

					contenedor2.show();
					$('#id_herramienta').fill(opciones);
					if(listado.length == 1){
						$('#id_herramienta').val(listado[0].id_herramienta);
						$('#otra_herramienta').val('');
						$('#otra_herramienta').disabled();
					}else{
						var itemTarea = opciones.find(function(item){
							return item.label.toLowerCase().indexOf('tarea') != -1;
						});
						if(itemTarea){
							console.log("=======> encontrada opcion tarea");
							$('#id_herramienta').val(itemTarea.id);
							$('#otra_herramienta').val('');
							$('#otra_herramienta').disabled();
						}else{
							console.log("=======> no encontrada opcion tarea");
						}
					}
					$('#btn_guardar_ada').prop('disabled', false);
					debeMostrarOtraHerramienta();
				}else{
					$('#btn_sugerir_palabras_clave').hide();
					utils.alert.error(response.mensaje);
				}
			},
			error: function(){
				$('#btn_sugerir_palabras_clave').hide();
				utils.alert.error('Ha ocurrido un error al intentar obtener las recomendaciones de herramientas para la actividad');
			}
		});

	}

	var guardarADA = function (datos){
		utils.busy.show('Guardando la ada')
		$('#btn_guardar_ada').disabled();
		$('#btn_cancelar_guardado_ada').disabled();
		// dialog.body.html('<strong>Guardando la ADA, espera por favor...</strong>')
		api.post({
			url: '<?= ruta("asistente.fresultado_guardar_ada") ?>',
			data: datos,
			cb: function (response){
				if(response.estado){
					utils.alert.success(response.mensaje);
					// dialog.body.html(successAlert(response.mensaje));
					recargarListadoAda(1);
					esconderFormularioAda();
				}else{
					// dialog.modal.modal('hide');
					utils.alert.error(response.mensaje);
				}
				utils.busy.hide();
				$('#btn_guardar_ada').enabled();
				$('#btn_cancelar_guardado_ada').enabled();
			},
			error: function (){
				$('#btn_guardar_ada').enabled();
				$('#btn_cancelar_guardado_ada').enabled();
				utils.busy.hide();
				// dialog.modal.modal('hide');
				utils.alert.error('Ha ocurrido un error al intentar guardar');
			}
		});
	}

	function validarSiMostrarBtnSugerencia() {
		var modalidad = $('#modalidad_ada').val();
		var nombre = $('#nombre_ada').val();
		var instruccion = $('#instruccion_ada').val();
		var procedimiento = $('#procedimiento_ada').val();
		if(modalidad.trim() != '' && nombre.trim() != '' && instruccion.trim() != ''  && procedimiento.trim() != ''){
			// $('#btn_obtener_recomendaciones').prop('disabled', false);
			primeraSugerencia = true;
		}else{
			// $('#btn_obtener_recomendaciones').prop('disabled', primeraSugerencia ? false : true);
		}

		var item = '<div class="alert alert-'+(primeraSugerencia ? 'success' : 'info')+'"><strong>Ingresa la modalidad, el nombre, las instrucciones y procedimientos. Seguidamente oprime el botón para obtener las sugerencias automáticas de las herramientas para esta ADA</strong></div>';
		$('#alerta_info_completar_datos_previos').html(item);
	}

    $(document).ready(function(){
    	$('#edicion_plantilla').hide();
    	if(ada.id_ada){
    		contenedor2.show();

    		// $('#contenedor_rubricas').hide();
    		// $('#contenedor_plantillas_rubrica').hide();

    		if(instrumentosConPlantilla[ada.id_instrumento_eval]){
    			$('#contenedor_rubricas').show();
    			if(instrumentosConPlantilla[ada.id_instrumento_eval].nombre == 'rubrica'){
    				$('#contenedor_plantillas_rubrica').show();
    			}
    		}

    		actualizarView('producto');
    		actualizarView('recurso');
    		actualizarView('ea');
    	}else{
    		$('#btn_sugerir_palabras_clave').hide();
    		contenedor2.hide();
    		// $('#contenedor_rubricas').hide();
    		// $('#contenedor_plantillas_rubrica').hide();
    	}

		var formAda = $('#frm_ada');
		formAda.validate({
			messages: {
				nombre_ada: 'El nombre es requerido',
				instruccion_ada: 'La descripción de las instrucciones es necesaria',
				procedimiento_actividad: 'La descripción del procedimiento es necesaria',
			}
		});

		formAda.on('submit', function (e){
			e.preventDefault();
			if(formAda.valid()){

				// llamamos a los metodos de agregar producto y recurso
				// para el caso donde solo haya seleccionado un elemento
				// pero no le hayan dado agregar
				agregarItem('producto', true);
				agregarItem('recurso', true);

				var id_herramienta = $('#id_herramienta').val() || null;
				var datos = {
					id_ada                    : $('#id_ada').val() || 0,
					id_asignatura		      : ada.id_asignatura,
					id_unidad 			      : ada.id_unidad,
					num_unidad 			      : ada.num_unidad,
					resultado_competencia_ada : ada.resultado_competencia_ada,
					id_herramienta            : id_herramienta,
					nombre_ada                : $('#nombre_ada').val(),
					modalidad_ada             : $('#modalidad_ada').val(),
					instruccion_ada           : $('#instruccion_ada').val(),
					max_integrantes_equipo    : 1,
					procedimiento_ada         : $('#procedimiento_ada').val(),
					duracion_horas            : $('#duracion_horas').val(), // tiempo estimad,
					referencias_ada           : $('#referencias_ada').val(),
					fecha_fin_ada             : $('#fecha_fin_ada').val(),
					fecha_ini_ada             : null,
					id_instrumento_eval       : $('#id_instrumento_eval').val(),
					otra_herramienta          : id_herramienta ? null : $('#otra_herramienta').val(),
					// id_estrategia_ea          : $('#id_estrategia_ea').val(),
					ponderacion               : $('#ponderacion').val(),
					id_rubrica                : $('#id_rubrica').val(),
					id_plantilla_rubrica      : $('#plantilla_rubrica_id').val(),
				};
				
				// if(instrumentosConPlantilla[datos.id_instrumento_eval]){
				// 	if(instrumentosConPlantilla[datos.id_instrumento_eval].nombre == 'rubrica'){
				// 		datos.id_rubrica = $('#id_rubrica').val();
				// 		datos.id_plantilla_rubrica = $('#plantilla_rubrica_id').val();
				// 	}
				// }

				datos.agentes_eval = '';
				var tmp1 = [];
				$('.agentes:checked').each(function(i){
					tmp1.push($(this).val());
				});
				datos.agentes_eval = tmp1.join(',');

				datos.momento_eval = null;
				if($('#rb_diagnostico').is(':checked')){
					datos.momento_eval = $('#rb_diagnostico').val();
				}else if($('#rb_formativa').is(':checked')){
					datos.momento_eval = $('#rb_formativa').val();
				}else if($('#rb_sumativa').is(':checked')){
					datos.momento_eval = $('#rb_sumativa').val();
				}

				datos.productos_entregar = [];
				productosSel.forEach(function (item, index){
					datos.productos_entregar.push(item.id_producto);
				});

				datos.recursos_entregar = [];
				recursosSel.forEach(function (item, index){
					datos.recursos_entregar.push(item.id_recurso);
				});

				datos.estrategias_ea_entregar = [];
				easSel.forEach(function (item, index){
					datos.estrategias_ea_entregar.push(item.id_estrategia_ea);
				});

				datos.cgs = [];
				$('.cgs_evaluadas:checked').each(function(i){
					var cg_id = $(this).val();
					datos.cgs.push(cg_id);
				})

				datos.otro_producto = $('#otro_producto').val();
				datos.otro_recurso  = $('#otro_recurso').val();
				datos.otro_estrategia_ea  = $('#otro_estrategia_ea').val();

				// validamos algunas cosas que queda fuera del jquery validator
				if(!datos.id_herramienta && !datos.otra_herramienta){
					utils.alert.warning('Debe seleccionar una herramienta o ingresar una opcional');
					$('#id_herramienta').focus();
					return;
				}

				if(datos.agentes_eval == ""){
					utils.alert.warning('Debe seleccionar al menos un agente que evalua');
					return;
				}

				if(!datos.momento_eval){
					utils.alert.warning('Debe seleccionar un momento de evaluación');
					return;
				}

				if(datos.productos_entregar.length == 0 && !datos.otro_producto){
					utils.alert.warning('Debe agregar un producto para entregar, o ingresar en el campo otro el nombre de un producto diferente', 5000);
					$('#producto_sel').focus();
					return;
				}

				if(datos.recursos_entregar.length == 0 && !datos.otro_recurso){
					utils.alert.warning('Debe agregar un recurso/material para entregar, o ingresar en el campo otro el nombre de un recurso/material diferente', 5000);
					$('#recurso_sel').focus();
					return;
				}

				// validamos que tenga seleccionado al menos una cg
				if(datos.cgs.length == 0){
					utils.alert.warning('Debe seleccionar al menos una competencia genérica');
					return;
				}

				// if(datos.estrategias_ea_entregar.length == 0){

				// }

				guardarADA(datos);
				// utils.modal.confirm({
				// 	titulo: 'Confirmación guardado',
				// 	contenido: 'Oprime aceptar para guardar',
				// 	size: 'sm',
				// 	type: 'primary',
				// 	backdrop: 'static',
				// 	success: function (dialog){
				// 		dialog.modal.modal('hide');
				// 		guardarADA(datos, dialog);
				// 	}
				// });

			}
		});

		// eventos
		$('#fecha_fin_ada').datetimepicker({
			format:'d/m/Y H:i',
		});
		$('#id_herramienta').change(herramientaSeleccionada);
		$('#id_instrumento_eval').change(instrumentoSeleccionado)
		$('#id_rubrica').change(rubricaSeleccionada);
		$('#btn_recomendar_plantilla').on('click').off('click', recomendarPlantillaRubrica);
		
		// actualizamos vistas
		actualizarView('producto');
		actualizarView('recurso');
		actualizarView('ea');
	
		if(ada && ada.id_ada){
			debeMostrarOtraHerramienta(true);
			// if(ada.id_herramienta){
			// 	$('#otra_herramienta').disabled();
			// 	$('#otra_herramienta').val('');
			// }else{
			// 	$('#otra_herramienta').enabled();
			// }
		}

    });

</script>