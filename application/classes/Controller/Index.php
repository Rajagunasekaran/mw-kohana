<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Website {

	public function _construct() {
         parent::__construct($request, $response);
    } 

	public function action_index()
	{
		if (!Auth::instance()->logged_in()) {
			if($this->request->param('site_name')){
				$this->redirect(URL::site(NULL, 'http').'site/'.$this->request->param('site_name'));
			}
		}
		$this->template->title = 'Log In or Sign Up';
		$usermodel = ORM::factory('user');	
		$smtpmodel = ORM::factory('admin_smtp');
		if (HTTP_Request::POST == $this->request->method()){
			$this->data['cookie_error'] = false;
			// Attempt to login user or signup
			if(!Helper_Common::isCookieEnable()) {
				$this->data['cookie_error'] = true;
			} 
			if(isset($_POST['login'])){
				if($this->data['cookie_error']) {
					$this->redirect('index/login?cookie=0');		
				} else {
					$remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
					$validator = $usermodel->validate_user_login(arr::extract($_POST,array('user_email','password')));
					if ($validator->check()) {
						$user = Auth::instance()->login($this->request->post('user_email'), $this->request->post('password'), $remember);
						// If successful, redirect user
						if ($user){
							// setting dynamic site id for users dated on 19th april 2016
							$site_id = Auth::instance()->get_user()->site_id;
							if(!empty($site_id)){
								$sites        	= Helper_Common::hasSiteAccess($site_id);
								$this->session->set('current_site_id', $site_id);
								$this->session->set('current_site_name', $sites['name']);
								$this->session->set('current_site_slug', $sites['slug']);
							}
							/******************* Activity Feed *********************/
							$activity_feed = array();
							$activity_feed["feed_type"]   	= 10; // This get from feed_type table
							$activity_feed["action_type"]  	= 12;  // This get from action_type table  
							$activity_feed["type_id"]=$activity_feed["user"]= Auth::instance()->get_user()->pk();
							$activity_feed["site_id"]  		= $this->session->get('current_site_id');
							Helper_Common::createActivityFeed($activity_feed);
							/******************* Activity Feed *********************/
							// update to user_sites
							$slug_id  = ($this->session->get('current_site_slug') ? $this->session->get('current_site_slug').'/' : ''); 
							$this->redirect($slug_id.'dashboard/index');
						}
						else{
							$this->session->set('common_error','Username or password is invalid');
							$this->redirect('index/login');
						}
					} else {
						//validation failed, get errors
						$this->data['error_messages']= $validator->errors('errors/en');
					}
					if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
						foreach($this->data['error_messages'] as $keys => $value){
							$this->session->set($keys.'_error',$value);
						}
						$this->redirect('index/login');		
					}
				}
			}elseif(isset($_POST['signup'])){
				if($this->data['cookie_error']) {
					$this->redirect('index/signup?cookie=0');		
				} else {
					
					$currentDate	= Helper_Common::get_default_datetime();		
					// Create User
					$validator = $usermodel->validate_user_create(arr::extract($_POST,array('user_fname','user_lname','user_email','user_reenter_email','password','birthday_month','birthday_day','birthday_year')));
					if ($validator->check()) {
						//validation passed, add to the db
						$user = ORM::factory('user')->create_user($this->request->post(), array(
							'user_email',
							'password'
						
						));
						//clearing so it won't populate the form
						$validator = null;
						// Grant user login role && admin or user role
						$user->add('roles', ORM::factory('Role', array('name' => 'register')));
						$user->user_fname 		=	$this->request->post('user_fname');
						$user->user_lname 		=	$this->request->post('user_lname');
						$user->user_gender		=	$this->request->post('user_gender');
						$user->ip_address 		=	$_SERVER['REMOTE_ADDR'];
						$user->activation_code	=	md5(microtime().rand());
						$user->user_dob 		=	date("Y-m-d",strtotime($this->request->post('birthday_year').'-'.$this->request->post('birthday_month').'-'.$this->request->post('birthday_day')));
						$user->date_created 	=	$currentDate;
						$user->site_id			=	($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
						$user->save();
						// Reset values so form is not sticky
						$_POST = array();
						$this->session->set('flash_success_popup', 'A registration activation link has been emailed to the email address you provided. This activation code will expire in 24 hours.');
						$useractivation = URL::site(NULL, 'http').'index/activate/'.$user->activation_code;
						if(!empty($user->user_email)){
							/*
							$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register'));
							$messageArray = array('subject'	=> $templateArray['subject'],
													  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
													  'to'		=> $user->user_email,
													  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'toname'	=> ucfirst(strtolower($user->user_fname)).' '.ucfirst(strtolower($user->user_lname)),
													  'body'	=> str_replace(array('[FirstName]','[ActivationLink]'),array(ucfirst(strtolower($user->user_fname)),$useractivation),$templateArray['body']),
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
							
							Email::sendBysmtp($emailMailer,$messageArray);
							*/
						}
						/******************* Activity Feed *********************/
						$activity_feed = array();
						$activity_feed["feed_type"]   	= 10; // This get from feed_type table
						$activity_feed["action_type"]  	= 17;  // This get from action_type table  
						$activity_feed["type_id"]    	= $activity_feed["user"]  = $user->id;
						$activity_feed["site_id"]  		= $this->session->get('current_site_id');
						Helper_Common::createActivityFeed($activity_feed);
						/******************* Activity Feed *********************/
					} else {
						//validation failed, get errors
						$this->data['error_messages'] = $validator->errors('errors/en');
					}
					if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
						$key_array = array_keys($this->data['error_messages']);
						$key_str = implode(',',$key_array);
						foreach($_POST as $keys => $values){
							$this->session->set($keys,trim($values));
						}
						if($key_str=='user_email') {
							foreach($this->data['error_messages'] as $keys => $value){
								if($value=='Email is unconfirmed.') {
									$this->session->set('resendConfirmation','1');
									$this->session->set('user_email',$this->request->post('user_email'));
									$this->session->set('activation_code',md5(microtime().rand()));
									$this->session->set('date_created',$currentDate);
									$this->session->set('user_fname',$this->request->post('user_fname'));
									$this->session->set('user_lname',$this->request->post('user_lname'));
								} else if(($value=='Phone number is unconfirmed.') || ($value=='Email / Phone number is confirmed.') )  {
									$this->session->set('forgotPassword','Email / Phone number is already exists.');
								} else {
									$this->session->set($keys.'_error',$value);
								}
							}
						} else {
							foreach($this->data['error_messages'] as $keys => $value){
								$this->session->set($keys.'_error',$value);
							}
						}
						$this->redirect('index/signup');		
					}
				}
			}
		}
		$this->render();
	}
	
	public function action_activate(){
		$activationKey = urldecode($this->request->param('id'));
		$usermodel = ORM::factory('user');
		$usermodel->where('activation_code', '=', trim($activationKey))->where('date_created', '>', date('Y-m-d H:i:s',strtotime('-1 day')))->find();
		if($usermodel->pk()){
			if($usermodel->has('roles', ORM::factory('Role', array('name' => 'login')))){
				$this->session->set('flash_activation_popup', 'Your account has already activated.');
				$this->redirect('index');
			}else{
				$usermodel->add('roles', ORM::factory('Role', array('name' => 'login')));
				$usermodel->last_updated =Helper_Common::get_default_datetime();
				$usermodel->user_access ='1';
				$usermodel->save();
				$this->session->set('flash_activation_popup', 'Your account has been activated.');
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 10;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $usermodel->pk();
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				$this->redirect('index');
			}
		}else{
			$this->session->set('flash_error_message', 'Your activation key is invalid, your account is deleted from our database.');
			$this->redirect('index/recover');
		}
	}
	public function action_login()
	{
		$this->template->title = 'Log In';
		$this->render();
	}	
	public function action_signup()
	{
		$this->template->title = 'Sign up';
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		if (HTTP_Request::POST == $this->request->method()){
			if(isset($_POST['signup'])){
				if(!Helper_Common::isCookieEnable()) {
					$this->redirect('index/signup?cookie=0');
				} 
				$currentDate	= Helper_Common::get_default_datetime();		
				// Create User
				$validator = $usermodel->validate_user_create(arr::extract($_POST,array('user_fname','user_lname','user_email','user_reenter_email','password','birthday_month','birthday_day','birthday_year')));
				if ($validator->check()) {
					//validation passed, add to the db
					$user = ORM::factory('user')->create_user($this->request->post(), array(
						'user_email',
						'password'
					));
					//clearing so it won't populate the form
					$validator = null;
					// Grant user login role && admin or user role
					$user->add('roles', ORM::factory('Role', array('name' => 'register')));
					$user->user_fname 		=	$this->request->post('user_fname');
					$user->user_lname 		=	$this->request->post('user_lname');
					$user->user_gender		=	$this->request->post('user_gender');
					$user->ip_address 		=	$_SERVER['REMOTE_ADDR'];
					$user->activation_code	=	md5(microtime().rand());
					$user->user_dob 		=	date("Y-m-d",strtotime($this->request->post('birthday_year').'-'.$this->request->post('birthday_month').'-'.$this->request->post('birthday_day')));
					$user->date_created 	=	$currentDate;
					$user->site_id			=	($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
					$user->save();
					// Reset values so form is not sticky
					$_POST = array();
					$this->session->set('flash_success_popup', 'A registration activation link has been emailed to the email address you provided. This activation code will expire in 24 hours.');
					$useractivation = URL::site(NULL, 'http').'index/activate/'.$user->activation_code;
					if(!empty($user->user_email)){
						$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register'));
						$messageArray = array('subject'	=> $templateArray['subject'],
												  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
												  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
												  'to'		=> $user->user_email,
												  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
												  'toname'	=> ucfirst(strtolower($user->user_fname)).' '.ucfirst(strtolower($user->user_lname)),
												  'body'	=> str_replace(array('[FirstName]','[ActivationLink]'),array(ucfirst(strtolower($user->user_fname)),$useractivation),$templateArray['body']),
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
						
						Email::sendBysmtp($emailMailer,$messageArray);
					}
					
					$this->redirect('index');
				} else {
					//validation failed, get errors
					$this->data['error_messages'] = $validator->errors('errors/en');
				}
				if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
					$key_array = array_keys($this->data['error_messages']);
					$key_str = implode(',',$key_array);
					foreach($_POST as $keys => $values){
						$this->session->set($keys,trim($values));
					}
					if($key_str=='user_email') {
						foreach($this->data['error_messages'] as $keys => $value){
							if($value=='Email is unconfirmed.') {
								$this->session->set('resendConfirmation','1');
								$this->session->set('user_email',$this->request->post('user_email'));
								$this->session->set('activation_code',md5(microtime().rand()));
								$this->session->set('date_created',$currentDate);
								$this->session->set('user_fname',$this->request->post('user_fname'));
								$this->session->set('user_lname',$this->request->post('user_lname'));
							} else if(($value=='Phone number is unconfirmed.') || ($value=='Email / Phone number is confirmed.') )  {
								$this->session->set('forgotPassword','Email / Phone number is already exists.');
							} else {
								$this->session->set($keys.'_error',$value);
							}
						}
					} else {
						foreach($this->data['error_messages'] as $keys => $value){
							$this->session->set($keys.'_error',$value);
						}
					}
					$this->redirect('index/signup');		
				}
			} else if(isset($_POST['resend'])) {
				$user = array();
				$currentDate	= Helper_Common::get_default_datetime();	
				$user['user_email']		= $this->request->post('user_email');
				$user['activation_code']= md5(microtime().rand());
				$user['date_created'] 	= $currentDate;
				$user['user_fname']		= $this->request->post('user_fname');
				$user['user_lname']		= $this->request->post('user_lname');
				//$this->session->set('flash_success_popup', 'A registration activation link has been emailed to the email address you provided. This activation code will expire in 24 hours.');
				$useractivation = URL::site(NULL, 'http').'index/activate/'.$user['activation_code'];
				if(!empty($user['user_email'])){
					DB::update('users')->set(array('activation_code' => $user['activation_code'],'last_updated' => $user['date_created']))->where('user_email', '=', $user['user_email'])->execute();
					$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register'));
					$messageArray = array('subject'	=> $templateArray['subject'],
											  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
											  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
											  'to'		=> $user['user_email'],
											  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
											  'toname'	=> ucfirst(strtolower($user['user_fname'])).' '.ucfirst(strtolower($user['user_lname'])),
											  'body'	=> str_replace(array('[FirstName]','[ActivationLink]'),array(ucfirst(strtolower($user['user_fname'])),$useractivation),$templateArray['body']),
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
					Email::sendBysmtp($emailMailer,$messageArray);
					$this->session->set('flash_success_popup','Resended Confirmation email');
				}
				$this->redirect('index/signup');	
			}
		}
		$this->render();
	}
	
	
	
	public function action_recover()
	{
		$this->template->title = 'Forgotten Password';
		$userid = base64_decode(urldecode($this->request->param('id')));
		$this->render();
		$profileData = array();
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		if (HTTP_Request::POST == $this->request->method() && empty($userid)){
			if(isset($_POST['identify_search']) && isset($_POST['identify_email']) && !empty($_POST['identify_email'])){
				$profileData = $usermodel->where('user_email', '=', trim($_POST['identify_email']))->or_where('user_mobile', '=', trim($_POST['identify_email']))->find();
				if($profileData->pk())
					$this->redirect('index/recover/'.base64_encode($profileData->pk()));
				else{
					$this->session->set('flash_error_message', 'Entered email / phone number is invalid.');
					$this->redirect('index/recover/');
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
						/*
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
						//print_r($messageArray); die;
						//Email::sendBysmtp($emailMailer,$messageArray);
						*/
						/******************* Activity Feed *********************/
						$activity_feed = array();
						$activity_feed["feed_type"]   	= 10; // This get from feed_type table
						$activity_feed["action_type"]  	= 11;  // This get from action_type table  
						$activity_feed["type_id"]    	= $activity_feed["user"]  = $usermodel->pk();
						$activity_feed["site_id"]  		= $this->session->get('current_site_id');
						Helper_Common::createActivityFeed($activity_feed);
						/******************* Activity Feed *********************/
					}else{
						
					}
					$this->redirect('index/securitycheck/'.base64_encode($usermodel->pk()));
				}else{
					$this->session->set('flash_error_message', 'Entered email / phone number is invalid.');
					$this->redirect('index/recover/');
				}
			}
		}elseif(!empty($userid)){
			$profileData = $usermodel->where('id', '=', trim($userid))->find();
		}
		$this->template->content->userid = $userid;
		$this->template->content->userDetail = $profileData;
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
			$this->redirect('index/recover');	
		}
		if(HTTP_Request::POST == $this->request->method() && isset($_POST['identify']) && base64_decode(trim($_POST['identify'])) == $userid && isset($_POST['identify_submit'])){
			if($profileData->loaded() && $profileData->security_code == trim($_POST['security_code'])){
				$this->session->get_once('recover_method');
				$this->session->set('identify',$userid);
				$this->redirect('index/generate/'.base64_encode($usermodel->pk()));
			}else{
				$this->session->set('flash_error_message', 'Entered code not matching!!! Please try again!!!');
			}
		}
		$this->template->content->userDetail = $profileData;
	}
	/**
     * new password
     */
    public function action_generate()
    {
		$this->template->title = 'Create a new password';
		$userid = base64_decode(urldecode($this->request->param('id')));
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		if (!empty($userid)){
			$usermodel->where('id', '=', trim($userid))->find();
		}else{
			$this->redirect('index/recover');	
		}
		if(HTTP_Request::POST == $this->request->method() && isset($_POST['new_pass']) && base64_decode(trim($_POST['identify'])) == $userid && isset($_POST['generate_submit'])){
			$validator = $usermodel->validate_user_password(arr::extract($_POST,array('new_pass','conf_pass')));
			if ($validator->check()) {
				$usermodel->set('password',trim($_POST['new_pass']));
				$usermodel->set('last_updated',Helper_Common::get_default_datetime());
				$usermodel->update();
				$siteDetails['link']			=  URL::base(true);
				$siteDetails['title']			=  'My Workouts';
				if(!empty($usermodel->user_email)){
					/*
					$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Password reset'));
					$messageArray = array('subject'	=> $templateArray['subject'],
											  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
											  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
											  'to'		=> $usermodel->user_email,
											  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
											  'toname'	=> ucfirst(strtolower($usermodel->user_fname)).' '.ucfirst(strtolower($usermodel->user_lname)),
											  'body'	=> str_replace(array('[FirstName]'),array(ucfirst(strtolower($usermodel->user_fname))),$templateArray['body']),
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
					Email::sendBysmtp($emailMailer,$messageArray);
					*/
					/******************* Activity Feed *********************/
					$activity_feed = array();
					$activity_feed["feed_type"]   	= 10; // This get from feed_type table
					$activity_feed["action_type"]  	= 14;  // This get from action_type table  
					$activity_feed["type_id"]    	= $activity_feed["user"]  = $userid;
					$activity_feed["site_id"]  		= $this->session->get('current_site_id');
					Helper_Common::createActivityFeed($activity_feed);
					/******************* Activity Feed *********************/
				}else{
				
				}
				$this->session->set('flash_success_popup', 'Your account password was successfully reset.');
				$this->redirect('index');
			} else {
				//validation failed, get errors
				$this->data['error_messages'] = $validator->errors('errors/en');
			}
			if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
				foreach($this->data['error_messages'] as $keys => $value){
					$this->session->set($keys.'_error',$value);
				}
				$this->redirect('index/generate/'.base64_encode($userid));	
			}		
		}
		$_POST = array();
		$this->render();
		$this->template->content->userid =base64_encode($usermodel->pk());
    }
	/**
     * Logout
     */
    public function action_logout()
    {
		$slug_name = ($this->session->get('current_site_slug') ? 'site/'.$this->session->get('current_site_slug').'/' : 'index');
		/******************* Activity Feed *********************/
		$activity_feed = array();
		$activity_feed["feed_type"]   	= 10; // This get from feed_type table
		$activity_feed["action_type"]  	= 13;  // This get from action_type table  
		$activity_feed["type_id"]    	= $activity_feed["user"]  = Auth::instance()->get_user()->pk();
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
		$activity_feed["action_type"]  	= 13;  // This get from action_type table  
		$activity_feed["type_id"]    	= $activity_feed["user"]  = Auth::instance()->get_user()->pk(); // user id
		$activity_feed["site_id"]  		= $this->session->get('current_site_id');
		$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>$this->session->get('user_from')));
		$activity_feed["extra_info"]	= $deviceInfo;
		Helper_Common::createActivityFeed($activity_feed);
		/******************* Activity Feed Browser*********************/
		Auth::instance()->logout();
		$this->redirect(URL::base().$slug_name);
    }
	/**
     * deactivate
     */
    public function action_deactivate()
    {
		$this->template->title = 'Create a new password';
		$userid = base64_decode(urldecode($this->request->param('id')));
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		if (!empty($userid)){
			$usermodel->where('id', '=', trim($userid))->find();
		}else{
			$this->redirect('index/recover');	
		}
		if(!empty($usermodel->user_email)){
			/*$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Cancel Account'));
			$messageArray = array('subject'	=> $templateArray['subject'],
									  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
									  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
									  'to'		=> $usermodel->user_email,
									  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
									  'toname'	=> ucfirst(strtolower($usermodel->user_fname)).' '.ucfirst(strtolower($usermodel->user_lname)),
									  'body'	=> str_replace(array('[FirstName]'),array(ucfirst(strtolower($usermodel->user_fname))),$templateArray['body']),
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
			Email::sendBysmtp($emailMailer,$messageArray);
			*/
			/******************* Activity Feed *********************/
			$activity_feed = array();
			$activity_feed["feed_type"]   	= 10; // This get from feed_type table
			$activity_feed["action_type"]  	= 23;  // This get from action_type table  
			$activity_feed["type_id"]    	= $activity_feed["user"]  = Auth::instance()->get_user()->pk();
			$activity_feed["site_id"]  		= $this->session->get('current_site_id');
			Helper_Common::createActivityFeed($activity_feed);
			/******************* Activity Feed *********************/
			$usermodel->set('deleted','0');
			$usermodel->set('last_updated',Helper_Common::get_default_datetime());
			$usermodel->update();
			Auth::instance()->logout();
			$this->session->set('flash_success_popup', 'Your account was Cancelled successfully.');
			$this->redirect('index');
		}
    }
	public function action_home()
	{
		$user = Auth::instance()->get_user();
		$this->render();
		$this->template->content->userDetails = $user;
	}
	public function _get_sites($slug_id){
		$hompage = ORM::factory('homepage');
		$result=$hompage->get_sites($slug_id);
		return $result;
	}
	public function _makeConnectionRequest($fromuser,$touser,$site_id){
		$networksModel		= ORM::factory('networks');
		$smtpmodel 			= ORM::factory('admin_smtp');
		$datetime 			= Helper_Common::get_default_datetime();
		if (!empty($fromuser) && !empty($touser) && !empty($site_id))
		{
			$getReqIdFrom	= $networksModel->get_request_id($fromuser,$touser);
			$getReqIdTo		= $networksModel->get_request_id($touser,$fromuser);
			//Requset Sent
			if(!$getReqIdFrom && !$getReqIdTo){
				$array						=	array();
				$array["chat_req_userid"] 	= 	$fromuser;
				$array["chat_req_to"]		=	$touser;
				$array["chat_req_msg"]		=	'';
				$array["chat_req_on"]		=	$datetime;
				$cres   = $networksModel->check_request("chat_request",$array["chat_req_userid"],$array["chat_req_to"]);
				if(!$cres)
					$result = $networksModel->insert("chat_request",$array);
				else
					$result = $networksModel->update("chat_request",$array);
				//Requset Sent
				$array						=	array();
				$array["chat_req_userid"] 	= 	$touser;
				$array["chat_req_to"]		=	$fromuser;
				$array["chat_req_msg"]		=	'';
				$array["chat_req_on"]		=	$datetime;
				$array["chat_req_status"]  	=  2; //Request pending
				$cres   = $networksModel->check_request("chat_request",$array["chat_req_userid"],$array["chat_req_to"]);
				if(!$cres)
					$result = $networksModel->insert("chat_request",$array);
				else
					$result = $networksModel->update("chat_request",$array);
				
				$from_user 	= $networksModel->get_user_details($fromuser);
				$user 	= $networksModel->get_user_details($touser);
				//Chat log Entry
				$getReqId=$networksModel->get_request_id($fromuser,$touser);
				$array						=	array();
				$array["chat_req_id"] 		= 	$getReqId;
				$array["chat_log_msg"]      =   "chat_request#@#";
				$array["chat_log_on"]		=	$datetime;
				$array["chat_log_type"]		=	0; //Request
				$result = $networksModel->insert("chat_log",$array);
				
				//Chat log Entry
				$networksModel->update_resend($touser,$fromuser);
				$array						=	array();
				$getReqId =$networksModel->get_request_id($touser,$fromuser);
				$array["chat_req_id"]		=	$getReqId;
				$array["chat_log_msg"]		=	"request_sent#@#".$touser;
				$array["chat_log_on"]		=	$datetime;
				$array["chat_log_resend"]	=	1;
				$array["chat_log_type"]		=	0; //Request
				$result = $networksModel->insert("chat_log",$array);
				
				//$user['user_email'] = "mani@yopmail.com";
				if(!empty($from_user['user_email']) && !empty($user['user_email'])){
					$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Chat Request','site_id'=>$site_id));
					if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
						$message = $array["chat_log_msg"]; 
						$templateArray['body'] = str_replace(array('[FirstName]','[Message]'), array(ucfirst(strtolower($user['user_fname'])),$message), $templateArray['body']);
						$messageArray = array('subject'	=> $templateArray['subject'],
							'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
							'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
							'to'		=> $user['user_email'],
							'replyto'=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
							'toname'	=> ucfirst(strtolower($user['user_fname'])).' '.ucfirst(strtolower($user['user_lname'])),
							'body'	=> $smtpmodel->merge_keywords($templateArray['body'], $site_id),
							'type'	=> 'text/html');
						$hostAddress = explode("://",$templateArray['smtp_host']);
						$emailMailer = Email::dynamicMailer('smtp',array(
							'hostname'   => trim($hostAddress['1']), 
							'port' 	    => $templateArray['smtp_port'], 
							'username'   => $templateArray['smtp_user'],   
							'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
							'encryption' => trim($hostAddress['0'])
							)
						);
					}else{
						$emailMailer = Email::dynamicMailer('',array());
					}
					if(isset($messageArray) && is_array($messageArray)) {
						if(Email::sendBysmtp($emailMailer,$messageArray)){
							echo true;
						}
					}
				}
			}
		}
	}
	public function action_autoredirect(){
		$sitesModel 		 = ORM::factory('admin_sites');
		$userModel 		     = ORM::factory('user');
		$workoutModel        = ORM::factory('workouts');
		$workid 		 	 = urldecode($this->request->param('id'));
		$site_name 	 		 = urldecode($this->request->param('site_name'));
		$currentrefuser 	 = urldecode($this->request->param('token'));
		$getData		 	 = (!empty($_GET['page']) ? $_GET['page'] : '');
		$sites   = $this->_get_sites($site_name);
		$userdetail = Auth::instance()->get_user();
		Session::instance()->set('current_site_id', $sites['id']);
		Session::instance()->set('current_site_name', $sites['name']);
		Session::instance()->set('current_site_slug',$sites['slug']);
		Session::instance()->set('user_from', 'front');
		$userdata_lg = Helper_Common::decryptPassword($currentrefuser);
		$autodatauser = explode("####",$userdata_lg);
		$username_email = $autodatauser[0];
		$user_seccode 	= $autodatauser[1];
		$wkoutType  	= $autodatauser[2];
		$urluserdetail=ORM::factory('user')->where('user_email', '=', trim($username_email))->where('deleted', '=', '0')->find(); 
		if(!empty($getData))
			Session::instance()->set('popup-page',$getData);
		if(is_object($urluserdetail) && !empty($urluserdetail->id)){
			if(is_object($userdetail) && $userdetail->pk() != '' && $userdetail->pk() != $urluserdetail->id){
				Auth::instance()->logout();
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 13;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $urluserdetail->id; // user id
				$activity_feed["site_id"]  		= Session::instance()->get('current_site_id');
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				Auth::instance()->force_login($username_email,false);
			}elseif(!is_object($userdetail)){
				Auth::instance()->force_login($username_email,false);
			}
			Session::instance()->set('newly_loggedin',$urluserdetail->id) ;
			if($wkoutType != ''){
				if($wkoutType == 'assignedplan'){
					$assignedDate = date('Y-m-d');
					$assignedArray = $workoutModel->getAssignInfoById($workid);
					if(!empty($assignedArray)){
						$clickOption  = ' getAssignedwrkoutpreview('."'".$assignedArray['wkout_id']."','".$workid."','".$assignedArray['assigned_date']."','".($assignedArray['from_wkout'] == '0' ? $assignedArray['assigned_by'] : '0')."'".')';
						Session::instance()->set('todayReminder',$clickOption);
					}
					$this->redirect($sites['slug'].'/exercise/myactionplans/'.$assignedDate);
				}elseif($wkoutType == 'sharedassignworkoutall' || $wkoutType == 'sharedassignworkout'){
					$assignedDate = date('Y-m-d');
					$datevalue = Helper_Common::get_default_datetime();
					if($wkoutType == 'sharedassignworkout'){
						$wkoutArray		= $workoutModel->getShareAssignworkoutByIdvalue($workid,'',$urluserdetail->id);
						if(count($wkoutArray) > 0){
							$workoutRecord  = $workoutModel->getShareworkoutById($urluserdetail->id, $wkoutArray['wkout_share_id']);
							$exerciseRecord = $workoutModel->getExerciseSets('shared', $wkoutArray['wkout_share_id']);
							
							$updateArray = array();
							$updateArray['wkout_id']    = $wkoutArray['wkout_share_id'];
							$updateArray['from_wkout']  = '1';
							$updateArray['wkout_title'] = $workoutRecord['wkout_title'];
							$updateArray['wkout_color'] = $workoutRecord['wkout_color'];
							$updateArray['wkout_focus'] = $workoutRecord['wkout_focus'];
							$updateArray["modified"]    = $datevalue;
							$updateArray['created']     = $datevalue;
							$updateArray['modified_by'] = $updateArray['assigned_by'] = $urluserdetail->id;
							$updateArray['assigned_for'] = $wkoutArray['shared_user_id'];
							$updateArray['wkout_group']    = $workoutRecord['wkout_group'];
							$updateArray['wkout_order']    = '1';
							$updateArray['status_id']      = '1';
							$updateArray['user_id']        = $urluserdetail->id;
							$updateArray['created_date']   = $datevalue;
							$updateArray['modified_date']  = $datevalue;
							$updateArray['assigned_date'] = $assignedDate  = Helper_Common::get_default_date($wkoutArray['assign_date']);
							$workidnew = $workoutModel->addToWkoutAssignCustom($updateArray, $urluserdetail->id);
							/** activity for sender **/
							$activity_feed            = array();
							$activity_feed["user"]    = $wkoutArray['shared_user_id'];
							$activity_feed["site_id"] = Session::instance()->get('current_site_id');
							$activity_feed["feed_type"] = '13';
							$activity_feed["action_type"]  = '1';
							$activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
							$activity_feed["type_id"]      = $workidnew;
							$activity_feed["json_data"]    = json_encode(array('assigned'=>$workidnew,'createdbyuser'=>$wkoutArray['assigned_user_id']));
							Helper_Common::createActivityFeed($activity_feed);
							/** activity for sender -end ==== activity for receiver **/
							$activity_feed["feed_type"] = '12';
							$activity_feed["action_type"]  = '22';
							$activity_feed["user"]= $wkoutArray['assigned_user_id'];
							$activity_feed["type_id"]      = $wkoutArray['wkout_share_id'];
							$activity_feed["json_data"]= json_encode(array('wkoutassign'=>$workidnew,'createdbyuser'=>$wkoutArray['shared_user_id']));
							Helper_Common::createActivityFeed($activity_feed);
							/** activity for receiver - end **/
							$count = 0;
							if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
								foreach ($exerciseRecord as $keys1 => $values1) {
									if (is_array($values1) && !empty($values1)) {
										$values1['goal_order'] = $count + 1;
										$values1['wkout_id'] = $workidnew;
										$workoutModel->addAssignWorkoutSetFromExistworkout($values1, $urluserdetail->id, $workidnew);
									}
								}
							}
							/*** email -automation Start ***/
							$emailNotifyArray['wkout_assign_id'] = $workidnew;
							$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
							$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
							$workoutModel->insertEmailNotify($emailNotifyArray);
							/*** email -automation End ***/
							$assignArray = array();
							$assignArray['inserted_assign_id'] = $workidnew;
							$assignArray['status'] = '2';
							$workoutModel->updateShareAssign($assignArray,$wkoutArray['id']);
							$this->session->set('success', 'created Assigned Plan successfully!!!');
							$this->_makeConnectionRequest($urluserdetail->id,$wkoutArray['shared_user_id'],Session::instance()->get('current_site_id'));
						}else{
							$this->session->set('success', 'Assigned Plan is already created successfully!!!');
						}
					}else{
						$wkoutArray		= $workoutModel->getShareAssignworkoutById($workid,'',$urluserdetail->id);
						if(count($wkoutArray) > 0){
							$workoutRecord  = $workoutModel->getShareworkoutById($urluserdetail->id, $workid);
							$exerciseRecord = $workoutModel->getExerciseSets('shared', $workid);
							foreach($wkoutArray as $keys => $values){
								$updateArray = array();
								$updateArray['wkout_id']    = $workid;
								$updateArray['from_wkout']  = '1';
								$updateArray['wkout_title'] = $workoutRecord['wkout_title'];
								$updateArray['wkout_color'] = $workoutRecord['wkout_color'];
								$updateArray['wkout_focus'] = $workoutRecord['wkout_focus'];
								$updateArray["modified"]    = $datevalue;
								$updateArray['created']     = $datevalue;
								$updateArray['modified_by'] = $updateArray['assigned_by'] = $urluserdetail->id;
								$updateArray['assigned_for'] = $values['shared_user_id'];
								$updateArray['wkout_group']    = $workoutRecord['wkout_group'];
								$updateArray['wkout_order']    = '1';
								$updateArray['status_id']      = '1';
								$updateArray['user_id']        = $urluserdetail->id;
								$updateArray['created_date']   = $datevalue;
								$updateArray['modified_date']  = $datevalue;
								$updateArray['assigned_date']  = Helper_Common::get_default_date($values['assign_date']);
								$workidnew = $workoutModel->addToWkoutAssignCustom($updateArray, $urluserdetail->id);
								/** activity for sender **/
								$activity_feed            = array();
								$activity_feed["user"]    = $values['shared_user_id'];
								$activity_feed["site_id"] = Session::instance()->get('current_site_id');
								$activity_feed["feed_type"] = '13';
								$activity_feed["action_type"]  = '1';
								$activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
								$activity_feed["type_id"]      = $workidnew;
								$activity_feed["json_data"]    = json_encode(array('assigned'=>$workidnew,'createdbyuser'=>$values['assigned_user_id']));
								Helper_Common::createActivityFeed($activity_feed);
								/** activity for sender -end ==== activity for receiver **/
								$activity_feed["feed_type"] = '12';
								$activity_feed["action_type"]  = '22';
								$activity_feed["user"]= $values['assigned_user_id'];
								$activity_feed["type_id"]      = $workid;
								$activity_feed["json_data"]= json_encode(array('wkoutassign'=>$workidnew,'createdbyuser'=>$values['shared_user_id']));
								Helper_Common::createActivityFeed($activity_feed);
								/** activity for receiver - end **/
								$count = 0;
								if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
									foreach ($exerciseRecord as $keys1 => $values1) {
										if (is_array($values1) && !empty($values1)) {
											$values1['goal_order'] = $count + 1;
											$values1['wkout_id'] = $workidnew;
											$workoutModel->addAssignWorkoutSetFromExistworkout($values1, $urluserdetail->id, $workidnew);
										}
									}
								}
								/*** email -automation Start ***/
								$emailNotifyArray['wkout_assign_id'] = $workidnew;
								$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
								$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
								$workoutModel->insertEmailNotify($emailNotifyArray);
								/*** email -automation End ***/
								$assignArray = array();
								$assignArray['inserted_assign_id'] = $workidnew;
								$assignArray['status'] = '2';
								$workoutModel->updateShareAssign($assignArray,$values['id']);
								$this->_makeConnectionRequest($urluserdetail->id,$wkoutArray['shared_user_id'],Session::instance()->get('current_site_id'));
							}
							$this->session->set('success', 'Assigned Plans are successfully created!!!');
						}else{
							$this->session->set('success', 'Assigned Plan is already created successfully!!!');
						}
					}
					$this->redirect($sites['slug'].'/exercise/myactionplans/'.$assignedDate);
				}else
					$this->redirect($sites['slug'].'/exercise/'.$wkoutType.'/'.$workid);
			}else
				$this->redirect($sites['slug'].'/index');
		}else
			$this->redirect($sites['slug'].'/index');
	}
} // End Welcome
