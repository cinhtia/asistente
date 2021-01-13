<?php 

class BaseController {

	public $esAjax = false;
	public $titulo = "";
	public $nombre_ruta = "";
	public $existe_sesion = false;
	public $user = null;


	protected function render($strTemplate, Data $data, $htmlFile = true, $auto_exit = true){
		$ruta = $this->nombre_ruta;
		$user = $this->user;
		if($this->esAjax){
			if($htmlFile){
				$es_html_file = true;
				if(file_exists(DIRECTORY."views/$strTemplate".".template.php")){
					include(DIRECTORY."views/$strTemplate".".template.php");
				}else{
				    $file_path = DIRECTORY.'views/not-found.php';
				    include(DIRECTORY."views/$strTemplate".".template.php");

				}
			}else{
				print $strTemplate;
			}
		}else{
			$incluir_sidebar = !$this->esAjax && $this->user;
			$incluir_nav = !$this->esAjax;
			$titulo = $this->titulo ? $this->titulo : DEFAULT_TITLE;

			if($htmlFile){
				$es_html_file = true;
				if(file_exists(DIRECTORY."views/$strTemplate".".template.php")){
					$file_path = DIRECTORY."views/$strTemplate".".template.php";
				}else{
				    $file_path = DIRECTORY.'views/not-found.php';
				    $filename = DIRECTORY."views/$strTemplate".".template.php";
				}
			}else{
				$es_html_file = false;
				$file_path = $strTemplate;
			}

			include_once DIRECTORY.'views/layout.template.php';
			if($auto_exit){
				die();
			}
		}
	}

	protected function redirect($name){
		$data = Router::obtenerData($name);
		$ruta = $data['ruta'];
		// print BASE_URL_WEB.$ruta;
		header('Location: '.BASE_URL_WEB.$ruta);
		die();
	}



	public function Error404Action(Request $req, Response $res, Data $data){
		header("HTTP/1.0 404 Not Found");
		$dataHtml = "
		<div class='container'>
			<h1 class='text-center text-danger'>Error 404</h1>
			<h4 class='text-center'>Error en la solicitud. No se ha encontrado la ruta solicitada</h4>
		</div>
		";
		$this->render($dataHtml, $data, false);
	}

	public function Error404JsonAction(Request $req, Response $res, Data $data){
		$data->setErrorMessage('404 Pagina no encontrada');
		$res->json($data->forJSON());
	}

	public function Error401Action(Request $req, Response $res, Data $data){
		header("HTTP/1.0 401");
		$dataHtml = "
		<div class='container'>
			<h1 class='text-center text-danger'>Error 401</h1>
			<h4 class='text-center'>Error en la solicitud. Permiso denegado</h4>
		</div>
		";
		$this->render($dataHtml, $data, false);
	}

	public function Error401JsonAction(Request $req, Response $res, Data $data){
		$data->setErrorMessage('401 Permisos denegados a este módulo o acción');
		$res->json($data->forJSON());
	}
}