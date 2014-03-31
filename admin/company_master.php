<?php
  /**
   * Company Manager
 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Posts")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php //include("help/posts.php");?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_company_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CM_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CM_SUBTITLE1 . $row['company_name'];?></h2>
  </div>
  <div class="block-content">
   <span id="avatar">
    <?php if ($row['logo']):?>
    <img src="<?php echo UPLOADURL;?>/avatars/<?php echo $row['logo'];?>" alt="<?php echo $row['logo'];?>" class="avatar"/>
    <?php else:?>
    <img src="<?php echo UPLOADURL;?>/avatars/blank.png" alt="<?php echo $row['logo'];?>" class="avatar"/>
    <?php endif;?>
    </span>
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CM_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=company_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _CM_NAME;?>: <?php echo required();?></th>
            <td><input name="company_name" type="text" class="inputbox" value="<?php echo $row['company_name'];?>" size="55" /></td>
          </tr>
           <tr>
            <th><?php echo _COUNTRY_TITLE;?>:</th>
            <td><select name="country_id" class="custombox" style="width:300px">
                <?php $currencyrow = $content->getCountrylist();?>
                <?php foreach ($currencyrow as $prow):?>
                <?php $sel = ($row['country_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['country_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _S_TITLE;?>:</th>
            <td><select name="state_id" class="custombox" style="width:300px">
                <?php $staterow = $content->getstatelist();?> 
                <option value="">select your state</option>
                <?php foreach ($staterow as $srow):?>   
                 <?php $sel = ($row['state_id'] == $srow['id']) ? ' selected="selected"' : '' ;?>            
                <option value="<?php echo $srow['id'];?>"<?php echo $sel;?>><?php echo $srow['state_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _CI_NAME;?>:</th>
            <td><select name="city_id" class="custombox" style="width:300px">
                <?php $cityrow=$content->getCitylist();?> 
                <option value="">select your City</option>
                <?php foreach ($cityrow as $crow):?>       
                 <?php $sel = ($row['city_id'] == $crow['id']) ? ' selected="selected"' : '' ;?>            
                <option value="<?php echo $crow['id'];?>"<?php echo $sel;?>><?php echo $crow['city_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
        
         <tr>
            <th><?php echo _CI_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr> 
            <tr>
            <th><?php echo _CM_GA;?>:</th>
            <td><textarea name="address1" cols="50" rows="6" ><?php echo $row['address1'];?></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CS_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"><?php echo $row['address2'];?></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CM_timezone;?>:</th>
            <td><select name="timezone" class="custombox" style="width:300px">
                <?php $zonerow = $content->getTimezonelist();?> 
                <option value="">select your TimeZone</option>
                <?php foreach ($zonerow as $zrow):?>   
                 <?php $sel = ($row['timezone'] == $zrow['id']) ? ' selected="selected"' : '' ;?>             
                <option value="<?php echo $zrow['id'];?>"<?php echo $sel;?>><?php echo $zrow['zone'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
           <tr>
            <th><?php echo _CM_PHONE;?>: <?php echo required();?></th>
            <td><input name="phone_number" type="text" class="inputbox" value="<?php echo $row['phone_number'];?>"   size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _CM_PHONE1;?>: </th>
            <td><input name="phone_number1" type="text" class="inputbox" value="<?php echo $row['phone_number1'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _CM_FAX;?>:</th>
            <td><input name="fax_number" type="text" class="inputbox" value="<?php echo $row['fax_number'];?>"  size="55" title=""/></td>
          </tr>
           <tr>
            <th><?php echo _CM_ZIP;?>: <?php echo required();?></th>
            <td><input name="zipcode" type="text" class="inputbox" value="<?php echo $row['zipcode'];?>"  size="55" title=""/></td>
          </tr>         
          <tr>
            <th><?php echo _CM_WEB;?>: <?php echo required();?></th>
            <td><input name="website" type="text" class="inputbox" value="<?php echo $row['website'];?>"   size="55" title=""/></td>
          </tr>
          <tr>
            <th><?php echo _CM_APP;?>: <?php echo required();?></th>
            <td><input name="application_id" type="text" class="inputbox" value="<?php echo $row['application_id'];?>"  size="55" title=""/></td>
          </tr>
          
           <tr>
            <th>Specialty: <?php echo required();?></th>
            <td><input name="specialist" type="text" class="inputbox" value="<?php echo $row['specialist'];?>"  size="55" title=""/></td>
          </tr>
         <tr>
            <th><?php echo _CM_LOGO;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="logo" />
              </div></td>
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
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CM_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _CM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _CM_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=company_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _CM_NAME;?>: <?php echo required();?></th>
            <td><input name="company_name" type="text" class="inputbox"  size="55" title="<?php echo $row['company_name'];?>"/></td>
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
            <th><?php echo _CM_timezone;?>:</th>
            <td><select name="timezone" class="custombox" style="width:300px">
                <?php $zonerow = $content->getTimezonelist();?> 
                <option value="">select your TimeZone</option>
                <?php foreach ($zonerow as $zrow):?>               
                <option value="<?php echo $zrow['id'];?>"><?php echo $zrow['zone'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
         
          <tr>
            <th><?php echo _CM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" cols="50" rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _CS_GA;?>:</th>
            <td><textarea name="address2" cols="50" rows="6"></textarea>
              </td>
          </tr>
          
           <tr>
            <th><?php echo _CM_PHONE;?>: <?php echo required();?></th>
            <td><input name="phone_number" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CM_PHONE1;?>: </th>
            <td><input name="phone_number1" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CM_FAX;?>: </th>
            <td><input name="fax_number" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _CM_ZIP;?>: <?php echo required();?></th>
            <td><input name="zipcode" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
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
            <th>Specialty: <?php echo required();?></th>
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
<?php $companyrow = $content->getCompany();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _CM_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _CompanyINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=company_master&amp;action=add" class="button-sml"><?php echo _CM_ADD;?></a></span><?php echo _CM_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _CM_NAME;?></th>
          <th class="left sortable"><?php echo _COUNTRY_TITLE;?></th>
          <th class="left sortable"><?php echo _S_TITLE;?></th>
          <th class="left sortable"><?php echo _CI_NAME;?></th>
          <th class="left sortable"><?php echo _CM_GA;?></th>
           
           <th class="left sortable"><?php echo _CM_PHONE;?></th>
         <th class="left sortable"><?php echo _CM_WEB;?></th>
           <th class="left sortable"><?php echo _CM_LOGO;?></th>
          <th><?php echo _PUBLISHED;?></th>
          <th><?php echo _CM_EDIT;?></th>
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
        <?php if(!$companyrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_PO_NOPOST,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($companyrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['company_name'];?></td>          
          <td><?php echo $row['country_name'] ;?></td>
          <td><?php echo $row['state_name'] ;?></td>
          <td><?php echo $row['city_name'] ;?></td>
          <td><?php echo $row['address1'] ;?></td>
       
          <td><?php echo $row['phone_number'] ;?></td>
          <td><?php echo $row['website'] ;?></td>
            <th class="left"><img src="<?php echo UPLOADURL;?>/avatars/<?php echo $row['logo'];?>"></th>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="center"><a href="index.php?do=company_master&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _CM_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['company_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._CM_MANAGER, "deletecompany");?>
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