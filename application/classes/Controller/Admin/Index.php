<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Index extends Controller_Admin_Website {

	public function before()
	{
		parent::before();
		$user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
		Session::instance()->set('user_from',$user_from);
	}
	
	public function _construct($request, $response) {
		parent::__construct($request, $response);
	} 
		
	public function action_index()
	{
		$this->template->title = 'Admin Login';
		$this->template->js_bottom = array('assets/plugins/iCheck/icheck.min.js');
		$usermodel = ORM::factory('admin_user');
		$user =  Auth::instance()->get_user();
		if($user){
			$this->redirect('admin/dashboard');
		}
		if (HTTP_Request::POST == $this->request->method() ){
			// Attempt to login user
			if(!Helper_Common::isCookieEnable()) {
				$this->data['error_messages'] = array('Please enable your browser cookie');
			} else {
				$remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
				$validator = $usermodel->validate_user_login(arr::extract($_POST, array(
					'user_email',
					'password'
				)));
				//echo "<pre>";print_r($validator);die();
				if ($validator->check()) {
					$adminuser = Auth::instance()->login($this->request->post('user_email'), $this->request->post('password'), $remember);
					// If successful, redirect user
					if ($adminuser){
						$user =  Auth::instance()->get_user();
						//Get user sites
						if (Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) {
							$usersites = $usermodel->get_user_sites($user->id);
							$session = Session::instance();
							$session->set('user_sites', $usersites);
							//echo '<pre>';print_r($usersites);echo '</pre>';
							//die();
							if(is_array($usersites) && count($usersites) > 0){
								$site_id = $usersites[0]['site_id'];
								$site_name = $usersites[0]['name'];
								$slug_name = $usersites[0]['slug'];
							}else{
								//$site_id = 1;
								//$site_name = "My Workouts";
							}
							$session->set('current_site_id', $site_id);
							$session->set('current_site_name', $site_name);
							$session->set('current_site_slug', $slug_name);
							$this->user = $user;
							
							
							/*****User Site update**********/
							$user_id = $user->id;
							$update_str = 'last_login=now()';
							$condtn_str = 'user_id='.$user->id.' and site_id='.$site_id;
							$usermodel->updateUserSites($update_str,$condtn_str);
							/*****User Site update**********/
							
							
							/******************* Activity Feed *********************/
							$activity_feed = array();
							$activity_feed["feed_type"]   = 10;
							$activity_feed["action_type"]  = 12; 
							$activity_feed["type_id"]    = $user->id;
							$activity_feed["user"]     = $user->id;
							$activity_feed["site_id"]     = $site_id;
							Helper_Common::createActivityFeed($activity_feed);
							/******************* Activity Feed *********************/
							$this->redirect('admin/dashboard');
						} else {
							Auth::instance()->logout();
							$this->data['error_messages'] = array('The login details provided does not have access to this admin section');
						}
					}
					else{
						Auth::instance()->logout();
						$this->data['error_messages'] = array('Username or password is invalid');
					}
				}else{
					$this->data['error_messages'] = array('The login details provided does not have access to this admin section');
				}
			}
		} 
		$this->render();
	}
	
	/**
     * Logout
     */
    public function action_logout()
    {
		$userId = $this->globaluser->pk();
        Auth::instance()->logout();
		  /******************* Activity Feed *********************/
			$activity_feed = array();
			$activity_feed["feed_type"]   = 10;
			$activity_feed["action_type"]  = 13;
			$activity_feed["type_id"]    = $userId;
			$activity_feed["site_id"]  = $this->current_site_id;
			$activity_feed["user"]     = $userId;
			Helper_Common::createActivityFeed($activity_feed);
			/******************* Activity Feed *********************/
			/**************** Activity Feed browser*******************/
			$browserObj = new Helper_Browser();
			$browserName= $browserObj->getBrowser();
			$deviceName = $browserObj->getPlatform();
			$deviceInfo = $browserObj->__toString();
			$activity_feed = array();
			$activity_feed["feed_type"]   	= 31; // This get from feed_type table
			$activity_feed["action_type"]  	= 13;  // This get from action_type table  
			$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
			$activity_feed["site_id"]  		= $this->current_site_id;
			$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>Session::instance()->get('user_from')));
			$activity_feed["extra_info"]  	= $deviceInfo;
			Helper_Common::createActivityFeed($activity_feed);
			/***************** Activity Feed browser*******************/
			$this->redirect('admin/index');
    }
	
	/**
     * Forgotten Password
     */
	public function action_recover()
	{ 
		$this->template->title = 'Forgotten Password';
		$userid = base64_decode(urldecode($this->request->param('id')));
		$this->render(); 
		$profileData = array();
		$usermodel = ORM::factory('user'); //echo $userModel; die;
//$userModel = ORM::factory('user'); echo $userModel; die;
		$smtpmodel = ORM::factory('admin_smtp'); 
		
		if (HTTP_Request::POST == $this->request->method() && empty($userid)){  //print_r($userModel); die;
			if(isset($_POST['identify_search']) && isset($_POST['identify_email']) && !empty($_POST['identify_email'])){
				//$profileData = $usermodel->where('user_email', '=', trim($_POST['identify_email']))->or_where('user_mobile', '=', trim($_POST['identify_email']))->find();
				$profileData = $usermodel->where('user_email', '=', trim($_POST['identify_email']))->find();
				
				if($profileData->pk())
					$this->redirect('admin/index/recover/'.base64_encode($profileData->pk()));
				else{
					$this->session->set('flash_error_message', 'Entered email / phone number is invalid.');
					$this->redirect('admin/index/recover/');
				}
			}
		}elseif(!empty($userid) && HTTP_Request::POST == $this->request->method()){
			if(isset($_POST['reset_action'])){
				$usermodel->where('id', '=', trim($userid))->find();
				if($usermodel->pk()){
					$security_code = $usermodel->security_code = md5(microtime().rand());
					$usermodel->save();
					$this->session->set('recover_method',$_POST['recover_method']);
					if(isset($_POST['recover_method']) && $_POST['recover_method'] =='send_email'){
						$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Forgot Password'));
						$messageArray = array('subject'	=> $templateArray['subject'],
												  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
												  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
												  'to'		=> $usermodel->user_email,
												  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
												  'toname'	=> ucfirst(strtolower($usermodel->user_fname)).' '.ucfirst(strtolower($usermodel->user_lname)),
												  'body'	=> str_replace(array('[FirstName]','[SecureCode]'),array(ucfirst(strtolower($usermodel->user_fname)),$security_code),$templateArray['body']),
												  'type'	=> 'text/html');
												  
						if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
							$hostAddress = explode("://",$templateArray['smtp_host']);
							$emailMailer = Email::dynamicMailer('smtp',array(
																			  'hostname'   => trim($hostAddress['1']), 
																			  'port' 	   => $templateArray['smtp_port'], 
																			  'username'   => $templateArray['smtp_user'],   
																			  'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
																			  'encryption' => trim($hostAddress['0'])
																			  )
																);
						}else
							$emailMailer = Email::dynamicMailer('',array());
							
						
						/******************* Activity Feed *********************/
						$d_sql = "SELECT u.site_id FROM users as u join sites as s on u.site_id=s.id where s.is_active=1 and u.id = ".$userid;
						$qry		= DB::query(Database::SELECT, $d_sql);
						$site = $qry->execute()->as_array();
						$activity_feed = array();
						$activity_feed["action_type"]  = 11;
						$activity_feed["user"]     = $userid;
						$activity_feed["site_id"]		= ($site)?$site[0]["site_id"]:'';
						Helper_Common::createActivityFeed($activity_feed);
						/******************* Activity Feed *********************/	
						//print_r($messageArray); die;
						//Email::sendBysmtp($emailMailer,$messageArray);
					}else{
						
					} 
					$this->redirect('admin/index/securitycheck/'.base64_encode($usermodel->pk()));
				}else{
					$this->session->set('flash_error_message', 'Entered email / phone number is invalid.');
					$this->redirect('admin/index/recover/');
				}
			} 
		}elseif(!empty($userid)){
			$profileData = $usermodel->where('id', '=', trim($userid))->find();
		} 
		$this->template->content->userid = $userid;
		$this->template->content->userDetail = $profileData;

	}
	
	public function action_switchsite()
	{
		$siteid = $this->request->param('id');
		$site = ORM::factory('Sites', $siteid);
		$session = Session::instance();
		$session->set('current_site_id', $site->id);
		$session->set('current_site_name', $site->name);
		$session->set('current_site_slug', $site->slug);
		$session->set('current_site_agelimit', $site->min_agelimit);
		$this->redirect('admin/dashboard');	
	}
	public function action_securitycheck()
	{ 
		$this->template->title = 'Security Code'; 
		$userid = base64_decode(urldecode($this->request->param('id'))); 
		$profileData = array();
		$this->render();
		$usermodel = ORM::factory('user');  
		if (!empty($userid)){
			$profileData = $usermodel->where('id', '=', trim($userid))->find();
		}else{
			$this->redirect('admin/index/recover');	
		}
		if(HTTP_Request::POST == $this->request->method() && isset($_POST['identify']) && base64_decode(trim($_POST['identify'])) == $userid && isset($_POST['identify_submit'])){
			if($profileData->loaded() && $profileData->security_code == trim($_POST['security_code'])){
				$this->session->get_once('recover_method');
				$this->session->set('identify',$userid);
				$this->redirect('admin/index/generate/'.base64_encode($usermodel->pk()));
			}else{
				$this->session->set('flash_error_message', 'Entered code not matching!!! Please try again!!!');
			}
		}
		$this->template->content->userDetail = $profileData;
	}
	
	
} // End Welcome
