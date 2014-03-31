<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin");
	  
 if (isset($_POST['processPaymnet']))
      : if (intval($_POST['processPaymnet']) == 0 || empty($_POST['processPaymnet']))
      : redirect_to("../checkout.php");
   endif;
   
   	$allproductrow = $product->AllProductInBasket();
	if($allproductrow){
	$neat_amount = "";
	$gross_amount = "";
		foreach($allproductrow as $row)
		{
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
			}else{$sale_tax_rate = "0.00";$sale_tax = "0.00";}
			
		 	if($additionalfeesrow['additional_fee'])
			{
				$additional_fee = round_to_2dp($additionalfeesrow['additional_fee']);
				$neat_amount += $additional_fee;
			}
			else
			{
				$additional_fee = "0.00";
			}
			if($additionalfeesrow['gratuity'])
			{

				$gratuity = round_to_2dp($additionalfeesrow['gratuity']);
				$neat_amount += $gratuity; 
			}else{$gratuity = "0.00";}
			if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee'])
			{
				$delivery_fee = round_to_2dp($additionalfeesrow['delivery_fee']);
				$neat_amount += $delivery_fee;
			}else{$delivery_fee = "0.00";}		 
		 }
	}
	 $ordertotalamount = $neat_amount+$_POST['tips'];

              $location_id = $_POST['location']; 
			  $customer_id = $_POST['customer_id'];
			  if($_SESSION['orderType']=='pick_up')
			  {
			  	$order_type = "p";
				$d_address1 = "";
				$d_address2 = "";
				$d_state_id = "";
				$d_city_id = "";
				
			  }
			  else if($_SESSION['orderType']=='delivery')
			  {
			  	$order_type = "d";
				$d_address1 = $_POST['d_address1'];
				$d_address2 = $_POST['d_address2'];				
				$d_city_id =  $_POST['d_city_id'];
				$d_state_id = $_POST['d_state_id'];
				$d_country_id = $_POST['d_country_id'];
				$city = $_POST['city'];
				$state = $_POST['state'];
				$apt = $_POST['apt'];
				$zip_code = $_POST['zip_code'];			
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
			  
			  $order_number = time();
			  $_SESSION['orderid'] = $order_number;
			  
			  if($_POST['patment_type']=='cash')
			  {
			  	$payment_type = "c";
			  }
			  else
			  {
			  	$payment_type = "o";
			  }			  
			  $payment_gateway_type = "cash";
			  $transaction_id = "";
			  $order_comments =(isset($_POST['commnet'])) ? $_POST['commnet'] : 0;
			  $gross_amount =  $gross_amount;
			  $netamount =  $ordertotalamount;
			  $additional_fee = $additional_fee;
			  $gratuity = $gratuity;
			  $delivery_fee = $delivery_fee;
			  $sales_tax_rate = $sale_tax_rate;
			  $sales_tax = $sale_tax;
			  $tip = (isset($_POST['tips'])) ? $_POST['tips'] : 0;
			  $coupon_id = (isset($_POST['coupon_id'])) ? $_POST['coupon_id'] : 0;
			  $coupon_discount = (isset($_POST['coupon_discount'])) ? $_POST['coupon_discount'] : 0;
			  $pickup_date = (isset($_POST['pickup_date'])) ? $_POST['pickup_date'] : 0;
			  $pickup_time = (isset($_POST['pickup_time'])) ? $_POST['pickup_time'] : 0;
			  $web_access_type = (isset($_POST['web_access_type'])) ? $_POST['web_access_type'] : 0;
			  $browser_name = (isset($_POST['browser_name'])) ? $_POST['browser_name'] : 0;
			
			  
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
			unset($_SESSION['sessioncookie']);
			
    		/*XML pos create here */
			$siteurl = SITEURL;				   
			include('../createxml.php');
			$createxml = createXML($location_id,$ordermaster);
			
			header("Location:".SITEURL."/mobile/thankyou.php?orderid=".$ordermaster);	   
endif; 
?>
