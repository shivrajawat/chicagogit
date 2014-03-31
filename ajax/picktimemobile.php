<?php
  /*
   * 	User
  ***
 *****	Picktine 
*******	 @Kulacart
*/

  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php 
 date_default_timezone_set("UTC");
if($_POST['OrdType']=='delivery')
{
	//When ordertype is delivery
	if($_POST['pickupTime'] && $_POST['Locationid'])
	{	
		
		// $jd=cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));	
		// $currentday = (jddayofweek($jd,1));	
		 
		$TimeZone = $product->TimeZone($_POST['Locationid']);	
		$addhour  = 60*60*($TimeZone['hour_diff']);
		$addmin = 60*$TimeZone['minute_diff'];
		$daylight = 60*60*$TimeZone['daylight_saving'];
		$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight)));
		
	    $m = date('m', strtotime($datetime));
		$d = date('d', strtotime($datetime));
		$y = date('y', strtotime($datetime));$m = date('m', strtotime($datetime));
		$jd=cal_to_jd(CAL_GREGORIAN,date($m),date($d),date($y));	
		$currentday = (jddayofweek($jd,1));	
			
		$getday = date('l', strtotime($_POST['pickupTime']));	
		$startEndTime = $product->StartEndTime($_POST['Locationid'],$getday);	
		
		 $postdate = date('Y-m-d', strtotime($_POST['pickupTime'])); 	
		 $isholiday = $menu->isholiday($_POST['Locationid'],$postdate); 
		 $d_morning_start = date('H:i:s', strtotime($startEndTime['d_morning_start']));	
		 $d_morning_end = date('H:i:s', strtotime($startEndTime['d_morning_end']));	
		 $d_evening_start = date('H:i:s', strtotime($startEndTime['d_evening_start']));	
		 $d_evening_end = date('H:i:s', strtotime($startEndTime['d_evening_end']));	
		 
	 if(!empty($isholiday))
		{
			?>
			<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
		<?php }
		else
		{
			if($currentday==$getday)
			 { 	
						 
						$date =   date('m/d/Y',strtotime($datetime)); 
						$time =   date('H:i:s',strtotime($datetime)); 
						$starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
						$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	
						
						if($startEndTime['is_holidays'])
						{
						 ?>
							<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
						<?php }
						elseif($startEndTime['open_24hours'])
						{ 
							$times = create_time_range($startEndTime['day_start_time'], $startEndTime['last_order_time'], '15 mins'); ?>
							 <?php  foreach ($times as $key => $time) { 
							// $aa =  explode("-",$time); ?>
							 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
								<?php } ?>     
						<?php }
						else
						{
							$morningflag=0;
							$eveningflag=0;
							$iswholeday=0;
							
							if(empty($d_morning_start) || empty($d_morning_end) || $d_morning_start=='00:00:00' || $d_morning_end=='00:00:00')
								$morningflag=1;
								
							if(empty($d_evening_start) || empty($d_evening_end) || $d_evening_start=='00:00:00' || $d_evening_end=='00:00:00')
								$eveningflag=1;
							
							//echo $morningflag;
							//echo $eveningflag;
							if($morningflag==0)
							{
								//If morning time are set
								$starttime = $d_morning_start;
								$lasttime = $d_morning_end;
								
								if($time>=$starttime && $time <=$lasttime)
								{
									$m =  date('i', strtotime($time));
									$h =  date('H', strtotime($time));
									if($m<15)
									{
										 $startTime = date('h:i:s A',strtotime($h.":"."15"));
										 $morntimes = create_time_range($startTime, $startEndTime['d_morning_end'], '15 mins');  	
									}
									elseif($m>=15 && $m<30)
									{	
										 $startTime = date('h:i:s A',strtotime($h.":"."30"));
										 $morntimes = create_time_range($startTime, $startEndTime['d_morning_end'], '15 mins'); 
									}
									elseif($m>=30 && $m<45)
									{	
										$startTime =  $startTime = date('h:i:s A',strtotime($h.":"."45"));
										$morntimes = create_time_range($startTime, $startEndTime['d_morning_end'], '15 mins'); 
									}
									else if($h==23)
									{	
										$startTime = date('h:i:s A',strtotime('12:00 AM'));
										$morntimes = create_time_range($startTime, $startEndTime['d_morning_end'], '15 mins');
									}
									else
									{	
										//$startTime = date('h:00:s', strtotime('+1 hours', strtotime($time)));
										$startTime = date('h:00:s A', strtotime('+1 hours', strtotime($h.":"."00")));
										$morntimes = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins');
									}
								}
								elseif($time>$lasttime)
								{
									if($eveningflag==1)
									{
										$iswholeday=1;
										//Restaurant is closed, is there are blank evening delivery hours											 
								?>                                
											<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
								<?php }
								}
								else
								{
						
									//if($morningflag==0)
									$morntimes = create_time_range($startEndTime['d_morning_start'], $startEndTime['d_morning_end'], '15 mins'); 

								}
							}
							//echo $morntimes;
							if($eveningflag==0)
							{
								//If evening time are set
								$starttime = $d_evening_start;
								$lasttime = $d_evening_end;
								
								if($time>=$starttime && $time <=$lasttime)
								{
									$m =  date('i', strtotime($time));
									$h =  date('H', strtotime($time));
									if($m<15)
									{
										 $startTime = date('h:i:s A',strtotime($h.":"."15"));
										 $eventimes = create_time_range($startTime, $startEndTime['d_evening_end'], '15 mins');  	
									}
									elseif($m>=15 && $m<30)
									{	
										 $startTime = date('h:i:s A',strtotime($h.":"."30"));
										 $eventimes = create_time_range($startTime, $startEndTime['d_evening_end'], '15 mins');  
									}
									elseif($m>=30 && $m<45)
									{	
										$startTime = date('h:i:s A',strtotime($h.":"."45"));
										$eventimes = create_time_range($startTime, $startEndTime['d_evening_end'], '15 mins');  
									}
									else if($h==23)
									{	
										$startTime = date('h:i:s A',strtotime('12:00 AM'));
										$eventimes = create_time_range($startTime, $startEndTime['d_evening_end'], '15 mins');
									}
									else
									{
										//$startTime = date('h:0:s', strtotime('+1 hours', strtotime($h.":"."00")));
										$startTime = date('h:00:s A', strtotime('+1 hours', strtotime($h.":"."00")));
										$eventimes = create_time_range($startTime, $startEndTime['d_evening_end'], '15 mins'); 
									}
								}
								elseif($time>$lasttime)
								{
									$iswholeday=1;
									
								?>
                                <option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
						<?php   }
								else
								{
									
										$eventimes = create_time_range($d_evening_start, $d_evening_end, '15 mins'); 
								}
							}
							//echo $eventimes;
							if($morningflag==1 && $eveningflag==1)
								{
									$iswholeday=1;									
								?>                                
									<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
						<?php   }
							if($iswholeday==0)
							{
							?>
                                
								 <?php  foreach ($morntimes as $mkey => $mtime) { ?>
								<option><?php echo $morntimes[$mkey] = date('h:i A', $mtime);?></option>
									<?php } 
									
									?> 
								<?php  foreach ($eventimes as $ekey => $etime) { ?>
								<option><?php echo $eventimes[$ekey] = date('h:i A', $etime);?></option>
								<?php } ?>     
								<?php
							}
						}
						?>
			 <?php }
			 else
			 {
					$morningflag=0;
					$eveningflag=0;
						
					if(empty($d_morning_start) || empty($d_morning_end) || $d_morning_start=='00:00:00' || $d_morning_end=='00:00:00')
						$morningflag=1;
						
					if(empty($d_evening_start) || empty($d_evening_end) || $d_evening_start=='00:00:00' || $d_evening_end=='00:00:00')
						$eveningflag=1;
		
					if($morningflag==0)
						$morningtimes = create_time_range($startEndTime['d_morning_start'], $startEndTime['d_morning_end'], '15 mins'); 
					   
					if($evenigflag==0)
						$eveningtimes = create_time_range($startEndTime['d_evening_start'], $startEndTime['d_evening_end'], '15 mins'); ?>
						
					 <?php  foreach ($morningtimes as $key => $time) { ?>
					<option><?php echo $morningtimes[$key] = date('h:i A', $time);?></option>
						<?php } ?> 
					<?php  foreach ($eveningtimes as $key => $time) { ?>
					<option><?php echo $eveningtimes[$key] = date('h:i A', $time);?></option>
					<?php } ?>     
	<?php  }
		}
	}
}
else 
{
	//when orderytpe is pickup
	if($_POST['pickupTime'] && $_POST['Locationid'])
	{
	 	$TimeZone = $product->TimeZone($_POST['Locationid']);	
		$addhour  = 60*60*($TimeZone['hour_diff']);
		$addmin = 60*$TimeZone['minute_diff'];
		$daylight = 60*60*$TimeZone['daylight_saving'];
		$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight))); 
		$m = date('m', strtotime($datetime));
		$d = date('d', strtotime($datetime));
		$y = date('y', strtotime($datetime));$m = date('m', strtotime($datetime));
		$jd=cal_to_jd(CAL_GREGORIAN,date($m),date($d),date($y));	
		$currentday = (jddayofweek($jd,1));	
		
	 	$getday = date('l', strtotime($_POST['pickupTime']));	
		$startEndTime = $product->StartEndTime($_POST['Locationid'],$getday);	
		
		$postdate = date('Y-m-d', strtotime($_POST['pickupTime'])); 	
		$isholiday = $menu->isholiday($_POST['Locationid'],$postdate); 
	 
 if(!empty($isholiday))
	{
		?>
		<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
	<?php }
	else
	{
		if($currentday==$getday)
		 {		
					
					$date =   date('m/d/Y',strtotime($datetime)); 
					$time =   date('H:i:s',strtotime($datetime)); 
				    $starttime =  date('H:i:s', strtotime($startEndTime['day_start_time']));
					$lasttime =  date('H:i:s', strtotime($startEndTime['last_order_time']));	

					if($startEndTime['is_holidays'])
					{
						?>
						<option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>
					<?php }
					elseif($startEndTime['open_24hours'])
					{ 
						$times = create_time_range($startEndTime['day_start_time'], $startEndTime['last_order_time'], '15 mins'); ?>
                         <?php  foreach ($times as $key => $time) { 
                        // $aa =  explode("-",$time); ?>
                         <option><?php echo $times[$key] = date('h:i A', $time);?></option>
                            <?php } ?>     
					<?php }
					else
					{
						if($time>=$starttime && $time <=$lasttime)
						{
							
						    $m =  date('i', strtotime($time));
							$h =  date('H', strtotime($time));
							if($m<15)
							{
								 $startTime = date('h:i:s A',strtotime($h.":"."15"));
								 $times = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins');  	
								 ?>	
							 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time);?>
							 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
								<?php } ?>   
							 <?php 
							}
							elseif($m>=15 && $m<30)
							{	
    							 $startTime = date('h:i:s A',strtotime($h.":"."30"));
								 //echo $startEndTime['last_order_time'];
								//echo $startTime;
								 $times = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins');  ?>	
                                 
								 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time);?>
								 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
									<?php } ?>   
								<?php
							}
							elseif($m>=30 && $m<45)
							{	
								 $startTime =  $startTime = date('h:i:s A',strtotime($h.":"."45"));
								$times = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins');  ?>
								 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time);?>
								 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
									<?php } ?> 
								<?php
							}
							else if($h==23)
							{	
								$startTime = date('h:i:s A',strtotime('12:00 AM'));
								$times = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins'); ?>	
							 <?php  foreach ($times as $key => $time) {
							 //$aa =  explode("-",$time);
							  ?>
							 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
								<?php } ?>  
							<?php
							}
							else
							{
								//$startTime = date('h:i:s', strtotime('+1 hours', strtotime($time)));
								$startTime = date('h:00:s A', strtotime('+1 hours', strtotime($h.":"."00")));
								$times = create_time_range($startTime, $startEndTime['last_order_time'], '15 mins'); ?>	
								 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time); ?>
								 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
									<?php } ?>    
								<?php
							}
						}
						elseif($time>$lasttime)
						{
								?>    
                                <option value="restorant_closed">The store is closed for the day. Your order will be placed beginning the next time we are open</option>                            
								
						<?php }
						else
						{
							 $times = create_time_range($startEndTime['day_start_time'], $startEndTime['last_order_time'], '15 mins'); ?>	
							 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time); ?>
							 <option><?php echo $times[$key] = date('h:i A', $time);?></option>
								<?php } ?>   
						 <?php 
						}
					}
					?>
		 <?php }
		 else
		 {
	       $times = create_time_range($startEndTime['day_start_time'], $startEndTime['last_order_time'], '15 mins'); ?>
			 <?php  foreach ($times as $key => $time) { //$aa =  explode("-",$time);?>
			<option><?php echo $times[$key] = date('h:i A', $time);?></option>
				<?php } ?>     
<?php  }
	}
	
  }
}

?>