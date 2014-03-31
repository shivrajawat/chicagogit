<?php include("header.php");?>
	<div data-role="content">
    <h1 class="main-heading">Hours</h1>
	<table >
      <tbody>        
        <?php 
		
			$timeRow = $content->getAllrestorantTiming(); 
		    
			if($timeRow ==0):
			
			else:
				foreach($timeRow AS $row):
		?>
        <tr><td><b><?php echo $row['location_name']; ?></b></td></tr>
        <tr><td><?php echo cleanOut($row['restorant_time']); ?></td></tr>
		<?php	
				endforeach;
			endif;		
		?>      
      </tbody>
    </table>
    
	</div><!-- /content -->
    <a href="index.php" data-role="button" data-ajax="false">Back to Home</a>
    </div>
<?php include("footer.php");?>
<script type="text/javascript">
	function goback() {
		history.go(-1);
	}
</script>