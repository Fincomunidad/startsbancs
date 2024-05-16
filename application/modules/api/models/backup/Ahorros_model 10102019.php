<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ahorros_Model extends CI_Model {

  public $numero_cuenta;
  public $idacreditado;
  public $idsucursal;
  public $idproducto;
  public $fecha_alta;
  public $fecha_baja;
  public $idmoneda;
  public $idahorro;




//    public $esquema;
//    public $idsuc;
  
	function __construct(){
//		$this->load->model('Base_model','base');

//        $this->esquema = $this->session->userdata("esquema");
  //      $this->idsuc = $this->session->userdata('sucursal_id');

	}


   public function set_datos($data_cruda) {
        foreach ($data_cruda as $nombre_campo => $valor_campo ){
            if ( property_exists('Ahorros_model', $nombre_campo)){
                $this->$nombre_campo = $valor_campo;
            }
        }
        $this->idsucursal = $this->session->userdata('sucursal_id');
        $this->idproducto = '01';
        $this->idmoneda = 'MXN';
        $this->fecha_alta = date("Y-m-d H:i:s");

        return $this;
    }

    /*
    * realizar la inserción
    *Parametros
    *1.- Tabla 2.- datos  3-Si retorna sequence
    */
    public function transaction($datos){

      $esquema = $this->session->userdata('esquema');
      for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $fecha = date("Y-m-d H:i:s");
            $fecom =  date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;


            $aho = $datos;

            // en el caso que sea una cuenta de Ahorro Infantil 
            // Se agrega en la tabla de menores 
            if ($aho['idproducto'] == "03") {
                $aho['nombre1'] = trim(strtoupper($aho['nombre1']));
                $aho['nombre2']= trim(strtoupper($aho['nombre2']));
                $aho['apaterno'] = trim(strtoupper($aho['apaterno']));
                $aho['amaterno'] = trim(strtoupper($aho['amaterno']));
                $aho['curp'] = trim(strtoupper($aho['curp']));            
                $sql = "insert into public.menores"." (idsucursal,fechaalta,nombre1,nombre2,apaterno,amaterno,curp,idparentesco,fecha_mov,usuario) 
                    select '". $this->session->userdata('sucursal_id')."','".$fecha."','".$aho['nombre1']."','".$aho['nombre2']."','".$aho['apaterno']."','".$aho['amaterno']."',
                    '".$aho['curp']."','".$aho['idparentesco']."','".$fecha."','". $this->ion_auth->user()->row()->id."'
                    RETURNING idmenor";
                $sqlUpdate[$nosql] = $sql;
                $nosql++;

                $sql = "insert into ".$esquema."ahorros"." (numero_cuenta,idacreditado,idsucursal,idproducto,fecha_alta,idmoneda, idmenor) 
                select '".$aho['numero_cuenta']."','".$aho['idacreditado']."','".$this->session->userdata('sucursal_id')."','".$aho['idproducto']."','".$fecha."','MXN','?' 
                RETURNING idahorro";
                                
            }else {
                $sql = "insert into ".$esquema."ahorros"." (numero_cuenta,idacreditado,idsucursal,idproducto,fecha_alta,idmoneda) 
                select '".$aho['numero_cuenta']."','".$aho['idacreditado']."','".$this->session->userdata('sucursal_id')."','".$aho['idproducto']."','".$fecha."','MXN' 
                RETURNING idahorro";    
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
                      //evitará que se aplica varias veces el registro.
                      if ($response['err'] == 1) {
                        $message = $response['message_error'];     

                          break;
                      }
                  }
          }
        return $response;        


//      $response =  $this->base->insertRecord('fin.ahorros',$this, TRUE);
//      return $response;        
    }
	
	

    public function lockcuentas($idacreditado, $status, $user){
      $esquema = $this->session->userdata('esquema');
      for($i=0; $i<=2; $i++) {
            // buscar el numero de secuencia 
            $fecha = date("Y-m-d H:i:s");
            $fecom =  date("Y-m-d");
            $sqlUpdate= array();
            $nosql=0;

            $sql = "update public.acreditado set lock_cuenta ='".$status."', userid =".$user." where  (lock_cuenta <> cast(".$status." as boolean) or  lock_cuenta is null) and acreditadoid =". $idacreditado;
            $sqlUpdate[$nosql] = $sql;
            $nosql++;

            $response = $this->base->transaction($sqlUpdate, true);
            if ($response['code'] == 200) {
              break;
            }else{
                if ($response['err'] == 1) {
                  $response['message']  = 'Acreditada(o) ya se encuentra bloqueada/desbloqueada';     

                    break;
                }
            }
          }
        return $response;        

    }

	

}