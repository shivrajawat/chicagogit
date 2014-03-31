<?php
  /**
   * Menu location Mapping Manager
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Menu Location Master")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_menu_location_mapping", $menu->menuid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MLMAP_TITLE11;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MLMAP_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MLMAP_SUBTITLE1 . $row['menu_id'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MLMAP_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_location_mapping" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
         <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">please select menu Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($row['location_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
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
        </tbody>
      </table>
      <input name="menuid" type="hidden" value="<?php echo $menu->menuid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processmenuLocation","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MLMAP_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MLMAP_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MLMAP_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MLMA_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=menu_location_mapping" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _MENU_TITLE;?>:</th>
            <td><select name="menu_id" class="custombox" style="width:300px">
                <?php $menurow = $menu->getMenulistlocation();?>
                <option value="">Select Your Menu</option>
                <?php foreach ($menurow as $mrow):?>
                <?php $sel = ($row['id'] == $mrow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $mrow['id'];?>"<?php echo $sel;?>><?php echo $mrow['menu_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
         
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processmenuLocation","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $menulocationrow = $menu->getmenulocation();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MLMAP_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MLMAPINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=menu_location_mapping&amp;action=add" class="button-sml"><?php echo _MLMAP_ADD;?></a></span><?php echo _MLMAP_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _MLMAP_TITLE;?></th>
          <th class="left sortable"><?php echo _MLMAP;?></th>
          <th><?php echo _MLMAP_EDIT;?></th>
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
        <?php if(!$menulocationrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_LOC_NOLOCMAPPING,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($menulocationrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>
          <td><?php echo $row['menu_name'] ;?></td>
          <td class="center"><a href="index.php?do=menu_location_mapping&amp;action=edit&amp;menuid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MLMAP_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['menu_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MLMAP_TITLE, "deletemenulocation");?>
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