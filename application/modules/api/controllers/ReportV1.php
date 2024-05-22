<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/modules/api/controllers/CarteraV1.php');
//require_once(APPPATH.'/third_party/dompdf/dompdf_config.inc.php');

class ReportV1 extends CarteraV1
{

	public $base;
	public $esquema;
	public $session;
	public $uri;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('permisos');
		include_once(APPPATH . 'modules/api/controllers/Funciones.php');
	}

	// Función para generar el certificado de aportación social -- Fincomunidad
	public function aportacertif_get()
	{
		// Verifica si el usuario tiene permiso para acceder a la función
		$gruposPermitidos = ['admin', 'cartera', 'caja'];

		if (!verificarPermisos($gruposPermitidos)) {
			redirect('/', 'refresh'); // Redireccionar si no tiene permisos
		}

		// Se obtiene el ID de la persona
		$idpersona = $this->uri->segment(4);

		// Obtiene los datos de la persona
		$data = $this->findPersona($idpersona);
		$data =  $data['result'][0];

		// Obtiene la fecha
		$fechaHoy = new DateTime();
		$fechaHoy = $fechaHoy->format('Y-m-d');

		// Obtiene la última aportación social
		$last = $this->getAportaSocLast($data['acreditadoid'], $fechaHoy);

		if ($last['code'] == 200) {
			$fecha = new DateTime($last['result'][0]['fecha']);
		}

		// Convierte la fecha a letras
		$fechaEnLetras = convertirFechaLetras($fecha);

		$empresaLargo = $this->getEmpresaLargo();

		// Subtítulos
		$subtitulos = [
			'<h4>CERTIFICADO DE APORTACIÓN A CAPITAL SOCIAL</h4>',
		];

		// Genera el HTML del certificado
		$cabecera = generateBasicHeader('Certificado de Aportación Social', '14px');

		// Agrega el logo y el subtítulo (si están disponibles)
		$header = addLogoAndSubtitle($cabecera, $empresaLargo, $subtitulos, $this->getLogo(120));

		$html = $header;

		$html .= '<p align="right">CERTFICADO NO. ' . $data['idacreditado'] . ' </p>';
		$html .= '<p align="right">SOCIO NÚMERO: ' . $data['idacreditado'] . '</p>';

		$html .= '<br>
		<p align="justify" style="line-height: 1.5;">Este título representa y hace constar para todos los efectos legales a que haya lugar, la suscripción y pago de <b>UN CERTIFICADO DE APORTACIÓN A CAPITAL SOCIAL</b> de la Sociedad denominada ' . $empresaLargo . ' que realiza la C. ' . $data['nombre'] . ' con valor nominal de $100.00 (CIEN PESOS 00/100 M.N.) valor exhibido en su totalidad, misma que le da la calidad de Socio de esa sociedad confiriéndole por tal acto los derechos societarios, corporativos y económicos que le son propios, a partir del ' . $fechaEnLetras . ', de acuerdo a sus Estatutos Sociales.</p>
		<br><br>';
		$html .= '<p align = "left" style="line-height: 1.5;">Datos de FINCOMUNIDAD, S.A de C.V., S.F.C. <br>
        Fecha de constitución: 26 de Mayo del 2014<br>
        Notaria No. 84 Lic. José Jorge Enrique Zárate Ramírez<br>
        Instrumento Público No. 1305, Volumen XXXV<br>
        Domicilio Legal: Zimatlán de Álvarez, Oaxaca.<br>
        DATOS DE INSCRIPCIÓN EN EL REGISTRO PÚBLICO DE LA PROPIEDAD.<br>
        Del Distrito Judicial de Zimatlán de Álvarez, Oaxaca,<br>
        Registro 158 inscrito en el Tomo III, de la Sección Comercio con fecha 29 de Mayo del 2014.<br>
		</p>';

		$html .= '<br>';
		$html .= '<br>';
		$html .= '<br>';
		$html .= generarTabla2Firmas('Presidente del Consejo de Administración', 'C. NICANDRO VASQUEZ RUIZ', 'Gerente de Sucursal', '');
		$html .= '<br>';
		$html .= '<br>';
		$html .= '<page_footer>
				<p align ="left" style ="color:#839192; font-size:11px; border-top: 1px; bottom: 15px; position: absolute;">Este documento es válido sólo con sello o firma</p>
			</page_footer>';
		$html .= '
			</body>
			</html>
		';

		generarPDF($html, 'Certificado de Aportación Social', false);
	}

	// Reporte de cierre de bóveda -- Todos los esquemas
	public function bovedacierep2_get()
	{
		// Verifica si el usuario tiene permiso para acceder a la función
		$gruposPermitidos = ['admin', 'gerencial', 'contabilidad'];

		if (!verificarPermisos($gruposPermitidos)) {
			redirect('/', 'refresh'); // Redireccionar si no tiene permisos
		}

		// Obtener el ID del movimiento
		$idmov = $this->uri->segment(4);

		// Obtener datos del corte de bóveda
		$corteBoveda = $this->findCorteBoveda($idmov, 1);

		// Verificar si se encontraron datos
		if (empty($corteBoveda['mov']) || empty($corteBoveda['movdet'])) {
			// Definir mensaje de error
			$error_message = 'No se encontraron datos para el corte de bóveda con ID ' . $idmov;

			return;
		}

		$mov = $corteBoveda['mov'][0];
		$movdet = $corteBoveda['movdet'];

		// Obtiene empresa y fecha
		$empresaCorto = $this->getEmpresa();
		$fechaFinalMovimiento = date_create($mov['fecfinal']);
		$fechaFormateada = date_format($fechaFinalMovimiento, 'd/m/Y');
		$horaFormateada = date_format($fechaFinalMovimiento, 'H:i');
		$nombreResponsable = $mov['nomuser'];

		$sucursalId = $this->session->userdata('sucursal_id');
		$sucursalNombre = obtenerNombreSucursal($sucursalId);

		// Subtítulos
		$subtitulos = [
			'<p align="center" style="font-size: 16px"> SUCURSAL ' . $sucursalNombre . '<br>OPERACIONES DE BÓVEDA DEL ' . $fechaFormateada . '</p><hr>',
		];

		// Genera el encabezado del reporte
		$cabecera = generateBasicHeader('Cierre Final de Bóveda', '16px');

		// Agrega el logo y el subtítulo (si están disponibles)
		$header = addLogoAndSubtitle($cabecera, $empresaCorto, $subtitulos);

		$html = $header;

		//  Genera el HtMl del reporte
		$html .= '
        <p style="font-size: 10px">Fecha y hora de cierre: ' . $fechaFormateada . '  <b align="right" style="float:right">' . $horaFormateada . '</b></p>';

		$html .= '
        <div style="font-size:12px;">
        <table style="width:50%" border="0" align="center">
            <tr class="seccion">
                <td style="width:150px">Responsable de Bóveda: </td>
                <td>' . $nombreResponsable . '</td>
            </tr>                
            <tr class="seccion">
                <td>Tipo de transacción:  </td>
                <td>CIERRE FINAL DE BÓVEDA</td>
            </tr>
        </table>
		';

		$html .= '
        <table style="width:50%; border-top: 1px solid; " align="center">
            <tr style ="border-bottom: 1px solid; ">
                <th align="left">Operación</th>
                <th>Código</th>
                <th>Nombre</th>
            </tr>
            <tr>
                <td align="left">Caja</td>
                <td align="center"></td>
                <td align="center"></td>
            </tr>
            <tr>
                <td align="left">Banco</td>
                <td align="center"></td>
                <td align="center"></td>
            </tr>
        </table>
		</div>
		<div style="font-size:13px;">';

		if (!empty($movdet)) {
			$html .= $this->printDetalle($movdet);
		}

		$html .= '<br><br><br><br>' . generarTabla2Firmas('', 'Entregué Conforme', '', 'Recibí Conforme', $nombreResponsable);

		$html .= '
				</div>
			</body>
        </html>';

		generarPDF($html, 'Cierre Final de Bóveda.pdf');
	}



	public function bovedacierep_get()
	{
		if ($this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else {
			redirect('/', 'refresh');
		}
		$idmov = $this->uri->segment(4);
		$empresal = $this->getEmpresa();
		$data = $this->findCorteBoveda($idmov, 1);
		$mov = $data['mov'][0];
		$movdet = $data['movdet'];
		//$miFecha = date_create($mov['fecinicio']);
		// 29-06-2023
		$miFecha = date_create($mov['fecfinal']);
		$fechoy = date_format($miFecha, 'd/m/Y');
		$tiempo = date_format($miFecha, 'H:i');
		$responsable = $mov['nomuser'];
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			OPERACIONES DE BOVEDA DEL ' . $fechoy . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b></font>';

		$html .= $header;
		$html .= '
				<div style="font-size:12px;">';
		$html .= '<br>
				<table  style="width:100%" border="0">
					<tr class="seccion">
						<td></td>
						<td style="width:130px">Responsable de Bóveda: </td>
						<td>' . $responsable . '</td>
						<td></td>
					</tr>				
					<tr class="seccion">
						<td></td>
						<td>Tipo de transacción:  </td>
						<td>CIERRE FINAL DE BOVEDA</td>
						<td></td>
					</tr>

				</table>';

		$html .= '<br>
				<table style="width:50%; border-top: 1px solid; " align="center">
	 			    <tr style ="border-bottom: 1px solid; ">
				   		<th align="left">Operación</th>
				   		<th>Código</th>
				   		<th>Nombre</th>
					</tr>
					<tr>
						<td align="left">Caja</td>
						<td align="center"></td>
						<td align="center"></td>
					</tr>
					<tr>
						<td align="left">Banco</td>
						<td align="center"></td>
						<td align="center"></td>
					</tr>
				</table>';
		if ($movdet != []) {
			$html .= $this->printDetalle($movdet);
		}
		$html .= '<br>';
		$html .= '<br> ';
		$html .= '<br> <br> <br> <br> <br>
			<table style="width:100%" border="0" >
				<tr>
					<td style="border-top: 1px solid" align="center" width="25%">Entregué Conforme</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Recibí Conforme</td>
				</tr>		
				<tr>
					<td align="center">' . $responsable . '</td>
					<td></td>
					<td align="center"></td>
				</tr>
			</table>';
		$html .= '
			</div>
			</body>
			</html>
			';

		$this->printReport($html);
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



	public function saldoboveda_get()
	{
		if ($this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else {
			redirect('/', 'refresh');
		}
		$id = $this->uri->segment(4);
		$empresal = $this->getEmpresa();
		$data = $this->getSaldoBoveda($id);
		$mov = $data['mov'][0];
		$movdet = $data['result'];
		$miFecha = date_create($mov['fecinicio']);
		$fechoy = date_format($miFecha, 'd/m/Y');
		$tiempo = date_format($miFecha, 'H:i');
		$responsable = $mov['nomuser'];

		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . strtoupper($this->session->userdata('nomsucursal')) . '<br>
			SALDO DE BOVEDA DEL ' . $fechoy . ' ' . $tiempo . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b></font>';

		$html .= $header;
		$html .= '
				<div style="font-size:12px;">';
		$html .= '<br>
				<table  style="width:100%" border="0">
					<tr class="seccion">
						<td></td>
						<td style="width:130px">Responsable de Bóveda: </td>
						<td>' . $responsable . '</td>
						<td></td>
					</tr>				
				</table>';

		$html .= '<br>';
		$html .= $this->printDetalle($movdet, 1);
		$html .= '<br>';
		$html .= '<br> ';
		$html .= '
			</div>
			</body>
			</html>
			';
		$this->printReport($html);
	}

	public function saldoboveda2_get()
	{
		// Verificar permisos de usuario
		if (!$this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
			redirect('/', 'refresh');
		}

		// Obtiene el ID
		$id = $this->uri->segment(4);

		// Obtiene datos del saldo de bóveda
		$saldoBoveda = $this->getSaldoBoveda($id);
		$mov = $saldoBoveda['mov'][0];
		$movdet = $saldoBoveda['result'];

		// Obtiene empresa y fecha
		$empresaCorto = $this->getEmpresa();
		$fechaInicioMovimiento = date_create($mov['fecinicio']);
		$fechaFormateada = date_format($fechaInicioMovimiento, 'd/m/Y');
		$horaFormateada = date_format($fechaInicioMovimiento, 'H:i');
		$nombreResponsable = $mov['nomuser'];

		// Obtiene nombre de la sucursal apartir del ID
		$sucursalId = $this->session->userdata('sucursal_id');
		$sucursalNombre = obtenerNombreSucursal($sucursalId);

		// Subtítulos
		$subtitulos = [
			'<p align="center" style="font-size: 16px">SUCURSAL ' . $sucursalNombre . '<br>SALDO DE BÓVEDA DEL ' . $fechaFormateada . ' ' . $horaFormateada . '</p><hr>',
		];

		// Genera el encabezado del reporte
		$cabecera = generateBasicHeader('Saldo de Bóveda', '16px');

		// Agrega el logo y el subtítulo (si están disponibles)
		$header = addLogoAndSubtitle($cabecera, $empresaCorto, $subtitulos);

		$html = $header;

		//  Genera el HtMl del reporte
		$html .= '
        <p style="font-size: 10px">Fecha y hora de apertura: ' . $fechaFormateada . '  <b align="right" style="float:right">' . $horaFormateada . '</b></p>';

		$html .= '
        <div style="font-size:12px;">
        <table style="width:50%" border="0" align="center">
            <tr class="seccion">
                <td style="width:150px">Responsable de Bóveda: </td>
                <td><b>' . $nombreResponsable . '</b></td>
            </tr>                
        </table>
		</div>
		<div style="font-size:13px;">
		<br>
		';

		$html .= $this->printDetalle($movdet, 1);
		$html .= '
		</div>
		</body>
		</html>
		';

		generarPDF($html, 'Saldo de Bóveda.pdf');
	}

	public function bovedarep_get()
	{
		$idmov = $this->uri->segment(4);
		if ($this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else if ($this->ion_auth->in_group('caja')) {
			$query = "select usuario, tipo from " . $this->session->userdata('esquema') . "boveda_mov where idmovdet = " . $idmov;
			$data = $this->base->querySelect($query, TRUE);
			if ($data) {
				if ($data[0]['tipo'] == "O" || $data[0]['usuario'] != $this->ion_auth->user()->row()->id) {
					redirect('/', 'refresh');
				}
			}
		} else {
			redirect('/', 'refresh');
		}
		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$caja = $this->session->userdata('idcaja');
		$data = $this->findCorteCaja($idmov);
		$mov = $data['mov'][0];
		$movdet = $data['movdet'];

		$miFecha = date_create($mov['fecha']);
		$fechoy = date_format($miFecha, 'd/m/Y');
		$tiempo = date_format($miFecha, 'H:i');
		$responsable = "";
		$idcaja  = "";
		$nomcaja = "";
		$idbanco = "";
		$nombanco = "";
		$fechaIngreso = "";
		$horaIngreso = "";
		$titulobov = "OPERACIONES DE BÓVEDA DEL " . $fechoy;
		if ($mov['tipo'] == 'C') {
			$responsable = strtoupper($mov['nomuser2']);
			$titulo = "DEVOLUCIÓN DE CAJA";
			$idcaja = $mov['idbanco'];
			$nomcaja = $mov['nomcaja'];
		} else {
			if ($mov['movimiento'] == 'E') {
				$responsable = strtoupper($mov['nomuser1']);
				if ($mov['des_ori'] == "C") {
					$titulo = "DOTACIÓN A CAJA";
					$idcaja = $mov['idbanco'];
					$nomcaja = $mov['nomcaja'];
				} else {
					$idbanco = $mov['idbanco'];
					$nombanco = $mov['nombanco'];
					$titulo = "EGRESO A BANCO";
				}
			} else {
				$titulobov = "OPERACIONES CON BÓVEDA";
				$fechaIngreso = "Fecha: " . $fechoy;
				$horaIngreso = "Hora: " . $tiempo;
				$responsable = strtoupper($mov['nomuser2']);
				if ($mov['des_ori'] == "C") {
					$titulo = "DEVOLUCIÓN DE CAJA";
					$idcaja = $mov['idbanco'];
					$nomcaja = $mov['nomcaja'];
				} else {
					$titulo = "DOTACIÓN/MINISTRACIÓN DE BANCO A BÓVEDA";
					$idbanco = $mov['idbanco'];
					$nombanco = $mov['nombanco'];
				}
			}
		}
		if ($mov['des_ori'] == "C") {
			$desori = "Caja (" . $mov['idbanco'] . ")";
		} else {
			$desori = "Banco";
		}

		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b></font>';

		$html .= $header;
		$html .= '
				<div style="font-size:12px;">';
		$html .= '<br>
				<table  style="width:100%" border="0">
					<tr class="seccion">
						<td></td>
						<td style="width:130px">Responsable de Bóveda: </td>
						<td>' . mb_strtoupper($responsable) . '</td>
						<td>' . $fechaIngreso . '</td>
					</tr>				
					<tr class="seccion">
						<td></td>
						<td>Tipo de transacción:  </td>
						<td>' . $titulo . '</td>
						<td>' . $horaIngreso . '</td>
					</tr>

				</table>';

		$html .= '<br>
				<table style="width:50%; border-top: 1px solid; " align="center">
	 			    <tr style ="border-bottom: 1px solid; ">
				   		<th align="left">Operación</th>
				   		<th>Código</th>
				   		<th>Nombre</th>
					</tr>
					<tr>
						<td align="left">Caja</td>
						<td align="center">' . $idcaja . '</td>
						<td align="center">' . $nomcaja . '</td>
					</tr>
					<tr>
						<td align="left">Banco</td>
						<td align="center">' . $idbanco . '</td>
						<td align="center">' . $nombanco . '</td>
					</tr>
				</table>';

		$html .= $this->printDetalle($movdet);
		$html .= '<br>';
		$html .= '<br> ';
		$html .= '<br> <br> <br> <br> <br>
			<table style="width:100%" border="0" >
				<tr>
					<td style="border-top: 1px solid" align="center" width="25%">Entregué Conforme</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">Recibí Conforme</td>
				</tr>		
				<tr>
					<td align="center">' . $mov['nomuser1'] . '</td>
					<td></td>
					<td align="center">' . $mov['nomuser2'] . '</td>
				</tr>
			</table>';
		$html .= '
			</div>
			</body>
			</html>
			';

		$this->printReport($html);
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

	public function printDetalle($movdet, $saldo = 0)
	{
		$html = "";
		$html .= '<br><hr><br>
		<table style="width:50%;" align="center">
			 <tr>
				   <th>Denominación</th>
				   <th>Número</th>
				   <th>Total</th>
			</tr>';
		$billete = "";
		$totbillete = 0;
		$moneda = "";
		$totmoneda = 0;
		foreach ($movdet as $key => $value) {
			if ($saldo == 1) {
				$cantidad = $value['saldo'];
				$total =  $value['importe'];
			} else {
				$cantidad = $value['cantidad'];
				$total = $value['total'];
				if ($value['cantidad'] < 0) {
					$cantidad = ($value['cantidad'] * -1);
				}
				if ($value['total'] < 0) {
					$total = ($value['total'] * -1);
				}
			}

			if ($total > 0) {
				if ($value['nombre'] >= 20 && $billete == "") {
					$billete = "BILLETES";
					$html .= '<tr class="seccion">
									<td  align="center">' . $billete . '</td>';
					$html .= '	<td ></td>';
					$html .= '	<td ></td> 
							</tr>';
				}
				if ($value['nombre'] >= 20) {
					$totbillete = (float)$totbillete + (float)$total;
				} else {
					$totmoneda  = (float)$totmoneda + (float)$total;
				}
				if ($value['nombre'] < 20 && $moneda == "") {
					$html .= '<tr class="seccion">
									<td></td>';
					$html .= '	<td></td>';
					$html .= '	<td style="border-top:1px solid" align="right">' . number_format($totbillete, 2, '.', ',') . '</td> 
							</tr>';
					$moneda = "MONEDAS";
					$html .= '<tr class="seccion">
									<td  align="center">' . $moneda . '</td>';
					$html .= '	<td </td>';
					$html .= '	<td </td> 
							</tr>';
				}
				$html .= '<tr class="seccion">
								<td  align="center">' . $value['nombre'] . '</td>';
				$html .= '	<td  align="center">' . $cantidad . '</td>';
				$html .= '	<td  align="right">' . number_format($total, 2, '.', ',') . '</td> 
							</tr>';
			}
		}
		if ($totmoneda > 0) {
			$html .= '<tr class="seccion">
						<td></td>';
			$html .= '	<td></td>';
			$html .= '	<td style="border-top:1px solid" align="right">' . number_format($totmoneda, 2, '.', ',') . '</td> 
						</tr>';
		}

		$html .= '<tr><td></td><td></td><td></td> </tr><tr class="seccion">
			<td></td>';
		$html .= '	<td>Total Efectivo</td>';
		$html .= '	<td style="border-top:1px solid" align="right">' . number_format($totbillete + $totmoneda, 2, '.', ',') . '</td> 
			</tr>';
		$html .= '</table>';
		return $html;
	}


	public function bovedamov_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
		} else {
			redirect('/', 'refresh');
		}

		$idmov = $this->uri->segment(4);
		$fecini = $this->uri->segment(5);
		$fecfin = $this->uri->segment(6);

		$fecha = new DateTime();
		$fecini1 =  $fecha->format('d/m/Y');
		$fecfin2 =  $fecha->format('d/m/Y');

		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');

				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = date("Y-m-d");
			}
		} else {
			$fecini = date("Y-m-d");
		}

		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = date("Y-m-d");
			}
		} else {
			$fecfin = date("Y-m-d");
		}

		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$data = $this->findBovedaMov($idmov, $fecini, $fecfin);
		$movdet = $data['movdet'];
		//		$miFecha = date_create($mov['fecha']);
		$fechoy = date_format($fecha, 'd/m/Y');
		$tiempo = date_format($fecha, 'H:i');

		$titulobov = "DETALLES DE MOVIMIENTO DE BOVEDA DEL " . $fecini1 . " AL " . $fecfin2;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b></font>';

		$html .= $header;

		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th colspan="2">Ingreso</th>';
		$html .= '    <th colspan="2">Egreso</th>';
		$html .= '    <th colspan="2">Saldos</th>';
		$html .= '    <th></th>';
		$html .= '  </tr>';
		$html .= '  <tr>';
		$html .= '    <th align="left">Fecha</th>';
		$html .= '    <th>Origen</th>';
		$html .= '    <th>Destino</th>';
		$html .= '    <th>Tipo Movimiento</th>';
		$html .= '    <th>Efectivo</th>';
		$html .= '    <th>Cheques</th>';
		$html .= '    <th>Efectivo</th>';
		$html .= '    <th>Cheques</th>';
		$html .= '    <th>Efectivo</th>';
		$html .= '    <th>Cheques</th>';
		$html .= '    <th>Saldo</th>';
		$html .= '  </tr>';
		$primero = 1;

		foreach ($movdet as $key => $value) {
			$ingresos = 0;
			$egresos  = 0;
			if ($value['importe'] < 0) {
				$egresos = ($value['importe'] * -1);
			} else {
				$ingresos = ($value['importe']);
			}

			if ($primero == 1) {
				$html .= '  <tr>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right">' . number_format($value['saldo'] + $ingresos + $egresos, 2, '.', ',') . '</td>';
				$html .= '  </tr>';
				$primero == 2;
			}
			$miFecha = date_format(date_create($value['fecha']), 'd/m/Y');
			$html .= '  <tr>';
			$html .= '  <td>' . $miFecha . '</td>';
			$html .= '  <td>' . $value['origen'] . '</td>';
			$html .= '  <td>' . $value['destino'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td align="right">' . number_format($ingresos, 2, '.', ',') . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td align="right">' . number_format($egresos, 2, '.', ',') . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';
		$html .= '
			</div>
			</body>
			</html>
			';

		$this->printReport($html, 'landscape');
	}




	public function bovedaopera_get()
	{

		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
		} else {
			redirect('/', 'refresh');
		}

		$idmov = $this->uri->segment(4);
		$fecini = $this->uri->segment(5);
		$fecfin = $this->uri->segment(6);

		$fecha = new DateTime();
		$fecini1 =  $fecha->format('d/m/Y');
		$fecfin2 =  $fecha->format('d/m/Y');

		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');

				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = date("Y-m-d");
			}
		} else {
			$fecini = date("Y-m-d");
		}

		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = date("Y-m-d");
			}
		} else {
			$fecfin = date("Y-m-d");
		}
		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$data = $this->findBovedaOpera($idmov, $fecini, $fecfin);
		$saldoini = $this->findBovedaOperaIni($idmov, $fecini, $fecfin);
		$movdet = $data['movdet'];
		$movdetini = $saldoini['movdet'][0];

		//		$miFecha = date_create($mov['fecha']);
		$fechoy = date_format($fecha, 'd/m/Y');
		$tiempo = date_format($fecha, 'H:i');

		$titulobov = "HISTORIAL DE MOVIMIENTOS DE BOVEDA OPERATIVA DEL " . $fecini1 . " AL " . $fecfin2;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b>
			</font>';
		$html .= $header;
		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th colspan="4" >INGRESOS</th>';
		$html .= '    <th colspan="4">EGRESOS</th>';
		$html .= '    <th></th>';

		$html .= '  <tr>';
		$html .= '    <th align="left">Fecha</th>';
		$html .= '    <th>Dia de la semana</th>';
		$html .= '    <th>Dev.Caja</th>';
		$html .= '    <th>Ret.Caja</th>';
		$html .= '    <th>Req.Suc</th>';
		$html .= '    <th>Otro Ing.</th>';
		$html .= '    <th>Dotac.Caja</th>';
		$html .= '    <th>Dep.Banco</th>';
		$html .= '    <th>Env.Suc</th>';
		$html .= '    <th>Otro Egreso</th>';
		$html .= '    <th>Saldos</th>';
		$html .= '  </tr>';

		$ingresoCaja = 0;
		$egresoCaja = 0;
		$ingresoBanco = 0;
		$egresoBanco = 0;
		$saldo = 0;
		$fecha = '';
		$primero = 1;

		foreach ($movdet as $key => $value) {
			if ($fecha == '') {
				$fecha = $value['fecha'];
			}

			if ($primero == 1) {
				$saldo =  $movdetini['saldo'] - $movdetini['importe'];
				$html .= '  <tr>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right"></td>';
				$html .= '  <td></td>';
				$html .= '  <td align="right">' . number_format($saldo, 2, '.', ',') . '</td>';
				//					$html.='  <td align="right">'.number_format(0 , 2, '.', ',').'</td>';
				$html .= '  </tr>';
				$primero = 2;
			}



			if ($fecha <> $value['fecha']) {
				$html .= $this->totalBoveda($fecha, $ingresoCaja, $ingresoBanco, $egresoCaja, $egresoBanco, $saldo);

				$ingresoCaja = 0;
				$egresoCaja = 0;
				$ingresoBanco = 0;
				$egresoBanco = 0;
			}
			if ($value['orden'] == "A" && $value['movimiento'] == "I") {
				$ingresoCaja = $ingresoCaja + $value['importe'];
				$saldo = $saldo + $value['importe'];
			} else if ($value['orden'] == "A" && $value['movimiento'] == "E") {
				$egresoCaja = $egresoCaja + ($value['importe'] * -1);
				$saldo = $saldo - ($value['importe'] * -1);
			} else if ($value['orden'] == "Y" && $value['movimiento'] == "I") {
				$ingresoBanco = $ingresoBanco + $value['importe'];
				$saldo = $saldo + $value['importe'];
			} else if ($value['orden'] == "Y" && $value['movimiento'] == "E") {
				$egresoBanco = $egresoBanco + ($value['importe'] * -1);
				$saldo = $saldo - ($value['importe'] * -1);
			}

			$fecha = $value['fecha'];
		}
		if ($fecha != '') {
			$html .= $this->totalBoveda($fecha, $ingresoCaja, $ingresoBanco, $egresoCaja, $egresoBanco, $saldo);
		}

		$html .= '</table>';
		$html .= '
			</div>
			</body>
			</html>
			';
		$this->printReport($html, 'landscape');
	}


	public function totalBoveda($fecha, $ingresoCaja, $ingresoBanco, $egresoCaja, $egresoBanco, $saldo)
	{
		$diasarray = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
		$dia = $diasarray[date('w', strtotime($fecha))];

		$fecha1 = new DateTime();
		$fecha1->setDate(substr($fecha, 0, 4), substr($fecha, 5, 2), substr($fecha, 8, 2));
		$fechafor =  $fecha1->format('d/m/Y');

		$html = '';
		$html .= '  <tr>';
		$html .= '  <td>' . $fechafor . '</td>';
		$html .= '  <td>' . $dia . '</td>';
		$html .= '  <td align="right">' . number_format($ingresoCaja, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($ingresoBanco, 2, '.', ',') . '</td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($egresoCaja, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($egresoBanco, 2, '.', ',') . '</td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($saldo, 2, '.', ',') . '</td>';
		$html .= '  </tr>';
		return $html;
	}



	public function cajaopera_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
		} else {
			redirect('/', 'refresh');
		}
		$idsuc =  $this->session->userdata('sucursal_id');
		$idbov =  $this->uri->segment(4);
		$fecini = $this->uri->segment(5);
		$fecfin = $this->uri->segment(6);

		$fecha = new DateTime();
		$fecini1 =  $fecha->format('d/m/Y');
		$fecfin2 =  $fecha->format('d/m/Y');

		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');

				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = date("Y-m-d");
			}
		} else {
			$fecini = date("Y-m-d");
		}

		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = date("Y-m-d");
			}
		} else {
			$fecfin = date("Y-m-d");
		}
		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$data = $this->findCajaOpera($idbov, $idsuc, $fecini, $fecfin);
		//		$data = $this->findCajaOpera($idsuc,$fecini,$fecfin);
		$movdet = $data['movdet'];
		//		$miFecha = date_create($mov['fecha']);
		$fechoy = date_format($fecha, 'd/m/Y');
		$tiempo = date_format($fecha, 'H:i');

		$titulobov = "HISTORIAL DE MOVIMIENTOS DE CAJA OPERATIVA DEL " . $fecini1 . " AL " . $fecfin2;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b>
			</font>';
		$html .= $header;
		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Fecha</th>';
		$html .= '    <th>Cajero</th>';
		$html .= '    <th>Dia de la semana</th>';
		$html .= '    <th>No.</th>';
		$html .= '    <th>Dotaciones</th>';
		$html .= '    <th>Ingresos</th>';
		$html .= '    <th>Egresos</th>';
		$html .= '    <th>Devoluciones</th>';
		$html .= '  </tr>';

		$dotacion = 0;
		$ingreso = 0;
		$egreso = 0;
		$devolucion = 0;
		$no = 0;
		$fecha = '';

		foreach ($movdet as $key => $value) {
			if ($fecha == '') {
				$fecha = $value['fecha'];
			}

			if ($fecha <> $value['fecha']) {
				$html .= $this->totalCaja($fecha, $no, $dotacion, $ingreso, $egreso, $devolucion);

				$dotacion = 0;
				$ingreso = 0;
				$egreso = 0;
				$devolucion = 0;
				$no = 0;
			}
			if ($value['cabo'] == "A") {
				$dotacion = $dotacion + $value['importe'];
			} else if ($value['cabo'] == "D") {
				$ingreso = $ingreso + $value['importe'];
			} else if ($value['cabo'] == "R") {
				$egreso = $egreso + ($value['importe'] * -1);
			} else if ($value['cabo'] == "Z") {
				$devolucion = $devolucion + ($value['importe'] * -1);
			}
			$no = $no + $value['no'];

			$fecha = $value['fecha'];
		}
		if ($fecha != '') {
			$html .= $this->totalCaja($fecha, $no, $dotacion, $ingreso, $egreso, $devolucion);
		}

		$html .= '</table>';
		$html .= '
			</div>
			</body>
			</html>
			';


		$this->printReport($html);
	}

	public function totalCaja($fecha, $no, $dotacion, $ingreso, $egreso, $devolucion)
	{
		$diasarray = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
		$dia = $diasarray[date('w', strtotime($fecha))];
		$fecha1 = new DateTime();
		$fecha1->setDate(substr($fecha, 0, 4), substr($fecha, 5, 2), substr($fecha, 8, 2));
		$fechafor =  $fecha1->format('d/m/Y');
		$html = '';
		$html .= '  <tr>';
		$html .= '  <td>' . $fechafor . '</td>';
		$html .= '  <td></td>';
		$html .= '  <td>' . $dia . '</td>';
		$html .= '  <td align="right">' . number_format($no, 2, '.', '') . '</td>';
		$html .= '  <td align="right">' . number_format($dotacion, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($ingreso, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($egreso, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($devolucion, 2, '.', ',') . '</td>';
		$html .= '  </tr>';
		return $html;
	}

	public function printReport($html, $orientation = 'portrait')
	{
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', $orientation);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	public function solcreditopdf_get()
	{
		if (!$this->ion_auth->in_group('cartera')  &&  !$this->ion_auth->in_group('caja')) {
			redirect('/', 'refresh');
		}
		$idpersona = $this->uri->segment(4);
		$data = $this->findSocioSol($idpersona, 0, FALSE);
		$sol = $data['persona'][0];
		$dom = $data['dom'][0];
		$ben = $data['ben'][0];
		$emp = $this->getDatosEmpresa();
		$emp = $emp['result'][0];

		$fecalta = new DateTime($sol['fechaalta']);
		$fechaletras = $this->getFechaLetras($fecalta);

		$fecha = new DateTime($sol['fecha_nac']);
		$fecnac =  $fecha->format('d-m-Y');

		$data = array(
			'Reg1' =>  array(
				'enca' => array('Primer Nombre', '', 'Segundo Nombre', '', 'Apellido Paterno', '', 'Apellido Materno'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['nombre1'], '', $sol['nombre2'], '', $sol['apaterno'], '', $sol['amaterno'])
			),
			'Reg2' =>  array(
				'enca' => array('Conocido como', '', 'Fec. Nacimiento', '', 'Sexo'),
				'size' => array('50p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['aliaspf'], '', $fecnac, '', $sol['sexoc'])
			),
			'Reg3' =>  array(
				'enca' => array('Estado Nacimiento', '', 'Estado Civil', '', 'Escolaridad', '', 'RFC'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['estadonac'], '', $sol['estadocivil'], '', $sol['escolaridadc'], '', $sol['rfc'])
			),
			'Reg4' =>  array(
				'enca' => array('CURP', '', 'IFE/INE', '', 'Otra', '', 'email'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['curp'], '', $sol['folio_ife'], '', $sol['otroiden'], '', $sol['email'])
			),
			'Reg5' =>  array(
				'enca' => array('Celular', '', 'Nombre Conyuge'),
				'size' => array('25p', '1p', '50p'),
				'datos' => array($sol['celular'], '', $sol['conyuge'])
			)
		);

		$data1 = array(
			'Reg1' =>  array(
				'enca' => array('Actividad', '', 'Monto Patrimonio', '', 'Experiencia de la actividad(años)', '', 'Teléfono del domilio laboral'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['actividad'], '', $sol['patrimonio'], '', $sol['experiencia'], '', $sol['teltrabajo'])
			),
			'Reg2' =>  array(
				'enca' => array('Domicilio Laboral', '', 'Referencia del domilio laboral'),
				'size' => array('50p', '1p', '50p'),
				'datos' => array($sol['domlaboral'], '', $sol['domlabref'])
			),
			'Reg3' =>  array(
				'enca' => array('Ingresos mensuales', '', 'Ingresos mensuales extraordinario', '', 'Egresos mensuales', '', 'Egresos mensuales extraordinario'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($sol['ingresomen'], '', $sol['ingresomenext'], '', $sol['egresomen'], '', $sol['egresomenext'])
			),
			'Reg4' =>  array(
				'enca' => array('No. de personas que dependen de Usted', '', 'Compromiso de ahorro'),
				'size' => array('50p', '1p', '25p'),
				'datos' => array($sol['dependientes'], '', $sol['ahorro'])
			)
		);

		$tipovivienda = "";
		switch ($dom['tipovivienda']) {
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
		$agua = $dom['aguapot'] == '0' ? 'No' : 'Si';
		$luz = $dom['enerelec'] == '0' ? 'No' : 'Si';
		$drenaje = $dom['drenaje'] == '0' ? 'No' : 'Si';

		$data2 = array(
			'Reg1' =>  array(
				'enca' => array('Direccion', '', 'No. Ext.', '', 'No. Int.', '', 'Referencia del domicilio'),
				'size' => array('50p', '1p', '10p', '1p', '10p', '1p', '25p'),
				'datos' => array($dom['direccion1'], '', $dom['noexterior'], '', $dom['nointerior'], '', $dom['direccion2'])
			),
			'Reg2' =>  array(
				'enca' => array('Estado', '', 'Municipio', '', 'Colonia', '', 'Código Postal'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '10p'),
				'datos' => array($dom['estado'], '', $dom['municipio'], '', $dom['colonia'], '', $dom['cp'])
			),
			'Reg3' =>  array(
				'enca' => array('Ciudad', '', 'Tiempo de radicar en el domicilio actual(años)', '', 'Telefono actual'),
				'size' => array('25p', '1p', '25p', '1p', '25p'),
				'datos' => array($dom['ciudad'], '', $dom['tiempo'], '', $dom['telefono'])
			),
			'Reg4' =>  array(
				'enca' => array('Tipo de Vivienda', '', 'Agua Potable', '', 'Energía eléctrica', '', 'Drenaje'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($tipovivienda, '', $agua, '', $luz, '', $drenaje)
			)
		);
		$data3 = array();
		$data3 = array(
			'Reg1' =>  array(
				'enca' => array('Primer Nombre', '', 'Segundo Nombre', '', 'Apellido Paterno', '', 'Apellido Materno'),
				'size' => array('25p', '1p', '25p', '1p', '25p', '1p', '25p'),
				'datos' => array($ben['nombre1'], '', $ben['nombre2'], '', $ben['apaterno'], '', $ben['amaterno'])
			),
			'Reg2' =>  array(
				'enca' => array('Conocido como', '', 'RFC', '', 'celular'),
				'size' => array('50p', '1p', '25p', '1p', '25p'),
				'datos' => array($ben['aliaspf'], '', $ben['rfc'], '', $ben['celular'])
			),
			'Reg3' =>  array(
				'enca' => array('Parentesco', '', 'Participacion (%)',),
				'size' => array('25p', '1p', '25p'),
				'datos' => array($ben['parentesco'], '', $ben['porcentaje'])
			)
		);

		$header = $this->headerReport('SOLICITUD DE INGRESO DE SOCIOS');
		$html = $header . '
			<div style="font-size:10.8px;">
			<h4 class="encatitulo">DATOS PERSONALES</h4><div style="font-family: Arial, Helvetica, sans-serif;">';
		$html .= $this->tableCreate($data);
		$html .= '</div><br>
			<h4 class="encatitulo">ACTIVIDAD ECONÓMICA</h4><div style="font-family: Arial, Helvetica, sans-serif;">';
		$html .= $this->tableCreate($data1);
		$html .= '</div><br>
			<h4 class="encatitulo">DOMICILIO DEL SOLICITANTE</h4><div style="font-family: Arial, Helvetica, sans-serif;">';
		$html .= $this->tableCreate($data2);
		$html .= '</div><br>
			<h4 class="encatitulo">BENEFICIARIO</h4><div style="font-family: Arial, Helvetica, sans-serif;">';
		$html .= $this->tableCreate($data3);

		$html .= '</div><br>
		<div style="font-family: Arial, Helvetica, sans-serif;">
		<p align="center" style="padding-left: 5px; padding-right: 10px; border: 1px solid rgba(34,36,38,.15)!important; font-family: Arial, Helvetica, sans-serif;">
		Recibí la información del funcionamiento, organización, reglas y políticas de operación de la institución,
		solicito voluntariamente mi ingreso como socio y me comprometo a cumplir las obligaciones que emanen de tal calidad. 
		</p>
		<p>NOMBE Y FIRMA DEL SOLICITANTE: <strong>' . $sol['nombre1'] . ($sol['nombre2'] != '' ? ' ' . $sol['nombre2'] : '') . ' ' . $sol['apaterno'] . ' ' . $sol['amaterno'] . ' </strong></p>
		<p>LUGAR Y FECHA DE SOLICITUD: <strong>' . $emp["domicilio"] . ', ' . $emp["colonia"] . ', ' . $emp["municipio"] . '' . ' a ' . $fechaletras . '</strong></p>
		<p>SE RESERVA EL DERECHO DE ADMISIÓN CONFORME A LOS ESTATUTOS VIGENTES APROBADOS POR EL CONSEJO DE ADMINISTRACIÓN</p>
		</div>
		';
		$html .= '
		</div>
		</body>
		</html>
		';

		$this->printReport($html);
		/*
		// Load library
		$this->load->library('dompdf_gen');
		// Convert to PDF
		$this->dompdf->load_html($html, 'UTF-8');
		$this->dompdf->render();
		$this->dompdf->set_paper('letter', 'portrait');
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));
		$this->dompdf->stream("file.pdf", array("Attachment" => 0));
*/
	}


	public function creditosmov_get()
	{
		if ($this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else {
			redirect('/', 'refresh');
		}
		set_time_limit(0);
		$fecome = $this->uri->segment(4);
		$pantalla = $this->uri->segment(5);
		$empresal = $this->getEmpresa();
		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));

				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
		} else {
			$fecom =  date("Y-m-d");
			$fechoy =  date("d/m/Y");
		}

		//		$fecom =  date("2018-03-21");
		$tiempo =  date("H:m");

		$data = $this->findMovCreditos($fecom);
		$mov = $data['mov'];
		$resumen =  $data['resumen'];
		$titulobov = "MOVIMIENTOS DE CREDITOS AL " . $fechoy;
		$html = '
			<html>
			<body>
		';
		if ($pantalla == "P"  || $pantalla == "p") {
			$header = '<font size="15px" style="font-size: 16px;">';
		} else {
			$header = '<font size="18px">';
		}
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<div style="font-size:12px;"><br>';
		$html .= '</div>';

		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Hora</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th>Nombre</th>';
		$html .= '    <th align="right">Capital</th>';
		$html .= '    <th align="right">Interes</th>';
		$html .= '    <th align="right">IVA</th>';
		$html .= '    <th align="right">Total</th>';
		$html .= '  </tr>';
		foreach ($mov as $key => $value) {
			$capital =  $value['importe'] - $value['interes'] - $value['iva'];
			$miFecha = date_create($value['fecha']);
			$tiempo = date_format($miFecha, 'H:i');
			$html .= '  <tr>';
			$html .= '  <td>' . $tiempo . '</td>';
			$html .= '  <td>' . $value['nomov'] . '</td>';
			$html .= '  <td>' . $value['nomacre'] . '</td>';
			$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';

		$html .= '<div style="font-size:12px;"><br>';
		$html .= 'RESUMEN <hr>';
		$html .= '</div>';

		$html .= '<table style="font-size:10px; width:50%">';
		$html .= '  <tr>';
		$html .= '    <th align="center">Transacción</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th align="right">Capital</th>';
		$html .= '    <th align="right">Interes</th>';
		$html .= '    <th align="right">IVA</th>';
		$html .= '    <th align="right">Total</th>';
		$html .= '  </tr>';
		foreach ($resumen as $key => $value) {
			$html .= '  <tr>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$capital =  $value['importe'] - $value['interes'] - $value['iva'];
			$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';

		$html .= '
			</body>
			</html>
			';

		if ($pantalla == "P"  || $pantalla == "p") {
			print_r($html);
			die();
		} else {
			$this->printReport($html);
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



	public function cajamov_get()
	{
		if ($this->ion_auth->in_group('caja') || $this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else {
			redirect('/', 'refresh');
		}

		$idcajae = $this->uri->segment(4);
		$fecome = $this->uri->segment(5);
		$opc = $this->uri->segment(6);

		$empresal = $this->getEmpresa();

		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));

				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
			$idcaja = $idcajae;
			if ($idcajae == '0') {
				$idcaja = $this->session->userdata('idcaja');
			}
		} else {
			$fecom =  date("Y-m-d");
			$idcaja = $this->session->userdata('idcaja');
			$fechoy =  date("d/m/Y");
		}

		//		$fecom =  date("2018-03-21");
		$tiempo =  date("H:m");
		$data = $this->findMovCaja($idcaja, $fecom);
		if ($fecome != "") {

			$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
			if ($data['code'] == 200) {
				$user = $data['user'][0]['nombre'];
			}
		} else {
			$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		}

		if ($data['code'] == 200) {
			$mov = $data['mov'];
			$resumen =  $data['resumen'];
		} else {
			$mov = array();
			$resumen =  array();
		}


		$titulobov = "MOVIMIENTOS DE CAJA AL " . $fechoy;
		$html = '
			<html>
			<body>
		';
		if ($opc == "P"  || $opc == "p") {
			$header = '<font size="15px" style="font-size: 16px;">';
		} else {
			$header = '<font size="18px">';
		}
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<div style="font-size:12px;"><br>';
		$html .= 'Caja ' . $idcaja . ' ' . $user . '<hr>';
		$html .= '</div>';

		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Hora</th>';
		$html .= '    <th align="center">Transacción</th>';
		$html .= '    <th align="left">Sucursal</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th>Nombre</th>';
		$html .= '    <th align="right">Efectivo</th>';
		$html .= '    <th align="right">Saldo</th>';
		$html .= '  </tr>';


		foreach ($mov as $key => $value) {
			$miFecha = date_create($value['fecha']);
			$tiempo = date_format($miFecha, 'H:i');

			$html .= '  <tr>';
			$html .= '  <td>' . $tiempo . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['nomov'] . '</td>';
			$html .= '  <td>' . $value['nomacre'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}



		$html .= '</table>';
		$html .= '<div style="font-size:12px;"><br>';
		$html .= 'RESUMEN POR TRANSACCIÓN <hr>';
		$html .= '</div>';

		$html .= '<table style="font-size:10px; width:50%">';
		$html .= '  <tr>';
		$html .= '    <th align="center">Transacción</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th align="right">Importe</th>';
		$html .= '  </tr>';

		foreach ($resumen as $key => $value) {
			$html .= '  <tr>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			if ($value['importe'] < 0) {
				$html .= '  <td align="right">' . number_format(($value['importe'] * -1), 2, '.', ',') . '</td>';
			} else {
				$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
			}
			$html .= '  </tr>';
		}
		$html .= '</table>';
		$html .= '
			</body>
			</html>
			';


		if ($opc == 'p' || $opc == 'P') {
			print_r($html);
			die();
		} else {
			$this->printReport($html);
		}




		/*
			$this->load->library('dompdf_gen');
			$this->dompdf->load_html($html);
			$this->dompdf->set_paper('letter', 'portrait');
			$this->dompdf->render();	
			$canvas = $this->dompdf->get_canvas();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(550, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
			$this->dompdf->stream("file.pdf", array("Attachment" => 0));
			*/
	}



	public function cuenta_acre_get()
	{
		if (!$this->ion_auth->in_group('caja') && !$this->ion_auth->in_group('contabilidad') && !$this->ion_auth->in_group('gerencial')) {
			redirect('/', 'refresh');
		}

		$global =  $this->uri->segment(4);

		$fecha = new DateTime();
		$idacreditado = $_GET['id'];
		$cuentas = $_GET['cuentas'];
		$fecini = $_GET['fechai'];
		$fecfin =  $_GET['fechaf'];
		$fecini1 =  '';
		$fecfin2 =  '';




		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');
				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = '';
			}
		}
		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = '';
				$fecha = new DateTime();
				$fecfin2 = $fecha->format('d/m/Y');
			}
		}
		//		$idacreditado = $this->uri->segment(4);
		$empresal = $this->getEmpresa();
		$fechoy =  date("d/m/Y");
		$fecom =  date("Y-m-d");
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$data = $this->findCuentas($idacreditado, $cuentas, $fecini, $fecfin);
		$acre = $data['acreditada'];
		$mov = $data['mov'];
		$resumen =  $data['resumen'];
		$titulobov = "ESTADO DE CUENTA ";

		if ($fecini1 != '') {
			$titulobov = $titulobov . ' DEL ' . $fecini1;
		}
		if ($fecfin2 != '') {
			$titulobov = $titulobov . ' AL ' . $fecfin2;
		}


		$html = '
			<html>
			<body>
		';

		if ($global == "P"  || $global == "p") {
			$header = '<font size="15px" style="font-size: 16px;">';
		} else {
			$header = '<font size="18px">';
		}
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= $this->encaAcre($acre);

		$html .= '<hr>';
		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Fecha</th>';
		$html .= '    <th align="left">Hora</th>';
		$html .= '    <th align="center">Transacción</th>';
		$html .= '    <th align="left">Sucursal</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th align="right">Deposito</th>';
		$html .= '    <th align="right">Retiro</th>';
		$html .= '    <th align="right">Saldo</th>';
		$html .= '  </tr>';
		$cuenta = "";
		$valant = "";
		foreach ($mov as $key => $value) {
			if ($cuenta !== $value['orden'] . $value['cuenta']) {
				if ($cuenta !== "") {
					$valor = $valant;

					$html .= $this->resumencuentas($resumen, $valor);
				}
				$cuenta = $value['orden'] . $value['cuenta'];
				$tipoc = "";
				$tipoc = $value['nomcuenta'];

				$html .= '  <tr>';
				$html .= '  <td colspan="3" style="padding-top: 15px"><b>' . substr($cuenta, 1) . ' ' . $tipoc . '</b></td>';
				$html .= '  <td colspan="3">ABIERTA: ' . '</td>';
				$html .= '  <td >ESTATUS: ' . '</td>';
				$html .= '  <td >S. Ini.: ' . number_format($value['saldo'] - $value['importe'], 2, '.', ',') . '</td>';
				$html .= '  </tr>';
			}
			$valant = $value['orden'] . $value['cuenta'];

			$miFecha = date_create($value['fecha']);
			$fecmov = date_format($miFecha, 'd/m/Y');
			$tiempo = date_format($miFecha, 'H:i:s');
			$html .= '  <tr>';
			$html .= '  <td>' . $fecmov . '</td>';
			$html .= '  <td>' . $tiempo . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['nomov'] . '</td>';
			if ($value['importe'] < 0) {
				$html .= '  <td align="right">0.00</td>';
				$html .= '  <td align="right">' . number_format(($value['importe'] * -1), 2, '.', ',') . '</td>';
			} else {
				$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
				$html .= '  <td align="right">0.00</td>';
			}
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}

		$valor = $valant;
		$html .= $this->resumencuentas($resumen, $valor);

		$html .= '</table>';
		$html .= '
			</body>
			</html>
			';

		if ($global == 'p' || $global == 'P') {
			print_r($html);
			die();
		} else {
			$this->printReport($html);
		}
	}

	function encaAcre($acre)
	{
		$html = "";
		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left" style="width: 70px;">Socia:</th>';
		$html .= '    <th align="left" style="width: 90px;">' . $acre[0]['idacreditado'] . '</th>';
		$html .= '    <th align="left">' . $acre[0]['acreditado'] . '</th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '    <th></th>';
		$html .= '  </tr>';
		$html .= '  <tr>';
		$html .= '    <th align="left" >RFC:</th>';
		$html .= '    <th align="left" >' . $acre[0]['rfc'] . '</th>';
		$html .= '    <th align="left" style="white-space:pre">CURP:     ' . $acre[0]['curp'] . '</th>';
		$html .= '    <th align="left">' . '</th>';
		$html .= '    <th align="left">Telefono</th>';
		$html .= '    <th align="left">' . $acre[0]['celular'] . '</th>';
		$html .= '    <th align="left">Email</th>';
		$html .= '    <th align="left">' . $acre[0]['email'] . '</th>';
		$html .= '  </tr>';
		$html .= '  <tr>';
		$html .= '    <th align="left">Domicilio</th>';
		$html .= '    <th align="left" colspan="7">' . $acre[0]['direccion'] . '</th>';
		$html .= '  </tr>';
		$html .= '</table>';
		return $html;
	}


	function resumenCuentas($resumen, $valor)
	{
		$filtra = array_filter($resumen, function ($res) use ($valor) {
			return $res['orden'] . $res['cuenta'] === $valor;
		});
		$html = "";
		$html .= '  <tr>';
		$html .= '     <td>' . '</td>';
		$html .= '     <td>' . '</td>';
		$html .= '     <td style="font-size:12px;" colspan="2" align="center">RESUMEN</td>';
		$html .= '  </tr>';
		$html .= '  <tr>';
		$html .= '  <td>' . '</td>';
		$html .= '     <td>' . '</td>';
		$html .= '  <td colspan="0">      
							<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Transacción</th>';
		$html .= '    <th align="left">No</th>';
		$html .= '    <th align="right">Importe</th>';
		$html .= '  </tr>';
		foreach ($filtra as $key => $value2) {
			$html .= '  <tr>';
			$html .= '     <td>' . $value2['nombre'] . '</td>';
			$html .= '  	 <td>' . $value2['numero'] . '</td>';
			if ($value2['importe'] < 0) {
				$html .= '  <td align="right">' . number_format(($value2['importe'] * -1), 2, '.', ',') . '</td>';
			} else {
				$html .= '  <td align="right">' . number_format($value2['importe'], 2, '.', ',') . '</td>';
			}
			$html .= '  </tr>';
		}
		$html .= '		</table>
					</td>';
		$html .= '  </tr>';
		return $html;
	}

	public function cuenta_acre_resumen_get()
	{
		if (!$this->ion_auth->in_group('caja') && !$this->ion_auth->in_group('contabilidad') && !$this->ion_auth->in_group('gerencial')) {
			redirect('/', 'refresh');
		}
		$idacreditado = $_GET['id'];
		$cuentas = '';
		$_GET['cuentas'];

		//		$idacreditado = $this->uri->segment(4);
		$empresal = $this->getEmpresa();
		$fechoy =  date("d/m/Y");
		$fecom =  date("Y-m-d");
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$data = $this->findCuentasResumen($idacreditado, $cuentas);




		$acre = $data['acreditada'];
		$mov = $data['mov'];
		$titulobov = "ESTADO DE CUENTA RESUMEN";
		$html = '
			<html>
			<body>
		';

		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= $this->encaAcre($acre);

		$html .= '<hr>';
		$cuenta = "";
		$valant = "";
		foreach ($mov as $key => $value) {
			if ($cuenta !== $value['orden']) {
				$cuenta = $value['orden'];
				$tipoc = "";
				if ($value['orden'] == 'B') {
					$tipoc = "APORTE SOCIAL";

					$html .= '<b>' . $tipoc . '</b>';
					$html .= '<table style="font-size:10px; width:80%">';
					$html .= '  <tr>';
					$html .= '    <th align="left">Numero cuenta</th>';
					$html .= '    <th align="left">F.Apertura</th>';
					$html .= '    <th align="center">Estatus</th>';
					$html .= '    <th align="right">Total aportado</th>';
					$html .= '  </tr>';

					$valant = "1";
				} else if ($value['orden'] == 'D') {
					$tipoc = "AHORROS";

					if ($valant == "1") {
						$html .= '</table>';
					}
					$html .= '<b>' . $tipoc . '</b>';
					$html .= '<table style="font-size:10px; width:80%">';
					$html .= '  <tr>';
					$html .= '    <th align="left">Numero cuenta</th>';
					$html .= '    <th>F.Apertura</th>';
					$html .= '    <th>F.Cierre</th>';
					$html .= '    <th align="center">Estatus</th>';
					$html .= '    <th align="right">Saldo disponible</th>';
					$html .= '  </tr>';

					$valant = "1";
				} else if ($value['orden'] == 'E') {
					$tipoc = "AHORROS A PLAZO";

					if ($valant == "1") {
						$html .= '</table>';
					}
					$html .= '  <b>' . $tipoc . '</b>';
					$html .= '<table style="font-size:10px; width:80%">';
					$html .= '  <tr>';
					$html .= '    <th align="left">Numero cuenta</th>';
					$html .= '    <th>F.Emision</th>';
					$html .= '    <th>F.Vence</th>';
					$html .= '    <th align="right">Monto</th>';
					$html .= '    <th align="right">Plazo</th>';
					$html .= '    <th align="center">Estatus</th>';
					$html .= '    <th align="right">Rendimiento</th>';
					$html .= '    <th align="right">Impuestos</th>';
					$html .= '    <th align="right">Total</th>';
					$html .= '  </tr>';
				}
			}

			$miFecha = date_create($value['fecapertura']);
			$fecmov = date_format($miFecha, 'd/m/Y');

			$tiempo = date_format($miFecha, 'H:i:s');

			$status = $value['fecbaja'] == "" ? "ACTIVA" : "CANCELADA";
			if ($value['orden'] == 'B') {
				$html .= '  <tr>';
				$html .= '  <td>' . $value['cuenta'] . '</td>';
				$html .= '  <td>' . $value['fecapertura'] . '</td>';
				$html .= '  <td align="center">' . $status . '</td>';
				$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
				$html .= '  </tr>';
			} else if ($value['orden'] == 'D') {

				$html .= '  <tr>';
				$html .= '  <td>' . $value['cuenta'] . '</td>';
				$html .= '  <td>' . $value['fecapertura'] . '</td>';
				$html .= '  <td>' . $value['fecbaja'] . '</td>';
				$html .= '  <td align="center">' . $status . '</td>';
				$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';

				$html .= '  </tr>';
			} else if ($value['orden'] == 'E') {
			}
		}
		if ($cuenta != "") {
			$html .= '</table>';
		}
		$html .= '
			</body>
			</html>
			';

		$this->printReport($html);
	}



	public function cuenta_acre_saldos_get()
	{
		if (!$this->ion_auth->in_group('caja') && !$this->ion_auth->in_group('contabilidad') && !$this->ion_auth->in_group('gerencial')) {
			redirect('/', 'refresh');
		}

		$fecfin =  $_GET['fechaf'];
		$fecfin2 =  '';
		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {

				$fecha = new DateTime();
				$fecfin2 = $fecha->format('d/m/Y');
				$fecfin = $fecha->format('Y-m-d');
			}
		}


		$empresal = $this->getEmpresa();
		$fechoy =  date("d/m/Y");
		$fecom =  date("Y-m-d");
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$data = $this->findCuentaSaldos($fecfin);

		$mov = $data['saldos'];
		$encadata = $data['enca'];
		$titulobov = "RELACION DE CUENTAS AL " . $fecfin2;
		$html = '
			<html>
			<body>
		';

		$header = '<font size="15px" style="font-size: 16px;">';
		//	$header='<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<hr>';
		$cuenta = "";
		$nombre = "";
		$valant = "";

		$enca = array('', '', '', '', '', '', '', '', '');

		foreach ($encadata as $key => $value) {
			$enca[(float)$value['idproducto']] = $value['nombre'];
		}

		$totales = array(0, 0, 0, 0, 0, 0, 0, 0, 0);
		$totalesg = array(0, 0, 0, 0, 0, 0, 0, 0, 0);

		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left" style="width: 70px;">Socia:</th>';
		$html .= '    <th align="left" style="width: 90px;">Nombre</th>';
		$html .= '    <th>' . $enca[1] . '</th>';
		$html .= '    <th>' . $enca[2] . '</th>';
		$html .= '    <th>' . $enca[3] . '</th>';
		$html .= '    <th>' . $enca[4] . '</th>';
		$html .= '    <th>' . $enca[5] . '</th>';
		$html .= '    <th>' . $enca[6] . '</th>';
		$html .= '    <th>' . $enca[7] . '</th>';
		$html .= '    <th>' . $enca[8] . '</th>';
		$html .= '  </tr>';


		foreach ($mov as $key => $value) {

			if ($cuenta != $value['idacreditado'] && $cuenta != '') {
				$html .= '<tr>';
				$html .= '<td>' . $cuenta . ' </td>';
				$html .= '<td>' . $nombre . ' </td>';
				$html .= '<td align="right">' . number_format($totales[1], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[2], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[3], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[4], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[5], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[6], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[7], 2, '.', ',') . '</td>';
				$html .= '<td align="right">' . number_format($totales[8], 2, '.', ',') . '</td>';
				$html .= '</tr>';
				$totales[1] = 0;
				$totales[2] = 0;
				$totales[3] = 0;
				$totales[4] = 0;
				$totales[5] = 0;
				$totales[6] = 0;
				$totales[7] = 0;
				$totales[8] = 0;
			}
			$totales[(float)$value['idproducto']]  +=  $value['saldo'];
			$totalesg[(float)$value['idproducto']] += $value['saldo'];

			$cuenta = $value['idacreditado'];
			$nombre = $value['nombre'];
		}

		$html .= '<tr>
			<td></td>
			<td>Totales</td>
			 <td align="right">' . number_format($totalesg[1], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[2], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[3], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[4], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[5], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[6], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[7], 2, '.', ',') . '</td>
			 <td align="right">' . number_format($totalesg[8], 2, '.', ',') . '</td>

			</tr>
			';

		$html .= '</table>
			</body>
			</html>
			';

		print_r($html);
		die();

		$this->printReport($html);
	}







	public function tableCreateAmor($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		foreach ($data as $key => $value) {
			$html .= '  <tr>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$html .= '  <td>' . $value['fecha_vence'] . '</td>';

			if ($this->session->userdata('esquema') == 'ban.') {
				$html .= '  <td align="right">' . number_format($value['saldo_capital'], 2, '.', ',') . '</td>';
				$html .= '  <td align="right">' . $value['aportesol'] . '</td>';
				$html .= '  <td align="right">' . number_format($value['total'] - $value['aportesol'], 2, '.', ',') . '</td>';
				$html .= '  <td align="right">' . number_format($value['total'], 2, '.', ',') . '</td>';
			} else {
				$html .= '  <td align="right">' . number_format($value['saldo_capital'], 2, '.', ',') . '</td>';
				$html .= '  <td align="right">' . $value['interes'] . '</td>';
				$html .= '  <td align="right">' . $value['iva'] . '</td>';
				$html .= '  <td align="right">' . $value['capital'] . '</td>';
				//$html.='  <td>'.$value['aportesol'].'</td>';
				//$html.='  <td>'.$value['garantia'].'</td>';
				$html .= '  <td align="right">' . $value['total'] . '</td>';
			}
			/*
			foreach($value as $keydata => $valuedata)
				$html.='<td>'.$valuedata.'</td>';
			}
			*/
			$html .= '  </tr>';
		}
		$html .= '</table>';
		return $html;
	}


	// Función para generar o descargar el PDF
	private function generarPDF($html, $nombreArchivo)
	{
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));

		$documento = $this->dompdf->stream($nombreArchivo, array("Attachment" => 0));
	}

	public function polizadiario_get()
	{
		//Verificando los permisos del usuario
		$gruposPermitidos = ['admin', 'contabilidad'];

		if (!verificarPermisos($gruposPermitidos)) {
			redirect('/', 'refresh'); // Redireccionar si no tiene permisos
		}

		set_time_limit(0);
		$fecome = $this->uri->segment(4);
		$global = $this->uri->segment(5);
		$apantalla = "";
		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));

				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
		} else {
			$fecom =  date("Y-m-d");
			$fechoy =  date("d/m/Y");
		}

		$data = $this->findPolizaDiario($fecom);

		$empresal = $this->getEmpresa();
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;

		$mov = $data['mov'];
		$resumen =  $data['resumen'];

		$apantalla = "P";
		if ($global == "p" || $global == "P") {
			$apantalla = "P";
			$global = "";
		} else if ($global == "1") {
			$apantalla = "";
		}

		if ($global != "") {
			$titulobov = "PÓLIZA GLOBAL AL " . $fechoy;
		} else {
			$titulobov = "PÓLIZA DIARIO AL " . $fechoy;
		}
		$html = '
				<html>
					<head>
						<title>Póliza Diaria</title>
						<style>
							body {
								font-size: 13px;
								font-family: Arial, Helvetica, sans-serif;
							}

							p {
								text-align: center;
								font-size: 15px
							}

							table {
								font-size: 12px;
								width: 100%;
							}
						</style>
					</head>
					<body>
			';

		// if ($apantalla == "P") {
		// 	$header = '<style="font-size: 16px;">';
		// } else {
		// 	$header = '<font size="18px">';
		// }

		// Definiendo el encabezado del reporte
		$header = '
				<p>' . $empresal . '
					<br>
				SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal')) . '
					<br>
				' . $titulobov . '
				</p><hr>';
		$html .= $header;
		$html .= '<hr>';

		// Generando tabla para póliza diaria
		if ($global == "") {
			$html .= '<table>';
			$html .= '  <tr>';
			$html .= '    <th align="left">Hora</th>';
			$html .= '    <th align="left">No.</th>';
			$html .= '    <th align="left">Nombre</th>';
			$html .= '    <th align="right">Depósito</th>';
			$html .= '    <th align="right">Retiro</th>';
			$html .= '  </tr>';
			$cuenta = "";
			$valant = "";
			foreach ($mov as $key => $value) {
				if ($cuenta !== $value['nombre']) {
					if ($cuenta !== "") {
						$valor = $valant;
						$html .= $this->resumenPolDia($resumen, $valor, $global);
					}
					$cuenta = $value['nombre'];
					$html .= '  <tr>';
					$html .= '  	<td colspan="4" style="padding-top: 15px"><b>' . $cuenta . '</b></td>';
					$html .= '  </tr>';
				}
				$valant = $value['nombre'];

				$miFecha = date_create($value['fecha']);
				$fecmov = date_format($miFecha, 'd/m/Y');
				$tiempo = date_format($miFecha, 'H:i:s');
				$html .= '  <tr>';
				$html .= '  <td>' . $tiempo . '</td>';
				$html .= '  <td>' . $value['nomov'] . '</td>';
				$html .= '  <td>' . $value['nomacre'] . '</td>';
				if ($value['importe'] < 0) {
					$html .= '  <td align="right">0.00</td>';
					$html .= '  <td align="right">' . number_format(($value['importe'] * -1), 2, '.', ',') . '</td>';
					// $html .= '  <td align="right">' . formatearDecimal(($value['importe'] * -1)) . '</td>';
				} else {
					$html .= '  <td align="right">' . number_format($value['importe'], 2, '.', ',') . '</td>';
					// $html .= '  <td align="right">' . formatearDecimal($value['importe']) . '</td>';
					$html .= '  <td align="right">0.00</td>';
				}
				$html .= '  </tr>';
			}

			$valor = $valant;
			$html .= $this->resumenPolDia($resumen, $valor, $global);
			$html .= '</table>';
		} else {
			$valor = "";
			$html .= $this->resumenPolDia($resumen, $valor, $global);
		}

		$html .= '
				</body>
			</html>
			';

		if ($apantalla == "P") {
			print_r($html);
			die();
		}

		// Imprimir o descargar el PDF
		generarPDF($html, "Póliza Global Diaria.pdf");
	}

	function resumenPolDia($resumen, $valor, $global)
	{
		$esquema = $this->session->userdata('esquema');

		$html = "";
		if ($global == "") {
			$filtra = array_filter($resumen, function ($res) use ($valor) {
				return $res['nombre'] === $valor;
			});
			$html .= '  <tr>';
			$html .= '     <td>' . '</td>';
			$html .= '     <td>' . '</td>';
			$html .= '     <td style="font-size:12px;" colspan="2" align="center">RESUMEN</td>';
			$html .= '  </tr>';
			$html .= '  <tr>';
			$html .= '  <td>' . '</td>';
			$html .= '     <td>' . '</td>';
			$html .= '  <td colspan="0">';
		} else {
			$filtra = $resumen;
		}
		$html .= ' <table style="font-size:11px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Cuenta</th>';
		$html .= '    <th align="left">Concepto</th>';
		$html .= '    <th align="left">Cargo</th>';
		$html .= '    <th align="right">Abono</th>';
		$html .= '  </tr>';

		$primero = 0;
		foreach ($filtra as $key => $value2) {
			$html .= '  <tr>';
			$html .= '     <td>100</td>';
			if ($esquema == 'ban.' && (floatval($value2['garantia']) > 0.00 || $value2['nombre'] == 'RETIRO GARANTIA')) {
				$html .= '     <td>' . $value2['nombre'] . ' ' . $value2['colmena_numero'] . ' ' . $value2['colmena_nombre'] . '</td>';
			} else {
				$html .= '     <td>' . $value2['nombre'] . '</td>';
			}
			$html .= '  	 <td></td>';
			if ($value2['importe'] < 0) {
				$html .= '  <td align="right">' . number_format(($value2['importe'] * -1), 2, '.', ',') . '</td>';
				// $html.='  <td align="right">'.formatearDecimal(($value2['importe'] * -1)).'</td>';
			} else {
				if ($this->session->userdata('esquema') == 'ban.') {
					$html .= '  <td align="right">' . number_format($value2['importe'] + $value2['interes'], 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal($value2['importe'] + $value2['interes']).'</td>';	
				} else if ($this->session->userdata('esquema') == 'ama.') {
					$html .= '  <td align="right">' . number_format($value2['importe'] + $value2['mora'], 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal($value2['importe'] + $value2['mora']).'</td>';
				} else {
					$html .= '  <td align="right">' . number_format($value2['importe'], 2, '.', ',') . '</td>';
					// $html .= '  <td align="right">' . formatearDecimal($value2['importe']) . '</td>';
				}
			}
			$html .= '  </tr>';

			if ($value2['nombre'] == 'PAGO DE CREDITO' || $value2['nombre'] == 'PAGO DE CREDITO INDIVIDUAL' || $value2['nombre'] == 'PAGO INTERSUCURSAL DE CREDITO INDIVIDUAL' || substr($value2['nombre'], 0, 16) == 'INVERSION RETIRO') {
				$html .= '  <tr style="font-size:9px">';
				$html .= '     <td>100</td>';
				$html .= '     <td>&nbsp;&nbsp;&nbsp;CAPITAL</td>';
				$html .= '  	 <td></td>';
				if ($value2['capital'] < 0) {
					$html .= '  <td align="right">' . number_format(($value2['capital'] * -1), 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal(($value2['capital'] * -1)).'</td>';
				} else {
					$html .= '  <td align="right">' . number_format($value2['capital'], 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal($value2['capital']).'</td>';
				}
				$html .= '  </tr>';


				$html .= '  <tr style="font-size:9px">';
				$html .= '     <td>100</td>';
				if ($this->session->userdata('esquema') == 'ban.') {
					$html .= '     <td>&nbsp;&nbsp;&nbsp;APORTE SOLIDARIO</td>';
				} else {
					$html .= '     <td>&nbsp;&nbsp;&nbsp;INTERES</td>';
				}
				$html .= '  	 <td></td>';
				if ($value2['interes'] < 0) {
					$html .= '  <td align="right">' . number_format(($value2['interes'] * -1), 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal(($value2['interes'] * -1)).'</td>';
				} else {
					$html .= '  <td align="right">' . number_format($value2['interes'], 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal($value2['interes']).'</td>';
				}
				$html .= '  </tr>';

				if ($esquema == 'ban.' && floatval($value2['garantia']) > 0.00) {
					$html .= '  <tr style="font-size:9px">';
					$html .= '     <td>100</td>';
					$html .= '     <td>&nbsp;&nbsp;&nbsp;GARANTIA</td>';
					$html .= '  	 <td></td>';
					$html .= '  <td align="right">' . number_format(($value2['garantia']), 2, '.', ',') . '</td>';
					// $html.='  <td align="right">'.formatearDecimal(($value2['garantia'] )).'</td>';
					$html .= '  </tr>';
				}
				if ($esquema == 'ama.') {
					$html .= '  <tr style="font-size:9px">';
					$html .= '     <td>100</td>';
					$html .= '     <td>&nbsp;&nbsp;&nbsp;MORATORIOS</td>';
					$html .= '  	 <td></td>';
					if ($value2['mora'] < 0) {
						$html .= '  <td align="right">' . number_format(($value2['mora'] * -1), 2, '.', ',') . '</td>';
						// $html.='  <td align="right">'.formatearDecimal(($value2['mora'] * -1)).'</td>';
					} else {
						$html .= '  <td align="right">' . number_format($value2['mora'], 2, '.', ',') . '</td>';
						// $html.='  <td align="right">'.formatearDecimal($value2['mora']).'</td>';
					}
				}

				if ($esquema != 'ban.' && substr($value2['nombre'], 0, 16) != 'INVERSION RETIRO') {
					$html .= '  <tr style="font-size:9px">';
					$html .= '     <td>100</td>';
					$html .= '     <td>&nbsp;&nbsp;&nbsp;IVA</td>';
					$html .= '  	 <td></td>';
					if ($value2['iva'] < 0) {
						$html .= '  <td align="right">' . number_format(($value2['iva'] * -1), 2, '.', ',') . '</td>';
						// $html.='  <td align="right">'.formatearDecimal(($value2['iva'] * -1)).'</td>';
					} else {
						$html .= '  <td align="right">' . number_format($value2['iva'], 2, '.', ',') . '</td>';
						// $html.='  <td align="right">'.formatearDecimal($value2['iva']).'</td>';
					}
				}
				$html .= '  </tr>';
			}
		}
		$html .= '		</table>';
		if ($global == "") {
			$html .= '	</td>';
			$html .= '  </tr>';
		}

		return $html;
	}


	public function edocta_get()
	{
		if ($this->ion_auth->in_group('caja') || $this->ion_auth->in_group('gerencial') || $this->ion_auth->in_group('contabilidad')) {
		} else {
			redirect('/', 'refresh');
		}
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "fecha_entrega_col", "to_char(fecha_entrega_col,'DD/MM/YYYY') as fecha_entrega_col1", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "idproducto");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->session->userdata('esquema') . "get_solicitud_credito", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$montopagado = 0;
		$montocapital = 0;
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];
		$sucursal = 'ZIMATLAN';
		if ($cred['idsucursal'] === '02') {
			$sucursal = 'OAXACA';
		}
		$fecha = new DateTime($cred['fecha']);
		/* 		$fields = array("numero", "fecha_vence", "capital as capital", "interes as interes", "iva as iva", "saldo_capital","aportesol", "garantia", "total", "fecha_pago","idcaja","nomov","capital_pag","interes_pag","iva_pag");
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema')."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);
 */

		$query = "select b.numero, b.fecha_pago, b.fecha_vence, b.capital as capital, b.interes as interes, b.iva as iva, b.saldo_capital,b.aportesol, b.garantia, b.total, 
				b.fecha_pago,b.idcaja,b.nomov,b.capital_pag,b.interes_pag,b.iva_pag, x.dia as fecha_prov, x.pago_capital as capital_prov, x.int_pag as int_prov, x.iva_pag as iva_prov from 
				" . $this->session->userdata('esquema') . "amortizaciones as b left join (select  ROW_NUMBER () OVER (ORDER BY idprovision), a.* from 
				" . $this->session->userdata('esquema') . "credito_prov as a where a.pago_capital <>0 and a.idcredito =" . $idcredito . "  ) as x on b.idcredito = x.idcredito and 
				x.row_number = b.numero  where  b.idcredito = " . $idcredito . " order by b.numero asc";

		$amor = $this->base->querySelect($query, TRUE);



		$ultimorecord = $amor[count($amor) - 1];
		$datetime1 = date_create($ultimorecord['fecha_vence']);
		$datetime2 = date_create($cred['fecha_entrega_col']);

		$interval = date_diff($datetime1, $datetime2);
		$dias =  $interval->format('%a Dias');

		//2020-03-23 Dias segun tabla de niveles
		$query = "select dias FROM niveles WHERE nivel=" . $cred['nivel'] . " ORDER BY fecha_inicio desc limit 1";
		$nivel = $this->base->querySelect($query, TRUE);
		$nivel = $nivel[0];
		$dias = $nivel['dias'];
		// Daniel

		if ($this->session->userdata('esquema') == 'ban.') {
			$title = array("Semana", "Vencimiento", "Saldo Capital", "Aporte Solidario", "Capital", "Cuota");
		} else {
			$title = array("Semana", "Vencimiento", "Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		}
		$tabla = '';
		$tabla .= $this->tableCreateAmor($title, $amor);
		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h4 style="text-align:center">TABLA DE AMORTIZACIONES</h4>';
		$html .= '<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: ' . $cred['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th></th>
					<th class="seccion-right">
						Credito: ' . $cred['idcredito'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '
					</th>		
					<th class="seccion-left">
						Fecha: ' . $cred['fecha_entrega_col1'] . '
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Monto: ' . number_format($monto, 2, '.', ',') . '
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
						Plazo: ' . $dias . ' dias
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
						Producto: CREDITO COLMENA ' . $cred['nivel'] . '
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Oficial SF: 
					</th>		
				</tr>													
			</table>';
		$html .= '<br><br><div > </div>';
		$html .= $tabla;


		if ($this->session->userdata('esquema') == 'ama.' && $cred['idproducto'] == 10) {
			$fecom =  date("Y-m-d");
			$amor = $this->base->querySelect("SELECT numero, fecfinal as fecha, case when total_pago > 0 then fecfinal else null end as fecha_pago, dias, interesnormal,  capital as pag_capital, interes_n as interes, iva as iva, total_pago as total_pagado,
				saldo as saldo_capital, coalesce(interes_m,0) as int_mora, capital as capital_pagado, 0 as capital_requerido, 0 as c_interes_acumula, interesmoratorio as c_mora, 0 as c_iva, importe_acum, capital_acum, interesacumulado,
				 interes_n_acum, condona_n_acum, interesmora_acum, interes_m_acum, condona_m_acum, saldo  FROM " . $this->session->userdata('esquema') . "get_edocta_ind(" . $idcredito . ",'" . $fecom . "')", TRUE);

			$title = array("Pago", "Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. normal", "Int. mora",);

			$idproducto =  $cred['idproducto'];
			$tabla = $this->tableEdoCuenta($title, $amor, $idproducto);

			$html .= '<br><br>' . $tabla;
		} else {
			//			$amor = $this->base->querySelect("SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, p_capital_vig as pag_capital, p_interes_vig as interes, p_iva_vig as iva, p_pago_total as total_pagado,
			//				capital_saldo as saldo_capital, coalesce(p_interes_mora,0) as int_mora, capital_pagado, capital_requerido, 0 as c_interes_acumula, interes_mora as c_mora, iva_vig as c_iva  FROM ama.ftr_pago_individual(".$idcredito.", current_timestamp::timestamp)", TRUE);
			$title = array("Pago", "Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora",);


			$html .= '<br><br>';
			$html .= '<br>
				<table style="width:100%" >
					<tr class="seccion">
						<th >PAGO</th>
						<th width="130px">FECHA</th>
						<th>CAPITAL</th>';
			if ($this->session->userdata('esquema') == 'ban.') {
				$html .= '<th>APORTE SOLIDARIO</th>';
			} else {
				$html .= '<th>INTERES</th>
						<th>MORA</th>
						<th>IVA</th>';
			}
			$html .= '<th>PAGO</th>
						<th>SUC.PAGO</th>
						<th>NO.TRANS</th>
					</tr>';
			$esquema = $this->session->userdata('esquema');

			foreach ($amor as $key => $value) {
				if ($value['fecha_pago'] != "") {
					$html .= '  <tr>';
					$html .= '  <td>' . $value['numero'] . '</td>';
					$html .= '  <td style="width:100px">' . $value['fecha_pago'] . '</td>';

					if ($esquema == 'ban.') {
						$html .= '  <td align="right">' . ($value['total'] -  $value['aportesol'])  . '</td>';
						$html .= '  <td align="right">' . $value['aportesol'] . '</td>';
					} else {
						if ($value['capital_pag'] != 0) {
							$html .= '  <td align="right">' . $value['capital_pag'] . '</td>';
						} else {
							if ($esquema == 'fin.' || $esquema == 'imp.') {
								$html .= '  <td align="right">0.00</td>';
							} else {
								$html .= '  <td align="right">' . $value['capital'] . '</td>';
							}
						}
						if ($value['interes_pag'] != 0) {
							$html .= '  <td align="right">' . $value['interes_pag'] . '</td>';
						} else {
							if ($esquema == 'fin.' || $esquema == 'imp.') {
								$html .= '  <td align="right">0.00</td>';
							} else {
								$html .= '  <td align="right">' . $value['interes'] . '</td>';
							}
						}
						$html .= '  <td align="right"></td>';
						if ($value['iva_pag'] != 0) {
							$html .= '  <td align="right">' . $value['iva_pag'] . '</td>';
						} else {
							if ($esquema == 'fin.' || $esquema == 'imp.') {
								$html .= '  <td align="right">0.00</td>';
							} else {
								$html .= '  <td align="right">' . $value['iva'] . '</td>';
							}
						}
					}

					$html .= '  <td align="right">' . $value['total'] . '</td>';

					$html .= '  <td align="right">' . $sucursal . '</td>';
					$html .= '  <td align="right">' . $value['nomov'] . '</td>';
					$html .= '  </tr>';
					$montopagado = $montopagado + $value['total'];
					if ($value['capital_pag'] != 0) {
						$montocapital = $montocapital + $value['capital_pag'];
					} else {
						$montocapital = $montocapital + $value['capital'];
					}
				}
			}


			$html .= '			
				</table>';
			$html .= '<br>
				<table style="width:100%" >
					<tr>
						<td align="left">TOTAL PAGADO: ' . number_format($montopagado, 2, '.', ',') . '</td>
					</tr>		
					<tr>
						<td align="left">SALDO CAPITAL: ' . number_format($monto - $montocapital, 2, '.', ',') . '</td>
						<td align="left">DIAS VENCIDOS: </td>
					</tr>		
				</table>';
		}


		//	


		/*		
		if ($this->session->userdata('esquema')== 'ama.') {			
			$amor = $this->base->querySelect("SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, (p_capital_vig+p_capital_ven) as pag_capital, (p_interes_vig+p_interes_ven) as interes, 
				(p_iva_vig+p_iva_ven) as iva, p_pago_total as total_pagado,
				capital_saldo as saldo_capital, coalesce(p_interes_mora,0) as int_mora, capital_pagado, capital_requerido, (interes_vig+interes_ven) as c_interes_acumula, interes_mora as c_mora, (iva_vig+iva_ven+iva_mora) as c_iva 
				FROM ama.ftr_pago_individual(".$idcredito.", current_timestamp::timestamp)", TRUE);
			$title = array("Pago","Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora", );
			$tabla= $this->tableEdoCuenta($title, $amor);
			$html.='<br><br>'.$tabla;
		
		}else {
			
		$html.='<br><br>';
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
				</tr>';

				foreach($amor as $key => $value) {
					if ($value['fecha_pago'] != ""){
						$html.='  <tr>';
						$html.='  <td>'.$value['numero'].'</td>';
						$html.='  <td style="width:100px">'.$value['fecha_pago'].'</td>';
						if ($value['capital_pag'] != 0){
							$html.='  <td align="right">'.$value['capital_pag'].'</td>';
						}else {
							$html.='  <td align="right">'.$value['capital'].'</td>';
						}
						if ($value['interes_pag'] != 0){
							$html.='  <td align="right">'.$value['interes_pag'].'</td>';
						}else{
							$html.='  <td align="right">'.$value['interes'].'</td>';
						}
						$html.='  <td align="right"></td>';
						if ($value['iva_pag'] != 0){
							$html.='  <td align="right">'.$value['iva_pag'].'</td>';
						}else {
							$html.='  <td align="right">'.$value['iva'].'</td>';
						}
						$html.='  <td align="right">'.$value['total'].'</td>';
						$html.='  <td align="right">'.$sucursal.'</td>';
						$html.='  <td align="right">'.$value['nomov'].'</td>';
						$html.='  </tr>';
						$montopagado = $montopagado + $value['total'];
						if ($value['capital_pag'] != 0){
							$montocapital = $montocapital + $value['capital_pag'];
						}else {
							$montocapital = $montocapital + $value['capital'];
						}
					}
				}
		
		$html.='			
			</table>';
        $html.='<br>
			<table style="width:100%" >
				<tr>
					<td align="left">TOTAL PAGADO: '.number_format($montopagado, 2, '.', ',').'</td>
				</tr>		
				<tr>
					<td align="left">SALDO CAPITAL: '.number_format($monto - $montocapital, 2, '.', ',').'</td>
					<td align="left">DIAS VENCIDOS: </td>
				</tr>		
			</table>';				
			
		}
		*/

		$html .= '
		</div>
		</body>
		</html>
		';
		$this->printReport($html);

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


	public function aportasoc_get()
	{
		if (!$this->ion_auth->in_group('contabilidad')) {
			redirect('/', 'refresh');
		}
		$fecome = $this->uri->segment(4);
		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));
				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
		} else {
			$fecom =  date("Y-m-d");
			$fechoy =  date("d/m/Y");
		}
		$suc =  $this->uri->segment(5);
		$sSucursal = $suc == "99" ? "CONSOLIDADO" : 'SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal'));
		$empresal = $this->getEmpresa();
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$data = $this->findAportaSoc($fecom, $suc);
		$mov = $data['mov'];
		$titulobov = "APORTACION SOCIAL AL " . $fechoy;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px" style="font-size: 16px;">';
		$header .= '<p align="center">' . $empresal . '<br>
			' . $sSucursal . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<hr>';


		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th>Suc</th>';
		$html .= '    <th>NSoc</th>';
		$html .= '    <th>Nombre Socio</th>';
		$html .= '    <th align="right">Total Pag</th>';
		$html .= '    <th align="right">Parcial</th>';
		$html .= '    <th>F.Aport</th>';
		$html .= '    <th align="right">Apor</th>';
		$html .= '    <th>Nom.Estado</th>';
		$html .= '    <th>Nom.Municipio</th>';
		$html .= '    <th>Nom.Localidad</th>';
		$html .= '    <th>Ct</th>';
		$html .= '    <th>Dirección</th>';
		$html .= '    <th>FNacimi</th>';
		$html .= '    <th>Sexo</th>';
		$html .= '  </tr>';
		$cuenta = "";
		$valant = "";
		foreach ($mov as $key => $value) {
			$miFecha = date_create($value['fecha_alta']);
			$fecmov = date_format($miFecha, 'd/m/Y');
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . $value['parcial'] . '</td>';
			$html .= '  <td>' . $fecmov . '</td>';
			$html .= '  <td align="right">' . $value['apor'] . '</td>';
			$html .= '  <td>' . $value['estado'] . '</td>';
			$html .= '  <td>' . $value['municipio'] . '</td>';
			$html .= '  <td>' . $value['localidad'] . '</td>';
			$html .= '  <td>' . $value['ct'] . '</td>';
			$html .= '  <td>' . $value['direccion'] . '</td>';
			$html .= '  <td>' . $value['fecha_nac'] . '</td>';
			$html .= '  <td>' . $value['sexo'] . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';

		$html .= '
			</body>
			</html>
			';


		print_r($html);
		die();

		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('legal', 'landscape');
		$this->dompdf->render();

		$canvas = $this->dompdf->get_canvas();
		//		$font = Font_Metrics::get_font("helvetica", "bold");
		//		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}





	public function captaciones_get()
	{
		if (!$this->ion_auth->in_group('contabilidad')) {
			redirect('/', 'refresh');
		}
		$fecome = $this->uri->segment(4);
		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));
				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
		} else {
			$fecom =  date("Y-m-d");
			$fechoy =  date("d/m/Y");
		}
		$suc = $this->uri->segment(5);
		$sSucursal = $suc == "99" ? "CONSOLIDADO" : 'SUCURSAL ' . mb_strtoupper($this->session->userdata('nomsucursal'));
		$empresal = $this->getEmpresa();
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$data = $this->findCaptaciones($fecom, $suc);
		$data2 = $this->findCaptaciones2($fecom, $suc);
		$mov = $data['mov'];
		$mov2 = $data2['mov'];

		$titulobov = "CAPTACIONES AL " . $fechoy;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px" style="font-size: 16px;">';
		$header .= '<p align="center">' . $empresal . '<br>
			' . $sSucursal . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<hr>';


		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th>Suc</th>';
		$html .= '    <th>NSoc</th>';
		$html .= '    <th>Nombre Socio</th>';
		$html .= '    <th>Nombre Estado</th>';
		$html .= '    <th>Nombre Municipio</th>';
		$html .= '    <th>Nombre Localidad</th>';
		$html .= '    <th>Producto</th>';
		$html .= '    <th>F. Inicio</th>';
		$html .= '    <th>F. Venc</th>';
		$html .= '    <th>%</th>';
		$html .= '    <th align="right">Saldo</th>';
		$html .= '    <th align="right">Interes</th>';
		$html .= '    <th align="right">Interes Total</th>';
		$html .= '    <th align="right">Capital e Interes</th>';
		if ($this->esquema == "ban.") {
			$html .= '    <th>Colmena</th>';
			$html .= '    <th>Nombre Colmena</th>';
		}
		$html .= '  </tr>';
		$cuenta = "";
		$valant = "";
		foreach ($mov as $key => $value) {
			$miFecha = date_create($value['fecha_alta']);
			$fecmov = date_format($miFecha, 'd/m/Y');
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . strtoupper($value['estado']) . '</td>';
			$html .= '  <td>' . strtoupper($value['municipio']) . '</td>';
			$html .= '  <td>' . strtoupper($value['localidad']) . '</td>';
			$html .= '  <td>' .  strtoupper($value['producto']) . '</td>';
			$html .= '  <td>' . $fecmov . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td>' . $value['tasa'] . '</td>';
			$html .= '  <td align="right">' . number_format((round($value['saldo'], 2) - round($value['interesmes'], 2)), 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interesmes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			if ($this->esquema == "ban.") {
				$html .= '  <td>' . strtoupper($value['colmena_numero']) . '</td>';
				$html .= '  <td>' . strtoupper($value['colmena_nombre']) . '</td>';
			}
			$html .= '  </tr>';
		}

		foreach ($mov2 as $key => $value) {
			$miFecha = date_create($value['fecha_alta']);
			$fecmov = date_format($miFecha, 'd/m/Y');
			$miFecha = date_create($value['fecha_baja']);
			$fecbaja = date_format($miFecha, 'd/m/Y');
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . strtoupper($value['estado']) . '</td>';
			$html .= '  <td>' . strtoupper($value['municipio']) . '</td>';
			$html .= '  <td>' . strtoupper($value['localidad']) . '</td>';
			$html .= '  <td>' .  strtoupper($value['producto']) . '</td>';
			$html .= '  <td>' . $fecmov . '</td>';
			$html .= '  <td>' . $fecbaja . '</td>';
			$html .= '  <td>' . $value['tasa'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interesmes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo'] + $value['interesmes'], 2, '.', ',') . '</td>';
			if ($this->esquema == "ban.") {
				$html .= '  <td>' . strtoupper($value['colmena_numero']) . '</td>';
				$html .= '  <td>' . strtoupper($value['colmena_nombre']) . '</td>';
			}
			$html .= '  </tr>';
		}


		$html .= '</table>';





		$html .= '
			</body>
			</html>
			';
		print_r($html);
		die();
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		//		$font = Font_Metrics::get_font("helvetica", "bold");
		//		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}


	public function cartera_get()
	{
		if (!$this->ion_auth->in_group('contabilidad')) {
			redirect('/', 'refresh');
		}
		$fecome = $this->uri->segment(4);
		if ($fecome != "") {
			if ($fecome == "0") {
				$fecom =  date("Y-m-d");
				$fechoy =  date("d/m/Y");
			} else {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecome, 4, 4), substr($fecome, 2, 2), substr($fecome, 0, 2));
				$fecom =  $fecha->format('Y-m-d');
				$fechoy =  $fecha->format('d-m-Y');
			}
		} else {
			$fecom =  date("Y-m-d");
			$fechoy =  date("d/m/Y");
		}
		$suc = $this->uri->segment(5);

		if ($suc == "99") {
			$sSucursal = "CONSOLIDADO";
		} else {
			$sSucursal = "SUCURSAL " . mb_strtoupper($this->session->userdata('nomsucursal'));
		}
		$empresal = $this->getEmpresa();
		$tiempo =  date("H:m");
		$user = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
		$enca = array('SUC', 'SOCIO', 'NOMBRE DEL SOCIO', 'RFC', 'SEXO', 'ESTADO', 'MUNICIPIO', 'LOCALIDAD', 'ACTIVIDAD', 'Relacion Institucion', 'TIPO CRED', 'COND PAGO', 'F. EMIS', 'MONTO', 'FVENCE', 'T.INT', 'NPAG', 'FPAG', 'CVIGEN', 'CVENCI', 'SCAPIT', 'INTVIGNC', 'INT.VENC', 'FUPINT', 'FUPCAP', 'MUPINT', 'MUPCAP', 'DGARAN', 'DVEN', 'DIAXV', 'INTERES	MORA', 'NOMBRE PRODUCTO', 'NOMBRE PROMOTOR', 'CRED', 'C EXI', 'C NEXI', 'SLD. INS. PARTE CUB.', '% PARTE CUB.', 'ESTIM. PARTE CUB.', 'SLD. INS. PARTE EXP.', '% PARTE EXP.', 'IMP. CONS. PARTE. EXPUESTA', 'ESTIMACION. INT. VEN', 'IMPORTE TOTAL', '> 4000 UDIS');
		if ($this->session->userdata('esquema') == 'ban.') {
			array_push($enca, "COLMENA", "NOMBRE COLMENA");
		}
		$data = $this->findCartera($fecom, $suc);
		$mov = $data['mov'];
		$titulobov = "CARTERA AL " . $fechoy;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px" style="font-size: 16px;">';
		$header .= '<p align="center">' . $empresal . '<br>
			' . $sSucursal . '<br>
			' . $titulobov . '
		</p></font><hr>';
		$html .= $header;
		$html .= '<hr>';


		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';


		foreach ($enca as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}

		$html .= '  </tr>';


		$cuenta = "";
		$valant = "";
		foreach ($mov as $key => $value) {
			$html .= '  <tr>';
			foreach ($value as $key => $field) {
				$html .= '  <td>' . $field . '</td>';
			}
			$html .= '  </tr>';
		}
		$html .= '</table>';

		$html .= '
			</body>
			</html>
			';

		print_r($html);
		die();
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		//		$font = Font_Metrics::get_font("helvetica", "bold");
		//		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	// ACTUALIZACIÓN 07-09-2023
	public function contratoinver_get()
	{

		// Verifica si el usuario tiene permiso
		if (!$this->ion_auth->in_group('gerencial') && !$this->ion_auth->in_group('caja')) {
			redirect('/', 'refresh');
		}

		// Obteniendo el número de movimiento
		$nomov = $this->uri->segment(4);

		//Obteniendo nombre de empresa
		$enca = $this->getEmpresa();

		// Obteniendo datos de la inversión
		$query = "SELECT * FROM " . $this->session->userdata('esquema') . "get_inversiones WHERE numero = " . $nomov;
		$data = $this->base->querySelect($query, FALSE);
		$record = $data['result'][0];

		// Obteniendo el idpersona para obtener los beneficiarios
		$queryp = "SELECT * FROM acreditado a INNER JOIN personas p ON a.idpersona = p.idpersona 
		WHERE A.idacreditado = " . $record['nosocio'];
		$datap = $this->base->querySelect($queryp, FALSE);
		$record2 = $datap['result'][0];

		// Obteniendo los datos del beneficiario
		$sql = "SELECT pb.idpersona, idbeneficiario, porcentaje, rfc,
		nombre1, nombre2, apaterno, amaterno, direccion1, noexterior, d_codigo, d_asenta, d_mnpio, d_estado, d_cp
        FROM public.persona_ben pb
        INNER JOIN personas p ON pb.idbeneficiario = p.idpersona
		INNER JOIN persona_domicilio pd ON pd.idpersona = p.idpersona
		INNER JOIN localidades l ON pd.idcolonia = l.id_asenta_cpcons
        WHERE pb.idbeneficiario IN (
            SELECT idbeneficiario
            FROM persona_ben
            WHERE pb.idpersona = " . $record2['idpersona'] . "
        )";

		$resultados = $this->db->query($sql)->result_array();

		// Definiendo nombre para sucursal 01
		if ($record['idsucursal'] === '01') {
			$record['nomsuc'] = 'Zimatlán';
		}

		// Formatear el monto a letras
		$monto = $record['importe'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));

		// Formateando fechas
		$fecha = date_create($record['fecha']);
		$dia = date_format($fecha, 'd');
		$mes = date_format($fecha, 'm');
		$anio = date_format($fecha, 'Y');
		$fecha_fin = date_format(date_create($record['fechafin']), 'd/m/Y');

		// Obteniendo instrucción al vencimiento
		$instruccion = $record['numeroant'] == null ? 'ACREDITACIÓN EN CUANTO SE VENZA' : 'RENOVACIÓN';

		// Creando el encabezado del documento
		$header = $this->headerReport('');
		$html = $header . '<div style="font-size:12px;"><h2 align="center">Recibo de Depósito en Administración</h2>';

		// Creando tabla para la fecha
		$html .= '
		<div style="height: 40px; margin: 0 auto;">
			<table style="width: 20%; margin-left: auto;">';
		$html .= '<tr>
					<th>Día</th>
					<th>Mes</th>
					<th>Año</th>
				</tr>';
		$html .= '
				<tr>
					<td style="text-align: center">' . $dia . '</td>
					<td style="text-align: center">' . $mes . '</td>
					<td style="text-align: center">' . $anio . '</td>
				</tr>';
		$html .= '</table>
		</div>';

		// Tabla para datos generales de la socia
		$html .= '<h3>Datos Generales</h3>';
		$html .= '
		<div>
			<table style="font-size: 12px;">
				<tr>
					<th style="padding: 2px 0;">Sucursal</th>
					<td style="text-align: center">' . $record['nomsuc'] . '</td>
				</tr>
				<tr>
					<th style="padding: 2px 0;">Número de contrato</th>
					<td style="text-align: center">' . $record['numero'] . '</td>
				</tr>
				<tr>
					<th style="padding: 2px 0;">No. de Socia (o)</th>
					<td style="text-align: center">' . $record['nosocio'] . '</td>
				</tr>
				<tr>
					<th style="padding: 2px 0;">Nombre del Socio</th>
					<td style="text-align: center">' . $record['nombre'] . '</td>
				</tr>
				<tr>
					<th style="padding: 2px 0;">RFC</th>
					<td style="text-align: center">' . $record['rfc'] . '</td>
				</tr>';
		$html .= '
			</table>
		</div>';

		$html .= '
		<p style="font-size: 13px">Recibimos del depositante que se describe en el presente recibo, la cantidad de <strong>' . number_format($monto, 2, '.', ',') . ' (' . strtoupper($monto_letra) . ')</strong> en calidad de depósito en administración, el cual se manejará conforme a las condiciones de este documento.</p>';

		// Tabla para detalle de la inversión
		$html .= '
		<div>
			<table style="margin: 0 auto 25px;">
				<tr>
					<th style="padding: 8px 0;">Plazo (Días)</th>
					<th>Tasa</th>
					<th>Importe de intereses</th>
					<th>Fecha de vencimiento</th>
				</tr>
				<tr>
					<td style="text-align: center; padding: 15px 0;">' . $record['dias'] . '</td>
					<td style="text-align: center">' . number_format($record['tasa'], 2, '.', '') . '</td>
					<td style="text-align: center">' . number_format($record['interes'], 2, '.', ',') . '</td>
					<td style="text-align: center">' . $fecha_fin . '</td>
				</tr>';
		$html .= '
			</table>
		</div>';

		$html .= '<hr>
		<p>Instrucción al vencimiento: <strong>' . $instruccion . '</strong></p>';

		$html .= '<hr><h3>Beneficiarios</h3>';
		// Verifica si se encontraron resultados
		if (!empty($resultados)) {
			$html .= '<table style="width: 100%; margin: 0 0 35px 0">';
			$html .= '<tr>';
			$html .= '<th style="width: 30%; padding: 5px 2px">Nombre del Beneficiario</th>';
			$html .= '<th>Porcentaje</th>';
			$html .= '<th>RFC</th>';
			$html .= '<th>Dirección</th>';
			$html .= '<th>Colonia</th>';
			$html .= '<th>Municipio</th>';
			$html .= '<th>Estado</th>';
			$html .= '<th>CP</th>';
			$html .= '</tr>';

			// Itera a través de los resultados y construye las filas de la tabla
			foreach ($resultados as $beneficiario) {
				$html .= '<tr>';
				$html .= '<td style="width: 30%; padding: 5px 2px">' . $beneficiario['nombre1'] . ' ' . $beneficiario['nombre2'] . ' ' . $beneficiario['apaterno'] . ' ' . $beneficiario['amaterno'] .  '</td>';
				$html .= '<td style="text-align: center;">' . $beneficiario['porcentaje'] . '%</td>';
				$html .= '<td style="text-align: center;">' . $beneficiario['rfc'] . '</td>';
				$html .= '<td>' . $beneficiario['direccion1'] . ', ' . $beneficiario['noexterior'] . '</td>';
				$html .= '<td>' . $beneficiario['d_asenta'] . '</td>';
				$html .= '<td>' . $beneficiario['d_mnpio'] . '</td>';
				$html .= '<td style="text-align: center;">' . $beneficiario['d_estado'] . '</td>';
				$html .= '<td style="text-align: center;">' . $beneficiario['d_codigo'] . '</td>';
				$html .= '</tr>';
			}

			$html .= '</table>';
		} else {
			$html .= '<div style="margin-bottom: 15px">No se encontraron beneficiarios.</div>';
		}

		// Tabla para firmas
		$html .= '<hr><h4 align="center">Firmas de conformidad</h4>';
		$html .= '
			<table style="width:100%; font-size:12px;" border="0">	
				<tr>
					<td style="padding-bottom: 120px"></td>
					<td align="center" width="25%">' . $enca . '</td>
					<td></td>
					<td align="center" width="25%">DEPOSITANTE</td>
					<td></td>
				</tr>		
				<tr>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">FRANCISCO URIEL JERÓNIMO HERNÁNDEZ</td>
					<td></td>
					<td style="border-top: 1px solid" align="center" width="25%">' . $record['nombre'] . '</td>
					<td></td>
				</tr>
			</table>';

		$html .= '<div style="page-break-after:always;"></div>';

		$html .= '<p style="font-size:12px; text-align:center;"><b>CONTRATO DE DEPÓSITO DE DINERO EN ADMINISTRACIÓN</b></p>';

		$html .= '<p style="font-size:12px;  text-align:justify; ">CONTRATO DE DEPÓSITO DE DINERO Y/O DE TÍTULOS EN ADMINISTRACIÓN QUE CELEBRAN POR PARTE ' . $enca . ' COMO DEPOSITARIO, QUIEN EN LO SUCESIVO SE DENOMINARÁ “LA SOCIEDAD”, Y EL TITULAR DEL DEPÓSITO CUYO NOMBRE APARECE EN EL ANVERSO DE ESTE INSTRUMENTO, A QUIEN EN LO SUCESIVO SE DENOMINARÁ “EL DEPOSITANTE”, AL TENOR DE LAS SIGUIENTES:</p>';

		$html .= '<p style="font-size:12px; text-align:center;"><b>CLÁUSULAS</b></p><hr>';

		$html .= '
		<table style="width: 100%; font-size: 10px; border: 2px solid white; border-collapse: collapse;">
			<tr>
				<td  style="width: 50%; padding: 0 10px 0 0; vertical-align: top;">
					<p>1.- “LA SOCIEDAD”, recibirá de “EL DEPOSITANTE” sumas de dinero y/o títulos de renta fija para su administración, emitidos por "LA SOCIEDAD". Estos depósitos se documentarán en constancias o comprobantes que expida “LA SOCIEDAD” por la cantidad del depósito que en ella se precisa podrán denominarse en Moneda Nacional.<p>

					<p>2.- “EL DEPOSITANTE” designa como beneficiario(s) para el caso de fallecimiento a las(s) persona(s) mencionada(s) en el anverso de este contrato, en el porcentaje ahí previsto. “EL DEPOSITANTE” se reserva el derecho de cambiar beneficiario(s) en cualquier tiempo, debiendo en este acto acudir a las oficinas de “LA SOCIEDAD” para suscribir el formulario para el efecto diseñado. En caso de fallecimiento “LA SOCIEDAD”, entregará el importe correspondiente a lo(s) beneficiario(s) en los términos que señala el artículo 56 de la Ley de Instituciones de Crédito. El excedente se entregará en los términos previstos la legislación común.</p>

					<p>3.- Conforme el presente contrato “EL DEPOSITANTE” tendrá derecho a retirar, total o parcialmente, las condiciones de dinero y/o títulos en administración que hubiera depositado, de acuerdo al período de retiro del depósito o el día de vencimiento, tratándose de depósito de títulos en administración. Cuando el día de retiro escogido y/o vencimiento sea inhábil, “EL DEPOSITANTE” podrá efectuarlos el día hábil inmediato siguiente. Para efectuar retiros, “EL DEPOSITANTE” deberá presentar este contrato, la constancia del depósito y deberá identificarse a satisfacción de la “LA SOCIEDAD”.</p>

					<p>4.-Por las sumas que se mantengan en depósito “EL DEPOSITANTE” recibirá un interés de acuerdo a las tasas autorizadas por la “LA SOCIEDAD” para este tipo de operaciones, mismas que se expresan de forma anual. Dicho interés se calculará conforme a los días efectivamente transcurridos durante el período en el cual se devenguen a la tasa correspondiente. Los intereses serán pagados, en todos los casos, en Moneda Nacional, según el tipo de depósito, dentro del siguiente mes natural a aquel en que causen o al vencimiento. A la terminación del presente contrato “LA SOCIEDAD” liquidará a “EL DEPOSITANTE” los intereses devengados no pagados a esta fecha.</p>

					<p>5.-“EL DEPOSITANTE” autoriza a la “SOCIEDAD” para que los intereses que devenguen los depósitos se liquiden de acuerdo a las instrucciones anotadas en el anverso de este instrumento, así mismo para que “LA SOCIEDAD” retenga el impuesto correspondiente de acuerdo a la tasa señalada en la Ley de Impuesto sobre la renta en vigor.</p>

					<p>6.- Al vencimiento de los títulos depositados “EL DEPOSITANTE” podrá autorizar a “LA SOCIEDAD” para que los reinvierta en otros de naturaleza similar que estén en vigor, los cuales quedarán depositados en “LA SOCIEDAD” al amparo de este contrato. “EL DEPOSITANTE” podrá, sin embargo, presentar instrucción concreta en otro sentido a más tardar el día del vencimiento y el que, en forma específica, decida la aplicación del principal e intereses.</p>


				</td>
				<td  style="width: 50%; padding: 0 0 0 10px; vertical-align: top;">
					<p>7.- “EL DEPOSITANTE” deberá realizar todos los movimientos relacionados con las operaciones que ampara el presente contrato, en las oficinas de “LA SOCIEDAD” utilizando los formularios para su documentación que, para tal efecto, le proporcione “LA SOCIEDAD”.</p> 

					<p>8.-Este contrato y las constancias de depósito, no tendrán carácter de títulos de crédito ni serán negociables; sin embargo, “EL DEPOSITANTE” podrá ceder y/o afectar sin garantía los derechos que se derivan del presente contrato, excepto a favor de instrucciones, siempre y cuando de aviso a “LA SOCIEDAD” con un mínimo de 5 días hábiles de anticipación.</p>

					<p>9.-La presentación de las constancias, comprobantes de depósitos y registros contables, o copias de los mismos, que “LA SOCIEDAD” expida serán el único medio probatorio de los depósitos bancarios de dinero y/o de títulos en administración.</p>

					<p>10.- Toda comunicación que “EL DEPOSITANTE” dirija a “LA SOCIEDAD” para todos los efectos que haya lugar, en relación con el presente contrato, será enviada al domicilio que se señala en el anverso. “EL DEPOSITANTE” deberá comunicar por escrito a “LA SOCIEDAD” cualquier cambio de domicilio, quedando “LA SOCIEDAD” liberada de toda responsabilidad cuando “EL DEPOSITANTE” por cualquier causa, no la proporcione.</p>

					<p>11.- “LA SOCIEDAD” se reserva el derecho de modificar los montos mínimos a partir de los cuales recibirá estos depósitos, los plazos y las tasas de rendimiento de acuerdo a los parámetros económicos, a las disposiciones internas de la institución y a las disposiciones que dice la ley en la materia.</p>

					<p>12.-Tratándose de depósitos bancarios de dinero mediante documentos de cobro inmediato, estos serán recibidos por “LA SOCIEDAD” “Salvo buen cobro”, los importes se abonarán hasta que los documentos se hayan hecho efectivos, a partir de esa fecha comenzarán a devengar intereses y el contrato entrará en vigor.</p>
			
					<p>13.-Para cualquier conflicto que surgiera con motivo de la interpretación y cumplimiento de este contrato, las partes se someten a la jurisdicción y competencia de los tribunales de la localidad, renunciando al fuero de cualquier domicilio que tengan o llegaren a tener.</p>
				</td>
			</tr>
		</table>
		';

		$html .= '<p style="text-align: center; padding-top: 15px">Celebrado y firmado por duplicado "LA SOCIEDAD" conserva un ejemplar y uno "EL DEPOSITANTE"</p>';

		$html .= '
		<table style="width:100%; font-size:12px;" border="0">	
			<tr>
				<td style="padding: 0 0 120px 0"></td>
				<td align="center" width="25%">LA SOCIEDAD</td>
				<td></td>
				<td align="center" width="25%">ACEPTO (EL DEPOSITANTE)</td>
				<td></td>
			</tr>		
			<tr>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="25%">FRANCISCO URIEL JERÓNIMO HERNÁNDEZ</td>
				<td></td>
				<td style="border-top: 1px solid" align="center" width="25%">' . $record['nombre'] . '</td>
				<td></td>
			</tr>
		</table>';

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
		$documento = $this->dompdf->stream("Inversión.pdf", array("Attachment" => 0));
	}


	public function PrintMov_get()
	{
		if (!$this->ion_auth->in_group('caja')) {
			//				redirect('/','refresh');
		}
		$nomov = $this->uri->segment(4);

		$enca = $this->getEmpresa() . "<br>";
		$enca .= 'SUCURSAL ' . $this->session->userdata('nomsucursal') . "<br>";
		$fecha = date("Y-m-d");
		$idcaja = $this->session->userdata('idcaja');

		if ($this->ion_auth->user()->row()->id  == 1) {
			//			$idcaja = 13;
		}


		$query = "select * from " . $this->session->userdata('esquema') . "get_movimientosdia('" . $fecha . "','" . $fecha . "','" . $idcaja . "','') where orden in ('B','C','D','E','I') and nomov =" . $nomov . " order by orden,cuenta,idacreditado";

		$data = $this->base->querySelect($query, FALSE);
		//		$namePrint ="EPSON TM-T88V Receipt";



		try {

			$datos = $data['result'];
			$noelementos = count($datos);
			$cuenta = 0;
			$nombre = '';
			$height = '240px;';

			if ($noelementos > 1) {
				if ($noelementos == 2) {
					$height = (90 * $noelementos + 100) . 'px;';
				} else {
					$height = (50 * $noelementos + 100) . 'px;';
				}
			}

			$html = "<!DOCTYPE html>";
			$html .= "<html>";
			$html .= "<head>";
			$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
			$html .= ' <style>';
			$html .= '   @page { size: 160pt ' . $height . ' margin: 43px;}';
			$html .= '   body {';
			$html .= '   font-size: 10px;';
			$html .= '    margin: 0px; ';
			$html .= '    padding: 0px';
			$html .= '   }';
			$html .= '   .container {';
			$html .= '    margin: -40px; ';
			$html .= '    padding: -0px; ';
			$html .= '   }';
			$html .= '  </style>';
			$html .= '</head>';
			$html .= '<body>';
			$html .= '<div class="container">';

			$html .= $enca;
			$html .= date("d-m-Y") . "    " . $nomov . "    " . date("h:i");


			$tot = 0;
			foreach ($datos as $key => $value) {
				if ($value['orden'] !== 'C') {
					if ($noelementos === 1) {
						$html .=  "<br><br>";
						$html .= 'Socia   ' . $value['idacreditado'] . '  ' . $value['nomacre'] . "<br>";
						$html .= 'Cuenta  ' . $value['cuenta'] . '  ' . $value['nomcuenta'] . "<br>";
						$html .= 'Moneda  PESO MEXICANO' . "<br>";
						$html .= 'Caja  ' . $this->session->userdata('nocaja') . '  ' . $this->session->userdata('name_user_caja') . "<br>";
						$html .= "<br>";
						$html .=  $value['nombre'] . "<br>";
					} else {
						if ($cuenta === 0) {
							$html .= "<br><br>";
							$html .= 'Caja  ' . $this->session->userdata('nocaja') . '  ' . $this->session->userdata('name_user_caja') . "<br>";
						}
						if ($nombre != $value['nombre']) {
							$html .= "<br><br>";
							$html .= $value['nombre'] . "<br><br>";
						}
						$nombre = $value['nombre'];
					}
				} elseif ($tot === 0 && $value['orden'] === "C") {
					if ($cuenta === 0) {
						$html .= "<br><br>";
						$html .= 'Caja  ' . $this->session->userdata('nocaja') . '  ' . $this->session->userdata('name_user_caja') . "<br>";
						$html .= $value['nombre'] . "<br><br>";
					}
					$cuenta = $cuenta + 1;
				}

				//CREDITO
				$body = "";
				//				$tot  =0;
				if ($value['orden'] === 'C') {
					$body = 'Socia ' . $value['idacreditado'] . '  ' . $value['nomacre'] . "<br>";
					$body .= 'Importe ' . number_format($value['importe'], 2, '.', ',') . "<br>";
					$tot = floatval($tot)  + floatval($value['importe']);
				} else {
					if ($noelementos === 1) {
						$body = "Importe  " . number_format($value['importe'], 2, '.', ',') . "<br>";
						$body .= "---------------------<br>";
						$body .= "Total    " . number_format($value['importe'], 2, '.', ',') . "<br><br>";
					} else {
						$body = 'Socia ' . $value['idacreditado'] . '  ' . $value['nomacre'] . "<br>";
						$body .= 'Importe ' . number_format($value['importe'], 2, '.', ',') . "<br><br>";
						$tot = floatval($tot)  + floatval($value['importe']);
					}
				}
				$html .=  $body;
				if ($value['orden'] === 'C') {
				} else {
					if ($noelementos === 1) {
						$html .= "<br><br>";
						$html .= "______________________" . "<br>";
						$html .= "Firma" . "<br>";
					}
				}
			}
			if ($tot != 0) {
				$html .= "---------------------<br>";
				$html .= "Total Pago  " . number_format($tot, 2, '.', ',') . "<br><br>";
			}

			$html .= '</div>';
			$html .= '</body>';
			$html .= '</html>';

			//			print_r($html);
			//			die();



			ob_clean();
			$this->load->library('dompdf_gen');
			$this->dompdf->load_html($html);
			//			$this->dompdf->set_paper('letter', 'portrait');
			$this->dompdf->render();
			$canvas = $this->dompdf->get_canvas();
			//		$font = Font_Metrics::get_font("helvetica", "bold");
			//		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
			$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
		} catch (Exception $e) {

			return  "Error al enviar a la pdf : " . $e->getMessage();
		}
	}





	public function asistenciaFormat_get()
	{
		$idcolmena = $this->uri->segment(4);
		$anio = $this->uri->segment(5);
		$semana = $this->uri->segment(6);
		$data = $this->base->querySelect("select a.grupo_nombre,a.idgrupo, a.nombre, a.idacreditado, a.acreditadoid, a.idanterior, a.orden, a.cargo_colmena, a.cargo_grupo, b.asistencia, b.incidencia from fin.get_acreditado_grupo as a left join col.asistencia as b on b.acreditadoid = a.acreditadoid and b.idgrupo = a.idgrupo and b.semana = " . $semana . " and b.anio = " . $anio . "  where a.idcolmena = " . $idcolmena . "  order by a.grupo_nombre, a.orden", true);

		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idcolmena = " . $idcolmena, true);



		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= '<p align="center">FORMATO DE ASISTENCIA</p>';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;">Colmena: ' . $colmena[0]['numero'] . '  ' . $colmena[0]['nombrecolmena'] . '&nbsp;&nbsp;&nbsp;&nbsp; Promotor:  ' . $colmena[0]['nompromotor'] . '</p>';
		$html .= '<p style="font-size: 15px;">Año: ' . $anio . '   Semana: ' . $semana . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>Socia</th>';
		$html .= '    <th>Nombre</th>';
		$html .= '    <th>Cargo colmena</th>';
		$html .= '    <th>Cargo grupo</th>';
		$html .= '    <th>Asistencia</th>';
		$html .= '    <th>Incidencia</th>';
		$html .= '  </tr>';
		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		foreach ($data as $key => $value) {
			if ($idgrupo != $value['idgrupo']) {
				$html .= '  <tr style="text-align:left">';
				$html .= '  <td colspan="6">' . $value['grupo_nombre'] . '</td>';
				$html .= '  </tr>';
			}
			$html .= '  <tr>';
			$html .= '  <td style="text-align:center">' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['cargo_colmena'] . '</td>';
			$html .= '  <td>' . $value['cargo_grupo'] . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td></td>';
			$html .= '  </tr>';
			$idgrupo = $value['idgrupo'];
		}

		$html .= '</table';


		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';

		/*   			print_r($html);
			die();
 */



		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		//			$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		//		$font = Font_Metrics::get_font("helvetica", "bold");
		//		$canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0,0,0));			
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	/******
	 * 
	 * 
	 * 
	 * 
	 * 
	 */
	public function asisglobal_get()
	{
		$idpromotor = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);

		$anio = substr($fecha, 4);
		$mes = intval(substr($fecha, 2, 2));
		$esquema = strtoupper(substr($this->esquema, 0, 3));
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$data = $this->base->querySelect("select * FROM col.get_asistencia_global(" . $idpromotor . "," . $anio . "," . $mes . ",'" . $esquema . "') ORDER BY numero ASC", true);

		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idpromotor = " . $idpromotor, true);

		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';
		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= '<!--<p align="center">FORMATO DE ASISTENCIA</p>-->';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px; text-align: center;"> INFORME DEL MES DE ' . $meses[$mes - 1] . ' DE ' . $anio . '<br>PRODUCTIVIDAD</p>';
		$html .= '<p style="font-size: 15px;">PROMOTORA:  ' . $colmena[0]['nompromotor'] . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th rowspan="2" width="50px">No.</th>';
		$html .= '    <th rowspan="2" width="150px">Nombre</th>';
		$html .= '    <th colspan="8">Mujeres</th>';
		$html .= '    <th colspan="4">Grupos</th>';
		$html .= '    <th colspan="4">Colmenas</th>';
		$html .= '  </tr>';
		$html .= '  <tr>';
		$html .= '    <!--<th>No</th>-->';
		$html .= '    <!--<th>Nombre</th>-->';
		$html .= '    <th>Del Mes Pasado</th>';
		$html .= '    <th>Integrados en el mes</th>';
		$html .= '    <th>Nuevas</th>';
		$html .= '    <th>Reingresos</th>';
		$html .= '    <th>Inactivas</th>';
		$html .= '    <th>Renuncias</th>';
		$html .= '    <th>Total en listas</th>';
		$html .= '    <th>Pendientes por capacitar</th>';
		$html .= '    <th>Del Mes Pasado</th>';
		$html .= '    <th>Integrados en el mes</th>';
		$html .= '    <th>Cerrados</th>';
		$html .= '    <th>Total</th>';
		$html .= '    <th>Actuales</th>';
		$html .= '    <th>Nuevas</th>';
		$html .= '    <th>Cerradas</th>';
		$html .= '    <th>Total</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		$t1 = 0;
		$t2 = 0;
		$t2 = 0;
		$t3 = 0;
		$t4 = 0;
		$t5 = 0;
		$t6 = 0;
		$t7 = 0;
		$t8 = 0;
		$t9 = 0;
		$t10 = 0;
		$t11 = 0;
		$t12 = 0;
		$t13 = 0;
		$t14 = 0;
		$t15 = 0;
		$t16 = 0;
		foreach ($data as $key => $value) {
			$html .= '  <tr>';
			$html .= '  <td style="text-align:center; width:10%">' . $value['numero'] . '</td>';
			$html .= '  <td style="width:35%">' . $value['nombre'] . '</td>';
			$html .= '  <td align="center">' . $value['mespasado'] . '</td>';
			$html .= '  <td align="center">' . $value['integradas'] . '</td>';
			$html .= '  <td align="center">' . $value['nuevas'] . '</td>';
			$html .= '  <td align="center">' . $value['reingresos'] . '</td>';
			$html .= '  <td align="center">' . $value['inactivas'] . '</td>';
			$html .= '  <td align="center">' . $value['renuncias'] . '</td>';
			$html .= '  <td align="center">' . $value['total'] . '</td>';
			$html .= '  <td align="center">' . $value['porcapacitar'] . '</td>';
			$html .= '  <td align="center">' . $value['grupomespasado'] . '</td>';
			$html .= '  <td align="center">' . $value['integrados'] . '</td>';
			$html .= '  <td align="center">' . $value['cerrados'] . '</td>';
			$html .= '  <td align="center">' . $value['totalgrupo'] . '</td>';
			$html .= '  <td align="center">' . $value['col_actual'] . '</td>';
			$html .= '  <td align="center">' . $value['col_nuevas'] . '</td>';
			$html .= '  <td align="center">' . $value['col_cerradas'] . '</td>';
			$html .= '  <td align="center">' . $value['col_total'] . '</td>';
			$html .= '  </tr>';


			$t1 = $t1 + $value['mespasado'];
			$t2 = $t2 + $value['integradas'];
			$t3 = $t3 + $value['nuevas'];
			$t4 = $t4 + $value['reingresos'];
			$t5 = $t5 + $value['inactivas'];
			$t6 = $t6 + $value['renuncias'];
			$t7 = $t7 + $value['total'];
			$t8 = $t8 + $value['porcapacitar'];
			$t9 = $t9 + $value['grupomespasado'];
			$t10 = $t10 + $value['integrados'];
			$t11 = $t11 + $value['cerrados'];
			$t12 = $t12 + $value['totalgrupo'];
			$t13 = $t13 + $value['col_actual'];
			$t14 = $t14 + $value['col_nuevas'];
			$t15 = $t15 + $value['col_cerradas'];
			$t16 = $t16 + $value['col_total'];
		}

		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td style="width:35%; text-align: center;">TOTALES</td>';
		$html .= '  <td align="center">' . $t1 . '</td>';
		$html .= '  <td align="center">' . $t2 . '</td>';
		$html .= '  <td align="center">' . $t3 . '</td>';
		$html .= '  <td align="center">' . $t4 . '</td>';
		$html .= '  <td align="center">' . $t5 . '</td>';
		$html .= '  <td align="center">' . $t6 . '</td>';
		$html .= '  <td align="center">' . $t7 . '</td>';
		$html .= '  <td align="center">' . $t8 . '</td>';
		$html .= '  <td align="center">' . $t9 . '</td>';
		$html .= '  <td align="center">' . $t10 . '</td>';
		$html .= '  <td align="center">' . $t11 . '</td>';
		$html .= '  <td align="center">' . $t12 . '</td>';
		$html .= '  <td align="center">' . $t13 . '</td>';
		$html .= '  <td align="center">' . $t14 . '</td>';
		$html .= '  <td align="center">' . $t15 . '</td>';
		$html .= '  <td align="center">' . $t16 . '</td>';
		$html .= '  </tr>';


		$html .= '</table';


		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->set_paper('A4', 'landscape');
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}





	public function asisrenuncia_get()
	{
		$idpromotor = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);

		$anio = substr($fecha, 4);
		$mes = intval(substr($fecha, 2, 2));
		$esquema = strtoupper(substr($this->esquema, 0, 3));
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$data = $this->base->querySelect("select * FROM col.get_asistencia_renuncias(" . $idpromotor . "," . $anio . "," . $mes . ",'" . $esquema . "')", true);
		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idpromotor = " . $idpromotor, true);

		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;"> INFORME MENSUAL DE RENUNCIAS </p>';
		$html .= '<p style="font-size: 15px;"> INFORME DEL MES DE ' . $meses[$mes - 1] . ' DE ' . $anio . '</p>';
		$html .= '<p style="font-size: 15px;">PROMOTORA:  ' . $colmena[0]['nompromotor'] . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>No. de Colmena</th>';
		$html .= '    <th>Nombre de Colmena</th>';
		$html .= '    <th>No. de Grupo</th>';
		$html .= '    <th>Nombre Completo de la mujer</th>';
		$html .= '    <th>Nivel del crédito</th>';
		$html .= '    <th>Fecha de Salida</th>';
		$html .= '    <th>Motivo de la Renuncia</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="text-align:center; width:10%">' . $value['numero'] . '</td>';
				$html .= '  <td style="width:15%">' . $value['nombrecolmena'] . '</td>';
				$html .= '  <td>' . $value['grupo'] . '</td>';
				$html .= '  <td>' . $value['nomacreditada'] . '</td>';
				$html .= '  <td>' . $value['nivel'] . '</td>';
				$html .= '  <td>' . $value['fechabaja'] . '</td>';
				$html .= '  <td>' . $value['motivo'] . '</td>';
				$html .= '  </tr>';
			}
		}

		$html .= '</table';


		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';


		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}







	public function asisicambios_get()
	{
		$idpromotor = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);

		$anio = substr($fecha, 4);
		$mes = intval(substr($fecha, 2, 2));
		$esquema = strtoupper(substr($this->esquema, 0, 3));
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$data = $this->base->querySelect("select * FROM col.get_asistencia_nueva_rein(" . $idpromotor . "," . $anio . "," . $mes . ",'" . $esquema . "')", true);
		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idpromotor = " . $idpromotor, true);
		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;"> INFORME MENSUAL DE MUJERES INDIVIDUALES INCORPORADAS Y CAMBIOS </p>';
		$html .= '<p style="font-size: 15px;"> INFORME DEL MES DE ' . $meses[$mes - 1] . ' DE ' . $anio . '</p>';
		$html .= '<p style="font-size: 15px;">PROMOTORA:  ' . $colmena[0]['nompromotor'] . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>No. de Colmena</th>';
		$html .= '    <th>Nombre de Colmena</th>';
		$html .= '    <th>No. de Grupo</th>';
		$html .= '    <th>Nombre Completo de la mujer</th>';
		$html .= '    <th>Fecha de ingreso</th>';
		$html .= '    <th align="center">Nueva</th>';
		$html .= '    <th align="center">Reingreso</th>';
		$html .= '    <th>Motivo de la Renuncia</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="text-align:center; width:10%">' . $value['numero'] . '</td>';
				$html .= '  <td style="width:15%">' . $value['nombrecolmena'] . '</td>';
				$html .= '  <td>' . $value['grupo'] . '</td>';
				$html .= '  <td>' . $value['nomacreditada'] . '</td>';
				$html .= '  <td>' . $value['fechaingreso'] . '</td>';
				$html .= '  <td align="center">' . $value['nueva'] . '</td>';
				$html .= '  <td align="center">' . $value['reingreso'] . '</td>';
				$html .= '  <td>' . $value['observa'] . '</td>';
				$html .= '  </tr>';
			}
		}

		$html .= '</table';


		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';


		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}


	public function asisinactiva_get()
	{
		$idpromotor = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);
		$anio = substr($fecha, 4);
		$mes = intval(substr($fecha, 2, 2));
		$esquema = strtoupper(substr($this->esquema, 0, 3));
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$data = $this->base->querySelect("select * FROM col.get_asistencia_inactivas(" . $idpromotor . "," . $anio . "," . $mes . ",'" . $esquema . "')", true);
		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idpromotor = " . $idpromotor, true);
		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;"> INFORME MENSUAL DE ATENCION DE INACTIVAS </p>';
		$html .= '<p style="font-size: 15px;"> INFORME DEL MES DE ' . $meses[$mes - 1] . ' DE ' . $anio . '</p>';
		$html .= '<p style="font-size: 15px;">PROMOTORA:  ' . $colmena[0]['nompromotor'] . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>No. de Colmena</th>';
		$html .= '    <th>Nombre de Colmena</th>';
		$html .= '    <th>Nombre Completo de la mujer</th>';
		$html .= '    <th>No. de Grupo</th>';
		$html .= '    <th>Saldo de Capital</th>';
		$html .= '    <th>Saldo de Garantia</th>';
		$html .= '    <th>Ult. Fecha de pago</th>';
		$html .= '    <th>Tipo de Mora (Interna en colmena o al programa)</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="text-align:center; width:10%">' . $value['numero'] . '</td>';
				$html .= '  <td style="width:15%">' . $value['nombrecolmena'] . '</td>';
				$html .= '  <td>' . $value['nomacreditada'] . '</td>';
				$html .= '  <td>' . $value['grupo'] . '</td>';
				$html .= '  <td  style="text-align:right">' . $value['saldocapital'] . '</td>';
				$html .= '  <td style="text-align:right">' . $value['saldogtia'] . '</td>';
				$html .= '  <td>' . $value['ultfecpago'] . '</td>';
				$html .= '  <td>' . $value['tipomora'] . '</td>';
				$html .= '  </tr>';
			}
		}
		$html .= '</table';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}




	public function asisincidencia_get()
	{
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$idpromotor = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);
		$anio = substr($fecha, 4);
		$mes = intval(substr($fecha, 2, 2));
		$esquema = strtoupper(substr($this->esquema, 0, 3));
		$meses = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
		$data = $this->base->querySelect("select * FROM col.asistencia_incidencia(" . $idpromotor . "," . $anio . "," . $mes . ",'" . $esquema . "')", true);

		$colmena = $this->base->querySelect("select * from col.v_promotor_grupo where idpromotor = " . $idpromotor, true);

		$t1 = 0;
		$t2 = 0;
		$t3 = 0;
		$t4 = 0;
		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= $this->getLogo(60);
		//			$html .= '<div style ="top: 15px;position: absolute; float:right;"><img src="'.base_url("dist/img/logofin.png").'" height="60px" alt=""></div>';		
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;"> INFORME MENSUAL DE INCIDENCIAS </p>';
		$html .= '<p style="font-size: 15px;"> INFORME DEL MES DE ' . $meses[$mes - 1] . ' DE ' . $anio . '</p>';
		$html .= '<p style="font-size: 15px;">PROMOTORA:  ' . $colmena[0]['nompromotor'] . '</p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>No. de Colmena</th>';
		$html .= '    <th>Nombre de Colmena</th>';
		$html .= '    <th>Fichas fuera de tiempo</th>';
		$html .= '    <th>Faltas de pago FP</th>';
		$html .= '    <th>Faltas de ficha FF</th>';
		$html .= '    <th>Faltas en el mes</th>';
		$html .= '    <th>Observaciones</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';
		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="text-align:center; width:10%">' . $value['numero'] . '</td>';
				$html .= '  <td style="width:15%">' . $value['nombre'] . '</td>';
				$html .= '  <td style="text-align:right">' . $value['inci_ft'] . '</td>';
				$html .= '  <td style="text-align:right">' . $value['inci_fp'] . '</td>';
				$html .= '  <td style="text-align:right">' . $value['inci_ff'] . '</td>';
				$html .= '  <td style="text-align:right">' . $value['inci_f'] . '</td>';
				$html .= '  <th></th>';
				$html .= '  </tr>';

				$t1 = $t1 + $value['inci_ft'];
				$t2 = $t2 + $value['inci_fp'];
				$t3 = $t3 + $value['inci_ff'];
				$t4 = $t4 + $value['inci_f'];
			}
		}
		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td style="width:15%">TOTALES</td>';
		$html .= '  <td style="text-align:right">' . $t1 . '</td>';
		$html .= '  <td style="text-align:right">' . $t2 . '</td>';
		$html .= '  <td style="text-align:right">' . $t3 . '</td>';
		$html .= '  <td style="text-align:right">' . $t4 . '</td>';
		$html .= '  <th></th>';
		$html .= '  </tr>';


		$html .= '</table';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';
		ob_clean();
		$this->load->library('dompdf_gen');
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$canvas = $this->dompdf->get_canvas();
		$documento = $this->dompdf->stream("file.pdf", array("Attachment" => 0));
	}



	public function tableEdoCuenta($title, $data, $idproducto)
	{
		$html = '';
		$html .= '<table style="width:80%" align="center">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';

		//$amor = $this->base->querySelect("SELECT numero, fecha, fecha_pago, dias, pag_capital, (pag_interes_vig+pag_interes_ven) as interes, pag_iva, total_pagado, (capital-pag_capital) as saldo_capital, 0 as int_mora 
		//$title = array("Pago","Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora", );

		$capital_req = 0;
		$capital_pag = 0;
		$interes_req = 0;
		$interes_mora = 0;
		$capital_saldo = 0;
		$dias_saldo = 0;
		$capital_inicial = 0;
		$iva = 0;
		$ultreg = count($data);
		$cuenta = 0;

		$esp_capital = 0;
		$esp_int = 0;
		$esp_total_t = 0;
		foreach ($data as $key => $value) {
			$fecha = date_create($value['fecha']);
			$fecha = date_format($fecha, 'd/m/Y');

			$fechaPago = date_create($value['fecha_pago']);
			$fechaPago = date_format($fechaPago, 'd/m/Y');
			if ($value['numero'] <> -99) {
				$html .= '  <tr>';
				if ($value['numero'] <= 0) {
					$html .= '  <td>  </td>';
				} else {
					$html .= '  <td align="right">' . $value['numero'] . '</td>';
				}
				$html .= '  <td>' . $fecha . '</td>';
				if (substr($this->esquema, 0, 3) == 'ama' && $idproducto == 10) {
					$iva = $value['iva'];
				} else {
					$iva = ($value['c_interes_acumula'] + $value['c_mora']) * 0.16;
				}
				if ($value['fecha_pago'] === null) {
					$html .= '  <td> </td>';
					$html .= '  <td align="right">' . $value['dias'] . '</td>';
					$html .= '  <td> </td>';
					$html .= '  <td align="right">' . number_format($value['c_interes_acumula'], 2, '.', ',') . '</td>';
					$html .= '  <td align="right">' . number_format($iva, 2, '.', ',') . '</td>';
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $fechaPago . '</td>';
					$html .= '  <td align="right">' . $value['dias'] . '</td>';
					$html .= '  <td align="right">' . number_format($value['pag_capital'], 2, '.', ',') . '</td>';
					$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
					$html .= '  <td align="right">' . number_format($iva, 2, '.', ',') . '</td>';
					$html .= '  <td align="right">' . number_format($value['total_pagado'], 2, '.', ',') . '</td>';
				}
				$dias_saldo = $value['dias'];
				$capital_saldo = $value['saldo_capital'];
				$html .= '  <td align="right">' . number_format($value['saldo_capital'], 2, '.', ',') . '</td>';



				if (array_key_exists('interesnormal', $value)) {
					$html .= '  <td align="right">' . number_format($value['interesnormal'], 2, '.', ',') . '</td>';
				}


				$html .= '  <td align="right">' . number_format($value['c_mora'], 2, '.', ',') . '</td>';
				$html .= '  </tr>';
				$capital_req = $value['capital_requerido'];
				$capital_pag = $value['capital_pagado'];
				$interes_req = $value['c_interes_acumula']; // + $value['c_mora'];
				$interes_mora = $value['c_mora'];

				$cuenta++;
				if ($ultreg == $cuenta) {
					$esp_capital = $value['importe_acum'] - $value['capital_acum'];
					if ($esp_capital < 0) {
						$esp_capital = 0;
					}
					$esp_int =  $value['interesacumulado'] - ($value['interes_n_acum'] + $value['condona_n_acum']);
					$interes_mora = $value['interesmora_acum'] - ($value['interes_m_acum'] + $value['condona_m_acum']);
					$esp_iva = 0;

					$esp_total = $esp_capital + $esp_int + $interes_mora + $esp_iva;
					$esp_total_t = $value['saldo'] + $esp_int + $interes_mora + $esp_iva;
				}
			} else {
				$capital_inicial = $value['saldo_capital'];
			}
		}
		$html .= '</table>';

		if ($capital_pag <= $capital_inicial) {
			$title2 = array("Capital requerido", "Interes", "Int. moratorio", "IVA", "Pago total amortización");
			$html2 = '<br><br>';
			$html2 .= '<table style="width:80%" align="center">';
			$html2 .= '  <tr>';
			foreach ($title2 as $key => $value) {
				$html2 .= '    <th>' . $value . '</th>';
			}
			$html2 .= '  </tr>';

			if ($this->session->userdata('esquema') != 'ama.'  && $idproducto != 10) {
				$esp_capital = 0;
				$esp_int = 0;
				if ($capital_req > $capital_pag) {
					$esp_capital = $capital_req - $capital_pag;
					$esp_int = $interes_req;
				}
				$esp_iva = ($esp_int + $interes_mora) * 0.16;
			}
			$esp_total = $esp_capital + $esp_int + $interes_mora + $esp_iva;

			$html2 .= '  <tr>';
			$html2 .= '  <td align="right">' . number_format($esp_capital, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($esp_int, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($interes_mora, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($esp_iva, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($esp_total, 2, '.', ',') . ' </td>';
			$html2 .= '  </tr>';
			$html2 .= '</table>';


			$title3 = array("Capital a liquidar", "Interes", "Int. moratorio", "IVA", "Pago total para liquidar");
			$html3 = '<br><br>';
			$html3 .= '<table style="width:80%" align="center">';
			$html3 .= '  <tr>';
			foreach ($title3 as $key => $value) {
				$html3 .= '    <th>' . $value . '</th>';
			}
			$html3 .= '  </tr>';


			if ($this->session->userdata('esquema') != 'ama.'  && $idproducto != 10) {
				$esp_capital = 0;
				$esp_int = 0;
				$esp_iva = 0;
				if ($capital_req > $capital_pag) {
					$esp_capital = $capital_req - $capital_pag;
					$esp_int = $interes_req;
				}
				$esp_iva = ($esp_int + $interes_mora) *  0.16;
			}
			$esp_total = $capital_saldo + $esp_int + $interes_mora + $esp_iva;
			$html3 .= '  <tr>';
			$html3 .= '  <td align="right">' . number_format($capital_saldo, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($esp_int, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($interes_mora, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($esp_iva, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($esp_total, 2, '.', ',') . ' </td>';
			$html3 .= '  </tr>';
			$html3 .= '</table>';
		} else {

			$title2 = array("Estatus");

			$html2 = '<br><br>';
			$html2 .= '<table style="width:80%" align="center">';
			$html2 .= '  <tr>';
			$html2 .= '  <td align="CENTER"> CREDITO PAGADO </td>';
			$html2 .= '  </tr>';
			$html2 .= '</table>';

			$html3 = '';
		}


		$html .= $html2;
		$html .= $html3;
		return $html;
	}





	public function cheques_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
		} else {
			redirect('/', 'refresh');
		}

		$fecini = $this->uri->segment(4);
		$fecfin = $this->uri->segment(5);

		$fecha = new DateTime();
		$fecini1 =  $fecha->format('d/m/Y');
		$fecfin2 =  $fecha->format('d/m/Y');

		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');

				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = date("Y-m-d");
			}
		} else {
			$fecini = date("Y-m-d");
		}

		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = date("Y-m-d");
			}
		} else {
			$fecfin = date("Y-m-d");
		}

		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$data = $this->findCheques($fecini, $fecfin);
		$movdet = $data['movdet'];
		//		$miFecha = date_create($mov['fecha']);
		$fechoy = date_format($fecha, 'd/m/Y');
		$tiempo = date_format($fecha, 'H:i');

		$titulobov = "RELACION DE CHEQUES DEL " . $fecini1 . " AL " . $fecfin2;
		$html = '
			<html>
			<body>
		';
		$header = '<font size="18px">';
		$header .= '<p align="center">' . $empresal . '<br>
			SUCURSAL ' . strtoupper($this->session->userdata('nomsucursal')) . '<br>
			' . $titulobov . '
		</p><hr>';

		$header .= '
			</font><font size="8px">' . $fechoy . '  <b align="right" style="float:right">' . $tiempo . '</b></font>';

		$html .= $header;

		$html .= '<table style="font-size:10px; width:100%">';
		$html .= '  <tr>';
		$html .= '    <th align="left">Fecha</th>';
		$html .= '    <th>No.Socia</th>';
		$html .= '    <th>Nombre</th>';
		$html .= '    <th>No. Cheque</th>';
		$html .= '    <th>Monto</th>';
		$html .= '    <th>Colmena</th>';
		$html .= '  </tr>';
		$primero = 1;
		foreach ($movdet as $key => $value) {
			$miFecha = date_format(date_create($value['fecha_entrega']), 'd/m/Y');
			$html .= '  <tr>';
			$html .= '  <td>' . $miFecha . '</td>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['acreditado'] . '</td>';
			$html .= '  <td>' . $value['cheque_ref'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['monto'], 2, '.', ',') . '</td>';
			$html .= '  <td>' . $value['colmena_numero'] . ' ' . $value['colmena_nombre'] . '</td>';

			$html .= '  </tr>';
		}
		$html .= '</table>';
		$html .= '
			</div>
			</body>
			</html>
			';
		$this->printReport($html);
	}



	public function polcheque_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))) {
		} else {
			redirect('/', 'refresh');
		}

		$idcredito = $this->uri->segment(4);
		$fecha = new DateTime();
		$empresal = $this->getEmpresa();
		$fecom =  date("Y-m-d");
		$data = $this->findPolCheque($idcredito);
		$movdet = $data['movdet'];
		$garantias = $data['garan'][0]{
			'garan'};
		$fechoy = date_format($fecha, 'd/m/Y');
		$tiempo = date_format($fecha, 'H:i');

		$html = '
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta http-equiv="Content-type" content="text/html; charset=utf-8" Content-Type:text/html; charset=utf-8>
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>Poliza Cheque</title>
				<style type="text/css">
					body {
						font-family: Arial, Helvetica, sans-serif;
						font-size: 12px;
						color: rgb(30, 53, 184);
					}

					table {
						width: 100%;
						
					}
				
				</style>
			</head>
			<body>
		';
		$miFecha = date_format(date_create($movdet[0]['fecha_entrega']), 'd/m/Y');

		$monto_letra = $this->numeroToLetras(number_format($movdet[0]['monto'], 2, '.', ''));

		$html .= '<table style="width:100%; border: 2px solid rgb(30, 53, 184); border-radius: 5px; padding: 10px; color: black">
					<tr>
					   <td style="font-size:14px;"><b>POLIZA CHEQUE</b></td>
					   <td></td>
					</tr>
					<tr>
					  <td></td>
					  <td>FECHA ENTREGA ' . $miFecha . '</td>
					</tr>
					<tr>
						<td>' . $movdet[0]['acreditado'] . '</td>
						<td></td>
					</tr>
					<tr>
						<td>(' . strtoupper($monto_letra) . ')</td>
						<td>$' . number_format($movdet[0]['monto'], 2, '.', ',') . '</td>
					</tr>
					<tr>
						<td>Cheque No. ' . $movdet[0]['cheque_ref'] . '</td>
						<td>Cuenta No. ' . $movdet[0]['cuentabanco'] . ' ' . $movdet[0]['nombanco'] . '</td>
					</tr>
					</table>

			';

		$html .= '<br>
			<table style="width:100%; color: black">
					<tr>
					   <td style="border: 2px solid rgb(30, 53, 184); border-radius: 5px; padding: 10px;">
					   	 <p>COLMENA ' . $movdet[0]['colmena_numero'] . '</p>
						 <p>' . $movdet[0]['colmena_nombre'] . '</p>
					     <p>NIVEL ' . $movdet[0]['nivel'] . '</p>
						 <p>GARANTIA $ ' . number_format($garantias, 2, '.', ',') . '</p>
					   </td>
					   <td style="text-align:center; border: 2px solid rgb(30, 53, 184); border-radius: 5px; padding: 10px;"><p>FIRMA DE CHEQUE RECIBIDO</p><br><br><br><br><br></td>
					</tr>
					</table>
					';

		$html .= '<br>
					<table style="width:100%; border: 2px solid rgb(30, 53, 184); border-radius: 5px; padding: 10px; ">
					<tr style="font-size:10px; border: 1px solid  ">
					   <td>CUENTA</td>
					   <td>SUBCUENTA</td>
					   <td>NOMBRE</td>
					   <td>PARCIAL</td>
					   <td>DEBE</td>
					   <td>HABER</td>
					</tr>
					<tr>
					<td>
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

					</td>
					</tr>
				</table>
			';

		$html .= '<br>
				<table style="border: 2px solid rgb(30, 53, 184); border-radius: 5px; padding: 10px;">
					<tr style="font-size:10px; border: 1px solid black; ">
					   <td>HECHO POR</td>
					   <td>REVISADO POR</td>
					   <td>AUTORIZADO</td>
					   <td>DIARIO</td>
					   <td>AUXILIARES</td>
					   <td>POLIZA NO.</td>
					</tr>
					<tr>
					<td>
						<br><br><br>
					</td>
					</tr>
				</table>
				
			';


		$html .= '
			</body>
			</html>
			';
		$this->printReport($html);
	}


	public function vine_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'filtros'))) {
		} else {
			redirect('/', 'refresh');
		}

		$idAnio = $this->uri->segment(4);

		$data = $this->base->querySelect("select a.idpersona, a.acreditadoid, a.nombre, a.idsucursal, a.col_nombre, c.nompromotor, COALESCE (b.vine, '0') as anio from " . $this->session->userdata('esquema') . "get_acreditado_grupo as a inner join public.personas as b on b.idpersona  = a.idpersona join col.v_promotor_grupo as c on c.idgrupo = a.idgrupo where  COALESCE(b.vine, '0') = '" . $idAnio . "' order by a.idsucursal", true);

		$empresa = $this->getEmpresa();
		$empresal = $this->getEmpresaLargo();
		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
			<html>
			<body>
		';



		$html = "<!DOCTYPE html>";
		$html .= "<html>";
		$html .= "<head>";
		$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';

		$html .= '</head>';
		$html .= '<body>';
		$html = '<font size="15px">';
		$html .= '<p align="center" style="margin-left:140px;">' . $empresal . '</p>';
		$html .= $this->getLogo(60);
		$html .= '<br><br>
				</font>';
		$html .= '<div class="container">';
		$html .= '<p style="font-size: 15px;">LISTADO DE VENCIMIENTO INE </p>';

		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>Persona</th>';
		$html .= '    <th>No. Socia</th>';
		$html .= '    <th>Nombre</th>';
		$html .= '    <th>Sucursal</th>';
		$html .= '    <th>Colmena</th>';
		$html .= '    <th>Promotor(a)</th>';
		$html .= '    <th>Año</th>';
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';

		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="text-align:center; width:8%">' . $value['idpersona'] . '</td>';
				$html .= '  <td style="width:10%">' . $value['acreditadoid'] . '</td>';
				$html .= '  <td style="width:30%">' . $value['nombre'] . '</td>';
				$html .= '  <td style="width:10%">' . $value['idsucursal'] . '</td>';
				$html .= '  <td style="width:25%">' . $value['col_nombre'] . '</td>';
				$html .= '  <td style="width:25%">' . $value['nompromotor'] . '</td>';
				$html .= '  <td style="width:8%">' . $value['anio'] . '</td>';
				$html .= '  </tr>';
			}
		}
		$html .= '</table';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';

		$this->printReport($html);
	}

	/**
	 * Genera un informe en formato PDF con las aportaciones sociales de todas las sucursales.
	 * 
	 * @param string $fecini La fecha de inicio en formato 'Y-m-d'.
	 * @param string $fecfin La fecha de fin en formato 'Y-m-d'.
	 * @param string $tipo El tipo de movimiento ('D' para ingreso, 'R' para retiro).
	 * @return void
	 */
	public function repaportasoc_get($fecini = null, $fecfin = null, $tipo = null)
	{

		// Verifica si el usuario pertenece a uno de los grupos autorizados
		if (!$this->ion_auth->in_group(array('gerencial', 'filtros', 'contabilidad'))) {
			redirect('/', 'refresh');
		}

		// Convierte las fechas a formato 'Y-m-d'
		$fecini = $this->convertDate($fecini);
		$fecfin = $this->convertDate($fecfin);

		// Obtener el nombre de la empresa
		$nombre_empresa = getEmpresa($this->esquema);

		// Realiza la consulta en la base de datos
		$query = "
        SELECT a.idacreditado, a1.acreditado, b.fecha, 
            CASE WHEN b.movimiento = 'D' THEN b.importe ELSE 0 END AS ingreso, 
            CASE WHEN b.movimiento = 'R' THEN b.importe ELSE 0 END AS retiro 
        FROM fin.aporta_soc_p AS a 
        JOIN fin.aporta_social AS b ON b.idacreditado = a.idacreditado 
        JOIN public.get_acreditados AS a1 ON a1.acreditadoid = a.idacreditado 
        WHERE b.idsucursal = '01' 
            AND b.fecha::date >= '$fecini' 
            AND b.fecha::date <= '$fecfin' 
            AND b.movimiento = '$tipo' 
        ORDER BY b.fecha";


		$data = $this->base->querySelect($query, true);


	// Genera el contenido HTML del reporte
	// Determinar el título según el tipo
	$titulo = $tipo == 'D' ? 'INGRESOS' : 'RETIROS';

	// Formatear las fechas de inicio y fin
	$fecha_inicio = date('d/m/Y', strtotime($fecini));
	$fecha_fin = date('d/m/Y', strtotime($fecfin));

	// Crear el título del reporte
	$titulo_reporte = "$nombre_empresa <br>REPORTE DE APORTACIÓN SOCIAL $titulo DEL $fecha_inicio AL $fecha_fin";

	// Generar el HTML del título del reporte
	$html = "<h3 align='center' style='font-size: 20px;'>$titulo_reporte</h3>";

		$html .= "
		<style>
			table {
				font-size: 14px; /* Aumentar tamaño de la fuente */
				width: 100%;
				border-collapse: collapse;
				font-family: Arial, sans-serif;
				margin: 20px 0;
			}
			th, td {
				padding: 6px; /* Aumentar el relleno para mayor legibilidad */
			}
			th {
				text-align: center;
				height: 20px;
			}
			td {
				text-align: center;
			}
			tr:hover {
				background-color: #f1f1f1;
			}
			.text-left {
				text-align: left;
				padding-left: 10px; 
				padding-right: 10px; 
			}
		</style>";
		
		if (!empty($data)) {
			// Construye la tabla del reporte
			$html .= '<table style="border-collapse: collapse; font-size:14px; width:100%" border="1">';
			$html .= '<tr><th>No. Socia</th><th>Nombre</th><th>Fecha</th><th>' . ($tipo == "D" ? 'Ingreso' : 'Retiro') . '</th></tr>';
			$total = 0;
		
			foreach ($data as $value) {
				$html .= '<tr>';
				$html .= '<td style="width: 10%;">' . $value['idacreditado'] . '</td>';
				$html .= '<td style="width: 40%;">' . $value['acreditado'] . '</td>';
				$html .= '<td style="width: 30%;">' . date('d/m/Y H:i:s', strtotime($value['fecha'])) . '</td>';
				$html .= '<td align="right" style="width: 20%;">' . number_format($tipo == "D" ? $value['ingreso'] : $value['retiro'], 2, '.', ',') . '</td>';
				$html .= '</tr>';
				$total += $tipo == "D" ? $value['ingreso'] : $value['retiro'];
			}
		
			$html .= '<tr>';
			$html .= '<td colspan="3" style="text-align:right">TOTAL</td>';
			$html .= '<td align="right">' . number_format($total, 2, '.', ',') . '</td>';
			$html .= '</tr>';
			$html .= '</table>';
		
			// Imprime el HTML generado para depuración
			echo " <pre>$html</pre>";
		
			// Genera el PDF del reporte
			generarPDF($html, 'Reporte de Aportación Social.pdf');
		} else {
			echo "No se encontraron datos para el período seleccionado.";
		}
	}

	/**
	 * Convierte una fecha del formato 'ddmmyyyy' al formato 'Y-m-d'.
	 *
	 * @param string $fecha Fecha en formato 'ddmmyyyy'.
	 * @return string Fecha en formato 'Y-m-d'.
	 */
	private function convertDate($fecha)
	{
		if (empty($fecha) || $fecha == '0') {
			return date("Y-m-d");
		}

		$fechaObj = DateTime::createFromFormat('dmY', $fecha);
		if ($fechaObj === false) {
			return date("Y-m-d");
		}

		return $fechaObj->format('Y-m-d');
	}

	public function repseguros_get()
	{
		if ($this->ion_auth->in_group(array('gerencial', 'contabilidad'))  &&  $this->session->userdata('esquema') == 'imp.') {
		} else {
			redirect('/', 'refresh');
		}
		$fecini = $this->uri->segment(4);
		$fecfin = $this->uri->segment(5);
		$tipo = $this->uri->segment(6);

		if ($fecini != '') {
			if ($fecini != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini, 4, 4), substr($fecini, 2, 2), substr($fecini, 0, 2));
				$fecini =  $fecha->format('Y-m-d');

				$fecini1 = $fecha->format('d/m/Y');
			} else {
				$fecini = date("Y-m-d");
			}
		} else {
			$fecini = date("Y-m-d");
		}

		if ($fecfin != '') {
			if ($fecfin != '0') {
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin, 4, 4), substr($fecfin, 2, 2), substr($fecfin, 0, 2));
				$fecfin =  $fecha->format('Y-m-d');
				$fecfin2 = $fecha->format('d/m/Y');
			} else {
				$fecfin = date("Y-m-d");
			}
		} else {
			$fecfin = date("Y-m-d");
		}

		if ($tipo == 'S') {
			$data = $this->base->querySelect("select esquema, idcredito, idacreditado, idpagare, fecha_entrega::date, fecha_liquida::date, nomacre, fecha, movimiento, importe from imp.v_seguros order by fecha ", true);
		} else if ($tipo == 'V') {
			$data = $this->base->querySelect("select idacreditado, idcredito, nomacre, fecha_entrega::date, fecha_liquida::date, esquema, importe from imp.v_seguros where fecha_liquida is null group by idacreditado, idcredito, nomacre, fecha_entrega, fecha_liquida, esquema, importe order by esquema, fecha_entrega", true);
		}


		$fechoy = new DateTime();
		$fechoy =  $fechoy->format('Y-m-d');
		$html = '
				<html>
				<body>
			';

		$titulo = $tipo == 'S' ? 'SALDOS DE SEGUROS' : 'SEGUROS VIGENTES';
		$header = $this->headerReport($titulo . ' DEL ' . $fecini1 . ' AL ' . $fecfin2);
		$html .= $header;
		$html .= '<table style="border-collapse: collapse; font-size:10px; width:100%"  border="1">';
		$html .= '  <tr>';
		$html .= '    <th>Esquema</th>';
		$html .= '    <th>Credito</th>';
		$html .= '    <th>Fec. entrega</th>';
		$html .= '    <th>Fec. liquida</th>';
		$html .= '    <th>Nombre</th>';
		if ($tipo == "S") {
			$html .= '    <th>Fecha</th>';
			$html .= '    <th>Ingreso</th>';
			$html .= '    <th>Egreso</th>';
			$html .= '    <th>Saldo</th>';
		} else {
			$html .= '    <th>Ingreso</th>';
		}
		$html .= '  </tr>';

		$cuenta = "";
		$valant = "";
		$idgrupo = '';

		$saldo = 0;
		if ($data != []) {
			foreach ($data as $key => $value) {
				$html .= '  <tr>';
				$html .= '  <td style="width:10%">' . $value['esquema'] . '</td>';
				$html .= '  <td style="width:10%">' . $value['idacreditado'] . '</td>';
				$html .= '  <td style="width:20%">' . $value['fecha_entrega'] . '</td>';
				$html .= '  <td style="width:20%">' . $value['fecha_liquida'] . '</td>';
				$html .= '  <td style="width:40%">' . $value['nomacre'] . '</td>';
				if ($tipo == "S") {
					$html .= '  <td style="width:30%">' . $value['fecha'] . '</td>';
					if ($value['movimiento'] == "D") {
						$html .= '  <td align="right" style="width:25%">' . number_format($value['importe'], 2, '.', ',') . '</td>';
						$html .= '  <td align="right" style="width:25%"></td>';
						$saldo = $saldo + $value['importe'];
					} else {
						$html .= '  <td align="right" style="width:25%"></td>';
						$html .= '  <td align="right" style="width:25%">' . number_format($value['importe'], 2, '.', ',') . '</td>';
						$saldo = $saldo - $value['importe'];
					}
					$html .= '  <td align="right" style="width:25%">' . number_format($saldo, 2, '.', ',') . '</td>';
				} else {
					$html .= '  <td align="right" style="width:25%">' . number_format($value['importe'], 2, '.', ',') . '</td>';
				}
				$html .= '  </tr>';
			}

			$html .= '  </tr>';
		}
		$html .= '</table';
		$html .= '</div>';
		$html .= '</body>';
		$html .= '</html>';
		$this->printReport($html);
	}
}
