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
		// Model
		$this->_CI->load->model('zhout/model_zhout');
		
		// Helper
		$this->_CI->load->helper('directory');
		$this->_CI->load->helper('image/image');
		
		$_data['recent_activity']	= $this->_CI->model_zhout->recent_activity();
		
		if($_data['recent_activity']->num_rows() > 0){
			foreach ($_data['recent_activity']->result() as $row)
			{ 
				$_data['propic_ra'] = directory_map(getcwd().'/assets/zhopie/userfiles/'.$row->zhout_member.'/profile_picture');
				if($row->barang){
					$_data['stuff_pic'][$row->barang]	= directory_map(getcwd().'/assets/zhopie/userfiles/'.$row->id_member.'/zhops/'.$row->zhopie_shop.'/stuff/'.$row->barang.'/primary');
				}
			}
		}
		$this->_CI->bep_site->set_js_block("
		k=1;
        setInterval('livefeed(k++)',7000);
        function livefeed(i){
        slide(i);
        }

        function slide(i){
                 addTop((i+3)%10);
                removeBottom(i%10);
                 var j=((i-1)%10);
                 $('.livefeed').prepend($('#'+j));

        }

        function addTop(i){
                  var e=document.getElementById(i);
                  $('#'+i).fadeIn(6000);  
        }
        function removeBottom(i){
                  $('#'+i).fadeOut(7000);   
         }
		"
		);
		
		$_string = $this->_CI->load->view('zhout/recentzhout_widget',$_data,TRUE);
		return $_string;
	}
}
?>   
