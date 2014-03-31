<div class="row-fluid margin-top">
  <div class="span12">
    <div class="span12  fit">
    <div class="span12 box-shadow">
    <div class="span12 fit">
			<?php
                     $arr = $content->getTermconditionPage('Shipping Terms');
                     if($arr!=0){
            ?>
          <div class="heading-top"><?php echo $arr['page_title']; ?></div>
        </div>
        <div class="span12 padding-outer-box">
          <?php		     
			    echo ($arr['description']) ? cleanOut($arr['description']) : "";
		    }
		  ?>
        </div>
        <div class="span12 fit btn_back_next">
            <div class="span6 fit">
              <div><a href="javascript:goback()"><img src="<?php echo THEMEURL;?>/images/btn_back.png" alt="Back"/></a></div>
            </div>            
            <div class="clr"></div>
          </div>
           <br />
    </div>
      
    </div>
    <!-----Product Details END----->
    <!-----RIGHT SEACTION----->
    <?php //include("rightside.php");?>
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