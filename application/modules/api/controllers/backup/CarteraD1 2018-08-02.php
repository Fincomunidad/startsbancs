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
		$this->insertData('credito', $valores, 'solicitud_credito_put',$adicionDatos);
	}

	public function add_credito_put() {
            $valores = $this->put('data')?$this->put('data', TRUE):array();
            $valores['idproducto'] = 1;
            $valores['idejecutivo'] = $this->ion_auth->user()->row()->id;
            $where = array('idcredito' => $valores['idcredito']);
		$this->updateData('credito', $valores, 'solicitud_credito_put', $where, false);
            
	}
      
	public function add_crediton_post() {
            $valores = $this->post('data')?$this->post('data', TRUE):array();
		$adicionDatos = array('idproducto' => 10, 'idejecutivo' => 1);
            $this->insertData('crediton', $valores, 'solicitud_crediton_put',$adicionDatos);
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



	public function get_solicitud_credito_get() {
            $idcredito = $this->uri->segment(4);
            
            $idsucursal = $this->session->userdata('sucursal_id');
		
            $fields = array("idcredito", "idsucursal", "fecha", "idpagare", "acreditadoid", "direccion", "edocivil", "edocivil_nombre", "idactividad", "actividad_nombre", "idcolmena", "nomcolmena", "idgrupo", "nomgrupo", "nivel", "monto", "proy_nombre", "proy_descri", "proy_lugar", "proy_observa", "idchecklist", "fecha_aprov", "usuario_aprov", "fecha_pago", "periodo", "num_pagos","tasa", "fecha_pago2", "idaval1", "idaval2", "fecha_entrega_col");
		$where = array("idcredito"=>$idcredito);
            $solcredito = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
            $idacreditado = $solcredito[0]['acreditadoid'];

            $query = "Select count(idcredito) as total FROM ".$this->esquema."v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull";
            $checklist = $this->base->querySelect($query, TRUE);

		$fields = array("numero", "fecha_vence", "capital", "interes", "iva", "aportesol", "garantia", "(garantia + total) as total ");
		$where = array("idcredito"=>$idcredito);
		//$this->session->userdata('esquema').
            $order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->esquema."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);

		$cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
		$cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
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
		} else {
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
            
            $fields = array("idcredito", "idsucursal", "fecha", "idpagare", "acreditadoid", "direccion", "edocivil", "edocivil_nombre", "idactividad", "actividad_nombre", "idcolmena", "nomcolmena", "idgrupo", "nomgrupo", "nivel", "monto", "proy_nombre", "proy_descri", "proy_lugar", "proy_observa", "idchecklist", "fecha_aprov", "usuario_aprov", "fecha_pago", "periodo", "num_pagos","tasa", "fecha_pago2", "idaval1", "idaval2", "fecha_entrega_col");
            $where = array("idproducto"=>$idproducto, "idcredito"=>$idcredito);
            //$where = array("idcredito"=>$idcredito);
            $solcredito = $this->base->selectRecord($this->esquema."get_solicitud_credito", $fields, "", $where, "","", "", "", "","", TRUE);
            if ($solcredito) {
                  $idacreditado = $solcredito[0]['acreditadoid'];
            }else{
                  $idacreditado=0;
            }

            $query = "Select count(idcredito) as total FROM ".$this->esquema."v_check_list where idcredito=".$idcredito." and requerido =true and fecha isnull";
            $checklist = $this->base->querySelect($query, TRUE);

		$fields = array("numero", "fecha_vence", "capital", "interes", "iva", "aportesol", "garantia", "(garantia + total) as total ");
		$where = array("idcredito"=>$idcredito);
		//$this->session->userdata('esquema').
            $order_by = array(array('campo'=> 'numero', 'direccion'=>	'asc'));
		$amor = $this->base->selectRecord($this->esquema."amortizaciones", $fields, "", $where, "","", "", $order_by, "","", TRUE);

		$cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
		$cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
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
            
            
		$tabla = $this->session->userdata('esquema').'acreditado';
		$record = array('idgrupo' => $idgrupo, 'orden'=>$orden);				
		$where = array('acreditadoid' => $acreditadoid);
            $update = $this->base->updateRecord($tabla, $record, $where, 0);
            $this->validaCode($update);
            
	}

	public function delete_acreditado_grupo_put() {
            $acreditadoid = $this->uri->segment(4);

            $tabla = $this->session->userdata('esquema').'acreditado';
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
      Llamado al seleccionar la colmena
      */
      public function get_acreditadosbycolmena_get() {
		$idcolmena = $this->uri->segment(4);
            //$fields = array("idcolmena", "idgrupo", "grupo_numero","grupo_nombre", "nombre", "idacreditado", "acreditadoid", "idanterior");
            $fields = array("grupo_nombre","idgrupo", "nombre", "idacreditado", "acreditadoid", "idanterior", "orden", "cargo_colmena", "cargo_grupo");
            $where = array("idcolmena"=>$idcolmena);
            $order_by = array(array('campo'=> 'grupo_nombre', 'direccion'=>	'asc'),
            			array('campo'=> 'orden', 'direccion'=>	'asc'));
            $grupo_acreditados = $this->base->selectRecord($this->esquema."get_acreditado_grupo", $fields, "", $where, "","", "", $order_by, "","", TRUE);
            if ($grupo_acreditados===false){
                  $grupo_acreditados=[];
            }
            if ($grupo_acreditados) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Solicitud obtenida correctamente!",
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


	public function get_acreditado_get() {
		$idacreditado = $this->uri->segment(4);

		$where = array("acreditadoid" => $idacreditado);
		//$solcre = $this->base->selectRecord("public.personas", "", "", $where, "","", "", "", "","", TRUE);
            $acreditado = $this->base->selectRecord($this->esquema.'get_acreditado_solicitud', 'idacreditado, nombre, idsucursal, edocivil, edocivil_nombre, idactividad, actividad_nombre, direccion, idgrupo, grupo_numero, grupo_nombre, col_numero, col_nombre',"",$where, "","", "", "", "","", TRUE);
            
            /*
		$cataval2 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_colmena_aval as v ON a.idcolmena=v.idcolmena
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
                  WHERE a.acreditadoid =".$idacreditado." ORDER BY v.idcargo;", TRUE);                   
		$cataval1 = $this->base->querySelect("SELECT v.acreditadoid as value, c.nombre || '('||v.cargo||')' as name
                  FROM fin.get_acreditado_solicitud a
                  JOIN fin.get_grupo_aval as v ON a.idgrupo=v.idgrupo
                  JOIN fin.get_acreditado_solicitud c ON v.acreditadoid = c.acreditadoid
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
            */

		if ($acreditado) {
				$data = array("status"=>"OK",
				"code" => 200,
				"message"=>"Acreditado obtenido correctamente!",
                        "acreditado"=> $acreditado,
                        //"cataval1"=> $cataval1,
                        //"cataval2"=> $cataval2,
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
            $nombre = $_GET["q"];
            $query = "SELECT c.idsucursal, c.idcredito, c.idgrupo, c.nivel, c.idpagare, upper(a.acreditado) as nombre, a.rfc, a.idacreditado, a.acreditadoid
                        FROM fin.creditos as c JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid WHERE idproducto<>10 and upper(a.acreditado) like '%".$nombre."%' ORDER BY fecha_aprov limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}

      /*
      * Busqueda de acreditados con creditos  
      */
	public function find_acreditadosInd_get(){
            $nombre = $_GET["q"];
            $query = "SELECT c.idsucursal, c.idcredito, c.idgrupo, c.nivel, c.idpagare, upper(a.acreditado) as nombre, a.rfc, a.idacreditado, a.acreditadoid
                        FROM fin.creditos as c JOIN get_acreditados as a ON c.idacreditado=a.acreditadoid WHERE c.idproducto=10 and upper(a.acreditado) like '%".$nombre."%' ORDER BY fecha_aprov limit 8";
		$acredita = $this->base->querySelect($query, FALSE);
		$this->validaCode($acredita);
	}



	public function get_colmenas_get() {
            $cat_grupo_orden =  array(
                  array('value'=> '1', 'name'=>'1'),
                  array('value'=> '2', 'name'=>'2'),
                  array('value'=> '3', 'name'=>'3'),
                  array('value'=> '4', 'name'=>'4'),
                  array('value'=> '5', 'name'=>'5')
            );

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,2,4));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_col_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);

		$fields = array("idcargo as value", "descripcion as name");
		$where_in = array("idcargo"=> array(0,1,3));
		$order_by = array(array('campo'=> 'idcargo', 'direccion'=>	'asc'));
		$cat_grupo_cargos = $this->base->selectRecord("public.cat_cargo", $fields, "", "", "","", "",  $order_by, "","", TRUE, $where_in);


		$catcolmenas = $this->base->selectRecord("col.colmenas","idcolmena as value, (numero || ' - ' || nombre) as name", "", "", "", "", "","", "", "",TRUE);
		if ($catcolmenas) {
				$data = array("status"=>"OK",
					"code" => 200,
					"message"=>"Tabla consultada",
                              "catcolmenas" => $catcolmenas,
                              "cat_grupo_orden"=> $cat_grupo_orden,
                              "cat_col_cargos"=>$cat_col_cargos,
                              "cat_grupo_cargos"=>$cat_grupo_cargos

				);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => $catcolmenas,
                        "cat_grupo_orden"=> $cat_grupo_orden,
                        "cat_col_cargos"=>$cat_col_cargos,
                        "cat_grupo_cargos"=>$cat_grupo_cargos
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
                  fin.get_cargo_colmena(a.acreditadoid) AS cargo_colmena, fin.get_cargo_grupo(a.acreditadoid) AS cargo_grupo
                  FROM public.get_acreditados as a
                        LEFT JOIN fin.get_colmena_grupo as g ON a.idgrupo=g.idgrupo
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
            $acreditado = $this->base->querySelect("SELECT acreditadoid FROM fin.acreditado WHERE idacreditado=".$acreditadoid, TRUE);
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
                  FROM fin.ahorros_int as i
                        JOIN fin.ahorros as a ON i.idahorro=a.idahorro
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
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
                        "prov_ahorro" => $prov_ahorro,
                        "prov_credito"=> $prov_credito
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


/*! 
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @result string - Devuelve el n?mero escrito en letra. 

*/ 
function numeroToLetras($num, $fem = false, $dec = true) { 
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
      $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
      return $end_num; 
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
}
