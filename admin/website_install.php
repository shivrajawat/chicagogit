<?php
  /**
   * Posts Manager
   *
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Webinstall")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_install_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _INS_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _INS_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _INS_SUBTITLE1 . $row['website_url'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _INS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=website_install" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _INS_WEBSITe;?>: <?php echo required();?></th>
            <td><input name="website_url" type="text" class="inputbox tooltip"  size="55" value="<?php echo $row['website_url']; ?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LOCATION;?>:</th>
            <td>
            	<select name="location_id[]" multiple="multiple"  class="select" style="width:250px" size="10" id="locationid">
                	<?php $locationrow = $content->getlocationlist($userlocationid);?>
                	<option value="">Please Select Location Name</option>
                	<?php foreach ($locationrow as $prow):?>
                	<?php  $location_array = explode(",", $row['location_id']);
				       		$sel = (in_array($prow['id'],$location_array) == $location_array) ? ' selected="selected"' : ''; ?>
                	<option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                	<?php endforeach;?>
              	</select>
            </td>
          </tr>          
          <tr>
            <th><?php echo _INS_FLOW;?>:</th>
            <td>
            	<span class="input-out">
                  <label for="by_location"><?php echo _INS_BYLOCATION;?></label>
                  <input name="flow" type="radio" value="1" <?php getChecked($row['flow'], 1); ?> id="by_location" />
                  <label for="by_default"><?php echo _INS_BYMENU;?></label>
                  <input name="flow" type="radio"  value="2"  id="by_default" <?php getChecked($row['flow'], 2); ?>/>
                  <label for="active-2">Ecommerce</label>
              <input name="flow" type="radio"  value="3"  id="by_default" <?php getChecked($row['flow'], 3); ?>/>
              </span></td>
              	</span>
            </td>
          </tr>
           <tr id="bylocation" style="display:none;">
            <th><?php echo _INS_BYHYBRID;?>:</th>
            <td>
            	<input type="checkbox" value="1" name="hybrid" <?php getChecked($row['hybrid'], 1); ?>>
                <label for="">By Hybird</label>
            </td>
          </tr>
          <tr>
            <th>Mode:</th>
            <td>
            	<input type="checkbox" value="1" name="test_mode" id="test_mode" <?php getChecked($row['test_mode'], 1); ?>>
                <label for="test_mode">Test Mode</label>
            </td>
          </tr>
          <tr id="default_loc">
            <th><?php echo _INS_DEFAULT;?>:</th>
            <td>
            	<span class="input-out">
                    <select name="default"  class="selectboxnew" style="width:300px" id="myselect">
                    <!--<select name="default"  class="selectboxnew" style="width:300px" id="myselect">-->
                        <?php $locWebrow = $content->getWebInstalllocation($row['location_id']);?>
                        <?php foreach ($locWebrow as $wrow):?>
                        <?php 
					    /***************commented on 10th january, 2014, starts here******************************************** 
								$location_array = explode(",", $wrow['location_id']);
                                $sel = (in_array($wrow['id'],$location_array) == $location_array) ? 'selected="selected"' : '';
						***************commented on 10th january, 2014, ends here***********************************************/
					    ?>                               
                        <?php 						
						$default_loc = $row['default_location'];						
						$locs_id_arr = explode(',',$row['location_id']);
						$sel = (in_array($default_loc,$locs_id_arr)) ? 'selected="selected"' : '';
						?>
                        <option value="<?php echo $wrow['id'];?>" <?php echo $sel;?>><?php echo $wrow['location_name'];?></option>
                        <?php endforeach;?>
            		</select>
             	</span>
            </td>
          </tr>
          <tr>
            <th>Order Type</th>
            <td><table>
                <tr>
                  <td><input type="checkbox" value="1"   name="pick_up" <?php getChecked($row['pick_up'], 1); ?>>
                    <label for="">Pick Up</label></td>
                  <td><input type="checkbox"  value="1"  name="delivery" <?php getChecked($row['delivery'], 1); ?>>
                    <label for="">Delivery</label></td>
                  <td><input type="checkbox"  value="1"   name="dineln" <?php getChecked($row['dineln'], 1); ?>>
                    <label for="">Dine In</label></td>                  
                </tr>
              </table></td>
          </tr>  
          <tr>
            <th>Latitude: </th>
            <td><input name="latitude" type="text" class="inputbox"  size="55" value="<?php echo $row['latitude'];?>"/></td>
          </tr>
          <tr>
            <th>Longitude: </th>
            <td><input name="longitude" type="text" class="inputbox"  size="55" value="<?php echo $row['longitude'];?>"/></td>
          </tr>
          <tr>
            <th>Zoom Level: </th>
             <td>
             <input name="zoom_level" type="text" value="<?php echo ($row['zoom_level']) ? $row['zoom_level'] : ""; ?>" class="inputbox"  size="55" title="<?php echo "Enter zoom level";?>"/></td>
          </tr>
          <tr>
            <th>Restorant Timing: </th>
             <td class="editor">
             <textarea name="hours_notes" id="hours_notes" rows="7" cols="52"><?php echo $row['hours_notes'];?></textarea>
             <?php //loadEditor("hours_details"); ?>
             <?php loadEditor("hours_notes", "100%", "200" ,"hours_notes"); ?>
            </td>
          </tr>
          <tr><th>Notes:</th>
            <td colspan="1" class="editor"><textarea id="notes" name="notes" rows="4" cols="30"><?php echo $core->out_url($row['notes']);?></textarea>
              <?php loadEditor("notes"); ?></td>
          </tr>
          <tr>
            <th>Show e-Club:</th>
            <td><span class="input-out">
              <label for="show_e_club-1"><?php echo _YES;?></label>
              <input type="radio" name="show_e_club" id="show_e_club-1" value="1"   <?php getChecked($row['show_e_club'], 1); ?>/>
              <label for="show_e_club-2"><?php echo _NO;?></label>
              <input type="radio" name="show_e_club" id="show_e_club-2" value="0"  <?php getChecked($row['show_e_club'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th>Show Delivery Address:</th>
            <td><span class="input-out">
              <label for="show_deliveryaddress-1"><?php echo _YES;?></label>
              <input type="radio" name="show_deliveryaddress" id="show_deliveryaddress-1" value="1" <?php getChecked($row['show_deliveryaddress'], 1); ?> />
              <label for="show_deliveryaddress-2"><?php echo _NO;?></label>
              <input type="radio" name="show_deliveryaddress" id="show_deliveryaddress-2" value="0" <?php getChecked($row['show_deliveryaddress'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _INS_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?>/>
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?>/>
              </span></td>
          </tr>        
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<script type="text/javascript">
      $(document).ready(function() {	 
	  <?php if($row['flow']=='2') {?> $("#default_loc").show(); <?php  }  ?>
	  <?php if($row['flow']=='1') {?> $("#bylocation").show(); <?php  }  ?>
	  	
});
 </script> 
<?php echo $core->doForm("processWebsiteInstall","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _INS_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _INS_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _INS_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _INS_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=website_install" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _INS_WEBSITe;?>: <?php echo required();?></th>
            <td><input name="website_url" type="text" class="inputbox tooltip"  size="55" title="<?php echo _INS_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LOCATION;?>:</th>
            <td><select name="location_id[]" multiple="multiple"  class="select" style="width:250px" size="10" id="locationid">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Location Name</option>
                <?php foreach ($locationrow as $prow):?>
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>          
          <tr>
            <th><?php echo _INS_FLOW;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _INS_BYLOCATION;?></label>
              <input name="flow" type="radio" value="1" checked="checked" id="by_location"/>
              <label for="active-2"><?php echo _INS_BYMENU;?></label>
              <input name="flow" type="radio"  value="2"  id="by_default"/>
              <label for="active-2">Ecommerce</label>
              <input name="flow" type="radio"  value="3"  id="ecomerce" />
              </span></td>
          </tr>
          <tr id="bylocation">
            <th><?php echo _INS_BYHYBRID;?>:</th>
            <td><input type="checkbox" value="1"   name="hybrid" >
                    <label for="">By Hybird</label></td>
          </tr>
          <tr>
            <th>Mode:</th>
            <td>
            	<input type="checkbox" value="1" name="test_mode" id="test_mode">
                <label for="test_mode">Test Mode</label>
            </td>
          </tr>
          <tr id="default_loc">
            <th><?php echo _INS_DEFAULT;?>:</th>
            <td><span class="input-out">
            <select name="default"  class="selectboxnew" style="width:300px" id="myselect"></select>
              </span></td>
          </tr>  
          <tr>
            <th>Order Type</th>
            <td><table>
                <tr>
                  <td><input type="checkbox" value="1"   name="pick_up" id="chkorder1">
                    <label for="">Pick Up</label></td>
                  <td><input type="checkbox"  value="1"  name="delivery" id="chkorder2">
                    <label for="">Delivery</label></td>
                  <td><input type="checkbox"  value="1"   name="dineln" id="chkorder3">
                    <label for="">Dine In</label></td>                  
                </tr>
              </table></td>
          </tr>
          <tr>
            <th>Latitude: </th>
            <td><input name="latitude" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th>Longitude: </th>
            <td><input name="longitude" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th>Zoom Level: </th>
             <td><input name="zoom_level" type="text" class="inputbox"  size="55" title="<?php echo "Enter zoom level";?>"/></td>
          </tr>
          <tr>
            <th>Restorant Timing: </th>
             <td class="editor">
             <textarea name="hours_notes" id="hours_notes" rows="7" cols="52"></textarea>
             <?php //loadEditor("hours_details"); ?>
             <?php loadEditor("hours_notes", "100%", "200" ,"hours_notes"); ?>
            </td>
          </tr>
          <tr><th>Notes:</th>
            <td colspan="1" class="editor"><textarea id="notes" name="notes" rows="4" cols="30"></textarea>
              <?php loadEditor("notes"); ?></td>
          </tr>
          <tr>
            <th>Show e-Club:</th>
            <td><span class="input-out">
              <label for="show_e_club-1"><?php echo _YES;?></label>
              <input type="radio" name="show_e_club" id="show_e_club-1" value="1"  />
              <label for="show_e_club-2"><?php echo _NO;?></label>
              <input type="radio" name="show_e_club" id="show_e_club-2" value="0" checked="checked" />
              </span></td>
          </tr>
          <tr>
            <th>Show Delivery Address:</th>
            <td><span class="input-out">
              <label for="show_deliveryaddress-1"><?php echo _YES;?></label>
              <input type="radio" name="show_deliveryaddress" id="show_deliveryaddress-1" value="1"  />
              <label for="show_deliveryaddress-2"><?php echo _NO;?></label>
              <input type="radio" name="show_deliveryaddress" id="show_deliveryaddress-2" value="0" checked="checked" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _INS_PUB;?>:</th>
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
<?php echo $core->doForm("processWebsiteInstall","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $webinstallrow = $content->getWebInstall();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _INS_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _INSINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=website_install&amp;action=add" class="button-sml"><?php echo _INS_ADD;?></a></span><?php echo _INS_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _INS_WEBSITe;?></th>
          <th class="left sortable"><?php echo _INS_FLOW;?></th>
          <th><?php echo _PUBLISHED;?></th>          
          <th>View</th> 
          <th><?php echo _INS_EDIT;?></th>
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
        <?php if(!$webinstallrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_INS_NOWEBINSTALL,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($webinstallrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['website_url'];?></td>
          <td><?php if($row['flow']==1){ echo "By Location"; }else if($row['flow']==2){echo "By Menu"; } else{ echo "Ecommerce"; }?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="center"><a href="javascript:void(0);" class="view-webinstall" data-info="<?php echo $row['website_url'];?>" id="install_<?php echo $row['id'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo _CUSVIEW_;?>"/></a></td>
          <td class="center"><a href="index.php?do=website_install&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _INS_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['website_url'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._POST, "deleteWebInstall");?>
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

// View Customer Details 
    $('a.view-webinstall').click(function() {
        var id = $(this).attr('id').replace('install_', '')
        var title = $(this).attr('data-info');
		  $.ajax({
			  type: 'post',
			  url: "ajax.php",
			  data: 'viewWebInstall=' + id + '&name=' + title,
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
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
<script type="text/javascript">
// folw radio button click chage div of location hide show 
$("input[name$='flow']").click(function() {
        var test = $(this).val();
		
		//alert(test); 	
		if (test==1) {	
			$('#default_loc').show();
			$('#bylocation').show();
			//$('#default_loc').hide();	//modidication made on 10th jan, 2014
    	}	
		else if (test==2) {	
		
			$('#default_loc').show();	
			$('#bylocation').hide();	
    	}		
	else {		
			$('#bylocation').hide();
			$('#default_loc').hide();
    	}
    });
	// onchange location event call and apend option in select box default location 
	$('#locationid').on('change', function() {		
	  
		$("#myselect").empty();
		var $options = $("#locationid > option:selected").clone();
		$('#myselect').append($options);
	});	

</script>