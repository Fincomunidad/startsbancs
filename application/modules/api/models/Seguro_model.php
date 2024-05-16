
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Seguro_Model extends CI_Model {
    public $esquema;
    public $idsuc;
    public $iduser;
    public $movimiento;

	function __construct(){
//		$this->load->model('Base_model','base');
        $this->esquema = $this->session->userdata("esquema");
        $this->idsuc = $this->session->userdata('sucursal_id');
        $this->iduser = $this->ion_auth->user()->row()->id;
        $this->idcaja = $this->session->userdata('idcaja');
	}


    public function transaction($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            $nomov = $this->base_caja->nextMovimiento();
            $sqlUpdate= array();
            $nosql=0;
            $inv = $pag;
            $fecha = date("Y-m-d H:i:s");
            if ($inv['movimiento'] =="D") {
               $valida = " where (select count(*) as total from ".$this->esquema."seguros where idcredito =".$inv['idcredito']." and idsucursal='".$this->idsuc."') = 0";
            }else {
                $valida = " where (select count(*) as total from ".$this->esquema."seguros where idcredito =".$inv['idcredito']." and idsucursal='".$this->idsuc."') = 1 and 
                                  (select fehca_liquida from ".$this->esquema."v_seguros where idcredito =".$inv['idcredito']." and idsucursal='".$this->idsuc."') = null ";
            }
            $sql = "insert into ".$this->esquema."Seguros "." (fecha, idsucursal,idcredito, esquema, idmovimiento,movimiento, importe, idinstrumento, nomov, idcaja, usuario) select '".$fecha."','".$this->idsuc."','".$inv['idcredito']."','".$inv['esquema']."','01','".$inv['movimiento']."  ','".$inv['monto']."','01','".$nomov."','".$this->idcaja."','".$this->iduser."'".$valida;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$this->idcaja."' and esquema='".$this->esquema."' and nomov=".$ant;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);            
			if ($response['code'] == 200) {
                $response['nomov'] = $nomov;
				break;
			}else{
                if ($response['err'] == 1) {
                    $response['message'] = "Error en transacción, movimiento aplicado con anterioridad o sin saldo para aplicar el  movimiento";
                    break;
                }
            }
		}
        return $response;
    }
}
