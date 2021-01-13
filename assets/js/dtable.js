(function ($){
	/*
	id_val => Key del campo en el array
	btn => Btn seleccionar elemento
	listado_sel => Seleccion
	listado => Listado disponible
	*/
	$.fn.dtable: function (config){

		var _this_tbody = this;

		var existeElemento = function(val){
			for (var i = 0; i < config.listado_sel.length; i++) {
				if(config.listado_sel[i][config.id_val] == val){
					return true;
				}
			}
			return false;
		}

		var actualizarView = function(){
			if(config.listado_sel.length > 0){
				_this_tbody.html('');
				config.listado_sel.forEach(function (item, index){
					var htmlItem = config.generarHtmlItem(item, index) || '<tr></tr>';
					_this_tbody.append(htmlItem);
				});
			}else{
				_this_tbody.html('<div class="alert alert-info">'+(config.msj_sin_resultados || 'Sin elementos seleccionados')+'</div>')
			}
		}

		var agregarElemento = function(val){
			var item = utils.findOne(null, function (item){ return item[config.id_val] == val });
			if(item){ config.listado_sel.push(item); }
			actualizarView();
		}

		config.btn.on('click').off('click',function (e){
			if(e){e.preventDefault();}
			var val = config.select.val();
			console.log("Ejecutnaod "+val);
			if(val){
				if(!existeElemento()){
					agregarElemento(val);
				}else{
					utils.alert.warning('Ya existe el elemento seleccionado');
				}
			}else{
				utils.alert.warning('Debes seleccionar un elemento');
			}
		});
	}
});