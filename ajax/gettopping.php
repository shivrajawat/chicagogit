<?php
  /**
   * GET TOPPING IF OPTION TYPE DROPDOWN
   *
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
if(!empty($_GET['Option'])):
$isnormaloption = $menu->isNormalOption($_GET['Option']);
	if($isnormaloption):
		$optiontopping = $menu->gettoopinglist($_GET['Option']); ?>
        <?php if($optiontopping){
					$num = 1;
					foreach($optiontopping as $oprow){
			   ?>
            <h3 class="toppings "><?php echo $oprow['instruction'];?>:</h3>
            <div class="span12 fit">
               <input type="hidden" name="topping[<?php echo $num ;?>][id]" value="<?php echo $oprow['option_id'];?>" />
             
                   <?php 
		$toppingrow = $menu->topingByItem($oprow['option_id']);
		if($oprow['option_type']=='r'){
			$toppingrow = $menu->topingByItem($oprow['option_id']);
				if($toppingrow){				
				foreach($toppingrow as $tprow){?><label class="span6 checkbox inline fit"> 
			<?php 
			if($oprow['min_choise']>0)
			{
			echo '<div class="span2 toppingdetail"><input type="radio" value="'.$tprow['option_topping_id'].'" name="topping[' . $num . '][val]"  id="inlineCheckbox1" required></div><div class="span7">'.$tprow['topping_name'].' ($'.$tprow['price'].')</div><input type="hidden" name="topping[' . $num . '][price]"  value="'.$tprow['price'].'" /><input type="hidden" name="topping[<?php echo $num ;?>][option_topping_name]" value="'.$tprow['topping_name'].'" />'; }
				else
				{
					echo '<div class="span2 toppingdetail"><input type="radio" value="'.$tprow['option_topping_id'].'" name="topping[' . $num . '][val]"  id="inlineCheckbox1" ></div><div class="span7">'.$tprow['topping_name'].' ($'.$tprow['price'].')</div><input type="hidden" name="topping[' . $num . '][price]"  value="'.$tprow['price'].'" /><input type="hidden" name="topping[<?php echo $num ;?>][option_topping_name]" value="'.$tprow['topping_name'].'" />'; 
				}?> 
            </label><?php  } }
			 }
		elseif($oprow['option_type']=='c'){			
				if($toppingrow){				
				foreach($toppingrow as $tprow){?>
         <label class="span6 checkbox inline fit">
          <?php 	if($oprow['min_choise']>0)
			{
				echo '<div class="span2 toppingdetail"><input type="checkbox" name="topping[' . $num . '][val][]" value="'.$tprow['option_topping_id'].'"  id="inlineCheckbox1" data-maxlength="'.$tprow['max_choise'].'" data-minlength="'.$tprow['min_choise'].'" required/></div><div class="span7">'.$tprow['topping_name'].' ($'.$tprow['price'].')</div><input type="hidden" name="topping[' . $num . '][price]"  value="'.$tprow['price'].'" /><input type="hidden" name="topping[<?php echo $num ;?>][option_topping_name]" value="'.$tprow['topping_name'].'" />';
					
				}
				else
				{
					echo '<div class="span2 toppingdetail"><input type="checkbox" name="topping[' . $num . '][val][]" value="'.$tprow['option_topping_id'].'"  id="inlineCheckbox1" /></div><div class="span7">'.$tprow['topping_name'].' ($'.$tprow['price'].')</div><input type="hidden" name="topping[' . $num . '][price]"  value="'.$tprow['price'].'" /><input type="hidden" name="topping[' . $num . '][option_topping_name]" value="'.$tprow['topping_name'].'" />';	
				}	?>
        </label><?php  } }
			 } 		
		else {
			if($toppingrow){?>	
            <label class="span6 checkbox inline fit">			
		<select name="topping[<?php echo  $num; ?>][val]">
          <?php  foreach($toppingrow as $trow): ?>
          <option value="<?php echo $trow['option_topping_id'];?>"><?php echo $trow['topping_name']."(".$tprow['price'].")";?></option>
          <?php endforeach;?>
        </select>
        </label>
				<?php }
			
			 } ?>
            
            </div>
            <div class="clr"></div>
            <?php $num++; } } ?>
<?php else: ?>
<div class="gray-box">
  <div class="form-group">
    <label for="inputEmail1" class="span12 col-lg-2 control-label">Size and Type:</label>
    <div class="col-lg-10">
      <?php  $childoption = $menu->optionchild($_GET['Option']); ?>
      <select class="form-control" onchange="changetooping(this.value)">
        <?php $childoption = $menu->optionchild($mrow['option_id']); ?>
        <option value="">Please Select</option>
        <?php foreach($childoption as $chrow):?>
        <option value="<?php echo $chrow['option_id'];?>"><?php echo $chrow['option_name'];?></option>
        <?php endforeach;?>
      </select>
    </div>
  </div>
</div>
<?php endif;?>
<?php endif
?>
