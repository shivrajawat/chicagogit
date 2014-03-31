<?php
  /**
   * Menu Category Manager
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("MenuCategory")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php  $userlocationid = $user->getlocationIdByData(); ?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_category_master", $menu->menuid);?>

<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MCAT_TITLE11;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MCAT_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MCAT_SUBTITLE1 . $row['category_name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
                <td><div class="button arrow">
                    <input type="submit" value="<?php echo _MCAT_SAVCL;?>" name="dosubmit" />
                    <span></span></div>
                </td>
                 <td><?php /*?><div class="button arrow">
                    <input type="submit" value="<?php echo _MCAT_SAVNEW;?>" name="dosubmit" />
                    <span></span></div><?php */?><a href="index.php?do=menu_category_master" class="button-orange"><?php echo _CANCEL;?></a>
                </td>       
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MENU_TITLE;?>:</th>
            <td><select name="menu_id" class="custombox" style="width:300px">
                <?php $menurow = $menu->getMenulistlocation();?>
                <option value="">Select Your Menu</option>
                <?php foreach ($menurow as $mrow):?>
                <?php $sel = ($row['menu_id'] == $mrow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $mrow['id'];?>"<?php echo $sel;?>><?php echo $mrow['menu_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MCAT;?>: <?php echo required();?></th>
            <td><input name="category_name" type="text" class="inputbox"  size="55" value="<?php echo $row['category_name'];?>" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th>Parent Category:</th>
            <td><select name="parent_id" class="custombox" style="width:359px">
                <option value="0">Top</option>
                <?php $content->getCategoryDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row['parent_id']);?>
              </select>
              </td>
          </tr>
          <tr>
            <th>Display Order:</th>
            <td><input type="text" class="inputbox"  maxlength="3" name="display_order" value="<?php echo $row['display_order'];?>"></td>
          </tr>
          <tr>
            <th> Upload Category Image: </th>
          	<td>
            	<div class="fileuploader">
                    <input type="text" class="filename" readonly="readonly"/>
                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                    <input type="file" name="photo_image" />
              	</div>
            </td>
          </tr>
          <tr><th>Category Description:</th>
            <td colspan="1" class="editor"><textarea id="category_dec" name="category_dec" rows="4" cols="30"> <?php echo $row['category_dec'];?></textarea>
              <?php loadEditor("category_dec"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MENU_CAT;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
        </tbody>
      </table>
      <input name="menuid" type="hidden" value="<?php echo $menu->menuid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doFormlatest("processmenucategory","controller.php","menu_category_master");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MCAT_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MCAT_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MCAT_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MCAT_SAVCL;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_category_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MENU_TITLE;?>:</th>
            <td><select name="menu_id" class="custombox" style="width:300px">
                <?php $menurow = $menu->getMenulistlocation();?>
                <option value="">Select Your Menu</option>
                <?php foreach ($menurow as $mrow):?>
                <?php $sel = ($row['id'] == $mrow['id']) ? 'selected="selected"' : '' ;?>
                <option value="<?php echo $mrow['id'];?>"<?php echo $sel;?>><?php echo $mrow['menu_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MCAT;?>: <?php echo required();?></th>
            <td><input name="category_name" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th>Parent Category:</th>
            <td><select name="parent_id" class="custombox" style="width:359px">
                <option value="0">Top</option>
                <?php $content->getCategoryDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row['parent_id']);?>
              </select>
              </td>
          </tr>
          <tr>
            <th> Display Order: </th>
            <td><input type="text" class="inputbox"  maxlength="3" value="100" name="display_order"></td>
          </tr>
          <tr>
            <th> Upload Category Image: </th>
          	<td>
            	<div class="fileuploader">
                    <input type="text" class="filename" readonly="readonly"/>
                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                    <input type="file" name="photo_image" />
              	</div>
            </td>
          </tr>
          <tr><th>Category Description:</th>
            <td colspan="1" class="editor"><textarea id="category_dec" name="category_dec" rows="4" cols="30"> </textarea>
              <?php loadEditor("category_dec"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MENU_CAT;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doFormlatest("processmenucategory","controller.php","menu_category_master");?>
<?php break;?>
<?php default: ?>
<?php if(isset($_GET['active']) && !empty($_GET['active'])):
	$data['active'] = "1";
	 $db->update("res_category_master", $data, "id='" . $_GET['active']  . "'");	
endif;
if(isset($_GET['deactive']) && !empty($_GET['deactive'])):
	$data['active'] = "0";
	 $db->update("res_category_master", $data, "id='" . $_GET['deactive']  . "'");	
endif;
?>
<?php $menucategoryrow = $menu->getmenucategory($userlocationid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MCAT_TITLE3;?></h1>
  <div id="success"></div>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MCATINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=menu_category_master&amp;action=add" class="button-sml"><?php echo _MCAT_ADD;?></a></span><?php echo _MCAT_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <?php if($pager->display_pages()):?>
    <div class="utility">
      <table class="display">
        <tr>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <?php endif;?>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _MLMAP;?></th>
          <th class="left sortable"><?php echo _MCAT;?></th>
          <th class="left sortable"><?php echo _MLDIS;?></th>
          <th><?php echo _MENU_CAT;?></th>
          <th><?php echo _MCAT_EDIT;?></th>
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
        <?php if(!$menucategoryrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_MCAT_NOCATEGORY,false);?></td>
        </tr>
        <?php else:?>
        <?php $i =1;  foreach ($menucategoryrow as $row):?>
        <tr id="<?php echo $row['id']; ?>" class="edit_tr">
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['menu_name'];?></td>
          <td class="edit_td"><span id="first_<?php echo $row['id']; ?>" class="text"><?php echo $row['category_name'];?></span>
            <input type="text" value="<?php echo $row['category_name'];?>" class="editbox inputbox" id="first_input_<?php echo $row['id']; ?>" style="display:none;"/>
          </td>
          <td  class="edit_td"><span id="order_<?php echo $row['id']; ?>" class="text"><?php echo $row['display_order'];?></span>
            <input type="text" value="<?php echo $row['display_order'];?>"  class="editbox inputbox" id="order_input_<?php echo  $row['id']; ?>" style="display:none;"/>
          </td>
          <td class="center"><?php echo isActive($row['active']);?> <a href="index.php?do=menu_category_master&amp;active=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Published</a> | <a href="index.php?do=menu_category_master&amp;deactive=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Unpublished</a> </td>
          <td class="center"><a href="index.php?do=menu_category_master&amp;action=edit&amp;menuid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MCAT_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['category_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php $i++; endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MCAT_TITLE, "deletecategorymaster");?>
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
	
// Quck update data   //  item name, description , display order edit  onclick event 
$(".edit_tr").click(function()
	{
		var ID=$(this).attr('id');
		$("#first_"+ID).hide();
		$("#order_"+ID).hide();
		$("#first_input_"+ID).show();
		$("#order_input_"+ID).show();
		}).change(function()
	{
		var ID=$(this).attr('id');
		var cat=$("#first_input_"+ID).val();
		var order=$("#order_input_"+ID).val();
		var dataString = 'menucategoryquick=1&id='+ ID +'&categoryname='+encodeURIComponent(cat)+'&orderdispaly='+order;
		$("#first_"+ID).html('<img src="images/loading.gif" />');
	
	
	if(cat.length && order.length>0)
	{
		$.ajax({
		type: "POST",
		url: "ajax.php",
		data: dataString,
		cache: false,
		success: function(html)
			{	
				$("#first_"+ID).html(cat);
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

});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
