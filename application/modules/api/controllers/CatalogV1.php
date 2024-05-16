<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'/modules/api/controllers/BaseV1.php');

class CatalogV1 extends BaseV1 {
	public function __construct()
	{
		parent::__construct();

	}

    
    public function productos_get(){
		$query =  "SELECT idproducto, nombre, tipo, activo, top, minini, maxini, movmin, movmax, nomov, case when monudi = true then 1 else 0 end as monudi, tasa, saldomin, saldomax, comret, comision, aplicaisr FROM public.productos order by idproducto;";
		$acre = $this->base->querySelect($query, FALSE);	
		$this->returnData($acre);
    }


    public function productos_post() {
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->insertData('productos', $valores, 'productos_post');
    }

    public function productos_put() {       
		$valores = $this->put('data')?$this->put('data', TRUE):array();
		$where = array('idproducto' => $valores["idproducto"]);
        $valores['minini'] = (double)str_replace(",","",$valores['minini']);
        $valores['maxini'] = (double)str_replace(",","",$valores['maxini']);
        $valores['movmin'] = (double)str_replace(",","",$valores['movmin']);
        $valores['movmax'] = (double)str_replace(",","",$valores['movmax']);
		$this->updateData('productos', $valores, 'productos_put', $where, false, $where);

    }
	
	
    public function udis_get(){
		$query =  "SELECT id, fecha, valor FROM public.udis order by fecha desc limit 30;";
		$udis = $this->base->querySelect($query, FALSE);	
		$this->returnData($udis);
    }


    public function udis_post() {
		$valores = $this->post('data')?$this->post('data', TRUE):array();
		$this->insertData('udis', $valores, 'udis_post');
    }


	
	

}
