<?php
  /**
   * Forget Password
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: register.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
 if ($customers->customerlogged_in)
      redirect_to("account");
		
  include(THEMEDIR."/header.php");
  
  require_once(THEMEDIR."/forgetpassword.tpl.php");	

  include(THEMEDIR."/footer.php");
?>