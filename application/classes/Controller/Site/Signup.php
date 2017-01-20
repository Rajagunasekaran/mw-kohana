<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Signup extends Controller_Site_Website {
	public function _construct() {
         parent::__construct($request, $response);
    } 
	public function action_index()
	{		
		$this->render();			
		$this->template->title = 'Sign up';
		$usermodel = ORM::factory('user');
		$settings_model = ORM::factory('settings');
		$smtpmodel = ORM::factory('admin_smtp');
		$siteurl   = $this->template->content->site_url	= $this->data['siteurl'];
		$slug_id = Request::current()->param('site_id');
		if ($slug_id != '' && !$this->session->get('current_site_id')) {
			 $sites = $this->_get_sites($slug_id);
			 if (!$sites) {
				$this->redirect();
			 }
			 $site_id = $sites["id"];
			 // setting dynamic site id for users dated on 19th april 2016
			 $this->session->set('current_site_id', $site_id);
			 $this->session->set('current_site_name', $sites['name']);
			 $this->session->set('current_site_slug', $sites['slug']);
		}
		if (HTTP_Request::POST == $this->request->method()){
			$signupPopup = isset($_POST['signup_from']) ? true : false;
			if(isset($_POST['signup'])){
				$this->session->set('user_fname',$this->request->post('user_fname'));
				$this->session->set('user_lname',$this->request->post('user_lname'));
				$this->session->set('user_email',$this->request->post('user_email'));
				$this->session->set('user_reenter_email',$this->request->post('user_reenter_email'));
				$this->session->set('birthday_month',$this->request->post('birthday_month'));
				$this->session->set('birthday_day',$this->request->post('birthday_day'));
				$this->session->set('birthday_year',$this->request->post('birthday_year'));
				if(!Helper_Common::isCookieEnable()) {
					if($signupPopup)
						$this->redirect($siteurl.'#signupPopup?cookie=0&form=signup');
					else
						$this->redirect($siteurl.'page/signup?cookie=0&form=signup');
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
					$user->site_id			=	$this->session->get('current_site_id');
					$user->save();
					if($user->id)
					{
							
							$siteid = Session::instance()->get('current_site_id');
							$check_site = DB::select('*')->from('user_sites')->where('site_id', '=', $siteid)->where('user_id', '=', $user->id)->execute()->as_array();
										//->where('status', '!=', '4')
							if(isset($check_site) && count($check_site)==0 )	{
								$id = DB::insert('user_sites', array('user_id','site_id'))->values(array($user->id,$siteid))->execute();
							}
					}
					$preference_Defaults 	= $settings_model->get_site_settings();
					if(isset($preference_Defaults[0]) && count($preference_Defaults[0]) > 0)
						$settings_model->insertUserSettings($preference_Defaults[0],$user->pk());
					/******************* Activity Feed *********************/
					$activity_feed = array();
					$activity_feed["feed_type"]   	= 10; // This get from feed_type table
					$activity_feed["action_type"]  	= 17;  // This get from action_type table  
					$activity_feed["type_id"]    	= $activity_feed["user"]  = $user->pk(); // user id
					$activity_feed["site_id"]  		= $this->session->get('current_site_id');
					Helper_Common::createActivityFeed($activity_feed);
					/******************* Activity Feed *********************/
					// Reset values so form is not sticky
					$_POST = array();
					$this->session->set('flash_success_popup', 'A registration activation link has been emailed to the email address you provided. This activation code will expire in 24 hours.');
					$useractivation = '<a href="'.URL::site(NULL, 'http').substr($siteurl, 1).'page/activate/'.$user->activation_code.'" target="_blank" title="Click here to activate your account" style="color: #1b9af7;">Click here to activate your account</a>';
					if(!empty($user->user_email)){
						$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register','site_id' => $this->session->get('current_site_id')));
						if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
							$templateArray['body'] = str_replace(array('[FirstName]','[ActivationLink]'), array(ucfirst(strtolower($user->user_fname)), $useractivation), $templateArray['body']);
							$messageArray = array('subject'	=> $templateArray['subject'],
								'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
								'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
								'to'		=> $user->user_email,
								'replyto'=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
								'toname'	=> ucfirst(strtolower($user->user_fname)).' '.ucfirst(strtolower($user->user_lname)),
								'body'	=> $smtpmodel->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
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
							Email::sendBysmtp($emailMailer,$messageArray);
						}
					}
					if($signupPopup)
						$this->redirect($siteurl.'#signupPopup');
					else
						$this->redirect($siteurl.'page/signup');
				} else {
					//validation failed, get errors
					$this->data['error_messages'] = $validator->errors('errors/en');
				}
				if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
					$key_array = array_keys($this->data['error_messages']);
					$key_str = implode(',',$key_array);
					if($key_str=='user_email') {
						foreach($this->data['error_messages'] as $keys => $value){
							if($value=='Email is unconfirmed.') {
								$this->session->set('resendConfirmation','1');
								$this->session->set('user_email',$this->request->post('user_email'));
								$this->session->set('activation_code',md5(microtime().rand()));
								$this->session->set('date_created',$currentDate);
								$this->session->set('user_fname',$this->request->post('user_fname'));
								$this->session->set('user_lname',$this->request->post('user_lname'));
								$this->session->set($keys.'_error',$value);
							} else if(($value=='Phone number is unconfirmed.') || ($value=='Email / Phone number is confirmed.') || ($value=='Email is confirmed.') )  {
								$this->session->set('user_email_error','Email already exists.');
							} else {
								$this->session->set($keys.'_error',$value);
							}
						}
					} else {
						foreach($this->data['error_messages'] as $keys => $value){
							$this->session->set($keys.'_error',$value);
						}
					}
					if($signupPopup)
						$this->redirect($siteurl.'#signupPopup');
					else
						$this->redirect($siteurl.'page/signup');
				}
			} else if(isset($_POST['resend'])) {
				$user = array();
				$currentDate	= Helper_Common::get_default_datetime();	
				$user['user_email']		= $this->request->post('user_email');
				$user['activation_code']= md5(microtime().rand());
				$user['date_created'] 	= $currentDate;
				$user['user_fname']		= $this->request->post('user_fname');
				$user['user_lname']		= $this->request->post('user_lname');
				$useractivation = '<a href="'.URL::site(NULL, 'http').'index/activate/'.$user['activation_code'].'" target="_blank" title="Click here to activate your account" style="color: #1b9af7;">Click here to activate your account</a>';
				if(!empty($user['user_email'])){
					DB::update('users')->set(array('activation_code' => $user['activation_code'],'last_updated' => $user['date_created']))->where('user_email', '=', $user['user_email'])->execute();

					$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register','site_id' => $this->session->get('current_site_id')));

					$templateArray['body'] = str_replace(array('[FirstName]','[ActivationLink]'), array(ucfirst(strtolower($user['user_fname'])), $useractivation), $templateArray['body']);

					$messageArray = array('subject'	=> $templateArray['subject'],
						'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
						'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
						'to'		=> $user['user_email'],
						'replyto'=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
						'toname'	=> ucfirst(strtolower($user['user_fname'])).' '.ucfirst(strtolower($user['user_lname'])),
						'body'	=> $smtpmodel->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
						'type'	=> 'text/html');

					if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
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
					Email::sendBysmtp($emailMailer,$messageArray);
					$this->session->set('flash_success_popup','Confirmation email has been resent.');
				}
				if($signupPopup)
					$this->redirect($siteurl.'#signupPopup');
				else
					$this->redirect($siteurl.'page/signup');	
			}
		}
	}
	
	
} // End Site
