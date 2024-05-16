<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Gerencial extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('gerencial')){
			redirect('/','refresh');
		}
		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}	
		if ($this->session->userdata('change_password') ===true) {
			redirect('auth/change_password', 'refresh');
		}
		
		$this->session->set_userdata('opcion', 'gerencial');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(6);
		$this->session->set_userdata('menus', $menus);		
	}

	public function index(){
		$view= $this->load->view('welcome_gerencial', null, TRUE);
		$javascript = array('inicio.js'); 
		render($view, $javascript);
	}

	public function boveda(){
		$view= $this->load->view('boveda', null, TRUE);
		$javascript = array('ger_boveda.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function dispersion(){
		$view= $this->load->view('dispersion', null, TRUE);
		$javascript = array('ger_dispersion.js'); 
		render($view, $javascript);
	}

	public function inversiones(){
		$view= $this->load->view('inversiones', null, TRUE);
		$javascript = array('inicio.js', 'ger_inversion.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function ahorracon(){
		$view= $this->load->view('ahorracon', null, TRUE);
		$javascript = array('ger_ahorracon.js');
		render($view, $javascript);
	}

	public function reportes(){
		$view= $this->load->view('reportes', null, TRUE);
		$javascript = array('ger_reportes.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function ctasahorro(){
		$view= $this->load->view('ctasahorro', null, TRUE);
		$javascript = array('ger_ctasahorro.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function bovedarep(){
		$view= $this->load->view('bovedarep', null, TRUE);
		$javascript = array('ger_bovedarep.js','moment.min.js'); 
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
	
	public function autorizarev(){
		$view= $this->load->view('autorizarev', null, TRUE);
		$javascript = array('inicio.js', 'ger_autorizarev.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function vencimientos(){
		$view= $this->load->view('vencimientos', null, TRUE);
		$javascript = array('ger_vencimientos.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function acredat(){
		$view= $this->load->view('acredat', null, TRUE);
		$javascript = array('ger_acre_datos.js','moment.min.js'); 
		render($view, $javascript);
	}


	public function autorizacheq(){
		$view= $this->load->view('autorizacheq', null, TRUE);
		$javascript = array('inicio.js', 'ger_autorizacheq.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function altacol(){
		$view= $this->load->view('altacol', null, TRUE);
		$javascript = array('col_alta.js','moment.min.js'); 
		render($view, $javascript);
	}
	public function cerrarcol(){
		$view= $this->load->view('cerrarcol', null, TRUE);
		$javascript = array('col_elimina.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function provconfig(){
		$view= $this->load->view('provconfig', null, TRUE);
		$javascript = array('ger_provconfig.js','moment.min.js');
		render($view, $javascript);
	}
	
	public function niveles(){
		$view= $this->load->view('niveles', null, TRUE);
		$javascript = array('ger_nivel.js','moment.min.js');
		render($view, $javascript);
	}

	public function credact(){
		$view= $this->load->view('credact', null, TRUE);
		$javascript = array('ger_cred_activo.js','moment.min.js');
		render($view, $javascript);
	}
}		
