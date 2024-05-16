<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class ColmenasV1 extends BaseV1 {
	public $esquema;

	public function __construct()
    {
		parent::__construct();
		$this->methods['user_get']['limit']= 500;
		$this->methods['user_post']['limit']= 100;
		$this->methods['user_delete']['limit']= 50;
		$this->load->model('base_model','base');
		$this->load->helper('general');
		$this->load->library('form_validation');
		$this->esquema = $this->session->userdata('esquema')==""?'fin.': $this->session->userdata('esquema');		
	}

	
	public function add_aplica_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//Carga de los modelos 
		$this->load->model('colmenas_model','colmenas');		
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$datos['grantotal'] = (double)str_replace(",","",$datos['grantotal']);
		$datos['totalcompara']= (double)str_replace(",","",$datos['totalcompara']);
		$fecha_pago = $datos['fecha_pago'];

//		$fecha = explode("/", $datos['fecha_pago']); 
//		$fecha_pago =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
		

		if (array_key_exists('fecha_aplica', $datos )) {
			if ($datos['fecha_aplica'] != ''){
				$fecha = explode("/", $datos['fecha_aplica']); 
				$datos['fecha'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
			}else {
				$datos['fecha'] ='';
			}
		}

		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('colmenas_put') == TRUE) {
			$pag = array();
			$indice = 0;
			
			foreach($datos as $key => $value) {
				if (is_array($value)) {
					$indice =0;
					
					foreach($value as $key2 => $value2)	{
						if ($datos['chkpago'][$indice] =='on') {
							$pag[$key2][$key] = $value2;
						}
						$indice++;
					}
				}
			}
			$edocta= array();
/*			
			if (array_key_exists("autorizacion",$datos)) {
				$edocta['instrumento'] = $datos['instrumento'];
				$edocta['autorizacion'] = $datos['autorizacion'];
				$edocta['fecha'] = $datos['fecha'];
				$edocta['hora'] = $datos['hora'];
				$edocta['vale'] = $datos['vale'];
				$edocta['semana'] = $datos['semana'];
				$edocta['caja'] = $datos['caja'];
				$edocta['deposito'] = (double)str_replace(",","",$datos['deposito']);;
				$edocta['idgrupo'] = $datos['idgrupo'];
			}
*/			
			$updatetrans = $this->colmenas->transaction($pag, $fecha_pago, $edocta);
			$this->validaCode($updatetrans);
			if ($updatetrans['code'] == 200){
				$this->print($updatetrans['nomov']);				
			}
		 }else {
			$this->validaForm();
		 }
	}



	public function reversa_aplica_post() {
		$idgrupo = $this->uri->segment(4);
		$nomov = $this->uri->segment(5);
		if (!is_numeric($idgrupo) || !is_numeric($nomov)){
			$this->returnCode();
		 }else {

			$valores = array('idgrupo' => $idgrupo, 'nomov' => $nomov);
			//Carga de los modelos 
			$this->load->model('colmenas_model','colmenas');		
			$reversatrans = $this->colmenas->reversatrans($valores);
			$this->validaCode($reversatrans);
			if ($reversatrans['code'] == 200){
				// Aqui enviar correo electrónico
			}
		 }
	}



		
	public function add_aplica_col_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//Carga de los modelos 
		$this->load->model('colmenas_model','colmenas');		
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$datos['grantotal'] = (double)str_replace(",","",$datos['grantotal']);
		$datos['totalcompara']= (double)str_replace(",","",$datos['totalcompara']);
		$fecha_pago = $datos['fecha_pago'];
		$fecha = explode("/", $valores['fecha']); 
		$datos['fecha'] =  $fecha[2] .'-'.$fecha[1].'-'.$fecha[0] ;
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('colmenas_put') == TRUE) {
			$pag = array();
			foreach($datos as $key => $value) {
				if (is_array($value)) {
					foreach($value as $key2 => $value2)	{
						$pag[$key2][$key] = $value2;
					}
				}
			}

			$edocta= array();
			$edocta['autorizacion'] = $datos['autorizacion'];
			$edocta['fecha'] = $datos['fecha'];
			$edocta['hora'] = $datos['hora'];

			if (array_key_exists("vale",$datos)) {
				$edocta['vale'] = $datos['vale'];
			}else {
				$edocta['vale'] = $datos['vales'];
			}
			$edocta['semana'] = $datos['semana'];
			$edocta['caja'] = $datos['caja'];
			$edocta['deposito'] = (double)str_replace(",","",$datos['deposito']);;
			$edocta['idgrupo'] = $datos['idgrupo'];

			$updatetrans = $this->colmenas->transacol($pag, $edocta, $fecha_pago);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}


	public function catAIV_get(){
//		$iduser = $this->ion_auth->user()->row()->id;		
//		$anio = date("Y"); 
//		$semana = date("W"); 
		$asistencia = $this->base->querySelect("select id as value, nombre  as name from col.cat_asistencia where id <= 4 order by id", TRUE);
		$incidencia= $this->base->querySelect("select id as value, nombre  as name from col.cat_asistencia where id>4 and id <9 order by id", TRUE);
		$incidencia_det= $this->base->querySelect("select idsec as value, sancion  as name, id  from col.cat_incidencias order by id, idsec", TRUE);
//		$vales = $this->base->querySelect("select vale as value, vale  as name from ".$this->esquema."promotor_vales where idpromotor=".$iduser." and anio =".$anio." and semana =".$semana , TRUE);
		if (!$incidencia){
			$incidencia = array();
		}
//		if (!$vales){
//			$vales = array();
//		}

		if ($asistencia) {
			$data = array("status"=>"OK",
			"code" => 200,
			"asistencia" => $asistencia,
			"incidencia" => $incidencia,
			"incidencia_det" => $incidencia_det,
//			"vales" => $vales,
			"message"=>"Registro Obtenidos correctamente!"
			);
		}else{
			$data = array("status"=>"ERROR",
			"code" => 404,
			"asistencia" => $asistencia,
			"incidencia" => $incidencia,
//			"vales" => $vales,
			"message"=>"Registro inexistente!"
			);
		}
		$this->returnData($data);
	}
	

   public function lista_promotores_get() {
		$idsuc = $this->session->userdata('sucursal_id');
		$empresa = strtoupper(substr($this->esquema,0,3));
		$sfil ="";
		if (!$this->ion_auth->in_group('gerencial')){
			$iduser = $this->ion_auth->user()->row()->id;
			$sfil = " and idpromotor = ".$iduser;
		}
		$data = $this->base->querySelect("SELECT a.idpromotor as value, a.promotor as name FROM col.v_colmenas_directorio as a  where empresa ='".$empresa."' and idpromotor <> 100 ".$sfil." group by a.idpromotor, a.promotor order by a.promotor ", true);

		if ($data) {
			$data = array("status"=>"OK",
				"code" => 200,
				"message" => "Registro obtenidos correctamente!",
				"result" => $data,
			);
		}else{
			$data = array("status"=>"ERROR",
			"code" => 404,
			"message"=>"Registro inexistente!",
			"result" => [],
			);
		}
		$this->returnData($data);

   }

	public function promotores_get(){
		$anio = $this->uri->segment(4);
		$semana = $this->uri->segment(5);
		$idsuc = $this->session->userdata('sucursal_id');
		$empresa = strtoupper(substr($this->esquema,0,1));
		$data = $this->base->querySelect("SELECT a.idpromotor, a.nompromotor, a.dia, (('".$anio."-01-01'::DATE + INTERVAL '".$semana." WEEK') - (9 - a.dia || ' DAY')::interval)::date as fecha, case when b.vale is null then 0 else b.vale end as vale
				FROM col.v_promotor_grupo as a left join ".$this->esquema."promotor_vales as b on b.idpromotor = a.idpromotor and b.fecha::date =(('".$anio."-01-01'::DATE + INTERVAL '".$semana." WEEK') - (9 - a.dia || ' DAY')::interval)::date where a.idsucursal ='".$idsuc."' and a.dia <> 6 group by a.idpromotor, a.nompromotor, a.dia, b.vale order by a.dia, a.horainicio;", true);
		if ($data) {
			$asignado = false;
			if (count($data >0)) {
				if ($data[0]['vale'] != 0){
					$asignado = true;
				}
			}
			$data = array("status"=>"OK",
				"code" => 200,
				"message" => "Registro obtenidos correctamente!",
				"result" => $data,
				"asignado" => $asignado
			);
		}else{
			$data = array("status"=>"ERROR",
			"code" => 404,
			"message"=>"Registro inexistente!",
			"result" => [],
			"asignado" => true
			);
		}
		$this->returnData($data);
	}




	






	public function colmenas_get(){
		$iduser = $this->ion_auth->user()->row()->id;
		$sfil ="";
		if (!$this->ion_auth->in_group('gerencial')){
			$sfil = "and idpromotor = ".$iduser;
		}

		$colmenas= $this->base->querySelect("SELECT a.idcolmena as value, (numero::text || ' ' || a.nombrecolmena) as name, dia_text,  horainicio, nompromotor, direccion || ', ' || d_asenta || ', ' || d_mnpio as direccion FROM col.v_promotor_grupo as a 
			where a.idsucursal ='".  $this->session->userdata('sucursal_id') ."'".$sfil."  group by a.idcolmena, a.numero::text || ' ' || a.nombrecolmena, dia_text,  horainicio, nompromotor, direccion || ', ' || d_asenta || ', ' || d_mnpio  order by a.idcolmena asc", TRUE);
		if ($colmenas) {
			$data = array("status"=>"OK",
				"code" => 200,
				"message" => "Registro obtenidos correctamente!",
				"catcolmenas" => $colmenas
			);
		} else {
			$data = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Error al tratar de consultar",
				"catcolmenas" => []
				);
		}
		$this->returnData($data);	
	}



		
	public function add_vales_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		//Carga de los modelos 
		$this->load->model('colmenas_model','colmenas');		
		//Creacion del arreglo para el almacenamiento se ejecuta del helper general
		$datos = fn_extraer($valores,'N');

		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('vales_put') == TRUE) {
			$pag = array();
			foreach($datos as $key => $value) {
				if (is_array($value)) {
					foreach($value as $key2 => $value2)	{
						$pag[$key2][$key] = $value2;
					}
				}
			}
			$enca = array();
			$enca['anio'] = $datos['anio'];
			$enca['semana'] = $datos['semana'];
			$enca['vale'] = $datos['novale'];
			$updatetrans = $this->colmenas->transacvales($pag, $enca);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}


      public function get_acreditadosbycolmena_get() {
		$idcolmena = $this->uri->segment(4);
		$anio = $this->uri->segment(5);
		$semana = $this->uri->segment(6);
		$this->load->model('colmenas_model','colmenas');		
		$record = $this->base->querySelect("select id from  col.colmenas_asistencia where semana = ".$semana." and anio = ".$anio."  and idcolmena = ".$idcolmena, true);
		if ($record) {
			$result = $this->fncFindAsistencia($idcolmena, $anio, $semana);
		}else {
			$data  = [];
			$data['idcolmena'] = $idcolmena;
			$data['anio'] = $anio;
			$data['semana'] = $semana;
			$add = $this->colmenas->addAsisColmena($data);
			if ($add['code'] == 200) {
				$result = $this->fncFindAsistencia($idcolmena, $anio, $semana);
			}else {
					$result = array("status"=>"ERROR",
						"code" => 404,
						"message"=>"Registro no se agregó correctamente!");

			}


					

		}

		
		$this->returnData($result);
	}


	private function fncFindAsistencia($idcolmena, $anio, $semana) {
		$colmena = $this->base->querySelect("select * from  col.colmenas_asistencia where semana = ".$semana." and anio = ".$anio."  and idcolmena = ".$idcolmena, true);

		$solingre = [];
		$nuevoingre = [];
		$reingre = [];
		if ($colmena){
			$solingre = $this->base->querySelect("select a.idpersona as value, b.acreditado || ' - ' || a.idpersona as name from col.colmenas_solingre as a join public.get_acreditados as b on b.idpersona = a.idpersona where id =".$colmena[0]['id'], true);
			$nuevoingre = $this->base->querySelect("select a.idpersona as value, b.acreditado || ' - ' || a.idpersona as name from col.colmenas_nuevoingre as a join public.get_acreditados as b on b.idpersona = a.idpersona where id =".$colmena[0]['id'], true);
			$reingre = $this->base->querySelect("select a.idpersona as value, b.acreditado || ' - ' || a.idpersona as name from col.colmenas_reingre as a join public.get_acreditados as b on b.idpersona = a.idpersona where id =".$colmena[0]['id'], true);
		}
		$data = $this->base->querySelect("select a.grupo_nombre,a.idgrupo, a.nombre, a.idacreditado, a.acreditadoid, a.idanterior, a.orden, a.cargo_colmena, a.cargo_grupo, b.asistencia, b.incidencia, b.opcion, b.verificacion, b.niveldesea, b.descrip from  ".$this->esquema."get_acreditado_grupo as a 
			left join col.asistencia as b on b.acreditadoid = a.acreditadoid and b.idgrupo = a.idgrupo and b.semana = ".$semana." and b.anio = ".$anio."  where a.idcolmena = ".$idcolmena."  order by a.grupo_nombre, a.orden", true);
		$mab = $this->base->querySelect("select map from col.colmenas where idcolmena = ".$idcolmena, true);
		$mab = $mab[0]['map'];
		if ($data) {
				$result = array("status"=>"OK",
				"code" => 200,
				"message"=>"Registros obtenidos correctamente!",
						"grupo_acreditados"=> $data,
						"colmena" => $colmena,
						"solingre" => $solingre,
						"nuevoingre" => $nuevoingre,
						"reingre" => $reingre,
						"mab" => $mab
				);
		} else {
			$result = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registros no encontrados!");
		}
		return $result;
	}

	

      public function get_promotorbycolmena_get() {
		$idpromotor = $this->uri->segment(4);
		$anio = $this->uri->segment(5);
		$semana = $this->uri->segment(6);
		$empresa = strtoupper(substr($this->esquema,0,3));

/* 		$data = $this->base->querySelect("select a.idcolmena, a.numero, a.nombre, b.mujeres, b.entiempo, b.participa, b.tema, b.imparte, b.asistencia, b.inci_fp, b.inci_ff, b.inci_f, b.verificaciones, b.sol_ingreso, b.ingreso_nuevo, b.reingreso, 
		b.renuncias, b.mab_entrega, b.mab_pedido from col.v_colmenas_directorio as a left join col.info_semanal as b on b.idcolmena = a.idcolmena and (b.anio = ".$anio." or b.anio is null) and 
		(b.semana = ".$semana." or b.semana is null) where empresa ='".$empresa."' and idpromotor = ".$idpromotor."  order by a.dia, a.horainicio", true);
 */

		$data = $this->base->querySelect("SELECT * from col.get_info_semanal(".$anio.",".$semana.",".$idpromotor.",'".$empresa."')", true);
		if ($data) {
				$result = array("status"=>"OK",
				"code" => 200,
				"message"=>"Registros obtenidos correctamente!",
                        "grupo_acreditados"=> $data
				);
		} else {
			$result = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registros no encontrados!");
		}
		$this->returnData($result);
	}



	public function add_asistencia_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$this->load->model('colmenas_model','colmenas');		
		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $datos );
		//Valida las reglas 
	 	if ($this->form_validation->run('asistencia_put') == TRUE) {
			$pag = $datos['datos'];
			$edocta= array();
			$edocta['idcolmena'] = $datos['idcolmena'];
			$edocta['anio'] = $datos['anio'];
			$edocta['semana'] = $datos['semana'];

			$updatetrans = $this->colmenas->asistencia($pag, $edocta);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}



	public function update_asistencia_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$this->load->model('colmenas_model','colmenas');		
		$datos = fn_extraer($valores,'N');
		$updatetrans = $this->colmenas->update_asistencia($datos);
		$this->validaCode($updatetrans);
	}



	public function data_colmena_put() {
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$this->load->model('colmenas_model','colmenas');		
//		$datos = fn_extraer($valores,'N');
		//Validacion de los datos del formulario
		$this->form_validation->set_data( $valores );
		//Valida las reglas 
		
	 	if ($this->form_validation->run('asistencia_put') == TRUE) {
			$updatetrans = $this->colmenas->asisColmena($valores);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}


      public function getcalifica_get() {
		$idcredito = $this->uri->segment(4);
		$idacreditada = $this->uri->segment(5);
		if ($idcredito == -1) {
   			$data = $this->base->querySelect("SELECT to_char(fecha::date, 'dd-mm-yyyy') as fecpago,* from ".$this->esquema."get_califica_acreditada( ".$idacreditada.")", true);			
		}else{
   			$data = $this->base->querySelect("SELECT to_char(fecha::date, 'dd-mm-yyyy') as fecpago,* from ".$this->esquema."get_califica_credito( ".$idcredito.")", true);			
		}
		if ($data) {
				$result = array("status"=>"OK",
				"code" => 200,
				"message"=>"Registros obtenidos correctamente!",
                "califica"=> $data
				);
		} else {
			$result = array("status"=>"ERROR",
				"code" => 404,
				"message"=>"Registros no encontrados!");
		}
		$this->returnData($result);
	}



	public function repsemanal_post() {
		$anio = $this->uri->segment(5);
		$semana = $this->uri->segment(6);

		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->load->model('colmenas_model','colmenas');
		$datos = fn_extraer($valores,'N');
		$datos['anio'] = $anio;
		$datos['semana'] = $semana;
		$this->form_validation->set_data( $datos );
	 	if ($this->form_validation->run('repsemanal_post') == TRUE) {
			$updatetrans = $this->colmenas->repsemana_add($datos);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}




	public function repsemanal_put() {
		$anio = $this->uri->segment(5);
		$semana = $this->uri->segment(6);

		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$this->load->model('colmenas_model','colmenas');
		$datos = fn_extraer($valores,'N');
		$datos['anio'] = $anio;
		$datos['semana'] = $semana;
		$this->form_validation->set_data( $datos );
	 	if ($this->form_validation->run('repsemanal_post') == TRUE) {
			$updatetrans = $this->colmenas->repsemana_update($datos);
			$this->validaCode($updatetrans);
		 }else {
			$this->validaForm();
		 }
	}

}