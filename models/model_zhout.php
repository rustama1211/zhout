<?php
   class Model_zhout extends Model
   {
   		function __construct()
		{
			parent::__construct();
		}
		
	/* ------ NEW MODEL FUNCTION ----*/
	/** Get Category zhout
	 * @param string $_id_member
	 * @return array data category
	 */
	function get_category_zhout($_id_member)
	{
		$this->db->select('*');
		$this->db->where('id_member',$_id_member);
		$_data_category =$this->db->get('tb_category_zhout');
		return $_data_category->result_array();
	}
	/** Get count of wishes product by id product
	 * @param string $_id_product
	 * @return bolean/data
	 */
	function get_wishes_product_by_id_product($_id_product)
	{
		$this->db->select('*');
		$this->db->where('id_product',$_id_product);
		$_data = $this->db->get('tb_wishes_product');
		
		if ( $_data->num_rows())
		{
			$_data_return = array_shift($_data->result_array());
			return $_data_return['count_wishes_product'];
		}
		else
		{
			return FALSE;
		}
	}
	
	
	/** Update attribute zhout to get most active comparison with the newest one
	 * @param int $id_zhout
	 * @return void
	 */
	function update_attribute_time($_id_zhout)
	{
		$this->db->where('id_zhout',$_id_zhout);
		$this->db->update('tb_zhopie_zhout',array('attr_time'=>strtotime(date("d M Y H:i:s"))));
	}
	//------------ FRIENDS ZHOUT RELATION -------------------//
	/** Insert Friends
	 * @param int $id_zhout
	 * @param array $id_member
	 * @return void
	 */
	function insert_friends_id ($id_zhout,$id_friends)
	{
		 if(is_array($id_friends))
		 {
			  foreach ($id_friends as $index=> $value)
			  {
				$this->db->set('id_zhout',$id_zhout);
				$this->db->set('id_member_and_friends', $value['id_member']);
				$this->db->insert('tb_relation_zhout');
			  }
		 }
	}
	
	/** Get Id Friends
	 * @param string $id_member
	 * @return array id_friends
	 */
	function get_id_friends($id_member)
	{
	$data =$this->db->query("SELECT id_member FROM tb_zhopie_friends WHERE id_member_friend='".$id_member."' and status=2 UNION
    SELECT id_member_friend FROM tb_zhopie_friends WHERE id_member='".$id_member."' and status=2;");
    return $data->result_array();
	}
	//------------ END FRIENDS ZHOUT RELATION -------------------//
	
	
	//------------ FILTER ZHOUT BY MOST ACTIVE OR LAST UPDATE ------------//
	
	/** get zhout data by most active clicked
	 * @param string $_id_member
	 * @param int offset
	 * @param int limit
	 * @return array data zhout
	 */
	function get_post_data_by_id_member_most_active($_id_member,$_offset = 0, $_LIMIT = 10)
	{
		//$_data_id_friends_and_me = $this->get_id_friends($_id_member);
		$_friends_and_me = $this->get_id_friends($_id_member);
		$_friends_and_me[] = array('id_member'=>$_id_member);
		$text = "SELECT DISTINCT * from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout  WHERE ";
		//LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop // tb_follow.id_member = '004'
		$inc = 0;
		$add_text ="";
		foreach($_friends_and_me as $value)
		{
			if(!is_array($value))
			{
			$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
			}
			else
			{
				if($inc == 0)
				{
					$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
				}
				else if($inc < count($_friends_and_me)-1)
				{
					$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
				}
				else
				{
					$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
				}
				
		
		    }
		$inc ++;
		}
		
		$text .= $add_text." group by tb_zhopie_zhout.id_zhout  order by tb_zhopie_zhout.id_zhout desc limit ".$_offset.",".$_LIMIT."";

			$result = $this->db->query($text);
			
			return $result->result_array();
		
	}
	
	
	
	//------------ END FILTER ZHOUT BY MOST ACTIVE OR LAST UPDATE
	function get_post_data_by_id_member($_id_member)
	{
		$_friends_and_me = $this->get_id_friends($_id_member);
		$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE ";
		//LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop // tb_follow.id_member = '004'
		$inc = 0;
		$add_text ="";
		foreach($_friends_and_me as $value)
		{
			if(!is_array($value))
			{
			$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
			}
			else
			{
				if($inc == 0)
				{
					$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
				}
				else if($inc < count($friends)-1)
				{
					$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
				}
				else
				{
					$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
				}
				
		
		    }
		$inc ++;
		}
		
		$text .= $add_text." order by tb_zhopie_zhout.id_zhout desc limit 0,10";
		
			$result = $this->db->query($text);
			return $result;
		
	}
	
	function count_total_comment_by_id_zhout($_id_zhout)
	{
		$this->db->select('count(*) as total_count');
		$this->db->where('id_zhout',$_id_zhout);
		$_data = $this->db->get('tb_zhout_comment');
		$data = array_shift($_data->result_array());
		return (count($data))? $data['total_count'] : 0 ;
	}
	
	function get_comment_by_id_zhout($_id_zhout,$_offset = 0 , $_limit = FALSE)
	{
		//$this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS CommentTimeSpent where  id_zhout = '".$p_id."' order by id_comment asc;");//
		$_count_all = $this->count_total_comment_by_id_zhout($_id_zhout);
		$this->db->select('*,UNIX_TIMESTAMP() - date AS CommentTimeSpent');
		$this->db->where('id_zhout',$_id_zhout);
		if($_limit)
		{
			$this->db->limit($_limit,$_offset);
		}else
		{
			//for showing first comment with 2 row record only, offset must set to 2;
			$this->db->limit($_count_all,$_offset);
		}
		$result = $this->db->get('tb_zhout_comment');
		return $result->result_array();
	}
	
	//----------------- ZHOUT MODEL ADD/DELETE ---------------------//
	function add_zhout($_data_zhout = array())
	{
		$this->db->insert('tb_zhopie_zhout',$_data_zhout);
		$_last_insert = $this->db->insert_id();
		$_friends_and_me = $this->get_id_friends($_data_zhout['id_member']);
		$_friends_and_me[] = array('id_member'=>$_data_zhout['id_member']);
		$this->insert_friends_id($_last_insert,$_friends_and_me);
		return $this->get_zhout_by_id_zhout($_last_insert);
		
		
	}
	
	function get_zhout_by_id_zhout($_id_zhout)
	{
		$this->db->select('*');
		$this->db->from('tb_zhopie_zhout');
		$this->db->join('tb_zhopie_profile','tb_zhopie_zhout.id_member =tb_zhopie_profile.id_member');
		$this->db->where('tb_zhopie_zhout.id_zhout',$_id_zhout);
		$_data = $this->db->get();
		
		return array_shift($_data->result_array());
	}
	
	//----------------- END ZHOUT MODEL ADD/DELETE ---------------------//
	
	//----------------- COMMENT MODEL ADD/DELETE ---------------------//
	function add_comment($_data_comment=array())
	{
		$this->db->insert('tb_zhout_comment',$_data_comment);
		$_id_comment = $this->db->insert_id();
		return $this->get_comment_by_id_comment($_id_comment);
	}
	
	function get_comment_by_id_comment($_id_comment)
	{
		$this->db->select('*');
		$this->db->from('tb_zhout_comment');
		$this->db->join('tb_zhopie_profile','tb_zhout_comment.id_member=tb_zhopie_profile.id_member');
		$this->db->where('tb_zhout_comment.id_comment',$_id_comment);
		$_data = $this->db->get();
		return array_shift($_data->result_array());
	}
	
	
	/*----- END NEW MODEL FUNCTIOn ---*/
		
	
	
	function insert_wall($data)
	{
		 $this->db->query("INSERT INTO tb_zhopie_zhout (zhout_content,userip,date,id_member) VALUES('".checkValues($data['value'])."','".$data['user_ip']."','".strtotime(date("Y-m-d H:i:s"))."','".$data['user_id']."')");
			$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent 
				FROM (tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member)
				JOIN tb_picture 
				WHERE  tb_zhopie_zhout.id_member = '".$data['user_id'] ."'and tb_picture.id_member = tb_zhopie_zhout.id_member and tb_picture.folder='profil_picture' and tb_picture.primary=1
			order by tb_zhopie_zhout.id_zhout desc limit 0,1;");
			return $result;
	}
	
	
	function get_wall_id()
	{
		
		$result = $this->db->query('SELECT id_zhout from tb_zhopie_zhout order by id_zhout desc');
		
		return $result;
	}
	
	function get_comment_view($data)
	{
		$result = $this->db->query("SELECT *, UNIX_TIMESTAMP() - date AS CommentTimeSpent FROM tb_zhout_comment where id_zhout = ".$data['p_id']." order by id_comment desc");
		if ($result->num_rows() > 0)
		{
		$kembali = array('result'=>$result,'num_row'=>$result->num_rows());
		return $kembali;	
		}
		
	}
	
	
	function get_post_data_by($p_id)
	{
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent 
				FROM (tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member)
				JOIN tb_picture 
				WHERE  tb_zhopie_zhout.id_zhout = '".$p_id."' and tb_picture.id_member = tb_zhopie_zhout.id_member and tb_picture.folder='profil_picture' and tb_picture.primary=1
order by tb_zhopie_zhout.id_zhout desc limit 0,10;");
		
		return $result;
	}
	
	function get_comment_by($p_id)
	{
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS CommentTimeSpent,(SELECT id_picture from tb_picture where tb_picture.id_member = tb_zhout_comment.id_member and folder = 'profil_picture' and tb_picture.primary = 1)  AS picture_location 
FROM tb_zhout_comment JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhout_comment.id_member  where tb_zhout_comment.id_zhout = '".$p_id."' order by tb_zhout_comment.id_comment asc;");
		return $result;
	}
	
	//------------------------------GET REQUEST TABS FEED MODELS--------------------------//
	
	function get_post_id_you_by($user_id)
	{
	 $this->db->distinct('tb_zhopie_zhout.id_zhout');
	 $this->db->from('tb_zhopie_zhout');
	 $this->db->join('tb_relation_zhout','tb_zhopie_zhout.id_zhout=tb_relation_zhout.id_zhout');
	 $this->db->where('tb_zhopie_zhout.id_member',$user_id);
	 $this->db->where('tb_relation_zhout.id_member_and_friends',$user_id);
	 $this->db->order_by('tb_zhopie_zhout.id_zhout','desc');
	 $this->db->limit(10);
	 $data = $this->db->get();
	 return $data;
	}
	
	
	function get_post_id_you_and_brands($user_id)
	{
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop WHERE  tb_follow.id_member = '".$user_id."' ORDER BY tb_zhopie_zhout.id_zhout DESC LIMIT 0,10";
	$result = $this->db->query($text);
	return $result;
	}
	
	function get_post_id_you_and_friends($user_id)
	{
	$friends = array();
	if(!is_array($user_id))
	{
		$friends[]=$user_id;
	}
	else
	{
		$friends = $user_id;
	}
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE ";
	//LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop // tb_follow.id_member = '004'
	$inc = 0;
	$add_text ="";
	foreach($friends as $value)
	{
		if(!is_array($value))
		{
		$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
		}
		else
		{
			if($inc == 0)
			{
				$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
			}
			else if($inc < count($friends)-1)
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
			}
			else
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
			}
			
	
	    }
	$inc ++;
	}
	
	$text .= $add_text." order by tb_zhopie_zhout.id_zhout desc limit 0,10";
	
		$result = $this->db->query($text);
		return $result;
	
	}
	
	
	function get_post_id_by_newsfeeds($user_id)
	{
	$friends = array();
	if(!is_array($user_id))
	{
		$friends[]=$user_id;
	}
	else
	{
		$friends = $user_id;
	}
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop WHERE ";
	$text .= " tb_relation_zhout.id_member_and_friends ='".$friends[0]['id_member']."'";
	/*$inc = 0;
	$add_text ="";
	foreach($friends as $value)
	{
		if(!is_array($value))
		{
		$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
		}
		else
		{
			if($inc == 0)
			{
				$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
			}
			else if($inc < count($friends)-1)
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
			}
			else
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
			}
			
	
	    }
	$inc ++;
	}*/
	$text .= " order by tb_zhopie_zhout.id_zhout desc limit 0,10";
	//$text .= $add_text." order by tb_zhopie_zhout.id_zhout desc limit 0,10";
		$result = $this->db->query($text);
		return $result;
	}
	//------------------------------ END GET REQUEST TABS FEED MODELS--------------------------//
	
	//------------------------------ GET REQUEST SHOW MORE POST TABS FEED MODELS---------------------//
	function get_post_id_by_newsfeeds_show_more_post($user_id,$offset)
	{
		//$result = $this->db->query("SELECT id_zhout from tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE tb_zhopie_zhout.id_member= '".$user_id."' order by tb_zhopie_zhout.id_zhout desc limit ".$limit.",10");
		//return $result;
		$friends = array();
	if(!is_array($user_id))
	{
		$friends[]=$user_id;
	}
	else
	{
		$friends = $user_id;
	}
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE ";
	$inc = 0;
	$add_text ="";
	
	foreach($friends as $value)
	{
		if(!is_array($value))
		{
		$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
		}
		else
		{
			if($inc == 0)
			{
				$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
			}
			else if($inc < count($friends)-1)
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
			}
			else
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
			}
			
	
	    }
	$inc ++;
	}
	
	$text .= $add_text." order by tb_zhopie_zhout.id_zhout desc limit ".$offset.",10";
	
		$result = $this->db->query($text);
		return $result;
	}
	
	function get_post_id_by_you_show_more_post($user_id,$offset)
	{
		 $this->db->select('tb_zhopie_zhout.id_zhout');
		 $this->db->from('tb_zhopie_zhout');
		 $this->db->join('tb_relation_zhout','tb_zhopie_zhout.id_zhout=tb_relation_zhout.id_zhout');
		 $this->db->where('tb_zhopie_zhout.id_member',$user_id);
		 $this->db->where('tb_relation_zhout.id_member_and_friends',$user_id);
		 $this->db->order_by('tb_zhopie_zhout.id_zhout','desc');
		 $this->db->limit(10,$offset);
		 $data = $this->db->get();
		 return $data;
	}
	
	
function get_post_id_by_you_and_friends_show_more_post($user_id,$offset)
	{
	$friends = array();
	if(!is_array($user_id))
	{
		$friends[]=$user_id;
	}
	else
	{
		$friends = $user_id;
	}
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE ";
	//LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop // tb_follow.id_member = '004'
	$inc = 0;
	$add_text ="";
	foreach($friends as $value)
	{
		if(!is_array($value))
		{
		$add_text .=  "tb_zhopie_zhout.id_member = '".$value."'";
		}
		else
		{
			if($inc == 0)
			{
				$add_text .=  "tb_zhopie_zhout.id_member = '".$value['id_member']."'";
			}
			else if($inc < count($friends)-1)
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'";
			}
			else
			{
				$add_text .= " or tb_zhopie_zhout.id_member ='".$value['id_member']."'" ;
			}
			
	
	    }
	$inc ++;
	}
	
	$text .= $add_text." order by tb_zhopie_zhout.id_zhout desc limit ".$offset.",10";
	
		$result = $this->db->query($text);
		return $result;
	
	}
	
	function get_post_id_by_you_and_brands_show_more_post($user_id,$offset)
	{
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop WHERE  tb_follow.id_member = '".$user_id."' ORDER BY tb_zhopie_zhout.id_zhout DESC LIMIT ".$offset.",10";
	$result = $this->db->query($text);
	return $result;
	}
	
	//------------------------------ END GET REQUEST SHOW MORE POST TABS FEED MODELS---------------------//
	
	//------------------------------CHECK LAST UPDATE TABS MOST COMMENT YOU--------------------------------//
	function check_last_update($user_id)
	{
	$this->db->distinct ('tb_zhopie_zhout.id_zhout');
	$this->db->from('tb_zhopie_zhout');
	$this->db->join('tb_relation_zhout','tb_relation_zhout.id_zhout=tb_zhopie_zhout.id_zhout');
	$this->db->where('tb_zhopie_zhout.id_member',$user_id);
	$this->db->where('tb_relation_zhout.id_member_and_friends',$user_id);
	$this->db->order_by('tb_zhopie_zhout.id_zhout','DESC');
	$this->db->limit(10);
	
	return $this->db->get();
	}
	
	function chek_most_comment($user_id)
	{
	$query = " select tb_zhopie_zhout.id_zhout, (Select COUNT(*) FROM tb_zhout_comment WHERE tb_zhopie_zhout.id_zhout = tb_zhout_comment.id_zhout) as count from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_relation_zhout.id_zhout = tb_zhopie_zhout.id_zhout where tb_relation_zhout.id_member_and_friends ='".$user_id."' AND tb_zhopie_zhout.id_member ='".$user_id."' order by count  DESC LIMIT 0,10;";
	$data = $this->db->query($query);
	
	return $data;
	}
	//------------------------------END CHECK LAST UPDATE MOST COMMENT TABS YOU--------------------------------//
	
	//------------------------------ SHOW MORE POST CHECK LAST UPDATE TABS MOST COMMENT YOU--------------------------------//
	function show_more_post_check_last_update($user_id,$offset)
	{
	$this->db->select ('tb_zhopie_zhout.id_zhout');
	$this->db->from('tb_zhopie_zhout');
	$this->db->join('tb_relation_zhout','tb_relation_zhout.id_zhout=tb_zhopie_zhout.id_zhout');
	$this->db->where('tb_relation_zhout.id_member_and_friends',$user_id);
	$this->db->where('tb_zhopie_zhout.id_member',$user_id);
	$this->db->order_by('tb_zhopie_zhout.id_zhout','DESC');
	$this->db->limit(10,$offset);
	
	return $this->db->get();
	}
	
	function show_more_post_chek_most_comment($user_id,$offset)
	{
	$query = " select tb_zhopie_zhout.id_zhout, (Select COUNT(*) FROM tb_zhout_comment WHERE tb_zhopie_zhout.id_zhout = tb_zhout_comment.id_zhout) as count from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_relation_zhout.id_zhout = tb_zhopie_zhout.id_zhout where tb_relation_zhout.id_member_and_friends ='".$user_id."' AND tb_zhopie_zhout.id_member ='".$user_id."' order by count  DESC LIMIT ".$offset.",10;";
	$data = $this->db->query($query);
	
	return $data;
	}
	//------------------------------END SHOW MORE POST CHECK LAST UPDATE MOST COMMENT TABS YOU--------------------------------//
	
	function get_brand_picture($id_shop)
	{
	 $this->db->select ('*');
	 $this->db->where('id_shop',$id_shop);
	 $data =$this->db->get('tb_picture');
	 $result = $data->result_array();
	 if(count($result) > 0)
	 {
	 return $result[0];
	 }
	 return false;
	}
	function get_has_more_posts($user_id,$limit)
	{
		$result = $this->db->query("SELECT id_zhout from tb_zhopie_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member WHERE tb_zhopie_zhout.id_member = '".$user_id."' order by tb_zhopie_zhout.id_zhout desc limit ".$limit.",10");
		return $result->num_rows();
	}
	
	function  insert_comment ($data)
	{
		//$userip = $_SERVER['REMOTE_ADDR'];
		$userip = "003";
		$result = $this->db->query("INSERT INTO tb_zhout_comment (id_zhout,id_member,comment_content,userip,date,active) VALUES('".$data['id_zhout']."','".$data['id_member']."','".$data['comment_content']."','".$userip."','".strtotime(date("Y-m-d H:i:s"))."','1')");
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS CommentTimeSpent,(SELECT id_picture from tb_picture where tb_picture.id_member = tb_zhout_comment.id_member and folder = 'profil_picture' and tb_picture.primary = 1)  AS picture_location FROM tb_zhout_comment JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhout_comment.id_member WHERE tb_zhout_comment.id_member = '".$data['id_member']."' order by id_comment desc limit 1");
		return $result;
	}
	function set_del_comment($c_id,$user_id)
	{
		//$userip = $_SERVER['REMOTE_ADDR'];
		$userip = "003";
		$result =$this->db->query("delete from tb_zhout_comment where id_comment ='".$c_id."' AND userip ='".$userip."'");
	}
	function set_del_post ($p_id,$user_id)
	{
		//$userip = $_SERVER['REMOTE_ADDR'];
	    if($this->get_id_zhout_by_user_id($p_id,$user_id) > 0)
		{
		$this->db->query("delete from tb_relation_zhout where id_zhout ='".$p_id."'");
		}
		else
		{
		$result = $this->db->query("delete from tb_relation_zhout where id_zhout ='".$p_id."' and id_member_and_friends = '".$user_id."'");
		}
		var_dump($p_id);
		var_dump($user_id);
		
		//$result = $this->db->query("delete from tb_zhopie_zhout where id_zhout ='".$p_id."'");
		//$result = $this->db->query("delete from tb_zhout_comment where id_zhout ='".$p_id."'");
		
	}
	
     function get_id_zhout_by_user_id($post_id,$user_id)
	{
	$data = $this->db->query("select * from tb_zhopie_zhout where id_zhout = '".$post_id."' AND id_member ='".$user_id."'" );
	
	return $data->num_rows();
	
	}
	
    function select_max_zhout_data($id_member)
	{
		$data = $this->db->query("select id_zhout, id_stuff, FROM_UNIXTIME(date) as date, id_shop, count_id_stuff, count_id_shop from tb_zhopie_zhout where id_zhout=(select id_zhout from tb_zhopie_zhout where id_stuff=(select max(id_stuff) from tb_zhopie_zhout where id_member='".$id_member."') and id_member='".$id_member."');");
	
		return $data->result_array();
	}
	
	function insert_zhout($id_member,$id_picture)
	{
		$data_max_zhout_data = $this->select_max_zhout_data($id_member);
		//print_r($data_max_zhout_data);
		$status = 0;
		if(count($data_max_zhout_data)>0)
		{
			$id_zhout = $data_max_zhout_data[0]['id_zhout'];	
			$id_stuff = $data_max_zhout_data[0]['id_stuff'];	
			$date = $data_max_zhout_data[0]['date'];	
			$id_shop = $data_max_zhout_data[0]['id_shop'];
			$count_id_stuff = $data_max_zhout_data[0]['count_id_stuff'];	
			$count_id_shop = $data_max_zhout_data[0]['count_id_shop'];	
			$jum_count_id_stuff = substr_count($count_id_stuff, '#')+1;
		
			$ar_date = explode(' ',$date);
			$ar_tgl = explode('-',$ar_date[0]);
			$tgl = date("z", mktime(0, 0, 0, ''.$ar_tgl[1].'', ''.$ar_tgl[2].'', ''.$ar_tgl[0].''));
			$now_date = date("z", mktime(0, 0, 0, ''.date('m').'', ''.date('d').'', ''.date('Y').''));
			$ar_jam = explode('-',$ar_date[1]);
			//$jam = date("g", mktime(''.$ar_tgl[0].'', ''.$ar_tgl[1].'', ''.$ar_tgl[2].'', ''.$ar_tgl[1].'', ''.$ar_tgl[2].'', ''.$ar_tgl[0].''));
			
			if(($tgl == $now_date) and (date('H') < ($ar_jam[0]+2)))//blm fix
			{
				if(($count_id_stuff == "") or (($count_id_stuff != "") and ($jum_count_id_stuff < 4)))
					$status = 2;
				elseif(($count_id_stuff != "") and ($jum_count_id_stuff == 4))
					$status = 1;
			}
			else
				$status = 1;
		}
		else
			$status = 1;
		
		if($status != 0)
		{
			// 1 = insert
			// 2 = update
			if($status == 1)
			{
				$this->db->set('id_member',$id_member);
				$this->db->set('id_shop','wishlist');
				$this->db->set('date',strtotime(date("Y-m-d H:i:s")));
				$this->db->set('active','1');
				$this->db->set('id_stuff',$id_picture);
				$this->db->insert('tb_zhopie_zhout');
				$last_id = $this->db->insert_id();
				$my_id[]['id_member'] = $id_member;
				$data =$this->get_id_friends($id_member);
				$all_data = array_merge($my_id,$data);
				$this->insert_friends_id($last_id,$all_data);
			}
			else
			{
				if($count_id_stuff!="" or ($count_id_stuff!=0))
					$count_stuff = $count_id_stuff.'#'.$id_picture;
				else
					$count_stuff = $id_picture;
					
				$text = 'whislist';
				
				if($count_id_shop!="" or ($count_id_shop!=0))
					$count_shop = $count_id_shop.'#'.$text;
				else
					$count_shop = $text;
					
				$result = $this->db->query("update tb_zhopie_zhout set count_id_stuff='".$count_stuff."', count_id_shop='".$count_shop."' where id_zhout='".$id_zhout."';");
			}
			$return = 1;
		}
		else
			$return = 0;
		return $return;
		
		/*$this->db->set('id_member',$id_member);
		//$this->db->set('id_shop',$id_shop);
		$this->db->set('date',strtotime(date("Y-m-d H:i:s")));
		$this->db->set('active','1');
		$this->db->set('id_stuff',$id_picture);
		$this->db->insert('tb_zhopie_zhout');
		$last_id = $this->db->insert_id();
		$my_id[]['id_member'] = $id_member;
		$data =$this->get_id_friends($id_member);
		$all_data = array_merge($my_id,$data);
		$this->insert_friends_id($last_id,$all_data);*/
	}
	
	
	
	// ------------------------------------------------------------- QUERY BARU ------------------------------------------------------------------------------ //
	
	/*function insert_wall_ver2($data)
	{
	$is_upload_photo = (isset($data['is_upload_photo'])) ? ",is_upload_photo" : "";
	$is_value =(isset($data['is_upload_photo'])) ? ",'".$data['is_upload_photo']."'" : '';
		 $this->db->query("INSERT INTO tb_zhopie_zhout (zhout_content,userip,date,id_member".$is_upload_photo.") VALUES('".$data['value']."','".$data['user_ip']."','".strtotime(date("Y-m-d H:i:s"))."','".$data['user_id']."'".$is_value.")");
			$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent 
			FROM (tb_picture RIGHT JOIN tb_zhopie_profile ON tb_picture.id_member = tb_zhopie_profile.id_member)
			JOIN tb_zhopie_zhout 
			WHERE  tb_zhopie_profile.id_member = '".$data['user_id'] ."' and tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member
			order by tb_zhopie_zhout.id_zhout desc limit 0,1;");
			return $result;
	}*/
    function insert_wall_ver2($data,$id_member)
	{
	$is_upload_photo = (isset($data['is_upload_photo'])) ? ",is_upload_photo" : "";
	$is_value =(isset($data['is_upload_photo'])) ? ",'".$data['is_upload_photo']."'" : '';
		 $this->db->query("INSERT INTO tb_zhopie_zhout (zhout_content,userip,date,id_member".$is_upload_photo.") VALUES('".$data['value']."','".$data['user_ip']."','".strtotime(date("Y-m-d H:i:s"))."','".$data['user_id']."'".$is_value.")");
			$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent,(SELECT id_picture from tb_picture where tb_picture.id_member = tb_zhopie_zhout.id_member and folder = 'profil_picture' and tb_picture.primary = 1)  AS picture_location 
			FROM (tb_picture RIGHT JOIN tb_zhopie_profile ON tb_picture.id_member = tb_zhopie_profile.id_member)
			JOIN tb_zhopie_zhout 
			WHERE  tb_zhopie_profile.id_member = '".$data['user_id'] ."' and tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member
			order by tb_zhopie_zhout.id_zhout desc limit 0,1;");
			$data = $result->result_array();
	
			$this->insert_friends_id($data[0]['id_zhout'],$id_member);
			return $result;
	}
	
	function get_post_data_by_ver2($p_id)
	{
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent,(SELECT id_picture from tb_picture where tb_picture.id_member = tb_zhopie_zhout.id_member and folder = 'profil_picture' and tb_picture.primary = 1)  AS picture_location
									FROM (tb_picture RIGHT JOIN tb_zhopie_profile ON tb_picture.id_member = tb_zhopie_profile.id_member)
									JOIN tb_zhopie_zhout 
									WHERE  tb_zhopie_zhout.id_zhout =  '".$p_id."' and tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member 
									order by tb_zhopie_zhout.id_zhout desc limit 0,1;");
		return $result;
	}
	
	function get_comment_by_ver2($p_id)
	{
	//RIGHT JOIN tb_picture on tb_zhout_comment.id_member=tb_picture.id_member
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS CommentTimeSpent 
								FROM tb_zhout_comment LEFT JOIN tb_picture on tb_zhout_comment.id_member=tb_picture.id_member
								where id_zhout = '".$p_id."' order by id_comment asc");
		return $result;
	}
	
	function get_picture_stuff_by_id_stuff($id_stuff)
	{
	 $this->db->select('*');
	 $this->db->where('id_stuff',$id_stuff);
	 $this->db->where('primary',1);
	 $data = $this->db->get('tb_picture');
	 $tmp = $data->result_array();
	 $tmp = (isset($tmp[0]))? $tmp[0] : null;
	 return $tmp;
	}
	
	//Check Wishlist if Added
	function is_added_wishlist($id_stuff,$id_member)
	{
	$data = $this->db->query("select * from tb_zhopie_wishlist where id_member = '".$id_member."' AND id_stuff = '".$id_stuff."'");
	return $data->num_rows();
	}
	
	//Delete zhout by id_stuff
	
	function delete_stuff_by_id_stuff($id_stuff)
	{
		//$this->db->query("delete from tb_relation_zhout where  ")
		//$this->db->query("delete  from tb_zhopie_zhout where id_stuff ='".$id_stuff."'");
		
		//new
		$result_1 = $this->db->query("select id_zhout, id_stuff from tb_zhopie_zhout where id_member='".$id_member."' and id_stuff='".$id_stuff."';");
		$result_3 = $this->db->query("select id_zhout, count_id_stuff, count_id_shop from tb_zhopie_zhout where id_member='".$id_member."' and count_id_stuff like "%".$id_stuff."%";");
		if($result_1->num_rows()>0)
		{
			//delete id_zhout
			$data = $result_1->result_array();
			$result_2 = $this->db->query("'delete from tb_zhopie_zhout where id_zhout='".$data[0]['id_zhout']."';");
		}
		elseif($result_3->num_rows()>0)
		{
			//update id_zhout
			$data = $result_1->result_array();
			$ar_count_id_stuff = explode("#", $data[0]['count_id_stuff']);
			$ar_count_id_shop = explode("#", $data[0]['count_id_shop']);
			$no = 0;
			$hasil_id_stuff = '';
			$hasil_id_shop = '';
			foreach($ar_count_id_stuff as $index)
			{
				if($ar_count_id_stuff[$no] != $id_stuff)
				{
					if($no == 0)
					{
						$hasil_id_stuff .= $ar_count_id_stuff[$no];
						$hasil_id_shop .= $ar_count_id_shop[$no];
					}
					else
					{
						$hasil_id_stuff .= '#'.$ar_count_id_stuff[$no];
						$hasil_id_shop .= '#'.$ar_count_id_shop[$no];
					}
				}
				$no++;
			}
			
			$result_2 = $this->db->query("'update tb_zhopie_zhout set count_id_stuff = '".$hasil_id_stuff."' and count_id_shop = '".$hasil_id_shop."' where id_zhout='".$data[0]['id_zhout']."';");
		}
	}
	
	function view_selected_shop($id_shop)
	{
			$data = $this->db->query("SELECT * from tb_zhopie_shop where id_shop='".$id_shop."';");
			$result = $data->result_array();
			return $result[0];
	}
	
	/*function get_wishlist($id_member)
	{
		$result = $this->db->query("select * from tb_zhopie_zhout where id_shop is null and shop_id is null and zhout_content is null and date < UNIX_TIMESTAMP() and date > UNIX_TIMESTAMP(now()- interval 3 HOUR) and id_member='".$id_member."' order by id_zhout desc;");
		return $result;
	}
*/	
	function get_wishlist($id_member)
	{
		$result = $this->db->query("select * from tb_zhopie_zhout where id_shop is null and shop_id is null and zhout_content is null and  id_member='".$id_member."' order by id_zhout desc;");
		return $result;
	}
	
	function get_post_data_by_recentZhout($p_id)
	{
		$result = $this->db->query("SELECT *,UNIX_TIMESTAMP() - date AS TimeSpent,(SELECT id_picture from tb_picture where tb_picture.id_member = tb_zhopie_zhout.id_member and folder = 'profil_picture' and tb_picture.primary = 1)  AS picture_location
									FROM (tb_picture RIGHT JOIN tb_zhopie_profile ON tb_picture.id_member = tb_zhopie_profile.id_member)
									JOIN tb_zhopie_zhout 
									WHERE  tb_zhopie_zhout.id_zhout =  '".$p_id."' and tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member 
									order by tb_zhopie_zhout.id_zhout asc limit 0,10;");
		return $result;
	}
	
	//$temp = $this->db->query('SELECT DISTINCT id_zhout from tb_zhopie_zhout WHERE id_zhout > "'.$id_zhout.'" and id_member = "'.$friends[0]['id_member'].'" order by id_zhout asc limit 0,10');
	
	function get_post_id_by_recentZhout($user_id, $id_zhout)
	{
	$friends = array();
	if(!is_array($user_id))
	{
		$friends[]=$user_id;
	}
	else
	{
		$friends = $user_id;
	}	
	$text = "SELECT DISTINCT tb_zhopie_zhout.id_zhout from tb_zhopie_zhout JOIN tb_relation_zhout ON tb_zhopie_zhout.id_zhout = tb_relation_zhout.id_zhout LEFT JOIN tb_zhopie_profile ON tb_zhopie_profile.id_member = tb_zhopie_zhout.id_member LEFT JOIN tb_follow on tb_follow.id_shop = tb_zhopie_zhout.id_shop WHERE ";
	
	$inc = 0;
	$add_text ="";
	foreach($friends as $value)
	{
		 if(!is_array($value))
		{
		$add_text .=  "tb_relation_zhout.id_member_and_friends = '".$value."' and tb_relation_zhout.id_zhout > ".$id_zhout." ";
		}
		else
		{
			if($inc == 0)
			{
				$add_text .=  "tb_relation_zhout.id_member_and_friends = '".$value['id_member']."' and tb_relation_zhout.id_zhout > ".$id_zhout." ";
			}
			else if($inc < count($friends)-1)
			{
				$add_text .= " or tb_relation_zhout.id_member_and_friends ='".$value['id_member']."' and tb_relation_zhout.id_zhout > ".$id_zhout." ";
			}
			else
			{
				$add_text .= " or tb_relation_zhout.id_member_and_friends ='".$value['id_member']."' and tb_relation_zhout.id_zhout > ".$id_zhout." ";
			}
	    }
	$inc ++;
	}
	$text .= $add_text." order by tb_zhopie_zhout.id_zhout asc limit 0,10";
	$result = $this->db->query($text);
	return $result;
  }
		

		/* ------------- Recent Activity (NV) ---------------*/
	function recent_activity()
	{
		$result = $this->db->query("select distinct a.id_stuff as barang, e.id_member, a.zhout_content, a.id_shop as wishlist, 
									d.id_shop as zhopie_shop, b.first_name, b.last_name, c.name_stuff, a.id_member as zhout_member
									from tb_zhopie_zhout as a
									left join tb_zhopie_profile as b on a.id_member = b.id_member 
									left join tb_stuff as c on a.id_stuff = c.id_stuff 
									left join tb_relation_category as d on c.id_stuff = d.id_stuff
									left join tb_zhopie_shop as e on d.id_shop = e.id_shop 
									order by rand() limit 20;");
		return $result;
	}
		/* ------------- End Recent Activity (NV) ---------------*/	
   }
?>
