<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Website extends Controller_Template {
	//The webpage title
	private $site_title = 'My Workout';
	//The default template
	public $template = 'templates/admin';
	//This variable will hold the data passed to the views
	public $data = array();

	public function before()
	{
		parent::before();
		//$constants = Kohana::$config->load('constants');
		$this->session = Session::instance();
		$this->session->set('user_from', 'admin');
		$this->session->set('lang','en');
		if (Request::current()->param('lang') != null)
			$this->session->set('lang', Request::current()->param('lang'));
		I18n::lang('admin-'.$this->session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller()));
		if($this->auto_render) {
		  $this->template->title            = '';
		  $this->template->meta_keywords    = 'My Workouts';
		  $this->template->meta_description = 'My Workouts';
		  //$this->template->meta_copywrite   = '';
		  $this->template->content          = '';
		}
		if(!Auth::instance()->logged_in() && Request::current()->controller() != "Index"){
			$this->redirect("admin/index");
		}
		if(!Auth::instance()->logged_in() && Request::current()->directory() != "Admin" && Request::current()->controller() != "Index" && $this->request->action() != 'recover' && $this->request->action() != 'logout') {
			$this->redirect("admin/index");
		}else if(!Auth::instance()->logged_in() && Request::current()->directory() == "Admin" && Request::current()->controller() == "Index" && $this->request->action() == 'logout') {
			$this->redirect("admin/index");
		}else{
			if(Auth::instance()->logged_in() && Request::current()->directory() == "Admin" && Request::current()->controller() == "Index" && $this->request->action() != 'switchsite' && $this->request->action() != 'logout'){
				$this->redirect("/admin/dashboard");
			}
			if(Auth::instance()->logged_in()){
				$this->globaluser = Auth::instance()->get_user();
				View::set_global('user', $this->globaluser);
				/*****Don't Change Without My Knowledge By Prabakaran ****/
				$this->common_slug = array("faq-help","privacy","terms");
				View::set_global('common_slug', $this->common_slug);
				$this->contact_slug = "contact";
				$this->contact_title = "Contact Us";
				View::set_global('contact_title', $this->contact_title);
				$mailboxmodel = ORM::factory('admin_mailbox');
				$userid = Auth::instance()->get_user()->pk();
				//echo $this->currenturl;die;
				if(Helper_Common::currentAdminUrl()=="admin/mailbox/preview"){
					$mailid = $this->request->param('id');
					if($mailid){
						DB::update('sitecontact_mapping')->set(array('read_status' =>1,'notification_read_status' =>1))->where('contact_id', '=', $mailid)->where('userid', '=', $userid)->execute();
					}
				}
				$notify_list = $mailboxmodel->get_unread_message($userid);
				$mail_list   = $mailboxmodel->get_unread_mail_message($userid);
				View::set_global('notify_cnt', (isset($notify_list))?count($notify_list):0 );
				View::set_global('mail_cnt', (isset($mail_list))?count($mail_list):0 );
				
				$userModel = ORM::factory('admin_user');
				$errorfeeds  = $userModel->get_errorfeeds_readstatus('');
				View::set_global('e_cnt', (isset($errorfeeds))?count($errorfeeds):0 );
				$perrorfeeds  = $userModel->get_errorfeeds_readstatus(1);
				View::set_global('php_cnt', (isset($perrorfeeds))?count($perrorfeeds):0 );
				$merrorfeeds  = $userModel->get_errorfeeds_readstatus(2);
				View::set_global('mysql_cnt', (isset($merrorfeeds))?count($merrorfeeds):0 );
				
				
				/*****Don't Change Without My Knowledge By Prabakaran ****/
				/*******Defined Common Slugs******************/ // By Prabakaran
				//Check and Assign default user site
				$this->user_sites = $this->session->get('user_sites');
				$this->session->set('user_allow_tour','disallow');
				$this->session->set('user_allow_edit_notify','disallow');
				$cur_file = 'Controller_Admin_'.$this->request->controller();
				$pageId = (!empty($_GET['d']) ? $_GET['d'] : '');
				$pageName = strtolower('Admin_'.$this->request->controller()).'_'.strtolower($this->request->action()).(is_numeric($pageId) ? ($pageId == '1' ? '_default' : ($pageId == '2' ? '_sample' : ($pageId == '3' ? '_shared' : ''))) : '');
				if (class_exists($cur_file) && method_exists($cur_file,'action_'.$this->request->action())) {
					$page_id  = Helper_Common::getPageIdbyName($pageName);
					$hideArray = Helper_Common::getAllowAllAccessByUser($page_id);
					$this->session->set('user_allow_page',$page_id);
					if(is_array($hideArray) && count($hideArray) > 0){
						$this->session->set('user_allow_tour',($hideArray['is_tour_hidden'] ? 'disallow' : 'allow'));
						$this->session->set('user_allow_edit_notify',($hideArray['is_edit_notify_hidden'] ? 'disallow' : 'allow'));
					}
				}
				if(!$this->user_sites && Auth::instance()->get_user() && $this->session->get('current_site_id') =='' ) {
					$usermodel  = ORM::factory('admin_user');
					$user_sites = $usermodel->get_user_sites(Auth::instance()->get_user());
					$this->session->set('current_site_id', $user_sites[0]['site_id']);
					$this->session->set('current_site_name', $user_sites[0]['name']);
					$this->session->set('current_site_slug', $user_sites[0]['slug']);
					$this->session->set('current_site_agelimit', $user_sites[0]['min_agelimit']);
					$this->user_sites = $user_sites;
				}
				$this->current_site_id   = $this->session->get('current_site_id');
				$this->current_site_name = $this->session->get('current_site_name');
				$this->current_site_slug = $this->session->get('current_site_slug');
				View::set_global('current_site_name', $this->current_site_name);
				View::set_global('current_site_id', $this->current_site_id);
				View::set_global('current_site_slug', $this->current_site_slug);
				View::set_global('user_sites', $this->user_sites);				
				if (Auth::instance()->get_user()) {
					$settings_model     = ORM::factory('admin_settings');
					$this->site_timezone = '';
					$this->site_timeformat = '';
					$this->site_dateformat = '';
					$this->site_weight = '';
					$this->site_distance = '';
					$this->site_language = '';
					$site_settings = $settings_model->getsettings();
					if(!empty($site_settings) && count($site_settings)>0){
						$this->site_timezone	= $site_settings[0]['timezone'];
						$this->site_timeformat	= $site_settings[0]['time_format'];
						$this->site_dateformat	= $site_settings[0]['date_format'];
						$this->site_datetimeformat	= $this->site_dateformat.' '.$this->site_timeformat;
						$this->site_weight		= $site_settings[0]['Weight'];
						$this->site_distance	= $site_settings[0]['Distance'];
						$this->site_language	= $site_settings[0]['language'];
						
						/****   Week Setting for Dashboard  *********************************************************** * ****/  
						$week = array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
						
						$this->week	= $week;
						View::set_global('week'	, $this->week);
						$this->session->set('week', $this->week);
						
						$this->site_week_starts_on	= (isset($site_settings[0]['week_sarts_on']))?$site_settings[0]['week_sarts_on']:1;
						View::set_global('site_week_starts_on'	, $this->site_week_starts_on);
						$this->session->set('site_week_starts_on', $this->site_week_starts_on);
						/****   Week Setting for Dashboard  *********************************************************** * ****/
						
						View::set_global('site_timezone'	, $this->site_timezone);
						View::set_global('site_timeformat'	, $this->site_timeformat);
						View::set_global('site_dateformat'	, $this->site_dateformat);
						View::set_global('site_datetimeformat'	, $this->site_datetimeformat);
						View::set_global('site_weight'		, $this->site_weight);
						View::set_global('site_distance'	, $this->site_distance);
						View::set_global('site_language'	, $this->site_language);
						$this->session->set('site_timezone', $this->site_timezone);
						$this->session->set('site_timeformat', $this->site_timeformat);
						$this->session->set('site_dateformat', $this->site_dateformat);
						$this->session->set('site_weight', $this->site_weight);
						$this->session->set('site_distance', $this->site_distance);
						$this->session->set('site_datetimeformat', $this->site_datetimeformat);
						$this->session->set('site_language', $this->site_language);
						$this->site_language= $settings_model->get_current_langue($this->current_site_id);
						$language_idadmin 	= Helper_Common::getidlanguage($this->current_site_id);
						Session::instance()->set('adminuser_language',$language_idadmin);
						$language_idsite 	= (Session::instance()->get('adminuser_language') ? Session::instance()->get('adminuser_language') : '1');
						$loginuser_lang 	= Helper_Common::getlanguage($language_idsite);
						$this->session->set('user_lang_id',$loginuser_lang);
						if($loginuser_lang != null)
							$this->session->set('lang', $loginuser_lang);
						else
							$this->session->set('lang', 'en');
						I18n::lang('admin-'.$this->session->get('current_site_id').'-'.$this->session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller()));
						View::set_global('site_language' , $this->site_language);
						/*** Get user access based on role ***/
						$role_access_model = ORM::factory('admin_roleaccess');
						$role_id           = Model::instance('Model/admin/exercise')->getUserRole();
						$roleAccessArray   = array();
						if($role_id>0){
							$condtn        = 'role_id=' . $role_id . ' and site_id=' . $this->current_site_id;
							$getRoleAccess = $role_access_model->getRoleAccessByContn('access_type_id', $condtn);
							if(isset($getRoleAccess) && count($getRoleAccess) > 0) {
								foreach($getRoleAccess as $key => $value)
									$roleAccessArray[] = $value['access_type_id'];
							}
						}
						$this->session->set('roleAccessArray', $roleAccessArray);
					}
					$userId = $this->session->get_once('newly_loggedin');
					if(!empty($userId)){
						/******************* Activity Feed *********************/
						$activity_feed = array();
						$activity_feed["feed_type"]   	= 10; // This get from feed_type table
						$activity_feed["action_type"]  	= 12;  // This get from action_type table  
						$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
						$activity_feed["site_id"]  		= $this->session->get('current_site_id');
						Helper_Common::createActivityFeed($activity_feed);
						/******************* Activity Feed *********************/
						/**************** Activity Feed browser*******************/
						$browserObj = new Helper_Browser();
						$browserName= $browserObj->getBrowser();
						$deviceName = $browserObj->getPlatform();
						$deviceInfo = $browserObj->__toString();
						$activity_feed = array();
						$activity_feed["feed_type"]   	= 31; // This get from feed_type table
						$activity_feed["action_type"]  	= 12;  // This get from action_type table  
						$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
						$activity_feed["site_id"]  		= $this->session->get('current_site_id');
						$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>$this->session->get('user_from')));
						$activity_feed["extra_info"]  	= $deviceInfo;
						Helper_Common::createActivityFeed($activity_feed);
						/***************** Activity Feed browser*******************/
					}
				}
			}
		}
	}
	public function _get_current_user()
	{
		$user = Auth::instance()->get_user();
		return $user;
	}
	public function after()
	{
		/*
		* Set the page title to $site_title if title is not set,
		* otherwise create title 'path'
		*/
		if($this->template->title){
			$this->template->title = $this->template->title.' » '.$this->site_title;
		} else {
			$this->template->title = $this->site_title;
		}
		parent::after();
	}
	public function render($template = FALSE, $view = FALSE)
	{
		/*
		* Force specific template
		*/
		if($template){
			$this->template = $template;
		}
		/*
		* Use default view logic if $view is not forced
		*/
		if (!$view)
			$view = 'pages/'.$this->request->directory().'/'.$this->request->controller().'/'.$this->request->action();
			$this->template->content             = View::factory($view);
		$this->template->content->error_messages = (isset($this->data['error_messages']) ? $this->data['error_messages'] : array());
		$this->template->content->topnav         = View::factory('templates/admin/topnav');
		$this->template->content->leftnav        = View::factory('templates/admin/leftnav');
		$current_url                             = Helper_Common::currentAdminUrl();
		$this->template->content->imglibrary     = View::factory('templates/admin/template-imglibrary');
		if($this->request->action()=='exerciselibrary' || $this->request->action()=='workoutrecord' || $current_url=='admin/exercise/create' || $current_url=='admin/workout/browse' || $current_url=='admin/workout/edit' || $current_url=='admin/workout/sampleedit'){
			$this->template->content->XRciselib = View::factory('templates/admin/exerciselibrary');
		}
		$this->template->content->imgeditor2 = View::factory('templates/front/imglib-imgeditor');
		$this->template->content->XRciseCreate = View::factory('templates/front/exercisecreate');
	}
} // End Welcome
