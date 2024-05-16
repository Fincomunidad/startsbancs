<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class GeneralV1 extends BaseV1 {
	public function __construct()
	{
		parent::__construct();

	}

	/*
	* Asigna Sucursal y verifica si existe en la relación de sucursales
	*/
	public function asignaSuc_post(){
		$valor = $this->uri->segment(4);
		if ($valor !="") {
			$this->load->model('auth/base_auth','basep');
			$sucursales = $this->basep->sucursales();
			$encontro = false;
			$nomsucursal="";
			foreach ($sucursales as $key => $value){
				if ($value['sucursal_id'] === $valor) {
					$encontro = true;
					$nomsucursal = $value['nombre'];
					break;
				}
			}
			if ($encontro === true) {
				$this->session->set_userdata('sucursal_id', $valor);
				$this->session->set_userdata('nomsucursal', $nomsucursal);
			}else {
				$this->session->set_userdata('sucursal_id', '');
				$this->session->set_userdata('nomsucursal', '');
			}

			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Tabla consultada",
				"sucursal" => array("id" =>  $valor, 
									"nomsuc" => $nomsucursal));
			$this->returnData($data);


		}
	}




	/*
	* Se utiliza en la solicitu de Ingreso
	* Consulta las tablas que se utilizan en la captura
	*/
	public function catsolcre_get() {
		$group_by = array("idestado","estado");
		$order_by = array(array('campo'=> 'estado','direccion'=>	'asc'));		
		$group_bym = array("c_mnpio","d_mnpio");
		$order_byp = array(array('campo'=> 'nombre','direccion'=> 'asc'));
		$pais = $this->base->selectRecord("pais", "idpais as value, nombre as name", "", "", "", "",  "", $order_byp, "","", TRUE);		
		$estado = $this->base->selectRecord("estados", "idestado as value, estado as name", "", "", "","", $group_by, $order_by, "","", TRUE);
		$mpio = $this->base->selectRecord("localidades","c_mnpio as value, d_mnpio as name", "", "", "", "",  $group_bym,"", "", "", TRUE);
		$edocivil = $this->base->selectRecord("edocivil","idedocivil as value, nombre as name", "", "", "", "", "","", "", "", TRUE);
		$escolaridad = $this->base->selectRecord("escolaridad","idescolaridad as value, nombre as name", "", "", "", "", "","", "", "",TRUE);
		$parentesco = $this->base->selectRecord("parentesco","idparentesco as value, nombre as name", "", "", "", "", "", "", "", "",TRUE);
		$order_by = array(array('campo'=> 'nombre',
		'direccion'=>	'asc'));		
		$where = array('activo' => true);
		$actividad = $this->base->selectRecord("actividades","idactividad as value, nombre as name", "", $where, "", "", "",$order_by, "", "",TRUE);
		$tiposociedad = $this->base->selectRecord("tiposociedad","idtiposociedad as value, nomcorto as name", "", "", "", "", "","", "", "",TRUE);


		if ($estado) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Tabla consultada",
				"pais"=> $pais,
				"estado"=> $estado,
				"mpio"=> $mpio,
				"edocivil" => $edocivil,
				"escolaridad" => $escolaridad,
				"parentesco" => $parentesco,
				"actividad" => $actividad,
				"tiposociedad"=> $tiposociedad);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					"pais"=> $pais,
					"estado" => $estado,
					"edocivil" => $edocivil,
					"escolaridad" => $escolaridad,
					"parentesco" => $parentesco );
			}
			$this->returnData($data);
	}



	/* 
	* API Para el control de Bancos
	* POST
	*/	
	public function bancos_post() {
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->insertData('bancos', $valores, 'bancos_post');
	}


	/*
	*  Bancos PUT
	*/
    public function bancos_put(){
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$where = array('idbanco' => $valores['idbanco']);
		$isarray = false;
		$this->updateData('bancos', $valores, 'bancos_post', $where, $isarray);
    }

	/*
	* Bancos GET
	*/
	public function bancos_get(){
		$idbanco = $this->uri->segment(4);
		$where = array('idbanco' => $idbanco);
		$this->selectData('public.bancos', "","", $where, "", "", "", "", "", "", FALSE);
	}


	/*
	* Bancos GET
	*/
	public function bancosall_get(){
		$where = array('b.vigente' => true, 
		               'b.idsucursal' => $this->session->userdata('sucursal_id'),
						'b.empresa' => $this->session->userdata('esquema'));
		$order_by = array(array('campo'=> 'a.idbanco',
				  'direccion'=>	'asc'));
		$join = array('public.banco_detalles as b' => 'b.idbanco = a.idbanco');
		$this->selectData('public.bancos as a', "b.idbancodet as value, a.nombre || ' ' || b.cuentabanco as name, b.idcuenta as idcuenta ",$join, $where, "", "", "", $order_by, "", "", FALSE);
	}
	
	/*
	* Bancos DELETE
	*/

	public function bancos_delete(){
		$idbanco = $this->uri->segment(4);
		$where = array('idbanco' => $idbanco);
		$this->deleteData('public.bancos', $where);
	}
	/*
	* Paginacion Bancos
	*/
	public function bancospag_get(){
		$pagina = $this->uri->segment(4);
		$por_pagina = $this->uri->segment(5);
		$tabla = "bancos";
		$order_by = array();
		if ($content = $this->get('orderby', TRUE)) {
			$order_by = $content;
		}
		$this->pagina_table( $tabla , $pagina, $por_pagina, $order_by);
	}


	/*
	* Bancos GET
	*/
	public function cajasall_get(){
		$esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');		
		$cajasf ="";
		if ($user = $this->ion_auth->user()->row()->email =='admin@admin.com') {
			$cajasf = " a.idcaja <> '00' ";
		}else {
			$cajasf = " (a.idcaja <> '00' AND a.idcaja <> '05') ";
		}
		$query ="select a.idcaja as value, (a.descripcion || ' ' || b.first_name || ' ' || b.last_name) as name from  public.cajas as a join security.users as b on b.id = a.iduser where a.esquema ='".$esquema."' and a.idsucursal ='". $this->session->userdata('sucursal_id')."' and ". $cajasf." order by a.idcaja asc";
		$data = $this->base->querySelect($query, FALSE);		
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
	* Consulta de los municipios segun el estado
	*/
	public function catmpio_get() {
		$valor = $this->uri->segment(4);
		$where = array('c_estado' => $valor);
		$group_by = array("c_mnpio","d_mnpio");
		$this->selectData('public.localidades', "c_mnpio as value, d_mnpio as name","", $where,"", "" , $group_by, "", "", "", FALSE);
	}

	/*
	* Consulta de  las colonias segun el municipios
	*/
	public function catcolonia_get(){
		$valor = $this->uri->segment(4);
		$where = array('c_mnpio' => $valor);
		$group_by = array("id_asenta_cpcons","d_asenta");
		$this->selectData('public.localidades', "id_asenta_cpcons as value, d_asenta as name", "", $where,"", "" , $group_by, "", "", "", FALSE);
	}

	/*
	* Consulta de los codigos postales segun la colonia
	*/
	public function catcp_get(){
		$valor = $this->uri->segment(4);
		$cp = $this->uri->segment(5);
		$where = array('c_mnpio' => $valor, 'id_asenta_cpcons' => $cp);
		$this->selectData('public.localidades', "d_cp as value, d_cp as name", "", $where,"", "" ,"", "", "", "", FALSE);
	}



	public function catempleados_get(){
		$esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');	
		$where = array('idsucursal' => $this->session->userdata('sucursal_id'));
		$this->selectData($esquema."get_empleados","idempleado as value, empleado as name", "", $where, "", "", "","", "", "",FALSE);
	}

	public function getPlazos_get(){
		$order_by = array(array('campo'=> 'monto',
				  'direccion'=>	'asc'));
		$plazos = $this->base->selectRecord("cat_plazos", "idplazo as value, descrip as name", "", "", "","", "", "", "","", TRUE);
		$interes = $this->base->selectRecord("cat_interes", "", "", "", "","", "", $order_by, "","", TRUE);
		
		$anio = date("Y");
		$query = "select TO_CHAR(fecha, 'dd/mm/yyyy') as fecha from public.dias_festivos where extract(year from fecha) >= ".$anio."  order by fecha";
		$festivos = $this->base->querySelect($query, TRUE);
		
		if ($plazos) {
			$data = array("status"=>"OK",
			"code" => 200,
			"message"=>"Tabla consultada",
			"cat_plazos"=> $plazos,
			"cat_interes" => $interes, 
			"cat_festivos" => $festivos );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"cat_plazos"=> $plazos,
				"cat_interes" => $interes, 
			    "cat_festivos" => $festivos );
			}
		$this->returnData($data);
	

	}

}
