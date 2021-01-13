var api = {
	get : function ( config ){
		
		$.ajax({
		    url: config.url,
		    method: 'get',
		    data: config.data || {},
		    success: function(response){
				if(config.json){
					if(config.cb){
						config.cb(response);
					}else if(config.success){
						config.success(response);
					}else{
						console.log(response);
					}
				}else{
					if(config.contenedor){
						$(config.contenedor).html(response);
					}

					if(config.cb){
						config.cb(response);
					}else if(config.success){
						config.success(response);
					}
				}
		    },
		    error: function(xhr){
		     	if(config.error){
		     		config.error(xhr);
		     	} 
		    }
		});
	},
	post: function (config){
		$.ajax({
		    url: config.url,
		    method: 'post',
		    data: config.data || {},
		    success: function(response){
				if(config.json){
					if(config.cb){
						config.cb(response);
					}else if(config.success){
						config.success(response);
					}else{
						if(config.success){
							config.success(response);
						}else
							console.log(response);
					}
				}else{
					if(config.contenedor){
						$(config.contenedor).html(response);
					}

					if(config.cb){
						config.cb(response);
					}else if(config.success){
						config.success(response);
					}
				}
		    },
		    error: function(xhr){
		        if(isFunction(config.error) ){
		     		config.error(xhr);
		     	}else{
		     		if(config.errorMessage){
		     			utils.alert.error(config.errorMessage);
		     		}
		     	}
		    }
		});
	}
};
