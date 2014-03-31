<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
  	if (!$customers->customerlogged_in)
      	redirect_to("signin"); 

	$ssl_result = $_GET['ssl_result'];
if ($ssl_result == 0)
{ 
	if($_GET['ssl_result_message']=='APPROVED')
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
			  	$d_address1 = (isset($_SESSION['paymentform']['d_address1'])) ? $_SESSION['paymentform']['d_address1'] : "";   
				$d_address2 = (isset($_SESSION['paymentform']['d_address2'])) ? $_SESSION['paymentform']['d_address2'] : ""; 
				$d_city_id = (isset($_SESSION['paymentform']['d_city_id'])) ? $_SESSION['paymentform']['d_city_id'] : "";  
				$d_state_id = (isset($_SESSION['paymentform']['d_state_id'])) ? $_SESSION['paymentform']['d_state_id'] : ""; 
				$d_country_id = (isset($_SESSION['paymentform']['d_country_id'])) ? $_SESSION['paymentform']['d_country_id'] : ""; 
				$city = (isset($_SESSION['paymentform']['city'])) ? $_SESSION['paymentform']['city'] : ""; 
				$state = (isset($_SESSION['paymentform']['state'])) ? $_SESSION['paymentform']['state'] : ""; 
				$apt = (isset($_SESSION['paymentform']['apt'])) ? $_SESSION['paymentform']['apt'] : ""; 
				$zip_code = (isset($_SESSION['paymentform']['zip_code'])) ? $_SESSION['paymentform']['zip_code'] : ""; 
			  }
			  else
			  {
			  	$order_type = "dl";
				$d_address1 = "";
				$d_address2 = "";				
				$d_city_id = "";
				$d_state_id = "";
				$d_country_id = "";
				$city = "";
				$state = "";
				$apt = "";
				$zip_code = "";
			  }
			  
			  $order_number = ($_SESSION['invoiceno'] && !empty($_SESSION['invoiceno'])) ? $_SESSION['invoiceno'] : time() ;
			  $_SESSION['orderid'] = $order_number;
			  
			  if($_SESSION['paymentform']['patment_type']=='cash')
			  {
			  	$payment_type = "c";
			  }
			  else
			  {
			  	$payment_type = "o";
			  }			  
			  $payment_gateway_type = "Virtual Merchant";
			  $transaction_id = "";
			  $order_comments =(isset($_SESSION['paymentform']['commnet'])) ? $_SESSION['paymentform']['commnet'] : 0;
			  
			  /* Get set variable in sission from process merchant*/
			  $gross_amount =  $_SESSION['paymentAmount']['gross_amount'];
			  $netamount =  $_SESSION['paymentAmount']['totalamount'];
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
			  $web_access_type = (isset($_SESSION['paymentform']['web_access_type'])) ? $_SESSION['paymentform']['web_access_type'] : 0;
			  $browser_name = (isset($_SESSION['paymentform']['browser_name'])) ? $_SESSION['paymentform']['browser_name'] : 0;	
			  
			   // Insert data in order master table  
			   $ordermaster = $product->processOrderMaster($location_id,$customer_id,$order_type,$order_number,$payment_type,$payment_gateway_type,$transaction_id,$order_comments,$gross_amount,$netamount,$additional_fee,$gratuity,$delivery_fee,$sales_tax_rate,$sales_tax,$tip,$coupon_id,$coupon_discount,$pickup_date,$pickup_time,$d_address1,$d_address2,$d_state_id,$d_city_id,$d_country_id,$city,$state,$apt,$zip_code,$web_access_type,$browser_name);	
					  		  
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
						unset($_SESSION['paymentform']);
						unset($_SESSION['paymentform2']);
						unset($_SESSION['paymentAmount']);
						unset($_SESSION['sessioncookie']);				
				
						/*XML pos create here */
						$siteurl = SITEURL;				   
						include('../createxml.php');
						$createxml = createXML($location_id,$ordermaster);
						if($_SESSION['access_type']=='c'){				
							header("Location:$siteurl/thankyou/$ordermaster" . "/" . $createxml);
						} else {
							header("Location:".SITEURL."/mobile/thankyou.php?orderid=".$ordermaster);	
						}			
	}
	else
	{
		echo $_GET['ssl_result_message']." Please try again <a href=\"".SITEURL."/checkout\">click here</a>";
	}
}
else if ($ssl_result==1)
{
	 echo "Transaction Declined <a href=\"".SITEURL."/checkout\">Try Again</a>";
}
?>