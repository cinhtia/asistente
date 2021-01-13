<div class="text-center" style="position: fixed; bottom: 0px; width: 100%;">
	<small>Asistente para evaluar competencias | <a href="mailto:gsegura.uady@gmail.com">Contacto</a></small>
</div>

<script>
	// $(function () {
	//   $('[data-toggle="tooltip"]').tooltip()
	// });

	$('body').tooltip({
	    selector: '[data-toggle=tooltip]'
	});

	$('body').popover({
	    selector: '[data-toggle="popover"]',
	    trigger: 'hover',
	});

	$(document).off('click', '.btn-paginacion').on('click', '.btn-paginacion', function(e){
		e.preventDefault();
		cargarListado($(this).data('page'));
	});
</script>
