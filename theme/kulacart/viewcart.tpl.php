<?php
  /**
   * Index
   * Kula cart 
   *  
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
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
<script src="<?php echo THEMEURL;?>/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo THEMEURL;?>/css/jquery-ui.css" />
<script src="<?php echo THEMEURL;?>/js/jquery.popupoverlay.js"></script>
<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--<div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> View Cart </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top">
    <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">
      <!-----View Cart Details----->
      <div class="span9 fit">
        <div class="row-fluid">
          <div class="span12 top_heading_strip"> View Your Shopping Cart </div>
        </div>
        <?php 
            $allproductrow = $product->AllProductInBasket();
			if($allproductrow){
        ?>
        <div class="span12 fit"><br />
          <table class="table table-bordered product-table-border table-bordered1" border="0" style="border:none !important;">
            <thead>
              <tr class="product-des-name">
                <th class="tacenter">Quantity</th>
                <th width="200">Item Description</th>
                <th class="tacenter">Unit Price</th>
                <!--<th>Quantity</th>-->
                <th class="tacenter">Total Amount</th>
                <th class="tacenter">Edit</th>
                <th class="tacenter">Delete</th>
              </tr>
            </thead>
            <?php 
				$gross_amount = "";
				foreach($allproductrow as $row){ 
					$ItemSize = $product->getItemSize($row['menu_size_map_id']);  //Item Size
					$gross_amount += $row['total_price'];
				?>
            <tbody>
              <tr>
                <td class="tacenter"><strong><?php echo $row['quantity'];?></strong></td>
                <td><strong><?php echo $row['name']; echo ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";  ?> </strong>
               <?php /*?> <br /><span class="itemdesc"><?php echo $row['description'];?></span><?php */?>
                </td>
                <td class="tacenter"><strong><?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "$ " . round_to_2dp($row['unit_price']);}?></strong></td>
                <?php /*?><td><strong><?php echo $row['quantity'];?></strong></td><?php */?>
                <td class="tacenter"><strong><?php echo "$ " . round_to_2dp($row['total_price']);?></strong></td>
                <td class="tacenter">
				  <?php if(isset($_SESSION['chooseAddress'])){ ?>						
                  <a href="<?php echo SITEURL;?>/?location#/product-<?php echo $row['product_id'];?>/sizeid-<?php echo $ItemSize['sizeid']; ?>-<?php echo $row['basket_id'];?>" >Edit</a>
                  <?php  } else {?>
                  <a href="<?php echo SITEURL;?>/#product-<?php echo $row['product_id'];?>-<?php echo $row['basket_id'];?>" class="ChanegeLocation">Edit</a>
                  <?php  } ?>
                </td>
                <!--<td>
                	<a href="#" id="<?php //echo $row['basket_id'];?>" title="Click to delete" class="delete">
                    	<img src="<?php //echo SITEURL;?>/images/close.png" alt="X" />
                    </a> 
                </td>-->
                <td class="tacenter">
                	<a href="#" id="<?php echo $row['basket_id'];?>" title="Delete" class="my_modal_open deleteIdValue" >
                      <img src="<?php echo SITEURL;?>/images/close.png" alt="X" />
                    </a> 
                </td>
              </tr>
            </tbody>
            <?php if($row['topping_details']){ ?>
            <tbody>
              <tr class="product-des-name2">
                <td>&nbsp;</td>
                <td colspan="6"><span> <a id="<?php echo $row['basket_id'];?>" class="imageDivLink"> Toppings & Extras <i class="icon-chevron-down"></i> </a> </span> </td>
              </tr>
            </tbody>
            <tbody id="showToggle<?php echo $row['basket_id'];?>">
              <?php 
				$toppingdetails = $row['topping_details'];				
				$toppingarray = explode("%%", $toppingdetails);
				//echo "<pre>"; print_r($toppingarray); exit(); 
				$match = 0;
				for($ct = 0; $ct < count($toppingarray); $ct++){				
					 $toppingrow = $toppingarray[$ct];
					 $toppingrow = explode("||", $toppingrow);					
					 $optionChoiceName = $product->showOptinChoicName($toppingrow[5]);  //option choice name	
					 $nexttopping = $toppingarray[$ct+1];
					 $nexttoppingrow = explode("||", $nexttopping);	
					  if($ct<count($toppingarray)){	
					 if($nexttoppingrow[3]==$toppingrow[3] && $match == 0){
						 echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
						 $match=1;
					 }
					 else if($ct==0){						 
						echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
					}
					else if($nexttoppingrow[3]!=$toppingrow[3]){						
						if($match==0){							
						  echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';								
						}
						else{							
								$match = 0;
						}
					 }
				 }
				 	  else {				 
					if($match==0){					
						echo '<tr><td>&nbsp;</td><td colspan="6" class="optionnamewithtopping">'.$product->geroptiontoppingname($toppingrow[3]).'</td></tr>';
					}	 
				}			 				
				?>               
              <tr>
                <td>&nbsp;</td>
                <td>
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
                </td>
                <?php /*?><td><?php if(!empty($toppingrow[1]) && $toppingrow[1]!=0.00){ echo "$ " . $toppingrow[1];}?></td>
                <td><?php if(!empty($toppingrow[1]) && $toppingrow[1]!=0.00){ echo $toppingrow[2];}?></td>
                <td><?php if(!empty($toppingrow[7]) && $toppingrow[7]!=0.00){ echo "$ " . $toppingrow[7];}?></td><?php */?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <?php 
				}
				?>
            </tbody>
            <?php 	
				} ?>
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
            <?php }
				?>
          </table>
        </div>
        <div class="span12 fit">
          <div class="heading-top2">Amount Details:</div>
        </div>
        <div class="separator2"></div>
        <div class="span12">
          <div class="row-fluid">
            <div class="span5">
              <div class="row-fluid">
                <div class="span7">Gross Amount : </div>
                <div class="span5">$ <?php echo round_to_2dp($gross_amount); ?></div>
              </div>
              <?php 
				if($additionalfeesrow)
				{
				if($additionalfeesrow['sales_tax'])
				{
					$sale_tax = round_to_2dp($gross_amount * ($additionalfeesrow['sales_tax']/100));
					$gross_amount += $sale_tax;
				?>
              <div class="row-fluid">
                <div class="span7">Sales Tax(<?php echo $additionalfeesrow['sales_tax'];?>%) :</div>
                <div class="span5">$ <?php echo round_to_2dp($sale_tax); ?></div>
              </div>
              <?php 
				}
				if($additionalfeesrow['additional_fee'])
				{
					$gross_amount += $additionalfeesrow['additional_fee'];
			  ?>
              <div class="row-fluid">
                <div class="span7">Additional Fee : </div>
                <div class="span5">$ <?php echo round_to_2dp($additionalfeesrow['additional_fee']);?></div>
              </div>
              <?php 
				}
				if($additionalfeesrow['gratuity'])
				{
					$gross_amount += $additionalfeesrow['gratuity'];
			  ?>
              <div class="row-fluid">
                <div class="span7">Gratuity : </div>
                <div class="span5">$ <?php echo round_to_2dp($additionalfeesrow['gratuity']);?></div>
              </div>
              <?php
				}
				if(isset($_SESSION['orderType']) && ($_SESSION['orderType'] != 'pick_up') && $additionalfeesrow['delivery_fee'])
				{
					$gross_amount += $additionalfeesrow['delivery_fee'];
			  ?>
              <div class="row-fluid">
                <div class="span7">Delivery Fees:</div>
                <div class="span5">$ <?php echo round_to_2dp($additionalfeesrow['delivery_fee']); ?></div>
              </div>
              <?php 
				}
				}
			  ?>
              <div class="row-fluid">
                <div class="span7"><b>Total Amount :</b></div>
                <div class="span5"><strong>$ <?php echo round_to_2dp($gross_amount); ?></strong></div>
              </div>
            </div>
            <div class="span7">
              <div class="span12 fit"></div>
              <div  class="span5">
               <div class="checkout">               
                <a style="cursor:pointer;" class="btn-2-2" href="<?php echo SITEURL; ?>/?location" title="Add More Order">ADD MORE FOOD</a>   
               <?php /*?><img src="<?php echo THEMEURL;?>/images/btn_addmorefood.png" alt="Add More Order" /><?php */?> 
               </div>
              </div>
              <div class="span6">
                <?php 	
					$webrow = $menu->checkFlow($websitenmae);
					if(isset($_SESSION['chooseAddress']) && $webrow['flow']=='1'){
						$url = SITEURL . "/checkout";
					}
					else{					
						$url = SITEURL . "/chooselocation";
					}
                  ?>
                <div class="checkout"> 
                   <a style="cursor:pointer;" class="btn-2-2" href="<?php echo $url; ?>" title="Checkout">CHECKOUT</a>                
					<?php /*?><img src="<?php echo THEMEURL;?>/images/btn_checkout2.png" alt="Check out" /><?php */?>  
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php 
			}
			else
			{
				echo "Your shopping cart is empty";	
			}
            ?>
        <div class="span12 fit">
          <div class="heading-top"  style="height:21px;"></div>
        </div>
      </div>
      <!-----Product Details END----->
      <!-----RIGHT SEACTION upto 5th, jan 2014----->
      <?php /*?><div class="span3 margin-left-16">
        <div class="span12 box2 fit">
          <div class="row-fluid myorder_headings"><span>ORDER SETTINGS</span> </div>
          <?php $location =  $product->LocationDetailsByDefoult($locationid); ?>
          <div class="">
            <div class="order_summary_tags">My Location :</div>
            <h5>
              <?php  echo $location['location_name'];?>
            </h5>
            <div class="order_summary_tags top_border">My Store</div>
            <h5>
              <?php  echo $location['address1'];?>
            </h5>
            <div class="order_summary_tags top_border">Service Method</div>
            <h5>
              <?php 
				if(isset($_SESSION['orderType']))
				{
					echo ($_SESSION['orderType'] == 'pick_up') ? "Pick Up" : "Delivery";
				}
				else
				{
					echo "Not applicable.";	
				}
				?>
            </h5>
            <div class="order_summary_tags top_border">Order Timing</div>
            <h5>
              <?php 
				if(isset($_SESSION['orderTime']))
				{
					echo $_SESSION['orderTime'];
				}
				else
				{
					echo "Not applicable.";	
				}
			?>
            </h5>
          </div>
        </div>
        <div class="span12 joinclub_img margin-top-30"> 
        	<a href="<?php echo SITEURL;?>/register">
            	<img src="<?php echo THEMEURL;?>/images/join_eclub.png" alt="" />
            </a> 
        </div>
      </div><?php */?>
      <!-----RIGHT END, 5th, jan 2014----->
      <!-----RIGHT SEACTION upto 7th, jan 2014----->
      <div class="span3 margin-left-16">
        <div class="span12 box2 fit">
          <div class="row-fluid myorder_headings"><span>ORDER SETTINGS</span> </div>
           <?php 
	  		$location =  $product->LocationDetailsByDefoult($locationid); 

			$webrow = $menu->checkFlow($websitenmae);	
		  ?>
          <div class="">
            <div class="order_summary_tags">Location           
			<?php 
                if($webrow['flow']=='1'){				
                    if($webrow['hybrid']==1){					
    
              ?>
            (<a href="javascript:void(0);" class="ChanegeLocation">Change</a>)
            <?php
                    } 
                }
            ?>
            </div>
            <h5>
              <?php  echo $location['location_name'];?>
            </h5>
            <?php /*?><div class="order_summary_tags top_border">My Store</div>
            <h5>
              <?php  echo $location['address1'];?>
            </h5><?php */?>
            <div class="order_summary_tags top_border">Service Method            
            <?php 

		  	if($webrow['flow']=='1'){
				 if($webrow['hybrid']==1){			

		    ?>
        	(<a href="javascript:void(0);" class="ChanegeLocation">Change</a>)
			<?php
                    }
                }
            ?>
            </div>
            <h5>
            <?php
			if(isset($_SESSION['orderType'])){ echo ($_SESSION['orderType'] == 'pick_up') ? "Pick Up" : "Delivery";	}

			else{ echo "Not applicable."; }
			?>
            </h5>
            <div class="order_summary_tags top_border">Order Timing
            <?php 
		  		if($webrow['flow']=='1'){

					if($webrow['hybrid']==1){
		   ?>
           (<a href="javascript:void(0);" class="ChanegeLocation">Change</a>)
            <?php 
		  		}
			} 
		    ?>
            </div>
            <h5>
              <?php 
				if(isset($_SESSION['orderTime'])){	echo $_SESSION['orderTime']; }
				else{ echo "Not applicable.";	}
			 ?>
            </h5>
          </div>
        </div>
        <div class="span12 joinclub_img margin-top-30"> 
        	<a href="<?php echo SITEURL;?>/register">
            	<img src="<?php echo THEMEURL;?>/images/join_eclub.png" alt="" />
            </a> 
        </div>
      </div>
      <!-----RIGHT END, 7th, jan 2014----->
      <div id="content-top-shadow"></div>
      <div id="content-bottom-shadow"></div>
      <div id="content-widget-light"></div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<!-- Add content to modal -->
  <div id="my_modal" style="display:none"> 
  	 <div class="dialog_heading">Delete Item </div> 	
     <p>
     	<span style="float: left; margin: 0 7px 20px 0;"></span>
        Are you sure to delete this item.<br/>
        This action can not be undone !!
     </p> 
     <a title="Delete" class="delete bold" id="d_address1" style="cursor:pointer">Delete</a>  
     <a title="Close" class="my_modal_close bold" style="cursor:pointer">Close</a>
  </div>
<!-- Add content to modal -->
<script language="javaScript">
  $(document).ready(function(){
	  
	  $(".imageDivLink").click(function(){
		 var id =  $(this).attr('id');		
		 
	     $("#showToggle"+id).toggle();
		  
	  });	  
  });

</script>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {	
	
	$(".deleteIdValue").click(function(){	
	
		del_val = $(this).attr("id");		
		var element1 = document.getElementById('d_address1');		
		element1.setAttribute("name", del_val); 		
		
	 }); 
	
	$("a.delete").click(function(){	
		
		var deleteId = $("#d_address1").attr("name");		
		$("#my_modal").css({display: "none"});
		$("#smallLoader").css({display: "block"});				
		 $.ajax({
				type: 'post',
				url: "<?php echo SITEURL;?>/ajax/user.php",
				data: 'deleteInBasket=' + deleteId,
				cache: false,				
				success: function () {					
					$("#smallLoader").css({display: "none"});	
					window.location.reload();
				}
			});
		 }); 
		 
   $("a.ChanegeLocation").click(function(){
		$.ajax({
			type:"POST",
			url:"<?php echo SITEURL;?>/ajax/user.php",
			data:{ChangeLocation:"1"},
			success: function(theResponse){
				window.location.href= SITEURL+"/?location";
			}

		});
	});	
});

 $(function() {
      $('#my_modal').popup();
    });
// ]]>
</script>
