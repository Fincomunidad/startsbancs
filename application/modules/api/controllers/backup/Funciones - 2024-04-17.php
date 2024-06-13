<?php

/**
 * Obtiene el nombre de la empresa según el esquema proporcionado.
 *
 * @param string $esquema El esquema para el cual se desea obtener el nombre de la empresa.
 * @return string El nombre de la empresa correspondiente al esquema proporcionado.
 */
function getEmpresa($esquema)
{
    $esquemaEmpresas = [
        "fin." => "FINCOMUNIDAD S.A. DE C.V., S.F.C.",
        "ban." => "CENTRO DE DESARROLLO COMUNITARIO CENTÉOTL A.C.",
        "ama." => "Asociación de Mujeres para la Autogestión, S.C. de R.L.",
        "imp." => "IMPULSO"
    ];

    // Verifica si el esquema actual existe en el array, si no, asigna un valor predeterminado
    $empresa = array_key_exists($esquema, $esquemaEmpresas) ? $esquemaEmpresas[$esquema] : "Empresa Desconocida";

    return $empresa;
}

/**
 * Renderiza y muestra un PDF.
 *
 * @param string $html El contenido HTML que se convertirá en PDF.
 * @param string $fileName El nombre del archivo PDF resultante.
 * @param bool $showPageNumber Indica si se debe mostrar el número de página en el PDF.
 * @param string $paper El tamaño del papel para el PDF (por defecto 'letter').
 * @param string $pageOrientation La orientación de la página (por defecto 'portrait').
 * @return string|null El contenido del PDF generado si se desea capturar, o null si se muestra directamente.
 */
function renderPDF($html, $fileName = 'file.pdf', $showPageNumber = true, $paper = 'letter', $pageOrientation = 'portrait')
{
    try {
        ob_clean();
        $CI = &get_instance(); // Accede a la instancia de CodeIgniter
        $CI->load->library('dompdf_gen');
        $CI->dompdf->load_html(($html));
        $CI->dompdf->set_paper($paper, $pageOrientation);
        $CI->dompdf->render();

        if ($showPageNumber) {
            $canvas = $CI->dompdf->get_canvas();
            $font = Font_Metrics::get_font("helvetica", "bold");
            $canvas->page_text(500, 770, "Página: {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
        }

        $output = $CI->dompdf->output();

        // Muestra el PDF directamente
        $CI->dompdf->stream($fileName, array("Attachment" => 0));

        // Si se desea capturar el PDF generado, se puede devolver el contenido
        return $output;
    } catch (Exception $e) {
        // Manejo de errores
        log_message('error', 'Error al renderizar el PDF: ' . $e->getMessage());
        show_error('Error al renderizar el PDF. Por favor, inténtalo de nuevo más tarde.');
        return null;
    }
}

/**
 * Convierte un número en formato numérico a su representación en palabras en español.
 *
 * @param int|float|string $num El número a convertir.
 * @param bool $fem Indica si se deben usar formas femeninas para los números.
 * @param bool $dec Indica si se deben incluir los decimales en la representación.
 * @return string La representación en palabras del número.
 */
function numberToWords($num, $fem = false, $dec = true)
{
    $matuni[2]  = "dos";
    $matuni[3]  = "tres";
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

    $float = explode('.', $num);
    $num = $float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
        $neg = 'menos ';
        $num = substr($num, 1);
    } else
        $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
        $n = $num[$c];
        if (!(strpos(".,'''", $n) === false)) {
            if ($punt) break;
            else {
                $punt = true;
                continue;
            }
        } elseif (!(strpos('0123456789', $n) === false)) {
            if ($punt) {
                if ($n != '0') $zeros = false;
                $fra .= $n;
            } else

                $ent .= $n;
        } else

            break;
    }
    $ent = '     ' . $ent;
    if ($dec and $fra and !$zeros) {
        $fin = ' coma';
        for ($n = 0; $n < strlen($fra); $n++) {
            if (($s = $fra[$n]) == '0')
                $fin .= ' cero';
            elseif ($s == '1')
                $fin .= $fem ? ' una' : ' un';
            else
                $fin .= ' ' . $matuni[$s];
        }
    } else
        $fin = '';
    if ((int)$ent === 0) return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while (($num = substr($ent, -3)) != '   ') {
        $ent = substr($ent, 0, -3);
        if (++$sub < 3 and $fem) {
            $matuni[1] = 'una';
            $subcent = 'as';
        } else {
            $matuni[1] = $neutro ? 'un' : 'uno';
            $subcent = 'os';
        }
        $t = '';
        $n2 = substr($num, 1);
        if ($n2 == '00') {
        } elseif ($n2 < 21)
            $t = ' ' . $matuni[(int)$n2];
        elseif ($n2 < 30) {
            $n3 = $num[2];
            if ($n3 != 0) $t = 'i' . $matuni[$n3];
            $n2 = $num[1];
            $t = ' ' . $matdec[$n2] . $t;
        } else {
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
        } elseif ($n == 5) {
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
        } elseif ($n != 0) {
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
        }
        if ($sub == 1) {
        } elseif (!isset($matsub[$sub])) {
            if ($num == 1) {
                $t = ' mil';
            } elseif ($num > 1) {
                $t .= ' mil';
            }
        } elseif ($num == 1) {
            $t .= ' ' . $matsub[$sub] . '?n';
        } elseif ($num > 1) {
            $t .= ' ' . $matsub[$sub] . 'ones';
        }
        if ($num == '000') $mils++;
        elseif ($mils != 0) {
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
            $mils = 0;
        }
        $neutro = true;
        $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    $end_num = ucfirst($tex) . ' pesos ' . $float[1] . '/100 M.N.';
    return $end_num;
}

/**
 * Formatea un número con dos decimales y separadores de miles y decimales.
 *
 * @param float $number El número a formatear.
 * @return string El número formateado.
 */
function formatNumber($number)
{
    return number_format($number, 2, '.', ',');
}

/**
 * Calcula la edad basada en la fecha de nacimiento y una fecha de referencia.
 *
 * @param string $fechaNacimiento La fecha de nacimiento en formato 'YYYY-MM-DD'.
 * @param string $fecha La fecha de referencia en formato 'YYYY-MM-DD'.
 * @return int La edad calculada.
 * @throws Exception Si hay un error al calcular la edad.
 */
function calcularEdad($fechaNacimiento, $fechaRef)
{
    try {
        $fechaNac = new DateTime($fechaNacimiento);
        // $fechaRef = new DateTime($fecha);
        $dif = $fechaNac->diff($fechaRef);
        return $dif->y;
    } catch (Exception $e) {
        throw new Exception("Error al calcular la edad: " . $e->getMessage());
    }
}

// 2024-04-12
/**
 * Convierte una fecha en formato DateTime a su representación en texto,
 * opcionalmente incluyendo el día de la semana.
 *
 * @param DateTime $fecha La fecha en formato DateTime que se desea convertir.
 * @param bool $incluyeDia (Opcional) Indica si se desea incluir el día de la semana en la representación de la fecha. Por defecto es false.
 * @return string La fecha en formato de texto, con o sin el día de la semana, según el parámetro $incluyeDia.
 * @throws InvalidArgumentException Cuando la fecha proporcionada no es válida.
 */
function convertirFechaLetras($fecha, $incluyeDia = false)
{
    // Manejo de errores
    if (!$fecha instanceof DateTime) {
        throw new InvalidArgumentException('La fecha proporcionada no es válida.');
    }

    $meses = array(
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    );

    $dias = array(
        "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"
    );

    $month = date_format($fecha, 'm');
    $fechaLetras = '';

    if ($incluyeDia) {
        $fechaLetras .= $dias[date_format($fecha, 'w')] . ' ';
    }

    $fechaLetras .= date_format($fecha, 'd') . ' de ' . $meses[$month - 1] . ' de ' . date_format($fecha, 'Y');

    return $fechaLetras;
}

/**
 * Convierte el código de tipo de vivienda en su nombre descriptivo correspondiente.
 *
 * @param string $tipoVivienda El código de tipo de vivienda.
 * @return string El nombre descriptivo del tipo de vivienda.
 * @throws InvalidArgumentException Si se proporciona un código de tipo de vivienda desconocido.
 */
function tipoVivienda($tipoVivienda)
{
    switch ($tipoVivienda) {
        case '1':
            return 'Propia';
        case '2':
            return 'Rentada';
        case '3':
            return 'Familiar';
        case '4':
            return 'Prestada';
        case '5':
            return 'Otro';
        default:
            throw new InvalidArgumentException("Tipo de vivienda desconocido: $tipoVivienda");
    }
}

/**
 * Convierte un código de período en su equivalente legible.
 *
 * @param string $periodo El código de período a convertir.
 * @return string El período en formato legible (ejemplo: "Semanal", "Catorcenal", etc.).
 */
function getPeriodo($periodo)
{
    $periodos = [
        'S' => 'Semanal',
        'C' => 'Catorcenal',
        'Q' => 'Quincenal',
        'M' => 'Mensual',
        'N' => '28 días',
    ];

    // Verifica si el período está definido en el array, de lo contrario, usa '28 días'
    return $periodos[$periodo];
}

/**
 * Selecciona y formatea los datos del aval, dependiendo si el aval 1 tiene o no datos.
 *
 * @param array|null $aval1 Los datos del primer aval.
 * @param array|null $aval2 Los datos del segundo aval.
 * @return array Los datos del aval seleccionado y formateado.
 */
function seleccionarAval($aval1, $aval2)
{
    $avalText1 = $aval1 ? ucfirst(strtolower($aval1['cargo'])) . ' de Grupo' : '';
    $avalText2 = $aval2 ? ucfirst(strtolower($aval2['cargo'])) . ' de Colmena' : '';

    if ($aval1) {
        return ['nombre' => $aval1['nombre'], 'cargo' => $avalText1];
    } elseif ($aval2) {
        return ['nombre' => $aval2['nombre'], 'cargo' => $avalText2];
    } else {
        return ['nombre' => '', 'cargo' => 'Presidenta de Grupo'];
    }
}

/**
 * Genera la cabecera básica del HTML para un informe interno. 2023-09-28
 *
 * @param string $title   El título del informe.
 * @param string $fontSize Tamaño de fuente (por ejemplo, '12px') (opcional).
 * @return string La cabecera del HTML.
 */
function generateBasicHeader($title, $fontSize = '12px') {
    $header = '
	<!DOCTYPE html>
	<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>' . $title . '</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: ' . $fontSize . ';
            }
            h3, h4 {
                text-align: center;
            }
        </style>
    </head>
    <body>';
    
    return $header;
}

/**
 * Agrega el logo y subtítulo a la cabecera del informe. 2023-09-08
 *
 * @param string $header   La cabecera del informe generada por generateBasicHeader.
 * @param string $empresa  El nombre de la empresa.
 * @param string $titulo   El subtítulo o título secundario.
 * @param string $logo     El código HTML para mostrar el logo (opcional).
 * @return string La cabecera completa con logo y subtítulo.
 */
function addLogoAndSubtitle2($header, $titulo, $subtitulo1 = '', $subtitulo2 = '', $logo = '') {
    if (!empty($logo)) {
        $header .= '<div style="margin-left:120px;">' . $logo . '</div>';
    }
    $header .= $titulo;
    $header .= $subtitulo1;
	$header .= $subtitulo2;
    
    return $header;
}

// Correcto
function addLogoAndSubtitle($header, $titulo, $subtitulos = [], $logo = '')
{
	foreach ($subtitulos as $subtitulo) {
        $subtit .= $subtitulo;
    }
	
	if (!empty($logo)) {
			$header .= '<h3 style="margin-left:120px;">' . $titulo . '</h3>';
			$header .= $subtit;
			$header .= $logo;
		} else {
			$header .= '<h3>' . $titulo . '</h3>';
			$header .= $subtit;
		}

    return $header;
}

/**
 * Obtiene el nombre de la sucursal en el formato deseado.
 *
 * @param string $sucursalId El ID de la sucursal.
 * @return string El nombre de la sucursal formateado.
 */
function obtenerNombreSucursal($sucursalId) {
    // Verificar si el ID de la sucursal es igual a '01'
    if ($sucursalId === '01') {
        return 'ZIMATLÁN';
    }

    // Obtener el nombre de la sucursal desde la sesión y convertirlo a mayúsculas
    $nombreSucursal = mb_strtoupper(get_instance()->session->userdata('nomsucursal'));

    return $nombreSucursal;
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
function generarTabla2Firmas2($cargo1, $persona1, $cargo2, $persona2, $text1 = '', $text2 = '') {
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
function generateReportHeader2($title, $empresa, $titulo, $logo = '', $fontSize = '12px') {
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