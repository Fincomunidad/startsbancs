<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('render')){
	function render($view, $js=array() ){
		$CI =& get_instance(); 
	   $data = array('content'=>$view,
	   	'js'=> $js
	   	);

		$CI->load->view('template/inicio', $data);

	}
}