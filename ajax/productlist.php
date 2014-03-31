<?php
  /**
   * User
   *
   * Kulacart product list By category 
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
if(!empty($_GET['categoryid'])):
		 
		 $cat = $menu->categoryName($_GET['categoryid']);
		 $orerdady   =  getDayfullname($_GET['availabledays']);
		 $ordertime =  date('h:i:s A', strtotime($_GET['ordertime']));
		 $availabilityTime =  $menu->OrderAvailabilityTime($_GET['locationid'],$orerdady,$ordertime);
		 $availabledays = $_GET['availabledays'];
		 $category = $menu->productByCatWithchild($_GET['categoryid']);
		 
		 if($category):	
		 
			foreach($category as $crow): 
			if(isset($crow['category_image']) && $crow['category_image']!='' && is_file("../uploads/category_images/". $crow['category_image'])){
				
				echo '<img src="'.SITEURL.'/uploads/category_images/'.$crow['category_image'].'" height="245" width="458" alt="'.$crow['category_name'].'" />'; 
			}			
			$catname = str_replace(" ","-",$crow['category_name']);
		?> 
        <h4 class="product-cat"><?php echo $crow['category_name'];?></h4> 
         <?php 
		 	if(isset($crow['category_dec']) && !empty($crow['category_dec'])){
				echo '<p class="category_desc">'.cleanOut($crow['category_dec']).'</p>';
			}  
		?>       
        <div class="row-fluid">
        <?php  
	   
			   // $product = $menu->productnameByCat($crow['id']);	
			   $havemenu = $menu->have_time_menu($_GET['locationid'],$orerdady);
				$product = $menu->productnameByCat($crow['id'],$availabledays,$availabilityTime,$havemenu['have_time_menu']); 				
				if($product):
				
				$sizedurl = "";
				foreach($product as $prow):
					 if($prow['sizedid']!=0){	$sizedurl = "/sizeid-".$prow['sizedid']."";	 }  else {	$sizedurl = "";	 }
		 ?>
          <div class="span12 fit">
            <div class="span12 product-listing">
              <h4><a href="#category-<?php echo $crow['id']."-". $catname."/product-". $prow['id'].$sizedurl;?>" id="clickingEvent" class="itemtopping" rel="<?php echo $prow['id'];?>"><?php echo $prow['item_name'];?></a>
               <?php 
			  if($prow['special_menu_icon'])
			  {
			  	$SpecialMenuIcon = $menu->SpecialMenuIcon($prow['special_menu_icon']);
				
					if($SpecialMenuIcon)
					{
						foreach($SpecialMenuIcon as $smirow)
						{?>
							<img src="<?php echo SITEURL.'/uploads/menu_icon/'.$smirow['special_item_icon'];?>" rel="tooltip" title="<?php echo $smirow['special_item_name'];?>" />
						<?php }
					}
			  	 }
			   ?> 
              </h4>
              <p><?php echo $prow['item_description'];?></p>
            </div>            
            <div class="clr"></div>
          </div>   
          <?php endforeach; else: 
		  	//echo "Have no item under this category";
		 endif;?>       
        </div>
        <?php endforeach; endif;?>
<?php 

endif;
?>
<?php 
if(!empty($_GET['OnloadProduct']))
{ 
			 $cat = $menu->categoryName($_GET['categoryid']);
			 $orerdady   =  getDayfullname($_GET['availabledays']);
			 $ordertime =  date('h:i:s A', strtotime($_GET['ordertime']));
			 $availabilityTime =  $menu->OrderAvailabilityTime($_GET['locationid'],$orerdady,$ordertime);
 			 $availabledays = $_GET['availabledays'];
			$category = $menu->categotyfront($_GET['categoryid']);
			if($category):			
			foreach($category as $crow): $catname = str_replace(" ","-",$crow['category_name']);
?> 
        <h4 class="product-cat"><?php echo $crow['category_name'];?></h4> 
         <?php 
		 	if(isset($crow['category_dec']) && !empty($crow['category_dec'])){
				echo '<p class="category_desc">'.cleanOut($crow['category_dec']).'</p>';
			}  
		?>       
        <div class="row-fluid">
      <?php   
	  $product = $menu->productnameByCat($crow['id'],$availabledays,$availabilityTime);	   
	  if($product):
			$sizedurl = "";
			foreach($product as $prow):
				 if($prow['sizedid']!=0){ $sizedurl = "/sizeid-".$prow['sizedid'].""; }  else {	$sizedurl = "";	 }
	  ?>
          <div class="span12 fit">
            <div class="span12 product-listing">
              <h4>
              <a href="#category-<?php echo $crow['id']."-". $catname."/product-". $prow['id'].$sizedurl;?>" id="clickingEvent" class="itemtopping" rel="<?php echo $prow['id'];?>"><?php echo $prow['item_name'];?></a>
              <?php 
			  if($prow['special_menu_icon'])
			  {
			  	$SpecialMenuIcon = $menu->SpecialMenuIcon($prow['special_menu_icon']);
				
					if($SpecialMenuIcon)
					{
						foreach($SpecialMenuIcon as $smirow)
						{?>
							<img src="<?php echo SITEURL.'/uploads/menu_icon/'.$smirow['special_item_icon'];?>" rel="tooltip" title="<?php echo $smirow['special_item_name'];?>" />
						<?php }
					}
			  	 }
			  ?>    
              </h4>
              <p><?php echo $prow['item_description'];?></p>
            </div>            
            <div class="clr"></div>
          </div>   
          <?php endforeach; else: 
		  	// echo "have No item Under this category";
		 endif;?>       
        </div>
        <?php endforeach; endif;?>
<?php }
?>