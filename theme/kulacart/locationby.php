<?php
  /**
   * Index
   * Kula cart   
   *
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>

<div class="row-fluid top_links_strip">
  <div class="span12">     
    <!--<div class="span4 fit"></div>-->    
    <?php include("welcome.php"); ?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top ">
    <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">       
      <!-----MENU CATEGORIES----->       
      <!-----MENU CATEGORIES END----->       
      <!-----Product Details----->
      <div class="span12 fit">
        <div class="span9">
          <div class="row-fluid">
            <div class="span12 top_heading_strip"> Choose Options Below </div>
          </div>
          <div class="row-fluid">
            <div class="span12 fit">
              <div class="span1"></div>
              <div class="span10 home_page_images">
                <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image2.png" alt="" /> </div>
                <div class="span9">
                  <div class="span12">
                    <h2>Start My Order</h2>
                    <p>Lets get started. Let's go to the Menu.</p>
                  </div>
                  <div class="span12 taright fit">
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
									
							        $_SESSION['repeatThanksOrder'] =1; 
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
                    <a <?php echo $var; ?> class="btn-2-2" title="REPEAT YOUR LAST ORDER" style="cursor:pointer;">REPEAT YOUR LAST ORDER</a>&nbsp;
                    <?php	if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){  ?>
                    <a href="<?php echo SITEURL;?>/?location" class="btn-2-2" title="Quick Order Here">GET STARTED</a>
                    <?php  } else  { ?>
                    <a href="<?php echo SITEURL;?>/chooselocation" class="btn-2-2" title="Quick Order Here">GET STARTED</a>
                    <?php  }  ?>
                  </div>
                </div>
              </div>
              <div class="span1"></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="span12 fit">
              <div class="span1"></div>
              <div class="span10 home_page_images">
                <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image1.png" alt="" /> </div>
                <div class="span9">
                  <div class="span12">
                    <h2>Create Your Profile Now!</h2>
                    <p>Create your profile for fast and easy checkout for future orders. Save locations, favorite items, and more!!</p>
                  </div>
                  <div class="span12 taright fit"> <a href="<?php echo SITEURL;?>/register" class="btn-2-2" title="Create Your Profile - Register Here">GET STARTED</a> </div>
                </div>
              </div>
              <div class="span1"></div>
            </div>
          </div>
          <div class="row-fluid">
            <div class="span12 fit">
              <div class="span1"></div>
              <div class="span10 home_page_images">
                <div class="span3"> <img src="<?php echo THEMEURL;?>/images/home_image3.png" alt="" /> </div>
                <div class="span9">
                  <div class="span12">
                    <h2>Find a Coupon</h2>
                    <p>Find a coupon to use with your order!</p>
                  </div>
                  <div class="span12 taright fit"><a href="#" class="btn-2-2" title="Find a coupon">GET STARTED</a></div>
                </div>
              </div>
              <div class="span1"></div>
            </div>
          </div>
        </div>
        <div class="span3 margin-left-16">
          <div class="span12 box2 fit" id="basketItemsWrap">
            <div class="row-fluid myorder_headings"><span>MY ORDERS</span> </div>
            <div id="meniheading"></div>
            <div id="hidemeniheading">
              <?php if($product->getBasket()){ ?>
              <div class="row-fluid"> <span class="item-name span7 fit">
                <h5>Item Name</h5>
                </span> <span class="qty span2 fit">
                <h5>Qty</h5>
                </span> <span class="Delete span3 fit">
                <h5> Delete</h5>
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
                  <?php 
				  	 $style='style="display:none"';
				 	 if(!$product->getBasket()){ $style='style="display:none"'; 
				  ?>
                  <div class="total-order-amount span12 acenter"> Awaiting your delicious selections.<br />
                    <a href="javascript:void(0);" class="CancelOrder">Cancel order & Start Over</a> </div>
                  <?php } else { $style='style="display:block"';?>
                  <div class="total-order-amount span12 acenter bold">
                  <?php $baskettotal = $product->getToalpriceinBasket(); ?>
                  <?php echo 'Total Order Amount:  $'.round_to_2dp($product->getToalpriceinBasket()).'';?> </div>
                  <div class="clr"></div>
                  <div class="span12 acenter fit">
                    <div class="span12 fit">
                      <div class="span12" align="center"> <a href="<?php echo SITEURL;?>/view-cart" class="deleteitem btn-2-2" style="float:left; margin:10px 0 2px 42px;">VIEWCART </a><br />
                        <?php if(isset($_SESSION['chooseAddress'])) { ?>
                        <a href="<?php echo SITEURL;?>/checkout" class="btn-2-2" style="float:left; margin:10px 0 10px 42px">CHECKOUT</a>
                        <?php } else { ?>
                        <a href="<?php echo SITEURL;?>/getorder-type" class="btn-2-2" style="float:left; margin:10px 0 10px 42px" >CHECKOUT</a>
                        <?php } ?>
                        <div class="clr"></div>
                      </div>
                      <a href="javascript:void(0);" class="CancelOrder">Cancel order & Start Over</a> </div>
                  </div>
                  <?php }?>
                </div>
              </div>
            </div>
          </div>
          <div class="span12 joinclub_img margin-top-30"> <a href="<?php echo SITEURL;?>/register"><img src="<?php echo THEMEURL;?>/images/join_eclub.png" alt="" /></a> </div>
          <?php if(isset($webrow['hours_notes']) && !empty($webrow['hours_notes'])){ ?>
          <div class="span12 box2 hours_home margin-top-30">
            <div class="row-fluid myorder_headings"><span>ONLINE STORE HOURS</span></div>
            <div class="row-fluid">
              <div class="span12 hoursdisplay">
                <?php
				     echo cleanOut($webrow['hours_notes']);
					 /*$hours = $product->landingpageHoursInstallManager($websitenmae);					 
					 if($hours):
								echo cleanOut($hours['hours_notes']);
					 endif;*/
				?>
              </div>
            </div>
          </div>
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
        <div id="content-top-shadow"></div>
        <div id="content-bottom-shadow"></div>
        <div id="content-widget-light"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
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


	$("a.deleteitem img").click(function(){	
	
		delay(function(){
			
			$.ajax({
			   type: "POST", 
				url: "<?php echo SITEURL; ?>/ajax/basket.php",
				data: { action: "addViewCart" },
				success: function(theResponse){
					$("#totalprice").html(theResponse).show();
					$("#hideprice").hide();	
					$("#hidemeniheading").hide();
				}
			});

		}, 2000);
	});	


	$("a.CancelOrder").click(function(){
				
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
	
	$("a.ChanegeLocation").click(function(){
		
		$.ajax({
			type:"POST",
			url:"<?php echo SITEURL;?>/ajax/user.php",
			data:{ChangeLocation:"1"},
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
  
//]]>
</script>