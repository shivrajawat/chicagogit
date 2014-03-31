<?php
  /**
   * User
   *
   * 
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Registration */
  if (isset($_POST['doRegister']))
      : if (intval($_POST['doRegister']) == 0 || empty($_POST['doRegister']))
      : redirect_to("../register.php");
  endif;
  $customers->register();
  endif;
?>
<?php
  /* Registration via mobile */
  if (isset($_POST['doRegisterMobile']))
      : if (intval($_POST['doRegisterMobile']) == 0 || empty($_POST['doRegisterMobile']))
      : redirect_to("../mobile/register.php");
  endif;
  $customers->registerMobile();
  endif;
?>
<?php
  /* Password Reset */
  if (isset($_POST['passReset']))
      : if (intval($_POST['passReset']) == 0 || empty($_POST['passReset']))
      : redirect_to("../login.php");
  endif;
  $user->passReset();
  endif;
?>
<?php
  /* Forgot Password */
  if (isset($_POST['ForgotPassword']))
      : if (intval($_POST['ForgotPassword']) == 0 || empty($_POST['ForgotPassword']))
      : redirect_to("../login.php");
  endif;
  $customers->ForgotPassword();
  endif;
?>
<?php
  /* Account Acctivation */
  if (isset($_POST['accActivate']))
      : if (intval($_POST['accActivate']) == 0 || empty($_POST['accActivate']))
      : redirect_to("../login.php?action=activate");
  endif;
  $user->activateUser();
  endif;
?>
<?php
  /* Check Username */
  if (isset($_POST['checkUsername'])): 
  
  $username = trim(strtolower($_POST['checkUsername']));
  $username = $db->escape($username);
  
  $sql = "SELECT username FROM users WHERE username = '".$username."' LIMIT 1";
  $result = $db->query($sql);
  $num = $db->numrows($result);
  
  echo $num;
  
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteInBasket']))
      : if (intval($_POST['deleteInBasket']) == 0 || empty($_POST['deleteInBasket']))
      : redirect_to("index.php?do=viewcart");
  endif;
  
  $id = intval($_POST['deleteInBasket']);
  $res = $db->delete("res_baskets", "basketID='" . $id . "'");
  $db->delete("res_basket_topping", "basketID='" . $id . "'");
 
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
  if (isset($_POST['FlyToBasket']))
      : if (intval($_POST['FlyToBasket']) == 0 || empty($_POST['FlyToBasket']))
      : redirect_to("../index");
  endif;
  $product->FlyToBasket();
  endif;
?>
<?php
  /* reorder post here*/
  if (isset($_POST['reorderInBasket']))
      : if (intval($_POST['reorderInBasket']) == 0 || empty($_POST['reorderInBasket']))
      : redirect_to("index.php?do=viewcart");
  endif;
  
   $order_number = sanitize($_POST['reorderInBasket']);   
   $order_id =  sanitize($_POST['orderId']);
   
    $sql = "SELECT rom.order_number, rmim.item_name, rmotm.topping_name
		FROM `res_order_master` AS rom
		INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id
		INNER JOIN res_menu_item_master AS rmim ON rod.menu_item_id = rmim.id
		LEFT JOIN `res_order_menutopping_details` AS romd ON rod.order_detail_id = romd.order_detail_id
		LEFT JOIN res_menu_option_topping_master AS rmotm ON rmotm.option_topping_id = romd.order_menu_topping_id
		WHERE rom.order_number = '1382624555'
		AND rmim.active = '1'";	
   
 
 
 
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['CancelOder']))
      : if (intval($_POST['CancelOder']) == 0 || empty($_POST['CancelOder']))
      : redirect_to("index.php?do=viewcart");
  endif;
  
 $sessionID = SESSION_COOK;
	  $res = $db->delete("res_baskets", "basketSession='" . $sessionID . "'");
      $db->delete("res_basket_topping", "basketSession='" . $sessionID . "'");
	  unset($_SESSION['chooseAddress']);
	  unset($_SESSION['orderType']);
	  unset($_SESSION['orderTime']);
	  unset($_SESSION['orderDate']);
	  unset($_SESSION['orderHour']); 
	  unset($_SESSION['repeatOrder']);
  endif;
?>
<?php
  /* Fly to basket */
  if (isset($_POST['FlyToRepeatLastOrder']))
      : if (intval($_POST['FlyToRepeatLastOrder']) == 0 || empty($_POST['FlyToRepeatLastOrder']))
      : redirect_to("../index");
  endif;
  $product->FlyToRepeatLastOrder();
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['ChangeLocation']))
      : if (intval($_POST['ChangeLocation']) == 0 || empty($_POST['ChangeLocation']))
      : redirect_to("index.php?do=viewcart");
  endif;
  
      $db->delete("res_baskets", "basketSession='" . $session_cookie . "'");
      $db->delete("res_basket_topping", "basketSession='" . $session_cookie . "'");
  	
  	  unset($_SESSION['chooseAddress']);
	  unset($_SESSION['orderType']);
	  unset($_SESSION['orderTime']);
	  unset($_SESSION['orderDate']);
	  unset($_SESSION['orderHour']);
	  unset($_SESSION['repeatOrder']); 
	  print "OK";
  endif;
?>