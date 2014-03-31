<?php
  /**
   * Index
   *  Register Form 
   *kulacart Coutomer     
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
   if ($customers->customerlogged_in)
      redirect_to("account");
  
  if(isset($_POST['reg_submit'])): 
	$_SESSION['register_form'] = $_POST;	
	if(empty($_POST['first_name']) || !trim($_POST['first_name'])):
		$error['first_name'] = "<p class=\"input_error\">Please provide your name.</p>";
	endif;
	if(empty($_POST['last_name']) || !trim($_POST['last_name'])):
		$error['last_name'] = "<p class=\"input_error\">Please provide your name.</p>";
	endif;
	if(empty($_POST['email_address'])):
		$error['email'] = "<p class=\"input_error\">Please provide your email address.</p>";
	endif;
	if($customers->emailAlreadyexit($_POST['email_address'])):
		$error['email'] = "<p class=\"input_error\">This email address already exit.</p>";
	endif;
	if (!$customers->isEmailValid($_POST['email_address'])):
			 $error['email'] ="<p class=\"input_error\">Entered email address is not valid.</p>";
	endif;
	
		
	if(empty($_POST['password'])):
		$error['password'] = "<p class=\"input_error\">Please provide your password.</p>";
	endif;
	
	if(empty($_POST['confirm_password'])):
		$error['confirm_password'] = "<p class=\"input_error\">Please provide your confirm_password.</p>";
	endif;
	
	if(!empty($_POST['password'])):	
	if (strlen($_POST['password']) < 6)
			  $error['password'] = "<p class=\"input_error\">Password is too short (less than 6 characters long).</p>"; 
		  elseif (!preg_match("/^([0-9a-z])+$/i", ($_POST['password'] = trim($_POST['password']))))
			  $error['password'] = "<p class=\"input_error\">Password entered is not alphanumeric.!.</p>";
		  elseif ($_POST['password'] != $_POST['confirm_password'])
			  $error['confirm_password'] = "<p class=\"input_error\">Your password did not match the confirmed password!</p>";
	
	endif;
	
	if(empty($_POST['zip_code'])):
		$error['zip_code'] = "<p class=\"input_error\">Please provide your zip code.</p>";
		
	endif;
	
	if(empty($_POST['state'])):
		$error['state'] = "<p class=\"input_error\">Please select your state.</p>";
		
	endif;
	
	if(empty($_POST['phone_no'])):
		$error['phone_no'] = "<p class=\"input_error\">Please provide your mobile no.</p>";
	endif;
	
	
	if(!empty($_POST['phone_no'])):
	if (!is_numeric($_POST['phone_no']))
			  $error['phone_no'] ="<p class=\"input_error\">Mobile number should be numeric. </p>";	
	   elseif (strlen($_POST['phone_no'])<9 || strlen($_POST['phone_no']) > 13)
			  $error['phone_no'] = "<p class=\"input_error\">Please provide your proper mobile no.</p>"; 
	
	endif;
	
	 if(empty($error)):
	 	$reg_submit = $customers->register();
		
		if($reg_submit == 1):
		
			unset($_SESSION['user_form']);
			$_SESSION['thanks'] = "<h1>Thank you for registering. Please proceed to place your order.</h1>";
			redirect_to("info");
		else:
			$error['submit_error'] = "<p class=\"input_error\">" . _SYSTEM_PROCCESS . "</p>";
		endif;	 
	 endif;
else:
	$error['first_name'] = "";
    $error['last_name'] = "";
	$error['email'] = "";
	$error['password'] = "";
	$error['confirm_password']="";
	$error['zip_code']="";
	$error['phone_no']="";
	$error['state']="";
	$error['city']="";

endif;
?>
<?php include(THEMEDIR."/header.php");?>
<?php
  require_once(THEMEDIR . "/register.tpl.php"); 
?>
<?php include(THEMEDIR."/footer.php");?>