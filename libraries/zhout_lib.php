<?php if (! defined ('BASEPATH')) exit('No direct script access allowed');
 class Zhout_lib 
{
	private $CI = NULL;
	private $_INPUT_DATA = array();
	private $_IS_MOST_ACTIVE_AS_DEFAULT = TRUE;
	
	//id_member parameter for ajax request
	public $_ID_MEMBER_PARAMETER = 'id_member';
	//zhout parameter for ajax request
	public $_ZHOUT_PARAMETER = 'zhout_data';
	//comment parameter
	public $_COMMENT_PARAMETER = '';
	//set all category
	private $_ALL_CATEGORY = array('0'=>'All');
	private $_FRIEND_CATEGORY = array('friend'=>'Friend'); 
	
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
	private $_AJAX_SORT_CURRENT_ACTIVE = 'zhout/ajax_zhout/current_active/';
	
	
	
	//sort
	//End Type of ajax is GET
	
	private $_DEFAULT_ZHOUT_POST = 'Write Your Wish';
	private $_DEFAULT_ZHOUT_COMMENT = 'Write your comment';
	//show comment from 2 newest comment
	private $_DEFAULT_SHOW_COMMENT_FROM_LAST = TRUE;
	private $_LIMIT_ZHOUT = 10;
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('zhout/model_zhout');
		$this->_INPUT_DATA = array('zhout_text'=>array('type'=>'form_textarea','value'=> array('id'=>'watermark','name'=>'zhout','value'=>$this->_DEFAULT_ZHOUT_POST,'style'=>'resize:none;')),
								   'button_zhout'=>array('value'=>'<a href="javascript:void(0);" id="shareButton"><div class="btn_zhout">Zhout</div></a>'),
								   'category_filter'=>array('type'=>'form_dropdown','value'=>'id ="category_filter" onChange="changeCategory(this);"','name'=>'category_filter'),
								   'comment_text'=>array('type'=>'form_textarea','value'=> array('id'=>'comment','name'=>'comment_text','cols'=>'50','style'=>"height:5px;resize:none",'class'=>'comment','onkeypress'=>'return addComment(this,event);')),
								   'most_active' => array('value'=>'<a href="javascript:void(0);" onClick="currentActive(this);" class="default_active '.$_out = (($this->_IS_MOST_ACTIVE_AS_DEFAULT)?'current_active':'').'" id="most_active" >Most Active</a>'),
								   'last_update' => array('value'=>'<a href="javascript:void(0);" onClick="currentActive(this);" class="default_active '.$_out = ((!$this->_IS_MOST_ACTIVE_AS_DEFAULT)?'current_active':'').'"id="last_update" >Last Update</a>')
								  );
		$this->CI->load->helper('zhout/add_this');	
		
		/* Load Model Amazon And Zappos And Shops Zhopie*/
		$this->CI->load->model('zhout/model_amazon');
		$this->CI->load->model('zhout/model_zappos');
		$this->CI->load->model('product/model_product');
		/* NOTE */
		/* Must load member profile from model_member*/
		
		/*Load Helper*/
		$this->CI->load->helper('image/image');
		$this->CI->load->helper('directory');
		 
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
			return 'var clickedOnce = false; $(\'#shareButton\').click(function(e){
			var a = $("#watermark").val();
			if(a != "'.$this->_DEFAULT_ZHOUT_POST.'")
			{		
					if (! clickedOnce )
					{
						$.ajax({url :\''.site_url(array($this->_AJAX_URL_ADD_ZHOUT)).'\',
								type :\'POST\',
								data :{'.$this->_ZHOUT_PARAMETER.':a,'.$this->_ID_MEMBER_PARAMETER.':id_member},
								beforeSend: function()
										{
											clickedOnce = true;
										},
								success : function(response)
										{
											
											if (response != \'0\')
											{
												$(\'#wrap_zhout\').prepend($(response).fadeIn(\'slow\'));
												$("#watermark").val("'.$this->_DEFAULT_ZHOUT_POST.'");
												$("#watermark").Watermark("'.$this->_DEFAULT_ZHOUT_POST.'","#369");
												$(\'textarea\').css({height :30});
											}
											clickedOnce = false;
										},
								error : function(response)
										{
											clickedOnce = false;
										}
						
								});
					}
			}
			else 
			{
			if (a == "'.$this->_DEFAULT_ZHOUT_POST.'") a=null;
			}
		});	';
	}
	
	function add_comment_javascript()
	{
		return 'function addComment(tag,e)
				{	
					
					if(e.keyCode ==13)
					{
						if(tag.value !=\'\' && tag.value !=\''.$this->_DEFAULT_ZHOUT_COMMENT.'\' && $(tag).attr(\'clicked\') != \'clicked\')
						{
							var comment_length = $(\'#wrap_comment-\'+$(tag).attr(\'ref\')).find(\'.people_comment\').length;
							$.ajax({url : \''.site_url().'/'.$this->_AJAX_URL_ADD_COMMENT.'\',
									data : {comment_content :tag.value , id_member:id_member, id_zhout : $(tag).attr(\'ref\')},
									type : \'POST\',
									beforeSend : function()
												{
													$(tag).attr(\'clicked\',\'clicked\');
												},
									success		: function(response)
												{
													//alert($(\'#wrap_comment-\'+$(tag).attr(\'ref\')).length);
													
													if($(\'#wrap_comment-\'+$(tag).attr(\'ref\')).find(\'.people_comment\').length == 0)
													{
											
														$(\'#wrap_comment-\'+$(tag).attr(\'ref\')).prepend($(response));
													}
													else
													{
														//alert($(\'#wrap_comment-\'+$(tag).attr(\'ref\')).find(\'.people_comment\').eq(comment_length-1));
														//console.log(\'%o\',comment_length);
														//console.log(\'%o\',$(\'#wrap_comment-\'+$(tag).attr(\'ref\')).find(\'.people_comment\').eq(2));
														$(\'#wrap_comment-\'+$(tag).attr(\'ref\')).find(\'.people_comment\').eq(comment_length-1).append($(response));
													}
												var  current_count_comment = $(\'#comment_status-\'+$(tag).attr(\'ref\')).html();	
												current_count_comment = current_count_comment.substring(current_count_comment.indexOf(\'(\')+1,current_count_comment.indexOf(\')\'));
												$(\'#comment_status-\'+$(tag).attr(\'ref\')).html(\'COMMENT(\'+(parseInt(current_count_comment)+1)+\')\');
												$(tag).val(\'\').blur();
												$(tag).removeAttr(\'clicked\',\'clicked\');
												},
									error		: function (response)
												{
													$(tag).removeAttr(\'clicked\',\'clicked\');
												}
								   });
						}
						return false;
					}
				
				}
		';
	}
	function current_active_javascript()
	{
		return 'function currentActive(tag)
				{
					
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_CURRENT_ACTIVE.'\'+id_member+\'/\'+$(\'#category_filter\').val()+\'/\'+tag.id,
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
										$(\'#wrap_zhout\').html($(response));
										}
						
						
					});
					
					$(\'.default_active\').removeClass(\'current_active\');
					$(tag).addClass(\'current_active\');
					
				}';
	}
	
	
	function add_wishlist_javascript()
	{
		return 'function addWishlist(tag)
				{
					/*$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_ADD_WISHLIST.'\'+id_member+\'/\'+tag.id+\'/\'+tag.id_source,
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
				var tmp_onclick =\'\';
				var attr_id = $(tag).attr(\'id\');
				attr_id = attr_id.split(\'-\');
				var found_comment_wrap = $(\'#wrap_comment-\'+attr_id[1]).length;
				if (found_comment_wrap == 0)
				{
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_CLICK_COMMENT.'\'+attr_id[1],
							 beforeSend : function()
							 			{
							 				tmp_onclick = $(tag).attr(\'onclick\');
											$(tag).removeAttr(\'onclick\');
							 			},
							success : function(response)
										{
											$(\'#zhout-\'+attr_id[1]).append($(response)).after(\'<div class="clear"></div>\');
										},
							error   : function (response)
										{
											$(tag).attr(\'onclick\',tmp_onclick);
										}
						
						
					});
				}
				
				
			}
		';
	}
	
	function change_category_javascript()
	{
		return 'function changeCategory(tag)
				{
					var id_current_active = $(\'.current_active\').attr(\'id\');
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_SORT_CATEGORY.'\'+id_member+\'/\'+tag.value+\'/\'+id_current_active,
							 beforeSend : function()
							 			{
							 			 $(tag).attr(\'disabled\',\'disabled\');
							 			},
							success : function(response)
										{
											$(\'#wrap_zhout\').html($(response));
										 $(tag).removeAttr(\'disabled\');
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
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_SHOW_MORE_ZHOUT.'\'+id_member+\'/\'+current_status+\'/\'+offset,
							 dataType   : \'json\',
							 beforeSend : function()
							 			{
							 	
							 			},
							success : function(response)
										{
											console.log(\'%o\',response);
										//$(tag).removeAttr(\'onclick\');	
										}
						
						
					});
					
				}';
	}
	
	function show_more_comment_javascript()
	{
		return 'function showMoreComment(tag)
				{
					var tmp_onclick =\'\';
					$.ajax({ url : \''.site_url().'/'.$this->_AJAX_URL_SHOW_MORE_COMMENT.'\'+tag.id,
							 beforeSend : function()
							 			{
							 				tmp_onclick = $(tag).attr(\'onclick\');
											$(tag).removeAttr(\'onclick\');
							 			},
							success : function(response)
									   {
										var comment_length = $(\'#wrap_comment-\'+tag.id).find(\'.people_comment\');
										'.$_out =(($this->_DEFAULT_SHOW_COMMENT_FROM_LAST)?'$(\'#wrap_comment-\'+tag.id).prepend($(response));':'$(\'#wrap_comment-\'+tag.id).find(\'people_comment\').eq(comment_length-1).append($(response));' ).'
										$(tag).remove();
									   },
							error	:function(response)
									   {
										 $(tag).attr(\'onclick\',tmp_onclick);	
									   }
						
						
					});
					
				}';
	}
	
	/* ------- END JAVA SCRIPT ---------- */
	
	/* ------------- NEW FETCH DATA ------------- */
	/** Show all content filtered by default 
	 * @param string $_id_member
	 * @param offset = 0 [optional]
	 * @return object html zhout content
	 */
	function get_feeds_zhout_by_id_member($_id_member,$_offset = 0,$_category = FALSE,$_ajax_request= FALSE)
	{
		//condition
		$_data_zhout = array();
		$_zhout_content = '';
		
		if (! $_ajax_request)
		{
			if($this->_IS_MOST_ACTIVE_AS_DEFAULT)
			{
				if($_category != FALSE && $_category != 'friend' && $_category != 0)
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,$_category);
					
				}
				else if($_category == 'friend')
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE,TRUE);
				}
				else
				{
				$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE);
				}
				
			}
			else
			{	
				if($_category != FALSE && $_category != 'friend' && $_category != 0)
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,$_category);
					
				}
				else if($_category == 'friend')
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE,TRUE);
				}
				else
				{
				$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE);
				}
			}
		}
		else
		{
			if($_ajax_request == 'most_active')
			{
				if($_category != FALSE && $_category != 'friend' && $_category != 0)
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,$_category);
					
				}
				else if($_category == 'friend')
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE,TRUE);
				}
				else
				{
				$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_most_active($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE);
				}
				
			}
			else
			{
				// Must update the value
				if($_category != FALSE && $_category != 'friend' && $_category != 0)
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,$_category);
					
				}
				else if($_category == 'friend')
				{
					$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE,TRUE);
				}
				else
				{
				$_data_zhout =	$this->CI->model_zhout->get_post_data_by_id_member_last_update($_id_member,$_offset,$this->_LIMIT_ZHOUT,FALSE);
				}
			}
			
		}
		
		if(count($_data_zhout))
		{	
			
			foreach ($_data_zhout as $_value)
			{
				
				switch ( intval($_value['id_type_zhout']))
				{
					//if (type = 1) then get_product_update_source
					case 1 : $_zhout_content .= call_user_func(array($this,'get_wishes_product'),$_value['id_member'],$_value['id_stuff'],$_value['id_zhout'],$_value['id_stuff_source']);
							break;
					 // if (type = 2) then get post zhout
					case 2 : $_zhout_content .= call_user_func(array($this,'get_post_zhout'),$_value['id_zhout']);
							break;
					// if (type = 3) then get wishes product
					case 3 : $_zhout_content .= call_user_func(array($this,'get_wishes_product'),$_value['id_member'],$_value['id_stuff'],$_value['id_zhout'],$_value['id_stuff_source']);
							break;
					default : $_zhout_content .='Cannot Find id_type_zhout on ID Zhout '.$_value['id_zhout'];
							break;
				}
			}
		
		}
		
		return $_zhout_content;
		 
	}
	/* ------------- END FETCH DATA ------------- */
	
	
	/*------------- ZHOUT CONTENT CONDITION -------*/
	/**
	 * @param int $_id_zhout
	 * @param int (enum type product) e.g 1 = zhop in zhopie, 2 = zhop in amazon, 3 = zhop in zappos
	 * @return object html
	 * Description : Only return view update product posting
	 */
	
	function get_product_update_source($_id_zhout,$_id_source,$_id_product)
	{
		switch ($_id_source)
		{
			case 1 : $_data_zhopie_product = call_user_func(array($this,'get_detail_product_by_id_source_id_product'),1,$_id_product);  
			       							 break;
			
			case 2 :  $_data_amazon_product = call_user_func(array($this,'get_detail_product_by_id_source_id_product'),2,$_id_product); 
											if ($_data_amazon_product)
											{
												return $this->CI->load->view('zhout/zhout_product_update_single',$_data_amazon_product);
											}
											else
											{
												throw 'Data Product Amazon tidak ada';
											}
											break;
			
			case 3 :  $_data_zappos_product = call_user_func(array($this,'get_detail_product_by_id_source_id_product'),3,$_id_product); 
											if ($_data_amazon_product)
											{
												return $this->CI->load->view('zhout/zhout_product_update_single',$_data_amazon_product);
											}
											else
											{
												throw 'Data Product Amazon tidak ada';
											}
											break;
			default : throw 'Type sumber product yang diplih tidak ada'; break;			
		}
	}
	
	/** Show zhout with single zhout posting
	 * @param int $_id_zhout
	 * @return object html
	 * Description : Only return view zhout posting
	 */
	function get_post_zhout($_id_zhout)
	{
		$_data_zhout = $this->CI->model_zhout->get_zhout_by_id_zhout($_id_zhout);
		//image must set with directory helper;
		$_image_profile = directory_map(getcwd().'/assets/zhopie/userfiles/'.$_data_zhout['id_member'].'/profile_picture');
		
		$_image_url['profil_picture_url'] = checkPicture($_out =((count($_image_profile)== 1)? base_url().'/assets/zhopie/userfiles/'.$_data_zhout['id_member'].'/profile_picture/'.$_image_profile[0] : 'NULL'));
		$_data_inserted = array_merge($_data_zhout,$_image_url);
		//set appropriate attribute
		$_data_inserted['_comment_button'] = call_user_func(array($this,'get_attribute_by_type'),'comment_status',$_data_zhout['id_member'],FALSE,$_data_zhout['id_zhout'],array(),NULL);
		return $this->CI->load->view('zhout/zhout_post_single',$_data_inserted,TRUE);
	}
	/** show zhout with single wish product posting
	 * @param int $_id_product
	 * @return object html
	 * Description : Only return wishes product
	 */
	function get_wishes_product($_id_member,$_id_product,$_id_zhout,$_id_product_source)
	{
		$_data_product =$this->get_detail_product_by_id_source_id_product($_id_product_source,$_id_product);
		return $this->CI->load->view('zhout/wishlist_product_single',$_data_product,TRUE);
	}
	
	/*------------- END ZHOUT CONTENT CONDITION -------*/
	
	/*------------------ GET ZHOUT ATTRIBUTE ----------------*/
	/*---(E.g 'wishes product','addthis button','comment')---*/
	/**
	 * @type public function
	 * @param string $_type  ('wishes_product','addthis_button','comment_view','comment_status','mode_comments')
	 * @param string $_id_product
	 * @param int $_id_zhout 
	 * @return object html
	 */
	function get_attribute_by_type($_type,$_id_member=FALSE,$_id_product=FALSE,$_id_zhout=FALSE,$_data_product = array(),$_id_source=NULL)
	{
		switch($_type)
		{
			case 'wishes_product' : $_wishes_product = $this->CI->model_zhout->get_wishes_product_by_id_product($_id_product);
									//Must relate with model wishlist return must an integer
									$_wishlist_already_added = $this->CI->model_product->is_added_stuff($_id_product,$_id_member);
									$_wishlist_html ='';
									$_wishlist_html ='<a href="javascript:void(0);"'.((!$_wishlist_already_added)?'onClick ="addWishlist(this)"' :'').'"
													   id="'.$_id_product.' id_source="'.$_id_source.'">';
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
			case 'comment_view'		  : $_data_comment =$this->CI->model_zhout->get_comment_by_id_zhout($_id_zhout);
									if(count($_data_comment))
									{
										$_data_view_comment = array();
										if(count($_data_comment)> 2)
										{
											$_data_comment =$this->CI->model_zhout->get_comment_by_id_zhout($_id_zhout,0,2,$this->_DEFAULT_SHOW_COMMENT_FROM_LAST);
											$_data_view_comment['_show_all_comment'] = TRUE;
											$_data_view_comment['_data_comment'] = $_data_comment;
											$_data_view_comment['_show_comment_input'] = TRUE;
											$_data_view_comment['_id_zhout'] =$_id_zhout;
											$this->_INPUT_DATA['comment_text']['value']['ref'] = $_id_zhout;
											$_data_view_comment['_comment_text'] =call_user_func($this->_INPUT_DATA['comment_text']['type'],$this->_INPUT_DATA['comment_text']['value']);
											$_data_view_comment['_for_single_ajax'] =FALSE;
											return $this->CI->load->view('zhout/zhout_comment_view',$_data_view_comment,TRUE);
										}
										else if(count($_data_comment)> 0 && count($_data_comment)<=2)
										{
											
											$_data_view_comment['_show_all_comment'] = FALSE;
											$_data_view_comment['_data_comment'] = $_data_comment;
											$_data_view_comment['_show_comment_input'] = TRUE;
											$_data_view_comment['_id_zhout'] =$_id_zhout;
											$this->_INPUT_DATA['comment_text']['value']['ref'] = $_id_zhout;
											$_data_view_comment['_comment_text'] =call_user_func($this->_INPUT_DATA['comment_text']['type'],$this->_INPUT_DATA['comment_text']['value']);
											$_data_view_comment['_for_single_ajax'] =FALSE;
											return $this->CI->load->view('zhout/zhout_comment_view',$_data_view_comment,TRUE);
										}
								
									}
									else
									{
										$_data_view_comment['_show_all_comment'] = FALSE;
										$_data_view_comment['_data_comment'] = $_data_comment;
										$_data_view_comment['_show_comment_input'] = TRUE;
										$_data_view_comment['_id_zhout'] =$_id_zhout;
										$this->_INPUT_DATA['comment_text']['value']['ref'] = $_id_zhout;
										$_data_view_comment['_comment_text'] =call_user_func($this->_INPUT_DATA['comment_text']['type'],$this->_INPUT_DATA['comment_text']['value']);
										$_data_view_comment['_for_single_ajax'] =FALSE;
										return $this->CI->load->view('zhout/zhout_comment_view',$_data_view_comment,TRUE);		
									}
									return FALSE;	
									break;
			case 'comment_status'	 : $_count_comment = $this->CI->model_zhout->get_comment_by_id_zhout($_id_zhout);
									   $_status_comment ='<a href ="javascript:void(0)" id="comment_status-'.$_id_zhout.'" onClick="commentStatus(this);" >';
									   if(count($_count_comment))
									   {
									   	$_status_comment .= 'COMMENT('.count($_count_comment).')';	
									   }
									   else
									   {
									   	$_status_comment .= 'COMMENT('.count($_count_comment).')';
									   }
									   $_status_comment .= '</a>';
									   
									   return $_status_comment; 
									break;
		  case 'more_comments'		: $_data_comment =$this->CI->model_zhout->get_comment_by_id_zhout($_id_zhout,0,FALSE,$this->_DEFAULT_SHOW_COMMENT_FROM_LAST);
		  							  $_data_view_comment['_show_all_comment'] = FALSE;
									  $_data_view_comment['_data_comment'] = $_data_comment;
									  $_data_view_comment['_show_comment_input'] = TRUE;
									  $_data_view_comment['_id_zhout'] =$_id_zhout;
									  $this->_INPUT_DATA['comment_text']['value']['ref'] = $_id_zhout;
									  $_data_view_comment['_comment_text'] =call_user_func($this->_INPUT_DATA['comment_text']['type'],$this->_INPUT_DATA['comment_text']['value']);
									  $_data_view_comment['_for_single_ajax'] =TRUE;
						
									  return $this->CI->load->view('zhout/zhout_comment_view',$_data_view_comment,TRUE);
									  
		  								
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
		if(count($_data_category)== 1 && $_data_category[0] == NULL )
		{
			$_data_category = $this->_ALL_CATEGORY + $this->_FRIEND_CATEGORY;
		}
		else if (count($_data_category)>=2)
		{
			$_data_category = $this->_ALL_CATEGORY + $_data_category  + $this->_FRIEND_CATEGORY;  
		
		}
		
		foreach($this->_INPUT_DATA as $_index =>$_value)
		{
			if(  isset($_value['type'])  && $_value['type'] == 'form_dropdown')
			{
				$_data[$_index] = call_user_func($_value['type'],$_value['name'],$_data_category,'',$_value['value']);
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
	
	/**
	 * @param integer $_id_source e.g 1 = zhopie shop , 2= amazon shop , 3 = zappos shop
	 * @param string $_id_product
	 * @return array data detail_product
	 */
	
	function get_detail_product_by_id_source_id_product($_id_source,$_id_product)
	{
		switch ($_id_source)
		{
			case 1 :  $_product_on_zhopie = $this->CI->model_product->get_detail_product($_id_product);
					  $_product_on_zhopie = array_shift($_product_on_zhopie->result_array());
						return $_product_on_zhopie;
					break ;
			case 2 : return  $this->CI->model_amazon->get_product_amazon_by_id($_id_product); 
					break;
			case 3 : return $this->CI->model_zappos->get_product_zappos_by_id($_id_product); 
					break;
		}
	}
	
	/* ------------- END FUNCTION ------------- */
	
	/* ------------- ZHOUT AJAX ACTION AND RESPONSE ------------------- */
	//get_attribute_by_type($_type,$_id_member=FALSE,$_id_product=FALSE,$_id_zhout=FALSE,$_data_product = array())
	
		//Type Is Post
		/** function to add  zhout through ajax with callback of object html which have requested before
		 * @param string $_id_member
		 * @param string $_data_zhout
		 * @return object html 
		 */
		function add_zhout($_id_member,$_data_zhout)
		{
			//data zhout must be validate to prevent xss 
			//locate checkValues
			$_data_inserted = $this->CI->model_zhout->add_zhout(array('zhout_content'=>$_data_zhout,'userip'=>$_SERVER['REMOTE_ADDR'],'id_member'=>$_id_member,'date'=>strtotime(date("Y-m-d H:i:s")),'id_type_zhout'=>2));
			return call_user_func(array($this,'get_post_zhout'),$_data_inserted['id_zhout'] );	
		}
		
		/**function to add  comment through ajax with callback of object html which have requested before
		 * @param string $_id_zhout
		 * @param string $_comment_content
		 * @param string $_id_member
		 * @return object html
		 */
		function add_comment($_id_zhout,$_comment_content,$_id_member)
		{
			//data comment zhout must be validate to prevent xss 
			//locate checkValues
			$_data_comment = $this->CI->model_zhout->add_comment(array('id_zhout'=>$_id_zhout,'comment_content'=>$_comment_content,'id_member'=>$_id_member,'date'=>strtotime(date('d M Y H:i:s'))));
			$_data_view_comment['_show_all_comment'] = FALSE;
			$_data_view_comment['_data_comment'] = $_data_comment;
			$_data_view_comment['_show_comment_input'] = TRUE;
			$_data_view_comment['_id_zhout'] =$_id_zhout;
			$_data_view_comment['_for_single_ajax'] =TRUE;
			$_data_view_comment['_comment_text'] =call_user_func($this->_INPUT_DATA['comment_text']['type'],$this->_INPUT_DATA['comment_text']['value']);
			return $this->CI->load->view('zhout/zhout_comment_view',$_data_view_comment,TRUE);
			
		}
		
		//End Type Post
	
		/** get first comment view by clicking comment button and returned of object html comment view
		 * @param int $_id_zhout
		 * @return object html
		 */
		function get_dropdown_comment($_id_zhout)
		{
			return call_user_func(array($this,'get_attribute_by_type'),'comment_view',NULL,NULL,$_id_zhout,NULL,NULL);
		}
		
		/** get more comment by clicking see all comment
		 * @param object $_id_zhout
		 * @return object html
		 */
		function show_more_comment($_id_zhout)
		{
			return call_user_func(array($this,'get_attribute_by_type'),'more_comments',NULL,NULL,$_id_zhout,NULL,NULL);
		}
		
		/** get more zhout by clicking more zhout button
		 * @param string $_id_member
		 * @param string $_current_active
		 * @param int $_offset
		 * @return object html
		 */
		function show_more_zhout($_id_member,$_current_active,$_offset)
		{
			
		}
		
		/** doing add wishlist by clicking wishlist button
		 * @param string $_id_member
		 * @param string $_id_product
		 * @param int $_id_source
		 * @return object html
		 */
		function add_wishlist($_id_member,$_id_product,$_id_source)
		{
			//must make a rule to get different product detail depend on id_source
			
			$_id_member_session = $this->CI->session->userdata('id_member');
			//Must relate with model wishlist return must an integer
			$_wishlist_already_added = $this->CI->model_product->is_added_stuff($_id_product,$_id_member);
			/*if(!$_wishlist_already_added)
			{
				$this->model_zhout->update_wishlist_status($_id_product);
				$this->model_wishlist->insert_wishlist($_id_member,$_id_product);
				$data['message'] = ''
				return 
			}*/
		}
		
		/** to delete zhout (must validate if id_member have permission to delete zhout)
		 * @param string $_id_member
		 * @param string $_id_zhout
		 * @return void
		 */
		function delete_zhout($_id_member,$_id_zhout)
		{
			
		}
		
		function delete_comment($_id_member,$_id_comment)
		{
			
		}
		
		function change_category($_id_member,$_id_category,$_current_active)
		{
			 return call_user_func(array ($this,'get_feeds_zhout_by_id_member'),$_id_member,0,$_id_category,$_current_active);
		}
		
		
		function current_active($_id_member,$_id_category,$_current_active)
		{
			 return call_user_func(array ($this,'get_feeds_zhout_by_id_member'),$_id_member,0,$_id_category,$_current_active);
		}
		
		
		
		function update_attribute_time($_id_zhout)
		{
			$this->CI->model_zhout->update_attribute_time($_id_zhout);
		}
	
	/* ------------- END ZHOUT AJAX ACTION AND RESPONSE -------------- */
	
	
	
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