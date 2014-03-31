<?php
/**
* Checkout 
* Kula cart 
*  
*/  
if (!defined("_VALID_PHP"))
	die('Direct access to this location is not allowed.');
	
$location = $menu->locationListByMenu($websitenmae);
if (isset($_POST['processCheckout']) || isset($_SESSION['chooseAddress'])): 

	/*if (intval($_POST['processCheckout']) == 0 || empty($_POST['processCheckout'])): 
	redirect_to("index.php?do=menus");
	endif;*/
	
	$hour = (isset($_POST['hour'])) ? $_POST['hour'] : 0;
	
	if(isset($_SESSION['chooseAddress']))
	{
		$locationID = $_SESSION['chooseAddress'];
	}
	else
	{
		if(!empty($_POST['changeaddress']))
		{
			$locationID = $_POST['changeaddress'];
		}
		else
		{
			$locationID = $_POST['selected_delivery_address'];
		} 
	}
	$loc =  $product->locationName($locationID);
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>

<div class="row-fluid margin-top">
    <div class="span12" id="invoicedetails" style="display:none"></div>
	<div class="span12" id="checkoutprocess">
    	<div class="span9  fit">
        	<div class="span12 box-shadow">
                <div class="span12">
                    <div class="span12 fit">
                    	<div class="heading-top">Confirm your order</div>
                    </div>
                    <div class="item-listing-div">
                        <div class="row-fluid product-item">
                            <div class="span4" style="margin:0;">Item Name </div>
                            <div class="span4">Item Description </div>
                            <div class="span1">Price</div>
                            <div class="span1">Qty</div>
                            <div class="span1">Amount </div>
                        </div>
                    </div>
					<?php 
                    $inbasket  = $product->MyShoppingBasket();
                    if($inbasket): 
						foreach ($inbasket as $rows): 
                      	$prow = $product->viewcartdetails($rows['productID']);
                    ?>
          			<div class="separator2"></div>
          			<div class="row-fluid">
                        <div class="span4 product-neame-det-listing" style="margin:0;">
                        	<div class="left-p"><?php echo $prow['item_name']; ?></div>
                        </div>
                        <div class="span4 product-neame-det-listing"><?php echo $prow['item_description'];?></div>
                        <div class="span1 product-neame-det-listing"><?php echo $rows['productPrice'];?></div>
                        <div class="span1 product-neame-det-listing"><?php echo $rows['qty'];?></div>
                        <div class="span1 product-neame-det-listing"><?php echo round_to_2dp($rows['totalprice']);?></div>
						<?php 
                        $topping  = $product->viewCartTopping($rows['basketID']); 
                        if($topping)
                        {
                        ?>
            			<div class="row-fluid">
              				<div class="span12">
                                <div class="row-fluid">
                                	<div class="span12">
                                    	<a id="<?php echo $rows['basketID'];?>" class="imageDivLink">
                                        Hide Topping Details<i class="icon-chevron-down"></i>
                                        </a>
                                    </div>
                                </div>
                				<div class="row-fluid test" id="showToggle<?php echo $rows['basketID'];?>" style="display: block;">
                  					<div class="span12">
                    					<div class="span8">
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="span2"></div>
                                                    <div class="span5 bold">Topping Name </div>
                                                    <div class="span5 bold">Price </div>
                                                </div>
                                            </div>
											<?php 
                                            $totalprice = "";
                                            foreach ($topping as $trow): 
                                            //  $topping = $product->cartToppinglist($trow['topping_id']);
                                            ?>
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <div class="span2"></div>
                                                    <div class="span5"><?php echo $trow['topping_name'];?></div>
                                                    <?php $toppingPrice = $menu->getToppingPrice($trow['option_topping_id']); 			  
                                                    //if($toppingPrice):	
                                                    //foreach($toppingPrice as $toprow):?>
                                                    <div class="span5">
                                                    <?php if($toppingPrice['price']) {  echo "$".$toppingPrice['price']."X".$rows['qty']; } else { echo "$00.00 X".$rows['qty']; } $topingtoalamount = $toppingPrice['price']*$rows['qty'];  echo " = ".$topingtoalamount;?>
                                                    </div>
                                                    <?php $totalprice += $topingtoalamount; ?>
                                                    <?php //endforeach; endif;?>
                                                </div>
                                            </div>
											<?php 
                                            endforeach;
                                            ?>
                    					</div>
                    					<div class="span4 bold">
                      						<div class="separator3"></div>
                      						$<?php echo  round_to_2dp($totalprice);?>
                                        </div>
                    					<div class="clr"></div>
                  					</div>
                				</div>
              				</div>
            			</div>
						<?php 
                        } 
                        ?>
          			</div>
					<?php 
                    	endforeach; 
                    endif;
                    ?>
          			<div class="span12 fit">
            			<div class="heading-top" style="margin-top:25px;">Gratuity & Discounts</div>
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
                                    <input type="hidden" name="netamount" value="<?php echo $_POST['net_ammount'];?>" />
                                    <input type="submit" name="submit" class="apply" value="Apply" />
                                </div>
                            </div>
                            </form>
              				<h3 class="discount">Tip to server</h3>
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
          			<form name="paymentform" action="" method="post" id="paymentform">
            			<div class="span12 fit">
              				<div class="heading-top" >
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
                                        <td>$<?php echo round_to_2dp($_POST['totalamount']);?></td>
                                    </tr>
                                    <tr>
                                        <td>Sales Tax:(6%) </td>
                                        <td>$<?php echo round_to_2dp($_POST['sales_tax']);?></td>
                                    </tr>
                                    <tr>
                                        <td>Discount Amount: </td>
                                        <td  id="discount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tip Amount: </td>
                                        <td id="showTipAmount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td><span class="product-name-final-amount">Final  Amount</span></td>
                                        <td>
                                        	<span class="product-name-final-amount" id="netamount">
											<?php echo $_POST['totalamount']+ $_POST['sales_tax'];?>
                                            </span>
                                    	</td>
                                    </tr>
                                </table>
              				</div>
              				<div class="span6">
                				<div class="pyment-process">
									<?php
                                    $payment = $product->paymentSystemList($locationID); 
                                    if($payment):			
                                    if($payment['is_cash_on_delivery']==1)
                                    {
                                    ?>
                                    <label class="checkbox">
                                    	<input type="radio" name="patment_type" id="cash"  value="cash" />Cash 
                                    </label>
									<?php 
                                    }
                                    if($payment['is_paypal']==1)
                                    {
                                    ?>
                  					Paypal :<input type="radio" name="patment_type" id="is_paypal" value="is_paypal"/>
									<?php 
                                    }
                                    if($payment['is_authorize']==1)
                                    {
                                    ?>
                                    <label class="checkbox">
                                    	<input type="radio" name="patment_type" id="is_authorize" value="is_authorize" />First Data:
                                    </label>
									<?php 
									}
                                    if($payment['is_mercury']==1)
                                    {
									?>
                                    <label class="checkbox">
                                        <input type="radio" name="patment_type" id="is_mercury" value="is_mercury" />
                                        <input type="hidden" name="merchantid" value="<?php echo $payment['merchant_id'];?>" />
                                        <input type="hidden" name="merchant_password" value="<?php echo $payment['merchant_password'];?>" />Credit Card
                                    </label>
									<?php 
                                    }
                                    if($payment['is_internet_secure']==1)
                                    {
                                    ?>
                  					<label class="checkbox">
                  						<input type="radio" name="patment_type" id="is_internet_secure"  value="is_internet_secure"/>Internet Secure 
                                    </label>
                  					<?php
									}
									endif;
									?>
                                    <button type="submit" class="submit-btin">SUBMIT</button>
                                    <input type="hidden" value="1" name="processPaymnet" />
                                    <input type="hidden" name="customer_id" value="<?php echo $customers->cid;?>"  />
                                    <input type="hidden" name="location" value="<?php echo $locationID; ?>" />
                                    <input type="hidden" name="pickup_date" value="<?php echo $_POST['pickupdate_time'];?>" />
                                    <input type="hidden" name="pickup_time" value="<?php echo $hour;?>" />
                                    <input type="hidden" name="gross_amount" value="<?php echo $_POST['totalamount']; ?>" />
                                    <input type="hidden" name="netamount" value="<?php echo $_POST['net_ammount'];?>" />
                                    <input type="hidden" name="sales_tax_rate" value="<?php echo $_POST['sales_tax_rate'];?>"  />
                                    <input type="hidden" name="sales_tax" value="<?php echo $_POST['sales_tax'];?>"  />
                                    <input type="hidden" name="additional_fee" value="<?php echo $_POST['additional_fee'];?>" />
                                    <input type="hidden" name="delivery_fee" value="<?php echo $loc['delivery_fee'];?>" />
                                    <input type="hidden" name="gratuity" value="<?php echo $_POST['gratuity'];?>" />
                                    <input type="hidden" name="ordertype" value="<?php echo $_POST['ordertype'];?>" />
                                    <input type="hidden" name="d_address1" id="d_address1" value="" />
                                    <input type="hidden" name="d_address2" id="d_address2" value="" />
                                    <input type="hidden" name="d_state_id" id="d_state_id" value="" />
                                    <input type="hidden" name="d_city_id" id="d_city_id" value="" />
                                    <input type="hidden" name="coupon_id" id="coupon_id" value="" />
                                    <input type="hidden" name="coupon_discount" id="coupon_discount" value="" />
                                    <!-- <input name="submit" class="btn" value="Place To Order" type="submit" /> -->
                                    <input type="hidden" id="order_commnet" name="commnet"     />
                                    <!--<button type="submit" class="submit-btin1">Save For Later</button>-->
                				</div>
              				</div>
            			</div>
          			</form>
          			<div class="clr"></div>
          			<div class="span12 fit">
            			<div class="heading-top" style="height:21px;" ></div>
          			</div>
        		</div>
        		<div class="clr"></div>
      		</div>
      		<div class="span12 fit btn_back_next">
        		<div class="span6 fit">
                <?php 
				if(isset($_SESSION['chooseAddress']))
				{
				?>
         	 		<div><a href="<?php echo SITEURL; ?>/view-cart"><img src="<?php echo THEMEURL;?>/images/btn_back.png" alt="Back"/></a></div>
                <?php 
				}
				else
				{
				?>
         	 		<div><a href="<?php echo SITEURL; ?>/checkout"><img src="<?php echo THEMEURL;?>/images/btn_back.png" alt="Back"/></a></div>
                <?php 
				}
				?>
        		</div>
        		<div class="clr"></div>
      		</div>
    	</div>
		<div class="span3">
			<?php 
            if(($_POST['ordertype']=='pick_up') || ($_POST['ordertype']=='Pick Up'))
            {
            ?>
            <div class="span12 order-summery fit">
                <h3>ORDER SUMMARY:</h3>
                <h2><?php  echo $loc['location_name'];?></h2>
                <h5>Location :</h5>
                <p><?php echo $loc['address1'];?></p>
                <h5>Order Type :</h5>
                <p>Pick Up</p>
                <h5>Expected Order Time :</h5>
                <p><?php echo ($hour) ? $hour : "";?>&nbsp;<?php echo $_POST['pickupdate_time'];?> </p>
            </div>
			<?php 
            }
            else
            {
            ?>
      		<div class="span12 order-summery fit">
                <h3>ORDER SUMMARY:</h3>
                <?php 
                $location =  $product->LocationDetailsByDefoult($_POST['selected_delivery_address']);
                ?>
                <h2><?php  echo $location['location_name'];?></h2>
                <h5>Location :</h5>
                <p><?php  echo $location['address1'];?></p>
                <h5>Order Type :</h5>
                <p>Delivery Area</p>
                <h5>Expected Order Time :</h5>
                <p><?php echo ($hour) ? $hour : "";?>&nbsp;<?php echo $_POST['deliverydate_time'];?> </p>
      		</div>
      	<?php 
		}
		?>
		</div>
		<div class="clr"></div>
	</div>
	<div class="clr"></div>
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
			remote:{url:"ajax/promocode.php", type:"post"}
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
						   $("#smallLoader").css({display: "none"});
						   $('#discount').html("$"+msg);
						   $('#coupon_discount').val(msg);
						    $('#coupon_id').val($('#promo_code').val());
													   
						   var totalamount = <?php echo $_POST['totalamount'];?>,
						   		additionalfee = <?php echo $_POST['additional_fee'];?>,
								gratuity     = <?php echo $_POST['gratuity'];?>,
								salestax = <?php echo $_POST['sales_tax'];?>,
								discount = msg;
														   
						   var netammount = parseFloat(totalamount)+parseFloat(additionalfee)+parseFloat(gratuity)+parseFloat(salestax)-parseFloat(discount);
						   $('#netamount').html('$'+netammount.toFixed(2));					   
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
  $("#paymentform").validate({
	rules: {
		patment_type: {
			required: true
		},
	},
	messages: {
		patment_type: {
			required: "Please provide your Payment option"
		},
		
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


/*********This function,show tip value on blur, starts here********/
function showTipAmount(){
var val = document.getElementById("tip").value;	
if(!isNaN(val) && val!='')
{
	var netamount  = $('#netamount').text();
	var amount  = netamount.replace("$",""); 
	
	document.getElementById('showTipAmount').innerHTML = '$'+val;
	var finalnetamount = parseFloat(amount)+parseFloat(val);
	document.getElementById("tip").value='';
	$('#netamount').html('$'+finalnetamount.toFixed(2));	
	}
	else
	{
	document.getElementById("tip").value='';
}}
/*********This function,show tip value on blur, ends here********/

/*********This click event function for FORM action change, when
 click on radio buttion for payment gateway type, starts here******/

$('#cash').click(function(){
   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/cash.php');
});


$('#is_mercury').click(function(){
   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/paymentprocess.php');
});

$('#is_paypal').click(function(){
   $('#paymentform').attr('action', 'page3');
});


$('#is_authorize').click(function(){
   $('#paymentform').attr('action', 'page4');
});


$('#is_internet_secure').click(function(){
   $('#paymentform').attr('action', 'page5');
});
/*********This click event function for FORM action change, when
 click on radio buttion for payment gateway type, ends here******/
// ]]>
</script>
