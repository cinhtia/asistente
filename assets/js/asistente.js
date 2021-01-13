
function cargarFase1(idCompetencia, callBack){

	var parametros = {};

	if(idCompetencia != null){
		parametros.id_competencia = idCompetencia;
	}

	$.ajax({
		url: 'asistente/fase1-datos-basicos',
		method: 'get',
		data: parametros,
		success: function(response){
			callBack(null,response);
		},
		error: function(xhr){
			console.log(xhr);
			callBack(xhr, null);
		}
	});
}

function cargarFase2(idCompetencia, callBack){

	var parametros = {};

	if(idCompetencia != null){
		parametros.id_competencia = idCompetencia;
	}


	$.ajax({
		url: 'asistente/fase2-edicion-competencia',
		method: 'get',
		data: parametros,
		success: function(response){
			callBack(null,response);
		},
		error: function(xhr){
			console.log(xhr);
			callBack(xhr, null);
		}
	});
}

function cargarFase3(idCompetencia, callBack){

	var parametros = {};
	if(idCompetencia){
		parametros.id_competencia = idCompetencia;
	}

	$.ajax({
		url: 'asistente/fase3-desglosar-componentes',
		method: 'get',
		data: parametros,
		success: function(response){
			callBack(null, response);
		},
		error: function(xhr){
			console.log(xhr);
			callBack(xhr, null);
		}
	});
}

function cargarFase4(idCompetencia, callBack){

	var parametros = {};
	if(idCompetencia){
		parametros.id_competencia = idCompetencia;
	}

	$.ajax({
		url: 'asistente/fase4-seleccionar-comp-genericas',
		method: 'get',
		data: parametros,
		success: function(response){
			callBack(null, response);
		},
		error: function(xhr){
			console.log(xhr);
			callBack(xhr, null);
		}
	});
}

$(document).ready(function(){

});