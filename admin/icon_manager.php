<?php
  /**
   * Menu Size Manager
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Coupon Master")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowByIdNew("res_menu_icon","special_id", $menu->menuid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" />Special Menu Icon Master</h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo 'Here you can edit Menu Icon Master Fields marked' . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo 'Edit Menu Icon Master › ' . $row['special_item_name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
  <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="Update Menu Icon" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=icon_manager" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>         
         <tr>
            <th>Menu Icon Name: <?php echo required();?></th>
            <td><input name="special_item_name" type="text" class="inputbox" value="<?php echo $row['special_item_name']; ?>" size="55" /></td>
          </tr>
           <tr>
            <th>Icon Image </th>
             <td>
            	<div class="fileuploader">
                    <input type="text" class="filename" readonly="readonly"/>
                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                    <input type="file" name="avatar" />
                    <?php  if(is_file("../uploads/menu_icon/".$row['special_item_icon'])){ ?>
                    <span style="margin-left:10px;">
                    	<img src="<?php echo UPLOADURL.'/menu_icon/'.$row['special_item_icon']; ?>" width="100" hight="100"/></span>
                    <?php } ?>
              	</div>
             </td>
          </tr>                    
         
          <tr>
            <th><?php echo _CPN_ACTIVE;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="is_active" type="radio" id="active-1" value="1" <?php getChecked($row['is_active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="is_active" type="radio" id="active-2" value="0" <?php getChecked($row['is_active'], 0); ?>  />
              </span></td>
          </tr>      
         
        </tbody>
      </table>         
      <input name="menuid" type="hidden" value="<?php echo $menu->menuid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processMenuIconmaster","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" />Special Menu Icon Master</h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo 'Here you can add new Menu Icon Master Fields marked' . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2>Add New Menu Icon Manager</h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="Add New Menu Icon" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=icon_manager" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>         
          <tr>
            <th>Menu Icon Name: <?php echo required();?></th>
            <td><input name="special_item_name" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th>Icon Image : <?php echo required();?></th>
             <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="avatar" />
              </div>
             </td>
          </tr>          
          <tr>
            <th><?php echo _CPN_ACTIVE;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="is_active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="is_active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>      
         
        </tbody>
      </table>         
    </form>
  </div>
</div>
<?php echo $core->doForm("processMenuIconmaster","controller.php");?>
<?php break;?>
<?php default: ?>

<?php $Menurow = $menu->getMenuIconMaster();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" />Special Menu Icon Manager</h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span>Here you can manager Menu Special Menu Icon</p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=icon_manager&amp;action=add" class="button-sml">Add New Menu Icon</a></span>Viewinig Special Menu Icon</h2>
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
    <form action="" method="post" id="admin_form" name="admin_form">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left">Menu Icon Name</th>
          <th class="left">Menu Icon </th>
          <th>Edit</th>          
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="8"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$Menurow):?>
        <tr>
          <td colspan="8"><?php echo $core->msgAlert("You don't have any special Menu Icon Master under this page yet...",false);?></td>
        </tr>
        <?php else:?>
        <?php 
			$i=1;
			foreach ($Menurow as $row): 
		?>
        <tr>
         <th><?php echo $i;?>.</th>
          <td><?php echo $row['special_item_name'];?></td>
          <td>
          	 <?php  
			 		if(is_file("../uploads/menu_icon/".$row['special_item_icon'])){ ?>
                    <span style="margin-left:10px;">
                    	<img src="<?php echo UPLOADURL.'/menu_icon/'.$row['special_item_icon']; ?>" width="100" hight="100"/></span>
                    <?php 
					} 
			?>
          </td>
          <td class="center">
         	<a href="index.php?do=icon_manager&amp;action=edit&amp;menuid=<?php echo $row['special_id'];?>">
         		<img src="images/edit.png" class="tooltip"  alt="" title="Menu Icon Edit"/>
            </a>
        </td>
        <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['special_item_name'];?>" id="item_<?php echo $row['special_id'];?>">
        	<img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/>
            </a>
         </td>
        </tr>
        <?php $i++; endforeach;?>
        <?php unset($row);?>
        <?php endif;?>       
      </tbody>
    </table>
    </form>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' Menu Icon', "deleteMenuIconMaster");?>
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