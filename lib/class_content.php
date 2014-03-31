<?php
  /**
   * Content Class
   * 
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Content
  {
      
	  public $pageid = null;
	  public $modpageid = null;
	  public $modalias = null;
	  public $moduledata = array();
	  public $id = null;
	  public $slug = null;
	  public $homeslug = null;
	  public $homeid = null;
	  public $postid = null;
	  public $is_homemod = null;
	  private $menutree = array();
	  private $menulist = array();
	  private $categorymenulist = array();
	  

      /**
       * Content::__construct()
       * 
       * @param bool $menutre
       * @return
       */
      public function __construct($menutre = true)
      {
	  	  $this->getWebSettings();
          $this->getPageId();
		  $this->getId();
		  $this->getPageSlug();
		  $this->getHomePageSlug();
		  $this->getModAlias();
		  $this->getPostId();
		  ($menutre) ? $this->menutree = $this->getMenuTree() : $this->menutree = null;
		  $this->getPageSettings();
		   ($menutre) ? $this->categorytree = $this->getcategoryTree() : $this->categorytree = null;
		  ($this->modalias) ? $this->moduledata = $this->getModuleMetaData() : null;
      }

	  /**
	   * Content::getPageSlug()
	   * 
	   * @return
	   */
	  private function getPageSlug()
	  {
	  	  global $db;
		  
		  if (isset($_GET['pagename'])) {
			  $this->slug = sanitize($_GET['pagename'],50);
			  return $db->escape($this->slug);
		  }
	  }

	  /**
	   * Content::getModAlias()
	   * 
	   * @return
	   */
	  private function getModAlias()
	  {
	  	  global $db;
		  
		  if (isset($_GET['module'])) {
			  $this->modalias = sanitize($_GET['module'],20);
			  return $db->escape($this->modalias);
		  }
	  }

	  /**
	   * Content::getHomePageSlug()
	   * 
	   * @return
	   */
	  private function getHomePageSlug()
	  {
	  	  global $db;
		  
		  $row = $db->first("SELECT page_id, mod_id, page_slug FROM menus WHERE home_page = '1'");
		  $this->homeslug = $row['page_slug'];
		  $this->homeid = $row['page_id'];
		  if($row['mod_id'] and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			  $this->modalias =$row['page_slug'];
		  }

		  $this->is_homemod = $row['mod_id'];
	  }

	  /**
	   * Content::getPageId()
	   * 
	   * @return
	   */
	  private function getPageId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['pageid'])) {
			  $_GET['pageid'] = sanitize($_GET['pageid'],6,true);
			  $pageid = (is_numeric($_GET['pageid']) && $_GET['pageid'] > -1) ? intval($_GET['pageid']) : false;

			  if ($pageid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Core::getPageId()") : $core->ooops();
			  } else
				  return $this->pageid = $pageid;
		  }
	  }

	  /**
	   * Content::getPostId()
	   * 
	   * @return
	   */
	  private function getPostId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['postid'])) {
			  $postid = (is_numeric($_GET['postid']) && $_GET['postid'] > -1) ? intval($_GET['postid']) : false;
			  $postid = sanitize($postid,8,true);
			  
			  if ($postid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Core::getPostId()") : $core->ooops();
			  } else
				  return $this->postid = $postid;
		  }
	  }

	  /**
	   * Content::getId()
	   * 
	   * @return
	   */
	  private function getId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['id'])) {
			  $id = (is_numeric($_GET['id']) && $_GET['id'] > -1) ? intval($_GET['id']) : false;
			  $id = sanitize($id,8,true);
			  
			  if ($id == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Content::getId()") : $core->ooops();
			  } else
				  return $this->id = $id;
		  }
	  }

      /**
       * Content::getPageSettings()
       * 
       * @return
       */
      private function getPageSettings()
      {
          global $db, $core;
		  
          $sql = "SELECT * FROM pages WHERE slug = '".$this->slug."'";
          $row = $db->first($sql);
          
		  $this->title = $row['title'.$core->dblang];
		  $this->slug = $row['slug'];
		  $this->contact_form = $row['contact_form'];
		  $this->membership_id = $row['membership_id'];
		  $this->module_id = $row['module_id'];
		  $this->module_data = $row['module_data'];
		  $this->module_name = $row['module_name'];
		  $this->access = $row['access'];

		  $this->keywords = $row['keywords'.$core->dblang];
		  $this->description = $row['description'.$core->dblang];
		  $this->created = $row['created'];
		  $this->active = $row['active'];
		  $this->modpageid = $row['id'];

      }

	  /**
	   * Content::getHomePage()
	   * 
	   * @return
	   */
	  private function getHomePage()
	  {
		  global $db, $core, $user, $pager;

		  $sql = "SELECT p.title{$core->dblang}, p.body{$core->dblang}, p.show_title, m.home_page AS home, p.jscode" 
		  . "\n FROM pages AS pg" 
		  . "\n LEFT JOIN menus AS m ON pg.slug = m.page_slug" 
		  . "\n LEFT JOIN posts AS p ON p.page_slug = pg.slug" 
		  . "\n WHERE m.home_page = '1' ORDER BY p.position";
		  $result = $db->fetch_all($sql);
		  
		  if ($result) {
			foreach ($result as $row) {
				print "<article class=\"post\">";
				if ($row['show_title'] == 1) {
					print "<header class=\"home-header\">";
					print "<h1><span>" . $row['title' . $core->dblang] . "</span></h1>\n";
					print "</header>";
				}
				print "<div class=\"home-body\">" . cleanOut($row['body' . $core->dblang]) . "\n";
				print ($row['jscode']) ? cleanOut($row['jscode']) : "";
				print "</div>\n";
				print "</article>\n";

			}
			
		  } elseif (file_exists(MODDIR . $this->homeslug.'/main.php') and $this->is_homemod and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			   require(MODDIR . $this->homeslug.'/main.php'); 
			   $this->module_name = $this->homeslug;
			   
		  } else
			  print _CONTENT_NOT_FOUND;
	  }

	  /**
	   * Content::displayPage()
	   * 
	   * @return
	   */
	  public function displayPage()
	  {
		 ($this->slug) ? $this->getPagePosts() : $this->getHomePage();

	  }

	  /**
	   * Content::displayModule()
	   * 
	   * @return
	   */
	  public function displayModule()
	  {
		  global $db, $core, $user, $pager;
		  
		   if (file_exists(MODDIR . $this->moduledata['modalias'].'/main.php')) {
			   require(MODDIR . $this->moduledata['modalias'].'/main.php');  
		  } else {
			  redirect_to(SITEURL);
		  }
	  }

	  /**
	   * Content::getPagePosts()
	   * 
	   * @return
	   */
	  private function getPagePosts()
	  {
		  global $db, $core, $user, $pager;

		  $sql = "SELECT * FROM posts" 
		  . "\n WHERE page_slug = '" . $this->slug . "'" 
		  . "\n AND active = '1'" 
		  . "\n ORDER BY position";
		  $result = $db->fetch_all($sql);

		  $sql2 = "SELECT p.*, m.modalias, m.active as mactive FROM posts as p" 
		  . "\n LEFT JOIN modules AS m ON m.id = '".$this->module_id . "'"
		  . "\n WHERE p.page_slug = '" . $this->slug . "'" 
		  . "\n AND p.active = '1'"
		  . "\n AND m.system = '0'";
		  $row2 = $db->first($sql2);	
		  
		  if ($result) {
			  if($this->getAccess()) {
				  foreach ($result as $row) {
					  print "<article class=\"post\">";
					  if ($row['show_title'] == 1) {
					      print "<header class=\"post-header\">";
						  print "<h1><span>" . $row['title' . $core->dblang] . "</span></h1>\n";
						  print "</header>";
					  }
					  print "<div class=\"post-body\">" . cleanOut($row['body' . $core->dblang]) . "\n";
					  print ($row['jscode']) ? cleanOut($row['jscode']) : "";
					  print "</div>\n";
					  print "</article>\n";
				  }
				  
				  if ($this->contact_form <> 0)
					  include("contact_form.php");
					  
				  if ($row2['mactive'] and file_exists(MODDIR . $row2['modalias'].'/main.php')) {
					  include(MODDIR . $row2['modalias'].'/main.php');  
				  }
			  }
		  } else
			  print _CONTENT_NOT_FOUND;
	  } 

	  /**
	   * Content::getBreadcrumbs()
	   * 
	   * @return
	   */
	  public function getBreadcrumbs()
	  {
		  global $db, $core;
          
		  $crumbs = WOJOLITE . 'admin/modules/'.$this->modalias.'/crumbs.php';
		  if (file_exists($crumbs) and $this->moduledata['system']) {
			 include($crumbs);  
		  }
			 
		  $pageid = ($this->slug) ? $this->title : "";
		  $data = ($this->modalias and $this->moduledata['system']) ? $nav : $pageid;

		  return $data;
	  }

	  /**
	   * Content::getAccess()
	   * 
	   * @return
	   */
	  public function getAccess($showMsg = true)
	  {
		  global $db, $user, $core;
		  $m_arr = explode(",", $this->membership_id);
		  reset($m_arr);
		  
		  switch ($this->access) {
			  case "Registered":
				  if (!$user->logged_in) {
					  $showMsg ? $core->msgError(_UA_ACC_ERR1, false) : null;
					  return false;
				  } else
					  return true;
				  break;
				  
			  case "Membership":
				  if ($user->logged_in and $user->validateMembership() and in_array($user->membership_id, $m_arr)) {
					  return true;
				  } else {
					  if ($user->logged_in and $user->memused) {
						  $showMsg ? $core->msgError(_UA_ACC_ERR3 . $this->listMemberships($this->membership_id), false) : null;
					  } else {
						  $showMsg ? $core->msgError(_UA_ACC_ERR2 . $this->listMemberships($this->membership_id), false) : null;
					  }
					  
					  return false;
				  }
				  break;
				  
			  case "Public":
				  return true;
				  break;
				  
			  default:
				  return true;
				  break;
		  }
	  }

      /**
       * Content::listMemberships()
       * 
       * @param mixed $memid
       * @return
       */
	  private function listMemberships($memid)
	  {
		  global $db, $core;
		  
		  $data = $db->fetch_all("SELECT title{$core->dblang} as mtitle FROM memberships WHERE id IN(" . $memid . ")");
		  if ($data) {
			  $display = _UA_ACC_MEMBREQ;
			  $display .= '<ul class="error">';
			  foreach($data as $row) {
				  $display .= '<li>' . $row['mtitle'] . '</li>';
			  }
			  $display .= '</ul>';
			  return $display;
		  }
		  
	  }
	  
	  /**
	   * Content::getPages()
	   * 
	   * @return
	   */
	  public function getPages()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *,"
		  . "\n DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM pages"
		  . "\n ORDER BY title{$core->dblang}";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getModuleList()
       * 
       * @param bool $sel
       * @return
       */
      public function getModuleList($sel = false)
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id, modalias, title{$core->dblang} FROM modules" 
		  . "\n WHERE active = '1' AND hasconfig = '1' AND system = '0' ORDER BY title{$core->dblang}";
		  $sqldata = $db->fetch_all($sql);
		  
		  $data = '';
		  $data .= '<select name="module_id" style="width:200px" class="custombox" id="modulename">';
		  $data .= "<option value=\"0\"> --- No Module Assigned---</option>\n";
		  foreach ($sqldata as $val) {
              if ($val['id'] == $sel) {
                  $data .= "<option selected=\"selected\" value=\"" . $val['id'] . "\">" . $val['title' . $core->dblang] . "</option>\n";
              } else
                  $data .= "<option value=\"" . $val['id'] . "\">" . $val['title' . $core->dblang] . "</option>\n";
          }
          unset($val);
		  $data .= "</select>";
          return $data;
      }

	  /**
	   * Content::displayMenuModule()
	   * 
	   * @return
	   */
	  public function displayMenuModule()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id, title{$core->dblang} FROM modules" 
		  . "\n WHERE active = '1' AND system = '1' ORDER BY title{$core->dblang}";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getSitemap()
	   * 
	   * @return
	   */
	  public function getSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as pgtitle, slug FROM pages ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }

	  /**
	   * Content::getArticleSitemap()
	   * 
	   * @return
	   */
	  public function getArticleSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as atitle, slug FROM mod_articles WHERE active = 1 ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }
	  
	  /**
	   * Content::getDigishopSitemap()
	   * 
	   * @return
	   */
	  public function getDigishopSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as dtitle, slug FROM mod_digishop WHERE active = 1 ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }

	  /**
	   * Content::getPortfolioSitemap()
	   * 
	   * @return
	   */
	  public function getPortfolioSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as ptitle, slug FROM mod_portfolio ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }
	  
	  /**
	   * Content::processPage()
	   * 
	   * @return
	   */
	  public function processPage()
	  {
		  global $db, $core, $wojosec;
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _PG_TITLE_R;

		  if ($_POST['access'] == "Membership" && !isset($_POST['membership_id']))
			  $core->msgs['access'] = _PG_MEMBERSHIP_R;
			  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'keywords'.$core->dblang => sanitize($_POST['keywords'.$core->dblang]),
				  'description'.$core->dblang => sanitize($_POST['description'.$core->dblang]),
				  'slug' => (empty($_POST['slug'])) ? paranoia($_POST['title'.$core->dblang]) : paranoia($_POST['slug']),
				  'module_id' => intval($_POST['module_id']),
				  'module_data' => (isset($_POST['module_data'])) ? intval($_POST['module_data']) : 0,
				  'module_name' => getValue("modalias","modules","id='".intval($_POST['module_id'])."'"),
				  'contact_form' => intval($_POST['contact_form']),
				  'access' => sanitize($_POST['access'])
			  );

			  if (isset($_POST['membership_id'])) {
				  $mids = $_POST['membership_id'];
				  $total = count($mids);
				  $i = 1;
				  if (is_array($mids)) {
					  $midata = '';
					  foreach ($mids as $mid) {
						  if ($i == $total) {
							  $midata .= $mid;
						  } else
							  $midata .= $mid . ",";
						  $i++;
					  }
				  }
				  $data['membership_id'] = $midata;
			  } else
				  $data['membership_id'] = 0;
				  
			  if ($data['contact_form'] == 1) {
				  $contactform['contact_form'] = "DEFAULT(contact_form)";
				  $db->update("pages", $contactform);
			  }
			  
			  if (!$this->pageid) {
				  $data['created'] = "NOW()";
			  }
			  
			  if ($this->pageid) {
				  $sdata['page_slug'] = $data['slug'];
				  $db->update("layout", $sdata, "page_id='" . (int)$this->pageid . "'");
				  $db->update("menus", $sdata, "page_id='" . (int)$this->pageid . "'");
				  $db->update("posts", $sdata, "page_id='" . (int)$this->pageid . "'");
			  }
			  
			  ($this->pageid) ? $db->update("pages", $data, "id='" . (int)$this->pageid . "'") : $db->insert("pages", $data);
			  $message = ($this->pageid) ? _PG_UPDATED : _PG_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPosts()
	   * 
	   * @return
	   */
	  public function getPosts()
	  {
		  global $db;
		  
		  $where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : null ;
		  $sql = "SELECT * FROM posts"
		  . "\n {$where}"
		  . "\n ORDER BY position";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getPagePost()
	   * 
	   * @return
	   */
	  public function getPagePost()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("posts");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : NULL ;
		  $sql = "SELECT pt.*, pt.id as id, pg.id as pageid, pg.title".$core->dblang." as pagetitle, pg.slug as pgslug"
		  . "\n FROM posts AS pt"
		  . "\n LEFT JOIN pages AS pg ON pg.id = pt.page_id"
		  . "\n $where"
		  . "\n ORDER BY pt.page_id, pt.position". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processPost()
	   * 
	   * @return
	   */
	  public function processPost()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] =  _PO_TITLE_R;
		  
		  if (empty($core->msgs)) {
				  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'page_id' => intval($_POST['page_id']), 
				  'page_slug' => getValue("slug", "pages","id = '".intval($_POST['page_id'])."'"), 
				  'show_title' => intval($_POST['show_title']),
				  'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
				  'jscode' => $_POST['jscode'],
				  'active' => intval($_POST['active'])
			  );
			  
			  ($this->postid) ? $db->update("posts", $data, "id='" . (int)$this->postid . "'") : $db->insert("posts", $data);
			  $message = ($this->postid) ? _PO_UPDATED : _PO_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPagePlugins()
	   * 
	   * @return
	   */
	  public function getPagePlugins()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("plugins");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM plugins"
		  . "\n ORDER BY hasconfig DESC, title".$core->dblang . $pager->limit;;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	 

	  /**
	   * Content::processPlugin()
	   * 
	   * @return
	   */
	  public function processPlugin()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _PL_TITLE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'show_title' => intval($_POST['show_title']),
				  'alt_class' => sanitize($_POST['alt_class']),
				  'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
				  'info'.$core->dblang => sanitize($_POST['info'.$core->dblang]),
				  'jscode' => isset($_POST['jscode']) ? $_POST['jscode'] : "NULL",
				  'active' => intval($_POST['active'])
			  );
			  
			  if (!$this->id) {
				  $data['created'] = "NOW()";
			  }
			  
			  ($this->id) ? $db->update("plugins", $data, "id='" . (int)$this->id . "'") : $db->insert("plugins", $data);
			  $message = ($this->id) ? _PL_UPDATED : _PL_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPageModules()
	   * 
	   * @return
	   */
	  public function getPageModules()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("modules");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM modules"
		  . "\n ORDER BY title".$core->dblang . $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	 	  

	  /**
	   * Content::processModule()
	   * 
	   * @return
	   */
	  public function processModule()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _MO_TITLE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'info'.$core->dblang => sanitize($_POST['info'.$core->dblang]),
				  'theme' => (isset($_POST['theme']) and !empty($_POST['theme'])) ? sanitize($_POST['theme']) : 'NULL',
				  'metakey'.$core->dblang => sanitize($_POST['metakey'.$core->dblang]), 
				  'metadesc'.$core->dblang => sanitize($_POST['metadesc'.$core->dblang])
			  );

			  $db->update("modules", $data, "id='" . (int)$this->id . "'");
			  ($db->affected()) ? $wojosec->writeLog(_MO_UPDATED, "", "no", "module") . $core->msgOk(_MO_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

      /**
       * Content::getAvailablePlugins()
       * 
       * @return
       */
      public function getAvailablePlugins()
	  {
          global $db;
		  $pageid = ($this->pageid) ? "page_id='".$this->pageid."'" : "page_id='".$this->homeid."'";
		  $data = (isset($_GET['modid'])) ? "mod_id='".intval($_GET['modid'])."'" : $pageid;
		  
          $sql = "SELECT * FROM plugins" 
		  . "\n WHERE id NOT IN (SELECT plug_id FROM layout"
		  . "\n WHERE $data)";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
      }

      /**
       * Content::getPluginName()
       * 
       * @param mixed $name
       * @return
       */
      public function getPluginName($name)
	  {
          global $db, $core;
		  $name = sanitize($name);
          $sql = "SELECT title{$core->dblang} FROM plugins" 
		  . "\n WHERE plugalias = '" . $db->escape($name) . "'";
          $row = $db->first($sql);
          
		  return ($row) ? $row['title'.$core->dblang] : "NA";
      }

      /**
       * Content::getModuleName()
       * 
       * @param mixed $name
       * @return
       */
      public function getModuleName($name)
	  {
          global $db, $core;
		  $name = sanitize($name);
          $sql = "SELECT title{$core->dblang} FROM modules" 
		  . "\n WHERE modalias = '" . $db->escape($name) . "'";
          $row = $db->first($sql);
          
		  return ($row) ? $row['title'.$core->dblang] : "NA";
      }

      /**
       * Content::getModuleMetaData()
       * 
       * @return
       */
      public function getModuleMetaData()
	  {
          global $db, $core;
		  
          $sql = "SELECT * FROM modules" 
		  . "\n WHERE modalias = '" . $this->modalias . "'"
		  . "\n AND active = 1 AND system = 1";
          $row = $db->first($sql);
          
		  return $this->moduledata = $row;
      }

      /**
       * Content::getLayoutOptions()
       * 
       * @return
       */
      public function getLayoutOptions()
      {
          global $db, $core;

		  $pageid = ($this->pageid) ? "l.page_id='".$this->pageid."'" : "l.page_id='".$this->homeid."'";
		  $data = (isset($_GET['modid'])) ? "l.mod_id='".intval($_GET['modid'])."'" : $pageid;
		  
          $sql = "SELECT l.*, p.id as plid, p.title{$core->dblang}" 
		  . "\n FROM layout AS l" 
		  . "\n INNER JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE $data"
		  . "\n ORDER BY l.position ASC, p.title{$core->dblang} ASC";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
      }

      /**
       * Content::getPluginLayout()
       * 
       * @param mixed $place
       * @param bool $modalias
       * @return
       */
      public function getPluginLayout($place, $modalias = false)
      {
          global $db, $core;
		  
		  //$pageid = ($this->slug) ? "l.page_slug = '".$this->slug."'" : "l.page_slug = '".$this->homeslug."'";
		  if($this->slug) {
			  $pageid = "l.page_slug = '".$this->slug."'";
		  } elseif($this->homeid == 0 and $this->is_homemod and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			  $pageid = "l.modalias = '".$this->homeslug."'";
		  } else {
			  $pageid = "l.page_slug = '".$this->homeslug."'";
		  }
		  $data = ($modalias) ? "l.modalias = '".$this->modalias."'" : $pageid;
		  
          $sql = "SELECT l.*, p.id as plid, p.title{$core->dblang}, p.body{$core->dblang}, p.plugalias, p.hasconfig, p.system, p.show_title, p.alt_class, p.jscode" 
		  . "\n FROM layout AS l" 
		  . "\n LEFT JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE l.place = '".$place."'"
		  . "\n AND {$data}"
		  . "\n AND p.active = '1'"
		  . "\n ORDER BY l.position ASC";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : null;

      }
  
      /**
       * Content::getPluginAssets()
       * 
       * @return
       */
	  public function getPluginAssets()
	  {
		  global $db, $core;
		  
		  $pageid = ($this->slug) ? "l.page_slug = '" . $this->slug . "'" : "l.page_slug = '" . $this->homeslug . "'";
		  $data = ($this->modalias) ? "l.modalias = '" . $this->modalias . "'" : $pageid;
	
		  $sql = "SELECT l.*,  p.plugalias" 
		  . "\n FROM layout AS l" 
		  . "\n LEFT JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE {$data}" 
		  . "\n AND p.system = '1'" 
		  . "\n AND p.active = '1'";
		  $result = $db->fetch_all($sql);
	
		  if ($result) {
			  foreach ($result as $row) {
				  $tcssfile = PLUGDIR . $row['plugalias'] . "/theme/" . $core->theme . "/style.css";
				  $tjsfile = PLUGDIR . $row['plugalias'] . "/theme/" . $core->theme . "/script.js";
	
				  $cssfile = PLUGDIR . $row['plugalias'] . "/style.css";
				  $jsfile = PLUGDIR . $row['plugalias'] . "/script.js";
	
				  if (is_file($tcssfile)) {
					  print "<link href=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
				  } elseif (is_file($cssfile)) {
					  print "<link href=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	
				  }
	
				  if (is_file($tjsfile)) {
					  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/theme/" . $core->theme . "/script.js\"></script>\n";
				  } elseif (is_file($jsfile)) {
					  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/script.js\"></script>\n";
				  }
	
			  }
		  }
	  }

	  /**
	   * Content::getModuleAssets()
	   * 
	   * @return
	   */
	  public function getModuleAssets()
	  {
		  global $core;
		  
		  if ($this->modalias) {
			  $tcssfile = MODDIR . $this->modalias . "/theme/" . $core->theme . "/style.css";
			  $jsfile = MODDIR . $this->modalias . "/script.js";

			  if (is_file($tcssfile))
				  print "<link href=\"" . SITEURL . "/modules/" . $this->modalias . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  
			  if (file_exists($jsfile))
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/modules/" . $this->modalias . "/script.js\"></script>\n";
		  
		  } elseif ($this->module_name != '' or $this->module_id <> 0) {
			  $tcssfile = MODDIR . $this->module_name . "/theme/" . $core->theme . "/style.css";
			  $tjsfile = MODDIR . $this->module_name . "/theme/" . $core->theme . "/script.js";
			  
			  $cssfile = MODDIR . $this->module_name . "/style.css";
			  $jsfile = MODDIR . $this->module_name . "/script.js";

			  if (is_file($tcssfile)) {
				  print "<link href=\"" . SITEURL . "/modules/" . $this->module_name . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  } elseif (is_file($cssfile)) {
				  print "<link href=\"" . SITEURL . "/modules/" . $this->module_name . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

			  }

			  if (is_file($tjsfile)) {
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $this->module_name . "/theme/" . $core->theme . "/script.js\"></script>\n";
			  } elseif (is_file($jsfile)) {
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/modules/" . $this->module_name . "/script.js\"></script>\n";
			  }
		  }
	  }

	  /**
	   * Content:::getStyle()
	   * 
	   * @return
	   */
	  public function getThemeStyle()
	  {
		  global $core;
	      
		  $themevar = THEMEDIR . "/skins/" . $core->theme_var . ".css";
		  if ($core->lang_dir == "rtl") {
			  $css = THEMEDIR . "/css/style_rtl.css";
			  if (is_file($css)) {
				  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style_rtl.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  } else {
				  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  }
		  } else {
			  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
		  }
		  
		  if($core->theme_var and is_file($themevar)) {
			  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/skins/" . $core->theme_var . ".css\" rel=\"stylesheet\" type=\"text/css\" />\n";
		  }
	
	  }
	  
      /**
       * Content::getMenuTree()
       * 
       * @return
       */
      protected function getMenuTree()
	  {
		  global $db, $core;
		  $query = $db->query('SELECT * FROM menus ORDER BY parent_id, position');
		  
		  while ($row = $db->fetch($query)) {
			  $this->menutree[$row['id']] = array(
			        'id' => $row['id'],
					'name'.$core->dblang => $row['name'.$core->dblang], 
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->menutree;
	  }

      /**
       * Content::getMenuList()
       * 
       * @return
       */
      public function getMenuList()
	  {
		  global $db, $core;
		  $query = $db->query("SELECT *"
		  . "\n FROM menus " 
		  . "\n WHERE active = '1'"
		  . "\n ORDER BY parent_id, position");
          
		  $res = $db->numrows($query);
		  while ($row = $db->fetch($query)) {
			  $menulist[$row['id']] = array(
			        'id' => $row['id'],
					'name'.$core->dblang => $row['name'.$core->dblang], 
					'parent_id' => $row['parent_id'],
					'page_id' => $row['page_id'],
					'mod_id' => $row['mod_id'],
					'content_type' => $row['content_type'],
					'link' => $row['link'],
					'home_page' => $row['home_page'],
					'active' => $row['active'],
					'target' => $row['target'],
					'icon' => $row['icon'],
					'pslug' => $row['page_slug'],
			  );
			  
		  }
		  return ($res) ? $menulist : 0;
	  }

      /**
       * Content::getSortMenuList()
       * 
       * @param integer $parent_id
       * @return
       */
      public function getSortMenuList($parent_id = 0)
	  {
		  global $core;
		  
		  $submenu = false;
		  $class = ($parent_id == 0) ? "parent" : "child";

		  foreach ($this->menutree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($submenu === false) {
					  $submenu = true;
					  print "<ul>\n";
				  }
				  
				  print '<li id="list_' . $row['id'] . '">'
				  .'<div><a href="javascript:void(0)" id="item_'.$row['id'].'" data-title="' . $row['name'.$core->dblang] . '" class="delete">'
				  .'<img src="images/del.png" alt="" class="tooltip" title="'._DELETE.'"/></a>'
				  .'<a href="index.php?do=menus&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'.$core->dblang] . '</a></div>';
				  $this->getSortMenuList($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);
		  
		  if ($submenu === true)
			  print "</ul>\n";
	  }
 

	  /**
	   * Content::getMenu()
	   * 
	   * @param mixed $array
	   * @param integer $parent_id
	   * @return
	   */
	  public function getMenu($array, $parent_id = 0, $menuid = 'topmenu')
	  {
		  global $core;
		  
		  if(is_array($array) && count($array) > 0) {
				  
			  $submenu = false;
			  
			  $attr = (!$parent_id) ? ' class="menu-parent" id="' . $menuid . '"' : ' class="menu-submenu"';
			  foreach ($array as $key => $row) {
				  if ($row['parent_id'] == $parent_id) {
					  
					  if ($submenu === false) {
						  $submenu = true;	
						  print "<ul" . $attr . ">\n";
					  }
					  
					  $url = ($core->seo == 1) ? $core->site_url . '/' . sanitize($row['pslug'], 50) . '.html' : $url = $core->site_url . '/content.php?pagename=' . sanitize($row['pslug'], 50);
					  $active = ($row['pslug'] == $this->slug) ? " class=\"active\"" : "";
					  $mactive = ($row['pslug'] == $this->modalias) ? " class=\"active\"" : "";
					  $homeactive = (preg_match('/index.php/', $_SERVER['PHP_SELF'])) ? "active" : "";
					  $home = ($row['home_page']) ? " homepage" : "";
					  $icon = ($row['icon']) ? '<img src="' . UPLOADURL . 'menuicons/' . $row['icon'] . '" alt="" class="menuicon" />' : "";
					  
					  switch ($row['content_type']) {
						  case 'module':
							  $murl = ($core->seo == 1) ? $core->site_url . '/content/' . sanitize($row['pslug'], 50) . '/' : $murl = $core->site_url . '/modules.php?module=' . $row['pslug'];
							  $murl2 = $row['home_page'] ? SITEURL . '/index.php' : $murl;
							  $link = '<li' . $mactive . '><a href="' . $murl2 . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  break;
							  
						  case 'page':
							  ($row['home_page'] == 1) ? $link = '<li class="' . $homeactive . $home . '"><a href="' . SITEURL . '/index.php"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>' : 
							  $link = '<li' . $active . '><a href="' . $url . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  
							  break;
							  
						  case 'web':
							  $link = '<li><a href="' . $row['link'] . '" target="' . $row['target'] . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  break;
					  }
					  
					  print $link;
					  $this->getMenu($array, $key);
					  print "</li>\n";
				  }
			  }
			  unset($row);
			  
			  if ($submenu === true)
				  print "</ul>\n";
		  }	  
	  }

	  /**
	   * Content::getMenuDropList()
	   * 
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
	   * @return
	   */
	  public function getMenuDropList($parent_id, $level = 0, $spacer, $selected = false)
	  {
		  global $core;
		  foreach ($this->menutree as $key => $row) {
			  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
			  if ($parent_id == $row['parent_id']) {
				  print "<option value=\"" . $row['id'] . "\"".$sel.">";
				  
				  for ($i = 0; $i < $level; $i++)
					  print $spacer;
				  
				  print $row['name'.$core->dblang] . "</option>\n";
				  $level++;
				  $this->getMenuDropList($key, $level, $spacer, $selected);
				  $level--;
			  }
		  }
		  unset($row);
	  }

	  /**
	   * Content::processMenu()
	   * 
	   * @return
	   */
	  public function processMenu()
	  {
		  global $db, $core, $wojosec;
		  if (empty($_POST['name'.$core->dblang]))
			  $core->msgs['name'] = _MU_NAME_R;
		  
		  if ($_POST['content_type'] == "NA")
			  $core->msgs['content_type'] = _MU_TYPE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'name'.$core->dblang => sanitize($_POST['name'.$core->dblang]), 
				  'parent_id' => intval($_POST['parent_id']), 
				  'page_id' => (isset($_POST['page_id'])) ? intval($_POST['page_id']) : "DEFAULT(page_id)",
				  'page_slug' => (isset($_POST['page_id'])) ? getValue("slug", "pages","id = '".intval($_POST['page_id'])."'") : getValue("modalias", "modules","id = '".intval($_POST['mod_id'])."'"), 
				  'mod_id' => (isset($_POST['mod_id'])) ? intval($_POST['mod_id']) : "DEFAULT(mod_id)",
				  'slug' => paranoia($_POST['name'.$core->dblang]),
				  'content_type' => sanitize($_POST['content_type']),
				  'link' => (isset($_POST['web'])) ? sanitize($_POST['web']) : "NULL",
				  'target' => (isset($_POST['target'])) ? sanitize($_POST['target']) : "DEFAULT(target)",
				  'icon' => (isset($_POST['icon'])) ? sanitize($_POST['icon']) : "NULL",
				  'home_page' => intval($_POST['home_page']),
				  'active' => intval($_POST['active'])
			  );

			  if ($data['home_page'] == 1) {
				  $home['home_page'] = "DEFAULT(home_page)";
				  $db->update("menus", $home);
			  }
			  
			  ($this->id) ? $db->update("menus", $data, "id='" . (int)$this->id . "'") : $db->insert("menus", $data);
			  $message = ($this->id) ? _MU_UPDATED : _MU_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
			
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getMenuIcons()
	   * 
	   * @return
	   */
	  function getMenuIcons($selected = false)
	  {
		  $path = UPLOADS . 'menuicons/';
		  checkDir($path);
		  $res = '';
		  $handle = opendir($path);
		  $class = 'odd';
		  while (false !== ($file = readdir($handle))) {
			  $class = ($class == 'even' ? 'odd' : 'even');
			  if ($file != "." && $file != ".." && $file != "_notes" && $file != "index.php" && $file != "blank.png") {
				  $sel =  ($selected == $file) ? ' sel' : '';
				  $res .= "<div class=\"".$class.$sel."\">";
				  if ($selected == $file) {
					  $res .= "<input type=\"radio\" name=\"icon\" value=\"" . $file . "\" checked=\"checked\" />" 
					          . " <img src=\"".UPLOADURL . "/menuicons/" . $file."\" alt=\"\"/> ".$file;
				  } else {
					  $res .= "<input type=\"radio\" name=\"icon\" value=\"" . $file . "\" />" 
					           . " <img src=\"".UPLOADURL . "/menuicons/" . $file."\" alt=\"\"/> ".$file;
				  }
				  $res .= "</div>\n";
			  }
		  }
		  closedir($handle);
		  return $res;
	  }

	  /**
	   * Content::createSiteMap()
	   * 
	   * @return
	   */
	  private function createSiteMap()
	  {
		  global $db, $core;
  
		  $psql = "SELECT slug FROM pages ORDER BY created DESC";
		  $pages = $db->query($psql);
		  
		  $smap = "";
		  
		  @header('<?phpxml version="1.0" encoding="UTF-8"?>');
		  $smap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n';
		  $smap .= "<url>\r\n";
		  $smap .= "<loc>" . SITEURL . "/index.php</loc>\r\n";
		  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
		  $smap .= "</url>\r\n";

		  while ($row = $db->fetch($pages)) {
			 $url = ($core->seo == 1) ? SITEURL . '/' . $row['slug'] . '.html' : SITEURL . '/content.php?pagename=' . $row['slug'];
			  
			  $smap .= "<url>\r\n";
			  $smap .= "<loc>" . $url . "</loc>\r\n";
			  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $smap .= "<changefreq>weekly</changefreq>\r\n";
			  $smap .= "</url>\r\n";
		  }
          unset($row);
		  if(isset($_POST['am'])) {
		  $amsql = "SELECT slug FROM mod_articles WHERE active = 1 ORDER BY created DESC";
		  $articles = $db->query($amsql);
			  
			while ($row = $db->fetch($articles)) {
				$url = ($core->seo == 1) ? SITEURL . '/article/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=articles&amp;do=article&amp;artname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  if(isset($_POST['ds'])) {
		  $dssql = "SELECT slug FROM mod_digishop WHERE active = 1 ORDER BY created DESC";
		  $digishop = $db->query($dssql);
			  
			while ($row = $db->fetch($digishop)) {
				$url = ($core->seo == 1) ? SITEURL . '/digishop/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=digishop&amp;do=digishop&amp;productname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  if(isset($_POST['pf'])) {
		  $pfsql = "SELECT slug FROM mod_portfolio ORDER BY created DESC";
		  $portfolio = $db->query($pfsql);
			  
			while ($row = $db->fetch($portfolio)) {
				$url = ($core->seo == 1) ? SITEURL . '/portfolio/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=portfolio&amp;do=digishop&amp;productname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  $smap .= "</urlset>";
		  
		  return $smap;
	  }
	  
      /**
       * Content::writeSiteMap()
       * 
       * @return
       */
	  public function writeSiteMap()
	  {
		  global $core;
		  
		  $filename = WOJOLITE . 'sitemap.xml';

		  if (is_writable($filename)) {
			  $handle = fopen($filename, 'w');
			  fwrite($handle, $this->createSiteMap());
			  fclose($handle);
			  $core->msgOk(_SM_SMAPOK);
		  } else
			  $core->msgError(str_replace("[FILENAME]", $filename, _SM_SMERROR),false);
	  }
	  
      /**
       * Content::getContentType()
       * 
       * @param bool $selected
       * @return
       */
      public function getContentType($selected = false)
	  {
		  $modlist = $this->displayMenuModule();
          if($modlist) {
			  $arr = array(
					'page' => _CON_PAGE,
					'module' => _MODULE,
					'web' => _EXT_LINK
			  );
		  } else {
			  $arr = array(
					'page' => _CON_PAGE,
					'web' => _EXT_LINK
			  );  
		  }
		  
		  $contenttype = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $contenttype .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $contenttype .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $contenttype;
      }
	    
	  /**
	   * Content::getHomePageMeta()
	   * 
	   * @return
	   */
	  private function getHomePageMeta()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT p.title{$core->dblang}, p.description{$core->dblang}, p.keywords{$core->dblang}" 
		  . "\n FROM pages AS p" 
		  . "\n LEFT JOIN menus AS m ON p.id = m.page_id" 
		  . "\n WHERE m.home_page = '1'";
		  $row = $db->first($sql);
		  
		  return $row;
	  }

	  /**
	   * Content::getPageMeta()
	   * 
	   * @return
	   */
	  private function getPageMeta()
	  {
		global $core;
		
		$meta = "<title>" . $core->site_name . "  |  ";
		if ($this->slug) {
			$meta .= $this->title;
		} else {
			if (isset($_GET['mode'])) {
				$meta .= "Sitemap of " . $core->site_name;
			} else {
				$home = $this->getHomePageMeta();
				$meta .= $home['title'.$core->dblang];
			}
		}
		$meta .= "</title>\n";
		$meta .= "<meta name=\"description\" content=\"";
		if ($this->slug) {
			if ($this->description) {
				$meta .= $this->description;
			} else
				$meta .= $core->metadesc;
		} else {
			$home = $this->getHomePageMeta();
			$meta .= $home['description'.$core->dblang];
		}
		$meta .= "\" />\n";
		$meta .= "<meta name=\"keywords\" content=\"";
		if ($this->slug) {
			if ($this->keywords) {
				$meta .= $this->keywords;
			} else
				$meta .= $core->metakeys;
		} else {
			$home = $this->getHomePageMeta();
			$meta .= $home['keywords'.$core->dblang];
		}
		$meta .= "\" />\n";
		return $meta;
	  }

	  /**
	   * Content::getModuleMeta()
	   * 
	   * @return
	   */
	  private function getModuleMeta()
	  {
		  global $core;
          
		  $modmeta = WOJOLITE . 'admin/modules/'.$this->modalias.'/meta.php';
		  if (file_exists($modmeta))
			 include($modmeta);  
	  }

	  /**
	   * Content::getMeta()
	   * 
	   * @return
	   */
	  public function getMeta()
	  {
		  global $core;
		  
		  $meta = '';
		  $meta = "<meta charset=\"utf-8\">\n";
		 /* if ($this->modalias) {
			  $meta .= $this->getModuleMeta();
		  } else {
			  $meta .= $this->getPageMeta();
		  }*/
		   $meta .= $this->getPageMeta();
		  $meta .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" .SITEURL ."/assets/favicon.ico\" />\n";
		  $meta .= "<meta name=\"publisher\" content=\"" . $core->site_name . "\" />\n";
		  $meta .= "<meta name=\"dcterms.rights\" content=\"" . $core->site_name . " &copy; All Rights Reserved\" >\n";
		  $meta .= "<meta name=\"robots\" content=\"index\" />\n";
		  $meta .= "<meta name=\"robots\" content=\"follow\" />\n";
		  $meta .= "<meta name=\"revisit-after\" content=\"1 day\" />\n";
		  $meta .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\" />\n";
		  return $meta;
	  }
	  
	  /**
	   * Content::processCurrency()
	   * 
	   * @return
	   */
	  public function processCurrency()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['currency']))
			  $core->msgs['currency'] =  _CUR_TITLE_R;
			  
		  if ($this->currencyExists($_POST['currency'],$this->postid))
			  $core->msgs['currency'] = _CUR_UNI_NAME;	
		  
		  if (empty($_POST['currency_symbol']))
			  $core->msgs['currency_symbol'] =  _CUR_SYM_TITLE_R;
		  
		  if (empty($core->msgs)) {
				  $data = array(
				  'currency'=> sanitize($_POST['currency']), 
				  'currency_symbol' => sanitize($_POST['currency_symbol']),				  
				  'active' => intval($_POST['active'])
			  );
			  
			  
			  
			  ($this->postid) ? $db->update("res_currency_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_currency_master", $data);
			  $message = ($this->postid) ? _CUR_UPDATED : _CUR_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  
	   /**
	   * Content::getCurrency()
	   * 
	   * @return
	   */
	  public function getCurrency()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_currency_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *"
		  . "\n FROM res_currency_master"		  
		  . "\n ORDER BY currency". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::currencyExists()
	   * 
	   * @return
	   */
	  public function currencyExists($currency,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, currency" 
							  . "\n FROM res_currency_master " 
							  . "\n WHERE currency = '" . sanitize($currency) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	   
	  /**
	   * Content::getCurrencylist()
	   * 
	   * @return
	   */
	  public function getCurrencylist()
	  {
		  global $db, $core;
		  
		   $sql =" SELECT id,currency,currency_symbol"
				. "\n  FROM `res_currency_master`";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::processCountry()
	   * 
	   * @return
	   */
	  public function processCountry()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['country_name']))
			  $core->msgs['country_name'] =  _COUN_TITLE_R;
			  
		  if ($this->countryExists($_POST['country_name'],$this->postid))
			  $core->msgs['country_name'] = _COUN_UNI_NAME;	
		  
		  if (empty($_POST['country_code']))
			  $core->msgs['country_code'] =  _COUN_CODE_R;
			  
		  if (empty($_POST['currency_id']))
			  $core->msgs['currency_id'] =  _COUN_CURRENCYID;
			  
			  
		  
		  if (empty($core->msgs)) {
				  $data = array(
				  'country_name'=> sanitize($_POST['country_name']), 
				  'country_code' => sanitize($_POST['country_code']),
				  'currency_id' => intval($_POST['currency_id']), 				  
				  'active' => intval($_POST['active']),
				  
			  );
			  
			   if(empty($this->postid))
			  {
				  $data['created_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }
			  
			  ($this->postid) ? $db->update("res_country_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_country_master", $data);
			  $message = ($this->postid) ? _COUN_UPDATED : _COUN_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_country_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
	   * Content::countryExists()
	   * 
	   * @return
	   */
	  public function countryExists($country,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, country_name" 
							  . "\n FROM   res_country_master " 
							  . "\n WHERE country_name = '" . sanitize($country) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	  /**
	   * Content::getCountry()
	   * 
	   * @return
	   */
	  public function getCountry()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_country_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT coun.*,cur.currency"
		  . "\n FROM res_country_master AS coun"
		  ."\n LEFT JOIN res_currency_master AS cur ON cur.id = coun.currency_id"		  
		  . "\n ORDER BY country_name". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::getCountrylist()
	   * 
	   * @return
	   */
	  public function getCountrylist()
	  {
		  global $db, $core;
		  
		   $sql =" SELECT id,country_name"
				. "\n  FROM `res_country_master`";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	   /**
       * Content::processState()
       * 
       * @return
       */
	  public function processState()
	  {
		  global $db, $core, $wojosec;
		 
		  
		  if (empty($_POST['state_name']))
			  $core->msgs['state_name'] = _S_TITLE_R;
			  
		  if (empty($_POST['country_id']))
			  $core->msgs['country_id'] = "please select Your Country";
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'state_name' => sanitize($_POST['state_name']),
				  'country_id' => sanitize($_POST['country_id']),
				  'active' => sanitize($_POST['active'])
			  );
			  
			   if(empty($this->postid))
			  {
				  $data['create_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }

			  
			  ($this->postid) ? $db->update("res_state_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_state_master", $data);
			  $message = ($this->postid) ? _ST_UPDATED : _ST_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_state_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	   /**
       * Content::getstate()
       * 
       * @return
       */
	  
	  public function getstate()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_state_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT sm.*,cm.country_name"
		  . "\n FROM res_state_master as sm"
		  ."\n left join res_country_master as cm on cm.id=sm.country_id". $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	  /**
	   * Content::getstatelist()
	   * 
	   * @return
	   */
	  public function getstatelist()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id,state_name"
		  . "\n FROM res_state_master";
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	   /**
       * Content::getcity()
       * 
       * @return
       */
	  
	  public function getcity()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_city_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT c.* ,s.state_name"
		  . "\n FROM res_city_master as c"
		  . "\n LEFT JOIN res_state_master as s ON s.id=c.state_id". $pager->limit;
		  	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	  //city exits code here
	   public function cityExists($city,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, city_name" 
							  . "\n FROM res_city_master " 
							  . "\n WHERE city_name = '" . sanitize($city) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	   /**
	   * Content::getCountrylist()
	   * 
	   * @return
	   */
	  public function getCitylist()
	  {
		  global $db, $core;
		  
		   $sql =" SELECT id,city_name"
				. "\n  FROM `res_city_master`";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
       * Content::processCity()
       * 
       * @return
       */
	  public function processCity()
	  {
		  global $db, $core, $wojosec;
		 
		  
		  if (empty($_POST['city_name']))
			  $core->msgs['city_name'] = _CI_TITLE_R;
			
		
		  if ($this->cityExists($_POST['city_name'],$this->postid))
			  $core->msgs['city_name'] = _CI_UNI_NAME;	 
			  
		 if (empty($_POST['state_id']))
			  $core->msgs['state_id'] = _CI_STATE_ID_R;
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'city_name' => sanitize($_POST['city_name']),
				  'state_id' => sanitize($_POST['state_id']),
				  'active' => sanitize($_POST['active'])
				  
			  );
			  if(empty($this->postid))
			  {
				  $data['create_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }
			
			  ($this->postid) ? $db->update("res_city_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_city_master", $data);
			  $message = ($this->postid) ? _CI_UPDATED : _CI_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "city") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 
	  
	   /**
	   * Content::getTimezonelist()
	   * 
	   * @return
	   */
	  public function getTimezonelist()
	  {
		  global $db, $core;
		  
		   $sql =" SELECT id,zone"
				. "\n  FROM `res_timezone`";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	    /**
	   * Content::processcompany()
	   * 
	   * @return
	   */
	  public function processcompany()
	  {
		  global $db, $core, $wojosec;
				  
		  if (empty($_POST['company_name']))
			  $core->msgs['company_name'] = "Please Enter Your Company Name";
			  
		  if (empty($_POST['country_id']))
			  $core->msgs['country_id'] = "Please Select Your Country Name";
			  
		 if (empty($_POST['state_id']))
			  $core->msgs['state_id'] = "Please Select Your State Name";
			  
	     if (empty($_POST['city_id']))
			  $core->msgs['city_id'] = "Please Select Your City Name";
			  
		 if (empty($_POST['timezone']))
			  $core->msgs['timezone'] = "Please Select Your Time Zone";
			  
	    if (empty($_POST['address1']))
			  $core->msgs['address1'] = "Please Enter Your Address";
			  
		if (empty($_POST['phone_number']))
			  $core->msgs['phone_number'] = "Please Enter Phone Number";
			  
	  /* if (empty($_POST['fax_number']))
			  $core->msgs['fax_number'] = "Please Type Your Fax Number";*/
			  
	  if (empty($_POST['zipcode']))
			  $core->msgs['zipcode'] = "Please Type Your Zipcode";
	
			  
     if (empty($_POST['website']))
			  $core->msgs['website'] = "Please Type Your Website Name";
			  
	 if (empty($_POST['application_id']))
			  $core->msgs['application_id'] = "Please Type Your Application ID";
			  
     if (empty($_POST['specialist']))
			  $core->msgs['specialist'] = "Please Type Your Specialty";
			  
		
			  
			   if (!empty($_FILES['logo']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['logo']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['logo']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }
		  //if(!empty($_POST['logo']))
			//{
			//	$gallery_cat_id = implode( ",", $_POST['logo']);
			//}
		  
		  if (empty($core->msgs)) {
				  $data = array(				  
				  'company_name' =>sanitize($_POST['company_name']), 
				  'country_id' => intval($_POST['country_id']),
				  'state_id' => intval($_POST['state_id']),
				  'city_id' => intval($_POST['city_id']),
				  'address1' => sanitize($_POST['address1']),
				  'address2' => sanitize($_POST['address2']),
				  'timezone'=> sanitize ($_POST['timezone']),
				  'phone_number'=> sanitize ($_POST['phone_number']),
				  'zipcode'=> intval ($_POST['zipcode']),
				  'phone_number1'=> sanitize ($_POST['phone_number1']),
				  'fax_number'=> intval ($_POST['fax_number']),				 
				  'website'=> sanitize($_POST['website']),
				  'application_id'=> intval($_POST['application_id']),
				  'specialist'=> sanitize($_POST['specialist']),
				  'active' => intval($_POST['active'])
				  
			  );
			 
			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['logo']['name'], strrpos($_FILES['logo']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['logo'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/avatars/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->postid) {
				  $avatar = getValue("logo","res_company_master","id = '".$this->postid."'");
				  if (!empty($_FILES['logo']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['logo'] = $name;
				  } else {
					  $data['logo'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['logo']['name'])) 
				  $data['logo'] = $name;
			  }
			  //print_r($data);
			   if(empty($this->postid))
			  {
				  $data['create_date'] = "NOW()";
			  }
			  else{
				  $data['modified_date'] = "NOW()";
			  }
			  ($this->postid) ? $db->update("res_company_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_company_master", $data);
			  $message = ($this->postid) ? _CM_UPDATED : _CM_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	    /**
	   * Content::getCompany()
	   * 
	   * @return
	   */
	  public function getCompany()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_company_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  //$where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : NULL ;
		  $sql = "SELECT cm.*,coun.country_name,stat.state_name,ct.city_name"		 
		  . "\n FROM  res_company_master as cm"
		  ."\n LEFT JOIN res_country_master as coun ON coun.id= cm.country_id"
		  ."\n LEFT JOIN res_state_master as stat ON stat.id = cm.state_id"
          ."\n LEFT JOIN res_city_master as ct ON ct.id = cm.city_id". $pager->limit;
          $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	  
	   /**
	   * Content::getstatelist()
	   * 
	   * @return
	   */
	  public function getcompanylist()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id,company_name"
		  . "\n FROM res_company_master";
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	   /**
	   * Content::processlocation()
	   * 
	   * @return
	   */
	  public function processlocation()
	  {
	  	  error_reporting(0);
		  global $db, $core, $wojosec;
		  if (empty($_POST['company_id']))
			  $core->msgs['company_id'] = "Please Enter Your Company Name";
			  
		  if (empty($_POST['country_id']))
			  $core->msgs['country_id'] = "Please Select Your Country Name";
			  
		 if (empty($_POST['state_id']))
			  $core->msgs['state_id'] = "Please Select Your State Name";
			  
	     if (empty($_POST['city_id']))
			  $core->msgs['city_id'] = "Please Select Your City Name";			  
		 
			  
	    if (empty($_POST['address1']))
			  $core->msgs['address1'] = "Please Enter Your Address";
			  
		if(!empty($_POST['zoom_level']) && !ctype_digit($_POST['zoom_level'])){			
			$core->msgs['zoom_level'] = "Please enter Zoom level value as numeric";			
		}
			  
		/*if (empty($_POST['phone_number']))
			  $core->msgs['phone_number'] = "Please Enter Phone Number";
			  
	    if (empty($_POST['fax_number']))
			  $core->msgs['fax_number'] = "Please Type Your Fax Number";
			  
	    if (empty($_POST['zipcode']))
			  $core->msgs['zipcode'] = "Please Type Your Zipcode";	 
			 
	   if (empty($_POST['sales_tax']))
			  $core->msgs['sales_tax'] = "Please Type Your Sales Tax";*/
			  
	   if (!empty($_FILES['first_data_file_name']['name'])) {
			  if (!preg_match("/(\.pem)$/i", $_FILES['first_data_file_name']['name'])) {
				  $core->msgs['first_data_file_name'] = "Invalid File. Please upload a File with extension: pem";
		 }
			  
		 /* $file_info = getimagesize($_FILES['first_data_file_name']['tmp_name']);
		  if(empty($file_info))
			  $core->msgs['first_data_file_name'] = _CG_LOGO_R;*/
		  }
		  
		  if (!empty($_FILES['banner_image']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['banner_image']['name'])) {
				  $core->msgs['banner_image'] =   "Invalid File. Please upload a valid banner image";
			  }
			  $file_info = getimagesize($_FILES['banner_image']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['banner_image'] = "Invalid File. Please upload a valid banner image";
		  }
		  
		  if (empty($core->msgs)) {
				  $data = array(
				  'location_name' =>sanitize($_POST['location_name']), 
				  'zone_id' => intval($_POST['zone_id']),
				  'restaurant_name' =>sanitize($_POST['restaurant_name']), 				  
				  'company_id' =>sanitize($_POST['company_id']), 
				  'country_id' => intval($_POST['country_id']),
				  'state_id' => intval($_POST['state_id']),
				  'city_id' => intval($_POST['city_id']),
				  'is_same_company_address' => sanitize($_POST['is_same_company_address']),
				  'address1' => sanitize($_POST['address1']),
				  'address2' => sanitize($_POST['address2']),				 
				  'phone_number'=> sanitize($_POST['phone_number']),
				  'zipcode'=> sanitize($_POST['zipcode']),
				  'phone_number1'=> sanitize($_POST['phone_number1']),
				  'fax_number'=> sanitize($_POST['fax_number']),				 
				  'website'=> sanitize($_POST['website']),
				  'location_slogan'=> sanitize($_POST['location_slogan']),
				  'latitude'=> $_POST['latitude'],
				  'longitude'=> $_POST['longitude'],
				  'zoom_level'=> sanitize($_POST['zoom_level']),
				  'pick_up_available'=> sanitize($_POST['pick_up_available']),
				  'pickup_time'=> sanitize($_POST['pickup_time']),
				  'delivery_available'=> sanitize($_POST['delivery_available']),
				  'delivery_time'=> sanitize($_POST['delivery_time']),
				  'restorant_time'=> sanitize($_POST['restorant_time']),		//Restorant timing
				  'dinein_available'=> intval($_POST['dinein_available']),
				  'delivery_fee'=> sanitize($_POST['delivery_fee']),
				  'additional_fee'=> sanitize($_POST['additional_fee']),
				  'gratuity'=> sanitize($_POST['gratuity']),
				  'sales_tax'=> intval($_POST['sales_tax']),
				  'max_advance_order'=> sanitize($_POST['max_advance_order']),
				  'sales_tax_id'=> sanitize($_POST['sales_tax_id']),
				  'emai_disclaimer'=> sanitize($_POST['emai_disclaimer']),				  
				  'site_id'=> sanitize($_POST['site_id']),
				  'pos_password'=> sanitize($_POST['pos_password']),
				  'pos_ip'=> sanitize($_POST['pos_ip']),
				  'is_cash_on_delivery'=> sanitize($_POST['is_cash_on_delivery']),
            	  'is_paypal'=> sanitize($_POST['is_paypal']),
				  'is_authorize'=> sanitize($_POST['is_authorize']),
				  'is_first_data'=> sanitize($_POST['is_first_data']),
				  'is_mercury'=> sanitize($_POST['is_mercury']),
				  'is_vm'=> sanitize($_POST['is_vm']),
				  'is_internet_secure'=> sanitize($_POST['is_internet_secure']),
				  'paypal_email_id'=> sanitize($_POST['paypal_email_id']),
				  'paypal_password'=> sanitize($_POST['paypal_password']),
				  'paypal_signature'=> sanitize($_POST['paypal_signature']),
				  'authorizr_api_id'=> sanitize($_POST['authorizr_api_id']),
				  'authorizze_trans_key'=> sanitize($_POST['authorizze_trans_key']),
				  'authorize_hash_key'=> sanitize($_POST['authorize_hash_key']),
				  /*'first_data_file_name'=> sanitize($_POST['first_data_file_name']),*/
				  'merchant_id'=> sanitize($_POST['merchant_id']),
				  'merchant_password'=> sanitize($_POST['merchant_password']),
				  'vm_merchant_id'=> sanitize($_POST['vm_merchant_id']),
				  'vm_user_id'=> sanitize($_POST['vm_user_id']),
				  'vm_pin'=> sanitize($_POST['vm_pin']),
				  'internet_secure_getwayid'=> sanitize($_POST['internet_secure_getwayid']),
				  'cash_payment_id'=> sanitize($_POST['cash_payment_id']),
				  'online_payment_id'=> sanitize($_POST['online_payment_id']),
				  'order_email1'=> sanitize($_POST['order_email1']),
				  'order_email2'=> sanitize($_POST['order_email2']),
				  'order_email3'=> sanitize($_POST['order_email3']),
				  'order_email4'=> sanitize($_POST['order_email4']),
				  'custom_header_html'=> sanitize($_POST['custom_header_html']),
				  'disclaimer'=> sanitize($_POST['disclaimer']),
				  'confirm_order_msg'=> sanitize($_POST['confirm_order_msg']),
				  'hide_price_in_menu'=> sanitize($_POST['hide_price_in_menu']),
				  'hide_price_in_option'=> sanitize($_POST['hide_price_in_option']),
				  'menu_iteam_comments'=> sanitize($_POST['menu_iteam_comments']),
				  'order_comments'=> sanitize($_POST['order_comments']),
				  'delivery_instruction'=> sanitize($_POST['delivery_instruction']),
				  'daylight_saving'=> sanitize($_POST['daylight_saving']),
				  'allowed_unconfirmed_order'=> sanitize($_POST['allowed_unconfirmed_order']),	
				  'banner_link'=> sanitize($_POST['banner_link']),			 
				  'active' => intval($_POST['active']),
				  'create_date' => "NOW()"				  
			  );
			  
			  //echo "<pre>"; print_r($data); exit();			 
			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['first_data_file_name']['name'], strrpos($_FILES['first_data_file_name']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['first_data_file_name'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/firstdata/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->postid) {
				  $avatar = getValue("first_data_file_name","res_location_master","id = '".$this->postid."'");
				  if (!empty($_FILES['first_data_file_name']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['first_data_file_name'] = $name;
				  } else {
					  $data['first_data_file_name'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['first_data_file_name']['name'])) 
				  $data['first_data_file_name'] = $name;
			  }
			  
			  /*************Banner image upload, starts here********************/			  
			  
			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['banner_image']['name'], strrpos($_FILES['banner_image']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['banner_image'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/banner/';
			 // $als->NewWidth = $core->avatar_w;
			  //$als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->postid) {
				  $avatar = getValue("banner_image","res_location_master","id = '".$this->postid."'");
				  if (!empty($_FILES['banner_image']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['banner_image'] = $name;
				  } else {
					  $data['banner_image'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['banner_image']['name'])) 
				  $data['banner_image'] = $name;
			  }
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			    }
			  
			  /*************Banner image upload, ends here********************/	
			  
			  ($this->postid) ? $db->update("res_location_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_location_master", $data);
			  $message = ($this->postid) ? _LM_UPDATED : _LM_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	   /**
	   * Content::getlocation()
	   * 
	   * @return
	   */
	  public function getlocation($locationid)
	  {
		  global $db, $core, $pager;
		
		 if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "where cm.id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_location_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  //$where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : NULL ;
		  $sql = "SELECT cm.*,coun.country_name,stat.state_name,ct.city_name,cn.company_name"		 
		  . "\n FROM  res_location_master as cm"
		  ."\n LEFT JOIN res_country_master as coun ON coun.id= cm.country_id"
		  ."\n LEFT JOIN res_state_master as stat ON stat.id = cm.state_id"
          ."\n LEFT JOIN res_city_master as ct ON ct.id = cm.city_id"
		  ."\n LEFT JOIN res_company_master as cn ON cn.id = cm.company_id"
		  ."\n" . $adsql. $pager->limit;
          $row = $db->fetch_all($sql);
		  		   
		  return ($row) ? $row : 0;
	  }
	  
	   /**
	   * Content::getlocationlist()
	   * 
	   * @return
	   */
	  public function getlocationlist($locationid)
	  {
		  global $db, $core;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  $sql = "SELECT id,location_name"
		  . "\n FROM res_location_master"." ".$adsql;
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::locationTimeIdExists()
	   * 
	   * @return
	   */
	  public function locationTimeIdExists($locationid,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& location_id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, location_id" 
							  . "\n FROM res_location_time_master " 
							  . "\n WHERE location_id = '" . sanitize($locationid) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	  /**
	   * Content::processTimeLocation()
	   * 
	   * @return
	   */
	  public function processTimeLocation()
	  {
	  	error_reporting(0);
		  global $db, $core, $wojosec;
		  if (empty($_POST['location_id']))
			  $core->msgs['location_id'] = "Please Select Your Location Name";	
			    
		  if ($this->locationTimeIdExists($_POST['location_id'],$this->postid))
			  $core->msgs['location_id'] = _LSE_UNI_NAME;
			  
		  if (empty($core->msgs)) {
		  			
			  foreach ($_POST['days'] as $row)
			{	
				if(!empty($row['breakfast_start']))
				{
					$aa = explode(" ", $row['breakfast_start']);
					$breakfast_start = $aa[0].":00 ".$aa[1];
				}	else { $breakfast_start = "";}
				
				if(!empty($row['breakfast_end']))
				{
					$aa = explode(" ", $row['breakfast_end']);
					$breakfast_end = $aa[0].":00 ".$aa[1];
				} else{ $breakfast_end = ""; }
				
				if(!empty($row['breakfast_last']))
				{
					$aa = explode(" ", $row['breakfast_last']);
					$breakfast_last = $aa[0].":00 ".$aa[1];
				}	else{$breakfast_last = "";}
				
				if(!empty($row['launch_start']))
				{
					$aa = explode(" ", $row['launch_start']);
					$launch_start = $aa[0].":00 ".$aa[1];
				}	else{$launch_start = "";}
				
				if(!empty($row['launch_end']))
				{
					$aa = explode(" ", $row['launch_end']);
					$launch_end = $aa[0].":00 ".$aa[1];
				}else{$launch_end = "";	}
				
				if(!empty($row['launch_last']))	{
					$aa = explode(" ", $row['launch_last']);
					$launch_last = $aa[0].":00 ".$aa[1];
				}else{$launch_last = "";}
				
				
				if(!empty($row['dinner_start']))
				{
					$aa = explode(" ", $row['dinner_start']);
					$dinner_start = $aa[0].":00 ".$aa[1];
				} else{	$dinner_start = "";	}
				
				if(!empty($row['dinner_end']))
				{
					$aa = explode(" ", $row['dinner_end']);
					$dinner_end = $aa[0].":00 ".$aa[1];
				} else{	$dinner_end = ""; }
				
				if(!empty($row['dinner_last']))
				{
					$aa = explode(" ", $row['dinner_last']);
					$dinner_last = $aa[0].":00 ".$aa[1];
				} else	{ $dinner_last = ""; }		
			
				  $data = array(				  
				  'location_id' =>sanitize($_POST['location_id']),
				  'have_time_menu' =>sanitize($_POST['have_time_menu']),
				  'days'=> sanitize ($row['day']),
				  'day_start_time'=> sanitize ($row['day_start_time']),
				  'day_end_time'=> sanitize ($row['day_end_time']),
				  'last_order_time'=> sanitize($row['last_order_time']),
				  'open_24hours'=> intval ($row['open_24hours']),
				  'is_holidays'=> sanitize ($row['is_holidays']),
				  'breakfast_start'=> $breakfast_start,
				  'breakfast_end'=> $breakfast_end,
				  'breakfast_last'=> $breakfast_last,
				  'launch_start'=> $launch_start,
				  'launch_end'=> $launch_end,
				  'launch_last'=> $launch_last,
				  'dinner_start'=> $dinner_start,
				  'dinner_end'=> $dinner_end,
				  'dinner_last'=> $dinner_last,
				  
				  'd_morning_start'=> sanitize ($row['d_morning_start']),
				  'd_morning_end'=> sanitize ($row['d_morning_end']),
				  'd_evening_start'=> sanitize ($row['d_evening_start']),
				  'd_evening_end'=> sanitize ($row['d_evening_end'])
				   );
				   		
				if(empty($this->postid))
			  		{
				 	 $data['created_date'] = "NOW()";
					 
					}
			   else{
				   $data['modified_date'] = "NOW()";
			      }	 
			  ($this->postid) ? $db->update("res_location_time_master", $data, "days='" . $row['day'] . "' AND location_id='" . $this->postid . "' ") : $db->insert("res_location_time_master", $data);
			   }
			  $message = ($this->postid) ? _LTM_UPDATED : _LTM_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	    /**
	   * Content::getCompany()
	   * 
	   * @return
	   */
	  public function getTime($locationid)
	  {
		  global $db, $core, $pager;
		  
		   if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "&& tm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_location_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  //$where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : NULL ;
		  $sql = "SELECT tm.id,tm.location_id,tm.days,ln.location_name"		 
		  . "\n FROM  res_location_time_master as tm"
		  ."\n LEFT JOIN res_location_master as ln ON ln.id = tm.location_id"
		  ."\n WHERE tm.days = 'Monday'".$adsql. $pager->limit;
          $row = $db->fetch_all($sql);
           
		  
		   
		   
		  return ($row) ? $row : 0;
	  }
	   /**
	   * Content::getTimeList()
	   * 
	   * @return
	   */
	  public function getTimeList()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id,location_id *"
		  . "\n FROM res_location_timing_master";
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
       * Content::processHolidays()
       * 
       * @return
       */
	  public function processHolidays()
	  {
		  global $db, $core, $wojosec;	
		
		
		
		  if (empty($_POST['location_id']))
			  $core->msgs['location_id'] = "pleaser select your location";
		   
		  if (empty($core->msgs)) {
		  		 
		   $length = count($_POST['holiday_date']);
			for($i=0; $i< $length; $i++){	
			  $data = array(
				  'location_id' => sanitize($_POST['location_id']),
				  'holiday_date' => sanitize($_POST['holiday_date'][$i]),
				  'holiday_description'=> sanitize($_POST['holiday_description'][$i])
				  
			  );			  
			  ($this->postid) ? $db->update("res_holiday_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_holiday_master", $data);			
			  }
			  $message = ($this->postid) ? _HT_UPDATED : _HT_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_state_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Content::getholiday()
       * 
       * @return
       */
	  
	  public function getholiday($locationid)
	  {
		  global $db, $core, $pager;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE sm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_holiday_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT sm.*,hm.location_name"
		  . "\n FROM res_holiday_master as sm"
		  ."\n left join res_location_master as hm on hm.id=sm.location_id"." ".$adsql. $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	 
	  
	 Public function locationTimeEdit($locationid)
	 {
	 	global $db, $core;
		
		 $sql ="SELECT * FROM `res_location_time_master` WHERE location_id = '" . $locationid. "' ";
		 $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	 
	 } 	
	  /**
	   * Content::menuExists()
	   * 
	   * @return
	   */
	  public function menuExists($menu_name,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, menu_name" 
							  . "\n FROM res_menu_master " 
							  //. "\n WHERE menu_name = '" . sanitize($menu_name) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }	  
	  
	   /**
	   * Content::getstatelist()
	   * 
	   * @return
	   */
	  public function getmenulocationlist()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id,location_name"
		  . "\n FROM res_menu_location";
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	   /**
	   * Content::getmenucategoryList()
	   * 
	   * @return
	   */
	  public function getmenucategoryList($locationid)
	  {
		  global $db, $core;
		  
		  if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE mlm.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		$sql = "SELECT sm.id,sm.category_name"
		   . "\n FROM res_category_master as sm"
		   . "\n left join res_menu_master as lm on lm.id=sm.menu_id"
		   . "\n left join res_menu_location_mapping as mlm on mlm.menu_id =lm.id ".$adsql.""   
		   ."\n GROUP BY sm.id";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	   
	   
	   /**
       * Content::processlocationsetting()
       * 
       * @return
       */
	  public function processlocationsetting()
	  {
		  global $db, $core, $wojosec;
	   if (empty($_POST['location_id']))
			  $core->msgs['location_id'] =  _LSE_TITLE_R;
			  
		  if ($this->locationSettingIdExists($_POST['location_id'],$this->postid))
			  $core->msgs['location_id'] = _LSE_UNI_NAME;
		if(!empty($_POST['username']))
		 {	  
		 	if (!$this->isValidEmail($_POST['username']))
			  	$core->msgs['username'] = _UR_EMAIL_R2;	
		}	  
		 if(!empty($_POST['support_email1']))
		 {
		 	 if (!$this->isValidEmail($_POST['support_email1']))
			  $core->msgs['support_email1'] = _UR_EMAIL_R2;
		 }
		 if(!empty($_POST['support_email2']))
		 {
		 	if (!$this->isValidEmail($_POST['support_email2']))
			  $core->msgs['support_email2'] = _UR_EMAIL_R2;
		 }
		 if(!empty($_POST['support_email3']))
		 {
		 	if (!$this->isValidEmail($_POST['support_email3']))
			  $core->msgs['support_email3'] = _UR_EMAIL_R2;
		 }
		 if(!empty($_POST['support_email4']))
		 {
		 	 if (!$this->isValidEmail($_POST['support_email4']))
			  $core->msgs['support_email4'] = _UR_EMAIL_R2;
		 }			 
			  
			 
		  if (empty($core->msgs)) {
			  
			  $data = array(
				    'location_id' => intval($_POST['location_id']),
				    'smtp_host' => sanitize($_POST['smtp_host']),
				    'username' => sanitize($_POST['username']),
				    'password' => sanitize($_POST['password']), 	
					'fax_url' => sanitize($_POST['fax_url']),
					'fax_user_name' => sanitize($_POST['fax_user_name']),
					'fax_password' => sanitize($_POST['fax_password']),
					'google_api_key' => sanitize($_POST['google_api_key']),
					'google_anaytics_script' => sanitize($_POST['google_anaytics_script']),
					'support_email1' => sanitize($_POST['support_email1']),
					'support_email2' => sanitize($_POST['support_email2']),
					'support_email3' => sanitize($_POST['support_email3']),
					'support_email4' => sanitize($_POST['support_email4']),
					'fb_url' => sanitize($_POST['fb_url']),
					'twitter_url' => sanitize($_POST['twitter_url']),
					'youtube_url' => sanitize($_POST['youtube_url']),
					'pininterest_url' => sanitize($_POST['pininterest_url']),
					'yelpUrl' => sanitize($_POST['yelpUrl'])
				  
			  );
			 
			  ($this->postid) ? $db->update("res_location_setting", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_location_setting", $data);
			  $message = ($this->postid) ? _LS_UPDATED : _LS_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_location_setting") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
	   * Content::locationSettingIdExists()
	   * 
	   * @return
	   */
	  public function locationSettingIdExists($locationid,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT id, location_id" 
							  . "\n FROM res_location_setting " 
							  . "\n WHERE location_id = '" . sanitize($locationid) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	  
	  /**
       * Content::getlocationsetting()
       * 
       * @return
       */
	  
	  public function getlocationsetting()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_location_setting");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT sm.*,ls.location_name"
		  . "\n FROM res_location_setting as sm"
		  ."\n left join res_location_master as ls on ls.id=sm.location_id". $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }	
	  /**
	   * content::isValidEmail()
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
       * Content::processDelivaryArea()
       * 
       * @return
       */
	  public function processDelivaryArea()
	  {
		  global $db, $core, $wojosec;
		  
		 
		 if (empty($_POST['location_id']))
			  $core->msgs['location_id'] = "Please Select Your Location Name";
			  
		 if ($this->locationExists($_POST['location_id']))
			  $core->msgs['location_id'] = _LOCATION_UNI_ID;	
			  
		 if (empty($_POST['address']))
			  $core->msgs['address'] = "Please Enter Your Address";
		 
		 if (empty($_POST['lat']) && empty($_POST['lng']))
			{
				$latlng = getCoords($_POST['address']); 
				$data['latitude'] = $latlng['lat'];
	            $data['longitude'] = $latlng['lng'];
			}
			
			else
			{
				$data['latitude'] = $_POST['lat'];
	            $data['longitude'] = $_POST['lng'];
			
			}
			
	     if (empty($core->msgs)) {
		 	
			   if (isset($_POST['data']))
					{
							foreach ($_POST['data'] as $type => $coords)
							{
								foreach ($coords as $hash => $d)
								{
									$data1 = array(
													   'location_id' => sanitize($_POST['location_id']),
													   'type' => sanitize($type),
													   'data' => sanitize($d),
													   'color_code' => sanitize($_POST['color_code'])
												  );
								($this->postid) ? $db->update("res_location_google_cordinates", $data1, "id='" . (int)$this->postid . "'") : $db->insert("res_location_google_cordinates", $data1);
								}
							}
						}			   
				$db->update("res_location_master", $data, "id='" . (int)$_POST['location_id']. "'");			  
			  $message =  _DLA_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_menu_item_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  
	  } 
	  /**
	   * Content::locationExists()
	   * 
	   * @return
	   */
	  public function locationExists($location)
	  {
		  global $db;		  
			
		  $sql = $db->query("SELECT location_id" 
							  . "\n FROM   res_location_google_cordinates " 
							  . "\n WHERE location_id = '" . sanitize($location) . "' " 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	  /**
       * Content::updateDelivaryArea()
       * 
       * @return
       */
	  public function updateDelivaryArea()
	  {
		  global $db, $core, $wojosec;	
		  
		 		 
		 if (empty($_POST['location_id']))
			  $core->msgs['location_id'] = "Please Select Your Location Name";
			  
		 		 
		 if (empty($_POST['lat']) && empty($_POST['lng']))
			{
				$latlng = getCoords($_POST['address']); 
				$data['latitude'] = $latlng['lat'];
	            $data['longitude'] = $latlng['lng'];
			}			
			else
			{
				$data['latitude'] = $_POST['lat'];
	            $data['longitude'] = $_POST['lng'];
			
			}
			
	     if (empty($core->msgs)) {
		 	
				 $delete = $db->delete("res_location_google_cordinates", "location_id='" . $this->postid . "'");	
				
			   if (isset($_POST['data']))
					{
							foreach ($_POST['data'] as $type => $coords)
							{
								foreach ($coords as $hash => $d)
								{
									$data1 = array(
													   'location_id' => sanitize($_POST['location_id']),
													   'type' => sanitize($type),
													   'data' => sanitize($d),
													   'color_code' => sanitize($_POST['color_code'])
												  );
								$db->insert("res_location_google_cordinates", $data1);
								
								}
							}
						}
						
			   
			  $db->update("res_location_master", $data, "id='" . (int)$_POST['location_id']. "'");	
			  		  
			  $message =  _DLA_UPDATED;
			  
			 ($db->affected() || $delete) ? $wojosec->writeLog($message, "", "no", "res_menu_item_master") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  
	  } 
	  
	  /**
       * Content::getDeliveryArea()
       * 
       * @return
       */
	  
	  public function getDeliveryArea($locationid)
	  {
		  global $db, $core, $pager;
		  
		   if(count(array_filter($locationid)) > 0):		 		
					$ids = join(',',$locationid);
					$adsql = "WHERE lg.location_id IN ($ids)";	
		   	
		   else:
		   	$adsql =  "";
		    endif;
			
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_location_google_cordinates");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		  $sql = "SELECT lg.*,ls.location_name,ls.address1"
		  . "\n FROM res_location_google_cordinates as lg"
		  ."\n left join res_location_master as ls on ls.id=lg.location_id "
		  ."\n ".$adsql." GROUP BY lg.location_id". $pager->limit;	  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }	
	  
	  /**
       * Content::editDeliveryArea()
       * 
       * @return
       */
	  
	  public function editDeliveryArea($locationid)
	  {
		  global $db, $core;

		 $sql = "SELECT *"
		  . "\n FROM res_location_master "
		  ."\n WHERE id ='".$locationid."'";	  
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
		  
	  }	
	  
	  public function getcoordmaps($locationid)
	  {
	  	global $db, $core;
		$sql = "SELECT *"
			."\n FROM res_location_google_cordinates"
			."\n WHERE location_id ='".$locationid."'";
		$row = $db->fetch_all($sql);
		return ($row)? $row : 0;
	  }
	  
	  
	  /**
	   * Content::getCustomer()
	   * 
	   * @return
	   */
	  public function getCustomer($where = false, $from = false)
	  {
		  global $db, $core, $pager;
		
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_customer_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }	
		  if(isset($_POST['location_search'])): 			

			  if($_POST['location_id'] != ""):   
				
			     if(isset($where)):

					$sql .= " WHERE  location_id = '" . $_POST['location_id'] . "'";

				   else:

					$where = " WHERE ";

					$sql .= $where .= "  location_id = '" . $_POST['location_id'] . "'";

				   endif;

				endif;
		 endif;	   
		 
		  if (isset($_GET['sort'])) {
			  list($sort, $order) = explode("-", $_GET['sort']);
			  $sort = sanitize($sort);
			  $order = sanitize($order);
			  if (in_array($sort, array("active", "first_name","email_id","create_date"))) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  $sorting = " " . $sort . $ord;
			  } else {
				  $sorting = " create_date DESC";
			  }
		  } else {
			  $sorting = " create_date DESC";
		  }
		  
          $clause = ($where) ? " WHERE CONCAT(first_name,' ',last_name) LIKE '%" . $where . "%'" : "";
		  
          if (isset($_POST['fromdate']) && $_POST['fromdate'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? $_POST['fromdate'] : $from;
              if (isset($_POST['enddate']) && $_POST['enddate'] <> "") {
                  $enddate = $_POST['enddate'];
              }
              $clause .= " WHERE create_date BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          } 
		  
		 $sql = "SELECT *,CONCAT(first_name,' ',last_name) as name"		 
		  . "\n FROM  res_customer_master"
		  . "\n " . $clause. $sql 
		  . "\n ORDER BY " . $sorting . $pager->limit;
          $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
	  }
	  	
	  /**
	   * Content::getCustomerDetails()
	   * 
	   * @return
	   */
	  public function getCustomerDetails($id)
	  {
		  global $db, $core;
		  
		  
		  $sql = "SELECT cm.*,CONCAT(cm.first_name,' ',cm.last_name) as name,c.country_name,s.state_name,ct.city_name"		 
		  . "\n FROM  res_customer_master AS cm"
		  ."\n LEFT JOIN res_country_master AS c On c.id = cm.country_id"
		  ."\n LEFT JOIN res_state_master AS s On s.id = cm.state_id"
		  ."\n LEFT JOIN res_city_master AS ct On ct.id = cm.city_id"
		  ."\n WHERE cm.id ='".$id."' ";
          $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::getLocationDetails()
	   * 
	   * @return
	   */
	  public function getLocationDetails($id)
	  {
		  global $db, $core;		
		
		  $sql = "SELECT cm.*,coun.country_name,stat.state_name,ct.city_name,cn.company_name"		 
		  . "\n FROM  res_location_master as cm"
		  ."\n LEFT JOIN res_country_master as coun ON coun.id= cm.country_id"
		  ."\n LEFT JOIN res_state_master as stat ON stat.id = cm.state_id"
          ."\n LEFT JOIN res_city_master as ct ON ct.id = cm.city_id"
		  ."\n LEFT JOIN res_company_master as cn ON cn.id = cm.company_id"
		  ."\n WHERE cm.id='".$id."'";
          $row = $db->first($sql);
		  		   
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::getCustomerFilter()
	   * 
	   * @return
	   */
		public function getCustomerFilter()
	  {
		  $arr = array(
				 'active-ASC' => "Active".'&uarr;',
				 'active-DESC' => "Active".'&darr;',				 
				 'first_name-ASC' => 'Username &uarr;',
				 'first_name-DESC' => 'Username &darr;',
				 'email_id-ASC' => 'User Email &uarr;',
				 'email_id-DESC' => 'User Email &darr;',
				 'created_date-ASC' => 'Created &uarr;',
				 'created_date-DESC' => 'Created &darr;'				 
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
	  
	   function isValidURL($website_url) {
     if (filter_var($website_url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) return true;
      else return false;
  }
	  /**
	   * Content::processWebsiteInstall()
	   * 
	   * @return
	   */
	  public function processWebsiteInstall()
	  {
		  global $db, $core, $wojosec;
		  
		  
		  
		  if (empty($_POST['website_url']))
			     $core->msgs['website_url'] =  _INS_TITLE_R;
			  
	             
         if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $_POST['website_url']))
			  {
			 // $core->msgs['website_url'] = "URL is valid";
			   }
			else {
		      $core->msgs['website_url']= "URL is invalid";
  			}
			  
    
			
		  if ($this->WebInstallExists($_POST['website_url'],$this->postid))
			  $core->msgs['website_url'] = "This url already Exist";	
			  
		  if (empty($_POST['location_id']))
				  $core->msgs['location_id'] =_INS_LOCA;	
		  if (!empty($_POST['location_id'])){
		  		$locationid = implode(",",$_POST['location_id']);
		  	}		 
		  if (empty($core->msgs)) {
				  $data = array(
				  'website_url' => sanitize($_POST['website_url']), 
				  'location_id' => $locationid,
				  'default_location' => sanitize($_POST['default']),
				  'flow' => intval($_POST['flow']),
				  'latitude'=> sanitize($_POST['latitude']),
				  'longitude'=> sanitize($_POST['longitude']),
				  'zoom_level'=> sanitize($_POST['zoom_level']),	
				  'hybrid' => (empty($_POST['hybrid'])) ?  "0" : intval($_POST['hybrid']),  
				  'test_mode' => (empty($_POST['test_mode'])) ?  "0" : intval($_POST['test_mode']),  
				  'pick_up' => (empty($_POST['pick_up'])) ?  "0" : intval($_POST['pick_up']),  
				  'delivery' => (empty($_POST['delivery'])) ?  "0" : intval($_POST['delivery']),
				  'dineln' => (empty($_POST['dineln'])) ?  "0" : intval($_POST['dineln']),	
				  'hours_notes' => sanitize($_POST['hours_notes']),		
				  'notes' => sanitize($_POST['notes']),	
				  'show_e_club' => intval($_POST['show_e_club']),
				  'show_deliveryaddress' => intval($_POST['show_deliveryaddress']),	 				  
				  'active' => intval($_POST['active'])
			  );
			  
				 /*if($_POST['flow']==2)
				 {
					$data['default_location'] = sanitize($_POST['default']);
				 }
				 else{
						$data['default_location'] = "";
				 }*/
			     //echo "<pre>"; print_r($data); exit();
			  
			  ($this->postid) ? $db->update("res_install_master", $data, "id='" . (int)$this->postid . "'") : $db->insert("res_install_master", $data);
			  $message = ($this->postid) ? _INS_UPDATED : _INS_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
       * Content::getWebSettings()
       *
       * @return
       */
      private function getWebSettings()
      {
          global $db;
          $sql = "SELECT * FROM  res_install_master";
          $row = $db->first($sql);
          
          $this->show_e_club = $row['show_e_club'];
		  $this->show_deliveryaddress = $row['show_deliveryaddress'];
      }
	  
	  /**
	   * Content::getWebInstall()
	   * 
	   * @return
	   */
	  public function getWebInstall()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("res_install_master");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $where = ($this->postid) ? "WHERE location-id = '".$this->postid."'" : NULL ;
		  $sql = "SELECT i.*"
		  . "\n FROM res_install_master AS i"
		  . "\n LEFT JOIN res_location_master AS lm ON lm.id = i.location_id"
		  . "\n $where"
		  . "\n ORDER BY i.id". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::getWebInstallDetails()
	   * 
	   * @return
	   */
	  public function getWebInstallDetails($id)
	  {
		  global $db, $core;
		  
		  
		  $sql = "SELECT i.* ,lm.location_name"		 
		  . "\n FROM  res_install_master AS i"
		  ."\n LEFT JOIN res_location_master AS lm ON lm.id = i.location_id"		  
		  ."\n WHERE i.id ='".$id."' ";
          $row = $db->first($sql);
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Content::getWebInstalllocation()
	   * 
	   * @return
	   */
	  public function getWebInstalllocation($locationid)
	  {
		  global $db, $core;
		  
		  $ids = join(',',array($locationid));
			
		  $sql = "SELECT id,location_name"
		  . "\n FROM res_location_master WHERE id IN (".$ids.")";
		 // . "\n ORDER BY company_name";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }
	  
	   /**
	   * Content::WebInstallExists()
	   * 
	   * @return
	   */
	  public function WebInstallExists($websiteurl,$postid)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& id != $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		 $sql = $db->query("SELECT id, website_url" 
							  . "\n FROM res_install_master" 
							  . "\n WHERE website_url = '" . sanitize($websiteurl) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	  /**
	   * content::getorders()
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function getorders($status,$from = false)
	  {
	  
		   global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

		 
		  $adsql = "";
		  
		  
		  if(!empty($_POST['order_search']))
		  {
		  	if(empty($adsql))
			{$adsql .= " WHERE ";}
		  	$adsql .= " rom.order_number ='" . trim($_POST['order_search']) . "'";
		  }
		  
		if(isset($status) && !empty($status)){
			  $adsql = "WHERE rom.order_status ='".$status."' ";
		  }
		  
		  
		  if (isset($_POST['fromdate']) && $_POST['fromdate'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? $_POST['fromdate'] : $from;
              if (isset($_POST['enddate']) && $_POST['enddate'] <> "") {
                  $enddate = $_POST['enddate'];
              }
              $adsql = " WHERE rom.pickup_date BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          }		  
	  
			$sql =" SELECT rom.*,rod.*,rcm.first_name, rcm.last_name,rlm.location_name "
				. "\n  FROM res_order_master AS rom "		
				. "\n  INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id "		
				. "\n  INNER JOIN res_customer_master AS rcm ON rom.customer_id = rcm.id "
				. "\n  INNER JOIN res_location_master AS rlm ON rom.location_id = rlm.id " .$adsql 
				. "\n  GROUP BY rom.orderid ORDER BY rom.orderid DESC ";
			
		  $sql1 = $db->query($sql);
	
		  $counter = $db->numrows($sql1);         

		  $pager->items_total = $counter;

		  $pager->default_ipp = $core->perpage;

		  $pager->paginate();
		  

		  if ($counter == 0) {

			  $pager->limit = null;

		  }
		  
		  $sql .= $pager->limit;	
		  
		  $query_variable = $sql;
		  
		  $this->is_homemod =  $query_variable;
		
				
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;   
		 
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
	  
	   
	  
	 
	   /**
	   * content::getproductOrder()
	   * 
	   * @param bool $from
	   * @return
	   */
	  
	  public function getproductOrder($orderid)
	  {
		  global $db, $core;	
		  
		    $sql = "SELECT rom.*,rcm.first_name, rcm.last_name,rcm.email_id,rcm.apt,rcm.address1 as caddress1,rcm.city as customer_city,rcm.state as customer_state,cus_country.country_name as customer_country,rcm.phone_number,rlm.location_name,city.city_name,state.state_name,country.country_name " 
		  		 
			  . "\n FROM res_order_master AS rom "
			  //. "\n INNER JOIN res_order_details AS rod ON rom.orderid = rod.order_id" 
			  . "\n INNER JOIN res_customer_master AS rcm ON rom.customer_id = rcm.id"
			  //. "\n LEFT JOIN res_menu_item_master AS mim ON rod.menu_item_id = mim.id" 
			  .	"\n LEFT JOIN res_city_master AS city ON rom.d_city_id = city.id "
			  //.	"\n LEFT JOIN res_city_master AS cus_city ON rcm.city_id = cus_city.id "
			  .	"\n LEFT JOIN res_state_master AS state ON rom.d_state_id = state.id "
			  //.	"\n LEFT JOIN res_state_master AS cus_state ON rcm.state_id = cus_state.id "
			  .	"\n LEFT JOIN res_country_master AS cus_country ON rcm.country_id = cus_country.id "
			  .	"\n LEFT JOIN res_country_master AS country ON rom.d_country_id = country.id "
			  . "\n INNER JOIN res_location_master AS rlm ON rom.location_id = rlm.id" 
			  . "\n WHERE rom.orderid='".$orderid."'";			  
			
				
           $row = $db->first($sql);  
          
		  return ($row) ? $row : 0;
	  } 
	  
	   /**
	   * content::getproductOrderFront()
	   * 
	   * @param bool $from
	   * @return
	   */
	  
	  public function getproductOrderFront($orderid)
	  {
		  global $db, $core;	
		  
		  $sql = "SELECT rom.order_number,rom.order_comments,od.price,od.qty , mim.item_name, rmot.topping_name "
						." \n FROM `res_order_details` AS od "
						." \n LEFT JOIN res_order_master AS rom ON od.order_id = rom.orderid "
						." \n LEFT JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id "
						." \n LEFT JOIN res_order_menutopping_details AS romd ON od.order_detail_id = romd.order_detail_id "
						." \n LEFT JOIN res_menu_option_topping_master AS rmot ON romd.option_topping_id = rmot.option_topping_id "
						." \n WHERE od.order_id = '".$orderid."'";
				
           $row = $db->fetch_all($sql);  
          
		  return ($row) ? $row : 0;
	  } 
	  
	   
	  
	  /**
	   * content::getproductOrderDetailsFront()  
	   * This function get data of prodct details with customer profile
	   * @param bool $from
	   * @return
	   */
	  
	  public function getproductOrderDetailsFront($orderid)
	  {
		  global $db, $core;	
		  
			$sql = "SELECT rcm.first_name,rcm.email_id,rcm.phone_number,rlm.location_name,rom.orderid,rom.order_number, rom.`pickup_time` AS order_time, rom.`pickup_date` AS order_date,rom.`order_type` ,rom.`payment_type`, rom.`order_status` , rom.`net_amount`"
			  .	"\n FROM `res_order_master` AS rom "
			  .	"\n INNER JOIN res_customer_master AS rcm ON rom.`customer_id` = rcm.id "
			  .	"\n INNER JOIN res_location_master AS rlm ON rom.location_id = rlm.id WHERE rom.orderid ='".$orderid."'";
				
           $row = $db->first($sql);  
          
		  return ($row) ? $row : 0;
	  } 
	  
	  /**
	  * Product::ThanksProducts_previous_12_dec_2013() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ThanksProducts_previous_12_dec_2013($ordermaster)
	  {
		  global $db, $core;	
		  
          $sql = "SELECT  od.*, mim.item_name"
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n WHERE od.order_id ='".$ordermaster."'";
				
           $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  } 
	  
	   /**
	  * Product::ThanksProducts() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ThanksProducts($orderId)
	  {
		  global $db, $core;	
		  
          $sql = "SELECT  od.*, mim.item_name,mim.item_description,
		  			(SELECT SUM( `price` * `qty` )
					 FROM `res_order_menutopping_details`
					 WHERE od.order_detail_id = order_detail_id
					 ) AS total_price "
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n WHERE od.order_id ='".$orderId."'";
				 
           $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }   
	  
	  /**
	  * Product::ThanksProductsFront() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ThanksProductsFront($ordermaster)
	  {
		  global $db, $core;	
		  
          $sql = "SELECT  od.*, mim.item_name,rmot.topping_name"
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n LEFT JOIN res_order_menutopping_details AS romd ON od.order_detail_id = romd.order_detail_id"
				."\n INNER JOIN res_menu_option_topping_master AS rmot ON romd.option_topping_id = rmot.option_topping_id"
				."\n WHERE od.order_id ='".$ordermaster."'";
				
           $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }  
	  
	   /**
	  * Product::ProductsToppingNameFront() 
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function ProductsToppingNameFront($orderid)
	  {
		  global $db, $core;	
		  
          $sql = "SELECT mim.item_name, rmot.topping_name,od.price,rmot.option_topping_id"
				."\n FROM `res_order_details` AS od"
				."\n INNER JOIN res_menu_item_master AS mim ON od.menu_item_id = mim.id"
				."\n LEFT JOIN res_order_menutopping_details AS romd ON od.order_detail_id = romd.order_detail_id"
				."\n INNER JOIN res_menu_option_topping_master AS rmot ON romd.option_topping_id = rmot.option_topping_id"
				."\n WHERE od.order_id ='".$orderid."'";
				
           $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }  
	  
	/* 
	* Content:LocationNameByLocationId()
	*/
	public function LocationNameByLocationId($locationid)
	{
		global $db, $core;
		$sql = "SELECT phone_number,location_name FROM `res_location_master` WHERE `id` = '".$locationid."'";
		$row = $db->first($sql);
	   if($row)
	   {
	   	return $row['location_name'];
	   }
	}	  
	  
	/* 
	* Content:LocationNameByLocationId()
	*/
	public function GetOrderDetailsForReorder($order_id,$order_number)
	{
		global $db, $core;
		$sql = "SELECT rom.orderid, rom.order_number, rb.productID, rb.user_id, rb.qty, rb.menu_size_map_id, rb.productPrice, rb.additional_notes, rbt.option_topping_id, rbt.option_choice_id, rbt.basketID, rbt.basketSession
		FROM `res_order_master` AS rom
		LEFT JOIN res_order_details AS rod ON rom.orderid = rod.order_id
		LEFT JOIN res_baskets AS rb ON rod.menu_item_id = rb.productID
		LEFT JOIN res_basket_topping AS rbt ON rb.basketID = rbt.basketID WHERE rom.orderid ='".$order_id."' and rom.order_number ='".$order_number."' GROUP BY rom.order_number ORDER BY rom.orderid ";	
		
		$row = $db->first($sql);
		return ($row) ? $row : 0;
	}
	/**
       * Content::QPageExists()
     
       */
	  
	   public function QPageExists($page_title,$postid=NULL)
	  {
		  global $db;
		   if(!empty($postid)):
		   	$adsql = "&& page_id!= $postid";
		   else:
		   	$adsql = "";
		    endif;
			
		  $sql = $db->query("SELECT page_id 	, page_title" 
							  . "\n FROM res_page_manager " 
							  . "\n WHERE page_title = '" . sanitize($page_title) . "' ".$adsql 
							  . "\n LIMIT 1");
							
		  if ($db->numrows($sql) == 1) {
			 return true;
		  } else
			 return false;
	  }
	  
	 /**
       * Content::processPageMaster()
       * 
       * @return
       */
	  public function processPageMaster()
	  {
		  global $db, $core, $wojosec;
		 		  
		  if (empty($_POST['page_title']))
			  $core->msgs['page_title'] = "Please Type Page Title";
			
		
		  if ($this->QPageExists($_POST['page_title'],$this->postid))
			  $core->msgs['page_title'] = "Page Title Already Exits";	 
			  
			  
		
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'page_title' => sanitize($_POST['page_title']),
				  'description' => sanitize($_POST['description']),
				  'active' => intval($_POST['active']),
				  'update_date'=>"NOW()"
			  );
			
			  
			  ($this->postid) ? $db->update("res_page_manager", $data, "page_id='" . (int)$this->postid . "'") : $db->insert("res_page_manager", $data);
			  $message = ($this->postid) ? _PAGEM_UPDATED : _PAGEM_ADDED;
			 ($db->affected()) ? $wojosec->writeLog($message, "", "no", "res_page_manager") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 
	  
	  /**
	   * Content::getpagemaster()
	   * 
	   * @return
	   */
	  public function getpagemaster()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries("res_page_manager");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		 $sql = "SELECT *"
		  . "\n FROM res_page_manager" ;
		  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  } 	  
	  
	   /**
	   * Content::getpagemaster()
	   * 
	   * @return
	   */
	  public function getTermconditionPage($pageTitle)
	  {
		  global $db, $core;

		 $sql = "SELECT * FROM res_page_manager WHERE page_title LIKE '%".$pageTitle."%' " ;
		  
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
		  
	  } 
	   /**
	   * Content::getpagemaster()
	   * 
	   * @return
	   */
	  public function getPrivacypolicyPage($pageTitle)
	  {
		  global $db, $core;

		 $sql = "SELECT * FROM res_page_manager WHERE page_title LIKE '%".$pageTitle."%' " ;
		  
		  $row = $db->first($sql);
		  return ($row) ? $row : 0;
		  
	  }
	  
	 /**
	   * Product::thanksToppingList()
	   *
	   * @return
	   */
		public function thanksToppingList($order_detail_id){
			global $db, $core;
									
			error_reporting(0);
			 			  
			$sql  = "SELECT bt.*,rm.choice_name "
			   . "\n FROM  res_order_menutopping_details AS bt"
			   . "\n LEFT JOIN res_menu_option_choice_master AS rm ON bt.option_choice_id =rm.option_choice_id "
			 
			   . "\n WHERE bt.order_detail_id = '".$order_detail_id."'"; 
			   
			  $row = $db->fetch_all($sql);
			  return ($row) ? $row : 0;			  
		
	 	 }	
		 
	  /**
       * Menu::getMenuOptionDropDown()
       * 
       * @return manu
       */
		public function getMenuOptionDropDown($menuitemid)
		{
			global $db, $core;
			
			 $sql = "SELECT rmom.option_id, rmom.option_name "
			
					. "\n FROM res_menu_option_master AS rmom "				
					
					. "\n WHERE rmom.option_id NOT IN ('".$menuitemid."')";
			
					//. "\n WHERE option_id NOT IN (SELECT item_option_id FROM `res_menu_option_item_mapping` WHERE menu_item_id ='".$menuitemid."')"; 
			
			
						
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}	 
		
	   /**
       * Menu::getMenuOptionGroupList()
       * 
       * @return manu
       */
		public function getMenuOptionGroupList($menuitemid)
		{
			global $db, $core;
			
			$sql = "SELECT * FROM `res_menu_option_group`WHERE `item_option_id` ='".$menuitemid."'"; 		
						
			 $row = $db->fetch_all($sql);
			 return ($row) ? $row : 0;
		}		    	    	     	    	  
	  
	  /**
	   * Content::getAllrestorantTiming()
	   * 
	   * @return
	   */
	  public function getAllrestorantTiming()
	  {
		  global $db, $core;		

		 $sql = "SELECT `id` , `address1` , `restaurant_name` , `location_name` , `restorant_time`"
				. "\n FROM `res_location_master` "
				. "\n WHERE `id` IN (SELECT id FROM `res_location_master`)" ;
		  
		  $row = $db->fetch_all($sql);
		  return ($row) ? $row : 0;
		  
	  }
	
	 /**
	   * Content::getCategoryDropList()
	   * 
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
	   * @return
	   */
	  public function getCategoryDropList($parent_id, $level = 0, $spacer, $selected = false)
	  {
		  global $core;
		  foreach ($this->categorytree as $key => $row) {
			  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
			  if ($parent_id == $row['parent_id']) {
				  print "<option value=\"" . $row['id'] . "\"".$sel.">";
				  
				  for ($i = 0; $i < $level; $i++)
					  print $spacer;
				  
				  print $row['category_name'] . "</option>\n";
				  $level++;
				  $this->getCategoryDropList($key, $level, $spacer, $selected);
				  $level--;
			  }
		  }
		  unset($row);
	  }
	  
	  
	  /**
       * Content::getcategoryTree()
       * 
       * @return
       */
      protected function getcategoryTree()
	  {
		  global $db, $core;
		  $query = $db->query('SELECT * FROM res_category_master ORDER BY parent_id');
		  
		  while ($row = $db->fetch($query)) {
			  $this->categorytree[$row['id']] = array(
			        'id' => $row['id'],
					'category_name' => $row['category_name'], 
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->categorytree;
	  }
	  	  	  
	  
}
	  
	  
?>