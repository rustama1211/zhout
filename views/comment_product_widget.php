<div class="content_comment">
    <h2>COMMENTS(<?php echo $commentproduct->num_rows();?>)</h2>
    <?php 
	//$row1 		= $idzhopiezout->row();
	$id_zhout = '';
	if ($commentproduct->num_rows() > 0)
	{
		foreach($commentproduct->result() as $row)
		{
	?>
        <div class="comment_product_wrap">
        	<div class="pict_comment">
			<?php 
	            if($commentpict[$row->id_member]!=false)
				{
					foreach ($commentpict[$row->id_member] as $key => $row_pic) 
					{
	        ?>
	           			<img src="<?php echo checkPicture(base_url().'assets/zhopie/userfiles/'.$row->id_member.'/profile_picture/'.$row_pic);?>">
	        <?php 
					}
	        	} 
				else
	       		{
			?>
	       	  		<img src="<?php echo checkPicture(null);?>" alt="friend" />
	       	<?php
	       		}
			?>	
            </div>
            <div class="desc_comment">
                <h2><?php echo $row->first_name;?>&nbsp;<?php echo $row->last_name; ?></h2>
                <p><?php echo $row->comment_content; ?></p>
                <p>8 Hours ago</p>
            </div>
            <div class="clear"></div>
        </div>
	<?php
		}
		$id_zhout = $row->id_zhout;
	}
	else
	{
	?>
    	<div class="no_comment_product">
        	No Comment for this product
        </div>
    <?php
	}
	if($id_zhout!='')
	{
	?>
    <div class="input_comment">
      <textarea name="write_comment" id="id_write_comment" ></textarea>
      	<div><a href="javascript:void(0)" onclick="addcoment(<?php echo $id_zhout;?>);">See all</a></div>
      <div class="clear" ></div>
    </div>
    <?php
    }
	?>
</div>