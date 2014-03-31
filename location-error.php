<?php
  /**
   * Location website installation Web URL not allow 
   *
   * @chicago 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
?>
<!doctype html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="assets/jquery.js"></script>
<script type="text/javascript" src="assets/ud//script.js"></script>
<title><?php echo $core->site_name;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="http://fonts.googleapis.com/css?family=BenchNine" rel="stylesheet" type="text/css" />
<link href="assets/ud/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div class="wrapper"> 
  <h1>This url not allow</h1>
  <div id="dashboard" class="row">
  </div>
  <div class="info-box"> An error occurred on the server when processing the URL. Please contact the system administrator </div>
</div>
</div>
<?php 
  $v = str_replace(" ",":",$core->offline_data); 
  $d = explode(":",$v);
?>
<script type="text/javascript">
$(document).ready(function () {
	$('#dashboard').countDown({
		targetDate: {
			'day': <?php echo $d[2];?>,
			'month': <?php echo $d[1];?>,
			'year': <?php echo $d[0];?>,
			'hour': <?php echo $d[3];?>,
			'min': <?php echo $d[4];?>,
			'sec': 0
		}
	});
});
</script>
</body>
</html>