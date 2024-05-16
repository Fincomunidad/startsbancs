<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class GeneralD1 extends BaseV1 {
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
			foreach ($sucursales as $key => $value){
				if ($value['sucursal_id'] === $valor) {
					$encontro = true;
					break;
				}
			}
			if ($encontro === true) {
				$this->session->set_userdata('sucursal_id', $valor);
			}else {
				$this->session->set_userdata('sucursal_id', '');
			}
		}
	}

	/*
	* Se utiliza en la solicitu de Credito
	* Consulta las tablas que se utilizan en la captura
	* 2018-04-01
	*/
	public function get_solicitud_credito_get() {

		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$order_by = array(array('campo'=> 'nombre', 'direccion'=>	'asc'));
		$catsocio = $this->base->selectRecord("fin.get_acreditado_solicitud","acreditadoid as value, (idacreditado||' - '||nombre) as name", "", $where, "", "", "",$order_by, "", "", TRUE);

		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$catcolmena = $this->base->selectRecord("col.colmenas","idcolmena as value, nombre as name", "", $where, "", "", "","", "", "",TRUE);
		
		$catchklst = $this->base->selectRecord("checklist","idchecklist as value, descripcion as name", "", "", "", "", "","", "", "",TRUE);
		$catnivel = $this->base->selectRecord("niveles","nivel as value, nivel as name", "", "", "", "", "","", "", "",TRUE);

		$cat_periodo =  array(
			array('value'=> 'S', 'name'=>'Semanal'),
			array('value'=> 'Q', 'name'=>'Quincenal'),
			array('value'=> 'M', 'name'=>'Mensual')
		);		
		$cat_num_pagos =  array( 
			array('value'=> 6, 'name'=>6)
		);
		
		for ($i = 7; $i <= 30; $i++) {
			array_push($cat_num_pagos, array('value'=> $i, 'name'=>$i));
		}
		

		if ($catsocio) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catsocio" => $catsocio,
					"catcolmena" => $catcolmena,
					"catnivel" => $catnivel,
					"catchklst" => $catchklst,
					"cat_periodo"=> $cat_periodo,
					"cat_num_pagos" =>$cat_num_pagos
				);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					"catsocio" => $catsocio,
					"catcolmena" => $catcolmena,
					"catnivel" => $catnivel,
					"catchklst" => $catchklst
					);
			}
			$this->returnData($data);
	}

	public function get_colmenas_get() {
		$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", "", "", "", "","", "", "",TRUE);
		if ($catcolmenas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catcolmenas" => $catcolmenas
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas
				);
		}
		$this->returnData($data);
	}

	/*
	// 2018-04-01
	*/
	public function get_colmenas_asigna_get() {
		$idSucursal = $this->session->userdata('sucursal_id');

		$fields = array("acreditadoid as value", "(idacreditado || ' - ' || nombre || ' (' || idanterior || ')' ) as name ",);
		$where = array("idsucursal" => $idSucursal, 
						"idgrupo" => NULL);
		$order_by = array(array('campo'=> 'nombre', 'direccion'=>	'asc'));
		$cat_noasigna = $this->base->selectRecord($this->session->userdata('esquema')."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
	
		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,2,4));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_col_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,3));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_grupo_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);
		
		$cat_grupo_orden =  array(
			array('value'=> '1', 'name'=>'1'),
			array('value'=> '2', 'name'=>'2'),
			array('value'=> '3', 'name'=>'3'),
			array('value'=> '4', 'name'=>'4'),
			array('value'=> '5', 'name'=>'5')
		);

		$where = array("idsucursal" => $idSucursal);
		$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", $where, "", "", "","", "", "",TRUE);
		if ($catcolmenas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catcolmenas" => $catcolmenas,
					"cat_noasigna"=> $cat_noasigna,
					"cat_col_cargos"=> $cat_col_cargos,
					"cat_grupo_cargos"=> $cat_grupo_cargos,
					"cat_grupo_orden"=> $cat_grupo_orden
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas
				);
		}
		$this->returnData($data);
	}


	//Catalogo de grupos por colmena
	public function get_colmena_grupo_get(){
		$valor = $this->uri->segment(4);
		$where = array('idcolmena' => $valor);
		$this->selectData('col.grupos', "idgrupo as value, numero as name", "", $where,"", "" ,"", "", "", "", FALSE);
	}

	public function get_colmena_acreditados_get(){
		$valor = $this->uri->segment(4);
		$where = array('idcolmena' => $valor);
		$this->selectData('fin.get_colmena_grupo_acreditado', "acreditadoid as value, (idacreditado||' - '||nombre) as name", "", $where,"", "" ,"", "", "", "", FALSE);
	}
	

	public function get_solicitud_cheques_get() {
		$order_by = array(array('campo'=> 'fecha', 'direccion'=>'desc'));
		$group_by = array("fecha");
		$catfechas = $this->base->selectRecord("fin.v_sol_emision_cheques","fecha as value, fecha as name", "", "", "", "", $group_by, $order_by, "", "", TRUE);
		if ($catfechas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catfechas" => $catfechas
				);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					//"edocivil" => $edocivil,
					//"actividad" => $actividad,
					"catfechas" => $catfechas
					);
			}
			$this->returnData($data);
	}


	public function get_provisiones_fecha_get() {
		$order_by = array(array('campo'=> 'fecha', 'direccion'=>'desc'));
		$group_by = array("fecha");
		$catfechas = $this->base->selectRecord("fin.ahorros_int","fecha as value, fecha as name", "", "", "", "", $group_by, $order_by, "", "", TRUE);
		if ($catfechas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catfechas" => $catfechas
				);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					//"edocivil" => $edocivil,
					//"actividad" => $actividad,
					"catfechas" => $catfechas
					);
			}
			$this->returnData($data);
	}	


}