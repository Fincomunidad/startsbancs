
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Credito_checklist_Model extends CI_Model {
  public $idcredito;
  public $idchecklist;
  public $iddocumento;
  public $fecha;
  public $usuario;

	function __construct(){
		$this->load->model('Base_model','base');
	}

    // asigna los valores a la clase
    public function set_datos($data_cruda) { 
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            foreach($valor_campo as $nuevo_campo => $nuevo_valor){
                if ( property_exists('Credito_checklist_model', $nuevo_campo)){
                    $this->$nuevo_campo = $nuevo_valor;
                }
                $this->usuario = $this->ion_auth->user()->row()->id;
            }            
        }
        return $this;
    }


    public function update($datos, $where, $isarray) {
		$response =  $this->base->updateRecord( $this->session->userdata('esquema').'credito_checklist',$datos, $where, $isarray);
        return $response;        
    }
}