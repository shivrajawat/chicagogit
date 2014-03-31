<?php
  /**
   * Index
   * Kula cart 
   *  
   */
    if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $location = $menu->locationIdByMenu($websitenmae);?>

<div class="row-fluid margin-top">
  <div class="span12">
    <!-----MENU CATEGORIES----->
    <div class="span3">
      <h3 class="title h3">MENU CATEGORIES</h3>
      <ul class="nav">
        <?php $category = $menu->categotyfront($location['location']);
			if($category):
			
			foreach($category as $crow): $catname = str_replace(" ","-",$crow['category_name']);?>
        <li><a href="#category-<?php echo $crow['id'].'-'. $catname; ?>" class="category" rel="<?php echo $crow['id']; ?>"><?php echo $crow['category_name'];?></a> </li>
        <?php endforeach; endif;?>
      </ul>
    </div>
    <!-----MENU CATEGORIES END----->
    <!-----Product Details----->
    <div class="span6 box-shadow fit">
      <div class="span12 padding-outer-box" id="productview">
        <!-------------------------------Product Show By category use jquey---------------------------------- -->
        <img src="images/back.png" alt="" /> </div>
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php include("rightside.php");?>
    <!-----RIGHT END----->
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<div style="display: none;" id="smallLoader">
  <div>
    <div> </div>
  </div>
</div>
<script type="text/javascript">
var clickhash='';
$(document).ready(function () {

 			$.history.init(pageload);
//			$('a[href=' + window.location.hash + ']').addClass('active');		
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
   
   			
  });
  
  function pageload(hash) {
		if (hash) 
		{
			var myString = hash.substr(hash.indexOf("/") + 1)  
			var streetaddress= myString.substr(0, myString.indexOf('-')); 
			if(streetaddress=='category')
			{				
				getPage(hash.split('-')[1]);  
			}
			else if(streetaddress=='product')
			{
				$("#smallLoader").css({display: "block"});
					getItem(myString.split('-')[1]);
			}
	
				
		}
		else 
		{
			var defaultcatID =  $('ul.nav li:first a').attr('rel');
			getPage(defaultcatID);    
			$('ul.nav li:first a').addClass('active');
		}
	}
		
	function getPage(cat_id) 
	{
//	alert(cat_id);
		var data = 'categoryid=' + cat_id;
		$.ajax({
			url: "<?php echo SITEURL;?>/ajax/productlist.php",	
			type: "GET",		
			data: data,		
			cache: false,
			success: function (html) {	
				$("#smallLoader").css({display: "none"});				
				$('#productview').html(html);		
			}		
		});
	}
	function getItem(itemid) 
	{
		var data = 'Item=' + itemid;		
					$.ajax({
						url: "<?php echo SITEURL;?>/ajax/toppingbyproduct.php",	
						type: "GET",		
						data: data,		
						cache: false,
						success: function (html) {	
							$("#smallLoader").css({display: "none"});
						   $('#productview').html(html);   		
						}		
					});
	}
	</script>
