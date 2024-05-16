<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller
{
		public $grupo;
		public $sucursales;

		/**
		 * Index Page for this controller.
		 *
		 * Maps to the following URL
		 * 		http://example.com/index.php/welcome
		 *	- or -
		 * 		http://example.com/index.php/welcome/index
		 *	- or -
		 * Since this controller is set as the default controller in
		 * config/routes.php, it's displayed at http://example.com/
		 *
		 * So any other public methods not prefixed with an underscore will
		 * map to /index.php/welcome/<method_name>
		 * @see http://codeigniter.com/user_guide/general/urls.html
		 */
		function __construct(){
			parent::__construct();
			if (!$this->ion_auth->logged_in()) {
				redirect('auth','refresh');
			}						
			$this->load->helper('template');

			$this->grupo = "";
			// busca los grupos que tiene integrado 
			$id = $this->ion_auth->user()->row()->id;
			$currentGroups = $this->ion_auth->get_users_groups($id)->result();
			$nogrupos = sizeof($currentGroups);
			if ($nogrupos == 1) {
				if ($currentGroups[0]->description == "Caja") {
					$this->grupo ='Cajera(o)';
				}
			}
			$this->load->model('caja/base_ca','basec');
			$this->load->model('auth/base_auth','basep');
			$this->sucursales = $this->basep->sucursales();
			$sucursales = $this->sucursales;
			if (sizeof($sucursales) === 1) {
				$this->session->set_userdata('sucursal_id', $sucursales[0]['sucursal_id']);
				$this->session->set_userdata('nomsucursal', $sucursales[0]['nombre']);
			} else {
				$this->session->set_userdata('sucursal_id', '');
				$this->session->set_userdata('nomsucursal', '');
			}
			$obtener = $this->basec->cajaEquipo();
			$this->session->set_userdata('idcaja', '');
			
			// if (sizeof($obtener) == 1){
				if (is_array($obtener) && count($obtener) == 1) {
				
				
//				if ($_SERVER['REMOTE_ADDR'] == $obtener[0]['ip']) {
//Habilitar esta linea por SEGURIDAD					
//				if ($_SERVER['REMOTE_ADDR'] == $obtener[0]['ip']  && $_SERVER['REMOTE_ADDR'] != '::1' ) {
//				if ($_SERVER['REMOTE_ADDR'] != '::1' ) {
					$this->session->set_userdata('idcaja', $obtener[0]['idcaja'] );
					$this->session->set_userdata('nocaja', $obtener[0]['descripcion'] );
					$this->session->set_userdata('name_user_caja', $obtener[0]['name'] );
					
//				}
			}
//			print_r($_SERVER['REMOTE_ADDR']);
//			die();
		}



		public function index()
		{
			$grupo = $this->grupo;
			$sucursales = $this->sucursales;
			$this->session->set_flashdata('sucursales', $sucursales);
			$this->session->set_userdata('grupo', $grupo);
			$this->load->view('welcome_user');
		}
}
