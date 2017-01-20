<?php
defined('SYSPATH') or die('No direct script access.');
ini_set('memory_limit', '-1');
set_time_limit(0);
class Controller_Admin_Exercise extends Controller_Admin_Website
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
      if (!Helper_Common::hasAccess('Create Exercise') || !Helper_Common::hasAccess('Manage Exercise')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->title = 'Create Exercise Record';
      $userid = Auth::instance()->get_user()->pk();
      $site_id = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
      $workoutModel = ORM::factory('workouts');
      $this->render();
      $XrRecid = urldecode($this->request->param('id'));
      $exerciseid = urldecode($this->request->param('eid'));
      $getunitdata = $getmusothdata = $getequipothdata = $getseqdata = $gettaglist = array();
      $formtype = ''; $xrid='';
      $openfrom = (!empty($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : 'indx');
      if (HTTP_Request::POST == $this->request->method()) {
         $XrRecid = urldecode($this->request->post('xrid'));
         $method = $this->request->post('f_method');
         $startExercise = $this->request->post('startExercise');
         // echo '<pre>';print_r($_POST);exit;
         if (!empty($method)) {
            if (($method == 'save' || $method == 'save-edit') && !empty($XrRecid)) {
               $resdata = $workoutModel->UpdateExerciseRecByIdData($_POST, $XrRecid);
               if($resdata['success']){
                  $xrid = $resdata['xrid'];
                  $this->session->set('flash_success','Exercise record modified successfully!!!');
               }else{
                  $this->session->set('flash_error','Error occurred while updating!!!');
               }
            } elseif (($method == 'save' || $method == 'save-edit') && empty($XrRecid)) {
               $resdata = $workoutModel->InsertExerciseRecByIdData($_POST);
               if($resdata['success']){
                  $xrid = $resdata['xrid'];
                  $this->session->set('flash_success','Exercise record created successfully!!!');
               }else{
                  $this->session->set('flash_error','Error occurred while creating!!!');
               }
            }
         }
         //echo $xrid; exit;
         if ($method == 'save-edit') {
            $this->redirect('admin/exercise/create/'.(!empty($xrid) ? $xrid : (!empty($XrRecid) ? $XrRecid : '')).'?act='.$openfrom);
         }elseif($method != 'save-edit' && $openfrom == 'indx'){
           $this->redirect('admin/workout/browse');
         }else{
            $this->redirect('admin/exercise/browse');
         }
      }
      if(!empty($XrRecid) && is_numeric($XrRecid)){
         $getunitdata      = $workoutModel->getExerciseById($XrRecid);
         $getmusothdata    = $workoutModel->getMusOthByUnitId($XrRecid);
         $getequipothdata  = $workoutModel->getEquipOthByUnitId($XrRecid);
         $getseqdata       = $workoutModel->getSequencesByUnitId($XrRecid);
         $gettaglist       = $workoutModel->getUnitTagsById($XrRecid);
         // activity feed for open
         $activitydtl = $workoutModel->getActivityFeedDetail(array('feed_type'=>'5', 'action_type'=>'15', 'user'=>$userid, 'site_id'=>$site_id, 'type_id'=>$XrRecid));
         $feedjson = array();
         if(count($activitydtl)>0 && !empty($activitydtl)){
            $feed_time = strtotime($activitydtl[0]['created_date']);
            $curr_time = strtotime(Helper_Common::get_default_datetime());
            $hour = abs($curr_time - $feed_time)/(60*60);
            if($hour > 1){
               $feedjson['text'] = 'in edit-mode';
               $workoutModel->insertActivityFeed(5, 15, $XrRecid, $feedjson);
            }else{ }
         }else{
            $feedjson['text'] = 'in edit-mode';
            $workoutModel->insertActivityFeed(5, 15, $XrRecid, $feedjson);
         }
         $formtype = 'edit';
         $this->template->content->xrid = $XrRecid;
      }elseif(!empty($XrRecid) && !is_numeric($XrRecid) && !empty($exerciseid)){
         $this->template->content->startExercise = $XrRecid;
         if($XrRecid == 'startmyxr' || $XrRecid == 'startsamplexr' || $XrRecid == 'startsharedxr'){
            $getunitdata      = $workoutModel->getExerciseById($exerciseid);
            $getmusothdata    = $workoutModel->getMusOthByUnitId($exerciseid);
            $getequipothdata  = $workoutModel->getEquipOthByUnitId($exerciseid);
            $getseqdata       = $workoutModel->getSequencesByUnitId($exerciseid);
            $gettaglist       = $workoutModel->getUnitTagsById($exerciseid);
         }
         // activity feed for open
         $activitydtl = $workoutModel->getActivityFeedDetail(array('feed_type'=>'5', 'action_type'=>'15', 'user'=>$userid, 'site_id'=>$site_id, 'type_id'=>$exerciseid));
         $feedjson = array();
         if(count($activitydtl)>0 && !empty($activitydtl)){
            $feed_time = strtotime($activitydtl[0]['created_date']);
            $curr_time = strtotime(Helper_Common::get_default_datetime());
            $hour = abs($curr_time - $feed_time)/(60*60);
            if($hour > 1){
               $feedjson['text'] = 'in edit-mode';
               $workoutModel->insertActivityFeed(5, 15, $exerciseid, $feedjson);
            }else{ }
         }else{
            $feedjson['text'] = 'in edit-mode';
            $workoutModel->insertActivityFeed(5, 15, $exerciseid, $feedjson);
         }
         $formtype = 'edit';
         $this->template->content->xrid = $exerciseid;
      }
      $this->template->content->openfrom           = $openfrom;
      $this->template->content->formtype           = $formtype;
      $this->template->content->exerciseArray      = $getunitdata;
      $this->template->content->exerciseMusOth     = $getmusothdata;
      $this->template->content->exerciseEquipOth   = $getequipothdata;
      $this->template->content->exerciseSeq        = $getseqdata;
      $this->template->content->exerciseTags       = $gettaglist;
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
   public function action_edit1()
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
            /*if($this->request->post('password') != ""){
            $user->password 		=	$this->hash($this->request->post('password'));
            }*/
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
   public function action_edit()
   {
      // $exerciseid = $this->request->param('id'); 
      $exerciseid = urldecode($this->request->param('eid'));
      echo $exerciseid;
      die;
      $exercise     = ORM::factory('admin_exercise', $exerciseid);
      $workoutModel = ORM::factory('workouts');
      //$this->redirect('exercise/exerciselibrary/'.(!empty($xrid) ? $xrid.'/#create-record' : '#create-record'));
      $getunitdata  = $workoutModel->getExerciseById($exerciseid);
      //$this->template->content->exerciseArray		= $getunitdata;
      //$this->template->title 	= 'Exercise Update';
      $this->render();
   }
   public function action_rateXrData()
   {
      if (isset($_POST) && count($_POST) > 0) {
         $unitid = $_POST["unit_id"];
         $siteid = $this->current_site_id;
         $result = Helper_Common::getRateXrData($unitid, $siteid);
         //print_r($result);
         if ($result) {
            $str = "";
            foreach ($result["comments"] as $k => $v) {
               $str .= "<div class='row listrate'>
									<div class='col-xs-12'>
										" . $v["rate_comments"] . "
									</div>
									<div class='col-xs-3 alignleft'>
										Rated : " . $v["rate_value"] . "
									</div>
									<div class='col-xs-6 alignright'>
										by " . $v["user"] . " on <span class='rateddate'>" . date("d M Y", strtotime($v["created_date"])) . "</span>
									</div>
									<div class='col-xs-3'>";
               if ($v["is_active"] == 0) {
                  $str .= "<button class='btn rate_approvebtn' id='approve" . $v["rate_id"] . "'  onclick='approve_by(" . $v["rate_id"] . ")' data-ajax='false' type='button' data-role='none'>
										<i class='fa fa-check-square-o' data-toggle='collapse' ></i>
										</button>";
               }
               $str .= "</div>
								</div>";
            }
            $res["content"] = $str;
            $res["title"]   = "Rating for " . $result["title"] . " (" . $result["rating"] . ")";
            echo json_encode($res);
         } else {
            echo 0;
         }
      }
      exit;
   }
   public function action_approveRateXrData()
   {
      if (isset($_POST) && count($_POST) > 0) {
         $rid           = $_POST["rate_id"];
         $siteid        = $this->current_site_id;
         $approved_by   = $this->globaluser->pk();
         $exercisemodel = ORM::factory('admin_exercise');
         echo $exercisemodel->approveRateXrData($approved_by, $rid);
      }
      echo false;
      exit;
   }
   public function action_defaultUnitData()
   {
      if (isset($_POST) && count($_POST) > 0) {
         $exercisemodel = ORM::factory('admin_exercise');
         $unitid        = $_POST["unit_id"];
         //$siteid = $_POST["site_id"];
         if ($unitid) { //if($unitid && $siteid){
            foreach ($unitid as $k => $v) {
               $query = DB::update('unit_gendata')->set(array(
                  'default_status' => 1
               ))->where('unit_id', '=', $v)->execute();
               //$insertid = DB::insert('unit_gendata_default', array('unit_id'))->values(array($v,$y))->execute();
               //foreach($siteid as $x=>$y){
               //echo "$v----$y<br>";
               //$checkdefault = $exercisemodel->checkdefault($v,$y);
               //if(!$checkdefault){
               //$insertid = DB::insert('unit_gendata_default', array('unit_id','site_id'))->values(array($v,$y))->execute();
               //}
               //}
            }
         }
      }
      echo true;
      exit;
   }
   public function action_browse()
   {
      $d=0; if(isset($_GET['d']) && $_GET['d']==3){ $d = 3; }
      $exercisemodel = ORM::factory('admin_exercise');
      $workoutmodel = ORM::factory('workouts');
      $this->template->title = ($d==3) ? 'Browse Shared Exercise Records' : 'Browse Exercise Records';
      $this->render();
      if (!Helper_Common::is_admin()) {
         $_POST["site_id"] = $this->current_site_id;
      }
      $lim        = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall    = $exercisemodel->get_exerciseRecordGallery($d, (isset($_POST)) ? $_POST : '', '', '');
      $cnt        = count($dataall);
      $pagination = pagination::factory(array(
         'total_items' => $cnt,
         'items_per_page' => $lim,
         // 'auto_hide' => TRUE,
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
      $data                                          = $exercisemodel->get_exerciseRecordGallery($d, (isset($_POST)) ? $_POST : '', $pagination->items_per_page, $offset);
      $this->template->content->default_status       = $d;
      $this->template->content->template_details     = $data;
      $this->template->content->template_details_all = $dataall;
      $this->template->content->pagination           = $pagination;
      $this->template->content->lim                  = $lim;
      $this->template->content->searchval            = $_POST;
      $this->template->content->status               = $exercisemodel->get_status();
      $this->template->css                           = array( 'assets/plugins/iCheck/square/green.css' );
      $this->template->js_bottom                     = array( 'assets/plugins/iCheck/icheck.js' );
   }
   public function action_sample()
   {
      if(Helper_Common::is_admin()){
         $d=1; if(isset($_GET['d']) && $_GET['d']==2){ $d = 2; }
      }else if(Helper_Common::hasAccessByDefaultXr($this->current_site_id) && !Helper_Common::is_admin()){
         $d = 'all';
      }else{
         $d = '2';
      }        
      $exercisemodel = ORM::factory('admin_exercise');
      $workoutmodel = ORM::factory('workouts');
      $this->template->title = ($d==1) ? 'Browse Default Exercise Records' : 'Browse Sample Exercise Records';
      $this->render();
      $_POST["site_id"] = $this->current_site_id; //print_r($_POST); // die;
      $users = array();
      if($d==2){
         $users1 = Helper_Common::get_role_by_users(2,$this->current_site_id); //get_role_by_users($roleid, $siteid = '')
         $users2 = Helper_Common::get_role_by_users(8,$this->current_site_id);
         if(isset($users1) && is_array($users1) && count($users1)>0)
            $users = $users1;
         if(isset($users2) && is_array($users2) && count($users2)>0)
            $users = array_merge($users,$users2);
         $users3 = Helper_Common::get_role_by_users(7,$this->current_site_id);
         if(isset($users3) && is_array($users3) && count($users3)>0)
            $users = array_merge($users,$users3);
      }else if($d==1){
         $users = Helper_Common::get_role_by_users(2);
      }
      //print_r($users); die;
      $userids = '';
      if ($users) {
         foreach ($users as $k => $v) {
            $userids[] = $v["id"];
         }
         $_POST['userids'] = implode(',', $userids);
      }
      $lim        = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
      $dataall    = $exercisemodel->get_exerciseRecordGallery($d, (isset($_POST)) ? $_POST : '', '', '');
      $cnt        = count($dataall); //echo $cnt;
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
      $data                                          = $exercisemodel->get_exerciseRecordGallery($d, (isset($_POST)) ? $_POST : '', $pagination->items_per_page, $offset);
      $this->template->content->default_status       = $d;
      $this->template->content->template_details     = $data;
      $this->template->content->template_details_all = $dataall;
      $this->template->content->pagination           = $pagination;
      $this->template->content->lim                  = $lim;
      $this->template->content->searchval            = $_POST;
      $this->template->content->status               = $exercisemodel->get_status();
      $this->template->css                           = array( 'assets/plugins/iCheck/square/green.css' );
      $this->template->js_bottom                     = array( 'assets/plugins/iCheck/icheck.js' );
   }
   
   public function action_getAdvanceSearchExerciseRecords()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      if (isset($_POST) && count($_POST) > 0) {
         $exercisemodel = ORM::factory('admin_exercise');
         $recordSet     = $exercisemodel->get_exerciseRecordGallery($_POST, '', '');
         if (isset($recordSet) && count($recordSet) > 0) {
            $table_str = '';
            foreach ($recordSet as $key => $value) {
               $taglist = '';
               $table_str .= '<tr id="row-' . $value['id'] . '">' . '<td><input type="checkbox" name="row_index[]" class="chkbox-item subscribeselect" value="' . $value['id'] . '" /></td>' . '<td><img src="' . $value['featimg'] . '" width="40" /></td>' . '<td>' . $value['name'] . '</td>' . '<td>' . $value['status'] . '</td>' . '<td>' . $value['type'] . '</td>' . '<td>' . $value['muscle'] . '</td>' . '<td>' . $value['equip'] . '</td>' . '<td>' . $value['access'] . '</td>' . '<td>';
               if (isset($value['tagdetails']) && !empty($value['tagdetails'])) {
                  $tags    = explode('@@', $value['tagdetails']);
                  $taglist = implode(", ", $tags);
               }
               $table_str .= '' . $taglist . '</td>' . '<td>
							<select id="' . $value['id'] . '" name="exerciseaction" class="exerciseaction selectActions1 ex-single-action form-control" >
							<option value="" selected="selected">Choose Action</option>
							<option value="edit">Edit this record</option>
							<option value="view">View this record</option>
							<option value="duplicate">Duplicate this record</option>
							<option value="delete">Delete this record</option>
							<option value="view_related">View related exercises</option>
							<option value="tag">Tag this record</option>
							<option value="rate">Rate this exercise</option>
							<option value="share" disabled="disabled">Share this record </option>
							<option value="feedback">Feedback for this record</option>
							<option value="default">Set As Default</option>
							</select>
						
						</td>
						
					</tr>';
            }
            //echo '<pre>';print_r($recordSet);echo '</pre>';
            $data['message'] = $table_str;
         } else {
            $data['message'] = 'No records Found';
         }
         $this->response->body(json_encode($data));
      }
   }
   function action_report_email()
   {
      $user_detail   = Auth::instance()->get_user();
      $to_email      = $this->request->post('email_address');
      $siteid        = Session::instance()->get('current_site_id');
      $sitename      = Session::instance()->get('current_site_name');
      $exercisemodel = ORM::factory('admin_exercise');
      $default       = $this->request->post('d_status');
	  $xrids	     = $this->request->post('exe');
      $content       = $exercisemodel->get_exerciseRecordGallery($default,'','','',$xrids);
      $config        = Kohana::$config->load('emailsetting');
      $from_address  = $user_detail->user_email;
      $from_name     = $sitename;
	  $title         = '"' . $sitename . '"'.($default == '1' ? ' Default' : ($default == '2' ? ' Sample' : ($default == '3' ? ' Shared' : ' My'))).' Exercise Records List';
      $report        = $this->_Gst_exercise_report_content($content, $title);
      $subject       = '"' . $sitename . '"'.($default == '1' ? ' Default' : ($default == '2' ? ' Sample' : ($default == '3' ? ' Shared' : ' My'))).' Exercise Records List';
      /*activity feed*/
      $feedtype = ($default == '0' ? '23' : ($default == '1' ? '25' : ($default == '2' ? '24' : ($default == '3' ? '26' : ''))));
	  $action_type = '43';
	  if( isset($xrids) && !empty($xrids)){
		$action_type = '46';
	  }
      $feedjson = 'Email';
      $exercisemodel->insertActivityFeed($feedtype, $action_type, 0, $feedjson);

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
   function _Gst_exercise_report_content($content, $title)
   {
      if (empty($content)) {
         return false;
      }
      $msg = '<h2>' . $title . '</h2><table cellspacing="8" cellpadding="5" border="1" width="100%">';
      $msg .= '<thead><tr><td style="height:25px; vertical-align:middle; width:20%; padding-left:5px;">Title</td><td style="height:25px; vertical-align:middle; width:20%; padding-left:5px;">Status</td><td style="height:25px; vertical-align:middle; width:20%; padding-left:5px;">Type</td><td style="width:20%; vertical-align:middle; padding-left:5px;">Primary</td><td style="height:25px; width:20%; vertical-align:middle; padding-left:5px;">Equipment</td><td style="height:25px; vertical-align:middle; width:20%; padding-left:5px;">Tags</td></tr></thead><tbody>';
      foreach ($content as $key => $value) {
         // if ($key > 500)
            // break;
         $msg .= '<tr>';
         $msg .= '<td>' . $value['name'] . '</td>';
         $msg .= '<td>' . substr($value['status'], 0, 3) . '</td>';
         $msg .= '<td>' . $value['type'] . '</td>';
         $msg .= '<td>' . $value['muscle'] . '</td>';
         $msg .= '<td>' . $value['equip'] . '</td>';
         if (isset($value['tagdetails']) && !empty($value['tagdetails'])) {
            $tags = explode('@@', $value['tagdetails']);
            $msg .= '<td>' . implode(", ", $tags) . '</td>';
         }else{
            $msg .= '<td> </td>';
         }
         $msg .= '</tr>';
      }
      $msg .= '</tbody></table>';
      return $msg;
   }
   public function action_get_exercise_report_as_excel()
   {
      $authid        = $this->globaluser->pk();
      $roleid        = $this->request->param('id');
      $siteid        = Session::instance()->get('current_site_id');
      $user          = ORM::factory('admin_user');
      $sitename      = Session::instance()->get('current_site_name');
      $exercisemodel = ORM::factory('admin_exercise');
      $default       = (isset($_GET["default"])) ? $_GET["default"] : 0;
	  $xrids	     = (isset($_GET["exe"])) ? $_GET["exe"] : '';
      $online_report = $exercisemodel->get_exerciseRecordGallery($default,'','','',$xrids);
      /*activity feed*/
      $feedtype = ($default == '0' ? '23' : ($default == '1' ? '25' : ($default == '2' ? '24' : ($default == '3' ? '26' : ''))));
      $feedjson = 'Excel';
	  $action_type = '43';
	  if( isset($xrids) && !empty($xrids)){
		$action_type = '46';
	  }
      $exercisemodel->insertActivityFeed($feedtype, $action_type, 0, $feedjson);
	  $file_name 		  = ($default == '1' ? 'Default' : ($default == '2' ? 'Sample' : ($default == '3' ? 'Shared' : 'My'))).'ExerciseRecords';
	  $file_title         = '"' . $sitename . '"'.($default == '1' ? ' Default' : ($default == '2' ? ' Sample' : ($default == '3' ? ' Shared' : ' My'))).' Exercise Records List';
      include("./plugins/phpexcel/Classes/PHPExcel.php");
      $objPHPExcel  = new PHPExcel();
      $serialnumber = 0;
      //Set header with temp array
      $tmparray     = array(
         "S.No",
         "Title",
         "Status",
         "Type",
         "Primary",
         "Equipment",
         "Tags"
      );
      //take new main array and set header array in it.
      $sheet = array( $tmparray );
      $tem = array();
      array_unshift($sheet, $tem);
      foreach ($online_report as $key => $value) {
         $tmparray     = array();
         $serialnumber = $serialnumber + 1;
         array_push($tmparray, $serialnumber);
         $name = $value['name'];
         array_push($tmparray, $name);
         $status = substr($value['status'], 0, 3);
         array_push($tmparray, $status);
         $type = $value['type'];
         array_push($tmparray, $type);
         $muscle = $value['muscle'];
         array_push($tmparray, $muscle);
         $equip = $value['equip'];
         array_push($tmparray, $equip);
         if (isset($value['tagdetails']) && !empty($value['tagdetails'])) {
            $tags   = explode('@@', $value['tagdetails']);
            $tagstr = implode(", ", $tags);
            array_push($tmparray, $tagstr);
         }
         array_push($sheet, $tmparray);
      }
      foreach ($sheet as $row => $columns) {
         foreach ($columns as $column => $data) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, ($row + 1), $data);
         }
      }
      header('Content-Type: application/vnd.openxmlformats-   officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
      header('Cache-Control: max-age=0');
      $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getStyle("A2:G2")->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet()->getCell('D1')->setValue($file_title);
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save('php://output');
      exit;
   }
   function action_get_exerciselist_pdf()
   {
      $this->auto_render = FALSE;
      $user          = ORM::factory('admin_user');
      $authid        = $this->globaluser->pk();
      $siteid        = Session::instance()->get('current_site_id');
      $sitename      = Session::instance()->get('current_site_name');
      $exercisemodel = ORM::factory('admin_exercise');
      $default       = (isset($_GET["default"])) ? $_GET["default"] : 0;
	  $xrids	     = (isset($_GET["exe"])) ? $_GET["exe"] : '';
      $content       = $exercisemodel->get_exerciseRecordGallery($default,'','','',$xrids);
	  $title         = '"' . $sitename . '"'.($default == '1' ? ' Default' : ($default == '2' ? ' Sample' : ($default == '3' ? ' Shared' : ' My'))).' Exercise Records List';
      $contents      = $this->_Gst_exercise_report_content($content, $title);
      if(!empty($contents)){
         $contents = $this->_generatePDF($contents, $title);
         /*activity feed*/
         $feedtype = ($default == '0' ? '23' : ($default == '1' ? '25' : ($default == '2' ? '24' : ($default == '3' ? '26' : ''))));
		 $action_type = '43';
		 if( isset($xrids) && !empty($xrids)){
			 $action_type = '46';
		 }
         $feedjson = 'PDF';
         $exercisemodel->insertActivityFeed($feedtype, $action_type, 0, $feedjson);
      }
      die();
   }
   public function _generatePDF($message, $title)
   {
      $this->auto_render = FALSE;
      include("./plugins/mpdf60/mpdf.php");
      $mpdf = new mPDF('c', 'A4', '', '', 20, 20, 20, 20, 10, 10);
      $mpdf->debug = true;
      $mpdf->SetTitle($title);
      $mpdf->SetDisplayMode('fullwidth');
      $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
      $mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
      $stylesheet = file_get_contents('plugins/mpdf60/mpdfstyletables.css');
      $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text				
      $mpdf->WriteHTML($message, 2);
      $file_name = $title . '-' . date('Ymdhis');
      $mpdf->Output($file_name . '.pdf', 'I');
   }
   public function action_assign_status_val()
   {
      $adminexercisemodel = ORM::factory('admin_exercise');
      if (isset($_POST) && count($_POST) > 0) {
         $unitid = $this->request->post('unitid');
         $result = $adminexercisemodel->get_status_by_id($unitid);
         //print_r($result); die;
         if (!empty($result)) {
            $response['success']   = true;
            $response['status_id'] = $result[0]['status_id'];
            $response['featured']  = $result[0]['featured'];
            echo json_encode($response);
            die;
         }
      }
   }
   public function action_exercise_update_status()
   {
      $adminworkoutmodel = ORM::factory('admin_exercise');
      if (isset($_POST) && count($_POST) > 0) {
         $unitid      = $this->request->post('unitid');
         $unit_status = $this->request->post('unit_status');
         $featured    = $this->request->post('featured');
         $result      = $adminworkoutmodel->exercise_update_status($unitid, $unit_status, $featured);
         echo $result;
         die;
      }
   }
   public function action_defaulthide(){
		$workoutsmodel = ORM::factory('admin_workouts');
		if (HTTP_Request::POST == $this->request->method()) {
			$this->globaluser = Auth::instance()->get_user();
			$method           = $this->request->post('f_method');
			$default          = ($this->request->post('default_status')) ? $this->request->post('default_status') : 0;
			$unitid          = $this->request->post('unitid');
			$workoutsmodel->hideDefaultRecords($this->globaluser->pk(), $this->current_site_id, $unitid,'2');
			$this->redirect('admin/exercise/sample');
		}
   }
}
