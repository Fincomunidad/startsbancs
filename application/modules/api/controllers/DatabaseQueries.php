<?php

class DatabaseQueries
{
    // Instancia de la clase de manejo de la BD
    private $base;
    private $esquema;

    public function __construct($base, $esquema)
    {
        $this->base = $base;
        $this->esquema = $esquema;
    }

    /**
     * Obtiene la información de todas las sucursales ordenadas por ID de sucursal de forma ascendente.
     *
     * @return array La información de todas las sucursales.
     */
    public function getSucursales()
    {
        $orderBy = array(array('campo' => 'public.sucursales.idsucursal', 'direccion' => 'asc'));
        $sucursales = $this->base->selectRecord("public.sucursales", "", "", "", "", "", "", $orderBy, "", "", true);

        return $sucursales;
    }

    /**
     * Obtiene los datos de una sucursal basándose en su identificador.
     * 
     * @param string $idSucursal El identificador de la sucursal.
     * @return array Los datos de la sucursal.
     */
    public function getSucursal($idSucursal)
    {
        // Consulta para obtener los datos de la sucursal
        $query = "SELECT idsucursal, nombre, domicilio, colonia, municipio, estado
                    FROM public.sucursales 
                    WHERE idsucursal = '" . $idSucursal . "'";

        // Ejecuta la consulta
        $sucursal = $this->base->querySelect($query, true);

        // Verifica si se encontraron datos de la sucursal
        if (empty($sucursal)) {
            throw new Exception("No se encontraron datos para la sucursal con el ID: $idSucursal");
        }

        // Si la sucursal es '01', establece el nombre como 'ZIMATLÁN'
        if ($idSucursal === '01') {
            $sucursal[0]['nombre'] = 'Zimatlán';
        }

        // Retorna los datos de la sucursal
        return $sucursal[0];
    }

    /**
     * Obtiene el nombre completo de un usuario a partir de su ID.
     *
     * @param int $idUsuario El ID del usuario del cual se desea obtener el nombre.
     * @return string El nombre completo del usuario.
     * @throws Exception Si no se encuentra ningún usuario con el ID especificado.
     */
    public function getUser($idUsuario)
    {
        // Previene posibles ataques de inyección SQL usando parámetros seguros
        $query = "SELECT first_name, last_name FROM security.users U WHERE U.id = " . $idUsuario;

        // Ejecuta la consulta SQL de forma segura
        $user = $this->base->querySelect($query, true);

        // Verifica si se encontró algún usuario con el ID especificado
        if (empty($user)) {
            throw new Exception("No se encontró ningún usuario con el ID: $idUsuario");
        }

        // Concatena el primer nombre y el apellido para obtener el nombre completo
        $nombreCompleto = $user[0]['first_name'] . ' ' . $user[0]['last_name'];

        return $nombreCompleto;
    }

    /**
     * Obtiene los datos de los niveles de crédito activos desde la base de datos.
     * @param object $base Objeto de conexión a la base de datos.
     * @return array|false Arreglo de datos de los niveles de crédito o false si hay un error.
     */
    function getNivelesData()
    {
        // Consulta SQL para recuperar los datos de los niveles de crédito activos
        $query = "SELECT 
                n1.idnivel,
                n1.nivel,
                n1.importe,
                n1.pf_capital as capital,
                n1.pf_aporte_sol as interes,
                (n1.pf_capital + n1.pf_aporte_sol) as total,
                n1.pf_garantia as garantia,
                (n1.pf_capital + n1.pf_aporte_sol + n1.pf_garantia) as pago_semanal,
                (n1.pf_garantia * n1.numero_pagos) as garantia_total,
                n1.numero_pagos,
                n1.fecha_inicio
            FROM niveles n1
            WHERE n1.fecha_fin IS NULL
            ORDER BY n1.nivel, n1.numero_pagos";

        // Ejecutar la consulta SQL y devolver los resultados
        try {
            return $this->base->querySelect($query, TRUE);
        } catch (Exception $e) {
            // Manejar cualquier excepción que ocurra durante la ejecución de la consulta
            error_log('Error al recuperar los datos de los niveles de crédito: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene los datos de una persona acreditada por su ID.
     *
     * @param int $idAcreditado El ID del acreditado.
     * @return array Los datos de la persona acreditada.
     * @throws Exception Si no se encuentran datos para el ID especificado.
     */
    public function getPersonaData($idAcreditado)
    {
        // Campos a seleccionar en la consulta
        $fields = array("celular", "curp", "rfc", "P.tipo", "fecha_nac", "tipovivienda", "vine", "direccion2", "P.idpersona");

        // Consulta preparada para obtener los datos de la persona acreditada
        $query = "SELECT " . implode(', ', $fields) . "
        FROM public.acreditado A
        INNER JOIN public.personas P ON A.idpersona = P.idpersona
        INNER JOIN public.persona_domicilio PD ON PD.idpersona = P.idpersona 
        WHERE A.idacreditado = " . $idAcreditado;

        // Ejecución de la consulta preparada de manera segura
        $personaData = $this->base->querySelect($query, true);

        // Verifica si se encontraron datos para el ID especificado
        if (empty($personaData)) {
            throw new Exception("No se encontraron datos para el ID: $idAcreditado");
        }

        // Devuelve los datos de la persona acreditada
        return $personaData[0];
    }

    public function getPersonaDataA($acreditadoid)
    {
        $fields = array("celular", "curp", "rfc", "fecha_nac", "tipovivienda", "vine", "direccion2", "P.idpersona");

        $personaData = $this->base->querySelect("SELECT " . implode(', ', $fields) . "
            FROM public.acreditado A
            INNER JOIN public.personas P ON A.idpersona = P.idpersona
            INNER JOIN public.persona_domicilio PD ON PD.idpersona = P.idpersona 
            WHERE A.acreditadoid = $acreditadoid", TRUE);

        if (!$personaData) {
            return null;
        }

        return $personaData[0];
    }

    /**
     * Obtiene los datos de un crédito dado su ID.
     *
     * @param int $idCredito El ID del crédito a recuperar.
     * @return array Los datos del crédito.
     * @throws Exception Cuando no se encuentra el crédito con el ID especificado.
     */
    public function getCreditData($idCredito)
    {
        // Campos a seleccionar de la vista de créditos
        $fields = array(
            "idcredito", "idacreditado", "idgrupo", "idsucursal", "fecha", "proy_nombre", "proy_descri", "proy_lugar", "nomcolmena", "nomgrupo",
            "idproducto", "idnivel", "nivel", "monto", "periodo",  "num_pagos", "idpagare", "idchecklist", "idejecutivo", "fecha_aprov", "usuario_aprov",
            "fecha_mov", "usuario", "idpersona", "nombre", "tipo", "edocivil", "edocivil_nombre", "sexo", "idactividad", "actividad_nombre", "direccion",
            "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "tasa", "tasa_mora"
        );

        $where = array("idcredito" => $idCredito);

        // Obtener los datos del crédito
        $creditData = $this->base->selectRecord($this->esquema . 'get_solicitud_credito', $fields, "", $where, "", "", "", "", "", "", TRUE);

        // Verificar si se encontraron datos del crédito
        if (!$creditData) {
            throw new Exception("No se encontró el crédito con ID: $idCredito");
        }

        return $creditData[0];
    }

    /**
     * Obtiene las tasas de interés mensuales, anuales y de mora asociadas a un crédito.
     * 
     * @param array $creditoData Los datos del crédito, incluyendo la tasa de interés y el tipo de producto.
     * @return array Un arreglo asociativo con las tasas calculadas.
     */
    public function obtenerTasas($creditData)
    {
        // Tasas para crédito individual
        if ($creditData['idproducto'] === '10') {
            $tasaMensual = $creditData['tasa'];
            $tasaMensualMora = $tasaMensual * 2;
            $tasaAnual = $tasaMensual * 12;
            $tasaAnualMora = $tasaAnual * 2;
        } else { // Tasas para crédito colmena
            $tasaMensual = $creditData['tasa'] / 12;
            $tasaMensualMora = $tasaMensual * 2;
            $tasaAnual = $creditData['tasa'];
            $tasaAnualMora = $tasaAnual * 2;
        }

        return array(
            'tasaMensual' => formatNumber($tasaMensual),
            'tasaMensualMora' => formatNumber($tasaMensualMora),
            'tasaAnual' => formatNumber($tasaAnual),
            'tasaAnualMora' => formatNumber($tasaAnualMora)
        );
    }

    /**
     * Obtiene los datos de amortizaciones de un crédito específico.
     *
     * @param int    $idcredito El ID del crédito del cual se obtendrán las amortizaciones.
     * @param string $esquema   El esquema de la base de datos donde se encuentran las amortizaciones.
     * 
     * @return array|false Un arreglo de datos de amortizaciones si se encuentran registros, o false si no hay datos.
     *                     En caso de error, lanza una excepción.
     * @throws Exception Si ocurre un error al recuperar los datos de amortizaciones.
     */
    public function getAmortizacionesData($idcredito, $esquema)
    {
        try {
            $fields = array("numero", "fecha_vence", "saldo_capital", "capital", "interes", "iva", "aportesol", "garantia", "total", "ajuste");
            $where = array("idcredito" => $idcredito);
            $order_by = array(array('campo' => 'numero', 'direccion' => 'asc'));

            $amortizaciones = $this->base->selectRecord($esquema . "amortizaciones", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);

            if ($amortizaciones === false) {
                throw new Exception("No se encontraron amortizaciones para el crédito con ID: " . $idcredito);
            }

            return $amortizaciones;
        } catch (Exception $e) {
            throw new Exception("Error al obtener datos de amortizaciones: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la última fecha de vencimiento para un crédito específico.
     *
     * @param int $idCredito El ID del crédito.
     * @return string|null La última fecha de vencimiento, o null si no se encuentra.
     * @throws Exception Si ocurre un error durante la ejecución de la consulta.
     */
    public function getUltimaFechaVencimiento($idCredito)
    {
        try {
            $query = "SELECT fecha_vence FROM {$this->esquema}amortizaciones WHERE idcredito = $idCredito ORDER BY fecha_vence DESC LIMIT 1";

            $credVencimiento = $this->base->querySelect($query, TRUE);

            if (empty($credVencimiento)) {
                throw new Exception("No se encontró la fecha de vencimiento para el crédito con ID: $idCredito");
            }

            return $credVencimiento[0];
        } catch (Exception $e) {
            // Manejar la excepción
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    /**
     * Obtiene los datos de emisión de créditos para un crédito específico.
     *
     * @param int $idcredito El ID del crédito.
     * @return array Los datos de emisión de créditos para el crédito especificado.
     * @throws Exception Si no se encuentra información para el ID de crédito proporcionado.
     */
    /*public function getEmisionCreditosByIdCredito($idcredito)
    {
        $fields = array("fecha", "idacreditado", "idcredito", "acreditado_nombre", "nivel", "monto", "cheque_ref", "idgrupo", "idcolmena", "colmena_numero", "colmena_nombre", "colmena_grupo", "promotor", "proy_observa", "garantia_monto");
        $where = array("idcredito" => $idcredito);
        $order_by = array(array('campo' => 'fecha', 'direccion' => 'desc'));
        $emisionData = $this->base->selectRecord($this->esquema . "v_sol_emision_creditos", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);

        // Verificar si se encontraron datos
        if (empty($emisionData)) {
            // Lanzar una excepción si no se encontró información para el idcredito especificado
            throw new Exception("No se encontró información para el crédito: $idcredito");
        }

        // Devolver el primer elemento del array de datos
        return $emisionData[0];
    }*/

    /**
     * Obtiene los datos de emisión de créditos para un crédito específico.
     *
     * @param int $idcredito El ID del crédito.
     * @return array Los datos de emisión de créditos para el crédito especificado.
     * @throws Exception Si no se encuentra información para el ID de crédito proporcionado.
     */
    public function getCreditoGarantia($idcredito)
    {
        // Obtener el monto de garantía utilizando la función de la base de datos
        $fields = array("get_monto_garantia_credito");
        $where = array("idcredito" => $idcredito);
        $montoGarantia = $this->base->selectRecord($this->esquema . "get_monto_garantia_credito", $fields, "", $where, "", "", "", "", "", "", TRUE);

        // Verificar si se encontraron datos
        if (empty($montoGarantia)) {
            // Lanzar una excepción si no se encontró el monto de garantía para el idcredito especificado
            throw new Exception("No se encontró el monto de garantía para el crédito: $idcredito");
        }

        // Devolver el monto de garantía
        return $montoGarantia[0];
    }

    /**
     * Obtiene los datos de emisión de créditos para una fecha y sucursal específicas.
     *
     * @param DateTime|string $fecha La fecha de emisión de créditos.
     * @param int $idsucursal El ID de la sucursal.
     * @return array Los datos de emisión de créditos para la fecha y sucursal especificadas.
     * @throws Exception Si no se encuentra información para la fecha y sucursal proporcionadas.
     */
    public function getEmisionCreditos($fecha, $idsucursal)
    {
        $fields = array("fecha", "idacreditado", "idcredito", "acreditado_nombre", "nivel", "monto", "cheque_ref", "idgrupo", "colmena_numero", "colmena_grupo", "promotor", "proy_observa", "garantia_monto");
        $where = array("fecha" => $fecha, "idsucursal" => $idsucursal);
        $order_by = array(array('campo' => 'promotor, colmena_numero, colmena_grupo', 'direccion' => 'asc'));

        $emisionData = $this->base->selectRecord($this->esquema . "v_sol_emision_creditos", $fields, "", $where, "", "", "", $order_by, "", "", TRUE);

        // Verificar si se encontraron datos
        if (!$emisionData) {
            // Lanzar una excepción si no se encontró información para la fecha y sucursal especificadas
            throw new Exception("No se encontró información para el dia: $fecha");
        }

        return $emisionData;
    }

    /**
     * Obtiene los datos de los creditos activos, filtrando por nivel y sucursal (cuando nivel es 0 muestra todos los niveles, cuando sucursal es null muestra todas las sucursales)
     *
     * @param int $idnivel El nivel consultado.
     * @param  $idsucursal El ID de la sucursal.
     * @return array Los datos de los creditos activos.
     */
    public function getCreditosActivos($idNivel, $idSucursal = null)
    {
        $conditions = array();

        if ($idNivel != 0) {
            $conditions[] = "nivel = " . $idNivel;
        }

        if (!is_null($idSucursal)) {
            $conditions[] = "idsucursal = '" . $idSucursal . "'";
        }

        $whereClause = (!empty($conditions)) ? "AND " . implode(" AND ", $conditions) : "";

        $query = "SELECT idsucursal, idcredito, fecha_dispersa::date, idacreditado, nombre, nivel, monto, num_pagos, periodo, grupo_numero, col_numero, col_nombre, promotor
                FROM " . $this->esquema . "rpt_credito_activo 
                WHERE NOT fecha_dispersa IS NULL " . $whereClause . " 
                ORDER BY nivel, fecha_dispersa";

        return $this->base->querySelect($query, TRUE);
    }

/**
 * Obtiene los datos de los aportes sociales, filtrando por fechas y tipo de movimiento.
 *
 * @param string $fecini Fecha de inicio en formato 'Y-m-d'.
 * @param string $fecfin Fecha de fin en formato 'Y-m-d'.
 * @param string $tipo El tipo de movimiento ('D' para ingresos, 'R' para retiros).
 * @return array Los datos de los aportes sociales.
 */
public function getAportesSociales($fecini, $fecfin, $tipo)
{
    $conditions = array();

    if (!empty($fecini) && !empty($fecfin)) {
        $conditions[] = "b.fecha::date >= '" . $fecini . "' AND b.fecha::date <= '" . $fecfin . "'";
    }

    if (!empty($tipo)) {
        $conditions[] = "b.movimiento = '" . $tipo . "'";
    }

    $whereClause = (!empty($conditions)) ? "WHERE " . implode(" AND ", $conditions) : "";

    $query = "SELECT a.idacreditado, a1.acreditado, b.fecha, 
                CASE WHEN b.movimiento = 'D' THEN b.importe ELSE 0 END AS ingreso, 
                CASE WHEN b.movimiento = 'R' THEN b.importe ELSE 0 END AS retiro 
            FROM fin.aporta_soc_p AS a 
            JOIN fin.aporta_social AS b ON b.idacreditado = a.idacreditado 
            JOIN public.get_acreditados AS a1 ON a1.acreditadoid = a.idacreditado 
            $whereClause
            ORDER BY b.fecha";

    return $this->base->querySelect($query, true);
}



    public function getEmisionCreditosYear($idsucursal, $fechaInicio, $fechaFin, $esquema)
    {
        $query = "SELECT fecha, idacreditado, idcredito, acreditado_nombre, nivel, monto, cheque_ref, idgrupo, colmena_numero, colmena_grupo, promotor, proy_observa 
            FROM " . $esquema . "v_sol_emision_creditos
            WHERE idsucursal='" . $idsucursal . "' and fecha between '" . $fechaInicio . "'::date and '" . $fechaFin . "'::date 
            ORDER BY fecha, idcredito asc";

        $emisionData = $this->base->querySelect($query, TRUE);
        return $emisionData;
    }

    public function getAcreditadoId($idacreditado)
    {
        $query = "SELECT acreditadoid
                FROM public.acreditado
                WHERE idacreditado = " . $idacreditado;

        $result = $this->base->querySelect($query, TRUE);

        if ($result) {
            return $result[0]['acreditadoid'];
        }

        return null; // o manejar el caso cuando no hay crédito anterior
    }

    public function getPreviousCredit2($acreditadoid, $idcredito, $esquema)
    {
        $query = "SELECT idcredito
                FROM " . $esquema . "creditos
                WHERE idacreditado = " . $acreditadoid . "
                        AND idcredito <> " . $idcredito . " and idcredito < " . $idcredito . "
                ORDER BY fecha DESC
                LIMIT 1";

        $result = $this->base->querySelect($query, TRUE);

        if ($result) {
            return $result[0]['idcredito'];
        }

        return null; // o manejar el caso cuando no hay crédito anterior
    }

    public function getPreviousCredit($acreditadoid, $idcredito)
    {
        $query = "SELECT idcredito
                FROM " . $this->esquema . "creditos
                WHERE idacreditado = " . $acreditadoid . "
                        AND idcredito <> " . $idcredito . " and idcredito < " . $idcredito . "
                        AND fecha_dispersa IS NOT NULL
                        ORDER BY idcredito DESC
                        LIMIT 1";

        $result = $this->base->querySelect($query, TRUE);

        if ($result) {
            return $result[0]['idcredito'];
        }

        return null; // o manejar el caso cuando no hay crédito anterior
    }

    public function getTotalAmortizations($idcredito)
    {
        $query = "SELECT SUM(garantia) AS total_garantia
                FROM " . $this->esquema . "amortizaciones
                WHERE idcredito = $idcredito";

        $result = $this->base->querySelect($query, TRUE);

        if ($result) {
            return $result[0]['total_garantia'];
        }

        return 0; // o manejar el caso cuando no hay amortizaciones
    }

    public function getLiquidaciones($idcredito, $garantia)
    {
        $query = "SELECT SUM(total + ajuste) AS total_ajustes
            FROM " . $this->esquema . "amortizaciones
            WHERE idcredito = $idcredito AND garantia = 0.0";

        $result = $this->base->querySelect($query, TRUE);
        $result = $result[0]['total_ajustes'] * -1 + $garantia;

        // Regresa el monto siempre y cuando sea mayor a 0, es decir, haya a favor de la socia
        if ($result > 0) {
            return $result;
        }

        return 0; // o manejar el caso cuando no hay ajustes
    }

    public function getMontoGarantiaLiquidacion($acreditadoid, $idcredito)
    {
        // Obtener el ID del crédito anterior
        $previousCreditId = $this->getPreviousCredit($acreditadoid, $idcredito);

        // Obtener el total de garantías del crédito anterior
        $totalGarantia = $this->getTotalAmortizations($previousCreditId);

        // Obtener el monto de liquidaciones
        $montoLiquidaciones = $this->getLiquidaciones($previousCreditId, $totalGarantia);

        return $montoLiquidaciones;
    }


    /* public function getTotalAmortizations($idcredito)
    {
        $query = "SELECT SUM(garantia) AS total_garantia
                FROM " . $this->esquema . "amortizaciones
                WHERE idcredito = $idcredito";

        $result = $this->base->querySelect($query, TRUE);

        if ($result) {
            return $result[0]['total_garantia'];
        }

        return 0; // o manejar el caso cuando no hay amortizaciones
    }

    public function getLiquidaciones($idcredito, $garantia)
    {
        $query = "SELECT SUM(total + ajuste) AS total_ajustes
            FROM " . $this->esquema . "amortizaciones
            WHERE idcredito = $idcredito AND garantia = 0.0";

        $result = $this->base->querySelect($query, TRUE);
        $result = $result[0]['total_ajustes'] * -1 + $garantia;

        // Regresa el monto siempre y cuando sea mayor a 0, es decir, haya a favor de la socia
        if ($result > 0) {
            return $result;
        }

        return 0; // o manejar el caso cuando no hay ajustes
    } */


    function getColmenasToVisit($idcolmena, $dia)
    {
        return $this->base->querySelect("
        SELECT c.idcolmena, c.idsucursal, c.numero, c.nombre, c.dia, c.idpromotor, c.empresa, g.numero AS numerogrupo
        FROM col.colmenas c
            JOIN security.users u ON c.idpromotor = u.id
            JOIN (
                SELECT idcolmena, col_grupo AS numero 
                FROM col.col_personas 
                WHERE idcolmena = " . $idcolmena . " 
                GROUP BY idcolmena, col_grupo
            ) AS g ON g.idcolmena = c.idcolmena 
        WHERE c.idcolmena = " . $idcolmena . " AND dia = " . $dia . " AND c.fechacierre IS NULL
        ORDER BY c.idpromotor, c.horainicio, g.numero", TRUE);
    }

    /**
     * Obtiene los datos de la colmena asociada a un grupo.
     *
     * @param int $idGrupo El ID del grupo del que se desea obtener los datos de la colmena.
     * @return array Los datos de la colmena.
     * @throws Exception Si no se encuentra información de la colmena.
     */
    public function getColmenaData($idGrupo)
    {
        $fields = array("idcolmena", "colmena_numero", "colmena_nombre", "idgrupo", "colmena_grupo", "grupo_nombre");
        $where = array("idgrupo" => $idGrupo);
        $colmenaData = $this->base->selectRecord($this->esquema . "get_colmena_grupo", $fields, "", $where, "", "", "", "", "", "", TRUE);

        if (!$colmenaData) {
            throw new Exception("No se encontró información de la colmena: $idGrupo");
        }

        return $colmenaData[0];
    }

    /**
     * Obtiene los datos del promotor asociado a una colmena.
     *
     * @param int $idColmena El ID de la colmena de la que se desea obtener los datos del promotor.
     * @return array Los datos del promotor asociado a la colmena.
     * @throws Exception Si no se encuentra información del promotor.
     */
    public function getColmenaPromotor($idColmena)
    {
        $fields = array("numero", "nombre", "promotor");
        $where = array("idcolmena" => $idColmena);
        $colmenaPromotor = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "", "", "", "", "", "", TRUE);

        if (!$colmenaPromotor) {
            throw new Exception("No se encontró información de la colmena: $idColmena");
        }

        return $colmenaPromotor[0];
    }

    /**
     * Obtiene el nombre del promotor.
     *
     * @param array           $cred      Datos del crédito.
     * @param DatabaseQueries $dbQueries Instancia de DatabaseQueries.
     *
     * @return string Nombre del promotor.
     */
    public function getPromotorNombre($cred, $dbQueries)
    {
        $promotor = $dbQueries->getColmenaPromotor($cred['idcolmena']);
        $promotorNombre = $promotor['promotor'];
        $usuario = $dbQueries->getUser($cred['usuario']);

        if ($cred['idproducto'] == 10 && $promotorNombre === 'NO ASIGNADO') {
            $promotorNombre = $usuario;
        }

        return $promotorNombre;
    }

    public function getColmenaHorario($sucursalId, $empresa, $idPromotor)
    {
        $query = "SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
            FROM col.get_colmena_horario('$sucursalId', '$empresa', $idPromotor) 
            WHERE numero > 0 AND (lunes IS NOT NULL OR martes IS NOT NULL OR miercoles IS NOT NULL OR jueves IS NOT NULL OR viernes IS NOT NULL OR sabado IS NOT NULL);";

        return $this->base->querySelect($query, TRUE);
    }

    public function getColmenaHorarioG($sucursalId, $idPromotor)
    {
        $query = "SELECT numero, nombre, ruta, hora, lunes, martes, miercoles, jueves, viernes, sabado 
                FROM col.get_colmena_horariog('$sucursalId', $idPromotor) 
                WHERE numero > 0 
                AND (lunes IS NOT NULL OR martes IS NOT NULL OR miercoles IS NOT NULL OR jueves IS NOT NULL OR viernes IS NOT NULL OR sabado IS NOT NULL);";

        return $this->base->querySelect($query, TRUE);
    }

    /**
     * Obtiene los datos del aval del grupo a partir de su ID.
     *
     * @param int $idaval El ID del aval del grupo del que se desea obtener los datos.
     * @return array Los datos del aval del grupo.
     */
    public function getAvalGrupoData($idaval)
    {
        $fields = array("nombre", $this->esquema . "get_cargo_grupo(cast(acreditadoid as integer)) as cargo");
        $where = array("acreditadoid" => $idaval);

        $avalGrupoData = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);

        return $avalGrupoData['0'];
    }

    /**
     * Obtiene los datos del aval de la colmena a partir de su ID.
     *
     * @param int $idaval El ID del aval de la colmena del que se desea obtener los datos.
     * @return array Los datos del aval de la colmena.
     */
    public function getAvalColmenaData($idaval)
    {
        $fields = array("nombre", $this->esquema . "get_cargo_colmena(cast(acreditadoid as integer)) as cargo");
        $where = array("acreditadoid" => $idaval);

        $avalData = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);

        return $avalData['0'];
    }

    public function getPresidentaGrupo($idgrupo)
    {
        $query = "SELECT C.idgrupo, idcargo1, idacreditado, acreditadoid, A.idsucursal, P.idpersona, nombre1, nombre2, apaterno, amaterno
            FROM col.grupo_cargo C
            INNER JOIN public.acreditado A ON A.acreditadoid = C.idcargo1
            INNER JOIN public.personas P ON P.idpersona = A.idpersona
            WHERE C.idgrupo = $idgrupo";

        $presidentaGrupoData = $this->base->querySelect($query, TRUE);

        return empty($presidentaGrupoData) ? null : $presidentaGrupoData[0];
    }



    public function getAcreditadoSolicitud($id, $opc)
    {
        $fields = array(
            "idpersona", "acreditadoid", "idacreditado", "idsucursal", "nombre", "tipo", "edocivil_nombre", "sexo", "idactividad", "direccion", "idcolmena"
        );

        if ($opc == 'a') {
            $where = array("idacreditado" => $id);
        } else {
            $where = array("idpersona" => $id);
        }

        $acreditado = $this->base->selectRecord($this->esquema . "get_acreditado_solicitud", $fields, "", $where, "", "", "", "", "", "", TRUE);

        return $acreditado[0];
    }

    public function getPersonaDomicilio($idPersona)
    {
        $fields = array("estado", "municipio", "colonia", "direccion1");
        $where = array("idpersona" => $idPersona);
        $domicilio = $this->base->selectRecord("public.v_persona_domicilio", $fields, "", $where, "", "", "", "", "", "", TRUE);
        return $domicilio[0];
    }

    /**
     * Obtiene el checklist asociado a un crédito.
     *
     * @param int $idcredito El ID del crédito del que se quiere obtener el checklist.
     * @return array El checklist asociado al crédito.
     */
    public function getCheckList($idcredito)
    {
        $fields = array("idcredito", "grupo", "documento", "requerido", "fecha");
        $where = array("idcredito" => $idcredito, "fecha IS NOT NULL" => NULL);
        $checklist = $this->base->selectRecord($this->esquema . "v_check_list", $fields, "", $where, "", "", "", "", "", "", TRUE);

        return $checklist;
    }

    public function getTotalComprometidoForCredit($credId, $acreditadoId)
    {
        $query = "SELECT sum(comprometido) as total FROM " . $this->esquema . "get_creditos_resumen2(" . $acreditadoId . ") where idcredito ='" . $credId . "'";
        $result = $this->base->querySelect($query, TRUE);

        if (!empty($result)) {
            return $result[0]['total'];
        } else {
            return 0;
        }
    }

    public function getAhorroAlta($idAcreditado)
    {
        $query = "SELECT a.fecha_alta, a.idsucursal, m.fecha
                FROM " . $this->esquema . "ahorros as a
                JOIN " . $this->esquema . "ahorros_mov as m ON a.idahorro = m.idahorro
                WHERE idproducto in ('01', '02') and idacreditado=" . $idAcreditado . "
                ORDER BY fecha LIMIT 1";

        $ahorro = $this->base->querySelect($query, TRUE);

        if (!empty($ahorro)) {
            $ahorro = $ahorro[0];
            $ahorro['fecha_alta'] = new DateTime($ahorro['fecha_alta']);
            return $ahorro;
        }

        return null;
    }
}
