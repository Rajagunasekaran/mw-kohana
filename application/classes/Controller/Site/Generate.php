<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Generate extends Controller_Site_Website {
	public function _construct() {
         parent::__construct($request, $response);
    } 
	public function action_index()
    {
		$this->render();
		$userid = base64_decode(urldecode($this->request->param('param1')));
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		$siteurl	= $this->template->content->site_url	= $this->data['siteurl'];
		$site_title	= $this->template->content->site_title	= $this->data['title'];
		if (!empty($userid)){
			$usermodel->where('id', '=', trim($userid))->find();
		}else{
			$this->redirect($siteurl.'page/recover/');	
		}
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
		if(HTTP_Request::POST == $this->request->method() && isset($_POST['new_pass']) && base64_decode(trim($_POST['identify'])) == $userid && isset($_POST['generate_submit'])){
			$validator = $usermodel->validate_user_password(arr::extract($_POST,array('new_pass','conf_pass')));
			if ($validator->check()) {
				$usermodel->set('password',trim($_POST['new_pass']));
				$usermodel->set('last_updated',Helper_Common::get_default_datetime());
				$usermodel->update();
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 14;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $usermodel->pk(); // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				$siteDetails['link']			=  URL::base(true);
				$siteDetails['title']			=  'My Workouts';
				if(!empty($usermodel->user_email)){
					$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Password reset','site_id' => $this->data['siteidpk']));
					if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
						$templateArray['body'] = str_replace(array('[FirstName]'),array(ucfirst(strtolower($usermodel->user_fname))),$templateArray['body']);
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
					if(isset($messageArray) && is_array($messageArray)) {
						Email::sendBysmtp($emailMailer,$messageArray);
					}
				}else{
				
				}
				$this->session->set('flash_success_popup', 'Your account password was successfully reset.');
				$this->redirect($siteurl.'page/generate/'.base64_encode($userid));
			} else {
				//validation failed, get errors
				$this->data['error_messages'] = $validator->errors('errors/en');
			}
			if(isset($this->data['error_messages']) && !empty($this->data['error_messages'])){
				foreach($this->data['error_messages'] as $keys => $value){
					$this->session->set($keys.'_error',$value);
				}
				$this->redirect($siteurl.'page/generate/'.base64_encode($userid));	
			}		
		}
		$_POST = array();
		$this->template->content->userid =base64_encode($usermodel->pk());
    }
	public function _get_sites($slug_id)
	{
		  $hompage = ORM::factory('homepage');
		  $result  = $hompage->get_sites($slug_id);
		  return $result;
	}
} // End Site