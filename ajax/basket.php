<?php
  /**
   * Basket 
   * Add product in basket throw ajax
   * Kulacart 
   */
  define("_VALID_PHP", true);
  require_once("../init.php");

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
			$cartrowid = $_GET['cartrowid'];
			$noJavaScript = 1;
		} else {
			$action 	= $_POST['action'];
			$productID	= $_POST['productID']; 
			$menu_size_map_id = (empty($_POST['menu_size_map_id'])) ?  "0" : $_POST['menu_size_map_id'];
			$qty = (empty($_POST['qty'])) ?  "0" : $_POST['qty'];
			$additional_notes = $_POST['additional_notes'];
			$option_topping_id = $_POST['topping'];
			$item_price = $_POST['item_price'];
			$cartrowid = $_POST['cartrowid']; 
			$noJavaScript = 0;
		}
	}

	 if(!empty($cartrowid))
	  {
			$db->delete("res_baskets", "basketID='" . $cartrowid . "' AND basketSession = '" . $sessionID . "'");
			$db->delete("res_basket_topping", "basketID='" . $cartrowid . "' AND basketSession = '" . $sessionID . "'");
	  }

	if ($action == "addToBasket" && $qty!=0) {
					  
			   $data = array(
					  'productID'=> $productID,
					  'menu_size_map_id'=> $menu_size_map_id,
					  'qty'=> $qty, 
					  'productPrice'=> $item_price,
					  'basketSession' => $sessionID,
					  'additional_notes' => html_entity_decode( $additional_notes),
					  'created_date'=>"NOW()"
				  );
				 
				 $db->insert("res_baskets", $data);
				 $insert_id = $db->insertid();			
				
			 if(!empty($insert_id))
			 {
				foreach($option_topping_id as $val)
				{
				
					$option_id  = $val['id'];
					$option_topping_id = $val['val'];
					$topping_length = count($val['val']);
					$topping_price  = $val['price'];
					$option_choice_id = $val['option_choice_id'];
					
					$option_topping_name = $val['option_topping_name'];
					
					if($topping_length > 0){
					if(!is_array($option_topping_id))
					{					
					  $data1 = array(
							  'product_id'=> $productID,
							  'option_topping_id' => $option_topping_id,
							  'option_choice_id' => $option_choice_id,
							  'option_topping_name' =>$option_topping_name,
							  'option_id'=>	$option_id,
							  'price'=>	$topping_price,		
							  'qty'=> $qty,		 
							  'basketID'=>$insert_id,
							  'basketSession' => $sessionID
						  );
						  
					$db->insert("res_basket_topping", $data1);				
					}				
					else
					{					
						for($i=0; $i< $topping_length; $i++)
						{
						
							 $data1 = array(
								  'product_id'=> $productID,
								  'option_topping_id' => $option_topping_id[$i],
								  'option_choice_id' => $option_choice_id[$i],
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
			 
				$productName		= $row['item_name'];
			   
				if ($noJavaScript == 1) {
					
					header("Location: ../index.php");
					
				}			 
				else {
					
				$query2  = "SELECT * FROM res_baskets AS rb LEFT JOIN res_menu_item_master AS rmim ON rb.productID =rmim.id  WHERE rb.basketSession = '" . $sessionID . "' ORDER By basketID DESC";
				
				$row2 = $db->first($query2);
				$productName = $row2['item_name'];
				$qty =  $row2['qty'];
				$basketID = $row2['basketID'];
					 $message = "<div class=\"jquery-notify-bar success\"><strong>" . ucwords($productName) . "</strong> added successfully to basket.</div>";
				  echo $message;
				}
	}	
	
	if ($action == "deleteFromBasket"){	
		 
			 $db->delete("res_baskets", "basketID='" . $productID . "' AND basketSession = '" . $sessionID . "'");	
			 $db->delete("res_basket_topping", "basketID='" . $productID . "' AND basketSession = '" . $sessionID . "'");
				
			if ($noJavaScript == 1) {
				header("Location: ../index.php");
			}	
	} 
?>
 <script type="text/javascript">
// <![CDATA[
  $(document).ready(function() {
	  	  
	  $("#basketItemsWrap li img").live("click", function(event) { 
																
			var productIDValSplitter 	= (this.id).split("_");
			var productIDVal 			= productIDValSplitter[1];
						
			$("#smallLoader").css({display: "block"});	
				$.ajax({  
					type: "POST",  
					url: "<?php echo SITEURL;?>/ajax/basket.php",  
					data: { productID: productIDVal, action: "deleteFromBasket"},  
					success: function(theResponse) {			
						$("#productID_" + productIDVal).hide("slow",  function() {$(this).remove();});
						$("#smallLoader").css({display: "none"});
				
					}  
			}); 
	});	
	
	$("a.CancelOrder").click(function() {
		$.ajax({
			type: "POST",  
			url: "<?php echo SITEURL; ?>/ajax/user.php",  
			data: { CancelOder: "1"},  
			success: function(theResponse){	
		 	 	window.location.reload();
			
			}
		});
	});	
  });
// ]]>
</script>