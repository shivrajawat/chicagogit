<?php
  /**
   * Logout
   */
  define("_VALID_PHP", true);
  
  require_once("init.php"); 
?>
<?php
$ordernotification = $menu->ordernotificationemail();

	foreach($ordernotification as $onrow)
	{
		if($onrow['postcount']=='0')
		{	
			$date1timestamp =strtotime(date("Y-m-d h:i:s", time()));  
			$date2timestamp = strtotime($onrow['created_date']);
			if($date1timestamp>$date2timestamp)
			{
				$difference =  date_difference($date1timestamp,$date2timestamp);
			 	$mins =  $difference['mins']; 
				$hours =  $difference['hours'];
					if($hours==0)
					{
						if($mins>=3 && $mins<=5)
						{			
							$sqlsupportemail = "SELECT `support_email1`,`support_email2` ,`support_email3` ,`support_email4`"
							. "\n  FROM `res_location_setting`"
							. "\n WHERE `location_id` = '".$onrow['location_id']."'";
							$rowsupportmail = $db->first($sqlsupportemail);	
									
							$allmailid = "";				
							if(!empty($rowsupportmail['support_email1']))
							{
								$allmailid .= $rowsupportmail['support_email1'];
							}
							if(!empty($rowsupportmail['support_email2']))
							{
								$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email2'] : $rowsupportmail['support_email2'];
							}
							if(!empty($rowsupportmail['support_email3']))
							{
								$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email3'] : $rowsupportmail['support_email3'];
							}
							if(!empty($rowsupportmail['support_email4']))
							{
								$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email4'] : $rowsupportmail['support_email4'];
							}
							$support_email =  $allmailid; 
							$mailto = $support_email;
							$replyto = "";
							$from_name = $core->site_email;
							$mailSubject = "An Order Failed at Chicago Connection";
							
							$filenamepath = UPLOADURL."orderxml/".$onrow['posorderxml'];
							$mailBody = "An order has been failed. XML of order is <a href='".$filenamepath."'>attached here</a>";	
							
							// excute mail send function 
							sendemail($mailto,$mailBody,$mailSubject);
							//Postcount Upddate 1 order master  				
						
						  $data['postcount'] = 1;
						  $db->update("res_order_master",$data,"created_date = '".$onrow['created_date']."'");
						}
					}
			}		    
	
		} 
	}
	
	function sendemail($to,$msg,$subject)
	{
			    $to = $to;
			    $sub  = $subject;
			    $enqMessage = $msg;
				$headers = "From: kulacart\r\n"; 
				$headers .= "MIME-Version: 1.0\r\n"; 
				$boundary = uniqid("HTMLEMAIL"); 
					  
				$headers .= "Content-Type: multipart/alternative;".
								"boundary = $boundary\r\n\r\n"; 
				
				$headers .= "This is a MIME encoded message.\r\n\r\n"; 
				
				 
				
				$headers .= "--$boundary\r\n".
							"Content-Type: text/html; charset=ISO-8859-1\r\n".
							"Content-Transfer-Encoding: base64\r\n\r\n"; 
								
				$headers .= chunk_split(base64_encode($enqMessage)); 					
			
				 mail( $to, "Subject: $sub","", $headers );
	}
?>