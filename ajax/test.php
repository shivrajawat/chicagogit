<?php
  /**
   * User
   *
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Check Username */
  if (isset($_POST['email_address'])): 

  $username = trim(strtolower($_POST['email_address']));
  $username = $db->escape($username);
  if (filter_var($username, FILTER_VALIDATE_EMAIL))
  {
	  $sql = "SELECT email_id FROM res_customer_master WHERE email_id = '".$username."' LIMIT 1";
	  $result = $db->query($sql);
	  $num = $db->numrows($result);
	  if($num == 1)
	  {
		  echo "false";
	  }
	  else
	  echo "true";
	  //echo $num;
  }
	else  echo "false";
  
  endif;
?>