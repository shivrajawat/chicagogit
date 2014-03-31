<?php
  /**
   * Register 
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
          <legend>Login an account</legend>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Username:<span>*</span></label>
            <div class="controls">
              <input name="username" placeholder="Enter username" class="input-xlarge"  type="text">
            </div>
          </div>
          <!-- Text input-->
          <div class="control-group">
            <label class="control-label" for="textinput">Password:<span>*</span></label>
            <div class="controls">
              <input name="password" placeholder="Enter password" class="input-xlarge" type="password">
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
            <input name="submit" value="Login" type="submit" class="btn"/>           
            <input name="doLogin" type="hidden" value="1" />
            </div>
          </div>
          </fieldset>
        </form>
        <img src="images/back.png" alt="" /> 
        </div>
        </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <div class="span3">
      <div class="span12 box-shadow padding-outer-box ">
        <h3 class="h4">ORDER SUMMARY</h3>
        <span class="add-details">Location :</span>
        <div class="clr"></div>
        <p class="location">Rapid City,SD, 
          1325 N Elk Vale Road, 
          Rapid City  57703
          (605) 791-1800</p>
        <span class="add-details">Location :</span>
        <p class="location">Rapid City,SD, </p>
        <span class="add-details">Expected Order Time :</span>
        <div class="clr"></div>
        <p>07/15/2012 03:31 PM</p>
        <div class="clr"></div>
      </div>
      <div class="span12 box-shadow  margin-top  padding-outer-box left "> <span class="item-name">Item Name</span> <span class="qty"> Qty</span> <span class="Delete"> Delete</span>
        <div class="clr"></div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon Avocado</span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon </span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="item-delete-a"> <span class="item-name1">ChickenBacon </span><span class="qty1">20</span><span class="Delete1"><img src="images/close.png" alt="" /></span>
          <div class="clr"></div>
        </div>
        <div class="total-order-amount"> Total Order Amount </div>
        <div class="clr"></div>
        <br />
        <div class="view-cart"><a href="#">View Cart</a></div>
        <img src="images/cart.png" alt="" style="float:left" />
        <div class="clr"></div>
        <br />
        <div class="checkout"><a href="#">CHECKOUT</a></div>
        <img src="images/checkout.png" alt="" style="float:left" />
        <div class="clr"></div>
        <p class="cancel-order">Cancel order & Start Over</p>
      </div>
      <div class="span12 box-shadow  margin-top  padding-outer-box left ">
        <h4 class="h4">USER LOGIN</h4>
        <label>Login</label>
        <input type="text" placeholder="Type something…">
        <label>Password</label>
        <input type="text" placeholder="Type something…">
        <label class="checkbox">
        <input type="checkbox">
        Check me out </label>
        <button type="submit" class="btn">CONTINUE</button>
        <br />
        <br />
        <a href="#" class="forgot">Forgot Password?</a><br />
        <a href="#" class="forgot">Register as a New User</a> </div>
    </div>
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

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
  
  $("#register_form").validate({
	rules: {		
		username: {
			required: true,
			minlength: 4
		},
		password: {
			required: true,
			minlength: 5
		},
	},
	messages: {
		username: "Please provide your username",		
		password: {
			required: "Please provide a password",
			minlength: "Your password must be at least 5 characters long"
		}
		
	}
	
});


});
// ]]>
</script>