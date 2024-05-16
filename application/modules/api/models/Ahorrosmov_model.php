
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ahorrosmov_Model extends CI_Model {

  public $numero_cuenta;
  public $fecha;
  public $idsucursal;
  public $movimiento;
  public $importe;
  public $idinstrumento;
  public $lugar;
  public $idbanco;
  public $idpagare;
  public $usuario;
  public $nomov;
  public $idcaja;
  public $idpol1;
  public $idpol2;

    public $esquema;
    public $idsuc;
    public $iduser;

	function __construct(){
//		$this->load->model('Base_model','base');

        $this->esquema = $this->session->userdata("esquema");
        $this->idsuc = $this->session->userdata('sucursal_id');
        $this->iduser = $this->ion_auth->user()->row()->id;
        $this->idcaja = $this->session->userdata('idcaja');

	}


    public function transacRetiro($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
            $fecha = date("Y-m-d H:i:s");
            $fechoy = date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;

            $ahorro = $pag;            
            $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                    select '". $ahorro['idahorrop'] ."','".$fecha."', '".$this->idsuc."','R','".$ahorro['importe']."','01','1',null,'".$ahorro['idcredito']."','".$nomov."','".$this->idcaja."','".$this->iduser."','01'
                    where (select comprometido from ".$this->esquema."get_creditos_resumen(".$ahorro['idacreditado'].") where idahorro ='".$ahorro['idahorrop']."' and idcredito='".$ahorro['idcredito']."' 
                    and idsucursal='".$this->idsuc."') = ".$ahorro['importe']." and 
                    (select saldocaja from ".$this->esquema."get_bovedas_saldos('".$fechoy."','C','".$this->idcaja."') where idsucursal ='".$this->session->userdata('sucursal_id')."') >= ".$ahorro['importe'];
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            // aplica un deposito a la cuenta de ahorro voluntario 
            // en caso de iddestino =="V"    Ahorro Voluntario 
            if ( $ahorro['iddestino'] =="V") {
                $query = "Select numero_cuenta, idahorro from ".$this->esquema."get_ahorros_voluntarios(".$ahorro['idacreditado'].",'G','".$fechoy."') where substring(numero_cuenta,1,2) ='AV'";
                $sql = $this->base->querySelect($query, TRUE);
                if (is_array($sql)){
					if ($sql != []){
						$ctaAhorro = $sql[0]['idahorro'];
						$sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
								values('". $ctaAhorro ."','".$fecha."', '".$this->idsuc."','D','".$ahorro['importe']."','01','1',null,'".$ahorro['idcredito']."','".$nomov."','".$this->idcaja."','".$this->iduser."','02')";
						$sqlUpdate[$nosql] = $sql;
						$nosql++;						
					}
                }else{
                    $response = array("status"=>"OK",
                        "code" => "404",
                        "message"=>"Aplicación incorrecta. Error CX004",
                        "result" => $query->result_array()
                    );
                    break;
                }
            }




            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$this->idcaja."' and esquema='".$this->esquema."' and nomov=".$ant;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
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

    public function transaction($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
            $fecha = date("Y-m-d H:i:s");
            $fechoy = date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;

            $ahorro = $pag;

            if ($ahorro['movimiento'] == "D") {
                $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                            values('". $ahorro['idahorrov'] ."','".$fecha."', '".$this->idsuc."','".$ahorro['movimiento']."','".$ahorro['importe']."','01','1',null,null,'".$nomov."','".$this->idcaja."','".$this->iduser."','01')";
            } else {
                $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                        select '". $ahorro['idahorrov'] ."','".$fecha."', '".$this->idsuc."','".$ahorro['movimiento']."','".$ahorro['importe']."','01','1',null,null,'".$nomov."','".$this->idcaja."','".$this->iduser."', '01'
                        where (select saldo from ".$this->esquema."get_ahorros_voluntarios(".$ahorro['idacreditado'].",'R','".$fechoy."') where  idahorro ='".$ahorro['idahorrov']."') >= ".$ahorro['importe']." and 
                        (select saldocaja from ".$this->esquema."get_bovedas_saldos('".$fechoy."','C','".$this->idcaja."') where idsucursal ='".$this->session->userdata('sucursal_id')."') >= ".$ahorro['importe'];
            }
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
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                if ($response['err'] == 1) {
                    $response['message'] = "Error. Cuenta bloqueada, fondos insuficientes o saldo de caja en cero. Error CX001";
                    break;

                }

            }

		}
        return $response;
    }


}




