<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Model extends CI_Model {

	function __construct(){

	}


	/*
	* Inserta un registro
	* $tabla = Nombre de la tabla
	* $data = Datos a insertar
	* $returnid = TRUE retorna la secuencia de la tabla FALSE no retorna secuencia
	*/
	
	function insertRecordSequence($tabla, $data, $returnid = FALSE, $seq_name) {
		$this->db->set($data);		
		$respuesta = array("status"=>"OK");
		if ($this->db->insert($tabla)){
			if ($returnid === TRUE ) {
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message" => "Elemento insertado correctamente.",
					"newtoken" => $this->security->get_csrf_hash(),
					"insert_id" => $this->db->insert_id($seq_name),
				);
				//return $respuesta;
			} else {
   			  	if ($this->db->affected_rows() > 0) {
					$respuesta = array("status"=>"OK",
						"code" => "200",
						"message"=>"Elemento insertado correctamente",
						"newtoken"=>$this->security->get_csrf_hash()
					);
				} else {
						$respuesta = array("status"=>"ERROR",
						"code" => "404",
						"message"=>"Error al tratar de insertar",
						"newtoken"=>$this->security->get_csrf_hash(),
					);
				}
			}
		}else{
			$error = $this->db->error();
			$pos = strpos($error['message'], 'llave duplicada viola');
			if ($pos > 0){
				$error['message'] ='ERROR: El registro existe no es posible agregarlo!';
			}
			$respuesta =  array("status"=>"ERROR",
				"code" => "409",
                "message"=> $error!=""?$error['message']:"Error en base de datos al insertar",
                 "message_error"=>$error['message'],
//                "message_error"=> $this->db->_error_message(),
 //               "message_code"=> $this->db->_error_number(),
                "newtoken"=>$this->security->get_csrf_hash()
                );
		}

		return $respuesta;

	}

	/*
	* Inserta un registro
	* $tabla = Nombre de la tabla
	* $data = Datos a insertar
	* $returnid = TRUE retorna la secuencia de la tabla FALSE no retorna secuencia
	*/
	function insertRecord($tabla, $data, $returnid = FALSE) {
		$this->db->set($data);		

		if ($this->db->insert($tabla)){
			if ($returnid === TRUE ) {
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Elemento insertado correctamente",
					"newtoken"=>$this->security->get_csrf_hash(),
					"insert_id"=> $this->db->insert_id()
				);
			} else {
   			  	if ($this->db->affected_rows() > 0) {
					$respuesta = array("status"=>"OK",
						"code" => "200",
						"message"=>"Elemento insertado correctamente",
						"newtoken"=>$this->security->get_csrf_hash()
					);
				} else {
					$respuesta = array("status"=>"ERROR",
						"code" => "404",
						"message"=>"Error al tratar de insertar",
						"newtoken"=>$this->security->get_csrf_hash(),
					);
				}
			}
		}else{
			$error = $this->db->error();
			$pos = strpos($error['message'], 'llave duplicada viola');
			if ($pos > 0){
				$error['message'] ='ERROR: El registro existe no es posible agregarlo!';
			}
			$respuesta =  array("status"=>"ERROR",
				"code" => "409",
                "message"=> $error!=""?$error['message']:"Error en base de datos al insertar",
                 "message_error"=>$error['message'],
//                "message_error"=> $this->db->_error_message(),
 //               "message_code"=> $this->db->_error_number(),
                "newtoken"=>$this->security->get_csrf_hash()
                );
		}



		return $respuesta;
	}


	/*
	* Seleccion un o varios registros segun la consulta
	* $tabla = Nombre la tabla (con esquema)
	* $select = consulta de los campos 
	* $where = filtro de la tabla de los campos se envia como arreglo 
	* $limit= no. de registros a mostrar, 
	* $offset= no. de pagina, 
	* $group_by= group by de postgres se envia como arreglo, 
	* $order_by = order by de postgres se envia como arreglo, 
	* $like =like  de postgres se envia como arreglo, 
	* $whereor=filtro con (OR) en conjunto con $where de la tabla se envia como arreglo 
	* $returnData= TRUE retorna los datos FALSE retorna un arreglo con los datos y codigo
	*/

	function selectRecord($tabla, $select, $join=array(), $where=array(), $limit=null, $offset=null, $group_by=array(), $order_by = array(), $like =array(), $whereor=array(), $returnData=FALSE, $where_in =array()){
		if (!empty($select)){
			$this->db->select($select);
		}	
		if (!empty($join)){
			foreach ($join as $key => $valor){
				$this->db->join($key, $valor);
			}
		}		
		if (!empty($where)){
			$this->db->where($where);
		}
		if (!empty($whereor)){
			$this->db->or_where($whereor);
		}
		if (!empty($where_in)){
			foreach ($where_in as $key => $value) {
				$this->db->where_in($key, $value);
			}
		}


		if (!empty($order_by)){
			foreach ($order_by as $key => $value) {
				$this->db->order_by($value['campo'], $value['direccion']);
			}
		}
		if (!empty($group_by)){
			$this->db->group_by($group_by);
		}		
		if (!empty($like)){
			$i =0;
			foreach ($like as $key => $value) {
				if($i==0){
					$this->db->like($key,$value);
				}else{					
					$this->db->or_like($key,$value);
				}
				$i=1;
			}
		}
		$query =$this->db->get($tabla, $limit, $offset);


		if ($query->num_rows()>0){
			if ($returnData == TRUE) {
				return $query->result_array();
			}else {
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Registro(s) obtenido(s) correctamente",
					"result" => $query->result_array()
				);
			}
		}
		else{
			if ($returnData == TRUE) {
				return FALSE;
			}else{
				$respuesta = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error al tratar de obtener los datos"
				);
			}		
		}
		return $respuesta;
	}






	/*
	* Seleccion un o varios registros segun la consulta directa 
	* $query = Nombre la consulta (con esquema)
	* $returnData= TRUE retorna los datos FALSE retorna un arreglo con los datos y codigo
	*/

	function querySelect($query, $returnData=FALSE, $nameData="result"){
		$query =$this->db->query($query);
		if ($query->num_rows()>0){
			if ($returnData == TRUE) {
				return $query->result_array();
			}else {
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Registro(s) obtenido(s) correctamente",
				);
				$respuesta[$nameData] = $query->result_array();
			}
		}
		else{
			if ($returnData == TRUE) {
//				return FALSE;
				return [];
			}else{
				$respuesta = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Sin Registro(s) para mostrar"
				);
				$respuesta[$nameData] = [];
			}		
		}
		return $respuesta;
	}






	/*
	* Inserta un registro
	* $tabla = Nombre de la tabla
	* $data = registro a actualizar
	* $where = filtro del registro a actualizar
	* $isarray = TRUE actualiza en batch FALSE un registro  
	*/
	function updateRecord($tabla, $data, $where, $isarray, $where_in=array()) {
		if ($isarray == 1){
			$update = $this->db->update_batch($tabla, $data, $where);
//			$update = $this->db->replace($tabla, $data);
		}else {
			if (!empty($where_in)){
    			$this->db->where_in($where_in);
			}
			$update = $this->db->update($tabla, $data, $where);
		}
		if ($update) {
			$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Registro actualizado correctamente",
					"newtoken"=>$this->security->get_csrf_hash()
			);			
		}else {
			$respuesta =  array("status"=>"ERROR",
				"code" => "409",
				"message"=> "Error de base de datos al tratar de actualizar",
				"newtoken"=>$this->security->get_csrf_hash()
			);
		}
		return $respuesta;
	}


	/*
	* Elimina Registro
	* $tabla = Nombre de la tabla
	* $where = Filtro del registro a eliminar
	*/
	public function deleteRecord($tabla, $where){
		if ($this->db->delete($tabla, $where)) {
			if ($this->db->affected_rows() >0) {
				$respuesta = array("status"=>"OK",
					"code" => "200",
					"message"=>"Registro(s) eliminado(s) correctamente",
					"registros" => $this->db->affected_rows(),
					"newtoken"=>$this->security->get_csrf_hash()
				);
			}else {
				$respuesta = array("status"=>"ERROR",
					"code" => "404",
					"message"=>"Error al tratar de eliminar",
					"newtoken"=>$this->security->get_csrf_hash(),
				);
			}
		}else{
			$respuesta =  array("status"=>"ERROR",
				"code" => "409",
				"message"=> "Error de base de datos al tratar de eliminar",
				"newtoken"=>$this->security->get_csrf_hash()
			);
			
		}
		return $respuesta;
	}



	public function transaction($sqlTrans, $ValideLast = false) {
		$validate = 1;
		$errtrans = 1;
		$idmov = 0;
		$encuentra = 0;
		$noquery =1;

		$consultas="";
		$this->db->trans_begin();
		foreach ($sqlTrans as $key => $value) {
			$sql = $value;
			$val = "?";
			/// Busca si tiene un '?' que reemplazar por el idmov 
			$encuentra = stripos($sql, $val);
			if ($encuentra > 0  && $idmov > 0 ) {
			   	$sql = str_replace($val,$idmov,$sql);
			}
			$query = $this->db->query($sql);
			$consultas = $consultas.' '. $sql;
			// Busca si existe un insert para retorna un ID
			$encuentra = stripos($sql, "RETURNING");
			if ($encuentra > 0 ) {
				if ($idmov == 0) {
					$idmov = $this->db->insert_id();
				}
			}
			//Valida si se realizo la transaccion en caso contrario tendria que 
			//enviar mensaje al cliente sobre tal situación	
			$record = $this->db->affected_rows();
		    if ($record == 0){
  	  		    $errtrans = 0;
				break;
		    }
			$noquery++;
		}
//		$id = $this->db->insert_id();

		//Valida el último query si se realizó para completar la trasnacción
		//podria ser la actualizacion de un numero de movimiento 
		if ($ValideLast == 1) {
			$record = $this->db->affected_rows();
		   if ($record == 0){
				$validate = 0;	
		   }
		}

		if ($this->db->trans_status() === FALSE || $validate == 0 || $errtrans == 0)
		{
			$error = $this->db->error();
			$pos = strpos($error['message'], 'llave duplicada viola');
			if ($pos > 0){
				$error['message'] ='ERROR: El registro existe no es posible agregarlo!';
			}

			$this->db->trans_rollback();
			$err = $errtrans == 0?"1":"0";
			$message = $errtrans==0?"Error en transacción!. Error CX001 ":"Error en transacción!. Error CX002";
			$respuesta = array("status"=>"ERROR",
				"code" => "404",
				"message"=> $message,
				"newtoken"=>$this->security->get_csrf_hash(),
				"err" => $err,
				"message_error"=>$error['message'],
				"noquery" => $noquery,
				"consultas"  => $consultas
			);				
		}else{
			$this->db->trans_commit();
			$respuesta = array("status"=>"OK",
				"code" => "200",
				"registros"=> $idmov,
				"message"=>"Transacción exitosa!",
				"newtoken"=>$this->security->get_csrf_hash()
			);	
		}
		return $respuesta;
	}








	/*
	* Retorna de una tabla un bloque de datos en paginación
	* $tabla = Nombre de la tabla
	* $pagina = No. de la página 
	* $por_pagina = no. de registro a presentar en la paginación
	* $order_by = Ordenar los campos dados en un array
	*/
	public function selectPage($tabla, $pagina, $por_pagina, $order_by= array()){
		 if (!isset($pagina)){
            $pagina = 1;
        }
		if (!isset( $por_pagina)){
			$por_pagina = 15;
		}

        if ($por_pagina <= 0) {
            $por_pagina = 15;
        }
		$cuantos = $this->db->count_all ( $tabla  );
		$total_paginas = ceil($cuantos / $por_pagina);
		if ($pagina > $total_paginas){
			$pagina = $total_paginas;
		}
		$desde = $pagina * $por_pagina;
		if ($pagina +2 >= $total_paginas ) {
			$pag_siguiente = $total_paginas;
		}else {
			$pag_siguiente = $pagina + 2;
		}

		if ($pagina < 1) {
			$pag_anterior = 1;
		}else{
			$pag_anterior = $pagina;
		}
		if (!empty($order_by)){
			foreach ($order_by as $key => $value) {
				$this->db->order_by($value['campo'], $value['direccion']);
			}
		}

		if ($pagina + 1 > $total_paginas){
			$pag_actual = $total_paginas;
		}else {
			$pag_actual = $pagina+1;
		}		

		$query = $this->db->get($tabla, $por_pagina, $desde);
		if ($query) {
			$respuesta = array(
				'status' => 'OK',
				'code' => 200,
				'inicio_registro' => $desde,
				'final_registro' => $por_pagina,
				'total_registros' => $cuantos,
				'total_paginas' => $total_paginas,
				'pag_actual' => $pag_actual,
				'pag_siguiente' => $pag_siguiente,
				'pag_anterior' => $pag_anterior,
				'datos' => $query->result()
			);
		}else {
			$respuesta = array("status"=>"ERROR",
				"code" => "404",
				"message"=>"Error al tratar de obtener la paginación"
				);
		}
		return $respuesta;
	}


	/*
	* Retorna el no de registros de una tabla
	*/
	function contar($tabla){
		$query = $this->db->get($tabla);
		return $query->num_rows();
	}	
	

	/*
	* Genera las consultas desde un array enviado
	* $queries = Array 
	* Ejemplo 
		$query2 ="select * from fin.creditos where idcredito = ?data.idcredito and idacreditado = ?data.idsocia";
		$queries = array ("data" => $query,
				  		array("data2" => $query2));
	*/
	function queriesSelect($queries){
		$respuesta = array("status"=>"OK",
		"code" => "200",
		"message"=>"Registro(s) obtenido(s) correctamente",		
		);
		foreach ($queries as $key => $value){
			$sql = $value;
			if (is_array($value)){
			   foreach($value as $k => $v){
					$this->setRespuesta($respuesta, $v, $k, true );
				}
			}else {
				$this->setRespuesta($respuesta, $value, $key);
			}
		}
		return $respuesta;
	}



	
	/*
	   Ejecucion de la consulta 
	   $respuesta 
	   $v registro
	   $k nombre del array a integrar en la $respuesta
	   $isArray boolean que determina si la consulta es integrado 
	   por cada registro de otra consulta (subconsulta)
	*/
	function setRespuesta(&$respuesta, $v, $k, $isArray = false ){
		$arrayWhere = $this->findWhere($v);
		if ($arrayWhere != []){
				$data = $respuesta[$arrayWhere[0]["data"]];
				$nameData = $arrayWhere[0]["data"];
				if ($isArray){
					foreach($data as $keydata => $valuedata){
						$sql = $this->modifySQL($v, $arrayWhere, $valuedata);
						$query = $this->querySelect($sql, TRUE);
						$respuesta[$nameData][$keydata][$k] = $query;
					}
				}else {
					$record =  $respuesta[$arrayWhere[0]['data']][0];
					$sql = $this->modifySQL($value, $arrayWhere, $record);
					$query = $this->querySelect($sql, TRUE);
					$respuesta[$k] = $query;
				}

		}else {
			$query = $this->querySelect($v, TRUE);
			$respuesta[$k] = $query;

		}

	}

	/*
      Busca dentro de la consulta dada los datos a reemplazar y
	  los guarda en un array
	  $v = consulta sql
	*/
    function findWhere($v){
		$init = 0; 
		$count = 0;
		$newWhere = $v;
		$data = [];
		$replace = "";
		while (true):
			$findwhere = strpos($v, "?", $init);
			if ($findwhere >0) {
				$cadena = substr($v, $findwhere + 1);
				$where = explode(".", $cadena);
				$field = explode(" ", $where[1]);
				$replace = "?".$where[0].".".$field[0];
				array_push($data, array("sql" => $replace, "data" => $where[0], "field" => $field[0]));
			}
			$init= $findwhere + 1;
			if ($findwhere  == 0  || $count > 10) {
				break;
			}
			$count = $count + 1;
		endwhile;
		return $data;
	}


	/*
      Reemplaza en la consulta 
	  $v = consulta sql
	  $arrayWhere = array con los valores a reemplazar
	  $valuedata = registro donde se obtiene los valores que se 
	  utilizarn para reemplazarlo en la consulta
	*/
	function modifySQL($v, $arrayWhere, $valuedata){
		$newWhere = $v;
		foreach($arrayWhere as $key => $value) {
			$valor = $valuedata[$value['field']];
			$replace = $value['sql'];
			$newWhere = str_replace($replace, $valor, $newWhere);
		}
		return $newWhere;
	}

		
	
}