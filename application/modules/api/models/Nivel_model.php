<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Nivel_Model extends CI_Model {
    
    public $nivel;
    public $importe;
    public $ssi_tasa;
    public $ssi_pago;
    public $pf_tasa;
    public $pf_servicio;
    public $pf_capital;
    public $pf_aporte_sol;
    public $pf_garantia;
    public $numero_pagos;
    public $dias;
    public $idproducto;
    public $fecha_inicio;
    public $ssi_tasamora;
    public $tasa_ind;
    public $tasa_mora;

	function __construct(){
		//$this->load->model('Base_model','base');
	}

    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Nivel_Model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
            if ($nombre_campo === "fecha"){
                $this->fecha_inicio = $valor_campo;
            }
        }
        $this->importe = $this->nivel * 1000;
        $this->ssi_pago = $this->pf_capital + $this->pf_aporte_sol;
        $this->pf_tasa = $this->ssi_tasa;
        $this->pf_servicio = 0;
        $this->idproducto = 1;
        $this->ssi_tasamora = $this->ssi_tasa;
        $this->tasa_ind = $this->ssi_tasa;
        $this->tasa_mora = $this->ssi_tasa;
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
        /*
		$response =  $this->base->insertRecord('fin.creditos',$this, TRUE);
        return $response;        
        */
        $response =  $this->base->insertRecordSequence('niveles',$this, TRUE, 'seq_idnivel');
        return $response;
    }


    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('niveles',$datos, $where, $isarray);
        return $response;        
    }


}