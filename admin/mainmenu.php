<?php
  /**
   * Mainmenu left side
   *   
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<ul id="nav">
  <?php if($user->getAcl("Menus")):?>
  <li><a href="index.php?do=menus" class="<?php if ($core->do == 'menus') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _N_MENUS;?></a></li>
  <?php endif;?>
    <?php if($user->getAcl("Currency Master")):?>
  <li><a href="index.php?do=currency_master" class="<?php if ($core->do == 'currency_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _N_CURRENCY;?></a></li>
  <?php endif;?>  
  <?php if($user->getAcl("Country Master")):?>
  <li><a href="index.php?do=country_master" class="<?php if ($core->do == 'country_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _N_COUNTRY;?></a></li>
  <?php endif;?>
   <?php if($user->getAcl("State Master")):?>
  <li><a href="index.php?do=state_master" class="<?php if ($core->do == 'state_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _N_STATE;?></a></li>
  <?php endif;?>
   <?php if($user->getAcl("City Master")):?>
  <li><a href="index.php?do=city_master" class="<?php if ($core->do == 'city_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _CI_MANAGER;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Customer")):?>
  <li><a href="index.php?do=customer_master" class="<?php if ($core->do == 'city_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _CMM_MANAGER;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Company Master")):?>
  <li><a href="index.php?do=company_master" class="<?php if ($core->do == 'company_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _CM_MANAGER;?></a></li>
  <?php endif;?>    
  
   <?php if($user->getAcl("Location Manager")):?>
  <li><a href="javascript:void(0);" class="<?php echo ($core->do == 'location_master' or $core->do == 'location_timing_master' or $core->do == 'delivery_area_master' or $core->do == 'holiday_master' or $core->do == 'location_setting') ? "expanded" : "collapsed";?>"><img src="images/icons/leftmenu-icons.png" alt="" /><span>...</span><?php echo _LM_MANAGER;?></a>
    <ul class="subnav">
      <?php if($user->getAcl("Location")):?>
  <li><a href="index.php?do=location_master" class="<?php if ($core->do == 'location_master') echo "active";?>"><?php echo _LM_MANAGER;?></a></li>
  <?php endif;?>
     <?php if($user->getAcl("Location Timing")):?>
  <li><a href="index.php?do=location_timing_master" class="<?php if ($core->do == 'location_timing_master') echo "active";?>"><?php echo _LTM_MANAGER;?></a></li>
  <?php endif;?>   
   <?php if($user->getAcl("DeliveryArea")):?>
  <li><a href="index.php?do=delivery_area_master" class="<?php if ($core->do == 'delivery_area_master') echo "active";?>">Delivery Area</a></li>
  <?php endif;?>
   <?php if($user->getAcl("Holidays")):?>
  <li><a href="index.php?do=holiday_master" class="<?php if ($core->do == 'holiday_master') echo "active";?>"><?php echo _HM_MANAGER;?></a></li>
  <?php endif;?>   
  <?php if($user->getAcl("Location Setting")):?>
  <li><a href="index.php?do=location_setting" class="<?php if ($core->do == 'location_setting') echo "active";?>"><?php echo _LS_SETTING;?></a></li>
  <?php endif;?>     
    </ul>
  </li>
  <?php endif;?>  
   <?php if($user->getAcl("Install Manager")):?>
  <li><a href="index.php?do=website_install" class="<?php if($core->do =='website_install') echo "active";?>"><img src="images/icons/user-ico.png" alt=""/><?php echo _U_INSTALL;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Menu Manager")):?>
  <li><a href="javascript:void(0);" class="<?php echo ($core->do == 'menu_master' or $core->do == 'menu_location_mapping' or $core->do == 'menu_category_master' or $core->do == 'menu_size_master' or $core->do == 'menu_item_master' or $core->do == 'menu_option_master') ? "expanded" : "collapsed";?>"><img src="images/icons/config-ico.png" alt="" /><span>...</span><?php echo _N_MENU;?></a>
    <ul class="subnav">
      <?php if($user->getAcl("Main Menu Manager")):?>
      <li><a href="index.php?do=menu_master" class="<?php if ($core->do == 'menu_master') echo "active";?>"><?php echo _MENU_;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Menu Location Mapping")):?>
      <li><a href="index.php?do=menu_location_mapping" class="<?php if ($core->do == 'menu_location_mapping') echo "active";?>"><?php echo _N_MENUMAPPING;?></a></li>
      <?php endif;?>     
       <?php if($user->getAcl("MenuCategory")):?>
      <li><a href="index.php?do=menu_category_master" class="<?php if ($core->do == 'menu_category_master') echo "active";?>"><?php echo _MCAT_MENUMAPPING;?></a></li>
      <?php endif;?>   
      <?php if($user->getAcl("Menu Size Master")):?>
      <li><a href="index.php?do=menu_size_master" class="<?php if ($core->do == 'menu_size_master') echo "active";?>"><?php echo _MSIZE_MENUMAPPING;?></a></li>
      <?php endif;?>   
      <?php if($user->getAcl("MenuItem")):?>
      <li><a href="index.php?do=menu_item_master" class="<?php if ($core->do == 'menu_item_master') echo "active";?>"><?php echo _MITEM_MITEM;?></a></li>
      <?php endif;?>
        <?php if($user->getAcl("MenuItem")):?>
      <li><a href="index.php?do=icon_manager" class="<?php if ($core->do == 'icon_manager') echo "active";?>">Menu Icon Manager</a></li>
      <?php endif;?>
       <?php if($user->getAcl("MenuOption")):?>
      <li><a href="index.php?do=menu_option_master" class="<?php if ($core->do == 'menu_option_master') echo "active";?>"><?php echo _MENU_OPTION;?></a></li>
      <?php endif;?>
    </ul>
  </li>
  <?php endif;?>
  <?php if($user->getAcl("Order Manager")):?>
  <li><a href="index.php?do=order" class="<?php if ($core->do == 'order') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _CPN_ORDER_MANAGER;?></a></li>
  <?php endif;?>
  
  <?php if($user->getAcl("Coupon")):?>
  <li><a href="index.php?do=coupon_master" class="<?php if ($core->do == 'coupon_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _CPN_MANAGER;?></a></li>
  <?php endif;?>
  
  <?php if($user->getAcl("page")):?>
  <li><a href="index.php?do=page_master" class="<?php if ($core->do == 'page_master') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _PAGE_MANAGER;?></a></li>
  <?php endif;?>
  
   <?php if($user->getAcl("Users Manager")):?>
  <li><a href="index.php?do=users_master" class="<?php if($core->do =='users_master') echo "active";?>"><img src="images/icons/user-ico.png" alt=""/><?php echo _U_MENUS;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Preview")):?>
  <li><a href="../index.php" class="<?php if ($core->do == 'preview') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _PREVIEW;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Configuration")):?>
  <li><a href="javascript:void(0);" class="<?php echo ($core->do == 'config' or $core->do == 'templates' or $core->do == 'newsletter' or $core->do == 'language' or $core->do == 'maintenance' or $core->do == 'logs') ? "expanded" : "collapsed";?>"><img src="images/icons/config-ico.png" alt="" /><span>...</span><?php echo _N_CONF;?></a>
    <ul class="subnav">
      <?php if($user->getAcl("Configuration")):?>
      <li><a href="index.php?do=config" class="<?php if ($core->do == 'config') echo "active";?>"><?php echo _CG_TITLE1;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Templates")):?>
      <li><a href="index.php?do=templates" class="<?php if ($core->do == 'templates') echo "active";?>"><?php echo _N_EMAILS;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Empty Garbase")):?>
      <li><a href="index.php?do=empty_garbase" class="<?php if ($core->do == 'empty_garbase') echo "active";?>"><?php echo _N_GARBASE;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Newsletter")):?>
      <li><a href="index.php?do=newsletter" class="<?php if ($core->do == 'newsletter') echo "active";?>"><?php echo _N_NEWSL;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Language")):?>
      <li><a href="index.php?do=language" class="<?php if ($core->do == 'language') echo "active";?>"><?php echo _N_LANGS;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Maintenance")):?>
      <li><a href="index.php?do=maintenance" class="<?php if ($core->do == 'maintenance') echo "active";?>"><?php echo _N_SMTCN;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Logs")):?>
      <li><a href="index.php?do=logs" class="<?php if ($core->do == 'logs') echo "active";?>"><?php echo _N_LOGS;?></a></li>
      <?php endif;?>
    </ul>
  </li>
  <?php endif;?>
</ul>