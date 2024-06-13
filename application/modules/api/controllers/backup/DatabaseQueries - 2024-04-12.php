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
	
}