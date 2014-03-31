<?php
/**
* User Account
*  
*/
define("_VALID_PHP", true);
require_once("init.php");

include(THEMEDIR."/header.php");
$webrow = $menu->checkFlow($websitenmae);

if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress']))
{  
	header("Location:".SITEURL."/?location");
}

if($webrow['flow']=='3' || $webrow['test_mode']=='1')
{
	require_once(THEMEDIR . "/menuby.php");
}
else
{
	require_once(THEMEDIR . "/chooselocation.tpl.php");
}
include(THEMEDIR."/footer.php");
?>