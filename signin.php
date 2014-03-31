<?php
  /**
   * Login
   *
   */
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php include(THEMEDIR."/header.php");?>
<?php
		
 if ($customers->customerlogged_in)
      redirect_to("account");
	  	  
	  $sessionID = SESSION_COOK;
	  $checkout =   $customers->chekoutproduct($sessionID);
	  $webrow = $menu->checkFlow($websitenmae);	 
	 
	  
  if (isset($_POST['doLogin']))
      : $result = $customers->login($_POST['username'], $_POST['password']);
	  
  		  /*Login Successful */
  		  if ($result):	
		  
		  	   if(isset($_GET['repeatOrder']) && $_GET['repeatOrder']==1){
		   
		   		    $_SESSION['repeatThanksOrder'] =1; 
					$_SESSION['repeatOrder']; 
					
					$repeatOrderURL = SITEURL.'/repeat-lastorder.php';				  
					redirect_to($repeatOrderURL); 
					
			   } else{
			 
			  if($checkout){   // if item have added in basket then 
				  			
				if($webrow['flow']=='1'){	// website Flow Location By 
					 if($webrow['flow']=='1' && isset($_SESSION['chooseAddress']) ){ 				
						redirect_to("checkout");
					 }					
				}
				else if($webrow['flow']=='2'){  // website Flow Menu By 
						redirect_to("chooselocation");
				}
				else {  // website Flow Ecommerce  By 
						redirect_to("checkout");
				}
			  }	
			  else{  	  	 	    
				 	redirect_to("account");
			  }	
			  
			}
 		 endif;
  endif; 
  require_once(THEMEDIR."/signin.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>