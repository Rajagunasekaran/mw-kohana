<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Ajax extends Controller_Admin_Website
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
		// $this->auto_render = FALSE;
		//echo "#".$this->random_color();
		//$user = Session::instance()->get('auth_user');
		//echo $user->user_email;
	}
	public function action_UserAdduUdateTags()
	{
		$response = array();
		$shareworkoutmodel = ORM::factory('admin_shareworkout');
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$mymodel = ORM::factory('admin_subscriber');
		if (isset($_POST) && count($_POST) > 0) {
			$userid = $_POST["userid"];
			$userid = explode(',', $userid);
			$tags   = $_POST["tags"];
			if (!empty($tags)) {
				$tagarray          = explode(",", $tags);
				$existing_usertags = $mymodel->get_user_tags($userid);
				$cuurenttags       = array();
				if (count($existing_usertags) > 0) {
					$existingtags = array();
					foreach ($existing_usertags as $xrecrd) {
						$existingtags[] = $xrecrd['tag_id'];
					}
					//Delete linked tags					
					foreach ($existingtags as $xrecrd) {
						if (in_array($xrecrd, $tagarray)) {
							$cuurenttags[] = $xrecrd;
						} else {
							//$mymodel->delete_user_tag($userid, $xrecrd);
							$res = (count($userid) == 1) ? $mymodel->delete_user_tag($userid, $xrecrd) : '';
							if ($res) {
								/******************* Activity Feed *********************/
								$activityjson["text"] = "from user";
								$activityjson["tag_id"][0] = $xrecrd;
								$activity_feed      				= array();
								$activity_feed["user"]  		= $this->globaluser->pk();
								$activity_feed["site_id"]		= $this->current_site_id;
								$activity_feed["feed_type"]   = '8';
								$activity_feed["action_type"] = '21';
								$activity_feed["type_id"]     = $userid;
								$activity_feed["json_data"]   = json_encode($activityjson);
								Helper_Common::createActivityFeed($activity_feed);								
								/******************* Activity Feed *********************/
							}
						}
					}
				}
				foreach ($tagarray as $tag) {
					if (is_numeric($tag)) {
						if (in_array($tag, $cuurenttags)) {
						} else {
							$res = $mymodel->add_user_tag($tag, $userid);
							if ($res) {
								/******************* Activity Feed *********************/
								foreach ($userid as $kx => $vx) {
									$activity_feed                  = array();
									$tt                             = array();
									$tt[]                           = $tag;
									$activity_feed      				= array();
									$activity_feed["user"]  		= $this->globaluser->pk();
									$activity_feed["site_id"]		= $this->current_site_id;
									$activity_feed["feed_type"]   = '2';
									$activity_feed["action_type"] = '20';
									$activity_feed["type_id"]     = $userid;
									$activity_feed["json_data"]   = json_encode($tt);
									Helper_Common::createActivityFeed($activity_feed);
								}
								/******************* Activity Feed *********************/
							}
						}
					} else {
						$data['tag_title']  = $tag;
						$data['tag_cat_id'] = 2;
						$data['created_by'] = $this->globaluser->pk();
						$tagid              = $mymodel->add_tag($data);
						if (isset($tagid)) {
							/******************* Activity Feed *********************/
							$activityjson["text"] = "for user";
							$activityjson["tag_id"][0] = $tagid;
							foreach ($userid as $kx => $vx) {
								$activityjson["user_id"][] = $vx;
							}
							$activity_feed      				= array();
							$activity_feed["user"]  		= $this->globaluser->pk();
							$activity_feed["site_id"]		= $this->current_site_id;
							$activity_feed["feed_type"]   = '8';
							$activity_feed["action_type"] = '1';
							$activity_feed["type_id"]     = $tagid;
							$activity_feed["json_data"]   = json_encode($activityjson);    // if need to encode data and store
							Helper_Common::createActivityFeed($activity_feed);
							/******************* Activity Feed *********************/
						}
						$res = $mymodel->add_user_tag($tagid, $userid);
						
					}
				}
			} else {
				//$mymodel->delete_user_tag($userid, '');
				(count($userid) == 1) ? $mymodel->delete_user_tag($userid, '') : '';
			}
		}
		$listtags            = $mymodel->get_listuser_tags($userid);
		$response['tags']    = $listtags;
		$response['success'] = true;
		echo $this->request->response = json_encode($response);
	}
	public function action_getusertags()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_GET) && count($_GET) > 0) {
			//$exercisemodel	= ORM::factory('admin_exercise');
			//$this->template->content->template_details	= $exercisemodel->get_exerciseRecordGallery($_POST);
			$userid    = $_GET['userid'];
			$usermodel = ORM::factory('admin_subscriber');
			$result    = $usermodel->get_user_created_tags($this->globaluser->pk(), 2);
			$usertags  = $usermodel->get_user_tags($userid);
			$ut        = array();
			if (count($usertags) > 0) {
				foreach ($usertags as $usertag) {
					$ut[] = $usertag['tag_id'];
				}
			}
			$res = array();
			if (count($result) > 0) {
				foreach ($result as $rec) {
					$data  = array(
						"id" => $rec['tag_id'],
						"text" => $rec['tag_title']
					);
					$res[] = $data;
				}
			}
			$response              = array();
			$response['tags']      = $res;
			$response['user_tags'] = implode(",", $ut);
			$response['success']   = true;
			echo $this->request->response = json_encode($response);
		}
	}
	public function action_addSitesToManager()
	{
		$response = array();
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_POST) && count($_POST) > 0) {
			$userid = $_POST['userid'];
			$sql    = "DELETE FROM user_sites WHERE user_id=" . $userid;
			$query  = DB::query(Database::DELETE, $sql)->execute();
			$sites  = $_POST['sites'];
			if (!empty($sites)) {
				$sitesarr = explode(",", $sites);
				if (count($sitesarr) > 0) {
					foreach ($sitesarr as $site_id) {
						list($id) = DB::insert('user_sites', array(
							'user_id',
							'site_id'
						))->values(array(
							$userid,
							$site_id
						))->execute();
					}
				}
			}
		}
		$response['success'] = true;
		echo $this->request->response = json_encode($response);
	}
	public function action_getSitesToManager()
	{
		$response = array();
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_GET) && count($_GET) > 0) {
			$userid     = $_GET['userid'];
			$query      = DB::query(Database::SELECT, "select id, name as text from sites where is_deleted = 0 and is_active = 1");
			$sites      = $query->execute()->as_array();
			$query      = DB::query(Database::SELECT, "select * from users where id = '" . $userid . "'");
			$userinfo   = $query->execute()->as_array();
			$query      = DB::query(Database::SELECT, "select site_id from user_sites where user_id = '" . $userid . "'");
			$user_sites = $query->execute()->as_array();
			$us         = array();
			if (count($user_sites) > 0) {
				foreach ($user_sites as $site) {
					$us[] = $site['site_id'];
				}
			}
			$response['userinfo']   = $userinfo;
			$response['source']     = $sites;
			$response['user_sites'] = implode(",", $us);
			$response['success']    = true;
			//$usermodel = ORM::factory('admin_subscriber');
			//$result = $usermodel->get_user_created_tags($this->session->get('auth_user'),2);
			//$usertags = $usermodel->get_user_tags($userid);
		}
		echo $this->request->response = json_encode($response);
	}
	public function action_getuserstatus()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$response = array();
		if (isset($_GET) && count($_GET) > 0) {
			$siteid                      = isset($this->current_site_id) ? $this->current_site_id : 1;
			$userid                      = $_GET['userid'];
			$usermodel                   = ORM::factory('admin_subscriber');
			$userstatus                  = $usermodel->get_user_current_status($userid, $siteid);
			$user_status_all             = $usermodel->get_user_all_status();
			$response['user_status_all'] = $user_status_all;
			$response['user_status']     = $userstatus;
			$response['success']         = true;
		}
		echo $this->request->response = json_encode($response);
	}
	public function action_UserUpdateStatus()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$response = array();
		if (isset($_POST) && count($_POST) > 0) {
			//echo "<pre>";print_r($_POST);die();
			$userid    = $_POST['userid'];
			$status    = $_POST['status'];
			$siteid    = (isset($this->current_site_id) ? $this->current_site_id : '');
			if($siteid){
				$usermodel = ORM::factory('admin_subscriber');
				$usermodel->update_user_stats($userid, $siteid, $status);
				$response['success'] = true;
				$response['message'] = 'Status updated successfully';
				/******************* Activity Feed *********************/
				/*$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 14;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $user->pk(); // user id
				$activity_feed["site_id"]  		= $this->session->get('current_site_id');
				Helper_Common::createActivityFeed($activity_feed);*/
				/******************* Activity Feed *********************/
			}
		}
		echo $this->request->response = json_encode($response);
	}
	public function action_WkoutAdduUdateTags()
	{
		$response          = array();
		$siteid = $this->current_site_id;
		$shareworkoutmodel = ORM::factory('admin_shareworkout');
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$mymodel = ORM::factory('admin_workouts');
		if (isset($_POST) && count($_POST) > 0) {
			$wkoutid = $_POST["wkoutid"];
			$wkoutid = explode(',', $wkoutid);
			//print_r($wkoutid); exit;
			$tags    = $_POST["tags"];
			if (!empty($tags)) {
				$tagarray          = explode(",", $tags);
				$existing_usertags = $mymodel->get_wkout_tags($wkoutid,$siteid);
				$cuurenttags       = array();
				if (count($existing_usertags) > 0) {
					$existingtags = array();
					foreach ($existing_usertags as $xrecrd) {
						$existingtags[] = $xrecrd['tag_id'];
					}
					//Delete linked tags					
					foreach ($existingtags as $xrecrd) {
						if (in_array($xrecrd, $tagarray)) {
							$cuurenttags[] = $xrecrd;
						} else {
							//$mymodel->delete_wkout_tag($wkoutid,$xrecrd);
							$res = '';
							if(count($wkoutid) == 1){
								$res = $mymodel->delete_wkout_tag($wkoutid, $xrecrd);
								/*********************Activiy Feed**********************/
								$activity_feed = array();
								$activity_feed["feed_type"]   = 8;  
								$activity_feed["action_type"]  = 21; 
								$activity_feed["type_id"]    = $wkoutid;
								$activity_feed["user"]     = $this->globaluser->pk();
								$activityjson["text"] = "from workout plan";
								$activityjson["tag_id"][] = $xrecrd;
								$activity_feed["json_data"]     = json_encode($activityjson);
								$activity_feed["site_id"]  		= $this->current_site_id;
								Helper_Common::createActivityFeed($activity_feed);
								/*********************Activiy Feed**********************/
							}
						}
					}
				}
				
				foreach ($tagarray as $tag) {
					if (is_numeric($tag)) {
						if (in_array($tag, $cuurenttags)) {
						} else {
							
							$res = $mymodel->add_wkout_tag($tag, $wkoutid,$siteid);
							if ($res) {
								/******************* Activity Feed *********************/
								foreach ($wkoutid as $kx => $vx) {
									$activity_feed      				= array();
									$activity_feed["user"]  		= $this->globaluser->pk();;
									$activity_feed["site_id"]		= $this->current_site_id;
									$activity_feed["feed_type"]   = '2';
									$activity_feed["action_type"] = '20';
									$activity_feed["type_id"]     = $vx;
									$tt[]                         = $tag;
									$activity_feed["json_data"]   = json_encode($tt); // if need to encode data and store
									Helper_Common::createActivityFeed($activity_feed);

									
								}
								/******************* Activity Feed *********************/
							}
						}
					} else {
						$data['tag_title']  = $tag;
						$data['tag_cat_id'] = 4;
						$data['created_by'] = $this->globaluser->pk();
						$tagid              = $mymodel->add_tag($data);
						if (isset($tagid)) {
							/******************* Activity Feed *********************/
							$activity_feed                  = array();
							$activity_feed["feed_type"]     = 8;
							$activity_feed["action_type"]   = 1;
							$activity_feed["user"]         = $this->globaluser->pk();
							$activity_feed["site_id"]      = $this->current_site_id;
							$activityjson["text"] = "for workout plan";
							$activityjson["tag_id"][0] = $tagid;
							$activity_feed["json_data"]     = json_encode($activityjson);  
							foreach ($wkoutid as $kx => $vx) {
								$activity_feed["type_id"]       = $vx;
								Helper_Common::createActivityFeed($activity_feed);
							}
							/******************* Activity Feed *********************/
						}
						$res = $mymodel->add_wkout_tag($tagid, $wkoutid,$siteid);
					}
				}
			} else {
				if(count($wkoutid) == 1) {
					$mymodel->delete_wkout_tag($wkoutid, '');
				}
			}
		}
		$listtags            = $mymodel->get_listwkout_tags($wkoutid);
		//print_r($listtags);
		$response['tags']    = $listtags;
		$response['success'] = true;
		echo $this->request->response = json_encode($response);
	}
	
	public function action_ExerciseAdduUdateTags()
	{ 
		$response          = array();
	  	$siteid = $this->current_site_id;
		$shareworkoutmodel = ORM::factory('admin_shareworkout');
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$mymodel = ORM::factory('admin_exercise');
		if (isset($_POST) && count($_POST) > 0) {
			$exerciseid = $_POST["exerciseid"];
			$exerciseid = explode(',', $exerciseid);
			//print_r($exerciseid); exit;
			$tags = $_POST["tags"];
			if (!empty($tags)) { // if tag posted
				$tagarray          = explode(",", $tags);
				$existing_usertags = $mymodel->get_exercise_tags($exerciseid, $siteid); // get all the tags for xr id
				$cuurenttags       = array();
				if (count($existing_usertags) > 0) { // if xr id has tag
					$existingtags = array();
					foreach ($existing_usertags as $xrecrd) {
						$existingtags[] = $xrecrd['tag_id'];
					}
					//Delete linked tags
					$tag_ids = array(); $feedjson = array();
					foreach ($existingtags as $xrecrd) {
						if (in_array($xrecrd, $tagarray)) { // for add tag
							$cuurenttags[] = $xrecrd;
						} else { // for delete existing tag
							$tag_ids[] = $xrecrd;
							$res = $mymodel->delete_exercise_tag($exerciseid, $xrecrd);
						}
					}
					/******************* Activity Feed *********************/
					if(!empty($tag_ids) && count($tag_ids) > 0){
						$feedjson["text"] = "from exercise record";
						$feedjson["tag_id"] = $tag_ids;
						if(count($exerciseid) == 1) {
							$unit_id = (is_array($exerciseid)) ? implode(',', $exerciseid) : $exerciseid;
							$mymodel->insertActivityFeed(8, 2, $unit_id, $feedjson);
						}else{
							foreach ($exerciseid as $kx => $vx) {
								$mymodel->insertActivityFeed(8, 2, $vx, $feedjson);
							}
						}
					}
					/******************* Activity Feed *********************/
				}
				//Adding tags
				$tag_ids = array(); $feedjson = array();
				foreach ($tagarray as $tag) {
					if (is_numeric($tag)) { // add tag to xr
						if (in_array($tag, $cuurenttags)) {
							$res = true;
						} else {
							$res = $mymodel->add_exercise_tag($tag, $exerciseid, $siteid);
							$tag_ids[] = $tag;
						}
					} else { // insert new tag and add to xr
						$data['tag_title']  = $tag;
						$data['tag_cat_id'] = 1;
						$data['created_by'] = $this->globaluser->pk();
						$tagid = $mymodel->add_tag($data);
						$res = $mymodel->add_exercise_tag($tagid, $exerciseid, $siteid);
						$tag_ids[] = $tagid;
					}
				}
				/******************* Activity Feed *********************/
				if ($res && !empty($tag_ids) && count($tag_ids) > 0) {
					foreach ($exerciseid as $kx => $vx) {
						$feedjson["text"] = "for exercise record";
						$feedjson["tag_id"] = $tag_ids;
						$mymodel->insertActivityFeed(8, 1, $vx, $feedjson);
					}
				}
				/******************* Activity Feed *********************/
			} else { // if no tag posted
				$tag_ids = array(); $feedjson = array();
				$existing_usertags = $mymodel->get_exercise_tags($exerciseid,$siteid);
				if (count($existing_usertags) > 0) {
					foreach ($existing_usertags as $xrecrd) {
						$tag_ids[] = $xrecrd['tag_id'];
						$res = $mymodel->delete_exercise_tag($exerciseid, $xrecrd['tag_id']);
					}
					/******************* Activity Feed *********************/
					if(!empty($tag_ids) && count($tag_ids) > 0){
						$feedjson["text"] = "from exercise record";
						$feedjson["tag_id"] = $tag_ids;
						if(count($exerciseid) == 1) {
							$unit_id = (is_array($exerciseid)) ? implode(',', $exerciseid) : $exerciseid;
							$mymodel->insertActivityFeed(8, 2, $unit_id, $feedjson);
						}else{
							foreach ($exerciseid as $kx => $vx) {
								$mymodel->insertActivityFeed(8, 2, $vx, $feedjson);
							}
						}
					}
					/******************* Activity Feed *********************/
				}
			}
		}
		$listtags            = $mymodel->get_listexercise_tags($exerciseid);
		//print_r($listtags);
		$response['tags']    = $listtags;
		$response['success'] = true;
		echo $this->request->response = json_encode($response);
	}
	
	public function action_exactions(){
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$exerciseModel = ORM::factory('admin_exercise'); 
		if(isset($_POST) && count($_POST)>0) {
			$unit_id = $_POST["id"]; // print_r($_POST); die;
			$method  = $_POST["action"];
			$type    = $_POST["action_type"]; 
			if($unit_id !='' && $method !=''){
				if($method =='copy' || $method =='de_copy' || $method =='delete' || $method =='sample' ){   //|| $method =='default'
					if($exerciseModel->CopyDeleteExerciseRecById('exerciseRecord', $unit_id, $this->current_site_id, $method, $type)){
						$response['success'] = true;
						$response['action']	 = $method;
						echo $this->request->response = json_encode($response);
					}
				}else if($method =='default'){
					if($exerciseModel->CopyDeleteExerciseRecById('exerciseRecord', $unit_id, $this->current_site_id, $method, $type, 1)){
						$response['success'] = true;
						$response['action']	 = $method;
						echo $this->request->response = json_encode($response);
					}
				}/*else if($method =='sample'){
					if($exerciseModel->DefaultExerciseRecById('exerciseRecord', $unit_id, $this->current_site_id, $method,$type,2)){
						$response['success'] = true;
						$response['action']	 = $method;
						echo $this->request->response = json_encode($response); die;
					}
				}*/
			}
		}
		
	}
	
	
	public function action_getwkouttags()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_GET) && count($_GET) > 0) {
			$userid       = $_GET['wkoutid'];
			$workoutmodel = ORM::factory('admin_workouts');
			$result       = $workoutmodel->get_user_created_tags($this->globaluser->pk(), 4);
			$siteid 	  = $this->current_site_id;
			$usertags     = $workoutmodel->get_wkout_tags($userid,$siteid);
			$ut           = array();
			if (count($usertags) > 0) {
				$d = 0;
				foreach ($usertags as $usertag) {
					$ut[] = $usertag['tag_id'];
					$d++;
				}
			}
			$res = array();
			if (count($result) > 0) {
				foreach ($result as $rec) {
					$data  = array(
						"id" => $rec['tag_id'],
						"text" => $rec['tag_title']
					);
					$res[] = $data;
				}
			}
			$response              = array();
			$response['tags']      = $res;
			$response['user_tags'] = $ut;
			$response['success']   = true;
			echo $this->request->response = json_encode($response);
		}
	}
	public function action_getexercisetags()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_GET) && count($_GET) > 0) {
			$unitid       = $_GET['exerciseid']; 
			$exercisemodel = ORM::factory('admin_exercise');
			$result       = $exercisemodel->get_user_created_tags($this->globaluser->pk(), 1);
		 	$siteid       = $this->current_site_id;
			$usertags     = $exercisemodel->get_exercise_tags($unitid,$siteid);
			$ut           = array();
			if (count($usertags) > 0) {
				$d = 0;
				foreach ($usertags as $usertag) {
					$ut[] = $usertag['tag_id'];
					$d++;
				}
			}
			$res = array();
			if (count($result) > 0) {
				foreach ($result as $rec) {
					$data  = array(
						"id" => $rec['tag_id'],
						"text" => $rec['tag_title']
					);
					$res[] = $data;
				}
			}
			$response              = array();
			$response['tags']      = $res;
			//$response['user_tags'] = implode (",", $ut);
			$response['user_tags'] = $ut;
			$response['success']   = true;
			echo $this->request->response = json_encode($response);
		}
	}
	public function action_getuserdetails()
	{
		$shareworkoutmodel = ORM::factory('admin_shareworkout');
		if (isset($_POST) && count($_POST) > 0) {
			$y    = $this->request->post('userids');
			$user = $shareworkoutmodel->getuserdetails($y);
			if ($user) {
				echo json_encode($user);
			}
		}
		echo false;
		exit;
	}
	
	public function action_exerciseUnitData(){ 
		$this->auto_render = FALSE;
		if(isset($_POST['xr_id']) && !empty($_POST['xr_id'])){
			$workoutModel = ORM::factory('workouts'); 
			$xrciseunitdata=$workoutModel->get_exerciseUnitData($_POST['xr_id']); 
			echo json_encode( array("items"=>$xrciseunitdata) );
		}
	}
	public function action_imgFilter(){
		$this->auto_render = FALSE;
		if(isset($_POST) && !empty($_POST)){
			$imagelibrary = ORM::factory('admin_imagelibrary');
			$filteredimg = array();
			if(!empty($_POST['fid']) || !empty($_POST['subid'])){
				$filteredimg=$imagelibrary->get_Imageslistbyfilter($_POST);
			}else{
				$filteredimg=$imagelibrary->get_Imageslistbyfilter($_POST);
			}
			echo json_encode( array("items"=>$filteredimg) );
		}
	}
	public function action_tagnames(){
		$this->auto_render = FALSE;
		$imagelibrary = ORM::factory('admin_imagelibrary');
		$tagnameslist=$imagelibrary->get_tagnames();
		$tagname = array();
		//["sports","injury risk","baseball","basketball","jumping","explosive","vertical"]
		if(count($tagnameslist) > 1){
			foreach($tagnameslist as $keys => $values)
				$tagname[$values['tag_id']]= $values['tag_title'];
		}	
		echo json_encode( array("tagnames"=>$tagname) );
	}
	public function action_getAjaxShowMoreImages(){
		$this->auto_render = FALSE;
		$imagelibrary 	= ORM::factory('admin_imagelibrary');
		$siteid 		= $this->current_site_id;
		$folderid 		= (isset($_GET['fid']) && !empty($_GET['fid']) ? $_GET['fid'] : '');
		$subfolderid 	= (isset($_GET['subfid']) && !empty($_GET['subfid']) ? $_GET['subfid'] : '');
		$slimit 		= (isset($_GET['slimit']) && !empty($_GET['slimit']) ? $_GET['slimit'] : '0');
		$elimit 		= (isset($_GET['elimit']) && !empty($_GET['elimit']) ? $_GET['elimit'] : '10');
		$moreitems = '';
		if(!empty($folderid) || !empty($subfolderid)){
			if($folderid == '2' || $folderid == '6')
				$subfolderid = '5';
			$moreitems = $imagelibrary->getFolderImages($subfolderid, $folderid, $siteid, $slimit, $elimit);
		}else{
		}
		echo json_encode( array("items"=>$moreitems) );
	}
	public function action_ajaxGetImageCommonTags(){
		$this->auto_render = FALSE;
		$imagelibrary = ORM::factory('admin_imagelibrary');
		if (isset($_GET) && count($_GET) > 0) {
			$imgids = $_GET['imgids'];
			$imgtags=$imagelibrary->ajaxGetImageCommonTagsById($imgids);
			$it = array();
			if (count($imgtags) > 0) {
				foreach ($imgtags as $imgtag) {
					$it[] = $imgtag['tag_title'];
				}
			}
			$response = array();
			$response['img_tags'] = implode(",", $it);
			$response['success'] = true;
			echo $this->request->response = json_encode($response);
		}
	}
	public function action_getAjaxImgLibraryHtml(){
		$this->auto_render = FALSE;
		$folderid = (isset($_POST['fid']) && !empty($_POST['fid']) ? $_POST['fid'] : '');
		$subfolderid = (isset($_POST['subfid']) && !empty($_POST['subfid']) ? $_POST['subfid'] : '');
		$imgdatamethod = (isset($_POST['process']) && !empty($_POST['process']) ? $_POST['process'] : '');
		$saveid = (isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid']) ? $_POST['curr_imgid'] : '');
		$imagelibrary = ORM::factory('admin_imagelibrary');
		$getsubfolders=array(); $getfolderitem=array(); $message='';
		$site_id = $this->current_site_id;
		if(!empty($imgdatamethod) && ($imgdatamethod == 'savecontinue' || $imgdatamethod == 'saveclose')){
			if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
				if(isset($_POST['croppedData']) && !empty($_POST['croppedData'])){
					if($imagelibrary->updateImgUrlById($_POST)){
						$message = array('flag'=>1, 'msg'=>'Image successfully updated!!!');
					}else{
						$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
					}
				}else{
					$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
				}
			}else{
				$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
			}
		}
		if(!empty($subfolderid) && !empty($folderid)){
			$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid, $site_id);
			$subfolders = '';
			$folderitem = $getfolderitem;
			$foldername = $imagelibrary->getImgFolderName($subfolderid);
		}else if(!empty($folderid)){
			if($folderid !='2' && $folderid !='6'){
				$getsubfolders = $imagelibrary->getSubImgFolder($folderid);
				if(count($getsubfolders)>0){
					$subfolders = $getsubfolders;
					$foldername = $imagelibrary->getImgFolderName($folderid);
				}
				if(empty($subfolderid) && count($getsubfolders)<=0){
					$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid, $site_id);
					$subfolders = '';
					$folderitem = $getfolderitem;
					$foldername = $imagelibrary->getImgFolderName($folderid);
				}
			}else{
				$getfolderitem = $imagelibrary->getFolderImages('5', $folderid, $site_id);
				$subfolders = '';
				$folderitem = $getfolderitem;
				$foldername = $imagelibrary->getImgFolderName($folderid);
			}
		}else{
			$partentfolder = $imagelibrary->getParentImgFolder();
			if(isset($partentfolder) && count($partentfolder) > 0){
				$parentFoldertemp = $partentfolder;
				foreach($parentFoldertemp as $keys => $values){
					if($values['folder_id'] == '1')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 1);
					else if($values['folder_id'] == '2')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 2);
					else if($values['folder_id'] == '3')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 3);
					else if($values['folder_id'] == '6')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 6);
				}
			}
		}
		if(!isset($partentfolder) && !empty($folderid) && empty($subfolderid)){
			$profileimgcnt = $imagelibrary->getImgCountByFolder(4, $folderid);
			$exerciseimgcnt = $imagelibrary->getImgCountByFolder(5, $folderid);
		}
		$response = '<!-- forlder & listing start -->
		<div class="mdl_img-lib-folder identityclass'.$folderid.'">
			<div id="sticky-header">
				<div class="row">
					<div class="page-head">
						<div class="col-xs-3 aligncenter">
							<a href="javascript:void(0);" id="mdl_folderBack" title="'.__("Back").'" data-ajax="false" data-role="none">
								<i class="fa fa-caret-left iconsize"></i>
							</a>
						</div>
						<div class="col-xs-6 aligncenter centerheight page-title">';
							if(!empty($foldername) && count($foldername > 0)) {
								$response .= __(ucfirst($foldername[0]['folder_title']));
							}else{
								$response .= __("Images");
							}
							if($folderid!='' && $folderid!=1 || $subfolderid!=''){
								$searchclass = '';
							}else{
								$searchclass = ' hide';
							}
							if($subfolderid!=''){
								$currentfolder = $subfolderid;
							}else{
								$currentfolder = $folderid;
							}
							$response .= '<input type="hidden" id="mdl_parentFolderId" name="mdl_parentFolderId" value="'.$folderid.'">
							<input type="hidden" id="mdl_subFolderId" name="mdl_subFolderId" value="'.$subfolderid.'">
							<input type="hidden" id="mdl_currentFolderId" name="mdl_currentFolderId" value="'.$currentfolder.'">
						</div>
						<div class="col-xs-3 aligncenter">
							<div class="filter-search'.$searchclass.'" title="'.__("Search Images").'"> 
								<a href="#" data-toggle="modal" data-target="#mdl_popupfilteract-modal" data-ajax="false" data-role="none"><i class="fa fa-search iconsize2"></i></a>
							</div>
						</div>
					</div>
				</div>
				<hr>';
				if(isset($folderitem['itemlist']) && count($folderitem['itemlist'])>0){
					if($folderitem['itemlist'][0]['parentfolder_id']==1){
						$response .='<div class="listing-header header-toggle">
							<div class="row">
								<div class="col-xs-12">
									<a href="javascript:void(0);" class="upload-image" title="'.__("Upload Images").'" onclick="popuptriggerSelectFolderModal();" data-ajax="false" data-role="none">
										<div class="col-xs-9 header-cell aligncenter">
											<i class="fa fa-plus iconsize2 activedatacol"></i>
										</div>
									</a>
									<div class="col-xs-3 header-cell aligncenter datacol">
										<i class="fa fa-list-ul iconsize2 datacol"></i>
									</div>
								</div>
							</div>
							<hr>
						</div>';
					}
				}
			$response .= '</div>'; //sticky header end
			$class = $class1 = '';
			if(!empty($folderitem['itemlist']) || (isset($foldername) && ($folderid!=1))){
				$class = "hide";
			}
			if(!empty($partentfolder) && count($partentfolder > 0)){ 
				$class1 = 'activedatacol';
			}else{ 
				$class1 = 'activedatacol';
			}
			$response .= '<div id="mdl_imgupload-link" class="'.$class.'">
				<div class="row">
					<a href="javascript:void(0);" id="mdl_trigger-uploader" class="upload-image" title="'.__("Upload Images").'" onclick="popuptriggerSelectFolderModal();" data-ajax="false" data-role="none">
						<div class="col-xs-12">
							<div class="header-cell">
								<div class="col-xs-3 aligncenter">
									<i class="fa fa-plus iconsize2 '.$class1.'"></i>
								</div>
								<div class="col-xs-6 '.$class1.'">'.__("Upload Images").'</div>
								<div class="col-xs-3"></div>
							</div>
						</div>
					</a>
				</div>
				<hr>
			</div>'; // mdl_imgupload-link end
			$hideclass = $class = $class1 = '';
			if(!empty($partentfolder) && count($partentfolder) > 0){ }else{ $hideclass .= "hide"; }
			$response .='<div id="mdl_parentfolder-div" class="'.$hideclass.'">';
				if(!empty($partentfolder) && count($partentfolder > 0)){ 
					$foldercount=count($partentfolder)-1;
					foreach($partentfolder as $key => $value){
						$response .=' <div class="row">';
							if(empty($value['countval']) || $value['countval'] == 0){ $class = 'datacol'; $class1 = ''; }else{ $class = 'activedatacol'; $class1 = 'f-parent'; }
							$p_imgcnt = number_format($value['countval']);
							$response .='<a href="javascript:void(0);" id="'.$value['folder_id'].'" class="mdl_folderclk-btn '.$class1.'" data-ajax="false" data-role="none">
								<div class="col-xs-12 page-head-row">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-folder-o iconsize2 folderclick '.$class.'"></i>
									</div>
									<div class="col-xs-6 folderclick '.$class.'">'.__(ucfirst($value['folder_title'])).'&nbsp;('.$p_imgcnt.')</div>
									<div class="col-xs-3"></div>
								</div>
							</a>
						</div>';
						if($foldercount!=$key){ $response .='<hr>'; }
					}
				}
			$response .='</div>'; // mdl_parentfolder-div end
			$hideclass = $class = $class1 = '';
			if(!empty($subfolders) && count($subfolders) > 0){ }else{ $hideclass .= "hide"; }
			$response .='<div id="mdl_subfolder-div" class="'.$hideclass.'">';
				if(!empty($subfolders) && count($subfolders > 0)){ 
					$subfoldercount=count($subfolders)-1;
					foreach($subfolders as $subkey=>$subvalue){
						$response .='<div class="row">';
							if($subvalue['folder_id']!=4){ $class = 'activedatacol'; $class1 = 'f-child'; }else{ $class = 'datacol'; $class1 = ''; }
							if($subvalue['folder_id']==4){ $s_imgcnt = number_format($profileimgcnt); }
							elseif($subvalue['folder_id']==5){ $s_imgcnt = number_format($exerciseimgcnt); }
							$response.='<a href="javascript:void(0);" id="'.$subvalue['folder_id'].'" class="mdl_folderclk-btn '.$class1.'" data-ajax="false" data-role="none">
								<div class="col-xs-12 page-head-row">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-folder-o iconsize2 folderclick '.$class.'"></i>
									</div>
									<div class="col-xs-6 folderclick '.$class.'">'.__(ucfirst($subvalue['folder_title'])).'&nbsp;('.$s_imgcnt.')</div>
									<div class="col-xs-3"></div>
								</div>
							</a>
						</div>';
						if($subfoldercount!=$subkey){ $response .='<hr>'; }
					}
				}
			$response .='</div>'; // mdl_subfolder-div end
			if(isset($folderitem['itemlist']) && count($folderitem['itemlist'])>0){ $item = '';
				$response .= '<!-- img item listing start--><input type="hidden" id="filter_fid" name="filter_fid" value="'.$folderitem['itemlist'][0]['parentfolder_id'].'">
				<input type="hidden" id="filter_subfid" name="filter_subfid" value="'.$folderitem['itemlist'][0]['subfolder_id'].'">
				<ul class="img-listing" id="mdl_img_listing">';
				foreach($folderitem['itemlist'] as $keys => $values){
					$dummyicon = '';
					if(empty($values['img_url'])||!file_exists($values['img_url'])){
						$dummyicon = '<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>';
					}
					$attributes = 'data-itemid="'.$values['img_id'].'" data-itemname="'.ucfirst($values['img_title']).'" data-itemurl="'.$values['img_url'].'" data-itemtype="folder"';
					$response .= '<li class="imgRecord" id="'.$values['img_id'].'">
						<div class="imgRecordDataFrame col-xs-12 col-sm-12">
							<a href="javascript:void(0);" class="col-xs-10 col-sm-10 imgFrame-left" data-ajax="false" data-role="none">
								<div class="col-xs-4 col-sm-4 mdl_thumb-img" '.$attributes.' onclick="popuptriggerImgPrevModal(this);" '.
								(!empty($values['img_url']) && file_exists($values['img_url']) ? 'style="background-image: url('.URL::base().$values['img_url'].');">' : '>'.$dummyicon).
								'</div>
								<div class="col-xs-8 col-sm-8 mdl_img-itemname" '.$attributes.' onclick="popuptriggerImgOptionModal(this);">
									<div class="altimgtitle break-img-name">'.ucfirst($values['img_title']).'</div><div class="item-info">'.$values['default'].'</div>';
									$i=0; $tags = ''; $taglist = '';
									if(!empty($values['taglist']) && count($values['taglist'])>0){
										foreach($values['taglist'] as $tagkeys => $tagvalues){ 
											if($tagvalues['img_id'] == $values['img_id']){
												if($i==0){
													$tags .= $tagvalues['tag_title']; $taglist .= $tagvalues['tag_title'];
												} else {
													$tags .= ', '.$tagvalues['tag_title']; $taglist .= ','.$tagvalues['tag_title'];
												}
											$i++;
											}
										}
										if($tags != ''){
											$response .= '<div class="img-tags"><span class="info-bold">'.__('Tags').': </span>'.$tags.'</div>';
										}
									}
								$response .= '</div>
							</a>
							<a class="col-xs-2 col-sm-2 insert-this-img text-center imgFrame-right" '.$attributes.' title="Insert this Image" data-ajax="false" data-role="none"><div class="col-xs-12 col-sm-12"><i class="fa fa-sign-in iconsize2"></i></div></a>
						</div>
					</li>';
				}
				// mdl_img_listing end
				$response .= '</ul><!-- img item listing end -->
				<div class="nothingfound" style="display: none;">
					<div class="nofiles"></div>
					<span>'.__("No image files here").'.</span>
				</div>';
				$response .= '<script type="text/javascript">
					if($("#mdl_img_listing").length){$("#mdl_img_listing").bind("scroll",function(ev){$("html, body").animate({ scrollTop: $("#mdl_img_listing").position().top }, "slow"); var scrollTop = Math.round($(this).scrollTop()); var scrollHeight = $(this)[0].scrollHeight; if (mdl_loadAjaxSend) {if (scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1) {mdl_loadAjaxSend = false; setTimeout(function() {ev.preventDefault(); if (ev.handled !== true) {ev.handled = true; popuptriggerShowMoreImage(ev); } }, 200); } } }); if(getBrowserZoomLevel() < 100){ popupAutoShowMore(); } }
				</script>';
			}
		$response .= '</div><!-- folder & listing end -->';
		echo json_encode(array('content'=>$response, 'imgid'=>$saveid, 'saveaction'=>$imgdatamethod, 'message'=>$message));
	}
	public function action_insertTagFromXrciseModal(){
		$this->auto_render = FALSE;
		$workoutModel = ORM::factory('workouts');
		$msg = ''; $xrcisetags = '';
		if(isset($_POST['action']) && $_POST['action']=='xr-tagging'){
			if(isset($_POST['xrtag-input']) && (isset($_POST['xrunitid']) && !empty($_POST['xrunitid']))){
				if($workoutModel->insertUnitTagById($_POST['xrtag-input'], $_POST['xrunitid'])==='no-tag'){
					$msg = 'fail';
				}elseif($workoutModel->insertUnitTagById($_POST['xrtag-input'], $_POST['xrunitid'])){
					$msg = 'success';
				}else{
					$msg = 'error';
				}
				$xrcisetags = $workoutModel->getUnitTagsById($_POST['xrunitid']);
			}
		}else{
			$msg = 'error';
		}
		echo json_encode(array('msg'=>$msg, 'xrtags'=>$xrcisetags));
	}
	public function action_insertRatingFromXrciseModal(){
		$this->auto_render = FALSE;
		$workoutModel = ORM::factory('workouts');
		$user_id = Auth::instance()->get_user()->pk();
		$msg = ''; $ratingflag = true;
		if(isset($_POST['action']) && $_POST['action']=='xr-rating' && isset($_POST['slider-1'])){
			$rating['unit_id'] = $_POST['unit_id'];
			$rating['rate_value'] = $_POST['slider-1'];
			$rating['rate_comments'] = $_POST['rating_msg'];
			$rating['created_date']  = $rating['modified_date'] = Helper_Common::get_default_datetime();
			$rateId = $workoutModel->insertRatingDetails($rating, $user_id);
			if(is_int($rateId)){
				$workoutModel->insertActivityFeed(5, 25, $rating['unit_id']);
				$msg = 'success';
			}else{
				$msg = 'error';
			}
			$ratingflag = $workoutModel->isUserRatedbyUnitId($rating['unit_id'], $user_id);
		}else{
			$msg = 'fail';
		}
		echo json_encode(array('msg'=>$msg, 'ratings'=>$ratingflag));
	}
	public function action_shareExerciseRecordFromPage(){
		$this->auto_render = FALSE;
		$workoutModel = ORM::factory('workouts');
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id = Auth::instance()->get_user()->pk();
		$action = $this->request->post('action');
		$unit_id = $this->request->post('xr_share_id');
		$shared_msg= $this->request->post('xr_share_msg');
		$selectedUser = $this->request->post('seletedUser');
		$selectedSite = $this->request->post('seletedSite');
		$shareArr['user_ids'] = $shareArr['site_ids'] = '';
		$msg = 'error';
		if(isset($unit_id[0])){
			$shareArr['unit_ids'] = explode(',', $unit_id[0]);
		}
		if(isset($selectedSite[0])){
			$shareArr['site_ids'] = explode(',', $selectedSite[0]);
		}
		if(isset($selectedUser[0])){
			$shareArr['user_ids'] = explode(',', $selectedUser[0]);
		}
		if(!empty($action) && $action=='sharing'){
			foreach($shareArr['unit_ids'] as $key => $unitid){
				foreach($shareArr['site_ids'] as $key => $siteid){
					foreach($shareArr['user_ids'] as $keys => $userid){
						$allsites = Helper_Common::getAllSiteIdByUser($userid);
						if(in_array($siteid, $allsites)){
							$exerciseShareId = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $unitid, 'share', array('shared_by'=>$user_id, 'shared_for'=>$userid, 'shared_msg'=>$shared_msg, 'site_id'=>$siteid));
							$sharingact = $this->_sendExerciseShareEmailToUser($exerciseShareId['shared_xrid'], $siteid, $userid, $unitid);
							if($sharingact){
								$msg = 'success';
							}
						}
					}
				}
			}
		}
		echo json_encode(array('msg'=>$msg));
	}
	public function action_ajaxInsertActivityfeed(){
		$this->auto_render = FALSE;
		if(isset($_POST) && !empty($_POST['actid'])){
			$workoutModel = ORM::factory('workouts');
			$actid = $_POST['actid'];
			$method = $_POST['method'];
			$type = $_POST['type'];
			$feedtype = $actiontype = ''; $feedjson = array();
			if($type == 'exercise'){
				$feedtype = 5;
			}elseif($type == 'image'){
				$feedtype = 9;
			}elseif($type == 'image data'){
				$feedtype = 16;
			}
			if($method == 'previewed'){
				$actiontype = 42;
			}elseif($method == 'opened'){
				$actiontype = 15;
				$feedjson['text'] = 'in edit-mode';
			}elseif($method == 'exited'){
				$actiontype = 44;
				$feedjson['text'] = 'without saving';
			}
			echo json_encode($workoutModel->insertActivityFeed($feedtype, $actiontype, $actid, $feedjson));
		}
	}
	public function _sendExerciseShareEmailToUser($sharedXrId, $site_id, $user_id, $unit_id){
		$smtpmodel    			= ORM::factory('admin_smtp');
		$shareworkoutmodel 	= ORM::factory('admin_shareworkout');
		$workoutModel			= ORM::factory('workouts');
		$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		if (isset($sharedXrId) && is_numeric($sharedXrId) && !empty($sharedXrId)) {
			$sites			= Helper_Common::hasSiteAccess($site_id);
			$user				= $shareworkoutmodel->getuserdetails($user_id);
			$user				= $user[0];
			$templateArray = $smtpmodel->getSendingMailTemplate(array(
			    'type_name' => 'notification - shared workout'
			));
			$encryptedmessage = Helper_Common::encryptPassword($user['user_email'].'####'.$user['security_code'].'####exerciserecord');
			$exerciseUrl = URL::site(NULL, 'http').(isset($sites["slug"]) ? $sites["slug"] : '')."/index/autoredirect/".$sharedXrId."/".$encryptedmessage;
			$templateArray['body'] = str_replace(array(
					'workout',
					'Workout Plan',
					'Workout Plans',
					'[trainer_name]',
					'[site_title]',
					'[share_exercise_plan_link]'
				), array(
					'exercise',
					'Exercise',
					'Exercises',
					ucfirst(strtolower(Auth::instance()->get_user()->user_fname)),
					($sites) ? $sites["name"] : '', $exerciseUrl ), $templateArray['body']
				);
			$messageArray = array(
				'subject' => str_replace(array(
					'Workout Plan',
					'[trainer_name]'
				), array(
					'Exercise',
					ucfirst(strtolower(Auth::instance()->get_user()->user_fname))
				), $templateArray['subject']),
				'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
				'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
				'to' => $user["user_email"],
				'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
				'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
				'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $current_site_id),
				'type' => 'text/html'
			);
			if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && false){
				$hostAddress = explode("://", $templateArray['smtp_host']);
				$emailMailer = Email::dynamicMailer('smtp', array(
					'hostname' => trim($hostAddress['1']),
					'port' => $templateArray['smtp_port'],
					'username' => $templateArray['smtp_user'],
					'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
					'encryption' => trim($hostAddress['0'])
				));
			}else{
				$emailMailer = Email::dynamicMailer('', array());
			}
			Email::sendBysmtp($emailMailer, $messageArray);
			/*Activity Feed */
			if(!empty($user_id)){
				$feedjson = array();
				$feedjson[] = $user_id;
				$workoutModel->insertActivityFeed(5, 7, $unit_id, $feedjson);
			}
		}
		return true;
	}
	public function action_getLanguageValue(){
		$settings_model = ORM::factory('admin_settings');
		if(isset($_POST) && count($_POST)>0) {
			$id = $_POST["id"];  
			 $result_obj    = $settings_model->get_language_value($id);  //print_r($result_obj);
			 $result    = htmlspecialchars_decode($result_obj[0]['value'], ENT_QUOTES);
			 echo json_encode( $result ); die();
		}
	}
	public function action_upadateLanguageValue(){
		$settings_model = ORM::factory('admin_settings');
		if(isset($_POST) && count($_POST)>0) {
			$value = $_POST["language_val"];
			$id = $_POST["id"]; 
			$actiontype = $_POST["actiontype"];
			$result    = $settings_model->upadate_language_value($value,$id,$actiontype);
			echo json_encode( $result ); die();
		}
	}
	public function action_addLanguageValue(){ 
		$settings_model = ORM::factory('admin_settings');
		if(isset($_POST) && count($_POST)>0) {
			$language_key = $_POST["language_key"];  
			$value = $_POST["language_val"];  
			$language_id = $_POST["language_id"];  
			$siteid = $_POST["siteid"];
			$result    = $settings_model->add_language_value($language_key,$value,$language_id,$siteid);
			echo json_encode( $result ); die();
		}
	}
	public function action_get_useremail_byid(){
		$this->auto_render = FALSE;
		$user = ORM::factory('admin_user');
		$userid = $_POST["userid"];  
		$getemail = $user->get_useremail_byid($userid);
		echo json_encode( array("email"=>$getemail) );
	}
	public function action_send_emailto_selected_user(){
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$response = array();
	  $user_detail = Auth::instance()->get_user();
	  $emailmodel = ORM::factory('admin_smtp');
		if (isset($_POST) && count($_POST) > 0) {
		$emailsubject    = $_POST['emailsubject'];
		$emailmessage    = $_POST['emailmessage'];  
		$user_list = $this->action_get_user_list();
		$template_body_Array = $emailmodel->merge_keywordsByuser($emailmessage,$user_list); //echo $template_body_Array; die;
		$currentmail    = $_POST['currentmail'];
		$config = Kohana::$config->load('emailsetting');
		$from_address= $user_detail->user_email;
		$sitename = Session::instance()->get('current_site_name');
		$from_name= $sitename;
		$email = Email::factory($emailsubject);
		$email->message($template_body_Array, 'text/html');
		$email->to($currentmail);
		$email->from($from_address, $from_name);
		if($email->send()){
			$response['success'] = true;
			$response['message'] = 'Mail Send Successfully';
		}
		}
		echo $this->request->response = json_encode($response);
	}
	public function action_get_user_list(){
		$tempmodel = ORM::factory('admin_smtp');
		$loginUser = Auth::instance()->get_user();
		if(isset($loginUser) && count($loginUser)>0) {
			$loginUserId = $loginUser->id;
			$fromEmail = $loginUser->user_email;
			$fromName = $loginUser->user_fname;
			//$this->template->content->loginUserId = $loginUserId; 
		}
		$admin_users = '';
		$admin_user_arr = $tempmodel->get_admin_user();
		if(!empty($admin_user_arr)){
			foreach ($admin_user_arr as $keys=>$values ){
				$admin_users .= $values['user_id'].',';
			}	
		}	
		$user_list = $admin_users.$loginUserId; 
		return $user_list;
	}
	public function action_get_multible_useremail_byid(){
		$this->auto_render = FALSE;
		$user = ORM::factory('admin_user');
		$userids = $_POST["userids"];  
		$getemail = $user->get_multible_useremail_byid($userids);
		echo json_encode( array("email"=>$getemail) );
	}
	public function action_send_emailto_multiple_user(){
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$response = array();
		$user_detail = Auth::instance()->get_user();
		$emailmodel = ORM::factory('admin_smtp');
		if (isset($_POST) && count($_POST) > 0) {
			$emailsubject    = $_POST['emailsubject'];
			$emailmessage    = $_POST['emailmessage'];
			$user_list = $this->action_get_user_list();
			$template_body_Array = $emailmodel->merge_keywordsByuser($emailmessage,$user_list); //echo $template_body_Array; die;
			$currentmail    = $_POST['currentmail'];
			$config = Kohana::$config->load('emailsetting');
			$from_address= $user_detail->user_email;
			$sitename = Session::instance()->get('current_site_name');
			$from_name= $sitename;
			$emailarray = explode(',',$currentmail);
			foreach ($emailarray as $key => $value) { 
				 $email = Email::factory($emailsubject);
				$email->message($template_body_Array, 'text/html');
				$email->to($emailarray[$key]);
				$email->from($from_address, $from_name);
				$email->send();
				//Email::disconnect();
			}
			$response['success'] = true;
			$response['message'] = 'Mail Send Successfully';
			echo $this->request->response = json_encode($response);
		}
	}
	public function action_WkoutUpdateStatus(){
		if (isset($_POST) && count($_POST) > 0) {
		}
	}
}