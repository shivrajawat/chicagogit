<?php
  /**
   * User Account page 
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<div class="row-fluid margin-top">

  <div class="span12">
    <div class="span9 box-shadow fit">
      <div class="span12 padding-outer-box">
       <div id="response"></div>
       <div id="fullform">
        <form class="form-horizontal" action="" name="register_form" method="post" id="register_form">
          <fieldset>
          <!-- Form Name -->
          <legend>Welcome: </legend>
        
          </fieldset>
        </form>
        <img src="images/back.png" alt="" /> 
        </div>
        </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php include("rightside.php");?>
    <!-----RIGHT END----->
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<div style="display: none;" id="smallLoader">
  <div>
    <div> </div>
  </div>
</div>