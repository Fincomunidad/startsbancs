<?php
function table_col_horario( $data, $global)
{
    $columnas = ($global === '1') ? 4 : 3;
    $html = '<table style="width:100%" >';
    $html .= crearCabeceraTabla($columnas);
    $numero = 0;
    foreach ($data as $value) {
        $html .= generarFilaTabla($value, $numero, $global);
    }
    $html .= '</table>';
    return $html;
}

function crearCabeceraTabla($columnas)
{
    $html = '<tr style="height:20px; background:lightblue;">';
    $html .= '<th span=2 height="18px">RUTA</th>';
    $diasSemana = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
    foreach ($diasSemana as $dia) {
        $html .= '<th colspan="' . $columnas . '" align="center">' . $dia . '</th>';
    }
    $html .= '</tr>';
    return $html;
}

function generarFilaTabla($value, &$numero, $global)
{
    $html = '<tr>';
    if ($numero != $value['numero']) {
        $numero = $value['numero'];
        $html .= '<td style="border-bottom:0px;" align="center"><b>' . $value['nombre'] . '</b></td>';
        $fila = '1';
    } else {
        $html .= '<td style="border-bottom:0px; border-top:0px;"></td>';
        $fila = '';
    }
    $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
    foreach ($dias as $dia) {
        $html .= col_horario_dia($value[$dia], $fila, $global);
    }
    $html .= '</tr>';
    return $html;
}

function col_horario_dia($data, $fila, $global)
{
    $miHtml = '';
    $border = ($fila === '') ? "border-bottom:0px; border-top:0px;" : "border-bottom:0px;";
    
    if (!empty($data)) {
        $parts = explode('|', $data, 6);
        $nombre = $parts[1] . ' (' . $parts[4];
        if (sizeof($parts) > 5) {
            $nombre .= ', $' . number_format($parts[5], 2, '.', ',');
        }
        $nombre .= ')';
        
        $miHtml .= '<td align="right" style="width:15px; border-right:0px;' . $border . '">' . $parts[0] . '</td>';
        if ($global == '1') {
            $miHtml .= '<td align="right" style="width:5px; border-right:0px;' . $border . '">' . $parts[3] . '</td>';
        }
        $miHtml .= '<td style="border-left:0px; border-right:0px; ' . $border . '">' . $nombre . '</td>';
        $miHtml .= '<td style="width:15px; border-left:0px;' . $border . '">' . $parts[2] . '</td>';
    } else {
        $miHtml .= '<td style="width:15px; border-right:0px;' . $border . '"></td>';
        if ($global == '1') {
            $miHtml .= '<td style="width:5px; border-right:0px;' . $border . '"></td>';
        }
        $miHtml .= '<td style="border-left:0px; border-right:0px; ' . $border . '"></td>';
        $miHtml .= '<td style="width:15px; border-left:0px; ' . $border . '"></td>';
    }
    return $miHtml;
}
