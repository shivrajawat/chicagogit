<?php
  /**
   * Login page
   *   
   */
  
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php
  if ($user->is_Admin())
      redirect_to("index.php");
	  
  if (isset($_POST['submit']))
      : $result = $user->login($_POST['username'], $_POST['password']);
  //Login successful 
  if ($result)
      : $wojosec->writeLog(_USER . ' ' . $user->username. ' ' . _LG_LOGIN, "user", "no", "user");
	  redirect_to("index.php");
  endif;
  endif;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->site_name;?></title>
<link href="assets/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
<script type="text/javascript" src="../assets/jquery-ui.js"></script>
<script type="text/javascript" src="../assets/global.js"></script>
<style type="text/css">
.logo_body{
background: url("images/body.png") repeat-x scroll 0 0 #F9F9F9;
}
#container {
    min-height: 100%;
    width: 100%;
}

#middle {
    margin: 0 auto;
    padding: 25px 0 60px;
    width: 1040px;
}
#header {
    height: 118px;
    margin: 0 auto;
    position: relative;
    width: 1040px;
}

#logo {
    display: block;
    height: 118px;
    margin: 0 auto;
    width: 552px;
}

fieldset, img {
    border: 0 none;
}
</style>
</head>
<body class="logo_body">
<div id="container">
<div id="header">
				<a target="_blank" id="logo" href="http://kulasite.com"><img alt="Food Delivery Script" src="images/logo.jpg"></a>
			</div>
<div id="middle">
<div id="loginform">
  <form id="admin_form" name="admin_form" method="post" action="login.php">
    <h1>admin panel</h1>
    <fieldset id="inputs">
      <input id="username" name="username" type="text" onClick="disAutoComplete(this);" />
      <input id="password" name="password" type="password" />
    </fieldset>
    <fieldset id="actions">
      <input type="submit" name="submit" id="submit" value="<?php echo _UA_LOGIN;?>" />
      <div>
        <p>&lsaquo; <a href="../index.php"><?php echo _LG_BACK;?></a></p>
        Copyright &copy; <?php echo date('Y').' '.$core->site_name;?></div>
    </fieldset>
  </form>
</div>
</div>
</div>
<div id="message-box"><?php print $core->showMsg;?></div>
</body>
</html>