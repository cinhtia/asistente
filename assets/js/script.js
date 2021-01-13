var conectores = ['y', 'e', 'ni', 'ademas', 'también', 'del mismo modo', 'incluso', 'tampoco','o','u','pero', 'en cambio', 'sino', 'a pesar de', 'por el contrario', 'mas', 'sino', 'sin embargo', 'no obstante','por lo tanto', 'así que, en consecuencia', 'por consiguiente', 'por ende', 'de manera','de manera que', 'de modo que', 'mientras', 'en efecto', 'pues', 'luego','porque', 'pues', 'ya que', 'puesto que', 'dado que', 'debido a que', 'a causa de', 'por eso', 'como','antes de','previamente','al principio','mucho antes','cuando','mientras','mientras tanto', 'en cuanto','donde','a','de','que','el','la','los','las','le','en','sus','su','ellos'];

// configuracion para adaptar el jquery-validator
$.validator.setDefaults({
    errorClass: 'is-invalid',
    validClass: 'is-valid',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

jQuery.extend(jQuery.validator.messages, {
    required    : "Este campo es requerido.",
    remote      : "Por favor corrige este campo.",
    email       : "Por favor ingresa un correo válido.",
    url         : "Por favor ingresa una url válida.",
    date        : "Por favor ingresa una fecha válida.",
    dateISO     : "Por favor ingresa una fecha válida (ISO).",
    number      : "Por favor ingresa un número válido.",
    digits      : "Por favor ingresa sólo dígitos.",
    creditcard  : "Por favor ingresa un número de tarjeta válido.",
    equalTo     : "Por favor ingresa el mismo valor nuevamente.",
    accept      : "Por favor ingresa un valor con extensión válida.",
    maxlength   : jQuery.validator.format("Por favor ingresa no mas de {0} caracteres."),
    minlength   : jQuery.validator.format("Por favor ingresa al menos {0} caracteres."),
    rangelength : jQuery.validator.format("Por favor ingresa un valor entre {0} y {1} caracteres de longitud."),
    range       : jQuery.validator.format("Por favor ingresa un valor entre {0} y {1}."),
    max         : jQuery.validator.format("Por favor ingresa un valor menor o igual que {0}."),
    min         : jQuery.validator.format("Por favor ingresa un valor mayor o igual que {0}.")
});



function removerConectores(array) {
	var lista_final = [];
	array.forEach(function (item, index){
		var str = item.replace(new RegExp(/,|\.|;|-|_|#|\{|\}|\[|\]/, 'g'), "");
		str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
		var tokens = str.split(" ");
		for (var i = 0; i < tokens.length; i++) {
			var token = tokens[i].toLowerCase();
			if(conectores.indexOf(token) === -1){
				if(lista_final.indexOf(token) == -1){
					lista_final.push(tokens[i]);
				}
			}
		}
	});
	return lista_final;
}

function loading(obj, mensaje){
	obj.html('<span class="fa fa-spinner fa-pulse"></span> <span>'+mensaje+'</span>');
	obj.prop('disabled',true);
}

function notLoading(obj, html){
	obj.prop('disabled' , false);
	obj.html(html);
}

function GET(objetoHtml, url, parametros, callBack, errorCallBack){
	// loading(objetoHtml, 'Cargando, espera por favor');
	objetoHtml.prepend('<span class="fa fa-spinner fa-pulse"></span> <span>Cargando, espera por favor</span>');

	$.ajax({
		url: url+parametros,
		method: 'get',
		success: function (response){
			callBack(response);
		},
		error: function (xhr){
			errorCallBack(xhr.responseText);
		}
	});
}

function POST(objetoHtml, url, data, callBack, errorCallBack){
	loading(objetoHtml, 'Cargando, espera por favor');

	$.ajax({
		url: url,
		method: 'post',
		data: data,
		success: function (response){
			callBack(response);
		},
		error: function (){
			errorCallBack();
		}
	});
}

function multiPartPOST(objetoHtml, url, data, callBack, errorCallBack){
	loading(objetoHtml, 'Cargando, espera por favor');

	$.ajax({
		url: url,
		method: 'post',
		data: data,
		success: function (response){
			callBack(response);
		},
		error: function (){
			errorCallBack();
		}
	});
}

function errorAlert(msj){
	return customAlert("danger",msj);
}

function successAlert(msj){
	return customAlert("success",msj);
}

function warningAlert(msj){
	return customAlert('warning', msj);
}

function customAlert(tipo, msj){
	return "<br><div class='alert alert-"+tipo+"'>"+msj+"</div><br>";
}

function mostrarMensajeModal(titulo, msj){
	$('#modal_modal_body').html(msj);
	$('#modal_modal_titulo').html(titulo);
	$('#modal_btn_primary').hide();
	$('#modal').modal('show');
}

function isFunction(functionToCheck) {
 return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
}

Array.prototype.findOne = function(key,whereCb) {
	var ret = null;
	for (var i = 0; i < this.length; i++) {
		if(whereCb(this[i])){ret = key ? this[i][key] : this[i]; break;}
	}
	return ret;
};

Array.prototype.mapFor = function(key, whereCb) {
	let isF = isFunction(whereCb);
	return this.map(function (item){ 
		if(isF){ 
			if(whereCb(item)){
				return item[key]
			} 
		}else{
			return item[key]; 
		}
	});
};

Array.prototype.mapMany = function(arrayKey, whereCb) {

	var getFor = function(item){
		var tmpObj = {};
		arrayKey.forEach(function (key, indexKey){
			tmpObj[key] = item[key];
		});
		console.log("obtenido ");
		return tmpObj;
	};

	var out = [];
	let isF = isFunction(whereCb);
	this.forEach(function (item, indexItem){
		if(isF){
			if(whereCb(item))out.push(getFor(item));
		}else{
			out.push(getFor(item));
		}		
	});
	return out;
};

