<?php
  /**
   * Menu Option Manager
   *
  
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Menu Option Manager")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowByIdNew("res_menu_option_master","option_id", $menu->menuid); //echo "<pre>"; print_r($row); exit(); ?>

<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MIO_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MIO_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MIO_SUBTITLE1 . $row['option_name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form" enctype="multipart/form-data">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MIO_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_option_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MIO_OP_NMAE;?>: <?php echo required();?></th>
            <td><input name="option_name" type="text" class="inputbox"  size="55" value="<?php echo $row['option_name']; ?>"/></td>
          </tr>
          <tr>
            <th><?php echo _MIO_INST;?>: <?php echo required();?></th>
            <td><input name="instruction" type="text" class="inputbox"  size="55" value="<?php echo $row['instruction'];?>"/></td>
          </tr>
          <tr><th>Instruction Description:</th>
            <td colspan="1" class="editor"><textarea id="bodycontent" name="instruction_desc" rows="4" cols="30"><?php echo $core->out_url($row['instruction_desc']);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MIO_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?>/>
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0"  <?php getChecked($row['active'], 0); ?>/>
              </span></td>
          </tr>
          <tr>
            <th><?php echo _MIO_ISNOEMAL;?>:</th>
            <td><span class="input-out">
              <input name="is_normal_option" id="is_normal_option" type="checkbox" value="1" class="checkbox" <?php getChecked($row['is_normal_option'], 1); ?>/>
              <?php echo tooltip(_CG_ISNORMAL_T);?></span></td>
          </tr>
          <tr>
            <th>Hide Option :</th>
            <td><input name="hide_option" id="hide_option" type="checkbox" value="1" class="checkbox" <?php getChecked($row['hide_option'], 1); ?>/></td>
          </tr>
           <tr>
            <th>Hide Instruction :</th>
            <td><input name="hide_instruction" id="hide_instruction" type="checkbox" value="1" class="checkbox" <?php getChecked($row['hide_instruction'], 1); ?>/></td>
          </tr>
          <tr>
            <th>Click to Open:</th>
            <td><input name="click_to_open" id="click_to_open" type="checkbox" value="1" class="checkbox" <?php getChecked($row['click_to_open'], 1); ?>/></td>
          </tr>
          <tr>
            <th>Is Special calculation :</th>
            <td><input name="is_special_calc" id="is_special_calc" type="checkbox" value="1" class="checkbox" <?php getChecked($row['is_special_calc'], 1); ?>/></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0"><table class="forms" id="option_parent" style="display:none;">
                <tr>
                  <th><?php echo _MIO_COPTION;?>:</th>
                  <td>
                  <table width="100%" border="1">
                  <tr>
                    <td><select name="parent_id[]" multiple="multiple"  class="select" style="width:250px" size="10" id="locationid">
                                          <option value="">Please Select Option Name</option>
                                          <?php 
                                            $optionlist = $content->getMenuOptionDropDown($row['option_id']);
                                            $optionGroup = $content->getMenuOptionGroupList($row['option_id']); //only in edit case, only for to display to menu option							
                                            if($optionlist):						 
                                            foreach ($optionlist as $orow):
                                          ?>
                                           <option  value="<?php echo $orow['option_id'];?>" 
                                                <?php
                                                $display_order = ""; 
                                                   if($optionGroup !=0){   
                                                     foreach ($optionGroup as $grow):
                                                        if($orow['option_id']==@$grow['p_itemOption_id'])
														{ 
														echo $sel ='selected="selected"'; 
														$display_order .=   $grow['display_order'];
														}
														 else 
														 { 
														 echo $sel =""; 
														 $display_order .= "";
														 }
														
                                                     endforeach;
                                                   }
                                                ?> data-title="<?php echo $display_order;?>"><?php echo $orow['option_name'];?></option>
                                          <?php endforeach; endif;?>
                                    </select></td>
                   <td id="bindinputbox" style="vertical-align: top; text-align: left;"></td>
                  </tr>
                </table>
                  </td>
                </tr>
                <tr>
                  <th>Select Option:</th>
                  <td><span class="input-out">
                      <label for="show_title-1">Show as Select Box </label>
                      <input name="display_option" type="radio" id="show_title-1" value="1" <?php if($row['display_option']=='1'){ echo 'checked="checked"'; } ?>  />
                      <label for="show_title-2">Show Grid List</label>
                      <input name="display_option" type="radio" id="show_title-2" value="2" <?php if($row['display_option']=='2'){ echo 'checked="checked"'; } ?> />
                      </span>
              </td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0"><table class="forms" id="is_normal_hide">
                <tbody>
                  <tr>
                    <th><?php echo _MIO_CHOISE;?>:</th>
                    <td><span class="input-out">
                      <label><?php echo _MIO_MIN_CHOISE;?></label>
                      <input name="min_choise" type="text" class="inputbox"  size="20" value="<?php echo $row['min_choise'];?>"/>
                      <label><?php echo _MIO_MAX_CHOISE;?></label>
                      <input name="max_choise" type="text" class="inputbox"  size="20" value="<?php echo $row['max_choise'];?>"/>
                      </span> </td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_SIZEAFFP;?>:</th>
                    <td><span class="input-out">
                      <label for="size_affect_price-1"><?php echo _YES;?></label>
                      <input name="size_affect_price" type="radio" id="size_affect_price-1" value="1" <?php getChecked($row['size_affect_price'], 1); ?> />
                      <label for="size_affect_price-2"><?php echo _NO;?></label>
                      <input name="size_affect_price" type="radio" id="size_affect_price-2" value="0" <?php getChecked($row['size_affect_price'], 0); ?>/>
                      </span></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_OPTYPE;?>:</th>
                    <td><select name="option_type" class="custombox" style="width:300px">
                      <option value="r" <?php if($row['option_type']=='r') { echo 'selected="selected"'; } ?>>radiobutton</option>
                      <option value="c"  <?php if($row['option_type']=='c') { echo 'selected="selected"'; } ?>>checkbox</option>
                      <option value="d"  <?php if($row['option_type']=='d') { echo 'selected="selected"'; } ?>>dropdown</option>
                    </select></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_ISDEFAULT;?>:</th>
                    <td><input name="is_default" type="checkbox" value="1" class="checkbox" <?php getChecked($row['is_default'], 1); ?>/></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_HCHOICE;?>:</th>
                    <td><input name="have_choice" id="have_choice" type="checkbox" value="1" class="checkbox" <?php getChecked($row['have_choice'], 1); ?>/></td>
                  </tr>
                   <tr>
                    <th><?php echo _MIO_HTOOPING;?>:</th>
                    <td><input name="have_toppings" id="have_toppings" type="checkbox" value="1" class="checkbox" <?php getChecked($row['have_toppings'], 1); ?>/></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0">
            <table class="forms" id="choise_show" style="display:none;">
                <tbody>
                  <tr>
                    <td>
            			<table class="TextBoxesGroup" style="border: solid 1px #4F565B;">
                            <tbody>
                              <tr>
                                <th>Choice Name</th>
                                <th>Description </th>
                                <th>Image </th>
                                <th>Calculate Price</th>
                              </tr>
                              <?php $optionchoise = $menu->getoptionchoiselist($row['option_id']); 
							  if($optionchoise):
							  foreach($optionchoise as $omcrow):
							  ?>
                              <tr id="TextBoxDiv1">
                                <td><input name="choice_name[]" type="text" class="inputbox"  size="20" value="<?php echo $omcrow['choice_name'];?>"/></td>
                                <td><input name="choice_description[]" type="text" class="inputbox"  size="20" value="<?php echo $omcrow['choice_description'];?>"/></td>
                                <td><div class="fileuploader">
                                    <input type="text" class="filename" readonly="readonly" style="width:120px;"/>
                                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                                    <input type="file" name="fileinput[]" />
                                  </div></td>
                                <td><select name="is_add_price[]" class="custombox" style="width:100px">
                                    <option value="1"  <?php if($omcrow['is_add_price']=='1') { echo 'selected="selected"'; } ?>>YES</option>
                                    <option value="0"  <?php if($omcrow['is_add_price']=='0') { echo 'selected="selected"'; } ?>>NO</option>
                                  </select>
                                  <input type="hidden"  name="optionid[]" value="<?php echo $omcrow['option_choice_id']; ?>"/>
                                  
                                </td>
                                 <td> <a href="javascript:void(0);" class="deletechoice" data-title="<?php echo $omcrow['choice_name'];?>" id="item_<?php echo $omcrow['option_choice_id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a>                                    
                                      </td>
                              </tr>
                              <?php endforeach; endif;?>
                            </tbody>
              		</table>
             		 </td>
                    <td style="vertical-align: bottom; width: 10%;">
                    <img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/> 
                    <img src="images/delete.png" id='removeButton' style="cursor:pointer; margin-left:5px;"  class="tooltip"  alt="" title="Remove new field"/>
                     </td>
              </tr>
              </tbody>
              </table>
              </td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0">
            <table class="forms" id="topping_show" style="display:none;">
                <tbody>
                  <tr>
                    <td><div id="demo-grid">                        
                        <table style="border: solid 1px #4F565B;" id="TappingGroup" class="forms">
                          <tbody>
                            <tr>                               
                                <td >Menu Toppings Name</td>
                                <td >Display Order</td>
                                <td > OutofStock</td>
                                <td>Is Default</td>
                                <td >MenuItem</td>
                                <td >&nbsp;</td>
                              </tr>
                              <?php
							  	 $optiontoppiing = $menu->getTopingOptionList($row['option_id']); 
								 $j = count( $optiontoppiing);
								  if($optiontoppiing):
								 	 $i= 0;
								 	 foreach($optiontoppiing as $omtrow):
								  		$i++;
							  ?>
                              <tr rowchoiceno="0" id="Toppingtr<?php echo $i; ?>">                                      
                                      <td><input name="topping_name[]" id="toppingnameedit<?php echo $i; ?>" type="text" class="inputbox"  size="40" value="<?php echo $omtrow['topping_name'];?>"/></td>
                                      <td><input name="display_order[]" type="text" value="<?php echo $omtrow['display_order'];?>" /></td>
                                      <td><input name="out_of_stock[<?php echo $i-1; ?>]" type="checkbox" value="1" class="checkbox" <?php getChecked($omtrow['out_of_stock'], 1); ?>/></td>
                                       <td><input name="is_defaulttopping[<?php echo $i-1; ?>]" type="checkbox" value="1" class="checkbox" <?php getChecked($omtrow['is_default'], 1); ?>/></td>
                                      <td><select name="change_value[]"  style="width:300px" onchange="getvalueFrom_toppingitem('<?php echo $i; ?>',this.value)">
											<?php $itemmenulist = $menu->getToppingMenuItem(); ?>
                                            <option value="">please select</option>
                                            <?php foreach ($itemmenulist as $irow):?>
                                            <?php
												/*****This is for showing selected item starts here*******/ 
											   if(isset($omtrow['menu_size_map_id']) && $omtrow['menu_size_map_id'] == 0 ) { 
											   											   
											  		$menuSizeMapID = $omtrow['menu_size_map_id']; $menuSizeMapID = "";
												} 
											   else { 											   
											   		$menuSizeMapID = $omtrow['menu_size_map_id'];
												}
												/*****This is for showing selected item ends here*******/ 
											?>
                                            <option value="<?php echo $irow['MenuItemID'];?>/<?php echo $irow['MenuSizeMapID'];?>" <?php if($irow['MenuItemID'].'/'.$irow['MenuSizeMapID'] == $omtrow['menu_item_id'] .'/'.$menuSizeMapID) { echo 'selected="selected"';}  ?>><?php echo $irow['price'];?></option>
                                            <?php  endforeach;?>
                                        </select>
                                         <input type="hidden" name="menu_size_map_id[]" value="<?php echo $omtrow['menu_size_map_id'];?>"  id="MenuSizeMapIDedit<?php echo $i; ?>" />
                                        <input type="hidden" name="menu_item_id[]" value="<?php echo $omtrow['menu_item_id'];?>"  id="MenuItemIDedit<?php echo $i; ?>" />
                                        <input type="hidden"  name="toppingid[]" value="<?php echo $omtrow['option_topping_id']; ?>"/>
                                      </td>
                                      <td> <a href="javascript:void(0);" class="delete" data-title="<?php echo $omtrow['topping_name'];?>" id="item_<?php echo $omtrow['option_topping_id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a>                                    
                                      </td>
                                    </tr>  
                                    <?php endforeach; endif; ?>                                                                                
                          </tbody>
                        </table>
                      </div></td>
                    <td style="vertical-align: bottom; width: 10%;">
                    <img src="images/add.png" id='addToppings' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/>
                    <img src="images/delete.png" id='removeToppingButton' style="cursor:pointer; margin-left:5px;"  class="tooltip"  alt="" title="Remove new field"/>
                     </td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
        </tbody>
      </table>
      <input name="menuid" type="hidden" value="<?php echo $menu->menuid;?>" />
    </form>
  </div>
</div>
<script type="text/javascript">
<?php if($row['is_normal_option']=='0'){?> $("#option_parent").show();$('#is_normal_hide').hide(); <?php }?>
 <?php if($row['have_choice']=='1'){?> $("#choise_show").show(); <?php }?>
 <?php if($row['have_toppings']=='1'){?> $("#topping_show").show(); <?php }?>
 $('#is_normal_option').live("click", function() {
    if (this.checked) {	
        $('#is_normal_hide').show();
		$('#option_parent').hide();
    }
    else {
	
        $('#is_normal_hide').hide();
		$('#option_parent').show();
		 $('#choise_show').hide();
		 $('#topping_show').hide();	
    }
});
 $('#have_choice').live("click", function() {
    if (this.checked) {	
        $('#choise_show').show();		
    }
    else {
	
        $('#choise_show').hide();		
    }
});
 $('#have_toppings').live("click", function() {
    if (this.checked) {	
        $('#topping_show').show();		
    }
    else {
	
        $('#topping_show').hide();		
    }
});
onloadoptionorderBy();
function onloadoptionorderBy()
{
var str = "";
$('#locationid option:selected' ).each(function() {
		var display_order = $(this).attr("data-title");
		str += "<table width=\"100%\" border=\"1\"><tr><td style=\"width: 100px;\">"+ $(this ).text() + "</td><td><input type=\"text\" name=\"display_order[]\" class=\"inputbox\" value=\""+display_order +"\"></td></tr></table>";		
		});
$( "#bindinputbox" ).html( str );
}
		
$('#locationid').change(function () {
		var str = "";
		$('#locationid option:selected' ).each(function() {
		var display_order = $(this).attr("data-title");		
		str += "<table width=\"100%\" border=\"1\"><tr><td style=\"width: 100px;\">"+ $(this ).text() + "</td><td><input type=\"text\" name=\"display_order[]\" class=\"inputbox\" value=\""+display_order +"\"></td></tr></table>";
		
		});
		$( "#bindinputbox" ).html( str );
	});
</script>
<script>
// onclick generate new texbox  start 
$(document).ready(function(){
	var increment=2;	
	 // onclick Add new Field of Chosice  
  $("#addButton").click(function(){
		// validation texbox more then 10 field add
	  if(increment > 20)
	  {
		//alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }
	  // two methods
	  // first memthod...we can write div element
		var maincontent='<td><input name=\"choice_name[]\" type=\"text\" class=\"inputbox\"  size=\"20\"></td><td><input name="choice_description[]" type="text" class="inputbox"  size="20"></td><td><div class="fileuploader"><input type="text" class="filename" readonly="readonly" style="width:120px;"/><input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/><input type="file" name="fileinput[]" /></div></td><td><select name="is_add_price[]" class="custombox selectboxnew" style="width:100px"><option value="0">YES</option><option value="0">NO</option></select></td>';	
		var fun="<div id='TextBoxDiv"+increment+"'>"+maincontent+"</div>";	
			
	  var newTextBoxDiv = $(document.createElement('tr')).attr('id', 'TextBoxDiv' +increment);
	  newTextBoxDiv.after('').html(maincontent);	  
	 $(newTextBoxDiv).appendTo(".TextBoxesGroup");
	
	increment++;
  });
  
  // remove onclick Choise option   
  $("#removeButton").click(function () {  
		    if(increment==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        increment--;
			
	        $("#TextBoxDiv" + increment).remove();
			$("#TextAreaDiv" + increment).remove();
		});
	
	var incrementtopping="<?php echo $j+1;?>";	
	// onclick Add new Field of Toppings  
  $("#addToppings").click(function(){
		// validation texbox more then 10 field add
	  if(incrementtopping > 20)
	  {
		//alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }

		var toppingmaincontent='<td><input name=\"topping_name[]"+increment+"\" type=\"text\" class=\"inputbox\"  size=\"40\" id=\"toppingnameedit'+incrementtopping+'\"></td><td><input name=\"display_order[]\" type=\"text\" /></td><td><input name=\"out_of_stock['+incrementtopping+']\" type=\"checkbox"\ value=\"1\" class=\"checkbox\"></td><td><input name=\"is_defaulttopping['+incrementtopping+']\" type=\"checkbox"\ value=\"1\" class=\"checkbox\"></td><td><select name=\"change_value[]\" class=\"custombox\" style=\"width:300px\" onchange=\"getvalueFrom_toppingitem('+incrementtopping+',this.value)\"><?php $itemmenulist = $menu->getToppingMenuItem(); ?><option value=\"\">please select</option><?php foreach ($itemmenulist as $irow):?><option value=\"<?php echo $irow['MenuItemID'];?>/<?php echo $irow['MenuSizeMapID'];?>\"><?php echo $irow['price'];?></option><?php endforeach;?></select></td><td><input type="hidden" name="menu_size_map_id[]" value=""  id="MenuSizeMapIDedit'+incrementtopping+'" /><input type="hidden" name="menu_item_id[]" value=""  id="MenuItemIDedit'+incrementtopping+'" /></td>';	
		
	
	  var ToppingTextBoxDiv = $(document.createElement('tr')).attr('id', 'Toppingtr' +incrementtopping);
	  ToppingTextBoxDiv.after('').html(toppingmaincontent);	  
	  $(ToppingTextBoxDiv).appendTo("#TappingGroup");
	
	  incrementtopping++;	
  });
  
  // remove onclick Topping option   
  $("#removeToppingButton").click(function () {  
		    if(incrementtopping==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        incrementtopping--;
			
			
	        $("#Toppingtr" + incrementtopping).remove();
			
			//$("#TextAreaDiv" + incrementtopping).remove();
		});
		
});
</script>

<script type="text/javascript">
function getvalueFrom_toppingitem(n,pid){
	
	$.post('ajax.php',{"prid":pid,'action':'selectvalTopping'},function(data){
		$("#toppingnameedit"+n).val(data.item_name);
		$("#MenuItemIDedit"+n).val(data.item_id);
		$("#MenuSizeMapIDedit"+n).val(data.menu_sizemap_id);
	},'json');
}
</script>

<?php echo $core->doForm("processMenuOption","controller.php");?>
<?php echo Core::doDelete(_DELETE.' '._MENU_OPTION, "deleteToppingItem");?>
<?php echo Core::doDeleteNew(_DELETE.' '._MENU_CHOICE, "a.deletechoice", "deleteChoiceItem");?>
<?php break;?>


<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MIO_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MIO_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MIO_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form" enctype="multipart/form-data">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MIO_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_option_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>           
            <th><span title="Item Option Name"><?php echo _MIO_OP_NMAE;?></span>: <?php echo required();?></th>
            <td><input name="option_name" type="text" class="inputbox"  size="55" title="<?php echo _MIO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _MIO_INST;?>: <?php echo required();?></th>
            <td><input name="instruction" type="text" class="inputbox"  size="55" title="<?php echo _MIO_INST_R;?>"/></td>
          </tr>
          <tr><th>Instruction Description:</th>
            <td colspan="1" class="editor"><textarea id="bodycontent" name="instruction_desc" rows="4" cols="30"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MIO_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _MIO_ISNOEMAL;?>:</th>
            <td><span class="input-out">
              <input name="is_normal_option" id="is_normal_option" type="checkbox" value="1" class="checkbox" checked="checked"/>
              <?php echo tooltip(_CG_ISNORMAL_T);?></span></td>
          </tr>
          <tr>
            <th>Hide Option :</th>
            <td><input name="hide_option" id="hide_option" type="checkbox" value="1" class="checkbox"/></td>
         </tr>
          <tr>
            <th>Hide Instruction :</th>
            <td><input name="hide_instruction" id="hide_instruction" type="checkbox" value="1" class="checkbox"/></td>
          </tr>
          <tr>
            <th>Click to Open:</th>
            <td><input name="click_to_open" id="click_to_open" type="checkbox" value="1" class="checkbox"/></td>
         </tr>
          <tr>
            <th>Is Special calculation :</th>
            <td><input name="is_special_calc" id="is_special_calc" type="checkbox" value="1" class="checkbox" /></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0">
            <table class="forms" id="option_parent" style="display:none;">
                <tr>
                  <th><?php echo _MIO_COPTION;?>:</th>
                  <td>
                  <table width="100%" border="1">
  <tr>
    <td><select name="parent_id[]" multiple="multiple"  class="select" style="width:250px" size="20" id="locationid">
                      <?php $optionlist = $menu->getMenuOptionDropDownAdd();?>
                      <option value="">Please Select Option Name</option>
                      <?php 
					  if($optionlist):
					  	foreach ($optionlist as $orow):
					  ?>                      
                      <option value="<?php echo $orow['option_id'];?>"><?php echo $orow['option_name'];?></option>
                      <?php endforeach; endif;?>
                    </select></td>
     <td id="bindinputbox" style="vertical-align: top; text-align: left;"></td>
  </tr>
</table>

                  
                  </td>
                 
                </tr>
                <tr>
                  <th>Select Option:</th>
                  <td><span class="input-out">
                      <label for="show_title-1">Show as Select Box </label>
                      <input name="display_option" type="radio" id="show_title-1" value="1" checked="checked" />
                      <label for="show_title-2">Show Grid List</label>
                      <input name="display_option" type="radio" id="show_title-2" value="2" />
                      </span>
              </td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0"><table class="forms" id="is_normal_hide">
                <tbody>
                  <tr>
                    <th><?php echo _MIO_CHOISE;?>:</th>
                    <td><span class="input-out">
                      <label><?php echo _MIO_MIN_CHOISE;?></label>
                      <input name="min_choise" type="text" class="inputbox"  size="20" title="<?php echo _MIO_INST_R;?>"/>
                      <label><?php echo _MIO_MAX_CHOISE;?></label>
                      <input name="max_choise" type="text" class="inputbox"  size="20" title="<?php echo _MIO_INST_R;?>"/>
                      </span> </td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_SIZEAFFP;?>:</th>
                    <td><span class="input-out">
                      <label for="size_affect_price-1"><?php echo _YES;?></label>
                      <input name="size_affect_price" type="radio" id="size_affect_price-1" value="1" checked="checked" />
                      <label for="size_affect_price-2"><?php echo _NO;?></label>
                      <input name="size_affect_price" type="radio" id="size_affect_price-2" value="0" />
                      </span></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_OPTYPE;?>:</th>
                    <td><select name="option_type" class="custombox" style="width:300px">
                        <option value="r">radiobutton</option>
                        <option value="c">checkbox</option>
                        <option value="d">dropdown</option>
                      </select></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_ISDEFAULT;?>:</th>
                    <td><input name="is_default" type="checkbox" value="1" class="checkbox"/></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_HCHOICE;?>:</th>
                    <td><input name="have_choice" id="have_choice" type="checkbox" value="1" class="checkbox"/></td>
                  </tr>
                  <tr>
                    <th><?php echo _MIO_HTOOPING;?>:</th>
                    <td><input name="have_toppings" id="have_toppings" type="checkbox" value="1" class="checkbox"/></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr>
            <td colspan="2" style="padding:0">
            <table class="forms" id="choise_show" style="display:none;">
                <tbody>
                  <tr>
                    <td>
            			<table class="TextBoxesGroup" style="border: solid 1px #4F565B;">
                            <tbody>
                              <tr>
                                <th>Choice Name</th>
                                <th>Description </th>
                                <th>Image </th>
                                <th>Calculate Price</th>
                              </tr>
                              <tr id="TextBoxDiv1"><!--add-->
                                <td><input name="choice_name[]" type="text" class="inputbox"  size="20" title="<?php echo _MIO_INST_R;?>"/></td>
                                <td><input name="choice_description[]" type="text" class="inputbox"  size="20" title="<?php echo _MIO_INST_R;?>"/></td>
                                <td><div class="fileuploader">
                                    <input type="text" class="filename" readonly="readonly" style="width:120px;"/>
                                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                                    <!--<input type="file" name="choice_image[]" />-->
                                    <input type="file" name="fileinput[]" />
                                  </div></td>
                                <td><select name="is_add_price[]" class="custombox" style="width:100px">
                                    <option value="1">YES</option>
                                    <option value="0">NO</option>
                                  </select>
                                </td>
                              </tr>
                            </tbody>
              		</table>
             		 </td>
                    <td style="vertical-align: bottom; width: 10%;">
                    <img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/> 
                    <img src="images/delete.png" id='removeButton' style="cursor:pointer; margin-left:5px;"  class="tooltip"  alt="" title="Remove new field"/>
                     </td>
              </tr>
              </tbody>
              </table>
              </td>
          </tr>
          <tr>
            <td style="padding:0" colspan="2">
            <table class="forms" id="topping_show" style="display:none;">
                <tbody>
                  <tr>
                    <td><div id="demo-grid">                        
                        <table style="border: solid 1px #4F565B;" id="TappingGroup" class="forms">
                          <tbody>
                            <tr>                               
                                <td >Menu Toppings Name</td>
                                 <td >Display Order</td>
                                <td > OutofStock</td>
                                 <td>Is Default</td>
                                <td >MenuItem</td>
                                <td >&nbsp;</td>
                              </tr>
                              <tr rowchoiceno="0" id="Toppingtr1">                                      
                                <td>
                                <input name="topping_name[]" type="text" id="toppingname1" class="inputbox"  size="40" title="<?php echo _MIO_INST_R;?>"/>
                                </td>
                                   <td><input name="display_order[]" type="text" /></td>
                                <td><input name="out_of_stock[0]" type="checkbox" value="1" class="checkbox"/></td>
                                  <td><input name="is_defaulttopping[0]" type="checkbox" value="1" class="checkbox" /></td>
                                <td>
                                  <select name="change_value" style="width:300px" onchange="getvalueFrom_toppingitem('1',this.value)">
									<?php $itemmenulist = $menu->getToppingMenuItem(); ?>
                                    <option value="">please select</option>
                                    <?php foreach ($itemmenulist as $irow):?>
                                    <option value="<?php echo $irow['MenuItemID'];?>/<?php echo $irow['MenuSizeMapID'];?>"><?php echo $irow['price'];?></option>
                                    <?php endforeach;?>
                                  </select>
                                    <input type="hidden" name="menu_size_map_id[]" value=""  id="MenuSizeMapID1" />
                                    <input type="hidden" name="menu_item_id[]" value=""  id="MenuItemID1" />
                                  </td>
                                  <td>                                     
                                  </td>
                              </tr>                                                                                  
                          </tbody>
                        </table>
                      </div></td>
                    <td style="vertical-align: bottom; width: 10%;">
                    <img src="images/add.png" id='addToppings' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/>
                    <img src="images/delete.png" id='removeToppingButton' style="cursor:pointer; margin-left:5px;"  class="tooltip"  alt="" title="Remove new field"/>                     
                     </td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
   $('#locationid').change(function () {
		var str = "";
		$('#locationid option:selected' ).each(function() {
		str += "<table width=\"100%\" border=\"1\"><tr><td style=\"width: 100px;\">"+ $(this ).text() + "</td><td><input type=\"text\" name=\"display_order[]\" class=\"inputbox\"></td></tr></table>";
		
		});
		$( "#bindinputbox" ).html( str );
	});
 $('#is_normal_option').live("click", function() {
    if (this.checked) {	
        $('#is_normal_hide').show();
		$('#option_parent').hide();
    }
    else {
	
        $('#is_normal_hide').hide();
		$('#option_parent').show();
		 $('#choise_show').hide();
		 $('#topping_show').hide();	
    }
});
 $('#have_choice').live("click", function() {
    if (this.checked) {	
        $('#choise_show').show();		
    }
    else {
	
        $('#choise_show').hide();		
    }
});
 $('#have_toppings').live("click", function() {
    if (this.checked) {	
        $('#topping_show').show();		
    }
    else {
	
        $('#topping_show').hide();		
    }
});
</script>
<script>
// onclick generate new texbox  start 
$(document).ready(function(){
	var increment=2;	
	 // onclick Add new Field of Chosice  
  $("#addButton").click(function(){
		// validation texbox more then 10 field add
	  if(increment > 20)
	  {
		//alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }
	  // two methods
	  // first memthod...we can write div element
		var maincontent='<td><input name=\"choice_name[]\" type=\"text\" class=\"inputbox\"  size=\"20\"></td><td><input name="choice_description[]" type="text" class="inputbox"  size="20"></td><td><div class="fileuploader"><input type="text" class="filename" readonly="readonly" style="width:120px;"/><input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/><input type="file" name="fileinput[]" /></div></td><td><select name="is_add_price[]" class="custombox selectboxnew" style="width:100px"><option value="0">YES</option><option value="0">NO</option></select></td>';	
		var fun="<div id='TextBoxDiv"+increment+"'>"+maincontent+"</div>";	
			
	  var newTextBoxDiv = $(document.createElement('tr')).attr('id', 'TextBoxDiv' +increment);
	  newTextBoxDiv.after('').html(maincontent);	  
	 $(newTextBoxDiv).appendTo(".TextBoxesGroup");
	
	increment++;
  });
  
  // remove onclick Choise option   
  $("#removeButton").click(function () {  
		    if(increment==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        increment--;
			
	        $("#TextBoxDiv" + increment).remove();
			$("#TextAreaDiv" + increment).remove();
		});
	
	var incrementtopping=2;	
	// onclick Add new Field of Toppings  
  $("#addToppings").click(function(){
		// validation texbox more then 10 field add
	  if(incrementtopping > 20)
	  {
		alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }
		var countingfield = incrementtopping-1;
		var toppingmaincontent='<td><input name=\"topping_name[]"+increment+"\" type=\"text\" class=\"inputbox\"  size=\"40\" id=\"toppingname'+incrementtopping+'\"></td><td><input name=\"display_order[]\" type=\"text\" /></td><td><input name=\"out_of_stock['+countingfield+']\" type=\"checkbox"\ value=\"1\" class=\"checkbox\"></td><td><input name=\"is_defaulttopping['+countingfield+']\" type=\"checkbox"\ value=\"1\" class=\"checkbox\"></td><td><select name=\"change_value[]\" class=\"custombox\" style=\"width:300px\" onchange=\"getvalueFrom_toppingitem('+incrementtopping+',this.value)\"><?php $itemmenulist = $menu->getToppingMenuItem(); ?><option value=\"\">please select</option><?php foreach ($itemmenulist as $irow):?><option value=\"<?php echo $irow['MenuItemID'];?>/<?php echo $irow['MenuSizeMapID'];?>\"><?php echo $irow['price'];?></option><?php endforeach;?></select></td><td><input type="hidden" name="menu_size_map_id[]" value=""  id="MenuSizeMapID'+incrementtopping+'" /><input type="hidden" name="menu_item_id[]" value=""  id="MenuItemID'+incrementtopping+'" /></td>';	
		var toppingfun="<div id='Toppingtr"+incrementtopping+"'>"+toppingmaincontent+"</div>";
		
	
	  var ToppingTextBoxDiv = $(document.createElement('tr')).attr('id', 'Toppingtr' +incrementtopping);
	  ToppingTextBoxDiv.after('').html(toppingmaincontent);	  
	 $(ToppingTextBoxDiv).appendTo("#TappingGroup");
	
	incrementtopping++;	
  });
  
  // remove onclick Topping option   
  $("#removeToppingButton").click(function () {  
		    if(incrementtopping==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        incrementtopping--;
			
			
	        $("#Toppingtr" + incrementtopping).remove();
			
			//$("#TextAreaDiv" + incrementtopping).remove();
		});
		
});
</script>
<script>
function getvalueFrom_toppingitem(n,pid){
$.post('ajax.php',{"prid":pid,'action':'selectvalTopping'},function(data){
$("#toppingname"+n).val(data.item_name);
$("#MenuItemID"+n).val(data.item_id);
$("#MenuSizeMapID"+n).val(data.menu_sizemap_id);
},'json');

}
</script>
<?php echo $core->doForm("processMenuOption","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $menuoptionrow = $menu->getMenuOption();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MIO_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MIOINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=menu_option_master&amp;action=add" class="button-sml"><?php echo _MIO_ADD;?></a></span><?php echo _MIO_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display">
        <tr>
        <form action="" method="post" id="dForm">
            <td><input type="text" class="inputbox" id="searchfield" size="30" name="optionsearchfield" />
              <input name="search_keyword" type="submit" class="button-blue" value="Search" />
              <div style="position:relative">
                <div id="suggestions" class="box2" style="display:none"></div>
              </div></td>
          </form>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _MIO_OP_NMAE;?></th>
          <th class="left sortable"><?php echo _MIO_INST;?></th>
          <th><?php echo _MIO_DEFAULT;?></th>
          <th><?php echo _MIO_OPTYPE;?></th>
          <th><?php echo _MIO_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="7"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$menuoptionrow):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(_MIO_NOOPTIONMENU,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($menuoptionrow as $row):?>
        <tr>
          <th><?php echo $row['option_id'];?>.</th>
          <td><?php echo $row['option_name'];?></td>
          <td><?php echo $row['instruction'] ;?></td>
          <td><input type="checkbox" value="1" disabled="disabled"  <?php getChecked($row['is_default'], 1); ?>/></td>
          <td class="center"><?php if($row['option_type']=='r') { echo "radiobutton"; } elseif($row['option_type']=='c') { echo "checkbox"; } else { echo "dropdown"; }?></td>
          <td class="center"><a href="index.php?do=menu_option_master&amp;action=edit&amp;menuid=<?php echo $row['option_id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MIO_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['option_name'];?>" id="item_<?php echo $row['option_id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MENU_OPTION, "deleteMenuOption");?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            3: {
                sorter: false
            },
            4: {
                sorter: false
            },
            5: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>

<?php break;?>
<?php endswitch;?>
