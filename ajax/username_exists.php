<?php
  /**
   * User
   *
   * @package  
   * @author  
   * @copyright 2010
   * @version $Id: user.php, 
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Check Username */
  if (isset($_POST['username'])): 

  $username = trim(strtolower($_POST['username']));
  $username = $db->escape($username);
  if (filter_var($username, FILTER_VALIDATE_EMAIL))
  {
	  $sql = "SELECT email_id FROM res_customer_master WHERE email_id = '".$username."' LIMIT 1";
	  $result = $db->query($sql);
	  $num = $db->numrows($result);
	  if($num == 1)
	  {
		  echo "true";
	  }
	  else
	  echo "false";
	  
  }
	else  echo "true";
  
  endif;
?>