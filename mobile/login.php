<?php include("header.php");?>
<?php 
	if ($customers->customerlogged_in)
          redirect_to("account.php");
	  
    $sessionID = SESSION_COOK;
	$checkout =   $customers->chekoutproduct($sessionID); 
	
	
 if (isset($_POST['doLogin'])):
  	$result = $customers->login($_POST['username'], $_POST['password']);
  
	  /* Login Successful */
	   if ($result):
	   
				if(isset($checkout) && $checkout ==1):
				
					echo '<script type="text/javascript">
							window.location.href = "'.SITEURL.'/mobile/checkout.php";
					     </script>';
				
				elseif(isset($_GET['repeatOrder']) && $_GET['repeatOrder']==1):				
				
						$_SESSION['repeatThanksOrder'] =1; 
						$_SESSION['repeatOrder']; 
					
						$repeatOrderURL = SITEURL.'/mobile/repeat-lastorder.php';				  
						redirect_to($repeatOrderURL); 				
				
					/*echo '<script type="text/javascript">
							window.location.href = "'.SITEURL.'/mobile/index.php";
					 	  </script>';*/
			  
			   elseif(isset($_GET['repeatOrder']) && $_GET['repeatOrder']==2):
			  
			   		echo '<script type="text/javascript">
							window.location.href = "'.SITEURL.'mobile/index.php";
					      </script>'; 
			   else:
				
				   echo "<script type=\"text/javascript\">
							window.location.href = 'account.php'
						 </script>";
				endif;
			
		else:
			$_SESSION['thanks'] = "<span style=\"color:#FF0000\">Username and Password did not match.</span>";
		
  		endif;		
		
  endif;
  ?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
    <div data-role="content">
          <h1 class="main-heading">Login to your existing account </h1>
     <div id="msgholder">
     <?php  
            if(!empty($_SESSION['thanks'])) 
			    echo $_SESSION['thanks'];
                unset($_SESSION['thanks']);
                
             if(!empty($error['submit_error']) && isset($error['submit_error'])) 
			 	echo $error['submit_error'];
     ?>
     </div>
      <form id="frmLogin" action="" method="post" data-ajax="false">
        <div data-role="fieldcontain">
          <input type="text" id="email" name="username" class="required username" placeholder="Username" title="Username"/>
        </div>
        <div data-role="fieldcontain">
          <input type="password" id="password" name="password" class="required" placeholder="Password" title="Password" />
        </div>
        <div class="ui-body-b">
          <input type="submit" name="submit" value="Login" class="btnLogin">
        </div>        
        <input name="doLogin" type="hidden" value="1" />
      </form>
      <a href="forgotpassword.php" data-role="button" data-ajax="false">Forgot Your password</a>
      <a href="register.php" data-role="button" data-ajax="false">Not Yet Registered?</a>
    </div>
    <a href="index.php" data-role="button" data-ajax="false">Back To Home</a>
    <!-- /content -->
  </div>
<?php include("footer.php");?>
<script type="text/javascript">
// <![CDATA[
	$(document).ready(function() { 
		$("#frmLogin").validate({
		rules: {
			username: {
				required: true,
				email: true
			},
			password: {
				required: true
			}			
		},
		messages: {
			username: {
				required: "Please provide username",
				email: "Please enter a valid username"
			},
			password: "Please provide password"			
		  }
		
		});
   });	
   
   function goback() {
    	history.go(-1);
	}  
 // ]]>	  
</script>