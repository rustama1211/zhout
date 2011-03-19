<?php if (! defined("BASEPATH")) die('No Direct Script Access Allowed');
   
   if (! function_exists('getAddThisJavascript'))
   {
   	function getAddThisJavascript()
	{
		$CI =&get_instance();
		$CI->load->library('zhout/zhout_lib');
		return $CI->zhout_lib->add_this_javascript();
	}
   }
?>
