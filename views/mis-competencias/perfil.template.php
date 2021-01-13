<style>
	.cunidad .cresultado{ padding-left: 30px; }
	.cunidad {padding-left: 20px;}
	h5{
		margin-bottom: 0px;
		padding-bottom: 0px;
	}
</style>
<div class="container">
	<div>
		<h4 style="margin-bottom:0px; padding-bottom:0px;"><strong>Competencia de asignatura <small id="contenedor_asignatura"></small> </strong> <span id="contenedor_preview_competencia"></span>
		<a href="#" id="btn_editar_comp_asign" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placemente="top" title="Editar esta competencia de asignatura">
			<i class="fa fa-edit"></i>
		</a>
		</h4>
		<div class="cunidad" id="contenedor_competencias_unidades">
		</div>
	</div>
</div>

<script type="text/javascript">
	var idCompetencia = 0;
	var dataCompetencia = {};//< ?= $this->competencia ? json_encode($this->competencia) : '{}' ? >; 

	var generarItemsCResultado = function (numUnidad, cResultados,idCompetenciaUnidad){
		if(cResultados.length > 0){
			var html = '';
			cResultados.forEach(function (item, index){
				var str = numUnidad+"."+(index+1);
				html += '<div class=""><strong>'+str+' - Competencia de resultado '+numUnidad+'.'+(index+1)+'</strong>: '+(item.competencia_editable || item.descripcion || '-').substring(0,30)+'... <a href="<?= ruta('asistente.fresultado') ?>?id_competencia_unidad='+item.id_competencia_padre+'&id_competencia_resultado='+item.id_competencia+'" ><i class="fa fa-arrow-right"></i> Editar</a></div>';
			});
			return html;
		}else{
			return '<div class="text-danger"><strong>- Sin competencias de resultado</strong> <a href="<?= ruta('asistente.fresultado')?>?id_competencia_unidad='+idCompetenciaUnidad+'" ><i class="fa fa-arrow-right"></i> Crear</a></div>';
		}
	};

	var generarItemCUnidad = function (item, numUnidades){
		var html = '<h5 class="text-info"><strong>'+item.num_unidad+' - Unidad <span>'+item.num_unidad+'</span></strong> (Fase '+(parseInt(item.etapa_actual)+1)+') <a class="btn btn-sm btn-secondary" title="Editar" href="<?= ruta('asistente.funidad') ?>?id_competencia='+idCompetencia+'&unidad='+item.num_unidad+'"><i class="fa fa-edit"></i></a></h5>'+
			'<div class="cresultado" style="margin-bottom:20px;">'+
				generarItemsCResultado(item.num_unidad,item.CompetenciasResultado, item.id_competencia)+
			'</div>';

		return html;
	};

	var llenarDatos = function (){
		$('#contenedor_preview_competencia').html((dataCompetencia.competencia_editable || '').substring(0,30)+'...');
		$('#contenedor_asignatura').html("("+dataCompetencia.Asignatura.nombre_asignatura+")");
		var totalUnidades = dataCompetencia.Asignatura.num_unidades;
		
		var i = 1;
		var htmls = [];
		for (; i <= totalUnidades; i++) {
			var htmlItem = '<h5 class="text-danger" style="margin-bottom:20px;"><strong>'+i+' - Unidad <span>'+i+'</span></strong> No creado <a href="<?= ruta('asistente.funidad') ?>?id_competencia='+idCompetencia+'&unidad='+i+'">Crear <i class="fa fa-arrow-right"></i></a></h5>';
			htmls.push(htmlItem);
		};

		var cUnidades = dataCompetencia.CompetenciasUnidad;
		for (i=0; i < cUnidades.length; i++) {
			var htmlTmp = generarItemCUnidad(cUnidades[i], dataCompetencia.Asignatura.num_unidades);
			if(htmlTmp){
				htmls[cUnidades[i].num_unidad-1] = htmlTmp;
			}
		};

		htmls.forEach(function (item, index){
			$('#contenedor_competencias_unidades').append(item);
		});
	};

	var obtenerDatos = function(){
		$.ajax({
		    url: '<?= ruta("competencia.arbol") ?>',
		    method: 'get',
		    data: { id: <?= $this->idCompetencia ?> },
		    success: function(response){
				if(response.estado){
					idCompetencia = response.extra.id_competencia;
					dataCompetencia = response.extra;
					$('#contenedor_competencias_unidades').html('');
					llenarDatos();
				}else{
					utils.alert.error(response.mensaje);
				}
		    },
		    error: function(xhr){
		        utils.alert.error('Ha ocurrido un error al obtener los datos solicitados');
		    }
		});
	};

    $(document).ready(function(){
    	obtenerDatos();

    	<?php if($this->frm ){ ?>
    		utils.alert.success('La competencia ha sido guardada correctamente. ');
    	<?php } ?>

		$('#btn_mostrar_competencias_unidades').off('click').on('click', function (e){
		    e.preventDefault();
		});

		$('#btn_editar_comp_asign').off('click').on('click', function (e){
		    e.preventDefault();
		    window.location.replace("<?= ruta('asistente').'?id_competencia='.$this->idCompetencia ?>");
		});
    });
</script>