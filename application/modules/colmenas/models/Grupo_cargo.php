<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo_cargo_Model extends CI_Model {

  public $idgrupo;
  public $idcargo1;
  public $idcargo2;
  public $usuario;

	function __construct(){

	}

    // asigna los valores a la clase
    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Grupo_cargo_Model', $nombre_campo)){
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
		$response =  $this->base->insertRecord('col.grupo_cargo',$this, FALSE);
        return $response;        
    }

    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('col.grupo_cargo',$datos, $where, $isarray);
        return $response;        
    }
    

}
