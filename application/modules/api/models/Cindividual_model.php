<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cindividual_Model extends CI_Model {

//    public $idcredito;
    public $idcredito;
    public $idsucursal;
    public $fecha;
    public $fecha_pago;

    public $condonacion;
    public $gastos;
    public $efectivo;
    public $importepagar;
    public $fecha_mov;
    public $usuario;

    

	function __construct(){
		//$this->load->model('Base_model','base');
	}



/*
    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Credito_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }

  //      $this->idcredito = $this->idgrupo;
        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->fecha_mov = date("Y-m-d H:i:s");
        $this->usuario = $this->ion_auth->user()->row()->id;

        return $this;
    }
*/



  
    /*
    *
    */
    public function transaccion($mov, $fecha_pago){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsucursal = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $idcaja = $this->session->userdata('idcaja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
        if ($fecha_pago ==""){
            $fecha =  date("Y-m-d");            
        }else{
            $fecha = $fecha_pago;
        }

		for($i=0; $i<=2; $i++) {
            $nomov = $this->base_caja->nextMovimiento();
            $fecha = date("Y-m-d H:i:s");
            $fechoy = date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;
            $credito = $mov;

            $sql = "insert into ".$esquema."pagos"." (idcredito, fecha_pago, total_pago, condonacion, gastos, nomov, caja, idmoneda, idinstrumento, idsucursal) 
                        select  '". $credito['idcredito'] ."','".$fecha."','".$credito['importepagar']."','".$credito['condonacion']."','".$credito['gastos']."','".$nomov."','".$idcaja."','MXN','01','".$idsucursal."'";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$idcaja."' and esquema='".$esquema."' and nomov=".$ant;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

			$response = $this->base->transaction($sqlUpdate, true);

			if ($response['code'] == 200) {
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplique varias veces el registro.
                $response['nomov'] = 0;
                if ($response['err'] == 1) {
//                    $message ="Crédito ha sido aplicado, ya no existe para dispersión!";
//                    $response['message'] = $message;
                    break;

                }

            }

		}
        return $response;        
    }




}