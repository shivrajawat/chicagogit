<?php include("header.php"); ?>
<?php
$webrow = $menu->checkFlow($websitenmae);
if($webrow['flow']=='1')
{
	if(isset($_SESSION['chooseAddress']) && !empty($_SESSION['chooseAddress']))
	{
		header("Location:restaurantmenu.php");		
	}
}
 ?>
<?php $ordertype = $menu->OrderType($websitenmae); ?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>

<div data-role="content">
  <h1 class="main-heading">SELECT THE ORDER TYPE & DATE AND TIME </h1>
    
    <!-- /header -->
    <form name="order_form" id="order_form" method="post">
      <div data-role="fieldcontain">
        <label for="select-native-1">Order Type* :</label>
        <?php $ordertype = $menu->OrderTypeMobile();  ?>
        <select name="ordertype" id="ordertype">
            <?php if($ordertype['pick_up']==1){  ?>                  
            <option value="pick_up" selected="selected">Pickup</option>
            <?php } if($ordertype['delivery']==1){ ?>
            <option value="delivery" >Delivery</option>
            <?php } if($ordertype['dineln']==1){  ?>
            <option value="<?php  echo "Dine In"; ?>" ><?php  echo "Dine In";  ?></option>
            <?php  } ?>
        </select>
      </div>
      <div data-role="fieldcontain">
        <label for="select-native-1">Select Location :</label>
         <!--<select name="select-native-1" id="select-native-1" class="changeaddress" >-->
         <select name="changeaddress" id="changeaddress" >
            <?php
			$pickuplocation = $menu->pickUpLocationMobile();
			
			if($pickuplocation){
			
			?>
				<option value="">Select Location</option>
				<?php 
				foreach($pickuplocation as $prow):
				?>
				<option value="<?php echo $prow['id'];?>"><?php echo cleanOut($prow['location_name']);?></option>
				<?php 
				endforeach;
			}
			?>
        </select>
      </div>
      <div data-role="fieldcontain">
        <label for="select-native-1">Order Date* :<br>(e.g.<?php echo date('d/m/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>)</label>
        
        	<select name="order_date" id="select-native-1" class="picktimedate" >
            	 <option value="" selected="selected">Select Order Date</option>
       			 <option value="<?php echo date('d/m/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>" ><?php echo date('d/m/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?></option>   
				 <?php
                    for($i=1; $i<=10; $i++){
                 ?> 
                <option value="<?php echo date('d/m/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?>"><?php echo date('d/m/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?></option>
                 <?php }  ?>             
        </select>
      </div>
      <div data-role="fieldcontain">
        <label for="select-native-1">Order Time* :</label>
        <select name="order_time" id="display_time">        
        </select>
      </div>
      <input type="submit" value="Submit">
    </form>
    <div data-role="content">
      <p>Notes:<br><?php echo ($ordertype['notes']) ? cleanOut($ordertype['notes']) : "";  ?></p>
    </div>
    <!-- /content -->
    <a href="index.php" data-role="button">BACK TO HOME</a>
</div>
<!-- /content -->
</div>
<?php include("footer.php");?>
<script type="text/javascript">

$(document).ready(function() {  
	
 $("#order_form").validate({
	rules: {
		ordertype: {
			required: true			
		},
		changeaddress: {
			required: true
		},	
		order_date: {
			required: true
		},
		order_time: {
			required: true
		}
	},
	messages: {
		ordertype: "Please provide order type",
		changeaddress: "Please provide location",		
		order_date: "Please provide order date",
		order_time: "Please provide order time"		
		
	},submitHandler: function(form) {
		
		 var str = $("#order_form").serialize();		 
		 $.mobile.loading("show");
		 	 
		 $.ajax({
				type: "POST",														
				url: "<?php echo SITEURL;?>/mobile/ajax/locationchoose.php",				
				data: str,
				success: function (msg){											  
					$.mobile.loading("hide");	
					window.location = "<?php echo SITEURL;?>/mobile/restaurantmenu.php";
				}
			});			
		 return false;
		 		
		}	
	});
});

	   //});
    </script>
<!-----------------------on selecting date, show time, starts here--------------------------->
<script type="text/javascript">
$(document).ready(function(){
	
	$(".picktimedate").change(function(){
		
			var date=$(this).val();		
			
			var locationid = $("#changeaddress").val();	
					
			if(locationid =="")
				{
					alert("Please select your Location name");
					$("#changeaddress").removeAttr("selected");
				}
				else
				$.mobile.loading( "show" );
				
				//alert('http://192.168.0.2/kulacart/mobile/chooselocation.php');
				$.ajax({														
					type: "POST",					
					url: "<?php echo SITEURL;?>/ajax/picktimemobile.php",
					//data: dataString,
					data: { pickupTime: date, Locationid:locationid},
					cache: false,
					success: function(html){
						$.mobile.loading( "hide" );					
						$("#display_time").html(html);
					} 
				});
		
		});
});
</script>
<!-----------------------on selecting date, show time, ends here--------------------------->
