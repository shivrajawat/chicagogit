<?php
  /**
   * choose location for repeat last order
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
				 
	 $sql = "SELECT MAX(`orderid`) AS orderid"
			 . "\n FROM  `res_order_master` "
			 . "\n WHERE  `customer_id` ='".$_SESSION['cid']."'";
				
	 $row = $db->first($sql);			  
	 $order_id = $row['orderid'];	
	 
	 $sql2 = "SELECT order_type,location_id,d_address1,apt,d_zipcode "
			 . "\n FROM  `res_order_master`"
			 . "\n WHERE  `orderid` ='".$order_id."'"; 
				
	 $row2 = $db->first($sql2);	
	 
				$order_type = $row2['order_type']; 
				$location_id = $row2['location_id'];   //location id here as delivery area
				$delivery_address = ($row2['d_address1'] && !empty($row2['d_address1'])) ?  $row2['d_address1'] : "";
				$apt = ($row2['apt'] && !empty($row2['apt'])) ?  $row2['apt'] : "";
				$d_zipcode = ($row2['d_zipcode'] && !empty($row2['d_zipcode'])) ?  $row2['d_zipcode'] : "";		 
		
	    		/***set the order time for reorder ie. as soon as possible, starts here******/
				 date_default_timezone_set("UTC");
			    if($order_type =='d')
				 {	
					$TimeZone = $product->TimeZone($location_id);
					$addhour  = 60*60*($TimeZone['hour_diff']);
					$addmin = 60*$TimeZone['minute_diff'];
					$daylight = 60*60*$TimeZone['daylight_saving'];
					$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
					$date =   date('m/d/Y',strtotime($datetime)); 
					
					$curentdate = date('m/d/Y',strtotime($datetime));
					$isholiday = $menu->isholiday($location_id,$curentdate);
						if(!empty($isholiday))
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
																
									$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);							
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
									$delivaryprepationtime = $menu->getPreprationtime($location_id);							
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
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
							$getday = date('l', strtotime($curentdate));
							$startEndTime = $product->StartEndTime($location_id,$getday);
							
							$time =   date('H:i:s',strtotime($datetime));   
							$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
							$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	
							
							if($startEndTime['is_holidays'])
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
																
									$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);							
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
									$delivaryprepationtime = $menu->getPreprationtime($location_id);							
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $date;
									$_SESSION['orderHour'] = $hour;
									$_SESSION['zip_code'] = $d_zipcode;
									$_SESSION['apt'] = $apt;
									$_SESSION['delivery_address'] = $delivery_address;
									
						   
							}
							elseif($startEndTime['open_24hours'])
							{
								
								$delivaryprepationtime = $menu->getPreprationtime($location_id);
								$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
								
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
									
									$delivaryprepationtime = $menu->getPreprationtime($location_id);
									$hour =  date('h:i A', strtotime("+".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
									
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
																
									$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);							
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
									$delivaryprepationtime = $menu->getPreprationtime($location_id);							
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $date;
									$_SESSION['orderHour'] = $hour;
									$_SESSION['zip_code'] = $d_zipcode;
									$_SESSION['apt'] = $apt;
									$_SESSION['delivery_address'] = $delivery_address;
									
								}
							}
					}
				  }
				 else
				 {
									
							$TimeZone = $product->TimeZone($location_id);
							$addhour  = 60*60*($TimeZone['hour_diff']);
							$addmin = 60*$TimeZone['minute_diff'];
							$daylight = 60*60*$TimeZone['daylight_saving'];
							$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
				
							$curentdate = date('m/d/Y',strtotime($datetime));
							$date =   date('m/d/Y',strtotime($datetime)); 
							$isholiday = $menu->isholiday($location_id,$curentdate);
							if(!empty($isholiday))
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
																		
								$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);
								$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
								$delivaryprepationtime = $menu->getPreprationtime($location_id);							
								$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
								$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
								$_SESSION['chooseAddress'] = $location_id;
								$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
								$_SESSION['orderDate'] = $deliverydate;
								$_SESSION['orderHour'] = $hour;
													
							}
							else
							{
									$getday = date('l', strtotime($curentdate));
									$startEndTime = $product->StartEndTime($location_id,$getday);
									$time =   date('H:i:s',strtotime($datetime)); 				
									$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
									$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	
									
									if($startEndTime['is_holidays'])
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
																			
									$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);							
									
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
									$delivaryprepationtime = $menu->getPreprationtime($location_id);							
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
									
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $deliverydate;
									$_SESSION['orderHour'] = $hour;
									
										
								}
								elseif($startEndTime['open_24hours'])
								{
									$time = round($time / 300) * 300;
									$curent_time = date("H:i:s",$time);
									$delivaryprepationtime = $menu->getPreprationtime($location_id);
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
									
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $date;
									$_SESSION['orderHour'] = $hour;
								}
								else
								{
									if($time<$starttime)
									{			
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour = date('h:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttime)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;				
										$_SESSION['orderHour'] = $hour;
									}
									elseif($time>=$starttime && $time <=$lasttime)
									{										
										$delivaryprepationtime = $menu->getPreprationtime($location_id);
										$hour =  date('h:i A', strtotime("+".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
										
										$_SESSION['chooseAddress'] = $location_id;
										$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
										$_SESSION['orderDate'] = $date;
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
																		
									$startEndTimeNextDay = $product->StartEndTime($location_id,$nextday);							
									
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
									$delivaryprepationtime = $menu->getPreprationtime($location_id);							
									$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
									
									$_SESSION['chooseAddress'] = $location_id;
									$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
									$_SESSION['orderDate'] = $deliverydate;
									$_SESSION['orderHour'] = $hour;											
									}
								}
						}
					
					 
				 }
				  
				 $_SESSION['orderType'] = ($order_type=='d') ? "delivery" : "pick_up";				 
				 $_SESSION['chooseAddress'] = $location_id;
				/***set the order time for reorder ie. as soon as possible, ends here******/
   
  $basketResponce = $product->FlyToRepeatLastOrder();
 
 if($basketResponce=='OK'){
	$_SESSION['repeatOrder'] = 1;
	redirect_to("view-cart");	 
 }  
  
  
?>
