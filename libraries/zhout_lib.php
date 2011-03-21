<?php if (! defined ('BASEPATH')) exit('No direct script access allowed');
 class Zhout_lib 
{
	private $CI = NULL;
	private $_INPUT_DATA = array();
	private $_AJAX_URL = 'zhout/zhout/add_zhout';
	private $_DEFAULT_ZHOUT_POST = 'Write Your Wish';
	private $_DEFAULT_ZHOUT_COMMENT = 'Write\'s your comment';
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('zhout/model_zhout');
		$this->_INPUT_DATA = array('zhout_text'=>array('type'=>'form_textarea','value'=> array('id'=>'watermark','name'=>'zhout','value'=>$this->_DEFAULT_ZHOUT_POST)),
								   'button_zhout'=>array('value'=>'<a href="javascript:void(0);" id="shareButton"><div class="btn_zhout">Zhout</div></a>'),
								   'category_filter'=>array('type'=>'form_dropdown','value'=>array('id'=>'category_filter'),'name'=>'category_filter'),
								   'comment_text'=>array('type'=>'form_textarea','value'=> array('id'=>'comment','name'=>'comment_text','cols'=>'50','style'=>"height:10px;",'class'=>'comment')),
								   
								  );
		$this->CI->load->helper('zhout/add_this');	
		
		/* Load Model Amazon And Zappos And Shops Zhopie*/
		$this->CI->load->model('zhout/model_amazon');
		$this->CI->load->model('zhout/model_zappos');
		 
	}
	/* ------- JAVA SCRIPT ---------- */
	function data_javascript()
	{
		return 'var index_friend = location.href.substring(location.href.lastIndexOf(\'\/\'), location.href.length);
		if(index_friend ==\'/index\')
		{
			index_friend = \'/\';
		}';
	}
	function text_area_javascript()
	{
			return 'jQuery(function($){
		   			$("#watermark").Watermark("'.$this->_DEFAULT_ZHOUT_POST.'","#369");
					$(\'.comment\').Watermark("'.$this->_DEFAULT_ZHOUT_COMMENT.'","#369");
					});
			
					$(document).ready(function()
					{			
		  			$(\'textarea\').elastic();
					});
					';
	}
	
	function add_this_javascript()
	{
		return '<!-- AddThis Button BEGIN -->
				<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d817252790ccd96"></script>
				<!-- AddThis Button END -->';
	}
	
	function button_zhout_javascript()
	{
			return '$(\'#shareButton\').click(function(){
			var a = $("#watermark").val();
			if(a != "'.$this->_DEFAULT_ZHOUT_POST.'")
			{
					$.post(\''.site_url(array($this->_AJAX_URL)).'\',{value:a}, function(response)
					{
					$(\'#posting\').prepend($(response).fadeIn(\'slow\'));
					$("#watermark").val("'.$this->_DEFAULT_ZHOUT_POST.'");
					$(\'textarea\').css({height :30});
					});				
			}
			else 
			{
			if (a == "'.$this->_DEFAULT_ZHOUT_POST.'") a=null;
			}
		});	';
	}
	
	/* ------- END JAVA SCRIPT ---------- */
	
	/* ------------- NEW FETCH DATA ------------- */
	function get_feeds_zhout_by_id_member($_id_member)
	{
		
	}
	/* ------------- END FETCH DATA ------------- */
	
	
	/*------------- ZHOUT CONTENT CONDITION -------*/
	
	
	function get_product_update_source($_id_zhout,$_id_source,$_id_product)
	{
		switch ($_id_source)
		{
			case 'zhop in zhopie(id_product)' :           break;
			
			case 'zhop eksternal amazon' :  $_data_amazon_product = $this->CI->model_amazon->get_product_amazon_by_id($_id_product); 
											if ($_data_amazon_product)
											{
												return $this->CI->load->view('zhout/zhout_product_update_single',$_data_amazon_product);
											}
											else
											{
												throw 'Data Product Amazon tidak ada';
											}
											break;
			
			case 'zhop eksternal zappos' :  $_data_zappos_product = $this->CI->model_zappos->get_product_zappos_by_id($_id_product); 
											if ($_data_amazon_product)
											{
												return $this->CI->load->view('zhout/zhout_product_update_single',$_data_amazon_product);
											}
											else
											{
												throw 'Data Product Amazon tidak ada';
											}
											break;			
		}
	}
	
	function get_post_zhout($_id_zhout)
	{
		
	}
	
	function get_wishes_product($_id_product)
	{
		
	}
	
	/*------------- ZHOUT CONTENT CONDITION -------*/
	
	/*------------------ GET ZHOUT ATTRIBUTE ----------------*/
	/*---(E.g 'wishes product','addthis button','comment')---*/
	/**
	 * @type public function
	 * @param string $_type  ('wishes_product','addthis_button','comment')
	 * @param string $_id_product
	 * @param int $_id_zhout 
	 * @return object html
	 */
	function get_attribute_by_type($_type,$_id_product,$_id_zhout)
	{
		switch($_type)
		{
			case 'wishes_product' : $_wishes_product = $this->model_zhout->get_wishes_product_by_id_product($_id_product);
									if($_wishes_product)
									{
										return 'Wishes('.$_wishes_product.')';
									}
									else
									{
									 	return 'Wishes(0)';	
									}
									break;
			case 'addthis_button' : 
									break;
			case 'comment'		  : 
									break;
		}
	}
	
	/*----------------- END ZHOUT ATTRIBUTE -----------------*/
	
	/* ------------- NEW FUNCTION ------------- */
	function get_zhout_content_by_id_member($_id_member)
	{
		$_data = array();
		$_dropdowsn_value = array('merc'=>'mercedes','toyota'=>'toyota');
		foreach($this->_INPUT_DATA as $_index =>$_value)
		{
			if(  isset($_value['type'])  && $_value['type'] == 'form_dropdown')
			{
				$_data[$_index] = call_user_func($_value['type'],$_value['name'],$_dropdowsn_value,$_value['value']);
			}
			else if (isset($_value['type']))
			{
				$_data[$_index] = call_user_func($_value['type'],$_value['value']);
			}
			else
			{
				$_data[$_index] = $_value['value'];
			}
		}
		
		 return $this->CI->load->view('zhout/zhout_content',$_data,TRUE);
	}
	
	/* ------------- END FUNCTION ------------- */
	
	/* --------------- OLD FUNCTION ------------------- */
	private function get_newsfeeds($user_id)
	{
		$this->load->helper('directory');
		$return_data = array();
		$result_post_data = array();
		$result_comment_by = array();
		$result_tmp = array();
		$info_stuff = array();
		$info_shop = array();
		$location_image_stuff = array();
		$location_brand_logo = array();
		$location_shop_logo = array();
		$is_added_wishlist = array();
		$count = 0;
		//find user id friends
		$return = $this->get_id_friends($user_id);
		$result = $this->mdUserwall->get_post_id_by_newsfeeds($return);
		foreach($result->result() as $row)
		{
			$result =  $this->mdUserwall->get_post_data_by_ver2($row->id_zhout);
			foreach($result->result_array() as $row_post)
			{
				$result_post_data [$row->id_zhout] = $row_post;
				if($row_post['id_stuff'] != null)
				{
					$count = count($row_post['count_id_stuff']);
					
					if($count>0)
					{
						$val_count = explode('#',$row_post['count_id_stuff']);
						if(count($val_count)>0)
						{
							foreach($val_count as $valcount)
							{
								$location_image_stuff[$valcount] = $this->mdUserwall->get_picture_stuff_by_id_stuff($valcount);
							}
						}
						$location_image_stuff[$row_post['id_stuff']] = $this->mdUserwall->get_picture_stuff_by_id_stuff($row_post['id_stuff']);
						/*else
						{
							$location_image_stuff[$val_count] = $this->mdUserwall->get_picture_stuff_by_id_stuff($val_count);
						}*/
					}
					else 
					{
						$location_image_stuff[$row_post['id_stuff']] = $this->mdUserwall->get_picture_stuff_by_id_stuff($row_post['id_stuff']);

					}
				  		$info_stuff[$row_post['id_stuff']] = $this->mdWishlist->get_info_stuff_by_id_stuff($row_post['id_stuff']);
				  		$is_added_wishlist[$row_post['id_stuff']] = ($this->mdUserwall->is_added_wishlist($row_post['id_stuff'],$this->user->getIdMember())>0)? 1 : 0;

				}
				if($row_post['id_shop'] != null)
				{
					$location_brand_logo[$row_post['id_shop']] = $this->mdUserwall->get_brand_picture($row_post['id_shop']);
					//$location_brand_logo[$row_post['id_shop']][1] = directory_map(getcwd().'/userfiles/'.$location_brand_logo[$row_post['id_shop']][0]['id_member'].'/'.$row_post['id_shop'].'/logo');
					//var_dump($location_brand_logo[$row_post['id_shop']][0]);
				}
				if($row_post['shop_id'] != null)
				{
					$location_shop_logo[$row_post['shop_id']] = directory_map(getcwd().'/userfiles/'.$user_id.'/'.$row_post['shop_id'].'/logo');
					$info_shop[$row_post['shop_id']] = $this->mdUserwall->view_selected_shop($row_post['shop_id']);
				}
			   	$result_all_comment = $this->mdUserwall->get_comment_by($row->id_zhout);
		       	if ($result_all_comment->num_rows > 0)
		       	{
		        	$result_comment_by[$row->id_zhout] = $result_all_comment->result_array(); 
		       	}
			}
		}
		$select3 = $this->mdWishlist->get_name($user_id);
		if ($select3->num_rows() > 0)
		{
			$row = $select3->row();
			$name = $row->first_name;
		}
		$return_data['name'] = $name;
		$return_data['user_id'] = $user_id;
		$return_data['user_id_active'] = $this->user->getIdMember();
		//$return_data['data_post_id'] = $result->result_array();
		//print_r($result_comment_by);
		$return_data['post_data'] = $result_post_data;
		$return_data['comment_data'] = $result_comment_by;
		$return_data['info_stuff'] = $info_stuff;
		//check untuk older_post boleh_ditampilkan/ tidak berdasarkan jumlah post_id 
		$return_data['location_image_stuff'] = $location_image_stuff;
		$return_data['is_older_post_visible'] = ((count($this->mdUserwall->get_post_id_by_newsfeeds($return)->result_array()) < 10)? 0 : 1 );
		$return_data['is_added_wishlist'] = $is_added_wishlist;
		$return_data['location_brand_logo'] = $location_brand_logo;
		$return_data['location_shop_logo'] = $location_shop_logo;
		$return_data['info_shop'] = $info_shop;
		return $return_data;
	}
	
	/* --------------- END OLD FUNCTION ------------------- */
}
    
?>
