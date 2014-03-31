<?php
  /**
   * Functions
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: functions.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  /**
   * redirect_to()
   * 
   * @param mixed $location
   * @return
   */
  function redirect_to($location)
  {
      if (!headers_sent()) {
          header('Location: ' . $location);
		  exit;
	  } else
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
  }
  
  /**
   * countEntries()
   * 
   * @param mixed $table
   * @param string $where
   * @param string $what
   * @return
   */
  function countEntries($table, $where = '', $what = '')
  {
      global $db;
      if (!empty($where) && isset($what)) {
          $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where . " = '" . $what . "' LIMIT 1";
      } else
          $q = "SELECT COUNT(*) FROM " . $table . " LIMIT 1";
      
      $record = $db->query($q);
      $total = $db->fetchrow($record);
      return $total[0];
  }
  
  /**
   * getChecked()
   * 
   * @param mixed $row
   * @param mixed $status
   * @return
   */
  function getChecked($row, $status)
  {
      if ($row == $status) {
          echo "checked=\"checked\"";
      }
  }
  
  /**
   * post()
   * 
   * @param mixed $var
   * @return
   */
  function post($var)
  {
      if (isset($_POST[$var]))
          return $_POST[$var];
  }
  
  /**
   * get()
   * 
   * @param mixed $var
   * @return
   */
  function get($var)
  {
      if (isset($_GET[$var]))
          return $_GET[$var];
  }
  
  /**
   * sanitize()
   * 
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function sanitize($string, $trim = false, $int = false, $str = false)
  {
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
      
	  if ($trim)
          $string = substr($string, 0, $trim);
      if ($int)
		  $string = preg_replace("/[^0-9\s]/", "", $string);
      if ($str)
		  $string = preg_replace("/[^a-zA-Z\s]/", "", $string);
		  
      return $string;
  }

  /**
   * cleanSanitize()
   * 
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function cleanSanitize($string, $trim = false,  $end_char = '&#8230;')
  {
	  $string = cleanOut($string);
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
      
	  if ($trim) {
        if (strlen($string) < $trim)
        {
            return $string;
        }

        $string = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $string));

        if (strlen($string) <= $trim)
        {
            return $string;
        }

        $out = "";
        foreach (explode(' ', trim($string)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $trim)
            {
                $out = trim($out);
                return (strlen($out) == strlen($string)) ? $out : $out.$end_char;
            }       
        }
	  }
      return $string;
  }

  /**
   * character_limiter()
   * 
   * @param mixed $str
   * @param int $n
   * @param mixed $end_char
   * @return
   */
  function character_limiter($str, $n = 100, $end_char = '&#8230;')
  {
	  if (strlen($str) < $n)
	  {
		  return $str;
	  }

	  $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	  if (strlen($str) <= $n)
	  {
		  return $str;
	  }

	  $out = "";
	  foreach (explode(' ', trim($str)) as $val)
	  {
		  $out .= $val.' ';

		  if (strlen($out) >= $n)
		  {
			  $out = trim($out);
			  return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		  }       
	  }
  }
 
  /**
   * getValue()
   * 
   * @param mixed $stwhatring
   * @param mixed $table
   * @param mixed $where
   * @return
   */
  function getValue($what, $table, $where)
  {
      global $db;
      $sql = "SELECT $what FROM $table WHERE $where";
      $row = $db->first($sql);
      return $row[$what];
  } 

  /**
   * getValueById()
   * 
   * @param mixed $what
   * @param mixed $table
   * @param mixed $id
   * @return
   */
  function getValueById($what, $table, $id)
  {
      global $db;
	  
      $sql = "SELECT $what FROM $table WHERE id = $id";
      $row = $db->first($sql);
      return ($row) ? $row[$what] : '';
  }
  
  /**
   * self()
   * 
   * @return
   */
  function self()
  {
      return htmlspecialchars($_SERVER['PHP_SELF']);
  }
  
  /**
   * tooltip()
   * 
   * @param mixed $tip
   * @return
   */
  function tooltip($tip, $front = false)
  {
	  $url = ($front) ? THEMEURL : ADMINURL;
	  
      return '<img src="' . $url . '/images/info2.png" alt="Tip" class="tooltip" title="' . $tip . '" />';
  }
  
  /**
   * required()
   * 
   * @return
   */
  function required($front = false)
  {
	  $url = ($front) ? THEMEURL : ADMINURL;
      return '<img src="' . $url . '/images/required.png" alt="'._REQ_FIELD.'" class="tooltip" title="'._REQ_FIELD.'" />';
  }

  /**
   * createPageLink()
   * 
   * @param mixed $slug
   * @return
   */
  function createPageLink($slug, $nourl = false)
  {
      global $db, $core;
	  
      $sql = "SELECT slug FROM pages WHERE slug = '".sanitize($slug,100)."'";
      $row = $db->first($sql);
      
      if ($core->seo == 1) {
		  $display = ($nourl) ? $row['slug'].'.html' : SITEURL . '/' . $row['slug'].'.html';
      } else {
		  $display = ($nourl) ? 'content.php?pagename=' . $row['slug'] : SITEURL . '/content.php?pagename=' . $row['slug'];
      }
      return $display;
  }
  
  /**
   * stripTags()
   * 
   * @param mixed $start
   * @param mixed $end
   * @param mixed $string
   * @return
   */
  function stripTags($start, $end, $string)
  {
	  $string = stristr($string, $start);
	  $doend = stristr($string, $end);
	  return substr($string, strlen($start), -strlen($doend));
  }
  
  /**
   * getTemplates()
   * 
   * @param mixed $dir
   * @param mixed $site
   * @return
   */
  function getTemplates($dir, $site)
  {
      $getDir = dir($dir);
      while (false !== ($templDir = $getDir->read())) {
          if ($templDir != "." && $templDir != ".." && $templDir != "index.php") {
              $selected = ($site == $templDir) ? " selected=\"selected\"" : "";
              echo "<option value=\"{$templDir}\"{$selected}>{$templDir}</option>\n";
          }
      }
      $getDir->close();
  }
  
  /**
   * stripExt()
   * 
   * @param mixed $filename
   * @return
   */ 
  function stripExt($filename)
  {
      if (strpos($filename, ".") === false) {
          return ucwords($filename);
      } else
          return substr(ucwords($filename), 0, strrpos($filename, "."));
  }
  
  /**
   * loadEditor()
   * 
   * @param mixed $field
   * @param mixed $value
   * @param mixed $width
   * @param mixed $height
   * @param mixed $toolbar
   * @param mixed $var
   * @return
   */
  function loadEditor($field, $width = "100%", $height = "450", $var = "oEdit1")
  {
	  print '
		  <script type="text/javascript">
		    // <![CDATA[
			var '.$var.' = new InnovaEditor("'.$var.'");
			'.$var.'.width="'.$width.'";
			'.$var.'.height='.$height.';
			'.$var.'.enableFlickr = false;
			'.$var.'.enableCssButtons = false;
			'.$var.'.flickrUser = "";
			'.$var.'.returnKeyMode = 2;
			'.$var.'.arrCustomButtons = [
			["CustomName1","modalDialog(\'editor/scripts/common/paypal.htm\',350,270)","PayPal Button","btnPayPal.gif"],
			["HTML5Video", "modalDialog(\'editor/scripts/common/webvideo.htm\',750,550,\'HTML5 Video\');", "HTML5 Video", "btnVideo.png"]
			];
			'.$var.'.toolbarMode = 2;
			'.$var.'.groups=[
			["grpEdit", "", ["SourceDialog", "FullScreen", "SearchDialog", "RemoveFormat", "BRK", "Undo", "Redo", "Cut", "Copy", "Paste"]],
			["grpFont", "", ["FontName", "FontSize", "Strikethrough", "Superscript", "BRK", "Bold", "Italic", "Underline", "ForeColor", "BackColor"]],
			["grpPara", "", ["CompleteTextDialog", "Quote", "Indent", "Outdent", "Styles", "StyleAndFormatting", "Absolute", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
			["grpInsert", "", ["LinkDialog", "BRK", "ImageDialog", "Form"]],
			["grpTables", "", ["TableDialog", "BRK", "Guidelines", "Guidelines", "CustomName1"]],
			["grpMedia", "", ["Media", "FlashDialog", "YoutubeDialog", "HTML5Video", "BRK", "CustomTag", "CharsDialog", "Line"]]
			];
			
			'.$var.'.css="'.THEMEURL.'/css/custom.css";
			'.$var.'.fileBrowser = "'.SITEURL.'/admin/editor/filemanager.php";
			'.$var.'.arrCustomTag=[
			["First Last Name","[NAME]"],
			["Username","[USERNAME]"],
			["Site Name","[SITE_NAME]"],
			["Site Url","[URL]"]
			];
			'.$var.'.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];
			'.$var.'.mode="XHTMLBody";
			'.$var.'.REPLACE("'.$field.'");
			// ]]>
		  </script>
		  ';
  }

  /**
   * cleanOut()
   * 
   * @param mixed $text
   * @return
   */
  function cleanOut($text) {
	 $text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
	 $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	 $text = str_replace('<br>', '<br />', $text);
	 return stripslashes($text);
  }
    
  /**
   * isActive()
   * 
   * @param mixed $id
   * @return
   */
  function isActive($id)
  {
	  if ($id == 1) {
		  $display = '<img src="images/yes.png" alt="" class="tooltip" title="'._PUBLISHED.'"/>';
	  } else {
		  $display = '<img src="images/no.png" alt="" class="tooltip" title="'._NOTPUBLISHED.'"/>';
	  }

      return $display;;
  }

  /**
   * isAdmin()
   * 
   * @param mixed $id
   * @return
   */
  function isAdmin($userlevel)
  {
	  if ($userlevel == 9) {
		  $display = '<img src="images/superadmin.png" alt="" class="tooltip" title="Super Admin"/>';
	  } elseif ($userlevel == 8) {
		  $display = '<img src="images/admin.png" alt="" class="tooltip" title="Admin"/>';
	  } else {
		  $display = '<img src="images/user.png" alt="" class="tooltip" title="User"/>';
	  }

      return $display;;
  }

  /**
   * userStatus()
   * 
   * @param mixed $id
   * @return
   */
  function userStatus($status)
  {
	  switch ($status) {
		  case "y":
			  $display = '<img src="images/u_active.png" alt="" class="tooltip" title="'._USER_A.'"/>';
			  break;
			  
		  case "n":
			  $display = '<img src="images/u_inactive.png" alt="" class="tooltip" title="'._USER_I.'"/>';
			  break;
			  
		  case "t":
			  $display = '<img src="images/u_pending.png" alt="" class="tooltip" title="'._USER_P.'"/>';
			  break;
			  
		  case "b":
			  $display = '<img src="images/u_banned.png" alt="" class="tooltip" title="'._USER_B.'"/>';
			  break;
	  }
	  
      return $display;;
  }
  
  /**
   * delete_directory()
   * 
   * @param mixed $dirname
   * @return
   */ 
	function delete_directory($dirname) {
	   if (is_dir($dirname))
		  $dir_handle = opendir($dirname);
	   if (!$dir_handle)
		  return false;
	   while($file = readdir($dir_handle)) {
		  if ($file != "." && $file != "..") {
			 if (!is_dir($dirname."/".$file))
				@unlink($dirname."/".$file);
			 else
				delete_directory($dirname.'/'.$file);    
		  }
	   }
	   closedir($dir_handle);
	   @rmdir($dirname);
	   return true;
	}

  /**
   * randName()
   * 
   * @return
   */ 
  function randName() {
	  $code = '';
	  for($x = 0; $x<6; $x++) {
		  $code .= '-'.substr(strtoupper(sha1(rand(0,999999999999999))),2,6);
	  }
	  $code = substr($code,1);
	  return $code;
  }
        
  /**
   * checkDir()
   * 
   * @param mixed $dir
   * @return
   */ 
  function checkDir($dir)
  {
      if (!is_dir($dir)) {
          echo "path does not exist<br/>";
          $dirs = explode('/', $dir);
          $tempDir = $dirs[0];
          $check = false;
          
          for ($i = 1; $i < count($dirs); $i++) {
              echo " Checking " . $tempDir . "<br/>";
              if (is_writeable($tempDir)) {
                  $check = true;
              } else {
                  $error = $tempDir;
              }
              
              $tempDir .= '/' . $dirs[$i];
              if (!is_dir($tempDir)) {
                  if ($check) {
                      echo " Creating " . $tempDir . "<br/>";
                      @mkdir($tempDir, 0755);
                      @chmod($tempDir, 0755);
                  }
                  else
                      echo " Not enough permissions";
              }
          }
      }
  }

  /**
   * getSize()
   * 
   * @param mixed $size
   * @param integer $precision
   * @param bool $long_name
   * @param bool $real_size
   * @return
   */
  function getSize($size, $precision = 2, $long_name = false, $real_size = true)
  {
      $base = $real_size ? 1024 : 1000;
      $pos = 0;
      while ($size > $base) {
          $size /= $base;
          $pos++;
      }
      $prefix = _getSizePrefix($pos);
      $size_name = $long_name ? $prefix . "bytes" : $prefix[0] . 'B';
      return round($size, $precision) . ' ' . ucfirst($size_name);
  }

  /**
   * _getSizePrefix()
   * 
   * @param mixed $pos
   * @return
   */  
  function _getSizePrefix($pos)
  {
      switch ($pos) {
          case 00:
              return "";
          case 01:
              return "kilo";
          case 02:
              return "mega";
          case 03:
              return "giga";
          case 04:
              return "tera";
          default:
              return "?-";
      }
  }
  
  /**
   * dodate()
   * 
   * @param mixed $format
   * @param mixed $date
   * @return
   */  
  function dodate($format, $date) {
	  
	return strftime($format, strtotime($date));
  } 
  
  /**
   * getTime()
   * 
   * @return
   */ 
  function getTime() {
	  $timer = explode( ' ', microtime() );
	  $timer = $timer[1] + $timer[0];
	  return $timer;
  }
  
  function getCoords($str)
	{
		if (!is_array($str))
		{
			$_address = preg_replace('/\s+/', '+', $str);
			$_address = urlencode($_address);
		} else {
			$address = array();
			$address[] = $str['d_zip'];
			$address[] = $str['d_address_1'];
			$address[] = $str['d_city'];
			$address[] = $str['d_state'];
	
			foreach ($address as $k => $v)
			{
				$tmp = preg_replace('/\s+/', '+', $v);
				$address[$k] = $tmp;
			}
			$_address = join(",+", $address);
		}
							
		$url = sprintf("http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false", $_address);
		
		//$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=India";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$geoObj = json_decode($response);
				
		$data = array();
		if ($geoObj->status == 'OK')
		{
			$data['lat'] = $geoObj->results[0]->geometry->location->lat;
			$data['lng'] = $geoObj->results[0]->geometry->location->lng;
		} else {
			$data['lat'] = '1.000000';
			$data['lng'] = '1.000000';
		}
		return $data;
	}
	
	function query_to_csvPrevious($query, $filename, $attachment = false, $headers = true)
	{
	   require_once(WOJOLITE . "lib/class_db.php");		  
		   $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
		   $db->connect();
		
		   $connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die('Oops connection error -> ' . mysql_error());
		   mysql_select_db(DB_DATABASE, $connection) or die('Database error -> ' . mysql_error());
		   
		if($attachment) {
			// send response headers to the browser
			header( 'Content-Type: text/csv' );
			header( 'Content-Disposition: attachment;filename='.$filename);
			$fp = fopen('php://output', 'w');
		} else {
			$fp = fopen($filename, 'w');
		}
	   
		$result = mysql_query($query) or die( mysql_error() );
	   
		if($headers) {
			// output header row (if at least one row exists)
			$row = mysql_fetch_assoc($result);
			if($row) {
				fputcsv($fp, array_keys($row));
				// reset pointer back to beginning
				mysql_data_seek($result, 0);
			}
		}
	   
		while($row = mysql_fetch_assoc($result)) {
			fputcsv($fp, $row);
		}
	   
		fclose($fp);
	}
	
	
	
	function query_to_csv($query, $filename,$filetype, $attachment = false, $headers = true)
	{
	  require_once(WOJOLITE . "lib/class_db.php");		  
	  $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	  $db->connect();
	  
	   $connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die('Oops connection error -> ' . mysql_error());
	   mysql_select_db(DB_DATABASE, $connection) or die('Database error -> ' . mysql_error());
		
	if($attachment) {
		// send response headers to the browser
		header('Content-Type: application/$filetype');		
		header( 'Content-Disposition: attachment;filename='.$filename);
		header('Pragma: no-cache');
		header('Expires: 0');
		$fp = fopen('php://output', 'w');
	} else {
		$fp = fopen($filename, 'w');
	}
   
	$result = mysql_query($query) or die( mysql_error() );
   
	if($headers) {
		// output header row (if at least one row exists)
		$row = mysql_fetch_assoc($result);
		if($row) {
			fputcsv($fp, array_keys($row));
			// reset pointer back to beginning
			mysql_data_seek($result, 0);
		}
	}
   
	while($row = mysql_fetch_assoc($result)) {
		fputcsv($fp, $row);
	}
   
	fclose($fp);
 }
 
 function query_to_csvTest($query, $filename,$filetype, $attachment = false, $headers = true)
	{
	  require_once(WOJOLITE . "lib/class_db.php");		  
	  $db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	  $db->connect();	 
	  	
	  $connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS) or die('Oops connection error -> ' . mysql_error());
	  mysql_select_db(DB_DATABASE, $connection) or die('Database error -> ' . mysql_error());	  
	 		
	if($attachment) {
		// send response headers to the browser
		header('Content-Type: application/$filetype');		
		header( 'Content-Disposition: attachment;filename='.$filename);
		header('Pragma: no-cache');
		header('Expires: 0');
		$fp = fopen('php://output', 'w');
	} else {
		$fp = fopen($filename, 'w');
	}
   
      
	$result = mysql_query($query) or die( mysql_error() );
    
	if($headers) {
		// output header row (if at least one row exists)
		$row = mysql_fetch_array($result);
		if($row) {
			fputcsv($fp, array_keys($row));
			// reset pointer back to beginning
			mysql_data_seek($result, 0);
		}
	}
   
	while($row = mysql_fetch_array($result)) {
		fputcsv($fp, $row);
	}
   
	fclose($fp);
 }
 
 // Generate Guid 
function NewGuid($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}
// End Generate Guid 
function query_to_excel($query, $filename, $attachment = false, $headers = true)
{
global $db;
   /*$connection = mysql_connect("localhost", "root", "") or die('Oops connection error -> ' . mysql_error());
        mysql_select_db("onlinefoodorder", $connection) or die('Database error -> ' . mysql_error());*/
	if($attachment) {
		// send response headers to the browser
		//header info for browser
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=$filename.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$fp = fopen('php://output', 'w');
	} else {
		$fp = fopen($filename, 'w');
	}
   
	$result = mysql_query($query) or die( mysql_error() );
   
	if($headers) {
	/*******Start of Formatting for Excel*******/
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
 
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
     mysql_field_name($result,$i) . "\t";
}
print("\n");
//end of printing column names
 
//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
    }
	}
   
	fclose($fp);
}
function round_to_2dp($number)
{
return number_format((float)$number, 2, '.', '');

}
	/*function create_time_range($start, $end, $by='15 mins') {
	
		$start_time = strtotime($start);
		$end_time   = strtotime($end);
	
		$current    = time();
		$add_time   = strtotime('+'.$by, $current);
		$diff       = $add_time-$current;
	
		$times = array();
		while ($start_time < $end_time) {
			$times[] = date('g:i A', $start_time) . " - " . date('g:i A', ($start_time+$diff));
			$start_time += $diff;
		}
		//$times[] = date('g:i A', $start_time);
		return $times;
	}*/
	
	
	function create_time_range($start, $end, $by='15 mins') {
	
		$start_time = strtotime($start);
		$end_time   = strtotime($end);
	
		$current    = time();
		$add_time   = strtotime('+'.$by, $current);
		$diff       = $add_time-$current;
	
		$times = array();
		while ($start_time < $end_time) {
			$times[] = $start_time;
			$start_time += $diff;
		}
		$times[] = $start_time;
		return $times;
	}
		
	function emailattached($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
			$file = $path.$filename;
			$file_size = filesize($file);
			$handle = fopen($file, "r");
			$content = fread($handle, $file_size);
			fclose($handle);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
			$name = basename($file);
			$header = "From: ".$from_name." <".$from_mail.">\r\n";
			$header .= "Reply-To: ".$replyto."\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $message."\r\n\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			$header .= $content."\r\n\r\n";
			$header .= "--".$uid."--";
			if (mail($mailto, $subject, "", $header)) {
				//echo "mail send ... OK"; // or use booleans here
			} else {
				echo "mail send ... ERROR!";
			}
	}
	function removespecialcharacters($str)
	{
		 //$string = str_replace(' ', '-', $str); // Replaces all spaces with hyphens.
   		$newstr= preg_replace("/[^A-Za-z0-9\(). -]/", "", html_entity_decode($str)); 
		return $newstr;
		//return preg_replace('/[^A-Za-z0-9\. -]/', '', $str);
	}
	
	function getDayfullname($dayscurent)
    {
		switch ($dayscurent) {
			case "Mon":
				$nextday = 'Monday';
				break;
			case "Tue":
				$nextday = 'Tuesday';
				break;
			case "Wed":
				$nextday = 'Wednesday';
				break;
			case "Thu":
				$nextday = 'Thursday';
				break;
			case "Fri":
				$nextday = 'Friday';
				break;
			case "Sat":
				$nextday = 'Saturday';
				break;
			case "Sun":
				$nextday = 'Sunday';
				break;
				}
		
		return $nextday;
  }
  function date_difference ($date1timestamp, $date2timestamp) {
		$all = round(($date1timestamp - $date2timestamp) / 60);
		$d = floor ($all / 1440);
		$h = floor (($all - $d * 1440) / 60);
		$m = $all - ($d * 1440) - ($h * 60);
		//Since you need just hours and mins
		return array('hours'=>$h, 'mins'=>$m);
}
function Special_Char_convert($str)
{
$entities = array(
    'À'=>'&Agrave;',
    'à'=>'&agrave;',
    'Á'=>'&Aacute;',
    'á'=>'&aacute;',
    'Â'=>'&Acirc;',
    'â'=>'&acirc;',
    'Ã'=>'&Atilde;',
    'ã'=>'&atilde;',
    'Ä'=>'&Auml;',
    'ä'=>'&auml;',
    'Å'=>'&Aring;',
    'å'=>'&aring;',
    'Æ'=>'&AElig;',
    'æ'=>'&aelig;',
    'Ç'=>'&Ccedil;',
    'ç'=>'&ccedil;',
    '?'=>'&ETH;',
    '?'=>'&eth;',
    'È'=>'&Egrave;',
    'è'=>'&egrave;',
    'É'=>'&Eacute;',
    'é'=>'&eacute;',
    'Ê'=>'&Ecirc;',
    'ê'=>'&ecirc;',
    'Ë'=>'&Euml;',
    'ë'=>'&euml;',
    'Ì'=>'&Igrave;',
    'ì'=>'&igrave;',
    'Í'=>'&Iacute;',
    'í'=>'&iacute;',
    'Î'=>'&Icirc;',
    'î'=>'&icirc;',
    'Ï'=>'&Iuml;',
    'ï'=>'&iuml;',
    'Ñ'=>'&Ntilde;',
    'ñ'=>'&ntilde;',
    'Ò'=>'&Ograve;',
    'ò'=>'&ograve;',
    'Ó'=>'&Oacute;',
    'ó'=>'&oacute;',
    'Ô'=>'&Ocirc;',
    'ô'=>'&ocirc;',
    'Õ'=>'&Otilde;',
    'õ'=>'&otilde;',
    'Ö'=>'&Ouml;',
    'ö'=>'&ouml;',
    'Ø'=>'&Oslash;',
    'ø'=>'&oslash;',
    'Œ'=>'&OElig;',
    'œ'=>'&oelig;',
    'ß'=>'&szlig;',
    '?'=>'&THORN;',
    '?'=>'&thorn;',
    'Ù'=>'&Ugrave;',
    'ù'=>'&ugrave;',
    'Ú'=>'&Uacute;',
    'ú'=>'&uacute;',
    'Û'=>'&Ucirc;',
    'û'=>'&ucirc;',
    'Ü'=>'&Uuml;',
    'ü'=>'&uuml;',
    '?'=>'&Yacute;',
    '?'=>'&yacute;',
    'Ÿ'=>'&Yuml;',
	'&'=>'&amp;',
    'ÿ'=>'&yuml;',
	'©'=>'&copy;',
	'®'=>'&reg;',
	'€'=>'&euro;',
	'™'=>'&trade;'
);
$str = str_replace(array_values($entities), array_keys($entities), $str);
return $str;
}
?>