<?php
  /**
   * Users
   *
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Order")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php 
	switch($core->action): case "view":
	
	if(isset($_POST['order_status']) && !empty($_POST['order_status'])):
	
	     if($_POST['order_status']=='p'){  $data['order_status'] = "p"; }   //Pending 
	     if($_POST['order_status']=='c'){  $data['order_status'] = "c"; }   //Cancelled
		 if($_POST['order_status']=='f'){  $data['order_status'] = "f"; }   //Complete
	     if($_POST['order_status']=='d'){  $data['order_status'] = "d"; }   //Denied
		 if($_POST['order_status']=='b'){  $data['order_status'] = "f"; }   //Processing 
	     if($_POST['order_status']=='r'){  $data['order_status'] = "d"; }   //Refund
		 if($_POST['order_status']=='g'){  $data['order_status'] = "g"; }   //Delete
		 
		 $db->update("res_order_master", $data, "orderid='" . $content->postid  . "'");	
	endif;
	
 	$row = $content->getproductOrder($content->postid);
?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" />View Order Details > <?php echo ucwords($row['first_name'].' '.$row['last_name']); ?></h1>
  <h1 style="float:right;margin-right: 6px; margin-top: -56px;"><a href="<?php echo SITEURL; ?>/admin/index.php?do=order">Back</a></h1>
  <div class="divider"><span></span></div>
</div>
<div class="block-content">
  <table width="100%" border="0"  class="forms">
  <thead>
  <tr>
    <th colspan="2" align="center" style="background:#CCCCCC"><b>Order History</b></th>
    <th colspan="2" align="center" style="background:#CCCCCC"><b>Customer Details</b></th>
  </tr>  
  <tr>
    <th align="left">Order No</th>
    <td><?php echo ($row['order_number']) ? $row['order_number'] : "";?></td> 
     <th align="left">Customer Name</th>
    <td><?php echo ucwords($row['first_name'].' '.$row['last_name']); ?></td> 
   </tr>
   <tr> 
   		<th align="left">Order Date</th>
        <td>		
        	<?php $orderdate =  $row['pickup_date']; echo $date =  date("m/d/Y", strtotime($row['pickup_date']));?> / <?php echo $row['pickup_time'];?>
        </td>
        <th align="left">E-Mail ID</th>
        <td align="left"><?php echo ($row['email_id'])? $row['email_id'] : ""; ?></td>
        <!--<th>Phone No</th>
        <td><?php echo ($row['phone_number'])? $row['phone_number'] : ""; ?></td>-->
    </tr>
    <tr>
        <th align="left">Order Type</th>
        <td>
            <?php 
                if(isset($row['order_type']) && $row['order_type']=='p'){ echo 'Pick Up';} 
                if(isset($row['order_type']) && $row['order_type']=='d'){ echo 'Delivery';} 
                if(isset($row['order_type']) && $row['order_type']=='dl'){ echo 'Dine In';}
            ?>
        </td>
        <th align="left">Address</th>
        <td><?php  echo ($row['apt'] && !empty($row['apt'])) ? $row['apt'].', ' : "";  echo ($row['caddress1'])? $row['caddress1'] : ""; ?></td> 
    </tr>
    <tr>
        <th align="left">Payment Method</th>
        <td>
			<?php 
				if(isset($row['payment_type']) && $row['payment_type']=='c'){ echo 'Cash';}  
				if(isset($row['payment_type']) && $row['payment_type']=='o'){ echo 'Online';}  
			?>
        </td>
        <th align="left">City</th>
        <td><?php echo ($row['customer_city'])? $row['customer_city'] : ""; ?></td>
    </tr>
    <tr>
       <th align="left">Net Amount</th>
        <td><?php echo ($row['net_amount'])? '$ '. $row['net_amount'] : ""; ?></td>
        <th align="left">State</th>
        <td><?php echo ($row['customer_state'])? $row['customer_state'] : ""; ?></td>
    </tr>
    <tr>
       <th align="left">Order Status</th>
        <td>        	
            <form action="" method="post">
              <select name="order_status" class="custombox" style="width:147px">
                <option value="">Select Order Status</option>
                <option value="p" <?php if(isset($row['order_status']) && $row['order_status']=='p'){ echo 'selected="selected"'; } ?>>Pending</option>
                <option value="c" <?php if(isset($row['order_status']) && $row['order_status']=='c'){ echo 'selected="selected"'; } ?>>Cancelled</option>
                <option value="f" <?php if(isset($row['order_status']) && $row['order_status']=='f'){ echo 'selected="selected"'; } ?>>Complete</option>
                <option value="d" <?php if(isset($row['order_status']) && $row['order_status']=='d'){ echo 'selected="selected"'; }  ?>>Denied</option>
                <option value="b" <?php if(isset($row['order_status']) && $row['order_status']=='b'){ echo 'selected="selected"'; } ?>>Processing</option>
                <option value="r" <?php if(isset($row['order_status']) && $row['order_status']=='r'){ echo 'selected="selected"'; } ?>>Refunded</option>
                <option value="g" <?php if(isset($row['order_status']) && $row['order_status']=='g'){ echo 'selected="selected"'; }  ?>>Delete</option>
              </select>
              <input type="submit" value="Update Status" name="dosubmit" class="button" />
            </form>
        </td> 
        <th align="left"><?php if(isset($row['customer_country']) && !empty($row['customer_country'])){ echo "Country";  } ?></th>
        <td><?php echo ($row['customer_country'])? $row['customer_country'] : ""; ?></td>   
  </tr>
  </thead>
  <?php if(isset($row['order_type']) && $row['order_type']=='d'){  ?>
  <tbody<?php echo 'style="display:block"'; ?> >
    <tr><td colspan="4"></td></tr>
  	<tr>
    	<th colspan="2" style="text-align:center"><b>Delivery Details</b></th><th style="text-align:center" colspan="2" align="center"><b>Delivery Status</b></th>
    </tr>
    <tr>
    	<th>Address</th><td><?php echo ($row['d_address1'])? $row['d_address1'] : ''; ?></td>
        <th align="left">Delivery Date</th><td align="left"><?php echo $row['pickup_date'];?></td>
   		
    </tr>
    <tr>
    	<th>City</th><td><?php echo ($row['city_name'])? $row['city_name'] : ""; ?></td>
        <th align="left">Delivery Time</th><td><?php echo $row['pickup_time'];?></td>
    </tr>
    <tr>
    	<th>State</th><td><?php echo ($row['state_name'])? $row['state_name'] : ""; ?></td>
        <th></th><td></td>
    </tr>
    <tr>
    	<th>Country</th><td><?php echo ($row['country_name'])? $row['country_name'] : ""; ?></td>
        <th></th><td></td>
    </tr>
  </tbody>
  <?php 
  }
   if(isset($row['order_type']) && $row['order_type']=='p'){
  ?>
  <tbody<?php echo'style="display:block"';?> >
    <tr><td colspan="4"></td></tr>
  	<tr>
    	<th colspan="2" style="text-align:center;background:#CCCCCC;"><b>Pick Up Details</b></th>
        <th style="text-align:center;background:#CCCCCC;" colspan="2" align="center"><b>Pick Up Status</b></th>
    </tr>
    <tr>
    	<td><b>Location</b></td><td><?php echo ($row['location_name'])? $row['location_name'] : ""; ?></td>
        <td align="left"><b>Pickup Date</b></td>
   		<td align="left"><?php echo $row['pickup_date'];?></td>
    </tr>
    <tr>
    	<td></td><td></td>
        <td align="left"><b>Pick Up Time</b></td><td><?php echo $row['pickup_time'];?></td>
    </tr>
     <tr>
    	<td></td><td><?php echo ($row['country_name'])? $row['country_name'] : ""; ?></td>
        <td></td><td></td>
    </tr>
    
  </tbody>
  <?php
  
   } ?>
</table>
</div>
<h2>Menu Item Details</h2>
<table class="display sortable-table">
      <thead>
       <tr>
          <th class="firstrow">#</th>
          <th class="left">Item Name</th>
          <!--<th>Item Description</th>-->
          <th>Unit Price</th>
          <th class="center">Quantity</th>
          <th>Total Amount</th>
        </tr>
      </thead>     
      <tbody> 
      <?php 	   
		   $orderproduct =  $content->ThanksProducts($row['orderid']);
		   
		   $i=1;
		   
		   foreach($orderproduct as $prow){   
		  
	   ?>      
        <tr>
          <td><?php echo $i;?>.</td>
          <td class="left"><?php echo ($prow['item_name']) ? '<b>'.ucwords($prow['item_name']).'</b>':""; ?></td>
          <!--<td class="center">Item Description</td>-->
          <td class="center"><?php echo ($prow['price']) ? '$ '.$prow['price']:""; ?></td>
          <td class="center"><?php echo ($prow['qty']) ? $prow['qty']:""; ?></td>
          <!--<td class="center"><?php //$total_price = $prow['price'] * $prow['qty']; echo $formatted = '$ '. number_format($total_price, 2, '.',','); ?></td>-->
          <td class="center"><?php if(!empty($prow['total_price'])){ $total = $prow['price']*$prow['qty']; echo '$'.round_to_2dp($prow['total_price'] +$total); } ?></td>
        </tr> 
        <tr>
          <td colspan="4"><table style="width:100%">
              <tbody>
                <tr>
                  <td style="width:25%">&nbsp;</td>
                  <td style="width:37.2%"><b>Topping Name</b></td>
                  <td style="width:37.2%"><b>Price</b></td>
                  <td><b>Qty</b></td>
                  <td><b>Topping Amount</b></td>
                </tr>
                 <?php 
                  $totalprice = "";
                   //$orderTopping =  $content->ProductsToppingNameFront($row['orderid']);	   
                   $orderTopping  = $content->thanksToppingList($prow['order_detail_id']); 	
				   
                   if( $orderTopping==0):
				   
                   else:
				   
                       $j=1;
                       foreach($orderTopping as $trow):
                  ?>
                <tr>
                  <td>&nbsp;</td>
                  <td><?php echo ($trow['option_topping_name'])? $trow['option_topping_name'] : ""; echo ($trow['option_topping_name']) ? ' ('.$trow['option_topping_name'].')' : "";  ?></td>
                  <td>
                    <?php
					 					
					 if(!empty($trow['price']) && $trow['price']!=0.00){ echo "$ " . round_to_2dp($trow['price']); }
					 
                     /***************** 
					  $toppingPrice = $menu->getToppingPrice($trow['option_topping_id']); 
                            
                      if($toppingPrice['price']) {  echo "$".$toppingPrice['price']."X".$row['qty']; } else { echo "$0.0 X".$row['qty']; } $topingtoalamount = $toppingPrice['price']*$row['qty'];  echo " = ".$topingtoalamount;
                      
                      $totalprice += $topingtoalamount;
					  ****************/
                     
                    ?>
                  </td>
                  <td><?php if(!empty($trow['qty']) && $trow['qty']!=0.00){ echo $trow['qty'];}?></td>
                  <td><?php $topping_total = $trow['price']*$trow['qty']; echo '$'.round_to_2dp($topping_total);  ?></td>
                </tr>
                 <?php $j++; endforeach;  endif;?>
              </tbody>
            </table></td>
          <td style="text-align:center"></td>
        </tr>
        <tr>
        	<td colspan="2"><b>Additional Note :</b></td>
            <td colspan="2"><?php echo ($prow['item_description']) ? $prow['item_description'] : "";  ?></td>
            <td align="center"></td>
        </tr>
        <?php $i++; }?>    
      </tbody>
    </table>
    <table width="100%" border="0"  class="forms">
  <thead>
      <tr>
        <th colspan="2" align="left" style="background:#CCCCCC">Amount Details:</b></th>
      </tr>
  </thead>
  <tbody> 
      <tr>
    	<th align="left">Gross Amount: </th>
    	<td>$<?php echo ($row['gross_amount']) ? round_to_2dp($row['gross_amount']) : "";  ?></td>
      </tr> 
      <tr>
    	<th align="left">Sales Tax(<?php echo ($row['sales_tax_rate']);?>%):</th>
    	<td>$<?php echo ($row['sales_tax']) ? round_to_2dp($row['sales_tax']) : "";  ?></td>
      </tr>
      <?php if(isset($row['additional_fee']) && $row['additional_fee'] !='0.00' ){ ?>
      <tr>
    	<th align="left">Additional Fee: </th>
    	<td>$<?php echo ($row['additional_fee']) ? round_to_2dp($row['additional_fee']) : "";  ?></td>
      </tr> 
       <?php }  if(isset($row['gratuity']) && $row['gratuity']!='0.00' ){ ?>
      <tr>
    	<th align="left">Gratuity: </th>
    	<td>$<?php echo ($row['gratuity']) ? round_to_2dp($row['gratuity']) : "";  ?></td>
      </tr>  
       <?php }  if(isset($row['delivery_fee']) && $row['delivery_fee']!='0.00' ){ ?>    
      <tr>
    	<th align="left">Delivery Fees: </th>
    	<td>$<?php echo ($row['delivery_fee']) ? round_to_2dp($row['delivery_fee']) : "";  ?></td>
      </tr>
       <?php }  if(isset($row['coupon_discount']) && $row['coupon_discount']!='0.00' ){ ?> 
      <tr>
    	<th align="left">Discount (<?php echo ($row['coupon_id']) ? $row['coupon_id'] : "";  ?>)</th>
    	<td>$<?php echo ($row['coupon_discount']) ? round_to_2dp($row['coupon_discount']) : "";  ?></td>
      </tr>
      <?php }  if(isset($row['tip']) && $row['tip']!='0.00' ){ ?>   
      <tr>
    	<th align="left">Tip:</th>
    	<td>$<?php echo ($row['tip']) ? round_to_2dp($row['tip']) : "";  ?></td>
      </tr>
       <?php  } ?>
       <tr>
    	<th align="left">Net Amount: </th>
    	<td>$<?php echo ($row['net_amount']) ? round_to_2dp($row['net_amount']) : "";?></td>
      </tr>      
  </tr>
  </tbody>  
 </table>

<?php break;?>
<?php default:

	$search = (isset($_POST['order_search'])) ? ($_POST['order_search']) : false;
	$status =  isset($_GET['status']) ? ($_GET['status']) : false;
	$orderrow = $content->getorders($status);
    $sql_query = $content->is_homemod;  //This is the query from class $content->getorders($status) 	
		
?>
<div class="block-top-header">
  <h1><img src="images/users-sml.png" alt="" />Manage Orders</h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span>Here you can manage your orders. <br />You can  canceled ,dispatched and Delivered , each order by clicking on oprion of action.</p>
<div class="block-border">
   <div class="block-header">
    <h2>
    <span><a href="javascript:void(0);" id="export_csv" rel="<?php echo $sql_query; ?>">Click to Export Csv</a></span>
    	Viewing Orders
    </h2>
  </div>
  <div id="export" style="border:1px solid; margin-top:2px;">	
	<div id="loading" style="display:none;"><img src="<?php echo SITEURL;?>/admin/images/loading.gif" /> Please wait..Request processing..</div>
	<div id="export_result"></div>
  </div>
  <div class="block-content">
    <div class="utility">
      <table width="100%" border="0">
		<tr>
          <td>
              <form action="" method="post">
               <input name="order_search" type="text" class="inputbox" id="order_search" size="40" placeholder="Search by Order Number"/>
               <input name="submit" type="submit" class="button-blue" value="<?php echo _TR_FIND;?>"  />
              </form>
             </td>
             <td>
                <form action="" method="post" id="dForm">
                  <strong> <?php echo _UR_SHOW_FROM;?></strong>
                  <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="12" id="fromdate" />
                  <strong> <?php echo _UR_SHOW_TO;?></strong>
                  <input name="enddate" type="text" class="inputbox-sml" size="12" id="enddate" />
                  <input name="find" type="submit" class="button-blue" value="<?php echo _UR_FIND;?>" />
                </form>
             </td>
             <td>
                  <select name="status" style="width:220px" class="custombox" onchange="if(this.value!='NA') window.location='index.php?do=order&amp;status='+this[this.selectedIndex].value; else window.location='index.php?do=order';">
                    <option value="NA">--- Filter Orders ---</option>
                     <?php
                     if(isset($_POST['status'])){ $sortslect = $_POST['status'];  }
                     else {  $sortslect=''; }
					?>
                    <option value="d" <?php if($sortslect =="d"){ echo "selected"; }?>>Status - Delivered</option>
                    <option value="c" <?php if($sortslect == "c"){ echo "selected"; }?>>Status - Cancelled</option>
                    <option value="dis" <?php if($sortslect == "dis"){ echo "selected"; }?>>Status - Dispatched</option>
                    <option value="p" <?php if($sortslect == "p"){ echo "selected"; }?>>Status - Pending</option>
                  </select>
          </td>
        </tr>
      </table>
      <table class="display">        
        <tr>          
          <td>
          	<!--<img src="images/u_active.png" class="tooltip" alt="" title="Order Completed"/> Order Completed
            <img src="images/u_inactive.png" class="tooltip" alt="" title="Order Cancelled"/> Order Cancelled 
            <img src="images/u_pending.png" class="tooltip" alt="" title="Order Pending"/> Order Pending 
            <img src="images/u_banned.png" class="tooltip" alt="" title="Order Despatched"/> Order Despatched-->
          </td>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left">Order No</th>
          <th class="left">Customer Name</th>
          <th class="">Location Name</th>
          <th class="">Order Date</th>
          <th class="">Order Type</th>
          <th class="">Net Amount</th>
          <!--<th class="left sortable">Status</th>-->
          <th>View Details</th>
          <th>Order Status</th>
          <!--<th>Delete</th>-->
        </tr>
      </thead>
      <?php if($pager->items_total >= $pager->items_per_page):?>
      <tfoot>
        <tr>
          <td colspan="9"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if($orderrow == 0):?>
        <tr>
          <td colspan="8"><?php echo $core->msgAlert('<span>Alert!</span>You don\'t have any Orders  yet...',false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($orderrow as $row):?>
        <tr>
          <th><?php echo $row['orderid'];?>.</th> 
          <th><?php echo $row['order_number'];?></th>         
          <td><?php echo ucwords($row['first_name'].' '.$row['last_name']); ?></td>
          <td class="center"><?php echo $row['location_name'];?></td>   
          <td class="center"><?php echo $row['pickup_date'] .' '.$row['pickup_time'];  ?></td>  
          <td class="center">
		  	<?php 
				if(isset($row['order_type']) && $row['order_type']=='p'){ echo 'Pick Up';} 
				if(isset($row['order_type']) && $row['order_type']=='d'){ echo 'Delivery';} 
				if(isset($row['order_type']) && $row['order_type']=='dl'){ echo 'Dine In';}
			?>
          </td> 
          <td class="center"><?php echo ($row['net_amount'])? '$ '.round_to_2dp($row['net_amount']) : "";  ?></td>         
          <!--<td class="center"><?php //echo orderStatus($row['order_status']);?></td>-->
          <td class="center">
          	<a href="index.php?do=order&amp;action=view&amp;postid=<?php echo $row['orderid'];?>">
            	<img src="images/view.png" class="tooltip"  alt="" title="<?php echo "View Details Orders";?>"/>
            </a>
          </td>
          <td align="center">
          	<?php 
				if(isset($row['order_status']) && $row['order_status']=='p'){ echo "Pending";   }  
				if(isset($row['order_status']) && $row['order_status']=='c'){ echo "Cancelled"; } 
				if(isset($row['order_status']) && $row['order_status']=='f'){ echo "Completed"; }   
				if(isset($row['order_status']) && $row['order_status']=='d'){ echo 'Denied';    } 
				if(isset($row['order_status']) && $row['order_status']=='b'){ echo 'Processing';}
				if(isset($row['order_status']) && $row['order_status']=='r'){ echo 'Refunded';  } 
				if(isset($row['order_status']) && $row['order_status']=='g'){ echo 'Delete';    }        
			?>
          </td>
          <!--<td>
          	<a href="javascript:void(0);" class="delete" data-title="<?php //echo $row['first_name'];?>" id="item_<?php // echo $row['orderid'];?>">
            	<img src="images/delete.png" class="tooltip"  alt="" title="Delete"/>
            </a>
          </td>-->
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._USER, "deleteUser");?> 
<script type="text/javascript"> 
// <![CDATA[
    var dates = $('#fromdate, #enddate').datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 1,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var option = this.id == "fromdate" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
	
	$(document).ready(function () {
	/** export csv start here**/
	   $("#export_csv").click(function () {
		   
				var sqlQuery = $(this).attr('rel');				
				
				$.ajax({
					type: "POST",
					url: "ajax.php",
					data: '&export_ordercsv=1'+ '&postQuery=' + sqlQuery,
					//data: "&export_ordercsv=1",
					
					beforeSend: function () {
						$('#loading').css({ display: "block" });
					},
					success: function (res) {
						$('#loading').css({ display: "none" });
						$('#export_result').html(res).show();
					}
				});
		  });
	/** export csv ends here**/
	});
	
// ]]>
</script>
<?php break;?>
<?php endswitch;?>