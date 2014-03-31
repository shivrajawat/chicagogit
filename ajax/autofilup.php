<?php
  /**
   * Autofilup fields  
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
$company_id = $_GET['companyid']; 
$sql = "SELECT `address1`,`address2`,`city_id`,`state_id`,`country_id`,`zipcode`,`phone_number`,`phone_number1`,`fax_number` FROM `res_company_master` WHERE id = '".$company_id."'";
  $row = $db->first($sql);
  
$json = array(array('field' => 'address1', 
                    'value' => $row['address1']), 
            array('field' => 'address2', 
                    'value' => $row['address2']),
			array('field' => 'city_id', 
                    'value' => $row['city_id']),
			array('field' => 'state_id', 
                    'value' => $row['state_id']),
			array('field' => 'country_id', 
                    'value' => $row['country_id']),
			array('field' => 'zipcode', 
                    'value' => $row['zipcode']),
			array('field' => 'phone_number', 
                    'value' => $row['phone_number']),
					
			array('field' => 'phone_number1', 
                    'value' => $row['phone_number1']),
					
			array('field' => 'fax_number', 
                    'value' => $row['fax_number']));
echo json_encode($json );
?>