<?php
  /**
   * PayPal IPN
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: ipn.php,<?php echo  2010-08-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);
  
  
  if (isset($_POST['payment_status'])) {
      require_once("../../init.php");
	  
      /* only for debuggin purpose. Create logfile.txt and chmot to 0777
       ob_start();
       echo '<pre>';
       print_r($_POST);
       echo '</pre>';
       $logInfo = ob_get_contents();
       ob_end_clean();
       
       $file = fopen('logfile.txt', 'a');
       fwrite($file, $logInfo);
       fclose($file);
       */

      $req = 'cmd=_notify-validate';
      
      foreach ($_POST as $key => $value) {
          $value =  urlencode(stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')));
		  $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);
          $req .= '&' . $key . '=' . $value;
      }
      $url = 'www.paypal.com';
      
	  $header  = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n";
	  $header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
	  $header .= 'Content-Length: ' . strlen(utf8_decode($req)) . "\r\n";
	  $header .= 'Connection: close'  ."\r\n\r\n";
      $fp = fsockopen($url, 80, $errno, $errstr, 30);
      
      $payment_status = $_POST['payment_status'];
      $receiver_email = $_POST['business'];
      $mc_gross = $_POST['mc_gross'];

	  require_once(WOJOLITE . "admin/plugins/donate/admin_class.php");
	  $donate = new Donate();
  
      if (!$fp) {
          echo $errstr . ' (' . $errno . ')';
      } else {
          fputs($fp, $header . $req);
          
          while (!feof($fp)) {
              $res = fgets($fp, 1024);
              if (strcmp($res, "VERIFIED") == 0) {
                  if (preg_match('/Completed/', $payment_status)) {
                      if ($donate->paypal == $receiver_email) {
                          $data = array(
								'name' => sanitize($_POST['first_name'].' '.$_POST['last_name']),
								'email' => isset($_POST['payer_email']) ? sanitize($_POST['payer_email']) : "NULL",
								'amount' => (float)$mc_gross, 
								'created' => "NOW()"

						  );
                          
                          $db->insert("plug_donate", $data);

                      }
                  }
				  
              }
          }
          fclose($fp);
      }
  }
?>