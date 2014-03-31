<?php
  /**
   * Topping show by click on item
   * Kulacar
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
 if(isset($_GET['locationid']))
 {
 	$locationid = $_GET['locationid'];
 } 
 ?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/tooltip.js"></script>

<?php 
  if(!empty($_GET['Item'])): 

  $productrow = $menu->productByItem($_GET['Item']); 
  $option = $menu->optionListByItem($_GET['Item']);
  $sizemap = $menu->sizeByItem($productrow['id']);
  $cat = $menu->categoryName($_GET['cartrowid']); 
?>
<div id="notifiyer"></div>
<form name="kuladd" id="kuladd" action="" method="post">
<div class="row-fluid">
<h4 class="product-cat"><?php echo $cat['category_name'];?></h4> 
  <?php 
		 if(isset($cat['category_dec']) && !empty($cat['category_dec'])){
				echo '<p class="category_desc">'.cleanOut(ucwords($cat['category_dec'])).'</p>';
		}  
  ?>       
</div>
  <?php if($productrow['show_image_in_menu']=='1'){?>
  <div class="row-fluid">
  <div class="span12">
  <?php if($productrow['thumb_item_image']){?>
  	<img src="<?php echo UPLOADURL;?>/menuitem/thumb/<?php echo $productrow['thumb_item_image'];?>" alt="" />
  <?php }?>
  </div>
  </div>
  <?php } ?>
  <div class="row-fluid">
    <div class="span12 fit">
      <div class="span8">
        <div class="productname"><?php echo $productrow['item_name'];?></div>
      </div>
      
      <div class="span4">
        <div style="text-align:center;" id="sizedprice">
         <h4 class="price_at_topping">
            $<span id="getTopPriceSpan"> 
			<?php 
			// Menu type is Sized then price display here 
			if($productrow['item_type']=='1'){			
				if(!empty($_GET['sizedid'])){				
					foreach($sizemap as $sizrow):	
							if($_GET['sizedid']==$sizrow['sizeid']){							
								echo $sizrow['price'];
							}							
					endforeach; 
				}	
			}
			// if Menu item type is regular price display
			if($productrow['item_type']=='0'){								
                	echo $productrow['price'];?>
             </span>      
             </h4>
                    <input type="hidden" name="item_price" value="<?php echo $productrow['price'];?>"  />
        <?php  }?>         
        </div>
      </div>
    </div>
    <br />    
      <?php  echo (cleanSanitize($productrow['item_description']))? "<div class=\"span11 fit itemdesc\">".cleanOut($productrow['item_description'])."</div>" : ""; ?>
    
  </div>
  <div class="row-fluid">
    <!---------------Current Item Price  if item is sized ------------------------->
    <?php if($productrow['item_type']=='1'){ ?>
    <div class="span12 fit">
      <div class="form-group">
        <!--<label for="inputEmail1" class="span12 col-lg-2 control-label toppings_heading bold">Size and Type:</label>-->
        <h3 class="toppings">Size:</h3>
         <?php 
		  	$sizecount = 1;
			$flag=0;
		   ?>       
          <?php foreach($sizemap as $srow):	
          if($sizecount%5==1)
          {
		  $flag++;
		  ?>
          <div class="span12">  
          <?php } ?>
              <div class="span_size_img fit">
                <div class="span12 toppingdetail tacenter">
                  <?php 
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
                      <?php if(isset($_GET['cartrowid']) && !empty($_GET['cartrowid'])) { $update_id = "-".$_GET['cartrowid']; } ?>
                     <input type="radio" name="menu_size_map_id" value="<?php echo $srow['menusizemapid'];?>"  id="menu_size_map_id" <?php echo $checked;?>  style="visibility:hidden; position:absolute"/>
                      <a href="#category-<?php echo $_GET['cartrowid']."-". $cat['category_name']."/product-".$productrow['id'];?>/sizeid-<?php echo $srow['sizeid'].$update_id;?>" class="itemtopping" rel="<?php echo $productrow['id'];?>">			  
				  <?php if($srow['size_image'] && is_file("../uploads/size_image/". $srow['size_image'])):?>
                  <span class="rdtoppings">
                  <input type="radio" name="item_price" value="<?php echo $srow['price'];?>" rel="<?php echo $srow['menusizemapid'];?>" class="sized_price" required  <?php echo $checked;?> />
                  <img src="<?php echo UPLOADURL;?>/size_image/<?php echo $srow['size_image'];?>" alt="<?php echo $srow['size_name'];?>" /> </span>
                  <?php else:?>
                  <span class="rdtoppings1">
                  <input type="radio" name="item_price" value="<?php echo $srow['price'];?>" rel="<?php echo $srow['menusizemapid'];?>" class="sized_price" required  <?php echo $checked;?>/><img src="<?php echo THEMEURL;?>/images/icon.png" alt="<?php echo $srow['size_name'];?>" />
                  </span>
                  <?php endif;?>
                  </a>
                </div>
<?php /* ?>  <div class="span12 fit"><?php echo $srow['size_name']."-".$srow['price'];?></div>   <?php */ ?>
<div class="span12 fit tacenter"><?php echo $srow['size_name']; ?></div>
              </div>
           <?php if($sizecount%5==0)
		  {
		            $flag=0;
          ?>
           <div class="clr"></div>
          </div>
          <?php }	
		  $sizecount++;?>

          <?php endforeach; 
		  if($flag>0)
		  {
		  ?>
           <div class="clr"></div>
          </div>
          <?php }?>          
      </div>
    </div>
    <?php } ?>
    <!----------------------Start Topping Display -------------------------------------------------------------->
    <div class="span12 fit" id="topping">
      <?php if($option){ ?>
      <?php 
		$num = 1;
		foreach($option as $oprow){
		$pricediff = $menu->getoptionpricediff($_GET['sizedid'],$oprow['menu_option_map_id']);	// get price_diff	form res_menu_size_price 
		// we will show a checkbox, On check of that it, other option to add option, choice & topping will be appeared otherwise list of options will be displayed.
		  if($oprow['is_normal_option']=='0'){ 
 			$mainoption = $menu->optionMain($productrow['id']);
 			if($oprow['display_option'] ==1)  //menu option is display Selectview started here......
			{			
			if($mainoption):
       			foreach($mainoption as $mrow): ?>
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
      <div class="span12 fit" id="topping<?php echo $mrow['option_id']; ?>"> </div>
      <?php endforeach; ?>
      <?php endif;
			}
			else    //Gridview html started here.......			
			{			
			  $option_visiblity ="";	  
			  if($oprow['hide_option']==1)
			  {
				$option_visiblity = 'style = "visiblity: hidden;position:absolute; left:-1500px;"';  
			  }
			  else
			  {
				  $option_visiblity ="";
			  }
			  $hide_instruction = "";
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
     		  <div class="span12" <?php echo $option_visiblity; ?>>
        <?php 			
			   $childoption = $menu->optionchild($oprow['option_id']); 		   
			   $numone = $num+1;
			   foreach($childoption as $chrow){
			   $optiontopping = $menu->gettoopinglist($chrow['option_id']);
			    if($optiontopping)
				{	
					$num=$num+1;
					foreach($optiontopping as $oprow)
					{
					if(count($optiontopping)==1){$toppingcss = '';} else if($numone%2==0){ $toppingcss = 'evenoptions_style'; } else {  $toppingcss = 'oddoptions_style'; }
			   ?>
        <div class="span6 fit <?php echo $toppingcss;?>">
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
		  $hide_instruction = "";
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
   		    
          <div class="span12 fit" <?php echo $option_visiblity; ?>>
            <input type="hidden" name="topping[<?php echo $numone ;?>][id]" value="<?php echo $oprow['option_id'];?>" />
            <?php 
            $toppingrow = $menu->topingByItem($oprow['option_id']);
             $optionname = $menu->getOptionChoice($oprow['option_id']);
			 $HidePriceInOption = $product->HidePriceInOption($locationid);	 // hide price option form backend 
		 /******************************If option type is "radio button"***********************************************/	
		 if($oprow['option_type']=='r')
		 { 
				if($toppingrow)
				{
				$chooseid = 1;	
				$isdefaultflag=0;
				foreach($toppingrow as $tprow)
				{ 
					$toppingprice  = "";
					if(!empty($tprow['price']) && $tprow['price']!=0.00)
					{ 
							if($oprow['size_affect_price']==1) // menu Option Size Affect Price is active then display its price display 
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
            <label class="span12 checkbox inline fit">
            <div class="span2 toppingdetail fit">
              <input type="radio" value="<?php echo $tprow['option_topping_id'];?>" name="topping[<?php echo $numone;?>][val]"  class="showPriceRadio<?php echo $numone; ?> inlineCheckbox<?php echo $numone."_".$chooseid; ?>" id="inlineCheckbox<?php echo $numone."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" <?php if($oprow['min_choise']>0){ echo "required"; } ?>  <?php echo $isdefault;?>>
            </div>
            <div class="span7 fit">
				<?php 
                    if($HidePriceInOption==0){ $toppingprices = ($toppingprice) ? "$".$toppingprice : "";  }
                    echo $tprow['topping_name'].' '.$toppingprices;
                ?>
            </div> 
            <script type="text/javascript">					
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
							$(".mychoiceidNew<?php echo $numone;?>").hide();
						}
						else
						{					
							$(".mychoiceidNew<?php echo $numone;?>").hide();					
						  	$("#mychoiceid<?php echo $numone."_".$chooseid; ?>").show();		
						}
					});									
				});
			</script>
          <div style="position:relative; display:none" id="mychoiceid<?php echo $numone."_".$chooseid; ?>"  class="mychoiceidNew<?php echo $numone;?>">
            <?php
		   $countchoose = count($optionname);
		  if($countchoose>0 && $countchoose==1)
		  {
		  	 foreach($optionname as $sss){
			?>
            <input type="checkbox" name="topping[<?php echo  $numone;?>][option_choice_id][]" value="<?php echo $sss['option_choice_id'];?>" checked="checked" style="visibility: hidden;position:absolute; left:-999px;" id="chosse_<?php echo $num."_".$chooseid;?>"/>
            <?php } }
		  else 
		  {
		   foreach($optionname as $sss)
		   { ?>
            <label class="choicetoppings span1 <?php echo $sss['choice_name'];?>" rel="tooltip" title="<?php echo $sss['choice_name'];?>" id="blah">
            <input type="radio" name="topping[<?php echo  $numone;?>][option_choice_id]" value="<?php echo $sss['option_choice_id'];?>" id="chosse_<?php echo $num."_".$chooseid;?>">
            <span class=""></span>
            </label>
            <?php 
			}
		  } ?>
          </div>           
            </label>
            <input type="radio" id="hidden<?php echo $tprow['option_topping_id']."_1";?>" name="topping[<?php echo $numone;?>][price]"  value="<?php echo $toppingprice;?>" style="visibility: hidden;position:absolute; left:-999px;" />
            <input type="radio" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo $numone;?>][option_topping_name]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden;position:absolute; left:-999px;" />
            <?php 
			$chooseid++;
		    } 
		  }
		  ?>
           <input type="hidden" id="radio-previous<?php echo $numone; ?>" name="radio-previous<?php echo $numone; ?>" />
           <script type="text/javascript">
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
		 </script>
		 <?php
		 }
		 //*****************************If option type is "Checkbox"***************************************************/
		elseif($oprow['option_type']=='c')
		{		
			if($toppingrow)
			{					
				$chooseid = 1;			
				foreach($toppingrow as $tprow)
				{
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
              <label class="span1">
              <input type="checkbox" name="topping[<?php echo $numone;?>][val][]" value="<?php echo $tprow['option_topping_id'];?>"  class="showPrice inlineCheckbox<?php echo $numone."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" data-maxlength="<?php echo $tprow['max_choise'];?>" data-minlength="<?php echo $tprow['min_choise'];?>"  <?php if($oprow['min_choise']>0){ echo "required"; } ?> <?php echo $isdefault; ?> />
              </label>
              <div class="span7">
               <?php 
				if($HidePriceInOption==0){				
					$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
				}
				echo $tprow['topping_name'].' '.$toppingprices;
			   ?>
                <?php 
				//16th, jan 2014, starts here
				if($productrow['item_type']=='1')
				{
					if(!empty($_GET['sizedid']))
					{
						foreach($sizemap as $sizrow):	
								if($_GET['sizedid']==$sizrow['sizeid'])
								{
									$sizrow['price'];
								}							
						endforeach; 
					}	
				}
				if($productrow['item_type']=='0')
				{					
								$productrow['price'];
				}
				//ends here
				?>
              </div>
              <script type="text/javascript">
				$(document).ready(function() {		
				
				 if($('.inlineCheckbox<?php echo $numone."_".$chooseid; ?>').is(':checked')) {
						 $("input#hidden<?php echo $tprow['option_topping_id']."_2";?>" ).prop( "checked", true );
						 $("input#hidden<?php echo $tprow['option_topping_id']."_1";?>" ).prop( "checked", true );
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
																
					$(".inlineCheckbox<?php echo $numone."_".$chooseid; ?>").click(function(event) {
						if ($(this).is(":unchecked"))
						{
						  $(".mychoiceid<?php echo $numone."_".$chooseid; ?>").hide();
						  <?php  
						   foreach($optionname as $ss)
						   {
								if(strtolower($ss['choice_name'])=='whole'){?>
								document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").removeAttribute("value",<?php echo $ss['option_choice_id'];?>);
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
			</script>
            <!--Choise display here start-->
             <div style="position:relative; <?php if($oprow['is_default']=='1'){?> display:block; <?php } else { ?> display:none; <?php } ?>"  class="mychoiceid<?php echo $numone."_".$chooseid; ?>">
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
                <input type="radio" name="option_choice<?php echo $numone."_".$chooseid;?>" value="<?php echo $ss['option_choice_id'];?>" class="id_<?php echo $numone."_".$chooseid;?>" id="dd_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>" <?php if(strtolower($ss['choice_name'])=='whole'){ ?> checked="checked" <?php } ?>  data-choicename="<?php echo $ss['choice_name'];?>">
                <span id="span_<?php echo $chooseid.$numone.strtolower($ss['choice_name']);?>" class="<?php if(strtolower($ss['choice_name'])=='whole'){ echo "active"; }?>"></span>
                <?php /*?><img src="<?php echo UPLOADURL;?>choice_images/<?php echo $ss['choice_image'];?>"  /><?php */?>
                </label>
                
                <script type="text/javascript">
				       $(document).ready(function() {
					   					   
						  <?php if($oprow['is_default']=='1'){  if(strtolower($ss['choice_name'])=='whole'){?>
						document.getElementById("chosse_<?php echo $numone."_".$chooseid;?>").setAttribute("value",<?php echo $ss['option_choice_id'];?>);
						$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
						  <?php } } ?>
						  
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
              <!--Choise display here End-->                     
             <input type="checkbox" id="chosse_<?php echo $numone."_".$chooseid;?>" name="topping[<?php echo  $numone;?>][option_choice_id][]" value="" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
             
              <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo  $numone;?>][option_topping_name][]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
              
              <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_1";?>" name="topping[<?php echo $numone;?>][price][]"  value="<?php echo $toppingprice;?>" style="visibility: hidden; position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
              
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
          <select name="toppingdetailsbind<?php echo $numone; ?>" id="toppingdetailsbind<?php echo $numone; ?>" class="toppingdetailsbind<?php echo $numone; ?>" <?php if($oprow['min_choise']>0){ echo "required"; } ?>>
          <option value="" data-topping-price="0">--Select--</option>
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
			echo $trow['topping_name'].$toppingprices;?></option>
            <?php endforeach;?>
          </select>
          <input type="radio" id="bindvalue<?php echo $numone."_1";?>" name="topping[<?php echo $numone;?>][price]"  value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" id="bindvalue<?php echo $numone."_2";?>" name="topping[<?php echo $numone;?>][option_topping_name]" value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" value="" name="topping[<?php echo $numone;?>][val]"  style="visibility: hidden;position:absolute; left:-999px;" id="bindvalue<?php echo $numone."_3";?>">
           <input type="hidden" id="select_previous_val<?php echo $numone; ?>" name="select_previous_val<?php echo $numone; ?>" /> 
          </label>
          <script type='text/javascript'>
			$(function() {
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

		  	$('.toppingdetailsbind<?php echo $numone; ?>').change(function() {

			var var1 = document.getElementById("getTopPriceSpan").innerHTML;
			
			

			var radio_pre_val = $("#select_previous_val<?php echo $numone; ?>").val();			

			if (radio_pre_val!=''){	
				var sumss = Number(var1)-Number(radio_pre_val);	
				var sum =sumss.toFixed(2);	

			}	
			
			var val1 = $('option:selected', ".toppingdetailsbind<?php echo  $numone; ?>").attr('data-topping-price');

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
			}
	 		  ?>
      </div>
      </div>
      <?php
	  		 }
		else
		{
	  ?>
      <div class="span12 fit">
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
		$HidePriceInOption = $product->HidePriceInOption($locationid);			
		 /******************************If option type is "radio button"***********************************************/	
		if($oprow['option_type']=='r'){	
				if($toppingrow){	
					$chooseid	=1;	
					$isdefaultflag = 0;
					foreach($toppingrow as $tprow){
						$toppingprice  = "";
						
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
          <label class="span6 checkbox inline fit">
          <div class="span2 toppingdetail fit">
            <input type="radio" value="<?php echo $tprow['option_topping_id'];?>" name="topping[<?php echo $num;?>][val]"  class="showPriceRadio<?php echo $num;?> inlineCheckbox<?php echo $num."_".$chooseid; ?>" id="inlineCheckbox<?php echo $numone."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" <?php if($oprow['min_choise']>0){ echo "required "; }  echo $isdefault;?>  >
          </div>
          <div class="span7 fit">
		  <?php 
		 	if($HidePriceInOption==0)
			{
				$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
			}
			echo $tprow['topping_name'].' '.$toppingprices;?></div>
          <script type="text/javascript">
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
						if ($(this).is(":unchecked")){	
							$(".mychoiceidNew<?php echo $num;?>").hide();					
						    //$(".mychoiceid<?php //echo $num."_".$chooseid; ?>").hide();						  
						  }						  
						else
						{	
							$(".mychoiceidNew<?php echo $num;?>").hide();					
						  	$("#mychoiceid<?php echo $num."_".$chooseid; ?>").show();						
						     //$(".mychoiceid<?php //echo $num."_".$chooseid; ?>").show();
						 }
					});									
				});
			</script>
          <div style="position:relative;display:none"  class="mychoiceidNew<?php echo $num;?>" id="mychoiceid<?php echo $num."_".$chooseid; ?>" >
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
            <input type="radio" name="topping[<?php echo  $num;?>][option_choice_id]" value="<?php echo $sss['option_choice_id'];?>" id="chosse_<?php echo $num."_".$chooseid;?>" >
            <?php /*?><img src="<?php echo UPLOADURL;?>choice_images/<?php echo $sss['choice_image'];?>"  /><?php */?> 
            <span class=""></span>
            </label>
            <?php 
			}
		  } ?>
          </div>
          </label>
          <input type="radio" id="hidden<?php echo $tprow['option_topping_id']."_1";?>" name="topping[<?php echo $num;?>][price]"  value="<?php echo $toppingprice;?>" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo $num;?>][option_topping_name]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden;position:absolute; left:-999px;" />
          <?php 
		$chooseid++;
		    } 
		  }
		   ?>
          <input type="hidden" id="radio-previous<?php echo $num; ?>" name="radio-previous<?php echo $num; ?>" />
          <script type="text/javascript">
		  
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
				//sum1 = (Math.ceil( sum * 10 ) / 10).toFixed(2);
			}	
			var val1 = $(this).attr("data-title");	
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
			document.getElementById("radio-previous<?php echo $num; ?>").setAttribute("value",val1);
			});	

		 </script>
          <?php 
		  
		 }
		 //*****************************If option type is "Checkbox"***************************************************/
		elseif($oprow['option_type']=='c'){	
			if($toppingrow){
				$chooseid = 1;			
				foreach($toppingrow as $tprow){	
					$toppingprice  = "";
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
          <div class="span6 fit">
            <label class="span1">
            <input type="checkbox" name="topping[<?php echo $num;?>][val][]" value="<?php echo $tprow['option_topping_id'];?>" class="showPrice inlineCheckbox<?php echo $num."_".$chooseid; ?>" data-title="<?php echo $toppingprice; ?>" data-maxlength="<?php echo $tprow['max_choise'];?>"  data-minlength="<?php echo $tprow['min_choise'];?>" <?php if($oprow['min_choise']>0){ echo "required"; } ?> <?php echo $isdefault; ?> />
            </label>
            <div class="span7">
			<?php 		
			if($HidePriceInOption==0)
			{
				$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
			}
			echo $tprow['topping_name'].' '.$toppingprices;?></div>
            <script type="text/javascript">
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
              <input type="radio" name="option_choice<?php echo $chooseid;?>" value="<?php echo $sss['option_choice_id'];?>" class="id_<?php echo $num."_".$chooseid;?>" id="dd_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>"<?php if(strtolower($sss['choice_name'])=='whole'){ ?> checked="checked" <?php } ?> data-choicename="<?php echo $sss['choice_name'];?>" <?php /*?><?php if($j==0){ echo "checked='checked'";}?><?php */?>>
              <?php /*?><img src="<?php echo UPLOADURL;?>choice_images/<?php echo $sss['choice_image'];?>" rel="tooltip" title="<?php echo $sss['choice_name'];?>" id="blah"  /> <?php */?>
                              <span id="span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>" class="<?php if(strtolower($sss['choice_name'])=='whole'){ echo "active"; }?>"></span>
              </label>
              <script type="text/javascript">
				       $(document).ready(function() {
					   					   					   
						  <?php if($oprow['is_default']=='1'){  if(strtolower($sss['choice_name'])=='whole'){?>
						document.getElementById("chosse_<?php echo $num."_".$chooseid;?>").setAttribute("value",<?php echo $sss['option_choice_id'];?>);
						$("input#chosse_<?php echo $numone."_".$chooseid;?>" ).prop( "checked", true );
						  <?php } } ?>
						  
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
						$( "input#chosse_<?php echo $num."_".$chooseid;?>" ).prop( "checked", true );	
						
						
						if($("#dd_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").is(":checked"))
						{
							$("#span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").addClass("active");
						}
						else 
						{
							$("#span_<?php echo $chooseid.$num.strtolower($sss['choice_name']);?>").removeClass("active");
						}
					});												
				});
			</script>
              <?php $j++; }		  	
		  } ?>
            </div>            
            <input type="checkbox" id="chosse_<?php echo $num."_".$chooseid;?>" name="topping[<?php echo  $num;?>][option_choice_id][]" value="" style="visibility: hidden;position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
            <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_2";?>" name="topping[<?php echo  $num;?>][option_topping_name][]" value="<?php echo $tprow['topping_name'];?>" style="visibility: hidden;position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
            <input type="checkbox" id="hidden<?php echo $tprow['option_topping_id']."_1";?>" name="topping[<?php echo $num;?>][price][]"  value="<?php echo $toppingprice;?>" style="visibility: hidden;position:absolute; left:-999px;" <?php if($oprow['is_default']=='1'){?> checked="checked" <?php } ?>/>
            <!--on check, show rounded , ends here-->
          </div>
          <?php 
				$chooseid++; 
			  }
		    }
			
		  } 	
		 //******************************If option type is "Dropdown box"**********************************************/	
		else {
			if($toppingrow)
			{
		?>
          <label class="span6 checkbox inline fit">
          <select name="toppingdetailsbind<?php echo $numone; ?>" id="toppingdetailsbind<?php echo $num; ?>" class="toppingdetailsbind<?php echo $num; ?>" <?php if($oprow['min_choise']>0){ echo "required"; } ?>>
          <option value="" data-topping-price="0">--Select--</option>
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
            <option value="<?php echo $trow['option_topping_id'];?>" data-topping-price="<?php echo $toppingprice;?>" data-topping-name="<?php echo $trow['topping_name'];?>"><?php 
			if($HidePriceInOption==0)
			{
				$toppingprices = ($toppingprice) ? "$".$toppingprice : "";
			}
			echo $trow['topping_name'].$toppingprices;?></option>
            <?php endforeach;?>
          </select>
          <input type="radio" id="bindvalue<?php echo $num."_1";?>" name="topping[<?php echo $num;?>][price]"  value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" id="bindvalue<?php echo $num."_2";?>" name="topping[<?php echo $num;?>][option_topping_name]" value="" style="visibility: hidden;position:absolute; left:-999px;" />
          <input type="radio" value="" name="topping[<?php echo $num;?>][val]"  style="visibility: hidden;position:absolute; left:-999px;" id="bindvalue<?php echo $num."_3";?>">
          <input type="hidden" id="select_previous_val<?php echo $num; ?>" name="select_previous_val<?php echo $num; ?>" />
          
          </label>
          <script type='text/javascript'>
			$(function() {
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
		  	$('.toppingdetailsbind<?php echo $num; ?>').change(function() {
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
		 </script>
          <?php
		    }
			
		  } 
	    ?>
        </div>
        </div>
      </div>
      <div class="clr"></div>
      <?php	
	  } 
		 $num++;
	 	 }  
	   };
	 ?>
    </div>
    <!-- ------Qty & Recipient------------------>
    <div class="span12 margin-top-20">
      <div class="span11 product-listing qty-box" style="padding:5px">
        <div class="span12">
          <div class="span5">
            <div class="form-group">
              <label for="inputEmail1" class="col-lg-2 control-label bold">Qty:</label>
              <div class="col-lg-10">
                <input type="text" class="form-control" name="qty" value="1" id="qty" style="width:100px;">
              </div>
            </div>
          </div>
          <div class="span7">
            <div class="form-group">
              <label for="inputEmail1" class="col-lg-2 control-label bold">Who is this for ?</label>
              <div class="col-lg-10">
                <textarea name="additional_notes"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="span12">
          <div class="span12 productPriceWrapRight">
            <div class="span2">
              <?php 
				  if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){
				  
				  ?>
                  <a href="<?php echo SITEURL;?>/?location#category-<?php echo $_GET['cartrowid'];?>-<?php echo $cat['category_name'];?>"><img src="<?php echo THEMEURL;?>/images/btn_cancel2.png" alt="Back"/></a>
                  <?php  }
				  else {
				  
			 ?>
              <a href="<?php echo SITEURL;?>/chooselocation"><img src="<?php echo THEMEURL;?>/images/btn_cancel2.png" alt="Back"/></a>
              <?php } ?>
            </div>
            <div class="productPriceWrapRight span3"> 
              <input name="submit" value="Add to cart" id="ADDTOCART" type="image" src="<?php echo THEMEURL;?>/images/btn_additem.png" />
              <!--<input type="submit" src="images/add-to-basket.gif" name="submit" id="featuredProduct_<?php echo $productrow['id'];?>"/>-->
              <?php /*?> <img src="images/add-to-basket.gif" alt="Add To Basket" width="111" height="32" id="featuredProduct_<?php echo $productrow['id'];?>" /><?php */?>
             </div>
          </div>
        </div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
  <input type="hidden" name="productID" value="<?php echo $productrow['id'];?>" />
  <input type="hidden" name="action" value="addToBasket" />
  <input type="hidden" name="cartrowid" value="<?php echo (int)$_GET['cartrowid']?>" />
  <input type="hidden" id="radio-previous" name="radio-previous" />
</form>

<?php endif;?>
<script type="text/javascript">
function changetooping(val,itemid){

	var optionid = val;
	$("#smallLoader").css({display: "block"});
	
	 $.get('<?php echo SITEURL;?>/ajax/gettopping.php', { Option:optionid }, function(data) {	
  		$("#smallLoader").css({display: "none"});		
        $('#topping'+itemid).html(data);      
    });
    return false; // prevent default
}
</script>
<script>
		/*$('.sized_price').change(function () {
			$('#sizedprice').html('');
			$('#sizedprice').html("<h4 class='price_at_topping'>"+ "$"+$(this).val()+"</h4>");
			 $this = $(this);
			 $("#menu_size_map_id").val($(this).attr('rel'));
						
        });*/
		
		/*$('.sized_price').click(function () {
					var sizeid =  $(this).val();
					var data = 'Item=' + 46 + '&cartrowid=' + <?php echo $_GET['cartrowid'];?> + '&sizedid=' + 16 + '&locationid=' + <?php echo $locationid;?>;
						$.ajax({
						url: SITEURL+"/ajax/toppingbyproduct.php",	
						type: "GET",		
						data: data,		
						cache: false,
						success: function (html)
						{	
							$("#smallLoader").css({display: "none"});
						   $('#productview').html(html);   		
						}		
					});
		});*/
</script>
<script type="text/javascript">
 // <![CDATA[  
  /************Submit form data thruought AJAX start here***********/
  $(document).ready(function() {	            
		 
	$('#kuladd').validate({	
		rules: {
				qty: {
					required: true,
					digits: true,
					custom_rule : true,
					minlength: 1					
				}
		  },
		  submitHandler: function(form) {
			  
		 		 var str = $('form').serialize();		  
		 		 $("#smallLoader").css({display: "block"});
		  
				  $.ajax({
					  type: "POST",
					  url: "<?php echo SITEURL;?>/ajax/basket.php",  
					  data: str,
					  success: function (msg) {
						  
							$("#basketItemsWrap li:first").before(msg);
							$("#basketItemsWrap li:first").hide();
							$("#basketItemsWrap li:first").show("slow");  
							$("#smallLoader").css({display: "none"});
							$("#notifiyer").html(msg);
							
							$("html, body").animate({
							scrollTop: 0
							}, 600);							
							setTimeout(function() {       
									$(".jquery-notify-bar").fadeOut("slow");
									window.location.href= SITEURL + "/?location";
							},1000);		
												
						}
					});
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
   // procees add view cart button and checkout with total price ................................................
	  var delay = (function(){
		  var timer = 0;
		  return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		  };
		})();
		
	   $(".productPriceWrapRight a input").click(function() {
		delay(function(){
		$.ajax({
			type: "POST",  
					url: "<?php echo SITEURL; ?>/ajax/basket.php",  
					data: { action: "addViewCart"},  
					success: function(theResponse){			
				   $("#totalprice").html(theResponse).show();	
				   $("#hideprice").hide();	
				
			}
		});
		}, 2000);
	}); 
	
	 var delay2 = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
			
	$("#ADDTOCART").click(function() {
		delay2(function(){
		$.ajax({
			type: "POST",  
					url: "<?php echo SITEURL; ?>/ajax/basket.php",  
					data: { action: "orderitemheading"},  
					success: function(theResponse){			
				   $("#meniheading").html(theResponse).show();	
				   $("#hidemeniheading").hide();	
				
			}
		});
		}, 100);
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
   });
   /************Submit form data thruought AJAX ends here***********/
</script>
<script>
 $(document).ready(function(){
    $("[rel=tooltip]").tooltip({ placement: 'top'});
});
</script>
<script type="text/javascript">
/**show price top, starts here**/
 $(document).ready(function(){	
 
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
	
	
});
</script>
<script language="javaScript">
  $(document).ready(function(){
	  $(".imageDivLink").click(function(){
	  
		 var id =  $(this).attr('id');
	     $("#showToggle"+id).slideToggle();
		  
	  });	  
  });
</script>