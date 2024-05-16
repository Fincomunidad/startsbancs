<?php 
if( ! defined('BASEPATH') ) exit('No direct script access allowed');


$config = array(
      'logUser' => array(
            array('field'=>'identify', 'label'=>'Usuario ','rules'=>'trim|required'),
            array('field'=>'password', 'label'=>'password','rules'=>'trim|required'),
      ),
	'solingresogen_put' => array(
            array('field'=>'fechaalta', 'label'=>'Fecha de Alta','rules'=>'trim|required'),
            array('field'=>'nombre1', 'label'=>'Primer Nombre','rules'=>'trim|required|min_length[2]|max_length[75]'),
            array('field'=>'sexo','label'=>'Sexo','rules'=>'trim|required'),
            array('field'=>'fecha_nac','label'=>'Fecha de Nacimiento','rules'=>'required'),
            array('field'=>'idnacionalidad','label'=>'Pais de Nacimiento','rules'=>'trim|required'),
            array('field'=>'paisnac','label'=>'Pais de Nacimiento','rules'=>'trim|required'),
            array('field'=>'edonac','label'=>'Estado de Nacimiento','rules'=>'trim|required'),
            array('field'=>'edocivil','label'=>'Fecha de Nacimiento','rules'=>'trim|required'),
            array('field'=>'escolaridad','label'=>'Escolaridad','rules'=>'trim|required'),
            array('field'=>'rfc','label'=>'RFC','rules'=> array('trim','required','min_length[10]','max_length[13]','regex_match[/^[A-Za-z]{3,4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z0-9\d]{0,3})$/]')),
            array('field'=>'curp','label'=>'CURP','rules'=> array('trim','required','exact_length[18]','regex_match[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z]{6})([A-Za-z0-9]{1})([0-9]{1})$/]')),
            array('field'=>'idactividad','label'=>'Actividad económica','trim|required'),
            array('field'=>'patrimonio','label'=>'Monto de patrimonio','rules'=>'required|numeric'),
            array('field'=>'experiencia','label'=>'Experiencia de actividad','rules'=>'required|integer'),
            array('field'=>'ingresomen','label'=>'Ingreso Mensual','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'ingresomenext','label'=>'Ingreso Extraordinario','rules'=>'required|numeric'),
            array('field'=>'egresomen','label'=>'Egreso Mensual','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'egresomenext','label'=>'Egreso Extraordinario','rules'=>'required|numeric'),
            array('field'=>'dependientes','label'=>'No. de personas dependientes','rules'=>'required|integer'),
            array('field'=>'ahorro','label'=>'Compromiso de ahorro','rules'=>'required|numeric|greater_than[0]'),
		),
        'solingresogensedo_put' => array(
            array('field'=>'fechaalta', 'label'=>'Fecha de Alta','rules'=>'trim|required'),
            array('field'=>'nombre1', 'label'=>'Primer Nombre','rules'=>'trim|required|min_length[2]|max_length[75]'),
            array('field'=>'sexo','label'=>'Sexo','rules'=>'trim|required'),
            array('field'=>'idnacionalidad','label'=>'Pais de Nacimiento','rules'=>'trim|required'),
            array('field'=>'paisnac','label'=>'Pais de Nacimiento','rules'=>'trim|required'),
            array('field'=>'lugnac','label'=>'Pais de Nacimiento','rules'=>'trim|required'),
            array('field'=>'fecha_nac','label'=>'Fecha de Nacimiento','rules'=>'required'),
            array('field'=>'edocivil','label'=>'Fecha de Nacimiento','rules'=>'trim|required'),
            array('field'=>'escolaridad','label'=>'Escolaridad','rules'=>'trim|required'),
            array('field'=>'rfc','label'=>'RFC','rules'=> array('trim','required','min_length[10]','max_length[13]','regex_match[/^[A-Za-z]{3,4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z0-9\d]{0,3})$/]')),
            array('field'=>'curp','label'=>'CURP','rules'=> array('trim','required','exact_length[18]','regex_match[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z]{6})([A-Za-z0-9]{1})([0-9]{1})$/]')),
            array('field'=>'idactividad','label'=>'Actividad económica','trim|required'),
            array('field'=>'patrimonio','label'=>'Monto de patrimonio','rules'=>'required|numeric'),
            array('field'=>'experiencia','label'=>'Experiencia de actividad','rules'=>'required|integer'),
            array('field'=>'ingresomen','label'=>'Ingreso Mensual','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'ingresomenext','label'=>'Ingreso Extraordinario','rules'=>'required|numeric'),
            array('field'=>'egresomen','label'=>'Egreso Mensual','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'egresomenext','label'=>'Egreso Extraordinario','rules'=>'required|numeric'),
            array('field'=>'dependientes','label'=>'No. de personas dependientes','rules'=>'required|integer'),
            array('field'=>'ahorro','label'=>'Compromiso de ahorro','rules'=>'required|numeric|greater_than[0]'),
		),
	'solingresodom_put' => array(
            array('field'=>'direccion1','label'=>'Direccion','rules'=>'trim|required|min_length[3]|max_length[40]'),
            array('field'=>'noexterior','label'=>'No. Exterior','rules'=>'trim|required'),
            array('field'=>'idestado','label'=>'Estado','rules'=>'trim|required'),
            array('field'=>'idmunicipio','label'=>'Municipio','rules'=>'trim|required'),
            array('field'=>'idcolonia','label'=>'Colonia','trim|required'),
            array('field'=>'cp','label'=>'Código Postal','rules'=>'trim|required'),
            array('field'=>'ciudad','label'=>'Ciudad','rules'=>'trim|required'),
            array('field'=>'tiempo','label'=>'Tiempo de Radicar en el domicilio','rules'=>'required|integer'),
            array('field'=>'tipovivienda','label'=>'Tipo de Vivienda','rules'=>'trim|required'),
		),
	'solingresoben_put' => array(
            array('field'=>'nombre1_ben','label'=>'Nombre de Beneficiario','rules'=>'trim|required|min_length[2]|max_length[75]'),
            array('field'=>'rfc_ben','label'=>'RFC del beneficiario','rules'=>'trim|required|min_length[10]|max_length[13]'),
            array('field'=>'idparentesco','label'=>'Parentesco de Beneificario','rules'=>'trim|required'),
            array('field'=>'porcentaje','label'=>'% de participación','rules'=>'required|greater_than[0]|less_than[101]')
		),
	'altasocio_post' => array(
            array('field'=>'idpersona','label'=>'Id solicitud','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idcolmena','label'=>'Colmena','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idgrupo','label'=>'Grupo','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idcolcargo','label'=>'Cargo de colmena','rules'=>'trim|required|integer'),
            array('field'=>'idgrupocargo','label'=>'Cargo del grupo','rules'=>'trim|required|integer'),
			
	),
	'altasocio_put' => array(
            array('field'=>'idpersona','label'=>'Id solicitud','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idcolmena','label'=>'Colmena','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idcolcargo','label'=>'Cargo de colmena','rules'=>'trim|required|integer'),
            array('field'=>'idgrupocargo','label'=>'Cargo del grupo','rules'=>'trim|required|integer'),

	),	
	'aportasocial_post' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'movimiento','label'=>'Movimiento','rules'=>'trim|required'),
            array('field'=>'instrumento','label'=>'Tipo','rules'=>'trim|required'),
            array('field'=>'importe','label'=>'Importe','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'idbancodet','label'=>'Banco','rules'=>'trim|required|integer'),
            array('field'=>'cheque_ref','label'=>'Cheque/Referencia','rules'=>'trim|required'),
            array('field'=>'afavor','label'=>'A favor/Banco','rules'=>'trim|required|min_length[5]')
      ),
	'inversion_post' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'trim|required|integer|greater_than[0]'),
            array('field'=>'importe','label'=>'Importe','rules'=>'trim|required|greater_than[0]'),
            array('field'=>'dias','label'=>'Dias','rules'=>'trim|required|greater_than[0]'),
            array('field'=>'tasa','label'=>'Tasa','rules'=>'trim|required|greater_than[0]'),
            array('field'=>'interes','label'=>'Interes','rules'=>'trim|required|greater_than[0]'),
            array('field'=>'fechafin','label'=>'Fecha termino','rules'=>'trim|required'),
            array('field'=>'total','label'=>'Total','rules'=>'trim|required|greater_than[0]')
	),      
      'bancos_post' => array(
            array('field'=>'idbanco','label'=>'Clave','rules'=>'trim|required|min_length[2]|max_length[3]'),
            array('field'=>'nombre','label'=>'Nombre','rules'=>'trim|required|min_length[2]|max_length[50]'), 
      ),
      'edoctaUpdate_put' => array(
            array('field'=>'id','label'=>'Id','rules'=>'trim|required'),
            array('field'=>'hora','label'=>'Hora','rules'=>'trim|required'),
            array('field'=>'vale','label'=>'Vale','rules'=>'trim|required'),
            array('field'=>'semana','label'=>'Semana','rules'=>'trim|required'),
            array('field'=>'caja','label'=>'Caja','rules'=>'trim|required') 
      ),      	  
      'amortizaciones_put' => array(
            array('field'=>'idcredito','label'=>'id Credito','rules'=>'trim|required|integer'),
            array('field'=>'numero','label'=>'Numero','rules'=>'trim|required|integer'),
      ),
      'creditoindividual_post' => array(
            array('field'=>'idcredito','label'=>'Id Credito inexistente','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'fecha_pago','label'=>'Fecha de Pago','rules'=>'required'),
            array('field'=>'idpagare','label'=>'Pagaré','rules'=>'required'),
            array('field'=>'importepagar','label'=>'Importe a Pagar','rules'=>'required|numeric'),
      ),      	  
      'solicitud_credito_put' => array(
            array('field'=>'idacreditado','label'=>'No de acreditado','rules'=>'required'),
            array('field'=>'idgrupo','label'=>'No grupo','rules'=>'required'),
            //array('field'=>'idsucursal','label'=>'Sucursal','rules'=>'trim|required'),
            array('field'=>'fecha','label'=>'Fecha alta','rules'=>'required'),
            array('field'=>'fecha_pago','label'=>'Fecha pago','rules'=>'required'),            
            array('field'=>'proy_nombre','label'=>'Proyecto nombre','rules'=>'trim|required'),
            array('field'=>'proy_descri','label'=>'Proyecto descripcion','rules'=>'trim|required'),
            array('field'=>'proy_lugar','label'=>'Proyecto lugar','rules'=>'trim|required'),

            //array('field'=>'idproducto','label'=>'Producto','rules'=>'required'),
            array('field'=>'nivel','label'=>'Nivel','rules'=>'required'),
            array('field'=>'monto','label'=>'Monto del credito','rules'=>'required|numeric'),

            //array('field'=>'idpagare','label'=>'Pagare','rules'=>'required'),
            array('field'=>'idchecklist','label'=>'CheckList','rules'=>'required'),
            //array('field'=>'idejecutivo','label'=>'Ejecutivo','rules'=>'required'),
            //array('field'=>'usuario','label'=>'Usuario','rules'=>'required')
      ),
      'solicitud_crediton_put' => array(
            array('field'=>'idacreditado','label'=>'No de acreditado','rules'=>'required'),
            //array('field'=>'idsucursal','label'=>'Sucursal','rules'=>'trim|required'),
            array('fecha_pago2field'=>'fecha','label'=>'Fecha alta','rules'=>'required'),
            array('field'=>'fecha_pago','label'=>'Fecha pago','rules'=>'required'),            
            //array('field'=>'fecha_pago2','label'=>'Fecha pago 2','rules'=>'required'),            
            array('field'=>'proy_nombre','label'=>'Proyecto nombre','rules'=>'trim|required'),
            array('field'=>'proy_descri','label'=>'Proyecto descripcion','rules'=>'trim|required'),
            array('field'=>'proy_lugar','label'=>'Proyecto lugar','rules'=>'trim|required'),
            array('field'=>'proy_observa','label'=>'Proyecto observación','rules'=>'trim|required'),

            array('field'=>'nivel','label'=>'Nivel','rules'=>'required'),
            array('field'=>'monto','label'=>'Monto del credito','rules'=>'required|numeric'),

            array('field'=>'idchecklist','label'=>'CheckList','rules'=>'required'),
            array('field'=>'periodo','label'=>'Periodo','rules'=>'required'),
            array('field'=>'num_pagos','label'=>'Numero de pagos','rules'=>'required')
      ),
      'new_colmenas_put' => array(
            array('field'=>'numero','label'=>'numero','rules'=>'trim|required|integer'),
      ),
      'colmenas_put' => array(
            array('field'=>'totalcompara','label'=>'Total Pago','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'grantotal','label'=>'Suma de Pago','rules'=>'required|numeric|greater_than[0]')
      ),

      'repsemanal_post' => array(
            array('field'=>'idcolmena','label'=>'Colmena','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'anio','label'=>'Año','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'semana','label'=>'Semana','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'mujeres','label'=>'Mujeres','rules'=>'required|numeric|greater_than[0]')
      ),	  
      'asistencia_put' => array(
            array('field'=>'idcolmena','label'=>'Colmena','rules'=>'trim|required|integer'),
            array('field'=>'anio','label'=>'Año','rules'=>'trim|required|integer|greater_than[2018]'),
            array('field'=>'semana','label'=>'Semana','rules'=>'trim|required|integer|greater_than[0]'),
      ),	  
      'vales_put' => array(
            array('field'=>'anio','label'=>'Año','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'semana','label'=>'Semana','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'novale','label'=>'Vale','rules'=>'required|numeric|greater_than[0]')
      ),	  
      'ahorros_put' => array(
            array('field'=>'numero_cuenta','label'=>'Cuenta','rules'=>'required'),
            array('field'=>'idacreditado','label'=>'Acreditada','rules'=>'required')
      ),   
      'ahorrosmov_post' => array(
            array('field'=>'numero_cuenta','label'=>'Número de cuenta','rules'=>'required'),
            array('field'=>'movimiento','label'=>'Tipo de movimiento','rules'=>'required|max_length[1]'),
            array('field'=>'importe','label'=>'Importe','rules'=>'required|numeric|greater_than[0]')
      ),
      'ahorrosret_post' => array(
            array('field'=>'numero_cuenta','label'=>'Número de cuenta','rules'=>'required'),
            array('field'=>'iddestino','label'=>'Destino ','rules'=>'required'),
            array('field'=>'idpagare','label'=>'Número de pagaré','rules'=>'required'),
            array('field'=>'importe','label'=>'Importe','rules'=>'required|numeric|greater_than[0]')
      ),
      'boveda_post' => array(
            array('field'=>'idclave','label'=>'idclave','rules'=>'required'),
      ),
      'bovedamov_post' => array(
            array('field'=>'idmov','label'=>'Id Movimiento','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'movimiento','label'=>'Movimiento','rules'=>'required'),
            array('field'=>'des_ori','label'=>'Destino/Origen','rules'=>'required'),
            array('field'=>'idbanco','label'=>'Caja/Banco','rules'=>'required'),
            array('field'=>'importe','label'=>'Importe','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'grantotal','label'=>'grantotal','rules'=>'required|numeric|greater_than[0]')
      ),
      'bovedamovban_post' => array(
            array('field'=>'idmov','label'=>'Id Movimiento','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'movimiento','label'=>'Movimiento','rules'=>'required'),
            array('field'=>'des_ori','label'=>'Destino/Origen','rules'=>'required'),
            array('field'=>'idbanco','label'=>'Caja/Banco','rules'=>'required')
      ),	  
      'bovedamovcierre_post' => array(
            array('field'=>'idmov','label'=>'Id Movimiento','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'movimiento','label'=>'Movimiento','rules'=>'required'),
            array('field'=>'des_ori','label'=>'Destino/Origen','rules'=>'required'),
            array('field'=>'idbanco','label'=>'Caja/Banco','rules'=>'required'),
            array('field'=>'importe','label'=>'Importe','rules'=>'required|numeric'),
            array('field'=>'grantotal','label'=>'grantotal','rules'=>'required|numeric')
      ),      
      'creditos_dis_put' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'idpagare','label'=>'Pagaré','rules'=>'required'),
            array('field'=>'colmena','label'=>'Colmena','rules'=>'required'),
            array('field'=>'grupo','label'=>'Grupo','rules'=>'required'),
            array('field'=>'monto','label'=>'Monto','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'importe','label'=>'Importe','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'movimiento','label'=>'Movimiento de dispersión','rules'=>'required'),
            array('field'=>'numero_cuenta','label'=>'Cuenta', 'rules'=>'required'),
            array('field'=>'idbancodet','label'=>'Banco', 'rules'=>'required'),
            array('field'=>'cheque_ref','label'=>'Cheque/Referencia','rules'=>'required'),
            array('field'=>'afavor','label'=>'A favor de','rules'=>'required')
      ),
      'creditos_con_put' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'idpagare','label'=>'Pagaré','rules'=>'required'),
            array('field'=>'colmena','label'=>'Colmena','rules'=>'required'),
            array('field'=>'grupo','label'=>'Grupo','rules'=>'required'),
            array('field'=>'monto','label'=>'Monto','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'noxpcomprome','label'=>'noxpcomprome','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'xpcomprometido','label'=>'xpcomprometido','rules'=>'required|numeric|greater_than[0]')
      ),
      
      'cred_entrega_put' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'idpagare','label'=>'Pagaré','rules'=>'required'),
            array('field'=>'colmena','label'=>'Colmena','rules'=>'required'),
            array('field'=>'grupo','label'=>'Grupo','rules'=>'required'),
            array('field'=>'monto','label'=>'Monto','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'numero_cuenta','label'=>'Cuenta', 'rules'=>'required')
      ),
	  
      'prov_config_put' => array(
            array('field'=>'idcredito','label'=>'No de acreditado','rules'=>'required')
      ),
	  
      'seguro_post' => array(
            array('field'=>'idacreditado','label'=>'Acreditado','rules'=>'required|numeric|greater_than[0]'),
            array('field'=>'idpagare','label'=>'Pagaré','rules'=>'required'),
            array('field'=>'colmena_s','label'=>'Colmena','rules'=>'required'),
            array('field'=>'grupo_s','label'=>'Grupo','rules'=>'required'),
            array('field'=>'monto','label'=>'Seguro','rules'=>'required|numeric|greater_than[0]'),
      ),

      'nivel_put' => array(
            array('field'=>'nivel','label'=>'nivel','rules'=>'trim|required|integer'),
      ),

      'productos_post' => array(
        array('field'=>'idproducto','label'=>'IdProducto','rules'=>'required|greater_than[0]'),
        array('field'=>'nombre','label'=>'Nombre','rules'=>'required'),
        array('field'=>'tipo','label'=>'Tipo','rules'=>'required'),
        array('field'=>'minini','label'=>'Monto minimo inicial','rules'=>'required|numeric'),
        array('field'=>'maxini','label'=>'Monto maximo inicial','rules'=>'required|numeric'),
        array('field'=>'movmin','label'=>'Monto minimo','rules'=>'required|numeric'),
        array('field'=>'movmax','label'=>'Monto maximo','rules'=>'required|numeric'),
      ),

      'productos_put' => array(
        array('field'=>'idproducto','label'=>'IdProducto','rules'=>'required|greater_than[0]'),
        array('field'=>'nombre','label'=>'Nombre','rules'=>'required'),
        array('field'=>'tipo','label'=>'Tipo','rules'=>'required'),
        array('field'=>'minini','label'=>'Monto minimo inicial','rules'=>'required|numeric'),
        array('field'=>'maxini','label'=>'Monto maximo inicial','rules'=>'required|numeric'),
        array('field'=>'movmin','label'=>'Monto minimo','rules'=>'required|numeric'),
        array('field'=>'movmax','label'=>'Monto maximo','rules'=>'required|numeric'),
      ),
      'udis_post' => array(
        array('field'=>'fecha','label'=>'Fecha','rules'=>'required'),
        array('field'=>'valor','label'=>'Valor de Udi','rules'=>'required|greater_than[0]'),

      ),
      'creditoaut_put' => array(
            array('field'=>'option', 'label'=>'Opcion ','rules'=>'trim|required'),
      ),
	  
      
)



?>

