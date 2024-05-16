<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
class Base_car extends CI_model
{	
	function __construct()

	{
	}

	// Obtiene el numero de movimiento de la inversion según la base (esquema) 'fin.' o 'ban.'
	function nextMovimiento(){
        $esquema = $this->session->userdata('esquema');
	    $this->db->where("esquema", $esquema);
		$query =$this->db->get('public.inversion_mov');
		if ($query->num_rows() > 0 ){
			$reg =  $query->result_array();
			return ($reg[0]['nomov'] + 1);
		}else {
			$insert = array('esquema'=> $esquema, 'nomov'=> 0);
			$query = $this->db->insert('public.inversion_mov', $insert);
			return 1;
		}
	}


	function nextAcreditado(){
        $esquema = $this->session->userdata('esquema');
	    $this->db->where("idcaja", "00");
	    $this->db->where("esquema", $esquema);
		$query =$this->db->get('public.cajasmov');
		if ($query->num_rows() > 0 ){
			$reg =  $query->result_array();
			return ($reg[0]['nomov'] + 1);
		}else {
			$insert = array('idcaja' => '00', 'esquema'=> $esquema, 'nomov'=> 0);
			$query = $this->db->insert('public.cajasmov', $insert);
			return 1;
		}
		
		
	}
	

}