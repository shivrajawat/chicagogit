<?php
  /**
   * Controller
   *
   * thsi is define for check submit form and send lib class pages 
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");
?>
<?php
  /* Proccess Menu */
  if (isset($_POST['processMenu']))
      : if (intval($_POST['processMenu']) == 0 || empty($_POST['processMenu']))
      : redirect_to("index.php?do=menus");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processMenu();
  endif;
?>
<?php
  /* Proccess Page */
  if (isset($_POST['processPage']))
      : if (intval($_POST['processPage']) == 0 || empty($_POST['processPage']))
      : redirect_to("index.php?do=pages");
  endif;
  $content->pageid = (isset($_POST['pageid'])) ? $_POST['pageid'] : 0; 
  $content->processPage();
  endif;
?>
<?php
  /* Proccess Post */
  if (isset($_POST['processPost']))
      : if (intval($_POST['processPost']) == 0 || empty($_POST['processPost']))
      : redirect_to("index.php?do=posts");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processPost();
  endif;
?>
<?php
  /* Proccess Module */
  if (isset($_POST['processModule']))
      : if (intval($_POST['processModule']) == 0 || empty($_POST['processModule']))
      : redirect_to("index.php?do=modules");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processModule();
  endif;
?>
<?php
  /* Proccess Plugin */
  if (isset($_POST['processPlugin']))
      : if (intval($_POST['processPlugin']) == 0 || empty($_POST['processPlugin']))
      : redirect_to("index.php?do=plugins");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processPlugin();
  endif;
?>
<?php
  /* Proccess Membership */
  if (isset($_POST['processMembership']))
      : if (intval($_POST['processMembership']) == 0 || empty($_POST['processMembership']))
      : redirect_to("index.php?do=pages");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processMembership();
  endif;
?>
<?php
  /* Proccess User */
  if (isset($_POST['processUser']))
      : if (intval($_POST['processUser']) == 0 || empty($_POST['processUser']))
      : redirect_to("index.php?do=users");
  endif;
  $user->userid = (isset($_POST['userid'])) ? $_POST['userid'] : 0; 
  $user->processUser();
  endif;
?>
<?php
  /* Proccess Configuration */
  if (isset($_POST['processConfig']))
      : $core->processConfig();
  endif;
?>
<?php
  /* Proccess Email Template */
  if (isset($_POST['processTemplate']))
      : if (intval($_POST['processTemplate']) == 0 || empty($_POST['processTemplate']))
      : redirect_to("index.php?do=templates");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processEmailTemplate();
  endif;
?>
<?php
  /* Add New Language */
  if (isset($_POST['addLanguage']))
      : if (intval($_POST['addLanguage']) == 0 || empty($_POST['addLanguage']))
      : redirect_to("index.php?do=language");
  endif;
  $core->addLanguage();
  endif;
?>
<?php
  /* Update Language */
  if (isset($_POST['updateLanguage']))
      : if (intval($_POST['updateLanguage']) == 0 || empty($_POST['updateLanguage']))
      : redirect_to("index.php?do=language");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $core->updateLanguage();
  endif;
?>
<?php
  /* Proccess Newsletter */
  if (isset($_POST['processNewsletter']))
      : if (intval($_POST['processNewsletter']) == 0 || empty($_POST['processNewsletter']))
      : redirect_to("index.php?do=newsletter");
  endif;
  $member->emailUsers();
  endif;
?>
<?php
  /* Proccess Gateway */
  if (isset($_POST['processGateway']))
      : if (intval($_POST['processGateway']) == 0 || empty($_POST['processGateway']))
      : redirect_to("index.php?do=gateways");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processGateway();
  endif;
?>
<?php
  /* Proccess Theme Switch */
  if (isset($_POST['themeoption'])):
      print $core->getThemeOptions(sanitize($_POST['themeoption']));
	  print '<script type="text/javascript">
	  $(\'select.custombox2\').selectbox();
	  </script>';
  endif;
?>
<?php
  /* == Site Maintenance == */
  if (isset($_POST['processMaintenance'])):
      if (isset($_POST['inactive'])):
          $now = date('Y-m-d H:i:s');
          $diff = intval($_POST['days']);
          $expire = date("Y-m-d H:i:s", strtotime($now . -$diff . " days"));
          $db->delete("users", "lastlogin < '" . $expire . "' AND active = 'y' AND userlevel !=9");
          print ($db->affected()) ? $core->msgOk(str_replace("[NUMBER]", $db->affected(), _SM_INACTIVEOK)) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['pending'])):
          $db->delete("users", "active = 't'");
          print ($db->affected()) ? $core->msgOk(str_replace("[NUMBER]", $db->affected(), _SM_PENDINGOK)) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['banned'])):
		  $db->delete("users", "active = 'b'");
          print ($db->affected()) ? $core->msgOk(_SM_BANNEDOK) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['sitemap'])):
          $content->writeSiteMap();
      endif;
  endif;
?>
<?php
  /* process Currency */
  if (isset($_POST['processCurrency']))
      : if (intval($_POST['processCurrency']) == 0 || empty($_POST['processCurrency']))
      : redirect_to("index.php?do=currency_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processCurrency();
  endif;
?>
<?php
  /* process Country */
  if (isset($_POST['processCountry']))
      : if (intval($_POST['processCountry']) == 0 || empty($_POST['processCountry']))
      : redirect_to("index.php?do=country_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processCountry();
  endif;
?>
<?php
  /* process Country */
  if (isset($_POST['processState']))
      : if (intval($_POST['processState']) == 0 || empty($_POST['processState']))
      : redirect_to("index.php?do=state_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processState();
  endif;
?>
<?php
  /* Proccess City */
  if (isset($_POST['processCity']))
      : if (intval($_POST['processCity']) == 0 || empty($_POST['processCity']))
      : redirect_to("index.php?do=city_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processCity();
  endif;
?>
<?php
  /* Proccess Company */
  if (isset($_POST['processcompany']))
      : if (intval($_POST['processcompany']) == 0 || empty($_POST['processcompany']))
      : redirect_to("index.php?do=company_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processcompany();
  endif;
?>
<?php
  /* Proccess Location */
  if (isset($_POST['processlocation']))
      : if (intval($_POST['processlocation']) == 0 || empty($_POST['processlocation']))
      : redirect_to("index.php?do=location_master");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processlocation();
  endif;
?>
<?php
  /* Proccess TimeLocation */
  if (isset($_POST['processTimeLocation']))
      : if (intval($_POST['processTimeLocation']) == 0 || empty($_POST['processTimeLocation']))
      : redirect_to("index.php?do=location_timing_master");
  endif;
 
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processTimeLocation();
  endif;
?>
<?php
  /* Proccess Holidays*/
  if (isset($_POST['processHolidays']))
      : if (intval($_POST['processHolidays']) == 0 || empty($_POST['processHolidays']))
      : redirect_to("index.php?do=holiday_master");
  endif;
 
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processHolidays();
  endif;
?>
<?php
  /* Update TimeLocation */
  if (isset($_POST['UpdateTimeLocation']))
      : if (intval($_POST['UpdateTimeLocation']) == 0 || empty($_POST['UpdateTimeLocation']))
      : redirect_to("index.php?do=location_timing_master");
  endif;
 
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->UpdateTimeLocation();
  endif;
?>
<?php
  /* process processMenuMaster */
  if (isset($_POST['processMenuMaster']))
      : if (intval($_POST['processMenuMaster']) == 0 || empty($_POST['processMenuMaster']))
      : redirect_to("index.php?do=menu_master");
  endif;
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processMenuMaster();
  endif;
?>
<?php
  /* process processmenuLocation */
  if (isset($_POST['processmenuLocation']))
      : if (intval($_POST['processmenuLocation']) == 0 || empty($_POST['processmenuLocation']))
      : redirect_to("index.php?do=menu_location_mapping");
  endif;
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processmenuLocation();
  endif;
?>
<?php
  /* process processmenucategory */
  if (isset($_POST['processmenucategory']))
      : if (intval($_POST['processmenucategory']) == 0 || empty($_POST['processmenucategory']))
      : redirect_to("index.php?do=menu_category_master");
  endif;
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processmenucategory();
  endif;
?>
<?php
  /* process processsizemaster */
  if (isset($_POST['processsizemaster']))
      : if (intval($_POST['processsizemaster']) == 0 || empty($_POST['processsizemaster']))
      : redirect_to("index.php?do=menu_size_master");
  endif;
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processsizemaster();
  endif;
?>
<?php
  /* process processsizemaster */
  if (isset($_POST['processlocationsetting']))
      : if (intval($_POST['processlocationsetting']) == 0 || empty($_POST['processlocationsetting']))
      : redirect_to("index.php?do=location_setting");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processlocationsetting();
  endif;
?>
<?php
  /* process processsizemaster */
  if (isset($_POST['procesmenuitemmaster']))
      : if (intval($_POST['procesmenuitemmaster']) == 0 || empty($_POST['procesmenuitemmaster']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->procesmenuitemmaster();
  endif;
?>
<?php
  /* process Delivary Area */
  if (isset($_POST['processDelivaryArea'])):  	
	   if (intval($_POST['processDelivaryArea']) == 0 || empty($_POST['processDelivaryArea']))
		  : redirect_to("index.php?do=delivery_area_master");
	   endif;					
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
 $content->processDelivaryArea();
  endif;
?>
<?php
  /* updateDelivaryArea Delivary Area */
  if (isset($_POST['updateDelivaryArea'])):  	
	   if (intval($_POST['updateDelivaryArea']) == 0 || empty($_POST['updateDelivaryArea']))
		  : redirect_to("index.php?do=delivery_area_master");
	   endif;					
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
 $content->updateDelivaryArea();
  endif;
?>
<?php
  /* Customer Email Export to excel , Ms word , CSV file , and PDF*/
  if (isset($_POST['comproccess']) && intval($_POST['comproccess']) == 1) :
  $action = '';
  if (empty($_POST['comid']))
      : $core->msgAlert(MOD_CM_NA);
  endif;

  if (!empty($_POST['comid']))
      : /*foreach ($_POST['comid'] as $val)
      : $id = intval($val);*/
	  
  if (isset($_POST['action']) && $_POST['action'] == "excelexport"):
	   
	   $sql = "SELECT email_id FROM res_customer_master where id IN (".implode(",", $_POST['comid']).")";		  
		   // $db_conn should be a valid db handle	
		    $file = UPLOADS ."excel/Export_Customer_" . date("d_M_Y_h_ia") . ".xls";	
			$type = "vnd.ms-word";	
		    // output to file system
		    $csv = query_to_csv($sql, $file, false);		
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/excel/Export_Customer_" . date("d_M_Y_h_ia") . ".xls';"						
					."\n // ]]>"
					."\n </script>";
		$action = "Excel file Export ";
		}else{$action = "System error to download.";}
		
  elseif(isset($_POST['action']) && $_POST['action'] == "wordexport"):
      $sql = "SELECT email_id FROM res_customer_master where id IN (".implode(",", $_POST['comid']).")";		  
		   // $db_conn should be a valid db handle	
		    $file = UPLOADS ."ms_word/Export_User_" . date("d_M_Y_h_ia") . ".doc";	
			$type = "msword";	
		    // output to file system
		    $csv = query_to_csv($sql, $file, false);		
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/ms_word/Export_User_" . date("d_M_Y_h_ia") . ".doc';"					
					."\n // ]]>"
					."\n </script>";
		$action = "MS word  file Export ";
		}else{$action = "System error to download.";}
		
  elseif (isset($_POST['action']) && $_POST['action'] == "csvexport"):
 	  	$sql = "SELECT email_id FROM res_customer_master where id IN (".implode(",", $_POST['comid']).")";		  
		   // $db_conn should be a valid db handle		    
			$file = UPLOADS ."csv/customer_" . date("d_M_Y_h_ia") . ".csv";
			$filetype = "csv";		
		    // output to file system
		    $csv = query_to_csv($sql, $file,$filetype, false);		
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/csv/customer_" . date("d_M_Y_h_ia") . ".csv';"						
					."\n // ]]>"
					."\n </script>";
		  $action = "CSV file Export ";
		}else{$action =  "System error to download.";}
		
  elseif (isset($_POST['action']) && $_POST['action'] == "pdfexport"): 
  			  $header=array('email_id');		
				//Data loading
				//*** Load MySQL Data ***//	
			   require_once(WOJOLITE . "lib/class_db.php");		  
			   $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
			   $db->connect();
			
			   $objConnect = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die('Oops connection error -> ' . mysql_error());
			   $objDB = mysql_select_db(DB_DATABASE, $connection) or die('Database error -> ' . mysql_error());	
			   
			   $strSQL = "SELECT email_id FROM res_customer_master where id IN (".implode(",", $_POST['comid']).")";
				
			   $objQuery = mysql_query($strSQL);
				
				$resultData = array();
				
				for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
					$result = mysql_fetch_array($objQuery);
					array_push($resultData,$result);
				}
				$pdf->SetFont('Arial','',6);
				$d=date('d_m_Y');
				$file = UPLOADS ."pdffile/customer_" . date("d_M_Y_h_ia") . ".pdf";
				//*** Table 1 ***//
				$pdf->AddPage();
				$pdf->Ln(35);
				$pdf->BasicTable($header,$resultData);
				forme();
				  $action = "PDF file Export ";
				$pdf->Output($file,"F");
  	
  endif;

  print_r($core->msgOk($action));   
 
  endif;

  endif;
?> 
<?php
  /* updateDelivaryArea Delivary Area */
  if (isset($_POST['processWebsiteInstall'])):  	
	   if (intval($_POST['processWebsiteInstall']) == 0 || empty($_POST['processWebsiteInstall']))
		  : redirect_to("index.php?do=website_install");
	   endif;					
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processWebsiteInstall();
  endif;
?>
<?php
  /* process processCouponmaster */
  if (isset($_POST['processCouponmaster']))
      : if (intval($_POST['processCouponmaster']) == 0 || empty($_POST['processCouponmaster']))
      : redirect_to("index.php?do=coupon_master");
  endif;
  $menu->menuid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $menu->processCouponmaster();
  endif;
?>
<?php
  /* Customer Email Export to excel , Ms word , CSV file , and PDF*/
  if (isset($_POST['couponproccess']) && intval($_POST['couponproccess']) == 1) :
  $action = '';
  if (empty($_POST['couponid'])) 
      :$core->msgAlert(MOD_CM_NA);
  endif;

  if (!empty($_POST['couponid'])): 	  
  
  if (isset($_POST['action']) && $_POST['action'] == "excelexport"):
	   
      $sql = "SELECT * FROM res_coupon_master where id IN (".implode(",", $_POST['couponid']).")";	
	
      $result = $db->query($sql);
     
	  
      /*$type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->site_name . " on $date";

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
      header("Content-Transfer-Encoding: binary ");
      
      echo("$title\n");
      $sep = "\t";
      
      for ($i = 0; $i < $db->numfields($result); $i++) {
          echo mysql_field_name($result, $i) . "\t";
      }
      print("\n");
      
      while ($row = mysqli_fetch_fields($result)) {
          $schema_insert = "";
          for ($j = 0; $j < $db->numfields($result); $j++) {
              if (!isset($row[$j]))
                  $schema_insert .= "NULL" . $sep;
              elseif ($row[$j] != "")
                  $schema_insert .= "$row[$j]" . $sep;
              else
                  $schema_insert .= "" . $sep;
          }
          $schema_insert = str_replace($sep . "$", "", $schema_insert);
          $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
          $schema_insert .= "\t";
          print(trim($schema_insert));
          print "\n";
      }
	  */  	  
		   // $db_conn should be a valid db handle	
		    $file = UPLOADS ."excel/Export_Coupan_" . date("d_M_Y_h_ia") . ".xls";	
			$type = "vnd.ms-word";
				
		    // output to file system
		    $csv = query_to_csv($sql, $file, $type);
					
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/excel/Export_Coupan_" . date("d_M_Y_h_ia") . ".xls';"					
					."\n // ]]>"
					."\n </script>";
					
		$action = "Excel file Export ";
		
		} else{ $action = "System error to download."; }
		
  elseif(isset($_POST['action']) && $_POST['action'] == "wordexport"):
            
 	  	$sql = "SELECT * FROM res_coupon_master where id IN (".implode(",", $_POST['couponid']).")";	
			  
		   // $db_conn should be a valid db handle	
		    $file = UPLOADS ."csv/customer_" . date("d_M_Y_h_ia") . ".doc";
			
			$file_type = "vnd.ms-word";		
			
		    // output to file system
		    $csv = query_to_csv($sql, $file,$filetype, false);	
				
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/csv/customer_" . date("d_M_Y_h_ia") . ".doc';"						
					."\n // ]]>"
					."\n </script>";
		  $action = "CSV file Export ";
		  
		 }else{ $action =  "System error to download."; }  
		
  elseif (isset($_POST['action']) && $_POST['action'] == "csvexport"):
        
 	  		$sql = "SELECT * FROM res_coupon_master where id IN (".implode(",", $_POST['couponid']).")";
				  
		    // $db_conn should be a valid db handle	
		    $file = UPLOADS ."csv/customer_" . date("d_M_Y_h_ia") . ".csv";
			$filetype = "csv";
					
		    // output to file system
		    $csv = query_to_csv($sql, $file,$filetype, false);		
			if($file)
			{			
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/csv/customer_" . date("d_M_Y_h_ia") . ".csv';"						
					."\n // ]]>"
					."\n </script>";
		  $action = "CSV file Export ";
		} else{ $action =  "System error to download."; }
		
  elseif (isset($_POST['action']) && $_POST['action'] == "pdfexport"): 
  
  		       $header=array('coupon_id','title','no_of_coupon','type_of_discount','discount');
		       
			    //Data loading
			    //*** Load MySQL Data ***//				
				$sql = "SELECT * FROM res_coupon_master where id IN (".implode(",", $_POST['couponid']).")";
				
				//$objQuery = mysql_query($strSQL);
				$pdfrow = $db->fetch_all($sql);
				$resultData = array();
				$pdf->SetFont('Arial','',6);
				$d=date('d_m_Y');
				$file = UPLOADS ."pdffile/customer_" . date("d_M_Y_h_ia") . ".pdf";
				//*** Table 1 ***//
				$pdf->AddPage();
				$pdf->Ln(35);
				$pdf->CBasicTable($header,$pdfrow);
				forme();
				  $action = "PDF file Export ";
				$pdf->Output($file,"F");
  	
  endif;

  print_r($core->msgOk($action));   
 
  endif;

  endif;
?> 
<?php
  /* Proccess Menu Option */
  if (isset($_POST['processMenuOption']))
      : if (intval($_POST['processMenuOption']) == 0 || empty($_POST['processMenuOption']))
      : redirect_to("index.php?do=menu_option_master");
  endif;
 $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processMenuOption();
  endif;
?>
<?php
  /* processPageMaster */
  if (isset($_POST['processPageMaster'])):  	
	   if (intval($_POST['processPageMaster']) == 0 || empty($_POST['processPageMaster']))
		  : redirect_to("index.php?do=page_master");
	   endif;					
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processPageMaster();
  endif;
?>
<?php
  /* Proccess Menu Option */
  if (isset($_POST['processMenuOptionTest']))
      : if (intval($_POST['processMenuOptionTest']) == 0 || empty($_POST['processMenuOptionTest']))
      : redirect_to("index.php?do=menu_option_master");
  endif;
  
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processMenuOptionTest();
  endif;
?>
<?php
  /* Proccess Menu Option */
  if (isset($_POST['processMenuIconmaster']))
      : if (intval($_POST['processMenuIconmaster']) == 0 || empty($_POST['processMenuIconmaster']))
      : redirect_to("index.php?do=icon_manager");
  endif;
  
  $menu->menuid = (isset($_POST['menuid'])) ? $_POST['menuid'] : 0; 
  $menu->processMenuIconmaster();
  endif;
?>