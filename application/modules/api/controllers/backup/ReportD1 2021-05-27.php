<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: text/html; charset=UTF-8');

require_once(APPPATH.'/modules/api/controllers/CarteraD1.php');
require_once(APPPATH.'/third_party/dompdf/dompdf_config.inc.php');

class ReportD1 extends CarteraD1 {
	public function __construct()
    {
		parent::__construct();
//		if(!$this->ion_auth->logged_in())
//		{
//			redirect('auth','refresh');
//		}
		$this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');

	}



	public function pdf_solicitud_credito_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "edocivil_nombre", "sexo", "idactividad", "actividad_nombre", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$idgrupo=$cred['idgrupo'];
		
		$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
		$where = array("idgrupo"=>$idgrupo);
		$gpo = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
		$gpo = $gpo[0];

		$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid"=>$cred['idaval1']);
		$aval = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
		$aval = $aval[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);


		$header = $this->headerReport('SOLICITUD DE CREDITO');
		$html = $header.'
			<div style="font-size:11px;">
			<h4>1. DATOS GENERALES DE LA SOLICITANTE</h4>';
        $html.='<br>
			<table style="width:100%">
				<tr class="seccion">
					<td colspan="3">Fecha: '.date_format($fecha,'d/m/Y').'</td>
					<td>No. Socia: '.$cred['idacreditado'].'</td>
				</tr>
				<tr class="seccion">
					<td colspan="4">Nombre completo: '.$cred['nombre'].'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="4">Domicilio: '.$cred['direccion'].'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="2">Ocupacion: '.$cred['actividad_nombre'].'</td>
					<td colspan="2">Estado civil: '.$cred['edocivil_nombre'].' </td>
				</tr>						
				<tr class="seccion">
					<td colspan="4">Nombre de la colmena: '.$gpo['colmena_nombre'].'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="2">No. colmena: '.$gpo['colmena_numero'].'</td>
					<td colspan="2">Grupo: '.$gpo['colmena_grupo'].'</td>
				</tr>						
			</table>';
		$html.='<br> <br> <br>
			<h4>2. DATOS DEL PROYECTO</h4>';			
        $html.='<br>
			<table style="width:100%">
				<tr class="seccion">
					<td>Titulo del proyecto: '.$cred['proy_nombre'].'</td>
				</tr>
				<tr class="seccion">
					<td>Monto del credito '.number_format($monto, 2, '.', ',').'</td>
				</tr>						
				<tr class="seccion">
					<td>Lugar donde se realizará el proyecto: '.$cred['proy_lugar'].'</td>
				</tr>						
				<tr class="seccion">
					<td>Nivel: '.$cred['nivel'].'</td>
				</tr>						
				<tr>
					<td  class="seccion-center">Descripción del proyecto:</td>
				</tr>						
				<tr> 
					<td>'.$cred['proy_descri'].'  <br><br>&nbsp;</td> 
				</tr>											
			</table>';						
        $html.='<br> <br> <br> <br> <br>
			<table style="width:100%" border="0" >
				<tr >
					<td align="center" width="25%">'.$cred['nombre'].'</td>
					<td></td>
					<td align="center" width="25%"></td>
					<td></td>
					<td align="center" width="25%">'.$aval['nombre'].'</td>
				</tr>				
				<tr >
					<td style="border-top: 1px solid" align="center" width="25%">Socia</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Promotor</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">'.$aval['cargo'].'</td>
				</tr>				
			</table>';			
		//$html.=$this->tableCreate($data1);
		$html.='<br> <br> <br>
			<h4>3. VERIFICACION DEL CREDITO</h4>
			<div class="seccion-center"> OBSERVACIONES </div>';
        $html.='<br>
			<table style="width:100%" >
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> <br>&nbsp; </td>
				</tr>				
			</table>';		
        $html.='<br> <br> <br> <br> 
			<table style="width:100%"  border="0">
				<tr >
					<td></td>
					<td align="center" width="25%">'.$aval['nombre'].'</td>
					<td></td>
					<td align="center" width="25%"></td>
					<td></td>
				</tr>				
				<tr >
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">'.$aval['cargo'].'</td>
					<td></td>
					<td style="border-top: 1px solid"  align="center" width="25%">Promotor</td>
					<td></td>
				</tr>		
			</table>';				
		$html.='
		</div>
		</body>
		</html>
		';

        ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html(($html));
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}


	//2019-04-09 Integración credito individual
	public function pdf_solicitud_credito_ind_get(){
		$idcredito = $this->uri->segment(4);
		//$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "edocivil_nombre", "sexo", "idactividad", "actividad_nombre", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "iva");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$idgrupo=$cred['idgrupo'];
		if ($idgrupo==="0"){
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
			$aval_nombre = "";
			$aval_cargo = "";
		}else{
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
			$where = array("idgrupo"=>$idgrupo);
			$gpo = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
			$gpo = $gpo[0];
			$col_nombre = $gpo['colmena_nombre'];
			$col_numero = $gpo['colmena_numero'];
			$col_grupo = $gpo['colmena_grupo'];

			$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
			$where = array("acreditadoid"=>$cred['idaval1']);
			$aval = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
			$aval = $aval[0];
			$aval_nombre = $aval['nombre'];
			$aval_cargo = $aval['cargo'];			
		}
		
		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);
		$header = $this->headerReport('SOLICITUD DE CREDITO');
		$html = $header.'
			<div style="font-size:11px;">
			<h4>1. DATOS GENERALES DE LA SOLICITANTE</h4>';
        $html.='<br>
			<table style="width:100%">
				<tr class="seccion">
					<td colspan="3">Fecha: '.date_format($fecha,'d/m/Y').'</td>
					<td>No. Socia: '.$cred['idacreditado'].'</td>
				</tr>
				<tr class="seccion">
					<td colspan="4">Nombre completo: '.$cred['nombre'].'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="4">Domicilio: '.$cred['direccion'].'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="2">Ocupacion: '.$cred['actividad_nombre'].'</td>
					<td colspan="2">Estado civil: '.$cred['edocivil_nombre'].' </td>
				</tr>						
				<tr class="seccion">
					<td colspan="4">Nombre de la colmena: '.$col_nombre.'</td>
				</tr>						
				<tr class="seccion">
					<td colspan="2">No. colmena: '.$col_numero.'</td>
					<td colspan="2">Grupo: '.$col_grupo.'</td>
				</tr>						
			</table>';
		$html.='<br> <br> <br>
			<h4>2. DATOS DEL PROYECTO</h4>';			
        $html.='<br>
			<table style="width:100%">
				<tr class="seccion">
					<td>Titulo del proyecto: '.$cred['proy_nombre'].'</td>
				</tr>
				<tr class="seccion">
					<td>Monto del credito '.number_format($monto, 2, '.', ',').'</td>
				</tr>						
				<tr class="seccion">
					<td>Lugar donde se realizará el proyecto: '.$cred['proy_lugar'].'</td>
				</tr>						
				<tr class="seccion">
					<td>Nivel: '.$cred['nivel'].'</td>
				</tr>						
				<tr>
					<td  class="seccion-center">Descripción del proyecto:</td>
				</tr>						
				<tr> 
					<td>'.$cred['proy_descri'].'  <br><br>&nbsp;</td> 
				</tr>											
			</table>';						
        $html.='<br> <br> <br> <br> <br>
			<table style="width:100%" border="0" >
				<tr >
					<td align="center" width="25%">'.$cred['nombre'].'</td>
					<td></td>
					<td align="center" width="25%"></td>
					<td></td>
					<td align="center" width="25%">'.$aval_nombre.'</td>
				</tr>				
				<tr >
					<td style="border-top: 1px solid" align="center" width="25%">Socia</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Promotor</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">'.$aval_cargo.'</td>
				</tr>				
			</table>';			
		//$html.=$this->tableCreate($data1);
		$html.='<br> <br> <br>
			<h4>3. VERIFICACION DEL CREDITO</h4>
			<div class="seccion-center"> OBSERVACIONES </div>';
        $html.='<br>
			<table style="width:100%" >
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> </td>
				</tr>				
				<tr class="seccion">
					<td> <br>&nbsp; </td>
					<td> <br>&nbsp; </td>
				</tr>				
			</table>';		
        $html.='<br> <br> <br> <br> 
			<table style="width:100%"  border="0">
				<tr >
					<td></td>
					<td align="center" width="25%">'.$aval_nombre.'</td>
					<td></td>
					<td align="center" width="25%"></td>
					<td></td>
				</tr>				
				<tr >
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">'.$aval_cargo.'</td>
					<td></td>
					<td style="border-top: 1px solid"  align="center" width="25%">Promotor</td>
					<td></td>
				</tr>		
			</table>';				
		$html.='
		</div>
		</body>
		</html>
		';
        ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html(($html));
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}

	
	public function tableCheckList($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		foreach($data as $key => $value) {
			$html.='  <tr>';
			$html.='  <td>'.$value['documento'].'</td>';
			$html.='  <td>'.$value['fecha'].'</td>';
			/*
			foreach($value as $keydata => $valuedata)
				$html.='<td>'.$valuedata.'</td>';
			}
			*/
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}


	public function pdf_checklist_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "grupo", "documento", "requerido", "fecha");
		$where = array("idcredito"=>$idcredito);
		$list = $this->base->selectRecord($this->session->userdata('esquema')."v_check_list", $fields, "", $where, "","", "", "", "","", TRUE);

		$title = array("Documento","Fecha cumplimiento");
		$tabla = '';
		$tabla.= $this->tableCheckList($title, $list);

		$header = $this->headerReport('');
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CHECK LIST</h3>';

		$html.='<div>'.$tabla.'</div>';

        $html.='<br> <br> <br>
			<table style="width:100%" border="0" >
				<tr >
					<td style="border-top: 1px solid" align="center" width="25%">ELABORO</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">SUPERVISO</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">REVISO</td>
				</tr>				
			</table>';			


		$html.='
		</div>
		</body>
		</html>
		';


		ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));

	}

	/*
	public function getEmpresa(){
		$empresa =$this->session->userdata('esquema') =="fin." ? "FINCOMUNIDAD, S.A. DE C.V. DE S.F.C." : "BANCOMUNIDAD, S.A. DE C.V. DE S.F.C.";
		return $empresa;	
	}
	*/

	//2019-04-09 Integracion credito individual
	//2019-04-29 Correccion en Tasas y direccion
	public function pdf_contrato_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","idaval1", "idaval2", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$idPersona = $cred['idpersona'];
		$monto = $cred['monto'];
		$nombre = $cred['nombre'];
		$domicilio = $cred['direccion'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);
		//$fechaText = $this->getFechaToText($fecha);


		$fields = array("estado", "municipio", "colonia", "direccion1");
		$where = array("idpersona"=>$idPersona);
		$domi = $this->base->selectRecord("public.v_persona_domicilio", $fields, "", $where, "","", "", "", "","", TRUE);
		$domi = $domi[0];
		$estado = $domi['estado'];
		$municipio = $domi['municipio'];
		$colonia = $domi['colonia'];

		$fields = array("numero", "fecha_vence", "capital", "interes", "iva", "aportesol", "garantia", "total");
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Semana","Vencimiento","Saldo Capital", "Interes", "IVA", "Capital", "Cuota");

		$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid"=>$cred['idaval1']);
		$aval1 = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
		if ($aval1) {
			$aval1_nombre = $aval1[0]['nombre'];
		}else{
			$aval1_nombre = "";
		}

		$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid"=>$cred['idaval2']);
		$aval2 = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
		if ($aval2) {
			$aval2_nombre = $aval2[0]['nombre'];
		}else{
			$aval2_nombre = "";
		}


		$nivel = $this->base->querySelect("SELECT ssi_tasa, dias, round((ssi_tasa/12),2) as tasa_mes, round((ssi_tasa/12),2)*2 as mes_mora, numero_pagos 
			FROM public.niveles 
			WHERE nivel=".$cred['nivel']." and fecha_inicio <= '".$cred['fecha_entrega_col']."'::date ORDER BY fecha_inicio desc LIMIT 1", TRUE);
		$nivel= $nivel[0];		

		$cred_ven = $this->base->querySelect("SELECT fecha_vence 
			FROM fin.amortizaciones WHERE idcredito=".$idcredito." ORDER BY fecha_vence DESC limit 1", TRUE);
		$cred_ven= $cred_ven[0];
		$fecha_ven = new DateTime($cred_ven['fecha_vence']);
		/*
		print_r($cred_ven['fecha_vence']);
		die;
		*/

		
		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CONTRATO DE CREDITO</h3>';		
        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr class="seccion">
					<th align="left">
						SUCURSAL: '.$sucursal.'
					</th>
					<th class="seccion-right">
						FECHA: '.date_format($fecha,'d/m/Y').'
					</th>
				</tr>
				<tr class="seccion">
					<th align="left">
						Contrato No.: '.$idcredito.'
					</th>		
				</tr>				
			</table>';
		$html.='<div > </div>';
		$html.='<font size="12px">';
		$html.='<br>';
		$html.='<font size="12px">';
		$html.='<br>
		<p>Contrato de crédito que celebran por una parte la '.$nombre.' a quien en lo sucesivo se le 
			denominará ACREDITADO, y '.$empresa.' que en lo sucesivo se denominará el ACREDITANTE al 
			tenor de las siguientes cláusulas y declaraciones:</p>

		<p align="center"><b>DECLARACIONES</b></p>
		<p><b>I.- ACREDITANTE por conducto del representante:</b></p>
		<ul type="a">
			<li> Que es una sociedad anónima debidamente constituida conforme a las leyes mexicanas, según consta en el testimonio notarial número 1305, de fecha 26 de mayo de 2014, otorgado ante la fe del Lic. JOSE JORGE ENRIQUE ZARATE RAMIREZ, Notario Público número OCHENTA Y CUATRO, del Estado de Oaxaca.</li>
			<li> Que cuenta con poderes suficientes para obligar a su representada en los términos y condiciones de este contrato los cuales no le han sido revocados ni limitados.</li>
			<li> Que tiene como objeto social, entre otros otorgar servicios y productos financieros.</li>
			<li> Que su domicilio se ubica en Rayón #704, Barrio San Antonio, Zimatlán de Álvarez, Oaxaca de Juárez, Oaxaca. C.P. 71200.</li>					
		</ul>
		<p><b>II.- ACREDITADO por su propio derecho:</b></p>
		<ul type="a">
			<li> Que es una persona física de nacionalidad mexicana, mayor de edad, en pleno uso y goce de sus facultades para la celebración del presente contrato de crédito.</li>
			<li> Que su domicilio se ubica en '.$domicilio.' Que es socio de la ACREDITANTE.</li>
			<li> Que es su deseo celebrar el presente contrato.</li>
		</ul>
		
		<p><b>III.- Del (los) AVAL(es).</b></p>
		<ul type="a" text-align= "justify">
			<li>Que es (son): <br> 
				'.$aval1_nombre.'
				<br>
				'.$aval2_nombre.'
				<br>
				Persona(s) física(s) de nacionalidad mexicana y que darán cumplimiento del presente contrato.</li>
			<li class="stretch"> Que en virtud de las relaciones personales y jurídicas que tiene(n) con el ACREDITADO, es de su interés hacer con el presente contrato el objeto de obligarse conjunta y solidariamente con éste último frente al ACREDITANTE, en el cumplimiento de todas las obligaciones establecidas en el presente contrato a su cargo y, por tanto, es su intención constituirse como obligado(s) solidario(s), así como suscribir el o los pagarés con que se documente(n) la(s) disposición(es) del crédito objeto del presente contrato en su carácter de avalista(s) y garante(s).</li>
			<li> Que su(s) domicilio(s) respectivamente, se ubica(n) en: </li>
				<br>
				<br>
				'.$domi['direccion1'].', Colonia'.$colonia.', Municipio '.$municipio.', en el Estado de '.$estado.' 
				<br>
				<br>
			<li> Que es su voluntad comparecer en la celebración del presente contrato con el carácter de aval(es).
			<li> Que en caso de no cumplir el ACREDITADO con alguna obligación de pago, me(o nos) comprometemos al pago del capital más intereses devengados en un plazo de cinco días contados a partir de la fecha en que se haga obligatorio el pago de crédito.
		</ul>			


		<p align="center"><b>CLAUSULAS</b></p>
		<br>
		<p>Primera.- Importe. La ACREDITANTE entrega al ACREDITADO en la fecha de firma del presente contrato la cantidad de $'.number_format($monto, 2, '.', ',').' ('.strtoupper($monto_letra).'). El acreditado se obliga a pagar el crédito de conformidad con los pagos fijados en la cláusula tercera.</p>
		<p>Segunda.- Vigencia. La vigencia de este contrato es de '.$nivel['dias'].' días contados a partir de la fecha del presente contrato, por lo que concluirá precisamente el día '.$this->getFechaLetras($fecha_ven).'.
		No obstante su terminación de este contrato producirá todos sus efectos legales, hasta que el ACREDITADO haya liquidado en su totalidad el importe del crédito más sus accesorios a la ACREDITANTE.</p>
		<p>Tercera.- Plan de Pagos. Los plazos y montos a pagarse se regirán mediante la Tabla de Amortización Anexa.</p>
		<p>Cuarta.- Intereses Ordinarios. El ACREDITADO se obliga a pagar a la ACREDITANTE, durante la vigencia del presente contrato, intereses ordinarios sobre capital insoluto del crédito y se calcularan a una tasa mensual del '.$nivel['tasa_mes'].'%.</p>
		El ACREDITADO pagará intereses ordinarios a partir de la fecha en que se disponga del crédito conforme a lo establecido en este contrato, hasta que cubra el importe total del crédito.
		<p>Quinta.- Intereses Moratorios. En caso de que el ACREDITADO no pague puntualmente en la fechas de pago se obliga a pagar como pena convencional una tasa de interés moratoria mensual de '.$nivel['mes_mora'].' % aplicada a cada mes o fracción de retraso. La tasa quedara como única en el periodo de mora.</p>
		<p>Sexta.- Leyes y Tribunales. Este contrato se rige de acuerdo a las Leyes del Estado de Oaxaca. El domicilio para dirimir cualquier controversia respecto al presente contrato, así como su interpretación legal, se sujetaran a jurisdicción y territorio del domicilio de la ACREDITANTE o la que esta elija, renunciando el ACREDITADO y el (los) AVAL(es) a cualquier jurisdicción que le corresponda de conformidad con la ley que rija en su domicilio.</p>
		<p>Séptima.- Garantías. De las garantías otorgadas por el ACREDITADO y/o el o los AVAL(es) se describen y enumeran las siguiente(s):</p>
		

		<p>Octava.- Disposición para compensación. A fin de garantizar la total recuperación del crédito, el ACREDITADO expresamente a la ACREDITANTE, para que en caso de que el ACREDITADO aplique los haberes que tenga depositados con la ACREDITANTE, cuentas de ahorro a la vista o a plazo o cualquier otra para cubrir el adeudo pendiente de pago a la fecha de aplicación. Lo anterior sin necesidad de requerimiento, demanda alguna.
		De darse el supuesto anterior, las partes expresamente pactan que la aplicación de los recursos depositados en las referidas cuentas se aplique al pago del crédito de la forma siguiente: intereses moratorios, intereses ordinarios y, si sobrase cantidad alguna, esta se aplicará al pago del capital hasta donde alcance.
		Una vez aplicados los recursos, la ACREDITANTE notificara al ACREDITADO el monto abonado por la deuda.</p>
		<p>Novena.- Causas de Vencimiento. El plazo para el pago del crédito y sus accesorios anticipadamente en caso de que acontezca cualquiera de las siguientes causas:
		<ul type="1">
		<li> Si el ACREDITADO no paga puntual e íntegramente alguna amortización de capital vencido devengado, que se causen en virtud del presente instrumento y en relación con el crédito (cada uno constituirá una “Causa de Vencimiento Anticipado”).</li>
		<li> Si el ACREDITADO faltare al cumplimiento de cualquiera de las obligaciones establecidas en el presente contrato.</li>
		<li> Que el (los) AVAL (es) no cumplieran con su obligación de garantes o deudores solidarios con declaratorias.</li>
		En caso de ocurrir alguna de las causas de vencimiento anticipado previstas, la ACREDITANTE podrá exigir el total del crédito y sus accesorios, y el ACREDITADO deberá pagar a la ACREDITANTE de manera inmediata el importe total de dicho crédito y todas las demás sumas que se adeuden bajo el presente contrato, caso en el haya suscrito el ACREDITADO y serán pagados de inmediato; en caso contrario el ACREDITADO se obliga a pagar intereses moratorios conforme a lo pactado en el presente instrumento.</p>
		<p>Decima.- El ACREDITADO se obliga a informar de manera ineludible a la ACREDITANTE su cambio de domicilio, dentro de los cinco días naturales, para que dicho cambio se verifique.</p>
				

		<p>Este contrato se firma el  '.$this->getFechaLetras($fecha).', en ZIMATLAN DE ALVAREZ, OAXACA</p>
		<br>
		<br>
		<br>
		<table style="width:100%" border=0>
		
			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%">'.$empresa.'</td>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%"><b>'.$nombre.'</b></td>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%">'.$aval1_nombre.'</td>
				<td></td>
			</tr>		
			<tr>
				<td></td>
				<td align="center" width="30%">LA ACREDITANTE</td>
				<td></td>
				<td align="center" width="30%">EL ACREDITADO</td>
				<td></td>
				<td align="center" width="30%">EL AVAL</td>
				<td></td>
			</tr>			
		</table>	
		';
		if ($cred['nivel']>=15){
			$html.='<br><br><br><br>
				<table style="width:100%" border=0>
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%">'.$aval2_nombre.'</td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td align="center" width="30%">EL AVAL</td>
					<td></td>
				</tr>			
			</table>	
			';
			
		}
		$html.='
		</div>
		</body>
		</html>
		';

//		print_r($html);
//die();		
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html(( $html));
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));

	}

	
	//Integración credito individual
	public function pdf_ahorro_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		
		$ahorro = $this->base->querySelect("SELECT a.fecha_alta, m.fecha
			FROM ".$this->esquema."ahorros as a JOIN ".$this->esquema."ahorros_mov as m ON a.idahorro = m.idahorro
			WHERE idproducto in ('01', '02') and idacreditado=".$idcredito."
			ORDER BY fecha limit 1", TRUE);
		$ahorro= $ahorro[0];
		$fecha = new DateTime($ahorro['fecha']);
		

		$nombre = $cred['nombre'];
		$idsocio = $cred['idacreditado'];
		$direccion = $cred['direccion'];
		$sucursal ='ZIMATLÁN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		//$fecha = new DateTime($cred['fecha_entrega_col']);

		$header =  $this->headerReport('CONTRATO');
		$html = $header;
		$html.='<font size="12px">
		<p>CONTRATO DE DEPÓSITO DE DINERO PARA OPERACIONES EN CUENTA DE AHORROS QUE CELEBRAN, POR UNA PARTE, '.$nombre.', A QUIEN EN LO SUCESIVO SE LE DESIGNARÁ COMO “EL SOCIO”, 
			Y POR OTRA PARTE “'.$empresa.'” REPRESENTADA EN ESTE ACTO POR EL C. ADRIANA COINTA GERONIMO DIAZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL A QUIEN EN LO SUCESIVO SE LLAMARÁ 
			“LA SOCIEDAD”, DE CONFORMIDAD CON LAS SIGUIENTES:</p>
		<p align="justify"><b>CLAUSULAS</b></p>
		<div align="justify">
		<p align="justify">PRIMERA. El objeto del presente Contrato de Depósito de Dinero para operaciones a la vista en cuenta de ahorros es establecer los lineamientos en virtud de 
			los cuales “'.$empresa.'” ofrecerá al Socio el servicio de depósito a la vista. El Socio podrá consignar, a través de los mecanismos que más adelante se establecen, diversas 
			cantidades de dinero que de conformidad con lo establecido en la fracción I del Artículo 32 de la Ley de Ahorro y Crédito Popular, serán recibidas por '.$empresa.' en calidad 
			de depósito de  ahorro. El Socio podrá llevar a cabo en días y horas hábiles para '.$empresa.', las operaciones que se enlistan a continuación (en lo sucesivo denominadas como “Los Servicios”):</p>
		<ul type="a">
			<li> Efectuar depósitos de dinero a la vista.</li>
			<li> Efectuar retiros de dinero, con cargo a su Saldo o Saldos Disponibles.</li>
			<li> Solicitar consultas de sus movimientos o Saldo Disponible en las sucursales y oficinas de la Sociedad.</li>
			<li> Solicitar traspasos a Operaciones de Inversión; y,</li>
			<li> Aquellos servicios adicionales que la Sociedad establezca en beneficio de El Socio.</li>
		</ul>
		<p>SEGUNDA. El Socio tiene acceso a Los Servicios en virtud de la aportación de capital que ha sido realizada en La Sociedad.</p>
		
		<p>TERCERA.  Todos  Los  Servicios  que  ejecute  El  Socio  se  realizarán  sólo  con  su  número  de  Socio  que  funcionará  como  cuenta universal,  sobre  la  cual  podrán  asignarse  tantas  cuentas  a  la  vista  como  El  Socio  desee  aperturar   (en  lo  sucesivo  denominada como “Número de Socio”).</p>
		<p>CUARTA. Todos  los  accesos  a  Los  Servicios que  se  ofrecen  en  virtud  de  este  Contrato,  se  harán  por  instrucciones expresas  de  El Socio,  entregara  en  las  oficinas  de  la  Sociedad,  mediante  el  envío  de  un  fax,  o  a  través  de  un  correo  electrónico,  o  mediante instrucciones telefónicas, previa identificación de la Sociedad, mediante su Número en la Sociedad y su número de cuenta.
		De  la  misma  manera  con  su  Número  de  Socio,  El  Socio  podrá  indicar,  ahora  o  en  cualquier  tiempo,  el  número  de  las  cuentas  en Intermediarios    Financieros    debidamente    autorizados    conforme    a    los    ordenamientos    legales    aplicables    (en    lo    sucesivo    “La Sociedad”),   a   las   cuales   se   podrán   efectuar   depósitos   sobre   Saldos   Disponibles   de   dinero   a   la   vista,   dejando   en   todo   caso constancia escrita de esta autorización.</p>
		<p>QUINTA.  Para  efectos  de  este  Contrato,  se  entenderá  por  Saldo  Disponible,  la  suma  de  los  depósitos,  más  los  intereses  que devenguen  los  mismos,  menos  los  retiros,  las  comisiones,  los  gastos  que  se  generen  y  los  impuestos  retenidos  que  se  causen  en cada una de las cuentas (en lo sucesivo “Saldos Disponibles”).</p>		
		<p>SEXTA.   El   Socio   podrá   autorizar   a   una   o   varias   personas   para   que   en   su   nombre   y   representación, con el carácter de mandatarios,  hagan  uso  de  Los  Servicios  que  fueran  necesarios  en  sus  números  de  cuenta  con  su  Número  de  Socio  (en  lo sucesivo “Las Personas Autorizadas”).</p>
		
		<p>SÉPTIMA.  El  Socio  se  hace  responsable  del  buen  uso  que  se  dé  a  su  Número  de  Socio,  y  al  número  de  las  cuentas  que  la 
		Sociedad,  le  proporcione,  por  lo  cual,  serán  responsabilidad  de  El  Socio  cualesquiera  operaciones  que  se  autoricen  al  amparo  
		de estas claves por El Socio o por Las Personas Autorizadas.</p>
		
		<p>OCTAVA.   La   Sociedad   podrá   determinar   libremente   los   montos   mínimos   a   partir   de   los   cuales   esté   dispuesto   a   
		recibir   y documentar depósitos de dinero a la vista, y los importes a partir de los cuales se devengarán intereses a favor de El Socio.</p>
		
		<p>NOVENA.  Los  depósitos  realizados  por  El  Socio  definidos  en  Los  Servicios  habrán  de  ser  realizados  en  Moneda  Nacional  y  
		la Sociedad,  restituirá  las  sumas  prestadas  en  la  misma  moneda,  conforme  a  la  Ley  Monetaria  vigente  en  la  República  Mexicana  
		al momento  de  hacerse  el  pago,  devolviendo  una  cantidad  igual  a  la  recibida,  más  los  intereses  generados  en  su  caso,  menos  las comisiones,   gastos,   retenciones   de   impuestos   u   otras   erogaciones   que   se   hubieren   realizado.   La   Sociedad,   podrá   realizar   los pagos  mediante  efectivo,  cheque  nominativo  o  depósito  en  cuenta  en  cualquier  Institución  Sociedad  autorizada  por  El  Socio  de conformidad con los acuerdos establecidos en el presente contrato.		
		En  caso  de  que  El  Socio  desee  que  los  pagos  se  realicen  a  través  de  depósito  en  alguna  cuenta  girada  a  favor  de  El  Socio  en alguna  Institución,  las  partes  están  de  acuerdo  que  dicho  depósito  sea  realizado  por  la  Sociedad,   en   alguna   de   las   cuentas referidas en la Relación de Bancos y Números de Cuenta del Socio.</p>
		 
		<p>DÉCIMA.  La  Sociedad,  únicamente  recibirá  en  depósito  las  cantidades  que  se  entreguen  en  los  días  y  horas  laborales  en  las oficinas  de  la  Sociedad  o  a  través  de  sus  sucursales,  o  cuando  El  Socio  deposite  en  las  cuentas  de  cheques  que  al  efecto  la Sociedad,  le  indique,  y  de  las  cuáles  la  Sociedad  es  titular.  En  caso  de  que  El  Socio  desee  girar  cheques  a  nombre  de  la Sociedad,  para  depósito  en  las  cuentas  a  nombre  de  El  Socio,   éste  deberá  de  ser  expedido  a  nombre  de  '.$empresa.' En  ningún  caso,  la  Sociedad  será  responsable  sobre  depósitos  que  El  Socio  o  Las  Personas  Autorizadas  realicen  en una cuenta donde la Sociedad, no sea el titular.</p>
		<BR>
		<BR>
		<p>DÉCIMA  PRIMERA.     La  Sociedad,  abonará  a  las  cuentas  a  nombre  de  El  Socio,  los  depósitos  que  el  mismo  realice  en  días hábiles  de  lunes  a  viernes,  de  9:00 a  16:00 hrs  en  la  misma  fecha  del  depósito.  Los  depósitos  realizados  con  posterioridad  a  esa hora se entenderán como realizados en el día hábil siguiente.</p>
		
		<p>DÉCIMA  SEGUNDA.  Las   sumas   que   El   Socio   mantenga  depositadas  en   sus   cuentas,   generarán   intereses  que   se   calcularán aplicando  la  tasa  de  interés  vigente  que  para  tales  efectos  la  Sociedad,  determine,  y  serán  acreditados  en  los  propios  números  de cuenta de El Socio por mensualidades vencidas.</p>
		
		<p>DÉCIMA  TERCERA.  Los  porcentajes  de  interés  se  aplicarán  de  acuerdo  al  rango  en  que  se  encuentre  el  saldo  promedio  diario mensual  que  El  Socio  mantenga  durante  el  periodo  correspondiente.  Los  intereses  se  calcularán  dividiendo  la  suma  del  Saldo Disponible diario  del  mes  correspondiente entre  el  número  de  días  de  dicho  mes,  valor  que  será  multiplicado por  el  resultado  de  la división  de  la  tasa  de  interés  anual  convenida,  entre  360 y  finalmente  ese  valor  será  multiplicado  por  el  número  de  días  del  mes correspondiente.</p>
		
		<p>DÉCIMA  CUARTA.  El  monto  de  los  intereses,  la  tasa  y  los  saldos  correspondientes,  serán  dados  a  conocer  en  el  documento impreso  donde  se  haga  constar  la  operación,  o  en  el  estado  de  cuenta  respectivo,  donde  podrán  ser  revisados  por  El  Socio.  La Sociedad,  se  reserva  el  derecho  de  revisar  y  ajustar  la  tasa  de  interés  y  los  rangos  para  los  saldos  promedio  diarios.  La  tasa  de interés  que  la  Sociedad,  se  dará  a  conocer  para  operaciones  de  la  misma  clase,  mediante  la  página  electrónica  que  la  Sociedad, tenga  disponible  en  Internet,  en  carteles,  tableros  o  pizarrones  visibles  de  manera  destacada  en  los  lugares  abiertos  al  público  en las oficinas de la Sociedad.</p>
		
		<p>DÉCIMA  QUINTA.  Los  intereses  se  generarán  a  partir  del  día  en  que  los  depósitos  de  El  Socio  se  abonen  en  su  Cuenta  y  hasta  el día  anterior  a  aquel  en  el  que  se  efectúen  los  retiros.  Los  intereses  se  calcularán  mensualmente  conforme  a  lo  establecido  en  las cláusulas anteriores, y  pasarán  a  formar  parte  del  Saldo  Disponible de  cada  cuenta  al  primer  día  hábil  del  mes  siguiente  al  que  se calculen.</p>
		
		<p>DÉCIMA  SEXTA.  Para  el  retiro  y  entrega  del  Saldo  Disponible,  las  partes  acuerdan  que  el  retiro  solicitado  se  realice  mediante  la entrega   de   dinero   en   efectivo,   única   y   exclusivamente   tratándose   de   cantidades   inferiores   a   una   suma   equivalente   a   200 (Doscientos) salarios  mínimos  diarios  generales  vigentes  para  el  Distrito  Federal,  en  uno  o  varios  movimientos  el  mismo  día;  o  que se  realice  mediante  cheque  nominativo  a  favor  de  El  Socio,  de  alguna  de  Las  Personas  Autorizadas  o  de  un  tercero;  o  en  su  caso mediante  el  depósito  de  los  recursos  en  la  cuenta  bancaria  a  nombre  de  El  Socio,  de  alguna  de  Las  Personas  Autorizadas  o  de  un tercero.
		En  caso  de  que  el  cheque  nominativo  se  expida,  o  el  depósito  de  los  recursos  se  transfiera  a  favor  de  alguna  de  las  Personas Autorizadas  o  de  un  Tercero,  El  Socio  o  en  su  caso  alguna  de  Las  Personas  Autorizadas  deberán  de  solicitar  por  escrito  a  la Sociedad,  dicha  gestión  señalando  el  nombre  completo  del  beneficiario  y  en  su  caso  el  número  de  cuenta,  número  de  sucursal  y nombre  de  la  Institución  Sociedad  donde  se  depositarán  los  recursos,  en  consecuencia  sí  El  Socio  o  Las  Personas  Autorizadas  no solicitan por escrito la gestión referida, se entenderá que el cheque nominativo se expedirá a nombre del Socio, o el depósito de los recursos se realizará en el primer número de cuenta señalado por el Socio.
		De  acuerdo  a  lo  convenido  en  el  presente  Contrato,  surtirá  efectos  de  prueba  plena  para  la  entrega  de  los  recursos  depositados,  la ficha  de  depósito  o  del  traspaso  electrónico  de  dichos  fondos  de  las  cuentas  de  la  Sociedad,  a  las  cuentas  de  El  Socio  o  a  las cuentas  que  El  Socio  o  alguna  de  Las  Personas  Autorizadas  por  escrito  soliciten  depositar  en  alguna  cuenta  a  nombre  de  Las Personas  Autorizadas  o  de  un  tercero  en  cualquier  Institución  Sociedad,  o  en  su  caso  la  firma  de  El  Socio  o  de  alguna  de  Las Personas  Autorizadas  o  de  un  tercero  en  que  se  dé  por  recibido  del  dinero  o  del  cheque  nominativo  respectivo  que  ampara  la entrega  de  los  fondos  solicitados.  La  SOCIEDAD  se  reserva  el  derecho  de  rehusar  el  retiro  solicitado,  si  en  la  cuenta  de  El  Socio no existiera Saldo Disponible suficiente.</p>
		
		<p>DÉCIMA  SÉPTIMA.  En  los  términos  de  las  disposiciones  legales  aplicables,  la  Sociedad,  deducirá,  retendrá  y  enterará  de  la  tasa bruta  de  interés,  el  impuesto  que  corresponda  a  El  Socio  por  las  operaciones  efectuadas  al  amparo  de  este  Contrato,  por  lo  que invariablemente  entregará  a  El  Socio  los  rendimientos  netos.  De  igual  forma,  la  Sociedad,  trasladará  o  repercutirá  a  El  Socio cualquier contribución que se cause en los términos de la legislación que resulte aplicable.</p>
		 
		<p>DÉCIMA  OCTAVA.  La  Sociedad  queda  facultada  por  El  Socio,  con  la  firma  del  presente  contrato,  a  cargar  en  su  cuenta  todos  los gastos,  comisiones,  retenciones  de   impuestos  o   cualquier  adeudo  que   se   genere  a   cargo  de   El   Socio  en   relación   con   este Contrato. Los cargos se harán con efectos a partir de la fecha en que dicho gasto, comisión, retención o adeudo se genere.</p>
		
		<p>DÉCIMA    NOVENA.  En  caso  de  que  la  solicitud  de  disposición  de  recursos  en  los  números  de  cuenta  de  El  Socio,  requeridos  por El Socio o por alguna de Las Personas Autorizadas sea incumplida por la Sociedad, La Sociedad, no será responsable cuando el incumplimiento  se  haya  debido acaso fortuito,  a  fuerza  mayor,  o  a  eventos  tales  como  fallas  en  el  funcionamiento  de  los  sistemas de  cómputo  de  la  Institución   Sociedad   donde   El   Socio   o   alguna   de   Las   Personas   Autorizadas   deseen   que   se   depositen   los recursos,  o  en  virtud  de  cualquier  tipo  de  interrupción  en  los  sistemas  de  comunicación  con  dicha  Institución  Sociedad,  o  por cualquier acontecimiento similar que no se encuentre en posibilidad de resolver o esté fuera de control de la Sociedad.</p>
		
		<p>VIGÉSIMA.  Los  porcentajes  de  pago  de  intereses,  las  comisiones  o  el  importe  de  los  cobros  que  por  cualquier  concepto  deban efectuarse,  podrán  ser  modificados  de  acuerdo  a  las  disposiciones  que  expidan  la  Secretaría  de  Hacienda  y  Crédito  Público,  la Comisión  Nacional  bancaria  y  de  Valores  o  el  Banco  de  México,  o  según  de  las  políticas  internas  de  la  Sociedad,  y  podrán efectuarse en forma automática en los números de cuenta que la Sociedad, aperture a nombre de El Socio.</p>
		
		<p>VIGÉSIMA  PRIMERA.-  El  Socio  nombra  como  beneficiarios  de  las  cuentas  que  amparan  su  Número  de  Socio  a  las  personas mencionadas  con  este  carácter  en  la  Relación  de  Beneficiarios.  Sin  embargo  El  Socio  podrá  en  todo  tiempo,  designar  o  sustituir beneficiarios,  o  modificar  la  proporción  que  corresponda  a  los  que  hubiera  designado  mediante  notificación  por  escrito  dirigida  a  la Sociedad.
		En   caso   de   fallecimiento   de   El   Socio,   la   Sociedad   entregará   a   los   beneficiarios   el   importe   que   El   Socio   haya   designado expresamente y por escrito para cada uno de ellos, en caso de que exista Saldo Disponible.</p>
		
		<p>VIGÉSIMA SEGUNDA. Las  entregas  de  dinero  que  se  hagan  a  La  Sociedad  por  medio  de  documentos, serán  recibidas  “salvo  buen cobro”  y  su  importe  se  abonará  en  firme  únicamente al  efectuarse  su  cobro,  aun  cuando  los  documentos aparezcan  como  recibidos por La Sociedad en fecha anterior al abono en firme.</p>
		
		<p>VIGÉSIMA  TERCERA.  La  Sociedad  remitirá  únicamente  a  petición  expresa  y  por  escrito  de  El  Socio,  dentro  de  los  primeros  5 (cinco)  días  naturales  de  cada  mes,  un  estado  de  cuenta  en  el  que  se  especificarán  las  cantidades  abonadas  o  cargadas;  el  saldo al inicio del periodo y a la fecha de corte; el rendimiento bruto y neto obtenidos; y en su caso, el importe de las comisiones a cargo de  El  Socio  durante  el  período  anterior.  La  fecha  de  corte  para  el  estado  de  cuenta,  será  el  último  día  de  cada  mes.  La  Sociedad prevendrá  por  escrito  a  El  Socio  de  la  fecha  de  corte,  la  que  no  podrá  variar  sin  previo  aviso  por  escrito  comunicado  por  lo  menos con un mes de anticipación.
		
		La  Sociedad  quedará  relevada  del  envío  a  El  Socio    del  estado  de  cuenta,  cuando  no  haya  tenido  movimiento  alguno  durante  el periodo   respectivo,   cuando   durante   el   periodo,   el   promedio   diario   del   Saldo   Disponible   haya   sido   inferior   a   la   suma   de    500 (quinientos)  salarios  mínimos  diarios  generales  para  el  Distrito  Federal,  o  cuando  El  Socio  no  haya  manifestado  su  deseo,  por escrito,  de  recibirlos.  De  la  misma  manera  La  Sociedad  quedará  relevada  de  la  obligación  de  enviar  el  estado  de  cuenta  cuando  le proporcione a El Socio la información a través de otros medios impresos.</p>
		
		<p>VIGÉSIMA  CUARTA.  El  Socio  se  obliga  a  revisar  las  operaciones  consignadas  en  su  estado  de  cuenta  o  en  el  documento  impreso que  le  entregue  La  Sociedad,  debiendo  realizar  las  observaciones  que  estime  convenientes.  En  su  caso,  para  poder  objetar  en tiempo,  El  Socio  deberá  de  pedir  a  La  Sociedad su  estado  de  cuenta  mensual  o  la  información  de  los  movimientos de  su  cuenta  a través  de  otros  medios  impresos,  dentro  de  los  5 (cinco)  días  naturales  que  sigan  al  corte.  Se  presumirá  que  El  Socio  recibió  dicho estado  de  cuenta  o  la  información  de  movimientos  a  través  de  otro  medio  impreso,  si  no  lo  reclamare  por  escrito  en  dicho  plazo . Durante  los  15 (quince)  días  naturales  al  corte  de  la  cuenta  o  los  5 (cinco)  días  hábiles  siguientes  de  haberlo  recibido,  El  Socio podrá  manifestar  por  escrito  las  objeciones  u  observaciones  que  considere  procedentes,  transcurrido  el  plazo  aquí  convenido,  las operaciones  registradas  en  el  estado  de  cuenta  o  en  otros  medios  impresos  se  considerarán  como  válidas  y  correctas,  y  harán  fe salvo prueba en contrario, en el juicio respectivo.</p>
		
		<p>VIGÉSIMA QUINTA. Con  excepción de  los  casos  previstos por  la  ley,  La  Sociedad  no  podrá  dar  noticias  o  proporcionar información sobre  los  movimientos  operados,  el  estado  que  guarde  la  cuenta  de  El  Socio,  o  las  operaciones  que  a  su  amparo  se  realicen,  sino únicamente a El Socio, a sus representantes legales, o a Las Personas Autorizadas.		
		Sin  embargo,  El  Socio  expresamente  faculta  a  La  Sociedad  para  realizar,  en  todo  momento  investigaciones y  proporcionar  reportes o  información  a  los  organismos  de  supervisión  que  La  Sociedad  tuviere,  o  a  las  Sociedades  de  Información  Crediticia  que  fueran autorizadas  por  la    Secretaría  de  Hacienda  y  Crédito  Público,  acerca  de  las  operaciones  celebradas  entre  La  Sociedad  y  El  Socio, el cual manifiesta que conoce la naturaleza y el alcance de la información que se solicitará o proporcionará.</p>
		
		<p>VIGÉSIMA  SEXTA.  El  presente  contrato  podrá  ser  modificado  por  La  Sociedad,  bastando  para  ello  que  lo  notifique  por  escrito  a  El Socio,  por  lo  menos  con  10 (diez)  días  hábiles  de  anticipación,  a  través  de  los  métodos  establecidos  en  la  Cláusula  Trigésima Tercera  de  este  contrato.  Estas  modificaciones  se  entenderán  como  aceptadas  tácitamente  por  El  Socio  al  momento  en  que  El Socio  o  alguna  de  Las  Personas  Autorizadas  realicen  cualquier  movimiento  o  giren  cualquier  instrucción  a  La  Sociedad  o  hagan uso  de  cualquiera de  Los  Servicios  en  alguno  de  los  números  de  cuenta  de  El  Socio  o  en  nuevos  números  de  cuenta  que  deseen aperturar con el Número de Socio.</p>
		
		<p>VIGÉSIMA  SÉPTIMA.  El  presente  Contrato  podrá  darse  por  terminado  por  cualquiera  de  las  partes,  previo  aviso  dado  por  escrito  a la   otra   parte,   con   15 (quince)   días   naturales   de   anticipación.      No   obstante   la   terminación   del   presente   contrato,   éste   seguirá produciendo  todos  sus  efectos  legales  entre  las  partes,  hasta  que  El  Socio  y  la  SOCIEDAD  hayan  cumplido  con  todas  y  cada  una de las obligaciones contraídas al amparo de este contrato.</p>
		
		<p>VIGÉSIMA  OCTAVA.  En  caso  de  que  El  Socio  resolviera  terminar  el  presente  Contrato,  deberá  instruir  a  la  SOCIEDAD  por  escrito, acerca  de  la  forma  de  disposición  de  los  valores  o  efectivo  que  existieran  a  su  favor,  y  en  todo  caso,  deberá  liquidar  previamente todos los  saldos a  su  cargo, por  lo  que  La  Sociedad no  podrá ser  desposeído  de  los  recursos propiedad de  El  Socio sin  que  antes haya pagado íntegramente dichos saldos.</p>
		
		<p>VIGÉSIMA  NOVENA.  En  caso  de  que  La  Sociedad  decida  dar  por  terminado  este  contrato,  una  vez  que  El  Socio  haya  pagado  los saldos  referidos en  la  Cláusula anterior,  El  Socio  queda  obligado  a  retirar  la  totalidad  del  Saldo  Disponible a  su  favor  dentro de los 15 (quince)  días  hábiles  siguientes  a  la  fecha  del  aviso  respectivo,  caso  contrario,  la  SOCIEDAD  queda  facultada  para  rembolsar dicho Saldo Disponible en un cheque a nombre de El Socio, mismo que ya no generará intereses a partir de dicha fecha.</p>
		
		<p>TRIGÉSIMA.  El  incumplimiento por  parte  de  El  Socio  a  cualesquiera  de  los  términos  de  este  Contrato  dará  derecho  a  La  Sociedad a  su  inmediata  rescisión,  independientemente de  que  pueda  exigir  el  pago  de  daños  y  perjuicios  derivados  de  dicho  incumplimiento expidiendo  en  ese  momento  un  cheque  a  nombre  de  El  Socio  por  la  cantidad  del  Saldo  Disponible  en  sus  cuentas  al  amparo  de  su Número de Socio, cuentas que no generarán intereses a partir de dicha fecha.</p>
		
		<p>TRIGÉSIMA  PRIMERA.  Las   partes  están   de   acuerdo  en   que   los   anexos   al   presente  Contrato  de   Depósito,   sus   adiciones  y reformas forman parte integrante del contenido del mismo y su contenido es vinculante y obligatorio para ambos.</p>
		
		<p>TRIGÉSIMA SEGUNDA. Para todos los efectos judiciales y extrajudiciales, El Socio señala como su domicilio '.$direccion.' y la SOCIEDAD señala como su 
			domicilio, RAYÓN #704, BARRIO SAN ANTONIO, ZIMATLÁN DE ÁLVAREZ, OAXACA, C.P. 71200.</p>
		<p>Mientras  las  partes  no  se  notifiquen  por  escrito  el  cambio  de  domicilio,  todos  los  avisos,  notificaciones,  diligencias  que  se  realicen en el aquí señalado, se tendrán por válidos y surtirán todos los efectos legales que correspondan.</p>
		
		<p>TRIGÉSIMA  TERCERA  Las  partes  convienen  en  que  La  Sociedad  podrá  realizar  cualquier  notificación  a  El  Socio  a  través  del estado  de  cuenta,  otros  medios  impresos,  publicaciones  colocadas  en  las  oficinas  de  La  Sociedad  o  por  cualquier  otro  medio  de divulgación o mediante notificación entregada a El Socio en alguna sucursal de La Sociedad o en su domicilio.</p>
		
		<p>TRIGÉSIMA   CUARTA.   Para   la   interpretación   y   cumplimiento   del   presente   Contrato,   las   partes   se   someten   expresamente   a   la competencia   de    las    leyes    
			y    a    la    jurisdicción    del    Distrito    Federal,   independientemente   de    cualquier   fuero    que    pudiera corresponderles  en  virtud  de  sus  domicilios  presentes  
			y  futuros.  Expuesto  lo  anterior  las  partes  firman  este  Contrato  con  fecha '.$this->getFechaLetras($fecha).' en Zimatlán de Álvarez, Oaxaca.
		Ratifico   tener   conocimiento del clausulado y sus implicaciones en cuanto   a   riesgo,   rendimiento   y   plazo   resultantes   de   mis inversiones  así  como  de  la  forma  en  que  he  quedado  clasificado  en  sus  archivos  para  efectos  de  mi  régimen  fiscal,  así  mismo ratifico  que  el  (los)  movimiento  (s)  que  se  efectúen,  serán  con  dinero  producto  del  desarrollo  normal  de  la  (s)  actividad  (es)  propia (s)  y  que  por  tanto  no  provienen  de  la  realización  de  actividades  ilícitas,  por  lo  que  reconozco  plenamente  conocer  y  entender  las disposiciones relativas a las operaciones realizadas con recursos de procedencia ilícita y sus consecuencias.</p>
		</div>

		<table align="center" style="width:90%" border=0>
			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center">Nombre del Socio:</td>
				<td></td>
				<td style="border-top: 1px solid" align="center">ADRIANA COINTA GERONIMO DIAZ</td>
				<td></td>
			</tr>		
			<tr>
				<td></td>
				<td align="center"><b>'.$nombre.'</b></td>
				<td></td>
				<td align="center">Representante Legal</td>
				<td></td>
			</tr>		
			<tr>
				<td></td>
				<td align="center">No. de socio:'.$idsocio.'</td>
				<td></td>
				<td align="center">'.$empresa.'</td>
				<td></td>
			</tr>		
		</table>		
		';
		$html.='
		</div>
		</body>
		</html>
		';
		ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));		
	}

	
	//2019-04-09 Integración credito individual
	public function pdf_pagare_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$fun_credito = 'get_solicitud_credito';

		$fields = array("idproducto");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."creditos", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];
		if ($cred['idproducto']==='10'){
			$fun_credito = 'get_solicitud_credito_ind';
		}

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "tasa", "tasa_mora");
		$where = array("idcredito"=>$idcredito);
		//$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $this->base->selectRecord($this->esquema.$fun_credito, $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		if ($cred['idproducto']==='10'){
			$tasa = $cred['tasa']*12;
			$tasa_mora = $cred['tasa_mora'];
		}else{
			$tasa = $cred['tasa'];
			$tasa_mora = 0.01;
			if ($tasa==0){

				$nivel = $this->base->querySelect("SELECT ssi_tasa, dias, round((ssi_tasa/12),2) as tasa_mes, round((ssi_tasa/12),2)*2 as mes_mora, numero_pagos 
				FROM public.niveles WHERE nivel=".$cred['nivel']." and fecha_inicio <= '".$cred['fecha_entrega_col']."'::date ORDER BY fecha_inicio desc LIMIT 1", TRUE);
				$nivel= $nivel[0];
				$tasa = $nivel['tasa_mes'];
				$tasa_mora = $nivel['mes_mora'];				
			}
			
		}

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);

		


		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total");
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Semana","Vencimiento","Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		$tabla = '';
		$tabla.= $this->tableCreateAmor($title, $amor);

		$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid"=>$cred['idaval1']);
		$aval = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
		$aval = $aval[0];
		if ($aval){
			$aval_nombre = $aval['nombre'];
		}else{
			$aval_nombre = "";
		}

		$fields = array("nombre", $this->esquema."get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid"=>$cred['idaval2']);
		$aval2 = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
		if ($aval2) {
			$aval2_nombre = $aval2[0]['nombre'];
		}else{
			$aval2_nombre = "";
		}

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">PAGARÉ</h3>';
        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr class="seccion">
					<th align="left">
						SUCURSAL: '.$sucursal.'
					</th>
					<th class="seccion-right">
						FECHA: '.date_format($fecha,'d/m/Y').'
					</th>
				</tr>
				<tr class="seccion">
					<th align="left">
						PAGARE No.: '.$pagare.'
					</th>		
				</tr>				
			</table>';
		$html.='<div > </div>';
		$html.='<font size="12px">';
		$html.='<br>
			<p>Debemos y pagaremos incondicionalmente por este pagaré a la orden de '.$empresa.', 
			la cantidad de $'.number_format($monto, 2, '.', ',').' - ('.$monto_letra.'), 
			misma que deberá ser pagada de acuerdo a la tabla de amortización anexa.</p>';
		$html.='<br>
		<p>Pagadero en esta ciudad juntamente con el principal en el domicilio ubicado en RAYON #704, BARRIO SAN ANTONIO 
		Por lo que si existe un retraso de los pagos arriba mencionados, la totalidad del saldo insoluto se dara por vencido, 
		generando el vencimiento anticipado de los pagos pendientes, mas los intereses moratorios, que en su caso, 
		se generen hasta el pago total del adeudo. </p>
		<p>Valor recibido a mi entera satisfaccion con anterioridad a la presente fecha siendo la suscripcion del presente pagaré 
		el recibo mas amplio que en derecho proceda por la cantidad antes entregada, para todos los efectos a los que haya lugar. 
		El importe de este pagaré causará interes ordinarios a razón de '.number_format($tasa /12, 2, '.', ',').'% mensual, sobre el saldo insoluto del credito. 
		La cantidad importe de este pagaré causará intereses moratorios a razón de '.number_format(($tasa /12 ) * 2, 2, '.', ',').'% aplicado al saldo insoluto de cada mes 
		o fracción de retraso, mientras dure la mora.</p>
		';

		
        $html.='<br> 
			<table style="width:100%"  border="0">
				<tr>
					<td></td>
					<td align="center"  width="25%">EL DEUDOR <br><br>&nbsp;</td>
					<td></td>
					<td align="center"  width="25%">AVAL <br><br>&nbsp;</td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">Sr(a) obligado principal<br>'.$cred['nombre'].'</td>
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">'.$aval_nombre.'</td>
					<td></td>
				</tr>		
			</table>';				
		
		if ($cred['nivel']>=15){
			$html.='<br> <br> 
				<table style="width:100%"  border="0">
					<tr>
						<td></td>
						<td align="center"  width="25%">AVAL <br><br>&nbsp;</td>
						</tr>		
					<tr>
						<td></td>
						<td style="border-top: 1px solid" align="center"  width="25%">'.$aval2_nombre.'</td>
						<td></td>
					</tr>		
				</table>';					
		}
		$html.='
		</div>
		</body>
		</html>
		';
		ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	public function encabezadoReport($title){		
		$empresa =$this->session->userdata('esquema') =="fin." ? "FINCOMUNIDAD" : "BANCOMUNIDAD";
		$html ='<!DOCTYPE html">
		<html">
		<head>
				<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
                <title>Reporte</title>
		</head>
		<body font-family: DejaVu Sans;>';

		$html = $html.'
		<header>
			<table>
				<tr>
				<td id="header_logo">
				<img id="logo" src="./assets/images/hd.png">
			</td>
			<td id="header_texto">
				<div>CENTRO NACIONAL DE TUTORIALES MÉXICO Y ALREDEDORES</div>
				<div>Consejo de alguna institución para el desarrollox</div>
				<div>"La mejor de mí para ustedes"</div>
			</td>

			<td id="header_logos">
				<img id="logo1" src="./assets/images/hd1.png">
				<img id="logo2" src="./assets/images/hd2.png">
			</td>
		</tr>
			</table>
		</header>';		
		

		$html = $html.'<p style="font-family: firefly, DejaVu Sans, sans-serif;">献给母亲的爱</p>';
		$html = $html.'<div class="footer"><em>Impreso </em></div>';
		$html = $html.'<div class="titulo-data">';
		$html = $html.'	   <p>'.$title.'</p>';
		$html = $html.'	   <hr>';
		$html = $html.'</div>';
		return $html;		
	}


	public function pdf_pagare222_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = 'EMPRESA MUESTRA';

		$sucursal ='ZIMATLÁN';
		$fecha = new DateTime('2017-11-10');
		
		$html = $this->encabezadoReport('PAGARE');
        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr class="seccion">
					<th align="left">
						SUCURSAL: '.$sucursal.'
					</th>
					<th class="seccion-right">
						FECHA: '.date_format($fecha,'d/m/Y').'
					</th>
				</tr>
			</table>';
		$html.='<div > </div>';
		$html.='<font size="12px">';
		$html.='<br>
			<p >Debemos y pagaremos incondicionalmente por este MEGA pagaré a la orden de '.$empresa.', 
			misma que deberá ser pagada de acuerdo a la siguiente tabla de amortización.</p>';
		$html.='<font size="11px">';
		$html.='<font size="12px">';
		//$html.=utf8_decode('<br> D&aacute;niel');
		$html.='<br>
		<p>Pagadero en esta ciudad juntamente con el principal en el domicilio ubicado en RAYON #704, BARRIO SAN ANTONIO 
		Por lo que si existe un retraso de los pagos arriba mencionados, la totalidad del saldo insoluto se dara por vencido, 
		generando el vencimiento anticipado de los pagos pendientes, mas los intereses moratorios, que en su caso, 
		se generen hasta el pago total del adeudo. </p>
		<p>Valor recibido a mi entera satisfaccion con anterioridad a la presente fecha siendo la suscripcion del presente pagaré 
		el recibo mas amplio que en derecho proceda por la cantidad antes entregada, para todos los efectos a los que haya lugar. 
		El importe de este pagaré causará interes ordinarios a razón de 4.08% mensual, sobre el saldo insoluto del credito. 
		La cantidad importe de este pagaré causará intereses moratorios a razón de 0.00% aplicado al saldo insoluto de cada mes 
		o fracción de retraso, mientras dure la mora.</p>

		<p> Este pagaré se firma el  '.date_format($fecha,'d/m/Y').', en ZIMATLAN DE ALVAREZ, OAXACA</p>
		<br>
		<p align = "center">EL DEUDOR</p>
		<br><br>
		<p align = "center">Sr(a) obligado principal</p>
		';
		$html.='
		</div>
		</body>
		</html>
		';
		ob_clean();	
		//$this->load->library('dompdf_gen');
		$this->dompdf = new Dompdf();		

		$this->dompdf->load_html (($html), 'UTF-8');
		//$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));

	}


	public function tableEdoCuenta($title, $data) {
		$html='';
		$html.='<table style="width:80%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';

		//$amor = $this->base->querySelect("SELECT numero, fecha, fecha_pago, dias, pag_capital, (pag_interes_vig+pag_interes_ven) as interes, pag_iva, total_pagado, (capital-pag_capital) as saldo_capital, 0 as int_mora 
		//$title = array("Pago","Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora", );

		$capital_req=0;
		$capital_pag=0;
		$interes_req=0;
		$interes_mora=0;
		$capital_saldo=0;
		$dias_saldo=0;
		$capital_inicial=0;
		$iva = 0;
		foreach($data as $key => $value) {
			$fecha = date_create($value['fecha']);
			$fecha = date_format($fecha,'d/m/Y');

			$fechaPago = date_create($value['fecha_pago']);
			$fechaPago = date_format($fechaPago,'d/m/Y');
			if ($value['numero']<>-99){
				$html.='  <tr>';
				if ($value['numero']<=0){
					$html.='  <td>  </td>';
				}else{
					$html.='  <td align="right">'.$value['numero'].'</td>';
				}
				$html.='  <td>'.$fecha.'</td>';
				$iva = ($value['c_interes_acumula'] + $value['c_mora'])*0.16;
				if ($value['fecha_pago'] === null ){
					$html.='  <td> </td>';
					$html.='  <td align="right">'.$value['dias'].'</td>';
					$html.='  <td> </td>';
					$html.='  <td align="right">'.number_format($value['c_interes_acumula'], 2, '.', ',').'</td>';
					$html.='  <td align="right">'.number_format($iva, 2, '.', ',').'</td>';
					$html.='  <td> </td>';
				}else{
					$html.='  <td>'.$fechaPago.'</td>';
					$html.='  <td align="right">'.$value['dias'].'</td>';
					$html.='  <td align="right">'.number_format($value['pag_capital'], 2, '.', ',').'</td>';
					$html.='  <td align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
					$html.='  <td align="right">'.number_format($iva, 2, '.', ',').'</td>';
					$html.='  <td align="right">'.number_format($value['total_pagado'], 2, '.', ',').'</td>';
				}
				$dias_saldo = $value['dias'];
				$capital_saldo = $value['saldo_capital'];
				$html.='  <td align="right">'.number_format($value['saldo_capital'], 2, '.', ',').'</td>';
				$html.='  <td align="right">'.number_format($value['c_mora'], 2, '.', ',').'</td>';
				$html.='  </tr>';
				$capital_req = $value['capital_requerido'];
				$capital_pag = $value['capital_pagado'];
				$interes_req = $value['c_interes_acumula'] + $value['c_mora'];
				$interes_mora = $value['c_mora'];
			}
			else{
				$capital_inicial=$value['saldo_capital'];
			}
		}
		$html.='</table>';

		if ($capital_pag <= $capital_inicial )
		{
			$title2 = array("Capital requerido", "Interes", "Int. moratorio", "IVA", "Pago total amortización");		
			$html2='<br><br>';
			$html2.='<table style="width:80%" align="center">';
			$html2.='  <tr>';
			foreach($title2 as $key => $value) {
				$html2.='    <th>'.$value.'</th>';
			}
			$html2.='  </tr>';
			$esp_capital = 0;
			$esp_int = 0;
			if ($capital_req > $capital_pag ){
				$esp_capital = $capital_req - $capital_pag;
				$esp_int = $interes_req;
			}
			$esp_iva = ($esp_int+$interes_mora) * 0.16;
			$html2.='  <tr>';
			$html2.='  <td align="right">'.number_format($esp_capital, 2, '.', ',').' </td>';
			$html2.='  <td align="right">'.number_format($esp_int, 2, '.', ',').' </td>';
			$html2.='  <td align="right">'.number_format($interes_mora, 2, '.', ',').' </td>';
			$html2.='  <td align="right">'.number_format($esp_iva, 2, '.', ',').' </td>';
			$esp_total = $esp_capital + $esp_int + $esp_iva;
			$html2.='  <td align="right">'.number_format($esp_total, 2, '.', ',').' </td>';	
			$html2.='  </tr>';			
			$html2.='</table>';

			
			$title3 = array("Capital a liquidar", "Interes", "Int. moratorio", "IVA", "Pago total para liquidar");
			$html3='<br><br>';
			$html3.='<table style="width:80%" align="center">';
			$html3.='  <tr>';
			foreach($title3 as $key => $value) {
				$html3.='    <th>'.$value.'</th>';
			}
			$html3.='  </tr>';
			$esp_capital = 0;
			$esp_int = 0;
			$esp_iva = 0;
			if ($capital_req > $capital_pag ){
				$esp_capital = $capital_req - $capital_pag;
				$esp_int = $interes_req;
			}
			$esp_iva = ($esp_int+$interes_mora) *  0.16;
			$html3.='  <tr>';
			$html3.='  <td align="right">'.number_format($capital_saldo, 2, '.', ',').' </td>';
			$html3.='  <td align="right">'.number_format($esp_int, 2, '.', ',').' </td>';
			$html3.='  <td align="right">'.number_format($interes_mora, 2, '.', ',').' </td>';
			$html3.='  <td align="right">'.number_format($esp_iva, 2, '.', ',').' </td>';
			$esp_total = $capital_saldo + $esp_int + $esp_iva;
			$html3.='  <td align="right">'.number_format($esp_total, 2, '.', ',').' </td>';	
			$html3.='  </tr>';			
			$html3.='</table>';
		}
		else{

			$title2 = array("Estatus");		
			
			$html2='<br><br>';
			$html2.='<table style="width:80%" align="center">';
			$html2.='  <tr>';
			$html2.='  <td align="CENTER"> CREDITO PAGADO </td>';
			$html2.='  </tr>';			
			$html2.='</table>';

			$html3='';
		}
		

		$html.=$html2;
		$html.=$html3;
		return $html;
	}





	public function tableCreateAmor($title, $data) {
		$html='';
		$html.='<table style="width:80%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		foreach($data as $key => $value) {
			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha,'d/m/Y');
			
			$capital = $value['saldo_capital'];
			$html.='  <tr>';
			$html.='  <td>'.$value['numero'].'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td align="right">'.$capital.'</td>';
			$html.='  <td align="right">'.$value['interes'].'</td>';
			$html.='  <td align="right">'.$value['iva'].'</td>';
			$html.='  <td align="right">'.$value['capital'].'</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html.='  <td align="right">'.$value['total'].'</td>';
			/*
			foreach($value as $keydata => $valuedata)
				$html.='<td>'.$valuedata.'</td>';
			}
			*/
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}

	public function tableCreateAmorNueva($title, $data) {
		$html='';
		$html.='<table style="width:80%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		foreach($data as $key => $value) {
			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha,'d/m/Y');

			$fechaPago = date_create($value['fecha_pago']);
			$fechaPago = date_format($fechaPago,'d/m/Y');

			$capital = $value['saldo_capital2'];
			$cappag = $value['total'] - $value['interes_pag'] -$value['iva_pag'];
			$html.='  <tr>';
			$html.='  <td>'.$value['numero'].'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td>'.$fechaPago.'</td>';
			$html.='  <td align="right">'.$capital.'</td>';
			$html.='  <td align="right">'.$value['interes_pag'].'</td>';
			$html.='  <td align="right">'.$value['iva_pag'].'</td>';
			$html.='  <td align="right">'.$cappag.'</td>';
			$html.='  <td align="right">'.$value['total'].'</td>';
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}

	public function tableCreatePlanPagos($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$intPago=1;
		$saldoCapital=0;
		$pagoFijo=0;
		foreach($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			$total = $value['total'] + $value['garantia'] +  $value['ajuste'];
			if ($intPago==1)	{
				$capital = $value['total']-$value['aportesol']; //2021-05-04 Se cambio
				//$capital = $value['capital'];
				$saldoCapital=$value['saldo_capital']-$capital;
				$pagoFijo = $total;

				$html.='  <tr>';
				$html.='  <td height="15"></td>';
				//$html.='  <td></td>';
				$html.='  <td></td>';
				$html.='  <td></td>';
				$html.='  <td align="right">'.$value['saldo_capital'].'</td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  </tr>';

			}else{
				$saldoCapital-=$capital;
			}

			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha,'d/m/Y');

			$html.='  <tr>';
			$html.='  <td height="15">'.$intPago.'</td>';
			//$html.='  <td>'.$numero.'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td align="right">'.$capital.'</td>';
			$html.='  <td align="right">'.$saldoCapital.'</td>';
			$html.='  <td align="right">'.$value['aportesol'].'</td>';
			$html.='  <td align="right">'.$garantia.'</td>';
			$html.='  <td align="right">'.$garantia*$numero.'</td>';
			$html.='  <td align="right">'.$total.'</td>';
			$html.='  <td align="right"></td>';
			$html.='  <td align="right"></td>';
			$html.='  <td align="right"></td>';
			$html.='  </tr>';
			$intPago = $intPago + 1;
		}
		$html.='</table>';
		return $html;
	}


	public function tableCreateConvenio($title, $data, $monto) {
		$saldo=$monto;
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		//array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "Ahorro comp.", 
		//	"Saldo ahorro comp.", "Total del pago", "Firma del promotor", "Incidencias");
		foreach($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			$total = $value['ssi_total'];
			$ssi_capital = $value['ssi_capital'];
			$saldo= $saldo-$ssi_capital;
			$html.='  <tr>';
			$html.='  <td>'.$value['numero'].'</td>';
			$html.='  <td>'.$value['fecha_vence'].'</td>';
			$html.='  <td>'.$ssi_capital.'</td>';
			$html.='  <td>'.$saldo.'</td>';
			$html.='  <td align="right">'.$value['aportesol'].'</td>';
			$html.='  <td align="right">'.$garantia.'</td>';
			$html.='  <td align="right">'.$numero*$garantia.'</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html.='  <td align="right">'.$total.'</td>';
			$html.='  <td></td>';
			$html.='  <td></td>';
			/*
			foreach($value as $keydata => $valuedata)
				$html.='<td>'.$valuedata.'</td>';
			}
			*/
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}

	//2019-04-09 Integración credito individual
	public function pdf_plan_pago_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$id_grupo = $cred['idgrupo'];
		if ($id_grupo === "0"){
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
		}else{
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "colmena_grupo");
			$where = array("idgrupo"=>$id_grupo);
			$col = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
			$col = $col[0];
			$col_nombre = $col['colmena_nombre'];
			$col_numero = $col['colmena_numero'];
			$col_grupo = $col['colmena_grupo'];			
		}

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}		
		$fecha = new DateTime($cred['fecha_entrega_col']);


		$usr = $this->base->querySelect("SELECT c.usuario, (u.first_name || ' ' || u.last_name) as nombre 
			FROM ".$this->session->userdata('esquema')."creditos as c 
				JOIN security.users as u ON c.usuario=u.username or c.usuario=u.id::varchar
			WHERE idcredito=".$idcredito, TRUE);
		$promotor= $usr[0]['nombre'];				


		$data = $this->base->querySelect("SELECT sum(garantia) as total FROM (
			SELECT c.idcredito FROM ".$this->session->userdata('esquema')."creditos as c
				JOIN (SELECT idacreditado, fecha FROM ".$this->session->userdata('esquema')."creditos WHERE idcredito=".$idcredito.") as x ON c.idacreditado=x.idacreditado AND c.fecha<x.fecha
			ORDER BY c.fecha DESC limit 1) as c
			JOIN ".$this->session->userdata('esquema')."amortizaciones as a ON c.idcredito=a.idcredito", TRUE);

		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total", "ajuste" );
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Pago","Fecha<br>programada","Capital", "Saldo capital", "Aporte solidario", "Garantia", "Gtia. acum.", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
		$tabla = '';
		$tabla.= $this->tableCreatePlanPagos($title, $amor);

		$header = $this->headerReport('');
		//$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">PLAN DE PAGOS</h3>';

        $html.='
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: '.$cred['idsucursal'].' - '.$sucursal.'
					</th>
					<th class="seccion-left">
						Credito: '.$cred['idcredito'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Colmena: '.$col_numero.' '.$col_nombre.'
					</th>		
					<th class="seccion-left">
						Grupo: '.$col_grupo.'
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Socio: '.$cred['idacreditado'].' '.$cred['nombre'].'
					</th>		
					<th class="seccion-left">
						Fecha: '.date_format($fecha,'d/m/Y').'
					</th>		
				</tr>	
			</table>';
		$html.='<br><div > </div>';
        $html.=$tabla;
		$html.='<br>';
        $html.='<br> <br>
			<table style="width:100%"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%">Entrego <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%">Recibio <br><br><br>&nbsp;</td>
			<td></td>
			</tr>		

			<tr>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">'.$promotor.'<br>Promotor</td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">'.$cred['nombre'].'<br>Prestataria</td>
			<td></td>
			</tr>		
		</table>';		

		$html.='
		</div>
		</body>
		</html>
		';

ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}

	//2019-04-09 Integración credito individual
	//2019-04-29 Tasa moratoria
	public function pdf_tabla_amortizacion_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena", "fecha_entrega_col", "tasa", "tasa_mora");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$query ="select dias, ssi_tasa FROM niveles WHERE nivel=".$cred['nivel']." ORDER BY fecha_inicio desc limit 1";
		$temp = $this->base->querySelect($query, TRUE);
		$tasa = $temp[0]['ssi_tasa'];
		$tasa_mora = $tasa * 2;
		$nivel = $temp[0]['dias']; 

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);
		if ($cred['idproducto']==='10'){
			$tasa = $cred['tasa']*12;
			$tasa_mora = $cred['tasa_mora'];
		}


		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total" );
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Pago","Vencimiento","Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		$tabla = '';
		$tabla.= $this->tableCreateAmor($title, $amor);


		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: '.$cred['idsucursal'].' - '.$sucursal.'
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: '.$cred['idcredito'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: '.$cred['idacreditado'].' '.$cred['nombre'].'
					</th>		
					<th class="seccion-left">
						Fecha: '.date_format($fecha,'d/m/Y').'
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Monto: '.number_format($monto, 2, '.', ',').'
					</th>		
					<th class="seccion-left">
						Interes: '.number_format($tasa, 2, '.', ',').'%
					</th>
					<th class="seccion-left">
						Mora: '.number_format($tasa*2, 2, '.', ',').'%
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Plazo: '.$nivel.' dias
					</th>		
					<th class="seccion-left">
						Cartera: Comercial
					</th>
					<th class="seccion-left">
						Financiamiento: Recursos propios
					</th>		
				</tr>
				<tr>
					<th class="seccion-left">
						Producto: Crédito colmena '.$cred['nivel'].'
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Oficial SF: 
					</th>		
				</tr>													
				<tr>
					<th class="seccion-left">
						Nivel: '.$cred['nivel'].'
					</th>		
					<th>
					</th>
					<th>
					</th>		
				</tr>													
			</table>';
		$html.='<br><br><div > </div>';
        $html.=$tabla;
		$html.='<br><br>';
		/*
        $html.='<br>
			<table style="width:100%" >
				<tr class="seccion">
					<th >PAGO</th>
					<th>FECHA</th>
					<th>CAPITAL</th>
					<th>INTERES</th>
					<th>MORA</th>
					<th>IVA</th>
					<th>PAGO</th>
					<th>SUC.PAGO</th>
					<th>NO.TRANS</th>
				</tr>
				<tr class="seccion">
					<td>. </th>
					<td> </th>
					<td> </th>
					<td> </th>
					<td> </th>
					<td> </th>
					<td> </th>
					<td> </th>
					<td> </th>
				</tr>				
			</table>';
		
        $html.='<br>
			<table style="width:100%" >
				<tr>
					<td align="left">TOTAL PAGADO: </td>
				</tr>		
				<tr>
					<td align="left">SALDO CAPITAL: '.number_format($monto, 2, '.', ',').'</td>
					<td align="left">DIAS VENCIDOS: </td>
				</tr>		
			</table>';	
		*/				
		$html.='
		</div>
		</body>
		</html>
		';

ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}

	
	//2019-04-29 Tasas
	public function pdf_tabla_amortizacion_nueva_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena", "fecha_entrega_col", "tasa", "tasa_mora");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$query ="select dias, ssi_tasa FROM niveles WHERE nivel=".$cred['nivel']." ORDER BY fecha_inicio desc limit 1";
		$temp = $this->base->querySelect($query, TRUE);
		$tasa = $temp[0]['ssi_tasa'];
		$tasa_mora = $tasa * 2;
		$nivel = $temp[0]['dias']; 



		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);
		if ($cred['idproducto']==='10'){
			$tasa = $cred['tasa']*12;
			$tasa_mora = $cred['tasa_mora'];
		}

		$fields = array("numero", "fecha_vence", "fecha_pago", "saldo_capital","saldo_capital2","capital", "capital_pag", "interes_pag", "iva_pag", "aportesol", "garantia", "total" );
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Pago","Vencimiento", "Pago", "Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		$tabla = '';
		$tabla.= $this->tableCreateAmorNueva($title, $amor);


		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: '.$cred['idsucursal'].' - '.$sucursal.'
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: '.$cred['idcredito'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: '.$cred['idacreditado'].' '.$cred['nombre'].'
					</th>		
					<th class="seccion-left">
						Fecha: '.date_format($fecha,'d/m/Y').'
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Monto: '.number_format($monto, 2, '.', ',').'
					</th>		
					<th class="seccion-left">
						Interes: '.number_format($tasa, 2, '.', ',').'%
					</th>
					<th class="seccion-left">
						Mora: '.number_format($tasa*2, 2, '.', ',').'%
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Plazo: '.$nivel.' dias
					</th>		
					<th class="seccion-left">
						Cartera: Comercial
					</th>
					<th class="seccion-left">
						Financiamiento: Recursos propios
					</th>		
				</tr>
				<tr>
					<th class="seccion-left">
						Producto: Crédito colmena '.$cred['nivel'].'
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Oficial SF: 
					</th>		
				</tr>													
				<tr>
					<th class="seccion-left">
						Nivel: '.$cred['nivel'].'
					</th>		
					<th>
					</th>
					<th>
					</th>		
				</tr>													
			</table>';
		$html.='<br><br><div > </div>';
        $html.=$tabla;
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}




	public function pdf_edo_cuenta_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		
		$fecha = new DateTime($cred['fecha_entrega_col']);

/*
		$amor = $this->base->querySelect("SELECT numero, fecha, fecha_pago, dias, pag_capital, (pag_interes_vig+pag_interes_ven) as interes, pag_iva, total_pagado, (capital-pag_capital) as saldo_capital, 0 as int_mora, capital_pagado, capital_req, interes_ven, interes_vig, interes_mora 
			FROM ".$this->session->userdata('esquema')."ftr_pagos_ind(".$idcredito.", current_date)", TRUE);		
*/
/* 
		$amor = $this->base->querySelect("SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, p_capital as pag_capital, p_interes as interes, p_iva as iva, p_pago_total as total_pagado,
			capital_saldo as saldo_capital, coalesce(p_mora,0) as int_mora, capital_pagado, capital_requerido, c_interes_acumula, c_mora, c_iva  FROM ama.ftr_pago_individual(".$idcredito.", current_timestamp::timestamp)", TRUE);
*/
		$amor = $this->base->querySelect("SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, (p_capital_vig+p_capital_ven) as pag_capital, (p_interes_vig+p_interes_ven) as interes, 
			(p_iva_vig+p_iva_ven) as iva, p_pago_total as total_pagado,
			capital_saldo as saldo_capital, coalesce(p_interes_mora,0) as int_mora, capital_pagado, capital_requerido, (interes_vig+interes_ven) as c_interes_acumula, interes_mora as c_mora, (iva_vig+iva_ven+iva_mora) as c_iva
			FROM ama.ftr_pago_individual(".$idcredito.", current_timestamp::timestamp)", TRUE);
	//print_r($data);
		//die();
		$title = array("Pago","Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora", );
		$tabla = '';
		$tabla.= $this->tableEdoCuenta($title, $amor);


		

		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: '.$cred['idsucursal'].' - '.$sucursal.'
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: '.$cred['idcredito'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: '.$cred['idacreditado'].' '.$cred['nombre'].'
					</th>		
					<th class="seccion-left">
						Fecha: '.date_format($fecha,'d/m/Y').'
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Monto: '.number_format($monto, 2, '.', ',').'
					</th>		
					<th class="seccion-left">
						Interes: 48.95%
					</th>
					<th class="seccion-left">
						Mora: 0.01%
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Plazo: 175 dias
					</th>		
					<th class="seccion-left">
						Cartera: Comercial
					</th>
					<th class="seccion-left">
						Financiamiento: Recursos propios
					</th>		
				</tr>
				<tr>
					<th class="seccion-left">
						Producto:
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Oficial SF: 
					</th>		
				</tr>													
				<tr>
					<th class="seccion-left">
						Nivel: '.$cred['nivel'].'
					</th>		
					<th>
					</th>
					<th>
					</th>		
				</tr>													
			</table>';
		$html.='<br><br><div > </div>';
        $html.=$tabla;
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
	
	

	//2019-04-09 Integración credito individual
	public function pdf_convenio_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
	
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];
	
		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);
	
		$idgrupo=$cred['idgrupo'];
		if($idgrupo === "0"){
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
		}else{
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
			$where = array("idgrupo"=>$idgrupo);
			$gpo = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
			$gpo = $gpo[0];	
			$col_nombre = $gpo['colmena_nombre'];
			$col_numero = $gpo['colmena_numero'];
			$col_grupo = $gpo['colmena_grupo'];			
		}
		
	
		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "(total+garantia+ajuste) as ssi_total", "(total-aportesol+ajuste) as ssi_capital", "iva", "aportesol", "garantia", "total" );
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "Ahorro comp.", "Saldo ahorro comp.", "Total del pago", "Firma del promotor", "Incidencias");
		$tabla = '';
		$tabla.= $this->tableCreateConvenio($title, $amor, $monto);
	
	
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACION</h3>';
	
		$html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						Sucursal: '.$cred['idsucursal'].' - '.$sucursal.'
					</th>
					<th></th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: '.$cred['idacreditado'].' '.$cred['nombre'].'
					</th>		
					<th></th>
					<th class="seccion-left">
						Credito: '.$cred['idcredito'].'
					</th>
				</tr>	
				<tr>
					<th class="seccion-left">
						Fecha: '.date_format($fecha,'d/m/Y').'
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Monto: '.number_format($monto, 2, '.', ',').'
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Colmena: '.$col_numero.' - '.$col_nombre.'
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Grupo: '.$col_grupo.'
					</th>		
				</tr>													
			</table>';
		$html.='<br><br><div > </div>';
		$html.=$tabla;
		$html.='<br><br>';

        $html.='<br> <br> <br> <br> 
			<table style="width:100%"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%">Autorizó <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%">Entregó <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center" width="25%">Recibió <br><br><br>&nbsp;</td>
			<td></td>
			</tr>		

			<tr>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Promotor</td>
			<td></td>
			<td style="border-top: 1px solid"  align="center" width="25%">'.$cred['nombre'].'<br>Socia</td>
			<td></td>
			</tr>		
		</table>';		

		$html.='
		</div>
		</body>
		</html>
		';
	
	ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
	
	
	
	public function pdf_retgarantia_acreditado_get(){
		$idacreditado = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
	
		$fields = array("idacreditado", "idsucursal", "nombre", "col_numero", "col_nombre", "grupo_numero", "grupo_nombre");
		$where = array("idacreditado"=>$idacreditado);
		$cred = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];
		

		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		
		//2019-11 Se consulta los depositos y retiros para obtener la diferencia
		//$data = $this->base->querySelect("SELECT sum(comprometido) as total FROM ".$this->session->userdata('esquema')."get_creditos_resumen2(".$cred['idacreditado'].")", TRUE);
		$sql = "SELECT sum(q.deposito) as deposito, sum(q.ahorro) as ahorro, sum(q.diferencia) as total 
			FROM (
				select CASE WHEN m.movimiento='D' THEN m.importe ELSE 0 END as deposito, CASE WHEN m.movimiento='R' THEN m.importe ELSE 0 END as ahorro, 
				CASE WHEN m.movimiento='D' THEN m.importe ELSE m.importe*-1 END as diferencia,
				m.fecha
				from ".$this->session->userdata('esquema')."ahorros as a
					join ".$this->session->userdata('esquema')."ahorros_mov as m ON a.idahorro = m.idahorro and a.idacreditado=".$cred['idacreditado']."
				ORDER BY m.fecha
				) as q ";

		$data = $this->base->querySelect($sql, TRUE);
		$total_recibo= $data[0]['total'];				

		if ($total_recibo==0){
			$monto_letra = 'cero';
		}else{
			$monto_letra = $this->numeroToLetras( number_format($total_recibo, 2, '.', ''));
		}
	
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">';
	
		$html.='<br>
			<table style="width:100%">
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>
				<tr>
					<th colspan="2" class="seccion-left">
						AUTORIZACION DE TRANSPASO POR LA CANTIDAD DE
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						'.$total_recibo.' 
					</th>		
					<th class="seccion-left">'.$monto_letra.'</th>
				</tr>
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>	
			</table>';
		$html.='<br><br><br>
			<div border=1>
				<table style="width:100%">
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
					<tr>
						<th colspan="3" class="seccion-left">
							POR CONCEPTO DE: TRANSPASO DE AHORRO COMPROMETIDO AL AHORRO CORRIENTE DE LA SOCIA
						</th>
					</tr>
					<tr>
						<th colspan="2" class="seccion-left">
							'.$cred['nombre'].'
						</th>		
						<th class="seccion-left">Número: '.$cred['idacreditado'].' </th>
					</tr>	
					<tr>
						<th class="seccion-left">
							DE LA COLMENA NUMERO: '.$cred['col_numero'].'
						</th>		
						<th class="seccion-left">DENOMINADA: '.$cred['col_nombre'].'
						</th>
						<th class="seccion-left">
							DEL GRUPO: '.$cred['grupo_numero'].'
						</th>		
					</tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
					<tr> <th colspan="3" class="seccion-right">A   10   DE   JULIO   DE   2018  </th> </tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
				</table>

			</div>';

		$html.='<br> <br>
			<div border=0>
				<table style="width:100%" border="0">
				<tr>
				<td></td>
				<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				<td align="center" width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				</tr>		

				<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">Promotor</td>
				<td></td>
				<td style="border-top: 1px solid"  align="center" width="25%">'.$cred['nombre'].'<br>Socia</td>
				<td></td>
				</tr>		


			</table> </div>';		


		$html.='
		</div>
		</body>
		</html>
		';
ob_clean();			
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		//$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}	

	public function pdf_retgarantia_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
	
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		
	
		$monto = $cred['monto'];
		$pagare = $cred['idpagare'];
		
		
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha']);
	
		$idgrupo=$cred['idgrupo'];
		
		$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
		$where = array("idgrupo"=>$idgrupo);
		$gpo = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
		$gpo = $gpo[0];
		

		/* 2018-05-22
		$data = $this->base->querySelect("SELECT coalesce(sum(garantia),0) as total FROM (
			SELECT c.idcredito FROM ".$this->session->userdata('esquema')."creditos as c
				JOIN (SELECT idacreditado, fecha FROM ".$this->session->userdata('esquema')."creditos WHERE idcredito=".$idcredito.") as x ON c.idacreditado=x.idacreditado AND c.fecha<x.fecha
			ORDER BY c.fecha DESC limit 1) as c
			JOIN ".$this->session->userdata('esquema')."amortizaciones as a ON c.idcredito=a.idcredito", TRUE);
		*/
		
		$data = $this->base->querySelect("SELECT sum(comprometido) as total FROM ".$this->session->userdata('esquema')."get_creditos_resumen2(".$cred['idacreditado'].") where idcredito ='".$idcredito."'", TRUE);
		$total_recibo= $data[0]['total'];				

		
		if ($total_recibo==0){
			$monto_letra = 'cero';
		}else{
			$monto_letra = $this->numeroToLetras( number_format($total_recibo, 2, '.', ''));
		}
	
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">';
	
		$html.='<br>
			<table style="width:100%">
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>
				<tr>
					<th colspan="2" class="seccion-left">
						AUTORIZACION DE TRANSPASO POR LA CANTIDAD DE
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						'.$total_recibo.' 
					</th>		
					<th class="seccion-left">'.$monto_letra.'</th>
				</tr>
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>	
			</table>';
		$html.='<br><br><br>
			<div border=1>
				<table style="width:100%">
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
					<tr>
						<th colspan="3" class="seccion-left">
							POR CONCEPTO DE: TRANSPASO DE AHORRO COMPROMETIDO AL AHORRO CORRIENTE DE LA SOCIA
						</th>
					</tr>
					<tr>
						<th colspan="2" class="seccion-left">
							'.$cred['nombre'].'
						</th>		
						<th class="seccion-left">Número: '.$cred['idacreditado'].' </th>
					</tr>	
					<tr>
						<th class="seccion-left">
							DE LA COLMENA NUMERO: '.$gpo['colmena_numero'].'
						</th>		
						<th class="seccion-left">DENOMINADA: '.$gpo['colmena_nombre'].'
						</th>
						<th class="seccion-left">
							DEL GRUPO: '.$gpo['colmena_grupo'].'
						</th>		
					</tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>							
				</table>
			</div>';

		$html.='<br> <br>
			<div border=0>
				<table style="width:100%" border="0">
				<tr>
				<td></td>
				<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				<td align="center" width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				</tr>		

				<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">Promotor</td>
				<td></td>
				<td style="border-top: 1px solid"  align="center" width="25%">'.$cred['nombre'].'<br>Socia</td>
				<td></td>
				</tr>		
			</table> </div>';		


		$html.='
		</div>
		</body>
		</html>
		';
ob_clean();			
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		//$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
		
	public function pdf_retgarantia2_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
	
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","nomcolmena");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];
	
		$monto = $cred['monto'];
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha']);
	
		$idgrupo=$cred['idgrupo'];
		
		$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
		$where = array("idgrupo"=>$idgrupo);
		$gpo = $this->base->selectRecord($this->esquema."get_colmena_grupo", $fields, "", $where, "","", "", "", "","", TRUE);
		$gpo = $gpo[0];
		
			
		$data = $this->base->querySelect("SELECT sum(garantia) as total FROM (
			SELECT c.idcredito FROM ".$this->session->userdata('esquema')."creditos as c
				JOIN (SELECT idacreditado, fecha FROM ".$this->session->userdata('esquema')."creditos WHERE idcredito=".$idcredito.") as x ON c.idacreditado=x.idacreditado AND c.fecha<x.fecha
			ORDER BY c.fecha DESC limit 1) as c
			JOIN ".$this->session->userdata('esquema')."amortizaciones as a ON c.idcredito=a.idcredito", TRUE);
	
		$total_recibo= $data[0]['total'];				
		$monto_letra = $this->numeroToLetras( number_format($total_recibo, 2, '.', ''));

	
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">';
	
		$html.='<br>
			<table style="width:100%">
				<tr>
					<th colspan="2" class="seccion-left">
						AUTORIZACION DE TRANSPASO POR LA CANTIDAD DE
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						'.$total_recibo.' 
					</th>		
					<th class="seccion-left">'.$monto_letra.'</th>
				</tr>													
			</table>';
		$html.='<br><br><br>
			<div border=1>
			<table style="width:100%">
				<tr>
					<th colspan="3" class="seccion-left">
						POR CONCEPTO DE: TRANSPASO DE AHORRO COMPROMETIDO AL AHORRO CORRIENTE DE LA SOCIA
					</th>
				</tr>
				<tr>
					<th colspan="2" class="seccion-left">
						'.$cred['nombre'].'
					</th>		
					<th class="seccion-left">Número: '.$cred['idacreditado'].' </th>
				</tr>	
				<tr>
					<th class="seccion-left">
						DE LA COLMENA NUMERO: '.$gpo['colmena_numero'].'
					</th>		
					<th class="seccion-left">DENOMINADA: '.$gpo['colmena_nombre'].'
					</th>
					<th class="seccion-left">
						DEL GRUPO: '.$gpo['colmena_grupo'].'
					</th>		
				</tr>									
				<tr>
					<th colspan="3" class="seccion-right">
					
					</th>		
				</tr>													
			</table>
			</div>';

        $html.='<br> <br>
			<table style="width:100%"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center" width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			</tr>		



			<tr>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Promotor</td>
			<td></td>
			<td style="border-top: 1px solid"  align="center" width="25%">'.$cred['nombre'].'<br>Socia</td>
			<td></td>
			</tr>		
		</table>';		

		$html.='
		</div>
		</body>
		</html>
		';
	
ob_clean();		
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		//$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
		

	public function tableCreateCreditos($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		foreach($data as $key => $value) {
			$capital = $value['saldo_capital'];
			$html.='  <tr>';
			$html.='  <td>'.$value['numero'].'</td>';
			$html.='  <td>'.$value['fecha_vence'].'</td>';
			$html.='  <td>'.$capital.'</td>';
			$html.='  <td align="right">'.$value['interes'].'</td>';
			$html.='  <td align="right">'.$value['iva'].'</td>';
			$html.='  <td align="right">'.$value['capital'].'</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html.='  <td align="right">'.$value['total'].'</td>';
			/*
			foreach($value as $keydata => $valuedata)
				$html.='<td>'.$valuedata.'</td>';
			}
			*/
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}

	public function pdf_creditosfecha_get(){
		//$miFecha = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
	
		$data = $this->base->querySelect("SELECT c.idsucursal, c.fecha_pago, c.proy_observa, c.monto, c.nivel, a.acreditado
			FROM $this->session->userdata('esquema').creditos as c
				JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE fecha::date=current_date::date", TRUE);
	
		$total_recibo= $data[0]['monto'];				
		$monto_letra = $this->numeroToLetras( number_format($total_recibo, 2, '.', ''));

	
		$title = array("Fecha","Acreditado", "Nivel", "Monto", "Observacion");
		$tabla = '';
		//$tabla.= $this->tableCreateCreditos($title, $list);

		$header = $this->headerReport('CHECK LIST');
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CHECK LIST</h3>';

		//$html.='<div>'.$tabla.'</div>';			

		$html.='<br>
			<table style="width:100%">
				<tr>
					<th colspan="2" class="seccion-left">
						AUTORIZACION DE TRANSPASO POR LA CANTIDAD DE
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						
					</th>		
					<th class="seccion-left"></th>
				</tr>													
			</table>';

        $html.='<br> <br>
			<table style="width:100%"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center" width="25%"> <br><br><br>&nbsp;</td>
			<td></td>
			</tr>		



			<tr>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Promotor</td>
			<td></td>
			<td style="border-top: 1px solid"  align="center" width="25%"> <br>Socia</td>
			<td></td>
			</tr>		
		</table>';		

		$html.='
		</div>
		</body>
		</html>
		';
	
ob_clean();		
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		//$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
		




	public function pdf_emision_cheques_get(){
		$fecha = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$valores = $this->put('data')?$this->put('data', TRUE):array();

		//$fecha = "2018-03-12";
		//$fecha = date("l", mktime(0, 0, 0, 3, 12, 2018));
		
		//$fecha = '2018-03-12';
		$idsuc= $this->session->userdata('sucursal_id');

		//print_r($fecha);
		//die;

		/*
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];
		
		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		*/
		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}
		//$fecha = new DateTime($cred['fecha']);
		

		$fields = array("fecha", "idacreditado", "acreditado_nombre", "nivel", "monto", "total_garantia", "cheque_ref", "idgrupo", "colmena_numero", "colmena_grupo", "promotor", "proy_observa");
		$where = array("fecha"=>$fecha, "idsucursal"=>$idsuc);
		//$where = array("fecha"=>$fecha);
		$order_by = array(array('campo'=> 'fecha', 'direccion'=>	'asc'));
		//$amor = $this->base->selectRecord($this->session->userdata('esquema')."v_sol_emision_cheques", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."v_sol_emision_cheques", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		if ($this->session->userdata('esquema') === "fin."){
			$title = array("Fecha","Nombre", "Nivel", "Monto", "Garantia", "Importe", "Grupo", "Colmena", "Promotor", "Observaciones");
		}else{
			$title = array("Fecha","Nombre","S.C.", "Nivel", "Monto", "Garantia", "Pago en exceso", "Importe", "No. cheque", "Grupo", "Colmena", "Promotor", "Observaciones");
		}
		$tabla = '';


		$tabla.= $this->tableCreateEmisionCheques($title, $amor);

		//print_r($tabla);
		//die;

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">SOLICITUD DE EMISION DE CHEQUES</h3>
			<h3 align="center">Ruta '.$sucursal.'</h3>		
			<h3 align="center">Semana</h3>';		
		
		
		$html.='<div > </div>';
		$html.='<font size="10px">';
		$html.='<div>'.$tabla.'</div>';
		$html.='<font size="9px">';
/*
        $html.='<br> <br> <br> <br> 
			<table style="width:100%"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%">Entrega <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%">Vo, Bo. <br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%">Recibe <br><br><br>&nbsp;</td>
			<td></td>
			</tr>		

			<tr>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Contadora<br></td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Contadora general<br></td>
			<td></td>
			<td style="border-top: 1px solid" align="center"  width="25%">Coordinadora Administrativa<br></td>
			<td></td>
			</tr>		
		</table>';				
*/
		$html.='
		</div>
		</body>
		</html>
		';
		
		//print_r($html);
		//die;        
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'landscape');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	public function pdf_emision_cheques_year_get(){
		$year = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$valores = $this->put('data')?$this->put('data', TRUE):array();

		$datetime = new DateTime();				
		if ($year==="0"){
			$strYear = date("Y")-1;
		}else{
			$strYear = date("Y");
		}
		$fechaIni = '01/01/'.$strYear;
		$fechaFin = '31/12/'.$strYear;

		$idsuc= $this->session->userdata('sucursal_id');
		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}

		$query = "SELECT fecha, idacreditado, acreditado_nombre, nivel, monto, total_garantia, cheque_ref, idgrupo, colmena_numero, colmena_grupo, promotor, proy_observa 
		FROM ".$this->session->userdata('esquema')."v_sol_emision_cheques
		WHERE idsucursal='".$idsuc."' and fecha between '".$fechaIni."'::date and '".$fechaFin."'::date 
		ORDER BY fecha asc";

		$amor = $this->base->querySelect($query, TRUE);

		if ($this->session->userdata('esquema') === "fin."){
			$title = array("Fecha","Nombre", "Nivel", "Monto", "Garantia", "Importe", "Grupo", "Colmena", "Promotor", "Observaciones");
		}else{
			$title = array("Fecha","Nombre","S.C.", "Nivel", "Monto", "Garantia", "Pago en exceso", "Importe", "No. cheque", "Grupo", "Colmena", "Promotor", "Observaciones");
		}	
		$tabla = '';
		$tabla.= $this->tableCreateEmisionCheques($title, $amor);

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">SOLICITUD DE EMISION DE CHEQUES</h3>
			<h3 align="center">Ruta '.$sucursal.'</h3>		
			<h3 align="center">DEL '.$fechaIni.' AL '.$fechaFin.'</h3>';		
		
		
		//$html.='<div > </div>';
		$html.='<font size="9px">';
		$html.='<div style="font-size:9px;"> '.$tabla.' </div>';
		//$html.='<font size="9px">';
		$html.='
		</div>
		</body>
		</html>
		';
		
		print_r($html);
		die;        
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'landscape');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}

	public function pdf_prev_ahorro_get(){
		$fecha = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		//$valores = $this->put('data')?$this->put('data', TRUE):array();
		$idsuc= $this->session->userdata('sucursal_id');

		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}
		
		$fields = array("acreditadoid", "idsucursal", "acreditado", "idacreditado", "numero_cuenta", "fecha", "base", "interes", "idpoliza");
		//$where = array("fecha"=>$fecha, "idsucursal"=>$idsuc);
		$where = array("fecha"=>$fecha);
		$order_by = array(array('campo'=> 'acreditado', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_ahorro", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		//$title = array("Numero","Acreditado", "Cuenta", "Base", "Interes", "Poliza");
		$title = array("Numero","Acreditado", "Cuenta", "Base", "Interes");
		$tabla = '';

		//print_r($amor);
		//die;

		$tabla.= $this-> tableCreatProv($title, $amor);

		//print_r($tabla);
		//die;

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">PROVISION DE CUENTAS DE AHORRO</h3>
			<h3 align="center">FECHA: '.$fecha.'</h3>';		
		
		$html.='<div > </div>';
		$html.='<font size="9px">';
		$html.='<div>'.$tabla.'</div>';
		$html.='<font size="9px">';

		$html.='
		</div>
		</body>
		</html>
		';
		
		//print_r($html);
		//die;        
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));		
	}


	public function tableCreatProvSalto($title, $data) {
		$html='';
		$intCiclo = 0;
		foreach($data as $key => $value) {
			if ($intCiclo==0){
				$html.='<table style="width:80%" align="center">';
				$html.='  <tr>';
				foreach($title as $key => $value1) {
					$html.='    <th>'.$value1.'</th>';
				}
				$html.='  </tr>';
			}

			$intCiclo= $intCiclo + 1;
			$html.='  <tr>';
				$html.='  <td>'.$value['idacreditado'].'</td>';
				$html.='  <td>'.$value['acreditado'].'</td>';
				$html.='  <td>'.$value['numero_cuenta'].'</td>';
				$html.='  <td align="right">'.$value['base'].'</td>';
				$html.='  <td align="right">'.$value['interes'].'</td>';
				//$html.='  <td>'.$value['idpoliza'].'</td>';
			$html.='  </tr>';
			if ($intCiclo>50){
				$html.='</table> <br> <br>' ;
				$intCiclo=0;
			}
		}
		$html.='</table>';
		return $html;
	}


	public function tableCreatProv($title, $data) {
		$html='';
		$html.='<table style="width:80%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$intCiclo = 0;
		foreach($data as $key => $value) {
			$intCiclo= $intCiclo + 1;
			$html.='  <tr>';
				$html.='  <td>'.$value['idacreditado'].'</td>';
				$html.='  <td>'.$value['acreditado'].'</td>';
				$html.='  <td>'.$value['numero_cuenta'].'</td>';
				$html.='  <td align="right">'.$value['base'].'</td>';
				$html.='  <td align="right">'.$value['interes'].'</td>';
				//$html.='  <td>'.$value['idpoliza'].'</td>';
			$html.='  </tr>';
			if ($intCiclo>50){
				break;
			}
		}
		$html.='</table>';
		return $html;
	}

	


	public function tableCreateEmisionCheques($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$intPago=1;
		$saldoCapital=0;
		$pagoFijo=0;

		$totalMonto = 0.00;
		$totalGarantia =0.00;
		$totalImporte = 0;
		$isFIN = false;
		if ($this->session->userdata('esquema') === "fin."){
			$isFIN = true;
		}
		//$title = array("Fecha","Nombre","S.C.", "Nivel", "Monto", "Garantia", "Pago en exceso", "Importe", "No. cheque", "Grupo", "Colmena", "Promotor", "Observaciones");
		//$fields = array("fecha", "acreditado_nombre", "nivel", "monto", "total_garantia", "cheque_ref", "idgrupo", "colmena_numero", "colmena_grupo", "promotor", "proy_observa");
		foreach($data as $key => $value) {
			$fecha = date_create($value['fecha']);
			$fecha = date_format($fecha,'d/m/Y');

			$monto = $value['monto'];
			$garantia = $value['total_garantia'];
			$total = (float)($monto + $garantia + 0.00) ;
			
			$totalMonto = $totalMonto + $monto;
			$totalGarantia =$totalGarantia + $garantia;
			$totalImporte = $totalImporte + $total;
			
			$html.='  <tr>';
			$html.='  <td height="12" width="35">'.$fecha.'</td>';
			$html.='  <td >'.$value['idacreditado'].' - '.$value['acreditado_nombre'].'</td>';
			if ($isFIN===true){
				$html.='  <td width="15" align="right">'.$value['nivel'].'</td>';
				$html.='  <td width="33" align="right">'.number_format($monto,2).'</td>';
				$html.='  <td width="33" align="right">'.number_format($garantia,2).'</td>';
				$html.='  <td width="33" align="right">'.number_format($total,2).' </td>';
			}else{
				$html.='  <td width="10"> </td>';
				$html.='  <td width="15" align="right">'.$value['nivel'].'</td>';
				$html.='  <td width="33" align="right">'.number_format($monto,2).'</td>';
				$html.='  <td width="33" align="right">'.number_format($garantia,2).'</td>';
				$html.='  <td width="30" align="right"> </td>';
				$html.='  <td width="33" align="right">'.number_format($total,2).' </td>';
				$html.='  <td width="20" align="right">'.$value['cheque_ref'].'</td>';	
			}			
			$html.='  <td width="20" 1align="right">'.$value['colmena_grupo'].'</td>';
			$html.='  <td width="25" align="right">'.$value['colmena_numero'].'</td>';
			$html.='  <td width="90">'.$value['promotor'].'</td>';
			$html.='  <td >'.$value['proy_observa'].'</td>';
			$html.='  </tr>';
			$intPago = $intPago + 1;
		}
		$html.='  <tr>';
		$html.='  <td ></td>';
		$html.='  <td ></td>';
		if ($isFIN===true){
			$html.='  <td ></td>';
			$html.='  <td align="right"><b>'.number_format($totalMonto,2).'</b></td>';
			$html.='  <td align="right"><b>'.number_format($totalGarantia,2).'</b></td>';
			$html.='  <td align="right"><b>'.number_format($totalImporte,2).'</b></td>';
		}else{
			$html.='  <td ></td>';
			$html.='  <td></td>';
			$html.='  <td align="right"><b>'.number_format($totalMonto,2).'</b></td>';
			$html.='  <td align="right"><b>'.number_format($totalGarantia,2).'</b></td>';
			$html.='  <td> </td>';
			$html.='  <td align="right"><b>'.number_format($totalImporte,2).'</b></td>';
			$html.='  <td></td>';			
		}
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  </tr>';		
		

		$html.='</table>';
		return $html;
	}



	public function pdf_colmena_get(){
		$idcolmena = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$valores = $this->put('data')?$this->put('data', TRUE):array();

		$dato_colmena = $this->base->querySelect("SELECT l.numero, l.idsucursal, l.nombre, l.fecha_apertura,  p.idacreditado as idpresidente, coalesce(p.acreditado,'') as presidente, t.idacreditado as idtesorero, coalesce(t.acreditado,'') as tesorero, s.idacreditado as idsecretario, coalesce(s.acreditado,'') as secretario
			FROM col.colmenas as l
				JOIN col.colmena_cargo as c ON l.idcolmena=c.idcolmena
				LEFT JOIN public.get_acreditados as p ON c.idpresidente = p.acreditadoid
				LEFT JOIN public.get_acreditados as t ON c.idtesorero = t.acreditadoid
				LEFT JOIN public.get_acreditados as s ON c.idsecretario = s.acreditadoid
			WHERE l.idcolmena=".$idcolmena, TRUE);
		$dato_colmena = $dato_colmena[0];
		$sucursal ='ZIMATLAN';
		if ($dato_colmena['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}



		$acreditados = $this->base->querySelect("SELECT grupo_numero, grupo_nombre, idacreditado, nombre, cargo_grupo, orden
			FROM ".$this->esquema."get_acreditado_grupo 
			WHERE idcolmena=".$idcolmena." ORDER BY grupo_numero, orden", TRUE);
		$title = array("Posición","No. acreditada","Nombre","Cargo");
		$tabla = '';
		$tabla.= $this->tableGrupoColmena($title, $acreditados);


		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:12px;">
			<h3 align="center">COLMENA: '.$dato_colmena['numero'].' - '.$dato_colmena['nombre'].'</h3>
			</div>
			';
			
			$html.='
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						Promotor: 
					</th>
					<th></th>
					<th class="seccion-left">
						SUCURSAL: '.$dato_colmena['idsucursal'].' - '.$sucursal.'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Presidenta: '.$dato_colmena['idpresidente'].' - '.$dato_colmena['presidente'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Tesorera: '.$dato_colmena['idtesorero'].' - '.$dato_colmena['tesorero'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Secretaria: '.$dato_colmena['idsecretario'].' - '.$dato_colmena['secretario'].'
					</th>		
				</tr>	
			</table>';

		$html.='<br>';
		$html.='<font size="10px">';
		$html.='<div>'.$tabla.'</div>';
		$html.='<font>';

		$html.='
		</div>
		</body>
		</html>
		';
		
		//print_r($html);
		//die;        
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}

	public function tableGrupoColmena($title, $data) {
		$html='';
		$table='<table align="center" style="width:80%">';
		$titulo = '';
		foreach($title as $key => $value) {
			$titulo.='    <th>'.$value.'</th>';
		}
		$intGrupo=0;
		foreach($data as $key => $value) {
			$intGrupoTable = $value['grupo_numero'];
			if	($intGrupo != $intGrupoTable){
				if ($html != ""){
					$html.='</table>
					<br>';
				}
				$html.=$table;
				$html.='  <tr><td align="center" COLSPAN ="4"><b>'.$value['grupo_nombre'].'</b></td></tr>';
				$html.='  <tr>'.$titulo.'</tr>';
				$intGrupo=$intGrupoTable;
			}
			$html.='  <tr>';
			$html.='  <td align="right">'.$value['orden'].'</td>';
			$html.='  <td align="right">'.$value['idacreditado'].'</td>';
			$html.='  <td>'.$value['nombre'].'</td>';
			$html.='  <td>'.$value['cargo_grupo'].'</td>';
			$html.='  </tr>';
		}
		return $html;
	}


	public function html_datos_acre_get(){
		//$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idacreditado", "id_isis", "idsucursal", "nombre", "curp","ine","persona", "estado_civil", "fecha_nac", "edad", "sexo", "cp", "calle","localidad", "municipio", "aportacion_social", "actividad", "dependientes", "tipovivienda", "aguapot", "enerelec", "drenaje","colmena", "grupo", "telefono");
		$order_by = array(array('campo'=> 'idacreditado', 'direccion'=>	'asc'));
		$acre_datos = $this->base->selectRecord("get_acreditados_estadistica", $fields, "", "", "","", "", $order_by, "","", TRUE);

		$title = array("No","Isis","Suc","Nombre","CURP","INE","Persona","Edo.Civil","Fec.Nac.","Edad", "Sexo", "CP", "Calle","Localidad", "Municipio", "Ap.Soc.", "Actividad", "Dep.", "Vivienda", "Agua", "Elec.", "Drenaje","Colmena","Grupo", "Teléfono");
		$tabla= $this->tableDatosAcre($title, $acre_datos);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">DATOS DE SOCIAS</h3>';

		$html.='<br><br><div > </div>';
		$html.='<div style="font-size:9px;">';
		$html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

		print_r ($html);
		die;
	}


	public function tableDatosAcre($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		foreach($title as $key => $value) {
			$html.='    <th span 2>'.$value.'</th>';
		}
		$intGrupo=0;
		foreach($data as $key => $value) {
			$html.='  <tr ">';
			$fecha = new DateTime($value['fecha_nac']);
			$miFecha = date_format($fecha,'d/m/Y');

			$html.='  <td align="right">'.$value['idacreditado'].'</td>';
			$html.='  <td align="right">'.$value['id_isis'].'</td>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td>'.$value['nombre'].'</td>';
			$html.='  <td>'.$value['curp'].'</td>';
			$html.='  <td>'.$value['ine'].'</td>';
			$html.='  <td>'.$value['persona'].'</td>';
			$html.='  <td>'.$value['estado_civil'].'</td>';
			$html.='  <td>'.$miFecha.'</td>';
			$html.='  <td>'.$value['edad'].'</td>';
			$html.='  <td>'.$value['sexo'].'</td>';
			$html.='  <td>'.$value['cp'].'</td>';
			$html.='  <td>'.$value['calle'].'</td>';
			$html.='  <td>'.$value['localidad'].'</td>';
			$html.='  <td>'.$value['municipio'].'</td>';
			$html.='  <td>'.$value['aportacion_social'].'</td>';
			$html.='  <td>'.$value['actividad'].'</td>';
			$html.='  <td>'.$value['dependientes'].'</td>';
			$html.='  <td>'.$value['tipovivienda'].'</td>';
			$html.='  <td>'.$value['aguapot'].'</td>';
			$html.='  <td>'.$value['enerelec'].'</td>';
			$html.='  <td>'.$value['drenaje'].'</td>';
			$html.='  <td>'.$value['colmena'].'</td>';
			$html.='  <td>'.$value['grupo'].'</td>';
			$html.='  <td>'.$value['telefono'].'</td>';
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}

	public function html_sin_datos_acre_get(){
		//$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idacreditado", "id_isis", "idsucursal", "nombre", "persona", "estado_civil", "fecha_nac", "edad", "sexo", "cp", "localidad", "municipio", "aportacion_social", "actividad", "dependientes", "tipovivienda", "aguapot", "enerelec", "drenaje","colmena", "grupo", "telefono");
		$order_by = array(array('campo'=> 'idacreditado', 'direccion'=>	'asc'));
		$acre_datos = $this->base->selectRecord("get_acreditados_estadistica_no", $fields, "", "", "","", "", $order_by, "","", TRUE);

		$title = array("No","Isis","Suc","Nombre","Persona","Edo.Civil","Fec.Nac.","Edad", "Sexo", "CP", "Localidad", "Municipio", "Ap.Soc.", "Actividad", "Dep.", "Vivienda", "Agua", "Elec.", "Drenaje","Colmena","Grupo","Teléfono");
		$tabla= $this->tableDatosAcre($title, $acre_datos);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">DATOS DE SOCIAS</h3>';

		$html.='<br><br><div > </div>';
		$html.='<div style="font-size:9px;">';
		$html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

		print_r ($html);
		die;
	}


	public function pdf_colmenas_dir_get(){
		$tipo = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$reporte_title = "DIRECTORIO DE COLMENAS";
		if ($this->session->userdata('sucursal_id')==='01'){
			$reporte_suc = "MIAHUATLAN";
		}else{
			$reporte_suc = "OAXACA";
		}

		$valores = $this->put('data')?$this->put('data', TRUE):array();

		$title = array("Núm.", "Colmena", "Dia", "Promotor", "Hora", "Fecha apertura", "Fecha cierre", "Empresa", "Municipio", "Colonia", "Direccion", "Referencia", "Presidenta", "Celular", "Secretaria", "Celular");
		if ($tipo==="3"){
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' and fechacierre is null ";
			$title = array("Núm.", "Colmena", "Dia", "Promotor", "Hora", "Fecha apertura", "Empresa", "Municipio", "Colonia", "Direccion", "Referencia", "Presidenta", "Celular", "Secretaria", "Celular");
			$reporte_title.=" ACTIVAS";
		}else if ($tipo==="4"){
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' and not fechacierre is null ";
			$reporte_title.=" INACTIVAS";
		}else{
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' ";
		}
		$idsuc= $this->session->userdata('sucursal_id');
		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}

		$query = "SELECT numero, nombre, dia_text, promotor, horainicio, fecha_apertura::date, fechacierre::date, empresa, d_mnpio, d_asenta, direccion, direccion_ref,presidenta,presi_cell,secretaria,secre_cell from col.v_colmenas_directorio ".$where." ORDER BY numero";
		$amor = $this->base->querySelect($query, TRUE);
		
		$tabla = '';
		$tabla.= $this->tableCreateColmenas($title, $amor, $tipo);

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center"> '.$reporte_title.' <br> '.$reporte_suc.' </h3>
		';		
		
		
		//$html.='<div > </div>';
		$html.='<font size="10px">';
		$html.='<div style="font-size:9px;"> '.$tabla.' </div>';
		//$html.='<font size="9px">';
		$html.='
		</div>
		</body>
		</html>
		';
		
		print_r($html);
		die;
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'landscape');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));

	}


	public function tableCreateColmenas($title, $data, $tipo) {
		/*
		$html='';
		$html.='<table style="width:100%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		*/
		$html='';
		//$salto=25;
		$table='';
		$table.='<table style="width:100%" align="center">';
		$table.='  <tr>';
		foreach($title as $key => $value) {
			$table.='    <th>'.$value.'</th>';
		}
		$table.='  </tr>';		
		$intCiclo = 0;

		if ($data){
			foreach($data as $key => $value) {
				if($intCiclo===0){
					$html.=$table;
				}
				$intCiclo= $intCiclo + 1;
				$html.='  <tr>';
					$html.='  <td align="right">'.$value['numero'].'</td>';
					$html.='  <td>'.$value['nombre'].'</td>';
					$html.='  <td>'.$value['dia_text'].'</td>';
					$html.='  <td>'.$value['promotor'].'</td>';
					$html.='  <td>'.$value['horainicio'].'</td>';
					$html.='  <td>'.$value['fecha_apertura'].'</td>';
					if ($tipo!=3){
						if ($value['fechacierre'] === null){
							$html.='  <td> </td>';
						}else{
							$html.='  <td>'.$value['fechacierre'].'</td>';
						}	
					}
					$html.='  <td>'.$value['empresa'].'</td>';
					$html.='  <td>'.$value['d_mnpio'].'</td>';
					$html.='  <td>'.$value['d_asenta'].'</td>';
					$html.='  <td>'.$value['direccion'].'</td>';
					if ($value['direccion_ref'] === null){
						$html.='  <td> </td>';
					}else{
						$html.='  <td>'.$value['direccion_ref'].'</td>';
					}	
					if ($value['presidenta'] === null){
						$html.='  <td> </td>';
					}else{
						$html.='  <td>'.$value['presidenta'].'</td>';
					}	
					if ($value['presi_cell'] === null){
						$html.='  <td> </td>';
					}else{
						$html.='  <td>'.$value['presi_cell'].'</td>';
					}	
					if ($value['secretaria'] === null){
						$html.='  <td> </td>';
					}else{
						$html.='  <td>'.$value['secretaria'].'</td>';
					}	
					if ($value['secre_cell'] === null){
						$html.='  <td> </td>';
					}else{
						$html.='  <td>'.$value['secre_cell'].'</td>';
					}	
				$html.='  </tr>';
				/*
				if ($intCiclo ===$salto){
					$html.='</table> <br><br><br><br>';
					break;
					$salto = $salto + 25;
					$html.=$table;
				}
				*/
			}
		}
		$html.='</table><br><br>';
		return $html;
	}

	public function pdf_creditos_activos_get(){
		$tipo = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$reporte_title = "CREDITOS ACTIVOS";
		if ($this->session->userdata('sucursal_id')==='01'){
			$reporte_suc = "MIAHUATLAN";
		}else{
			$reporte_suc = "OAXACA";
		}

		$valores = $this->put('data')?$this->put('data', TRUE):array();

		$title = array("Núm.", "Colmena", "Grupo", "Acreditada", "Suc.", "Credito", "Pagare", "Empresa");
		//col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa
		/*
		if ($tipo==="3"){
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' and fechacierre is null ";
			$title = array("Núm.", "Colmena", "Dia", "Promotor", "Hora", "Fecha apertura", "Empresa", "Municipio", "Colonia", "Direccion", "Referencia", "Presidenta", "Celular", "Secretaria", "Celular");
			$reporte_title.=" ACTIVAS";
		}else if ($tipo==="4"){
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' and not fechacierre is null ";
			$reporte_title.=" INACTIVAS";
		}else{
			$where = " where idsucursal='".$this->session->userdata('sucursal_id')."' ";
		}*/
		$idsuc= $this->session->userdata('sucursal_id');
		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}


		$where = " where idsucursal='".$this->session->userdata('sucursal_id')."'";
		if ($tipo==="1"){
			$query = "SELECT col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa from ".$this->session->userdata('esquema')."v_creditos_vigentes ".$where." ORDER BY idsucursal, col_numero, grupo_nombre";
		}else if ($tipo==="2"){
			$query = "SELECT col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa from ".$this->session->userdata('esquema')."v_creditos_vigentes ".$where." ORDER BY idsucursal, empresa, col_numero, grupo_nombre";
		}else{
			$title = array("Empresa","Núm.", "Colmena", "Grupo", "Acreditada", "Suc.", "Credito", "Pagare", "Empresa","Dirección","Socia","Teléfono");
			$query = "SELECT sistema, col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa, direccion, idacreditado, celular from public.v_creditos_vigentes ORDER BY sistema, empresa, idsucursal, col_numero, grupo_nombre";
		}

		$amor = $this->base->querySelect($query, TRUE);

		$tabla = '';
		$tabla.= $this->tableCreateCreditoVigente($title, $amor, $tipo);

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center"> '.$reporte_title.' <br> '.$reporte_suc.' </h3>
		';		
		
		
		//$html.='<div > </div>';
		$html.='<font size="11px">';
		$html.='<div style="font-size:9px;"> '.$tabla.' </div>';
		//$html.='<font size="9px">';
		$html.='
		</div>
		</body>
		</html>
		';
		
		print_r($html);
		die;
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'landscape');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));

	}

	public function tableCreateCreditoVigente($title, $data, $tipo) {
		$html='';
		//$salto=25;
		$table='';
		$table.='<table style="width:100%" align="center">';
		$table.='  <tr>';
		foreach($title as $key => $value) {
			$table.='    <th>'.$value.'</th>';
		}
		$table.='  </tr>';		
		$intCiclo = 0;
		
		$renglon='  <tr>';
		foreach($title as $key => $value) {
			$renglon.='    <td>&nbsp;</td>';
		}
		$renglon.='  </tr>';		
		
		$empresa='';
		$antSis = '';
		$antEmp = '';
		$antCol = '';

		if ($data){
			foreach($data as $key => $value) {
				if($intCiclo===0){
					$html.=$table;
				}
				$intCiclo= $intCiclo + 1;
				$empresa = $value['empresa'];
				if ($antEmp===''){
					$antEmp = $value['empresa'];
				}
				if ($antCol===''){
					$antCol = $value['col_numero'];
				}
				if ($tipo==="3"){
					if ($antSis===''){
						$antSis = $value['sistema'];
					}
					if ($antSis<>$value['sistema']){
						$antSis = $value['sistema'];
						$html.=$renglon;
					}
				}
				if ($tipo==="2"){
					if ($antEmp<>$value['empresa']){
						$antEmp = $value['empresa'];
						$html.=$renglon;
					}
				}
				if ($tipo==="1"){
					if ($antCol<>$value['col_numero']){
						$antCol = $value['col_numero'];
						$html.=$renglon;
					}
				}

				$html.='  <tr>';
					//col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa
					if ($tipo==="3"){
						$html.='  <td>'.$value['sistema'].'</td>';
					}					
					$html.='  <td align="right">'.$value['col_numero'].'</td>';
					$html.='  <td>'.$value['col_nombre'].'</td>';
					$html.='  <td>'.$value['grupo_nombre'].'</td>';
					$html.='  <td>'.$value['nombre'].'</td>';
					$html.='  <td>'.$value['idsucursal'].'</td>';
					$html.='  <td>'.$value['idcredito'].'</td>';
					$html.='  <td>'.$value['idpagare'].'</td>';
					if ($empresa=='F'){
						$html.='  <td>Fincomunidad</td>';
					}else if ($empresa=='B'){
						$html.='  <td>Bancomunidad</td>';
					}else{	
						$html.='  <td>AMA</td>';
					}
						if ($tipo==="3"){
						$html.='  <td>'.$value['direccion'].'</td>';
						$html.='  <td>'.$value['idacreditado'].'</td>';
						$html.='  <td>'.$value['celular'].'</td>';
						}
					
					$html.='  </tr>';
				}
		}
		$html.='</table><br><br>';
		return $html;
	}
	
	
	public function pdf_credito_prov_val_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$idsuc= $this->session->userdata('sucursal_id');

		$sucursal ='ZIMATLAN';
		if ($idsuc==='02'){
			$sucursal ='OAXACA';
		}
		
		$fields = array("idsucursal", "acreditadoid", "acreditado", "idcredito", "dia", "pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "int_pag", "iva_pag", "factor", "pago_total_r", "pago_capital_r", "capital_saldo_r", "saldo_actual_r", "interes_r", "iva_r", "int_pag_r", "iva_pag_r", "factor_r");
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'dia', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_credito_valida", $fields, "", $where, "","", "", $order_by, "","", TRUE);
		$title = array("Fecha prov.", "Suc.",
			"Capital vigente","Pago total","Pago capital","Int. pag","IVA pag","Saldo capital","Interes diario","IVA diario", 
			"Capital vigente","Pago total","Pago capital","Int. pag","IVA pag","Saldo capital","Interes diario","IVA diario");

		$tabla = '';
		$tabla.= $this-> tableCreatProv_real($title, $amor);
		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">PROVISION CREDITO - VALIDAR <BR> CREDITO: '.$idcredito.' </h3>';
		
		$html.='<font size="9px">';
		$html.='<div style="font-size:9px;">'.$tabla.'</div>';
		$html.='<font size="9px">';

		$html.='
		</div>
		</body>
		</html>
		';
		
		print_r($html);
		die;        
ob_clean();	
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));		
	}


	public function tableCreatProv_real($title, $data) {
		$html='';
		$html.='<table style="width:100%" align="center">';
	   $html.='  <tr>';
	   $html.='  	<th align="center" colspan="2">PROVISION</th>';
	   $html.='  	<th align="center" colspan="8">ALMACENADO EN SISTEMA</th>';
	   $html.='  	<th align="center" colspan="8">NUEVA PROVISION</th>';
	   $html.='  </tr>';

		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$intCiclo = 0;
		foreach($data as $key => $value) {
			$intCiclo= $intCiclo + 1;
			$html.='  <tr>';
				$html.='  <td>'.$value['dia'].'</td>';
				$html.='  <td>'.$value['idsucursal'].'</td>';

				//"pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "int_pag", "iva_pag, factor", "pago_total_r", "pago_capital_r", "saldo_capital_r", "saldo_actual_r", "interes_r", "iva_r", "int_pag_r", "iva_pag_r", "factor_r"

				$html.='  <td align="right">'.number_format($value['capital_saldo'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['pago_total'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['pago_capital'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['int_pag'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['iva_pag'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['saldo_actual'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['interes'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['iva'],2).'</td>';

				$html.='  <td align="right">'.number_format($value['capital_saldo_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['pago_total_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['pago_capital_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['int_pag_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['iva_pag_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['saldo_actual_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['interes_r'],2).'</td>';
				$html.='  <td align="right">'.number_format($value['iva_r'],2).'</td>';
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}


	

}






