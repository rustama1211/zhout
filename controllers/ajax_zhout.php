<?php if (! defined ('BASEPATH')) die ('No Direct scrip access allowed');

	class Ajax_zhout extends Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->library('zhout/zhout_lib');
		}
		
		//Type input is POST
		function add_zhout()
		{
			$_data_zhout =$this->input->post($this->zhout_lib->_ZHOUT_PARAMETER);
			$_id_member = $this->input->post($this->zhout_lib->_ID_MEMBER_PARAMETER);
			if ($_data_zhout && $_id_member)
			{
				$_data_return_html = $this->zhout_lib->add_zhout($this->session->userdata('id_member'),$_data_zhout);
				if($_id_member != $this->session->userdata('id_member'))
				{	
				//need to handling where the user will be directed ?
					echo '0';		
				}
				else
				{
					echo $_data_return_html;
				}
				
				
			}
			echo "gdf";
		}
		
		function add_comment()
		{
			
		}
		
		// End Type input is POST
		
		function get_dropdown_comment($_id_zhout)
		{
			
		}
		
		function show_more_comment($_id_zhout)
		{
			
		}
		
		function show_more_zhout($_id_member,$_current_active,$_offset)
		{
			
		}
		
		function add_wishlist($_id_member,$_id_product)
		{
			
		}
		
		function delete_zhout($_id_member,$_id_zhout)
		{
			
		}
		
		function delete_comment($_id_member,$_id_comment)
		{
			
		}
		
		function change_category($_id_zhout)
		{
			
		}
		
		function last_update($_id_member)
		{
			
		}
		
		function most_active ($_id_member)
		{
			
		}
		
	}
?>
