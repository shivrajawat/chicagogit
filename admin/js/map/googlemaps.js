(function ($, undefined) {
	$(function () {
	
		var $frmCreateLocation = $('#frmCreateLocation'),
			$frmUpdateLocation = $('#frmUpdateLocation'),
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
				/*google.maps.event.addListener(self.map, 'click', function(e) {
					self.addMarker(e.latLng);
		        });*/
				return self;
			},
			/*addMarker: function (position) {
				if (myGoogleMapsMarker != null) {
					myGoogleMapsMarker.setMap(null);
				}
				myGoogleMapsMarker = new google.maps.Marker({
					map: this.map,
					position: position,
					icon: "app/web/img/frontend/pin.png"
				});
				this.map.setCenter(position);
				$("#lat").val(position.lat());
				$("#lng").val(position.lng());
				return this;
			},*/
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
		
			
		if ($frmCreateLocation.length > 0) {
			$frmCreateLocation.validate();			
					myGoogleMaps = new GoogleMaps();
					myGoogleMaps.drawing();
					google.maps.event.trigger(myGoogleMaps.map, 'resize');
					
					google.maps.event.addDomListener($(".btnDeleteShape").get(0), "click", function () {
						myGoogleMaps.deleteShape(overlays);
					});
		}
		
		if ($frmUpdateLocation.length > 0) {
			$frmUpdateLocation.validate();
			
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
})(jQuery);