<?php
  /**
   * User Account
   *  
   */
   
  define("_VALID_PHP", true);
  require_once("../init.php"); 


  if (!$customers->customerlogged_in)
      redirect_to("signin");
	  		
						 
			if(isset($_POST['patment_type']) && $_POST['patment_type']=='is_paypal'){
					 $_SESSION['paymentform'] = $_POST;
					 $_SESSION['paymentform2'] = $_POST;
			 }
			 else{
					$_SESSION['paymentform'] = $_SESSION['paymentform2'];
			 }	
			 
			 
						 
		 	$loc = $_SESSION['paymentform']['location'];
			 
			$paypaldetails = $product->PaypalPaymentDetails($loc);		
			
			/**********************paypal config details, starts here****************************/
			$PayPalMode 			= 'live'; // sandbox or live
		    $PayPalApiUsername 		= cleanSanitize($paypaldetails['paypal_email_id']);  //PayPal API Username			
			$PayPalApiPassword 		= cleanSanitize($paypaldetails['paypal_password']);  //Paypal API password			
		    $PayPalApiSignature 	= cleanSanitize($paypaldetails["paypal_signature"]);  //Paypal API Signature 			
			$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
			$PayPalReturnURL 		= SITEURL.'/mobile/paypal_process.php'; //Point to process.php page
			$PayPalCancelURL 		= SITEURL.'/mobile/checkout.php'; //Cancel URL if user clicks cancelexit();
			
			/**********************paypal config details, ends here****************************/	
	        include_once("paypal.class.php"); 
			
			
			
			 
?>
<?php
  //include_once("paypal-express-checkout/config.php");
  /* processPaymnet */
  if (isset($_POST['processPaymnet']) && $_POST['patment_type']=='is_paypal' ){		

       //Post Data received from product list page.
	   //Mainly we need 4 variables from an item, Item Name, Item Price, Item Number and Item Quantity.	 	
		$session_cookie;
		$paypal_data = '';
		$ItemTotalPrice = '';
		$too='';
	
		
		/*$ServiceCharge = $_POST['sales_tax'] + $_POST['additional_fee]']+$_POST['gratuity']+$_POST['tips']+$_POST['tips']+$_POST['sales_tax]']-$_POST['coupon_discount]'];*/
	
	
	
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
			
		  $totalamount = $neat_amount + $_POST['tips'];
		
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
		
		
		
	$ServiceCharge = $sale_tax + $additional_fee + $gratuity + $delivery_fee + $_POST['tips'] - $_POST['coupon_discount'];
	
	//loop through POST array
	if($allproductrow!=0){
		  
		  foreach($allproductrow as $key=>$itmname){ 
		  		
			$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($itmname['quantity']);
			$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($itmname['total_price']);
			$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($itmname['name']);
			
			/*	
			$ItemName[$key]=$_POST['item_name'][$key];
			$ItemQTY[$key]=$_POST['item_qty'][$key];
			$ItemPrice[$key]=$_POST['item_price'][$key];
			*/
			
			// item price X quantity
			$subtotal = ($itmname['total_price']*$itmname['quantity']);
			
			//total price
			$too = ($too + $subtotal);
		}
	}
	
	
	
	
	
	$ItemTotalPrice = $ServiceCharge + $too;		
	
	//Data to be sent to paypal
	$padata = 	'&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTACTION=Sale'.
				'&ALLOWNOTE=1'.
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ServiceCharge).
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($too). 
				$paypal_data.
				'&AMT='.urlencode($ItemTotalPrice).				
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL);	

		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword,$PayPalApiSignature, $PayPalMode);
	  
		//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{					
				// If successful set some session variable we need later when user is redirected back to page from paypal. 
				//$_SESSION['itemprice'] =  $ItemPrice;
				$_SESSION['totalamount'] = $ItemTotalPrice;
				$_SESSION['ServiceCharge'] = $ServiceCharge;
				
				
				/*
				$_SESSION['itemName'] =  $ItemName;
				$_SESSION['itemNo'] =  $ItemNumber;
				$_SESSION['itemQTY'] =  $ItemQTY;
				*/
				if($PayPalMode=='sandbox'){				
					$paypalmode ='.sandbox';
				}
				else {				
					$paypalmode ='';
				}
				//Redirect user to PayPal store with Token received.
			 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				header('Location: '.$paypalurl);
			 
		}else{
			//Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		}

 }

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	
	
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	
	//get session variables
	//$ItemPrice 		= $_SESSION['itemprice'];
	 $ItemTotalPrice = $_SESSION['totalamount']; 
	/*$ItemName 		= $_SESSION['itemName'];
	$ItemNumber 	= $_SESSION['itemNo'];
	$ItemQTY 		=$_SESSION['itemQTY'];
	echo count($ItemPrice)."<br/>";
	foreach($ItemPrice as $key=>$val)
	echo "<br/>  ip".$ItemPrice[$key]."   itp ".$ItemTotalPrice."  in ".$ItemName[$key]."  inu ".$ItemNumber."  iq ".$ItemQTY[$key];
	exit();*/
	$padata = 	'&TOKEN='.urlencode($token).
						'&PAYERID='.urlencode($playerid).
						'&PAYMENTACTION='.urlencode("SALE").
						'&AMT='.urlencode($ItemTotalPrice).
						'&CURRENCYCODE='.urlencode($PayPalCurrencyCode); 
						
						
			
	
	//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.	
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment',$padata,$PayPalApiUsername,$PayPalApiPassword,$PayPalApiSignature,$PayPalMode);
 echo "<pre>"; print_r($httpParsedResponseAr); 
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
				

				$transactionID = urlencode($httpParsedResponseAr["TRANSACTIONID"]);
				$paymentstatus = urlencode($httpParsedResponseAr["PAYMENTSTATUS"]);
				$nvpStr = "&TRANSACTIONID=".$transactionID;
				$paypal= new MyPayPal();
				$httpParsedResponseAr = $paypal->PPHttpPost('GetTransactionDetails',$nvpStr,$PayPalApiUsername,$PayPalApiPassword,$PayPalApiSignature, $PayPalMode);

				if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {				  
					 //print_r($_SESSION); echo "<pre>"; print_r($_SESSION['paymentform']);
					   
					  if($_SESSION['paymentform']['ordertype']=='pick_up'){					  
						$order_type = "p";
						$d_address1 = "";
						$d_address2 = "";
						$d_state_id = "";
						$d_city_id = "";						
					  }
					  else if($_SESSION['paymentform']['ordertype']=='delivery')
					  {
						$order_type = "d";
						$d_address1 = $_SESSION['paymentform']['d_address1'];
						$d_address2 = $_SESSION['paymentform']['d_address2'];
						$d_state_id = $_SESSION['paymentform']['d_state_id'];
						$d_city_id = $_SESSION['paymentform']['d_city_id'];
					  }
					  else
					  {
						$order_type = "dl";
						$d_address1 = "";
						$d_address2 = "";
						$d_state_id = "";
						$d_city_id = "";
					  }	
					  if($_SESSION['paymentform']['patment_type']=='cash'){	$payment_type = "c";  } else { $payment_type = "o";  }	
					  
					 
					  
					  $order_number = time();
					  $_SESSION['orderid'] = $order_number;
					  $location_id = $_SESSION['paymentform']['location']; 
					  $customer_id = $_SESSION['paymentform']['customer_id'];	  
					  $payment_gateway_type = "Paypal";
					  $transaction_id = (!empty($transactionID)) ? $transactionID : 0;					  					  
					  $order_comments =(isset($_SESSION['paymentform']['commnet'])) ? $_SESSION['paymentform']['commnet'] : 0;
					  
					  
					  $gross_amount =  $_SESSION['paymentAmount']['gross_amount'];
					  $netamount =  $_SESSION['paymentAmount']['totalamount'];
					  $sales_tax_rate = $_SESSION['paymentAmount']['sale_tax_rate'];
					  $sales_tax = $_SESSION['paymentAmount']['sale_tax'];
					  $additional_fee = $_SESSION['paymentAmount']['additional_fee'];
					  $gratuity = $_SESSION['paymentAmount']['gratuity'];
					  $delivery_fee = $_SESSION['paymentAmount']['delivery_fee'];
					  
					 	
					 
					  $tip = (isset($_SESSION['paymentform']['tips'])) ? $_SESSION['paymentform']['tips'] : 0;
					  $coupon_id = (isset($_SESSION['paymentform']['coupon_id'])) ? $_SESSION['paymentform']['coupon_id'] : 0;
					  $coupon_discount = (isset($_SESSION['paymentform']['coupon_discount'])) ? $_SESSION['paymentform']['coupon_discount'] : 0;
					  $pickup_date = (isset($_SESSION['paymentform']['pickup_date'])) ? $_SESSION['paymentform']['pickup_date'] : 0;
					  $pickup_time = (isset($_SESSION['paymentform']['pickup_time'])) ? $_SESSION['paymentform']['pickup_time'] : 0;
					  $web_access_type = (isset($_SESSION['paymentform']['web_access_type'])) ? $_SESSION['paymentform']['web_access_type'] : 0;
			 		  $browser_name = (isset($_SESSION['paymentform']['browser_name'])) ? $_SESSION['paymentform']['browser_name'] : 0;	
					  
					  	  
					  // Insert data in order master table  
					  $ordermaster = $product->processOrderMaster($location_id,$customer_id,$order_type,$order_number,$payment_type,$payment_gateway_type,$transaction_id,$order_comments,$gross_amount,$netamount,$additional_fee,$gratuity,$delivery_fee,$sales_tax_rate,$sales_tax,$tip,$coupon_id,$coupon_discount,$pickup_date,$pickup_time,$d_address1,$d_address2,$d_state_id,$d_city_id,$web_access_type,$browser_name);
					  	
	  
					   $data = array(
										'posorderxml' => $location_id . "_gt_" . date("mdY") .  "_" . (int)$ordermaster . ".xml"
									);
							
					  	$db->update("res_order_master", $data, "orderid=" . (int)$ordermaster);	
		
						// unset cookies and delete data form res basket table 
						$res = $db->delete("res_baskets", "basketSession='" . $session_cookie . "'");
							   $db->delete("res_basket_topping", "basketSession='" . $session_cookie . "'");
							   
						unset($_SESSION['paymentform']);
						unset($_SESSION['paymentform2']);
						unset($_SESSION['paymentAmount']);
						unset($_SESSION['sessioncookie']);
					   
						/*XML pos create here */
						$siteurl = SITEURL;				   
						include('../createxml.php');
						$createxml = createXML($location_id,$ordermaster);						
						header("Location:".SITEURL."/mobile/thankyou.php?orderid=".$ordermaster);	
					} 
				else  {
					echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					echo '<pre>';
					print_r($httpParsedResponseAr);
					echo '</pre>';

				}
	
	}
	else{ 
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
	}
}



?>
