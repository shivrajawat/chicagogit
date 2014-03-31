<?php
  /**
   * Menu Size Manager
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Menu size Master")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_menu_size_master", $menu->menuid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MSIZE_TITLE11;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MSIZE_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MSIZE_SUBTITLE1 . $row['size_name'];?></h2>
  </div>
  <div class="block-content">
   <span id="avatar">
    <?php if ($row['size_image']):?>
    <img src="<?php echo UPLOADURL;?>/size_image/<?php echo $row['size_image'];?>" alt="<?php echo $row['size_name'];?>" class="avatar"/>
    <?php else:?>
    <img src="<?php echo UPLOADURL;?>/avatars/blank.png" alt="<?php echo $row['size_name'];?>" class="avatar"/>
    <?php endif;?>
    </span>
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MSIZE_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_size_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
        <tr>
            <th><?php echo _MSIZE;?>: <?php echo required();?></th>
            <td><input name="size_name" type="text" class="inputbox"  size="55" value="<?php echo $row['size_name'];?>" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _MSTICKET;?>: <?php echo required();?></th>
            <td><input name="ticket_size_id" type="text" class="inputbox"  size="55" value="<?php echo $row['ticket_size_id'];?>" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
          <tr>
            <th>Menu Size Image:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="size_image" />
              </div></td>
          </tr>
          <tr><th>Size Description:</th>
            <td colspan="1" class="editor"><textarea id="description" name="description" rows="4" cols="30"> <?php echo $row['description'];?></textarea>
              <?php loadEditor("description"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MSACTIVE;?>:</th>
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
<?php echo $core->doFormlatest("processsizemaster","controller.php","menu_size_master");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MSIZE_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MSIZE_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MSIZE_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MSIZE_ADDCLOSE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_category_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MSIZE;?>: <?php echo required();?></th>
            <td><input name="size_name" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _MSTICKET;?>: <?php echo required();?></th>
            <td><input name="ticket_size_id" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th>Menu Size Image:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="size_image" />
              </div></td>
          </tr>
           <tr><th>Size Description:</th>
            <td colspan="1" class="editor"><textarea id="description" name="description" rows="4" cols="30"> </textarea>
              <?php loadEditor("description"); ?></td>
          </tr>
          <tr>
            <th><?php echo _MSACTIVE;?>:</th>
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
<?php echo $core->doFormlatest("processsizemaster","controller.php","menu_size_master");?>
<?php break;?>
<?php default: ?>
<?php if(isset($_GET['active']) && !empty($_GET['active'])):
	$data['active'] = "1";
	 $db->update("res_menu_size_master", $data, "id='" . $_GET['active']  . "'");	
endif;
if(isset($_GET['deactive']) && !empty($_GET['deactive'])):
	$data['active'] = "0";
	 $db->update("res_menu_size_master", $data, "id='" . $_GET['deactive']  . "'");	
endif;
?>
<?php $menusizerow = $menu->getmenusize();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MSIZE_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MSIZEINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=menu_size_master&amp;action=add" class="button-sml"><?php echo _MSIZE_ADD;?></a></span><?php echo _MSIZE_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _MSIZE;?></th>
          <th class="left sortable"><?php echo _MSTICKET;?></th>
          <th><?php echo _MSACTIVE;?></th>
          <th><?php echo _MSIZE_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="6"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$menusizerow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_MSIZE_NOSIZE,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($menusizerow as $row):?>
        <tr>
          <tr id="<?php echo $row['id']; ?>" class="edit_tr">
          <th><?php echo $row['id'];?>.</th>         
          <td class="edit_td"><span id="first_<?php echo $row['id']; ?>" class="text"><?php echo $row['size_name'];?></span>
            <input type="text" value="<?php echo $row['size_name'];?>" class="editbox inputbox" id="first_input_<?php echo $row['id']; ?>" style="display:none;"/>
          </td>
          <td  class="edit_td"><span id="order_<?php echo $row['id']; ?>" class="text"><?php echo $row['ticket_size_id'];?></span>
            <input type="text" value="<?php echo $row['ticket_size_id'];?>"  class="editbox inputbox" id="order_input_<?php echo  $row['id']; ?>" style="display:none;"/>
          </td>
            <td class="center"><?php echo isActive($row['active']);?> <a href="index.php?do=menu_size_master&amp;active=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Published</a> | <a href="index.php?do=menu_size_master&amp;deactive=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Unpublished</a> </td>
          <td class="center"><a href="index.php?do=menu_size_master&amp;action=edit&amp;menuid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MSIZE_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['size_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MSIZE, "deletemenusize");?>
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
var size=$("#first_input_"+ID).val();
var order=$("#order_input_"+ID).val();
var dataString = 'menusizequick=1&id='+ ID +'&sizename='+size+'&ticketid='+order;
$("#first_"+ID).html('<img src="images/loading.gif" />');


if(size.length && order.length>0)
{
$.ajax({
type: "POST",
url: "ajax.php",
data: dataString,
cache: false,
success: function(html)
{

$("#first_"+ID).html(size);
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