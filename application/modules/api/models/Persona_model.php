<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_Model extends CI_Model {

  public $idsucursal; 
  public $fechaalta;
  public $rfc;
  public $tipo;
  public $cia;
  public $replegal;
  public $nombre1;
  public $nombre2;
  public $apaterno; 
  public $amaterno;
  public $curp;
  public $folio_ife;
  public $vine;
  public $edocivil;
  public $fecha_nac;
  public $escolaridad;
  public $sexo;
  public $conyuge;
  public $email;
  public $celular;
  public $idactividad;
  public $experiencia;
  public $patrimonio;
  public $ingresomen;
  public $ingresomenext;
  public $egresomen;
  public $egresomenext;
  public $dependientes;
  public $ahorro;
  public $inicio_opera;
  public $idnacionalidad;
  public $fecha_mov;
  public $usuario;
  public $aliaspf;
  public $otroiden;
  public $paisnac;
  public $edonac;
  public $lugnac;
  public $domlaboral;
  public $domlabref;
  public $teltrabajo;
  public $idtiposociedad;
  public $personarel;
  public $idpersonarel;
  public $nota;

	function __construct(){
		$this->load->model('Base_model','base');
	}

    // asigna los valores a la clase
    public function set_datos($data_cruda) { 
      $fieldnumeric = array('edocivil','escolaridad','experiencia','patrimonio','ingresomen',
                    'ingresomenext','egresomen','egresomenext','dependientes','ahorro');
        //Limpia los valores 
        foreach  ($this as $nombre_campo => $valor_campo ){
            if (in_array($nombre_campo,$fieldnumeric)) {
                $this->$nombre_campo = NULL;
            }else if ($nombre_campo != 'inicio_opera') {
                $this->$nombre_campo = NULL;
            }
        }
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Persona_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->fecha_mov = date("Y-m-d H:i:s");
        $this->usuario = $this->ion_auth->user()->row()->id;
//        $this->idnacionalidad = "MEX";
        if ( $this->paisnac == "MEX") 
        {
            $this->lugnac = "";
        }else {
            $this->edonac = "";
        }

        if ($this->tipo =="F"){
            $this->cia ="";
            $this->idtiposociedad = NULL;
        }else{
            $this->cia = strtoupper($this->cia);            
        }
        $this->replegal = trim(strtoupper($this->replegal));
        $this->nombre1 = trim(strtoupper($this->nombre1));
        $this->nombre2 = trim(strtoupper($this->nombre2));
        $this->apaterno = trim(strtoupper($this->apaterno));
        $this->amaterno = trim(strtoupper($this->amaterno));
        $this->aliaspf = trim(strtoupper($this->aliaspf));
        $this->rfc = trim(strtoupper($this->rfc));
        $this->curp = trim(strtoupper($this->curp));
        $this->email = trim(strtolower($this->email));


        $this->conyuge = trim(strtoupper($this->conyuge));
        $this->domlaboral = trim(strtoupper($this->domlaboral));
        $this->domlabref = trim(strtoupper($this->domlabref));
        $this->folio_ife = trim(strtoupper($this->folio_ife));
        $this->vine = trim(strtoupper($this->vine));
        $this->nota = trim(strtoupper($this->nota));

        if ($this->personarel == "0"){
            $this->idpersonarel =null;
        }
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
		$response =  $this->base->insertRecord('public.personas',$this, TRUE);
        return $response;        
    }


    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('public.personas',$datos, $where, $isarray);
        return $response;        
    }


}