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
	$METHOD_TO_USE = "AIM";
 ?>
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
