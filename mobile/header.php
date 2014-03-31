<?php 
define("_VALID_PHP", true);
 require_once("../init.php");
 
 	$url = $_SERVER['REQUEST_URI']; //returns the current URL
		
	$parts = explode('/',$url);		
	
	$dir = $_SERVER['SERVER_NAME'];
	
	for ($i = 0; $i < count($parts) - 2; $i++) {
		 $dir .= $parts[$i] . "/";			
	}
	
	 $domain = substr($dir, 0, strlen($dir)-1);	
	 $websitenmae = preg_replace('#^www\.(.+\.)#i', '$1', $domain); 
 
?>
<!DOCTYPE HTML>
<html>
<head>
<?php header('Content-Type: text/html; charset=ISO-8859-1'); ?>
<!-- Explicit charset definition -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 "/>
<!-- useful for mobile devices -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Includes javascript & css files required for jquery mobile -->
<link rel="stylesheet" href="css/jquery.mobile-1.3.2.min.css" type="text/css" />
<link rel="stylesheet" href="css/kulacartmobile.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.mobile-1.3.2.min.js"></script>
<!-- INCLUDE YOUR FILE 'test.js' -->
<script type="text/javascript" src="js/test.js"></script>
</head>
<body>
<div data-role="header">
  <div align="center" style="margin:5px 0 10px 0;"><a href="index.php"><img src="images/logo.png" alt=""></a></div>
</div>
<!-- /header -->
<div  class="outer-page">
	<?php 
        if($core->showlogin){
            if($customers->customerlogged_in){		
    ?>
	<div class="pagesnames tacenter">&nbsp;<?php echo $customers->customername; ?> &nbsp;&nbsp;|
		<a href="<?php echo SITEURL.'/mobile/account.php'; ?>"  data-ajax="false">My Profile</a> &nbsp;&nbsp;|&nbsp;&nbsp;
		<a data-ajax="false" href="<?php echo SITEURL.'/mobile/logout.php'; ?>">Logout</a> 
	</div>
	<?php			
        }
        else{
    ?>
 	<div class="pagesnames tacenter">&nbsp;Welcome Guest&nbsp;&nbsp;|
  		<a href="<?php echo SITEURL.'/mobile/login.php'; ?>">Login</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo SITEURL.'/mobile/register.php'; ?>">Register</a> 
 	</div>
	<?php		  
         } 
       }  
    ?>