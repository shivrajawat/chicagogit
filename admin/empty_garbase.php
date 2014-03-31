<?php
  /**
   * Posts
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: posts.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Empty Garbase")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php //include("help/posts.php");?>
<?php switch($core->action): case "edit": ?>
<?php break;?>
<?php default: 

 ?>
<?php //$content->sendSMSOnDelivery();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _SMS_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span></span><?php echo _SMS_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _SMS_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div>
            <a class="button-sml sendsms" id="emptygarbase"><button onclick="onclick=myFunction()" class="button-orange">Empty Garbase</button></a>
             </div>
           </td>
           <td><p id="demo"><?php if(isset($_SESSION['thanks'])&& !empty($_SESSION['thanks']))
           {
	             echo $_SESSION['thanks'];
	               unset($_SESSION['thanks']);
	} ?>
    </p></td>
          </tr>
        </tfoot>
        <tbody>
        </tbody>
      </table>
  </div>
</div>


<?php break;?>
<?php endswitch;?>
<div id="dialog-confirm" title="Empty All Garbase Data?" style="display:none; width:auto">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Do you want to Empty  to all Garbase Data?</p>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {	
	
 $("a#emptygarbase").click(function(){
	 $(function() {
$( "#dialog-confirm" ).dialog({
resizable: false,
height:140,
modal: true,
buttons: {
"Empty Garbase Data": function() {
		$.ajax({
			type:"POST",
			url:"ajax.php",
			data:{processemptygarbase:"1"},
			success: function(theResponse){
				window.location.href= "";			
				}
		});
$( this ).dialog( "close" );
},
Cancel: function() {
$( this ).dialog( "close" );
}
}
});
});
		
	});	});
</script>