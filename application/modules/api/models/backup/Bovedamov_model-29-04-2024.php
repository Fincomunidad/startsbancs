<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bovedamov_Model extends CI_Model {

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



    public function transacmov($datos, $detalle){
        for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
//            $nomov = $this->base_caja->nextMovimiento();
            if ($datos['fechacierre']!= ''){
                $fecha = $datos['fechacierre'].' 22:59:00';
                $fec= $datos['fechacierre'];
            }else {
                $fecha = date("Y-m-d H:i:s");
                $fec = date("Y-m-d");
            }
            $sqlUpdate= array();
            $nosql=0;
            $bov = $datos;
            if ($bov['movimiento']  == "I"  && $bov['des_ori'] == "C") {
                $rec = $detalle[0];
                if (isset($rec['cantidad'])) {

                    if ($bov['tipo'] =="C") {
                        $sql = "insert into ".$this->esquema."boveda_mov"." (idmov, fecha, movimiento, des_ori, idbanco, importe, status, usuario,tipo,inicial) 
                        select '".$bov['idmov']."','".$fecha."','".$bov['movimiento']."','".$bov['des_ori']."','".$bov['idbanco']."','".$bov['importe']."','".$bov['status']."','".$this->iduser."','".$bov['tipo']."',''
                        Where (Select count(*) from ".$this->esquema."boveda_mov"." where fecha::date ='".$fec."' and des_ori ='C' and idbanco ='".$bov['idbanco']."') <> 0 and 
                              (Select count(*) from ".$this->esquema."boveda_mov"." where fecha::date ='".$fec."' and movimiento ='I' and des_ori ='C' and idbanco ='".$bov['idbanco']."' and status='0') = 0  and 
                              (Select count(*) from ".$this->esquema."inversiones"." where fechafin::date ='".$fec."' and idsucursal='".$this->idsuc."' and estatus= true) = 0 and 
                              (select count(*) from ".$this->esquema."creditos c where not fecha_aprov is null and not fecha_dispersa is null and fecha_entrega is null and c.idsucursal ='".$this->idsuc."' and fecha_dispersa::date ='".$fec."') = 0							  
                              RETURNING idmovdet";
                    }else {
                        $sql = "insert into ".$this->esquema."boveda_mov"." (idmov, fecha, movimiento, des_ori, idbanco, importe, status, usuario,tipo,inicial) 
                        select '".$bov['idmov']."','".$fecha."','".$bov['movimiento']."','".$bov['des_ori']."','".$bov['idbanco']."','".$bov['importe']."','".$bov['status']."','".$this->iduser."','".$bov['tipo']."',''
                        Where (Select count(*) from ".$this->esquema."boveda_mov"." where fecha::date ='".$fec."' and des_ori ='C' and idbanco ='".$bov['idbanco']."') <> 0 and 
                              (Select count(*) from ".$this->esquema."boveda_mov"." where fecha::date ='".$fec."' and movimiento ='I' and des_ori ='C' and idbanco ='".$bov['idbanco']."' and status='0') = 0  
                              RETURNING idmovdet";
                    }

                }else{
                    $sql = "update ".$this->esquema."boveda_mov"." set status ='1', usuarioaut ='".$this->iduser."'
                    Where fecha::date ='".$fec."' and movimiento ='I' and des_ori ='C' and idbanco ='".$bov['idbanco']."' and importe =".$bov['importe']." and status='0' and (tipo='N' or tipo ='C')";
                }
            } else {
                //Buscaria si se trata del primer movimiento de Egreso de boveda a caja
                //Para determinar que es Dotacion inicial a boveda 
                $inicial ='';
                $validaCierre ="";
                if ($bov['movimiento']  == "E"  && $bov['des_ori'] == "C") {
                    $queryb = "Select count(fecha) as conteo from ".$this->esquema."boveda_mov where fecha::date ='".$fec."' and movimiento ='E' and des_ori ='C' and idbanco ='".$bov['idbanco']."'";
                    $queryBov = $this->base->querySelect($queryb, TRUE);
                    if ($queryBov[0]['conteo'] == 0){
                        $inicial = '1';
                    }

                    //Valida si existe un cierre no genere  movimientos 
                    $queryb = "Select count(fecha) as conteo from ".$this->esquema."boveda_mov where fecha::date ='".$fec."' and movimiento ='I' and des_ori ='C' and idbanco ='".$bov['idbanco']."' and tipo ='C'";
                    $queryBov = $this->base->querySelect($queryb, TRUE);
                    if ($queryBov[0]['conteo'] > 0){
                        $respuesta = array("status"=>"ERROR",
                            "code" => "404",
                            "message"=>"Se ha generado el cierre de caja, no es imposible realizar el movimiento!",
                            "newtoken"=>$this->security->get_csrf_hash()
                        );
                    return $respuesta;
                    }

                }

                //inserta un movimineto  de egreso y valida que no exista un cierre de caja 
                $sql = "insert into ".$this->esquema."boveda_mov"." (idmov, fecha, movimiento, des_ori, idbanco, importe, status, usuario, tipo, inicial) 
                        select '".$bov['idmov']."','".$fecha."','".$bov['movimiento']."','".$bov['des_ori']."','".$bov['idbanco']."','".$bov['importe']."','1','".$this->iduser."','".$bov['tipo']."','".$inicial."'".$validaCierre." RETURNING idmovdet";
            }
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            $rec = $detalle[0];
            if (isset($rec['cantidad'])) {
                foreach($detalle as $key => $value){
                    $bovdet = $value;
                    $cantidad = $bovdet['cantidad'];
                    if ($bov['movimiento']  == "E") {
                        $cantidad = ($bovdet['cantidad'] * -1); 
                    }
                    $sql = "insert into ".$this->esquema."boveda_mov_det"." (idmovdet, iddenomina, cantidad) 
                            values('?','".$bovdet['iddenomina']."','".$cantidad."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }
            }
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                if ($response['err'] == 1) {
                    if ($response['noquery'] == 2 && $bov['movimiento']  == "I"  && $bov['des_ori'] == "C" && $bov['tipo'] =="C") {
                        $response['message'] = "Existen inversiones y/o nota de crédito por aplicar!";
                    }else if ($response['noquery'] == 2 && $bov['movimiento']  == "I"  && $bov['des_ori'] == "C" && $bov['tipo'] =="N") {
                        $response['message'] = "Existe una nota de crédito que no ha sido autorizada en boveda";;
                    }
                    break;
                }
            }
		}
        return $response;       
    }




}




