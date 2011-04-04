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
				//need to handling where the user will be redirected ?
					echo '0';		
				}
				else
				{
					echo $_data_return_html;
				}
				
				
			}

		}
		
		function add_comment()
		{
			$_id_zhout = $this->input->post('id_zhout');
			$_comment_content = $this->input->post('comment_content');
			$_id_member = $this->input->post('id_member');
	
			if ($_id_zhout && $_comment_content && $_id_member)
			{
				$_data_comment = $this->zhout_lib->add_comment($_id_zhout,$_comment_content,$_id_member = (($this->session->userdata('id_member')!= $_id_member)?$this->session->userdata('id_member'):$_id_member));
				$this->zhout_lib->update_attribute_time($_id_zhout);
				echo $_data_comment;		
			}
			
		}
		
		function add_attribute_time()
		{
			$_id_zhout = $this->input->post('id_zhout');
			if($_id_zhout)
			{
				$this->zhout_lib->update_attribute_time($_id_zhout);
			}
		}
		
		// End Type input is POST
		
		function get_dropdown_comment($_id_zhout)
		{
			echo $this->zhout_lib->get_dropdown_comment($_id_zhout).
			'<script type ="text/javascript">'.$this->zhout_lib->text_area_javascript().'</script>';
		}
		
		function show_more_comment($_id_zhout)
		{
			echo $this->zhout_lib->show_more_comment($_id_zhout);
			
		}
		
		function show_more_zhout($_id_member,$_current_active,$_offset)
		{
			//echo $this->zhout_lib->show_more_zhout($_id_member,$_current_active,$_offset);
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
		
		function change_category($_id_member,$_id_category,$_current_active)
		{
			echo $this->zhout_lib->change_category($_id_member,$_id_category,$_current_active);
		}
		
		function current_active($_id_member,$_id_category,$_current_active)
		{
			echo $this->zhout_lib->current_active($_id_member,$_id_category,$_current_active);
		}
		
		/*---------------- AUTO UPDATE URL --------------*/
		
		function auto_update_url($_id_member,$_id_latest_zhout)
		{
			$_data =$this->zhout_lib->get_update_zhout($_id_member,$_id_latest_zhout);
			//$_data = array(0=>'<div>dadd</div>',1=>'<div>dadfsdfsdfd</div>',2=>'<div>rftgtrdtgdgd</div>');
			echo json_encode($_data);
		}
		
		/*---------------- END AUTO UPDATE URL --------------*/
		
		
		
	}
?>
