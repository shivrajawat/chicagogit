<?php
  /**
   * Customer  Class
   *
   * Kula cart 
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Customers
  {
	  private $cTable = "res_customer_master";
	  public $customerlogged_in = null;
	  public $cid = 0;
	  public $customerid = 0;
      public $customername;
	  public $sesid;
	  public $email;
	  public $name;
      public $membership_id = 0;
	  public $memused = 0;
	  public $access = null;
      public $userlevel;
	  private $lastlogin = "NOW()";
      

      /**
       * Customer::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getCustomerId();
		  $this->startSession();
      }

	  /**
	   * Customer::getUserId()
	   * 
	   * @return
	   */
	  private function getCustomerId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['customerid'])) {
			  $customerid = (is_numeric($_GET['customerid']) && $_GET['customerid'] > -1) ? intval($_GET['customerid']) : false;
			  $customerid = sanitize($customerid);
			  
			  if ($customerid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Customers::getCustomerId()") : $core->ooops();
			  } else
				  return $this->customerid = $customerid;
		  }
	  }  

      /**
       * Customer::startSession()
       * 
       * @return
       */
      private function startSession()
      {
		if (strlen(session_id()) < 1)
			session_start();
	  
		$this->customerlogged_in = $this->loginCheck();		
		if (!$this->customerlogged_in) {
			$this->customername = $_SESSION['customername'] = "Guest";
			$this->sesid = sha1(session_id());
			$this->userlevel = 0;
		}
      }
	  
	  /**
	   * Customer::loginCheck()
	   * 
	   * @return
	   */
	  private function loginCheck()
	  {
          if (isset($_SESSION['customername']) && $_SESSION['customername'] != "Guest") {
              
              $row = $this->getUserInfo($_SESSION['customername']);
			  $this->cid = $row['id'];
              $this->customername = $row['first_name'] . " " .$row['last_name'] ;
			  $this->email = $row['email_id'];
			  $this->name = $row['first_name'].' '.$row['last_name'];
			  $this->sesid = sha1(session_id());
              return true;
          } else {
              return false;
          }  
	  }


	  /**
	   * Customer::login()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function login($customername, $password)
	  {
		  global $db, $core, $wojosec;
		  
		  $timeleft = null;
		 /* if (!$wojosec->loginAgain($timeleft)) {
			  $minutes = ceil($timeleft / 60);
			  $core->msgs['customername'] = str_replace("%MINUTES%", $minutes, _LG_BRUTE_RERR);
		  } else*/if ($customername == "" && $password == "") {
			  $core->msgs['customername'] = _LG_ERROR1;
		  } else {
			  $status = $this->checkStatus($customername, $password);
			  
			  switch ($status) {
				  case 0:
					  $core->msgs['customername'] = _LG_ERROR2;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 1:
					  $core->msgs['customername'] = _LG_ERROR3;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 2:
					  $core->msgs['customername'] = _LG_ERROR4;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 3:
					  $core->msgs['customername'] = _LG_ERROR5;
					  $wojosec->setFailedLogin();
					  break;
			  }
		  }
		  if (empty($core->msgs) && $status == 5) {
			  $row = $this->getUserInfo($customername);
			  $this->cid = $_SESSION['cid'] = $row['id'];
			  $this->customername = $_SESSION['customername'] = $row['email_id'];
			  $this->email = $_SESSION['email'] = $row['email_id'];
			  $this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];

			  $data = array(
					'lastlogin' => $this->lastlogin, 
					'lastip' => sanitize($_SERVER['REMOTE_ADDR'])
			  );
			  $db->update($this->cTable, $data, "email_id='" . $this->customername . "'");
				  
			  return true;
		  } else
			  $core->msgStatus();
	  }

      /**
       * Customer::logout()
       * 
       * @return
       */
      public function logout()
      {
          unset($_SESSION['customername']);
		  unset($_SESSION['email']);
		  unset($_SESSION['customername']);
          unset($_SESSION['cid']);
		  unset($_SESSION['userlevel']);
		  unset($_SESSION['access']);
          unset($_SESSION['cid']);
		  unset($_SESSION['fbid']);		 
		  unset($_SESSION['chooseAddress']);
		  unset($_SESSION['repeat_order_signin']);
		  unset($_SESSION['sessioncookie']);
		  unset($_SESSION['repeatOrder']);
          session_destroy();
		  session_regenerate_id();
          
          $this->customerlogged_in = false;
          $this->username = "Guest";
          $this->userlevel = 0;
      }

	  /**
	   * Customer::fbLogin()
	   * 
	   * @return
	   */
	  public function fbLogin($me)
	  {
		  global $db, $core, $facebook;
		  
		  if (!empty($me)) {
			  $result = $db->first("SELECT fbid FROM users WHERE fbid = " . $me['id']);
			  if (!$result) {
		
				  $data = array(
					  'fbid' => $me['id'],
					  'username' => sanitize($me['username']),
					  'email' => sanitize($me['email']),
					  'lname' => sanitize($me['last_name']),
					  'fname' => sanitize($me['first_name']),
					  'created' => "NOW()",
					  'lastlogin' => "NOW()",
					  'lastip' => sanitize($_SERVER['REMOTE_ADDR']),
					  'active' => 'y');
				  $db->insert($this->cTable, $data);
		
		
			  } else {
		
				  $data = array('lastlogin' => "NOW()", 'lastip' => sanitize($_SERVER['REMOTE_ADDR']));
				  
				  $db->update($this->cTable, $data, "fbid='" . $me['id'] . "'");
				  if (!$this->validateMembership()) {
					  $data = array('membership_id' => 0, 'mem_expire' => "0000-00-00 00:00:00");
					  $db->update($this->cTable, $data, "fbid='" . $me['id'] . "'");
				  }
			  }
		
		
			  $row = $db->first("SELECT * FROM " . $this->cTable . " WHERE fbid = " . $me['id']);
		
			  $this->cid = $_SESSION['cid'] = $row['id'];
			  $this->fbid = $_SESSION['fbid'] = $row['fbid'];
			  $this->username = $_SESSION['username'] = $row['username'];
			  $this->email = $_SESSION['email'] = $row['email'];
			  $this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
			  $this->userlevel = $_SESSION['membership_id'] = $row['membership_id'];
			  $this->access = $_SESSION['access'] = $row['access'];
			  $this->fb_token = $_SESSION['fb_token'] = $facebook->getAccessToken();
		
			  redirect_to(SITEURL);
		
		
		  }

	  }
	  
	  /**
	   * Customer::getUserInfo()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function getUserInfo($customername)
	  {
		  global $db;
		  $customername = sanitize($customername);
		  $customername = $db->escape($customername);
		  
		  $sql = "SELECT * FROM " . $this->cTable . " WHERE email_id = '" . $customername . "'";
		  $row = $db->first($sql);
		  if (!$customername)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Customer::getUserFBInfo()
	   * 
	   * @param mixed $fbid
	   * @return
	   */
	  private function getUserFBInfo($fbid)
	  {
		  global $db;
		  $fbid = sanitize($fbid);
		  $fbid = $db->escape($fbid);
		  
		  $sql = "SELECT * FROM " . $this->cTable . " WHERE fbid = '" . $fbid . "'";
		  $row = $db->first($sql);
		  if (!$fbid)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Customer::checkStatus()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function checkStatus($customername, $password)
	  {
		  global $db;
		  
		  $customername = sanitize($customername);
		  $customername = $db->escape($customername);
		  $password = sanitize($password);
		  
          $sql = "SELECT password, active FROM " . $this->cTable
		  . "\n WHERE email_id = '".$customername."'";
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
	   * Customer::processUser()
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

		  if (empty($_POST['fname']))
			  $core->msgs['fname'] = _UR_FNAME_R;
			  
		  if (empty($_POST['lname']))
			  $core->msgs['lname'] = _UR_LNAME_R;
			  
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

		  if (!empty($_FILES['avatar']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['avatar']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'username' => sanitize($_POST['username']), 
				  'email' => sanitize($_POST['email']), 
				  'lname' => sanitize($_POST['lname']), 
				  'fname' => sanitize($_POST['fname']), 
				  'membership_id' => intval($_POST['membership_id']),
				  'mem_expire' => $this->calculateDays($_POST['membership_id']),
				  'newsletter' => intval($_POST['newsletter']),
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
				  $userrow = $core->getRowById($this->cTable, $this->userid);
			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userrow['password'];

			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['avatar'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/avatars/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->userid) {
				  $avatar = getValue("avatar",$this->cTable,"id = '".$this->userid."'");
				  if (!empty($_FILES['avatar']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['avatar'] = $name;
				  } else {
					  $data['avatar'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['avatar']['name'])) 
				  $data['avatar'] = $name;
			  }
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  
			  ($this->userid) ? $db->update($this->cTable, $data, "id='" . (int)$this->userid . "'") : $db->insert($this->cTable, $data);
			  $message = ($this->userid) ? _UR_UPDATED : _UR_ADDED;

			  if ($db->affected()) {
				  $core->msgOk($message);
				  $wojosec->writeLog($message, "", "no", "content");
				  
				  if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
					  
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
				  }
			  } else
				  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 

	  /**
	   * Customer::updateProfile()
	   * 
	   * @return
	   */
	  public function updateProfile()
	  {
		  global $db, $core, $wojosec;

		  if (empty($_POST['fname']))
			  $core->msgs['fname'] = _UR_FNAME_R;
			  
		  if (empty($_POST['lname']))
			  $core->msgs['lname'] = _UR_LNAME_R;

		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;

		  if (!$this->isValidEmail($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R2;

		  if (!empty($_FILES['avatar']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  if ($_FILES["avatar"]["size"] > 307200) {
				  $core->msgs['avatar'] = _UA_AVATAR_SIZE;
			  }
			  $file_info = getimagesize($_FILES['avatar']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }

		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'email' => sanitize($_POST['email']), 
				  'lname' => sanitize($_POST['lname']), 
				  'fname' => sanitize($_POST['fname']), 
				  'newsletter' => intval($_POST['newsletter'])
			  );
				   
			  $userpass = getValue("password", $this->cTable, "id = '".$this->cid."'");
			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userpass;

			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['avatar'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/avatars/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  $avatar = getValue("avatar",$this->cTable,"id = '".$this->cid."'");
			  if (!empty($_FILES['avatar']['name'])) {
				  if ($avatar) {
					  @unlink($als->SavePath . $avatar);
				  }
				  $data['avatar'] = $name;
			  } else {
				  $data['avatar'] = $avatar;
			  }

			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  
			  $db->update($this->cTable, $data, "id='" . (int)$this->cid . "'");

			  ($db->affected()) ? $wojosec->writeLog(_USER . ' ' . $this->username. ' ' . _LG_PROFILE_UPDATED, "user", "no", "user") . $core->msgOk(_UA_UPDATEOK) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 
	  
	

      /**
       * Customer::register_previous_upto_30_dec_2013()
       * 
       * @return
       */
	  public function register_previous_upto_30_dec_2013()
	  {
		  global $db, $core, $wojosec;	
		  
		  	  
		  if (empty($_POST['captcha']))
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R;

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R1;
			  	  
		  if (empty($core->msgs)) {
			  
			  $pass = sanitize($_POST['password']);
			  
			  if($core->reg_verify == 1) {
				  $active = "t";
			  } elseif($core->auto_verify == 0) {
				  $active = "n";
			  } else {
				  $active = "y";
			  }
			  
			  /******if month and date comes, then formating like below******************************************
			  if(isset($_POST['birth_month']) && isset($_POST['birth_date'])){
				  
				  $bir_month = sanitize($_POST['birth_month']);
				  
				  if(strlen($_POST['birth_date']==1)){				   
				  	  $bir_day = '0'.$_POST['birth_date'];					
					  $birthday = $bir_month.'/'.$bir_day;				
				  } 
				  else { 				 
				  	 $bir_day = sanitize($_POST['birth_date']);
				  	 $birthday = $bir_month.'/'.$bir_day; 
				  }			 
			  }	
			  /*****if month and date comes, then formating like this*********************************************/ 
			  
			  $data = array(
					  'email_id' => sanitize($_POST['email_address']), 
					  'password' => sha1($_POST['password']),
					  'first_name' => sanitize(ucwords($_POST['first_name'])),
					  'last_name' => sanitize(ucwords($_POST['last_name'])),
					  'birthday' => sanitize(ucwords($_POST['birth_month'])),
					  //'address1' => sanitize($_POST['address1']),
					  //'state_id' => sanitize($_POST['state']),			//dropdown city,now commented
					  //'city_id' => sanitize($_POST['city']),			    //dropdown city,now commented
					  'state' => sanitize(ucwords($_POST['state'])),			
					  'city' => sanitize(ucwords($_POST['city'])),	
					  'zipcode' => sanitize($_POST['zip_code']),
					  'phone_number' => sanitize($_POST['phone_no']),	
					  //'active' => $active, 
					  'active' => 'y', 
					  'create_date' => "NOW()"
			  );
			  $db->insert($this->cTable, $data);			  
			  
		     
			  //require_once(WOJOLITE . "lib/class_mailer.php");
			  if ($core->reg_verify == 1) {
				 
				  //$actlink = $core->site_url . "/login.php?action=activate"; 
				
				  $row = $core->getRowById("email_templates",1);
				  	
				   $body = str_replace(
						array('[NAME]','[USERNAME]','[PASSWORD]','[URL]','[SITE_NAME]'), 
						array($data['first_name'], $data['email_id'],$_POST['password'],$core->site_url,$core->site_name), $row['body'.$core->dblang]
						);
							
				    $newbody = cleanOut($body);
				
				   
				    $to = $data['email_id'];
					$from = $core->site_name;
					$subject = $row['subject'.$core->dblang];
				
					// To send the HTML mail we need to set the Content-type header.
					$headers = "MIME-Version: 1.0rn";		
					$headers .= "--$boundary\r\n".
								"Content-Type: text/html; charset=ISO-8859-1\r\n".
								"Content-Transfer-Encoding: base64\r\n\r\n";
								
					$headers  .= "From: $from\r\n";    
					mail($to,$subject, $newbody, $headers);
			  } 
						   
			  if($db->affected())
			  {
				  $wojosec->writeLog(_USER . ' ' . $data['email_id']. ' ' . _LG_USER_REGGED, "user", "no", "user");
				  $_SESSION['reg'] = 1;
				  print "Ok";
				 		  
			  }
			  else
			  {
				  $core->msgError(_UA_REG_ERR,false);
			  }
		  } else
			  print $core->msgStatus();
	  }
	  
	   /**
       * Customer::register()
       * 
       * @return
       */
	  public function register()
	  {
		  global $db,$core,$wojosec;	
		  
		   //print_r($_POST); exit();	  
		  if (empty($_POST['captcha']))
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R;

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R1;
			  	  
		  if (empty($core->msgs)) {
			  
			  $pass = sanitize($_POST['password']);
			  
			  if($core->reg_verify == 1) {
				  $active = "t";
			  } elseif($core->auto_verify == 0) {
				  $active = "n";
			  } else {
				  $active = "y";
			  }
			  
			  /******if month and date comes, then formating like below******************************************
			  if(isset($_POST['birth_month']) && isset($_POST['birth_date'])){
				  
				  $bir_month = sanitize($_POST['birth_month']);
				  
				  if(strlen($_POST['birth_date']==1)){				   
				  	  $bir_day = '0'.$_POST['birth_date'];					
					  $birthday = $bir_month.'/'.$bir_day;				
				  } 
				  else { 				 
				  	 $bir_day = sanitize($_POST['birth_date']);
				  	 $birthday = $bir_month.'/'.$bir_day; 
				  }			 
			  }	
			  /*****if month and date comes, then formating like this*********************************************/ 
			  
			  $data = array(
					  'email_id' => sanitize($_POST['email_address']), 
					  'password' => sha1($_POST['password']),
					  'first_name' => sanitize(ucwords($_POST['first_name'])),
					  'last_name' => sanitize(ucwords($_POST['last_name'])),
					  'e_club' => ($_POST['eclub']) ? $_POST['eclub'] : "",
					  'birthday' => sanitize(ucwords($_POST['birth_month'])),
					  //'address1' => sanitize($_POST['address1']),
					  //'state_id' => sanitize($_POST['state']),			//dropdown city,now commented
					  //'city_id' => sanitize($_POST['city']),			    //dropdown city,now commented
					  'state' => sanitize(ucwords($_POST['state'])),			
					  'city' => sanitize(ucwords($_POST['city'])),	
					  'zipcode' => sanitize($_POST['zip_code']),
					  'phone_number' => sanitize($_POST['phone_no']),
					  'cell_phone' => ($_POST['cell_phone']) ? $_POST['cell_phone'] : "",
					  'receive_msg' => ($_POST['receive_msg']) ? $_POST['receive_msg'] : "",	
					  //'active' => $active, 
					  'active' => 'y', 
					  'create_date' => "NOW()"
			  );
			  $db->insert($this->cTable, $data);			  
			  
		     
			  require_once(WOJOLITE . "lib/class_mailer.php");
			   if ($core->reg_verify == 1) {
				  //$actlink = $core->site_url . "/login.php?action=activate"; 
				  $row = $core->getRowById("email_templates",1);
				  
				  $body = str_replace(
						array('[NAME]', '[USERNAME]', '[PASSWORD]','[EMAIL]', '[URL]','[SITE_NAME]'), 
						array($data['first_name'], $data['email_id'],$_POST['password'],$data['email_id'], $core->site_url,$core->site_name), $row['body'.$core->dblang]
						);
						
				  $newbody = cleanOut($body);						 
				  $mailer = $mail->sendMail();
				  $message = Swift_Message::newInstance()
							->setSubject($row['subject'.$core->dblang])						
							->setTo(array($data['email_id'] => $data['first_name']))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');
							
				 $mailer->send($message);
				 
			  }
						   
			  if($db->affected())
			  {
				  $wojosec->writeLog(_USER . ' ' . $data['email_id']. ' ' . _LG_USER_REGGED, "user", "no", "user");
				  /*$_SESSION['reg'] = 1;
				  print "Ok";*/
				  
				  $username = $data['email_id'];
				  $password = sanitize($_POST['password']);
				  					
				  $loginuser = $this->login($username,$password);
				  
				  $sessionID = SESSION_COOK;
	  			  $checkout =   $this->chekoutproduct($sessionID);				  
				 
				  if($checkout){
						echo "1"; 
						//redirect_to("getorder-type");
				  }	 
			  	  else {
						echo "2";
						//redirect_to("/account");
				  }
				 		  
			  }
			  else
			  {
				  $core->msgError(_UA_REG_ERR,false);
			  }
		  } else
			  print $core->msgStatus();
	  }
	  
	   /**
       * Customer::registerMobile()
       * Registration through mobile
       * @return
       */
	  public function registerMobile()
	  {
		  global $db, $core, $wojosec;	
		  	
	     // echo "<pre>"; print_r($_POST); exit();		  	  
		  if (empty($core->msgs)) {
			  
			  $pass = sanitize($_POST['password']);
			  
			  if($core->reg_verify == 1) {
				  $active = "t";
			  } elseif($core->auto_verify == 0) {
				  $active = "n";
			  } else {
				  $active = "y";
			  }
				  
			  $data = array(
					  'email_id' => sanitize($_POST['email_address']), 
					  'password' => sha1(sanitize($_POST['password'])),
					  'first_name' => sanitize(ucwords($_POST['first_name'])),
					  'last_name' => sanitize(ucwords($_POST['last_name'])),
					  'e_club' => ($_POST['eclub']) ? $_POST['eclub'] : "",
					  'birthday' => ($_POST['birth_month']) ? $_POST['birth_month'] : "",
					  'apt' => sanitize($_POST['apt']),					 
					  'state' => sanitize(ucwords($_POST['state'])),			
					  'city' => sanitize(ucwords($_POST['city'])),	
					  'zipcode' => sanitize($_POST['zip_code']),
					  'phone_number' => sanitize($_POST['phone_no']),
					  'cell_phone' => ($_POST['cell_phone']) ? $_POST['cell_phone'] : "",
					  'receive_msg' => ($_POST['receive_msg']) ? $_POST['receive_msg'] : "",		
					  //'active' => $active, 
					  'active' => 'y', 
					  'create_date' => "NOW()"
			  );
			  
			  $db->insert($this->cTable, $data);
					  
			  if ($core->reg_verify == 1) {
					
				  require_once(WOJOLITE . "lib/class_mailer.php");
				  
				  $row = $core->getRowById("email_templates",1);
				  
				  $body = str_replace(
						array('[NAME]', '[USERNAME]', '[PASSWORD]','[EMAIL]', '[URL]','[SITE_NAME]'), 
						array($data['first_name'], $data['email_id'],$pass,$data['email_id'], $core->site_url,$core->site_name), $row['body'.$core->dblang]
						);
						
				  $newbody = cleanOut($body);						 
				  $mailer = $mail->sendMail();
				  $message = Swift_Message::newInstance()
							->setSubject($row['subject'.$core->dblang])						
							->setTo(array($data['email_id'] => $data['first_name']))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');
							
				 $mailer->send($message);
			  } 
			  
			  if($db->affected()){
			  
				 $wojosec->writeLog(_USER . ' ' . $data['email_id']. ' ' . _LG_USER_REGGED, "user", "no", "user");
				  /**********************
					  $_SESSION['reg'] = 1;
					  print "Ok";
				  ***********************/
				  
				  $username = sanitize($_POST['email_address']);
				  $password = sanitize($_POST['password']);
				  
				 				  					
				  $loginuser = $this->login($username,$password);
				  				  
				  $sessionID = SESSION_COOK; 
	  			  $checkout =   $this->chekoutproduct($sessionID);		 
				  
				  if($checkout){
						echo "1"; 
						//redirect_to("checkout.php");
				  }	 
			  	  else {
						echo "2";
						//redirect_to("account.php");
				  } 
			  }
			  else {
				  $core->msgError(_UA_REG_ERR,false);			  }
		  } else
			  print $core->msgStatus();
	  }  
	  
      /**
       * Customer::passReset()
       * 
       * @return
       */
	   
	   
	  public function passReset()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['uname']))
			  $core->msgs['uname'] = _UR_USERNAME_R;
		  
		  $uname = $this->usernameExists($_POST['uname']);
		  if (strlen($_POST['uname']) < 4 || strlen($_POST['uname']) > 30 || !preg_match("/^([0-9a-z])+$/i", $_POST['uname']) || $uname != 3)
			  $core->msgs['uname'] = _UR_USERNAME_R0;

		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;

		  if (!$this->emailExists($_POST['email']))
			  $core->msgs['uname'] = _UR_EMAIL_R3;
			    
		  if (empty($_POST['captcha']))
			  $core->msgs['captcha'] = _UA_PASS_RTOTAL_R;

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  $core->msgs['captcha'] = _UA_PASS_RTOTAL_R1;
		  
		  if (empty($core->msgs)) {
			  
              $user = $this->getUserInfo($_POST['uname']);
			  $randpass = $this->getUniqueCode(12);
			  $newpass = sha1($randpass);
			  
			  $data['password'] = $newpass;
			  
			  $db->update($this->cTable, $data, "username = '" . $user['username'] . "'");
		  
			  require_once(WOJOLITE . "lib/class_mailer.php");
			  $row = $core->getRowById("email_templates", 2);
			  
			  $body = str_replace(
					array('[USERNAME]', '[PASSWORD]', '[URL]', '[LINK]', '[IP]', '[SITE_NAME]'), 
					array($user['username'], $randpass, $core->site_url, $core->site_url, $_SERVER['REMOTE_ADDR'], $core->site_name), $row['body'.$core->dblang]
					);
					
			  $newbody = cleanOut($body);

			  $mailer = $mail->sendMail();
			  $message = Swift_Message::newInstance()
						->setSubject($row['subject'.$core->dblang])
						->setTo(array($user['email'] => $user['username']))
						->setFrom(array($core->site_email => $core->site_name))
						->setBody($newbody, 'text/html');
						
			  ($db->affected() && $mailer->send($message)) ? $wojosec->writeLog(_USER . ' ' . $user['username']. ' ' . _LG_PASS_RESET, "user", "no", "user") . $core->msgOk(_UA_PASS_R_OK,false) : $core->msgError(_UA_PASS_R_ERR,false);

		  } else
			  print $core->msgStatus();
	  }
	  
      /**
       * Customer::activateUser()
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
			  
			  $db->update($this->cTable, $data, "email = '" . $email . "' AND token = '" . $token . "'");
			  ($db->affected()) ? $core->msgOk($message,false) : $core->msgError(_UA_TOKEN_R_ERR,false);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Customer::getUserData()
	   * 
	   * @return
	   */
	  public function getUserData()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT * "
		  . "\n FROM " . $this->cTable
		  . "\n WHERE id = '" . $this->cid . "'";
		  $row = $db->first($sql);

		  return ($row) ? $row : 0;
	  }	

	  /**
	   * Customer::calculateDays()
	   * 
	   * @return
	   */
	  public function calculateDays($membership_id)
	  {
		  global $db;
		  
		  $now = date('Y-m-d H:i:s');
		  $row = $db->first("SELECT days, period FROM memberships WHERE id = '" . (int)$membership_id . "'");
		  if($row) {
			  switch($row['period']) {
				  case "D" :
				  $diff = $row['days'];
				  break;
				  case "W" :
				  $diff = $row['days'] * 7;
				  break; 
				  case "M" :
				  $diff = $row['days'] * 30;
				  break;
				  case "Y" :
				  $diff = $row['days'] * 365;
				  break;
			  }
			$expire = date("Y-m-d H:i:s", strtotime($now . + $diff . " days"));
		  } else {
			$expire = "0000-00-00 00:00:00";
		  }
		  return $expire;
	  }     
	  
	
	  	  	  	  
	  /**
	   * Customer::usernameExists()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function customernameExists($customername)
	  {
		  global $db;
		  
		  $customername = sanitize($customername);
		  if (strlen($db->escape($customername)) < 4)
			  return 1;
		  
		  $alpha_num = str_replace(" ", "", $customername);
		  if (!ctype_alnum($alpha_num))
			  return 2;
		  
		  $sql = $db->query("SELECT username" 
		  . "\n FROM users" 
		  . "\n WHERE email_id = '" . $customername . "'" 
		  . "\n LIMIT 1");
		  
		  $count = $db->numrows($sql);
		  
		  return ($count > 0) ? 3 : false;
	  }  	
	  
	  /**
	   * Customer::emailExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function emailExists($email)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT email" 
		  . "\n FROM users" 
		  . "\n WHERE email_id = '" . sanitize($email) . "'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
	   * Customer::isValidEmail()
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
       * Customer::validateToken()
       * 
       * @param mixed $token
       * @return
       */
     private function validateToken($token)
      {
          global $db;
          $token = sanitize($token,40);
          $sql = "SELECT token" 
		  . "\n FROM ".$this->cTable 
		  . "\n WHERE token ='" . $db->escape($token) . "'" 
		  . "\n LIMIT 1";
          $result = $db->query($sql);
          
          if ($db->numrows($result))
              return true;
      }
	  
	  /**
	   * Customer::getUniqueCode()
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
	   * Customer::generateRandID()
	   * 
	   * @return
	   */
	  private function generateRandID()
	  {
		  return sha1($this->getUniqueCode(24));
	  }
	   /**
	   * Front Customer::emailExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  public function emailAlreadyexit($email)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT email_id" 
		  . "\n FROM res_customer_master" 
		  . "\n WHERE email_id = '" . sanitize($email) . "'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
	   * Front Customer::emailExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	   public function isEmailValid($email)
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
	   * Customers::UpdateUserData()             
	   * 									 Note:---This function is also using for mobile user profile update
	   * @return
	   */
	  public function UpdateUserData()
	  {
		  global $db, $core, $wojosec;
		 
		  if (empty($core->msgs)) {
			  
			   if(isset($_POST['cell_phone']) && !empty($_POST['cell_phone'])){
				  $cellPhone = $_POST['cell_phone'];
				  $receive_msg = ($_POST['receive_msg']) ? $_POST['receive_msg'] : "";
				  
			  }else {
				  
				  $cellPhone = "";
				  $receive_msg = "";
			  }			  
			  
			  $data = array(
					  'first_name' => sanitize($_POST['first_name']),
					  'last_name' => sanitize($_POST['last_name']),
					  'address1' => sanitize($_POST['address1']),
					  'address2' => sanitize($_POST['address2']),					  
					  'state' => sanitize($_POST['state']),
					  'city' => sanitize($_POST['city']),					  
					  'zipcode' => sanitize($_POST['zip_code']),
					  'phone_number' => sanitize($_POST['phone_no']),					  
					  'd_address1' => sanitize($_POST['d_address1']),
					  'd_address2' => sanitize($_POST['d_address2']),
					  /*'state_id' => sanitize($_POST['state']),
					  'city_id' => sanitize($_POST['city']),*/
					  /* 'dstate_id' => sanitize($_POST['dstate_id']),
					  'dcity_id' => sanitize($_POST['dcity_id']),*/
					  'dstate' => sanitize($_POST['dstate']),
					  'dcity' => sanitize($_POST['dcity']),
					  'dzipcode' => sanitize($_POST['dzipcode']),
					  'phone_number' => sanitize($_POST['phone_no']),
					  'apt' => (isset($_POST['apt'])) ? sanitize($_POST['apt']) : "",
					  'cell_phone' => $cellPhone,
					  'receive_msg' => $receive_msg,
					  'e_club' => (isset($_POST['eclub'])) ? intval($_POST['eclub']) : "",
					  'birthday' => (isset($_POST['birth_month'])) ? intval($_POST['birth_month']) : "",
					  'notification' => (isset($_POST['notification'])) ? intval($_POST['notification']) : "",
					  'occasionally' => (isset($_POST['occasionally'])) ? intval($_POST['occasionally']) : ""
					  
			  );
			  	   
			  $userpass = getValue("password", $this->cTable, "id = '".$this->cid."'");			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userpass;
			   $db->update($this->cTable, $data, "id='" . $this->cid . "'");
			  
			  if($db->affected()):
			  	 $wojosec->writeLog(_USER . ' ' . $data['first_name']. ' ' . _LG_USER_REGGED, "user", "no", "user");
				 return "1";				  
			  //else:
			  	//$core->msgAlert(_SYSTEM_PROCCESS);
			  endif;
		  } else
			  print $core->msgStatus();
	  }	 
	  
	  /**
       * customer::Forgot Password()
       * 
       * @return
       */
	  public function ForgotPassword()
	  {
		  global $db, $core, $wojosec;		  
		  
		  if (empty($_POST['username']))
			  $core->msgs['username'] = "Please provide your username /email";
		  
		  if (!$this->emailExistsForgotPassword($_POST['username']))
			  $core->msgs['username'] = "We are sorry, selected username/email does not exist";
			  
		  if (empty($core->msgs)) {
			  
              $user = $this->getUserInfo($_POST['username']);
			  $randpass = $this->getUniqueCode(12); 
			  $newpass = sha1($randpass);
			  
			  $data['password'] = $newpass; 
			  
			  $db->update("res_customer_master", $data, "email_id = '" . sanitize($_POST['username']) . "'");
		  
			  require_once(WOJOLITE . "lib/class_mailer.php");
			  $row = $core->getRowById("email_templates",2);
			  
			  $body = str_replace(
					array('[USERNAME]','[PASSWORD]', '[URL]', '[LINK]', '[IP]', '[SITE_NAME]'), 
					array(sanitize($_POST['username']), $randpass, $core->site_url, $core->site_url, $_SERVER['REMOTE_ADDR'], $core->site_name), $row['body'.$core->dblang]
					);
					
			  
			  $email_id = sanitize($_POST['username']);	//email id of user
					
			  $newbody = cleanOut($body);

			  $mailer = $mail->sendMail();
			  $message = Swift_Message::newInstance()
						->setSubject($row['subject'.$core->dblang])
						->setTo(array($email_id => $email_id))
						->setFrom(array($core->site_email => $core->site_name))
						->setBody($newbody, 'text/html');
						
			  ($db->affected() && $mailer->send($message)) ? $wojosec->writeLog(_USER . ' ' . $_POST['username']. ' has been reset successfully ' , "user", "no", "Forget password retrieve password") . $core->msgOk('You have successfully reset your password. Please log in to your email to retrieve this password.You can then change your password on your profile page.',false) : $core->msgError(_UA_PASS_R_ERR,false);

		  } else
			  print $core->msgStatus();
	  }
	  
	 
	  
	  /**
	   * Customer::emailExistsForgotPassword()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function emailExistsForgotPassword($email)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT email_id " 
						  . "\n FROM res_customer_master" 
						  . "\n WHERE email_id = '" . sanitize($email) . "'" 
						  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	   /**
	   * Customer::customerOrderDetails()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  public function customerOrderDetails($customer_id)
	  {
		  global $db;
		  
		  $sql = "SELECT rcm.first_name, rlm.location_name,rom.orderid,rom.order_number, rom.`created_date` AS order_date, rom.`order_type` , rom.`order_status` , rom.`net_amount`,rom.pickup_date,rom.pickup_time"
			  .	"\n FROM `res_order_master` AS rom "
			  .	"\n INNER JOIN res_customer_master AS rcm ON rom.`customer_id` = rcm.id "
			  .	"\n INNER JOIN res_location_master AS rlm ON rom.location_id = rlm.id WHERE rom.customer_id ='".$customer_id."' ORDER BY orderid DESC";

		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  
	  public function chekoutproduct($sessionID)
	  {
		  global $db;
		  
		   $sql = $db->query("SELECT * " 
			  . "\n FROM `res_baskets`" 
			  . "\n WHERE `basketSession`='".$sessionID."'" 
			  . "\n LIMIT 1");  
		  
		  if ($db->numrows($sql) >0) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
	   * Customer::getAddressType()
	   * 
	   * @return
	   */
	  public function getAddressType()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT addr_residence,addr_business,addr_university,addr_military,business_name "
			  . "\n FROM " . $this->cTable
			  . "\n WHERE id = '" . $this->cid . "'";
			  
		  $row = $db->first($sql);

		  return ($row) ? $row : 0;
	  }	
	  
	  /* 
	* Product:customerFirstOrderExistance()
	*/
	public function customerFirstOrderExistance()
	{
		global $db;
				
		$sql = $db->query("SELECT `orderid` " 
			  . "\n FROM `res_order_master` " 
			  . "\n WHERE `customer_id` ='".$this->cid."'");  
		  
		$count = $db->numrows($sql);
		 
		return  ($count > 0) ? 1 : 0;
	}
	  
	  
	
  }
?>