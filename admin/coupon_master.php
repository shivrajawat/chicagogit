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
<?php $row = $core->getRowById("res_coupon_master", $menu->menuid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _MSIZE_TITLE11;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MSIZE_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MSIZE_SUBTITLE1 . $row['title'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
  <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CPN_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=coupon_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
         
          <tr>
            <th><?php echo _CPN_TITLE;?>: <?php echo required();?></th>
            <td><input name="title" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_NOOFCOP;?>: <?php echo required();?></th>
            <td><input name="noofcoupon" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_TODIS;?>: </th>
            <td><input name="typeofdiscount" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          
          <tr>
            <th><?php echo _CPN_DISCNT;?>: <?php echo required();?></th>
            <td><input name="discount" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th>Amount Limits: <?php echo required();?></th>
            <td><input name="amount_limit" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_NOOFUSEA;?>: <?php echo required();?></th>
            <td><input name="noofuseallowed" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CPN_CREDATE;?>: <?php echo required();?></th>
            <td><input name="start_date" id="start_date" type="text" class="inputbox textbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_ENDATE;?>: <?php echo required();?></th>
            <td><input name="end_date" id="end_date" type="text" class="inputbox textbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
          <th><?php echo _CPN_DESC;?>: </th>
          <td> <textarea class="textbox" name="description" cols="55"  rows="4"></textarea></td>
          </tr>
          <tr>
            <th><?php echo _CPN_ACTIVE;?>:</th>
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
<script type="text/javascript">
var dates = $('#start_date, #end_date').datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var option = this.id == "start_date" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
</script>
<?php echo $core->doForm("processCouponmaster","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CPN_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CPN_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CPN_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CPN_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=coupon_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
         
          <tr>
            <th><?php echo _CPN_TITLE;?>: <?php echo required();?></th>
            <td><input name="title" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_NOOFCOP;?>: <?php echo required();?></th>
            <td><input name="noofcoupon" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>" maxlength="4"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_TODIS;?>: </th>            
            <td><span class="input-out">
              <label for="typeofdiscount-1"><?php echo _PERCENT;?></label>
              <input name="typeofdiscount" type="radio" id="typeofdiscount-1" value="percent" checked="checked" />
              <label for="typeofdiscount-2"><?php echo _FIXED;?></label>
              <input name="typeofdiscount" type="radio" id="typeofdiscount" value="2"  />
              </span></td>
          </tr>
           <tr id="amount_limit">
            <th>Amount Limits:</th>
            <td><input type="text" name="amount_limit" class="inputbox" value="" ></td>
          </tr>
          <tr>
            <th><?php echo _CPN_DISCNT;?>: <?php echo required();?></th>
            <td><input name="discount" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>" maxlength="5"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_NOOFUSEA;?>: <?php echo required();?></th>
            <td><input name="noofuseallowed" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CPN_CREDATE;?>: <?php echo required();?></th>
            <td><input name="start_date" id="start_date" type="text" class="inputbox textbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CPN_ENDATE;?>: <?php echo required();?></th>
            <td><input name="end_date" id="end_date" type="text" class="inputbox textbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
          <th><?php echo _CPN_DESC;?>: </th>
          <td> <textarea class="textbox" name="description" cols="55"  rows="4"></textarea></td>
          </tr>
          <tr>
            <th><?php echo _CPN_ACTIVE;?>:</th>
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
<script type="text/javascript">
var dates = $('#start_date, #end_date').datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var option = this.id == "start_date" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
</script>
<?php echo $core->doForm("processCouponmaster","controller.php");?>
<?php break;?>
<?php default: ?>
<?php if(isset($_GET['active']) && !empty($_GET['active'])):
	$data['active'] = "1";
	 $db->update("res_coupon_master", $data, "id='" . $_GET['active']  . "'");	
endif;
if(isset($_GET['deactive']) && !empty($_GET['deactive'])):
	$data['active'] = "0";
	 $db->update("res_coupon_master", $data, "id='" . $_GET['deactive']  . "'");	
endif;
?>

<?php $couponrow = $menu->getCouponMaster();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CPN_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CPNINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=coupon_master&amp;action=add" class="button-sml"><?php echo _CPN_ADD;?></a></span><?php echo _CPN_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _CPN_TITLE;?></th>
          <th class="left">Coupon Code</th>
          <th class="left sortable"><?php echo _CPN_NOOFUSEA;?></th>
           <th class="left sortable"><?php echo _CPN_USNO;?></th>
           <th class="left sortable">Amount Limit</th>
          <th><?php echo _MSACTIVE;?></th>
          <th>View</th> 
          <td class="center"><input type="checkbox" name="masterCheckbox" id="masterCheckbox" class="checkbox"/></td>
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
        <?php if(!$couponrow):?>
        <tr>
          <td colspan="8"><?php echo $core->msgAlert(_CPN_NOSIZE,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($couponrow as $row):?>
        <tr>
         <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'];?></td>
          <td><?php echo ($row['coupon_id']) ? $row['coupon_id'] : "";?></td>
          <td><?php echo $row['no_of_use_allowed'] ;?></td>
          <td><?php echo $row['used_no'] ;?></td>
          <td><?php echo $row['amount_limit'] ;?></td>
          <td class="center"><?php echo isActive($row['active']);?> 
          <a href="index.php?do=coupon_master&amp;active=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Published</a> | 
          <a href="index.php?do=coupon_master&amp;deactive=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Unpublished</a> </td>
          <td class="center"><a href="javascript:void(0);" class="view-coupon" data-info="<?php echo $row['title'];?>" id="cust_<?php echo $row['id'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo _CUSVIEW_;?>"/></a></td>
        <td class="center"><input name="couponid[]" type="checkbox" class="checkbox" id="status<?php echo $row['id'];?>" value="<?php echo $row['id'];?>" /></td>
        <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
        <tr>
    <td colspan="8"><div style="float:right">
    <input type="submit" value="Export to Excel" name="excelexport"  class="button-blue doform" id="excelexport"/>
    <input type="submit" value="Export to Word" name="wordexport" class="button-blue doform" id="wordexport"/>
    <input type="submit" value="Export to CSV" name="csvexport" class="button-blue doform" id="csvexport"/>
    <input type="submit" value="Export to PDF" name="pdfexport" class="button-blue doform" id="pdfexport"/></div></td>
  </tr>        
      </tbody>
    </table>
    </form>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._CPN_MANAGER, "deleteCouponMaster");?>
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
	
	 $('.doform').click(function () {
        var action = $(this).attr('id');
        var str = $("#admin_form").serialize();
        str += '&couponproccess=1';
        str += '&action=' + action;
        $.ajax({
            type: "post",
            url: "controller.php",
            data: str,
            beforeSend: function () {
                $('.display tbody tr').each(function () {
                    if ($(this).find('input:checked').length) {
                        $(this).animate({
                            'backgroundColor': '#FFBFBF'
                        }, 400);
                    }
                });
            },
            success: function (msg) {
                $('.display tbody tr').each(function () {
                    if ($(this).find('input:checked').length) {
                        if (action == "delete") {
                            $(this).fadeOut(400, function () {
                                $(this).remove();
                            });
                        } else {
                            $(this).animate({
                                'backgroundColor': '#fff'
                            }, 400);

                        }
                    }
                });
                $("#msgholder").html(msg);
            }
        });
        return false;
    });
});
// View Customer Details 
    $('a.view-coupon').click(function() {
        var id = $(this).attr('id').replace('cust_', '')
        var title = $(this).attr('data-info');
		  $.ajax({
			  type: 'post',
			  url: "ajax.php",
			  data: 'viewCoupon=' + id + '&title=' + title,
			  success: function (res) {
				$.confirm({
					'title': title,
					'message': res,
					'buttons': {
						'<?php echo _CLOSE;?>': {
							'class': 'no'
						}
					}
				});
			  }
		  });	
        return false;
    
	});
	

$(function () {
	$('#masterCheckbox').click(function(e) {
		$(this).parent().toggleClass("ez-checked");
		$('input[name^="couponid"]').each(function() {
			($(this).is(':checked')) ? $(this).removeAttr('checked') : $(this).attr({"checked":"checked"});
			 $(this).trigger('change');
		});
		return false;
	});
});




// ]]>
</script>

<?php break;?>
<?php endswitch;?>
<script type="text/javascript">
// folw radio button click chage div of location hide show 
$("input[name$='typeofdiscount']").click(function() { 
        var fixed = $(this).val();
		
		//alert(test); 	
		if (fixed=='fixed') {	
			$('#amount_limit').show();
    	}	
	
    });

</script>