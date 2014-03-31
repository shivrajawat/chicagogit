<?php

/**
	* Checkout 	
	* Kula cart 
	*  
*/ 
if (!defined("_VALID_PHP"))
	die('Direct access to this location is not allowed.');
	
 $location = $menu->locationListByMenu($websitenmae);
 $webrow = $menu->checkFlow($websitenmae);
 
if (isset($_POST['processCheckout']) || isset($_SESSION['chooseAddress'])  || $webrow['flow']=='3' || $webrow['test_mode']=='1' ):   
		
		if(isset($webrow['test_mode']) && $webrow['test_mode']=='1'){	
			$locationID = $webrow['default_location'];
			date_default_timezone_set("UTC");
			$TimeZone = $product->TimeZone($locationID);
			$addhour  = 60*60*($TimeZone['hour_diff']);
			$addmin = 60*$TimeZone['minute_diff'];
			$daylight = 60*60*$TimeZone['daylight_saving'];
			$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight)));
			$date =   date('m/d/Y',strtotime($datetime));
			
			$ordertype =  "pick_up";
			$orderdate =   $date;
			$ordertime  =  date('g:i A',strtotime($datetime));	
		}
		else
		{
			$locationID = $_SESSION['chooseAddress'];
			$ordertype =  $_SESSION['orderType'];
			$orderTime =  $_SESSION['orderTime'];	
			$orderdate =  $_SESSION['orderDate'];
			$ordertime  = $_SESSION['orderHour'];
		}
	$loc =  $product->locationName($locationID);
    $additionalfeesrow = $product->additionalAmmount($locationID);
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<div class="row-fluid top_links_strip">
  <div class="span12">
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Place Your Order </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top">
    <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">
      <div class="span12 fit" id="checkoutprocess">
        <div class="span9 fit">
          <div class="row-fluid">
            <div class="span12 top_heading_strip"> Place Your Order </div>
          </div>
          <div class="span12 fit">
            <?php 			
			$allproductrow = $product->AllProductInBasket();
			if($allproductrow){	
		    ?>
            <div class="span12 fit"><br />
              <table class="table table-bordered product-table-border table-bordered1" border="0" style="border:none !important;">
                <thead>
                  <tr class="product-des-name">
                    <th class="tacenter">Quantity</th>
                    <th width="250px">Item Description</th>
                    <th class="tacenter">Unit Price</th>
                    <th class="tacenter">Total Amount</th>
                  </tr>
                </thead>
                <?php 

				$neat_amount = "";
				$gross_amount = "";
				foreach($allproductrow as $row){
					$ItemSize = $product->getItemSize($row['menu_size_map_id']);  //Item Size
					$gross_amount += $row['total_price'];
              ?>
                <tbody>
                  <tr>
                    <td class="tacenter"><strong><?php echo $row['quantity'];?></strong></td>
                    <td><strong><?php echo $row['name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?> </strong>
                      <?php /*?><br /><?php echo $row['description'];?><?php */?></td>
                    <td class="tacenter"><strong>
                      <?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "$ " . $row['unit_price'];}?>
                      </strong></td>
                    <?php /*?><td><strong><?php echo $row['quantity'];?></strong></td><?php */?>
                    <td class="tacenter"><strong><?php echo "$ " . $row['total_price'];?></strong></td>
                  </tr>
                </tbody>
                <?php
				if($row['topping_details']){
			  ?>
                <tbody>
                  <tr class="product-des-name2">
                    <td>&nbsp;</td>
                    <td colspan="4"><span> <a id="<?php echo $row['basket_id'];?>" class="imageDivLink"> Toppings & Extras <i class="icon-chevron-down"></i> </a> </span></td>
                  </tr>
                </tbody>
                <tbody id="showToggle<?php echo $row['basket_id'];?>">
                <?php 
				$toppingdetails = $row['topping_details'];
				$toppingarray = explode("%%", $toppingdetails);
				$match = 0;
				for($ct = 0; $ct < count($toppingarray); $ct++)
				{
				 $toppingrow = $toppingarray[$ct];
				 $toppingrow = explode("||", $toppingrow);	
				 $optionChoiceName = $product->showOptinChoicName($toppingrow[5]);  //option choice name
				 $nexttopping = $toppingarray[$ct+1];
				 $nexttoppingrow = explode("||", $nexttopping);	
				 if($ct<count($toppingarray))
				 {	
					 if($nexttoppingrow[3]==$toppingrow[3] && $match == 0)
					 {
						echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
						$match=1;
					 }
					 else if($ct==0)
					 {
						echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
					 }
					 else if($nexttoppingrow[3]!=$toppingrow[3])
						{
							if($match==0)
							{
								echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
							}
							else 
							{
								$match = 0;
							}
					  }
				 }
				 else 
				 {
						if($match==0)
						{
							echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
						} 
				}
                 ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td><?php 
						if(isset($toppingrow[0])){ echo $toppingrow[0]; }	
						if(isset($toppingrow[0]) && !empty($toppingrow[0]) && $optionChoiceName && $toppingrow[1]==0.00 ) { 
							echo " (".$optionChoiceName.")"; 
						}
						else if(isset($toppingrow[0]) && !empty($toppingrow[0]) && empty($optionChoiceName) && $toppingrow[1]!=0.00 ) {
							 echo " (".$toppingrow[1].")"; 
						}					

						else if(isset($toppingrow[0]) && !empty($toppingrow[0]) && !empty($toppingrow[1]) && $toppingrow[1]!=0.00 && $optionChoiceName) {
							echo " (".$optionChoiceName."-".$toppingrow[1].")";	
						} 
					?></td>
                  </tr>
                  <?php 
				}
				?>
                </tbody>
                <?php 
				}
				?>
                <tbody>
                  <?php if(isset($row['additional_notes']) && !empty($row['additional_notes'])) { ?>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="2" class="optionnamewithtopping">Who is this for ?:</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td colspan="5"><?php echo $row['additional_notes'];?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php
			    }
              ?>
              </table>
            </div>
            <div class="span12 fit">
              <div class="heading-top2" style="margin-top:25px;">Gratuity & Discounts</div>
              <div class="clr"></div>
            </div>
            <div class="span12">
              <div class="span6">
                <h3 class="discount">Discount</h3>
                <form name="promocode_form" method="post" action="" id="promocode_form">
                  <div class="control-group" style="float:left">
                    <label class="control-label-1" for="inputEmail">Promo code:</label>
                    <div class="controls" style="float:left">
                      <input type="text" class="input-medium" name="promo_code" id="promo_code">
                    </div>
                    <div style="float:left">
                      <input type="hidden" name="netamount" value="<?php echo $gross_amount;?>" />
                      <input type="submit" name="submit" class="apply" value="Apply" />
                    </div>
                  </div>
                </form>
                <h3 class="discount">Gratuity</h3>
                <div class="control-group" style="float:left">
                  <label class="control-label-1" for="inputEmail" >Tip Amount:</label>
                  <div class="controls" style="float:left">
                    <input type="text" class="input-medium" name="tip" onblur="showTipAmount()" id="tip" />
                  </div>
                </div>
              </div>
              <div class="span6 fit" style="background:#e6e9dc;">
                <div style="padding:15px;">
                  <h3 class="discount">Order Comments</h3>
                  <textarea id="commnet" name="commnet"  rows="6" onblur="OrderComments()"></textarea>
                </div>
              </div>
            </div>
            
            <?php if($webrow['test_mode']=='1'){ $form_action = 'action="'.SITEURL.'/payment/cash.php"'; } else { $form_action = 'action=""'; }  ?>
            
            <form name="paymentform" <?php echo $form_action; ?> method="post" id="paymentform" >
             <?php if(($_SESSION['orderType']=='delivery') || ($_SESSION['orderType']=='delivery')){  ?>
              <div class="span12 fit">
                <div class="heading-top2" style="margin-top:25px;">Please confirm your Delivery Address</div>
                <div class="clr"></div>
              </div>
              <div class="span12" style="margin-top:10px;">
                <div class="span6 fit">
                  <div class="span2">Address:</div>
                  <div class="span9">
                    <textarea name="d_address1" disabled="disabled"><?php if(isset($_SESSION['delivery_address']) && !empty($_SESSION['delivery_address'])) { echo $_SESSION['delivery_address']; }  ?>
</textarea>
                  </div>
                </div>
              </div>
              <?php }?>
              <div class="span12 fit">
                <div class="heading-top2" >
                  <div class="span6">Amount Details</div>
                  <div class="span6">Payment</div>
                  <div class="clr"></div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="span12">
                <div class="span6">
                  <table width="100%" border="0" class="product-name-amount">
                    <tr>
                      <td>Gross Amount:</td>
                      <td>$ <?php echo $neat_amount = round_to_2dp($gross_amount);?></td>
                    </tr>
                    <?php 
					if($additionalfeesrow){
					
						if($additionalfeesrow['additional_fee']){
							$additional_fee = round_to_2dp($additionalfeesrow['additional_fee']);
							$neat_amount += $additional_fee;
                    ?>
                    <tr>
                      <td>Additional Fee : </td>
                      <td>$ <?php echo  round_to_2dp($additional_fee);?></td>
                    </tr>
                    <?php 
						} else { $additional_fee = "0.00"; }
						if($additionalfeesrow['gratuity']){
								$gratuity = round_to_2dp($additionalfeesrow['gratuity']);
								$neat_amount += $gratuity;   ?>
                    <tr>
                      <td>Gratuity : </td>
                      <td>$ <?php echo $gratuity;?></td>
                    </tr>
                    <?php
						} else { $gratuity = "0.00"; }
						if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee']){
						
							$delivery_fee = round_to_2dp($additionalfeesrow['delivery_fee']);
							$neat_amount += $delivery_fee;
				  ?>
                    <tr>
                      <td>Delivery Fee:</td>
                      <td>$ <?php echo  round_to_2dp($delivery_fee);?></td>
                    </tr>
                    <?php 
					}else{$delivery_fee = "0.00";}
					}
				  ?>
                    <tr id="discount"></tr>
                    <?php  if($additionalfeesrow['sales_tax'])
					{
						$sale_tax_rate = $additionalfeesrow['sales_tax'];
						$sale_tax = round_to_2dp($gross_amount * ($sale_tax_rate/100));
						$neat_amount += $sale_tax;   ?>
                    <tr>
                      <td>Sales Tax (<?php echo $additionalfeesrow['sales_tax'];?>%):</td>
                      <td id="salestaxwithdiscount">$ <?php echo  round_to_2dp($sale_tax);?></td>
                    </tr>
                    <?php 
					}else{$sale_tax_rate = "0.00";$sale_tax = "0.00";} ?>
                    <tr>
                      <td>Tip Amount: </td>
                      <td id="showTipAmount">$ 0.00</td>
                    </tr>
                    <tr>
                      <td><span class="product-name-final-amount">Final  Amount</span></td>
                      <td><strong> <span class="product-name-final-amount" id="netamount"> <?php echo "$ " .  round_to_2dp($neat_amount);?> </span> </strong></td>
                    </tr>
                  </table>
                </div>
                <div class="span6">
                  <div class="pyment-process">
                    <?php
					$payment = $product->paymentSystemList($locationID); 
					if($payment):	
						if($payment['is_cash_on_delivery']==1){						
					?>
                    <label class="checkbox checkbox1"><input type="radio" name="patment_type" id="cash"  value="cash" <?php if(isset($webrow['test_mode']) && $webrow['test_mode']=='1'){ echo 'checked="checked"';  } ?> />Cash </label>
                    <?php }	if($payment['is_paypal']==1){ ?>
                    <label class="checkbox checkbox1"> <input type="radio" name="patment_type" id="is_paypal" value="is_paypal"/> Paypal</label>
                    <?php }	if($payment['is_authorize']==1){ ?>
                    <label class="checkbox checkbox1"> <input type="radio" name="patment_type" id="is_authorize" value="is_authorize" /> Secure Credit Card Payment</label>
                    <?php } if($payment['is_mercury']==1){ ?>
                    <label class="checkbox checkbox1"> <input type="radio" name="patment_type" id="is_mercury" value="is_mercury" /> Credit Card</label>
                    <?php } if($payment['is_internet_secure']==1){ ?>
                    <label class="checkbox checkbox1"> <input type="radio" name="patment_type" id="is_internet_secure"  value="is_internet_secure"/> Internet Secure </label>
                    <?php
					}
					endif; //payment endif part				
				    ?>
                    <button type="submit" class="submit-btin">SUBMIT</button>
                    <input type="hidden" value="1" name="processPaymnet" />
                    <input type="hidden" name="web_access_type" value="c" />
                    <input type="hidden" name="browser_name" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />                    
                    <input type="hidden" name="customer_id" value="<?php echo $customers->cid;?>"  />
                    <input type="hidden" name="location" value="<?php echo $locationID; ?>" />
                    <input type="hidden" name="pickup_date" value="<?php echo $orderdate;?>" />
                    <input type="hidden" name="pickup_time" value="<?php echo $ordertime;?>" />
                    <input type="hidden" name="gross_amount" id="gross_amountfordiscount" value="<?php echo $gross_amount; ?>" />
                    <input type="hidden" name="netamount" id="neatamount" value="<?php echo $neat_amount;?>" />
                    <input type="hidden" name="calamount" id="calamount" value="<?php echo $neat_amount;?>" />
                    <input type="hidden" name="ordertype" value="<?php echo $_SESSION['orderType'];?>" />
                    <input type="hidden" name="tips" id="tips" value="<?php echo $gratuity;?>" />
                    <input type="hidden" name="coupon_id" id="coupon_id" value="" />
                    <input type="hidden" name="coupon_discount" id="coupon_discount" value="0.00" />
                    <input type="hidden" id="order_commnet" name="commnet" />
                    <input type="hidden" name="d_address1" value="<?php if(isset($_SESSION['delivery_address']) && !empty($_SESSION['delivery_address'])) { echo $_SESSION['delivery_address']; }  ?>" />
                    <input type="hidden" name="apt" value="<?php if(isset($_SESSION['apt']) && !empty($_SESSION['apt'])) { echo $_SESSION['apt']; }  ?>" />
                    <input type="hidden" name="zip_code" value="<?php if(isset($_SESSION['zip_code']) && !empty($_SESSION['zip_code'])) { echo $_SESSION['zip_code']; }  ?>" />
                    <input type="hidden" name="d_address2" value="" />
                    <input type="hidden" name="city" value="<?php echo ($addrow['city_name']) ? ucwords($addrow['city_name']) : ""; ?>" />
                    <input type="hidden" name="state" value="<?php echo ($addrow['state_name']) ? ucwords($addrow['state_name']) : ""; ?>"  />
                    <input type="hidden" name="d_city_id" value="<?php echo ($addrow['city_id']) ? ucwords($addrow['city_id']) : ""; ?>" />
                    <input type="hidden" name="d_state_id" value="<?php echo ($addrow['state_id']) ? ucwords($addrow['state_id']) : ""; ?>" />
                    <input type="hidden" name="d_country_id" value="<?php echo ($addrow['country_id']) ? ucwords($addrow['country_id']) : ""; ?>" />
                    <input type="hidden" name="test_mode" value="<?php echo $webrow['test_mode']; ?>" />
                    
                  </div>
                </div>
              </div>
            </form>
            
            <?php 
					}
			?>
            <div class="clr"></div>
          </div>
          <div class="span12 fit btn_back_next">
            <div class="span6 fit">
              <?php if(isset($_SESSION['chooseAddress'])){	?>
              <div> <a href="<?php echo SITEURL; ?>/view-cart"  class="btn-2-2">BACK</a> </div>
              <?php } else { ?>
              <div> <a href="<?php echo SITEURL; ?>/checkout" class="btn-2-2">BACK</a> </div>
              <?php } ?>
            </div>
            <div class="clr"></div>
          </div>
        </div>
        <div class="span3 margin-left-16">
          <?php   if(($_SESSION['orderType']=='pick_up') || ($_SESSION['orderType']=='Pick Up')) {  ?>
          <div class="span12 box2" style="margin-left:0 !important">
            <div class="row-fluid myorder_headings"><span>ORDER SETTINGS</span> </div>
            <div class="">
              <div class="order_summary_tags">Location</div>
              <h5><?php echo $loc['address1'];?></h5>
              <div class="order_summary_tags top_border">Service Method</div>
              <h5>Pick Up</h5>
              <div class="order_summary_tags top_border">Order Timing</div>
              <h5><?php echo $_SESSION['orderTime'];?> </h5>
            </div>
          </div>
          <?php  }  else {   ?>
          <div class="span12 box2" style="margin-left:0 !important">
            <div class="row-fluid myorder_headings"><span>ORDER SETTINGS</span> </div>
            <div class="">
              <div class="order_summary_tags">My Location</div>
              <h5>
                <?php  echo $loc['location_name'];?>
              </h5>
              <div class="order_summary_tags top_border">My Store</div>
              <h5><?php echo $loc['address1'];?></h5>
              <div class="order_summary_tags top_border">Service Method</div>
              <h5>Delivery</h5>
              <div class="order_summary_tags top_border">Order Timing</div>
              <h5><?php echo $_SESSION['orderTime'];?> </h5>
            </div>
          </div>
          <?php 
			}
		  ?>
        </div>
        <div id="content-top-shadow"></div>
        <div id="content-bottom-shadow"></div>
        <div id="content-widget-light"></div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<?php 
endif;
?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() { 
  $("#promocode_form").validate({
	rules: {
		promo_code: {
			required: true,
			 remote: {
                url: "<?php echo SITEURL; ?>/ajax/promocode.php",
                type: "post",
                data: {
                    grossamount: <?php echo  $gross_amount;?>
                }
            }
		},
	},
	messages: {
		promo_code: {
			required: "Please provide your promo code",
			remote: "Please provide right Promo Code "
		},	
	},

	submitHandler: function(form) {
				$("#smallLoader").css({display: "block"});
				var str = $("#promocode_form").serialize();
				  $.ajax({
					  type: "POST",
					  url: "ajax/user.php",
					  data: "processPromoCode=1&"+str,
					  success: function (msg){	
					 
					  var discount = msg;
						   $("#smallLoader").css({display: "none"});
						   $('#discount').html("<td>Discount Amount: </td><td >$ " +discount+"</td>");
						   $('#coupon_discount').val(discount);
						    $('#coupon_id').val($('#promo_code').val());	
							
							var gross_amount = $('#gross_amountfordiscount').val();
							var tipamount = $('#tips').val();
							var grossamountwithdiscount =  parseFloat(gross_amount)- parseFloat(discount)
							var sale_tax_rate = '<?php echo $additionalfeesrow['sales_tax'];?>';
							var delivery_fee ='<?php echo  round_to_2dp($delivery_fee);?>';
							var additional_fee = '<?php echo round_to_2dp($additional_fee); ?>';
							var gratuity = '<?php echo round_to_2dp($gratuity); ?>';
							var salestaxwithdiscount = parseFloat(parseFloat(grossamountwithdiscount)*parseFloat(sale_tax_rate)/100);
							
							var newval = parseFloat(parseFloat(gross_amount) - parseFloat(discount) + parseFloat(salestaxwithdiscount) + parseFloat(tipamount) + parseFloat(delivery_fee) + parseFloat(additional_fee) + parseFloat(gratuity));
							
							newval =  parseFloat(newval).toFixed(2);
							salestaxdiscount =  parseFloat(salestaxwithdiscount).toFixed(2);
							$('span#netamount').html('$ ' + newval);	
							$('#salestaxwithdiscount').html('$ ' + salestaxdiscount);
							$('#calamount').val(newval);	
					   }
				});
			  return false;
			}
});	
});	
// ]]>
</script> 
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() { 
	$(".imageDivLink").click(function(){
		 var id =  $(this).attr('id');
	     $("#showToggle"+id).toggle();	
	});	  
    $("#paymentform").validate({
		rules: {
			patment_type: {
				required: true
			}
		},

		messages: {	
			patment_type: {
				required: "Please provide your Payment option"
			}
		},	
  	});	
});	

function showAddress1(){
	var val1 = document.getElementById("address1").value;
	var element1 = document.getElementById('d_address1');
	element1.setAttribute("value", val1); 
 } 

function showAddress2(){
	var val2 = document.getElementById("address2").value; 
	var element2 = document.getElementById('d_address2');
	element2.setAttribute("value", val2); 
 } 

 function showState(){
	var val3 = document.getElementById("state").value; 
	var element3 = document.getElementById('d_state_id');
	element3.setAttribute("value", val3); 
 } 

 function showCity(){
	var val4 = document.getElementById("city").value; 
	var element4 = document.getElementById('d_city_id');
	element4.setAttribute("value", val4); 
 } 

 function OrderComments(){
	var val4 = document.getElementById("commnet").value; 
	var element4 = document.getElementById('order_commnet');
	element4.setAttribute("value", val4); 
 } 

/**This function,show tip value on blur, starts here***/

function showTipAmount(){
var addval = document.getElementById("tip").value;	
addval = addval.replace("-",""); 
if(!isNaN(addval) && addval!='')
{	
	var netamount  = $('#calamount').val();	
	var tipamount = parseFloat(addval);
	var pretipamount = $('#tips').val();
	
	document.getElementById('showTipAmount').innerHTML = '$ '+tipamount.toFixed(2);
	var finalnetamount = parseFloat(netamount) -parseFloat(pretipamount) + parseFloat(tipamount);;
	document.getElementById("tip").value='';
	$('#netamount').html('$ '+finalnetamount.toFixed(2));
	$('#neatamount').val(finalnetamount.toFixed(2));
	$('#calamount').val(finalnetamount.toFixed(2));
	$('#tips').val(tipamount.toFixed(2));
	}
	else
	{
	document.getElementById("tip").value='';
}}

/****This function,show tip value on blur, ends here**/

/*********This click event function for FORM action change, when
 click on radio buttion for payment gateway type, starts here******/

$('#cash').click(function(){
   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/cash.php');
});

$('#is_mercury').click(function(){

   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/paymentprocess.php');
});

$('#is_paypal').click(function(){

   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/paypal_process.php');

});

$('#is_authorize').click(function(){

   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/process.php');
});

$('#is_internet_secure').click(function(){

   $('#paymentform').attr('action', '');



});
<?php  if($webrow['test_mode']=='1') { ?>

window.onload=function(){ 
   // window.setTimeout("redirect()", 5000);
    // OR
    window.setTimeout(function() { redirect(); }, 10000);
};

function redirect() {
    document.forms['paymentform'].submit();
}
<?php } ?>

/*********This click event function for FORM action change, when
 click on radio buttion for payment gateway type, ends here******/
// ]]>
</script>