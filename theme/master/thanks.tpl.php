<?php
  /**
   * Checkout 
   * Kula cart 
   *  
   */  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  $orderid = $_GET['vis'];
?>
<div class="row-fluid margin-top">
   <div class="span12">
  <h1>Thank you For Shopping with us online!</h1>
  <?php $thanks = $product->ThanksOrderDetails($_SESSION['orderid']); ?>
    <!-----View Cart Details----->
    <div class="span9 box-shadow fit"> 
    <div class="span12 fit invicebg">order receipt</div>     
      <div class="span12">   
       <div class="span3">Order Number* :</div>
        <div class="span3"> <?php echo $thanks['order_number'];?></div>
        <div class="span3">Oder date & time</div>
         <div class="span3"><?php echo $thanks['pickup_date'];?> / <?php echo $thanks['pickup_time'];?></div>
          <div class="span12 fit">
            <div class="span3">Order Type* :</div>
            <div class="span3"> <?php if($thanks['order_type']=='p'){ echo "Pick Up"; } else{ echo "Delivery"; }?></div>
            <div class="span3">Oder amount</div>
             <div class="span3"><?php echo $thanks['gross_amount'];?></div>
          </div>
          <div class="span12 fit">
            <div class="span3">Payment method* :</div>
            <div class="span3"> <?php if($thanks['payment_type']=='c') { echo "Cash On delivery"; }?></div>
            <div class="span3">Location</div>
             <div class="span3"><?php echo $thanks['location_name'];?></div>
          </div>
          <div class="clr"></div>
      </div>
      <div class="row-fluid">
          <div class="span2 fit itembg">S.NO</div>
          <div class="span4  itembg">Item Name</div>
          <div class="span1  itembg">Price</div>
          <div class="span1  itembg">Qty</div>
          <div class="span4 itembg">Total Price</div>
      </div>
       <?php $orderproduct = $product->ThanksProducts($orderid);  
		 		$i =1;
				foreach($orderproduct as $prow)
				{?>
       <div class="row-fluid">
          <div class="span2 fit"><?php echo $i;?></div>
          <div class="span4"><?php echo $prow['item_name']; ?></div>
          <div class="span1">$<?php echo $prow['price'];?></div>
          <div class="span1"><?php echo $prow['qty'];?></div>
          <div class="span4">$<?php echo round_to_2dp($prow['price']*$prow['qty']);?></div>
           <?php $topping  = $product->thanksToppingList($prow['order_detail_id']); 
			if($topping){
			?>
          <div class="row-fluid">
          <div class="span12">
            <div class="row-fluid">
              <div class="span12"><a id="imageDivLink" href="#" class="dfdsfs">Hide Topping Details<i class="icon-chevron-down"></i></a> </div>
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
                      <div class="span5"><?php if($toppingPrice['price']){ echo "$".$toppingPrice['price'];} else { echo "$0.00"; } ?> X 1 =<?php if($toppingPrice['price']){ echo "$".$toppingPrice['price'];} else { echo "$0.00"; } ?></div>
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
      <?php $i++; }?>
      <div class="row-fluid">
          <div class="span8"></div>
          <div class="span4">
          	<div class="row-fluid">
              <div class="span7">Total Price : </div>
              <div class="span5"> $<?php echo $thanks['gross_amount'];?> </div>
            </div>
            <div class="row-fluid">
              <div class="span7">Additional Fee : </div>
              <div class="span5">$<?php echo $thanks['additional_fee'];?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Gratuity :</div>
              <div class="span5">$<?php echo $thanks['gratuity'];?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Sales Tax :<?php echo $thanks['sales_tax'];?>%</div>
              <div class="span5">$<?php echo $thanks['sales_tax_rate'];?></div>
            </div>
            <div class="row-fluid">
              <div class="span7">Net Amount :</div>
              <div class="span5"> $<?php echo $thanks['net_amount'];?></div>
            </div>
          </div>
      </div>
      <div class="row-fluid">
          <div class="span12 fit itembg">Order Comments</div>
          <div class="span10"><?php echo $thanks['order_comments'];?></div>
      </div>
      <!--<div class="row-fluid">
          <div class="span12 fit itembg">Delivery Address</div>
          <div class="span10"></div>
      </div>-->
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php //unset($_SESSION['orderid']);
	 //include("rightside.php");?>
    <!-----RIGHT END----->
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<!-- loder div -->
<div style="display: none;" id="smallLoader">
  <div>
    <div> </div>
  </div>
</div>