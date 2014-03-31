<?php include("header.php");?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
    <div data-role="content">
          <h1 class="main-heading">Forget Your Password</h1>
          <p>Please enter the recovery email address you provided when creating your account.</p>
     <div id="msgholder" class="error">
     
     </div>
      <form id="forgotpassword" action="" method="post" data-ajax="false" name="forgotpassword">
        <div data-role="fieldcontain">
          <input type="text" id="email" name="username" class="required username" placeholder="Username" title="Username / Email"/>
        </div>        
        <div class="ui-body ui-body-b">
          <input type="submit" name="submit" value="Recover Password" class="btnLogin">
        </div>        
        <input name="ForgotPassword" type="hidden" value="1" />
      </form>
    </div>
    <a href="javascript:goback()"  data-role="button" data-icon="arrow-l" data-iconpos="left">Back</a> 
    <a href="index.php" data-role="button" data-ajax="false">Back To Home</a>
    <!-- /content -->
  </div>
<?php include("footer.php");?>
<script type="text/javascript">
	
	$(document).ready(function() { 
		$("#forgotpassword").validate({
		rules: {
			username: {
				required: true,
				email: true,
				remote:{url:"<?php echo SITEURL;?>/ajax/username_exists.php", type:"post"}	
			}		
		},
		messages: {
			username: {
				required: "Please provide your username / email ",
			    email: "Please provide a valid username / email",
				remote: "Username does not exist"
			}
		},
		submitHandler: function(form) {
			
				 var str = $("#forgotpassword").serialize();
				 $.mobile.loading( "show");
				 				 
				  $.ajax({
					  type: "POST",
					  url: "<?php echo SITEURL;?>/ajax/user.php",
					  data: str,
					  success: function (msg){
						  
						    $.mobile.loading("hide");							
							$("#msgholder").html(msg);							
							alert("You have successfully reset your password. Please log in to your email to retrieve this password.You can then change your password on your profile page.");
							
							window.location.href = "<?php echo SITEURL;?>/mobile/login.php";
							
					   }
				});
			  return false;
			}
		
		});
   });	   
   
	function goback(){
	
		history.go(-1);
	
	}   
</script>