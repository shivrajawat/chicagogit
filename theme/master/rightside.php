<div class="span3">
      <div class="span12 box-shadow padding-outer-box ">
        <h3 class="h4">ORDER SUMMARY</h3>
       <?php $location =  $product->LocationDetailsByDefoult($location['location']); ?>
        <span class="add-details">Location:</span>
        <div class="clr"></div>
        <p class="location"><?php  echo $location['location_name'];?></p>
        <span class="add-details">Address :</span>
        <p class="location"><?php  echo $location['address1'];?>,</p>
        <span class="add-details">Expected Order Time :</span>
        <div class="clr"></div>
        <p>Not Applicable </p>
        <div class="clr"></div>
      </div>      
      <div class="span12 box-shadow  margin-top  padding-outer-box left" id="basketItemsWrap">
      	 <span class="item-name span7">Item Name</span> 
         <span class="qty span2"> Qty</span> 
         <span class="Delete span3"> Delete</span>
        <div class="clr"></div>
        <div class="item-delete-a">
        <div id="basketItemsWrap" class="basketItemsWrap">        
					<ul>
					<li></li>
					  <?php echo  $product->getBasket(); ?>
					</ul>
				</div>       	
          <div class="clr"></div>
        </div>      
        <div id="totalprice"></div>
        <div id="hideprice">
         <?php $style='style="display:none"'; if(!$product->getBasket()){ $style='style="display:none"'; ?> 
         <div class="total-order-amount span12" > Shopping Cart is empty</div>
         <?php } else { $style='style="display:block"';?>
        <div class="total-order-amount span12"> 
		<?php $baskettotal = $product->getToalpriceinBasket();
		 		
		
		?> <?php echo 'Total Order Amount:  $'.round_to_2dp($product->getToalpriceinBasket()).'';?></div>
        <div class="clr"></div>
        <br />
        <div class="view-cart"><a href="<?php echo SITEURL;?>/view-cart">View Cart</a></div>
        <img src="images/cart.png" alt="" style="float:left" />
        <div class="clr"></div>
        <br />
        <div class="checkout"><a href="<?php echo SITEURL;?>/checkout">CHECKOUT</a></div>
        <img src="images/checkout.png" alt="" style="float:left" />
          <?php }?>
          </div>
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
   <script type="text/javascript">
// <![CDATA[
  $(document).ready(function() {	  
	  	$("#basketItemsWrap li img").live("click", function(event) { 														
		var productIDValSplitter 	= (this.id).split("_");
		var productIDVal 			= productIDValSplitter[1];		
		$("#smallLoader").css({display: "block"});	
		$.ajax({  
		type: "POST",  
		url: "<?php echo SITEURL;?>/ajax/basket.php",  
		data: { productID: productIDVal, action: "deleteFromBasket"},  
		success: function(theResponse) {			
			$("#productID_" + productIDVal).hide("slow",  function() {$(this).remove();});
			$("#smallLoader").css({display: "none"});
		
		}  
		});  
		
	});
	
		  var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
          clearTimeout (timer);
         timer = setTimeout(callback, ms);
          };
		})();
		/*$(".productPriceWrapRight a input").click(function() {
	delay(function(){
	$.ajax({
		type: "POST",  
				url: "<?php echo SITEURL; ?>/ajax/basket.php",  
				data: { action: "addTotalPriceBasket"},  
				success: function(theResponse){
			//$('#suggestions').html(res);
			$("#totalprice").html(theResponse);		
			
		}
	});
	}, 2000);
	});*/
		/*$(".productPriceWrapRight a input").click(function() {
	delay(function(){
	$.ajax({
		type: "POST",  
				url: "<?php echo SITEURL; ?>/ajax/basket.php",  
				data: { action: "addViewCart"},  
				success: function(theResponse){
			//$('#suggestions').html(res);
			$("#totalprice").html(theResponse);		
			
		}
	});
	}, 2000);
	});*/
	$("a.deleteitem img").click(function() {
	delay(function(){
	$.ajax({
		type: "POST",  
				url: "<?php echo SITEURL; ?>/ajax/basket.php",  
				data: { action: "addViewCart"},  
				success: function(theResponse){			
			   $("#totalprice").html(theResponse).show();	
			   $("#hideprice").hide();	
			
		}
	});
	}, 2000);
	});
  });
// ]]>
</script>