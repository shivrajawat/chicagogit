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
  if (isset($_POST['promo_code'])): 

  $promocode = $_POST['promo_code'];

	   $sql = "SELECT  	amount_limit,coupon_id ,IF(`no_of_use_allowed`>`used_no`,'true','false') as allow FROM res_coupon_master WHERE coupon_id = '".$promocode."' && CURDATE()  between start_date and end_date LIMIT 1";
	  $result = $db->query($sql);
	  $row = $db->first($sql);
	  $num = $db->numrows($result);
	  if($num == 1)
	  {
	  		
		 if($_POST['grossamount'] < $row['amount_limit'] && $row['amount_limit']!=0)
		 {
			 echo "false";
		 }
		 else
		{
				if($row['allow']=='true')
		{
		  echo "true";
		  }
		  else
		  {
		  	echo "false";
		  }
		}
	  
	  }
	  else
	  echo "false";
 
  
  endif;
?>