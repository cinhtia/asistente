<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?=BASE_URL_WEB?>" >
	<title><?= $titulo ?></title>

	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>assets/css/style.css">
	
	<!-- css para jquery ui -->
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/jquery/jquery-ui.min.css">

	<!-- css para jquery validator -->
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/jquery/jquery-validate/css/screen.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/metismenu/metisMenu.min.css">

	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_arrows.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_circles.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_dots.min.css">
	
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/jquery.datetimepicker/jquery.datetimepicker.min.css">
	
	
	<!-- librerias js -->
	<script src="<?= BASE_URL_WEB ?>vendor/jquery/jquery-3.3.1.min.js"></script>
	<!-- Añadimos las librerias para jquery-ui -->
	<script src="<?= BASE_URL_WEB ?>vendor/jquery/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="<?= BASE_URL_WEB?>vendor/jquery/jquery-validate/jquery.validate.min.js"></script>
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/tagit/jquery.tagit.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/tagit/tagit.ui-zendesk.css">


	<script src="<?= BASE_URL_WEB ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/script.js"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/utils.js?e6a295b4e317e4214b7673872a8b3155"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/api.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/toastr/toastr.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/tagit/tag-it.js"></script>

	<script src="<?= BASE_URL_WEB ?>vendor/metismenu/metisMenu.min.js"></script>

	<!-- script src="< ?= BASE_URL_WEB ? >assets/js/angular.min.js"></script -->
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/multi-select/multi-select.dist.css">
	<script type="text/javascript" src="<?= BASE_URL_WEB ?>vendor/multi-select/jquery.multi-select.js"></script>
	
	<script type="text/javascript" src="<?= BASE_URL_WEB ?>vendor/jquery.datetimepicker/jquery.datetimepicker.full.min.js"></script>
	
	<!-- tinymce -->
	<script type="text/javascript" src="<?= BASE_URL_WEB ?>vendor/tinymce/tinymce.min.js"></script>
	


	<style type="text/css">
		
		#contenedor_loading_ajax{
			z-index: 9999999;
			position: fixed;
			top:0px;
			width: 100%;
		}

		#contenedor_loading_ajax .progress{
			border-radius: 0px;
			height: 10px;
		}

		.no-display{
			display: none;
			transition: 0.3s;
		}

		.display-table{
			display: table;
			transition: 0.2s;
			height: 100%;
		}

		/*body > .display-table{
			overflow: hidden;
		}*/

		.panel-display-info-busy{
			background-color: white;
			padding: 20px;
			text-align: center;
			display: inline-block;
			border-radius: 8px;
		}

	</style>

</head>
<body>



<div id="contenedor_loading_ajax">
	<div class="progress">
	  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
	</div>
</div>
<?php

if(count(MENU_SIDEBAR) == 0){
	$incluir_sidebar = false;
}

if($incluir_nav){ require_once 'nav.php'; }
?>
<div class="container-fluid">
	<?php if($incluir_sidebar){ ?>
	  <div class="row">
	    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
	      <?php include DIRECTORY.'views/sidebar.template.php' ; 
	      ?>
	    </nav>
	    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	    	<?php 
	    	if($es_html_file){ 
	    		include $file_path; 
	    	}else{
	    		print $file_path;
	    	} ?>
	    </main>
	  </div>
	<?php }else{ ?>
		<div style="margin-top: 45px;">
	    	<?php include $file_path; ?>
		</div>
	<?php } ?>
</div>


<div class="no-display" id="main_loader_busy" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; bottom: 0; background-color: rgba(0,0,0,0.5); text-align: center; vertical-align: middle; z-index: 9999999;">
	<div style="display: table-cell; vertical-align: middle;" >
		<div class="panel-display-info-busy">
			<span id="main_loader_busy_message">Cargando...</span> <br>
			<img src="<?= img('ajax-loader.gif') ?>" alt="Cargando">
		</div>
	</div>
</div>

<?php 
include_once 'views/partial_modal.php';
include_once 'views/partial_modal_confirm.php';
include 'views/footer.php';
?>
<script type="text/javascript">

	$("#metismenu").metisMenu();

	$(document).bind('ajaxStart', function(){
	    $('#contenedor_loading_ajax').show();
	}).bind('ajaxStop', function(){
	    $('#contenedor_loading_ajax').hide();
	    // $('#contenedor_loading_ajax').hide("slide", {direction: "left" }, 100);
	});

	$(document).ready(function(){
		$('#contenedor_loading_ajax').hide();
			
		jQuery.datetimepicker.setLocale('es');

	});
</script>
</body>
</html>