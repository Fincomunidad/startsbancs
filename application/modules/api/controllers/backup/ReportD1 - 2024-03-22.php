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
		include_once(APPPATH . 'modules/api/controllers/Funciones.php');
		$this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');

	}



	// SOLICITUD DE CRÉDITO ACTUALIZADO -- 18-08-2023
	public function pdf_solicitud_credito_get()
	{
		$idcredito = $this->uri->segment(4);

		// DATOS DEL CRÉDITO
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idpersona", "nombre", "tipo", "edocivil", "edocivil_nombre", "sexo", "idactividad", "actividad_nombre", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "num_pagos");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));

		$fecha = new DateTime($cred['fecha']);

		// DATOS DE LA COLMENA
		$idgrupo = $cred['idgrupo'];
		$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
		$where = array("idgrupo" => $idgrupo);
		$gpo = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$gpo = $gpo[0];

		//Obteniendo el nombre del promotor que atiende la colmena
		$idcolmena = $cred['idcolmena'];
		$fields = array("numero", "nombre", "promotor");
		$where = array("idcolmena" => $idcolmena);
		$colmena = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$colmena = $colmena[0];
		$col_numero = $colmena['numero'];
		$col_nombre = $colmena['nombre'];
		$promotor = $colmena['promotor'];

		// DATOS AVAL DE GRUPO
		$fields = array("nombre", $this->esquema . "get_cargo_grupo(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid" => $cred['idaval1']);
		$aval1 = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$aval1 = $aval1[0];
		$aval1Text = $aval1['cargo'] . " de grupo";

		// DATOS DE AVAL DE COLMENA (Se usará en caso de no seleccionar aval de grupo)
		$fields = array("nombre", $this->esquema . "get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid" => $cred['idaval2']);
		$aval2 = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$aval2 = $aval2[0];
		$aval2Text = $aval2['cargo'] . " de colmena";

		$avalText = $aval1Text;
		$aval = $aval1['nombre'];
		if ($aval1 == '') {
			$aval = $aval2['nombre'];
			$avalText = $aval2Text;
		}

		//EN CASO DE NO AGREGAR NINGÚN AVAL
		if ($aval == '') {
			$avalText = 'Presidenta de grupo';
		}

		// DATOS PERSONA
		$idacreditado = $cred['idacreditado'];
		$persona = $this->base->querySelect("SELECT celular, curp, rfc, fecha_nac, tipovivienda, vine, direccion2
		FROM public.acreditado A INNER JOIN 
		public.personas P ON A.idpersona = P.idpersona
		INNER JOIN public.persona_domicilio PD ON PD.idpersona = P.idpersona 
		WHERE A.idacreditado = " . $cred['idacreditado'], TRUE);
		$persona = $persona[0];
		// Si la vigencia del ine = 0, el campo se queda en blanco
		$persona['vine'] == '0' ? $persona['vine'] = '' : '';

		//Obteniendo la edad de la persona a la fecha de solicitud del crédito
		$fechaNacimiento = new DateTime($persona['fecha_nac']);

		$dif = $fechaNacimiento->diff($fecha);
		$edad = $dif->y;

		$tipovivienda = $persona['tipovivienda'];
		switch ($tipovivienda) {
			case '1':
				$tipovivienda = 'Propia';
				break;
			case '2':
				$tipovivienda = 'Rentada';
				break;
			case '3':
				$tipovivienda = 'Familiar';
				break;
			case '4':
				$tipovivienda = 'Prestada';
				break;
			case '5':
				$tipovivienda = 'Otro';
				break;
		}

		$header = $this->headerReport('');
		$html = $header . '
			<div style="font-size:12px;">
				<h2 align="center">SOLICITUD DE CRÉDITO</h2>
				<p align="right">Fecha de solicitud: <strong>' . date_format($fecha, 'd/m/Y') . '</strong></p>
				<h3 style="margin: 25px 0 10px 0;">1. DATOS GENERALES DE LA SOLICITANTE</h3>';
		$html .= '
				<table style="width:100%; margin: 0 0 25px 0;">
					<tr class="seccion">
						<td colspan="3" style="padding: 8px;">Nombre completo: <strong>' . $cred['nombre'] . '</strong></td>
						<td style="padding: 8px;">No. Socia: <strong>' . $cred['idacreditado'] . '<strong></td>
					</tr>
					<tr class="seccion">
						<td colspan="2" style="padding: 8px;">CURP: <strong>' . $persona['curp'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">RFC: <strong>' . $persona['rfc'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">Edad: <strong>' . $edad . ' años</strong></td>
					</tr>						
					<tr class="seccion">
						<td colspan="2" style="padding: 8px;">Estado civil: <strong>' . $cred['edocivil_nombre'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">Teléfono: <strong>' . $persona['celular'] . '</strong></td>
						<td colspan="1" style="padding: 8px;">Vig. INE: <strong>' . $persona['vine'] . '</strong></td>
					</tr>												
					<tr class="seccion">
						<td colspan="4" style="padding: 8px;">Domicilio: <strong>' . $cred['direccion'] . '</strong></td>
					</tr>
					<tr class="seccion">
					<td colspan="1" style="padding: 8px;">Tipo de residencia: <strong>' . $tipovivienda . '</strong></td>
						<td colspan="3" style="padding: 8px;">Referencia: <strong>' . mb_strtoupper($persona['direccion2']) . '</strong></td>
					</tr>						
					<tr class="seccion">
						<td colspan="1" style="padding: 8px;">No. Colmena: <strong>' . $gpo['colmena_numero'] . '</strong></td>
						<td colspan="2" style="padding: 8px;">Nombre: <strong>' . $gpo['colmena_nombre'] . '</strong></td>
						<td colspan="1" style="padding: 8px;">Grupo: <strong>' . $gpo['colmena_grupo'] . '</strong></td>
					</tr>						
				</table>';
		$html .= '
				<h3 style="margin: 25px 0 10px 0;">2. DATOS DE LA ACTIVIDAD PRODUCTIVA</h3>';
		$html .= '
				<table style="width:100%; margin: 15px 0;">
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Principal fuente de ingresos: <strong>' . $cred['actividad_nombre'] . '</strong></td>
					</tr>
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Propósito del crédito: <strong>' . mb_strtoupper($cred['proy_nombre']) . '</strong></td>
					</tr>
					<tr class="seccion">
						<td style="padding: 8px;" colspan="2">Monto solicitado: <strong>$' . number_format($monto, 2, '.', ',') . ' (' . $monto_letra . ')</strong></td>
						<td style="padding: 8px;" colspan="1">Plazo: <strong>' . $cred['num_pagos'] . ' semanas</strong></td>
					</tr>						
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Lugar donde se realizará la actividad: <strong>' . mb_strtoupper($cred['proy_lugar']) . '</strong></td>
					</tr>												
					<tr>
						<td  class="seccion-center" style="padding: 8px;" colspan="3">Descripción del uso del crédito:</td>
					</tr>						
					<tr> 
						<td style="padding: 8px;" colspan="3"><strong>' . mb_strtoupper($cred['proy_descri']) . '</strong><br><br>&nbsp;</td> 
					</tr>											
				</table>
				<p style="font-size: 11px">
					Declaro bajo protesta de decir la verdad que la información aquí asentada es cierta y que el origen de los fondos en los productos y servicios depositados, proceden de fuentes lícitas, así mismo conozco que el permitir a un tercero el uso del crédito sin haberlo declarado, u ocultando o falseando información, o actuando como prestanombres de un tercero, puede dar lugar a que hagan uso indebido del crédito, lo que a su vez podría llegar a constituir la comisión de un delito.
					<br>
					Autorizo compartir mi expediente de identificación, así como demás documentación e información financiera, comercial, operativa, de historial o información crediticia y de cualquier otra naturaleza que le sea proporcionada por mi o por terceros con mi autorización a cualquiera de las entidades asociadas.
					<br>
					Con la firma de esta Solicitud expreso mi conocimiento y conformidad con lo estipulado en las declaraciones y cláusulas del contrato integrado a este documento.
				</p>
			</div>
			';
		$html .= '
			<table style="width:100%; margin: 55px 0 0 0; font-size:12px;" border="0">			
				<tr >
					<td style="border-top: 1px solid" align="center" width="25%">Promotor (a)</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">' . ucfirst(strtolower($avalText)) . '</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Socia</td>
				</tr>
				<tr >
					<td align="center" width="25%">' . mb_strtoupper($promotor) . '</td>
					<td></td>
					<td align="center" width="25%">' . $aval . '</td>
					<td></td>
					<td align="center" width="25%">' . $cred['nombre'] . '</td>
				</tr>				
			</table>';
		$html .= '
		
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
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
		$documento = $this->dompdf->stream("Solicitud de crédito.pdf", array("Attachment" => 0));
	}


	//2019-04-09 Integración credito individual
	// SOLICITUD DE CRÉDITO INDIVIDUAL ACTUALIZADO - 18-08-2023
	public function pdf_solicitud_credito_ind_get()
	{
		$idcredito = $this->uri->segment(4);

		// DATOS DEL CRÉDITO
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "num_pagos", "idpersona", "nombre", "tipo", "edocivil", "edocivil_nombre", "sexo", "idactividad", "actividad_nombre", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "iva");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito_ind", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));

		$fecha = new DateTime($cred['fecha']);

		//OBTENIENDO EL NOMBRE DEL USUARIO QUE SOLICITÓ EL CRÉDITO
		$idusuario = $cred['usuario'];
		$usuario = $this->base->querySelect("SELECT first_name, last_name
		FROM security.users U WHERE U.id = " . $idusuario, TRUE);
		$usuario = $usuario[0];
		$usuario = $usuario['first_name'] . ' ' . $usuario['last_name'];

		// DATOS DE LA COLMENA
		$idgrupo = $cred['idgrupo'];
		if ($idgrupo === "0") {
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
			$aval_nombre = "";
			$aval_cargo = "";
		} else {
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
			$where = array("idgrupo" => $idgrupo);
			$gpo = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
			$gpo = $gpo[0];
			$col_nombre = $gpo['colmena_nombre'];
			$col_numero = $gpo['colmena_numero'];
			$col_grupo = $gpo['colmena_grupo'];
		}

		// DATOS PERSONA
		$idacreditado = $cred['idacreditado'];
		$persona = $this->base->querySelect("SELECT celular, curp, rfc, fecha_nac, tipovivienda, vine, direccion2
		FROM public.acreditado A INNER JOIN 
		public.personas P ON A.idpersona = P.idpersona
		INNER JOIN public.persona_domicilio PD ON PD.idpersona = P.idpersona 
		WHERE A.idacreditado = " . $cred['idacreditado'], TRUE);
		$persona = $persona[0];
		// Si la vigencia del ine = 0, el campo se queda en blanco
		$persona['vine'] == '0' ? $persona['vine'] = '' : '';

		//Obteniendo la edad de la persona a la fecha de solicitud del crédito
		$fechaNacimiento = new DateTime($persona['fecha_nac']);

		$dif = $fechaNacimiento->diff($fecha);
		$edad = $dif->y;

		$tipovivienda = $persona['tipovivienda'];
		switch ($tipovivienda) {
			case '1':
				$tipovivienda = 'Propia';
				break;
			case '2':
				$tipovivienda = 'Rentada';
				break;
			case '3':
				$tipovivienda = 'Familiar';
				break;
			case '4':
				$tipovivienda = 'Prestada';
				break;
			case '5':
				$tipovivienda = 'Otro';
				break;
		}

		$header = $this->headerReport('');
		$html = $header . '
			<div style="font-size:12px;">
				<h2 align="center">SOLICITUD DE CRÉDITO</h2>
				<p align="right">Fecha de solicitud: <strong>' . date_format($fecha, 'd/m/Y') . '</strong></p>
				<h3 style="margin: 25px 0 10px 0;">1. DATOS GENERALES DE LA SOLICITANTE</h3>';
		$html .= '
				<table style="width:100%; margin: 0 0 25px 0;">
					<tr class="seccion">
						<td colspan="3" style="padding: 8px;">Nombre completo: <strong>' . $cred['nombre'] . '</strong></td>
						<td style="padding: 8px;">No. Socia: <strong>' . $cred['idacreditado'] . '<strong></td>
					</tr>
					<tr class="seccion">
						<td colspan="2" style="padding: 8px;">CURP: <strong>' . $persona['curp'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">RFC: <strong>' . $persona['rfc'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">Edad: <strong>' . $edad . ' años</strong></td>
					</tr>						
					<tr class="seccion">
						<td colspan="2" style="padding: 8px;">Estado civil: <strong>' . $cred['edocivil_nombre'] . ' </strong></td>
						<td colspan="1" style="padding: 8px;">Teléfono: <strong>' . $persona['celular'] . '</strong></td>
						<td colspan="1" style="padding: 8px;">Vig. INE: <strong>' . $persona['vine'] . '</strong></td>
					</tr>												
					<tr class="seccion">
						<td colspan="4" style="padding: 8px;">Domicilio: <strong>' . $cred['direccion'] . '</strong></td>
					</tr>
					<tr class="seccion">
					<td colspan="1" style="padding: 8px;">Tipo de residencia: <strong>' . $tipovivienda . '</strong></td>
						<td colspan="3" style="padding: 8px;">Referencia: <strong>' . $persona['direccion2'] . '</strong></td>
					</tr>						
					<tr class="seccion">
						<td colspan="1" style="padding: 8px;">No. Colmena: <strong>' . $col_numero . '</strong></td>
						<td colspan="2" style="padding: 8px;">Nombre: <strong>' . $col_nombre . '</strong></td>
						<td colspan="1" style="padding: 8px;">Grupo: <strong>' . $col_grupo . '</strong></td>
					</tr>						
				</table>';
		$html .= '
				<h3 style="margin: 25px 0 10px 0;">2. DATOS DE LA ACTIVIDAD PRODUCTIVA</h3>';
		$html .= '
				<table style="width:100%; margin: 15px 0;">
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Principal fuente de ingresos: <strong>' . $cred['actividad_nombre'] . '</strong></td>
					</tr>
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Propósito del crédito: <strong>' . $cred['proy_nombre'] . '</strong></td>
					</tr>
					<tr class="seccion">
						<td style="padding: 8px;" colspan="2">Monto solicitado: <strong>$' . number_format($monto, 2, '.', ',') . ' (' . $monto_letra . ')</strong></td>
						<td style="padding: 8px;" colspan="1">Número de pagos: <strong>' . $cred['num_pagos'] . '</strong></td>
					</tr>						
					<tr class="seccion">
						<td style="padding: 8px;" colspan="3">Lugar donde se realizará la actividad: <strong>' . $cred['proy_lugar'] . '</strong></td>
					</tr>												
					<tr>
						<td  class="seccion-center" style="padding: 8px;" colspan="3">Descripción del uso del crédito:</td>
					</tr>						
					<tr> 
						<td style="padding: 8px;" colspan="3"><strong>' . $cred['proy_descri'] . '</strong><br><br>&nbsp;</td> 
					</tr>											
				</table>
				<p style="font-size: 11px">
					Declaro bajo protesta de decir la verdad que la información aquí asentada es cierta y que el origen de los fondos en los productos y servicios depositados, proceden de fuentes lícitas, así mismo conozco que el permitir a un tercero el uso del crédito sin haberlo declarado, u ocultando o falseando información, o actuando como prestanombres de un tercero, puede dar lugar a que hagan uso indebido del crédito, lo que a su vez podría llegar a constituir la comisión de un delito.
					<br>
					Autorizo compartir mi expediente de identificación, así como demás documentación e información financiera, comercial, operativa, de historial o información crediticia y de cualquier otra naturaleza que le sea proporcionada por mi o por terceros con mi autorización a cualquiera de las entidades asociadas.
					<br>
					Con la firma de esta Solicitud expreso mi conocimiento y conformidad con lo estipulado en las declaraciones y cláusulas del contrato integrado a este documento.
				</p>
			</div>';
		$html .= '
			<table style="width:100%; margin: 55px 0 0 0; font-size:12px;" border="0">			
				<tr >
					<td style="border-top: 1px solid" align="center" width="35%">Gerente de sucursal</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="35%">Socia</td>
				</tr>				
				<tr >
					<td align="center" width="35%">' . mb_strtoupper($usuario) . '</td>
					<td></td>
					<td align="center" width="35%">' . $cred['nombre'] . '</td>
				</tr>				
			</table>';
		$html .= '
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
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
		$documento = $this->dompdf->stream("Solicitud de.pdf", array("Attachment" => 0));
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
			FROM ".$this->esquema."amortizaciones WHERE idcredito=".$idcredito." ORDER BY fecha_vence DESC limit 1", TRUE);
		$cred_ven= $cred_ven[0];
		$fecha_ven = new DateTime($cred_ven['fecha_vence']);
		/*
		print_r($cred_ven['fecha_vence']);
		die;
		*/
		
		//17-07-2023 CONDICIÓN EN CASO DE QUE NO HAYA AVAL 1 SE ASIGNE EL AVAL 2
		// Se aplicó el 28-12-2023
		/*$seleccion_aval = '';
		if ($aval1 == '') {
			$seleccion_aval = $aval2[0]['nombre'];
		}*/

		
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
				<!--<td style="border-top: 1px solid" align="center" width="30%">'.$seleccion_aval.'</td>-->
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

	//2022-08-10
	public function pdf_convenioban_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena","idaval1", "idaval2", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
		$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		$cred = $cred[0];

		$idPersona = $cred['idpersona'];
		$monto = $cred['monto'];
		$nombre = $cred['nombre'];
		$domicilio = $cred['direccion'];
		$actividad = $cred['proy_nombre'];
		$idcolmena = $cred['idcolmena'];
		$monto_letra = $this->numeroToLetras( number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($cred['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);

		$fields = array("numero", "nombre", "promotor");
		$where = array("idcolmena"=>$idcolmena);
		$colmena = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "","", "", "", "","", TRUE);
		$colmena = $colmena[0];
		$col_numero = $colmena['numero'];
		$col_nombre = $colmena['nombre'];
		$col_promotor = $colmena['promotor'];

		$fields = array("estado", "municipio", "colonia", "direccion1");
		$where = array("idpersona"=>$idPersona);
		$domi = $this->base->selectRecord("public.v_persona_domicilio", $fields, "", $where, "","", "", "", "","", TRUE);
		$domi = $domi[0];
		$estado = $domi['estado'];
		$municipio = $domi['municipio'];
		$colonia = $domi['colonia'];

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

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CONVENIO</h3>';		
        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr class="seccion">
					<th align="left">
						<!--Convenio No.: '.$idcredito.'-->
					</th>
				</tr>
				<tr class="seccion">
					<th align="right">
						'.$pagare.'
					</th>		
				</tr>				
			</table>';
		$html.='<div > </div>';
		$html.='<font size="12px">';
		$html.='<br>';
		$html.='<font size="12px">';
		$html.='<br>
		<p>Convenio celebrado entre el '.$empresa.' representado en este acto por el 
			C. Mario Enrique Rendón Hernández y por la otra parte 
			como prestataria a la Sra. '.$nombre.'.</p>
		<p>Las “partes” contratantes manifiestan su consentimiento en las siguientes declaraciones y cláusulas.</p>
		<br>
		<p align="center"><b>DECLARACIONES</b></p>
		
		<ul type="I">
			<li>El '.$empresa.', está legalmente constituida y autorizada para celebrar este tipo de convenios.
			<li>La prestataria es una persona física con capacidad jurídica para celebrar contratos, 
				con domicilio en la calle de '.$domi['direccion1'].', '.$colonia.', en 
				la comunidad de '.$municipio.', integrante de la colmena No. '.$col_numero.' denominada: '.$col_nombre.'.
		</ul>
		<p>Las “partes” declaran que se obligan al cumplimiento de este convenio bajo las siguientes:</b>
		<br>
		<br>
		<br>
		<p align="center"><b>CLÁUSULAS</b></p>
		<ul type="I">
			<li>Por medio del presente Convenio, el '.$empresa.' entrega a la prestataria la cantidad de $'.number_format($monto, 2, '.', ',').'
			('.$monto_letra.') el día '.date_format($fecha,'d').' de '.$this->getFechaMes($fecha).' de '.date_format($fecha,'Y').', como capital semilla para la actividad de: '.$actividad.'.</li>
			<li>La prestataria se compromete a pagar al '.$empresa.' en efectivo mediante pagos semanales. Dichos pagos se realizarán en reuniones de la colmena cada 7 días a partir de la fecha de entrega del capital semilla.</li>
			<li>La prestataria pagará un Aporte Solidario fijo equivalente al 2.5% mensual.</li>
			<li>Cualquier problema de interpretación o incumplimiento de este convenio, las partes se someten a los tribunales competentes de la ciudad de Oaxaca de Juárez, Oaxaca, renunciando a cualquier otro que pudiera corresponderles en razón de su domicilio. 
				Leído el presente convenio, conociendo su contenido y fuerza legal, firman al calce para ratificarlo a los '.date_format($fecha,'d').' días del mes de '.$this->getFechaMes($fecha).' del año '.strtolower($this->getFechaYearLetras($fecha)).'.</li>
		</ul>	

		<br>
		<br>
		<table style="width:100%" border=0>
			<tr>
				<td></td>
				<td  align="center" width="30%">'.$empresa.'</td>
				<td></td>
				<td  align="center" width="30%">Prestataria</td>
				<td></td>
				<td  align="center" width="30%">Promotor</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td  width="30%" height="50px" >.</td>
				<td></td>
				<td  width="30%" height="50px">.</td>
				<td></td>
				<td  width="30%" height="50px">.</td>
				<td></td>
			</tr>				
			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%">C. MARIO ENRIQUE RENDÓN HERNÁNDEZ</td>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%"><b>C. '.$nombre.'</b></td>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="30%">C. '.mb_strtoupper($col_promotor).'</td>
				<td></td>
			</tr>			
		</table>	
		';
		
		if ($cred['nivel']<16){
			$html.='<br><br><br><br>
			<table style="width:100%" border=0>
				<tr>
					<td></td>
					<td  align="center" width="30%">Aval</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td  width="30%" height="50px" >.</td>
					<td></td>
					<td  width="30%" height="50px">.</td>
					<td></td>
					<td  width="30%" height="50px">.</td>
					<td></td>
				</tr>				
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%">C. '.$aval1_nombre.'</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>			
			</table>';
		}else{
			$html.='<br><br><br><br>
			<table style="width:100%" border=0>
				<tr>
					<td></td>
					<td align="center" width="30%">Aval</td>
					<td></td>
					<td align="center" width="30%">Aval</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td  width="30%" height="50px" >.</td>
					<td></td>
					<td  width="30%" height="50px">.</td>
					<td></td>
					<td  width="30%" height="50px">.</td>
					<td></td>
				</tr>				
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%">'.$aval1_nombre.'</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%">'.$aval2_nombre.'</td>
					<td></td>
					<td></td>
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
		$this->printReport ($html);
	}

	//Integración credito individual
	public function pdf_ahorro_get(){
		$idcredito = $this->uri->segment(4);
		$opc = $this->uri->segment(5);		
		$empresa = $this->getEmpresa();

		if ($opc == 'p' || $opc == 'a' ) {
			$fields = array("idpersona", "acreditadoid", "idacreditado", "idsucursal", "nombre", "tipo", "edocivil_nombre", "sexo", "idactividad", "direccion", "idcolmena");
			if ($opc == 'a' ) {
    			$where = array("idacreditado"=>$idcredito);
			}else {
    			$where = array("idpersona"=>$idcredito);
			}

			$cred = $this->base->selectRecord($this->esquema."get_acreditado_solicitud", $fields, "", $where, "","", "", "", "","", TRUE);
			$idcredito =  $cred[0]['acreditadoid'];
		}else {

			$fields = array("idcredito", "idacreditado", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "fecha_entrega_col");
			$where = array("idcredito"=>$idcredito);
			$cred = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
		}
		
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
			Y POR OTRA PARTE “'.$empresa.'” REPRESENTADA EN ESTE ACTO POR EL C. NICANDRO VASQUEZ RUIZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL A QUIEN EN LO SUCESIVO SE LLAMARÁ 
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
		</div>';

		if ($this->esquema === 'fin.'){
			$html.='<table align="center" style="width:90%" border=0>
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center">Nombre del Socio:</td>
					<td></td>
					<td style="border-top: 1px solid" align="center"> </td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td align="center"><b>'.$nombre.'</b></td>
					<td></td>
					<td align="center">'.$empresa.'</td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td align="center">No. de socio:'.$idsocio.'</td>
					<td></td>
					<td align="center">Sociedad Financiera Comunitaria</td>
					<td></td>
				</tr>		
			</table>		
			';			
		}else{
			$html.='<table align="center" style="width:90%" border=0>
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center">Nombre del Socio:</td>
					<td></td>
					<td style="border-top: 1px solid" align="center"> </td>
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
					<td align="center"> </td>
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
	// ACTUALIZACIÓN PAGARÉ : 04-09-2023
	public function pdf_pagare_get(){
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fun_credito = 'get_solicitud_credito';
		$fields = array("idproducto");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "creditos", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];
		if ($cred['idproducto'] === '10') {
			$fun_credito = 'get_solicitud_credito_ind';
		}

		// OBTENIENDO LOS DATOS DEL CRÉDITO
		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "tasa", "tasa_mora");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . $fun_credito, $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		// DETERMINANDO TASA ORDINARIA Y MORATORIA PARA CRÉDITOS DE COLMENA Y CRÉDITOS INDIVIDUALES
		if ($cred['idproducto'] === '10') {
			$tasa = $cred['tasa'] * 12;
			$tasa_mora = $cred['tasa_mora'];
		} else {
			$tasa = $cred['tasa'];
			$tasa_mora = 0.01;
			if ($tasa == 0) {
				$nivel = $this->base->querySelect("SELECT ssi_tasa, dias, round((ssi_tasa/12),2) as tasa_mes, round((ssi_tasa/12),2)*2 as mes_mora, numero_pagos 
				FROM public.niveles WHERE nivel=" . $cred['nivel'] . " and fecha_inicio <= '" . $cred['fecha_entrega_col'] . "'::date ORDER BY fecha_inicio desc LIMIT 1", TRUE);
				$nivel = $nivel[0];
				$tasa = $nivel['tasa_mes'];
				$tasa_mora = $nivel['mes_mora'];
			}
		}

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];

		// OBTENIENDO DATOS DE LA SUCURSAL
		$idsucursal = $cred['idsucursal'];
		$sucursal = $this->base->querySelect("SELECT idsucursal, nombre, domicilio, colonia, municipio, estado
		FROM public.sucursales S 
		WHERE S.idsucursal = '" . strval($idsucursal) . "'", TRUE);
		$sucursal = $sucursal[0];

		if ($idsucursal === '01') {
			$sucursal['nombre'] = 'ZIMATLÁN';
		}

		// DEFINIENDO LA FECHA PARA EL ENCABEZADO+
		$fecha = new DateTime($cred['fecha_entrega_col']);
		$fechaFormateada = $this->getFechaLetras($fecha);
		$lugarFecha = $sucursal['municipio'] . ', a ' . $fechaFormateada;

		// OBTENIENDO DATOS DEL AVAL
		// DATOS AVAL DE GRUPO
		$fields = array("nombre", $this->esquema . "get_cargo_grupo(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid" => $cred['idaval1']);
		$aval1 = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$aval1 = $aval1[0];

		// DATOS DE AVAL DE COLMENA (Se usará en caso de no seleccionar aval de grupo)
		$fields = array("nombre", $this->esquema . "get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
		$where = array("acreditadoid" => $cred['idaval2']);
		$aval2 = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$aval2 = $aval2[0];

		$aval = $aval1['nombre'];
		if ($aval1 == '') {
			if ($cred['nivel'] < 15) {
				$aval = $aval2['nombre'];
			}
		}
		
		$header =  $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">PAGARÉ</h3>';
			
		if ($this->session->userdata('esquema')=="ban."){
			$html.='<br>
			<table style="width:100%" border="0" class="100p">
					<tr class="seccion">
						<th class="seccion-right">
							' . $lugarFecha . '
						</th>
					</tr>
					<tr class="seccion">
						<th align="left">
						' . $pagare . '
						</th>	
					</tr>
				</table>';
		} else {
		
        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr class="seccion">
					<th align="left">
					SUCURSAL: ' . mb_strtoupper($sucursal['nombre']) . '
					</th>
					<th class="seccion-right">
					' . $lugarFecha . '
					</th>
				</tr>
				<tr class="seccion">
					<th align="left">
						'.$pagare.'
					</th>		
				</tr>				
			</table>';
		}
		$html.='<div > </div>';
		$html.='<font size="12px">';
		$html.='<br>
			<p>Debemos y pagaremos incondicionalmente por este pagaré a la orden de '.$empresa.', 
			la cantidad de $'.number_format($monto, 2, '.', ',').' - ('.$monto_letra.'), 
			misma que deberá ser pagada de acuerdo con la tabla de amortización anexa.</p>';
		$html.='
		<p>Pagadero en esta ciudad juntamente con el principal, en el domicilio ubicado en RAYON #704, BARRIO SAN ANTONIO, ZIMATLÁN DE ÁLVAREZ, OAXACA. En caso de que se produzca un retraso en los pagos arriba mencionados, la totalidad del saldo insoluto se dará por vencido, generando el vencimiento anticipado de los pagos pendientes, mas los intereses moratorios, que en su caso, se generen hasta el pago total del adeudo. </p>
		<p>Valor recibido a mi entera satisfacción con anterioridad a la presente fecha siendo la suscripción del presente pagaré el recibo mas amplio que en derecho proceda por la cantidad antes entregada, para todos los efectos a los que haya lugar.</p>
		<p>El importe de este pagaré causará interes ordinarios a razón de '.number_format($tasa /12, 2, '.', ',').'% mensual, sobre el saldo insoluto del crédito. La cantidad importe de este pagaré causará intereses moratorios a razón de '.number_format(($tasa /12 ) * 2, 2, '.', ',').'% aplicado al saldo insoluto de cada mes 
		o fracción de retraso, mientras dure la mora.</p>
		';
		
        $html.='
			<table style="width:100%"  border="0">
				<tr>
					<td></td>
					<td align="center"  width="25%" height="100px">AVAL <br><br>&nbsp;</td>
					<td></td>
					<td align="center"  width="25%">EL DEUDOR <br><br>&nbsp;</td>
					<td></td>	
				</tr>		
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">' . mb_strtoupper($aval) . '</td>
					<td></td>
					<td style="border-top: 1px solid" align="center"  width="25%">Sr(a) obligado principal<br>' . $cred['nombre'] . '</td>
					<td></td>
				</tr>		
			</table>';				
		
		if ($cred['nivel']>=15){
			$html.='
				<table style="width:100%; margin:5px 0 0 0;" border="0">
					<tr>
						<td></td>
						<td align="center" width="25%" height="100px">AVAL</td>
						</tr>		
					<tr>
						<td></td>
						<td style="border-top: 1px solid" align="center"  width="25%">' . $aval2['nombre'] . '</td>
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
		$documento = $this->dompdf->stream("Pagare.pdf", array("Attachment" => 0));
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
			
			//number_format($value['saldo_capital'], 2, '.', ',')
			$capital = $value['saldo_capital'];
			$html.='  <tr>';
			$html.='  <td>'.number_format($value['numero'], 0, '.', ',').'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
			
			$ban = true;
			if ($this->session->userdata('esquema' ) =="ban."  ){ 	    
				if ( $ban == true) { 
					$html.='  <td align="right">'.number_format($value['aportesol'], 2, '.', ',').'</td>';
				}			
			}else {
	
				$html.='  <td align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			}
			
			//$html.='  <td align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
			//$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['capital'], 2, '.', ',').'</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html.='  <td align="right">'.number_format($value['total'], 2, '.', ',').'</td>';
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
			$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes_pag'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva_pag'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($cappag, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['total'], 2, '.', ',').'</td>';
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
		$ahorro=0;
		$dblTotalFijo=0;
		foreach($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			$total = $value['total'] + $value['garantia'] +  $value['ajuste'];
			if ($dblTotalFijo==0)
				$dblTotalFijo = $total;
			$ahorro = $saldoCapital;
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
				$html.='  <td align="center">'.$this->formatNumber($value['saldo_capital']).'</td>';
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
				//2021-05-27 Si el saldo de capital es menor al capital				
				IF ($saldoCapital < 0){
					$saldoCapital=0.0;					
					$total = $value['total'] + $value['garantia'];
					if ($total < $dblTotalFijo) 
						$total = $dblTotalFijo;
				}
			}

			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha,'d/m/Y');

			$html.='  <tr>';
			$html.='  <td height="15">'.$intPago.'</td>';
			//$html.='  <td>'.$numero.'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td align="center">'.$this->formatNumber($capital).'</td>';
			$html.='  <td align="center">'.$this->formatNumber($saldoCapital).'</td>';
			$html.='  <td align="center">'.$this->formatNumber($value['aportesol']).'</td>';
			$html.='  <td align="center">'.$this->formatNumber($garantia).'</td>';
			$html.='  <td align="center">'.$this->formatNumber($garantia*$numero).'</td>';
			$html.='  <td align="center">'.$this->formatNumber($total).'</td>';
			$html.='  <td align="center"></td>';
			$html.='  <td align="center"></td>';
			$html.='  <td align="center"></td>';
			$html.='  </tr>';
			$intPago = $intPago + 1;
		}
		$html.='</table>';
		return $html;
	}
	
	// AGREGADO 21-08-2023
	function formatDate($date)
	{
		$formattedDate = date_create($date);
		return date_format($formattedDate, 'd/m/Y');
	}

	function formatNumber($number, $decimals = 0)
	{
		return number_format($number, $decimals);
	}
	
	//2021-06-20 Individual
	public function tableCreatePlanPagosInd($title, $data) {
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
		$ahorro=0;
		$dblTotalFijo=0;
		//$title = array("Pago","Fecha<br>programada","Capital", "Saldo capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
		
		foreach($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['interes'];
			$total = $value['total'] + $value['garantia'] +  $value['ajuste'];
			if ($dblTotalFijo==0)
				$dblTotalFijo = $total;
			$ahorro = $saldoCapital;
			if ($intPago==1)	{
				$capital = $value['capital'];//-$value['aportesol']; //2021-05-04 Se cambio
				//$capital = $value['capital'];
				$saldoCapital=$value['saldo_capital']-$capital;
				$pagoFijo = $total;
				$html.='  <tr>';
				$html.='  <td height="15"></td>';
				//$html.='  <td></td>';
				$html.='  <td></td>';
				$html.='  <td></td>';
				$html.='  <td align="right">'.number_format($value['saldo_capital'], 2, '.', ',').'</td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  <td align="right"></td>';
				$html.='  </tr>';

			}else{
				if ($capital > $saldoCapital){
					$temporal = $saldoCapital;
				}
				$saldoCapital-=$capital;
				//2021-05-27 Si el saldo de capital es menor al capital				
				IF ($saldoCapital < 0){
					$capital = $temporal;
					$saldoCapital=0.0;					
					$total = $capital + $value['interes'] + $value['iva'];
				}
			}

			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha,'d/m/Y');

			$html.='  <tr>';
			$html.='  <td height="15">'.$intPago.'</td>';
			$html.='  <td>'.$fecha.'</td>';
			$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($saldoCapital, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($total, 2, '.', ',').'</td>';
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

	//2021-06-20 Convenio individual
	public function tableCreateConvenioInd($title, $data, $monto) {
		$saldo=$monto;
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$totAmor = 0;
		//$title = array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Incidencias");		
		foreach($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			//$total = $value['ssi_total'];
			$capital = $value['capital'];
			$saldo= $saldo-$capital;
			$totAmor = $capital + $value['interes'] + $value['iva'];
			$html.='  <tr>';
			$html.='  <td>'.$value['numero'].'</td>';
			$html.='  <td>'.$value['fecha_vence'].'</td>';
			$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($saldo, 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html.='  <td align="right">'.$totAmor.'</td>';
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
	//2021-08-09 Correccion ultimo pago.
	// ACTUALIZACIÓN DE PLAN DE PAGOS - 18-08-2023
	public function pdf_plan_pago_get()
	{
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "num_pagos", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "actividad_nombre", "direccion", "idcolmena", "nomcolmena", "fecha_entrega_col");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito_ind", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$id_grupo = $cred['idgrupo'];
		if ($id_grupo === "0") {
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
		} else {
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "colmena_grupo");
			$where = array("idgrupo" => $id_grupo);
			$col = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
			$col = $col[0];
			$col_nombre = $col['colmena_nombre'];
			$col_numero = $col['colmena_numero'];
			$col_grupo = $col['colmena_grupo'];
		}

		$monto = $cred['monto'];
		$pagare = $cred['idpagare'];
		$sucursal = 'ZIMATLÁN';
		if ($cred['idsucursal'] === '02') {
			$sucursal = 'OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);

		//Obteniendo el nombre del promotor que atiende la colmena
		$idcolmena = $cred['idcolmena'];
		$fields = array("numero", "nombre", "promotor");
		$where = array("idcolmena" => $idcolmena);
		$colmena = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$colmena = $colmena[0];
		$promotorc = $colmena['promotor'];

		if ($cred['idproducto'] === '10') {
			$usr = $this->base->querySelect("SELECT c.usuario, (u.first_name || ' ' || u.last_name) as nombre 
				FROM " . $this->session->userdata('esquema') . "creditos as c 
					JOIN security.users as u ON c.usuario=u.username or c.usuario=u.id::varchar
				WHERE idcredito=" . $idcredito, TRUE);
			$promotor = $usr[0]['nombre'];
			$texto = 'Gerente de Sucursal';
		} else {
			$promotor = $promotorc;
			$texto = 'Promotor (a)';
		}

		// DATOS PERSONA
		$idacreditado = $cred['idacreditado'];
		$persona = $this->base->querySelect("SELECT celular, acreditado, curp, rfc , fecha_nac, tipovivienda, curp, rfc, vine, direccion2
		FROM public.acreditado inner join 
		public.personas on public.acreditado.idpersona = public.personas.idpersona
		inner join public.persona_domicilio PD on PD.idpersona = public.personas.idpersona 
		where public.acreditado.idacreditado = " . $cred['idacreditado'], TRUE);
		$persona = $persona[0];

		// Obteniendo nombre de la presidenta de grupo
		$pres_grupo = $this->base->querySelect("SELECT C.idgrupo, idcargo1, idacreditado, acreditadoid, A.idsucursal, P.idpersona, nombre1, nombre2, apaterno, amaterno
		FROM col.grupo_cargo C
		INNER JOIN public.acreditado A ON A.acreditadoid = C.idcargo1
		INNER JOIN public.personas P ON P.idpersona = A.idpersona
		WHERE C.idgrupo = " . $cred['idgrupo'], TRUE);
		$pres_grupo = $pres_grupo[0];

		//Obteniendo la edad de la persona a la fecha de solicitud del crédito
		$fechaNacimiento = new DateTime($persona['fecha_nac']);
		$fechaActual = $fecha;
		$dif = $fechaNacimiento->diff($fechaActual);
		$edad = $dif->y;

		$data = $this->base->querySelect("SELECT sum(garantia) as total FROM (
			SELECT c.idcredito FROM " . $this->session->userdata('esquema') . "creditos as c
				JOIN (SELECT idacreditado, fecha FROM " . $this->session->userdata('esquema') . "creditos WHERE idcredito=" . $idcredito . ") as x ON c.idacreditado=x.idacreditado AND c.fecha<x.fecha
			ORDER BY c.fecha DESC limit 1) as c
			JOIN " . $this->session->userdata('esquema') . "amortizaciones as a ON c.idcredito=a.idcredito", TRUE);

		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total", "ajuste");
		$where = array("idcredito" => $idcredito);
		$order_by = array(array('campo' => 'numero', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "amortizaciones", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		$tabla = '';

		if ($cred['idproducto'] === '10') {
			$title = array("Pago", "Fecha<br>programada", "Capital", "Saldo capital", "Interés", "IVA", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
			$tabla .= $this->tableCreatePlanPagosInd($title, $amor);
		} else {

			// Definiendo encabezados de acuerdo al esquema
			if ($this->session->userdata('esquema') === 'ban.') {
				$title = array("Pago", "Fecha<br>programada", "Capital", "Saldo capital", "Aporte solidario", "Garantía", "Gtía. acum.", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
			} else {
				$title = array("Pago", "Fecha<br>programada", "Capital", "Saldo capital", "Interés", "Ahorro Compr.", "Ahorro Compr. acum.", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
			}
			$tabla .= $this->tableCreatePlanPagos($title, $amor);
		}

		$espaciado = '';
		$espaciado2 = '';
		if (intval($cred['num_pagos']) < 25) {
			$espaciado2 = '<br><br>';
			$espaciado = '<div style="page-break-before: always;"></div>';
		} else if (intval($cred['num_pagos']) == 25 || intval($cred['num_pagos']) == 30) {
			$espaciado2 = '<div style="page-break-before: always;"></div>';
			$espaciado = '<hr><div></div>';
		} else {
			$espaciado2 = '<br><br><br>';
			$espaciado = '<div style="page-break-before: always;"></div>';
		}

		$header = $this->headerReport('');
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">PLAN DE PAGOS</h3>';

		$html .= '
			<table style="width:100%; font-size: 7px" border="0" class="100p">
				<tr>
					<td class="seccion-left" rowspan="2">
						Sucursal: <strong>' . $cred['idsucursal'] . ' - ' . $sucursal . '</strong>
					</td>
					<td class="seccion-left">
						Colmena:<strong> ' . $col_numero . ' ' . $col_nombre . '</strong>
					</td>
					<td class="seccion-left">
						Fecha:<strong> ' . date_format($fecha, 'd/m/Y') . '</strong>
					</td>	
					
				</tr>
				<tr>
					<td class="seccion-left">
						Grupo:<strong> ' . $col_grupo . '</strong>
					</td>
					<td class="seccion-left">
						Crédito:<strong> ' . $cred['idcredito'] . '</strong>
					</td>
							
				</tr>	
				<tr>
					<td class="seccion-left">
						Socia (o):<strong> ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '</strong>
					</td>
					<td class="seccion-left" colspan="2">
						Propósito del crédito:<strong> ' . $cred['proy_nombre'] . '</strong>
					</td>									
				</tr>	
			</table>';
		$html .= '<br><div> </div>';
		$html .= $tabla;
		$html .= '<br>';
		$html .= $espaciado2 . '
			<table style="width:100%; margin-bottom: 45px"  border="0">
			<tr>
			<td></td>
			<td align="center"  width="25%">Entregó<br><br><br>&nbsp;</td>
			<td></td>
			<td align="center"  width="25%">Recibió<br><br><br>&nbsp;</td>
			<td></td>
			</tr>		

			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">' . mb_strtoupper($promotor) . '<br>' . $texto . '</td>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">' . $cred['nombre'] . '<br>Prestataria</td>
				<td></td>
			</tr>		
		</table>

		<p style="font-size: 10px">
			La información contenida en el presente documento es un Plan de Pagos resumido para fines informativos y de control de campo, por lo que podrá estar sujeta a cambios y bajo ninguna circunstancia podrá considerarse como una oferta vinculante, ni como la autorización formal de crédito por parte de la empresa.
		</p>
		</div>
		
		' . $espaciado . '
		<div style="font-size: 12px">
			<h2 align="center">SOLICITUD DE CRÉDITO</h2>
			<strong>
				<table align="right" width="280px" border=0 cellpadding=0 bordercolor=#000000>
				
					<tr>
						<td rowspan="2" style="text-align: center;">Fecha de solicitud: </td>
						<td style="text-align: center">DD</td>
						<td style="text-align: center">MM</td>
						<td style="text-align: center">AAAA</td>
					</tr>
					<tr>
						<td style="padding: 10px 0; border: solid; border-color: #000000;"></td>
						<td style="padding: 10px 0; border: solid; border-color: #000000;"></td>
						<td style="padding: 10px 0; border: solid; border-color: #000000;"></td>
					</tr>
				</table>
			</strong>
				<div style="height: 40px">
				</div>
			<h3 style="margin: 15px 0 10px 0;">1. DATOS GENERALES DE LA SOLICITANTE</h3>

			<table style="width:100%; margin: 0 0 5px 0;">
				<tr class="seccion">
					<td colspan="3" style="padding: 8px;">Nombre completo: <strong>' . $cred['nombre'] . '</strong></td>
					<td style="padding: 8px;">No. Socia: <strong>' . $cred['idacreditado'] . '<strong></td>
				</tr>
										
				<tr class="seccion">
					<td colspan="2" style="padding: 8px;">Teléfono: <strong>' . $persona['celular'] . '</strong></td>
					<td colspan="1" style="padding: 8px;">Vig. INE: <strong>' . $persona['vine'] . '</strong></td>
					<td colspan="1" style="padding: 8px;">Edad: <strong>' . $edad . ' años</strong></td>
					
				</tr>												
				<tr class="seccion">
					<td colspan="4" style="padding: 8px;">Domicilio: <strong>' . $cred['direccion'] . '</strong></td>
				</tr>
				<tr class="seccion">
					<td colspan="1" style="padding: 8px;">No. Colmena: <strong>' . $col_numero . '</strong></td>
					<td colspan="2" style="padding: 8px;">Nombre: <strong>' . $col_nombre . '</strong></td>
					<td colspan="1" style="padding: 8px;">Grupo: <strong>' . $col_grupo . '</strong></td>
				</tr>						
			</table>
			<div>
			<h3 style="margin: 25px 0 10px 0;">2. DATOS DE LA ACTIVIDAD PRODUCTIVA</h3>

			<table style="width:100%; margin: 15px 0;">
				<tr class="seccion">
					<td style="padding: 8px;" colspan="3">Principal fuente de ingresos: <strong>' . $cred['actividad_nombre'] . '</strong></td>
				</tr>
				<tr class="seccion">
					<td style="padding: 8px;" colspan="3">Propósito del crédito:</td>
				</tr>
				<tr class="seccion">
					<td style="padding: 8px;" colspan="2">Monto solicitado:</td>
					<td>Plazo (semanas):</td>
				</tr>						
				<tr class="seccion">
					<td style="padding: 8px;" colspan="3">Lugar donde se realizará la actividad:</td>
				</tr>												
				<tr>
					<td  class="seccion-center" style="padding: 8px;" colspan="3">Descripción del uso del crédito:</td>
				</tr>						
				<tr> 
					<td style="padding: 8px;" colspan="3"><br><br>&nbsp;</td> 
				</tr>											
			</table>

			<table style="width:100%; margin: 55px 0 0 0; font-size:12px;" border="0">			
				<tr >
					
					<td style="border-top: 1px solid" align="center" width="25%">Promotor (a)</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Presidenta de Grupo</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Socia</td>
				</tr>
				<tr >
					
					<td align="center" width="25%">' . mb_strtoupper($promotor) . '</td>
					<td></td>
					<td align="center" width="25%">' . mb_strtoupper($pres_grupo['nombre1']) . ' ' . mb_strtoupper($pres_grupo['nombre2']) . ' ' . mb_strtoupper($pres_grupo['apaterno']) . ' ' . mb_strtoupper($pres_grupo['amaterno']) . '</td>
					<td></td>
					<td align="center" width="25%">' . $cred['nombre'] . '</td>
				</tr>				
			</table>
			</div>
		';
		$html .= '
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
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
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
		
		$ban = true;
		if ($this->session->userdata('esquema' ) =="ban."  ){ 	    
			if ( $ban == true) { 
				$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "aportesol", "garantia", "total" );
				$title = array("Pago","Vencimiento","Saldo Capital", "Aporte Solidario", "Capital", "Cuota");
			}			
		}else {

			$title = array("Pago","Vencimiento","Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		}
		
		//2022-08-15
		$idcolmena = $cred['idcolmena'];
		$fields = array("numero", "nombre", "promotor");
		$where = array("idcolmena"=>$idcolmena);
		$colmena = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "","", "", "", "","", TRUE);
		$colmena = $colmena[0];
		$col_numero = $colmena['numero'];
		$col_nombre = $colmena['nombre'];
		$col_promotor = $colmena['promotor'];
		
		//$title = array("Pago","Vencimiento","Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		$tabla = '';
		$tabla.= $this->tableCreateAmor($title, $amor);


		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

        $html.='
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
					<th>
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
						PDSF: '.$col_promotor.'
					</th>		
				</tr>													
				<tr>
					<!--<th class="seccion-left">
						Nivel: '.$cred['nivel'].'
					</th>-->		
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
		$tabla = '';
		if ($cred['idproducto']==="10"){
			$title = array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Incidencias");
			$tabla.= $this->tableCreateConvenioInd($title, $amor, $monto);
		}else{
			$title = array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "Ahorro comp.", "Saldo ahorro comp.", "Total del pago", "Firma del promotor", "Incidencias");
			$tabla.= $this->tableCreateConvenio($title, $amor, $monto);
		}
	
	
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
			<table style="width:100%; margin-bottom: 15px;">
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>
				<tr>
					<th colspan="2" class="seccion-left" style="text-align: center;">
						AUTORIZACIÓN DE TRANSPASO POR LA CANTIDAD DE: 
					</th>
				</tr>
				<tr>
					<th class="seccion-left" style="padding: 10px 60px;">$
						'.$total_recibo.' 
					</th>		
					<th class="seccion-left">('.$monto_letra.')</th>
				</tr>
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>	
				
				
			</table>';
		$html.='
			<div border=1>
				<table style="width:100%;">
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
					<tr>
						<th colspan="3" class="seccion-left" style="text-align: center;">
							Por concepto de traspaso de ahorro comprometido al ahorro corriente de la socia
						</th>
					</tr>
					<tr>
						<th colspan="2" class="seccion-left" style="text-align: center">
							'.$cred['nombre'].'
						</th>		
						<th class="seccion-left">Número de socia: '.$cred['idacreditado'].' </th>
					</tr>	
					<tr>
						<th class="seccion-left" style="text-align:center;">
							de la colmena número: '.$gpo['colmena_numero'].'
						</th>		
						<th class="seccion-left" style="text-align:center;">denominada: '.$gpo['colmena_nombre'].'
						</th>
						<th class="seccion-left" style="text-align:center;">
							del grupo: '.$gpo['colmena_grupo'].'
						</th>		
					</tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>							
				</table>
			</div>';

		$html.='<br> <br>
			<div border=0>
				<table style="width:100%" border="0">
				<tr>
				<!--<td></td>-->
				<!--<td align="center"  width="25%"> <br><br><br>&nbsp;</td>-->
				<td></td>
				<td align="center"  width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				<td align="center" width="25%"> <br><br><br>&nbsp;</td>
				<td></td>
				</tr>		

				<tr>
				<!--<td></td>-->
				<!--<td style="border-top: 1px solid" align="center"  width="25%">Representante Legal</td>-->
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%"><br>Promotor</td>
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
			$reporte_suc = "ZIMATLÁN";
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
			$reporte_suc = "ZIMATLÁN";
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


	public function pdf_credrecupera_det_get(){
		$fecha_ini = $this->uri->segment(4);
		$fecha_fin = $this->uri->segment(5);
		$pantalla = $this->uri->segment(6);
		$empresa = $this->getEmpresa();
		/*
		print_r ($fecha_ini);
		print_r ($fecha_fin);
		die;		
		*/
		$fecha1 = new DateTime($fecha_ini);
		$fecha2 = new DateTime($fecha_fin);

		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		
		$cred = $this->base->querySelect("SELECT c.idcredito, c.idsucursal, c.monto, c.idproducto, c.fecha_entrega, a.idacreditado, a.acreditado
			FROM ".$this->esquema."creditos as c
				JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and fecha_entrega::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."' ORDER BY c.fecha_entrega", TRUE);
		$title = array("Suc","F.Entrega", "Credito", "Monto", "ID", "Acreditado");
		$tabla = '';
		if ($cred){
			$tabla.= $this->table_men_cred($title, $cred);
		}

		if ($this->esquema == 'imp.') {
			$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, p.fecha_pago,  max(p.capital_pag + p.iva_pag + p.interes_pag) as total_pago, sum(p.capital_pag) as capital, sum(p.iva_pag) as iva, sum(p.interes_pag) as interes_n, 0 as interes_m
				FROM ".$this->esquema."amortizaciones as p
					JOIN ".$this->esquema."creditos as c ON p.idcredito=c.idcredito
				WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and p.fecha_pago::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
				GROUP BY c.idsucursal, p.idcredito, p.fecha_pago
				ORDER BY p.fecha_pago, c.idsucursal", TRUE);

		} else {	$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, p.fecha_pago,  max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
				FROM ".$this->esquema."pagos as p
					JOIN ".$this->esquema."creditos as c ON p.idcredito=c.idcredito
					JOIN ".$this->esquema."pagos_amor as d ON p.idpago=d.idpago
				WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and p.fecha_pago::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
				GROUP BY c.idsucursal, p.idcredito, p.fecha_pago
				ORDER BY p.fecha_pago, c.idsucursal", TRUE);
		}
		
		$title = array("Fecha", "Suc.", "Credito","Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		$tabla2 = '';
		if ($recu){
			$tabla2.= $this->table_men_recupera($title, $recu);
		}
		
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE CREDITOS Y RECUPERACIONES <br>
				SUCURSAL: '.$sucursal.', DEL '.date_format($fecha1,'d/m/Y').' AL '.date_format($fecha2,'d/m/Y').'</h3>';
        
		$html.='<br><div > CREDITOS OTORGADOS </div>';
        $html.=$tabla;
		$html.='<br><br><div > RECUPERACIONES </div>';
        $html.=$tabla2;
		$html.='<br><br>';
				
		$html.='
		</div>
		</body>
		</html>
		';
		
		if ($pantalla =='p') {
			print_r($html);
			//die();
		}else {
			$this->printReport ($html);

		}
		/*
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
		*/
	}


	public function table_men_cred($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$capital=0;
		foreach($data as $key => $value) {
			$capital = $capital + $value['monto'];
			$html.='  <tr>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td>'.$value['fecha_entrega'].'</td>';
			$html.='  <td>'.$value['idcredito'].'</td>';
			$html.='  <td align="right">'.number_format($value['monto'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.$value['idacreditado'].'</td>';
			$html.='  <td>'.$value['acreditado'].'</td>';
			$html.='  </tr>';
		}
		$html.='  <tr>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  </tr>';
		$html.='</table>';
		return $html;
	}

	public function table_men_recupera($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp = 0;
		foreach($data as $key => $value) {
			$capital += $value['capital'];
			$interes_n += $value['interes_n'];
			$interes_m += $value['interes_m'];
			$iva += $value['iva'];
			$total_temp = $value['capital'] + $value['interes_n'] + $value['interes_m'] + $value['iva'];
			$total_pago += $total_temp;
			//$total_pago += $value['total_pago'];			
			$html.='  <tr>';
			$html.='  <td>'.$value['fecha_pago'].'</td>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td align="right">'.number_format($value['idcredito'], 0, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['capital'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes_n'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes_m'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($total_temp, 2, '.', ',').'</td>';
			$html.='  </tr>';
		}
		$html.='  <tr>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($interes_n, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($interes_m, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($iva, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($total_pago, 2, '.', ',').'</td>';
		$html.='  </tr>';
		$html.='</table>';
		return $html;
	}	
	
	
	public function pdf_credrecupera_gl_get(){
		$fecha_ini = $this->uri->segment(4);
		$fecha_fin = $this->uri->segment(5);
		$empresa = $this->getEmpresa();

		$fecha1 = new DateTime($fecha_ini);
		$fecha2 = new DateTime($fecha_fin);

		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		
		$cred = $this->base->querySelect("SELECT c.idsucursal, c.fecha_entrega::date, sum(c.monto) as monto
			FROM ".$this->esquema."creditos as c
				JOIN public.get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and fecha_entrega::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."' 
			GROUP BY c.idsucursal, c.fecha_entrega::date ORDER BY c.fecha_entrega::date", TRUE);
		$title = array("F.Entrega", "Suc", "Monto");
		$tabla = '';
		if ($cred){
			$tabla.= $this->table_men_cred_gl($title, $cred);
		}
	
		if ($this->esquema == 'imp.') {
			$recu = $this->base->querySelect("SELECT c.idsucursal, p.fecha_pago::date,  max(p.capital_pag + p.iva_pag + p.interes_pag) as total_pago, sum(p.capital_pag) as capital, sum(p.iva_pag) as iva, sum(p.interes_pag) as interes_n, 0 as interes_m
				FROM ".$this->esquema."amortizaciones as p
					JOIN ".$this->esquema."creditos as c ON p.idcredito=c.idcredito
				WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and p.fecha_pago::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
				GROUP BY c.idsucursal, p.fecha_pago::date
				ORDER BY p.fecha_pago::date, c.idsucursal", TRUE);

		} else {
			$recu = $this->base->querySelect("SELECT c.idsucursal, p.fecha_pago::date, max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
				FROM ".$this->esquema."pagos as p
					JOIN ".$this->esquema."creditos as c ON p.idcredito=c.idcredito
					JOIN ".$this->esquema."pagos_amor as d ON p.idpago=d.idpago
				WHERE c.idsucursal='".$this->session->userdata('sucursal_id')."' and p.fecha_pago::date BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
				GROUP BY c.idsucursal, p.fecha_pago::date
				ORDER BY p.fecha_pago::date, c.idsucursal", TRUE);
		}
		$title = array("Fecha", "Suc","Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		$tabla2 = '';
		if ($recu){
			$tabla2.= $this->table_men_recupera_gl($title, $recu);
		}
		
		$header = $this->headerReport('');
		//$html = $header;
		
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE CREDITOS Y RECUPERACIONES <br>
				SUCURSAL: '.$sucursal.', DEL '.date_format($fecha1,'d/m/Y').' AL '.date_format($fecha2,'d/m/Y').'</h3>';
        
		$html.='<div><b>CREDITOS OTORGADOS </b>';
        $html.=$tabla;
		$html.='</div> ';
		$html.='<br><br><div> <b>RECUPERACIONES </b>';
        $html.=$tabla2;
		$html.='</div>';
				
		$html.='
		</div>
		</body>
		</html>
		';

		//print_r ($html);
		//die;
		
		$this->printReport ($html);
	}	

	public function table_men_cred_gl($title, $data) {
		$html='';
		$html.='<table style="width:50%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$capital=0;
		foreach($data as $key => $value) {
			$capital = $capital + $value['monto'];
			$html.='  <tr>';
			$html.='  <td>'.$value['fecha_entrega'].'</td>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td align="right">'.number_format($value['monto'], 2, '.', ',').'</td>';
			$html.='  </tr>';
		}
		$html.='  <tr>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
		$html.='  </tr>';
		$html.='</table>';
		return $html;
	}

	public function table_men_recupera_gl($title, $data) {
		/*$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
		$title = array("Suc.", "Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		*/
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp =0;
		foreach($data as $key => $value) {
			$capital += $value['capital'];
			$interes_n += $value['interes_n'];
			$interes_m += $value['interes_m'];
			$iva += $value['iva'];
			$total_temp = $value['capital'] + $value['interes_n'] + $value['interes_m'] + $value['iva'];
			$total_pago += $total_temp;
			$html.='  <tr>';
			$html.='  <td>'.$value['fecha_pago'].'</td>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td align="right">'.number_format($value['capital'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes_n'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['interes_m'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($value['iva'], 2, '.', ',').'</td>';
			$html.='  <td align="right">'.number_format($total_temp, 2, '.', ',').'</td>';
			$html.='  </tr>';
		}
		$html.='  <tr>';
		$html.='  <td></td>';
		$html.='  <td></td>';
		$html.='  <td align="right">'.number_format($capital, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($interes_n, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($interes_m, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($iva, 2, '.', ',').'</td>';
		$html.='  <td align="right">'.number_format($total_pago, 2, '.', ',').'</td>';
		$html.='  </tr>';
		$html.='</table>';
		return $html;
	}			
	
	public function printReport($html, $orientation='portrait'){
		ob_clean();		
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', $orientation);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));				
	}	

	public function pdf_provconfig_get(){
		$idprovcnf = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$fields = array("idprovcnf", "idcredito", "fecha_ini", "fecha_fin", "nota", "fecha_aprov", "usuario_aprov", "usuario", "fecha_mov", "nombre", "idsucursal", "idpagare", "idacreditado", "nivel");
		$where = array("idprovcnf"=>$idprovcnf);
		$prov = $this->base->selectRecord($this->esquema."v_prov_config", $fields, "", $where, "","", "", "", "","", TRUE);
		//print_r ($prov);
		//die;
		$prov = $prov[0];

		$pagare = $prov['idpagare'];
		$sucursal ='ZIMATLAN';
		if ($prov['idsucursal']==='02'){
			$sucursal ='OAXACA';
		}
		$fecha_ini = new DateTime($prov['fecha_ini']);
		$fecha_fin = new DateTime($prov['fecha_fin']);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CANCELACIÓN DE PROVISIONES</h3>';
			
		if ($prov['fecha_ini'] === $prov['fecha_fin']){
			$periodo_prov = 'PERIODO A PARTIR DEL '.date_format($fecha_ini,'d/m/Y');
		}else{
			$periodo_prov = 'PERIODO DEL '.date_format($fecha_ini,'d/m/Y').' AL '.date_format($fecha_fin,'d/m/Y');
		}
		$html .=  '<h3 align="center">'.$periodo_prov.'</h3>';

        $html.='<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: '.$prov['idsucursal'].' - '.$sucursal.'
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: '.$prov['idcredito'].'
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: '.$prov['idacreditado'].' '.$prov['nombre'].'
					</th>		
					</th>		
					<th>
					<th class="seccion-left">
						Nivel: '.$prov['nivel'].'
					</th>			
				</tr>													
			</table>';
			//$html.='<br><br><div > </div>';

			$html.='<br>
			<p>Solicitud de autorización para cancelar por el '.$periodo_prov.'
			la EMISIÓN DE LA PROVISION por el siguiente motivo:</p>';
			$html.='
			<p>'.$prov['nota'].'<br><br><br><br></p>';
			
			$html.='<table style="width:100%" border=0>
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%"> </td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="30%"><b> </b></td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td align="center" width="30%">GERENTE DE SUCURSAL</td>
					<td></td>
					<td align="center" width="30%">GERENTE GENERAL</td>
					<td></td>
				</tr>			
			</table>';				
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

		ob_clean();	
		$this->printReport ($html);
	}

	public function pdf_provconfiglst1_get(){
		$idprovcnf = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$fields = array("idprovcnf", "idsucursal", "idcredito", "idpagare", "nombre", "nota", "fecha_ini", "fecha_fin");
		$order_by = array(array('campo'=> 'idprovcnf', 'direccion'=>	'asc'));
		$prov = $this->base->selectRecord($this->esquema."v_prov_config", $fields, "", "", "","", "", $order_by, "","", TRUE);

		$title = array("Folio", "Suc", "Credito", "Pagare", "Socia", "Nota", "F. inicial", "F. final");
		$tabla2 = '';
		if ($prov){
			$tabla2.= $this->table_provconfig($title, $prov);
		}
		
		$fecha_ini = new DateTime($prov['fecha_ini']);
		$fecha_fin = new DateTime($prov['fecha_fin']);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">LISTADO DE CANCELACIÓN DE PROVISIONES</h3>';
        $html.='<br>';
		$html.=$tabla2;

			
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

		ob_clean();	
		$this->printReport ($html);
	}	

	public function table_provconfig($title, $data) {
		$html='';
		$html.='<table style="width:100%">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp =0;
		foreach($data as $key => $value) {
			$html.='  <tr>';
			$html.='  <td>'.$value['idprovcnf'].'</td>';
			$html.='  <td>'.$value['idsucursal'].'</td>';
			$html.='  <td align="right">'.$value['idcredito'].'</td>';
			$html.='  <td">'.$value['idpagare'].'</td>';
			$html.='  <td">'.$value['nombre'].'</td>';
			$html.='  <td">'.$value['nota'].'</td>';
			$html.='  <td">'.$value['fecha_ini'].'</td>';
			$html.='  <td">'.$value['fecha_fin'].'</td>';
			$html.='  </tr>';
		}

		$html.='</table>';
		return $html;
	}	


public function pdf_col_horario_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		if ($this->session->userdata('esquema')==="fin."){
			$empresa = 'F';
		}elseif ($this->session->userdata('esquema')==="ban."){
			$empresa = 'B';
		}elseif ($this->session->userdata('esquema')==="imp."){
			$empresa = 'I';
		}else{
			$empresa = '';
		}
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario('".$this->session->userdata('sucursal_id')."', '".$empresa."',".$idPromotor.") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_col_horario($title, $hora, '0');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		$this->printReport ($html, 'landscape');
	}
	
	public function pdf_col_horario2_get()
	{
		// Obtiene el promotor
		$idPromotor = $this->uri->segment(4);

		// Obtiene el nombre de la sucursal
		$sucursal = obtenerNombreSucursal($this->session->userdata('sucursal_id'));

		// Definiendo el esquema
		if ($this->session->userdata('esquema') === "fin.") {
			$empresa = 'F';
		} elseif ($this->session->userdata('esquema') === "ban.") {
			$empresa = 'B';
		} elseif ($this->session->userdata('esquema') === "imp.") {
			$empresa = 'I';
		} else {
			$empresa = '';
		}

		// Obteniendo la hora de reunión
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario('" . $this->session->userdata('sucursal_id') . "', '" . $empresa . "'," . $idPromotor . ") WHERE numero>1;", TRUE);

		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");

		$tabla = '';
		if ($hora) {
			$tabla .= $this->table_col_horario2($title, $hora, '0');
		}

		// Generando HTML
		$header = addLogoAndSubtitle($header, 'Horario - '. $sucursal);
		$header = generateReportHeader('Horario - ' . $sucursal, $this->getEmpresa() . '<hr>HORARIO SUCURSAL: ' . $sucursal, '', '', '14px');

		$html = $header . '<div style="font-size:10px;">';
		$html .= $tabla;
		$html .= '
				</div>
			</body>
		</html>
		';
		$this->printReport($html, 'landscape');
	}

	public function pdf_col_horariog_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('".$this->session->userdata('sucursal_id')."',".$idPromotor.") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		$this->printReport ($html, 'landscape');
	}		

	public function pdf_col_horariog2_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('01',".$idPromotor.") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$sucursal ='OAXACA';
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('02',".$idPromotor.") WHERE numero>1;", TRUE);
		if ($hora){
			$tabla= $this->table_col_horario($title, $hora, '1');

			$html .= '<div class="page_break"></div>'.$header.'
				<div style="font-size:11px;">
				<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
			$html.='<div style="font-size:8px;">';
			$html.=$tabla;
			$html.='</div>';
			$html.='<br><br>';		

		}
		$html.='
		</div>
		</body>
		</html>
		';
		$this->printReport ($html, 'landscape');
	}

public function pdf_col_horariocap_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLÁN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		if ($this->session->userdata('esquema')==="fin."){
			$empresa = 'F';
		}elseif ($this->session->userdata('esquema')==="ban."){
			$empresa = 'B';
		}elseif ($this->session->userdata('esquema')==="imp."){
			$empresa = 'I';
		}else{
			$empresa = '';
		}
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario_cap('".$this->session->userdata('sucursal_id')."', '".$empresa."',".$idPromotor.") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_col_horario($title, $hora, '0');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		$this->printReport ($html, 'landscape');
	}

	public function pdf_col_horariocapg_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario_capg('".$this->session->userdata('sucursal_id')."',".$idPromotor.") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		$this->printReport ($html, 'landscape');
	}		

	public function table_col_horario($title, $data, $global) {
		if ($global==='1'){
			$columnas=4;
		}else{
			$columnas=3;
		}
		$html='';
		$html.='<table style="width:100%" >';
		$html.='  <tr style="height:20px; background:lightblue;">';
		$html.='    <th span=2>RUTA</th>';
		$html.='    <th colspan="'.$columnas.'" align="center">LUNES</th>';
		$html.='    <th colspan="'.$columnas.'" align="center">MARTES</th>';
		$html.='    <th colspan="'.$columnas.'" align="center">MIERCOLES</th>';
		$html.='    <th colspan="'.$columnas.'" align="center">JUEVES</th>';
		$html.='    <th colspan="'.$columnas.'" align="center">VIERNES</th>';
		$html.='  </tr>';
		$capital=0;
		$numero=0;
		$fila='';
		foreach($data as $key => $value) {
			$html.='  <tr">';						
			if ($numero != $value['numero']){
				$numero = $value['numero'];
				$html.='  <td style="border-bottom:0px;"><b>'.$value['nombre'].'</b></td>';
				$fila = '1';
			}else{
				$html.='  <td style="border-bottom:0px; border-top:0px;"></td>';				
				$fila = '';
			}						
			$html.= $this->col_horario_dia($value['lunes'], $fila, $global);			
			$html.= $this->col_horario_dia($value['martes'], $fila, $global);
			$html.= $this->col_horario_dia($value['miercoles'], $fila, $global);
			$html.= $this->col_horario_dia($value['jueves'], $fila, $global);
			$html.= $this->col_horario_dia($value['viernes'], $fila, $global);
			$html.='  </tr>';
		}
		$html.='</table>';
		return $html;
	}	
	
	public function table_col_horario2($title, $data, $global)
	{
		if ($global === '1') {
			$columnas = 4;
		} else {
			$columnas = 3;
		}
		$html = '';
		$html .= '<table style="width:100%; border-collapse: collapse; border: 1px solid black;">';  // Añadido: borde en la tabla
		$html .= '  <tr style="height:20px; background:lightblue;">';
		$html .= '    <th span=2>RUTA</th>';  // Añadido: borde en la celda
		$html .= '    <th style="border: 1px solid black;" colspan="' . $columnas . '" align="center">LUNES</th>';
		$html .= '    <th style="border: 1px solid black;" colspan="' . $columnas . '" align="center">MARTES</th>';
		$html .= '    <th style="border: 1px solid black;" colspan="' . $columnas . '" align="center">MIERCOLES</th>';
		$html .= '    <th style="border: 1px solid black;" colspan="' . $columnas . '" align="center">JUEVES</th>';
		$html .= '    <th style="border: 1px solid black;" colspan="' . $columnas . '" align="center">VIERNES</th>';
		$html .= '  </tr>';
		$capital = 0;
		$numero = 0;
		$fila = '';
		foreach ($data as $key => $value) {
			$html .= '  <tr>';
			if ($numero != $value['numero']) {
				$numero = $value['numero'];
				$html .= '  <td style="border-top:1px solid black;" align="center"><b>' . $value['nombre'] . '</b></td>';
				$fila = '1';
			} else {
				$html .= '  <td style="border-bottom:0px; border-top:0px;"></td>';
				$fila = '';
			}
			$html .= $this->col_horario_dia2($value['lunes'], $fila, $global);
			$html .= $this->col_horario_dia2($value['martes'], $fila, $global);
			$html .= $this->col_horario_dia2($value['miercoles'], $fila, $global);
			$html .= $this->col_horario_dia2($value['jueves'], $fila, $global);
			$html .= $this->col_horario_dia2($value['viernes'], $fila, $global);
			$html .= '  </tr>';
		}
		$html .= '</table>';
		return $html;
	}



	private function col_horario_dia($data, $fila, $global){
		$miHtml='';
		$border='';
		if ($fila===''){
			$border= "border-bottom:0px; border-top:0px;";
		}else{
			$border= "border-bottom:0px;";
		}
		if ($data!=''){
			$parts = explode('|', $data,6);
			if (sizeof($parts)>5){
				$nombre= $parts[1].' ('.$parts[4].', $'.number_format($parts[5], 2, '.', ',').')';
			}else{
				$nombre= $parts[1].' ('.$parts[4].')';
			}
			
			if ($global=='1'){
				$miHtml.='  <td align="right" style="width:5px; border-right:0px;'.$border.'" >'.$parts[3].'</td>';
				$miHtml.='  <td align="right" style="width:15px; border-left:0px; border-right:0px;'.$border.'" >'.$parts[0].'</td>';
			}else{
				$miHtml.='  <td align="right" style="width:15px; border-right:0px;'.$border.'" >'.$parts[0].'</td>';
			}
			$miHtml.='  <td style="border-left:0px; border-right:0px; '.$border.'">'.$nombre.'</td>';
			$miHtml.='  <td style="width:15px; border-left:0px;'.$border.'">'.$parts[2].'</td>';
		}else{
			if ($global=='1'){
				$miHtml.='  <td style="width:5px; border-right:0px;'.$border.'"></td>';
				$miHtml.='  <td style="width:15px; border-left:0px; border-right:0px;'.$border.'"></td>';
			}else{
				$miHtml.='  <td style="width:15px; border-right:0px;'.$border.'"></td>';
			}
			$miHtml.='  <td style="border-left:0px; border-right:0px; '.$border.'"></td>';
			$miHtml.='  <td style="width:15px; border-left:0px; '.$border.'"></td>';
		}
		return $miHtml;
	}
	
	private function col_horario_dia2($data, $fila, $global)
	{
		$miHtml = '';
		$border = '';
		if ($fila === '') {
			$border = "border-bottom:0px; border-top:0px;";
		} else {
			$border = "border-top:1px solid black;";
		}
		if ($data != '') {
			$parts = explode('|', $data, 6);
			if (sizeof($parts) > 5) {
				$nombre = $parts[1] . ' (' . $parts[4] . ', $' . number_format($parts[5], 2, '.', ',') . ')';
			} else {
				$nombre = $parts[1] . ' (' . $parts[4] . ')';
			}

			if ($global == '1') {
				$miHtml .= '  <td align="right" style="width:5px; border-right:0px;' . $border . '" >' . $parts[3] . '</td>';
				$miHtml .= '  <td align="right" style="width:15px; border-left:0px; border-right:0px;' . $border . '" >' . $parts[0] . '</td>';
			} else {
				$miHtml .= '  <td align="right" style="width:15px; border-left:1px solid black; padding-left: 5px; padding-right: 5px;' . $border . '" >' . $parts[0] . '</td>';
			}
			$miHtml .= '  <td style="border-left:0px; border-right:0px; padding-left: 5px; padding-right: 5px;' . $border . '">' . $nombre . '</td>';
			$miHtml .= '  <td style="width:15px; border-left:0px; padding-left: 5px; padding-right: 5px;' . $border . '">' . $parts[2] . '</td>';
		} else {
			if ($global == '1') {
				$miHtml .= '  <td style="width:5px; border-right:0px;' . $border . '"></td>';
				$miHtml .= '  <td style="width:15px; border-left:0px; border-right:0px;' . $border . '"></td>';
			} else {
				$miHtml .= '  <td style="width:15px; border-left:1px solid black;' . $border . '"></td>';
			}
			$miHtml .= '  <td style="border-left:0px; border-right:0px; ' . $border . '"></td>';
			$miHtml .= '  <td style="width:15px; border-left:0px; ' . $border . '"></td>';
		}
		return $miHtml;
	}


	public function pdf_col_ficha_get(){
		//Se recibe unicamente el promotor y la fecha de reunion
		$idcolmena= $this->uri->segment(4);
		$fecha= $this->uri->segment(5);
		//$idPromotor = $this->ion_auth->user()->row()->id;
		//$idPromotor = 60;

		$fechaReunion = date_create($fecha);
		$dia = date_format($fechaReunion,'w');

		//$fechaReunion = new DateTime($dia);
		//Primera letra de la Empresa + Número colmena + numero grupo + fecha
		$sucursal ='ZIMATLAN';
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		if ($this->session->userdata('esquema')==="fin."){
			$empresa = 'F';
		}elseif ($this->session->userdata('esquema')==="ban."){
			$empresa = 'B';
		}elseif ($this->session->userdata('esquema')==="imp."){
			$empresa = 'I';
		}else{
			$empresa = '';
		}
		
		$header = $this->miHeaderReport('');
		$html = $header;
		
		$htmlPrint='';
		//Obtenemos las colmenas a visitar por el promotor en el día indicado
		$numeromas=0;	
		$fichas = $this->base->querySelect("SELECT c.idcolmena, c.idsucursal, c.numero, c.nombre, c.dia, c.idpromotor, c.empresa, g.numero AS numerogrupo
				FROM col.colmenas c
					JOIN security.users u ON c.idpromotor = u.id
					JOIN (select idcolmena, col_grupo as numero from col.col_personas where idcolmena=".$idcolmena." GROUP BY idcolmena, col_grupo) as g ON g.idcolmena = c.idcolmena 
				WHERE c.idcolmena=".$idcolmena." and dia=".$dia." and c.fechacierre is null
				ORDER BY c.idpromotor, c.horainicio, g.numero", TRUE);
		$intSalto=0;
		foreach($fichas as $key => $value) {
			$idPromotor = $value['idpromotor'];
			//print_r($value['idcolmena'].' -> '.$value['nombre']);
			$esquema = 'fin.';
			if ($value['empresa']==="B"){
				$esquema = 'ban.';
			}elseif($value['empresa']==="I"){
				$esquema = 'imp.';
			}
			$htmlFicha = $this->pdf_col_ficha_grupo_get($value['idcolmena'], $value['numerogrupo'], $fechaReunion, $empresa, $intSalto, $esquema);
			if ($intSalto===0){
				$intSalto=1;
			}
			$html.= $htmlFicha;
		}
		$html.='<div style="font-size:8px;"> </div>';
		$html.='<br><br>';
		$html.='
			</body>
		</html>';
		
		$this->printReport ($html);
	}	

	public function miHeaderReport($title){		
		$html ='<!DOCTYPE html>
			<html lang="en">
			<head>
					<meta http-equiv="Content-type" content="text/html; charset=utf-8" Content-Type:text/html; charset=utf-8 />
					<title>Reporte</title>
					<style  type="text/css">
						@page {
							margin: 1rem 1rem 1rem;
						}					
						body{							
							color: #000;
							background: #fff;
							text-align: justify;
							padding: 0px 30px;
							font-family: Arial, sans-serif;
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
						.round{
							border-radius: 5px;
							border-spacing: 0px;
						}
						.round2{
							border-radius: 5px;
							border-collapse: separate;
							padding: 5px;
						}
						table tr:nth-child(odd) td{
							background:Lavender;
						}
						table tr:nth-child(even) td{
							background:#fff;
						}

						th.cellcolor { background-color: GhostWhite }
						th.cell0 { font-weight: normal; border: 0px; padding:0px;}
						th.cell1 { font-weight: normal; border-bottom: 1px solid; border-right:0px; border-left:0px; border-top:0px; text-align:left;}

						.page_break {
							page-break-before: always;
						}

					</style>
				</style>
			</head>';
		return $html;		
	}

	private function pdf_col_ficha_grupo_get($idcolmena, $grupo, $fecha, $empresa, $salto, $esquema){
		$col = $this->base->querySelect("SELECT numero, nombre, promotor, empresa, dia_text, horainicio, substring(empresa,1,1) as empfolio FROM col.v_colmenas_directorio WHERE idcolmena=".$idcolmena.";", TRUE);		 
		$col= $col[0];
		$horaInicioFormateada = date('H:i', strtotime($col['horainicio']));
		$hoy = new DateTime();
		//$folio = $empresa.'-'.$col['numero'].'-'.$grupo.'-'.date_format($hoy,"Ymd");
		$folio = $col['empfolio'].'-'.$col['numero'].'-'.$grupo.'-'.date_format($hoy,"Ymd");
		
		$tesorera='';
		$cargo = $this->base->querySelect("SELECT p2.acreditado AS grupo_tesorera
			FROM col.grupos as g 
				JOIN col.grupo_cargo as gc ON g.idgrupo=gc.idgrupo
				LEFT JOIN get_acreditados p2 ON gc.idcargo2 = p2.acreditadoid
			WHERE g.idcolmena=".$idcolmena." and g.numero=".$grupo, TRUE);
		if ($cargo){
			$cargo=$cargo[0];
			$tesorera = $cargo['grupo_tesorera'];
		}

		//$miEmpresa = $this->getEmpresaCorto();
		//$htmlFicha ='';		
		$htmlFicha ='
			<div style="font-size:11px;">';        
		
		$htmlFicha.='<br>
			<div align="right" style="font-size:16px; color:red;"><b>'.$folio.'&nbsp; </b></div>';
		$htmlFicha.='
			<div align="center" style="font-size:16px;"><b>FICHA DE TESORERA PARA VENTANILLA </b></div>';
		$htmlFicha.='<br>
			<table style="width:100%; font-size: 13px" border="0">
				<tr>
					<th class="cell1" style="width:20%;">Semana:</th>
					<th class="cell1" style="width:45%;">'.date_format($fecha,"W").'</th>
					<th class="cell1" style="width:10%;">Fecha:</th>
					<th class="cell1" style="width:25%;">'.date_format($fecha,"d-m-Y").'</th>
				</tr>
				<tr >
					<th class="cell1" style="width:20%;">Colmena:</th>
					<th class="cell1" style="width:45%;">'.$col['nombre'].'</th>
					<th class="cell1" style="width:10%;">Número:</th>
					<th class="cell1" style="width:25%;">'.$col['numero'].'</th>
				</tr>
				<tr>
					<th class="cell1" style="width:20%;">Día y hora de reunión:</th>
					<th class="cell1" style="width:45%;">'.$col['dia_text'].', '.$horaInicioFormateada.'</th>
					<th class="cell1" style="width:10%;">Grupo:</th>
					<th class="cell1" style="width:25%;">'.$grupo.'</th>
				</tr>				
			</table>';
		$htmlFicha.='<br>
			<table style="width:100%; font-size: 11px;">
				<tr>
					<th class="cellcolor" style="width:10px; height: 35px;">No.</th>
					<th class="cellcolor" style="width:40px">Núm. de socia</th>
					<th class="cellcolor" style="width:250px">Nombre</th>
					<th class="cellcolor" style="width:50px">Pagos Registrados</th>
					<th class="cellcolor" style="width:25px">Nivel</th>
					<th class="cellcolor" style="width:2.2cm">Pago</th>
					<th class="cellcolor" style="width:2.2cm">Ahorro voluntario</th>
					<th class="cellcolor" style="width:2.2cm">Total</th>
				</tr>';
		$grupo = $this->base->querySelect("SELECT coalesce(p.col_numero,0)as col_numero, p.col_grupo, p.idacreditado, p.nombre, q.orden, g.num_pagos, g.pago_actual, g.nivel
											FROM 
												(SELECT col_numero, col_grupo, acreditadoid, idacreditado, nombre, orden
												FROM col.col_personas
												WHERE idcolmena=".$idcolmena." and col_grupo=".$grupo." ORDER BY col_numero, col_grupo, orden) as p
												RIGHT join (SELECT generate_series(1,5)as orden) as q ON p.orden=q.orden
												LEFT JOIN (SELECT c.idacreditado, c.idcredito, c.num_pagos, min(nivel) as nivel, 
													(CASE WHEN COALESCE(min(a.numero))>1 THEN min(a.numero)-1 ELSE NULL END) as pago_actual
															FROM public.creditos_g as c
																JOIN public.amortizaciones_g as a ON c.idcredito=a.idcredito and c.empresa=a.empresa
															WHERE a.fecha_pago is null
															GROUP BY c.idacreditado, c.idcredito, c.num_pagos
															) as g ON p.acreditadoid=g.idacreditado
											ORDER BY q.orden", TRUE);
		foreach($grupo as $key => $value) {
			$fila ='<tr style="width:100%; font-size: 13px;">';						
			$fila.='	<td style="height:30px">'.$value['orden'].'</td>';				
			if ($value['col_numero']===0){
				$fila.='	<td></td>';				
				$fila.='	<td></td>';
			}else{
				$fila.='	<td align="center">'.$value['idacreditado'].'</td>';				
				$fila.='	<td align="left">'.$value['nombre'].'</td>';				
			}
			if ($value['pago_actual']){
				$fila.='	<td align="center">'.$value['pago_actual'].'/'.$value['num_pagos'].'</td>';				
			}else{		
				$fila.='	<td ></td>';
			}
			if ($value['nivel']){
				$fila.='	<td align="center">'.$value['nivel'].'</td>';
			}else{		
				$fila.='	<td ></td>';
			}
			$fila.='	<td ></td>';
			$fila.='	<td ></td>';
			$fila.='	<td ></td>';
			$fila.='</tr>';						
			$htmlFicha.= $fila;
		}
		
		$htmlFicha.='<tr >
						<!-- <th class="cellcolor" style="width:10px; height:25px;"></th>
						<th class="cellcolor" ></th>
						<th class="cellcolor" ></th>
						<th class="cellcolor" ></th> -->
						<th class="cellcolor" colspan="5" align="right" style="padding-right: 15px; font-size:12px">TOTAL</th>
						<th class="cellcolor" style="width:10px; height:30px;"></th>
						<th class="cellcolor" ></th>
						<th class="cellcolor" ></th>
					</tr>';	

		$htmlFicha.='</table>';

		$htmlFicha.='
					<!-- <table class="round2" style="width:100%;" >
						<tr>
							<th class="cell0" align="center" >Depositar a:  <b>FINCOMUNIDAD</b></th>
						</tr>
						<tr border="0">
							<th class="cell0" align="center">Matriz: Rayón #704, Zimatlán de Álvarez, Oaxaca. Tel. 57 1 63 88</b></th>
						</tr>
						<tr border="0">
							<th class="cell0" align="center">Sucursal Oaxaca: 5a Priv. de la Noria #309 Int. 2, Centro, Oaxaca. Tel. 51 4 71 50</th>
						</tr>
					</table> -->
					<br>';		
		$htmlFicha.='<table  class="round2" style="width:100%; height:2cm; font-size: 12px;">
						<tr style="width:50%; height:1cm;">
							<th class="cell0" align="center">Tesorera de Grupo</th>
							<th class="cell0" align="center">Promotor (a) </th>
						</tr>
						<tr style="width:50%; height:1cm;">
							<th height="25px" class="cell0" align="left" style="padding-left: 40px;">'.$tesorera.'</td>
							<th class="cell0" align="left">'.mb_strtoupper($col['promotor'], 'UTF-8').'</td>
						</tr>
					</table>';		

		if ($salto===0){
			$html = $htmlFicha.'</div><br><br><div>'.$htmlFicha;
		}else{
			$html = '<div class="page_break"></div>'.$htmlFicha.'</div><br><br><div>'.$htmlFicha;
		}
		return $html;
	}	
	
	
	public function pdf_plan_pago2_get()
	{
		$sucursales = $this->base->selectRecord("public.sucursales", "", "", "", "","", "", $order_by, "","", TRUE);
		$direc="<table border='0' class='100p'>";
		$ban = true;
		$tit = " Rayón --- ";
		foreach($sucursales as $key => $value){
			if ($this->session->userdata('esquema' ) =="ban."  ){ 	    
				if ( $ban == true) { 
					$direc=$direc.'<td class="50p"><p>'.$value['domicilio'].' '.$value['colonia'].' C.P.'.$value['codpostal'].' '.$value['municipio'].' <br>Tel.'.$value['telefono1'].'</p></td>';	 
					$ban = false;
				}			
			}else {

				$direc=$direc.'<tr><td class="50p" align="center">'.$value['domicilio'].' '.$value['colonia'].' C.P.'.$value['codpostal'].' '.$value['municipio'].' Tel.'.$value['telefono1'].'</td></tr>';
			}
		}
		$direc=$direc.'</tr></table>';

		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$logo = $this->getLogo(45);

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "nomcolmena", "fecha_entrega_col");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito_ind", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$id_grupo = $cred['idgrupo'];
		if ($id_grupo === "0") {
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
		} else {
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "colmena_grupo");
			$where = array("idgrupo" => $id_grupo);
			$col = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
			$col = $col[0];
			$col_nombre = $col['colmena_nombre'];
			$col_numero = $col['colmena_numero'];
			$col_grupo = $col['colmena_grupo'];
		}

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal = 'ZIMATLAN';
		if ($cred['idsucursal'] === '02') {
			$sucursal = 'OAXACA';
		}
		$fecha = new DateTime($cred['fecha_entrega_col']);


		$usr = $this->base->querySelect("SELECT c.usuario, (u.first_name || ' ' || u.last_name) as nombre 
			FROM " . $this->session->userdata('esquema') . "creditos as c 
				JOIN security.users as u ON c.usuario=u.username or c.usuario=u.id::varchar
			WHERE idcredito=" . $idcredito, TRUE);
		$promotor = $usr[0]['nombre'];

		$data = $this->base->querySelect("SELECT sum(garantia) as total FROM (
			SELECT c.idcredito FROM " . $this->session->userdata('esquema') . "creditos as c
				JOIN (SELECT idacreditado, fecha FROM " . $this->session->userdata('esquema') . "creditos WHERE idcredito=" . $idcredito . ") as x ON c.idacreditado=x.idacreditado AND c.fecha<x.fecha
			ORDER BY c.fecha DESC limit 1) as c
			JOIN " . $this->session->userdata('esquema') . "amortizaciones as a ON c.idcredito=a.idcredito", TRUE);

		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total", "ajuste");
		$where = array("idcredito" => $idcredito);
		$order_by = array(array('campo' => 'numero', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "amortizaciones", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		$tabla = '';
		if ($cred['idproducto'] === '10') {
			$title = array("Pago", "Fecha<br>programada", "Capital", "Saldo capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
			$tabla .= $this->tableCreatePlanPagosInd($title, $amor);
		} else {
			$title = array("Pago", "Fecha<br>programada", "Capital", "Saldo capital", "Aporte solidario", "Garantia", "Gtia. acum.", "Total del pago", "Firma del promotor", "Ahorro<br>voluntario", "Fecha de recibido");
			$tabla .= $this->tableCreatePlanPagos($title, $amor);
		}

		$html = 
		'<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta http-equiv="Content-type" content="text/html; charset=utf-8" Content-Type:text/html; charset=utf-8 />
			<title>Plan de pagos</title>
			<style  type="text/css">
				@page {
					margin: 1rem 1rem 1rem;
				}
					
				body{
					
					color: #000;
					background: #fff;
					text-align: justify;
					padding: 0px 40px;
				}
				.fields {
					display: flex;
					-webkit-box-orient: horizontal;
					-webkit-box-direction: normal;	
					flex-direction: row;
					margin: 0 -.5em 1em;										
				}					
				.four.fields>.fields {
					width: 25%;
				}
				.titulo{
					text-align: center;
					padding:5px 0px;
					font-size:14px;
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
					font-weight: bold;
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
					font-weight: bold;
					border: 0px solid black;
				}					
				.seccion-center {
					text-align: center;
					padding:5px 0px;
					font-size:12px;
					font-weight: bold;
				}	

				.logo{
					font-size: 15px;
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
				table, th.1p {
					width: 1%;
				}
				table, th.10p {
					width: 10%;
				}
				table, th.25p {
					width: 24%;
				}
				table, th.50p {
					width: 100%;
				}
				table, th.75p {
					width: 75%;
				}
				table, td.linea-top {
					border-top: solid 1px;	
				}
				/* .nombre{
					border-bottom: 1px solid cornsilk;
					font-size: 24px;
					font-family: Courier, "Courier new", monospace;
					font-style: italic;
				}
				.descripcion{
					font-size: 24px;
					padding: 30px 0px;
				} */
				.image-top {
					top: -15px;
					position: absolute;
					float: right;

				}
				.span-right {
					padding-left: 85px;
				}
				.bordos {
					border: 1px solid black;
					border-radius: 0;
					padding: 5px;
				}

				/* .footer {
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
				} */
			</style>
		</head>';
		
		$html .= '
		<body>
			<div class="titulo">'. $logo . ' ' .$empresa. ' </div>
			<div style="font-size:11px;">' . $direc . ' </div>
			
			<hr>
			<div style="font-size:11px; line-height: 95%;">
			<h3 align="center">PLAN DE PAGOS</h3>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left" rowspan="2">
						SUCURSAL: ' . $cred['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th class="seccion-left">
						Colmena: ' . $col_numero . ' ' . $col_nombre . '
					</th>
					<th class="seccion-left">
						Fecha: ' . date_format($fecha, 'd/m/Y') . '
					</th>
					

				</tr>
				<tr>
					
					<th class="seccion-left">
						Grupo: ' . $col_grupo . '
					</th>
					<th class="seccion-left">
						Credito: ' . $cred['idcredito'] . '
					</th>
							
				</tr>	
				<tr>
					<th class="seccion-left" colspan="2">
						Socio: ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '
					</th>
					<th class="seccion-left">
						Próposito del crédito: ' . $cred['proy_nombre'] . '
					</th>
				</tr>
	
			</table>
			</div><div style="font-size:11px;">';
		$html .= '<br><div > </div>';
		$html .= $tabla;
		$html .= '<br>';
		$html .= '
			<table style="width:100%"  border="0">
			<tr>
				<td></td>
				<td align="center"  width="25%">Entregó<br><br><br>&nbsp;</td>
				<td></td>
				<td align="center"  width="25%">Recibió<br><br><br>&nbsp;</td>
				<td></td>
			</tr>		

			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">' . $promotor . '<br>Promotor</td>
				<td></td>
				<td style="border-top: 1px solid" align="center"  width="25%">' . $cred['nombre'] . '<br>Prestataria</td>
				<td></td>
			</tr>		
		</table>
		<br>
		<hr>
		<p style="font-size: 11px;">La información contenida en el presente documento es un Plan de Pagos resumido para fines informativos y de control de campo, por lo que podrá estar sujeta a cambios y bajo ninguna circunstancia podrá considerarse como una oferta vinculante, ni como la autorización formal de crédito por parte de la empresa.</p>
		';
		$html .= '
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
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}
	

	public function pdf_credactivos_get(){
		$idNivel= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		if ($idNivel == 0){
			$query_nivel = "";
		}else{
			$query_nivel = " and nivel=".$idNivel;
		}
		$hora = $this->base->querySelect("SELECT idsucursal, idcredito, fecha_dispersa::date, idacreditado, nombre, nivel, monto, garantia, grupo_numero, col_numero, col_nombre, promotor
										FROM ".$this->session->userdata('esquema')."rpt_credito_activo 
										WHERE not fecha_dispersa is null ".$query_nivel." and idsucursal='".$this->session->userdata('sucursal_id')."' ORDER BY nivel, fecha_dispersa", TRUE);
		$title = array("Suc", "Crédito", "Fecha", "Socia", "Nivel", "Monto", "Garantia", "Total", "Grupo", "Colmena", "Promotor");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_credactivos($title, $hora, '0');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CREDITOS ACTIVOS: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		if ($idNivel == 0){
			print_r($html);
			die();
		}else{
			$this->printReport ($html);
		}
	}

	public function pdf_credactivosg_get(){
		$idNivel= $this->uri->segment(4);
		$sucursal ='TODAS';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='TODAS';
		}
		if ($idNivel == 0){
			$query_nivel = "";
		}else{
			$query_nivel = " and nivel=".$idNivel;
		}
		$hora = $this->base->querySelect("SELECT idsucursal, idcredito, fecha_dispersa::date, idacreditado, nombre, nivel, monto, garantia, grupo_numero, col_numero, col_nombre, promotor
										FROM ".$this->session->userdata('esquema')."rpt_credito_activo 
										WHERE not fecha_dispersa is null ".$query_nivel." ORDER BY nivel, fecha_dispersa", TRUE);
		$title = array("Suc", "Crédito", "Fecha", "Socia", "Nivel", "Monto", "Garantia", "Total", "Grupo", "Colmena", "Promotor");
		$tabla = '';
		if ($hora){
			$tabla.= $this->table_credactivos($title, $hora, '0');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">CREDITOS ACTIVOS: '.$sucursal.' </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';
		if ($idNivel == 0){
			print_r($html);
			die();
		}else{
			$this->printReport ($html);
		}
	}	

	public function table_credactivos($title, $data) {		 
		$htmlInit='';
		$htmlInit.='<br><br> <table style="width:100%">';
		$htmlInit.='  <tr>';
		foreach($title as $key => $value) {
			$htmlInit.='    <th>'.$value.'</th>';
		}
		$htmlInit.='  </tr>';
		$Monto = 0;
		$Garantia = 0;
		$nivel = 0;
		$html = "";
		$tblNivel= $htmlInit;
		foreach($data as $key => $value) {
			
			if ($nivel != $value['nivel']){
				if ($nivel>1){
					$tblNivel.='</table>';
					$html.=$tblNivel;
				}
				$nivel = $value['nivel'];
				$tblNivel = $htmlInit;
			}
			$Monto = $value['monto'];
			$Garantia = $value['garantia'];
			$Total = $Monto + $Garantia;

			$tblNivel.='  <tr>';
			$tblNivel.='  <td style="width:5px;">'.$value['idsucursal'].'</td>';
			$tblNivel.='  <td style="width:15px;" align="right">'.$value['idcredito'].'</td>';
			$tblNivel.='  <td style="width:45px;">'.$value['fecha_dispersa'].'</td>';
			$tblNivel.='  <td style="width:150px;">'.$value['nombre'].'</td>';
			$tblNivel.='  <td style="width:5px;">'.$value['nivel'].'</td>';
			$tblNivel.='  <td style="width:40px;" align="right">'.number_format($Monto, 2, '.', ',').'</td>';
			$tblNivel.='  <td style="width:30px;" align="right">'.number_format($Garantia, 2, '.', ',').'</td>';
			$tblNivel.='  <td style="width:40px;" align="right">'.number_format($Total, 2, '.', ',').'</td>';
			$tblNivel.='  <td style="width:20px;">'.$value['grupo_numero'].'</td>';
			$tblNivel.='  <td style="width:100px;">'.$value['col_nombre'].'</td>';
			$tblNivel.='  <td style="width:80px;">'.$value['promotor'].'</td>';
			$tblNivel.='  </tr>';

			
		}
		$html.=$tblNivel.'</table>';
		return $html;
	}
	

	public function pdf_col_nivel_get(){
		$idPromotor= $this->uri->segment(4);
		$sucursal ='ZIMATLAN';		
		if ($this->session->userdata('sucursal_id')==='02'){
			$sucursal ='OAXACA';
		}
		$hora = $this->base->querySelect("SELECT  n1.idnivel, n1.nivel, n1.importe, n1.pf_capital as capital, n1.pf_aporte_sol as interes, (n1.pf_capital +n1.pf_aporte_sol) as total,  n1.pf_garantia as garantia,
					(n1.pf_capital +n1.pf_aporte_sol +n1.pf_garantia) as pago_semanal, (n1.pf_garantia * n1.numero_pagos) as garantia_total, n1.numero_pagos, n1.fecha_inicio
				FROM niveles as n1
					JOIN (SELECT nivel, numero_pagos, max(fecha_inicio)as fecha_inicio
						FROM niveles
						GROUP by nivel, numero_pagos
						order by nivel) as n2 ON n1.nivel=n2.nivel and n1.numero_pagos=n2.numero_pagos and n1.fecha_inicio=n2.fecha_inicio
				WHERE 
					n1.fecha_fin IS NULL
				ORDER BY n1.nivel, n1.numero_pagos", TRUE);
		$title = array("Nivel", "Crédito", "Capital", "Interés x pago", "Capital + interés", "Garantía", "Pago semanal", "Total garantía", "Pagos semanales");
		$tabla = '';
		if ($hora){
			$tabla.= $this->tableNiveles($title, $hora, '0');
		}
		$header = $this->headerReport('');
//		$html = $header;
		$html = $header.'
			<div style="font-size:11px;">
			<h3 align="center">Niveles de colmena  </h3>';        
		$html.='<div style="font-size:8px;">';
        $html.=$tabla;
		$html.='</div>';
		$html.='<br><br>';
		$html.='
		</div>
		</body>
		</html>
		';

		$this->printReport ($html);
	}

	public function tableNiveles($title, $data) {
		$html=''; //width:100%; 
		$html.='<table style="font-size:10px;" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		foreach($data as $key => $value) {
			$html.='  <tr>';
			$html.='  <td style="width:10px;" align="right">'.$value['nivel'].'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['importe'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['capital'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['interes'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['total'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['garantia'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['pago_semanal'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.number_format($value['garantia_total'], 2, '.', ',').'</td>';
			$html.='  <td style="width:20px;" align="right">'.$value['numero_pagos'].'</td>';
			$html.='  </tr>';
		}
		$html.='</table>';		
		return $html;
	}
	
	
	
}




