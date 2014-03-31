<?php
  /**
   * Location Setting Master
 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Posts")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php //include("help/posts.php");?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_location_setting", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LS_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LS_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LS_SUBTITLE1 . $row['smtp_host'];?></h2>
  </div>
  <div class="block-content">
  
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LSU_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_setting" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select  Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($row['location_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
       </tr>
       <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Email Setting </h2></td>
          </tr>
           <tr>
            <th><?php echo _LSSM;?>: </th>
            <td><input name="smtp_host" type="text" class="inputbox" value="<?php echo $row['smtp_host'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSUS;?>: </th>
            <td><input name="username" type="text" class="inputbox" value="<?php echo $row['username'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPS;?>: </th>
            <td><input name="password" type="password" class="inputbox" value="<?php echo $row['password'];?>"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000"> Fax Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSFU;?>: </th>
            <td><input name="fax_url" type="text" class="inputbox" value="<?php echo $row['fax_url'];?>"  size="55" title=""/></td>
          </tr>
         
          <tr>
            <th><?php echo _LSFUSER;?>:</th>
            <td><input name="fax_user_name" type="text" class="inputbox" value="<?php echo $row['fax_user_name'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPASSWORD;?>:</th>
            <td><input name="fax_password" type="password" class="inputbox" value="<?php echo $row['fax_password'];?>"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000"> Google Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSGAPI;?>:</th>
            <td><textarea name="google_api_key" cols="50" rows="6"><?php echo $row['google_api_key'];?></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _LSGASCRIPT;?>:</th>
            <td><textarea name="google_anaytics_script" cols="50" rows="6"><?php echo $row['google_anaytics_script'];?></textarea>
              </td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Support Email Setting </h2></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL1;?>: </th>
            <td><input name="support_email1" type="text" class="inputbox" value="<?php echo $row['support_email1'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL2;?>: </th>
            <td><input name="support_email2" type="text" class="inputbox" value="<?php echo $row['support_email2'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL3;?>: </th>
            <td><input name="support_email3" type="text" class="inputbox" value="<?php echo $row['support_email3'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL4;?>: </th>
            <td><input name="support_email4" type="text" class="inputbox" value="<?php echo $row['support_email4'];?>"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Social Media Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSFURL;?>: </th>
            <td><input name="fb_url" type="text" class="inputbox"  value="<?php echo $row['fb_url'];?>" size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSTURL;?>: </th>
            <td><input name="twitter_url" type="text" class="inputbox" value="<?php echo $row['twitter_url'];?>"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSYURL;?>: </th>
            <td><input name="youtube_url" type="text" class="inputbox"  value="<?php echo $row['youtube_url'];?>" size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPURL;?>: </th>
            <td><input name="pininterest_url" type="text" class="inputbox" value="<?php echo $row['pininterest_url'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSPIURL;?>: </th>
            <td><input name="yelpUrl" type="text" class="inputbox" value="<?php echo $row['yelpUrl'];?>"  size="55" title=""/></td>
          </tr>
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processlocationsetting","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LS_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LS_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2>Add Location Setting</h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LS_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_setting" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select  Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($content->postid == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
       </tr>
        <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Email Setting </h2></td>
          </tr>
           <tr>
            <th><?php echo _LSSM;?>: </th>
            <td><input name="smtp_host" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSUS;?>: </th>
            <td><input name="username" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPS;?>: </th>
            <td><input name="password" type="password" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Fax Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSFU;?>:</th>
            <td><input name="fax_url" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
         
          <tr>
            <th><?php echo _LSFUSER;?>: </th>
            <td><input name="fax_user_name" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPASSWORD;?>: </th>
            <td><input name="fax_password" type="password" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Google Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSGAPI;?>:</th>
            <td><textarea name="google_api_key" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _LSGASCRIPT;?>:</th>
            <td><textarea name="google_anaytics_script" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Support Email Setting </h2></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL1;?>: </th>
            <td><input name="support_email1" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL2;?>: </th>
            <td><input name="support_email2" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL3;?>: </th>
            <td><input name="support_email3" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSSEMAIL4;?>: </th>
            <td><input name="support_email4" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Social Media Setting </h2></td>
          </tr>
          <tr>
            <th><?php echo _LSFURL;?>:</th>
            <td><input name="fb_url" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSTURL;?>: </th>
            <td><input name="twitter_url" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSYURL;?>:</th>
            <td><input name="youtube_url" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _LSPURL;?>: </th>
            <td><input name="pininterest_url" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _LSPIURL;?>: </th>
            <td><input name="yelpUrl" type="text" class="inputbox"  size="55" title=""/></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processlocationsetting","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $locationsettingrow = $content->getlocationsetting();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LS_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LSETTINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=location_setting&amp;action=add" class="button-sml"><?php echo _LS_ADD;?></a></span><?php echo _LS_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _LTM_NAME;?></th>
          <th class="left sortable"><?php echo _LSSM;?></th>
          <th class="left sortable"><?php echo _LSUS;?></th>
          <th class="left sortable"><?php echo _LSPS;?></th>
          <th class="left sortable"><?php echo _LSFU;?></th>
           <th class="left sortable"><?php echo _LSSEMAIL1;?></th>
         <th class="left sortable"><?php echo _LSFURL;?></th>
          <th><?php echo _LSE_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="10"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$locationsettingrow):?>
        <tr>
          <td colspan="10"><?php echo $core->msgAlert(_LS_NOLOSET,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($locationsettingrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>          
          <td><?php echo $row['smtp_host'] ;?></td>
          <td><?php echo $row['username'] ;?></td>
          <td><?php echo $row['password'] ;?></td>
          <td><?php echo $row['fax_url'] ;?></td>
          <td><?php echo $row['support_email1'] ;?></td>
          <td><?php echo $row['fb_url'] ;?></td>
          <td class="center"><a href="index.php?do=location_setting&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _LSE_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['location_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._LS_SETTING, "deletelocationsetting");?>
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