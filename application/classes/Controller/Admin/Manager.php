<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Manager extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);
	} 	
	public function action_browse()
	{
		if(Helper_Common::is_trainer() || Helper_Common::is_manager()) {
			$this->redirect('admin/dashboard');	
			return;
		} 
		$subscribermodel		= ORM::factory('admin_subscriber');
		$usermodel				= ORM::factory('admin_user');
		$this->template->title	= 'Browse Managers';
		$this->render();
		
		
		
		$this->template->content->editor = Ckeditor::instance();
		$authid = $this->globaluser->pk();		
		$roleid = 8;
		//$siteid = Session::instance()->get('current_site_id');
		$siteid = '';
		//$manager_list = $subscribermodel->get_users_by_role("8");
		$subscriberid = "";
		$site_id = "";
		$gender = "";
		if (isset($_POST) && count($_POST) > 0) {
			$siteid = '';
			$site_id = $this->request->post('site_list'); 
			$subscriber_id = $this->request->post('subscribername'); 
			$gender = $this->request->post('gender'); 
			//print_r($_POST);
			
			if($site_id !='' && count($site_id) >0){
				foreach( $site_id as $keys => $values){
					 $siteid .= $values.",";
				}	
				 $siteid = rtrim($siteid, ',');
			}else{
				$siteid = Session::instance()->get('current_site_id');
			}	
			if(count($subscriber_id) >0){
				foreach( $subscriber_id as $keys => $values){
					 $subscriberid .= $values.",";
				}	
				$subscriberid = rtrim($subscriberid, ',');
			}
			
		}	
		//echo $siteid; ///die;
		$manager_list = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid,$subscriberid,$gender);
		$site_list =  $subscribermodel->get_site_list($authid, $siteid, $roleid); // print_r($site_list); die;
		
		if(isset($manager_list) && count($manager_list)>0) {
			foreach($manager_list as $key => $value) {
				$role_status = $usermodel->get_table_details_by_condtn('user_role_status','status_id','user_id='.$value['id']);
				if(isset($role_status) && count($role_status)>0) {
					$manager_list[$key]['status'] = $role_status[0]['status_id'];
				}
			}
		}
		
		$this->template->content->site_list =  $site_list;
		$this->template->content->usertags           = ORM::factory('admin_workouts')->get_user_created_tags($authid, 2);
		$this->template->content->template_details	= $manager_list; //echo "<pre>"; print_R($manager_list); die;
		$this->template->content->roleid	= $roleid;
		$this->template->content->status_array		= $usermodel->get_table_details_by_condtn('user_status','*');
		
		$this->template->css = array('assets/plugins/tinytoggle/css/tiny-toggle.css');
		$this->template->js_bottom = array('assets/plugins/tinytoggle/js/tiny-toggle.js','assets/js/pages/admin/managers.js'); //, 'assets/js/pages/admin/setting.js');
		
	}
	
	public function action_get_mangerby_siteid(){
		$subscribermodel		= ORM::factory('admin_subscriber');
		$usermodel				= ORM::factory('admin_user');
		$authid = $this->globaluser->pk();		
		$roleid = 8;
		$siteid = '';
		if (isset($_POST) && count($_POST) > 0) {
			$site_id = $this->request->post('site_id');  //echo $site_id;die;
			if($site_id != '' && count($site_id) >0){
				
				foreach( $site_id as $keys => $values){
					 $siteid .= $values.",";
				}	
				$siteid = rtrim($siteid, ',');
			}	 
			$manager_list = $subscribermodel->get_manager_list($authid,$siteid,$roleid); //print_r($manager_list); die;
			echo json_encode($manager_list); die;
		}	
		
	}

	public function action_contact_status_update(){
		if (isset($_POST) && count($_POST) > 0) {
			$user_id = $_POST["user_id"];
			$site_id = $_POST["site_id"];
			$contact_status = (isset($_POST["contact_status"]) && $_POST["contact_status"]=='true')?1:0;
			$usermodel				= ORM::factory('admin_user');
			echo $usermodel->contact_status_update($user_id,$site_id,$contact_status);
		}
		die;
	}
	public function action_get_usersites(){
		if (isset($_POST) && count($_POST) > 0) {
			$userid = $_POST["userid"];
			$sites_list = Helper_Common::get_sites_by_user($userid);
			//echo "<pre>";
			//print_r($sites_list); die;
			$str = '';
			if(isset($sites_list) && is_array($sites_list) && count($sites_list)>0){
				foreach($sites_list  as $k=>$v){
					$val = $userid."###".$v["site_id"];
					$check='';
					if($v["contact_status"]==1){
						$check='checked="checked"';
					}
					$str .="<div class='row'>
						<div class=\"col-xs-6\">
							<label for=\"square-radio-1\">".$v["name"]."</label></li>
						</div>
						<div class=\"col-xs-6\"><input class=\"contact_status\" id=\"mybutton\" type=\"checkbox\" data-tt-size=\"big\" data-tt-palette=\"blue\" value=\"".$val."\"
						$check
						></div>
					</div>";
				}
			}
			echo $str; die;
		}
		
	}
	
	
}
