<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class ContableD1 extends BaseV1 {
	public function __construct()
{
		parent::__construct();
//		if(!$this->ion_auth->logged_in())
//		{
//			redirect('auth','refresh');
//		}
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
        $this->load->library('form_validation');		
//		$this->load->helper(array('form','template'));
	}


	public function get_cuentas_catalogo_get() {
		//$idcredito = $this->uri->segment(4);
		$fields = array("idcuenta", "numero", "descripcion", "idtipo", "cuenta_tipo", "clase", "cuenta_clase", "naturaleza", "cuenta_naturaleza", "nivel");
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$catalogo = $this->base->selectRecord("fin.v_cuentas_catalogo", $fields, "", "", "","", "", $order_by, "","", TRUE);
		if ($catalogo) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Catálogo obtenido correctamente!",
				"catalogo"=> $catalogo,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Catálogo no encontrado!");
		}
		$this->returnData($data);
	}

	public function get_auxiliar_init_get() {
		$query = "SELECT idcuenta as value, (numero || ' - ' || descripcion) as name FROM ".$this->session->userdata('esquema')."cuentas WHERE clase ='R' ORDER BY numero";
		$cataux = $this->base->querySelect($query, true);
            if ($cataux) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Tabla consultada",
                        "cataux" => $cataux
                  );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"Error al tratar de consultar",
                        "cataux" => $cataux
                        );
            }
            $this->returnData($data);
	}      


	public function get_balanza_init_get() {
            $ejercicio[] = array ("value" =>2017,"name" =>"2017");

            $meses[] = array ("value" =>1,"name" =>"Enero");
            $meses[] = array ("value" =>2,"name" =>"Febrero");
            $meses[] = array ("value" =>3,"name" =>"Marzo");
            $meses[] = array ("value" =>4,"name" =>"Abril");
            $meses[] = array ("value" =>5,"name" =>"Mayo");
            $meses[] = array ("value" =>6,"name" =>"Junio");
            $meses[] = array ("value" =>7,"name" =>"Julio");
            $meses[] = array ("value" =>8,"name" =>"Agosto");
            $meses[] = array ("value" =>9,"name" =>"Septiembre");
            $meses[] = array ("value" =>10,"name" =>"Octubre");
            $meses[] = array ("value" =>11,"name" =>"Noviembre");
            $meses[] = array ("value" =>12,"name" =>"Diciembre");
		if ($meses) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Tabla consultada",
                        "catejercicio" => $ejercicio,
                        "catmes" => $meses
                  );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"Error al tratar de consultar",
                        "catejercicio" => $ejercicio,
                        "catmes" => $meses
                        );
            }
            $this->returnData($data);
	}      

	public function get_Balanza_get(){
            $nivel = $this->uri->segment(4);
		$valores = $this->get('data')?$this->get('data', TRUE):array();
		//Carga de los modelos 
		$datos = fn_extraer($valores,'N');
            $ejercicio = $datos['ejercicio'];
            $mes = $datos['mes'];
            //$nivel = 7;
		$query = "SELECT idcuenta, numero, descripcion, naturaleza, clase, nivel, saldo_inicial_debe, saldo_inicial_haber, debe, haber, saldo_debe, saldo_haber "
                  ."FROM ".$this->session->userdata('esquema')."get_balanza_mes('".$this->session->userdata('sucursal_id')."', ".$ejercicio."::smallint, ".$mes."::smallint, ".$nivel."::smallint, '', '', true)";
		$balanza = $this->base->querySelect($query, true);
		if ($balanza) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Balanza obtenida correctamente!",
				"balanza"=> $balanza,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Balanza no encontrada!");
		}
		$this->returnData($data);
	}

	public function get_Auxiliar_get(){
            $idcuenta = $this->uri->segment(4);            
		$valores = $this->get('data')?$this->get('data', TRUE):array();
		//Carga de los modelos 
		$datos = fn_extraer($valores,'N');

		$query = "SELECT idpoliza, idmovimiento, fecha, tipo, numero, concepto, referencia, debe, haber, saldo FROM "
                  .$this->session->userdata('esquema')."get_auxiliar('".$this->session->userdata('sucursal_id')."', ".$idcuenta.", '01/01/2017'::date, '31/12/2017'::date)";
		$auxiliar = $this->base->querySelect($query, true);
		if ($auxiliar) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Balanza obtenida correctamente!",
				"auxiliar"=> $auxiliar,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Balanza no encontrada!");
		}
		$this->returnData($data);
	}
      

function getFechaToText($fecha){
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$salida ='';
	$salida = $fecha; //$dias[$date('w')]." ".$date('d')." de ".$meses[$date('n')-1]. " del ".$date('Y') ;
	return $salida;
	
}


/*! 
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @result string - Devuelve el n?mero escrito en letra. 

*/ 
function numeroToLetras($num, $fem = false, $dec = true) { 
      $matuni[2]  = "dos";    $matuni[3]  = "tres"; 
      $matuni[4]  = "cuatro"; 
      $matuni[5]  = "cinco"; 
      $matuni[6]  = "seis"; 
      $matuni[7]  = "siete"; 
      $matuni[8]  = "ocho"; 
      $matuni[9]  = "nueve"; 
      $matuni[10] = "diez"; 
      $matuni[11] = "once"; 
      $matuni[12] = "doce"; 
      $matuni[13] = "trece"; 
      $matuni[14] = "catorce"; 
      $matuni[15] = "quince"; 
      $matuni[16] = "dieciseis"; 
      $matuni[17] = "diecisiete"; 
      $matuni[18] = "dieciocho"; 
      $matuni[19] = "diecinueve"; 
      $matuni[20] = "veinte"; 
      $matunisub[2] = "dos"; 
      $matunisub[3] = "tres"; 
      $matunisub[4] = "cuatro"; 
      $matunisub[5] = "quin"; 
      $matunisub[6] = "seis"; 
      $matunisub[7] = "sete"; 
      $matunisub[8] = "ocho"; 
      $matunisub[9] = "nove"; 

      $matdec[2] = "veint"; 
      $matdec[3] = "treinta"; 
      $matdec[4] = "cuarenta"; 
      $matdec[5] = "cincuenta"; 
      $matdec[6] = "sesenta"; 
      $matdec[7] = "setenta"; 
      $matdec[8] = "ochenta"; 
      $matdec[9] = "noventa"; 
      $matsub[3]  = 'mill'; 
      $matsub[5]  = 'bill'; 
      $matsub[7]  = 'mill'; 
      $matsub[9]  = 'trill'; 
      $matsub[11] = 'mill'; 
      $matsub[13] = 'bill'; 
      $matsub[15] = 'mill'; 
      $matmil[4]  = 'millones'; 
      $matmil[6]  = 'billones'; 
      $matmil[7]  = 'de billones'; 
      $matmil[8]  = 'millones de billones'; 
      $matmil[10] = 'trillones'; 
      $matmil[11] = 'de trillones'; 
      $matmil[12] = 'millones de trillones'; 
      $matmil[13] = 'de trillones'; 
      $matmil[14] = 'billones de trillones'; 
      $matmil[15] = 'de billones de trillones'; 
      $matmil[16] = 'millones de billones de trillones'; 

      //Zi hack
      $float=explode('.',$num);
      $num=$float[0];

      $num = trim((string)@$num); 
      if ($num[0] == '-') { 
            $neg = 'menos '; 
            $num = substr($num, 1); 
      }else 
            $neg = ''; 
      while ($num[0] == '0') $num = substr($num, 1); 
      if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
      $zeros = true; 
      $punt = false; 
      $ent = ''; 
      $fra = ''; 
      for ($c = 0; $c < strlen($num); $c++) { 
            $n = $num[$c]; 
            if (! (strpos(".,'''", $n) === false)) { 
            if ($punt) break; 
            else{ 
                  $punt = true; 
                  continue; 
            } 

            }elseif (! (strpos('0123456789', $n) === false)) { 
            if ($punt) { 
                  if ($n != '0') $zeros = false; 
                  $fra .= $n; 
            }else 

                  $ent .= $n; 
            }else 

            break; 

      } 
      $ent = '     ' . $ent; 
      if ($dec and $fra and ! $zeros) { 
            $fin = ' coma'; 
            for ($n = 0; $n < strlen($fra); $n++) { 
            if (($s = $fra[$n]) == '0') 
                  $fin .= ' cero'; 
            elseif ($s == '1') 
                  $fin .= $fem ? ' una' : ' un'; 
            else 
                  $fin .= ' ' . $matuni[$s]; 
            } 
      }else 
            $fin = ''; 
      if ((int)$ent === 0) return 'Cero ' . $fin; 
      $tex = ''; 
      $sub = 0; 
      $mils = 0; 
      $neutro = false; 
      while ( ($num = substr($ent, -3)) != '   ') { 
            $ent = substr($ent, 0, -3); 
            if (++$sub < 3 and $fem) { 
                  $matuni[1] = 'una'; 
                  $subcent = 'as'; 
            }else{ 
                  $matuni[1] = $neutro ? 'un' : 'uno'; 
                  $subcent = 'os'; 
            } 
            $t = ''; 
            $n2 = substr($num, 1); 
            if ($n2 == '00') { 
            }elseif ($n2 < 21) 
            $t = ' ' . $matuni[(int)$n2]; 
            elseif ($n2 < 30) { 
            $n3 = $num[2]; 
            if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
            $n2 = $num[1]; 
            $t = ' ' . $matdec[$n2] . $t; 
            }else{ 
            $n3 = $num[2]; 
            if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
            $n2 = $num[1]; 
            $t = ' ' . $matdec[$n2] . $t; 
            } 
            $n = $num[0]; 
            if ($n == 1) { 
            $t = ' ciento' . $t; 
            }elseif ($n == 5){ 
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
            }elseif ($n != 0){ 
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
            } 
            if ($sub == 1) { 
            }elseif (! isset($matsub[$sub])) { 
            if ($num == 1) { 
                  $t = ' mil'; 
            }elseif ($num > 1){ 
                  $t .= ' mil'; 
            } 
            }elseif ($num == 1) { 
            $t .= ' ' . $matsub[$sub] . '?n'; 
            }elseif ($num > 1){ 
            $t .= ' ' . $matsub[$sub] . 'ones'; 
            }   
            if ($num == '000') $mils ++; 
            elseif ($mils != 0) { 
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
            $mils = 0; 
            } 
            $neutro = true; 
            $tex = $t . $tex; 
      } 
      $tex = $neg . substr($tex, 1) . $fin; 
      //Zi hack --> return ucfirst($tex);
      $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
      return $end_num; 
} 


	/*
	* obtiene la paginación y los registros
	*/
	public function pagina_get() {
		$this->load->helper('paginacion');
		$pagina = $this->uri->segment(4);
		$por_pagina = $this->uri->segment(5);
		$tabla = "bancos";
		if ($content = $this->get('from', TRUE)) {
			$tabla = $content;
		}
		$order_by = array();
		if ($content = $this->get('orderby', TRUE)) {
			$order_by = $content;
		}		
		$respuesta = pagina_table( $tabla , $pagina, $por_pagina, $order_by);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}
}
