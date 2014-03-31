<?php
  /**
   * jQuery Slider
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("videoslider")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $slider = new videoSlider();
?>
<?php switch($core->paction): case "edit": ?>
<?php $slrow = $core->getRowById("plug_videoslider", $slider->sliderid);?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_VS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_VS_INFO3 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_VS_TITLE.' '.PLG_VS_UPDATE;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_VS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=videoslider" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_VS_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" value="<?php echo $slrow['title'.$core->dblang];?>"  size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_VS_DESC;?>:</th>
            <td><textarea id="bodycontent" name="description<?php echo $core->dblang;?>" cols="50" rows="6"><?php echo $slrow['description'.$core->dblang];?></textarea></td>
          </tr>
          <tr>
            <th><?php echo PLG_VS_URL;?>: <?php echo required();?></th>
            <td><input type="text" name="vidurl" class="inputbox" value="<?php echo $slrow['vidurl'];?>"  size="55"/>
              <?php echo tooltip(PLG_VS_URL_T);?></td>
          </tr>
        </tbody>
      </table>
      <input name="sliderid" type="hidden" value="<?php echo $slider->sliderid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processSliderImage","plugins/videoslider/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_VS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_VS_INFO3 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_VS_TITLE.' '.PLG_VS_IMGUPLOAD;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_VS_IMGUPLOAD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=videoslider" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_VS_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" title="<?php echo PLG_VS_CAPTION_R;?>" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_VS_DESC;?>:</th>
            <td><textarea id="bodycontent" name="description<?php echo $core->dblang;?>" cols="50" rows="6"></textarea></td>
          </tr>
          <tr>
            <th><?php echo PLG_VS_URL;?>: <?php echo required();?></th>
            <td><input type="text" name="vidurl" class="inputbox" size="55"/>
              <?php echo tooltip(PLG_VS_URL_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processSliderImage","plugins/videoslider/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $getimgs = $slider->getSliderImages();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_VS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_VS_INFO3 . PLG_VS_INFO3_1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=config&amp;plug=videoslider&amp;plug_action=add" class="button-sml"><?php echo PLG_VS_IMGUPLOAD;?></a> </span><?php echo PLG_VS_SUBTITLE3 . $content->getPluginName(get("plug"));?></h2>
  </div>
  <div class="block-content">
    <table class="display" id="pagetable">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_VS_CAPTION;?></th>
          <th><?php echo PLG_VS_POS;?></th>
          <th><?php echo PLG_VS_EDIT;?></th>
          <th><?php echo PLG_VS_DEL;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($getimgs == 0):?>
        <tr>
          <td colspan="5"><?php echo $core->msgAlert(PLG_VS_NOIMG,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($getimgs as $slrow):?>
        <tr id="node-<?php echo $slrow['id'];?>">
          <th class="id-handle center"><?php echo $slrow['id'];?>.</th>
          <td><?php echo $slrow['title'.$core->dblang];?></td>
          <td class="center"><?php echo $slrow['position'];?></td>
          <td class="center"><a href="index.php?do=plugins&amp;action=config&amp;plug=videoslider&amp;plug_action=edit&amp;sliderid=<?php echo $slrow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_VS_EDIT.': '.$slrow['title'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $slrow['title'.$core->dblang];?>" id="item_<?php echo $slrow['id'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo PLG_VS_DEL.': '.$slrow['title'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($slrow);?>
        <tr>
          <td colspan="5"><a href="javascript:void(0);" id="serialize" class="button"><?php echo PLG_VS_SAVE_POS;?></a></td>
        </tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_VS_SLIDE, "deleteSlide","plugins/videoslider/controller.php");?>
<script type="text/javascript"> 
// <![CDATA[
var tableHelper = function (e, tr) {
    tr.children().each(function () {
        $(this).width($(this).width());
    });
    return tr;
};
$(document).ready(function () {
    $("#pagetable tbody").sortable({
        helper: tableHelper,
        handle: '.id-handle',
        opacity: .6
    }).disableSelection();

    $('#serialize').click(function () {
        serialized = $("#pagetable tbody").sortable('serialize');
        $.ajax({
            type: "POST",
            url: "plugins/videoslider/controller.php?sortslides",
            data: serialized,
            success: function (msg) {
                $("#msgholder").html(msg);
            }
        });
    })
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>