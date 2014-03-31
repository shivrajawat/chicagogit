<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin");
	  
    $sessionID = SESSION_COOK;
	$checkout =   $customers->chekoutproduct($sessionID);
	
	if(!$checkout)
	{
		redirect_to("index");
	}  
  /* processPaymnet */
  if (isset($_POST['processPaymnet']))
      : if (intval($_POST['processPaymnet']) == 0 || empty($_POST['processPaymnet']))
      : redirect_to("../getorder-type");
   endif;        
		  
  	  if(!empty($_POST['patment_type'])):
	  	 if($_POST['patment_type']=='cash'):
			
		 elseif($_POST['patment_type']=='is_mercury'):	
		 	$merchantid = "494691720";
			$password = "KRD%8rw#+p9C13,T";
			$invoice = "12365214";
			$totalamount = "50";	 
		 	header("location:payment/mercurypay/SampleIntegration.php");
			
		 endif;
	  endif;		   
  endif;  
?>
<?php include(THEMEDIR."/header.php");?>
<?php 
  require_once(THEMEDIR . "/getorder-type.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>