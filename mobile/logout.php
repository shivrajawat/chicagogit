<?php
  /**
   * Logout
   */
  define("_VALID_PHP", true);
  
  require_once("../init.php");
  $wojosec->writeLog(_USER . ' ' . $user->username . ' ' . _LG_LOGOUT, "user", "no", "user");
?>
<?php
$res = $db->delete("res_baskets", "basketSession='" . $session_cookie . "'");
	   $db->delete("res_basket_topping", "basketSession='" . $session_cookie . "'");
 	unset($_SESSION['session_cookie']);
	
  //if ($user->logged_in)
      $customers->logout();
  redirect_to(SITEURL."/mobile/index.php");
?>