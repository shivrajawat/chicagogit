<?php
  /**
   * Menu Class
   *  
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Menu
  {
	  private $uTable = "res_menu_item_master";
	  public $menuid = 0;
	      

      /**
       * Menu::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getMenuId();		  
      }

	  /**
	   * Menu::getRestaurantId()
	   * 
	   * @return
	   */
	  private function getMenuId()
	  {
	  	  global $core;
		  if (isset($_GET['menuid'])) {
			  $menuid = (is_numeric($_GET['menuid']) && $_GET['menuid'] > -1) ? intval($_GET['menuid']) : false;
			  $menuid = sanitize($menuid);
			  
			  if ($menuid == false) {
				  $core->error("You have selected an Invalid Menuid","Menus::getMenuId()");
			  } else
				  return $this->menuid = $menuid;
		  }
	  } 

	/**
	   * Menu::processMenuMaster()
	   * 
	   * @return
	   */
	  public function processMenuMaster()
	  {
		  global $db, $core, $wojosec;
		  
		if (empty($_POST['menu_name']))
			  $core->msgs['menu_name'] = "Please Enter Menu Name";
		  
		   if (empty($core->msgs)) {
				  $data = array(
				  'menu_name'=> sanitize($_POST['menu_name']), 
				  'active' => intval($_POST['active'])
			  );
			  
			  if(empty($this->menuid))
			  		{
				 	 $data['created_date'] = "NOW()";
					 
					}
			   else{
				   $data['modified_date'] = "NOW()";
			      }	 
			  
			  ($this->menuid) ? $db->update("res_menu_master", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_menu_master", $data);
			  $message = ($this->menuid) ? _MENU_UPDATED : _MENU_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Menu::processmenuLocation()
       * 
       * @return
       */
	  public function processmenuLocation()
	  {
		  global $db, $core, $wojosec;
		 
		  
		  if (empty($_POST['location_id']))
			  $core->msgs['location_id'] = "please select Location";
			  
		  if (empty($_POST['menu_id']))
			  $core->msgs['menu_id'] = "please select menu";
			  
		  if ($this->locationMappingExists($_POST['location_id'],$_POST['menu_id'],$this->menuid))
			  $core->msgs['location_id'] = _LOC_UNI_LOCAMENU;	
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'location_id' => sanitize($_POST['location_id']),
				  'menu_id' => sanitize($_POST['menu_id']),
				  
			  );
			  
			   if(empty($this->menuid))
			  {
				  $data['created_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }

			 
			  ($this->menuid) ? $db->update("res_menu_location_mapping", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_menu_location_mapping", $data);
			  $message = ($this->menuid) ? _MLMAP_UPDATED : _MLMAP_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_menu_location_mapping") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  
	    /**
	   * Menu::currencyExists()
	   * 
	   * @return
	   */
	  public function locationMappingExists($location,$menu,$menuid)
	  {
		  global $db;
		   if(!empty($menuid)):
		   	$adsql = "&& id != $menuid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, location_id,menu_id" 
							  . "\n FROM res_menu_location_mapping " 
							  . "\n WHERE location_id = '" . intval($location) . "'  && menu_id = '" . intval($menu) . "'".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	   /**
       * Menu::getmenulocation()
       * 
       * @return
       */
	  
	  public function getmenulocation()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_menu_location_mapping");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT sm.*,cm.location_name,lm.menu_name"
		  . "\n FROM res_menu_location_mapping as sm"
		  ."\n left join res_location_master as cm on cm.id=sm.location_id"
		   ."\n left join res_menu_master as lm on lm.id=sm.menu_id". $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	  
	  /**
	   * Menu::getmenumaster()
	   * 
	   * @return
	   */
	  public function getmenumaster()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_menu_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *"
		  . "\n FROM res_menu_master";		  
		 // . "\n ORDER BY menu_name". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::getmenucategory()
       * 
       * @return
       */
	  
	  public function getmenucategory($locationid)
	  {
		  global $db, $core, $pager;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE mlm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_category_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT sm.*,lm.menu_name,location_name"
		   . "\n FROM res_category_master as sm"
		   . "\n left join res_menu_master as lm on lm.id=sm.menu_id"
		   . "\n left join res_menu_location_mapping as mlm on mlm.menu_id =lm.id"
		   ."\n left join res_location_master as l on l.id=mlm.location_id" . " " .$adsql
		   ."\n and is_delete ='0' GROUP BY sm.id ORDER BY sm.id DESC ".$pager->limit;  
		   	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }	  
	/**
       * Menu::processmenucategory()
       * 
       * @returns
       */
	  public function processmenucategory()
	  {
		  global $db, $core, $wojosec;
		  if (empty($_POST['menu_id']))
			  $core->msgs['menu_id'] = "please select Your menu";
			  if (empty($_POST['category_name']))
			  $core->msgs['category_name'] = "Please Enter Your Category Name";
			  if (empty($_POST['category_dec']))
			  $core->msgs['category_dec'] = "Please Enter Your Category Description";
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'menu_id' => sanitize($_POST['menu_id']),
				  'parent_id' => sanitize($_POST['parent_id']),
				  'category_name' => html_entity_decode($_POST['category_name']),
				  'display_order' => sanitize($_POST['display_order']),
				  'category_dec' => sanitize($_POST['category_dec']),
				  'active' => intval($_POST['active'])
				  
			  );
			  
			   if(empty($this->menuid))
			  {
				  $data['created_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }
			  
			   // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['photo_image']['name'], strrpos($_FILES['photo_image']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['photo_image'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/category_images/';
			 // $als->NewWidth = $core->avatar_w;
			 // $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->menuid) {
				  $photo_image = getValue("category_image","res_category_master","id = '".$this->menuid."'");
				  if (!empty($_FILES['photo_image']['name'])) {
					  if ($photo_image) {
						  @unlink($als->SavePath . $photo_image);
					  }
					  $data['category_image'] = $name;
				  } else {
					  $data['category_image'] = $photo_image;
				  }
			  } else {
				  if (!empty($_FILES['photo_image']['name'])) 
				  $data['category_image'] = $name;
			  }
			 
			  ($this->menuid) ? $db->update("res_category_master", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_category_master", $data);
			  
			  $insert_id = $db->insertid();	
			  
			  $message = ($this->menuid) ? _MCAT_UPDATED : _MCAT_ADDED;  
			  
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_category_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);		
			  
			  if($insert_id)
			  {
			  	if(!empty($data['parent_id']))
				{
			  	 $sql = "SELECT `child_node` as childnode"
					  . "\n FROM `res_category_master` WHERE `id` ='".$data['parent_id']."'";		  
					
					  $rowchild = $db->first($sql);	
					  $child =  $rowchild['childnode'].$insert_id.",";	
			   	}
				else
				{
					 $child = ",".$insert_id.",";
				}
					
						  	
			  	   $data1['child_node'] =  $child; 
				 $db->update("res_category_master", $data1, "id='" . (int)$insert_id . "'");
			  }
			  else {
				 			  
				 if($this->menuid  && !empty($data['parent_id']) ){				 
						
					 
					  $sql = "SELECT `child_node` as childnode"
					 		 . "\n FROM `res_category_master` WHERE `id` ='".$data['parent_id']."'";		  
					
					  $rowchild = $db->first($sql);	
					  $child = $rowchild['childnode'].$this->menuid."," ;					  
					 
				 }
				 else
				 {
					 $child = ",".$this->menuid.",";
				 }
				 
				 $data1['child_node'] =  $child; 
				 
				 $message = ($this->menuid) ? _MCAT_UPDATED : _MCAT_ADDED;
				 
				 
				 
				 $db->update("res_category_master", $data1, "id='" . (int)$this->menuid . "'");
				 
				//($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_category_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
			  
		  	 }	  
			  
			  
			  
			//$message = ($this->menuid) ? _MCAT_UPDATED : _MCAT_ADDED;  
			  
			//($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_category_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);			
			
			
		  } else
			  print $core->msgStatus();
	  }
	  /**
	   * Menu::getMenulistlocation()
	   * 
	   * @return
	   */
	  public function getMenulistlocation()
	  {
		  global $db, $core;
		  
		   $sql =" SELECT *"
				. "\n  FROM `res_menu_master`";
          $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::getmenusize()
       * 
       * @return
       */
	  
	  public function getmenusize()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_menu_size_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT * "
		  . "\n FROM res_menu_size_master "
		   . $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::processsizemaster()
       * 
       * @return
       */
	  public function processsizemaster()
	  {
		  global $db, $core, $wojosec;
		     if (empty($_POST['ticket_size_id']))
			  $core->msgs['ticket_size_id'] = "Please Enter Your size ID";
			  
			  if (empty($_POST['size_name']))
			  $core->msgs['size_name'] = "Please Enter Your size Name";
			  
			  if (empty($_POST['description']))
			  $core->msgs['description'] = "Please Enter Your size Description";
			  
			   if (!empty($_FILES['size_image']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['size_image']['name'])) {
				  $core->msgs['size_image'] = _CG_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['size_image']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['size_image'] = _CG_LOGO_R;
		  }
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'ticket_size_id' => sanitize($_POST['ticket_size_id']),
				   'size_name' => sanitize($_POST['size_name']),
				   'description' => sanitize($_POST['description']),
				    'active' => intval($_POST['active'])
				  
			  );
			  
			   if(empty($this->menuid))
			  {
				  $data['created_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }

			 // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['size_image']['name'], strrpos($_FILES['size_image']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['size_image'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/size_image/';			  
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->menuid) {
				  $size_image = getValue("size_image","res_menu_size_master","id = '".$this->menuid."'");
				  if (!empty($_FILES['size_image']['name'])) {
					  if ($size_image) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['size_image'] = $name;
				  } else {
					  $data['size_image'] = $size_image;
				  }
			  } else {
				  if (!empty($_FILES['size_image']['name'])) 
				  $data['size_image'] = $name;
			  }
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  ($this->menuid) ? $db->update("res_menu_size_master", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_menu_size_master", $data);
			  $message = ($this->menuid) ? _MSIZE_UPDATED : _MSIZE_ADDED;
			 if($db->affected()):			  	 
				$wojosec->writeLog($message, "", "no", "res_menu_size_master"); //. $core->msgOk("ok");
				 print_r("1");				  
			  else:
			  	$core->msgAlert(_SYSTEM_PROCCESS);
			  endif;
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Menu::getMenuItem()
       * 
       * @return
       */
	  
	  public function getMenuItem($locationid)
	  {
		  global $db, $core, $pager;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE mlm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_menu_item_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT im.*"
		  . "\n FROM res_menu_item_master AS im"
		  . "\n LEFT JOIN res_category_master AS sm ON sm.id = im.category_id"
		  . "\n LEFT JOIN res_menu_master AS lm on lm.id=sm.menu_id"
		  . "\n LEFT JOIN res_menu_location_mapping AS mlm on mlm.menu_id =lm.id"
		  ."\n LEFT JOIN res_location_master AS l on l.id=mlm.location_id" . " " .$adsql
		  ."\n GROUP BY im.id".$pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	   /**
       * Menu::procesmenuitemmaster()
       * 
       * @return
       */
	  public function procesmenuitemmaster()
	  {
		  global $db, $core, $wojosec;	
		  	 error_reporting(0);	
			 
			 
			// echo "<pre>"; print_r($_POST); exit();  
		  
		     if (empty($_POST['category_id']))
			  $core->msgs['category_id'] = "Please Select Your Category Name";
			  
			  if (empty($_POST['menu_type']))
			  $core->msgs['menu_type'] = "Please Select Your Menu Name";
			  
			  if (empty($_POST['short_name']))
			  $core->msgs['short_name'] = "Please Enter Your Short Name";
			  
			   if (empty($_POST['item_description']))
			  $core->msgs['item_description'] = "Please Enter Your Menu Item Description";
			  
			  if (empty($_POST['short_name']))
			  	$core->msgs['short_name'] = "Please Enter Your Short Name";
				
			  
		  	  if (!empty($_FILES['big_item_image']['name'])) {
				  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['big_item_image']['name'])) {
					  $core->msgs['big_item_image'] = _CG_LOGO_R;
				  }
				  $file_info = getimagesize($_FILES['big_item_image']['tmp_name']);
				  if(empty($file_info))
					  $core->msgs['big_item_image'] = _CG_LOGO_R;
		      }
			  
			  if (!empty($_FILES['thumb_item_image']['name'])) {
				  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['thumb_item_image']['name'])) {
					  $core->msgs['thumb_item_image'] = _CG_LOGO_R;
				  }
				  $file_info = getimagesize($_FILES['thumb_item_image']['tmp_name']);
				  if(empty($file_info))
					  $core->msgs['thumb_item_image'] = _CG_LOGO_R;
		      }
			  
			  
			  if (!empty($_POST['available_days']))
			  {
			  $days = implode(",",$_POST['available_days']);
			  }
			  else
			  {	
			  	$days = '0';
			  }
			   if (!empty($_POST['special_menu_icon'])) {				  
				 $special_menu_icon = implode(',',$_POST['special_menu_icon']); 
		      }
			  
		  if (empty($core->msgs)) {			  
			  $data = array(
				   'category_id' => sanitize($_POST['category_id']),
				   'item_type' => sanitize($_POST['item_type']),
				   'menu_type' => sanitize($_POST['menu_type']),
				   'item_name' => html_entity_decode($_POST['item_name']),
				   'special_menu_icon' => sanitize($special_menu_icon),
				   'short_name' => sanitize($_POST['short_name']),
				   'item_description' => html_entity_decode($_POST['item_description']),
				   'min_qty' => intval($_POST['min_qty']),
				   'max_qty' => intval($_POST['max_qty']),
				   'preparation_time' => intval($_POST['preparation_time']),
				   'out_of_stack' => intval($_POST['out_of_stack']),
				   'show_item_in_menu' => sanitize($_POST['show_item_in_menu']),
				   'show_item_in_option' => sanitize($_POST['show_item_in_option']),
				   'price' => sanitize($_POST['price']),
				   'pick_up' => (empty($_POST['pick_up'])) ?  "0" : intval($_POST['pick_up']),  
				   'delivery' => (empty($_POST['delivery'])) ?  "0" : intval($_POST['delivery']),
				   'dineln' => (empty($_POST['dineln'])) ?  "0" : intval($_POST['dineln']),	
				   'breakfast' => (empty($_POST['breakfast'])) ?  "0" : intval($_POST['breakfast']),  
				   'lunch' => (empty($_POST['lunch'])) ?  "0" : intval($_POST['lunch']),
				   'dinner' => (empty($_POST['dinner'])) ?  "0" : intval($_POST['dinner']),				  
				   'show_image_in_menu' => sanitize($_POST['show_image_in_menu']),
				   'available_days' => $days,
				   'ticket_item_id' => intval($_POST['ticket_item_id']),
				   'size_id' => sanitize($_POST['size_id']),
				   'show_nutrition_info' => sanitize($_POST['show_nutrition_info']),
				   'nutrition_ingredient' => sanitize($_POST['nutrition_ingredient']),
				   'display_order' => sanitize($_POST['display_order']),
				   'active' => intval($_POST['active'])  
			  );
			 // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['big_item_image']['name'], strrpos($_FILES['big_item_image']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['big_item_image'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/menuitem/';
			  $als->NewWidth = 458;
			  $als->NewHeight = 161;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->menuid) {
				  $avatar = getValue("big_item_image","res_menu_item_master","id = '".$this->menuid."'");
				  if (!empty($_FILES['big_item_image']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['big_item_image'] = $name;
				  } else {
					  $data['big_item_image'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['big_item_image']['name'])) 
				  $data['big_item_image'] = $name;
			  }
			  
			  $thumnewName = "IMG_" . randName();
			  $thumext = substr($_FILES['thumb_item_image']['name'], strrpos($_FILES['thumb_item_image']['name'], '.') + 1);
			  $thumname = $thumnewName.".".strtolower($thumext);
		
			  $als = new Upload();
			  $als->File = $_FILES['thumb_item_image'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/menuitem/thumb/';
			  $als->NewWidth = "70";
			  $als->NewHeight = "50";
			  $als->NewName  = $thumnewName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->menuid) {
				  $thumavatar = getValue("thumb_item_image","res_menu_item_master","id = '".$this->menuid."'");
				  if (!empty($_FILES['thumb_item_image']['name'])) {
					  if ($thumavatar) {
						  @unlink($als->SavePath . $thumavatar);
					  }
					  $data['thumb_item_image'] = $thumname;
				  } else {
					  $data['thumb_item_image'] = $thumavatar;
				  }
			  } else {
				  if (!empty($_FILES['thumb_item_image']['name'])) 
				  $data['thumb_item_image'] = $thumname;
			  }			  
			  
			  ($this->menuid) ? $db->update("res_menu_item_master", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_menu_item_master", $data);
			  $insert_id = $db->insertid();	
			  
			  if($_POST['item_type']=='1'){
				  
			  	 $length = count($_POST['sized_size_id']);
				
				//echo $length; exit();
				
			    for($i=0; $i< $length; $i++){	
					$data1 = array(
							  'size_id' => sanitize($_POST['sized_size_id'][$i]),
							  'price' => sanitize($_POST['sized_price'][$i]),
							  'created_date'=>"NOW()"				  
			             );
						if($_POST['sizemapid'][$i]==""){					
							  if(!empty($insert_id))
							  {
									$data1['menu_item_id'] = $insert_id;  
							  }	
							  else {
									$data1['menu_item_id'] = $this->menuid;
							  }
						  }	
						  	
						 if(!$_POST['sizemapid'][$i]=="") 
						 {
						   $db->update("res_menu_size_mapping", $data1, "id='" . (int)$_POST['sizemapid'][$i]. "'");
						 }
						 else { 
							 $db->insert("res_menu_size_mapping", $data1); 
						 }						 					
					 //$db->insert("res_menu_size_mapping", $data1);
				}
			  
			  }
			$message = ($this->menuid) ? _MENUITEM_UPDATED : _MENUITEM_ADDED;
			if($db->affected()):			  	 
				 $wojosec->writeLog($message, "", "no", "res_menu_item_master"); //. $core->msgOk("ok");
				 print_r("1");				  
			  else:
			  	$core->msgAlert(_SYSTEM_PROCCESS);
			  endif;
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Menu::getMeniSizelist()
       * 
       * @return
       */
	  
	  public function getMeniSizelist()
	  {
		  global $db, $core, $pager;
		

		 $sql = "SELECT * "
		  . "\n FROM res_menu_size_master ";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::getMeniSizeMappingarray()
       * 
       * @return
       */
	  
	  public function getMeniSizeMappingarray($menuitemID)
	  {
		  global $db, $core, $pager;
		

		 $sql = "SELECT * "
		  . "\n FROM res_menu_size_mapping"
		  ."\n WHERE menu_item_id = '".$menuitemID."'";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  /**
       * Menu::processCouponmaster()
       * 
       * @return
       */
	  public function processCouponmaster()
	  {
		  global $db, $core, $wojosec;
		  
		     if (empty($_POST['title']))
			  $core->msgs['title'] = "Please Enter Your title";
			  
			 if(!is_numeric($_POST['noofcoupon']))
			 {
			 	$core->msgs['title'] = "No of Coupon should be numeric";
			 }
			  
			  if (!is_numeric($_POST['discount']))
			  $core->msgs['discount'] = "Type Of Discount should be numeric";
			  
			  if (!is_numeric($_POST['noofuseallowed']))
			  $core->msgs['noofuseallowed'] = "No Of use Allowed should be numeric";
			  
			  
		      
		  if (empty($core->msgs)) {			  
			  $data = array(			    
				   'title' => sanitize($_POST['title']),				   
				   'no_of_coupon' => sanitize($_POST['noofcoupon']),
				   'type_of_discount' => sanitize($_POST['typeofdiscount']),
				   'amount_limit' => sanitize($_POST['amount_limit']),
				   'discount' => sanitize($_POST['discount']),
				   'no_of_use_allowed' => sanitize($_POST['noofuseallowed']),
				   'start_date' => sanitize($_POST['start_date']),
				   'end_date' => sanitize($_POST['end_date']),
				   'description' => sanitize($_POST['description']),				   
				   'active' => intval($_POST['active'])	
			  );
			  	for( $i = 1; $i <= $_POST['noofcoupon']; $i++){
				$Guid = NewGuid(12);
				$data['coupon_id'] = $Guid;
		   		$db->insert("res_coupon_master", $data);
		   }		 
			  //($this->menuid) ? $db->update("res_coupon_master", $data, "id='" . (int)$this->menuid . "'") : $db->insert("res_coupon_master", $data);			
			  //print_r($data);
			  $message = ($this->menuid) ? _CPN_UPDATED : _CPN_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_coupon_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  /**
       * Menu::getCouponMaster()
       * 
       * @return
       */
	  
	  public function getCouponMaster()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_coupon_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT * "
		  . "\n FROM res_coupon_master "
		   . $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	   public function ViewCouponMaster($id)
	  {
		  global $db, $core, $pager;
		  
		 $sql = "SELECT * "
		  . "\n FROM res_coupon_master where id=$id ";	  
		  $row = $db->first($sql);
		  return ($row) ? $row : 0; 
		  
	  }
	  
	   /**
       * Menu::getmenucategoryBYItem()
       * 
       * @return
       */
	  
	  public function getmenucategoryBYItem($locationid)
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
		 // echo $locationid; print_r($locationid);
		  
		  //echo "<pre>"; print_r($_POST); //exit();
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "&& mlm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql ='';
		    endif;
			$clause = (isset($clause)) ? $clause : null;
		  /************Start Advance Search *************/
		  if (isset($_POST['advance_search'])) {	
		  		 
				  $category= $_POST['category_id'];
				  
				  $active= $_POST['active'];
				  
				  $location_ID = $_POST['location_id'];
				  
				  $clause2 = '';
				  if($_REQUEST['location_id'] != ""):   

						 if(isset($where)):
		
							$clause2 .= "  &&  rmim.category_id='".$category."' AND rmim.active='".$active."' AND mlm.location_id = '".$_REQUEST['location_id']."' ";
		
						   else:
		
							$where = " AND ";
		
							$clause2 .= $where .= "  mlm.location_id  = '" . $_REQUEST['location_id'] . "'";
		
						   endif;
	
					endif;
					
				  
				   if($_REQUEST['category_id'] != ""):   

						 if(isset($where)):
		
							$clause .= " &&  rmim.category_id='" . $_REQUEST['category_id'] . "' ";
		
						  else:
		
							$where = " AND ";
		
							$clause .= $where .= "  rmim.category_id = '" . $_REQUEST['category_id'] . "'";
		
						   endif;
	
					endif;
					
				 if($_REQUEST['active'] != ""):   

						 if(isset($where)):
		
							$clause .= " &&  rmim.active ='" . $_REQUEST['active'] . "'";
		
						  else:
		
							$where = " AND ";
		
							$clause .= $where .= "  rmim.active = '" . $_REQUEST['active'] . "'";
		
						   endif;
	
					endif;				
					
			  
				 /*if ($category !="" && $active!="")
			      { $clause .= " &&  rmim.category_id='$category' AND rmim.active='$active'"; }
				 if ($category =="" && $active!="")
			      { $clause .= " &&  rmim.active='$active'"; }
				 if ($category !="" && $active=="")
			      { $clause .= " &&  rmim.category_id='$category'"; }*/
				  
			}
			  
			  
			$keywords = "";
			
			if (isset($_POST['usersearchfield'])) {
					
				if(!empty($_POST['usersearchfield'])){
					$keywords = " && sm.category_name LIKE '%".$_POST['usersearchfield']."%' OR rmim.item_name LIKE '%".$_POST['usersearchfield']."%'";
				}
				else {	$keywords = ""; }
				
			}
		  /************End Advance Search *************/   
		 
		  $sql = "SELECT sm.*,lm.menu_name,location_name"
		   . "\n FROM res_category_master as sm"
		   . "\n left join res_menu_master as lm on lm.id=sm.menu_id"
		   . "\n left join res_menu_location_mapping as mlm on mlm.menu_id =lm.id"
		   . "\n left join res_location_master as l on l.id=mlm.location_id"
		   . "\n WHERE sm.id IN (SELECT rmim.category_id  FROM res_menu_item_master AS rmim WHERE rmim.category_id = sm.id ".$clause." ".$adsql." ".$keywords.")"
		   . "\n ".$clause2." GROUP BY sm.id "; 
		
		 	  
		  $sql1 = $db->query($sql);
		
		  $counter = $db->numrows($sql1);  

          $pager->items_total = $counter;

          $pager->default_ipp = $core->perpage;

          $pager->paginate();

          if ($counter == 0) {

              $pager->limit = null;

          }
		  
		 $sql .= $pager->limit;
		 
       	 $row = $db->fetch_all($sql);
		 
		 return ($row) ? $row : 0;
		  
	  }	
	  
	  /**
       * Menu::getmenucategoryBYItem_previous()
       * 
       * @return
       */
	  
	  public function getmenucategoryBYItem_previous($locationid)
	  {
		  global $db, $core, $pager;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "&& mlm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			$clause = (isset($clause)) ? $clause : null;
		  /************Start Advance Search *************/
		  if (isset($_POST['advance_search'])) {			 
			  $category= $_POST['category_id'];
			  $active= $_POST['active'];
				 if ($category !="" && $active!="")
			      { $clause .= " &&  res_menu_item_master.category_id='$category' AND res_menu_item_master.active='$active'";}
				  if ($category =="" && $active!="")
			      { $clause .= " &&  res_menu_item_master.active='$active'";}
				   if ($category !="" && $active=="")
			      { $clause .= " &&  res_menu_item_master.category_id='$category'";}
				  
			  }
			  	$keywords = "";
			    if (isset($_POST['usersearchfield'])) {	
					if(!empty($_POST['usersearchfield'])){
						$keywords = " && sm.category_name LIKE '%".$_POST['usersearchfield']."%' OR res_menu_item_master.item_name LIKE '%".$_POST['usersearchfield']."%'";
					}
					else
					{
						$keywords = "";
					}
					
				}
		  /************End Advance Search *************/   
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_category_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		  $sql = "SELECT sm.*,lm.menu_name,location_name"
		   . "\n FROM res_category_master as sm"
		   . "\n left join res_menu_master as lm on lm.id=sm.menu_id"
		   . "\n left join res_menu_location_mapping as mlm on mlm.menu_id =lm.id"
		   ."\n left join res_location_master as l on l.id=mlm.location_id"
		   ."\n WHERE sm.id IN (SELECT category_id  FROM res_menu_item_master WHERE res_menu_item_master.category_id = sm.id ".$clause." ".$adsql." ".$keywords.")"
		   ."\n GROUP BY sm.id ".$pager->limit;
		
		 	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  	  
	  /**
	  
	   /**
       * Menu::getMenuItemByCat()
       * 
       * @return
       */
	  
	  public function getMenuItemByCat($cat_id)
	  {
		  global $db, $core, $pager;		  
		
		$clause = (isset($clause)) ? $clause : null;
		  /************Start Advance Search *************/
		  if (isset($_POST['advance_search'])) { 
			  $active= $_POST['active'];
				 if ($active!="")
			      { $clause .= " &&  im.active='$active'";}				  
			  }
			  
		$sql = "SELECT im.*"
		  . "\n FROM res_menu_item_master AS im"
		  . "\n LEFT JOIN res_category_master AS sm ON sm.id = im.category_id"
		  ."\n WHERE im.category_id = '".$cat_id."' ".$clause."";
		  //. "\n LEFT JOIN res_menu_master AS lm on lm.id=sm.menu_id"
		  //. "\n LEFT JOIN res_menu_location_mapping AS mlm on mlm.menu_id =lm.id"
		 // ."\n LEFT JOIN res_location_master AS l on l.id=mlm.location_id" . " " .$adsql
		 // ."\n GROUP BY im.id".$pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  
	  /**
	   * Menu::processMenuOption()
	   * 
	   * @return
	   */
	  public function processMenuOption()
	  {	 
		  error_reporting(0);
		  global $db, $core, $wojosec;		   
			 
		  	 $updateoption_id = (isset($_POST['optionid'])) ? $_POST['optionid'] : 0;
			 $updatetopping_id = (isset($_POST['toppingid'])) ? $_POST['toppingid'] : 0;
			 $optiongroup = (isset($_POST['option_group'])) ? $_POST['option_group'] : 0;			
			 $is_normal_option = (isset($_POST['is_normal_option'])) ? $_POST['is_normal_option'] : 0;
			 
			 if($is_normal_option =='0')
			 {
			 	if (empty($_POST['parent_id']))
				  $core->msgs['parent_id'] = "Please Select Child Option";
			 }			  		
			if (empty($_POST['option_name']))
				  $core->msgs['option_name'] = _MIO_TITLE_R;
				  
		  	if (empty($_POST['instruction']))
			  	$core->msgs['instruction'] = _MIO_INST_R;
				
			if(!empty($_POST['is_normal_option'])):
			/*if (empty($_POST['min_choise']))
			  	$core->msgs['min_choise'] = _MIO_Minimum_R;*/
				
			if (empty($_POST['max_choise']))
			  	$core->msgs['max_choise'] = _MIO_Maximum_R;
			
			if (!empty($_POST['max_choise'])) {
				if (!is_numeric($_POST['max_choise']))
				 	 $core->msgs['max_choise'] = "Type Of Max Choise should be numeric";
			}
			if (!empty($_POST['min_choise'])) {
				if (!is_numeric($_POST['min_choise']))
				  	$core->msgs['min_choise'] = "Type Of Min Choise should be numeric";
			}
			 endif;
		   if (empty($core->msgs)) {
			   
			   		  /***************************res_menu_option_master,starts here********************************************************************/
					  $option_name = sanitize($_POST['option_name']);
					  $instruction = sanitize($_POST['instruction']);
					  
					  $data = array(
					  
									  'option_name'=> html_entity_decode($option_name),
									  'instruction'=> html_entity_decode($instruction),
									  'instruction_desc'=> sanitize($_POST['instruction_desc']),
									  'option_type'=> sanitize($_POST['option_type']),
									  'is_normal_option'=> (empty($_POST['is_normal_option'])) ?  "0" : intval($_POST['is_normal_option']),
									  'display_option'=> (empty($_POST['display_option'])) ?  "0" : intval($_POST['display_option']),
									  'have_choice'=> (empty($_POST['have_choice'])) ?  "0" : intval($_POST['have_choice']),   
									  'hide_option'=> (empty($_POST['hide_option'])) ?  "0" : intval($_POST['hide_option']),
									   'hide_instruction'=> (empty($_POST['hide_instruction'])) ?  "0" : intval($_POST['hide_instruction']),
									  'click_to_open'=> (empty($_POST['click_to_open'])) ?  "0" : intval($_POST['click_to_open']), 
									  'is_special_calc'=> (empty($_POST['is_special_calc'])) ?  "0" : intval($_POST['is_special_calc']),
									  'have_toppings'=> (empty($_POST['have_toppings'])) ?  "0" : intval($_POST['have_toppings']),  
									  'min_choise'=> intval($_POST['min_choise']),
									  'max_choise'=> intval($_POST['max_choise']),
									  'size_affect_price'=> intval($_POST['size_affect_price']),
									  'is_default'=> (empty($_POST['is_default'])) ?  "0" : intval($_POST['is_default']),
									  'active' => intval($_POST['active'])
								  );
					  
					  if(empty($this->menuid)){			  		
							 $data['created_date'] = "NOW()";					 
					  }else{
							 $data['modified_date'] = "NOW()";
					  }	 
					  
					  	($this->menuid) ? $db->update("res_menu_option_master", $data, "option_id='" . (int)$this->menuid . "'") : $db->insert("res_menu_option_master", $data);
			  			/***************************res_menu_option_master,ends here********************************************************************/
			  		    $insert_id = $db->insertid();				  
			  if($data['have_choice']=='1'){
				  
			  		$length = count($_POST['choice_name']);	
					for($i=0; $i< $length; $i++){
						
						$choice_name = '';
						$choice_name = sanitize($_POST['choice_name'][$i]);
						
						$data1 = array(
								
							  'choice_name' => html_entity_decode($choice_name),
							  'choice_description' => sanitize($_POST['choice_description'][$i]),
							  'is_add_price' => intval($_POST['is_add_price'][$i]),
							  'active' => intval($_POST['active'])
						);					
							 
						if(empty($this->menuid)){						
							$data1['created_date'] = "NOW()";						 
						}
					   else {
							$data1['modified_date'] = "NOW()";
						}
												 
						if(empty($updateoption_id[$i])){					
							if(!empty($insert_id)){						  
								$data1['option_id'] = $insert_id; 
							}	
							else {
								$data1['option_id'] = $this->menuid;
							}
						}	
								   
					     //**********************************************multiple images upload, starts here***********************************************/  
						   if ($this->menuid) {
							   
							  $avatar = getValue("choice_image"," res_menu_option_choice_master","option_choice_id = '".$this->menuid."'");
							  
							  if (!empty($_FILES['fileinput']['name'][$i])) {
								  
									  if ($avatar) {
										  @unlink(UPLOADS.'/choice_images/'. $avatar);
									  }
									   $uploads_dir = UPLOADS.'/choice_images/';
									   $tmp_name = $_FILES["fileinput"]["tmp_name"][$i];
									   $name = time().$_FILES["fileinput"]["name"][$i];
									   move_uploaded_file($tmp_name, "$uploads_dir/$name");
									   $data1['choice_image'] = $name;
							  } 
							  else {
								  
								   	  $uploads_dir = UPLOADS.'/choice_images/';
									  $tmp_name = $_FILES["fileinput"]["tmp_name"][$i];
									  $name = time().$_FILES["fileinput"]["name"][$i];
									  move_uploaded_file($tmp_name, "$uploads_dir/$name");
									  $data1['choice_image'] = $avatar;
							  		}
						      } 
							  else {
							  
									  $uploads_dir = UPLOADS.'/choice_images/';
									  $tmp_name = $_FILES["fileinput"]["tmp_name"][$i];
									  $name = time().$_FILES["fileinput"]["name"][$i];
									  move_uploaded_file($tmp_name, "$uploads_dir/$name");
							  		  $data1['choice_image'] = $name;
						      }					
					     //**********************************************multiple images upload, ends here*************************************************/						  	
						 if(!$updateoption_id[$i]=="") { 
						 
						   $db->update("res_menu_option_choice_master", $data1, "option_choice_id='" . (int)$_POST['optionid'][$i]. "'");
						 }
						 else { 					 
							 $db->insert("res_menu_option_choice_master", $data1); 
						 }	
						}
			  
			  		}
					
			 if($data['have_toppings']=='1'){
				 			 
			  		 $length = count($_POST['topping_name']);
			 
			   		 for($i=0; $i< $length; $i++){
						 
						 $topping_name = '';
						 $topping_name = sanitize($_POST['topping_name'][$i]);
						 	
						 $data2 = array(
							  'topping_name' => html_entity_decode($topping_name),
							  'out_of_stock' => (empty($_POST['out_of_stock'])) ?  "0" : intval($_POST['out_of_stock'][$i]),
							  'is_default' => (empty($_POST['is_defaulttopping'])) ?  "0" : intval($_POST['is_defaulttopping'][$i]),
							  'option_type'=> sanitize($_POST['option_type']),
							  'display_order'=> intval($_POST['display_order'][$i]),
                 	  	 	  'menu_item_id' => (empty($_POST['menu_item_id'])) ?  "0" : intval($_POST['menu_item_id'][$i]),
							  'menu_size_map_id' => intval($_POST['menu_size_map_id'][$i])
			             );
						 
						if(empty($this->menuid)){
							 $data2['created_date'] = "NOW()";
							}
					   else {
						    $data2['modified_date'] = "NOW()";
						  }	
						   
						if(empty($updatetopping_id[$i])){					
							  if(!empty($insert_id))
							  {
									$data2['option_id'] = $insert_id;  
							  }	
							  else {
							  
									$data2['option_id'] = $this->menuid;
							  }
						  }		
						 
						if(!empty($updatetopping_id[$i]))
						 {
						   $db->update("res_menu_option_topping_master", $data2, "option_topping_id='" . (int)$_POST['toppingid'][$i]. "'");
						 }
						 else { 
							 $db->insert("res_menu_option_topping_master", $data2); 
						 }		
					}
			  }
			  
			 if($data['is_normal_option']=='0'){
			  			  		 
			   		$length = count($_POST['parent_id']);
					
					if($this->menuid && isset($_POST['parent_id']) && !empty($_POST['parent_id'])){
						
						$db->delete("res_menu_option_group", "item_option_id='" . $this->menuid . "'");
					}
			    	for($i=0; $i< $length; $i++){
				
								$data3 = array(			 
								   'p_itemOption_id' => (empty($_POST['parent_id'])) ?  "0" : intval($_POST['parent_id'][$i]),
								   'display_order' => (empty($_POST['display_order'])) ?  "0" : intval($_POST['display_order'][$i])
								   //'display_order' => 0 intval($_POST['display_order'][$i])
								);
								 							 	 		
						 		if(empty($optiongroup[$i])){					
								  if(!empty($insert_id)){								  
										$data3['item_option_id'] = $insert_id;  
								  }	
								  else {
										$data3['item_option_id'] = $this->menuid;
								  }
								 }		
								//if(!$optiongroup[$i]=="")
								if($this->menuid){ 	
								
								 // $db->update("res_menu_option_group", $data3, "option_group_id='" . (int)$_POST['parent_id'][$i]. "'");
								  $db->insert("res_menu_option_group", $data3); 
								  
								 }
								 else {
									 									 									 
									 $db->insert("res_menu_option_group", $data3); 
								 }	
			   				 }
			  	}				
				
			 	 $message = ($this->menuid) ? _MIO_UPDATED : _MIO_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "menu option") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Menu::getMenuOption()
       * 
       * @return
       */
	  
	  public function getMenuOption()
	  {
		  global $db, $core, $pager;
		  
		  $keywords = "";
		  if (isset($_POST['search_keyword'])) {	
					if(!empty($_POST['optionsearchfield'])){
						$keywords = " Where option_name LIKE '%".$_POST['optionsearchfield']."%'";
					}
					else
					{
						$keywords = "";
					}
					
				}
				
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_menu_option_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }			   
				
		 $sql = "SELECT *"
		  . "\n FROM res_menu_option_master".$keywords. $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	  
	  /**
       * Menu::getMenuOptionChoice()
       * 
       * @return
       */
	  
	  public function getMenuOptionChoice($optionid)
	  {
		  global $db, $core, $pager;
		

		 $sql = "SELECT * "
		  . "\n FROM res_menu_option_choice_master"
		  ."\n WHERE option_id = '".$optionid."'";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::getToppingMenuItem()
       * 
       * @return
       */
	  public function getToppingMenuItem()
	  	{
			global $db,$core;
			
			$sql = "select m.id as MenuItemID,msm.id as MenuSizeMapID, (case m.item_type when 0 then m.id " 
			. "\n when 1 then m.id + ',' + msm.id end) as 'Menu_SizeID',(case m.item_type when 0 then CONCAT(m.item_name ,' - ', m.ticket_item_id , ' - $' , m.price) "
			. "\n when 1 then CONCAT(m.item_name ,' - ', m.ticket_item_id, ' - $', msm.price , ' - [ ', sm.size_name , ' ]' ) end) as 'price' from "
			. "\n res_menu_size_master  sm "
			. "\n left outer join res_menu_size_mapping msm on msm.size_id=sm.id and sm.active=1 "
			. "\n right outer join res_menu_item_master m on msm.menu_item_id=m.id"
			. "\n where m.item_type != 2 AND m.show_item_in_option=1 AND m.active=1 ";
			
			
			$row = $db->fetch_all($sql);
			return ($row) ? $row : 0;
		}
		
		/**
       * Menu::getToppingMenuItem()
       * 
       * @return
       */
	  public function getOnchangeItem()
	  	{
			global $db,$core;
			
			$sql = "select m.id as MenuItemID,msm.id as MenuSizeMapID, (case m.item_type when 0 then m.id " 
			. "\n when 1 then m.id + ',' + msm.id end) as 'Menu_SizeID',(case m.item_type when 0 then CONCAT(m.item_name ,' - ', m.ticket_item_id , ' - $' , m.price) "
			. "\n when 1 then CONCAT(m.item_name ,' - ', m.ticket_item_id, ' - $', msm.price , ' - [ ', sm.size_name , ' ]' ) end) as 'price' from "
			. "\n res_menu_size_master  sm "
			. "\n left outer join res_menu_size_mapping msm on msm.size_id=sm.id and sm.active=1 "
			. "\n right outer join res_menu_item_master m on msm.menu_item_id=m.id"
			. "\n where m.item_type != 2 AND m.show_item_in_option=1 AND m.active=1 ";
			
			
			$row = $db->fetch_all($sql);
			return ($row) ? $row : 0;
		}
		
	   /**
       * Menu::getMenuOptionDropDown()
       * 
       * @return
       */
		public function getMenuOptionDropDown($menuitemid)
		{
			global $db, $core;
			
			 $sql = "SELECT option_id, option_name "
			
			."\n FROM res_menu_option_master"
			
			."\n WHERE option_id NOT IN (SELECT item_option_id FROM `res_menu_option_item_mapping` WHERE menu_item_id ='".$menuitemid."')"; 
			
			
						
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}
		
		 /**
       * Menu::getMenuOptionDropDown_previous()
       * 
       * @return
       */
		public function getMenuOptionDropDownAdd()
		{
			global $db, $core;
			
			$sql = "SELECT * "
			
			."\n FROM res_menu_option_master"; 
			
			
						
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}
		
		
		
		/**
       * Menu::getoptionchoiselist()
       * 
       * @return
       */
		public function getoptionchoiselist($optionid)
		{
			global $db, $core;
			
			$sql = "SELECT *"
			."\n FROM res_menu_option_choice_master"
			."\n WHERE option_id = '".$optionid."'";
			
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}
		
		/**
       * Menu::getoptionchoiselist()
       * 
       * @return
       */
		public function getTopingOptionList($optionid)
		{
			global $db, $core;
			
			 $sql = "SELECT *"
			."\n FROM res_menu_option_topping_master"
			."\n WHERE option_id = '".$optionid."'";
			
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}
	  /**
       * Menu::getoptionchild()
       * 
       * @return
       */
	  
	  public function getoptionchild($itemoption_id)
	  {
		  global $db, $core, $pager; 
			  
		$sql = "SELECT *"
		  . "\n FROM `res_menu_option_master`"
		  . "\n where option_id in(select p_itemoption_id from res_menu_option_group where item_option_id ='".$itemoption_id."')";			
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::getaddoptionmenu()
       * 
       * @return
       */
	  
	  public function getaddoptionmenu($itemid)
	  {
		  global $db, $core; 
			  
		 $sql = "SELECT mim.item_name, mm.menu_name, mcm.category_name"
			."\n FROM `res_menu_item_master` AS mim"
			."\n LEFT JOIN res_category_master AS mcm ON mcm.id = mim.category_id"
			."\n LEFT JOIN res_menu_master AS mm ON mm.id = mcm.menu_id"
			."\n WHERE mim.id ='".$itemid."'";			
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::procesMenuItemOptionMapping()
       * 
       * @return
       */
	  public function procesMenuItemOptionMapping()
	  {
		  error_reporting(0);
		  global $db, $core, $wojosec;		    
		  
		     if (empty($_POST['item_option_id']))
			  $core->msgs['item_option_id'] = "Please Select Your item Option Name";
			  			  
			 if ($this->itemOptionIdExists($_POST['item_option_id'], $_POST['menu_item_id']))
				  $core->msgs['item_option_id'] = "This option is already exists";
				  
				  
			  /*echo "<pre>";
			  print_r($_POST['sizedprice']);
			  exit();	*/	
		      
		  if (empty($core->msgs)) {
			  
			  			  
			  $data = array(			    
				   'menu_item_id' => sanitize($_POST['menu_item_id']),				   
				   'item_option_id' => sanitize($_POST['item_option_id']),
				   'display_order' => sanitize($_POST['display_order'])				   
			  );
			  	 
			   $db->insert("res_menu_option_item_mapping", $data);	
			   //return "1";		
			   
			   	  
			 $message = "Add option to item added successfully";
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_coupon_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);			 
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Menu::getaddoptionmenu()
       * 
       * @return
       */
	  
	  public function getMenuOptionListAtItem($itemid)
	  {
		  global $db, $core; 
			  
		  $sql = "SELECT mom.option_id, mom.option_name,mom.instruction,mom.min_choise,mom.max_choise,moim.item_option_id,moim.menu_option_map_id,mom.size_affect_price,moim.display_order"
			."\n FROM `res_menu_option_item_mapping` AS moim"			
			."\n INNER JOIN res_menu_option_master AS mom ON mom.option_id = moim.item_option_id"
			."\n WHERE moim.menu_item_id ='".$itemid."'";
						
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  /**
       * Menu::getaddoptionmenu()
       * 
       * @return
       */
	  
	  public function getMenuOptionToppingList($optionid)
	  {
		  global $db, $core; 
			  
		 $sql = "SELECT option_topping_id,topping_name,out_of_stock"
			."\n FROM `res_menu_option_topping_master`"
			."\n WHERE option_id ='".$optionid."'";			
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	  public function optionListItem($menuitem)
	  {
	  	global $db;
	  	$sql = "SELECT mom.option_name"
			."\n FROM `res_menu_option_item_mapping` AS moim"
			."\n LEFT JOIN res_menu_option_master AS mom ON mom.option_id = moim.item_option_id"
			."\n WHERE `menu_item_id` ='".$menuitem."'";
	   $rowdata = $db->fetch_all($sql);
	  if($rowdata):
	  $optionname = "";
	  foreach($rowdata as $row):
	  	$optionname .=  $row['option_name'].',';
		endforeach;
		echo rtrim($optionname, ',');
	 endif;
	  }
	  
 /************************* Front end function start here************************************************************ */
	  
	  /**
       * Menu::checkFlow()
       * 
       * @return
       */
	  public function checkFlow($websiteurl)
	  {
	  	global $db;
	  	$sql  = "SELECT `flow`,hybrid, test_mode,default_location,hours_notes,count(`id`)as total "
				."\n FROM `res_install_master`"
				."\n WHERE website_url = '".$websiteurl."'";
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	  }
	  /**
       * Menu::locationIdByMenu()
       * 
       * @return
       */
	   public function locationIdByMenu($websiteurl)
	  {
	  	global $db;
	  	
		 $sql  = "SELECT `default_location` AS location"
				."\n FROM `res_install_master`"
				."\n WHERE website_url = '".$websiteurl."'";
				
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::categotyfront()
       * 
       * @return
       */
	  public function categotyfront($locationid)
	  {
	 	 global $db;
	  	$sql = "SELECT id ,category_name,category_dec"
				."\n FROM `res_category_master`"
				."\n WHERE `menu_id` IN (SELECT `menu_id` FROM `res_menu_location_mapping` WHERE `location_id` ='".$locationid."')"
				."\n AND active = 1 ORDER BY display_order, category_name";
		$row = $db->fetch_all($sql);
		return ($row) ? $row : 0;
	  }	  	  
	   	  
	   /**
       * Menu::productnameByCat()
       * 
       * @return
       */
	  public function productnameByCat($catid,$availabledays,$availabilityTime,$have_time_menu)
	  {
	  	global $db;
	   	/*$sql = "SELECT id, item_name,item_description,thumb_item_image"
		    . "\n FROM res_menu_item_master"
		    ."\n WHERE category_id = '".$catid."' && show_item_in_menu =1 && out_of_stack = 0"
			."\n AND active = 1 ORDER BY display_order, item_name";*/
		
		if($have_time_menu==1)
		{
			$adsql = "";
		$errors = array_filter($availabilityTime);
		
		if(!empty($errors))
		{
			if($availabilityTime['breakfast']=='Breakfast')
			{
				$adsql .= "temp.breakfast = 1";
			}
			if($availabilityTime['lunch'] =='lunch')
			{
				$adsql .= ($adsql) ?  "|| temp.lunch = 1" :  "temp.lunch = 1";
			}
			if($availabilityTime['dinner'] =='dinner')
			{
				
				$adsql .= ($adsql) ?  "|| temp.dinner = 1" :  "temp.dinner = 1";
			}	
			$adsql ="&& (".$adsql.")";
			$sql = "SELECT temp.id, temp.item_name,temp.item_description,temp.thumb_item_image,temp.special_menu_icon,"
		    			. "\n IF(`item_type` = '1', (SELECT msm.id FROM res_menu_size_mapping AS ms LEFT JOIN res_menu_size_master AS  msm ON msm.id=ms.size_id WHERE ms.menu_item_id = temp.id ORDER BY price DESC LIMIT 1),  0) as sizedid"
		    			. "\n FROM res_menu_item_master AS temp "
		    			. "\n WHERE temp.category_id = '".$catid."' "
		    			. "\n &&  FIND_IN_SET('".$availabledays."',temp.`available_days`) && temp.show_item_in_menu =1 && temp.out_of_stack = 0 AND temp.active = 1"
		    			. "\n ORDER BY temp.display_order, temp.item_name";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
				
		}
		else
		{
			 return 0;
		}
		}
		else
		{
			$sql = "SELECT temp.id, temp.item_name,temp.item_description,temp.thumb_item_image,temp.special_menu_icon,"
		    			. "\n IF(`item_type` = '1', (SELECT msm.id FROM res_menu_size_mapping AS ms LEFT JOIN res_menu_size_master AS  msm ON msm.id=ms.size_id WHERE ms.menu_item_id = temp.id ORDER BY price DESC LIMIT 1),  0) as sizedid"
		    			. "\n FROM res_menu_item_master AS temp "
		    			. "\n WHERE temp.category_id = '".$catid."' "
		    			. "\n &&  FIND_IN_SET('".$availabledays."',temp.`available_days`) && temp.show_item_in_menu =1 && temp.out_of_stack = 0 AND temp.active = 1 ".$adsql.""
		    			. "\n ORDER BY temp.display_order, temp.item_name";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		}
		
		
		 
	  } 

	  
	   /**
       * Menu::categoryName()
       * 
       * @return
       */
	  public function categoryName($catid)
	  {
	  	global $db;
	  	$sql = "SELECT id,  `category_name`, category_image,category_dec "
		    . "\n FROM res_category_master"
		    ."\n WHERE id = '".$catid."' && active = 1";
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  }
	  
	   public function productByCatWithchild($catid)
		  {
		  	global $db;
			
			 $sql = "SELECT *"
		   		   . "\n FROM res_category_master"
		    	   . "\n  WHERE id	IN (SELECT id FROM res_category_master	WHERE child_node LIKE '%,".$catid.",%' AND active = 1)"
		    	   . "\n ORDER BY `id` ASC"
		    	   . "\n LIMIT 0 , 30";
				   
				$row = $db->fetch_all($sql);
				return ($row) ? $row : 0;
		  }
	  
	   /**
       * Menu::productnameByCat()
       * 
       * @return
       */
	  public function productByItem($productid)
	  {
	  	global $db;
		
	  	$sql = "SELECT id,item_name,item_description,item_type,price,show_image_in_menu"
		    . "\n FROM res_menu_item_master"
		    . "\n WHERE id = '".$productid."' && active = 1 && show_item_in_menu =1 && out_of_stack = 0";
			
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  }
	  /**
       * Menu::optionListByItem()
       * 
       * @return
       */
	  public function optionListByItem($menuitem)
	  {
	  	global $db;
	  	$sql = "SELECT mom.option_id, mom.display_option,mom.instruction,mom.is_normal_option,mom.option_type , mom.min_choise, mom.max_choise,mom.is_default,mom.hide_option,mom.size_affect_price,moim.menu_option_map_id,mom.instruction_desc,mom.is_special_calc,mom.click_to_open,mom.hide_instruction,moim.display_order"
			."\n FROM `res_menu_option_item_mapping` AS moim"
			."\n LEFT JOIN res_menu_option_master AS mom ON mom.option_id = moim.item_option_id"
			."\n WHERE `menu_item_id` ='".$menuitem."' ORDER BY moim.display_order";
	   $row = $db->fetch_all($sql);
	   return ($row) ? $row : 0;
	  
	  }
	   /**
       * Menu::sizeByItem()
       * 
       * @return
       */
	  public function sizeByItem($itemid)
	  {
	  	global $db;
		
	  	$sql = "SELECT ms.price,msm.size_name,msm.size_image,ms.id as menusizemapid,msm.id as sizeid"
		    . "\n FROM res_menu_size_mapping AS ms"
			."\n LEFT JOIN res_menu_size_master AS  msm ON msm.id=ms.size_id"
		    ."\n WHERE ms.menu_item_id = '".$itemid."' ORDER BY price desc ";
			
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	  /**
       * Menu::topingByItem()
       * 
       * @return
       */
	  public function topingByItem($optionid)
	  {
	  	global $db;
		
	  	/*$sql = "SELECT motm.* , mom.min_choise, mom.max_choise,mom.option_id "
		    ."\n FROM  `res_menu_option_topping_master` AS motm "
			."\n LEFT JOIN res_menu_option_master AS mom  ON motm.option_id=mom.option_id"
		    ."\n WHERE motm.option_id = '".$optionid."'";*/
			
			
		$sql = "SELECT temp.min_choise, temp.max_choise, temp.price, temp.option_id,temp.option_topping_id,temp.topping_name,temp.is_default,temp.display_order"
		    ."\n FROM ("
		    ."\n SELECT rm.id, mom.min_choise, mom.max_choise, rm.price, mom.option_id,motm.option_topping_id,motm.topping_name,motm.is_default,motm.display_order"
		    ."\n FROM `res_menu_option_topping_master` AS motm"
		    ."\n INNER JOIN res_menu_option_master AS mom ON motm.option_id = mom.option_id"
		    ."\n INNER JOIN res_menu_item_master AS rm ON rm.id = motm.menu_item_id"
		    ."\n WHERE rm.item_type =0"
		    ."\n UNION"
		    ."\n SELECT 0 AS id, mom.min_choise, mom.max_choise, rmm.price, mom.option_id,motm.option_topping_id,motm.topping_name,motm.is_default,motm.display_order"
		    ."\n FROM `res_menu_option_topping_master` AS motm"
		    ."\n INNER JOIN res_menu_size_mapping rmm ON motm.menu_size_map_id = rmm.id"
		    ."\n INNER JOIN res_menu_option_master AS mom ON motm.option_id = mom.option_id"
		    ."\n) AS temp"
		    ."\n WHERE temp.option_id = '".$optionid."' ORDER BY temp.display_order";
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	  
	   /**
       * Menu::optionMain()
       * 
       * @return
       */
	  public function optionMain($itemid)
	  {
	  	global $db;
	  	$sql = "SELECT *"
		    ."\n FROM `res_menu_option_master`"
		    ."\n WHERE `option_id` in(SELECT item_option_id FROM `res_menu_option_item_mapping` WHERE `menu_item_id`='".$itemid."') AND is_normal_option=0";
	   $row = $db->fetch_all($sql);
	   return ($row) ? $row : 0;
	  
	  }
	  /**
       * Menu::optionchild()
       * 
       * @return
       */
	   public function optionchild($item_optionid)
	  {
	  	global $db;
	  	/*$sql = "SELECT `option_id`,`option_name`,`instruction` FROM `res_menu_option_master` WHERE `option_id` IN(SELECT p_itemOption_id FROM `res_menu_option_group` WHERE `item_option_id` ='".$item_optionid."')";*/
			
			$sql = "SELECT rsom.`option_id`,rsom.`option_name`,rsom.`instruction`,rmog.display_order "
		    ."\n FROM `res_menu_option_master` as rsom"
		    ."\n INNER JOIN `res_menu_option_group` as rmog ON rsom.option_id=rmog.p_itemoption_id "
		    ."\n WHERE rmog.`item_option_id`=".$item_optionid.""
		    ."\n ORDER BY rmog.display_order ASC";
			
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	   /**
       * Menu::gettoopinglist()
       * 
       * @return
       */
	  public function gettoopinglist($optionid)
	  {
	  	global $db;
	  	$sql ="SELECT *"
		    ."\n FROM `res_menu_option_master`"
		    ."\n WHERE `option_id` ='".$optionid."'";
			$row = $db->fetch_all($sql);
			return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::isNormalOption()
       * 
       * @return
       */
	   public function isNormalOption($optionid)
	  {
	  	global $db;
	  	 $sql ="SELECT option_id,is_normal_option"
		    ."\n FROM `res_menu_option_master`"
		    ."\n WHERE `option_id` ='".$optionid."'";
			$row = $db->first($sql);
			$rowdata = $row['is_normal_option'];
			if($rowdata==1)
			{
				return true;
			}
			else
			{
				return false;
			}
			//return ($row) ? $row['is_normal_option'] : 0;
	  }
	  
	    /**
       * Menu::getToppingPrice()
       * 
       * @return
       */
	  public function getToppingPrice($toppingid)
	  {
	  	global $db;
	  	$sql ="SELECT id, option_topping_id, temp.price"
		    ."\n FROM ( SELECT rm.id, rmm.option_topping_id, price"
		    ."\n FROM res_menu_item_master rm"
		    ."\n INNER JOIN res_menu_option_topping_master rmm ON rm.id = rmm.menu_item_id"
		    ."\n WHERE rm.item_type =0"
		    ."\n UNION"
		    ."\n SELECT 0 AS id, rm.option_topping_id, rmm.price"
		    ."\n FROM res_menu_option_topping_master rm"
		    ."\n INNER JOIN res_menu_size_mapping rmm ON rm.menu_size_map_id = rmm.id) AS temp"
		    ."\n WHERE temp.option_topping_id='".$toppingid."'";
			$row = $db->first($sql);
			return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::locationListByMenu()
       * 
       * @return
       */
	   public function locationListByMenu($websiteurl)
	  {
	  	global $db;
		
	  	$sql  = "SELECT `location_id`  AS location ,latitude,longitude,zoom_level,flow,hybrid, test_mode,default_location"
				."\n FROM `res_install_master`"
				."\n WHERE website_url = '".$websiteurl."'";				
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	  }
	  
	  
	  /**
       * Menu::OrderType()
       * 
       * @return
       */
	   public function OrderType($websiteurl)
	  {
	  	global $db;
	  	$sql  = "SELECT `pick_up`,`delivery`,`dineln`,`notes`"
				."\n FROM `res_install_master`"
				."\n WHERE website_url = '".$websiteurl."'";
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	  }
	  /**
       * Menu::pickUpLocation()
       * 
       * @return
       */
	   public function pickUpLocation($locationid)
	  {	  	
	  	global $db;
			$location = join(',',$locationid);
	  	  $sql  = "SELECT `id`,`location_name` "
				."\n FROM `res_location_master`"
				."\n WHERE `id` IN ($location) && active = 1";
		 $row = $db->fetch_all($sql);
		 return ($row) ? $row : 0;
	  }
	   /* Menu::gettoopinglist()
       * 
       * @return
       */
	  public function ToppingTotalPrice($sessionID)
	  {
	  	global $db;
		//$sessionID = SESSION_COOK;	
	  	/*$sql ="SELECT id, option_topping_id, temp.price,basketSession ,temp.totalprice
						FROM (
						SELECT rm.id, rmm.option_topping_id, price,SUM(price) as totalprice,rbt.basketSession
						FROM res_menu_item_master rm
						INNER JOIN res_menu_option_topping_master rmm ON rm.id = rmm.menu_item_id
						INNER JOIN res_basket_topping rbt ON rbt.option_topping_id=rmm.option_topping_id
						WHERE rm.item_type =0
						
						UNION 
						
						SELECT 0 AS id, rm.option_topping_id, rmm.price,SUM(rmm.price*rbt.qty) as totalprice,rbt.basketSession
						FROM res_menu_option_topping_master rm
						INNER JOIN res_menu_size_mapping rmm ON rm.menu_size_map_id = rmm.id
						INNER JOIN res_basket_topping rbt on rbt.option_topping_id=rm.option_topping_id
						) AS temp
						WHERE temp.basketSession='".$sessionID."'";*/
						
		 	$sql = "SELECT id, option_topping_id, temp.price ,SUM(temp.price*temp.qty)as totalprice,temp.qty"
		    ."\n FROM ( SELECT rm.id, rmm.option_topping_id, rm.price,rbt.qty ,rbt.basketSession"
		    ."\n FROM res_menu_item_master rm"
		    ."\n INNER JOIN res_menu_option_topping_master rmm ON rm.id = rmm.menu_item_id"
		    ."\n INNER JOIN res_basket_topping rbt ON rbt.option_topping_id=rmm.option_topping_id"
		    ."\n WHERE rm.item_type =0"
		    ."\n UNION SELECT 0 AS id, rm.option_topping_id, rmm.price,rbt.qty,rbt.basketSession"
		    ."\n FROM res_menu_option_topping_master rm"
		    ."\n INNER JOIN res_menu_size_mapping rmm ON rm.menu_size_map_id = rmm.id"
		    ."\n INNER JOIN res_basket_topping rbt on rbt.option_topping_id=rm.option_topping_id"
		    ."\n ) AS temp"
		    ."\n WHERE temp.basketSession = '".$sessionID."'";
			$row = $db->fetch_all($sql);
			return ($row) ? $row : 0;
	  }
	  
	  
	  
	  /**
       * Menu::DeliveryLocation()
       * 
       * @return
       */
	   public function DeliveryLocation($locationid)
	  {	  	
	  	global $db;
			$location = join(',',$locationid);
	  	  $sql  = "SELECT `id`,`location_name` FROM"
				."\n `res_location_master`"
				."\n WHERE `id` IN ($location) && active = 1";
		 $rowdata = $db->fetch_all($sql);
		 if($rowdata)
		 	{
				$resultstr = array();
				foreach ($rowdata as $row) {
				  $resultstr[] = $row['id'];
				}
				$result = implode(",",$resultstr);
				return $result;
			}		 
	  }
	  
	 public function getcoordDeliverymaps($locationid)
	  {
	  	global $db, $core;
		$sql = "SELECT lg.*,l.location_name"
			."\n FROM res_location_google_cordinates AS lg"
			."\n LEFT JOIN `res_location_master` AS l ON l.id = lg.location_id"
			."\n WHERE location_id IN ($locationid) && l.active=1";
		$row = $db->fetch_all($sql);
		return ($row)? $row : 0;
	  }
	  /*
	  *function Menu::AllCoordinates()
	  *@return: all coordinates for location wise flow
	  */
	  public function AllCoordinates($location)
	  {
	  	global $db, $core;
		
		/*$sql = "SELECT GROUP_CONCAT(data SEPARATOR ', ') AS allcoord "
			."\n FROM res_location_google_cordinates";
		$row = $db->first($sql);*/
		
		$sql = "SELECT data AS allcoord, location_id AS location, type "
			."\n FROM res_location_google_cordinates WHERE location_id IN (" . implode(",", $location) . ") ";
			
		$row = $db->fetch_all($sql);
		return ($row)? $row : 0;
	  }
	  
	  /*
	  *function Menu::CategoryMenuSubMenu()
	  *@paqram: $locationid
	  */
	  public function CategoryMenuSubMenu($locationid)
	  {
		  global $db, $core;		  
		 $sql = "SELECT `rcm`.`id`, `rcm`.`category_name` AS `menu`, ("
				."\n SELECT GROUP_CONCAT( CONCAT( temp.`id`, '||', UPPER(temp.`item_name`), '||', (IF( temp.`item_type` = '1', (
						SELECT msm.id
						FROM res_menu_size_mapping AS ms
						LEFT JOIN res_menu_size_master AS msm ON msm.id = ms.size_id
						WHERE ms.menu_item_id = temp.id  ORDER BY price desc LIMIT 1
						), 0 )) )"
				."\n SEPARATOR '%%' )"
				."\n FROM `res_menu_item_master` AS temp"
				."\n WHERE temp.`category_id` = `rcm`.`id` && temp.`show_item_in_menu` = '1' && temp.`out_of_stack` = '0' && temp.`item_name` != '' "
				."\n AND temp.`active` = '1'"
				."\n ORDER BY temp.`display_order`, temp.`item_name`"
				."\n ) AS `sub`"
				."\n FROM `res_category_master` AS `rcm`"
				."\n WHERE `rcm`.`menu_id`"
				."\n IN ("
				."\n SELECT `menu_id`"
				."\n FROM `res_menu_location_mapping`"
				."\n WHERE `location_id` = '" . (int)$locationid . "'"
				."\n )"
				."\n AND `active` =1 AND `parent_id` = 0"
				."\n ORDER BY `display_order`, `category_name`";
				
			$row = $db->fetch_all($sql);
			return ($row) ? $row : 0;
	  }	  
	  
	   /*
	  *function Menu::getPreprationtime()
	  */
	  public function getPreprationtime($location)
	  {
	  	global $db, $core;
				
		$sql = "SELECT `pickup_time`,`delivery_time` "
			."\n FROM res_location_master WHERE id = '".$location."'";
		$row = $db->first($sql);
		return ($row)? $row : 0;
	  }
	  
	   /*
	  *function Menu::isholiday()
	  */
	  public function isholiday($location,$postdate)
	  {
	  	global $db, $core;				
		 $sql = "SELECT * FROM `res_holiday_master`"
			."\n WHERE `location_id`='".$location."' && `holiday_date` = '".$postdate."'";		
		$row = $db->first($sql);
		return ($row)? $row : 0;
	  }
	  
	  
	   /*
	  *function Menu::getLatituteLongitute()
	  */
	  public function getLatituteLongitute($locationid)
	  {
	  	global $db, $core;
		
		$location = join(',',$locationid);
				
		$sql = "SELECT `latitude`,`longitude`,`address1`"
			."\n FROM `res_location_master`"
			."\n WHERE `id` IN($location) && active=1";
			
		$row = $db->fetch_all($sql);
		return ($row)? $row : 0;
	  }
	  
	  /**
	  * function Menu::getOptionChoice()
	  */
	  public function getOptionChoice($optionid)
	  {
	  	global $db, $core;		
				
		$sql = "SELECT `option_choice_id`,`option_id`,`choice_name`,`choice_image`"
			."\n FROM `res_menu_option_choice_master` "
			."\n WHERE `option_id` = '".$optionid."'";
			
		$row = $db->fetch_all($sql);
		return ($row)? $row : 0;
	  }
	  
	  
	  
	  
	   /**
       * Menu::OrderTypeMobile()
       * 
       * @return
       */
	  
	   public function OrderTypeMobile($websiteurl = NULL)
	  {
	  	global $db;
	  	$sql  = "SELECT `pick_up`,`delivery`,`dineln`,`notes`"		
				."\n FROM `res_install_master`";
				
				//."\n WHERE website_url = '".$websiteurl."'";
				
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	  }
	  /**
       * Menu::pickUpLocationMobile()
       * 
       * @return
       */
	   public function pickUpLocationMobile($locationid =NULL)
	  {	  	
	  	global $db;
			$location = join(',',$locationid);
	  	  echo  $sql  = "SELECT `id`,`location_name` FROM"
				."\n `res_location_master` WHERE `id` IN ($location) && active = 1";				
		 $row = $db->fetch_all($sql);
		 return ($row) ? $row : 0;
	  }
	  
	  /**
       * Menu::getaddoptionmenu()
       * 
       * @return
       */
	  
	  public function getMenuSizePriceitem($itemid)
	  {
		  global $db, $core; 
			  
		  $sql = "SELECT ms.size_name,ms.id as sizeid"
				."\n FROM `res_menu_size_mapping` AS msm"
				."\n LEFT JOIN `res_menu_size_master` AS ms ON ms.id = msm.size_id"
				."\n WHERE `menu_item_id` ='".$itemid."'";			
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;		  
	  }
	  
	   /**
       * Menu::procesMenuSizePriceitem()
       * 
       * @return
       */
	  public function procesMenuSizePriceitem()
	  {
		  global $db, $core, $wojosec;	
		       // echo "<pre>"; print_r($_POST); exit();
			  	foreach($_POST['sizedprice'] as $val)
				{								
					$meniitemmapid  = $val['meniitemmapid'];
					 $mapOptionId =  $this->MenuoptionMapIdExists($val['meniitemmapid']);
					 if($mapOptionId == 1){
						 $db->delete("res_menu_size_price", "menu_option_map_id='" . $val['meniitemmapid']. "'");
					 }					
					$sizeid = $val['sizeid'];
					$price  = $val['price'];
					$length = count($val['price']);
					if($length > 0)
					{
						if(!is_array($_POST['sizedprice']))
						{					
						  $data = array(
								  'size_id'=> $sizeid,
								  'menu_option_map_id' => $meniitemmapid,
								  'price_diff' => $price
							  );
						$db->insert("res_menu_size_price", $data);
						}				
						else
						{		
							for($i=0; $i< $length; $i++)
							{
						
							$data1 = array(
								  'size_id'=> $sizeid[$i],
								  'menu_option_map_id' => $meniitemmapid,
								  'price_diff' => $price[$i]
							  );		
						   $db->insert("res_menu_size_price", $data1);
						}
						
						}
					
					}
				}				
	       }
	  
	   /**
	   * User::MenuoptionMapIdExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function MenuoptionMapIdExists($mapId)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT menu_option_map_id" 
		  . "\n FROM res_menu_size_price" 
		  . "\n WHERE menu_option_map_id = '" . sanitize($mapId) . "'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	   /**
	   * User::ItemoptionMapIdExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function itemOptionIdExists($itemOptionId,$menu_item_id)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT item_option_id" 
		  . "\n FROM res_menu_option_item_mapping" 
		  . "\n WHERE item_option_id = '" . sanitize($itemOptionId) . "' and menu_item_id ='".$menu_item_id."'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
       * Menu::getoptionpricediff()
       * 
       * @return
       */
	  public function getoptionpricediff($sizeid,$menuoptionmapid)
	  {
	  	global $db;
	  	$sql = "SELECT `price_diff`" 
		  . "\n FROM `res_menu_size_price`" 
		  . "\n WHERE `menu_option_map_id` = '".$menuoptionmapid."' && `size_id`= '".$sizeid."'";
	   $row = $db->first($sql);
	   return ($row) ? $row : 0;
	  
	  }
	  
	   /**
       * Menu::getoptionpricediffDisplay()
       * 
       * @return
       */
	  public function getoptionpricediffDisplay($menuoptionmapid,$sizeid)
	  {
	  	global $db;
	  	$sql = "SELECT `price_diff`" 
		  . "\n FROM `res_menu_size_price`" 
		  . "\n WHERE `menu_option_map_id` = '".$menuoptionmapid."' && `size_id`= '".$sizeid."'";
		  
	   $row = $db->first($sql);
	   return ($row) ? $row : 0;
	  
	  }
	
	/**
       * Menu::notificationemail()
       * 
       * @return
       */
	  public function notificationemail()
	  {
	  	global $db;
	  	$sql = "SELECT `location_id`,`postcount`,`posxml`,`created_date`" 
		  . "\n FROM `res_order_master` where postcount=0";
		  
	   $row = $db->fetch_all($sql);
	   return ($row) ? $row : 0;
	  
	  }  
	  
	  /**
       * Menu::getSubCatTree()
       * 
       * @return
       */
	  public function getSubCatTree($parentid)
	  {
	  	global $db;
	  	$sql = "SELECT *" 
		  . "\n FROM `res_category_master`" 
		  . "\n where `parent_id` ='".$parentid."'";
		  
	   $row = $db->fetch_all($sql);
	   return ($row) ? $row : 0;
	  
	  } 
	  
	   /**
       * Menu::OrderAvailabilityTime()
       * 
       * @return
       */
	  public function OrderAvailabilityTime($locationid,$day,$time)
	  {
	  	global $db;				 
				
				$sql = "SELECT ("
		    	. "\n CASE WHEN time_format( str_to_date('".$time."', '%r' ) , '%T' ) >= time_format( str_to_date( breakfast_start, '%h:%i:%s %p' ) , '%T' )"
		    	. "\n AND time_format( str_to_date( '".$time."', '%r' ) , '%T' ) <= time_format( str_to_date( breakfast_last, '%r' ) , '%T' )"
		    	. "\n THEN 'Breakfast'"
		    	. "\n END"
		    	. "\n ) AS breakfast, ("
		    	. "\n CASE WHEN time_format( str_to_date( '".$time."', '%r' ) , '%T' ) >= time_format( str_to_date( launch_start, '%h:%i:%s %p' ) , '%T' )"
		    	. "\n AND time_format( str_to_date('".$time."', '%r' ) , '%T' ) <= time_format( str_to_date( launch_last, '%r' ) , '%T' )"
		    	. "\n THEN 'lunch'"
		    	. "\n END"
		    	. "\n ) AS lunch, ("
		    	. "\n CASE WHEN time_format( str_to_date( '".$time."', '%r' ) , '%T' ) >= time_format( str_to_date( dinner_start, '%h:%i:%s %p' ) , '%T' )"
		    	. "\n AND time_format( str_to_date('".$time."', '%r' ) , '%T' ) <= time_format( str_to_date( dinner_last, '%r' ) , '%T' )"
		    	. "\n THEN 'dinner'"
		    	. "\n ELSE ''"
		    	. "\n END"
		    	. "\n ) AS dinner"
		    	. "\n FROM res_location_time_master"
		    	. "\n WHERE location_id ='".$locationid."' AND days = '".$day."'";
				
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  } 
	  
	      /**
	   * Menu::SpecialMenuIcon()
	   * 
	   * @return
	   */
	  public function SpecialMenuIcon($special_menu_icon)
	  {
		  global $db, $core;		  
		
		 $sql = "SELECT * FROM `res_menu_icon` WHERE `special_id` IN ($special_menu_icon)";
		  	
          $row = $db->fetch_all($sql);          
		  return ($row) ? $row : 0;
	  }
	  
	   /**
	   * Menu::processMenuIconmaster()
	   * 
	   * @return
	   */
	  public function processMenuIconmaster()
	  {
		  global $db, $core, $wojosec;
		 
		  
		  if (empty($_POST['special_item_name']))
			  $core->msgs['special_item_name'] = "Provide menu icon name";
		
		if (empty($_FILES['avatar']['name'])) {
			 $core->msgs['avatar'] = "Provide Menu Icon Image"; 
		  }		 

		  if (!empty($_FILES['avatar']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['avatar']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }		 
		  
		  if (empty($core->msgs)) {
			 
			  $data = array(
			    
				  'special_item_name' => sanitize($_POST['special_item_name']),				  
				  'is_active' => intval($_POST['is_active'])
			  );
			
			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['avatar'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/menu_icon/';
			 // $als->NewWidth = $core->avatar_w;
			  //$als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->menuid) {
				  $avatar = getValue("special_item_icon","res_menu_icon","special_id = '".$this->menuid."'");
				  if (!empty($_FILES['avatar']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['special_item_icon'] = $name;
				  } else {
					  $data['special_item_icon'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['avatar']['name'])) 
				  $data['special_item_icon'] = $name;
			  }
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  //print_r($data);
			  ($this->menuid) ? $db->update("res_menu_icon", $data, "special_id='" . (int)$this->menuid . "'") : $db->insert("res_menu_icon", $data);
			  $message = ($this->menuid) ? "Menu Icon Updated successfully" : "Menu Icon added successfully";
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "Menu Icon") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	 /**
       * Menu::getMenuIconMaster()
       * 
       * @return
       */
	  
	  public function getMenuIconMaster()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_menu_icon");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }	  
		 

		 $sql = "SELECT * "
		   . "\n FROM res_menu_icon "
		  
		   . $pager->limit;	 
		    
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }   
	   /**
	   * Menu::getSpecialMenuListIds()
	   * 
	   * @return
	   */
	  public function getSpecialMenuListIds()
	  {
		  global $db, $core;		  
		
		  $sql = "SELECT * FROM `res_menu_icon` ";
		  	
          $row = $db->fetch_all($sql);          
		  return ($row) ? $row : 0;
	  }  
	  /**
	   * Menu::processMenuItemFromXML()
	   * 
	   * @return
	   */
	  public function processMenuItemFromXML($data = array())
	  {
			global $db, $core;		  
			
			$idata = array(
				'item_type' => $data['item_type'], 
				'category_id' => ($data['category_id']) ?  $this->gatCatIdforXML($data['category_id']) : 0 , 
				'item_name' => $data['item_name'], 
				'short_name' => $data['item_name'], 
				'item_description' => '', 
				'min_qty' => 0, 
				'max_qty' => 200, 
				'preparation_time' => '', 
				'out_of_stack' => '', 
				'show_item_in_menu' => 0, 
				'show_item_in_option' => 1, 
				'price' => $data['item_price'], 
				'pick_up' => 1, 
				'delivery' => 1, 
				'breakfast' => 1, 
				'lunch' => 1, 
				'dinner' => 1, 
				'dineln' => 1, 
				'big_item_image' => '', 
				'thumb_item_image' => '', 
				'show_image_in_menu' => '', 
				'active' => 1, 
				'available_days' => 'mon,tue,wed,thu,fri,sat,sun', 
				'ticket_item_id' => $data['item_id'], 
				'menu_type' => $data['menu_type'], 
				'size_id' => $data['size_id'], 
				'show_nutrition_info' => '', 
				'nutrition_ingredient' => '', 
				'display_order' => ''
			);			
			return $db->insert("res_menu_item_master1", $idata);
	  }  
	  /**
	   * Menu::processMenuSizeFromXML()
	   * 
	   * @return
	   */
	  public function processMenuSizeFromXML($d = array())
	  {  
		  global $db, $core, $wojosec;	  
			
			$data = array(
				'size_name' => $d['size_name'], 
				'active' => 1,  
				'ticket_size_id' => $d['ticket_size_id'], 
				'created_date' => 'NOW()'
			);
			return $db->insert("res_menu_size_master1", $data);
	  }
	  /**
	   * Menu::processMenuItemSizeMappingFromXML()
	   * 
	   * @return
	   */
	  public function processMenuItemSizeMappingFromXML($data = array())
	  {
			global $db, $core;		  
			
			$idata = array(
				'menu_item_id' => $data['menu_item_id'], 
				'size_id' => ($data['size_id']) ?  $this->gatSizeIdforXML($data['size_id']) : 0 ,
				'price' => $data['price']
			);
			return $db->insert("res_menu_size_mapping1", $idata);
	  }
	  
	  /**
	   * Menu::processMenuCategoryFromXML()
	   * 
	   * @return
	   */
	  public function processMenuCategoryFromXML($data = array())
	  {  
		  global $db, $core, $wojosec;	
		
			$data = array(
				'menu_id' => 1, 
				'xml_cat_id' => $data['xml_cat_id'],
				'category_name' => $data['category_name'],  
				'parent_id' => 0, 
				'category_dec' => 0, 
				'category_image' => 0, 
				'display_order' => 0, 
				'parent_id' => 0, 
				'parent_id' => 0, 
				'active' => 1, 
				'created_date' => 'NOW()'
			);
			return $db->insert("res_category_master1", $data);
			$insert_id = $db->insertid();	
			if(!empty($insert_id))
			{
				 $data1['child_node'] =  ",".$insert_id.",";
				 $db->update("res_category_master1", $data1, "id='" . (int)$insert_id . "'");
			}
	  }
	  
	   /**
	   * Menu::gatcatidforXML()
	   * 
	   * @return
	   */
	  public function gatCatIdforXML($mcat_id = NULL)
	  {
		  global $db, $core;		  
		
		  $sql = "select id from"
		    	. "\n res_category_master1"
		    	. "\n where xml_cat_id= ".$mcat_id."";
		  	
          $row = $db->first($sql);          
		  return ($row) ? $row['id'] : 0;
	 }  
	 
	  /**
	   * Menu::gatSizeIdforXML()
	   * 
	   * @return
	   */
	  public function gatSizeIdforXML($ticket_size_id=NULL)
	  {
		  global $db, $core;
		  $sql = "select id from"
		    	. "\n res_menu_size_master1"
		    	. "\n where ticket_size_id= ".$ticket_size_id."";
		  	
          $row = $db->first($sql);          
		  return ($row) ? $row['id'] : 0;
	 }
	
		  /**
       * Menu::ordernotificationemail()
       * 
       * @return
       */
	  public function ordernotificationemail()
	  {
	  	global $db;
	  	$sql = "SELECT `location_id`,`postcount`,`posorderxml`,`created_date`" 
		  . "\n FROM `res_order_master` where postcount=0 order by created_date DESC";
		  
	   $row = $db->fetch_all($sql);
	   return ($row) ? $row : 0;
	  
	  } 
	  
	  /**
	   * Menu::have_time_menu()
	   * 
	   * @return
	   */
	  public function have_time_menu($location,$day)
	  {
		  global $db, $core;
		  $sql = "SELECT `have_time_menu`"
		    	. "\n FROM `res_location_time_master`"
		    	. "\n WHERE `location_id` = ".$location." && `days` ='".$day."'";
		  	
          $row = $db->first($sql);          
		  return ($row) ? $row : 0;
	 }
	  
	   
  }
?>