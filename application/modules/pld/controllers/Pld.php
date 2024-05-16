<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pld extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('pld')){
			redirect('/','refresh');
		}
		$this->session->set_userdata('opcion', 'pld');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(3);
		$this->session->set_userdata('menus', $menus);
	}

	public function index(){
		$view= $this->load->view('welcome_pld', null, TRUE);
		$javascript = array('inicio.min.js'); 
		render($view, $javascript);
	}
}		
