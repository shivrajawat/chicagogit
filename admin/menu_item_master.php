<?php
  /**
   * Menu Item  Manager
 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("MenuItem")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_menu_item_master", $menu->menuid); ?>

<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MITEM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MITEM_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MITEM_EDIT . $row['item_name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MITEM_EDIT;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_item_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MITEM_TYPE;?>:<?php echo required();?></th>
            <td><select name="item_type" class="custombox" style="width:300px" id="hidepricesize">
                <option value="0" <?php if($row['item_type']=='0') { echo 'selected="selected"'; } ?> >Regular</option>
                <option value="1" <?php if($row['item_type']=='1') { echo 'selected="selected"'; } ?>>Sized</option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MCAT;?>:<?php echo required();?></th>
            <td><select name="category_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $menucategoryrow = $content->getmenucategoryList();?>
                <option value="">Select Category Name</option>
                <?php foreach ($menucategoryrow as $crow):?>
                <?php $sel = ($row['category_id'] == $crow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $crow['id'];?>" <?php echo $sel;?>><?php echo $crow['category_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo MTYPE;?>:<?php echo required();?></th>
            <td><select name="menu_type" class="custombox" style="width:300px">
                <option value="MenuItem" <?php echo ($row['menu_type'] =='MenuItem') ? ' selected="selected"' : '' ;?>>Menu Item</option>
                <option value="calculate"<?php echo ($row['menu_type'] =='calculate') ? ' selected="selected"' : '' ;?>>Pizza Item</option>
                <option value="topping" <?php echo ($row['menu_type']=='topping') ? ' selected="selected"' : '' ;?>>Pizza Topping</option>
                <option value="crust" <?php echo  ($row['menu_type'] == 'crust') ? ' selected="selected"' : '' ;?>>Pizza Crust</option>
              
                <option value="sauce" <?php echo ($row['menu_type']=='sauce') ? ' selected="selected"' : '' ;?>>Pizza Sauce</option>
                <option value="prep_inst" <?php echo  ($row['menu_type']=='prep_inst') ? ' selected="selected"' : '' ;?>>Prep Instruction</option>
                <option value="cheese" <?php echo ($row['menu_type']=='cheese') ? ' selected="selected"' : '' ;?>>Cheese</option>
                <option value="modifier" <?php  echo ($row['menu_type']=='modifier') ? ' selected="selected"' : '' ;?>>Modifier</option>
              </select></td>
          </tr>
          <tr>
            <th>Special Menu Icon:</th>
            <td>
            	<select name="special_menu_icon[]" id="special_menu_icon" multiple="multiple" class="" style="width:300px">
					<?php $menu_row = $menu->getSpecialMenuListIds(); ?>
                    <option value="">Select Special Menu Icon</option>
                    <?php 
						$arr = explode(",", $row['special_menu_icon']);	
						foreach ($menu_row as $crow): 
				   
						$sel = (in_array($crow['special_id'], $arr)) ? "selected=\"selected\"" : "";
					?>           
                    <option value="<?php echo $crow['special_id'];?>" <?php echo $sel;?>><?php echo $crow['special_item_name'];?></option>
                    <?php endforeach;?>
              	</select>
            </td>
          </tr>
          <tr>
            <th><?php echo _MITEM_NAME;?>: </th>
            <td><input name="item_name" type="text" class="inputbox" value="<?php echo $row['item_name'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_SIN;?>: <?php echo required();?> </th>
            <td><input name="short_name" type="text" class="inputbox" value="<?php echo $row['short_name'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo MITEM_TID;?>: </th>
            <td><input name="ticket_item_id" type="text" class="inputbox"  value="<?php echo $row['ticket_item_id'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_DESC;?>:</th>
            <td><textarea name="item_description"  cols="53"  rows="6"><?php echo $row['item_description'];?></textarea>
            </td>
          </tr>
          <tr>
            <th><?php echo _MITEM_PRE;?>: </th>
            <td><input name="preparation_time" type="text" class="inputbox" value="<?php echo $row['preparation_time'];?>"  size="55" title=""/>(min)</td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text"> Available Days :</td>
            <td width="200" valign="middle" height="30" align="left" class="adminformT"><table id="">
                <tbody>
                  <tr>
                    <td><input type="checkbox" class="checkbox-all"  value="Mon"  name="available_days[]"  id="chk" <?php if(in_array("Mon", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for="">Mon</label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Tue"  name="available_days[]" id="chk" <?php if(in_array("Tue", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Tue </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Wed" name="available_days[]" id="chk" <?php if(in_array("Wed", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Wed </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Thu"  name="available_days[]" id="chk" <?php if(in_array("Thu", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Thu </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Fri"  name="available_days[]" id="chk" <?php if(in_array("Fri", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Fri </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Sat"  name="available_days[]" id="chk" <?php if(in_array("Sat", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Sat </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Sun"  name="available_days[]" id="chk" <?php if(in_array("Sun", explode(",", $row['available_days']))) echo "checked='checked'"; ?>>
                      <label for=""> Sun </label></td>
                    <td><input type="checkbox" class="checkbox-all" id="selectall" onClick="Mastercheckbox(this)" />
                      <label for="">Select All</label></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr>
            <th> Show Item IN Menu </th>
            <td><select class="custombox" style="width:300px" name="show_item_in_menu">
                <option value="1" <?php if($row['show_item_in_menu']=='1'){ ?> selected="selected" <?php }?>>Yes</option>
                <option value="0" <?php if($row['show_item_in_menu']=='0'){ ?> selected="selected" <?php }?>>No</option>
              </select></td>
          </tr>
          <tr>
            <th> Show Item In Option </th>
            <td><select name="show_item_in_option" class="custombox" style="width:300px">
                <option value="1" <?php if($row['show_item_in_option']=='1'){ ?> selected="selected" <?php } ?>>Yes</option>
                <option value="0" <?php if($row['show_item_in_option']=='0'){ ?> selected="selected" <?php } ?>>No</option>
              </select></td>
          </tr>
          <tr class="regular">
            <th><?php echo _MITEM_SIIM;?>: </th>
            <td><select name="size_id" class="custombox" style="width:300px">
                <?php $menusize = $menu->getMeniSizelist(); ?>
                <option value="">Select</option>
                <?php foreach ($menusize as $mzrow):?>
                <?php $sel = ($row['size_id'] == $mzrow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $mzrow['id'];?>"<?php echo $sel; ?>><?php echo $mzrow['size_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr class="regular">
            <th><?php echo _MITEM_PRICE;?>: <?php echo required();?> </th>
            <td><input name="price" type="text" class="inputbox"  size="55" title="" value="<?php echo $row['price'];?>"/></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text"> Show Nutrition </td>
            <td width="300" valign="middle" height="30" align="left" colspan="2"><select name="show_nutrition_info" class="custombox" style="width:300px">
                <option value="1" <?php if($row['show_nutrition_info']=='1'){ ?> selected="selected" <?php }?>>Yes</option>
                <option value="0" <?php if($row['show_nutrition_info']=='0'){ ?> selected="selected" <?php }?>>No</option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo MITEM_NUTRI;?>:</th>
            <td><textarea name="nutrition_ingredient" cols="53" rows="6"><?php echo $row['nutrition_ingredient'];?></textarea></td>
          </tr>
          <tr>
            <th> Min. Qty</th>
            <td><input type="text" class="inputbox" maxlength="3"  name="min_qty" value="<?php echo $row['min_qty'];?>">
              &nbsp; &nbsp;<span class="adminformT"> Max. Qty<span> &nbsp; &nbsp;
              <input type="text" class="inputbox" maxlength="4"  name="max_qty" value="<?php echo $row['max_qty'];?>">
              </span></span></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text">Order Type</td>
            <td width="300" valign="middle" height="30" align="left" colspan="2" class="adminformT"><table id="">
                <tr>
                  <td><input type="checkbox" class="checkbox-all" value="1"    name="pick_up" id="order1" onClick="OrderType_pickup()" <?php getChecked($row['pick_up'], 1); ?>>
                    <label for="">Pick Up</label></td>
                  <td><input type="checkbox" class="checkbox-all"  value="1"  name="delivery" id="order2" onClick="OrderType_delivery()" <?php getChecked($row['delivery'], 1); ?>>
                    <label for="">Delivery</label></td>
                  <td><input type="checkbox" class="checkbox-all" value="1"   name="dineln" id="order3" onClick="OrderType_dinein()" <?php getChecked($row['dineln'], 1); ?>>
                    <label for="">Dine In</label></td>
                  <td><input type="checkbox" class="checkbox-all" id="selectall_order" onClick="Mastercheckboxnew()" >
                    <label for="">Select All</label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text">Order Availability Time </td>
            <td width="300" valign="middle" height="30" align="left" colspan="2" class="adminformT"><table id="">
                <tr>
                  <td><input type="checkbox" class="checkbox-all" value="1"  id="food1"   name="breakfast" onClick="selectfood1()" <?php getChecked($row['breakfast'], 1); ?>>
                    <label for="">Breakfast</label></td>
                  <td><input type="checkbox" class="checkbox-all"  value="1" id="food2" name="lunch"  onClick="selectfood2()" <?php getChecked($row['lunch'], 1); ?>>
                    <label for="">Lunch</label></td>
                  <td><input type="checkbox" class="checkbox-all" value="1" id="food3"  name="dinner" onClick="selectfood3()" <?php getChecked($row['dinner'], 1); ?>>
                    <label for="">Dinner</label></td>
                  <td><input type="checkbox" class="checkbox-all" id="selectall_food" onClick="Mastercheckboxfood()" >
                    <label for="">Select All</label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text"> Show Image In Menu </td>
            <td width="300" valign="middle" height="30" align="left" colspan="2"><select name="show_image_in_menu" class="custombox" style="width:300px">
                <option value="1" <?php if($row['show_image_in_menu'] =='1'){ ?> selected="selected" <?php } ?>>Yes</option>
                <option value="0" <?php if($row['show_image_in_menu'] =='0'){ ?> selected="selected" <?php } ?>>No</option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_BII;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="big_item_image" />
              </div>
              <span>
              <?php if ($row['big_item_image']):?>
              <img src="<?php echo UPLOADURL;?>/menuitem/<?php echo $row['big_item_image'];?>" alt="<?php echo $row['big_item_image'];?>" class="avatar" width="72" height="72"/>
              <?php else:?>
              <img src="<?php echo UPLOADURL;?>/avatars/blank.png" alt="<?php echo $row['big_item_image'];?>" class="avatar"/>
              <?php endif;?>
              </span> </td>
          </tr>
          <tr>
            <th><?php echo _MITEM_TII;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="thumb_item_image" />
              </div>
              <span>
              <?php if ($row['thumb_item_image']):?>
              <img src="<?php echo UPLOADURL;?>/menuitem/thumb/<?php echo $row['thumb_item_image'];?>" alt="<?php echo $row['thumb_item_image'];?>" class="avatar" width="72" height="72"/>
              <?php else:?>
              <img src="<?php echo UPLOADURL;?>/avatars/blank.png" alt="<?php echo $row['thumb_item_image'];?>" class="avatar"/>
              <?php endif;?>
              </span></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text"> Out Of Stock </td>
            <td width="300" valign="middle" height="30" align="left" colspan="2"><select  name="out_of_stack" class="custombox" style="width:300px">
                <option value="1" <?php if($row['out_of_stack']=='1'){?> selected="selected" <?php }?>>Yes</option>
                <option value="0"<?php if($row['out_of_stack']=='0'){?> selected="selected" <?php }?>>No</option>
              </select>
            </td>
          </tr>
          <tr>
            <th> Display Order: </th>
            <td><input type="text" class="inputbox"  maxlength="3" value="<?php echo $row['display_order'];?>" name="display_order"></td>
          </tr>
          <tr>
            <th>Published:<?php echo required();?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php if($row['active']=='1'){ echo 'checked="checked"';}   ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <?php //if($row['item_type']=='1'){ $shw = 'style="display:block"'; } else { $shw = '"display:none;"'; }?>
          <tr class="sized">
            <td colspan="2"><table>
                <thead>
                  <tr>
                    <td>Size</td>
                    <td>Price</td>
                  </tr>
                </thead>
                <tr>
                  <td colspan="2"><div id='TextBoxesGroup'>
                      <div id="TextBoxDiv1">
                        <?php $sizeIteamMapping = $menu->getMeniSizeMappingarray($row['id']);
						if($sizeIteamMapping):
						foreach($sizeIteamMapping as $smrow):
					?>
                        <select name="sized_size_id[]" class="custombox" style="width:300px" >
                          <?php $menusize = $menu->getMeniSizelist(); ?>
                          <option value="">Select</option>
                          <?php foreach ($menusize as $mzrow):?>
                          <?php $sel = ($smrow['size_id'] == $mzrow['id']) ? ' selected="selected"' : '' ;?>
                          <option value="<?php echo $mzrow['id'];?>"<?php echo $sel; ?>><?php echo $mzrow['size_name'];?></option>
                          <?php endforeach;?>
                        </select>
                        &nbsp;&nbsp;
                        <input name="sized_price[]" type="text" class="inputbox newinputbox"  size="55" value="<?php echo $smrow['price'];?>" id="textbox1"/>
                        <input type="hidden"  name="sizemapid[]" value="<?php echo $smrow['id']; ?>"/>
                        <a href="javascript:void(0);" class="delete" data-title="<?php echo $smrow['price'];?>" id="item_<?php echo $smrow['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a>
                        <?php endforeach; endif;?>
                        <img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/> </div>
                    </div>
                    <img src="images/delete.png" id='removeButton' style="cursor:pointer; margin-top:-55px; margin-left:715px;"  class="tooltip"  alt="" title="Remove new field"/> </td>
                </tr>
              </table></td>
          </tr>
        </tbody>
      </table>
      <input name="menuid" type="hidden" value="<?php echo $menu->menuid;?>" />
    </form>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MENUITEM_, "deletesizeIteamMapping");?>
<script>
// onclick generate new texbox  start 
$(document).ready(function(){
	var increment=2;
  $("#addButton").click(function(){
		// validation texbox more then 10 field add
	  if(increment > 10)
	  {
		//alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }
	  // two methods
	  // first memthod...we can write div element
		var maincontent="<select name='sized_size_id[]"+increment+"' class='custombox inputbox selectboxnew' style='width:300px'><option value=''>Select</option><?php foreach ($menusize as $mzrow):?><option value='<?php echo $mzrow['id'];?>'><?php echo $mzrow['size_name'];?></option><?php endforeach;?></select><input type ='textbox' name='sized_price[]"+increment+"'  class='inputbox newinputbox TextBoxDiv textbox'  size='55' id='textbox"+increment+"'/>";		
		var fun="<div id='TextBoxDiv"+increment+"'>"+maincontent+"</div>";
		//$("#TextBoxesGroup").append(fun);
    
	//second method 
	// here jquery through create div element
	
	  var newTextBoxDiv = $(document.createElement('div')).attr('id', 'TextBoxDiv' +increment);
	  newTextBoxDiv.after('').html(maincontent);	  
	 $(newTextBoxDiv).appendTo("#TextBoxesGroup");
	
	increment++;
  });
  
  // remove texbox onclick 
  $("#removeButton").click(function () {  
		    if(increment==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        increment--;
			
	        $("#TextBoxDiv" + increment).remove();
			$("#TextAreaDiv" + increment).remove();
		});
		
});
</script>
<script type="text/javascript">
<?php if($row['item_type']=='1') { ?>$('.sized').show();$('.regular').hide(); <?php } else { ?>$('.regular').show(); $('.sized').hide();<?php } ?>
</script>
<?php echo $core->doFormlatest("procesmenuitemmaster","controller.php","menu_item_master");?>
<?php break;?>
<?php case"xml": ?>
<div class="block-top-header">
  <h1><img src="images/users-sml.png" alt="" /><?php echo _XML_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MITEM_INFO2. _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _XML_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _XML_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_item_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _XML_AVATAR;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="menuitem_xml" />
              </div></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("procesMenuItemXml","xml_menu_import.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MITEM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MITEM_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MITEM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MITEM_ADD1;?>" name="dosubmit" />
                <span></span></div></td>
            <td><?php /*?><div class="button arrow">
                <input type="submit" value="<?php echo _MITEM_ADD;?>" name="dosubmit" /> 
                <span></span></div><?php */?>
              <a href="index.php?do=menu_item_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MITEM_TYPE;?>:<?php echo required();?></th>
            <td><select name="item_type" class="custombox" style="width:300px" id="hidepricesize">
                <option value="0" selected="selected">Regular</option>
                <option value="1" >Sized</option>
              </select>
            </td>
          </tr>
          <tr>
            <th><?php echo _MCAT;?>:<?php echo required();?></th>
            <td><select name="category_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $menucategoryrow = $content->getmenucategoryList();?>
                <option value="0">Select Category Name</option>
                <?php foreach ($menucategoryrow as $prow):?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['category_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo MTYPE;?>:<?php echo required();?></th>
            <td><select name="menu_type" class="custombox" style="width:300px">
                <option value="MenuItem">Menu Item</option>
                <option value="calculate">Pizza Item</option>
                <option value="topping">Pizza Topping</option>
                <option value="crust">Pizza Crust</option>
                <option value="sauce">Pizza Sauce</option>
                <option value="prep_inst">Prep Instruction</option>
                <option value="cheese">Cheese</option>
                <option value="modifier">Modifier</option>
              </select></td>
          </tr>
          <tr>
            <th>Special Menu Icon:</th>
            <td>
            	<select name="special_menu_icon[]" id="special_menu_icon" multiple="multiple" class="" style="width:300px">
                <?php $menu_row = $menu->getSpecialMenuListIds(); ?>
                <option value="">Select Special Menu Icon</option>
                <?php foreach ($menu_row as $crow):?>                
                <option value="<?php echo $crow['special_id'];?>"><?php echo $crow['special_item_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_NAME;?>: </th>
            <td><input name="item_name" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_SIN;?>: <?php echo required();?> </th>
            <td><input name="short_name" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo MITEM_TID;?>: </th>
            <td><input name="ticket_item_id" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_DESC;?>:</th>
            <td><textarea name="item_description" cols="53" rows="6"></textarea></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_PRE;?>: </th>
            <td><input name="preparation_time" type="text" class="inputbox"  size="55" title=""/>(min)</td>
          </tr>
          <tr>
            <th> <?php echo MAVL;?>:</th>
            <td><table id="">
                <tbody>
                  <tr>
                    <td>
                    <input type="checkbox" class="checkbox-all" value="Mon"  name="available_days[]"  id="chk1">
                      <label for="">Mon</label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Tue"  name="available_days[]" id="chk2">
                      <label for=""> Tue </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Wed" name="available_days[]" id="chk3">
                      <label for=""> Wed </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Thu"  name="available_days[]" id="chk4">
                      <label for=""> Thu </label></td>
                    <td><input type="checkbox" class="checkbox-all"  value="Fri"  name="available_days[]" id="chk5">
                      <label for=""> Fri </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Sat"  name="available_days[]" id="chk6">
                      <label for=""> Sat </label></td>
                    <td><input type="checkbox" class="checkbox-all" value="Sun"  name="available_days[]" id="chk7">
                      <label for=""> Sun </label></td>
                    <td><input type="checkbox" class="checkbox-all" id="selectall" onClick="Mastercheckbox(this)" />
                      <label for="">Select All</label></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr>
            <th> Show Item IN Menu </th>
            <td><select class="custombox" style="width:300px" name="show_item_in_menu">
                <option value="1">Yes</option>
                <option value="0" selected="selected">No</option>
              </select></td>
          </tr>
          <tr>
            <th> Show Item In Option </th>
            <td><select name="show_item_in_option" class="custombox" style="width:300px">
                <option value="1">Yes</option>
                <option value="0" selected="selected">No</option>
              </select></td>
          </tr>
          <tr class="regular">
            <th><?php echo _MITEM_SIIM;?>: </th>
            <td><select name="size_id" class="custombox" style="width:300px">
                <?php $menusize = $menu->getMeniSizelist(); ?>
                <option value="">Select</option>
                <?php foreach ($menusize as $mzrow):?>
                <option value="<?php echo $mzrow['id'];?>"><?php echo $mzrow['size_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr class="regular">
            <th><?php echo _MITEM_PRICE;?>: <?php echo required();?> </th>
            <td><input name="price" type="text" class="inputbox"  size="55" title="" value="0.00"/></td>
          </tr>
          <tr>
            <th> Show Nutrition </th>
            <td><select name="show_nutrition_info" class="custombox" style="width:300px">
                <option value="1">Yes</option>
                <option value="0" selected="selected">No</option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo MITEM_NUTRI;?>:</th>
            <td><textarea name="nutrition_ingredient" cols="53" rows="6"></textarea></td>
          </tr>
          <tr>
            <th> Min. Qty</th>
            <td><input type="text" class="inputbox" maxlength="3"  name="min_qty" value="0">
              &nbsp; &nbsp;<span class="adminformT"> Max. Qty<span> &nbsp; &nbsp;
              <input type="text" class="inputbox" maxlength="4"  name="max_qty" value="20">
              </span></span></td>
          </tr>
          <tr>
            <th>Order Type</th>
            <td><table>
                <tr>
                  <td><input type="checkbox" class="checkbox-all" value="1" name="pick_up" id="order1" onClick="OrderType_pickup">
                    <label for="">Pick Up</label></td>
                  <td><input type="checkbox" class="checkbox-all"  value="1"  name="delivery" id="order2" onClick="OrderType_delivery">
                    <label for="">Delivery</label></td>
                  <td><input type="checkbox" class="checkbox-all"  value="1"  name="dineln" id="order3" onClick="OrderType_dinein">
                    <label for="">Dine In</label></td>
                  <td><input type="checkbox" class="checkbox-all" id="selectall_order" onClick="Mastercheckboxnew()" >
                    <label for="">Select All</label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td width="180" valign="middle" height="30" align="left" class="adminform_text">Order Availability Time </td>
            <td width="300" valign="middle" height="30" align="left" colspan="2" class="adminformT"><table id="">
                <tr>
                  <td><input type="checkbox" class="checkbox-all" value="1" id="food1"   name="breakfast" onClick="selectfood1()" >
                    <label for="">Breakfast</label></td>
                  <td><input type="checkbox" class="checkbox-all"  value="1" id="food2" name="lunch"  onClick="selectfood2()">
                    <label for="">Lunch</label></td>
                  <td><input type="checkbox"  class="checkbox-all" value="1"  id="food3"  name="dinner" onClick="selectfood3()">
                    <label for="">Dinner</label></td>
                  <td><input type="checkbox" class="checkbox-all" id="selectall_food" onClick="Mastercheckboxfood()" >
                    <label for="">Select All</label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <th> Show Image In Menu </th>
            <td><select name="show_image_in_menu" class="custombox" style="width:300px">
                <option value="1">Yes</option>
                <option value="0" selected="selected">No</option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_BII;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="big_item_image" />
              </div></td>
          </tr>
          <tr>
            <th><?php echo _MITEM_TII;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="thumb_item_image" />
              </div></td>
          </tr>
          <tr>
            <th> Out Of Stock </th>
            <td><select  name="out_of_stack" class="custombox" style="width:300px">
                <option value="1">Yes</option>
                <option value="0" selected="selected">No</option>
              </select></td>
          </tr>
          <tr>
            <th> Display Order: </th>
            <td><input type="text" class="inputbox"  maxlength="3" value="1000" name="display_order"></td>
          </tr>
          <tr>
            <th>Publish:<?php echo required();?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr class="sized" style="display:none;">
            <td colspan="2"><table>
                <thead>
                  <tr>
                    <td>Size</td>
                    <td>Price</td>
                  </tr>
                </thead>
                <tr>
                  <td colspan="2"><div id='TextBoxesGroup'>
                      <div id="TextBoxDiv1">
                        <select name="sized_size_id[]" class="custombox" style="width:300px" >
                          <?php $menusize = $menu->getMeniSizelist(); ?>
                          <option value="">Select</option>
                          <?php foreach ($menusize as $mzrow):?>
                          <option value="<?php echo $mzrow['id'];?>"><?php echo $mzrow['size_name'];?></option>
                          <?php endforeach;?>
                        </select>
                        &nbsp;&nbsp;
                        <input name="sized_price[]" type="text" class="inputbox newinputbox"  size="55" title="" id="textbox1"/>
                        <select name="sized_size_id[]" class="custombox" style="width:300px" >
                          <?php $menusize = $menu->getMeniSizelist(); ?>
                          <option value="">Select</option>
                          <?php foreach ($menusize as $mzrow):?>
                          <option value="<?php echo $mzrow['id'];?>"><?php echo $mzrow['size_name'];?></option>
                          <?php endforeach;?>
                        </select>
                        &nbsp;&nbsp;
                        <input name="sized_price[]" type="text" class="inputbox newinputbox"  size="55" title="" id="textbox1"/>  
                        <img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/> </div>
                    </div>
                    <img src="images/delete.png" id='removeButton' style="cursor:pointer; margin-top:-55px; margin-left:700px;"  class="tooltip"  alt="" title="Remove new field"/> </td>
                </tr>
                <?php /*?> <tr> 
              <td><select name="sized_size_id[]" class="custombox" style="width:300px">
                            <?php $menusize = $menu->getMeniSizelist(); ?>
                            <option value="">Select Your Size</option>
                            <?php foreach ($menusize as $mzrow):?>
                            <option value="<?php echo $mzrow['id'];?>"><?php echo $mzrow['size_name'];?></option>
                            <?php endforeach;?>
                   </select>
                </td>  
                <td><input name="sized_price[]" type="text" class="inputbox"  size="55" title=""/></td>
            </tr>
            <tr> 
              <td><select name="sized_size_id[]" class="custombox" style="width:300px">
                            <?php $menusize = $menu->getMeniSizelist(); ?>
                            <option value="">Select Your Size</option>
                            <?php foreach ($menusize as $mzrow):?>
                            <option value="<?php echo $mzrow['id'];?>"><?php echo $mzrow['size_name'];?></option>
                            <?php endforeach;?>
                   </select>
                </td>  
                <td><input name="sized_price[]" type="text" class="inputbox"  size="55" title=""/></td>
            </tr><?php */?>
              </table></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
// onclick generate new texbox  start 
$(document).ready(function(){
	var increment=2;
  $("#addButton").click(function(){
		// validation texbox more then 10 field add
	  if(increment > 10)
	  {
		//alert("Can't Add More Field");
		 $( "#dialog" ).dialog();
		return false;
	  }
	  // two methods
	  // first memthod...we can write div element
		var maincontent="<select name='sized_size_id[]"+increment+"' class='custombox inputbox selectboxnew' style='width:300px'><option value=''>Select</option><?php foreach ($menusize as $mzrow):?><option value='<?php echo $mzrow['id'];?>'><?php echo $mzrow['size_name'];?></option><?php endforeach;?></select><input type ='textbox' name='sized_price[]"+increment+"'  class='inputbox newinputbox TextBoxDiv textbox'  size='55' id='textbox"+increment+"'/>";		
		var fun="<div id='TextBoxDiv"+increment+"'>"+maincontent+"</div>";
		//$("#TextBoxesGroup").append(fun);
    
	//second method 
	// here jquery through create div element
	
	  var newTextBoxDiv = $(document.createElement('div')).attr('id', 'TextBoxDiv' +increment);
	  newTextBoxDiv.after('').html(maincontent);	  
	 $(newTextBoxDiv).appendTo("#TextBoxesGroup");
	
	increment++;
  });
  
  // remove texbox onclick 
  $("#removeButton").click(function () {  
		    if(increment==2){
		        alert("No more textbox to remove");
		        return false;
		    }   
	        increment--;
			
	        $("#TextBoxDiv" + increment).remove();
			$("#TextAreaDiv" + increment).remove();
		});
		
});
</script>
<?php echo $core->doFormlatest("procesmenuitemmaster","controller.php","menu_item_master");?>
<?php break;?>
<?php case"addoption": ?>
<?php
  /* ProccesMenu Item Option Mapping */

  if (isset($_POST['dosubmit']) && $_POST['dosubmit']=='Add and Save Selected Option' ):
  $menu->procesMenuItemOptionMapping();
  endif;
  if (isset($_POST['dosubmit']) && $_POST['dosubmit']=='Add Size Price'):
  $menu->procesMenuSizePriceitem();
  endif;
?>
<?php  
	$orrow = $menu->getaddoptionmenu($menu->menuid);
?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo ITEM_OPTIONTOITEM;?></h1>
  <div class="divider"><span></span></div>
</div>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo $orrow['menu_name']. " &rsaquo; ".$orrow['category_name']. " &rsaquo; ".$orrow['item_name'];?></h2>
  </div>
  <div class="block-content">
    <form  method="post" name="admin_form" action="" id="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo ITEM_IOPTIONADD;?>" name="dosubmit" class="comment_button" />                
                <span></span></div>
                <td><div class="button arrow">
                <input type="submit" value="Add Size Price" name="dosubmit" class="comment_button" />
                <span></span></div></td>
                </td>
            <td><a href="index.php?do=menu_item_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo ITEM_SEOPTION;?>:<?php echo required();?></th>
            <td>
            	<select name="item_option_id" class="select" style="width:400px" size="10" id="item_option_id">
               		 <?php $optionlist = $menu->getMenuOptionDropDown($menu->menuid);?>
                    <option value="">Select Option Name</option>
                    <?php if($optionlist):
                          foreach ($optionlist as $orow):
                    ?>
                    <option value="<?php echo $orow['option_id'];?>"><?php echo $orow['option_name'];?></option>
                    <?php endforeach; endif;?>
              </select>
              <input type="hidden" name="menu_item_id" value="<?php echo $menu->menuid;?>" />
            </td>
          </tr>
          <?php 		  
		  $itemoption = $menu->getMenuOptionListAtItem($menu->menuid); 
		  
		  if($itemoption):
		  
		  $i=1;
		  
		  foreach($itemoption as $optionrow):
		  
		 
		  ?>
          <tr>
            <td colspan="2"><table class="forms">
                <tbody>
                  <tr style="background-color:skyblue;">
                    <td><strong><?php echo $optionrow['option_name']; ?></strong> </td>
                    <td>
                    <span id="dispalyorder_<?php echo $optionrow['menu_option_map_id'];?>"></span>
                    <strong>Display Order:-</strong>&nbsp; &nbsp; &nbsp; 
                    <input type="text" name="display_order" value="<?php echo isset($optionrow['display_order']) ? $optionrow['display_order']  :'';?>" id="<?php echo $optionrow['menu_option_map_id'];?>"  onblur="updateDisplayOrder(this.id,this.value)"/></td>
                    <td style="cursor:pointer;">
                    	<a href="index.php?do=menu_option_master&amp;action=edit&amp;menuid=<?php echo $optionrow['option_id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MIO_EDIT;?>"/></a>
                    </td>
                    <td>
                    	<a href="javascript:void(0);" class="delete" data-title="<?php echo $optionrow['option_name'];?>" id="item_<?php echo $optionrow['menu_option_map_id'];?>">
                        	<img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/>
                        </a>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">
                    	<strong>Instruction:</strong><?php echo $optionrow['instruction'];?><?php $optionrow['menu_option_map_id']; ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4">
                        Min Choice: <?php echo $optionrow['min_choise'];?> <br/>
                        Max Choice: <?php echo $optionrow['max_choise'];?> 
                    </td>
                  </tr>
                  <?php 
				  
				  	if($optionrow['size_affect_price']==1):
					
				  	$sizedprice = 	$menu->getMenuSizePriceitem($menu->menuid);
					
					if(empty($sizedprice)): 
					?>
					<tr>
                    <td colspan="4"><strong>This Item not Sized check  this item <?php echo $orrow['item_name'];?></strong></td>
                  </tr> 
					<?php else:
				  ?>
                    <tr>
                    <td colspan="4"><strong>What is difference for each choice ?</strong></td>
                  </tr>   
                    <tr>
                    <td colspan="4">
                    <table width="100%" border="1">
                      <tr>
					   <?php
					  	
					   foreach($sizedprice as $sizrow): 
					   					   
					   
					   	$diff_price = $menu->getoptionpricediffDisplay($optionrow['menu_option_map_id'],$sizrow['sizeid']);
						
						if(isset($diff_price['price_diff']) && !empty($diff_price['price_diff'])){
							$different_price = $diff_price['price_diff'];
						}else{
							$different_price = "0.00";
						}
											   
					   ?>
                        <td><?php echo $sizrow['size_name']; ?> <br />
                        <input type="text" name="sizedprice[<?php echo $i;?>][price][]" value="<?php echo $different_price; ?>" />
                        <input type="hidden" name="sizedprice[<?php echo $i;?>][sizeid][]" value="<?php echo $sizrow['sizeid'];?>" />
                        <input type="hidden" name="sizedprice[<?php echo $i;?>][meniitemmapid]" value="<?php echo $optionrow['menu_option_map_id'];?>" />
                        </td>
                        <?php endforeach;?>
                      </tr>
                        
                    </table>                    
                    </td>
                  </tr>    
                   <?php  endif; endif;?>                       
                  <tr>
                    <td colspan="1">                    
                      <table class="forms">
                        <thead>
                          <tr>
                            <th>Option Name</th>
                            <th>Out of Stock</th>
                          </tr>                          
                        </thead>
                        <tbody>
							  <?php $toppingrow = $menu->getMenuOptionToppingList($optionrow['item_option_id']); 
                              if($toppingrow):
                              foreach($toppingrow as $trow):
                              ?>   
                          <tr>
                            <td><?php echo $trow['topping_name'];?></td>
                            <td><input type="checkbox" disabled="disabled" value="1" name="out_of_stock" <?php getChecked($trow['out_of_stock'], 1); ?> /></td>
                          </tr>
                 			 <?php endforeach; endif; ?> 
                        </tbody>
                      </table></td>
                    <td colspan="3"></td>
                  </tr>   
                </tbody>
              </table></td>
          </tr>  
          <?php  $i++; endforeach; endif; ?>        
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
	function updateDisplayOrder(menu_option_map_id,value)
	{
		var value;
		if (isNaN(value)) {
                alert('Only Numeric Values Allowed');
           }
		else
		{
		$("#dispalyorder_"+menu_option_map_id).html('<img src="images/loading.gif" />');
		 $.ajax({
			type: "POST",
			url: "ajax.php",
			data: "updateDisplayOrder=1&menu_option_map_id="+menu_option_map_id+"&display_order="+value,
			success: function(theResponse) {
				$("#dispalyorder_"+menu_option_map_id).html("");
				
			}  
			});  
	    }
	}

</script>
<?php echo Core::doDelete(_DELETE.' '._MENUITEM_, "deletemenuOptionItemMapping");?>
<?php break;?>
<?php default:

	 if(isset($_GET['active']) && !empty($_GET['active'])):
		 $data['active'] = "1";
		 $db->update("res_menu_item_master", $data, "id='" . $_GET['active']  . "'");	
	endif;
	
	if(isset($_GET['deactive']) && !empty($_GET['deactive'])):
	   $data['active'] = "0";
	   $db->update("res_menu_item_master", $data, "id='" . $_GET['deactive']  . "'");	
	endif;

	$menucategoryrow = $menu->getmenucategoryBYItem($userlocationid);
?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MITEM_TITLE3;?></h1>
  <div id="success"></div>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MITEMINFO3;?></p>
<div class="block-border">



  <div class="block-header">
  <h2><span><a href="index.php?do=menu_item_master&amp;action=xml" class="button-sml">Menu Item Xml Import</a></span>
    <span><a href="index.php?do=menu_item_master&amp;action=add" class="button-sml"><?php echo _MITEM_ADD;?></a></span><?php echo _MITEM_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display" border="1">
        <tr>
            <td>
                <form action="" method="post" id="dForm2">
                    <input type="text" class="inputbox" id="searchfield" size="30" name="usersearchfield" placeholder="Provide Item Name" />
                    <input name="search_keyword" type="submit" class="button-blue" value="Search" />
                </form>
           </td>            
        </tr>
        <tr>            
            <form action="" method="post" id="dForm">
            <td>
            	 <select name="location_id" id="ddlViewBy" class="custombox" style="width:220px">
				 	<?php $locationrow = $content->getlocationlist($userlocationid);?>
                    <option value="">Select Location Name</option>
                    <?php 
						foreach ($locationrow as $prow):
							if(isset($_REQUEST['category_id'])){ $sortslect2 = $_REQUEST['location_id'];  }
							else  { $sortslect2 =''; }
					?>
                    <option value="<?php echo $prow['id'];?>" <?php if($sortslect2 == $prow['id']){ echo "selected"; }?>><?php echo $prow['location_name'];?></option>
                    <?php endforeach;?>
             	</select>
            </td>
            <td>
            	<select name="category_id" id="ddlViewBy" class="custombox" style="width:220px">
                	<?php  $menucategory = $content->getmenucategoryList($userlocationid); ?>
               		<option value="">Select Category Name</option>
					<?php 
                        foreach ($menucategory as $catrow):
                         if(isset($_REQUEST['category_id'])){ $sortslect = $_REQUEST['category_id'];  }
                         else  { $sortslect=''; }
                    ?>
               	 	<option value="<?php echo $catrow['id'];?>"<?php if($sortslect == $catrow['id']){ echo "selected"; }?>><?php echo $catrow['category_name'];?></option>
                	<?php endforeach;?>
                </select>
            </td>
            <td>
            	<select name="active" style="width:220px" class="custombox">
                	<option value="">--- Select Menu Status ---</option>
					<?php 
                    if(isset($_REQUEST['active'])){ $sortslect = $_REQUEST['active'];  }
                      else  {  $sortslect='';   }
					?>
                    <option value="1"<?php if($sortslect == '1'){ echo "selected"; }?>>Published</option>
                    <option value="0"<?php if($sortslect == '0'){ echo "selected"; }?>>UnPublished</option>
             	 </select>
            </td>
            <td><input name="advance_search" type="submit" class="button-blue" value="Search<?php //echo _RS_FIND;?>" /></td>
          </form>
        </tr>
        <tr>
          <td><img src="images/yes.png" class="tooltip" alt="" title="<?php echo _PUBLISHED;?>"/> <?php echo _PUBLISHED;?> <img src="images/no.png" class="tooltip" alt="" title="<?php echo _NOTPUBLISHED;?>"/> <?php echo _NOTPUBLISHED;?> </td>
          <td class="right" colspan="3"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th>&nbsp;</th>
          <th class="left sortable"><?php echo _MITEM_NAME;?></th>
          <th class="left sortable"><?php echo  _MITEM_DESC;?></th>
          <th>Display Order</th>
           <th>Ticket item id</th>
          <th>Options</th>
          <th class="left sortable"><?php echo _MITEM_PRICE;?></th>
          <th class="center sortable"><?php echo MITEM_PUB;?></th>
          <th><?php echo ITEM_OPTIONADD; ?></th>
          <th><?php echo _MITEM_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="11"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$menucategoryrow):?>
        <tr>
          <td colspan="11"><?php echo $core->msgAlert(_MI_NOMENUITEM,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($menucategoryrow as $crow):?>
        <tr id="<?php echo $crow['id']; ?>" class="parent">
          <th><span class="btn"><img src="images/icons/arrow-menu.png" /></span>
            <?php //echo $crow['id'];?></th>
          <td colspan="11"><strong>Category Name: </strong><?php echo $crow['category_name'];?> </td>
        </tr>
        <?php $menuitemrow = $menu->getMenuItemByCat($crow['id']);        
		if($menuitemrow):		
		foreach ($menuitemrow as $row):?>
        <tr id="<?php echo $row['id']; ?>" class="edit_tr child-<?php echo $crow['id']; ?>">
          <th ></th>
          <th ></th>
          <td class="edit_td"><span id="item_<?php echo $row['id']; ?>" class="text"><?php echo $row['item_name'];?></span>
            <input type="text" value="<?php echo $row['item_name'];?>" class="editbox inputbox" id="item_input_<?php echo $row['id']; ?>" style="display:none;"/>
          </td>
          <td class="edit_td"><span id="item_desc_<?php echo $row['id']; ?>" class="text"><?php echo cleanSanitize(substr($row['item_description'], 0, 15))."..";?></span>
            <textarea name="item_description" cols="53" rows="6" class="editbox"  id="item_desc_input_<?php echo $row['id']; ?>" style="display:none;"><?php echo $row['item_description'];?></textarea>
          </td>
          <td  class="edit_td"><span id="order_<?php echo $row['id']; ?>" class="text"><?php echo $row['display_order'];?></span>
            <input type="text" value="<?php echo $row['display_order'];?>"  class="editbox inputbox" id="order_input_<?php echo  $row['id']; ?>" style="display:none;"/>
          </td>
          <td><?php echo  $row['ticket_item_id'];?></td>
          <td><?php echo $menu->optionListItem($row['id']);?></td>
          <td><?php echo $row['price'] ;?></td>
          <td class="center"><?php if($row['active']=='1'){ ?>
            <a href="index.php?do=menu_item_master&amp;deactive=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>"><img src="images/no.png" title="click here to publish"  class="tooltip"/></a>
            <?php } else {?>
            <a href="index.php?do=menu_item_master&amp;active=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>"><img src="images/yes.png" title="click here to unpublish"  class="tooltip" /></a>
            <?php } ?>
          </td>
          <td class="center"><a href="index.php?do=menu_item_master&amp;action=addoption&amp;menuid=<?php echo $row['id'];?>"><img src="images/add.png" class="tooltip"  alt="" title="<?php echo ITEM_OPTIONADD;?>"/></a></td>
          <td class="center"><a href="index.php?do=menu_item_master&amp;action=edit&amp;menuid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MITEM_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['item_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach; endif?>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MENUITEM_, "deleteMenuItem");?>
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

$(".edit_tr").click(function()
{
var ID=$(this).attr('id');
$("#item_"+ID).hide();
$("#item_desc_"+ID).hide();
$("#order_"+ID).hide();
$("#item_input_"+ID).show();
$("#item_desc_input_"+ID).show();
$("#order_input_"+ID).show();
}).change(function()
{
var ID=$(this).attr('id');
var item=$("#item_input_"+ID).val();
var itemdesc=$("#item_desc_input_"+ID).val();
var order=$("#order_input_"+ID).val();
var dataString = 'processitemquick=1&id='+ ID +'&itemname='+encodeURIComponent(item)+'&itemdesc='+encodeURIComponent(itemdesc)+'&orderdispaly='+order;
$("#item_"+ID).html('<img src="images/loading.gif" />');


if(item.length && itemdesc.length && order.length>0)
{
	
	$.ajax({
		type: "POST",
		url: "ajax.php",	
		data: dataString,
		cache: false,
		success: function(html)
		{
		
			$("#item_"+ID).html(item);
			$("#item_desc_"+ID).html(itemdesc);
			$("#order_"+ID).html(order);
			$('#success').html(html).show();
		}
	});
}
else
{
alert('Enter something.');
}

});

$(".editbox").mouseup(function() 
{
return false
});

$(document).mouseup(function()
{
$(".editbox").hide();
$(".text").show();
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
<script type="text/javascript">
 function Mastercheckbox(source) {
		checkboxes = document.getElementsByName('available_days[]');
		for(var i in checkboxes)
			checkboxes[i].checked = source.checked;
	}

function Mastercheckboxnew()
{ 
if(document.getElementById('selectall_order').checked==true)
{
document.admin_form.order1.checked=true
document.admin_form.order2.checked=true
document.admin_form.order3.checked=true
}
if(document.getElementById('selectall_order').checked==false)
{
document.admin_form.order1.checked=false
document.admin_form.order2.checked=false
document.admin_form.order3.checked=false
}
}
function OrderType_pickup()
{
if(document.getElementById('order1').checked==false)
{
document.admin_form.order1.checked=false
} 
}
function OrderType_delivery()
{
if(document.getElementById('order2').checked==false)
{
document.admin_form.order2.checked=false
} 
}
function OrderType_dinein()
{
if(document.getElementById('order3').checked==false)
{
document.admin_form.order3.checked=false
} 
}

/* breakfast lunch dinner*/
function Mastercheckboxfood()
{ 
if(document.getElementById('selectall_food').checked==true)
{
document.admin_form.food1.checked=true
document.admin_form.food2.checked=true
document.admin_form.food3.checked=true
}
if(document.getElementById('selectall_food').checked==false)
{
document.admin_form.food1.checked=false
document.admin_form.food2.checked=false
document.admin_form.food3.checked=false
}
}
function selectfood1()
{
if(document.getElementById('food1').checked==false)
{
document.admin_form.food1.checked=false
} 
}
function selectfood2()
{
if(document.getElementById('food2').checked==false)
{
document.admin_form.food2.checked=false
} 
}
function selectfood3()
{
if(document.getElementById('food3').checked==false)
{
document.admin_form.food3.checked=false
} 
}
</script>
<script>
 $('#hidepricesize').change(function(){
 	var regular= $('#hidepricesize').val();

        if (regular == '0') {
			$('.regular').show();
			$('.sized').hide();
			}
		else if (regular == '1') {
		$('.sized').show();
		$('.regular').hide();
		}
 });
$(function() {
    $('tr.parent th')
        .on("click","span.btn", function(){
            var idOfParent = $(this).parents('tr').attr('id');
            $('tr.child-'+idOfParent).toggle();
        });
})
</script>
