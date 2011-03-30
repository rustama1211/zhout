<?php ?>
<div class="content_status" id="zhout-<?php echo $id_zhout; ?>">
   <div class="status">
    	<div class="pict_wish">
    		<!-- Pict Member -->
        	<!--<img src="<?php echo $profil_picture_url; ?>">-->
			<img src="<?php echo $profil_picture_url ; ?>">
			<!-- End Pict Member -->
        </div>
		<!-- Date Time Member format  Thursday, at 4:40pm  -->
        <div class="time"><?php echo $timespent = date('l,', $date).' at '.date('H:ia', $date);  ?></div>
        <div class="desc_wish">
        	<!-- User Name Firt + Mid + Last Name  -->
        	<h2 class="name_user"><?php echo $first_name.' '.$middle_name.' '.$last_name;  ?></h2>
			<!-- Product Name  -->
            <p>Adidas</p>
			<!-- Product Price  -->
            <p>US$ 900</p>
			<!-- Product Source  -->
            <p>Source : Amazon.com</p>
            <div class="action">
            	<ul>
            		<!-- Click Wish pake ajax untuk dropdown  -->
                	<li><a href="#">WISH (103)</a></li> 
					<!-- Click End Wish  -->
                    <li>|</li>
					<!-- Add This API for share  -->
                    <li><a href="#">SHARE (223)</a></li>
					<!-- End Add This API for share  -->
                    <li>|</li>
					<!--Comment Click Dropdown access using ajax  -->
                    <li><!--<a href="#">COMMENTS (26)</a>--> <?php echo isset($_comment_button)?$_comment_button:''?></li>
					<!-- End Comment Click Dropdown access using ajax  -->
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>

