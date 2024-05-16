<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contabil extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('contabilidad')){
			redirect('/','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}
		$this->session->set_userdata('opcion', 'contabil');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(1);
		$this->session->set_userdata('menus', $menus);
	}

	public function index(){
		$view= $this->load->view('welcome_contabil', null, TRUE);
		$javascript = array('inicio.min.js'); 
		render($view, $javascript);
	}


	public function cuentas(){
		$view= $this->load->view('cuentas', null, TRUE);
		$javascript = array('con_cuentas.js'); 
		render($view, $javascript);
	}
	
	public function auxiliar(){
		$view= $this->load->view('auxiliar', null, TRUE);
		$javascript = array('con_auxiliar.js'); 
		render($view, $javascript);
	}
	
	public function balanza(){
		$view= $this->load->view('balanza', null, TRUE);
		$javascript = array('con_balanza.js'); 
		render($view, $javascript);
	}


	public function generarep(){
		$view= $this->load->view('generarep', null, TRUE);
		$javascript = array('con_generarep.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function generacre(){
		$view= $this->load->view('generacre', null, TRUE);
		$javascript = array('ger_reportes.js','moment.min.js'); 
		render($view, $javascript);
	}



	public function reportesconta(){
		$view= $this->load->view('reportesconta', null, TRUE);
		$javascript = array('con_reportesconta.js','moment.min.js'); 
		render($view, $javascript);
	}

}		
