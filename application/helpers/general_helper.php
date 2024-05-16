<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/*
	*
	*
	*
	*/
	function fn_extraer($valores,$arreglosn) {
		$dat = array();
		foreach ($valores as $key => $value) {
			$dat[$key] = $value;
		}
		array_shift($dat);	
		if ($arreglosn == 'S') {
			$datos = array();
			array_push($datos, $dat);		
			return $datos;
		} else {
			return $dat;
		}
	}


	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}



