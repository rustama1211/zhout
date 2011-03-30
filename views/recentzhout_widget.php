<div class="recent_zhout">
    <h2>Recent <span>Zhouts</span></h2>
</div>
<div class='livefeed' style='height:300px'>
<?php 
if ($recent_activity->num_rows() > 0)
{ 
	$i = 0;
	foreach ($recent_activity->result() as $row_zhout)
   	{	
         if($i<4&&$i>0){
	?>
		<div id=<?php echo $i; ?>>
	<?php	}else{ ?>
		<div style='display:none' id=<?php echo $i; ?>>
<?php }
	?>
		<div class="content_recent">
		
			<!-- ================== PIC USER ================== -->
		    <div class="pict-user">
		    	<?php
		    	if($propic_ra	!= false)
				{
					foreach ($propic_ra as $key => $row_pic) 
					{
		    	?>
		       <img src="<?php echo checkPicture(base_url().'/assets/zhopie/userfiles/'.$row_zhout->zhout_member.'/profile_picture/'.$row_pic);?>">

		        <?php }
		        }else { ?>
	       	  		<img src="<?php echo checkPicture(null);?>" alt="shop" width="178" height="209" border="0" alt="" class="gap2"/>
	       		<?php 	}?>
		    </div>
		    <!-- ================== END OF PIC USER ================== -->
		    
		    <!-- ================== PIC STUFF ================== -->
		    <div class="pict-zhouts">
		    	<?php 
		    	if($row_zhout->barang)
				{
			    	if($stuff_pic[$row_zhout->barang]	!= false)
					{
						foreach ($stuff_pic[$row_zhout->barang] as $key => $stuff_zhout){ 
			    	?>
					   		<img src="<?php echo checkPicture(base_url().'assets/zhopie/userfiles/'.$row_zhout->id_member.'/zhops/'.$row_zhout->zhopie_shop.'/stuff/'.$row_zhout->barang.'/primary/'.$stuff_zhout);?>">
				<?php }
					} 
				}?>
		    </div>
		    <!-- ================== END OF PIC STUFF ================== -->
		    
		    <div class="desc-zhout">
		    	<!-- ================== NAME ================== -->
		        <a href="#">
		        	<?php 
		        	$name = "";
		        	if($row_zhout->first_name){
		        		$name = $row_zhout->first_name;
		        	}
		        	if($row_zhout->last_name){
		        		$name = $name." ".$row_zhout->last_name;
		        	}
		        	
		        	echo $name;?>
		        </a>
		        <!-- ================== END OF NAME ================== -->
		        
		        <!-- ================== ZHOUT CONTENT ================== -->
		        <span>
		        	<?php 
		        		if($row_zhout->zhout_content){
		        			echo $row_zhout->zhout_content;
		        		}else{
		        			if($row_zhout->wishlist == "wishlist"){
		        				echo " just wished a new ".$row_zhout->name_stuff;
		        			}else{
		        				echo " just lounched a new ".$row_zhout->name_stuff;
		        			}
		        		}?></span>
		        <!-- ================== END OF ZHOUT CONTENT ================== -->
		    </div>
		    <div class="clear"></div>
		</div>
	</div>
	<?php
	$i++;
	}
}?>
</div>