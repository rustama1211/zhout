<?php 
if  (! defined('BASEPATH')) exit('No direct Scripts Access Allowed');
class Video_lib
{
	var $_CI = NULL;
	function __construct()
	{
		$this->_CI =& get_instance();
	}
	
	function get_video()
	{
		$_string = $this->_CI->load->view('zhout/video_widget','',TRUE);
		return $_string;
	}
}
?>   
