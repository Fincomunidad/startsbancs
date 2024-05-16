<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Catalog extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('admin')){
			redirect('/','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}	
		if ($this->session->userdata('change_password') ===true) {
			redirect('auth/change_password', 'refresh');
		}
		
		$this->session->set_userdata('opcion', 'catalog');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(0);
		$this->session->set_userdata('menus', $menus);		
	}


	public function index(){
		$view= $this->load->view('welcome_catalog', null, TRUE);
		$javascript = array('inicio.min.js'); 
		render($view, $javascript);
	}


	public function productos(){
		$view= $this->load->view('productos', null, TRUE);
		$javascript = array('cat_productos.js'); 
		render($view, $javascript);
	}

	
	public function udis(){
		$view= $this->load->view('udis', null, TRUE);
		$javascript = array('cat_udis.js'); 
		render($view, $javascript);
	}


}		
