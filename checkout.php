<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("init.php");	
	  
    $sessionID = SESSION_COOK;
	$checkout =   $customers->chekoutproduct($sessionID);
	
	if(!$checkout)
	{
		redirect_to("index");
	} 
  
?>
<?php include(THEMEDIR."/header.php");

 $webrow = $menu->checkFlow($websitenmae);
	if($webrow['test_mode']=='0'){
		if (!$customers->customerlogged_in ){
			redirect_to("signin");
		 }	
	}
?>
<?php 
  require_once(THEMEDIR . "/checkout.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>