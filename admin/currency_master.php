<?php
  /**
   * Currency Manager page
   * 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Currency Master")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_currency_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CUR_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CUR_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CUR_SUBTITLE1 . $row['currency'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CUR_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=currency_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _CUR_TITLE;?>: <?php echo required();?></th>
            <td><input name="currency" type="text" class="inputbox"  size="55" value="<?php echo $row['currency'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CUR_SYMBOL;?>: <?php echo required();?></th>
            <td><input name="currency_symbol" type="text" class="inputbox"  size="55" value="<?php echo $row['currency_symbol'];?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CUR_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>    
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processCurrency","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CUR_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CUR_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CUR_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CUR_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=currency_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _CUR_TITLE;?>: <?php echo required();?></th>
            <td><input name="currency" type="text" class="inputbox"  size="55" title="<?php echo _CUR_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CUR_SYMBOL;?>: <?php echo required();?></th>
            <td><input name="currency_symbol" type="text" class="inputbox"  size="55" title="<?php echo _CUR_SYM_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CUR_PUB;?>:</th>
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
<?php echo $core->doForm("processCurrency","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $currencyrow = $content->getCurrency();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CUR_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CURINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=currency_master&amp;action=add" class="button-sml"><?php echo _CUR_ADD;?></a></span><?php echo _CUR_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _CUR_TITLE;?></th>
          <th class="left sortable"><?php echo _CUR_SYMBOL;?></th>
          <th><?php echo _PUBLISHED;?></th>
          <th><?php echo _CUR_EDIT;?></th>
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
        <?php if(!$currencyrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_CUR_NOCURRENCY,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($currencyrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['currency'];?></td>
          <td><?php echo $row['currency_symbol'] ;?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="center"><a href="index.php?do=currency_master&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _CUR_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['currency'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._CUR_TITLE, "deleteCurrency");?>
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