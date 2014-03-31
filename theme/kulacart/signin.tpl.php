<?php
  /**
   * Register 
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<script>
function goback() {
    history.go(-1);
}
</script>

<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--    <div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Login Now </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top">
    <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">
      <div class="span9 fit">
        <div class="span12">
          <div class="row-fluid">
            <div class="span12 top_heading_strip"> Login to your existing account </div>
          </div>
          <div class="span12 fit padding-outer-box">
            <div class="span1">&nbsp; </div>
            <div class="span11">
              <div id="response"></div>
              <div id="fullform" class="span12">
                <div id="msgholder"></div>
                <div id="alt-msgholder"><?php print $core->showMsg;?></div>
                <form class="form-horizontal" action="" name="register_form" method="post" id="register_form">
                  <fieldset>
                    <!-- Form Name -->
                    <!--                <legend></legend>-->
                    <div class="reg-bg span9 box-shadow">
                      <!-- Text input-->
                      <div class="control-group">
                        <label class="control-label span3" for="textinput">Username:<span>*</span></label>
                        <div class="controls span5">
                          <input name="username" id="username" placeholder="Enter username" class="input-medium"  type="text" tabindex="1">
                        </div>
                        <div class="span4">
                          <input name="submit" value="Login" type="submit" tabindex="3" class="btn-2-2" />
                          <!--<input name="submit" value="Login" type="image" src="<?php //echo THEMEURL;?>/images/btn_login.png" tabindex="3"/>-->
                        </div>
                      </div>
                      <!-- Text input-->
                      <div class="control-group">
                        <label class="control-label span3" for="textinput">Password:<span>*</span></label>
                        <div class="controls span5">
                          <input name="password" id="password" placeholder="Enter password" class="input-medium" type="password" tabindex="2">
                        </div>
                      </div>
                    </div>
                    <div class="span12">
                      <div class="span6"> </div>
                      <div class="span6 fit"> <a href="<?php echo SITEURL; ?>/forgetpassword" tabindex="4">Forgot Your Password?</a>
                        <input name="doLogin" type="hidden" value="1" />
                      </div>
                    </div>
                    <div class="span12">
                      <div class="span6"> </div>
                      <div class="span6 fit"> <a href="<?php echo SITEURL; ?>/register" tabindex="5">Not Yet Registered?</a>
                        <input name="doLogin" type="hidden" value="1" />
                      </div>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="span12 fit btn_back_next">
          <div class="span6 fit">
            <div>
            	<a href="javascript:goback()" class="btn-2-2" title="Back">BACK
            	<!--<img src="<?php echo THEMEURL;?>/images/btn_back.png" alt="Back"/>-->
            	</a>
            </div>
          </div>
          <div class="clr"></div>
        </div>
      </div>
      <!-----Product Details END----->
      <!-----RIGHT SEACTION----->
      <?php include("rightside.php");?>
      <!-----RIGHT END----->
      <div class="clr"></div>
       <div id="content-top-shadow"></div>
          <div id="content-bottom-shadow"></div>
          <div id="content-widget-light"></div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div style="display: none;" id="smallLoader">
  <div>
    <div> </div>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
  
  $("#register_form").validate({
	rules: {		
		username: {
			required: true,
			minlength: 4
		},
		password: {
			required: true,
			minlength: 2
		},
	},
	messages: {
		username: "Please provide username",		
		password: {
			required: "Please provide a password",
			minlength: "Password must be at least 2 characters long"
		}
		
	}
	
  });

});


window.onload = function() {
  var input = document.getElementById("username").focus();
}

// ]]>
</script>
