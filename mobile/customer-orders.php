<?php include("header.php"); ?>
<?php
   $customer_id = $_SESSION['cid'];  
   $custmer_row = $customers->customerOrderDetails($customer_id);
?>
  <div data-role="content">
    <h1 class="main-heading">Your Order History </h1>
    <!-- /header --> 
    <!-- /content -->
    <?php	 
	 if($custmer_row == 0):			 
		print  "<div class ='row-fluid'>There is no orders in order history</div>";			  
	 else:
			  
	?>
    <div class="ui-grid-d your-shopping-cart">
        <div class="ui-block-a width20"><div class="ui-bar ui-bar-e your-shopping-cart">Order Number</div></div>
        <div class="ui-block-b width15"><div class="ui-bar ui-bar-e your-shopping-cart">Order Type</div></div>
        <div class="ui-block-c width20"><div class="ui-bar ui-bar-e your-shopping-cart">Date</div></div>
        <div class="ui-block-d width10"><div class="ui-bar ui-bar-e your-shopping-cart">Total Amount</div></div>
        <div class="ui-block-e width10"><div class="ui-bar ui-bar-e your-shopping-cart">View Details</div></div>
        <div class="ui-block-e width10"><div class="ui-bar ui-bar-e your-shopping-cart">Reorder</div></div>
    </div><!-- /grid-c -->
    <?php		    		 
			 foreach($custmer_row as $row):			   
			    $order_row = $content->GetOrderDetailsForReorder($row['orderid'],$row['order_number']); 
			  
		?>
    <div class="ui-grid-d">
        <div class="ui-block-a width20"><div class="ui-bar ui-bar-e bold"><?php echo ($row['order_number'])? $row['order_number'] : "";   ?></div></div>
        <div class="ui-block-b width15">
        	<div class="ui-bar ui-bar-e"> 
				<?php
                     if($row['order_type']=='p'){ echo "Pick Up";}       //PickUp 
                     if($row['order_type']=='d'){ echo "Delivery";}      //Delivery 
                     if($row['order_type']=='dl'){ echo "Dine In";}      //Dine In 
                  ?>
        	</div>
        </div>
        <div class="ui-block-c width20"><div class="ui-bar ui-bar-e">
			<?php echo ($row['pickup_date']) ?  $row['pickup_date'] :  "";  echo ($row['pickup_time']) ?  " ".$row['pickup_time'] :  ""; ?>
        </div></div>
        <div class="ui-block-d width10"><div class="ui-bar ui-bar-e"><?php echo ($row['net_amount'])? '$'.$row['net_amount'] : ""; ?></div></div>
        <div class="ui-block-e width10">
        	<div class="ui-bar ui-bar-e">
            	<a href="<?php echo SITEURL."/mobile/order-details.php?orderid=".$row['orderid']; ?>" data-role="button" data-icon="search" data-iconpos="notext" data-theme="c" data-inline="true" data-ajax="false">View Details</a>
        		
            </div>
        </div>
        <div class="ui-block-e width10">
        	<div class="ui-bar ui-bar-e">
        	  <a href="javascript:void(0);" onClick="javascript:FlyToBasket('<?php echo $row['orderid'];?>');" rel="<?php echo $row['orderid'];?>" id="reorder" title="Click to Re-Order" data-role="button" data-icon="refresh" data-iconpos="notext" data-theme="c" data-inline="true" data-ajax="false" class="show-page-loading-msg">Reorder</a>
            </div>
        </div>
  </div>
    <?php endforeach; endif; ?>
    <a href="restaurantmenu.php" data-role="button" data-ajax="false">Add More Food</a>
</div>
<a href="index.php" data-role="button" data-theme="b" data-ajax="false">Back to Home</a> 
  <!-- /content --> 
</div>
<?php include("footer.php");?>
<script type="text/javascript">
/* IN product-detail page Onclick Buy Button product add in Fly to basket */
function FlyToBasket(order_id){    		
		
		$.mobile.loading("show");	
		
		$.ajax({
			type: "POST",
			url: "<?php echo SITEURL;?>/mobile/ajax/user.php",
			data: "FlyToBasketMobile=1&order_id="+order_id,
			success: function(res){
				$.mobile.loading("hide");	
				window.location.href='<?php echo SITEURL;?>/mobile/view-cart.php';
			}
		});	
		
 }
</script>