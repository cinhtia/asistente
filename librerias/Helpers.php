<?php

class Helpers{

	const REP_ACCENT = array(
		'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â'  => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
		'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ'  => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
		'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î'  => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
		'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ'  => 'b', 'ÿ' => 'y'
	);

	public static $error_request= array(
		"estado"=>false,
		"error"=>"Error en tu petición",
		"mensaje"=>"Error en tu petición"
		);

	public static $error_server=array(
		"estado"=>false,
		"mensaje"=>"Error en el servidor",
		"error"=>"Error en el servidor"
		);

	public static $error_parameters = array(
		"estado"=>false,
		"mensaje"=>"Valores faltantes",
		"error"=>"Valores faltantes"
		);

	public static $error_invalid_parameters = array(
		"estado"=>false,
		"mensaje"=>"Algunos valores no son válidos",
		"error"=>"Algunos valores no son válidos"
	);

	public static $error_session = array(
		"estado"=>false,
		"mensaje"=>"No has iniciado sesión",
		"error"=>"No has iniciado sesión"
		);

	public static $session_start_failed = array(
		"estado"=>false,
		"mensaje"=>"Credenciales inválidas",
		"error"=>"Credenciales inválidas"
		);

	public static function generateRandomApiKey(){
		return md5(microtime().rand());
	}

	public static function generateRandomString($length = 10) {
	    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public static function currentDateTime(){
		return self::fechaHoy(true);
	}

	public static function fechaHoy($hora=false){
		return $hora?date('Y-m-d H:i:s'):date('y-m-d');
	}

	public static function seralizarArray2ParametrosMysql($arrayElements){
		return str_repeat('?,', count($arrayElements) - 1) . '?';
	}

	public static function fechaFormal($hoy, $fecha=null){

		if($hoy){
			$h = new DateTime();
			return strftime("%a, %d %B %G %I:%M %p", $h->getTimestamp());
			// return date("F j, Y, g:i a");
		}

		$date = new DateTime($fecha);// strtotime($fecha);// date('F j, Y, g:i a', strtotime($fecha));
		return strftime("%a, %d %B %G %I:%M %p",$date->getTimestamp());
	}

	public static function fechaFormalLocal($fecha, $formato){
		$date = new DateTime($fecha);// strtotime($fecha);// date('F j, Y, g:i a', strtotime($fecha));
		return strftime($formato,$date->getTimestamp());
	}

	public static function fechaFormalCorta($hoy, $fecha = null){
		if($hoy){
			$h = new DateTime();
			return strftime("%x", $h->getTimestamp());
		}
		$date = new DateTime($fecha);
		return strftime("%x", $date->getTimestamp());
	}


	public static function paginacionHTML($currentPage, $total, $count, $class, $currentTotal = -1){
		$fres = $total/$count;
		if($total % $count >0){
			$fres += 1;
		}
		$tPages = intval($fres);

		$var = "";
		
		$prevDisabled = $currentPage == 1 ? "disabled" : "";
		$lastDisabled = $currentPage == $tPages ? "disabled" : "";
		if($tPages >= 1 && $tPages <= 10){
			$var = '<nav aria-label="Page navigation" style="margin-top:10px; margin-bottom:10px;">
					  <ul class="pagination">
					    <li class="page-item '.$prevDisabled.' "><a class="page-link '.$class.'" data-page="'.($currentPage-1).'"  href="#"> <i class="fa fa-angle-left" aria-hidden="true"></i> </a></li>';

			for($i = 1; $i <= $tPages; $i++){
				$activeClass = $i == $currentPage ? "active" : "";
				$var .= '<li class="page-item '.$activeClass.'"><a class="page-link '.$class.'" data-page="'.$i.'" href="#">'.$i.'</a></li>';
			}

			$var .=    '<li class="page-item '.$lastDisabled.'"><a class="page-link '.$class.'" href="#" data-page="'.($currentPage+1).'" ><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
					  </ul>
					</nav>';
		}else{

			$var = '<nav aria-label="Page navigation" style="margin-top:10px; margin-bottom:10px;">
					  <ul class="pagination">
					    <li class="page-item '.$prevDisabled.' "><a class="page-link '.$class.'" data-page="'.($currentPage-1).'"  href="#"> <i class="fa fa-angle-left" aria-hidden="true"></i> </a></li>';

			$btns = [];
			$maxIntervalo = 4; // siempre debe ser par
			$middle = $maxIntervalo/2;
			if($currentPage >=1 && $currentPage <=$maxIntervalo){

				for ($i=1; $i <= $maxIntervalo+1; $i++) { 
					$btns[] = ['page'=>$i,'disabled'=>false];
				}
				
				$btns[] = ['page'=>'...','disabled'=>true];

				for ($i = $middle; $i >= 0 ; $i--) {
					$btns[] = ['page'=>$tPages-$i,'disabled'=>false];
				}

			}else if($currentPage >= ($tPages-$maxIntervalo)){
				
				for ($i = 1; $i <= $middle ; $i++) {
					$btns[] = ['page'=>$i,'disabled'=>false];
				}
				
				$btns[] = ['page'=>'...','disabled'=>true];

				for ($i = $maxIntervalo; $i >= 0; $i--) { 
					$btns[] = ['page'=>$tPages - $i,'disabled'=>false];
				}
			}else{
				$btns[] = ['page'=>1,'disabled'=>false];
				$btns[] = ['page'=>'...','disabled'=>true];

				for ($i = -$middle; $i <= $middle ; $i++) {
					$btns[] = ['page'=>$currentPage + $i,'disabled'=>false];
				}

				$btns[] = ['page'=>'...','disabled'=>true];
				$btns[] = ['page'=>$tPages,'disabled'=>false];
			}

			foreach ($btns as $dpage) {
				$activeClass = $dpage['page'] == $currentPage ? "active" : "";
				$var .= '<li class="page-item '.( $dpage['disabled'] ? "disabled" : "" ).' '.$activeClass.'" ><a class="page-link '.$class.'" data-page="'.$dpage['page'].'" href="#">'.$dpage['page'].'</a></li>';
			}

			$var .=    '<li class="page-item '.$lastDisabled.'"><a class="page-link '.$class.'" href="#" data-page="'.($currentPage+1).'" ><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
					  </ul>
					</nav>';

		}

		$msjExtra = "";
		if($currentTotal >= 0){
			if($currentTotal==0){
				$msjExtra = "<br><div class='alert alert-info'>No se encontraron resultados</div>";
			}else{
				$msjExtra = "<br><strong>Listando $currentTotal de $total resultados</strong><br>";
			}
		}

		return $msjExtra. ($total>0 && $total > $count ? $var : "");

		return "";
		
	}

	public static function options($arr, $key, $value, $isObj){
		$out = [];

		foreach ($arr as $index => $item) {
			if($isObj){
				$out[$item->$key] = $item->$value;
			}else{
				$out[$item[$key]] = $item[$value];
			}
		}

		return $out;
	}

	public static function substring($str, $max = 0){
		if($max == 0)
			return $str;

		return substr($str, 0, min($max, strlen($str))).(strlen($str) > $max ? '...' : '');
	}

	public static function textoNoAcentos($token = ''){
		return strtr( $token, self::REP_ACCENT);
	}

	public static function sinonimosModalidadAda($modalidad){
		if($modalidad == 'individual'){
			return implode(" ", MODALIDAD_INDIVIDUAL_SINONIMOS);
		}else if($modalidad == 'pares'){
			return implode(" ", MODALIDAD_PARES_SINONIMOS);
		}else if($modalidad == 'equipo'){
			return implode(" ", MODALIDAD_EQUIPO_SINONIMOS);
		}else if($modalidad == 'grupal'){
			return implode(" ", MODALIDAD_GRUPAL_SINONIMOS);
		}
		return $modalidad;
	}

	public static function removerConectores($str = ''){
		// removemos las puntuaciones
		$punc = array("?","!",",",";",".");
		$str = str_replace($punc, "", strtolower($str));
		
		// removemos pronombres y conectores innecesarios
		foreach (SEPARADORES as $token) {
			$str = str_replace(" $token ", " ", $str);
		}

		return $str;
	}

	public static function repetir($opts){
		if(isset($opts['str'])){
			return implode((isset($opts['separador']) ? $opts['separador'] : ','), array_fill(0, (isset($opts['total']) ? $opts['total'] : 1 ) , $opts['str']));
		}
		return "";
	}

	public static function formatoFecha($fecha, $formato)
	{
		$dt = new DateTime($fecha);
		return $dt->format($formato);
	}

	public static function getJSONFromFile($json_filename){
		$file_path = DIRECTORY.$json_filename;
		if(file_exists($file_path)){
			$json = json_decode(file_get_contents($file_path), true);
			return $json;
		}
		return [];
	}

	public static function numeroSemestreATexto($numero){
		if($numero == 1){
			return "Primer semestre";
		}else if($numero == 2){
			return "Segundo semestre";
		}else if($numero == 3){
			return "Tercer semestre";
		}else if($numero == 4){
			return "Cuarto semestre";
		}else if($numero == 5){
			return "Quinto semestre";
		}else if($numero == 6){
			return "Sexto semestre";
		}else if($numero == 7){
			return "Séptimo semestre";
		}else if($numero == 8){
			return "Octavo semestre";
		}else if($numero == 9){
			return "Noveno semestre";
		}else{
			return "Semestre ".$numero;
		}
	}

	public static function numberToRomanRepresentation($number){
		$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
		    $returnValue = '';
		    while ($number > 0) {
		        foreach ($map as $roman => $int) {
		            if($number >= $int) {
		                $number -= $int;
		                $returnValue .= $roman;
		                break;
		            }
		        }
		    }
		    return $returnValue;
	}

	public static function jsonString2Array($string){
		return json_decode(stripslashes(html_entity_decode(trim($string))), true);
	}

}
