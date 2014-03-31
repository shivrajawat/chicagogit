<?php
  /**
   * Checkout form for authirize 
   *
   */
   
    define("_VALID_PHP", true);
	 require_once("../../init.php");
 ?>
<?php include(THEMEDIR."/header.php");?>

<?php
 $_SESSION['authorize'] = $_POST;
 // print_r($_SESSION['authorize']);
 $AuthorizeNet = $product->AuthorizeNetDetails($_SESSION['authorize']['location']);
 $authorizr_api_id = $AuthorizeNet['authorizr_api_id'];
 $authorizze_trans_key = $AuthorizeNet['authorizze_trans_key'];
 $authorize_hash_key = $AuthorizeNet['authorize_hash_key'];
 $amount =   round_to_2dp($_SESSION['authorize']['netamount']);
/**
 * This file contains config info for the sample app.
 */

// Adjust this to point to the Authorize.Net PHP SDK
require_once 'anet_php_sdk/AuthorizeNet.php';


$METHOD_TO_USE = "AIM";
// $METHOD_TO_USE = "DIRECT_POST";         // Uncomment this line to test DPM


define("AUTHORIZENET_API_LOGIN_ID",$authorizr_api_id);    // Add your API LOGIN ID
define("AUTHORIZENET_TRANSACTION_KEY",$authorizze_trans_key); // Add your API transaction key
define("AUTHORIZENET_SANDBOX",true);       // Set to false to test against production
define("TEST_REQUEST", "FALSE");           // You may want to set to true if testing against production


// You only need to adjust the two variables below if testing DPM
define("AUTHORIZENET_MD5_SETTING",$authorize_hash_key);                // Add your MD5 Setting.
$site_root = SITEURL;; // Add the URL to your site


if (AUTHORIZENET_API_LOGIN_ID == "") {
    die('Enter your merchant credentials in config.php before running the sample app.');
}?>

<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="jquery.validate.creditcardtypes.js"></script>
    <script>
      $(document).ready(function(){
        $("#checkout_form").validate();
      });
      </script> 
    <?php
    
    if ($METHOD_TO_USE == "AIM") {
        ?>
        <form method="post" action="process.php" id="checkout_form">
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
            'x_amount'        => $_SESSION['authorize']['netamount'],
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
      <input type="submit" value="BUY" class="submit buy">
    </form>
  <?php include(THEMEDIR."/footer.php");?>
