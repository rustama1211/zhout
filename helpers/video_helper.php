<?php if(! defined('BASEPATH')) die('No Direct script access allowed');
if(!function_exists('video'))
{
	function video()
	{
		$CI =& get_instance();
		$CI->load->library('zhout/video_lib');
		return $CI->video_lib->get_video();
	}
}
?>