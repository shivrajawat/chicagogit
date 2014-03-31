<?php
  /**
   * Checkout form for authirize 
   *
   */
   
    define("_VALID_PHP", true);
	 require_once("../../init.php");
 ?>
<?php
/**
 * This file contains config info for the sample app.
 */

// Adjust this to point to the Authorize.Net PHP SDK
require_once 'anet_php_sdk/AuthorizeNet.php';
$METHOD_TO_USE = "AIM";

define("AUTHORIZENET_API_LOGIN_ID","658yU6Tde");    // Add your API LOGIN ID
define("AUTHORIZENET_TRANSACTION_KEY","66Hza7P7m9M3PsY4"); // Add your API transaction key
define("AUTHORIZENET_SANDBOX",true);       // Set to false to test against production
define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production


// You only need to adjust the two variables below if testing DPM
define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.
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
			  
			  $payment_gateway_type = "cash";
			  $transaction_id = "";
			  $order_comments =(isset( $_SESSION['authorize']['commnet'])) ?  $_SESSION['authorize']['commnet'] : 0;
			  $gross_amount =   $_SESSION['authorize']['gross_amount'];
			  $netamount =   $_SESSION['authorize']['netamount'];
			  $additional_fee =  $_SESSION['authorize']['additional_fee'];
			  $gratuity =  $_SESSION['authorize']['gratuity'];
			  $delivery_fee =  $_SESSION['authorize']['delivery_fee'];
			  $sales_tax_rate =  $_SESSION['authorize']['sales_tax_rate'];
			  $sales_tax =  $_SESSION['authorize']['sales_tax'];
			  $tip = (isset( $_SESSION['authorize']['tips'])) ?  $_SESSION['authorize']['tips'] : 0;
			  $coupon_id = (isset( $_SESSION['authorize']['coupon_id'])) ?  $_SESSION['authorize']['coupon_id'] : 0;
			  $coupon_discount = (isset( $_SESSION['authorize']['coupon_discount'])) ?  $_SESSION['authorize']['coupon_discount'] : 0;
			  $pickup_date = (isset( $_SESSION['authorize']['pickup_date'])) ?  $_SESSION['authorize']['pickup_date'] : 0;
			  $pickup_time = (isset( $_SESSION['authorize']['pickup_time'])) ?  $_SESSION['authorize']['pickup_time'] : 0;
			  
			  // Insert data in order master table  ..............................................................
			  $ordermaster = $product->processOrderMaster($location_id,$customer_id,$order_type,$order_number,$payment_type,$payment_gateway_type,$transaction_id,$order_comments,$gross_amount,$netamount,$additional_fee,$gratuity,$delivery_fee,$sales_tax_rate,$sales_tax,$tip,$coupon_id,$coupon_discount,$pickup_date,$pickup_time,$d_address1,$d_address2,$d_state_id,$d_city_id);
			  
			  $data = array(
			  		'posorderxml' => $location_id . "_gt_" . date("mdY") .  "_" . (int)$ordermaster . ".xml"
					);
					
			  $db->update("res_order_master", $data, "orderid=" . (int)$ordermaster);
			
			 // Insert data in order Details table  ..............................................................
			  $session_cookie;
		      $basket_details = $product->getBasketDetails($session_cookie);
			 if($basket_details){
	  			if(!empty($ordermaster))
			  		{			 
						foreach($basket_details as $rrow){				 
							$order_detail_id = $product->processOrderDetails($ordermaster,$rrow['productID'],$rrow['menu_size_map_id'],$rrow['productPrice'],$rrow['qty'],$rrow['additional_notes']);
							// Insert data in order menu topping table ..........................................................
							$OrderMenutopping  = $product->viewCartTopping($rrow['basketID']);
							
							 
							if($OrderMenutopping){								
										foreach($OrderMenutopping as $trow){
															 
											$product->processOrderMenuTopping($order_detail_id,$trow['option_id'],$trow['option_topping_id'],$trow['option_choice_id'],$trow['qty'],$order_type,$trow['option_topping_name'],$trow['price']);	
										}
							  }	
		 				}					
			  		}	
			  }		
			  
			
			 /*Send Email here */
			  
			


    // unset cookies and delete data form res basket table ........................................................... 
	$res = $db->delete("res_baskets", "basketSession='" . $session_cookie . "'");
		   $db->delete("res_basket_topping", "basketSession='" . $session_cookie . "'");
	unset($_SESSION['sessioncookie']);
    $siteurl = SITEURL;	
			   
	/*XML pos create here */			   
	include('../../createxml.php');
	$createxml = createXML($location_id, $ordermaster);
	
	print_r($createxm); exit();
	header("Location:$siteurl/thankyou/$ordermaster" . "/" . $createxml);	   
		
		
		
		
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
?>
<?php
    
    if ($METHOD_TO_USE == "AIM") {
        ?>
        <form method="post" action="process_sale.php" id="checkout_form">
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
      <fieldset>
        <div>
          <label>Credit Card Number</label>
          <input type="text" class="text required creditcard" size="15" name="x_card_num" value="6011000000000012"></input>
        </div>
        <div>
          <label>Exp.</label>
          <input type="text" class="text required" size="4" name="x_exp_date" value="04/15"></input>
        </div>
        <div>
          <label>CCV</label>
          <input type="text" class="text required" size="4" name="x_card_code" value="782"></input>
        </div>
      </fieldset>
      <fieldset>
        <div>
          <label>First Name</label>
          <input type="text" class="text required" size="15" name="x_first_name" value="John"></input>
        </div>
        <div>
          <label>Last Name</label>
          <input type="text" class="text required" size="14" name="x_last_name" value="Doe"></input>
        </div>
      </fieldset>
      <fieldset>
        <div>
          <label>Address</label>
          <input type="text" class="text required" size="26" name="x_address" value="123 Four Street"></input>
        </div>
        <div>
          <label>City</label>
          <input type="text" class="text required" size="15" name="x_city" value="San Francisco"></input>
        </div>
      </fieldset>
      <fieldset>
        <div>
          <label>State</label>
          <input type="text" class="text required" size="4" name="x_state" value="CA"></input>
        </div>
        <div>
          <label>Zip Code</label>
          <input type="text" class="text required" size="9" name="x_zip" value="94133"></input>
        </div>
        <div>
          <label>Country</label>
          <input type="text" class="text required" size="22" name="x_country" value="US"></input>
        </div>
      </fieldset>
      <input type="submit" value="BUY" class="submit buy">
    </form>


