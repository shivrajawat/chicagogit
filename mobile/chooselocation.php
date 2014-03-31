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
<?php 
	$ordertype = $menu->OrderType($websitenmae);
	$location = $menu->locationListByMenu($websitenmae);		
?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.validate.min.js"></script>
<div data-role="content">
  <h1 class="main-heading">SELECT ORDER TYPE, DATE & TIME </h1>  
    <form name="order_form" id="order_form" method="post">
      <div data-role="fieldcontain">
        <?php $ordertype = $menu->OrderTypeMobile();  ?>
        <select name="ordertype" id="ordertype" ajax-data="false">
            <?php if($ordertype['pick_up']==1){  ?>                  
            <option value="pick_up" selected="selected">Pickup</option>
            <?php } if($ordertype['delivery']==1){ ?>
            <option value="delivery" >Delivery</option>
            <?php } if($ordertype['dineln']==1){  ?>
            <option value="<?php  echo "Dine In"; ?>" ><?php  echo "Dine In";  ?></option>
            <?php  } ?>
        </select>
      </div>
      
      <div id="deliveryshow" style="display:none">
      <div data-role="fieldcontain"> 
          <input id="searchTextField" type="text" class="deliveryarloc ui-input-text ui-body-c" name="changeaddressdelevery">
          <input id="deliveryarea_location_id" type="hidden" class="deliveryarlocsel" name="deliveryarea" value="">
      </div>
      <?php if($content->show_deliveryaddress):?>
      <fieldset data-role="controlgroup">
    <input name="address_type_residence" id="checkbox-1a" checked="" type="checkbox" value="1" <?php if(isset($crow['addr_residence']) && $crow['addr_residence'] == 1){ echo 'checked="checked"'; } ?>>
    <label for="checkbox-1a">Residence</label>
    <input name="address_type_business" id="checkbox-2a" type="checkbox" <?php if(isset($crow['addr_business']) && $crow['addr_business'] == 1){ echo 'checked="checked"'; } ?>  onclick="Change();" value="1" ajax-data="false">
    <label for="checkbox-2a">Business</label>
    <input name="address_type_university" id="checkbox-3a" type="checkbox" <?php if(isset($crow['addr_university']) && $crow['addr_university'] == 1){ echo 'checked="checked"'; } ?> value="1" aja-data="flase">
    <label for="checkbox-3a">University</label>
    <input name="address_type_military" id="checkbox-4a" type="checkbox" <?php if(isset($crow['addr_military']) && $crow['addr_military'] == 1){ echo 'checked="checked"'; } ?>  value="1">
    <label for="checkbox-4a">Military</label>
</fieldset>
      <div data-role="fieldcontain" id="business_name" <?php if(isset($crow['addr_business']) && $crow['addr_business'] == 1){ echo $style = 'style="display:block"'; } else { echo $style = 'style="display:none"'; } ?> >   
            <input type="text" class="deliveryarloc ui-input-text ui-body-c" name="business_name" value="<?php echo ($crow['business_name']) ? ucwords($crow['business_name']) : "";  ?>" placeholder="Business name">
      </div>
      <div data-role="fieldcontain" >    
            <input type="text" class="deliveryarloc ui-input-text ui-body-c" name="apt" placeholder="Apt/Suite/Floor" title="Apt/Suite/Floor" />
      </div>
      <div data-role="fieldcontain"> 
            <input type="text" class="deliveryarloc ui-input-text ui-body-c" name="zip_code" placeholder="Zip Code" title="Zip Code" />
      </div>
       <?php endif;?>      
      <label><input name="asap_delivery" id="asap_delivery"  value="asap_delivery" type="radio">ASAP</label>
   	  <label>Future Time<input id="dftime" value="dftime" name="asap_delivery" class="custom" type="radio"></label>
      <div id="asapDatatime" style="display:none">
      <div data-role="fieldcontain">       
        <select id="dp2" class="deliverytimedate deliveryarloc" name="deliverydate_time">
            <option value="" selected="selected">Select Order Date</option>
       		<option value="<?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>" ><?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>
            </option>   
			 <?php  for($i=1; $i<=10; $i++){ ?> 
             <option value="<?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?>"><?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?></option>
             <?php }  ?>             
       	</select>
      </div>
      <div data-role="fieldcontain" id="open_res" class="fff">
        <select name="dhour" id="delivery_display_time" class="sss">  
        	<option value="">Order Time</option>      
        </select>
      </div>
      </div>
      </div>      
      <div id="pickupshow">
      <div data-role="fieldcontain">
         <select name="changeaddress" id="changeaddress" >
            <?php
			$pickuplocation = $menu->pickUpLocationMobile($location);			
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
       <label> <input type="radio" id="asap_pickup" value="asap_pickup" name="asap_pickup" />ASAP</label>
   	  <label> <input type="radio" id="ftime" value="ftime" name="asap_pickup" />Future Time</label>  
      <div id="asapPickupdatatime"  style="display:none">  
      <div data-role="fieldcontain">
        	<select name="pickupdate_time" id="select-native-1" class="picktimedate" >
            	 <option value="" selected="selected">Select Order Date</option>
       			 <option value="<?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>" >
				 <?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d'),date('Y')));  ?>
                 </option>   
				<?php
                    for($i=1; $i<=10; $i++){
                ?> 
                <option value="<?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?>">
					<?php echo date('m/d/Y',mktime(0,0,0,date('m'),date('d')+$i,date('Y'))); ?>
                </option>
                 <?php }  ?>             
        </select>
      </div>
      <div data-role="fieldcontain" id="open_res" class="fff">
        <select name="hour" id="display_time" class="sss">  
        	<option value="">Order Time</option>      
        </select>
      </div>
      </div>
      </div>
      <input type="submit" value="Submit">
    </form>
    <div data-role="content" style="display:none">
      <p>Notes:<br><?php echo ($ordertype['notes']) ? cleanOut($ordertype['notes']) : "";  ?></p>
    </div>
    <!-- /content -->
    <a href="index.php" data-role="button" data-theme="b" data-ajax="false">Back To Home</a>
</div>
</div>
<div id="googleMapNew" style="display:none"></div>
<?php include("footer.php");?>
<input type="hidden" name="hdLatitude" id="hdLatitude" />
<input type="hidden" name="hdLongitude" id="hdLongitude" />
<?php $ByDefoultlatlong  = $menu->getLatituteLongitute($location);
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
<?php $allcord = $menu->AllCoordinates($location); ?>
<script type="text/javascript">

$(document).ready(function() {  
	
 $("#order_form").validate({
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
			var typecheck = $("#ordertype").val();
			if(typecheck == 'delivery')
			{
				if(!sdd)
				{
					alert("Delivery address is outside our delivery zone.");
					return false;
				}
			}
		 var str = $("#order_form").serialize(); //alert(str);		 
		 $.mobile.loading("show");
		 	 
		 $.ajax({
				type: "POST",														
				url: "<?php echo SITEURL;?>/ajax/locationchoose.php",				
				data: str,
				success: function (msg){											  
					$.mobile.loading("hide");	
					var msg_1=msg.split('-');
					//alert(msg);
					if(msg)
					{
						if(msg_1[0]=='close')
						{
							var timedate = msg_1[1];
							alert("We are sorry but we are closed for the day.\nYour order will be placed for the next time \nwe are open which will be "+timedate);	
							window.location = "<?php echo SITEURL;?>/mobile/restaurantmenu.php";
						}
					}
					else
					{
						window.location = "<?php echo SITEURL;?>/mobile/restaurantmenu.php";
					}
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

<script type="text/javascript">
$(document).ready(function(){
	
	$(".deliverytimedate").change(function(){
			var date=$(this).val();
			var locationid = $("#deliveryarea_location_id").val();		
					
			if(locationid =="")
				{
					alert("Delivery address is outside our delivery zone.");
				$("#dp2").val('');
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
						$("#delivery_display_time").html(html);
					} 
				});
		
		});
});
</script>
<!-----------------------on selecting date, show time, ends here--------------------------->
<!-------- ---------------Google map for delivery area --------------------------->
<script type="text/javascript">
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
				$(".coords").each(function (i, el) {
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
								fillColor: '#008000',
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
								fillColor: '#008000',
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
					            fillColor: '#008000',
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
					fillColor: '#4295E8'
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
		{
			 $("#pickup").show();
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
<script type="text/javascript">
	$('#ordertype').change(function(){	
 		var regular= $('#ordertype').val(); 
        if (regular == 'pick_up') {			
			$('#pickupshow').show();
			$('#deliveryshow').hide();	
			$("#pickuptime").show();
		    $("#deliverytime").hide();		
		}			
		else if (regular == 'delivery') {			
			$('#deliveryshow').show();
			$('#pickupshow').hide();
			 $("#pickuptime").hide();
			 $("#deliverytime").show();
		}
 	});
</script>
<script type="text/javascript">
 function Change() {
 
 $('#checkbox-2a').click(function() {
    if (this.checked) {	
        $('#business_name').show();
    }
    else {	
        $('#business_name').hide();
    }
});
}

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
	

	$("#asap_delivery").prop("checked", true);
	$('#asap_delivery').click(function() {
			$('#asapDatatime').hide();
});
$('#dftime').click(function() {
			$('#asapDatatime').show();	
})
</script>