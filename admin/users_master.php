<?php
  /**
   * Users
   *
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Users")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php if($user->userlevel == 8 and $user->userid == 1): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;?>
<?php $row = $core->getRowById("res_users_master", $user->userid);?>
<?php $memrow = $member->getMemberships();?>
<div class="block-top-header">
  <h1><img src="images/users-sml.png" alt="" /><?php echo _UR_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _UR_INFO1. _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _UR_SUBTITLE1 . $row['username'];?></h2>
  </div>
  <div class="block-content"> 
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _UR_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=users_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _USERNAME;?>: <?php echo required();?></th>
            <td><input name="username" type="text" disabled="disabled" class="inputbox" value="<?php echo $row['username'];?>" size="55" readonly="readonly" /></td>
          </tr>
          <tr>
            <th><?php echo _PASSWORD;?>:</th>
            <td><input name="password" type="text" class="inputbox" size="55" />
              <?php echo tooltip(_UR_PASS_T);?></td>
          </tr>
          <tr>
            <th><?php echo _UR_EMAIL;?>: <?php echo required();?></th>
            <td><input name="email" type="text" class="inputbox" value="<?php echo $row['email'];?>" size="55"/></td>
          </tr>
          
          
          <?php if($user->userlevel == 9):?>
          <tr>
            <th><?php echo _LOCATION;?>:</th>
            <td><select name="location_id[]" multiple="multiple"  class="select" style="width:250px" size="10">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php  $location_array = explode(",", $row['location_id']);  $sel = (in_array($prow['id'],$location_array) == $location_array) ? ' selected="selected"' : ''; ?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _UR_LEVEL;?>:</th>
            <td><span class="input-out">
              <label for="userlevel-1"><?php echo _UR_SADMIN;?></label>
              <input name="userlevel" type="radio" id="userlevel-1" value="9"  <?php getChecked($row['userlevel'], 9); ?> />
              <label for="userlevel-2"><?php echo _UR_ADMIN;?></label>
              <input name="userlevel" type="radio" id="userlevel-2" value="8"   <?php getChecked($row['userlevel'], 8); ?>/>              
              <?php echo tooltip(_UR_ADMIN_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _UR_PERM;?>:</th>
            <td><?php echo $user->getPermissionList($row['access']);?></td>
          </tr>
          <?php endif;?>
          
          <tr>
            <th><?php echo _UR_STATUS;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _USER_A;?></label>
              <input name="active" type="radio" id="active-1" value="y" <?php getChecked($row['active'], "y"); ?> />
              <label for="active-2"><?php echo _USER_I;?></label>
              <input name="active" type="radio" id="active-2" value="n" <?php getChecked($row['active'], "n"); ?> />
              <label for="active-3"><?php echo _USER_B;?></label>
              <input name="active" type="radio" id="active-3" value="b" <?php getChecked($row['active'], "b"); ?> />
              <label for="active-4"><?php echo _USER_P;?></label>
              <input name="active" type="radio" id="active-4" value="t" <?php getChecked($row['active'], "t"); ?> />
              </span></td>
          </tr>  
        </tbody>
      </table>      
      <input name="username" type="hidden" value="<?php echo $row['username'];?>" />
      <input name="userid" type="hidden" value="<?php echo $user->userid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processUser");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/users-sml.png" alt="" /><?php echo _UR_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _UR_INFO2. _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _UR_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _UR_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=users_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _USERNAME;?>: <?php echo required();?></th>
            <td><input name="username" type="text" class="inputbox"  id="username" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _PASSWORD;?>: <?php echo required();?></th>
            <td><input name="password" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _UR_EMAIL;?>: <?php echo required();?></th>
            <td><input name="email" type="text" class="inputbox" size="55" /></td>
          </tr>
          
          
          <?php if($user->userlevel == 9):?>
          <tr>
            <th><?php echo _LOCATION;?>:</th>
            <td><select name="location_id[]" multiple="multiple"  class="select" style="width:250px" size="10">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _UR_LEVEL;?>:</th>
            <td><span class="input-out">
              <label for="userlevel-1"><?php echo _UR_SADMIN;?></label>
              <input name="userlevel" type="radio" id="userlevel-1" value="9" />
              <label for="userlevel-2"><?php echo _UR_ADMIN;?></label>
              <input name="userlevel" type="radio" id="userlevel-2" value="8"  checked="checked"/>              
              <?php echo tooltip(_UR_ADMIN_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _UR_PERM;?>:</th>
            <td><?php echo $user->getPermissionList();?></td>
          </tr>
          <?php endif;?>
          
          <tr>
            <th><?php echo _UR_STATUS;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _USER_A;?></label>
              <input name="active" type="radio" id="active-1" value="y" checked="checked" />
              <label for="active-2"><?php echo _USER_I;?></label>
              <input name="active" type="radio" id="active-2" value="n" />
              <label for="active-3"><?php echo _USER_B;?></label>
              <input name="active" type="radio" id="active-3" value="b" />
              <label for="active-4"><?php echo _USER_P;?></label>
              <input name="active" type="radio" id="active-4" value="t" />
              </span></td>
          </tr>  
        </tbody>
      </table>      
    </form>
  </div>
</div>
<?php echo $core->doForm("processUser");?>
<?php break;?>
<?php default:?>
<?php  $userrow = $user->getUsers();?>
<div class="block-top-header">
  <h1><img src="images/users-sml.png" alt="" /><?php echo _UR_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _UR_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=users_master&amp;action=add" class="button-sml"><?php echo _UR_ADD;?></a></span><?php echo _UR_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display">
        <tr>
          <td><input type="text" class="inputbox" id="searchfield" size="60" name="usersearchfield" />
            <div style="position:relative">
              <div id="suggestions" class="box2" style="display:none"></div>
            </div></td>
          <td><form action="#" method="post" id="dForm">
              <strong> <?php echo _UR_SHOW_FROM;?></strong>
              <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="12" id="fromdate" />
              <strong> <?php echo _UR_SHOW_TO;?></strong>
              <input name="enddate" type="text" class="inputbox-sml" size="12" id="enddate" />
              <input name="find" type="submit" class="button-blue" value="<?php echo _UR_FIND;?>" />
            </form></td>
        </tr>
        <tr>
          <td><img src="images/u_active.png" class="tooltip" alt="" title="<?php echo _USER_A;?>"/> <?php echo _USER_A;?> <img src="images/u_inactive.png" class="tooltip" alt="" title="<?php echo _USER_I;?>"/> <?php echo _USER_I;?> <img src="images/u_pending.png" class="tooltip" alt="" title="<?php echo _USER_P;?>"/> <?php echo _USER_P;?> <img src="images/u_banned.png" class="tooltip" alt="" title="<?php echo _USER_B;?>"/> <?php echo _USER_B;?></td>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _USERNAME;?></th>
          <th class="left sortable"><?php echo _UR_EMAIL;?></th>
          <th class="sortable"><?php echo _UR_STATUS;?></th>          
          <th><?php echo _UR_LEVEL;?></th>
          <th><?php echo _UR_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->items_total >= $pager->items_per_page):?>
      <tfoot>
        <tr>
          <td colspan="8"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if($userrow == 0):?>
        <tr>
          <td colspan="8"><?php echo $core->msgAlert(_UR_NOUSER,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($userrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><a href="index.php?do=newsletter&amp;emailid=<?php echo urlencode($row['email']);?>"><?php echo $row['username'];?></a></td>
          <td><?php echo $row['email'];?></td>
          <td class="center"><?php echo userStatus($row['active']);?></td>          
          <td class="center"><?php echo isAdmin($row['userlevel']);?></td>
          <td class="center"><a href="index.php?do=users_master&amp;action=edit&amp;userid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _UR_EDIT;?>"/></a></td>
          <td class="center"><?php if($row['id'] == 1):?>
            <img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/>
            <?php else:?>
            <a href="javascript:void(0);" class="delete" data-title="<?php echo $row['username'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a>
            <?php endif;?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._USER, "deleteUser");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $("#searchfield").watermark("<?php echo _UR_FIND_UNAME;?>");
    $("#searchfield").keyup(function () {
        var srch_string = $(this).val();
        var data_string = 'userSearch=' + srch_string;
        if (srch_string.length > 3) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: data_string,
                beforeSend: function () {
                    $('#search-input').addClass('loading');
                },
                success: function (res) {
                    $('#suggestions').html(res).show();
                    $("input").blur(function () {
                        $('#suggestions').customFadeOut();
                    });
                    if ($('#search-input').hasClass("loading")) {
                        $("#search-input").removeClass("loading");
                    }
                }
            });
        }
        return false;
    });
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            5: {
                sorter: false
            },
            6: {
                sorter: false
            },
            7: {
                sorter: false
            }
        }
    });
});
    var dates = $('#fromdate, #enddate').datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var option = this.id == "fromdate" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
// ]]>
</script>
<?php break;?>
<?php endswitch;?>