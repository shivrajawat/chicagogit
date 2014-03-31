<?php
  /**
   * City
 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Location Timing")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<link rel="stylesheet" href="js/include/ui-1.10.0/ui-lightness/jquery-ui-1.10.0.custom.min.css" type="text/css" />
<link rel="stylesheet" href="js/jquery.ui.timepicker.css?v=0.3.2" type="text/css" />
<script type="text/javascript" src="js/jquery.ui.timepicker.js?v=0.3.2"></script>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowByIdNew("res_location_time_master", "location_id", $content->postid);?>
<?php  $location = $content->locationTimeEdit($content->postid); ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LTM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LTM_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LTM_SUBTITLE1 . $row['location_id'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">        
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">please select company Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($content->postid == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          </tbody>        
      </table>   
      <table width="98%" cellspacing="0" cellpadding="0" border="0" class="forms" >
        <tbody>
          <tr>
            <th>&nbsp;</th>
            <th> Open </th>
            <th> Close </th>
            <th> Last Delivery Hrs </th>
            <th> Open 24Hrs </th>
            <th> Holiday On </th>
            <th> Mor. Delivery Start</th>
            <th> Mor. Delivery End </th>
            <th> Even. Delivery Start </th>
            <th> Even. Delivery End </th>
          </tr>
          <?php foreach($location as $mrow): ?>
          <tr>
            <th><?php echo ucfirst($mrow['days']);?></th>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][day_start_time]" value="<?php echo $mrow['day_start_time'];?>"></td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][day_end_time]" value="<?php echo $mrow['day_end_time'];?>"></td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][last_order_time]" value="<?php echo $mrow['last_order_time'];?>"></td>
            <td height="30"><span maxlength="10">
              <input type="checkbox" name="days[<?php echo ucfirst($mrow['days']);?>][open_24hours]" id="open_24hours" value="1" <?php if($mrow['open_24hours']=='1') {?> checked="checked" <?php }?>>
              </span></td>
            <td height="30"><input type="checkbox" name="days[<?php echo ucfirst($mrow['days']);?>][is_holidays]" id="is_holidays" value="1" <?php if($mrow['is_holidays']=='1') {?> checked="checked" <?php }?>>
              <input type="hidden" name="days[<?php echo ucfirst($mrow['days']);?>][day]" value="<?php echo ucfirst($mrow['days']);?>" />
            </td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][d_morning_start]" value="<?php echo $mrow['d_morning_start'];?>"></td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][d_morning_end]" value="<?php echo $mrow['d_morning_end'];?>"></td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][d_evening_start]" value="<?php echo $mrow['d_evening_start'];?>"></td>
            <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][d_evening_end]" value="<?php echo $mrow['d_evening_end'];?>"></td>
          </tr>
          <?php endforeach;?>
          <tr>
            <th>Have Timing Menu</th>
            <td colspan="9"><input name="have_time_menu" type="checkbox"  id="foo"   value="1" <?php if($mrow['have_time_menu']=='1') {?> checked="checked" <?php }?>/></td>
          </tr>
        </tbody>
      </table>
      <table width="98%" cellspacing="0" cellpadding="0" border="0" class="forms" id="show_timemenu">
         <tbody>          
            <tr>
              <th>&nbsp;</th>
              <th> Breakfast Open </th>
              <th> Breakfast Close </th>
              <th> Breakfast Last Delivery Hrs </th>
              <th> Lunch Open </th>
              <th> Lunch Close </th>
              <th> Lunch Last  Delivery Hrs </th>
              <th> Dinner Open </th>
              <th> Dinner Close </th>
              <th> Dinner Last Delivery Hrs </th>
            </tr>
             <?php foreach($location as $mrow):			
			if(isset($mrow['breakfast_start'])  && !empty($mrow['breakfast_start'])){
				$aa1 = explode(" ",$mrow['breakfast_start']);
				$breakfast_start = substr($aa1[0],0,5)." ".$aa1[1];
			} else { $breakfast_start = ''; }
			
			if(isset($mrow['breakfast_end'])  && !empty($mrow['breakfast_end'])){
				$aa2 = explode(" ",$mrow['breakfast_end']);
				$breakfast_end = substr($aa2[0],0,5)." ".$aa2[1];
			} else { $breakfast_end = ''; }
			
			if(isset($mrow['breakfast_last'])  && !empty($mrow['breakfast_last'])){
				$aa3 = explode(" ",$mrow['breakfast_last']);
				$breakfast_last = substr($aa3[0],0,5)." ".$aa3[1];
			} else { $breakfast_last = ''; }
			
			if(isset($mrow['launch_start'])  && !empty($mrow['launch_start'])){
				$aa4 = explode(" ",$mrow['launch_start']);
				$launch_start = substr($aa4[0],0,5)." ".$aa4[1];
			} else { $launch_start = ''; }
			
			if(isset($mrow['launch_end'])  && !empty($mrow['launch_end'])){
				$aa5 = explode(" ",$mrow['launch_end']);
				$launch_end = substr($aa5[0],0,5)." ".$aa5[1];
			} else { $launch_end = ''; }
			
			if(isset($mrow['launch_last'])  && !empty($mrow['launch_last'])){
				$aa6 = explode(" ",$mrow['launch_last']);
				$launch_last = substr($aa6[0],0,5)." ".$aa6[1];
			} else { $launch_last = ''; }
			
			
			if(isset($mrow['dinner_start'])  && !empty($mrow['dinner_start'])){
				$aa7 = explode(" ",$mrow['dinner_start']);
				$dinner_start = substr($aa7[0],0,5)." ".$aa7[1];
			} else { $dinner_start = ''; }
			
			if(isset($mrow['dinner_end'])  && !empty($mrow['dinner_end'])){
				$aa8 = explode(" ",$mrow['dinner_end']);
				$dinner_end = substr($aa8[0],0,5)." ".$aa8[1];
			} else { $dinner_end = ''; }
			
			if(isset($mrow['dinner_last'])  && !empty($mrow['dinner_last'])){
				$aa9 = explode(" ",$mrow['dinner_last']);
				$dinner_last = substr($aa9[0],0,5)." ".$aa9[1];
			} else { $dinner_last = ''; }
			
			?>  
            <tr>
              <th>&nbsp;&nbsp;<strong><?php echo $mrow['days']; ?></strong></th>
              <td height="30"><input type="text" style="width: 60px; padding:0 !important;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][breakfast_start]" value="<?php echo $breakfast_start;?>" /></td>
              <td height="30"><input type="text" style="width: 60px; padding:0 !important;" class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][breakfast_end]" value="<?php echo $breakfast_end;?>" /></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][breakfast_last]" value="<?php echo $breakfast_last;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox" name="days[<?php echo ucfirst($mrow['days']);?>][launch_start]" value="<?php echo $launch_start;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox"  name="days[<?php echo ucfirst($mrow['days']);?>][launch_end]" value="<?php echo $launch_end;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox"  name="days[<?php echo ucfirst($mrow['days']);?>][launch_last]" value="<?php echo $launch_last;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox"  name="days[<?php echo ucfirst($mrow['days']);?>][dinner_start]" value="<?php echo $dinner_start;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox"  name="days[<?php echo ucfirst($mrow['days']);?>][dinner_end]" value="<?php echo $dinner_end;?>"></td>
              <td height="30"><input type="text" style="width:60px; padding:0 !important;"  class="timepicker inputbox"  name="days[<?php echo ucfirst($mrow['days']);?>][dinner_last]" value="<?php echo $dinner_last;?>"></td>
            </tr>
            <?php endforeach;?>     
          </tbody>
        </table>   
     <table class="forms"><tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LTM_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_timing_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot></table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<script type="text/javascript">
            $(document).ready(function() {
                $('.timepicker').timepicker({
                    showPeriod: true,
                    showLeadingZero: true
                });
	<?php if($mrow['have_time_menu']=='0') {?> $("#show_timemenu").fadeOut(); <?php  }  ?>			

$("#foo").change(function(){

   var ischecked=$(this).is(':checked'); 
    if(ischecked)
    {
         $("#show_timemenu").fadeIn();
    }
    else
    {
        $("#show_timemenu").fadeOut();   
     }

});
            });

        </script> 
<?php echo $core->doForm("processTimeLocation","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LTM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LTM_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LTM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
              
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">please select Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
        <table width="98%" cellspacing="0" cellpadding="0" border="0"  class="forms">
          <tbody>
            <tr>
              <th>&nbsp;</th>
              <th> Open </th>
              <th> Close </th>
              <th> Last Delivery Hrs </th>
              <th> Open 24Hrs </th>
              <th> Holiday On </th>
              <th> Mor. Delivery Start</th>
            <th> Mor. Delivery End </th>
            <th> Even. Delivery Start </th>
            <th> Even. Delivery End </th>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Mon</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][last_order_time]">
              </td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Monday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Monday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Monday][day]" value="Monday" /></td>
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Monday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Tue</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][last_order_time]"></td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Tuesday][open_24hours]"  value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Tuesday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Tuesday][day]" value="Tuesday" /></td>
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Tuesday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Wed</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][last_order_time]">
              </td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Wednesday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Wednesday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Wednesday][day]" value="Wednesday" /></td>
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Wednesday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Thu</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][last_order_time]">
              </td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Thursday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Thursday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Thursday][day]" value="Thursday" /></td>
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Thursday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp; <strong>Fri</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][last_order_time]">
              </td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Friday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Friday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Friday][day]" value="Friday" /></td>
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Friday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Sat</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][last_order_time]">
              </td>
             
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Saturday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Saturday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Saturday][day]" value="Saturday" /></td> 
                <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Saturday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Sun</strong></th>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][day_start_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][day_end_time]"></td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][last_order_time]">
              </td>
              <td height="30"><span maxlength="10">
                <input type="checkbox" name="days[Sunday][open_24hours]" id="open_24hours" value="1">
                </span></td>
              <td height="30"><input type="checkbox" name="days[Sunday][is_holidays]" id="is_holidays" value="1">
                <input type="hidden" name="days[Sunday][day]" value="Sunday" /></td>
                 <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][d_morning_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][d_morning_end]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][d_evening_start]">
              </td>
              <td height="30"><input type="text" style="width:65px;" class="timepicker inputbox" name="days[Sunday][d_evening_end]">
              </td>
            </tr>
            <tr>
              <th>Have Timing Menu</th>
              <td colspan="9"><input name="have_time_menu" type="checkbox" onclick="Change();" id="have_time_menu" value="1" /></td>
            </tr>
          </tbody>
        </table>
        <div id="show_timemenu"  style="display:none;">
        <table width="98%" cellspacing="0" cellpadding="0" border="0" class="forms">        
          <tbody>
            <tr>
              <td>&nbsp;</td>
              <th> Breakfast Open </th>
              <th> Breakfast Close </th>
              <th> Breakfast Last      Delivery Hrs </th>
              <th> Lunch Open </th>
              <th> Lunch Close </th>
              <th> Lunch Last Delivery Hrs </th>
              <th> Dinner Open </th>
              <th> Dinner Close </th>
              <th> Dinner Last Delivery Hrs </th>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Mon</strong></th>
              <td height="30"><input type="text" style="width: 70px;" class="timepicker inputbox" name="days[Monday][breakfast_start]" /></td>
              <td height="30"><input type="text" style="width: 70px;" class="timepicker inputbox" name="days[Monday][breakfast_end]" /></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox" name="days[Monday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox" name="days[Monday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox"  name="days[Monday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox"  name="days[Monday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox"  name="days[Monday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox"  name="days[Monday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;"  class="timepicker inputbox"  name="days[Monday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Tue</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Tuesday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Tuesday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Tuesday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Tuesday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Tuesday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"   name="days[Tuesday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Tuesday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Tuesday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Tuesday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Wed</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Wednesday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Wednesday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Wednesday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Thu</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Thursday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Thursday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Thursday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Fri</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Friday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Friday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Friday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Sat</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Saturday][dinner_last]"></td>
            </tr>
            <tr>
              <th>&nbsp;&nbsp;<strong>Sun</strong></th>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Sunday][breakfast_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox" name="days[Sunday][breakfast_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][breakfast_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][launch_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][launch_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][launch_last]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][dinner_start]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][dinner_end]"></td>
              <td height="30"><input type="text" style="width:60px;" class="timepicker inputbox"  name="days[Sunday][dinner_last]"></td>
            </tr>
          </tbody>
        </table>
        </div>
          </tbody>  
          <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LTM_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_timing_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>      
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
            $(document).ready(function() {
                $('.timepicker').timepicker({
                    showPeriod: true,
                    showLeadingZero: true
                });
            });
        </script> 
<script type="text/javascript">
 function Change() {
 
 $('#have_time_menu').live("click", function() {
    if (this.checked) {	
        $('#show_timemenu').show();
    }
    else {
	
        $('#show_timemenu').hide();
    }
});
}
</script>
<?php echo $core->doForm("processTimeLocation","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $companyrow = $content->getTime($userlocationid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LTM_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _Time_LocationINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2>
    <?php if($user->userlevel == 9):?>
    <span><a href="index.php?do=location_timing_master&amp;action=add" class="button-sml"><?php echo _LTM_ADD;?></a></span>
    <?php endif;?>
	<?php echo _LTM_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _LTM_DY;?></th>
         <?php /*?> <th class="left sortable"><?php echo _LTM_DST;?></th>
          <th class="left sortable"><?php echo _LTM_DET;?></th>
          <th class="left sortable"><?php echo _LTM_LDT;?></th>
          <th class="left sortable"><?php echo _LTM_BRS;?></th>
          <th class="left sortable"><?php echo _LTM_BFE;?></th>
          <th class="left sortable"><?php echo _LTM_BFL;?></th><?php */?>
          <th><?php echo _LTM_EDIT;?></th>
          <?php if($user->userlevel == 9):?>
          <th><?php echo _DELETE;?></th>
          <?php endif;?>
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
        <?php if(!$companyrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_LTM_NOLOCTIME,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($companyrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>
          <td><?php echo $row['days'] ;?></td>
         <?php /*?> <td><?php echo $row['day_start_time'] ;?></td>
          <td><?php echo $row['day_end_time'] ;?></td>
          <td><?php echo $row['last_order_time'] ;?></td>
          <td><?php echo $row['breakfast_start'] ;?></td>
          <td><?php echo $row['breakfast_end'] ;?></td>
          <td><?php echo $row['breakfast_last'] ;?></td><?php */?>
          <td class="center"><a href="index.php?do=location_timing_master&amp;action=edit&amp;postid=<?php echo $row['location_id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _LTM_EDIT;?>"/></a></td>
          <?php if($user->userlevel == 9):?>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['location_name'];?>" id="item_<?php echo $row['location_id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._LTD_MANAGER, "deleteTime");?> 
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