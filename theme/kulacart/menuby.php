<?php
/**
* Index
* Kula cart 
*  
*/
if (!defined("_VALID_PHP"))
die('Direct access to this location is not allowed.');

if(isset($_SESSION['chooseAddress']))
{
	$locationid = $_SESSION['chooseAddress'];
	$ordertime =  date('h:i:s A', strtotime($_SESSION['orderHour'])); 
	$availabledays = date('D', strtotime($_SESSION['orderDate']));
}
else
{
	$locationrow = $menu->locationIdByMenu($websitenmae);
	$locationid = $locationrow['location'];
	
	// time and date according UTC time
		$TimeZone = $product->TimeZone($locationid);
		$addhour  = 60*60*($TimeZone['hour_diff']);
		$addmin = 60*$TimeZone['minute_diff'];
		$daylight = 60*60*$TimeZone['daylight_saving'];
		$datetime = date('m/d/Y H:i:s',(time()+($addhour+$addmin+$daylight)));
		$date =   date('m/d/Y',strtotime($datetime));
								
		$hour = date('g:i A', strtotime($datetime));
		
		$ordertime =  date('h:i:s A', strtotime($hour));
		$availabledays = date('D', strtotime($date));
}
?>

<script src="<?php echo THEMEURL;?>/js/highlight.pack.js"></script>
<script src="<?php echo THEMEURL;?>/js/fixture.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL;?>/js/jquery.navgoco.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo THEMEURL;?>/css/jquery.navgoco.css" media="screen" />
<script type="text/javascript">
 $(function(){
    // this will get the full URL at the address bar
    var url = window.location.href; 

    // passes on every "a" tag 
    $("#myaccordion a").each(function() {
            // checks if its the same on the address bar
        if(url == (this.href)) { 
            $(this).closest("li").addClass("active");
        }
    });
}); 
 
</script>
<script type="text/javascript" id="demo2-javascript">
$(document).ready(function() {
$('ul.accnav li:first').addClass('open');
	$("#demo2").navgoco({accordion: true, 
	onClickAfter: function(e, submenu) {
            e.preventDefault();
            $('#demo2').find('li').removeClass('active');
                var li =  $(this).parent();
                var lis = li.parents('li');
                li.addClass('active');
                lis.addClass('active');
				var loc = $(this).attr("href");
	   window.location = loc;
        }});
});
</script>
<div class="row-fluid top_links_strip">
  <div class="span12">
    <!--    <div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Online Menu </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="container">
<div class="row-fluid margin-top">
 <div class="span12 padding-top-10 padding-bottom-10 relative" id="content-right-bg">
<div class="span9 fit">
<div class="row-fluid">
  <div class="span12 top_heading_strip"> Online Menu </div>
</div>
<div class="row-fluid">
<div class="span4">
<div class="bs-docs-example">
<ul id="myTab" class="nav nav-tabs">
  <li class="active tab-menu-a"><a href="#home" data-toggle="tab" >MENU</a></li>
  <li class="active tab-menu-a"></li>
</ul>
<div id="demo2-html">
  <?php $menurow = $menu->CategoryMenuSubMenu($locationid);
			if($menurow != 0)
			{
				$str = "<ul id=\"demo2\" class=\"accnav\">";
				$c = 0;
			    foreach($menurow as $row)
				{									
					if($row['id'] != 0)
					{					
						$subcat = $menu->getSubCatTree($row['id']);
						if($subcat != 0)
						{
							$str .= "<li>";
							$str .= "<a class=\"accordion-toggle\" rel=\"". $row['id']."\" href=\"#category-" . $row['id'] . "-" . paranoia($row['menu'])."\">";
							$str .= ucfirst($row['menu']);
							$str .= "</a>";
							$str .= "";
							$str .= "<ul>";
							//$sr = 0;
							foreach($subcat as $srow)
							{	
                                $subsubcat = $menu->getSubCatTree($srow['id']);
								
								if($subsubcat)
								{
								
									$str .= "<li><a class=\"accordion-toggle color_011810\" href=\"#category-" . $srow['id'] . "-" . paranoia($srow['category_name'])."\">" .ucfirst($srow['category_name']). "</a>";
									$str .= "<ul>";
									foreach($subsubcat as $row3)
									{
										
										$str .= "<li><a class=\"color_011810\" href=\"#category-" . $row3['id'] . "-" . paranoia($row3['category_name'])."\" class=\"link\">" .ucfirst($row3['category_name']). "</a></li>";
									}
									$str .= "</ul>";
									$str .= "</li>";
								}
								else
								{
                                	$str .= "<li><a class=\"color_011810\" href=\"#category-" . $srow['id'] . "-" . paranoia($srow['category_name'])."\">" . $srow['category_name'] . "</a></li>";
								}
							
							}
							$str .= "</ul>";
							$str .= "";
							$str .= "</li>";
						}
						else
						{
								$str .= "<li><a href=\"#category-" . $row['id'] . "-" . paranoia($row['menu'])."\">" . ucfirst($row['menu']) . "</a></li>";														
						}
					}
					$c++;			 
				}
				$str .= "</ul>"; 
			}
			echo $str;
			?>
</div>

        </div>
      </div>
      <div class="span8" >
        <div class="span12" id="productview"></div>
      </div>
      </div>
      </div>
      <!-----RIGHT SEACTION----->
    <?php include("rightside.php");?>
    <!-----RIGHT END----->
       <div id="content-top-shadow"></div>
          <div id="content-bottom-shadow"></div>
          <div id="content-widget-light"></div>
      <div class="clr"></div>
    </div>
  </div>
  </div>
<script type="text/javascript">
var clickhash='';
$(document).ready(function () {

 			$.history.init(pageload);
            //$('a[href=' + window.location.hash + ']').addClass('active');		
			$('a.category').click(function () {			
				$('a.category').removeClass('active');
				$(this).addClass('active');
				var hash = this.href;
				hash = hash.replace(/^.*#/, '');
				$.history.load(hash);	
				//clickhash=hash;		
				$("#smallLoader").css({display: "block"});				
				//getPage($(this).attr('rel'));
				return false;
   });
   
  // $("a.accordion-toggle").click(function(){
//	   var loc = $(this).attr("href");
//	   window.location = loc;
//	   });   			
  });
  
  function pageload(hash) {  
  $("#smallLoader").css({display: "block"});	
  var cartrowid = "";
		if (hash) 
		{			
			var myString = hash.substr(hash.indexOf("/") + 1);
			var sized = myString.substr(myString.indexOf("/") + 1);			
			var streetaddress= myString.substr(0, myString.indexOf('-'));			
			var sizedid= sized.substr(0, sized.indexOf('-'));
						
			if(myString =='all')
			{
				getallproduct();
			}
			if(streetaddress=='category')
			{				
				getPage(hash.split('-')[1]);  
			}
			else if(streetaddress=='product')
			{
				 $("#smallLoader").css({display: "block"});
				 var urlsplitter = myString.split("-");
				 var str = urlsplitter[1];
				 var res = str.split("/");
				 var itemid = res[0];
				 var sizeurlsplitter = sized.split("-");				 
				
					if(sizeurlsplitter.length > 2)
					{
						cartrowid = sizeurlsplitter[2];
						sizeid = sizeurlsplitter[1];
					}
					else if(sizeurlsplitter.length > 1)
					{
						sizeid = sizeurlsplitter[1];
						cartrowid = hash.split('-')[1];
					}
					else
					{
						cartrowid = "NONE";
						sizeid = "NONE";
					}
					getItem(itemid,cartrowid,sizeid);
			}				
		}
		else 
		{
			var defaultcatID = $("#demo2").find('a:first').attr('rel'); 
			getPage(defaultcatID);    
			$('div.accordion-heading li:first a.firstshow').addClass('active');
			//defaultcatID =  '<?php //echo $locationid;?>';
			
		/*	var data = 'categoryid=' + defaultcatID;
			$.ajax({
			url: SITEURL+"/ajax/productlist.php",	
			type: "GET",		
			data: data,		
			cache: false,
			async: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});*/
		}
	}
		
	function getallproduct(){ 
		    
			var data = 'OnloadProduct= 1' +'categoryid=' + cat_id  + '&ordertime=' + encodeURIComponent('<?php echo $ordertime; ?>') + '&availabledays=' + '<?php echo $availabledays; ?>' + '&locationid=' + <?php echo $locationid;?>;
			
			$.ajax({
			url: SITEURL+"/ajax/productlist.php",	
			type: "GET",		
			data: data,		
			cache: false,
			//async: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});
	}
	
	function getPage(cat_id){ 
	        
		var data = 'categoryid=' + cat_id  + '&ordertime=' + encodeURIComponent('<?php echo $ordertime; ?>') + '&availabledays=' + '<?php echo $availabledays; ?>' + '&locationid=' + <?php echo $locationid;?>;
		
		$.ajax({
			url: SITEURL+"/ajax/productlist.php",				
			type: "GET",		
			data: data,		
			cache: false,
			//async: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});
	}
	
	function getItem(itemid, cartrowid, sizeid){ 
	
		var data = 'Item=' + itemid + '&cartrowid=' + cartrowid + '&sizedid=' + sizeid + '&locationid=' + <?php echo $locationid;?>;
				
		$.ajax({
			url: SITEURL+"/ajax/toppingbyproduct.php",	
			type: "GET",		
			data: data,		
			cache: false,
			//async: false,
			success: function (html){
				
				$("#smallLoader").css({display: "none"});
			    $('#productview').html(html);   		
			}		
		});
	}  
  
</script>