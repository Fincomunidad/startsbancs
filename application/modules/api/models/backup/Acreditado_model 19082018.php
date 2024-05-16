
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Acreditado_Model extends CI_Model {
    public $idpersona;
    public $idacreditado;
    public $idsucursal;
    public $idgrupo;


	function __construct(){
		$this->load->model('Base_model','base');
	}

    // asigna los valores a la clase
    public function set_datos($data_cruda) { 
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Acreditado_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
         $this->idsucursal = $this->session->userdata('sucursal_id');
//         $this->idgrupo = 0;
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
		$response =  $this->base->insertRecord($this->session->userdata('esquema').'acreditado',$this, FALSE);
        return $response;        
    }


    public function transaction( $pag ) {
        $this->load->model('../../cartera/models/Base_car','base_car');

        $idsuc = $this->session->userdata('sucursal_id');
        
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_car->nextAcreditado();
            $sqlUpdate= array();
            $nosql=0;

            $credito = $pag;
            $sql = "insert into ".$this->esquema."acreditado"." (idpersona, idacreditado, idgrupo, idsucursal, fecalta) 
                    select '".$credito['idpersona']."','".$nomov."', '".$credito['idgrupo']."','".$idsuc."','".$credito['fechaalta']."'
                    RETURNING acreditadoid";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='00' and esquema='".$this->esquema."' and nomov=".$ant;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
                $response['pagare'] = $nomov;
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                if ($response['err'] == 1) {
                    break;
                }
            }
        }
        return $response;        
    }
}