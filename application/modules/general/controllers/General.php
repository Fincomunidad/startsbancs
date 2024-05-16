<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class General extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}		
		if ($this->session->userdata('change_password') ===true) {
			redirect('auth/change_password', 'refresh');
		}
		
		$this->load->helper(array('form','template'));
	}
	public function bancos(){
		$view= $this->load->view('bancos', null, TRUE);
		$javascript = array('gen_bancos.js','inicio.js'); 
		render($view, $javascript);
	}



}		
