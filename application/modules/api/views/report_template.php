<?php

/**
 * Genera la parte inicial de un documento HTML para su uso en la creación de PDF.
 *
 * @param string $title El título del documento (por defecto 'Report').
 * @param string $styles Las reglas de estilo CSS adicionales a aplicar al documento (por defecto '').
 * @param string $fontSize El tamaño de fuente base para el documento (por defecto '12px').
 * @return string La parte inicial del documento HTML.
 */
function generateHead($title = 'Report', $styles = '', $fontSize = '12px')
{
    $head = '
	<!DOCTYPE html>
	<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>' . $title . '</title>
        <style>
            @page {
                margin: 1rem 1rem 1rem;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: ' . $fontSize . ';
                color: #000;
                background: #fff;
                text-align: justify;
                padding: 0px 40px;
            }
            .titulo{
                text-align: center;
                padding:5px 0px;
                font-size:18px;
                font-weight: bold;
                line-height: 50%;
            }
            .subtitulo {
                text-align: center;
                padding:3px 0px;
                font-size:12px;
                font-weight: bold;
            }	
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
            table.100p {
                width: 100%
            }
            .footer {
                position: fixed;
                bottom: 0;
                font-size: 11px;
                padding-left:40px;
                color: #73879C;

            }
            ' . $styles . '
        </style>
    </head>
    <body>
    ';

    return $head;
}

/**
 * Obtiene el código HTML para mostrar el logo asociado al esquema dado.
 *
 * @param string $esquema      El nombre del esquema.
 * @param string $logoDimension Dimensiones del logo en píxeles (por defecto: '60').
 * @return string              El código HTML para mostrar el logo, o una cadena vacía si no se encuentra ningún logo asociado al esquema.
 */
function getLogo($esquema, $logoDimension = '60')
{
    $logoPath = '';

    // Define los logos asociados a cada esquema
    $logos = [
        "fin." => "logofin.png",
    ];

    // Verifica si el esquema tiene un logo asociado
    if (array_key_exists($esquema, $logos)) {
        $logoPath = base_url("dist/img/" . $logos[$esquema]);
        return '<div style="top: -10px; position: absolute; float: right;"><img src="' . $logoPath . '" height="' . $logoDimension . 'px" alt=""></div>';
    }

    return $logoPath;
}

/**
 * Obtiene el subtitulo asociado al esquema dado.
 *
 * @param string $esquema El nombre del esquema.
 * @return string El subtitulo correspondiente al esquema.
 */
function getSubtitulo($esquema)
{
    // Define el subtitulo asociado a cada esquema
    $subtitulos = [
        "ban." => '<div class="subtitulo">PROGRAMA BANCOMUNIDAD</div>',
    ];

    // Verifica si el esquema tiene un subtitulo asociado
    return isset($subtitulos[$esquema]) ? $subtitulos[$esquema] : '<div class="subtitulo"></div>';
}

/**
 * Genera la dirección de las sucursales.
 *
 * @param array $sucursales El arreglo de sucursales.
 * @param string $esquema El esquema para determinar el formato de la dirección.
 * @return string La dirección de las sucursales en formato HTML.
 */
function generateDireccion($sucursales, $esquema)
{
    $direccion = '<table border="0" class="100p">';
    if ($esquema == "ban." && !empty($sucursales)) {
        $primerSucursal = $sucursales[0];
        $direccion .= '<tr>';
        $direccion .= '<td class="50p" align="center" style="padding: 3px">' . $primerSucursal['domicilio'] . ', ' . $primerSucursal['colonia'] . ', C.P. ' . $primerSucursal['codpostal'] . ', ' . $primerSucursal['municipio'] . ', Tel. ' . $primerSucursal['telefono1'] . '</td>';
        $direccion .= '</tr>';
    } else {
        foreach ($sucursales as $value) {
            $domicilio = $value['domicilio'];
            $colonia = $value['colonia'];
            $codpostal = $value['codpostal'];
            $municipio = $value['municipio'];
            $telefono = $value['telefono1'];

            // Formato de dirección
            $direccion .= '<tr>';
            $direccion .= '<td class="50p" align="center" style="padding: 3px">' . $domicilio . ', ' . $colonia . ', C.P. ' . $codpostal . ', ' . $municipio . ', Tel. ' . $telefono . '</td>';
            $direccion .= '</tr>';
        }
    }
    $direccion .= '</table>';

    return $direccion;
}

/**
 * Genera el encabezado con la información de la empresa y las sucursales.
 *
 * @param string $direccionSucursales La dirección de las sucursales en formato HTML.
 * @param string $empresa El nombre de la empresa.
 * @param string $subtitulo El subtítulo de la empresa.
 * @param string $logo El logo de la empresa en formato HTML.
 * @return string El encabezado con la información de la empresa y las sucursales en formato HTML.
 */
function generateHeaderSucursales($direccionSucursales, $empresa, $subtitulo, $logo)
{
    $htmlHeader = '<div class="titulo">' . $empresa . '</div>';
    $htmlHeader .= $subtitulo;
    $htmlHeader .= $logo;
    $htmlHeader .= '<div class="titulo-data" style="font-size:10px;">' . $direccionSucursales . '<hr></div>';

    return $htmlHeader;
}

/**
 * Genera un encabezado simple con el título proporcionado.
 *
 * @param string $title El título que se mostrará en el encabezado.
 * @return string El encabezado HTML generado.
 */
function generateSimpleHeader($title = '')
{
    // Validar que se proporciona un título
    if (empty($title)) {
        return ''; // Devolver una cadena vacía si no se proporciona título
    }

    $htmlHeader = '<div class="titulo">' . $title . '</div>';

    return $htmlHeader;
}

/**
 * Obtiene los datos comunes para el encabezado de los informes.
 * 
 * @param string $esquema El esquema (fin, ban, imp, ama).
 * @param DatabaseQueries $dbQueries La instancia de DatabaseQueries.
 * @return array Un arreglo con los datos del encabezado.
 */
function getCommonHeaderData($esquema, $dbQueries)
{
    $logo = getLogo($esquema);
    $subtitulo = getSubtitulo($esquema);
    $sucursales = $dbQueries->getSucursales();
    $direccionSucursales = generateDireccion($sucursales, $esquema);

    return compact('logo', 'subtitulo', 'direccionSucursales');
}

// 2024-04-12
/**
 * Genera el encabezado común para los informes.
 * 
 * @param string $title El título del informe.
 * @param string $logo El logo del esquema.
 * @param string $subtitulo El subtítulo del esquema.
 * @param string $direccionSucursales La dirección de las sucursales.
 * @return string El encabezado generado.
 */
function generateReportHeader($title, $logo, $subtitulo, $direccionSucursales, $esquema, $styles = '', $fontSize = '12px')
{
    $head = generateHead($title, $styles, $fontSize);
    $html = $head;
    $html .= generateHeaderSucursales($direccionSucursales, getEmpresa($esquema), $subtitulo, $logo);
    return $html;
}

// 2024-04-12
/**
 * Genera una tabla con espacio para una firmas.
 *
 * @param string $cargo El cargo de la persona.
 * @param string $nombre El nombre de la persona.
 * @return string La tabla HTML generada.
 */
function generarTabla1Firma($cargo, $nombre)
{
    $html = '
        <table style="width:100%; margin:5px 0 0 0;" border="0">
            <tr>
                <td></td>
                <td align="center" style="border-top: 1px solid" width="25%">' . $cargo . '</td>
                </tr>		
            <tr>
                <td></td>
                <td align="center"  width="25%">' . $nombre . '</td>
                <td></td>
            </tr>		
        </table>';

    return $html;
}

/**
 * Genera una tabla con espacio para dos firmas.
 *
 * @param string $cargo1 El cargo de la primera persona.
 * @param string $cargo2 El cargo de la segunda persona.
 * @param string $persona1 El nombre de la primera persona.
 * @param string $persona2 El nombre de la segunda persona.
 * @param bool $centrado Indica si se debe centrar la tabla.
 * @return string La tabla HTML generada.
 */
function generarTabla2Firmas($cargo1, $cargo2, $persona1, $persona2, $centrado = false)
{
    $styles = '"width:100%; margin: 55px 0 0 0;" border="0"';
    $tabla = '
        <tr>
            <td style="border-top: 1px solid" align="center" width="35%">' . $cargo1 . '</td>
            <td></td>
            <td style="border-top: 1px solid" align="center" width="35%">' . $cargo2 . '</td>
        </tr>
        <tr >
            <td align="center" width="25%">' . $persona1 . '</td>
            <td></td>
            <td align="center" width="25%">' . $persona2 . '</td>
        </tr>
    ';

    if ($centrado) {
        $styles = '"width:100%; margin: 55px auto;"  border="0"';
        $tabla = '
        <tr>
            <td></td>
            <td align="center" style="border-top: 1px solid" width="25%">' . $cargo1 . '</td>
            <td></td>
            <td align="center" style="border-top: 1px solid" width="25%">' . $cargo2 . '</td>
            <td></td>	
        </tr>		
        <tr>
            <td></td>
            <td align="center"  width="25%">' . $persona1 . '</td>
            <td></td>
            <td align="center"  width="25%">' . $persona2 . '</td>
            <td></td>
        </tr>	
        ';
    }

    $tabla = '
    <table style=' . $styles . '> '
        . $tabla .
        '			
    </table>
    ';

    return $tabla;
}

/**
 * Genera una tabla con tres filas, cada una conteniendo un cargo y una persona para firmar.
 *
 * @param string $cargo1 El cargo de la primera persona.
 * @param string $cargo2 El cargo de la segunda persona.
 * @param string $cargo3 El cargo de la tercera persona.
 * @param string $persona1 El nombre de la primera persona.
 * @param string $persona2 El nombre de la segunda persona.
 * @param string $persona3 El nombre de la tercera persona.
 * @return string La tabla HTML generada.
 */
function generarTabla3Firmas($cargo1, $cargo2, $cargo3, $persona1, $persona2, $persona3)
{
    $tabla = '
    <table style="width:100%; margin: 55px 0 0 0; font-size:12px;" border="0">			
        <tr >
            <td style="border-top: 1px solid" align="center" width="25%">' . $cargo1 . '</td>
            <td></td>
            <td style="border-top: 1px solid" align="center" width="25%">' . $cargo2 . '</td>
            <td></td>
            <td style="border-top: 1px solid" align="center" width="25%">' . $cargo3 . '</td>
        </tr>
        <tr >
            <td align="center" width="25%">' . $persona1 . '</td>
            <td></td>
            <td align="center" width="25%">' . $persona2 . '</td>
            <td></td>
            <td align="center" width="25%">' . $persona3 . '</td>
        </tr>				
    </table>
    ';

    return $tabla;
}

// 2024-04-12
/**
 * Genera un espacio vertical en HTML.
 *
 * @param string $altura Altura del espacio vertical en píxeles o cualquier otra unidad de medida válida en CSS.
 * @return string HTML generado para el espacio vertical.
 */
function generarEspacioVertical($altura = '10px')
{
    return '<div style="height: ' . $altura . ';"></div>';
}

/**
 * Genera el cierre del HTML.
 * @return string Cierre del HTML.
 */
function htmlEnd()
{
    // Cierre de la etiqueta </body> y </html>
    $html = '
    <div class="footer"><em>Impreso el ' . date('Y-m-d H:i:s') . '</em></div>
	</body>
	</html>
	';

    return $html;
}