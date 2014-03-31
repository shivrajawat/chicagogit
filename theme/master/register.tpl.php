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
<div class="row-fluid margin-top">

  <div class="span12">
    <div class="span9 box-shadow fit">
      <div class="span12 padding-outer-box">
       <div id="response"></div>
       <div id="fullform">
        <form class="form-horizontal" action="" name="register_form" method="post" id="register_form">
          <fieldset>
          <!-- Form Name -->
          <legend>Create an account</legend>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">First Name:<span>*</span></label>
            <div class="controls">
              <input name="first_name" placeholder="Enter First Name" class="input-xlarge"  type="text">
            </div>
          </div>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Last Name:<span>*</span></label>
            <div class="controls">
              <input name="last_name" placeholder="Enter Last Name" class="input-xlarge" type="text">
            </div>
          </div>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Login(Email):<span>*</span></label>
            <div class="controls">
              <input name="email_address" placeholder="Enter Email" class="input-xlarge" type="text">
            </div>
          </div>
          <!-- Password input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Password:<span>*</span></label>
            <div class="controls">
              <input name="password" placeholder="Enter Password" class="input-xlarge" type="password">
            </div>
          </div>
          <!-- Password input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Confirm Password:<span>*</span></label>
            <div class="controls">
              <input name="confirm_password" placeholder="Enter Confirm Password" class="input-xlarge" type="password" id="epasscheck">
            </div>
          </div>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Zip Code:<span>*</span></label>
            <div class="controls">
              <input name="zip_code" placeholder="placeholder" class="input-xlarge" type="text">
            </div>
          </div>         
          <div class="control-group">
            <label class="control-label" for="passwordinput">Telephone Number:(e.g.2083333333)</label>
            <div class="controls">
              <input name="phone_no" placeholder="placeholder" class="input-xlarge" type="text">
            </div>
          </div>
          <!-- Select Basic -->
          <div class="control-group">
            <label class="control-label" for="selectbasic">State:</label>
            <div class="controls">
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
            <label class="control-label" for="selectbasic">City:</label>
            <div class="controls">
              <select  name="city" class="input-xlarge">
                <option>Select City</option>
                 <?php $cityrow = $content->getCitylist();?> 
                <?php foreach ($cityrow as $crow):?>               
                <option value="<?php echo $crow['id'];?>"><?php echo $crow['city_name'];?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          <!-- Select Basic -->
          
          <!-- Multiple Checkboxes -->
          <div class="control-group">
          <label class="control-label" for="checkboxes-0">
              <input name="checkboxes" id="checkboxes-0" value="Option one" type="checkbox"></label>
            <div class="controls">
              <label for="checkboxes">Yes, I occasionally want to be informed on news and specials from Blimpie America's Sub Shop </label>
            </div>
          </div>
          <!-- Multiple Checkboxes -->
          <div class="control-group">
            <label class="control-label" for="checkboxes"><input name="agree" id="checkboxes-0" value="Option one" type="checkbox"></label>
            <div class="controls">
              <label class="checkbox" for="checkboxes-0">
             I Agree to the Terms & Conditions 
             </label>
            </div>
          </div>
          <div class="control-group">           
            <div class="controls">
              <div class="placeholder relative">
                    <input name="captcha" type="text" class="inputbox" size="10" maxlength="5" placeholder="Enter Captcha Code" />
                    <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" />&nbsp;&nbsp;Please enter this shown code to above text field</div>
            </div>
          </div>
          <!-- Button -->
          <div class="control-group">
            
            <div class="controls">
            <input name="reg_submit" value="Create Account" type="submit" class="btn"/>           
            <input name="doRegister" type="hidden" value="1" />
            </div>
          </div>
          </fieldset>
        </form>
        <img src="images/back.png" alt="" /> 
        </div>
        </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <div class="span3">
      <div class="span12 box-shadow padding-outer-box ">
        <h3 class="h4">ORDER SUMMARY</h3>
        <span class="add-details">Location :</span>
        <div class="clr"></div>
        <p class="location">Rapid City,SD, 
          1325 N Elk Vale Road, 
          Rapid City  57703
          (605) 791-1800</p>
        <span class="add-details">Location :</span>
        <p class="location">Rapid City,SD, </p>
        <span class="add-details">Expected Order Time :</span>
        <div class="clr"></div>
        <p>07/15/2012 03:31 PM</p>
        <div class="clr"></div>
      </div>
      <div class="span12 box-shadow  margin-top  padding-outer-box left "> <span class="item-name">Item Name</span> <span class="qty"> Qty</span> <span class="Delete"> Delete</span>
        <div class="clr"></div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon Avocado</span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon </span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon </span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="total-order-amount"> Total Order Amount </div>
        <div class="clr"></div>
        <br />
        <div class="view-cart"><a href="#">View Cart</a></div>
        <img src="images/cart.png" alt="" style="float:left" />
        <div class="clr"></div>
        <br />
        <div class="checkout"><a href="#">CHECKOUT</a></div>
        <img src="images/checkout.png" alt="" style="float:left" />
        <div class="clr"></div>
        <p class="cancel-order">Cancel order & Start Over</p>
      </div>
      <div class="span12 box-shadow  margin-top  padding-outer-box left ">
        <h4 class="h4">USER LOGIN</h4>
        <label>Login</label>
        <input type="text" placeholder="Type something…">
        <label>Password</label>
        <input type="text" placeholder="Type something…">
        <label class="checkbox">
        <input type="checkbox">
        Check me out </label>
        <button type="submit" class="btn">CONTINUE</button>
        <br />
        <br />
        <a href="#" class="forgot">Forgot Password?</a><br />
        <a href="#" class="forgot">Register as a New User</a> </div>
    </div>
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

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
  
  $("#register_form").validate({
	rules: {
		first_name: {
			required: true,
			minlength: 4
		},
		last_name: {
			required: true,
			minlength: 4
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
		zip_code: {
			required: true,
			number: true,
			minlength: 5,
			maxlength: 13
		},
		phone_no: {
			required: true,
			number: true,
			minlength: 10,
			maxlength: 13
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
		email_address: {
			required: "Please provide your email address",
			email: "Please enter a valid email address",
			remote: "Username already in use"
		},
		password: {
			required: "Please provide a password",
			minlength: "Your password must be at least 5 characters long"
		},
		confirm_password: {
			required: "Please provide a password",
			minlength: "Your password must be at least 5 characters long",
			equalTo: "Please enter the same password as above"
		},
		zip_code: "Please Enter your Zipcode",
		phone_no: {
			required: "Please provide your phone number",
			number: "Please enter a valid number.",
			minlength: "Phone Number consist of at least 10 digits",
			maxlength: "Phone Number consist of max 13 digits"
		},
		captcha: {
			required: "Please enter code",			
			remote: "Please enter Same  code as provide"
		},
		agree: "Please accept our policy"
		
	},
	submitHandler: function(form) {
		var str = $("#register_form").serialize(); 
		  $.ajax({
			  type: "POST",
			  url: "ajax/user.php",
			  data: str,
			 success: function (msg){
				  $("html, body").animate({
					  scrollTop: 0
				  }, 600);
				  if(msg == 'Ok')
				  {
				  	  $("#response").html("<h1 style=\"color:#FF0000;\">Thank you for registering with us. You can now start using kulacart</h1>");
					  $("#fullform").hide();
				  }
				  else
				  {
					$("#response").html(msg);  
				  }
				}
			  });
	}
	
});


});
// ]]>
</script>