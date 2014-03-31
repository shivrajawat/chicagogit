<!DOCTYPE html>
<html><head>
    <title>Places Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
    </style>
    <script src="http://192.168.0.2/kulacart/assets/jquery.js" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>

    <style>
      input {
        border: 1px solid  rgba(0, 0, 0, 0.5);
      }
      input.notfound {
        border: 2px solid  rgba(255, 0, 0, 0.4);
      }
    </style>

    <script>
	var searchlat;
	var searchlong;
function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 13,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  var input = /** @type {HTMLInputElement} */(document.getElementById('searchTextField'));
  var autocomplete = new google.maps.places.Autocomplete(input);

  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map
  });

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    input.className = '';
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      // Inform the user that the place was not found and return.
      input.className = 'notfound';
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
	var te = place.geometry.location;
	var tes = $.trim(te).split(',');
	searchlat = tes[0].replace("(", "");
	searchlong = tes[1].replace(")", "");
	//alert(tes[0].replace("(", ""));
	//alert(tes[1].replace(")", ""));
	btnCheckPointExistence_onclick(searchlat, searchlong);
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }

    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });

  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    google.maps.event.addDomListener(radioButton, 'click', function() {
      autocomplete.setTypes(types);
    });
  }
}

google.maps.event.addDomListener(window, 'load', initialize);
function btnCheckPointExistence_onclick(searchlat, searchlong) {
	google.maps.Polygon.prototype.Contains = function (point) {
            // ray casting alogrithm http://rosettacode.org/wiki/Ray-casting_algorithm
            var crossings = 0,
            path = this.getPath();

            // for each edge
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
	
        var boundarydata = new Array();
	var xxxx = "(-112.840576171875, 33.47910488998688), (-110.577392578125, 32.65972558453054), (-110.511474609375, 34.14545330281532), (-112.159423828125, 34.21816160444031), (-98.63525390625, 38.84313065600374), (-99.755859375, 37.77418860603272), (-96.78955078125, 37.878322362688515), (-97.0751953125, 38.654630424434)";
	var vvvv = xxxx.split("), (");
	var i = 0;
	$.each(vvvv, function(){
		var xxxxxxx = vvvv[i].replace("(", "");
		xxxxxxx = xxxxxxx.replace(")", "");
		latlong = xxxxxxx.trim().split(",");
        boundarydata[i] = new google.maps.LatLng(latlong[1], latlong[0]);
		i++;
		});

        boundaryPolygon = new google.maps.Polygon({
            path: boundarydata,
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: 'Red',
            fillOpacity: 0.4

        });
        var latitude = searchlat;
        var longitude = searchlong;
        var myPoint = new google.maps.LatLng(latitude, longitude);

        if (boundaryPolygon == null) {
            alert("Sorry! Delivery area not fixed");
        }
        else {

            if (boundaryPolygon.Contains(myPoint)) {
                alert(myPoint + "is inside the polygon.");
            } else {
                alert(myPoint + "is outside the polygon.");
            }
        }
    }
    </script>
  </head>
  <body>
    <div id="panel" style="margin-left: -260px">
      <input id="searchTextField" type="text" size="50">
    </div>
    <div id="map-canvas" style="display: none;"></div>
  </body>
</html>

