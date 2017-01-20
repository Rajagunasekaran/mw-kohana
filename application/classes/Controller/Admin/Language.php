<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Language extends Controller_Admin_Website {

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
        $urole = explode("/",Request::current()->param('id'));
        $urlRole = $urole[0];
		if(Helper_Common::is_trainer() && $urlRole!='register') {
			$this->redirect('admin/dashboard');	
			return;
		}
		
		//$smtpmodel = ORM::factory('admin_smtp');
		$this->template->title 	= 'Create User';
       	$this->render();
		/*$template_id = $this->request->param('id');
		if(isset($template_id) && $template_id!='') {
			$this->template->content->template_details = $smtpmodel->getEmailTemplate('*','template_id='.$template_id);
		}*/
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			$object = Validation::factory($post);
			$object->rule('user_fname', 'not_empty')
					->rule('user_lname', 'not_empty')
					->rule('user_email', 'not_empty')
					->rule('user_email', 'Controller_Admin_User::userEmailCheck', array( $post['user_email'],  ':validation' ))
					->rule('password', 'not_empty');
			if ($object->check()) { //Validate required fields
					$user = ORM::factory('user')->create_user($this->request->post(), array(
						'user_email',
						'password'
					));
					// Grant user login role && admin or user role
					$user->add('roles', ORM::factory('Role', array('name' => 'login')));
					$user->add('roles', ORM::factory('Role', array('name' => $this->request->post('user_level'))));
					$user->user_fname 		=	$this->request->post('user_fname');
					$user->user_lname 		=	$this->request->post('user_lname');
					$user->user_gender		=	$this->request->post('user_gender');
					$user->ip_address 		=	$_SERVER['REMOTE_ADDR'];
					$user->activation_code	=	md5(microtime().rand());
					$user->user_dob 		=	date("Y-m-d",strtotime($this->request->post('birthday_year').'-'.$this->request->post('birthday_month').'-'.$this->request->post('birthday_day')));
					$user->user_access		=	1;
					$user->date_created 	=	Helper_Common::get_default_datetime();
					$user->save();
					if($user->id)
					{
						$siteid = Session::instance()->get('current_site_id');
						$id = DB::insert('user_sites', array('user_id','site_id'))->values(array($user->id,$siteid))->execute();
					}
					$this->template->content->success = "New user created Successfully!!!";
			} else {
				$errors = $object->errors('user');
				$this->template->content->errors = $errors;
			}
		}
	}
	public static function userEmailCheck($user_email, Validation $validation){
		if(stripos($user_email,'@')){
			if((bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))->from('users')->where('user_email', '=', $user_email)->execute()->get('total_count')){
				$query = DB::select('user_access')->from('users')->where('user_email', '=', $user_email)->execute();
				$list = $query->as_array();	
				if(isset($list) && count($list)>0) {
					if($list[0]['user_access']==6) {
						// not yet confirmed
						$validation->error('user_email', 'Email already exists but it is unconfirmed.');
					} else if($list[0]['user_access']==1) {
						// confirmed
						$validation->error('user_email', 'Email already exists');
					}
				}
				//$validation->error('user_email', 'userEmailOrPhoneNotUnique');
			}else{
				return true;
			}
		}else{
			$justNums = preg_replace("/[^0-9]/", '', $user_email);
			//eliminate leading 1 if its there
			if (strlen($justNums) == 11) $justNums = preg_replace("/^1/", '',$justNums);
			if(is_numeric($justNums)){
				$query = DB::select('user_access')->from('users')->where('user_mobile', '=', $user_email)->execute();
				$list = $query->as_array();	
				if(isset($list) && count($list)>0) {
					if($list[0]['user_access']==6) {
						// not yet confirmed
						$validation->error('user_email', 'Phone number already exists but it is unconfirmed.');
					} else if($list[0]['user_access']==1){
						// confirmed
						$validation->error('user_email', 'Phone number already exists');
					}
				} 
				return true;
			}
			$validation->error('user_email', 'Email / Phone number is invalid.');
		}
	}
	public static function action_ajaxuserEmailCheck(){
        $assignsitesDel = ORM::factory('admin_assignsites');
		$user_email = $_POST['user_email'];
		if(isset($user_email) && $user_email!='') {
            if(stripos($user_email,'@')){
                if((bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))->from('users')->where('user_email', '=', $user_email)->execute()->get('total_count')){
                    $query = DB::select('user_access')->from('users')->where('user_email', '=', $user_email)->execute();
                    $list = $query->as_array();	
                    if(isset($list) && count($list)>0) {
                        if($list[0]['user_access']==6) {
                            // not yet confirmed
                            $data['error'] = true;
			                $data['message'] = 'Email already exists but it is unconfirmed.';
                        } else if($list[0]['user_access']==1) {
                            // confirmed
                            $data['error'] = true;
                            $data['message'] = 'Email ID already exists';
                        }
                    }
                }else{
                    $data['success'] = true;
			        $data['message'] = "Not Exists";
                }
            }   
            echo json_encode($data,true);
		}
        exit;
	}
	
	public function action_browse()
	{  
		//if(isset($file_name)){echo $file_name ;} else{echo "Files not selected";}
		$this->template->title = 'Browse Languages';
		/*$this->render();
		
		$usermodel = ORM::factory('admin_user');
		$user_id = $this->request->param('id');
		if(isset($user_id) && $user_id!='') {
			$updateStr = 'user_access=8';
			$condtnStr = 'id='.$user_id;
			$usermodel->update_user($updateStr,$condtnStr);
			$this->template->content->success = 'Subscriber Deleted Successfully';
		}
		$this->template->content->template_details	= $usermodel->get_user_by_condtn('*','user_access=6');
		$this->template->content->user_access_array = $usermodel->get_user_access_by_condtn('*');*/
		
		$this->render(); 
		$settings_model = ORM::factory('admin_settings');
		$siteid = Session::instance()->get('current_site_id');
		$user_id = $this->request->param('id');
		$current_langue = $settings_model->get_selected_langue($siteid); 
		$this->template->content->current_langue = $settings_model->get_selected_langue($siteid);
		$this->template->css = array('assets/plugins/tinytoggle/css/tiny-toggle.css');
		//$this->template->js_bottom = array('assets/plugins/tinytoggle/js/tiny-toggle.js', 'assets/js/tinytoggle.js','assets/js/pages/admin/settings.js');
		include("./plugins/phpexcel/Classes/PHPExcel.php");
		$objPHPExcel = new PHPExcel(); 
		$language_id = $current_langue['language_id'];
		$this->template->content->language_id =$language_id;  
		$languagelist = $settings_model->languagelist(); 
		$this->template->content->languagedata = $settings_model->languagedata($siteid, $language_id); 
		$this->template->content->siteid =$siteid;
		
		//File Upload 
		
		$filename = '';
		if (HTTP_Request::POST == $this->request->method()){
			 if (isset($_FILES)) { 
				if(!$_FILES['uploadedfile']['error']){
					$valid_file = true;
					$rootdir =  DOCROOT.'assets/uploads/language/';
					$file_name = $_FILES['uploadedfile']['name']; // echo $file_name;
					$file_size = $_FILES['uploadedfile']['size'];
					$file_tmp = $_FILES['uploadedfile']['tmp_name'];
					$file_type= $_FILES['uploadedfile']['type'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

					$target_path = $rootdir . basename( $_FILES['uploadedfile']['name']); 
					//echo "target_path = ".$target_path;
					if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
						//echo "The file ".  basename( $_FILES['uploadedfile']['name'])." has been uploaded";
						
						
						//echo  URL::base('https', TRUE); die;
				 
						$inputFileType = 'Excel2007';
						$inputFileName = DOCROOT.'assets/uploads/language/'.$file_name;
						$sheetname = 'Data Sheet #1';	
						try { $objPHPExcel = PHPExcel_IOFactory::load($inputFileName); } 
							catch(Exception $e) { die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage()); } 
						$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true); $arrayCount = count($allDataInSheet); //print_r($allDataInSheet);
						//echo $arrayCount;
						$language_values = array();	
						
						foreach($languagelist as $lan){
							if($lan['name'] == $allDataInSheet["1"]["B"]){
								$up_language_id = $lan['language_id'];
							}	
						}	
						
						if(isset($up_language_id)){
							for($i=2;$i<=$arrayCount;$i++){ 
								$value_a = trim($allDataInSheet[$i]["A"]);
								$value_b = trim($allDataInSheet[$i]["B"]);
								//echo $value;
								$language_key[$i] 	 = 	$value_a	;		
								$language_values[$i] = 	$value_b	;		
							} 
							$msg = $settings_model->insert_languagedata($siteid,$language_key,$language_values,$up_language_id);
							//$this->template->content->msg =  $msg;
							
							//print_r($language_values);die;
							if (file_exists($_FILES["uploadedfile"]["name"])) { unlink($_FILES["uploadedfile"]["name"]);
							}
							if($msg >0){
								$this->session->set('flash_success', ''.$msg.' Items imported successfully!!!');
								$this->redirect('admin/language/browse/');
							}else{
								$this->session->set('flash_error', 'Imported data already exist for this site');
								$this->redirect('admin/language/browse/');
							}	
						}else{
							$this->session->set('flash_error', 'Imported file is invalid.');
							$this->redirect('admin/language/browse/');
						}	
							
					} else{
						//echo "There was an error uploading the file, please try again!";
					}
				}
            }

		}
		
		
		
	}
    
	public function action_download_template(){
		
		$myFile = DOCROOT.'assets/uploads/template/test_template_french.xlsx';
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=\"test_template_french.xlsx\"");
		header("Cache-Control: max-age=0");
		readfile($myFile);
		//$objWriter->save('.\assets\uploads\language\lanague-t.xlsx');
		include("./plugins/phpexcel/Classes/PHPExcel.php");
		$objPHPExcel = new PHPExcel(); 
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output'); die;
	}	
	
	
	public function action_edit()
	{
        $userid = $this->request->param('id');
		$adminuser	= ORM::factory('admin_user');
		$role		= $adminuser->getUsersRoleByUserId($userid);
		if(isset($role) && count($role)>0) {
			$role_id = min($role);
		}
		if(Helper_Common::is_trainer() && $role_id!=6) {
			$this->redirect('admin/dashboard');	
			return;
		}
		
		
        if(!is_numeric($userid) || $userid == "" || $userid == "0"){
            $this->redirect("admin/subscriber/browse");
        }
        
        
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
    
	public function action_assignSitesToManager()
	{
	if ($this->request->is_ajax()) $this->auto_render = FALSE;
        
		if(isset($_POST['id']) && $_POST['id']!=''){
			$userid = $_POST['id'];
		} else if(isset($_POST['hidden-userid']) && $_POST['hidden-userid']!='') {
			$userid = $_POST['hidden-userid'];
		}
        if(!is_numeric($userid) || $userid == "" || $userid == "0"){
            
        }        
        $adminuser 		 = ORM::factory('admin_user');
        $sites = ORM::factory('admin_sites');
                
        
		$this->template->title 	= 'Assign Sites to Manager';
        $this->render($this->template, 'pages/Admin/Manager/assign'); 
        
        if(empty($userDetails) || $userDetails['role_name'] != "manager"){
            //$this->redirect("admin/manager/browse");
        }
        
        if(HTTP_Request::POST == $this->request->method()){
            $assignsitesDel = ORM::factory('admin_assignsites');
            
			$post 			= new Validation($_POST);
			$currentDate	= Helper_Common::get_default_datetime();			
			//Upload File
            
			if(isset($_POST['site_ids'])){
                $assignsitesDel->removeSites($this->request->post('hidden-userid'));
                foreach($_POST['site_ids'] as $siteId){
                    $assignsites = ORM::factory('admin_assignsites');
                    $assignsites->user_id = $this->request->post('hidden-userid');
                    $assignsites->site_id = $siteId;
                    $assignsites->save();
                }
                //$this->session->set('flash_success', 'Sites Assigned successfully!!!');			
				$data['message'] = 'Sites Assigned successfully!!!';
            }
            //$this->redirect('admin/manager/browse');
		}
		$userDetails = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $sitesArr = $sites->getAllSites();
        $assignedSites = $sites->getAllAssignedSites($userid);
		if(isset($assignedSites) && count($assignedSites)>0) {
			$site_id = array();
			foreach($assignedSites as $key => $value) {
				 $site_id[] = $value['site_id'];
			}
		}
		
		$data['userDetails']=$userDetails;
		$data['sitesArr']=$sitesArr;
		if(isset($site_id) && count($site_id)>0) {
			$data['assignedSites']=$site_id;
		}
       
		
		if(isset($data) && count($data)>0) {
			$data['success'] = true;
		} else {
			$data['success'] = false;
		}
		$this->response->body(json_encode($data));
		
		
	}
    
	public function action_viewAssignSitesToManager()
	{
        $userid = $this->request->param('id');
        if(!is_numeric($userid) || $userid == "" || $userid == "0"){
            $this->redirect("admin/manager/browse");
        }        
        $adminuser 		 = ORM::factory('admin_user');
        $sites = ORM::factory('admin_sites');
                
        $userDetails = $adminuser->getUserDetailsUniqueForUpdate($userid);
        $sitesArr = $sites->getAllSites();
        $assignedSites = $sites->getAllAssignedSitesByJoin($userid);
        
        
        $this->template->title 	= ucfirst($userDetails['user_fname']).' '.ucfirst($userDetails['user_lname']).' Sites';
        $this->render($this->template, 'pages/Admin/Manager/view_assign');
        
        
        
        if(empty($userDetails) || $userDetails['role_name'] != "manager"){
            $this->redirect("admin/manager/browse");
        }
        
        $this->template->content->template_details = $assignedSites;
	}
	public function action_getFeedDetails() {
		if ($this->request->is_ajax()) $this->auto_render = FALSE;
		$usermodel = ORM::factory('admin_user');
		$user_id = $_POST['id'];
		if(isset($user_id) && $user_id!='') {
			$user_details	= $usermodel->get_user_by_condtn('user_fname,user_lname','id='.$user_id);
			$role_name		= $usermodel->getUsersRoleNamesByUserId($user_id);
			
			if(isset($user_details) && count($user_details)>0) {
				$user_name = $user_details[0]['user_fname'].' '.$user_details[0]['user_lname'];
				$data['user_name'] = $user_name;
			}
			if(isset($role_name) && count($role_name)>0) {
				$role_name_str = implode(',',$role_name);
				$data['role_name'] = $role_name_str;
				if($data['role_name']=='register') {
					$get_register_details = $usermodel->get_user_by_condtn('user_dob','id='.$user_id);
					if(isset($get_register_details) && count($get_register_details)>0) {
						$data['user_dob'] = date('d/m/Y',strtotime($get_register_details[0]['user_dob']));
						$data['user_age'] = Helper_Common::get_age(date('d-m-Y',strtotime($get_register_details[0]['user_dob'])));
					}
				}
			}
			
			$admin_subscriber_model = ORM::factory('admin_subscriber');
			$usertags = $admin_subscriber_model->get_user_tags($user_id,$this->current_site_id);
			if($usertags){
				$ut = array();
				if(count($usertags)>0){
					foreach($usertags as $usertag){					
						$ut[] = $usertag['tag_title'];
					}
				}
				$data['user_tags'] = implode (",", $ut);
			}
			$offset = 0;
			$limit = 20;
			//echo "$offset---$limit";
			$feed_details_array	= $usermodel->get_feed_details($user_id,$this->current_site_id,$limit,$offset);
			//echo count($feed_details_array);
			if(isset($feed_details_array) && count($feed_details_array)>0) {
				
				$icon_array = array(
									'workout folder'	=> 'fa-folder-open',
									'workout plan'		=> 'fa-file',
									'assigned'			=> 'fa-calendar',
									'tag'				=> 'fa-tag',
									'image'				=> 'fa-picture-o',
									'account'				=> 'fa-user',
									'journal'			=>'fa-file',
								);
				$feed_details = '<div class="panel-body">
					<input type="hidden" id="af_limit" value="'.$limit.'">
					<input type="hidden" id="af_showmore" value="'.$offset.'">
					<input type="hidden" id="af_userids" value="'.$user_id.'">
					<input type="hidden" id="af_site" value="'.$this->current_site_id.'">
					<div class="list-group" id=\'act_feed\'>';
									
									
									
									
				/*					
				foreach($feed_details_array as $key => $value) { 
						if($value['type']=='workout folder') {
							$fetch_field = 'folder_title';
							$fetch_condtn	= 'folder_status=0 and id='.$value['type_id'];
							$type_dtl = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders',$fetch_field,$fetch_condtn);
						} else if($value['type']=='assigned') {
							$fetch_field = 'wkout_title';
							$fetch_condtn	= 'wkout_id='.$value['type_id'];
							$type_dtl = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
						} else if($value['type']=='workout plan' && $value['action']=='shared') {
							$fetch_field = 'wkout_title';
							$fetch_condtn	= 'wkout_id='.$value['type_id'];
							$type_dtl = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
						}
						
					$feed_details.= '<p  class="list-group-item feed-item">
						<span class="badge">'.Helper_Common::time_ago($value['created_date']).'</span>
						<i class="fa fa-fw '.$icon_array[$value['type']].'"></i> 
						<a href= "#" data-toggle="modal" data-target="#userModal"> '.$data['user_name'].'</a> 
						<strong>'.$value['action'].'</strong> ' 
						.$value['type'];
					if(isset($type_dtl) && count($type_dtl)>0){  
						$feed_details.= '<a href="#">"'.$type_dtl[0][$fetch_field].'"</a>';
					}
					$feed_details.= '</p>';
				}
				*/
				
				foreach($feed_details_array as $key => $value) {
					//print_r($value);
					$case = $value['type']."-".$value['action'];
					
					$type = ($value["type"]=="assigned")?"workout plan":$value["type"];
					
					//echo $case."<br>";
					$string = "";
					//$string .= $value["id"].'#'.$case;
					$string .= '<p class="list-group-item feed-item">';
					if(isset($icon_array[$value['type']]) && $icon_array[$value['type']]!='') $string .= '<i class="fa fa-fw '.$icon_array[$value['type']].'"></i> ';
					$string .= '<span class="badge">'.Helper_Common::time_ago($value['created_date']).'</span>';
					
					if($user_id==$value["user"]){
						$string .= '<a href="javascript:void(0);" onclick="showUserModel(\''.$user_id.'\',0)">You</a> ';
					}else{
						$fetch_field = 'concat(user_fname," ",user_lname) as name';	$fetch_condtn	= 'id='.$value['user'];
						$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('users',$fetch_field,$fetch_condtn);
						if($result){
							$result = $result[0];
							$string .= '<a href="javascript:void(0);" onclick="showUserModel(\''.$value["user"].'\',0)">'.$result["name"].'</a> ';
						}
					}
					
					$string .= '<strong>'.$value['action'].' </strong>';
					//$string .= $value["type"].' ';
					$string .= $type.' ';
					
					
					switch($case){
						case "-logged-out":
							$string .= date("d M Y H:i:s a T",strtotime($value['created_date']));
							break;
						case "account-logged-out":
							$string .= date("d M Y H:i:s a T",strtotime($value['created_date']));
							break;
						case "account-logged-in":
							$string .= date("d M Y H:i:s a T",strtotime($value['created_date']));
							break;
						case "sample workout plan-copied":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$jsondata = json_decode($value['json_data']);
								if($jsondata){
									$jstxt = trim($jsondata);
									$string .= $jstxt;
								}
								//$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
								$string .= ' <a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a> ';
							}
							break;
						case "sample workout plan-created":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_sample_id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_sample_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								//$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a> ';
								$jsondata = json_decode($value['json_data']);
								if($jsondata){
									$jstxt = trim($jsondata->text);
									$string .= $jstxt;
									if($jstxt=="from workout plan"){
										$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$jsondata->wkoutid;
										$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
										if(isset($result) && count($result)>0){
											$string .= ' <a href="javascript:void(0);" onclick="viewwkout(\''.$jsondata->wkoutid.'\')">"'.$result[0][$fetch_field].'"</a>';
										}
									}else if($jstxt=="from shared workout plan"){
										$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_share_id='.$jsondata->wkoutid;
										$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_share_gendata',$fetch_field,$fetch_condtn);
										if(isset($result) && count($result)>0){
											$string .= ' <a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
										}
									}
								}
							}
							break;
						case "workout journal-created":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
							break;
						case "workout journal-canceled":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
							break;
						case "journal-edited":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_log_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout folder-created":
							$fetch_field = 'folder_title';	$fetch_condtn	= 'folder_status=0 and id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout folder-moved":
							$fetch_field = 'folder_title';	$fetch_condtn	= 'folder_status=0 and id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout folder-edited":
							$fetch_field = 'folder_title';	$fetch_condtn	= 'folder_status=0 and id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout folder-copied":
							
							break;
						case "workout folder-logged":
							break;
						case "workout folder-deleted":
							$fetch_field = 'folder_title';	$fetch_condtn	= 'folder_status=0 and id='.$value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-printed":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-edited":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-deleted":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-created":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-shared":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
								$string .= ' to ';
								$jsondata = json_decode($value['json_data']);
								if($jsondata){
									$subscriber = array();
									$str = "";
									if(count($jsondata)>1){
										$i=0;
										$str = "$str and <a href='javascript:void(0);' onclick='show_others(\"".implode(",",$jsondata)."\")'>".count($jsondata)." users</a>";
									}else{
										foreach($jsondata as $k=>$v){
											$jsonuser = Model::instance('Model/admin/shareworkout')->getuserdetails($v);
											$jsonuser = $jsonuser[0];
											$str .= "<a href='javascript:void(0);' onclick='viewusers($v)'>";
											$str .= $jsonuser["user_fname"].' '.$jsonuser['user_lname']."</a>, ";
										}
										$str = substr($str,0,-2);
									}
									$string .= $str;
								}
							}
							break;
						case "tag-created":
							$fetch_field = 'tag_title';	$fetch_condtn	= 'tag_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "workout plan-tagged":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
								$string .= ' with ';
								$jsondata = json_decode($value['json_data']);
								if($jsondata){
									$subscriber = array();
									$str = "";
									foreach($jsondata as $k=>$v){
										//print_r($v); exit;
										//$v = implode(',',$v);
										$jsontag = Model::instance('Model/admin/shareworkout')->gettagdetails($v);
										$jsontag = $jsontag[0];
										$str .= '<a href="javascript:void(0);" >"';
										$str .= $jsontag["tag_title"].'"</a>, ';
									}
									$str = substr($str,0,-2);
									$string.= $str;
								}
							}
							break;
						case "tag-removed":
							$fetch_field = 'tag_title';	$fetch_condtn	= 'tag_id='.$value['type_id'];	$tag_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('tag',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" >"'.$result[0][$fetch_field].'"</a>';
								$jsondata = json_decode($value['json_data']);
								if($jsondata){
									$fetch = 'wkout_title';
									$condtn	= 'wkout_id='.$jsondata[0];
									$wkout_id = $jsondata[0];
									$results = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch,$condtn);
									$string.= " from workout plan <a href='javascript:void(0);' onclick='viewwkout(".$jsondata[0].")'>";
									$string.= '"'.$results[0]["wkout_title"].'"';
									$string.= "</a>";
								}
							}
							break;
						case "assigned-assigned":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "assigned-edited":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "assigned-copied":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						case "assigned-logged":
							$fetch_field = 'wkout_title';	$fetch_condtn	= 'wkout_id='.$value['type_id'];	$wkout_id = $value['type_id'];
							$result = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata',$fetch_field,$fetch_condtn);
							if(isset($result) && count($result)>0){
								$string .= '<a href="javascript:void(0);" onclick="viewwkout(\''.$wkout_id.'\')">"'.$result[0][$fetch_field].'"</a>';
							}
							break;
						default:
							break;
					}
					
					$string.="</p>";
					//echo $string;
					$feed_details.= $string;
				}
				
				$feed_details.=	'</div>
				<button class=\'btn btn-primary\' onclick=\'show_more()\' id=\'show_btn\'>Show More</button>
				</div>';
				$data['feed_details'] = $feed_details;
			} else {
				$data['feed_details'] = '<div class="panel-body">No feed found</div>';
			}
			if(isset($data) && count($data)>0) {
				$data['success'] = true;
			} else {
				$data['success'] = false;
			}
			
			$this->response->body(json_encode($data));
		} 
	}
	public function action_changeManagerStatus() {
		if ($this->request->is_ajax()) $this->auto_render = FALSE;
		$usermodel = ORM::factory('admin_user');
		if(isset($_POST['userId']) && $_POST['userId']!='' && isset($_POST['statusId']) && $_POST['statusId']!='') {
			$updateStr	= 'status_id='.$_POST['statusId'];
			$condtn		= 'user_id='.$_POST['userId'];
			$usermodel->updateManagerUserStatus($updateStr,$condtn);
			$data['success'] = true;
			$data['message'] = 'Status updated successfully';
		} 
		$this->response->body(json_encode($data));
	}
	function action_get_userlist_pdf()
	{   
	    $user = ORM::factory('admin_user');	
		$authid = $this->globaluser->pk();		
		$roleid = $this->request->param('id');
		$siteid = Session::instance()->get('current_site_id');
		$sitename = Session::instance()->get('current_site_name');
		$rolename = $user->user_role_load_by_id($roleid);
		if($rolename == 'register'){$rolename = 'Subscriber';}else{$rolename = $rolename;}
		$title = '"'.$sitename.'" '.$rolename.'s List Report';
		$subscribermodel		= ORM::factory('admin_subscriber');
     	//$content=$user->getUserDetails('','',3);
        $content=$subscribermodel->get_site_subscribers($authid, $siteid, $roleid);	//echo '<pre>';print_r($content);echo '</pre>';	exit;
		$contents = $this->_Gst_user_report_content($content,$title);		
		$contents = $this->_generatePDF($contents,$title);
	}
	function _Gst_user_report_content($content,$title)
	{
		if(empty($content)) {
			return false;
		}
		$msg = '<h2>'.$title.'</h2><table cellspacing="8" cellpadding="5" border="1" width="100%">';
		
		$msg .= '<tr><td style="width:20%; padding-left:5px;">First Name</td><td style="width:20%; padding-left:5px;">Last Name</td><td style="width:20%; padding-left:5px;">Email</td><td style="width:20%; padding-left:5px;">Phone</td><td style="width:20%; padding-left:5px;">Status</td><td style="width:20%; padding-left:5px;">Date Created</td></tr>';
		foreach($content as $con) { 
			$msg .= '<tr>';								 
			$msg .= '<td style="padding-left:5px;">'.$con['user_fname'].'</td>';
			$msg .= '<td style="padding-left:5px;">'.$con['user_lname'].'</td>';
			$msg .= '<td style="padding-left:5px;">'.$con['user_email'].'</td>';
			$msg .= '<td style="padding-left:5px;">'.$con['user_mobile'].'</td>';
			$msg .= '<td style="padding-left:5px;">'.$con['userstatus'].'</td>';
			$msg .= '<td style="padding-left:5px;">'.$con['date_created'].'</td>';
			
			
			$msg .= '</tr>';
		}
		$msg .= '</table>';
		return $msg;
	}
	public function _generatePDF($message, $title)
	{
		$this->auto_render = FALSE;	
		include("./plugins/mpdf60/mpdf.php");

		$mpdf=new mPDF('c','A4','','',20,20,20,20,10,10); 
		$mpdf->debug=true;
		$mpdf->SetTitle($title);				
		$mpdf->SetDisplayMode('fullwidth');				
		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
		$mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
		$stylesheet = file_get_contents('plugins/mpdf60/mpdfstyletables.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text				
		$mpdf->WriteHTML($message,2);
		$file_name = 'MTA-'.$title.'-'.date('Ymdhis');
		$mpdf->Output($file_name.'.pdf','I');

		exit;
		return $file_name;
	}
	public function action_get_user_report_as_excel()
	{	
    	$authid = $this->globaluser->pk();		
		$roleid = $this->request->param('id');
		$siteid = Session::instance()->get('current_site_id');
		$user = ORM::factory('admin_user');
		$sitename = Session::instance()->get('current_site_name');
		$rolename = $user->user_role_load_by_id($roleid);
		$subscribermodel		= ORM::factory('admin_subscriber');
		$online_report=$subscribermodel->get_site_subscribers($authid, $siteid, $roleid);
		include("./plugins/phpexcel/Classes/PHPExcel.php");
		$objPHPExcel = new PHPExcel();	
		$serialnumber=0;
		//Set header with temp array
		$tmparray =array("S.No","First Name","Last Name","Email","Phone Number","Status","Created On");
		//take new main array and set header array in it.
		$sheet =array($tmparray);
		$tem=array();
		array_unshift($sheet,$tem);
		foreach($online_report as $onlinereport)
		{
		$tmparray =array();
		$serialnumber = $serialnumber + 1;
		array_push($tmparray,$serialnumber);
		$first_name = $onlinereport['user_fname'];
		array_push($tmparray,$first_name);	
		$last_name = $onlinereport['user_lname'];
		array_push($tmparray,$last_name);	
		$email = $onlinereport['user_email'];
		array_push($tmparray,$email);	
		$mobile_phone = $onlinereport['user_mobile'];
		array_push($tmparray,$mobile_phone);
		$status = $onlinereport['userstatus'];
		array_push($tmparray,$status);
		$date_created = $onlinereport['date_created'];
		array_push($tmparray,$date_created);
		array_push($sheet,$tmparray);
		}
		foreach($sheet as $row => $columns) {
		foreach($columns as $column => $data) {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row + 1, $data);     
		}
		}
		header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="userlist.xlsx"');
		header('Cache-Control: max-age=0');
		$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
		$sitename = Session::instance()->get('current_site_name');
		$rolename = $user->user_role_load_by_id($roleid);
		if($rolename == 'register'){$rolename = 'Subscriber';}else{$rolename = $rolename;}
		$file_title = '"'.$sitename.'" '.$rolename.'s List Report';
		$objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;

	}
	function action_report_email()
	{   
	    $user_detail = Auth::instance()->get_user();
		//$to_email = $this->input->post('email_address');
		$to_email=$this->request->post('email_address');
		//$to_email ='srinivasan@versatile-soft.com';
		$user = ORM::factory('admin_user');	
		$roleid = $this->request->post('roleid');
		$authid = $this->globaluser->pk();
		$siteid = Session::instance()->get('current_site_id');
		$sitename = Session::instance()->get('current_site_name');
		$rolename = $user->user_role_load_by_id($roleid);
		if($rolename == 'register'){$rolename = 'Subscriber';}else{$rolename = $rolename;}
		$subscribermodel		= ORM::factory('admin_subscriber');
		$content=$subscribermodel->get_site_subscribers($authid, $siteid, $roleid);
		$config = Kohana::$config->load('emailsetting');		
		$from_address= $user_detail->user_email;
		$from_name= $sitename;
		$title = '"'.$sitename.'" '.$rolename.'s List Report';
		$report = $this->_Gst_user_report_content($content,$title);	
		$subject = '"'.$sitename.'" '.$rolename.'s List Report';
		if(!$report) {
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
	
	
}
