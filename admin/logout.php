<?php
  /**
   * Logout
   *
   *
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  $wojosec->writeLog(_USER . ' ' . $user->username . ' ' . _LG_LOGOUT, "user", "no", "user");
?>
<?php
  if ($user->logged_in)
      $user->logout();
	  
  redirect_to("login.php");
?>