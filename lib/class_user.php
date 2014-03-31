<?php
  /**
   * User Class
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: class_user.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Users
  {
	  private $uTable = "res_users_master";
	  public $logged_in = null;
	  public $uid = 0;	
	  public $userid = 0;
      public $username;
	  public $sesid;
	  public $email;
	  public $name;     
	  public $access = null;	
      public $userlevel;
	  private $lastlogin = "NOW()";      

      /**
       * Users::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getUserId();
		  $this->startSession();
      }

	  /**
	   * Users::getUserId()
	   * 
	   * @return
	   */
	  private function getUserId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['userid'])) {
			  $userid = (is_numeric($_GET['userid']) && $_GET['userid'] > -1) ? intval($_GET['userid']) : false;
			  $userid = sanitize($userid);
			  
			  if ($userid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Users::getUserId()") : $core->ooops();
			  } else
				  return $this->userid = $userid;
		  }
	  }  

      /**
       * Users::startSession()
       * 
       * @return
       */
      private function startSession()
      {
		if (strlen(session_id()) < 1)
			session_start();
	  
		$this->logged_in = $this->loginCheck();
		
		if (!$this->logged_in) {
			$this->username = $_SESSION['username'] = "Guest";
			$this->sesid = sha1(session_id());
			$this->userlevel = 0;
		}
      }

	  /**
	   * Users::loginCheck()
	   * 
	   * @return
	   */
	  private function loginCheck()
	  {
          if (isset($_SESSION['username']) && $_SESSION['username'] != "Guest") {
              
              $row = $this->getUserInfo($_SESSION['username']);
			  $this->uid = $row['id'];
              $this->username = $row['username'];
			  $this->email = $row['email'];			  
              $this->userlevel = $row['userlevel'];
			  $this->access = $row['access'];			 
			  $this->sesid = sha1(session_id());
              return true;
          } else {
              return false;
          }  
	  }

	  /**
	   * Users::is_Admin()
	   * 
	   * @return
	   */
	  public function is_Admin()
	  {
		  return($this->userlevel == 9 or $this->userlevel == 8);
	  
	  }	

	  /**
	   * Users::login()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function login($username, $password)
	  {
		  global $db, $core, $wojosec;
		  
		  $timeleft = null;
		  /*if (!$wojosec->loginAgain($timeleft)) {
			  $minutes = ceil($timeleft / 60);
			  $core->msgs['username'] = str_replace("%MINUTES%", $minutes, _LG_BRUTE_RERR);
		  } else*/
		  if ($username == "" && $password == "") {
			  $core->msgs['username'] = _LG_ERROR1;
		  } else {
			  $status = $this->checkStatus($username, $password);
			  
			  switch ($status) {
				  case 0:
					  $core->msgs['username'] = _LG_ERROR2;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 1:
					  $core->msgs['username'] = _LG_ERROR3;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 2:
					  $core->msgs['username'] = _LG_ERROR4;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 3:
					  $core->msgs['username'] = _LG_ERROR5;
					  $wojosec->setFailedLogin();
					  break;
			  }
		  }
		  if (empty($core->msgs) && $status == 5) {
			  $row = $this->getUserInfo($username);
			  $this->uid = $_SESSION['uid'] = $row['id'];			
			  $this->username = $_SESSION['username'] = $row['username'];
			  $this->email = $_SESSION['email'] = $row['email'];
			  $this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];			
			  $this->access = $_SESSION['access'] = $row['access'];

			  $data = array(
					'lastlogin' => $this->lastlogin, 
					'lastip' => sanitize($_SERVER['REMOTE_ADDR'])
			  );
			  $db->update($this->uTable, $data, "username='" . $this->username . "'");
			 /* if(!$this->validateMembership()) {
				$data = array(
					  'membership_id' => 0, 
					  'mem_expire' => "0000-00-00 00:00:00"
				);
				$db->update($this->uTable, $data, "username='" . $this->username . "'");
			  }*/
				  
			  return true;
		  } else
			  $core->msgStatus();
	  }

      /**
       * Users::logout()
       * 
       * @return
       */
      public function logout()
      {
          unset($_SESSION['username']);
		  unset($_SESSION['email']);
		  unset($_SESSION['name']);         
		  unset($_SESSION['access']);
          unset($_SESSION['uid']);		
          session_destroy();
		  session_regenerate_id();
          
          $this->logged_in = false;
          $this->username = "Guest";
          $this->userlevel = 0;
      }
	 
	  
	  /**
	   * Users::getUserInfo()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function getUserInfo($username)
	  {
		  global $db;
		  $username = sanitize($username);
		  $username = $db->escape($username);
		  
		  $sql = "SELECT * FROM " . $this->uTable . " WHERE username = '" . $username . "'";
		  $row = $db->first($sql);
		  if (!$username)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::getUserFBInfo()
	   * 
	   * @param mixed $fbid
	   * @return
	   */
	  private function getUserFBInfo($fbid)
	  {
		  global $db;
		  $fbid = sanitize($fbid);
		  $fbid = $db->escape($fbid);
		  
		  $sql = "SELECT * FROM " . $this->uTable . " WHERE fbid = '" . $fbid . "'";
		  $row = $db->first($sql);
		  if (!$fbid)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Users::checkStatus()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function checkStatus($username, $password)
	  {
		  global $db;
		  
		  $username = sanitize($username);
		  $username = $db->escape($username);
		  $password = sanitize($password);
		  
          $sql = "SELECT password, active FROM " . $this->uTable
		  . "\n WHERE username = '".$username."'";
          $result = $db->query($sql);
          
		  if ($db->numrows($result) == 0)
			  return 0;
			  
		  $row = $db->fetch($result);
		  $entered_pass = sha1($password);
		  
		  switch ($row['active']) {
			  case "b":
				  return 1;
				  break;
				  
			  case "n":
				  return 2;
				  break;
				  
			  case "t":
				  return 3;
				  break;
				  
			  case "y" && $entered_pass == $row['password']:
				  return 5;
				  break;
		  }
	  }

	  /**
	   * Users::getUsers()
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function getUsers($from = false)
	  {
		  global $db, $pager, $core;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries($this->uTable);
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
          $sql = "SELECT *"		 
		  . "\n FROM " . $this->uTable . ""  
		  . "\n ORDER BY id" . $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::processUser()
	   * 
	   * @return
	   */
	  public function processUser()
	  {
		  global $db, $core, $wojosec;

		  if (!$this->userid) {
			  if (empty($_POST['username']))
				  $core->msgs['username'] = _UR_USERNAME_R;
			  
			  if ($value = $this->usernameExists($_POST['username'])) {
				  if ($value == 1)
					  $core->msgs['username'] = _UR_USERNAME_R1;
				  if ($value == 2)
					  $core->msgs['username'] = _UR_USERNAME_R2;
				  if ($value == 3)
					  $core->msgs['username'] = _UR_USERNAME_R3;
			  }
		  }
		 			  
		  if (!$this->userid) {
			  if (empty($_POST['password']))
				  $core->msgs['password'] = _UR_PASSWORD_R;
		  }
 
		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;
		  if (!$this->userid) {
			  if ($this->emailExists($_POST['email']))
				  $core->msgs['email'] = _UR_EMAIL_R1;
		  }
		  if (!$this->isValidEmail($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R2;		 
		  
		  if (empty($_POST['location_id']))
				  $core->msgs['location_id'] =_INS_LOCA;	
		  if (!empty($_POST['location_id'])){
		  		$locationid = implode(",",$_POST['location_id']);
		  	}
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'username' => sanitize($_POST['username']), 
				  'email' => sanitize($_POST['email']), 				  
				  'location_id' => sanitize($locationid),
				  'userlevel' => intval($_POST['userlevel']), 
				  'active' => sanitize($_POST['active'])
			  );

			  if (isset($_POST['access'])) {
				  $accs = $_POST['access'];
				  $total = count($accs);
				  $i = 1;
				  if (is_array($accs)) {
					  $adata = '';
					  foreach ($accs as $acc) {
						  if ($i == $total) {
							  $adata .= $acc;
						  } else
							  $adata .= $acc . ",";
						  $i++;
					  }
				  }
				  $data['access'] = $adata;
			  } else
				  $data['access'] = "NULL";
				  
			  if (!$this->userid)
				  $data['created'] = "NOW()";
				   
			  if ($this->userid)
				  $userrow = $core->getRowById($this->uTable, $this->userid);
			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userrow['password'];			  
			 
			  ($this->userid) ? $db->update($this->uTable, $data, "id='" . (int)$this->userid . "'") : $db->insert($this->uTable, $data);
			  $message = ($this->userid) ? _UR_UPDATED : _UR_ADDED;

			  if ($db->affected()) {
				  $core->msgOk($message);
				  $wojosec->writeLog($message, "", "no", "content");
				  
				  /*if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
					  
					  require_once(WOJOLITE . "lib/class_mailer.php");
					  $mailer = $mail->sendMail();	
								  
					  $row = $core->getRowById("email_templates", 3);
					  
					  $body = str_replace(array('[USERNAME]', '[PASSWORD]', '[NAME]', '[SITE_NAME]', '[URL]'), 
					  array($data['username'], $_POST['password'], $data['fname'].' '.$data['lname'], $core->site_name, $core->site_url), $row['body'.$core->dblang]);
			
					  $message = Swift_Message::newInstance()
								->setSubject($row['subject'.$core->dblang])
								->setTo(array($data['email'] => $data['fname'].' '.$data['lname']))
								->setFrom(array($core->site_email => $core->site_name))
								->setBody(cleanOut($body), 'text/html');
								
					   $mailer->send($message);
				  }*/
			  } else
				  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 
	      
	  
      /**
       * User::activateUser()
       * 
       * @return
       */
	  public function activateUser()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;
		  
		  if (!$this->emailExists($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R3;
		  
		  if (empty($_POST['token']))
			  $core->msgs['token'] = _UA_TOKEN_R1;
		  
		  if (!$this->validateToken($_POST['token']))
			  $core->msgs['token'] = _UA_TOKEN_R;
		  
		  if (empty($core->msgs)) {
			  $email = sanitize($_POST['email']);
			  $token = sanitize($_POST['token']);
			  $message = ($core->auto_verify == 1) ? _UA_TOKEN_OK1 : _UA_TOKEN_OK2;
			  
			  $data = array(
					'token' => 0, 
					'active' => ($core->auto_verify) ? "y" : "n"
			  );
			  
			  $db->update($this->uTable, $data, "email = '" . $email . "' AND token = '" . $token . "'");
			  ($db->affected()) ? $core->msgOk($message,false) : $core->msgError(_UA_TOKEN_R_ERR,false);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Users::getUserData()
	   * 
	   * @return
	   */
	  public function getUserData()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as cdate,"
		  . "\n DATE_FORMAT(lastlogin, '" . $core->long_date . "') as ldate"
		  . "\n FROM " . $this->uTable
		  . "\n WHERE id = '" . $this->uid . "'";
		  $row = $db->first($sql);

		  return ($row) ? $row : 0;
	  }		  
	  	  	  	  
	  /**
	   * Users::usernameExists()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function usernameExists($username)
	  {
		  global $db;
		  
		  $username = sanitize($username);
		  if (strlen($db->escape($username)) < 4)
			  return 1;
		  
		  $alpha_num = str_replace(" ", "", $username);
		  if (!ctype_alnum($alpha_num))
			  return 2;
		  
		  $sql = $db->query("SELECT username" 
		  . "\n FROM res_users_master" 
		  . "\n WHERE username = '" . $username . "'" 
		  . "\n LIMIT 1");
		  
		  $count = $db->numrows($sql);
		  
		  return ($count > 0) ? 3 : false;
	  }  	
	  
	  /**
	   * User::emailExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function emailExists($email)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT email" 
		  . "\n FROM res_users_master" 
		  . "\n WHERE email = '" . sanitize($email) . "'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
	   * User::isValidEmail()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function isValidEmail($email)
	  {
		  if (function_exists('filter_var')) {
			  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  return true;
			  } else
				  return false;
		  } else
			  return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
	  } 	

      /**
       * User::validateToken()
       * 
       * @param mixed $token
       * @return
       */
     private function validateToken($token)
      {
          global $db;
          $token = sanitize($token,40);
          $sql = "SELECT token" 
		  . "\n FROM ".$this->uTable 
		  . "\n WHERE token ='" . $db->escape($token) . "'" 
		  . "\n LIMIT 1";
          $result = $db->query($sql);
          
          if ($db->numrows($result))
              return true;
      }
	  
	  /**
	   * Users::getUniqueCode()
	   * 
	   * @param string $length
	   * @return
	   */
	  private function getUniqueCode($length = "")
	  {
		  $code = md5(uniqid(rand(), true));
		  if ($length != "") {
			  return substr($code, 0, $length);
		  } else
			  return $code;
	  }

	  /**
	   * Users::generateRandID()
	   * 
	   * @return
	   */
	  private function generateRandID()
	  {
		  return sha1($this->getUniqueCode(24));
	  }

      /**
       * Users::getPermissionList()
       * 
       * @param bool $access
       * @return
       */
	  public function getPermissionList($access = false)
	  {
		  global $db, $core;  
		  
		  $data = '';
		  
		  if ($access) {
			  $arr = explode(",", $access);
			  reset($arr);
		  }
		  
		  $data .= '<select name="access[]" size="10" multiple="multiple" class="select" style="width:250px">';
		  foreach ($this->getPermissionValues() as $key => $val) {
			  if ($access && $arr) {
				  $selected = (in_array($key, $arr)) ? " selected=\"selected\"" : "";
			  } else 
				  $selected = null;
			  $data .= "<option $selected value=\"" . $key . "\">" . $val . "</option>\n";
		  }
		  unset($val);	  
		  
		  $data .= "</select>";
		  $data .= "&nbsp;&nbsp;";
		  $data .= tooltip(_UR_PERM_T);
		  
		  return $data;
	  } 

	  /**
	   * Users::getAcl()
	   * 
	   * @param string $content
	   * @return
	   */
	  public function getAcl($content)
	  {
	  
		  if ($this->userlevel == 8) {
			  $arr = explode(",", $this->access);
			  reset($arr);
			  
			  if (in_array($content, $arr)) {
				  return true;
			  } else
				  return false;
		  } else
			  return true;
	  }
	  	  
      /**
       * Users::getPermissionValues()
       * 
       * @return
       */
      private function getPermissionValues()
	  {
		  $arr = array(
				 'Customer' => _CMM_MANAGER,
				 'Location Manager' => _LM_MANAGER,
				 'Location' =>  '-- '._LOCATION,
				 'Location Timing' => '-- '._LTM_MANAGER,
				 'Holidays' =>  '-- '._HM_MANAGER,
				 'DeliveryArea' =>  '-- '._DLI_,
				 'Menu Manager'=> _N_MENU,
				 'MenuCategory' => '-- '._MCAT_MENUMAPPING,
				 'MenuItem' => '-- '._MITEM_MITEM				 
		  );

		  return $arr;
	  } 
	  	    	  	  
      /**
       * Users::getUserFilter()
       * 
       * @return
       */
      public function getUserFilter()
	  {
		  $arr = array(
				 'username-ASC' => _USERNAME . ' &uarr;',
				 'username-DESC' => _USERNAME . ' &darr;',
				 'fname-ASC' => _UR_FNAME . ' &uarr;',
				 'fname-DESC' => _UR_FNAME . ' &darr;',
				 'lname-ASC' => _UR_LNAME . ' &uarr;',
				 'lname-DESC' => _UR_LNAME . ' &darr;',
				 'email-ASC' => _UR_EMAIL . ' &uarr;',
				 'email-DESC' => _UR_EMAIL . ' &darr;',
				 'membership_id-ASC' => _MEMBERSHIP . ' &uarr;',
				 'membership_id-DESC' => _MEMBERSHIP . ' &darr;',
				 'created-ASC' => _UR_DATE_REGGED . ' &uarr;',
				 'created-DESC' => _UR_DATE_REGGED . ' &darr;',
		  );
		  
		  $filter = '';
		  foreach ($arr as $key => $val) {
				  if ($key == get('sort')) {
					  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
				  } else
					  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  } 
	  
	  /**
       * Users::getLocationList()
       * 
       * @param bool $access
       * @return
       */
	  public function LocationDropDown($selected = null)
	  {
		   	global $db, $core ;
			
			$delivery_str="";
			
            $sql = " SELECT * FROM res_location_master  WHERE active = '1'";			
            $result = $db->fetch_all($sql);
			
            if ($result)
            {
        		foreach ($result as $row)
                {
                     $id = $row['id'];
                     $location  = $row['location_name'];
					 
                     print $option_str = "<optgroup label='".$location ."' value='".$id."'>".$location ."</optgroup>";
                     $option_str = $this->deliveryDropList($id,$selected);
     			}
        	}		    
        }  	
		
	 public function getlocationIdByData()
	 {	
	 	global $db, $core;
		 $sql = " SELECT location_id FROM res_users_master where id = '".$this->uid."'";
		 $row = $db->first($sql);
		 return ($row) ? $row : 0;
	 }
	 
	 
	 
	 
	   	  	  	   
  }
?>