<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class CarteraV1 extends BaseV1 {
	public $esquema;
	public function __construct()
	{
		parent::__construct();
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
        $this->load->library('form_validation');
//		$this->load->helper(array('form','template'));
		$this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');
		$this->sucursal = $idsuc = $this->session->userdata('sucursal_id');
		
	}


	public function getProductos_get(){
		$query = "select idproducto as value, nombre as name, tipo as idcuenta from productos where activo = true";
		$productos = $this->base->querySelect($query, TRUE);
		if ($productos) {

			$query = "select idparentesco as value, nombre as name from parentesco where menor ='1' order by idparentesco";
			$paren = $this->base->querySelect($query, TRUE);
	
						
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"catproductos" => $productos,
				"catparentescos" => $paren
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro(s) inexistente(s)!",
				"catproductos" => [],
				"catparentescos" => []
			);
		}
		$this->returnData($data);
	}


	public function getCuentasAcre_get(){
		$idacreditado = $this->uri->segment(4);
		$query = "select acreditado as nombre, acreditadoid, lock_cuenta from public.get_acreditados where idacreditado =".$idacreditado;
		$acre = $this->base->querySelect($query, TRUE);
		$id = 0;
		if ($acre){
			$id = $acre[0]['acreditadoid'];
		}
		$query = "select a.idahorro, a.numero_cuenta, b.nombre || case when not m.menor is null then ' (' || m.menor || ') ' || m.curp  else '' end as  nombre, a.fecha_alta::date from ".$this->esquema."ahorros as a join public.productos as b on b.idproducto = a.idproducto 
					left join public.get_menores as m on m.idmenor = a.idmenor join public.acreditado as c on c.acreditadoid = a.idacreditado  where a.idacreditado =".$id.' order by a.numero_cuenta';
		$cuentas = $this->base->querySelect($query, TRUE);
		if ($cuentas) {
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"acre" => $acre,
				"catcuentas" => $cuentas
				);
		} else {
			if ($acre){
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>'Registro obtenido correctamente!',
					"acre" => $acre,
					"catcuentas" => [],
					);
				
			}else {
				$data = array("status"=>"ERROR",
					"code" => 404,
					"message"=>"Registro(s) inexistente(s)!",
					"catcuentas" => [],
					"acre" => []);
				
			}
  		}
		$this->returnData($data);
	}

	public function getCuentaNew_get(){
		$idacreditado = $this->input->get('acreditadoid');
		$idproducto = $this->input->get('idproducto');

		$query =  "select * from ".$this->esquema."ahorros join productos on productos.idproducto = ahorros.idproducto where idacreditado =".$idacreditado." and productos.idproducto ='".$idproducto."' order by numero_cuenta desc";
		$acre = $this->base->querySelect($query, TRUE);	
		if ($acre){
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"acre" => $acre,
				);
		}else {

			// busca el idproducto 
			$query =  "select tipo as numero_cuenta from public.productos where idproducto ='".$idproducto."'";
			$acre = $this->base->querySelect($query, TRUE);	

			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Registro(s) inexistente(s)!",
				"acre" => $acre);
		}
		$this->returnData($data);
	}

	public function add_cuentaho_post() {
		$idacreditado = $this->uri->segment(4);
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$valores['idacreditado'] = $idacreditado;
		$valores['numero_cuenta'] = $valores['numero_cuentanew'];


			//Carga de los modelos 
			$this->load->model('Ahorros_model','ahorro');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$this->form_validation->set_data( $datos );
			//Busca sucursal base del acreditado 
			$sucu = $this->base->querySelect("Select idsucursal from public.acreditado where acreditadoid = ".$datos['idacreditado'], true);
			$sucursal = $datos['idsucursal'];
			if ($sucu) {
				$sucursal = $sucu[0]['idsucursal'];
			}
			//Valida las reglas 
			if ($this->form_validation->run('ahorros_put') == TRUE) {
				$updatetrans = $this->ahorro->transaction($datos, $sucursal);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}



	}


	
	public function lock_cuentaho_post() {
		$idacreditado = $this->uri->segment(4);
		$status = $this->uri->segment(5);
		$user = $this->ion_auth->user()->row()->id;
		$this->load->model('Ahorros_model','ahorro');
		$updatetrans = $this->ahorro->lockcuentas($idacreditado, $status, $user);
		$this->validaCode($updatetrans);
	}



	public function getNameAcre_get(){
		$idacreditado = $this->uri->segment(4);
		$query =  "select * from public.get_acreditados where idacreditado =".$idacreditado;
		$acre = $this->base->querySelect($query, FALSE);	
		$this->returnData($acre);

	}


      public function get_acreditados_get() {
  			$idsuc = $this->session->userdata('sucursal_id');
            $fields = array("idpersona as value", "nombre || ' - ' || idpersona as name",);
            $where = array("idsucursal"=>$idsuc);
            $order_by = array(array('campo'=> 'nombre', 'direccion'=>	'asc'));
            $record = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            if ($record) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
                        "record"=>$record,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registros no encontrada!",
			    "record" => []);
		}
		$this->returnData($data);
	}
      


	
/*
* Solicitud  
*/
	public function solcreditogen_post() {
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$fecha = explode("/", $valores['fechaalta']); 
		$valores['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
		$fecha = explode("/", $valores['fecha_nac']); 
		$valores['fecha_nac'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
        $validation = "solingresogen_put";
        if ( $valores["paisnac"] != "MEX" ) {
            $validation = "solingresogensedo_put";
        }
		$this->insertData('persona', $valores, $validation);
	}



	public function solcreditogen_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$where = array('idpersona' => $valores['idpersona']);
		$fecha = explode("/", $valores['fechaalta']); 
		$valores['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;		
		$fecha = explode("/", $valores['fecha_nac']); 
		$valores['fecha_nac'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
        $validation = "solingresogen_put";
        if ( $valores["paisnac"] != "MEX" ) {
            $validation = "solingresogensedo_put";
        }
		$this->updateData('persona', $valores, $validation, $where, false);
	}


	public function solcreditodom_post() {
		$idpersona = $this->uri->segment(4);
		$adicionDatos = array('idpersona' => $idpersona);
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$fecha = explode("/", $valores['fechaaltad']); 
		$valores['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
		$this->insertData('domicilio', $valores, 'solingresodom_put', $adicionDatos);
	}

	public function solcreditodom_put() {
		$idpersona = $this->uri->segment(4);
		$where = array('idpersona' => $idpersona);
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$fecha = explode("/", $valores['fechaaltad']); 
		$valores['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;

		$this->updateData('domicilio', $valores, 'solingresodom_put', $where, false, $where);
	}



	public function solcreditoben_delete() {
		$idpersona = $this->uri->segment(4);
		$idbeneficario = $this->delete('idbeneficiario')?$this->delete('idbeneficiario', TRUE):0;
		$where = array('idpersona' => $idpersona, 'idbeneficiario' => $idbeneficario);
		$this->deleteData('public.persona_ben', $where);

	}

	public function solcreditoben_get() {
		$idpersona = $this->uri->segment(4);
		$ben = $this->getBeneficiarios($idpersona);

		if ($ben) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Beneficiarios obtenidos",
				"ben" => $ben,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

	public function solcreditoben_post() {
		$idpersona = $this->uri->segment(4);
		//Carga de los modelos 
		$this->load->model('persona_model','persona');
		$this->load->model('persona_ben_model','beneficiario');
		$this->load->model('domicilio_model','domicilio');
		//obtencion de los campos del formulario enviado
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('solingresoben_put') == TRUE) {
			//genera la fecha_nac en base a rfc_ben
			$rfcben = $datos['rfc_ben'];
			$dat = substr($rfcben,8,2).'/'.substr($rfcben,6,2).'/'.substr($rfcben,4,2);
			$fec = date('Y/m/d',strtotime($dat));
			if (validateDate($fec,'Y/m/d') == true) {
				$fec_nac = date('Y-m-d',strtotime($dat));
			}else {
				$fec_nac = NULL;
			}
			//Inserta el beneficiario como persona
			$fecha = explode("/", $datos['fechaaltab']); 
			$datos['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
			$datosbene = array('fechaalta' => $datos['fechaaltab'],
						'tipo' => 'F',
						'nombre1' => $datos['nombre1_ben'],
						'nombre2' => $datos['nombre2_ben'],
						'apaterno' => $datos['apaterno_ben'],
						'amaterno' => $datos['amaterno_ben'],
						'aliaspf' => $datos['aliaspf_ben'],
						'sexo' => $datos['sexo_ben'],
						'celular' => $datos['telefono_ben'],
						'rfc' => $rfcben,
						'fecha_nac' => $fec_nac
					);

			//Busca por RFC si existe en personas en caso que exista no va agregarlo solo asignarlo 
			// a la tabla de benficarios
			$persona = $this->base->querySelect("select idpersona, rfc from public.personas where RFC ='".$rfcben."'", TRUE);
	   	    if ($persona) {
				//Actualiza datos 
				$fecnow =date("Y-m-d");
				$datosbenetab = array('idpersona' => $idpersona,
				'fecha' => $fecnow,
				'idbeneficiario' => $persona[0]['idpersona'],
				'idparentesco' => $datos['idparentesco'],
				'porcentaje' => $datos['porcentaje']
				);

				//Asigna valores en la clase		
				$beneficiariotab = $this->beneficiario->set_datos($datosbenetab);
			// Inserta el beneficiario en la tabla persona_ben 
				$insertarbentab =  $this->beneficiario->insertar($beneficiariotab);
				$this->validaCode($insertarbentab);


			}else{

				//Asigna valores en la clase de persona (beneficiario)
				$beneficiario = $this->persona->set_datos($datosbene);
				// Inserta el beneficiario en la tabla persona
				$insertarben =  $this->persona->insertar($beneficiario);
				if ($insertarben['code'] == 200) {
					// asigna valores en el array para agregar a la tabla relacion de beneficiario-persona
					$fecnow =date("Y-m-d");
					$datosbenetab = array('idpersona' => $idpersona,
							'fecha' => $fecnow,
							'idbeneficiario' => $insertarben['insert_id'],
							'idparentesco' => $datos['idparentesco'],
							'porcentaje' => $datos['porcentaje']
							);

					//Asigna valores en la clase		
					$beneficiariotab = $this->beneficiario->set_datos($datosbenetab);
				// Inserta el beneficiario en la tabla persona_ben 
					$insertarbentab =  $this->beneficiario->insertar($beneficiariotab);
					
				$benedom = array('fechaalta' => $fecnow);
				$benedom['fechaalta'] = $fecnow;
				$benedom['direccion1'] = $datos['direccion1b'];
				$benedom['noexterior'] = $datos['noexteriorb'];
				$benedom['nointerior'] = $datos['nointeriorb'];
				$benedom['direccion2'] = $datos['direccion2b'];
				$benedom['idestado'] = $datos['idestadob'];
				$benedom['idmunicipio'] = $datos['idmunicipiob'];
				$benedom['idcolonia'] = $datos['idcoloniab'];
				$benedom['cp'] = $datos['cpb'];
				$benedom['ciudad'] = $datos['ciudadb'];
				$benedom['tiempo'] = $datos['tiempob'];
				$benedom['telefono'] = $datos['telefonob'];

				$where = array('idpersona' => $insertarben['insert_id']);
				$adicionDatos = array('idpersona' => $insertarben['insert_id'], 'fechaalta' => $fecnow);
				$this->insertData('domicilio', $benedom, 'solingresodom_put', $adicionDatos);




				

				$this->validaCode($insertarbentab);

				}else {
					$this->validaCode($insertarben);
				}
			}
		} else {
			$this->validaForm();
		}
		
	}



	public function solcreditoben_put() {
		$idpersona = $this->uri->segment(4);
		//Carga de los modelos 
		$this->load->model('persona_model','persona');
		$this->load->model('persona_ben_model','beneficiario');
		$this->load->model('domicilio_model','domicilio');
		//obtencion de los campos del formulario enviado
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');

		$fecha = explode("/", $datos['fechaaltab']); 
		$datos['fechaalta'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;

//		$datos['fechaalta'] = $datos['fechaaltab'];
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('solingresoben_put') == TRUE) {
			//genera la fecha_nac en base a rfc_ben
			$rfcben = $datos['rfc_ben'];
			$dat = substr($rfcben,8,2).'/'.substr($rfcben,6,2).'/'.substr($rfcben,4,2);
			$fec = date('Y/m/d',strtotime($dat));

			if (validateDate($fec,'Y/m/d') == true) {
				$fec_nac = date('Y-m-d',strtotime($dat));
			}else {
				$fec_nac = NULL;
			}
			//Actualiza el beneficiario como persona
			$datosbene = array('fechaalta' => $datos['fechaalta'],
						'tipo' => 'F',
						'nombre1' => $datos['nombre1_ben'],
						'nombre2' => $datos['nombre2_ben'],
						'apaterno' => $datos['apaterno_ben'],
						'amaterno' => $datos['amaterno_ben'],
						'aliaspf' => $datos['aliaspf_ben'],
						'sexo' => $datos['sexo_ben'],
						'celular' => $datos['telefono_ben'],
						'rfc' => $rfcben,
						'fecha_nac' => $fec_nac
					);
			//Asigna valores en la clase de persona (beneficiario)		
			$beneficiario = $this->persona->set_datos($datosbene);

			// Valida si el $idpersona == $datos['idpersonaben'] 
			// Lo agrega como beneficiario ya que al importar los datos
			//se integro el mismo como beneficario 
			// en caso contrario se actualiza 

/*			
			if ($idpersona == $datos['idpersonaben']) {
				//Asigna valores en la clase de persona (beneficiario)
				$beneficiario = $this->persona->set_datos($datosbene);
				// Inserta el beneficiario en la tabla persona
				$updateben =  $this->persona->insertar($beneficiario);
				$idPersonaBen = $updateben['insert_id'];
			}else {
*/				
				// actualiza el beneficiario en la tabla persona
				$where = array('idpersona' => $datos['idpersonaben']);
				$updateben =  $this->persona->update($beneficiario, $where, false);
				$idPersonaBen = $datos['idpersonaben'];
//			}
			
			if ($updateben['code'] == 200) {
				// asigna valores en el array para agregar a la tabla relacion de beneficiario-persona
				$fecnow =date("Y-m-d");
				$datosbenetab = array('idpersona' => $idpersona,
						'fecha' => $fecnow,
						'idbeneficiario' => $idPersonaBen,
						'idparentesco' => $datos['idparentesco'],
						'porcentaje' => $datos['porcentaje']
						);
				//Asigna valores en la clase		
				$beneficiariotab = $this->beneficiario->set_datos($datosbenetab);
				$where = array('idpersona' => $idpersona,
							   'idbeneficiario' => $datos['idpersonaben']);
			// update el beneficiario en la tabla persona_ben 
				$updatebentab =  $this->beneficiario->update($beneficiariotab, $where, false);



				// update datos de domicilio 
				$benedom = array('campo' => 'opc');
				$benedom['fechaalta'] = $fecnow;
				$benedom['direccion1'] = $datos['direccion1b'];
				$benedom['noexterior'] = $datos['noexteriorb'];

				$benedom['nointerior'] = $datos['nointeriorb'];
				$benedom['direccion2'] = $datos['direccion2b'];

				$benedom['idestado'] = $datos['idestadob'];
				$benedom['idmunicipio'] = $datos['idmunicipiob'];
				$benedom['idcolonia'] = $datos['idcoloniab'];
				$benedom['cp'] = $datos['cpb'];
				$benedom['ciudad'] = $datos['ciudadb'];
				$benedom['tiempo'] = $datos['tiempob'];
				$benedom['telefono'] = $datos['telefonob'];
				$benedom['fechaalta'] = $fecnow;

				//Busca si existe en domicilio lo actualiza sino agrega
				$query =  "select idpersona from public.persona_domicilio where idpersona ='".$idPersonaBen."'";
				$domi = $this->base->querySelect($query, TRUE);	

				if ($domi == []) {
					$adicionDatos = array('idpersona' => $idPersonaBen, 'fechaalta' => $fecnow);
					$this->insertData('domicilio', $benedom, 'solingresodom_put', $adicionDatos);
				}else {
					$where = array('idpersona' => $idPersonaBen);
					$this->updateData('domicilio', $benedom, 'solingresodom_put', $where, false, $where);
				}
	
				$this->validaCode($updatebentab);
			}else {
				$this->validaCode($updateben);
			}
		} else {
			$this->validaForm();
		}
	}




	public function solcredito_get() {		
		$idpersona = $this->uri->segment(4);
		$where = array("public.personas.idpersona" => $idpersona);
		$solcre = $this->base->selectRecord("public.personas", "", "", $where, "","", "", "", "","", TRUE);

		$tabla = "public.persona_domicilio";
		$where = array("public.persona_domicilio.idpersona" => $idpersona);
		$dom = $this->base->selectRecord("public.persona_domicilio", "", "", $where, "","", "", "", "","", TRUE);

		$colonias = array();
		$cp = array();
		if ($dom){
			$idmuni = $dom[0]['idmunicipio'];
			$idcol = $dom[0]['idcolonia'];

			$where = array("c_mnpio" => $idmuni);
			$colonias = $this->base->selectRecord("public.localidades", "id_asenta_cpcons as value, d_asenta as name", "", $where, "","", "", "", "","", TRUE);

			$where = array("c_mnpio" => $idmuni, "id_asenta_cpcons" => $idcol);
			$cp = $this->base->selectRecord("public.localidades", "d_cp as value, d_cp as name", "", $where, "","", "", "", "","", TRUE);
		}

		$ben = $this->getBeneficiarios($idpersona);
		if ($solcre) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
				"persona"=> $solcre,
				"dom" => $dom,
				"ben" => $ben,
				"col" => $colonias,
				"cp" => $cp
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

	public function solcrefind_get(){
		$nombre = strtoupper($_GET["q"]);
		$query = "select idsucursal, idpersona, CASE WHEN p.tipo::text = 'M'::text THEN p.cia::text ELSE ((COALESCE(p.nombre1, ''::character varying)::text || 
			CASE WHEN btrim(COALESCE(p.nombre2, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.nombre2::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.apaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.apaterno::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.amaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.amaterno::text, ''::character varying::text) ELSE ''::text END END AS nombre, 
			rfc from public.personas as p where  CASE WHEN p.tipo::text = 'M'::text THEN p.cia::text
			ELSE ((COALESCE(p.nombre1, ''::character varying)::text || CASE WHEN btrim(COALESCE(p.nombre2, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.nombre2::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.apaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.apaterno::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.amaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.amaterno::text, ''::character varying::text) ELSE ''::text END END like '%".$nombre."%' limit 8";

		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}


	public function solcreacre_get(){
		$nombre = strtoupper($_GET["q"]);
		$query ="select p.idsucursal, p.idpersona,  CASE WHEN p.tipo::text = 'M'::text THEN p.cia::text ELSE ((COALESCE(p.nombre1, ''::character varying)::text || 
			CASE WHEN btrim(COALESCE(p.nombre2, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.nombre2::text, ''::character varying::text) ELSE ''::text END) || 
			CASE WHEN btrim(COALESCE(p.apaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.apaterno::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.amaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.amaterno::text, ''::character varying::text) ELSE ''::text END END AS nombre, 
			b.idacreditado, c.colmena_numero || ' ' || c.colmena_nombre as nombrecol, c.grupo_nombre  from public.personas as p  left join public.acreditado as b on p.idpersona = b.idpersona  left join 
			".$this->esquema."get_colmena_grupo as c on c.idgrupo = b.idgrupo  WHERE b.inhabilitado = FALSE AND  CASE WHEN p.tipo::text = 'M'::text THEN p.cia::text ELSE ((COALESCE(p.nombre1, ''::character varying)::text ||
			CASE WHEN btrim(COALESCE(p.nombre2, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.nombre2::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.apaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.apaterno::text, ''::character varying::text) ELSE ''::text END) ||
			CASE WHEN btrim(COALESCE(p.amaterno, ''::character varying)::text) <> ''::text THEN COALESCE(' '::text || p.amaterno::text, ''::character varying::text) ELSE ''::text END END like '%".$nombre."%' limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);	
	}


	public function altasocio_get(){
		$idpersona = $this->uri->segment(4);
		$idsocio = $this->uri->segment(5);
		if (!is_numeric($idpersona) || !is_numeric($idsocio) ){
		    $this->returnCode();
		}else {
   			$this->findSocioSol($idpersona, $idsocio, TRUE);
		}
	}


	/*
	* Alta de socio asignando su numero de pagaré
	*/
	public function altasocio_post(){

			$valores = $this->post('data')?$this->post('data', TRUE):array();
			$where = array("public.persona_domicilio.idpersona" => $valores['idpersona']);
			$dom = $this->base->selectRecord("public.persona_domicilio","" , "" , $where, "","", "", "", "","", TRUE);
			if ($dom) {

				//Carga de los modelos 
				$this->load->model('Acreditado_model','acredita');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('altasocio_post') == TRUE) {
					$updatetrans = $this->acredita->transaction($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}


			}else {
				$data = array("status"=>"ERROR",
					"code" => 404,
					"newtoken"=>$this->security->get_csrf_hash(),
					"message"=>"Solicitud incompleta, faltan datos del domicilio!");
				$this->returnData($data);
			}






/*
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$where = array("public.persona_domicilio.idpersona" => $valores['idpersona']);
		$dom = $this->base->selectRecord("public.persona_domicilio","" , "" , $where, "","", "", "", "","", TRUE);
		if ($dom) {
			$this->insertData('acreditado', $valores, 'altasocio_post');
		}else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Solicitud incompleta, faltan datos del domicilio!");
			$this->returnData($data);
		}
*/		
	}





	/*
	* Actualizacion de socio asignando su numero de pagaré
	*/
	public function altasocio_put(){
			$valores = $this->put('data')?$this->put('data', TRUE):array();
			$where = array("public.persona_domicilio.idpersona" => $valores['idpersona']);
			$dom = $this->base->selectRecord("public.persona_domicilio","" , "" , $where, "","", "", "", "","", TRUE);
			if ($dom) {
				//Carga de los modelos 
				$this->load->model('Acreditado_model','acredita');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$this->form_validation->set_data( $datos );
				$datos['idgrupo'] =  $this->put('idgrupoc');				
				//Valida las reglas 
				if ($this->form_validation->run('altasocio_put') == TRUE) {
					$updatetrans = $this->acredita->transupdate($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}
			}else {
				$data = array("status"=>"ERROR",
					"code" => 404,
					"newtoken"=>$this->security->get_csrf_hash(),
					"message"=>"Solicitud incompleta, faltan datos del domicilio!");
				$this->returnData($data);
			}

	}




	public function findSocioSol($idpersona, $idsocio, $response){
		$tabla = "public.acreditado";
/*		
		$join = array('public.personas' => $tabla.'.idpersona = public.personas.idpersona', 
						$this->esquema.'get_colmena_grupo' => $tabla.'.idgrupo = '.$this->esquema.'get_colmena_grupo.idgrupo' );
		if ($idpersona != '0'){
			$where = array($tabla.".idpersona" => $idpersona);
		}else {
			$where = array($tabla.".idacreditado" => $idsocio);
		}
		$socio = $this->base->selectRecord($tabla, $tabla.".*, ".$this->esquema."get_colmena_grupo.*", $join, $where, "","", "", "", "","", TRUE);
*/


		if ($idpersona != '0'){
			$where = $tabla.".idpersona=".$idpersona;
		}else {
			$where = $tabla.".idacreditado=".$idsocio;
		}
		$query ="select public.acreditado.*,".$this->esquema."get_colmena_grupo.* from public.acreditado left join public.personas on public.acreditado.idpersona = public.personas.idpersona 
			left join ".$this->esquema."get_colmena_grupo on public.acreditado.idgrupo = ".$this->esquema."get_colmena_grupo.idgrupo where ".$where;
		$socio = $this->base->querySelect($query, TRUE);

		if ($socio) {
		   $idpersona = $socio[0]['idpersona'];
		   $mensaje="Socio obtenido correctamente";
		}else {
		   $mensaje="Solicitud obtenida correctamente";
		}
/*
		$select ="public.personas.*, public.estados.estado as estadonac, public.edocivil.nombre as estadocivil, public.escolaridad.nombre as escolaridadc, 
				case when public.personas.sexo='F' then 'Femenino' else 'Masculino' end as sexoc, public.actividades.nombre as actividad";
		$where = array("public.personas.idpersona" => $idpersona);
		$join = array('public.estados' => 'public.estados.idestado = public.personas.edonac',
				'public.edocivil' => 'public.edocivil.idedocivil = public.personas.edocivil',
				'public.escolaridad' => 'public.escolaridad.idescolaridad = public.personas.escolaridad',
				'public.actividades' => 'public.actividades.idactividad = public.personas.idactividad'
        );
		$solcre = $this->base->selectRecord("public.personas", $select, $join, $where, "","", "", "", "","", TRUE);
*/
		$query ="select public.personas.*, public.estados.estado as estadonac, public.edocivil.nombre as estadocivil, public.escolaridad.nombre as escolaridadc, 
		case when public.personas.sexo='F' then 'Femenino' else 'Masculino' end as sexoc, public.actividades.nombre as actividad from public.personas left join
		 public.estados on public.estados.idestado = public.personas.edonac 
		left join public.edocivil on public.edocivil.idedocivil = public.personas.edocivil left join public.escolaridad on public.escolaridad.idescolaridad = 
		public.personas.escolaridad left join public.actividades on public.actividades.idactividad = public.personas.idactividad where public.personas.idpersona =".$idpersona;
		$solcre = $this->base->querySelect($query, TRUE);

		$select ="public.persona_domicilio.*, public.estados.estado, public.localidades.d_mnpio as municipio, public.localidades.d_asenta as colonia";
		$where = array("public.persona_domicilio.idpersona" => $idpersona);
		$join = array('public.estados' => 'public.estados.idestado = public.persona_domicilio.idestado',
			'public.localidades' => 'public.localidades.id_asenta_cpcons = public.persona_domicilio.idcolonia and public.localidades.d_cp = public.persona_domicilio.cp '
		);

		
		
		$dom = $this->base->selectRecord("public.persona_domicilio", $select, $join, $where, "","", "", "", "","", TRUE);

		if (!is_array($dom)) {
			$dom = [];
		}

		$select ="public.personas.*, public.parentesco.nombre as parentesco, public.persona_ben.porcentaje";
		$where = array("public.persona_ben.idpersona" => $idpersona);
		$join = array('public.parentesco' => ' public.persona_ben.idparentesco = public.parentesco.idparentesco',
			'public.personas' => 'public.persona_ben.idbeneficiario = public.personas.idpersona'
		);
		$ben = $this->base->selectRecord("public.persona_ben", $select, $join, $where, "","", "", "", "","", TRUE);

		if ($solcre){
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>$mensaje,
				"socio" => $socio,
				"persona"=> $solcre,
				"dom" => $dom,
				"ben" => $ben
				);
		} else {
			
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro inexistente!");
		}
		if ($response){
			$this->returnData($data);
		}else {
			return $data;
		}
	}


	public function findAcre_get(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		    $this->returnCode();
		}else {
			$esquema = $this->esquema;			
			$query = "Select a.*, b.idcredito from ".$esquema."get_acreditado_solicitud as a left join ".$esquema."creditos as b on b.idacreditado = a.acreditadoid where a.idacreditado=".$idacreditado.' and a.inhabilitado = false limit 1';
			$acredita = $this->base->querySelect($query, FALSE);
			$this->validaCode($acredita);
		}
	}

	public function findPersona($idpersona ){
		$idacreditado = $idpersona;
		$esquema = $this->esquema;
		if ($this->ion_auth->in_group('caja')){
			$query = "Select * from ".$esquema."get_acreditado_solicitud where idacreditado=".$idacreditado;
		}else{
			$query = "Select * from ".$esquema."get_acreditado_solicitud where idpersona=".$idacreditado;
		}

		$acredita = $this->base->querySelect($query, FALSE);
		return $acredita;
	}

	public function findAcreAporta_get(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		    $this->returnCode();
		}else {
			$esquema = $this->esquema;       
			$query = "Select * from ".$esquema."get_acreditado_solicitud where idacreditado=".$idacreditado;
			$acredita = $this->base->querySelect($query, FALSE);
			$this->validaCode($acredita);
		}
		
	}




	public function credito_checklist_put(){
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');
		$idcredito = $datos['idcredito']; 
		$idchecklist = $datos['idchecklist'];
		$newdatos=array();
		$tabla = $this->esquema.'credito_checklist';
		foreach($datos as $key=>$valor){
			if (substr($key,0,6) =="iddoc_") {
				$no = substr($key,6,10);
				$doc =null;
				$user=null;
				if($valor=="on"){
					$doc =date("Y-m-d H:i:s");
					$user = $this->ion_auth->user()->row()->id;
				}
				$record = array('idcredito' => $idcredito,
								'idchecklist'=> $idchecklist,
								'iddocumento'=> $no,
								'fecha' => $doc,
								'usuario'=>$user
								);				
				$where = array('idcredito'=>$idcredito,'idchecklist'=>$idchecklist, 'iddocumento'=>$no);
				$update = $this->base->updateRecord($tabla, $record, $where, 0);
			}
		}
		$this->validaCode($update);			
	}


	public function getcreditos_clist_get(){
		$idacreditado = $this->uri->segment(4);
		$esquema = $this->esquema;		
		$query = "select acreditado as nombre, acreditadoid from public.get_acreditados where idacreditado =".$idacreditado." and inhabilitado = false" ;
//		$query = "select acreditado as nombre, acreditadoid from public.get_acreditados where idacreditado =".$idacreditado;
		$acre = $this->base->querySelect($query, TRUE);
		$query = "select a.idcredito, a.idacreditado, a.idcredito as value, a.idpagare as name, a.acreditado as nombre from
				 ".$esquema."get_creditos_acreditado as a where a.idacreditado =".$idacreditado." order by a.idpagare desc";				 
		$chk = $this->base->querySelect($query, TRUE);
		if ($acre) {
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"check" => $chk,
				"acre"  => $acre
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro(s) inexistente(s)!",
				"check"=> [],
				"acre" => []
				);
		}
		$this->returnData($data);

	}


	public function findPolizaDiario($fecha) {
		$query = "select * from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') order by orden,nombre,nomcuenta";
		$mov = $this->base->querySelect($query, TRUE);
		
		if ($mov) {
			if ($this->esquema == 'ban.'){				
				$query = "select a.nombre, count(a.nomov) as numero, sum(a.importe) as importe, sum(a.importe - (a.iva) - a.garantia) as capital, sum(a.garantia) as garantia, sum(a.interes) as interes,  sum(a.iva) as iva, x.numero as colmena_numero, x.nombre as colmena_nombre from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') a left join col.colmenas x on x.numero = a.colmena_numero group by a.orden, a.nombre, x.numero, x.nombre order by a.orden, x.numero";				
			}else if ($this->esquema == 'ama.'){
				$query = "select nombre, count(nomov) as numero, sum(importe) as importe, sum(importe - (interes + iva)) as capital, sum(interes) as interes,  sum(iva) as iva, sum(mora) as mora  from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') group by orden,nombre order by orden";				
			}else {
				$query = "select nombre, count(nomov) as numero, sum(importe) as importe, sum(importe - (interes + iva)) as capital, sum(interes) as interes,  sum(iva) as iva  from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') group by orden,nombre order by orden";
			}
			$resumen = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $mov,
				"resumen" => $resumen
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Información inexistente!",
				"mov" => [],
				"resumen" => []
			);
		}
		
		 return $respuesta;
	}



	

	public function credito_checklist_get(){
		$idacreditado = $this->uri->segment(4);
		$idpagare = strtoupper($this->uri->segment(5));
		$esquema = $this->esquema;
		if ($esquema =='ama.') {
			$tabla = $esquema."get_solicitud_credito_ind as a";
			
		}else {
			$tabla = $esquema."get_solicitud_credito as a";			
		}
		$where = array("a.idacreditado" => $idacreditado,
				"a.idcredito" => $idpagare
		);
		$select = "a.idcredito, a.idacreditado, a.tipo, a.idchecklist, a.nombre";
		$socio = $this->base->selectRecord($tabla, $select, "", $where, "","", "", "", "","", TRUE);
		if ($socio) {
			$idchecklist = $socio[0]['idchecklist'];
			$idcredito = $socio[0]['idcredito'];
			$tipo = $socio[0]['tipo'];

			$tabla = $this->esquema."get_checklist as a";
			$where = array('a.idchecklist' => $idchecklist,
						'a.idcredito' => $idcredito,
						'a.estatus' => "1"
			);
			$where_in = array('a.tipo' => array($tipo,'A'));
			$check = $this->base->selectRecord($tabla, "a.*, (case when not a.fecha is null then 1 else 0 end) as checked", "", $where, "","", "", "", "","", TRUE, $where_in);
			if ($check) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"socio" => $socio,
				"checklist"=> $check
				);
			}else {
				$data = array("status"=>"ERROR",
					"code" => 404,
					"message"=>"CheckList inexistente!");
			}
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro inexistente!");
		}
		$this->returnData($data);
	}



	/*
	* Alta de ahorro 
	*/
	public function altahorro_post(){		
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			if ($idacreditado ==  $this->ion_auth->user()->row()->acreditadoid) {
   		        $this->returnCodeSameUser();
			}else {
				$valores = $this->post('data')?$this->post('data', TRUE):array();
				//Carga de los modelos 
				$this->load->model('ahorrosmov_model','ahorros');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('ahorrosmov_post') == TRUE) {
					$datos['idacreditado'] = $idacreditado;
					$updatetrans = $this->ahorros->transaction($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}
			}

		 }
	}

	/*
	* Movimiento de ahorros 
	*/
	public function retahorro_post(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			if ($idacreditado ==  $this->ion_auth->user()->row()->acreditadoid) {
   		        $this->returnCodeSameUser();
			}else {
				$valores = $this->post('data')?$this->post('data', TRUE):array();
				//Carga de los modelos 
				$this->load->model('ahorrosmov_model','ahorros');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('ahorrosret_post') == TRUE) {
					$datos['idacreditado'] = $idacreditado;
					$updatetrans = $this->ahorros->transacRetiro($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}
			}
		}
	}




	/*
	*  Obtiene las amortizaciones de un grupo/colmena para su proceso en CAJA
	*/
	public function amortiza_get(){
		$idgrupo = $this->uri->segment(4);		
		 $esquema = $this->esquema;       
/*		 
		 if ($esquema == 'ban.') {
			 $query = "Select * from ".$esquema."get_creditos_acreditado where not fecha_aprov is null and not fecha_dispersa is null and fecha_pago::date <= current_date  and idgrupo=".$idgrupo.' order by orden, fecha_aprov';
		 }else {
*/			 
			 $query = "Select * from ".$esquema."get_creditos_acreditado where not fecha_aprov is null and not fecha_dispersa is null and idgrupo=".$idgrupo.' order by orden, fecha_aprov';

//		 }
//		 $query = "Select * from ".$esquema."get_creditos_acreditado where idgrupo=".$idgrupo;
		$amortiza = $this->base->querySelect($query, TRUE);
		$falta = false;
		$icon=0;
		$totalpagar=0;
		if ($amortiza) {
			//Busca el último movimiento realizado 
			//Para reversa 
			$idcaja = $this->session->userdata('idcaja');
			$query = "select b.fecha_pago, b.idcaja, b.nomov from ".$esquema."get_creditos_acreditado as a join ".$esquema."amortizaciones as b on b.idcredito = a.idcredito where not fecha_aprov is null and not fecha_dispersa is null and not b.fecha_pago is null and idgrupo=".$idgrupo." and b.fecha_pago::date = current_date::date and b.idcaja ='".$idcaja."'order by b.fecha_pago desc limit 1";
			$pago = $this->base->querySelect($query, TRUE);
			if (!$pago){
				$pago = [];
			}
			$creditosfaltan= '';
			$fecha_pago_col ='';
			$supervisor = false;
			foreach($amortiza as $key => $value){
				if ($esquema != 'ban.')
				{
					if ($amortiza[$key]['fecha_entrega'] === null || $amortiza[$key]['fecha_entrega'] === '' ) {
						$falta = true;
						$creditosfaltan = $creditosfaltan.' '. $amortiza[$key]['idcredito'].', ';
					}
				}
				
					//Aqui va a buscar el último registro
					$data = $this->base->querySelect("select idcredito, numero, capital, interes, iva, aportesol, garantia, total, ajuste, asistencia, incidencia, ahorro_vol,  to_char(fecha_pago_col,'DD/MM/YYYY HH:MI:SS') as fecha_pago_col, extract(week from a.fecha_pago_col::date) as iweek,
						(select count(idcredito) from ".$esquema."amortizaciones as b where b.idcredito = a.idcredito and extract(week from b.fecha_pago_col::date) = extract(week from a.fecha_pago_col::date) and not a.fecha_pago_col is null) as pagos_col, 
					    (select max(numero) from ".$esquema."amortizaciones as b where b.idcredito = a.idcredito) as pagomax  
						from ".$esquema."amortizaciones as a  where fecha_pago is null  and idcredito =". $amortiza[$key]['idcredito']." group by idcredito,numero,capital, aportesol, garantia, total  order by numero limit 1", TRUE);

					if ($data[0]['fecha_pago_col'] != null){
    					$fecha_pago_col= $data[0]['fecha_pago_col'];
					}
					$amortiza[$key]['pagomax'] = $data[0]['pagomax'];
					if ($data[0]['numero'] ==""){
						$amortiza[$key]['numero'] = 0;
					} else {
						$amortiza[$key]['numero'] = $data[0]['numero'];
					}

					if ($esquema == 'ban.') { 
						$amortiza[$key]['capital'] = $data[0]['total'] - $data[0]['aportesol'] ;
						$amortiza[$key]['interes'] = $data[0]['aportesol'];
						$amortiza[$key]['iva'] = 0;
						$amortiza[$key]['importepago'] = $data[0]['total'] + $data[0]['garantia'];
						$amortiza[$key]['ahocomprome'] = $data[0]['garantia'];
						$amortiza[$key]['ahocorriente'] = "";
						$amortiza[$key]['ajuste'] = 0;
						$amortiza[$key]['ahorro_vol'] = $data[0]['ahorro_vol'];
						$amortiza[$key]['asistencia'] = $data[0]['asistencia'];
						$amortiza[$key]['incidencia'] = $data[0]['incidencia'];
						$amortiza[$key]['pagos_col'] = '1';

					}else {
						$amortiza[$key]['capital'] = $data[0]['capital'];
						$amortiza[$key]['interes'] = $data[0]['interes'];
						$amortiza[$key]['iva'] = $data[0]['iva'];
						$amortiza[$key]['importepago'] = $data[0]['capital'] + $data[0]['interes'] + $data[0]['iva'];
						$amortiza[$key]['ahocomprome'] = $data[0]['garantia'];
						$amortiza[$key]['ahocorriente'] = "";
						$amortiza[$key]['ajuste'] = $data[0]['ajuste'];
						$amortiza[$key]['ahorro_vol'] = $data[0]['ahorro_vol'];
						$amortiza[$key]['asistencia'] = $data[0]['asistencia'];
						$amortiza[$key]['incidencia'] = $data[0]['incidencia'];
						$amortiza[$key]['pagos_col'] = '1';

					}

					if ($data[0]['pagos_col'] =="0" || empty($data[0]['pagos_col'])) {					   
					}else{
						  $supervisor = true;
						  $amortiza[$key]['pagos_col'] = $data[0]['pagos_col'];						  
						  if ($data[0]['pagos_col'] != '1') {
							 //Busca la cantidade pagos seleccionados 	
							 $iweek = $data[0]['iweek'];
							 if ($iweek != null ) {
								 $pagos_col = $this->base->querySelect("select idcredito, sum(capital) as capital, sum(interes) as interes, sum(iva) as iva, sum(garantia) as garantia, sum(total) as total, 
									sum(ajuste) as ajuste, sum(capital + interes + iva) as importepago, sum(garantia) as ahocomprome from ".$esquema."amortizaciones as a  
									where fecha_pago is null and idcredito =".$amortiza[$key]['idcredito']." and extract(week from a.fecha_pago_col::date) = ".$iweek." group by idcredito", TRUE);
								   if ($pagos_col) {
										 // print_r($pagos_col);
										$amortiza[$key]['importepago'] = $pagos_col[0]['importepago'] ;
										$amortiza[$key]['capital'] = $pagos_col[0]['capital'] ;
										$amortiza[$key]['interes'] = $pagos_col[0]['interes'] ;
										$amortiza[$key]['ahocomprome'] = $pagos_col[0]['ahocomprome'] ;
										$amortiza[$key]['ajuste'] = $pagos_col[0]['ajuste'];
								   }
							 }
						  }
					}
					$pagos_col =  $amortiza[$key]['pagos_col'] ;
//					$totalpagar+= $data[0]['total'] + $data[0]['garantia'];
//					$totalpagar+= $amortiza[$key]['importepago'] + $amortiza[$key]['ahocomprome'] + $amortiza[$key]['ajuste'] + $amortiza[$key]['ahorro_vol'];
//					$amortiza[$key]['total'] = $data[0]['total'] + $data[0]['garantia']; // + $data[0]['ahorro_vol'];
					if ($esquema == 'ban.' ) { 
						$totalpagar+= $amortiza[$key]['importepago'] + $amortiza[$key]['ajuste'] + $amortiza[$key]['ahorro_vol'];
					
 						$amortiza[$key]['total'] = $amortiza[$key]['importepago'] + $amortiza[$key]['ajuste']  + $data[0]['ahorro_vol'];
					}else {
    					$totalpagar+= $amortiza[$key]['importepago'] + $amortiza[$key]['ahocomprome'] + $amortiza[$key]['ajuste'] + $amortiza[$key]['ahorro_vol'];
						
						$amortiza[$key]['total'] = $amortiza[$key]['importepago'] + $amortiza[$key]['ahocomprome'] +  $amortiza[$key]['ajuste']  + $data[0]['ahorro_vol'];
					}
 					$nopago =  $data[0]['pagomax'] - $data[0]['numero'] + 1;
					$pagosno = [];
					$conteo =0;
					for($i=$pagos_col; $i<=$nopago; $i++ ){
						$pagosno[$conteo] = array('value' => $i, 'name' => $i);
						$conteo += 1;
					}
					$amortiza[$key]['nopagos'] = $pagosno;

					if ($esquema == 'fin.') { 
						// primer pago
						if ($data[0]['numero']  == 1) {
							$amortiza[$key]['capital'] = $amortiza[$key]['importepago'];
							$amortiza[$key]['interes'] = 0;
							$amortiza[$key]['iva'] = 0;
						}else {
							//Obtiene el saldo de capital para comparalos con el saldo_actual segun provision 
							$datac = $this->base->querySelect("select idcredito, capital as capital from ".$esquema."amortizaciones as a  
								where fecha_pago is null and idcredito =".$amortiza[$key]['idcredito']." order by fecha_vence limit 1", true);
							$capitalpago = (float) $datac[0]['capital'];
							// Ultimo pago 
//							if ($nopago == 1){
								// obtiene el saldo de capital actual en base a la provision 
								$data = $this->base->querySelect("select dia, saldo_actual, factor from ".$esquema."get_credito_provision(".$amortiza[$key]['idcredito'].", current_date )  where pago_total <>0 or dia = current_date order by dia desc limit 2", true);								
								if ($data){
									if ((float)$data[0]['saldo_actual']  < (float) $capitalpago  ) {
										$d_start    = new DateTime($data[0]['dia']); 
										if (count($data) == 2) {
											$d_end      = new DateTime($data[1]['dia']); 
											$diff = $d_start->diff($d_end); 
											$diastrans     = $diff->format('%d');
										}else {
											$diastrans     = 1;
										}
										$interes = round((float)$data[0]['saldo_actual'] * (float) $data[0]['factor'] * (integer)$diastrans,2);
										$iva = round($interes * 0.16,2);
										$pagotot = (float)$data[0]['saldo_actual']  + (float)($interes) + (float)($iva);

										$ajuste =  round((float)$amortiza[$key]['importepago'] + (float)$amortiza[$key]['ahocomprome'] + (float)$amortiza[$key]['ajuste'] - (float)$amortiza[$key]['ahocomprome'] - $pagotot,2);
										$amortiza[$key]['capital'] =  $data[0]['saldo_actual'];
										$amortiza[$key]['interes'] = $interes;
										$amortiza[$key]['iva'] = $iva;									
										$amortiza[$key]['importepago'] =  $pagotot;
	//									$amortiza[$key]['total'] = $pagotot;
										$amortiza[$key]['ajuste'] = $ajuste;
									}
								}

//							}



	
					/* 		
							// Ultimo pago 
							if ($nopago == 1){

								// obtiene el saldo de capital actual en base a la provision 
								$data = $this->base->querySelect("select dia, saldo_actual, factor from ".$esquema."get_credito_provision(".$amortiza[$key]['idcredito'].", current_date )  where pago_total <>0 or dia = current_date order by dia desc limit 2", true);								
								if ($data){
									$d_start    = new DateTime($data[0]['dia']); 
									if (count($data) == 2) {
										$d_end      = new DateTime($data[1]['dia']); 
										$diff = $d_start->diff($d_end); 
										$diastrans     = $diff->format('%d');
									}else {
										$diastrans     = 1;
									}
									$interes = round((float)$data[0]['saldo_actual'] * (float) $data[0]['factor'] * (integer)$diastrans,2);
									$iva = round($interes * 0.16,2);
									$pagotot = (float)$data[0]['saldo_actual']  + (float)($interes) + (float)($iva);


									$ajuste = round((float)$amortiza[$key]['importepago'] + (float)$amortiza[$key]['ahocomprome'] + (float)$amortiza[$key]['ajuste'] - (float)$amortiza[$key]['ahocomprome'] - $pagotot,2);
									$amortiza[$key]['capital'] =  $data[0]['saldo_actual'];
									$amortiza[$key]['interes'] = $interes;
									$amortiza[$key]['iva'] = $iva;									


//									$amortiza[$key]['importepago'] =  $pagotot;
//									$amortiza[$key]['total'] = $pagotot;
									$amortiza[$key]['ajuste'] = $ajuste;

									$campos = $data[0]['saldo_actual'] ." as capital, ".$interes." as interes, ".$iva." as iva, 
										".$pagotot." as total, ".$pagotot." as importepago,
										(sum(capital + interes + iva) + sum(garantia + ajuste)) - sum(garantia) - ".$pagotot."  as ajuste,
									";

								}



								
								
							}
							
							 */



						}


					}
 

			}
			
			//Elimina los pagos siempre y cuando tenga un credito vigente
			for ($i=count($amortiza)-1; $i >=0 ; $i--) { 
				if ($amortiza[$i]['numero'] == 0  && $amortiza[$i]['total'] == 0 ) {
						unset($amortiza[$i]);

				}
			}
			// Se reordena el arreglo 
			$amortizacopia = [];
			foreach($amortiza as $key => $value){
				array_push($amortizacopia, $value);
			}

				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registros obtenidos correctamente',
				"result" => $amortizacopia,
				"pago" => $pago,
				"totalxpagar" => $totalpagar,
				"falta" => $falta,
				"falta_lista" => $creditosfaltan,
				"supervisor" => $supervisor,
				"fecha_pago_col" => $fecha_pago_col
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro inexistente!"
				);
		}
		$this->returnData($data);
	}


	public function amortizaMas_get(){
		$idcredito =$this->uri->segment(4);
		$inicia = $this->uri->segment(5);
		$no = $this->uri->segment(6);
		$esquema = $this->esquema;       
		 
		$primero = $_GET["primero"] ;
		$ultimo = $_GET["ultimo"] ;

		$campos ="sum(capital) as capital, sum(interes) as interes, sum(iva) as iva, sum(ajuste) as ajuste, sum(total) as total, 
			 sum(capital + interes + iva) as importepago,";

		if ( $this->esquema === 'fin.' ||  $this->esquema === 'imp.') {
			if ($primero === true || $primero === 'true') {
				$campos ="sum(total) as capital, 0 as interes, 0 as iva, sum(ajuste) as ajuste, sum(total) as total, 
				 sum(capital + interes + iva) as importepago,";
				if ($ultimo === true || $ultimo === 'true') {
					$campos ="sum(total + ajuste ) as capital, 0 as interes, 0 as iva, 0 as ajuste, sum(total + ajuste) as total, 
				 sum(capital + ajuste + interes + iva) as importepago,";
				} 
			}else {
					//Obtiene el saldo de capital para comparalos con el saldo_actual segun provision 
 					$data = $this->base->querySelect("select idcredito, sum(capital) as capital, sum(garantia) as garantia,  sum(garantia) as ahocomprome from ".$esquema."amortizaciones as a  
						where fecha_pago is null and idcredito =".$idcredito." and numero <".$inicia." group by idcredito", true);
					$capitalpago = (float) $data[0]['capital'];



//				if ($ultimo === true || $ultimo === 'true') {
					// obtiene el saldo de capital actual en base a la provision 


					$data = $this->base->querySelect("select dia, saldo_actual, factor from ".$esquema."get_credito_provision(".$idcredito.", current_date )  where pago_total <>0 or dia = current_date order by dia desc limit 2", true);					
					if ($data){
						if ((float)$data[0]['saldo_actual']  < $capitalpago  ) {
							$d_start    = new DateTime($data[0]['dia']); 
							if (count($data) == 2) {
								$d_end      = new DateTime($data[1]['dia']); 
								$diff = $d_start->diff($d_end); 
								$diastrans     = $diff->format('%d');
							}else {
								$diastrans     = 1;
							}
							$interes = round((float)$data[0]['saldo_actual'] * (float) $data[0]['factor'] * (integer)$diastrans,2);
							$iva = round($interes * 0.16,2);
							$pagotot = (float)$data[0]['saldo_actual']  + (float)($interes) + (float)($iva);
							$campos = $data[0]['saldo_actual'] ." as capital, ".$interes." as interes, ".$iva." as iva, 
								".$pagotot." as total, ".$pagotot." as importepago,
								(sum(capital + interes + iva) + sum(garantia + ajuste)) - sum(garantia) - ".$pagotot."  as ajuste,
							";
						}
					}				
//				}




			}
		}
		//Aqui va a buscar el último registro     sum(total + garantia) as totalt,
		$data = $this->base->querySelect("select idcredito, ".$campos." sum(garantia) as garantia,  sum(garantia) as ahocomprome from ".$esquema."amortizaciones as a  
			where fecha_pago is null and idcredito =".$idcredito." and numero <".$inicia." group by idcredito", FALSE);

		if ($data) {
		}else{
			$data = array("status"=>"ERROR",
			"code" => 404,
			"message"=>"Registro inexistente!"
			);
		}
		$this->returnData($data);
	}



	/*
	* Obtiene los creditos de un acreditado dado para su proceso en CAJA
	*/

	public function getCreditos_get(){
		$idacreditado = $this->uri->segment(4);	
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;       
			$query = "Select idcredito, idpagare, monto, pagos, saldo, numero_cuenta, idahorro, comprometido, idproducto from ".$esquema."get_creditos_resumen(".$idacreditado.") where fecha_liquida is null or comprometido<>0  order by idpagare asc ";
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}


	/*
	*  Obtiene los creditos de un socio que no han sido entregados al acreditado
	*/
	public function getCreditos_cat_get(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			if ($esquema == "ama.") {
				$tabla = "get_solicitud_credito_ind";				
			}else {
				$tabla = "get_solicitud_credito";				
			}			
			$query = "Select  (SELECT h.idahorro FROM ".$this->esquema."ahorros h JOIN productos pr ON pr.idproducto::text = h.idproducto::text
				WHERE h.idacreditado = a.acreditadoid AND h.fecha_baja IS NULL AND pr.tipo::text = 'AV'::text LIMIT 1) AS idahorro, (SELECT h.numero_cuenta FROM ".$this->esquema."ahorros h JOIN
				productos pr ON pr.idproducto::text = h.idproducto::text WHERE h.idacreditado = a.acreditadoid AND h.fecha_baja IS NULL 
				AND pr.tipo::text = 'AV'::text LIMIT 1) AS numero_cuenta, a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.monto from ".$esquema.$tabla." as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and a.fecha_liquida is null and a.fecha_dispersa is null";
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}


	/*
	*  Obtiene los creditos de un socio que no han sido entregados al acreditado
	*/
	public function getCreditos_vig_get(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			if ($esquema == "ama.") {
				$tabla = "get_solicitud_credito_ind";				
			}else {
				$tabla = "get_solicitud_credito";				
			}						
			$query = "select a.*, b.idacreditado as acreditadoid, b.idcredito as value, b.idpagare as name, b.nombre, b.nomcolmena, b.nomgrupo from ".$esquema."get_creditos_resumen2(".$idacreditado.") as a
			     inner join ".$esquema.$tabla." as b on b.acreditadoid = a.idacreditado and b.idcredito = a.idcredito where b.idacreditado = ".$idacreditado." and not a.fecha_entrega is null and a.xpcomprometido <>0";
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}
	


	public function getCreditosxDis_get(){
		$idacreditado = $this->uri->segment(4);	
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			if ($esquema == "ama.") {
				$tabla = "get_solicitud_credito_ind";				
			}else {
				$tabla = "get_solicitud_credito";				
			}
			$query = "Select (SELECT h.idahorro FROM ".$esquema."ahorros h JOIN productos pr ON pr.idproducto::text = h.idproducto::text 
				WHERE h.idacreditado = a.acreditadoid AND h.fecha_baja IS NULL AND pr.tipo::text = 'AV'::text LIMIT 1) AS idahorro,  (SELECT h.numero_cuenta FROM ".$esquema."ahorros h JOIN productos pr ON pr.idproducto::text = h.idproducto::text 
				WHERE h.idacreditado = a.acreditadoid AND h.fecha_baja IS NULL AND pr.tipo::text = 'AV'::text LIMIT 1) AS numero_cuenta, a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, a.monto from ".$esquema.$tabla." as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and a.fecha_liquida is null and not a.fecha_dispersa is null and a.tipo_dispersa='01' and a.fecha_entrega is null and a.idsucursal ='".$this->session->userdata('sucursal_id')."'";
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}



	/*
	*  Segurox del credito
	*/

		public function getCreditosxSeg_get(){
		$idacreditado = $this->uri->segment(4);	
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			if ($esquema == "ama.") {
				$tabla = "get_solicitud_credito_ind";				
			}else {
				$tabla = "get_solicitud_credito";				
			}
			
			$sSuc = $this->session->userdata('sucursal_id');
			
/*			$query = "Select a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, a.monto, a.nivel, a.num_pagos * 5 as seguro from ".$esquema.$tabla." as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and a.fecha_liquida is null and not a.fecha_dispersa is null and a.tipo_dispersa='01' and not a.fecha_entrega is null and a.idsucursal ='".$this->session->userdata('sucursal_id')."' order by a.idcredito desc limit 1";
	*/			
				
			// Filtro solo funciona pra base IMPULSO
			$query = "
			select acreditadoid, idcredito, idacreditado, value, 
				name, nombre, nomcolmena, nomgrupo, fecha_aprov, fecha_dispersa, 
				monto, nivel, seguro, esquema from (
			   ( Select a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, 
				a.monto, a.nivel, a.num_pagos * 5 as seguro, 'imp' as esquema from imp.get_solicitud_credito as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and not a.fecha_dispersa is null and a.tipo_dispersa='01' and not a.fecha_entrega is null and a.idsucursal ='".$sSuc."' order by a.idcredito desc limit 3)
				UNION
			    (Select a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, 
				a.monto, a.nivel, a.num_pagos * 5 as seguro, 'fin' as esquema from fin.get_solicitud_credito as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and not a.fecha_dispersa is null and a.tipo_dispersa='01' and not a.fecha_entrega is null and a.idsucursal ='".$sSuc."' order by a.idcredito desc limit 3)
				UNION
			    (Select a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, 
				a.monto, a.nivel, a.num_pagos * 5 as seguro, 'ban' as esquema from ban.get_solicitud_credito as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and not a.fecha_dispersa is null and not a.fecha_entrega is null and a.idsucursal ='".$sSuc."' order by a.idcredito desc limit 3)
				UNION
			    (Select a.acreditadoid, a.idcredito, a.idacreditado, a.idcredito as value, 
				a.idpagare as name, a.nombre, a.nomcolmena, a.nomgrupo, a.fecha_aprov, a.fecha_dispersa, 
				a.monto, a.nivel, a.num_pagos * 5 as seguro, 'ama' as esquema from ama.get_solicitud_credito_ind as a where 
				a.idacreditado = ".$idacreditado." and not a.fecha_aprov is null and not a.fecha_dispersa is null and a.tipo_dispersa='01' and not a.fecha_entrega is null and a.idsucursal ='".$sSuc."' order by a.idcredito desc limit 3)
				) as rec
				";
				
				
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}	

	/*
	*  Dispersion del crédito 
	*/

	public function credito_dis_put(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			$valores = $this->put('data')?$this->put('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('credito_model','creditos');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$datos['monto'] = (double)str_replace(",","",$datos['monto']);
			$datos['importe'] = (double)str_replace(",","",$datos['importe']);
			if ($datos['movimiento'] =="01"){
					// Se agrega temporalmente solo para que pase la validacion
					$datos['idbancodet'] = '11111';
					$datos['cheque_ref'] = 'X';
					$datos['afavor'] = 'X';
			}else{
					$datos['numero_cuenta'] = '11111';
			}
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run('creditos_dis_put') == TRUE) {
				$datos['idcredito'] = $idcredito;
				$updatetrans = $this->creditos->transaccion($datos);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}
		}
	}



	/*
	*  Obtención de créditos para dispersion en módulo de tesoreria
	*/
	public function cred_entrega_put(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			$valores = $this->put('data')?$this->put('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('credito_model','creditos');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$datos['monto'] = (double)str_replace(",","",$datos['monto']);
			$datos['idacreditado'] = $datos['idacredis'];
			$datos['idpagare'] = $datos['idpagdis'];
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run('cred_entrega_put') == TRUE) {
				$datos['idcredito'] = $idcredito;
				$updatetrans = $this->creditos->transac_entrega($datos);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}		
		}
	}
	/*
    * Autorizacion de Cheques de creditos dispersados
	*/

	public function autorizacheque_get(){
		if ($this->ion_auth->in_group('gerencial')){
			$esquema = $this->esquema;
			$idsuc = $this->session->userdata('sucursal_id');
			$query = "select a.idcredito,a.acreditado, a.idpagare, a.monto, a.fecha_aprov, a.fecha_dispersa, a.fecha_entrega, case when a.fecha_entrega is null then 'Por Autorizar' else 'Autorizado' end as estatus from ".$esquema."get_creditos_acreditado a where a.idsucursal ='".$idsuc."' and (a.fecha_entrega is null or a.fecha_entrega::date = current_date) and not a.fecha_dispersa is null and a.tipo_dispersa ='10' order by fecha_dispersa desc";
			$auto = $this->base->querySelect($query, FALSE);
			$this->validaCode($auto);			
		}else {
			$this->returnCode();			
		}
	}

	public function autorizacheque_put(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			$this->load->model('credito_model','creditos');
			$datos = array();
			$datos['idcredito'] = $idcredito;
			$updatetrans = $this->creditos->transac_autoriza_cheque($datos);
			$this->validaCode($updatetrans);
		}
	}


	public function cancelacheque_put(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			$this->load->model('credito_model','creditos');
			$datos = array();
			$datos['idcredito'] = $idcredito;
			$updatetrans = $this->creditos->transac_cancela_cheque($datos);
			$this->validaCode($updatetrans);
		}
	}



	public function reversaPagos_get(){
		if ($this->ion_auth->in_group('gerencial')){
			$esquema = $this->esquema;
			$idsuc = $this->session->userdata('sucursal_id');
			$query = "select a.id,b.colmena_nombre, b.grupo_nombre, a.fecha_pago, a.nomov, a.idcaja, a.autoriza, c.username, a.fecha from ".$esquema."pagos_undo as a join ".$esquema."get_colmena_grupo as b on b.idgrupo = a.idgrupo left join security.users as c on c.id = a.usuario where a.idsucursal ='".$idsuc."' and fecha_pago::date = current_date::date order by autoriza desc";
			$auto = $this->base->querySelect($query, FALSE);
			$this->validaCode($auto);			
		}else {
			$this->returnCode();			
		}
	}

	
	public function reversaPagos_put(){
		if ($this->ion_auth->in_group('gerencial')){
			$id = $this->uri->segment(4);
			$esquema = $this->esquema;
			$idsuc = $this->session->userdata('sucursal_id');
			$user = $this->ion_auth->user()->row()->id;
			$fecha =date("Y-m-d H:i:s");
			$record = array('autoriza' => true,
							'usuario'=> $user,
							'fecha'=> $fecha
							);
			$where = array('id'=>$id,'idsucursal'=>$idsuc);
			$update = $this->base->updateRecord($esquema."pagos_undo", $record, $where, 0);
			$this->validaCode($update);
		}else {
			$this->returnCode();			
		}

	}

		/*
	*  Condonacion de ahorro comprometido 
	*/

	public function credito_con_put(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			
			$valores = $this->put('data')?$this->put('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('credito_model','creditos');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$datos['monto'] = (double)str_replace(",","",$datos['monto']);
			$datos['xpcomprometido'] = (double)str_replace(",","",$datos['xpcomprometido']);
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run('creditos_con_put') == TRUE) {
				$datos['idcredito'] = $idcredito;
				$updatetrans = $this->creditos->transacondona($datos);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}
		}
	}


	

	/*
	* Cuentas de Ahorro segun idacreditado 
	*/
	public function getCtaAhorros_get(){
		$idacreditado = $this->uri->segment(4);		
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$fechoy = date("Y-m-d");
			$query = "Select idahorro, numero_cuenta, idahorro, fecdeposito, fecretiro, menor from ".$esquema."get_ahorros_voluntarios(".$idacreditado.",'G','".$fechoy."')";
			// where substring(numero_cuenta,1,2) ='AV' or substring(numero_cuenta,1,2) ='AC'";			
			$amortiza = $this->base->querySelect($query, FALSE);
			$this->validaCode($amortiza);
		}
	}

	public function saldoCtaAhorros_get(){
		$idacreditado = $this->uri->segment(4);		
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$fechoy = date("Y-m-d");
			$query = "Select idahorro, numero_cuenta, saldo from ".$esquema."get_ahorros_voluntarios(".$idacreditado.",'R','".$fechoy."') where substring(numero_cuenta,1,2) ='AV'";
			$ahorro = $this->base->querySelect($query, FALSE);
			$this->validaCode($ahorro);
		}
	}


	/*
	* Aportación Social
	 */
	public function aportasoc_post(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			if ($idacreditado ==  $this->ion_auth->user()->row()->acreditadoid) {
   		        $this->returnCodeSameUser();
			}else {
				$valores = $this->post('data')?$this->post('data', TRUE):array();
				//Carga de los modelos 
				$this->load->model('aportasocial_model','aportasoc');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$datos['importe'] = (double)str_replace(",","",$datos['imporapor']);
				$datos['idacreditado'] = $idacreditado;
				$datos['movimiento'] = $datos['movapor'];
				$datos['instrumento'] = $datos['tipoapor'];
				$datos['idbancodet'] = "1";
				$datos['cheque_ref'] = "X";
				$datos['afavor'] = "XXXXX";
				if ($datos['movimiento'] == "E"  && $datos['instrumento'] == "10") {
					$datos['idbancodet'] = $datos['idbancodetapor'];
				}
				if ($datos['instrumento'] == "10") {
					$datos['cheque_ref'] = $datos['cheque_refapor'];
					$datos['afavor'] = $datos['afavorapor'];
				}
				if ($datos['movimiento'] == "I"  && $datos['instrumento'] =="10") {
				}else{
					$datos['titular'] = "XXXX";
				}
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('aportasocial_post') == TRUE) {
					$updatetrans = $this->aportasoc->transaccion($datos);
					$this->validaCode($updatetrans);

					// Impresion 
	//				if ($updatetrans['code'] == 200){
	//					$this->print($updatetrans['nomov']);					
	//				}

				}else {
					$this->validaForm();
				}		
			}
		}
	}





	public function findAportaSoc($fecha,$suc) {
		$sFiltro = ($suc == "99"?" order by idsucursal,idacreditado":" where idsucursal ='".$this->session->userdata('sucursal_id')."' order by idacreditado");
		
		$query = "select idsucursal, idacreditado, nombre, estado, municipio, localidad, saldo, 0 as parcial,fecha_alta, 0 as apor, localidad, 1 as ct,direccion, fecha_nac, sexo from ".$this->esquema."get_aporta_socialg('".$fecha."')".$sFiltro;
		$mov = $this->base->querySelect($query, TRUE);		
		if ($mov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $mov
			);
		}else{
			$mov = [];
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Información inexistente!",
				"mov" => $mov
			);
		}

		 return $respuesta;
	}


	public function getAportaSocLast($iacreditado, $fecha){
		$sFiltro = " where idacreditado =".$iacreditado." and fecha::date <='".$fecha."' and movimiento ='D' order by fecha desc";
		$query = "select idsucursal, idacreditado, fecha, importe from ".$this->esquema."aporta_social ".$sFiltro;
		$mov = $this->base->querySelect($query, FALSE);
		return $mov;

	}


	public function findCaptaciones($fecha,$suc) {
/*		$sFiltro = $suc =="99"?" where saldo > 0 order by idsucursal, idacreditado ":" where idsucursal ='".$this->session->userdata('sucursal_id')."' and saldo > 0 order by idacreditado";
		$query = "select idsucursal, idacreditado,nombre, producto,fecha_alta,fecha_baja, tasa, saldo,0 as interes, saldo as capint from ".$this->esquema."get_ahorros_voluntariosg('R','".$fecha."')".$sFiltro;
*/
		$campos ="";
		if ($this->esquema == "ban.") {
			$campos = ", colmena_numero, colmena_nombre";
		}
		$sFiltro = $suc =="99"?" where saldo > 0 and (idproducto <>'08' and idproducto <>'04')":" where idsucursal ='".$this->session->userdata('sucursal_id')."' and saldo > 0 and (idproducto <>'08' and idproducto <>'04')";
		$orderby = $suc =="99"?" order by a.idsucursal, a.idacreditado":" order by a.idacreditado, a.producto";
		$query = "select * from (
				select idsucursal, idacreditado,nombre, estado, municipio, localidad, producto,fecha_alta,fecha_baja, tasa, saldo,0 as interes, interesmes,  saldo as capint".$campos." from ".$this->esquema."get_ahorros_voluntariosg('R','".$fecha."')".$sFiltro."				
				) as a ".$orderby;
		$mov = $this->base->querySelect($query, TRUE);		
		if ($mov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $mov
			);
		}else{
			$mov = [];
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Información inexistente!",
				"mov" => $mov
			);
		}
		 return $respuesta;
	}

	public function findCaptaciones2($fecha,$suc) {
/*		$sFiltro = $suc =="99"?" where saldo > 0 order by idsucursal, idacreditado ":" where idsucursal ='".$this->session->userdata('sucursal_id')."' and saldo > 0 order by idacreditado";
		$query = "select idsucursal, idacreditado,nombre, producto,fecha_alta,fecha_baja, tasa, saldo,0 as interes, saldo as capint from ".$this->esquema."get_ahorros_voluntariosg('R','".$fecha."')".$sFiltro;
*/
	     $campos ="";
		if ($this->esquema == "ban.") {
			$campos = ", colmena_numero, colmena_nombre";
		}
	
		$sFiltro = $suc =="99"?" where saldo > 0 ":" where idsucursal ='".$this->session->userdata('sucursal_id')."' and saldo > 0 ";
		$orderby = $suc =="99"?" order by a.idsucursal, a.idacreditado":" order by a.idacreditado, a.producto";
		$query = "select * from (				
				select idsucursal, idacreditado,nombre, estado, municipio, localidad, producto,fecha_alta,fecha_baja, tasa, saldo,interes, interesmes, saldo as capint".$campos." from ".$this->esquema."get_inversiones('".$fecha."')".$sFiltro."
				) as a ".$orderby;
		$mov = $this->base->querySelect($query, TRUE);		
		if ($mov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $mov
			);
		}else{
			$mov = [];
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Información inexistente!",
				"mov" => $mov
			);
		}
		 return $respuesta;
	}


	public function findCartera($fecha, $suc) {
        $sFiltro = " order by idsucursal, idacreditado";
		if ($suc != "99"){
			$sFiltro ="where idsucursal='".$this->session->userdata('sucursal_id')."' order by idacreditado";
		}
		$query = "select * from ".$this->esquema."get_creditosg('".$fecha."') ".$sFiltro;
		$mov = $this->base->querySelect($query, TRUE);		
		if ($mov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $mov
			);
		}else{
			$mov = [];
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Información inexistente!",
				"mov" => $mov
			);
		}
		 return $respuesta;
	}





	public function getInversiones_get(){
		$idacreditado = $this->uri->segment(4);		
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$query = "Select numero, fecha::date, fechafin::date,DATE_PART('day',fechafin - current_date) as dias from ".$esquema."inversiones where idacreditado =".$idacreditado." and idsucursal ='".$this->session->userdata('sucursal_id')."' and estatus =true and  not fecha_dispersa is null and not fecha_entrega is null";
			$inver = $this->base->querySelect($query, FALSE);
			$this->validaCode($inver);
		}
	}


	public function getAllInversion_get(){
		$idacreditado = $this->uri->segment(4);		
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$query = "Select numero, fecha::date, fechafin::date, importe, interes, case when estatus = true then 'Vigente' else 'Cancelado' end as estatus from ".$esquema."inversiones where idacreditado =".$idacreditado." and idsucursal ='".$this->session->userdata('sucursal_id')."' order by numero";
			$inver = $this->base->querySelect($query, FALSE);
			$this->validaCode($inver);
		}
	}



	public function getInversion_get(){
		$numero = $this->uri->segment(4);		
		if (!is_numeric($numero)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$query = "Select *  from ".$esquema."inversiones where numero =".$numero." and idsucursal ='".$this->session->userdata('sucursal_id')."'";
			$inver = $this->base->querySelect($query, FALSE);
			$this->validaCode($inver);
		}
	}


	public function getInversionxDis_get(){
		$idacreditado = $this->uri->segment(4);	
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
			$esquema = $this->esquema;
			$query = "select a.idacreditado, a.idsucursal, a.fecha, a.numero as value, a.numero as name, a.dias, a.tasa, a.fechafin, a.importe, a.interes, a.total,a.idinversion, 
				a.fecha_dispersa from ".$esquema."inversiones as a join public.acreditado as b on a.idacreditado = b.acreditadoid where b.idacreditado =".$idacreditado." and a.fecha_entrega is null 
				and a.idsucursal ='".$this->session->userdata('sucursal_id')."' order by a.numero";
			$inver = $this->base->querySelect($query, FALSE);
			$this->validaCode($inver);
		}
	}

	public function getInverxVencer_get(){
		$esquema = $this->esquema;
		$query = "select idinversion, nosocio, nombre, numero, to_char(fecha,'DD/MM/YYYY') as fecha, tasa, to_char(fechafin,'DD/MM/YYYY') as fechafin, DATE_PART('day',fechafin - current_date) as dias, importe, interes, total, 
		case when fecha_entrega is null then 'Por ingresar' else  case when estatus = true then 'Activo' else 'Aplicado' end end as estatus  from ".$esquema."get_inversiones where idsucursal ='".$this->session->userdata('sucursal_id')."' order by fechafin::date DESC";
		$inver = $this->base->querySelect($query, FALSE);
		$this->validaCode($inver);
	}


	public function getVencimientos_get() {
		$filtro = $this->uri->segment(4);	
		$sfiltro ="";
		$esquema = $this->esquema;
		$query = "select idinversion, nosocio, nombre, numero, to_char(fecha,'DD/MM/YYYY') as fecha, tasa, to_char(fechafin,'DD/MM/YYYY') as fechafin, DATE_PART('day',fechafin - current_date) as dias, importe, interes, total, 
		case when fecha_entrega is null then 'Por ingresar' else  case when estatus = true then 'Activo' else 'Aplicado' end end as estatus, case when DATE_PART('day',fechafin - current_date) = 0 then '1' else '0' end as cancela, idacreditado  from ".$esquema."get_inversiones where idsucursal ='".$this->session->userdata('sucursal_id')."' order by fechafin::date DESC";
		$inver = $this->base->querySelect($query, TRUE);
		if ($inver){
		}else{
			$inver = [];
		}
		$pestatus ='A';
		$sfiltro2 ="";
		if ($filtro == 0){
			$sfiltro = "and estatus = 'Por Aprobar'";
		}elseif ($filtro == 1){
			$sfiltro = "and estatus = 'Por dispersar'";
			$sfiltro2 = ", fecha_aprov::date ASC";
		}elseif ($filtro == 2){
			$sfiltro = "and estatus = 'Por entregar'";
		}elseif ($filtro == 3){
			$sfiltro = "and (estatus = 'Por Aprobar'  or estatus = 'Por dispersar' or  estatus = 'Por entregar') ";
			
		}elseif ($filtro == 4){
			$sfiltro = "and estatus = 'Por Pagar'";
			$pestatus ='B';			
		}elseif ($filtro == 5){
			$sfiltro = "and estatus = 'Amortización Pagada'";
			$pestatus ='B';			
		}elseif ($filtro == 6){
			$sfiltro = "and (estatus = 'Por Pagar'  or estatus = 'Amortización Pagada') ";
			$pestatus ='B';			
		}elseif ($filtro == 7){
			$sfiltro = "and estatus = 'Crédito Liquidado'";
			$pestatus ='L';		
		}elseif ($filtro == 8){
			$sfiltro = "and estatus = 'Crédito Vencido'";
			$pestatus ='V';			
		}elseif ($filtro == 8){
			$sfiltro = "";
			$pestatus ='T';
		}
//2018-10-01'		
		$query = "SELECT idcredito, idacreditado, idpagare, acreditado, monto, to_char(fecha_pago, 'DD/MM/YYYY') as fecha_primerpago, to_char(fecha_aprov, 'DD/MM/YYYY') as fecha_aprov, to_char(fecha_dispersa, 'DD/MM/YYYY') as fecha_dispersa, to_char(fecha_entrega, 'DD/MM/YYYY') as fecha_entrega, case when not fecha_entrega is null then to_char(fecha_vence, 'DD/MM/YYYY') else '' end  as fecha_vence1, dias, importe, estatus FROM ".$esquema ."get_amortiza_estatus('".$pestatus."','2021-01-01') where idsucursal ='".$this->session->userdata('sucursal_id')."' ".$sfiltro." order by idcredito ASC, estatus".$sfiltro2;
		$credito = $this->base->querySelect($query, TRUE);
		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"inver" => $inver,
			"credito" => $credito
		);	
		$this->returnData($respuesta);


	}
	
	
	/*
		Status de los creditos de la socia activos 
	*/
	public function getStatusCre_get() {
		$filtro = $this->uri->segment(4);	
		$esquema = $this->esquema;

//and (c.fecha_aprov is null or c.fecha_dispersa is null or c.fecha_entrega is null) 

		$query ="Select c.idcredito, c.idpagare, c.monto, to_char(c.fecha_aprov,'DD/MM/YYYY') as fecha_aprov, to_char(c.fecha_dispersa,'DD/MM/YYYY') as fecha_dispersa, to_char(c.fecha_entrega,'DD/MM/YYYY') as fecha_entrega  from ".$esquema."creditos c join ".$esquema."get_creditos_acreditado ca on ca.acreditadoid = c.idacreditado and ca.idcredito = c.idcredito where ca.idacreditado = ".$filtro." and c.idsucursal='".$this->session->userdata('sucursal_id')."'  order by c.idcredito";

		

		$creditos = $this->base->querySelect($query, TRUE);
		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"creditos" => $creditos
		);	
		$this->returnData($respuesta);
	}

	

	/*
	* Inversion
	 */
	 public function add_inversion_post(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('inversiones_model','inversion');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$datos['importe'] = (double)str_replace(",","",$datos['importe']);
			$datos['interes'] = (double)str_replace(",","",$datos['interes']);
			$datos['total'] = (double)str_replace(",","",$datos['total']);
			$datos['idacreditado'] = $idacreditado;
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run('inversion_post') == TRUE) {
				$addtrans = $this->inversion->transaccion($datos);
				$this->validaCode($addtrans);
			}else {
				$this->validaForm();
			}		
		}
	}




	/*
	* Aportación Social
	 */
	 public function update_inversion_post(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			if ($idacreditado ==  $this->ion_auth->user()->row()->acreditadoid) {
   		        $this->returnCodeSameUser();
			}else {
				$valores = $this->post('data')?$this->post('data', TRUE):array();
				//Carga de los modelos 
				$this->load->model('inversiones_model','inversion');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$datos['importe'] = (double)str_replace(",","",$datos['importe']);
				$datos['total'] = (double)str_replace(",","",$datos['total']);
				$datos['idacreditado'] = $idacreditado;
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('inversion_post') == TRUE) {
					$updatetrans = $this->inversion->transupdate($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}		
			}
		}
	}


	public function reinversion_post(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			if ($idacreditado ==  $this->ion_auth->user()->row()->acreditadoid) {
   		        $this->returnCodeSameUser();
			}else {
				$valores = $this->post('data')?$this->post('data', TRUE):array();
				//Carga de los modelos 
				$this->load->model('inversiones_model','inversion');
				//Creacion del arreglo para el almacenamiento se ejecuta del helper general
				$datos = fn_extraer($valores,'N');
				$datos['incremento'] = (double)str_replace(",","",$datos['incremento']);
				$datos['retiroc'] = (double)str_replace(",","",$datos['retiroc']);
				$datos['retiroi'] = (double)str_replace(",","",$datos['retiroi']);
				$datos['idacreditado'] = $idacreditado;


				if ($datos['retiroccheck'] =="on"  && $datos['retiroicheck'] =="on"){
					$datos['fecha'] = date("Y-m-d");
	//				$datos['fecha'] = date("d-m-Y");
					$datos['importe'] = 1;
					$datos['dias'] = 1;
					$datos['tasa'] = 1;
					$datos['interes'] = 1;
	//				$datos['fechafin'] = date("d-m-Y");
					$datos['fechafin'] = date("Y-m-d");				
					$datos['total'] = 1;
				} else {
					$datos['importe'] = (double)str_replace(",","",$datos['importe']);
					$datos['total'] = (double)str_replace(",","",$datos['total']);
				}
				
				$this->form_validation->set_data( $datos );
				//Valida las reglas 
				if ($this->form_validation->run('inversion_post') == TRUE) {
					$updatetrans = $this->inversion->transreinver($datos);
					$this->validaCode($updatetrans);
				}else {
					$this->validaForm();
				}		
			}
		}
	}




	public function colmenas_get(){
		$empresa ='F';
		if ($this->esquema =='imp.'){
			$empresa ='I';
		}else if ($this->esquema !='fin.'){
			$empresa ='B';
		}
		$tabla = $this->esquema."get_colmena_grupo as a";
		$full = $this->uri->segment(4);
		
		if ($full == 1){
			$where = array();
		}else {
			if ($empresa =='F' || $empresa =='B') {
				$where = array('a.idsucursal' => $this->session->userdata('sucursal_id'));

			}else
				$where = array('a.idsucursal' => $this->session->userdata('sucursal_id')			,
						'a.empresa' => $empresa);
		}
        $select ="a.idcolmena as value, (colmena_numero::text || ' ' || a.colmena_nombre) as name";
		$group_by = array("a.idcolmena","colmena_numero::text || ' ' || a.colmena_nombre");
		$order_by = array(array('campo'=> 'a.idcolmena',
				  'direccion'=>	'asc'));
		$cat_grupo_orden =  array(
			array('value'=> '1', 'name'=>'1'),
			array('value'=> '2', 'name'=>'2'),
			array('value'=> '3', 'name'=>'3'),
			array('value'=> '4', 'name'=>'4'),
			array('value'=> '5', 'name'=>'5')
		);


		$colmenas = $this->base->selectRecord($tabla, $select, "", $where, "","", $group_by, $order_by, "","", true);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,2,4));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_col_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,3));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_grupo_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);

		if ($colmenas) {
			$data = array("status"=>"OK",
				"code" => 200,
				"catcolmenas" => $colmenas,
				"cat_grupo_orden"=> $cat_grupo_orden,
				"cat_col_cargos" => $cat_col_cargos,
				"cat_grupo_cargos" => $cat_grupo_cargos
			);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas,
				"cat_col_cargos" => [],
				"cat_grupo_cargos" => []
				);
		}
		$this->returnData($data);	
	}

	

	public function colmenas_grupos_get(){
		$idcolmena = $this->uri->segment(4);
        $tabla = $this->esquema."get_colmena_grupo as a";
		$where = array('a.idsucursal' => $this->session->userdata('sucursal_id'),
					  'a.idcolmena' => $idcolmena);
		$order_by = array(array('campo'=> 'a.idgrupo',
				  'direccion'=>	'asc'));
        $select ="a.idgrupo as value, a.grupo_nombre as name";
		$grupos = $this->base->selectRecord($tabla, $select, "", $where, "","", "", $order_by, "","", FALSE);
		$this->validaCode($grupos);
	}


	/* 
	* Obtiene las denominaciones de los billertes o monedas del catalogo 
	*/
	public function getDenomina_get(){
		$query = "Select iddenomina, nombre, 0 as cantidad, 0 as total from public.cat_denomina order by iddenomina";
		$denomina = $this->base->querySelect($query, FALSE);
		$this->validaCode($denomina);		
	}


	public function getSaldoBoveda_get(){
		$idmov = $this->uri->segment(4);
		$esquema = $this->esquema;	
		$fecha = date("Y-m-d");
		$query = "select  iddenomina, nombre, cantidad as saldo, cantidad1 as cantidad, total, importe from ".$esquema."get_bovedas_salini('".$fecha."',".$idmov.") order by iddenomina";
		$denomina = $this->base->querySelect($query, FALSE);
		$sumatotal = 0;
		foreach ($denomina['result'] as $key => $value) {
			$sumatotal = $sumatotal + $value['importe'];
		}
		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"result" => $denomina['result'],
			"saldo" => $sumatotal
		);		
		$this->returnData($respuesta);
	}


	public function getSaldoBoveda($id){
		$esquema = $this->esquema;	
		$fecha = date("Y-m-d");

		//Obtener el idmov ultimo de boveda 
		$query = "select  idmov from ".$esquema."boveda where idclave='".$id."' order by fecfinal desc limit 1";
		$boveda = $this->base->querySelect($query, TRUE);

		$caja = [];
		$idmov = -1;
		if ($boveda[0]){
			$idmov = $boveda[0]['idmov'];
			$query = "Select a.*, b.first_name || ' ' || b.last_name as nomuser from ".$this->esquema."boveda as a inner join security.users as b on 
				cast(a.iduser_a as integer) = b.id where idmov =".$idmov;
			$caja = $this->base->querySelect($query, TRUE);
		}
		$query = "select  iddenomina, nombre, cantidad as saldo, cantidad1 as cantidad, total, importe from ".$esquema."get_bovedas_salini('".$fecha."',".$idmov.") order by iddenomina";
		$denomina = $this->base->querySelect($query, FALSE);
		$result = [];
		if ($denomina['result']) {
			$result = $denomina['result'];
		}
		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"result" => $result,
			"mov" => $caja
		);		
		return $respuesta;
	}


	
	public function bovedas_get(){
		$query = "Select idboveda as value, nombre as name from public.bovedas where idsucursal ='".$this->session->userdata('sucursal_id')."' and ".substr($this->esquema,0,3)."=true";
		$boveda = $this->base->querySelect($query, TRUE);
		$query = "Select iddenomina, nombre, 0 as cantidad, 0 as total from public.cat_denomina order by iddenomina";
		$denomina = $this->base->querySelect($query, TRUE);
		if ($denomina){
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"boveda" => $boveda,
				"denomina" => $denomina
			);
		}else {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"boveda" => $boveda,
				"denomina" => $denomina
			);
		}
		$this->returnData($respuesta);
	}



	public function getboveda_get(){
		$idboveda = $this->uri->segment(4);
		$fecha = date("Y-m-d");
		$fecCierre = date("d/m/Y");	
		// Busca si existe boveda abiertas de dias anteriores 
		$query = "Select fecinicio::date as fecinicio, fecfinal, status, idmov from ".$this->esquema."boveda where idclave='".$idboveda."' and tipo='1' and fecinicio::date <'".$fecha."' and idsucursal='".$this->session->userdata('sucursal_id')."' and status ='1' and fecfinal is null  order by fecinicio ASC limit 1";
		$bovedant = $this->base->querySelect($query, TRUE);

		if ($bovedant) {
			$boveda = array();
			$query = "Select idmovdet as value, (case when movimiento ='E' then 'Egreso' else 'Ingreso' end || ', ' || case when des_ori ='C' then 'Caja' else 'Banco' end || '(' || idbanco || ')' || ', ' || importe || ', (' || to_char(fecha,'DD/MM/YYYY HH:MM:SS')) || ')' as name from ".$this->esquema."boveda_mov where idmov='".$bovedant[0]['idmov']."' and fecha::date='".$bovedant[0]['fecinicio']."'";
			$movimientos = $this->base->querySelect($query, TRUE);	
			if (!$movimientos){
				$movimientos = array();
			}
		}else {
			$query = "Select fecinicio, fecfinal, status, idmov from ".$this->esquema."boveda where idclave='".$idboveda."' and tipo='1' and fecinicio::date='".$fecha."' and idsucursal='".$this->session->userdata('sucursal_id')."'";
			$boveda = $this->base->querySelect($query, TRUE);
			if ($boveda) {
				$query = "Select idmovdet as value, (case when movimiento ='E' then 'Egreso' else 'Ingreso' end || ', ' || case when des_ori ='C' then 'Caja' else 'Banco' end || '(' || idbanco || ')' || ', ' || importe || ', Fecha: ' || to_char(fecha,'DD/MM/YYYY HH:MM:SS')) as name from ".$this->esquema."boveda_mov where idmov='".$boveda[0]['idmov']."' and fecha::date='".$fecha."'";
				$movimientos = $this->base->querySelect($query, TRUE);	
				if (!$movimientos){
					$movimientos = array();
				}
			}else {
				$movimientos = array();			
			}		
		}


		//Se integra los movimientos del dia de cada caja (Egresos)
        $filtro= "b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."' and a.movimiento ='E' and a.des_ori ='C'";
		$query = "Select b.fecfinal, b.status, b.idmov,a.idbanco as value, ('Movimientos del dia' || to_char(b.fecfinal,'DD/MM/YYYY') || ', ' || 'Caja ' || '(' || a.idbanco || ')' ) as name from ".$this->esquema."boveda_mov as a inner join ".$this->esquema."boveda  as b on b.idmov = a.idmov where ".$filtro;
		$movimientosdia = $this->base->querySelect($query, TRUE);
		if ($movimientosdia){
			foreach ($movimientosdia as $key => $value) {
				array_push($movimientos,$value);
			}
		}
		// Agrega opción para reporte de creditos con intereses e IVA
		array_push($movimientos,array('value' => '1', 'name' => 'Recuperación de Créditos ('.$fecCierre.')'));
		array_push($movimientos,array('value' => '2', 'name' => 'Detalle de boveda'));
		array_push($movimientos,array('value' => '3', 'name' => 'Movimientos de boveda operativa'));
		array_push($movimientos,array('value' => '4', 'name' => 'Movimientos de caja operativa'));
		

		array_push($movimientos,array('value' => '5', 'name' => 'Saldo de boveda ('.date("d/m/Y").')'));


		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"result" => $boveda,
			"movimientos" => $movimientos,
			"anterior" => $bovedant

		);
		$this->returnData($respuesta);
	}

	public function getboveda_mov_get(){
		$idmov = $this->uri->segment(4);		
		$fecha = $this->uri->segment(5);
		$idclave = $this->uri->segment(6);
		$saldoboveda = true;
		$fecCierre ="";
		$filtroclave =" and b.idclave ='".$idclave."'";
		if (is_null($fecha)){
	        $fecha = date("Y-m-d");	
	        $filtro= "a.fecha::date='".$fecha."'".$filtroclave;
//	        $filtro= "a.fecha::date='".$fecha."'";
//	        $filtro= "a.idmov='".$idmov."' and a.fecha::date='".$fecha."'";
	        $fecCierre = date("d/m/Y");	
		}else{
	        $fecCierre = substr($fecha,0,2).'/'.substr($fecha,2,2).'/'.substr($fecha,4);
			$fecha =substr($fecha,4).'-'.substr($fecha,2,2).'-'.substr($fecha,0,2);
	        $filtro= "b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."'".$filtroclave;			
//	        $filtro= "b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."'";
//	        $filtro= "b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."' and a.idmov='".$idmov."'";
		}
		$query = "Select b.fecfinal, b.status, b.idmov,a.idmovdet as value, (case when a.movimiento ='E' then 'Egreso' else 'Ingreso' end || ', ' || case when a.des_ori ='C' then 'Caja' else 'Banco' end || '(' || a.idbanco || ')' || ', ' || a.importe || ', (' || to_char(a.fecha,'DD/MM/YYYY HH:MM:SS')) || ')' as name from ".$this->esquema."boveda_mov as a inner join ".$this->esquema."boveda  as b on b.idmov = a.idmov where ".$filtro;
		$movimientos = $this->base->querySelect($query, TRUE);	
		if (!$movimientos){
            $movimientos = array(); 
		}else {
			if ($movimientos[0]['status'] ==0){
				$saldoboveda = false;
				$agrega =Array ( 'fecfinal' => $movimientos[0]['fecfinal'], 'status' => '0', 'value' => $movimientos[0]['idmov'], 'name' => 'Cierre Boveda ('.$fecCierre.')' );
				array_push($movimientos,$agrega);
			}
		}

		//Se integra los movimientos del dia de cada caja (Egresos)
        $filtro= "b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."' and a.movimiento ='E' and a.des_ori ='C'";
//        $filtro= "a.idmov='".$idmov."' and b.idsucursal='".$this->session->userdata('sucursal_id')."' and a.fecha::date='".$fecha."' and a.movimiento ='E' and a.des_ori ='C'";
		$query = "Select b.fecfinal, b.status, b.idmov,a.idbanco as value, ('Movimientos del dia (' || to_char(a.fecha,'DD/MM/YYYY') || '), ' || 'Caja ' || '(' || a.idbanco || ')' ) as name from ".$this->esquema."boveda_mov as a inner join ".$this->esquema."boveda  as b on b.idmov = a.idmov where ".$filtro;
		$movimientosdia = $this->base->querySelect($query, TRUE);
		if ($movimientosdia){
			foreach ($movimientosdia as $key => $value) {
				array_push($movimientos,$value);
			}
		}

		// Agrega opción para reporte de creditos con intereses e IVA
		array_push($movimientos,array('value' => '1', 'name' => 'Recuperación de Créditos ('.$fecCierre.')'));
		array_push($movimientos,array('value' => '2', 'name' => 'Detalle de boveda'));
		array_push($movimientos,array('value' => '3', 'name' => 'Movimientos de boveda operativa'));
		array_push($movimientos,array('value' => '4', 'name' => 'Movimientos de caja operativa'));


		// Agregar los dos movimientos de 
		if ($this->ion_auth->in_group('contabilidad')){
			if ($this->session->userdata('opcion') =='contabil'){
				array_push($movimientos,['value' => '1', 'name'=> 'Póliza de diario ('.$fecCierre.')' ]);
				array_push($movimientos,['value' => '1', 'name'=> 'Póliza de diario global ('.$fecCierre.')' ]);
			}
		}

		if ($saldoboveda == true){
			array_push($movimientos,array('value' => '5', 'name' => 'Saldo de boveda ('.date("d/m/Y").')'));
		}

		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"result" => $movimientos
		);
		$this->returnData($respuesta);
		
	}


	public function openboveda_post(){
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		//Carga de los modelos 
		$this->load->model('boveda_model','boveda');
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('boveda_post') == TRUE) {
			$updatetrans = $this->boveda->transaction($datos);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}


	public function closeboveda_post(){
		$idmov = $this->uri->segment(4);
		if (!is_numeric($idmov)){
			$this->returnCode();
		 }else {
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('boveda_model','boveda');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run('boveda_post') == TRUE) {
				$datos['idmov'] = $idmov;
				$updatetrans = $this->boveda->transacClose($datos);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}
		}
	}




	public function add_boveda_post() {
		$idmov = $this->uri->segment(4);
		if (!is_numeric($idmov)){
			$this->returnCode();
		 }else {
			$fechacierre = $this->post('fechacierre')?$this->post('fechacierre', TRUE):'';
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('bovedamov_model','boveda');		
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			//Validacion de los datos del formulario
			$datos['fechacierre'] = $fechacierre;
			$datos['idmov'] = $idmov;
			$datos['grantotal'] = (double)str_replace(",","",$datos['grantotal']);
			$datos['importe'] = (double)str_replace(",","",$datos['importe']);
			$datos['tipo'] = 'O';
			$datos['status'] = '1';
			if (($this->esquema =='ban.' || $this->esquema =='imp.') && $datos['movimiento'] == "E" && $datos['des_ori'] == "C"){
				$validation ='bovedamovban_post';
			}else{
				$validation ='bovedamov_post';
			}
			if ($datos['movimiento'] == "I" && $datos['des_ori'] == "C" && $datos['grantotal'] == 0  && $datos['importe'] == 0){
				$validation ='bovedamovcierre_post';
			}
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run($validation) == TRUE) {
				$detalle = array();
				foreach($datos as $key => $value) {
					if (is_array($value)) {
						foreach($value as $key2 => $value2)	{
							$detalle[$key2][$key] = $value2;
						}
					}
				}
				$inserttrans = $this->boveda->transacmov($datos,$detalle);
				$this->validaCode($inserttrans);
			}else {
				$this->validaForm();
			}
		 }	
	}
	
	public function cajavalida(){
	
	}

	public function findCorteBoveda($idmov, $tipo = 0) {
		$query = "Select a.*, b.first_name || ' ' || b.last_name as nomuser from ".$this->esquema."boveda as a inner join security.users as b on 
			cast(a.iduser_a as integer) = b.id where idmov =".$idmov;
		$caja = $this->base->querySelect($query, TRUE);
		if ($caja) {
			if ($tipo == 0){
				$query ="select b.nombre,sum(a.cantidad) as cantidad, (cast(b.nombre as numeric) * sum(a.cantidad)) as total from ".$this->esquema."boveda_mov_det as a join public.cat_denomina as b on b.iddenomina = a.iddenomina inner join ".$this->esquema."boveda_mov as c 
					on c.idmovdet = a.idmovdet where c.idmov = ".$idmov." and c.status ='1' group by b.nombre,b.iddenomina order by b.iddenomina";
			}else {
				$query ="select b.nombre,sum(a.cantidad) as cantidad, (cast(b.nombre as numeric) * sum(a.cantidad)) as total from ".$this->esquema."boveda_sal_det as a join public.cat_denomina as b on b.iddenomina = a.iddenomina inner join ".$this->esquema."boveda_sal as c 
					on c.idmov = a.idmov where c.idmov = ".$idmov."  group by b.nombre,b.iddenomina order by b.iddenomina";
			}
			$cajadet = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $caja,
				"movdet" => $cajadet
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Nota de crédito o cierre inexistente!",
				"mov" => [],
				"movdet" => []

			);
		}

		 return $respuesta;
	}

	public function findBovedaMov($idbov, $fecini, $fecfin) {
		$query = "select * from ".$this->esquema."get_bovedamov('".$fecini."','".$fecfin."','".$idbov."')  order by fecha";
		$bov = $this->base->querySelect($query, TRUE);
		if ($bov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"movdet" => $bov
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Sin movimientos!",
				"movdet" => []
			);
		}

		 return $respuesta;
	}


	public function findBovedaOpera($idbov, $fecini, $fecfin) {
		$query = "select fecha::date, orden, movimiento, sum(importe) as importe from ".$this->esquema."get_bovedamov('".$fecini."','".$fecfin."','".$idbov."') group by fecha::date, orden, movimiento order by fecha::date, orden, movimiento desc";
		$bov = $this->base->querySelect($query, TRUE);
		if ($bov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"movdet" => $bov
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Sin movimientos!",
				"movdet" => []
			);
		}

		 return $respuesta;
	}
	
	public function findBovedaOperaIni($idbov, $fecini, $fecfin) {
		$query = "select fecha::date, orden, movimiento, importe, saldo from ".$this->esquema."get_bovedamov('".$fecini."','".$fecfin."','".$idbov."') order by fecha, orden, movimiento limit 1";
		$bov = $this->base->querySelect($query, TRUE);
		if ($bov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"movdet" => $bov
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Sin movimientos!",
				"movdet" => []
			);
		}

		 return $respuesta;
	}




	public function findCajaOpera($idbov, $idsuc, $fecini, $fecfin) {
		//Busca las cajas de la boveda correspondiente
		$query = "select b.idbanco from ".$this->esquema."boveda a join ".$this->esquema."boveda_mov b on b.idmov = a.idmov where a.idclave ='".$idbov."' and b.movimiento ='E' and b.des_ori= 'C' and b.tipo ='O' and a.fecinicio::date ='".$fecini. "' group by b.idbanco";
		$caja = $this->base->querySelect($query, TRUE);
		$filter = "";
		foreach ($caja as $key => $value) {
			$filter = ($filter==""?$filter:$filter.",")."'".$value['idbanco']."'";
		}
		$query = "select fecha::date,cabo,idcaja,count(cabo) as no, sum(importe) as importe from ".$this->esquema."get_movimientosdia('".$fecini."','".$fecfin."','0','".$idsuc."') where idcaja <>'' and idcaja in (".$filter.") group by fecha::date, cabo,idcaja order by fecha::date,cabo,idcaja";
		$bov = $this->base->querySelect($query, TRUE);
		if ($bov) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"movdet" => $bov
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Sin movimientos!",
				"movdet" => []
			);
		}

		 return $respuesta;
	}


	
	public function findMovCaja($idcaja, $fecha) {
		$query = "select * from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','".$idcaja."','')  ";
		$caja = $this->base->querySelect($query, TRUE);

		if ($caja) {
			$query = "select a.*, b.first_name || ' ' || b.last_name as nombre from cajas as a inner join security.users as b on a.iduser = b.id where a.idcaja ='".$idcaja."'";
			$user = $this->base->querySelect($query, TRUE);
			$query = "select nombre, count(nomov) as numero, sum(importe) as importe from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','".$idcaja."','') group by orden,nombre order by orden";
			$resumen = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"user" => $user,
				"mov" => $caja,
				"resumen" => $resumen
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Nota de crédito o cierre inexistente!",
				"mov" => [],
				"resumen" => []
			);
		}

		 return $respuesta;
	}

	public function findMovCreditos($fecha) {
		$query = "select * from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') where orden ='C' order by fecha";
		$caja = $this->base->querySelect($query, TRUE);
		if ($caja) {
			$query = "select nombre, count(nomov) as numero, sum(importe) as importe, sum(interes) as interes, sum(iva) as iva from ".$this->esquema."get_movimientosdia('".$fecha."','".$fecha."','0','".$this->session->userdata('sucursal_id')."') where orden ='C' group by orden,nombre order by orden";
			$resumen = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $caja,
				"resumen" => $resumen
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Nota de crédito o cierre inexistente!",
				"mov" => [],
				"resumen" => []
			);
		}

		 return $respuesta;
	}
	

    public function getSaldoCaja_get(){
		$fecom =  date("Y-m-d");
		$caja = $this->session->userdata('idcaja');

		$cierreCajAnt = false;
		$idmovAnt = 0;
		$fechacierreant = '';
		// Busca si existe boveda abiertas de dias anteriores de la sucursal 
		$query = "Select idsucursal, fecinicio::date as fecinicio, fecfinal, status, idmov from ".$this->esquema."boveda where tipo='1' and fecinicio::date <'".$fecom."' and idsucursal='".$this->session->userdata('sucursal_id')."' and status ='1' and fecfinal is null  order by fecinicio ASC limit 1";
		$bovedant = $this->base->querySelect($query, TRUE);
		if ($bovedant) {
			
			// Busca si existe movimientos de Notas de créditos y/o caja crear 
			$query = "select * from ".$this->esquema."boveda_mov as a where (a.tipo ='N' or a.tipo ='C') and a.des_ori='C' and a.idbanco='".$caja."' and a.status ='0' and a.idmov =".$bovedant[0]['idmov'];
			$queryCajaAnt = $this->base->querySelect($query, TRUE);
			$cajaAnt = [];
			if (!$queryCajaAnt) {
			   //No lo encontro 
				$cierreCajAnt = true;
				$idmovAnt = $bovedant[0]['idmov'];
				$fecom = $bovedant[0]['fecinicio'];
				$fechacierreant = $fecom;
			}

			$query = "Select * from ".$this->esquema."get_bovedas_saldos('".$fecom."','C','".$caja."') where idsucursal ='".$this->session->userdata('sucursal_id')."'";
			$caja = $this->base->querySelect($query, TRUE);	

		}else {
			$query = "Select * from ".$this->esquema."get_bovedas_saldos('".$fecom."','C','".$caja."') where idsucursal ='".$this->session->userdata('sucursal_id')."'";
			$caja = $this->base->querySelect($query, TRUE);	
		}


		$respuesta = array("status"=>"OK",
			"code" => "200",
			"message"=>"Registro(s) obtenido(s) correctamente",
			"result" => $caja,
			"cierrecajant" => $cierreCajAnt,
			"movAnt" => $idmovAnt,
			"fechacierre" => $fechacierreant
		);
		$this->returnData($respuesta);

	}	



	public function getCorteCaja_get(){
		$idmov = $this->uri->segment(4);
		$idcaja =  $this->uri->segment(5);
		if (!is_numeric($idmov) || !is_numeric($idcaja)){
			$this->returnCode();
		 }else {			 
			$query = "Select idmovdet, importe, tipo from ".$this->esquema."boveda_mov where idmov =".$idmov." and movimiento='I' and des_ori='C' and idbanco ='".$idcaja."' and status='0'";
			$caja = $this->base->querySelect($query, TRUE);
			if ($caja) {
				$query = "select b.nombre, a.cantidad, (cast(b.nombre as numeric) * a.cantidad) as total from ".$this->esquema."boveda_mov_det as a join public.cat_denomina as b on b.iddenomina = a.iddenomina where a.idmovdet = ".$caja[0]['idmovdet']." order by b.iddenomina ";
				$cajadet = $this->base->querySelect($query, TRUE);
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Registro(s) obtenido(s) correctamente",
					"mov" => $caja,
					"movdet" => $cajadet
				);
			}else{
				$respuesta = array("status"=>"OK",
					"code" => "404",
					"message"=>"Nota de crédito o cierre inexistente!",
					"mov" => [],
					"movdet" => []

				);
			}
			$this->returnData($respuesta);
		 }
	}



	public function findCorteCaja($idmov) {
		$query = "Select a.*, b.first_name || ' ' || b.last_name as nomuser1, c.first_name || ' ' || c.last_name as nomuser2, d.iduser, d.descripcion, e.first_name || ' ' || e.last_name as nomcaja, f.nombre || ' ' || g.cuentabanco as nombanco from ".$this->esquema."boveda_mov as a inner join security.users as b on 
			cast(a.usuario as integer) = b.id left join security.users as c on cast(a.usuarioaut as integer) = c.id left join public.cajas as d on a.idbanco = d.idcaja left join security.users as e on d.iduser = e.id left join public.banco_detalles as g on cast(g.idbancodet as varchar) = a.idbanco left join public.bancos as f on f.idbanco = g.idbanco where idmovdet =".$idmov;
		$caja = $this->base->querySelect($query, TRUE);

		if ($caja) {
			$query = "select b.nombre, a.cantidad, (cast(b.nombre as numeric) * a.cantidad) as total from ".$this->esquema."boveda_mov_det as a join public.cat_denomina as b on b.iddenomina = a.iddenomina where a.idmovdet = ".$caja[0]['idmovdet']." order by b.iddenomina ";
			$cajadet = $this->base->querySelect($query, TRUE);


			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"mov" => $caja,
				"movdet" => $cajadet
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Nota de crédito o cierre inexistente!",
				"mov" => [],
				"movdet" => []
			);
		}

		 return $respuesta;
	}



	public function add_caja_post() {
		$idmov = $this->uri->segment(4);
		if (!is_numeric($idmov)){
			$this->returnCode();
		 }else {

			$fechacierre =  $this->post('fechacierre')?$this->post('fechacierre', TRUE):'';

			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('bovedamov_model','boveda');		
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			//Validacion de los datos del formulario
			$datos['fechacierre'] = $fechacierre;
			$datos['idmov'] = $idmov;
			$datos['movimiento'] = "I";
			$datos['des_ori'] = "C";
			$datos['idbanco'] = $this->session->userdata('idcaja');
			$datos['grantotal'] = (double)str_replace(",","",$datos['grantotalcorte']);
			$datos['importe'] = (double)str_replace(",","",$datos['grantotalcorte']);
			$datos['status'] = '0';
			$datos['tipo'] = $datos['movcaja'];
			$validation ='bovedamov_post';
			if ($datos['tipo'] =='C' && $datos['grantotal']  == 0 && $datos['importe']  == 0){
				$validation ='bovedamovcierre_post';
			}
			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			if ($this->form_validation->run($validation) == TRUE) {
				$detalle = array();
				foreach($datos as $key => $value) {
					if (is_array($value)) {
						foreach($value as $key2 => $value2)	{
							$detalle[$key2][$key] = $value2;
						}
					}
				}
				$inserttrans = $this->boveda->transacmov($datos,$detalle);
				$this->validaCode($inserttrans);
			}else {
				$this->validaForm();
			}
		 }
	}
	
	



	public function add_pagind_post() {
		$idmov = $this->uri->segment(4);
		if (!is_numeric($idmov)){
			$this->returnCode();
		 }else {
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('cindividual_model','credito');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			//Validacion de los datos del formulario

			$datos['fecha_pago']= (double)str_replace(",","",$datos['fecha_pagoi']);



			$datos['condonacion']= (double)str_replace(",","",$datos['condonacion']);
			$datos['gastos']= (double)str_replace(",","",$datos['gastos']);
			$datos['efectivo']= (double)str_replace(",","",$datos['efectivo']);			
			$datos['importepagar'] = (double)str_replace(",","",$datos['importepagar']);

//			$fecha_pago = $datos['fecha_pagoi'];

			$fecha = explode("/", $datos['fecha_pagoi']); 
			$fecha_pago =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;


			$this->form_validation->set_data( $datos );
			//Valida las reglas 
			 if ($this->form_validation->run('creditoindividual_post') == TRUE) {
				
				$updatetrans = $this->credito->transaccion($datos, $fecha_pago);
				$this->validaCode($updatetrans);
				if ($updatetrans['code'] == 200){
//					$this->print($updatetrans['nomov']);				
				}
			 }else {
				$this->validaForm();
			 }




		 }
	}
	



	public function findCuentas($idacreditado, $cuentas, $fecini, $fecfin) {
		$claves = explode("-", $cuentas);
		$filtros = "";

		if ($claves[0] != ''){
			$filtros = " where idproducto in (";
			foreach ($claves as $key => $value) {
				$filtros = $filtros."'".$value."',";
			}
			$filtros = substr($filtros,0, strlen($filtros)-4 );
			$filtros = $filtros.")";
		}

		
		if ($fecini !=''){
			if ($filtros ==''){
				$filtros = " where ";
			}else {
				$filtros = $filtros." and ";
			}
			$filtros = $filtros."(fecha::date >='".$fecini."'";
		}

		if ($fecfin !='') {
			if ($filtros ==''){
				$filtros = " where (";
			}else {
				$filtros = $filtros. " and ";
				if ($fecini ==''){
				   $filtros = $filtros. " (";

				}

			}
			$filtros = $filtros." fecha::date <='".$fecfin."')";
		}else {
	   	    if ($fecini !='' || $fecfin !=''){
			   $filtros = $filtros.")";
			}
		}



		$query = "select * from ".$this->esquema."get_cuentasacreditada(".$idacreditado.")".$filtros;
		$caja = $this->base->querySelect($query, TRUE);
		$query =  "select * from public.get_acreditados where idacreditado =".$idacreditado;
		$acre = $this->base->querySelect($query, TRUE);	
		$resumen = [];
		if ($caja  && $acre) {
			$query = "select orden,cuenta, nombre, count(nombre) as numero, sum(importe) as importe from ".$this->esquema."get_cuentasacreditada(".$idacreditado.")".$filtros." group by cuenta,orden,nombre order by cuenta,orden";
			$resumen = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"acreditada" => $acre,
				"mov" => $caja,
				"resumen" => $resumen
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Registro inexistente!",
				"acreditada" => $acre,
				"mov" => [],
				"resumen" => $resumen
			);
		}

		 return $respuesta;
	}



	public function findCuentasResumen($idacreditado, $cuentas) {

		$claves = explode("-", $cuentas);
		$filtros = "";
		if ($claves[0] != ''){
			$filtros = " where idproducto in (";
			foreach ($claves as $key => $value) {
				$filtros = $filtros."'".$value."',";
			}
			$filtros = substr($filtros,0, strlen($filtros)-4 );
			$filtros = $filtros.") ";
		}
		$query = "select orden, fecapertura, fecbaja, cuenta, sum(importe) as importe from ".$this->esquema."get_cuentasacreditada(".$idacreditado.")".$filtros."group by orden,fecapertura, fecbaja,cuenta order by cuenta";
		$caja = $this->base->querySelect($query, TRUE);
		$query =  "select * from public.get_acreditados where idacreditado =".$idacreditado;
		$acre = $this->base->querySelect($query, TRUE);	
		$resumen = [];
		if ($caja  && $acre) {
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"acreditada" => $acre,
				"mov" => $caja
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Registro inexistente!",
				"acreditada" => $acre,
				"mov" => []
			);
		}

		 return $respuesta;
	}


	public function findCuentaSaldos($fecfin) {
		$query = "select idproducto, idacreditado, nombre, producto || ' ' || numero_cuenta as producto, saldo from ".$this->esquema."get_ahorros_voluntariosg('R','".$fecfin."') where idsucursal ='".$this->session->userdata('sucursal_id')."' order by idacreditado, idproducto";
		$saldos = $this->base->querySelect($query, TRUE);
		if ($saldos) {
			$query = "select idproducto, nombre from public.productos order by idproducto";
			$enca = $this->base->querySelect($query, TRUE);
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"saldos" => $saldos,
				"enca" => $enca,
			);
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Registro inexistente!",
				"saldos" => [],
				"enca" => [],
				
			);
		}
		 return $respuesta;
	}



	public function getPagoInd_get() {
		$fecha = $this->uri->segment(4);
		$idcredito = $this->uri->segment(5);
	    $fecCierre =substr($fecha,4).'-'.substr($fecha,2,2).'-'.substr($fecha,0,2);
		$giva ='0';
		$query = "SELECT iva, idsucursal FROM ".$this->esquema."creditos where idcredito =".$idcredito;
		$data = $this->base->querySelect($query, TRUE);
		$editar = true;
		if ($data) {
			$giva =$data[0]['iva'];
			if  ($this->session->userdata('sucursal_id') != $data[0]['idsucursal']) {
				$editar = false;
			}
			
		}

		$query = "SELECT * FROM ".$this->esquema."get_edocta_ind(".$idcredito.", '".$fecCierre."') order by fecfinal desc";
/* 
		$query = "SELECT numero, fecha_vence as fecha, p_fecha as fecha_pago, dias, p_capital as pag_capital, p_interes as interes, p_iva as iva, p_pago_total as total_pagado,
			capital_saldo as saldo_capital, coalesce(p_mora,0) as int_mora, capital_pagado, capital_requerido, c_interes_acumula, c_mora, c_iva FROM ".$this->esquema."ftr_pago_individual(".$idcredito.", '".$fecCierre."')"; */			
		$data = $this->base->querySelect($query, TRUE);
/* 		$query = "SELECT idcredito, fecha, capital, interes, interes_mora, iva, msg FROM ".$this->esquema."get_saldo_pago_ind(".$idcredito.", '".$fecCierre."')";
 */
		if ($data) {
			$capital_req=0;
			$capital_pag=0;
			$interes_req=0;
			$interes_mora=0;
			$capital_saldo=0;
			$dias_saldo=0;
			$capital_inicial=0;
			$iva = 0;
			$esp_total_t = 0;
/*			
			
			foreach($data as $key => $value) {
				$fecha = date_create($value['fecha']);
				$fecha = date_format($fecha,'d/m/Y');
				$fechaPago = date_create($value['fecha_pago']);
				$fechaPago = date_format($fechaPago,'d/m/Y');
				if ($value['numero']<>-99){
					$iva = ($value['c_interes_acumula'] + $value['c_mora'])*0.16;
					$dias_saldo = $value['dias'];
					$capital_saldo = $value['saldo_capital'];
					$capital_req = $value['capital_requerido'];
					$capital_pag = $value['capital_pagado'];
					$interes_req += $value['c_interes_acumula']; // + $value['c_mora'];
					$interes_mora = $value['c_mora'];
				}
				else{
					$capital_inicial=$value['saldo_capital'];
				}
			}
			if ($capital_pag <= $capital_inicial )
			{
				$esp_capital = 0;
				$esp_int = 0;
				if ($capital_req > $capital_pag ){
					$esp_capital = $capital_req - $capital_pag;
					$esp_int = $interes_req;
				}
				$esp_iva = ($esp_int+$interes_mora) * 0.16;
				$esp_total = $esp_capital + $esp_int + $esp_iva;

				$esp_capital = 0;
				$esp_int = 0;
				$esp_iva = 0;
				if ($capital_req > $capital_pag ){
					$esp_capital = $capital_req - $capital_pag;
					$esp_int = $interes_req;
				}
				$esp_iva = ($esp_int+$interes_mora) *  0.16;
				$esp_total_t = $capital_saldo + $esp_int + $esp_iva;


			}
			else{


			}

*/
		  $primer = $data[0];
		 $esp_capital = $primer['importe_acum'] - $primer['capital_acum'];
		 if ($esp_capital < 0) {
			 $esp_capital = 0;
		 }
		$saldo_vencido = $primer['saldovencido'];
		$interes_mora = $primer['interesmora_acum'] - ($primer['interes_m_acum'] + $primer['condona_m_acum']);
		 $esp_int =  $primer['interesacumulado'] - ($primer['interes_n_acum'] + $primer['condona_n_acum']);
		 if ($giva =='1') {
  		     $esp_iva =(($esp_int + $interes_mora) * 0.16);
		 }else {
		     $esp_iva =0;
		 }
		  
		  $esp_total = $esp_capital + $esp_int + $interes_mora + $esp_iva;
		  $esp_total_t = $primer['saldo'] + $esp_int + $interes_mora + $esp_iva;
		  $capporvencer = $primer['saldo'] -  $esp_capital;
		  $capita_porvencer = $capporvencer< 0?0:$capporvencer;

				$saldos = array('idcredito'=> $idcredito,
								'fecha' => $fecCierre, 
								'editar' => $editar,								
								'saldovencido' => $saldo_vencido,
								'capital'  => $esp_capital,
								'capital_porvencer' => $capita_porvencer,
								'interes' => $esp_int,
								'interes_mora' => $interes_mora, 
								'iva' => $esp_iva,
								'gIva'=> $giva,
								'total' => $esp_total,
							    'liquidar' => $esp_total_t);

			$respuesta = array("status"=>"OK",
				"code" => "200",
				"message"=>"Registro(s) obtenido(s) correctamente",
				"saldos" => $saldos

			);
			
		}else{
			$respuesta = array("status"=>"OK",
				"code" => "404",
				"message"=>"Registro inexistente!",				
				"saldos" => []
				
				
			);
		}
		$this->returnData($respuesta);
	
	}


	/*
	* obtiene la paginación y los registros
	*/
	public function pagina_get() {
		$this->load->helper('paginacion');
		$pagina = $this->uri->segment(4);
		$por_pagina = $this->uri->segment(5);	
		$tabla = "bancos";
		if ($content = $this->get('from', TRUE)) {
			$tabla = $content;
		}
		$order_by = array();
		if ($content = $this->get('orderby', TRUE)) {
			$order_by = $content;
		}		
		$respuesta = pagina_table( $tabla , $pagina, $por_pagina, $order_by);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}




	public function getInstrumento_get(){
		$query = "select idinstrumento as value, descripcion as name from public.cat_instrumento where estado = '1'";
		$instrumento = $this->base->querySelect($query, FALSE, "catinstrumento");
		$this->returnData($instrumento);
	}
	

	/*
	* Seguros de vida del credito
	*/
	public function seguros_post(){
		$idcredito = $this->uri->segment(4);
		if (!is_numeric($idcredito)){
		   $this->returnCodeWithToken();
		}else {
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			//Carga de los modelos 
			$this->load->model('Seguro_model','seguro');
			//Creacion del arreglo para el almacenamiento se ejecuta del helper general
			$datos = fn_extraer($valores,'N');
			$datos['monto'] = (double)str_replace(",","",$datos['monto_s']);
			$datos['idacreditado'] = $datos['idacreseg'];
			$datos['idpagare'] = $datos['idpagseg'];
			$datos['esquema'] = $datos['esquema'];
			
			$this->form_validation->set_data( $datos );
			//Valida las reglas 

			if ($this->form_validation->run('seguro_post') == TRUE) {
				$datos['idcredito'] = $idcredito;
				$updatetrans = $this->seguro->transaction($datos);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}		
		}
	}


	public function findCheques($fecini, $fecfin) {
		$query = "SELECT c.fecha_entrega::date, c.idacreditado, c.acreditado,  ch.cheque_ref, c.monto, col.colmena_numero, col.colmena_nombre FROM ".$this->esquema."creditos_cheq ch join ".$this->esquema."get_creditos_acreditado c on c.idcredito = ch.idcredito join ban.get_colmena_grupo col on col.idgrupo = c.idgrupo where c.fecha_entrega >='".$fecini."' and c.fecha_entrega <='".$fecfin."' order by c.fecha_entrega,  ch.cheque_ref, c.idacreditado";
		$bov = $this->base->querySelect($query, FALSE, "movdet");
		 return $bov;
	}


	


	public function findPolCheque($idcredito) {		
		$garan  = "Select sum(a.garantia) as garan from ".$this->esquema."amortizaciones a where a.idcredito =".$idcredito;
		$bov = "SELECT ch.idcredito, c.fecha_entrega::date, c.idacreditado, c.acreditado,  ch.cheque_ref, c.monto, col.colmena_numero, col.colmena_nombre, s.nivel, de.cuentabanco, ba.nombre as nombanco FROM ".$this->esquema."creditos_cheq ch join ".$this->esquema."get_creditos_acreditado c on c.idcredito = ch.idcredito join ban.get_colmena_grupo col on col.idgrupo = c.idgrupo join ".$this->esquema."get_solicitud_credito s on s.idcredito = ch.idcredito and s.idsucursal = ch.idsucursal join public.banco_detalles de on de.idbancodet = ch.idbancodet join public.bancos ba on ba.idbanco = de.idbanco where c.idcredito =".$idcredito;
		$queries = array ("movdet" => $bov,
				  		  "garan" => $garan);
		$answer = $this->base->queriesSelect($queries);
		return $answer;
	}



	public function vine_get(){
		$idAnio = $this->uri->segment(4); 
		$query ="select a.idpersona, a.acreditadoid, a.nombre, a.idsucursal, a.col_nombre, c.nompromotor, COALESCE (b.vine, '0') as anio from ".$this->session->userdata('esquema')."get_acreditado_grupo as a inner join public.personas as b on b.idpersona  = a.idpersona join col.v_promotor_grupo as c on c.idgrupo = a.idgrupo where  COALESCE(b.vine, '0') = '".$idAnio."' order by a.idsucursal";
		$data = $this->base->querySelect($query, FALSE, "movine");
		$this->returnData($data);
	}


	public function getBeneficiarios($idpersona){
		$join = array('public.persona_ben' => 'public.persona_ben.idbeneficiario = public.personas.idpersona');
		$where = array("public.persona_ben.idpersona" => $idpersona);
		$ben = $this->base->selectRecord('public.personas', 'public.personas.*, public.persona_ben.idbeneficiario,public.persona_ben.idparentesco, public.persona_ben.porcentaje', $join, $where, "","", "", "", "","", TRUE);
		$bene = $this->direccionBen($ben);
		return $bene;
	}

	
	public function findPersonByRfc_get(){
		$rfcben = trim(strtoupper($this->uri->segment(4))); 
		$persona = $this->base->querySelect("select * from public.personas where RFC ='".$rfcben."'", TRUE);
		if ($persona) {
			$bene = $this->direccionBen($persona);
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"persona" => $bene,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro(s) inexistente(s)!",
				"persona" => []
			);
		}
		$this->returnData($data);
				
	}

	public function direccionBen($ben){
		$bene = [];
		if ($ben != []){
			foreach($ben as $key => $value){
				if (array_key_exists('idbeneficiario', $value)) {
					$id = $value['idbeneficiario'];
				}else {
					$id = $value['idpersona'];
				}
				$query =  "select * from public.persona_domicilio where idpersona =".$id;
				$benedirecc = $this->base->querySelect($query, FALSE);	
				$value['direccion'] = [];
				if ($benedirecc && $benedirecc['code']  == 200)  {
					$value['direccion'] = $benedirecc['result'][0];
				}
				array_push($bene, $value);
			}
		}
		return $bene;
	}
	
	public function creditosAut_get(){
		$valor  = $this->uri->segment(4);
		$filter = "(c.fecha_aprov is null and c.fecha_dispersa is null)";
		if ($valor == 1) {
			$filter = "(not c.fecha_aprov is null and c.fecha_dispersa is null)";
		}
		$query = "select false as activo, c.fecha_entrega_col, c.idacreditado as idsocia, a.idacreditado, (a.idacreditado || ' ' || a.acreditado) as nombre, c.idcredito, c.nivel, c.monto, col.colmena_nombre, col.grupo_nombre,c.fecha_aprov,
			COALESCE(fin.get_monto_garantia_liquidacion(c.idcredito),0.00) as garantia, (COALESCE(fin.get_monto_garantia_liquidacion(c.idcredito),0.00) + c.monto) as total, co.nompromotor as promotor, c.fecha_aprov as fecapro,
			case when c.fecha_aprov is null then false else true end as autoriza, case when not c.fecha_aprov is null and c.fecha_dispersa is null then true else false end as cancelapro, 
			case when c.fecha_aprov is null and c.fecha_dispersa is null then true else false end as eliminar
			FROM ".$this->esquema."creditos c
			JOIN public.get_acreditados a ON c.idacreditado = a.acreditadoid
			JOIN ".$this->esquema."get_colmena_grupo col ON col.idgrupo = a.idgrupo
			JOIN col.v_promotor_grupo as co on co.idgrupo = a.idgrupo			
			WHERE ".$filter."
		    and c.idsucursal ='".$this->sucursal."' ORDER BY c.idcredito;";

		$data = $this->base->querySelect($query, FALSE);
		$this->returnData($data);
	}


	function creditosOpt_post(){
		if ($this->ion_auth->in_group(array('gerencial','filtros'))){
		}else{
			redirect('/','refresh');			
		}

		$option = $this->uri->segment(4);
		if (!is_numeric($option)){
		   $this->returnCodeWithToken();
		}else {
			$valores = $this->post('data')?$this->post('data', TRUE):array();
			$this->load->model('credito_model','credito');
			$datos = fn_extraer($valores,'N');
			//Valida si la contraseña es valida 

			$user = $this->ion_auth->user()->row()->email;
			$password = $datos['password'];
			$response = $this->validateAutUser($user, $password);
			if ($response['code'] == 404) {
				$this->validaCode($response);
				return;
			}
			$this->form_validation->set_data( $datos );
			if ($this->form_validation->run('creditoaut_put') == TRUE) {
				$updatetrans = $this->credito->transAut($datos, $option);
				$this->validaCode($updatetrans);
			}else {
				$this->validaForm();
			}		
		}
	}
	
	
	
	function acreditado_gtia_get(){
		$idacreditado = $this->uri->segment(4);
		if (!is_numeric($idacreditado)){
		   $this->returnCodeWithToken();
		}else {
			$acre = "SELECT idacreditado, nombre, idsucursal, edocivil, edocivil_nombre, idactividad, actividad_nombre,
				 direccion, idgrupo, grupo_numero, grupo_nombre, col_numero, col_nombre FROM ".$this->esquema."get_acreditado_solicitud 
				 where acreditadoid = ".$idacreditado;
			$credits = "select a.idcredito as name, a.idcredito as value from 
			".$this->esquema."creditos a left join ".$this->esquema."creditos_gtia b 
			on b.idcredito = a.idcredito join public.acreditado c on c.acreditadoid = a.idacreditado
			where c.acreditadoid =".$idacreditado." and a.idsucursal ='".$this->sucursal."' and b.idcredito is null order by a.idcredito desc;";

			$queries = array ("acreditado" => $acre,
								"creditos" => $credits);
			$answer = $this->base->queriesSelect($queries);
			return $this->validaCode($answer);

		}
	}

	function addGtia_acreditado_post(){
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->load->model('credito_model','creditos');
		$datos = fn_extraer($valores,'N');
		
		$this->form_validation->set_data( $datos );
		if ($this->form_validation->run('creditogtia_post') == TRUE) {
			$data = $this->base->querySelect("Select idacreditado from  public.acreditado where acreditadoid =".$datos['idacreditado'], TRUE);
			print_r($data);
			die();


			$updatetrans = $this->creditos->addGtia($datos, $option);
			$this->validaCode($updatetrans);
		}else {
			$this->validaForm();
		}		
	}	

}
