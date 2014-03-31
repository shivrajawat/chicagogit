<?php include("header.php"); ?>
<?php
if(isset($_GET['Item']))
{
 $productrow = $menu->productByItem($_GET['Item']); ?>
<?php $option = $menu->optionListByItem($_GET['Item']); ?>
<?php 
$webrow = $menu->checkFlow($websitenmae);
if($webrow['flow']=='1')
{
	if(isset($_SESSION['chooseAddress']))
	{
		$locationid = $_SESSION['chooseAddress'];
		if(!$locationid)
		{
			header("location:chooselocation.php");
		}
	}
}
else
{
	$locationrow = $menu->locationIdByMenu($websitenmae);
	$locationid = $locationrow['location'];
}
if(empty($locationid))
{
	header("Location:index.php");
}
?>
<?php
 if(isset($_POST['action']) && ($_POST['action']== "addToBasket"))
 {	
	  $sessionID = SESSION_COOK;
	  if(empty($_POST['qty']) || !trim($_POST['qty'])):
			$error['qty'] = "<label  class=\"error\">Please provide your Item qty.</label >";
		endif;
	 if(!empty($_POST['qty'])):
		if($_POST['qty']==0)
		{
			$error['qty'] = "<label  class=\"error\">should greater than 0</label >";
		}
		elseif(!is_numeric($_POST['qty']))
		{
			$error['qty'] = "<p class=\"error\">qty should number and digits</p>";
		}
	 endif;
	 	  
		$productID	= $_POST['productID']; 
		$menu_size_map_id = (empty($_POST['menu_size_map_id'])) ?  "0" : $_POST['menu_size_map_id'];
		$qty = (empty($_POST['qty'])) ?  "0" : $_POST['qty'];
		$additional_notes = $_POST['additional_notes'];
		$option_topping_id = $_POST['topping'];
		$item_price = $_POST['item_price'];		
	 if(empty($error)):
	       $data = array(
				  'productID'=> $productID,
				  'menu_size_map_id'=> $menu_size_map_id,
				  'qty'=> $qty, 
				  'productPrice'=> $item_price,
				  'basketSession' => $sessionID,
				  'additional_notes' => $additional_notes,
				  'created_date'=>"NOW()"
			  );
		      $db->insert("res_baskets", $data);
		      $insert_id = $db->insertid();
		   	  if(!empty($insert_id))
			  {
					foreach($option_topping_id as $val)
					{
						$option_id  = $val['id'];
						$option_topping_id = $val['val'];
						$option_choice_id = $val['option_choice_id'];
						$topping_length = count($val['val']);
						$topping_price  = $val['price'];			
						
						$option_topping_name = $val['option_topping_name'];								
						if($topping_length > 0)
						{
							if(!is_array($option_topping_id))
							{									
							  $data1 = array(
									  'product_id'=> $productID,
									  'option_topping_id' => $option_topping_id,
									  'option_topping_name' =>$option_topping_name,
									  'option_choice_id' => $option_choice_id,
									  'option_id'=>	$option_id,
									  'price'=>	$topping_price,		
									  'qty'=> $qty,		 
									  'basketID'=>$insert_id,
									  'basketSession' => $sessionID
								  );
								$db->insert("res_basket_topping", $data1);
							}				
							else
							{
								for($i=0; $i< $topping_length; $i++)
								{
									 $data1 = array(
										  'product_id'=> $productID,
										 'option_topping_id' => $option_topping_id[$i],
										  'option_topping_name' =>$option_topping_name[$i],
										  'option_choice_id' => $option_choice_id[$i],
										  'option_id'=>	$option_id,		
										  'price'=>	$topping_price[$i],	
										  'qty'=> $qty,	 
										  'basketID'=>$insert_id,
										  'basketSession' => $sessionID
									  );		
									$db->insert("res_basket_topping", $data1);
								}					
							}				
						}
					}
			}	  
			  header("Location:restaurantmenu.php");
	 endif;
 } 
else{
	$error['qty'] = "";
	}
?> 
<?php $location =  $product->LocationDetailsByDefoult($locationid); ?>
<?php $sizemap = $menu->sizeByItem($productrow['id']) ?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
<div data-role="content">
  <div class="ui-grid-a">
    <div class="ui-block-a">
      <div class="ui-bar ui-bar-e ui-bar-e-menu" >
        <h1 class="main-heading"> Menu </h1>
      </div>
    </div>
    <div class="ui-block-b">
      <div class="ui-bar ui-bar-e ui-bar-e-ba" >       
        <div> <a href="view-cart.php" class="img_viewcart" data-ajax="false"  data-role="button" data-theme="c" data-inline="true">View Cart (<?php echo $product->Totalbasketitem();?>)</a> </div>
      </div>
    </div>
  </div>
  <div class="cart-content" style="display:none">
    <?php             
            $allproductrow = $product->AllProductInBasket();           
			if($allproductrow)
			{
            ?>
    <table data-role="table" id="table-custom-2" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Columns to display..." data-column-popup-theme="a">
      <thead>
        <tr class="ui-bar-d">
          <th>Item Name</th>
          <th data-priority="3">Unit Price</th>
          <th data-priority="1"><abbr title="Rotten Tomato Rating">Qty</abbr></th>
          <th data-priority="5">Delete</th>
        </tr>
      </thead>
      <tbody>
        <?php 
		$gross_amount = "";
				$i=1;
				foreach($allproductrow as $row){
					$gross_amount += $row['total_price'];
				?>
        <tr>
          <td><a href="" data-rel="external"><?php echo $row['name']; ?></a></td>
          <td><?php if(!empty($row['unit_price']) && $row['unit_price']!=0.00){echo "$ " . $row['unit_price'];}?></td>
          <td><?php echo $row['quantity'];?></td>
          <td><a href="#" id="<?php echo $row['basket_id'];?>" class="delete" data-role="button" data-icon="delete" data-iconpos="notext" data-inline="true">Icon only</a></td>
        </tr>
        <?php
		$i++; 
				}
			}
			?>
        <tr>
          <td colspan="4"><a href="<?php echo SITEURL; ?>/mobile/view-cart.php" data-ajax="false" data-role="button">View Cart</a> </td>
        </tr>
        <tr>
          <td colspan="4"><a href="<?php echo SITEURL; ?>/mobile/checkout.php" data-ajax="false" data-role="button">Checkout</a> </td>
        </tr>
      </tbody>
    </table>
  </div>
  
  <form name="kuladd" id="kuladd" action="" method="post"  class="ui-corner-all" data-ajax="false">
    <div class="ui-grid-a">
      <div class="product-name-q ui-block-a">
        <h4 class="titlemain"><?php echo $productrow['item_name'];?></h4>
      </div>
      <div class="product-name-price ui-block-b">
        <div style="text-align:center;" id="sizedprice">
          <h4 class="price_at_topping">
          $<span id="getTopPriceSpan">
           <?php  if($productrow['item_type']=='1')
				 {
					if(!empty($_GET['sizedid']))
					{	
						foreach($sizemap as $sizrow):	
								if($_GET['sizedid']==$sizrow['sizeid'])
								{
									echo $sizrow['price'];
								}							
						endforeach; 
					}	
				}
			?>
            <?php if($productrow['item_type']=='0'){
                echo $productrow['price'];
			?>
             </span>
          </h4>
          <input type="hidden" name="item_price" value="<?php echo $productrow['price'];?>"  />
          <?php  }?>
        </div>
      </div>
    </div>
    <div class="ui-grid-solo">
      <div class="ui-block-a" style="margin:10px 0;">
        <?php  echo ($productrow['item_description'])? $productrow['item_description'] : ""; ?>
        <br/>
      </div>
    </div>
    <div class="ui-grid-solo">
      <!--Current Item Price  if item is sized -->
      <?php if($productrow['item_type']=='1'){ ?>
      <div class="ui-block-a" >
        <div class="form-group">
          <label for="inputEmail1" class="span12 col-lg-2 control-label titlemain bold"><strong>size:</strong></label>
          <div class="12">
		<?php 
        $aa= 1;
        foreach($sizemap as $srow):
			if(!empty($_GET['sizedid']))
			{
				if($_GET['sizedid']==$srow['sizeid'])
				{
					$checked = 'checked="checked"';
				}
				else
				{
					$checked = "";
				}
			}	
        ?>
            <div class="span4">
            <input type="radio" name="menu_size_map_id" value="<?php echo $srow['menusizemapid'];?>"  id="menu_size_map_id" <?php echo $checked;?>  style="visibility:hidden"/>
              <input name="item_price" id="checkbox-<?php echo $aa;?>a" type="radio" value="<?php echo $srow['price'];?>" rel="<?php echo $srow['sizeid'];?>" data-sizedid="<?php echo $srow['sizeid'];?>" class="sized_price" <?php echo $checked;?> required>
              <label for="checkbox-<?php echo $aa;?>a"><?php echo $srow['size_name'];?></label>
            </div>
            <?php $aa++; endforeach; ?>           
            <div class="clr"></div>
          </div>
        </div>
      </div>
      <?php } ?>
      <!----------------------Topping -------------------------------------------------------------->
      <div class="ui-block-a" id="topping">
        <?php if($option){ 
		
	  	 	$num = 1;
	   		foreach($option as $oprow){
			$pricediff = $menu->getoptionpricediff($_GET['sizedid'],$oprow['menu_option_map_id']);
			if($oprow['is_normal_option']=='0'){
			$mainoption = $menu->optionMain($productrow['id']);
			        
       $mainoption = $menu->optionMain($productrow['id']) ;
	  if($oprow['display_option'] ==1)
	  {
		 if($mainoption): ?>
        <?php foreach($mainoption as $mrow):?>
        <div class="form-group">
          <h4><?php echo $mrow['option_name'];?>:</h4>
          <div class="col-lg-10">
            <?php  $childoption = $menu->optionchild($mrow['option_id']); ?>
            <select class="form-control" onchange="changetooping(this.value,<?php echo $mrow['option_id']; ?>)" required>
              <?php $childoption = $menu->optionchild($mrow['option_id']); ?>
              <option value="">Please Select</option>
              <?php foreach($childoption as $chrow):?>
              <option value="<?php echo $chrow['option_id'];?>"><?php echo $chrow['option_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <div class="ui-block-a" id="topping<?php echo $mrow['option_id']; ?>"> </div>
        <?php endforeach; ?>
        <?php endif;  
	  }
	 else
	 { ?>
      <?php 
		$option_visiblity ="";
		if($oprow['hide_option']==1){ // if hide option is selected then no dispaly this option 
			$option_visiblity = 'style = "visiblity: hidden;position:absolute; left:-1500px;"';  
		}
		else
		{
			$option_visiblity ="";
		}
		// id hide instruction is checked then option instruction hide ony display toping list of this option 
		if($oprow['hide_instruction']==1)
		{
			$hide_instruction = 'style = "visiblity: hidden;position:absolute; left:-1500px;"';  
		} 
		else
		{
			$hide_instruction = "";
		}
		// Click to open is published then by default is hide option, onclick to display option and topping 
		if($oprow['click_to_open']==1)
		{
			$clicktoopen =  'style="display:none"';
			echo '<a id="'.$oprow['option_id'].'" class="imageDivLink">
			<h3 class="notnormaltoppings" '.$option_visiblity.''. $hide_instruction.'>'. $oprow['instruction'].'<i class="icon-chevron-down"></i></h3></a>';
		} 
		else 
		{
			$clicktoopen =  ''; 
			echo '<h3 class="notnormaltoppings" '. $option_visiblity.'  '. $hide_instruction.'>'.$oprow['instruction'].':</h3>'; 
		} ?>             

       <div id="showToggle<?php echo $oprow['option_id'];?>" <?php echo $clicktoopen;?>>
      <?php echo (cleanSanitize($oprow['instruction_desc']))? "<div class=\"span11 fit itemdesc\" ".$hide_instruction.">".cleanOut($oprow['instruction_desc'])."</div>" : "";  ?>
    
       <div class="span12" <?php echo $option_visiblity; ?>>
        <?php 			
			   $childoption = $menu->optionchild($oprow['option_id']); 			   
			   $numone = $num+1;
			   foreach($childoption as $chrow){
			   $optiontopping = $menu->gettoopinglist($chrow['option_id']);
			    if($optiontopping){	
					$num=$num+1;
					foreach($optiontopping as $oprow){ ?>
        <div class="span6">
		<?php 
        $option_visiblity ="";		  
        if($oprow['hide_option']==1)
        {
        	$option_visiblity = 'style = "visiblity: hidden;position:absolute;left:-1500px;"';  
        }
        else
        {
        	$option_visiblity ="";
        }
        
        if($oprow['hide_instruction']==1)
        {
        	$hide_instruction = 'style = "visiblity: hidden;position:absolute; left:-1500px;"';  
        } 
        else
        {
			$hide_instruction = "";
        }        
        if($oprow['click_to_open']==1)
		{
        	$clicktoopen =  'style="display:none"';
			echo '<a id="'.$oprow['option_id'].'" class="imageDivLink">
       		<h3 class="notnormaltoppings" '.$option_visiblity.''. $hide_instruction.'>'. $oprow['instruction'].'<i class="icon-chevron-down"></i></h3></a>';
        } 
		else 
		{ 
			$clicktoopen =  ''; 
        	echo '<h3 class="notnormaltoppings" '. $option_visiblity.'  '. $hide_instruction.'>'.$oprow['instruction'].':</h3>'; 
		} ?>             

       <div id="showToggle<?php echo $oprow['option_id'];?>" <?php echo $clicktoopen;?>>
      <?php echo (cleanSanitize($oprow['instruction_desc']))? "<div class=\"span11 fit itemdesc\" ".$hide_instruction.">".cleanOut($oprow['instruction_desc'])."</div>" : "";  ?>
          <div class="span12 fit"  <?php echo $option_visiblity; ?>>
          <input type="hidden" name="topping[<?php echo $numone ;?>][id]" value="<?php echo $oprow['option_id'];?>" />
	<?php 
     $toppingrow = $menu->topingByItem($oprow['option_id']);
     $optionname = $menu->getOptionChoice($oprow['option_id']);
     $HidePriceInOption = $product->HidePriceInOption($locationid);	
     
      if($oprow['option_type']=='r'){
        $toppingrow = $menu->topingByItem($oprow['option_id']);				
        if($toppingrow){	
        $chooseid = 1;		
        $isdefaultflag=0;	
        foreach($toppingrow as $tprow){  
           if(!empty($tprow['price']) && $tprow['price']!=0.00)
            { 
                    if($oprow['size_affect_price']==1)
                    {
                        $priceaffect = (($pricediff['price_diff']*$tprow['price'])/100)+$tprow['price'];
                        $toppingprice = round_to_2dp($priceaffect);
                    }	
                    else
                    {
                        $toppingprice = round_to_2dp($tprow['price']);
                    }	
            }
            $isdefault = "";
            if($tprow['is_default']=='1')
            {
                if($isdefaultflag==0){  
                    $isdefault = 'checked="checked"';
                    $isdefaultflag = 1;
                    
                }
            }						
?>
        <fieldset data-role="controlgroup">
        <?php 
			if($HidePriceInOption==0){
				$toppingprices = ($toppingprice) ? " $".$toppingprice : "";
			}
      ?>
        <input type="radio" value="<?php  echo $tprow['option_topping_id'];?>" name="topping[<?php echo $numone;?>][val]"  id="radio-choice-<?php echo $numone."_".$chooseid;?>" class="showPriceRadio<?php echo $numone;?> inlineCheckbox<?php echo $numone."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>"  <?php echo $isdefault;?> <?php if($oprow['min_choise']>0){ echo "required "; }  echo $isdefault;?> >
        <label for="radio-choice-<?php echo $numone."_".$chooseid;?>"><?php  echo $tprow['topping_name']." ".$toppingprices;?></label>
</fieldset>  
<script type="text/javascript">	
  jQuery(document).ready(function($) {				
				$(document).ready(function() {	
				if($('.inlineCheckbox<?php echo $numone."_".$chooseid; ?>').is(':checked')) {
						 $("input#hidden<?php echo $tprow['option_topping_id']."_2";?>" ).prop( "checked", true );
						 $("input#hidden<?php echo $tprow['option_topping_id']."_1";?>" ).prop( "checked", true );
        				 $(".mychoiceid<?php echo $numone."_".$chooseid; ?>").show();
						   <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>	
  					 }
					 						
					$(".inlineCheckbox<?php echo $numone."_".$chooseid; ?>").click(function(event) {
						if ($(this).is(":unchecked"))						
							$(".mychoiceidNew").hide();
						else
							$(".mychoiceidNew").hide();					
						  	$("#mychoiceid<?php echo $numone."_".$chooseid; ?>").show();		
					});									
				});
		});
			</script> 
            <div style="position:relative; margin-left:25px; display:none" id="mychoiceid<?php echo $numone."_".$chooseid; ?>"  class="mychoiceidNew">
            <?php
		   $countchoose = count($optionname);
		  if($countchoose>0 && $countchoose==1)
		  {
		  	 foreach($optionname as $sss){
			?>
            <input type="checkbox" name="topping[<?php echo  $numone;?>][option_choice_id][]" value="<?php echo $sss['option_choice_id'];?>" checked="checked" style="visibility: hidden;position:absolute; left:-999px;"/>
            <?php } }
		  else 
		  {
		   foreach($optionname as $sss)
		   { ?>
            <label class="choicetoppings span1 <?php echo $sss['choice_name'];?>" rel="tooltip" title="<?php echo $sss['choice_name'];?>" id="blah">
            <input type="radio" name="topping[<?php echo  $numone;?>][option_choice_id]" value="<?php echo $sss['option_choice_id'];?>" >
             <label for="radio-choice-1"><?php echo ($sss['choice_name']);?></label>
            <span class=""></span>
            </label>
            <?php 
			}
		  } ?>
          </div>     
         <input type="radio" id="hidden<?php  echo $tprow['option_topping_id']."_1";?>" name="topping[<?php  echo $numone;?>][price]"  value="<?php  echo $toppingprice;?>" style="visibility: hidden;" />
         <input type="radio" id="hidden<?php  echo $tprow['option_topping_id']."_2";?>" name="topping[<?php  echo $numone;?>][option_topping_name]" value="<?php  echo $tprow['topping_name'];?>" style="visibility: hidden;" />
            <?php 
			$chooseid++;
		    } 
		  }?>
          <input type="hidden" id="radio-previous<?php echo $numone; ?>" name="radio-previous<?php echo $numone; ?>" />
          <script type="text/javascript">
			jQuery(function($) {
			 if($('.showPriceRadio<?php echo $numone; ?>').is(':checked')) {
			var var1 = document.getElementById("getTopPriceSpan").innerHTML;

			var radio_pre_val = $("#radio-previous<?php echo $numone; ?>").val();
			if (radio_pre_val!=''){	
				var sumss = Number(var1)-Number(radio_pre_val);
				var sum =sumss.toFixed(2);
			}	
			var val1 = jQuery( '.showPriceRadio<?php echo $num; ?>:checked' ).attr("data-title");
			if(radio_pre_val!=''){
				var sumss = Number(sum)+Number(val1);
				var sum2 =sumss.toFixed(2);	
			}
			else{	
				var sumss = Number(var1)+Number(val1);
				var sum2 =sumss.toFixed(2);
			}
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum2;
			document.getElementById("radio-previous<?php echo $numone; ?>").setAttribute("value",val1);
			}
		  		$(".showPriceRadio<?php echo $numone; ?>").click(function(){
					
				    var var1 = document.getElementById("getTopPriceSpan").innerHTML;
	
				    var radio_pre_val = $("#radio-previous<?php echo $numone; ?>").val();
					if (radio_pre_val!=''){	
						var sumss = Number(var1)-Number(radio_pre_val);
						var sum =sumss.toFixed(2);
					}	
					var val1 = $(this).attr("data-title");	
					if(radio_pre_val!=''){
						var sumss = Number(sum)+Number(val1);
						var sum2 =sumss.toFixed(2);	
					}
					else{	
						var sumss = Number(var1)+Number(val1);
						var sum2 =sumss.toFixed(2);
					}
					document.getElementById("getTopPriceSpan").innerHTML='';
					document.getElementById("getTopPriceSpan").innerHTML=sum2;
					document.getElementById("radio-previous<?php echo $numone; ?>").setAttribute("value",val1);
					});	
			});
		 </script>
		 <?php }
				 //*****************************If option type is "Checkbox"***************************************************/
				elseif($oprow['option_type']=='c'){			
					if($toppingrow){					
						$chooseid = 1;			
						foreach($toppingrow as $tprow){
							$toppingprice  = "";
							if(!empty($tprow['price']) && $tprow['price']!=0.00)
							{	
								if($oprow['size_affect_price']==1){
									$priceaffect = (($pricediff['price_diff']*$tprow['price'])/100)+$tprow['price'];
									$toppingprice = round_to_2dp($priceaffect);
								}	
								else {								
									$toppingprice = round_to_2dp($tprow['price']);
								}	
							}
							$isdefault = "";
							if($oprow['is_default']=='1')
							{
								$isdefault = 'checked="checked"';
							}
							else
							{
								if($tprow['is_default']=='1')
								{
									$isdefault = 'checked="checked"';
								}
								
							}	 
							 	?>
                          <div class="span12"> 
                           <fieldset data-role="controlgroup">
					  <input type="checkbox" name="topping[<?php echo $numone;?>][val][]" value="<?php echo $tprow['option_topping_id'];?>"  class="showPrice inlineCheckbox<?php echo $numone."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" data-maxlength="<?php echo $tprow['max_choise'];?>" data-minlength="<?php echo $tprow['min_choise'];?>"  <?php if($oprow['min_choise']>0){ echo "required"; } ?> id="checkbox-<?php echo $chooseid;?>a" <?php echo $isdefault; ?>/>
                      <?php 
				if($HidePriceInOption==0){
				
					$toppingprices = ($toppingprice) ? " $".$toppingprice : "";
				}
			   ?>
    <label for="checkbox-<?php echo $chooseid;?>a"><?php echo $tprow['topping_name'].' '.$toppingprices;?></label>
</fieldset>
                      <input type="checkbox" id="chosse_<?php echo $numone."_".$chooseid;?>" name="topping[<?php echo  $numone;?>][option_choice_id][]" value="" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
					  <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo  $numone;?>][option_topping_name][]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
					  <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_1";?>" name="topping[<?php echo $numone;?>][price][]"  value="<?php echo $toppingprice;?>" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
					</div> 
                          <script type="text/javascript">	
					jQuery(function($) {
					$(document).ready(function() {	
					 if($('.inlineCheckbox<?php echo $numone."_".$chooseid; ?>').is(':checked')) {
						 $("input#hidden<?php echo $tprow['option_topping_id']."_2";?>" ).prop( "checked", true );
						 $("input#hidden<?php echo $tprow['option_topping_id']."_1";?>" ).prop( "checked", true );
        				 $(".mychoiceid<?php echo $numone."_".$chooseid; ?>").show();
						   <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>	
  					 }
					$(".inlineCheckbox<?php echo $numone."_".$chooseid; ?>").click(function(event) {
						if ($(this).is(":unchecked"))
						{
						  $(".mychoiceid<?php echo $numone."_".$chooseid; ?>").hide();
						  <?php  
						   foreach($optionname as $ss)
						   {
								if(strtolower($ss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").removeAttribute("value",<?php echo $ss['option_choice_id'];?>);
								//$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", flase );
								  <?php }
						   }
						   ?>
						 }
						else
						{
						  $(".mychoiceid<?php echo $numone."_".$chooseid; ?>").show();
						   <?php  
						   foreach($optionname as $ss)
						   {
								if(strtolower($ss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",<?php echo $ss['option_choice_id'];?>);
								$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>
						 }
						});	
					});	
					});			
			</script>
                          <div style="position:relative; margin-left:25px; <?php if($oprow['is_default']=='1'){?> display:block; <?php } else { ?> display:none; <?php } ?>"  class="mychoiceid<?php echo $numone."_".$chooseid; ?>">
                    <?php
			  $countchoose = count($optionname);
			  if($countchoose>0 && $countchoose==1)
			  { 
		        foreach($optionname as $ss){?>
                <input type="checkbox" name="topping[<?php echo  $numone;?>][option_choice_id][]" value="<?php echo $ss['option_choice_id'];?>" style="visibility: hidden; position:absolute; left:-999px;" checked="checked"/>
                <?php } 
			 }
			 else
			 {
			  foreach($optionname as $ss){	?>
                <label class="choicetoppings span1 <?php echo strtolower($ss['choice_name']);?>"  rel="tooltip" title="<?php echo $ss['choice_name'];?>" id="blah">
                    <input type="radio" name="option_choice<?php echo $numone."_".$chooseid;?>" value="<?php echo $ss['option_choice_id'];?>" class="id_<?php echo $numone."_".$chooseid;?>" id="dd_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>" <?php if(strtolower($ss['choice_name'])=='whole'){ ?> checked="checked" <?php } ?> data-choicename="<?php echo $ss['choice_name'];?>">
                    <label for="radio-choice-1"><?php echo ($ss['choice_name']);?></label>
                    <span id="span_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>" class="<?php if(strtolower($ss['choice_name'])=='whole'){ echo "active"; }?>">
                    </span>          
                 </label>
                <script type="text/javascript">
				jQuery(function($) {
					    <?php if($oprow['is_default']=='1'){ if(strtolower($ss['choice_name'])=='whole'){?>
						document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",<?php echo $ss['option_choice_id'];?>);
						$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
						  <?php } }						 
						  ?>					  
				    	$(".id_<?php echo $numone."_".$chooseid;?>").click(function(){	
						var value1 = 	$(this).val();
							<?php if($oprow['is_special_calc']=='1')
						{?>						
							var toppingprice = $(this).attr('data-choicename');
							if(toppingprice=='H1' || toppingprice=='H2')
							{
								var choiseprice = '<?php echo $toppingprice/2;?>';
								document.getElementById("hidden<?php echo $tprow['option_topping_id']."_1";?>").setAttribute("value",choiseprice);
							}
							else
							{
								var choiseprice = '<?php echo $toppingprice;?>';
								document.getElementById("hidden<?php echo $tprow['option_topping_id']."_1";?>").setAttribute("value",choiseprice);							
							}
						<?php } ?>	
						document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",value1);	
						$( "input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );		
						
						
						if($("#dd_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>").is(":checked"))
						{
							$("#span_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>").addClass("active");
						}
						else 
						{
							$("#span_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>").removeClass("active");
						}
					});												
				});
			</script>
                <?php }
			 }
			 ?>   
             </div>
			 <?php 
			 	$chooseid++; 
					  }
					}
				  } 	
				 //******************************If option type is "Dropdown box"**********************************************/	
				else {
			    if($toppingrow){
			
		?>
          <label class="span6 checkbox inline fit">
          <select name="toppingdetailsbind<?php echo $numone; ?>" id="toppingdetailsbind<?php echo $numone; ?>" class="toppingdetailsbind<?php echo $numone;?>"  <?php if($oprow['min_choise']>0){ echo "required"; } ?>>
          <option value="">--Select--</option>
            <?php  foreach($toppingrow as $trow): 
			
			$toppingprice  = "";
					if(!empty($trow['price']) && $trow['price']!=0.00)

					{	
						 	if($oprow['size_affect_price']==1)
							{
								$priceaffect = (($pricediff['price_diff']*$trow['price'])/100)+$trow['price'];
								$toppingprice = round_to_2dp($priceaffect);
							}	
							else
							{
								$toppingprice = round_to_2dp($trow['price']);
							}	
					}	
			?>
            <option value="<?php echo $trow['option_topping_id'];?>" data-topping-price="<?php echo $toppingprice;?>" data-topping-name="<?php echo $trow['topping_name'];?>">				<?php 
			if($HidePriceInOption==0)
				{
					$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
				}
			$toppingprices = ($toppingprice) ? "("."$".$toppingprice.")" : "";
			echo $trow['topping_name'].$toppingprices;?></option>
            <?php endforeach;?>
          </select>
           </label>
          <input type="radio" id="bindvalue<?php echo $numone."_1";?>" name="topping[<?php echo $numone;?>][price]"  value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" id="bindvalue<?php echo $numone."_2";?>" name="topping[<?php echo $numone;?>][option_topping_name]" value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" value="" name="topping[<?php echo $numone;?>][val]"  style="visibility: hidden;position:absolute; left:-999px;" id="bindvalue<?php echo $numone."_3";?>">     
          <input type="hidden" id="select_previous_val<?php echo $numone; ?>" name="select_previous_val<?php echo $numone; ?>" />
         
          <script type='text/javascript'>
			jQuery(function($) {
				$('#toppingdetailsbind<?php echo  $numone; ?>').change(function() {
					var id = $(this).val();
					var toppingprice = $('option:selected', "#toppingdetailsbind<?php echo  $numone; ?>").attr('data-topping-price');
					var toppingname = $('option:selected', "#toppingdetailsbind<?php echo  $numone; ?>").attr('data-topping-name');
					
					document.getElementById("bindvalue<?php echo $numone."_1";?>").setAttribute("value",toppingprice);
					$("input#bindvalue<?php echo $numone."_1";?>" ).prop( "checked", true );
					document.getElementById("bindvalue<?php echo $numone."_2";?>").setAttribute("value",toppingname);
					$("input#bindvalue<?php echo $numone."_2";?>" ).prop( "checked", true );
					document.getElementById("bindvalue<?php echo $numone."_3";?>").setAttribute("value",id);
					$("input#bindvalue<?php echo $numone."_3";?>" ).prop( "checked", true );
				});
			});
			</script>
             <script type="text/javascript">
			 
			jQuery(function($){
				
		  		$('.toppingdetailsbind<?php echo $numone; ?>').change(function(){
					
					var var1 = document.getElementById("getTopPriceSpan").innerHTML;	
					var radio_pre_val = $("#select_previous_val<?php echo $numone; ?>").val();
					
					if (radio_pre_val!=''){	
						var sumss = Number(var1)-Number(radio_pre_val);	
						var sum =sumss.toFixed(2);	
		
					}
			
					var val1 = $('option:selected', ".toppingdetailsbind<?php echo $numone; ?>").attr('data-topping-price');
					//var val1 = $(this).attr("data-topping-price");
						if(radio_pre_val!=''){
							var sumss = Number(sum)+Number(val1);
							var sum2 =sumss.toFixed(2);	
						}
						else{	
							var sumss = Number(var1)+Number(val1);
							var sum2 =sumss.toFixed(2);
						}
							document.getElementById("getTopPriceSpan").innerHTML='';
							document.getElementById("getTopPriceSpan").innerHTML=sum2;
							document.getElementById("select_previous_val<?php echo $numone; ?>").setAttribute("value",val1);
						});	
					});
		 </script>
          <?php
		    }
			
		  }  
			?>
          </div>
          </div>
          <div class="clr"></div>
        </div>
        <?php  }
			 } 
				$numone++; 
			   }
	 		  ?>
      </div>
      </div>
	 <?php }	 
	 }
	 else
	 {	  
		   $option_visiblity ="";		  
		  if($oprow['hide_option']==1){
			$option_visiblity = 'style = "visiblity: hidden;position:absolute;left:-1500px;"';  
		  }
		  else
		  {
		  	$option_visiblity ="";
		  }
		  ?>
        <?php

               if($oprow['hide_instruction']==1)
				  {
				  	$hide_instruction = 'style = "visiblity: hidden;position:absolute; left:-1500px;"';  
				   } 
				   else
				   {
				   		$hide_instruction = "";
				   }
				?>
             <?php if($oprow['click_to_open']==1){
			  		$clicktoopen =  'style="display:none"';
              echo '<a id="'.$oprow['option_id'].'" class="imageDivLink">
			   <h3 class="notnormaltoppings" '.$option_visiblity.''. $hide_instruction.'>'. $oprow['instruction'].'<i class="icon-chevron-down"></i></h3></a>';
			  } else { $clicktoopen =  ''; 
			  echo '<h3 class="notnormaltoppings" '. $option_visiblity.'  '. $hide_instruction.'>'.$oprow['instruction'].':</h3>'; } ?>             

       <div id="showToggle<?php echo $oprow['option_id'];?>" <?php echo $clicktoopen;?>>
      <?php echo (cleanSanitize($oprow['instruction_desc']))? "<div class=\"span11 fit itemdesc\" ".$hide_instruction.">".cleanOut($oprow['instruction_desc'])."</div>" : "";  ?>
        <div class="span12 fit" <?php  echo $option_visiblity; ?>>
          <input type="hidden" name="topping[<?php echo $num ;?>][id]" value="<?php echo $oprow['option_id'];?>" />
        <?php 
		$toppingrow = $menu->topingByItem($oprow['option_id']);
		$optionname = $menu->getOptionChoice($oprow['option_id']);		
			 if($oprow['option_type']=='r'){			
				if($toppingrow){	
				$chooseid = 1;	
				$isdefaultflag=0;		
				foreach($toppingrow as $tprow){  
				   if(!empty($tprow['price']) && $tprow['price']!=0.00)
					{ 
							if($oprow['size_affect_price']==1)
							{
								$priceaffect = (($pricediff['price_diff']*$tprow['price'])/100)+$tprow['price'];
								$toppingprice = round_to_2dp($priceaffect);
							}	
							else
							{
								$toppingprice = round_to_2dp($tprow['price']);
							}	
					}	
					
					$isdefault = "";
					if($tprow['is_default']=='1')
					{
						if($isdefaultflag==0){  
							$isdefault = 'checked="checked"';
							$isdefaultflag = 1;
							
						}
					}					
		?>
        <fieldset data-role="controlgroup">
        <?php 
			if($HidePriceInOption==0){			
				$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
			}
		?>
        <input type="radio" value="<?php echo $tprow['option_topping_id'];?>" name="topping[<?php echo $num;?>][val]"  id="radio-choice-<?php echo $chooseid;?>" class="showPriceRadio<?php echo $num;?> inlineCheckbox<?php echo $num."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" <?php echo $isdefault;?> <?php if($oprow['min_choise']>0){ echo "required "; }  echo $isdefault;?>>
        <label for="radio-choice-<?php echo $chooseid;?>"><?php  echo $tprow['topping_name']." ".$toppingprices;?></label>
</fieldset>    
<script type="text/javascript">	
  jQuery(document).ready(function($) {				
				$(document).ready(function() {	
				if($('.inlineCheckbox<?php echo $num."_".$chooseid; ?>').is(':checked')) {
						 $("input#hidden<?php echo $tprow['option_topping_id']."_2";?>" ).prop( "checked", true );
						 $("input#hidden<?php echo $tprow['option_topping_id']."_1";?>" ).prop( "checked", true );
        				 $(".mychoiceid<?php echo $num."_".$chooseid; ?>").show();
						   <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>	
  					 }						
					$(".inlineCheckbox<?php echo $num."_".$chooseid; ?>").click(function(event) {
						if ($(this).is(":unchecked"))						
							$(".mychoiceidNew").hide();
						    //$(".mychoiceid<?php //echo $numone."_".$chooseid; ?>").hide();
						else
							$(".mychoiceidNew").hide();					
						  	$("#mychoiceid<?php echo $num."_".$chooseid; ?>").show();						
						    //$(".mychoiceid<?php //echo $numone."_".$chooseid; ?>").show();
					});									
				});
		});
			</script> 
            <div style="position:relative; margin-left:25px; display:none" id="mychoiceid<?php echo $num."_".$chooseid; ?>"  class="mychoiceidNew">
            <?php
		   $countchoose = count($optionname);
		  if($countchoose>0 && $countchoose==1)
		  {
		  	 foreach($optionname as $sss){
			?>
            <input type="checkbox" name="topping[<?php echo  $num;?>][option_choice_id][]" value="<?php echo $sss['option_choice_id'];?>" checked="checked" style="visibility: hidden;position:absolute; left:-999px;"/>
            <?php } }
		  else 
		  {
		   foreach($optionname as $sss)
		   { ?>
            <label class="choicetoppings span1 <?php echo $sss['choice_name'];?>" rel="tooltip" title="<?php echo $sss['choice_name'];?>" id="blah">
            <input type="radio" name="topping[<?php echo  $num;?>][option_choice_id]" value="<?php echo $sss['option_choice_id'];?>" >
             <label for="radio-choice-1"><?php echo ($sss['choice_name']);?></label>
            <span class=""></span>
            </label>
            <?php 
			}
		  } ?>
          </div>       
         <input type="radio" id="hidden<?php  echo $tprow['option_topping_id']."_1";?>" name="topping[<?php  echo $num;?>][price]"  value="<?php  echo $toppingprice;?>" style="visibility: hidden;" />
         <input type="radio" id="hidden<?php  echo $tprow['option_topping_id']."_2";?>" name="topping[<?php  echo $num;?>][option_topping_name]" value="<?php  echo $tprow['topping_name'];?>" style="visibility: hidden;" />
            <?php 
			$chooseid++;
		    } 
		  }
		  ?>
          <input type="hidden" id="radio-previous<?php echo $num; ?>" name="radio-previous<?php echo $num; ?>" />
          <script type="text/javascript">
		  
			jQuery(function($){
				 if($('.showPriceRadio<?php echo $num; ?>').is(':checked')) {
		
			var var1 = document.getElementById("getTopPriceSpan").innerHTML;		
			var radio_pre_val = $("#radio-previous<?php echo $num; ?>").val();	
			if (radio_pre_val!=''){	
				var sumss = Number(var1)-Number(radio_pre_val);	
				var sum =sumss.toFixed(2);
			}	
			var val1 = jQuery( '.showPriceRadio<?php echo $num; ?>:checked' ).attr("data-title");
			if(radio_pre_val!=''){
				var sumss = Number(sum)+Number(val1);
				var sum2 =sumss.toFixed(2);		
				//sum2 = (Math.ceil( sum2 * 10 ) / 10).toFixed(2);
			}	
			else{	
				var sumss = Number(var1)+Number(val1);
				var sum2 =sumss.toFixed(2);
			}
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum2;
			document.getElementById("radio-previous<?php echo $num; ?>").setAttribute("value",val1);		  }
			
		  		$(".showPriceRadio<?php echo $num; ?>").click(function(){
					
				var var1 = document.getElementById("getTopPriceSpan").innerHTML;
				var radio_pre_val = $("#radio-previous<?php echo $num; ?>").val();
				
				if (radio_pre_val!=''){	
					var sumss = Number(var1)-Number(radio_pre_val);
					var sum =sumss.toFixed(2);
				}	
				
				var val1 = $(this).attr("data-title");	
				
				if(radio_pre_val!=''){
					var sumss = Number(sum)+Number(val1);
					var sum2 =sumss.toFixed(2);	
				}
				
				else{	
					var sumss = Number(var1)+Number(val1);
					var sum2 =sumss.toFixed(2);
				}
				document.getElementById("getTopPriceSpan").innerHTML='';
				document.getElementById("getTopPriceSpan").innerHTML=sum2;
				document.getElementById("radio-previous<?php echo $num; ?>").setAttribute("value",val1);
				});	
			});
		 </script>
          <?php
		 }
		elseif($oprow['option_type']=='c'){			
				if($toppingrow){	
				$chooseid = 1;			
				foreach($toppingrow as $tprow){
					$toppingprice  = "";
							if(!empty($tprow['price']) && $tprow['price']!=0.00)
							{	
								if($oprow['size_affect_price']==1){
									$priceaffect = (($pricediff['price_diff']*$tprow['price'])/100)+$tprow['price'];
									$toppingprice = round_to_2dp($priceaffect);
								}	
								else {								
									$toppingprice = round_to_2dp($tprow['price']);
								}	
							} 
							$isdefault = "";
							if($oprow['is_default']=='1')
							{
								$isdefault = 'checked="checked"';
							}
							else
							{
								if($tprow['is_default']=='1')
								{
									$isdefault = 'checked="checked"';
								}
								
							}
				?>
            <label class="span6 checkbox inline fit">
             <div class="span2 toppingdetail fit">
             <input type="checkbox" name="topping[<?php echo $num;?>][val][]" value="<?php echo $tprow['option_topping_id'];?>"  class="showPrice inlineCheckbox<?php echo $num."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" data-maxlength="<?php echo $tprow['max_choise'];?>" data-minlength="<?php echo $tprow['min_choise'];?>" <?php if($oprow['min_choise']>0){ echo "required"; } ?> <?php echo $isdefault; ?> /></div>
                <?php 
				if($HidePriceInOption==0){
				
					$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
				}
			   ?>
             <div class="span7 fit"><?php echo $tprow['topping_name']. ' ' . $toppingprices;?></div> </label>
              <script type="text/javascript">
			  jQuery(document).ready(function($) {
				$(document).ready(function() {
				if($('.inlineCheckbox<?php echo $num."_".$chooseid; ?>').is(':checked')) {
						 $("input#hidden<?php echo $tprow['option_topping_id']."_2";?>" ).prop( "checked", true );
						 $("input#hidden<?php echo $tprow['option_topping_id']."_1";?>" ).prop( "checked", true );
        				 $(".mychoiceid<?php echo $num."_".$chooseid; ?>").show();
						   <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>	
  					 }
					$(".inlineCheckbox<?php echo $num."_".$chooseid; ?>").click(function(event) {
						if ($(this).is(":unchecked"))
						{
						  $(".mychoiceid<?php echo $num."_".$chooseid; ?>").hide();
						  <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								  <?php }
						   }
						   ?>	
						}
						else
						{
						  $(".mychoiceid<?php echo $num."_".$chooseid; ?>").show();
						  <?php  
						   foreach($optionname as $sss)
						   {
								if(strtolower($sss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
								$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );
								  <?php }
						   }
						   ?>
						}
					});									
				});
				});
			</script>
            <div style="position:relative; <?php if($oprow['is_default']=='1'){?> display:block; <?php } else { ?> display:none; <?php } ?>"  class="mychoiceid<?php echo $num."_".$chooseid; ?>">
              <?php
		  $countchoose = count($optionname);
		  if($countchoose>0 && $countchoose==1)
		  {
		  	 foreach($optionname as $sss){
			?>
              <input type="checkbox" name="topping[<?php echo  $num;?>][option_choice_id][]" value="<?php echo $sss['option_choice_id'];?>" checked="checked" style="visibility: hidden;position:absolute; left:-999px;"/>
              <?php } }
		  else
		  {
		  $j=0;
		   foreach($optionname as $sss){	?>
              <label class="choicetoppings span1 <?php echo strtolower($sss['choice_name']);?>" title="<?php echo $sss['choice_name'];?>" id="blah">
              <input type="radio" name="option_choice<?php echo $num."_".$chooseid;?>" value="<?php echo $sss['option_choice_id'];?>" class="id_<?php echo $num."_".$chooseid;?>" id="dd_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>"<?php if(strtolower($sss['choice_name'])=='whole'){ ?> checked="checked" <?php } ?> <?php /*?><?php if($j==0){ echo "checked='checked'";}?><?php */?> data-choicename="<?php echo $ss['choice_name'];?>">
              <?php /*?><img src="<?php echo UPLOADURL;?>choice_images/<?php echo $sss['choice_image'];?>" rel="tooltip" title="<?php echo $sss['choice_name'];?>" id="blah"  /> <?php */?>
              <label for="radio-choice-1"><?php echo ($sss['choice_name']);?></label>
                              <span id="span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>" class="<?php if(strtolower($sss['choice_name'])=='whole'){ echo "active"; }?>"></span>
              </label>
              <script type="text/javascript">
			  jQuery(function($) {
				       $(document).ready(function() {
					    <?php if($oprow['is_default']=='1'){ if(strtolower($sss['choice_name'])=='whole'){?>
						document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
						$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );
						  <?php } }
						  else {if(strtolower($sss['choice_name'])=='whole'){?>
						document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
						$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );
						  <?php }}
						  ?>
					   
				    	$(".id_<?php echo $num."_".$chooseid;?>").click(function(){	
						var value1 = 	$(this).val();
						<?php if($oprow['is_special_calc']=='1')
						{?>						
							var toppingprice = $(this).attr('data-choicename');
							if(toppingprice=='H1' || toppingprice=='H2')
							{
								var choiseprice = '<?php echo $toppingprice/2;?>';
								document.getElementById("hidden<?php echo $tprow['option_topping_id']."_1";?>").setAttribute("value",choiseprice);
							}
							else
							{
								var choiseprice = '<?php echo $toppingprice;?>';
								document.getElementById("hidden<?php echo $tprow['option_topping_id']."_1";?>").setAttribute("value",choiseprice);							
							}
						<?php } ?>	
						
						document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",value1);	
						$("input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );	
						
						if($("#dd_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").is(":checked"))
						{
							$("#span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").addClass("active");
						}
						else 
						{
							$("#span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").removeClass("active");
						}
						//Remove active class from current choices				
						//$(this).parent().find('span').removeClass("active");
						//Apply active class on selected choice
						//$(this).parent().find('span').addClass("active");					
					});												
				});
				});
			</script>
              <?php $j++; }		  	
		  } ?>
            </div>
            <input type="checkbox" id="chosse_<?php echo $num."_".$chooseid;?>" name="topping[<?php echo  $num;?>][option_choice_id][]" value="" style="visibility: hidden;position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
             <input type="checkbox" id="hidden<?php echo$tprow['option_topping_id']."_1";?>" name="topping[<?php echo $num;?>][price][]"  value="<?php echo $tprow['price'];?>" style="visibility: hidden;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?> />
             <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo $num;?>][option_topping_name][]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
           
            <?php  $chooseid ++; } }
			 } 		
		else {
			if($toppingrow){
			
		?>
          <label class="span6 checkbox inline fit">
          <select name="toppingdetailsbind<?php echo $num; ?>" id="toppingdetailsbind<?php echo $num; ?>" class="toppingdetailsbind<?php echo $num; ?>"  <?php if($oprow['min_choise']>0){ echo "required"; } ?>>
          <option value="">--Select--</option>
            <?php  foreach($toppingrow as $trow): 
			
			$toppingprice  = "";
					if(!empty($trow['price']) && $trow['price']!=0.00)
					{					
						if($HidePriceInOption==0)
						 {
						 	if($oprow['size_affect_price']==1)
							{
								$priceaffect = (($pricediff['price_diff']*$trow['price'])/100)+$trow['price'];
								$toppingprice = round_to_2dp($priceaffect);
							}	
							else
							{
								$toppingprice = round_to_2dp($trow['price']);
							}						
						 }	
					}	
			?>
            <option value="<?php echo $trow['option_topping_id'];?>" data-topping-price="<?php echo $toppingprice;?>" data-topping-name="<?php echo $trow['topping_name'];?>">
			<?php
			if($HidePriceInOption==0)
				{
					$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
				}
			$toppingprices = ($toppingprice) ? "("."$".$toppingprice.")" : "";
			
			 echo $trow['topping_name'].$toppingprices; ?></option>
            <?php endforeach;?>
          </select>
         </label>
          <input type="radio" id="bindvalue<?php echo $num."_1";?>" name="topping[<?php echo $num;?>][price]"  value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" id="bindvalue<?php echo $num."_2";?>" name="topping[<?php echo $num;?>][option_topping_name]" value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" value="" name="topping[<?php echo $num;?>][val]"  style="visibility: hidden;position:absolute; left:-999px;" id="bindvalue<?php echo $num."_3";?>">          
         <input type="hidden" id="select_previous_val<?php echo $num; ?>" name="select_previous_val<?php echo $num; ?>" />
          <script type='text/javascript'>
			jQuery(function($) {
				$('#toppingdetailsbind<?php echo  $num; ?>').change(function() {
					var id = $(this).val();
					var toppingprice = $('option:selected', "#toppingdetailsbind<?php echo  $num; ?>").attr('data-topping-price');
					var toppingname = $('option:selected', "#toppingdetailsbind<?php echo  $num; ?>").attr('data-topping-name');
					
					document.getElementById("bindvalue<?php echo $num."_1";?>").setAttribute("value",toppingprice);
					$("input#bindvalue<?php echo $num."_1";?>" ).prop( "checked", true );
					document.getElementById("bindvalue<?php echo $num."_2";?>").setAttribute("value",toppingname);
					$("input#bindvalue<?php echo $num."_2";?>" ).prop( "checked", true );
					document.getElementById("bindvalue<?php echo $num."_3";?>").setAttribute("value",id);
					$("input#bindvalue<?php echo $num."_3";?>" ).prop( "checked", true );
				});
			});
			</script>
            <script type="text/javascript">
			jQuery(function($){
				
		  		$('.toppingdetailsbind<?php echo $num; ?>').change(function(){
					
					var var1 = document.getElementById("getTopPriceSpan").innerHTML;	
					var radio_pre_val = $("#select_previous_val<?php echo $num; ?>").val();
					if (radio_pre_val!=''){	
						var sumss = Number(var1)-Number(radio_pre_val);	
						var sum =sumss.toFixed(2);	
		
					}
					
					var val1 = $('option:selected', ".toppingdetailsbind<?php echo $num; ?>").attr('data-topping-price');
					//var val1 = $(this).attr("data-topping-price");
					if(radio_pre_val!=''){
						var sumss = Number(sum)+Number(val1);
						var sum2 =sumss.toFixed(2);	
					}
					else{	
						var sumss = Number(var1)+Number(val1);
						var sum2 =sumss.toFixed(2);
					}
					document.getElementById("getTopPriceSpan").innerHTML='';
					document.getElementById("getTopPriceSpan").innerHTML=sum2;
					document.getElementById("select_previous_val<?php echo $num; ?>").setAttribute("value",val1);
					});	
			});
		 </script>
          <?php
		    }
			
		  }  ?>
             </div>
       </div>
        <div class="clr"></div>
        <?php 
			} ?>
        <?php  
		 $num++;		
	  }?>
        <?php  } ?>
      </div>
      <!-- ------Qty & Recipient------------------>
      <div class="ui-block-a">
        <div class="product-listing">
          <div class="form-group">
            <label for="inputEmail1" class="control-label toppings_heading bold">Qty:</label>
            <div>
              <input type="text" class="form-control" name="qty" value="1" id="qty" style="width:100px;">
               <?php  if(!empty($error['qty'])) echo $error['qty'];?> 
            </div>
          </div>
        </div>
      </div>
      <div class="ui-block-a">
        <div class="form-group">
          <label for="inputEmail1" class="control-label toppings_heading bold">Who is this for :</label>
          <div>
            <textarea name="additional_notes"></textarea>
          </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="span12 productPriceWrapRight">
      <div class="productPriceWrapRight span5">
        <input value="Add to cart" type="submit" name="submit" >
      </div>
    </div>
    <input type="hidden" name="productID" value="<?php echo $productrow['id'];?>" />
    <input type="hidden" name="action" value="addToBasket" />
    <input type="hidden" id="radio-previous" name="radio-previous" />
  </form>
  <!-- /content -->
</div>
<a href="restaurantmenu.php" data-ajax="false" data-role="button" data-theme="b">Back To Menu</a>
<!-- /content -->
<?php } 
else
{
	
	header("Location:".SITEURL."/mobile/index.php");
}?>
</div>
<?php include("footer.php");?>
<script type="text/javascript">
  // This takes out the jQuery variable '$' from the global scope
  $.noConflict();
  	jQuery(document).ready(function($) {
	
	$('#kuladd').validate({	
		rules: {
				qty: {
					required: true,
					digits: true,
					custom_rule : true,
					minlength: 1					
				}
		  }
	});

	$.validator.addMethod("custom_rule", function(value, element) {
	
		var dropdown_val = $("#qty").val();		
		if(dropdown_val==1 || dropdown_val>0 )
		{
		   return true;
		}
		else
		{
		   return false;
		}	
	
	 }, "Quantity must be more than 0.");

	$('input[data-minlength]').each(function(){
		if ($(this).data('minlength')) {
			$(this).rules("add", {
				minlength: $(this).data('minlength')
			});
		}
	});
	
    // WORKS
	$('input[data-maxlength]').each(function(){
		if ($(this).data('maxlength')) {
			$(this).rules("add", {
				maxlength: $(this).data('maxlength')
			});
		}
	});
	
    $('.sized_price').change(function () {
			/*$('#sizedprice').html('');
			$('#sizedprice').html("<h4 class='price_at_topping'>"+ "$"+$(this).val()+"</h4>");
			$this = $(this);
			$("#menu_size_map_id").val($(this).attr('rel'));*/
			var sizeid =  $(this).attr('rel');
			window.location.href= "<?php echo SITEURL;?>/mobile/toppingdetails.php?Item=<?php echo $_GET['Item'];?>&sizedid="+sizeid;
			
			/*if($this.val() != 0){
				output = $this.find('option:selected').attr('rel');
			}
			*/
        });
		
	$("#topping input").click(function(){		
		var checker = $(this).prop('checked');
		var inpvalue = $(this).val();
		
		if(checker){		
			$( "input#hidden"+inpvalue+"_1" ).prop( "checked", true );
			$( "input#hidden"+inpvalue+"_2" ).prop( "checked", true );
		}
		else
		{
			$( "input#hidden"+inpvalue+"_1" ).prop( "checked", false );
			$( "input#hidden"+inpvalue+"_2" ).prop( "checked", false );
			
		}
		
		}); 
		
	 $(".showPrice:checked").each(function() {
  var var1 = document.getElementById("getTopPriceSpan").innerHTML;
		if ($(this).is(":checked")){	
			var val1 = $(this).attr("data-title");							 
			var sumss = Number(var1)+Number(val1);
			var sum =sumss.toFixed(2)
			//sum = (Math.ceil( sum * 10 ) / 10).toFixed(2);	
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum;	
		}
		else {
			var val1 = $(this).attr("data-title");	
			var sumss = Number(var1)-Number(val1);
			var sum =sumss.toFixed(2)
			//sum = (Math.ceil( sum * 10 ) / 10).toFixed(2);	
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum;			
		}
		});
		
	$(".showPrice").click(function() {
						
		var var1 = document.getElementById("getTopPriceSpan").innerHTML;
																 
		if ($(this).is(":checked")){							
			
			var val1 = $(this).attr("data-title");							 
			var sumss = Number(var1)+Number(val1);
			var sum =sumss.toFixed(2);
			//sum = (Math.ceil( sum * 10 ) / 10).toFixed(2);									
			
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum;			
		 
		}
		else {
			var val1 = $(this).attr("data-title");						 
								 
			var sumss = Number(var1)-Number(val1);
			var sum =sumss.toFixed(2);
			//sum = (Math.ceil( sum * 10 ) / 10).toFixed(2);	
			
			document.getElementById("getTopPriceSpan").innerHTML='';
			document.getElementById("getTopPriceSpan").innerHTML=sum;			
		}
	});		
	
	$(".imageDivLink").click(function(){

		 var id =  $(this).attr('id');	

	     $("#showToggle"+id).toggle();

	  });
  });
  // Code that uses other library's $ can follow here.
</script>