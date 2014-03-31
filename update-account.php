<?php
  /**
   * Index
   *  Register Form 
   *kulacart Coutomer     
   */
  define("_VALID_PHP", true);
  require_once("init.php"); 
  
if (!$customers->customerlogged_in)
      redirect_to("signin");
	if(isset($_POST['processUpdateUserData'])):
		$updateuserdata = $customers->UpdateUserData();
		if($updateuserdata == 1):
			$_SESSION['thanks'] = "<span style=\"color:#FF0000\">Your Profile Update Sucessfully.</span>";
		else:
			$error['submit_error'] = "<span style=\"color:#FF0000\">" . _SYSTEM_PROCCESS . "</span>";
		endif;	
	endif;
  ?>

<?php include(THEMEDIR."/header.php");?>
<?php
	$row = $customers->getUserData();
	
  require_once(THEMEDIR . "/update-account.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>