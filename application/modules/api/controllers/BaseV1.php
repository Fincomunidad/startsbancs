<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

require(APPPATH.'/third_party/autoload.php');
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\PrintConnectors\FilePrintConnector;
//use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;


class BaseV1 extends REST_Controller {
	public function __construct()
    {
		parent::__construct();
		if(!$this->ion_auth->logged_in())
		{
			redirect('auth','refresh');
		}
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
        $this->load->library('form_validation');		
//		$this->load->helper(array('form','template'));
//		$this->load->library('form_validation');
	}

	/*
	* Retorna los errores en las reglas de validación
	*/
    function validaForm() {
			 $data = array("status"=>"ERROR",
  			    "code" => "404",			 
				"message"=>validation_errors(' ', ' '),
				"newtoken"=>$this->security->get_csrf_hash()
				);
			$this->response($data, REST_Controller::HTTP_NOT_FOUND);
	}

	/*
	* Retorna si el dato enviado a buscar en un tipo incorrecto
	*
	*/

	function returnCode(){
		$data = array("status"=>"ERROR",
		"code" => "404",			 
		"message"=>"Dato de búsqueda incorrecto!"
		);
		$this->response($data, REST_Controller::HTTP_NOT_FOUND);		
	}

	/*
	* Retorna los errores en las reglas de validación
	*/
    function returnCodeWithToken() {
			 $data = array("status"=>"ERROR",
  			    "code" => "404",			 
				"message"=>"Dato de búsqueda incorrecto!",
				"newtoken"=>$this->security->get_csrf_hash()
				);
			$this->response($data, REST_Controller::HTTP_NOT_FOUND);
	}

	
    function returnCodeSameUser() {
			 $data = array("status"=>"ERROR",
  			    "code" => "404",			 
				"message"=>"El usuario no puede aplicar movimientos!",
				"newtoken"=>$this->security->get_csrf_hash()
				);
			$this->response($data, REST_Controller::HTTP_NOT_FOUND);
	}


	/*
	* Retorna los valores en un arreglo, segun el código
	* $response = arreglo de los datos obtenidos 
	*/
	function validaCode($response) {
		//Valida el codigo generado
		if ($response['code'] == 409) {
			$this->response($response, REST_Controller::HTTP_CONFLICT);
		}else {
			if ($response['code'] == 200 ) {
			//retorna el registro exitoso
				$this->response($response, REST_Controller::HTTP_OK);
			} else {
				$this->response($response, REST_Controller::HTTP_NOT_FOUND);
			}
		}		
	}

    /*
	* Insertar datos
	* $model = Nombre del modelo para la inserción de los datos en la tabla
	* $valores = los valores crudos a insertar
	* $validation = Nombre de la regla de validación
	*/
	public function insertData($model, $valores, $validation, $adicionData=array()){
		//Carga de los modelos 
		$this->load->model($model.'_model','modelo');
		//obtencion de los campos del formulario enviado
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );		
		//Valida las reglas 
	 	if ($this->form_validation->run($validation) == TRUE) {
			if (!empty($adicionData)) {
				foreach ($adicionData as $key => $valor){
					$datos[$key] = $valor;
				}
			}
			//Asigna valores en la clase		
   		    $data = $this->modelo->set_datos($datos);
			 //Inserta los datos a la tabla
			$insertar = $this->modelo->insertar($data);
			$this->validaCode($insertar);
		} else {
			$this->validaForm();
		}
	}



	


    
	/*
	* Selecciona los valores de una consulta dada
	* $tabla = Nombre de la tabla base
	* $select = Campos a presentar
	* $join= tablas relacionadas con la tabla base o secundaria 
	* $where= filtro de la consulta  
	* $limit= no. de registros a mostrar, 
	* $offset= no. de pagina, 
	* $group_by= group by de postgres se envia como arreglo, 
	* $order_by = order by de postgres se envia como arreglo, 
	* $like =like  de postgres se envia como arreglo, 
	* $whereor=filtro con (OR) en conjunto con $where de la tabla se envia como arreglo 
	* $returnData= TRUE retorna los datos FALSE retorna un arreglo con los datos y codigo
	*/
    public function selectData($tabla, $select, $join=array(), $where=array(), $limit=null, $offset=null, $group_by=array(), $order_by = array(), $like =array(), $whereor=array(), $returnData=FALSE){
        $response = $this->base->selectRecord($tabla, $select, $join, $where, $limit, $offset, $group_by, $order_by, $like, $whereor, $returnData);
        $this->validaCode($response);
    }



	/*
	* Actualiza los datos en una tabla
	* $model = Nombre del modelo para la actualización de los datos en la tabla
	* $valores = los valores crudos a actualizar
	* $validation = Nombre de la regla de validación
	* $isarray = si es un bloque de datos (arreglo)
	*/
    public function updateData($model, $valores, $validation, $where, $isarray,  $adicionData=array()) {
        //Carga de los modelos 
		$this->load->model($model.'_model','modelo');
		//obtencion de los campos del formulario enviado
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );		
		//Valida las reglas 
	 	if ($this->form_validation->run($validation) == TRUE) {
			if (!empty($adicionData)) {
				foreach ($adicionData as $key => $valor){
					$datos[$key] = $valor;
				}
			}
			//Asigna valores en la clase		
   		    $data = $this->modelo->set_datos($datos);
			 //Actualiza los datos en la tabla
			$update = $this->modelo->update($data, $where, $isarray);
			$this->validaCode($update);
		} else {
			$this->validaForm();
		}
    }



	/*
	* Actualiza en una tranaccion 
	*/
    public function updateDataBloque($sqlTrans) {
		for($i=0; $i<=2; $i++) {
			$response = $this->base->transaction($sqlTrans);			
			if ($response['code'] == 200) {
				break;
			}
		}
    	$this->validaCode($response);
    }



	/*
	* Elimina uno o varios registro registro segun $where
	* $tabla = Nombre de la tabla
	* $where = Filtro de la consulta
	*/
    public function deleteData($tabla, $where){
        $response = $this->base->deleteRecord($tabla, $where);
        $this->validaCode($response);

    }

	/*
	* Paginación de una tabla
	* $tabla = Nombre de la tabla 
	* $pagina = Numero de la página
	* $por_pagina = No. de registros por página
	* $order_by = Ordenar los registros 
	*/
    public function pagina_table($tabla, $pagina, $por_pagina, $order_by){
        $response = $this->base->selectPage($tabla, $pagina, $por_pagina, $order_by);
        $this->validaCode($response);
    }

	/*
	* Valida el arreglo segun el Code
	* $response = arreglo a validar 
	*/
    public function returnData($response) {
        $this->validaCode($response);
    }



/*
* Encabezado de reporte
*
*/
public function headerReport($title)
{
    $order_by = array(array('campo' => 'public.sucursales.idsucursal', 'direccion' => 'asc'));
    $sucursales = $this->base->selectRecord("public.sucursales", "", "", "", "", "", "", $order_by, "", "", TRUE);
    $direc = "<table border='0' class='100p'>";
    $ban = true;
    $tit = " Rayón --- ";

    foreach ($sucursales as $value) {
        if ($this->session->userdata('esquema') == "ban.") {
            if ($ban == true) {
                $direc .= '<tr><td class="50p" align="center"><p>' . $value['domicilio'] . ' ' . $value['colonia'] . ' C.P. ' . $value['codpostal'] . ' ' . $value['municipio'] . ' <br>Tel. ' . $value['telefono1'] . '</p></td></tr>';
                $ban = false;
            }
        } else {
            $direc .= '<tr><td class="50p" align="center" style="padding: 3px">' . $value['domicilio'] . ', ' . $value['colonia'] . ', C.P. ' . $value['codpostal'] . ', ' . $value['municipio'] . ', Tel. ' . $value['telefono1'] . '</td></tr>';
        }
    }
    $direc .= '</tr></table>';
    $empresa = $this->getEmpresa();

    $html = '<!DOCTYPE html>
    <html lang="en">
    <head>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
            <title>Reporte</title>
            <style  type="text/css">
                @page {
                    margin: 1rem 1rem 1rem;
                }
                body {
                    color: #000;
                    background: #fff;
                    text-align: justify;
                    padding: 0px 40px;
                    font-family: Arial, Helvetica, sans-serif;
                }
                .titulo {
                    text-align: center;
                    padding:5px 0px;
                    font-size:18px;
                    font-weight: bold;
                    line-height: 50%;
                }
                .titulo-data {
                    line-height: 100%;
                }
                .subtitulo {
                    text-align: center;
                    padding:3px 0px;
                    font-size:12px;
                    font-weight: bold;
                }
                .text-space {
                    line-height: 150% !important;
                }
                .text-title {
                    padding: 0;
                    text-align: left;
                }
                .subtitle {
                    text-align: center;
                    padding:5px 0px;
                    font-size:12px;
                    font-weight: bold;
                }
                .seccion {
                    padding:5px 0px;
                    font-size:12px;
                    border: 0px solid black;
                }
                .seccion-right {
                    text-align: right;
                    padding:5px 0px;
                    font-size:12px;
                    font-weight: bold;
                }
                .seccion-left {
                    text-align: left;
                    padding:5px 0px;
                    font-size:12px;
                    border: 0px solid black;
                }
                .seccion-center {
                    text-align: center;
                    padding:5px 0px;
                    font-size:12px;
                }
                .logo {
                    font-size: 30px;
                }
                table, th, td {
                    border: 1px solid black;
                    border-collapse: collapse;
                }
                td {
                    text-align: justify;
                    padding-left: 5px;
                    padding-right: 5px;
                }
                table-coldes {
                    width: 70%;
                }
                table.100p {
                    width: 100%;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    font-size: 11px;
                    padding-left:40px;
                    color: #73879C;
                }
                .encatitulo {
                    background-color: #C0D5DA;
                    padding: 2px;
                    text-align: center;
                    font-family: Arial, Helvetica, sans-serif;
                }
                .page_break {
                    page-break-before: always;
                }
            </style>
    </head>
    <body>';
    $html .= '<div class="footer"><em>Impreso </em></div>';
    $html .= '<div class="titulo">' . $empresa . '</div>';
    $html .= $this->getSubtitulo();
    $html .= $this->getLogo(60);
    $html .= '<div class="titulo-data" style="font-size:10px;">';
    $html .= $direc;
    $html .= '   <p>' . $title . '</p>';
    $html .= '   <hr>';
    $html .= '</div>';
    return $html;
}



	public function getEmpresa(){
		switch ($this->session->userdata('esquema'))
		{
			case "fin.":
				$empresa ="FINCOMUNIDAD S.A. DE C.V., S.F.C." ;

				break;
			case "ban.":
				$empresa = "CENTRO DE DESARROLLO COMUNITARIO CENTÉOTL A.C.";
				//$empresa = "BANCOMUNIDAD, S.A. DE C.V. DE S.F.C.";
				break;
			case "ama.":
				$empresa = "Asociación  de Mujeres para la Autogestión, S.C. de R.L";
				break;
			case "imp.":
				$empresa = "IMPULSO";
				break;
		}
		//$empresa =$this->session->userdata('esquema') =="fin." ? "FINCOMUNIDAD, S.A. DE C.V. DE S.F.C." : "BANCOMUNIDAD, S.A. DE C.V. DE S.F.C.";
		return $empresa;	
	}

	public function getEmpresaCorto(){
		switch ($this->session->userdata('esquema'))
		{
			case "fin.":
				$empresa ="FINCOMUNIDAD" ;
				break;
			case "ban.":
				$empresa = "BANCOMUNIDAD";
				break;
			case "ama.":
				$empresa = "AMA";
				break;
			case "imp.":
				$empresa = "IMPULSO";
				break;
		}
		return $empresa;	
	}

	public function getEmpresaLargo(){
		switch ($this->session->userdata('esquema'))
		{
			case "fin.":
				$empresa ="FINCOMUNIDAD, Sociedad Anónima de Capital Variable, Sociedad Financiera Comunitaria" ;
				break;
			case "ban.":
				$empresa = "CENTRO DE DESARROLLO COMUNITARIO CENTÉOTL A.C.";			
			//	$empresa = "BANCOMUNIDAD, Sociedad Anónima de Capital Variable, Sociedad Financiera Comunitaria";
				break;
			case "ama.":
				$empresa = "Asociación  de Mujeres para la Autogestión, Sociedad Cooperativa de Responsabilidad Limitada";
				break;
			case "imp.":
				$empresa = "IMPULSO PRODUCTIVO";
				break;
		}
		//$empresa =$this->session->userdata('esquema') =="fin." ? "FINCOMUNIDAD, Sociedad Anónima de Capital Variable, Sociedad Financiera Comunitaria" : "BANCOMUNIDAD, Sociedad Anónima de Capital Variable, Sociedad Financiera Comunitaria";
		return $empresa;	
	}

	
	public function getDatosEmpresa(){
		$suc=$this->session->userdata('sucursal_id');
		$query ="select * from public.sucursales where idsucursal='".$suc."' ";
		$data = $this->base->querySelect($query, FALSE);
		return $data;
	}
	


/*
* Funcion crea de tabla
* en la generación de reporte
*/
	public function tableCreate($data) {
		$html='';

		foreach($data as $key => $value) {
			$html.='<table border="0" class="100p">';
			$html.='  <tr>';
			$i=0;
			foreach($value['datos'] as $keydata => $valuedata){
				$html.='<td style="text-align: center;" class="'. $value['size'][$i].'">'.$valuedata.'</td>';
				$i++;
			}
			$html.='  </tr>';
			$html.='  <tr>';

			$i=0;
			foreach($value['enca'] as $keyenca => $valueenca){
				$clase = $value['size'][$i] =="1p"?"":"linea-top";
				$html.='<td style="text-align: center;" class="'.$clase.'">'.$valueenca.'</td>';
				$i++;
			}
			$html.='  </tr>';
			$html.='</table>';
		}	
		return $html;
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
				// 07-09-2023 - (CORRECIÓN) VALIDACIÓN DE CONVERSIÓN A LETRAS DE CANTIDADES ENTRE 100,000 Y 100,999
				if ($num[1] == '0' && $num[2] == '0') {
					$t = ' cien';
				} else {
					$t = ' ciento' . $t;
				}
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


   function getFechaLetras($fecha){
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");	 
	$month = date_format($fecha,'m');
	$fechalet = date_format($fecha,'d').' de '.$meses[$month-1].' de '.date_format($fecha,'Y');

	return $fechalet;
}


	function getSubtitulo() {
		$subtitulo = '';
		if ($this->session->userdata('esquema' ) =="ban." ){
			$subtitulo = '<div class="subtitulo">PROGRAMA BANCOMUNIDAD</div>';
		}else {
			$subtitulo = '<div class="subtitulo"><br></div>';			
		}
		return $subtitulo;
	}
	

	function getLogo($LogoDimension) {
		$logo ='';
		/*if ($this->session->userdata('esquema' ) =="ban." ){
			$logo = '<div style ="top: -10px;position: absolute; float:right;"><img src="'.base_url("dist/img/logoban.png").'" height="'.$LogoDimension.'px" alt=""></div>';		
		}
		// Quita el logo a los esquemas impulso y ama 06-07-2023
		else if ($this->session->userdata('esquema' ) =="imp." || $this->session->userdata('esquema' ) =="ama.") {
			$logo = '';		
		}else {
			$logo = '<div style ="top: -10px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="'.$LogoDimension.'px" alt=""></div>';		
		}*/
		
		// ACTUALIZADO 19-08-2023 LOGO SOLO PARA FINCOMUNIDAD
		if ($this->session->userdata('esquema' ) =="fin."){
			$logo = '<div style ="top: -10px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="'.$LogoDimension.'px" alt=""></div>';
		}

		return $logo;
	}
	


function Print($nomov){
	//Obtener el nombre de la impresora
	//de acuerdo a la caja y usuario que esta conectada
	$this->load->model('../../caja/models/Base_ca','base_caja');
	$namePrint = $this->base_caja->getPrinterEquipo();		
	$enca =$this->getEmpresa()."\n";
	$enca .='SUCURSAL '.$this->session->userdata('nomsucursal')."\n";
	$fecha = date("Y-m-d");
	$idcaja = $this->session->userdata('idcaja');
	
	$query ="select * from ".$this->session->userdata('esquema')."get_movimientosdia('".$fecha."','".$fecha."','".$idcaja."','') where orden in ('B','C','D','E') and nomov =".$nomov." order by orden,cuenta,idacreditado";
	$data = $this->base->querySelect($query, FALSE);


//		$namePrint ="EPSON TM-T88V Receipt";
	try {

		$datos = $data['result'];
		$noelementos = count($datos);
		$cuenta =0;
		$nombre ='';


		// Enter the share name for your USB printer here
		$connector = new WindowsPrintConnector($namePrint);
		$printer = new Printer($connector);

		$printer -> setTextSize(1, 1);
		$printer -> setJustification(Printer::JUSTIFY_CENTER);
		$printer -> text($enca."\n");
		$printer -> setJustification(Printer::JUSTIFY_CENTER);
		$printer -> text(date("d-m-Y")."    ".$nomov."    ".date("h:i"));

		$tot = 0;
		foreach($datos as $key => $value){
			if ($value['orden'] !== 'C' ){
				if ($noelementos === 1){
					$printer -> text("\n\n");
					$printer -> setJustification(Printer::JUSTIFY_LEFT);
					$printer -> text('Socia   '.$value['idacreditado'].'  '.$value['nomacre']."\n");
					$printer -> text('Cuenta  '.$value['cuenta'].'  '.$value['nomcuenta']."\n");
					$printer -> text('Moneda  PESO MEXICANO'."\n");
					$printer -> text('Caja  '.$this->session->userdata('nocaja').'  '.$this->session->userdata('name_user_caja')."\n");
					$printer -> text("\n");
					$printer -> setJustification(Printer::JUSTIFY_CENTER);
					$printer -> text($value['nombre']."\n\n");
					$printer -> setJustification(Printer::JUSTIFY_RIGHT);
				}else {
					if ($cuenta ===0){
						$printer -> text("\n\n");
						$printer -> text('Caja  '.$this->session->userdata('nocaja').'  '.$this->session->userdata('name_user_caja')."\n");				
					}

					if ($nombre != $value['nombre']){
						$printer -> text("\n\n");
						$printer -> setJustification(Printer::JUSTIFY_CENTER);
						$printer -> text($value['nombre']."\n\n");
						$printer -> setJustification(Printer::JUSTIFY_RIGHT);	
					}
					$nombre = $value['nombre'];
				}
					
			}elseif ($tot === 0 && $value['orden'] === "C" ){
				if ($cuenta ===0){
					$printer -> text("\n\n");
					$printer -> setJustification(Printer::JUSTIFY_LEFT);
					$printer -> text('Caja  '.$this->session->userdata('nocaja').'  '.$this->session->userdata('name_user_caja')."\n");				
					$printer -> setJustification(Printer::JUSTIFY_CENTER);
					$printer -> text($value['nombre']."\n\n");
					$printer -> setJustification(Printer::JUSTIFY_RIGHT);
				}
				$cuenta = $cuenta + 1;
			}

			//CREDITO
			$body ="";
			if ($value['orden'] === 'C'){
				$printer -> setJustification(Printer::JUSTIFY_LEFT);				
				$body = 'Socia '.$value['idacreditado'].'  '.$value['nomacre']."\n";
				$printer -> setJustification(Printer::JUSTIFY_RIGHT);
				$body .= 'Importe '.number_format($value['importe'], 2, '.', ',')."\n";
				$tot = floatval($tot)  + floatval($value['importe']);
			}else {
				if ($noelementos === 1){
					$body ="Importe  ".number_format($value['importe'], 2, '.', ',')."\n";
					$body.="---------------------\n";
					$body.="Total    ".number_format($value['importe'], 2, '.', ',')."\n\n";
				}else {
					$printer -> setJustification(Printer::JUSTIFY_LEFT);				
					$body = 'Socia '.$value['idacreditado'].'  '.$value['nomacre']."\n";
					$printer -> setJustification(Printer::JUSTIFY_RIGHT);
					$body .= 'Importe '.number_format($value['importe'], 2, '.', ',')."\n";
					$tot = floatval($tot)  + floatval($value['importe']);
				}
			}
			$printer -> text($body);
			if ($value['orden'] === 'C'){
			}else{
				if ($noelementos === 1){				
					$printer -> text("\n\n");
					$printer -> setJustification(Printer::JUSTIFY_CENTER);
					$printer -> text("______________________"."\n");
					$printer -> text("Firma"."\n");
				}
			}

		}
		if ($tot !=0){
			$printer -> setJustification(Printer::JUSTIFY_RIGHT);			
			$printer -> text("---------------------\n");
			$printer -> text("Total Pago  ".number_format($tot, 2, '.', ',')."\n\n");			
		}

		$printer -> cut();						
		$printer -> close();

		return ". Enviado a impresora";

	} catch (Exception $e) {
		
		return  "Error al enviar a la impresora : " . $e -> getMessage();
	}




	}


	function validateAutUser($user, $password) {
		$response = array("status"=>"ERROR",
			"code" => "404",			 
			"message"=> "Contraseña inválida!",
			"newtoken"=>$this->security->get_csrf_hash()
		);
		if (!$this->ion_auth->logged_in()) {
		}else {
		    $option = "10031";
			if ($this->ion_auth->loginValidate($user, $password, $option, FALSE))
			{
			  $response = array("status"=>"OK",
					"code" => "200",
					"message"=> $this->ion_auth->messages(),
					"newtoken"=> $this->security->get_csrf_hash()
				);
			}	
		}
		return $response;

	 }

}