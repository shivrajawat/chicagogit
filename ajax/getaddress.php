<?php
  /**
   * Autofilup fields  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
 $location_id = $_GET['location_id']; 

  /*$sql = "SELECT `address1`,`latitude`,`longitude`,`phone_number`, (SELECT GROUP_CONCAT(CONCAT(days, ' ',day_start_time, ' - ', day_end_time) SEPARATOR '			              <br />') FROM  `res_location_time_master` WHERE location_id = '".$location_id."' && `days` != '') AS opentime FROM `res_location_master` 
			  WHERE id = '".$location_id."'";
   */

  $sql = "SELECT `address1`,`latitude`,`longitude`,`phone_number`,`zoom_level`, restorant_time AS opentime FROM `res_location_master` WHERE id = '".$location_id."'";
  
  $row1 = $db->first($sql);
     
  $address1 = $row1['address1'];  
  $latitude = $row1['latitude']; 
  $longitude = $row1['longitude'];
  $zoom_level = $row1['zoom_level'];
  $phone_number = $row1['phone_number']; 
  $opentime = cleanOut($row1['opentime']);
  
  $row = array("address1"=>$address1,"latitude"=>$latitude,"longitude"=>$longitude,"phone_number"=>$phone_number,"zoom_level"=>$zoom_level,"opentime"=>$opentime);
  
  $json = array(array('field' => 'address1', 
                    'value' => $row['address1']),
					array('field' => 'phone_number', 
                    'value' => $row['phone_number']),
					array('field' => 'latitude', 
                    'value' => $row['latitude']),
					array('field' => 'longitude', 
                    'value' => $row['longitude']));
					
echo json_encode($row); 
?>