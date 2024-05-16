<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aportasocial_Model extends CI_Model {
    public $esquema;
    public $idsucursal;
    public $iduser;
    public $idcaja;


	function __construct(){
        $this->esquema = $this->session->userdata("esquema");
        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->iduser = $this->ion_auth->user()->row()->id;
        $this->idcaja = $this->session->userdata('idcaja');
	}



    
    public function transaccion($mov){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 

            $aporta = $mov;

            if ($aporta['instrumento'] =="01") {
                $nomov = $this->base_caja->nextMovimiento();
            } else {
                $nomov = null;
            }
            $fecha = date("Y-m-d H:i:s");
            $sqlUpdate= array();
            $nosql=0;

            if ($aporta['movimiento'] =="E"  && $aporta['instrumento'] =="10") {
                $aporta['idbancodet'] = "'".$aporta['idbancodet']."'";
            }elseif ($aporta['movimiento'] =="I"  && $aporta['instrumento'] =="10") {
                $aporta['idbancodet'] = "null";
            }
            if ($aporta['instrumento'] =="01") {
                $sql = "insert into ".$this->esquema."aporta_social"." (idacreditado, idsucursal, fecha, movimiento, instrumento, importe, idbancodet, cheque_ref, afavor, nomov, idcaja, usuario) 
                    select  '". $aporta['idacreditado'] ."','".$this->idsucursal."', '".$fecha."','".$aporta['movimiento']."','".$aporta['instrumento']."','".$aporta['importe']."',null,null,
                    null,".$nomov.",'".$this->idcaja."','".$this->iduser."'";
                
            } else {
                $sql = "insert into ".$this->esquema."aporta_social"." (idacreditado, idsucursal, fecha, movimiento, instrumento, importe, idbancodet, cheque_ref, afavor, nomov, idcaja, usuario) 
                    select  '". $aporta['idacreditado'] ."','".$this->idsucursal."', '".$fecha."','".$aporta['movimiento']."','".$aporta['instrumento']."','".$aporta['importe']."',".$aporta['idbancodet'].",'".$aporta['cheque_ref']."','"
                    .$aporta['afavor']."',null,null,'".$this->iduser."'";
            }

            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            // Al final actualiza el numero de movimiento           
            if ($nomov != null) {
                $ant = $nomov-1;
                $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$this->idcaja."' and esquema='".$this->esquema."' and nomov=".$ant;
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
            }

			$response = $this->base->transaction($sqlUpdate, true);            
			if ($response['code'] == 200) {
                $response['nomov'] = $nomov;
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplique varias veces el registro.
                $response['nomov'] = 0;
                if ($response['err'] == 1) {
                    if ($response['message_error'] !=""){
                        $message = $response['message_error'];     
                                           
                    } 
                    $response['message'] = $message;
                    break;

                }

            }

		}
        return $response;        
        
    }



}