<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');
class BancosV1 extends BaseV1 {
	public function __construct()
    {
		parent::__construct();
//		if(!$this->ion_auth->logged_in())
//		{
//			redirect('auth','refresh');
//		}
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
            $this->load->library('form_validation');		
//		$this->load->helper(array('form','template'));
            $this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');
    }
    

    public function getEdoCta_get(){
		$idbanco = $this->uri->segment(4);
		$fecini = $this->uri->segment(5);
		$fecfin = $this->uri->segment(6);
		$fecha = new DateTime();
		$fecini1 =  $fecha->format('d/m/Y');
		$fecfin2 =  $fecha->format('d/m/Y');

		if ($fecini !=''){
			if ($fecini != '0'){
				$fecha = new DateTime();
				$fecha->setDate(substr($fecini,4,4), substr($fecini,2,2), substr($fecini,0,2));
				$fecini =  $fecha->format('Y-m-d');

			}else{
				$fecini = date("Y-m-d"); 	
			}
		}else{
			$fecini = date("Y-m-d"); 
		}

		if ($fecfin !=''){
			if ($fecfin != '0'){
				$fecha = new DateTime();
				$fecha->setDate(substr($fecfin,4,4), substr($fecfin,2,2), substr($fecfin,0,2));
				$fecfin =  $fecha->format('Y-m-d');
			}else{
				$fecfin = date("Y-m-d"); 	
			}
		}else{
			$fecfin = date("Y-m-d"); 
		}

		$query = "select to_char(fecha,'hh24:mi:ss') as hora, to_char(fecha,'DD/MM/YYYY') as fecha, autorizacion, concepto, deposito, retiro, saldo, vale, semana, colmena_numero as colmena, grupo_nombre as grupo, colmena_nombre as nomcolmena, caja,'1' as estatus from ".$this->esquema."v_edocta_colmena  where idbanco =".$idbanco." and (fecha::date >='".$fecini. "' and fecha::date <='".$fecfin."') order by fecha::date, consecutivo";
		$edocta = $this->base->querySelect($query, TRUE);
		if ($edocta) {						
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>'Registro obtenido correctamente!',
				"catmov" => $edocta,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registro(s) inexistente(s)!",
				"catmov" => [],
			);
		}
		$this->returnData($data);
	}


	public function add_edocta_put(){	
		$idbanco = $this->uri->segment(4);
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//Carga de los modelos 
		$this->load->model('Bancos_model','bancos');
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
//		$datos = fn_extraer($valores,'S');
		$datos = $valores[0];
		$fecha = explode("/", $datos['fecha']); 
		$datos['fecha'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;

		$datos['deposito'] = (double)str_replace(",","",$datos['deposito']); 
		$datos['retiro']  = (double)str_replace(",","",$datos['retiro']); 
		$datos['saldo']   = (double)str_replace(",","",$datos['saldo']); 
		$datos['idbanco'] = $idbanco;

		$addtrans = $this->bancos->transacEdoCta($datos);
		$this->validaCode($addtrans);

	}


	public function update_edocta_put(){	
		if ($this->ion_auth->in_group('gerencial')){
			$id = $this->uri->segment(4);
			$esquema = $this->esquema;
  		$valores = $this->put('data')?$this->put('data', TRUE):array();
			$record = $valores[0];
  		$fecha = explode("/", $record['fecha']); 
			$record['fecha'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0]. ' '.$record['hora'];
		 unset($record['hora']);

			$where = array('id'=>$id);
			$update = $this->base->updateRecord($esquema."edocta", $record, $where, 0);
			$this->validaCode($update);
		}else {
			$this->returnCode();			
		}
	}

	
	public function autorizacion_get(){
		$idasistencia = $this->uri->segment(4);
		$data = $this->base->querySelect("select a.id as value, a.autorizacion as name, to_char(a.fecha,'HH24:MI:SS') as hora, to_char(a.fecha,'DD/MM/YYYY') as fechao, to_char(a.fecha_pago_col,'DD/MM/YYYY HH:MI:SS') as fecha_pago_colmena, * from ".$this->esquema."v_edocta_colmena as a  where (extract(week from a.fecha::date) = extract(week from current_date::date) or extract(week from fecha::date) >= extract(week from current_date::date) -3)  and a.deposito <>0 order by a.fecha, a.consecutivo", FALSE);
//		$data = $this->base->querySelect("select a.id as value, a.autorizacion as name, to_char(a.fecha,'hh:mm:ss') as hora, to_char(a.fecha,'DD/MM/YYYY') as fechao, * from ".$this->esquema."edocta as a  left join ".$this->esquema."edocta_colmena as b on b.edoctaid = a.id  where  a.deposito <>0 and b.edoctaid is null order by a.fecha, a.consecutivo", FALSE);
//		$data = $this->base->querySelect("select * from ".$this->esquema."edocta where (extract(week from a.fecha::date) = extract(week from current_date::date) or extract(week from fecha::date) = extract(week from current_date::date) -1) and deposito <>0 order by fecha, consecutivo ", FALSE);
		if ($data) {
		}else{
			$data = array("status"=>"ERROR",
			"code" => 404,
			"message"=>"Registro inexistente!"
			);
		}
		$this->returnData($data);
	}
}

