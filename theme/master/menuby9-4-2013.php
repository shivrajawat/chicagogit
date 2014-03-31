<?php
  /**
   * Index
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $location = $menu->locationIdByMenu($websitenmae);?>

<h1>Menu By </h1>
<div class="row-fluid margin-top">
  <div class="span12">
    <!-----MENU CATEGORIES----->
    <div class="span3">
      <h3 class="title h3">MENU CATEGORIES</h3>
      <ul class="nav">
        <?php $category = $menu->categotyfront($location['location']);
			if($category):
			foreach($category as $crow):?>
        <li><a href="#" id="clickingEvent" class="category" rel="<?php echo $crow['id']; ?>"><?php echo $crow['category_name'];?></a> </li>
        <?php endforeach; endif;?>
      </ul>
    </div>
    <!-----MENU CATEGORIES END----->
    <!-----Product Details----->
    <div class="span6 box-shadow fit">
      <div class="span12 padding-outer-box" id="productview">
        <!-------------------------------Product Show By category use jquey---------------------------------- -->
        <img src="images/back.png" alt="" /> </div>
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
<script>
 $(document).ready(function(){
 // onclick category product list view By category
  $('a.category').click(function() 
  { 
	  $("#smallLoader").css({display: "block"});
		var catID = $(this).attr('rel');	
		$("ul.nav li").each(function(){
			$(this).removeClass("active");
		});
		$(this).parent().addClass("active");
		$.get('<?php echo SITEURL;?>/ajax/productlist.php', { catid:catID }, function(data) {	
			$("#smallLoader").css({display: "none"});
		   $('#productview').html(data);      
		});
		return false; // prevent default
  });

  // Onload page product list View first li a rel value 
   var defaultcatID =  $('ul.nav li:first a').attr('rel');
    $("#smallLoader").css({display: "block"});
    $('ul.nav li:first').addClass("active");
   $.get('<?php echo SITEURL;?>/ajax/productlist.php', { catid:defaultcatID }, function(data) {
   	   $("#smallLoader").css({display: "none"});
       $('#productview').html(data);      
    });
    return false; // prevent default
  
});
</script>
