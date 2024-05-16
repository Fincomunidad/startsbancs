<?php
/**
 * Convierte una fecha en formato legible en letras.
 *
 * @param DateTime $fecha La fecha que se desea convertir.
 * @return string La fecha en formato legible en letras.
 */
function convertirFechaLetras($fecha)
{
    $meses = array(
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    );

    $month = date_format($fecha, 'm');
    $fechaLetras = date_format($fecha, 'd') . ' de ' . $meses[$month - 1] . ' de ' . date_format($fecha, 'Y');

    return $fechaLetras;
}

/**
 * Genera una tabla con espacio para dos firmas.
 *
 * @param string $cargo1 El cargo de la persona1.
 * @param string $persona1 El nombre de la persona1.
 * @param string $cargo2 El cargo de la persona2.
 * @param string $persona2 El nombre de la persona2.
 * @param string $text1 Cargo o texto adicional.
 * @param string $text2 Cargo o texto adicional.
 * @return string La tabla en HTML.
 */
function generarTabla2Firmas($cargo1, $persona1, $cargo2, $persona2, $text1 = '', $text2 = '') {
    $tabla = '
        <table style="width:100%; margin-bottom: 45px"  border="0">
            <tr>
                <td></td>
                <td align="center"  width="25%">' . $cargo1 . '<br><br><br>&nbsp;</td>
                <td></td>
                <td align="center"  width="25%">' . $cargo2 . '<br><br><br>&nbsp;</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="border-top: 1px solid" align="center"  width="25%">' . $persona1 . '<br>' . $text1 . '</td>
                <td></td>
                <td style="border-top: 1px solid" align="center"  width="25%">' . $persona2 . '<br>' . $text2 . '</td>
                <td></td>
            </tr>
        </table>';
        
    return $tabla;
}


/**
 * Genera la primera parte del HTML para un informe interno (No incluye logo ni direcciones).
 *
 * @param string $title El título del informe.
 * @return string La primera parte del HTML.
 */
function generateReportHeader($title, $empresa, $titulo, $logo = '', $fontSize = '12px') {
    $header = '
	<!DOCTYPE html>
	<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
        <title>' . $title . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: ' . $fontSize .';
            }
			h3 {
				text-align: center;
			}
			h4 {
				text-align: center;
			}
        </style>
    </head>
    <body>';
        
		
		 if (!empty($logo)) {
			$header .= '<h3 style="margin-left:120px;">' . $empresa . '</h3>';
			$header .= '<h4>' . $titulo . '</h4>';
			$header .= $logo;
		} else {
			$header .= '<h3>' . $empresa . '</h3>';
			$header .= '<h4>' . $titulo . '</h4>';
		}

    return $header;
}

// Función para generar o descargar el PDF
function generarPDF2($html, $nombreArchivo)
{
    ob_clean();
    $CI = &get_instance(); // Accede a la instancia de CodeIgniter
    $CI->load->library('dompdf_gen');
    $CI->dompdf->load_html($html);
    $CI->dompdf->set_paper('letter', 'portrait');
    $CI->dompdf->render();
	
    $canvas = $CI->dompdf->get_canvas();
    $font = Font_Metrics::get_font("helvetica", "bold");
    $canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));

    $documento = $CI->dompdf->stream($nombreArchivo, array("Attachment" => 0));
}

/**
 * Genera la primera parte del HTML para un informe interno (No incluye logo ni direcciones).
 *
 * @param string $title El título del informe.
 * @return string La primera parte del HTML.
 */
function generarPDF($html, $nombreArchivo, $mostrarNumeroDePagina = true)
{
    ob_clean();
    $CI = &get_instance(); // Accede a la instancia de CodeIgniter
    $CI->load->library('dompdf_gen');
    $CI->dompdf->load_html($html);
    $CI->dompdf->set_paper('letter', 'portrait');
    $CI->dompdf->render();

    if ($mostrarNumeroDePagina) {
        $canvas = $CI->dompdf->get_canvas();
        $font = Font_Metrics::get_font("helvetica", "bold");
        $canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
    }

    $documento = $CI->dompdf->stream($nombreArchivo, array("Attachment" => 0));
} 

// Reduce los ceros a la derecha del punto decimal
function formatearDecimal($numero)
{
    // Redondear a 6 decimales
    $numero = round($numero, 6);

    // Convertir el número a una cadena con 6 decimales
    $cadena = number_format($numero, 6, '.', ',');

    // Eliminar los ceros a la derecha
    $cadena = rtrim($cadena, '0');

    // Si quedan 0 después del punto decimal, quitar el punto también
    if (substr($cadena, -1) == '.') {
        $cadena = rtrim($cadena, '.');
    }

    // Si la cadena no tiene un punto decimal, agregar dos decimales
    if (!strpos($cadena, '.')) {
        $cadena .= '.00';
    } elseif (strlen(substr($cadena, strpos($cadena, '.') + 1)) < 2) {
        // Si la cadena tiene menos de 2 decimales, agregar ceros adicionales
        $cadena .= '0';
    }

    return $cadena;
}