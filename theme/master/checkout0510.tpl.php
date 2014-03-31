<?php
  /**
   * Checkout 
   * Kula cart 
   *  
   */  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	 $location = $menu->locationListByMenu($websitenmae);	
?>
<?php 
$lat = '40.65';
$lang = '-73.95';
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
 <?php 
  $inbasket  = $product->MyShoppingBasket();
   foreach ($inbasket as $rows): 
	   $topping  = $product->viewCartTopping($rows['basketID']);
	   foreach ($topping as $trow): 
		 $toppingPrice = $menu->getToppingPrice($trow['option_topping_id']); 
  	     $totalprice += $toppingPrice['price'];
	  endforeach;
  endforeach;  
   $totalprice = $product->totalpriceitem();					 
	$loc = $menu->locationIdByMenu($websitenmae);
	$additional =  $product->additionalAmmount($loc['location']);					 
	$toaltoppingprice = $menu->ToppingTotalPrice();			  
		if($toaltoppingprice):
			foreach($toaltoppingprice as $toppingtotal):
				$totalToppingPrice += $toppingtotal['totalprice'];
			endforeach;
		endif;
			$totalamount =  round_to_2dp($totalprice['totalprice']+$totalToppingPrice);
			$salestex =  $totalamount*$additional['sales_tax']/100;
			$net_amount = $totalamount+$additional['additional_fee']+$salestex; ?>
<div class="row-fluid margin-top">
<div class="span12" id="invoicedetails" style="display:none"></div>
  <div class="span12" id="checkoutprocess">
    <!-----View Cart Details----->
   
    
    <div class="span9 box-shadow fit" >
     <form name="checkoutform" action="" method="post" id="checkoutform">
     <div class="span12 padding-outer-box" id="hidecheck">
     
        <div class="span3">Order Type* :</div>
        <div class="span3">
          <div class="btn btn-success">
            <label class="radio">
            <input type="radio" name="ordertype"  value="pick_up" checked="checked">
            Pick UP</label>
          </div>
        </div>
        <div class="span4">
          <div class="btn btn-success">
            <label class="radio">
            <input type="radio" name="ordertype"  value="delivery">
            Delivery</label>
          </div>
        </div>
        <div class="clr"></div>
        
          <div id="pickup" class="desc">
            <div class="span12 fit ">
              <div class="title-box"> Pick-Up Address </div>
            </div>
            <div class="span12 fit">
              <div class="span2">Locations</div>
              <div class="span9">
                <select class=" span12 input-xlarge" onchange="changelocation(this.value)" name="changeaddress" id="changeaddress">
                  <?php $pickuplocation =  $menu->pickUpLocation($location);?>
                  <option value="">Select Location</option>
                  <?php foreach($pickuplocation as $prow):?>
                  <option value="<?php echo $prow['id'];?>"><?php echo $prow['location_name'];?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="clr"></div>
            </div>
            <br />
            <br />
            <div class="span12 fit">
              <div class="span2">Address</div>
              <div class="span9">
                <input class="span12 input-xlarge" type="text" id="getaddress" name="address">
              </div>
              <div class="span12 fit">
                <div id="googleMap" style="width:100%; height:340px; border: 1px solid #C3C3C3;"></div>
              </div>
              <div class="span12 fit" style="margin-top:5%;">
                <div class="span2"> Pick Up date/time *:</div>
                <div class="span4">
                  <div id="datetimepicker4" class="input-append">
                    <input type="text" id="dp1" class="picktimedate" name="pickupdate_time">
                  </div>
                  <script type="text/javascript">
								 $('#dp1').datepicker({
				  format: 'yyyy-mm-dd'
				});
                    </script>
                  <!--<input class="span12" type="text"  name="pickupdate_time" />-->
                </div>
                <div class="span2" id="timelist"> </div>
                <?php /*?><div class="span2">
              <select class="span12" name="minute">
              <?php			  
				  $num = 0; 
				  while($num < 55){ 
				  $num = $num + 5;
			  ?>
                <option><?php echo $num; ?></option>
               <?php } ?> 
              </select>
            </div><?php */?>
              </div>
              <div class="span12">
                <div class="span3">
                  <div class="btn btn-success"><a href="<?php echo SITEURL; ?>/view-cart">Back</a></div>
                </div>
                <div class="span3">
                  <div>
                    <!--<a href="#" class="next" rel="pickup">NEXT</a>-->
                    <input type="submit" name="submit" value="next"  class="btn"/>
                  </div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="clr"></div>
            </div>
          </div>
          <input type="hidden" name="totalamount" value="<?php echo $totalamount;?>" />
          <input type="hidden" name="additional_fee" value="<?php echo $additional['additional_fee'];?>" />
          <input type="hidden" name="gratuity" value="<?php echo $additional['gratuity'];?>" />
          <input type="hidden" name="sales_tax" value="<?php echo $salestex;?>" />
          <input type="hidden" name="net_ammount" value="<?php echo $net_amount;?>"  />
          <input type="hidden" name="sales_tax_rate" value="<?php echo $additional['sales_tax'];?>"  />
        
        <div id="delivey" style="display:none">
          <div class="span12 fit ">
            <div class="title-box">Delivery Area</div>
          </div>
          <div class="span12 fit">
            <textarea class="span11"></textarea>
          </div>
          <div class="span12 fit">
            <div class="span2"> Pick Up date/time *:</div>
            <div class="span3">
              <input class="span12" type="text" placeholder="">
            </div>
            <div class="span2">
              <select class="span12">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
            <div class="span2">
              <select class="span12">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
              </select>
            </div>
          </div>
          <div class="span12">
            <div class="span3">
              <div class="btn btn-success"><a href="javascript:history.go(-1)">Back</a></div>
            </div>
            <div class="span3">
              <div class="btn btn-success"><a href="#" class="next" rel="delivery">NEXT</a></div>
            </div>
            <div class="clr"></div>
          </div>
          <div class="clr"></div>
        </div>
      </div>
      </form>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <div class="span3">
      <div class="span12 box-shadow padding-outer-box ">
        <h3 class="h4">ORDER SUMMARY</h3>
       <?php $location =  $product->LocationDetailsByDefoult($location['location']); ?>
        <span class="add-details">Location:</span>
        <div class="clr"></div>
        <p class="location"><?php  echo $location['location_name'];?></p>
        <span class="add-details">Address :</span>
        <p class="location"><?php  echo $location['address1'];?>,</p>
        <span class="add-details">Expected Order Time :</span>
        <div class="clr"></div>
        <p>Not Applicable </p>
        <div class="clr"></div>
      </div>    
      </div>
    <!-----RIGHT END----->
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>

<!-- loder div -->
<div style="display: none;" id="smallLoader">
  <div>
    <div> </div>
  </div>
</div>
<script language="JavaScript">
$(document).ready(function() {
    $("input[name$='ordertype']").click(function() {
        var id = $(this).val();
		if(id=='pick_up')
		{
			 $("#pickup").show();
			 $("#delivey").hide();
		}
		else
		{
			$("#delivey").show();
			$("#pickup").hide();
		}
      
    });
});
</script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script>
<script type="text/javascript">
loadMap();
function loadMap()
{
	var myCenter=new google.maps.LatLng('40.65','-73.95');
	var mapProp = {
		  center:myCenter,
		  zoom:8,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
  	};



	var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	google.maps.event.addDomListener(window, 'load', initialize);
}
function loadMapOnSelect(latitude,longitude,address)
{
	var myCenter=new google.maps.LatLng(latitude,longitude);
	var Selectname = document.getElementById('changeaddress');
	
		var mapProp = {
			  center:myCenter,
			  zoom:8,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
  		};

	var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

	 var marker=new google.maps.Marker({
 	 position:myCenter,
  	});

	marker.setMap(map);

	var infowindow = new google.maps.InfoWindow({
  	content:address
  	});

	infowindow.open(map,marker);
	google.maps.event.addDomListener(Selectname, 'change', initialize);
}

var   latitude;
var   longitude;

function changelocation(val)
{
	var locationid = val;	 
	var name = document.getElementById('changeaddress');
	$("#smallLoader").css({display: "block"});
  $.getJSON("<?php echo SITEURL;?>/ajax/getaddress.php?location_id=" +locationid,
        function(data)
		{
		 $("#smallLoader").css({display: "none"});
          $.each(data, function(i,item){
            if (item.field == "address1") {
              $("#getaddress").val(item.value);
			  address = item.value;
            } 
			else if (item.field == "latitude") {
               latitude = item.value;			 
            }
			else if (item.field == "longitude") {
              longitude = item.value;
			  			 
            }
			
          });
			loadMapOnSelect(latitude,longitude,address);
        });
		
}
</script>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {  
  $("#checkoutform").validate({
	rules: {
		changeaddress: {
			required: true
		},
		pickupdate_time: {
					required: true
				},
		hour: {
					required: true
				},
		minute: {
					required: true
				},
	},
	messages: {
		changeaddress: {
			required: "Please provide your location"
		},
		pickupdate_time: "Please provide pickup date and time",
		hour: "Please select hour",
		minute: "Please select minute"
		
	},
	submitHandler: function(form) {
				$("#smallLoader").css({display: "block"});
				var str = $("#checkoutform").serialize();
				  $.ajax({
					  type: "POST",
					  url: "ajax/ordertype.php",
					  data: "processPickUp=1&"+str,
					  success: function (msg){						  
						   $("#smallLoader").css({display: "none"});
						   $('#checkoutprocess').html(msg);
						   $('#hidecheck').hide();
					   }
				});
			  return false;
			}
	
});


});
// ]]>
</script>
<script type="text/javascript">
$(document).ready(function()
	{
	
	$(".picktimedate").change(function()
		{
			var date=$(this).val();
			var locationid = $("#changeaddress").val();				
			if(locationid=="")
				{
					alert("Please select your Location name");
					$("#changeaddress").removeAttr("selected");
				}
				else
			//var dataString = 'pickupTime='+ date,'location='+ date;
			$.ajax
			({
				type: "POST",
				url: "<?php echo SITEURL;?>/ajax/picktime.php",
				//data: dataString,
				data: { pickupTime: date, Locationid:locationid},
				cache: false,
				success: function(html)
				{
					$("#timelist").html(html);
				} 
			});
		
		});
});
</script>
