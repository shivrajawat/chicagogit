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
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Retrieve Password </div>
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
            <div class="span12 top_heading_strip"> Forget Password </div>
          </div>
          <div class="span12">
            <div id="response"></div>
            <div id="fullform">
              <div id="msgholder"></div>
              <div id="alt-msgholder">
                <?php // print $core->showMsg;?>
              </div>
              <form class="form-horizontal" action="" name="forgot_form" method="post" id="forgot_form">
                <fieldset>
                  <!-- Form Name -->
                  <div class="reg-bg span10 fit">
                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">Username:<span>*</span></label>
                      <div class="controls">
                        <input name="username" placeholder="Enter Username/Email" class="input-xlarge"  type="text">
                      </div>
                      <div class="control-group">
                        <div class="controls" style="margin-top:10px;">
                          <input type="submit" class="btn-2-2" name="submit" value="Retrieve Password" />
                         
                          <input name="ForgotPassword" type="hidden" value="1" />
                        </div>
                      </div>
                    </div>
                    <!-- Text input-->
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="span12 fit btn_back_next">
          <div class="span12 fit">
            <div class="taright"><a href="javascript:goback()" class="btn-2-2" title="Back">BACK
                </a></div>
          </div>
          <div class="clr"></div>
        </div>
      </div>
      <!-----Product Details END----->
      <!-----RIGHT SEACTION----->
      <?php include("rightside.php");?>
      <!-----RIGHT END----->
       <div id="content-top-shadow"></div>
          <div id="content-bottom-shadow"></div>
          <div id="content-widget-light"></div>
      <div class="clr"></div>
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
  
  $("#forgot_form").validate({
	rules: {		
		username: {
				required: true,
				email: true,
				remote:{url:"<?php echo SITEURL; ?>/ajax/username_exists.php", type:"post"}			
			}
	},
	messages: {
		username: {
				required: "Please provide your Username/Email ",
			    email: "Please provide a valid Username/Email",
				remote: "Username does not exist"
			}
		
	},
	
	submitHandler: function(form) {
				var str = $("#forgot_form").serialize();
				 // $("#displayProcessingProperty").css({display: "block"});
				  $.ajax({
					  type: "POST",
					  url: "ajax/user.php",
					  data: str,
					  success: function (msg){
						  //$("#displayProcessingProperty").css({display: "none"});
					
						/*if(msg =='OK')						
						  $("div#post_your_requirement_success").html(msg);
						else*/
							$("#msgholder").html(msg);
							
							alert("You have successfully reset your password. Please log in to your email to retrieve this password.You can then change your password on your profile page.");
							window.location.href = SITEURL+"/signin";
							
					   }
				});
			  return false;
			}
	
});

});
// ]]>
</script>
