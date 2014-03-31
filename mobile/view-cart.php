<?php include("header.php"); ?>
<?php
    $sessionID = SESSION_COOK;
	$checkout =   $customers->chekoutproduct($sessionID);
	if(!$checkout)
	{
		header("Location:restaurantmenu.php");
	}?>
  <?php $productrow = $menu->productByItem(@$_GET['Item']); ?>

  <?php $option = $menu->optionListByItem(@$_GET['Item']); ?>

  <div data-role="content">

    <h1 class="main-heading">Your Shopping Cart </h1>
    <!-- /header -->
    <!-- /content -->
    <?php 
		if(isset($_SESSION['chooseAddress']))
		{
			$locationid = $_SESSION['chooseAddress'];
		}
		else
		{
			$locationrow = $menu->locationIdByMenu($websitenmae);
			$locationid = $locationrow['location'];
		}
		$additionalfeesrow = $product->additionalAmmount($locationid);
    ?>
    <?php 	

		$allproductrow = $product->AllProductInBasket(); //print_r($allproductrow); exit();	
		if($allproductrow){
    ?>
    <div class="ui-grid-d your-shopping-cart">
        <div class="ui-block-a width10"><div class="ui-bar ui-bar-e your-shopping-cart">Qty</div></div>
        <div class="ui-block-b width35"><div class="ui-bar ui-bar-e your-shopping-cart">Item Name</div></div>
        <div class="ui-block-c"><div class="ui-bar ui-bar-e your-shopping-cart">Unit Price($)</div></div>
        <div class="ui-block-d"><div class="ui-bar ui-bar-e your-shopping-cart">Total($)</div></div>
        <div class="ui-block-e width10"><div class="ui-bar ui-bar-e your-shopping-cart"></div></div>
  </div><!-- /grid-c -->
      <?php	  

		  $gross_amount = "";	
		  foreach($allproductrow as $row){
		  	$ItemSize = $product->getItemSize($row['menu_size_map_id']);  //size name if topping is sizzed
		  	$gross_amount += $row['total_price'];
		   ?>
           <div class="ui-grid-d">
        <div class="ui-block-a width10"><div class="ui-bar ui-bar-e bold"><?php echo $row['quantity'];?></div></div>
        <div class="ui-block-b width35"><div class="ui-bar ui-bar-e"><?php echo $row['name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?></div></div>
        <div class="ui-block-c"><div class="ui-bar ui-bar-e"><?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "" . $row['unit_price'];}?></div></div>
        <div class="ui-block-d"><div class="ui-bar ui-bar-e bold"><?php echo "" . $row['total_price'];?></div></div>
        <div class="ui-block-e width15"><div class="ui-bar ui-bar-e"><a href="#" id="<?php echo $row['basket_id'];?>" class="delete" data-role="button" data-icon="delete" data-iconpos="notext" data-inline="true">Icon only</a></div></div>

  </div>    

      <?php
				if($row['topping_details'])
				{
				?>
            <div data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d">
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

				}

				?>

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
      <?php

		    

				}		   

		    }

		  ?>

  <h1 class="main-heading"> Amount Details:</h1>

    

     <div class="ui-grid-a table-1-a">

    <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Gross Amount: </div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($gross_amount); ?></div></div>

     <?php 

						if($additionalfeesrow)

						{

						if($additionalfeesrow['sales_tax'])

						{

							$sale_tax = round_to_2dp($gross_amount * ($additionalfeesrow['sales_tax']/100));

							$gross_amount += $sale_tax;

						?>

     <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Sales Tax(<?php echo $additionalfeesrow['sales_tax'];?>%) : </div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo $sale_tax; ?></div></div>

     <?php 

						}

						if($additionalfeesrow['additional_fee'])

						{

							$gross_amount += $additionalfeesrow['additional_fee'];

						?>

    

     <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Additional Fee </div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($additionalfeesrow['additional_fee']);?></div></div>

    <?php 

						}

						if($additionalfeesrow['gratuity'])

						{

							$gross_amount += $additionalfeesrow['gratuity'];

						?>

       <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Gratuity</div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($additionalfeesrow['gratuity']);?></div></div>

     <?php

						}

						if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee'])

						{

							$gross_amount += $additionalfeesrow['delivery_fee'];

						?>

       <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Delivery Fees:</div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo round_to_2dp($additionalfeesrow['delivery_fee']); ?></div></div>

      <?php 

						}

						}

						?>

    <div class="ui-block-a"><div class="ui-bar ui-bar-e table-1-a-b" >Net Amount:</div></div>

    <div class="ui-block-c"><div class="ui-bar ui-bar-e table-1-a-b" >$ <?php echo $gross_amount; ?></div></div>

    </div>

    <a href="checkout.php" data-role="button"  data-ajax="false">Checkout</a> <a href="restaurantmenu.php" data-role="button" data-ajax="false">Add More Food</a> </div>

  <a href="index.php" data-role="button" data-theme="b">Back to Home</a>

  <!-- /content -->

</div>

<?php include("footer.php");?>

<!-----------------------To delete item, starts here--------------------------->

<script type="text/javascript"> 

// <![CDATA[

$(document).ready(function () {	

    $('a.delete').click(function() {		

				

        var id = $(this).attr('id');

						

		$.ajax({

			type: 'post',

			url: "<?php echo SITEURL;?>/ajax/user.php",

			data: 'deleteInBasket=' + id,

			cache: false,				

			success: function () {

				window.location.reload();

			}

		}); 

		

    });	

});



// ]]>

</script>

<!-----------------------To delete item, ends here--------------------------->

