<?php 

if(isset($_SESSION['chooseAddress'])){
	$locationid = $_SESSION['chooseAddress'];
}
else {
	$locationrow = $menu->locationIdByMenu($websitenmae);
	$locationid = $locationrow['location'];
}
	 $webrow = $menu->checkFlow($websitenmae); //print_r($webrow); 
?>

<div class="span3 margin-left-16">
  <div class="span12 box2 fit" id="basketItemsWrap">
    <div class="row-fluid myorder_headings"><span>MY ORDERS</span> </div>
    <div id="meniheading"></div>
    <div id="hidemeniheading">
      <?php if($product->getBasket()){ ?>
      <div class="row-fluid"> <span class="item-name span7 fit">
        <h5 class="bold">Item Name</h5>
        </span> <span class="qty span2 fit">
        <h5 class="bold">Qty</h5>
        </span> <span class="Delete span3 fit">
        <h5 class="bold">Delete</h5>
        </span>
        <div class="clr"></div>
      </div>
      <?php } ?>
    </div>
    <div class="row-fluid">
      <div class="item-delete-a">
        <div id="basketItemsWrap" class="basketItemsWrap">
          <ul>
            <li></li>
            <?php echo  $product->getBasket(); ?>
          </ul>
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span11" style="margin:0 auto; float:none">
        <div id="totalprice"></div>
        <div id="hideprice">
          <?php $style='style="display:none"'; if(!$product->getBasket()){ $style='style="display:none"'; ?>
          <div class="total-order-amount span12 acenter"> Awaiting your delicious selections.<br />
            <a href="javascript:void(0);" class="CancelOrder">Cancel this order & start over</a> </div>
          <?php } else { $style='style="display:block"';?>
          <div class="total-order-amount span12 acenter bold">
            <?php $baskettotal = $product->getToalpriceinBasket(); ?>
            <?php echo 'Total Order Amount:  $'.round_to_2dp($product->getToalpriceinBasket()).'';?> </div>
          <div class="clr"></div>
          <div class="span12 acenter fit" style="margin-top:15px !important;">
            <div class="span12 fit" style="height:45px;">
              <?php if(isset($webrow['test_mode']) && $webrow['test_mode']=='1'){ ?>
              <a href="<?php echo SITEURL;?>/checkout" class="btn-2-2">VIEW CART</a>
              <?php } else { ?>
              <a href="<?php echo SITEURL;?>/view-cart" class="btn-2-2">VIEW CART</a>
              <?php  } ?>
            </div>
            <div class="clr"></div>
            <?php if(isset($webrow['test_mode']) && $webrow['test_mode']=='1'){ ?>
            <div class="span12 fit" style="height:35px;"> <a href="<?php echo SITEURL;?>/checkout" title="Checkout" class="btn-2-2">CHECKOUT </a> </div>
            <?php } else if(isset($_SESSION['chooseAddress']) && $webrow['flow']=='1') { ?>
            <div class="span12 fit" style="height:35px;"> <a href="<?php echo SITEURL;?>/checkout" title="Checkout" class="btn-2-2">CHECKOUT </a> </div>
            <?php }
			else {	
				if(isset($_SESSION['chooseAddress']) && $webrow['flow']=='2')
				{	?>
            <div class="span12 fit" style="height:35px;"> <a href="<?php echo SITEURL;?>/checkout" class="btn-2-2">CHECKOUT</a> </div>
            <?php	}
				else
				{
					?>
            <div class="span12 fit" style="height:35px;"> <a href="<?php echo SITEURL;?>/chooselocation" class="btn-2-2">CHECKOUT</a> </div>
            <?php }
			}
			?>
            <a href="javascript:void(0);" class="CancelOrder">Cancel this order & start over</a> </div>
          <?php }   ?>
        </div>
      </div>
    </div>
  </div>
  <!--Repeat last order, starts here-->
  <div class="span12 joinclub_img margin-top-30 tacenter">
    <?php 
             if(!$customers->customerlogged_in){
                $repeatOrderURL = SITEURL.'/signin?repeatOrder=1';	
                $var = 'href="'.$repeatOrderURL.'"'; 
              }
              else { 
					  		if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){
									 $row2 = $product->checkLocationIdMatchExistance();		
									 $order_type = $row2['order_type']; 
									 $location_id = $row2['location_id'];   //location id	
									 if($customers->customerFirstOrderExistance()==0) {	
										 $var = 'href="javascript:void(0);" onclick="customeFirstOrderNotExists()" ';
									 }  

									 if($location_id != $_SESSION['chooseAddress']) {	
										 $var = 'href="javascript:void(0);" onclick="locationNotMatch()" ';	
									 }
									 else {	
											$_SESSION['repeatThanksOrder'] =1;  
											$_SESSION['repeatOrder']; 
											$repeatOrderURL = SITEURL.'/repeat-lastorder.php';	
											$var = "href='".$repeatOrderURL."'";	
											if(isset($_SESSION['repeatOrder']) && $_SESSION['repeatOrder']==1 && $customers->customerlogged_in && $product->Totalbasketitem()!=0){	
												$var = 'href="javascript:void(0);" onclick="RepeatOrderExit()" ';	
											}
									 }
						    }
							
							else {
							        $_SESSION['repeatThanksOrder'] =2; 
									$_SESSION['repeatOrder']; 
									$repeatOrderURL = SITEURL.'/repeat-lastorder.php';	
									$var = "href='".$repeatOrderURL."'";									

									if($customers->customerFirstOrderExistance()==0) {																		 

										 $var = 'href="javascript:void(0);" onclick="customeFirstOrderNotExists()" ';	 

									 } 

							    	if(isset($_SESSION['repeatOrder']) && $_SESSION['repeatOrder']==1 && $customers->customerlogged_in && $product->Totalbasketitem()!=0){									

										$var = 'href="javascript:void(0);" onclick="RepeatOrderExit()" ';							 

							    	}
						    }		        

				   }
     ?>
    <a <?php echo $var; ?> class="btn-2-2" title="REPEAT LAST ORDER">REPEAT LAST ORDER</a> </div>
  <!--Repeat last order, ends here-->
  <div class="margin-top-30 span12" style="margin-left:0 !important">
    <div class="span12 box2 fit">
      <div class="row-fluid myorder_headings"><span>ORDER SETTINGS</span> </div>
      <?php 
	  		$location =  $product->LocationDetailsByDefoult($locationid);
			$webrow = $menu->checkFlow($websitenmae);	?>
      <div class="">
        <div class="order_summary_tags">Location
          <?php 
		  	if($webrow['flow']=='1'){
		  		if($webrow['hybrid']==1){
		  ?>
          (<a href="javascript:void(0);" class="ChanegeLocation my_modal_open" id="dialog-confirm">Change</a>)
          <?php
		  		} 
		    }
		  ?>
        </div>
        <h5>
          <?php  echo $location['address1'];?>
        </h5>
        <div class="order_summary_tags top_border">Service Method
          <?php 
		  	if($webrow['flow']=='1'){ 
				 if($webrow['hybrid']==1){	
		   ?>
          (<a href="javascript:void(0);" class="ChanegeLocation my_modal_open" id="service_method">Change</a>)
          <?php 
			  	}
			}
		 ?>
        </div>
        <h5>
          <?php 
			if(isset($_SESSION['orderType'])){			

				echo ($_SESSION['orderType'] == 'pick_up') ? "Pick Up" : "Delivery";
			}
			else{
				echo "Not applicable.";	
				}

			?>
        </h5>
        <div class="order_summary_tags top_border">Order Timing
          <?php 
		  if($webrow['flow']=='1'){
				if($webrow['hybrid']==1){
		  ?>
          (<a href="javascript:void(0);" class="ChanegeLocation my_modal_open" id="change_orderTime">Change</a>)
          <?php 
		  		} 
			} 
		  ?>
        </div>
        <h5>
          <?php 
			if(isset($_SESSION['orderTime']))
			{
				echo $_SESSION['orderTime'];
			}
			else
			{
				echo "Not applicable.";	
			}

		  ?>
        </h5>
      </div>
    </div>
  </div>
  <?php if(is_file("uploads/banner/".$location['banner_image'])){ ?>
  <div class="span12 joinclub_img margin-top-30"> <a href="<?php echo $location['banner_link']; ?>"> <img src="<?php echo UPLOADURL.'/banner/'.$location['banner_image']; ?>" width="230" hight="166" alt="Banner Image" /> </a> </div>
  <?php } ?>
  <div class="span12 hours_home margin-top-30 box2">
    <div class="row-fluid myorder_headings"><span>CARDS ACCEPTED</span></div>
    <div class="row-fluid margin-top-10">
      <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/visa.png"></a></div>
      <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/am-card.png"></a></div>
      <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/mastercard.png"></a></div>
    </div>
  </div>
</div>
<div id="my_modal" style="display:none">
  <div class="dialog_heading">Confirm </div>
  <p> <span style="float: left; margin: 0 7px 20px 0;"></span> Please note that making this change will require you to restart your order placement process from the beginning.<br/>
    &nbsp;
    Please click Ok to continue or Cancel to continue your order with the current settings. </p>
  <a title="Continue" class="continue bold" style="cursor:pointer">OK</a> <a title="Close" class="my_modal_close bold" style="cursor:pointer">Cancel</a> </div>
<script src="<?php echo THEMEURL;?>/js/jquery.popupoverlay.js"></script>
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
				window.location.reload();		

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

	$("a.deleteitem img").click(function() {	

		delay(function(){	
			$.ajax({
				type: "POST",  
					url: "<?php echo SITEURL; ?>/ajax/basket.php", 
					data: { action: "addViewCart"},  
					success: function(theResponse){	
						$("#totalprice").html(theResponse).show();	
						$("#hideprice").hide();	
						$("#hidemeniheading").hide();	
				}
			});
		}, 2000);
	});	

	$("a.CancelOrder").click(function() {		
		$("#smallLoader").css({display: "block"});	
		$.ajax({
				type: "POST",  
				url: "<?php echo SITEURL; ?>/ajax/user.php", 
				data: { CancelOder: "1"},  
				success: function(theResponse){	
					$("#smallLoader").css({display: "none"});	
			    	window.location.reload();
				}
		});
	});	
	
	$("a.ChanegeLocation22").click(function(){		
		var r = confirm("Are you sure to choose another location.");		
		if(r==true){
				$.ajax({
					type:"POST",	
					url:"<?php echo SITEURL;?>/ajax/user.php",	
					data:{ChangeLocation:"1"},
					cache:false,	
					success: function(theResponse){	
						window.location.href= SITEURL+"/chooselocation";	
					}
				});
			
		} else {
			return false;
		}
	});	
	
	$("a.continue").click(function(){			
				
			$("#my_modal").css({display: "none"});
			$("#smallLoader").css({display: "block"});					
			 $.ajax({
					type:"POST",	
					url:"<?php echo SITEURL;?>/ajax/user.php",	
					data:{ChangeLocation:"1"},
					cache:false,	
					success: function(theResponse){	
							window.location.href= SITEURL+"/chooselocation";	
					}
			  });	
			
		 }); 
	
 });

  function RepeatOrderExit(){				

		alert("You already added items of last order into cart.");
  }
  function locationNotMatch(){			

		alert("Last placed order location does not match with the current chosen location.");
  }
  function customeFirstOrderNotExists(){
	    alert("You didn't place any order yet.");
  }
  
  $(function() {
      $('#my_modal').popup();
  });
// ]]>
</script>
