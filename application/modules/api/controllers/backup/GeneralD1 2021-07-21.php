<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class GeneralD1 extends BaseV1 {
	public function __construct()
	{
		parent::__construct();
		$this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');
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
		/*
		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'),
				"idpromotor"=>$this->ion_auth->user()->row()->id);
		*/
		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$order_by = array(array('campo'=> 'nombre', 'direccion'=>	'asc'));
		$catsocio = $this->base->selectRecord($this->esquema."get_acreditado_solicitud","acreditadoid as value, (idacreditado||' - '||nombre) as name", "", $where, "", "", "",$order_by, "", "", TRUE);

		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$catcolmena = $this->base->selectRecord("col.colmenas","idcolmena as value, nombre as name", "", $where, "", "", "","", "", "",TRUE);
		
		$catchklst = $this->base->selectRecord("checklist","idchecklist as value, descripcion as name", "", "", "", "", "","", "", "",TRUE);
		
		/*
		$where = array("fecha_inicio"=>'2018-08-06');
		$order_by = array(array('campo'=> 'nivel', 'direccion'=>'asc'));
		$catnivel = $this->base->selectRecord("niveles","nivel as value, nivel as name", "", $where, "", "", "", $order_by, "", "",TRUE);
		*/
		$catnivel =  array( 
			array('value'=> 1, 'name'=>1)
		);
		
		
		if ($this->esquema=="ban."){
			for ($i = 2; $i <= 50; $i++) {
				array_push($catnivel, array('value'=> $i, 'name'=>$i));
			}		
		}else{
			for ($i = 2; $i <= 40; $i++) {
				array_push($catnivel, array('value'=> $i, 'name'=>$i));
			}
			array_push($catnivel, array('value'=> 50, 'name'=>50));
		}
		array_push($catnivel, array('value'=> 60, 'name'=>60));
		
		$cat_periodo =  array(
			array('value'=> 'S', 'name'=>'Semanal'),
			array('value'=> 'Q', 'name'=>'Quincenal'),
			array('value'=> 'M', 'name'=>'Mensual')
		);		
		$cat_num_pagos =  array( 
			array('value'=> 1, 'name'=>1)
		);
		
		for ($i = 2; $i <= 60; $i++) {
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

	/*
	* Se utiliza en la solicitu de Credito
	* Consulta las tablas que se utilizan en la captura
	* 2018-04-01
	*/
	/*
	* Se utiliza en la solicitu de Credito
	* Consulta las tablas que se utilizan en la captura
	* 2018-04-01
	*/
	public function get_solicitud_cred_ind_get() {
		/*
		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'),
				"idpromotor"=>$this->ion_auth->user()->row()->id);
		*/

		
		
		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$order_by = array(array('campo'=> 'nombre', 'direccion'=>'asc'));
		$catsocio = $this->base->selectRecord($this->esquema."get_acreditado_solind","acreditadoid as value, (idacreditado||' - '||nombre) as name", "", $where, "", "", "",$order_by, "", "", TRUE);


		$where = array("idsucursal"=>$this->session->userdata('sucursal_id'));
		$catcolmena = $this->base->selectRecord("col.colmenas","idcolmena as value, nombre as name", "", $where, "", "", "","", "", "",TRUE);
		
		$catchklst = $this->base->selectRecord("checklist","idchecklist as value, descripcion as name", "", "", "", "", "","", "", "",TRUE);

		//$catnivel = $this->base->selectRecord("niveles","nivel as value, nivel as name", "", "", "", "", "","", "", "",TRUE);
		$catnivel =  array( 
			array('value'=> 1, 'name'=>1)
		);
		
		for ($i = 2; $i <= 90; $i++) {
			array_push($catnivel, array('value'=> $i, 'name'=>$i));
		}
		array_push($catnivel, array('value'=> 366, 'name'=>366));

		$cat_periodo =  array(
			array('value'=> 'S', 'name'=>'Semanal'),
			array('value'=> 'C', 'name'=>'Catorcenal'),
			array('value'=> 'Q', 'name'=>'Quincenal'),
			array('value'=> 'M', 'name'=>'Mensual'),
			array('value'=> 'N', 'name'=>'28 dias')
		);

		$cat_tasa =  array(
			array('value'=> '1.50', 'name'=>'1.50'),
			array('value'=> '2.00', 'name'=>'2.00'),
			array('value'=> '2.50', 'name'=>'2.50')
		);

		$cat_iva =  array(
			array('value'=> '0', 'name'=>'No'),
			array('value'=> '1', 'name'=>'Si'),
		);

		$cat_num_pagos =  array( 
			array('value'=> 1, 'name'=>1)
		);		
		for ($i = 2; $i <= 60; $i++) {
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
					"cat_num_pagos" =>$cat_num_pagos,
					"cat_tasa"=>$cat_tasa,
					"cat_iva" =>$cat_iva
				);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					"catsocio" => $catsocio,
					"catcolmena" => $catcolmena,
					"catnivel" => $catnivel,
					"catchklst" => $catchklst,
					"cat_iva" =>$cat_iva
					);
			}
			$this->returnData($data);
	}
	
	
	public function get_colmenas_get() {
		$idsucursal = $this->session->userdata('sucursal_id');
		$order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$where = array("idsucursal"=>$idsucursal);
		//$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", $where, "", "", "","", "", "",TRUE);
		$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", $where, "", "", "", $order_by, "", "",TRUE);

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
		$this->selectData($this->esquema.'get_colmena_grupo_acreditado', "acreditadoid as value, (idacreditado||' - '||nombre) as name", "", $where,"", "" ,"", "", "", "", FALSE);
	}
	

	public function get_solicitud_cheques_get() {
		$order_by = array(array('campo'=> 'fecha', 'direccion'=>'desc'));
		$group_by = array("fecha");
		$catfechas = $this->base->selectRecord($this->esquema."v_sol_emision_cheques","fecha as value, fecha as name", "", "", "", "", $group_by, $order_by, "", "", TRUE);
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
		$catfechas = $this->base->selectRecord($this->esquema."ahorros_int","fecha as value, fecha as name", "", "", "", "", $group_by, $order_by, "", "", TRUE);
		
		$order_by = array(array('campo'=> 'idacreditado', 'direccion'=>'desc'));
		$catsocio = $this->base->selectRecord($this->esquema."get_creditos_acreditado","idacreditado as value, acreditado as name", "", "", "", "", "", $order_by, "", "", TRUE);
		/*
		SELECT DISTINCT idacreditado, acreditado
		FROM fin.get_creditos_acreditado
		ORDER BY idacreditado
		*/
		if ($catfechas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
					"catfechas" => $catfechas,
					"catsocio" => $catsocio
				);
			} else {
				$data = array("status"=>"ERROR",
				 	"code" => 404,
					"message"=>"Error al tratar de consultar",
					//"edocivil" => $edocivil,
					//"actividad" => $actividad,
					"catfechas" => $catfechas,
					"catsocio" => $catsocio
					);
			}
			$this->returnData($data);
	}	


}