<div class="input_zhout">
	<!-- Zhout Text Area -->
	<?php echo isset($zhout_text) ?$zhout_text:'' ;?>
	<!--<textarea name="zhout" id="watermark">What's on your mind?</textarea>-->
	<!-- Zhout Button -->
	<?php echo $button_zhout; ?>
    <!--<a href="#"><div class="btn_zhout">Zhout</div></a>-->
    <div class="clear"></div>
</div>
<div class="sort_update">
	<div class="sort_by">
    	<ul>
        	
            <!-- Combo Filter by Category  -->
			<?php echo isset($category_filter) ? '<li>Sort by</li><li>'.$category_filter.'</li>':'';?>
               <!-- <select>
                  <option value="volvo">Volvo</option>
                  <option value="saab">Saab</option>
                  <option value="mercedes">Mercedes</option>
                  <option value="audi">Audi</option>
                </select>-->
				<!-- End Combo Filter by Category  -->
            
        </ul>
        <div class="clear"></div>
    </div>
    <div class="update">
    	<ul>
    		<!-- Click Last Update-->
        	<li><a href="#">Last Update</a></li>
			<!-- End Click Last Update-->
            <li>|</li>
			<!-- Click Most Active-->
            <li><a href="#">Most active</a></li>
			<!-- End Click Most Active-->
        </ul>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<div id ="wrap_zhout" >
	<div class="content_status">
		<div class="status">
	    	<div class="pict_wish">
	    		<!-- Pict Member -->
	        	<img src="<?php echo base_url(); ?>assets/zhopie/images/pict_wish.png">
				<!-- End Pict Member -->
	        </div>
			<!-- Date Time Member format  Thursday, at 4:40pm  -->
	        <div class="time">Thursday, at 4:40pm</div>
	        <div class="desc_wish">
	        	<!-- User Name Firt + Mid + Last Name / Product Name -->
	        	<h2 class="name_user">Ciero Mid Blue</h2>
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
						<!-- AddThis Button BEGIN -->
	                    <li><!--<a href="#">SHARE (223)</a>-->
						<div class="addthis_toolbox addthis_default_style "
						  addthis:url="http://example.com/433/23"
       					  addthis:title="An Example Title"
                          addthis:description="An Example Description"
						>
						<a class="addthis_counter addthis_pill_style"></a>
						</div>
						</li>
						<!-- End Add This API for share  -->
	                    <li>|</li>
						<!--Comment Click Dropdown access using ajax  -->
	                    <li><a href="#">COMMENTS (26)</a></li>
						<!-- End Comment Click Dropdown access using ajax  -->
	                </ul>
	                <div class="clear"></div>
	            </div>
	            <div class="clear"></div>
	        </div>
	        <div class="clear"></div>
	    </div>
		<div id = "wrap_comment-<?php echo $_id_zhout = '001'; ?>">
			 <div class="people_comment">
		   		<div class="pict_people">
		        	<img src="<?php echo base_url(); ?>assets/zhopie/images/pict_user.png">
		        </div>
		        <div class="desc_people">
		        	<h2>Kim Kardashian</h2>
		            <p>My Boyfie wants me to buy this one, love it!</p>
		            <p>12 Hours ago</p>
		        </div>
		        <div class="see_comment">
		        	<a href="#">see all comment</a>
		        </div>
		        <div class="clear"></div>
		        <!--<input type=" text" value="Write Comment" name="">-->
				<!-- Comment Text Area-->
				<?php echo $comment_text; ?>
				
	   	    </div>
		</div>
    <div class="clear"></div>
	</div>
</div>
<div class="content_status">
	<div class="status">
    	<div class="pict_wish">
        	<img src="<?php echo base_url(); ?>assets/zhopie/images/pict_wish.png">
        </div>
        <div class="time">Thursday, at 4:40pm</div>
        <div class="desc_wish">
        	<h2 class="name_user">Ciero Mid Blue</h2>
            <p>Adidas</p>
            <p>US$ 900</p>
            <p>Source : Amazon.com</p>
            <div class="action">
            	<ul>
                	<li><a href="#">WISH (103)</a></li> 
                    <li>|</li>
					<div class="addthis_toolbox addthis_default_style "
						  addthis:url="http://example.com/coba/32"
       					  addthis:title="fdfgd"
                          addthis:description="Coba"
						>
						<a class="addthis_counter addthis_pill_style"></a>
						</div>
                    <li><a href="#">SHARE (223)</a></li>
                    <li>|</li>
                    <li><a href="#">COMMENTS (26)</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
	
</div>
<div class="content_status">
	<div class="status">
    	<div class="pict_wish">
        	<img src="<?php echo base_url(); ?>assets/zhopie/images/pict_wish.png">
        </div>
        <div class="time">
        	Thursday, at 4:40pm
            <div class="pict_com">
              <img src="<?php echo base_url(); ?>assets/zhopie/images/sepatu.jpg" />
              <div class="icon_bg_com">50%</div>
          </div>
      </div>
        <div class="desc_wish">
        	<h2 class="name_user">Ciero Mid Blue</h2>
            <p>Adidas</p>
            <p>US$ 900</p>
            <p>Source : Amazon.com</p>
            <div class="action">
            	<ul>
                	<li><a href="#">WISH (103)</a></li> 
                    <li>|</li>
                    <li><a href="#">SHARE (223)</a></li>
                    <li>|</li>
                    <li><a href="#">COMMENTS (26)</a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="people_comment">
   		<div class="pict_people">
        	<img src="<?php echo base_url(); ?>assets/zhopie/images/pict_user.png">
        </div>
        <div class="desc_people">
        	<h2>Kim Kardashian</h2>
            <p>My Boyfie wants me to buy this one, love it!</p>
            <p>12 Hours ago</p>
        </div>
        <div class="see_comment">
        	<a href="#">see all comment</a>
        </div>
        <div class="clear"></div>
        <input type=" text" value="Write Comment" name="">
    </div>
    <div class="clear"></div>
</div> 