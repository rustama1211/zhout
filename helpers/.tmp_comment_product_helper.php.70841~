<?php if(! defined('BASEPATH')) die('No Direct script access allowed');
if(!function_exists('comment_product'))
{
	function comment_product()
	{
		$CI =& get_instance();
		$CI->load->library('zhout/comment_product_lib');
		return $CI->comment_product_lib->get_comment_product();
	}
}
?>