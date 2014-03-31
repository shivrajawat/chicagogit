<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin");
	  
	  
	  if(isset($_POST['patment_type']) && !empty($_POST['patment_type'])){
			 $_SESSION['paymentform'] = $_POST;
			 $_SESSION['paymentform2'] = $_POST;
	 }
	 else{
		    $_SESSION['paymentform'] = $_SESSION['paymentform2'];
	 }	
	  
	  
  /* processPaymnet */
  if (isset($_POST['processPaymnet']))
      : if (intval($_POST['processPaymnet']) == 0 || empty($_POST['processPaymnet']))
      : redirect_to("../checkout.php");
   endif;        
		
  	  if(!empty($_POST['patment_type'])):
	  	if($_POST['patment_type']=='is_mercury'):
				
					$allproductrow = $product->AllProductInBasket();
					
					if($allproductrow){
						
						$neat_amount = "";
						$gross_amount = "";					
						foreach($allproductrow as $row){
						
							$gross_amount += $row['total_price'];
						}
					
						 $neat_amount = $gross_amount-$_POST['coupon_discount'];
						 $additionalfeesrow = $product->additionalAmmount($_POST['location']);
						 
						 if($additionalfeesrow)
						 {
							if($additionalfeesrow['sales_tax'])
							{
								$sale_tax_rate = $additionalfeesrow['sales_tax'];
								$sale_tax = round_to_2dp($neat_amount * ($sale_tax_rate/100));
								$neat_amount += $sale_tax; 
								
							}else{	$sale_tax_rate = "0.00";	$sale_tax = "0.00";	}
							
							if($additionalfeesrow['additional_fee'])
							{
								$additional_fee = round_to_2dp($additionalfeesrow['additional_fee']);
								$neat_amount += $additional_fee;
							}
							else {	$additional_fee = "0.00"; }
							
							if($additionalfeesrow['gratuity'])
							{
				
								$gratuity = round_to_2dp($additionalfeesrow['gratuity']);
								$neat_amount += $gratuity; 
								
							}else{	$gratuity = "0.00";	}
							
							if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee'])
							{
								$delivery_fee = round_to_2dp($additionalfeesrow['delivery_fee']);
								$neat_amount += $delivery_fee;
								
							} else {	$delivery_fee = "0.00";	}		 
						 }
					}
		 			$totalamount =$neat_amount+$_POST['tips'];
				
				/*Set variable in session for return vitual merchant payment */					
				 $_SESSION['paymentAmount']	=	array(
													"gross_amount"=> $gross_amount,				
													"neat_amount"=> $neat_amount,
													"sale_tax_rate"=>$sale_tax_rate,
													"sale_tax"=> $sale_tax,				
													"additional_fee"=> $additional_fee,
													"gratuity"=> $gratuity,				
													"delivery_fee"=> $delivery_fee,
													"totalamount"=> $totalamount
												 );
													 
								
		 		$mercurydetails = $product->IsmercuryDetails($_SESSION['paymentform']['location']);				
				$merchantid = $mercurydetails['merchant_id'];
				$password = $mercurydetails['merchant_password'];				
				
				$ProcessCompleteUrl = "".SITEURL."/mobile/returnmercury.php";
				$ReturnUrl  = "".SITEURL."/mobile/checkout.php";
				$LogoUrl = "".THEMEURL."/images/logo.jpg";
				$initPaymentResponse = $mercury->initPaymentExample($merchantid,$password,$totalamount,$ProcessCompleteUrl,$ReturnUrl,$LogoUrl);
				$_SESSION['merchantid']  = $merchantid;
				$_SESSION['merchant_password']  = $password;
				if($initPaymentResponse->InitializePaymentResult->ResponseCode ==0)
				{
					$hostedCheckoutURL = "https://hc.mercurydpay.com/Checkout.aspx";  //Live Payment URL
					//$hostedCheckoutURL = "https://hc.mercurydev.net/Checkout.aspx";   //testing URL
					 
					$paymentID = $initPaymentResponse->InitializePaymentResult->PaymentID;
					$_SESSION['paymentID']  = $paymentID;
					
					//It will redirect the browser to the Mercury HostedCheckout page. 
					echo("<html><head>"); 
					echo("</head><body onload=\"document.frmCheckout.submit()\">"); 
					echo("<form name=\"frmCheckout\" method=\"Post\" action=\"".$hostedCheckoutURL."\" >"); 
					echo("<input name=\"PaymentID\" type=\"hidden\" value=\"".$paymentID."\">"); 
					echo("</form>"); 
					echo("</body></html>");
				}
				else
				{
					echo $initPaymentResponse->InitializePaymentResult->Message;
				}												 
		 endif;
	  endif;		   
  endif;  
?>
<img src="<?php echo SITEURL; ?>/mobile/images/paymentprocess.gif" alt="Loading.........." />