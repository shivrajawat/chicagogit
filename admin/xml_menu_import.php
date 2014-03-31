<?php 
define("_VALID_PHP", true);
require_once("init.php");


  /* process procesMenuItemXml */
  if (isset($_POST['procesMenuItemXml']))
      : if (intval($_POST['procesMenuItemXml']) == 0 || empty($_POST['procesMenuItemXml']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
 
 $allowedExts = array("xml");
 $ss = explode(".", $_FILES["menuitem_xml"]["name"]);
 $extension = end($ss);
if ((($_FILES["menuitem_xml"]["type"] == "text/xml")) && in_array($extension, $allowedExts))
  {
  if ($_FILES["menuitem_xml"]["error"] > 0)
    {
		 echo  $_FILES["menuitem_xml"]["error"];
    }
  else
    {		
		$newName = "XML" . randName();
		$ext = substr($_FILES['menuitem_xml']['name'], strrpos($_FILES['menuitem_xml']['name'], '.') + 1);
		$name = $newName.".".strtolower($ext);
 		move_uploaded_file($_FILES["menuitem_xml"]["tmp_name"],  "../uploads/menuitemxmlfile/" . $name);
		/*if (file_exists("../uploads/menuitemxmlfile/" . $_FILES["menuitem_xml"]["name"]))
		  {
		 	 echo $_FILES["menuitem_xml"]["name"] . " already exists. ";
		  }
		else
		  {*/
			 
			 
		 // }
		 
		    $xml_file = "../uploads/menuitemxmlfile/".$name;
			if(is_file($xml_file)):
			$xml_file = "".SITEURL."/uploads/menuitemxmlfile/".$name;
			
			$xml = simplexml_load_file($xml_file);
			/*---------------  size info list start here ----------- */
			if($xml->size_info):											//check size_info node exist or not
				$size_info = $xml->size_info;
				if($size_info->size):										//check size node in size_info exist or not
					$size_set = $size_info->size;
					$data = array();
					foreach($size_set as $row):
						if($row->attributes()):								//check any attribute in size exist or not
							$size_attr = $row->attributes();
							$data['size_name'] = ($size_attr->name) ? $size_attr->name : '';
							$data['ticket_size_id'] = ($size_attr->id) ? $size_attr->id : '';
							
							$menu->processMenuSizeFromXML($data);			//insert to res_menu_item_master table from size list
						else:
					
						endif;
					endforeach;
				else:
					
				endif;
			else:
			
			endif;
			/*---------------  size info list end here ----------- */
			
			/*---------------  Category info list start here ----------- */
			if($xml->category_info):											//check category_info node exist or not
				$category_info = $xml->category_info;
				if($category_info->menu_category):										//check size node in size_info exist or not
					$category_set = $category_info->menu_category;
					$datacat = array();
					foreach($category_set as $catrow):
						if($catrow->attributes()):								//check any attribute in size exist or not
							$category_attr = $catrow->attributes();
							$datacat['category_name'] = ($category_attr->name) ? $category_attr->name : '';
							$datacat['xml_cat_id'] = ($category_attr->id) ? $category_attr->id : '';
							$menu->processMenuCategoryFromXML($datacat);			//insert to res_menu_item_master table from size list
						else:
						endif;
					endforeach;
				else:
					
				endif;
			else:
			
			endif;
			/*---------------  Category info list end here ----------- */
			
			/*---------------  Modifier info list start here ----------- */
			if($xml->modifier_info):										//check modifer_info node exist or not
				$modifier_info = $xml->modifier_info;
				if($modifier_info->modifier):								//check modifer node in modifier_info exist or not
					$modifier_set = $modifier_info->modifier;
					$data1 = array();
					foreach($modifier_set as $row):
						if($row->attributes()):								//check any attribute in modifier exist or not
							$modifier_attr = $row->attributes();
							$data1['item_type'] = 0;
							$data1['category_id'] = 0;
							$data1['item_name'] = ($modifier_attr->name) ? $modifier_attr->name : '';
							$data1['short_name'] = ($modifier_attr->name) ? $modifier_attr->name : '';
							$data1['item_price'] = ($modifier_attr->amount) ? $modifier_attr->amount : '0.00';
							$data1['item_id'] = ($modifier_attr->id) ? $modifier_attr->id : '';
							$data1['menu_type'] = 'modifier';
							$data1['size_id'] = 0;
							$menu->processMenuItemFromXML($data1);			//insert to res_menu_item_master table from modifier list
						else:
					
						endif;
					endforeach;
				else:
					
				endif;
			else:
			
			endif;
			/*---------------  Modifier info list end here ----------- */
			
			/*---------------  pizza_info list start here ----------- */
			if($xml->pizza_info):										//check pizza_info node exist or not
				$pizza_info = $xml->pizza_info;
				if($pizza_info->pizza_size):								//check pizza_size node in pizza_info exist or not
					$pizza_size_set = $pizza_info->pizza_size;
					$data2 = array();
					foreach($pizza_size_set as $row):
						if($row->attributes()):								//check any attribute in pizza_size exist or not
							$pizza_size_attr = $row->attributes();
							$data2['size_name'] = ($pizza_size_attr->name) ? $pizza_size_attr->name : '';
							$data2['ticket_size_id'] = ($pizza_size_attr->id) ? $pizza_size_attr->id : '';
							$menu->processMenuSizeFromXML($data2);			//insert to res_menu_size_master table from pizza_size list
						else:
					
						endif;
					endforeach;
				endif;
				if($pizza_info->pizza_crust):								//check pizza_crust node in pizza_info exist or not
					$pizza_crust_set = $pizza_info->pizza_crust;
					$data3 = array();
					foreach($pizza_crust_set as $row):
						if($row->attributes()):								//check any attribute in pizza_crust exist or not
							$pizza_crust_attr = $row->attributes();
							$data3['item_type'] = 0;
							$data3['category_id'] = 0;
							$data3['item_name'] = ($pizza_crust_attr->name) ? $pizza_crust_attr->name : '';
							$data3['short_name'] = ($pizza_crust_attr->name) ? $pizza_crust_attr->name : '';
							$data3['item_price'] = '0.00';
							$data3['item_id'] = ($pizza_crust_attr->id) ? $pizza_crust_attr->id : '';
							$data3['menu_type'] = 'crust';
							$data3['size_id'] = 0;
							$menu->processMenuItemFromXML($data3);			//insert to res_menu_item_master table from pizza_crust list
						else:
					
						endif;
					endforeach;
				endif;
				if($pizza_info->pizza_sauce):								//check pizza_sauce node in pizza_info exist or not
					$pizza_sauce_set = $pizza_info->pizza_sauce;
					$data4 = array();
					foreach($pizza_sauce_set as $row):
						if($row->attributes()):								//check any attribute in pizza_sauce exist or not
							$pizza_sauce_attr = $row->attributes();
							$data4['item_type'] = 0;
							$data4['category_id'] = 0;
							$data4['item_name'] = ($pizza_sauce_attr->name) ? $pizza_sauce_attr->name : '';
							$data4['short_name'] = ($pizza_sauce_attr->name) ? $pizza_sauce_attr->name : '';
							$data4['item_price'] = '0.00';
							$data4['item_id'] = ($pizza_sauce_attr->id) ? $pizza_sauce_attr->id : '';
							$data4['menu_type'] = 'sauce';
							$data4['size_id'] = 0;
							$menu->processMenuItemFromXML($data4);			//insert to res_menu_item_master table from pizza_sauce list
						else:
					
						endif;
					endforeach;
				endif;
				if($pizza_info->pizza_cheese):								//check pizza_cheese node in pizza_info exist or not
					$pizza_cheese_set = $pizza_info->pizza_cheese;
					$data5 = array();
					foreach($pizza_cheese_set as $row):
						if($row->attributes()):								//check any attribute in pizza_cheese exist or not
							$pizza_cheese_attr = $row->attributes();
							$data5['item_type'] = 0;
							$data5['category_id'] = 0;
							$data5['item_name'] = ($pizza_cheese_attr->name) ? $pizza_cheese_attr->name : '';
							$data5['short_name'] = ($pizza_cheese_attr->name) ? $pizza_cheese_attr->name : '';
							$data5['item_price'] = '0.00';
							$data5['item_id'] = ($pizza_cheese_attr->id) ? $pizza_cheese_attr->id : '';
							$data5['menu_type'] = 'cheese';
							$data5['size_id'] = 0;
							$menu->processMenuItemFromXML($data5);			//insert to res_menu_item_master table from pizza_cheese list
						else:
					
						endif;
					endforeach;
				endif;
				if($pizza_info->pizza_topping):								//check pizza_topping node in pizza_info exist or not
					$pizza_topping_set = $pizza_info->pizza_topping;
					$data6 = array();
					foreach($pizza_topping_set as $row):
						if($row->attributes()):								//check any attribute in pizza_topping exist or not
							$pizza_topping_attr = $row->attributes();
							$data6['item_type'] = 0;
							$data6['category_id'] = 0;
							$data6['item_name'] = ($pizza_topping_attr->name) ? $pizza_topping_attr->name : '';
							$data6['short_name'] = ($pizza_topping_attr->name) ? $pizza_topping_attr->name : '';
							$data6['item_id'] = ($pizza_topping_attr->id) ? $pizza_topping_attr->id : '';
							$data6['menu_type'] = 'topping';
							$data6['size_id'] = 0;
							if($row->topping_size):
								$pizza_topping_size_set = $row->topping_size;
								$arr = array();					
								foreach($pizza_topping_size_set as $rows):
									$pizza_topping_size_attr = $rows->attributes();
									$arr[] = $pizza_topping_size_attr->amount;
								endforeach;
								sort($arr);
								$data6['item_price'] = (reset($arr)) ? reset($arr) : '0.00';
							endif;				
							$menu->processMenuItemFromXML($data6);			//insert to res_menu_item_master table from pizza_topping list
						else:
					
						endif;
					endforeach;
				endif;
			else:
			
			endif;
			if($xml->menu_info):										//check menu_info node exist or not
				$menu_info = $xml->menu_info;
				if($menu_info->menu_item):								//check menu_item node in menu_info exist or not
					$menu_item_set = $menu_info->menu_item;
					$data7 = array();
					foreach($menu_item_set as $row):
						if($row->attributes()):								//check any attribute in modifier exist or not
							$menu_item_attr = $row->attributes();
							$data7['category_id'] = ($menu_item_attr->mcat_id) ? $menu_item_attr->mcat_id : '';
							$data7['item_name'] = ($menu_item_attr->name) ? $menu_item_attr->name : '';
							$data7['short_name'] = ($menu_item_attr->name) ? $menu_item_attr->name : '';
							
							$data7['item_id'] = ($menu_item_attr->id) ? $menu_item_attr->id : '';
							
							if($menu_item_attr->type=="regular")	
								$data7['menu_type'] = 'MenuItem';
							else 
								$data7['menu_type'] = 'calculate';
							
							if($row->size):
								$menu_item_size_set = $row->size;
								if(count($menu_item_size_set) > 1):
									$data7['size_id'] = 0;
									$data7['item_type'] = 1;
									$data7['item_price'] = '0.00';
								else:
									$menu_item_size_attr1 = $menu_item_size_set->attributes();
									$data7['size_id'] = $menu_item_size_attr1->id;
									$data7['item_type'] = 0;
									$data7['item_price'] = $menu_item_size_attr1->amount;
								endif;
							endif;	
							$insert_itemid = $menu->processMenuItemFromXML($data7);			//insert to res_menu_item_master table from modifier list
							if(count($menu_item_size_set) > 1 && $insert_itemid):
								$arr2 = array();
								foreach($menu_item_size_set as $rows):
									$menu_item_size_attr = $rows->attributes();
									$arr2['menu_item_id'] = $insert_itemid;
									$arr2['size_id'] = $menu_item_size_attr->id;
									$arr2['price'] = $menu_item_size_attr->amount;
									$menu->processMenuItemSizeMappingFromXML($arr2);			//insert to res_menu_size_mapping table from menu_item list
								endforeach;	
							endif;
						else:
					
						endif;
					endforeach;
				else:
					
				endif;
			 echo "Success! XML upload successfully!";
			 			 
			else:
			
			endif;
			else:
				echo "file not found";
			endif;
    }
  }
else
  {
  echo "Invalid file";
  }

endif;
?>
