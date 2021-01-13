<?php

class AcademicoController extends BaseController{


	public function IndexAction(Request $req, Response $res, Data $data){
		//$args['error'] = false;
		//$args['mensaje'] = "";
		

		$data->addToBody('seccion',$req->getIfExists('seccion',1));
		$data->addToBody('page',$req->getIfExists('page',1));
		$this->render('academico/index', $data);
	}


}