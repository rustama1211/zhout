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
	
	function get_comment($id_stuff)
	{
		// Model
		$this->_CI->load->model('product/model_product');
		
		// Helper
		$this->_CI->load->helper('directory');
		$this->_CI->load->helper('image/image');
		
		$_data['commentproduct']		= $this->_CI->model_product->get_comment($id_stuff);
		//$_data['idzhopiezout']			= $this->_CI->model_product->get_id_zhout($id_stuff);
		
		if($_data['commentproduct']->num_rows() > 0){
			foreach ($_data['commentproduct']->result() as $row)
			{
				$_data['commentpict'][$row->id_member] = directory_map(getcwd().'/assets/zhopie/userfiles/'.$row->id_member.'/profile_picture');
				
			}
		}
		
		$this->_CI->bep_site->set_js_block("
			function addcoment(id_zhout)
			{
				var write_comment = $('#id_write_comment').val();
				if(write_comment!='')
				{
					$.ajax({
						url:'".site_url()."/zhout/insert_comment/'+id_zhout+'/'+write_comment+'',
						success: function(msg){
							window.location.href=msg;
						}
					});
					return;
				}
			}
			"
		);
		$_string = $this->_CI->load->view('zhout/comment_product_widget',$_data,TRUE);
		return $_string;
	}
}
?>   