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
		redirect_to(SITEURL."/?location");
	} 
	  
	 
	//if($webrow['flow']=='1'){	
	/*if(!$checkout){	
	
		if(isset($_GET['location']) && isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress']))
		{
			redirect_to(THEMEDIR . "/menuby.php");
			//require_once(THEMEDIR . "/menuby.php");
		}
		else
		{
			redirect_to(THEMEDIR . "/locationby.php");
			//require_once(THEMEDIR . "/locationby.php");
		}
		
	}
	else
	{		
		//require_once(THEMEDIR . "/viewcart.tpl.php");
		redirect_to(THEMEDIR . "/view-cart");
	}*/
	
	
?>
<?php include(THEMEDIR."/header.php");?>
<?php 
  require_once(THEMEDIR . "/viewcart.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>