<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bancos extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('bancos')){
			redirect('/','refresh');
		}

		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}
		
		$this->session->set_userdata('opcion', 'bancos');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(7);
		$this->session->set_userdata('menus', $menus);		
	}

	public function index(){
		$view= $this->load->view('welcome_bancos', null, TRUE);
		$javascript = array('inicio.js'); 
		render($view, $javascript);
	}


	public function edocta(){
		$view= $this->load->view('edocta', null, TRUE);
		$javascript = array('ban_import.js','moment.min.js'); 
		render($view, $javascript);
	}


}		
