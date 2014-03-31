<?php include("header.php"); ?>
<?php
  
    $orderid = $_GET['orderid']; 
   
?>
  <div data-role="content">
    <h1 class="main-heading">Menu Item Details </h1>
    <!-- /header --> 
    <!-- /content -->   
    <div class="ui-grid-c your-shopping-cart">
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-d  your-shopping-cart">Item Name</div>
      </div>
      <div class="ui-block-b">
        <div class="ui-bar ui-bar-d  your-shopping-cart" >Unit Price</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-d  your-shopping-cart" >Qty</div>
      </div>
      <div class="ui-block-d">
        <div class="ui-bar ui-bar-d  your-shopping-cart" >Total Amount</div>
      </div>
    </div>
    <!-- /grid-c -->
    <?php  
	$orderproduct = $product->ThanksProducts($orderid); 
 
	$i =1;
	foreach($orderproduct as $prow){
		
		$ItemSize = $product->getItemSize_new($prow['order_detail_id'],$orderid,$prow['menu_size_map_id']);  //Item Size		
    ?>
            <div class="ui-grid-c">
              <div class="ui-block-a">
                <div class="ui-bar ui-bar-e" style="height:60px"><strong><?php echo $prow['item_name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?></strong></div>
              </div>
              <div class="ui-block-b">
                <div class="ui-bar ui-bar-e" style="height:60px">
                	<strong>
                  		<?php if(!empty($prow['price']) && $prow['price']!=0.00){echo "$ " . round_to_2dp($prow['price']);}?>
                    </strong>
                </div>
              </div>
              <div class="ui-block-c">
                <div class="ui-bar ui-bar-e" style="height:60px"><strong><?php echo $prow['qty'];?></strong></div>
              </div>
              <div class="ui-block-d">
                <div class="ui-bar ui-bar-e" style="height:60px">                
                  <?php  $total = $prow['price']*$prow['qty']; echo '$'.round_to_2dp($prow['total_price'] +$total);  ?>
                </div>
              </div>
            </div>
            <?php 
				$OptionName  = $product->OptionName($prow['order_detail_id']);
				if($OptionName==0):
			    else:
			
			?>
            <div data-role="collapsible" data-theme="b" data-content-theme="d" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u" data-collapsed="false">
              <h4 class="toppings-extras"> Toppings & Extras</h4>
              <ul data-role="listview" data-inset="false">
                
                <li>
                  <table style="width:100%">
                    <tbody>
					  <?php foreach($OptionName as $orow){ ?>
                      <tr>
                         <td class="toppings-extras-info" style="color:#FF0000;">
                            <span class="textsize14">
                                <?php echo ($orow['option_name']) ? $orow['option_name'] : ""; ?>
                            </span>
                         </td>
                      </tr>
					  <?php					  
					    $topping  = $product->thanksToppingListNew($orow['option_id'],$orow['order_detail_id']);
						
						if($topping==0):						
						else:						
                        foreach($topping as $row){                        
                      ?>
                      <tr>
                        <td class="toppings-extras-info">
                        	<span class="textsize14">
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
                          </span>
                        </td>
                        <?php /*?>
						<td class="toppings-extras-info" style="display:none">
                        	<span class="textsize14">
								<?php if(!empty($row['price']) && $row['price']!=0.00){ echo "$ " . round_to_2dp($row['price']);}?>
                            </span>
                        </td>
                        <td class="toppings-extras-info" style="display:none">
                        	<span class="textsize14">
								<?php if(!empty($row['price']) && $row['price']!=0.00 && $row['price']!=0.00){ echo $row['qty'];}?>
                           	</span>
                        </td>
                        <td class="toppings-extras-info" style="display:none">
                        	<span class="textsize14">								
							<?php  
                                if(!empty($row['price']) && $row['price']!=0.00){ 
								
                                    $topping_total = $row['price']*$row['qty']; 
                                    echo '$'.round_to_2dp($topping_total); 
                                } 
                            ?>
                            </span>
                        </td>
						<?php */?>
                      </tr>
					  <?php 
						   }
						  endif;
						}						
					 ?>
                    </tbody>
                  </table>
                </li>                
              </ul>
            </div>
            <?php endif; ?>
            <?php if(isset($prow['comments']) && !empty($prow['comments'])){ ?>
            <div class="ui-grid-solo">
              <div class="ui-block-a">
                <div class="ui-bar ui-bar-e bold cart_option_heading">Who is this for ?</div>
              </div>
            </div>
            <div class="ui-grid-solo">
              <div class="ui-block-a">
                <div class="ui-bar ui-bar-e"><?php echo ($prow['comments']) ? ucfirst($prow['comments']) : "" ;?></div>
              </div>
            </div>
             <?php } ?>
            <hr />
            <?php 	
		  
		$i++;
	 } 
	?>   
    <h1 class="main-heading"> Amount Details:</h1>
     <?php
		 $ProductAmount = $product->ShowProductFinalAmount($orderid);
	 ?>
    <div class="ui-grid-a table-1-a">
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Gross Amount: </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['gross_amount']) ? round_to_2dp($ProductAmount['gross_amount']) : "";  ?></div>
      </div>
     
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Sales Tax :<?php echo ($ProductAmount['sales_tax_rate']);?>%</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['sales_tax']) ? round_to_2dp($ProductAmount['sales_tax']) : "";?></div>
      </div>
      <?php if(isset($ProductAmount['additional_fee']) && $ProductAmount['additional_fee'] !='0.00' ){ ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Additional Fee </div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['additional_fee']) ? round_to_2dp($ProductAmount['additional_fee']) : "";?></div>
      </div>
      <?php } ?>
      <?php if(isset($ProductAmount['gratuity']) && $ProductAmount['gratuity']!='0.00' ){ ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Gratuity</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['gratuity']) ? $ProductAmount['gratuity'] : "";?></div>
      </div>      
      <?php }  if(isset($ProductAmount['delivery_fee']) && $ProductAmount['delivery_fee']!='0.00' ){ ?>      
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Delivery Fees:</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($ProductAmount['delivery_fee']); ?></div>
      </div>
      <?php }  if(isset($ProductAmount['coupon_discount']) && $ProductAmount['coupon_discount']!='0.00' ){ ?>  
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Discount (<?php echo ($ProductAmount['coupon_id']) ? $ProductAmount['coupon_id'] : "";  ?>) :</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['coupon_discount']);?></div>
      </div>      
      <?php }  if(isset($ProductAmount['tip']) && $ProductAmount['tip']!='0.00' ){ ?>     
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Tip :</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($ProductAmount['tip']); ?></div>
      </div>
      <?php } ?>
      <div class="ui-block-a">
        <div class="ui-bar ui-bar-e table-1-a-b" >Net Amount:</div>
      </div>
      <div class="ui-block-c">
        <div class="ui-bar ui-bar-e table-1-a-b" >$<?php echo ($ProductAmount['net_amount']) ? round_to_2dp($ProductAmount['net_amount']) : "";?></div>
      </div>
    </div>    
    <a href="restaurantmenu.php" data-role="button" data-ajax="false">Add More Food</a> 
  </div>
  <a href="index.php" data-role="button" data-ajax="false">Back to Home</a> 
  <!-- /content --> 
</div>
<?php include("footer.php");?>