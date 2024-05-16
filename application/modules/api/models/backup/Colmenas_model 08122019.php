<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Colmenas_Model extends CI_Model {    

//public idcolmena;
    public $idsucursal;
    public $numero;
    public $nombre;
    public $dia;
    public $idpromotor;
    public $horainicio;
    public $duracion;
    public $direccion;
    public $fecha_apertura;
    public $empresa;
    public $fecha_mov;
	public $map;

    public $idmunicipio;
    public $idcolonia;

	function __construct(){
		//parent::__construct();		
		$this->load->model('Base_model','base');
	}



    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Colmenas_Model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        $this->fecha_apertura = $data_cruda['fecha_apertura'];
        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->dia = $data_cruda['dia'];;
        $this->idpromotor = $data_cruda['idpromotor'];
        $this->horainicio = date('H:i:s', mktime($data_cruda['idHora'], $data_cruda['idMinuto'], 0, 1, 1, 2000));
        
        $this->duracion = 60;
        $this->fecha_mov = date("Y-m-d H:i:s");
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
        $response =  $this->base->insertRecord('col.colmenas',$this, TRUE);
        return $response;        
    }


    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord('col.colmenas',$datos, $where, $isarray);
        return $response;        
    }

    /*
    * realizar la transaccion de un bloque
    */
    public function transaction($pag, $fecha_pago="", $edocta){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $idcaja = $this->session->userdata('idcaja');

        //Busca en las amortizaciones si existe un moviento en la misma fecha 
        $evalua = true;
        if ($fecha_pago ==""){
            $fecha =  date("Y-m-d");            
        }else{
            $fecha = $fecha_pago;
        }
        foreach($pag as $key => $value){
            $pagare = $value;
            $sql = "select fecha_pago from ".$esquema."amortizaciones"." where fecha_pago::date ='".$fecha."' and idcredito =". $pagare['idcredito'];
            $record = $this->base->querySelect($sql, true);
            if (is_array($record)) {
                $evalua = false;
            }
        }

        if ($evalua == false) {
            $response = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error en aplicación de pagos. Error CX003. Pago aplicado con anterioridad",
                    "newtoken"=>$this->security->get_csrf_hash(),
				); 
           return $response;

        }

/*
                $nopagos = $pagare['nopagos'];
                $sql = "select garantia from ".$esquema."amortizaciones where idcredito =".$pagare['idcredito'].' limit 1';
                $rec = $this->base->querySelect($sql, true);
                $importe = $rec[0]['garantia'] *  $nopagos;

                print_r($rec);
                print_r($importe);
                die();
*/


        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
            $pagare= array();
            
            if ($fecha_pago ==""){
                $fecha = date("Y-m-d H:i:s");
            }else {
                $fecha = $fecha_pago.' '.date("H:i:s");
            }

            $sqlUpdate= array();
            $nosql=0;
            foreach($pag as $key => $value){
                $pagare = $value;
                $nopagos = $pagare['nopagos'];
                $idcredito = $pagare['idcredito'];
                $ultimoamor=0;                                
                if ($nopagos ==1) {
                    //En caso que solo se realice un pago al pagaré del acreditado
                    if ($pagare['numero'] > 0) {
                        $sql = "update ".$esquema."amortizaciones"." set fecha_pago ='".$fecha."', idcaja='".$idcaja."', nomov=".$nomov." where idcredito =". $pagare['idcredito']." and numero =". $pagare['numero']." and fecha_pago is null";
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;
                        $ultimoamor = $pagare['numero'];
                    }
                } else {
                    for($i=0; $i<$nopagos; $i++){
                        //En caso que solo se realice dos o mas pagos al pagaré del acreditado
                        $sql = "update ".$esquema."amortizaciones"." set  fecha_pago ='".$fecha."', idcaja='".$idcaja."', nomov=".$nomov." where idcredito =".$pagare['idcredito']." and numero =".($pagare['numero'] + $i)." and fecha_pago is null";
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;
                        $ultimoamor = $pagare['numero'] + $i;
                    }

                }

                //Si es el último pago guarda la fecha de liquidacion
                //primero valida si es el ultimo y despues si existe lo agrega a la transaccion
                $sql = "select a.idcredito from ".$esquema."creditos as a join ".$esquema."amortizaciones as b on a.idcredito = b.idcredito where a.monto = (b.numero * b.capital) and  b.idcredito =".$pagare['idcredito']." and b.numero =".$ultimoamor;
                $rec = $this->base->querySelect($sql, true);
                if (is_array( $rec)) {
                    $sql = "update ".$esquema."creditos as a set fecha_liquida='".$fecha."' where a.monto = (select (numero * capital) from ".$esquema."amortizaciones as b where a.idcredito = b.idcredito and b.numero =".$ultimoamor.") and a.idcredito=".$pagare['idcredito'];
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;
                }
                

                // Ahora lo voya  buscar de la tabla 
                $sql = "select garantia from ".$esquema."amortizaciones where idcredito =".$pagare['idcredito'].' and fecha_pago is null limit 1';
                $rec = $this->base->querySelect($sql, true);
                $importe = $rec[0]['garantia'] *  $nopagos;
                //Integra el ahorro comprometido 
//                $importe = $pagare['ahocomprome'];
                if ($importe > 0 && $pagare['ahocomprome'] > 0){
                    $sql = "insert into ".$esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                                values('". $pagare['numero_cuentac'] ."','".$fecha."', '".$idsuc."','D','".$importe."','01','1',null,'".$idcredito."','".$nomov."','".$idcaja."','".$iduser."','01')";
                    $sqlUpdate[$nosql] = $sql;
                    $nosql++;                
                }

                if ($esquema !='ban.') {
                    if ($pagare['ajuste'] > 0){
                        $importe = (double)str_replace(",","",$pagare['ajuste']);;
                        $sql = "insert into ".$esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                                    values('". $pagare['numero_cuentac'] ."','".$fecha."', '".$idsuc."','D','".$importe."','01','1',null,'".$idcredito."','".$nomov."','".$idcaja."','".$iduser."','11')";
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;                
                    }
                    $importe = $pagare['ahocorriente'];
                    if ($importe > 0 && $pagare['numero'] > 0 )  {
                        //Integra el ahorro volunntario en caso que contenga importe y tenga numero de crédito
                        $sql = "insert into ".$esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                                values('". $pagare['numero_cuentav'] ."','".$fecha."', '".$idsuc."','D','".$importe."','01','1',null,null,'".$nomov."','".$idcaja."','".$iduser."','01')";
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;	
                    }

                }

            }

     		if ($edocta != []) {
                //Agrega a la tabla edocta la asignacion de datos 
                $sql = "update ".$esquema."edocta"." set fecha ='".$edocta['fecha'].' '.$edocta['hora']."', vale='".$edocta['vale']."', semana='".$edocta['semana']."', caja ='".$edocta['caja']."' where id =".$edocta['autorizacion']." and fecha::date ='". $edocta['fecha']."' and deposito =".$edocta['deposito'];
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
                $sql = "insert into ".$esquema."edocta_colmena"." (edoctaid, grupoid, fecha, userid) 
                    select  '". $edocta['autorizacion']."','".$edocta['idgrupo']."', '".$fecha."','".$iduser."'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
             }

            // Al final actualiza el numero de movimiento           
            $ant = $nomov-1;
            $sql = "update public.cajasmov"." set nomov=".$nomov." where idcaja='".$idcaja."' and esquema='".$esquema."' and nomov=".$ant;
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
                $response['nomov'] = 0;
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }



    /*
    *  Reversa de aplicacion 
    */

    public function reversatrans($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $iduser = $this->ion_auth->user()->row()->id;
        $idcaja = $this->session->userdata('idcaja');
        $idsuc = $this->session->userdata('sucursal_id');

        $idgrupo = $pag['idgrupo'];
        $nomov = $pag['nomov'];

        // Busca si existe pagos posteriores         
        $sql = "select b.idcredito, b.fecha_pago, b.idcaja, b.nomov from ".$esquema."get_creditos_acreditado as a join ".$esquema."amortizaciones as b on b.idcredito = a.idcredito  where not fecha_aprov is null and not fecha_dispersa is null and not b.fecha_pago is null and idgrupo=".$idgrupo." and b.fecha_pago::date > current_date::date order by b.fecha_pago desc limit 1 ";
        $record = $this->base->querySelect($sql, true);

        if ($record){
            $response = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error en reversa de pagos. Existen pagos posteriores!",
                    "newtoken"=>$this->security->get_csrf_hash(),
				); 
           return $response;
        }


        // se inicia el proceso para reversa
        //Busca el registro del dia con el nomov 
        $sql = "select b.fecha_pago, b.idcaja, b.nomov from ".$esquema."get_creditos_acreditado as a join ".$esquema."amortizaciones as b on b.idcredito = a.idcredito  where not fecha_aprov is null and not fecha_dispersa is null and not b.fecha_pago is null and idgrupo=".$idgrupo." and b.fecha_pago::date = current_date::date and b.nomov =".$nomov." and b.idcaja ='".$idcaja."' order by b.fecha_pago desc limit 1 ";
        $record = $this->base->querySelect($sql, true);
        if ($record){
            $fecpago = $record[0]['fecha_pago'];
            $fecha = date("Y-m-d H:i:s");


            // Busca si existe un pago para autorizar
            $sql = "select * from ".$esquema."pagos_undo"." where idgrupo=".$idgrupo." and fecha_pago='".$fecpago."' and nomov=".$nomov." and idcaja='".$idcaja."'";
            $record2 = $this->base->querySelect($sql, true);

            if ($record2){
                if ($record2[0]['autoriza'] == 'f' || $record2[0]['autoriza'] == false) {
                    $response = array("status"=>"ERROR",
                        "code" => "404",
                        "message"=>"El movimiento no ha sido autorizado!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                    ); 
                    return $response;
                }
            }else {
                // No existe el registro por lo que se genera un proceso para que sea autorizado 
                $sql = "insert into ".$esquema."pagos_undo"." (idgrupo, fecha_pago, nomov, idcaja, idsucursal, autoriza) 
                values('". $idgrupo ."','".$fecpago."', '".$nomov."','".$idcaja."','".$idsuc."',false)";
                $sqlUpdate[0] = $sql;

                $response = $this->base->transaction($sqlUpdate, true);
                if ($response['code'] == 200) {
                    $response['message'] = 'Movimiento ha sido enviado para su autorización de reversa';
                }
                return $response;
            }


            for($i=0; $i<=2; $i++) {

                $sqlUpdate= array();
                $nosql=0;

                $sql = "insert into ".$esquema."amortizaciones_rev select * from  ".$esquema."amortizaciones where fecha_pago='".$fecpago."' and nomov=".$nomov." and idcaja ='".$idcaja."'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;
                
                $sql = "update ".$esquema."amortizaciones set fecha_pago=null, idcaja=null, nomov= null, capital_pag = 0, interes_pag = 0, iva_pag = 0, pago_chk =0, ahorro =0, saldo_capital2 =0 where fecha_pago='".$fecpago."' and nomov=".$nomov." and idcaja ='".$idcaja."'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                $sql = "insert into ".$esquema."ahorros_mov_rev select * from ".$esquema."ahorros_mov where fecha='".$fecpago."' and nomov=".$nomov." and idcaja='".$idcaja."' and movimiento ='D'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;


                $sql = "delete from ".$esquema."ahorros_mov where fecha='".$fecpago."' and nomov=".$nomov." and idcaja='".$idcaja."' and movimiento ='D'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                $sql = "update ".$esquema."pagos_undo set autoriza = true, usuario=".$iduser.", fecha='".$fecha."' where idgrupo=".$idgrupo." and fecha_pago='".$fecpago."' and nomov=".$nomov." and idcaja='".$idcaja."'";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;


                $response = $this->base->transaction($sqlUpdate, true);
                if ($response['code'] == 200) {
                    $response['message'] = 'Reversa de pago exitoso!';
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

        }else {
            $response = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error en reversa de pagos. Registro inexistente!",
                    "newtoken"=>$this->security->get_csrf_hash(),
				); 
           return $response;

        }
    }





    /*
    * realizar la transaccion de encargado de colmena para posteriormente aplicarse
    * en caja
    */
    public function transacol($pag, $edocta, $fecha_pago=""){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;

        //Busca en las amortizaciones si existe un moviento en la misma fecha 
        $evalua = true;
        if ($fecha_pago ==""){
            $fecha =  date("Y-m-d");            
        }else{
            $fecha = $fecha_pago;
        }
        foreach($pag as $key => $value){
            $pagare = $value;
            $sql = "select fecha_pago from ".$esquema."amortizaciones"." where fecha_pago::date ='".$fecha."' and idcredito =". $pagare['idcredito'];
            $record = $this->base->querySelect($sql, true);
            if (is_array($record)) {
                $evalua = false;
            }
        }

        if ($evalua == false) {
            $response = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error en aplicación de pagos. Error CX003. Pago aplicado con anterioridad",
                    "newtoken"=>$this->security->get_csrf_hash(),
				); 
           return $response;

        }



        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
//            $nomov = $this->base_caja->nextMovimiento();            
            $pagare= array();
            if ($fecha_pago ==""){
                $fecha = date("Y-m-d H:i:s");
            }else {
                $fecha = $fecha_pago.' '.date("H:i:s");
            }

            $sqlUpdate= array();
            $nosql=0;
            foreach($pag as $key => $value){
                $pagare = $value;
                $nopagos = $pagare['nopagos'];
                $ahocorriente = $pagare['ahocorriente'];
                $idcredito = $pagare['idcredito'];
                $asistencia = $pagare['asistencia'];
                $incidencia = $pagare['incidencia'];
                if ($asistencia  == '1') {
                    $incidencia = '1';
                }
                $ultimoamor=0;                                
                if ($nopagos ==1) {
                    //En caso que solo se realice un pago al pagaré del acreditado
                    if ($pagare['numero'] > 0) {
                        $sql = "update ".$esquema."amortizaciones"." set fecha_pago_col ='".$fecha."', asistencia='".$asistencia."', incidencia='".$incidencia."', ahorro_vol =".$ahocorriente." where idcredito =". $pagare['idcredito']." and numero =". $pagare['numero']." and (fecha_pago is null and fecha_pago_col is null)";
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;
                        $ultimoamor = $pagare['numero'];
                    }
                } else {
                    for($i=0; $i<$nopagos; $i++){
                        //En caso que solo se realice dos o mas pagos al pagaré del acreditado
                        if ($i == 0 ) {
                            $sql = "update ".$esquema."amortizaciones"." set  fecha_pago_col ='".$fecha."', asistencia='".$asistencia."', incidencia='".$incidencia."', ahorro_vol =".$ahocorriente." where idcredito =".$pagare['idcredito']." and numero =".($pagare['numero'] + $i)." and (fecha_pago is null and fecha_pago_col is null)";
                        }else {
                            $sql = "update ".$esquema."amortizaciones"." set  fecha_pago_col ='".$fecha."', ahorro_vol =0  where idcredito =".$pagare['idcredito']." and numero =".($pagare['numero'] + $i)." and (fecha_pago is null and fecha_pago_col is null)";
                        }
                        $sqlUpdate[$nosql] = $sql;
                        $nosql++;
                        $ultimoamor = $pagare['numero'] + $i;
                    }

                }
            }

            //Agrega a la tabla edocta la asignacion de datos 
            $sql = "update ".$esquema."edocta"." set fecha ='".$edocta['fecha'].' '.$edocta['hora']."', vale='".$edocta['vale']."', semana='".$edocta['semana']."', caja ='".$edocta['caja']."' where id =".$edocta['autorizacion']." and fecha::date ='". $edocta['fecha']."' and deposito =".$edocta['deposito'];
            $sqlUpdate[$nosql] = $sql;



            $nosql++;
            $sql = "insert into ".$esquema."edocta_colmena"." (edoctaid, grupoid, fecha, userid) 
                select  '". $edocta['autorizacion']."','".$edocta['idgrupo']."', '".$fecha."','".$iduser."'";
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
                $response['nomov'] = 0;
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }




    /*
    * realizar la transaccion de encargado de colmena para posteriormente aplicarse
    * en caja
    */
    public function transacvales($pag, $enca){
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;


        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
//            $nomov = $this->base_caja->nextMovimiento();            
            $promotor= array();

            $sqlUpdate= array();
            $nosql=0;
            foreach($pag as $key => $value){
                $promotor = $value;
                

                $nosql++;
                $sql = "insert into ".$esquema."promotor_vales"." (idpromotor, anio, semana, vale, fecha, userid) 
                    select  '". $promotor['idpromotor']."','".$enca['anio']."', '".$enca['semana']."', '".$promotor['vale']."', '".$promotor['fecha']."', '".$iduser."'";
                $sqlUpdate[$nosql] = $sql;


            }





            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                // en caso que contenga la bandera 1 no proseguir
                // ya que existio algún error de actualización por ejemplo 
                //que el registro ya que encuentre actualizado 
                //evitará que se aplica varias veces el registro.
                $response['nomov'] = 0;
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }





    
    /*
    * realizar la transaccion de encargado de colmena para posteriormente aplicarse
    * en caja
    */
    public function asistencia($pag, $edocta){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");

        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {

            $sqlUpdate= array();
            $nosql=0;
            $anio = $edocta['anio'];
            $semana =   $edocta['semana'];
            foreach($pag as $key => $value){
                $registro = $value;

                $acreditadoid = $registro['acreditadoid'];                
                $idgrupo = $registro['idgrupo'];      
                
                
                $asistencia = $registro['asistencia'];
                if ($asistencia == '' ) {
                    $asistencia = 1;
                }
                $incidencia = $registro['incidencia'];
                if ($incidencia == '' ) {
                    $incidencia = 5;
                }

                $opcion = $registro['opcion'];
                if ($opcion == '' ) {
                    $opcion = 0;
                }
                $verificacion = $registro['verificacion'];
                
                $niveldesea =  $registro['niveldesea'];
                if ($niveldesea =='' ) {
                    $niveldesea = 'null';
                }else {
                    $niveldesea = "'".$registro['niveldesea']."'";
                }
                $descrip = $registro['descrip'];
                   
            	$query = "select * from col.asistencia where acreditadoid =".$acreditadoid." and idgrupo =".$idgrupo." and anio =".$anio." and semana =".$semana;
                $rec = $this->base->querySelect($query, true);
                if ($rec == []) {
                    
                    $sql = "insert into col.asistencia"." (acreditadoid, idgrupo, asistencia, incidencia, anio, semana, opcion, verificacion, niveldesea, descrip, fecmov, iduser) 
                            select  '". $acreditadoid."','".$idgrupo."', '".$asistencia."', '".$incidencia."', '".$anio."', '".$semana."','".$opcion."','".$verificacion."',".$niveldesea.",'".$descrip."','".$fecha."', '".$iduser."'";

                }else {
                    $sql = "update col.asistencia set asistencia='".$asistencia."', incidencia='".$incidencia."', opcion='".$opcion."', verificacion='".$verificacion."', niveldesea=".$niveldesea.", descrip='".$descrip."', fecmov= '".$fecha."', iduser=".$iduser.
                        " where acreditadoid =".$acreditadoid." and idgrupo =".$idgrupo." and anio =".$anio." and semana =".$semana;

                }

				/*
                $sql = "insert into col.asistencia"." (acreditadoid, idgrupo, asistencia, incidencia, anio, semana, opcion, verificacion, niveldesea, descrip, fecmov, iduser) 
                    select '". $acreditadoid."','".$idgrupo."', '".$asistencia."', '".$incidencia."', '".$anio."', '".$semana."','".$opcion."','".$verificacion."',".$niveldesea.",'".$descrip."','".$fecha."', '".$iduser."'
                                    ON CONFLICT (acreditadoid, idgrupo, anio, semana)
                                    DO UPDATE SET  asistencia='".$asistencia."', incidencia='".$incidencia."', opcion='".$opcion."', verificacion='".$verificacion."', niveldesea=".$niveldesea.", descrip='".$descrip."', fecmov = '".$fecha."', iduser =".$iduser;
*/
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

            }


            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }




 /*
    * realizar la transaccion de encargado de colmena para posteriormente aplicarse
    * en caja
    */
    public function update_asistencia($pag){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");

        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {

            $sqlUpdate= array();
            $nosql=0;
                $acreditadoid = $pag['acreditadoid'];                
                $idgrupo = $pag['idgrupo'];                
                $asistencia = $pag['asistencia'];
                $incidencia = $pag['incidencia'];
                $anio = $pag['anio'];
                $semana = $pag['semana'];

                $sql = "update col.asistencia set asistencia =".$asistencia.", incidencia =".$incidencia." where acreditadoid =".$acreditadoid." and idgrupo =".$idgrupo." and 
                     anio =".$anio." and semana =".$semana;
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }




    public function repsemana_add($data){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");



        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {

            $sqlUpdate= array();
            $nosql=0;

            $sql = "insert into col.info_semanal"." (idcolmena, anio, semana, mujeres, entiempo, participa, tema, imparte, asistencia, inci_fp, inci_ff, inci_f, verificaciones, sol_ingreso, ingreso_nuevo, reingreso, renuncias, mab_entrega, mab_pedido, iduser, created_at) 
                select '". $data['idcolmena']."','".$data['anio']."', '".$data['semana']."', '".$data['mujeres']."', '".$data['entiempo']."', '".$data['participa']."',
                '".$data['tema']."','".$data['imparte']."','".$data['asistencia']."','".$data['inci_fp']."','".$data['inci_ff']."','".$data['inci_f']."','".$data['verificaciones']."','
                ".$data['sol_ingreso']."','".$data['ingreso_nuevo']."','".$data['reingreso']."','".$data['renuncias']."','".$data['mab_entrega']."','".$data['mab_pedido']."','".$iduser."', '".$fecha."'";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;

    }


    
    public function repsemana_update($data){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");



        //Realiza el proceso hasta 3 veces en caso  de error en la trasnaccion
		for($i=0; $i<=2; $i++) {

            $sqlUpdate= array();
            $nosql=0;

            $sql = "update col.info_semanal 
                set mujeres ='".$data['mujeres']."', entiempo ='".$data['entiempo']."', participa = '".$data['participa']."',
                tema = '".$data['tema']."', imparte = '".$data['imparte']."', asistencia = '".$data['asistencia']."', inci_fp ='".$data['inci_fp']."', inci_ff ='".$data['inci_ff']."', inci_f ='".$data['inci_f']."', verificaciones = '".$data['verificaciones']."',
                sol_ingreso = '".$data['sol_ingreso']."', ingreso_nuevo = '".$data['ingreso_nuevo']."', reingreso = '".$data['reingreso']."', renuncias = '".$data['renuncias']."', mab_entrega ='".$data['mab_entrega']."', mab_pedido ='".$data['mab_pedido']."', iduser ='".$iduser."', created_at ='".$fecha."'
                where idcolmena =". $data['idcolmena']."  and anio = ".$data['anio']." and semana = ".$data['semana'];
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            $nosql++;
			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;

    }




 public function addAsisColmena($rec){
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");

		for($i=0; $i<=2; $i++) {
            $sqlUpdate= array();
            $nosql=0;
            $sql = "insert into col.colmenas_asistencia (idcolmena, anio, semana, fecmov, iduser) 
                select  '". $rec['idcolmena']."','".$rec['anio']."', '".$rec['semana']."', '".$fecha."','".$iduser."'";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                $response['code'] = 404;
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }





    public function asisColmena($data){
        $esquema = $this->session->userdata("esquema");
        $idsuc = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $fecha = date("Y-m-d H:i:s");

		for($i=0; $i<=2; $i++) {
            $sqlUpdate= array();
            $nosql=0;


            $sql = "update col.colmenas_asistencia  
                set mujeres ='".$data['mujeres']."', entiempo ='".$data['entiempo']."', participa = '".$data['participa']."',
                tema = '".$data['tema']."', imparte = '".$data['imparte']."', mab_entrega = '".$data['mab_entrega']."', mab_incidencia ='".$data['mab_incidencia']."', mab_pedido ='".$data['mab_pedido']."', notas ='".$data['notas']."'
                where idcolmena =". $data['idcolmena']."  and anio = ".$data['anio']." and semana = ".$data['semana'];

            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            if (array_key_exists('sol_ingreso', $data )) {
                if ($data['sol_ingreso'] !='') {
                    foreach ($data['sol_ingreso'] as $key => $value) {
                        if ($value !=''){
                            $query = "select * from col.colmenas_solingre where id =".$data['id']." and idpersona =".$value;
                            $rec = $this->base->querySelect($query, true);
                            if ($rec == []) {
/*                                     $sql = "insert into col.colmenas_solingre (id, idpersona, fecmov, iduser) 
                                     select '". $data['id']."','".$value."', '".$fecha."','".$iduser."'
                                    ON CONFLICT (id, idpersona)
                                    DO UPDATE SET fecmov = '".$fecha."', iduser =".$iduser;
 */                                 
                                $sql = "insert into col.colmenas_solingre (id, idpersona, fecmov, iduser) 
                                    select '". $data['id']."','".$value."', '".$fecha."','".$iduser."'";
                                $sqlUpdate[$nosql] = $sql;
                                 $nosql++;
                            }
                        }                
                    }
                }
            }


            if (array_key_exists('ingreso_nuevo', $data )) {
                if ($data['ingreso_nuevo'] !='') {
                    foreach ($data['ingreso_nuevo'] as $key => $value) {
                          if ($value !=''){

                             $query = "select * from col.colmenas_nuevoingre where id =".$data['id']." and idpersona =".$value;
                             $rec = $this->base->querySelect($query, true);
                             if ($rec == []) {
                                $sql = "insert into col.colmenas_nuevoingre (id, idpersona, fecmov, iduser) 
                                    select  '". $data['id']."','".$value."', '".$fecha."','".$iduser."'";
                                $sqlUpdate[$nosql] = $sql;
                                $nosql++;

                            }
                        }

                    }                
                }
            }


            if (array_key_exists('reingreso', $data )) {
                if ($data['reingreso'] !='') {
                    foreach ($data['reingreso'] as $key => $value) {
                        if ($value !=''){
                             $query = "select * from col.colmenas_reingre where id =".$data['id']." and idpersona =".$value;
                             $rec = $this->base->querySelect($query, true);
                             if ($rec == []) {
                                $sql = "insert into col.colmenas_reingre (id, idpersona, fecmov, iduser) 
                                    select '". $data['id']."','".$value."', '".$fecha."','".$iduser."'";
                                $sqlUpdate[$nosql] = $sql;
                                $nosql++;
                            }
                        }
                    }                
                }            
            }


			$response = $this->base->transaction($sqlUpdate, true);
			if ($response['code'] == 200) {
				break;
			}else{
                $response['code'] = 404;
                if ($response['err'] == 1) {
                    break;
                }
            }
		}
        return $response;
    }





}