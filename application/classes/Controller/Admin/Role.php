<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Role extends Controller_Admin_Website {

	public function before()
	{
		parent::before();
		$user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
		Session::instance()->set('user_from',$user_from);
	}
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}
	/*
	public function action_create()
	{
		$this->render();
		$adminworkoutmodel	= ORM::factory('admin_workouts');
		$this->template->content->userid = $this->globaluser->pk();
		
		$this->template->content->focusRecord 	= $adminworkoutmodel->getAllFocus();
		$this->template->content->colorsRecord	= $adminworkoutmodel->getColors();
		
		$this->template->content->unit_rate       = $adminworkoutmodel->getunitsbytable('set_rate');
		$this->template->content->unit_innerdrive = $adminworkoutmodel->getInnerDrive();
		$this->template->content->unit_angles     = $adminworkoutmodel->getunitsbytable('set_angle');
		$this->template->content->resistance      = $adminworkoutmodel->getunitsbytable('set_resist');
		$this->template->content->distance        = $adminworkoutmodel->getunitsbytable('set_dist');
		
		
		
      $this->template->title = 'Create Workout Record';
		
	}
	*/
	
	public function action_access_settings()
	{
		$this->template->title = 'Role Access Level Control';
		$this->render();
		$siteModel = ORM::factory('admin_sites');
		$role_access_model = ORM::factory('admin_roleaccess');
		/*$allSites = $siteModel->getAllSites();
		$getAllRoleTypes = $role_access_model->getRoleAccessTypeByContn('id');
		if(isset($allSites) && count($allSites)>0) {
			foreach($allSites as $key => $value) {
				$siteId = $value['id'];
				if(isset($getAllRoleTypes) && count($getAllRoleTypes)>0) {
					foreach($getAllRoleTypes as $roleKey => $roleValue) {
						$roleId = $roleValue['id'];
						$roleInputArray['role_id'] = 8;
						$roleInputArray['site_id'] = $siteId;
						$roleInputArray['access_type_id'] = $roleId;
						$role_access_model->insertRoleAcces($roleInputArray);
					}
				}
			}
		}*/
		
		$this->template->css = array('assets/plugins/tinytoggle/css/tiny-toggle.css');
		$this->template->js_bottom = array('assets/plugins/tinytoggle/js/tiny-toggle.js', 'assets/js/tinytoggle.js');
		$this->template->content->roletype = $role_access_model->getAllRoleAccess();
		if(Helper_Common::is_admin())
			$condtn = 'site_id = '.$this->session->get('current_site_id');
		else
			$condtn = ' role_id in (7,6) AND site_id = '.$this->session->get('current_site_id');
		$this->template->content->roleaccess = $role_access_model->getRoleAccessByContn('*',$condtn);
	}
    
	public function action_edit()
	{
        $userid = $this->request->param('id');
        if(!is_numeric($userid) || $userid == "" || $userid == "0"){
            $this->redirect("admin/subscriber/browse");
        }
        
        $adminuser 		 = ORM::factory('admin_user');
        $this->template->title 	= 'Edit User';
        $this->render();
        
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			$object = Validation::factory($post);
			$object->rule('user_fname', 'not_empty')
					->rule('user_lname', 'not_empty')
					->rule('user_email', 'not_empty');
            
			if ($object->check()) { //Validate required fields                   
                    
                $user = ORM::factory('user')->where('id', '=', $userid)->find();

                $userCurrentRoleIds =  $adminuser->getUsersRoleNamesByUserId($userid);

                if(!in_array($this->request->post('user_level'),$userCurrentRoleIds)){
                    $hiddenUserRole = $adminuser->getUserRoleByName($this->request->post('hidden-userrole'));
                    $user->remove('roles', ORM::factory('Role', array('name' => $hiddenUserRole)));
                    $user->add('roles', ORM::factory('Role', array('name' => $this->request->post('user_level'))));                    
                }
                $user->user_fname 		=	$this->request->post('user_fname');
                $user->user_lname 		=	$this->request->post('user_lname');
                $user->user_gender		=	$this->request->post('user_gender');
                /*if($this->request->post('password') != ""){
                    $user->password 		=	$this->hash($this->request->post('password'));
                }*/
                $user->ip_address 		=	$_SERVER['REMOTE_ADDR'];
                $user->activation_code	=	md5(microtime().rand());
                $user->user_dob 		=	date("Y-m-d",strtotime($this->request->post('birthday_year').'-'.$this->request->post('birthday_month').'-'.$this->request->post('birthday_day')));
                $user->save();
                $this->template->content->success = "User Updated Successfully!!!";

                //Reset values so form is not sticky
                $_POST = array();
                
			} else {
				$errors = $object->errors('user');
				$this->template->content->errors = $errors;
			}            
		}
        
        $userDetails = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $this->template->content->userDetails = $userDetails;
        
	}
    public function action_browse()
	{
		//echo $this->session->get('auth_user');echo "<br>";echo $this->session->get('user_email');echo "<br>";print_r($this->session); exit;
		$usermodel = ORM::factory('admin_subscriber');
		$adminworkoutmodel	= ORM::factory('admin_workouts');
		
		$adminusermodel = ORM::factory('admin_user');
		
		$this->template->title = 'Browse Workout Records';
		$this->render();
		
		
		
		if(isset($_POST) && count($_POST)>0) {
			$workoutModel 		   = ORM::factory('workouts');
			$activityModel 		   = ORM::factory('activityfeed');
			$method = $this->request->post('f_method');
			$parentFolderArray     = array();
			$datevalue 			   = Helper_Common::get_default_datetime();
			$parentFolderId		   = urldecode($this->request->param('id'));
			
			if(!empty($method)  && trim($method) == 'add_workout'){
				$inputArray['wkout_group']		= '0';
				$inputArray['wkout_title']		= $this->request->post('wkout_title');
				$inputArray['wkout_color']		= $this->request->post('wrkoutcolor');
				$inputArray['wkout_order']		= '1';
				$inputArray['user_id']			= $this->globaluser->pk();
				$inputArray['status_id']		= '1';
				$inputArray['access_id']		= $this->globaluser->user_access;
				$inputArray['wkout_focus']		= $this->request->post('wkout_focus');
				$inputArray['wkout_poa']		= '0';
				$inputArray['wkout_poa_time']	= '0';
				$inputArray['parent_folder_id']	= $parentFolderId;
				$inputArray['created_date']		= $datevalue;
				$inputArray['modified_date']	= $datevalue;
				$_POST['wkout_id'] =  $workoutModel->insertWorkoutDetails($inputArray);
				foreach($_POST['exercise_title_new'] as $keys => $values){
					if(!empty($values) && trim($values) !=''){
						$res=$workoutModel->addWorkoutSetFromworkout($_POST, $keys , $this->globaluser->pk());
					}
				}
				$activity_feed["user"]     = $this->globaluser->pk();
				$activity_feed["site_id"]  = $this->current_site_id;
				$activity_feed["created_date"]  = $datevalue;
				$activity_feed["modified_date"]  = $datevalue;	
				$activity_feed["feed_type"] 	= '2';
				$activity_feed["action_type"] 	= '1';
				$activity_feed["type_id"]     	= $_POST['wkout_id'];
				$activity_result = $activityModel->insert('activity_feed',$activity_feed);
				
				//print_r($activity_result); exit;
				
				$this->session->set('success','Successfully <b>'.$inputArray['wkout_title'].'</b> Workout Record was added!!!');
			}
			//echo "<pre>";print_r($_POST);exit;
		}
		
		
		$userid = $this->globaluser->pk(); //Current Loggedin users			
		$result = $adminworkoutmodel->get_user_created_tags($userid,2);
		$usertags = $adminworkoutmodel->get_user_tags($userid);
		$this->template->content->usertags	= $result;
		//echo "<pre>";print_r($usertags);print_r($result);exit;
		
		
		$roleid = $adminusermodel->user_role_load_by_name('Register');
		$usersession = Session::instance()->get('auth_user');
		$siteid = $usersession->site_id;
		
		$this->template->content->focusRecord 	= $adminworkoutmodel->getAllFocus();
		
		
		$this->template->content->workout_details = $adminworkoutmodel->getuserWorkoutDetails('');
		//$this->template->content->subscriber_details	= $usermodel->get_subscribers_only();
		/*
		if(Helper_Common::is_trainer()) {
			$this->template->content->subscriber_details	= $usermodel->get_subscribers_only();
		} else {
			$this->template->content->subscriber_details	= $usermodel->get_users_by_role("6");
		}
		*/
		
		$role =  Helper_Common::get_role("manager");
		$this->template->content->manager =  Helper_Common::get_role_by_users($role,$siteid);
		
		$role =  Helper_Common::get_role("trainer");
		$this->template->content->trainer =  Helper_Common::get_role_by_users($role,$siteid);
		
		$role =  Helper_Common::get_role("register");
		$this->template->content->subscriber_details =  Helper_Common::get_role_by_users($role,$siteid);
		
		//echo "<pre>";print_r($this->template->content->subscriber_details); exit;
		
	}
	
	 public function action_sample()
	{
		//echo $this->session->get('auth_user');echo "<br>";echo $this->session->get('user_email');echo "<br>";print_r($this->session); exit;
		
		$adminworkoutmodel	= ORM::factory('admin_workouts');
		$usermodel = ORM::factory('admin_subscriber');
		$adminusermodel = ORM::factory('admin_user');
		
		$this->template->title = 'Sample Workout Records';
		$this->render();
		
		
		if(isset($_POST) && count($_POST)>0) {
			//echo $this->current_site_id;exit; 
			$workoutModel 		   = ORM::factory('admin_workouts');
			$activityModel 		   = ORM::factory('activityfeed');
			$method = $this->request->post('f_method');
			$parentFolderArray     = array();
			$datevalue 			   = Helper_Common::get_default_datetime();
			$parentFolderId		   = urldecode($this->request->param('id'));
			
			if(!empty($method)  && trim($method) == 'add_workout'){
				$inputArray['wkout_group']		= '0';
				$inputArray['wkout_title']		= $this->request->post('wkout_title');
				$inputArray['wkout_color']		= $this->request->post('wrkoutcolor');
				$inputArray['wkout_order']		= '1';
				$inputArray['user_id']			= $this->globaluser->pk();
				$inputArray['user_id']			= '';
				$inputArray['status_id']		= '1';
				$inputArray["site_id"]        = $this->current_site_id;
				$inputArray['access_id']		= $this->globaluser->user_access;
				$inputArray['wkout_focus']		= $this->request->post('wkout_focus');
				$inputArray['wkout_poa']		= '0';
				$inputArray['wkout_poa_time']	= '0';
				$inputArray['parent_folder_id']	= $parentFolderId;
				$inputArray['created_date']		= $datevalue;
				$inputArray['modified_date']	= $datevalue;
				$_POST['wkout_sample_id'] =  $workoutModel->insertSampleWorkoutDetails($inputArray);
				foreach($_POST['exercise_title_new'] as $keys => $values){
					if(!empty($values) && trim($values) !=''){
						$res=$workoutModel->addSampleWorkoutSetFromworkout($_POST, $keys , $this->globaluser->pk());
					}
				}
				$activity_feed["user"]     = $this->globaluser->pk();
				$activity_feed["site_id"]  = $this->current_site_id;
				$activity_feed["created_date"]  = $datevalue;
				$activity_feed["modified_date"]  = $datevalue;	
				$activity_feed["feed_type"] 	= '2';
				$activity_feed["action_type"] 	= '1';
				$activity_feed["type_id"]     	= $_POST['wkout_sample_id'];
				//$activity_result = $activityModel->insert('activity_feed',$activity_feed);
				
				//print_r($activity_result); exit;
				
				$this->session->set('success','Successfully <b>'.$inputArray['wkout_title'].'</b> Workout Record was added!!!');
			}
			//echo "<pre>";print_r($_POST);exit;
		}
		
		
		$userid = $this->globaluser->pk(); //Current Loggedin users			
		$result = $adminworkoutmodel->get_user_created_tags($userid,2);
		$usertags = $adminworkoutmodel->get_user_tags($userid);
		$this->template->content->usertags	= $result;
		//echo "<pre>";print_r($usertags);print_r($result);exit;
		
		
		$roleid = $adminusermodel->user_role_load_by_name('Register');
		$usersession = Session::instance()->get('auth_user');
		$siteid = $usersession->site_id;
		
		$this->template->content->focusRecord 	= $adminworkoutmodel->getAllFocus();
		
		if(!Helper_Common::is_admin()){ 
			$this->template->content->workout_details = $adminworkoutmodel->getSampleWorkoutDetails('','',$this->current_site_id);
		}else{
			$this->template->content->workout_details = $adminworkoutmodel->getSampleWorkoutDetails('');
		}
		
		//$this->template->content->subscriber_details	= $usermodel->get_subscribers_only();
		if(Helper_Common::is_trainer()) {
			$this->template->content->subscriber_details	= $usermodel->get_subscribers_only();
		} else {
			$this->template->content->subscriber_details	= $usermodel->get_users_by_role("6");
		}
		
		//echo "<pre>";print_r($this->template->content->subscriber_details); exit;
		
	}	
	
	public function action_json()
	{		
		$this->auto_render = FALSE;
		$data["id"] = 2;
		$data["title"] = "My title 1";
		$s[] = $data;
		$data["id"] = 2;
		$data["title"] = "Your title 2";
		$s[] = $data;
		echo json_encode($s);
		
	}	
	public function action_getAdvanceSearchExerciseRecords() {
		if ($this->request->is_ajax()) $this->auto_render = FALSE;
		if(isset($_POST) && count($_POST)>0) {
			$exercisemodel	= ORM::factory('admin_exercise');
			$this->template->content->template_details	= $exercisemodel->get_exerciseRecordGallery($_POST);
		}
	}
	public function action_getwkoutnames(){
		$shareworkoutmodel	= ORM::factory('admin_shareworkout');
		if(isset($_POST) && count($_POST)>0) {
			$str = implode(',',$_POST["data"]);
			if($str){
				$res	= $shareworkoutmodel->getwkoutname($str);
				if($res){
					$i=0;$temp=array();
					foreach($res as $k=>$v){
						$temp[$i]['id'] = $v['wkout_id'];
						$temp[$i]['text'] = $v['wkout_title'];
						$i++;
					}
					echo json_encode($temp);
				}
			}
			echo false;
		}
		echo false;
		exit;
	}
	
	
	
	public function action_gettaguser(){
		$workoutmodel	= ORM::factory('admin_workouts');
		if(isset($_POST) && count($_POST)>0 && isset($_POST["tag_id"])) {
			$tags = implode(",",$_POST["tag_id"]);
			$tagsuser = $workoutmodel->get_tags_user($tags);
			echo ($tagsuser)?json_encode($tagsuser):false;
		}
		echo false;
		exit;
	}
	
	public function action_removeworkout(){
		$workoutmodel	= ORM::factory('admin_workouts');
		if(isset($_POST) && count($_POST)>0) {
			$parent_folder_id =1;
			$wkout_id = $this->request->post('wkout_id'); 
			$res = $workoutmodel->deleteWorkoutDetails($parent_folder_id, $wkout_id);
			if(isset($res)){
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   = 2;
				$activity_feed["action_type"]  = 2;
				$activity_feed["type_id"]    =  $wkout_id;
				$activity_feed["site_id"]  = $this->current_site_id;
				$activity_feed["user"]     = $this->globaluser->pk();
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
			}
			echo ($res)?true:false;
		}
		echo false;
		exit;
	}
	
	public function action_saveshare(){
		$shareworkoutmodel	= ORM::factory('admin_shareworkout');
		if(isset($_POST) && count($_POST)>0) {
			$array['message'] = $_POST["message"];
			$res	= $shareworkoutmodel->insert('share_wkout',$array);
			$shareid = $res;
			
			$_POST["subscriber_id"] = is_string($_POST["subscriber_id"])?explode(",",$_POST["subscriber_id"]):$_POST["subscriber_id"];
			//print_r($_POST["subscriber_id"]);
			if($res){
				$activityjson = array();
				
				$currentuser  = $shareworkoutmodel->getuserdetails($this->globaluser->pk());
				$currentuser = $currentuser[0];
				
				$sites = $shareworkoutmodel->getsitedetails($currentuser["site_id"]);
				$sites = ($sites)?$sites[0]:'';
				
				foreach($_POST["wkout_id"] as $k=>$v){
					foreach($_POST["subscriber_id"] as $x=>$y){
						$temp = array();
						$temp["share_wkout_id"] = $shareid;
						$temp["wkout_id"]       = $v;
						$temp["subscriber_id"]  = $y;
						$temp["shared_by"]      = $this->globaluser->pk();
						$temp["read_status"]    = '1';
						$temp["created_date"]   = Helper_Common::get_default_datetime();
						$str = $temp; unset($str["share_wkout_id"]);
						
						$res = $shareworkoutmodel->getdata('share_wkout_mapping',$str);
						if(count($res)==0){
							$result	= $shareworkoutmodel->insert('share_wkout_mapping',$temp);
							$datetime = Helper_Common::get_default_datetime();
							if($result){
								
									$activityjson["wkout"][] = $v;
									$activityjson["subscriber"][] = $y;
								
								
								$user  = $shareworkoutmodel->getuserdetails($y);
								$user  = $user[0];
								$smtpmodel = ORM::factory('admin_smtp');
								$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Share'));
								$messageArray = array('subject'	=> str_replace(
																						array('[trainer_name]'),
																						array(ucfirst(strtolower($currentuser['user_fname']))),
																						$templateArray['subject']
																				),
													  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
													  'to'		=> $user["user_email"],
													  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
													  'toname'	=> ucfirst(strtolower($user['user_fname'])).' '.ucfirst(strtolower($user['user_lname'])),
													  'body'	   => str_replace(
																			array('[trainer_name]',
																					'[site_title]',
																					'[share_workout_plan_link]'
																			),
																			array(ucfirst(strtolower($currentuser['user_fname'])),
																					($sites)?$sites["name"]:'',
																					URL::site(NULL, 'http').'index/exercise/workoutrecord/'.$v
																			),
																			$templateArray['body']
																		),
													  //'body'	=> $templateArray['body'],
													  'type'	=> 'text/html');
								//echo $y;print_r($user);
								//echo $messageArray["body"]; exit;
								if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && false){
									$hostAddress = explode("://",$templateArray['smtp_host']);
									$emailMailer = Email::dynamicMailer('smtp',array
																						(
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
							
						}
					}
				}
				/******************* Activity Feed *********************/
				if($activityjson){
					$activityjson["subscriber"] = array_unique($activityjson["subscriber"]);
					$activityjson["wkout"]      = array_unique($activityjson["wkout"]);
					$activity_feed = array();
					$activity_feed["feed_type"] 		= 2; // This get from feed_type table
					$activity_feed["action_type"] 	= 7; // This get from action_type table	 
					//$activity_feed["created_date"] 	= $datetime;
					//$activity_feed["modified_date"] 	= $datetime;
					$activity_feed["user"] 				= $this->globaluser->pk();
					$activity_feed["site_id"] 				= $this->current_site_id;
					$activity_feed["json_data"] 	   = json_encode($activityjson["subscriber"]);
					foreach($activityjson["wkout"] as $sub=>$wkout){
						$activity_feed["type_id"] 			= $wkout; // Workout Id
						//$activity_result	= $shareworkoutmodel->insert('activity_feed',$activity_feed);
						Helper_Common::createActivityFeed($activity_feed);
					}
					/******************* Activity Feed *********************/
					/*
					if($_POST["param"]=="workout"){
						$activity_feed["type_id"] 			= $v; // Workout Id
						$activity_result	= $shareworkoutmodel->insert('activity_feed',$activity_feed);
					}elseif($_POST["param"]=="subscriber"){
						foreach($activityjson["wkout"] as $sub=>$wkout){
							$activity_feed["type_id"] 			= $wkout; // Workout Id
							$activity_result	= $shareworkoutmodel->insert('activity_feed',$activity_feed);
						}
					}
					*/
				}
			}
		}
		echo true;
		exit;
	}
	
	public function action_filtersubscribers(){
		//print_r($_POST);
		$shareworkoutmodel	= ORM::factory('admin_shareworkout');
		if (HTTP_Request::POST == $this->request->method()){
			$subscriberid  = $this->request->post('subscriberid');
			if($subscriberid){
				$subscriberid = implode(",",array_filter($subscriberid));
			}
			$gender 			= $this->request->post('gender');
			$agerange      = $this->request->post('setagerange');
			$from ='';$to='';
			if($agerange){
				$agerange = explode("-",$agerange);
				$from     = $agerange[0];
				$to       = $agerange[1];
			}
			//echo implode(",",$subscriberid)."---".$gender.'---'.$from."----".$to;
			$res = $shareworkoutmodel->filtersubscriber($subscriberid,$gender,$from,$to);
			//print_r($res);
			echo json_encode($res);
		}
		echo false;
		exit;
	}
	
	public function action_copyworkout(){
		$workoutsmodel	= ORM::factory('admin_workouts');
		if (HTTP_Request::POST == $this->request->method()){
			//print_r($this->globaluser->pk());
			$this->globaluser = Auth::instance()->get_user();
			$method = $this->request->post('f_method');
			$workid = $this->request->post('workout_id');
			$workoutsmodel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), 0, $workid , $method);
		}
		//print_r($_POST);
		//$workoutsmodel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), 0, $workid , 'down');
		//$this->session->set('success','Successfully copied!!!');
		//exit;
		$this->redirect('admin/workout/browse');
		
	}
	
	public function action_previewworkout(){
		
		$wkoutid			= Arr::get($_GET, 'wkoutid');
		$user       = Auth::instance()->get_user();
		$method			= Arr::get($_GET, 'method');
		//$assignId		= Arr::get($_GET, 'assignid');
		$type		= Arr::get($_GET, 'type'); // ''=>wkout , assign => assign_wkout , log => log_wkout
		$this->globaluser = Auth::instance()->get_user();
		//$workoutModel 	= ORM::factory('workouts');
		$adminm_workoutsmodel	= ORM::factory('admin_workouts');
		if($wkoutid){
			$focusRecord 	= $adminm_workoutsmodel->getAllFocus();
			$workoutRecord	= $adminm_workoutsmodel->getworkoutById('',$wkoutid);
			$exerciseRecord = $adminm_workoutsmodel->getExerciseSet($wkoutid);
			$data = array();
			//$data["workoutRecord"]  = ($workoutRecord)?$workoutRecord:'';
			//$data["exerciseRecord"] = ($exerciseRecord)?$exerciseRecord:'';
			//echo "<pre>";print_r($workoutRecord);print_r($exerciseRecord);exit;
			
			$data['wkout']["color_title"] = $workoutRecord["color_title"];
			$data['wkout']["wkout_title"] = $workoutRecord["wkout_title"];
			foreach($focusRecord as $keys => $values){
				if($values['focus_id'] == $workoutRecord['wkout_focus'])
					$data["wkout"]["overallfocus"] = ucfirst($values['focus_opt_title']);
			}
			
			$temp = array();
			if(isset($exerciseRecord) && count($exerciseRecord)>0){
				$i=0;
				foreach($exerciseRecord as $keys => $values){
					$temp[$i]["unit_id"]  = $values["unit_id"];
					if(file_exists($values["img_url"])){
						$temp[$i]["img"] 	= URL::base(TRUE).$values['img_url'];
						$temp[$i]["img_title"] = ucfirst($values['img_title']);
					}
					
					$temp[$i]["goal_id"]  	= $values["goal_id"];
					$temp[$i]["goal_alt"] 	= $values['goal_alt'];
					$temp[$i]["goal_title"] = ucfirst($values['goal_title']);
					
					$response = '';
					$parameter = $parameter1 =  $parameter2 = '';
					if($values['goal_time_hh']>0 || $values['goal_time_mm'] >0 || $values['goal_time_ss']  >0){
						$parameter .='<span>'.substr(sprintf("%02d", $values['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $values['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $values['goal_time_ss']),0,2).'</span> /// ';
					}
					if($values['goal_dist']>0 && $values['goal_dist_id']>0){
						$parameter .= $values['goal_dist'].' <span>'.Model::instance('Model/workouts')->getGoalVars('dist',$values['goal_dist_id']).'</span> /// ';
					}
					if($values['goal_reps']>0){
						$parameter .= $values['goal_reps'].' <span>reps</span> /// ';
					}
					if($values['goal_resist']>0 && $values['goal_resist_id']>0){
						$parameter .= ' x '.$values['goal_resist'].' <span>'.Model::instance('Model/workouts')->getGoalVars('resist',$values['goal_resist_id']).'</span> /// ';
					}																									
					$response .= ($parameter)?substr($parameter,0,-4):'';
					
					if($values['goal_rate']>0 && $values['goal_rate_id']>0){
						$parameter1 = '<span>@'.$values['goal_rate'].' '.Model::instance('Model/workouts')->getGoalVars('rate',$values['goal_rate_id']).'</span> ';
					}
					if($values['goal_angle']>0 && $values['goal_angle_id']>0){
						$parameter1 .= '<span>'.$values['goal_angle'].'%'.Model::instance('Model/workouts')->getGoalVars('angle',$values['goal_id']).'</span> ';
					}
					$intvalue = Model::instance('Model/workouts')->getGoalVars('int',$values['goal_id']);
					if($intvalue>0){
						$parameter1 .= '<span>'.$intvalue.' int</span>';
					}
					if($values['goal_rest_mm'] + $values['goal_rest_ss']>0){
						if($values['goal_rest_mm'] >0 ||  $values['goal_rest_ss']>0){
							$parameter1 .=  ' <span>'.$values['goal_rest_mm'];
							if($values['goal_rest_ss']>0 && $values['goal_rest_ss'] < 10)
								$parameter1 .=  ':0'.$values['goal_rest_ss'];
							else
								$parameter1 .=  ':'.substr(sprintf("%02d", $values['goal_rest_ss']),0,2);
							$parameter1 .=  ' rest</span>';
						}
					}
					$response .= ($parameter1)?'</br>'.$parameter1:'';
					$temp[$i]["response"] = $response;
					
					$i++;
				}
				$data["exeset"] = $temp;
			}else{
				$data["exeset"] = '';
			}
			
			//echo "<pre>";print_r($data);
			//print_r($workoutRecord);
			//print_r($exerciseRecord);exit;
			echo $this->request->response = json_encode($data);
			//$this->response->body(json_encode($data));
			exit;
		}
		echo false;
		exit;
	}
	
	public function action_changeRoleAccess() {
		$accessId = '';
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			$role_access_model = ORM::factory('admin_roleaccess');
			if(isset($post['accessId']) && $post['accessId']!='') {
				$role_access_model->removeRoleAccess($post['accessId']);
			} else {
				$accessId = $role_access_model->insertRoleAcces($post);
			}
		}
		echo $accessId;
		exit;
	}
}
