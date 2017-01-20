<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Trainer extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);
	} 
	public function action_browse()
	{
		if(Helper_Common::is_trainer()) {
			$this->redirect('admin/dashboard');	
			return;
		} 
		$subscribermodel = ORM::factory('admin_subscriber');
		$usermodel = ORM::factory('admin_user');
		$this->template->title = 'Browse Trainers';
		$this->render();
		$this->template->content->editor = Ckeditor::instance();
		$authid = $this->globaluser->pk();		
		$roleid = $usermodel->user_role_load_by_name('Trainer');
		//$usersession = Session::instance()->get('auth_user');
		$siteid = Session::instance()->get('current_site_id');
		
		//$this->template->content->template_details	= $usermodel->get_user_by_condtn('*','user_access=6 and deleted=0');
		$this->template->content->template_details	= $subscribermodel->get_site_subscribers($authid, $siteid, $roleid);
		$this->template->content->roleid	= $roleid;
		$this->template->js_bottom = array('assets/js/pages/admin/trainer.js');
		$this->template->content->status_array	= $usermodel->get_table_details_by_condtn('user_status','*');
	}
	public function action_user_filter(){
		//$usersession = Session::instance()->get('auth_user');
		$siteid = Session::instance()->get('current_site_id');
		$role    = $this->request->post('role');
		$subscribers    = $this->request->post('subscribers');
		
		$subscribermodel = ORM::factory('admin_subscriber');
		$usermodel = ORM::factory('admin_user');
		
		$roleid[] = $usermodel->user_role_load_by_name('Admin');
		$roleid[] = $usermodel->user_role_load_by_name('Manager');
		$roleid[] = $usermodel->user_role_load_by_name('Trainer');
		$roleid = implode(",",$roleid);
		$data	= $subscribermodel->get_site_trainer_with_profile($siteid, $roleid);
		$uids = array();
		if(isset($data) && !empty($data) && count($data)>0){
			foreach($data as $k=> $v)
				$uids[] = $v["id"];
		}
			
		if(in_array("admin",$role)==true){
			$roles     = Helper_Common::get_role("admin");
			$admins    = Helper_Common::get_role_by_users($roles, $siteid);
			$temp = array();
			if(isset($admins) && !empty($admins) && count($admins)>0){
				foreach($admins as $key => $value) {
					if($uids && in_array($value["id"],$uids)){
						$temp[] = $value["id"];
					}
				}
			}
			$admin = $subscribermodel->get_site_trainer_without_profile($siteid, $roles, (implode(",",$temp)));
		}
		if(in_array("manager",$role)==true){
			$roles       = Helper_Common::get_role("manager");
			$managers    = Helper_Common::get_role_by_users($roles, $siteid);
			$temp = array();
			if(isset($managers) && !empty($managers) && count($managers)>0){
				foreach($managers as $key => $value) {
					if($uids && in_array($value["id"],$uids)){
						$temp[] = $value["id"];
					}
				}
			}
			$manager = $subscribermodel->get_site_trainer_without_profile($siteid, $roles, implode(",",$temp));
			
		}
      if(in_array("trainer",$role)==true){
			$roles       = Helper_Common::get_role("trainer");
			$trainers    = Helper_Common::get_role_by_users($roles, $siteid);
			$temp = array();
			if(isset($trainers) && !empty($trainers) && count($trainers)>0){
				foreach($trainers as $key => $value) {
					if($uids && in_array($value["id"],$uids)){
						$temp[] = $value["id"];
					}
				}
			}
			$trainer = $subscribermodel->get_site_trainer_without_profile($siteid, $roles, implode(",",$temp));
		}
		
		$str ='<label for="workout-name" class="control-label">User(s):</label>';
		$str .="<div style='width:100%;' >";
		$str .="<select placeholder=\"Choose Users\" id='users' style='width:100%'  multiple=\"true\"    class=\" bordernone fa-blue aamoteactions\" tabindex=\"4\">";
				if(isset($admin) && is_array($admin) && count($admin)>0) {
					$str.='<optgroup label="Admin">';
					foreach($admin as $key => $value) {
						$select = '';
						if(isset($subscribers) && in_array($value["id"],$subscribers)==true){
							$select='selected="selected"';
						}
						$str.='<option '.$select.' value="'.$value['id'].'">'.$value['user_fname'].' '.$value['user_lname'].'</option>';
					}
					$str.='</optgroup>';
				}
				if(isset($manager) && count($manager)>0 && is_array($manager)) {
					$str.='<optgroup label="Managers">';
					foreach($manager as $key => $value) {
						$select = '';
						if(isset($subscribers) && in_array($value["id"],$subscribers)==true){
							$select='selected="selected"';
						}
						$str.='<option '.$select.' value="'.$value['id'].'">'.$value['user_fname'].' '.$value['user_lname'].'</option>';
					}
					$str.='</optgroup>';
				}
				if(isset($trainer) && count($trainer)>0 && is_array($trainer)) {
					$str.='<optgroup label="Trainers">';
					foreach($trainer as $key => $value) {
						$select = '';
						if(isset($subscribers) && in_array($value["id"],$subscribers)==true){
							$select='selected="selected"';
						}
						$str.='<option '.$select.' value="'.$value['id'].'">'.$value['user_fname'].' '.$value['user_lname'].'</option>';
					}
					$str.='</optgroup>';
				}
			$str.='</select>
		</div>';
		echo $str;
		die;
	}
	public function action_trainer_profile()
	{
		//$usersession = Session::instance()->get('auth_user');
		$siteid = Session::instance()->get('current_site_id');
		if(Helper_Common::is_trainer()) {
			$this->redirect('admin/dashboard');	
			return;
		} 
		$subscribermodel = ORM::factory('admin_subscriber');
		$usermodel = ORM::factory('admin_user');
		$this->template->title = 'Trainers Profile';
		$this->render();
		$this->template->content->editor = Ckeditor::instance();
		$authid = $this->globaluser->pk();		
		$roleid[] = $usermodel->user_role_load_by_name('Admin');
		$roleid[] = $usermodel->user_role_load_by_name('Manager');
		$roleid[] = $usermodel->user_role_load_by_name('Trainer');
		$roleid = implode(",",$roleid);
		
		
		$role       = Helper_Common::get_role("admin");
      $admin    = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->admin            = $admin;
		$role       = Helper_Common::get_role("manager");
      $manager    = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->manager            = $manager;
      $role       = Helper_Common::get_role("trainer");
      $trainer    = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->trainer            = $trainer;
		
		
		
		
		//$this->template->content->template_details	= $usermodel->get_user_by_condtn('*','user_access=6 and deleted=0');
		$this->template->content->template_details	= $subscribermodel->get_site_trainer_with_profile($siteid, $roleid);
		$this->template->content->roleid	= $roleid;
		$this->template->js_bottom = array('assets/js/pages/admin/trainer.js', 'assets/js/pages/admin/trainer_profile.js');
		
		$this->template->content->status_array	= $usermodel->get_table_details_by_condtn('user_status','*');
	}
	public function action_get_site_users_without_profile()
	{
		$subscribermodel = ORM::factory('admin_subscriber');
		$usermodel = ORM::factory('admin_user');
		
		$roleid[] = $usermodel->user_role_load_by_name('Admin');
		$roleid[] = $usermodel->user_role_load_by_name('Manager');
		$roleid[] = $usermodel->user_role_load_by_name('Trainer');
		$roleid = implode(",",$roleid);
		
		$siteid = Session::instance()->get('current_site_id');
		
		$data	= $subscribermodel->get_site_trainer_with_profile($siteid, $roleid);
		$uids = array();
		foreach($data as $k=>$v){
			$uids[] = $v["id"];
		}
		if(isset($uids) && is_array($uids) && count($uids)){
			$uids = implode(",",$uids);
			$newdata	= $subscribermodel->get_site_trainer_without_profile($siteid, $roleid, $uids);
			$str = "";
			if(isset($newdata) && is_array($newdata) && count($newdata)>0){
				$str .= '<ul class="list-group" style="border:0px solid #ededed;">';
				foreach($newdata as $x=>$y){
					
					$img = URL::base().'assets/img/user_placeholder.png';
					if(isset($y["avatarid"])  &&  $y["avatarid"]!=""){
						$getImg = $usermodel->get_users_profile_image($y["avatarid"]);
						if(file_exists($getImg["img_url"])){
							$img = URL::base().$getImg["img_url"];
						}
					}
					$name = $y["user_fname"].' '.$y["user_lname"];
					$str .='<li>
						<div class="col-xs-12 usercard">
							<div onclick="goto_trainer_profile(\''.$y["id"].'\')" class="round_div">
								<div class="col-xs-3 useravatar">
									<img width="65px" src="'.$img.'" alt="">
								</div>
								<div class="col-xs-9 card_thumb-content alignleft">
									<div class="datacol tname"><strong>'.$name.'</strong></div>
								</div>
							</div>
						</div>
					</li>';
				}
				$str .= '<ul>';
			}
			echo $str; die;
		}
	}
	
	
}
