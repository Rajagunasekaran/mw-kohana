<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Website extends Controller_Template {
	//The webpage title
	private $site_title = 'My Workouts';
	//The default template
	public $template = 'templates/front';
	//This variable will hold the data passed to the views
	public $data = array();

    public function before()
    {
        parent::before();
        $this->session = Session::instance();
		$this->session->set('user_from', 'front');
		if($this->session->get('current_site_id') !=''){
			$socialcontent = $this->_getSocialUnique($this->session->get('current_site_id'));
			if( !empty($socialcontent[0])) {
				$this->template->social_title = (!empty($socialcontent[0]['title']) ? trim(strip_tags($socialcontent[0]['title'])) : '');
				$this->template->social_image = (!empty($socialcontent[0]['site_image']) ? URL::site(NULL, 'http').'assets/uploads/logo/'.$socialcontent[0]['site_image'] : URL::site(NULL, 'http').'assets/img/icon-mw192x192.png');
				$this->template->social_video = ($socialcontent[0]['video_status'] == '1' ? $socialcontent[0]['video'] : '');
				$this->template->social_desc  = trim(strip_tags($socialcontent[0]['description']));
			}
		}
		$slug_id = Request::current()->param('site_name');
		if ($slug_id != '' && !$this->session->get('current_site_id')) {
			 $sites = $this->_get_sites($slug_id);
			 if (!$sites) {
				Auth::instance()->logout();
				$this->redirect();
			 }
			 $site_id = $sites["id"];
			 // setting dynamic site id for users dated on 19th april 2016
			 $this->session->set('current_site_id', $site_id);
			 $this->session->set('current_site_name', $sites['name']);
			 $this->session->set('current_site_slug', $sites['slug']);
			 $this->session->set('current_site_agelimit', $sites['min_agelimit']);
		}
		/*if (Request::current()->param('lang') != null)
			$this->session->set('lang', Request::current()->param('lang'));
		else
			$this->session->set('lang', 'en');
		I18n::lang('front-'.$this->session->get('lang').'-'.Request::current()->controller());//by mdh*/
		//echo 'front-'.$this->session->get('lang').'-'.Request::current()->controller();die();
		if($this->auto_render) {
		  $this->template->title            = '';
		  $this->template->meta_keywords    = 'My Workouts';
		  $this->template->meta_description = 'My Workouts';
		  //$this->template->meta_copywrite   = '';
		  $this->template->content          = '';
		}		
		if (!Auth::instance()->logged_in() && Request::current()->controller() != "index" && Request::current()->action() != "index" && Request::current()->action() !='autoredirect') {
			if(!$this->session->get('current_site_id')){
				$this->redirect(URL::site(NULL, 'http')."index");
			}else{
				$slug_name = ($this->session->get('current_site_slug') ? 'site/'.$this->session->get('current_site_slug') : 'index');
				$this->redirect(URL::site(NULL, 'http').$slug_name);
			}
		}else{
			if(Auth::instance()->logged_in() && $this->request->controller() == "Index" && $this->request->action() != 'logout' && $this->request->action() != 'deactivate' && $this->request->action() != 'general' && $this->request->action() !='autoredirect'){
				if(!$this->session->get('current_site_id'))
					$this->redirect("dashboard/index");
				else{
					$slug_name = ($this->session->get('current_site_slug') ? $this->session->get('current_site_slug').'/' : '');
					$this->redirect(URL::site(NULL, 'http').$slug_name.'dashboard/index');
				}
			}
			$this->globaluser = Auth::instance()->get_user();
			View::set_global('site_id' , ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0'));
			View::set_global('user' , $this->globaluser);
		}
		if(Auth::instance()->logged_in()){ 
			$settings_model = ORM::factory('settings');
			$this->user_timezone = '';
			$this->user_timeformat = '';
			$this->user_dateformat = '';
			$this->user_weight = '';
			$this->user_distance = '';
			$this->user_language = '';
			$pageName = strtolower($this->request->controller()).'_'.strtolower($this->request->action());
			$this->session->set('user_allow_tour','allow');
			$this->session->set('user_allow_edit_notify','allow');
			$cur_file = 'Controller_'.$this->request->controller();
			if (class_exists($cur_file) && method_exists($cur_file,'action_'.$this->request->action())) {
				$page_id  = Helper_Common::getPageIdbyName($pageName);
				$user_settings = $settings_model->getsettings();
				$hideArray = Helper_Common::getAllowAllAccessByUser($page_id);
				$this->session->set('user_allow_page',$page_id);
				if(is_array($hideArray) && count($hideArray) > 0){
					$this->session->set('user_allow_tour',($hideArray['is_tour_hidden'] ? 'disallow' : 'allow'));
					$this->session->set('user_allow_edit_notify',(isset($hideArray['is_edit_notify_hidden']) ? 'disallow' : 'allow'));
				}
			}
			$networkModel 	= ORM::factory('networks');
			$chatnotify = $networkModel->get_network_users_unread_count($this->globaluser->pk(),'');
			$this->session->set('chatnotify',($chatnotify>0 ? $chatnotify : ''));
			if(Helper_Common::getAddtoHomeStatus()){
				$this->session->set('user_allow_addtohome','allow');
			}else
				$this->session->set('user_allow_addtohome','disallow');
			if(!empty($user_settings) && count($user_settings)>0){
				$this->user_timezone	= $user_settings[0]['timezone'];
				$this->user_timeformat	= $user_settings[0]['time_format'];
				$this->user_dateformat	= $user_settings[0]['date_format'];
				$this->user_weight		= $user_settings[0]['Weight'];
				$this->user_distance	= $user_settings[0]['Distance'];
				$this->user_language	= $user_settings[0]['language'];
				$this->user_week_starts	= $user_settings[0]['week_sarts_on'];
				$this->user_xr_variable	= $user_settings[0]['XRset_extra_variable_flag'];
				View::set_global('user_timezone'	, $this->user_timezone);
				View::set_global('user_timeformat'	, $this->user_timeformat);
				View::set_global('user_dateformat'	, $this->user_dateformat);
				View::set_global('user_weight'		, $this->user_weight);
				View::set_global('user_distance'	, $this->user_distance);
				View::set_global('user_language'	, $this->user_language);
				View::set_global('user_week_starts'	, $this->user_week_starts);
				View::set_global('user_xr_variable'	, $this->user_xr_variable);
				$this->session->set('user_timezone', $this->user_timezone);
				$this->session->set('user_timeformat', $this->user_timeformat);
				$this->session->set('user_dateformat', $this->user_dateformat);
				$this->session->set('user_weight', $this->user_weight);
				$this->session->set('user_distance', $this->user_distance);
				$this->session->set('user_language', $this->user_language);
				$this->session->set('user_week_starts', $this->user_week_starts);
				$this->session->set('user_xr_variable', $this->user_xr_variable);
				$language_idsite = ($this->session->get('user_language') ? $this->session->get('user_language') : '1');
				$loginuser_lang = Helper_Common::getlanguage($language_idsite);
				$this->session->set('user_lang_id',$loginuser_lang);
				if ($loginuser_lang != null)
					$this->session->set('lang', $loginuser_lang);
				else
					$this->session->set('lang', 'en');
				I18n::lang('front-'.$this->session->get('current_site_id').'-'.$this->session->get('lang').'-'.strtolower(Request::current()->controller()));
				if(!empty($this->user_timezone)){
					$timezone_array = explode(" ", $this->user_timezone);
					date_default_timezone_set($timezone_array[0]);
				}
				/*** Get user access based on role ***/
				$role_access_model = ORM::factory('admin_roleaccess');
				$role_id           = Model::instance('Model/admin/exercise')->getUserRole();
				$roleAccessArray   = array();
				if($role_id>0){
					$condtn        = 'role_id=' . $role_id . ' and site_id=' . $this->session->get('current_site_id');
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
				$activity_feed["feed_type"]		= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 12;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				/******************* Activity Feed Browser*********************/
				$browserObj = new Helper_Browser();
				$browserName= $browserObj->getBrowser();
				$deviceName = $browserObj->getPlatform();
				$deviceInfo = $browserObj->__toString();
				$activity_feed = array();
				$activity_feed["feed_type"]		= 31; // This get from feed_type table
				$activity_feed["action_type"]  	= 12;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>$this->session->get('user_from')));
				$activity_feed["extra_info"]  	= $deviceInfo;
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed Browser*********************/
			}
		}
    }
	public function _get_current_user()
	{
		$user = Auth::instance()->get_user();
		return $user;
	}
	public function after() {
		/*
		 * Set the page title to $site_title if title is not set,
		 * otherwise create title 'path'
		 */
		$this->template->site_title = $this->site_title;
		if ($this->template->title){
			$this->template->title = $this->site_title.' - '.$this->template->title;
		} else {
			$this->template->title = $this->site_title;
		}
		parent::after();
	}
	public function render($template = FALSE, $view = FALSE){
		/*
		* Force specific template
		*/
		if ($template){
			$this->template = $template;
		}
		/*
		* Use default view logic if $view is not forced
		*/
		if (!$view)
			$view = 'pages/'.$this->request->controller().'/'.$this->request->action();
		$this->template->content = View::factory($view);
		$this->template->content->error_messages = (isset($this->data['error_messages']) ? $this->data['error_messages'] : array());
		$this->template->content->topHeader = View::factory('templates/front/topheader');
		// if($this->request->action()=='exerciselibrary'){
			// $this->template->content->imgeditor = View::factory('templates/front/imgeditor');
		// $this->template->content->imglibrary = View::factory('templates/front/template-imglibrary');
		//$this->template->content->usermodal = View::factory('templates/front/usermodal');
		//require_once(APPPATH.'views/templates/front/usermodal.php');
		// }
		if($this->request->action()=='exerciselibrary' || $this->request->action()=='workoutrecord'){
			$this->template->content->XRciselib = View::factory('templates/front/exerciselibrary');
		}
		// if($this->request->action()=='exerciseimages'){
		// 	$this->template->content->imgeditor2 = View::factory('templates/front/imglib-imgeditor');
		// }
		$this->template->content->topHeader->site_title  = $this->template->content->site_title    = $this->site_title;
	}
	public function _get_sites($slug_id)
	{
		  $hompage = ORM::factory('homepage');
		  $result  = $hompage->get_sites($slug_id);
		  return $result;
	}
	public function _getSocialUnique($site_id){
		$hompage = ORM::factory('homepage');
		$result=$hompage->site_cms_social_content($site_id);
		return $result;
	}
} // End Welcome
