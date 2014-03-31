<?php include("header.php");?>
  <?php 
    if (!$customers->customerlogged_in){				  

		echo '<script type="text/javascript">

					window.location.href = "'.SITEURL.'/mobile/login.php";

			 </script>';  

	}
	if(isset($_POST['processUpdateUserData'])):

		$updateuserdata = $customers->UpdateUserData();

		if($updateuserdata == 1):

			$_SESSION['thanks'] = "<span style=\"color:#FF0000\">Your profile was updated successfully.</span>";

		else:

			$error['submit_error'] = "<span style=\"color:#FF0000\">" . _SYSTEM_PROCCESS . "</span>";

		endif;	

	endif;
	
	$row = $customers->getUserData(); 
?>
  <script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
  <?php /*?>
  <div class="pagesnames"> Welcome <?php echo ($row['first_name']) ? ucwords($row['first_name']) : ""; echo ($row['last_name']) ? ' '.ucwords($row['last_name']) : ""; ?></div>
  <?php */?>
  <div data-role="content">
    <h1 class="main-heading">Update Profile </h1>
    <span><b>Billing Address </b></span>
    <div data-role="fieldcontain" id="msgholder">
      <?php  
            if(!empty($_SESSION['thanks'])) echo $_SESSION['thanks'];

                unset($_SESSION['thanks']);                

             if(!empty($error['submit_error'])) echo $error['submit_error'];
        ?>
    </div>
    <form action="" name="update_form" method="post" id="update_form" data-ajax="false">
      <div data-role="fieldcontain">
        <label for="first_name"> <em>* </em> First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo ($row['first_name']) ? ucwords($row['first_name']) : ""; ?>" class="required first_name" placeholder="First Name" title="First Name" />
      </div>
      <div data-role="fieldcontain">
        <label for="last_name"> <em>* </em> Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo ($row['last_name']) ? ucwords($row['last_name']) : ""; ?>" class="required last_name" placeholder="Last Name" title="Last Name" />
      </div>
      <div data-role="fieldcontain">
        <label for="email_address"> Email: </label>
        <input type="text" id="email_address" name="email_address" value="<?php echo ($row['email_id']) ? ucwords($row['email_id']) : ""; ?>"  class=" email_address" title="E-Mail address" placeholder="Login(Email)" disabled />
      </div>
      <div data-role="fieldcontain">
        <label for="password"> Password: </label>
        <input type="password" id="password" name="password" placeholder="Password" title="Password" />
      </div>
      <div data-role="fieldcontain">
        <label for="confirm_password"> Confirm Password:: </label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" title="Confirm Password" />
      </div>      
      <div data-role="fieldcontain">
        <label for="phone_no"> <em>* </em> Telephone Number: </label>
        <input type="text" id="phone_no" name="phone_no" value="<?php echo ($row['phone_number']) ? ucwords($row['phone_number']) : ""; ?>" class="required phone_no" placeholder="Telephone Number:(e.g.2083333333)" maxlength="10" title="Telephone Number" />
      </div>
      <div data-role="fieldcontain">
        <label for="phone_no"> Apt: </label>
        <textarea name="apt" id="apt" placeholder="Provide your Apt/Suite/Floor name" title="Apt"><?php echo ($row['apt']) ? ucwords($row['apt']) : "" ;?></textarea>
      </div>
      <div data-role="fieldcontain">
        <label for="phone_no"> Address1: </label>
        <textarea name="address1" id="address1" title="Address1"><?php echo ($row['address1']) ? ucwords($row['address1']) : "" ;?></textarea>
      </div>
      <div data-role="fieldcontain">
        <label for="phone_no"> Address2: </label>
        <textarea name="address2" id="address2" title="Address2"><?php echo ($row['address2']) ? ucwords($row['address2']) : "" ;?></textarea>
      </div>
      <div data-role="fieldcontain">
        <label for="state"> <em>* </em> State: </label>
        <input type="text" id="state" name="state" title="State" value="<?php echo ($row['state']) ? ucwords($row['state']) : ""; ?>" class="required state" placeholder="State name" />
      </div>
      <div data-role="fieldcontain">
        <label for="city"> <em>* </em> City: </label>
        <input type="text" id="city" name="city" title="City" value="<?php echo ($row['city']) ? ucwords($row['city']) : ""; ?>" class="required city" placeholder"City" />
      </div>
      <div data-role="fieldcontain">
        <label for="zip_code"> <em>* </em> Zip Code: </label>
        <input type="text" id="zip_code" name="zip_code" title="Zip Code" value="<?php echo ($row['zipcode']) ? ucwords($row['zipcode']) : ""; ?>" class="required zip_code" placeholder="Zip Code" />
      </div>
      <span><b>Delivery Address </b></span>
      <div data-role="fieldcontain">
        <label>
          <input name="billingtoo" id=""  type="checkbox" onclick="FillBilling(this.form)">
          Same as billing address</label>
      </div>
      <div data-role="fieldcontain">
        <label for="phone_no"> Address1: </label>
        <textarea name="d_address1" id="d_address1" title="Address1"><?php echo ($row['d_address1']) ? ucwords($row['d_address1']) : "" ;?></textarea>
      </div>
      <div data-role="fieldcontain">
        <label for="phone_no"> Address2: </label>
        <textarea name="d_address2" id="d_address2" title="Address2"><?php echo ($row['d_address2']) ? ucwords($row['d_address2']) : "" ;?></textarea>
      </div>
      
      <div data-role="fieldcontain">
        <label for="state"> State: </label>
        <input type="text" id="dstate_id" name="dstate" title="State" value="<?php echo ($row['dstate']) ? ucwords($row['dstate']) : ""; ?>" class="state" placeholder="State" />
      </div>
      <div data-role="fieldcontain">
        <label for="city"> City: </label>
        <input type="text" id="dcity_id" name="dcity" title="City" value="<?php echo ($row['dcity']) ? ucwords($row['dcity']) : ""; ?>" class="city" placeholder"City" />
      </div>
      <div data-role="fieldcontain">
        <label for="zip_code"> Zip Code: </label>
        <input type="text" id="dzipcode" name="dzipcode" title="Zip Code" value="<?php echo ($row['dzipcode']) ? ucwords($row['dzipcode']) : ""; ?>" class="zip_code" placeholder="Zip Code" />
      </div>
      <?php if($content->show_e_club):	?>
        <div data-role="fieldcontain">
            <input type="checkbox" id="checkbox-1a" name="eclub" value="1" <?php echo ($row['e_club'] && $row['e_club']==1 ) ? 'checked="checked"' : ""; ?> class="eclub">
            <label for="checkbox-1a">Signup for the e-Club</label>
        </div> 
          <?php if(isset($row['e_club']) && !empty($row['e_club']) && isset($row['birthday']) && !empty($row['birthday']) ) { $displayBirthdayStyle = 'style="display:block"';} else { $displayBirthdayStyle = 'style="display:none"'; } ?>       
        <div data-role="fieldcontain" id="BirthDiv" <?php echo $displayBirthdayStyle; ?>>
        	<select name="birth_month" id="select-native-1" class="picktimedate" title="Birth Month" >
            	<option value="" selected="selected">Select Birth Month</option>
                <option value="01" <?php echo ($row['birthday'] && $row['birthday']=='01') ? 'selected="selected"': ""; ?> >January</option>
                <option value="02" <?php echo ($row['birthday'] && $row['birthday']=='02') ? 'selected="selected"': ""; ?>> February</option>
                <option value="03" <?php echo ($row['birthday'] && $row['birthday']=='03') ? 'selected="selected"': ""; ?>>March</option>
                <option value="04" <?php echo ($row['birthday'] && $row['birthday']=='04') ? 'selected="selected"': ""; ?>>April</option>
                <option value="05" <?php echo ($row['birthday'] && $row['birthday']=='05') ? 'selected="selected"': ""; ?>>May</option>
                <option value="06" <?php echo ($row['birthday'] && $row['birthday']=='06') ? 'selected="selected"': ""; ?>>June</option>
                <option value="07" <?php echo ($row['birthday'] && $row['birthday']=='07') ? 'selected="selected"': ""; ?>>July</option>
                <option value="08" <?php echo ($row['birthday'] && $row['birthday']=='08') ? 'selected="selected"': ""; ?>>August</option>
                <option value="09" <?php echo ($row['birthday'] && $row['birthday']=='09') ? 'selected="selected"': ""; ?>>September</option>
                <option value="10" <?php echo ($row['birthday'] && $row['birthday']=='10') ? 'selected="selected"': ""; ?>>October</option>
                <option value="11" <?php echo ($row['birthday'] && $row['birthday']=='11') ? 'selected="selected"': ""; ?>>November</option>
                <option value="12" <?php echo ($row['birthday'] && $row['birthday']=='12') ? 'selected="selected"': ""; ?>>December</option>        
        	</select>
        </div>
      <?php endif;?>
      <div data-role="fieldcontain">
        <label>
          <input name="notification" id="notification" type="checkbox" value="1" <?php getChecked($row['notification'], 1); ?> >
          Receive Text Notifications for your Order and Deals via Text</label>
      </div>
      <div data-role="fieldcontain">
        <label>
          <input name="occasionally" type="checkbox" value="1" <?php getChecked($row['occasionally'], 1); ?> >
          Yes, please sign me up for <?php echo $core->company; ?> E-club to receive news and specials plus a treat on my birthday.
          <?php //echo $product->getCompanyname($locationid); ?>
        </label>
      </div>
      <div data-role="fieldcontain">
        <input name="agree" id="checkbox-2b" type="checkbox">
        <label for="checkbox-2b">Agree to the <a href="<?php echo SITEURL; ?>/term-n-condition"> Terms & Conditions</a></label>
      </div>
      <div class="ui-body ui-body-b">
        <input type="submit" name="submit" value="UPDATE ACCOUNT" class="btnLogin">
      </div>
      <a href="javascript:goback()"  data-role="button" data-icon="arrow-l" data-iconpos="left" data-ajax="false">BACK</a> 
      <input name="processUpdateUserData" type="hidden" value="1" />
    </form>
  </div>
  
  <!-- /content --> 
  
</div>
<?php include("footer.php");?>
<script type="text/javascript">
// <![CDATA[
    $(document).ready(function() {
		
       $("#update_form").validate({
			rules: {
		
				first_name: {		
					required: true,		
					minlength: 4		
				},
				last_name: {
					required: true,
					minlength: 4
				},
				
				zip_code: {
					required: true,
					number: true,
					minlength: 5,
					maxlength: 5
		
				},
				dzipcode: {
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
				}
			},

			messages: {
				first_name: "Please provide your first name",
				last_name: "Please provide your last name",
				
				zip_code: {
					required: "Please Enter your Zip Code",
					number: "Please enter valid zip code.",
					minlength: "Zip Code must be of 5 digits",
					maxlength: "Zip Code must be of 5 digits"	
				},
				dzipcode: {
					number: "Please enter a valid zip code.",
					minlength: "Zip code must be of 5 digits",
					maxlength: "Zip code must be of 5 digits"
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
					accept: "State can only be alphabatic"
				},
				city: {
					required: "Please provide your city name",
					minlength: "City must be atleast 2 characters long",
					accept: "City can only be alphabatic"
				}	
			}	
       });
   });
   
   

	function FillBilling(f) {	
	
	  if(f.billingtoo.checked == true) {	  
	
		 f.d_address1.value = f.address1.value;
	
		 f.d_address2.value = f.address2.value; 
	
		 f.dzipcode.value = f.zip_code.value; 
	
		 f.dstate_id.value = f.state.value; 
	
		 f.dcity_id.value = f.city.value;
	
	  }
	
	  if(f.billingtoo.checked == false) {
	
		f.d_address1.value = '';
	
		f.d_address2.value = '';
	
		f.dzipcode.value = '';
	
		f.dstate_id.value = '';  	
	
		f.dcity_id.value = '';  
	  }
	
	}
	
	
	$(document).ready(function(){
		
		
		$("#phone_no").blur(function(){
			var m = $(this).val();	
			if(m!='' && $.isNumeric(m)){
	
				 $("#notification").attr({"checked":"checked"});
			}
		});
		
		$(".eclub").click(function(event){	   
	  	  
			if ($(this).is(":checked"))
			  $("#BirthDiv").show();
			  
			else
			  $("#BirthDiv").hide();
  	   });
		
	});
	
	
	function goback() {
	
		history.go(-1);
	
	}
// ]]>
</script>
