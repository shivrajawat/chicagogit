<?php include("header.php");?>
	<div data-role="content">        
		<p>
        	<ul data-role="listview" data-inset="true">
            	<?php 
					$webrow = $menu->checkFlow($websitenmae);
					 if($webrow['flow']=='2') {	$quickOrderURL = "href='restaurantmenu.php'";	 }
					 elseif(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){  $quickOrderURL = "href='restaurantmenu.php'";   } 
					 else { $quickOrderURL = "href='chooselocation.php'";	}  
				?>
                <li><a <?php echo $quickOrderURL; ?> data-ajax="false">QUICK ORDER</a></li>
                <?php 										 	
					  if(!$customers->customerlogged_in){
						  
							$repeatOrderURL = SITEURL.'/signin?repeatOrder=1';  								
													
							$var = 'href="'.$repeatOrderURL.'"'; 
						  
					  }	    
					  else {      
					  		if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){ 						  
							 
									 $row2 = $product->checkLocationIdMatchExistance();
																 
									 $order_type = $row2['order_type']; 
									 $location_id = $row2['location_id'];   //location id
									 
									 if($customers->customerFirstOrderExistance()==0) {								 
										 $var = 'href="javascript:customeFirstOrderNotExists();" ';	 
									 }  
								
									 if($location_id != $_SESSION['chooseAddress']) {								 
										 $var = 'href="javascript:locationNotMatch();" ';	 
									 }
									 else {	
											$_SESSION['repeatThanksOrder'] =1;  
											$_SESSION['repeatOrder']; 
										  
											$repeatOrderURL = SITEURL.'/mobile/repeat-lastorder.php';
										  
											$var = "href='".$repeatOrderURL."'";
								  
											if(isset($_SESSION['repeatOrder']) && $_SESSION['repeatOrder']==1 && $customers->customerlogged_in && $product->Totalbasketitem()!=0){
											
												$var = 'href="javascript:RepeatOrderExit();" ';
									 
											}
									 }
						    }
							else {
							        $_SESSION['repeatThanksOrder'] =1; 
									$_SESSION['repeatOrder']; 
							  
									$repeatOrderURL = SITEURL.'/mobile/repeat-lastorder.php';
							  
									$var = "href='".$repeatOrderURL."'";
									
									if($customers->customerFirstOrderExistance()==0) {
																		 
										 $var = 'href="javascript:customeFirstOrderNotExists());" ';	 
									 }  
						  
							    	if(isset($_SESSION['repeatOrder']) && $_SESSION['repeatOrder']==1 && $customers->customerlogged_in && $product->Totalbasketitem()!=0){
									
										$var = 'href="javascript:RepeatOrderExit();" ';
							 
							    	}
						    }
					  }
					  
				
				?>
                <li><a <?php echo $var; ?> data-ajax="false">REPEAT YOUR LAST ORDER</a></li>
                <li><a href="./update-profile.php" data-ajax="false">UPDATE PROFILE</a></li>
                <li><a href="customer-orders.php" data-ajax="false">ORDER HISTORY</a></li>
                <li><a href="./workinghours.php" data-ajax="false">HOURS (OPEN)</a></li>
            </ul>
        </p>
	</div><!-- /content -->
</div>
<?php include("footer.php");?>
<script type="text/javascript">
 function RepeatOrderExit(){	 
	  
		alert("You already added items of last order into cart.");
  }
  function locationNotMatch(){
				
		alert("Last placed order location does not match with the current chosen location.");
  }
  function customeFirstOrderNotExists(){
	  
	    alert("You didn't place any order yet.");
  }
</script> 