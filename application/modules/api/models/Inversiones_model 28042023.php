
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Inversiones_Model extends CI_Model {
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


    public function transaccion($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $this->load->model('../../cartera/models/Base_car','base_car');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
//            $nomov = $this->base_caja->nextMovimiento();
            $numero = $this->base_car->nextMovimiento();
            $sqlUpdate= array();
            $nosql=0;
            $inv = $pag;
            $date = date_create(substr($inv['fecha'],6).'-'.substr($inv['fecha'],3,2).'-'.substr($inv['fecha'],0,2));
            $feccom = date_format($date, 'Y-m-d');
            $fecha = $feccom.' '.date("H:i:s");

            $date = date_create(substr($inv['fechafin'],6).'-'.substr($inv['fechafin'],3,2).'-'.substr($inv['fechafin'],0,2));
            $fechafin = date_format($date, 'Y-m-d');            
//            $fecha = $inv['fecha'].' '.date("H:i:s");
            $fechadis = date("Y-m-d H:i:s");

            //Si el monto para inversion se obtiene del Ahorro se integra la fecha de entrega = fecha de dispersion
            if ($inv['retiroc'] == 'V'){
                $fecha_entrega ="'".$fechadis."'";
            }else{
                $fecha_entrega ="null";
            }

            $sql = "insert into ".$this->esquema."inversiones "." (idacreditado, idsucursal,fecha,numero, dias, tasa, fechafin,importe, interes, isr, total, estatus, usuario, fecha_dispersa, fecha_entrega) 
                select '".$inv['idacreditado']."','".$this->idsuc."','".$fecha."','".$numero."','".$inv['dias']."','".$inv['tasa']."','".$fechafin."','".$inv['importe']."','".$inv['interes']."',false,'".$inv['total']."',true,'".$this->iduser."','".$fechadis."',".$fecha_entrega."
                where (select count(idacreditado) from ".$this->esquema."inversiones where idacreditado =".$inv['idacreditado']." and idsucursal='".$this->idsuc."' and dias =".$inv['dias']." and fecha::date ='".$feccom."') = 0
                RETURNING idinversion";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            $instrumento = "01";
            if ($inv['retiroc'] == 'V'){
                $instrumento = "03";
            }

            $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
            values('?','".$fecha."','D','".$instrumento. "','01','".$inv['importe']."')";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            if ($inv['retiroc'] == 'V'){
                $sql = "insert into ".$this->esquema."ahorros_mov "." (idahorro, fecha, idsucursal,movimiento, importe, idinstrumento,lugar, usuario, nomov, idmovimiento) 
                select '".$inv['idahorro']."','".$fecha."','".$this->idsuc."','R','".$inv['importe']."','03','1','".$this->iduser."','?','10'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
            }

            // Al final actualiza el numero de movimiento           
            $antinv = $numero-1;
            $sql = "update public.inversion_mov"." set nomov=".$numero." where esquema='".$this->esquema."' and nomov=".$antinv;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;



//            print_r($sqlUpdate);
//            die();

			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
                $response['registros'] = $numero;
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                if ($response['err'] == 1) {
                    $response['message'] = "Error en transacción";
                    break;

                }

            }

		}
        return $response;
    }




    /*
    * Inversion recibida en Caja
    */
    public function transupdate($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
//            $numero = $this->base_car->nextMovimiento();
            $sqlUpdate= array();
            $nosql=0;
            $inv = $pag;
            $fechaent = date("Y-m-d H:i:s");

            $date = date_create(substr($inv['fecha'],6).'-'.substr($inv['fecha'],3,2).'-'.substr($inv['fecha'],0,2));
            $feccom = date_format($date, 'Y-m-d');


            $sql = "update ".$this->esquema."inversiones "." set fecha_entrega ='".$fechaent."'
                where idacreditado =".$inv['idacreditado']." and idsucursal ='".$this->idsuc."' and fecha::date ='".$feccom."' and numero =".$inv['numero']." and total =".$inv['total']." and fecha_entrega is null";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
            
            $sql = "update ".$this->esquema."inversiones_det "." set nomov =".$nomov.", idcaja ='".$this->idcaja."'
            where idinversion =".$inv['idinversion']." and nomov is null and idcaja is null";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

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
                    $response['message'] = "Error en transacción";
                    break;

                }

            }

		}
        return $response;
    }




    /*
    * Reinversion 
    */
    public function transreinver($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $this->load->model('../../cartera/models/Base_car','base_car');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
            $numero = $this->base_car->nextMovimiento();
            $idahorro =0;
            $sqlUpdate= array();
            $nosql=0;
            $inv = $pag;
            $fechaent = date("Y-m-d H:i:s");

            $fecha = $inv['fecha'].' '.date("H:i:s");
            $fechadis = date("Y-m-d H:i:s");

            //Busca la cuenta de ahorro 
            if (($inv['retiroccheck'] == "on" && $inv['cretiroc'] =="V") || ($inv['retiroicheck'] == "on" && $inv['cretiroi'] == "V")) {
				//and idsucursal ='".$this->idsuc."'
                $ahorro = $this->base->querySelect("Select idahorro from ".$this->esquema."ahorros where idacreditado =".$inv['idacreditado']." and idproducto ='02' and substring(numero_cuenta,1,2) ='AV' and fecha_baja is null order by numero_cuenta ASC",true);
                if ($ahorro){
                    $idahorro = $ahorro[0]['idahorro'];
                }
            }

            if ($inv['retiroccheck'] == "on" && $inv['retiroicheck'] == "on") {

            }else {
                //Agrega nueva inversion
                $sql = "insert into ".$this->esquema."inversiones "." (idacreditado, idsucursal,fecha,numero, numeroant, dias, tasa, fechafin,importe, interes, isr, total, estatus, usuario, fecha_dispersa, fecha_entrega) 
                    select '".$inv['idacreditado']."','".$this->idsuc."','".$fecha."','".$numero."','".$inv['numeroi']."','".$inv['dias']."','".$inv['tasa']."','".$inv['fechafin']."','".$inv['importe']."','".$inv['interes']."',false,'".$inv['total']."',true,'".$this->iduser."','".$fechadis."','".$fechadis."'
                    where (select count(idacreditado) from ".$this->esquema."inversiones where idacreditado =".$inv['idacreditado']." and idsucursal='".$this->idsuc."' and fecha::date ='".$inv['fecha']."') = 0
                    RETURNING idinversion";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
            }
            
            // Incremento de capital 
            if ($inv['incrementocheck'] == "on" && $inv['retiroicheck'] == "false") {
                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                values('?','".$fecha."','D','01','01','".$inv['totali']."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                values('?','".$fecha."','D','01','09','".$inv['incremento']."','".$nomov."','".$this->idcaja."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
                
            // Incremento de capital y retiro de interes
            }else if ($inv['incrementocheck'] == "on" && $inv['retiroicheck'] == "on"){
                $saldo = $inv['totali'] -  $inv['retiroi'];
                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                values('?','".$fecha."','D','01','01','".$saldo."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                values('?','".$fecha."','D','01','09','".$inv['incremento']."','".$nomov."','".$this->idcaja."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                // Efectivo
                if ($inv['cretiroi'] =="E"){
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('?','".$fecha."','D','01','03','".$inv['retiroi']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                    values('?','".$fecha."','R','01','03','".$inv['retiroi']."','".$nomov."','".$this->idcaja."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                }else if ($inv['cretiroi'] =="V"){
                    $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, usuario, idmovimiento) 
                    values('".$idahorro."','".$fecha."','".$this->idsuc."','D','".$inv['retiroi']."','01','1','".$this->iduser."','03')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                }

            // Retiro de capital
            }else if ($inv['retiroccheck'] == "on"  && $inv['retiroicheck'] == "false" ){

                if ($inv['cretiroc'] =="E"){

                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('?','".$fecha."','D','01','09','".$inv['retiroc']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                    values('?','".$fecha."','R','01','09','".$inv['retiroc']."','".$nomov."','".$this->idcaja."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                    
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('?','".$fecha."','D','01','01','".$inv['importe']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                }else if ($inv['cretiroc'] =="V"){

                    $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, usuario, idmovimiento) 
                    values('".$idahorro."','".$fecha."','".$this->idsuc."','D','".$inv['retiroc']."','01','1','".$this->iduser."','09')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }


            //Retiro de interes
            }else if ($inv['retiroccheck'] == "false"  && $inv['retiroicheck'] == "on" ){
                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                values('?','".$fecha."','D','01','01','".$inv['importe']."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;


                if ($inv['cretiroi'] =="E"){
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('?','".$fecha."','D','01','03','".$inv['retiroi']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                    values('?','".$fecha."','R','01','03','".$inv['retiroi']."','".$nomov."','".$this->idcaja."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                }else if ($inv['cretiroi'] =="V"){

                    $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, usuario, idmovimiento) 
                    values('".$idahorro."','".$fecha."','".$this->idsuc."','D','".$inv['retiroi']."','01','1','".$this->iduser."','03')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                    

                }
                
            }


            //Retiro de capital e interes (Cancelación)
            if ($inv['retiroccheck'] == "on"  && $inv['retiroicheck'] == "on" ){
                if ($inv['cretiroc'] =="V" && $inv['cretiroi'] =="V" ){
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('".$inv['idinversion']."','".$fecha."','R','01','09','".$inv['importei']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }else {
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                    values('".$inv['idinversion']."','".$fecha."','R','01','09','".$inv['importei']."','".$nomov."','".$this->idcaja."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }

                if ($inv['cretiroc'] =="V"){

                    $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, usuario, idmovimiento) 
                    values('".$idahorro."','".$fecha."','".$this->idsuc."','D','".$inv['importei']."','01','1','".$this->iduser."','09')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }

                if ($inv['cretiroi'] =="E"){
                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                    values('".$inv['idinversion']."','".$fecha."','D','01','03','".$inv['retiroi']."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;

                    $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe, nomov, idcaja) 
                    values('".$inv['idinversion']."','".$fecha."','R','01','03','".$inv['retiroi']."','".$nomov."','".$this->idcaja."')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }else if ($inv['cretiroi'] =="V"){
                    $sql = "insert into ".$this->esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, usuario, idmovimiento) 
                    values('".$idahorro."','".$fecha."','".$this->idsuc."','D','".$inv['retiroi']."','01','1','".$this->iduser."','03')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }
            // Retiro de capital de la inversion original 
            }else {
                $sql = "insert into ".$this->esquema."inversiones_det"." (idinversion, fecha, movimiento, idinstrumento, idmovimiento, importe) 
                values('".$inv['idinversion']."','".$fecha."','R','01','01','".$inv['importei']."')";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
            }

            //Se cancela la inversion de cualquier movimiento.
            $sql = "update ".$this->esquema."inversiones "." set estatus = false 
            where idacreditado =".$inv['idacreditado']." and idsucursal ='".$this->idsuc."' and numero =".$inv['numeroi']." and total =".$inv['totali']."";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;
        

            // Al final actualiza el numero de movimiento           
            $antinv = $numero-1;
            $sql = "update public.inversion_mov"." set nomov=".$numero." where esquema='".$this->esquema."' and nomov=".$antinv;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$this->idcaja."' and esquema='".$this->esquema."' and nomov=".$ant;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
                $response['registros'] = $numero;
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                if ($response['err'] == 1) {
                    $response['message'] = "Error en transacción";
                    break;
                }
            }
		}
        return $response;
    }
}




