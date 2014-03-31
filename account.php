<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin");
?>
<?php include(THEMEDIR."/header.php");?>
<?php 
  //$row = $customers->getUserData();
  require_once(THEMEDIR . "/account.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>