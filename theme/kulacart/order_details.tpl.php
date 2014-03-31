<?php
  /**
   * Index
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  $orderid = $_GET['orderid'];	
	  
?>
<link rel="stylesheet" href="<?php echo THEMEURL;?>/css/jquery-ui.css" />
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery-ui.js" ></script>
<script src="<?php echo THEMEURL;?>/js/jquery.popupoverlay.js"></script>

<script type="text/javascript">
	$(function(){
		$( "#tabs" ).tabs();
	});
</script>

<div class="row-fluid top_links_strip">
  <div class="span12">
<!--    <div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Order Details </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
  <div class="container">
<div class="row-fluid margin-top padding-top-10 padding-bottom-10">
  <div class="span12">     
    <!-----View Cart Details----->
    <div class="span12 fit">
      <div class="row-fluid">
        <div class="span12 top_heading_strip">Order Details</div>
        </div> 
      <div class="span12 padding-outer-box"> 
        <!--Tabbing Starts here-->
        <div class="span10"> <?php echo $customers->customername;  ?></div>
        <div class="clr"></div>
        <div class="span11 fit pages-tab-view">
          <ul id="myTab" class="nav nav-tabs pages-tab-view-ul">
            <li class="active"><a href="#home" data-toggle="tab">Order Details</a></li>
            <li><a href="#menuitems" data-toggle="tab">Menu Item Details</a></li>
            <li><a href="#reorder" data-toggle="tab">Place this order again</a></li>
            <div class="clr"></div>
          </ul>
          <div id="myTabContent" class="tab-content tab-message">
            <div class="tab-pane fade in active tab-message" id="home">
              <table cellspacing="3" cellpadding="3" style="width:100%">
                <tbody>
                  <tr class="order-his">
                    <td colspan="2" align="center" valign="middle">Order Details</td>
                  </tr>
                  <?php 			
					  $orderrow = $content->getproductOrderDetailsFront($orderid); 								   
					  if( $orderrow==0):
					  else:  
				  ?>
                  <tr>
                    <td>Order Number :</td>
                    <td align="left"><?php echo ($orderrow['order_number'])? $orderrow['order_number'] : ""; ?></td>
                  </tr>
                  <tr>
                    <td>Order Date :</td>
                    <td align="left"><?php echo ($orderrow['order_date'])? $orderrow['order_date'] : ""; ?></td>
                  </tr>
                  <tr>
                    <td>Order time :</td>
                    <td align="left"><?php echo ($orderrow['order_time'])? $orderrow['order_time'] : ""; ?></td>
                  </tr>
                  <tr>
                    <td>Customer Name :</td>
                    <td align="left"><?php echo ($orderrow['first_name'])? $orderrow['first_name'] : ""; ?></td>
                  </tr>
                  <!--<tr><td>Store Name :</td><td align="left"><?php //echo ($orderrow['order_number'])? $orderrow['order_number'] : ""; ?></td></tr>-->
                  <tr>
                    <td>E-mail ID :</td>
                    <td align="left"><?php echo ($orderrow['email_id'])? $orderrow['email_id'] : ""; ?></td>
                  </tr>
                  <tr>
                    <td>Phone :</td>
                    <td align="left"><?php echo ($orderrow['phone_number'])? $orderrow['phone_number'] : ""; ?></td>
                  </tr>
                  <tr>
                    <td>Order Type :</td>
                    <td align="left"><?php
						 if($orderrow['order_type']=='p'){ echo "Pick Up";}       //PickUp 
						 if($orderrow['order_type']=='d'){ echo "Delivery";}      //Delivery 
						 if($orderrow['order_type']=='dl'){ echo "Dine In";}      //Dine In 
					?></td>
                  </tr>
                  <tr>
                    <td>Payment Method :</td>
                    <td align="left"><?php
					 if($orderrow['payment_type']=='c'){ echo "Cash";}         //Cash 
					 if($orderrow['payment_type']=='o'){ echo "Online";}       //Online 
				   ?></td>
                  </tr>                  
                  <tr>
                    <td>Order Total :</td>
                    <td align="left"><?php echo ($orderrow['net_amount'])? '$'.$orderrow['net_amount'] : ""; ?></td>
                  </tr>                 
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            
            <!-----menu item details, start here----->            
            <div class="tab-pane fade tab-message" id="menuitems">
            <table cellspacing="3" cellpadding="3" style="width:100%; margin-bottom:10px;">
                    <tbody>
                      <tr class="order-his">
                        <td colspan="2" align="center" valign="middle">Menu Item Details</td>
                      </tr>
                     </tbody>
              </table>                
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
                        <strong><?php echo $prow['item_name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?> </strong><br />
                        <span class="itemdesc"><?php echo $prow['item_description'];?></span>
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
              <?php	$ProductAmount = $product->ShowProductFinalAmount($orderid); ?>
                <div class="span12 fit">
            	<div class="heading-top2">Amount Details:</div>
            </div>
      		<div class="separator2"></div>
      		<div class="span8">
                <div class="row-fluid">
                	<div class="span5">
                		<div class="row-fluid">
                			<div class="span7"><span class="black">Gross Amount : </span> </div>
                			<div class="span5">
                              <span class="black">$<?php echo ($ProductAmount['gross_amount']) ? round_to_2dp($ProductAmount['gross_amount']) : "";  ?></span>
                            </div>
                		</div>
                		<div class="row-fluid">
                            <div class="span7"><span class="black">Sales Tax : @<?php echo ($ProductAmount['sales_tax_rate']);?>%</span></div>
                            <div class="span5">
                            	<span class="black">$<?php echo ($ProductAmount['sales_tax']) ? round_to_2dp($ProductAmount['sales_tax']) : "";?></span>
                            </div>
                        </div>
                         <?php if(isset($ProductAmount['delivery_fee']) && $ProductAmount['delivery_fee']!='0.00' ){ ?>             		
                		<div class="row-fluid">
                            <div class="span7"><span class="black">Delivery Fee:</span></div>
                            <div class="span5"><span class="black">$ <?php echo round_to_2dp($ProductAmount['delivery_fee']); ?></span></div>
                        </div>
                        <?php } ?>
                       <?php if(isset($ProductAmount['additional_fee']) && $ProductAmount['additional_fee'] !='0.00' ){ ?>
                		<div class="row-fluid">
                			<div class="span7"><span class="black">Additional Fee : </span></div>
                			<div class="span5">
                             <span class="black">$<?php echo ($ProductAmount['additional_fee']) ? round_to_2dp($ProductAmount['additional_fee']) : "";?></span>
                            </div>
                		</div>
                        <?php } ?>
                        <?php if(isset($ProductAmount['gratuity']) && $ProductAmount['gratuity']!='0.00' ){ ?>
                        <div class="row-fluid">
                            <div class="span7"><span class="black">Gratuity : </span></div>
                            <div class="span5"><span class="black">$<?php echo ($ProductAmount['gratuity']) ? round_to_2dp($ProductAmount['gratuity']) : "";?></span></div>
                        </div>   
                        <?php } ?>
                        <?php if(isset($ProductAmount['coupon_discount']) && $ProductAmount['coupon_discount']!='0.00' ){ ?>
                        <div class="row-fluid">
                            <div class="span7"><span class="black">Coupon(<?php echo $ProductAmount['coupon_id'] ; ?>) : </span></div>
                            <div class="span5"><span class="black">$<?php echo ($ProductAmount['coupon_discount']) ? round_to_2dp($ProductAmount['coupon_discount']) : "";?></span></div>
                        </div>   
                        <?php } ?>
                         <?php if(isset($ProductAmount['tip']) && $ProductAmount['tip']!='0.00' ){ ?>
                        <div class="row-fluid">
                            <div class="span7"><span class="black">Tip : </span></div>
                            <div class="span5"><span class="black">$<?php echo ($ProductAmount['tip']) ? round_to_2dp($ProductAmount['tip']) : "";?></span></div>
                        </div>   
                        <?php } ?>
                       
                        <div class="row-fluid">
                            <div class="span7"><span class="black"><b>Net Amount :</b></span></div>
                            <div class="span5"><strong><span class="black">$<?php echo ($ProductAmount['net_amount']) ? round_to_2dp($ProductAmount['net_amount']) : "";?></span></strong></div>
                        </div>
                	</div>
                	
                </div>
      		</div>			
            </div>            
            <!-----menu item details, ends here----->
            <div class="tab-pane fade tab-message" id="reorder">
              <table cellspacing="3" cellpadding="3" style="width:100%">
                <tbody>
                  <tr class="order-his">
                    <td colspan="2" align="center" valign="middle">Place this order again</td>
                  </tr>
                  <tr>
                    <td align="left"><br />
                      You can quickly place this exact same order again by clicking on the Reorder button below. This will add the same items to your shopping cart. <br />
                      <br /></td>
                  </tr>
                  <tr>                    
                    <td style="text-align:center">
                      <a href="#" class="my_modal_open orderIdValue" title="Click To Re-Order" rel="<?php echo $row['orderid'];?>" id="<?php echo $orderid;?>">
                        	<img src="<?php echo THEMEURL;?>/images/btn_reorder.png" alt="reorder" />
                      </a> <br />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!--Tabbing Ends here--> 
      </div>
      <div class="span12 fit btn_back_next">
        <div class="span10"></div>
            <div class="span2">
              <div>
              	<a href="javascript:goback()" title="Back" class="btn-2-2">BACK</a>
              </div>
            </div>            
        <div class="clr"></div>
     </div>      
    </div>
  </div>
</div>
</div>
<!-- Add content to modal -->
  <div id="my_modal" style="display:none"> 
  	 <div class="dialog_heading">Reorder </div> 	
     <p>
     	<span style="float: left; margin: 0 7px 20px 0;"></span>
        You can quickly place this exact same order again by clicking on the Reorder button below.<br/>
        This will add the same items to your shopping cart.
     </p> 
     <a title="Reorder" class="reorder bold" style="cursor:pointer">Reorder</a>  
     <a title="Close" class="my_modal_close bold" style="cursor:pointer">Close</a>
  </div>
<!-- Add content to modal -->
<script type="text/javascript">
// <![CDATA[
	$(document).ready(function() {
		
		$("a.reorder").click(function(){
			
			var order_id = $(".orderIdValue").attr("id");
			
			$('#load').fadeIn();
			$("#smallLoader").css({display: "block"});
			$("#my_modal").hide();
			
			
			$.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/ajax/user.php",
				data: "FlyToBasket=1&order_id="+order_id,
				cache: false,
				success: function (res) {
					$('#load').fadeOut();
					$("#smallLoader").css({display: "none"});
					window.location.href = SITEURL+'/view-cart';
				}
			});
		}); 		
		
		$(".imageDivLink").click(function(){		   
			 var id =  $(this).attr('id');
			 $("#showToggle"+id).toggle();		  
	   });
		
	});

	$(function() {
		  $('#my_modal').popup();
	});
	
// ]]>
</script> 
