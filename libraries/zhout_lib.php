<?php if (! defined ('BASEPATH')) exit('No direct script access allowed');
 class Zhout_lib 
{
	private $CI = NULL;
	private $_INPUT_DATA = array();
	
	//Type of ajax is POST
	private $_AJAX_URL_ADD_ZHOUT = 'zhout/ajax_zhout/add_zhout';
	private $_AJAX_URL_ADD_COMMENT = 'zhout/ajax_zhout/add_comment';
	// End
	
	//Type of ajax is GET
	//must append with  id_zhout;
	private $_AJAX_URL_CLICK_COMMENT = 'zhout/ajax_zhout/get_dropdown_comment/';
	
	//must append with  id_zhout;
	private $_AJAX_URL_SHOW_MORE_COMMENT = 'zhout/ajax_zhout/show_more_comment/';
	
	//must append with id_member + offset 
	private $_AJAX_URL_SHOW_MORE_ZHOUT = 'zhout/ajax_zhout/show_more_zhout/';
	
	//must append with id_member + id_product
	private $_AJAX_URL_ADD_WISHLIST = 'zhout/ajax_zhout/add_wishlist/';
	
	//delete zhout
	//must append with id_member + id_zhout
	private $_AJAX_URL_DELETE_ZHOUT = 'zhout/ajax_zhout/delete_zhout/';
	
	//delete comment
	//must append with id_member + id_comment
	private $_AJAX_URL_DELETE_COMMENT = 'zhout/ajax_zhout/delete_comment/';
	
	//sort category
	//must append with id_member + id_comment
	private $_AJAX_SORT_CATEGORY = 'zhout/ajax_zhout/change_category/';
	
	//sort last update 
	private $_AJAX_SORT_LAST_UPDATE = 'zhout/ajax_zhout/last_update/';
	
	//sort last update 
	private $_AJAX_SORT_MOST_ACTIVE = 'zhout/ajax_zhout/most_active/';
	
	
	//sort
	//End Type of ajax is GET
	
	private $_DEFAULT_ZHOUT_POST = 'Write Your Wish';
	private $_DEFAULT_ZHOUT_COMMENT = 'Write your comment';
	private $_LIMIT_ZHOUT = 10;
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('zhout/model_zhout');
		$this->_INPUT_DATA = array('zhout_text'=>array('type'=>'form_textarea','value'=> array('id'=>'watermark','name'=>'zhout','value'=>$this->_DEFAULT_ZHOUT_POST,'style'=>'resize:none;')),
								   'button_zhout'=>array('value'=>'<a href="javascript:void(0);" id="shareButton"><div class="btn_zhout">Zhout</div></a>'),
								   'category_filter'=>array('type'=>'form_dropdown','value'=>array('id'=>'category_filter','onChange'=>'changeCategory(this);'),'name'=>'category_filter'),
								   'comment_text'=>array('type'=>'form_textarea','value'=> array('id'=>'comment','name'=>'comment_text','cols'=>'50','style'=>"height:10px;resize:none",'class'=>'comment')),
								   'most_active' => array('value'=>'<a href="javascript:void(0);" onClick="mostActive(this);" class="default_active current_active" id="most_active" >Most Active</a>'),
								   'last_update' => array('value'=>'<a href="javascript:void(0);" onClick="lastUpdate(this);" class="default_active"id="last_update" >Last Update</a>')
								  );
		$this->CI->load->helper('zhout/add_this');	
		
		/* Load Model Amazon And Zappos And Shops Zhopie*/
		$this->CI->load->model('zhout/model_amazon');
		$this->CI->load->model('zhout/model_zappos');
		/* NOTE */
		/* Must load member profile from model_member*/
		 
	}
	/* ------- JAVA SCRIPT ---------- */
	function variable_zhout_javascript($_id_member)
	{
		$this->CI->bep_site->set_variable('id_member',$_id_member);
		
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
					$.post(\''.site_url(array($this->_AJAX_URL_ADD_ZHOUT)).'\',{value:a}, function(response)
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
	
	function last_update_javascript()
	{
		return 'function lastUpdate(tag)
				{
					/*$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_LAST_UPDATE.'\'+id_member,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
											
										}
						
						
					});*/
					
					$(\'.default_active\').removeClass(\'current_active\');
					$(tag).addClass(\'current_active\');
					
				}';
	}
	
	function most_active_javascript()
	{
		return 'function mostActive(tag)
				{
					/*$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_MOST_ACTIVE.'\'+id_member,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
											
										}
						
						
					});*/
					$(\'.default_active\').removeClass(\'current_active\');
					$(tag).addClass(\'current_active\');
					
				}';
	}
	
	function add_wishlist_javascript()
	{
		return 'function addWishlist(tag)
				{
					/*$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_ADD_WISHLIST.'\'+id_member,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(tag).removeAttr(\'onclick\');	
										}
						
						
					});*/
					
					
					
				}';
	}
	
	function comment_status_javascript()
	{
		return 'function commentStatus(tag)
			{
				var attr_id = $(tag).Attr(\'id\');
				attr_id = attr_id.split(\'-\');
				var found_comment_wrap = $(\'#wrap_comment-\'+attr_id[1]).length;
				
				if (found_comment_wrap == 0)
				{
					/*$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_CLICK_COMMENT.'\'+id_member,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(tag).removeAttr(\'onclick\');	
										}
						
						
					});*/
				}
				
				
			}
		';
	}
	
	function change_category_javascript()
	{
		return 'function changeCategory(tag)
				{
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_CATEGORY.'\'+id_member+\'/\'+tag.value,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(tag).removeAttr(\'onclick\');	
										}
						
						
					});
					
				}';
	}
	
	function show_more_zhout_javascript()
	{
		return 'function showMoreZhout(tag)
				{
					//Must get offset and current active e.g most_active / last_update
					var offset = $(tag).attr(\'offset\');
					var current_status = $(\'.current_active\').attr(\'id\');
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_CATEGORY.'\'+id_member+\'/\'+current_status+\'/\'+offset,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(tag).removeAttr(\'onclick\');	
										}
						
						
					});
					
				}';
	}
	
	function show_more_comment_javascript()
	{
		return 'function showMoreComment(tag)
				{
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_CATEGORY.'\'+id_member+\'/\'+tag.value,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(tag).removeAttr(\'onclick\');	
										}
						
						
					});
					
				}';
	}
	
	/* ------- END JAVA SCRIPT ---------- */
	
	/* ------------- NEW FETCH DATA ------------- */
	function get_feeds_zhout_by_id_member($_id_member,$_offset = 0)
	{
		//condition
		/*if (type = 1) then get_product_update_source
		 * if (type = 2) then get post zhout
		 * if (type = 3) then get wishes product
		 */
		
	}
	/* ------------- END FETCH DATA ------------- */
	
	
	/*------------- ZHOUT CONTENT CONDITION -------*/
	/**
	 * @param int $_id_zhout
	 * @param int (enum type product) e.g 1 = zhop in zhopie, 2 = zhop in amazon, 3 = zhop in zappos
	 * @return object html
	 * Description : Only return view zhout posting
	 */
	
	function get_product_update_source($_id_zhout,$_id_source,$_id_product)
	{
		switch ($_id_source)
		{
			case 1 :  /*Note Need to be relation with model shop to get appropriate data */
			       							 break;
			
			case 2 :  $_data_amazon_product = $this->CI->model_amazon->get_product_amazon_by_id($_id_product); 
											if ($_data_amazon_product)
											{
												return $this->CI->load->view('zhout/zhout_product_update_single',$_data_amazon_product);
											}
											else
											{
												throw 'Data Product Amazon tidak ada';
											}
											break;
			
			case 3 :  $_data_zappos_product = $this->CI->model_zappos->get_product_zappos_by_id($_id_product); 
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
	
	/**
	 * @param int $_id_zhout
	 * @return object html
	 * Description : Only return view zhout posting
	 */
	function get_post_zhout($_id_zhout)
	{
		
	}
	/**
	 * @param int $_id_product
	 * @return object html
	 * Description : Only return wishes product
	 */
	function get_wishes_product($_id_member,$_id_product,$_id_zhout)
	{
		
	}
	
	/*------------- ZHOUT CONTENT CONDITION -------*/
	
	/*------------------ GET ZHOUT ATTRIBUTE ----------------*/
	/*---(E.g 'wishes product','addthis button','comment')---*/
	/**
	 * @type public function
	 * @param string $_type  ('wishes_product','addthis_button','comment_view','comment_status')
	 * @param string $_id_product
	 * @param int $_id_zhout 
	 * @return object html
	 */
	function get_attribute_by_type($_type,$_id_member,$_id_product,$_id_zhout,$_data_product = array())
	{
		switch($_type)
		{
			case 'wishes_product' : $_wishes_product = $this->model_zhout->get_wishes_product_by_id_product($_id_product);
									//Must relate with model wishlist return must an integer
									$_wishlist_already_added = $this->model_wishlist->get_wishlist($_id_member,$_id_product);
									
									$_wishlist_html ='';
									$_wishlist_html ='<a href="javascript:void(0);"'.(($_wishlist_already_added)?'onClick ="addWishlist(this)"' :'').'"
													   id="'.$_id_product.'">';
									if($_wishes_product)
									{
										$_wishlist_html .= 'Wishes('.$_wishes_product.')';
									}
									else
									{
									 	$_wishlist_html .= 'Wishes(0)';	
									}
									$_wishlist_html .= '</a>';
									return $_wishlist_html;
									break;
			case 'addthis_button' : 
									return '<div class="addthis_toolbox addthis_default_style "
						  			addthis:url="'.$_data_url = ((isset($_data_product['product_detail_page_link']))? $_data_product['product_detail_page_link']:'product internal').'"
       					  			addthis:title="'.$_data_title =((isset($_data_product['product_detaill']))? $_data_product['product_detail_page_link']:'product internal').'"
                          			addthis:description="'.$_data_desc =((isset($_data_product['desc']))?$_data_product['desc']:'' ).'">
									<a class="addthis_counter addthis_pill_style"></a>
									</div>';
			                     
									break;
			case 'comment_view'		  : $_data_comment =$this->model_zhout->get_comment_by_id_zhout($_id_zhout);
									if(count($_data_comment))
									{
										$_data_view_comment = array();
										if(count($_data_comment)> 2)
										{
											$_data_view_comment['_show_all_comment'] = TRUE;
											$_data_view_comment['_data_comment'] = $_data_comment;
											return $this->load->view('zhout/comment_view',$_data_view_comment,TRUE);
										}
										else if(count($_data_comment)> 0 && count($_data_comment)<=2)
										{
											$_data_view_comment['_show_all_comment'] = FALSE;
											$_data_view_comment['_data_comment'] = $_data_comment;
											return $this->load->view('zhout/comment_view',$_data_view_comment,TRUE);
										}
								
									}
									return FALSE;	
									break;
			case 'comment_status'	 : $_count_comment = $this->model_zhout->get_comment_by_id_zhout($_id_zhout);
									   $_status_comment ='<a href ="javascript:void(0)" id="comment_status-'.$_id_zhout.'" onClick="commentStatus(this);" >';
									   if(count($_count_comment))
									   {
									   	$_status_comment .= 'COMMENT('.$_count_comment.')';	
									   }
									   else
									   {
									   	$_status_comment .= 'COMMENT('.$_count_comment.')';
									   }
									   $_status_comment .= '</a>';
									   
									   return $_status_comment; 
									break;
		}
	}
	
	/*----------------- END ZHOUT ATTRIBUTE -----------------*/
	
	/* ------------- NEW FUNCTION ------------- */
	/**
	 * @param string $_id_member
	 * @return object html 
	 */
	function get_zhout_content_by_id_member($_id_member)
	{
		$_data = array();
		$_data_category = $this->CI->model_zhout->get_category_zhout($_id_member);
		$_dropdown_value = array();
		if(count($_data_category)== 0)
		{
			unset($this->_INPUT_DATA['category_filter']);
		}
		else
		{
			
			foreach ($_data_category as $_index =>$_value_category)
			{
				$_dropdown_value[$_index] = $_value_category;
			}
		}
		foreach($this->_INPUT_DATA as $_index =>$_value)
		{
			if(  isset($_value['type'])  && $_value['type'] == 'form_dropdown')
			{
				$_data[$_index] = call_user_func($_value['type'],$_value['name'],$_dropdown_value,$_value['value']);
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
	function get_newsfeeds($user_id)
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