<?php 
class Helper{
	const REP_ACCENT = array(
		'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â'  => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
		'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ'  => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
		'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î'  => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
		'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ'  => 'b', 'ÿ' => 'y'
	);

	public static function snake2Camel($snakeCase){
		return str_replace('_','',ucwords($snakeCase,'_'));
	}

	public static function randomString($longitud){
		$diccionario = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $contrasena = array(); 
	    $longitudDict = strlen($diccionario) - 1; 

	    for ($i = 0; $i < $longitud; $i++) {
	        $n = rand(0, $longitudDict);
	        $contrasena[] = $diccionario[$n];
	    }
	    
	    return implode($contrasena);
	}

	public static function currentDate($time = false){
		return $time?date('Y-m-d H:i:s'):date('y-m-d');
	}

	public static function formalDate($date=null){

		if($date == null){
			$h = new DateTime();
			return strftime("%a, %d %B %G %I:%M %p", $h->getTimestamp());
			// return date("F j, Y, g:i a");
		}

		$dt = new DateTime($date);
		return strftime("%a, %d %B %G %I:%M %p",$dt->getTimestamp());
	}

	public static function formalDateNoTime($date){
		$dt = new DateTime($date);
		return strftime("%d de %B de %G",$dt->getTimestamp());
	}

	public static function shortFormalDate($date = null){
		if($date == null){
			$h = new DateTime();
			return strftime("%x", $h->getTimestamp());
		}
		$dt = new DateTime($date);
		return strftime("%x", $dt->getTimestamp());
	}

	public static function shortFormalDatetime($date = null){
		if($date == null){
			$h = new DateTime();
			return strftime("%x %I:%M %p", $h->getTimestamp());
		}
		$dt = new DateTime($date);
		return strftime("%x %I:%M %p", $dt->getTimestamp());
	}

	public static function formatDate($fecha, $formato)
	{
		$dt = new DateTime($fecha);
		return $dt->format($formato);
	}

	public static function bs4Paginate($currentPage, $total, $count, $class, $currentTotal = -1){
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

	public static function normalize($token = ''){
		return strtr( $token, self::REP_ACCENT);
	}

	public static function humanizeSnake($str) {
	    return ucwords(str_replace("_", " ", $str));
	}

	// Los 4 primeros parametros son de tipo DateTime
	public static function existeTraslapeFechas(DateTime $fIni, DateTime $fFin, DateTime $f2Ini, DateTime $f2Fin, $comprobar_casos_especificos = true){
		$ret = false;

		// primero verificamos dos casos muy especificos en los cuales,
		// no existe traslape
		// primer caso especifico: la fecha $fFin es igual a la fecha $f2Ini 
		// 							(el nuevo taller inicia cuando termina el taller guardado)
		// segund caso especifico: la fecha $fIni es igual a la fecha $f2Fin
		// 							(el nuevo taller termina cuando inicia el taller guardado)
		if($comprobar_casos_especificos && ( $fFin == $f2Ini || $fIni == $f2Fin )){
			return false;
		}
		
		// las fechas se traslapan:
		// caso 1:
		if( $f2Ini <= $fIni && $f2Fin >= $fFin ){
			$ret = true;

		// caso 2:
		} else if( $f2Ini >= $fIni && $f2Ini <= $fFin && $f2Fin >= $fFin ){
			$ret = true;

		// caso 3:
		} else if( $f2Ini <= $fIni &&  $fIni <= $f2Fin && $f2Fin <= $fFin ){
			$ret = true;

		// caso 4:
		} else if( $fIni <= $f2Ini && $f2Fin <= $fFin ) {
			$ret = true;
		}

		return $ret;
	}

	public static function toBool($var) {
	  if (!is_string($var)) return (bool) $var;
	  switch (strtolower($var)) {
	    case '1':
	    case 'true':
	    case 'on':
	    case 'yes':
	    case 'y':
	      return true;
	    default:
	      return false;
	  }
	}

}