<?php
  /**
   * Product Class
   * Kulacart
   */  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Product
  {
      
	  public $productid = null;
	  public $updateid = null;
	  public $infoid = null;
	  

      /**
       * Product::__construct()
       * 
       * 
       * @return
       */
      public function __construct()
      {
		  $this->getProductId();
		  $this->getUpdateId();
		  $this->getInfoId();
      }

	  	  
	  /**
	   * Product::getProjectId()
	   * 
	   * @return
	   */
	  private function getProductId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['productid'])) {
			  $_GET['productid'] = sanitize($_GET['productid'],6,true);
			  $projectid = (is_numeric($_GET['productid']) && $_GET['productid'] > -1) ? intval($_GET['productid']) : false;

			  if ($projectid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Product::getProductId()") : $core->ooops();
			  } else
				  return $this->productid = $productid;
		  }
	  }
	  /**
	   * Product::getUpdateId()
	   * 
	   * @return
	   */
	  private function getUpdateId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['updateid'])) {
			  $_GET['updateid'] = sanitize($_GET['updateid'],6,true);
			  $updateid = (is_numeric($_GET['updateid']) && $_GET['updateid'] > -1) ? intval($_GET['updateid']) : false;

			  if ($updateid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Project::getUpdateId()") : $core->ooops();
			  } else
				  return $this->updateid = $updateid;
		  }
	  }
	  /**
	   * Product::getInfoId()
	   * 
	   * @return
	   */
	  private function getInfoId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['infoid'])) {
			  $_GET['infoid'] = sanitize($_GET['infoid'],6,true);
			  $infoid = (is_numeric($_GET['infoid']) && $_GET['infoid'] > -1) ? intval($_GET['infoid']) : false;

			  if ($infoid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Project::getInfoId()") : $core->ooops();
			  } else
				  return $this->infoid = $infoid;
		  }
	  }	 
	  
	    /**
	   * Product::getBasket()
	   *
	   * @return
	   */
		public function getBasket(){
		global $db, $core;
		
		error_reporting(0);		
		$sessionID = SESSION_COOK;
		
		$query  = "SELECT * FROM res_baskets WHERE basketSession = '" . $sessionID . "' ORDER By basketID DESC";
		$basket = $db->fetch_all($query);	
		
		
		foreach ($basket as $row)
		{
			
			$sql  = "SELECT id,item_name,price"
					. "\n FROM res_menu_item_master"
					. "\n WHERE id ="  . $row['productID'];
			
			$row2 = $db->first($sql);
		
			$productID	 		= $row2['id'];
			$productPrice 		= $row2['price'];	
			$productName		= $row2['item_name'];
		
			$query2  = "SELECT basketID , qty FROM res_baskets WHERE basketSession = '" . $sessionID . "' AND productID = " . $productID;
			$row2 = $db->first($query2);
			$qty =  $row2['qty'];
			$basketID = $row2['basketID'];
			
			$basketText = $basketText . '<li  id="productID_' . $basketID . '"><span class="item-name1 span7">'.$productName.'</span><span class="qty1 span2">'.$qty.'</span><span class="Delete1 span3"><a href="'.SITEURL.'/basket.php?action=deleteFromBasket&productID=' . $basketID . '"rel="' . $productID . '" class="deleteitem" onClick="return false;"><img src="images/close.png" alt=""  id="deleteProductID_' . $basketID . '" /></a></span></li>';		
		}
		return $basketText;
		}
		
	   /**
	   * Product::AllProductInBasket_upto_5th_jan_2013()
	   *
	   * @return
	   */
		public function AllProductInBasket_upto_5th_jan_2013()
		{
			global $db, $core;
			
			 $sql = "SELECT `rmim`.`item_name` AS `name` , `rmim`.`item_description` AS `description` , `rb`.`productprice` AS `unit_price` , "
				."\n `rb`.`additional_notes`,`rb`.`menu_size_map_id`,`rb`.`productID`, `rb`.`qty` AS `quantity` , ROUND(`rb`.`productprice` * `rb`.`qty` + ( "
				."\n SELECT COALESCE(SUM( `price` * `qty` ), 0)"
				."\n FROM `res_basket_topping`"
				."\n WHERE `basketSession` = '" . SESSION_COOK . "' && `product_id` = `rmim`.`id` && `basketID` = `rb`.`basketID`"
				."\n ), 2) AS `total_price` , `rmim`.`id` AS `product_id` , `rb`.`basketID` AS `basket_id`, "
				."\n (SELECT GROUP_CONCAT( CONCAT( IF(`rbt`.`price` = '0.00', `rbt`.`option_topping_name`, CONCAT( `rbt`.`option_topping_name` , '($', `rbt`.`price` , ')' )) , "
				."\n '||', `rbt`.`price` , '||', `rbt`.`qty` , '||',`rbt`.`option_id` , '||',`rbt`.`option_topping_id` , '||',`rbt`.`option_choice_id` , '||',`rbt`.`option_topping_name` , '||', ("
				."\n `rbt`.`price` * `rbt`.`qty`"
				."\n ) )"
				."\n SEPARATOR '%%' )"
				."\n FROM `res_basket_topping` AS `rbt`"
				."\n WHERE `rbt`.`product_id` = `rb`.`productID` && `rbt`.`basketID` = `rb`.`basketID`) AS `topping_details`"
				."\n FROM `res_baskets` AS `rb`"
				."\n INNER JOIN `res_menu_item_master` AS `rmim` ON `rb`.`productID` = `rmim`.`id`"
				."\n WHERE `rb`.`basketSession` = '" . SESSION_COOK . "'"; 
				
			$row = $db->fetch_all($sql);
			
			
			return ($row) ? $row : 0;
	 	}	 
		
		/**
	   * Product::AllProductInBasket()
	   *
	   * @return
	   */
		public function AllProductInBasket()
		{
			global  $core;
				
			require_once(WOJOLITE . "lib/class_db.php");		  
			$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
			
			$link = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
			
			$query = "SET @@group_concat_max_len = 9999999;";
			$query .= "SELECT `rmim`.`item_name` AS `name` , `rmim`.`item_description` AS `description` , `rb`.`productprice` AS `unit_price` , "
				."\n `rb`.`additional_notes`,`rb`.`menu_size_map_id`,`rb`.`productID`, `rb`.`qty` AS `quantity` , ROUND(`rb`.`productprice` * `rb`.`qty` + ( "
				."\n SELECT COALESCE(SUM( `price` * `qty` ), 0)"
				."\n FROM `res_basket_topping`"
				."\n WHERE `basketSession` = '" . SESSION_COOK . "' && `product_id` = `rmim`.`id` && `basketID` = `rb`.`basketID`"
				."\n ), 2) AS `total_price` , `rmim`.`id` AS `product_id` , `rb`.`basketID` AS `basket_id`, "
				."\n (SELECT GROUP_CONCAT( CONCAT( `rbt`.`option_topping_name` , "
				."\n '||', `rbt`.`price` , '||', `rbt`.`qty` , '||',`rbt`.`option_id` , '||',`rbt`.`option_topping_id` , '||',`rbt`.`option_choice_id` , '||',`rbt`.`option_topping_name` , '||', ("
				."\n `rbt`.`price` * `rbt`.`qty`"
				."\n ) )"
				."\n SEPARATOR '%%' )"
				."\n FROM `res_basket_topping` AS `rbt` "
				."\n WHERE `rbt`.`product_id` = `rb`.`productID` && `rbt`.`basketID` = `rb`.`basketID`) AS `topping_details`"
				."\n FROM `res_baskets` AS `rb`"
				."\n INNER JOIN `res_menu_item_master` AS `rmim` ON `rb`.`productID` = `rmim`.`id`"
				."\n WHERE `rb`.`basketSession` = '" . SESSION_COOK . "'  ORDER BY name"; 
			
			
			//echo $query; exit();
			
			
			/* Execute queries */
			if (mysqli_multi_query($link,$query)) {				
				
				do {
					/* store first result set */
					if ($result = mysqli_store_result($link)) {
						
						 $record = array();
						  while ($row = mysqli_fetch_array($result)) :
							  $record[] = $row;
						  endwhile;
												
							
							
							mysqli_free_result($result);
					}   
				} 
				while (mysqli_next_result($link));
			}
			
			//echo "<pre>"; print_r($record); exit();
			return ($record) ? $record : 0;
	 	}	 	
			
		/**
	   * Product::MyShoppingBasket()
	   *
	   * @return
	   */
		public function MyShoppingBasket(){
			global $db, $core;
			error_reporting(0);
				  
			  $sql  = "SELECT *,(qty*productPrice) as totalprice FROM res_baskets WHERE basketSession = '" . SESSION_COOK . "' ORDER By basketID DESC";		
			  $row = $db->fetch_all($sql);
			  return ($row) ? $row : 0;
		
	 	 }	 	  
      /**
	   * Product::viewcartdetails()
	   *
	   * @return
	   */
		public function viewcartdetails($productID){
			 global $db, $core;
			 $sql = "SELECT *"
				. "\n FROM res_menu_item_master"
				."\n WHERE id = '".$productID."'";
			   $row = $db->first($sql);
				return ($row) ? $row : 0;
		}
		/**
	   * Product::viewCartTopping()
	   *
	   * @return
	   */
		public function viewCartTopping($basketID){
			global $db, $core;
			error_reporting(0);
				  
			  //$sql  = "SELECT * FROM res_basket_topping WHERE basketSession = '" . SESSION_COOK . "' && basketID = '".$basketID."'";	
			  $sql  = "SELECT bt.*,tm.* FROM "
			   . "\n res_basket_topping AS bt"
			   . "\n LEFT JOIN res_menu_option_topping_master AS tm ON tm.option_topping_id =bt.option_topping_id"
			   . "\n WHERE bt.basketSession = '" . SESSION_COOK . "' && bt.basketID = '".$basketID."'";		
			  $row = $db->fetch_all($sql);
			  return ($row) ? $row : 0;			  
		
	 	 }	
		  /**
	   * Product::cartToppinglist()
	   *
	   * @return
	   */
		public function cartToppinglist($toppingid){
			 global $db, $core;
			 $sql = "SELECT *"
				. "\n FROM res_menu_option_topping_master"
				."\n WHERE option_topping_id IN('".$toppingid."')";
			   $row = $db->first($sql);
				return ($row) ? $row : 0;
		}
		
		/**
	   * Product::getToalpriceinBasket()
	   *
	   * @return
	   */
	   
		public function getToalpriceinBasket(){
			global $db, $core;
			$sessionID = SESSION_COOK;	
			$sql  = "SELECT `rb`.`qty` AS `quantity`, `rb`.`basketID` AS `basket_id`,"
					."\n SUM(ROUND(`rb`.`productprice` * `rb`.`qty` + ( SELECT COALESCE(SUM( `price` * `qty` ), 0) FROM `res_basket_topping`"
					."\n  WHERE `basketSession` = '" .SESSION_COOK. "' && `basketID` = `rb`.`basketID` ), 2)) AS `total_price`"
					."\n FROM `res_baskets` AS rb WHERE `rb`.`basketSession` ='".SESSION_COOK."'";
				 $row = $db->first($sql);			
				return ($row) ? $row['total_price'] : 0;	
	}
		/**
	   * Product::totalpriceitem()
	   *
	   * @return
	   */
	public function totalpriceitem()
	{
		global $db, $core;
		$sessionID = SESSION_COOK;	
		$sql = "SELECT SUM( qty * productPrice) AS totalprice"
			   ."\n FROM res_baskets"
			   ."\n WHERE basketSession = '" . $sessionID . "'";
			   $row = $db->first($sql);
			   return ($row) ? $row : 0;
	}
		/**
	   * Product::additionalAmmount()
	   *
	   * @return
	   */
	public function additionalAmmount($locationid)
	{
		global $db, $core;
		   $sql = "SELECT `id`, ROUND(`delivery_fee`, 2) AS `delivery_fee`, ROUND(`sales_tax`, 2) AS `sales_tax`, `gratuity`,`additional_fee` FROM `res_location_master`"
			."\n WHERE id = '".$locationid."'";
			 $row = $db->first($sql);
			 return ($row) ? $row : 0;
	}
	
	/**
	   * Product::checkbasket()
	   *
	   * @return
	   */
	public function checkbasket()
	{
		global $db, $core;
		$sessionID = SESSION_COOK;
		$sql = "SELECT `basketSession`,`user_id` FROM `res_baskets`"
			."\n WHERE `basketSession`='".$sessionID."'";// && `user_id`='".."'";
			 $row = $db->first($sql);
			 return ($row) ? $row : 0;
	}
	
	/**
	   * Product::StartEndTime()
	   *
	   * @return
	   */
	public function StartEndTime($locationid,$days)
	{
		global $db, $core;
		   $sql = "SELECT day_start_time,last_order_time,is_holidays,open_24hours,d_morning_start,d_evening_start,d_morning_end,d_evening_end "
		      ."\n  FROM `res_location_time_master`"
			  ."\n WHERE location_id = '".$locationid."' && days = '".$days."'";
			 $row = $db->first($sql);
			 return ($row) ? $row : 0;
	}
	
	public function TimeZone($locationid)
	{
		global $db, $core;
		
		$sql = "SELECT * FROM"
		      ."\n `res_location_master` AS lm"
		      ."\n LEFT JOIN res_timezone AS tz ON lm.zone_id = tz.id"
		      ."\n WHERE lm.id = '".$locationid."'";
			 $row = $db->first($sql);
			 return ($row) ? $row : 0;
	}
	
	/* 
	* Product:locationName()
	*/
	public function locationName($locationid)
	{
		global $db, $core;
		$sql = "SELECT `location_name`,delivery_fee, id, address1 FROM `res_location_master` WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
	/* 
	* Product:locationName()
	*/
	public function paymentSystemList($locationid)
	{
		global $db, $core;
		$sql = "SELECT `id`,`is_cash_on_delivery`,`is_paypal`,`is_authorize`,`is_first_data`,`is_mercury`,`is_internet_secure`,`merchant_id`,`merchant_password`"
		      ."\n FROM `res_location_master` "
			  ."\n WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
	
	
	    /**
	   * Product::getBasketDetails()
	   * 
	   * @return
	   */
	  public function getBasketDetails($session_cookie)
	  {
		  global $db, $core;
		  
          $sql = "SELECT *"
				 . "\n FROM `res_baskets` "
				 . "\n  WHERE `basketSession` = '" . $session_cookie . "'";
				   $row = $db->fetch_all($sql);          
		  return ($row) ? $row : 0;
	  }
	
	   /**
       * Product::processOrderMaster()
       * 
       * @return
       */
	  public function processOrderMaster($location_id,$customer_id,$order_type,$order_number,$payment_type,$payment_gateway_type,$transaction_id,$order_comments,$gross_amount,$netamount,$additional_fee,$gratuity,$delivery_fee,$sales_tax_rate,$sales_tax,$tip,$coupon_id,$coupon_discount,$pickup_date,$pickup_time,$d_address1,$d_address2,$d_state_id,$d_city_id,$d_country_id,$city,$state,$apt,$zip_code,$web_access_type,$browser_name)
	  {
		  global $db, $core,$customers, $wojosec; 		 
		 	 
		  	  if(isset($coupon_id) && !empty($coupon_id)){	//If customer applies coupon		
					  $usedno = getValue("used_no","res_coupon_master","coupon_id = '".$coupon_id."'");				  		  
					  $datacoupon['used_no'] =  $usedno+1;			  
					  $db->update("res_coupon_master", $datacoupon, "coupon_id='" . $coupon_id . "'");				  	
	 		  }
							  
			  $data = array(
					  'location_id' =>  $location_id, 
					  'customer_id' => $customer_id,					 					  
					  'order_type' => $order_type,
					  'order_number' => $order_number,
					  'payment_type' => $payment_type,					  				  				  
					  'payment_gateway_type' => $payment_gateway_type,
					  'browser_name' => $browser_name,					  				  				  
					  'web_access_type' => $web_access_type,		
					  'transaction_id' => $transaction_id,
					  'order_comments' => html_entity_decode($order_comments),
					  'gross_amount' => $gross_amount,
					  'net_amount' => $netamount,
					  'additional_fee' =>$additional_fee,
					  'gratuity' =>$gratuity,
					  'delivery_fee' =>$delivery_fee,
					  'sales_tax_rate' => $sales_tax_rate,
					  'sales_tax' => $sales_tax,
					  'tip' => $tip,
					  'coupon_id' => $coupon_id,
					  'coupon_discount' => $coupon_discount,
					  'pickup_date' => $pickup_date,
					  'pickup_time' => $pickup_time,
					  'd_address1' => $d_address1,
					  'd_address2' => $d_address2,					  
					  'apt' => $apt,
					  'd_state' => $state,
					  'd_city' => $city,
					  'd_zipcode' => $zip_code,				  
					  'd_state_id' => $d_state_id,		//state id
					  'd_city_id' => $d_city_id,		//city id
					  'd_country_id' => $d_country_id,	//country id
					  'created_by' => $customer_id,					  
					  'created_date' => date("Y-m-d h:i:s", time())
			  );			  
		
			  $db->insert("res_order_master", $data);
			  $insert_id = $db->insertid();				 			 
			  if(!empty($insert_id))
			  {
			  	$allproductrow = $this->AllProductInBasket();
				if($allproductrow)
				{
					$k =0;
					foreach($allproductrow as $row)
					{
						$data1 = array(
						  'order_id' => $insert_id,
						  'menu_item_id' => $row['productID'],
						  'menu_size_map_id' => $row['menu_size_map_id'],
						  'price' => $row['unit_price'],	
						  'qty' => $row['quantity'],				  
						  'comments' => html_entity_decode($row['additional_notes'])
				 		 );
						 
						 $additional_notes_arr[$k] = $row['additional_notes']; 
						 
						 $k++;
						 
					 $db->insert("res_order_details", $data1);
					 
					 $insert_id1 = $db->insertid();	
					 	if($row['topping_details'])
						{
							$toppingdetails = $row['topping_details'];
							$toppingarray = explode("%%", $toppingdetails);
							for($ct = 0; $ct < count($toppingarray); $ct++)
							{
								$toppingrow = $toppingarray[$ct];
								$toppingrow = explode("||", $toppingrow);
									$data2 = array(
											  'order_detail_id' => $insert_id1,
											  'option_id' => $toppingrow[3],
											  'option_topping_id' => $toppingrow[4],
											  'option_choice_id' => $toppingrow[5],	
											  'option_topping_name' => html_entity_decode($toppingrow[6]),	
											  'price' => $toppingrow[1],					 	
											  'qty' => $toppingrow[2],				  
											  'option_type' =>""
									  );
									  
									$db->insert("res_order_menutopping_details", $data2);
							}
						}
					}
				}
				
			  } 
			  
			  //update delivery details to res_customer_master
			  if(isset($_SESSION['orderType']) && $_SESSION['orderType'] =='delivery'){
				  $data4 = array(
						
						  'd_address1' => sanitize($d_address1),
						  'd_address2' => sanitize($d_address2),					  
						  /*'addr_residence' => ($_SESSION['address_type_residence']) ? $_SESSION['address_type_residence'] : "" ,
						  'addr_business' => ($_SESSION['address_type_business']) ? $_SESSION['address_type_business'] : "" ,	
						  'addr_university' => ($_SESSION['address_type_university']) ? $_SESSION['address_type_university'] : "" ,
						  'addr_military' => ($_SESSION['address_type_military']) ? $_SESSION['address_type_military'] : "" ,	
						  'address_type' => ($_SESSION['address_type']) ? $_SESSION['address_type'] : "" ,*/
						  'business_name' => ($_SESSION['business_name']) ? $_SESSION['business_name'] : "" ,					  	
						  'apt' => sanitize($apt),
						  'dstate' => sanitize($state),
						  'dcity' => sanitize($city),
						  'dzipcode' => sanitize($zip_code),
						  'dstate_id' => $d_state_id,	  //state id
					  	  'dcity_id' => $d_city_id,		  //City id
					 	  'dcountry_id' => $d_country_id  //Country Id
						  
						  
				  );			  
			  	  $db->update("res_customer_master", $data4, "id='" . $customer_id . "'");
			  }		
			  
			 		 
  //order details commented part upto 19_nov_2013, starts here//
  $order_details2 ='<tr>
  					<td style="color:#000000; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">
						<table width="437" border="0" cellspacing="0" cellpadding="0">
						  <tr style="background:#f5ce7f;">
							<td style="background:#f5ce7f;color:#ef4423;font-size:12px;font-weight:bold;font-family:Verdana,Arial,Helvetica,sans-serif;padding-left:8px; line-height:20px;">Quantity </td>
							<td style="background:#f5ce7f;color:#ef4423;font-size:12px;font-weight:bold; font-family:Verdana, Arial, Helvetica, sans-serif;line-height:20px; padding-left:10px;">Description</td>
							<td style="background:#f5ce7f;color:#ef4423;font-size:12px;font-weight:bold;font-family:Verdana,Arial, Helvetica,sans-serif;line-height:20px;text-align:left; padding-right:10px;">Amount</td>
					</tr>';			
					
				$orderproduct = $this->ThanksProducts($insert_id); 
		 		$i =0;
				foreach($orderproduct as $prow){	
						
					 $ItemSize = $this->getItemSize_new($prow['order_detail_id'],$insert_id,$prow['menu_size_map_id']);  //Item Size
					 $itemSize2 = ($ItemSize['size_name']) ? " (".$ItemSize['size_name'].")" : "";
					 $itemSize3 = ($ItemSize['size_name']) ? " (".$prow['category_name'] .'-'. $ItemSize['size_name'].")" : "";   //This is used for restorant email invoice 
					
//item name
$order_details2 .='<tr>
				<td valign="top" style="text-align:center;">
					<span style="color:#000;font-weight:bold; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif; padding-left:8px;">'.$prow['qty'].'</span>
					</td>
					<td style="padding-left:10px;">
		  			 <span style="color:#000;font-weight:bold; font-size:13px; font-family:Verdana, Arial, Helvetica, sans-serif;">'.$prow['item_name'].$itemSize2.'</span><br />';
					 
					 
					 //added on 27th jan, starts
					 $OptionName  = $this->OptionName($prow['order_detail_id']); 				 
					  	
					 foreach($OptionName as $orow){
						 
						 $order_details2 .='<span style="color:#ef4423;font-weight:bold; font-size:13px; font-family:Verdana, Arial, Helvetica, sans-serif;">'.$orow['instruction'].'</span><br>';
					  //added on 27th jan, ends
		
		/*****************************************************Topping display****************************************************************/		    
		$topping  = $this->thanksToppingListNew($orow['option_id'],$orow['order_detail_id']);
		// $topping  = $this->thanksToppingList($prow['order_detail_id']);
		
		if($topping){	
			foreach ($topping as $trow){ 
				$optionChoiceName = ($trow['choice_name'])? $trow['choice_name'] : '';
					$optionChoiceName2 = '';
						if(isset($trow['option_topping_name']) && !empty($trow['option_topping_name']) && $optionChoiceName && $trow['price']==0.00 ) { 
							$optionChoiceName2 = '('.$trow['choice_name'].')'; 
						}						
						else if(isset($trow['option_topping_name']) && !empty($trow['option_topping_name']) && empty($optionChoiceName) && $trow['price']!=0.00 ) {
							 $optionChoiceName2 = '('.$trow['price'].')'; 
						}						
						else if(isset($trow['option_topping_name']) && !empty($trow['option_topping_name']) && !empty($trow['price']) && $trow['price']!=0.00 && $optionChoiceName) {
							
							$optionChoiceName2 = '('.$trow['choice_name'].'-'.$trow['price'].')';						 
						} 		
				
     		    $order_details2.='<span style="color:#000; font-weight:bold; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">'.$trow['option_topping_name'].'</span>'.$optionChoiceName2.' <br />';
		 	}//topping name foreach loop, ends
		  }
		
		}//option name foreach loop, ends
				
			if(isset($additional_notes_arr[$i]) && !empty($additional_notes_arr[$i])){
										
					$order_details2.='<span style="color:#ef4423; font-weight:bold; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">Who is this for? 
								</span>'.$additional_notes_arr[$i].'</td>';	
			} 
			else {
													
					$order_details2.='<span style="color:#ef4423; font-weight:bold; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;"></span></td>';
				}
					
$order_details2.='<td  valign="top">
			<span style="color:#ef4423;font-weight:bold; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">';
			            $total = $prow['price']*$prow['qty']; 
						$order_details2.='$'.round_to_2dp($prow['total_price'] +$total);   
				
			
$order_details2.='</span>
		</td>
      </tr>';
			 $i++; 
		  }	
	  
	  //payment details
	   $order_details2.='<tr>
        <td style="border-bottom:1px solid #231f20; padding-bottom:20px;" colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top" style="padding-top:10px;">
			  	<span style="color:#000;font-weight:bold; font-size:13px; font-family:Verdana, Arial, Helvetica, sans-serif;">
					Order Comments:</span>'.$order_comments.'</td>
              <td style="padding:10px;">
			  	<table width="100%" border="0" cellspacing="0" cellpadding="0"  style="background:#f5ce7f; font-size:12px; color:#000;">';
				
				 if(isset($data['gross_amount']) && $data['gross_amount']!='0.00'){
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Subtotal :</td>
										<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['gross_amount']).'</td>
									  </tr>';
				}				
				if(isset($data['sales_tax']) && $data['sales_tax']!='0.00'){														  
				 $order_details2.='<tr>
									<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Tax :@'.$data['sales_tax_rate'].'%:</td>
									<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['sales_tax']).'</td>
								  </tr>';				  
				  } 
				  if(isset($data['additional_fee']) && $data['additional_fee']!='0.00'){					 
				        $order_details2.='<tr>
											<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Additional Fee :</td>
											<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['additional_fee']).'</td>
										  </tr>';								  
				   }			  
				   if(isset($data['gratuity']) && $data['gratuity']!='0.00'){				   														  
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Gratuity:</td>
										<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['gratuity']).'</td>
									  </tr>';
					}					
					if(isset($data['coupon_discount']) && $data['coupon_discount']!='0.00'){
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Discount ('. $data['coupon_id'] .') :</td>
										<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['coupon_discount']).'</td>
									  </tr>';
					}										  
					if(isset($data['delivery_fee']) && $data['delivery_fee']!='0.00'){								  
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Delivery Charge: </td>
										<td style=" padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['delivery_fee']).'</td>
									  </tr>';
					}					
					if(isset($data['tip']) && $data['tip']!='0.00'){														
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Tip: </td>
										<td style=" padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['tip']).'</td>
									  </tr>';
					}					
					if(isset($data['net_amount']) && $data['net_amount']!='0.00'){	
					$order_details2.='<tr>
										<td style="font-weight:bold; padding-left:8px; padding-right:8px;">Total: </td>
										<td style="padding-left:8px; padding-right:8px;">$'.round_to_2dp($data['net_amount']).'</td>
									  </tr>';
					}	
								  
$order_details2.='</table></td>
            </tr>
          </table></td>
      </tr>';
	  
	  
      $order_details2.=' </table>
					   </td>
					</tr>';
														  
							/*************************delivery details, starts here*********************************/
							if(isset($data['order_type']) && !empty($data['order_type']) && $data['order_type']=='d'){	
										  
							$delivery_details = '<table style="width: 100%;">
												<tbody>';
						   
							  	$delivery_details .= '<tr><td></td><td>Delivery Details</td></tr>';						
							
							  	$delivery_details .= '<tr><td></td><td>'.$data['d_address1'].'</td></tr>';													
							
							  	$delivery_details .= '<tr><td></td><td>'.$data['d_city'].','.$data['d_state'].'</td></tr>';				
								$delivery_details .= '<tr><td></td><td>'.($data['d_zipcode']) ? ' '.$data['d_zipcode'] : ' '.'</td></tr>';									
							   
							    $delivery_details .= '</tbody>
											   </table>'; 
							}
							else {
								$delivery_details ='';
							}
						   /*************************delivery details, ends here*********************************/	
											  
						  //If order type is delivery then display delivery address
						  if(isset($data['order_type']) && !empty($data['order_type']) && $data['order_type']=='d'){
							   $delivery_address = ($data['apt']) ? '<span style="color: rgb(239, 68, 35); font-size: 11px; font-family: Verdana,Arial,Helvetica,sans-serif;"> Delivery Address: </span><span style="color:black; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">'.$data['apt'].',' : '';
							   $delivery_address .= ($data['d_address1']) ? $data['d_address1'].', ' : '';
							   $delivery_address .= ($data['d_city']) ? $data['d_city'].', ' : ' ';
							   $delivery_address .= ($data['d_zipcode']) ? $data['d_zipcode'].' ' : '';
							   $delivery_address .= '</span>';
						   }
						   else { $delivery_address =''; } 
						    
						  					  
					      //order details commented part upto 19_nov_2013, ends here//
						  $customer_name = $customers->customername; 						  //Customer name  
						  $contact_number = $this->phoneNoEmailByLocation($location_id);      //Restorant phone number
						  $restorant_location = $this->resLocationINemail($location_id); 
						 
						  $locRow = $this->getLocationDetails($location_id);	
						  $email_disclaimer = $locRow['emai_disclaimer'];
						  $restaurant_name = $locRow['restaurant_name'];
						  $location_name = $locRow['location_name'];
						  $phone_number = $locRow['phone_number'];	 //restorant contact number1
						  $phone_number1 = $locRow['phone_number1']; //restorant contact number2
						  $address1 = $locRow['address1']; 
						  $address2 = ($locRow['address2']) ? $locRow['address2'].',<br/>' : "";
						  $city_name = $locRow['city_name'];								//restorant city
						  
						  $state_name = $locRow['state_name']; 
						  
						  $restorant_complete_address = $restaurant_name. '<br/>'.$address1 .$address2.',<br/>'.$phone_number;
						   
						  $myRow = $this->getPaymentMethodNType($order_number);	
						  if($myRow['order_type']=='p'){ $orderType ="Pick Up"; }       //PickUp 
						  if($myRow['order_type']=='d'){ $orderType ="Delivery"; }      //Delivery 
						  if($myRow['order_type']=='dl'){ $orderType ="Dine In"; }      //Dine In 
						  
						  if($myRow['payment_type']=='c'){
						  
								$paymentType = "Cash";
						  }
						  if($myRow['payment_type']=='o'){
						  
								$paymentType = "Online";
						  }
						  
						  $orderRow = $this->getOrderDateNtime($order_number);
						  $order_generated_date = ($orderRow['order_date'])? $orderRow['order_date'] : "";    //Order created date	
						  $orderDateTime = ($orderRow['order_date']) ? date('m/d/Y h: i A',strtotime($orderRow['order_date'])): '';
						  
						  $order_dt = ($orderRow['pickup_date'] ) ? $orderRow['pickup_date'] : "";
						  $order_tm = ($orderRow['pickup_time']) ? $orderRow['pickup_time'] : "";						  
						  $pickDateTime = $order_dt. '  '.$order_tm;   //pick date and time as oder date and time
						  
						   
						  //If order type is delivery then display order date and time 
						  if(isset($data['order_type']) && !empty($data['order_type']) && $data['order_type']=='d'){ 
							   $delivery_dateTime = '<span style="color: rgb(239, 68, 35); font-size: 11px; font-family: Verdana,Arial,Helvetica,sans-serif;"> Delivery Time: </span><span style="color:black; font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;">'.$order_dt. '  '.$order_tm.'</span>';  
							   
						   }
						   else { $delivery_dateTime =''; }						 
						 	
						   $customer_contactno = ($customer_id && !empty($customer_id))  ? getValue("phone_number","res_customer_master","id='" . $customer_id . "'") : ""; 
						  
						  /************************************order email invoice for the user, starts here***********************************************/						  
						  require_once(WOJOLITE . "lib/class_mailer.php");	
						  
						  if(!isset($_SESSION['test_mode'])){
						 		 $order_email[] = $customers->email; 
						  }	
						  if(isset($locRow['order_email1']) && !empty($locRow['order_email1'])) {  $order_email[] = $locRow['order_email1']; }
						  
						  if(isset($locRow['order_email2']) && !empty($locRow['order_email2'])) {  $order_email[] = $locRow['order_email2']; } 
						  
						  if(isset($locRow['order_email3']) && !empty($locRow['order_email3'])) {  $order_email[] = $locRow['order_email3']; }
						  
						  if(isset($locRow['order_email4']) && !empty($locRow['order_email4'])) {  $order_email[] = $locRow['order_email4']; } 		
						 
						  				  
						  $count_email = count($order_email);
						  						  
						  for($k=0; $k<$count_email; $k++){	
						   
						  if($k==0){	
						  						 				
						  $mailer = $mail->sendMail();							  
						  $row = $core->getRowById("email_templates",16);
						  
						 
						 $body = str_replace(array('[CUSTOMER_NAME]','[CUSTOMER_CONTACTNO]','[TRANSACTIONID]','[RESTORANT_NAME]','[RESTORANT_CONTACTNO]','[CONTACT_NUMBER]','[LOCATION]','[RES_CITY]','[RESTORANT_LOCATION]','[ORDER_DATETIME]','[ORDER_DATE]','[PAYMENT_METHOD]','[ORDER_TYPE]','[ORDER_NUMBER]','[ORDER_DETAILS]','[DELIVERY_ADDRESS]','[SALE_RATE]','[GROSS_AMOUNT]','[DELIVERY_FEE]','[SALES_TEX]','[TIP]','[TOTAL_AMOUNT]','[EMAIL_DISCLAIMER]','[ORDER_COMMENT]','[RES_CONTACTNO]','[DELIVERY_DATE_TIME]','[SITE_NAME]','[URL]'), 
						  array($customer_name,$customer_contactno,$transaction_id,$restaurant_name,$phone_number,$contact_number,$location_name,$city_name,$restorant_complete_address,$pickDateTime,$pickDateTime,$paymentType,$orderType,$order_number,$order_details2,$delivery_address,round_to_2dp($sales_tax_rate),round_to_2dp($gross_amount),$delivery_fee,round_to_2dp($sales_tax),$tip,round_to_2dp($netamount),$email_disclaimer,$order_comments,$phone_number,$delivery_dateTime,$core->site_name,$core->site_url), $row['body'.$core->dblang]);
						  				  	  				  			  			 				 
				  		  $mailer = $mail->sendMail(); 
						  							
						  $message = Swift_Message::newInstance()
									->setSubject($row['subject'.$core->dblang])
									->setTo(array($order_email[$k] => $core->site_name))
									->setFrom(array($core->site_email => $core->site_name))
									->setBody(cleanOut($body), 'text/html');
							 		
						   $mailer->send($message);
						  }
						   else {								 
							  
							  $mailer = $mail->sendMail();							  
							  $row2 = $core->getRowById("email_templates",17);									  
							
							  $order_details3 = str_replace(array('background:#f5ce7f;','color:#ef4423;',$itemSize2),array('','',$itemSize3),$order_details2);	
							  
							  $body2 =  str_replace(array('[CUSTOMER_NAME]','[CUSTOMER_CONTACTNO]','[TRANSACTIONID]','[RESTORANT_NAME]','[RESTORANT_CONTACTNO]','[CONTACT_NUMBER]','[LOCATION]','[RES_CITY]','[RESTORANT_LOCATION]','[ORDER_DATETIME]','[ORDER_DATE]','[PAYMENT_METHOD]','[ORDER_TYPE]','[ORDER_NUMBER]','[ORDER_DETAILS]','[DELIVERY_ADDRESS]','[SALE_RATE]','[GROSS_AMOUNT]','[DELIVERY_FEE]','[SALES_TEX]','[TIP]','[TOTAL_AMOUNT]','[EMAIL_DISCLAIMER]','[ORDER_COMMENT]','[RES_CONTACTNO]','[DELIVERY_DATE_TIME]','[SITE_NAME]','[URL]'), 
						  array($customer_name,$customer_contactno,$transaction_id, $restaurant_name,$phone_number,$contact_number,$location_name,$city_name,$restorant_complete_address,$pickDateTime,$pickDateTime,$paymentType,$orderType,$order_number,$order_details3,$delivery_address,round_to_2dp($sales_tax_rate),round_to_2dp($gross_amount),$delivery_fee,round_to_2dp($sales_tax),$tip,round_to_2dp($netamount),$email_disclaimer,$order_comments,$phone_number,$delivery_dateTime,$core->site_name,$core->site_url), $row2['body'.$core->dblang]);	
						  
						     // print html_entity_decode($body2); exit(); 
							
							  $mailer = $mail->sendMail(); 														
							  $message2 = Swift_Message::newInstance()
										->setSubject($row2['subject'.$core->dblang])
										->setTo(array($order_email[$k] => $core->site_name))
										->setFrom(array($core->site_email => $core->site_name))
										->setBody(cleanOut($body2), 'text/html');							
										
							  $mailer->send($message2);	
							   
						   }						   
						   		
						  }						  
						      
						   /****************************************commented on 6 Dec, 2013, ends here************************************************/
						   
			  return  $insert_id;
			  
		  
	  }
	  
	    
	  
	  
	  /**
       * Product::processOrderDetails()
       * 
       * @return
       */
	  public function processOrderDetails($ordermaster,$menu_item_id,$menu_size_map_id,$price,$qty,$comments)
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($core->msgs)) {			  
				  
			  $data = array(
					  'order_id' => $ordermaster,
					  'menu_item_id' => $menu_item_id,
					  'menu_size_map_id' => $menu_size_map_id,
					  'price' => $price,	
					  'qty' => $qty,				  
					  'comments' => $comments
			  );
			  
			
			  $db->insert("res_order_details", $data);
			  return $insert_id = $db->insertid();
			  if($db->affected()):
			  	 $wojosec->writeLog(_USER . ' ' . $data['order_id']. ' ' . _LG_USER_REGGED, "order_detail", "no", "order_id");
				 return "1";				  
			  else:
			  	$core->msgAlert(_SYSTEM_PROCCESS);
			  endif;
		  } else
			  print $core->msgStatus();
	  } 
	  
	  
	   /**
       * Product::processOrderMenuTopping()
       * 
       * @return
       */
	  public function processOrderMenuTopping($order_detail_id,$option_id,$option_topping_id,$option_choice_id,$qty,$option_type,$option_topping_name,$topping_price)
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($core->msgs)) {			  
				  
			  $data = array(
					  'order_detail_id' => $order_detail_id,
					  'option_id' => $option_id,
					  'option_topping_id' => $option_topping_id,
					  'option_choice_id' => $option_choice_id,	
					  'option_topping_name' => $option_topping_name,	
					  'price' => $topping_price,					 	
					  'qty' => $qty,				  
					  'option_type' => $option_type
			  );
			  
			
			  $db->insert("res_order_menutopping_details", $data);
			  
			  if($db->affected()):
			  	 $wojosec->writeLog(_USER . ' ' . $data['order_detail_id']. ' ' . _LG_USER_REGGED, "order_detail_id", "no", "order_id");
				 return "1";				  
			  else:
			  	$core->msgAlert(_SYSTEM_PROCCESS);
			  endif;
		  } else
			  print $core->msgStatus();
	  } 
	  
	  
	  /**
	  * Product::ThanksOrderDetails()
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ThanksOrderDetails($sessionorderid)
	  {
		  global $db, $core;
		  
          $sql = "SELECT  om.*,loc.location_name,loc.address1 ,om.location_id as locationid"
				."\n FROM `res_order_master` AS om"
				."\n INNER JOIN res_location_master AS loc ON om.location_id = loc.id"
				. "\n WHERE om.orderid = '".$sessionorderid."' ";// &&  ui.id = '".$_SESSION['fuid']."'";
				
           $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  } 
	  
	  /**
	   * Product::ThanksProducts() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ThanksProducts($ordermaster)
	  {
		  global $db, $core;	
		  
               $sql = "SELECT  od.*, mim.item_name,mim.item_description,rcm.category_name,
					  IFNULL((SELECT SUM( `price` * `qty` )
						 FROM `res_order_menutopping_details`
						 WHERE od.order_detail_id = order_detail_id
					   ),0) AS total_price "
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n LEFT JOIN res_category_master AS rcm ON mim.category_id = rcm.id "
				."\n WHERE od.order_id ='".$ordermaster."'"; 
			
		   /***************upto 10th, Dec, commente********************
		   $sql = "SELECT  od.*, mim.item_name,mim.item_description "
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n WHERE od.order_id ='".$ordermaster."'"; 		
			*************************************************************/	
				
           $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  } 
	  
	  /**
	  * Product::ShowProductFinalAmount() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ShowProductFinalAmount($ordermaster)
	  {
		  global $db, $core;	
		  
           $sql = "SELECT gross_amount,net_amount,delivery_fee,additional_fee,sales_tax,gratuity,sales_tax_rate,tip,coupon_id,coupon_discount "
				 . "\n FROM `res_order_master`"
				
				 . "\n WHERE orderid ='".$ordermaster."'"; 
           $row = $db->first($sql);
          
		  return ($row) ? $row : 0;
	  } 
	  
	  
	  /* 
	* Product:LocationDetailsByDefoult()
	*/
	public function LocationDetailsByDefoult($locationid)
	{
		global $db, $core;
		
		$sql = "SELECT `location_name`,`address1`,`banner_image`,`banner_link`, id FROM `res_location_master` WHERE `id` = '".$locationid."'";
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}		
	
	 /* 
	* Product:OptionName()
	*/
	public function OptionName($order_detail_id)
	{
		global $db, $core;
		
		$sql = "SELECT mom.*,omd.* " 
		."\n FROM `res_order_menutopping_details` AS omd " 
		."\n LEFT JOIN `res_menu_option_master` AS mom ON mom.option_id = omd.`option_id` " 
		."\n WHERE omd.`order_detail_id` = '".$order_detail_id."' GROUP BY  mom.option_id ORDER BY omd.order_menu_topping_id";
		
		$row = $db->fetch_all($sql);
	    return ($row) ? $row : 0;		
	}
	
		 /**
	   * Product::thanksToppingList()
	   *
	   * @return
	   */
		public function thanksToppingList($order_detail_id){
			global $db, $core;
			
			 error_reporting(0);
				  
			 
		 	   $sql  = "SELECT bt.*,rm.choice_name "
			   . "\n FROM res_order_menutopping_details AS bt "
			   . "\n LEFT JOIN res_menu_option_choice_master AS rm ON bt.option_choice_id =rm.option_choice_id "			  
			   . "\n WHERE bt.order_detail_id = '".$order_detail_id."'"; 
			   
			  $row = $db->fetch_all($sql);
			  return ($row) ? $row : 0;			  
		
	 	 }			
	
	   /**
	   * Product::thanksToppingListNew()
	   *
	   * @return
	   */
		public function thanksToppingListNew($option_id,$order_detail_id){
			global $db, $core;
						
			   error_reporting(0);
			  	
			   $sql  = "SELECT bt.*,rm.choice_name  "
			   . "\n FROM res_order_menutopping_details AS bt"
			   . "\n LEFT JOIN res_menu_option_choice_master AS rm ON bt.option_choice_id =rm.option_choice_id "			  
			   . "\n WHERE bt.option_id = '".$option_id."' && bt.order_detail_id = '".$order_detail_id."' order by bt.option_topping_name"; 
			   
			  $row = $db->fetch_all($sql);
			  return ($row) ? $row : 0;			  
		
	 	}			 
	/* 
	* Product:LocationDetailsByDefoult()
	*/
	public function phoneNoEmailByLocation($locationid)
	{
		global $db, $core;
		
		 $sql = "SELECT phone_number,location_name "		
			."\n FROM `res_location_master` "			
			."\n WHERE `id` = '".$locationid."'";
		
		
		$row = $db->first($sql);
	   if($row)
	   {
	   	return $row['phone_number'];
	   }
	}	
	
	/* 
	* Product:resLocationINemail()
	*/
	public function resLocationINemail($locationid)
	{
		global $db, $core;
		 $sql = "SELECT phone_number,location_name "		
			."\n FROM `res_location_master` "			
			."\n WHERE `id` = '".$locationid."'";
		
		
		$row = $db->first($sql);
	   if($row)
	   {
	   	return $row['location_name'];
	   }
	}	
	
	/* 
	* Product:getPaymentMethodNType()
	*/
	public function getPaymentMethodNType($order_number)
	{
		global $db, $core;
		
		$sql = "SELECT * "		
			."\n FROM `res_order_master` "			
			."\n WHERE `order_number` = '".$order_number."'";
		
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}
	
	/* 
	* Product:getLocationDetails()
	*/
	public function getLocationDetails($locationid)
	{
		global $db, $core;
		
		 $sql = "SELECT emai_disclaimer,restaurant_name,location_name,phone_number,phone_number1,address1,address2,state_name,city_name,order_email1,order_email2,order_email3,order_email4"		
			."\n FROM `res_location_master` AS rlm "
			."\n LEFT JOIN  res_city_master AS rcm ON rlm.city_id = rcm.id"	
			."\n LEFT JOIN  res_state_master AS rsm ON rlm.state_id = rsm.id"			
			."\n WHERE rlm.`id` = '".$locationid."'";
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
	/* 
	* Product:getLocationDetails()
	*/
	public function getOrderDateNtime($order_number)
	{
		global $db, $core;
		
		$sql = "SELECT created_date AS order_date,pickup_date,pickup_time "		
			."\n FROM `res_order_master` "					
			."\n WHERE `order_number` = '".$order_number."'";
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
		
	
	//for xml copy added by tirth start here
	/* 
	* Product:OrderDetails()
	*/
	public function OrderDetails($orderid)
	{
		global $db, $core;
		$sql = "SELECT `rom`.*, `rlm`.`site_id`, `rlm`.`pos_password`, `rlm`.`cash_payment_id`, `rlm`.`online_payment_id` FROM `res_order_master` AS `rom` INNER JOIN `res_location_master` AS `rlm` ON `rom`.`location_id` = `rlm`.`id` WHERE `orderid` = '" . (int)$orderid . "'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}
	/* 
	* Product:OrderProducts()
	*/
	public function OrderProducts($orderid)
	{
				global $db, $core;
		$sql = "SELECT `rod`.* , `rmim`.`item_name` , `rmim`.`ticket_item_id` , `rod`.`menu_size_map_id`, `rmim`.`size_id` , `rmim`.`item_type` , "
			."\n `rmim`.`menu_type` , `rmim`.`price`,"
			."\n CASE `rmim`.`item_type` WHEN '1' THEN "
			."\n (SELECT `rmsm1`.`size_name` FROM `res_menu_size_mapping` AS `rmsm` INNER JOIN  `res_menu_size_master` AS `rmsm1` ON `rmsm`.`size_id` = `rmsm1`.`id` WHERE `rmsm`.`id` = `rod`.`menu_size_map_id` AND `rmsm`.`menu_item_id` = `rod`.`menu_item_id`)"
			."\n ELSE NULL END `size_name`,"
			."\n CASE `rmim`.`item_type` WHEN '1' THEN "
			."\n (SELECT `rmsm1`.`ticket_size_id` FROM `res_menu_size_mapping` AS `rmsm` INNER JOIN  `res_menu_size_master` AS `rmsm1` ON `rmsm`.`size_id` = `rmsm1`.`id` WHERE `rmsm`.`id` = `rod`.`menu_size_map_id` AND `rmsm`.`menu_item_id` = `rod`.`menu_item_id`)"
			."\n ELSE (SELECT `rmsm2`.`ticket_size_id` FROM `res_menu_size_master` AS `rmsm2` INNER JOIN  `res_menu_item_master` AS `rmsm3` ON `rmsm3`.`size_id` = `rmsm2`.`id`  WHERE `rmsm3`.`id` = `rod`.`menu_item_id`) END `size_id`,"
			."\n CASE `rmim`.`item_type` WHEN '1' THEN "
			."\n (SELECT `rmsm`.`price` FROM `res_menu_size_mapping` AS `rmsm` INNER JOIN  `res_menu_size_master` AS `rmsm1` ON `rmsm`.`size_id` = `rmsm1`.`id` WHERE"
			."\n `rmsm`.`id` = `rod`.`menu_size_map_id` AND `rmsm`.`menu_item_id` = `rod`.`menu_item_id`)"
			."\n ELSE `rmim`.`price` END `size_price`"
			."\n FROM `res_order_details` AS `rod` "
			."\n INNER JOIN `res_menu_item_master` AS `rmim` ON `rod`.`menu_item_id` = `rmim`.`id` "
			."\n WHERE `rod`.`order_id` = '" . (int)$orderid . "'";
		$row = $db->fetch_all($sql);
	    return ($row) ? $row : 0;
	}
	
	/* 
	* Product:OrderProductsOptions()
	*/
	public function OrderProductsOptions($orderdetailid)
	{
		global $db, $core;
		$sql = "SELECT `romd`.`option_id`, `romd`.`option_topping_id`, "
			."\n (SELECT `option_type` FROM `res_menu_option_master` WHERE `option_id` = `romd`.`option_id`) AS `option_type`, "
			."\n (SELECT `item_name` FROM `res_menu_item_master` WHERE `id` = `romd`.`option_topping_id`) AS `item_name`, "
			."\n (SELECT `ticket_item_id` FROM `res_menu_item_master` WHERE `id` = `romd`.`option_topping_id`) AS `ticket_item_id`"
			."\n FROM `res_order_menutopping_details` AS `romd` "
			."\n INNER JOIN `res_menu_item_master` AS `rmim` ON `rod`.`menu_item_id` = `rmim`.`id` "
			."\n WHERE `romd`.`order_detail_id` = '" . (int)$orderdetailid . "'";
		$row = $db->fetch_all($sql);
	    return ($row) ? $row : 0;
	}
	/* 
	* Product:OrderProductsToppingOptions()
	*/
	public function OrderProductsToppingOptions($orderdetailid)
	{
		global $db, $core;
		$sql = "SELECT `rmom`.`option_type` , `rmom`.`option_name` , `rmim`.`ticket_item_id` , `rmim`.`price` AS option_price, `romd`.price , `rmim`.`menu_type`, `rmotm`.`topping_name`,"
			."\n (SELECT choice_name FROM res_menu_option_choice_master WHERE option_choice_id = `romd`.`option_choice_id`) AS choice_name"
			."\n FROM `res_order_menutopping_details` AS `romd`"
			."\n INNER JOIN `res_menu_option_master` AS `rmom` ON `romd`.`option_id` = `rmom`.`option_id`"
			."\n INNER JOIN `res_menu_option_topping_master` AS `rmotm` ON ( `rmotm`.`option_topping_id` = `romd`.`option_topping_id`"
			."\n AND `rmotm`.`option_id` = `romd`.`option_id` )"
			."\n INNER JOIN `res_menu_item_master` AS `rmim` ON `rmim`.`id` = `rmotm`.`menu_item_id`"
			."\n WHERE `romd`.`order_detail_id` = '" . (int)$orderdetailid . "'";
		$row = $db->fetch_all($sql);
	    return ($row) ? $row : 0;
	}
	
	
		/**
	   * Product::FlyToBasket()			Note:- This function is also used for mobile site
	   * 										Modification to this might create issues another place
	   * @return
	   */
	  public function FlyToBasket()
	  {
		 global $db, $core,$menu,$wojosec;
		  	  				
				/*************set date and time of order, statsts here************************/
				 $sql43 = "SELECT order_type,location_id,d_address1,apt,d_zipcode "
				 . "\n FROM  `res_order_master`"
				 . "\n WHERE  `orderid` ='".$_POST['order_id']."'";  
						
				$orderRow = $db->first($sql43);	
				$order_type = $orderRow['order_type']; 
				$location_id = $orderRow['location_id'];   //location id here as delivery area 
				$delivery_address = ($orderRow['d_address1'] && !empty($orderRow['d_address1'])) ?  $orderRow['d_address1'] : "";
				$apt = ($orderRow['apt'] && !empty($orderRow['apt'])) ?  $orderRow['apt'] : "";
				$d_zipcode = ($orderRow['d_zipcode'] && !empty($orderRow['d_zipcode'])) ?  $orderRow['d_zipcode'] : "";				
						
			     if($order_type =='d')
				 {			   
					date_default_timezone_set("UTC");	
						
					$curentdate = date('Y-m-d');
					$isholiday = $menu->isholiday($location_id,$curentdate);
					if(!empty($isholiday))
					{
						echo "Today is holiday, Do you want to place order for next day";?>
						<select class="span12" name="order_time">
							<option value=""></option>
						</select>
					<?php }
					else
					{
					$getday = date('l', strtotime($curentdate));
					$startEndTime = $this->StartEndTime($location_id,$getday);
					$TimeZone = $this->TimeZone($location_id);	
					$addhour  = 60*60*($TimeZone['hour_diff']);
					$addmin = 60*$TimeZone['minute_diff'];
					$daylight = 60*60*$TimeZone['daylight_saving'];
					$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
					$date =   date('m/d/Y',strtotime($datetime)); 
					$time =   date('H:i:s',strtotime($datetime));   
					$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
					$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));
					
					if($startEndTime['is_holidays'])
					{
						echo "today is Holiday";?>
                        <select class="span12" name="order_time">
                        <option value=""></option>
                        </select>
                    <?php
					}
					elseif($startEndTime['open_24hours'])
					{
						//$time = round(time() / 300) * 300;
						//$curent_time = date("h:i:s A",$time);
						$delivaryprepationtime = $menu->getPreprationtime($location_id);
						$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
						//$deliverydate = date('m/d/Y');
						$_SESSION['chooseAddress'] = $location_id;
						$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
						$_SESSION['orderDate'] = $date;
						$_SESSION['orderHour'] = $hour;
						$_SESSION['zip_code'] = $d_zipcode;
						$_SESSION['apt'] = $apt;
						$_SESSION['delivery_address'] = $delivery_address;
					}
					else
					{
						if($time<$starttime)
						{							
							$delivaryprepationtime = $menu->getPreprationtime($location_id);
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttime)));
							
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
						}
						elseif($time>=$starttime && $time <=$lasttime)
						{							
							//$timess = round(time() / 300) * 300;
							//"curent_time".$curent_time = date("h:i:s A",$timess);
							$delivaryprepationtime = $menu->getPreprationtime($location_id);
							$hour =  date('h:i:s A', strtotime("+".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
							$deliverydate = date('m/d/Y');
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
						}
						elseif($time>=$lasttime)
						{
							$dayscurent = date('D', strtotime($date.' +1 day'));
									     switch ($dayscurent) {
										case "Mon":
											$nextday = 'Monday';
											break;
										case "Tue":
											$nextday = 'Tuesday';
											break;
										case "Wed":
											$nextday = 'Wednesday';
											break;
										case "Thu":
											$nextday = 'Thursday';
											break;
										case "Fri":
											$nextday = 'Friday';
											break;
										case "Sat":
											$nextday = 'Saturday';
											break;
										case "Sun":
											$nextday = 'Sunday';
											break;
									}					
							$startEndTimeNextDay = $this->StartEndTime($location_id,$nextday);							
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($location_id);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
							print "close-". $deliverydate. " " . $hour;
						}
					  }
			        }
						
				  }
				 else
				 {
					 
						date_default_timezone_set("UTC");  		
						$curentdate = date('Y-m-d');
						$isholiday = $menu->isholiday($location_id,$curentdate);
						if(!empty($isholiday))
						{
							echo "Today is holiday, Do you want to place order for next day";?>
							<select class="span12" name="order_time">
							<option value=""></option>
							</select>
						<?php }
						else
						{
								$getday = date('l', strtotime($curentdate));
								$startEndTime = $this->StartEndTime($location_id,$getday);
								$TimeZone = $this->TimeZone($location_id);
								/* echo "currenttime".$currenttime =  date("m/d/Y h:i:s A", time()); echo "<br/>";	*/
								$addhour  = 60*60*($TimeZone['hour_diff']);
								$addmin = 60*$TimeZone['minute_diff'];
								$daylight = 60*60*$TimeZone['daylight_saving'];
								$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
								$date =   date('m/d/Y',strtotime($datetime)); 
								$time =   date('H:i:s',strtotime($datetime)); 				
								$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
								$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));		
								
								if($startEndTime['is_holidays'])
								{
									echo "today is Holiday";?>
									<select class="span12" name="order_time">
									<option value=""></option>
									</select>
								<?php
								}
								elseif($startEndTime['open_24hours'])
								{
									$delivaryprepationtime = $menu->getPreprationtime($location_id);
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
									
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
									$_SESSION['orderHour'] = $hour;
								}
								else
								{
									if($time<$starttime)
									{							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttime)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
									}
									elseif($time>=$starttime && $time <=$lasttime)
									{							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour =  date('h:i:s A', strtotime("+".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
									}
									elseif($time>=$lasttime)
									{
										$dayscurent = date('D', strtotime($date.' +1 day'));	
													switch ($dayscurent) {
													case "Mon":
														$nextday = 'Monday';
														break;
													case "Tue":
														$nextday = 'Tuesday';
														break;
													case "Wed":
														$nextday = 'Wednesday';
														break;
													case "Thu":
														$nextday = 'Thursday';
														break;
													case "Fri":
														$nextday = 'Friday';
														break;
													case "Sat":
														$nextday = 'Saturday';
														break;
													case "Sun":
														$nextday = 'Sunday';
														break;
												}	
																		
										$startEndTimeNextDay = $this->StartEndTime($location_id,$nextday);							
										$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);							
										$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
										$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $deliverydate;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
										print "close-". $deliverydate. " " . $hour;
									}
								}
						}
						
						
				  }
				  
				  $_SESSION['orderType'] = ($order_type=='d') ? "delivery" : "pick_up";				 
				  $_SESSION['chooseAddress'] = $location_id;
			  
			     /*************set date and time of order, ends here************************/
				
				
				 	$order_id  = $_POST['order_id'];
					
				 	$sqlrodermaster = "SELECT rom.orderid, rod.menu_item_id, rod.menu_size_map_id, rod.price, rod.comments, rod.qty ,rod.order_detail_id"
							 . "\n FROM `res_order_master` AS rom "
							 . "\n INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id "
							 . "\n WHERE rom.orderid = '".$_POST['order_id']."'"; 
							 
					 $rowordermaster = $db->fetch_all($sqlrodermaster);						
		    
			foreach($rowordermaster AS $rowom){
				
				
						$productID = $rowom['menu_item_id'];
						$menu_size_map_id = (empty($rowom['menu_size_map_id'])) ?  "0" : $rowom['menu_size_map_id'];
						$qty = (empty($rowom['qty'])) ?  "0" : $rowom['qty'];
						//$option_topping_id = $row['option_topping_id'];
						$item_price = $rowom['price'];
						$option_id = (empty($rowom['option_id'])) ?  "0" : $rowom['option_id'];
						//$optionChoiceId  = $row['option_choice_id']; 
						$additional_notes =(empty($rowom['comments'])) ?  "" : $rowom['comments'];
						$basketSession  = SESSION_COOK;
						if (!empty($productID)){ 
						  	$query = $db->query("SELECT item_name,price FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'");
						  	$db->numrows($query);
						  
						  if ($db->numrows($query)== 0) {
									 $core->msgs['productID'] = "Some products of order are not available right now";	
							  }
							   else {
								  
								  $sql = "SELECT item_name,price  FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'";									
								  $row = $db->first($sql);
								  
								  $productPrice = $row['price'];
								  $product_name = $row['item_name'];	 
							  }
						}
			    
			  			  
						$data = array(
							  'productID'=> $productID,
							  'user_id'=> $_SESSION['cid'],
							  'menu_size_map_id'=> $menu_size_map_id,
							  'qty'=> $qty, 
							  'productPrice'=> $item_price,
							  'basketSession' => $basketSession,
							  'additional_notes' => $additional_notes,
							  'created_date'=>"NOW()"
						);						  
				    
				 	   $db->insert("res_baskets", $data);
					   $insert_id = $db->insertid();
					
					  	$sqltoppingorder = "SELECT *"
										. "\n FROM res_order_menutopping_details"
										. "\n WHERE order_detail_id = '".$rowom['order_detail_id']."'";
						
						$toppingrow = $db->fetch_all($sqltoppingorder);		 
			      
						
					  //foreach starts here				  
					  foreach($toppingrow as $trow){  
						
						$option_topping_id = $trow['option_topping_id'];
						$option_id = (empty($trow['option_id'])) ?  "0" : $trow['option_id'];
						$optionChoiceId  = $trow['option_choice_id']; 
						$qty = (empty($trow['qty'])) ?  "0" : $trow['qty'];
						$price = (empty($trow['price'])) ?  "0" : $trow['price'];
						$option_topping_name = (empty($trow['option_topping_name'])) ?  "" : $trow['option_topping_name'];
						
						$data1 = array(
								  'product_id'=> $data['productID'],
								  'option_topping_id' => $option_topping_id,
								  'option_id'=>	$option_id,		
								  'qty'=> $qty,	
								  'price'=>$price,
								  'option_topping_name' => $option_topping_name,
								  'option_choice_id' =>	$optionChoiceId, 
								  'basketID'=>$insert_id,
								  'basketSession' => $basketSession
						);										
						$db->insert("res_basket_topping", $data1);					
						
							
					 }//foreach $row3,  ends				 
    		    }//foreach ends here outer
				
				echo "1";
					
				 /* 
				  $message = "<div class=\"jquery-notify-bar success\">Product <strong> " . ucwords(@$product_name) . " </strong> has been added successfully to basket.</div>";			  
				  echo $message;
			      ($db->affected()) ? $wojosec->writeLog($message, "", "no", " add to basket") :  $core->msgAlert(_SYSTEM_PROCCESS);
				  */
				
		   
	  }
	  
	  
	 /**
	   * Product::FlyToBasket_previous()			Note:- This function is also used for mobile site
	   * 										Modification to this might create issues another place
	   * @return
	   */
	  public function FlyToBasket_previous()
	  {
		 global $db, $core,$menu,$wojosec;
		 
		
		  	  				
				/*************set date and time of order, statsts here************************/
				 $sql43 = "SELECT order_type,location_id,d_address1,apt,d_zipcode "
				 . "\n FROM  `res_order_master`"
				 . "\n WHERE  `orderid` ='".$_POST['order_id']."'"; 
				 
				
						
				$orderRow = $db->first($sql43);	
				$order_type = $orderRow['order_type']; 
				$location_id = $orderRow['location_id'];   //location id here as delivery area 
				$delivery_address = ($orderRow['d_address1'] && !empty($orderRow['d_address1'])) ?  $orderRow['d_address1'] : "";
				$apt = ($orderRow['apt'] && !empty($orderRow['apt'])) ?  $orderRow['apt'] : "";
				$d_zipcode = ($orderRow['d_zipcode'] && !empty($orderRow['d_zipcode'])) ?  $orderRow['d_zipcode'] : "";				
						
			     if($order_type =='d')
				 {			   
					date_default_timezone_set("UTC");	
						
					$curentdate = date('Y-m-d');
					$isholiday = $menu->isholiday($location_id,$curentdate);
					if(!empty($isholiday))
					{
						echo "Today is holiday, Do you want to place order for next day";?>
						<select class="span12" name="order_time">
							<option value=""></option>
						</select>
					<?php }
					else
					{
					$getday = date('l', strtotime($curentdate));
					$startEndTime = $this->StartEndTime($location_id,$getday);
					$TimeZone = $this->TimeZone($location_id);	
					$addhour  = 60*60*($TimeZone['hour_diff']);
					$addmin = 60*$TimeZone['minute_diff'];
					$daylight = 60*60*$TimeZone['daylight_saving'];
					$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
					$date =   date('m/d/Y',strtotime($datetime)); 
					$time =   date('H:i:s',strtotime($datetime));   
					$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
					$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));
					
					if($startEndTime['is_holidays'])
					{
						echo "today is Holiday";?>
                        <select class="span12" name="order_time">
                        <option value=""></option>
                        </select>
                    <?php
					}
					elseif($startEndTime['open_24hours'])
					{
						//$time = round(time() / 300) * 300;
						//$curent_time = date("h:i:s A",$time);
						$delivaryprepationtime = $menu->getPreprationtime($location_id);
						$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
						//$deliverydate = date('m/d/Y');
						$_SESSION['chooseAddress'] = $location_id;
						$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
						$_SESSION['orderDate'] = $date;
						$_SESSION['orderHour'] = $hour;
						$_SESSION['zip_code'] = $d_zipcode;
						$_SESSION['apt'] = $apt;
						$_SESSION['delivery_address'] = $delivery_address;
					}
					else
					{
						if($time<$starttime)
						{							
							$delivaryprepationtime = $menu->getPreprationtime($location_id);
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttime)));
							
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
						}
						elseif($time>=$starttime && $time <=$lasttime)
						{							
							//$timess = round(time() / 300) * 300;
							//"curent_time".$curent_time = date("h:i:s A",$timess);
							$delivaryprepationtime = $menu->getPreprationtime($location_id);
							$hour =  date('h:i:s A', strtotime("+".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
							$deliverydate = date('m/d/Y');
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
						}
						elseif($time>=$lasttime)
						{
							$dayscurent = date('D', strtotime($date.' +1 day'));
									     switch ($dayscurent) {
										case "Mon":
											$nextday = 'Monday';
											break;
										case "Tue":
											$nextday = 'Tuesday';
											break;
										case "Wed":
											$nextday = 'Wednesday';
											break;
										case "Thu":
											$nextday = 'Thursday';
											break;
										case "Fri":
											$nextday = 'Friday';
											break;
										case "Sat":
											$nextday = 'Saturday';
											break;
										case "Sun":
											$nextday = 'Sunday';
											break;
									}					
							$startEndTimeNextDay = $this->StartEndTime($location_id,$nextday);							
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($location_id);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							$_SESSION['chooseAddress'] = $location_id;
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $d_zipcode;
							$_SESSION['apt'] = $apt;
							$_SESSION['delivery_address'] = $delivery_address;
							print "close-". $deliverydate. " " . $hour;
						}
					  }
			        }
						
				  }
				 else
				 {
					 
						date_default_timezone_set("UTC");  		
						$curentdate = date('Y-m-d');
						$isholiday = $menu->isholiday($location_id,$curentdate);
						if(!empty($isholiday))
						{
							echo "Today is holiday, Do you want to place order for next day";?>
							<select class="span12" name="order_time">
							<option value=""></option>
							</select>
						<?php }
						else
						{
								$getday = date('l', strtotime($curentdate));
								$startEndTime = $this->StartEndTime($location_id,$getday);
								$TimeZone = $this->TimeZone($location_id);
								/* echo "currenttime".$currenttime =  date("m/d/Y h:i:s A", time()); echo "<br/>";	*/
								$addhour  = 60*60*($TimeZone['hour_diff']);
								$addmin = 60*$TimeZone['minute_diff'];
								$daylight = 60*60*$TimeZone['daylight_saving'];
								$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
								$date =   date('m/d/Y',strtotime($datetime)); 
								$time =   date('H:i:s',strtotime($datetime)); 				
								$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
								$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));		
								
								if($startEndTime['is_holidays'])
								{
									echo "today is Holiday";?>
									<select class="span12" name="order_time">
									<option value=""></option>
									</select>
								<?php
								}
								elseif($startEndTime['open_24hours'])
								{
									$delivaryprepationtime = $menu->getPreprationtime($location_id);
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
									
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
									$_SESSION['orderHour'] = $hour;
								}
								else
								{
									if($time<$starttime)
									{							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttime)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
									}
									elseif($time>=$starttime && $time <=$lasttime)
									{							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour =  date('h:i:s A', strtotime("+".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
									}
									elseif($time>=$lasttime)
									{
										$dayscurent = date('D', strtotime($date.' +1 day'));	
													switch ($dayscurent) {
													case "Mon":
														$nextday = 'Monday';
														break;
													case "Tue":
														$nextday = 'Tuesday';
														break;
													case "Wed":
														$nextday = 'Wednesday';
														break;
													case "Thu":
														$nextday = 'Thursday';
														break;
													case "Fri":
														$nextday = 'Friday';
														break;
													case "Sat":
														$nextday = 'Saturday';
														break;
													case "Sun":
														$nextday = 'Sunday';
														break;
												}	
																		
										$startEndTimeNextDay = $this->StartEndTime($location_id,$nextday);							
										$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
										$delivaryprepationtime = $menu->getPreprationtime($location_id);							
										$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
										$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $deliverydate;//$_POST['pickupdate_time'];
										$_SESSION['orderHour'] = $hour;
										print "close-". $deliverydate. " " . $hour;
									}
								}
						}
						
						
				  }
				  
				  $_SESSION['orderType'] = ($order_type=='d') ? "delivery" : "pick_up";				 
				  $_SESSION['chooseAddress'] = $location_id;
			  
			     /*************set date and time of order, ends here************************/
				
				
				 	$order_id  = $_POST['order_id'];
					
				 	$sqlrodermaster = "SELECT rom.orderid, rod.menu_item_id, rod.menu_size_map_id, rod.price, rod.comments, rod.qty ,rod.order_detail_id"
							 . "\n FROM `res_order_master` AS rom "
							 . "\n INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id "
							 . "\n WHERE rom.orderid = '".$_POST['order_id']."'"; 
							 
					 $rowordermaster = $db->fetch_all($sqlrodermaster);						
		    
			foreach($rowordermaster AS $rowom){
				
				
						$productID = $rowom['menu_item_id'];
						$menu_size_map_id = (empty($rowom['menu_size_map_id'])) ?  "0" : $rowom['menu_size_map_id'];
						$qty = (empty($rowom['qty'])) ?  "0" : $rowom['qty'];
						//$option_topping_id = $row['option_topping_id'];
						$item_price = $rowom['price'];
						$option_id = (empty($rowom['option_id'])) ?  "0" : $rowom['option_id'];
						//$optionChoiceId  = $row['option_choice_id']; 
						$additional_notes =(empty($rowom['comments'])) ?  "" : $rowom['comments'];
						$basketSession  = SESSION_COOK;
						if (!empty($productID)){ 
						  	$query = $db->query("SELECT item_name,price FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'");
						  	$db->numrows($query);
						  
						  if ($db->numrows($query)== 0) {
									 $core->msgs['productID'] = "Some products of order are not available right now";	
							  }
							   else {
								  
								  $sql = "SELECT item_name,price  FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'";									
								  $row = $db->first($sql);
								  
								  $productPrice = $row['price'];
								  $product_name = $row['item_name'];	 
							  }
						}
			    
			  			  
						$data = array(
							  'productID'=> $productID,
							  'user_id'=> $_SESSION['cid'],
							  'menu_size_map_id'=> $menu_size_map_id,
							  'qty'=> $qty, 
							  'productPrice'=> $item_price,
							  'basketSession' => $basketSession,
							  'additional_notes' => $additional_notes,
							  'created_date'=>"NOW()"
						);						  
				    
				 	   $db->insert("res_baskets", $data);
					   $insert_id = $db->insertid();
					
					  	$sqltoppingorder = "SELECT *"
										. "\n FROM res_order_menutopping_details"
										. "\n WHERE order_detail_id = '".$rowom['order_detail_id']."'";
						
						$toppingrow = $db->fetch_all($sqltoppingorder);		 
			      
						
					  //foreach starts here				  
					  foreach($toppingrow as $trow){  
						
						$option_topping_id = $trow['option_topping_id'];
						$option_id = (empty($trow['option_id'])) ?  "0" : $trow['option_id'];
						$optionChoiceId  = $trow['option_choice_id']; 
						$qty = (empty($trow['qty'])) ?  "0" : $trow['qty'];
						$price = (empty($trow['price'])) ?  "0" : $trow['price'];
						$option_topping_name = (empty($trow['option_topping_name'])) ?  "" : $trow['option_topping_name'];
						
						$data1 = array(
								  'product_id'=> $data['productID'],
								  'option_topping_id' => $option_topping_id,
								  'option_id'=>	$option_id,		
								  'qty'=> $qty,	
								  'price'=>$price,
								  'option_topping_name' => $option_topping_name,
								  'option_choice_id' =>	$optionChoiceId, 
								  'basketID'=>$insert_id,
								  'basketSession' => $basketSession
						);										
						$db->insert("res_basket_topping", $data1);					
						
							
					 }//foreach $row3,  ends				 
    		    }//foreach ends here outer
				
				echo "1";
					
				 /* 
				  $message = "<div class=\"jquery-notify-bar success\">Product <strong> " . ucwords(@$product_name) . " </strong> has been added successfully to basket.</div>";			  
				  echo $message;
			      ($db->affected()) ? $wojosec->writeLog($message, "", "no", " add to basket") :  $core->msgAlert(_SYSTEM_PROCCESS);
				  */
				
		   
	  }
	
	 
	 
	 
	   /**
       * Menu::getCompanyname()
       * 
       * @return
       */
	   public function getCompanyname($locationid)
	  {	  	
	  	global $db;			
	  	  $sql  = "SELECT rcm.company_name FROM"
				."\n `res_location_master` AS rlm"
				."\n LEFT JOIN `res_company_master`  AS rcm ON rcm.id = rlm.company_id"
				."\n  WHERE rlm.id = '".$locationid."'";
		 $row = $db->first($sql);
		 return ($row) ? $row['company_name'] : 0;
	  }
	  
	/* 
	* Product:AuthorizeNetDetails()
	*/
	public function AuthorizeNetDetails($locationid)
	{
		global $db, $core;
		$sql = "SELECT `id`,`is_authorize`,`authorizr_api_id`,`authorizze_trans_key`,`authorize_hash_key`"
		      ."\n FROM `res_location_master` "
			  ."\n WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
	
	  
	/* 
	* Product:AuthorizeNetDetails()
	*/
	public function IsmercuryDetails($locationid)
	{
		global $db, $core;
		$sql = "SELECT `id`,`merchant_id`,`merchant_password`"
		      ."\n FROM `res_location_master` "
			  ."\n WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
		/* 
	* Product:PaypalPaymentDetails()
	*/
	public function PaypalPaymentDetails($locationid)
	{
		global $db, $core;
		$sql = "SELECT `id`,`paypal_email_id`,`paypal_password`,`paypal_signature`"
		      ."\n FROM `res_location_master` "
			  ."\n WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}	
	
	/* 
	* Product:showOptinChoicName()
	*/
	public function showOptinChoicName($choiceId)
	{
		global $db, $core;
		
		$sql = "SELECT choice_name "
			  ."\n FROM `res_menu_option_choice_master` "
			  ."\n WHERE option_choice_id ='".$choiceId."'";
			  
		$row = $db->first($sql);
	    return ($row) ? $row['choice_name'] : 0;
	}
	
	/* 
	* Product:GetStateNcity()
	*/
	public function GetStateNcity($locationId)
	{
		global $db, $core;
		
		$sql = "SELECT rcm.city_name,rcm.id AS city_id,rsm.state_name,rsm.id AS state_id, rm.country_name,rm.id AS country_id "
			  . "\n FROM  `res_location_master` AS rlm "
			  . "\n LEFT JOIN  `res_city_master` AS rcm ON rlm.city_id = rcm.id "
			  . "\n LEFT JOIN  `res_state_master` AS rsm ON rlm.state_id = rsm.id "
			  . "\n LEFT JOIN  `res_country_master` AS rm ON rlm.country_id = rm.id "
			  . "\n WHERE rlm.id ='".$locationId."'"; 
			  
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}		
	
	/* 
	* Product:landingpageHours ()
	*/
	public function landingpageHours($website_flow)
	{
		global $db, $core;
		
		 $sql = "SELECT rlm.`restorant_time` "
		
		      . "\n FROM `res_install_master` rim "
			  
		      . "\n LEFT JOIN `res_location_master` AS rlm  ON rim.default_location = rlm.id "
			  
		      . "\n  WHERE rim.flow='".$website_flow."' "; 
			  
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}
	
	  /**
	   * Product::FlyToRepeatLastOrder()
	   * 
	   * @return
	   */
	  public function FlyToRepeatLastOrder()
	  {
		  global $db, $core, $wojosec;
		  
		    
			    $sql9 = "SELECT MAX(`orderid`) AS orderid  "
				        . "\n FROM  `res_order_master`"
				        . "\n WHERE  `customer_id` ='".$_SESSION['cid']."'";
							
				 $row9 = $db->first($sql9);			  
			 	 $order_id = $row9['orderid'];
		  	
				
				 //$order_id  = $_POST['order_id'];
			 
				 $sqlrodermaster = "SELECT rom.orderid, rod.menu_item_id, rod.menu_size_map_id, rod.price, rod.comments, rod.qty,rod.order_detail_id "
								 . "\n FROM `res_order_master` AS rom "
								 . "\n INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id "
								 . "\n WHERE rom.orderid = '".$order_id."'"; 
							
					$rowordermaster = $db->fetch_all($sqlrodermaster);		
						
				foreach($rowordermaster AS $rowom){
						
						$productID = $rowom['menu_item_id'];
						$menu_size_map_id = (empty($rowom['menu_size_map_id'])) ?  "0" : $rowom['menu_size_map_id'];
						$qty = (empty($rowom['qty'])) ?  "0" : $rowom['qty'];
						//$option_topping_id = $row['option_topping_id'];
						$item_price = $rowom['price'];
						$option_id = (empty($rowom['option_id'])) ?  "0" : $rowom['option_id'];
						//$optionChoiceId  = $row['option_choice_id']; 
						$additional_notes =(empty($rowom['comments'])) ?  "" : $rowom['comments'];
						$basketSession  = SESSION_COOK;
			  
						if (!empty($productID)){ 
						  
						  	$query = $db->query("SELECT item_name,price FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'");
						  
						  	$db->numrows($query);
						  
						  if ($db->numrows($query)== 0) {
									 $core->msgs['productID'] = "Some products of order are not available right now";	
							  }
							   else {
								  
								  $sql = "SELECT item_name,price  FROM res_menu_item_master  WHERE id = '".$productID."' and active ='1'";									
								  $row = $db->first($sql);
								  
								  $productPrice = $row['price'];
								  $product_name = $row['item_name'];	 
							  }
						}
			    
			  			  
						$data = array(
							  'productID'=> $productID,
							  'user_id'=> $_SESSION['cid'],
							  'menu_size_map_id'=> $menu_size_map_id,
							  'qty'=> $qty, 
							  'productPrice'=> $item_price,
							  'basketSession' => $basketSession,
							  'additional_notes' => $additional_notes,
							  'created_date'=>"NOW()"
						);						  
				    
				 	   $db->insert("res_baskets", $data);
			
		         	   $insert_id = $db->insertid();
										
					
						$sqltoppingorder = "SELECT * "
										. "\n FROM res_order_menutopping_details"
										. "\n WHERE order_detail_id = '".$rowom['order_detail_id']."'";
						
						$toppingrow = $db->fetch_all($sqltoppingorder);	 
			      
			
					  //foreach starts here				  
					  foreach($toppingrow as $trow){  		 				  
						
						$option_topping_id = $trow['option_topping_id'];
						$option_id = (empty($trow['option_id'])) ?  "0" : $trow['option_id'];
						$optionChoiceId  = $row2['option_choice_id']; 
						$qty = (empty($trow['qty'])) ?  "0" : $trow['qty'];
						$price = (empty($trow['price'])) ?  "0" : $trow['price'];
						$option_topping_name = (empty($trow['option_topping_name'])) ?  "" : $trow['option_topping_name'];
						
						$data1 = array(
								  'product_id'=>  $data['productID'],
								  'option_topping_id' => $option_topping_id,
								  'option_id'=>	$option_id,		
								  'qty'=> $qty,	
								  'price'=>$price,
								  'option_topping_name' => $option_topping_name,	 
								  'basketID'=>$insert_id,
								  'basketSession' => $basketSession
						);										
						$db->insert("res_basket_topping", $data1);					
						
								
					 }//foreach ends				 
    		    }//foreach ends here outer
	
				
				  return "OK";				
			 
			      
		  
	  }	
	  
	/* 
	* Product:Totalbasketitem()
	*/
	public function Totalbasketitem()
	{
		global $db, $core;
		$sessionID = SESSION_COOK;
		$sql = "SELECT count(`basketID`) as total FROM `res_baskets` WHERE `basketSession` ='".$sessionID."'";
		$row = $db->first($sql);
	    return ($row) ? $row['total'] : 0;
	}
	
	   /**
       * Product::checkLocationIdMatchExistance()
       * 
       * @return
       */
	   public function checkLocationIdMatchExistance()
	  {	  	
	  	global $db;
					
	      $sql = "SELECT MAX(`orderid`) AS orderid"
			 . "\n FROM  `res_order_master` "
			 . "\n WHERE  `customer_id` ='".$_SESSION['cid']."'";
								
		  $row = $db->first($sql);	
		  
		  $order_id = $row['orderid'];	
		  
		  $sql2 = "SELECT location_id,order_type "
				 . "\n FROM  `res_order_master`"
				 . "\n WHERE  `orderid` ='".$order_id."'";
					
		  $row2 = $db->first($sql2);	
		  
		 return ($row2) ? $row2 : 0;
	  }
	
	
	/* 
	* Product:geroptiontoppingname()
	*/
	public function geroptiontoppingname($optionid)
	{
		global $db, $core;
		$sql = "SELECT instruction as option_name FROM `res_menu_option_master` WHERE `option_id` ='".$optionid."'";
		$row = $db->first($sql);
	    return ($row) ? $row['option_name'] : 0;
	}
	
	
	
	/* 
	* Product:getItemSize()
	*/
	public function getItemSize($menuSizeId)
	{
		global $db, $core;
		
		$sql = "SELECT rsm.size_name,rsm.id as sizeid
				FROM `res_baskets` AS rsb
				LEFT JOIN `res_menu_size_mapping` AS rmsm ON rsb.menu_size_map_id = rmsm.id				
				LEFT JOIN `res_menu_size_master` AS rsm ON rmsm.size_id = rsm.id 
				WHERE rsb.`menu_size_map_id` ='".$menuSizeId."'"; 
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}
	
	/* 
	* Product:HidePriceInOption()
	*/
	public function HidePriceInOption($locationid)
	{
		global $db, $core;
		
		$sql = "SELECT `hide_price_in_option`"
			   . "\n FROM `res_location_master`"
			   . "\n WHERE `id` ='".$locationid."'"; 		
		$row = $db->first($sql);
	    return ($row) ? $row['hide_price_in_option'] : 0;
	}
	
	/* 
	* Product:getItemSize_new()
	*/
	public function getItemSize_new($orderDetailId,$order_id,$menuSizeId)
	{
		global $db, $core; 
		
		 $sql = "SELECT rsm.size_name,rsm.id as sizeid
				FROM `res_order_details`  AS rsb
				LEFT JOIN `res_menu_size_mapping` AS rmsm ON rsb.menu_size_map_id = rmsm.id				
				LEFT JOIN `res_menu_size_master` AS rsm ON rmsm.size_id = rsm.id 
				WHERE rsb.`order_detail_id` ='".$orderDetailId."'  and rsb.`order_id` ='".$order_id."' and rsb.`menu_size_map_id` ='".$menuSizeId."'"; 
		
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	}
	
	   
	  
	  /**
	   * Product::AllProductForpaypal()
	   *
	   * @return
	   */
		public function AllProductForpaypal()
		{
			global $db, $core;
			
			$sql = "SELECT rb.basketID,rb.basketSession,rb.qty,rb.productID,rb.productPrice,rmim.item_name " 
			 		. "\n FROM `res_baskets` AS rb " 
					. "LEFT JOIN `res_menu_item_master` AS rmim ON rb.productID = rmim.id"
				    . "\n WHERE `rb`.`basketSession` = '" . SESSION_COOK . "'"; 
				
			$row = $db->fetch_all($sql);
			
			
			return ($row) ? $row : 0;
	 	}	
		
		/* 
	* Product:landingpageHoursInstallManager ()
	*/
	public function landingpageHoursInstallManager($websitename)
	{
		global $db, $core;
		$sql = "SELECT `hours_notes`"
		      ."\n FROM `res_install_master`"
			  ."\n WHERE website_url ='".$websitename."'"
		      ."\n LIMIT 1";
			  
		$row = $db->first($sql);
	    return ($row) ? $row : 0;
	} 	

	
	
	
  }
?>
