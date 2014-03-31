<?php include("header.php"); ?>
<?php 
  if (!$customers->customerlogged_in)
      redirect_to("login.php"); 
	  
	$location = $menu->locationListByMenu($websitenmae);
	
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
	$additionalfeesrow = $product->additionalAmmount($locationID);
?>

<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
<div data-role="content">
  <h1 class="main-heading">Place your Order</h1>
  <!-- /header -->
  <!-- /content -->
  <?php 	
		$allproductrow = $product->AllProductInBasket(); //print_r($allproductrow); exit();	
		if($allproductrow){
    ?>
    <div class="ui-grid-c your-shopping-cart">
        <div class="ui-block-a width10"><div class="ui-bar ui-bar-e your-shopping-cart">Qty</div></div>
        <div class="ui-block-b width40"><div class="ui-bar ui-bar-e your-shopping-cart">Item Name</div></div>
        <div class="ui-block-c"><div class="ui-bar ui-bar-e your-shopping-cart">Unit Price($)</div></div>
        <div class="ui-block-d"><div class="ui-bar ui-bar-e your-shopping-cart">Total($)</div></div>
    </div>
    <!-- /grid-c -->
    <?php	  
		  $gross_amount = "";	
		  foreach($allproductrow as $row){
		  	$ItemSize = $product->getItemSize($row['menu_size_map_id']);  //size name if topping is sizzed
		  	$gross_amount += $row['total_price'];
	?>
    <div class="ui-grid-c">
        <div class="ui-block-a width10"><div class="ui-bar ui-bar-e bold"><?php echo $row['quantity'];?></div></div>
        <div class="ui-block-b width40"><div class="ui-bar ui-bar-e"><?php echo $row['name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?></div>
        </div>
        <div class="ui-block-c"><div class="ui-bar ui-bar-e"><?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "" . $row['unit_price'];}?></div></div>
        <div class="ui-block-d"><div class="ui-bar ui-bar-e bold"><?php echo "" . $row['total_price'];?></div></div>
 	 </div>  
     <?php 
	 if($row['topping_details']){ ?>
        <div data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
          <h4 class="toppings-extras"> Toppings & Extras</h4>
          <ul data-role="listview" data-inset="false">
            <?php 
                $toppingdetails = $row['topping_details'];
                $toppingarray = explode("%%", $toppingdetails);	
                for($ct = 0; $ct < count($toppingarray); $ct++){					

                $toppingrow = $toppingarray[$ct];
                $toppingrow = explode("||", $toppingrow);
                $optionChoiceName = $product->showOptinChoicName($toppingrow[5]);  //option choice name	
             $nexttopping = $toppingarray[$ct+1];
             $nexttoppingrow = explode("||", $nexttopping);				
             if($ct<count($toppingarray))
             {
                 if($nexttoppingrow[3]==$toppingrow[3] && $match == 0)
                 {
                    echo '<li class="optiontitle viwcartli">'.$product->geroptiontoppingname($toppingrow[3]).'</li>';
                    $match=1;
                 }
                 else if($ct==0)
                 {
                    echo '<li  class="optiontitle viwcartli">'.$product->geroptiontoppingname($toppingrow[3]).'</li>';
                    }else if($nexttoppingrow[3]!=$toppingrow[3])
                    {
                        if($match==0)
                        {
                            echo '<li class="optiontitle viwcartli">'.$product->geroptiontoppingname($toppingrow[3]).'</li>';
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
                        echo '<li class="optiontitle viwcartli">'.$product->geroptiontoppingname($toppingrow[3]).'</li>';
                    }	 
            }

                /********This is only for, if topping price is not there, then hide topping price and quantity and toal topping price div, starts here**/

                if(!empty($toppingrow[1]) && $toppingrow[1]==0.00){ 
                    $hidechoicestyle = 'width-1-2-1'; $hidePrice = 'boxnone';
                }

                else { 
                    $hidechoicestyle = ''; $hidePrice = ''; 
                }

                /********This is only for, if topping price is not there, then hide topping price and quantity and toal topping price div, ends here**/					

            ?>

            <li class="viwcartli">
             <div class="ui-grid-a" <?php echo $hidechoicestyle; ?>>                        
                <span class="textsize14">
              <?php 
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
                ?> 
                
                </span>  
         </div>  
            </li>

            <?php 

            }   ?>
          </ul>
        </div>

            <?php } if(isset($row['additional_notes']) && !empty($row['additional_notes']) ){  	?>	
			<div class="ui-grid-solo">
              <div class="ui-block-a">
                <div class="ui-bar ui-bar-e bold cart_option_heading">Who is this for ?</div>
              </div>
            </div>
             <div class="ui-grid-solo">
              <div class="ui-block-a">
                <div class="ui-bar ui-bar-e"><?php echo ($row['additional_notes']) ? $row['additional_notes'] : "" ;?></div>
              </div>
            </div>
             <?php   } ?>
            <hr />
      <?php	} } ?>
  <form name="promocode_form" method="post" action="" id="promocode_form" data-ajax="false">
    <div data-role="fieldcontain">
      <input type="text" class="input-medium" name="promo_code" id="promo_code" placeholder="Enter Coupon Code">
    </div>
    <div>
      <input type="hidden" name="netamount" value="<?php echo $gross_amount;?>" />
      <input type="submit" value="Apply Coupon" />
    </div>
    <div data-role="fieldcontain">
      <input type="text" class="input-medium" name="tip" onblur="showTipAmount()" id="tip" placeholder="Gratuity" />
    </div>
  </form>

  <form name="paymentform" action="" method="post" id="paymentform" data-ajax="false">
    <div data-role="fieldcontain">
      <textarea id="commnet" name="commnet"  rows="6" onblur="OrderComments()" placeholder="Enter order comments"></textarea>
    </div>

    <?php if(isset($_SESSION['orderType']) && $_SESSION['orderType']=='delivery'){  ?>
    <div class="ui-grid-a your-shopping-cart">
        <div class="ui-block-a">
          <div class="ui-bar ui-bar-e your-shopping-cart">Delivery Details</div>
        </div>
        <div class="ui-block-b">
          <div class="ui-bar ui-bar-e your-shopping-cart"></div>
        </div>     
	</div> 
    <?php if(isset($_SESSION['delivery_address']) && !empty($_SESSION['delivery_address'])){ ?>
    <div class="ui-grid-a">
        <div class="ui-block-a">
          <div class="ui-bar ui-bar-e">Address:</div>
        </div>
        <div class="ui-block-b">
          <div class="ui-bar ui-bar-e">
		  	<?php if(isset($_SESSION['delivery_address']) && !empty($_SESSION['delivery_address'])) { echo $_SESSION['delivery_address']; }  ?> 
          </div>
        </div> 
    </div>  
    <?php 	
	    }	
	  }
	?>
    <div class="ui-grid-a table-1-a">
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Gross Amount: </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo $neat_amount = round_to_2dp($gross_amount);?></div>
      </div>
       <div id="discount" style="width:100%;"></div>
      <?php 
		if($additionalfeesrow){
			if($additionalfeesrow['sales_tax']){
			$sale_tax_rate = $additionalfeesrow['sales_tax'];
			$sale_tax = round_to_2dp($gross_amount * ($sale_tax_rate/100));
			$neat_amount += $sale_tax;
	  ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Sales Tax (<?php echo $additionalfeesrow['sales_tax'];?>%): </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" id="salestaxwithdiscount" >$ <?php echo  round_to_2dp($sale_tax);?></div>
      </div>
      <?php 
		}
		else{	$sale_tax_rate = "0.00"; $sale_tax = "0.00";	}	
		if($additionalfeesrow['additional_fee']){	
			$additional_fee = round_to_2dp($additionalfeesrow['additional_fee']);
			$neat_amount += $additional_fee;
	  ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Additional Fee : </div></div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo  round_to_2dp($additional_fee);?></div>
      </div>
      <?php 
		}else{ $additional_fee = "0.00"; }	
		if($additionalfeesrow['gratuity']){
			$gratuity = round_to_2dp($additionalfeesrow['gratuity']);
			$neat_amount += $gratuity;	  ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Gratuity : </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo $gratuity;?></div>
      </div>
      <?php
		}else{ $gratuity = "0.00"; }	

		if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee']){		

			$delivery_fee = round_to_2dp($additionalfeesrow['delivery_fee']);
			$neat_amount += $delivery_fee;
	  ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Delivery Fee: </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo  round_to_2dp($delivery_fee);?></div>
      </div>
      <?php 
			}else{	$delivery_fee = "0.00";	}
		}
	  ?>
     
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Tip Amount: </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" id="showTipAmount" >$ 0.00</div>
      </div>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Final  Amount: </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b"><span class="product-name-final-amount" id="netamount"> <?php echo "$ " .  round_to_2dp($neat_amount);?> </span></div>
      </div>
    </div>
    <div class="span6">
      <div class="pyment-process">
        <?php
		$payment = $product->paymentSystemList($locationID); 
		if($payment):
		if($payment['is_cash_on_delivery']==1)
		{
		?>
        <label class="checkbox checkbox1"><input type="radio" name="patment_type" id="cash" value="cash" /> Cash </label>
        <?php 
		}
		if($payment['is_paypal']==1)
		{
		?>
        <label class="checkbox checkbox1"> <input type="radio" name="patment_type" id="is_paypal" value="is_paypal"/>Paypal</label>
        <?php 		
		}
		if($payment['is_authorize']==1)
		{
		?>
        <label class="checkbox checkbox1"><input type="radio" name="patment_type" id="is_authorize" value="is_authorize" />Secure Credit Card Payment </label>
        <?php 
		}
		if($payment['is_mercury']==1)
		{
		?>
        <label class="checkbox checkbox1"><input type="radio" name="patment_type" id="is_mercury" value="is_mercury" />Credit Card </label>
        <?php 
		}
		if($payment['is_internet_secure']==1)
		{
		?>
        <label class="checkbox checkbox1">
        <input type="radio" name="patment_type" id="is_internet_secure"  value="is_internet_secure"/>Internet Secure </label>

        <?php
		}
		endif;
		?>

        <!--orderdate and time lessthen current time -->
        <?php 
		$orderdate =  $_SESSION['orderDate'];
		$ordertime  = $_SESSION['orderHour'];
		 ?>

        <button type="submit" class="submit-btin">Submit Your Order</button>

        <input type="hidden" value="1" name="processPaymnet" />
        <input type="hidden" name="customer_id" value="<?php echo $customers->cid;?>"  />
        <input type="hidden" name="web_access_type" value="m" />
        <input type="hidden" name="browser_name" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
        <input type="hidden" name="location" value="<?php echo $locationID; ?>" />
        <input type="hidden" name="pickup_date" value="<?php echo $orderdate;?>" />
        <input type="hidden" name="pickup_time" value="<?php echo $ordertime;?>" />
        <input type="hidden" name="gross_amount" id="gross_amountfordiscount" value="<?php echo $gross_amount; ?>" />
        <input type="hidden" name="netamount" id="neatamount" value="<?php echo $neat_amount;?>" />
        <input type="hidden" name="calamount" id="calamount" value="<?php echo $neat_amount;?>" />
        <input type="hidden" name="neatamount_wot" id="neatamount_wot" value="<?php echo $neat_amount;?>" />
        <input type="hidden" name="sales_tax_rate" value="<?php echo $sale_tax_rate;?>"  />
        <input type="hidden" name="sales_tax" value="<?php echo $sale_tax;?>"  />
        <input type="hidden" name="additional_fee" value="<?php echo $additional_fee;?>" />
        <input type="hidden" name="gratuity" value="<?php echo $gratuity;?>" />
        <input type="hidden" name="tips" id="tips" value="<?php echo $gratuity;?>" />
        <input type="hidden" name="delivery_fee" value="<?php echo $delivery_fee;?>" />
        <input type="hidden" name="ordertype" value="<?php echo $_SESSION['orderType'];?>" />
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
      </div>

    </div>

  </form>

</div>
<a href="restaurantmenu.php" data-ajax="false" data-role="button">Back to Menu</a>
<a href="index.php" data-ajax="false" data-role="button" data-theme="b">Back to Home</a>
<!-- /content -->
</div>
<?php include("footer.php");?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() { 
  $("#promocode_form").validate({
	rules: {
			required: true,
			 remote: {
                url: "<?php echo SITEURL; ?>/ajax/promocode.php",
                type: "post",
                data: {
                    grossamount: <?php echo  $gross_amount;?>
                }
            }
		},
	messages: {
		promo_code: {
			required: "Please provide your promo code",
			remote: "Please provide correct promo code "
		}
	},
	submitHandler: function(form) {
				
				 $.mobile.loading("show");				
				  var str = $("#promocode_form").serialize();
				  				  
				  $.ajax({
					  type: "POST",
					  url: "ajax/user.php",
					  data: "processPromoCode=1&"+str,
					  success: function (msg){	
					  
						    $.mobile.loading("hide");
							
						    $('#discount').html("<div class=\"ui-block-a\"><div class=\"ui-bar ui-bar-e table-1-a-b\" >Discount:</div></div><div class=\"ui-block-c\"><div class=\"ui-bar ui-bar-e table-1-a-b\">$ "+ msg +"</div></div>");
						    $('#coupon_discount').val(msg);
						    $('#coupon_id').val($('#promo_code').val());
							
							var gross_amount = $('#gross_amountfordiscount').val();
							
							var tipamount = $('#tips').val();
							var grossamountwithdiscount =  parseFloat(gross_amount)- parseFloat(msg)
							
							var sale_tax_rate = '<?php echo $additionalfeesrow['sales_tax'];?>';
							var delivery_fee ='<?php echo  round_to_2dp($delivery_fee);?>';
							var additional_fee = '<?php echo round_to_2dp($additional_fee); ?>';
							var gratuity = '<?php echo round_to_2dp($gratuity); ?>';
							var salestaxwithdiscount = parseFloat(parseFloat(grossamountwithdiscount)*parseFloat(sale_tax_rate)/100);
							
							var newval = parseFloat(parseFloat(gross_amount) - parseFloat(msg) + parseFloat(salestaxwithdiscount) + parseFloat(tipamount) + parseFloat(delivery_fee) + parseFloat(additional_fee) + parseFloat(gratuity));
							
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
		}
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
	
	   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/paypal_process.php');
	
	});
	
	$('#is_authorize').click(function(){
	
	   $('#paymentform').attr('action', '<?php echo SITEURL;?>/payment/process.php');
	
	});

	$('#is_internet_secure').click(function(){
	   $('#paymentform').attr('action', 'page5');
	
	});
/*********This click event function for FORM action change, when
 click on radio buttion for payment gateway type, ends here******/

// ]]>
</script>