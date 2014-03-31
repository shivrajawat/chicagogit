<?php
  /**
   * Register 
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');	
	  
	  $locationrow = $menu->locationIdByMenu($websitenmae);
	  $locationid = $locationrow['location'];  

?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--<div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Update Account </div>
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
          <div class="span12 top_heading_strip"> Update My Account details </div>
        </div>
        <div class="span12 padding-outer-box">
          <div id="response">
            <?php  
				if(!empty($_SESSION['thanks'])) echo $_SESSION['thanks'];
          			unset($_SESSION['thanks']);
					
				 if(!empty($error['submit_error'])) echo $error['submit_error'];
			?>
          </div>
          <div id="fullform">
            <form class="form-horizontal" action="" name="update_form" method="post" id="update_form" style="margin:0">
              <div class="span12">
                <div class="span11">
                  <fieldset>
                    <div class="control-group">
                      <label class="control-label" for="textinput">First Name:<span>*</span></label>
                      <div class="controls">
                        <input name="first_name"  class="input-xlarge"  type="text" value="<?php echo $row['first_name'];?>">
                      </div>
                    </div>
                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">Last Name:<span>*</span></label>
                      <div class="controls">
                        <input name="last_name" class="input-xlarge" type="text" value="<?php echo $row['last_name'];?>">
                      </div>
                    </div>
                    <!-- Text input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">Login(Email):</label>
                      <div class="controls">
                        <input name="email_address" class="input-xlarge" type="text" value="<?php echo $row['email_id'];?>" disabled="disabled">
                      </div>
                    </div>
                    <!-- Password input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">Password:</label>
                      <div class="controls">
                        <input name="password" class="input-xlarge" type="password" >
                      </div>
                    </div>
                    <!-- Password input-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">Confirm Password:</label>
                      <div class="controls">
                        <input name="confirm_password" class="input-xlarge" type="password" id="epasscheck" >
                      </div>
                    </div>                    
                    <div class="control-group">
                      <label class="control-label" for="cellphone">Telephone Number:</label>
                      <div class="controls">
                          <input name="phone_no" value="<?php echo ($row['phone_number']) ? $row['phone_number'] : ""; ?>" placeholder="Enter Phone Number" class="input-xlarge" type="text" maxlength="10" style="width:40%">                     
                          <input type="checkbox" name="cell_phone" id="cellphone" value="1" <?php echo ($row['cell_phone'] && $row['cell_phone']==1 ) ? 'checked="checked"' : ""; ?>/>  Cell Phone
                          
                        </div>
                      </div>
                      <?php 
					  		if(isset($row['receive_msg']) && !empty($row['receive_msg'])) { $displayReceiveStyle = 'style="display:block"'; } 
								else { $displayReceiveStyle = 'style="display:none"'; } 
					  ?>
                      <div class="control-group" id="Cell_phone" <?php echo $displayReceiveStyle; ?>>
                        <div class="controls">
                          <label for="receive_msg"><input name="receive_msg" id="receive_msg" value="1" type="checkbox" <?php echo ($row['receive_msg'] && $row['receive_msg']==1 ) ? 'checked="checked"' : ""; ?>> Yes I am ok to receive text messages from <?php echo $core->site_name; ?></label>
                        </div>
                      </div>
                    <legend>Billing Address</legend>
                    <div class="control-group">
                      <label class="control-label" for="textinput">Apt/Suite/Floor:</label>
                      <div class="controls">
                        <input name="apt" id="apt" placeholder="Provide your Apt/Suite/Floor name" class="input-xlarge" type="text" value="<?php echo ($row['apt']) ? $row['apt'] : "" ;?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="selectbasic">Address1:</label>
                      <div class="controls">
                        <textarea name="address1" id="address1" style="width:268px;" placeholder="Provide your address"><?php echo $row['address1'];?></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="selectbasic">Address2:</label>
                      <div class="controls">
                        <textarea name="address2" id="address2" style="width:268px;"><?php echo $row['address2'];?></textarea>
                      </div>
                    </div> 
                    <!-- Select Basic -->
                    <!-- <div class="control-group">
                        <label class="control-label" for="selectbasic">State:</label>
                        <div class="controls">
                          <select id="state" name="state" class="input-xlarge">
                            <option>Select State</option>
                             <?php //$staterow = $content->getstatelist();?> 
                            <?php //foreach ($staterow as $srow):?>    
                            <?php //$sel = ($row['state_id'] == $srow['id']) ? ' selected="selected"' : '' ;?>           
                            <option value="<?php //echo $srow['id'];?>"<?php //echo $sel;?>><?php //echo $srow['state_name'];?></option>
                            <?php //endforeach;?>
                          </select>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label" for="selectbasic">City:</label>
                        <div class="controls">
                          <select  name="city" id="city" class="input-xlarge">
                            <option>Select City</option>
                             <?php //$cityrow = $content->getCitylist();?> 
                            <?php //foreach ($cityrow as $crow):?>    
                            <?php //$sel = ($row['city_id'] == $crow['id']) ? ' selected="selected"' : '' ;?>           
                            <option value="<?php //echo $crow['id'];?>"<?php //echo $sel;?>><?php //echo $crow['city_name'];?></option>
                            <?php //endforeach;?>
                          </select>
                        </div>
                      </div>-->
                    <div class="control-group">
                      <label class="control-label" for="textinput">State:<span>*</span></label>
                      <div class="controls">
                        <input name="state" id="state" placeholder="Provide your state name" class="input-xlarge" type="text" value="<?php echo ($row['state']) ? $row['state'] : "" ;?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="textinput">City:<span>*</span></label>
                      <div class="controls">
                        <input name="city" id="city" placeholder="Provide your city name" class="input-xlarge" type="text" value="<?php echo ($row['city']) ? $row['city'] : "";?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="textinput">Zip Code:</label>
                      <div class="controls">
                        <input name="zip_code" id="zip_code" class="input-xlarge" type="text" value="<?php echo $row['zipcode'];?>" placeholder="Provide your zip code">
                      </div>
                    </div>
                    <!-- Select Basic -->
                    <legend>Delivery Address: </legend>
                    <div class="control-group">
                      <div class="controls">
                        <label for="billingtoo"><input type="checkbox" name="billingtoo" onclick="FillBilling(this.form)" id="billingtoo" > Same as billing address</label>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="selectbasic">Address1:</label>
                      <div class="controls">
                        <textarea name="d_address1" id="d_address1" style="width:268px;"><?php echo $row['d_address1'];?></textarea>
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="selectbasic">Address2:</label>
                      <div class="controls">
                        <textarea name="d_address2" id="d_address2" style="width:268px;"><?php echo $row['d_address2'];?></textarea>
                      </div>
                    </div>
                    <!-- Select Basic -->
                    <!-- <div class="control-group">
                    <label class="control-label" for="selectbasic">State:</label>
                    <div class="controls">
                      <select id="dstate_id" name="dstate_id" class="input-xlarge">
                        <option value="">Select State</option>
                         <?php $staterow = $content->getstatelist();?> 
                        <?php foreach ($staterow as $dsrow):?>          
                        <?php $sel = ($row['dstate_id'] == $dsrow['id']) ? 'selected="selected"' : '' ;?>      
                        <option value="<?php echo $dsrow['id'];?>"<?php echo $sel;?>><?php echo $dsrow['state_name'];?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="selectbasic">City:</label>
                    <div class="controls">
                      <select  name="dcity_id" id="dcity_id" class="input-xlarge">
                        <option value="">Select City</option>
                         <?php $cityrow = $content->getCitylist();?> 
                        <?php foreach ($cityrow as $dcrow):?>    
                        <?php $sel = ($row['dcity_id'] == $dcrow['id']) ? 'selected="selected"' : '' ;?>           
                        <option value="<?php echo $dcrow['id'];?>"<?php echo $sel;?>><?php echo $dcrow['city_name'];?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>-->
                    <!-- Select Basic -->
                    <div class="control-group">
                      <label class="control-label" for="textinput">State:</label>
                      <div class="controls">
                        <input name="dstate" id="dstate_id" placeholder="Provide your state name" class="input-xlarge" type="text" value="<?php echo ($row['dstate']) ? $row['dstate'] : "" ;?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label" for="textinput">City:</label>
                      <div class="controls">
                        <input name="dcity" id="dcity_id" placeholder="Provide your city name" class="input-xlarge" type="text" value="<?php echo ($row['dcity']) ? $row['dcity'] : "";?>">
                      </div>
                    </div>
                    <div class="control-group">
                      <label class="control-label checkbox2" for="textinput">Zip Code:</label>
                      <div class="controls">
                        <input name="dzipcode" id="dzipcode" class="input-xlarge" type="text" value="<?php echo $row['dzipcode'];?>" placeholder="Provide your zip code">
                      </div>
                    </div>                    
                    <?php  if($content->show_e_club):?>
                    <div class="control-group">
                      <div class="controls">
                        <label for="eclub"><input name="eclub" id="eclub" value="1" type="checkbox"  <?php echo ($row['e_club'] && $row['e_club']==1 ) ? 'checked="checked"' : ""; ?> > Signup for the e-Club </label>
                      </div>
                    </div> 
                     <?php if(isset($row['e_club']) && !empty($row['e_club']) && isset($row['birthday']) && !empty($row['birthday']) ) { $displayBirthdayStyle = 'style="display:block"';} else { $displayBirthdayStyle = 'style="display:none"'; } ?>
                    <div class="control-group" id="BirthDiv" <?php echo $displayBirthdayStyle; ?>>
                        <label class="control-label checkbox2" for="textinput">Birthday</label>
                        <div class="controls">
                          <select name="birth_month" id="birth_month111111" class="input-medium">
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
                      </div>   
                    <?php endif; ?>                
                    <div class="control-group">
                      <div class="controls">
                        <label for="notification"><input name="notification" id="notification"  value="1" type="checkbox" <?php getChecked($row['notification'], 1); ?>> Receive Text Notifications for your Order and Deals via Text</label>
                      </div>
                    </div>
                    <!-- Multiple Checkboxes -->
                    <div class="control-group">
                      <div class="controls">
                        <label for="occasionally"><input name="occasionally" id="occasionally"  value="1" type="checkbox" <?php getChecked($row['occasionally'], 1); ?>> Yes, I occasionally want to be informed on news and specials from <?php echo $core->company; //$product->getCompanyname($locationid);?> </label>
                      </div>
                    </div>
                    <!-- Multiple Checkboxes -->
                    <div class="control-group">
                      <div class="controls">
                        <label for="agree"><input name="agree" id="agree"  value="1" type="checkbox" checked="checked" > I Agree to the  <a href="<?php echo SITEURL; ?>/term-n-condition" target="_blank"> Terms & Conditions</a> </label>
                      </div>
                    </div>
                    <!-- Button -->
                    <div class="control-group">
                      <div class="controls">
                        <input name="reg_submit" value="UPDATE ACCOUNT" type="submit" class="btn-2-2" >                       
                        <input name="processUpdateUserData" type="hidden" value="1" />
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </form>
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
      
      <div class="span12 fit btn_back_next">
        <div class="span10 fit"> 
          <div class="span9"></div>       
          <div>
          	<a href="javascript:goback()" title="Back" class="btn-2-2">BACK</a>
          </div>
        </div>
        <div class="clr"></div>
      </div>      
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
		email_address: {
			required: true,
			email: true,			
			remote:{url:"ajax/test.php", type:"post"}
		},
		zip_code: {			
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
		zip_code: {			
			number: "Please enter a valid zip code.",
			minlength: "Zip code must be of 5 digits",
			maxlength: "Zip code must be of 5 digits"
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
			remote: "Please enter Same  code as provide"
		},
		agree: "Please accept our policy"		
	}	
});
});
// ]]>
</script>
<script type="text/javascript">
// <![CDATA[
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
