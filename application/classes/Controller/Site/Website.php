<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site_Website extends Controller_Template {
	
	
	//The webpage title
	private $site_title = 'My Workout';
	//The default template
	public $template = 'templates/site';
	//This variable will hold the data passed to the views
	public $data = array();

    public function before()
    {
        parent::before();
		$slug_id 	 = $this->request->param('site_id');
		$slug_name 	 = $this->request->param('slug_name');	
		$sites   = $this->_get_sites($slug_id);
		if(!$sites){
			Auth::instance()->logout();
			$this->redirect();
		}
		
		View::set_global('common_question'	, $sites["common_question"]);
		
		$siteId = $sites['id'];
		$this->data['siteidpk'] = $siteId;
		$sitecontent	=	$this->_getHomepageUnique($sites['id']);
		$socialcontent  =	$this->_getSocialUnique($sites['id']);
		$this->site_title = $this->site_title.' - '.$sites["name"];
		$this->session = Session::instance();
		$this->session->set('user_from', 'front');
		$siteallsettings = Helper_Common::selectgeneralfn('site_settings','*','site_id="'.$siteId.'"');
        $language_details = Helper_Common::getlanguage(isset($siteallsettings[0]['language']) ? $siteallsettings[0]['language'] : 1 );
        if ($language_details != null)
			$this->session->set('lang', $language_details);
		else
			$this->session->set('lang', 'en');
		if($siteId != ''){
			$this->session->set('idsite', $siteId);
		}else{
			$this->session->set('idsite', 1);
		}
		/*if (Request::current()->param('lang') != null)
			$this->session->set('lang', Request::current()->param('lang'));
		else
			$this->session->set('lang', 'en');*/
			//echo "===========".'front-'.$this->session->get('idsite').'-'.$this->session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller());

		I18n::lang('front-'.$this->session->get('idsite').'-'.$this->session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller()));
		$this->data['siteid'] = $slug_id;
		$this->data['title'] = $this->site_title;
		if( !empty($sitecontent[0]['site_logo'])) {
			$this->data['site_logo'] = $sitecontent[0]['site_logo'];
		}
		if( !empty($sitecontent[0]['advanced_css'])) {
			$this->data['site_css'] = $sitecontent[0]['advanced_css'];
		}
		if( !empty($sitecontent[0]['bg_color'])) {
			$this->data['bg_color'] = $sitecontent[0]['bg_color'];
		}
		if( !empty($sitecontent[0]['font_color'])) {
			$this->data['font_color'] = $sitecontent[0]['font_color'];
		}
		if( !empty($socialcontent[0])) {
			$this->data['social_title'] = (!empty($socialcontent[0]['title']) ? trim(strip_tags($socialcontent[0]['title'])) : '');
			$this->data['social_image'] = (!empty($socialcontent[0]['site_image']) ? URL::site(NULL, 'http').'assets/uploads/logo/'.$socialcontent[0]['site_image'] : URL::site(NULL, 'http').'assets/img/icon-mw192x192.png');
			$this->data['social_video'] = ($socialcontent[0]['video_status'] == '1' ? $socialcontent[0]['video'] : '');
			$this->data['social_desc']  = trim(strip_tags($socialcontent[0]['description']));
		}
		if(!empty($slug_id)&& ($slug_id!='')){	
			 $siteurl=URL::base().'site/'.$slug_id.'/';
		}else{
			 $siteurl=URL::base();
		}
		$this->data['siteurl'] =  $siteurl;
		if($this->auto_render) {
			
		  $this->template->title            = '';
		  $this->template->meta_keywords    = 'My Workouts';
		  $this->template->meta_description = 'My Workouts';
		  $this->template->content          = '';
		  
		}
		if(Auth::instance()->logged_in()){
			$this->session->set('user_allow_tour','disallow');
			$this->session->set('user_allow_edit_notify','disallow');
			$cur_file = 'Controller_Site_'.$this->request->controller();
			$pageName = strtolower('Site_'.$this->request->controller()).'_'.strtolower($this->request->action());
			if (class_exists($cur_file) && method_exists($cur_file,'action_'.$this->request->action())) {
				$page_id  = Helper_Common::getPageIdbyName($pageName);
				$hideArray = Helper_Common::getAllowAllAccessByUser($page_id);
				$this->session->set('user_allow_page',$page_id);
				if(is_array($hideArray) && count($hideArray) > 0){
					$this->session->set('user_allow_tour',($hideArray['is_tour_hidden'] ? 'disallow' : 'allow'));
					$this->session->set('user_allow_edit_notify',($hideArray['is_edit_notify_hidden'] ? 'disallow' : 'allow'));
				}
			}
			$networkModel 	= ORM::factory('networks');
			$globaluser = Auth::instance()->get_user()->pk();
			$chatnotify = $networkModel->get_network_users_unread_count($globaluser,'');
			$this->session->set('chatnotify',($chatnotify>0 ? $chatnotify : ''));
			$settings_model = ORM::factory('settings');
			$this->user_timezone = '';
			$this->user_timeformat = '';
			$this->user_dateformat = '';
			$this->user_weight = '';
			$this->user_distance = '';
			$this->user_language = '';
			$user_settings = $settings_model->getsettings();
			if(!empty($user_settings) && count($user_settings)>0){
				$this->user_timezone	= $user_settings[0]['timezone'];
				$this->user_timeformat = $user_settings[0]['time_format'];
				$this->user_dateformat = $user_settings[0]['date_format'];
				$this->user_datetimeformat = $this->user_dateformat.' '.$this->user_timeformat;
				$this->user_weight = $user_settings[0]['Weight'];
				$this->user_distance	= $user_settings[0]['Distance'];
				$this->user_language	= $user_settings[0]['language'];
				$this->user_week_starts	= $user_settings[0]['week_sarts_on'];
				View::set_global('user_timezone', $this->user_timezone);
				View::set_global('user_timeformat', $this->user_timeformat);
				View::set_global('user_dateformat', $this->user_dateformat);
				View::set_global('user_datetimeformat', $this->user_datetimeformat);
				View::set_global('user_weight', $this->user_weight);
				View::set_global('user_distance', $this->user_distance);
				View::set_global('user_language', $this->user_language);
				View::set_global('user_week_starts', $this->user_week_starts);
				$this->session->set('user_timezone', $this->user_timezone);
				$this->session->set('user_timeformat', $this->user_timeformat);
				$this->session->set('user_dateformat', $this->user_dateformat);
				$this->session->set('user_weight', $this->user_weight);
				$this->session->set('user_distance', $this->user_distance);
				$this->session->set('user_datetimeformat', $this->user_datetimeformat);
				$this->session->set('user_language', $this->user_language);
				$this->session->set('user_week_starts', $this->user_week_starts);
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
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
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
				$activity_feed["extra_info"]	= $deviceInfo;
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
		if ($this->template->title){
			$this->template->title = $this->template->title.' » '.$this->site_title;
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
			$view = 'pages/'.$this->request->directory().'/'.$this->request->controller();
		$this->template->content = View::factory($view);
		$this->template->content->error_messages = (isset($this->data['error_messages']) ? $this->data['error_messages'] : array());
		$this->template->content->title = $this->site_title;
		$this->template->content->header 	= View::factory('templates/site/header')->set('data', $this->data);
		$this->template->content->footer 	= View::factory('templates/site/footer')->set('data', $this->data);
		$this->template->content->slug_id = $this->request->param('id');	
	}
	public function _get_sites($slug_id){
		$hompage = ORM::factory('homepage');
		$result=$hompage->get_sites($slug_id);
		return $result;
	}
	public function _getHomepageUnique($site_id){
		$hompage = ORM::factory('homepage');
		$result=$hompage->site_cms_homepage_content($site_id);
		return $result;
	}
	public function _getSocialUnique($site_id){
		$hompage = ORM::factory('homepage');
		$result=$hompage->site_cms_social_content($site_id);
		return $result;
	}
} // End Welcome
