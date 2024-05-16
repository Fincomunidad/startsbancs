<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class Base_ca extends CI_model
{	
	function __construct()

	{
	}
	// Obtiene el registro de la caja - equipo  asignados segun el usuario 
	function cajaEquipo(){
		$esquema = $this->session->userdata('esquema');		
		$data = "Select a.*, (b.first_name || ' ' || b.last_name) as name from public.cajas as a inner join security.users as b on a.iduser = b.id where 
			a.iduser = ".$this->ion_auth->user()->row()->id." and a.idsucursal ='".$this->session->userdata('sucursal_id')."' and a.status = true and a.esquema ='".$esquema."'";
		$query = $this->db->query($data);

		if ($query->num_rows() > 0 ){
			return $query->result_array();
		}
		
/*
        $esquema = $this->session->userdata('esquema');		
//				if ($_SERVER['REMOTE_ADDR'] == $obtener[0]['ip']  && $_SERVER['REMOTE_ADDR'] != '::1' ) {
		//$this->db->where("iduser", $this->ion_auth->user()->row()->id);

//		$where = array("ip" =>  $_SERVER['REMOTE_ADDR'], "idsucursal" =>  $this->session->userdata('sucursal_id'),
//		"status" =>  true, "esquema" => $esquema);

		$where = array("iduser" => $this->ion_auth->user()->row()->id, "idsucursal" =>  $this->session->userdata('sucursal_id'),
				"status" =>  true, "esquema" => $esquema);
		$this->db->where($where);
		$query =$this->db->get('public.cajas');
		if ($query->num_rows() > 0 ){
			$data = $query->result_array();
//			if ($query->num_rows() == 1){
//				$query = $this->db->query("update cajas set iduser =".$this->ion_auth->user()->row()->id." where ip ='".$_SERVER['REMOTE_ADDR']."' and 
//				  idsucursal ='".$this->session->userdata('sucursal_id')."' and status=true and esquema ='".$esquema."'");
//			}
			return $data;
		}
*/		
	}


	// Obtiene el numero de movimiento de la caja según la base (esquema) 'fin.' o 'ban.'
	function nextMovimiento(){
        $esquema = $this->session->userdata('esquema');
        $idcaja = $this->session->userdata('idcaja');
		$this->db->where("idcaja", $idcaja);
	    $this->db->where("esquema", $esquema);
		$query =$this->db->get('public.cajasmov');
		if ($query->num_rows() > 0 ){
			$reg =  $query->result_array();
			return ($reg[0]['nomov'] + 1);
		}else {
			$insert = array('idcaja'=> $idcaja, 'esquema'=> $esquema, 'nomov'=> 0);
			$query = $this->db->insert('public.cajasmov', $insert);
			return 1;
		}
	}

	function getPrinterEquipo(){
        $esquema = $this->session->userdata('esquema');		
		$where = array("iduser" => $this->ion_auth->user()->row()->id, "idsucursal" =>  $this->session->userdata('sucursal_id'),
				"status" =>  true, "esquema" => $esquema);
		$this->db->select('name_printer');
		$this->db->where($where);
		$query =$this->db->get('public.cajas');
		$row = $query->row_array();
		if (isset($row))
		{
			return $row['name_printer'];
		}else {
			return "";
		}
	}

}