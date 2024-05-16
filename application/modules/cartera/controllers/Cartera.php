<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cartera extends MX_Controller
{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}
		if (!$this->ion_auth->in_group('cartera')){
			redirect('/','refresh');
		}

		if ($this->session->userdata('sucursal_id') == ""){
			redirect('/','refresh');
		}
		if ($this->session->userdata('change_password') ===true) {
			redirect('auth/change_password', 'refresh');
		}
		
		$this->session->set_userdata('opcion', 'cartera');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(array(2,8), array('2002','2005', '2007' ));
		$this->session->set_userdata('menus', $menus);		
	}

	public function index(){
		$view= $this->load->view('welcome_cartera', null, TRUE);
		$javascript = array('inicio.js'); 
		render($view, $javascript);
	}

	public function solingreso(){
		$view= $this->load->view('solingreso', null, TRUE);
		$javascript = array('inicio.js','car_solingreso.js','moment.min.js'); 
		render($view, $javascript);
	}


	public function altasocio(){
		$view= $this->load->view('altasocio', null, TRUE);
		$javascript = array('car_altasocio.js','moment.min.js'); 
		render($view, $javascript);
	}


	public function solcredito(){
		$view= $this->load->view('solcredito', null, TRUE);
		$javascript = array('inicio.js','car_solcredito.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function solcreditonew(){
		$view= $this->load->view('solcreditonew', null, TRUE);
		$javascript = array('inicio.js','car_solcreditonew.js','moment.min.js'); 
		render($view, $javascript);
	}



	public function solcrediton(){
		$view= $this->load->view('solcrediton', null, TRUE);
		$javascript = array('inicio.js','car_solcrediton.js','moment.min.js'); 
		render($view, $javascript);
	}


	public function checklist(){
		$view= $this->load->view('checklist', null, TRUE);
		$javascript = array('car_checklist.js'); 
		render($view, $javascript);
	}

	public function retgarantia(){
		$view= $this->load->view('retgarantia', null, TRUE);
		$javascript = array('car_retgarantia.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function solemicheq(){
		$view= $this->load->view('solemicheq', null, TRUE);
		$javascript = array('car_solemicheq.js','moment.min.js'); 
		render($view, $javascript);
	}
	public function creditocan(){
		$view= $this->load->view('creditocan', null, TRUE);
		$javascript = array('inicio.js','car_credito_can.js','moment.min.js'); 
		render($view, $javascript);
	}	

	public function credauthrev(){
		$view= $this->load->view('credauthrev', null, TRUE);
		$javascript = array('inicio.js','car_credauthrev.js','moment.min.js'); 
		render($view, $javascript);
	}
	
	public function directorio(){
		$view= $this->load->view('directorio', null, TRUE);
		$javascript = array('gen_dir_tel.js','inicio.js'); 
		render($view, $javascript);
	}	
	
	
	public function vine(){
		$view= $this->load->view('vencimientoine', null, TRUE);
		$javascript = array('car_vine.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function creditosaut(){
		$view= $this->load->view('creditosaut', null, TRUE);
		$javascript = array('inicio.js','car_creditosaut.js'); 
		render($view, $javascript);
	}

	
}		
