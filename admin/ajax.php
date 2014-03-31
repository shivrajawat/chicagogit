<?php
  /**
   * Ajax to call admin pages for delete and some tast excute
   *  
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");
?>
<?php
  /* Load Menu */
  if (isset($_POST['getmenus']))
      : $content->getSortMenuList();
  endif;
?>
<?php
  /* Sort Menu */
  if (isset($_POST['sortmenuitems']))
      : $i = 0;
	foreach ($_POST['list'] as $k => $v)
		: $i++;
	$data['parent_id'] = intval($v);
	$data['position'] = intval($i);
	$res = $db->update("menus", $data, "id='" . (int)$k . "'");
	endforeach;
	print ($res) ? $core->msgOk(_MU_SORTED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;  
?>
<?php
  /* Delete Menu */
  if (isset($_POST['deleteMenu']))
      : if (intval($_POST['deleteMenu']) == 0 || empty($_POST['deleteMenu']))
      : redirect_to("index.php?do=menus");
  endif;
  
  $id = intval($_POST['deleteMenu']);
  
  $action = $db->delete("menus", "id='" . $id . "'");
  $db->delete("menus", "parent_id='" . $id . "'");
  
  $title = sanitize($_POST['title']);
  print ($action) ? $wojosec->writeLog(_MENU .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MENU .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);   
  endif;
?>
<?php
  /* Delete Content Page */
  if (isset($_POST['deletePage']))
      : if (intval($_POST['deletePage']) == 0 || empty($_POST['deletePage']))
      : redirect_to("index.php?do=pages");
  endif;
  
  $id = intval($_POST['deletePage']);
  $res = $db->delete("pages", "id='" . $id . "'");
  $db->delete("posts", "page_id='" . $id . "'");
  $db->delete("layout", "page_id='" . $id . "'");

  $title = sanitize($_POST['title']);
  print ($res) ? $wojosec->writeLog(_PAGE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_PAGE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Get Membership List */
  if (isset($_POST['membershiplist'])) :
      if($_POST['membershiplist'] == "Membership"):
	  $memid = getValue("membership_id", "pages", "id='".(int)$_POST['pageid']."'");
	  print $member->getMembershipList($memid);
	  endif; 
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deletePost']))
      : if (intval($_POST['deletePost']) == 0 || empty($_POST['deletePost']))
      : redirect_to("index.php?do=posts");
  endif;
  
  $id = intval($_POST['deletePost']);
  $db->delete("posts", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_POST .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_POST .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Module */
  if (isset($_POST['deleteModule']))
      : if (intval($_POST['deleteModule']) == 0 || empty($_POST['deleteModule']))
      : redirect_to("index.php?do=modules");
  endif;
  
  $id = intval($_POST['deleteModule']);
  $data['module_id'] = 0;
  $data['module_data'] = 0;
  $db->update("pages",$data,"module_id = '".$id."'");
  $db->delete("modules", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MODULE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(_MODULE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Get Module List */
  if (isset($_POST['modulelist'])) :
      $alias = getValue('modalias','modules','id="'.intval($_POST['modulelist']).'"');
	  $module_data = intval($_POST['module_data']);
	  if(file_exists(MODPATH.$alias.'/config.php'))
	  include(MODPATH.$alias.'/config.php');
  endif;
?>
<?php
  /* Delete Plugin */
  if (isset($_POST['deletePlugin']))
      : if (intval($_POST['deletePlugin']) == 0 || empty($_POST['deletePlugin']))
      : redirect_to("index.php?do=plugins");
  endif;
  
  $id = intval($_POST['deletePlugin']);
  $db->delete("plugins", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_PLUGIN .' <strong>'.$title.'</strong> '._DELETED, "", "no", "plugin") . $core->msgOk(_PLUGIN .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Delete Membership */
  if (isset($_POST['deleteMembership']))
      : if (intval($_POST['deleteMembership']) == 0 || empty($_POST['deleteMembership']))
      : redirect_to("index.php?do=memberships");
  endif;
  
  $id = intval($_POST['deleteMembership']);
  $db->delete("memberships", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MEMBERSHIP .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MEMBERSHIP .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Transaction */
  if (isset($_POST['deleteTransaction']))
      : if (intval($_POST['deleteTransaction']) == 0 || empty($_POST['deleteTransaction']))
      : redirect_to("index.php?do=transactions");
  endif;
  
  $id = intval($_POST['deleteTransaction']);
  $db->delete("payments", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_TRANSACTION .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_TRANSACTION .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Export Transactions */
  if (isset($_GET['exportTransactions'])) {
      $sql = "SELECT * FROM payments";
      $result = $db->query($sql);
      
      $type = "vnd.ms-excel";
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
      
      while ($row = $db->fetchrow($result)) {
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
	  exit();
  }
?>
<?php
  /* Delete User */
  if (isset($_POST['deleteUser']))
      : if (intval($_POST['deleteUser']) == 0 || empty($_POST['deleteUser']))
      : redirect_to("index.php?do=users");
  endif;
  
  $id = intval($_POST['deleteUser']);
	if($id == 1):
	$core->msgError(_UR_ADMIN_E);
	else:
	$db->delete("users", "id='" . $id . "'");
	
	$username = sanitize($_POST['title']);
	
	print ($db->affected()) ? $wojosec->writeLog(_USER .' <strong>'.$username.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_USER .' <strong>'.$username.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
  endif;
?>
<?php
  /* User Search */
  if (isset($_POST['userSearch']))
      : $string = sanitize($_POST['userSearch'],15);
  
  if (strlen($string) > 3)
      : $sql = "SELECT id, username, email, CONCAT(fname,' ',lname) as name" 
	  . "\n FROM users"
	  . "\n WHERE MATCH (username) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)"
	  . "\n ORDER BY username LIMIT 10";
  $display = '';
  if($result = $db->fetch_all($sql)):
  $display .= '<ul id="searchresults">';
	foreach($result as $row):
	  $link = 'index.php?do=users&amp;action=edit&amp;userid=' . (int)$row['id'];
	  $display .= '<li><a href="'.$link.'">'.$row['username'].'<small>'.$row['name'].' - '.$row['email'].'</small></a></li>';
	endforeach;
  $display .= '</ul>';
  print $display;
  endif;
  endif;
  endif;
?>
<?php
  /* Check Username */
  if (isset($_POST['checkUsername'])): 
  
  $username = trim(strtolower($_POST['checkUsername']));
  $username = $db->escape($username);
  
  $sql = "SELECT username FROM users WHERE username = '".$username."' LIMIT 1";
  $result = $db->query($sql);
  $num = $db->numrows($result);
  
  echo $num;
  
  endif;
?>
<?php
  /* Update Post Order */
  if (isset($_GET['sortposts']) && $_GET['sortposts'] == 1) :
      foreach ($_GET['pid'] as $k => $v) :
          $p = $k + 1;
          
          $data['position'] = $p;
          
          $db->update("posts", $data, "id='" . intval($v) . "'");
      endforeach;
 endif;
?>
<?php
  /* Get Content Type */
  if (isset($_GET['contenttype']))
      : $type = sanitize($_GET['contenttype']);
  $display = "";
  switch ($type)
      : case "page":
      $sql = "SELECT id, title{$core->dblang} FROM pages WHERE active = '1' ORDER BY title{$core->dblang} ASC";
  $result = $db->fetch_all($sql);
  
  $display .= "<select name=\"page_id\" class=\"custombox2\" style=\"width:250px\">";
  if ($result)
      : foreach ($result as $row)
      : $display .= "<option value=\"" . $row['id'] . "\">page. " . $row['title'.$core->dblang] . "</option>\n";
  endforeach;
  endif;
  $display .= "</select>\n";
  break;
      
  case "module" :
      $sql = "SELECT id, title{$core->dblang}, modalias FROM modules WHERE active = '1' AND system = '1' ORDER BY title{$core->dblang} ASC";
  $result = $db->fetch_all($sql);
  
  if ($result): 
  $display .= "<select name=\"mod_id\" class=\"custombox2\" style=\"width:250px\">";
  
      foreach ($result as $row)
      : $display .= "<option value=\"" . $row['id'] . "\">module. " . $row['title'.$core->dblang] . "</option>\n";
  endforeach;
  
  $display .= "</select>\n";
  endif;

  break;
  default:
      $display .= "<input name=\"web\" type=\"text\" class=\"inputbox\" value=\"" . post('web') . "\" size=\"45\" />
	  &nbsp;".tooltip(_MU_LINK_T)."
	  <div class=\"mybox\"><select name=\"target\" style=\"width:100px\" class=\"select\">
          <option value=\"\">"._MU_TARGET."</option>
		  <option value=\"_blank\">"._MU_TARGET_B."</option>
		  <option value=\"_self\">"._MU_TARGET_S."</option>
        </select></div>
	  <input name=\"page_id\" type=\"hidden\" value=\"0\" />";
      
      endswitch;

      print $display;

	  print '<script type="text/javascript">
		$(function(){
			$("select.custombox2").selectbox();
		 });   
	  </script>';
	  
  endif;
?>
<?php
  /* Update Layout */

  if (isset($_GET['layout']))
      : $sort = sanitize($_GET['layout']);
  $idata = (isset($_GET['modslug'])) ? 'mod_id' : 'page_id';
  
  @$sorted = str_replace("list-", "", $_POST[$sort]);
  if ($sorted)
      : foreach ($sorted as $plug_id)
      : list($order, $plug_id) = explode("|", $plug_id);
  $stylename = explode("-", $sort);
  $page_id = $stylename[1];
  if ($stylename[0] == "default")
      //continue;
	  $db->delete("layout", "plug_id='" . (int)$plug_id . "' AND $idata = '" . (int)$page_id . "'");
  
  $data = array(
		  'plug_id' => $plug_id, 
		  'page_id' => (isset($_GET['pageslug'])) ? $page_id : 0, 
		  'mod_id' => (isset($_GET['modslug'])) ? $page_id : 0, 
		  'page_slug' => (isset($_GET['pageslug'])) ? sanitize($_GET['pageslug']) : "",
		  'modalias' => (isset($_GET['modslug'])) ? sanitize($_GET['modslug']) : "",
		  'place' => $stylename[0], 
		  'position' => $order
  );
  
  if ($stylename[0] != "default") :
  $db->delete("layout", "plug_id='" . (int)$plug_id . "' AND $idata = '" . (int)$page_id . "'");
  $db->insert("layout", $data);
  endif;
  endforeach;
  endif;
 
  endif;

?>
<?php
  /* Remote Links */
  if (isset($_GET['linktype']) && $_GET['linktype'] == "internal"): 
  $display = "";
  $display .= "<select name=\"content_id\" style=\"width:245px\" id=\"content_id\" onchange=\"updateChooser(this.value);\">";
  $display .= "<option value=\"NA\">"._RL_SELECT."</option>\n";
  
  $sql = $db->query("SELECT slug, title{$core->dblang}" 
  . "\n FROM pages" 
  . "\n ORDER BY title{$core->dblang} ASC");
  
  while ($row = $db->fetch($sql))
  : $title = $row['title'.$core->dblang];
  
  $link = str_replace(SITEURL, "", createPageLink($row['slug']));
  $display .= "<option value=\"" . $link . "\">".$title."</option>\n";
  endwhile;
  $display .= "</select>\n";
  echo $display;
  endif;
?>
<?php
  /* Delete Language */
  if (isset($_POST['deleteLanguage'])): 
  $flag_id = sanitize($_POST['deleteLanguage'],2);
  set_time_limit(120);
  $core->deleteLanguage($flag_id);
  endif;
?>
<?php
  /* == Latest Visitor Stats == */
  if (isset($_GET['getVisitsStats'])):
      if (intval($_GET['getVisitsStats']) == 0 || empty($_GET['getVisitsStats'])):
          die();
      endif;

      $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'month';
      $data = array();
      $data['hits'] = array();
      $data['xaxis'] = array();
      $data['hits']['label'] = _MN_TOTAL_H;
      $data['visits']['label'] = _MN_UNIQUE_V;

      switch ($range)
      {
          case 'day':
		      $date = date('Y-m-d');
			  
              for ($i = 0; $i < 24; $i++)
              {
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE DATE(day)='" . $db->escape($date) . "'" 
				  . "\n AND HOUR(day) = '" . (int)$i . "'" 
				  . "\n GROUP BY HOUR(day) ORDER BY day ASC");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
              }
              break;
          case 'week':
              $date_start = strtotime('-' . date('w') . ' days');

              for ($i = 0; $i < 7; $i++)
              {
                  $date = date('Y-m-d', $date_start + ($i * 86400));
                  $row = $db->first("SELECT SUM(pageviews) AS total," 
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats"
				  . "\n WHERE DATE(day) = '" . $db->escape($date) . "'" 
				  . "\n GROUP BY DATE(day)");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('D', strtotime($date)));
              }

              break;
          default:
          case 'month':
		     
              for ($i = 1; $i <= date('t'); $i++)
              {
                 $date = date('Y') . '-' . date('m') . '-' . $i;
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE (DATE(day) = '" . $db->escape($date) . "')" 
				  . "\n GROUP BY DAY(day)");
				 

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('j', strtotime($date)));
              }
              break;
          case 'year':
              for ($i = 1; $i <= 12; $i++)
              {
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE YEAR(day) = '" . date('Y') . "'" 
				  . "\n AND MONTH(day) = '" . $i . "'" 
				  . "\n GROUP BY MONTH(day)");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
				  $data['xaxis'][] = array($i, doDate('%b',date('M', mktime(0, 0, 0, $i, 1, date('Y')))));
				  
              }
              break;
      }
	  

      print json_encode($data);
	 
  endif;

  /* == Latest Sales Stats == */
  if (isset($_GET['getTransactionStats'])):
	
  $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'year';	  
  $data = array();
  $data['order'] = array();
  $data['xaxis'] = array();
  $data['order']['label'] = _TR_TOTREV;
  
  switch ($range) {
	  case 'day':
	  $date = date('Y-m-d');
		  for ($i = 0; $i < 24; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments" 
			  . "\n WHERE DATE(date) = '" . $db->escape($date) . "'" 
			  . "\n AND HOUR(date) = '" . (int)$i . "'" 
			  . "\n AND status = 1"
			  . "\n GROUP BY HOUR(date) ORDER BY date ASC");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
		  }
		  break;
	  case 'week':
		  $date_start = strtotime('-' . date('w') . ' days');
  
		  for ($i = 0; $i < 7; $i++) {
			  $date = date('Y-m-d', $date_start + ($i * 86400));
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE DATE(date) = '" . $db->escape($date) . "'"
			  . "\n AND status = 1"
			  . "\n GROUP BY DATE(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('D', strtotime($date)));
		  }
  
		  break;
	  default:
	  case 'month':
		  for ($i = 1; $i <= date('t'); $i++) {
			  $date = date('Y') . '-' . date('m') . '-' . $i;
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE (DATE(date) = '" . $db->escape($date) . "')"
			  . "\n AND status = 1"
			  . "\n GROUP BY DAY(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('j', strtotime($date)));
		  }
		  break;
	  case 'year':
		  for ($i = 1; $i <= 12; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE YEAR(date) = '" . date('Y') . "'"
			  . "\n AND MONTH(date) = '" . $i . "'"
			  . "\n AND status = 1"
			  . "\n GROUP BY MONTH(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
		  }
		  break;
  }

   print json_encode($data);
   exit();
  endif;
  
  /* Delete Statistics */
  if (isset($_POST['deleteStats'])): 
  $action = $db->query("TRUNCATE TABLE stats");
  print ($action) ? $wojosec->writeLog(_MN_STATS_EMPTY, "", "no", "content") . $core->msgOk(_MN_STATS_EMPTY) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete SQL Backup */
  if (isset($_POST['deleteBackup'])) :
  $action = @unlink(WOJOLITE . 'admin/backups/'.sanitize($_POST['deleteBackup']));
  
  print ($action) ? $wojosec->writeLog(_BK_DELETE_OK, "", "no", "database") . $core->msgOk(_BK_DELETE_OK) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Logs */
  if (isset($_POST['deleteLogs'])): 
  $action = $db->query("TRUNCATE TABLE log");
  print ($action) ? $wojosec->writeLog(_LG_STATS_EMPTY, "", "no", "content") . $core->msgOk(_LG_STATS_EMPTY) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Currency */
  if (isset($_POST['deleteCurrency']))
      : if (intval($_POST['deleteCurrency']) == 0 || empty($_POST['deleteCurrency']))
      : redirect_to("index.php?do=currency_master");
  endif;
  
  $id = intval($_POST['deleteCurrency']);
  $db->delete("res_currency_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_CUR_TITLE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_CUR_TITLE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Country */
  if (isset($_POST['deleteCountry']))
      : if (intval($_POST['deleteCountry']) == 0 || empty($_POST['deleteCountry']))
      : redirect_to("index.php?do=currency_master");
  endif;
  
  $id = intval($_POST['deleteCountry']);
  $db->delete("res_country_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_COUNTRY .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_COUNTRY .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteState']))
      : if (intval($_POST['deleteState']) == 0 || empty($_POST['deleteState']))
      : redirect_to("index.php?do=state_master");
  endif;
  
  $id = intval($_POST['deleteState']);
  $db->delete("res_state_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_ST_TITLE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_state_master") . $core->msgOk(_ST_TITLE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deletecity']))
      : if (intval($_POST['deletecity']) == 0 || empty($_POST['deletecity']))
      : redirect_to("index.php?do=city");
  endif;
  
  $id = intval($_POST['deletecity']);
  $db->delete("res_city_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_CM_NAME .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_city_master") . $core->msgOk(_CM_NAME .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletecompany */
  if (isset($_POST['deletecompany']))
      : if (intval($_POST['deletecompany']) == 0 || empty($_POST['deletecompany']))
      : redirect_to("index.php?do=company_master");
  endif;
 
  $id = intval($_POST['deletecompany']);
  
  $title = sanitize($_POST['title']);
    $companyImage = getValue("logo", "res_company_master", "id = '".$id."'");	
    
	
	 if(is_file("../uploads/avatars/".$companyImage)){
		unlink("../uploads/avatars/".$companyImage);
	}
	$db->delete("res_company_master", "id='" . $id . "'");
  print ($db->affected()) ? $wojosec->writeLog(_CM_MANAGER .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_company_master") . $core->msgOk(_CM_MANAGER .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletecompany */
  if (isset($_POST['deletelocation']))
      : if (intval($_POST['deletelocation']) == 0 || empty($_POST['deletelocation']))
      : redirect_to("index.php?do=location_master");
  endif;
  
  $id = intval($_POST['deletelocation']);
  $db->delete("res_location_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_LMD_MANAGER .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_location_master") . $core->msgOk(_LMD_MANAGER .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deleteTime */
  if (isset($_POST['deleteTime']))
      : if (intval($_POST['deleteTime']) == 0 || empty($_POST['deleteTime']))
      : redirect_to("index.php?do=location_timing_master");
  endif;
  
  $id = intval($_POST['deleteTime']);
  $db->delete("res_location_time_master", "location_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_LMD_MANAGER .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_location_time_master") . $core->msgOk(_LMD_MANAGER .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deleteholiday */
  if (isset($_POST['deleteholiday']))
      : if (intval($_POST['deleteholiday']) == 0 || empty($_POST['deleteholiday']))
      : redirect_to("index.php?do=holiday_master");
  endif;
  
  $id = intval($_POST['deleteholiday']);
  $db->delete("res_holiday_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_HD_MANAGER .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_holiday_master") . $core->msgOk(_HD_MANAGER .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletemenumaster */
  if (isset($_POST['deletemenumaster']))
      : if (intval($_POST['deletemenumaster']) == 0 || empty($_POST['deletemenumaster']))
      : redirect_to("index.php?do=menu_master");
  endif;
  
  $id = intval($_POST['deletemenumaster']);
  $db->delete("res_menu_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENU_ .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_menu_master") . $core->msgOk(_MENU_ .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletemenumaster */
  if (isset($_POST['deletemenulocation']))
      : if (intval($_POST['deletemenulocation']) == 0 || empty($_POST['deletemenulocation']))
      : redirect_to("index.php?do=menu_location_mapping");
  endif;
  
  $id = intval($_POST['deletemenulocation']);
  $db->delete("res_menu_location_mapping", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENU_ .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_menu_location") . $core->msgOk(_MENU_ .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletecategorymaster */
  if (isset($_POST['deletecategorymaster']))
      : if (intval($_POST['deletecategorymaster']) == 0 || empty($_POST['deletecategorymaster']))
      : redirect_to("index.php?do=menu_category_master");
  endif;
  
  $id = intval($_POST['deletecategorymaster']);
  $data['is_delete'] = '1';
 $db->delete("res_category_master", "id='" . $id . "'");
 
  
  
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MCAT .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_category_master") . $core->msgOk(_MCAT .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletemenusize */
  if (isset($_POST['deletemenusize']))
      : if (intval($_POST['deletemenusize']) == 0 || empty($_POST['deletemenusize']))
      : redirect_to("index.php?do=menu_category_master");
  endif;
  
  $id = intval($_POST['deletemenusize']);
  $db->delete("res_menu_size_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MSIZE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_menu_size_master") . $core->msgOk(_MSIZE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletelocationsetting */
  if (isset($_POST['deletelocationsetting']))
      : if (intval($_POST['deletelocationsetting']) == 0 || empty($_POST['deletelocationsetting']))
      : redirect_to("index.php?do=location_setting");
  endif;
  
  $id = intval($_POST['deletelocationsetting']);
  $db->delete("res_location_setting", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_LS_SETTING .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_location_setting") . $core->msgOk(_LS_SETTING .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content deletemenuitemmaster */
  if (isset($_POST['deletemenuitemmaster']))
      : if (intval($_POST['deletelocationsetting']) == 0 || empty($_POST['deletemenuitemmaster']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  
  $id = intval($_POST['deletemenuitemmaster']);
  $db->delete("res_menu_item_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MITEM_MITEM .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_menu_item_master") . $core->msgOk(_MITEM_MITEM .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete DeliveryArea  deleteDeliveryArea */
  if (isset($_POST['deleteDeliveryArea']))
      : if (intval($_POST['deleteDeliveryArea']) == 0 || empty($_POST['deleteDeliveryArea']))
      : redirect_to("index.php?do=delivery_area_master");
  endif;
  
  $id = intval($_POST['deleteDeliveryArea']);
  $db->delete("res_location_google_cordinates", "location_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_DLI_ .' <strong>'.$title.'</strong> '._DELETED, "", "no", "deleteDeliveryArea") . $core->msgOk(_DLI_ .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete DeliveryArea  deleteDeliveryArea */
  if (isset($_POST['deleteMenuItem']))
      : if (intval($_POST['deleteMenuItem']) == 0 || empty($_POST['deleteMenuItem']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  
  $id = intval($_POST['deleteMenuItem']);
  $db->delete("res_menu_item_master", "id='" . $id . "'");
  $db->delete("res_menu_size_mapping", "menu_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENUITEM_ .' <strong>'.$title.'</strong> '._DELETED, "", "no", "res_menu_item_master") . $core->msgOk(_MENUITEM_ .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* == View Customer Details == */
  if (isset($_POST['viewCustomer'])):
      if (intval($_POST['viewCustomer']) == 0 || empty($_POST['viewCustomer'])):
          die();
      endif;
	  $id = intval($_POST['viewCustomer']);
	  $row = $content->getCustomerDetails($id);
	  
      print '<div id="pollcontainer"><table class="forms">       
        <tbody>
          <tr>
            <th>Name:</th>
            <td>'.$row['name'].'</td>
          </tr>
          <tr>
            <th>Address:</th>
            <td>'.$row['address1'].'</td>
          </tr>
          <tr>
            <th>Address 2</th>
            <td>'.$row['address2'].'</td>
          </tr>
          <tr>
            <th>Email Address</th>
            <td>'.$row['email_id'].'</td>
          </tr>
          <tr>
            <th>City</th>
            <td>'.$row['city_name'].'</td>
          </tr>
          <tr>
            <th>State</th>
            <td>'.$row['state_name'].'</td>
          </tr>
           <tr>
            <th>Country</th>
            <td>'.$row['country_name'].'</tr>
          <tr>
            <th>Zip Code</th>
            <td>'.$row['zipcode'].'</td>
          </tr>
          <tr>
            <th>Phone No</th>
            <td>'.$row['phone_number'].'</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>'.$row['active'].'</td>
          </tr>           
        </tbody>
      </table></div>';
  endif;
?>
<?php
  /* == View Location Details == */
  if (isset($_POST['viewLocation'])):
      if (intval($_POST['viewLocation']) == 0 || empty($_POST['viewLocation'])):
          die();
      endif;
	  $id = intval($_POST['viewLocation']);
	  //$row = $content->getCustomerDetails($id);
	  ?>
      <?php  $row = $content->getLocationDetails($id);?> 
      <div style="float: right; margin-top: -44px;"><a class="button no" href="javascript:void(0);"><img src="images/del-mini.png" /></a></div>
<div class="inner">
        <div class="content">
          <span style="display:none" id="loader"></span>
          <div id="msgholder"></div>
<div class="block-top-header">
  <h1 style="text-align:center">View Details</h1>
  <div class="divider"><span></span></div>
</div>
<div class="block-border">
  <div class="block-header">
     <h2><?php echo "View Details &gt;" . $row['location_name'];?></h2>
  </div>
  <div class="block-content">  
      <table class="forms">        
        <tbody>
        <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Location Details</h2></td>
        </tr>
        <tr>
            <th>Location Name:</th>
            <td><?php echo $row['location_name'];?></td>
          </tr>
          <tr>
            <th>Restaurant Name:</th>
            <td><?php echo $row['restaurant_name'];?></td>
          </tr>
           <tr>
            <th>Company Name:</th>
            <td><?php echo $row['company_name'];?></td>
          </tr>         
          <tr>
            <th>Address1:</th>
            <td><?php echo $row['address1'];?>
              </td>
          </tr>
          <tr>
            <th>Address2:</th>
            <td><?php echo $row['address2'];?>
              </td>
          </tr>
          
           <tr>
            <th>Phone Number1: </th>
            <td><?php echo $row['phone_number']; ?></td>
          </tr>
           <tr>
            <th>Phone Number2: </th>
            <td><?php echo $row['phone_number1'];?></td>
          </tr>
           <tr>
            <th>Fax Number:</th>
            <td><?php echo $row['fax_number'];?></td>
          </tr>
           <tr>
            <th>Zip Code:</th>
            <td><?php echo $row['zipcode'];?></td>
          </tr>          
          <tr>
            <th>Country Name:</th>
            <td><?php echo $row['country_name']; ?></td>
          </tr>
          <tr>
            <th>State Name:</th>
            <td><?php echo $row['state_name'] ?></td>
          </tr>
          <tr>
            <th>City Name:</th>
            <td><?php echo $row['city_name'];?></td>
          </tr>  
          <tr>
            <th>Website: </th>
            <td><?php echo $row['website'];?></td>
          </tr>     
          <tr>
            <th>Location Slogan: </th>
            <td><?php echo $row['location_slogan'];?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Types</h2></td>
          </tr>
           <tr>
            <th>Pick Up Available:</th>
            <td><div style="width:18px;"><?php if($row['pick_up_available']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div> 
            <label>Pickup time</label>   <?php echo $row['pickup_time'];?>&nbsp;(min)</td>
          </tr>
           <tr>
            <th>Delievery Available:</th>
            <td><div style="width:18px;"><?php if($row['delivery_available']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
             <label>Delivery-time</label> <?php echo $row['delivery_time']; ?> &nbsp;(min)</td>
          </tr>
           <tr>
            <th>DineinAvailable:</th>
            <td><div style="width:18px;"><?php if($row['dinein_available']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div></td>
          </tr> 
          <tr>
            <th>DeliveryFee: </th>
            <td><?php echo $row['delivery_fee'];?> </td>
          </tr>
          
          <tr>
            <th>AdditionalFee: </th>
            <td><?php echo $row['additional_fee'];?></td>
          </tr>
          <tr>
            <th>Gratuity:</th>
            <td><?php echo $row['gratuity'];?></td>
          </tr>
          <tr>
            <th>Sales Tax:</th>
            <td><?php echo $row['sales_tax'];?></td>
          </tr>
          <tr>
            <th>Maximum AdvanceOrder: </th>
            <td><?php echo $row['max_advance_order'];?></td>
          </tr>
          <tr>
            <th>Sales TaxID: </th>
            <td><?php echo $row['sales_tax_id'];?></td>
          </tr>
          
          <tr>
            <th>Email Disclaimer:</th>
            <td><?php echo $row['emai_disclaimer']; ?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">POS Details</h2></td>
          </tr>
          <tr>
            <th>Site Id </th>
            <td><?php echo $row['site_id'];?></td>
          </tr>
          <tr>
            <th>Pos Password</th>
            <td><?php echo $row['pos_password'];?></td>
          </tr>
          <tr>
            <th>Pos IP</th>
            <td><?php echo $row['pos_ip'];?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Payment Gateway Settings </h2></td>
          </tr>
            <tr>
            <th>Choose Payment Method :</th>
            <td><span class="input-out">
              <label>Cash On Delivery</label>
              <div class="ez-checkbox ez-checked"><input type="checkbox" checked="checked" value="1" name="is_cash_on_delivery" class="ez-hide"></div>
              <label>Is Authorize</label>
              <div class="ez-checkbox"><input type="checkbox" value="1" id="is_authorize" onclick="Change();" name="is_authorize" class="ez-hide"></div>
              <label>Is First Data</label>
              <div class="ez-checkbox"><input type="checkbox" value="1" id="is_first_data" onclick="Change();" name="is_first_data" class="ez-hide"></div>
              <label>Is Mercury</label>
              <div class="ez-checkbox"><input type="checkbox" value="1" id="is_mercury" onclick="Change();" name="is_mercury" class="ez-hide"></div>
              <label>Is Internet Secure</label>
              <div class="ez-checkbox"><input type="checkbox" value="1" id="is_internet_secure" onclick="Change();" name="is_internet_secure" class="ez-hide"></div>
              </span></td>
          </tr>         
          <tr>
            <th>Paypal Email Id</th>
            <td><?php echo $row['paypal_email_id'];?></td>
          </tr>
          <!--Start Authorize.net Settings Hide show-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Authorize.net Settings </h2></td>
          </tr>
          <tr>
            <th>Authorize Api ID</th>
            <td><?php echo $row['authorizr_api_id'];?></td>
          </tr>
          <tr>
            <th>Authorize Transaction Key</th>
            <td><?php echo $row['authorizze_trans_key']; ?></td>
          </tr>
          <tr>
            <th>Authorize Hash Key</th>
            <td><?php echo $row['authorize_hash_key']; ?></td>
          </tr>
       
          <!--Start First Data Settings Hide show-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">First Data Settings </h2></td>
          </tr>
          <tr> 
            <th>First Data File Name</th>
            <td><?php echo $row['first_data_file_name'];?></td>
          </tr>
         <!---->
         <!--Start Mercury Settings hide show-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Mercury Settings </h2></td>
          </tr>
          <tr>
            <th>Merchant Id </th>
            <td><?php echo $row['merchant_id'];?></td>
          </tr>
          <tr>
            <th>Merchant Password</th>
            <td><?php echo $row['merchant_password'];?></td>
          </tr>
          
           <!--Start  Internet Secure Gateway Settings hide show-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Internet Secure Gateway Settings</h2></td>
          </tr>
           <tr>
            <th>Internet Secure Gateway ID</th>
            <td><?php echo $row['internet_secure_getwayid'];?></td>
          </tr>
         <!--end  Internet Secure Gateway Settings-->
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">PaymentID Settings</h2></td>
          </tr>
          <tr>
            <th>Cash PaymentID</th>
            <td><?php echo $row['cash_payment_id'];?></td>
          </tr>
          <tr>
            <th>Online PaymentID</th>
            <td><?php echo $row['online_payment_id'];?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Order Notification Emails</h2></td>
          </tr>
          <tr>
            <th>Order Email1</th>
            <td><?php echo $row['order_email1'];?></td>
          </tr>
          <tr>
            <th>Order Email2</th>
            <td><?php echo $row['order_email2'];?></td>
          </tr>
          <tr>
            <th>Order Email3</th>
            <td><?php echo $row['order_email3'];?></td>
          </tr>
          <tr>
            <th>Order Email4</th>
            <td><?php echo $row['order_email4'];?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Custom Site Information</h2></td>
          </tr>
          <tr>
            <th>CustomHeaderHTML</th>
            <td><?php echo $row['custom_header_html'];?></td>
          </tr>
          <tr>
            <th>Disclaimer</th>
            <td><?php echo $row['disclaimer'];?></td>
          </tr>
          <tr>
            <th>ConfirmOrderMessage</th>
            <td><?php echo $row['confirm_order_msg'];?></td>
          </tr>
          <tr>            
            <td colspan="2"><h2 style="color:#FF0000">Pricing Display</h2></td>
          </tr>
          <tr>
            <th>HidePriceInMenu</th>
            <td><span class="input-out">
              <div style="width:18px;"><?php if($row['hide_price_in_menu']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
            </span></td>
          </tr>
           <tr>
            <th>HidePriceInOption</th>
            <td><span class="input-out">
              <div style="width:18px;"><?php if($row['hide_price_in_option']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
            </span></td>
          </tr>
           <tr>            
            <td colspan="2"><h2 style="color:#FF0000">CUSTOMER COMMENTS</h2></td>
          </tr>
           <tr>
            <th>Allow Menu Item Comments </th>
            <td><span class="input-out">
              <div style="width:18px;"><?php if($row['menu_iteam_comments']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
            </span></td>
          </tr>
           <tr>
            <th>Allow Order Comments</th>
            <td><span class="input-out">
              <div style="width:18px;"><?php if($row['menu_iteam_comments']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
            </span></td>
          </tr>
           <tr>
            <th>Allow Delivery Instruction</th>
            <td><span class="input-out">
              <div style="width:18px;"><?php if($row['delivery_instruction']==1){?> <img src="images/yes.png" /> <?php } else {?> <img src="images/del-mini.png" /><?php } ?></div>
            </span></td>
          </tr>          
        </tbody>
      </table>
    </form>
  </div>
</div>
</div>
</div>
 <?php      
  endif;
?>
<?php
  /* == View Customer Details == */
  if (isset($_POST['viewWebInstall'])):
      if (intval($_POST['viewWebInstall']) == 0 || empty($_POST['viewWebInstall'])):
          die();
      endif;
	  $id = intval($_POST['viewWebInstall']);
	  $row = $content->getWebInstallDetails($id);
	  	  
	  if($row['flow']==1){ $flow =  "By Location"; } else { $flow = "By Menu";}
      print '<div id="pollcontainer"><table class="forms">       
        <tbody>
          <tr>
            <th>Wbsite Url:</th>
            <td>'.$row['website_url'].'</td>
          </tr>
          <tr>
            <th>Location:</th>
            <td>'.$row['location_name'].'</td>
          </tr>
          <tr>
            <th>Website Flow</th>
            <td>'.$flow.'</td>
          </tr>          
          <tr>
            <th>Status</th>
            <td>'.$row['active'].'</td>
          </tr>           
        </tbody>
      </table></div>';
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteWebInstall']))
      : if (intval($_POST['deleteWebInstall']) == 0 || empty($_POST['deleteWebInstall']))
      : redirect_to("index.php?do=website_install");
  endif;
  
  $id = intval($_POST['deleteWebInstall']);
  $db->delete("res_install_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_INS_WEBSITe .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_INS_WEBSITe .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Category menu update data listing time quick */
  if (isset($_POST['menucategoryquick']))
      : if (intval($_POST['menucategoryquick']) == 0 || empty($_POST['menucategoryquick']))
      : redirect_to("index.php?do=menu_category_master");
  endif;
  
		$id=$_POST['id'];
		$categoryname = html_entity_decode($_POST['categoryname']);
		$orderdisplay = $_POST['orderdispaly'];
		$data['category_name'] = $categoryname;
		$data['display_order'] = $orderdisplay;
		
	$res = $db->update("res_category_master", $data, "id='" . (int)$id . "'");
	print ($res) ? $core->msgOk(_MCAT_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete SQL Backup */
 if (isset($_POST['menusizequick']))
      : if (intval($_POST['menusizequick']) == 0 || empty($_POST['menusizequick']))
      : redirect_to("index.php?do=menu_size_master");
  endif;
		$id=$_POST['id'];
		$sizename=$_POST['sizename'];
		$ticketid= $_POST['ticketid'];
		$data['size_name'] = $sizename;
		$data['ticket_size_id'] = $ticketid;
	$res = $db->update("res_menu_size_master", $data, "id='" . (int)$id . "'");
	print ($res) ? $core->msgOk(_MCAT_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /*  Menu Item update data listing time quick */
 if (isset($_POST['processitemquick']))
      : if (intval($_POST['processitemquick']) == 0 || empty($_POST['processitemquick']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  
         
		$id = sanitize($_POST['id']);
		$itemname = html_entity_decode($_POST['itemname']);
		$itemdesc = html_entity_decode($_POST['itemdesc']);
		$orderdisplay = sanitize($_POST['orderdispaly']);
		$data['item_name'] = sanitize($itemname);
		$data['item_description'] = sanitize($itemdesc);
		$data['display_order'] = $orderdisplay;
	$res = $db->update("res_menu_item_master", $data, "id='" . (int)$id . "'");
	print ($res) ? $core->msgOk(_MENUITEM_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Coupon Master */
  if (isset($_POST['deleteCouponMaster']))
      : if (intval($_POST['deleteCouponMaster']) == 0 || empty($_POST['deleteCouponMaster']))
      : redirect_to("index.php?do=coupon_master");
  endif;
  
  $id = intval($_POST['deleteCouponMaster']);
  $db->delete("res_coupon_master", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_CPN_MANAGER .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_CPN_MANAGER .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* == View Coupon Details == */
  if (isset($_POST['viewCoupon'])):
      if (intval($_POST['viewCoupon']) == 0 || empty($_POST['viewCoupon'])):
          die();
      endif;
	  $id = intval($_POST['viewCoupon']);
	  $crow = $menu->ViewCouponMaster($id);
	 
      print '<div id="pollcontainer"><table class="forms">       
        <tbody>
          <tr>
            <th>title:</th>
            <td>'.$crow['title'].'</td>
          </tr>
          <tr>
            <th>noofcoupon:</th>
            <td>'.$crow['no_of_coupon'].'</td>
          </tr>
          <tr>
            <th>Typeofdiscount</th>
            <td>'.$crow['type_of_discount'].'</td>
          </tr>
          <tr>
            <th>Discount</th>
            <td>'.$crow['discount'].'</td>
          </tr>
		  <tr>
            <th>Amount Limit</th>
            <td>'.$crow['amount_limit'].'</td>
          </tr>
          <tr>
            <th>noofuseallowed</th>
            <td>'.$crow['no_of_use_allowed'].'</td>
          </tr>
          <tr>
            <th>usedno</th>
            <td>'.$crow['used_no'].'</td>
          </tr>
           
            <th>start_date</th>
            <td>'.$crow['start_date'].'</td>
          </tr>
          <tr>
            <th>end_date No</th>
            <td>'.$crow['end_date'].'</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>'.$crow['active'].'</td>
          </tr>   
		  <tr>
            <th>Description</th>
            <td>'.$crow['description'].'</td>
          </tr>         
        </tbody>
      </table></div>';
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteMenuOption']))
      : if (intval($_POST['deleteMenuOption']) == 0 || empty($_POST['deleteMenuOption']))
      : redirect_to("index.php?do=menu_option_master");
  endif;
  
  $id = intval($_POST['deleteMenuOption']);
  $res = $db->delete("res_menu_option_master", "option_id='" . $id . "'");
  $db->delete("res_menu_option_choice_master", "option_id='" . $id . "'");
  $db->delete("res_menu_option_topping_master", "option_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENU_OPTION .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MENU_OPTION .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Process  selectvalTopping  onchange topinge items process goes here  */
  if (isset($_POST['action'])):
  if($_POST['action']=='selectvalTopping'):
  if($_POST['prid']):
  	//redirect_to("index.php?do=menu_option_master");
  //endif;	
$menusizeid = explode("/", $_POST['prid']);
$MenuItemID =  $menusizeid[0]; // 1
if($menusizeid[1]==""){ $asql = ""; } else {  $asql = "&& msm.id = '".$menusizeid[1]."'";}
	 $sql = "SELECT mim.id ,mim.item_name ,msm.id AS MenuSizeMapID"
	 ."\n FROM res_menu_item_master AS mim"
	 ."\n LEFT JOIN res_menu_size_mapping AS msm ON msm.menu_item_id = mim.id"
	 ."\n WHERE mim.id = '".$MenuItemID."'" .$asql;			
	 $row = $db->first($sql);	
$data["item_id"] 		=  $row["id"];
$data["menu_sizemap_id"] 		=  $row["MenuSizeMapID"];
$data["item_name"] 		=  $row["item_name"];
echo json_encode($data);    
 endif;
 endif;
 endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deletemenuOptionItemMapping']))
      : if (intval($_POST['deletemenuOptionItemMapping']) == 0 || empty($_POST['deletemenuOptionItemMapping']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  
  $id = intval($_POST['deletemenuOptionItemMapping']);
  $db->delete("res_menu_option_item_mapping", "menu_option_map_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENU_OPTION .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MENU_OPTION .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteToppingItem']))
      : if (intval($_POST['deleteToppingItem']) == 0 || empty($_POST['deleteToppingItem']))
      : redirect_to("index.php?do=menu_option_master");
  endif;
  
  $id = intval($_POST['deleteToppingItem']);
  $db->delete("res_menu_option_topping_master", "option_topping_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_POST .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_POST .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deleteChoiceItem']))
      : if (intval($_POST['deleteChoiceItem']) == 0 || empty($_POST['deleteChoiceItem']))
      : redirect_to("index.php?do=menu_option_master");
  endif;
  
  $id = intval($_POST['deleteChoiceItem']);
  $db->delete("res_menu_option_choice_master", "option_choice_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MENU_CHOICE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MENU_CHOICE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deletesizeIteamMapping']))
      : if (intval($_POST['deletesizeIteamMapping']) == 0 || empty($_POST['deletesizeIteamMapping']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
  
  $id = intval($_POST['deletesizeIteamMapping']);
  $db->delete("res_menu_size_mapping", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_POST .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_POST .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php 
	if(isset($_POST['export_ordercsv']))
	{
		
        //$sql = $_POST['postQuery'] ;  //Here postQuery is a sql query is coming from $content->getorders($param)
		
		$content->is_homemod = $_POST['postQuery'] ; 
		  
		// $db_conn should be a valid db handle	
		$file = "csv/Export_Orders_" . date("d_M_Y_h_ia") . ".csv";		
		// output to file system
		$csv = $content->query_to_csvTest($content->is_homemod = $_POST['postQuery'], $file, false);		
		if($file)
		{
			print "<img src='".SITEURL."/admin/images/Excel-files-icon.png' height=\"50px;\"  />&nbsp;<a href=\"javascript:void(0);\" id=\"download_csv\">Download</a>";
			print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "
					."\n $(document).ready(function () { "
					."\n $('a#download_csv').click(function(e) {"
					."\n  e.preventDefault();  "
 					."\n window.location.href = '" . SITEURL . "/admin/" . $file . "';"
					."\n });"
					."\n });"
					."\n // ]]>"
					."\n </script>";
		} else { echo "System error to download."; }
		
		
	}
?>
<?php
  /* Delete deletePageMaster */
  if (isset($_POST['deletePageMaster']))
      : if (intval($_POST['deletePageMaster']) == 0 || empty($_POST['deletePageMaster']))
      : redirect_to("index.php?do=page_master");
  endif;
  
  $id = intval($_POST['deletePageMaster']);
  $db->delete("res_page_manager", "page_id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_PAGE_TITLE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_PAGE_TITLE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>

<?php
  /* Delete Content Post */
  if (isset($_POST['processemptygarbase']))
      : if (intval($_POST['processemptygarbase']) == 0 || empty($_POST['processemptygarbase']))
     : redirect_to("index.php?do=empty_garbase");
    endif; 
  	  $action = $db->query("TRUNCATE TABLE res_baskets"); 
      $action = $db->query("TRUNCATE TABLE res_basket_topping");
        
 	$_SESSION['thanks']="<h1>Garbase data has been Empty Succesfully</h1>";
  endif;
?>
<?php
  /* Delete deleteMenuIconMaster */
  if (isset($_POST['deleteMenuIconMaster']))
      : if (intval($_POST['deleteMenuIconMaster']) == 0 || empty($_POST['deleteMenuIconMaster']))
      : redirect_to("index.php?do=icon_manager");
  endif;
  
  $id = intval($_POST['deleteMenuIconMaster']);
  $title = sanitize($_POST['title']);
  
  $IconImage = getValue("special_item_icon", "res_menu_icon", "special_id = '".$id."'");	
    
	
	 if(is_file("../uploads/menu_icon/".$IconImage)){
		unlink("../uploads/menu_icon/".$IconImage);
	}
    $db->delete("res_menu_icon", "special_id='" . $id . "'");
  
  
  
  print ($db->affected()) ? $wojosec->writeLog(_PAGE_TITLE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_PAGE_TITLE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Update Display order for item Option  */
  if (isset($_POST['updateDisplayOrder']))
      : if (intval($_POST['updateDisplayOrder']) == 0 || empty($_POST['updateDisplayOrder']))
      : redirect_to("index.php?do=menu_item_master");
  endif;
		
	$data['display_order'] = $_POST['display_order'];
	$res = $db->update("res_menu_option_item_mapping", $data, "menu_option_map_id='" . (int)$_POST['menu_option_map_id'] . "'");
	print ($res) ? $core->msgOk(_MCAT_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>