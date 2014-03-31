<?php
  /**
   * User
   *
   */
  define("_VALID_PHP", true);
  require_once("../../init.php");
?>
<?php
  /* Registration */
  if (isset($_POST['doLogin']))
      : if (intval($_POST['doLogin']) == 0 || empty($_POST['doLogin']))
      : redirect_to("../login.php");
  endif;
   $result = $user->login($_POST['username'], $_POST['password']);
   if ($result)
      : redirect_to("account.php");
  endif;
  endif;
?>
<?php
  /* processPromoCode */
  if (isset($_POST['processPromoCode']))
      : if (intval($_POST['processPromoCode']) == 0 || empty($_POST['processPromoCode']))
      : redirect_to("../checkout.php");
   endif;
  	  $sql = "SELECT * FROM "
		   ."\n `res_coupon_master`"
		   ."\n WHERE `coupon_id` = '".$_POST['promo_code']."' && CURDATE()  between start_date and end_date";	
		 $row = $db->first($sql);
		 if($row['type_of_discount']=='percent')
		 {
		 	$amount = $_POST['netamount']*$row['discount']/100;			
		 }
		 else
		 {
		   	$amount = $row['discount'];
		 } 
		 echo round_to_2dp($amount);
		   
  endif;
?>
<?php
  /* Fly to basket */
  if (isset($_POST['FlyToBasketMobile']))
      : if (intval($_POST['FlyToBasketMobile']) == 0 || empty($_POST['FlyToBasketMobile']))
      : redirect_to("index.php");
  endif;  
  
  $product->FlyToBasket();  
  
  endif;
?>