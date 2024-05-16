
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Boveda_Model extends CI_Model {


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


    public function transaction($pag){
//        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
//            $nomov = $this->base_caja->nextMovimiento();
            $fecha = date("Y-m-d H:i:s");
            $fecom =  date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;

            $bov = $pag;
            $sql = "insert into ".$this->esquema."boveda"." (idclave, tipo, idsucursal, fecinicio, iduser_a, status) 
                    select '". $bov['idclave'] ."','1','".$this->idsuc."','".$fecha."','".$this->iduser."','1'
                    where (select count(idclave) from ".$this->esquema."boveda where idclave ='".$bov['idclave']."' and fecinicio::date ='".$fecom."' 
                    and idsucursal='".$this->idsuc."') = 0  RETURNING idmov";
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


 public function transacClose($pag){
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $fecha = date("Y-m-d H:i:s");
            $fechacierre = date("Y-m-d H:i:s");
            $fechafin = date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;
            $bov = $pag;

            //Variable para cambiar el insert de boveda en caso que no tenga cierres de caja o notas de credito
            $existeCorteCaja = true;
            // Busca el idmov si no es del dia de hoy quiere decir que se va a realizar un 
            // Cierre del dia anterior por lo que si existe Notas de crédito o Cierre de Caja a entregar
            // Lo va a cerrar o cambiar el status y asignarl el usuario que autorizo
            $query = "select fecinicio::date as fecinicio from ".$this->esquema."boveda where idmov = ".$bov['idmov'];
            $queryBovAnt = $this->base->querySelect($query, TRUE);
            if ($queryBovAnt){
                if ($queryBovAnt[0]['fecinicio'] <> $fechafin ){
                    $fechacierre = substr($queryBovAnt[0]['fecinicio'],0,10).' 23:00:00';
                    $query = "select idmov from ".$this->esquema."boveda_mov where (tipo ='N' or tipo ='C') and status ='0' and idmov = ".$bov['idmov'];
                    $queryBovAnt = $this->base->querySelect($query, TRUE);
                    if ($queryBovAnt) {

                        // Se quitó para que se ingrese la nota de crédito y/o cierre en forma manual 
/*                        
                        $query = "update ".$this->esquema."boveda_mov set status='1', usuarioaut ='".$this->iduser."' where (tipo ='N' or tipo ='C') and status ='0' and idmov =".$bov['idmov'];
                        $sqlUpdate[0] = $query;
                        $response = $this->base->transaction($sqlUpdate, true);
                        //Si existe error al actualizar los movimientos de boveda_mov 
                        if ($response['code'] != 200) {
                            return $response;
                        }
*/

                    }else {
                        //Busca si existe Egreso a caja 
                        $query = "select idmov from ".$this->esquema."boveda_mov where movimiento ='E' and des_ori ='C' and tipo ='O' and  idmov = ".$bov['idmov'];
                        $queryBovAnt = $this->base->querySelect($query, TRUE);
                        if (!$queryBovAnt) {
                            $existeCorteCaja = false;
                        }
                    }
                }else {
                    //Busca si existe notas de credito o cierre de caja del dia activo
                    //Sino existe para que no valida los cortes o notas en la tabla boveda_mov
                    $query = "select idmov from ".$this->esquema."boveda_mov where (tipo ='N' or tipo ='C') and status ='0' and idmov = ".$bov['idmov'];
                    $queryBovAnt = $this->base->querySelect($query, TRUE);
                    if (!$queryBovAnt) {
                        //Busca si existe Egreso a caja 
                        $query = "select idmov from ".$this->esquema."boveda_mov where movimiento ='E' and des_ori ='C' and tipo ='O' and idmov = ".$bov['idmov'];
                        $queryBovAnt = $this->base->querySelect($query, TRUE);
                        if (!$queryBovAnt) {
                            $existeCorteCaja = false;
                        }
                    }
                }
            }
            $sqlUpdate= array();
            // Crear un registro en boveda_sal
            $sql = "insert into ".$this->esquema."boveda_sal "." (idclave, idsucursal, fecha, iduser, idmov)  
                    select '". $bov['idclave'] ."','".$this->idsuc."','".$fecha."','".$this->iduser."','".$bov['idmov']."'
                    where (select count(idclave) from ".$this->esquema."boveda_sal where idclave ='".$bov['idclave']."' and idmov =".$bov['idmov']." 
                    and idsucursal='".$this->idsuc."') = 0";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            // Determina el saldo final y lo agrega 
            $query = "select * from ".$this->esquema."get_bovedas_salini('".$fechafin."', ".$bov['idmov'].")";
            $querySalFin = $this->base->querySelect($query, TRUE);
            if ($querySalFin){
                foreach($querySalFin as $key => $value){
                    $cantidad = $value['cantidad'];

                    $sql = "insert into ".$this->esquema."boveda_sal_det"." (idmov, iddenomina, cantidad) 
                            values('".$bov['idmov']."','".$value['iddenomina']."','".$cantidad."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }
            }


            //Integra la consulta de la tabla boveda_mov en caso que exista Egresos a Caja 
            //y no tenga cerrado el Cierre de Caja
            $sCierreCorte = "";
            if ($existeCorteCaja == true) {
                $sCierreCorte ="and 
                (Select count(idmov) from ".$this->esquema."boveda_mov where tipo ='C' and status ='1' 
                  and idmov='".$bov['idmov']."') > 0 ";
            }

            //Update boveda 
            $sql = "update ".$this->esquema."boveda"." set fecfinal='".$fechacierre."', status ='0' 
                    where idclave='".$bov['idclave']  ."' and tipo='1' and idsucursal='".$this->idsuc."' and status='1' and idmov='".$bov['idmov']."' 
                    and (Select count(idmov) from ".$this->esquema."boveda_mov where status ='0' 
                          and idmov='".$bov['idmov']."') = 0 ".$sCierreCorte." and 
                        (Select count(idmov) from ".$this->esquema."boveda_sal_det where idmov='".$bov['idmov']."') > 0";
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
                if ($response['err'] = 1) {
//                    $response['message'] = "Error en ".$response['noquery']." ".$response['consultas']."!";
  //                  break;
                    if ($response['noquery'] >= 15) {
                        $response['message'] = "Existen movimientos por aplicar, verifique su Notas de crédito y/o Cierre de caja!";
                        break;
                    }
                }
            }
		}
        return $response;        
    }




}




