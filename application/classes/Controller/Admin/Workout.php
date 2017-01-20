<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Workout extends Controller_Admin_Website
{
   public function before()
   {
      parent::before();
      $user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
      Session::instance()->set('user_from', $user_from);
   }
   public function __construct(Request $request, Response $response)
   {
      parent::__construct($request, $response);
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
   public function action_edituser()
   {
      $userid = $this->request->param('id');
      if (!is_numeric($userid) || $userid == "" || $userid == "0") {
         $this->redirect("admin/subscriber/browse");
      }
      $adminuser             = ORM::factory('admin_user');
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
            $user->ip_address      = $_SERVER['REMOTE_ADDR'];
            $user->activation_code = md5(microtime() . rand());
            $user->user_dob        = date("Y-m-d", strtotime($this->request->post('birthday_year') . '-' . $this->request->post('birthday_month') . '-' . $this->request->post('birthday_day')));
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
   public function action_browse()
   {
      // print_r($_POST);die();
      $usermodel             = ORM::factory('admin_subscriber');
      $adminworkoutmodel     = ORM::factory('admin_workouts');
      $adminusermodel        = ORM::factory('admin_user');
      $workoutModel          = ORM::factory('workouts');
      $activityModel         = ORM::factory('activityfeed');
      $this->template->title = 'Browse Workout Records';
      $this->render();
      $wkoutf_val  = $futured_val = $autosearch = '';
      $sortby      = '3'; //most recent 
      $wkoutfilter = array();
      if ($this->request->method() == HTTP_Request::POST) {
         $sortby            = $this->request->post('fsortby');
         $futured_val       = $this->request->post('futured_filter');
         $autosearch        = $this->request->post('autosearch');
         $wkoutfilter       = $this->request->post('wkoutfilter');
         $method            = $this->request->post('f_method');
         $save_edit         = $this->request->post('save_edit');
         $parentFolderArray = array();
         $datevalue         = Helper_Common::get_default_datetime();
         $parentFolderId    = urldecode($this->request->param('id'));
         if (!empty($wkoutfilter)) {
            foreach ($wkoutfilter as $key => $value) {
               $wkoutf_val .= $value . ',';
            }
            $wkoutf_val = rtrim($wkoutf_val, ',');
         }
         if (!empty($method) && trim($method) == 'add_workout') {
            if ($save_edit == '2')
               $this->redirect('admin/workout/browse/');
            $inputArray['wkout_group']      = '0';
            $inputArray['wkout_title']      = $this->request->post('wkout_title');
            $inputArray['wkout_color']      = $this->request->post('wrkoutcolor');
            $inputArray['wkout_order']      = '1';
            $inputArray['user_id']          = $this->globaluser->pk();
            $inputArray['site_id']          = $this->current_site_id;
            $inputArray['status_id']        = '1';
            $inputArray['access_id']        = $this->globaluser->user_access;
            $inputArray['wkout_focus']      = $this->request->post('wkout_focus');
            $inputArray['wkout_poa']        = '0';
            $inputArray['wkout_poa_time']   = '0';
            $inputArray['parent_folder_id'] = $parentFolderId;
            $inputArray['created_date']     = $datevalue;
            $inputArray['modified_date']    = $datevalue;
            $_POST['wkout_id']              = $adminworkoutmodel->insertWorkoutDetails($inputArray);
            foreach ($_POST['exercise_title_new'] as $keys => $values) {
               if (!empty($values) && trim($values) != '') {
                  $res = $workoutModel->addWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
               }
            }
            /******************* Activity Feed *********************/
            $activity_feed["user"]        = $this->globaluser->pk();
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["feed_type"]   = '2';
            $activity_feed["action_type"] = '1';
            $activity_feed["type_id"]     = $_POST['wkout_id'];
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
            $this->session->set('success', 'Successfully <b>' . $inputArray['wkout_title'] . '</b> Workout Record was added!!!');
            if ($save_edit == '1')
               $this->redirect('admin/workout/edit/' . $_POST['wkout_id'] . '?act=edit');
         }
      }
      $userid                                      = $this->globaluser->pk(); //Current Loggedin users			
      $result                                      = $adminworkoutmodel->get_user_created_tags($userid, 2);
      $usertags                                    = $adminworkoutmodel->get_user_tags($userid);
      $this->template->content->usertags           = $result;
      $roleid                                      = $adminusermodel->user_role_load_by_name('Register');
      $siteid                                      = $this->current_site_id;
      $this->template->content->focusRecord        = $adminworkoutmodel->getAllFocus();
      $role                                        = Helper_Common::get_role("manager");
      $manager                                     = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->manager            = $manager;
      $role                                        = Helper_Common::get_role("trainer");
      $trainer                                     = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->trainer            = $trainer;
      $role                                        = Helper_Common::get_role("register");
      $subscriber                                  = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->subscriber_details = $subscriber;
      $this->template->content->status             = $adminworkoutmodel->get_status();
      $this->template->content->sortby             = $sortby;
      $this->template->content->wkoutfilter        = $wkoutfilter; // print_r($wkoutfilter) ;
      $this->template->content->futured_val        = $futured_val; // print_r($wkoutfilter) ;
      $this->template->content->searchval          = $_POST;
      if ($wkoutf_val == '') {
         $wkoutf_val = 1;
      }
      $lim        = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall    = $adminworkoutmodel->getWorkoutDetailsByUser($userid, '', '', $wkoutf_val, $sortby, $futured_val, $autosearch, '', '');
      $cnt        = count($dataall);
      $pagination = pagination::factory(array(
         'total_items' => $cnt,
         'items_per_page' => $lim,
         //'auto_hide'         => TRUE,
         'first_page_in_url' => TRUE
      ));
      if (isset($_REQUEST['page'])) {
         $page_number = $_REQUEST['page'];
      } else {
         $page_number = 1;
      }
      $offset = $lim * ($page_number - 1);
      // Pass controller and action names explicitly to $pagination object
      $pagination->route_params(array(
         'controller' => $this->request->controller(),
         'action' => $this->request->action()
      ));
      $this->template->content->workout_details = $adminworkoutmodel->getWorkoutDetailsByUser($userid, '', '', $wkoutf_val, $sortby, $futured_val, $autosearch, $pagination->items_per_page, $offset);
      $this->template->content->workout_count   = $cnt;
      $this->template->content->pagination      = $pagination;
      $this->template->content->lim             = $lim;
      $this->template->css                      = array(
         'assets/plugins/iCheck/square/green.css'
      );
      $this->template->js_bottom                = array(
         'assets/plugins/iCheck/icheck.js'
      );
   }
   public function action_edit()
   {
      $workoutModel             = ORM::factory('workouts');
      $adminworkoutModel        = ORM::factory('admin_workouts');
      $workid                   = urldecode($this->request->param('id'));
      $datevalue                = Helper_Common::get_default_datetime();
      /******************* Activity Feed *********************/
      /** activity feed starts **/
      $activity_feed            = array();
      $activity_feed["user"]    = $this->globaluser->pk();
      $activity_feed["site_id"] = $this->current_site_id;
      $folderId                 = '';
      /** activity feed ends **/
      /******************* Activity Feed *********************/
      if (empty($workid))
         $this->redirect('admin/workout/browse');
      $this->render();
      if (HTTP_Request::POST == $this->request->method()) {
         $method                   = $this->request->post('f_method');
         $save_edit                = $this->request->post('save_edit');
         $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
         if ($save_edit == 2) {
            /******************* Activity Feed *********************/
            $activity_feed["feed_type"]   = '2';
            $activity_feed["action_type"] = '44';
            $activity_feed["type_id"]     = $workid;
            $activity_feed["json_data"]   = json_encode('without saving');
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
            $this->redirect('admin/workout/browse/');
         }
         if (isset($_POST['wrkoutname_hidden']) && !empty($_POST['wrkoutname_hidden']) && !empty($workid) && $method == '') {
            $updateArray['wkout_title'] = $wkoutTitle = $this->request->post('wrkoutname_hidden');
            $updateArray['wkout_color'] = $this->request->post('wrkoutcolor');
            $updateArray['wkout_focus'] = $this->request->post('wkout_focus');
            $workoutModel->updateWkoutDetails($updateArray, $workid);
            $added                        = 0;
            /******************* Activity Feed *********************/
            $activity_feed["feed_type"]   = '2';
            $activity_feed["action_type"] = '26';
            $activity_feed["type_id"]     = $workid;
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
            if (isset($_POST['exercise_title']) && count($_POST['exercise_title']) > 0) {
               foreach ($_POST['exercise_title'] as $keys => $values) {
                  if (!empty($values) && trim($values) != '' && isset($_POST['goal_remove'][$keys]) && empty($_POST['goal_remove'][$keys])) {
                     $goal_id                        = $keys;
                     $updateArray                    = array();
                     $updateArray['goal_order']      = (($_POST['goal_order'][$keys]) ? $_POST['goal_order'][$keys] : '0');
                     $updateArray['title']           = $_POST['exercise_title'][$keys];
                     $updateArray['goal_title_self'] = '1';
					 $updateArray['goal_unit_id']   	= 0;
					 if (isset($_POST['exercise_unit'][$keys])){
						$exerciseUnitArray = explode('_',$_POST['exercise_unit'][$keys]);
						if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
							$updateArray['goal_title_self'] = 0;
							$updateArray['goal_unit_id']	= $exerciseUnitArray[1];
						}else{
							$updateArray['goal_unit_id']	= 0;
						}
					 }
                     $updateArray['goal_resist']    = $_POST['exercise_resistance'][$keys];
                     $updateArray['goal_resist_id'] = $_POST['exercise_unit_resistance'][$keys];
                     $updateArray['goal_reps']      = $_POST['exercise_repetitions'][$keys];
                     $updateArray['goal_time_hh']   = $updateArray['goal_time_mm'] = $updateArray['goal_time_ss'] = '';
                     if (!empty($_POST['exercise_time'][$keys])) {
                        $timeArray                   = explode(":", $_POST['exercise_time'][$keys]);
                        $updateArray['goal_time_hh'] = $timeArray['0'];
                        $updateArray['goal_time_mm'] = $timeArray['1'];
                        $updateArray['goal_time_ss'] = $timeArray['2'];
                     }
                     $updateArray['goal_dist']     = $_POST['exercise_distance'][$keys];
                     $updateArray['goal_dist_id']  = $_POST['exercise_unit_distance'][$keys];
                     $updateArray['goal_rate']     = $_POST['exercise_rate'][$keys];
                     $updateArray['goal_rate_id']  = $_POST['exercise_unit_rate'][$keys];
                     $updateArray['goal_int_id']   = $_POST['exercise_innerdrive'][$keys];
                     $updateArray['goal_angle']    = $_POST['exercise_angle'][$keys];
                     $updateArray['goal_angle_id'] = $_POST['exercise_unit_angle'][$keys];
                     $updateArray['goal_rest_mm']  = $updateArray['goal_rest_ss'] = '';
                     if (!empty($_POST['exercise_rest'][$keys])) {
                        $resttimeArray               = explode(":", $_POST['exercise_rest'][$keys]);
                        $updateArray['goal_rest_mm'] = $resttimeArray['0'];
                        $updateArray['goal_rest_ss'] = $resttimeArray['1'];
                     }
                     $goal_remarks                  = $_POST['exercise_remark'][$keys];
                     $updateArray['goal_remarks']   = (!empty($goal_remarks) ? strip_tags($goal_remarks) : '');
                     $updateArray['primary_time']   = (($_POST['primary_time'][$keys]) ? $_POST['primary_time'][$keys] : '0');
                     $updateArray['primary_dist']   = (($_POST['primary_dist'][$keys]) ? $_POST['primary_dist'][$keys] : '0');
                     $updateArray['primary_reps']   = (($_POST['primary_reps'][$keys]) ? $_POST['primary_reps'][$keys] : '0');
                     $updateArray['primary_resist'] = (($_POST['primary_resist'][$keys]) ? $_POST['primary_resist'][$keys] : '0');
                     $updateArray['primary_rate']   = (($_POST['primary_rate'][$keys]) ? $_POST['primary_rate'][$keys] : '0');
                     $updateArray['primary_angle']  = (($_POST['primary_angle'][$keys]) ? $_POST['primary_angle'][$keys] : '0');
                     $updateArray['primary_rest']   = (($_POST['primary_rest'][$keys]) ? $_POST['primary_rest'][$keys] : '0');
                     $updateArray['primary_int']    = (($_POST['primary_int'][$keys]) ? $_POST['primary_int'][$keys] : '0');
					 if(is_numeric($keys) && empty($start_journal)){
						$workoutModel->updateExerciseSet($updateArray, $goal_id);
					 }else{
						$updateArray['goal_title'] = $updateArray['title'];
						$updateArray['wkout_id']   = $this->request->post('wkout_id');
						$res                       = $workoutModel->addWorkoutSetFromExistworkout($updateArray, $this->globaluser->pk(), $workid);
						$_POST['goal_order'][$res] = $_POST['goal_order'][$keys];
						$_POST['goal_remove'][$res]= $_POST['goal_remove'][$keys];
						unset($_POST['goal_order'][$keys]);
						unset($_POST['goal_remove'][$keys]);
					 }
                  }
               }
            }
            if (isset($_POST['goal_order']) && count($_POST['goal_order']) > 0) {
               foreach ($_POST['goal_order'] as $keys => $values) {
                  $workoutModel->updateGoalOrder($values, $workid, $keys, $this->globaluser->pk());
               }
            }
            if (isset($_POST['goal_remove']) && count($_POST['goal_remove']) > 0) {
               foreach ($_POST['goal_remove'] as $keys => $values) {
                  if ($values != '0') {
                     $workoutModel->doDeleteForExerciseSetsById('exerciseSet', $keys, $workid, $this->globaluser->pk());
                  }
               }
            }
            $this->session->set('success', 'Successfully <b>' . $wkoutTitle . '</b> Workout Record was updated!!!');
            if ($save_edit == '1')
               $this->redirect('admin/workout/edit/' . $workid . '?act=edit');
            else
               $this->redirect('admin/workout/browse/');
         }
      }
      $this->template->content->workoutRecord = $adminworkoutModel->getworkoutById('', $workid);
      if (empty($this->template->content->workoutRecord))
         $this->redirect('admin/workout/browse');
      $this->template->content->focusRecord    = $workoutModel->getAllFocus();
      $exerciseRecord = $workoutModel->getExerciseSet($workid);
      $this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord);
      $this->template->title                   = 'Edit Workout Records';
      $this->template->content->popupAct       = (!empty($_GET['act']) ? $_GET['act'] : '');
      $this->template->content->save           = (!empty($_GET['p']) ? $_GET['p'] : '');
      $this->template->content->workoutId      = trim($workid);
      $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
      $this->template->content->colorsRecord   = $workoutModel->getColors();
   }
   public function action_sampleedit()
   {
      $dexe = Helper_Common::hasSiteAccess($this->current_site_id);
      if ($dexe["sample_workouts"] == 0) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $workoutModel             = ORM::factory('workouts');
      $adminworkoutModel        = ORM::factory('admin_workouts');
      $workid                   = urldecode($this->request->param('id'));
      $datevalue                = Helper_Common::get_default_datetime();
      /******************* Activity Feed *********************/
      /** activity feed starts **/
      $activity_feed            = array();
      $activity_feed["user"]    = $this->globaluser->pk();
      $activity_feed["site_id"] = $this->current_site_id;
      /** activity feed ends **/
      /******************* Activity Feed *********************/
      $this->render();
      if (HTTP_Request::POST == $this->request->method()) {
         $method                   = $this->request->post('f_method');
		 $default_status		   = $this->request->post('d');
         $save_edit                = $this->request->post('save_edit');
         $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
         if ($save_edit == 2) {
            /******************* Activity Feed *********************/
            $activity_feed["feed_type"]   = '15';
            $activity_feed["action_type"] = '44';
            $activity_feed["type_id"]     = $workid;
            $activity_feed["json_data"]   = json_encode('without saving');
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
            $this->redirect('admin/workout/sample/'.($default_status ==1 ? '?d='.$default_status : ''));
         }
         if (isset($_POST['wrkoutname_hidden']) && !empty($_POST['wrkoutname_hidden']) && !empty($workid) && $method == '') {
            $updateArray['wkout_title'] = $wkoutTitle = $this->request->post('wrkoutname_hidden');
            $updateArray['wkout_color'] = $this->request->post('wrkoutcolor');
            $updateArray['wkout_focus'] = $this->request->post('wkout_focus');
            $workoutModel->updateSampleWkoutDetails($updateArray, $workid);
            $added                        = 0;
            /******************* Activity Feed *********************/
            $activity_feed["feed_type"]   = '15';
            $activity_feed["action_type"] = '3';
            $activity_feed["type_id"]     = $workid;
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
            if (isset($_POST['exercise_title']) && count($_POST['exercise_title']) > 0) {
               foreach ($_POST['exercise_title'] as $keys => $values) {
                  if (!empty($values) && trim($values) != '' && isset($_POST['goal_remove'][$keys]) && empty($_POST['goal_remove'][$keys])) {
                     $goal_id                        = $keys;
                     $updateArray                    = array();
                     $updateArray['goal_order']      = (($_POST['goal_order'][$keys]) ? $_POST['goal_order'][$keys] : '0');
                     $updateArray['title']           = $_POST['exercise_title'][$keys];
                     $updateArray['goal_title_self'] = '1';
					 $updateArray['goal_unit_id']   	= 0;
					 if (isset($_POST['exercise_unit'][$keys])){
						$exerciseUnitArray = explode('_',$_POST['exercise_unit'][$keys]);
						if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
							$updateArray['goal_title_self'] = 0;
							$updateArray['goal_unit_id']	= $exerciseUnitArray[1];
						}else{
							$updateArray['goal_unit_id']	= 0;
						}
					 }
                     $updateArray['goal_resist']    = $_POST['exercise_resistance'][$keys];
                     $updateArray['goal_resist_id'] = $_POST['exercise_unit_resistance'][$keys];
                     $updateArray['goal_reps']      = $_POST['exercise_repetitions'][$keys];
                     $updateArray['goal_time_hh']   = $updateArray['goal_time_mm'] = $updateArray['goal_time_ss'] = '';
                     if (!empty($_POST['exercise_time'][$keys])) {
                        $timeArray                   = explode(":", $_POST['exercise_time'][$keys]);
                        $updateArray['goal_time_hh'] = $timeArray['0'];
                        $updateArray['goal_time_mm'] = $timeArray['1'];
                        $updateArray['goal_time_ss'] = $timeArray['2'];
                     }
                     $updateArray['goal_dist']     = $_POST['exercise_distance'][$keys];
                     $updateArray['goal_dist_id']  = $_POST['exercise_unit_distance'][$keys];
                     $updateArray['goal_rate']     = $_POST['exercise_rate'][$keys];
                     $updateArray['goal_rate_id']  = $_POST['exercise_unit_rate'][$keys];
                     $updateArray['goal_int_id']   = $_POST['exercise_innerdrive'][$keys];
                     $updateArray['goal_angle']    = $_POST['exercise_angle'][$keys];
                     $updateArray['goal_angle_id'] = $_POST['exercise_unit_angle'][$keys];
                     $updateArray['goal_rest_mm']  = $updateArray['goal_rest_ss'] = '';
                     if (!empty($_POST['exercise_rest'][$keys])) {
                        $resttimeArray               = explode(":", $_POST['exercise_rest'][$keys]);
                        $updateArray['goal_rest_mm'] = $resttimeArray['0'];
                        $updateArray['goal_rest_ss'] = $resttimeArray['1'];
                     }
                     $goal_remarks                  = $_POST['exercise_remark'][$keys];
                     $updateArray['goal_remarks']   = (!empty($goal_remarks) ? strip_tags($goal_remarks) : '');
                     $updateArray['primary_time']   = (($_POST['primary_time'][$keys]) ? $_POST['primary_time'][$keys] : '0');
                     $updateArray['primary_dist']   = (($_POST['primary_dist'][$keys]) ? $_POST['primary_dist'][$keys] : '0');
                     $updateArray['primary_reps']   = (($_POST['primary_reps'][$keys]) ? $_POST['primary_reps'][$keys] : '0');
                     $updateArray['primary_resist'] = (($_POST['primary_resist'][$keys]) ? $_POST['primary_resist'][$keys] : '0');
                     $updateArray['primary_rate']   = (($_POST['primary_rate'][$keys]) ? $_POST['primary_rate'][$keys] : '0');
                     $updateArray['primary_angle']  = (($_POST['primary_angle'][$keys]) ? $_POST['primary_angle'][$keys] : '0');
                     $updateArray['primary_rest']   = (($_POST['primary_rest'][$keys]) ? $_POST['primary_rest'][$keys] : '0');
                     $updateArray['primary_int']    = (($_POST['primary_int'][$keys]) ? $_POST['primary_int'][$keys] : '0');
					 if(is_numeric($keys)){
						$workoutModel->updateSampleExerciseSet($updateArray, $goal_id);
					 }else{
						$updateArray['goal_title'] = $updateArray['title'];
						$updateArray['wkout_id']   = $this->request->post('wkout_id');
						$res                       = $workoutModel->addWorkoutSetFromExistSampleworkout($updateArray, $this->globaluser->pk(), $workid);
						$_POST['goal_order'][$res] = $_POST['goal_order'][$keys];
						$_POST['goal_remove'][$res]= $_POST['goal_remove'][$keys];
						unset($_POST['goal_order'][$keys]);
						unset($_POST['goal_remove'][$keys]);
					 }
                  }
               }
            }
            if (isset($_POST['goal_order']) && count($_POST['goal_order']) > 0) {
               foreach ($_POST['goal_order'] as $keys => $values) {
                  $workoutModel->updateSampleGoalOrder($values, $workid, $keys, $this->globaluser->pk());
               }
            }
            if (isset($_POST['goal_remove']) && count($_POST['goal_remove']) > 0) {
               foreach ($_POST['goal_remove'] as $keys => $values) {
                  if ($values != '0') {
                     $workoutModel->doDeleteForExerciseSetsById('sampleexerciseSet', $keys, $workid, $this->globaluser->pk());
                  }
               }
            }
            $this->session->set('success', 'Successfully <b>' . $wkoutTitle . '</b> Workout Record was updated!!!');
            if ($save_edit == '1')
               $this->redirect('admin/workout/sampleedit/' . $workid . '?act=edit'.($default_status ==1 ? '&d='.$default_status : ''));
            else
               $this->redirect('admin/workout/sample/'.($default_status ==1 ? '?d='.$default_status : ''));
         }
      }
      $this->template->content->workoutRecord = $adminworkoutModel->getsampleworkoutById('', $workid); //$this->globaluser->pk()
      if (empty($this->template->content->workoutRecord))
         $this->redirect('admin/workout/sample/'.(isset($_GET['d']) && $_GET['d'] ==1 ? '?d='.$_GET['d'] : ''));
      $this->template->content->focusRecord    = $workoutModel->getAllFocus();
	  $exerciseRecord = $adminworkoutModel->getExerciseSampleSet($workid);
      $this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_sample_id');
      $this->template->title                   = 'Edit Sample Workout Records';
      $this->template->content->popupAct       = (!empty($_GET['act']) ? $_GET['act'] : '');
      $this->template->content->save           = (!empty($_GET['p']) ? $_GET['p'] : '');
      $this->template->content->workoutId      = trim($workid);
	  $this->template->content->default_status = (isset($_GET["d"])) ? $_GET["d"] : 0;
      $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
      $this->template->content->colorsRecord   = $workoutModel->getColors();
   }
   public function action_cpytosample()
   {
      $workoutModel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $method   = $this->request->post('f_method');
         $default  = $this->request->post('default_status');
         $wkoutids = $this->request->post('workouts');
         if (!empty($method) && (trim($method) == 'mywkout' || trim($method) == 'mysharedwkout')) {
            $shared_by = $this->globaluser->pk();
            foreach ($wkoutids as $k => $wkout_id) {
               $checkres = $workoutModel->checkSampleWkout($wkout_id, $method, $default);
               if (!$checkres) {
                  $res = $workoutModel->createSampleWkout($method, $shared_by, $this->current_site_id, $wkout_id, '', $default);
                  echo $res;
               }
            }
         }
         echo true;
      }
      echo false;
      exit;
   }
   public function action_sample()
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      $usermodel         = ORM::factory('admin_subscriber');
      $adminusermodel    = ORM::factory('admin_user');
      $this->render();
      $wkoutf_val  = $futured_val = $autosearch = '';
      $sortby      = '3'; //most recent
      $wkoutfilter = array();
      if (isset($_POST) && count($_POST) > 0) {
         $sortby      = $this->request->post('fsortby');
         $futured_val = $this->request->post('futured_filter');
         $autosearch  = $this->request->post('autosearch');
         $wkoutfilter = $this->request->post('wkoutfilter');
         $save_edit   = $this->request->post('save_edit');
         if (!empty($wkoutfilter)) {
            foreach ($wkoutfilter as $key => $value) {
               $wkoutf_val .= $value . ',';
            }
            $wkoutf_val = rtrim($wkoutf_val, ',');
         }
         $workoutModel      = $adminworkoutmodel;
         $activityModel     = ORM::factory('activityfeed');
         $method            = $this->request->post('f_method');
         $parentFolderArray = array();
         $datevalue         = Helper_Common::get_default_datetime();
         $parentFolderId    = urldecode($this->request->param('id'));
         if ($save_edit == '2')
            $this->redirect('admin/workout/sample/');
         if (!empty($method) && trim($method) == 'add_workout') {
            $inputArray['wkout_group']      = '0';
            $inputArray['wkout_title']      = $this->request->post('wkout_title');
            $inputArray['wkout_color']      = $this->request->post('wrkoutcolor');
            $inputArray['wkout_order']      = '1';
            $inputArray['user_id']          = $this->globaluser->pk();
            $inputArray['status_id']        = '1';
            $inputArray["site_id"]          = $this->current_site_id;
            $inputArray['access_id']        = $this->globaluser->user_access;
            $inputArray['wkout_focus']      = $this->request->post('wkout_focus');
            $inputArray['wkout_poa']        = '0';
            $inputArray['wkout_poa_time']   = '0';
            $inputArray['parent_folder_id'] = $parentFolderId;
            $inputArray['created_date']     = $datevalue;
            $inputArray['modified_date']    = $datevalue;
            $_POST['wkout_sample_id']       = $workoutModel->insertSampleWorkoutDetails($inputArray);
            /*********************Activiy Feed**********************/
            $activity_feed                  = array();
            $activity_feed["feed_type"]     = 15; // This get from feed_type table
            $activity_feed["action_type"]   = 1; // This get from action_type table  
            $activity_feed["type_id"]       = $_POST['wkout_sample_id'];
            $activity_feed["user"]          = $this->globaluser->pk();
            $activity_feed["site_id"]       = $this->current_site_id;
            Helper_Common::createActivityFeed($activity_feed);
            /*********************Activiy Feed**********************/
            foreach ($_POST['exercise_title_new'] as $keys => $values) {
               if (!empty($values) && trim($values) != '') {
                  $res = $workoutModel->addSampleWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
               }
            }
            $this->session->set('success', 'Successfully <b>' . $inputArray['wkout_title'] . '</b> Workout Record was added!!!');
            if ($save_edit == 1)
               $this->redirect('admin/workout/sampleedit/' . $_POST['wkout_sample_id'] . '/?act=edit');
         } else if (!empty($method) && (trim($method) == 'mywkout' || trim($method) == 'mysharedwkout')) {
            $wkoutids  = $this->request->post('workouts');
            $shared_by = $this->globaluser->pk();
            foreach ($wkoutids as $k => $wkout_id) {
               $checkres = $workoutModel->checkSampleWkout($wkout_id, $method);
               echo $wkout_id . "--" . $checkres . "<br>";
               exit;
               if (!$checkres) {
                  $res = $workoutModel->createSampleWkout($method, $shared_by, $this->current_site_id, $wkout_id, '', 0);
               }
            }
         }
      }
      if (Helper_Common::is_admin())
         $default = (isset($_GET["d"])) ? $_GET["d"] : 0;
      else if (Helper_Common::hasAccessBySampleWkouts($this->current_site_id) && !Helper_Common::is_admin())
         $default = 'all';
      else
         $default = '0';
      $userid                                      = $this->globaluser->pk(); //Current Loggedin users			
      $result                                      = $adminworkoutmodel->get_user_created_tags($userid, 2);
      $usertags                                    = $adminworkoutmodel->get_user_tags($userid);
      $this->template->content->usertags           = $result;
      $roleid                                      = $adminusermodel->user_role_load_by_name('Register');
      $usersession                                 = Session::instance()->get('auth_user');
      $siteid                                      = $this->current_site_id;
      $this->template->content->focusRecord        = $adminworkoutmodel->getAllFocus();
      $role                                        = Helper_Common::get_role("manager");
      $manager                                     = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $this->template->content->manager            = $manager;
      $role                                        = Helper_Common::get_role("trainer");
      $trainer                                     = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $this->template->content->trainer            = $trainer;
      $role                                        = Helper_Common::get_role("register");
      $subscriber                                  = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $subscriber                                  = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->subscriber_details = $subscriber;
      $this->template->content->focusRecord        = $adminworkoutmodel->getAllFocus();
      $this->template->content->status             = $adminworkoutmodel->get_status();
      $this->template->content->wkoutfilter        = $wkoutfilter;
      $this->template->content->futured_val        = $futured_val;
      $this->template->content->sortby             = $sortby;
      $this->template->content->searchval          = $_POST;
      if ($wkoutf_val == '') {
         $wkoutf_val = 1;
      }
      $lim        = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall    = $adminworkoutmodel->getSampleWorkoutDetails($default, $userid, '', $siteid, '', $wkoutf_val, $sortby, $futured_val, $autosearch, '', '');
      $cnt        = count($dataall);
      $pagination = pagination::factory(array(
         'total_items' => $cnt,
         'items_per_page' => $lim,
         //'auto_hide'         => TRUE,
         'first_page_in_url' => TRUE
      ));
      if (isset($_REQUEST['page'])) {
         $page_number = $_REQUEST['page'];
      } else {
         $page_number = 1;
      }
      $offset = $lim * ($page_number - 1);
      // Pass controller and action names explicitly to $pagination object
      $pagination->route_params(array(
         'controller' => $this->request->controller(),
         'action' => $this->request->action()
      ));
      $this->template->content->workout_details      = $adminworkoutmodel->getSampleWorkoutDetails($default, $userid, '', $siteid, '', $wkoutf_val, $sortby, $futured_val, $autosearch, $pagination->items_per_page, $offset);
      $this->template->title                         = ($default == 1) ? 'Default Workout Records' : 'Sample Workout Records';
      $this->template->content->template_details_all = $dataall;
      $this->template->content->default_status       = $default;
      $this->template->content->pagination           = $pagination;
      $this->template->content->lim                  = $lim;
      if (Helper_Common::is_trainer()) {
         $this->template->content->subscriber_details = $usermodel->get_subscribers_only();
      } else {
         $this->template->content->subscriber_details = $usermodel->get_users_by_role("6");
      }
      $this->template->css       = array(
         'assets/plugins/iCheck/square/green.css'
      );
      $this->template->js_bottom = array(
         'assets/plugins/iCheck/icheck.js'
      );
   }
   public function action_shared()
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      $usermodel         = ORM::factory('admin_subscriber');
      $adminusermodel    = ORM::factory('admin_user');
      $siteid            = $this->current_site_id;
      $this->render();
      $wkoutf_val  = $futured_val = $autosearch = '';
      $sortby      = '3'; //most recent
      $wkoutfilter = array();
      if (isset($_POST) && count($_POST) > 0) {
         $sortby      = $this->request->post('fsortby');
         $futured_val = $this->request->post('futured_filter');
         $autosearch  = $this->request->post('autosearch');
         $wkoutfilter = $this->request->post('wkoutfilter');
         $save_edit   = $this->request->post('save_edit');
         if (!empty($wkoutfilter)) {
            foreach ($wkoutfilter as $key => $value) {
               $wkoutf_val .= $value . ',';
            }
            $wkoutf_val = rtrim($wkoutf_val, ',');
         }
         $workoutModel      = $adminworkoutmodel;
         $activityModel     = ORM::factory('activityfeed');
         $method            = $this->request->post('f_method');
         $parentFolderArray = array();
         $datevalue         = Helper_Common::get_default_datetime();
         $parentFolderId    = urldecode($this->request->param('id'));
         if ($save_edit == '2')
            $this->redirect('admin/workout/sample/');
         if (!empty($method) && trim($method) == 'add_workout') {
            $inputArray['wkout_group']      = '0';
            $inputArray['wkout_title']      = $this->request->post('wkout_title');
            $inputArray['wkout_color']      = $this->request->post('wrkoutcolor');
            $inputArray['wkout_order']      = '1';
            $inputArray['user_id']          = $this->globaluser->pk();
            $inputArray['status_id']        = '1';
            $inputArray["site_id"]          = $this->current_site_id;
            $inputArray['access_id']        = $this->globaluser->user_access;
            $inputArray['wkout_focus']      = $this->request->post('wkout_focus');
            $inputArray['wkout_poa']        = '0';
            $inputArray['wkout_poa_time']   = '0';
            $inputArray['parent_folder_id'] = $parentFolderId;
            $inputArray['created_date']     = $datevalue;
            $inputArray['modified_date']    = $datevalue;
            $_POST['wkout_sample_id']       = $workoutModel->insertSampleWorkoutDetails($inputArray);
            /*********************Activiy Feed**********************/
            $activity_feed                  = array();
            $activity_feed["feed_type"]     = 15; // This get from feed_type table
            $activity_feed["action_type"]   = 1; // This get from action_type table  
            $activity_feed["type_id"]       = $_POST['wkout_sample_id'];
            $activity_feed["user"]          = $this->globaluser->pk();
            $activity_feed["site_id"]       = $this->current_site_id;
            Helper_Common::createActivityFeed($activity_feed);
            /*********************Activiy Feed**********************/
            foreach ($_POST['exercise_title_new'] as $keys => $values) {
               if (!empty($values) && trim($values) != '') {
                  $res = $workoutModel->addSampleWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
               }
            }
            $this->session->set('success', 'Successfully <b>' . $inputArray['wkout_title'] . '</b> Workout Record was added!!!');
            if ($save_edit == 1)
               $this->redirect('admin/workout/sampleedit/' . $_POST['wkout_sample_id'] . '/?act=edit');
         } else if (!empty($method) && (trim($method) == 'mywkout' || trim($method) == 'mysharedwkout')) {
            $wkoutids  = $this->request->post('workouts');
            $shared_by = $this->globaluser->pk();
            foreach ($wkoutids as $k => $wkout_id) {
               $checkres = $workoutModel->checkSampleWkout($wkout_id, $method);
               echo $wkout_id . "--" . $checkres . "<br>";
               exit;
               if (!$checkres) {
                  $res = $workoutModel->createSampleWkout($method, $shared_by, $this->current_site_id, $wkout_id, '', 0);
               }
            }
         }
      }
      if (Helper_Common::is_admin())
         $default = (isset($_GET["d"])) ? $_GET["d"] : 0;
      else if (Helper_Common::hasAccessBySampleWkouts($this->current_site_id) && !Helper_Common::is_admin())
         $default = 'all';
      else
         $default = '0';
      $userid                                      = $this->globaluser->pk(); //Current Loggedin users
      $result                                      = $adminworkoutmodel->get_user_created_tags($userid, 2);
      $usertags                                    = $adminworkoutmodel->get_user_tags($userid);
      $this->template->content->usertags           = $result;
      $roleid                                      = $adminusermodel->user_role_load_by_name('Register');
      $usersession                                 = Session::instance()->get('auth_user');
      $this->template->content->focusRecord        = $adminworkoutmodel->getAllFocus();
      $role                                        = Helper_Common::get_role("manager");
      $manager                                     = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $this->template->content->manager            = $manager;
      $role                                        = Helper_Common::get_role("trainer");
      $trainer                                     = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $this->template->content->trainer            = $trainer;
      $role                                        = Helper_Common::get_role("register");
      $subscriber                                  = Helper_Common::get_role_by_users($role, ($default == 0) ? $siteid : '');
      $subscriber                                  = Helper_Common::get_role_by_users($role, $siteid);
      $this->template->content->subscriber_details = $subscriber;
      $this->template->content->focusRecord        = $adminworkoutmodel->getAllFocus();
      $this->template->content->status             = $adminworkoutmodel->get_status();
      $this->template->content->wkoutfilter        = $wkoutfilter;
      $this->template->content->sortby             = $sortby;
      $this->template->content->futured_val        = $futured_val;
      $this->template->content->searchval          = $_POST;
      if ($wkoutf_val == '') {
         $wkoutf_val = 1;
      }
      $lim        = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall    = $adminworkoutmodel->getSharedWorkoutDetails($userid, '', $siteid, '', $wkoutf_val, $sortby, $futured_val, $autosearch, '', '');
      $cnt        = count($dataall);
      $pagination = pagination::factory(array(
         'total_items' => $cnt,
         'items_per_page' => $lim,
         //'auto_hide'         => TRUE,
         'first_page_in_url' => TRUE
      ));
      //echo $pagination->items_per_page; 
      if (isset($_REQUEST['page'])) {
         $page_number = $_REQUEST['page'];
      } else {
         $page_number = 1;
      }
      $offset = $lim * ($page_number - 1);
      // Pass controller and action names explicitly to $pagination object
      $pagination->route_params(array(
         'controller' => $this->request->controller(),
         'action' => $this->request->action()
      ));
      $this->template->content->workout_details      = $adminworkoutmodel->getSharedWorkoutDetails($userid, '', $siteid, '', $wkoutf_val, $sortby, $futured_val, $autosearch, $pagination->items_per_page, $offset);
      $this->template->title                         = ($default == 1) ? 'Default Workout Records' : 'Shared Workout Records';
      $this->template->content->template_details_all = $dataall;
      $this->template->content->default_status       = $default;
      $this->template->content->pagination           = $pagination;
      $this->template->content->lim                  = $lim;
      if (Helper_Common::is_trainer()) {
         $this->template->content->subscriber_details = $usermodel->get_subscribers_only();
      } else {
         $this->template->content->subscriber_details = $usermodel->get_users_by_role("6");
      }
      $this->template->css       = array(
         'assets/plugins/iCheck/square/green.css'
      );
      $this->template->js_bottom = array(
         'assets/plugins/iCheck/icheck.js'
      );
   }
   public function action_json()
   {
      $this->auto_render = FALSE;
      $data["id"]        = 2;
      $data["title"]     = "My title 1";
      $s[]               = $data;
      $data["id"]        = 2;
      $data["title"]     = "Your title 2";
      $s[]               = $data;
      echo json_encode($s);
   }
   public function action_getAdvanceSearchExerciseRecords()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      if (isset($_POST) && count($_POST) > 0) {
         $exercisemodel                             = ORM::factory('admin_exercise');
         $this->template->content->template_details = $exercisemodel->get_exerciseRecordGallery($_POST);
      }
   }
   public function action_getwkoutnames()
   {
      $shareworkoutmodel = ORM::factory('admin_shareworkout');
      if (isset($_POST) && count($_POST) > 0) {
         $str = implode(',', $_POST["data"]);
         if ($str) {
            $res = $shareworkoutmodel->getwkoutname($str);
            if ($res) {
               $i    = 0;
               $temp = array();
               foreach ($res as $k => $v) {
                  $temp[$i]['id']   = $v['wkout_id'];
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
   public function action_gettaguser()
   {
      $workoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0 && isset($_POST["tag_id"])) {
         $tags     = implode(",", $_POST["tag_id"]);
         $tagsuser = $workoutmodel->get_tags_user($tags);
         echo ($tagsuser) ? json_encode($tagsuser) : false;
      }
      echo false;
      exit;
   }
   public function action_removeworkout()
   {
      $workoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $parent_folder_id = 1;
         $wkout_id         = $this->request->post('wkout_id');
         $action_type      = $this->request->post('action_type');
         if ($action_type == 'multiple') {
            $w_id = '';
            if (count($wkout_id) > 0) {
               foreach ($wkout_id as $keys => $values) {
                  $w_id .= $values . ",";
               }
               $wkout_id = rtrim($w_id, ',');
            }
         }
         $res = $workoutmodel->deleteWorkoutDetails($parent_folder_id, $wkout_id);
         if (isset($res)) {
            /******************* Activity Feed *********************/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = 2; // This get from feed_type table
            $activity_feed["action_type"] = 2; // This get from action_type table  
            $activity_feed["type_id"]     = $wkout_id;
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["user"]        = $this->globaluser->pk();
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
         }
         echo ($res) ? true : false;
      }
      echo false;
      exit;
   }
   public function action_removeDefaultworkout()
   {
      $workoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $parent_folder_id = $this->request->post('folder_id');
         $wkout_id         = $this->request->post('wkout_id');
         $action_type      = $this->request->post('action_type');
         $parent_folder    = '';
         if ($action_type == 'multiple') {
            $w_id = '';
            $f_id = '';
            if (count($wkout_id) > 0) {
               foreach ($wkout_id as $keys => $values) {
                  $w_id .= $values . ",";
               }
               $wkout_id = rtrim($w_id, ',');
            } // echo $wkout_id; die;
            if (count($parent_folder_id) > 0) {
               foreach ($parent_folder_id as $keys => $values) {
                  $f_id .= $values . ",";
               }
               $parent_folder = rtrim($f_id, ',');
            }
         }
         $res = $workoutmodel->deleteSampleWorkoutDetails($parent_folder_id, $parent_folder, $wkout_id, 1);
         if (isset($res)) {
            /******************* Activity Feed *********************/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = 23; // This get from feed_type table
            $activity_feed["action_type"] = 2; // This get from action_type table  
            $activity_feed["type_id"]     = $wkout_id;
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["user"]        = $this->globaluser->pk();
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
         }
         echo ($res) ? true : false;
      }
      echo false;
      exit;
   }
   public function action_removeSampleworkout()
   {
      $workoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $parent_folder_id = $this->request->post('folder_id'); //print_r($parent_folder_id); die;
         $wkout_id         = $this->request->post('wkout_id');
         $action_type      = $this->request->post('action_type');
         $parent_folder    = '';
         if ($action_type == 'multiple') {
            $w_id = '';
            $f_id = '';
            if (count($wkout_id) > 0) {
               foreach ($wkout_id as $keys => $values) {
                  $w_id .= $values . ",";
               }
               $wkout_id = rtrim($w_id, ',');
            }
            if (count($parent_folder_id) > 0) {
               foreach ($parent_folder_id as $keys => $values) {
                  $f_id .= $values . ",";
               }
               $parent_folder = rtrim($f_id, ',');
            }
         }
         $res = $workoutmodel->deleteSampleWorkoutDetails($parent_folder_id, $parent_folder, $wkout_id);
         if (isset($res)) {
            /******************* Activity Feed *********************/
            $activity_feed                = array();
            $activity_feed["user"]        = $this->globaluser->pk();
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["feed_type"]   = '15';
            $activity_feed["action_type"] = '2';
            $activity_feed["type_id"]     = $wkout_id;
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
         }
         echo ($res) ? true : false;
      }
      echo false;
      exit;
   }
   public function action_removeSharedworkout()
   {
      $workoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $parent_folder_id = 1;
         $wkout_id         = $this->request->post('wkout_id');
         $action_type      = $this->request->post('action_type');
         if ($action_type == 'multiple') {
            $w_id = '';
            if (count($wkout_id) > 0) {
               foreach ($wkout_id as $keys => $values) {
                  $w_id .= $values . ",";
               }
               $wkout_id = rtrim($w_id, ',');
            }
         }
         $res = $workoutmodel->deleteSharedWorkoutDetails($parent_folder_id, $wkout_id);
         if (isset($res)) {
            /******************* Activity Feed *********************/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = 12; // This get from feed_type table
            $activity_feed["action_type"] = 2; // This get from action_type table  
            $activity_feed["type_id"]     = $wkout_id;
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["user"]        = $this->globaluser->pk();
            Helper_Common::createActivityFeed($activity_feed);
            /******************* Activity Feed *********************/
         }
         echo ($res) ? true : false;
      }
      echo false;
      exit;
   }
   public function action_mail()
   {
      $wkoutid               = 50;
      $workoutmodel          = ORM::factory('admin_workouts');
      $shareworkoutmodel     = ORM::factory('admin_shareworkout');
      $shared_for            = 8;
      $currentuser           = $shareworkoutmodel->getuserdetails($this->globaluser->pk());
      $currentuser           = $currentuser[0];
      $user                  = $shareworkoutmodel->getuserdetails($shared_for);
      $user                  = $user[0];
      $smtpmodel             = ORM::factory('admin_smtp');
      $templateArray         = $smtpmodel->getSendingMailTemplate(array(
         'type_name' => 'notification - shared workout'
      ));
      $fetch_field           = 'name';
      $fetch_condtn          = 'id=' . 1;
      $sites                 = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', $fetch_field, $fetch_condtn);
      $sites                 = $sites[0];
      $templateArray['body'] = str_replace(array(
         '[trainer_name]',
         '[site_title]',
         '[share_workout_plan_link]'
      ), array(
         ucfirst(strtolower($currentuser['user_fname'])),
         ($sites) ? $sites["name"] : '',
         URL::site(NULL, 'http') . 'exercise/sharedworkout/' . $wkoutid
      ), $templateArray['body']);
      $messageArray          = array(
         'subject' => str_replace(array(
            '[trainer_name]'
         ), array(
            ucfirst(strtolower($currentuser['user_fname']))
         ), $templateArray['subject']),
         'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
         'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
         'to' => $user["user_email"],
         'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
         'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
         'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
         'type' => 'text/html'
      );
      if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && false) {
         $hostAddress = explode("://", $templateArray['smtp_host']);
         $emailMailer = Email::dynamicMailer('smtp', array(
            'hostname' => trim($hostAddress['1']),
            'port' => $templateArray['smtp_port'],
            'username' => $templateArray['smtp_user'],
            'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
            'encryption' => trim($hostAddress['0'])
         ));
      } else
         $emailMailer = Email::dynamicMailer('', array());
      Email::sendBysmtp($emailMailer, $messageArray);
   }
   public function action_saveshare()
   {
      $siteid            = $this->current_site_id;
      $workoutmodel      = ORM::factory('admin_workouts');
      $shareworkoutmodel = ORM::factory('admin_shareworkout');
      $usermodel         = ORM::factory('admin_user');
      if (isset($_POST) && count($_POST) > 0) {
         $from_wkout = $_POST["param"];
         unset($_POST["param"]);
         $shared_by              = $this->globaluser->pk();
         $_POST["subscriber_id"] = (is_array($_POST["subscriber_id"])) ? $_POST["subscriber_id"] : array(
            $_POST["subscriber_id"]
         );
         $wkoutids               = $_POST["wkout_id"];
         $shared_userids         = $_POST["subscriber_id"];
         $shared_userids_array   = array_unique($shared_userids);
         $shared_message         = $_POST["message"];
         $assign_option          = $_POST['is_share_assing'];
         $assign_dates           = (isset($_POST['wkout_dates'])) ? $_POST['wkout_dates'] : '';
         $assign_dates_array     = array();
         if ($assign_option == 'on' && !empty($assign_dates)) {
            $assign_dates_array = explode(',', $assign_dates);
         }
         $sharedUsers = array();
         if (isset($wkoutids) && is_array($wkoutids) && count($wkoutids) > 0 && isset($shared_userids_array) && is_array($shared_userids_array) && count($shared_userids_array) > 0) {
            $activityjson = array();
            $currentuser  = $shareworkoutmodel->getuserdetails($this->globaluser->pk());
            $currentuser  = $currentuser[0];
            foreach ($wkoutids as $k => $wkoutid) {
               foreach ($shared_userids_array as $a => $shared_for) {
                  $userAllSiteIds = Helper_Common::getAllSiteIdByUser($this->globaluser->pk());
                  $user_siteid    = (in_array($siteid, $userAllSiteIds) ? $siteid : $siteid);
                  if (isset($user_siteid) && !empty($user_siteid)) {
                     $result       = $workoutmodel->createSharedWkout($from_wkout, $shared_by, $shared_for, $shared_message, $user_siteid, $wkoutid, '');
                     $fetch_field  = ' * ';
                     $fetch_condtn = 'id=' . $user_siteid;
                     $sites        = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', $fetch_field, $fetch_condtn);
                     $sites        = $sites[0];
                     $datetime     = Helper_Common::get_default_datetime();
                     if ($result) {
                        $activity_feed                = array();
                        $activity_feed["feed_type"]   = ($from_wkout != 'sample-workout' ? 2 : 15);
                        $activity_feed["action_type"] = 7;
                        $activity_feed["user"]        = $shared_by;
                        $activity_feed["site_id"]     = $user_siteid;
                        $activity_feed["type_id"]     = $wkoutid;
                        if (isset($assign_dates_array) && count($assign_dates_array) > 0) {
                           foreach ($assign_dates_array as $keydate => $valuedate) {
                              $shareAssign                     = array();
                              $shareAssign['wkout_share_id']   = $result;
                              $shareAssign['assigned_user_id'] = $shared_for;
                              $shareAssign['assign_date']      = Helper_Common::get_default_date($valuedate);
                              $workoutmodel->insertShareAssign($shareAssign);
                           }
                           $this->_sendShareAssignEmailToUser($result, $user_siteid, $shared_for, $activity_feed);
                        }
                        $sharedUsers[$shared_for][$result] = $activity_feed;
                     }
                  }
               }
            }
            if (isset($sharedUsers) && count($sharedUsers) > 0)
               $this->_sendShareEmailToUser($sharedUsers, $siteid, $shared_by);
         }
      }
      echo true;
      exit;
   }
   public function _sendShareAssignEmailToUser($workoutId, $site_id, $user_id, $activity_feed)
   {
      $smtpmodel         = ORM::factory('admin_smtp');
      $shareworkoutmodel = ORM::factory('admin_shareworkout');
      $workoutModel      = ORM::factory('workouts');
      if (isset($workoutId) && is_numeric($workoutId) && !empty($workoutId)) {
         $sites                      = Helper_Common::hasSiteAccess($site_id);
         $user                       = $shareworkoutmodel->getuserdetails($user_id);
         $user                       = $user[0];
         $templateArray              = $smtpmodel->getSendingMailTemplate(array(
            'type_name' => 'notification - shared / assignment workout',
            'site_id' => $site_id
         ));
         $user_Role_name             = $workoutModel->getRoleNameByUserId($this->globaluser->pk());
         $encryptedmsgall            = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'] . '####sharedassignworkoutall');
         $encryptedmessage           = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'] . '####sharedassignworkout');
         $activityjson["from_wkout"] = ($activity_feed["feed_type"] == 2 ? "myworkout" : ($activity_feed["feed_type"] == 15 ? "sample" : ($activity_feed["feed_type"] == 13 ? "assigned" : ($activity_feed["feed_type"] == 11 ? "logged" : ''))));
         $activityjson["sharedto"]   = $user_id;
         $wkoutUrl                   = '<p><a href="' . URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . "/index/autoredirect/" . $workoutId . "/" . $encryptedmsgall . '" target="_blank" title="Click here to Approve All the Assignments" style="color: #1b9af7;">Click here to Approve All the Assignments</a></p>';
         $wkoutArray                 = $workoutModel->getShareAssignworkoutById($workoutId, $this->globaluser->pk(), $user_id);
         $assign_content             = '';
         foreach ($wkoutArray as $keys => $values) {
            $wkoutTitle = $values['wkout_title'];
            $clientdate = Helper_Common::change_default_date_dob($values['assign_date']);
            $atagUrl    = URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . '/index/autoredirect/' . $values['id'] . '/' . $encryptedmessage;
            $assign_content .= '<p><strong> - ' . date('l', strtotime($clientdate)) . ', ' . $clientdate . '</strong>	<a href="' . $atagUrl . '" target="_blank" title="Approve this Assignment" style="color: #1b9af7;">Approve this Assignment</a></p><p>&nbsp;</p>';
         }
         $templateArray['body'] = str_replace(array(
            '[Sender_Name]',
            '[Sender_Role]',
            '[Workout_Title]',
            '[Assign_Count]',
            '[Site_Title]',
            '[Assign_Content]',
            '[AcceptLink]'
         ), array(
            ucfirst(strtolower($this->globaluser->user_fname)),
            $user_Role_name['name'],
            $wkoutTitle,
            count($wkoutArray),
            ($sites ? $sites["name"] : ''),
            $assign_content,
            $wkoutUrl
         ), $templateArray['body']);
         if (true) {
            $messageArray = array(
               'subject' => str_replace(array(
                  '[Sender_Name]'
               ), array(
                  ucfirst(strtolower($this->globaluser->user_fname))
               ), $templateArray['subject']),
               'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
               'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
               'to' => $user["user_email"],
               'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
               'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
               'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
               'type' => 'text/html'
            );
            if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && true) {
               $hostAddress = explode("://", $templateArray['smtp_host']);
               $emailMailer = Email::dynamicMailer('smtp', array(
                  'hostname' => trim($hostAddress['1']),
                  'port' => $templateArray['smtp_port'],
                  'username' => $templateArray['smtp_user'],
                  'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                  'encryption' => trim($hostAddress['0'])
               ));
            } else
               $emailMailer = Email::dynamicMailer('', array());
            Email::sendBysmtp($emailMailer, $messageArray);
         }
         /******************* Activity Feed *********************/
         if ($activityjson) {
            $activity_feed["json_data"] = json_encode($activityjson);
            Helper_Common::createActivityFeed($activity_feed);
         }
         /******************* Activity Feed *********************/
      }
      return true;
   }
   public function _sendShareEmailToUser($sharedArray, $site_id, $userid)
   {
      $smtpmodel         = ORM::factory('admin_smtp');
      $shareworkoutmodel = ORM::factory('admin_shareworkout');
      $workoutmodel      = ORM::factory('admin_workouts');
      if (isset($sharedArray) && is_array($sharedArray) && count($sharedArray) > 0) {
         $sites         = Helper_Common::hasSiteAccess($site_id);
         $templateArray = $smtpmodel->getSendingMailTemplate(array(
            'type_name' => 'notification - shared workout',
            'site_id' => $site_id
         ));
         foreach ($sharedArray as $user_id => $wkoutshareArray) {
            $user             = $shareworkoutmodel->getuserdetails($user_id);
            $user             = $user[0];
            $encryptedmessage = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'] . '####sharedworkout');
            $wkoutUrl         = '';
            foreach ($wkoutshareArray as $workoutId => $activityjson) {
               $wkoutArray = $workoutmodel->getShareworkoutById('', $workoutId);
               $wkoutUrl .= '<p>- ' . $wkoutArray['wkout_title'] . ' - <a href="' . URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . "/index/autoredirect/" . $workoutId . "/" . $encryptedmessage . '" target="_blank" title="Click here to View this Shared Workout plan" style="color: #1b9af7;">Click here to View this Shared Workout plan</a></p><p>&nbsp;</p>';
            }
            $activityjson["sharedto"] = $user_id;
            $templateArray['body']    = str_replace(array(
               '[trainer_name]',
               '[first_name]',
               '[site_title]',
               '[share_workout_plan_link]'
            ), array(
               ucfirst(strtolower($this->globaluser->user_fname)),
               ucfirst(strtolower($user['user_fname'])),
               ($sites) ? $sites["name"] : '',
               $wkoutUrl
            ), $templateArray['body']);
            if (true) {
               $messageArray = array(
                  'subject' => str_replace(array(
                     '[trainer_name]'
                  ), array(
                     ucfirst(strtolower($this->globaluser->user_fname))
                  ), $templateArray['subject']),
                  'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                  'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
                  'to' => $user["user_email"],
                  'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                  'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
                  'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $site_id),
                  'type' => 'text/html'
               );
               if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && true) {
                  $hostAddress = explode("://", $templateArray['smtp_host']);
                  $emailMailer = Email::dynamicMailer('smtp', array(
                     'hostname' => trim($hostAddress['1']),
                     'port' => $templateArray['smtp_port'],
                     'username' => $templateArray['smtp_user'],
                     'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                     'encryption' => trim($hostAddress['0'])
                  ));
               } else
                  $emailMailer = Email::dynamicMailer('', array());
               Email::sendBysmtp($emailMailer, $messageArray);
            }
            /************** Activity Feed ***************/
            if ($activityjson) {
               $activity_feed["json_data"] = json_encode($activityjson);
               Helper_Common::createActivityFeed($activity_feed);
            }
            /************* Activity Feed **************/
         }
      }
      return true;
   }
   public function action_filtersubscribers()
   {
      //print_r($_POST);
      $shareworkoutmodel = ORM::factory('admin_shareworkout');
      if (HTTP_Request::POST == $this->request->method()) {
         $subscriberid = $this->request->post('subscriberid');
         if ($subscriberid) {
            $subscriberid = implode(",", array_filter($subscriberid));
         }
         $gender   = $this->request->post('gender');
         $agerange = $this->request->post('setagerange');
         $from     = '';
         $to       = '';
         if ($agerange) {
            $agerange = explode("-", $agerange);
            $from     = $agerange[0];
            $to       = $agerange[1];
         }         
         $res = $shareworkoutmodel->filtersubscriber($subscriberid, $gender, $from, $to);
         echo json_encode($res);
      }
      echo false;
      exit;
   }
   public function action_defaulthide()
   {
      $workoutsmodel = ORM::factory('admin_workouts');
      if (HTTP_Request::POST == $this->request->method()) {
         $wkout_type       = $this->request->post('workout_type');
         $this->globaluser = Auth::instance()->get_user();
         $method           = $this->request->post('f_method');
         $default          = ($this->request->post('default_status')) ? $this->request->post('default_status') : 0;
         $workid           = $this->request->post('workouts');
         if (is_array($workid) && count($workid) > 0) {
            foreach ($workid as $k => $v) {
               $workoutsmodel->hideDefaultRecords($this->globaluser->pk(), $this->current_site_id, $v, '1');
            }
         }
         $this->redirect('admin/workout/sample');
      }
   }
   public function action_copyworkout()
   {
      $workoutsmodel = ORM::factory('admin_workouts');
      if (HTTP_Request::POST == $this->request->method()) {
         $wkout_type = $this->request->post('workout_type');
         if ($wkout_type == '') {
            $this->globaluser             = Auth::instance()->get_user();
            $method                       = $this->request->post('f_method');
            $workid                       = $this->request->post('workout_id');
            $createdId                    = $workoutsmodel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), 0, $workid, $method, '');
            /**** Activity Feed **/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = '2';
            $activity_feed["action_type"] = 22;
            $activity_feed["user"]        = $this->globaluser->pk();
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["type_id"]     = $workid;
            $activity_feed["json_data"]   = json_encode(array(
               'wkout' => $createdId
            ));
            Helper_Common::createActivityFeed($activity_feed);
            /*** Activity Feed **/
            $this->redirect('admin/workout/browse');
         } elseif ($wkout_type == 'sampleToworkout' || $wkout_type == 'defaultToworkout') {
            $this->globaluser             = Auth::instance()->get_user();
            $method                       = $this->request->post('f_method');
            $workid                       = $this->request->post('workout_id');
            $default                      = ($this->request->post('default_status')) ? $this->request->post('default_status') : 0;
            $createdId                    = $workoutsmodel->doCopyForExerciseSetsById($wkout_type, $this->globaluser->pk(), 0, $workid, $method, $default);
            /**** Activity Feed **/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = ($default == 1) ? 32 : 15;
            $activity_feed["action_type"] = 22;
            $activity_feed["user"]        = $this->globaluser->pk();
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["type_id"]     = $workid;
            $activity_feed["json_data"]   = json_encode(array(
               'wkout' => $createdId
            ));
            Helper_Common::createActivityFeed($activity_feed);
            /*** Activity Feed **/
            $this->redirect('admin/workout/browse');
         } elseif ($wkout_type == 'sample' || $wkout_type == 'default') {
            $this->globaluser = Auth::instance()->get_user();
            $method           = $this->request->post('f_method');
            $default          = ($this->request->post('default_status')) ? $this->request->post('default_status') : 0;
            $workid           = $this->request->post('workout_id');
            $workid           = explode(",", $workid);
            if (is_array($workid) && count($workid) > 0) {
               foreach ($workid as $k => $v) {
                  $createdId                    = $workoutsmodel->doCopyForExerciseSetsById('sample workout', $this->globaluser->pk(), 0, $v, $method, $default);
                  /**** Activity Feed **/
                  //Example : You copied sample workout plan 139 "User15 PLan1_copy_shared_copy"
                  $activity_feed                = array();
                  $activity_feed["feed_type"]   = ($wkout_type == 'default') ? 32 : 15;
                  $activity_feed["action_type"] = 22;
                  $activity_feed["user"]        = $this->globaluser->pk();
                  $activity_feed["site_id"]     = $this->current_site_id;
                  $activity_feed["type_id"]     = $v;
                  $activity_feed["json_data"]   = ($default == 1) ? json_encode(array(
                     'wkoutdefault' => $createdId
                  )) : json_encode(array(
                     'wkoutsample' => $createdId
                  ));
                  Helper_Common::createActivityFeed($activity_feed);
                  /*** Activity Feed **/
               }
            } else {
               $createdId                    = $workoutsmodel->doCopyForExerciseSetsById('sample workout', $this->globaluser->pk(), 0, $workid, $method, $default);
               /**** Activity Feed **/
               //Example : You copied sample workout plan 139 "User15 PLan1_copy_shared_copy"
               $activity_feed                = array();
               $activity_feed["feed_type"]   = ($default == 1) ? 32 : 15;
               $activity_feed["action_type"] = 22;
               $activity_feed["user"]        = $this->globaluser->pk();
               $activity_feed["site_id"]     = $this->current_site_id;
               $activity_feed["type_id"]     = $workid;
               $activity_feed["json_data"]   = ($default == 1) ? json_encode(array(
                  'wkoutdefault' => $createdId
               )) : json_encode(array(
                  'wkoutsample' => $createdId
               ));
               Helper_Common::createActivityFeed($activity_feed);
               /*** Activity Feed **/
            }
            if ($default == 1)
               $this->redirect('admin/workout/sample?d=1');
            else
               $this->redirect('admin/workout/sample');
         } else if ($wkout_type == 'shared') {
            $this->globaluser = Auth::instance()->get_user();
            $method           = $this->request->post('f_method');
            $workid           = $this->request->post('workout_id');
            $createdId        = $workoutsmodel->doCopyForExerciseSetsById('shared', $this->globaluser->pk(), 0, $workid, $method, '');
            $this->session->set('success', 'Successfully your shared workout Record was copied to myworkout !!!');
            /**** Activity Feed **/
            $activity_feed                = array();
            $activity_feed["feed_type"]   = '12';
            $activity_feed["action_type"] = 22;
            $activity_feed["user"]        = $this->globaluser->pk();
            $activity_feed["site_id"]     = $this->current_site_id;
            $activity_feed["type_id"]     = $workid;
            $activity_feed["json_data"]   = json_encode(array(
               'wkout' => $createdId
            ));
            Helper_Common::createActivityFeed($activity_feed);
            /*** Activity Feed **/
            $this->redirect('admin/workout/shared');
         }
      }
   }
   public function action_previewworkout()
   {
      $wkoutid              = Arr::get($_GET, 'wkoutid');
      $user                 = Auth::instance()->get_user();
      $method               = Arr::get($_GET, 'method');
      $type                 = Arr::get($_GET, 'type'); // ''=>wkout , assign => assign_wkout , log => log_wkout
      $this->globaluser     = Auth::instance()->get_user();
      $adminm_workoutsmodel = ORM::factory('admin_workouts');
      if ($wkoutid) {
         $focusRecord                  = $adminm_workoutsmodel->getAllFocus();
         $workoutRecord                = $adminm_workoutsmodel->getworkoutPreviewById('', $wkoutid);
         $exerciseRecord               = $adminm_workoutsmodel->getExerciseSet($wkoutid); 
         $data                         = array();
         $data['wkout']["color_title"] = $workoutRecord["color_title"];
         $data['wkout']["wkout_title"] = $workoutRecord["wkout_title"];
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $workoutRecord['wkout_focus'])
               $data["wkout"]["overallfocus"] = ucfirst($values['focus_opt_title']);
         }
         $temp = array();
         if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
            $i = 0;
            foreach ($exerciseRecord as $keys => $values) {
               $temp[$i]["unit_id"] = $values["unit_id"];
               if (file_exists($values["img_url"])) {
                  $temp[$i]["img"]       = URL::base(TRUE) . $values['img_url'];
                  $temp[$i]["img_title"] = ucfirst($values['img_title']);
               }
               $temp[$i]["goal_id"]    = $values["goal_id"];
               $temp[$i]["goal_alt"]   = $values['goal_alt'];
               $temp[$i]["goal_title"] = ucfirst($values['goal_title']);
               $response               = '';
               $parameter              = $parameter1 = $parameter2 = '';
               if ($values['goal_time_hh'] > 0 || $values['goal_time_mm'] > 0 || $values['goal_time_ss'] > 0) {
                  $parameter .= '<span>' . substr(sprintf("%02d", $values['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $values['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $values['goal_time_ss']), 0, 2) . '</span> /// ';
               }
               if ($values['goal_dist'] > 0 && $values['goal_dist_id'] > 0) {
                  $parameter .= $values['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getGoalVars('dist', $values['goal_dist_id']) . '</span> /// ';
               }
               if ($values['goal_reps'] > 0) {
                  $parameter .= $values['goal_reps'] . ' <span>reps</span> /// ';
               }
               if ($values['goal_resist'] > 0 && $values['goal_resist_id'] > 0) {
                  $parameter .= ' x ' . $values['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getGoalVars('resist', $values['goal_resist_id']) . '</span> /// ';
               }
               $response .= ($parameter) ? substr($parameter, 0, -4) : '';
               if ($values['goal_rate'] > 0 && $values['goal_rate_id'] > 0) {
                  $parameter1 = '<span>@' . $values['goal_rate'] . ' ' . Model::instance('Model/workouts')->getGoalVars('rate', $values['goal_rate_id']) . '</span> ';
               }
               if ($values['goal_angle'] > 0 && $values['goal_angle_id'] > 0) {
                  $parameter1 .= '<span>' . $values['goal_angle'] . '%' . Model::instance('Model/workouts')->getGoalVars('angle', $values['goal_id']) . '</span> ';
               }
               $intvalue = Model::instance('Model/workouts')->getGoalVars('int', $values['goal_id']);
               if ($intvalue > 0) {
                  $parameter1 .= '<span>' . $intvalue . ' int</span>';
               }
               if ($values['goal_rest_mm'] + $values['goal_rest_ss'] > 0) {
                  if ($values['goal_rest_mm'] > 0 || $values['goal_rest_ss'] > 0) {
                     $parameter1 .= ' <span>' . $values['goal_rest_mm'];
                     if ($values['goal_rest_ss'] > 0 && $values['goal_rest_ss'] < 10)
                        $parameter1 .= ':0' . $values['goal_rest_ss'];
                     else
                        $parameter1 .= ':' . substr(sprintf("%02d", $values['goal_rest_ss']), 0, 2);
                     $parameter1 .= ' rest</span>';
                  }
               }
               $response .= ($parameter1) ? '</br>' . $parameter1 : '';
               $temp[$i]["response"] = $response;
               $i++;
            }
            $data["exeset"] = $temp;
         } else {
            $data["exeset"] = '';
         }
         echo $this->request->response = json_encode($data);
         exit;
      }
      echo false;
      exit;
   }
   public function action_getwkouts()
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $action     = $this->request->post('action');
         $folderid   = $this->request->post('foldid');
         $result     = "";
         $userid     = $this->globaluser->pk(); //Current Loggedin users			
         $siteid     = $this->current_site_id;
         $role       = Helper_Common::get_role("manager");
         $manager    = Helper_Common::get_role_by_users($role, $siteid);
         $role       = Helper_Common::get_role("trainer");
         $trainer    = Helper_Common::get_role_by_users($role, $siteid);
         $role       = Helper_Common::get_role("register");
         $subscriber = Helper_Common::get_role_by_users($role, $siteid);
         $userids    = '';
         $userids    = $userid;
         if ($action == 'mywkout') {
            if (Helper_Common::is_admin())
               $result = $adminworkoutmodel->getWorkoutDetailsByUser($userids, '', '');
            else
               $result = $adminworkoutmodel->getWorkoutDetailsByUser($userids, '', $siteid);
         } elseif ($action == 'mysharedwkout') {
            $result = $adminworkoutmodel->getSharedWorkoutDetailsByUser($userids, '', $siteid);
         }
         $str = "";
         if ($result && count($result) > 0) {
            $str = '<div class="row-pad"><ul>';
            foreach ($result as $k => $v) {
               $str .= '<li id="" class="bgC4 item_add_wkout_noclick ui-sortable-handle" data-module="item_set_new" data-id="new_0_1">
									<div class="row" id="itemsetnew_0_1">
										<div class="mobpadding">
											<div class="border full">
												<div class="checkboxchoosen popupchoosen col-xs-2 listwkouts" >
													<div class="checkboxcolor" style="font-size:20px;">
														<label><input class="checkhidden wkoutcheck" type="checkbox" value="' . $v["wkout_id"] . '" name="workouts[]" onclick="enableButtons();" data-ajax="false" data-role="none"/><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label>
													</div>
												</div>
												<div class="col-xs-9 navdescrip">
													<div class="col-xs-1 activelinkpopup navimage"  disabled="disabled">
														<div class="colorchoosen col-xs-2"><i class="glyphicon ' . $v["color_title"] . '"></i></div>
													</div>
													<div class="col-xs-10 pointers activelinkpopup datacol" style="height:50px" disabled="disabled">
														<div class="activelinkpopup navimagedetails" disabled="disabled">
															<div class="navimgdet1"><b>' . $v["wkout_title"] . '</b></div>
														</div>
													</div>
												</div>
												<div class="col-xs-1 navbarmenu"></div>
											</div>
										</div>
									</div>
								</li>';
            }
            $str .= '</ul></div>';
         }
         $str .= "<input type='hidden' value='" . $action . "' name='f_method' id='f_method_action'>";
         echo $str;
      }
      exit;
   }
   function action_report_email()
   {
      $activity_feed            = array();
      $user_detail              = Auth::instance()->get_user();
      $to_email                 = $this->request->post('email_address');
      $wkoutIds                 = $this->request->post('wkoutIds');
      $wkout_type               = $this->request->post('wkouttype');
      $adminworkoutmodel        = ORM::factory('admin_workouts');
      $siteid                   = Session::instance()->get('current_site_id');
      $sitename                 = Session::instance()->get('current_site_name');
      $activity_feed["user"]    = $user_detail->pk();
      $activity_feed["site_id"] = $siteid;
      if (empty($wkoutIds)) {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '28' : ($type == 'default' ? '29' : ($type == 'shared' ? '30' : '27')));
         $activity_feed["action_type"] = '43';
      } else {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '15' : ($type == 'default' ? '32' : ($type == 'shared' ? '12' : '2')));
         $activity_feed["action_type"] = '46';
      }
      $activity_feed["type_id"]   = 0;
      $activity_feed["json_data"] = json_encode('Email');
      Helper_Common::createActivityFeed($activity_feed);
      if ($wkout_type == 'sample' || $wkout_type == 'default')
         $content = $adminworkoutmodel->getSampleWorkoutDetails(($wkout_type == 'default' ? '1' : '0'), $user_detail->pk(), $wkoutIds, $siteid);
      elseif ($wkout_type == 'shared')
         $content = $adminworkoutmodel->getSharedWorkoutDetails($user_detail->pk(), $wkoutIds, $siteid);
      else
         $content = $adminworkoutmodel->getWorkoutDetailsByUser($user_detail->pk(), $wkoutIds, $siteid);
      $config       = Kohana::$config->load('emailsetting');
      $from_address = $user_detail->user_email;
      $from_name    = $sitename;
      $title        = '"' . $sitename . '" ' . ($type == 'sample' ? 'Sample' : ($type == 'default' ? 'Default' : ($type == 'shared' ? 'Shared' : ''))) . ' Workout Records List Report';
      $report       = $this->_Gst_report_content($content, $title);
      $subject      = '"' . $sitename . '" ' . ($type == 'sample' ? 'Sample' : ($type == 'default' ? 'Default' : ($type == 'shared' ? 'Shared' : ''))) . ' Workout Records List Report';
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
   function _Gst_report_content($content, $title)
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      $focusRecord       = $adminworkoutmodel->getAllFocus();
      if (empty($content)) {
         return false;
      }
      $msg = '<h2>' . $title . '</h2><table cellspacing="8" cellpadding="5" border="1" width="100%">';
      $msg .= '<tr><td style="width:20%; padding-left:5px;">Color</td><td style="width:20%; padding-left:5px;">Workout Title</td><td style="width:20%; padding-left:5px;">Workout Focus</td><td style="width:20%; padding-left:5px;">Folder</td><td style="width:20%; padding-left:5px;">Tags</td></tr>';
      foreach ($content as $con) {
         $msg .= '<tr>';
         $msg .= '<td style="padding-left:5px;">' . $con['color_title'] . '</td>';
         $msg .= '<td style="padding-left:5px;">' . $con['wkout_title'] . '</td>';
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $con['wkout_focus'])
               $msg .= '<td style="padding-left:5px;">' . ucfirst($values['focus_opt_title']) . '</td>';
         }
         $msg .= '<td style="padding-left:5px;">' . $con['folder_title'] . '</td>';
         $tagfinal = '';
         if (isset($con['tagdetails']) && !empty($con['tagdetails'])) {
            $tags     = explode('@@', $con['tagdetails']);
            $tagfinal = implode(", ", $tags);
         }
         $msg .= '<td style="padding-left:5px;">' . $tagfinal . '</td>';
         $msg .= '</tr>';
      }
      $msg .= '</table>';
      return $msg;
   }
   public function action_get_report_as_excel()
   {
      $activity_feed            = array();
      $user_detail              = Auth::instance()->get_user();
      $adminworkoutmodel        = ORM::factory('admin_workouts');
      $type                     = (isset($_GET['type']) ? $_GET['type'] : 'wkout');
      $wkoutIds                 = (isset($_GET['wsid']) ? $_GET['wsid'] : '');
      $siteid                   = Session::instance()->get('current_site_id');
      $sitename                 = Session::instance()->get('current_site_name');
      $focusRecord              = $adminworkoutmodel->getAllFocus();
      $activity_feed["user"]    = $user_detail->pk();
      $activity_feed["site_id"] = $siteid;
      if (empty($wkoutIds)) {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '28' : ($type == 'default' ? '29' : ($type == 'shared' ? '30' : '27')));
         $activity_feed["action_type"] = '43';
      } else {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '15' : ($type == 'default' ? '32' : ($type == 'shared' ? '12' : '2')));
         $activity_feed["action_type"] = '46';
      }
      $activity_feed["type_id"]   = 0;
      $activity_feed["json_data"] = json_encode('Excel');
      Helper_Common::createActivityFeed($activity_feed);
      if ($type == 'sample' || $type == 'default')
         $online_report = $adminworkoutmodel->getSampleWorkoutDetails(($type == 'default' ? '1' : '0'), $user_detail->pk(), $wkoutIds, $siteid);
      elseif ($type == 'shared')
         $online_report = $adminworkoutmodel->getSharedWorkoutDetails($user_detail->pk(), $wkoutIds, $siteid);
      else
         $online_report = $adminworkoutmodel->getWorkoutDetailsByUser($user_detail->pk(), $wkoutIds);
      include("./plugins/phpexcel/Classes/PHPExcel.php");
      $objPHPExcel  = new PHPExcel();
      $serialnumber = 0;
      //Set header with temp array
      $tmparray     = array(
         "S.No",
         "Color",
         "Workout Title",
         "Workout Focus",
         "Folder",
         "Tags"
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
         $color_title = $onlinereport['color_title'];
         array_push($tmparray, $color_title);
         $wkout_title = $onlinereport['wkout_title'];
         array_push($tmparray, $wkout_title);
         $focus_opt_title = '';
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $onlinereport['wkout_focus']) {
               $focus_opt_title = ucfirst($values['focus_opt_title']);
            }
         }
         array_push($tmparray, $focus_opt_title);
         $folder_title = $onlinereport['folder_title'];
         array_push($tmparray, $folder_title);
         $tagfinal = '';
         if (isset($onlinereport['tagdetails']) && !empty($onlinereport['tagdetails'])) {
            $tags     = explode('@@', $onlinereport['tagdetails']);
            $tagfinal = implode(", ", $tags);
         }
         $tagfinal = $tagfinal;
         array_push($tmparray, $tagfinal);
         array_push($sheet, $tmparray);
      }
      foreach ($sheet as $row => $columns) {
         foreach ($columns as $column => $data) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row + 1, $data);
         }
      }
      header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . ($type == 'sample' ? 'SampleList' : ($type == 'default' ? 'DefaultList' : ($type == 'shared' ? 'SharedList' : 'WorkoutList'))) . '.xlsx"');
      header('Cache-Control: max-age=0');
      $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
      $file_title = '"' . $sitename . '" ' . ($type == 'sample' ? 'Sample' : ($type == 'default' ? 'Default' : ($type == 'shared' ? 'Shared' : ''))) . ' Workout Records List Report';
      $objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
      exit;
   }
   function action_get_report_as_pdf()
   {
      $activity_feed            = array();
      $adminworkoutmodel        = ORM::factory('admin_workouts');
      $authid                   = $this->globaluser->pk();
      $type                     = (isset($_GET['type']) ? $_GET['type'] : 'wkout');
      $wkoutIds                 = (isset($_GET['wsid']) ? $_GET['wsid'] : '');
      $siteid                   = Session::instance()->get('current_site_id');
      $sitename                 = Session::instance()->get('current_site_name');
      $activity_feed["user"]    = $authid;
      $activity_feed["site_id"] = $siteid;
      if (empty($wkoutIds)) {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '28' : ($type == 'default' ? '29' : ($type == 'shared' ? '30' : '27')));
         $activity_feed["action_type"] = '43';
      } else {
         $activity_feed["feed_type"]   = ($type == 'sample' ? '15' : ($type == 'default' ? '32' : ($type == 'shared' ? '12' : '2')));
         $activity_feed["action_type"] = '46';
      }
      $activity_feed["type_id"]   = 0;
      $activity_feed["json_data"] = json_encode('PDF');
      Helper_Common::createActivityFeed($activity_feed);
      if ($type == 'sample' || $type == 'default')
         $content = $adminworkoutmodel->getSampleWorkoutDetails(($type == 'default' ? '1' : '0'), $authid, $wkoutIds, $siteid);
      elseif ($type == 'shared')
         $content = $adminworkoutmodel->getSharedWorkoutDetails($authid, $wkoutIds, $siteid);
      else
         $content = $adminworkoutmodel->getWorkoutDetailsByUser($authid, $wkoutIds);
      $title    = '"' . $sitename . '" ' . ($type == 'sample' ? 'Sample' : ($type == 'default' ? 'Default' : ($type == 'shared' ? 'Shared' : 'Workout'))) . ' Workout Records List Report';
      $contents = $this->_Gst_report_content($content, $title);
      $contents = $this->_generatePDF($contents, $title);
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
      $file_name = 'MW-' . $title . '-' . date('Ymdhis');
      $mpdf->Output($file_name . '.pdf', 'I');
      exit;
      return $file_name;
   }
   function action_report_email1()
   {
      $user_detail       = Auth::instance()->get_user();
      $to_email          = $this->request->post('email_address');
      $adminworkoutmodel = ORM::factory('admin_workouts');
      $siteid            = Session::instance()->get('current_site_id');
      $sitename          = Session::instance()->get('current_site_name');
      if (!Helper_Common::is_admin()) {
         $content = $adminworkoutmodel->getSampleWorkoutDetails('', '', $siteid);
      } else {
         $content = $adminworkoutmodel->getSampleWorkoutDetails('');
      }
      $config       = Kohana::$config->load('emailsetting');
      $from_address = $user_detail->user_email;
      $from_name    = $sitename;
      $title        = '"' . $sitename . '" Sample Workout Records List';
      $report       = $this->_Gst_report_content1($content, $title);
      $subject      = '"' . $sitename . '" Sample Workout Records List';
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
   function _Gst_report_content1($content, $title)
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      $focusRecord       = $adminworkoutmodel->getAllFocus();
      if (empty($content)) {
         return false;
      }
      $msg = '<h2>' . $title . '</h2><table cellspacing="8" cellpadding="5" border="1" width="100%">';
      $msg .= '<tr><td style="width:20%; padding-left:5px;">Color</td><td style="width:20%; padding-left:5px;">Workout Title</td><td style="width:20%; padding-left:5px;">Workout Focus</td><td style="width:20%; padding-left:5px;">Folder</td></tr>';
      foreach ($content as $con) {
         $msg .= '<tr>';
         $msg .= '<td style="padding-left:5px;">' . $con['color_title'] . '</td>';
         $msg .= '<td style="padding-left:5px;">' . $con['wkout_title'] . '</td>';
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $con['wkout_focus'])
               $msg .= '<td style="padding-left:5px;">' . ucfirst($values['focus_opt_title']) . '</td>';
         }
         $msg .= '<td style="padding-left:5px;">' . $con['folder_title'] . '</td>';
         $msg .= '</tr>';
      }
      $msg .= '</table>';
      return $msg;
   }
   public function action_get_report_as_excel1()
   {
      $activity_feed                = array();
      $adminworkoutmodel            = ORM::factory('admin_workouts');
      $authid                       = $this->globaluser->pk();
      $siteid                       = Session::instance()->get('current_site_id');
      $sitename                     = Session::instance()->get('current_site_name');
      $focusRecord                  = $adminworkoutmodel->getAllFocus();
      $default                      = (isset($_GET["d"])) ? $_GET["d"] : 0;
      $online_report                = $adminworkoutmodel->getSampleWorkoutDetails($default, '', '', $siteid, '', 1, '', '', '', '', '');
      $activity_feed["user"]        = $authid;
      $activity_feed["site_id"]     = $siteid;
      $activity_feed["feed_type"]   = ($default == '1' ? '29' : '28');
      $activity_feed["action_type"] = '43';
      $activity_feed["type_id"]     = 0;
      $activity_feed["json_data"]   = json_encode('Excel');
      Helper_Common::createActivityFeed($activity_feed);
      include("./plugins/phpexcel/Classes/PHPExcel.php");
      $objPHPExcel  = new PHPExcel();
      $serialnumber = 0;
      //Set header with temp array
      $tmparray     = array(
         "S.No",
         "Color",
         "Workout Title",
         "Workout Focus",
         "Folder"
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
         $color_title = $onlinereport['color_title'];
         array_push($tmparray, $color_title);
         $wkout_title = $onlinereport['wkout_title'];
         array_push($tmparray, $wkout_title);
         $focus_opt_title = '';
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $onlinereport['wkout_focus']) {
               $focus_opt_title = ucfirst($values['focus_opt_title']);
            }
         }
         array_push($tmparray, $focus_opt_title);
         $folder_title = $onlinereport['folder_title'];
         array_push($tmparray, $folder_title);
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
      $file_title = '"' . $sitename . '" Sample Workout Records List';
      $objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
      exit;
   }
   function action_get_report_as_pdf1()
   {
      $activity_feed                = array();
      $authid                       = $this->globaluser->pk();
      $adminworkoutmodel            = ORM::factory('admin_workouts');
      $siteid                       = Session::instance()->get('current_site_id');
      $sitename                     = Session::instance()->get('current_site_name');
      $focusRecord                  = $adminworkoutmodel->getAllFocus();
      $default                      = (isset($_GET["d"])) ? $_GET["d"] : 0;
      $content                      = $adminworkoutmodel->getSampleWorkoutDetails($default, '', '', $siteid, '', 1, '', '', '', '', '');
      $activity_feed["user"]        = $authid;
      $activity_feed["site_id"]     = $siteid;
      $activity_feed["feed_type"]   = ($default == '1' ? '29' : '28');
      $activity_feed["action_type"] = '43';
      $activity_feed["type_id"]     = 0;
      $activity_feed["json_data"]   = json_encode('PDF');
      Helper_Common::createActivityFeed($activity_feed);
      $title    = '"' . $sitename . '" Sample Workout Records List';
      $contents = $this->_Gst_report_content1($content, $title);
      $contents = $this->_generatePDF($contents, $title);
   }
   public function action_get_report_as_excel2()
   {
      $activity_feed                = array();
      $adminworkoutmodel            = ORM::factory('admin_workouts');
      $authid                       = $this->globaluser->pk();
      $siteid                       = Session::instance()->get('current_site_id');
      $sitename                     = Session::instance()->get('current_site_name');
      $focusRecord                  = $adminworkoutmodel->getAllFocus();
      $online_report                = $adminworkoutmodel->getSharedWorkoutDetails($authid, '', $siteid, '', 1, '', '', '', '', '');
      $activity_feed["user"]        = $authid;
      $activity_feed["site_id"]     = $siteid;
      $activity_feed["feed_type"]   = 12;
      $activity_feed["action_type"] = '43';
      $activity_feed["type_id"]     = 0;
      $activity_feed["json_data"]   = json_encode('Excel');
      Helper_Common::createActivityFeed($activity_feed);
      include("./plugins/phpexcel/Classes/PHPExcel.php");
      $objPHPExcel  = new PHPExcel();
      $serialnumber = 0;
      //Set header with temp array
      $tmparray     = array(
         "S.No",
         "Color",
         "Workout Title",
         "Workout Focus",
         "Folder"
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
         $color_title = $onlinereport['color_title'];
         array_push($tmparray, $color_title);
         $wkout_title = $onlinereport['wkout_title'];
         array_push($tmparray, $wkout_title);
         $focus_opt_title = '';
         foreach ($focusRecord as $keys => $values) {
            if ($values['focus_id'] == $onlinereport['wkout_focus']) {
               $focus_opt_title = ucfirst($values['focus_opt_title']);
            }
         }
         array_push($tmparray, $focus_opt_title);
         $folder_title = $onlinereport['folder_title'];
         array_push($tmparray, $folder_title);
         array_push($sheet, $tmparray);
      }
      foreach ($sheet as $row => $columns) {
         foreach ($columns as $column => $data) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $row + 1, $data);
         }
      }
      header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Shared Workout-' . date("Y-M-D") . '.xlsx"');
      header('Cache-Control: max-age=0');
      $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
      $file_title = '"' . $sitename . '" Shared Workout Records List';
      $objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
      exit;
   }
   function action_get_report_as_pdf2()
   {
      $activity_feed                = array();
      $authid                       = $this->globaluser->pk();
      $adminworkoutmodel            = ORM::factory('admin_workouts');
      $siteid                       = Session::instance()->get('current_site_id');
      $sitename                     = Session::instance()->get('current_site_name');
      $focusRecord                  = $adminworkoutmodel->getAllFocus();
      $default                      = (isset($_GET["d"])) ? $_GET["d"] : 0;
      $content                      = $adminworkoutmodel->getSharedWorkoutDetails($authid, '', $siteid, '', 1, '', '', '', '', '');
      $activity_feed["user"]        = $authid;
      $activity_feed["site_id"]     = $siteid;
      $activity_feed["feed_type"]   = ($default == '1' ? '29' : '28');
      $activity_feed["action_type"] = '43';
      $activity_feed["type_id"]     = 0;
      $activity_feed["json_data"]   = json_encode('PDF');
      Helper_Common::createActivityFeed($activity_feed);
      $title    = '"' . $sitename . '" Shared Workout Records List';
      $contents = $this->_Gst_report_content1($content, $title);
      $contents = $this->_generatePDF($contents, $title);
   }
   public function action_WkoutUpdateStatus()
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $wkoutid   = $this->request->post('wkoutid');
         $wk_status = $this->request->post('wk_status');
         $featured  = $this->request->post('featured');
         $page      = $this->request->post('page');
         if ($page == 'wkout') {
            $result = $adminworkoutmodel->wkout_update_status($wkoutid, $wk_status, $featured);
         } else if ($page == 'wkoutsample') {
            $result = $adminworkoutmodel->sample_wkout_update_status($wkoutid, $wk_status, $featured);
         }
         echo $result;
         die;
      }
   }
   public function action_assign_status_val()
   {
      $adminworkoutmodel = ORM::factory('admin_workouts');
      if (isset($_POST) && count($_POST) > 0) {
         $wkoutid = $this->request->post('wkoutid');
         $page    = $this->request->post('page');
         if ($page == 'more') {
            $result = $adminworkoutmodel->get_status_by_id($wkoutid);
         } else if ($page == 'sample_more') {
            $result = $adminworkoutmodel->get_sample_wk_status_by_id($wkoutid);
         }
         if (!empty($result)) {
            $response['success']   = true;
            $response['status_id'] = $result[0]['status_id'];
            $response['featured']  = $result[0]['featured'];
            echo json_encode($response);
            die;
         }
      }
   }
}