<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Crediton_Model extends CI_Model {

//    public $idcredito;
    public $idacreditado;
    public $idgrupo;
    public $idsucursal;
    public $fecha;
    public $fecha_pago;
    public $proy_nombre;
    public $proy_descri;
    public $proy_lugar;
    public $idproducto;
    public $nivel;
    public $monto;
    public $idpagare;
    public $idchecklist;
    public $idejecutivo;
    public $fecha_aprov;
    public $usuario_aprov;
    public $proy_observa;
    public $fecha_mov;
    public $usuario;

    public $fecha_entrega_col;
    public $idaval1;
    public $idaval2;

    public $periodo;
    public $num_pagos;
    public $tasa;
    public $fecha_pago2;
    public $iva;
    

	function __construct(){
		//$this->load->model('Base_model','base');
	}

    public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Crediton_Model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }

  //      $this->idcredito = $this->idgrupo;
        $this->idaval1 =0;
        if ($this->idaval2 ===""){
            $this->idaval2 =0;
        }
        $this->idproducto = 10;      //Credito individual
        $this->idejecutivo = 1;

        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->fecha_mov = date("Y-m-d H:i:s");
        $this->usuario = $this->ion_auth->user()->row()->id;

        if ($this->periodo === "M"){
            $this->fecha_pago2 = $this->fecha_pago;
        }
        if ($this->periodo === "C"){
            $this->fecha_pago2 = $this->fecha_pago;
        }
		if ($this->idgrupo === ""){
			$this->idgrupo = 0;
		}

        $this->proy_nombre = strtoupper($this->proy_nombre);
        $this->proy_descri = strtoupper($this->proy_descri);
        $this->proy_lugar = strtoupper($this->proy_lugar);
        $this->proy_observa = strtoupper($this->proy_observa);
        
        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function insertar($datos){
        /*
		$response =  $this->base->insertRecord('fin.creditos',$this, TRUE);
        return $response;        
        */
        $response =  $this->base->insertRecordSequence($this->session->userdata("esquema").'creditos',$this, TRUE, $this->session->userdata("esquema").'seq_creditos');
        return $response;
    }


    public function update($datos, $where, $isarray){
		$response =  $this->base->updateRecord($this->session->userdata("esquema").'creditos',$datos, $where, $isarray);
        return $response;        
    }

    /*
    *
    */
    public function transaccion($mov){
        $esquema = $this->session->userdata("esquema");
        $idsucursal = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            $fecha = date("Y-m-d H:i:s");
            $sqlUpdate= array();
            $nosql=0;

            $credito = $mov;

            $sql = "update ".$this->esquema."creditos set fecha_dispersa ='".$fecha."', tipo_dispersa ='".$credito['movimiento']."' where idcredito =".$credito['idcredito']." and idsucursal='".$idsucursal."' and monto =".$credito['monto']." and fecha_dispersa is null and tipo_dispersa is null and  fecha_entrega is null";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            if ($credito['movimiento'] == "01") {
                $sql = "insert into ".$esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                            select  '". $credito['idahorro'] ."','".$fecha."', '".$idsucursal."','D','".$credito['monto']."','01','1',null,'".$credito['idcredito']."',null,null,'".$iduser."','06'
                            where (select count(idcredito) from ".$esquema."ahorros_mov where idahorro ='".$credito['idahorro']."' and idsucursal='".$idsucursal."' and idcredito =".$credito['idcredito']." 
                            and idmovimiento ='06') = 0";
            } else if ($credito['movimiento'] == "10") { 
                $sql = "insert into ".$this->esquema."creditos_cheq"." (idcredito, tipo_dispersa, idsucursal, idbancodet, cheque_ref, afavor, usuario) 
                        select '". $credito['idcredito'] ."','".$credito['movimiento']."', '".$idsucursal."','".$credito['idbancodet']."','".$credito['cheque_ref']."','".$credito['afavor']."','".$iduser."' 
                        where (select count(idcredito) from ".$esquema."ahorros_mov where idsucursal='".$idsucursal."' and idcredito='".$credito['idcredito']."'
                            and idmovimiento ='06') = 0";
            }
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
                if ($response['err'] == 1) {
                    $message ="Crédito ha sido aplicado, ya no existe para dispersión!";
                    if ($response['noquery'] ==2)  {
                        if ($credito['movimiento'] == "01") {
                            $message ="Cuenta ya ingreso a Caja o fue aplicado como cheque!";
                        }else {
                            $message ="Movimiento ya fue aplicado como efectivo, verifique por favor!";
                        }
                    }
                    $response['message'] = $message;
                    break;

                }

            }

		}
        return $response;        
    }



    public function transac_entrega($mov){
        $this->load->model('../../caja/models/Base_ca','base_caja');
        $esquema = $this->session->userdata("esquema");
        $idsucursal = $this->session->userdata('sucursal_id');
        $iduser = $this->ion_auth->user()->row()->id;
        $idcaja = $this->session->userdata('idcaja');
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
		for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $nomov = $this->base_caja->nextMovimiento();
            $fecha = date("Y-m-d H:i:s");
            $sqlUpdate= array();
            $nosql=0;

            $credito = $mov;

            $sql = "update ".$esquema."creditos set fecha_entrega ='".$fecha."' where idcredito =".$credito['idcredito']." and idsucursal='".$idsucursal."' and monto =".$credito['monto']." and not fecha_dispersa is null and tipo_dispersa='01' and fecha_entrega is null";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;


            $sql = "insert into ".$esquema."ahorros_mov"." (idahorro, fecha, idsucursal, movimiento, importe, idinstrumento, lugar, idbanco, idcredito, nomov, idcaja, usuario, idmovimiento) 
                        select  '". $credito['idahorrodis'] ."','".$fecha."', '".$idsucursal."','R','".$credito['monto']."','01','1',null,'".$credito['idcredito']."','".$nomov."','".$idcaja."','".$iduser."','07'
                        where (select count(idahorro) from ".$esquema."ahorros_mov where idahorro ='".$credito['idahorrodis']."' and idsucursal='".$idsucursal."' and idcredito='".$credito['idcredito']."'
                        and idmovimiento ='06' and movimiento ='D' and nomov is null) = 1";
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            $sql = "update ".$this->esquema."ahorros_mov set nomov ='".$nomov."' where idahorro ='".$credito['idahorrodis']."' and idsucursal='".$idsucursal."' and idcredito='".$credito['idcredito']."'
                    and idmovimiento ='06' and movimiento ='D' and nomov is null";
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
                if ($response['err'] == 1) {
                    $message ="Crédito ha sido aplicado, ya no existe para entrega!";
                    if ($response['noquery'] ==2)  {
                        $message ="Movimiento ya fue aplicado, verifique por favor!";
                    }
                    $response['message'] = $message;
                    break;
                }

            }

		}
        return $response;        
        
    }

 public function transacondona($mov){
        $esquema = $this->session->userdata("esquema");
        //Realiza el proceso hasta 3 veces en caso  de error en la transaccion
        for($i=0; $i<=2; $i++) {
            $sqlUpdate= array();
            $nosql=0;
            $credito = $mov;
            $sql = "update ".$this->esquema."amortizaciones set garantia=0  where idcredito=".$credito['idcredito']." and fecha_pago is null";
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
                if ($response['err'] == 1) {
                    $message ="Crédito ha sido condonado, ya no existe para amortizaciones!";
                    $response['message'] = $message;
                    break;
                }
            }
        }
        return $response;        
    }

}