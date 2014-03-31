<?php
  /**
   * Header
   * Kulacart Online food order 
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!doctype html>
<head>
<?php echo $content->getMeta(); ?>
<script type="text/javascript">
var THEMEURL = "<?php echo THEMEURL; ?>";
var SITEURL = "<?php echo SITEURL; ?>";
</script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-1.7.1.min.js"></script>
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/bootstrap.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/bootstrap-responsive.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/kulacart.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo THEMEURL; ?>/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL; ?>/js/jquery.history.js"></script>
</head>
<body>
<?php $url = $_SERVER['REQUEST_URI']; //returns the current URL
	$parts = explode('/',$url);
	$dir = $_SERVER['SERVER_NAME'];
	for ($i = 0; $i < count($parts) - 1; $i++) {
	 $dir .= $parts[$i] . "/";
	}
	$domain = substr($dir, 0, strlen($dir)-1);
	
	$websitenmae = preg_replace('#^www\.(.+\.)#i', '$1', $domain); 
?>
<div class="container">
<div class="row-fluid">
  <div class="span12">
    <div class="span7 bg1"><a href="<?php echo SITEURL;?>/" style="color:#FFFFFF;">LOGO</a></div>    
    <div class="span5  bg2 fit"> <?php if($core->showlogin):?>      
      <?php 
	  if($customers->customerlogged_in):?>
        		<a href="<?php echo SITEURL;?>/account">MY ACCOUNT</a> | <a href="<?php echo SITEURL;?>/logout.php">Signout</a>     
          <?php else:?>
          <strong>Welcome Guest</strong>&nbsp;
           	<a href="<?php echo SITEURL;?>/signin">Sign In</a> | <a href="<?php echo SITEURL;?>/register.php">Sign up</a>
          <?php endif;?>   
        <!--/ Login End --> 
         <?php endif;?></div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>