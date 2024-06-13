<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class CarteraD1 extends BaseV1 {
	public function __construct()
{
		parent::__construct();
//		if(!$this->ion_auth->logged_in())
//		{
//			redirect('auth','refresh');
//		}
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
            $this->load->library('form_validation');		
//		$this->load->helper(array('form','template'));
            $this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');
	}


/*
* Si el check list esta completo
*/
      public function checklist_completo_get() {
            $idcredito = $this->uri->segment(4);
            $query = "SELECT l.iddocumento FROM ".$this->session->userdata('esquema')."credito_checklist as l JOIN public.checklistdoc as c ON l.idchecklist = c.idchecklist and l.iddocumento = c.iddocumento JOIN public.documentos as d ON c.iddocumento = d.iddocumento WHERE l.idcredito =".$idcredito." and c.estatus='1' and fecha is null ORDER BY iddocumento;";
            $checklist = $this->base->querySelect($query, FALSE);
            if ($checklist) {
                        $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"CheckList obtenido correctamente!",
                        "checklist"=> $checklist
                        );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"CheckList no encontrado!");
            }
            $this->returnData($data);            
 
      }
                  


/*
* Autorizar credito
*/
	public function aut_credito_put() {
            $idcredito = $this->uri->segment(4);
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');

            $auto = $datos['identify'];            
            $clave = $datos['password'];
            $blnValida = false;
            //$miSQL = $this->base->querySelect("SELECT id FROM security.users WHERE email ='".$auto."';", TRUE);
            $miSQL = $this->base->querySelect("SELECT u.id, g.group_id FROM security.users as u
                                                LEFT JOIN security.users_groups as g ON u.id = g.user_id
                                                WHERE email ='".$auto."' and g.group_id in (6,8) LIMIT 1;", TRUE);            
            if ($miSQL) {
                  $autoriza = $miSQL[0]['id'];
                  $blnValida = $this->ion_auth_model->hash_password_db($autoriza, $clave);
            }else{
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "message"=>"Usuario no existe o no esta facultado para autorizar!");
                  $this->returnData($data);
                  return;
            }
            if ($blnValida === TRUE){
                  $tabla = $this->session->userdata('esquema').'creditos';
                  $record = array('fecha_aprov' => date("Y-m-d H:i:s"),
                                          'usuario_aprov'=> $this->ion_auth->user()->row()->username
                                          );				
                  $where = array('idcredito'=>$idcredito);
                  $update = $this->base->updateRecord($tabla, $record, $where, 0);
                  $this->validaCode($update);
            }else{
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "message"=>"Usuario o contraseña incorrecta!");
                  $this->returnData($data);
            }
	}
	
	public function aut_crediton_put() {

		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');
		$idcredito = $datos['idcredito']; 

		$tabla = $this->session->userdata('esquema').'creditos';
		$record = array('fecha_aprov' => date("Y-m-d H:i:s"),
						'usuario_aprov'=> $this->ion_auth->user()->row()->username
						);				
		$where = array('idcredito'=>$idcredito);
		$update = $this->base->updateRecord($tabla, $record, $where, 0);
		$this->validaCode($update);
	}
/*
* Solicitud de credito  
*/

	public function add_credito_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $adicionDatos = array('idproducto' => 1, 'idejecutivo' => 1);
			
			// Formateo de fechas - 07-09-2023
            $fecha = explode("/", $valores['fecha_pago']);
            $valores['fecha_pago'] = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
            $fecha = explode("/", $valores['fecha_entrega_col']);
            $valores['fecha_entrega_col'] = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
            $fecha = explode("/", $valores['fecha']);
            $valores['fecha'] = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
			
			// Inserción en BD
			$this->insertData('credito', $valores, 'solicitud_credito_put',$adicionDatos);
	}

	public function add_credito_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();
            $valores['idproducto'] = 1;
            $valores['idejecutivo'] = $this->ion_auth->user()->row()->id;
            $fecha = explode("/", $valores['fecha_pago']); 
            $valores['fecha_pago'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
            $fecha = explode("/", $valores['fecha_entrega_col']); 
            $valores['fecha_entrega_col'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
            $fecha = explode("/", $valores['fecha']); 
            $valores['fecha'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;

			
            $where = array('idcredito' => $valores['idcredito']);
		$this->updateData('credito', $valores, 'solicitud_credito_put', $where, false);
            
	}
      
	public function add_crediton_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            if ($valores['periodo']==='M'){
                $valores['fecha_pago2']=$valores['fecha_pago'];
            }
            //print_r($valores);
            $this->insertData('Crediton', $valores, 'solicitud_crediton_put');
	}

	public function add_crediton_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();
            $valores['idproducto'] = 10;
            $valores['idejecutivo'] = 1;
            $where = array('idcredito' => $valores['idcredito']);
		$this->updateData('crediton', $valores, 'solicitud_crediton_put', $where, false);
            
	}

/*
* Solicitud  
*/
	public function solcreditoben_post() {
		$idpersona = $this->uri->segment(4);
		//Carga de los modelos 
		$this->load->model('persona_model','persona');
		$this->load->model('persona_ben_model','beneficiario');
		//obtencion de los campos del formulario enviado
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('solingresoben_put') == TRUE) {
			//genera la fecha_nac en base a rfc_ben
			$rfcben = $datos['rfc_ben'];
			$dat = substr($rfcben,8,2).'/'.substr($rfcben,6,2).'/'.substr($rfcben,4,2);
			$fec = date('Y/m/d',strtotime($dat));

			if (validateDate($fec,'Y/m/d') == true) {
				$fec_nac = date('Y-m-d',strtotime($dat));
			}else {
				$fec_nac = NULL;
			}

			//Inserta el beneficiario como persona
			$datosbene = array('fechaalta' => $datos['fechaalta'],
						'nombre1' => $datos['nombre1_ben'],
						'nombre2' => $datos['nombre2_ben'],
						'apaterno' => $datos['apaterno_ben'],
						'amaterno' => $datos['amaterno_ben'],
						'aliaspf' => $datos['aliaspf_ben'],
						'celular' => $datos['telefono_ben'],
						'rfc' => $rfcben,
						'fecha_nac' => $fec_nac
					);
			//Asigna valores en la clase de persona (beneficiario)		
			$beneficiario = $this->persona->set_datos($datosbene);
			// Inserta el beneficiario en la tabla persona
			$insertarben =  $this->persona->insertar($beneficiario);
			if ($insertarben['code'] == 200) {
				// asigna valores en el array para agregar a la tabla relacion de beneficiario-persona
				$datosbenetab = array('idpersona' => $idpersona,
						'fecha' => $datos['fechaalta'],
						'idbeneficiario' => $insertarben['insert_id'],
						'idparentesco' => $datos['idparentesco'],
						'porcentaje' => $datos['porcentaje']
						);
				//Asigna valores en la clase		
				$beneficiariotab = $this->beneficiario->set_datos($datosbenetab);
			// Inserta el beneficiario en la tabla persona_ben 
				$insertarbentab =  $this->beneficiario->insertar($beneficiariotab);
				$this->validaCode($insertarbentab);
			}else {
				$this->validaCode($insertarben);
			}
		} else {
			$this->validaForm();
		}
	}


      /*
            2018-08-20 Validacion de creditos del promotor
      */
	public function get_solicitud_credito_get() {
            $idcredito = $this->uri->segment(4);
            
            $idsucursal = $this->session->userdata('sucursal_id');
		
            $fields = array("idcredito", "idsucursal", "fecha", "idpagare", "acreditadoid", "direccion", "edocivil", "edocivil_nombre", "idactividad", "actividad_nombre", "idcolmena", "nomcolmena", "idgrupo", "nomgrupo", "nivel", "monto", "proy_nombre", "proy_descri", "proy_lugar", "proy_observa", "idchecklist", "fecha_aprov", "usuario_aprov", "fecha_pago", "periodo", "num_pagos","tasa", "fecha_pago2", "idaval1", "idaval2", "fecha_entrega_col", "idpromotor","empresa","idnivel");
            $where = array("idcredito"=>$idcredito);
            $solcredito = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
            if ($solcredito) {
                  //if ( $solcredito[0]['idpromotor'] === $this->ion_auth->user()->row()->id){
                        $idacreditado = $solcredito[0]['acreditadoid'];

                        if ($this->session->userdata('esquema')==="ama."){
                              $query = "Select count(idcredito) as total FROM ama.v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull and iddocumento <>6";
                        }else{
                              $query = "Select count(idcredito) as total FROM ".$this->esquema."v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull";
                        }
                        $checklist = $this->base->querySelect($query, TRUE);

                        $fields = array("numero", "fecha_vence", "capital", "interes", "iva", "aportesol", "garantia", "(garantia + total) as total ", "(capital+interes+iva-aportesol) as captotal");
                        $where = array("idcredito"=>$idcredito);
                        //$this->session->userdata('esquema').
                        $order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
                        $amor = $this->base->selectRecord($this->esquema."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);

                        $cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                              FROM ".$this->esquema."get_acreditado_solicitud a
                              JOIN ".$this->esquema."get_colmena_aval as v ON a.idcolmena=v.idcolmena
                              JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                              WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
                        $cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                              FROM ".$this->esquema."get_acreditado_solicitud a
                              JOIN ".$this->esquema."get_grupo_aval as v ON a.idgrupo=v.idgrupo
                              JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                              WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);            
                        if($cataval1){
                        }else{
                              $cataval1=[];
                        }
                        if($cataval2){
                        }else{
                              $cataval2=[];
                        }
                        array_push($cataval1, array("value"=>0,"name"=>"No asignado"));
                        array_push($cataval2, array("value"=>0,"name"=>"No asignado"));

                        if ($solcredito[0]['idsucursal']===$idsucursal){
                              $data = array("status"=>"OK",
                                    "code" => 200,
                                    "message"=>"Solicitud obtenida correctamente!",
                                    "solcredito"=> $solcredito,
                                    "amortizaciones" => $amor,
                                    "checklist" => $checklist,
                                    "cataval1" => $cataval1,
                                    "cataval2" => $cataval2
                              );
                        }else{
                              $data = array("status"=>"ERROR",
                              "code" => 404,
                              "message"=>"Se ha cancelado la consulta. La solicitud está asignada a otra sucursal (".$solcredito[0]['idsucursal'].") !");
                        }
						/*
                  }else{
                        $data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Se ha cancelado la consulta. La solicitud está asignada a otro usuario (".$solcredito[0]['idpromotor'].") !");
                  }
				  */
            }else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

	public function get_solicitud_credito_ind_get() {
            $idcredito = $this->uri->segment(4);

            $idsucursal = $this->session->userdata('sucursal_id');
            $idproducto=10;
            
            $fields = array("idcredito", "idsucursal", "fecha", "idpagare", "acreditadoid", "direccion", "edocivil", "edocivil_nombre", "idactividad", "actividad_nombre", "idcolmena", "nomcolmena", "idgrupo", "nomgrupo", "nivel", "monto", "proy_nombre", "proy_descri", "proy_lugar", "proy_observa", "idchecklist", "fecha_aprov", "usuario_aprov", "fecha_pago", "periodo", "num_pagos","tasa", "fecha_pago2", "idaval1", "idaval2", "fecha_entrega_col", "iva");
            $where = array("idproducto"=>$idproducto, "idcredito"=>$idcredito);
            //$where = array("idcredito"=>$idcredito);
            $solcredito = $this->base->selectRecord($this->esquema."get_solicitud_credito_ind", $fields, "", $where, "","", "", "", "","", TRUE);
            if ($solcredito) {
                  $idacreditado = $solcredito[0]['acreditadoid'];
            }else{
                  $idacreditado=0;
            }
            
			//Para ama no se requiere el documento 6
            if ($this->session->userdata('esquema')==="ama."){
                  $query = "Select count(idcredito) as total FROM ama.v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull and iddocumento <>6";
            }else{
                  $query = "Select count(idcredito) as total FROM ".$this->esquema."v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull";
            }
            $checklist = $this->base->querySelect($query, TRUE);

		$fields = array("numero", "fecha_vence", "capital", "interes", "iva", "aportesol", "garantia", "(garantia + total) as total ");
		$where = array("idcredito"=>$idcredito);
		//$this->session->userdata('esquema').
            $order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->esquema."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);

		$cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
		$cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);            
            if($cataval1){
            }else{
                  $cataval1=[];
            }
            if($cataval2){
            }else{
                  $cataval2=[];
            }
            array_push($cataval1, array("value"=>0,"name"=>"No asignado"));
            array_push($cataval2, array("value"=>0,"name"=>"No asignado"));

            if ($solcredito) {
                  if ($solcredito[0]['idsucursal']===$idsucursal){
                        $data = array("status"=>"OK",
                              "code" => 200,
                              "message"=>"Solicitud individual obtenida correctamente!",
                              "solcredito"=> $solcredito,
                              "amortizaciones" => $amor,
                              "checklist" => $checklist,
                              "cataval1" => $cataval1,
                              "cataval2" => $cataval2
                        );
                  }else{
                        $data = array("status"=>"ERROR",
				"code" => 404,
                        "message"=>"Se ha cancelado la consulta. La solicitud está asignada a otra sucursal (".$solcredito[0]['idsucursal'].") !",
                        "solcredito"=> $solcredito,
                        "amortizaciones" => $amor,
                        "checklist" => $checklist,
                        "cataval1" => $cataval1,
                        "cataval2" => $cataval2
                  );
                  }
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud individual no encontrada!");
		}
		$this->returnData($data);
	}

      public function update_acreditado_grupo2_put(){
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');
            
            $acreditadoid = $valores[0]['idacreditado'];
            $idgrupo = $valores[0]['idgrupo'];
            $idcolcargo = $valores[0]['idcolcargo'];
            $idgpocargo = $valores[0]['idgrupocargo'];
            $orden = $valores[0]['idgrupoorden'];

            //$idacreditado = $this->uri->segment(4);	
            /*
		if (!is_numeric($idacreditado)){
		   $this->returnCode();
		}else {
                  */
			$esquema = $this->esquema;       
			$query = "Select ".$this->esquema."update_acreditado_grupo(".$acreditadoid.",".$idgrupo.",".$idcolcargo.",".$idgpocargo.",".$orden.")";
			$regresa = $this->base->querySelect($query, FALSE);
			$this->validaCode($regresa);
		//}            
      }

	public function update_acreditado_grupo_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');
		$acreditadoid = $datos['idacreditado'];
            $idgrupo = $datos['idgrupo'];
            $idcolcargo = $datos['idcolcargo'];
            $orden = $datos['idgrupoorden'];

            /*
            $idcargo1=0;
            $idcargo2=0;            
            if ($idcolcargo!=0){

                  $tabla = 'col.grupo_cargo';
                  switch ($idcolcargo) {
                        case 3:
                              $record = array('idcargo2' => $acreditadoid);			
                              break;
                        default:
                              $record = array('idcargo1' => $acreditadoid);				
                              break;
                  }
                  $record = array('idcargo2' => $acreditadoid);			
                  $where = array('idgrupo' => $idgrupo, 'fecha'=>date("d-m-Y"));
                  $update = $this->base->updateRecord($tabla, $record, $where, 0);

                  
                  //$query = "SELECT count(idgrupo) as total FROM col.grupo_cargo WHERE fecha=current_date and idgrupo=".$idgrupo;
                  $query = "SELECT idgrupo FROM col.grupo_cargo WHERE fecha=current_date and idgrupo=".$idgrupo;
                  $acredita = $this->base->querySelect($query, FALSE);


                  switch ($idcolcargo) {
                        case 3:
                              $idcargo1 = $acreditadoid;
                              break;
                        default:
                              $idcargo2 = $acreditadoid;
                              break;
                  }

                  if ($acredita) {
                        $tabla = 'col.grupo_cargo';
                        switch ($idcolcargo) {
                              case 3:
                                    $record = array('idcargo2' => $acreditadoid);			
                                    break;
                              default:
                                    $record = array('idcargo1' => $acreditadoid);				
                                    break;
                        }
                        $record = array('idcargo2' => $acreditadoid);			
                        $where = array('idgrupo' => $idgrupo, 'fecha'=>date("d-m-Y"));
                        $update = $this->base->updateRecord($tabla, $record, $where, 0);
                  } else {
                        //Inserta el beneficiario como persona
                        $user = $this->ion_auth->user()->row()->username;
                        $newcargo = array('idgrupo' => $idgrupo,
                                          'idcargo1' => $idcargo1,
                                          'idcargo2' => $idcargo2,
                                          'usuario' => $user
                                    );
                        $this->insertData('col.grupo_cargo', $newcargo, 'grupo_cargo_put',"");                        
                  }        
                   
            }
            
            $user = $this->ion_auth->user()->row()->username;
            $newcargo = array('idgrupo' => $idgrupo,
                              'idcargo1' => $idcargo1,
                              'idcargo2' => $idcargo2,
                              'usuario' => $user
                        );
            $this->insertData('Grupo_cargo', $newcargo, 'grupo_cargo_put',"");     
            */
            
            
		$tabla = 'public.acreditado';
		$record = array('idgrupo' => $idgrupo, 'orden'=>$orden);				
		$where = array('acreditadoid' => $acreditadoid);
            $update = $this->base->updateRecord($tabla, $record, $where, 0);
            $this->validaCode($update);
            
	}

	public function delete_acreditado_grupo_put() {
            $acreditadoid = $this->uri->segment(4);

            $tabla = 'public.acreditado';
		$record = array('idgrupo' => 999, 'orden'=>0);
		$where = array('acreditadoid' => $acreditadoid);
            $update = $this->base->updateRecord($tabla, $record, $where, 0);
            $this->validaCode($update);
            
	}
      
      /*
      Llamado al actualizar el grupo en asignación
      */
      public function get_onlyacreditadosbygrupo_get() {
		$idgrupo = $this->uri->segment(4);
		
            $fields = array("idgrupo", "nombre", "idacreditado", "acreditadoid", "idanterior", "orden", "cargo_colmena", "cargo_grupo");
            $where = array("idgrupo"=>$idgrupo);
            $order_by = array(array('campo'=> 'orden', 'direccion'=>	'asc'));
            $grupo_acreditados = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            if ($grupo_acreditados===false){
                  $grupo_acreditados=[];
            }
            if ($grupo_acreditados) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
                        "grupo_acreditados"=> $grupo_acreditados,
                        "grupo_tesoreros"=> $grupo_acreditados,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}


      /*
      Llamado al seleccionar la colmena
      2019-09-30
      */
      public function get_acreditadosbycolmena_get() {
		$idcolmena = $this->uri->segment(4);
            //$fields = array("idcolmena", "idgrupo", "grupo_numero","grupo_nombre", "nombre", "idacreditado", "acreditadoid", "idanterior");
            $fields = array("grupo_nombre","idgrupo", "nombre", "idacreditado", "acreditadoid", "idanterior", "orden", "cargo_colmena", "cargo_grupo");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'grupo_nombre', 'direccion'=>	'asc'),
            			array('campo'=> 'orden', 'direccion'=>	'asc'));
            $grupo_acreditados = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);


            $fields = array("dia_text", "promotor", "horainicio");
            $where = array("idcolmena"=>$idcolmena);
            $colmena_info = $this->base->selectRecord("col.v_colmenas_directorio", $fields, "", $where, "","", "", "", "","", TRUE);
            
            if ($grupo_acreditados===false){
                  $grupo_acreditados=[];
            }
            if ($colmena_info) {
                  $colmena_info = $colmena_info[0];
                  $data = array("status"=>"OK",
                  "code" => 200,
                  "message"=>"Solicitud obtenida correctamente!",
                  "colmena_info"=> $colmena_info,
                  "grupo_acreditados"=> $grupo_acreditados
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}


      /*
      Llamado al seleccionar el grupo en asignación
      */
      public function get_acreditadosbygrupo_get() {
		$idgrupo = $this->uri->segment(4);
		
            //$fields = array("idcolmena", "idgrupo", "grupo_numero","grupo_nombre", "nombre", "idacreditado", "acreditadoid", "idanterior");
            $fields = array("idgrupo", "nombre", "idacreditado", "acreditadoid", "idanterior", "orden", "cargo_colmena", "cargo_grupo");
            $where = array("idgrupo"=>$idgrupo);
            $order_by = array(array('campo'=> 'orden', 'direccion'=>	'asc'));
            $grupo_acreditados = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            if ($grupo_acreditados===false){
                  $grupo_acreditados=[];
            }

            $fields = array("acreditadoid as value", "idacreditado || ' - ' || nombre as name",);
            $where = array("idgrupo"=>NULL);
            $order_by = array(array('campo'=> 'nombre', 'direccion'=>	'asc'));
            $cat_noasigna = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);


            if ($cat_noasigna) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
                        "cat_noasigna"=>$cat_noasigna,
                        "grupo_acreditados"=> $grupo_acreditados
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}
      
      /*
      Llamado al seleccionar la colmena en asignacion
      */
	public function get_grupo_acreditados_get() {
		$idcolmena = $this->uri->segment(4);

            $fields = array("idcargo as value", "descripcion as name");
            $order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
            $cat_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE);
            
            $fields = array("acreditadoid as value", "idacreditado || ' - ' || nombre as name");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'grupo_numero', 'direccion'=>	'asc'));
            $cat_asigna = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);

            $fields = array("idgrupo as value", "numero || ' - ' || nombre as name");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'idgrupo', 'direccion'=>	'asc'));
            $cat_grupos = $this->base->selectRecord("col.grupos", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            
            if ($cat_grupos) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
                        "cat_grupos"=>$cat_grupos,
                        "cat_asigna"=>$cat_asigna,
                        "cat_cargos"=> $cat_cargos
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

      //2019-08-15
	public function get_acreditado_get() {
		$idacreditado = $this->uri->segment(4);

		$where = array("acreditadoid" => $idacreditado);
		//$solcre = $this->base->selectRecord("public.personas", "", "", $where, "","", "", "", "","", TRUE);
            $acreditado = $this->base->selectRecord($this->esquema.'get_acreditado_solicitud', 
                  'idacreditado, nombre, idsucursal, edocivil, edocivil_nombre, idactividad, actividad_nombre, direccion, idgrupo, grupo_numero, grupo_nombre, col_numero, col_nombre', "", $where, "","", "", "", "","", TRUE);
            
            $query = "Select idcredito, idpagare FROM ".$this->esquema."get_solicitud_credito where acreditadoid=".$idacreditado." and fecha_aprov is null";
            $historial = $this->base->querySelect($query, TRUE);

            if ($historial){
                  $nota= "SOCIA CON CREDITO SIN AUTORIZAR: ".$historial[0]['idcredito'];
            }else{
				if ($acreditado[0]['col_numero']==""){
					$nota= "LA SOCIA NO ESTA ASIGNADA A COLMENA";
				}else{
					$nota= "";
				}				
            }			
            
            $cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
            $cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);            

            if($cataval1){
            }else{
                  $cataval1=[];
            }
            if($cataval2){
            }else{
                  $cataval2=[];
            }

            array_push($cataval1, array("value"=>0,"name"=>"No asignado"));
            array_push($cataval2, array("value"=>0,"name"=>"No asignado"));

			
            //Ultimo            
            $strMensaje = "";
            
            $sancion=null;
            $ultimo = $this->base->querySelect("SELECT idcredito as id_credito, fecha_dispersa FROM ".$this->session->userdata('esquema')."creditos WHERE idacreditado=".$idacreditado." and not fecha_dispersa is null and not fecha_entrega is null ORDER BY fecha_entrega DESC limit 1", TRUE);
            if ($ultimo) {
                  $micredito = $ultimo[0]['id_credito'];
                  $sancion = $this->base->querySelect("SELECT fecha::varchar, coalesce(sancion,'') as descri from ".$this->session->userdata('esquema')."get_califica_credito(".$micredito.") ORDER BY fecha DESC LIMIT 1;", TRUE);
                  if ($sancion){
                        $strMensaje = $sancion[0]['fecha']." ".$sancion[0]['descri'];
                  }
                  //Si hay registros obtener el ultimo y verificar que este terminado

            }else{
                  $strMensaje ="Sin creditos previos.";
            }
            
			
            
            /*
            $pago = $this->base->querySelect("SELECT * FROM col.get_credito_pagado(".$idacreditado.")", TRUE);
            $pagado = $pago.pagado;
            */

            //$sancion = $pago[0];
            if ($acreditado) {
                  $data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Acreditado obtenido correctamente! ".$strMensaje,
                        "acreditado"=> $acreditado,
                        "cataval1"=> $cataval1,
                        "cataval2"=> $cataval2,
                        "sancion"=>$strMensaje,
						"msgaviso"=>$nota
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Acreditado no encontrado!");
            }
            $this->returnData($data);
            

      }

	public function get_acreditado_gar_get() {
		$idacreditado = $this->uri->segment(4);

		$where = array("idacreditado" => $idacreditado);
		//$solcre = $this->base->selectRecord("public.personas", "", "", $where, "","", "", "", "","", TRUE);
            $acreditado = $this->base->selectRecord($this->esquema.'get_acreditado_solicitud', 'idacreditado, nombre, idsucursal, edocivil, edocivil_nombre, idactividad, actividad_nombre, direccion, idgrupo, grupo_numero, grupo_nombre, col_numero, col_nombre',"",$where, "","", "", "", "","", TRUE);
            
            
            $cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.idacreditado =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
            $cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM ".$this->esquema."get_acreditado_solicitud a
                  JOIN ".$this->esquema."get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN ".$this->esquema."get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.idacreditado =".$idacreditado." ORDER BY v.idcargo;", TRUE);            

            if($cataval1){
            }else{
                  $cataval1=[];
            }
            if($cataval2){
            }else{
                  $cataval2=[];
            }

            array_push($cataval1, array("value"=>0,"name"=>"No asignado"));
            array_push($cataval2, array("value"=>0,"name"=>"No asignado"));

            if ($acreditado) {
                  $data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Acreditado obtenido correctamente!",
                        "acreditado"=> $acreditado,
                        "cataval1"=> $cataval1,
                        "cataval2"=> $cataval2,
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Acreditado no encontrado!");
            }
            $this->returnData($data);
	}
	
	
	public function get_acreditado_pagares_get() {
		$idacreditado = $this->uri->segment(4);

		$where = array("idacreditado" => $idacreditado);
		$cat_acreditado = $this->base->selectRecord("get_acreditados","acreditado as nombre_acreditado, idpersona, idsucursal, curp, idacreditado, acreditadoid, idgrupo", "", $where, "", "", "", "", "", "", TRUE);

		$where = array("idacreditado" => $idacreditado);
		$order_by = array(array('campo'=> 'idcredito', 'direccion'=>'desc'));
		$catpagare = $this->base->selectRecord($this->esquema."get_creditos_acreditado","idcredito as value, idpagare as name", "", $where, "", "", "", $order_by, "", "", TRUE);
		if ($cat_acreditado) {
                  $cat_acreditado = $cat_acreditado[0];                  
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Acreditado obtenido correctamente!",
                        "cat_acreditado" => $cat_acreditado,
                        "catpagare"=> $catpagare
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Acreditado no encontrado!");
            }
            $this->returnData($data);
      }

	public function solicitudcredito_get() {
		$idacreditado = $this->uri->segment(4);

		$where = array("idacreditado" => $idacreditado);
		$acreditado = $this->base->selectRecord($this->esquema.'get_acreditado_solicitud', 'idacreditado, nombre, idsucursal, edocivil, idactividad, direccion',"",$where, "","", "", "", "","", TRUE);
		if ($acreditado) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Acreditado obtenido correctamente!",
				"acreditado"=> $acreditado
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Acreditado no encontrado!");
		}
		$this->returnData($data);
	}



      /*
      * Busqueda de acreditados con creditos  
      */
	public function find_acreditados_get(){
            $nombre = strtoupper($_GET["q"]);
            $query = "SELECT c.idsucursal, c.idcredito, c.idgrupo, c.nivel, c.idpagare, upper(a.acreditado) as nombre, a.rfc, a.idacreditado, a.acreditadoid
                        FROM ".$this->esquema."creditos as c JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid WHERE idproducto<>10 and upper(a.acreditado) like '%".$nombre."%' ORDER BY c.idcredito desc limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}

      /*
      * Busqueda de acreditados con creditos  
      */
	public function find_acreditadosInd_get(){
            $nombre = strtoupper($_GET["q"]);
            $query = "SELECT c.idsucursal, c.idcredito, c.idgrupo, c.nivel, c.idpagare, upper(a.acreditado) as nombre, a.rfc, a.idacreditado, a.acreditadoid
                        FROM ".$this->esquema."creditos as c JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid WHERE c.idproducto=10 and upper(a.acreditado) like '%".$nombre."%' ORDER BY c.idcredito desc limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}



      //2019-08-14
	public function get_colmenas_get() {

            $catHoras =  array( array('value'=> '8', 'name'=>'08') );
            for ($i = 8; $i <= 20; $i++) {
                  array_push($catHoras, array('value'=> $i, 'name'=>str_pad((int) $i, 2, "0" , STR_PAD_LEFT)));
            }            

            $catMinutos =  array( array('value'=> '0', 'name'=>'00') );
            for ($i = 1; $i <= 59; $i++) {
                  //array_push($catMinutos, array('value'=> $i, 'name'=>$i));
                  array_push($catMinutos, array('value'=> $i, 'name'=>str_pad((int) $i, 2, "0" , STR_PAD_LEFT)));
                  
            }           
            
            $catMAP =  array(
                  array('value'=> '0', 'name'=>'NO'),
                  array('value'=> '1', 'name'=>'SI')
            );            

            $catDias =  array(
                  array('value'=> '1', 'name'=>'Lunes'),
                  array('value'=> '2', 'name'=>'Martes'),
                  array('value'=> '3', 'name'=>'Miercoles'),
                  array('value'=> '4', 'name'=>'Jueves'),
                  array('value'=> '5', 'name'=>'Viernes'),
                  array('value'=> '6', 'name'=>'Sabado')
            );

            $cat_grupo_orden =  array(
                  array('value'=> '1', 'name'=>'1'),
                  array('value'=> '2', 'name'=>'2'),
                  array('value'=> '3', 'name'=>'3'),
                  array('value'=> '4', 'name'=>'4'),
                  array('value'=> '5', 'name'=>'5')
            );

		$group_by = array("c_mnpio","d_mnpio");
		$mpio = $this->base->selectRecord("localidades","c_mnpio as value, d_mnpio as name", "", "", "", "",  $group_by,"", "", "", TRUE);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,2,4));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_col_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,3));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_grupo_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);


		$order_by = array(array('campo'=> 'first_name', 'direccion'=>	'asc'));
		$catpromotor = $this->base->selectRecord("security.users","id as value, (first_name || ' ' || last_name) as name", "", "", "", "", "",$order_by, "", "",TRUE);

		$catempresa =  array(
			  array('value'=> 'F', 'name'=>'Fincomunidad'),
			  array('value'=> 'B', 'name'=>'Bancomunidad'),
			  array('value'=> 'A', 'name'=>'AMA'),
			  array('value'=> 'I', 'name'=>'Impulso')            
		);

		$catSucursal = array(
			  array('value'=> '01', 'name'=>'Zimatlán'),
			  array('value'=> '02', 'name'=>'Oaxaca'),
		); 

		$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", "", "", "", "","", "", "",TRUE);
		if ($catcolmenas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "catcolmenas" => $catcolmenas,
                              "cat_grupo_orden"=> $cat_grupo_orden,
                              "cat_col_cargos"=>$cat_col_cargos,
                              "cat_grupo_cargos"=>$cat_grupo_cargos,
                              "catpromotor"=>$catpromotor,
                              "catempresa"=>$catempresa,
                              "mpio"=>$mpio,
                              "catDias"=>$catDias,
                              "catHoras"=>$catHoras,
                              "catMinutos"=>$catMinutos,
                              "catMAP"=>$catMAP,
							  "catsucursal"=>$catSucursal,							  
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas,
                        "cat_grupo_orden"=> $cat_grupo_orden,
                        "cat_col_cargos"=>$cat_col_cargos,
                        "cat_grupo_cargos"=>$cat_grupo_cargos,
                        "catpromotor"=>$catpromotor,
                        "catempresa"=>$catempresa,
                        "mpio"=>$mpio,
                        "catDias"=>$catDias
            );
		}
		$this->returnData($data);
      }
            
      /*
      *     Obtener el catálogo de grupos
      */
      public function get_acreditado_colmena_grupo_get(){
		$idacreditado = $this->uri->segment(4);
            //$where = array('acreditadoid' => $valor);
		$acreditado = $this->base->querySelect("SELECT a.acreditadoid, g.idcolmena, a.idgrupo, a.acreditado as nombre_socio, a.orden, g.colmena_nombre, g.grupo_nombre,
                  ".$this->esquema."get_cargo_colmena(a.acreditadoid::int) AS cargo_colmena, ".$this->esquema."get_cargo_grupo(a.acreditadoid::int) AS cargo_grupo
                  FROM public.get_acreditados as a
                        LEFT JOIN ".$this->esquema."get_colmena_grupo as g ON a.idgrupo=g.idgrupo
                  WHERE idacreditado=".$idacreditado, TRUE);                   
            if ($acreditado) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Solicitud obtenida correctamente!",
                        "acreditado"=> $acreditado
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Acreditada no encontrada!");
		}
		$this->returnData($data);
	}


      /*
      * Cambio de promotor
      */
	public function set_colmena_promotor_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
            //print_r($valores);           
            //die;
            $idcolmena = $valores['idcolmena'];
            $idpromotor = $valores['idpromotor'];
            $empresa = $valores['idempresa'];

            $esquema = $this->esquema;       
            $query = "Select col.set_colmena_promotor(".$idcolmena.",".$idpromotor.",'".$empresa."')";
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

	}

      /*
      *     Obtener el promotor
      */
      public function get_colmena_promotor_get(){
		$valor = $this->uri->segment(4);
		$where = array('idcolmena' => $valor);
            $this->selectData('col.colmenas', "idcolmena, idpromotor, empresa", "", $where,"", "" ,"", "", "", "", FALSE);
	}      

      /*
      *     Obtener el catálogo de grupos
      */
      public function get_colmena_grupo_get(){
		$valor = $this->uri->segment(4);
		$where = array('idcolmena' => $valor);
            $this->selectData('col.grupos', "idgrupo as value, numero as name", "", $where,"", "" ,"", "", "", "", FALSE);
	}

      public function get_colmena_grupo_orden_get(){
            $cat_grupo_orden =  array(
                  array('value'=> '1', 'name'=>'1'),
                  array('value'=> '2', 'name'=>'2'),
                  array('value'=> '3', 'name'=>'3'),
                  array('value'=> '4', 'name'=>'4'),
                  array('value'=> '5', 'name'=>'5')
            );
            $this->$cat_grupo_orden;
	}

      /*
      * Cambio de grupo y/o cargo del acreditado y modificacion en los créditos vigentes
      */
	public function set_acreditado_change_grupo_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');

            //print_r($valores);           
            //die;
            $acreditadoid = $valores['idacreditado'];
            $acreditado = $this->base->querySelect("SELECT acreditadoid FROM public.acreditado WHERE idacreditado=".$acreditadoid, TRUE);
            //print_r($acreditado[0]['acreditadoid']);           
            //die;
            
            $acreditadoid = $acreditado[0]['acreditadoid'];
            $idgrupo = $valores['idgrupo'];
            $idcolcargo = $valores['idcol_cargo'];
            $idgpocargo = $valores['idgrupo_cargo'];
            $orden = $valores['grupo_orden'];

            $esquema = $this->esquema;       
            $query = "Select ".$this->esquema."set_acreditado_grupo(".$acreditadoid.",".$idgrupo.",".$idcolcargo.",".$idgpocargo.",".$orden.")";
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

	}

      public function get_provision_ahorro_get(){
            $fecha = $this->uri->segment(4);

            /*
            print_r($fecha);
            die;
            
            $prov_ahorro = $this->base->querySelect("SELECT s.idacreditado, s.acreditado, a.numero_cuenta, i.base, i.interes, i.idpoliza
                  FROM ".$this->esquema."ahorros_int as i
                        JOIN ".$this->esquema."ahorros as a ON i.idahorro=a.idahorro
                        JOIN get_acreditados as s ON a.idacreditado=s.acreditadoid
                  WHERE fecha=".$fecha." ORDER BY s.acreditado;", TRUE);
            */
		$fields = array("idacreditado", "idsucursal", "acreditado",  "numero_cuenta", "fecha", "base", "interes", "idpoliza");
		$where = array("fecha"=>$fecha);
		$order_by = array(array('campo'=> 'acreditado', 'direccion'=>	'asc'));
		$prov_ahorro = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_ahorro", $fields, "", $where, "","", "", $order_by, "","", TRUE);

        $fields = array("acreditadoid", "idacreditado", "idsucursal", "acreditado", "idcredito", "dia", "pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "pago_num", "factor", "int_acu", "iva_acu", "int_pag", "iva_pag");
		$where = array("dia"=>$fecha);
		$order_by = array(array('campo'=> 'acreditado', 'direccion'=>'asc'));
		$prov_credito = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_credito", $fields, "", $where, "","", "", $order_by, "","", TRUE);

            //print_r($prov_ahorro);
            //die;
            
		if ($prov_ahorro) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "prov_ahorro" => $prov_ahorro,
                              "prov_credito"=> $prov_credito
				);
		} else {
			if ($this->session->userdata('esquema') === "imp."){
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "prov_ahorro" => [],
                              "prov_credito"=> $prov_credito
				);				
				
				
			}else {
				$data = array("status"=>"ERROR",
					"code" => 404,
					"message"=>"Error al tratar de consultar",
							"prov_ahorro" => $prov_ahorro,
							"prov_credito"=> $prov_credito
					  );
				
			}
		}
		$this->returnData($data);

            

            
      }

      public function get_provision_ahorro_acre_get(){
            $idacreditado = $this->uri->segment(4);
            $idcredito = $this->uri->segment(5);
            /*
            print_r("Hola ".$idacreditado." - ".$idcredito);
            die;
            */

		$fields = array("idacreditado", "idsucursal", "acreditado",  "numero_cuenta", "fecha", "base", "interes", "idpoliza");
		$where = array("idacreditado"=>$idacreditado);
		$order_by = array(array('campo'=> 'fecha', 'direccion'=>	'asc'));
		$prov_ahorro = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_ahorro", $fields, "", $where, "","", "", $order_by, "","", TRUE);

            $fields = array("acreditadoid", "idacreditado", "idsucursal", "acreditado", "idcredito", "dia", "pago_total", "pago_capital", "capital_saldo", "saldo_actual", "interes", "iva", "pago_num", "factor", "int_acu", "iva_acu", "int_pag", "iva_pag");
		$where = array("idcredito"=>$idcredito);
		$order_by = array(array('campo'=> 'dia', 'direccion'=>'asc'));
		$prov_credito = $this->base->selectRecord($this->session->userdata('esquema')."v_provision_credito", $fields, "", $where, "","", "", $order_by, "","", TRUE);

		if ($prov_credito) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "prov_ahorro" => $prov_ahorro,
                              "prov_credito"=> $prov_credito
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
                        "prov_ahorro" => $prov_ahorro,
                        "prov_credito"=> $prov_credito
                  );
		}
		$this->returnData($data);
      }


      public function get_acreditados_data_get(){
            $fields = array("idacreditado", "id_isis", "nombre", "persona", "estado_civil", "fecha_nac", "edad", "sexo", "cp", "localidad", "municipio", "aportacion_social", "actividad", "dependientes", "tipovivienda", "aguapot", "enerelec", "drenaje","telefono");
		$order_by = array(array('campo'=> 'idacreditado', 'direccion'=>	'asc'));
		$acre_datos = $this->base->selectRecord("get_acreditados_estadistica", $fields, "", "", "","", "", $order_by, "","", TRUE);

            //print_r($prov_ahorro);
            //die;
            
		if ($acre_datos) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "acre_datos" => $acre_datos
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
                        "acre_datos" => $acre_datos
                  );
		}
		$this->returnData($data);
      }




function getFechaToText($fecha){
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$salida ='';
	$salida = $fecha; //$dias[$date('w')]." ".$date('d')." de ".$meses[$date('n')-1]. " del ".$date('Y') ;
	return $salida;
	
}


/*!  2022-08-10 Se integra parametro para ver si es solo la cantidad o si lleva pesos
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @param $pesos bool - Si lleva pesos y centavos (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra. 

*/ 
function numeroToLetras($num, $fem = false, $dec = true, $pesos=true) { 
      $matuni[2]  = "dos";    $matuni[3]  = "tres"; 
      $matuni[4]  = "cuatro"; 
      $matuni[5]  = "cinco"; 
      $matuni[6]  = "seis"; 
      $matuni[7]  = "siete"; 
      $matuni[8]  = "ocho"; 
      $matuni[9]  = "nueve"; 
      $matuni[10] = "diez"; 
      $matuni[11] = "once"; 
      $matuni[12] = "doce"; 
      $matuni[13] = "trece"; 
      $matuni[14] = "catorce"; 
      $matuni[15] = "quince"; 
      $matuni[16] = "dieciseis"; 
      $matuni[17] = "diecisiete"; 
      $matuni[18] = "dieciocho"; 
      $matuni[19] = "diecinueve"; 
      $matuni[20] = "veinte"; 
      $matunisub[2] = "dos"; 
      $matunisub[3] = "tres"; 
      $matunisub[4] = "cuatro"; 
      $matunisub[5] = "quin"; 
      $matunisub[6] = "seis"; 
      $matunisub[7] = "sete"; 
      $matunisub[8] = "ocho"; 
      $matunisub[9] = "nove"; 

      $matdec[2] = "veint"; 
      $matdec[3] = "treinta"; 
      $matdec[4] = "cuarenta"; 
      $matdec[5] = "cincuenta"; 
      $matdec[6] = "sesenta"; 
      $matdec[7] = "setenta"; 
      $matdec[8] = "ochenta"; 
      $matdec[9] = "noventa"; 
      $matsub[3]  = 'mill'; 
      $matsub[5]  = 'bill'; 
      $matsub[7]  = 'mill'; 
      $matsub[9]  = 'trill'; 
      $matsub[11] = 'mill'; 
      $matsub[13] = 'bill'; 
      $matsub[15] = 'mill'; 
      $matmil[4]  = 'millones'; 
      $matmil[6]  = 'billones'; 
      $matmil[7]  = 'de billones'; 
      $matmil[8]  = 'millones de billones'; 
      $matmil[10] = 'trillones'; 
      $matmil[11] = 'de trillones'; 
      $matmil[12] = 'millones de trillones'; 
      $matmil[13] = 'de trillones'; 
      $matmil[14] = 'billones de trillones'; 
      $matmil[15] = 'de billones de trillones'; 
      $matmil[16] = 'millones de billones de trillones'; 

      //Zi hack
      $float=explode('.',$num);
      $num=$float[0];

      $num = trim((string)@$num); 
      if ($num[0] == '-') { 
            $neg = 'menos '; 
            $num = substr($num, 1); 
      }else 
            $neg = ''; 
      while ($num[0] == '0') $num = substr($num, 1); 
      if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
      $zeros = true; 
      $punt = false; 
      $ent = ''; 
      $fra = ''; 
      for ($c = 0; $c < strlen($num); $c++) { 
            $n = $num[$c]; 
            if (! (strpos(".,'''", $n) === false)) { 
            if ($punt) break; 
            else{ 
                  $punt = true; 
                  continue; 
            } 

            }elseif (! (strpos('0123456789', $n) === false)) { 
            if ($punt) { 
                  if ($n != '0') $zeros = false; 
                  $fra .= $n; 
            }else 

                  $ent .= $n; 
            }else 

            break; 

      } 
      $ent = '     ' . $ent; 
      if ($dec and $fra and ! $zeros) { 
            $fin = ' coma'; 
            for ($n = 0; $n < strlen($fra); $n++) { 
            if (($s = $fra[$n]) == '0') 
                  $fin .= ' cero'; 
            elseif ($s == '1') 
                  $fin .= $fem ? ' una' : ' un'; 
            else 
                  $fin .= ' ' . $matuni[$s]; 
            } 
      }else 
            $fin = ''; 
      if ((int)$ent === 0) return 'Cero ' . $fin; 
      $tex = ''; 
      $sub = 0; 
      $mils = 0; 
      $neutro = false; 
      while ( ($num = substr($ent, -3)) != '   ') { 
            $ent = substr($ent, 0, -3); 
            if (++$sub < 3 and $fem) { 
                  $matuni[1] = 'una'; 
                  $subcent = 'as'; 
            }else{ 
                  $matuni[1] = $neutro ? 'un' : 'uno'; 
                  $subcent = 'os'; 
            } 
            $t = ''; 
            $n2 = substr($num, 1); 
            if ($n2 == '00') { 
            }elseif ($n2 < 21) 
            $t = ' ' . $matuni[(int)$n2]; 
            elseif ($n2 < 30) { 
            $n3 = $num[2]; 
            if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
            $n2 = $num[1]; 
            $t = ' ' . $matdec[$n2] . $t; 
            }else{ 
            $n3 = $num[2]; 
            if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
            $n2 = $num[1]; 
            $t = ' ' . $matdec[$n2] . $t; 
            } 
            $n = $num[0]; 
            if ($n == 1) { 
            $t = ' ciento' . $t; 
            }elseif ($n == 5){ 
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
            }elseif ($n != 0){ 
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
            } 
            if ($sub == 1) { 
            }elseif (! isset($matsub[$sub])) { 
            if ($num == 1) { 
                  $t = ' mil'; 
            }elseif ($num > 1){ 
                  $t .= ' mil'; 
            } 
            }elseif ($num == 1) { 
            $t .= ' ' . $matsub[$sub] . '?n'; 
            }elseif ($num > 1){ 
            $t .= ' ' . $matsub[$sub] . 'ones'; 
            }   
            if ($num == '000') $mils ++; 
            elseif ($mils != 0) { 
            if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
            $mils = 0; 
            } 
            $neutro = true; 
            $tex = $t . $tex; 
      } 
      $tex = $neg . substr($tex, 1) . $fin; 
      //Zi hack --> return ucfirst($tex);
      if ($pesos){
            $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
      }else{
            $end_num=ucfirst($tex);
      }
      return $end_num; 
	} 

      //2022-08-10
      function getFechaLetras($fecha){
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");	 
		$month = date_format($fecha,'m');
		$fechalet = date_format($fecha,'d').' de '.$meses[$month-1].' de '.date_format($fecha,'Y');

		return $fechalet;
	}

      //2022-08-10
	function getFechaMes($fecha){
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$month = date_format($fecha,'m');
		$fechalet = $meses[$month-1];
		return $fechalet;		
	}

      //2022-08-10
	function getFechaYearLetras($fecha){
		$year = date_format($fecha,'Y');
		$ret = $this->numeroToLetras($year, false, false, false);
		return $ret;		
	}	

	/*
	* obtiene la paginación y los registros
	*/
	public function pagina_get() {
		$this->load->helper('paginacion');
		$pagina = $this->uri->segment(4);
		$por_pagina = $this->uri->segment(5);
		$tabla = "bancos";
		if ($content = $this->get('from', TRUE)) {
			$tabla = $content;
		}
		$order_by = array();
		if ($content = $this->get('orderby', TRUE)) {
			$order_by = $content;
		}		
		$respuesta = pagina_table( $tabla , $pagina, $por_pagina, $order_by);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}
	

      //2018-10-17
	public function colmena_query_get(){
		$nombre = strtoupper($_GET["q"]);
		$query ="SELECT c.numero, c.nombre, (u.first_name || ' ' || u.last_name) as promotor, CASE WHEN c.empresa='B' THEN 'Bancomunidad' WHEN c.empresa='F' THEN 'Fincomunidad' END as empresa FROM col.colmenas as c LEFT JOIN security.users as u ON c.idpromotor = u.id WHERE upper(c.nombre) like '%".$nombre."%' ORDER BY c.numero limit 8";
		$colmena = $this->base->querySelect($query, FALSE);
		$this->validaCode($colmena);	
	}

      //2019-08-14
      //2018-10-17
      public function get_colmena_id_get(){
		$numero = $this->uri->segment(4);
            $idMunicipio=0;
            //$where = array('acreditadoid' => $valor);
		$colmena = $this->base->querySelect("SELECT idcolmena, idsucursal, idmunicipio, idcolonia, numero, nombre, dia, idpromotor, fecha_apertura, empresa, direccion, direccion_ref, extract(hour from horainicio) as horas, extract(minute from horainicio) as minutos, map 
                  FROM col.colmenas WHERE numero=".$numero, TRUE);   
            if($colmena){
                  $idMunicipio = $colmena[0]['idmunicipio'];
            }      

            /*
		$catColonia = $this->base->querySelect("SELECT l.id_asenta_cpcons as value, l.d_asenta as name 
            FROM public.localidades as l WHERE l.c_mnpio='".$idMunicipio."' ORDER BY id_asenta_cpcons", TRUE);
            //print_r($catColonia);
            //die();           

            if ($catColonia){

            }else{
                  $catColonia = array(
                        array('value'=> '00', 'name'=>'Seleccione...')
                  );

            };
            */
                              
            $libre = $this->base->querySelect("SELECT c.idcolmena
                  FROM col.colmenas c JOIN col.grupos g ON g.idcolmena = c.idcolmena 
                        LEFT JOIN acreditado as a ON g.idgrupo=a.idgrupo
                  WHERE not a.idacreditado is null
                        and c.numero=".$numero." LIMIT 1", TRUE);
            $canDelete = "0";
            if  ($libre){
                  $canDelete="1";
            }

            if ($colmena) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Colmena obtenida correctamente!",
                        "colmena"=> $colmena,
                        //"catColonia"=>$catColonia,
                        "canDelete"=> $canDelete
                  );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"Colmena ".$numero." no encontrada!");
            }
            $this->returnData($data);
      }

      //2023-02-06
      public function get_colmena_colonia_get(){
		$numero = $this->uri->segment(4);
            //$idMunicipio=0;

		$catColonia = $this->base->querySelect("SELECT l.id_asenta_cpcons as value, l.d_asenta as name
                  FROM col.colmenas as c
                        JOIN public.localidades as l ON c.idmunicipio=l.c_mnpio
                  WHERE numero=".$numero, TRUE);
            if ($catColonia){

            }else{
                  $catColonia = array(
                        array('value'=> '00', 'name'=>'Seleccione...')
                  );

            };
            if ($catColonia) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Colmena obtenida correctamente!",
                        "catColonia"=>$catColonia,
                  );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"Colmena ".$numero." no encontrada!");
            }
            $this->returnData($data);
      }  


      public function new_colmenas_post() {

		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');

            //print_r($valores);           
            //die;
            $numero = $valores['numero'];
            $col = $this->base->querySelect("SELECT idcolmena FROM col.colmenas WHERE numero=".$numero, TRUE);
            if ($col) {
                  //Existe
                  $query = "Select ".$this->esquema."set_acreditado_grupo(".$acreditadoid.",".$idgrupo.",".$idcolcargo.",".$idgpocargo.",".$orden.")";
            }else{
                  //No existe
                  $query = "INSERT INTO ".$this->esquema."set_acreditado_grupo(".$acreditadoid.",".$idgrupo.",".$idcolcargo.",".$idgpocargo.",".$orden.")";
            }
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

            /*
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $adicionDatos = array('idsucursal' => '01');
		$this->insertData('Colmenas', $valores, '',$adicionDatos);
            //$this->insertData('Credito', $valores, 'solicitud_credito_put',$adicionDatos);
            */
	}

            
      //2018-10-17
	public function add_colmenas_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $adicionDatos = array('idsucursal' => '01');
		$this->insertData('Colmenas', $valores, 'new_colmenas_put',$adicionDatos);
	}

	public function add_colmenas_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();
            $where = array('numero' => $valores['numero']);
//            print_r($valores);
  //          die;
		$this->updateData('Colmenas', $valores, 'new_colmenas_put', $where, false);
            
      }

      //2018-10-29
	/*
	* Consulta de  las colonias segun el municipios
	*/
	public function catcolonia_get(){
		$valor = $this->uri->segment(4);
		$where = array('c_mnpio' => $valor);
		$group_by = array("id_asenta_cpcons","d_asenta");
		$this->selectData('public.localidades', "id_asenta_cpcons as value, d_asenta as name", "", $where,"", "" , $group_by, "", "", "", FALSE);
	}      

      
      public function getcreditos_sin_entregar_get(){
            $numero = $this->uri->segment(4);
		$catCreditos = $this->base->querySelect("SELECT idcredito as value, (x.dias || ' dias, ' || acreditado || ', pagare: ' || idpagare)::varchar as name 
                  FROM (
                        SELECT c.idcredito, c.idpagare, (current_date - c.fecha_entrega_col) as dias, a.acreditado
                        FROM ".$this->session->userdata('esquema')."creditos as c
                              JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid 
                        WHERE c.fecha_entrega is null) AS x
                  WHERE x.dias >".$numero." ORDER BY x.dias desc", TRUE);
            if ($catCreditos) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Creditos obtenidos correctamente!",
                        "catCreditos"=> $catCreditos
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Creditos no encontrados!");
		}
            $this->returnData($data);            
      }

      public function getcreditos_eliminar_get(){
            $numero = $this->uri->segment(4);
		$catCredito = $this->base->querySelect("SELECT c.idcredito, c.idpagare, c.fecha_entrega_col, a.acreditado, c.monto, c.fecha
                  FROM ".$this->session->userdata('esquema')."creditos as c
                        JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid 
                  WHERE c.idcredito=".$numero, TRUE);
            if ($catCredito) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Credito obtenido correctamente!",
                        "catCredito"=> $catCredito
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Credito no encontrado!");
		}
            $this->returnData($data);            
      }      

      public function credito_eliminar_post(){

		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$idCredito = $valores['idcredito'];
		$catCredito = $this->base->querySelect("SELECT * from ".$this->session->userdata('esquema')."eliminar_credito(".$idCredito.")", TRUE);
            if ($catCredito) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Credito eliminado correctamente!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "catCredito"=> $catCredito
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Credito no encontrado!");
            }
            $this->returnData($data);            
      }    
	  
      public function credito_eliminar2_post(){
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $idCredito = $valores['idbuscar'];
			$catCredito = $this->base->querySelect("SELECT * from ".$this->session->userdata('esquema')."eliminar_credito(".$idCredito.")", TRUE);
            if ($catCredito) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Credito eliminado correctamente!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "catCredito"=> $catCredito
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Credito no encontrado!");
            }
            $this->returnData($data);            
      }    	  

	public function creditosrev_get(){
		$where = array('idsucursal' => $this->session->userdata('sucursal_id') );
		$this->selectData($this->session->userdata('esquema')."creditos_auth_rev", "idcredito, idacreditado, nombre, idpagare, monto, fecha_aprov, fecha_dispersa, idsucursal", "", $where,"", "" ,"", "", "", "", FALSE);
      }

	public function creditosrev_put(){
            $idcredito = $this->uri->segment(4);
                        
		$tabla = $this->session->userdata('esquema').'creditos';
		$record = array('fecha_aprov' => null,
						'usuario_aprov'=> null
						);				
		$where = array('idcredito'=>$idcredito);
            $update = $this->base->updateRecord($tabla, $record, $where, 0);
            print_r($update);
		$this->validaCode($update);

	}    

	
	public function get_nivel_pagos_get(){
		$nivel = $this->uri->segment(4);
		$cat_numpagos = $this->base->querySelect("SELECT numero_pagos as value, numero_pagos as name FROM niveles WHERE nivel = ".$nivel." AND fecha_baja is null ORDER BY fecha_inicio", TRUE);
		if ($cat_numpagos) {
			  $data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Colmenas obtenidas correctamente!",
					"newtoken"=>$this->security->get_csrf_hash(),
					"cat_numpagos"=> $cat_numpagos
			  );
	} else {
		$data = array("status"=>"ERROR",
			"code" => 404,
					"newtoken"=>$this->security->get_csrf_hash(),
			"message"=>"Colmenas no encontradas!");
		}
		$this->returnData($data);

	}

      //2020-01-20
      public function colmena_eliminar_post(){

            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $idColmena = $valores['idcolmena'];

		$catCredito = $this->base->querySelect("SELECT col.eliminar_colmena(".$idColmena.")", TRUE);
            if ($catCredito) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Colmena eliminada correctamente!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "catCredito"=> $catCredito
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Colmena no encontrada!");
            }
            $this->returnData($data);            
      }    

      //2020-01-20
      //2020/02/01
      public function colmena_cerrar_post(){

            $valores = $this->post('data')?$this->post('data', TRUE):array();
            $idColmena = $valores['idcolmena'];

		$catCredito = $this->base->querySelect("SELECT col.cerrar_colmena(".$idColmena.")", TRUE);
            if ($catCredito) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Colmena cerrada correctamente!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "catCredito"=> $catCredito
                  );
                  
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Colmena no encontrada!");
            }
            $this->returnData($data);            
      }    

      //2020-01-20
      //2020-02-01
      public function get_colmenas_vacias_get(){
            //$valores = $this->post('data')?$this->post('data', TRUE):array();
            $catColmenas = $this->base->querySelect("SELECT idcolmena as value, (numero || ' - ' || nombre) as name from col.v_colmena_vacia WHERE fechacierre is null ORDER BY numero", TRUE);   
            if ($catColmenas) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Colmenas obtenidas correctamente!",
                        "newtoken"=>$this->security->get_csrf_hash(),
                        "catColmenas"=> $catColmenas
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
                        "newtoken"=>$this->security->get_csrf_hash(),
				"message"=>"Colmenas no encontradas!");
            }
            $this->returnData($data);            
      }    


		public function get_importe_by_idnivel_get(){
            $idnivel = $this->uri->segment(4);
		$catnivel = $this->base->querySelect("SELECT nivel, importe, ssi_tasa, numero_pagos FROM niveles WHERE idnivel=".$idnivel, TRUE);  
            if ($catnivel){
                  $importe = $catnivel[0]['importe'];
                  $nivel = $catnivel[0]['nivel'];
                  $tasa = $catnivel[0]['ssi_tasa'];
                  $numero_pagos = $catnivel[0]['numero_pagos'];
            }else{
                  $nivel = 0;
                  $importe = 0;
                  $tasa = 0;
                  $numero_pagos = 0;
            }
            $data = array("status"=>"OK",
                  "code" => 200,
                  "message"=>"Nivel obtenido",
                  "nivel" => $nivel,
                  "importe" => $importe,
                  "tasa" => $tasa,
                  "numero_pagos" => $numero_pagos
            );
            $this->returnData($data);
      }

      public function update_telefono_put(){            
        $idpersona = $this->uri->segment(4);
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');

        $tel = $datos[0]['telefono']; 
        $tel_nota = $datos[1]['telefono_nota']; 
		$tabla = 'public.personas';
		$record = array('celular' => $tel,
			'celular_nota'=> $tel_nota
						);				
		$where = array('idpersona'=>$idpersona);
            $update = $this->base->updateRecord($tabla, $record, $where, 0);
            print_r($update);
		$this->validaCode($update);            
      }

		//2022-04-05
	public function get_config_prov_get() {
            $idprovision = $this->uri->segment(4);

            $fields = array("idprovcnf", "idcredito", "fecha_ini", "fecha_fin", "nota", "fecha_aprov", "usuario_aprov", "usuario", "fecha_mov");
            $where = array("idprovcnf"=>$idprovision);
            $provision = $this->base->selectRecord($this->esquema."prov_config", $fields, "", $where, "","", "", "", "","", TRUE);
			
            if ($provision) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Configuración de provisión obtenida correctamente!",
                        "provision"=> $provision
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Configuración de provisión no encontrada!");
		}
		$this->returnData($data);
	}

      public function aut_config_prov_put() {

		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$datos = fn_extraer($valores,'N');
		$idprovision = $datos['idprovision']; 

		$tabla = $this->session->userdata('esquema').'prov_config';
		$record = array('fecha_aprov' => date("Y-m-d H:i:s"),
						'usuario_aprov'=> $this->ion_auth->user()->row()->username
						);				
		$where = array('idprovcnf'=>$idprovision);
		$update = $this->base->updateRecord($tabla, $record, $where, 0);
		$this->validaCode($update);
	}


      public function add_config_prov_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->insertData('ProvConfig', $valores, 'prov_config_put');
          

	}

	public function add_config_prov_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();

            $valores['usuario'] = $this->ion_auth->user()->row()->id;
            $valores['fecha_mov'] = date("Y-m-d H:i:s");

            $fecha = explode("/", $valores['fecha_ini']); 
            $valores['fecha_ini'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
            $fecha = explode("/", $valores['fecha_fin']); 
            $valores['fecha_fin'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
			
            $where = array('idprovcnf' => $valores['idprovision']);
		$this->updateData('ProvConfig', $valores, 'prov_config_put', $where, false);
            
	}

	/*
      * Busqueda de configuración de provisiones  
      */
	public function find_prov_config_get(){
            $nombre = strtoupper($_GET["q"]);
            $query = "SELECT p.idprovcnf, p.idcredito, upper(a.acreditado) as nombre 
                        FROM ".$this->esquema."prov_config as p 
                              JOIN ".$this->esquema."creditos as c ON p.idcredito = c.idcredito 
                              JOIN get_acreditados as a ON c.idacreditado = a.acreditadoid  
                        WHERE upper(a.acreditado) like '%".$nombre."%' ORDER BY p.idprovcnf limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}


	/*
      2020-03-06
      2022-05
      Llamado al seleccionar el grupo en asignación
      */
      public function get_grupo_acreditados_cargo_get() {
		$idgrupo = $this->uri->segment(4);
            
            //Cargos asignados
            $idgrupo_presi = 0; 
            $grupo_presi = "";
            $idgrupo_teso = 0;
            $grupo_teso = "";         

            $cargo_asigna = $this->base->querySelect("SELECT  g.idcolmena, g.numero, gc.idcargo1, p1.acreditado AS grupo_presidenta, gc.idcargo2, p2.acreditado AS grupo_tesorera
                  FROM col.grupos as g 
                        JOIN col.grupo_cargo as gc ON g.idgrupo=gc.idgrupo
                        LEFT JOIN get_acreditados p1 ON gc.idcargo1 = p1.acreditadoid
                        LEFT JOIN get_acreditados p2 ON gc.idcargo2 = p2.acreditadoid
                  WHERE g.idgrupo=".$idgrupo." ORDER BY g.numero;", TRUE); 
            

            if ($cargo_asigna===false){
                  $cargo_asigna=[];
            }else{
                  $idgrupo_presi = $cargo_asigna[0]['idcargo1']; 
                  $grupo_presi = $cargo_asigna[0]['grupo_presidenta'];
                  $idgrupo_teso = $cargo_asigna[0]['idcargo2'];
                  $grupo_teso = $cargo_asigna[0]['grupo_tesorera'];                         
            }
            //$fields = array("idcolmena", "idgrupo", "grupo_numero","grupo_nombre", "nombre", "idacreditado", "acreditadoid", "idanterior");
            //$fields = array("idgrupo as value", "nombre as name", "idacreditado", "acreditadoid", "idanterior", "orden", "cargo_colmena", "cargo_grupo");
            $fields = array("acreditadoid as value", "nombre as name");
            $where = array("idgrupo"=>$idgrupo);
            $order_by = array(array('campo'=> 'orden', 'direccion'=>	'asc'));
            $grupo_acreditados = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            if ($grupo_acreditados===false){
                  $grupo_acreditados=[];
            }

            if ($grupo_acreditados) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Grupo obtenido correctamente!",
                        "grupo_acreditados"=> $grupo_acreditados,
                        "grupo_tesoreros"=> $grupo_acreditados,
                        "cargo_asigna"=>$cargo_asigna,
                        "idgrupo_presi"=>$idgrupo_presi, 
                        "grupo_presi"=>$grupo_presi, 
                        "idgrupo_teso"=>$idgrupo_teso, 
                        "grupo_teso"=>$grupo_teso,                          
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Grupo no encontrado!");
		}
		$this->returnData($data);
	}


      //2020-03-06
      //2020-05
      //
	public function update_colmena_cargo_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');

            //print_r($valores);           
            //die;

            $idcolmena = $valores['idcolmena'];
            $idcol_presi = $valores['idcol_presi'];
            $idcol_secre = $valores['idcol_secre'];
            //$idcol_mab = $valores['idcol_mab'];
            if ($idcol_presi==''){
                  $idcol_presi=0;
            }
            if ($idcol_secre==''){
                  $idcol_secre=0;
            }
            /*
            if ($idcol_mab==''){
                  $idcol_mab=0;
            }
            */
            $idcol_mab=0;
            //$query = "Select col.set_colmena_cargos(".$idcolmena.",".$idcol_presi.",".$idcol_secre.",".$idcol_mab.",".$this->ion_auth->user()->row()->id.")";
            $query = "Select col.set_colmena_cargos(".$idcolmena.",".$idcol_presi.",".$idcol_secre.",".$idcol_mab.",".$this->ion_auth->user()->row()->id.")";
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

	}


	//2020-03-06
      //2022-05
      //
	public function update_colmena_cargo2_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');

            //print_r($valores);           
            //die;

            $idcolmena = $valores['idgrupo'];
            $idcol_presi = $valores['idgrupo_presi'];
            $idcol_teso = $valores['idgrupo_teso'];
            //$idcol_mab = $valores['idcol_mab'];
            if ($idcol_presi==''){
                  $idcol_presi=0;
            }
            if ($idcol_teso==''){
                  $idcol_teso=0;
            }
            /*
            if ($idcol_mab==''){
                  $idcol_mab=0;
            }
            */
            $idcol_mab=0;
            //$query = "Select col.set_colmena_cargos(".$idcolmena.",".$idcol_presi.",".$idcol_secre.",".$idcol_mab.",".$this->ion_auth->user()->row()->id.")";
            $query = "Select col.set_grupo_cargos(".$idcolmena.",".$idcol_presi.",".$idcol_teso.",".$this->ion_auth->user()->row()->id.")";
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

	}

      //2020-03-06
      //2020-05
      //
	public function update_grupo_cargo_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//$datos = fn_extraer($valores,'N');


            $idgrupo = $valores['idgrupo'];
            $idgrupo_presi = $valores['idgrupo_presi'];
            $idgrupo_teso = $valores['idgrupo_teso'];
            if ($idgrupo_presi==''){
                  $idgrupo_presi=0;
            }
            if ($idgrupo_teso==''){
                  $idgrupo_teso=0;
            }
            $query = "Select col.set_grupo_cargos(".$idgrupo.",".$idgrupo_presi.",".$idgrupo_teso.",".$this->ion_auth->user()->row()->id.")";
            $regresa = $this->base->querySelect($query, FALSE);
            $this->validaCode($regresa);

	}

/*
      2020-03-06
      Para hacer el cambio de cargos en la colmena
      */
	public function get_colmena_acreditados_get() {
		$idcolmena = $this->uri->segment(4);

            $where = array("idcolmena" => $idcolmena);
            $col_cargos = $this->base->selectRecord('col.v_colmenas_directorio', 'idpresidente, presidenta, idsecretario, secretaria',"",$where, "","", "", "", "","", TRUE);
            
            //Todas las asociadas asignadas a la colmena
            $fields = array("acreditadoid as value", "idacreditado || ' - ' || nombre as name");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'grupo_numero', 'direccion'=>	'asc'));
            $cat_asigna = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            
            //Todos los grupos asignados
            $fields = array("idgrupo as value", "numero || ' - ' || nombre as name");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'idgrupo', 'direccion'=>	'asc'));
            $cat_grupos = $this->base->selectRecord("col.grupos", $fields, "", $where, "","", "", $order_by, "","", TRUE);

            if ($cat_asigna) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Colmena obtenida correctamente!",
                        "cat_asigna"=>$cat_asigna,
                        "cat_grupos"=>$cat_grupos,
                        "col_cargos"=> $col_cargos
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

     //2022-10-18
	public function niveles_query_get(){
		$nombre = strtoupper($_GET["q"]);
            /*
		$query ="SELECT c.numero, c.nombre, (u.first_name || ' ' || u.last_name) as promotor, 
                  CASE WHEN c.empresa='B' THEN 'Bancomunidad' WHEN c.empresa='F' THEN 'Fincomunidad' END as empresa 
                  FROM col.colmenas as c LEFT JOIN security.users as u ON c.idpromotor = u.id WHERE upper(c.nombre) like '%".$nombre."%' ORDER BY c.numero limit 8";
		*/
            $query ="SELECT n.idnivel, n.nivel, to_char(n.importe,'999,999') as importe, n.numero_pagos, n.fecha_inicio FROM niveles as n WHERE upper(n.nivel::varchar) like '".$nombre."%' AND fecha_fin is null ORDER BY n.nivel, numero_pagos limit 8";
            $niveles = $this->base->querySelect($query, FALSE);
		$this->validaCode($niveles);	
	}

      //2022-10-18
      public function get_niveles_get() {

            $cat_Nivel =  array( array('value'=> '1', 'name'=>'Nivel 01') );
            for ($i = 2; $i <= 100; $i++) {
                  //array_push($catMinutos, array('value'=> $i, 'name'=>$i));
                  $mi_nivel = str_pad((int) $i, 2, "0" , STR_PAD_LEFT);
                  array_push($cat_Nivel, array('value'=> $i, 'name'=>"Nivel ".$mi_nivel));
                  
            }           

            $cat_numero_pagos =  array(
                  array('value'=> '16', 'name'=>'16'),
				  array('value'=> '20', 'name'=>'20'),
				  array('value'=> '25', 'name'=>'25'),
                  array('value'=> '30', 'name'=>'30'),
                  array('value'=> '40', 'name'=>'40'),
				  array('value'=> '45', 'name'=>'45'),
                  array('value'=> '52', 'name'=>'52'),
                  array('value'=> '78', 'name'=>'78')
            );            

            $cat_tasa =  array(
                  array('value'=> '48', 'name'=>'48 %'),
                  array('value'=> '54', 'name'=>'54 %'),
				  array('value'=> '60', 'name'=>'60 %')
            );

		$cat_idnivel = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", "", "", "", "","", "", "",TRUE);
		if ($cat_tasa) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Tabla consultada",
                        "cat_Nivel" => $cat_Nivel,
                        "cat_numero_pagos"=> $cat_numero_pagos,
                        "cat_tasa"=>$cat_tasa,
                        "cat_idnivel"=>$cat_idnivel
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
                        "cat_Nivel" => $cat_Nivel,
                        "cat_numero_pagos"=> $cat_numero_pagos,
                        "cat_tasa"=>$cat_tasa,
                        "cat_idnivel"=>$cat_idnivel                        
            );
		}
		$this->returnData($data);
      }      

      //2022-10-18
      public function get_nivel_id_get(){
		$numero = $this->uri->segment(4);
		$nivel = $this->base->querySelect("SELECT idnivel, nivel, importe, ssi_tasa::int, ssi_pago, pf_tasa, pf_servicio, pf_capital, pf_aporte_sol, pf_garantia, numero_pagos, dias, idproducto, fecha_inicio,	ssi_tasamora, tasa_ind, tasa_mora
                  FROM niveles WHERE idnivel=".$numero, TRUE);    
            /*
            $colmena = $this->base->querySelect("SELECT idcolmena, idsucursal, idmunicipio, idcolonia, numero, nombre, dia, idpromotor, fecha_apertura, empresa, direccion, direccion_ref, extract(hour from horainicio) as horas, extract(minute from horainicio) as minutos, map
                  FROM col.colmenas WHERE numero=".$numero, TRUE);     
            */
            //Validar si el hay un credito con ese nivel asignado, si es asi, no se puede editar
            $existe='';
		$catCredito = $this->base->querySelect("SELECT * FROM fin.creditos WHERE idnivel=".$numero." limit 1", TRUE);
            if ($catCredito){
                  $existe.='- Fincomunidad ';
            }
		$catCredito = $this->base->querySelect("SELECT * FROM imp.creditos WHERE idnivel=".$numero." limit 1", TRUE);
            if ($catCredito){
                  $existe.='- Impulso ';
            }
		$catCredito = $this->base->querySelect("SELECT * FROM ban.creditos WHERE idnivel=".$numero." limit 1", TRUE);
            if ($catCredito){
                  $existe.='- Bancomunidad ';
            }

            if ($nivel) {
                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Nivel obtenido correctamente!",
                        "niveles"=> $nivel,
                        "existe"=>$existe
                  );
            } else {
                  $data = array("status"=>"ERROR",
                        "code" => 404,
                        "message"=>"Nivel de ".$numero." no encontrado!");
            }
            $this->returnData($data);
      }

      //2022-10-18
	public function add_nivel_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
            //$adicionDatos = array('idsucursal' => '01');
		//$this->insertData('Colmenas', $valores, 'new_colmenas_put',$adicionDatos);

		$this->insertData('Nivel', $valores, 'nivel_put');
	}
      //2022-10-18
	public function add_nivel_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();
            $where = array('idnivel' => $valores['idnivel']);
            //print_r($valores);
            //die;
		$this->updateData('Nivel', $valores, 'nivel_put', $where, false);
            
      }      

    /*
	// 2023-03-25 Colmenas manejadas por un promotor
	*/
	public function get_promotor_col_get() {
            $idPromotor = $this->uri->segment(4);
            $fecha = $this->uri->segment(5);

            $miFecha = date_create($fecha);
            $dia = date_format($miFecha,'w');
		$idSucursal = $this->session->userdata('sucursal_id');
		//$idPromotor = $this->ion_auth->user()->row()->id;
		//$idPromotor = 60;
		$catcolmenas = $this->base->querySelect("SELECT idcolmena as value, (numero || ' - ' || nombre) as name 
						FROM col.v_colmenas_directorio 
						WHERE idpromotor=".$idPromotor." AND fechacierre is null and idsucursal='".$idSucursal."' and dia=".$dia." ORDER BY horainicio", TRUE);   
		if ($catcolmenas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Colmenas consultadas",
					"catcolmenas" => $catcolmenas,
                              "diaColmena" => $dia,
				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas
				);
		}
		$this->returnData($data);
	}


      public function get_niveles_activos_get() {

            $cat_nivel = $this->base->querySelect("SELECT nivel as value, ('Nivel ' || nivel) as name FROM ".$this->session->userdata('esquema')."rpt_credito_activo group by nivel", TRUE);
            if ($cat_nivel) {

			$cat_niveles = array( array('value'=> 0, 'name'=>'TODOS') );
			foreach($cat_nivel as $key => $value) {
				Array_push($cat_niveles, array('value'=> $value['value'], 'name'=>$value['name']));
			}


                  $data = array("status"=>"OK",
                        "code" => 200,
                        "message"=>"Tabla consultada",
                        "cat_nivel" => $cat_niveles,
                  );
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
                        "cat_nivel" => $cat_niveles,
            );
		}
		$this->returnData($data);
      }            

      //2023-09-25
	public function get_credito_aut_get() {
		$idcredito = $this->uri->segment(4);
		$fields = array("idcredito", "fecha_aprov", "usuario_aprov");
		$where = array("idcredito"=>$idcredito);
		$cre_aut = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
		if ($cre_aut) {
			$cre_aut = $cre_aut[0];
			$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
				"cre_aut"=> $cre_aut
			);
		}else {
		$data = array("status"=>"ERROR",
			"code" => 404,
			"message"=>"Solicitud no encontrada!");
		}
		$this->returnData($data);
	}

}
