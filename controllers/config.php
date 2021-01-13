<?php

// requerir (require_once...) aqui los nuevos controladores
require_once 'BaseController.php';
// require_once 'IndexController.php';
// require_once 'AsistenteController.php';
// require_once 'AsistenteUnidadController.php';
// require_once 'AsistenteResultadoController.php';
// require_once 'VerboController.php';
// require_once 'ContenidoController.php';
// require_once 'ContextoController.php';
// require_once 'CriterioController.php';
// require_once 'CompetenciaController.php';
// require_once 'UsuarioController.php';
// require_once 'ActitudValorController.php';
// require_once 'AcademicoController.php';
// require_once 'InstitucionController.php';
// require_once 'PlanEstudioController.php';
// require_once 'AsignaturaController.php';
// require_once 'CompetenciaGenericaController.php';
// require_once 'ConocimientoController.php';
// require_once 'HabilidadController.php';
// require_once 'UnidadController.php';
// require_once 'CompetenciaDisciplinarController.php';
// require_once 'ContenidoUnidadAsignaturaController.php';

foreach (glob("controllers/*Controller.php") as $filename){
	if(strpos($filename, 'Controller.php') !== false)
    	require_once $filename;
}