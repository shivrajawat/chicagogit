<?php include("header.php");?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
    <div data-role="content">
      <h1 class="main-heading"> Register Now </h1>
     <div data-role="fieldcontain" id="msgholder"></div>
      <form action="" name="register_form" method="post" id="register_form" data-ajax="false">
        <div data-role="fieldcontain">          
          <input type="text" id="first_name" name="first_name" class="required first_name" placeholder="First Name" title="First Name" />
        </div>
        <div data-role="fieldcontain">
          <input type="text" id="last_name" name="last_name" class="required last_name" placeholder="Last Name" title="Last Name" />
        </div>
        <div data-role="fieldcontain">
          <input type="text" id="email_address" name="email_address" class="required email_address" placeholder="Login(Email)" title="E-Mail Address" />
        </div>        
        <div data-role="fieldcontain">
          <input type="password" id="password" name="password" class="required" placeholder="Password" title="Password" />
        </div>
        <div data-role="fieldcontain">
          <input type="password" id="confirm_password" name="confirm_password" class="required" placeholder="Confirm Password" title="Confirm Password" />
        </div>
        <div data-role="fieldcontain">
          <input type="text" id="zip_code" name="zip_code" class="required zip_code" placeholder="Zip Code" title="Zip Code" />
        </div>
        <div data-role="fieldcontain">         
          <input type="text" id="phone_no" name="phone_no" class="required phone_no" placeholder="Telephone Number:(e.g.2083333333)" maxlength="10" title="Telephone Number" />
        </div>
        <div data-role="fieldcontain">
          <input type="text" id="state" name="state" class="required state" placeholder="State" title="State" />
        </div>        
        <div data-role="fieldcontain">          
          <input type="text" id="city" name="city" class="required city" placeholder="City" title="City" />
        </div>  
        <div data-role="fieldcontain"> 
            <input id="searchTextField" type="text" class="deliveryarloc ui-input-text ui-body-c" name="apt" placeholder="Apt/Suite/Floor" title="Apt/Suite/Floor" />
        </div>
        <?php if($content->show_e_club):?>
        <div data-role="fieldcontain">
            <input type="checkbox" id="checkbox-1a" name="eclub" value="1" checked="checked" class="eclub">
            <label for="checkbox-1a">Signup for the e-Club</label>
        </div>
        <div data-role="fieldcontain" id="BirthDiv">
        	<select name="birth_month" id="select-native-1" class="picktimedate" title="Bith Month" >
            	<option value="" selected="selected">Select Birth Month</option>
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
        </div>
        <?php endif;?>
        <div data-role="fieldcontain">
        <input name="agree" id="checkbox-2a" type="checkbox">
    	<label for="checkbox-2a">Agree to the <a href="<?php echo SITEURL; ?>/term-n-condition"> Terms & Conditions</a></label>
        </div>
        
        <div class="ui-body ui-body-b">
          <input type="submit" name="submit" value="Register" class="btnLogin" data-ajax="false">
        </div>        
        <input name="doRegisterMobile" type="hidden" value="1" />
      </form>
    </div>
    <a href="index.php" data-role="button" data-ajax="false">Back to Home</a>
    <!-- /content -->
  </div>
 <?php include("footer.php");?>
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
			remote:{url:"<?php echo SITEURL; ?>/ajax/test.php", type:"post"}
		},
		password: {
			required: true,
			minlength: 5
		},
		confirm_password: {
			required: true,
			minlength: 5,
			equalTo: "#confirm_password"
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
			required: "Please confirm password",
			minlength: "Your password must be at least 5 characters long",
			equalTo: "Please enter the same password as above"
		},
		zip_code: {
			required: "Please Enter your Zip Code",
			number: "Please enter valid zip code.",
			minlength: "Zip Code must be of 5 digits",
			maxlength: "Zip Code must be of 5 digits",

		},
		phone_no: {
			required: "Please provide your phone number",
			number: "Please enter a valid number.",			
			minlength: "Phone number consist of min. 10 digits",			
			maxlength: "Phone number consist of max. 10 digits"
		},
		state: {
			required: "Please provide your state name",
			minlength: "State must be atleast 2 characters long",
			accept: "State name can only be alphabatic"
		},
		city: {
			required: "Please provide your city name",
			minlength: "City must be atleast 2 characters long",
			accept: "City name can only be alphabatic"
		},		
		agree: "Please accept our policy",
		
	},submitHandler: function(form) {
		
			 	var SITEURL = '<?php echo SITEURL; ?>';
			  	var str = $("#register_form").serialize(); 
			  	$("#smallLoader").css({display: "block"});
		  
				$.ajax({
					 type: "POST",
					 url: "<?php echo SITEURL;?>/ajax/user.php",
					 data: str,
					 success: function (msg){				   
						if(msg ==1) {
							window.location.href = '<?php echo SITEURL; ?>/mobile/checkout.php';
						}
						else if(msg ==2) {
							window.location.href = '<?php echo SITEURL; ?>/mobile/account.php';
						}			 	
					}
   		  		});
	   }
	
	});
	
	
	 $(".eclub").click(function(event) {	   
	  	  
			if ($(this).is(":checked"))
			  $("#BirthDiv").show();
			  
			else
			  $("#BirthDiv").hide();
  	});
	
 });
 // ]]>
</script>
