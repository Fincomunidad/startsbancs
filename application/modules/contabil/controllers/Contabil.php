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
		if ($this->session->userdata('change_password') ===true) {
			redirect('auth/change_password', 'refresh');
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

	public function provision(){
		$view= $this->load->view('provision', null, TRUE);
		$javascript = array('inicio.js', 'ger_provision.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function provacre(){
		$view= $this->load->view('provacre', null, TRUE);
		$javascript = array('inicio.js', 'ger_prov_acre.js','moment.min.js'); 
		render($view, $javascript);
	}	

	public function vencimientos(){
		$view= $this->load->view('vencimientos', null, TRUE);
		$javascript = array('ger_vencimientos.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function reccreditos(){
		$view= $this->load->view('reccreditos', null, TRUE);
		$javascript = array('inicio.js', 'con_reccred.js', 'moment.min.js'); 
		render($view, $javascript);		
	}
	
	public function cheques(){
		$view= $this->load->view('repcheques', null, TRUE);
		$javascript = array('inicio.js', 'con_cheques.js', 'moment.min.js'); 
		render($view, $javascript);		
	}
		
	
	public function repaportasoc(){
		$view= $this->load->view('repaportasoc', null, TRUE);
		$javascript = array('inicio.js', 'con_repaportasoc.js', 'moment.min.js'); 
		render($view, $javascript);		
	}

	public function horario(){
		$view= $this->load->view('horario', null, TRUE);
		$javascript = array('con_horario.js'); 
		render($view, $javascript);
	}	
	public function seguros(){
		$view= $this->load->view('repseguros', null, TRUE);
		$javascript = array('inicio.js', 'con_repseguros.js', 'moment.min.js'); 
		render($view, $javascript);		
	}
	
	
}		
