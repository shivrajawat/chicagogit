<?php
  /**
   * State Manager
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Holidays")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_holiday_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _HT_TITLE11;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _HT_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _HT_SUBTITLE1 . $row['holiday_date'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _HT_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=holiday_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td>
            <?php if($user->userlevel == 9):?>
            <select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">please select company Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($row['location_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select>
              <?php endif;?>
              </td>
          </tr>
          <tr>
            <th><?php echo _HOLY_DATE;?>: <?php echo required();?></th>
            <td><input name="holiday_date[]" type="text" class="inputbox textbox"   size="55" value="<?php echo $row['holiday_date'];?>"/>
              <!--<img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/>
                    <img src="images/delete.png" id='removeButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/>-->
            </td>
          </tr>
          <tr>
            <th>Holiday Description</th>
            <td colspan="2" class="editor"><textarea name="holiday_description[]" rows="4" cols="45"><?php echo $row['holiday_description'];?></textarea>
            </td>
          </tr>
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processHolidays","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _H_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _H_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _H_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _H_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=holiday_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
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
          <tr>
            <th><?php echo _HOLY_DATE;?> /Holiday Description:</th>
            <td><div id='TextBoxesGroup'>
                <div id="TextBoxDiv1">
                  <input name="holiday_date[]" id='textbox1 fromdate' type="text" class="inputbox textbox"   size="40" title="<?php echo _S_TITLE_R;?>"/>
                  <textarea class="textbox" name="holiday_description[]" cols="45"  rows="4"></textarea>
                  <img src="images/add.png" id='addButton' style="cursor:pointer;"  class="tooltip"  alt="" title="Add new field"/> </div>
              </div>
              <img src="images/delete.png" id='removeButton' style="cursor:pointer; margin-top:-110px; margin-left:590px;"  class="tooltip"  alt="" title="Remove field"/> </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<div id="dialog" title="Kulacart Warning" style="display:none">
  <p>You can add 10 holidays at a time</p>
</div>
<?php echo $core->doForm("processHolidays","controller.php",'1','1');?>
<?php break;?>
<?php default: ?>
<?php $holodayrow = $content->getholiday($userlocationid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _H_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _HOLYINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=holiday_master&amp;action=add" class="button-sml"><?php echo _H_ADD;?></a></span><?php echo _H_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _H_TITLE;?></th>
          <th class="left sortable"><?php echo _HOLY_DATE;?></th>
          <th><?php echo _HT_EDIT;?></th>
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
        <?php if(!$holodayrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_HD_NOHOLIDAYS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($holodayrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>
          <td><?php echo $row['holiday_date'] ;?></td>
          <td class="center"><a href="index.php?do=holiday_master&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _HT_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['location_id'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._HOLY_DATE, "deleteholiday");?>
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
   
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
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
		var maincontent="<input type ='textbox' name='holiday_date[]"+increment+"'  class='inputbox TextBoxDiv textbox'  size='40' id='textbox"+increment+"'/><textarea style='margin-left:5px;' class='textbox TextBoxDiv' name='holiday_description[]' cols='45'  rows='4'></textarea>";		
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
<script>
    $(document).on('focusin', '.textbox', function(){
      $(this).datepicker({dateFormat: $.datepicker.W3C, changeMonth: true, changeYear: true, yearRange: '2011:2020'});
    });
</script>
