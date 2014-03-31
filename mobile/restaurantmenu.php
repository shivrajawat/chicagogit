<?php include("header.php"); ?>
<?php 
$webrow = $menu->checkFlow($websitenmae);
if($webrow['flow']=='1')
{
	if(isset($_SESSION['chooseAddress']))
	{
		$locationid = $_SESSION['chooseAddress'];
		$ordertime =  date('h:i:s A', strtotime($_SESSION['orderHour'])); 
		$availabledays = date('D', strtotime($_SESSION['orderDate']));
		if(!$locationid)
		{
			header("location:chooselocation.php");
		}
	}
}
else
{
	$locationrow = $menu->locationIdByMenu($websitenmae);
	$locationid = $locationrow['location'];
	
		$TimeZone = $product->TimeZone($locationid);
		$addhour  = 60*60*($TimeZone['hour_diff']);
		$addmin = 60*$TimeZone['minute_diff'];
		$daylight = 60*60*$TimeZone['daylight_saving'];
		$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight)));
		$date =   date('m/d/Y',strtotime($datetime));
									
		$hour = date('g:i A', strtotime($datetime));
		
		$ordertime =  date('h:i:s A', strtotime($hour));
		$availabledays = date('D', strtotime($date));
}
if(empty($locationid))
{
	header("Location:index.php");
}
	$location =  $product->LocationDetailsByDefoult($locationid);
?>
  <div data-role="content">
    <div class="ui-grid-a">
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >
          <h1 class="main-heading"> Menu </h1>
        </div>
      </div>
      <div class="ui-block-d">
        <div class="ui-bar ui-bar-e table-1-a-b fit" >
        	<a href="#" class="img_viewcart" data-role="button" data-theme="c" data-inline="true" style="float:right;">View Cart (<?php echo $product->Totalbasketitem();?>)
            </a>
        </div>
      </div>
    </div>    
    <!--show item and topping details, starts here-->
    <div class="cart-content" style="display:none">
      <h1 class="heading-top">ORDER SUMMARY:</h1>
      <span class="heading-product-type">Location :</span> <span class="product-type">
      <?php
	  	echo ($location['location_name']) ? $location['location_name'].', <br/>			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : "";
		echo ($location['address1']) ? $location['address1'] : ""; 
	  ?>
      </span><br>
      <span class="heading-product-type">Order Type :</span> <span class="product-type">
      <?php 
		if(isset($_SESSION['orderType'])) {	echo ($_SESSION['orderType'] == 'pick_up') ? "Pick Up" : "Delivery";	}
		else {	echo "Not applicable.";	  }
	  ?>
      </span><br>
      <span class="heading-product-type">Expected Order Time :</span> <span class="product-type">
      <?php 
		if(isset($_SESSION['orderTime'])) { echo $_SESSION['orderTime']; }
		else {  echo "Not applicable.";   }
	  ?>
      </span>
      <?php            
            $allproductrow = $product->AllProductInBasket();           
			if($allproductrow){			
       ?>
      <div class="ui-grid-c your-shopping-cart">
        <div class="ui-block-a width45">
          <div class="ui-bar ui-bar-e your-shopping-cart" >Item Name</div>
        </div>
        <div class="ui-block-b">
          <div class="ui-bar ui-bar-e your-shopping-cart">Unit Price</div>
        </div>
        <div class="ui-block-c width10">
          <div class="ui-bar ui-bar-e your-shopping-cart">Qty</div>
        </div>
        <div class="ui-block-d">
          <div class="ui-bar ui-bar-e your-shopping-cart"></div>
        </div>
      </div>
      <?php 
		$gross_amount = "";		
		$i=1;		
		foreach($allproductrow as $row)
		{		
			$gross_amount += $row['total_price'];
		?>
      <div class="ui-grid-c">
        <div class="ui-block-a width45">
          <div class="ui-bar ui-bar-e"><?php echo $row['name']; ?></div>
        </div>
        <div class="ui-block-b">
          <div class="ui-bar ui-bar-e">
            <?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "$ " . $row['unit_price'];}?>
          </div>
        </div>
        <div class="ui-block-c width10">
          <div class="ui-bar ui-bar-e"><?php echo $row['quantity'];?></div>
        </div>
        <div class="ui-block-d width15">
          <div class="ui-bar ui-bar-e"><a href="#" id="<?php echo $row['basket_id'];?>" class="delete" data-role="button" data-icon="delete" data-iconpos="notext" data-inline="true">Icon only</a></div>
        </div>
      </div>
      <?php
				$i++; 
				}
	  ?>
      <a href="<?php echo SITEURL; ?>/mobile/view-cart.php" data-ajax="false" data-role="button">View Cart</a>
      <?php 
             if (!$customers->customerlogged_in){  ?>
      <a href="<?php echo SITEURL; ?>/mobile/login.php" data-ajax="false" data-role="button">Checkout</a>
      <?php } else { ?>
      <a href="<?php echo SITEURL; ?>/mobile/checkout.php" data-ajax="false" data-role="button">Checkout</a>
      <?php  } ?>
      <?php
			}
			else 
			{
	  ?>
      <span class="heading-product-no-type"><br/>
      There is no items in cart.</span>
      <?php
			}
	  ?>
    </div>
    <!--show item and topping details, ends here--> 
    <!-- /header -->
    <div data-role="collapsible-set" data-inset="false">
      <?php	
	   if(isset($_SESSION['chooseAddress']))
	   {
			$locationid = $_SESSION['chooseAddress'];
		}
		else {					
			$locationrow = $menu->locationIdByMenu($websitenmae);
			$locationid = $locationrow['location'];
		}	     	  	 
	 	 $menurow = $menu->CategoryMenuSubMenu($locationid); 
		   $orerdady   =  getDayfullname($availabledays);
		   $ordertime =  date('h:i:s A', strtotime($_SESSION['orderHour']));
		   $availabilityTime =  $menu->OrderAvailabilityTime($locationid,$orerdady,$ordertime);	 
		 if($menurow){
		  $i = 1;			
		  foreach($menurow as $row){					
	    ?>
      <div data-role="collapsible">
        <h3 class="collspace"><?php echo ucfirst($row['menu']); ?></h3>
        <ul data-role="listview" data-inset="false">
        <?php 
		if($row['id'] != 0)
		{					
			$subcat = $menu->getSubCatTree($row['id']);
			if($subcat != 0)
			{
			foreach($subcat as $srow)
			{ 
			
			?>
            <div data-role="collapsible" style="margin-left:10px;">
            <h3><?php echo ucfirst($srow['category_name']); ?></h3>
            <ul data-role="listview" data-inset="false">
		  		<?php 
		  		$product = $menu->productnameByCat($srow['id'],$availabledays,$availabilityTime);
				
			    if($product){	
				foreach($product as $prow){
				 if($prow['sizedid']!=0)
				 {
					$sizedId = "&sizedid=".$prow['sizedid']."";
				 }
				 else
					{
						$sizedId="";
					}
				  ?>
				  <li><a  data-ajax="false" href="toppingdetails.php?Item=<?php echo $prow['id'];?><?php echo $sizedId;?>">	
				  <?php
				   if(isset($prow['thumb_item_image']) && $prow['thumb_item_image']!='' && is_file("../uploads/menuitem/thumb/".$prow['thumb_item_image']))
					{ ?>
					<img src="<?php echo UPLOADURL;?>/menuitem/thumb/<?php echo $prow['thumb_item_image'];?>"  width="50" height="50" />
					<?php }			  

				   echo $prow['item_name'];?></a></li>
				  <?php }	
				   }
		 
			 ?>
            </ul>
       		</div>
			<?php 
				}
			}
			else
			{ 
		  		
		  		$product = $menu->productnameByCat($row['id'],$availabledays);	
			    if($product){				

				foreach($product as $prow){
				 if($prow['sizedid']!=0)
				 {
					$sizedId = "&sizedid=".$prow['sizedid']."";
				 }
				 else
					{
						$sizedId="";
					}
				  ?>
				  <li><a  data-ajax="false" href="toppingdetails.php?Item=<?php echo $prow['id'];?><?php echo $sizedId;?>">	
				  <?php
				   if(isset($prow['thumb_item_image']) && $prow['thumb_item_image']!='' && is_file("../uploads/menuitem/thumb/".$prow['thumb_item_image']))
					{ ?>
					<img src="<?php echo UPLOADURL;?>/menuitem/thumb/<?php echo $prow['thumb_item_image'];?>"  width="50" height="50" />
					<?php }			  

				   echo $prow['item_name'];?></a></li>
				  <?php }	
				   }
			 }
			
		}
		?>			  
				  
        </ul>
      </div>
      <?php 
    			$i++;
			}
		   unset($row);
		}
		/*--------------------------------------  END Category Menu submenu list in accordian style ------------------------------------------------*/
		?>
    </div>
    <!-- /content -->
    <div id="cartDiv" style="display:none;">This is testing </div>
    <a href="#" data-role="button" class="CancelOrder">CANCEL ORDER AND START OVER</a> </div>
</div>
<?php include("footer.php");?>

<script type="text/javascript">
$(document).ready(function(){	
		$("a.CancelOrder").click(function() {
		$.ajax({
		type: "POST",  
				url: "<?php echo SITEURL; ?>/ajax/user.php",  
				data: { CancelOder: "1"},  
				success: function(theResponse){	
			    	window.location.href='index.php';
				}
		});
	});	
	
	$("ul li:last-child").addClass("last-item");
});
</script>
<script type="text/javascript">
        $(document).ready(function() {
            $('.img_viewcart').click(function() {				
                if ($('.cart-content').hasClass('activeCart') == false) {
                    $('.cart-content').addClass('activeCart');
                }
                else {
                    $('.cart-content').removeClass('activeCart');
                }
            });
        });
</script>

<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {	
    $('a.delete').click(function() {					
        var id = $(this).attr('id');						
		$.ajax({
			type: 'post',
			url: "<?php echo SITEURL;?>/ajax/user.php",
			data: 'deleteInBasket=' + id,
			cache: false,				
			success: function () {
				window.location.reload();
			}
		}); 
		
    });	
});
// ]]>
</script>
<style type="text/css">
li.last-item { margin-bottom:10px !important;/* ... */ }
</style>