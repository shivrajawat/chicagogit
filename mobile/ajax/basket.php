<?php
  /**
   * Basket 
   * Add product in basket throw ajax
   * Kulacart 
   */
  define("_VALID_PHP", true);
  require_once("../../init.php");  
?>
<?php
$sessionID = SESSION_COOK;

if($_POST['action'] != '' || $_GET['action'] != '') {
	error_reporting(0);
	if($_POST['action'] == '')
	{
		$action 	= $_GET['action'];
		$productID	= $_GET['productID'];
		$menu_size_map_id = (empty($_GET['menu_size_map_id'])) ?  "0" : $_GET['menu_size_map_id'];
		$qty = $_GET['qty'];
		$option_topping_id = $_GET['topping'];
		$noJavaScript = 1;
	} else {
		$action 	= $_POST['action'];
		$productID	= $_POST['productID']; 
		$menu_size_map_id = (empty($_POST['menu_size_map_id'])) ?  "0" : $_POST['menu_size_map_id'];
		$qty = (empty($_POST['qty'])) ?  "0" : $_POST['qty'];
		$additional_notes = $_POST['additional_notes'];
		$option_topping_id = $_POST['topping'];
		$item_price = $_POST['item_price'];
		$noJavaScript = 0;
	}
}
  
if ($action == "addToBasket") {

		   		  
		    $data = array(
				  'productID'=> $productID,
				  'menu_size_map_id'=> $menu_size_map_id,
				  'qty'=> $qty, 
				  'productPrice'=> $item_price,
				  'basketSession' => $sessionID,
				  'additional_notes' => $additional_notes,
				  'created_date'=>"NOW()"
			  );
		     $db->insert("res_baskets", $data);
		     $insert_id = $db->insertid();			
			
		 if(!empty($insert_id)){
			foreach($option_topping_id as $val)
			{
				$option_id  = $val['id'];
				$option_topping_id = $val['val'];
				$topping_length = count($val['val']);
				$topping_price  = $val['price'];
				
				
				$option_topping_name = $val['option_topping_name'];
								
				if($topping_length > 0){
				if(!is_array($option_topping_id))
				{									
				  $data1 = array(
						  'product_id'=> $productID,
						  'option_topping_id' => $option_topping_id,
						  'option_topping_name' =>$option_topping_name,
						  'option_id'=>	$option_id,
						  'price'=>	$topping_price,		
						  'qty'=> $qty,		 
						  'basketID'=>$insert_id,
						  'basketSession' => $sessionID
					  );
					  
				$db->insert("res_basket_topping", $data1);
				
				}				
				else{
				
				for($i=0; $i< $topping_length; $i++)
				{
					 $data1 = array(
						  'product_id'=> $productID,
						 'option_topping_id' => $option_topping_id[$i],
						  'option_topping_name' =>$option_topping_name[$i],
						  'option_id'=>	$option_id,		
						  'price'=>	$topping_price[$i],	
						  'qty'=> $qty,	 
						  'basketID'=>$insert_id,
						  'basketSession' => $sessionID
					  );		
				$db->insert("res_basket_topping", $data1);
				}
					
					
				}
				
				}
			}
			}	  

	header("Location:restaurantmenu.php");
}
?>
 