<?php include("header.php");?>
<?php 
    $sessionID = SESSION_COOK;
	$checkout =   $customers->chekoutproduct($sessionID);
 if (isset($_POST['doLogin'])):
  $result = $customers->login($_POST['username'], $_POST['password']);
  /* Login Successful */
   if ($result):
  if($checkout)
  {
	 if(isset($_SESSION['chooseAddress']))
			{
				redirect_to("checkout");
			}
			else{
			 redirect_to("getorder-type");
			 }
  }
  else
  {
  	 redirect_to("account");
  }
  endif;
  endif;
  ?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
    <div data-role="content">
     <div id="msgholder"></div>
      <form id="frmLogin" action="" method="post">
        <div data-role="fieldcontain">
          <label for="email"> <em>* </em> Email: </label>
          <input type="text" id="email" name="username" class="required username" />
        </div>
        <div data-role="fieldcontain">
          <label for="password"> <em>* </em>Password: </label>
          <input type="password" id="password" name="password" class="required" />
        </div>
        <div class="ui-body ui-body-b">
          <input type="submit" name="submit" value="Login" class="btnLogin">
        </div>
        <input name="doLogin" type="hidden" value="1" />
      </form>
    </div>
    <!-- /content -->
  </div>
  <?php include("footer.php");?>
	<script type="text/javascript">
	 $( "#frmLogin" ).validate({
			rules: {
        username: {
            required: true
        }
    },
    messages: {
        username: {
            required: "Enter your name"
        }
    }
		});
    </script>