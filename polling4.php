<?php

    define("_VALID_PHP", true);
    require_once("init.php");
	$em_body='';
    $request='';
    $result_array =array();
	$OrderUrl = SITEURL.'/uploads/orderxml/';
	$locationid = 4; //Location Id is asssigned here as 4

		// Call to function here
if ( $_SERVER["REQUEST_METHOD"] === "POST" &&
       $_SERVER["CONTENT_TYPE"] === "text/xml" )
		 {
			parsingXml();
		 }
		function parsingXml()
			{
				$locationid = 4;
				$errorCode='';
				$errorMsg='';
				$xmlText = trim(file_get_contents('php://input'));
				$xmlData = simplexml_load_string($xmlText);
  			    $em_body="<br>input stream: <br>";
  			    $em_body="<br>". $xmlData. " <br>";
				
				if(trim($xmlText)!='')
				{
					foreach($xmlData->children() as $child)
					   {
						   if(($child->getName())=='poll_action')
						   {
							   $action=$child;
						   }
						   if(($child->getName())=='user_name')
						   {
							   $username=$child;
						   }
						   if(($child->getName())=='password')
						   {
							   $password=$child;
						   }
					   }	   		

							$stringSeparators ="<online_rsp_code>";
							$rspArr =explode($stringSeparators,$xmlText);
						  if (count($rspArr) > 1)
							 {

								$rspCodeArr = explode('</online_rsp_code>',$rspArr[1]);
								if (count($rspCodeArr)> 1)
								{
									$errorCode = $rspCodeArr[0];
								}
				
								$rspMArr = explode('<online_rsp_msg>',$rspArr[1]);
								if (count($rspMArr) > 1)
								{
									$rspMsgArr = explode("</online_rsp_msg>",$rspMArr[1]);
									if (count($rspMsgArr) > 1)
									{
										$errorMsg = $rspMsgArr[0];
									}
								}
					   }
					  
						//Get Error Code
				  
					   
				}	  
				   
  			    $em_body="<br>poll_action: <br>";
				$em_body =$em_body.$action;
				$em_body =$em_body."<br>user_name: <br>";
				$em_body =$em_body.$username;
				$em_body =$em_body."<br>password: <br>";
				$em_body =$em_body.$password;
				$em_body =$em_body."<br>Error Code: <br>";
				$em_body =$em_body.$errorCode;
				
				//checking for action
				if($action=='request')
				{
					WriteToXml($username,$password);//calling method
				}
						
				if($action=='response')
				{
					global $db;
					$qryOrder = "SELECT * "
						. "\n FROM res_order_master"
						. "\n WHERE postcount !=1 and `location_id` = '".$locationid."' LIMIT 1";
									
			   			$row = $db->first($qryOrder);
							
							if ($errorCode != "0")
                				{
									$em_body =$em_body."<br>In Error Code";
									$confNo = rand(1, 999999999);
									$confNo = get_unic($confNo);
									$resultFlag='false';
									//Update postcount, errorcode & error msg if error comes during polling
									$data['postcount'] = 1;
									$data['errorcode'] = $errorCode;
									$data['errormsg'] = $errorMsg;

									$qryOrderUpdate = $db->update("res_order_master", $data, "postcount!=1 AND posorderxml='".$row['posorderxml']."'");
									
									$em_body =$em_body."<br>".$qryOrderUpdate;

									$em_body =$em_body."<br>Result Flag: ".$resultFlag;
									$xmlPath = str_replace(' ','',$row["posorderxml"]);
									$em_body =$em_body."<br>".$xmlPath;
									////Get OrderXMLInString
									$orderXmlString = getOrderXmlString($xmlPath);
									$em_body =$em_body."<br>" + $orderXmlString;
				
									//Get support email ids from database
									$locationid = $row['location_id'];
									$orderdatetime = $row['pickup_date']." / ". $row['pickup_time'];
									$sqlsupportemail = "SELECT `support_email1`,`support_email2` ,`support_email3` ,`support_email4`"
														. "\n  FROM `res_location_setting`"
														. "\n WHERE `location_id` = '".$locationid."'";
									$rowsupportmail = $db->first($sqlsupportemail);
									
									$allmailid = "";
									
									if(!empty($rowsupportmail['support_email1']))
									{
										//$support_email1 = $rowsupportmail['support_email1'];
										$allmailid .= $rowsupportmail['support_email1'];
									}
									if(!empty($rowsupportmail['support_email2']))
									{
										$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email2'] : $rowsupportmail['support_email2'];
										//$support_email2 = $rowsupportmail['support_email2'];
									}
									if(!empty($rowsupportmail['support_email3']))
									{
										$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email3'] : $rowsupportmail['support_email3'];
										//$support_email3 = $rowsupportmail['support_email3'];
									}
									if(!empty($rowsupportmail['support_email4']))
									{
										$allmailid .= ($allmailid) ? "," . $rowsupportmail['support_email4'] : $rowsupportmail['support_email4'];
										//$support_email4 = $rowsupportmail['support_email4'];
									}
									$support_email =  $allmailid; 
									
									////Send Email of order failure
									$to = $support_email;
									$mailSubject = "Order Failed at Chicago Connection";
									$mailBody = '<table>
													  <tr>
														<td colspan="2">An order has failed with the following details. Please see attached for the XML transmitted.</td></tr>
													   <tr><td>Order Date & Time:</td><td> '.$orderdatetime.'</td></tr>
														<tr><td>Error Code: </td><td>'.$errorCode.'</td></tr>
													   <tr><td>Error Message:</td><td>'.$errorMsg.'</td></tr>
													   <tr><td colspan="2"><a href="'.SITEURL."/uploads/orderxml/".$row['posorderxml'].'">Click here to check Order XML</a></td></tr>
													   </table>';
									sendemail($to,$mailBody,$mailSubject);
								}
								else
								{
									$em_body =$em_body."<br>In Not Error Code";
									$confNo = rand(1, 999999999);
									$confNo = get_unic($confNo);
									//Update postcount and confirmationno. in case successful polling
									$data['postcount'] = 1;
									$data['confirmationno'] = $confNo;
									$qryOrderUpdate = $db->update("res_order_master", $data, "postcount!=1 AND posorderxml='".$row['posorderxml']."'");

									$em_body =$em_body.qryOrderUpdate;
								}
				}
			   else
       		    {
                    $em_body=$em_body."<br>No Response";
       		    }
			}
			
			  //This function return orderXml in string format.
    	function getOrderXmlString($orderXmlPath)
    	 	{
				$locationid = 4;
				$orderXmlString = "";
				$OrderUrl = SITEURL.'/uploads/orderxml/';
				$fullPath = $OrderUrl.$orderXmlPath;
				$address = $fullPath;
				$orderXmlString = GetPageAsString($address);
		
				return $orderXmlString;
    		}
 	   function GetPageAsString($address)
			{
				$locationid = 4;
				$result = "";
       			$file = fopen($address, "r") or exit("Unable to open file!");
				$result=file_get_contents($address);
				fclose($file);
       			return $result;
			}
		function WriteToXml($username,$password)
			{
				 global $db;
				 $locationid = 4;
				 $transaction = "";
				 $xmlOutput;
				 $psXML="";
				 $subject = "All Request";
				 $qryOrder = "SELECT * FROM res_order_master WHERE postcount!=1 and `location_id` = '".$locationid."' limit 1";
				 $result=$db->first($qryOrder);
				 
				 if($result)
				 {
						  $psXML = str_replace(' ','',$result["posorderxml"]);
    					  $OrderUrl = SITEURL."/uploads/orderxml/";
						  $add = $OrderUrl.$psXML;
						  $transaction = GetPageAsString($add);
						  $transaction=str_replace('<?xml version=\"1.0\"?>','',$transaction);
						  $transaction=str_replace('&lt;','<',$transaction);
						  $transaction=str_replace('&gt;','>',$transaction);
					 //echo $transaction;
				 }

						$xml = new DOMDocument("1.0");
						$root = $xml->createElement("poll_response");
						//$root->addAttribute('version', '1.0');
						$xml->appendChild($root);
						
						$header = $xml->createElement("header");
						$root->appendChild($header);
						
						$uname = $xml->createElement("user_name");
						$uname->nodeValue=$username;
						$header->appendChild($uname);
						
						$p_rsp_code = $xml->createElement("poll_rsp_code");
						$p_rsp_code->nodeValue="";
						$header->appendChild($p_rsp_code);
						
						$p_rsp_msg = $xml->createElement("poll_rsp_msg");
						$p_rsp_msg->nodeValue="";
						$header->appendChild($p_rsp_msg);
						
						$tran = $xml->createElement("poll_transaction");
						$tran->nodeValue=$transaction;
						$root->appendChild($tran);
						
						
						$em_body = "";
						$xml->formatOutput = true;
						$xml_string = $xml->saveXML();
						$xmlOutput = html_entity_decode($xml_string);
						try
						{
							$em_body .= "<br>Out Put: <br>";
							$em_body .= $xmlOutput;
							
						}
						catch (Exception $e) {
								echo 'Caught exception: ',  $e->getMessage(), "\n";
							}
						ob_clean();
						header( 'Content-type: text/xml; charset=utf-8' );
						echo  html_entity_decode($xmlOutput);
						exit();
			}
			
		
		function  RandomNumber($min,$max)
   			 {
				$random = rand ($min ,$max );
				return $random;
   			 }
			 
	    function get_unic($randmno)
   			 {
			 	global $db;
				$locationid = 4;
				$get_no = "SELECT confirmationno FROM res_order_master where confirmationno= '".$randmno."' and `location_id` = '".$locationid."' ";
				$dt_no=$db->query($get_no);
				if ($db->numrows($dt_no) <= 0)
					return $randmno;
				else
					return (get_unic(rand (1 ,999999999)));
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
				 echo "email sent";
}
?>