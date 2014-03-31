<?php
/**
* Checkout form for authirize 
*
*/
   
define("_VALID_PHP", true);
require_once("../init.php");

  if (!$customers->customerlogged_in)
      redirect_to("signin");
	  
	
	 if(isset($_POST['patment_type']) && $_POST['patment_type'] =='is_authorize' ){
	  
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
	  
	  
			
			 $_SESSION['paymentAmount2'] = $_SESSION['paymentAmount'];
			 
			// echo "<pre>"; print_r($_SESSION['paymentAmount2']); exit();
	 }
	 else{
		    $_SESSION['paymentAmount'] = $_SESSION['paymentAmount2'];
	 }	

	require_once '../payment/anet_php_sdk/AuthorizeNet.php';
	$METHOD_TO_USE = "AIM";





if(isset($_POST['submitauthorized']))
{ 
	$AuthorizeNet = $product->AuthorizeNetDetails($_SESSION['authorize']['location']);	
	$authorizr_api_id = $AuthorizeNet['authorizr_api_id'];
	$authorizze_trans_key = $AuthorizeNet['authorizze_trans_key'];
	$authorize_hash_key = $AuthorizeNet['authorize_hash_key'];
 
	define("AUTHORIZENET_API_LOGIN_ID",$authorizr_api_id);    // Add your API LOGIN ID
	define("AUTHORIZENET_TRANSACTION_KEY",$authorizze_trans_key); // Add your API transaction key
	define("AUTHORIZENET_SANDBOX",false);       // Set to false to test against production
	define("TEST_REQUEST", "TRUE");          // You may want to set to true if testing against production

	// You only need to adjust the two variables below if testing DPM
	define("AUTHORIZENET_MD5_SETTING",$authorize_hash_key);                // Add your MD5 Setting.
	$site_root = SITEURL; // Add the URL to your site

	if (AUTHORIZENET_API_LOGIN_ID == "") {
		die('Enter your merchant credentials in config.php before running the sample app.');
	}
// Post data of caredit card form 
if ($METHOD_TO_USE == "AIM") {
    $transaction = new AuthorizeNetAIM;
    $transaction->setSandbox(AUTHORIZENET_SANDBOX);
    $transaction->setFields(
        array(
        'amount' => $_SESSION['authorize']['netamount'], 
        'card_num' => $_POST['x_card_num'], 
        'exp_date' => $_POST['x_exp_date'],        
        'card_code' => $_POST['x_card_code'],
        )
    );
    $response = $transaction->authorizeAndCapture();
    if ($response->approved) {
        	 // Transaction approved! Do your logic here.
		      $location_id =  $_SESSION['authorize']['location']; 
			  $customer_id =  $_SESSION['authorize']['customer_id'];
			  if( $_SESSION['authorize']['ordertype']=='pick_up')
			  {
			  	$order_type = "p";
				$d_address1 = "";
				$d_address2 = "";
				$d_state_id = "";
				$d_city_id = "";				
			  }
			  else if( $_SESSION['authorize']['ordertype']=='delivery')
			  {
			  	$order_type = "d";
				$d_address1 =  $_SESSION['authorize']['d_address1'];
				$d_address2 =  $_SESSION['authorize']['d_address2'];
				$d_state_id =  $_SESSION['authorize']['d_state_id'];
				$d_city_id =   $_SESSION['authorize']['d_city_id'];
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
			  if( $_SESSION['authorize']['patment_type']=='cash')
			  {
			  	$payment_type = "c";
			  }
			  else
			  {
			  	$payment_type = "o";
			  }
			  
			  $payment_gateway_type = "Authorized";
			  $transaction_id = $response->transaction_id;
			  $order_comments =(isset( $_SESSION['authorize']['commnet'])) ?  $_SESSION['authorize']['commnet'] : 0;
			  
			  
			 /* Get set variable in sission from process merchant*/
			  $gross_amount =  $_SESSION['paymentAmount']['gross_amount'];
			  $netamount =  $_SESSION['paymentAmount']['neat_amount'];
			  $sales_tax_rate = $_SESSION['paymentAmount']['sale_tax_rate'];
			  $sales_tax = $_SESSION['paymentAmount']['sale_tax'];
			  $additional_fee = $_SESSION['paymentAmount']['additional_fee'];
			  $gratuity = $_SESSION['paymentAmount']['gratuity'];
			  $delivery_fee = $_SESSION['paymentAmount']['delivery_fee'];
			  $totalamount = $_SESSION['paymentAmount']['totalamount'];	
			 
			 
			  $tip = (isset( $_SESSION['authorize']['tips'])) ?  $_SESSION['authorize']['tips'] : 0;
			  $coupon_id = (isset( $_SESSION['authorize']['coupon_id'])) ?  $_SESSION['authorize']['coupon_id'] : 0;
			  $coupon_discount = (isset( $_SESSION['authorize']['coupon_discount'])) ?  $_SESSION['authorize']['coupon_discount'] : 0;
			  $pickup_date = (isset( $_SESSION['authorize']['pickup_date'])) ?  $_SESSION['authorize']['pickup_date'] : 0;
			  $pickup_time = (isset( $_SESSION['authorize']['pickup_time'])) ?  $_SESSION['authorize']['pickup_time'] : 0;
			  $web_access_type = (isset($_SESSION['authorize']['web_access_type'])) ? $_SESSION['authorize']['web_access_type'] : 0;
			  $browser_name = (isset($_SESSION['authorize']['browser_name'])) ? $_SESSION['authorize']['browser_name'] : 0;
			  
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
       					// header('Location: thank_you_page.php?transaction_id=' . $response->transaction_id);
    } else {
	
        header('Location: error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text);
    }
} elseif (count($_POST)) {
    $response = new AuthorizeNetSIM;
    if ($response->isAuthorizeNet()) {
        if ($response->approved) {
            // Transaction approved! Do your logic here.
            // Redirect the user back to your site.
            $return_url = $site_root . 'thank_you_page.php?transaction_id=' .$response->transaction_id;
        } else {
            // There was a problem. Do your logic here.
            // Redirect the user back to your site.
            $return_url = $site_root . 'error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text;
        }
        echo AuthorizeNetDPM::getRelayResponseSnippet($return_url);
    } else {
        echo "MD5 Hash failed. Check to make sure your MD5 Setting matches the one in config.php";
    }
}
}

?>
<?php include("header.php");?>
<?php
    $_SESSION['authorize'] = $_POST;
    if ($METHOD_TO_USE == "AIM") {
        ?>
        <form method="post" action="" id="checkout_form">
        <input type="hidden" name="size" value="<?php echo $size?>">
        <?php
    } else {
        ?>
        <form method="post" action="<?php echo (AUTHORIZENET_SANDBOX ? AuthorizeNetDPM::SANDBOX_URL : AuthorizeNetDPM::LIVE_URL)?>" id="checkout_form">
        <?php
        $time = time();
        $fp_sequence = $time;
        $fp = AuthorizeNetDPM::getFingerprint(AUTHORIZENET_API_LOGIN_ID, AUTHORIZENET_TRANSACTION_KEY, $amount, $fp_sequence, $time);
        $sim = new AuthorizeNetSIM_Form(
            array(
            'x_amount'        => $amount,
            'x_fp_sequence'   => $fp_sequence,
            'x_fp_hash'       => $fp,
            'x_fp_timestamp'  => $time,
            'x_relay_response'=> "TRUE",
            'x_relay_url'     => $coffee_store_relay_url,
            'x_login'         => AUTHORIZENET_API_LOGIN_ID,
            'x_test_request'  => TEST_REQUEST,
            )
        );
        echo $sim->getHiddenFieldString();
    }
    ?>
    
     <div data-role="fieldcontain">
          <input type="text" class="text required creditcard" size="15" name="x_card_num" value="" placeholder="Enter Credit Card Number"></input>
        </div>
        
      <div data-role="fieldcontain">
          <input type="text" class="text required" size="4" name="x_exp_date" value="04/15" placeholder="Exp."></input>
        </div>
        
        <div data-role="fieldcontain">
          <input type="text" class="text required" size="4" name="x_card_code" value="782" placeholder="CCV."></input>
        </div>      
      <input type="submit" value="Submit" class="submit buy" name="submitauthorized">
    </form>

<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="jquery.validate.creditcardtypes.js"></script>
    <script>
      $(document).ready(function(){
        $("#checkout_form").validate();
      });
      </script> 
<?php include("footer.php");?>