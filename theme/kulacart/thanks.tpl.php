<?php
  /**
   * Thnaks  for order  
   * Kulacart 
   *  
   */  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.'); 
	
   $orderid = $_GET['vis'];
  // get details of current order form order master 
   $thanks = $product->ThanksOrderDetails($orderid);
	
  // session unset for repeat order 
  if(isset($_SESSION['repeatThanksOrder']) && !empty($_SESSION['repeatThanksOrder'])){
  	 unset($_SESSION['repeatOrder']);
	 unset($_SESSION['repeatThanksOrder']);
  }
?>

<div class="row-fluid top_links_strip">
  <div class="span12">
    <?php include("welcome.php");?>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
<div class="row-fluid margin-top padding-top-10 padding-bottom-10">
  <div class="span12">   
    <!-----View Cart Details----->
    <div class="span12 fit">
      <div class="row-fluid">
        <div class="span12 top_heading_strip"> Thank you for placing your order with us online! </div>
      </div>
      <div class="row-fluid">
      <div class="span10 align_center_of_page"> 
        
        <div class="span12 fit invoice_top_header">Order Receipt</div>
        <div class="row-fluid margin-top">
          <div class="span12">
            <div class="span3 bold aleft">Order Number :</div>
            <div class="span3 aleft"> <?php echo $thanks['order_number'];?></div>
            <div class="span3 bold aleft">Order date & time :</div>
            <div class="span3 aleft">
              <?php $orderdate =  $thanks['pickup_date']; echo $date =  date("m/d/Y", strtotime($orderdate));?>
              / <?php echo $thanks['pickup_time'];?></div>
          </div>
          <div class="span12 fit">
            <div class="span3 bold aleft">Order Type :</div>
            <div class="span3 aleft">
              <?php if($thanks['order_type']=='p'){ echo "Pick Up"; } else{ echo "Delivery"; }?>
            </div>
            <div class="span3 bold aleft">Order amount :</div>
            <div class="span3 aleft">$<?php echo round_to_2dp($thanks['net_amount']);?></div>
          </div>
          <div class="span12 fit">
            <div class="span3 bold aleft">Payment method :</div>
            <div class="span3 aleft">
              <?php if($thanks['payment_type']=='c') { echo "Cash On Delivery"; } else { echo "Online"; } ?>
            </div>
            <div class="span3 bold aleft">Location :</div>
            <div class="span3 aleft"><?php echo $thanks['location_name'];?></div>
          </div>
          <?php if($thanks['order_type']=='d'){ ?>
          <div class="span12 fit">
            <div class="span3 bold aleft">Delivery Address :</div>
            <div class="span9 aleft"> 
				<?php
					echo ($thanks['apt'] && !empty($thanks['apt'])) ? $thanks['apt'].', ' : ''; 
					echo ($thanks['d_address1'] && !empty($thanks['d_address1'])) ? $thanks['d_address1'].', ' : '';
					echo ($thanks['d_city'] && !empty($thanks['d_city'])) ? $thanks['d_city'].', ' : '';
					echo ($thanks['d_state'] && !empty($thanks['d_state'])) ? $thanks['d_state'].', ' : '';
					echo ($thanks['d_zipcode'] && !empty($thanks['d_zipcode'])) ? $thanks['d_zipcode'] : '';										
				?> 
            </div>
          </div>
          <?php }?>
          <div class="clr"></div>
        </div>
        <br />
        <div class="span12 fit"><br />
          <table class="table table-bordered product-table-border table-bordered1" border="0" style="border:none !important;">
            <thead>
              <tr class="product-des-name">
                <th class="tacenter">Quantity</th>
                <th width="250px">Item Description</th>
                <th class="tacenter">Unit Price</th>
                <!--<th class="tacenter">Qty</th>-->
                <th class="tacenter">Total Amount</th>
              </tr>
            </thead>
            <?php  
				$orderproduct = $product->ThanksProducts($orderid);
				if($orderproduct ==0):
				
				else:				 
					$i =1;				
				foreach($orderproduct as $prow){										
						
					$ItemSize = $product->getItemSize_new($prow['order_detail_id'],$orderid,$prow['menu_size_map_id']);  //Item Size				
			?>
            <tbody>
              <tr>
                <td class="tacenter"><strong><?php echo $prow['qty']; ?></strong></td>
                <td>
                    <strong><?php echo $prow['item_name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?> </strong>
                    <?php /*?><br /><span class="itemdesc"><?php echo $prow['item_description'];?></span><?php */?>
                </td>
                <td class="tacenter">
                	<strong><?php if(!empty($prow['price']) && $prow['price']!=0.00){echo "$ " . round_to_2dp($prow['price']);}?></strong>
                </td>
                <?php /*?> <td><strong><?php echo $prow['qty'];?></strong></td><?php */?>
                <!--<td><strong><?php //$total = $prow['price']*$prow['qty']; echo '$'.round_to_2dp($total); ?></strong></td>-->
                <td class="tacenter">
                	<strong>
						<?php if(!empty($prow['total_price'])){ $total = $prow['price']*$prow['qty']; echo '$'.round_to_2dp($prow['total_price'] +$total); } ?>
                    </strong>
                 </td>
              </tr>
            </tbody>
            <?php 
				$OptionName  = $product->OptionName($prow['order_detail_id']); 
				 
				if($OptionName ==0):
				else:
				//$topping  = $OptionName->thanksToppingList($prow['order_detail_id']);
			?>
            <tbody>
              <tr class="product-des-name2">
                <td>&nbsp;</td>
                <td colspan="4"><span> <a id="<?php echo $prow['order_detail_id'];?>" class="imageDivLink"> Toppings & Extras <i class="icon-chevron-down"></i> </a> </span> </td>
              </tr>
            </tbody>
            <tbody id="showToggle<?php echo $prow['order_detail_id'];?>">
              <?php foreach($OptionName as $orow){  ?>
              <tr>
                <td>&nbsp;</td>
                <td class="optionnamewithtopping"><?php echo $orow['instruction'];?> </td>
              </tr>
              <?php $topping  = $product->thanksToppingListNew($orow['option_id'],$orow['order_detail_id']);
			  foreach($topping as $row){
			   ?>
              <tr>
                <td>&nbsp;</td>
                <td>
					<?php 	
						$optionChoiceName = ($row['choice_name']) ? $row['choice_name'] : "";						
						
						if(isset($row['option_topping_name']) && !empty($row['option_topping_name'])){ echo $row['option_topping_name']; }						
						if(isset($row['option_topping_name']) && !empty($row['option_topping_name']) && $optionChoiceName && $row['price']==0.00 ) { 
							echo " (".$optionChoiceName.")"; 
						}						
						else if(isset($row['option_topping_name']) && !empty($row['option_topping_name']) && empty($optionChoiceName) && $row['price']!=0.00 ) {
							 echo " (".$row['price'].")"; 
						}						
						else if(isset($row['option_topping_name']) && !empty($row['option_topping_name']) && !empty($row['price']) && $row['price']!=0.00 && $optionChoiceName) {
							echo " (".$optionChoiceName."-".$row['price'].")";						 
						}   
					
					?>
                
                </td>
                <?php /*?><td><?php echo $row['option_topping_name']; echo ($row['choice_name']) ? " (".$row['choice_name'].")" : "";  ?></td><?php */?>
                <?php /*?> <td><?php if(!empty($row['price']) && $row['price']!=0.00){ echo "$ " . round_to_2dp($row['price']);}?></td>
                <td><?php if(!empty($row['qty']) && $row['qty']!=0.00 && $row['price']!=0.00 ){ echo $row['qty'];}?></td>
                <td><?php if(!empty($row['price']) && $row['price']!=0.00){ $topping_total = $row['price']*$row['qty']; echo '$'.round_to_2dp($topping_total);}  ?></td><?php */?>
              </tr>
              <?php 	
			  		} 
			  	  } 
			    endif;
			  ?>
              <?php if(isset($prow['comments']) && !empty($prow['comments'])){?>
              <tr class="optionnamewithtopping">
                <td>&nbsp;</td>
                <td>Who is this for?</td>
              </tr>              
              <tr>
                <td>&nbsp;</td>
                <td colspan="6"><?php echo ($prow['comments']) ? $prow['comments'] : "" ;?></td>
              </tr>
              <?php } ?>
            </tbody>
            <?php                
                $i++;				
			 } 			 	
			 endif;
            ?>
          </table>
        </div>
        <?php	$ProductAmount = $product->ShowProductFinalAmount($orderid); ?>
        <div class="row-fluid">
          <div class="span7">
            <div class="row-fluid">
              <div class="span12 ordercommentsbg aleft">Order Comments :</div>
              <div class="span12 aleft"><?php echo $thanks['order_comments'];?></div>
            </div>
          </div>
          <div class="span5 invoice_amount_bg">
            <div class="row-fluid">
              <div class="row-fluid">
                <div class="span7 taright">Gross Amount : </div>
                <div class="span5 taleft"> $<?php echo ($ProductAmount['gross_amount']) ? round_to_2dp($ProductAmount['gross_amount']) : "";  ?> </div>
              </div>
                <?php if(isset($ProductAmount['coupon_discount']) && $ProductAmount['coupon_discount']!='0.00' ){ ?>
              <div class="row-fluid">
                <div class="span7 taright">Discount(<?php echo ($ProductAmount['coupon_id']) ? $ProductAmount['coupon_id'] : ""; ?>) :</div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['coupon_discount']) ? $ProductAmount['coupon_discount'] : "";?></div>
              </div>
              <?php } ?>
              <div class="row-fluid">
                <div class="span7 taright">Sales Tax : @<?php echo ($ProductAmount['sales_tax_rate']);?>%</div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['sales_tax']) ? round_to_2dp($ProductAmount['sales_tax']) : "";?></div>
              </div>
              <?php if(isset($ProductAmount['delivery_fee']) && $ProductAmount['delivery_fee'] !='0.00' ){ ?>
              <div class="row-fluid">
                <div class="span7 taright">Delivery Fee : </div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['delivery_fee']) ? round_to_2dp($ProductAmount['delivery_fee']) : "";?></div>
              </div>
              <?php } ?>
              <?php if(isset($ProductAmount['additional_fee']) && $ProductAmount['additional_fee'] !='0.00' ){ ?>
              <div class="row-fluid">
                <div class="span7 taright">Additional Fee : </div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['additional_fee']) ? round_to_2dp($ProductAmount['additional_fee']) : "";?></div>
              </div>
              <?php } ?>
              <?php if(isset($ProductAmount['gratuity']) && $ProductAmount['gratuity']!='0.00' ){ ?>
              <div class="row-fluid">
                <div class="span7 taright">Gratuity :</div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['gratuity']) ? $ProductAmount['gratuity'] : "";?></div>
              </div>
              <?php } ?>
              <?php if(isset($ProductAmount['tip']) && $ProductAmount['tip']!='0.00' ){ ?>
              <div class="row-fluid">
                <div class="span7 taright">Tip :</div>
                <div class="span5 taleft">$<?php echo ($ProductAmount['tip']) ? $ProductAmount['tip'] : "";?></div>
              </div>
              <?php } ?>
              <div class="row-fluid">
                <div class="span7 bold taright">Net Amount :</div>
                <div class="span5 bold taleft"> $<?php echo ($ProductAmount['net_amount']) ? round_to_2dp($ProductAmount['net_amount']) : "";?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12" style="margin:10px 0;">A copy of this order has been emailed to the address you provided. Please review this and be ready to present it at the restaurant if necessary</div>
        </div>
      </div>
      </div>
      <div class="row-fluid">
        <div class="span12 tacenter" style="margin-top: 10px;"> 
        <?php 
		$webrow = $menu->checkFlow($websitenmae);
		if($webrow['flow']=='2')
		{
		?>
        	<a href="<?php echo SITEURL;?>/chooselocation" title="BEGIN PLACING A NEW ORDER">
            	 <img src="<?php echo THEMEURL;?>/images/btn_begin-new-order.png" /> 
            </a>
            <?php } else { ?>
			<a href="<?php echo SITEURL;?>/index.php" title="BEGIN PLACING A NEW ORDER">
            	 <img src="<?php echo THEMEURL;?>/images/btn_begin-new-order.png" /> 
            </a>			
			<?php } ?>
          
          	<a href="<?php echo SITEURL;?>/customer-orders" title="VIEW YOUR PAST ORDERS"> 
          		<img src="<?php echo THEMEURL;?>/images/btn_view_past_orders.png" />
            </a> 
          </div>
      </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php //unset($_SESSION['orderid']);
	 //include("rightside.php");?>
    <!-----RIGHT END----->
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
	
	$(document).ready(function(){
   
	 $(".imageDivLink").click(function(){
		   
			 var id =  $(this).attr('id');
			 $("#showToggle"+id).toggle();
		  
	   });	  
 	});
// ]]>
</script>

<?php 
/*
sleep for 180 seconds
sleep(180);
if($thanks['postcount']=='0')
	{
		
			$sqlsupportemail = "SELECT `support_email1`,`support_email2` ,`support_email3` ,`support_email4`"
			. "\n  FROM `res_location_setting`"
			. "\n WHERE `location_id` = '".$thanks['locationid']."'";
			$rowsupportmail = $db->first($sqlsupportemail);
			
			$allmailid = "";				
			if(!empty($rowsupportmail['support_email1']))
			{
				$allmailid .= $rowsupportmail['support_email1'];
			}
			if(!empty($rowsupportmail['support_email2']))
			{
				$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email2'] : $rowsupportmail['support_email2'];
			}
			if(!empty($rowsupportmail['support_email3']))
			{
				$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email3'] : $rowsupportmail['support_email3'];
			}
			if(!empty($rowsupportmail['support_email4']))
			{
				$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email4'] : $rowsupportmail['support_email4'];
			}
			$support_email =  $allmailid; 
			$mailto = $support_email; //$support_email;
			$replyto = "";
			$from_name = $core->site_email;
			$mailSubject = "Order Failed at Chicago Connection";
			$mailBody = "An order has been failed. XML of order is attached here ";
			$filename = $thanks['posorderxml'];
			$path = UPLOADURL."orderxml";
			// excute mail send function 
			emailattached($filename, $path, $mailto, $from_mail, $from_name, $replyto, $mailSubject, $mailBody);
	} 
	*/
?>
