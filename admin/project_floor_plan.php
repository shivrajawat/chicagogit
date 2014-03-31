<?php
  /**
   * Users
   *
   * @package  
   * @author  
   * @copyright 2010
   * @version $Id: users.php, 
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
switch($core->action): 
case "edit":
$row = $core->getRowByIdNew("project_logo" , "id" , $core->updateid);
?>
<form class="form-horizontal seperator" name="eventform" id="eventform" method="post" action="" enctype="multipart/form-data">
    <div class="form-row row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="image">Project Logo: </label>
                <?php if(is_file("../uploads/project_logo/" . $row['project_logo'])):?>
                	<img src="../uploads/project_logo/<?php echo $row['project_logo'];?>" alt="" class="image marginR10" width="50" height="50" /><?php endif;?>
                <input type="file" name="fileinput" id="file"/>
            </div>
        </div>
    </div>
        
    <div class="form-row row-fluid">        
        <div class="span12">
            <div class="row-fluid">
                <div class="form-actions">
                <div class="span3"></div>
                <div class="span4 controls">
                    <input type="submit" name="dosubmit" id="dosubmit" class="btn btn-info marginR10 nostyle" value="Save Changes"/>
                    <input type="reset" name="Cancel" id="Cancel" class="btn btn-danger nostyle" value="Cancel"/>
                    <input type="hidden" name="updateid" id="updateid" value="<?php echo $core->updateid;?>" />	
                    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project->projectid;?>" />	
                </div>
                </div>
            </div>
        </div>   
    </div>
</form>
<?php 
echo $core->doFormNew("processProjectCustomLogo", "eventform");
break;


case"add":
?>
<form class="form-horizontal seperator" name="eventform" id="eventform" method="post" action="" enctype="multipart/form-data">
    
    <div class="form-row row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="image">Project Logo: </label>
                  <input type="file" name="fileinput" id="file"/>
            </div>
        </div>
    </div>
        
    <div class="form-row row-fluid">        
        <div class="span12">
            <div class="row-fluid">
                <div class="form-actions">
                <div class="span3"></div>
                <div class="span4 controls">
                    <input type="submit" name="dosubmit" id="dosubmit" class="btn btn-info marginR10 nostyle" value="Save"/>
                    <input type="reset" name="Cancel" id="Cancel" class="btn btn-danger nostyle" value="Reset"/>
                    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project->projectid;?>" />	
                </div>
                </div>
            </div>
        </div>   
    </div>
</form>
<?php 
echo $core->doFormNew("processProjectCustomLogo", "eventform");
break;

default:	  
$maprow = $project->getProjectLogo($project->projectid); 
?>
<table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered display" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    <?php 
		if($maprow != 0):
		$i = 1;
		foreach($maprow as $row):
	?>
        <tr class="odd gradeX">
            <td><?php echo $i;?></td>
            <td>
            	<?php if(is_file("../uploads/project_logo/" . $row['project_logo'])){?>
            	<a href="<?php echo "../uploads/project_logo/" . $row['project_logo'];?>" title="Click to Large view" target="_blank">
            	<img src="../uploads/project_logo/<?php echo $row['project_logo'];?>" alt="<?php echo $row['project_logo'];?>" title="<?php echo $row['project_logo'];?>" width="80" height="80" />
                </a>
                <?php }else echo "No image";?>
            </td>
            <td class="center">
            	<a href="customize.php?do=project_custom_logo&amp;projectid=<?php echo $row['project_id'];?>&amp;action=edit&amp;updateid=<?php echo $row['id'];?>" 
                			title="Click to update"><img src="images/edit.png" alt="Update" title="<?php echo _UR_EDIT;?>" /></a>
            </td>
            <td class="center">
            	<a href="javascript:void(0);" class="delete" rel="<?php echo $row['project_logo'];?>" id="item_<?php echo $row['id'];?>">
                 <img src="images/delete.png" alt="Delete" /></a>
            </td>
        </tr>
    <?php 
		$i++;
		endforeach;
		endif;
	?>
    </tbody>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </tfoot>
</table>
<div id="dialog-confirm" style="display:none;" title="Delete Logo">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to delete this record?<br /><strong>This action cannot be undone!!!</strong></p>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $('a.delete').live('click', function () {
        var id = $(this).attr('id').replace('item_', '')
        var parent = $(this).parent().parent();
		var title = $(this).attr('rel');
        $("#dialog-confirm").data({
            'delid': id,
            'parent': parent,
			'title': title
        }).dialog('open');
        return false;
    });

    $("#dialog-confirm").dialog({
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        width: 400,
        height: "auto",
        zindex: 9998,
        modal: false,
        buttons: {
            'Delete': function () {
                var parent = $(this).data('parent');
                var id = $(this).data('delid');
				var title = $(this).data('title');

                $.ajax({
                    type: 'post',
                    url: "ajax.php",
                    data: 'deleteProjectLogo=' + id + '&LogoImage=' + title,
                    beforeSend: function () {
                        parent.animate({
                            'backgroundColor': '#FFBFBF'
                        }, 400);
                    },
                    success: function (msg) {
                        parent.fadeOut(400, function () {
                            parent.remove();
                        });
						$("html, body").animate({scrollTop:0}, 600);
						$("#msgholder").html(msg);
                    }
                });

                $(this).dialog('close');
            },
            'Cancel': function () {
                $(this).dialog('close');
            }
        }
    });
});
// ]]>
</script>
<?php 
break;
endswitch;
?>