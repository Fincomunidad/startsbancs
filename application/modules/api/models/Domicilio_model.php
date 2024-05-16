<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Domicilio_Model extends CI_Model {

  public $idpersona;
  public $tipo;
  public $fecha;
  public $direccion1;
  public $direccion2;
  public $noexterior;
  public $nointerior;
  public $idestado;
  public $idmunicipio;
  public $idcolonia;
  public $ciudad;
  public $cp;
  public $telefono;
  public $telefonoext;
  public $fax;
  public $activo;
  public $tiempo;
  public $tipovivienda;
  public $aguapot;
  public $enerelec;
  public $drenaje;

	function __construct(){

	}

    // asigna los valores a la clase
    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Domicilio_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        $this->aguapot = $this->aguapot == 'on' ? '1' : '0';
        $this->enerelec = $this->enerelec == 'on' ? '1' : '0';
        $this->drenaje = $this->drenaje == 'on' ? '1' : '0';
        $this->fecha = $data_cruda['fechaalta'];
        $this->tipo ="1";
        $this->activo= true;

        $this->direccion1 = strtoupper($this->direccion1);
        $this->direccion2 = strtoupper($this->direccion2);
        $this->ciudad = strtoupper($this->ciudad);
        

        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */

    public function insertar($datos){
		$response =  $this->base->insertRecord('public.persona_domicilio',$this, FALSE);
        return $response;        
    }

    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('public.persona_domicilio',$datos, $where, $isarray);
        return $response;        
    }
    

}
