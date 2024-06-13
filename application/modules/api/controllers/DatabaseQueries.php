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
	
	// 2024-04-12
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
        $query = "SELECT first_name, last_name FROM security.users U WHERE U.id = " . $idUsuario;

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
            "idcolmena", "idaval1", "idaval2", "fecha_entrega_col", "tasa", "tasa_mora", "acreditadoid"
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
	
	// 2024-04-12
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
	
	// 2024-04-17
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
	
	// 2024-04-17
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
	
	// 2024-05-06
	/**
     * Obtiene los datos de emisión de créditos para un crédito específico.
     *
     * @param int $idcredito El ID del crédito.
     * @return array Los datos de emisión de créditos para el crédito especificado.
     * @throws Exception Si no se encuentra información para el ID de crédito proporcionado.
     */
    public function getEmisionCreditosByIdCredito($idcredito)
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
    }
	
	// 2024-04-20
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
	
	//2024-05-15
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
	
	// 2024-04-17
	/**
     * Obtiene el nombre del promotor dependiendo el tipo de crédito.
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
	
	//2024-05-08
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
	
}