<?php
  /**
   * Location manager  
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');	  
  if(!$user->getAcl("Location")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php  $userlocationid = $user->getlocationIdByData(); ?>
<script type="text/javascript" src="js/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="js/highslide.css" />
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("res_location_master", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LM_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LM_SUBTITLE1 . $row['location_slogan'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LM_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
        <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Location Details</h2></td>
        </tr>
        <tr>
            <th><?php echo _LM_LON;?>: <?php echo required();?></th>
            <td><input name="location_name" id="location_name" type="text" class="inputbox"  size="55" value="<?php echo $row['location_name'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_RES;?>: <?php echo required();?></th>
            <td><input name="restaurant_name" id="restaurant_name" type="text" class="inputbox"  size="55" value="<?php echo $row['restaurant_name'];?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_NAME;?>:<?php echo required();?></th>
            <td><select name="company_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $companyrow = $content->getcompanylist();?>
                <option value="">please select company Name</option>
                <?php foreach ($companyrow as $prow):?>     
                 <?php $sel = ($row['company_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>           
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['company_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>  
         <tr>
            <th>Address Same As Default Company Address:</th>
            <td><span class="input-out">
              <input name="is_same_company_address" id="is_same_company_address" type="checkbox" value="1" class="checkbox" <?php if($row['is_same_company_address']=='1') {?> checked="checked" <?php } ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _LM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" id="address1" cols="50"  rows="6"><?php echo $row['address1'];?></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _LS_GA;?>:</th>
            <td><textarea name="address2" id="address2" cols="50" rows="6"><?php echo $row['address2'];?></textarea>
              </td>
          </tr>
          
           <tr>
            <th><?php echo _LM_PHONE;?>: <?php echo required();?></th>
            <td><input name="phone_number" id="phone_number" type="text" class="inputbox"  size="55" value="<?php echo $row['phone_number'];?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_PHONE1;?>: </th>
            <td><input name="phone_number1" id="phone_number1" type="text" class="inputbox"  size="55" value="<?php echo ($row['phone_number1']) ? $row['phone_number1'] : "";?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_FAX;?>: </th>
            <td><input name="fax_number" id="fax_number" type="text" class="inputbox"  size="55" value="<?php echo $row['fax_number'];?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_ZIP;?>: <?php echo required();?></th>
            <td><input name="zipcode" type="text" class="inputbox"  size="55" value="<?php echo $row['zipcode'];?>"/></td>
          </tr>
          <tr>
            <th>Country:<?php echo required();?></th>
            <td><select name="country_id" id="country_id" class="custombox" style="width:300px">
                <?php $currencyrow = $content->getCountrylist();?>
                <option value="">please select country </option>
                <?php foreach ($currencyrow as $crow):?>       
                <?php $sel = ($row['country_id'] == $crow['id']) ? ' selected="selected"' : '' ;?>          
                <option  value="<?php echo $crow['id'];?>"<?php echo $sel;?>><?php echo $crow['country_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>State:<?php echo required();?></th>
            <td><select name="state_id" id="state_id" class="custombox" style="width:300px">
                <?php $staterow = $content->getstatelist();?> 
                <option value="">select your state</option>
                <?php foreach ($staterow as $srow):?>        
                 <?php $sel = ($row['state_id'] == $srow['id']) ? ' selected="selected"' : '' ;?>            
                <option value="<?php echo $srow['id'];?>"<?php echo $sel; ?>><?php echo $srow['state_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>City:<?php echo required();?></th>
            <td><select name="city_id" id="city_id" class="custombox" style="width:300px">
                <?php $cityrow = $content->getCitylist();?> 
                <option value="">select your City</option>
                <?php foreach ($cityrow as $cirow):?>
                <?php $sel = ($row['city_id'] == $cirow['id']) ? ' selected="selected"' : '' ;?>               
                <option value="<?php echo $cirow['id'];?>"<?php echo $sel;?>><?php echo $cirow['city_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>    
          <tr>
            <th><?php echo _LM_WEB;?>: </th>
            <td><input name="website" type="text" class="inputbox"  size="55" value="<?php echo $row['website'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_LS;?>:</th>
            <td><input name="location_slogan" type="text" class="inputbox"  size="55" value="<?php echo $row['location_slogan'];?>"/></td>
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
             <td><input name="zoom_level" type="text" value="<?php echo ($row['zoom_level']) ? $row['zoom_level'] : ""; ?>" class="inputbox"  size="55" title="<?php echo "Enter zoom level";?>"/></td>
          </tr>
          <tr>
            <th>Restorant Timing: </th>
             <td class="editor">
             <textarea name="restorant_time" id="bodycontent" rows="7" cols="52"><?php echo $row['restorant_time'];?></textarea>
             <?php loadEditor("bodycontent"); ?>
            </td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Types</h2></td>
          </tr>
           <tr>
            <th><?php echo _LM_PICK_AVL;?>:</th>
            <td><input name="pick_up_available" type="checkbox" id="active-1" value="1" <?php if($row['pick_up_available']=='1') {?> checked="checked" <?php } ?>  /> 
            <label>Pickup time</label>   <input name="pickup_time" type="text" class="inputbox"  size="10" value="<?php echo $row['pickup_time'];?>"/> &nbsp;(min)</td>
          </tr>
           <tr>
            <th>Delivery Available:</th>
            <td><input name="delivery_available" type="checkbox" id="active-2" value="1" <?php if($row['delivery_available']=='1') {?> checked="checked" <?php } ?>  />
             <label>Delivery-time</label>   <input name="delivery_time" type="text" class="inputbox"  size="10" value="<?php echo $row['delivery_time'];?>"/>&nbsp;(min) </td>
          </tr>
           <tr>
            <th>Dine-in Available:</th>
            <td><input name="dinein_available" type="checkbox" id="active-3" value="1"  <?php if($row['dinein_available']=='1') {?> checked="checked" <?php } ?> /></td>
          </tr> 
          <tr>
            <th>Delivery Fee: </th>
            <td><input name="delivery_fee" type="text" class="inputbox"  size="55" value="<?php echo $row['delivery_fee'];?>"/>  </td>
          </tr>
          
          <tr>
            <th>Additional Fee: </th>
            <td><input name="additional_fee" type="text" class="inputbox"  size="55" value="<?php echo $row['additional_fee'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_GT;?>: </th>
            <td><input name="gratuity" type="text" class="inputbox"  size="55" value="<?php echo $row['gratuity'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_ST;?>: </th>
            <td><input name="sales_tax" type="text" class="inputbox"  size="55" value="<?php echo $row['sales_tax'];?>"/>(%)</td>
          </tr>
          <tr>
            <th>Maximum Advance Order:</th>
            <td><input name="max_advance_order" type="text" class="inputbox"  size="55" value="<?php echo $row['max_advance_order'];?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_STI;?>:</th>
            <td><input name="sales_tax_id" type="text" class="inputbox"  size="55" value="<?php echo $row['sales_tax_id'];?>"/></td>
          </tr>
          
          <tr>
            <th><?php echo _LM_EDC;?>: </th>
            <td><input name="emai_disclaimer" type="text" class="inputbox"  size="55" value="<?php echo $row['emai_disclaimer'];?>"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">POS Details</h2></td>
          </tr>
          <tr>
            <th>Site Id </th>
            <td><input name="site_id" type="text" class="inputbox"  size="55" value="<?php echo $row['site_id'];?>"></td>
          </tr>
          <tr>
            <th>Pos Password</th>
            <td><input name="pos_password" type="text" class="inputbox"  size="55" value="<?php echo $row['pos_password'];?>"/></td>
          </tr>
          <tr>
            <th>Pos IP</th>
            <td><input name="pos_ip" type="text" class="inputbox"  size="55" value="<?php echo $row['pos_ip'];?>"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Payment Gateway Settings </h2></td>
          </tr>
            <tr>
            <th>Choose Payment Method :</th>
            <td><span class="input-out">
              <label>Cash On Delivery</label>
              <input name="is_cash_on_delivery" type="checkbox"  value="1"  <?php if($row['is_cash_on_delivery']=='1') {?> checked="checked" <?php } ?>  />
              <label>Paypal</label>
              <input name="is_paypal" onclick="Change();" id="is_paypal" type="checkbox"  value="1" <?php if($row['is_paypal']=='1') {?> checked="checked" <?php } ?> />
              <label>Authorize</label>
              <input name="is_authorize" onclick="Change();" id="is_authorize" type="checkbox"  value="1" <?php if($row['is_authorize']=='1') {?> checked="checked" <?php } ?> />
              <label>First Data</label>
              <input name="is_first_data" onclick="Change();" id="is_first_data" type="checkbox"  value="1" <?php if($row['is_first_data']=='1') {?> checked="checked" <?php } ?>/>
              <label>Mercury</label>
              <input name="is_mercury" onclick="Change();" id="is_mercury" type="checkbox" value="1" <?php if($row['is_mercury']=='1') {?> checked="checked" <?php } ?>/>
              <label>Virtual Merchant</label>
              <input name="is_vm" onclick="Change();" id="is_vm" type="checkbox" value="1" <?php if($row['is_vm']=='1') {?> checked="checked" <?php } ?>/>
              <label>Internet Secure</label>
              <input name="is_internet_secure" onclick="Change();" id="is_internet_secure" type="checkbox" value="1" <?php if($row['is_internet_secure']=='1') {?> checked="checked" <?php } ?>/>
              </span></td>
          </tr> 
          <?php if($row['is_paypal']=='0') { $paypl = 'style="display:none;"';  } else {  $paypl = "";} ?>        
          <tr class="paypal"  <?php echo $paypl;?>>            
            <td colspan="2"><h2 style="color:#FF0000">Paypal Settings </h2></td>
          </tr> 
                 
          <tr class="paypal"  <?php echo $paypl;?>>
            <th>Paypal Email Id</th>
            <td><input name="paypal_email_id" type="text" class="inputbox"  size="55" value="<?php echo $row['paypal_email_id'];?>"/></td>
          </tr>
           <tr class="paypal"  <?php echo $paypl;?>>
            <th>Paypal Password</th>
            <td><input name="paypal_password" type="text" class="inputbox"  size="55" value="<?php echo $row['paypal_password'];?>"/></td>
          </tr>
           <tr class="paypal"  <?php echo $paypl;?>>
            <th>Paypal Signature</th>
            <td><input name="paypal_signature" type="text" class="inputbox"  size="100" value="<?php echo $row['paypal_signature'];?>"/></td>
          </tr>
          
          <!--Start Authorize.net Settings Hide show-->
          <?php if($row['is_authorize']=='0') { $autho = 'style="display:none;"';  } else {  $autho = "";} ?>
          <tr class="authorize" <?php echo $autho;?>>            
            <td colspan="2"><h2 style="color:#FF0000">Authorize.net Settings </h2></td>
          </tr>
          <tr class="authorize" <?php echo $autho;?>>
            <th>Authorize Api ID</th>
            <td><input name="authorizr_api_id" type="text" class="inputbox"  size="55" value="<?php echo $row['authorizr_api_id'];?>"/></td>
          </tr>
          <tr class="authorize" <?php echo $autho;?>>
            <th>Authorize Transaction Key</th>
            <td><input name="authorizze_trans_key" type="text" class="inputbox"  size="55" value="<?php echo $row['authorizze_trans_key'];?>"/></td>
          </tr>
          <tr class="authorize" <?php echo $autho;?>>
            <th>Authorize Hash Key</th>
            <td><input name="authorize_hash_key" type="text" class="inputbox"  size="55" value="<?php echo $row['authorize_hash_key'];?>"/></td>
          </tr>
       
          <!--Start First Data Settings Hide show-->
          <?php if($row['is_first_data']=='0') { $firstdata = 'style="display:none;"';  } else {  $firstdata = "";} ?>
          <tr class="first_data" <?php echo $firstdata;?>>            
            <td colspan="2"><h2 style="color:#FF0000">First Data Settings </h2></td>
          </tr>
          <tr class="first_data"  <?php echo $firstdata;?>> 
            <th>First Data File Name</th>
            <td>
            <div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="logo" />
              </div>
            <!--<input name="first_data_file_name" type="text" class="inputbox"  size="55" value="<?php //echo $row['first_data_file_name'];?>"/>--></td>
          </tr>
         <!---->
         <!--Start Mercury Settings hide show-->
         <?php if($row['is_mercury']=='0') { $mercury='style="display:none;"'; } else {  $mercury="";} ?>
          <tr class="mercury" <?php echo $mercury;?>>            
            <td colspan="2"><h2 style="color:#FF0000">Mercury Settings </h2></td>
          </tr>
          <tr class="mercury" <?php echo $mercury;?>>
            <th>Merchant Id </th>
            <td><input name="merchant_id" type="text" class="inputbox"  size="55" value="<?php echo $row['merchant_id'];?>"/></td>
          </tr>
          <tr class="mercury" <?php echo $mercury;?>>
            <th>Merchant Password</th>
            <td><input name="merchant_password" type="text" class="inputbox"  size="55" value="<?php echo $row['merchant_password'];?>"/></td>
          </tr>
          
          <!--Start virtual payment Settings hide show, starts here-->
         <?php if($row['is_vm']=='0') { $virtualStyle='style="display:none;"'; } else {  $virtualStyle="";} ?>
          <tr class="virtual" <?php echo $virtualStyle;?>>            
            <td colspan="2"><h2 style="color:#FF0000">Virtual Payment Settings </h2></td>
          </tr>         
          <tr class="virtual" <?php echo $virtualStyle;?>>
            <th> Virtual Merchant Id </th>
            <td>
            	<input name="vm_merchant_id" type="text" class="inputbox"  size="55" title="Enter Your Virtual Merchant Id" value="<?php echo $row['vm_merchant_id'];?>" />
           </td>
          </tr>         
          <tr class="virtual" <?php echo $virtualStyle;?>>
            <th>Virtual Payment User Id</th>
            <td><input name="vm_user_id" type="text" value="<?php echo $row['vm_user_id'];?>" class="inputbox"  size="55" title="Enter Your Merchant User Id"/></td>
          </tr>
           <tr class="virtual" <?php echo $virtualStyle;?>>
            <th> Virtual Merchant PIN </th>
            <td><input name="vm_pin" type="text" class="inputbox" value="<?php echo $row['vm_pin'];?>" size="55" title="Enter Your Merchant PIN"/></td>
          </tr>
          <!--Start virtual payment Settings hide show, ends here-->
          
           <!--Start  Internet Secure Gateway Settings hide show-->
           <?php if($row['is_internet_secure']=='0') { $internet='style="display:none;"'; } else {  $internet="";} ?>
          <tr class="internet_secure" <?php echo $internet;?>>            
            <td colspan="2"><h2 style="color:#FF0000">Internet Secure Gateway Settings</h2></td>
          </tr>
           <tr class="internet_secure" <?php echo $internet;?>>
            <th>Internet Secure Gateway ID</th>
            <td><input name="internet_secure_getwayid" type="text" class="inputbox"  size="55" value="<?php echo $row['internet_secure_getwayid'];?>"/></td>
          </tr>
         <!--end  Internet Secure Gateway Settings-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">PaymentID Settings</h2></td>
          </tr>
          <tr>
            <th>Cash PaymentID</th>
            <td><input name="cash_payment_id" type="text" class="inputbox"  size="55" value="<?php echo $row['cash_payment_id'];?>"/></td>
          </tr>
          <tr>
            <th>Online PaymentID</th>
            <td><input name="online_payment_id" type="text" class="inputbox"  size="55" value="<?php echo $row['online_payment_id'];?>"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Notification Emails</h2></td>
          </tr>
          <tr>
            <th>Order Email1</th>
            <td><input name="order_email1" type="text" class="inputbox"  size="55" value="<?php echo $row['order_email1'];?>"/></td>
          </tr>
          <tr>
            <th>Order Email2</th>
            <td><input name="order_email2" type="text" class="inputbox"  size="55" value="<?php echo $row['order_email2'];?>"/></td>
          </tr>
          <tr>
            <th>Order Email3</th>
            <td><input name="order_email3" type="text" class="inputbox"  size="55" value="<?php echo $row['order_email3'];?>"/></td>
          </tr>
          <tr>
            <th>Order Email4</th>
            <td><input name="order_email4" type="text" class="inputbox"  size="55" value="<?php echo $row['order_email4'];?>"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Custom Site Information</h2></td>
          </tr>
          <tr>
            <th>CustomHeaderHTML</th>
            <td><textarea name="custom_header_html" cols="50" rows="6"><?php echo $row['custom_header_html'];?></textarea></td>
          </tr>
          <tr>
            <th>Disclaimer</th>
            <td><textarea name="disclaimer" cols="50" rows="6"><?php echo $row['disclaimer'];?></textarea></td>
          </tr>
          <tr>
            <th>ConfirmOrderMessage</th>
            <td><textarea name="confirm_order_msg" cols="50" rows="6"><?php echo $row['confirm_order_msg'];?></textarea></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Pricing Display</h2></td>
          </tr>
          <tr>
            <th>HidePriceInMenu</th>
            <td><span class="input-out">
              <input name="hide_price_in_menu" type="checkbox"  value="1" <?php if($row['hide_price_in_menu']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
           <tr>
            <th>HidePriceInOption</th>
            <td><span class="input-out">
              <input name="hide_price_in_option" type="checkbox"  value="1" <?php if($row['hide_price_in_option']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
           <tr>            
            <td colspan="2"><h2 style="color:#FF0000">CUSTOMER COMMENTS</h2></td>
          </tr>
           <tr>
            <th>Allow Menu Item Comments </th>
            <td><span class="input-out">
              <input name="menu_iteam_comments" type="checkbox"  value="1" <?php if($row['menu_iteam_comments']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
           <tr>
            <th>Allow Order Comments</th>
            <td><span class="input-out">
              <input name="order_comments" type="checkbox"  value="1" <?php if($row['order_comments']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
           <tr>
            <th>Allow Delivery Instruction</th>
            <td><span class="input-out">
              <input name="delivery_instruction" type="checkbox"  value="1" <?php if($row['delivery_instruction']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Time Zone Settings</h2></td>
          </tr>
          <tr>
            <th>Time Zone</th>
            <td><select name="zone_id" class="custombox" style="width:300px">
                <?php $zonerow = $content->getTimezonelist();?>
                <option value="">please select time zone</option>
                <?php foreach ($zonerow as $zrow):?>
                 <?php $sel = ($row['zone_id'] == $zrow['id']) ? ' selected="selected"' : '' ;?>                 
                <option value="<?php echo $zrow['id'];?>"<?php echo $sel;?>><?php echo $zrow['zone'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>Day Light Saving</th>
            <td><span class="input-out">
              <input name="daylight_saving" type="checkbox"  value="1" <?php if($row['daylight_saving']=='1') {?> checked="checked" <?php } ?> />
            </span></td>
          </tr>
          <tr>
            <th>Allowed Unconfirmed Order</th>
            <td><span class="input-out">
              <input name="allowed_unconfirmed_order" type="checkbox"  value="1" <?php if($row['allowed_unconfirmed_order']=='1') {?> checked="checked" <?php } ?>/>
            </span></td>
          </tr>
          <tr>
            <th><?php echo _LM_PUB;?>:<?php echo required();?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th>Banner Link</th>
            <td><input name="banner_link" type="text" class="inputbox"  size="55" value="<?php echo $row['banner_link']; ?>" /></td>
          </tr> 
          <tr>
            <th>Banner Image </th>
             <td>
            	<div class="fileuploader">
                    <input type="text" class="filename" readonly="readonly"/>
                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                    <input type="file" name="banner_image" />
                    <?php  if(is_file("../uploads/banner/".$row['banner_image'])){ ?>
                    <span style="margin-left:10px;">
                    	<img src="<?php echo UPLOADURL.'/banner/'.$row['banner_image']; ?>" width="100" hight="100"/></span>
                    <?php } ?>
              	</div>
             </td>
          </tr>
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processlocation","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LM_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LM_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LM_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LM_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=location_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
        <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Location Details</h2></td>
        </tr>
        <tr>
            <th><?php echo _LM_LON;?>: <?php echo required();?></th>
            <td><input name="location_name" id="location_name" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_RES;?>: <?php echo required();?></th>
            <td><input name="restaurant_name" id="restaurant_name" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_NAME;?>:<?php echo required();?></th>
            <td><select name="company_id" id="ddlViewBy" class="custombox" style="width:300px">
                <?php $companyrow = $content->getcompanylist();?>
                <option value="">please select company Name</option>
                <?php foreach ($companyrow as $prow):?>                
                <option value="<?php echo $prow['id'];?>"><?php echo $prow['company_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
         <tr>
            <th><?php echo _LM_LOGO_DEL;?>:</th>
            <td><span class="input-out">
              <input name="is_same_company_address" id="is_same_company_address" type="checkbox" value="1" class="checkbox"/>
              </span></td>
          </tr>
          <tr>
            <th><?php echo _LM_GA;?>:<?php echo required();?></th>
            <td><textarea name="address1" id="address1" cols="50"  rows="6"></textarea>
              </td>
          </tr>
          <tr>
            <th><?php echo _LS_GA;?>:</th>
            <td><textarea name="address2" id="address2" cols="50"  rows="6"></textarea>
              </td>
          </tr>
          
           <tr>
            <th><?php echo _LM_PHONE;?>: </th>
            <td><input name="phone_number" id="phone_number" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_PHONE1;?>: </th>
            <td><input name="phone_number1" id="phone_number1" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_FAX;?>:</th>
            <td><input name="fax_number" id="fax_number" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
           <tr>
            <th><?php echo _LM_ZIP;?>:</th>
            <td><input name="zipcode" id="zipcode" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>          
          <tr>
            <th>Country:<?php echo required();?></th>
            <td><select name="country_id" id="country_id" class="custombox" style="width:300px">
                <?php $currencyrow = $content->getCountrylist();?>
                <option value="">please select country </option>
                <?php foreach ($currencyrow as $prow):?>                
                <option  value="<?php echo $prow['id'];?>"><?php echo $prow['country_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>State:<?php echo required();?></th>
            <td><select name="state_id" id="state_id" class="custombox" style="width:300px">
                <?php $staterow = $content->getstatelist();?> 
                <option value="">select your state</option>
                <?php foreach ($staterow as $srow):?>               
                <option value="<?php echo $srow['id'];?>"><?php echo $srow['state_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>City:<?php echo required();?></th>
            <td><select name="city_id" id="city_id" class="custombox" style="width:300px">
                <?php $cityrow = $content->getCitylist();?> 
                <option value="">select your City</option>
                <?php foreach ($cityrow as $srow):?>               
                <option value="<?php echo $srow['id'];?>"><?php echo $srow['city_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>  
          <tr>
            <th><?php echo _LM_WEB;?>: </th>
            <td><input name="website" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>     
          <tr>
            <th><?php echo _LM_LS;?>: </th>
            <td><input name="location_slogan" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th>Latitude: </th>
            <td><input name="latitude" type="text" class="inputbox"  size="55" title="<?php echo "enter latitude";?>"/></td>
          </tr>
          <tr>
            <th>Longitude: </th>
            <td><input name="longitude" type="text" class="inputbox"  size="55" title="<?php echo "enter longitude";?>"/></td>
          </tr>
          <tr>
            <th>Zoom Level: </th>
             <td><input name="zoom_level" type="text" class="inputbox"  size="55" title="<?php echo "Enter zoom level";?>"/></td>
          </tr>
          <tr>
            <th>Restorant Timing: </th>
            <td class="editor">
             <textarea  name="restorant_time" rows="7" cols="52"></textarea>
               <?php //loadEditor("bodycontent"); ?>
            </td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Types</h2></td>
          </tr>
           <tr>
            <th><?php echo _LM_PICK_AVL;?>:</th>
            <td><input name="pick_up_available" type="checkbox" id="active-1" value="1"  /> 
            <label>Pickup time</label>   <input name="pickup_time" type="text" class="inputbox"  size="10" title="enter your pickup time"/>&nbsp;(min)</td>
          </tr>
           <tr>
            <th>Delivery Available:</th>
            <td><input name="delivery_available" type="checkbox" id="active-2" value="1" />
             <label>Delivery-time</label>   <input name="delivery_time" type="text" class="inputbox"  size="10" title="enter your delivery time"/> &nbsp;(min)</td>
          </tr>
           <tr>
            <th>Dine-in Available:</th>
            <td><input name="dinein_available" type="checkbox" id="active-3" value="1"  /></td>
          </tr> 
          <tr>
            <th>Delivery Fee: </th>
            <td><input name="delivery_fee" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/>  </td>
          </tr>
          
          <tr>
            <th>Additional Fee: </th>
            <td><input name="additional_fee" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_GT;?>:</th>
            <td><input name="gratuity" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_ST;?>:</th>
            <td><input name="sales_tax" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/>(%)</td>
          </tr>
          <tr>
            <th>Maximum Advance Order: </th>
            <td><input name="max_advance_order" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/></td>
          </tr>
          <tr>
            <th><?php echo _LM_STI;?>: </th>
            <td><input name="sales_tax_id" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>" value="0"/></td>
          </tr>
          
          <tr>
            <th><?php echo _LM_EDC;?>:</th>
            <td><input name="emai_disclaimer" type="text" class="inputbox"  size="55" title="<?php echo _CI_TITLE_R;?>"/></td>
          </tr>
          
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">POS Details</h2></td>
          </tr>
          <tr>
            <th>Site Id </th>
            <td><input name="site_id" type="text" class="inputbox"  size="55" title="Enter your site id"/></td>
          </tr>
          <tr>
            <th>Pos Password</th>
            <td><input name="pos_password" type="text" class="inputbox"  size="55" title="Enter your pos password"/></td>
          </tr>
          <tr>
            <th>Pos IP</th>
            <td><input name="pos_ip" type="text" class="inputbox"  size="55" title="Enter your pos ip"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Payment Gateway Settings </h2></td>
          </tr>
            <tr>
            <th>Choose Payment Method :</th>
            <td><span class="input-out">
              <label>Cash On Delivery</label>
              <input name="is_cash_on_delivery" type="checkbox"  value="1" checked="checked" />
               <label>Paypal</label>
              <input name="is_paypal" onclick="Change();" id="is_paypal" type="checkbox"  value="1" />
              <label>Authorize</label>
              <input name="is_authorize" onclick="Change();" id="is_authorize" type="checkbox"  value="1" />
              <label>First Data</label>
              <input name="is_first_data" onclick="Change();" id="is_first_data" type="checkbox"  value="1" />
              <label>Mercury</label>
              <input name="is_mercury" onclick="Change();" id="is_mercury" type="checkbox" value="1"/>
              <label>Virtual Merchant</label>
              <input name="is_vm" onclick="Change();" id="is_vm" type="checkbox" value="1"/>
              <label>Internet Secure</label>
              <input name="is_internet_secure" onclick="Change();" id="is_internet_secure" type="checkbox" value="1"/>
              </span></td>
          </tr> 
          <tr class="paypal" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">Paypal Settings </h2></td>
          </tr>        
          <tr style="display:none;" class="paypal">
            <th>Paypal Email Id</th>
            <td><input name="paypal_email_id" type="text" class="inputbox"  size="55" title="Enter your paypal email address"/></td>
          </tr>
          <tr class="paypal"  style="display:none;" >
            <th>Paypal Password</th>
            <td><input name="paypal_password" type="text" class="inputbox"  size="55"/></td>
          </tr>
           <tr class="paypal"  style="display:none;" >
            <th>Paypal Signature</th>
            <td><input name="paypal_signature" type="text" class="inputbox"  size="100" /></td>
          </tr>
          <tr class="paypal"  style="display:none;" >
            <th>Paypal Password</th>
            <td><input name="paypal_password" type="text" class="inputbox"  size="55"/></td>
          </tr>
           <tr class="paypal"  style="display:none;" >
            <th>Paypal Signature</th>
            <td><input name="paypal_signature" type="text" class="inputbox"  size="100" /></td>
          </tr>
          <!--Start Authorize.net Settings Hide show-->
          <tr class="authorize" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">Authorize.net Settings </h2></td>
          </tr>
          <tr class="authorize" style="display:none;">
            <th>Authorize Api ID</th>
            <td><input name="authorizr_api_id" type="text" class="inputbox"  size="55" title="Enter your Authorize Api ID"/></td>
          </tr>
          <tr class="authorize" style="display:none;">
            <th>Authorize Transaction Key</th>
            <td><input name="authorizze_trans_key" type="text" class="inputbox"  size="55" title="Enter your Authorize Transaction Key"/></td>
          </tr>
          <tr class="authorize" style="display:none;">
            <th>Authorize Hash Key</th>
            <td><input name="authorize_hash_key" type="text" class="inputbox"  size="55" title="Enter your Authorize Hash Key"/></td>
          </tr>
       
          <!--Start First Data Settings Hide show-->
          <tr class="first_data" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">First Data Settings </h2></td>
          </tr>
          <tr class="first_data" style="display:none;"> 
            <th>First Data File Name</th>
            <td>
            <div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="first_data_file_name" />
              </div>
            <!--<input name="first_data_file_name" type="text" class="inputbox"  size="55" title="Enter Your First Data File Name"/>--></td>
          </tr>
         <!---->
         <!--Start Mercury Settings hide show-->
          <tr class="mercury" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">Mercury Settings </h2></td>
          </tr>
          <tr class="mercury" style="display:none;">
            <th>Merchant Id </th>
            <td><input name="merchant_id" type="text" class="inputbox"  size="55" title="Enter Your Merchant Id"/></td>
          </tr>
          <tr class="mercury" style="display:none;">
            <th>Merchant Password</th>
            <td><input name="merchant_password" type="text" class="inputbox"  size="55" title="Enter Your Merchant Password"/></td>
          </tr>
          <!--Start Virtual payment Settings, starts show-->
          <tr class="virtual" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">Virtual Payment Settings </h2></td>
          </tr>
          <tr class="virtual" style="display:none;">
            <th> Virtual Merchant Id </th>
            <td><input name="vm_merchant_id" type="text" class="inputbox"  size="55" title="Enter Your Virtual Merchant Id"/></td>
          </tr>         
          <tr class="virtual" style="display:none;">
            <th>Virtual Payment User Id</th>
            <td><input name="vm_user_id" type="text" class="inputbox"  size="55" title="Enter Your Merchant User Id"/></td>
          </tr>
           <tr class="virtual" style="display:none;">
            <th> Virtual Merchant PIN </th>
            <td><input name="vm_pin" type="text" class="inputbox"  size="55" title="Enter Your Merchant PIN"/></td>
          </tr>
          <!--Start Virtual payment Settings, ends show-->      
          
          <!--Start  Internet Secure Gateway Settings hide show-->
          <tr class="internet_secure" style="display:none;">            
            <td colspan="2"><h2 style="color:#FF0000">Internet Secure Gateway Settings</h2></td>
          </tr>
           <tr class="internet_secure" style="display:none;">
            <th>Internet Secure Gateway ID</th>
            <td><input name="internet_secure_getwayid" type="text" class="inputbox"  size="55" title="Enter Your Internet Secure Gateway ID"/></td>
          </tr>
         <!--end  Internet Secure Gateway Settings-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">PaymentID Settings</h2></td>
          </tr>
          <tr>
            <th>Cash PaymentID</th>
            <td><input name="cash_payment_id" type="text" class="inputbox"  size="55" title="Enter Your Cash PaymentID"/></td>
          </tr>
          <tr>
            <th>Online PaymentID</th>
            <td><input name="online_payment_id" type="text" class="inputbox"  size="55" title="Enter Your Online PaymentID"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Notification Emails</h2></td>
          </tr>
          <tr>
            <th>Order Email1</th>
            <td><input name="order_email1" type="text" class="inputbox"  size="55" title="Enter Your Cash PaymentID"/></td>
          </tr>
          <tr>
            <th>Order Email2</th>
            <td><input name="order_email2" type="text" class="inputbox"  size="55" title="Enter Your Cash PaymentID"/></td>
          </tr>
          <tr>
            <th>Order Email3</th>
            <td><input name="order_email3" type="text" class="inputbox"  size="55" title="Enter Your Cash PaymentID"/></td>
          </tr>
          <tr>
            <th>Order Email4</th>
            <td><input name="order_email4" type="text" class="inputbox"  size="55" title="Enter Your Cash PaymentID"/></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Custom Site Information</h2></td>
          </tr>
          <tr>
            <th>CustomHeaderHTML</th>
            <td><textarea name="custom_header_html" cols="50" rows="6"></textarea></td>
          </tr>
          <tr>
            <th>Disclaimer</th>
            <td><textarea name="disclaimer" cols="50" rows="6"></textarea></td>
          </tr>
          <tr>
            <th>ConfirmOrderMessage</th>
            <td><textarea name="confirm_order_msg" cols="50" rows="6"></textarea></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Pricing Display</h2></td>
          </tr>
          <tr>
            <th>HidePriceInMenu</th>
            <td><span class="input-out">
              <input name="hide_price_in_menu" type="checkbox"  value="1" />
            </span></td>
          </tr>
           <tr>
            <th>HidePriceInOption</th>
            <td><span class="input-out">
              <input name="hide_price_in_option" type="checkbox"  value="1" />
            </span></td>
          </tr>
           <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Customer Comments</h2></td>
          </tr>
           <tr>
            <th>Allow Menu Item Comments </th>
            <td><span class="input-out">
              <input name="menu_iteam_comments" type="checkbox"  value="1" />
            </span></td>
          </tr>
           <tr>
            <th>Allow Order Comments</th>
            <td><span class="input-out">
              <input name="order_comments" type="checkbox"  value="1" />
            </span></td>
          </tr>
           <tr>
            <th>Allow Delivery Instruction</th>
            <td><span class="input-out">
              <input name="delivery_instruction" type="checkbox"  value="1" />
            </span></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Time Zone Settings</h2></td>
          </tr>
          <tr>
            <th>Time Zone</th>
            <td><select name="zone_id" class="custombox" style="width:300px">
                <?php $zonerow = $content->getTimezonelist();?>
                <option value="">please select time zone</option>
                <?php foreach ($zonerow as $zrow):?>                
                <option value="<?php echo $zrow['id'];?>"><?php echo $zrow['zone'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th>Day Light Saving</th>
            <td><span class="input-out">
              <input name="daylight_saving" type="checkbox"  value="1" />
            </span></td>
          </tr>
          <tr>
            <th>Allowed Unconfirmed Order</th>
            <td><span class="input-out">
              <input name="allowed_unconfirmed_order" type="checkbox"  value="1" />
            </span></td>
          </tr>
          <tr>
            <th><?php echo _LM_PUB;?>:<?php echo required();?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
         <tr>
            <th>Banner Link</th>
            <td><input name="banner_link" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th>Banner Image </th>
             <td>
            	<div class="fileuploader">
                    <input type="text" class="filename" readonly="readonly"/>
                    <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                    <input type="file" name="banner_image" />
              	</div>
             </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>

<?php echo $core->doForm("processlocation","controller.php" ,1,1);?>
<?php break;?>
<?php default: ?>
<?php  $companyrow = $content->getlocation($userlocationid); ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _LM_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LocationINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2>
    <?php if($user->userlevel == 9):?>
    <span><a href="index.php?do=location_master&amp;action=add" class="button-sml"><?php echo _LM_ADD;?></a></span>
	<?php endif;?>
	<?php echo _LM_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _LM_LON;?></th>
          <th class="left sortable"><?php echo _CM_NAME;?></th> 
          <th><?php echo _PUBLISHED;?></th>
          <th>View</th>
          <?php if($user->userlevel == 9):?>
          <th><?php echo _LM_EDIT;?></th>
         
          <th><?php echo _DELETE;?></th>
           <?php endif; ?>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="12"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$companyrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_PO_NOLOCATION,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($companyrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>   
          <td><?php echo $row['company_name'];?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
            <td class="center"><a href="javascript:void(0);" class="view-location" data-info="<?php echo $row['location_name'];?>" id="loc_<?php echo $row['id'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo _CUSVIEW_;?>"/></a></td>
            
          <?php /*?><td class="center"><a href="detailspage/location_master.php?pageid=<?php echo $row['id'];?>" onclick="return hs.htmlExpand(this, { objectType: 'ajax',align: 'center'} )"><img src="images/view.png" class="tooltip"  alt="" title="View Location Details"/></a></td><?php */?>
          <?php if($user->userlevel == 9):?>
          <td class="center"><a href="index.php?do=location_master&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _LM_EDIT;?>"/></a></td>
          
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['company_name'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._LMD_MANAGER, "deletelocation");?>
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
    $('a.view-location').click(function() {
        var id = $(this).attr('id').replace('loc_', '')
        var title = $(this).attr('data-info');
		  $.ajax({
			  type: 'post',
			  url: "ajax.php",
			  data: 'viewLocation=' + id + '&name=' + title,
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
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
<script>
// Price show according Attribute
$(document).ready( function()
{

$("#is_same_company_address").live("click", function(e){
var id = parseInt($(this).val(), 10);
var e = $("#ddlViewBy").val();
if(e=="")
{
	alert("Please select your company name");
	$("#is_same_company_address").removeAttr("checked");
}
//alert(e);
if($(this).is(":checked")) {
            $.getJSON("<?php echo SITEURL;?>/ajax/autofilup.php?companyid=" + $("#ddlViewBy").val(),
        function(data){
          $.each(data, function(i,item){
            if (item.field == "address1") {
              $("#address1").val(item.value);
            } else if (item.field == "address2") {
              $("#address2").val(item.value);
            }  else if (item.field == "city_id") {
              $("#city_id").val(item.value);
            }  else if (item.field == "state_id") {
              $("#state_id").val(item.value);
            }  else if (item.field == "country_id") {
              $("#country_id").val(item.value);			 
            }  else if (item.field == "zipcode") {
              $("#zipcode").val(item.value);
            }  else if (item.field == "phone_number") {
              $("#phone_number").val(item.value);
            }  else if (item.field == "phone_number1") {
              $("#phone_number1").val(item.value);
            }  else if (item.field == "fax_number") {
              $("#fax_number").val(item.value);
            }
          });
        });
        } 
  
});

});
</script>
<script type="text/javascript">
 function Change() {
 
  $('#is_paypal').live("click", function() {
    if (this.checked) {	
        $('.paypal').show();
    }
    else {
	
        $('.paypal').hide();
    }
});

 $('#is_authorize').live("click", function() {
    if (this.checked) {	
        $('.authorize').show();
    }
    else {
	
        $('.authorize').hide();
    }
});

$('#is_first_data').live("click", function() {
    if (this.checked) {
        $('.first_data').show();
    }
    else {
        $('.first_data').hide();
    }
});
 
 $('#is_mercury').live("click", function() {
    if (this.checked) {
        $('.mercury').show();
    }
    else {
        $('.mercury').hide();
    }
});

$('#is_vm').live("click", function() {
    if (this.checked) {
        $('.virtual').show();
    }
    else {
        $('.virtual').hide();
    }
});

$('#is_internet_secure').live("click", function() {
    if (this.checked) {
        $('.internet_secure').show();
    }
    else {
        $('.internet_secure').hide();
    }
});
  }             
</script>
<script type="text/javascript">

hs.graphicsDir = 'js/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';

</script>