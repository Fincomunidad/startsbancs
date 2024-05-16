<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_ben_Model extends CI_Model {

    public $idpersona;
    public $fecha;
    public $idbeneficiario;
    public $idparentesco;
    public $porcentaje;

	function __construct(){

	}

    public function set_datos($data_cruda) {
        //Busca el campo y le almacena el valor de la $data_cruda
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Persona_ben_model', $nombre_campo)){
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
		$response =  $this->base->insertRecord('public.persona_ben',$this, FALSE);
        return $response;        
    }

     public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('public.persona_ben',$datos, $where, $isarray);
        return $response;        
    }
    

}