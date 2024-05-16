<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Udis_Model extends CI_Model {
    public $fecha;
    public $valor;
    public $usuario;

	function __construct(){
        $this->usuario = $this->ion_auth->user()->row()->id;

	}

    public function set_datos($data_cruda) {
        //Busca el campo y le almacena el valor de la $data_cruda
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Udis_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function insertar($datos){
		$response =  $this->base->insertRecord('public.udis',$this, FALSE);
        return $response;        
    }

     public function update($datos, $where, $isarray){
//		$response =  $this->base->updateRecord('public.productos',$datos, $where, $isarray);
  //      return $response;        
    }
    
}