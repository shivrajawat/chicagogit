<?php
/**
* Checkout 
* Kula cart 
*  
*/  
if (!defined("_VALID_PHP"))
	die('Direct access to this location is not allowed.');	

	$location = $menu->locationListByMenu($websitenmae);
	$lat = "'".$location['latitude']."'";	
	$lang = "'".$location['longitude']."'";	
	$zoomlevel = ($location['zoom_level']) ? $location['zoom_level']  : 3;
	$webrow = $menu->checkFlow($websitenmae);
	if($webrow['flow']=='2'):
		 if (!$customers->customerlogged_in)
      redirect_to("signin");
	endif;
?>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.validate.min.js"></script>
<div class="row-fluid top_links_strip">
  <div class="span12">
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Pick the Date & Time </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
  <div class="row-fluid margin-top">
    <div class="span12" id="invoicedetails" style="display:none"></div>
    <div class="span12 fit" id="checkoutprocess">
      <!-----View Cart Details----->
      <div id="content-right-bg" class="span12 padding-top-10 padding-bottom-10 relative">
        <div class="span9 fit">
          <form name="checkoutform" action="<?php echo SITEURL;?>/" method="post" id="checkoutform">
            <div class="span12 fit">
              <div class="row-fluid">
                <div class="span12 top_heading_strip"> Select the Order Type </div>
              </div>
              <div class="span12"> <br />
                <div class="span4 fit">
                  <div class="order-type-gree span12"> <span class="bold">Order Type: </span>
                    <?php $ordertype = $menu->OrderType($websitenmae);
						if($ordertype['pick_up'])
						{?>
                    <label class="radio pickup-bt">
                      <input type="radio" name="ordertype" id="pick"  value="pick_up" checked="checked">
                      Pick Up </label>
                    <?php }
						if($ordertype['delivery'])
						{?>
                    <label class="radio pickup-bt">
                      <input type="radio" name="ordertype" id="deliver"  value="delivery">
                      Delivery </label>
                    <?php }
						if($ordertype['dineln'])
						{?>
                    <label class="radio pickup-bt">
                      <input type="radio" name="ordertype" id="extra"  value="dineln">
                      Dineln </label>
                    <?php }	?>
                    <div> 
                      <!--------------------------------------Will be shown in delivery case, starts here-------------------------------------------------->
                      <?php

				  	 $crow = $customers->getAddressType();?>
                      <div class="span12" id="showDeliveryDetails" style="display:none">
                        <div class=""><span class="bold">Delivery Address:</span></div>
                        <div id="datetimepicker4" class="input-append">
                          <input id="searchTextField" type="text" class="deliveryarloc" name="changeaddressdelevery">
                          <input id="deliveryarea_location_id" type="hidden" class="deliveryarlocsel" name="deliveryarea" value="">
                        </div>
                        <?php if($content->show_deliveryaddress):?>
                        <div class="row-fluid">
                          <div class="span12 bold">Apt/Suite/Floor</div>
                          <div class="span12 fit">
                            <input type="text" name="apt" class="deliveryarloc" />
                          </div>
                        </div>
                        <div class="row-fluid">
                          <div class="span12 bold">Zip Code:</div>
                          <div class="span12 fit">
                            <input type="text" name="zip_code" class="deliveryarloc" />
                          </div>
                        </div>
                        <div class=""><span class="bold">Address:</span></div>
                        <div class="row-fluid">
                          <div class="span12">
                            <div class="span6">
                              <label for="addresstype_residence">
                              <input type="radio" name="address_type" id="addresstype_residence"class="addr_hide" value="addr_residence" <?php if(isset($crow['addr_residence']) && $crow['addr_residence'] == 1){ echo 'checked="checked"'; } ?> /> Residence</label>
                              </div>
                            <div class="span6">
                            	<label for="addresstype_bussiness">
                              	<input type="radio" name="address_type" class="addr_hide" value="addr_business" id="addresstype_bussiness" <?php if(isset($crow['addr_business']) && $crow['addr_business'] == 1){ echo 'checked="checked"'; } ?> /> Business</label></div>
                          </div>
                        </div>
                        <div class="row-fluid">
                          <div class="span12">
                            <div class="span6">
                              <label for="addresstype_university">
                              <input type="radio" name="address_type" id="addresstype_university" class="addr_hide" value="addr_university" <?php if(isset($crow['addr_university']) && $crow['addr_university'] == 1){ echo 'checked="checked"'; } ?>   /> University </label></div>
                            <div class="span6">
                            	<label for="addresstype_military">
                              		<input type="radio" name="address_type" id="addresstype_military" class="addr_hide" value="addr_military" <?php if(isset($crow['addr_military']) && $crow['addr_military'] == 1){ echo 'checked="checked"'; } ?> />Military
                                </label>
                           </div>
                          </div>
                        </div>
                        <div class="row-fluid" id="business_name" <?php if(isset($crow['addr_business']) && $crow['addr_business'] == 1){ echo $style = 'style="display:block"'; } else { echo $style = 'style="display:none"'; } ?> >
                          <div class="span12">Business name</div>
                          <div class="span12">
                            <input type="text" name="business_name"   value="<?php echo ($crow['business_name']) ? ucwords($crow['business_name']) : "";  ?>" />
                          </div>
                        </div>
                        <?php endif;?>
                      </div>
                      
                      <!--------------------------------------Will be shown in delivery case, ends here--------------------------------------------------> 
                      
                    </div>
                    <div id="pickuptime">
                      <div class=""><span class="bold">Pickup Location:</span></div>
                      <select class=" span12 input-large" onchange="changelocation(this.value)" name="changeaddress" id="changeaddress">
                        <?php
						$pickuplocation = $menu->pickUpLocation($location);	
						if($pickuplocation){ 
						
							$countlocation = count($pickuplocation);
							if($countlocation == 1)
							{
								$sel = 'selected="selected"';
							}else
							{
								$sel = "";
							}						
						?>
                        <option value="">Select Location</option>
                        <?php foreach($pickuplocation as $prow): ?>
                        <option value="<?php echo $prow['id'];?>" <?php echo $sel;?>><?php echo $prow['location_name'];?></option>
                        <?php 
                              endforeach;
                           }
                           ?>
                      </select>
                      <div class="" style="text-align:center"> <span id="getaddress"></span> </div>
                      <div class="span12 asapborder fit">
                        <div class=""><span class="bold">When do you want this?</span></div>
                        <div>
                          <label class="radio pickup-bt">
                            <input type="radio" id="asap_pickup" value="asap_pickup" name="asap_pickup" />
                            ASAP</label>
                        </div>
                        
                        <div>
                          <label class="radio pickup-bt">
                          <input type="radio" id="ftime" value="ftime" name="asap_pickup" />
                          Future Time</label>
                          </div>
                        <div id="asapPickupdatatime"  style="display:none">
                          <div class=""><span class="bold">Pickup Date:</span></div>
                          <div id="datetimepicker4" class="input-append">
                            <input type="text" id="dp1" class="picktimedate" name="pickupdate_time" placeholder="Click here to select" style="width:90%;">
                          </div>
                          <script type="text/javascript">
								$('#dp1').datepicker({
				  				format: 'mm/dd/yyyy',
								startDate: new Date(),
								endDate:'+15d',
								autoclose: true
								});
                	 	</script>
                          <div class="span10" id="timelist"></div>
                        </div>
                      </div>
                    </div>
                    
                    <div id="deliverytime" style="display:none; margin-top:10px !important;" class="span12 asapborder fit">
                      <div class=""><span class="bold">When do you want this?</span></div>
                      <div>
                        <label class="radio pickup-bt">
                          <input type="radio" id="asap_delivery" value="asap_delivery" name="asap_delivery"/>
                          ASAP</label>
                      </div>
                      <div>
                          <label class="radio pickup-bt">
                          <input type="radio" id="dftime" value="dftime" name="asap_delivery" />
                          Future Time</label>
                      </div>
                      <div id="asapDatatime" style="display:none">
                        <div class="bold"> Delivery Date: </div>
                        <div id="datetimepicker4" class="input-append">
                          <input type="text" id="dp2" class="deliverytimedate deliveryarloc" name="deliverydate_time" style="width:90%;">
                        </div>
                        <script type="text/javascript">

                                $('#dp2').datepicker({
									format: 'mm/dd/yyyy',
									startDate: new Date(),
									endDate:'+15d',
									autoclose: true
                                });
                            </script>
                        <div class="span10" id="deliverytimelist"></div>
                      </div>
                    </div>
                    <div class="span12" style="margin-top:15px;">
                      <input type="submit" class="btn-2-2" name="sunmit" value="SUBMIT"/>
                    </div>
                  </div>
                </div>
                <div class="span8 fit">
                  <div class="span12" id="hidecheck">                     
                    <!-------------------Start Delivery Area -------------------------------------------------------------------------->                    
                    <div id="pickup" class="desc">
                      <div class="span12 fit">
                        <div class="span12 fit">
                          <div id="googleMap" style="width:100%; height:340px;" class="location-map"></div>
                          <div id="googleMapNew" style="display:none"></div>
                        </div>
                      </div>
                      <?php if(isset($ordertype['notes']) && !empty($ordertype['notes'])){  ?>
                      <div class="span11 locationnote" style="margin-top:1%;"> <?php echo "Notes:".cleanout($ordertype['notes']);  ?>
                        <div class="span2" id="timelist"> </div>
                      </div>
                      <?php } ?>
                      <div class="clr"></div>
                    </div>
                    <div id="delivey" style="display:none">
                      <input type="hidden" name="selected_delivery_address" id="selected_delivery_address" value=""/>
                      <div class="span12 fit">
                        <div class="map_holder">
                          <div id="map_canvas" style="height:300px; width:95%" class="location-map"></div>
                        </div>
                      </div>
                      <?php if(isset($ordertype['notes']) && !empty($ordertype['notes'])){  ?>
                      <div class="span11" style="margin-top:1%;"> <?php echo "Notes:".cleanout($ordertype['notes']);?>
                        <div class="span2" id="timelist"></div>
                      </div>
                      <?php } ?>
                    </div>
                    <div class="clr"></div>
                  </div>
                </div>
                <?php
                $coords = $menu->getcoordDeliverymaps($menu->DeliveryLocation($location));
                foreach ($coords as $c){					
					if(isset($c['color_code']) && !empty($c['color_code'])){
						$color_code = $c['color_code'];
					}else{ $color_code = "#008000"; }
                ?>
                <input type="hidden" name="data[<?php echo $c['type']; ?>][<?php echo $c['id']; ?>]" value="<?php echo $c['data']; ?>" class="coords" data-type="<?php echo $c['type']; ?>" data-id="<?php echo $c['id']; ?>" data-title="<?php echo $c['location_name'];?>" data-color="<?php echo $color_code; ?>" />
                <?php } ?>
                <input type="hidden" name="lat" id="lat" value="<?php echo $row['latitude']; ?>" />
                <input type="hidden" name="lng" id="lng" value="<?php echo $row['longitude'];?>" />
                <div class="clr"></div>
              </div>
              <div class="span12 fit btn_back_next">
                <div class="span10"></div>
                <div class="span2">
                  <div> <a href="javascript:goback()" class="btn-2-2" title="Back">BACK </a> </div>
                </div>
                <div class="clr"></div>
              </div>
            </div>
          </form>
        </div>        
        <!--------------------------------------------------Right side panel, starts here------------------------------------------------------------>        
        <div class="span3 margin-left-16">
          <div class="span12 box2 fit" id="basketItemsWrap">
            <div class="row-fluid myorder_headings"><span>MY ORDERS</span> </div>
            <div id="meniheading"></div>
            <div id="hidemeniheading">
              <?php if($product->getBasket()){ ?>
              <div class="row-fluid"> <span class="item-name span7 fit">
                <h5>Item Name</h5>
                </span> <span class="qty span2 fit">
                <h5>Qty</h5>
                </span> <span class="Delete span3 fit">
                <h5> Delete</h5>
                </span>
                <div class="clr"></div>
              </div>
              <?php } ?>
            </div>
            <div class="row-fluid">
              <div class="item-delete-a">
                <div id="basketItemsWrap" class="basketItemsWrap">
                  <ul>
                    <li></li>
                    <?php echo  $product->getBasket(); ?>
                  </ul>
                </div>
                <div class="clr"></div>
              </div>
            </div>
            <div class="row-fluid">
              <div class="span11" style="margin:0 auto; float:none">
                <div id="totalprice"></div>
                <div id="hideprice">
                  <?php $style='style="display:none"'; 
				  if(!$product->getBasket()){ $style='style="display:none"'; ?>
                  <div class="total-order-amount span12 acenter"> Awaiting your delicious selections.<br />
                    <a href="javascript:void(0);" class="CancelOrder">Cancel order & Start Over</a> </div>
                  <?php
				  	 } 
					 else { 
					 		$style='style="display:block"'; 
				  ?>
                  <div class="total-order-amount span12 acenter bold">
                    <?php $baskettotal = $product->getToalpriceinBasket(); ?>
                    <?php echo 'Total Order Amount:  $'.round_to_2dp($product->getToalpriceinBasket()).'';?> </div>
                  <div class="clr"></div>
                  <div class="span12 acenter fit">
                    <div class="span12 fit">
                      <div class="span12" align="center"> <a href="<?php echo SITEURL;?>/view-cart" class="deleteitem btn-2-2" style="float:left; margin:10px 0 2px 42px;">VIEWCART</a><br />
                        <?php 
						if(isset($_SESSION['chooseAddress'])){	
						?>
                        <a href="<?php echo SITEURL;?>/checkout" class="btn-2-2" style="float:left; margin:10px 0 10px 42px">CHECKOUT</a>
                        <?php
							}
						else {	
						?>
                        <a href="<?php echo SITEURL;?>/getorder-type" class="btn-2-2" style="float:left; margin:10px 0 10px 42px" >CHECKOUT</a>
                        <?php 	} ?>
                        <div class="clr"></div>
                      </div>
                      <a href="javascript:void(0);" class="CancelOrder">Cancel order & Start Over</a></div>
                  </div>
                  <?php }?>
                </div>
              </div>
            </div>
          </div>
          <div class="span12 joinclub_img margin-top-30"> <a href="<?php echo SITEURL;?>/register"> <img src="<?php echo THEMEURL;?>/images/join_eclub.png" alt="" /> </a> </div>
           <?php if(isset($webrow['hours_notes']) && !empty($webrow['hours_notes'])){ ?>
          <div class="span12 box2 hours_home margin-top-30">
            <div class="row-fluid myorder_headings"><span>STORE HOURS</span></div>
            <div class="row-fluid">
              <div class="span12">
                <?php 
					echo cleanOut($webrow['hours_notes']);
					/*$hours = $product->landingpageHours($webrow['flow']);
					 if($hours):
							echo cleanOut($hours['restorant_time']);
					endif;*/
				?>
              </div>
            </div>
          </div>
          <?php } ?>
          <div class="span12 hours_home margin-top-50 box2">
            <div class="row-fluid myorder_headings"><span>CARDS ACCEPTED</span> </div>
            <div class="row-fluid">
              <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/visa.png"></a></div>
              <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/am-card.png"></a></div>
              <div class="span4 fit tacenter"><a href="#"><img alt="" src="<?php echo THEMEURL;?>/images/mastercard.png"></a></div>
            </div>
          </div>
        </div>
        
        <!--------------------------------------------------Right side panel, ends here---------------------------------------------------------------> 
        
        <!--************************Delivery Area  END*****************************-->
        
        <div class="clr"></div>
        <div id="content-top-shadow"></div>
        <div id="content-bottom-shadow"></div>
        <div id="content-widget-light"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<input type="hidden" name="hdLatitude" id="hdLatitude" />
<input type="hidden" name="hdLongitude" id="hdLongitude" />
<?php 
		$ByDefoultlatlong  = $menu->getLatituteLongitute($location);
		$str = "";
		$i = 1;	
		foreach($ByDefoultlatlong as $latlong)
		{	
			$str .='{ "DisplayText": "'.$latlong['address1'].'", "ADDRESS": "'.$latlong['address1'].'", "LatitudeLongitude": "'.$latlong['latitude'].','.$latlong['longitude'].'", "MarkerId": "Customer" }';

			if($i < count($ByDefoultlatlong))
				$str .= ",";
		   $i++;
		}
?>
<?php 

$allcord = $menu->AllCoordinates($location);
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false"></script> 
<script type="text/javascript">
    // Google Map By Ordder type Pickup 
    $(document).ready(function() {
           loadMap();
    });

        var map;
        var geocoder;
        var marker;
        var people = new Array();
        var latlng;
        var infowindow;	
		
		function loadMap()
		{
			var myCenter=new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lang;?>);
			var mapProp = {
				  center:myCenter,
				  zoom:<?php echo $zoomlevel;?>,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
            map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
            // Get data from database. It should be like below format or you can alter it.
            var data = '[<?php echo $str;?>]';
            people = JSON.parse(data); 
            for (var i = 0; i < people.length; i++) {
                setMarker(people[i]);
            }
        }

		 function setMarker(people) {

					geocoder = new google.maps.Geocoder();
					infowindow = new google.maps.InfoWindow();

					if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
						geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
								marker = new google.maps.Marker({
									position: latlng,
									map: map,
									draggable: false,
									html: people["DisplayText"],
									icon: "images/marker/" + people["MarkerId"] + ".png"
								});

								//marker.setPosition(latlng);
								//map.setCenter(latlng);
								google.maps.event.addListener(marker, 'click', function(event) {
									infowindow.setContent(this.html);
									infowindow.setPosition(event.latLng);
									infowindow.open(map, this);
								});
							}
							else {
								alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
							}
						});
					}
					else {
						var latlngStr = people["LatitudeLongitude"].split(",");
						var lat = parseFloat(latlngStr[0]);
						var lng = parseFloat(latlngStr[1]);
						latlng = new google.maps.LatLng(lat, lng);
						marker = new google.maps.Marker({
							position: latlng,
							map: map,
							draggable: false,               // cant drag it
							html: people["DisplayText"]     // Content display on marker click
							//icon: "images/marker.png"     // Give ur own image
						});

						//marker.setPosition(latlng);
						//map.setCenter(latlng);
						google.maps.event.addListener(marker, 'click', function(event) {
							infowindow.setContent(this.html);
							infowindow.setPosition(event.latLng);
							infowindow.open(map, this);
						});
					}
		  }
		// change location by map show in pickup 
		function loadMapOnSelect(latitude,longitude,address,zoom_level)
		{
			var myCenter=new google.maps.LatLng(latitude,longitude);
			var Selectname = document.getElementById('changeaddress');
			if(zoom_level =="" || zoom_level ==0 ){
				var zoom_level2 = 8;
		    } else {
				var zoom_level2 = zoom_level;
			}	
			var mapProp = {
				  center:myCenter,
				  zoom:Number(zoom_level2),
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
			//google.maps.event.addDomListener(Selectname, 'change', initialize);

		}
		var   latitude;
		var   longitude;
		var locationid = $('#changeaddress :selected').val();
		if(!isNaN(locationid) && locationid!='')
		{
			changelocation(locationid)
		}
		// address become by change location pickup
		function changelocation(val)
		{
			var locationid = val;	
			var name = document.getElementById('changeaddress');
		  $("#smallLoader").css({display: "block"});
		  $.getJSON("<?php echo SITEURL;?>/ajax/getaddress.php?location_id=" +locationid,
				function(data)
				{
					if(data)
					{

						 $("#smallLoader").css({display: "none"});	
						 if(typeof data.address1 != 'undefined')
						 {
							$("#getaddress").html(data.address1); 
						 }
						 if(typeof data.phone_number != 'undefined')
						 {
							 if(parseInt(data.phone_number))
							 {
								$("#getaddress").append('<br />Tel.: ' + data.phone_number);
							 }
						 }
						 if(typeof data.opentime != 'undefined')
						 {
							 if(data.opentime)
							 {	
								$("#getaddress").append('<br /><div style="text-align:left !important;">' + data.opentime+'</div>'); 
							 }
						 }
						 if((typeof data.latitude != 'undefined') && (typeof data.longitude != 'undefined'))
						 {
							loadMapOnSelect(data.latitude,data.longitude,data.address1,data.zoom_level);
						 }
					}
					else
					{
						$("#getaddress").html('');
						loadMap();
					}
				});
		}
</script> 
<!-------- ---------------Validation of Delivery area form Fields---------------------------> 

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() { 
	$("#checkoutform").validate({
		rules: {
			changeaddress: {
				required: true
			},
			changeaddressdelevery: {
				required: true
			},
			pickupdate_time: {
				required: true
			},
			deliverydate_time: {
				required: true
			},
			hour: {
				required: true
			},
			minute: {
				required: true
			},
			zip_code: {
				required: true,
				number: true,
				minlength: 5,
				maxlength: 5
			}
		},

		messages: {
			changeaddress: "Please select your pickup location",
			changeaddressdelevery: "We are sorry by the address you entered is outside our delivery zone.",
			pickupdate_time: "Please select the order date and time",
			deliverydate_time: "Please select the order date and time",
			hour: "Please select hour",
			minute: "Please select minute",			
			zip_code: {
				required: "Please provide your zip code",
				number: "Zip code must be numeric",
				minlength: "Zip code must be of 5 digits",
				maxlength: "Zip code must be of 5 digits"
			}	
		},
		
		submitHandler: function(form) {
			var sdd = $("#deliveryarea_location_id").val();
			var sdd1 = $("#changeaddress").val();
			var typecheck = $("input[name='ordertype']:checked").val();
			if(typecheck == 'delivery')
			{
				if(!sdd)
				{
					alert("We are sorry by the address you entered is outside our delivery zone.");
					return false;
				}
			}
			$("#smallLoader").css({display: "block"});
			var str = $("#checkoutform").serialize();
			$.ajax({
				type: "POST",
				url: "ajax/locationchoose.php",
				data: str,
				success: function (msg){	

					$("#smallLoader").css({display: "none"});
					var msg_1=msg.split('-');
					//alert(msg);
					if(msg)
					{
						if(msg_1[0]=='close')
						{
							var timedate = msg_1[1];	
							alert("We are sorry but we are closed for the day.\nYour order will be placed for the next time \nwe are open which will be "+timedate);
							<?php if($webrow['flow']=='1'):?>
							window.location = "<?php echo SITEURL;?>/?location";
							<?php else: ?>
							window.location = "<?php echo SITEURL;?>/checkout.php";
							<?php endif;?>
						}
					}
					else
					{
						<?php if($webrow['flow']=='1'):?>
							window.location = "<?php echo SITEURL;?>/?location";
							<?php else: ?>
							window.location = "<?php echo SITEURL;?>/checkout.php";
							<?php endif;?>
					}
				}
			});
			return false;
		}	
	});
});



// ]]>



</script> 

<!-------- ---------------ON Select date Display time range  Order type Pick UP---------------------------> 

<script type="text/javascript">
$(document).ready(function(){
	
	$(".picktimedate").change(function(){
			var date=$(this).val();
			var locationid = $("#changeaddress").val();	
			if(locationid ==""){
					alert("Please select your Location name");
					$("#changeaddress").removeAttr("selected");
				}
				else
			$.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/ajax/picktime.php",
				//data: dataString,
				data: { pickupTime: date, Locationid:locationid, OrdType:'pickup'},
				cache: false,
				success: function(html)
				{
					$("#timelist").html("<div><span class='bold'>Pickup Time</span></div>"+html);
				}
			});

		});
});

</script> 

<!-------- ---------------ON Select date Display time range  Order type Delivery Area---------------------------> 

<script type="text/javascript">



$(document).ready(function()
	{
	$(".deliverytimedate").change(function()
		{
			var date=$(this).val();
			var locationid = $("#deliveryarea_location_id").val();
			if(locationid=="")
			{
				alert("We are sorry by the address you entered is outside our delivery zone.");

				$("#dp2").val('');
			}
			else
			{



				//var dataString = 'pickupTime='+ date,'location='+ date;



				$.ajax



				({



					type: "POST",



					url: "<?php echo SITEURL;?>/ajax/picktime.php",



					//data: dataString,



					data: { pickupTime: date, Locationid:locationid, OrdType:'delivery'},



					cache: false,



					success: function(html)



					{



						$("#deliverytimelist").html("<div><span class='bold'>Delivery Time</span></div>"+html);



					} 



				});



			}



		



		});



});



</script> 

<!-------- ---------------Google map for delivery area ---------------------------> 

<script>
$(function () {
		var $frmUpdateLocation = $('#checkoutform'),
			$tabs = $("#tabs"),
			$dialogDelete = $("#dialogDelete"),
			dialog = ($.fn.dialog !== undefined),
			$content = $("#content"),
			overlays = [],
			selectedShape = null;
		var myGoogleMaps = null,
			myGoogleMapsMarker = null;
		function GoogleMaps() {
			this.map = null;
			this.drawingManager = null;
			this.init();
		}
		GoogleMaps.prototype = {
			init: function () {
				var self = this;
				self.map = new google.maps.Map(document.getElementById("map_canvas"), {
					zoom: 8,
					center: new google.maps.LatLng(40.65, -73.95),
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				return self;
			},
			addMarker: function (position) {
				if (myGoogleMapsMarker != null) {
					myGoogleMapsMarker.setMap(null);
				}
				myGoogleMapsMarker = new google.maps.Marker({
					map: this.map,
					position: position,
					icon: "images/marker.png"
				});
				this.map.setCenter(position);
				$("#lat").val(position.lat());
				$("#lng").val(position.lng());
				return this;
			},
			draw: function () {
				var $el,
					self = this,
					tmp = {cnt: 0, type: ""},
					mapBounds = new google.maps.LatLngBounds();
				$(".coords").each(function (i, el) 
				{
					$el = $(el);
					tmp.cnt += 1;						
					switch ($el.data("type")) {						
						case 'circle':
							var str = $el.val().replace(/\(|\)|\s+/g, ""),
								arr = str.split("|"),								
								center = new google.maps.LatLng(arr[0].split(",")[0], arr[0].split(",")[1]);								
							    var circle = new google.maps.Circle({
								strokeColor: '#008000',
								strokeOpacity: 1,
								strokeWeight: 1,
								fillColor: $el.data("color"),
								fillOpacity: 0.5,
								center: center,		
					            radius: parseFloat(arr[1]),
					            editable: false,
					            center_changed: function ($_el) {
					            	return function () {
					            		self.update.call(self, this, $_el, 'circle');
					            	};
					            }($el),
					            radius_changed: function ($_el) {
					            	return function () {
					            		self.update.call(self, this, $_el, 'circle');
					            	};
					            }($el)

							});
							circle.myObj = {
								"id": $el.data("id"),
								"title": $el.data("title")
							};
							circle.setMap(self.map);
							mapBounds.extend(center);
							<?php /*?>google.maps.event.addListener(circle, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;
								$("#selectedArea").html(this.myObj.title);
								$("#selected_delivery_address").val(this.myObj.id);
							});<?php */?>
							
							overlays.push(circle);
							tmp.type = "circle";
							break;
						case 'polygon':
							var path,
								str = $el.val().replace(/\(|\s+/g, ""),
								arr = str.split("),"),
								paths = [];
							arr[arr.length-1] = arr[arr.length-1].replace(")", "");
							for (var i = 0, len = arr.length; i < len; i++) {
								path = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
								paths.push(path);
								mapBounds.extend(path);
							}
							var polygon = new google.maps.Polygon({
								paths: paths,
								strokeColor: '#008000',
								strokeOpacity: 1,
								strokeWeight: 1,
								fillColor: $el.data("color"),
								fillOpacity: 0.5,
					            editable: false
						    });
							polygon.myObj = {
								"id": $el.data("id"),
								"title": $el.data("title")
							};
							polygon.setMap(self.map);
							<?php /*?>google.maps.event.addListener(polygon, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;	
								$("#selectedArea").html(this.myObj.title);
								$("#selected_delivery_address").val(this.myObj.id);
							});<?php */?>
							overlays.push(polygon);
							tmp.type = "plygon";
							break;
						case 'rectangle':
							var bound,
								str = $el.val().replace(/\(|\s+/g, ""),
								arr = str.split("),"), 
								bounds = [];
							for (var i = 0, len = arr.length; i < len; i++) {
								arr[i] = arr[i].replace(/\)/g, "");
								bound = new google.maps.LatLng(arr[i].split(",")[0], arr[i].split(",")[1]);
								bounds.push(bound);
								mapBounds.extend(bound);
							}
							var rectangle = new google.maps.Rectangle({
								strokeColor: '#008000',
					            strokeOpacity: 1,
					            strokeWeight: 1,
					            fillColor: $el.data("color"),
					            fillOpacity: 0.5,
					            bounds: new google.maps.LatLngBounds(bounds[0], bounds[1]),
					            editable: false,
					            bounds_changed: function ($_el) {
					            	return function () {
					            		self.update.call(self, this, $_el, 'rectangle');
					            	};
					            }($el)
							});
							rectangle.myObj = {
								"id": $el.data("id"),
								"title": $el.data("title")
							};
							rectangle.setMap(self.map);

							<?php /*?>google.maps.event.addListener(rectangle, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;
								$("#selectedArea").html(this.myObj.title);
								$("#selected_delivery_address").val(this.myObj.id);

							});<?php */?>
							overlays.push(rectangle);
							tmp.type = "rectangle";
							break;
					}
				});

				//Zoom fix
				if (tmp.cnt === 1 && tmp.type === "circle") {
					this.map.setZoom(13);
				} else {
					this.map.fitBounds(mapBounds);
				}
			},
			setFocus: function (overlay) {
				overlay.setOptions({
					strokeColor: '#1B7BDC',
					fillColor: '+$el.data("color")+',
				});
				$(".btnDeleteShape").removeAttr("disabled");
			},
			removeFocus: function (overlays, exceptId) {
				if (overlays && overlays.length > 0) {
					for (var i = 0, len = overlays.length; i < len; i++) {
						if (overlays[i].myObj.id != exceptId) {
							overlays[i].setOptions({
								strokeColor: '#008000',
								fillColor: '#008000'
							});
						}
					}
				}
			}
		};
		
    $("input[name$='ordertype']").click(function() {
        var id = $(this).val();
		if(id=='pick_up')
		{	 $("#pickup").show();
			 $("#delivey").hide();
			 $("#pickuptime").show();
			 $("#deliverytime").hide();
		}
		else
		{
			$("#delivey").css("display", "block");
			$("#pickup").hide();
			 $("#pickuptime").hide();
			 $("#deliverytime").show();
		if ($frmUpdateLocation.length > 0) {
			//$frmUpdateLocation.validate();
			if (myGoogleMaps == null) {
				myGoogleMaps = new GoogleMaps();
			}
			myGoogleMaps.addMarker(new google.maps.LatLng($("#lat").val(), $("#lng").val()));
			myGoogleMaps.draw();
		}
		}
    });
	});
</script> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script> 
<script>
$(document).ready(function() {
           initializemap();
    });

	var searchlat;
	var searchlong;
	var globalpoly = new Array();
	var globalLocation = new Array();
	
	function initializemap() 
	{
		var input = document.getElementById('searchTextField');
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setComponentRestrictions({'country':'us'});
		google.maps.event.addListener(autocomplete, 'place_changed', function() 
		{
			var place = autocomplete.getPlace();
			
			document.getElementById('hdLatitude').value = place.geometry.location.lat();
			document.getElementById('hdLongitude').value = place.geometry.location.lng();
			
			btnCheckPointExistence_onclick();
		});
	}
 
	google.maps.event.addDomListener(window, 'load', initializemap);

	var map;
	var boundaryPolygon;
	function initialize1() 
	{
		google.maps.Polygon.prototype.Contains = function(point) {
		var crossings = 0,
		path = this.getPath();
        for (var i = 0; i < path.getLength(); i++) {
        var a = path.getAt(i),
        j = i + 1;
        if (j >= path.getLength()) {
        j = 0;
        }
        var b = path.getAt(j);
        if (rayCrossesSegment(point, a, b)) {
        crossings++;
        }
        }
        // odd number of crossings?
        return (crossings % 2 == 1);
        function rayCrossesSegment(point, a, b) {
        var px = point.lng(),
        py = point.lat(),
        ax = a.lng(),
        ay = a.lat(),
        bx = b.lng(),
        by = b.lat();
        if (ay > by) {
        ax = b.lng();
        ay = b.lat();
        bx = a.lng();
        by = a.lat();
        }
        if (py == ay || py == by) py += 0.00000001;
        if ((py > by || py < ay) || (px > Math.max(ax, bx))) return false;
        if (px < Math.min(ax, bx)) return true;

        var red = (ax != bx) ? ((by - ay) / (bx - ax)) : Infinity;
        var blue = (ax != px) ? ((py - ay) / (px - ax)) : Infinity;
        return (blue >= red);
        }
        };

        var mapProp = {
        center: new google.maps.LatLng(43.937461690316646, -115.9661865234375),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };
		
        map = new google.maps.Map(document.getElementById('googleMapNew'), mapProp);

        google.maps.event.addListener(map, 'click', function(event) {
        if (boundaryPolygon != null && boundaryPolygon.Contains(event.latLng)) {
        	alert(event.latLng + ' is inside the polygon');
        } else {
        	alert(event.latLng + ' is outside the polygon');
        }
        });
        drawPolygon();
        }
		
    function drawPolygon() 
	{
			<?php 
			$strLocation[]="";
			if($allcord)
			{
				$i=0;
				foreach($allcord as $cord)
				{
					$chords = $cord['allcoord'];
					$chords = str_replace(' ', '', $chords);
					$chords = explode('),(', $chords);
					$string = "";
					for($t = 0; $t < count($chords); $t++)
					{
						$ss = $chords[$t];
						$ss = str_replace('(', '', $ss);
						$ss = str_replace(')', '', $ss);
						$yy = explode(',', $ss);
						
						$string .= (($string) ? ', ' : '' ) . $yy[1] . " " . $yy[0];
					}	
			?>
					var boundary<?php echo $i;?> = <?php echo "'" . $string . "'";?>;

					 var boundarydata<?php echo $i;?> = new Array();
					 
					 var latlongs<?php echo $i;?> = boundary<?php echo $i;?>.split(',');

					
					 
					  for (var i = 0; i < latlongs<?php echo $i;?>.length; i++) 
					  {
						   latlong<?php echo $i;?> = latlongs<?php echo $i;?>[i].trim().split(' ');
						   boundarydata<?php echo $i;?>[i] = new google.maps.LatLng(latlong<?php echo $i;?>[1], latlong<?php echo $i;?>[0]);
					  }
					  
					  boundaryPolygon<?php echo $i;?> = new google.maps.Polygon({
					  path: boundarydata<?php echo $i;?>,
					  strokeColor: '#0000FF',
					  strokeOpacity: 0.8,
					  strokeWeight: 2,
					  fillColor: 'Red',
					  fillOpacity: 0.4
							});
							
							globalpoly[<?php echo $i;?>]=boundaryPolygon<?php echo $i;?>;
							
							globalLocation[<?php echo $i;?>] = <?php echo $cord['location'];?>;
							
							<?php $strLocation[$i] =  $cord['location'];
							echo $strLocation[$i];
							?>
							
							google.maps.event.addListener(boundaryPolygon<?php echo $i;?>, 'click', function(event) 
							{
								if (boundaryPolygon<?php echo $i;?>.Contains(event.latLng)) {
									alert(event.latLng + 'is inside the polygon.');
								} 
								else {
									alert(event.latLng + 'is outside the polygon.');
								}
							});
		
							map.setCenter(boundarydata<?php echo $i;?>[0]);
							boundaryPolygon<?php echo $i;?>.setMap(map);
			<?php	
					$i++;
				}
			}
			?>

	}
	
	function btnCheckPointExistence_onclick() 
	{
		var latitude = document.getElementById('hdLatitude').value;
		var longitude = document.getElementById('hdLongitude').value;
		var myPoint = new google.maps.LatLng(latitude, longitude);
		var IsFound=0;
		
		for(var j=0;j<globalpoly.length;j++)
		{
			if(IsFound==0){
				if (globalpoly[j] == null) {
					alert('Opps!! No delivery area defined by admin.');
				}
				else 
				{
					if (globalpoly[j].Contains(myPoint)) 
					{
						IsFound=1;
						document.getElementById('deliveryarea_location_id').value = globalLocation[j];
					} else {
						IsFound=0;
						document.getElementById('deliveryarea_location_id').value = '';
						}
					}
			 }
		}
		
		if(IsFound==0)
		{
        	alert('Delivery is not available for entered address, please choose another delivery address or choose order type to pickup.');
	        document.getElementById('searchTextField').value='';
        }

    }
        google.maps.event.addDomListener(window, 'load', initialize1);

</script>
<div id="map-canvas" style="display: none;"></div>
<!-- AS SOON AS POSSIBLE Radio btn checkded unchecked --> 

<script type="text/javascript">

	$("#asap_pickup").prop("checked", true);
	$('#asap_pickup').click(function() {
	$('#asapPickupdatatime').hide();
	$('#timelist').hide();			
		
});
$('#ftime').click(function() {
	$('#asapPickupdatatime').show();
	$('#timelist').show();
	$( "input#asap_pickup" ).prop( "checked", false );

});
	/*var radioChecked = $('#asap_pickup').is(':checked');
	
	$('#asap_pickup').click(function(){
		
			radioChecked = !radioChecked;
			
			if(radioChecked){
			
					$(this).attr('unchecked', radioChecked);
					$('#asapPickupdatatime').hide();
					$('#timelist').hide();
			}
			else
			{
					$(this).attr('checked', radioChecked);
					$('#asapPickupdatatime').show();
					$('#timelist').show();
			}
		$(this).attr('unchecked', radioChecked);
	
	});*/

	$("#asap_delivery").prop("checked", true);
	$('#asap_delivery').click(function() {
			$('#asapDatatime').hide();
});
$('#dftime').click(function() {
			$('#asapDatatime').show();	
})
	/*var radioChecked = $('#asap_delivery').is(':checked');
	
	$('#asap_delivery').click(function(){
		
			radioChecked = !radioChecked;
			
			if(radioChecked){
			
				$(this).attr('unchecked', radioChecked);
				$('#asapDatatime').hide();
			}
			else
			{
					$(this).attr('checked', radioChecked);
					$('#asapDatatime').show();
			}
	
		$(this).attr('unchecked', radioChecked);
	
	});*/
</script> 
<script type="text/javascript">
 
	  /*$('#addresstype_bussiness').click(function(){
		 
		 // alert("hello2");
		if (this.checked){ 
			 //alert("hello3");
			$('#business_name').show();
		}
		else {
			// alert("hello4");
			$('#business_name').hide();

		}
	});*/
	
	 $(document).ready(function(){	
	 
	 	$(".addr_hide").click(function(){
			
			var attribute_Idval = $(this).attr("id");
			var attribute_val = $(this).val();
						
			if(attribute_val =="addr_business"){
				
				$('#business_name').show();				
			}
			else {
				
				$('#business_name').hide();
			}			
		});	
	 }); 
</script> 
<script type="text/javascript">

 $(document).ready(function(){	

		$('#deliver').click(function(){	
			$("#pickuptime").hide();	
			$("#showDeliveryDetails").show();
		});

		$('#pick').click(function(){
			$("#showDeliveryDetails").hide();
			$("#pickuptime").show();
		});

 });
</script> 
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
				success: function(theResponse){ 
				
					$("#productID_" + productIDVal).hide("slow",  function() {$(this).remove();});
					$("#smallLoader").css({display: "none"});
					window.location.reload();
				}  
			});	

	});

	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
        };

	})();

	$("a.deleteitem img").click(function(){		
		delay(function(){		
				
				$.ajax({
			   		type: "POST",  
					url: "<?php echo SITEURL; ?>/ajax/basket.php", 
					data: { action: "addViewCart"},  
					success: function(theResponse){	
						$("#totalprice").html(theResponse).show();	
						$("#hideprice").hide();	
						$("#hidemeniheading").hide();	
					}
			});
		}, 2000);
	});	

	$("a.CancelOrder").click(function(){
		
		$("#smallLoader").css({display: "block"});	
		$.ajax({
			type: "POST",  
			url: "<?php echo SITEURL; ?>/ajax/user.php", 
			data: { CancelOder: "1"},  
			success: function(theResponse)
			{
				$("#smallLoader").css({display: "none"});
				window.location.reload();
			}
		});
	});
	
	$("a.ChanegeLocation").click(function(){
		
		$("#smallLoader").css({display: "block"});	
		$.ajax({
			type:"POST",
			url:"<?php echo SITEURL;?>/ajax/user.php",
			data:{ChangeLocation:"1"},
			success: function(theResponse)
			{
				$("#smallLoader").css({display: "none"});	
				window.location.href= SITEURL+"/chooselocation";

			}
		});

       });	
   });
// ]]>
</script> 
