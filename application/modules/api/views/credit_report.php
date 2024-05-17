<?php
include_once 'application/modules/api/controllers/Funciones.php';

// HTML para generación de reporte de Solicitud de Crédito
/**
 * Genera la sección de datos generales de una solicitud de crédito.
 *
 * @param DateTime|string $fecha La fecha de solicitud.
 * @param array $cred Los datos del crédito.
 * @param array $persona Los datos de la persona.
 * @param int $edad La edad de la persona.
 * @param string $tipoVivienda El tipo de vivienda.
 * @param array $colmenaData Los datos de la colmena.
 * @param bool $useCustomFormat Indica si se utiliza un formato personalizado.
 * @return string El HTML generado.
 */
function generateSolicitudDatosGenerales($fecha, $cred, $persona, $edad, $tipoVivienda, $colmenaData, $useCustomFormat = false)
{
    $html = '<h2 align="center">SOLICITUD DE CRÉDITO</h2>';

    if ($useCustomFormat) {
        $html .= generateCustomTable();
    } else {
        $html .= '<p align="right">Fecha de solicitud: <strong>' . date_format($fecha, 'd/m/Y') . '</strong></p>';
    }

    $html .= '<h3 style="margin: 15px 0 10px 0;">1. DATOS GENERALES DE LA SOLICITANTE</h3>';

    if ($useCustomFormat) {
        $html .= generateCustomDataTable($cred, $persona, $edad, $colmenaData);
    } else {
        $html .= generateDefaultDataTable($cred, $persona, $edad, $tipoVivienda, $colmenaData);
    }

    return $html;
}

/**
 * Genera una tabla personalizada para la fecha en caso de que sea sin fecha de solicitud.
 *
 * @return string El HTML generado.
 */
function generateCustomTable()
{
    return '
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
        <div style="height: 40px"></div>';
}

/**
 * Genera una tabla con los datos generales de la solicitante.
 *
 * @param array $cred Los datos del crédito.
 * @param array $persona Los datos de la persona.
 * @param int $edad La edad de la persona.
 * @param string $tipoVivienda El tipo de vivienda.
 * @param array $colmenaData Los datos de la colmena.
 * @return string El HTML generado.
 */
function generateDefaultDataTable($cred, $persona, $edad, $tipoVivienda, $colmenaData)
{
    return '
        <table style="width:100%; margin: 0 0 25px 0;">
            <tr>
                <td colspan="3" style="padding: 8px;">Nombre completo: <strong>' . $cred['nombre'] . '</strong></td>
                <td style="padding: 8px;">No. Socia: <strong>' . $cred['idacreditado'] . '<strong></td>
            </tr>
            <tr>
                    <td colspan="2" style="padding: 8px;">CURP: <strong>' . $persona['curp'] . ' </strong></td>
                    <td colspan="1" style="padding: 8px;">RFC: <strong>' . $persona['rfc'] . ' </strong></td>
                    <td colspan="1" style="padding: 8px;">Edad: <strong>' . $edad . ' años</strong></td>
                </tr>						
                <tr>
                    <td colspan="2" style="padding: 8px;">Estado civil: <strong>' . $cred['edocivil_nombre'] . ' </strong></td>
                    <td colspan="1" style="padding: 8px;">Teléfono: <strong>' . $persona['celular'] . '</strong></td>
                    <td colspan="1" style="padding: 8px;">Vig. INE: <strong>' . $persona['vine'] . '</strong></td>
                </tr>												
                <tr>
                    <td colspan="4" style="padding: 8px;">Domicilio: <strong>' . $cred['direccion'] . '</strong></td>
                </tr>
                <tr>
                    <td colspan="1" style="padding: 8px;">Tipo de residencia: <strong>' . $tipoVivienda . '</strong></td>
                    <td colspan="3" style="padding: 8px;">Referencia: <strong>' . mb_strtoupper($persona['direccion2']) . '</strong></td>
                </tr>						
                <tr>
                    <td colspan="1" style="padding: 8px;">No. Colmena: <strong>' . $colmenaData['colmena_numero'] . '</strong></td>
                    <td colspan="2" style="padding: 8px;">Nombre: <strong>' . $colmenaData['colmena_nombre'] . '</strong></td>
                    <td colspan="1" style="padding: 8px;">Grupo: <strong>' . $colmenaData['colmena_grupo'] . '</strong></td>
                </tr>		
        </table>';
}

/**
 * Genera una tabla de datos para el formato de la solicitud vacía.
 *
 * @param array $cred Los datos del crédito.
 * @param array $persona Los datos de la persona.
 * @param int $edad La edad de la persona.
 * @param array $colmenaData Los datos de la colmena.
 * @return string El HTML generado.
 */
function generateCustomDataTable($cred, $persona, $edad, $colmenaData)
{
    return '
        <table style="width:100%;">
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
					<td colspan="1" style="padding: 8px;">No. Colmena: <strong>' . $colmenaData['colmena_numero'] . '</strong></td>
					<td colspan="2" style="padding: 8px;">Nombre: <strong>' . $colmenaData['colmena_nombre'] . '</strong></td>
					<td colspan="1" style="padding: 8px;">Grupo: <strong>' . $colmenaData['colmena_grupo'] . '</strong></td>
				</tr>	
        </table>';
}

/**
 * Genera la sección de datos de actividad de una solicitud de crédito ya sea individual o de colmena.
 *
 * @param array $cred Los datos del crédito.
 * @param string $montoLetra El monto del crédito en letra.
 * @param string $periodo El periodo de pago.
 * @param bool $useCustomFormat Indica si se utiliza un formato personalizado.
 * @return string El HTML generado.
 */
function generateSolicitudDatosActividad($cred, $montoLetra, $periodo = '', $useCustomFormat = false)
{
    $montoGeneral = '$' . number_format($cred['monto'], 2, '.', ',') . ' (' . $montoLetra . ')';
    if ($useCustomFormat) {
        $cred['proy_nombre'] = '';
        $montoGeneral = '';
        $cred['num_pagos'] = '_______';
        $periodo = '';
        $cred['proy_lugar'] = '';
        $cred['proy_descri'] = '';
    }

    $html = '<h3 style="margin: 25px 0 10px 0;">2. DATOS DE LA ACTIVIDAD PRODUCTIVA</h3>';
    $html .= '
    <table style="width:100%; margin: 15px 0;">
        <tr>
            <td style="padding: 8px;" colspan="4">Principal fuente de ingresos: <strong>' . $cred['actividad_nombre'] . '</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px;" colspan="4">Propósito del crédito: <strong>' . mb_strtoupper($cred['proy_nombre']) . '</strong></td>
        </tr>';

    if ($cred['idproducto'] == '1') {
        // Ajusta la tercera fila según el tipo de crédito -- Colmena
        $html .= '
            <tr>
                <td style="padding: 8px;" colspan="3">Monto solicitado: <strong>' . $montoGeneral . '</strong></td>
                <td style="padding: 8px;" colspan="1">Plazo: <strong>' . $cred['num_pagos'] . ' semanas</strong></td>
            </tr>
        ';
    } elseif ($cred['idproducto'] == '10') {
        // Ajusta la tercera fila según el tipo de crédito -- Individual
        $html .= '
            <tr>
                <td style="padding: 8px;" colspan="2">Monto solicitado: <strong>' . $montoGeneral  . '</strong></td>
                <td style="padding: 8px;" colspan="1">Periodo: <strong>' . $periodo . '</strong></td>
                <td style="padding: 8px;" colspan="1">Número de pagos: <strong>' . $cred['num_pagos'] . '</strong></td>
            </tr>';
    }
    $html .= '			
        <tr>
            <td style="padding: 8px;" colspan="4">Lugar donde se realizará la actividad: <strong>' . mb_strtoupper($cred['proy_lugar']) . '</strong></td>
        </tr>												
        <tr>
            <td style="padding: 8px;" colspan="4" align="center">Descripción del uso del crédito:</td>
        </tr>						
        <tr> 
            <td style="padding: 8px;" colspan="4"><strong>' . mb_strtoupper($cred['proy_descri']) . '</strong><br><br>&nbsp;</td> 
        </tr>											
    </table>
    ';

    return $html;
}

/**
 * Genera la declaración de veracidad para una solicitud de crédito.
 *
 * @return string El HTML generado.
 */
function generateDeclaracionVeracidad()
{
    $html = '
    <p style="font-size: 11px">
        Declaro bajo protesta de decir la verdad que la información aquí asentada es cierta y que el origen de los fondos en los productos y servicios depositados, proceden de fuentes lícitas, así mismo conozco que el permitir a un tercero el uso del crédito sin haberlo declarado, u ocultando o falseando información, o actuando como prestanombres de un tercero, puede dar lugar a que hagan uso indebido del crédito, lo que a su vez podría llegar a constituir la comisión de un delito.
        <br>
        Autorizo compartir mi expediente de identificación, así como demás documentación e información financiera, comercial, operativa, de historial o información crediticia y de cualquier otra naturaleza que le sea proporcionada por mi o por terceros con mi autorización a cualquiera de las entidades asociadas.
        <br>
        Con la firma de esta Solicitud expreso mi conocimiento y conformidad con lo estipulado en las declaraciones y cláusulas del contrato integrado a este documento.
    </p>
    ';

    return $html;
}

// Generación de HTML para pagaré
/**
 * Genera el encabezado de un pagaré en formato HTML.
 *
 * @param string $esquema El esquema de la sucursal.
 * @param string $lugarFecha El lugar y la fecha del pagaré.
 * @param string $pagare El número de pagaré.
 * @param array $sucursal Los datos de la sucursal.
 * @return string El encabezado del pagaré en formato HTML.
 */
function generatePagareHeaderHtml($esquema, $lugarFecha, $pagare, $sucursal)
{
    // Título del pagaré
    $html = '<h3 align="center" class="pagare-titulo">PAGARÉ</h3>';

    // Verifica el esquema de la sucursal
    if ($esquema == "ban.") {
        $html .= generatePagareHeaderTable($lugarFecha, $pagare);
    } else {
        $html .= generatePagareHeaderTableWithSucursal($lugarFecha, $pagare, $sucursal);
    }

    return $html;
}

/**
 * Genera la tabla del encabezado del pagaré cuando el esquema de la sucursal es "ban.".
 *
 * @param string $lugarFecha El lugar y la fecha del pagaré.
 * @param string $pagare El número de pagaré.
 * @return string La tabla del encabezado del pagaré.
 */
function generatePagareHeaderTable($lugarFecha, $pagare)
{
    return '
        <table style="width:100%;" border="0" class="100p">
            <tr>
                <th class="seccion-right">' . $lugarFecha . '</th>
            </tr>
            <tr>
                <th align="left">' . $pagare . '</th>	
            </tr>
        </table>';
}

/**
 * Genera la tabla del encabezado del pagaré cuando el esquema de la sucursal no es "ban.".
 *
 * @param string $lugarFecha El lugar y la fecha del pagaré.
 * @param string $pagare El número de pagaré.
 * @param array $sucursal Los datos de la sucursal.
 * @return string La tabla del encabezado del pagaré.
 */
function generatePagareHeaderTableWithSucursal($lugarFecha, $pagare, $sucursal)
{
    return '
        <table style="width:100%" border="0" class="100p">
            <tr>
                <th align="left" style="padding: 0 0 5px 0">SUCURSAL: ' . mb_strtoupper($sucursal['nombre']) . '</th>
                <th class="seccion-right">' . $lugarFecha . '</th>
            </tr>
            <tr>
                <th align="left">' . $pagare . '</th>		
            </tr>				
        </table>';
}

/**
 * Genera el cuerpo HTML para el pagaré.
 *
 * @param string $empresa Nombre de la empresa a la que se debe el pagaré.
 * @param float $monto Monto del pagaré.
 * @param string $montoLetra Monto del pagaré en letras.
 * @param array $tasas Tasas de interés para el pagaré.
 * @return string HTML generado para el cuerpo del pagaré.
 */
function generatePagareBodyHtml($empresa, $monto, $montoLetra, $tasas)
{

    $html = '
    <p>
        Debemos y pagaremos incondicionalmente por este pagaré a la orden de ' . $empresa . ', la cantidad de $' . number_format($monto, 2, '.', ',') . ' - (' . $montoLetra . '), misma que deberá ser pagada de acuerdo con la tabla de amortización anexa.
    </p>
    <p>
        Pagadero en esta ciudad juntamente con el principal, en el domicilio ubicado en RAYÓN #704, BARRIO SAN ANTONIO, ZIMATLÁN DE ÁLVAREZ, OAXACA. En caso de que se produzca un retraso en los pagos arriba mencionados, la totalidad del saldo insoluto se dará por vencido, generando el vencimiento anticipado de los pagos pendientes, más los intereses moratorios, que en su caso, se generen hasta el pago total del adeudo. 
    </p>
    <p>
        Valor recibido a mi entera satisfacción con anterioridad a la presente fecha siendo la suscripción del presente pagaré el recibo mas amplio que en derecho proceda por la cantidad antes entregada, para todos los efectos a los que haya lugar.
    </p>
    <p>
        El importe de este pagaré causará intereses ordinarios a razón de ' . $tasas['tasaMensual'] . '% mensual, sobre el saldo insoluto del crédito. La cantidad importe de este pagaré causará intereses moratorios a razón de ' . $tasas['tasaMensualMora'] . '% aplicado al saldo insoluto de cada mes o fracción de retraso, mientras dure la mora.
    </p>';

    return $html;
}

// Generación de HTML para la TABLA DE AMORTIZACIONES
/**
 * Genera el encabezado de la tabla de amortizaciones.
 *
 * @param array   $credito        Datos del crédito.
 * @param string  $nombreSucursal Nombre de la sucursal.
 * @param string  $fechaEntrega   Fecha de entrega del crédito.
 * @param float   $monto          Monto del crédito.
 * @param array   $tasas          Tasas de interés.
 * @param int     $diasCredito    Plazo en días del crédito.
 * @param string  $nombrePromotor Nombre del promotor.
 *
 * @return string HTML del encabezado de la tabla de amortizaciones.
 */
function generarHeaderTablaAmortizaciones($credito, $nombreSucursal, $fechaEntrega, $monto, $tasas, $diasCredito, $nombrePromotor)
{
    $html = '<h3 align="center">TABLA DE AMORTIZACIONES</h3>';

    $html .= '
    <table style="width:100%" border="0">
        <tr>
            <th width="40%">
                Sucursal: ' . $credito['idsucursal'] . ' - ' . $nombreSucursal . '
            </th>
            <th width="20%">
                Producto: Crédito ' . $credito['nivel'] . '
            </th>
            <th width="40%">
                Crédito: ' . $credito['idcredito'] . '
            </th>
        </tr>
        <tr>
            <th>
                Socio (a): ' . $credito['idacreditado'] . ' ' . $credito['nombre'] . '
            </th>
            <th>
                Interés: ' . $tasas['tasaAnual'] . '%
            </th>	
            <th>
                Fecha: ' . $fechaEntrega . '
            </th>		
        </tr>	
        <tr>
            <th >
                Monto: ' . $monto . '
            </th>		
            <th>
                Mora: ' . $tasas['tasaAnualMora'] . '%
            </th>
            <th>
                Financiamiento: Recursos propios
            </th>		
        </tr>									
        <tr>
            <th>
                Plazo: ' . $diasCredito . ' días
            </th>		
            <th>
                Cartera: Comercial
            </th>
            <th>
                PDSF: ' . $nombrePromotor . '
            </th>		
        </tr>													
    </table>
    ';

    return $html;
}

/**
 * Crea una tabla de amortizaciones con los datos proporcionados.
 *
 * @param array  $title   Títulos de las columnas de la tabla.
 * @param array  $data    Datos de la tabla de amortizaciones.
 * @param string $esquema Esquema utilizado para determinar qué columnas mostrar.
 *
 * @return string HTML de la tabla de amortizaciones.
 */
function tableCreateAmortizaciones($data, $esquema)
{
    if ($esquema == "ban.") {
        $title = array("Pago", "Vencimiento", "Saldo Capital", "Aporte Solidario", "Capital", "Cuota");
    } else {
        $title = array("Pago", "Vencimiento", "Saldo Capital", "Interés", "IVA", "Capital", "Cuota");
    }

    $html = '<table style="width:80%" align="center">';
    $html .= '  <tr>';

    // Agrega los títulos de las columnas
    foreach ($title as $value) {
        $html .= '    <th align="center">' . $value . '</th>';
    }

    $html .= '  </tr>';

    // Agrega los datos de cada fila
    foreach ($data as $value) {

        // Formatea la fecha de vencimiento
        $fecha = date_create($value['fecha_vence']);
        $fecha = date_format($fecha, 'd/m/Y');

        $html .= '  <tr>';
        $html .= '  <td>' . formatNumber($value['numero'], 0) . '</td>';
        $html .= '  <td>' . $fecha . '</td>';
        $html .= '  <td>' . formatNumber($value['saldo_capital']) . '</td>';

        // Agrega los datos dependiendo del esquema
        if ($esquema == "ban.") {
            $html .= '  <td>' . formatNumber($value['aportesol']) . '</td>';
        } else {
            $html .= '  <td>' . formatNumber($value['interes']) . '</td>';
            $html .= '  <td>' . formatNumber($value['iva']) . '</td>';
        }

        $html .= '  <td>' . formatNumber($value['capital']) . '</td>';
        $html .= '  <td>' . formatNumber($value['total']) . '</td>';
        $html .= '  </tr>';
    }

    $html .= '</table>';
    return $html;
}

// Generación de HTML para el checklist
/**
 * Crea una tabla HTML para mostrar los datos del checklist.
 *
 * @param array $data Los datos del checklist.
 * @param string $esquema El esquema de créditos.
 * @return string La tabla HTML generada.
 */
function tableCheckList($data)
{
    $title = array("Documento", "Fecha cumplimiento");

    $html = '<h3 align="center">CHECKLIST</h3>';
    $html .= '<table style="width:100%">';
    $html .= '  <tr>';

    // Encabezados de la tabla
    foreach ($title as $column) {
        $html .= '    <th>' . $column . '</th>';
    }

    $html .= '  </tr>';

    foreach ($data as $row) {
        $fechaFormateada = '';

        // Verifica si la fecha no está vacía antes de formatear
        if (!empty($row['fecha'])) {
            $fechaFormateada = date('d-m-Y H:i:s', strtotime($row['fecha']));
        }

        $html .= '  <tr>';
        $html .= '    <td>' . $row['documento'] . '</td>';
        $html .= '    <td>' . $fechaFormateada . '</td>';
        $html .= '  </tr>';
    }

    $html .= '</table>';
    return $html;
}

// HTML para recibo de retiro de garantía
/**
 * Genera el cuerpo del recibo de autorización de traspaso de garantía.
 *
 * @param array $garantiaData Los datos de la garantía.
 * @param string $montoLetra El monto en palabras.
 * @return string El cuerpo del recibo de autorización de traspaso de garantía en formato HTML.
 */
function generateBodyReciboGarantia($garantiaData, $montoLetra)
{
    $html = '
    <table style="width:100%; margin-bottom: 15px;" border="1">
        <tr>
            <th colspan="2" style="text-align: center;">
                RECIBO DE AUTORIZACIÓN DE TRASPASO
            </th>
        </tr>
        <tr>
            <th width="30%">
                Cantidad Transferida:
            </th>		
            <th>$'
        . formatNumber($garantiaData['garantia_monto']) . ' -  (' . $montoLetra . ')
            </th>
        </tr>
        <tr>
            <th>
                Concepto:
            </th>		
            <th>
                Traspaso de Ahorro Comprometido a Ahorro Corriente
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">
                Datos de la Socia:
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">
                <ul type="none">
                    <li>Nombre: ' . $garantiaData['idacreditado'] . ' - ' . $garantiaData['acreditado_nombre'] . '</li>
                    <li>Colmena: ' . $garantiaData['colmena_numero'] . ' - ' . $garantiaData['colmena_nombre'] . '</li>
                    <li>Grupo: ' . $garantiaData['colmena_grupo'] . '</li>
                </ul>
            </th>
        </tr>  
        
        <tr>
            <th style="padding: 10px 60px;">
                Fecha de Autorización:
            </th>		
            <th style="text-align: center;">
                ' . convertirFechaLetras($garantiaData['fecha'], true) . '
            </th>
        </tr>
    </table>
    ';

    return $html;
}

// Generación de HTML para la Solicitid de emisión de créditos
/**
 * Genera el encabezado para el reporte de emisión de créditos o cheques.
 *
 * @param string $esquema El esquema utilizado para el tipo de solicitud ('ban.' o 'otro').
 * @param string $sucursalNombre El nombre de la sucursal.
 * @param int $numeroSemana El número de la semana.
 * @param string $fechaLetras La fecha en letras.
 * @return string El encabezado HTML para el reporte.
 */
function headerEmisionCreditos($esquema, $sucursalNombre, $numeroSemana, $fechaLetras)
{
    $title = ($esquema === 'ban.') ? 'SOLICITUD DE EMISIÓN DE CHEQUES' : 'SOLICITUD DE EMISIÓN DE CRÉDITOS';
    $html = '
			<h3 align="center">' . $title . '</h3>
			<h3 align="center">RUTA ' . $sucursalNombre . '</h3>	
			<h3 align="center">Semana ' . $numeroSemana . ', ' . $fechaLetras . '</h3>';

    return $html;
}

/**
 * Crea una tabla HTML para mostrar los datos de emisión de créditos.
 *
 * @param array $data Los datos de emisión de créditos.
 * @param string $esquema El esquema de créditos.
 * @param bool $mostrarFecha Indica si se debe mostrar la fecha en la tabla, para el caso de la emisión global.
 * @return string La tabla HTML generada.
 */
function tableCreateEmisionCreditos($data, $esquema, $mostrarFecha = true)
{
    // Definir los encabezados base comunes a ambos esquemas
    $headers = array("Fecha", "Nombre", "Crédito", "Nivel", "Monto", "Garantia", "Importe", "No. cheque", "Grupo", "Colmena", "Promotor", "Observaciones");

    // Ajustar los encabezados si no se debe mostrar la fecha
    if (!$mostrarFecha) {
        // Remover el encabezado de "Fecha"
        unset($headers[0]);
    }

    // Ajustar los encabezados si el esquema no es "ban."
    if ($esquema !== "ban.") {
        // Remover el encabezado de "No. cheque"
        unset($headers[7]);
    }

    // Reindexar el array de encabezados para asegurarnos de que esté en orden
    $headers = array_values($headers);

    // Inicializar el HTML de la tabla
    $html = '<table width="100%">';

    // Encabezados de la tabla
    $html .= '<tr>';
    foreach ($headers as $value) {
        $html .= '<th  height="18" align="center">' . $value . '</th>';
    }
    $html .= '</tr>';

    $totalMonto = 0.00;
    $totalGarantia = 0.00;
    $totalImporte = 0;

    // Recorrer los datos de emisión de créditos y agregar filas a la tabla
    foreach ($data as $value) {
        $html .= '<tr>';
        if ($mostrarFecha) {
            $fecha = date('d/m/Y', strtotime($value['fecha']));
            $html .= '<td align="center" width="5%">' . $fecha . '</td>';
        }

        $monto = $value['monto'];
        $garantia = max($value['garantia_monto'], 0); // Garantía mínima de 0
        $total = $monto + $garantia;
        $totalMonto += $monto;
        $totalGarantia += $garantia;
        $totalImporte += $total;

        $html .= '<td width="25%">' . $value['idacreditado'] . ' - ' . mb_strtoupper($value['acreditado_nombre']) . '</td>';
        $html .= '  <td align="center" width="5%">' . $value['idcredito'] . '</td>';
        $html .= '  <td align="center" width="4%">' . $value['nivel'] . '</td>';
        $html .= '  <td align="right" width="6%">' . formatNumber($monto) . '</td>';
        $html .= '  <td align="right" width="6%">' . formatNumber($garantia) . '</td>';
        $html .= '  <td align="right" width="6%">' . formatNumber($total) . ' </td>';

        $html .= ($esquema == 'ban.') ? '<td align="center" width="7%">' . $value['cheque_ref'] . '</td>' : '';

        $html .= '  <td align="center" width="4%">' . $value['colmena_grupo'] . '</td>';
        $html .= '  <td align="center" width="6%">' . $value['colmena_numero'] . '</td>';
        $html .= '  <td>' . $value['promotor'] . '</td>';
        $html .= '  <td>' . $value['proy_observa'] . '</td>';
        $html .= '  </tr>';
    }

    // Agregar la fila de totales
    $html .= '  <tr>';
    if ($mostrarFecha) {
        $html .= '  <td colspan="4" align="right" height="16"><b>TOTALES</b></td>';
    } else {
        $html .= '  <td colspan="3" align="right" height="16"><b>TOTALES</b></td>';
    }
    $html .= '  <td align="right"><b>' . formatNumber($totalMonto) . '</b></td>';
    $html .= '  <td align="right"><b>' . formatNumber($totalGarantia) . '</b></td>';
    $html .= '  <td align="right"><b>' . formatNumber($totalImporte) . '</b></td>';

    if ($esquema == 'ban.') {
        $html .= '  <td colspan="5"></td>';
    } else {
        $html .= '  <td colspan="4"></td>';
    }
    $html .= '  </tr>';
    $html .= '</table>';
    return $html;
}







// Generación de HTML para el Plan de Pagos
function determinarEncabezadoPlanPagos($idproducto, $esquema)
{
    if ($idproducto === '10') {
        return array("Pago", "Fecha programada", "Capital", "Saldo capital", "Interés", "IVA", "Total del pago", "Firma del promotor", "Ahorro voluntario", "Fecha de recibido");
    } else {
        if ($esquema === 'ban.') {
            return array("Pago", "Fecha programada", "Capital", "Saldo capital", "Aporte solidario", "Garantía", "Gtía. acum.", "Total del pago", "Firma del promotor", "Ahorro voluntario", "Fecha de recibido");
        } else {
            return array("Pago", "Fecha programada", "Capital", "Saldo capital", "Interés", "Ahorro Compr.", "Ahorro Compr. acum.", "Total del pago", "Firma del promotor", "Ahorro voluntario", "Fecha de recibido");
        }
    }
}

function generarHeaderPlanPagos($cred, $sucursal, $colmenaData, $fecha, $persona)
{

    $html = '<h3 align="center">PLAN DE PAGOS</h3>';

    $html .= '
    <table style="width:100%; margin-bottom: 15px;" border="0" class="100p">
        <tr>
            <td class="header-plan" width="44%">
                Sucursal: <strong>' . $cred['idsucursal'] . ' - ' . mb_strtoupper($sucursal['nombre']) . '</strong>
            </td>
            <td class="header-plan" width="36%">
                Colmena:<strong> ' . $colmenaData['colmena_numero'] . ' ' . $colmenaData['colmena_nombre'] . '</strong>
            </td>
            <td class="header-plan" width="20%">
                Fecha:<strong> ' . $fecha . '</strong>
            </td>	
            
        </tr>
        <tr>
            <td class="header-plan">
                Socia (o):<strong> ' . $cred['idacreditado'] . ' ' . $cred['nombre'] . '</strong>
            </td>
            <td class="header-plan">
                Grupo:<strong> ' . $colmenaData['colmena_grupo'] . '</strong>
            </td>
            <td class="header-plan">
                Crédito:<strong> ' . $cred['idcredito'] . '</strong>
            </td>
                    
        </tr>	
        <tr>
            <td class="header-plan">
                Tel:<strong> ' . $persona['celular'] . '</strong>
            </td>
            <td colspan="2" class="header-plan">
                Propósito del crédito:<strong> ' . $cred['proy_nombre'] . '</strong>
            </td>									
        </tr>	
    </table>';

    return $html;
}

function avisoPlanPagos()
{
    $html = '
    <p style="font-size: 10px">
		La información contenida en el presente documento es un Plan de Pagos resumido para fines informativos y de control de campo, por lo que podrá estar sujeta a cambios y bajo ninguna circunstancia podrá considerarse como una oferta vinculante, ni como la autorización formal de crédito por parte de la empresa.
	</p>
    ';

    return $html;
}

















// Generación de HTML para el contrato de crédito
function datosRegistro($esquema)
{
    switch ($esquema) {
        case 'ama.':
            $notarioData = 'Que es una sociedad cooperativa debidamente constituida conforme a las leyes mexicanas, según consta en el testimonio notarial número 17,664, Volumen 220, con fecha 09 de abril de 2015, otorgado ante la fe del Lic. EDUARDO GARCÍA CORPUS, Notario Público número CIENTO CINCO, del Estado de Oaxaca.';
            break;
        default:
            $notarioData = 'Que es una sociedad anónima debidamente constituida conforme a las leyes mexicanas, según consta en el testimonio notarial número 1305, de fecha 26 de mayo de 2014, otorgado ante la fe del Lic. JOSE JORGE ENRIQUE ZARATE RAMIREZ, Notario Público número OCHENTA Y CUATRO, del Estado de Oaxaca.';
            break;
    }

    return $notarioData;
}

function generarHeaderContratoHTML($sucursal, $fecha, $idCredito)
{
    $html = '<h3 align="center">CONTRATO DE CRÉDITO</h3>';

    $html .= '
    <table style="width:100%; margin: 15px 0 0 0" border="0" class="100p">
        <tr>
            <th align="left" style="padding: 0 0 5px 0">
                SUCURSAL: ' . $sucursal . '
            </th>
            <th align="right">
                FECHA: ' . $fecha . '
            </th>
        </tr>
        <tr>
            <th align="left">
                Contrato No.: ' . $idCredito . '
            </th>		
        </tr>				
    </table>';

    return $html;
}

function generarTextoAval($cred, $aval1, $aval2, $nivel)
{
    $texto = '<li>Que es (son):<br>';

    if ($cred['idproducto'] === '10') {
        $texto .= '<hr class="informacion">';
    } elseif ($cred['idproducto'] === '1') {
        $texto .= $aval1['nombre'] . '<br>';
        if ($nivel >= 15) {
            $texto .= $aval2['nombre'] . '<br>';
        }
    }
    $texto .= 'Persona(s) física(s) de nacionalidad mexicana que dará(n) cumplimiento al presente contrato.';

    $texto .= '</li>';

    return $texto;
}

function generarTextoDomicilios($cred, $domicilioAval1, $domicilioAval2, $nivel)
{
    $texto = '<li>Que su(s) domicilio(s) respectivo(s), se ubica(n) en: <br>';
    if ($cred['idproducto'] === '1') {

        $texto .= '<ul><li>' . $domicilioAval1['direccion1'] . ', Colonia ' . $domicilioAval1['colonia'] . ', Municipio ' . $domicilioAval1['municipio'] . ', en el Estado de ' . $domicilioAval1['estado'] . '</li>';

        if ($nivel >= 15) {
            $texto .= '<li>' . $domicilioAval2['direccion1'] . ', Colonia ' . $domicilioAval2['colonia'] . ', Municipio ' . $domicilioAval2['municipio'] . ', en el Estado de ' . $domicilioAval2['estado'] . '</li>';
        }
    } else {
        $texto .= '<hr class="informacion">';

        if ($nivel >= 15) {
            $texto .= '<hr class="informacion">';
        }
    }
    $texto .= '</ul></li>';

    return $texto;
}


function generarContratoHTML($cred, $empresa, $esquema, $tipoPersona, $nivel, $aval1, $aval2, $domicilioAval1, $domicilioAval2 = '', $monto, $montoLetra, $diasCredito, $fechaVencimiento, $tasas, $fecha, $sucursal)
{

    $html = '
    <p>Contrato de crédito que celebran, por una parte, la C. ' . $cred['nombre'] . ' a quien en lo sucesivo se le denominará ACREDITADO, y ' . $empresa . ' en lo sucesivo denominada el ACREDITANTE, al tenor de las siguientes cláusulas y declaraciones:</p>
    
    <h4 align="center">DECLARACIONES</h4>';

    $html .= '
    <ol type="I">
        <li><b>ACREDITANTE, por conducto de su representante:</b></li>
            <ol type="a">
                <li>' . datosRegistro($esquema) . '</li>
                <li>Que cuenta con poderes suficientes para obligar a su representada en los términos y condiciones de este contrato, los cuales no le han sido revocados ni limitados.</li>
                <li>Que tiene como objeto social, entre otros, otorgar servicios y productos financieros.</li>
                <li>Que su domicilio se ubica en Rayón #704, Barrio San Antonio, Zimatlán de Álvarez, Oaxaca de Juárez, Oaxaca. C.P. 71200.</li>
            </ol>
        
        <li><b>ACREDITADO, por su propio derecho:</b></li>
            <ol type="a">
                <li>Que es una persona ' . ($tipoPersona == 'M' ? 'moral legalmente constituida conforme a las leyes de la República Mexicana.' : 'física de nacionalidad mexicana, mayor de edad, en pleno uso y goce de sus facultades para la celebración del presente contrato de crédito.') . '</li>
                <li>Que su domicilio se ubica en ' . $cred['direccion'] . '.</li>
                <li>Que es socio de la ACREDITANTE.</li>
                <li>Que es su deseo celebrar el presente contrato.</li>
            </ol>
        <li><b>Del (los) AVAL(es):</b></li>
            <ol type="a">
                ' . generarTextoAval($cred, $aval1, $aval2, $nivel) . '
                
                <li>Que, en virtud de las relaciones personales y jurídicas que tiene(n) con el ACREDITADO, es de su interés hacer con el presente contrato el objeto de obligarse conjunta y solidariamente con éste último frente al ACREDITANTE, en el cumplimiento de todas las obligaciones establecidas en el presente contrato a su cargo y, por tanto, es su intención constituirse como obligado(s) solidario(s), así como suscribir el o los pagarés con que se documente(n) la(s) disposición(es) del crédito objeto del presente contrato en su carácter de avalista(s) y garante(s).</li>
                ' . generarTextoDomicilios($cred, $domicilioAval1, $domicilioAval2, $nivel) . '
                <li>Que es su voluntad comparecer en la celebración del presente contrato con el carácter de aval(es).</li>
                <li>Que, en caso de incumplimiento del ACREDITADO, se comprometen al pago del capital más intereses devengados en un plazo de cinco días contados a partir de la fecha en que se haga obligatorio el pago del crédito.</li>
            </ol>
    </ol>

    <h4 align="center">CLÁUSULAS</h4>

    <p><b>Primera.- Importe.</b> La ACREDITANTE entrega al ACREDITADO en la fecha de firma del presente contrato la cantidad de $' . $monto . ' (' . $montoLetra . '). El acreditado se obliga a pagar el crédito de conformidad con los pagos fijados en la cláusula tercera.</p>

    <p><b>Segunda.- Vigencia.</b> La vigencia de este contrato es de ' . $diasCredito . ' días contados a partir de la fecha del presente contrato, por lo que concluirá precisamente el día ' . $fechaVencimiento . '. No obstante a su terminación, este contrato producirá todos sus efectos legales hasta que el ACREDITADO haya liquidado en su totalidad el importe del crédito más sus accesorios a la ACREDITANTE.</p>

    <p><b>Tercera.- Plan de Pagos.</b> Los plazos y montos a pagarse se regirán mediante la Tabla de Amortización Anexa.</p>

    <p><b>Cuarta.- Intereses Ordinarios.</b> El ACREDITADO se obliga a pagar a la ACREDITANTE, durante la vigencia del presente contrato, intereses ordinarios sobre capital insoluto del crédito y se calcularan a una tasa mensual del ' . $tasas['tasaMensual'] . '%. El ACREDITADO pagará intereses ordinarios a partir de la fecha en que se disponga del crédito conforme a lo establecido en este contrato, hasta que cubra el importe total del crédito.</p> 

    <p><b>Quinta.- Intereses Moratorios.</b> En caso de que el ACREDITADO no pague puntualmente en la fechas establecidas, se obliga a pagar como pena convencional una tasa de interés moratoria mensual de ' . $tasas['tasaMensualMora'] . '% aplicada a cada mes o fracción de retraso. La tasa quedará como única en el periodo de mora.</p>

    <p><b>Sexta.- Leyes y Tribunales.</b> Este contrato se rige de acuerdo a las Leyes del Estado de Oaxaca. El domicilio para dirimir cualquier controversia respecto al presente contrato, así como su interpretación legal, se sujetará a la jurisdicción y territorio del domicilio de la ACREDITANTE o la que esta elija, renunciando el ACREDITADO y el (los) AVAL(es) a cualquier jurisdicción que le corresponda de conformidad con la ley que rija en su domicilio.</p>

    <p><b>Séptima.- Garantías.</b> En la eventualidad de requerir garantías al ACREDITADO, las mismas serán detalladas en un documento anexo a este contrato. Dichas garantías serán válidas durante la vigencia del presente contrato y hasta que el ACREDITADO haya cumplido con todas las obligaciones establecidas en el mismo.</p>

    <p>En caso de incumplimiento por parte del ACREDITADO, la ACREDITANTE tendrá el derecho de tomar posesión de las garantías ofrecidas y, a su discreción, proceder a su venta o disposición para cubrir cualquier adeudo pendiente.</p>

    <p>El ACREDITADO autoriza expresamente a la ACREDITANTE para realizar los trámites y gestiones necesarios para la toma de posesión y eventual venta de las garantías, renunciando a cualquier notificación adicional o proceso legal que pudiera ser requerido para tales efectos.</p>
    
    <p>La tasación de las garantías y los procedimientos para su realización se llevarán a cabo conforme a lo dispuesto por las leyes aplicables.</p>

    <p><b>Octava.- Disposición para compensación.</b> A fin de garantizar la total recuperación del crédito, el ACREDITADO autoriza expresamente a la ACREDITANTE a compensar cualquier adeudo pendiente mediante la aplicación de los haberes que tenga depositados en cuentas de ahorro a la vista o a plazo, o cualquier otra cuenta con la ACREDITANTE. Lo anterior sin necesidad de requerimiento o demanda alguna. De darse el supuesto anterior, las partes expresamente pactan que la aplicación de los recursos depositados en las referidas cuentas se apliquen al pago del crédito de la forma siguiente: intereses moratorios, intereses ordinarios y, si sobrase cantidad alguna, esta se aplicará al pago del capital hasta donde alcance. Una vez aplicados los recursos, la ACREDITANTE notificará al ACREDITADO el monto abonado por la deuda.</p>

    <p><b>Novena.- Causas de Vencimiento.</b> El plazo para el pago del crédito y sus accesorios podrá vencer anticipadamente en caso de que ocurra alguna de las siguientes situaciones:</p>

    <ol>
        <li>Si el ACREDITADO no realiza el pago puntual e íntegro de alguna amortización de capital vencido devengado, en virtud del presente contrato y en relación con el crédito. Cada situación de este tipo constituirá una "Causa de Vencimiento Anticipado".</li>
        <li>En caso de que el ACREDITADO incumpla con cualquiera de las obligaciones establecidas en el presente contrato.</li>
        <li>Si el (los) AVAL (es) no cumplen con su obligación de garantes o deudores solidarios, de acuerdo con las declaratorias correspondientes.</li>
    </ol>

    <p>En caso de que se presente alguna de las causas de vencimiento anticipado antes mencionadas, la ACREDITANTE tendrá el derecho de exigir el pago inmediato del total del crédito y sus accesorios. En tal situación, el ACREDITADO se compromete a liquidar de manera inmediata el importe total del crédito y cualquier otra suma pendiente bajo el presente contrato, según lo estipulado en el caso de que haya suscrito este documento. En caso contrario, el ACREDITADO se obliga a pagar intereses moratorios conforme a lo pactado en este instrumento.</p>

    <p><b>Décima.- Cambio de Domicilio. </b>El ACREDITADO se compromete a informar de manera inmediata y obligatoria a la ACREDITANTE sobre cualquier cambio de domicilio, dentro de los cinco días naturales siguientes a la fecha en que dicho cambio se efectúe. Esta notificación es indispensable para la correcta verificación del cambio de domicilio.</p>

    <p>Este contrato se firma el  ' . $fecha . ', en ' . $sucursal['municipio'] . '.</p>
    ';

    return $html;
}





// HTML PARA GENERAR CONVENIO 
function generarBodyConvenio($empresa, $cred, $monto, $montoLetra, $fechaLetras, $tasa, $dia, $mes, $año)
{
    $html = '
    <h3 align="center">CONVENIO</h3>

    <h4 align="right">' . $cred['idpagare'] . '</h4>

    <p>Convenio celebrado entre el ' . $empresa . ' representado en este acto por el C. Mario Enrique Rendón Hernández y por la otra parte como prestataria a la Sra. ' . $cred['nombre'] . '.</p>
    
    <p>Las “partes” contratantes manifiestan su consentimiento en las siguientes declaraciones y cláusulas.</p>

    <h4 align="center">DECLARACIONES</h4>

    <ol type="I">
        <li>
            El ' . $empresa . ', está legalmente constituida y autorizada para celebrar este tipo de convenios.
        </li>
        <li>
            La prestataria es una persona física con capacidad jurídica para celebrar contratos, con domicilio en la calle de ' . $cred['direccion'] . ', integrante de la colmena No. ' . $cred['nomcolmena'] . '.
        </li>
    </ol>

    <p>Las “partes” declaran que se obligan al cumplimiento de este convenio bajo las siguientes:</p>

    <h4 align="center">CLÁUSULAS</h4>

    <ol type="I">
        <li>
            Por medio del presente Convenio, el ' . $empresa . ' entrega a la prestataria la cantidad de $' . $monto . ' (' . $montoLetra . ') el día ' . $fechaLetras . ', como capital semilla para la actividad de: ' . $cred['proy_nombre'] . '.
        </li>
        <li>
            La prestataria se compromete a pagar al ' . $empresa . ' en efectivo mediante pagos semanales. Dichos pagos se realizarán en reuniones de la colmena cada 7 días a partir de la fecha de entrega del capital semilla.
        </li>
        <li>
            La prestataria pagará un Aporte Solidario fijo equivalente al ' . $tasa . '% mensual.
        </li>
        <li>
            Cualquier problema de interpretación o incumplimiento de este convenio, las partes se someten a los tribunales competentes de la ciudad de Oaxaca de Juárez, Oaxaca, renunciando a cualquier otro que pudiera corresponderles en razón de su domicilio. 
        </li>
    </ol>

    <p>Leído el presente convenio, conociendo su contenido y fuerza legal, firman al calce para ratificarlo a los ' . $dia . ' días del mes de ' . $mes . ' del año ' . $año . '.</p>

    <table style="width:100%" border=0>
        <tr>
            <td  align="center" width="25%">' . $empresa . '</td>
            <td></td>
            <td  align="center" width="25%">Promotor (a)</td>
            <td></td>
            <td  align="center" width="25%">Prestataria</td>
        </tr>
    </table>
    ';

    return $html;
}



/**
 * Genera una tabla HTML con los datos proporcionados.
 *
 * @param array $title Los títulos de las columnas de la tabla.
 * @param array $data Los datos para llenar la tabla.
 * @return string El código HTML de la tabla generada.
 */
function generateNivelesTable($title, $data)
{
    $html = '<table align="center">';
    $html .= '<tr>';

    // Generar encabezados de la tabla
    foreach ($title as $value) {
        $html .= '<th>' . $value . '</th>';
    }

    $html .= '</tr>';

    // Generar filas de datos
    foreach ($data as $value) {
        $html .= '<tr>';
        $html .= '  <td align="center">' . $value['nivel'] . '</td>';
        $html .= '  <td align="center" width="10px">' . $value['numero_pagos'] . '</td>';
        $html .= '  <td align="center" width="70px">' . formatNumber($value['importe']) . '</td>';
        $html .= '  <td align="center" width="60px">' . formatNumber($value['capital']) . '</td>';
        $html .= '  <td align="center">' . formatNumber($value['interes']) . '</td>';
        $html .= '  <td align="center">' . formatNumber($value['total']) . '</td>';
        $html .= '  <td align="center">' . formatNumber($value['garantia']) . '</td>';
        $html .= '  <td align="center">' . formatNumber($value['pago_semanal']) . '</td>';
        $html .= '  <td align="center">' . formatNumber($value['garantia_total']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    return $html;
}

// HTML para reporte de créditos activos
/**
 * Genera una tabla HTML con los datos de los creditos activos.
 *
 * @param array $title Los títulos de las columnas de la tabla.
 * @param array $data Los datos para llenar la tabla.
 * @return string El código HTML de la tabla generada.
 */
function generateCreditosActivosTable($title, $data, $esquema)
{
    $htmlInit = '<br><br><table style="width:100%" align="center">';
    $htmlInit .= '  <tr>';
    foreach ($title as $value) {
        $htmlInit .= '    <th>' . htmlspecialchars($value) . '</th>';
    }
    $htmlInit .= '  </tr>';

    $html = '';
    $nivel = 0;
    $contador = 1;

    foreach ($data as $value) {
        if ($nivel != $value['nivel']) {
            if ($nivel > 0) {
                $html .= '</table>';
            }
            $nivel = $value['nivel'];
            $html .= $htmlInit;
        }

        $html .= '  <tr>';
        $html .= '  <td style="width:5px;">' . htmlspecialchars($contador) . '</td>';
        $html .= '  <td style="width:3px;">' . htmlspecialchars($value['idsucursal']) . '</td>';
        $html .= '  <td style="width:3px;">' . htmlspecialchars($value['idcredito']) . '</td>';
        $html .= '  <td style="width:45px;">' . htmlspecialchars($value['fecha_dispersa']) . '</td>';
        $html .= '  <td class="text-left" style="width:100px;">' . htmlspecialchars($value['idacreditado']) . ' - ' . htmlspecialchars($value['nombre']) . '</td>';
        $html .= '  <td style="width:5px;">' . htmlspecialchars($value['nivel']) . '</td>';
        $html .= '  <td style="width:40px;">' . htmlspecialchars(formatNumber($value['monto'])) . '</td>';
        $html .= '  <td style="width:5px;">' . htmlspecialchars($value['num_pagos']) . '</td>';

        if ($esquema == 'ama.') {
            $html .= '  <td style="width:5px;">' . htmlspecialchars($value['periodo']) . '</td>';
        }
        
        $html .= '  <td style="width:3px;">' . htmlspecialchars($value['grupo_numero']) . '</td>';
        $html .= '  <td class="text-left" style="width:80px;">' . htmlspecialchars($value['col_numero']) . ' - ' . htmlspecialchars($value['col_nombre']) . '</td>';
        $html .= '  <td style="width:80px;">' . htmlspecialchars($value['promotor']) . '</td>';
        $html .= '  </tr>';
        $contador++;
    }

    $html .= '</table>';
    return $html;
}

