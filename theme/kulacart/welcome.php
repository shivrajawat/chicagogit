<div class="span7">
        <div class="row-fluid">
          <div class="span12 fit" style="text-align:left">
                <h4 class="welcomeh4">
                  <?php if($core->showlogin):?>
                  <?php 
	  if($customers->customerlogged_in):?>
                  <strong>Welcome</strong>&nbsp; <?php echo $customers->customername;?>&nbsp;|&nbsp;<a href="<?php echo SITEURL;?>/account" class="top_signin">MY ACCOUNT</a> | <a href="<?php echo SITEURL;?>/logout.php" class="top_signin">Sign Out</a>
                  <?php else:?>
                  <a href="<?php echo SITEURL;?>/signin" class="top_signin">Sign In <img src="<?php echo THEMEURL;?>/images/top_arrow.png" alt="" /></a>&nbsp;Don't have a Pizza Profile?&nbsp;<a href="<?php echo SITEURL;?>/register" class="top_signin"><img src="<?php echo THEMEURL;?>/images/top_arrow.png" alt="" /> create one</a>
                  <?php endif;?>
                  <!--/ Login End -->
                  <?php endif;?>
                </h4>
          </div>
        </div>
    </div>