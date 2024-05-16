<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ProvConfig_Model extends CI_Model {    

    //idprovcnf, idcredito, fecha_ini, fecha_fin, fecha_aprov, usuario_aprov, usuario, fecha_mov
    //public $idprovcnf;
    public $idcredito;
    public $fecha_ini;
    public $fecha_fin;
    public $fecha_aprov;
    public $usuario_aprov;
    public $usuario;
    public $fecha_mov;
	public $nota;

	function __construct(){
		//parent::__construct();		
		$this->load->model('Base_model','base');
	}



    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('ProvConfig_Model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }   

        $this->usuario = $this->ion_auth->user()->row()->id;
        $this->fecha_mov = date("Y-m-d H:i:s");
        
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
        //$response =  $this->base->insertRecord('col.colmenas',$this, TRUE);
        $response =  $this->base->insertRecordSequence($this->session->userdata("esquema").'prov_config', $this, TRUE, $this->session->userdata("esquema").'seq_idprovcnf');
        return $response;
    }



    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord($this->session->userdata("esquema").'prov_config',$datos, $where, $isarray);
        return $response;        
    }



}