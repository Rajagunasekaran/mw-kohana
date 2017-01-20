<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Recover extends Controller_Site_Website {
	public function _construct() {
         parent::__construct($request, $response);
    } 
	public function action_index()
	{
		$this->render();
		$profileData	= array();
		$userid			= $this->template->content->userid		= base64_decode(urldecode($this->request->param('param1')));
		$siteurl		= $this->template->content->site_url	= $this->data['siteurl'];
		$site_slug 		= $this->session->get('current_site_slug');
		$passwordRedireect = (!empty($site_slug) ? str_replace($site_slug.'/','',URL::base(True)) : URL::base(True)).(!empty($site_slug) ? 'site/'.$site_slug.'/' : '');
		$usermodel		= ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		if (HTTP_Request::POST == $this->request->method() && empty($userid)){
			if(isset($_POST['identify_search']) && isset($_POST['identify_email']) && !empty($_POST['identify_email'])){
				$profileData = $usermodel->where('user_email', '=', trim($_POST['identify_email']))->or_where('user_mobile', '=', trim($_POST['identify_email']))->find();
				if($profileData->pk())
					$this->redirect($siteurl.'page/recover/'.base64_encode($profileData->pk()));
				else{
					$this->session->set('flash_error_message', 'Entered Email is Invalid.');
					$this->redirect($siteurl.'page/recover/');
				}
			}
		}elseif(!empty($userid) && HTTP_Request::POST == $this->request->method()){
			if(isset($_POST['reset_action'])){
				$usermodel->where('id', '=', trim($userid))->find();
				if($usermodel->pk()){
					$usermodel->security_code = md5(microtime().rand());
					$security_url_link = '<a href="'.$passwordRedireect.'page/generate/'.base64_encode($usermodel->pk()).'" title="Click to Reset Password" target="_blank" style="color: #1b9af7;">Click to Reset Password</a>';
					$usermodel->save();
					$this->session->set('recover_method',$_POST['recover_method']);
					if(isset($_POST['recover_method']) && $_POST['recover_method'] =='send_email'){
						$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Forgot Password','site_id' => $this->data['siteidpk']));
						if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
							$templateArray['body'] = str_replace(array('[FirstName]','[SecureCode]'),array(ucfirst(strtolower($usermodel->user_fname)),$security_url_link),$templateArray['body']);
							$messageArray = array('subject'	=> $templateArray['subject'],
													  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
													  'to'		=> $usermodel->user_email,
													  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'toname'	=> ucfirst(strtolower($usermodel->user_fname)).' '.ucfirst(strtolower($usermodel->user_lname)),
													  'body'	=> ORM::factory('admin_smtp')->merge_keywords($templateArray['body'],$this->session->get('current_site_id')),
													  'type'	=> 'text/html');
												  
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
						
						if( is_array($messageArray)) {
							
							Email::sendBysmtp($emailMailer,$messageArray); 
						}
					}else{
						
					}
					/******************* Activity Feed *********************/
					$activity_feed = array();
					$activity_feed["feed_type"]   	= 10; // This get from feed_type table
					$activity_feed["action_type"]  	= 11;  // This get from action_type table  
					$activity_feed["type_id"]    	= $activity_feed["user"]  = $userid; // user id
					$activity_feed["site_id"]  		= $this->session->get('current_site_id');
					Helper_Common::createActivityFeed($activity_feed);
					/******************* Activity Feed *********************/
					//$this->redirect($siteurl.'page/securitycheck/'.base64_encode($usermodel->pk()));
					$this->session->set('flash_pwdresetmail_message', '1');
					$this->redirect($siteurl.'page/recover/');
				}else{
					$this->session->set('flash_error_message', 'Entered Email is Invalid.');
					$this->redirect($siteurl.'page/recover/');
				}
			}
		}elseif(!empty($userid)){
			$profileData = $usermodel->where('id', '=', trim($userid))->find();
		}
		$this->template->content->userDetail = $profileData;
	}
	
} // End Site