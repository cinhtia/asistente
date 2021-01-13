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

	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_arrows.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_circles.min.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/smart-wizard/css/smart_wizard_theme_dots.min.css">
	
	
	<!-- librerias js -->
	<script src="<?= BASE_URL_WEB ?>vendor/jquery/jquery-3.3.1.min.js"></script>
	<!-- Añadimos las librerias para jquery-ui -->
	<script src="<?= BASE_URL_WEB ?>vendor/jquery/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="<?= BASE_URL_WEB?>vendor/jquery/jquery-validate/jquery.validate.min.js"></script>
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/tagit/jquery.tagit.css">
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/tagit/tagit.ui-zendesk.css">


	<script src="<?= BASE_URL_WEB ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/script.js"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/utils.js"></script>
	<script src="<?= BASE_URL_WEB ?>assets/js/api.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/smart-wizard/js/jquery.smartWizard.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/toastr/toastr.min.js"></script>
	<script src="<?= BASE_URL_WEB ?>vendor/tagit/tag-it.js"></script>

	<!-- script src="< ?= BASE_URL_WEB ? >assets/js/angular.min.js"></script -->
	<link rel="stylesheet" href="<?= BASE_URL_WEB ?>vendor/multi-select/multi-select.dist.css">
	<script type="text/javascript" src="<?= BASE_URL_WEB ?>vendor/multi-select/jquery.multi-select.js"></script>
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

	</style>

</head>
<body>
<?php require_once 'nav.php' ?>

<div id="contenedor_loading_ajax">
	<div class="progress">
	  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
	</div>
</div>


<script type="text/javascript">
	
	$(document).bind('ajaxStart', function(){
	    $('#contenedor_loading_ajax').show();
	}).bind('ajaxStop', function(){
	    $('#contenedor_loading_ajax').hide();
	    // $('#contenedor_loading_ajax').hide("slide", {direction: "left" }, 100);
	});

	$(document).ready(function(){
		$('#contenedor_loading_ajax').hide();
	});
</script>