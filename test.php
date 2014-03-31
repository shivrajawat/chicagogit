<?php
  /**
   * Index
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");?>
	<?php include(THEMEDIR."/header.php");?>
<?php 	$webrow = $menu->checkFlow($websitenmae);
  $core->getVisitors(); // visitor counter
  if ($core->offline == 1 && $user->is_Admin()):
      require_once(THEMEDIR . "/index.php");
  elseif ($core->offline == 1):
      require_once("maintenance.php");
  elseif($webrow['flow']=='1'):
  		require_once(THEMEDIR . "/locationby.php");
  else:
      require_once(THEMEDIR . "/menuby9-4-2013.php");

endif;
?>
<?php include(THEMEDIR."/footer.php");?>