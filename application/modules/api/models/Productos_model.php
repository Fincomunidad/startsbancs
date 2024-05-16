<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_Model extends CI_Model {

    public $idproducto;
    public $nombre;
    public $tipo;
    public $minini;
    public $maxini;
    public $movmin;
    public $movmax;
    public $monudi;


	function __construct(){

	}

    public function set_datos($data_cruda) {
        //Busca el campo y le almacena el valor de la $data_cruda
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Productos_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        if ($this->monudi == "on")
        {
            $this->monudi = true;
        }else {
            $this->monudi = false;
        }
        return $this;
    }

    public function insertar($datos){
		$response =  $this->base->insertRecord('public.productos',$this, FALSE);
        return $response;        
    }

     public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('public.productos',$datos, $where, $isarray);
        return $response;        
    }
    
}