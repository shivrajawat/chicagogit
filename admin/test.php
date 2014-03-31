<?php
  /**
   * Users
   *
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Users")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php if (isset($_POST['location_create']))
				{
				
				print_r($_POST); exit();
				}
?>
<style>
html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#map_canvas {
  height: 100%;
}
.map_holder {
    border: 1px solid #CCCCCC;
    height: 280px;
    margin: 0 0 10px;
    padding: 10px;
    width: 710px;
}

@media print {
  html, body {
    height: auto;
  }

  #map_canvas {
    height: 650px;
  }
}

</style>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing"></script>   
<script type="text/javascript" src="js/map/googlemaps.js"></script>
<script type="text/javascript" src="plugins/validate/js/jquery.validate.min.js"></script>
<form action="" method="post" id="frmCreateLocation" class="frmLocation">
				<input type="hidden" name="location_create" value="1" />
				<input type="hidden" name="lat" id="lat" value="" />
				<input type="hidden" name="lng" id="lng" value="" />
				<p><label class="title">location_name</label><input type="text" name="name" id="name" class="text w400 required" /></p>
				<p style="position: relative">
					<label class="title">location_address</label><input type="text" name="address" id="address" class="text w400 required" />
					<a href="#" class="btnGetCoords" style="position: absolute;"><img src="images/search.png" class="align_middle" alt="" /></a>
				</p>
				<p id="map_error" style="display: none">
					<label class="title">&nbsp;</label>
					<span class="left red"><?php echo $FD_LANG['location_not_found']; ?></span>
				</p>                
				<div class="map_holder">
					 <div id="map_canvas"></div>
				</div>
				<p>
					<input type="submit" value="" class="button button_save align_middle" />
					<input type="button" value="" class="align_middle btnDeleteShape" disabled="disabled" />
				</p>
			</form>