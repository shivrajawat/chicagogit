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
            <input type="radio" name="ordertype"  value="pick_up">
            Pick UP</label>
          </div>
        </div>
        <div class="span4">
          <div class="btn btn-success">
            <label class="radio">
            <input type="radio" name="ordertype"  value="delivery" checked="checked">
            Delivery</label>
          </div>
        </div>
        <div class="clr"></div>
        
          <div id="pickup" class="desc" style="display:none">
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
        
        <div id="delivey">
          <div class="span12 fit ">
            <div class="title-box">Delivery Area</div>
          </div>
          <div class="span12 fit">
            <div class="map_holder">
                <div id="map_canvas"></div>
              </div>
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
      </div><input type="hidden" name="data[polygon][37]" value="(27.328831223685675, 76.0748291015625), (27.328831223685675, 75.52001953125), (27.192105666542524, 75.6463623046875), (27.128568859783016, 75.673828125)" class="coords" data-type="polygon" data-id="37" />
						<input type="hidden" name="data[polygon][38]" value="(27.689365202759078, 75.1629638671875), (27.971116901981443, 74.9981689453125)" class="coords" data-type="polygon" data-id="38" />
						<input type="hidden" name="data[polygon][39]" value="(27.971116901981443, 74.9981689453125)" class="coords" data-type="polygon" data-id="39" />
						<input type="hidden" name="data[polygon][40]" value="(27.75744116324521, 75.0091552734375), (27.806040807129378, 75.047607421875), (28.014771342140307, 74.9652099609375), (28.058408093326786, 75.2618408203125), (27.66504206300213, 75.1959228515625), (28.053560439510917, 75.2947998046875)" class="coords" data-type="polygon" data-id="40" />
						<input type="hidden" name="data[polygon][41]" value="(28.072949743037334, 75.2728271484375), (27.718545819185294, 75.201416015625)" class="coords" data-type="polygon" data-id="41" />
						<input type="hidden" name="data[polygon][42]" value="(27.835190164456343, 75.113525390625)" class="coords" data-type="polygon" data-id="42" />
						<input type="hidden" name="data[polygon][43]" value="(27.805554888557875, 75.06134033203125)" class="coords" data-type="polygon" data-id="43" />
						<input type="hidden" name="data[polygon][44]" value="(27.827417738071848, 75.11627197265625), (27.28441383164302, 75.6463623046875), (27.269766849948354, 75.70953369140625)" class="coords" data-type="polygon" data-id="44" />
						<input type="hidden" name="data[polygon][45]" value="(40.36412549384708, -73.8665771484375), (40.7730539304473, -73.1304931640625), (40.77721378585079, -74.37744140625)" class="coords" data-type="polygon" data-id="45" />
						<input type="hidden" name="data[polygon][46]" value="(40.49375050411333, -74.1082763671875), (40.7938505392551, -73.740234375), (40.947543465884394, -73.8775634765625)" class="coords" data-type="polygon" data-id="46" />
						<input type="hidden" name="data[polygon][47]" value="(41.51844926518588, -68.038330078125), (40.790523623525594, -73.092041015625)" class="coords" data-type="polygon" data-id="47" />
						<input type="hidden" name="data[polygon][48]" value="(43.73300132341176, -22.1484375), (30.932385134454616, -9.4921875), (45.97558706008126, -6.328125)" class="coords" data-type="polygon" data-id="48" />
						<input type="hidden" name="data[polygon][49]" value="(36.20173071347575, 23.73046875), (41.66634607095136, 40.25390625), (46.94426183573009, 14.765625)" class="coords" data-type="polygon" data-id="49" />
			       <input type="hidden" name="lat" id="lat" value="1.000000" />
      <input type="hidden" name="lng" id="lng" value="1.000000" />
      <input name="postid" type="hidden" value="2" />
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
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing"></script>
<script>
$(function () {
		var $frmUpdateLocation = $('#checkoutform'),
			$tabs = $("#tabs"),
			$dialogDelete = $("#dialogDelete"),
			dialog = ($.fn.dialog !== undefined),
			$content = $("#content"),
			overlays = [],
			selectedShape = null;
			
		$content.delegate(".btnGetCoords", "click", function (e) {
															  
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.get("http://192.168.0.2/onlinefoodorder/ajax/googlemaps.php?action=getCoords", {
				"address": $("#address").val()
			}).done(function (data) {
				if (data.lat && data.lng) {
					$("#map_error").hide();
					if (myGoogleMaps == null) {
						myGoogleMaps = new GoogleMaps();
					}
					google.maps.event.trigger(myGoogleMaps.map, 'resize');
					myGoogleMaps.map.setCenter(new google.maps.LatLng(data.lat, data.lng));
					myGoogleMaps.addMarker(new google.maps.LatLng(data.lat, data.lng));
				} else {
					$("#map_error").show();
					$("#lat").val("");
					$("#lng").val("");
				}
			});
			return false;
		});	
		
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
					            editable: true,
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
								"id": $el.data("id")
							};
							circle.setMap(self.map);
							mapBounds.extend(center);
							google.maps.event.addListener(circle, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;
							});
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
					            editable: true
						    });
							polygon.myObj = {
								"id": $el.data("id")
							};
							polygon.setMap(self.map);
								
							google.maps.event.addListener(polygon, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;
							});
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
					            editable: true,
					            bounds_changed: function ($_el) {
					            	return function () {
					            		self.update.call(self, this, $_el, 'rectangle');
					            	};
					            }($el)
							});
							
							rectangle.myObj = {
								"id": $el.data("id")
							};
							rectangle.setMap(self.map);
								
							google.maps.event.addListener(rectangle, "click", function () {
								self.removeFocus(overlays, this.myObj.id);
								self.setFocus(this);
								selectedShape = this.myObj.id;
							});
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
			drawing: function () {
				var self = this;
				this.drawingManager = new google.maps.drawing.DrawingManager({
					drawingMode: google.maps.drawing.OverlayType.POLYGON,
					drawingControl: true,
					drawingControlOptions: {
						position: google.maps.ControlPosition.TOP_CENTER,
						drawingModes: [
				            google.maps.drawing.OverlayType.CIRCLE,
				            google.maps.drawing.OverlayType.POLYGON,
				            google.maps.drawing.OverlayType.RECTANGLE
				        ]
					},
					circleOptions: {
						fillColor: '#008000',
						fillOpacity: 0.5,
					    strokeWeight: 1,
					    strokeColor: '#008000',
					    strokeOpacity: 1,
						editable: true
					},
					polygonOptions: {
						fillColor: '#008000',
						fillOpacity: 0.5,
					    strokeWeight: 1,
					    strokeColor: '#008000',
					    strokeOpacity: 1,
						editable: true
					},
					rectangleOptions: {
						fillColor: '#008000',
						fillOpacity: 0.5,
					    strokeWeight: 1,
					    strokeColor: '#008000',
					    strokeOpacity: 1,
						editable: true
					}
				});
				this.drawingManager.setMap(this.map);
				
				google.maps.event.addListener(this.drawingManager, 'overlaycomplete', function(event) {
					var rand = Math.ceil(Math.random() * 999999),
						$frm = $(".frmLocation").eq(0);
					switch (event.type) {
						case google.maps.drawing.OverlayType.CIRCLE:
							var input = $("<input>", {
								"type": "hidden",
								"name": "data[circle][new_" + rand + "]",
								"class": "coords",
								"data-type": "circle",
								"data-id": "new_" + rand
							}).appendTo($frm);
							self.update.call(self, event.overlay, input, 'circle');
							break;
						case google.maps.drawing.OverlayType.POLYGON:
							var input = $("<input>", {
								"type": "hidden",
								"name": "data[polygon][new_" + rand + "]",
								"class": "coords",
								"data-type": "polygon",
								"data-id": "new_" + rand
							}).appendTo($frm);
							self.update.call(self, event.overlay, input, 'polygon');
							break;
						case google.maps.drawing.OverlayType.RECTANGLE:
							var input = $("<input>", {
								"type": "hidden",
								"name": "data[rectangle][new_" + rand + "]",
								"class": "coords",
								"data-type": "rectangle",
								"data-id": "new_" + rand
							}).appendTo($frm);
							self.update.call(self, event.overlay, input, 'rectangle');
							break;
					}
					
					event.overlay.myObj = {
						id: "new_" + rand
					};
					
					google.maps.event.addListener(event.overlay, "click", function () {
						self.removeFocus(overlays, this.myObj.id);
						self.setFocus(this);
						selectedShape = this.myObj.id;
					});
					
					overlays.push(event.overlay);
				});
			},
			update: function (obj, $el, type) {
				switch (type) {
					case "circle":
						$el.val(obj.getCenter().toString()+"|"+obj.getRadius());
						break;
					case "polygon":
						var str = [],
							paths = obj.getPaths();
						paths.getArray()[0].forEach(function (el, i) {
							str.push(el.toString());
						});
						$el.val(str.join(", "));
						break;
					case "rectangle":
						$el.val(obj.getBounds().toString());
						break;
				}
			},
			deleteShape: function (overlays) {
				if (overlays && overlays.length > 0) {
					for (var i = 0, len = overlays.length; i < len; i++) {
						if (overlays[i].myObj.id == selectedShape) {
							overlays[i].setMap(null);
							$(".btnDeleteShape").attr("disabled", "disabled");
							$(".coords[data-id='" + selectedShape + "']").remove();
							return true;
							break;
						}
					}
				}
				return false;
			},
			clearOverlays: function (overlays) {
				if (overlays && overlays.length > 0) {
					while (overlays[0]) {
						overlays.pop().setMap(null);
					}
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
		if ($frmUpdateLocation.length > 0) {
			//$frmUpdateLocation.validate();
			
			if (myGoogleMaps == null) {
				myGoogleMaps = new GoogleMaps();
			}
			myGoogleMaps.addMarker(new google.maps.LatLng($("#lat").val(), $("#lng").val()));
			myGoogleMaps.draw();
			myGoogleMaps.drawing();
			
			google.maps.event.addDomListener($(".btnDeleteShape").get(0), "click", function () {
				myGoogleMaps.deleteShape(overlays);
			});
		}
		
		if ($dialogDelete.length > 0 && dialog) {
			$dialogDelete.dialog({
				autoOpen: false,
				resizable: false,
				draggable: false,
				modal: true,
				buttons: {
					'Delete': function() {
						$.post("index.php?controller=pjAdminLocations&action=delete", {
							id: $(this).data('id')
						}).done(function (data) {
							$content.html(data);
							$("#tabs").tabs();
						});
						$(this).dialog('close');			
					},
					'Cancel': function() {
						$(this).dialog('close');
					}
				}
			});
		}
	});
</script>