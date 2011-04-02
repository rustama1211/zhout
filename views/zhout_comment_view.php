<?php if (! $_for_single_ajax)
{ ?>
<div id = "wrap_comment-<?php echo $_id_zhout; ?>" class="people_comment_wrap">
	<?php if ($_show_all_comment)
							{ ?>
			                    <div class="see_comment">
			                        <a href="javascript:void(0)" id="<?php echo $_id_zhout; ?>" onClick ="showMoreComment(this)">see all comment</a>
			                    </div>
					<?php	} ?>
	<?php if (count($_data_comment)) 
		  {
		  	
		  	foreach($_data_comment as $_comment_value) 
			{?>
				<?php
					//Process Time Spent
					$days = floor($_comment_value['CommentTimeSpent'] / (60 * 60 * 24));
					$remainder = $_comment_value['CommentTimeSpent'] % (60 * 60 * 24);
					$hours = floor($remainder / (60 * 60));
					$remainder = $remainder % (60 * 60);
					$minutes = floor($remainder / 60);
					$seconds = $remainder % 60;
					$_timespent = '';
					if($days > 0)
					{
						$_timespent = date('F d Y', $_comment_value['date']);
					}
					elseif($hours > 0 && $hours != 0)
					{
						$_timespent = $hours." hours ago";		
					}
					elseif($days == 0 && $hours == 0 && $minutes > 0)
					{
						$_timespent = $minutes.' minutes ago';
					}
					else
					{
						$_timespent = "few seconds ago";	
					}
					//End Process Time Spent
				   
				?>
                 <div class="people_comment">
                    <div class="pict_people">
                    	<?php 
						//GET default image profile
						$_image_name = directory_map(getcwd().'/assets/zhopie/userfiles/'.$_comment_value['id_member'].'/profile_picture');
						
						?>
                        <img src="<?php echo checkPicture(isset($_image_name[0])?base_url().'/assets/zhopie/userfiles/'.$_comment_value['id_member'].'/profile_picture/'.$_image_name[0] : 'NULL' );?>">
                    </div>
                    <div class="desc_people">
                        <h2><?php echo $_comment_value['first_name'].' '.$_comment_value['middle_name'].' '.$_comment_value['last_name'];?></h2>
                        <p><?php echo $_comment_value['comment_content']; ?></p>
                        <p><?php echo $_timespent; ?></p>
                    </div>
                    <div class="clear"></div>
                    <!--<input type=" text" value="Write Comment" name="">-->
                    <!-- Comment Text Area-->
                </div>
	<?php	 } 
		}?>
                <?php if($_show_comment_input)
					{
				     // echo '<div id="'.$_id_zhout.'">';			 
					  echo $_comment_text;
					  //echo '<a href = "javascript:void(0)" onClick="add_comment(this)">Add Comment</a>';
					  //echo '</div>';	
					}
				 ?>
                <div class="clear"></div>
           
		</div>
<?php
}
else 
{
		if (! isset($_data_comment[0]))
			{
  					//Process Time Spent
					$days = floor($_data_comment['CommentTimeSpent'] / (60 * 60 * 24));
					$remainder = $_data_comment['CommentTimeSpent'] % (60 * 60 * 24);
					$hours = floor($remainder / (60 * 60));
					$remainder = $remainder % (60 * 60);
					$minutes = floor($remainder / 60);
					$seconds = $remainder % 60;
					$_timespent = '';
					if($days > 0)
					{
						$_timespent = date('F d Y', $_data_comment['date']);
					}
					elseif($hours > 0 && $hours != 0)
					{
						$_timespent = $hours." hours ago";		
					}
					elseif($days == 0 && $hours == 0 && $minutes > 0)
					{
						$_timespent = $minutes.' minutes ago';
					}
					else
					{
						$_timespent = "few seconds ago";	
					}
					//End Process Time Spent
				   
				?>
                 <div class="people_comment">
                    <div class="pict_people">
                    	<?php 
						//GET default image profile
						$_image_name = directory_map(getcwd().'/assets/zhopie/userfiles/'.$_data_comment['id_member'].'/profile_picture');
						
						?>
                        <img src="<?php echo checkPicture(isset($_image_name[0])?base_url().'/assets/zhopie/userfiles/'.$_data_comment['id_member'].'/profile_picture/'.$_image_name[0] : 'NULL' );?>">
                    </div>
                    <div class="desc_people">
                        <h2><?php echo $_data_comment['first_name'].' '.$_data_comment['middle_name'].' '.$_data_comment['last_name'];?></h2>
                        <p><?php echo $_data_comment['comment_content']; ?></p>
                        <p><?php echo $_timespent; ?></p>
                    </div>
                    <div class="clear"></div>
                </div>
<?php
		 }
	else
	{
			foreach($_data_comment as $_comment_value) 
			{?>
				<?php
					//Process Time Spent
					$days = floor($_comment_value['CommentTimeSpent'] / (60 * 60 * 24));
					$remainder = $_comment_value['CommentTimeSpent'] % (60 * 60 * 24);
					$hours = floor($remainder / (60 * 60));
					$remainder = $remainder % (60 * 60);
					$minutes = floor($remainder / 60);
					$seconds = $remainder % 60;
					$_timespent = '';
					if($days > 0)
					{
						$_timespent = date('F d Y', $_comment_value['date']);
					}
					elseif($hours > 0 && $hours != 0)
					{
						$_timespent = $hours." hours ago";		
					}
					elseif($days == 0 && $hours == 0 && $minutes > 0)
					{
						$_timespent = $minutes.' minutes ago';
					}
					else
					{
						$_timespent = "few seconds ago";	
					}
					//End Process Time Spent
				   
				?>
                 <div class="people_comment">
                    <div class="pict_people">
                    	<?php 
						//GET default image profile
						$_image_name = directory_map(getcwd().'/assets/zhopie/userfiles/'.$_comment_value['id_member'].'/profile_picture');
						
						?>
                        <img src="<?php echo checkPicture(isset($_image_name[0])?base_url().'/assets/zhopie/userfiles/'.$_comment_value['id_member'].'/profile_picture/'.$_image_name[0] : 'NULL' );?>">
                    </div>
                    <div class="desc_people">
                        <h2><?php echo $_comment_value['first_name'].' '.$_comment_value['middle_name'].' '.$_comment_value['last_name'];?></h2>
                        <p><?php echo $_comment_value['comment_content']; ?></p>
                        <p><?php echo $_timespent; ?></p>
                    </div>
                    <div class="clear"></div>
                    <!--<input type=" text" value="Write Comment" name="">-->
                    <!-- Comment Text Area-->
                </div>
	<?php	 }
		
	} 
}
?>
