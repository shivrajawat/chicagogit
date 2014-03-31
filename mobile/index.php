<?php include("header.php");?>
<?php 

		$webrow = $menu->checkFlow($websitenmae);
		
	    if ($core->offline == 1 && $user->is_Admin()){	require_once(THEMEDIR . "/index.php");	}
		elseif ($core->offline == 1) {	require_once(SITEURL . "/maintenance.php");   }
		elseif($webrow['flow']=='2') {	header("Location:restaurantmenu.php");	 }
		else {	require_once("index.php");	}

?>
    <?php  if(!$customers->customerlogged_in){ ?>     
	<div data-role="content">
		<p>
        	<ul data-role="listview" data-inset="true">
            	 <?php
				 	if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress'])){
						$quickOrderURL = 'restaurantmenu.php';							
														
						$href1 = 'href="'.$quickOrderURL.'"'; 
					}
					else {
						$quickOrderURL = 'chooselocation.php';							
													
						$href1 = 'href="'.$quickOrderURL.'"';
						
					}
				?>
                <li><a <?php echo $href1; ?> data-ajax="false">QUICK ORDER</a></li>
                <?php
					$repeatOrderURL = SITEURL.'/mobile/login.php?repeatOrder=1';	
					$var = 'href="'.$repeatOrderURL.'"'; 				  
				?>
                <li><a <?php echo $var; ?> data-ajax="false">REPEAT YOUR LAST ORDER</a></li>
                <li><a href="./register.php" data-ajax="false">NEW CUSTOMER</a></li>
                <li><a href="login.php" data-ajax="false">RETURNING CUSTOMER</a></li>
                <li><a href="./workinghours.php" data-ajax="false">HOURS (OPEN)</a></li>
            </ul>
        </p>
	</div><!-- /content -->
    <?php 
	      } 	
		  else{ 
		 		redirect_to("account.php"); 
		  }
	?>
    </div>
<?php include("footer.php");?>