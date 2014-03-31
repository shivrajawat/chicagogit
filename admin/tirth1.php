<?php 
define("_VALID_PHP", true);
require_once("init.php");

$xml = simplexml_load_file('XML_SAMPLE_CHICAGO.xml');
/*---------------  size info list start here ----------- */
if($xml->size_info):										//check size_info node exist or not
	$size_info = $xml->size_info;
	if($size_info->size):								//check size node in modifier_info exist or not
		$size_set = $size_info->size;
		$data = array();
		foreach($size_set as $row):
			if($row->attributes()):								//check any attribute in size exist or not
				$size_attr = $row->attributes();
				$data['size_name'] = ($size_attr->name) ? $size_attr->name : '';
				$data['ticket_size_id'] = ($size_attr->id) ? $size_attr->id : '';
				
				echo $menu->processMenuSizeFromXML($data);			//insert to res_menu_size_master1 table from size list
			else:
		
			endif;
		endforeach;
	else:
		
	endif;
else:

endif;
?>