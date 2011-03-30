<div id = "wrap_comment-<?php echo $_id_zhout; ?>" class="people_comment_wrap">
	<?php if (count($_data_comment)) 
		  {
		  	foreach($_data_comment as $_comment_value) 
			{?>
				<?php
					//Process Time Spent
					$days = floor($_comment_value['TimeSpent'] / (60 * 60 * 24));
					$remainder = $post['TimeSpent'] % (60 * 60 * 24);
					$hours = floor($remainder / (60 * 60));
					$remainder = $remainder % (60 * 60);
					$minutes = floor($remainder / 60);
					$seconds = $remainder % 60;
					$_timespent = '';
					if($days > 0)
					{
						$_timespent = date('F d Y', $post['date']);
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
                        <img src="<?php echo $_comment_value['profil_picture_url'];?>">
                    </div>
                    <div class="desc_people">
                        <h2><?php echo $_comment_value['first_name'].' '.$_comment_value['middle_name'].' '.$_comment_value['last_name'];?></h2>
                        <p><?php echo $_comment_value['comment_content']; ?></p>
                        <p><?php echo $_timespent; ?></p>
                    </div>
					<?php if ($_show_all_comment)
							{ ?>
			                    <div class="see_comment">
			                        <a href="javascript:void(0)" id="<?php echo $_id_zhout; ?>" onClick ="show_more_comment_javascript(this)">see all comment</a>
			                    </div>
					<?php	} ?>
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
