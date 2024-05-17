<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/modules/api/controllers/CarteraD1.php');
require_once(APPPATH . '/modules/api/controllers/DatabaseQueries.php');
require_once(APPPATH . '/modules/api/controllers/Funciones.php');
require_once(APPPATH . '/modules/api/views/report_template.php');
require_once(APPPATH . '/modules/api/views/credit_report.php');
require_once(APPPATH . '/modules/api/views/ahorro_report.php');
require_once(APPPATH . '/modules/api/views/colmenas_report.php');

class ReportD1 extends CarteraD1
{

	public $base;
	public $esquema;
	public $session;
	public $uri;

	public function __construct()
	{
		parent::__construct();

		// Asigna el valor de $esquema desde la sesión
		$this->esquema = $this->session->userdata('esquema');
	}

	/**
	 * Genera un informe en formato PDF con la solicitud de crédito.
	 * 
	 * @return void
	 */
	public function pdf_solicitud_credito_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtiene datos del crédito
			$creditData = $dbQueries->getCreditData($idcredito);
			$montoLetra = numberToWords(number_format($creditData['monto'], 2, '.', ''));
			$periodo = getPeriodo($creditData['periodo']);
			$usuario = $dbQueries->getUser($creditData['usuario']);

			// Obtiene datos de la solicitante
			$fecha = new DateTime($creditData['fecha']);
			$persona = $dbQueries->getPersonaData($creditData['idacreditado']);
			$edad = calcularEdad($persona['fecha_nac'], $fecha);
			$tipoVivienda = tipoVivienda($persona['tipovivienda']);

			// Obtiene datos de la colmena
			$colmenaData = $dbQueries->getColmenaData($creditData['idgrupo']);
			$aval1 = $dbQueries->getAvalGrupoData($creditData['idaval1']);
			$aval2 = $dbQueries->getAvalColmenaData($creditData['idaval2']);
			$avalData = seleccionarAval($aval1, $aval2);

			// Obtiene el nombre del promotor
			$promotorNombre = mb_strtoupper($dbQueries->getPromotorNombre($creditData, $dbQueries));

			// Genera el encabezado del informe
			$html = generateReportHeader('Solicitud de Crédito - ' . $idcredito,  $headerData['logo'], $headerData['subtitulo'], $headerData['direccionSucursales'], $this->esquema);

			// Generación de contenido HTML
			$html .= generateSolicitudDatosGenerales($fecha, $creditData, $persona, $edad, $tipoVivienda, $colmenaData);
			$html .= generateSolicitudDatosActividad($creditData, $montoLetra, $periodo);
			$html .= generateDeclaracionVeracidad();

			// Muestra datos de acuerdo al tipo de crédito
			if ($creditData['idproducto'] == 1) { // Crédito colmena
				$html .= generarTabla3Firmas('Promotor (a)', $avalData['cargo'], 'Socia', $promotorNombre, $avalData['nombre'], $creditData['nombre']);
			} elseif ($creditData['idproducto'] == 10) { // Crédito Individual
				if ($promotorNombre === 'NO ASIGNADO') {
					$promotorNombre = mb_strtoupper($usuario);
				}

				$html .= generarTabla2Firmas('Promotor (a)', 'Socia', $promotorNombre, $creditData['nombre']);
			}

			$html .= htmlEnd();

			renderPDF($html, 'Solicitud de crédito - ' . $idcredito . '.pdf');
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}

	/**
	 * Genera un archivo PDF con los datos del pagaré asociado a un crédito específico.
	 * 
	 * @return void
	 */
	public function pdf_pagare_get()
	{
		try {
			// Inicialización de objetos y obtención de datos necesarios
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtiene datos del crédito
			$creditData = $dbQueries->getCreditData($idcredito);
			$montoLetra = numberToWords(number_format($creditData['monto'], 2, '.', ''));
			$tasas = $dbQueries->obtenerTasas($creditData);

			// Obtiene los datos de la sucursal en la que se solicitó el crédito
			$sucursal = $dbQueries->getSucursal($creditData['idsucursal']);

			// Formatea la fecha para el encabezado del pagaré
			$fechaFormateada = convertirFechaLetras($creditData['fecha_entrega_col']);
			$lugarFecha = $sucursal['municipio'] . ', a ' . $fechaFormateada;

			// Obtiene datos de avales
			$aval1 = $dbQueries->getAvalGrupoData($creditData['idaval1']);
			$aval2 = $dbQueries->getAvalColmenaData($creditData['idaval2']);

			if ($creditData['nivel'] < 15) {
				$aval1 = seleccionarAval($aval1, $aval2);
			}

			// Genera el encabezado del informe
			$html = generateReportHeader(
				'Pagaré - ' . $idcredito,
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'
			.seccion-right {
				text-align: right;
			}
			.pagare-titulo {
				margin-bottom: 25px;
			}',
				'13px'
			);

			// Generación de contenido HTML
			$html .= generatePagareHeaderHtml($this->esquema, $lugarFecha, $creditData['idpagare'], $sucursal);
			$html .= generarEspacioVertical();
			$html .= generatePagareBodyHtml($this->getEmpresa(), $creditData['monto'], $montoLetra, $tasas);
			$html .= generarEspacioVertical('35px');

			$html .= generarTabla2Firmas('<b>AVAL</b>', '<b>EL DEUDOR</b>', $aval1['nombre'], $creditData['nombre'], true);

			// Si el nivel de crédito es mayor o igual a 15, se agrega una tabla adicional para el segundo aval
			if ($creditData['nivel'] >= 15) {
				$html .= generarEspacioVertical('35px');
				$html .= generarTabla1Firma('<b>AVAL</b>', $aval2['nombre']);
			}

			$html .= htmlEnd();

			renderPDF($html, 'Pagaré - ' . $idcredito . '.pdf');
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}

	/**
	 * Genera un archivo PDF con la tabla de amortizaciones de un crédito.
	 *
	 * @throws Exception Si ocurre algún error durante la generación del PDF.
	 */
	public function pdf_tabla_amortizacion_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtiene datos del crédito
			$creditData = $dbQueries->getCreditData($idcredito);
			$monto = formatNumber($creditData['monto']);
			$sucursal = mb_strtoupper($dbQueries->getSucursal($creditData['idsucursal'])['nombre']);
			$tasas = $dbQueries->obtenerTasas($creditData);
			$fechaEntrega = date_format(new DateTime($creditData['fecha_entrega_col']), 'd/m/Y');

			// Obtiene el número de días que dura el crédito
			$fechaVencimiento = $dbQueries->getUltimaFechaVencimiento($idcredito);
			$diasCredito = calcularDiasEntreFechas($creditData['fecha_entrega_col'], $fechaVencimiento['fecha_vence']);

			// Obtiene el nombre del promotor
			$promotorNombre = $dbQueries->getPromotorNombre($creditData, $dbQueries);

			// Obtiene las amortizaciones
			$amortizaciones = $dbQueries->getAmortizacionesData($idcredito, $this->esquema);

			// Generación de contenido HTML
			$html = generateReportHeader(
				'Tabla de Amortizaciones - ' . $idcredito,
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'th {
					text-align: left;
					padding: 5px 0;
				},
				td {
					text-align: center;
					padding: 3px 0;
				}'
			);

			$html .= generarHeaderTablaAmortizaciones($creditData, $sucursal, $fechaEntrega, $monto, $tasas, $diasCredito, $promotorNombre);
			$html .= generarEspacioVertical('15px');
			$html .= tableCreateAmortizaciones($amortizaciones, $this->esquema);
			$html .= htmlEnd();

			renderPDF($html, 'Tabla de Amortizaciones - ' . $idcredito);
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}

	/**
	 * Genera un informe en formato PDF con el checklist de un crédito.
	 *
	 * @param int $idcredito El ID del crédito.
	 * @throws Exception Si ocurre un error durante la generación del informe.
	 */
	public function pdf_checklist_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			$checklist = $dbQueries->getCheckList($idcredito);

			// Generación de contenido HTML
			$html = generateReportHeader(
				'Checklist - ' . $idcredito,
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'td, th {
				padding: 3px 0 3px 15px;
			}'
			);
			$html .= tableCheckList($checklist);
			$html .= generarTabla3Firmas('ELABORÓ', 'SUPERVISÓ', 'REVISÓ', '', '', '');
			$html .= htmlEnd();

			renderPDF($html, 'Checklist - ' . $idcredito);
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}

	/**
	 * Genera un PDF para el retiro de garantías de un crédito especifico.
	 *
	 * @param int $idcredito El ID del crédito.
	 * @throws Exception Si no se encuentra información para el ID de crédito proporcionado.
	 */
	public function pdf_retgarantia_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtiene datos del crédito
			$garantiaData = $dbQueries->getCreditoGarantia($idcredito);
			$montoLetra = numberToWords($garantiaData[0]);

			// Generación de contenido HTML
			$html = generateReportHeader(
				'Retiro de garantías - ' . $idcredito,
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'th {
			padding: 8px;
			}',
				'13px'
			);
			$html .= generarEspacioVertical();
			$html .= generateBodyReciboGarantia($garantiaData, $montoLetra);
			$html .= generarTabla2Firmas(mb_strtoupper($garantiaData['promotor']), $garantiaData['acreditado_nombre'], 'Promotor (a)', 'Socia', true);
			$html .= htmlEnd();

			renderPDF($html, 'Retiro de garantías - ' . $idcredito, false);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	/**
	 * Genera un archivo PDF con la emisión de créditos para una fecha y sucursal específica.
	 *
	 * @throws Exception Si ocurre algún error durante la generación del PDF.
	 */
	public function pdf_emision_cheques_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);

			// Obtener la fecha de la URI y formatearla
			$fecha = $this->uri->segment(4);

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtener el ID de sucursal y su nombre
			$idsucursal = $this->session->userdata('sucursal_id');
			$sucursalNombre = mb_strtoupper($dbQueries->getSucursal($idsucursal)['nombre']);

			// Obtener datos de emisión de créditos para la fecha y sucursal
			$emisionData = $dbQueries->getEmisionCreditos($fecha, $idsucursal);

			// Obtener el número de semana y convertir la fecha a letras
			$numeroSemana = getNumeroSemana($fecha);
			$fechaLetras = convertirFechaLetras($fecha, true);

			// Generación de contenido HTML
			$html = generateReportHeader(
				'Emisión de Créditos',
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'
				table {
					font-size: 11px;
				}
				td {
					padding: 5px;
				}'
			);

			$html .= headerEmisionCreditos($this->esquema, $sucursalNombre, $numeroSemana, $fechaLetras);
			$html .= tableCreateEmisionCreditos($emisionData, $this->esquema, false);
			$html .= htmlEnd();

			renderPDF($html, 'Emisión de Créditos.pdf', false, 'letter', 'landscape');
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}

// CRÉDITOS ACTIVOS EN TODAS LAS SUCURSALES
	/**
	 * Genera un informe en formato PDF con los créditos activos de todas las sucursales.
	 * 
	 * @return void
	 */
	public function pdf_credactivosg_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);

			$idNivel = $this->uri->segment(4);
			$title = $idNivel > 0 ? ": NIVEL $idNivel" : ": TODOS LOS NIVELES";

			// Obtener datos de créditos activos
			$creditosActivosData = $dbQueries->getCreditosActivos($idNivel);

			// Generar tabla HTML solo si hay datos disponibles
			$tabla = '';
			if ($creditosActivosData) {
				if ($this->esquema == 'ama.') {
					$headers = array("No.", "Suc.", "Crédito", "Fecha Entrega", "Socia", "Nivel", "Monto", "No. Pagos", "Periodo", "Grupo", "Colmena", "Promotor");
				} else {
					$headers = array("No.", "Suc.", "Crédito", "Fecha Entrega", "Socia", "Nivel", "Monto", "No. Pagos", "Grupo", "Colmena", "Promotor");
				}
				$tabla = generateCreditosActivosTable($headers, $creditosActivosData, $this->esquema);
			}

			// Generar contenido HTML
			$html = generateHead(
				'Créditos Activos',
				'
				table {
					font-size: 12px;
					width: 100%;
					border-collapse: collapse;
					font-family: Arial, sans-serif;
					margin: 20px 0;
				}
				th, td {
					padding: 4px; /* Reduced padding */
				}
				th {
					text-align: center;
					height: 15px;
				}
				td {
					text-align: center;
				}
				tr:hover {
					background-color: #f1f1f1;
				}
				.text-left {
					text-align: left;
					padding-left: 6px; 
					padding-right: 6px; 
				}
			'
			);
			$html .= generateSimpleHeader(getEmpresa($this->esquema));
			$html .= "<h3 align='center'>REPORTE GLOBAL DE CRÉDITOS ACTIVOS $title</h3>";
			$html .= $tabla;
			$html .= htmlEnd();

			// Imprimir HTML y detener la ejecución
			print_r($html);
			die();
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}
	}



























	// REFACTORIZACIÓN DE PLAN DE PAGOS
	public function pdf_plan_pago_get()
	{
		try {
			$dbQueries = new DatabaseQueries($this->base, $this->esquema);
			$idcredito = $this->uri->segment(4);
			// $esquema = $this->session->userdata('esquema');

			// Obtiene datos comunes para el encabezado
			$headerData = getCommonHeaderData($this->esquema, $dbQueries);

			// Obtiene datos del crédito
			$cred = $dbQueries->getCreditData($idcredito);
			$fecha = date_format(new DateTime($cred['fecha_entrega_col']), 'd/m/Y');

			// Obtiene los datos de la sucursal en la que se solicitó el crédito
			$sucursal = $dbQueries->getSucursal($cred['idsucursal']);

			// Obtiene datos de la colmena
			$colmenaData = $dbQueries->getColmenaData($cred['idgrupo']);
			$promotor = $dbQueries->getColmenaPromotor($colmenaData['idcolmena']);
			$promotorNombre = mb_strtoupper($promotor['promotor']);
			$usuario = $dbQueries->getUser($cred['usuario']);

			// DATOS PERSONA
			$persona = $dbQueries->getPersonaData($cred['idacreditado']);
			$fechaSolicitud = new DateTime($cred['fecha']);
			$edad = calcularEdad($persona['fecha_nac'], $fechaSolicitud);

			// Obteniendo nombre de la presidenta de grupo
			$pres_grupo = $dbQueries->getPresidentaGrupo($cred['idgrupo']);

			// Obteniendo las amortizaciones del crédito
			$amortizaciones = $dbQueries->getAmortizacionesData($idcredito, $this->esquema);

			// Determina el encabezado de la tabla de acuerdo al esquema
			$title = determinarEncabezadoPlanPagos($cred['idproducto'], $this->esquema);

			// Creación de la tabla con los encabezados determinados
			$tablaPlanPagos = ($cred['idproducto'] === '10') ? tableCreatePlanPagosInd($title, $amortizaciones) : tableCreatePlanPagos($title, $amortizaciones);

			$espaciado = '';
			$espaciado2 = '';

			$numPagos = intval($cred['num_pagos']);

			if ($numPagos < 25) {
				$espaciado = '<br><br>';
				$espaciado2 = '<div style="page-break-before: always;"></div>';
			} elseif ($numPagos == 25 || $numPagos == 30) {
				$espaciado = '<div style="page-break-before: always;"></div>';
				$espaciado2 = '<hr><div></div>';
			} else {
				$espaciado = '<br><br><br>';
				$espaciado2 = '<div style="page-break-before: always;"></div>';
			}

			// Generación de contenido HTML
			$html = generateReportHeader(
				'Plan de Pagos - ' . $idcredito,
				$headerData['logo'],
				$headerData['subtitulo'],
				$headerData['direccionSucursales'],
				$this->esquema,
				'.header-plan {
				padding: 5px 0;
			}'
			);

			$html .= generarHeaderPlanPagos($cred, $sucursal, $colmenaData, $fecha, $persona);
			$html .= $tablaPlanPagos;
			$html .= $espaciado;
			$html .= generarEncabezadoFirmas('Entregó', 'Recibió');
			$html .= generarTabla2Firmas($promotorNombre, $cred['nombre'], 'Promotor (a)', 'Prestataria', true);
			$html .= avisoPlanPagos();
			$html .= $espaciado2;
			$html .= generarEspacioVertical('5px');
			$html .= generateSolicitudDatosGenerales($fecha, $cred, $persona, $edad, '', $colmenaData, true);
			$html .= generarEspacioVertical('1px');
			$html .= generateSolicitudDatosActividad($cred, '', '', true);

			if ($cred['idproducto'] == 1) { // Crédito colmena
				$html .= generarTabla3Firmas('Promotor (a)', 'Presidenta de Grupo', 'Socia', $promotorNombre, mb_strtoupper($pres_grupo['nombre1']) . ' ' . mb_strtoupper($pres_grupo['nombre2']) . ' ' . mb_strtoupper($pres_grupo['apaterno']) . ' ' . mb_strtoupper($pres_grupo['amaterno']), $cred['nombre']);
			} elseif ($cred['idproducto'] == 10) { // Crédito Individual
				if ($promotorNombre === 'NO ASIGNADO') {
					$promotorNombre = mb_strtoupper($usuario);
				}

				$html .= generarTabla2Firmas('Promotor (a)', 'Socia', $promotorNombre, $cred['nombre']);
			}

			$html .= htmlEnd();

			renderPDF($html, 'Plan de pagos - ' . $idcredito);
		} catch (Exception $e) {
			// Manejar el error
			echo "Error: " . $e->getMessage();
		}
	}



	// REFACTORIZACIÓN DE CONTRATO DE CRÉDITO
	public function pdf_contrato_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);
		$empresa = getEmpresa($this->esquema);

		// Obtiene datos comunes para el encabezado
		$headerData = getCommonHeaderData($this->esquema, $dbQueries);

		// Obtiene datos del crédito
		$cred = $dbQueries->getCreditData($idcredito);
		$monto = $cred['monto'];
		$montoLetra = numberToWords(number_format($monto, 2, '.', ''));
		$fecha = convertirFechaLetras(new DateTime($cred['fecha_entrega_col']));
		$tasas = $dbQueries->obtenerTasas($cred);
		$fechaVencimiento = $dbQueries->getUltimaFechaVencimiento($idcredito);

		// Obtiene los datos de la sucursal en la que se solicitó el crédito
		$sucursal = $dbQueries->getSucursal($cred['idsucursal']);

		// Obtiene el tipo de persona, física o moral
		$tipoPersona = $dbQueries->getPersonaData($cred['idacreditado']);
		$tipoPersona = $tipoPersona['tipo'];

		// Obtiene datos de avales
		$aval1 = $dbQueries->getAvalGrupoData($cred['idaval1']);
		$domicilioAval1 = $dbQueries->getPersonaDataA($cred['idaval1']);
		$domicilioAval1 = $dbQueries->getPersonaDomicilio($domicilioAval1['idpersona']);

		$aval2 = $dbQueries->getAvalColmenaData($cred['idaval2']);
		$domicilioAval2 = $dbQueries->getPersonaDataA($cred['idaval2']);
		$domicilioAval2 = $dbQueries->getPersonaDomicilio($domicilioAval2['idpersona']);

		// Verifica si $aval1 está vacío y si el nivel es menor a 15
		if (empty($aval1) && $cred['nivel'] < 15) {
			// Asigna los datos de $aval2 a $aval1
			$aval1 = $dbQueries->getAvalColmenaData($cred['idaval2']);
			$domicilioAval1 = $dbQueries->getPersonaDataA($cred['idaval2']);
			$domicilioAval1 = $dbQueries->getPersonaDomicilio($domicilioAval1['idpersona']);
		}

		$diasCredito = calcularDiasEntreFechas($cred['fecha_entrega_col'], $fechaVencimiento['fecha_vence']);

		$fechaVencimiento = convertirFechaLetras(new DateTime($fechaVencimiento['fecha_vence']));

		// Generación de contenido HTML
		$html = generateReportHeader(
			'Contrato de crédito - ' . $idcredito,
			$headerData['logo'],
			$headerData['subtitulo'],
			$headerData['direccionSucursales'],
			$this->esquema,
			'
			ol, ol>ol{
				margin: 10px 0;
			}
			hr.informacion {
				margin-top: 18px;
			}',
			'11px'
		);

		$html .= generarHeaderContratoHTML(mb_strtoupper($sucursal['nombre']), $fecha, $idcredito);
		$html .= generarEspacioVertical('10px');

		$html .= generarContratoHTML($cred, $empresa, $this->esquema, $tipoPersona, $cred['nivel'], $aval1, $aval2, $domicilioAval1, $domicilioAval2, number_format($monto, 2, '.', ','), mb_strtoupper($montoLetra), $diasCredito, $fechaVencimiento, $tasas, $fecha, $sucursal);

		$html .= generarTabla3Firmas($empresa, $aval1['nombre'], '<b>' . $cred['nombre'] . '</b>', 'LA ACREDITANTE', 'EL AVAL', 'EL ACREDITADO');

		if ($cred['nivel'] >= 15) {
			$html .= generarEspacioVertical('100px');
			$html .= generarTabla1Firma($aval2['nombre'], 'EL AVAL');
		}

		$html .= htmlEnd();

		renderPDF($html, 'Contrato de crédito - ' . $idcredito . '.pdf');
	}



	//REFACORIZACIÓN DE CONVENIO BANCOMUNIDAD
	public function pdf_convenioban_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);
		$esquema = $this->session->userdata('esquema');
		$empresa = getEmpresa($esquema);

		// Obtiene datos para el encabezado
		$logo = getLogo($esquema);
		$subtitulo = getSubtitulo($esquema);
		$sucursales = $dbQueries->getSucursales();
		$direccionSucursales = generateDireccion($sucursales, $esquema);

		$cred = $dbQueries->getCreditData($idcredito);
		$tasa = $dbQueries->obtenerTasas($cred);
		$tasa = $tasa['tasaMensual'];

		$fecha = new DateTime($cred['fecha_entrega_col']);
		$dia = convertirNumeroALetras(date_format($fecha, 'd'));
		$mes = mb_strtolower($this->getFechaMes($fecha));
		$año = mb_strtolower($this->getFechaYearLetras($fecha));

		$promotorNombre = $dbQueries->getColmenaPromotor($cred['idcolmena']);
		$promotorNombre = mb_strtoupper($promotorNombre['promotor']);

		$monto = number_format($cred['monto'], 2, '.', ',');
		$montoLetra = $this->numeroToLetras($cred['monto']);

		$fechaLetras = convertirFechaLetras($fecha);

		// Obtiene datos de avales
		$aval1 = $dbQueries->getAvalGrupoData($cred['idaval1']);
		$aval2 = $dbQueries->getAvalColmenaData($cred['idaval2']);

		// Verifica si $aval1 está vacío y si el nivel es menor a 15
		if (empty($aval1) && $cred['nivel'] < 15) {
			// Asigna los datos de $aval2 a $aval1
			$aval1 = $dbQueries->getAvalColmenaData($cred['idaval2']);
		}

		// Generación de contenido HTML
		$head = generateHead(
			'Convenio - ' . $idcredito,
			'h4 {
				padding: 10px;
			}'
		);
		$html = $head;
		$html .= generateHeaderSucursales($direccionSucursales, $this->getEmpresa(), $subtitulo, $logo);
		$html .= generarBodyConvenio($empresa, $cred, $monto, $montoLetra, $fechaLetras, $tasa, $dia, $mes, $año);

		$html .= generarTabla3Firmas('C. MARIO ENRIQUE RENDÓN HERNÁNDEZ', $promotorNombre, $cred['nombre'], '', '', '');

		if ($cred['nivel'] < 15) {
			$html .= espaciado('90px');
			$html .= generarTabla1Firma($aval1['nombre'], 'AVAL');
		} else {
			$html .= espaciado('20px');
			$html .= generarEncabezadoFirmas('AVAL', 'AVAL');
			$html .= espaciado('40px');
			$html .= generarTabla2Firmas($aval1['nombre'], $aval2['nombre'], '', '', true);
		}

		$html .= htmlEnd();

		renderPDF($html, 'Convenio - ' . $idcredito);
	}

	//REFACTORIZACIÓN DE CONTRATO DE AHORRO
	public function pdf_ahorro_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$esquema = $this->session->userdata('esquema');
		$empresa = getEmpresa($esquema);

		// Obtiene datos para el encabezado
		$logo = getLogo($esquema);
		$subtitulo = getSubtitulo($esquema);
		$sucursales = $dbQueries->getSucursales();
		$direccionSucursales = generateDireccion($sucursales, $esquema);

		$id = $this->uri->segment(4); //id obtenido puede ser de de persona o acreditado
		$opc = $this->uri->segment(5);

		$acreditadoData = $dbQueries->getAcreditadoSolicitud($id, $opc);
		$idAcreditado = $acreditadoData['acreditadoid'];

		$nombreSocia = $acreditadoData['nombre'];
		$idsocio = $acreditadoData['idacreditado'];
		$direccion = $acreditadoData['direccion'];

		$ahorroInfo = $dbQueries->getAhorroAlta($idAcreditado);
		$sucursalDireccion = $dbQueries->getSucursal($ahorroInfo['idsucursal']);
		$sucursalDireccion = $sucursalDireccion['municipio'];
		$fechaAlta = convertirFechaLetras($ahorroInfo['fecha_alta']);

		// Generación de contenido HTML
		$head = generateHead('Contrato de ahorro - ' . $id);
		$html = $head;
		$html .= generateHeaderSucursales($direccionSucursales, $this->getEmpresa(), $subtitulo, $logo);
		$html .= generateBodyContratoAhorro($nombreSocia, $empresa, $direccion, $fechaAlta, $sucursalDireccion);
		$html .= generarTabla2Firmas($nombreSocia, $empresa, 'No. de Socio: ' . $idsocio, 'Sociedad Financiera Comunitaria');
		$html .= htmlEnd();

		renderPDF($html, 'Contrato de Ahorro - ' . $id);
	}

	public function tableEdoCuenta($title, $data)
	{
		$html = '';
		$html .= '<table style="width:80%" align="center">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';

		$capital_req = 0;
		$capital_pag = 0;
		$interes_req = 0;
		$interes_mora = 0;
		$capital_saldo = 0;
		$dias_saldo = 0;
		$capital_inicial = 0;
		$iva = 0;
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
				$iva = ($value['c_interes_acumula'] + $value['c_mora']) * 0.16;
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
				$html .= '  <td align="right">' . number_format($value['c_mora'], 2, '.', ',') . '</td>';
				$html .= '  </tr>';
				$capital_req = $value['capital_requerido'];
				$capital_pag = $value['capital_pagado'];
				$interes_req = $value['c_interes_acumula'] + $value['c_mora'];
				$interes_mora = $value['c_mora'];
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
			$esp_capital = 0;
			$esp_int = 0;
			if ($capital_req > $capital_pag) {
				$esp_capital = $capital_req - $capital_pag;
				$esp_int = $interes_req;
			}
			$esp_iva = ($esp_int + $interes_mora) * 0.16;
			$html2 .= '  <tr>';
			$html2 .= '  <td align="right">' . number_format($esp_capital, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($esp_int, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($interes_mora, 2, '.', ',') . ' </td>';
			$html2 .= '  <td align="right">' . number_format($esp_iva, 2, '.', ',') . ' </td>';
			$esp_total = $esp_capital + $esp_int + $esp_iva;
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
			$esp_capital = 0;
			$esp_int = 0;
			$esp_iva = 0;
			if ($capital_req > $capital_pag) {
				$esp_capital = $capital_req - $capital_pag;
				$esp_int = $interes_req;
			}
			$esp_iva = ($esp_int + $interes_mora) *  0.16;
			$html3 .= '  <tr>';
			$html3 .= '  <td align="right">' . number_format($capital_saldo, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($esp_int, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($interes_mora, 2, '.', ',') . ' </td>';
			$html3 .= '  <td align="right">' . number_format($esp_iva, 2, '.', ',') . ' </td>';
			$esp_total = $capital_saldo + $esp_int + $esp_iva;
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

	public function tableCreateAmorNueva($title, $data)
	{
		$html = '';
		$html .= '<table style="width:80%" align="center">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		foreach ($data as $key => $value) {
			$fecha = date_create($value['fecha_vence']);
			$fecha = date_format($fecha, 'd/m/Y');

			$fechaPago = date_create($value['fecha_pago']);
			$fechaPago = date_format($fechaPago, 'd/m/Y');

			$capital = $value['saldo_capital2'];
			$cappag = $value['total'] - $value['interes_pag'] - $value['iva_pag'];
			$html .= '  <tr>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$html .= '  <td>' . $fecha . '</td>';
			$html .= '  <td>' . $fechaPago . '</td>';
			$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_pag'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva_pag'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($cappag, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['total'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';
		return $html;
	}

	// AGREGADO 21-08-2023
	function formatDate($date)
	{
		$formattedDate = date_create($date);
		return date_format($formattedDate, 'd/m/Y');
	}


	public function tableCreateConvenio($title, $data, $monto)
	{
		$saldo = $monto;
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		//array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "Ahorro comp.", 
		//	"Saldo ahorro comp.", "Total del pago", "Firma del promotor", "Incidencias");
		foreach ($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			$total = $value['ssi_total'];
			$ssi_capital = $value['ssi_capital'];
			$saldo = $saldo - $ssi_capital;
			$html .= '  <tr>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$html .= '  <td>' . $value['fecha_vence'] . '</td>';
			$html .= '  <td>' . $ssi_capital . '</td>';
			$html .= '  <td>' . $saldo . '</td>';
			$html .= '  <td align="right">' . $value['aportesol'] . '</td>';
			$html .= '  <td align="right">' . $garantia . '</td>';
			$html .= '  <td align="right">' . $numero * $garantia . '</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html .= '  <td align="right">' . $total . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td></td>';
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

	//2021-06-20 Convenio individual
	public function tableCreateConvenioInd($title, $data, $monto)
	{
		$saldo = $monto;
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$totAmor = 0;
		//$title = array("Pago","Vencimiento","Capital","Saldo Capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Incidencias");		
		foreach ($data as $key => $value) {
			$numero = $value['numero'];
			$garantia = $value['garantia'];
			//$total = $value['ssi_total'];
			$capital = $value['capital'];
			$saldo = $saldo - $capital;
			$totAmor = $capital + $value['interes'] + $value['iva'];
			$html .= '  <tr>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$html .= '  <td>' . $value['fecha_vence'] . '</td>';
			$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($saldo, 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2, '.', ',') . '</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html .= '  <td align="right">' . $totAmor . '</td>';
			$html .= '  <td></td>';
			$html .= '  <td></td>';
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





	//2019-04-29 Tasas
	public function pdf_tabla_amortizacion_nueva_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "nomcolmena", "fecha_entrega_col", "tasa", "tasa_mora");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$query = "select dias, ssi_tasa FROM niveles WHERE nivel=" . $cred['nivel'] . " ORDER BY fecha_inicio desc limit 1";
		$temp = $this->base->querySelect($query, TRUE);
		$tasa = $temp[0]['ssi_tasa'];
		$nivel = $temp[0]['dias'];



		$monto = $cred['monto'];

		$sucursal = $dbQueries->getSucursal($cred['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fecha = new DateTime($cred['fecha_entrega_col']);
		if ($cred['idproducto'] === '10') {
			$tasa = $cred['tasa'] * 12;
			$tasa_mora = $cred['tasa_mora'];
		}

		$fields = array("numero", "fecha_vence", "fecha_pago", "saldo_capital", "saldo_capital2", "capital", "capital_pag", "interes_pag", "iva_pag", "aportesol", "garantia", "total");
		$where = array("idcredito" => $idcredito);
		$order_by = array(array('campo' => 'numero', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "amortizaciones", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		$title = array("Pago", "Vencimiento", "Pago", "Saldo Capital", "Interes", "IVA", "Capital", "Cuota");
		$tabla = '';
		$tabla .= $this->tableCreateAmorNueva($title, $amor);


		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

		$html .= '<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: ' . $cred['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: ' . $cred['idcredito'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '
					</th>		
					<th class="seccion-left">
						Fecha: ' . date_format($fecha, 'd/m/Y') . '
					</th>		
				</tr>	
				<tr>
					<th class="seccion-left">
						Monto: ' . number_format($monto, 2, '.', ',') . '
					</th>		
					<th class="seccion-left">
						Interes: ' . number_format($tasa, 2, '.', ',') . '%
					</th>
					<th class="seccion-left">
						Mora: ' . number_format($tasa * 2, 2, '.', ',') . '%
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Plazo: ' . $nivel . ' dias
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
						Producto: Crédito colmena ' . $cred['nivel'] . '
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Oficial SF: 
					</th>		
				</tr>													
				<tr>
					<th class="seccion-left">
						Nivel: ' . $cred['nivel'] . '
					</th>		
					<th>
					</th>
					<th>
					</th>		
				</tr>													
			</table>';
		$html .= '<br><br><div > </div>';
		$html .= $tabla;
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}


	public function pdf_edo_cuenta_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "nomcolmena", "fecha_entrega_col");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));
		$pagare = $cred['idpagare'];

		$sucursal = $dbQueries->getSucursal($cred['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fecha = new DateTime($cred['fecha_entrega_col']);

		$amor = $this->base->querySelect("SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, (p_capital_vig+p_capital_ven) as pag_capital, (p_interes_vig+p_interes_ven) as interes, 
			(p_iva_vig+p_iva_ven) as iva, p_pago_total as total_pagado,
			capital_saldo as saldo_capital, coalesce(p_interes_mora,0) as int_mora, capital_pagado, capital_requerido, (interes_vig+interes_ven) as c_interes_acumula, interes_mora as c_mora, (iva_vig+iva_ven+iva_mora) as c_iva
			FROM ama.ftr_pago_individual(" . $idcredito . ", current_timestamp::timestamp)", TRUE);
		$title = array("Pago", "Vencimiento", "Pago", "Dias", "Capital pagado", "Interes", "IVA", "Pago total", "Saldo capital", "Int. mora",);
		$tabla = '';
		$tabla .= $this->tableEdoCuenta($title, $amor);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

		$html .= '<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: ' . $cred['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: ' . $cred['idcredito'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '
					</th>		
					<th class="seccion-left">
						Fecha: ' . date_format($fecha, 'd/m/Y') . '
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
						Nivel: ' . $cred['nivel'] . '
					</th>		
					<th>
					</th>
					<th>
					</th>		
				</tr>													
			</table>';
		$html .= '<br><br><div > </div>';
		$html .= $tabla;
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}


	//2019-04-09 Integración credito individual
	public function pdf_convenio_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);

		$fields = array("idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "idproducto", "nivel", "monto", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov", "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "sexo", "idactividad", "direccion", "idcolmena", "nomcolmena", "fecha_entrega_col");
		$where = array("idcredito" => $idcredito);
		$cred = $this->base->selectRecord($this->esquema . "get_solicitud_credito_ind", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$monto = $cred['monto'];
		$monto_letra = $this->numeroToLetras(number_format($monto, 2, '.', ''));

		$sucursal = $dbQueries->getSucursal($cred['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fecha = new DateTime($cred['fecha_entrega_col']);

		$idgrupo = $cred['idgrupo'];
		if ($idgrupo === "0") {
			$col_nombre = "";
			$col_numero = "";
			$col_grupo = "";
		} else {
			$fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
			$where = array("idgrupo" => $idgrupo);
			$gpo = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
			$gpo = $gpo[0];
			$col_nombre = $gpo['colmena_nombre'];
			$col_numero = $gpo['colmena_numero'];
			$col_grupo = $gpo['colmena_grupo'];
		}

		$fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "(total+garantia+ajuste) as ssi_total", "(total-aportesol+ajuste) as ssi_capital", "iva", "aportesol", "garantia", "total");
		$where = array("idcredito" => $idcredito);
		$order_by = array(array('campo' => 'numero', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "amortizaciones", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		$tabla = '';
		if ($cred['idproducto'] === "10") {
			$title = array("Pago", "Vencimiento", "Capital", "Saldo Capital", "Interes", "IVA", "Total del pago", "Firma del promotor", "Incidencias");
			$tabla .= $this->tableCreateConvenioInd($title, $amor, $monto);
		} else {
			$title = array("Pago", "Vencimiento", "Capital", "Saldo Capital", "Interes", "Ahorro comp.", "Saldo ahorro comp.", "Total del pago", "Firma del promotor", "Incidencias");
			$tabla .= $this->tableCreateConvenio($title, $amor, $monto);
		}

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE AMORTIZACION</h3>';

		$html .= '<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						Sucursal: ' . $cred['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th></th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '
					</th>		
					<th></th>
					<th class="seccion-left">
						Credito: ' . $cred['idcredito'] . '
					</th>
				</tr>	
				<tr>
					<th class="seccion-left">
						Fecha: ' . date_format($fecha, 'd/m/Y') . '
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Monto: ' . number_format($monto, 2, '.', ',') . '
					</th>		
				</tr>									
				<tr>
					<th class="seccion-left">
						Colmena: ' . $col_numero . ' - ' . $col_nombre . '
					</th>		
					<th>
					</th>
					<th class="seccion-left">
						Grupo: ' . $col_grupo . '
					</th>		
				</tr>													
			</table>';
		$html .= '<br><br><div > </div>';
		$html .= $tabla;
		$html .= '<br><br>';

		$html .= '<br> <br> <br> <br> 
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
			<td style="border-top: 1px solid"  align="center" width="25%">' . $cred['nombre'] . '<br>Socia</td>
			<td></td>
			</tr>		
		</table>';

		$html .= htmlEnd();

		renderPDF($html, 'Convenio');
	}


	public function pdf_retgarantia_acreditado_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idacreditado = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idacreditado", "idsucursal", "nombre", "col_numero", "col_nombre", "grupo_numero", "grupo_nombre");
		$where = array("idacreditado" => $idacreditado);
		$cred = $this->base->selectRecord($this->esquema . "get_acreditado_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);
		$cred = $cred[0];

		$sucursal = $dbQueries->getSucursal($cred['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		//2019-11 Se consulta los depositos y retiros para obtener la diferencia
		//$data = $this->base->querySelect("SELECT sum(comprometido) as total FROM ".$this->session->userdata('esquema')."get_creditos_resumen2(".$cred['idacreditado'].")", TRUE);
		$sql = "SELECT sum(q.deposito) as deposito, sum(q.ahorro) as ahorro, sum(q.diferencia) as total 
			FROM (
				select CASE WHEN m.movimiento='D' THEN m.importe ELSE 0 END as deposito, CASE WHEN m.movimiento='R' THEN m.importe ELSE 0 END as ahorro, 
				CASE WHEN m.movimiento='D' THEN m.importe ELSE m.importe*-1 END as diferencia,
				m.fecha
				from " . $this->session->userdata('esquema') . "ahorros as a
					join " . $this->session->userdata('esquema') . "ahorros_mov as m ON a.idahorro = m.idahorro and a.idacreditado=" . $cred['idacreditado'] . "
				ORDER BY m.fecha
				) as q ";

		$data = $this->base->querySelect($sql, TRUE);
		$total_recibo = $data[0]['total'];

		if ($total_recibo == 0) {
			$monto_letra = 'cero';
		} else {
			$monto_letra = $this->numeroToLetras(number_format($total_recibo, 2, '.', ''));
		}

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">';

		$html .= '<br>
			<table style="width:100%">
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>
				<tr>
					<th colspan="2" class="seccion-left">
						AUTORIZACION DE TRANSPASO POR LA CANTIDAD DE
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						' . $total_recibo . ' 
					</th>		
					<th class="seccion-left">' . $monto_letra . '</th>
				</tr>
				<tr> <th colspan="2" class="seccion-left"> </th> </tr>	
			</table>';
		$html .= '<br><br><br>
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
							' . $cred['nombre'] . '
						</th>		
						<th class="seccion-left">Número: ' . $cred['idacreditado'] . ' </th>
					</tr>	
					<tr>
						<th class="seccion-left">
							DE LA COLMENA NUMERO: ' . $cred['col_numero'] . '
						</th>		
						<th class="seccion-left">DENOMINADA: ' . $cred['col_nombre'] . '
						</th>
						<th class="seccion-left">
							DEL GRUPO: ' . $cred['grupo_numero'] . '
						</th>		
					</tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
					<tr> <th colspan="3" class="seccion-right">A   10   DE   JULIO   DE   2018  </th> </tr>
					<tr> <th colspan="3" class="seccion-left"> </th> </tr>
				</table>

			</div>';

		$html .= '<br> <br>
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
				<td style="border-top: 1px solid"  align="center" width="25%">' . $cred['nombre'] . '<br>Socia</td>
				<td></td>
				</tr>		


			</table> </div>';

		$html .= htmlEnd();

		renderPDF($html, 'Report', false);
	}





	public function tableCreateCreditos($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		foreach ($data as $key => $value) {
			$capital = $value['saldo_capital'];
			$html .= '  <tr>';
			$html .= '  <td>' . $value['numero'] . '</td>';
			$html .= '  <td>' . $value['fecha_vence'] . '</td>';
			$html .= '  <td>' . $capital . '</td>';
			$html .= '  <td align="right">' . $value['interes'] . '</td>';
			$html .= '  <td align="right">' . $value['iva'] . '</td>';
			$html .= '  <td align="right">' . $value['capital'] . '</td>';
			//$html.='  <td>'.$value['aportesol'].'</td>';
			//$html.='  <td>'.$value['garantia'].'</td>';
			$html .= '  <td align="right">' . $value['total'] . '</td>';
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

	public function pdf_creditosfecha_get()
	{
		//$miFecha = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$data = $this->base->querySelect("SELECT c.idsucursal, c.fecha_pago, c.proy_observa, c.monto, c.nivel, a.acreditado
			FROM $this->session->userdata('esquema').creditos as c
				JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE fecha::date=current_date::date", TRUE);

		$total_recibo = $data[0]['monto'];
		$monto_letra = $this->numeroToLetras(number_format($total_recibo, 2, '.', ''));


		$title = array("Fecha", "Acreditado", "Nivel", "Monto", "Observacion");
		$tabla = '';
		//$tabla.= $this->tableCreateCreditos($title, $list);

		$header = $this->headerReport('CHECK LIST');
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">CHECK LIST</h3>';

		//$html.='<div>'.$tabla.'</div>';			

		$html .= '<br>
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

		$html .= '<br> <br>
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

		$html .= htmlEnd();

		renderPDF($html, 'Report', false);
	}





	public function pdf_emision_cheques_year_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$esquema = $this->session->userdata('esquema');

		// Obtiene datos para el encabezado
		$subtitulo = getSubtitulo($esquema);
		$year = $this->uri->segment(4);

		if ($year === "0") {
			$strYear = date("Y") - 1;
		} else {
			$strYear = date("Y");
		}
		$fechaInicio = '01/01/' . $strYear;
		$fechaFin = '31/12/' . $strYear;

		$idsucursal = $this->session->userdata('sucursal_id');
		$sucursalNombre = $dbQueries->getSucursal($idsucursal);
		$sucursalNombre = $sucursalNombre['nombre'];

		$emisionData = $dbQueries->getEmisionCreditosYear($idsucursal, $fechaInicio, $fechaFin, $esquema);

		$acreditadoId = $dbQueries->getAcreditadoId(1669);
		$creditoPrevio = $dbQueries->getPreviousCredit($acreditadoId, 23857, $esquema);
		$garantia = $dbQueries->getTotalAmortizations($creditoPrevio, $esquema);
		$garantiaFinal = $dbQueries->getLiquidaciones($creditoPrevio, $garantia, $esquema);

		$tabla = tableCreateEmisionCreditos($emisionData, $esquema);

		// Generación de contenido HTML
		$head = generateHead(
			'Emisión de Créditos',
			'
			table {
				font-size: 11px;
			}
			td {
				padding: 5px;
			}'
		);

		$html = $head;
		$html .= generateHeaderSucursales('', getEmpresa($esquema), $subtitulo, '');

		$html .= '
			<h3 align="center">SOLICITUD DE EMISIÓN DE CRÉDITOS</h3>
			<h3 align="center">Ruta ' . $sucursalNombre . '</h3>		
			<h3 align="center">DEL ' . $fechaInicio . ' AL ' . $fechaFin . '</h3>
			';

		/* $html .= '
			<h3>Acreditadoid: ' . $acreditadoId . '</h3>
			<h3>Credito previo: ' . $creditoPrevio . '</h3>
			<h3>Monto Garantia: ' . $garantia . '</h3>
			<h3>Monto Garantia: ' . $garantiaFinal . '</h3>
			'; */


		$html .= $tabla;

		$html .= htmlEnd();

		print_r($html);
		die;
		renderPDF($html, 'Emisión de Cheques Anual', true, 'letter', 'landscape');
	}


	public function pdf_prev_ahorro_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$fecha = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		//$valores = $this->put('data')?$this->put('data', TRUE):array();
		$idsuc = $this->session->userdata('sucursal_id');

		$sucursal = $dbQueries->getSucursal($idsuc);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fields = array("acreditadoid", "idsucursal", "acreditado", "idacreditado", "numero_cuenta", "fecha", "base", "interes", "idpoliza");
		//$where = array("fecha"=>$fecha, "idsucursal"=>$idsuc);
		$where = array("fecha" => $fecha);
		$order_by = array(array('campo' => 'acreditado', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "v_provision_ahorro", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		//$title = array("Numero","Acreditado", "Cuenta", "Base", "Interes", "Poliza");
		$title = array("Numero", "Acreditado", "Cuenta", "Base", "Interes");
		$tabla = '';

		//print_r($amor);
		//die;

		$tabla .= $this->tableCreatProv($title, $amor);

		//print_r($tabla);
		//die;

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">PROVISION DE CUENTAS DE AHORRO</h3>
			<h3 align="center">FECHA: ' . $fecha . '</h3>';

		$html .= '<div > </div>';
		$html .= '<font size="9px">';
		$html .= '<div>' . $tabla . '</div>';
		$html .= '<font size="9px">';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}


	public function tableCreatProvSalto($title, $data)
	{
		$html = '';
		$intCiclo = 0;
		foreach ($data as $key => $value) {
			if ($intCiclo == 0) {
				$html .= '<table style="width:80%" align="center">';
				$html .= '  <tr>';
				foreach ($title as $key => $value1) {
					$html .= '    <th>' . $value1 . '</th>';
				}
				$html .= '  </tr>';
			}

			$intCiclo = $intCiclo + 1;
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['acreditado'] . '</td>';
			$html .= '  <td>' . $value['numero_cuenta'] . '</td>';
			$html .= '  <td align="right">' . $value['base'] . '</td>';
			$html .= '  <td align="right">' . $value['interes'] . '</td>';
			//$html.='  <td>'.$value['idpoliza'].'</td>';
			$html .= '  </tr>';
			if ($intCiclo > 50) {
				$html .= '</table> <br> <br>';
				$intCiclo = 0;
			}
		}
		$html .= '</table>';
		return $html;
	}


	public function tableCreatProv($title, $data)
	{
		$html = '';
		$html .= '<table style="width:80%" align="center">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$intCiclo = 0;
		foreach ($data as $key => $value) {
			$intCiclo = $intCiclo + 1;
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['acreditado'] . '</td>';
			$html .= '  <td>' . $value['numero_cuenta'] . '</td>';
			$html .= '  <td align="right">' . $value['base'] . '</td>';
			$html .= '  <td align="right">' . $value['interes'] . '</td>';
			//$html.='  <td>'.$value['idpoliza'].'</td>';
			$html .= '  </tr>';
			if ($intCiclo > 50) {
				break;
			}
		}
		$html .= '</table>';
		return $html;
	}


	public function tableCreateEmisionCheques($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$intPago = 1;
		$saldoCapital = 0;
		$pagoFijo = 0;

		$totalMonto = 0.00;
		$totalGarantia = 0.00;
		$totalImporte = 0;
		$isFIN = false;
		if ($this->session->userdata('esquema') === "fin.") {
			$isFIN = true;
		}
		//$title = array("Fecha","Nombre","S.C.", "Nivel", "Monto", "Garantia", "Pago en exceso", "Importe", "No. cheque", "Grupo", "Colmena", "Promotor", "Observaciones");
		//$fields = array("fecha", "acreditado_nombre", "nivel", "monto", "total_garantia", "cheque_ref", "idgrupo", "colmena_numero", "colmena_grupo", "promotor", "proy_observa");
		foreach ($data as $key => $value) {
			$fecha = date_create($value['fecha']);
			$fecha = date_format($fecha, 'd/m/Y');

			$monto = $value['monto'];
			$garantia = $value['total_garantia'];
			$total = (float)($monto + $garantia + 0.00);

			$totalMonto = $totalMonto + $monto;
			$totalGarantia = $totalGarantia + $garantia;
			$totalImporte = $totalImporte + $total;

			$html .= '  <tr>';
			$html .= '  <td height="12" width="35">' . $fecha . '</td>';
			$html .= '  <td >' . $value['idacreditado'] . ' - ' . $value['acreditado_nombre'] . '</td>';
			if ($isFIN === true) {
				$html .= '  <td width="15" align="right">' . $value['nivel'] . '</td>';
				$html .= '  <td width="33" align="right">' . number_format($monto, 2) . '</td>';
				$html .= '  <td width="33" align="right">' . number_format($garantia, 2) . '</td>';
				$html .= '  <td width="33" align="right">' . number_format($total, 2) . ' </td>';
			} else {
				$html .= '  <td width="10"> </td>';
				$html .= '  <td width="15" align="right">' . $value['nivel'] . '</td>';
				$html .= '  <td width="33" align="right">' . number_format($monto, 2) . '</td>';
				$html .= '  <td width="33" align="right">' . number_format($garantia, 2) . '</td>';
				$html .= '  <td width="30" align="right"> </td>';
				$html .= '  <td width="33" align="right">' . number_format($total, 2) . ' </td>';
				$html .= '  <td width="20" align="right">' . $value['cheque_ref'] . '</td>';
			}
			$html .= '  <td width="20" 1align="right">' . $value['colmena_grupo'] . '</td>';
			$html .= '  <td width="25" align="right">' . $value['colmena_numero'] . '</td>';
			$html .= '  <td width="90">' . $value['promotor'] . '</td>';
			$html .= '  <td >' . $value['proy_observa'] . '</td>';
			$html .= '  </tr>';
			$intPago = $intPago + 1;
		}
		$html .= '  <tr>';
		$html .= '  <td ></td>';
		$html .= '  <td ></td>';
		if ($isFIN === true) {
			$html .= '  <td ></td>';
			$html .= '  <td align="right"><b>' . number_format($totalMonto, 2) . '</b></td>';
			$html .= '  <td align="right"><b>' . number_format($totalGarantia, 2) . '</b></td>';
			$html .= '  <td align="right"><b>' . number_format($totalImporte, 2) . '</b></td>';
		} else {
			$html .= '  <td ></td>';
			$html .= '  <td></td>';
			$html .= '  <td align="right"><b>' . number_format($totalMonto, 2) . '</b></td>';
			$html .= '  <td align="right"><b>' . number_format($totalGarantia, 2) . '</b></td>';
			$html .= '  <td> </td>';
			$html .= '  <td align="right"><b>' . number_format($totalImporte, 2) . '</b></td>';
			$html .= '  <td></td>';
		}
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  </tr>';

		$html .= '</table>';
		return $html;
	}



	public function pdf_colmena_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcolmena = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$valores = $this->put('data') ? $this->put('data', TRUE) : array();

		$dato_colmena = $this->base->querySelect("SELECT l.numero, l.idsucursal, l.nombre, l.fecha_apertura,  p.idacreditado as idpresidente, coalesce(p.acreditado,'') as presidente, t.idacreditado as idtesorero, coalesce(t.acreditado,'') as tesorero, s.idacreditado as idsecretario, coalesce(s.acreditado,'') as secretario
			FROM col.colmenas as l
				JOIN col.colmena_cargo as c ON l.idcolmena=c.idcolmena
				LEFT JOIN public.get_acreditados as p ON c.idpresidente = p.acreditadoid
				LEFT JOIN public.get_acreditados as t ON c.idtesorero = t.acreditadoid
				LEFT JOIN public.get_acreditados as s ON c.idsecretario = s.acreditadoid
			WHERE l.idcolmena=" . $idcolmena, TRUE);
		$dato_colmena = $dato_colmena[0];

		$sucursal = $dbQueries->getSucursal($dato_colmena['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$acreditados = $this->base->querySelect("SELECT grupo_numero, grupo_nombre, idacreditado, nombre, cargo_grupo, orden
			FROM " . $this->esquema . "get_acreditado_grupo 
			WHERE idcolmena=" . $idcolmena . " ORDER BY grupo_numero, orden", TRUE);
		$title = array("Posición", "No. acreditada", "Nombre", "Cargo");
		$tabla = '';
		$tabla .= $this->tableGrupoColmena($title, $acreditados);


		$header =  $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:12px;">
			<h3 align="center">COLMENA: ' . $dato_colmena['numero'] . ' - ' . $dato_colmena['nombre'] . '</h3>
			</div>
			';

		$html .= '
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						Promotor: 
					</th>
					<th></th>
					<th class="seccion-left">
						SUCURSAL: ' . $dato_colmena['idsucursal'] . ' - ' . $sucursal . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Presidenta: ' . $dato_colmena['idpresidente'] . ' - ' . $dato_colmena['presidente'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Tesorera: ' . $dato_colmena['idtesorero'] . ' - ' . $dato_colmena['tesorero'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Secretaria: ' . $dato_colmena['idsecretario'] . ' - ' . $dato_colmena['secretario'] . '
					</th>		
				</tr>	
			</table>';

		$html .= '<br>';
		$html .= '<font size="10px">';
		$html .= '<div>' . $tabla . '</div>';
		$html .= '<font>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}


	public function tableGrupoColmena($title, $data)
	{
		$html = '';
		$table = '<table align="center" style="width:80%">';
		$titulo = '';
		foreach ($title as $key => $value) {
			$titulo .= '    <th>' . $value . '</th>';
		}
		$intGrupo = 0;
		foreach ($data as $key => $value) {
			$intGrupoTable = $value['grupo_numero'];
			if ($intGrupo != $intGrupoTable) {
				if ($html != "") {
					$html .= '</table>
					<br>';
				}
				$html .= $table;
				$html .= '  <tr><td align="center" COLSPAN ="4"><b>' . $value['grupo_nombre'] . '</b></td></tr>';
				$html .= '  <tr>' . $titulo . '</tr>';
				$intGrupo = $intGrupoTable;
			}
			$html .= '  <tr>';
			$html .= '  <td align="right">' . $value['orden'] . '</td>';
			$html .= '  <td align="right">' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['cargo_grupo'] . '</td>';
			$html .= '  </tr>';
		}
		return $html;
	}


	public function html_datos_acre_get()
	{
		//$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idacreditado", "id_isis", "idsucursal", "nombre", "curp", "ine", "persona", "estado_civil", "fecha_nac", "edad", "sexo", "cp", "calle", "localidad", "municipio", "aportacion_social", "actividad", "dependientes", "tipovivienda", "aguapot", "enerelec", "drenaje", "colmena", "grupo", "telefono");
		$order_by = array(array('campo' => 'idacreditado', 'direccion' =>	'asc'));
		$acre_datos = $this->base->selectRecord("get_acreditados_estadistica", $fields, "", "", "", "", "", $order_by, "", "", TRUE);

		$title = array("No", "Isis", "Suc", "Nombre", "CURP", "INE", "Persona", "Edo.Civil", "Fec.Nac.", "Edad", "Sexo", "CP", "Calle", "Localidad", "Municipio", "Ap.Soc.", "Actividad", "Dep.", "Vivienda", "Agua", "Elec.", "Drenaje", "Colmena", "Grupo", "Teléfono");
		$tabla = $this->tableDatosAcre($title, $acre_datos);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">DATOS DE SOCIAS</h3>';

		$html .= '<br><br><div > </div>';
		$html .= '<div style="font-size:9px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';
		$html .= '
		</div>
		</body>
		</html>
		';

		print_r($html);
		die;
	}


	public function tableDatosAcre($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		foreach ($title as $key => $value) {
			$html .= '    <th span 2>' . $value . '</th>';
		}
		$intGrupo = 0;
		foreach ($data as $key => $value) {
			$html .= '  <tr ">';
			$fecha = new DateTime($value['fecha_nac']);
			$miFecha = date_format($fecha, 'd/m/Y');

			$html .= '  <td align="right">' . $value['idacreditado'] . '</td>';
			$html .= '  <td align="right">' . $value['id_isis'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['nombre'] . '</td>';
			$html .= '  <td>' . $value['curp'] . '</td>';
			$html .= '  <td>' . $value['ine'] . '</td>';
			$html .= '  <td>' . $value['persona'] . '</td>';
			$html .= '  <td>' . $value['estado_civil'] . '</td>';
			$html .= '  <td>' . $miFecha . '</td>';
			$html .= '  <td>' . $value['edad'] . '</td>';
			$html .= '  <td>' . $value['sexo'] . '</td>';
			$html .= '  <td>' . $value['cp'] . '</td>';
			$html .= '  <td>' . $value['calle'] . '</td>';
			$html .= '  <td>' . $value['localidad'] . '</td>';
			$html .= '  <td>' . $value['municipio'] . '</td>';
			$html .= '  <td>' . $value['aportacion_social'] . '</td>';
			$html .= '  <td>' . $value['actividad'] . '</td>';
			$html .= '  <td>' . $value['dependientes'] . '</td>';
			$html .= '  <td>' . $value['tipovivienda'] . '</td>';
			$html .= '  <td>' . $value['aguapot'] . '</td>';
			$html .= '  <td>' . $value['enerelec'] . '</td>';
			$html .= '  <td>' . $value['drenaje'] . '</td>';
			$html .= '  <td>' . $value['colmena'] . '</td>';
			$html .= '  <td>' . $value['grupo'] . '</td>';
			$html .= '  <td>' . $value['telefono'] . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';
		return $html;
	}

	public function html_sin_datos_acre_get()
	{
		//$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$fields = array("idacreditado", "id_isis", "idsucursal", "nombre", "persona", "estado_civil", "fecha_nac", "edad", "sexo", "cp", "localidad", "municipio", "aportacion_social", "actividad", "dependientes", "tipovivienda", "aguapot", "enerelec", "drenaje", "colmena", "grupo", "telefono");
		$order_by = array(array('campo' => 'idacreditado', 'direccion' =>	'asc'));
		$acre_datos = $this->base->selectRecord("get_acreditados_estadistica_no", $fields, "", "", "", "", "", $order_by, "", "", TRUE);

		$title = array("No", "Isis", "Suc", "Nombre", "Persona", "Edo.Civil", "Fec.Nac.", "Edad", "Sexo", "CP", "Localidad", "Municipio", "Ap.Soc.", "Actividad", "Dep.", "Vivienda", "Agua", "Elec.", "Drenaje", "Colmena", "Grupo", "Teléfono");
		$tabla = $this->tableDatosAcre($title, $acre_datos);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">DATOS DE SOCIAS</h3>';

		$html .= '<br><br><div > </div>';
		$html .= '<div style="font-size:9px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';
		$html .= '
		</div>
		</body>
		</html>
		';

		print_r($html);
		die;
	}


	public function pdf_colmenas_dir_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$tipo = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$reporte_title = "DIRECTORIO DE COLMENAS";
		if ($this->session->userdata('sucursal_id') === '01') {
			$reporte_suc = "ZIMATLÁN";
		} else {
			$reporte_suc = "OAXACA";
		}

		$valores = $this->put('data') ? $this->put('data', TRUE) : array();

		$title = array("Núm.", "Colmena", "Dia", "Promotor", "Hora", "Fecha apertura", "Fecha cierre", "Empresa", "Municipio", "Colonia", "Direccion", "Referencia", "Presidenta", "Celular", "Secretaria", "Celular");
		if ($tipo === "3") {
			$where = " where idsucursal='" . $this->session->userdata('sucursal_id') . "' and fechacierre is null ";
			$title = array("Núm.", "Colmena", "Dia", "Promotor", "Hora", "Fecha apertura", "Empresa", "Municipio", "Colonia", "Direccion", "Referencia", "Presidenta", "Celular", "Secretaria", "Celular");
			$reporte_title .= " ACTIVAS";
		} else if ($tipo === "4") {
			$where = " where idsucursal='" . $this->session->userdata('sucursal_id') . "' and not fechacierre is null ";
			$reporte_title .= " INACTIVAS";
		} else {
			$where = " where idsucursal='" . $this->session->userdata('sucursal_id') . "' ";
		}
		$idsuc = $this->session->userdata('sucursal_id');

		$sucursal = $dbQueries->getSucursal($idsuc);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$query = "SELECT numero, nombre, dia_text, promotor, horainicio, fecha_apertura::date, fechacierre::date, empresa, d_mnpio, d_asenta, direccion, direccion_ref,presidenta,presi_cell,secretaria,secre_cell from col.v_colmenas_directorio " . $where . " ORDER BY numero";
		$amor = $this->base->querySelect($query, TRUE);

		$tabla = '';
		$tabla .= $this->tableCreateColmenas($title, $amor, $tipo);

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center"> ' . $reporte_title . ' <br> ' . $reporte_suc . ' </h3>
		';


		//$html.='<div > </div>';
		$html .= '<font size="10px">';
		$html .= '<div style="font-size:9px;"> ' . $tabla . ' </div>';
		//$html.='<font size="9px">';

		$html .= htmlEnd();

		print_r($html);
		die;

		renderPDF($html, 'Report');
	}


	public function tableCreateColmenas($title, $data, $tipo)
	{
		/*
		$html='';
		$html.='<table style="width:100%" align="center">';
		$html.='  <tr>';
		foreach($title as $key => $value) {
			$html.='    <th>'.$value.'</th>';
		}
		$html.='  </tr>';
		*/
		$html = '';
		//$salto=25;
		$table = '';
		$table .= '<table style="width:100%" align="center">';
		$table .= '  <tr>';
		foreach ($title as $key => $value) {
			$table .= '    <th>' . $value . '</th>';
		}
		$table .= '  </tr>';
		$intCiclo = 0;

		if ($data) {
			foreach ($data as $key => $value) {
				if ($intCiclo === 0) {
					$html .= $table;
				}
				$intCiclo = $intCiclo + 1;
				$html .= '  <tr>';
				$html .= '  <td align="right">' . $value['numero'] . '</td>';
				$html .= '  <td>' . $value['nombre'] . '</td>';
				$html .= '  <td>' . $value['dia_text'] . '</td>';
				$html .= '  <td>' . $value['promotor'] . '</td>';
				$html .= '  <td>' . $value['horainicio'] . '</td>';
				$html .= '  <td>' . $value['fecha_apertura'] . '</td>';
				if ($tipo != 3) {
					if ($value['fechacierre'] === null) {
						$html .= '  <td> </td>';
					} else {
						$html .= '  <td>' . $value['fechacierre'] . '</td>';
					}
				}
				$html .= '  <td>' . $value['empresa'] . '</td>';
				$html .= '  <td>' . $value['d_mnpio'] . '</td>';
				$html .= '  <td>' . $value['d_asenta'] . '</td>';
				$html .= '  <td>' . $value['direccion'] . '</td>';
				if ($value['direccion_ref'] === null) {
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $value['direccion_ref'] . '</td>';
				}
				if ($value['presidenta'] === null) {
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $value['presidenta'] . '</td>';
				}
				if ($value['presi_cell'] === null) {
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $value['presi_cell'] . '</td>';
				}
				if ($value['secretaria'] === null) {
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $value['secretaria'] . '</td>';
				}
				if ($value['secre_cell'] === null) {
					$html .= '  <td> </td>';
				} else {
					$html .= '  <td>' . $value['secre_cell'] . '</td>';
				}
				$html .= '  </tr>';
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
		$html .= '</table><br><br>';
		return $html;
	}

	public function pdf_creditos_activos_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$tipo = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$reporte_title = "CREDITOS ACTIVOS";
		if ($this->session->userdata('sucursal_id') === '01') {
			$reporte_suc = "ZIMATLÁN";
		} else {
			$reporte_suc = "OAXACA";
		}

		$valores = $this->put('data') ? $this->put('data', TRUE) : array();

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
		$idsuc = $this->session->userdata('sucursal_id');

		$sucursal = $dbQueries->getSucursal($idsuc);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$where = " where idsucursal='" . $this->session->userdata('sucursal_id') . "'";
		if ($tipo === "1") {
			$query = "SELECT col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa from " . $this->session->userdata('esquema') . "v_creditos_vigentes " . $where . " ORDER BY idsucursal, col_numero, grupo_nombre";
		} else if ($tipo === "2") {
			$query = "SELECT col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa from " . $this->session->userdata('esquema') . "v_creditos_vigentes " . $where . " ORDER BY idsucursal, empresa, col_numero, grupo_nombre";
		} else {
			$title = array("Empresa", "Núm.", "Colmena", "Grupo", "Acreditada", "Suc.", "Credito", "Pagare", "Empresa", "Dirección", "Socia", "Teléfono");
			$query = "SELECT sistema, col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa, direccion, idacreditado, celular from public.v_creditos_vigentes ORDER BY sistema, empresa, idsucursal, col_numero, grupo_nombre";
		}

		$amor = $this->base->querySelect($query, TRUE);

		$tabla = '';
		$tabla .= $this->tableCreateCreditoVigente($title, $amor, $tipo);

		$header =  $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center"> ' . $reporte_title . ' <br> ' . $reporte_suc . ' </h3>
		';


		//$html.='<div > </div>';
		$html .= '<font size="11px">';
		$html .= '<div style="font-size:9px;"> ' . $tabla . ' </div>';
		//$html.='<font size="9px">';

		$html .= htmlEnd();

		print_r($html);
		die;

		renderPDF($html, 'Report');
	}

	public function tableCreateCreditoVigente($title, $data, $tipo)
	{
		$html = '';
		//$salto=25;
		$table = '';
		$table .= '<table style="width:100%" align="center">';
		$table .= '  <tr>';
		foreach ($title as $key => $value) {
			$table .= '    <th>' . $value . '</th>';
		}
		$table .= '  </tr>';
		$intCiclo = 0;

		$renglon = '  <tr>';
		foreach ($title as $key => $value) {
			$renglon .= '    <td>&nbsp;</td>';
		}
		$renglon .= '  </tr>';

		$empresa = '';
		$antSis = '';
		$antEmp = '';
		$antCol = '';

		if ($data) {
			foreach ($data as $key => $value) {
				if ($intCiclo === 0) {
					$html .= $table;
				}
				$intCiclo = $intCiclo + 1;
				$empresa = $value['empresa'];
				if ($antEmp === '') {
					$antEmp = $value['empresa'];
				}
				if ($antCol === '') {
					$antCol = $value['col_numero'];
				}
				if ($tipo === "3") {
					if ($antSis === '') {
						$antSis = $value['sistema'];
					}
					if ($antSis <> $value['sistema']) {
						$antSis = $value['sistema'];
						$html .= $renglon;
					}
				}
				if ($tipo === "2") {
					if ($antEmp <> $value['empresa']) {
						$antEmp = $value['empresa'];
						$html .= $renglon;
					}
				}
				if ($tipo === "1") {
					if ($antCol <> $value['col_numero']) {
						$antCol = $value['col_numero'];
						$html .= $renglon;
					}
				}

				$html .= '  <tr>';
				//col_numero, col_nombre, grupo_nombre, nombre, idsucursal, idcredito,  idpagare, empresa
				if ($tipo === "3") {
					$html .= '  <td>' . $value['sistema'] . '</td>';
				}
				$html .= '  <td align="right">' . $value['col_numero'] . '</td>';
				$html .= '  <td>' . $value['col_nombre'] . '</td>';
				$html .= '  <td>' . $value['grupo_nombre'] . '</td>';
				$html .= '  <td>' . $value['nombre'] . '</td>';
				$html .= '  <td>' . $value['idsucursal'] . '</td>';
				$html .= '  <td>' . $value['idcredito'] . '</td>';
				$html .= '  <td>' . $value['idpagare'] . '</td>';
				if ($empresa == 'F') {
					$html .= '  <td>Fincomunidad</td>';
				} else if ($empresa == 'B') {
					$html .= '  <td>Bancomunidad</td>';
				} else {
					$html .= '  <td>AMA</td>';
				}
				if ($tipo === "3") {
					$html .= '  <td>' . $value['direccion'] . '</td>';
					$html .= '  <td>' . $value['idacreditado'] . '</td>';
					$html .= '  <td>' . $value['celular'] . '</td>';
				}

				$html .= '  </tr>';
			}
		}
		$html .= '</table><br><br>';
		return $html;
	}


	public function pdf_credito_prov_val_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idcredito = $this->uri->segment(4);
		$empresa = $this->getEmpresa();

		$idsuc = $this->session->userdata('sucursal_id');

		$sucursal = $dbQueries->getSucursal($idsuc);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fields = array("idsucursal", "acreditadoid", "acreditado", "idcredito", "dia", "pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "int_pag", "iva_pag", "factor", "pago_total_r", "pago_capital_r", "capital_saldo_r", "saldo_actual_r", "interes_r", "iva_r", "int_pag_r", "iva_pag_r", "factor_r");
		$where = array("idcredito" => $idcredito);
		$order_by = array(array('campo' => 'dia', 'direccion' =>	'asc'));
		$amor = $this->base->selectRecord($this->session->userdata('esquema') . "v_provision_credito_valida", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);
		$title = array(
			"Fecha prov.", "Suc.",
			"Capital vigente", "Pago total", "Pago capital", "Int. pag", "IVA pag", "Saldo capital", "Interes diario", "IVA diario",
			"Capital vigente", "Pago total", "Pago capital", "Int. pag", "IVA pag", "Saldo capital", "Interes diario", "IVA diario"
		);

		$tabla = '';
		$tabla .= $this->tableCreatProv_real($title, $amor);
		$header =  $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">PROVISION CREDITO - VALIDAR <BR> CREDITO: ' . $idcredito . ' </h3>';

		$html .= '<font size="9px">';
		$html .= '<div style="font-size:9px;">' . $tabla . '</div>';
		$html .= '<font size="9px">';

		$html .= htmlEnd();

		print_r($html);
		die;

		renderPDF($html, 'Report');
	}


	public function tableCreatProv_real($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%" align="center">';
		$html .= '  <tr>';
		$html .= '  	<th align="center" colspan="2">PROVISION</th>';
		$html .= '  	<th align="center" colspan="8">ALMACENADO EN SISTEMA</th>';
		$html .= '  	<th align="center" colspan="8">NUEVA PROVISION</th>';
		$html .= '  </tr>';

		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$intCiclo = 0;
		foreach ($data as $key => $value) {
			$intCiclo = $intCiclo + 1;
			$html .= '  <tr>';
			$html .= '  <td>' . $value['dia'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';

			//"pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "int_pag", "iva_pag, factor", "pago_total_r", "pago_capital_r", "saldo_capital_r", "saldo_actual_r", "interes_r", "iva_r", "int_pag_r", "iva_pag_r", "factor_r"

			$html .= '  <td align="right">' . number_format($value['capital_saldo'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['pago_total'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['pago_capital'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['int_pag'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva_pag'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo_actual'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2) . '</td>';

			$html .= '  <td align="right">' . number_format($value['capital_saldo_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['pago_total_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['pago_capital_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['int_pag_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva_pag_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['saldo_actual_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_r'], 2) . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva_r'], 2) . '</td>';
			$html .= '  </tr>';
		}
		$html .= '</table>';
		return $html;
	}


	public function pdf_credrecupera_det_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
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

		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$cred = $this->base->querySelect("SELECT c.idcredito, c.idsucursal, c.monto, c.idproducto, c.fecha_entrega, a.idacreditado, a.acreditado
			FROM " . $this->esquema . "creditos as c
				JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and fecha_entrega::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "' ORDER BY c.fecha_entrega", TRUE);
		$title = array("Suc", "F.Entrega", "Credito", "Monto", "ID", "Acreditado");
		$tabla = '';
		if ($cred) {
			$tabla .= $this->table_men_cred($title, $cred);
		}

		if ($this->esquema == 'imp.') {
			$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, p.fecha_pago,  max(p.capital_pag + p.iva_pag + p.interes_pag) as total_pago, sum(p.capital_pag) as capital, sum(p.iva_pag) as iva, sum(p.interes_pag) as interes_n, 0 as interes_m
				FROM " . $this->esquema . "amortizaciones as p
					JOIN " . $this->esquema . "creditos as c ON p.idcredito=c.idcredito
				WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and p.fecha_pago::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "'
				GROUP BY c.idsucursal, p.idcredito, p.fecha_pago
				ORDER BY p.fecha_pago, c.idsucursal", TRUE);
		} else {
			$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, p.fecha_pago,  max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
				FROM " . $this->esquema . "pagos as p
					JOIN " . $this->esquema . "creditos as c ON p.idcredito=c.idcredito
					JOIN " . $this->esquema . "pagos_amor as d ON p.idpago=d.idpago
				WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and p.fecha_pago::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "'
				GROUP BY c.idsucursal, p.idcredito, p.fecha_pago
				ORDER BY p.fecha_pago, c.idsucursal", TRUE);
		}

		$title = array("Fecha", "Suc.", "Credito", "Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		$tabla2 = '';
		if ($recu) {
			$tabla2 .= $this->table_men_recupera($title, $recu);
		}

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE CREDITOS Y RECUPERACIONES <br>
				SUCURSAL: ' . $sucursal . ', DEL ' . date_format($fecha1, 'd/m/Y') . ' AL ' . date_format($fecha2, 'd/m/Y') . '</h3>';

		$html .= '<br><div > CREDITOS OTORGADOS </div>';
		$html .= $tabla;
		$html .= '<br><br><div > RECUPERACIONES </div>';
		$html .= $tabla2;
		$html .= '<br><br>';

		$html .= htmlEnd();

		if ($pantalla == 'p') {
			print_r($html);
			//die();
		} else {
			renderPDF($html, 'Report');
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


	public function table_men_cred($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$capital = 0;
		foreach ($data as $key => $value) {
			$capital = $capital + $value['monto'];
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td>' . $value['fecha_entrega'] . '</td>';
			$html .= '  <td>' . $value['idcredito'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['monto'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . $value['idacreditado'] . '</td>';
			$html .= '  <td>' . $value['acreditado'] . '</td>';
			$html .= '  </tr>';
		}
		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  </tr>';
		$html .= '</table>';
		return $html;
	}

	public function table_men_recupera($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp = 0;
		foreach ($data as $key => $value) {
			$capital += $value['capital'];
			$interes_n += $value['interes_n'];
			$interes_m += $value['interes_m'];
			$iva += $value['iva'];
			$total_temp = $value['capital'] + $value['interes_n'] + $value['interes_m'] + $value['iva'];
			$total_pago += $total_temp;
			//$total_pago += $value['total_pago'];			
			$html .= '  <tr>';
			$html .= '  <td>' . $value['fecha_pago'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['idcredito'], 0, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['capital'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_n'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_m'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($total_temp, 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($interes_n, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($interes_m, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($iva, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($total_pago, 2, '.', ',') . '</td>';
		$html .= '  </tr>';
		$html .= '</table>';
		return $html;
	}


	public function pdf_credrecupera_gl_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$fecha_ini = $this->uri->segment(4);
		$fecha_fin = $this->uri->segment(5);
		$empresa = $this->getEmpresa();

		$fecha1 = new DateTime($fecha_ini);
		$fecha2 = new DateTime($fecha_fin);

		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$cred = $this->base->querySelect("SELECT c.idsucursal, c.fecha_entrega::date, sum(c.monto) as monto
			FROM " . $this->esquema . "creditos as c
				JOIN public.get_acreditados as a ON c.idacreditado=a.acreditadoid
			WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and fecha_entrega::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "' 
			GROUP BY c.idsucursal, c.fecha_entrega::date ORDER BY c.fecha_entrega::date", TRUE);
		$title = array("F.Entrega", "Suc", "Monto");
		$tabla = '';
		if ($cred) {
			$tabla .= $this->table_men_cred_gl($title, $cred);
		}

		if ($this->esquema == 'imp.') {
			$recu = $this->base->querySelect("SELECT c.idsucursal, p.fecha_pago::date,  max(p.capital_pag + p.iva_pag + p.interes_pag) as total_pago, sum(p.capital_pag) as capital, sum(p.iva_pag) as iva, sum(p.interes_pag) as interes_n, 0 as interes_m
				FROM " . $this->esquema . "amortizaciones as p
					JOIN " . $this->esquema . "creditos as c ON p.idcredito=c.idcredito
				WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and p.fecha_pago::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "'
				GROUP BY c.idsucursal, p.fecha_pago::date
				ORDER BY p.fecha_pago::date, c.idsucursal", TRUE);
		} else {
			$recu = $this->base->querySelect("SELECT c.idsucursal, p.fecha_pago::date, max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
				FROM " . $this->esquema . "pagos as p
					JOIN " . $this->esquema . "creditos as c ON p.idcredito=c.idcredito
					JOIN " . $this->esquema . "pagos_amor as d ON p.idpago=d.idpago
				WHERE c.idsucursal='" . $this->session->userdata('sucursal_id') . "' and p.fecha_pago::date BETWEEN '" . $fecha_ini . "' and '" . $fecha_fin . "'
				GROUP BY c.idsucursal, p.fecha_pago::date
				ORDER BY p.fecha_pago::date, c.idsucursal", TRUE);
		}
		$title = array("Fecha", "Suc", "Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		$tabla2 = '';
		if ($recu) {
			$tabla2 .= $this->table_men_recupera_gl($title, $recu);
		}

		$header = $this->headerReport('');
		//$html = $header;

		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">TABLA DE CREDITOS Y RECUPERACIONES <br>
				SUCURSAL: ' . $sucursal . ', DEL ' . date_format($fecha1, 'd/m/Y') . ' AL ' . date_format($fecha2, 'd/m/Y') . '</h3>';

		$html .= '<div><b>CREDITOS OTORGADOS </b>';
		$html .= $tabla;
		$html .= '</div> ';
		$html .= '<br><br><div> <b>RECUPERACIONES </b>';
		$html .= $tabla2;
		$html .= '</div>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');

		//print_r ($html);
		//die;
	}

	public function table_men_cred_gl($title, $data)
	{
		$html = '';
		$html .= '<table style="width:50%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$capital = 0;
		foreach ($data as $key => $value) {
			$capital = $capital + $value['monto'];
			$html .= '  <tr>';
			$html .= '  <td>' . $value['fecha_entrega'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['monto'], 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
		$html .= '  </tr>';
		$html .= '</table>';
		return $html;
	}

	public function table_men_recupera_gl($title, $data)
	{
		/*$recu = $this->base->querySelect("SELECT p.idcredito, c.idsucursal, max(p.total_pago) as total_pago, sum(d.capital) as capital, sum(d.iva) as iva, sum(d.interes_n) as interes_n, sum(d.interes_m) as interes_m
		$title = array("Suc.", "Capital", "Int.Nor", "Int.Mora", "IVA", "Total");
		*/
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp = 0;
		foreach ($data as $key => $value) {
			$capital += $value['capital'];
			$interes_n += $value['interes_n'];
			$interes_m += $value['interes_m'];
			$iva += $value['iva'];
			$total_temp = $value['capital'] + $value['interes_n'] + $value['interes_m'] + $value['iva'];
			$total_pago += $total_temp;
			$html .= '  <tr>';
			$html .= '  <td>' . $value['fecha_pago'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td align="right">' . number_format($value['capital'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_n'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['interes_m'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($value['iva'], 2, '.', ',') . '</td>';
			$html .= '  <td align="right">' . number_format($total_temp, 2, '.', ',') . '</td>';
			$html .= '  </tr>';
		}
		$html .= '  <tr>';
		$html .= '  <td></td>';
		$html .= '  <td></td>';
		$html .= '  <td align="right">' . number_format($capital, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($interes_n, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($interes_m, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($iva, 2, '.', ',') . '</td>';
		$html .= '  <td align="right">' . number_format($total_pago, 2, '.', ',') . '</td>';
		$html .= '  </tr>';
		$html .= '</table>';
		return $html;
	}

	public function pdf_provconfig_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idprovcnf = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$fields = array("idprovcnf", "idcredito", "fecha_ini", "fecha_fin", "nota", "fecha_aprov", "usuario_aprov", "usuario", "fecha_mov", "nombre", "idsucursal", "idpagare", "idacreditado", "nivel");
		$where = array("idprovcnf" => $idprovcnf);
		$prov = $this->base->selectRecord($this->esquema . "v_prov_config", $fields, "", $where, "", "", "", "", "", "", TRUE);
		//print_r ($prov);
		//die;
		$prov = $prov[0];

		$pagare = $prov['idpagare'];

		$sucursal = $dbQueries->getSucursal($prov['idsucursal']);
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$fecha_ini = new DateTime($prov['fecha_ini']);
		$fecha_fin = new DateTime($prov['fecha_fin']);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">CANCELACIÓN DE PROVISIONES</h3>';

		if ($prov['fecha_ini'] === $prov['fecha_fin']) {
			$periodo_prov = 'PERIODO A PARTIR DEL ' . date_format($fecha_ini, 'd/m/Y');
		} else {
			$periodo_prov = 'PERIODO DEL ' . date_format($fecha_ini, 'd/m/Y') . ' AL ' . date_format($fecha_fin, 'd/m/Y');
		}
		$html .=  '<h3 align="center">' . $periodo_prov . '</h3>';

		$html .= '<br>
			<table style="width:100%" border="0" class="100p">
				<tr>
					<th class="seccion-left">
						SUCURSAL: ' . $prov['idsucursal'] . ' - ' . $sucursal . '
					</th>
					<th></th>
					<th class="seccion-left">
						Credito: ' . $prov['idcredito'] . '
					</th>
				</tr>
				<tr>
					<th class="seccion-left">
						Socio: ' . $prov['idacreditado'] . ' ' . $prov['nombre'] . '
					</th>		
					</th>		
					<th>
					<th class="seccion-left">
						Nivel: ' . $prov['nivel'] . '
					</th>			
				</tr>													
			</table>';
		//$html.='<br><br><div > </div>';

		$html .= '<br>
			<p>Solicitud de autorización para cancelar por el ' . $periodo_prov . '
			la EMISIÓN DE LA PROVISION por el siguiente motivo:</p>';
		$html .= '
			<p>' . $prov['nota'] . '<br><br><br><br></p>';

		$html .= '<table style="width:100%" border=0>
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
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}

	public function pdf_provconfiglst1_get()
	{
		$idprovcnf = $this->uri->segment(4);
		$empresa = $this->getEmpresa();
		$fields = array("idprovcnf", "idsucursal", "idcredito", "idpagare", "nombre", "nota", "fecha_ini", "fecha_fin");
		$order_by = array(array('campo' => 'idprovcnf', 'direccion' =>	'asc'));
		$prov = $this->base->selectRecord($this->esquema . "v_prov_config", $fields, "", "", "", "", "", $order_by, "", "", TRUE);

		$title = array("Folio", "Suc", "Credito", "Pagare", "Socia", "Nota", "F. inicial", "F. final");
		$tabla2 = '';
		if ($prov) {
			$tabla2 .= $this->table_provconfig($title, $prov);
		}

		$fecha_ini = new DateTime($prov['fecha_ini']);
		$fecha_fin = new DateTime($prov['fecha_fin']);

		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">LISTADO DE CANCELACIÓN DE PROVISIONES</h3>';
		$html .= '<br>';
		$html .= $tabla2;


		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report');
	}

	public function table_provconfig($title, $data)
	{
		$html = '';
		$html .= '<table style="width:100%">';
		$html .= '  <tr>';
		foreach ($title as $key => $value) {
			$html .= '    <th>' . $value . '</th>';
		}
		$html .= '  </tr>';
		$capital = 0;
		$interes_n = 0;
		$interes_m = 0;
		$iva = 0;
		$total_pago = 0;
		$total_temp = 0;
		foreach ($data as $key => $value) {
			$html .= '  <tr>';
			$html .= '  <td>' . $value['idprovcnf'] . '</td>';
			$html .= '  <td>' . $value['idsucursal'] . '</td>';
			$html .= '  <td align="right">' . $value['idcredito'] . '</td>';
			$html .= '  <td">' . $value['idpagare'] . '</td>';
			$html .= '  <td">' . $value['nombre'] . '</td>';
			$html .= '  <td">' . $value['nota'] . '</td>';
			$html .= '  <td">' . $value['fecha_ini'] . '</td>';
			$html .= '  <td">' . $value['fecha_fin'] . '</td>';
			$html .= '  </tr>';
		}

		$html .= '</table>';
		return $html;
	}

	// REFACTORIZACIÓN HORARIO DE ACUERDO A EMPRESA
	public function pdf_col_horario_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idPromotor = $this->uri->segment(4);

		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursalNombre = mb_strtoupper($sucursal['nombre']);

		$empresa = getEmpresaCode($this->session->userdata('esquema'));

		$colmenaHorarioData = $dbQueries->getColmenaHorario($sucursal['idsucursal'], $empresa, $idPromotor);
		if ($colmenaHorarioData) {
			$tabla = table_col_horario($colmenaHorarioData, '0');
		}

		$head = generateHead(
			'Horario de Colmena',
			'
			table {
				font-size: 9px;
			}
			th {
				font-size: 11px;
			}
			td {
				padding: 3px 5px;
			}
			'
		);
		$html = $head;
		$html .= generateSimpleHeader(getEmpresa($this->session->userdata('esquema')));
		$html .= '<h3 align="center">HORARIO SUCURSAL: ' . $sucursalNombre . ' </h3>';
		$html .= $tabla;
		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	public function pdf_col_horario2_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		// Obtiene el promotor
		$idPromotor = $this->uri->segment(4);

		// Obtiene el nombre de la sucursal
		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

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
		$header = addLogoAndSubtitle($header, 'Horario - ' . $sucursal);
		$header = generateReportHeader('Horario - ' . $sucursal, $this->getEmpresa() . '<hr>HORARIO SUCURSAL: ' . $sucursal, '', '', '14px');

		$html = $header . '<div style="font-size:10px;">';
		$html .= $tabla;

		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	// TODO_ refactorización
	public function pdf_col_horariog_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$idPromotor = $this->uri->segment(4);

		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('" . $this->session->userdata('sucursal_id') . "'," . $idPromotor . ") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora) {
			$tabla .= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: ' . $sucursal . ' </h3>';
		$html .= '<div style="font-size:8px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	public function pdf_col_horariog2_get()
	{
		$idPromotor = $this->uri->segment(4);
		$sucursal = 'ZIMATLAN';
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('01'," . $idPromotor . ") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora) {
			$tabla .= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: ' . $sucursal . ' </h3>';
		$html .= '<div style="font-size:8px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';
		$sucursal = 'OAXACA';
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horariog('02'," . $idPromotor . ") WHERE numero>1;", TRUE);
		if ($hora) {
			$tabla = $this->table_col_horario($title, $hora, '1');

			$html .= '<div class="page_break"></div>' . $header . '
				<div style="font-size:11px;">
				<h3 align="center">HORARIO SUCURSAL: ' . $sucursal . ' </h3>';
			$html .= '<div style="font-size:8px;">';
			$html .= $tabla;
			$html .= '</div>';
			$html .= '<br><br>';
		}

		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	public function pdf_col_horariocap_get()
	{
		$idPromotor = $this->uri->segment(4);
		$sucursal = 'ZIMATLÁN';
		if ($this->session->userdata('sucursal_id') === '02') {
			$sucursal = 'OAXACA';
		}
		if ($this->session->userdata('esquema') === "fin.") {
			$empresa = 'F';
		} elseif ($this->session->userdata('esquema') === "ban.") {
			$empresa = 'B';
		} elseif ($this->session->userdata('esquema') === "imp.") {
			$empresa = 'I';
		} else {
			$empresa = '';
		}
		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario_cap('" . $this->session->userdata('sucursal_id') . "', '" . $empresa . "'," . $idPromotor . ") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora) {
			$tabla .= $this->table_col_horario($title, $hora, '0');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: ' . $sucursal . ' </h3>';
		$html .= '<div style="font-size:8px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	public function pdf_col_horariocapg_get()
	{
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);

		$idPromotor = $this->uri->segment(4);
		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$sucursal = $dbQueries->getSucursal($this->session->userdata('sucursal_id'));
		$sucursal = mb_strtoupper($sucursal['nombre']);

		$hora = $this->base->querySelect("SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
											FROM col.get_colmena_horario_capg('" . $this->session->userdata('sucursal_id') . "'," . $idPromotor . ") WHERE numero>1;", TRUE);
		$title = array("Ruta", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
		$tabla = '';
		if ($hora) {
			$tabla .= $this->table_col_horario($title, $hora, '1');
		}
		$header = $this->headerReport('');
		$html = $header;
		$html = $header . '
			<div style="font-size:11px;">
			<h3 align="center">HORARIO SUCURSAL: ' . $sucursal . ' </h3>';
		$html .= '<div style="font-size:8px;">';
		$html .= $tabla;
		$html .= '</div>';
		$html .= '<br><br>';

		$html .= htmlEnd();

		renderPDF($html, 'Report', true, 'letter', 'landscape');
	}

	public function table_col_horario($title, $data, $global)
	{
		if ($global === '1') {
			$columnas = 4;
		} else {
			$columnas = 3;
		}
		$html = '';
		$html .= '<table style="width:100%" >';
		$html .= '  <tr style="height:20px; background:lightblue;">';
		$html .= '    <th span=2>RUTA</th>';
		$html .= '    <th colspan="' . $columnas . '" align="center">LUNES</th>';
		$html .= '    <th colspan="' . $columnas . '" align="center">MARTES</th>';
		$html .= '    <th colspan="' . $columnas . '" align="center">MIERCOLES</th>';
		$html .= '    <th colspan="' . $columnas . '" align="center">JUEVES</th>';
		$html .= '    <th colspan="' . $columnas . '" align="center">VIERNES</th>';
		$html .= '  </tr>';
		$capital = 0;
		$numero = 0;
		$fila = '';
		foreach ($data as $key => $value) {
			$html .= '  <tr">';
			if ($numero != $value['numero']) {
				$numero = $value['numero'];
				$html .= '  <td style="border-bottom:0px;"><b>' . $value['nombre'] . '</b></td>';
				$fila = '1';
			} else {
				$html .= '  <td style="border-bottom:0px; border-top:0px;"></td>';
				$fila = '';
			}
			$html .= $this->col_horario_dia($value['lunes'], $fila, $global);
			$html .= $this->col_horario_dia($value['martes'], $fila, $global);
			$html .= $this->col_horario_dia($value['miercoles'], $fila, $global);
			$html .= $this->col_horario_dia($value['jueves'], $fila, $global);
			$html .= $this->col_horario_dia($value['viernes'], $fila, $global);
			$html .= '  </tr>';
		}
		$html .= '</table>';
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



	private function col_horario_dia($data, $fila, $global)
	{
		$miHtml = '';
		$border = '';
		if ($fila === '') {
			$border = "border-bottom:0px; border-top:0px;";
		} else {
			$border = "border-bottom:0px;";
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
				$miHtml .= '  <td align="right" style="width:15px; border-right:0px;' . $border . '" >' . $parts[0] . '</td>';
			}
			$miHtml .= '  <td style="border-left:0px; border-right:0px; ' . $border . '">' . $nombre . '</td>';
			$miHtml .= '  <td style="width:15px; border-left:0px;' . $border . '">' . $parts[2] . '</td>';
		} else {
			if ($global == '1') {
				$miHtml .= '  <td style="width:5px; border-right:0px;' . $border . '"></td>';
				$miHtml .= '  <td style="width:15px; border-left:0px; border-right:0px;' . $border . '"></td>';
			} else {
				$miHtml .= '  <td style="width:15px; border-right:0px;' . $border . '"></td>';
			}
			$miHtml .= '  <td style="border-left:0px; border-right:0px; ' . $border . '"></td>';
			$miHtml .= '  <td style="width:15px; border-left:0px; ' . $border . '"></td>';
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

	// REFACTORIZACION 
	public function pdf_col_ficha_get()
	{
		$idcolmena = $this->uri->segment(4);
		$fecha = $this->uri->segment(5);
		$fechaReunion = date_create($fecha);
		$dia = date_format($fechaReunion, 'w');

		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$esquema = $this->session->userdata('esquema');

		$empresa = getEmpresaCode($esquema);

		$head = generateHead(
			'Fichas de Colmena',
			'					
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
			th.cellcolor { 
				background-color: GhostWhite 
			}
			th.cell0 { 
				font-weight: normal; border: 0px; padding:0px;
			}
			th.cell1 { 
				font-weight: normal; border-bottom: 1px solid; border-right:0px; border-left:0px; border-top:0px; text-align:left;
			}
			.page_break {
				page-break-before: always;
			}
		'
		);
		$html = $head;
		$html .= generateSimpleHeader();

		$fichas = $dbQueries->getColmenasToVisit($idcolmena, $dia);

		$intSalto = 0;
		foreach ($fichas as $value) {
			$htmlFicha = $this->pdf_col_ficha_grupo_get($value['idcolmena'], $value['numerogrupo'], $fechaReunion, $empresa, $intSalto, $esquema);
			$intSalto = ($intSalto === 0) ? 1 : $intSalto;
			$html .= $htmlFicha;
		}

		$html .= htmlEnd();

		renderPDF($html, 'Fichas de colmena');
	}

	// REFACTORIZACIÓN 
	function pdf_col_ficha_grupo_get($idcolmena, $grupo, $fecha, $empresa, $salto, $esquema)
	{
		$col = $this->base->querySelect("SELECT numero, nombre, promotor, empresa, dia_text, horainicio, substring(empresa,1,1) as empfolio FROM col.v_colmenas_directorio WHERE idcolmena=" . $idcolmena . ";", TRUE);
		$col = $col[0];
		$horaInicioFormateada = date('H:i', strtotime($col['horainicio']));
		$hoy = new DateTime();
		$folio = $col['empfolio'] . '-' . $col['numero'] . '-' . $grupo . '-' . date_format($hoy, "Ymd");

		$tesorera = '';
		$cargo = $this->base->querySelect("SELECT p2.acreditado AS grupo_tesorera
			FROM col.grupos as g 
				JOIN col.grupo_cargo as gc ON g.idgrupo=gc.idgrupo
				LEFT JOIN get_acreditados p2 ON gc.idcargo2 = p2.acreditadoid
			WHERE g.idcolmena=" . $idcolmena . " and g.numero=" . $grupo, TRUE);
		if ($cargo) {
			$cargo = $cargo[0];
			$tesorera = $cargo['grupo_tesorera'];
		}

		$htmlFicha =
			'<div style="font-size:11px;">';
		$htmlFicha .= '<br>
			<div align="right" style="font-size:16px; color:red;"><b>' . $folio . '&nbsp; </b></div>';
		$htmlFicha .= '
			<div align="center" style="font-size:16px;"><b>FICHA DE TESORERA PARA VENTANILLA </b></div>';
		$htmlFicha .= '<br>
			<table style="width:100%; font-size: 13px" border="0">
				<tr>
					<th class="cell1" style="width:20%;">Semana:</th>
					<th class="cell1" style="width:45%;">' . date_format($fecha, "W") . '</th>
					<th class="cell1" style="width:10%;">Fecha:</th>
					<th class="cell1" style="width:25%;">' . date_format($fecha, "d-m-Y") . '</th>
				</tr>
				<tr >
					<th class="cell1" style="width:20%;">Colmena:</th>
					<th class="cell1" style="width:45%;">' . $col['nombre'] . '</th>
					<th class="cell1" style="width:10%;">Número:</th>
					<th class="cell1" style="width:25%;">' . $col['numero'] . '</th>
				</tr>
				<tr>
					<th class="cell1" style="width:20%;">Día y hora de reunión:</th>
					<th class="cell1" style="width:45%;">' . $col['dia_text'] . ', ' . $horaInicioFormateada . '</th>
					<th class="cell1" style="width:10%;">Grupo:</th>
					<th class="cell1" style="width:25%;">' . $grupo . '</th>
				</tr>				
			</table>';
		$htmlFicha .= '<br>
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
			WHERE idcolmena=" . $idcolmena . " and col_grupo=" . $grupo . " ORDER BY col_numero, col_grupo, orden) as p
			RIGHT join (SELECT generate_series(1,5)as orden) as q ON p.orden=q.orden
			LEFT JOIN (SELECT c.idacreditado, c.idcredito, c.num_pagos, min(nivel) as nivel, 
				(CASE WHEN COALESCE(min(a.numero))>1 THEN min(a.numero)-1 ELSE NULL END) as pago_actual
						FROM public.creditos_g as c
							JOIN public.amortizaciones_g as a ON c.idcredito=a.idcredito and c.empresa=a.empresa
						WHERE a.fecha_pago is null
						GROUP BY c.idacreditado, c.idcredito, c.num_pagos
						) as g ON p.acreditadoid=g.idacreditado
		ORDER BY q.orden", TRUE);

		foreach ($grupo as $key => $value) {
			$fila = '<tr style="width:100%; font-size: 13px;">';
			$fila .= '	<td style="height:30px">' . $value['orden'] . '</td>';
			if ($value['col_numero'] === 0) {
				$fila .= '	<td></td>';
				$fila .= '	<td></td>';
			} else {
				$fila .= '	<td align="center">' . $value['idacreditado'] . '</td>';
				$fila .= '	<td align="left">' . $value['nombre'] . '</td>';
			}
			if ($value['pago_actual']) {
				$fila .= '	<td align="center">' . $value['pago_actual'] . '/' . $value['num_pagos'] . '</td>';
			} else {
				$fila .= '	<td ></td>';
			}
			if ($value['nivel']) {
				$fila .= '	<td align="center">' . $value['nivel'] . '</td>';
			} else {
				$fila .= '	<td ></td>';
			}
			$fila .= '	<td ></td>';
			$fila .= '	<td ></td>';
			$fila .= '	<td ></td>';
			$fila .= '</tr>';
			$htmlFicha .= $fila;
		}

		$htmlFicha .= '
		<tr>
			<th class="cellcolor" colspan="5" align="right" style="padding-right: 15px; font-size:12px">TOTAL</th>
			<th class="cellcolor" style="width:10px; height:30px;"></th>
			<th class="cellcolor" ></th>
			<th class="cellcolor" ></th>
		</tr>';
		$htmlFicha .= '</table>';

		$htmlFicha .= '<br>';

		$htmlFicha .= '
		<table  class="round2" style="width:100%; height:2cm; font-size: 12px;">
			<tr style="width:50%; height:1cm;">
				<th class="cell0" align="center">Tesorera de Grupo</th>
				<th class="cell0" align="center">Promotor (a) </th>
			</tr>
			<tr style="width:50%; height:1cm;">
				<th height="25px" class="cell0" align="left" style="padding-left: 40px;">' . $tesorera . '</td>
				<th class="cell0" align="left">' . mb_strtoupper($col['promotor'], 'UTF-8') . '</td>
			</tr>
		</table>';

		if ($salto === 0) {
			$html = $htmlFicha . '</div><br><br><div>' . $htmlFicha;
		} else {
			$html = '<div class="page_break"></div>' . $htmlFicha . '</div><br><br><div>' . $htmlFicha;
		}

		return $html;
	}

// CRÉDITOS ACTIVOS POR SUCURSALES
	/**
	 * Genera un informe en formato PDF con los créditos activos de la sucursal en la que se escuentre seleccionada.
	 * 
	 * @return void
	 */
	public function pdf_credactivos_get()
{
    try {
        $dbQueries = new DatabaseQueries($this->base, $this->esquema);
        $esquema = $this->session->userdata('esquema');

        $idNivel = $this->uri->segment(4);

        if ($idNivel > 0) {
            $title = 'NIVEL ' . $idNivel;
        } else {
            $title = 'TODOS LOS NIVELES';
        }

        $idsucursal = $this->session->userdata('sucursal_id');
        $sucursal = $dbQueries->getSucursal($idsucursal);
        $sucursalNombre = mb_strtoupper($sucursal['nombre']);

        $creditosActivosData = $dbQueries->getCreditosActivos($idNivel, $idsucursal);

        // Definir títulos de columnas dependiendo del esquema
		if ($this->esquema == 'ama.') {
            $headers = array("No.", "Suc.", "Crédito", "Fecha Entrega", "Socia", "Nivel", "Monto", "No. Pagos", "Periodo", "Grupo", "Colmena", "Promotor");
        } else {
            $headers = array("No.", "Suc.", "Crédito", "Fecha Entrega", "Socia", "Nivel", "Monto", "No. Pagos", "Grupo", "Colmena", "Promotor");
        }

        // Llamamos a la función para generar la tabla HTML
        $tabla = generateCreditosActivosTable(
            $headers,
            $creditosActivosData,
            $this->esquema
        );

        // Generamos el HTML con el diseño proporcionado
        $html = generateHead(
            'Créditos Activos',
            '
            table {
                font-size: 12px;
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
                margin: 20px 0;
            }
            th, td {
                padding: 4px; /* Reduced padding */
            }
            th {
                text-align: center;
                height: 15px;
            }
            td {
                text-align: center;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            .text-left {
                text-align: left;
                padding-left: 6px; 
                padding-right: 6px; 
            }
            '
        );

        $html .= generateSimpleHeader(getEmpresa($this->esquema));
        $html .= "<h3 align='center'>REPORTE GLOBAL DE CRÉDITOS ACTIVOS $title</h3>";
		$html .= "<h3 align='center'>Sucursal $idsucursal</h3>";
        $html .= $tabla;
        $html .= htmlEnd();

        // Imprimir HTML y detener la ejecución
        print_r($html);
        die();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

		
	
	/**
	 * Genera un informe en formato PDF con los niveles de crédito.
	 *  
	 * @return void
	 */
	public function pdf_col_nivel_get()
	{
		// Obtener datos de los niveles de crédito
		$dbQueries = new DatabaseQueries($this->base, $this->esquema);
		$nivelesData = $dbQueries->getNivelesData();

		// Título de la tabla
		$title = array("Nivel", "No. Pagos", "Crédito", "Capital", "Interés x pago", "Capital + interés", "Garantía", "Pago semanal", "Total garantía");

		// Generar tabla de niveles si hay datos disponibles
		$nivelesTabla = '';
		if ($nivelesData) {
			$nivelesTabla = generateNivelesTable($title, $nivelesData);
		}

		// Generación de contenido HTML
		$head = generateHead(
			'Niveles de Crédito',
			'
		table {
			font-size: 10px;
		}
		th {
			padding: 3px 5px;
			height: 25px;
		}
		'
		);
		$html = $head;
		$html .= generateSimpleHeader('Niveles de Colmena');
		$html .= $nivelesTabla;
		$html .= htmlEnd();

		// Generar PDF
		renderPDF($html, 'Niveles de Crédito.pdf', false);
	}
}
