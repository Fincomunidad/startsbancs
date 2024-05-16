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
 * Formatea un número con decimales y separador de miles.
 *
 * @param float  $number   El número que se formateará.
 * @param int    $decimals (Opcional) El número de decimales. Por defecto es 2.
 * 
 * @return string El número formateado como cadena de caracteres.
 */
function formatNumber($number, $decimals = 2)
{
    return number_format($number, $decimals, '.', ',');
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
    if (!$fecha instanceof DateTime) {
        // Intenta crear un objeto DateTime a partir del parámetro proporcionado
        try {
            $fecha = new DateTime($fecha);
        } catch (Exception $e) {
            // Lanza una excepción si no se puede crear un objeto DateTime
            throw new InvalidArgumentException('La fecha proporcionada no es válida.');
        }
    } else {
        // Si el parámetro ya es un objeto DateTime, lo asigna directamente
        $fecha = $fecha;
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

function convertirNumeroALetras($numero)
{
    $unidades = array('', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve');
    $decenas = array('', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa');
    $diez_a_veinte = array('diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve');

    $resultado = '';

    $numero = str_pad($numero, 2, '0', STR_PAD_LEFT);

    $unidad = (int) $numero % 10;
    $decena = (int) ($numero / 10) % 10;

    if ($numero == 0) {
        $resultado = 'cero';
    } elseif ($numero <= 9) {
        $resultado = $unidades[$unidad];
    } elseif ($numero == 10) {
        $resultado = 'diez';
    } elseif ($numero < 20) {
        $resultado = $diez_a_veinte[$unidad];
    } elseif ($numero <= 90) {
        $resultado = $decenas[$decena];
        if ($unidad > 0) {
            $resultado .= ' y ' . $unidades[$unidad];
        }
    }

    return $resultado;
}

/**
 * Obtiene el número de semana a partir de una fecha.
 *
 * @param DateTime|string $fecha La fecha de la cual se desea obtener el número de semana.
 * @return int El número de semana correspondiente a la fecha especificada.
 * @throws InvalidArgumentException Si el parámetro proporcionado no es una fecha válida.
 */
function getNumeroSemana($fecha)
{
    // Valida si el parámetro no es un objeto DateTime y lo convierte si es necesario
    if (!$fecha instanceof DateTime) {
        try {
            $fechaObj = new DateTime($fecha);
        } catch (Exception $e) {
            // Lanza una excepción si no se puede crear un objeto DateTime
            throw new InvalidArgumentException('La fecha proporcionada no es válida.');
        }
    } else {
        // Si el parámetro ya es un objeto DateTime, lo asigna directamente
        $fechaObj = $fecha;
    }

    // Obtiene el número de semana usando el formato ISO-8601 (1 a 53)
    $numeroSemana = $fechaObj->format("W");

    // Devuelve el número de semana
    return $numeroSemana;
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
 * Calcula el número de días entre dos fechas.
 *
 * @param string $fechaInicio La fecha de inicio en formato 'YYYY-MM-DD'.
 * @param string $fechaFin    La fecha de fin en formato 'YYYY-MM-DD'.
 * 
 * @return int El número de días entre las dos fechas.
 */
function calcularDiasEntreFechas($fechaInicio, $fechaFin)
{
    // Convertir las fechas a objetos DateTime para facilitar el cálculo
    $fechaInicioObj = new DateTime($fechaInicio);
    $fechaFinObj = new DateTime($fechaFin);

    // Calcular la diferencia entre las fechas
    $diferencia = $fechaInicioObj->diff($fechaFinObj);

    // Obtener el número total de días de diferencia
    $dias = $diferencia->days;

    return $dias;
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

// obtiene la primera letra de la empresa
function getEmpresaCode($esquema)
{
    switch ($esquema) {
        case "fin.":
            return 'F';
        case "ban.":
            return 'B';
        case "imp.":
            return 'I';
        default:
            return '';
    }
}

/* function getEsquemaByEmpresa($empresa)
{
    switch ($empresa) {
        case "B":
            return 'ban.';
        case "I":
            return 'imp.';
        default:
            return 'fin.';
    }
} */