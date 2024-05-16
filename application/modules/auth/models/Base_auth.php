<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Base_auth extends CI_Model{

	public $tables = array();

	function __construc(){		
		parent::__construct();
		$this->config->load('ion_auth');
		// initialize db tables data
		$this->tables  = $this->config->item('tables', 'ion_auth');
	}




/*
    function options_menu($idgrupo){
		$id = $this->ion_auth->user()->row()->id;
		
		$query = $this->db->select('options.group_id, options.key, options.name, options.level, options.option, options.link, options.icon')
				->from($this->tables['profile_options'])
				->join('security.options', 'security.profile_options.options_id = security.options.id')
				->join('security.profile', 'security.profile.id = security.profile_options.profile_id')
				->join('security.users_profiles', 'security.users_profiles.profile_id = security.profile.id')
				->where('options.group_id', $idgrupo)
				->where('users_profiles.user_id', $id)
				->where('security.options.status', 0)
				->group_by(array('options.group_id', 'options.key', 'options.name', 'options.level', 'options.option', 'options.link', 'options.icon'))
				->order_by('options.key')
				->get();
		if ($query->num_rows() > 0 ){
			return $query->result_array();
		}
		else{
           return null; 
		}        

    }
*/


	



	function sucursales(){
		$id = $this->ion_auth->user()->row()->id;
		$query = $this->db->select('security.users_sucursales.sistema, security.users_sucursales.sucursal_id, security.users_sucursales.user_id, public.sucursales.nombre')
				->from('security.users_sucursales')
				->join('public.sucursales', 'security.users_sucursales.sucursal_id = public.sucursales.idsucursal')
				->where('security.users_sucursales.sistema', $this->session->userdata('esquema'))
				->where('security.users_sucursales.user_id', $id)
				->order_by('security.users_sucursales.sucursal_id')
				->get();
		if ($query->num_rows() > 0 ){
			return $query->result_array();
		}
		else{
           return null; 
		}        	
	}


	function allsucursal(){
		$this->db->select('idsucursal as id,nombre');
		$this->response = $this->db->get('sucursales');
		return $this->response->result_array();
	}

	public function get_users_sucursales($esquema, $id=FALSE)
	{
		$id || $id = $this->session->userdata('user_id');
		$query = $this->db->query("select b.idsucursal as id, b.nombre from security.users_sucursales as a inner join public.sucursales as b on a.sucursal_id = b.idsucursal where a.sistema ='".$esquema."' and a.user_id =".$id);
		$row = $query->result();
		return $row;
	}


}