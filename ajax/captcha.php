<?php
  /**
   * captcha
   *   
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Check captcha */
  if (isset($_POST['captcha'])): 

 	 if (empty($_POST['captcha']))
	 {
	 	 echo "false";
	 }
	 else if ($_SESSION['captchacode'] != $_POST['captcha'])
	 {
	 	echo "false";
	 }
	 else
	 {
	 	echo "true";
	 }
  
  endif;
?>