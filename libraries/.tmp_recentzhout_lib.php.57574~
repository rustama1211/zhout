<?php 
if  (! defined('BASEPATH')) exit('No direct Scripts Access Allowed');
class Recentzhout_lib
{
	var $_CI = NULL;
	function __construct()
	{
		$this->_CI =& get_instance();
	}
	
	function get_recentzhout()
	{
		$_string = $this->_CI->load->view('zhout/recentzhout_widget','',TRUE);
		return $_string;
	}
}
?>   
