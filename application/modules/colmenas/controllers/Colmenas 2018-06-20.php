<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Colmenas extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('colmenas')){
			redirect('/','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}		
		$this->session->set_userdata('opcion', 'colmenas');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(4);
		$this->session->set_userdata('menus', $menus);		
	}

	public function index(){
		$view= $this->load->view('welcome_colmenas', null, TRUE);
		$javascript = array('inicio.min.js'); 
		render($view, $javascript);
	}

	public function catalogo(){
		$view= $this->load->view('catalogo', null, TRUE);
		$javascript = array('col_catalogo.js'); 
		render($view, $javascript);
	}

	public function alta(){
		$view= $this->load->view('alta', null, TRUE);
		$javascript = array('col_alta.js'); 
		render($view, $javascript);
	}

	public function asignacion(){
		$view= $this->load->view('asignacion', null, TRUE);
		$javascript = array('col_asigna.js'); 
		render($view, $javascript);
	}

	public function cambio(){
		$view= $this->load->view('cambio', null, TRUE);
		$javascript = array('col_cambio.js'); 
		render($view, $javascript);
	}
	
}		
