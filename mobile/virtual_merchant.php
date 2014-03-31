<?php
  /**
   * User Account
   *  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
  if (!$customers->customerlogged_in)
      redirect_to("signin");
	 
	  
  /* processPaymnet */
  if (isset($_POST['processPaymnet']))
      : if (intval($_POST['processPaymnet']) == 0 || empty($_POST['processPaymnet']))
      : redirect_to("../checkout.php");
   endif; 
   
   
   			if(isset($_POST['patment_type']) && $_POST['patment_type']=='is_vm'){
					 $_SESSION['paymentform'] = $_POST;
					 $_SESSION['paymentform2'] = $_POST;
			 }
			 else{
					$_SESSION['paymentform'] = $_SESSION['paymentform2'];
			 }	
						 
		 	$loc = $_SESSION['paymentform']['location'];
          
		
  	  if(!empty($_POST['patment_type'])):
	  	if($_POST['patment_type']=='is_vm'):
						
				echo "<img src=\"paymentprocess.gif\" alt=\"Processing, please wait...\"/>";
				//$hostedCheckoutURL = "https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do";    //Test demo URL
				$hostedCheckoutURL = "https://www.myvirtualmerchant.com/VirtualMerchant/process.do";			//Live URL
				$payment = $product->paymentSystemList($_SESSION['paymentform']['location']); 
				$merchant_id = $payment['vm_merchant_id'];
				$user_id = $payment['vm_user_id'];
				$pin = $payment['vm_pin']; 
				
				$allproductrow = $product->AllProductInBasket();
				
				$totalamount = $neat_amount + $_POST['tips'];
		
				/*Set variable in session for return vitual merchant payment */
				 $_SESSION['paymentAmount']	=	array(
											"gross_amount"=> $gross_amount,				
											"neat_amount"=> $neat_amount,
											"sale_tax_rate"=>$sale_tax_rate,
											"sale_tax"=> $sale_tax,				
											"additional_fee"=> $additional_fee,
											"gratuity"=> $gratuity,				
											"delivery_fee"=> $delivery_fee,
											"totalamount"=> $totalamount
										 );				
					
				$totalamount = $totalamount;
				
				$invoiceno = time();
				$_SESSION['invoiceno'] = $invoiceno; 
				
				//It will redirect the browser to the Mercury HostedCheckout page. 
				if(!empty($merchant_id) && !empty($user_id) && !empty($pin))
				{
				echo("<html><head>"); 
				echo("</head><body onload=\"document.frmCheckout.submit()\">"); 
				echo("<form name=\"frmCheckout\" method=\"Post\" action=\"".$hostedCheckoutURL."\" >"); 
				echo("<input type=\"hidden\" name=\"ssl_amount\" id=\"neatamount\" value=\"".$totalamount."\">");
                echo("<input type=\"hidden\" name=\"ssl_merchant_id\" value=\"".$merchant_id."\">");
                echo("<input type=\"hidden\" name=\"ssl_user_id\" value=\"".$user_id."\">");
                echo("<input type=\"hidden\" name=\"ssl_pin\" value=\"". $pin."\">");
				echo("<input type=\"hidden\" name=\"ssl_test_mode\" value=\"false\">");
				echo("<input type=\"hidden\" name=\"ssl_transaction_type\" value=\"ccsale\">");
				echo("<input type=\"hidden\" name=\"ssl_show_form\" value=\"true\">");
				echo("<input type=\"hidden\" name=\"ssl_invoice_number\" value=\"".$invoiceno."\">");
				echo("<input type=\"hidden\" name=\"ssl_email\" value=\"".$_SESSION['email']."\">");
				echo("<input type=\"hidden\" name=\"ssl_result_format\" value=\"HTML\">");
				echo("<input type=\"hidden\" name=\"ssl_receipt_decl_method\" value=\"REDG\">");
				echo("<input type=\"hidden\" name=\"ssl_receipt_decl_get_url\" value=\"".SITEURL."/mobile/return_virtual_merchant.php\">");
				echo("<input type=\"hidden\" name=\"ssl_receipt_apprvl_method\" value=\"REDG\">");
				echo("<input type=\"hidden\" name=\"ssl_receipt_apprvl_get_url\" value=\"".SITEURL."/mobile/return_virtual_merchant.php\">");
				echo("</form>"); 
				echo("</body></html>");
				}
				else
				{
					echo "Have no permission please contact admin";
				}																 
		 endif;
	  endif;		   
  endif;  
?>