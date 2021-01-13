<?php 

// requerir (require_once...) aqui los nuevos modelos
// require_once 'BaseModel.php';
// require_once 'TblUsuario.php';
// require_once 'TblVerbo.php';
// require_once 'TblContenido.php';
// require_once 'TblContexto.php';
// require_once 'TblCriterio.php';
// require_once 'TblCompo_actitud_valor.php';
// require_once 'TblInstitucion.php';
// require_once 'TblPlanEstudio.php';
// require_once 'TblAsignatura.php';
// require_once 'TblAsignaturaPe.php';
// require_once 'TblCG.php';
// require_once 'TblCompetenciaGenericaInstitucion.php';
// require_once 'TblMallaCurricular.php';
// require_once 'TblCompetencia.php';
// require_once 'TblVerboCompetencia.php';
// require_once 'TblContenidoCompetencia.php';
// require_once 'TblContextoCompetencia.php';
// require_once 'TblCriterioCompetencia.php';
// require_once 'TblCompo_conocimiento.php';
// require_once 'TblCompo_habilidad.php';
// require_once 'TblConocimientoCompetencia.php';
// require_once 'TblActitudValorCompetencia.php';
// require_once 'TblHabilidadCompetencia.php';

foreach (glob("models/Tbl*.php") as $filename){
    require_once $filename;
}