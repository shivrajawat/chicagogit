<?php
  /**
   * Index
   * Kula cart 
   *  
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php 

   $customer_id = $_SESSION['cid'];
  
   $custmer_row = $customers->customerOrderDetails($customer_id);
?>
<script src="<?php echo THEMEURL;?>/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo THEMEURL;?>/css/jquery-ui.css" />
<script src="<?php echo THEMEURL;?>/js/jquery.popupoverlay.js"></script>

<div class="row-fluid top_links_strip">
  <div class="span12">
    <!-- <div class="span4 fit"></div>-->
    <?php include("welcome.php");?>
    <div class="span5">
      <div class="row-fluid">
        <div class="span12 fit" style="text-align:right">
          <div id="breadcrumbs"> <a href="<?php echo SITEURL; ?>">Online Ordering Home</a> <span class="raquo">&raquo;</span> Order History </div>
        </div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
  <div class="container">
<div class="row-fluid margin-top padding-top-10 padding-bottom-10">
  <div class="span12">
    <!-----View Cart Details----->
    <div class="span12 fit">
        <div class="row-fluid">
        <div class="span12 top_heading_strip">Order History</div>
        </div>  
        <div class="span12 fit">
        
        <?php
		     if($custmer_row == 0):	
			 		 
			  	print  "<div class ='row-fluid'><span style=\"margin-left:306px\">There is no orders in order history<span></div>";	
						 
			 else:		
		
		?>
        <table class="table table-bordered product-table-border table-bordered1" border="0" style="border:none !important; margin-top:20px;">
        <thead>
          <tr class="product-des-name">
            <th>Order No</th>
            <th>Date</th>
            <th>Time</th>
            <th>Order Type</th>
            <th>Total Amount</th>
            <th>View detail</th>
            <th>Reorder</th>
          </tr>
        </thead>
        <tbody>
		<?php	 
			   foreach($custmer_row as $row):	
			   		   
			    	$order_row = $content->GetOrderDetailsForReorder($row['orderid'],$row['order_number']);
			  
		 ?>
          <tr>
            <td><?php echo ($row['order_number'])? $row['order_number'] : "";   ?></td>
            <td><?php echo $date =  date("m/d/Y", strtotime($row['pickup_date']));?></td>
            <td><?php echo $row['pickup_time'];?></td>
            <td>  
				<?php
                 if($row['order_type']=='p'){ echo "Pick Up";}       //PickUp 
                 if($row['order_type']=='d'){ echo "Delivery";}      //Delivery 
                 if($row['order_type']=='dl'){ echo "Dine In";}      //Dine In 
              ?>
          </td>
            <td><?php echo ($row['net_amount'])? '$'.$row['net_amount'] : "";   ?></td>
            <td style="background-color:005936;"><a href="<?php echo SITEURL."/order-details/".$row['orderid']; ?>"><img src="<?php echo THEMEURL;?>/images/btn_view_details.png"  alt="view Details" /></a></td>
            <!--<td>            
            	<a href="javascript:void(0);" onClick="javascript:FlyToBasket('<?php //echo $row['orderid'];?>'); " class="reorder" title="Click To Re-Order" rel="<?php //echo $row['orderid'];?>" id="item_<?php //echo $row['orderid'];?>"><img src="<?php //echo THEMEURL;?>/images/btn_reorder.png" alt="reorder" />
                </a>
            </td>--> 
            <td>            
               <a href="#" class="my_modal_open orderIdValue" title="Click To Re-Order" rel="<?php echo $row['orderid'];?>" id="<?php echo $row['orderid'];?>">
                	<img src="<?php echo THEMEURL;?>/images/btn_reorder.png" alt="reorder" />
               </a>
            </td>          
          </tr>
         <?php endforeach; endif;   ?>
        </tbody>
      </table>
      </div>
      
    </div>    
    <div class="clr"></div>
    <div class="span12 fit btn_back_next">
        <div class="span10"></div>
            <div class="span2">
              <div>
              	<a href="javascript:goback()" title="Back" class="btn-2-2">
                	BACK
                	<!--<img src="<?php //echo THEMEURL;?>/images/btn_back.png" alt="Back"/>-->
                </a>
              </div>
            </div>            
        <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
</div>
<!-- Add content to modal -->
  <div id="my_modal" style="display:none"> 
  	 <div class="dialog_heading">Reorder </div> 	
     <p>
     	<span style="float: left; margin: 0 7px 20px 0;"></span>
        You can quickly place this exact same order again by clicking on the Reorder button below.<br/>
        This will add the same items to your shopping cart.
     </p> 
     <a id="d_address1" title="Reorder" class="reorder bold" style="cursor:pointer">Reorder</a>  
     <a title="Close" class="my_modal_close bold" style="cursor:pointer">Close</a>
  </div>
<!-- Add content to modal -->


<script type="text/javascript">
	$(function() {
		
		$(".delete").click(function() {
			
			$('#load').fadeIn();
			
			var commentContainer = $(this).parent().parent();
			var id = $(this).attr("id");
			var string = 'id='+ id ;
			
			$.ajax({				
			   type: "POST",
			   url: "<?php echo SITEURL;?>/ajax/user.php",
			   data: 'deleteInBasket=' + id,
			   cache: false,
			   success: function(){
					commentContainer.slideUp('slow', function() {$(this).remove();});
					$('#load').fadeOut();
			   }			   
			});
		  return false;
		});
	});
</script>
<script type="text/javascript">
  
 $(document).ready(function() {
		
	 $(".orderIdValue").click(function(){		
		
			del_val = $(this).attr("id");
			
			var element1 = document.getElementById('d_address1');
			
			element1.setAttribute("name", del_val); 		
		
	 }); 
		
		
	$("a.reorder").click(function(){	
	
			$('#load').fadeIn();
			
			var order_id = $("#d_address1").attr("name");	
			
			$("#smallLoader").css({display: "block"});	
			$("#my_modal").hide();
			
			$.ajax({
				type: "POST",
				url: "<?php echo SITEURL;?>/ajax/user.php",
				data: "FlyToBasket=1&order_id="+order_id,
				cache: false,
				success: function (res) {
					$('#load').fadeOut();
					window.location.href = SITEURL+'/view-cart';
				}
			});
		}); 
	});

	$(function() {
		  $('#my_modal').popup();
	});
</script>

