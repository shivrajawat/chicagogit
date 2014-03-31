<?php
  /**
   * Company Manager 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Customer")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_customer_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CUS_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CUS_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CUS_SUBTITLE1 . $row['first_name']. "" .$row['last_name'];?></h2>
  </div>
  <div class="block-content">   
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CUS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=customer_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist();?>
                <option value="">please select company Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($row['id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _COUNTRY_TITLE;?>:<?php echo required();?></th>
            <td><select name="country_id" class="custombox" style="width:300px">
                <?php $countryrow = $content->getCountrylist();?>
                <option value="">please select country</option>
                <?php foreach ($countryrow as $crow):?>
                <?php $sel = ($row['id'] == $crow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $crow['id'];?>"<?php echo $sel;?>><?php echo $crow['country_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _S_TITLE;?>:<?php echo required();?></th>
            <td><select name="state_id" class="custombox" style="width:300px">
                <?php $staterow = $content->getstatelist();?> 
                <option value="">select your state</option>
                <?php foreach ($staterow as $srow):?> 
                <?php $sel = ($row['id'] == $srow['id']) ? ' selected="selected"' : '' ;?>              
                <option value="<?php echo $srow['id'];?>"<?php echo $sel;?>><?php echo $srow['state_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CI_NAME;?>:<?php echo required();?></th>
            <td><select name="city_id" class="custombox" style="width:300px">
                <?php $cityrow = $content->getCitylist();?> 
                <option value="">select your City</option>
                <?php foreach ($cityrow as $cirow):?>      
                <?php $sel = ($row['id'] == $cirow['id']) ? ' selected="selected"' : '' ;?>           
                <option value="<?php echo $cirow['id'];?>"<?php echo $sel;?>><?php echo $cirow['city_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CMM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" cols="50" rows="6"><?php echo $row['address1'];?></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CSM_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"><?php echo $row['address2'];?></textarea>
              </td>
          </tr>
           <tr>
            <th><?php echo _CMM_FIR;?>: <?php echo required();?></th>
            <td><input name="first_name" type="text" class="inputbox"  size="55" value="<?php echo $row['first_name'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_LST;?>: <?php echo required();?></th>
            <td><input name="last_name" type="text" class="inputbox"  size="55" value="<?php echo $row['last_name'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_EML;?>: <?php echo required();?></th>
            <td><input name="email_id" type="text" class="inputbox"  size="55" value="<?php echo $row['email_id'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_PASS;?>: <?php echo required();?></th>
            <td><input name="password" type="text" class="inputbox"  size="55" value="<?php echo $row['password'];?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CMM_PHONE;?>: <?php echo required();?></th>
            <td><input name="phone_number" type="text" class="inputbox"  size="55" value="<?php echo $row['phone_number'];?>"/></td>
          </tr>
           <tr>
           <tr>
            <th><?php echo _CM_ZIP;?>: <?php echo required();?></th>
            <td><input name="zipcode" type="text" class="inputbox"  size="55" value="<?php echo $row['zipcode'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _DMM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _DSM_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CM_LATI;?>: <?php echo required();?></th>
            <td><input name="latitude" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_LONG;?>: <?php echo required();?></th>
            <td><input name="longitude" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_WEB;?>: <?php echo required();?></th>
            <td><input name="website" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_APP;?>: </th>
            <td><input name="application_id" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CM_SP;?>: <?php echo required();?></th>
            <td><input name="specialist" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
         <tr>
            <th><?php echo _CM_LOGO;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="logo" />
              </div></td>
          </tr>
           <tr>
            <th><?php echo _CM_PUB;?>:<?php echo required();?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processcompany","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CMM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CMM_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CMM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CMM_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=customer_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LTM_NAME;?>:<?php echo required();?></th>
            <td><select name="location_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist();?>
                <option value="">please select company Name</option>
                <?php foreach ($locationrow as $prow):?>
                <?php $sel = ($row['id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _COUNTRY_TITLE;?>:<?php echo required();?></th>
            <td><select name="country_id" class="custombox" style="width:300px">
                <?php $currencyrow = $content->getCountrylist();?>
                <option value="">please select country</option>
                <?php foreach ($currencyrow as $prow):?>
                <?php $sel = ($row['id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['country_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _S_TITLE;?>:<?php echo required();?></th>
            <td><select name="state_id" class="custombox" style="width:300px">
                <?php $staterow = $content->getstatelist();?> 
                <option value="">select your state</option>
                <?php foreach ($staterow as $srow):?>               
                <option value="<?php echo $srow['id'];?>"><?php echo $srow['state_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CI_NAME;?>:<?php echo required();?></th>
            <td><select name="city_id" class="custombox" style="width:300px">
                <?php $cityrow = $content->getCitylist();?> 
                <option value="">select your City</option>
                <?php foreach ($cityrow as $srow):?>               
                <option value="<?php echo $srow['id'];?>"><?php echo $srow['city_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CMM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CSM_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"></textarea>
              </td>
          </tr>
           <tr>
            <th><?php echo _CMM_FIR;?>: <?php echo required();?></th>
            <td><input name="first_name" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_LST;?>: <?php echo required();?></th>
            <td><input name="last_name" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_EML;?>: <?php echo required();?></th>
            <td><input name="email_id" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CMM_PASS;?>: <?php echo required();?></th>
            <td><input name="password" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CMM_PHONE;?>: <?php echo required();?></th>
            <td><input name="phone_number" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
           <tr>
            <th><?php echo _CM_ZIP;?>: <?php echo required();?></th>
            <td><input name="zipcode" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _DMM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _DSM_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CM_LATI;?>: <?php echo required();?></th>
            <td><input name="latitude" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_LONG;?>: <?php echo required();?></th>
            <td><input name="longitude" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_WEB;?>: <?php echo required();?></th>
            <td><input name="website" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _CM_APP;?>: </th>
            <td><input name="application_id" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CM_SP;?>: <?php echo required();?></th>
            <td><input name="specialist" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
         <tr>
            <th><?php echo _CM_LOGO;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="logo" />
              </div></td>
          </tr>
           <tr>
            <th><?php echo _CM_PUB;?>:<?php echo required();?></th>
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
<?php echo $core->doForm("processcompany","controller.php");?>
<?php break;?>
<?php default: ?>
<?php if(isset($_GET['active']) && !empty($_GET['active'])):
	$data['active'] = "1";
	 $db->update("res_customer_master", $data, "id='" . $_GET['active']  . "'");	
endif;
if(isset($_GET['deactive']) && !empty($_GET['deactive'])):
	$data['active'] = "0";
	 $db->update("res_customer_master", $data, "id='" . $_GET['deactive']  . "'");	
endif;
?>
<?php
$search = (isset($_POST['search'])) ? ($_POST['search']) : false;
$customerrow = $content->getCustomer($search);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CMM_MANAGER;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CustomerINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CMM_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">   
    <div class="utility">
      <table class="display forms">
      <tr style="background-color:transparent">
      <td><form action="" method="post">
          <input name="search" type="text" class="inputbox" id="search-input" size="30" placeholder="Search By Name"/>
          <input name="submit" type="submit" class="button-blue" value="<?php echo _TR_FIND;?>" />
        </form></td>
       <td> <form action="" method="post">
              <select name="location_id" id="ddlViewBy" class="custombox" style="width:220px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select  Location Name</option>
                <?php foreach ($locationrow as $prow):
                if(isset($_POST['location_id'])){ $sortslect = $_POST['location_id'];  }
				  else 
				  {
					 $sortslect=''; 
				  }?>
                <option value="<?php echo $prow['id'];?>" <?php if($sortslect == $prow['id']){ echo "selected"; }?>><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select>
              <input name="location_search" type="submit" class="button-blue" value="Status Search" />
            </form></td>
      <td align="right" ><form action="" method="get" name="filter_browse" id="filter_browse">
          <strong><?php echo _TR_STATUS_FILTER;?>:</strong>&nbsp;&nbsp;
          <div class="mybox">
          <select name="select" class="custombox" onchange="if(this.value!='NA') window.location = 'index.php?do=customer_master&amp;sort='+this[this.selectedIndex].value; else window.location = 'index.php?do=customer_master';" style="width:220px">
            <option value="NA"><?php echo _TR_RESET_FILTER;?></option>
            <?php echo $content->getCustomerFilter();?>
          </select>
          </div>
        </form></td>
    </tr>    
        <tr>
        <td colspan="2"><form action="#" method="post">
              <strong> <?php echo _UR_SHOW_FROM;?></strong>
              <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="12" id="fromdate" />
              <strong> <?php echo _UR_SHOW_TO;?></strong>
              <input name="enddate" type="text" class="inputbox-sml" size="12" id="enddate" />
              <input name="find" type="submit" class="button-blue" value="<?php echo _UR_FIND;?>" />
            </form></td>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>    
    
    <form action="" method="post" id="admin_form" name="admin_form">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>          
          <th class="left sortable"><?php echo _USERNAME;?></th>
          <th class="left sortable"><?php echo _UR_NAME;?></th>
          <th class="sortable"><?php echo _UR_STATUS;?></th>
          <th>View</th>         
          <th>Action</th>
          <td class="center"><input type="checkbox" name="masterCheckbox" id="masterCheckbox" class="checkbox"/></td>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="7"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$customerrow):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(_PO_NOPOST,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($customerrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>          
          <td><?php echo $row['email_id'];?></td>          
          <td><?php echo $row['name'] ;?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="center"><a href="javascript:void(0);" class="view-customer" data-info="<?php echo $row['name'];?>" id="cust_<?php echo $row['id'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo _CUSVIEW_;?>"/></a></td>
          <?php /*?><td class="center"><a href="index.php?do=customer_master&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _CMM_EDIT;?>"/></a></td><?php */?>
         <td class="center">
           <a href="index.php?do=customer_master&amp;active=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Active</a> |
           <a href="index.php?do=customer_master&amp;deactive=<?php echo $row['id'];?><?php if(isset($_GET['pg'])){ echo "&amp;pg=" . $_GET['pg']; }?>">Deactive</a> 
          </td>
           <td class="center"><input name="comid[]" type="checkbox" class="checkbox" id="status<?php echo $row['id'];?>" value="<?php echo $row['id'];?>" /></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
        <tr>
    <td colspan="7"><div style="float:right">
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
<?php echo Core::doDelete(_DELETE.' '._CMM_MANAGER, "deletecompany");?>
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
	// View Customer Details 
    $('a.view-customer').click(function() {
        var id = $(this).attr('id').replace('cust_', '')
        var title = $(this).attr('data-info');
		  $.ajax({
			  type: 'post',
			  url: "ajax.php",
			  data: 'viewCustomer=' + id + '&name=' + title,
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
	
	 $('.doform').click(function () {
        var action = $(this).attr('id');
        var str = $("#admin_form").serialize();
        str += '&comproccess=1';
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

$(function () {
	$('#masterCheckbox').click(function(e) {
		$(this).parent().toggleClass("ez-checked");
		$('input[name^="comid"]').each(function() {
			($(this).is(':checked')) ? $(this).removeAttr('checked') : $(this).attr({"checked":"checked"});
			 $(this).trigger('change');
		});
		return false;
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