<?php
  /**
   * Search by location get lat long google map
   *
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  /* Check ggoolemap */

if ($_GET['action']== "getCoords")  
{
			$data = getCoords($_GET['address']);
			if (is_array($data['lat']) && $data['lat'][0] == 'NULL' && is_array($data['lng']) && $data['lng'][0] == 'NULL')
			{
				$data = array();
			}		
			$ss =  $json->jsonResponse($data);
}
?>