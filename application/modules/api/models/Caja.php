<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Caja extends MX_Controller
{
    public $grupo;
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth','refresh');
		}

		$this->grupo = "";
		
		// busca los grupos que tiene integrado 
		$id = $this->ion_auth->user()->row()->id;
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		$nogrupos = sizeof($currentGroups);
		if ($nogrupos == 1  || $nogrupos == 2) {
/* 			if ($currentGroups[0]->description == "Caja") {
				$this->grupo ='Cajera(o)';
			}
 */
			if ($this->ion_auth->in_group('caja') || $this->ion_auth->in_group('cartera')) {
				$this->grupo ='Cajera(o)';
			}

		}


		if ($this->grupo =="" || $this->session->userdata('sucursal_id') == "" || $this->session->userdata('idcaja') =="" ){
			redirect('/','refresh');
		}

		$this->session->set_userdata('opcion', 'caja');
		$this->load->helper(array('form','template'));
		$this->load->model('ion_auth_model','ionauthmodel');
		$menus = $this->ionauthmodel->options_menu(5);  // 5 es Grupo Caja
		$this->session->set_userdata('menus', $menus);	
	}

	public function index(){
        $grupo = $this->grupo;
		$this->session->set_userdata('grupo', $grupo);    
		$view= $this->load->view('welcome_caja', null, TRUE);
		$javascript = array('inicio.js', 'caj_opera.js', 'moment.min.js'); 
		render($view, $javascript);
	}

}		
