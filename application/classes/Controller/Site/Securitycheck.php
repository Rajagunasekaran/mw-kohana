<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Securitycheck extends Controller_Site_Website {
	public function _construct() {
         parent::__construct($request, $response);
    } 
	public function action_index()
	{
		$this->render();
		$userid = base64_decode(urldecode($this->request->param('param1')));
		$siteurl = $this->template->content->site_url = $this->data['siteurl'];
		$profileData = array();
		$usermodel = ORM::factory('user');
		if (!empty($userid)){
			$profileData = $usermodel->where('id', '=', trim($userid))->find();
		}else{
			$this->redirect($siteurl.'page/recover/');	
		}
		
		if(HTTP_Request::POST == $this->request->method() && isset($_POST['identify']) && base64_decode(trim($_POST['identify'])) == $userid && isset($_POST['identify_submit'])){
			if($profileData->loaded() && $profileData->security_code == trim($_POST['security_code'])){
				$this->session->get_once('recover_method');
				$this->session->set('identify',$userid);
				$this->redirect($siteurl.'page/generate/'.base64_encode($usermodel->pk()));
			}else{
				$this->session->set('flash_error_message', 'Entered code not matching!!! Please try again!!!');
			}
		}
		$this->template->content->userDetail = $profileData;
	}
	
} // End Site