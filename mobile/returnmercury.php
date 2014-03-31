<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin"); 

	include(THEMEDIR."/header.php");

 	$site_root = SITEURL;
	header("Refresh: 3;url=$site_root/checkout"); 
	
	$verifyPaymentResponse = $mercury->verifyPaymentExample($_SESSION['merchantid'],$_SESSION['merchant_password'],$_SESSION['paymentID']);
	switch ($verifyPaymentResponse->VerifyPaymentResult->ResponseCode) :
	
    case 0:
      	    $status =  $verifyPaymentResponse->VerifyPaymentResult->Status;
			if($status == 'Approved')
			{
			  $location_id = $_SESSION['paymentform']['location']; 
			  $customer_id = $_SESSION['paymentform']['customer_id'];
			  if($_SESSION['paymentform']['ordertype']=='pick_up')
			  {
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
			  
			  $order_number = time();
			  $_SESSION['orderid'] = $order_number;
			  
			  if($_SESSION['paymentform']['patment_type']=='cash')
			  {
			  	$payment_type = "Mercury";
			  }
			  else
			  {
			  	$payment_type = "o";
			  }			  
			  $payment_gateway_type = "cash";
			  $transaction_id = (!empty($_SESSION['paymentID'])) ? $_SESSION['paymentID'] : 0;
			  $order_comments =(isset($_SESSION['paymentform']['commnet'])) ? $_SESSION['paymentform']['commnet'] : 0;
			  
			  //echo "<pre>"; print_r($_SESSION['paymentAmount']); exit();
			  
			  /* Get set variable in sission from process merchant*/
			  $gross_amount =  $_SESSION['paymentAmount']['gross_amount'];
			  $netamount =  $_SESSION['paymentAmount']['neat_amount'];
			  $sales_tax_rate = $_SESSION['paymentAmount']['sale_tax_rate'];
			  $sales_tax = $_SESSION['paymentAmount']['sale_tax'];
			  $additional_fee = $_SESSION['paymentAmount']['additional_fee'];
			  $gratuity = $_SESSION['paymentAmount']['gratuity'];
			  $delivery_fee = $_SESSION['paymentAmount']['delivery_fee'];
			  $totalamount = $_SESSION['paymentAmount']['totalamount'];			 
			
			  $tip = (isset($_SESSION['paymentform']['tips'])) ? $_SESSION['paymentform']['tips'] : 0;
			  $coupon_id = (isset($_SESSION['paymentform']['coupon_id'])) ? $_SESSION['paymentform']['coupon_id'] : 0;
			  $coupon_discount = (isset($_SESSION['paymentform']['coupon_discount'])) ? $_SESSION['paymentform']['coupon_discount'] : 0;
			  $pickup_date = (isset($_SESSION['paymentform']['pickup_date'])) ? $_SESSION['paymentform']['pickup_date'] : 0;
			  $pickup_time = (isset($_SESSION['paymentform']['pickup_time'])) ? $_SESSION['paymentform']['pickup_time'] : 0;
			  $web_access_type = (isset($_SESSION['paymentAmount']['web_access_type'])) ? $_SESSION['paymentAmount']['web_access_type'] : 0;
			  $browser_name = (isset($_SESSION['paymentAmount']['browser_name'])) ? $_SESSION['paymentAmount']['browser_name'] : 0;
			  			  
			 // Insert data in order master table 
			  $ordermaster = $product->processOrderMaster($location_id,$customer_id,$order_type,$order_number,$payment_type,$payment_gateway_type,$transaction_id,$order_comments,$gross_amount,$netamount,$additional_fee,$gratuity,$delivery_fee,$sales_tax_rate,$sales_tax,$tip,$coupon_id,$coupon_discount,$pickup_date,$pickup_time,$d_address1,$d_address2,$d_state_id,$d_city_id,$web_access_type,$browser_name);			  
			  $data = array(
			  		'posorderxml' => $location_id . "_gt_" . date("mdY") .  "_" . (int)$ordermaster . ".xml"
					);
					
			  $db->update("res_order_master", $data, "orderid=" . (int)$ordermaster);	

    // unset cookies and delete data form res basket table 
	$res = $db->delete("res_baskets", "basketSession='" . $session_cookie . "'");
		   $db->delete("res_basket_topping", "basketSession='" . $session_cookie . "'");
		   
			unset($_SESSION['chooseAddress']);
			unset($_SESSION['orderType']);
			unset($_SESSION['orderTime']);
			unset($_SESSION['orderDate']);
			unset($_SESSION['orderHour']); 
			unset($_SESSION['repeatOrder']);
			unset($_SESSION['sessioncookie']);
			
    		/*XML pos create here */
			$siteurl = SITEURL;				   
			include('../createxml.php');
			$createxml = createXML($location_id,$ordermaster);
			header("Location:".SITEURL."/mobile/thankyou.php?orderid=".$ordermaster);
			}
			else{
					echo "Declined. The transaction was declined. <br/> ";
			}
        break;
    case 100:
        echo  "Acknowledge Authentication failure";
        break;
    case 300:
        echo  "Acknowledge Validation failure. Check MerchantID Password and Payment ID are valid.";
        break;
endswitch;
?>
<?php include(THEMEDIR."/footer.php");?>