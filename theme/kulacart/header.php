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
<?php header('Content-Type: text/html; charset=ISO-8859-1'); ?>
<?php echo $content->getMeta(); ?>
<script type="text/javascript">
var THEMEURL = "<?php echo THEMEURL; ?>";
var SITEURL = "<?php echo SITEURL; ?>";
</script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<!--<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-1.7.1.min.js"></script>-->
<link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo THEMEURL; ?>/css/bootstrap.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo THEMEURL; ?>/css/bootstrap-responsive.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo THEMEURL; ?>/css/coustom.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo THEMEURL; ?>/css/g7webs.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/datepicker.css" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo THEMEURL; ?>/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL; ?>/js/jquery.history.js"></script>
<link rel="stylesheet" href="<?php echo SITEURL; ?>/css/kulacart.css" type="text/css" media="all" />
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
	// admin redirect properly 
	if($url== '/admin.php?pagename=admin')
	{
		header("Location:".SITEURL."/admin/login.php");
	}
	?>
<div class="wrapper">
<header><div class="row-fluid header">
    <div class="span4 padding-right" style="padding-top:5px;"><a href="<?php echo SITEURL;?>/"><img src="<?php echo THEMEURL; ?>/images/logo.jpg" alt="" /> </a></div>
    <div class="span6 fit">
      <div class="navbar top-nav-menu1" > <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="#">MENU</a>
        <div class="navbar-inner">
            <div class="nav-collapse collapse navbar-responsive-collapse">
              <ul class="nav">
                <li class="active"><a href="http://chicagoconnection.com/locations/">Locations</a></li>
                <li> <a href="http://chicagoconnection.com/menu/" class="dropdown-toggle" data-toggle="dropdown">Menu</a>
                </li>
                <li><a href="http://chicagoconnection.com/coupons/">Coupons</a></li>
                <li><a href="http://chicagoconnection.com/eclub/">eClub</a></li>
                <li><a href="http://chicagoconnection.com/contact-us/"> Contact Us</a></li>
                <li><a href="#"><img src="<?php echo THEMEURL; ?>/images/home.png" alt="" /></a></li>
              </ul>
            </div>
            <!-- /.nav-collapse -->
          <div class="clr"></div>
        </div>
      </div>
    </div>
    <div class="span2"><img src="<?php echo THEMEURL; ?>/images/PizzaTradition.png" alt=""  height="144" style="float:right;" /></div>
  </div></header>
  <div class="featured"> 