<?php
  /**
   * 404
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: 404.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php include(THEMEDIR."/header.php");?>
<!-- Full Layout -->
<div class="container">
  <div class="">
    <div class="notfound">
      <p class="info"><img src="<?php echo SITEURL;?>/images/404-img.jpg" align="<?php echo _ER_404_1;?>"/></p>
      </div>
  </div>
</div>
<!-- Full Layout /-->
<?php include(THEMEDIR."/footer.php");?>