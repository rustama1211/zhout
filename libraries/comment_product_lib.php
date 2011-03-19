<?php 
if  (! defined('BASEPATH')) exit('No direct Scripts Access Allowed');
class Comment_product_lib
{
	var $_CI = NULL;
	function __construct()
	{
		$this->_CI =& get_instance();
	}
	
	function get_comment_product()
	{
		$_string = $this->_CI->load->view('zhout/comment_product_widget','',TRUE);
		return $_string;
	}
}
?>   