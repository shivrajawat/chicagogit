<?php
define("_VALID_PHP", true);
include("init.php");
$order_id = 45;
$row = $product->OrderDetails($order_id);

function CreateElementWithValue($dom, $parent, $elementname, $elementvalue = NULL, $child = false)
{
	$tag = $dom->createElement($elementname);
	if($elementvalue)
	{
		$tagValue = $dom->createTextNode($elementvalue);
		$tag->appendChild($tagValue);
	}
	if(!$child)
	{
		$parent->appendChild($tag);
	}
}
function CreateElementAttrWithValue($dom, $parent, $elementname, $elementvalue = NULL, $child = false)
{
	$tag = $dom->createAttribute($elementname);
	if($elementvalue)
	{
		$tagValue = $dom->createTextNode($elementvalue);
		$tag->appendChild($tagValue);
	}
	if(!$child)
	{
		$parent->appendChild($tag);
	}
}
function SimpleElementCreate($xx, $elementname)
{
	return $xx->createElement($elementname);
}
// create doctype
$dom = new DOMDocument("1.0", "utf-8");

// for readability purposes
$dom->formatOutput = true;
$dom->preserveWhiteSpace = false;
$pos_request = $dom->createElement("pos_request");
$dom->appendChild($pos_request);

if($row)
{
	if($row['site_id'])
	{
		$site_id = $row['site_id'];	
	}
	else
	{
		$site_id = "";
	}
	if($row['pos_password'])
	{
		$password = $row['pos_password'];	
	}
	else
	{
		$password = "";
	}
	//Site id creator
	CreateElementWithValue($dom, $pos_request, "site_id", $site_id, false);
	//password creator
	CreateElementWithValue($dom, $pos_request, "password", $password, false);

	/*------------------------- Ticket Request Section -------------------------*/

	//ticket_request creator
	$ticket_request = SimpleElementCreate($dom, "ticket_request");
	//ticket_type creator
	if($row['order_type'])
	{
		$order_type = ($row['order_type'] == 'p') ? "P" : "D";
		CreateElementWithValue($dom, $ticket_request, "ticket_type", $order_type, false);
	}
	//ready_date creator
	if($row['pickup_date'])
	{
		$pickup_date = $row['pickup_date'];
		CreateElementWithValue($dom, $ticket_request, "ready_date", $pickup_date, false);
	}
	//ready_time creator
	if($row['pickup_time'])
	{
		$pickup_time = $row['pickup_time'];
		CreateElementWithValue($dom, $ticket_request, "ready_time", $pickup_time, false);
	}
	//subtotal creator
	if($row['gross_amount'])
	{
		$gross_amount = $row['gross_amount'];
		CreateElementWithValue($dom, $ticket_request, "subtotal", $gross_amount, false);
	}
	//delivery_fee creator
	if($row['delivery_fee'])
	{
		$delivery_fee = $row['delivery_fee'];
		CreateElementWithValue($dom, $ticket_request, "delivery_fee", $delivery_fee, false);
	}
	//additional_fee creator
	if($row['additional_fee'])
	{
		$additional_fee = $row['additional_fee'];
		CreateElementWithValue($dom, $ticket_request, "additional_fee", $additional_fee, false);
	}
	//gratuity creator
	if($row['gratuity'])
	{
		$gratuity = $row['gratuity'];
		CreateElementWithValue($dom, $ticket_request, "gratuity", $gratuity, false);
	}
	//sales_tax creator
	if($row['sales_tax'])
	{
		$sales_tax = $row['sales_tax'];
		CreateElementWithValue($dom, $ticket_request, "sales_tax", $sales_tax, false);
	}
	//total creator
	if($row['net_amount'])
	{
		$total = $row['net_amount'];
		CreateElementWithValue($dom, $ticket_request, "total", $total, false);
	}
	//notes creator
	/*if($row['notes'])
	{
		$notes = $row['notes'];
		CreateElementWithValue($dom, $ticket_request, "notes", $notes, false);
	}*/
	//add all child element
	$pos_request->appendChild($ticket_request);
	
	/*--------------------------- Ticket Section ------------------------*/
	$ticket = SimpleElementCreate($dom, "ticket");
	$orderproductrow = $product->OrderProducts($order_id);
	if($orderproductrow)
	{
		foreach($orderproductrow as $prow)
		{
			$ticket_item = SimpleElementCreate($dom, "ticket_item");
			//item id creator
			if($prow['menu_item_id'])
			{
				$item_id = $prow['ticket_item_id'];
				CreateElementAttrWithValue($dom, $ticket_item, "id", $item_id, false);
			}
			//type creator
			if($prow['menu_item_id'])
			{
				$item_type = ($prow['menu_type'] == 'calculate') ? "Pizza" : "Regular";
				CreateElementAttrWithValue($dom, $ticket_item, "types", $item_type, false);
			}
			//quantity creator
			if($prow['qty'])
			{
				$quantity = $prow['qty'];
				CreateElementAttrWithValue($dom, $ticket_item, "quantity", $quantity, false);
			}
			//item name creator
			if($prow['item_name'])
			{
				$name = $prow['item_name'];
				CreateElementAttrWithValue($dom, $ticket_item, "name", $name, false);
			}
			$ticket->appendChild($ticket_item);
			
			/*------------------------------------- SIZE area start here for SIZED item only -------------------------------------*/
			//size node creator
			$size = SimpleElementCreate($dom, "size");
			//if item type = 0 then size's information come from res_menu_item_master
			if($prow['item_type'] == 0)
			{				
				// attribute size id creator
				$size_id = $prow['size_id'];
				CreateElementAttrWithValue($dom, $size, "id", $size_id, false);
				//attribute size name creator : for regular item type size name should be blank
				CreateElementAttrWithValue($dom, $size, "name", "", false);
				//attribute price creator
				$size_price = $prow['size_price'];
				CreateElementAttrWithValue($dom, $size, "amount", $size_price, false);
			}
			//if item type = 1 then size's information come from res_menu_item_master
			if($prow['item_type'] == 1)
			{
				// attribute size id creator
				$size_id = $prow['size_id'];
				CreateElementAttrWithValue($dom, $size, "id", $size_id, false);
				//attribute size name creator
				$size_name = $prow['size_name'];
				CreateElementAttrWithValue($dom, $size, "name", $size_name, false);
				//attribute price creator
				$size_price = $prow['size_price'];
				CreateElementAttrWithValue($dom, $size, "amount", $size_price, false);
			}
			$ticket_item->appendChild($size);
			
			/*------------------------------------- SIZE area start here for SIZED item only -------------------------------------*/
			
			/*------------------------------------- Kitchen message area start here -------------------------------------*/
			//node kitchen msg creator 
			$kitchen_msg = SimpleElementCreate($dom, "kitchen_msg");
			//attribute message creator
			$comments = $prow['comments'];
			CreateElementAttrWithValue($dom, $kitchen_msg, "message", $comments, false);
			$ticket->appendChild($kitchen_msg);
			$ticket_item->appendChild($kitchen_msg);			
			/*------------------------------------- Kitchen message area end here -------------------------------------*/
			
			/*------------------------------------- SPLIT area start here for SIZED item only -------------------------------------*/
			//if( MenuType = "calculate" || "topping" || "sauce" || "cheese" ) add split
			$MenuType = $prow['menu_type'];
			if(($prow['item_type'] == 1) && (in_array($MenuType, array('calculate', 'topping', 'sauce', 'cheese'))))
			{
				//node split creator 
				$split = SimpleElementCreate($dom, "split");
				//attribute id creator
				$split_id = $prow['ticket_item_id'];
				CreateElementAttrWithValue($dom, $split, "id", $split_id, false);
				//attribute id creator
				$split_name = $prow['item_name'];
				CreateElementAttrWithValue($dom, $split, "name", $split_name, false);
				$ticket_item->appendChild($split);
			}
			//end here split addition
			/*------------------------------------- SPLIT area end here for SIZED item only -------------------------------------*/
			
			/*----------------------- TOPPING area start here for those item which belongs topping -------------------------------------*/
			$toppingrow = $product->OrderProductsToppingOptions($prow['order_detail_id']);
			if($toppingrow)
			{
				foreach($toppingrow as $trow)
				{
				/*----------------------- TOPPING area start here for those topping which option type radio or dropdown --------------------*/
				//If OptionType='r' or 'd' (Radio Button or dropdown)
				$OptionType = $trow['option_type'];
				if(($OptionType == 'r') || ($OptionType == 'd'))
				{
					//node modifier creator 
					$MenuType = $trow['menu_type'];
					if($MenuType == 'modifier')
					{
						$modifier = SimpleElementCreate($dom, "modifier");
					}
					else
					{			
						$modifier = SimpleElementCreate($dom, $MenuType);
					}
					//attribute id creator
					$modifier_id = $trow['ticket_item_id'];
					CreateElementAttrWithValue($dom, $modifier, "id", $modifier_id, false);
					//attribute name creator
					if($item_type == 'Pizza')
					{
						$modifier_name = $trow['topping_name'] . "(" . $trow['choice_name'] . ")";
					}
					else
					{
						$modifier_name = $trow['topping_name'];
					}
					CreateElementAttrWithValue($dom, $modifier, "name", $modifier_name, false);
					//attribute price creator
					$modifier_price = $trow['price'];
					CreateElementAttrWithValue($dom, $modifier, "price", $modifier_price, false);
					//if(MenuType!='Crust')		
					if(($MenuType != 'Crust') && ($MenuType != 'modifier'))
					{
						$modifier_location = "";
						CreateElementAttrWithValue($dom, $modifier, "location", $modifier_location, false);
					}
					
					$ticket->appendChild($modifier);
				}
				/*----------------------- TOPPING area end here for those topping which option type = radio or dropdown --------------------*/
				/*----------------------- TOPPING area start here for those topping which option type = checkbox --------------------*/
				//if option type = checkbox
				if($OptionType == 'c')
				{
					//checkbox modifier panel goes here 
					$MenuType = $trow['menu_type'];
					if($MenuType == 'modifier')
					{
						$modifier = SimpleElementCreate($dom, "modifier");
					}
					else
					{			
						$modifier = SimpleElementCreate($dom, $MenuType);
					}
					//attribute id creator
					$modifier_id = $trow['ticket_item_id'];
					CreateElementAttrWithValue($dom, $modifier, "id", $modifier_id, false);
					//attribute name creator					
					if($item_type == 'Pizza')
					{
						$modifier_name = $trow['topping_name'] . " (" . $trow['choice_name'] . ") ";
					}
					else
					{
						$modifier_name = $trow['topping_name'];
					}
					CreateElementAttrWithValue($dom, $modifier, "name", $modifier_name, false);
					//attribute price creator
					$modifier_price = $trow['price'];
					CreateElementAttrWithValue($dom, $modifier, "price", $modifier_price, false);
					//if(MenuType!='Crust')		
					if(($MenuType != 'Crust') && ($MenuType != 'modifier'))
					{
						$modifier_location = $trow['choice_name'];
						CreateElementAttrWithValue($dom, $modifier, "location", $modifier_location, false);
					}
					
					$ticket->appendChild($modifier);
				}			
				/*----------------------- TOPPING area end here for those topping which option type = checkbox --------------------*/
				$ticket_item->appendChild($modifier);
				}
			}
			/*------------------------------------- TOPPING area end here for those item which belongs topping -------------------------------------*/
		}
	}
	//add all child element
	$pos_request->appendChild($ticket);
	
	/*--------------------------- Payment Section ------------------------*/
	//payment creator
	$payment = SimpleElementCreate($dom, "payment");
	
	if($row['payment_type'])
	{
		$payment_id = ($row['payment_type'] == 'c') ? $row['cash_payment_id'] : $row['online_payment_id'];	
	}
	else
	{
		$payment_id = "xcvz";	
	}
	//payment id creator
	CreateElementAttrWithValue($dom, $payment, "id", $payment_id, false);
	//payment name creator
	if($row['payment_type'])
	{
		$payment_name = ($row['payment_type'] == 'c') ? "Cash" : "Online Credit Card";	
	}
	else
	{
		$payment_name = "";	
	}
	CreateElementAttrWithValue($dom, $payment, "name", $payment_name, false);
	//payment amount creator
	if($row['net_amount'])
	{
		$net_amount = $row['net_amount'];	
	}
	else
	{
		$net_amount = "";	
	}
	CreateElementAttrWithValue($dom, $payment, "amount", $net_amount, false);
	//add all child element
	$pos_request->appendChild($payment);

	/*-------------------------- Customer Section -------------------------*/
	if($row['customer_id'])
	{
		$customer_id = $row['customer_id'];
		$crow = $core->getRowById("res_customer_master", $customer_id);
		//customer creator
		$customer = SimpleElementCreate($dom, "customer");
		//first name creator
		if($crow['first_name'])
		{
			$first_name = trim($crow['first_name']);
			CreateElementWithValue($dom, $customer, "first_name", $first_name, false);
		}
		//first name creator
		if($crow['last_name'])
		{
			$last_name = trim($crow['last_name']);
			CreateElementWithValue($dom, $customer, "last_name", $last_name, false);
		}
		//first name creator
		if($crow['address1'])
		{
			$addr = trim($crow['address1'] . " " . $crow['address2']); 
			CreateElementWithValue($dom, $customer, "addr", $addr, false);
		}
		//city creator
		if($crow['city_id'])
		{
			$city_id = $crow['city_id'];
			$cityrow = $core->getRowById("res_city_master", $city_id);
			if($cityrow)
			{
				$city = trim($cityrow['city_name']);
			}
			else
			{
				$city = "";
			}
			CreateElementWithValue($dom, $customer, "city", $city, false);
		}
		//state creator
		if($crow['state_id'])
		{
			$state_id = $crow['state_id'];
			$staterow = $core->getRowById("res_state_master", $state_id);
			if($staterow)
			{
				$state = trim($staterow['state_name']);
			}
			else
			{
				$state = "";
			}
			CreateElementWithValue($dom, $customer, "state", $state, false);
		}
		//postal creator
		if($crow['zipcode'])
		{
			$zipcode = trim($crow['zipcode']);
			CreateElementWithValue($dom, $customer, "postal", $zipcode, false);
		}
		//phone creator
		if($crow['phone_number'])
		{
			$phone = trim($crow['phone_number']);
			CreateElementWithValue($dom, $customer, "phone", $phone, false);
		}
		//email creator
		if($crow['email_id'])
		{
			$email = trim($crow['email_id']);
			CreateElementWithValue($dom, $customer, "email", $email, false);
		}
		//this_contact creator
		CreateElementWithValue($dom, $customer, "this_contact", "", false);
		//add all child element to customer node
		$pos_request->appendChild($customer);
	}

	/*--------------------------- delivery section ---------------------------*/
	//delivery directions creator
	CreateElementWithValue($dom, $pos_request, "delivery_directions", "Directions", false);
}
else
{
	//error code
	CreateElementWithValue($dom, $pos_request, "error_code", "2222", false);
}

echo $dom->save('uploads/orderxml/document.xml');
?>