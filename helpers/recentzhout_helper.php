<?php if(! defined('BASEPATH')) die('No Direct script access allowed');
if(!function_exists('recentzhout'))
{
	function recentzhout()
	{
		$CI =& get_instance();
		$CI->load->library('zhout/recentzhout_lib');
		return $CI->recentzhout_lib->get_recentzhout();
	}
}
?>