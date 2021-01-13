// configuraciones para toast

jQuery.fn.extend({
	clear: function(){
		 this.html(' ');
	},
	appendN: function (items){
		var _this = this;
		items.forEach(function (item, index){
			var option = $('<option>');
			option.text(item.label).attr('value', item.id);
			if(item.tooltip){
				// option.attr('data-toggle', 'tooltip');
				// option.attr('data-container', item.tooltip_container || '#container_tooltip');
				// option.attr('data-placement', item.tooltip_placement || 'right');
				option.attr('title', item.tooltip);
			}
			_this.append(option);
		});
	},
	fill: function(items){
		this.clear();
		this.appendN(items);
	},
	loading: function (message, centered){
		var html = '<span class="fa fa-spinner fa-pulse"></span> <span>'+message+'</span>';
		if(centered){
			html = "<div class='text-center'>"+html+"</div>";
		}
		this.html(html);
	},
	notLoading: function(){
		this.html('');
	},
	errorAlert: function(msj){
		this.html(errorAlert(msj || '-'));
	},
	successAlert: function(msj){
		this.html(successAlert(msj || '-'));
	},
	warningAlert: function(msj){
		this.html(warningAlert(msj || '-'));
	},
	infoAlert: function(msj){
		this.html(infoAlert(msj || '-'));
	},
	disabled: function (b){
		if(b){
			this.prop('disabled', b ? true : false);
		}else{
			this.prop('disabled', true);
		}
	},
	enabled: function(b){
		if(b){
			this.prop('disabled', b ? false : true);
		}else{
			this.prop('disabled', false);
		}
	},
	isChecked: function(){
		return this.is(':checked') ? true : false;
	},
});


var utils = {
	alert: {
		success: function (msj, title){
			toastr.options = {
			  "closeButton": true,
			  // "debug": false,
			  // "newestOnTop": false,
			  // "progressBar": false,
			  "positionClass": "toast-top-right",
			  // "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "500",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};
			toastr.success(msj, title || 'Exitoso');
		},
		warning: function (msj, title){
			toastr.options = {
			  "closeButton": true,
			  // "debug": false,
			  // "newestOnTop": false,
			  // "progressBar": false,
			  "positionClass": "toast-top-right",
			  // "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "500",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};
			toastr.warning(msj, title || 'Alerta');
		},
		error: function (msj, title){
			toastr.options = {
			  "closeButton": true,
			  // "debug": false,
			  // "newestOnTop": false,
			  // "progressBar": false,
			  "positionClass": "toast-top-right",
			  // "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "500",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};
			toastr.error(msj, title || 'Error');
		},
		danger: function(msj, title){
			utils.alert.error(msj, title);
		},
		info: function (msj, title){
			toastr.options = {
			  "closeButton": true,
			  // "debug": false,
			  // "newestOnTop": false,
			  // "progressBar": false,
			  "positionClass": "toast-top-right",
			  // "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "500",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};
			// toastr.options.closeButton = true;
			toastr.info(msj, title || 'Información');
		}
	},
	forzarTooltip: function(){
		$('[data-toggle="tooltip"]').tooltip();
	},
	modal : {
		remote: function(opciones){

			$.ajax({
				url: opciones.url,
				method: opciones.method ? opciones.method : 'get',
				data: opciones.data ? opciones.data : {},
				success: function(response){
					opciones.modal_options.contenido = response;
					utils.modal.show(opciones.modal_options);
					if(opciones.success != null){
						opciones.success();
					}
				},
				error: function (){
					if(opciones.error != null){
						opciones.error();
					}

					if(opciones.errorMessage){
						utils.alert.error(opciones.errorMessage);
					}
				}
			});

		},
		confirm: function(opciones){
			var contenido = "";
			var titulo = "";
			var btnPrimary = "Aceptar";
			var opcionesShow = "show";
			if(opciones){
				if(opciones.contenido){
					contenido = opciones.contenido;
				}

				if(opciones.titulo){
					titulo = opciones.titulo;
				}

				if(opciones.btn_primary){
					btnPrimary = opciones.btn_primary;
				}

				if(opciones.backdrop){
					opcionesShow = {
						backdrop: opciones.backdrop
					}
				}

				if(opciones.vertical_center){
					$('#modal_confirm_size').addClass('modal-dialog-centered');
				}else{
					$('#modal_confirm_size').removeClass('modal-dialog-centered');
				}

				if(opciones.type){
					var classHeader = 'modal-header-'+opciones.type;
					$('#modal_confirm_header').addClass(classHeader);
				}

				$('#modal_confirm_size').removeClass('modal-lg');
				$('#modal_confirm_size').addClass('modal-'+(opciones.size || 'lg'));

			};

			$('#modal_modal_confirm_body').html(contenido);
			$('#modal_modal_confirm_title').html(titulo);
			$('#modal_confirm_btn_primary').html(btnPrimary);
			$('#modal_confirm_btn_primary').show();

			$('#modal_confirm_btn_primary').prop('disabled', false);
			$('#modal_confirm').modal(opcionesShow);
			$('#modal_confirm_btn_primary').off('click').on('click', function(e){
				if(opciones.success != null){
					if(opciones.type == 'danger'){ // de eliminar
						$('#modal_confirm_btn_primary').hide();
					}

					opciones.success({
						modal: $('#modal_confirm'),
						body: $('#modal_modal_confirm_body'), 
						btn: $('#modal_confirm_btn_primary')
					});
				}
				if(opciones.auto_dismiss){
					$('#modal_confirm').modal('hide');
				}
			});
		},
		confirmDelete(opciones){
			utils.modal.confirm({
				titulo: opciones.titulo,
				contenido: opciones.contenido,
				type: 'danger',
				size: 'md',
				success: function(dialog){
					dialog.btn.hide();
					dialog.btn.disabled();
					opciones.confirm(dialog);
				}
			});
		},
		contenido : function(content){
			return $('#modal_modal_body').html(content);
		},

		beginLoading: function(msj){
			loading($('#modal_btn_primary'), msj);
		},
		endLoading: function(msj){
			if(!msj){
				msj = "Guardar";
			}
			notLoading($('#modal_btn_primary'), msj);
		},
		error: function(message, delay){
			$('#modal_alert').html(errorAlert(message));
			$('#modal_alert').fadeIn().delay(delay ? delay : 4000).fadeOut();
		},
		success: function(message, delay){
			$('#modal_alert').html(successAlert(message));
			$('#modal_alert').fadeIn().delay(delay ? delay : 4000).fadeOut();
		},
		hide : function(){
			$('#modal').modal('hide');
		},
		show: function(opciones){
			var contenido = "";
			var titulo = "";
			var btnPrimary = "Guardar";
			var opcionesShow = "show";
			if(opciones){
				if(opciones.contenido){
					contenido = opciones.contenido;
				}

				if(opciones.titulo){
					titulo = opciones.titulo;
				}

				if(opciones.btn_primary){
					btnPrimary = opciones.btn_primary;
				}

				if(opciones.backdrop){
					opcionesShow = {
						backdrop: opciones.backdrop
					};
				}else{
					opcionesShow = {
						backdrop: 'static',
					};
				}

				if(opciones.type){
					var classHeader = 'modal-header-'+opciones.type;
					$('#modal_modal_header').addClass(classHeader);
				}

				$('#modal_modal_size').removeClass('modal-lg');
				$('#modal_modal_size').addClass('modal-'+(opciones.size || 'lg'));
			}

			$('#modal_modal_body').html(contenido);
			$('#modal_modal_title').html(titulo || '');
			$('#modal_btn_primary').html(btnPrimary);
			$('#modal').modal(opcionesShow);

		},
		btn: {
			onClick: function (cb){
				$('#modal_btn_primary').off('click').on('click', function (e){
				    e.preventDefault();
				    cb();
				});
			}
		},
		setTitle: function(title){
			$('#modal_modal_title').html(title || '');
		}
	},
	modal2 : {
		remote: function(opciones){

			$.ajax({
				url: opciones.url,
				method: opciones.method ? opciones.method : 'get',
				data: opciones.data ? opciones.data : {},
				success: function(response){
					opciones.modal_options.contenido = response;
					utils.modal2.show(opciones.modal_options);
					if(opciones.success != null){
						opciones.success();
					}
				},
				error: function (){
					if(opciones.error != null){
						opciones.error();
					}

					if(opciones.errorMessage){
						utils.alert.error(opciones.errorMessage);
					}
				}
			});

		},
		confirm: function(opciones){
			var contenido = "";
			var titulo = "";
			var btnPrimary = "Aceptar";
			var opcionesShow = "show";
			if(opciones){
				if(opciones.contenido){
					contenido = opciones.contenido;
				}

				if(opciones.titulo){
					titulo = opciones.titulo;
				}

				if(opciones.btn_primary){
					btnPrimary = opciones.btn_primary;
				}

				if(opciones.backdrop){
					opcionesShow = {
						backdrop: opciones.backdrop
					}
				}

				if(opciones.vertical_center){
					$('#modal_confirm_size2').addClass('modal-dialog-centered');
				}else{
					$('#modal_confirm_size2').removeClass('modal-dialog-centered');
				}

				if(opciones.type){
					var classHeader = 'modal-header-'+opciones.type;
					$('#modal_confirm_header2').addClass(classHeader);
				}

				$('#modal_confirm_size2').removeClass('modal-lg');
				$('#modal_confirm_size2').addClass('modal-'+(opciones.size || 'lg'));

			};

			$('#modal_modal_confirm_body2').html(contenido);
			$('#modal_modal_confirm_title2').html(titulo);
			$('#modal_confirm_btn_primary2').html(btnPrimary);

			$('#modal_confirm_btn_primary2').prop('disabled', false);
			$('#modal_confirm2').modal(opcionesShow);
			$('#modal_confirm_btn_primary2').off('click').on('click', function(e){
				if(opciones.success != null){
					opciones.success({
						modal: $('#modal_confirm2'),
						body: $('#modal_modal_confirm_body2'), 
						btn: $('#modal_confirm_btn_primary2')
					});
				}
				if(opciones.auto_dismiss){
					$('#modal_confirm2').modal('hide');
				}
			});
		},
		confirmDelete(opciones){
			utils.modal.confirm({
				titulo: opciones.titulo,
				contenido: opciones.contenido,
				type: 'danger',
				size: 'md',
				success: function(dialog){
					dialog.btn.disabled();
					opciones.confirm(dialog);
				}
			});
		},
		contenido : function(content){
			return $('#modal_modal_body2').html(content);
		},

		beginLoading: function(msj){
			loading($('#modal_btn_primary2'), msj);
		},
		endLoading: function(msj){
			if(!msj){
				msj = "Guardar";
			}
			notLoading($('#modal_btn_primary2'), msj);
		},
		error: function(message, delay){
			$('#modal_alert2').html(errorAlert(message));
			$('#modal_alert2').fadeIn().delay(delay ? delay : 4000).fadeOut();
		},
		success: function(message, delay){
			$('#modal_alert2').html(successAlert(message));
			$('#modal_alert2').fadeIn().delay(delay ? delay : 4000).fadeOut();
		},
		hide : function(){
			$('#modal2').modal('hide');
		},
		show: function(opciones){
			var contenido = "";
			var titulo = "";
			var btnPrimary = "Guardar";
			var opcionesShow = "show";
			if(opciones){
				if(opciones.contenido){
					contenido = opciones.contenido;
				}

				if(opciones.titulo){
					titulo = opciones.titulo;
				}

				if(opciones.btn_primary){
					btnPrimary = opciones.btn_primary;
				}

				if(opciones.backdrop){
					opcionesShow = {
						backdrop: opciones.backdrop
					}
				}

				if(opciones.type){
					var classHeader = 'modal-header-'+opciones.type;
					$('#modal_modal_header2').addClass(classHeader);
				}

				$('#modal_modal_size2').removeClass('modal-lg');
				$('#modal_modal_size2').addClass('modal-'+(opciones.size || 'lg'));
			}

			$('#modal_modal_body2').html(contenido);
			$('#modal_btn_primary2').prop('disabled', false);
			$('#modal_modal_title2').html(titulo);
			$('#modal_btn_primary2').html(btnPrimary);
			$('#modal2').modal(opcionesShow);

		},
		btn: {
			disabled: function(){
				$('#modal_btn_primary2').prop('disabled', true);
			},
			enabled: function(){
				$('#modal_btn_primary2').prop('disabled', false);
			},
			onClick: function (cb){
				$('#modal_btn_primary2').off('click').on('click', function (e){
				    e.preventDefault();
				    cb();
				});
			}
		}
	},
	camel2Kebab: function(camelString){
		return camelString.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
	},
	tag: function(name, content, props, childrens){

		if(!name && content){ // por motivos de recursividad
			return content;
		}
		

		var strTag = "<"+name+"";
		for(var key in props){
			strTag += " "+(utils.camel2Kebab(key))+"=\""+props[key]+"\"";
		}
		var isContainer = ['img', 'input', 'br', 'hr', 'meta', 'etc'].indexOf(name) == -1;
		strTag += isContainer ? ">" : "/>";

		if(isContainer){
			if(content){
				strTag += content;
			}
			if(childrens){
				childrens.forEach(function (item, index){
					strTag += utils.tag(item.name, item.content, item.props, item.childrens);
				});
			}
			strTag += "</"+name+">";;
		}
		return strTag;
	},
	busy: {
		show: function(message){
			var h = $('body').height();
			$('#main_loader_busy_message').html(message);
			$('#main_loader_busy').removeClass('no-display');
			$('#main_loader_busy').addClass('display-table');
			$('#main_loader_busy').css('height', h+'px');
		},
		hide: function(){
			$('#main_loader_busy_message').html('');
			$('#main_loader_busy').removeClass('display-table');
			$('#main_loader_busy').addClass('no-display');
		}
	},
	goToUrl: function(url){
		window.location.href = url;
	},
	randomString: function(length){
		var result           = '';
	    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	    var charactersLength = characters.length;
	    for ( var i = 0; i < length; i++ ) {
	       result += characters.charAt(Math.floor(Math.random() * charactersLength));
	    }
	    return result;
	},
};