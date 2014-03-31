<?php
  /**
   * Index
   * Kula cart 
   *  
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>

<div class="row-fluid margin-top">
  <div class="span12">
    <!-----View Cart Details----->
    <div class="span9 box-shadow fit">
      <div class="span12 padding-outer-box">
        <h3 class="title ">Your Shopping Cart :</h3>
        <div class="row-fluid viewcart_header">
          <div class="span4" style="margin:0;">Item Name </div>
          <div class="span4">Item Description </div>
          <div class="span1">Price</div>
          <div class="span1">Qty</div>
          <div class="span1">Amount </div>
          <div class="span1">Action</div>
        </div>
        <?php $inbasket  = $product->MyShoppingBasket();
		  if($inbasket): 
		  foreach ($inbasket as $rows): 
		  $prow = $product->viewcartdetails($rows['productID']);
		  ?>
              <div class="separator2"></div>
        <div class="row-fluid">
          <div class="span4 bold" style="margin:0;"><?php echo $prow['item_name']; ?> </div>
          <div class="span4 bold"><?php echo $prow['item_description'];?></div>
          <div class="span1 bold"><?php echo $rows['productPrice'];?></div>
          <div class="span1 bold"><?php echo $rows['qty'];?></div>
          <div class="span1 bold"><?php echo round_to_2dp($rows['totalprice']);?></div>
          <div class="span1 bold"><a href="#" id="<?php echo $rows['basketID'];?>" class="delete"><img src="images/close.png" /></a></div>
           <?php $topping  = $product->viewCartTopping($rows['basketID']); 
			if($topping){
			?>
          <div class="row-fluid">
          <div class="span12">
            <div class="row-fluid">
              <div class="span12"><a id="imageDivLink" href="javascript:toggle('<?php echo $rows['basketID'];?>');" class="dfdsfs">Hide Topping Details<i class="icon-chevron-down"></i></a> </div>
            </div>
            
            <div class="row-fluid test" id="<?php echo $rows['basketID'];?>" style="display: block;">
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
                      <div class="span5"><?php if($toppingPrice['price']) {  echo "$".$toppingPrice['price']; } else { echo "$0.00";}  ?> X 1 = <?php if($toppingPrice['price']) {  echo "$".$toppingPrice['price']; } else { echo "$0.00";}  ?></div>
                      <?php $totalprice += $toppingPrice['price']; ?>
                      <?php //endforeach; endif;?>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
                <div class="span4 bold">
                  <div class="separator3"></div>
                  $<?php echo  round_to_2dp($totalprice);?></div>
                <div class="clr"></div>
              </div>
            </div>
            
          </div>
        </div>
         <?php } ?>
        </div>
        
        <?php endforeach; endif;?>
        <div class="row-fluid viewcart_header">
          <div class="span2"></div>
          <div class="span3"><strong>Additional Notes</strong>:</div>
          <div class="span6"></div>
        </div>
        <div class="separator2"></div>
        <div class="row-fluid">
          <div class="span8"></div>
          <div class="span4 cart_total">
            <div class="row-fluid">
              <div class="span7">
                <?php $totalprice = $product->totalpriceitem();					 
					  $location = $menu->locationIdByMenu($websitenmae);
					  $additional =  $product->additionalAmmount($location['location']);
				?>
                Total Price : </div>
              <div class="span5">
              <?php  $toaltoppingprice = $menu->ToppingTotalPrice();			  
			  		if($toaltoppingprice):
					foreach($toaltoppingprice as $toppingtotal):
					$totalToppingPrice += $toppingtotal['totalprice'];
					endforeach;
					endif;
			  ?>
              
              $<?php echo $totalamount =  round_to_2dp($totalprice['totalprice']+$totalToppingPrice); ?> </div>
            </div>
            <div class="row-fluid">
              <div class="span7">Additional Fee : </div>
              <div class="span5">$<?php echo round_to_2dp($additional['additional_fee']);?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Gratuity : </div>
              <div class="span5">$<?php echo round_to_2dp($additional['gratuity']);?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Sales Tax :<?php echo $additional['sales_tax'];?>%</div>
              <div class="span5">$<?php echo round_to_2dp($salestex =  $totalamount*$additional['sales_tax']/100); ?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Net Amount :</div>
              <div class="span5"> $<?php echo round_to_2dp($net_amount = $totalamount+$additional['additional_fee']+$salestex); ?></div>
            </div>
          </div>
        </div>
         
      </div>
      <div class="row-fluid">
             <div class="span12">
             <div  class="span4"></div>
             <div  class="span4"><div class="checkout"><a href="<?php echo SITEURL; ?>/">Shopping </a></div></div>
             <div  class="span4"><div class="checkout"><a href="<?php echo SITEURL; ?>/checkout">CHECKOUT</a></div></div>
             </div>.
         </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php include("rightside.php");?>
    <!-----RIGHT END----->
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<script language="javaScript">

</script>
<script type="text/javascript">
$(function() {
$(".delete").click(function() {
$('#load').fadeIn();
var commentContainer = $(this).parent().parent();
var id = $(this).attr("id");
var string = 'id='+ id ;
	
$.ajax({
   type: "POST",
   url: "<?php echo SITEURL;?>/ajax/user.php",
   data: 'deleteInBasket=' + id,
   cache: false,
   success: function(){
	commentContainer.slideUp('slow', function() {$(this).remove();});
	$('#load').fadeOut();
  }
   
 });

return false;
	});
});
</script>
