<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Autho extends REST_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language','general'));
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}

 

    function validateUser($identify, $password, $option, $vs){
		if (!$this->ion_auth->logged_in()) {
			$respuesta = array('code' => 404 , 'message'=> 'No se encuentra logueado!! ');
        } else {
            $base = $this->session->userdata('esquema');
            if ($base === 'fin.' || $base === 'ban.') 
            {
                if ($this->ion_auth->loginValidate($identify, $password, $option, $vs))
                {
                    //if the login is successful
                  $respuesta = array("status"=>"OK",
					    "code" => "200",
					    "message"=> $this->ion_auth->messages(),
					    "newtoken"=> $this->security->get_csrf_hash()
				    );
                } else {
                    // if the login was un-successful
                    $respuesta = array("status"=>"ERROR",
                        "code" => "404",			 
                        "message"=> $this->ion_auth->errors(),
                        "newtoken"=>$this->security->get_csrf_hash()
                    );
                }			   			
            } else {
                    $respuesta = array("status"=>"ERROR",
                        "code" => "404",			 
                        "message"=> 'No se ha podido validar base de sesión',
                        "newtoken"=>$this->security->get_csrf_hash()
                    );
            }
        }
        return $respuesta;
    }


    public function validateAutUser_post(){
   		$option = $this->uri->segment(4);
        $valores = $this->post('data')?$this->post('data', TRUE):array();
		$datos = fn_extraer($valores,'N');
		$this->form_validation->set_data( $datos );		
        if ($this->form_validation->run('logUser') == true && $option !=='')
        {
            $respuesta = $this->validateUser($this->input->post('identity'), $this->input->post('password'), $option, TRUE);
            $this->response($respuesta, REST_Controller::HTTP_OK);            
        }else {
           $respuesta = array("status"=>"ERROR",
  			    "code" => "404",			 
				"message"=>'Datos incompletos!',
				"newtoken"=>$this->security->get_csrf_hash()
            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);            
        }

    }


	// log the user in
	public function validateLogUser_post()
	{
   		$option = $this->uri->segment(4);
        $valores = $this->post('data')?$this->post('data', TRUE):array();
		$datos = fn_extraer($valores, 'N');
		$this->form_validation->set_data( $datos );	
        if ($this->form_validation->run('logUser') == true && $option != '')
        {
            $respuesta = $this->validateUser($datos['identify'], $datos['password'], $option, FALSE);
            $this->response($respuesta, REST_Controller::HTTP_OK);            
        }else{
          $respuesta = array("status"=>"ERROR",
  			    "code" => "404",			 
				"message"=>'Datos incompletos!',
				"newtoken"=>$this->security->get_csrf_hash()
            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);            
        }
	}


}

