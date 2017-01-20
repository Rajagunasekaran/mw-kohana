<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Activate extends Controller_Site_Website {
	public function _construct() {
         parent::__construct($request, $response);
    } 
	public function action_index()
	{		
		$this->template->content = View::factory('pages/Site/Index');
		$this->template->content->header	= View::factory('templates/site/header')->set('data', $this->data);
		$this->template->content->footer 	= View::factory('templates/site/footer')->set('data', $this->data);
		$this->template->content->title 	= $this->data['title'];
		$activationKey = urldecode($this->request->param('param1'));
		$resendKey 	   = (isset($_GET['redo']) ? $_GET['redo'] : '');
		$siteurl	= $this->template->content->site_url	= $this->data['siteurl'];
		$usermodel = ORM::factory('user');
		if($resendKey){
			$usermodel->where('activation_code', '=', trim($activationKey))->where('last_updated', '>', date('Y-m-d H:i:s',strtotime('-1 day')))->find();
		}else{
			$usermodel->where('activation_code', '=', trim($activationKey))->where('date_created', '>', date('Y-m-d H:i:s',strtotime('-1 day')))->find();
		}
		if($usermodel->pk()){
			if($usermodel->has('roles', ORM::factory('Role', array('name' => 'login')))){
				$this->session->set('flash_activation_popup', 'Your account has already activated.');
				$this->redirect($siteurl);
			}else{
				$usermodel->add('roles', ORM::factory('Role', array('name' => 'login')));
				$usermodel->last_updated = Helper_Common::get_default_datetime();
				$usermodel->user_access  = '1';
				$usermodel->save();
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 10;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $usermodel->pk(); // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				$activity_feed["json_data"] 	= json_encode('for Subscribers');
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				/******************* Activity Feed Browser*********************/
				$activity_feed = array();
				$browserObj = new Helper_Browser();
				$browserName= $browserObj->getBrowser();
				$deviceName = $browserObj->getPlatform();
				$deviceInfo = $browserObj->__toString();
				$activity_feed["feed_type"]   	= 31; // This get from feed_type table
				$activity_feed["action_type"]  	= 10;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $usermodel->pk(); // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>$this->session->get('user_from')));
				$activity_feed["extra_info"]	= $deviceInfo;
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed Browser*********************/
				Auth::instance()->force_login($usermodel->user_email,false);
				$this->session->set('newly_loggedin',$usermodel->pk()) ;
				$this->session->set('flash_activation_popup', 'Your account has been activated.');
				$this->redirect($siteurl);
			}
		}else{
			$this->session->set('flash_error_message', 'Your activation key is invalid, your account is deleted from our database.');
			$this->redirect($siteurl.'page/recover/');
		}
	}
	
	
} // End Site
