<?php
  /**
   * Checkout form for authirize 
   *
   */
   
    define("_VALID_PHP", true);
  require_once("../init.php");
 ?> <?php include(THEMEDIR."/header.php");?>
 <?php 
 $site_root = SITEURL;
 header("Refresh: 3;url=$site_root/checkout"); ?>
    <h2>Error!</h2>
    <div class="error">
      <h3>We're sorry, but we can't process your order at this time due to the following error:</h3>
      <?php echo htmlentities($_GET['response_reason_text'])?>
      <table>
        <tr>
          <td>response code</td>
          <td><?php echo htmlentities($_GET['response_code'])?></td>
        </tr>
        <tr>
          <td>response reason code</td>
          <td><?php echo htmlentities($_GET['response_reason_code'])?></td>
        </tr>
      </table>
    </div>
    <form method="get" action="<?php echo SITEURL;?>/checkout">
      <input type="submit" class="submit" value="Start Over">
    </form>
<?php include(THEMEDIR."/footer.php");?>
