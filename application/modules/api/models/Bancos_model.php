
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bancos_Model extends CI_Model {
    public $idbanco;
    public $nombre;
    public $activo;
    public $esquema;


	function __construct(){
		$this->load->model('Base_model','base');
        $this->esquema = $this->session->userdata("esquema");
		}

    // asigna los valores a la clase
    public function set_datos($data_cruda) { 
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Bancos_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
		$response =  $this->base->insertRecord('public.bancos',$this, FALSE);
        return $response;        
    }

    public function update($datos, $where, $isarray) {
		$response =  $this->base->updateRecord('public.bancos',$this, $where, $isarray);
        return $response;        
    }
	

    public function transacEdoCta($datos){
        
		$response =  $this->base->insertRecord($this->esquema.'edocta',$datos, FALSE);
        return $response;        

    }
	
    public function transacUpdateEdoCta($datos,$where, $isarray){        
		$response =  $this->base->updateRecord($this->esquema.'edocta',$this, $where, $isarray);
        return $response;        

    }
	
}