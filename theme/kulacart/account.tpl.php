<?php
  /**
   * User Account page 
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>

<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--    <div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> My Account </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top">
 <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">
      <div class="span9  fit">
        <div class="span12">
          <div class="row-fluid">
            <div class="span12 top_heading_strip"> Welcome &nbsp;<?php echo ucwords($customers->customername);?> </div>
          </div>
          <div class="span12">
            <div id="response"></div>
            <br />
            <div class="row-fluid">
              <div class="span12 fit">
                <div class="span1"></div>
                <div class="span10 home_page_images">
                  <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image2.png" alt="" /> </div>
                  <div class="span9">
                    <div class="span12">
                      <h2>Start My Order</h2>
                      <p>Lets get started. Let's go to the Menu.</p>
                    </div>
                    <?php 
					  if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress']))
					  {
					  ?>
                    <div class="span12 taright fit"> 
                    	<a href="<?php echo SITEURL;?>/?location" class="btn-2-2">GET STARTED                        
                   		  <!--<img src="<?php echo THEMEURL;?>/images/btn_get_started.png" alt="Quick Order Here " />-->
                        </a>
                    </div>
                    <?php 
					  }
					  else
					  {
					  ?>
                    <div class="span12 taright fit">
                    	<a href="<?php echo SITEURL;?>/chooselocation" class="btn-2-2"> GET STARTED                       
                        	<!--<img src="<?php echo THEMEURL;?>/images/btn_get_started.png" alt="Quick Order Here " />-->
                        </a> 
                    </div>
                    <?php 
					  }
					  ?>
                  </div>
                </div>
                <div class="span1"></div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12 fit">
                <div class="span1"></div>
                <div class="span10 home_page_images">
                  <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image1.png" alt="" /> </div>
                  <div class="span9">
                    <div class="span12">
                      <h2>Update Your Profile!</h2>
                      <p>Update your profile to keep you upto date!!</p>
                    </div>
                    <div class="span12 taright fit"> 
                    	<a href="<?php echo SITEURL;?>/update-account" title="Update Your Profile" class="btn-2-2">GET STARTED
                    		<!--<img src="<?php //echo THEMEURL;?>/images/btn_get_started.png" alt="Update Pizza Profile" />-->
                        </a>
                    </div>
                  </div>
                </div>
                <div class="span1"></div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12 fit">
                <div class="span1"></div>
                <div class="span10 home_page_images">
                  <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image2.png" alt="" /> </div>
                  <div class="span9">
                    <div class="span12">
                      <h2>Order History</h2>
                      <p>Check order history and reorder your favoriate orders again in quick time.</p>
                    </div>
                    <div class="span12 taright fit"> 
                    	<a href="<?php echo SITEURL;?>/customer-orders" class="btn-2-2">GET STARTED
                        	<!--<img src="<?php echo THEMEURL;?>/images/btn_get_started.png" alt="Find a coupon" />-->
                        </a>
                    </div>
                  </div>
                </div>
                <div class="span1"></div>
              </div>
            </div>
            <div class="span12 fit btn_back_next">
              <div class="span6 fit">
                <div>
                	<a href="javascript:goback()" title="Back" class="btn-2-2">BACK
                    	<!--<img src="<?php echo THEMEURL;?>/images/btn_back.png" alt="Back"/>-->
                    </a>
                </div>
              </div>
              <div class="clr"></div>
            </div>
            <br />
          </div>
        </div>
      </div>
      <!-----Product Details END----->
      <!-----RIGHT SEACTION----->
      <?php include("rightside.php");?>
      <div id="content-top-shadow"></div>
          <div id="content-bottom-shadow"></div>
          <div id="content-widget-light"></div>
      <!-----RIGHT END----->
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div style="display: none;" id="smallLoader">
    <div>
      <div> </div>
    </div>
  </div>
</div>
