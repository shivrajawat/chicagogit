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
<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--    <div class="span4 fit"></div>-->
    <?php include("welcome.php"); ?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Register Now </div>
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
        <div class="row-fluid">
          <div class="span12 top_heading_strip"> Customer Registration </div>
        </div>
        <div class="span12 padding-outer-box">
          <div id="response">
            <?php if(isset($_SESSION['thanks']) && $_SESSION['thanks']!=''){
		       echo $_SESSION['thanks'];
			   $_SESSION['thanks']='';
			   unset($_SESSION['thanks']);
	  	 }?>
          </div>
          <div id="fullform">
            <form class="form-horizontal" action="" name="register_form" method="post" id="register_form">
              <div class="row-fluid">
                <div class="span12">
                  <div class="span10 reg-bg">
                    <fieldset>
                      <!-- Form Name -->
                      <!-- Text input-->
                      <div class="control-group ">
                        <div class="clearfix">
                          <input name="first_name" placeholder="Enter First Name *" class="input-xlarge width100" type="text" maxlength="50" title="First Name" >
                        </div>
                      </div>
                      <!-- Text input-->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="last_name" placeholder="Enter Last Name *" class="input-xlarge width100" type="text" maxlength="50" title="Last Name">
                        </div>
                      </div>
                      <!-- Text input-->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="email_address" placeholder="Enter Login(Email) *" class="input-xlarge width100" type="text" maxlength="50" title="Login(E-mail)">
                        </div>
                      </div>
                      <!-- Password input-->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="password" placeholder="Enter Password *" class="input-xlarge width100" type="password" maxlength="50" title="Password">
                        </div>
                      </div>
                      <!-- Password input-->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="confirm_password" placeholder="Confirm Password *" class="input-xlarge width100" type="password" id="epasscheck" title="Confirm Password" maxlength="50">
                        </div>
                      </div>
                      <!-- Text input-->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="zip_code" placeholder="Enter Zip Code *" class="input-xlarge width100" type="text" maxlength="10" title="Zip Code">
                        </div>
                      </div>
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="phone_no" placeholder="Enter Phone Number *" class="input-xlarge" type="text" maxlength="10" title="Phone Number">
                          
                          <input type="checkbox" name="cell_phone" id="cellphone" value="1"/> Cell Phone
                         
                        </div>
                      </div>
                      <div class="control-group" id="Cell_phone" style="display:none">
                        <div class="clearfix">
                          <label for="receive_msg"> 
                            <input name="receive_msg" id="receive_msg"  value="1" type="checkbox"> Yes I am ok to receive text messages from <?php echo $core->company; ?>
                          </label>
                        </div>
                      </div>
                      <div class="control-group" style="display:none;">
                        <div class="clearfix">
                          <select id="selectbasic" name="state" class="input-xlarge">
                            <option>Select State</option>
                            <?php $staterow = $content->getstatelist();?>
                            <?php foreach ($staterow as $srow):?>
                            <option value="<?php echo $srow['id'];?>"><?php echo $srow['state_name'];?></option>
                            <?php endforeach;?>
                          </select>
                        </div>
                      </div>
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="state" placeholder="Enter state name *" class="input-xlarge width100" type="text" title="State" >
                        </div>
                      </div>
                      <div class="control-group" >
                        <div class="clearfix">
                          <input name="city" placeholder="Enter city name *" class="input-xlarge width100" type="text" title="City" >
                        </div>
                      </div>
                       <?php if($content->show_e_club):?>
                      <div class="control-group">
                        <label class="control-label field-name-a" for="checkboxes-0"></label>
                        <div class="clearfix">
                          <label for="eclub"> <input name="eclub" id="eclub" value="1" type="checkbox" checked="checked"> Signup for the e-Club </label> 
                        </div>
                      </div>
                      <div class="control-group" id="BirthDiv">
                        <label for="passwordinput">Birth Month : 
                        	<select name="birth_month" id="birth_month111111" class="input-medium" title="Birth Month">
                                <option value="" selected="selected">Select Month</option>
                                <option value=01>January</option>
                                <option value=02> February</option>
                                <option value=03>March</option>
                                <option value=04>April</option>
                                <option value=05>May</option>
                                <option value=06>June</option>
                                <option value=07>July</option>
                                <option value=08>August</option>
                                <option value=09>September</option>
                                <option value=10>October</option>
                                <option value=11>November</option>
                                <option value=12>December</option>
                          	</select>
                          </label>
                        <div class="clearfix">                          
                          <!--<select name="birth_date" id="show_birthdate" class="input-small" style="margin-left:30px;"  >
                            <option value="" selected="selected">Date</option>
                          </select>-->
                        </div>
                      </div>
                      <?php endif;?>
                      <!--<div class="control-group" style="display:none;">
                        <label class="control-label field-name-a" for="selectbasic">City:</label>
                        <div class="clearfix">
                          <select  name="city" class="input-xlarge">
                            <option>Select City</option>
                            <?php $cityrow = $content->getCitylist();?>
                            <?php foreach ($cityrow as $crow):?>
                            <option value="<?php echo $crow['id'];?>"><?php echo $crow['city_name'];?></option>
                            <?php endforeach;?>
                          </select>
                        </div>
                      </div>-->
                      <!-- Select Basic -->
                      <!-- Multiple Checkboxes -->
                      
                      <!-- Multiple Checkboxes -->
                      <div class="control-group">
                        <div class="clearfix">
                          <label for="agree"> <input name="agree" id="agree" value="Option one" type="checkbox" checked="checked"> I Agree to the <a href="<?php echo SITEURL; ?>/term-n-condition"> Terms & Conditions</a> </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <div class="clearfix">
                          <div class="placeholder relative">
                            <input name="captcha" type="text" class="inputbox" size="10" maxlength="5" placeholder="Enter Code" title="Captcha Code" />
                            <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" />&nbsp;&nbsp;Please enter code shown here</div>
                        </div>
                      </div>
                      <!-- Button -->
                      <div class="control-group">
                        <div class="clearfix">
                          <input name="reg_submit"  type="submit" class="btn-2-2" value="SIGN UP" />
                          <input name="doRegister" type="hidden" value="1" />
                        </div>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </form>
            <div class="span12 fit btn_back_next">
        <div class="span10"></div>
            <div class="span2">
              <div>
              	<a href="javascript:goback()" class="btn-2-2" title="Back">BACK</a>
              </div>
            </div>            
        <div class="clr"></div>
    </div>
          </div>
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
	
   $("#eclub").click(function(event) {	   
	  
		if ($(this).is(":checked"))
		  $("#BirthDiv").show();
		  
		else
		  $("#BirthDiv").hide();
  });
  
  $("#cellphone").click(function(event) {	   
	  
		if ($(this).is(":checked"))
		  $("#Cell_phone").show();
		  
		else
		  $("#Cell_phone").hide();
  });
});

// ]]>
</script>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
  
  $("#register_form").validate({
	rules: {
		first_name: {
			required: true,
			minlength: 1
		},
		last_name: {
			required: true,
			minlength: 1
		},		
		email_address: {
			required: true,
			email: true,			
			remote:{url:"ajax/test.php", type:"post"}
		},
		password: {
			required: true,
			minlength: 5
		},
		confirm_password: {
			required: true,
			minlength: 5,
			equalTo: "#epasscheck"
		},
		birth_month: {
			required: true
		},
		birth_date: {
			required: true	
		},
		zip_code: {
			required: true,
			number: true,
			minlength: 5,
			maxlength: 5
		},
		phone_no: {
			required: true,
			number: true,	
			minlength: 10,		
			maxlength: 10
		},
		state: {
			required: true,
			minlength: 2,
			accept: "[a-zA-Z]"
		},
		city: {
			required: true,
			minlength: 2,
			accept: "[a-zA-Z]"
		},		
		captcha: {
			required: true,						
			remote:{url:"ajax/captcha.php", type:"post"}
		},
		agree: "required"		
	},
	messages: {
		first_name: "Please provide your first name",
		last_name: "Please provide your last name",
		birth_month: "Provide Month",
		birth_date: "Provide Date",
		email_address: {
			required: "Please provide your email address",
			email: "Please enter a valid email address",
			remote: "Username already in use"
		},
		password: {
			required: "Please provide password",
			minlength: "Your password must be at least 5 characters long"
		},
		confirm_password: {
			required: "Please confirm password",
			minlength: "Your password must be at least 5 characters long",
			equalTo: "Please enter the same password as above"
		},		
		zip_code: {
			required: "Please provide your zip code",
			number: "Please enter a valid zip code.",
			minlength: "zip code must be of 5 digits",			
			maxlength: "zip code must be of 5 digits"
		},
		phone_no: {
			required: "Please provide your phone number",
			number: "Please enter a valid number.",
			minlength: "Phone number consist of min. 10 digits",			
			maxlength: "Phone number consist of max. 10 digits"
		},
		state: {
			required: "Please provide your state name",
			minlength: "State name must be atleast 2 characters long",
			accept: "State name can only be alphabatic"
		},
		city: {
			required: "Please provide your city name",
			minlength: "City name must be atleast 2 characters long",
			accept: "City name can only be alphabatic"
		},
		captcha: {
			required: "Please enter code",					
			remote: "Please enter Same code as provide"
		},
		agree: "Please accept our policy"
		
	},submitHandler: function(form) {
		  var str = $("#register_form").serialize(); 
		  $("#smallLoader").css({display: "block"});
		  
		  $.ajax({
			 type: "POST",
			 url: "<?php echo SITEURL;?>/ajax/user.php",
			 data: str,
			 success: function (msg){
				 
				    if(msg == 1) {
					<?php $webrow = $menu->checkFlow($websitenmae);
						if($webrow['flow']=='1'): ?>
				 		window.location.href = SITEURL+'/checkout';
					<?php else: ?>
						window.location.href = SITEURL+'/chooselocation';
					<?php endif;?>
					}
				 	else if(msg = 2) {
				 		window.location.href = SITEURL+'/account';
					}
				 	else if(msg==3) {
				 		window.location.href = SITEURL+'/account';
					}
			  
	    	   }	
	
   		  });
		
	  }

  });
});
// ]]>
</script>
<script language="javascript">
var requests = 10;

$(document).ready(function() { 
  // Your code here
  // $(window).load(pageid);
});

function request(self)
{if(self.href != "#")
requests -= 1;

if(requests === 0)

var pageid = function () {
    var thisID = null;
    $('#step').click(function () {
        thisID = this.id;
        $('#content').animate({
            height: getPageHeight()
        },
        700, function () {
            $('#next-page').fadeIn(500);
            $('#content-' + thisID).fadeIn(500, function () {});
        });
        return false;
    });
};

function getPageHeight() {
    var windowHeight;
    if (self.innerHeight) windowHeight = self.innerHeight;
    else if (document.documentElement && document.documentElement.clientHeight) windowHeight = document.documentElement.clientHeight;
    else if (document.body) windowHeight = document.body.clientHeight;
    return windowHeight;
}}
</script>
<script type="text/javascript">
// <![CDATA[
$("#birth_month").change(function(){
	
		  var birth_month_id = $(this).val();		  
	  
		  if(birth_month_id != '')
		  {			 
			  $("#smallLoader").css({display: "block"});			  
			  $.ajax({					  
				  type: "POST",
				  url: "<?php echo SITEURL;?>/ajax/showmonth.php",
				  cache: false,
				  data: { showMonth: "1", birth_month_id: birth_month_id },
				  success: function (msg){
					  	
				  	 $("#smallLoader").css({display: "none"});			
				     $("#show_birthdate").html(msg);
					 						
				  }	
			  });	
		  }	
	});	
// ]]>
</script>
