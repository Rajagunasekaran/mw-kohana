<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_User extends Controller_Admin_Website
{
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
    public function action_create()
    {
        $urole   = explode("/", Request::current()->param('id'));
        $urlRole = $urole[0];
        if (Helper_Common::is_trainer() && !(Helper_Common::is_register())) {
            $this->redirect('admin/dashboard');
            return;
        }
		$settings_model = ORM::factory('settings');
        //$smtpmodel = ORM::factory('admin_smtp');
        $this->template->title = 'Create User';
        $this->render();
        /*$template_id = $this->request->param('id');
        if(isset($template_id) && $template_id!='') {
        $this->template->content->template_details = $smtpmodel->getEmailTemplate('*','template_id='.$template_id);
        }*/
        if ($this->request->method() == HTTP_Request::POST) {
            $post   = $this->request->post();
            $object = Validation::factory($post);
            $object->rule('user_fname', 'not_empty')->rule('user_lname', 'not_empty')->rule('user_email', 'not_empty')->rule('user_email', 'Controller_Admin_User::userEmailCheck', array(
                $post['user_email'],
                ':validation'
            ))->rule('password', 'not_empty');
            if ($object->check()) { //Validate required fields
                $user = ORM::factory('user')->create_user($this->request->post(), array(
                    'user_email',
                    'password'
                ));
                // Grant user login role && admin or user role
                $user->add('roles', ORM::factory('Role', array(
                    'name' => 'login'
                )));
                $user->add('roles', ORM::factory('Role', array(
                    'name' => $this->request->post('user_level')
                )));
                $user->user_fname      = $this->request->post('user_fname');
                $user->user_lname      = $this->request->post('user_lname');
                $user->user_gender     = $this->request->post('user_gender');
                $user->ip_address      = $_SERVER['REMOTE_ADDR'];
                $user->activation_code = md5(microtime() . rand());
                $user->user_dob        = date("Y-m-d", strtotime($this->request->post('birthday_year') . '-' . $this->request->post('birthday_month') . '-' . $this->request->post('birthday_day')));
                $user->user_access     = 1;
                $user->site_id         = Session::instance()->get('current_site_id');
				$user->user_profile    = $this->request->post('user_profile');
                $user->date_created    = Helper_Common::get_default_datetime();
                $user->save();
				$preference_Defaults 	= $settings_model->get_site_settings();
					if(isset($preference_Defaults[0]) && count($preference_Defaults[0]) > 0)
						$settings_model->insertUserSettings($preference_Defaults[0],$user->pk());
                if($user->id)
                {
						
						$siteid = Session::instance()->get('current_site_id');
						$check_site = DB::select('*')->from('user_sites')->where('site_id', '=', $siteid)->where('user_id', '=', $user->id)->execute()->as_array();
									//->where('status', '!=', '4')
						//print_r($check_site);
						if(isset($check_site) && count($check_site)==0 )	{
							$id = DB::insert('user_sites', array('user_id','site_id'))->values(array($user->id,$siteid))->execute();
						}
                }
					 //die;
                $this->template->content->success = "New user created Successfully!!!";
            } else { 
                $errors                          = $object->errors('user');
                $this->template->content->errors = $errors;
            }
        }
    }
    public static function userEmailCheck($user_email, Validation $validation)
    {
        if (stripos($user_email, '@')) {
            if ((bool) DB::select(array(
                DB::expr('COUNT(*)'),
                'total_count'
            ))->from('users')->where('user_email', '=', $user_email)->execute()->get('total_count')) {
                $query = DB::select('user_access')->from('users')->join('user_sites')->on('users.id', '=', 'user_sites.user_id')->where('user_sites.status', '!=', '4')->where('users.user_email', '=', $user_email)->execute();
                $list  = $query->as_array();
                if (isset($list) && count($list) > 0) {
                    if ($list[0]['user_access'] == 6) {
                        // not yet confirmed
                        $validation->error('user_email', 'Email already exists but it is unconfirmed.');
                    } else if ($list[0]['user_access'] == 1) {
                        // confirmed
                        $validation->error('user_email', 'Email already exists');
                    }
                }
                //$validation->error('user_email', 'userEmailOrPhoneNotUnique');
            } else {
                return true;
            }
        } else {
            $justNums = preg_replace("/[^0-9]/", '', $user_email);
            //eliminate leading 1 if its there
            if (strlen($justNums) == 11)
                $justNums = preg_replace("/^1/", '', $justNums);
            if (is_numeric($justNums)) {
                $query = DB::select('user_access')->from('users')->where('user_mobile', '=', $user_email)->execute();
                $list  = $query->as_array();
                if (isset($list) && count($list) > 0) {
                    if ($list[0]['user_access'] == 6) {
                        // not yet confirmed
                        $validation->error('user_email', 'Phone number already exists but it is unconfirmed.');
                    } else if ($list[0]['user_access'] == 1) {
                        // confirmed
                        $validation->error('user_email', 'Phone number already exists');
                    }
                }
                return true;
            }
            $validation->error('user_email', 'Email / Phone number is invalid.');
        }
    }
    public static function action_ajaxuserEmailCheck()
    {
        $assignsitesDel = ORM::factory('admin_assignsites');
        $user_email     = $_POST['user_email'];
		$data = array();
        if (isset($user_email) && $user_email != '') {
            if (stripos($user_email, '@')) {
                if ((bool) DB::select(array(
                    DB::expr('COUNT(*)'),
                    'total_count'
                ))->from('users')->join('user_sites')->on('users.id', '=', 'user_sites.user_id')->where('user_sites.status', '!=', '4')->where('users.user_email', '=', $user_email)->execute()->get('total_count')) {
                    $query = DB::select('user_access')->from('users')->where('user_email', '=', $user_email)->execute();
					
                    $list  = $query->as_array();
                    if (isset($list) && count($list) > 0) {
                        if ($list[0]['user_access'] == 6) {
                            // not yet confirmed
                            $data['error']   = true;
                            $data['message'] = 'Email already exists but it is unconfirmed.<br><a class="act_resend" href="javascript:void(0);" onclick="resend_link(\''.$user_email.'\',\'\')">Resend Activation Link</a><span id="act_re"></span>';
                        } else if ($list[0]['user_access'] == 1) {
                            // confirmed
                            $data['error']   = true;
                            $data['message'] = 'Email ID already exists';
                        }
                    }
					


                } else {
                    $data['success'] = true;
                    $data['message'] = "Not Exists";
                }
            }
            echo json_encode($data, true);
        }
        exit;
    }
    public function action_browseuser()
    {
        $this->template->title = 'Browse Subscribers';
        $this->render();
        $usermodel = ORM::factory('admin_user');
        $user_id   = $this->request->param('id');
		  
		  
		  
        if (isset($user_id) && $user_id != '') {
            $updateStr = 'user_access=8';
            $condtnStr = 'id=' . $user_id;
            $usermodel->update_user($updateStr, $condtnStr);
            $this->template->content->success = 'Subscriber Deleted Successfully';
        }
        $this->template->content->template_details  = $usermodel->get_user_by_condtn('*', 'user_access=6');
        $this->template->content->user_access_array = $usermodel->get_user_access_by_condtn('*');
    }
	 public function action_remove_profile(){
		$usermodel = ORM::factory('admin_user');
		$userid    = $this->request->post('userid');
		$res = $usermodel->remove_profile($userid);
		if($res){
			//$this->template->content->success = 'Promo profile was removed successfully...!';
			$this->session->set('flash_success', 'Promo profile was removed successfully...!');
			echo true;
		}
		die;
	 }
	 
	public function action_save_profile(){
		$usermodel = ORM::factory('admin_user');
		$userid    = $this->request->post('subscribers');
		if($userid){
			$userid = implode(",",$userid);
			$result = $usermodel->get_users_details($userid);
			if($result){
				$str1 = $str = "";
				foreach($result as $k=>$v){
					$check_profile_exist = $usermodel->check_profile_exist($v['userid']);					
					if($check_profile_exist){
						$v["site_id"] = $this->current_site_id;
						$res = $usermodel->updateprofile($v,$v['userid']);
						$str1 .= $v["firstname"]." ".$v["lastname"]." was already added successfully...!<br>";
					}else{
						$v["site_id"] = $this->current_site_id;
						$res = $usermodel->insertprofile($v);
						if($res){
							$str .= $v["firstname"]." ".$v["lastname"]." was added successfully...!<br>";
						}else{
							$str1 .= $v["firstname"]." ".$v["lastname"]." wasn't added due to invalid data...!<p><br>";
						}
					}
				}
				$this->session->set('flash_success', $str);
				$this->session->set('flash_error', $str1);
				echo true;
				/*
				echo "<pre>";
				print_R($result);
				die;
				*/
			}
		}
		die();
	}
	 
	 public function action_profile(){
		  $usermodel = ORM::factory('admin_user');
		  $userid    = $this->request->param('id');
		  /*$is_trainer = $usermodel->check_trainer($userid);
		  if(!$is_trainer){
				$this->redirect("admin");
		  }*/
		  /*****Don't Change for this array****/
		  $specialties = array(
								"1"	=>	"Body transformation",
								"2"	=>	"Boxing",
								"3"	=>	"Core Strength",
								"4"	=>	"Weight Loss",
								"5"	=>	"Functional Training",
								"6"	=>	"Small Group Training",
								"7"	=>	"Strength",
								"8"	=>	"Pre/Post Pregnancy",
								"9"	=>	"Rehab/Physio",
								"10"	=>	"Martial Arts",
								"11"	=>	"Sports Specific Training"
						  );
		  $this->template->title = 'Profile User';
		  $this->render();
		  $userdata = array();
		  
		  $getuser = $usermodel->get_users($userid);
		  
		  if(isset($getuser) && is_array($getuser) && count($getuser)>0){
				$userdata = $getuser[0];	
		  }
		  
		  $getuserdata = $usermodel->get_profile($userid);
		  $img = URL::base(TRUE).'assets/img/user_placeholder.png';
		  if(isset($getuserdata) && is_array($getuserdata) && count($getuserdata)>0){
				$getuserdata = $getuserdata[0];
				$getuserdata["qualifications"] = explode("#,#",$getuserdata["qualifications"]);
				$getuserdata["achievements"] = explode("#,#",$getuserdata["achievements"]);
				$getuserdata["specialties"] = explode("#,#",$getuserdata["specialties"]);
				$getuserdata["otherspecialties"] = explode("#,#",$getuserdata["otherspecialties"]);
				$getImg = $usermodel->get_users_profile_image($getuserdata["profile_img"]);
				
				if(file_exists($getImg["img_url"])){
					$img = URL::base(TRUE).$getImg["img_url"];
				}
				
				//echo $img;
				//echo "<pre>";print_r($getuserdata);print_R($getImg);die;
				$userdata = $getuserdata;
		  }
		  //echo "<pre>";print_r($userdata);die;
		  $this->template->content->profileimg = $img;
		  $this->template->content->profiledata = $userdata;
		  $this->template->content->specialties = $specialties;
		  if ($this->request->method() == HTTP_Request::POST) {
				$post   = $this->request->post();
				$post["profile_img"] = (isset($post["trainer_profile_image"]) && $post["trainer_profile_image"]!='')?$post["trainer_profile_image"]:'';
				unset($post["trainer_profile_image"]);
				$validator = $usermodel->validate_user_profile(arr::extract($post,array('specialties'))
																				);
				if ($validator->check()) {
					unset($post["submit"]);
					if(isset($post["qualifications"]) && is_array($post["qualifications"]) && count($post["qualifications"])>0){
						$post["qualifications"] = implode("#,#",$post["qualifications"]);
					}
					if(isset($post["achievements"]) && is_array($post["achievements"]) && count($post["achievements"])>0){
						$post["achievements"] = implode("#,#",$post["achievements"]);
					}
					if(isset($post["otherspecialties"]) && is_array($post["otherspecialties"]) && count($post["otherspecialties"])>0){
						$post["otherspecialties"] = implode("#,#",$post["otherspecialties"]);
					}
					if(isset($post["specialties"]) && is_array($post["specialties"]) && count($post["specialties"])>0){
						$post["specialties"] = implode("#,#",$post["specialties"]);
					}
					$check_profile_exist = $usermodel->check_profile_exist($userid);
					if($check_profile_exist){
						$res = $usermodel->updateprofile($post,$userid);
						$udata["user_fname"] = $post["firstname"];
						$udata["user_lname"] = $post["lastname"];
						$udata["avatarid"] = $post["profile_img"];
						$uer_res = $usermodel->updateuser($udata,$userid);
					}else{
						$post["userid"] = $userid;
						$res = $usermodel->insertprofile($post);
					}
					if($res == true){
						$this->template->content->success = "Trainer profile was updated successfully";
					}
				}else {
					//$errors = $validator->errors('errors/en');
               $errors                          = $validator->errors('user');
               $this->template->content->errors = $errors;
            }
				
				//echo "<pre>";print_r($errors);
				//print_r($post);
				//die;
				
				//echo "<pre>";print_r($this->template->content->profiledata);die;
				
		  }
		  $this->template->js_bottom = array('assets/js/SimpleAjaxUploader.js','assets/js/ad_script.js');
		  
		  
		  
	} 
	 public static function the_rule($post)
	 {
		  if(isset($pos["specialist"]) && count($pos["specialist"])>0){
				return true;
		  }else{
				return false;
		  }
		  
	 }
	 
    public function action_edit()
    {
        $userid    = $this->request->param('id');
        $adminuser = ORM::factory('admin_user');
        $role      = $adminuser->getUsersRoleByUserId($userid);
        if (isset($role) && count($role) > 0) {
            $role_id = min($role);
        }
        if (Helper_Common::is_trainer() && $role_id != 6) {
            $this->redirect('admin/dashboard');
            return;
        }
        if (!is_numeric($userid) || $userid == "" || $userid == "0") {
            $this->redirect("admin/subscriber/browse");
        }
        $this->template->title = 'Edit User';
        $this->render();
        if ($this->request->method() == HTTP_Request::POST) {
            $post   = $this->request->post();
            $object = Validation::factory($post);
            $object->rule('user_fname', 'not_empty')->rule('user_lname', 'not_empty')->rule('user_email', 'not_empty');
            if ($object->check()) { //Validate required fields                   
                $user               = ORM::factory('user')->where('id', '=', $userid)->find();
                $userCurrentRoleIds = $adminuser->getUsersRoleNamesByUserId($userid);
                if (!in_array($this->request->post('user_level'), $userCurrentRoleIds)) {
                    $hiddenUserRole = $adminuser->getUserRoleByName($this->request->post('hidden-userrole'));
                    $user->remove('roles', ORM::factory('Role', array(
                        'name' => $hiddenUserRole
                    )));
                    $user->add('roles', ORM::factory('Role', array(
                        'name' => $this->request->post('user_level')
                    )));
                }
                $user->user_fname      = $this->request->post('user_fname');
                $user->user_lname      = $this->request->post('user_lname');
                $user->user_gender     = $this->request->post('user_gender');
                /*if($this->request->post('password') != ""){
                $user->password 		=	$this->hash($this->request->post('password'));
                }*/
                $user->ip_address      = $_SERVER['REMOTE_ADDR'];
                $user->activation_code = md5(microtime() . rand());
                $user->user_dob        = date("Y-m-d", strtotime($this->request->post('birthday_year') . '-' . $this->request->post('birthday_month') . '-' . $this->request->post('birthday_day')));
				$user->user_profile    = $this->request->post('user_profile');
                $user->save();
                $this->template->content->success = "User Updated Successfully!!!";
                //Reset values so form is not sticky
                $_POST                            = array();
            } else {
                $errors                          = $object->errors('user');
                $this->template->content->errors = $errors;
            }
        }
        $userDetails                          = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $this->template->content->userDetails = $userDetails;
    }
    public function action_assignSitesToManager()
    {
        if ($this->request->is_ajax())
            $this->auto_render = FALSE;
        if (isset($_POST['id']) && $_POST['id'] != '') {
            $userid = $_POST['id'];
        } else if (isset($_POST['hidden-userid']) && $_POST['hidden-userid'] != '') {
            $userid = $_POST['hidden-userid'];
        }
        if (!is_numeric($userid) || $userid == "" || $userid == "0") {
        }
        $adminuser             = ORM::factory('admin_user');
        $sites                 = ORM::factory('admin_sites');
        $this->template->title = 'Assign Sites to Manager';
        $this->render($this->template, 'pages/Admin/Manager/assign');
        if (empty($userDetails) || $userDetails['role_name'] != "manager") {
            //$this->redirect("admin/manager/browse");
        }
        if (HTTP_Request::POST == $this->request->method()) {
            $assignsitesDel = ORM::factory('admin_assignsites');
            $post           = new Validation($_POST);
            $currentDate    = Helper_Common::get_default_datetime();
            //Upload File
            if (isset($_POST['site_ids'])) {
                $assignsitesDel->removeSites($this->request->post('hidden-userid'));
                foreach ($_POST['site_ids'] as $siteId) {
                    $assignsites          = ORM::factory('admin_assignsites');
                    $assignsites->user_id = $this->request->post('hidden-userid');
                    $assignsites->site_id = $siteId;
                    $assignsites->save();
                }
                //$this->session->set('flash_success', 'Sites Assigned successfully!!!');			
                $data['message'] = 'Sites Assigned successfully!!!';
            }
            //$this->redirect('admin/manager/browse');
        }
        $userDetails   = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $sitesArr      = $sites->getAllSites();
        $assignedSites = $sites->getAllAssignedSites($userid);
        if (isset($assignedSites) && count($assignedSites) > 0) {
            $site_id = array();
            foreach ($assignedSites as $key => $value) {
                $site_id[] = $value['site_id'];
            }
        }
        $data['userDetails'] = $userDetails;
        $data['sitesArr']    = $sitesArr;
        if (isset($site_id) && count($site_id) > 0) {
            $data['assignedSites'] = $site_id;
        }
        if (isset($data) && count($data) > 0) {
            $data['success'] = true;
        } else {
            $data['success'] = false;
        }
        $this->response->body(json_encode($data));
    }
    public function action_viewAssignSitesToManager()
    {
        $userid = $this->request->param('id');
        if (!is_numeric($userid) || $userid == "" || $userid == "0") {
            $this->redirect("admin/manager/browse");
        }
        $adminuser             = ORM::factory('admin_user');
        $sites                 = ORM::factory('admin_sites');
        $userDetails           = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $sitesArr              = $sites->getAllSites();
        $assignedSites         = $sites->getAllAssignedSitesByJoin($userid);
        $this->template->title = ucfirst($userDetails['user_fname']) . ' ' . ucfirst($userDetails['user_lname']) . ' Sites';
        $this->render($this->template, 'pages/Admin/Manager/view_assign');
        if (empty($userDetails) || $userDetails['role_name'] != "manager") {
            $this->redirect("admin/manager/browse");
        }
        $this->template->content->template_details = $assignedSites;
    }
    public function action_getFeedDetails()
    {
        if ($this->request->is_ajax())
            $this->auto_render = FALSE;
        $usermodel = ORM::factory('admin_user');
        $user_id   = $_POST['id'];
		$is_front  = (isset($_POST['is_front']) && $_POST['is_front'] ? true : false);
		$is_popup= (isset($_POST['popupFlag']) && $_POST['popupFlag'] ? true : false);
        if (isset($user_id) && $user_id != '') {
            $user_details = $usermodel->get_user_by_condtn('user_fname,user_lname,user_gender,user_mobile', 'id=' . $user_id);
            $role_name    = $usermodel->getUsersRoleNamesByUserId($user_id);
            if (isset($user_details) && count($user_details) > 0) {
                $user_name         = '<span id="fname">' . $user_details[0]['user_fname'] . '</span> <span id="lname">' . $user_details[0]['user_lname'] . "</span>";
                 $user_gender       = '<span id="gender">' .($user_details[0]['user_gender'] == '1' ? 'Male' : 'Female'). '</span>';
				 $user_phone       	= '<span id="phone">' .$user_details[0]['user_mobile']. '</span>';
				 $data['user_name'] 	= $user_name;
				 $data['user_gender'] 	= $user_gender;
				 $data['user_phone'] 	= $user_phone;
            }
            if (isset($role_name) && count($role_name) > 0) {
                $role_name_str     = implode(',', $role_name);
                $data['role_name'] = $role_name_str;
                //if ($data['role_name'] == 'register') {
                    $get_register_details = $usermodel->get_user_by_condtn('user_dob', 'id=' . $user_id);
                    if (isset($get_register_details) && count($get_register_details) > 0) {
                        $data['user_dob'] = date('d M Y', strtotime($get_register_details[0]['user_dob']));
                        $data['user_age'] = Helper_Common::get_age(date('d-m-Y', strtotime($get_register_details[0]['user_dob'])));
                    }
                //}
            }
            $admin_subscriber_model = ORM::factory('admin_subscriber');
            $usertags               = $admin_subscriber_model->get_user_tags($user_id, $this->current_site_id);
            if ($usertags) {
                $ut = array();
                if (count($usertags) > 0) {
                    foreach ($usertags as $usertag) {
                        $ut[] = $usertag['tag_title'];
                    }
                }
                $data['user_tags'] = implode(",", $ut);
            }
			$questionmodel         = ORM::factory('admin_questions');
			$answers = $questionmodel->getSingleAnswers($user_id);
			if(!empty($answers) && count($answers)>0){
				$height = $answers['height'] / 100;
				$bmiRate = round(($answers['weight'] / $height) / $height );
				if($bmiRate >= 18.5 && $bmiRate <= 24.9){
					$data['user_bmi']  = $bmiRate.' = Normal (18.5-24.9)';
				}else if($bmiRate >= 25 && $bmiRate <= 29.9){
					$data['user_bmi']  = $bmiRate.' = Overweight (25-29.9)';
				}else if($bmiRate >= 30 && $bmiRate <= 34.9){
					$data['user_bmi']  = $bmiRate.' = Obese(30-34.9)';
				}else if($bmiRate >= 35 && $bmiRate <= 39.9){
					$data['user_bmi']  = $bmiRate.' = Severly Obese (35 - 39.9)';
				}else if($bmiRate >= 40){
					$data['user_bmi']  = $bmiRate.' = Morbix Obese (40+)';
				}
			}
            $offset             = 0;
            $limit              = 20;
			$feed_details_array_all = $usermodel->get_feed_details($user_id, ($is_front ? '' : $this->current_site_id), '', '', '');
            $feed_details_array = $usermodel->get_feed_details($user_id, ($is_front ? '' : $this->current_site_id), '', $limit, $offset);
            if (isset($feed_details_array) && count($feed_details_array) > 0) {
                $feed_details = '<div class="panel-body">
					<input type="hidden" id="af_limit_popup" value="' . $limit . '">
					<input type="hidden" id="af_all_popup" value="' . count($feed_details_array_all) . '">
					<input type="hidden" id="af_showmore_popup" value="' . $offset . '">
					<input type="hidden" id="af_userids_popup" value="' . $user_id . '">
					<input type="hidden" id="af_popup" value="1">
					<input type="hidden" id="af_site_popup" value="' . ($is_front ?  '' : $this->current_site_id) . '">
					<div class="list-group" id=\'act_feed_popup\'>';
                foreach ($feed_details_array as $key => $value) {
                    $string = Helper_Activityfeed::activity_index($value,$is_front,$is_popup);
                    $feed_details .= $string;
                }
                $feed_details .= '</div>';
				$feed_details.='</div><script>$(".panel-body").bind("scroll", function(e){ if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight){ if($("div#act_feed_popup").length){show_more(e);}}});</script>';
                $data['feed_details'] = $feed_details;
            } else {
                $data['feed_details'] = '<div class="panel-body">No feed found</div>';
            }
            $offset                = 0;
            $limit                 = 20;
            $loggoal_details_array = $usermodel->get_loggoal_details($user_id, $this->current_site_id, $limit, $offset);
            $loggoalmain_array     = $musmainarray = $listgoalmustype = $listgoalmusname = $others_listar = $chloggoalmain_array = '';
            if (is_array($loggoal_details_array) && count($loggoal_details_array) > 0) {
                foreach ($loggoal_details_array as $key_val => $value_loggoal) {
                    if ($value_loggoal['wkout_status'] == 1) {
                        $musmainarray[$value_loggoal['musprim_id']][]  = $value_loggoal['goal_id'];
                        $listgoalmustype[]                             = $value_loggoal['musprim_id'];
                        $listgoalmusname[$value_loggoal['musprim_id']] = $value_loggoal['muscle_title'];
                    }
                }
            }
            $key_val2set = 0;
            $liste       = array();
            if (is_array($listgoalmustype) && count($listgoalmustype) > 0) {
                foreach ($listgoalmustype as $key_val1 => $value_loggoal1) {
                    if (isset($listgoalmusname[$value_loggoal1]) && isset($musmainarray[$value_loggoal1])) {
                        if (!in_array($value_loggoal1, $liste) && round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100) > 1) {
                            $loggoalmain_array[$key_val2set]['label']      = $listgoalmusname[$value_loggoal1];
                            $loggoalmain_array[$key_val2set]['data']       = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
                            $chloggoalmain_array[$key_val2set]['label']    = $listgoalmusname[$value_loggoal1];
                            $chloggoalmain_array[$key_val2set]['value']    = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
                            $chloggoalmain_array[$key_val2set]['orgvalue'] = count($musmainarray[$value_loggoal1]);
                            array_push($liste, $value_loggoal1);
                            $key_val2set++;
                        } else if (!in_array($value_loggoal1, $liste)) {
                            $others_listar = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
                            array_push($liste, $value_loggoal1);
                        }
                    }
                }
            }
            if (count($loggoalmain_array) <= 1 && $others_listar != '') {
                $loggoalmain_array[$key_val2set]['label'] = 'Others';
                $loggoalmain_array[$key_val2set]['data']  = $others_listar;
            }
            if (count($chloggoalmain_array) <= 1 && $others_listar != '') {
                $chloggoalmain_array[$key_val2set]['label'] = 'Others';
                $chloggoalmain_array[$key_val2set]['data']  = $others_listar;
            }
            $data['contentchart']      = $loggoalmain_array;
			$data['profiledetails']    = $this->profiledetails($user_id,$is_front);
			$data['profilereports']    = $this->profilereports();
            $data['checkcontentchart'] = $chloggoalmain_array;
            $data['totalgoal']         = count($loggoal_details_array);
			$data['success']		   = (isset($data) && count($data) > 0 ? true : false);
            $this->response->body(json_encode($data));
        }
    }
    public function action_changeManagerStatus()
    {
        if ($this->request->is_ajax())
            $this->auto_render = FALSE;
        $usermodel = ORM::factory('admin_user');
        if (isset($_POST['userId']) && $_POST['userId'] != '' && isset($_POST['statusId']) && $_POST['statusId'] != '') {
            $updateStr = 'status_id=' . $_POST['statusId'];
            $condtn    = 'user_id=' . $_POST['userId'];
            $usermodel->updateManagerUserStatus($updateStr, $condtn);
            $data['success'] = true;
            $data['message'] = 'Status updated successfully';
        }
        $this->response->body(json_encode($data));
    }
    function action_get_userlist_pdf()
    {
        $user     = ORM::factory('admin_user');
        $authid   = $this->globaluser->pk();
        $roleid   = $this->request->param('id');
		$uid   	  = (isset($_GET["uid"])) ? $_GET["uid"] : '';
        $siteid   = Session::instance()->get('current_site_id');
        $sitename = Session::instance()->get('current_site_name');
        $rolename = $user->user_role_load_by_id($roleid);
        if ($rolename == 'register') {
            $rolename = 'Subscriber';
        } else {
            $rolename = $rolename;
        }
        $title           = '"' . $sitename . '" ' . $rolename . 's List Report';
        $subscribermodel = ORM::factory('admin_subscriber');
        //$content=$user->getUserDetails('','',3);
        $content         = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid,$uid); 
		//echo '<pre>';print_r($content);echo '</pre>';	exit;
        $contents        = $this->_Gst_user_report_content($content, $title);
        $contents        = $this->_generatePDF($contents, $title);
    }
    function _Gst_user_report_content($content, $title)
    {
        if (empty($content)) {
            return false;
        }
        $msg = '<h2>' . $title . '</h2><table cellspacing="8" cellpadding="5" border="1" width="100%">';
        $msg .= '<tr><td style="width:20%; padding-left:5px;">First Name</td><td style="width:20%; padding-left:5px;">Last Name</td><td style="width:20%; padding-left:5px;">Email</td><td style="width:20%; padding-left:5px;">Phone</td><td style="width:20%; padding-left:5px;">Status</td><td style="width:20%; padding-left:5px;">Date Created</td></tr>';
        foreach ($content as $con) {
            $msg .= '<tr>';
            $msg .= '<td style="padding-left:5px;">' . $con['user_fname'] . '</td>';
            $msg .= '<td style="padding-left:5px;">' . $con['user_lname'] . '</td>';
            $msg .= '<td style="padding-left:5px;">' . $con['user_email'] . '</td>';
            $msg .= '<td style="padding-left:5px;">' . $con['user_mobile'] . '</td>';
            $msg .= '<td style="padding-left:5px;">' . $con['userstatus'] . '</td>';
            $msg .= '<td style="padding-left:5px;">' . $con['date_created'] . '</td>';
            $msg .= '</tr>';
        }
        $msg .= '</table>';
        return $msg;
    }
    public function _generatePDF($message, $title)
    {
        $this->auto_render = FALSE;
        include("./plugins/mpdf60/mpdf.php");
        $mpdf        = new mPDF('c', 'A4', '', '', 20, 20, 20, 20, 10, 10);
        $mpdf->debug = true;
        $mpdf->SetTitle($title);
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
        $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
        $stylesheet = file_get_contents('plugins/mpdf60/mpdfstyletables.css');
        $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text				
        $mpdf->WriteHTML($message, 2);
        $file_name = 'MTA-' . $title . '-' . date('Ymdhis');
        $mpdf->Output($file_name . '.pdf', 'I');
        exit;
        return $file_name;
    }
    public function action_get_user_report_as_excel()
    {
        $authid          = $this->globaluser->pk();
        $roleid          = $this->request->param('id');
		$uid   	  		 = (isset($_GET["uid"])) ? $_GET["uid"] : '';
        $siteid          = Session::instance()->get('current_site_id');
        $user            = ORM::factory('admin_user');
        $sitename        = Session::instance()->get('current_site_name');
        $rolename        = $user->user_role_load_by_id($roleid);
        $subscribermodel = ORM::factory('admin_subscriber');
        $online_report   = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid,$uid);
        include("./plugins/phpexcel/Classes/PHPExcel.php");
        $objPHPExcel  = new PHPExcel();
        $serialnumber = 0;
        //Set header with temp array
        $tmparray     = array(
            "S.No",
            "First Name",
            "Last Name",
            "Email",
            "Phone Number",
            "Status",
            "Created On"
        );
        //take new main array and set header array in it.
        $sheet        = array(
            $tmparray
        );
        $tem          = array();
        array_unshift($sheet, $tem);
        foreach ($online_report as $onlinereport) {
            $tmparray     = array();
            $serialnumber = $serialnumber + 1;
            array_push($tmparray, $serialnumber);
            $first_name = $onlinereport['user_fname'];
            array_push($tmparray, $first_name);
            $last_name = $onlinereport['user_lname'];
            array_push($tmparray, $last_name);
            $email = $onlinereport['user_email'];
            array_push($tmparray, $email);
            $mobile_phone = $onlinereport['user_mobile'];
            array_push($tmparray, $mobile_phone);
            $status = $onlinereport['userstatus'];
            array_push($tmparray, $status);
            $date_created = $onlinereport['date_created'];
            array_push($tmparray, $date_created);
            array_push($sheet, $tmparray);
        }
        foreach ($sheet as $row => $columns) {
            foreach ($columns as $column => $data) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row + 1, $data);
            }
        }
        header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="userlist.xlsx"');
        header('Cache-Control: max-age=0');
        $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
        $sitename = Session::instance()->get('current_site_name');
        $rolename = $user->user_role_load_by_id($roleid);
        if ($rolename == 'register') {
            $rolename = 'Subscriber';
        } else {
            $rolename = $rolename;
        }
        $file_title = '"' . $sitename . '" ' . $rolename . 's List Report';
        $objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
    function action_report_email()
    {
        $user_detail = Auth::instance()->get_user();
        //$to_email = $this->input->post('email_address');
        $to_email    = $this->request->post('email_address');
        //$to_email ='srinivasan@versatile-soft.com';
        $user        = ORM::factory('admin_user');
        $roleid      = $this->request->post('roleid');
		$uid      	 = $this->request->post('uid');
        $authid      = $this->globaluser->pk();
        $siteid      = Session::instance()->get('current_site_id');
        $sitename    = Session::instance()->get('current_site_name');
        $rolename    = $user->user_role_load_by_id($roleid);
        if ($rolename == 'register') {
            $rolename = 'Subscriber';
        } else {
            $rolename = $rolename;
        }
        $subscribermodel = ORM::factory('admin_subscriber');
        $content         = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid,$uid);
        $config          = Kohana::$config->load('emailsetting');
        $from_address    = $user_detail->user_email;
        $from_name       = $sitename;
        $title           = '"' . $sitename . '" ' . $rolename . 's List Report';
        $report          = $this->_Gst_user_report_content($content, $title);
        $subject         = '"' . $sitename . '" ' . $rolename . 's List Report';
        if (!$report) {
            echo 'no_data';
            exit;
        }
        $email = Email::factory($subject);
        $email->message($report, 'text/html');
        $email->to($to_email);
        $email->from($from_address, $from_name);
        $email->send();
        echo 1;
        exit;
    }
	 public function profiledetails($user_id,$is_front){
		$fromAdmin 	= $is_front;
		$fid 			= $user_id;
		if($fromAdmin == 0){
			$userdetails 	= Auth::instance()->get_user();
			$fid 				= $userdetails->pk();
		}else{
			$userdetails 	= ORM::factory('user')->where('id', '=', $fid)->find(); 
		}
		$age = Helper_Common::get_age(date('d-m-Y', strtotime($userdetails->user_dob)));
		$role_names = Model::instance('Model/admin/user')->getUsersRoleNamesByUserId($fid);
		$all_site_names =  Model::instance('Model/admin/sites')->getAllActiveUserSites($fid);
		$height = $user_bmi = $weight = '';
		$questionmodel         = ORM::factory('admin_questions');
		$answers = $questionmodel->getSingleAnswers($fid);
		if(!empty($answers) && count($answers)>0){
			$height 		= $answers['height'];
			$heightcal 	= $height / 100;
			$weight 		= $answers['weight'];
			$bmiRate = round(($weight / $heightcal) / $heightcal );
			if($bmiRate >= 18.5 && $bmiRate <= 24.9){
				$user_bmi  = $bmiRate.' = Normal (18.5-24.9)';
			}else if($bmiRate >= 25 && $bmiRate <= 29.9){
				$user_bmi  = $bmiRate.' = Overweight (25-29.9)';
			}else if($bmiRate >= 30 && $bmiRate <= 34.9){
				$user_bmi  = $bmiRate.' = Obese(30-34.9)';
			}else if($bmiRate >= 35 && $bmiRate <= 39.9){
				$user_bmi  = $bmiRate.' = Severly Obese (35 - 39.9)';
			}else if($bmiRate >= 40){
				$user_bmi  = $bmiRate.' = Morbix Obese (40+)';
			}
		}
		$settings_model = ORM::factory('settings');
		$devicename = $settings_model->getAlldevice_Integrations();
		$response = '<div class="panel panel-default">
							<ul class="list-group">
								<li class="list-group-item">
									<div data-toggle="detail-1" id="dropdown-detail-1" class="row toggle">
										<div class="col-xs-10">
											<strong>About Me</strong>
										</div>
										<div class="col-xs-2"><i class="fa fa-chevron-up pull-right"></i></div>
									</div>
									<div style="display: block;" id="detail-1">
									<hr>
									<div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Role :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											 '.implode($role_names,', ').'
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Site(s) :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											<span id="sitenamecurrent">'.current($all_site_names).'</span>
											<span id="sitenameall">'.implode($all_site_names,', ').'</span> 
											<i id="showallsitenames" class="fa fa-ellipsis-h activedatacol"></i>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Activated :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											 '.Helper_Common::get_default_datetime($userdetails->date_created).'
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Gender :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											 <i id="malefemaleedit" class="fa fa-pencil activedatacol"></i> '.($userdetails->user_gender == '1' ? 'male' : 'female').'<span id="malefemalespan"><label class="radio-inline">
											<input data-role="none" data-ajax="false" type="radio" name="user_gender" class="user_gender" '.($userdetails->user_gender == '1' ? 'checked=""' : '').' id="male" value="1" />Male
										</label>
										<label class="radio-inline">
											<input data-role="none" data-ajax="false" type="radio" name="user_gender" class="user_gender" id="female" '.($userdetails->user_gender == '2' ? 'checked=""' : '').' value="2" />Female
										</label></span>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Birthdate :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											 <i class="fa fa-pencil activedatacol"></i> '.Helper_Common::change_default_date_dob($userdetails->user_dob).'
										</div>
									</div>
									<div class="row">
										<div class="col-xs-4 datacol">
											 <strong>Phone No :</strong>
										</div>
										<div class="col-xs-8 inactivedatacol">
											 <i class="fa fa-pencil activedatacol"></i> - '.$userdetails->user_mobile.'
										</div>
									</div>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div data-toggle="detail-2" id="dropdown-detail-2" class="row toggle">
									 <div class="col-xs-10">
										  <strong>Measurements</strong>
									 </div>
									 <div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
								</div>
								<div style="display: none;" id="detail-2">
									<hr>
									<div>
										<div class="row">
											<div class="col-xs-4 datacol">
												<strong>Height :</strong>
											</div>
											<div class="col-xs-8 inactivedatacol">
												<i class="fa fa-pencil activedatacol"></i> '.$height.' cm
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4 datacol">
												 <strong>Weight :</strong>
											</div>
											<div class="col-xs-8 inactivedatacol">
												 '.$weight.' kg <span style="font-size:0.8em;color:blue; font-style:italic;"> -1 from last week</span>
												 <i class="fa fa-plus activedatacol"></i><span class="activedatacol"> Current Weight</span>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4 datacol">
												 <strong>BMI :</strong>
											</div>
											<div class="col-xs-8 inactivedatacol">'.$user_bmi.'
											</div>
										  </div>
											<div class="row">
												<div class="col-xs-4 datacol">
													 <strong>Age :</strong>
												</div>
												<div class="col-xs-8 inactivedatacol">
													 '.$age.'
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="list-group-item">
									<div data-toggle="detail-3" id="dropdown-detail-3" class="row toggle">
										<div class="col-xs-10">
										  <strong>Initial Questions</strong>
										</div>
										<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
									</div>
									<div style="display:none;" id="detail-3">
									<hr>
									<div>
										  <!-- dynamicall insert the COMMON QUESTIONS and Answers -->
										<div class="row">
											<div class="col-xs-4 datacol">
												Height :
											</div>
											<div class="col-xs-8 inactivedatacol">
												<i class="fa fa-pencil activedatacol"></i> 197 cm
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4 datacol">
												 Weight :
											</div>
											<div class="col-xs-8 inactivedatacol">
												 <i class="fa fa-pencil activedatacol"></i> 120 cm
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4 datacol">
												 Devices :
											</div>
											<div class="col-xs-8 inactivedatacol">
											 <i class="fa fa-pencil activedatacol"></i> ';
											 $deviceall = array();
											 foreach($devicename as $device) {
												$deviceall[] = $device['name'];
											 }
											 $response .= implode(', ',$deviceall). '</div>
										</div>
										<!-- dynamicall insert the SITE QUESTIONS and Answers -->
									</div>
								</div>
						  </li>
						</ul>
					</div>
					<script>$(document).ready(function(){$("#malefemalespan").hide();$("#malefemaleedit").on("click",function(){$("#malefemalespan").show();});$("#sitenameall").hide();$("#showallsitenames").on("click", function(){$("#sitenamecurrent").hide();$("#sitenameall").show();$("#showallsitenames").hide();});});</script>';
		return $response;
	}
	public function profilereports(){
		$response ='<div class="panel panel-default">
												 <ul class="list-group">
													  <li class="list-group-item">
															<div data-toggle="detail-6" id="dropdown-detail-6" class="row toggle">
																 <div class="col-xs-10">
																	  <strong>Track Record</strong>
																 </div>
																 <div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
															</div>
															<div style="display: none;" id="detail-6">
																 <hr>
																 <div>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Logins :</strong>
																			</div>
																			<div class="col-xs-4 datacol">
																				 Total:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 98
																			</div>
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4 <span style="font-size:0.8em;color:green; font-style:italic;"> +2 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 3<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 12<span style="font-size:0.8em;font-style:italic;">/month</span><span style="font-size:0.8em;color:red; font-style:italic;"> -1 from last month</span>
																			</div>
																	  </div>
																	  <hr>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Workouts Assigned :</strong>
																			</div>
																			<div class="col-xs-4 datacol">
																				 Total:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 12
																			</div>
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 1 <span style="font-size:0.8em;color:red; font-style:italic;"> -2 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 1<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4<span style="font-size:0.8em;font-style:italic;">/month</span> <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																	  
																	  
																	  <hr>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Workout Journals Logged :</strong>
																			</div>
																			<div class="col-xs-4 datacol">
																				 Total:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 83
																			</div>
																			
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4 <span style="font-size:0.8em;color:red; font-style:italic;"> +1 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 13<span style="font-size:0.8em;font-style:italic;">/month</span> <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																	  
																			
																 </div>
															</div>
													  </li>
													  
													  <li class="list-group-item">
															<div data-toggle="detail-9" id="dropdown-detail-9" class="row toggle">
																 <div class="col-xs-10">
																	  <strong>Cardio/Endurance Results</strong>
																 </div>
																 <div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
															</div>
															<div style="display: none;" id="detail-9">
																 <hr>
																 <div>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Cardio Activity Chart :</strong>
																			</div>
																			<div class="col-xs-12 datacol">
																				 <div style="clear: both; display: block;" class="row">
																					  <div class="col-lg-12">
																							<div class="panel panel-default">
																								 <div style="height: auto;" class="panel-body">
																								 <div class="inactivedatacol">Chart 
																										<p>display totals of any xr sets with "cardio" (type_id of activity) logged for each day.</p>
																										<p><strong>default:</strong>
																											 </p><ul>
																												  <li>y-axis = time</li>
																												  <li>x-axis = date</li>
																											  </ul>
																										 <p></p>
																										 <p>options:
																											  </p><ul>
																											  <li>by distance</li>
																											  <li>most recent:</li>
																													<ul>
																													<li>today</li>
																													<li>this week</li>
																													<li>this month</li>
																													<li>specify date(s)</li>
																													</ul>
																											  </ul>
																										 <p></p>
																								 </div>
																								 </div>
																							</div>
																					  </div>
																				 </div>
																			</div>
																	  </div>
																	  <hr>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Cardio :</strong>
																			</div>
																	  </div>
																	  <div class="cardio-1"><div class="row">
																			<div class="col-xs-12 datacol">
																				 Jog / Run:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 24 km <span style="font-size:0.8em;color:red; font-style:italic;"> -2 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 116 min <span style="font-size:0.8em;color:green; font-style:italic;"> +2 from last week</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Month:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 138 km <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 308 min <span style="font-size:0.8em;color:red; font-style:italic;"> -14 from last month</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 1<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4<span style="font-size:0.8em;font-style:italic;">/month</span> <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div></div>
																	  <hr>
																	  <div class="cardio-2"><div class="row">
																			<div class="col-xs-12 datacol">
																				 Bike:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 24 km <span style="font-size:0.8em;color:red; font-style:italic;"> -2 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 116 min <span style="font-size:0.8em;color:green; font-style:italic;"> +2 from last week</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Month:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 138 km <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 308 min <span style="font-size:0.8em;color:red; font-style:italic;"> -14 from last month</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 1<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4<span style="font-size:0.8em;font-style:italic;">/month</span> <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div></div>
																	  <hr>
																	  <div class="cardio-3"><div class="row">
																			<div class="col-xs-12 datacol">
																				 Rowing (stationary):
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 24 km <span style="font-size:0.8em;color:red; font-style:italic;"> -2 from last week</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 116 min <span style="font-size:0.8em;color:green; font-style:italic;"> +2 from last week</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Month:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 138 km <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																			
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 308 min <span style="font-size:0.8em;color:red; font-style:italic;"> -14 from last month</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 Average:
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 1<span style="font-size:0.8em;font-style:italic;">/week</span>
																			</div>
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 4<span style="font-size:0.8em;font-style:italic;">/month</span> <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div></div>
																	  <hr>
																 </div>
															</div>
													  </li>
													  
													  <li class="list-group-item">
															<div data-toggle="detail-10" id="dropdown-detail-10" class="row toggle">
																 <div class="col-xs-10">
																	  <strong>Strength/Resistance Results</strong>
																 </div>
																 <div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
															</div>
															<div style="display:none;" id="detail-10">
																 <hr>
																 <div>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Concentration :</strong>
																			</div>
																			<div class="col-xs-12 datacol">
																				<div class="row" style="clear:both;" id="morris-donutabove" >
																					<div class="col-lg-12">
																						<div class="panel panel-default">
																							<div class="panel-body" >
																								<div class="responsive-chart"><div id="placeholder" style="width: 100%; height: 300px;" ></div></div>
																								<div id="chartLegend"></div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																	  </div>
																	  <hr>
																	  <div class="row">
																			<div class="col-xs-12 datacol">
																				 <strong>Chest :</strong>
																			</div>
																	  </div>  
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Week:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 12 reps <span style="font-size:0.8em;color:red; font-style:italic;"> -2 from last week</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 75 kg max <span style="font-size:0.8em;color:green; font-style:italic;"> +5 from last week</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																				 <span style="font-size:0.8em;font-style:italic;">This Month:</span>
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 52 reps <span style="font-size:0.8em;color:green; font-style:italic;"> +1 from last month</span>
																			</div>
																	  </div>
																	  <div class="row">
																			<div class="col-xs-4 datacol">
																			</div>
																			<div class="col-xs-8 inactivedatacol">
																				 85 kg max <span style="font-size:0.8em;color:red; font-style:italic;"> -14 from last month</span>
																			</div>
																	  </div>
																 </div>
															</div>
													  </li>
												</ul> 
												</div> <script>$(document).ready(function(){$(".toggle").click(function() {$input=$(this);$target=$("#"+$input.attr("data-toggle"));$target.slideToggle();if($input.find(".col-xs-2 i").attr("class")=="fa fa-chevron-down pull-right")$input.find(".col-xs-2 i").removeClass("fa fa-chevron-down pull-right").addClass("fa fa-chevron-up pull-right");else	$input.find(".col-xs-2 i").removeClass("fa fa-chevron-up pull-right").addClass("fa fa-chevron-down pull-right");});});</script>';
		return $response;
	}
	
	public function action_resnedactivation(){
		$usermodel 	= ORM::factory('user');
		$smtpmodel 	= ORM::factory('admin_smtp');
		$user 		= array();
		$session  	= Session::instance();
		$site_id 	= $session->get('current_site_id');
		$usermail 	= $this->request->post('email');
		if(!empty($usermail)){
			$userunreg  = $usermodel->get_user_details($usermail,'');
			$currentDate	= Helper_Common::get_default_datetime();	
			$user['user_email']		= $userunreg['user_email'];
			$user['user_id']		= $userunreg['user_id'];
			$user['user_site_id']	= $userunreg['site_id'];
			$user['activation_code']= md5(microtime().rand());
			$user['date_created'] 	= $currentDate;
			$user['user_fname']		= $userunreg['user_fname'];
			$user['user_lname']		= $userunreg['user_lname'];
			
			/******************* Activity Feed *********************/
			$activity_feed = array();
			$activity_feed["feed_type"]   	= 33;
			$activity_feed["action_type"]  	= 47;
			$activity_feed["type_id"]    	= $activity_feed["user"]  = $user['user_id'];
			$activity_feed["site_id"]  		= $site_id;
			Helper_Common::createActivityFeed($activity_feed);
			/******************* Activity Feed *********************/
			$hompage 		= ORM::factory('homepage');
			$sites 		 	= $hompage->get_sitesby_id($user['user_site_id']);
			$site_slug_name = $sites['slug'];
					
			$useractivation = '<a href="'.URL::site(NULL, 'http').'site/'.$site_slug_name.'/page/activate/'.$user['activation_code'].'?redo=1'.'" target="_blank" title="Click here to activate your account" style="color: #1b9af7;">Click here to activate your account</a>';
			if(!empty($user['user_email'])){
				DB::update('users')->set(array('activation_code' => $user['activation_code'],'last_updated' => $user['date_created']))->where('user_email', '=', $user['user_email'])->execute();
				$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register','site_id'=>$site_id));
				if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
					$templateArray['body'] = str_replace(array('[FirstName]','[ActivationLink]'), array(ucfirst(strtolower($user['user_fname'])), $useractivation), $templateArray['body']);
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
						die();
					}
				}
			}
		}
		echo false;
		die;
	}
}
