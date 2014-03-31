<?php
  /**
   * choose location from location by 
   */
  define("_VALID_PHP", true);
  require_once("../init.php"); 
  
  
  if($_POST['ordertype'] == 'delivery')
  { 
  		date_default_timezone_set("UTC");
	
   		if(!empty($_POST['asap_delivery']) && $_POST['asap_delivery']=='asap_delivery')
		{		
			$_POST['deliveryarea'];
			$TimeZone = $product->TimeZone($_POST['deliveryarea']);
			$addhour  = 60*60*($TimeZone['hour_diff']);
			$addmin = 60*$TimeZone['minute_diff'];
			$daylight = 60*60*$TimeZone['daylight_saving'];
			$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
			$date =   date('m/d/Y',strtotime($datetime));
			
			$curentdate = date('m/d/Y',strtotime($datetime));
			
	   /* $m = date('m', strtotime($datetime));
		$d = date('d', strtotime($datetime));
		$y = date('y', strtotime($datetime));$m = date('m', strtotime($datetime));
		$jd=cal_to_jd(CAL_GREGORIAN,date($m),date($d),date($y));	
		echo $currentday = (jddayofweek($jd,1));	*/
		
			$isholiday = $menu->isholiday($_POST['deliveryarea'],$curentdate);
			if(!empty($isholiday))
			{
				$dayscurent = date('D', strtotime($date.' +1 day'));	
						$nextday   =  getDayfullname($dayscurent);
													
						$startEndTimeNextDay = $product->StartEndTime($_POST['deliveryarea'],$nextday);							
						$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
						$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
						$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
						$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
						$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
						$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
						$_SESSION['orderDate'] = $deliverydate;
						$_SESSION['orderHour'] = $hour;
						$_SESSION['zip_code'] = $_POST['zip_code'];
						$_SESSION['apt'] = $_POST['apt'];
						$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
						/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
						$_SESSION['address_type_business'] = $_POST['address_type_business'];
						$_SESSION['address_type_university'] = $_POST['address_type_university'];
						$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
						$_SESSION['address_type'] = $_POST['address_type'];
						$_SESSION['business_name'] = $_POST['business_name'];
						print "close-". $deliverydate. " " . $hour;
			
			 }
			else
			{
				$getday = date('l', strtotime($curentdate));
				$startEndTime = $product->StartEndTime($_POST['deliveryarea'],$getday);
				$time =   date('H:i:s',strtotime($datetime));   
				$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
				$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	
				$d_morning_start = date('H:i:s', strtotime($startEndTime['d_morning_start']));	
				$d_morning_end = date('H:i:s', strtotime($startEndTime['d_morning_end']));	
				$d_evening_start = date('H:i:s', strtotime($startEndTime['d_evening_start']));	
				$d_evening_end = date('H:i:s', strtotime($startEndTime['d_evening_end']));	
				
				if($startEndTime['is_holidays'])
				{
								$dayscurent = date('D', strtotime($date.' +1 day'));	
								$nextday   =  getDayfullname($dayscurent);
													
						$startEndTimeNextDay = $product->StartEndTime($_POST['deliveryarea'],$nextday);							
						$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
						$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
						$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
						$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
						$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
						$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
						$_SESSION['orderDate'] = $deliverydate;
						$_SESSION['orderHour'] = $hour;
						$_SESSION['zip_code'] = $_POST['zip_code'];
						$_SESSION['apt'] = $_POST['apt'];
						$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
						/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
						$_SESSION['address_type_business'] = $_POST['address_type_business'];
						$_SESSION['address_type_university'] = $_POST['address_type_university'];
						$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
						$_SESSION['address_type'] = $_POST['address_type'];
						$_SESSION['business_name'] = $_POST['business_name'];
						print "close-". $deliverydate. " " . $hour;	
			   
				}
				elseif($startEndTime['open_24hours'])
				{
					//$time = round(time() / 300) * 300;
					//$curent_time = date("H:i:s",$time);
					$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
					$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
					
					$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
					$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
					$_SESSION['orderDate'] = $date;
					$_SESSION['orderHour'] = $hour;
					$_SESSION['zip_code'] = $_POST['zip_code'];
					$_SESSION['apt'] = $_POST['apt'];
					$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
					
					/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
					$_SESSION['address_type_business'] = $_POST['address_type_business'];
					$_SESSION['address_type_university'] = $_POST['address_type_university'];
					$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
					$_SESSION['address_type'] = $_POST['address_type'];
					$_SESSION['business_name'] = $_POST['business_name'];
				}
				else
				{
					$morningflag=0;
					$eveningflag=0;
					$isopen=0;
					$isalreadyset=0;

					if(empty($d_morning_start) || empty($d_morning_end) || $d_morning_start=='00:00:00' || $d_morning_end=='00:00:00')
						$morningflag=1;
					if(empty($d_evening_start) || empty($d_evening_end) || $d_evening_start=='00:00:00' || $d_evening_end=='00:00:00')
						$eveningflag=1;
										
					if($morningflag==0)
					{
						//If morning time are set
						$starttime = $d_morning_start;
						$lasttime = $d_morning_end;
						if($time<$starttime)
						{				
							$isalreadyset=1;
							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttime)));
							
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
						}
						elseif($time>=$starttime && $time <=$lasttime)
						{						
							//$timess = round(time() / 300) * 300;
							//$curent_time = date("H:i:s",$timess);
							//echo 'middle morning';
							//echo $time;
							$isalreadyset=1;
							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
							$hour =  date('h:i A', strtotime("+".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
							
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
						}
						elseif($time>=$lasttime)
						{
							if($eveningflag==1)
							{
								$isalreadyset=1;
								//Morning hours have been spent and evening hours are blank then get next day date
								$dayscurent = date('D', strtotime($date.' +1 day'));	
								$locationid = $_POST['deliveryarea'];
								$isNextDayOff=0;	
								
								$nextday   =  getDayfullname($dayscurent);
								
								$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);	
								
								$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
								$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
								
								if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
								elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));							
								else 
								{
									//If restaurant is closed next day then get timing of next to next day
									$dayscurent = date('D', strtotime($date.' +2 day'));	
									$isNextDayOff=1;
									$locationid = $_POST['deliveryarea'];
									
									$nextday   =  getDayfullname($dayscurent);
							
									$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);

									$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
									$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
									
									if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
										$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
									elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
										$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));
								}
								
								$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
								$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
								
								if($isNextDayOff==0)
									$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
								else 
									$deliverydate = date('m/d/Y', strtotime($date.' +2 day'));
									
								$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
								$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
								$_SESSION['orderDate'] = $deliverydate;
								$_SESSION['orderHour'] = $hour;
								$_SESSION['zip_code'] = $_POST['zip_code'];
								$_SESSION['apt'] = $_POST['apt'];
								$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
								/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
								$_SESSION['address_type_business'] = $_POST['address_type_business'];
								$_SESSION['address_type_university'] = $_POST['address_type_university'];
								$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
								$_SESSION['address_type'] = $_POST['address_type'];
								$_SESSION['business_name'] = $_POST['business_name'];
								print "close-". $deliverydate. " " . $hour;
							}
						}
					}
					if($eveningflag==0 && $isalreadyset==0)
					{	
						//If evening time are set
						$starttime = $d_evening_start;
						$lasttime = $d_evening_end;
						if($time<$starttime)
						{						
							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttime)));
							
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
						}
						elseif($time>=$starttime && $time <=$lasttime)
						{						
							//$timess = round(time() / 300) * 300;
							//$curent_time = date("H:i:s",$timess);
							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
							$hour =  date('h:i A', strtotime("+".$delivaryprepationtime['delivery_time']." minutes", strtotime($time)));
							
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
						}
						elseif($time>=$lasttime)
						{

							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$locationid = $_POST['deliveryarea'];
								
							$nextday   =  getDayfullname($dayscurent);
							
							$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);
							
							$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
							$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
							
							if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
								$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
							elseif(!empty($d_evening_start)  && $d_evening_start!='00:00:00')
								$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));							
							else 
							{
								echo 'in else';
								//If restaurant is closed next day then get timing of next to next day
								$dayscurent = date('D', strtotime($date.' +2 day'));	
								$locationid = $_POST['deliveryarea'];
								
								$nextday   =  getDayfullname($dayscurent);
							
								$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);

								
								$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
								$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
								
								if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
								elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));
							}

							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
							print "close-". $deliverydate. " " . $hour;}
					}
					
					//If both morning & evening delivery hours are not mentioned
					if($morningflag==1 && $eveningflag==1)
					{
							$isNextDayOff=0;
							//Morning hours have been spent and evening hours are blank then get next day date
							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$locationid = $_POST['deliveryarea'];
								
							$nextday   =  getDayfullname($dayscurent);
							
							$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);	

							$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
							$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
							
							if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
								$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
							elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
								$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));							
							else 
							{
								//If restaurant is closed next day then get timing of next to next day
								$isNextDayOff=1;
								$dayscurent = date('D', strtotime($date.' +2 day'));	
								$locationid = $_POST['deliveryarea'];
								
								$nextday   =  getDayfullname($dayscurent);

								$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);

								$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
								$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
								
								if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
								{
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
								}
								elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
								{
									$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));
								}
							}
							
							$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
							
							if($isNextDayOff==0)
								$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							else 
								$deliverydate = date('m/d/Y', strtotime($date.' +2 day'));
								
							$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							$_SESSION['zip_code'] = $_POST['zip_code'];
							$_SESSION['apt'] = $_POST['apt'];
							$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
							/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
							$_SESSION['address_type_business'] = $_POST['address_type_business'];
							$_SESSION['address_type_university'] = $_POST['address_type_university'];
							$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
							$_SESSION['address_type'] = $_POST['address_type'];
							$_SESSION['business_name'] = $_POST['business_name'];
							print "close-". $deliverydate. " " . $hour;
					}
				}					
			}
		}
		else
		{
			$TimeZone = $product->TimeZone($_POST['deliveryarea']);
			$addhour  = 60*60*($TimeZone['hour_diff']);
			$addmin = 60*$TimeZone['minute_diff'];
			$daylight = 60*60*$TimeZone['daylight_saving'];
			$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
			$date =   date('m/d/Y',strtotime($datetime)); 
			
			if($_POST['dhour']=='restorant_closed')
			{
				$dayscurent = date('D', strtotime($date.' +1 day'));	
				$locationid = $_POST['deliveryarea'];
				$isNextDayOff=0;
				
				$nextday   =  getDayfullname($dayscurent);
							
				$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);

				$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
				$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
				
				if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
					$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
				elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
					$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));							
				else 
				{
					//If restaurant is closed next day then get timing of next to next day
					$dayscurent = date('D', strtotime($date.' +2 day'));	
					$locationid = $_POST['deliveryarea'];
					$isNextDayOff=1;
					
					$nextday   =  getDayfullname($dayscurent);
							
					$startEndTimeNextDay=$product->StartEndTime($locationid,$nextday);

					
					$d_morning_start = date('H:i:s', strtotime($startEndTimeNextDay['d_morning_start']));	
					$d_evening_start = date('H:i:s', strtotime($startEndTimeNextDay['d_evening_start']));	
					
					if(!empty($d_morning_start) && $d_morning_start!='00:00:00')
						$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_morning_start']));							
					elseif(!empty($d_evening_start) && $d_evening_start!='00:00:00')
						$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['d_evening_start']));
				}
					
				$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);							
				$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", strtotime($starttimenextday)));
				
				if($isNextDayOff==0)
					$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
				else 
					$deliverydate = date('m/d/Y', strtotime($date.' +2 day'));
					
				$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
				$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
				$_SESSION['orderDate'] = $deliverydate;
				$_SESSION['orderHour'] = $hour;
				$_SESSION['zip_code'] = $_POST['zip_code'];
				$_SESSION['apt'] = $_POST['apt'];
				$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
				/*$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
				$_SESSION['address_type_business'] = $_POST['address_type_business'];
				$_SESSION['address_type_university'] = $_POST['address_type_university'];
				$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
				$_SESSION['address_type'] = $_POST['address_type'];
				$_SESSION['business_name'] = $_POST['business_name'];
				print "close-". $deliverydate. " " . $hour;
			}				
			else
			{					
				//print_r($_POST);
				//$time = strtotime($_POST['dhour']);
				//$delivaryprepationtime = $menu->getPreprationtime($_POST['deliveryarea']);
				//$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['delivery_time']." minutes", $time));
				$hour = date('g:i A', strtotime($_POST['dhour']));
				$_SESSION['chooseAddress'] = $_POST['deliveryarea'];
				$_SESSION['orderTime'] = $_POST['deliverydate_time']. "&nbsp;" . $hour;
				$_SESSION['orderDate'] = $_POST['deliverydate_time'];
				$_SESSION['orderHour'] = $hour;
				$_SESSION['zip_code'] = $_POST['zip_code'];
				$_SESSION['apt'] = $_POST['apt'];
				/*$_SESSION['delivery_address'] = $_POST['changeaddressdelevery'];
				$_SESSION['address_type_residence'] = $_POST['address_type_residence'];
				$_SESSION['address_type_business'] = $_POST['address_type_business'];
				$_SESSION['address_type_university'] = $_POST['address_type_university'];
				$_SESSION['address_type_military'] = $_POST['address_type_military'];*/
				$_SESSION['address_type'] = $_POST['address_type'];
				$_SESSION['business_name'] = $_POST['business_name'];
			}
		}
  }
  else
  {
  		date_default_timezone_set("UTC");
  		if(!empty($_POST['asap_pickup']) && $_POST['asap_pickup']=='asap_pickup')
		{
			$TimeZone = $product->TimeZone($_POST['changeaddress']);
			$addhour  = 60*60*($TimeZone['hour_diff']);
			$addmin = 60*$TimeZone['minute_diff'];
			$daylight = 60*60*$TimeZone['daylight_saving'];
			$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 

			$curentdate = date('m/d/Y',strtotime($datetime));
			$date =   date('m/d/Y',strtotime($datetime)); 
			$isholiday = $menu->isholiday($_POST['changeaddress'],$curentdate);
			if(!empty($isholiday))
			{							
							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$nextday   =  getDayfullname($dayscurent);
															
							$startEndTimeNextDay = $product->StartEndTime($_POST['changeaddress'],$nextday);
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							print "close-". $deliverydate. " " . $hour;					
			}
			else
			{
					$getday = date('l', strtotime($curentdate));
					$startEndTime = $product->StartEndTime($_POST['changeaddress'],$getday);
					
					$time =   date('H:i:s',strtotime($datetime)); 				
					$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
					$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	
					
					if($startEndTime['is_holidays'])
					{
							
							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$nextday   =  getDayfullname($dayscurent);
															
							$startEndTimeNextDay = $product->StartEndTime($_POST['changeaddress'],$nextday);							
							
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							print "close-". $deliverydate. " " . $hour;
						
					}
					elseif($startEndTime['open_24hours'])
					{
						//$time = round($time / 300) * 300;
						//$curent_time = date("H:i:s",$time);
						$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);
						$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
						
						$_SESSION['chooseAddress'] = $_POST['changeaddress'];
						$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
						$_SESSION['orderDate'] = $date;
						$_SESSION['orderHour'] = $hour;
					}
					else
					{
						if($time<$starttime)
						{			
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);
							$hour = date('h:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttime)));
							
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;

							$_SESSION['orderHour'] = $hour;
						}
						elseif($time>=$starttime && $time <=$lasttime)
						{
							//$timess = round(time() / 300) * 300;
							//$curent_time = date("H:i:s",$timess);
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);
							$hour =  date('h:i A', strtotime("+".$delivaryprepationtime['pickup_time']." minutes", strtotime($time)));
							
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $date. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $date;
							$_SESSION['orderHour'] = $hour;
						}
						elseif($time>=$lasttime)
						{	
							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$nextday   =  getDayfullname($dayscurent);	
															
							$startEndTimeNextDay = $product->StartEndTime($_POST['changeaddress'],$nextday);							
							
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;
							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							print "close-". $deliverydate. " " . $hour;
						}
					}
			}
		}
		else
		{
					$TimeZone = $product->TimeZone($_POST['changeaddress']);
					$addhour  = 60*60*($TimeZone['hour_diff']);
					$addmin = 60*$TimeZone['minute_diff'];
					$daylight = 60*60*$TimeZone['daylight_saving'];
					$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight)));	
					$date =   date('m/d/Y',strtotime($datetime)); 
					
			if($_POST['hour']=='restorant_closed')
			{		
							$dayscurent = date('D', strtotime($date.' +1 day'));	
							$nextday   =  getDayfullname($dayscurent);		// get next day 			
							$startEndTimeNextDay = $product->StartEndTime($_POST['changeaddress'],$nextday);							
							$starttimenextday =  date('h:i:s A', strtotime($startEndTimeNextDay['day_start_time']));							
							$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);							
							$hour = date('g:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes", strtotime($starttimenextday)));
							$deliverydate = date('m/d/Y', strtotime($date.' +1 day'));
							$_SESSION['chooseAddress'] = $_POST['changeaddress'];
							$_SESSION['orderTime'] = $deliverydate. "&nbsp;" . $hour;

							$_SESSION['orderDate'] = $deliverydate;
							$_SESSION['orderHour'] = $hour;
							print "close-". $deliverydate. " " . $hour;
			}
			else
			{
				
				//$time = strtotime($_POST['hour']);
				//$delivaryprepationtime = $menu->getPreprationtime($_POST['changeaddress']);
				//$hour = date('h:i A', strtotime("+ ".$delivaryprepationtime['pickup_time']." minutes",$time));
				$hour = date('h:i A', strtotime($_POST['hour']));
				$_SESSION['chooseAddress'] = $_POST['changeaddress'];
				$_SESSION['orderTime'] = $_POST['pickupdate_time'] . "&nbsp;" . $hour;
				$_SESSION['orderDate'] = $_POST['pickupdate_time'];
				$_SESSION['orderHour'] = $hour;
			}
		}
  }
  
  
  $_SESSION['orderType'] = $_POST['ordertype'];
  $_SESSION['chooseAddress'];  
  
?>