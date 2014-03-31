<?php
  /**
   * Delivery Area Manager
   *
  
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("DeliveryArea")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<style>
#map_canvas {
	height: 100%;
}
.map_holder {
	border: 1px solid #CCCCCC;
	height: 280px;
	margin: 0 0 10px;
	padding: 10px;
	width: 970px;
}
 @media print {

#map_canvas {
	height: 650px;
}
}
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing"></script>
<!--<script type="text/javascript" src="js/map/googlemaps.js"></script>-->
<script type="text/javascript" src="plugins/validate/js/jquery.validate.min.js"></script>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
  $('#colorpicker').colorPicker();
});
// ]]>
</script>
<?php switch($core->action): case "edit": ?>
<?php 
	$row = $content->editDeliveryArea($content->postid);	
	
	if(isset($_GET['postid']) && !empty($_GET['postid'])){
		
		$color_code = getValue("color_code","res_location_google_cordinates","location_id = '".$_GET['postid']."'");
		
		if(isset($color_code) && !empty($color_code)){
			
			$color_code2 = $color_code;			
		}
		
	}
	
 ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _DLA_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _DLA_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _DLA_SUBTITLE1 . $row['location_name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="frmUpdateLocation" class="form frmLocation frmUpdateLocation" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _DLA_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><input type="button" value="Delete Selected Shape" class="button-orange btnDeleteShape" disabled="disabled" /><a href="index.php?do=delivery_area_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _DIL_LOC;?>:</th>
            <td><select name="location_id" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Your location</option>
                <?php foreach ($locationrow as $lrow):?>
                <?php $sel = ($row['id'] == $lrow['id']) ? 'selected="selected"' : '' ;?>
                <option value="<?php echo $lrow['id'];?>"<?php echo $sel;?>><?php echo $lrow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _DIL_TITLE;?>: <?php echo required();?></th>
            <td><input name="address" id="address" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/>
              <a href="#" class="btnGetCoords" ><img src="images/search.png" class="align_middle" alt="" /></a></td>
          </tr>
          <?php /*?><tr>
            <th>Color Code: </th>
            <td><input name="color_code" type="text" class="inputbox" value="<?php echo ($color_code && !empty($color_code)) ? $color_code : ""; ?>"  size="55"/></td>
          </tr><?php */?>
          <?php if(isset($color_code) && !empty($color_code)){ ?>
          <tr>
            <th>Color Code:</th>
            <td><input id="colorpicker" name="color_code" type="text" value="<?php echo $color_code; ?>" /></td>
          </tr>
          <?php } else { ?>
          <tr>
            <th>Color Code:</th>
            <td><input id="colorpicker" name="color_code" type="text" value="#<?php echo str_replace("#", "", $core->bgcolor);?>" /></td>
          </tr>          
          <?php  } ?>
          <tr>
            <td colspan="2"><div class="map_holder">
                <div id="map_canvas"></div>
              </div></td>
          </tr>
        </tbody>
      </table>
      <?php
	  	$coords = $content->getcoordmaps($row['id']);
		foreach ($coords as $c)
		{
			?>
			<input type="hidden" name="data[<?php echo $c['type']; ?>][<?php echo $c['id']; ?>]" value="<?php echo $c['data']; ?>" class="coords" data-type="<?php echo $c['type']; ?>" data-id="<?php echo $c['id']; ?>" />
			<?php
		}
		?>
       <input type="hidden" name="lat" id="lat" value="<?php echo $row['latitude']; ?>" />
      <input type="hidden" name="lng" id="lng" value="<?php echo $row['longitude'];?>" />
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doFormNew("updateDelivaryArea","controller.php","frmUpdateLocation");?>

<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _DLI_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _DLI_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _DLI_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="" method="post" id="frmCreateLocation" class="frmLocation">      
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _DLI_ADD;?>" name="location_create" />               
                <span></span></div></td>
            <td><input type="button" value="Delete Selected Shape" class="button-orange btnDeleteShape" disabled="disabled" /> <a href="index.php?do=delivery_area_master" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _DIL_LOC;?>:</th>
            <td><select name="location_id" class="custombox" style="width:300px">
                <?php $locationrow = $content->getlocationlist($userlocationid);?>
                <option value="">Please Select Your location</option>
                <?php foreach ($locationrow as $lrow):?>
                <option value="<?php echo $lrow['id'];?>"><?php echo $lrow['location_name'];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _DIL_TITLE;?>: <?php echo required();?></th>
            <td><input name="address" id="address" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/>
              <a href="#" class="btnGetCoords" ><img src="images/search.png" class="align_middle" alt="" /></a></td>
          </tr>
          <?php /*?>  <tr>
            <th>Color Code: </th>
            <td><input name="color_code" type="text" class="inputbox"  size="55"/></td>
          </tr><?php */?>
          <tr>
            <th>Color Code:</th>
            <td><input id="colorpicker" name="color_code" type="text" value="#<?php echo str_replace("#", "", $core->bgcolor);?>" /></td>
          </tr>
          <tr>
            <td colspan="2"><div class="map_holder">
                <div id="map_canvas"></div>
              </div></td>
          </tr>
        </tbody>
      </table>      
      <input type="hidden" name="lat" id="lat" value="" />
      <input type="hidden" name="lng" id="lng" value="" />
    </form>
  </div>
</div>
<?php echo $core->doFormNew("processDelivaryArea","controller.php","frmCreateLocation");?>

<?php break;?>
<?php default: ?>
<?php $deliveryrow = $content->getDeliveryArea($userlocationid); ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _DLI_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _DLINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2>
     <?php if($user->userlevel == 9):?>
    <span><a href="index.php?do=delivery_area_master&amp;action=add" class="button-sml"><?php echo _DLI_ADD;?></a></span>
    <?php endif;?>
	<?php echo _DLI_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <?php if($pager->display_pages()):?>
    <div class="utility">
      <table class="display">
        <tr>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <?php endif;?>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _DIL_LOC;?></th>
          <th class="left sortable"><?php echo _DIL_TITLE;?></th>         
          <th><?php echo _DLA_EDIT;?></th>
          <?php if($user->userlevel == 9):?>
          <th><?php echo _DELETE;?></th>
          <?php endif;?>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="6"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$deliveryrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_DLA_NODELIVERY,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($deliveryrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['location_name'];?></td>
          <td><?php echo $row['address1'] ;?></td>          
          <td class="center"><a href="index.php?do=delivery_area_master&amp;action=edit&amp;postid=<?php echo $row['location_id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _DLA_EDIT;?>"/></a></td>
          <?php if($user->userlevel == 9):?>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['location_name'];?>" id="item_<?php echo $row['location_id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._DLI_, "deleteDeliveryArea");?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            3: {
                sorter: false
            },
            4: {
                sorter: false
            },
            5: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>
<script>
$(function () {
	
	    var d_color_code = $("#colorpicker").val();
		
		if(d_color_code!=''){
			color_code = d_color_code;
		}
		else {
			color_code = '#008000';
		}
	
		var $frmCreateLocation = $('#frmCreateLocation'),
			$frmUpdateLocation = $('#frmUpdateLocation'),
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
			$.get("<?php echo SITEURL;?>/ajax/googlemaps.php?action=getCoords", {
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
								fillColor: color_code,
								//fillColor: '#008000',
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
								fillColor: color_code,
								//fillColor: '#008000',
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
					            fillColor: color_code,
								//fillColor: '#008000',
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
						fillColor: color_code,
					   //fillColor: '#008000',
						fillOpacity: 0.5,
					    strokeWeight: 1,
					    strokeColor: '#008000',
					    strokeOpacity: 1,
						editable: true
					},
					polygonOptions: {
						fillColor: color_code,
					    //fillColor: '#008000',
						fillOpacity: 0.5,
					    strokeWeight: 1,
					    strokeColor: '#008000',
					    strokeOpacity: 1,
						editable: true
					},
					rectangleOptions: {
						fillColor: color_code,
						//fillColor: '#008000',
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
</script>
