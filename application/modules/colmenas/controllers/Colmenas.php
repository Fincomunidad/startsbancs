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
		$menus = $this->ionauthmodel->options_menu(array(4,8), array('1007', '1008', '1009'));
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
		$javascript = array('col_alta.js','moment.min.js'); 
		render($view, $javascript);
	}

	public function eliminar(){
		$view= $this->load->view('eliminar', null, TRUE);
		$javascript = array('col_elimina.js','moment.min.js'); 
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

	public function promotor(){
		$view= $this->load->view('promotor', null, TRUE);
		$javascript = array('col_promotor.js'); 
		render($view, $javascript);
	}	
	
	
	public function registro(){
		$view= $this->load->view('registro', null, TRUE);
		$javascript = array('col_registro.js'); 
		render($view, $javascript);
	}

	public function vales(){
		$view= $this->load->view('vales', null, TRUE);
		$javascript = array('col_vales.js'); 
		render($view, $javascript);
	}
	

	public function asistencia(){
		$view= $this->load->view('asistencia', null, TRUE);
		$javascript = array('col_asistencia.js'); 
		render($view, $javascript);
	}

		
	public function califica(){
		$view= $this->load->view('califica', null, TRUE);
		$javascript = array('col_calasistencia.js'); 
		render($view, $javascript);
	}

	
	public function repsemanal(){
		$view= $this->load->view('repsemanal', null, TRUE);
		$javascript = array('col_repsemanal.js','moment.min.js'); 
		render($view, $javascript);
	}
	
	
	public function cargo(){
		$view= $this->load->view('cargo', null, TRUE);
		$javascript = array('col_cargo.js','moment.min.js'); 
		render($view, $javascript);
	}
	
	public function horario(){
		$view= $this->load->view('horario', null, TRUE);
		$javascript = array('col_horario.js','moment.min.js'); 
		render($view, $javascript);
	}
	
	public function ficha(){
		$view= $this->load->view('ficha', null, TRUE);
		$javascript = array('col_ficha.js','moment.min.js'); 
		render($view, $javascript);
	}	
	
	public function niveles(){
		$view= $this->load->view('niveles', null, TRUE);
		$javascript = array('col_nivel.js','moment.min.js'); 
		render($view, $javascript);
	}

	
	
	
}		
